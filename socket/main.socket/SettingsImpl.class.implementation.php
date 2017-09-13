<?php
/**
 * Created by PhpStorm.
 * User: thorben
 * Date: 11.09.17
 * Time: 16:57
 */

namespace Main;


class SettingsImpl implements Settings {

	private $debug;
	private $config_path;

	public function __construct(Debug $debug = null, string $config_path = null) {
		$this->debug = $debug;
		$this->config_path = $config_path;
		$_SESSION['System'] = [
			"debug" => [
				"system_debug" => [],
				"custom" => []
			],
			"info" => []
		];
	}

	public function update_setting(string $key, $new_value) : bool {
		if(array_key_exists($key, $_SESSION['System']['settings'])) {
			$_SESSION['System']['settings'][$key] = $new_value;
			return true;
		} else {
			$this->debug->debug_input(["(Notice)Failed to update setting \"" . $key . "\" to \"" . $new_value . "\" because this key does not exist"]);
			return false;
		}
	}

	public function set_new_setting(string $key, $value) : bool {
		if(!array_key_exists($key, $_SESSION['System']['settings'])) {
			$_SESSION['System']['settings'][$key] = $value;
			return true;
		} else {
			$this->debug->debug_input(["(Notice)Failed to add new setting \"" . $key . "=" . $value . "\" because this key is already in use with \"" . $key . "=" . $_SESSION['System']['settings'][$key] . "\""]);
			return false;
		}
	}

	public function set_setting(string $key, $value) : bool {
		$_SESSION['System']['settings'][$key] = $value;
		return true;
	}

	public function set_info(string $key, $value) : bool {
		return (!array_key_exists($key, $_SESSION['System']['info']) ? $this->set_new_info($key, $value) : $this->update_info($key, $value));
	}

	public function set_new_info(string $key, $value) : bool {
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
			$this->debug->top_level_debug("info", ["(Notice)Failed to add new info \"" . ($key) . "=" . ($value) . "\" because this key is already in use with \"" . ($key) . "=" . ($new_value) . "\""]);
			return false;
		}
	}

	public function update_info(string $key, $new_value) : bool {
		if(!isset($_SESSION['System']['info'])) {
			$_SESSION['System']['info'] = [];
		}

		if(array_key_exists($key, $_SESSION['System']['info'])) {
			$_SESSION['System']['info'][$key] = $new_value;
			return true;
		} else {
			$this->debug->debug_input(["(Notice)Failed to update info \"" . $key . "=" . $new_value . "\" because this key does not exist."]);
			return false;
		}
	}

	public function read_config($path = null) {
		if($this->config_loaded()) {
			$this->debug->top_level_debug("read_config", "(Notice)The config loaded more than 1 time!", false, "loading");
			return true;
		}

		if($path !== null) {
			$this->debug->top_level_debug("read_config", "trying to load the config with given path:" . $path, false, "loading");
			if(strpos($path, "config.php") === false) {
				if($path [count($path) - 1] == "/") {
					$path = $path . "config.php";
				} else {
					$path = $path . "/config.php";
				}
			}

			if(file_exists(realpath($path))) {
				$this->debug->top_level_debug("read_config", "(Notice)Found config at " . $path, false, "loading");
				return require_once $path;
			} else {
				$this->debug->top_level_debug("read_config", "(Notice)Could not find the config at " . $path, false, "loading");
			}
		}

		$path = realpath("config.php");
		if(file_exists($path)) {
			$this->debug->top_level_debug("read_config", "(Notice)Found config at " . $path, false, "loading");
			require_once $path;
		} else {
			$counter = 0;
			$max_rec = 5;
			while(!file_exists($path) && $counter < $max_rec) {
				$path = realpath("../" . $path);
				if(file_exists($path) && basename($path) == "config.php") {
					$this->debug->top_level_debug("read_config", "(Notice)Found config at " . $path, false, "loading");
					return require_once $path;
				} else {
					$counter ++;
					if($counter == $max_rec) {
						$this->debug->top_level_debug("read_config", "(Notice)This algorithm could not find the config ... Last searched place: " . $path, false, "loading");
						return false;
					}
				}
			}
		}

		// Call access to load configurations for the first time
		$this->access();

		return true;
	}

	public function config_loaded() {
		return !empty($this->info());
	}

	public function info() : array {
		if(isset($_SESSION['System']['info'])) {
			return $_SESSION['System']['info'];
		} else {
			return [];
		}
	}

	public function access() : array {
		if(isset($_SESSION['System']['settings']) && !empty($_SESSION['System']['settings'])) {
			return $_SESSION['System']['settings'];
		} else {
			$settings = \System::getSettings();
			foreach($settings as $key => $value) {
				$_SESSION['System']['settings'][$key] = $value;
			}
			return $this->access();
		}
	}

	public function accessCertain(string $what) {
		return array_key_exists($what, $this->access()) ? $this->access()[$what] : "";
	}

	public function initialize(Debug $debug, $config_path = null) {
		$this->debug = $debug;
		if($this->access()['dev_mode']) {
			ini_set("display_errors", "1");
			error_reporting(E_ALL | E_STRICT);
		} else {
			ini_set("display_errors", "0");
		}

		$this->force_settings_reload();
	}

	public function force_settings_reload() : bool {
		unset($_SESSION['System']['settings']);
		return !empty($this->access());
	}
}