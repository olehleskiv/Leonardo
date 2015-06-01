<?php
/*
** START OF OPTIONS
*/

// For those of you including this script in another PHP file, be sure to manually
// add the CSS declaration in the header section of your page:
//   <link rel="stylesheet" href="(location of dd-formmailer.css)" type="text/css" media="screen" />
// You also need to load the JS file used by the date chooser, if you choose to use this field:
//   <script type="text/javascript" src="(location of date_chooser.js)"></script>
// If you are using the stand-alone mode, these will be added automatically

// LANGUAGE SETTING 
// The relative path to the language file you want to use.
$language = "lang/".$LangDir.".php";

// FULL URL TO SCRIPT
// The full URL to dd-formmailer.php (or whatever you have renamed it to)

$script_path = 'http://http://www.agencyleonard.com/dd-formmailer/dd-formmailer.php';

// FULL URL TO CONTACT PAGE
// If you are running this script in standalone mode, leave this blank. Otherwise,
// enter the full URL to the page that is displaying the form

$path_contact_page = 'http://www.agencyleonard.com/?page=contacts';

// RECIPIENT DATA
// If you are just sending email to a single address, enter it here. For more advanced
// usage such as multiple recipients, CC, BCC, etc.. please see the web page for instructions

//$recipients = 'info@agencyleonard.com';
$recipients = 'to=info@agencyleonard.com|cc=alina@mail.lviv.ua';


// IMAGE VERIFICATION
// You can disable image verification, use the simple built-in method, or use ReCaptcha
// If you use ReCaptcha, sign up for a free account at http://recaptcha.net and enter the codes below

$verify_method = 'recaptcha'; // 'off', 'basic', or 'recaptcha'

// BASIC IMAGE VERIFICATION OPTIONS

$verify_background = 'F0F0F0';	// hex code for background color
$verify_text = '005ABE';		// hex code for text color
$force_type = '';				// problems showing the code? try forcing to 'gif', 'jpeg' or 'png'

// RECAPTCHA IMAGE VERIFICATION OPTIONS
// Public and private keys - you get these when you sign up an account at http://recaptcha.net

$re_public_key = '6LefyLwSAAAAAPJWA4vX5e-j_JlafSr9UXxeDxCd';
$re_private_key = '6LefyLwSAAAAAAYoKPzm8F9Dt8ZBZqs0nL9DDQb4';

// FORM STRUCTURE
// This is used to generate the form. Each form element must be on its own line.
// Detailed usage instructions can be found on the web page

$form_struct = '
	type=text|class=fmtext|label=Name|fieldname=fm_name|max=100|req=true
	type=text|class=fmtext|label=Email|fieldname=fm_email|max=100|req=true|ver=email
	type=text|class=fmtext|label=Subject|fieldname=fm_subject|max=100|req=true
	type=verify|class=fmverify|label=Verify
	type=textarea|class=fmtextarea|label=Message|fieldname=fm_message|max=1000|rows=6|req=true
';

// MANUAL FORM CODE
// Advanced users only! please read documentation first

	// Load language settings
	include_once($language);

$manual_form_code = '
			<form class="ddfm" method="post" action="http://www.agencyleonard.com/?page=contacts" enctype="application/x-www-form-urlencoded" accept-charset="UTF-8">
				<div style="clear: both;">
					<div class="ddfmwrap"  style="float: left;">
						<p class="fieldwrap">
							<label for="fm_name"><span class="required">*</span> '.DDFM_FORM_NAME.'</label>
							<input class="fmtext" type="text" name="fm_name" id="fm_name" value=""    maxlength="100" />
						</p>
						<p class="fieldwrap">
							<label for="fm_email"><span class="required">*</span> '.DDFM_FORM_EMAIL.'</label>
							<input class="fmtext" type="text" name="fm_email" id="fm_email" value="" maxlength="100" />
						</p>
					</div>
					<div class="ddfmwrap" style="float: left;">
						<p class="fieldwrap">
							<label for="fm_message"><span class="required">*</span> '.DDFM_FORM_MESSAGE.'</label>
							<textarea class="fmtextarea" name="fm_message" cols="20" rows="6" id="fm_message" maxlength="1000" ></textarea>
						</p>
					</div>
				</div>
				<div class="ddfmwrap">';
if ($verify_method == 'recaptcha') 
{
	$manual_form_code .= "
					<script type=\"text/javascript\"> var RecaptchaOptions = { theme : 'white' }; </script>";
	@include_once('recaptchalib.php');
	$publickey = $re_public_key;

	$manual_form_code .= '
					<div class="recaptcha">
						<div class="recaptcha-inner">';
	$manual_form_code .= '
							' . recaptcha_get_html($publickey);
	$manual_form_code .= '
						</div>
					</div>';
}
$manual_form_code .= '
				</div>
				<div class="submit"><input type="submit" name="form_submitted" value="' . DDFM_SUBMITBUTTON . '" /></div>
			</form>
';

// WRAP MESSAGES
// If enabled, this wraps messages to 70 chars per line (for RFC compliance)

$wrap_messages = TRUE;

// SAVE ATTACHMENTS
// If enabled, attachments will be saved to a directory instead of emailed

$attach_save = FALSE;

// SAVE ATTACHMENT PATH
// Where files will be saved, if attach_save is enabled
// ** Full path on server. Ex: /home/user/public_html/upload/
// ** Make sure directory has write permission
// ** include trailing slash

$attach_path = '';

// SHOW REQUIRED
// If enabled, required fields are marked with an asterisk

$show_required = TRUE;

// SHOW URL
// If enabled, the URL the script is running from will be added to the message

$show_url = FALSE;

// SHOW IP AND HOSTNAME
// If enabled, the visitor's IP and hostname are added to the message

$show_ip_hostname = FALSE;

// SPECIAL FIELDS
// These options help generate the email headers. Simply enter a field name,
// and the user input from that field will be used. You can also combine fields. 
// For example, if you have a fm_firstname and fm_lastname field, you could 
// set $sender_name to 'fm_lastname, fm_firstname'

$sender_name = 'fm_name';
$sender_email = 'fm_email';
//$email_subject = 'Contact: fm_subject';
$email_subject = 'Leonard web page user message';

// MAX UPLOAD SIZE
// If you are using file uploads in your form, this specifies the max file size.
// (This does not override any server settings you might have in PHP.ini)

$max_file_size = 1000000; // in bytes

// MESSAGE STRUCTURE
// This is an optional setting that allows you to define your own custom message
// template. More information can be found on the web page. If left blank, the script
// will generate the message itself, which is generally suitable for most purposes.
// You use field names in this - they will be replaced with the user input from those fields.

$message_structure = "Від: fm_name\n\n".fm_message."\n";

// SUCCESS MESSAGE
// This is the text shown after the visitor has successfully submitted the form.
// You use field names in this - they will be replaced with the user input from those fields.

$sent_message = '<p>'.DDFM_MESSAGESENT.'.</p>';

// AUTO REPLY OPTION
// This optional feature allows you to automatically send a pre-defined auto reply email.
// To use it, simply specify the name and email address you want the message to be 'from', 
// as well as a subject and message. To disable, just leave $auto_reply_message blank.
// You use field names in the message - they will be replaced with the user input from those fields.


$auto_reply_name = '';
$auto_reply_email = '';
$auto_reply_subject = '';
$auto_reply_message = '';

// SAVE DATA TO FILE
// If set to TRUE, the form input will be saved in a delimited file

$save_to_file = FALSE;

// STILL SEND EMAIL
// If saving the data to a file, still have the script send the email?

$save_email = TRUE;

// DATA PATH
// The file that will be written to - make sure it has write access

$save_path = 'data.txt';

// DELIMITER
// Fields will be separated by this character. If this character is found in
// the actual data, it will be removed.

$save_delimiter = '|';

// NEWLINES
// Newlines in the data will be replaced by this

$save_newlines = "<br />";

// TIMESTAMP
// Add date/time to the beginning of each line 
// Uses the PHP date format: http://us.php.net/date
// Leave blank to disable this feature

$save_timestamp = "m-d-Y h:i:s A";

/*
** END OF OPTIONS 
*/
?>