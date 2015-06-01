<?php

/*
** Dagon Design Form Mailer 
**
** Version 5.8
**
** http://www.dagondesign.com/articles/secure-php-form-mailer-script/
**
** A basic explanation of each option can be found below. For full documentation,
** including advanced usage, updates, and more, please visit the web site.
**
*/

// error_reporting(E_ALL);

	if (!defined('PHP_EOL')) define ('PHP_EOL', strtoupper(substr(PHP_OS,0,3) == 'WIN') ? "\r\n" : "\n");
	//--------//added by McAngel----------------------------------------------###---------------
	include_once("mailer_functions.php");
	include_once("dd-formmailer_variables.php");
	include_once("dd-formmailer_functions.php");
	
	//include_once("dd-formmailer_functions_formOutput.php");
	//--------//added by McAngel----------------------------------------------###---------------

	if (trim($path_contact_page) == '') 
	{
		$path_contact_page = $script_path;
	}
	$verify_method = strtolower($verify_method);

	$my_form_struct = array();
	{
		$mfs_tmp = trim($manual_form_code);
		//echo "mfs_tmp=$mfs_tmp <br/> <p>END</p><!--END-->".PHP_EOL;
		$mfs_tmp = safe_str_replace("<textarea", "<input type=\"textarea\"", $mfs_tmp);
		$mfs_tmp = safe_str_replace("></textarea>", "/>", $mfs_tmp);
		//echo "mfs_tmp=$mfs_tmp <br/> <p>END</p><!--END-->".PHP_EOL;
		while(!(strpos($mfs_tmp, "<input") === false))
		{
			$n = strpos($mfs_tmp, "<input");
			//if ($n === false)
			//	break;
			$mfs_tmp = substr($mfs_tmp, $n, strlen($mfs_tmp) - $n);	//delete the part before <input
			//echo "$n : mfs_tmp=$mfs_tmp <br/> <p>END</p><!--END-->".PHP_EOL;
			$n2 = strpos($mfs_tmp, "name");
			//find name of input tag ----- Start
			$key = substr($mfs_tmp, $n2, strlen($mfs_tmp) - $n2);
			$n2 = strpos($key, "\"");
			$key = substr($key, $n2+1, strlen($mfs_tmp) - $n2 - 1);
			$n2 = strpos($key, "\"");
			$key = trim(substr($key, 0, $n2));
			$n = strpos($mfs_tmp, "/>");
			//find name of input tag ----- End
			$my_form_struct[$key] = /*safe_str_replace('"', '', */trim(substr($mfs_tmp, 6, $n - 6))/*)*/;	//6 == strlen("<input");
																								//copy input tag attributes and values
			$mfs_tmp = trim(substr($mfs_tmp, $n+2, strlen($mfs_tmp) - $n - 2));
			//echo "$n : mfs_tmp=$mfs_tmp <br/> <p>END</p><!--END-->".PHP_EOL;
		}
	}
	
	$label_list = array();
	{
		$ll_tmp = trim($manual_form_code);
		//echo "mfs_tmp=$mfs_tmp <br/> <p>END</p><!--END-->".PHP_EOL;
		//$mfs_tmp = safe_str_replace("<textarea", "<input type=\"textarea\"", $mfs_tmp);
		//$mfs_tmp = safe_str_replace("></textarea>", "/>", $mfs_tmp);
		//echo "mfs_tmp=$mfs_tmp <br/> <p>END</p><!--END-->".PHP_EOL;
		while(!(strpos($ll_tmp, "<label") === false))
		{
			$n = strpos($ll_tmp, "<label");
			//if ($n === false)
			//	break;
			$ll_tmp = substr($ll_tmp, $n, strlen($ll_tmp) - $n);	//delete the part before <input
			//echo "$n : mfs_tmp=$mfs_tmp <br/> <p>END</p><!--END-->".PHP_EOL;
			$n = strpos($ll_tmp, "for");
			//$n += 3; // strlen("for");
			$ll_tmp = substr($ll_tmp, $n, strlen($ll_tmp) - $n);
			$n = strpos($ll_tmp, "\"");
			$n += 1; // strlen("\"");
			$ll_tmp = substr($ll_tmp, $n, strlen($ll_tmp) - $n);
			$n = strpos($ll_tmp, "\"");
			$key = trim(substr($ll_tmp, 0, $n));
			$n = strpos($ll_tmp, ">");
			$n += 1; // strlen(">");
			$ll_tmp = substr($ll_tmp, $n, strlen($ll_tmp) - $n);
			$n = strpos($ll_tmp, "</label");
			$label_list[$key] = trim(strip_tags(trim(substr($ll_tmp, 0, $n))));
			//$my_form_struct[] = /*safe_str_replace('"', '', */trim(substr($mfs_tmp, 6, $n - 6))/*)*/;	//6 == strlen("<input");
																								//copy input tag attributes and values
			//$mfs_tmp = trim(substr($mfs_tmp, $n+2, strlen($mfs_tmp) - $n - 2));
			//echo "$n : mfs_tmp=$mfs_tmp <br/> <p>END</p><!--END-->".PHP_EOL;
		}
	}
	
	/*$ll_keys = array_keys($label_list);
	foreach($ll_keys as $ll_k)
	{
		echo $ll_k.": ".$label_list[$ll_k]."<br/>".PHP_EOL;
	}*/
//	foreach($my_form_struct as $mfs)
//	{
//		echo $mfs."<br/>".PHP_EOL;
//	}
	
	//from array of strings (input element attributes) to array of: Array of attributes and their values ----- #Start
	$mfs_keys = array_keys($my_form_struct);
	foreach($mfs_keys as $mfs_k)
	{
		$mfs = $my_form_struct[$mfs_k];
		$attr_arr = array();
		while(strlen($mfs) > 0)
		{
			$n1 = strpos($mfs, '=');
			$n2 = strpos($mfs, '"');
			$mfs = substr($mfs, 0, $n2) . substr($mfs, $n2+1, strlen($mfs) - $n2 - 1);
			$n3 = strpos($mfs, '"');
			$attr_arr[trim(substr($mfs, 0, $n1))] = trim(substr($mfs, $n2, $n3-$n2));
			$mfs = trim(substr($mfs, $n3+1, strlen($mfs) - $n3 - 1));
		}
		$my_form_struct[$mfs_k] = $attr_arr;
//		echo"::|".$mfs_k;
	}
	//from array of strings (input element attributes) to array of: Array of attributes and their values ----- #End
	
	/*echo "<br/>".PHP_EOL;
	$mfs_keys = array_keys($my_form_struct);
	foreach($mfs_keys as $mfs_k)
	{
		$mfs_node = $my_form_struct[$mfs_k];
		$mfs_node_keys = array_keys($mfs_node);
		$k = 0;
		echo $mfs_k."==> ";
		foreach($mfs_node_keys as $mfs_n_k)
		{
			$k++;
			echo $k.":".$mfs_n_k."=".$mfs_node[$mfs_n_k]." ";
		}
		echo "<br/>".PHP_EOL;
	}*/
	
	//change keys from 0,1,... to name values of inputs ----- #Start
	/*$mfs_keys = array_keys($my_form_struct);
	foreach($mfs_keys as $mfs_k)
	{
		$mfs = $my_form_struct[$mfs_k];
		$my_form_struct[$mfs["name"]] = $mfs;
		$my_form_struct[$mfs_k] = NULL;
	}
	$my_form_struct = array_filter($my_form_struct);*/
	//change keys from 0,1,... to name values of inputs ----- #End
	
	/*echo "<br/>".PHP_EOL;
	$mfs_keys = array_keys($my_form_struct);
	foreach($mfs_keys as $mfs_k)
	{
		$mfs_node = $my_form_struct[$mfs_k];
		$mfs_node_keys = array_keys($mfs_node);
		$k = 0;
		echo $mfs_k."==> ";
		foreach($mfs_node_keys as $mfs_n_k)
		{
			$k++;
			echo $k.":".$mfs_n_k."=".$mfs_node[$mfs_n_k]." ";
		}
		echo "<br/>".PHP_EOL;
	}*/
	
	
	/* Generate the script output */
		
	// convert $form_struct into array of strings
	//$form_struct = (array)explode('<br />', nl2br(trim($form_struct)));
	
	// Prepare globals
	$form_submitted = FALSE;
	$message_sent = FALSE;
	
	// Prepare output
	// Convert form structure to multi-dimensional array
	/*$fs_tmp1 = array();
	$fs_tmp2 = array();
	$fitem = 0;
	
	foreach ($form_struct as $fs) 
	{
		if (trim($fs) != "") 
		{
			$fs_tmp1 = (array)explode("|", trim($fs));
			foreach ($fs_tmp1 as $fs1) 
			{
				list($k, $v) = (array)explode("=", trim($fs1), 2);	
				$fs_tmp2[$fitem][$k] = $v;
			}			
		}
		$fitem++;
	}
	$form_struct = $fs_tmp2;*/

	// Make sure form structure is not missing empty keys
	/*$valid_keys = array('fieldname', 'type', 'req', 'label', 'max', 'ver', 'confirm', 'data', 'multi', 'allowed', 'default');
	for ($i = 0; $i < count($form_struct); $i++) 
	{
		foreach ($valid_keys as $k) 
		{
			if (!isset($form_struct[$i][$k])) 
				$form_struct[$i][$k] = NULL;
		}
	}*/
	
	// Do a quick check to make sure there are no duplicate field names
	/*$dd_unique_fields = array();
	$dd_unique_test = TRUE;
	foreach ($form_struct as $fs) 
	{
		if ($dd_unique_test && ($fs['fieldname'] != NULL) && (in_array($fs['fieldname'], $dd_unique_fields))) 
		{
			$dd_unique_test = FALSE;
		} 
		else 
		{
			$dd_unique_fields[] = $fs['fieldname'];
		}
	}
	if (!$dd_unique_test) 
	{
		echo '<p>*** ERROR - You have duplicate fieldnames in your form structure ***</p>';
	}*/
	
	// Was form submitted?
	if (isset($_POST["form_submitted"])) 
	{
		$form_submitted = TRUE;
		$mail_message = "";
		$csv = "";
		$orig_auto_reply_message = $auto_reply_message;
		$auto_reply_message = '';
		
		// make correct encoding in auto - sokai - BEGIN
		$mime_boundary = md5(time());
		$auto_reply_message .= '--' . $mime_boundary . PHP_EOL;
		$auto_reply_message .= 'Content-Type: text/plain; charset="utf-8"' . PHP_EOL;
		$auto_reply_message .= 'Content-Transfer-Encoding: 8bit' . PHP_EOL . PHP_EOL;
		// make correct encoding in auto - sokai - END

		$attached_files = array();
		$attached_index = 0;
		$sel_recip = NULL;
		$message_structure = trim($message_structure);
		$auto_reply_message .= $orig_auto_reply_message;
		unset($errors);
		$errors = array();
		if ($verify_method == 'recaptcha') 
		{
			@include_once('recaptchalib.php');
			$privatekey = $re_private_key;
			/*echo $_POST["recaptcha_challenge_field"];
			echo "<br/>
			";
			echo $_POST["recaptcha_response_field"];
			echo "<br/>
			";*/
			$resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);
			if (!$resp->is_valid) 
			{
				//echo $resp->error;
				$errors[] = DDFM_INVALIDVER;
			}
		}

		$form_input = array();
		
		// Get form input and put in array
		foreach ($_POST as $key => $i) 
		{
			if ($key != "form_submitted") 
			{
				if (!is_array($i)) 
				{
					$form_input[strtolower($key)] = trim($i);
				} 
				else 
				{
					$form_input[strtolower($key)] = $i;
				}
			}
		}
		
		$msg_field_sep = ': ';
		$msg_field_line_end = "\n\n";
		$fsindex = -1;

		// Validate input
		//$form_input_keys = array_keys($form_input);
		$mfs_keys = array_keys($my_form_struct);
		//foreach($form_input_keys as $fik)
		foreach($mfs_keys as $mfs_k)
		{
			$t = correct_stripslashes($form_input[$mfs_k]);
			switch ($mfs_k)
			{
			case 'form_submitted':
				//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
				break;
			case 'fm_email':
				if ($t != "") 
				{
					if (!dd_is_valid_email($t)) 
						$errors[] = DDFM_INVALIDEMAIL . " '" . $label_list[$mfs_k] . "'";
				}
			case 'fm_name':
				if (check_specialChars_injections($t)) 
				{
					$errors[] = DDFM_INVALIDINPUT . " '" . $label_list[$mfs_k] . "'";
				}
				$tag_to_search = "<input";
			case 'fm_message':
				$tag_to_search = "<textarea";
			default:
				if ($t == "") 
				{
					$errors[] = DDFM_MISSINGFIELD . " '" . $label_list[$mfs_k] . "'";
				}
				else
				{
					if (strlen($t) > (int)$my_form_struct[$mfs_k]['maxlength']) 
					{
						$errors[] = $my_form_struct[$mfs_k]['maxlength'] . ' ' . DDFM_MAXCHARLIMIT . " '" . $label_list[$mfs_k] . "'";
					}
					$n = strpos($manual_form_code, $mfs_k);
					$replace_tmp1 = substr($manual_form_code, 0, $n);
					//echo "!!!!".$replace_tmp1."!!!!";
					if(strpos(strrev($replace_tmp1), strrev($tag_to_search)) == (strrpos($replace_tmp1, "<")))
					{
						$n = strrpos($replace_tmp1, "<");
						//echo"aaa";
					}
					else
					{
						$n_tmp = $n;
						$replace_tmp1 = substr($manual_form_code, $n + strlen($mfs_k), strlen($manual_form_code) - $n - strlen($mfs_k));
					//echo "!!!!".$replace_tmp1."!!!!";
						//echo "<<!".$mfs_k."!>>";
						$n = strpos($replace_tmp1, $mfs_k);
						//echo $n;
						$tmp = substr($replace_tmp1, 0, $n);
						//echo "!!!!"."tmp>>>".$tmp."!!!!";
						
						$n = strlen($tmp) - strpos(strrev($tmp), strrev($tag_to_search)) - strlen($tag_to_search) + $n_tmp + strlen($mfs_k);
						//echo "\$n==".$n;
						//echo"bbb".$mfs_k;
					}
					
					$replace_tmp1 = substr($manual_form_code, $n, strlen($manual_form_code) - $n);
					$n = strpos($replace_tmp1, '>');
					$replace_tmp2 = '';
					if(isset($my_form_struct[$mfs_k]['value']))
					{						
					//echo "!!!!".$replace_tmp1."!!!!";
						
						$replace_tmp1 = substr($replace_tmp1, 0, $n+1);
					//echo "!!!!".$replace_tmp1."!!!!";
						$n = strpos($replace_tmp1, "value");
						$replace_tmp2 = substr($replace_tmp1, 0, $n);
						$replace_tmp3 = substr($replace_tmp1, $n, strlen($replace_tmp1) - $n);
						$n = strpos($replace_tmp3, "\"");
						$replace_tmp4 = substr($replace_tmp3, $n+1, strlen($replace_tmp3) - $n - 1);
						$replace_tmp3 = substr($replace_tmp3, 0, $n+1);
						$n = strpos($replace_tmp4, "\"");
					//echo "!!!!".$n."!!!!";
						$replace_tmp4 = substr($replace_tmp4, $n, strlen($replace_tmp4) - $n);
						$replace_tmp2 = $replace_tmp2.$replace_tmp3.$form_input[$mfs_k].$replace_tmp4;
					//echo "!!!!".$replace_tmp2."!!!!";
						//$replace_tmp3 = safe_str_replace($my_form_struct[$mfs_k]['value'], $form_input[$mfs_k], $replace_tmp1);
					}
					else
					{
						if($tag_to_search == "<textarea")
						{
							$replace_tmp2 = substr($replace_tmp1, 0, $n+1);
							$replace_tmp3 = substr($replace_tmp1, $n+1, strlen($replace_tmp1) - $n - 1);
						//echo "!!!!".$replace_tmp3."!!!!";
							$n = strpos($replace_tmp3, '<');
							$replace_tmp3 = substr($replace_tmp3, $n, strlen($replace_tmp1) - $n);
							$n = strpos($replace_tmp3, '>');
							$replace_tmp3 = substr($replace_tmp3, 0, $n+1);
							
							$n = strpos($replace_tmp1, '>');
							$replace_tmp1 = substr($replace_tmp1, 0, $n+1);
							$replace_tmp2 = $replace_tmp2.$form_input[$mfs_k].$replace_tmp3;
						}
						else
						{
							$replace_tmp1 = substr($replace_tmp1, 0, $n+1);
							$replace_tmp2 = substr($replace_tmp1, 0, $n)."value=\"".$form_input[$mfs_k]."\">";
						}
					}
					$manual_form_code = safe_str_replace($replace_tmp1, $replace_tmp2, $manual_form_code);
				}
				
				$csv .= safe_str_replace($save_delimiter, ' ', $t) . $save_delimiter;
				$mail_message .= $label_list[$mfs_k] . $msg_field_sep . $t . $msg_field_line_end;
				$message_structure = safe_str_replace($mfs_k, $t, $message_structure);
				$auto_reply_message = safe_str_replace($mfs_k, $t, $auto_reply_message);
				$sent_message = safe_str_replace($mfs_k, ddfm_bsafe($t), $sent_message);
				
				$sender_name = safe_str_replace($mfs_k, correct_stripslashes($form_input[$mfs_k]), $sender_name);
				$sender_email = safe_str_replace($mfs_k, correct_stripslashes($form_input[$mfs_k]), $sender_email);
				$email_subject = safe_str_replace($mfs_k, correct_stripslashes($form_input[$mfs_k]), $email_subject);
				break;
			}
		}
		
		// Validate input
		/*foreach ($form_struct as $fs) 
		{
			if (!isset($form_input[$fs['fieldname']])) 
			{
				$form_input[$fs['fieldname']] = '';
			}
			$fsindex++;

			// check for fields used in vars
			if (isset($form_input[$fs['fieldname']])) 
			{
				$sender_name = safe_str_replace($fs['fieldname'], correct_stripslashes($form_input[$fs['fieldname']]), $sender_name);
				$sender_email = safe_str_replace($fs['fieldname'], correct_stripslashes($form_input[$fs['fieldname']]), $sender_email);
				$email_subject = safe_str_replace($fs['fieldname'], correct_stripslashes($form_input[$fs['fieldname']]), $email_subject);
			}

			switch ($fs['type']) 
			{
			case 'date':
				// type=date|class=|label=|fieldname=|req=(TRUEFALSE)
				$t = correct_stripslashes($form_input[$fs['fieldname']]);
				
				if ((strtolower($fs['req']) == 'true') && ($t == "")) 
				{ 
					$errors[] = DDFM_MISSINGFIELD . " '" . $fs['label'] . "'";
				} 
				else if (check_specialChars_injections($t)) 
				{
					$errors[] = DDFM_INVALIDINPUT . " '" . $fs['label'] . "'";
				}
				
				$csv .= str_replace($save_delimiter, ' ', $t) . $save_delimiter;
				$mail_message .= $fs['label'] . $msg_field_sep . $t . $msg_field_line_end;
				$message_structure = safe_str_replace($fs['fieldname'], $t, $message_structure);
				$auto_reply_message = safe_str_replace($fs['fieldname'], $t, $auto_reply_message);
				$sent_message = safe_str_replace($fs['fieldname'], ddfm_bsafe($t), $sent_message);
				break;
			case 'text':
				// type=text|class=|label=|fieldname=|max=|req=(TRUEFALSE)|[ver=]|[default=]
				$t = correct_stripslashes($form_input[$fs['fieldname']]);
				
				if ((strtolower($fs['req']) == 'true') && ($t == "")) 
				{
					$errors[] = DDFM_MISSINGFIELD . " '" . $fs['label'] . "'";
				}
				else if (strlen($t) > (int)$fs['max']) 
				{
					$errors[] = $fs['max'] . ' ' . DDFM_MAXCHARLIMIT . " '" . $fs['label'] . "'";
				}
				else if (check_specialChars_injections($t)) 
				{
					$errors[] = DDFM_INVALIDINPUT . " '" . $fs['label'] . "'";
				}
				else if ((strtolower($fs['ver']) == 'email') && ((strtolower($fs['req']) == "true") || ($t != ""))) 
				{
					if (!dd_is_valid_email($t)) 
						$errors[] = DDFM_INVALIDEMAIL . " '" . $fs['label'] . "'";
				}
				else if ((strtolower($fs['ver']) == 'url') && ((strtolower($fs['req']) == "true") || ($t != ""))) 
				{
					if (!ddfm_is_valid_url($t)) 
						$errors[] = DDFM_INVALIDURL . " '" . $fs['label'] . "'";					
				} 
					
				$csv .= str_replace($save_delimiter, ' ', $t) . $save_delimiter;
				$mail_message .= $fs['label'] . $msg_field_sep . $t . $msg_field_line_end;
				$message_structure = safe_str_replace($fs['fieldname'], $t, $message_structure);
				$auto_reply_message = safe_str_replace($fs['fieldname'], $t, $auto_reply_message);
				$sent_message = safe_str_replace($fs['fieldname'], ddfm_bsafe($t), $sent_message);
				break;
			case 'password':
				// type=password|class=|label=|fieldname=|max=|req=(TRUEFALSE)|confirm=(TRUEFALSE)
				$t = correct_stripslashes($form_input[$fs['fieldname']]);

				if ((strtolower($fs['req']) == 'true') && ($t == "")) 
				{
					$errors[] = DDFM_MISSINGFIELD . " '" . $fs['label'] . "'";
				}
				else if (strlen($t) > (int)$fs['max']) 
				{
					$errors[] = $fs['max'] . ' ' . DDFM_MAXCHARLIMIT . " '" . $fs['label'] . "'";
				} 
				else if (check_specialChars_injections($t)) 
				{
					$errors[] = DDFM_INVALIDINPUT . " '" . $fs['label'] . "'";
				}
				else if (strtolower($fs['confirm']) == 'true') 
				{
					$tc = correct_stripslashes($form_input[$fs['fieldname']  . 'c']);
					if ($t != $tc) 
						$errors[] = DDFM_NOMATCH . " '" . $fs['label'] . "'";
				}

				$csv .= str_replace($save_delimiter, ' ', $t) . $save_delimiter;
				$mail_message .= $fs['label'] . $msg_field_sep . $t . $msg_field_line_end;
				$message_structure = safe_str_replace($fs['fieldname'], $t, $message_structure);
				$auto_reply_message = safe_str_replace($fs['fieldname'], $t, $auto_reply_message);
				$sent_message = safe_str_replace($fs['fieldname'], ddfm_bsafe($t), $sent_message);
				break;
			case 'textarea':
			case 'widetextarea':
				// type=textarea|class=|label=|fieldname=|max=|rows=|req=(TRUEFALSE)|[default=]
				$t = correct_stripslashes($form_input[$fs['fieldname']]);

				if ((strtolower($fs['req']) == 'true') && ($t == "")) 
				{
					$errors[] = DDFM_MISSINGFIELD . " '" . $fs['label'] . "'";
				}
				else if (strlen($t) > (int)$fs['max']) 
				{
					$errors[] = $fs['max'] . ' ' . DDFM_MAXCHARLIMIT . " '" . $fs['label'] . "'";
				}

				$csv .= str_replace($save_delimiter, ' ', $t) . $save_delimiter;
				$mail_message .= $fs['label'] . $msg_field_sep . $t . $msg_field_line_end;
				$message_structure = safe_str_replace($fs['fieldname'], $t, $message_structure);
				$auto_reply_message = safe_str_replace($fs['fieldname'], $t, $auto_reply_message);
				$sent_message = safe_str_replace($fs['fieldname'], ddfm_bsafe($t), $sent_message);
				break;
			case 'verify':
				// type=verify|class=|label=

				if ($verify_method == 'basic') 
				{
					$t = correct_stripslashes($form_input['fm_verify']);
					if ($t == "") 
					{
						$errors[] = DDFM_MISSINGVER;
					}
					else if (trim($_COOKIE["ddfmcode"]) == "") 
					{
						$errors[] = DDFM_NOVERGEN;
					}
					else if ($_COOKIE["ddfmcode"] != md5(strtoupper($t))) 
					{
						$errors[] = DDFM_INVALIDVER;
					}
				}
				break;
			case 'checkbox':
				//  type=checkbox|class=|label=|data=
				//	  (fieldname),(text),(CHECKED),(REQUIRED),
				//	  (fieldname),(text),(CHECKED),(REQUIRED),
				//	  (fieldname),(text),(CHECKED),(REQUIRED)

				// ### following three lines edited in order to have commas in the values, add by MG ###
				$fs['data'] = str_replace(",,", "C0mM@", $fs['data']);
				$data = explode(",", trim($fs['data']));
				$data = str_replace("C0mM@", ",", $data);

				$tmp_msg = array();
				$checkBoxChecked = false; //### added by MG ###

				for ($i = 0; $i < count($data); $i+=4) 
				{
					$t = '';
					if (isset($form_input[$data[$i]])) 
					{
						$t = correct_stripslashes(trim($form_input[$data[$i]]));
					}

					if ((strtolower($data[$i+3]) == 'true') && ($t == "")) 
					{
						$errors[] = DDFM_MISSINGFIELD . " '" . $fs['label'] . "'";
					}
						
					if ($t != "") 
					{
						$tmp_msg[] = $t;
						$checkBoxChecked = true; //### added by MG ###
					}

					$message_structure = safe_str_replace($data[$i], $t, $message_structure);
					$auto_reply_message = safe_str_replace($data[$i], $t, $auto_reply_message);
					$sent_message = safe_str_replace($data[$i], ddfm_bsafe($t), $sent_message);
				}
				
				// ### start of changes by MG ###
				if ((strtolower($fs['req']) == 'true') && !$checkBoxChecked) 
				{ 
					$errors[] = DDFM_MISSINGFIELD . " '" . $fs['label'] . "'";
				}
				// ### end of changes by MG ###

				$csv .= str_replace($save_delimiter, ' ', implode(', ', $tmp_msg)) . $save_delimiter;
				$mail_message .= $fs['label'] . $msg_field_sep . implode(', ', $tmp_msg) . $msg_field_line_end;
				break;
			case 'radio':
				//  type=radio|class=|label=|fieldname=|req=|[default=]|data=
				//	  (text),(text),(text),(text)
				$t = correct_stripslashes(trim($form_input[$fs['fieldname']]));

				if ((strtolower($fs['req']) == 'true') && ($t == "")) 
				{
					$errors[] = DDFM_MISSINGFIELD . " '" . $fs['label'] . "'";
				}

				$csv .= str_replace($save_delimiter, ' ', $t) . $save_delimiter;	
				$mail_message .= $fs['label'] . $msg_field_sep . $t . $msg_field_line_end;
				$message_structure = safe_str_replace($fs['fieldname'], $t, $message_structure);
				$auto_reply_message = safe_str_replace($fs['fieldname'], $t, $auto_reply_message);
				$sent_message = safe_str_replace($fs['fieldname'], ddfm_bsafe($t), $sent_message);
				break;
			case 'select':
				//  type=select|class=|label=|fieldname=|multi=(TRUEFALSE)|data=
				//    (#group),(text),(text),(#group),(text),(text)
				$data = explode(",", trim($fs['data']));

				if (strtolower($fs['multi']) != 'true') 
				{
					$t = correct_stripslashes($form_input[$fs['fieldname']]);
					$first_item = $data[0];
					if ((strtolower($fs['req']) == 'true') && (($t == "") || ($t == $first_item))) 
					{
						$errors[] = DDFM_MISSINGFIELD . " '" . $fs['label'] . "'";
					}
					
					$csv .= str_replace($save_delimiter, ' ', $t) . $save_delimiter;
					$mail_message .= $fs['label'] . $msg_field_sep . $t . $msg_field_line_end;
					$message_structure = safe_str_replace($fs['fieldname'], $t, $message_structure);
					$auto_reply_message = safe_str_replace($fs['fieldname'], $t, $auto_reply_message);
					$sent_message = safe_str_replace($fs['fieldname'], ddfm_bsafe($t), $sent_message);
				}
				else // multi = true
				{
					$t = (array)$form_input[$fs['fieldname']];
					if ((count($t) == 1) && ($t[0] == '')) 
					{
						unset($t[0]);
					}
					
					if ((strtolower($fs['req']) == 'true') && (count($t) == 0)) 
					{
						$errors[] = DDFM_MISSINGFIELD . " '" . $fs['label'] . "'";
					}

					$tmp_msg = array();

					foreach ($t as $tt) 
					{
						if ($tt != "") 
							$tmp_msg[] = $tt;
					}

					$csv .= str_replace($save_delimiter, ' ', implode(', ', $tmp_msg)) . $save_delimiter;
					$mail_message .= $fs['label'] . $msg_field_sep . implode(', ', $tmp_msg) . $msg_field_line_end;
					$message_structure = safe_str_replace($fs['fieldname'], implode(', ', $tmp_msg), $message_structure);
					$auto_reply_message = safe_str_replace($fs['fieldname'], implode(', ', $tmp_msg), $auto_reply_message);
					$sent_message = safe_str_replace($fs['fieldname'], ddfm_bsafe(implode(', ', $tmp_msg)), $sent_message);
				}
				break;
			case 'file':
				// type=file|class=|label=|fieldname=|[req=]|[allowed=1,2,3]

				if ((strtolower($fs['req']) == 'true') && (($_FILES[$fs['fieldname']]['name'] == ""))) 
				{
					$errors[] = DDFM_MISSINGFILE . " '" . $fs['label'] . "'";
				}
				
				$allowed = array();

				if (trim($fs['allowed']) != "") 
				{
					$allowed = (array)explode(",", trim(strtolower($fs['allowed'])));
				}
				
				if (($_FILES[$fs['fieldname']]['name'] != "") && ((int)$_FILES[$fs['fieldname']]['size'] == 0)) 
				{
					$errors[] = DDFM_FILETOOBIG . ' ' . $_FILES[$fs['fieldname']]['name'];
				}
				else if ($_FILES[$fs['fieldname']]['tmp_name'] != "") 
				{
					if (($_FILES[$fs['fieldname']]['error'] == UPLOAD_ERR_OK) && ($_FILES[$fs['fieldname']]['size'] > 0)) 
					{
						$origfilename = $_FILES[$fs['fieldname']]['name'];
						$filename = explode(".", $_FILES[$fs['fieldname']]['name']);
						$filenameext = $filename[count($filename) - 1];
						unset($filename[count($filename) - 1]);
						$filename = implode(".", $filename);
						$filename = substr($filename, 0, 15) . "." . $filenameext;
						$file_ext_allow = TRUE;
						
						if (count($allowed) > 0) 
						{
							$file_ext_allow = FALSE;
							for ($x = 0; $x < count($allowed); $x++) 
							{ 
								if (strtolower($filenameext) == strtolower($allowed[$x])) 
								{
									$file_ext_allow = TRUE;
								}
							} 
						}
						if ($file_ext_allow) 
						{
							if((int)$_FILES[$fs['fieldname']]['size'] < $max_file_size) 
							{
								$attached_files[$attached_index]['file'] = $_FILES[$fs['fieldname']]['name']; 
								$attached_files[$attached_index]['tmpfile'] = $_FILES[$fs['fieldname']]['tmp_name']; 
								$attached_files[$attached_index]['content_type'] = $_FILES[$fs['fieldname']]['type']; 
								$attached_index++;
								
								$csv .= str_replace($save_delimiter, ' ', $_FILES[$fs['fieldname']]['name']) . $save_delimiter;
									
								if (!$attach_save) 
								{
									$mail_message .= DDFM_ATTACHED . $msg_field_sep . $_FILES[$fs['fieldname']]['name'] . $msg_field_line_end; 
								}
								else 
								{
									$mail_message .= $fs['label'] . $msg_field_sep . $_FILES[$fs['fieldname']]['name'] . $msg_field_line_end;
								}

								$message_structure = safe_str_replace($fs['fieldname'], $_FILES[$fs['fieldname']]['name'], $message_structure);
								$auto_reply_message = safe_str_replace($fs['fieldname'], $_FILES[$fs['fieldname']]['name'], $auto_reply_message);
								$sent_message = safe_str_replace($fs['fieldname'], $_FILES[$fs['fieldname']]['name'], $sent_message);					
							}
							else 
							{ 
								$errors[] = DDFM_FILETOOBIG . ' ' . $_FILES[$fs['fieldname']]['name'];
							}
						}
						else 
						{ 
							$errors[] = DDFM_INVALIDEXT . ' ' . $_FILES[$fs['fieldname']]['name'];
						}
					} 
					else 
					{ 
						$errors[] = DDFM_UPLOADERR . ' ' . $_FILES[$fs['fieldname']]['name'];
					}
				}

				// handled above 
				//$csv .= str_replace($save_delimiter, ' ', $_FILES[$fs['fieldname']]['name']) . $save_delimiter;
				//$mail_message .= $fs['label'] . $msg_field_sep . $_FILES[$fs['fieldname']]['name'] . $msg_field_line_end;
				//$message_structure = safe_str_replace($fs['fieldname'], $_FILES[$fs['fieldname']]['name'], $message_structure);
				//$auto_reply_message = safe_str_replace($fs['fieldname'], $_FILES[$fs['fieldname']]['name'], $auto_reply_message);
				//$sent_message = safe_str_replace($fs['fieldname'], $_FILES[$fs['fieldname']]['name'], $sent_message);
				
				break;
			case 'selrecip':
				//  type=selrecip|class=|label=|data=(select),User1,user1@domain.com,User2 etc..
				$data = explode(",", trim($fs['data']));
				$t = correct_stripslashes($form_input['fm_selrecip']);

				if (($t == "") || ($t == $data[0])) 
				{
					$errors[] = DDFM_MISSINGFIELD . " '" . $fs['label'] . "'";
				}
				else 
				{
					for ($i = 1; $i < count($data); $i+=2) 
					{
						if ($data[$i] == $t) 
						{
							$sel_recip = trim($data[$i+1]);
						}
					}
				}
				break;
			}
		}*/

		// make sure no un-used fieldnames are left in template
		/*foreach ($form_struct as $fs) 
		{
			$message_structure = safe_str_replace($fs['fieldname'], '', $message_structure);
			$auto_reply_message = safe_str_replace($fs['fieldname'], '', $auto_reply_message);
			$sent_message = safe_str_replace($fs['fieldname'], '', $sent_message);
		}*/

		if (check_specialChars_injections($sender_name)) 
			$errors[] = DDFM_INVALIDINPUT;
		if (check_specialChars_injections($sender_email)) 
			$errors[] = DDFM_INVALIDINPUT;
		if (check_specialChars_injections($email_subject)) 
			$errors[] = DDFM_INVALIDINPUT;

		//$o = "\n\n\n" . '<!-- START of Dagon Design Formmailer output -->' . "\n\n";
		$o = "";

		if ($errors) 
		{
			$o .= '<div class="ddfmwrap"><div class="ddfmerrors">' . DDFM_ERRORMSG . '</div>';
			$o .= '<div class="errorlist">';

			foreach ($errors as $err) 
			{
				$o .= $err . '<br />';
			}
			$o .= '</div><div style="clear:both;"><!-- --></div></div>';
		}
		else 
		{
			if ($wrap_messages) 
			{
				$mail_message = wordwrap($mail_message, 70);
			}

			if ($recipients == 'selrecip') 
			{
				$recipients = $sel_recip;
			}

			// if template exists, use it instead
			if (strlen(trim($message_structure)) > 0) 
			{
				$mail_message = $message_structure . "\n\n";
			}

			if ($show_ip_hostname) 
			{
				$mail_message .= 'IP: ' . $_SERVER['REMOTE_ADDR'] . "\n" . 'HOST: ' . gethostbyaddr($_SERVER['REMOTE_ADDR']) . "\n";
			}

			$sndmsg = TRUE;
			if (($save_to_file == TRUE) && ($save_email == FALSE)) 
			{
				$sndmsg = FALSE;
			}
			
			$csv = safe_str_replace("\n", $save_newlines, $csv);
			$csv = safe_str_replace("\r", '', $csv);
			$csv = substr($csv, 0, strlen($csv) - strlen($save_delimiter));

			if (trim($save_timestamp) != '') 
			{
				$csv = date($save_timestamp) . $save_delimiter . $csv;
			}

			if (is_writable($save_path)) 
			{
				$handle = fopen($save_path, 'a+');
				fwrite($handle, $csv . "\n");
				fclose($handle);
			}

			if ($show_url == TRUE) 
			{
				$mail_message .= "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			}
			
			if ($sndmsg == TRUE) 
			{
				if (ddfm_send_mail($recipients, $sender_name, $sender_email, $email_subject, $mail_message, $attach_save, $attach_path, $attached_files)) 
				{
					$o .= $sent_message;// . "to " .$recipients;
					$auto_reply_name = trim($auto_reply_name);
					$auto_reply_email = trim($auto_reply_email);
					$auto_reply_subject = trim($auto_reply_subject);
					$auto_reply_message = trim($auto_reply_message);
					
					if (($orig_auto_reply_message != "") && (trim($sender_email != ""))) 
					{					
						$auto_reply_headers = '';
						$auto_reply_headers .= 'From: ' . $auto_reply_name . ' <' . $auto_reply_email . '>' . PHP_EOL;
						$auto_reply_headers .= 'Reply-To: ' . $auto_reply_name . ' <' . $auto_reply_email . '>' . PHP_EOL;
						$auto_reply_headers .= 'Return-Path: ' . $auto_reply_name . ' <' . $auto_reply_email . '>' . PHP_EOL;;
						$auto_reply_headers .= "Message-ID: <" . time() . "ddfm@" . $_SERVER['SERVER_NAME'] . ">" . PHP_EOL;
						$auto_reply_headers .= 'X-Sender-IP: ' . $_SERVER["REMOTE_ADDR"] . PHP_EOL;
						$auto_reply_headers .= "X-Mailer: PHP v" . phpversion() . PHP_EOL;
						$auto_reply_headers .= 'MIME-Version: 1.0' . PHP_EOL;
						$auto_reply_headers .= 'Content-Type: multipart/related; boundary="' . $mime_boundary . '"';
						/*$auto_reply_headers .= 'Content-Type: text/plain; charset=utf-8';*/
						// make correct encoding in auto - sokai - BEGIN
						//$auto_reply_message .= PHP_EOL . PHP_EOL;
						$auto_reply_message .= PHP_EOL . PHP_EOL . '--' . $mime_boundary . '--' . PHP_EOL . PHP_EOL;
						// make correct encoding in auto - sokai - END

						mail($sender_email, $auto_reply_subject, $auto_reply_message, $auto_reply_headers);
					}

					$message_sent = TRUE;
					$_POST = array();
				}
				else
				{
					$o .= DDFM_SERVERERR;
					$message_sent = FALSE;
				}
			}
			else 
			{
				$o .= $sent_message;
			}
		}
	} // end of form submission processing

	// Generate form if message has not been sent
	if (!$message_sent)
	{
		if ($verify_method == 'basic' && !ddfm_check_gd_support()) 
		{
			$o .= DDFM_GDERROR;
		}

		$o .= $manual_form_code;
		// Form generation complete
	} // end of display form code

	//$o .= '<!-- END of Dagon Design Formmailer output -->' . "\n\n\n";

	
	
	
	
	
	
/* Page Generation */
// show script output
echo $o; 
?>