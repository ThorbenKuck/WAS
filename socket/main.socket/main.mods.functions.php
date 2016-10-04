<?php
namespace Main;
date_default_timezone_set ('Europe/Berlin');
/**
 * Mod-Function section.
 */


function mod_debug ($what) {
	system_debug ("mods_loader_errors", $what);
}

function mods_enabled () {
	return settings ()['mod_usage'];
}

function load_mods () {
	if (!mods_enabled ()) {
		mod_debug ("(Notice)mod-usage has been disabled. You can reenable it in the " . "<b>" . "\"settings.ini\" file" . "</b>");
		return null;
	}

	mod_debug ("(Title)modification_stacktrace");

	$all_mods = read_mods_folder ();
	$corrupted_mods = [];

	if (count ($all_mods) == 0) {
		return true;
	}

	for ( $i = 0 ; $i < count ($all_mods) ; $i ++ ) {
		if (!check_requirements ($all_mods [$i] [0])) {
			$current = str_replace ("(li)", "", $all_mods [$i] [1]);
			mod_debug ("(Error)the mod: <b>\"" . $current . "\"</b> will not be used ");
			array_push ($corrupted_mods, $all_mods [$i] [1]);
		}
	}
	for ( $i = 0 ; $i < count ($all_mods) ; $i ++ ) {
		if (array_search ($all_mods [$i] [1], $corrupted_mods) === false) {
			$config = read_mod_config ($all_mods [$i] [0]);
			if (!check_for_required_frameworks ($config ["required"], $all_mods [$i] [1])) {
				array_push ($corrupted_mods, $all_mods [$i]);
				$current = str_replace ("(li)", "", $all_mods [$i] [1]);
				mod_debug ("(Error)the mod: \"" . $current . "\" will not be used ");
			} else {
				if ($config ["activ"]) {
					if (!load_mod ($all_mods [$i] [0])) {
						mod_debug ("(Error)the mod: \"" . $all_mods [$i] [1] . "\" could not be loaded correctly ");
					}
				} else {
					mod_debug ("(Notice)the mod: \"" . $all_mods [$i] [1] . "\" will not be used, because it is not activated." . "<br>" . "change \"active\" to \"true\"in the settings.json to activat it!");
				}
			}
		}
	}
	return true;
}

function load_mod ($mod) {
	if (!mods_enabled ()) {
		return null;
	}
	if (file_exists (realpath ($mod . "/index.php"))) {
		return require_once realpath ($mod . "/index.php");
	}
	return false;
}

function read_mod_config ($mod) {
	if (!mods_enabled ()) {
		return null;
	}
	$return = "";
	if (!is_dir ($mod)) {
		return null;
	} else {
		$json_data = file_get_contents (realpath ($mod . "/config.json"));
		$return = json_decode ($json_data, true);
	}
	return $return;
}

function check_for_required_frameworks ($required, $mod_name) {
	if (!mods_enabled ()) {
		return null;
	}
	$loaded_packages = info()['loaded_packages'];
	$lost_requirements = [];
	$correct = true;

	for ( $i = 0 ; $i < count ($required) ; $i ++ ) {
		if (array_search ($required [$i], $loaded_packages) === false) {
			mod_debug ("(Warning)the mod: \"" . $mod_name . "\" is missing " . $required [$i]);
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
function read_mods_folder ($path = "mods/") {
	if (!mods_enabled ()) {
		return null;
	}
	$mod_names = [];
	$mods_folder = [];
	$return_array = [];

	if (isset(info()['root_path'])) {
		$path = realpath (info()['root_path'] . $path);

		if (!is_dir ($path)) {
			mod_debug ("(Fatal-Error)did not found the correct mod-folder! Droping mod read algorithm!");
			return [];
		}
	} else {
		mod_debug ("(Fatal-Error)config not loaded! Droping mod read algorithm!");
		return [];
	}

	if (is_dir ($path)) {
		// open directory to functions
		if ($handle = opendir ($path)) {
			mod_debug ("Gefundene mods Ordner:");
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
				mod_debug ("-- NONE --");
			} else {
				mod_debug (["(StackStart)Found mod folders", $mod_names, "(StackEnd)"]);
			}
			closedir ($handle);
		}
	} else {
		mod_debug ("(Fatal-Error)" . $path . " is not a correct directory! Droping mod read algorithm!");
		return [];
	}

	for ( $i = 0 ; $i < count ($mods_folder) ; $i ++ ) {
		$return_array [$i] [0] = $mods_folder [$i] . "/";
		$return_array [$i] [1] = $mod_names [$i];
	}

	return $return_array;
}

/**
 *
 * @param string $mod
 *            Path to the mod, you wanna check
 * @param boolean $try_copy
 *            On fail, try to copy requirements?
 * @return bool whether or not the mods fullfill the requirements (now)
 */
function check_requirements ($mod, $try_copy = true) {
	if (!mods_enabled ()) {
		return null;
	}
	$requirements = get_mod_requirements ();
	$success = true;

	for ( $j = 0 ; $j < count ($requirements) ; $j ++ ) {
		// Then search for the required Files
		$target = $mod . $requirements [$j] [1];
		if (!file_exists ($target)) {
			if ($try_copy) {
				mod_debug ("(Warning)the mod: \"" . $mod . "\" is missing: \"" . $requirements [$j] [1] . "\" .. trying to copy");
				if (copy ($requirements [$j] [0], $target) === false) {
					if (!is_writable ($target)) {
						mod_debug ("(Error)the mod: \"" . $mod . "\" is not writeable! could not create \"" . $target . "\"" . "<br>" . " Please make sure, that php has the rights to modify this folder!");
						$success = false;
					} else if ($file = fopen ($target, "w")) {
						if (fwrite ($file, "(Info)Could not copy file! Please modify it yourself!")) {
							fclose ($file);
						} else {
							mod_debug ("(Error)could not create: \"" . $target . "\"");
							$success = false;
						}
					} else {
						mod_debug ("(Fatal-Error)While creating: \"" . $target . "\"");
						$success = false;
					}
				} else {
					mod_debug("(Notice)Copy successful");
					$success = true;
				}
			} else {
				mod_debug ("(Warning)the mod: \"" . $mod . "\" is missing: \"" . $requirements [$j] [1] . "\"! Enable the copy parameter to make this algorithm try to creat the file");
				mod_debug("Enable the copy parameter to make this algorithm try to creat the file");
				$success = false;
			}
		}
	}
	return $success;
}

function get_mod_requirements ($path = "mods/requirements/") {
	if (!mods_enabled ()) {
		return null;
	}
	$requirements = [];
	$requirements_name = [];
	$requirements_path = [];

	if (isset(info()['root_path'])) {
		$path = info()['root_path'] . $path;
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
		mod_debug ("(Error)" . $path . " is not a correct directory! Droping requirements algorithm!");
		return false;
	}

	for ( $i = 0 ; $i < count ($requirements_name) ; $i ++ ) {
		$requirements [$i] [0] = $requirements_path [$i];
		$requirements [$i] [1] = $requirements_name [$i];
	}

	return $requirements;
}

?>