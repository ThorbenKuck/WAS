<?php
require "config.php";

if (defined ( 'ABSPATH' ) && defined ( 'VPM_CONNECT' ) && defined ( 'FUNCTIONS' )) {
	$to_json = array (
			"connect_path" => VPM_CONNECT,
			"abspath" => ABSPATH,
			"functions" => FUNCTIONS,
			"vpm_mod_path" => PATH_TO_VPM_MOD,
			"startuhr" => STARTUHR,
			"enduhr" => ENDUHR,
			"ip" => CONNECTION-IP 
	);
	$json_string = json_encode ( $to_json );
	$config_path = VPM_CONNECT . "connect_config.json";
	if ($handle = fopen ( $config_path, "w" )) {
		fwrite ( $handle, $json_string );
		fclose ( $handle );
	} else {
		Main\debug_input ( "Die Datei: \"" . $config_path . "\" konnte nicht erstellt werden!" );
	}
} else {
	echo "<br><br>" . "Eines der define-statements ist nicht korrekt!" . "<br><br>";
	var_dump ( ABSPATH );
	echo "<br>";
	var_dump ( VPM_CONNECT );
	echo "<br>";
	var_dump ( FUNCTIONS );
	echo "<br>";
}

?>