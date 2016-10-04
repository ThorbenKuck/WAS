<?php namespace Main; ?>
	<?php

date_default_timezone_set ('Europe/Berlin');

function main (string $config_path = null) {
	init ($config_path);
	load_all_packages ();
	load_mods ();
	load_theme ();
	eoe ();
}

/**
 * A simple function, that can be used to check if the package has been correctly loaded
 *
 * @return boolean Return true
 */
function integrated () {
	return true;
}

/**
 * This function is trying to load the config of the Framework.
 * Trys to run read_config()
 *
 * @param string $config_path
 * @return bool Wether or not the config could be loaded
 */
function init (string $config_path = null) {

	$_SESSION['System'] = [
		"debug" => [
			"system_debug" => [],
			"custom" => []
		],
		"info" => []
	];

	if (settings()['dev_mode']) {
		ini_set ( "display_errors", "1" );
		error_reporting ( E_ALL | E_STRICT );
	} else {
		ini_set ( "display_errors", "0" );
	}

	force_settings_reload();

	if (isset (info()['root_path'])) {
		return read_config (info()['root_path']) && read_theme_config ();
	} else {
		return read_config ($config_path) && read_theme_config ();
	}
}

function eoe () {
	register_shutdown_function (function () {
		$test_file_path = info()['test_file'];
		if(settings()['use_test_file'] && file_exists($test_file_path)) {
			include $test_file_path;
		}
		open_info_frame ();
		open_debug_frame ();
	});
}

require dirname(__FILE__) . '/main.functions.php';
require dirname(__FILE__) . '/main.debug.functions.php';
require dirname(__FILE__) . '/main.settings.functions.php';
require dirname(__FILE__) . '/main.mods.functions.php';
require dirname(__FILE__) . '/main.todo.functions.php';
require dirname(__FILE__) . '/main.interact.functions.php';
require dirname(__FILE__) . '/main.packages.functions.php';
require dirname(__FILE__) . '/main.pseudo.functions.php';