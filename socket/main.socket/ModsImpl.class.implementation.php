<?php
/**
 * Created by PhpStorm.
 * User: thorben
 * Date: 11.09.17
 * Time: 16:57
 */

namespace Main;


class ModsImpl implements Mods {

	private $debug;
	private $settings;

	public function __construct (Debug $debug, Settings $settings) {
		$this->debug = $debug;
		$this->settings = $settings;
	}

	function mod_debug ($what) {
		$this->debug->system_debug ("mods_loader_errors", $what);
	}

	function mods_enabled () : bool {
		return $this->settings->access()['mod_usage'];
	}

	function load_mods () : bool {
		if (!$this->mods_enabled ()) {
			$this->mod_debug ("(Notice)mod-usage has been disabled. You can reenable it in the " . "<b>" . "\"settings.ini\" file" . "</b>");
			return null;
		}

		$this->mod_debug ("(Title)modification_stacktrace");

		$all_mods = $this->read_mods_folder ();
		$corrupted_mods = [];

		if (count ($all_mods) == 0) {
			return true;
		}

		for ( $i = 0 ; $i < count ($all_mods) ; $i ++ ) {
			if (!$this->check_requirements ($all_mods [$i] [0])) {
				$current = str_replace ("(li)", "", $all_mods [$i] [1]);
				$this->mod_debug ("(Error)the mod: <b>\"" . $current . "\"</b> will not be used ");
				array_push ($corrupted_mods, $all_mods [$i] [1]);
			}
		}
		for ( $i = 0 ; $i < count ($all_mods) ; $i ++ ) {
			if (array_search ($all_mods [$i] [1], $corrupted_mods) === false) {
				$config = $this->read_mod_config ($all_mods [$i] [0]);
				if (!$this->check_for_required_frameworks ($config ["required"], $all_mods [$i] [1])) {
					array_push ($corrupted_mods, $all_mods [$i]);
					$current = str_replace ("(li)", "", $all_mods [$i] [1]);
					$this->mod_debug ("(Error)the mod: \"" . $current . "\" will not be used ");
				} else {
					if ($config ["activ"]) {
						if (!$this->load_mod ($all_mods [$i] [0])) {
							$this->mod_debug ("(Error)the mod: \"" . $all_mods [$i] [1] . "\" could not be loaded correctly ");
						}
					} else {
						$this->mod_debug ("(Notice)the mod: \"" . $all_mods [$i] [1] . "\" will not be used, because it is not activated." . "<br>" . "change \"active\" to \"true\"in the settings.json to activat it!");
					}
				}
			}
		}
		return true;
	}

	function load_mod ($mod) : bool {
		if (!$this->mods_enabled ()) {
			return null;
		}
		if (file_exists (realpath ($mod . "/index.php"))) {
			return require_once realpath ($mod . "/index.php");
		}
		return false;
	}

	function read_mod_config ($mod) : bool {
		if (!$this->mods_enabled ()) {
			return null;
		}
		$return = null;
		if (!is_dir ($mod)) {
			return null;
		} else {
			$json_data = file_get_contents (realpath ($mod . "/config.json"));
			$return = json_decode ($json_data, true);
		}
		return $return;
	}

	function check_for_required_frameworks ($required, $mod_name) : bool {
		if (!$this->mods_enabled ()) {
			return null;
		}
		$loaded_packages = $this->settings->info()['loaded_packages'];
		$lost_requirements = [];
		$correct = true;

		for ( $i = 0 ; $i < count ($required) ; $i ++ ) {
			if (array_search ($required [$i], $loaded_packages) === false) {
				$this->mod_debug ("(Warning)the mod: \"" . $mod_name . "\" is missing " . $required [$i]);
				array_push ($lost_requirements, $required [$i]);
				$correct = false;
			}

		}

		return $correct;
	}

	/**
	 *
	 * @param string $path
	 *            The path to the mods folder
	 * @return array Multidimensional array with the paths and the names of the mods
	 */
	function read_mods_folder ($path = "mods/") : array {
		if (!$this->mods_enabled ()) {
			return null;
		}
		$mod_names = [];
		$mods_folder = [];
		$return_array = [];

		if (isset($this->settings->info()['root_path'])) {
			$path = realpath ($this->settings->info()['root_path'] . $path);

			if (!is_dir ($path)) {
				$this->mod_debug ("(Fatal-Error)did not found the correct mod-folder! Droping mod read algorithm!");
				return [];
			}
		} else {
			$this->mod_debug ("(Fatal-Error)config not loaded! Droping mod read algorithm!");
			return [];
		}

		if (is_dir ($path)) {
			// open directory to functions
			if ($handle = opendir ($path)) {
				$this->mod_debug ("Gefundene mods Ordner:");
				while ( ($file = readdir ($handle)) !== false ) {
					// read and safe every file with ".package" in its name, aswell as its path
					if (strpos ($file, ".mod") !== false) {
						$current_path = realpath ($path . "/" . $file);
						if (is_dir ($current_path)) {
							array_push ($mods_folder, $current_path);
							array_push ($mod_names, $file);
						}
					}
				}
				if (sizeof ($mod_names) <= 0) {
					$this->mod_debug ("-- NONE --");
				} else {
					$this->mod_debug (["(StackStart)Found mod folders", $mod_names, "(StackEnd)"]);
				}
				closedir ($handle);
			}
		} else {
			$this->mod_debug ("(Fatal-Error)" . $path . " is not a correct directory! Droping mod read algorithm!");
			return [];
		}

		for ( $i = 0 ; $i < count ($mods_folder) ; $i ++ ) {
			$return_array [$i] [0] = $mods_folder [$i] . "/";
			$return_array [$i] [1] = $mod_names [$i];
		}

		return $return_array;
	}

	function check_requirements ($mod, $try_copy = true) : bool {
		if (!$this->mods_enabled ()) {
			return null;
		}
		$requirements = $this->get_mod_requirements ();
		$success = true;

		for ( $j = 0 ; $j < count ($requirements) ; $j ++ ) {
			// Then search for the required Files
			$target = $mod . $requirements [$j] [1];
			if (!file_exists ($target)) {
				if ($try_copy) {
					$this->mod_debug ("(Warning)the mod: \"" . $mod . "\" is missing: \"" . $requirements [$j] [1] . "\" .. trying to copy");
					if (copy ($requirements [$j] [0], $target) === false) {
						if (!is_writable ($target)) {
							$this->mod_debug ("(Error)the mod: \"" . $mod . "\" is not writeable! could not create \"" . $target . "\"" . "<br>" . " Please make sure, that php has the rights to modify this folder!");
							$success = false;
						} else if ($file = fopen ($target, "w")) {
							if (fwrite ($file, "(Info)Could not copy file! Please modify it yourself!")) {
							} else {
								$this->mod_debug ("(Error)could not create: \"" . $target . "\"");
								$success = false;
							}
							fclose ($file);
						} else {
							$this->mod_debug ("(Fatal-Error)While creating: \"" . $target . "\"");
							$success = false;
						}
					} else {
						$this->mod_debug("(Notice)Copy successful");
						$success = true;
					}
				} else {
					$this->mod_debug ("(Warning)the mod: \"" . $mod . "\" is missing: \"" . $requirements [$j] [1] . "\"! Enable the copy parameter to make this algorithm try to creat the file");
					$this->mod_debug("Enable the copy parameter to make this algorithm try to creat the file");
					$success = false;
				}
			}
		}
		return $success;
	}

	function get_mod_requirements ($path = "mods/requirements/") : array {
		if (!$this->mods_enabled ()) {
			return null;
		}
		$requirements = [];
		$requirements_name = [];
		$requirements_path = [];

		if (isset($this->settings->info()['root_path'])) {
			$path = $this->settings->info()['root_path'] . $path;
		}

		if (is_dir ($path)) {
			// open directory to functions
			if ($handle = opendir ($path)) {
				while ( ($file = readdir ($handle)) !== false ) {
					// read and safe every file with ".package" in its name, aswell as its path
					if (!is_dir ($file)) {
						array_push ($requirements_name, $file);
						array_push ($requirements_path, $path . $file);
					}
				}
				closedir ($handle);
			}
		} else {
			$this->mod_debug ("(Error)" . $path . " is not a correct directory! Droping requirements algorithm!");
			return [];
		}

		for ( $i = 0 ; $i < count ($requirements_name) ; $i ++ ) {
			$requirements [$i] [0] = $requirements_path [$i];
			$requirements [$i] [1] = $requirements_name [$i];
		}

		return $requirements;
	}
}