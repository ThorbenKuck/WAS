<?php
/**
 * Created by PhpStorm.
 * User: thorben
 * Date: 11.09.17
 * Time: 16:09
 */

namespace Main;


interface Mods {
	function mod_debug ($what);

	function mods_enabled () : bool;

	function load_mods () : bool;

	function load_mod ($mod) : bool;

	function read_mod_config ($mod) : bool;

	function check_for_required_frameworks ($required, $mod_name) : bool;

	function read_mods_folder ($path = "mods/") : array;

	function check_requirements ($mod, $try_copy = true) : bool;

	function get_mod_requirements ($path = "mods/requirements/") : array;
}