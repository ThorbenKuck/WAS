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
	if (move_uploaded_file ( $_FILES ["fileToUpload"] ["tmp_name"], $file )) {
		return true;
	} else {
		echo "<font color=\"red\" size=\"4\">" . "Es gab einen Fehler beim hochladen der Datei" . "</font>";
		return false;
	}
}
function include_css($path_to_css, $in_theme = true) {

	if ($in_theme)
		$path_to_css = str_replace(info()['root_path'] , "" , info()['active_theme_path'] . $path_to_css);
	
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
    $path = pathinfo(debug_backtrace()[0]['file'])['dirname'] . '/' . $path;
	if ($once_only) {
		if (file_exists ( $path )) {
			require_once ($path);
			return true;
		} else
			return false;
	} else {
		if (file_exists ( $path )) {
			require ($path);
			return true;
		} else
			return false;
	}
}
function include_javascript($path_to_javascript) {
	if (file_exists ( realpath ( $path_to_javascript ) )) {
		// $this->debug_to_console("include_javascript: ".$path_to_javascript);
		echo "<script type='text/javascript' src='" . $path_to_javascript . "'>
														</script>";
		return true;
	} else {
		debug_package_loading("(Warning)Could not include the given Javascript: " . realpath($path_to_javascript));
		return false;
	}
}
function return_new_dialogbox($string1, $width, $height, $max_width, $max_height, $path_to_include, $string2, $security = false, $admin = false) {
	$message1 = $string1;
	$message2 = $string2;
	// $_SESSION['dialogbox'] ist der absolute Pfad zur dialogbox
    $dir = info()['active_theme_path'];
	
	if (is_dir ( $dir )) {
		if (file_exists ( $dir . "/dialogbox.php" )) {
			return require $dir . "/dialogbox.php";
		}
	}
}
function return_new_error($string) {
	$message = $string;
    $dir = info()['active_theme_path'];
	
	if (is_dir ( $dir )) {
		if (file_exists ( $dir . "/error.php" )) {
			return require $dir . "/error.php";
		}
	}
}
function return_new_warning($string) {
	$message = $string;
	$dir = info()['active_theme_path'];
	
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
	$settings = settings();
	if ($settings['debug_mode'] && $settings['open_debug_window']) {
		if ($settings['admin_only_debug_window']) {
			if (is_logdin () && (is_admin () || is_root ())) {
				echo "<script type='text/javascript'>open_new_debug_window();</script>";
				return true;
			} else {
				echo "<script type='text/javascript'>close_debug_window();</script>";
				return false;
			}
		} else {
			echo "<script type='text/javascript'>open_new_debug_window();</script>";
			return true;
		}
	} else {
		echo "<script type='text/javascript'>close_debug_window();</script>";
		return false;
	}
}

function open_info_frame() {
    $settings = settings();
	if ($settings['debug_mode'] && $settings['open_info_window']) {
		if ($settings['admin_only_info_window']) {
			if (is_logdin () && (is_admin () || is_root ())) {
				echo "<script type='text/javascript'>open_new_info_window();</script>";
				return true;
			} else {
				echo "<script type='text/javascript'>close_info_window();</script>";
				return false;
			}
		} else {
			echo "<script type='text/javascript'>open_new_info_window();</script>";
			return true;
		}
	} else {
		echo "<script type='text/javascript'>close_info_window();</script>";
		return false;
	}
}


?>




<script>
var myDebugWindow;
var myInfoWindow;

function open_new_debug_window() {
	if (!myDebugWindow || myDebugWindow.closed) {
		myDebugWindow = window.open("debug.php", "Debug-Window", "toolbar=no, scrollbars=yes, width=500, height=500");
		myDebugWindow.focus();
		return false;
	} else {
		myDebugWindow.close();
		myDebugWindow = window.open("debug.php", "Debug-Window", "toolbar=no, scrollbars=yes, width=500, height=500");
		myDebugWindow.focus();
		return false;
	}
}

function open_new_info_window() {
	if (!myInfoWindow || myInfoWindow.closed) {
		myInfoWindow = window.open("info.php", "Info-Window", "toolbar=no, scrollbars=yes, width=500, height=500");
		myInfoWindow.focus();
		return false;
	} else {
		myInfoWindow.close();
		myInfoWindow = window.open("info.php", "Info-Window", "toolbar=no, scrollbars=yes, width=500, height=500");
		myInfoWindow.focus();
		return false;
	}
}

function close_debug_window() {
	if(myDebugWindow != false && myInfoWindow != null) {
		myDebugWindow.close();
	}
}

function close_info_window() {
	if(myInfoWindow != false && myInfoWindow != null) {
		myInfoWindow.close();
	}
}

</script>

