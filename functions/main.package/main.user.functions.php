<?php namespace Main; ?>
<?php 
date_default_timezone_set ( 'Europe/Berlin' );

/**
 * Gets the username of the logdin user from the database "Logins"
 *
 * @return NULL|string Returns the Username, if found, and NULL if any error accoured
 */
function get_username() {
	if (! is_logdin ())
		return null;
		if (empty ( $_SESSION ["vpm_login_session"] ))
			return null;
			$sql = "SELECT `UserName` FROM `Logins`
			WHERE `Hash`='" . $_SESSION ["vpm_login_session"] . "'";
			$link = mysqli_connect ( MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE );
			if (! check_connection_to_database ( $link ))
				die ( "Es gab einen Fehler mit der Datenbank!" );
				$result = execute_sql_statement ( $sql, $link );
				return $result ["UserName"];
}

/**
 * verifys wheter or not a login-attempt is correct or not.
 *
 * @param string $username
 *        	The username of the user, trying to log in
 * @param unknown $password
 *        	The password of the user, trying to log in
 * @return boolean Returns true if successful and else false
 */
function verify_login($username, $password) {
	if (login_locked ()) {
		unset ( $_SESSION ["vpm_login_session"] );
		$_SESSION ["delete_keep_logd_in"] = 1;
		$sql = "DELETE FROM `Logins`";
		$link = mysqli_connect ( MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE );
		if (! check_connection_to_database ( $link ))
			die ( "Es gab einen Fehler mit der Datenbank!" );

			execute_sql_statement ( $sql, $link );
			return_new_error ( "Die Anmeldung wurde ausgesetzt!" . "<br>" . "Bei Bedarf kontaktieren sie bitte einen Administrator!" . "<br>" . "Sollte dieses Problem weiterhing bestehen bleiben, kontaktieren sie bitte den Root-User" );
			die ( "Die Anmeldung wurde vorübergehend ausgesetzt!" . "<br>" . "Bei Bedarf kontaktieren sie bitte einen Administrator" );
	}
	// connect_to_database()
	// verify hash from db at username with given password
	$link = mysqli_connect ( MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE );
	if (! check_connection_to_database ( $link ))
		die ( "Es gab einen Fehler mit der Datenbank!" );

		$erg;
		$sql = "SELECT PassHash
			FROM  `Users`
			WHERE UserName='" . mysqli_real_escape_string ( $link, $username ) . "'";
		$erg = execute_sql_statement ( $sql, $link );
		if (! $erg) {
			return false;
		}
		if (password_verify ( $password, $erg ["PassHash"] )) {
			return true;
		} else
			return false;
}

/**
 * Creates a random string of the minimum length $length and the maximum length HASH_MAX_LENGTH or 50
 *
 * @param int $length
 *        	The startlength of a hash.
 * @return string|boolean Returns the generated String or false if anything has gone wrong.
 */
function create_random_hash($length = 25) {
	if (! defined ( HASH_MAX_LENGTH )) {
		$max_length = 50;
	} else {
		$max_length = HASH_MAX_LENGTH;
	}
	// Der String für alle möglichen Zeichen in dem random_hash
	$possibilitys = "0123456789%abcdefghijklmnopqrstuvwxyz%ABCDEFGHIJKLMNOPQRSTUVWXYZ";

	// länge des Strings der alle möglichkeiten beinhaltet
	$string_length = strlen ( $possibilitys ) - 1;

	// Die Variable, die den entgültigen random-cache enthalten wird
	$erg = "";
	for($i = 0; $i < $length; $i ++) {
		$erg .= $possibilitys [rand ( 0, $string_length )];
	}

	if ($length >= $max_length) {
		// Die Maximale Länge wurde erreicht
		return str_replace ( "%", "", $erg );
	} else if (strpos ( $erg, "%" ) !== false) {
		// Der String enthält ein % zeichen
		return create_random_hash ( ++ $length );
	} else {
		// es wurde ein String gefunden, der kein % enthält
		return $erg;
	}
	return false;
}

/**
 * Trys to log a user in.
 * Automaticly checks, if the login is veryfied or not.
 *
 * @todo Check for SQLInjection!
 *
 * @param string $username
 *        	The username of the user, who is trying to log in
 * @param string $password
 *        	The password of the user, who is trying to log in
 * @param boolean $keep_logdin
 *        	Boolean, if the user wants to keepd logdin
 * @return NULL|boolean Returns null, if anything goes wrong and a boolean, wether or not the login was successful
 */
function login_user($username, $password, $keep_logdin = false) {
	if (verify_login ( $username, $password )) {
		$ip = $_SERVER ["REMOTE_ADDR"];

		$r_hash = create_random_hash ();
		// counter, der Prüft, dass die While-Schleife nicht endlos läuft
		$counter = 0;

		// Zu erst den alten Eintrag, sofern vohanden, löschen!
		$sql_find_old_entry = "SELECT `Hash` FROM `Logins` WHERE `UserName`='" . $username . "'";
		$link = mysqli_connect ( MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE );
		if (! check_connection_to_database ( $link ))
			die ( "Es gab einen Fehler mit der Datenbank!" );
			$found_old_entry = execute_sql_statement ( $sql_find_old_entry, $link );
			// Gab es einen
			if (is_string ( $found_old_entry ["Hash"] )) {
				log ( "Zwangsabmeldung fuer \"" . $username . "\"! Abmeldung wird empfohlen!" );
				$sql_delete_old = "DELETE FROM `Logins` WHERE `UserName`='" . $username . "'";
				$link = mysqli_connect ( MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE );
				if (! check_connection_to_database ( $link ))
					die ( "Es gab einen Fehler mit der Datenbank!" );
					// lösche ihn
					$erg = execute_sql_statement ( $sql_delete_old, $link );
			}

			$link = mysqli_connect ( MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE );
			// dann alle bereits belegten hashes eliminieren.
			$sql_get_give_hashes = "SELECT `Hash` FROM `Logins` WHERE `Hash`='" . $r_hash . "'";
			while ( execute_sql_statement ( $sql_get_give_hashes, $link ) !== false ) {
				$r_hash = create_random_hash ();
				$counter ++;
				if ($counter === 1000)
					die ( "Leider sind zu viele Hashes aktuell vergeben.. Probieren sie es erneut!" );
			}

			// Nun den neuen Hash in die Datenbank "Logins" eintragen
			$sql = "INSERT INTO `Logins` (Hash, UserName)
				VALUES ('" . $r_hash . "', '" . $username . "')";
			$link = mysqli_connect ( MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE );
			if (! check_connection_to_database ( $link ))
				die ( "Es gab einen Fehler mit der Datenbank!" );
				$erg = execute_sql_statement ( $sql, $link );

				if ($erg === false) {
					// Trit ein Fehler beim Eintragen des Nutzers in die Datenbank "logins" auf, so returne null
					log ( "Ein Fehler trat auf, als der Nutzer: \"" . $username . "\" versucht wurde an zu melden" );
					return null;
				} else {
					// Verläuft alles reibungslos, so schreibe die Session und gegebenen Falls den Cookie
					$_SESSION ['vpm_login_session'] = $r_hash;
					if ($keep_logdin) {
						// Setze einen Cookie für 100 Tage
						$_SESSION ["keep_me_logd_in"] = $r_hash;
					}
						
					log ( "Erfolgreiche angemeldung von Nutzer: \"" . $username . "\". IP: " . $ip );
					log ( "Logins-Hash fuer Nutzer \"" . $username . "\": " . $r_hash );
					return is_logdin ();
				}
	} else {
		log ( "FEHLGESCHLAGENE angemeldung von Nutzer: \"" . $username . "\". IP: " . $ip );
		return false;
	}
}

/**
 * Logs the current user out
 *
 * @return boolean Returns the success of the logout as a boolean
 */
function logout_user() {
	$hash = $_SESSION ['vpm_login_session'];

	$sql2 = "SELECT `UserName` FROM `Logins` WHERE `Hash`='" . $hash . "'";
	$link = mysqli_connect ( MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE );
	if (! check_connection_to_database ( $link ))
		die ( "Es gab einen Fehler mit der Datenbank!" );
		$username = execute_sql_statement ( $sql2, $link );

		$link = mysqli_connect ( MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE );
		if (! check_connection_to_database ( $link ))
			die ( "Es gab einen Fehler mit der Datenbank!" );

			$sql = "DELETE FROM `Logins` WHERE `Hash`='" . $hash . "'";

			if (execute_sql_statement ( $sql, $link )) {
				unset ( $_SESSION ["vpm_login_session"] );
				$_SESSION ["delete_keep_logd_in"] = 1;
				log ( "Der Nutzer " . $username ["UserName"] . " hat sich erfolgreich vom System abgemeldet" );
				return true;
			}

			return false;
}

/**
 * A function, wich checks whether or not the current user is logdin
 *
 * @return boolean True if logdin, false if logdout
 */
function is_logdin() {
	if (login_locked ()) {
		unset ( $_SESSION ["vpm_login_session"] );
		$_SESSION ["delete_keep_logd_in"] = 1;
		$sql = "DELETE FROM `Logins` WHERE `Hash` NOT NULL";
		$link = mysqli_connect ( MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE );
		if (! check_connection_to_database ( $link ))
			die ( "Es gab einen Fehler mit der Datenbank!" );

			execute_sql_statement ( $sql, $link );
			if ($_SESSION ["login_lock_warning_set"] !== true) {
				return_new_warning ( "Die Anmeldung ist vorrüber gehend ausgesetzt" . "<br>" . "Bei Bedarf, kontaktieren sie bitte einen Administrator" );
				$_SESSION ["login_lock_warning_set"] = true;
			}
			return false;
	}

	if(isset($_SESSION ['vpm_login_session'])) {
		$current_session_hash = $_SESSION ['vpm_login_session'];
	} else {
		return false;
	}


	if (! empty ( $current_session_hash )) {
		$sql = "SELECT `Hash` FROM `Logins` WHERE `Hash` = '" . $current_session_hash . "'";
		$link = mysqli_connect ( MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE );
		if (! check_connection_to_database ( $link ))
			die ( "Es gab einen Fehler mit der Datenbank!" );

			$erg = execute_sql_statement ( $sql, $link );
			if (! $erg) {
				unset ( $_SESSION ["vpm_login_session"] );
				return false;
			}
			if ($current_session_hash === $erg ["Hash"]) {
				return true;
			} else {
				debug_input ( array (
						"password_hash existiert",
						function_exists ( "password_hash" )
				) );
				return false;
			}
	} else {
		return false;
	}
}

/**
 * Easy function to check if the current user ist administrator
 *
 * @return boolean Wheter or not the current user is Administrator
 */
function is_admin() {
	$permission = get_user_permissions ();

	if ($permission === false)
		return false;

		return ($permission > 1500 && $permission <= 4500);
}

/**
 * Easy function to check if the current user ist root
 *
 * @return boolean Wheter or not the current user is root
 */
function is_root() {
	$permission = get_user_permissions ();

	if ($permission === false)
		return false;

		return ($permission == 9999);
}

/**
 * A function to get the permissions of the current logd in user as an integer
 *
 * @return boolean|integer Returns the permissions as an integer or else false
 */
function get_user_permissions() {
	if (empty ( $_SESSION ["vpm_login_session"] ))
		return false;
		$sql = "
				SELECT `Permissions` FROM `Users`
				WHERE `UserName`=(
					SELECT `Username` FROM `Logins`
					WHERE `Hash`='" . $_SESSION ["vpm_login_session"] . "')";
		$link = mysqli_connect ( MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE );
		if (! check_connection_to_database ( $link ))
			die ( "Es gab einen Fehler mit der Datenbank!" );
			$result = execute_sql_statement ( $sql, $link );
			if (is_bool ( $result ))
				return false;
				else
					return $result ["Permissions"];
					// get username from logins where hash = $_SESSION['login']
					// return users.rechte
}


?>