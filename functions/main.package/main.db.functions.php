<?php namespace Main; ?>
<?php 
date_default_timezone_set ( 'Europe/Berlin' );
/**
 * Function to check if the connection to the database is established
 *
 * @param mysqli_connect_result $link
 *        	The mysqli_connect return value
 *
 * @return boolean Wheter or not the connection ist correktly established
 */
function check_connection_to_database($link) {
	if (mysqli_connect_errno ()) {
		// Fehler
		unset ( $link );
		return false;
	} else {
		if (! isset ( $link ) || $link === null)
			return false;
			if (mysqli_ping ( $link )) {
				// kein Fehler und Verdinung aktiv
				unset ( $link );
				return true;
			}
			// kein Fehler, aber keine aktive Verbindung
			unset ( $link );
			return false;
	}
}

/**
 * Function to close the database connection
 *
 * @param mysqli_connect_result $link
 *        	The mysqli_connect return value
 *
 * @return mysqli_close_result If the connection has been succesfully closed
 */
function close_database_connection($link) {
	$return = mysqli_close ( $link );
	unset ( $link );
	return $return;
}

/**
 * Function for executing any sql-statement.
 *
 * @param string $sql
 *        	The query, that shall be executed
 * @param mysqli_connect_result $link
 *        	The mysqli_connect return value
 * @return array|boolean If the query failed, it returns false. In every other szenario it returns the result of the execution
 */
function execute_sql_statement($sql, $link) {
	if (! check_connection_to_database ( $link ))
		die ( "Es gab einen Fehler mit der Datenbank!" );
		// execute SQL-Query
		$command = mysqli_query ( $link, $sql );

		// wenn der Eintrag nicht gefunden wurde gebe false zurück
		// wenn aber etwas erfolgreich eingefügt wurde o.ä. gebe true zurück
		if (is_bool ( $command )) {
			return $command;
		}

		// Antwort der Datenbank in ein assoziatives Array übergeben
		$result = mysqli_fetch_assoc ( $command );

		if ($result !== null) {
			if (! close_database_connection ( $link )) {
				check_connection_to_database ( $link );
				close_database_connection ( $link );
			}
			unset ( $link );
			return $result;
		} else {
			if (! close_database_connection ( $link )) {
				check_connection_to_database ( $link );
				close_database_connection ( $link );
			}
			unset ( $link );
			return false;
		}
}


?>