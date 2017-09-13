<?php

class Main {

	private static $packageRoot;
	private static $socketName = "main.socket";

	public static function loadSocket() {
		if(function_exists('Main\main')) {
			return;
		}
		if(file_exists(dirname(__FILE__) . '/socket/'.self::$socketName.'/index.php')) {
			require_once dirname(__FILE__) . '/socket/'.self::$socketName.'/index.php';
			if(! function_exists('Main\main')) {
				System::hardClose("Der Socket konnte nicht geladen werden! Stellen sie sicher, dass ein Socket vorhanden ist!");
			} else {
				Main\main();
			}
		}
		else throw new Exception( "The socket does not exist! This is a HUGHE Error!" );
	}

	public static function asynch() {
		if(function_exists('Main\main')) {
			return;
		}
		if(file_exists(dirname(__FILE__) . '/socket/'.self::$socketName.'/index.php')) {
			require_once dirname(__FILE__) . '/socket/'.self::$socketName.'/index.php';
			if(! function_exists('Main\main')) {
				System::hardClose("Der Socket konnte nicht geladen werden! Stellen sie sicher, dass ein Socket vorhanden ist!");
			} else {
				\Main\load_all_packages();
			}
		}
		else throw new Exception( "The socket does not exist! This is a HUGHE Error!" );
	}

	public static function packagesExists () {
		return self::fileExists ( self::$packageRoot . "packages/index.php" );
	}

	public static function fileExists ( string $filename ) {
		self::createPackageRoot ();
		return file_exists ( self::$packageRoot . $filename );
	}

	private static function createPackageRoot () {
		if ( self::$packageRoot == null ) {
			self::$packageRoot = dirname ( __FILE__ ) . "/";
		}
	}

	public static function getSettings () {
		self::createPackageRoot ();

		$array = parse_ini_file(self::$packageRoot . "settings.ini", false, INI_SCANNER_TYPED);

		return $array;
	}

	public static function writePhpIni ( $array, $file ) {
		$res = [];
		foreach ( $array as $key => $val ) {
			if ( is_array ( $val ) ) {
				$res[] = "[$key]";
				foreach ( $val as $skey => $sval ) $res[] = "$skey = " . ( is_numeric ( $sval ) ? $sval : '"' . $sval . '"' );
			} else $res[] = "$key = " . ( is_numeric ( $val ) ? $val : '"' . $val . '"' );
		}
		self::safeFileRewrite ( $file, implode ( "\r\n", $res ) );
	}

	private static function safeFileRewrite ( $fileName, $dataToSave ) {
		if ( $fp = fopen ( $fileName, 'w' ) ) {
			$startTime = microtime ( TRUE );
			do {
				$canWrite = flock ( $fp, LOCK_EX );
				// If lock not obtained sleep for 0 - 100 milliseconds, to avoid collision and CPU load
				if ( !$canWrite ) usleep ( round ( rand ( 0, 100 ) * 1000 ) );
			} while ( ( !$canWrite ) and ( ( microtime ( TRUE ) - $startTime ) < 5 ) );

			//file was locked so now we can store information
			if ( $canWrite ) {
				fwrite ( $fp, $dataToSave );
				flock ( $fp, LOCK_UN );
			}
			fclose ( $fp );
		}

	}

	public static function setSocketName(string $newSocketName) {
		self::$socketName = $newSocketName;
	}
}