<?php namespace Main; ?>
<?php

date_default_timezone_set ( 'Europe/Berlin' );

/**
 * Debug any input to the debug-frame
 *
 * @version 1.0
 *         
 * @param string $input
 *        	The iput, that shall be debuged
 *        	
 * @return NULL|boolean Returns NULL, if the input is empty, and if not a boolean wehter or not the input has been correctly debuged
 */
function debug_input($input) {
	if (empty ( $input ))
		return null;
	if (session_status () !== PHP_SESSION_ACTIVE)
		session_start ();
		
	// Voraussetzung: Debug_mode muss true sein
	if (DEBUG_MODE) {
		// Die Session existiert noch nicht.
		if (! isset ( $_SESSION ['debug_content'] )) {
			// Dann erzeuge sie
			$_SESSION ['debug_content'] = array ();
		}
		// Wenn die Session keine Array ist
		if (! is_array ( $_SESSION ['debug_content'] )) {
			return false;
		}
		// Die Session existiert und der Input ist kein array
		array_push ( $_SESSION ['debug_content'], $input );
		
		// $debug_content [$index] = $input;
		return true;
	} else {
		// Debug_mode ist auf false gesetzt
		unset ( $_SESSION ['debug_content'] );
		return false;
	}
}

/**
 * Writes anything down inside the "logs/" folder.
 * This function automaticly aplies a timestamp at the front of the input.
 * It normaly log into a file, wich looks like this YEARMONTHDAY.log, where YEAR is eaqual to the current year, MONTH is eaqual to the current month and DAY is eaqual to the current day.
 * The format of the log can be "old", "new", "modern", "console"
 *
 * @version 1.1.4
 *         
 * @param string $string
 *        	The string, that shall be loged. Can contain HTML
 * @param string $custom_name
 *        	If set, it will safe the $string variable in a file named like this variable. If not, it will use the normal format.
 * @param string $log_format
 *        	A short string, what format shall be used
 *        	
 * @return NULL|boolean Returns if the log has been correctly modified and null if the variables are wrong
 */
function log($string, $log_format = "old", $custom_name = null) {
	if ($log_format === "old") {
		$start = "[" . date ( 'H' ) . ":" . date ( 'i' ) . ":" . date ( 's' ) . " Uhr | " . date ( 'd' ) . "." . date ( 'm' ) . "." . date ( 'Y' ) . "] | ( ";
		$end = " )" . "\n";
	} else if ($log_format === "new") {
		$start = date ( 'Y' ) . "-" . date ( 'm' ) . "-" . date ( 'd' ) . " | " . date ( 'H' ) . ":" . date ( 'i' ) . ":" . date ( 's' ) . " : ";
		$end = "\n";
	} else if ($log_format === "modern") {
		if (is_logdin ()) {
			$start = $_SERVER ["REMOTE_ADDR"] . " - " . get_username () . "[" . date ( 'D' ) . ", " . date ( 'd' ) . "/" . date ( 'M' ) . "/" . date ( 'Y' ) . " " . date ( 'H' ) . ":" . date ( 'i' ) . ":" . date ( 's' ) . "] : ";
			$end = "\n";
		} else {
			$start = $_SERVER ["REMOTE_ADDR"] . " - Unkown " . "[" . date ( 'D' ) . ", " . date ( 'd' ) . "/" . date ( 'M' ) . "/" . date ( 'Y' ) . " " . date ( 'H' ) . ":" . date ( 'i' ) . ":" . date ( 's' ) . "] : ";
			$end = "\n";
		}
	} else if ($log_format === "console") {
		$start = $_SERVER ["REMOTE_ADDR"] . " - [" . date ( 'r' ) . "] : ";
		$end = "\n";
	} else
		return null;
	
	if ($custom_name !== null)
		$current_log = ABSPATH . "logs/" . $custom_name;
	else
		$current_log = find_current_log ();
	
	$handle = fopen ( $current_log, "a" ) or die ( "Konnte den aktuellen Log nicht oeffnen" );
	if (! file_exists ( $current_log ) || ! $handle) {
		return false;
	}
	fwrite ( $handle, $start . $string . $end );
	fclose ( $handle );
	return true;
}

/**
 * Tries to find the current day log-file in the format of YEARMONTHDAY.log.
 * If this file does not exist, this function is going to try to create this file
 *
 * @version 1.0
 *         
 * @return NULL|string|boolean Returns NULL if the config is not loaded and false if it cant create the new log. If everything goes well, it returns the path to the current log
 */
function find_current_log() {
	if (! defined ( 'ABSPATH' ))
		return null;
	
	$fallback = false;
	$path_to_logs = ABSPATH . "logs/";
	$date_now = date ( 'Y' ) . date ( 'm' ) . date ( 'd' );
	
	$path_to_logs .= date ( 'Y' ) . "/";
	
	if (! is_dir ( $path_to_logs )) {
		if (! mkdir ( $path_to_logs, 0777 )) {
			debug_input ( "(Warning): could not create folder for current year's log!" );
			$fallback = true;
		}
	}
	$path_to_logs .= date ( 'F' ) . "/";
	if (! is_dir ( $path_to_logs )) {
		if (! mkdir ( $path_to_logs, 0777 )) {
			debug_input ( "(Warning): could not create folder for current month's log!" );
			$fallback = true;
		}
	}
	
	if ($fallback) {
		$path_to_logs = ABSPATH . "logs/";
	}
	$current_log = $path_to_logs . $date_now . ".log";
	
	if (file_exists ( $current_log )) {
		return $current_log;
	} else {
		if (fopen ( $current_log, "a" )) {
			return $current_log;
		} else {
			return false;
		}
	}
}

/**
 * Debug errors, warnings, notices and infos, created by the logic of the main framework.
 * Reccomended to not be used outside the main runtime. However, feel free to use it, if you need to.
 *
 * @version 1.0
 *         
 * @return boolean Wheter or not the system-outputs habe been debuged
 */
function system_debug() {
	$package_output = $_SESSION ['package_include_algortihm'];
	$iniload_output = $_SESSION ['iniload_error_log'];
	$modload_output = $_SESSION ['mods_loader_errors'];
	
	if (! empty ( $package_output )) {
		unset ( $_SESSION ['package_include_algortihm'] );
		debug_input ( $package_output );
	}
	if (! empty ( $modload_output )) {
		unset ( $_SESSION ['mods_loader_errors'] );
		debug_input ( $modload_output );
	}
	if (! empty ( $iniload_output )) {
		unset ( $_SESSION ['iniload_error_log'] );
		debug_input ( $iniload_output );
	}
	
	return true;
}

?>