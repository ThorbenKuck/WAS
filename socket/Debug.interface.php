<?php
/**
 * Created by PhpStorm.
 * User: thorben
 * Date: 11.09.17
 * Time: 16:00
 */

namespace Main;


interface Debug {

	function log ($string, $log_format = "old", $custom_name = null);

	function debug_mod_enabled () : bool;

	function debug_input ($input, $with_file_information = true) : bool;

	function top_level_debug ($top_level_name, $to_debug, $with_trace_back = false, $sub_name = null);

	function find_current_log () : mixed;

	function system_debug (string $routine_name, $to_debug) : bool;

}