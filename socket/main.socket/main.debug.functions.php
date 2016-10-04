<?php namespace Main; ?>
	<?php

date_default_timezone_set ('Europe/Berlin');

function debug_mod_enabled () {
	return settings ()['debug_mode'];
}

/**
 * Debug any input to the debug-frame
 *
 * @version 1.0
 *
 * @param string $input
 *            The iput, that shall be debuged
 *
 * @param bool $with_file_information
 * @return bool|NULL Returns NULL, if the input is empty, and if not a boolean wehter or not the input has been correctly debuged
 */
function debug_input ($input, $with_file_information = true) {
	if (empty ($input))
		return null;
	if (session_status () !== PHP_SESSION_ACTIVE)
		session_start ();

	// Voraussetzung: Debug_mode muss true sein
	if (debug_mod_enabled ()) {

		$post = "";
		if ($with_file_information) {
			$backtrace = debug_backtrace ()[0];
			$post = "<br>" . "[" . $backtrace["file"] . " : line=" . $backtrace["line"] . "]";
		}

		if(!isset($_SESSION['System']['debug']['custom'])) {
			$_SESSION['System']['debug']['custom'] = [];
		}
		if (is_string ($input)) {
			// Die Session existiert und der Input ist kein array
			array_push ($_SESSION ['System']['debug']['custom'], $input . $post);
		} else {
			array_push ($_SESSION ['System']['debug']['custom'], $input);
		}

		return true;
	} else {
		return false;
	}
}

function top_level_debug ($top_level_name, $to_debug, $with_trace_back = false, $sub_name = null) {
	if (debug_mod_enabled ()) {
		if($with_trace_back) {
			$backtrace = debug_backtrace ()[0];
			$to_debug .= "<br>" . "[" . $backtrace["file"] . " : line=" . $backtrace["line"] . "]";
		}
		if (!isset($_SESSION['System']['debug'][$top_level_name])) {
			$_SESSION['System']['debug'][$top_level_name] = [];
		}
		if ($sub_name !== null) {
			if (!isset($_SESSION['System']['debug'][$top_level_name][$sub_name])) {
				$_SESSION['System']['debug'][$top_level_name][$sub_name] = [];
			}
			array_push ($_SESSION['System']['debug'][$top_level_name][$sub_name], $to_debug);
		} else {
			array_push ($_SESSION['System']['debug'][$top_level_name], $to_debug);
		}
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
 *            The string, that shall be loged. Can contain HTML
 * @param string $custom_name
 *            If set, it will safe the $string variable in a file named like this variable. If not, it will use the normal format.
 * @param string $log_format
 *            A short string, what format shall be used
 *
 * @return NULL|boolean Returns if the log has been correctly modified and null if the variables are wrong
 */
function log ($string, $log_format = "old", $custom_name = null) {

	$start = "";
	$end = "";

	switch ( $log_format ) {
		case "new":
			$start = "[" . date ('H') . ":" . date ('i') . ":" . date ('s') . " Uhr | " . date ('d') . "." . date ('m') . "." . date ('Y') . "] | ( ";
			$end = " )" . PHP_EOL;
			break;
		case "modern":
			$start = date ('Y') . "-" . date ('m') . "-" . date ('d') . " | " . date ('H') . ":" . date ('i') . ":" . date ('s') . " : ";
			$end = PHP_EOL;
			break;
		case"console":
			if (is_logdin ()) {
				$start = get_username () . "@" . $_SERVER ["REMOTE_ADDR"] . " [" . date ('H') . ":" . date ('i') . ":" . date ('s') . "] $ ";
			} else {
				$start = get_username () . "@" . $_SERVER ["REMOTE_ADDR"] . " [" . date ('H') . ":" . date ('i') . ":" . date ('s') . "] $ ";
			}
			$end = PHP_EOL;
			break;
		case "crawler":
			$start = "crawler@[" . date ('H') . ":" . date ('i') . ":" . date ('s') . "," . date('u') . "]$ ";
			$end = PHP_EOL;
			break;
		case "old":
		default:
			$start = "[" . date ('H') . ":" . date ('i') . ":" . date ('s') . " Uhr | " . date ('d') . "." . date ('m') . "." . date ('Y') . "] | ( ";
			$end = " )" . PHP_EOL;
			break;
	}

	if ($custom_name !== null) $current_log = info()['root_path'] . "logs/" . $custom_name;
	else $current_log = find_current_log ();

	$handle = fopen ($current_log, "a") or debug_input ("(Error)Could not open the current log ... See further stacktrace for information");
	if (!file_exists ($current_log) || !$handle) {
		return false;
	}
	fwrite ($handle, $start . $string . $end);
	fclose ($handle);
	chmod($current_log, 0777);
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
function find_current_log () {
	if (!isset(info()['root_path']))
		return null;

	$fallback = false;
	$path_to_logs = info()['root_path'] . "logs/";
	$date_now = date ('Y') . date ('m') . date ('d');
	$errors = ["(Title)log_stacktrace"];

	$path_to_logs .= date ('Y') . "/";

	if (!is_dir ($path_to_logs)) {
		array_push ($errors, "(Warning)Could not find the current year's log folder..");
		if (!mkdir ($path_to_logs, 0777)) {
			array_push ($errors, "(Error)could not create folder for current year's log!");
			$fallback = true;
		} else {
			array_push ($errors, "(Notice)Created folder for the current year");
		}
	}
	$path_to_logs .= date ('F') . "/";
	if (!is_dir ($path_to_logs)) {
		array_push ($errors, "(Warning)Could not find the current month's log folder..");
		if (!mkdir ($path_to_logs, 0777)) {
			array_push ($errors, "(Error)could not create folder for current month's log!");
			$fallback = true;
		} else {
			array_push ($errors, "(Notice)Created folder for the current month");
		}
	}

	if ($fallback) {
		array_push ($errors, "using fallback ...");
		$path_to_logs = ABSPATH . "logs/";
	}
	$current_log = $path_to_logs . $date_now . ".log";

	if (count ($errors) > 1) {
		debug_input ($errors, false);
	}

	if (file_exists ($current_log)) {
		return $current_log;
	} else {
		if (fopen ($current_log, "a")) {
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
 * @param string $routine_name
 * @param string $to_debug
 * @return bool Wheter or not the system-outputs habe been debuged
 */
function system_debug (string $routine_name, $to_debug) : bool {
	if (!isset($_SESSION['System']['debug']['system_debug'][$routine_name])) {
		$_SESSION['System']['debug']['system_debug'][$routine_name] = [];
	}

	array_push ($_SESSION['System']['debug']['system_debug'][$routine_name], $to_debug);
	return true;
}

?>