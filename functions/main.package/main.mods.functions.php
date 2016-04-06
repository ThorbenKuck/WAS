<?php namespace Main;?>
<?php
date_default_timezone_set ( 'Europe/Berlin' );
/**
 * Mod-Function section.
 */

/**
 * 
 * @return NULL|boolean
 */
function load_mods() {
	if(!defined('MOD_USAGE') || !MOD_USAGE) {
		if(!isset($_SESSION['mods_loader_errors'])) {
			$_SESSION['mods_loader_errors'] = array();
		}
		array_push ( $_SESSION ['mods_loader_errors'], "(Notice): mod-usage has been disabled. You can reenable it in the vpm-config.php file" );
		return null;
	}
	
	$all_mods = read_mods_folder ();
	$corrupted_mods = array ();
	
	if (count ( $all_mods ) == 0) {
		return true;
	}
	
	for($i = 0; $i < count ( $all_mods ); $i ++) {
		if (! check_requirements ( $all_mods [$i] [0] )) {
			array_push ( $_SESSION ['mods_loader_errors'], "(Error): the mod: \"" . $all_mods [$i] [1] . "\" will not be used " );
			array_push ( $corrupted_mods, $all_mods [$i] [1] );
		}
	}
	for($i = 0; $i < count ( $all_mods ); $i ++) {
		if (array_search ( $all_mods [$i] [1], $corrupted_mods ) === false) {
			$config = read_mod_config ( $all_mods [$i] [0] );
			if (! check_for_required_frameworks ( $config ["required"], $all_mods [$i] [1] )) {
				array_push ( $corrupted_mods, $all_mods [$i] );
				array_push ( $_SESSION ['mods_loader_errors'], "(Error): the mod: \"" . $all_mods [$i] [1] . "\" will not be used " );
			} else {
				if ($config ["activ"]) {
					if (! load_mod ( $all_mods [$i] [0] )) {
						array_push ( $_SESSION ['mods_loader_errors'], "(Error): the mod: \"" . $all_mods [$i] [1] . "\" could not be loaded correctly " );
					}
				} else {
					array_push ( $_SESSION ['mods_loader_errors'], "(Info): the mod: \"" . $all_mods [$i] [1] . "\" will not be used, because it is not activated." . "<br>" . "change \"active\" to \"true\"in the config.json to activat it!" );
				}
			}
		}
	}
	return true;
}
function load_mod($mod) {
	if(!defined('MOD_USAGE') || !MOD_USAGE) {
		return null;
	}
	if (file_exists ( realpath ( $mod . "/index.php" ) )) {
		return require_once realpath ( $mod . "/index.php" );
	}
	return false;
}
function find_mods_folder($hint = null) {
}
function read_mod_config($mod) {
	if(!defined('MOD_USAGE') || !MOD_USAGE) {
		return null;
	}
	$return;
	if (! is_dir ( $mod )) {
		return null;
	} else {
		$json_data = file_get_contents ( realpath ( $mod . "/config.json" ) );
		$return = json_decode ( $json_data, true );
	}
	return $return;
}
function check_for_required_frameworks($required, $mod_name) {
	if(!defined('MOD_USAGE') || !MOD_USAGE) {
		return null;
	}
	$loaded_packages = $_SESSION ['loaded_packages'];
	$lost_requirements = array ();
	$needed = count ( $required );
	$correct = true;
	
	for($i = 0; $i < count ( $required ); $i ++) {
		for($j = 0; $j < count ( $loaded_packages [0] ); $j ++) {
			if (array_search ( $required [$i], $loaded_packages [0] ) === false) {
				if (array_search ( $required [$i], $lost_requirements ) === false) {
					array_push ( $_SESSION ['mods_loader_errors'], "(Warning): the mod: \"" . $mod_name . "\" is missing " . $required [$i] );
					array_push ( $lost_requirements, $required [$i] );
					$correct = false;
				}
			}
		}
	}
	
	return $correct;
}
/**
 *
 * @param string $path
 *        	The path to the mods folder
 * @return array Multidimensional array with the paths and the names of the mods
 */
function read_mods_folder($path = "mods/") {
	if(!defined('MOD_USAGE') || !MOD_USAGE) {
		return null;
	}
	$mod_names = array ();
	$mods_folder = array ();
	$return_array = array ();
	
	if (! isset ( $_SESSION ['mods_loader_errors'] ))
		$_SESSION ['mods_loader_errors'] = array ();
	if (defined ( 'ABSPATH' )) {
		$path = realpath ( ABSPATH . $path );
		if (! is_dir ( $path )) {
			array_push ( $_SESSION ['mods_loader_errors'], "(Error): did not found the correct mod-folder! Droping mod read algorithm!" );
			return false;
		}
	} else {
		array_push ( $_SESSION ['mods_loader_errors'], "(Error): config not loaded! Droping mod read algorithm!" );
		return false;
	}
	
	if (is_dir ( $path )) {
		// open directory to functions
		if ($handle = opendir ( $path )) {
			array_push ( $_SESSION ['mods_loader_errors'], "Gefundene mods Ordner:" );
			while ( ($file = readdir ( $handle )) !== false ) {
				// read and safe every file with ".package" in its name, aswell as its path
				if (strpos ( $file, ".mod" ) !== false) {
					$current_path = realpath ( $path . "/" . $file );
					if (is_dir ( $current_path )) {
						array_push ( $mods_folder, $current_path );
						array_push ( $mod_names, $file );
					}
				}
			}
			
			array_push ( $_SESSION ['mods_loader_errors'], $mod_names );
			closedir ( $handle );
		}
	} else {
		array_push ( $_SESSION ['mods_loader_errors'], "(Error): " . $path . " is not a correct directory! Droping mod read algorithm!" );
		return false;
	}
	
	for($i = 0; $i < count ( $mods_folder ); $i ++) {
		$return_array [$i] [0] = $mods_folder [$i] . "/";
		$return_array [$i] [1] = $mod_names [$i];
	}
	
	return $return_array;
}

/**
 *
 * @param string $mod
 *        	Path to the mod, you wanna check
 * @param boolean $try_copy
 *        	On fail, try to copy requirements?
 * @return boolen whether or not the mods fullfill the requirements (now)
 */
function check_requirements($mod, $try_copy = true) {
	if(!defined('MOD_USAGE') || !MOD_USAGE) {
		return null;
	}
	$requirements = get_mod_requirements ();
	$success = true;
	
	for($j = 0; $j < count ( $requirements ); $j ++) {
		// Then search for the required Files
		$target = $mod . $requirements [$j] [1];
		if (! file_exists ( $target )) {
			if ($try_copy) {
				array_push ( $_SESSION ['mods_loader_errors'], "(Warning): the mod: \"" . $mod . "\" is missing: \"" . $requirements [$j] [1] . "\"" );
				if (@copy ( $requirements [$j] [0], $target ) === false) {
					if (! is_writable ( $target )) {
						array_push ( $_SESSION ['mods_loader_errors'], "(Error): the mod: \"" . $mod . "\" is not writeable! could not create \"" . $target . "\"" . "<br>" . " Please make sure, that php has the rights to modify this folder!" );
						$success = false;
					} else if ($file = fopen ( $target, "w" )) {
						if (fwrite ( $file, "Could not copy file! Please modify it yourself!" )) {
							fclose ( $file );
						} else {
							array_push ( $_SESSION ['mods_loader_errors'], "(Error): could not create: \"" . $target . "\"" );
							$success = false;
						}
					} else {
						array_push ( $_SESSION ['mods_loader_errors'], "(Fatal-Error): While creating: \"" . $target . "\"" );
						$success = false;
					}
				}
			} else {
				array_push ( $_SESSION ['mods_loader_errors'], "(Warning): the mod: \"" . $mod . "\" is missing: \"" . $requirements [$j] [1] . "\"" . "<br>" . "Enable the copy parameter to make this algorithm try to creat the file" );
				$success = false;
			}
		}
	}
	return $success;
}
function get_mod_requirements($path = "mods/requirements/") {
	if(!defined('MOD_USAGE') || !MOD_USAGE) {
		return null;
	}
	$requirements = array ();
	$requirements_name = array ();
	$requirements_path = array ();
	
	if (defined ( 'ABSPATH' )) {
		$path = ABSPATH . $path;
	}
	
	if (is_dir ( $path )) {
		// open directory to functions
		if ($handle = opendir ( $path )) {
			while ( ($file = readdir ( $handle )) !== false ) {
				// read and safe every file with ".package" in its name, aswell as its path
				if (! is_dir ( $file )) {
					array_push ( $requirements_name, $file );
					array_push ( $requirements_path, $path . $file );
				}
			}
			closedir ( $handle );
		}
	} else {
		array_push ( $_SESSION ['mods_loader_errors'], "(Error): " . $path . " is not a correct directory! Droping requirements algorithm!" );
		return false;
	}
	
	for($i = 0; $i < count ( $requirements_name ); $i ++) {
		$requirements [$i] [0] = $requirements_path [$i];
		$requirements [$i] [1] = $requirements_name [$i];
	}
	
	return $requirements;
}

?>