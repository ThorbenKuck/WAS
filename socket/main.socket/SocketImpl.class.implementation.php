<?php
/**
 * Created by PhpStorm.
 * User: thorben
 * Date: 11.09.17
 * Time: 16:55
 */

namespace Main;

class SocketImpl implements Socket {

	private $debug;
	private $interact;
	private $mods;
	private $packages;
	private $low_level;
	private $settings;

	public function __construct (Debug $debug, Interact $interact, Mods $mods, Packages $packages, LowLevel $lowLevel, Settings $settings) {
		$this->debug = $debug;
		$this->interact = $interact;
		$this->mods = $mods;
		$this->packages = $packages;
		$this->low_level = $lowLevel;
		$this->settings = $settings;
	}

	public function __destruct () {
		$this->debug()->top_level_debug("Socket", "Socket shutdown initiated!");
		unset($this->debug);
		unset($this->interact);
		unset($this->mods);
		unset($this->packages);
		unset($this->low_level);
		unset($this->settings);
	}

	public function interact () : Interact {
		return $this->interact;
	}

	public function mods () : Mods {
		return $this->mods;
	}

	public function packages () : Packages {
		return $this->packages;
	}

	public function user_base () : LowLevel {
		return $this->low_level;
	}

	public function debug () : Debug {
		return $this->debug;
	}

	public function settings () : Settings {
		return $this->settings;
	}

	public function load_theme () : bool {
		if (session_status () !== PHP_SESSION_ACTIVE)
			session_start ();

		$this->debug_iniload ("(Title)loading_theme_stacktrace");

		$error_debug_log = array (
			"(Notice)Folgende Funktion(en) wurde(n) nicht korrekt ausgeführt:"
		);
		$error = false;
		if (!$this->read_config ()) {
			array_push ($error_debug_log, "(Warning)function read_config()");
			$error = true;
		}

		if (!$this->read_theme_config ()) {
			array_push ($error_debug_log, "(Warning)function read_theme_dir()");
		}

		if (!$this->include_theme_header ()) {
			array_push ($error_debug_log, "(Warning)function include_theme_header()");
		}

		if (!$this->include_theme_body ()) {
			array_push ($error_debug_log, "(Warning)function include_theme_body()");
		}

		if (!$this->include_theme_nav ()) {
			array_push ($error_debug_log, "(Warning)function include_theme_nav()");
		}

		if (!$error) {
			$this->debug_iniload ("Alle Theme Bestandteile richtig geladen");
			return true;
		} else {
			$this->debug_iniload ($error_debug_log);
			return false;
		}
	}

	private function debug_iniload ($what) {
		$this->debug ()->system_debug ("iniload_error_log", $what);
	}

	public function read_theme_config () : bool {
		if(empty($this->settings->info())) {
			return false;
		}
		$dir =$this->settings()->info()['active_theme_path'];
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

	public function include_theme_header () : bool {
		if ($this->user_base()->is_logdin ()) {
			$this->debug_iniload ("trying to load theme-header for logdin user");
			$theme_nav = $this->settings()->info ()['active_theme_path'] . "header_logd_in.php";
			if (file_exists ($theme_nav)) {
				$this->debug_iniload ("loaded theme-header for logdin user");
				include $theme_nav;
				return true;
			} else {
				$theme_nav = $this->settings()->info ()['active_theme_path'] . "header.php";
				if (file_exists ($theme_nav)) {
					include $theme_nav;
					$this->debug_iniload ("(Notice)could not locate login-specific file. Used fallback!");
					return true;
				} else {
					$this->debug_iniload ("(Error)error loading. Could not find any file at all! Continue");
					return false;
				}
			}
		} else {
			$this->debug_iniload ("trying to load theme-header for not logdin user");
			$theme_nav = $this->settings()->info ()['active_theme_path'] . "header_not_logd_in.php";
			if (file_exists ($theme_nav)) {
				$this->debug_iniload ("loaded theme-header for not logdin user");
				include $theme_nav;
				return true;
			} else {
				$theme_nav =$this->settings()->info()['active_theme_path'] . "header.php";
				if (file_exists ($theme_nav)) {
					include $theme_nav;
					$this->debug_iniload ("(Notice)could not locate login-specific file. Used fallback!");
					return true;
				} else {
					$this->debug_iniload ("(Error)error loading. Could not find any file at all! Continue");
					return false;
				}
			}
		}
	}

	public function include_theme_body () : bool {
		if ($this->user_base()->is_logdin ()) {
			$this->debug_iniload ("trying to load theme-body for logdin user");
			$theme_nav = $this->settings()->info ()['active_theme_path'] . "index_logd_in.php";
			if (file_exists ($theme_nav)) {
				$this->debug_iniload ("loaded theme-body for logdin user");
				include $theme_nav;
				return true;
			} else {
				$theme_nav = $this->settings()->info ()['active_theme_path'] . "index.php";
				if (file_exists ($theme_nav)) {
					include $theme_nav;
					$this->debug_iniload ("(Notice)could not locate login-specific file. Used fallback!");
					return true;
				} else {
					$this->debug_iniload ("(Error)error loading. Could not find any file at all! Continue");
					return false;
				}
			}
		} else {
			$this->debug_iniload ("trying to load theme-body for not logdin user");
			$theme_nav =$this->settings()->info()['active_theme_path'] . "index_not_logd_in.php";
			if (file_exists ($theme_nav)) {
				include $theme_nav;
				$this->debug_iniload ("loaded theme-body for not logdin user");
				return true;
			} else {
				$theme_nav =$this->settings()->info()['active_theme_path'] . "index.php";
				if (file_exists ($theme_nav)) {
					include $theme_nav;
					$this->debug_iniload ("(Notice)could not locate login-specific file. Used fallback!");
					return true;
				} else {
					$this->debug_iniload ("(Error)error loading. Could not find any file at all! Continue");
					return false;
				}
			}
		}
	}

	public function include_theme_nav () : bool {
		if ($this->user_base()->is_logdin ()) {
			$this->debug_iniload ("trying to load theme-navigation for logdin user");
			// Nutzer ist eingeloggt
			$theme_nav = $this->settings()->info ()['active_theme_path'] . "nav_logd_in.php";
			if (file_exists ($theme_nav)) {
				$this->debug_iniload ("loaded theme-navigation for logdin user");
				return include $theme_nav;
			} else {
				$theme_nav =$this->settings()->info()['active_theme_path'] . "nav.php";
				if (file_exists ($theme_nav)) {
					include $theme_nav;
					$this->debug_iniload ("(Notice)could not locate login-specific file. Used fallback!");
					return true;
				} else {
					$this->debug_iniload ("(Error)error loading. Could not find any file at all! Continue");
					return false;
				}
			}
		} else {
			$this->debug_iniload ("trying to load theme-navigation for not logdin user");
			$theme_nav =$this->settings()->info()['active_theme_path'] . "nav_not_logd_in.php";
			if (file_exists ($theme_nav)) {
				$this->debug_iniload ("loaded theme-navigation for not logdin user");
				return include $theme_nav;
			} else {
				$theme_nav =$this->settings()->info()['active_theme_path'] . "nav.php";
				if (file_exists ($theme_nav)) {
					include $theme_nav;
					$this->debug_iniload ("(Notice)could not locate login-specific file. Used fallback!");
					return true;
				} else {
					$this->debug_iniload ("(Error)error loading. Could not find any file at all! Continue");
					return false;
				}
			}
		}
	}

	public function config_loaded () : bool {
		return !empty($this->settings()->info()) && (isset($this->settings()->info()['active_theme_path']) && ! empty(isset($this->settings()->info()['active_theme_path'])));
	}

	public function read_config ($path = null) : bool {
		if($this->config_loaded()) {
			$this->debug()->top_level_debug("read_config", "(Notice)The config loaded more than 1 time!", false, "loading");
			return true;
		}

		if ($path !== null) {
			$this->debug()->top_level_debug("read_config", "trying to load the config with given path:" . $path, false, "loading");
			if (strpos ($path, "config.php") === false) {
				if ($path [count ($path) - 1] == "/") {
					$path = $path . "config.php";
				} else {
					$path = $path . "/config.php";
				}
			}

			if (file_exists (realpath ($path))) {
				$this->debug()->top_level_debug("read_config", "(Notice)Found config at " . $path, false, "loading");
				return require_once $path;
			} else {
				$this->debug()->top_level_debug("read_config", "(Notice)Could not find the config at " . $path, false, "loading");
			}
		}

		$path = realpath ("config.php");
		if (file_exists ($path)) {
			$this->debug()->top_level_debug("read_config", "(Notice)Found config at " . $path, false, "loading");
			require_once $path;
		} else {
			$counter = 0;
			$max_rec = 5;
			while ( !file_exists ($path) && $counter < $max_rec ) {
				$path = realpath ("../" . $path);
				if (file_exists ($path) && basename ($path) == "config.php") {
					$this->debug()->top_level_debug("read_config", "(Notice)Found config at " . $path, false, "loading");
					return require_once $path;
				} else {
					$counter ++;
					if ($counter == $max_rec) {
						$this->debug()->top_level_debug("read_config", "(Notice)This algorithm could not find the config ... Last searched place: " . $path, false, "loading");
						return false;
					}
				}
			}
		}

		$this->settings()->access();

		return true;
	}

	public function __toString() : string {
		return "Socket!";
	}
}