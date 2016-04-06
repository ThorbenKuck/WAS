<?php

/**
 * Given variables.
 * These will not be changed
 */
// name of the used direktion
$functions_dir = dirname ( __FILE__ );

// folder of the files, required for any package
$requirement_folder = $functions_dir . "/requirements/";

/**
 * Calculated variables
 * These will be calculated one time
 */

// required files in packages
// will be found in the folder "required"
$requirements = array ();

// realpath of the found packages, mainly for logic
$packages = array ();

// realpath of the found packages, mainly for logic
$packages = array ();

/**
 * For debug and partly for logic
 */
// errors / warnings occurred while reading the packages
$package_errors = array ();

// all found packages in the $functions_dir
$packages_included = array ();

// packages wich did not contain the required files
// and packages where the copy of the required files failed
$corrupted_packages = array ();

// names of the found packages, mainly for logic
$packages_filenames = array ();

$loaded_packages = array("Die verwendeten packages sind:");



/**
 * Try to copy a file to a destination 
 * 
 * @param string 	$required_file	The path to the file that you want to copy
 * @param string 	$target_folder	The destination, where the file should be copied
 * @param boolean	$echo_errors	Wether or not the function shall output error-messages
 * 
 * @return null 	If the parameters are wrong
 * @return false	If the file could not be copied
 * @return true		If the file was succesfully copied
 */
function copy_required_file($required_file, $target_folder, $echo_errors = false) {
	//Check the given parameters
	//return null if anything is wrong with them
	if (! is_dir ( $target_folder )){
		if($echo_errors)
			echo "The given given parameter: target_folder, is not a folder";
		return null;
	}
	if (empty ( $required_file ) || ! is_string ( $required_file )) {
		if($echo_errors)
			echo "The given given parameter: required_file, is not correct";
		return null;
	}
	if (! file_exists ( $required_file ) || ! is_file ( $required_file )) {
		if($echo_errors)
			echo "The given given parameter: required_file, does not exist or is not a file";
		return null;
	}
	if (file_exists ( $target_folder . basename ( $required_file ) )) {
		if($echo_errors)
			echo "The destination already contains the file";
		return null;
	}
	
	//The file, that shall be created
	$target = $target_folder . basename ( $required_file );
	
	//Try to copy the file, using it copy() command
	//If it fails, try to create the file
	//If all fails, return false, so that 
	if ( @copy ( $required_file, $target ) === false) {
		if(!is_writable($target)) {
			if($echo_errors)
				echo "Zieldatei kann nicht erstellt werden!";
			return false;
		}
		if ($file = fopen ( $target, "w" )) {
			if (fwrite ( $file, "Could not copy file! Please modify it yourself!" )) {
				fclose ( $file );
				return true;
			} else {
				if($echo_errors)
					echo "Konnte die Zieldatei nicht beschreiben";
			}
		} else {
			if($echo_errors)
				echo "Konnte die Zieldatei nicht erstellen";
		}
		return false;
	}
	//the copy function was succefull
	return true;
}









/**
 * This parti am  is reading the required files from the "functions/required" folder
 * Any file in ther is needed in any package, that will be used.
 * You can modify the folder, if needed. This will change the requirements for packages.
 */
if (is_dir ( $requirement_folder )) {
	// open directory to required files
	if ($handle = opendir ( $requirement_folder )) {
		while ( ($file = readdir ( $handle )) !== false ) {
			// read and safe all files
			if (filetype ( $requirement_folder . $file ) !== "dir") {
				array_push ( $requirements, $file );
			}
		}
		closedir ( $handle );
	}
}

/**
 * This part is reading the whole "functions" folder
 * It is searching for any folder wi$_SESSION['loaded_packages'] = array();th ".package" in its name
 * A syntax of
 * "something.package.somethingelse"
 * is possible, if so pleased.
 */
if (is_dir ( $functions_dir )) {
	// open directory to functions
	if ($handle = opendir ( $functions_dir )) {
		
		array_push ( $packages_included, "gefundene .package Ordner:" );
		while ( ($file = readdir ( $handle )) !== false ) {
			// read and safe every file with ".package" in its name, aswell as its path
			if (strpos ( $file, ".package" ) !== false) {
				$path = realpath ( $functions_dir . "/" . $file );
				array_push ( $packages, $path );
				array_push ( $packages_included, $path );
				array_push ( $packages_filenames, $file );
			}
		}
		closedir ( $handle );
	}
}

/**
 * This sektion is only trying to include all packages found.
 * If any package does not fullfil the required standarts, aka. does not contain the required files, it will be marked and debuged as corrupted.
 * If any package ist marked as corrupted, the algorithm tries to copy sample files into the package
 */
for($i = 0; $i < count ( $packages ); $i ++) {
	if (is_dir ( $packages [$i] )) {
		//Try open the directory for the current package
		$packages [$i] .= "/";
		for($j = 0; $j < count ( $requirements ); $j ++) {
			if(strpos($packages [$i], "main.package") !== false) {
				continue;
			}
			//Then search for the required Files
			//if they do not exist, mark the package as corrupt and go on
			if (array_search ( $packages [$i], $corrupted_packages ) !== false) {
			} else if (! file_exists ( $packages [$i] . $requirements [$j] )) {
				
				array_push ( $package_errors, "(Warning): file not found: \"" . $packages [$i] . $requirements [$j]."\"" );
				if ( copy_required_file ( $functions_dir . "/requirements/" . $requirements [$j], $packages [$i] ) === false) {
					
					array_push ( $package_errors, "(Error): could not coppy: \"" . $requirements [$j] . "\"! Dropping " . $packages_filenames [$i] . "!"."<br>"." Make sure that php has the rights to modify this Folder!" );
					array_push ( $corrupted_packages, $packages [$i] );
				} else {
					array_push ( $package_errors, "(Info): took example file: \"" . $requirements [$j] ."\"");
				}
			}
		}
		require_once "main.package/index.php";
		//Lastly try to include the index.php file of the current package and continue onwards
		if (file_exists ( $packages [$i] . "index.php" ) && array_search ( $packages [$i], $corrupted_packages ) === false ) {
			array_push($loaded_packages, $packages_filenames[$i]);
			require_once ($packages [$i] . "index.php");
		}
		//fallback!
	}
}

/**
 * This last part is only here for debug purposes!
 */
for($i = 0; $i < count ( $corrupted_packages ); $i ++) {
	$corrupted_packages [$i] = "(Warning): \"" . $corrupted_packages [$i] . "\" will not be used!";
}

$_SESSION ["package_include_algortihm"] = array (
		$packages_included,
		$package_errors,
		$corrupted_packages,
		$loaded_packages
);


$_SESSION['loaded_packages'] = array ( $loaded_packages );

?>