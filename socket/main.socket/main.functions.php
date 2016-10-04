<?php namespace Main; ?>
<?php

date_default_timezone_set ('Europe/Berlin');

function debug_iniload ($what) {
	system_debug ("iniload_error_log", $what);
}

/**
 * This function is loading the whole active theme.
 * It should only be used after the init() function.
 * However, the manual use of this function is highly discuraged!
 *
 * @return mixed Returns the returnstatement of the iniload\main() function
 */
function load_theme () {
	if (session_status () !== PHP_SESSION_ACTIVE)
		session_start ();

	debug_iniload ("(Title)loading_theme_stacktrace");

	$error_debug_log = array (
		"(Notice)Folgende Funktion(en) wurde(n) nicht korrekt ausgeführt:"
	);
	$a = read_config ();
	if (!$a) array_push ($error_debug_log, "(Warning)function read_config()");

	$b = read_theme_config ();
	if (!$b) array_push ($error_debug_log, "(Warning)function read_theme_dir()");

	$c = include_theme_header ();
	if (!$c) array_push ($error_debug_log, "(Warning)function include_theme_header()");

	$d = include_theme_body ();
	if (!$d) array_push ($error_debug_log, "(Warning)function include_theme_body()");

	$e = include_theme_nav ();
	if (!$e) array_push ($error_debug_log, "(Warning)function include_theme_nav()");

	if ($a && $b && $c && $d && $e) debug_iniload ("Alle Theme Bestandteile richtig geladen");
	else debug_iniload ($error_debug_log);

	return ($a && $b && $c && $d && $e);
}

function include_theme_body () {
	if (is_logdin ()) {
		debug_iniload ("trying to load theme-body for logdin user");
		$theme_nav = info ()['active_theme_path'] . "index_logd_in.php";
		if (file_exists ($theme_nav)) {
			debug_iniload ("loaded theme-body for logdin user");
			include $theme_nav;
			return true;
		} else {
			$theme_nav = info ()['active_theme_path'] . "index.php";
			if (file_exists ($theme_nav)) {
				include $theme_nav;
				debug_iniload ("(Notice)could not locate login-specific file. Used fallback!");
				return true;
			} else {
				debug_iniload ("(Error)error loading. Could not find any file at all! Continue");
				return false;
			}
		}
	} else {
		debug_iniload ("trying to load theme-body for not logdin user");
		$theme_nav = info ()['active_theme_path'] . "index_not_logd_in.php";
		if (file_exists ($theme_nav)) {
			include $theme_nav;
			debug_iniload ("loaded theme-body for not logdin user");
			return true;
		} else {
			$theme_nav = info ()['active_theme_path'] . "index.php";
			if (file_exists ($theme_nav)) {
				include $theme_nav;
				debug_iniload ("(Notice)could not locate login-specific file. Used fallback!");
				return true;
			} else {
				debug_iniload ("(Error)error loading. Could not find any file at all! Continue");
				return false;
			}
		}
	}
}

function include_theme_header () : bool {
	if (is_logdin ()) {
		debug_iniload ("trying to load theme-header for logdin user");
		$theme_nav = info ()['active_theme_path'] . "header_logd_in.php";
		if (file_exists ($theme_nav)) {
			debug_iniload ("loaded theme-header for logdin user");
			include $theme_nav;
			return true;
		} else {
			$theme_nav = info ()['active_theme_path'] . "header.php";
			if (file_exists ($theme_nav)) {
				include $theme_nav;
				debug_iniload ("(Notice)could not locate login-specific file. Used fallback!");
				return true;
			} else {
				debug_iniload ("(Error)error loading. Could not find any file at all! Continue");
				return false;
			}
		}
	} else {
		debug_iniload ("trying to load theme-header for not logdin user");
		$theme_nav = info ()['active_theme_path'] . "header_not_logd_in.php";
		if (file_exists ($theme_nav)) {
			debug_iniload ("loaded theme-header for not logdin user");
			include $theme_nav;
			return true;
		} else {
			$theme_nav = info ()['active_theme_path'] . "header.php";
			if (file_exists ($theme_nav)) {
				include $theme_nav;
				debug_iniload ("(Notice)could not locate login-specific file. Used fallback!");
				return true;
			} else {
				debug_iniload ("(Error)error loading. Could not find any file at all! Continue");
				return false;
			}
		}
	}
}

function include_theme_nav () {
	if (is_logdin ()) {
		debug_iniload ("trying to load theme-navigation for logdin user");
		// Nutzer ist eingeloggt
		$theme_nav = info ()['active_theme_path'] . "nav_logd_in.php";
		if (file_exists ($theme_nav)) {
			debug_iniload ("loaded theme-navigation for logdin user");
			return include $theme_nav;
		} else {
			$theme_nav = info ()['active_theme_path'] . "nav.php";
			if (file_exists ($theme_nav)) {
				include $theme_nav;
				debug_iniload ("(Notice)could not locate login-specific file. Used fallback!");
				return true;
			} else {
				debug_iniload ("(Error)error loading. Could not find any file at all! Continue");
				return false;
			}
		}
	} else {
		debug_iniload ("trying to load theme-navigation for not logdin user");
		$theme_nav = info ()['active_theme_path'] . "nav_not_logd_in.php";
		if (file_exists ($theme_nav)) {
			debug_iniload ("loaded theme-navigation for not logdin user");
			return include $theme_nav;
		} else {
			$theme_nav = info ()['active_theme_path'] . "nav.php";
			if (file_exists ($theme_nav)) {
				include $theme_nav;
				debug_iniload ("(Notice)could not locate login-specific file. Used fallback!");
				return true;
			} else {
				debug_iniload ("(Error)error loading. Could not find any file at all! Continue");
				return false;
			}
		}
	}
}

function config_loaded() {
	return !empty(info());
}

function read_config ($path = null) {
	if(config_loaded()) {
		top_level_debug("read_config", "(Notice)The config loaded more than 1 time!", false, "loading");
		return true;
	}

	if ($path !== null) {
		top_level_debug("read_config", "trying to load the config with given path:" . $path, false, "loading");
		if (strpos ($path, "config.php") === false) {
			if ($path [count ($path) - 1] == "/") {
				$path = $path . "config.php";
			} else {
				$path = $path . "/config.php";
			}
		}

		if (file_exists (realpath ($path))) {
			top_level_debug("read_config", "(Notice)Found config at " . $path, false, "loading");
			return require_once $path;
		} else {
			top_level_debug("read_config", "(Notice)Could not find the config at " . $path, false, "loading");
		}
	}

	$path = realpath ("config.php");
	if (file_exists ($path)) {
		top_level_debug("read_config", "(Notice)Found config at " . $path, false, "loading");
		require_once $path;
	} else {
		$counter = 0;
		$max_rec = 5;
		while ( !file_exists ($path) && $counter < $max_rec ) {
			$path = realpath ("../" . $path);
			if (file_exists ($path) && basename ($path) == "config.php") {
				$counter += $max_rec;
				top_level_debug("read_config", "(Notice)Found config at " . $path, false, "loading");
				return require_once $path;
			} else {
				$counter ++;
				if ($counter == $max_rec) {
					top_level_debug("read_config", "(Notice)This algorithm could not find the config ... Last searched place: " . $path, false, "loading");
					return false;
				}
			}
		}
	}

	settings ();

	return true;
}

function read_theme_config () {
	if(empty(info())) {
		return false;
	}
	$dir = info ()['active_theme_path'];
	if (is_dir ($dir)) {
		if (file_exists ($dir . "config.php")) {
			return require_once ($dir . "config.php");
		} else {
			return false;
		}
	} else {
		return false;
	}
}


?>
