<?php namespace Main; ?>
	<?php

use System;

date_default_timezone_set('Europe/Berlin');

function hook() {
	System::println("starting hook of Socket");
	$settings = new SettingsImpl();
	$user = new LowLevelImpl($settings);

	$debug = new DebugImpl($settings, $user);
	$settings->initialize($debug);
	$packages = new PackagesImpl($debug, $settings);
	$mods = new ModsImpl($debug, $settings);
	$interact = new InteractImpl($settings, $debug, $user);
	$socket = new SocketImpl($debug, $interact, $mods, $packages, $user, $settings);

	System::println("initiating System using Socket " . $socket);

	if(!System::initiate($socket)) {
		System::hardClose("Could not initiate Socket!");
	}
}

function main(string $config_path = null) {
	init($config_path);
	load_all_packages();
	load_mods();
	load_theme();
	eoe();
}

/**
 * A simple function, that can be used to check if the package has been correctly loaded
 *
 * @return boolean Return true
 */
function integrated() {
	return true;
}

/**
 * This function is trying to load the config of the Framework.
 * Trys to run read_config()
 *
 * @param string $config_path
 * @return bool Wether or not the config could be loaded
 */
function init(string $config_path = null) {

	$_SESSION['System'] = [
		"debug" => [
			"system_debug" => [],
			"custom" => []
		],
		"info" => []
	];

	if(settings()['dev_mode']) {
		ini_set("display_errors", "1");
		error_reporting(E_ALL | E_STRICT);
	} else {
		ini_set("display_errors", "0");
	}

	force_settings_reload();

	if(isset (info()['root_path'])) {
		return read_config(info()['root_path']) && read_theme_config();
	} else {
		return read_config($config_path) && read_theme_config();
	}
}

function eoe() {
	register_shutdown_function(function() {
		$test_file_path = info()['test_file'];
		if(settings()['use_test_file'] && file_exists($test_file_path)) {
			include $test_file_path;
		}
		open_info_frame();
		open_debug_frame();
	});
}

{
	$root = dirname(__FILE__);

//	require $root . '/main.functions.php';
//	require $root . '/main.debug.functions.php';
//	require $root . '/main.settings.functions.php';
//	require $root . '/main.mods.functions.php';
//	require $root . '/main.todo.functions.php';
//	require $root . '/main.interact.functions.php';
//	require $root . '/main.packages.functions.php';
//	require $root . '/main.pseudo.functions.php';
		echo "loading from " . $root;
		ob_flush();
	require $root . '/DebugImpl.class.implementation.php';
	require $root . '/InteractImpl.class.implementation.php';
	require $root . '/LowLevelImpl.class.implementation.php';
	require $root . '/ModsImpl.class.implementation.php';
	require $root . '/PackagesImpl.class.implementation.php';
	require $root . '/SettingsImpl.class.implementation.php';
	require $root . '/SocketImpl.class.implementation.php';
}