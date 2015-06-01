<?php
function check_specialChars_injections($s)
{
	return (preg_match('/\r/', $s) || preg_match('/\n/', $s) || preg_match('/%0a/', $s) || preg_match('/%0d/', $s));
}
function correct_stripslashes($s) 
{
    if (get_magic_quotes_gpc()) 
	{
        return stripslashes($s);
    } 
	else 
	{
        return $s;
    }
}
/* Safe str_replace */
function safe_str_replace($search, $replace, $subject) 
{
    if (isset($search)) 
	{
        return str_replace($search, $replace, $subject);
    }
	else 
	{
        return $subject;
    }
}
?>