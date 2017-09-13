<?php
/**
 * Created by PhpStorm.
 * User: thorben
 * Date: 11.09.17
 * Time: 16:13
 */

namespace Main;


interface Packages {
	function debug_package_loading($to_debug);

	function load_all_packages() : bool;

	function copy_required_file ($required_file, $target_folder, $debug_errors = true) : bool;

	function get_requirements($requirement_folder) : array;

	function get_all_packages(string $packages_dir);
}