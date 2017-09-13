<?php
/**
 * Created by PhpStorm.
 * User: thorben
 * Date: 11.09.17
 * Time: 16:56
 */

namespace Main;


class InteractImpl implements Interact {

	private $settings;
	private $debug;
	private $user_base;

	public function __construct (Settings $settings, Debug $debug, LowLevel $user_base) {
		$this->settings = $settings;
		$this->debug = $debug;
		$this->user_base = $user_base;
	}

	/**
	 * TODO return String not echo!
	 *
	 * @param $file
	 * @return bool
	 */
	function uploadFile ($file) : bool {
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

	function include_css ($path_to_css, $in_theme = true) : bool {
		if ($in_theme)
			$path_to_css = str_replace($this->settings->info()['root_path'] , "" , $this->settings->info()['active_theme_path'] . $path_to_css);

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

	function include_other_php ($path, $once_only = true) : bool {
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

	function include_javascript ($path_to_javascript) : bool {
		if (file_exists ( realpath ( $path_to_javascript ) )) {
			// $this->debug_to_console("include_javascript: ".$path_to_javascript);
			echo "<script type='text/javascript' src='" . $path_to_javascript . "'>
														</script>";
			return true;
		} else {
			$this->debug->system_debug("package_include_algorithm", "(Warning)Could not include the given Javascript: " . realpath($path_to_javascript));
			return false;
		}
	}

	function return_new_dialogbox ($string1, $width, $height, $max_width, $max_height, $path_to_include, $string2,
								   $security = false, $admin = false) : bool {
		$message1 = $string1;
		$message2 = $string2;
		// $_SESSION['dialogbox'] ist der absolute Pfad zur dialogbox
		$dir = $this->settings->info()['active_theme_path'];
		$success = false;

		if (is_dir ( $dir )) {
			if (file_exists ( $dir . "/dialogbox.php" )) {
				$success = require $dir . "/dialogbox.php";
				unset($message1);
				unset($message2);
				unset($string1);
				unset($width);
				unset($height);
				unset($max_width);
				unset($max_height);
				unset($path_to_include);
				unset($string2);
				unset($security);
				unset($admin);
			}
		}

		return $success;
	}

	function return_new_error ($string) : bool {
		$message = $string;
		$dir = $this->settings->info()['active_theme_path'];

		if (is_dir ( $dir )) {
			if (file_exists ( $dir . "/error.php" )) {
				return require $dir . "/error.php";
			}
		}
	}

	function return_new_warning ($string) : bool {
		$message = $string;
		$dir = $this->settings->info()['active_theme_path'];

		if (is_dir ( $dir )) {
			if (file_exists ( $dir . "/warning.php" )) {
				return require $dir . "/warning.php";
			}
		}
	}

	function open_debug_frame (): bool {
		$settings = $this->settings->access();
		if ($settings['debug_mode'] && $settings['open_debug_window']) {
			if ($settings['admin_only_debug_window']) {
				$user = $this->user_base;
				if ($user->is_logdin () && ($user->is_admin () || $user->is_root ())) {
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

	function open_info_frame (): bool {
		$settings = $this->settings->access();
		if ($settings['debug_mode'] && $settings['open_info_window']) {
			if ($settings['admin_only_info_window']) {
				$user = $this->user_base;
				if ($user->is_logdin () && ($user->is_admin () || $user->is_root ())) {
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

