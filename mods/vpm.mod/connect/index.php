<?php 
if (session_status () !== PHP_SESSION_ACTIVE)
	session_start ();
?>

#!/usr/bin/php -q
<br>
<?php

$json_data;


if(file_exists("connect_config.json")){
	$json_data = json_decode ( file_get_contents ( realpath ( "connect_config.json" ) ), true);
}


if(!defined('PATH_TO_VPM_MOD')) {
	define('PATH_TO_VPM_MOD', $json_data['vpm_mod_path']);
		
}

if(!defined('VPM_CONNECT')) {
	define('VPM_CONNECT', $json_data['connect_path']);
}

if(!defined('ABSPATH')) {
	define('ABSPATH', $json_data['abspath']);
}

if(!defined('FUNCTIONS')) {
	define('FUNCTIONS', $json_data['functions']);
}

if(!defined('STARTUHR')) {
	define('STARTUHR', $json_data['startuhr']);
}

if(!defined('ENDUHR')) {
	define('ENDUHR', $json_data['enduhr']);

}

if(!defined('CONNECTION-IP')) {
	define('CONNECTION-IP', $json_data['ip']);
}

require FUNCTIONS . "index.php";

date_default_timezone_set('Europe/Berlin');



//to edit the crontan, use this:
//sudo nano /etc/crontab
//do not use:
//crontab -e
//since you would have to deal with permissions!

if(function_exists('Main\integrated')) {
	Main\read_config();
	
	Vpm\sort_core();
	Vpm\current_state(false);
	
	$stand = Vpm\current_state(false);
	
	var_dump($stand);

	if($stand) {
		Main\log("Die Monitore sind eingeschaltet", "console", "monitorzustand.".date("Y").date("m").date("d").".log");
	} else {
		Main\log("Die Monitore sind ausgeschaltet", "console","monitorzustand.".date("Y").date("m").date("d").".log");
	}
} else {
	$file = "error.log";
	if($erg = fopen($file, "w+")) {
        	fwrite($erg, "Es gab ein Problem mit den Funktionen".date("Y").date("m").date("d").date('H').":".date('i').":".date('s'));
        	fclose($erg);
	}
}

if($_SERVER ["REMOTE_ADDR"] != "127.0.0.1") {
	die("You should not see that! This incedent has been reported!"."<br>"."If you are here on purpose, please report this to an administrator!");
}

?>
