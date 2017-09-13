<?php
/**
 * Created by PhpStorm.
 * User: thorben
 * Date: 11.09.17
 * Time: 15:59
 */

namespace Main;


interface Socket {
	public function debug () : Debug;

	public function interact () : Interact;

	public function mods () : Mods;

	public function packages () : Packages;

	public function user_base () : LowLevel;

	public function settings() : Settings;

	public function load_theme () : bool;

	public function include_theme_body () : bool;

	public function include_theme_header () : bool;

	public function include_theme_nav () : bool;

	public function config_loaded () : bool;

	public function read_config ($path = null) : bool;

	public function read_theme_config () : bool;

}