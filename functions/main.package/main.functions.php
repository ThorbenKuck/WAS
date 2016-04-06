<?php namespace Main; ?>
<?php

date_default_timezone_set ( 'Europe/Berlin' );

/**
 * This function is loading the whole active theme.
 * It should only be used after the init() function.
 * However, the manual use of this function is highly discuraged!
 *
 * @return mixed Returns the returnstatement of the iniload\main() function
 */
function iniload() {
	if (session_status () !== PHP_SESSION_ACTIVE)
		session_start ();
	$_SESSION ['iniload_error_log'] = array ();
	$error_debug_log = array (
			"Folgende Funktion(en) wurde(n) nicht korrekt ausgeführt:" 
	);
	$a = read_config ();
	if (! $a)
		array_push ( $error_debug_log, "function read_config()" );
	
	$b = read_theme_config ( );
	if (! $b)
		array_push ( $error_debug_log, "function read_theme_dir()" );
	
	$c = include_theme_header ();
	if (! $c)
		array_push ( $error_debug_log, "function include_theme_header()" );
	date_default_timezone_set ( 'Europe/Berlin' );
	$d = include_theme_body ();
	if (! $d)
		array_push ( $error_debug_log, "function include_thene_body()" );
	
	$e = include_theme_nav ();
	if (! $e)
		array_push ( $error_debug_log, "function include_theme_nav()" );
	
	if ($a && $b && $c && $d && $e)
		array_push ( $_SESSION ['iniload_error_log'], "Alle Theme Bestandteile richtig geladen" );
	else
		array_push ( $_SESSION ['iniload_error_log'], $error_debug_log );
	
	system_debug ();
	return ($a && $b && $c && $d && $e);
}
function include_theme_body() {
	$action_log = array ();
	if (is_logdin ()) {
		array_push ( $action_log, "trying to load theme-body for logdin user" );
		$theme_nav = ACTIVE_THEME . "index_logd_in.php";
		if (file_exists ( $theme_nav )) {
			array_push ( $action_log, "loaded theme-body for logdin user" );
			array_push ( $_SESSION ['iniload_error_log'], $action_log );
			include $theme_nav;
			return true;
		} else {
			$theme_nav = ACTIVE_THEME . "index.php";
			if (file_exists ( $theme_nav )) {
				include $theme_nav;
				array_push ( $action_log, "(Notice): could not locate login-specific file. Used fallback!" );
				array_push ( $_SESSION ['iniload_error_log'], $action_log );
				return true;
			} else {
				array_push ( $action_log, "(Warning): error loading. Could not find any file at all! Continue" );
				array_push ( $_SESSION ['iniload_error_log'], $action_log );
				return false;
			}
		}
	} else {
		array_push ( $action_log, "trying to load theme-body for not logdin user" );
		$theme_nav = ACTIVE_THEME . "index_not_logd_in.php";
		if (file_exists ( $theme_nav )) {
			include $theme_nav;
			array_push ( $action_log, "loaded theme-body for not logdin user" );
			array_push ( $_SESSION ['iniload_error_log'], $action_log );
			return true;
		} else {
			$theme_nav = ACTIVE_THEME . "index.php";
			if (file_exists ( $theme_nav )) {
				include $theme_nav;
				array_push ( $action_log, "(Notice): could not locate login-specific file. Used fallback!" );
				array_push ( $_SESSION ['iniload_error_log'], $action_log );
				return true;
			} else {
				array_push ( $action_log, "(Warning): error loading. Could not find any file at all! Continue" );
				array_push ( $_SESSION ['iniload_error_log'], $action_log );
				return false;
			}
		}
	}
	array_push ( $_SESSION ['iniload_error_log'], "(Error): critical error while loading theme-body! Contact the developer of this framework!" );
	return null;
}
function include_theme_header() {
	$action_log = array ();
	if (is_logdin ()) {
		array_push ( $action_log, "trying to load theme-header for logdin user" );
		$theme_nav = ACTIVE_THEME . "header_logd_in.php";
		if (file_exists ( $theme_nav )) {
			array_push ( $action_log, "loaded theme-header for logdin user" );
			array_push ( $_SESSION ['iniload_error_log'], $action_log );
			include $theme_nav;
			return true;
		} else {
			$theme_nav = ACTIVE_THEME . "header.php";
			if (file_exists ( $theme_nav )) {
				include $theme_nav;
				array_push ( $action_log, "(Notice): could not locate login-specific file. Used fallback!" );
				array_push ( $_SESSION ['iniload_error_log'], $action_log );
				return true;
			} else {
				array_push ( $action_log, "(Warning): error loading. Could not find any file at all! Continue" );
				array_push ( $_SESSION ['iniload_error_log'], $action_log );
				return false;
			}
		}
	} else {
		array_push ( $action_log, "trying to load theme-header for not logdin user" );
		$theme_nav = ACTIVE_THEME . "header_not_logd_in.php";
		if (file_exists ( $theme_nav )) {
			array_push ( $action_log, "loaded theme-header for not logdin user" );
			array_push ( $_SESSION ['iniload_error_log'], $action_log );
			include $theme_nav;
			return true;
		} else {
			$theme_nav = ACTIVE_THEME . "header.php";
			if (file_exists ( $theme_nav )) {
				include $theme_nav;
				array_push ( $action_log, "(Notice): could not locate login-specific file. Used fallback!" );
				array_push ( $_SESSION ['iniload_error_log'], $action_log );
				return true;
			} else {
				array_push ( $action_log, "(Warning): error loading. Could not find any file at all! Continue" );
				array_push ( $_SESSION ['iniload_error_log'], $action_log );
				return false;
			}
		}
	}
	array_push ( $_SESSION ['iniload_error_log'], "(Error): critical error while loading theme-header! Contact the developer of this framework!" );
	return null;
}
function include_theme_nav() {
	$action_log = array ();
	if (is_logdin ()) {
		array_push ( $action_log, "trying to load theme-navigation for logdin user" );
		// Nutzer ist eingeloggt
		$theme_nav = ACTIVE_THEME . "nav_logd_in.php";
		if (file_exists ( $theme_nav )) {
			array_push ( $action_log, "loaded theme-navigation for logdin user" );
			array_push ( $_SESSION ['iniload_error_log'], $action_log );
			return include $theme_nav;
		} else {
			$theme_nav = ACTIVE_THEME . "nav.php";
			if (file_exists ( $theme_nav )) {
				include $theme_nav;
				array_push ( $action_log, "(Notice): could not locate login-specific file. Used fallback!" );
				array_push ( $_SESSION ['iniload_error_log'], $action_log );
				return true;
			} else {
				array_push ( $action_log, "(Warning): error loading. Could not find any file at all! Continue" );
				array_push ( $_SESSION ['iniload_error_log'], $action_log );
				return false;
			}
		}
		return false;
	} else {
		array_push ( $action_log, "trying to load theme-navigation for not logdin user" );
		$theme_nav = ACTIVE_THEME . "nav_not_logd_in.php";
		if (file_exists ( $theme_nav )) {
			array_push ( $action_log, "loaded theme-navigation for not logdin user" );
			array_push ( $_SESSION ['iniload_error_log'], $action_log );
			return include $theme_nav;
		} else {
			$theme_nav = ACTIVE_THEME . "nav.php";
			if (file_exists ( $theme_nav )) {
				include $theme_nav;
				array_push ( $action_log, "(Notice): could not locate login-specific file. Used fallback!" );
				array_push ( $_SESSION ['iniload_error_log'], $action_log );
				return true;
			} else {
				array_push ( $action_log, "(Warning): error loading. Could not find any file at all! Continue" );
				array_push ( $_SESSION ['iniload_error_log'], $action_log );
				return false;
			}
		}
		array_push ( $action_log, "(Warning): error loading. Could not find any file at all! Continue" );
		array_push ( $_SESSION ['iniload_error_log'], $action_log );
		return false;
	}
	array_push ( $_SESSION ['iniload_error_log'], "(Error): critical error while loading theme-navigation! Contact the developer of this framework!" );
	return null;
}

function read_config($path = null) {
	if ($path !== null) {
		if (strpos ( $path, "vpm-config.php" ) === false) {
			if ($path [count ( $path ) - 1] == "/") {
				$path = $path . "vpm-config.php";
			} else {
				$path = $path . "/vpm-config.php";
			}
		}

		if (file_exists ( realpath ( $path ) )) {
			return require_once $path;
		}
	}

	$path = realpath ( "vpm-config.php" );
	if (file_exists ( $path )) {
		require_once $path;
	} else {
		$counter = 0;
		$max_rec = 5;
		while ( ! file_exists ( $path ) && $counter < $max_rec ) {
			$path = realpath ( "../" . $path );
			if (file_exists ( $path ) && basename($path) == "vpm-config.php") {
				$counter = $max_rec + 1;
				require_once $path;
			} else {
				$counter ++;
				if ($counter == $max_rec) {
					return false;
				}
			}
		}
	}

	// Zu erst lösche die alten Angaben
	unset ( $_SESSION ['themes_name'] );
	unset ( $_SESSION ['active_theme_name'] );
	unset ( $_SESSION ['abspath'] );
	unset ( $_SESSION ['classes'] );
	unset ( $_SESSION ['themes'] );
	unset ( $_SESSION ['active_theme'] );
	unset ( $_SESSION ['functions'] );
	unset ( $_SESSION ['css_active_theme'] );
	unset ( $_SESSION ['debug_mode'] );
	unset ( $_SESSION ['dev_mode'] );

	// Dann setzte die neuen Werte
	$_SESSION ['themes_name'] = THEMES_NAME;
	$_SESSION ['active_theme_name'] = ACTIVE_THEME_NAME;
	$_SESSION ['abspath'] = ABSPATH;
	$_SESSION ['classes'] = CLASSES;
	$_SESSION ['themes'] = THEMES;
	$_SESSION ['active_theme'] = ACTIVE_THEME;
	$_SESSION ['functions'] = FUNCTIONS;
	$_SESSION ['css_active_theme'] = CSS_ACTIVE_THEME;
	$_SESSION ['debug_mode'] = DEBUG_MODE;
	$_SESSION ['dev_mode'] = DEV_MODE;
	return true;
}

function read_theme_config() {
	$dir = ACTIVE_THEME;
	if (is_dir ( $dir )) {
		if (file_exists ( $dir . "config.php" )) {
			return require_once ($dir . "config.php");
		} else
			return false;
	} else
		return false;
}



?>
