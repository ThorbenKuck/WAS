<?php
use Main\Socket;

/**
 * Created by PhpStorm.
 * User: thorben
 * Date: 11.09.17
 * Time: 17:04
 */
class System {

	private static $socket;
	private static $eoe = [];

	public static function initiate(\Main\Socket $socket) : bool {
		if($socket == null) {
			return false;
		}
		if(self::isInitialized()) {
			throw new Error("System already initialized!");
		}

		self::$socket = $socket;
		return true;
	}

	public static function isInitialized() : bool {
		return self::$socket != null;
	}

	public static function startup() {
		echo "\n" . "starting system ..";
		if(!self::isInitialized()) {
			echo "loading socket ..";
			self::loadSocketFiles();
		}
		echo "calling socket hookup";
		self::hookUpSocket();
		self::registerEndOfExecution();

		$socket = self::getSocket();
//
		if(isset ($socket->settings()->info()['root_path'])) {
			$socket->read_config($socket->settings()->info()['root_path']);
		} else {
			$socket->read_config();
		}
		$socket->read_theme_config();
		$socket->packages()->load_all_packages();
		$socket->mods()->load_mods();
		$socket->load_theme();
	}

	private static function loadSocketFiles(string $socketName = "main.socket") : bool {
		if(function_exists('Main\hook')) {
			return true;
		}
		$rootPath = dirname(__FILE__);
		if(file_exists($rootPath . '/socket/index.php')) {
			require_once $rootPath . '/socket/index.php';
		}
		if(file_exists($rootPath . '/socket/' . $socketName . '/index.php')) {
			require_once $rootPath . '/socket/' . $socketName . '/index.php';
			if(!function_exists('Main\hook')) {
				self::hardClose("Der Socket konnte nicht geladen werden! Stellen sie sicher, dass ein Socket vorhanden ist!");
			}
		}
		return true;
	}

	public static function hardClose(string $message, string $logMessage = null) {
		if(isset($socket)) {
			self::getSocket()->debug()->log("[DIE]: " . $logMessage == null ? $message : $logMessage);
			die($message);
		}
	}

	public static function getSocket() : Socket {
		return self::$socket;
	}

	private static function hookUpSocket() {
		if(function_exists('Main\hook')) {
			Main\hook();
		} else {
			throw new Error("Could not locate the function Main\\hook!");
		}
	}

	private static function registerEndOfExecution() {
		register_shutdown_function(function() {
			foreach( self::$eoe as $callable ) {
				$callable();
			}
			unset($callable);
			self::getSocket()->interact()->open_info_frame();
			self::getSocket()->interact()->open_debug_frame();
		});
	}

	public static function startupAsynchronous() {
		if(!self::isInitialized()) {
			self::loadSocketFiles();
		}
		self::requireInitiated();
		self::$socket->packages->load_all_packages();
	}

	private static function requireInitiated() {
		if(self::$socket == null) {
			throw new Error("System has to be initialized first!");
		}
	}

	public static function getSettings() {
		$root = dirname(__FILE__);

		$array = parse_ini_file($root . "/settings.ini", false, INI_SCANNER_TYPED);

		return $array;
	}

	public static function addEndOfExecutionCallback(callable $function) : bool {
		if(!is_callable($function)) {
			return false;
		}

		if(empty(self::$eoe)) {
			array_push(self::$eoe, function() {
				$test_file_path = self::getSocket()->settings()->info()['test_file'];
				if(self::getSocket()->settings()->access()['use_test_file'] && file_exists($test_file_path)) {
					include $test_file_path;
				}
			});
		}

		array_push(self::$eoe, $function);

		return true;
	}

	public static function println(string $string) {
		self::print($string);
		self::print("<br>");
	}

	public static function print(string $string) {
		echo $string;
	}
}