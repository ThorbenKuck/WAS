<?php
/**
 * Created by PhpStorm.
 * User: thorben
 * Date: 11.09.17
 * Time: 16:56
 */

namespace Main;


class LowLevelImpl implements LowLevel  {

	private $settings;
	private $login_username = "";
	private $permissions = -1;
	private $identifier = "-none-";
	private $logged_in = false;
	private $locked = false;

	public function __construct (Settings $settings) {
		$this->settings = $settings;
		$this->login_username = (string) isset($settings->info()['login_username']) ? $settings->info()['login_username']
			: (isset($_SESSION["login_session"]["username"]) ? $_SESSION["login_session"]["username"] : "Unknown");
		$this->locked = isset($_SESSION["login_session"]["locked"]) ? $_SESSION["login_session"]["locked"] : false;
		$this->permissions = (int) isset($settings->info()['login_permissions']) ? $settings->info()['login_permissions']
			: (isset($_SESSION["login_session"]["permissions"]) ? $_SESSION["login_session"]["permissions"] : -1);
		$this->identifier = (string) isset($settings->info()['identifier']) ? $settings->info()['identifier']
			: (isset($_SESSION["login_session"]["identifier"]) ? $_SESSION["login_session"]["identifier"] : "-none-");

		// Read out login-data
		$session_login = (isset($this->settings->info()['logdin']) ? $this->settings->info()['logdin'] : false)
			|| isset($_SESSION["login_session"]["logdin"]) ? $_SESSION["login_session"]["logdin"] : false;
		$cookie_login = isset($_COOKIE['login']);
		$this->logged_in = (bool) ($session_login || $cookie_login) && !$this->login_locked ();
	}

	public function login (string $username, int $permissions, string $identifier = "", bool $keep_logedin = false,
					int $keep_logdin_time = (60 * 60 * 24 * 7)) : bool {
		if ($this->login_locked ()) {
			return false;
		}
		$this->settings->set_info ("login_username", $username);
		$_SESSION["login_session"]["username"] = $username;
		$this->login_username = $username;
		$this->settings->set_info ("login_permissions", $permissions);
		$_SESSION["login_session"]["permissions"] = $permissions;
		$this->permissions = $permissions;
		$this->settings->set_info ("identifier", $identifier);
		$_SESSION["login_session"]["identifier"] = $identifier;
		$this->identifier = $identifier;
		if ($keep_logedin) {
			setcookie ('login', $identifier, $keep_logdin_time);
//			var_dump ($_COOKIE['login']);
		}
		$this->settings->set_info ("logdin", true);
		$_SESSION["login_session"]["logdin"] = true;
		$this->logged_in = true;
		return true;
	}

	public function logout () {
		set_info ("login_username", "");
		$this->login_username = "";
		$_SESSION["login_session"]["username"] = "Unknown User";
		set_info ("login_permissions", - 1);
		$this->permissions = -1;
		$_SESSION["login_session"]["permissions"] = -1;
		set_info ("logdin", false);
		$this->logged_in = false;
		$_SESSION["login_session"]["logdin"] = false;
		set_info("identifier", "-none-");
		$this->identifier = "-none-";
		$_SESSION["login_session"]["identifier"] = "-none-";
	}

	public function disable_login () {
		$this->locked = true;
		$this->settings->set_info ('login_locked', true);
		$_SESSION["login_session"]["locked"] = true;
	}

	public function enable_login () {
		$this->locked = false;
		$this->settings->set_info ('login_locked', false);
		$_SESSION["login_session"]["locked"] = false;
	}

	public function is_logdin () : bool {
		return $this->logged_in && !$this->login_locked();
	}

	public function login_locked () : bool {
		return $this->locked === true;
	}

	public function get_username () : string {
		return $this->login_username;
	}

	public function get_permissions () : int {
		return $this->permissions;
	}

	public function get_identifier () : string {
		return $this->identifier;
	}

	public function is_normal_user () : bool {
		return (!$this->is_admin () && !$this->is_root ()) && $this->get_permissions() > 0;
	}

	public function is_admin () : bool {
		$permissions = $this->get_permissions ();
		return $permissions > 1500 && $permissions <= 4500;
	}

	public function is_root () : bool {
		$permissions = $this->get_permissions ();
		return $permissions > 4500;
	}
}