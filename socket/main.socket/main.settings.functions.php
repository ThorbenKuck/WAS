<?php
namespace Main;

use Main;

function settings() : array {
	if(isset($_SESSION['System']['settings']) && !empty($_SESSION['System']['settings'])) {
		return $_SESSION['System']['settings'];
	} else {
		$settings = Main::getSettings();
		foreach ($settings as $key => $value) {
			$_SESSION['System']['settings'][$key] = $value;
		}
		return settings();
	}
}

function force_settings_reload() : bool{
	unset($_SESSION['System']['settings']);
	return !empty(settings());
}

function update_setting(string $key, $new_value) : bool {
	if(array_key_exists($key, $_SESSION['System']['settings'])) {
		$_SESSION['System']['settings'][$key] = $new_value;
		return true;
	} else {
		debug_input(["(Notice)Failed to update setting \"".$key."\" to \"".$new_value."\" because this key does not exist"]);
		return false;
	}
}

function set_new_setting(string $key, $value) : bool {
	if(!array_key_exists($key, $_SESSION['System']['settings'])) {
		$_SESSION['System']['settings'][$key] = $value;
		return true;
	} else {
		debug_input(["(Notice)Failed to add new setting \"".$key."=".$value."\" because this key is already in use with \"".$key."=".$_SESSION['System']['settings'][$key]."\""]);
		return false;
	}
}

function set_setting(string $key, $value) : bool {
	$_SESSION['System']['settings'][$key] = $value;
	return true;
}

function info() : array {
	if(isset($_SESSION['System']['info'])) {
		return $_SESSION['System']['info'];
	} else {
		return [];
	}
}

function update_info(string $key, $new_value) : bool {

	if(!isset($_SESSION['System']['info'])) {
		$_SESSION['System']['info'] = [];
	}

	if(array_key_exists($key, $_SESSION['System']['info'])) {
		$_SESSION['System']['info'][$key] = $new_value;
		return true;
	} else {
		debug_input(["(Notice)Failed to update info \"".$key."=".$new_value."\" because this key does not exist."]);
		return false;
	}
}

function set_new_info(string $key, $value) : bool {
	if(!isset($_SESSION['System']['info'])) {
		$_SESSION['System']['info'] = [];
	}
	if(!array_key_exists($key, $_SESSION['System']['info'])) {
		$_SESSION['System']['info'][$key] = $value;
		return true;
	} else {
		$key = (is_array($key) ? "array" : $key);
		$value = (is_array($value) ? "array" : $key);
		$new_value = (is_array($_SESSION['System']['info'][$key]) ? "array" : $_SESSION['System']['info'][$key]);
		top_level_debug("info" , ["(Notice)Failed to add new info \"".($key)."=".($value)."\" because this key is already in use with \"".($key)."=".($new_value)."\""]);
		return false;
	}
}

function set_info(string $key, $value) : bool {
	return (!array_key_exists($key, $_SESSION['System']['info']) ? set_new_info($key, $value) : update_info($key, $value));
}