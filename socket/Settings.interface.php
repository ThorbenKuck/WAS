<?php
/**
 * Created by PhpStorm.
 * User: thorben
 * Date: 11.09.17
 * Time: 16:24
 */

namespace Main;


interface Settings {

	function access() : array;

	function accessCertain(string $what);

	function force_settings_reload() : bool;

	function update_setting(string $key, $new_value) : bool;

	function set_new_setting(string $key, $value) : bool;

	function set_setting(string $key, $value) : bool;

	function info() : array;

	function update_info(string $key, $new_value) : bool;

	function set_new_info(string $key, $value) : bool;

	function set_info(string $key, $value) : bool;
}