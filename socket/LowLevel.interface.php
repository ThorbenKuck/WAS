<?php
/**
 * Created by PhpStorm.
 * User: thorben
 * Date: 11.09.17
 * Time: 16:18
 */

namespace Main;


interface LowLevel {
	function login (string $username, int $permissions, string $identifier = "", bool $keep_logedin = false,
					int $keep_logdin_time = (60 * 60 * 24 * 7)) : bool;

	function login_locked () : bool;

	function logout () ;

	function disable_login ();

	function enable_login ();

	function is_logdin () : bool;

	function get_username () : string;

	function get_permissions () : int;

	function get_identifier () : string;

	function is_normal_user () : bool;

	function is_admin () : bool;

	function is_root () : bool;
}