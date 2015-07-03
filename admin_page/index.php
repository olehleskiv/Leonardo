<?php

session_start();

$username = '1';
$password = '1';

$random1 = 'secret_key1';
$random2 = 'secret_key2';
$invalid = false;

$hash = md5($random1.$pass.$random2); 

$self = $_SERVER['REQUEST_URI'];

if(isset($_GET['logout']))
{
	unset($_SESSION['login']);
}

if (isset($_SESSION['login']) && $_SESSION['login'] == $hash) {
	include 'admin.php';
}
else if (isset($_POST['submit'])) {

	if ($_POST['username'] == $username && $_POST['password'] == $password){
		//IF USERNAME AND PASSWORD ARE CORRECT SET THE LOG-IN SESSION
		$_SESSION["login"] = $hash;
		header("Location: $_SERVER[PHP_SELF]");
	} else {
		// DISPLAY FORM WITH ERROR
		$invalid = true;
		display_login_form($invalid);
	}
}
else { 

	display_login_form($invalid);

}

function display_login_form($isError){ 
	 include 'login.php';
}

?>

