<?php

/* Convert hex color code to R, G, B */
function ddfm_hex_to_rgb($h) {
    $h = trim($h, "#");
    $color = array();    
    if (strlen($h) == 6) {
        $color[] = (int)hexdec(substr($h, 0, 2));
        $color[] = (int)hexdec(substr($h, 2, 2));
        $color[] = (int)hexdec(substr($h, 4, 2));
    } else if (strlen($h) == 3) {
        $color[] = (int)hexdec(substr($h, 0, 1) . substr($h, 0, 1));
        $color[] = (int)hexdec(substr($h, 1, 1) . substr($h, 1, 1));
        $color[] = (int)hexdec(substr($h, 2, 1) . substr($h, 2, 1));
    }
    return $color;
}

/* Check for GD support */
function ddfm_check_gd_support() {
    if (extension_loaded("gd") && (function_exists("imagegif") || function_exists("imagepng") || function_exists("imagejpeg"))) {
        return TRUE;
    } else {
        return FALSE;
    }
}


/* Check for valid URL */
function ddfm_is_valid_url($link) { 
    if (strpos($link, "http://") === FALSE) {
        $link = "http://" . $link;
    }
    $url_parts = @parse_url($link);
    if (empty($url_parts["host"])) 
        return( false );
    if (!empty($url_parts["path"])) {
        $documentpath = $url_parts["path"];
    } else {
        $documentpath = "/";
    }
    if (!empty($url_parts["query"])) {
        $documentpath .= "?" . $url_parts["query"];
    }
    $host = $url_parts["host"];
    $port = $url_parts["port"];
    if (empty($port)) 
        $port = "80";
    $socket = @fsockopen( $host, $port, $errno, $errstr, 30 );
    if (!$socket) {
        return(false);
    } else  {
        fwrite ($socket, "HEAD ".$documentpath." HTTP/1.0\r\nHost: $host\r\nUser-Agent: DDFMVerify\r\n\r\n");
        $http_response = fgets( $socket, 22 );
        if (ereg("200 OK", $http_response, $regs)) {
            return(true);
            fclose($socket);
        } else {
            return(false);
        }
    }
}


/* Check for valid email address */
function dd_is_valid_email($email) {

    $validator = new EmailAddressValidator;
    if ($validator->check_email_address($email)) {
        return TRUE;
    } else {
        return FALSE;
    }
}

    /*
        EmailAddressValidator Class
        http://code.google.com/p/php-email-address-validation/

        Released under New BSD license
        http://www.opensource.org/licenses/bsd-license.php
    */

    class EmailAddressValidator {

        /**
         * Check email address validity
         * @param   strEmailAddress     Email address to be checked
         * @return  True if email is valid, false if not
         */
         function check_email_address($strEmailAddress) {
            
            // If magic quotes is "on", email addresses with quote marks will
            // fail validation because of added escape characters. Uncommenting
            // the next three lines will allow for this issue.
            //if (get_magic_quotes_gpc()) { 
            //    $strEmailAddress = stripslashes($strEmailAddress); 
            //}

            // Control characters are not allowed
            if (preg_match('/[\x00-\x1F\x7F-\xFF]/', $strEmailAddress)) {
                return false;
            }

            // Split it into sections using last instance of "@"
            $intAtSymbol = strrpos($strEmailAddress, '@');
            if ($intAtSymbol === false) {
                // No "@" symbol in email.
                return false;
            }
            $arrEmailAddress[0] = substr($strEmailAddress, 0, $intAtSymbol);
            $arrEmailAddress[1] = substr($strEmailAddress, $intAtSymbol + 1);

            // Count the "@" symbols. Only one is allowed, except where 
            // contained in quote marks in the local part. Quickest way to
            // check this is to remove anything in quotes.
            $arrTempAddress[0] = preg_replace('/"[^"]+"/'
                                             ,''
                                             ,$arrEmailAddress[0]);
            $arrTempAddress[1] = $arrEmailAddress[1];
            $strTempAddress = $arrTempAddress[0] . $arrTempAddress[1];
            // Then check - should be no "@" symbols.
            if (strrpos($strTempAddress, '@') !== false) {
                // "@" symbol found
                return false;
            }

            // Check local portion
            if (!$this->check_local_portion($arrEmailAddress[0])) {
                return false;
            }

            // Check domain portion
            if (!$this->check_domain_portion($arrEmailAddress[1])) {
                return false;
            }

            // If we're still here, all checks above passed. Email is valid.
            return true;

        }

        /**
         * Checks email section before "@" symbol for validity
         * @param   strLocalPortion     Text to be checked
         * @return  True if local portion is valid, false if not
         */
         function check_local_portion($strLocalPortion) {
            // Local portion can only be from 1 to 64 characters, inclusive.
            // Please note that servers are encouraged to accept longer local
            // parts than 64 characters.
            if (!$this->check_text_length($strLocalPortion, 1, 64)) {
                return false;
            }
            // Local portion must be:
            // 1) a dot-atom (strings separated by periods)
            // 2) a quoted string
            // 3) an obsolete format string (combination of the above)
            $arrLocalPortion = explode('.', $strLocalPortion);
            for ($i = 0, $max = sizeof($arrLocalPortion); $i < $max; $i++) {
                 if (!preg_match('.^('
                                .    '([A-Za-z0-9!#$%&\'*+/=?^_`{|}~-]' 
                                .    '[A-Za-z0-9!#$%&\'*+/=?^_`{|}~-]{0,63})'
                                .'|'
                                .    '("[^\\\"]{0,62}")'
                                .')$.'
                                ,$arrLocalPortion[$i])) {
                    return false;
                }
            }
            return true;
        }

        /**
         * Checks email section after "@" symbol for validity
         * @param   strDomainPortion     Text to be checked
         * @return  True if domain portion is valid, false if not
         */
         function check_domain_portion($strDomainPortion) {
            // Total domain can only be from 1 to 255 characters, inclusive
            if (!$this->check_text_length($strDomainPortion, 1, 255)) {
                return false;
            }
            // Check if domain is IP, possibly enclosed in square brackets.
            if (preg_match('/^(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])'
               .'(\.(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])){3}$/'
               ,$strDomainPortion) || 
                preg_match('/^\[(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])'
               .'(\.(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])){3}\]$/'
               ,$strDomainPortion)) {
                return true;
            } else {
                $arrDomainPortion = explode('.', $strDomainPortion);
                if (sizeof($arrDomainPortion) < 2) {
                    return false; // Not enough parts to domain
                }
                for ($i = 0, $max = sizeof($arrDomainPortion); $i < $max; $i++) {
                    // Each portion must be between 1 and 63 characters, inclusive
                    if (!$this->check_text_length($arrDomainPortion[$i], 1, 63)) {
                        return false;
                    }
                    if (!preg_match('/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|'
                       .'([A-Za-z0-9]+))$/', $arrDomainPortion[$i])) {
                        return false;
                    }
                }
            }
            return true;
        }

        /**
         * Check given text length is between defined bounds
         * @param   strText     Text to be checked
         * @param   intMinimum  Minimum acceptable length
         * @param   intMaximum  Maximum acceptable length
         * @return  True if string is within bounds (inclusive), false if not
         */
         function check_text_length($strText, $intMinimum, $intMaximum) {
            // Minimum and maximum are both inclusive
            $intTextLength = strlen($strText);
            if (($intTextLength < $intMinimum) || ($intTextLength > $intMaximum)) {
                return false;
            } else {
                return true;
            }
        }

    }







/* Make output safe for the browser */
function ddfm_bsafe($input) {
    return htmlspecialchars(stripslashes($input));
}




function ddfm_injection_test($str) { 
    $tests = array("/bcc\:/i", "/Content\-Type\:/i", "/Mime\-Version\:/i", "/cc\:/i", "/from\:/i", "/to\:/i", "/Content\-Transfer\-Encoding\:/i"); 
    return preg_replace($tests, "", $str); 
} 



function ddfm_send_mail($recipients, $sender_name, $sender_email, $email_subject, $email_msg, $attach_save, $attach_path, $attachments = false) {

    $extra_recips = '';

    // generate recipient data from list
    if (strpos($recipients, '|')) {

        $rdata = array();
        $ri = 0;
        $rtmp = explode('|', $recipients);
        foreach ($rtmp as $rd) 
		{
            if (trim($rd) != "") 
			{
                list($m, $e) = (array)explode("=", trim($rd), 2);
                $rdata[$ri]['m'] = trim(strtolower($m));
                $rdata[$ri]['e'] = trim($e);
                $ri++;
            }
        }    

        rsort($rdata);

        $r_to = array();
        $extra_recips = "";
        foreach ($rdata as $r) 
		{ 
            if ($r['m'] == 'to') $r_to[] = $r['e'];    
            if ($r['m'] == 'cc') $extra_recips .= 'cc: ' . $r['e'] . PHP_EOL;        
            if ($r['m'] == 'bcc') $extra_recips .= 'bcc: ' . $r['e'] . PHP_EOL;    
        }
        $send_to = implode(', ', $r_to);
    
    } else {
        $send_to = trim($recipients);
    }


    $sender_name = ddfm_injection_test($sender_name);
    $sender_email = ddfm_injection_test($sender_email);
    $email_subject = ddfm_injection_test($email_subject);
    
    if (function_exists('mb_encode_mimeheader')) {
    $email_subject = mb_encode_mimeheader($email_subject, 'UTF-8', 'Q', '');
    //$sender_name = mb_encode_mimeheader($sender_name, 'UTF-8', 'Q', '');
    }


    if (trim($sender_name) == "") {
        $sender_name = 'Anonymous';
    }
    if (trim($sender_email) == "") {
        $sender_email = 'user@domain.com';
    }
    if (trim($email_subject) == "") {
        $email_subject = 'Contact Form';
    }


    $mime_boundary = md5(time()); 

    $headers = '';
    $msg = '';


    $headers .= "From: =?UTF-8?B?" . base64_encode($sender_name) . "?= <" . $sender_email . '>' . PHP_EOL;
    $headers .= $extra_recips;
    $headers .= 'Reply-To: ' . $sender_name . ' <' . $sender_email . '>' . PHP_EOL;
    $headers .= 'Return-Path: ' . $sender_name . ' <' . $sender_email . '>' . PHP_EOL;
    $headers .= "Message-ID: <" . time() . "ddfm@" . $_SERVER['SERVER_NAME'] . ">" . PHP_EOL;
    $headers .= 'X-Sender-IP: ' . $_SERVER["REMOTE_ADDR"] . PHP_EOL;
    $headers .= "X-Mailer: PHP v" . phpversion() . PHP_EOL;

    $headers .= 'MIME-Version: 1.0' . PHP_EOL;
//    $headers .= 'Content-Type: multipart/related; boundary="' . $mime_boundary . '"';
    $headers .= 'Content-Type: multipart/mixed; boundary="' . $mime_boundary . '"';

    $msg .= '--' . $mime_boundary . PHP_EOL;
    $msg .= 'Content-Type: text/plain; charset="utf-8"' . PHP_EOL;
//    $msg .= 'Content-Type: text/plain; charset="iso-8859-1"' . PHP_EOL;

    $msg .= 'Content-Transfer-Encoding: 8bit' . PHP_EOL . PHP_EOL;

    $msg .= $email_msg . PHP_EOL . PHP_EOL;

    if (count($attachments) > 0) {

        for ($i = 0; $i < count($attachments); $i++) { 

            if (is_file($attachments[$i]['tmpfile'])) {

                if ($attach_save) {

                    if (!rename($attachments[$i]['tmpfile'], $attach_path . $attachments[$i]['file'])) {
                        echo 'Error saving file. Check your path and permissions. Stopping script.';
                        exit();
                    }

                } else {

                    $handle = fopen($attachments[$i]['tmpfile'], 'rb');
                    $f_contents = fread($handle, filesize($attachments[$i]['tmpfile'])); 
                    $f_contents = chunk_split(base64_encode($f_contents));
                    fclose($handle);        

                    $msg .= '--' . $mime_boundary . PHP_EOL;
                    $msg .= 'Content-Type: application/octet-stream; name="' . $attachments[$i]['file'] . '"' . PHP_EOL;
                    $msg .= 'Content-Transfer-Encoding: base64' . PHP_EOL;
                    $msg .= 'Content-Disposition: attachment; filename="' . $attachments[$i]['file'] . '"' . PHP_EOL . PHP_EOL; 
                    $msg .= $f_contents . PHP_EOL . PHP_EOL;

                }

            }
       
        }
    }

	/*echo "!!!!!!!!!!!";
	echo $send_to;
	echo "!!!!!!!!!!!";
	echo "<br/>".PHP_EOL;
	echo $email_subject;
	echo "<br/>".PHP_EOL;
	echo $msg;
	echo "<br/>".PHP_EOL;
	echo $headers;*/
	
    $msg .= '--' . $mime_boundary . '--' . PHP_EOL . PHP_EOL;
    @ini_set('sendmail_from', $sender_email);
    $send_status = mail($send_to, $email_subject, $msg, $headers);
    @ini_restore('sendmail_from');

    return $send_status;
}




?>