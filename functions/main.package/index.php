<?php namespace Main; ?>
<?php

date_default_timezone_set ( 'Europe/Berlin' );

function main() {
	init ();
	load_mods ();
	iniload ();
	system_debug ();
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
 * @return boolean Wether or not the config could be loaded
 */
function init() {
	if (session_status () !== PHP_SESSION_ACTIVE) {
		session_start ();
	}
	
	if (isset ( $_SESSION ['debug_content'] ) && ! empty ( $_SESSION ['debug_content'] ))
		unset ( $_SESSION ['debug_content'] );
	
	if (isset ( $_SESSION ['abspath'] )) {
		if (! read_config ( $_SESSION ['abspath'] ) || ! read_theme_config ())
			return false;
	} else {
		if (! read_config () || ! read_theme_config ())
			return false;
	}
	return true;
}



require 'main.functions.php';
require 'main.mods.functions.php';
require 'main.user.functions.php';
require 'main.db.functions.php';
require 'main.todo.functions.php';
require 'main.interact.functions.php';
require 'main.debug.functions.php';
?>