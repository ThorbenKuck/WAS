<?php

namespace Main;

function debug_package_loading($to_debug) {
	system_debug("package_include_algorithm", $to_debug);
}


function load_all_packages() : bool {
	debug_package_loading("(Title)package_loading_stacktrace");
	if(!settings()['package_usage']) {
		debug_package_loading("(Warning)Package usage has been disabled");
		debug_package_loading("This may cause many problems! You can reenable it in the settings.ini file!");
		return false;
	}
	$requirement_folder = info()['root_path'] . settings()['package_requirement_folder'];
	$package_folder = info()['root_path'] . settings()['package_folder'];
	$corrupted_packages = [];
	$packages_used = [];
	$packages_filenames = [];
	$packages = get_all_packages($package_folder);
	if($packages == null) {
		debug_package_loading("(Fatal-Error)Could not read the package folder!");
		die("<hr>An error accrued!</br>This incident has been log'ed. We are working on it!<hr>");
	}
	debug_package_loading(["(StackStart)Found .package folders", $packages , "(StackEnd)"]);
	$requirements = get_requirements($requirement_folder);
	if($requirements == null) {
		debug_package_loading("(Fatal-Error)Could not read the package-requirements folder!");
		return false;
	}
	debug_package_loading(["(StackStart)All requirements for mods", $requirements, "(StackEnd)"]);
	for ( $i = 0 ; $i < count ($packages) ; $i ++ ) {
		if (is_dir ($packages [$i])) {
			//Try open the directory for the current package
			$packages [$i] .= "/";
			for ( $j = 0 ; $j < count ($requirements) ; $j ++ ) {
				//Then search for the required Files
				//if they do not exist, mark the package as corrupt and go on
				if (array_search ($packages [$i], $corrupted_packages) !== false) {
				} else if (!file_exists ($packages [$i] . $requirements [$j])) {

					debug_package_loading("(Warning)file not found: \"" . $packages [$i] . $requirements [$j] . "\"");
					if (copy_required_file ($package_folder . "/requirements/" . $requirements [$j], $packages [$i]) === false) {

						array_push($corrupted_packages, $packages[$i]);
						debug_package_loading("(Error)could not coppy: \"" . $requirements [$j] . "\"! Dropping " . $packages_filenames [$i] . "!" . "<br>" . " Make sure that php has the rights to modify this Folder!");
						debug_package_loading($packages [$i]);
					} else {
						debug_package_loading("(Notice)took example file: \"" . $requirements [$j] . "\"");
					}
				}
			}
			//Lastly try to include the index.php file of the current package and continue onwards
			if (file_exists ($packages [$i] . "index.php") && array_search ($packages [$i], $corrupted_packages) === false) {
				debug_package_loading($packages[$i]);
				array_push($packages_used, basename($packages[$i]));
				require_once ($packages [$i] . "index.php");
			}
		}
	}
	/**
	 * This last part is only here for debug purposes!
	 */
	for ( $i = 0 ; $i < count ($corrupted_packages) ; $i ++ ) {
		debug_package_loading("(Notice): \"" . $corrupted_packages [$i] . "\" will not be used!");
	}

	debug_package_loading(["(StackStart)used packages", $packages_used, "(StackEnd)"]);

	set_new_info("loaded_packages", $packages_used);

	return true;
}


/**
 * Try to copy a file to a destination
 *
 * @param string $required_file The path to the file that you want to copy
 * @param string $target_folder The destination, where the file should be copied
 * @param boolean $debug_errors Wether or not the function shall output error-messages
 *
 * @return null    If the parameters are wrong
 * @return false    If the file could not be copied
 * @return true        If the file was succesfully copied
 */
function copy_required_file ($required_file, $target_folder, $debug_errors = true) {
	debug_package_loading("trying to copy " . $required_file . " to " . $target_folder);
	//Check the given parameters
	//return null if anything is wrong with them
	if (!is_dir ($target_folder)) {
		if ($debug_errors)
			debug_package_loading("The given given parameter: target_folder, is not a folder");
		return null;
	}
	if (empty ($required_file) || !is_string ($required_file)) {
		if ($debug_errors)
			debug_package_loading("The given given parameter: required_file, is not correct");
		return null;
	}
	if (!file_exists ($required_file) || !is_file ($required_file)) {
		if ($debug_errors)
			debug_package_loading("The given given parameter: required_file, does not exist or is not a file");
		return null;
	}
	if (file_exists ($target_folder . basename ($required_file))) {
		if ($debug_errors)
			debug_package_loading("The destination already contains the file");
		return null;
	}

	//The file, that shall be created
	$target = $target_folder . basename ($required_file);

	//Try to copy the file, using it copy() command
	//If it fails, try to create the file
	//If all fails, return false, so that
	if (copy ($required_file, $target) === false) {
		if (!is_writable ($target)) {
			if ($debug_errors)
				debug_package_loading("Zieldatei kann nicht erstellt werden!");
			return false;
		}
		if ($file = fopen ($target, "w")) {
			if (fwrite ($file, "Could not copy file! Please modify it yourself!")) {
				fclose ($file);
				return true;
			} else {
				if ($debug_errors)
					debug_package_loading("Konnte die Zieldatei nicht beschreiben");
			}
		} else {
			if ($debug_errors)
				debug_package_loading("Konnte die Zieldatei nicht erstellen");
		}
		return false;
	}
	//the copy function was succefull
	system_debug("package_include_algorithm", "Ok");
	return true;
}

function get_requirements($requirement_folder) : array {
	$requirements = [];
	debug_package_loading("finding all requirements .. ");
	/**
	 * This parti am  is reading the required files from the "functions/required" folder
	 * Any file in ther is needed in any package, that will be used.
	 * You can modify the folder, if needed. This will change the requirements for packages.
	 */
	if (is_dir ($requirement_folder)) {
		// open directory to required files
		if ($handle = opendir ($requirement_folder)) {
			while ( ($file = readdir ($handle)) !== false ) {
				// read and safe all files
				if (filetype ($requirement_folder . $file) !== "dir") {
					array_push ($requirements, $file);
				}
			}
			closedir ($handle);
		}
	} else {

	}
	debug_package_loading("done!");
	return $requirements;
}

function get_all_packages(string $packages_dir) {
	debug_package_loading("reading the package folder ..");
	$packages = [];
	/**
	 * This part is reading the whole "functions" folder
	 * It is searching for any folder wi$_SESSION['loaded_packages'] = array();th ".package" in its name
	 * A syntax of
	 * "something.package.somethingelse"
	 * is possible, if so pleased.
	 */
	if (is_dir ($packages_dir)) {
		// open directory to functions
		if ($handle = opendir ($packages_dir)) {
			while ( ($file = readdir ($handle)) !== false ) {
				// read and safe every file with ".package" in its name, aswell as its path
				if (strpos ($file, ".package") !== false) {
					$path = realpath ($packages_dir . "/" . $file);
					array_push ($packages, $path);
				}
			}
			debug_package_loading("done");
			closedir ($handle);
			return $packages;
		} else {
			debug_package_loading("(Error)could not open the packages directory: \"" . $packages_dir . "\"!");
			return null;
		}
	} else if(is_dir(info()['root_path'] . $packages_dir)) {

	} else {
		debug_package_loading("(Error)The given folder: \"" . $packages_dir . "\" is not a valid directory!");
		return [];
	}
}
