<?php 
ini_set('session.gc_maxlifetime', 36000000);
ini_set('session.gc_divisor', 1);

if (session_status () !== PHP_SESSION_ACTIVE) 
	session_start ();

if(!empty($_SESSION["keep_me_logd_in"]) && $_SESSION["delete_keep_logd_in"] === $_SESSION["vpm_login_session"]) {
	setcookie ( "vpm_login_cookie", $_SESSION["keep_me_logd_in"], time () + 3600 * 24 * 100 );
	unset($_SESSION["keep_me_logd_in"]);
}

if(!empty($_SESSION["delete_keep_logd_in"]) && $_SESSION["delete_keep_logd_in"] == 1) {
	setcookie ( "vpm_login_cookie", "", time () - 3600 );
}

if(!empty($_COOKIE["vpm_login_cookie"])) {
	$_SESSION['vpm_login_session'] = $_COOKIE["vpm_login_cookie"];
}

unset($_SESSION["login_lock_warning_set"]);
?>
<head>
<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/vpm_debug_frame.css" />
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<link rel="icon" type="image/x-icon" href="themes/numberOne/resources/kgslogo300_klein.ico">
</head>
<?php
date_default_timezone_set('Europe/Berlin');

if (!file_exists ( 'functions/index.php' )) {
	die ( "Es gab einen Fehler mit der Darstellung!" . "<br>" . "Bitte gedulden sie sich etwas!" . "<br>" . "Die Administratoren wurden verständigt" . "<br>");
}
require_once 'functions/index.php';

if(! function_exists('Main\integrated')) {
	die("Das Main-Package konnte nicht geladen werden! Stellen sie sicher, dass das Package vorhanden ist!");
}
Main\main();


if (DEV_MODE) {
	ini_set ( "display_errors", "1" );
	error_reporting ( E_ALL );
} else {
	ini_set ( "display_errors", "0" );
}

// Vpm\debug\debug_input ( "Das ist ein Test" );
// if (file_exists ( 'classes/index.php' ))
// 	require_once 'classes/index.php';
// else
// 	die ( "Es gab einen Fehler mit der Darstellung!" . "<br>" . "Bitte gedulden sie sich etwas!" . "<br>" . "Die Administratoren wurden verständigt" . "<br>" . __FILE__);

Main\debug_input(array("(Title): TODO!", "--none--"));

Main\open_debug_frame ();




?>


<!--
<button 
	style="width: 100px; height: 100px; background-color: black; color: white;"
	onclick="notifyMe('Das ist ein Test', 'blabla')">Klick mich!</button>
 -->
 
<script>
var first_path;
var theme_path;
<?php

if (SUCCES) {
	$first_path = THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/index.php";
	$theme_path = THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/";
	echo "var first_path = '{$first_path}';";
	echo "var theme_path = '{$theme_path}';";
}
?>


function test() {
	return "erfolgreich";
}

document.addEventListener('DOMContentLoaded', function () {
  if (Notification.permission !== "granted")
    Notification.requestPermission();
});


$(document).ready(function(){
	var body_content = document.getElementById("body_content");
	var nav_info = document.getElementById("nav-info");
	$(body_content).load(window.location.href + first_path);
	$(nav_info).html("<b>Home</b>");
});



function notifyMe(body, title) {
  if (!Notification) {
    alert('Desktop notifications not available in your browser. Try another browser.'); 
    return;
  }

  if (Notification.permission !== "granted")
    Notification.requestPermission();
  else {
    var notification = new Notification(title, {
      icon: theme_path + "resources/kgslogo300_klein.png",
      body: body
    });

    notification.onclick = function () {
      window.focus();
    };

  }

}

</script>
