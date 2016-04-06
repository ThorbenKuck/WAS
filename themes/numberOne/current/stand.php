<?php

if (session_status () !== PHP_SESSION_ACTIVE)
	session_start ();

if(file_exists($_SESSION['autoload'])) {
	require_once($_SESSION['autoload']);
}

// var_dump($logic);
Main\return_new_dialogbox("", "600px", "700px" , "1000px", "1200px", "required/now.php", "");


?>