<?php

namespace Main;

function login (string $username, int $permissions, string $identifier = "", bool $keep_logedin = false,
				int $keep_logdin_time = (60 * 60 * 24 * 7)) : bool {
	if (login_locked ()) {
		return false;
	}
	set_info ("login_username", $username);
	$_SESSION["login_session"]["username"] = $username;
	set_info ("login_permissions", $permissions);
	$_SESSION["login_session"]["permissions"] = $permissions;
	set_info ("identifier", $identifier);
	$_SESSION["login_session"]["identifier"] = $identifier;
	if ($keep_logedin) {
		setcookie ('login', $identifier, $keep_logdin_time);
		var_dump ($_COOKIE['login']);
	}
	set_info ("logdin", true);
	$_SESSION["login_session"]["logdin"] = true;
	return true;
}

function login_locked () {
	return isset($_SESSION["login_session"]["locked"]) ? $_SESSION["login_session"]["locked"] : false;
}

function logout () {
	set_info ("login_username", "");
	$_SESSION["login_session"]["username"] = "Unknown";
	set_info ("login_permissions", - 1);
	$_SESSION["login_session"]["permissions"] = -1;
	set_info ("logdin", false);
	$_SESSION["login_session"]["logdin"] = false;
	set_info("identifier", "-none-");
	$_SESSION["login_session"]["identifier"] = "-none-";
}

function disable_login () {
	set_info ('login_locked', true);
	$_SESSION["login_session"]["locked"] = true;
}

function enable_login () {
	set_info ('login_locked', false);
	$_SESSION["login_session"]["locked"] = false;
}

function is_logdin () : bool {
	$session_login = (isset(info ()['logdin']) ? info ()['logdin'] : false)
		|| isset($_SESSION["login_session"]["logdin"]) ? $_SESSION["login_session"]["logdin"] : false;
	$cookie_login = isset($_COOKIE['login']);
	return (bool) ($session_login || $cookie_login) && !login_locked ();
}

function get_username () : string {
	return (string) isset(info ()['login_username']) ? info ()['login_username'] 
		: (isset($_SESSION["login_session"]["username"]) ? $_SESSION["login_session"]["username"] : "Unknown");
}

function get_permissions () : int {
	return (int) isset(info ()['login_permissions']) ? info ()['login_permissions']
		: (isset($_SESSION["login_session"]["permissions"]) ? $_SESSION["login_session"]["permissions"] : -1);
}

function get_identifier () : string {
	return (string) isset(info ()['identifier']) ? info ()['identifier']
		: (isset($_SESSION["login_session"]["identifier"]) ? $_SESSION["login_session"]["identifier"] : "-none-");
}

function is_normal_user () : bool {
	return (!is_admin () && !is_root ()) && get_permissions() > 0;
}

function is_admin () : bool {
	$permissions = get_permissions ();
	return $permissions > 1500 && $permissions <= 4500;
}

function is_root () : bool {
	$permissions = get_permissions ();
	return $permissions > 4500;
}