<?php namespace Main; ?>
<?php

date_default_timezone_set ( 'Europe/Berlin' );
function uploadFile($file) {
	if ($_FILES ["fileToUpload"] ["type"] !== "application/octet-stream") {
		echo "<font color=\"red\" size=\"4\">" . "Die Datei ist nicht im richtigen Format (CSV-Datei)!" . "</font>";
		return false;
	}
	if ($_FILES ["fileToUpload"] ["size"] > 100000) {
		echo "<font color=\"red\" size=\"4\">" . "Die Datei ist zu groß!" . "</font>";
		return false;
	}
	if (move_uploaded_file ( $_FILES ["fileToUpload"] ["tmp_name"], $target_file )) {
		return true;
	} else {
		echo "<font color=\"red\" size=\"4\">" . "Es gab einen Fehler beim hochladen der Datei" . "</font>";
		return false;
	}
}
function include_css($path_to_css, $in_theme = true) {
	if (SUCCES !== true) {
		read_config ( "../vpm-config.php" );
	}
	if ($in_theme)
		$path_to_css = CSS_ACTIVE_THEME . $path_to_css;
	
	if (file_exists ( realpath ( $path_to_css ) )) {
		echo '
		<head>
			<link rel="stylesheet" type="text/css" href="' . $path_to_css . '" />
		</head>
		';
		return true;
	} else {
		debug_input ( "Das css: " . $path_to_css . " konnte nicht geladen werden!" );
		return false;
	}
}
function include_other_php($path, $once_only = true) {
	// $core = $this->get_core();
	if ($once_only) {
		if (SUCCES) {
			$path = ACTIVE_THEME . $path;
		} else {
			$path = $_SESSION ['active_theme'] . $path;
		}
		// $debug_to_console("include_other_php: ".$path);
		if (file_exists ( $path )) {
			require_once ($path);
			return true;
		} else
			return false;
	} else {
		if (SUCCES) {
			$path = ACTIVE_THEME . $path;
		} else {
			$path = $_SESSION ['active_theme'] . $path;
		}
		// $this->debug_to_console("include_other_php: ".$path);
		if (file_exists ( $path )) {
			require ($path);
			return true;
		} else
			return false;
	}
}
function include_javascript($path_to_javascript) {
	$path_to_javascript = CSS_ACTIVE_THEME . $path_to_javascript;
	if (file_exists ( realpath ( $path_to_javascript ) )) {
		// $this->debug_to_console("include_javascript: ".$path_to_javascript);
		echo "<script type='text/javascript' src='" . $path_to_javascript . "'>
														</script>";
		return true;
	} else {
		return false;
	}
	return false;
}
function return_new_dialogbox($string1, $width, $height, $max_width, $max_height, $path_to_include, $string2, $security = false, $admin = false) {
	$message1 = $string1;
	$message2 = $string2;
	// $_SESSION['dialogbox'] ist der absolute Pfad zur dialogbox
	if (SUCCES === 1)
		$dir = ACTIVE_THEME;
	else
		$dir = $_SESSION ['active_theme'];
	
	if (is_dir ( $dir )) {
		if (file_exists ( $dir . "/dialogbox.php" )) {
			return require $dir . "/dialogbox.php";
		}
	}
}
function return_new_error($string) {
	$message = $string;
	if (SUCCES === 1)
		$dir = ACTIVE_THEME;
	else
		$dir = $_SESSION ['active_theme'];
	
	if (is_dir ( $dir )) {
		if (file_exists ( $dir . "/error.php" )) {
			return require $dir . "/error.php";
		}
	}
}
function return_new_warning($string) {
	$message = $string;
	if (SUCCES === 1)
		$dir = ACTIVE_THEME;
	else
		$dir = $_SESSION ['active_theme'];
	
	if (is_dir ( $dir )) {
		if (file_exists ( $dir . "/warning.php" )) {
			return require $dir . "/warning.php";
		}
	}
}

/**
 * Open the debug-frame.
 * It is not reccomended to use this function. It is used by the logic.
 * It does NOT check, wheter or not the current user is logd in and if he is admin/root!
 * It also does not check, wheter or not the debug-frame was correctly opend
 * However, if you feel like you need to use it, feel free to do so.
 *
 * @version 1.0
 *         
 * @return boolean Returns the state of DEBUG_MODE
 */
function open_debug_frame() {
	if (DEBUG_MODE) {
		if (ADMIN_ONLY_DEBUG) {
			if (is_logdin () && (is_admin () || is_root ())) {
				echo "<script type='text/javascript'>open_new_window();</script>";
				return true;
			} else {
				echo "<script type='text/javascript'>close_window();</script>";
				return false;
			}
		} else {
			echo "<script type='text/javascript'>open_new_window();</script>";
			return true;
		}
	} else {
		echo "<script type='text/javascript'>close_window();</script>";
		return false;
	}
}

?>




<script>
var myWindow;
function open_new_window() {
	if (!myWindow || myWindow.closed) {
		myWindow = window.open("vpm-debug.php", "Debug-Window", "toolbar=no, scrollbars=yes, width=500, height=500");
		myWindow.focus();
		return false;
	} else {
		myWindow.close();
		myWindow = window.open("vpm-debug.php", "Debug-Window", "toolbar=no, scrollbars=yes, width=500, height=500");
		myWindow.focus();
		return false;
	}
}

function close_window() {
	myWindow.close();
}
</script>

