<?php
/**
 * Created by PhpStorm.
 * User: thorben
 * Date: 11.09.17
 * Time: 16:06
 */

namespace Main;


interface Interact {
	function uploadFile ($file) : bool;

	function include_css ($path_to_css, $in_theme = true) : bool;

	function include_other_php ($path, $once_only = true) : bool;

	function include_javascript ($path_to_javascript) : bool;

	function return_new_dialogbox($string1, $width, $height, $max_width, $max_height, $path_to_include, $string2, $security = false, $admin = false) : bool;

	function return_new_error($string) : bool;

	function return_new_warning($string) : bool;

	function open_debug_frame(): bool;

	function open_info_frame(): bool;
}