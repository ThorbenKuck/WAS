<?php namespace Vpm;?>
<?php

date_default_timezone_set ( 'Europe/Berlin' );

function feiertage_to_array($jahr) {
	if ( !empty ( $_SESSION['VPM_FREE_DAYS'] ) ) {
		require ( $_SESSION['VPM_FREE_DAYS'] . "days.php");
		if (isset ( $feiertage ))
			return $feiertage;
		else
			return false;
	} else {
		\Main\debug_input("Die Datei \"days.php\" wurde nicht gefunden".""."Die Mod-configuration konnte nicht geladen werden!");
		return null;
	}
}

/**
 * gets the path for the core.csv
 *
 * @category functions
 * @namespace Vpm\core
 * @param $path string        	
 * @return string The path to core
 */
function find_core_path($path = null) {
	// algorithmus hat bereits den Pfad gefunden
	if(isset($_SESSION['core_path'])) {
		return $_SESSION['core_path'];
	}
	
	//Ein Pfad wurde angegeben
	if($path !== null) {
		if(basename ($path) == "core.csv") {
			if(file_exists($path)) {
				$_SESSION['core_path'] = $path;
				return $path;
			}
		}
	}
	
	if ( !empty($_SESSION['PATH_TO_VPM_CORE']) ) {
		$path = $_SESSION['PATH_TO_VPM_CORE'];
	} else {
		if(defined(ABSPATH)) {
			// This is a hard-coded fallback!
			// If, at any time, this is used, you get notified in the debug-frame
			$path = MODS . "vpm.mod/core/core.csv";
			\Main\debug_input("(Notice): the function \"find_core_path\" used it's fallback! This means, the ");
		} else {
			\Main\debug_input("Die Datei \"core.csv\" wurde nicht gefunden".""."Die Mod-configuration konnte nicht geladen werden!");
			return null;
		}
	}
	
	if (file_exists ( $path )) {
		$_SESSION ['core_path'] = $path;
		return $path;
	}
	return false;
}

/**
 * gets the core.csv as an array
 *
 * @category functions
 * @return array The internals of the core.csv
 */
function core_array($path = null) {
	if(isset($_SESSION['core_path'])) {
		$core_file = file($_SESSION['core_path']);
	} else if($path !== null) {
		if (file_exists ( $path ) && basename($path) == "core.csv") {
			$core_file = file ( $path );
		} else {
			\Main\debug_input("(Warning): You have given the function \"core_array\" the path = \".$path.\"."."<br>"." This is not a valid path for the \"core.csv\"-file!");
		}
	} else {
		if(file_exists (find_core_path())) {
			$core_file = file ( find_core_path() );
		} else {
			return false;
		}
	}	
	$core = array ();
	for($i = 0; $i < count ( $core_file ); $i ++) {
		$core [$i] = explode ( "|", $core_file [$i] );
	}
	return $core;
}

/**
 * Insert a date into the existing core.csv
 *
 * <p> Note that the array should not be custom-formated. This means:<br>
 * <code>
 * $date[0] = "name_of_the_date";
 * $date[1] = "starting_day";
 * $dates[2] = "starting_month";
 * $dates[3] = "starting_year";
 * $dates[4] = "starting_hour";
 * $dates[5] = "starting_minute";
 * $date[6] = "ending_day";
 * $dates[7] = "ending_month";
 * $dates[8] = "ending_year";
 * $dates[9] = "enging_hour";
 * $dates[10] = "ending_minute";
 * $dates[11] = "monitor_on/off";
 * </code>
 *
 * In this case the last entry is an integer, 2 = on ; 1 = off.</p>
 *
 *
 * @category functions
 * @return boolean succsefully_insert_date
 * @param array $date
 *        	Das einzutragende Datum im oben genannten Format
 */
function insert_date_into_core(array $date, $path = null) {
	$core_path;
	
	if ($path === null) {
		if (isset ( $_SESSION ['core_path'] )) {
			$core_path = realpath ( $_SESSION ['core_path'] );
		} else {
			$core_path = realpath( find_core_path () );
			if(!file_exists($core_path));
				return false;
		}
	} else {
		$core_path = realpath ( $path );
	}
	
	if (! file_exists ( $core_path ))
		return false;
	
	$erg = array ();
	if (is_array ( $date )) {
		for($i = 0; $i < count ( $date ); $i ++) {
			if (! empty ( $date [$i] )) {
				$erg [$i] = $date [$i];
			} else {
				return false;
			}
		}
		$entry = $erg [0] . "|" . $erg [1] . "." . $erg [2] . "." . $erg [3] . "|" . $erg [4] . ":" . $erg [5] . "|" . $erg [6] . "." . $erg [7] . "." . $erg [8] . "|" . $erg [9] . ":" . $erg [10] . "|" . $erg [11];
		if ($handle = fopen ( $core_path, "a" )) {
			fwrite ( $handle, $entry . "\n" );
			fclose ( $handle );
		} else {
			echo "Es gab ein Problem beim Eintragen des Termins" . "<br>";
			return false;
		}
	}
	return true;
}

/**
 *
 * @param array $dates
 *        	Die zu entfernenden Daten (Wichtig! Es muss ein 2-Dimensionales Array sein, bei dem es einen Schlüssel für die Indizes gibt!)
 * @param string $key
 *        	Der Schlüssel für die Indizies (Standardmäßig ["index"]
 */
function delete_date_from_core(array $dates, $key = "index", $output = false) {
	// return true;
	if (empty ( $dates ))
		return null;
	
	$core = core_array ( find_core_path () );
	$to_give = array ();
	$counter = 0;
	$length = count ( $core );
	
	// Lösche die Indize
	for($i = 0; $i < $length; $i ++) {

			if($output) echo "lösche Indize: " . $dates [$i] [$key];
			unset ( $core [$dates [$i] [$key]] );
			if($output) echo "</br>";
		
	}
	
	for($i = 0; $i < $length; $i ++) {
		if (! empty ( $core [$i] )) {
			if($output)
				echo $i . "<br>";
			$to_give [$counter] = $core [$i];
			$counter ++;
		}
	}
	
	if($output) {
		echo "<br><br><hr><br><br>";
		var_dump ( $to_give );
		echo "<br><br><hr><br><br>";
	}
	
	return rewrite_core ( $to_give );
}

/**
 */
function delete_whole_core() {
	$path_to_core = find_core_path ();
	$fp = fopen ( $path_to_core, "w" );
	return fclose ( $fp );
}

/**
 */
function rewrite_core(array $dates, $output = false) {
	if (! delete_whole_core ())
		return false;
	
	$path_to_core = find_core_path ();
	$pass = true;
	// echo "<br><br><hr><br><br>";
	if($output) 
		var_dump ( $dates );
	// echo "<br><br><hr><br><br>";
	// echo "Anzahl der übergebenen Daten: " . count ( $dates );
	// echo "<br>";
	
	for($i = 0; $i <= count ( $dates ); $i ++) {
		if (! empty ( $dates [$i] [0] )) {
			if ($handle = fopen ( $path_to_core, "a" )) {
				fwrite ( $handle, $dates [$i] [0] . "|" . $dates [$i] [1] . "|" . $dates [$i] [2] . "|" . $dates [$i] [3] . "|" . $dates [$i] [4] . "|" . $dates [$i] [5] );
				fclose ( $handle );
			} else {
				$pass = false;
				$i = count ( $dates );
				// echo "3";
			}
		}
	}
	return $pass;
}

/**
 */
function sort_core() {
	$datumJetztCheck = date ( 'YmdHi' ); // Wir holen uns den Time-Stamp des Momentes wo das Script aufgerufen wird
	$jahr = date ( "Y" ); // dieses Jahr
	$datum_now = date ( "Y-m-d" ); // Time-Stamp für das jetzige Datum
	
	$path_to_core = find_core_path ();
	/*
	 * Zuerst werden alte Termine aussortiert
	 * Dazu werden zu beginn die Daten ausgelesen und getrennt
	 */
	
	// Zerlege die Core in eine 2-D-Array
	if (file_exists ( $path_to_core ))
		$file = file ( $path_to_core ); // Die core.csv
	else
		return null;
	
	for($i = 0; $i < count ( $file ); $i ++) {
		$care [$i] = explode ( "|", $file [$i] );
	}
	
	// Erstelle *Check-Einträge (Timestamps)
	for($i = 0; $i < count ( $file ); $i ++) {
		$care [$i] ["datumCheck"] = $care [$i] [1] [6] . $care [$i] [1] [7] . $care [$i] [1] [8] . $care [$i] [1] [9] . $care [$i] [1] [3] . $care [$i] [1] [4] . $care [$i] [1] [0] . $care [$i] [1] [1] . $care [$i] [2] [0] . $care [$i] [2] [1] . $care [$i] [2] [3] . $care [$i] [2] [4];
		$care [$i] ["enddatumCheck"] = $care [$i] [3] [6] . $care [$i] [3] [7] . $care [$i] [3] [8] . $care [$i] [3] [9] . $care [$i] [3] [3] . $care [$i] [3] [4] . $care [$i] [3] [0] . $care [$i] [3] [1] . $care [$i] [4] [0] . $care [$i] [4] [1] . $care [$i] [4] [3] . $care [$i] [4] [4];
	}
	
	// prüfe, ob das eingetragene Datum in der Vergangenheit liegt
	for($i = 0; $i < count ( $file ); $i ++) {
		if ($care [$i] ["enddatumCheck"] < $datumJetztCheck) { // wenn ja,
			unset ( $care [$i] ); // lösche es
		}
	}
	
	/*
	 * Dann werden die restlichen Daten sortiert
	 *
	 */
	$cache = $care;
	// zur Differentzierung und späteren Fehleranlyse
	// trennen wir cache und care, auch wenn nur cache später eingetragen wird
	// 10. Stelle zur Sortierung der Arrays im Späteren Verlauf
	for($i = 0; $i < count ( $file ); $i ++) {
		$cache [$i] [10] = $cache [$i] [1] [6] . $cache [$i] [1] [7] . $cache [$i] [1] [8] . $cache [$i] [1] [9] . $cache [$i] [1] [3] . $cache [$i] [1] [4] . $cache [$i] [1] [0] . $cache [$i] [1] [1];
	}
	if (empty ( $cache ) || empty ( $care ) || ! is_array ( $care ))
		return null;
		// Deklaration der Namen
	for($i = 0; $i < count ( $file ); $i ++) {
		$cache [$i] ["name"] = $cache [$i] [0];
		$cache [$i] ["datum"] = $cache [$i] [1];
		$cache [$i] ["zeit"] = $cache [$i] [2];
		$cache [$i] ["edatum"] = $cache [$i] [3];
		$cache [$i] ["ezeit"] = $cache [$i] [4];
		$cache [$i] ["zustand"] = $cache [$i] [5];
		$cache [$i] ["sortCache"] = $cache [$i] [10];
	}
	
	foreach ( $cache as $nr => $inhalt ) {
		$name [$nr] = strtolower ( $inhalt ['name'] );
		$datum [$nr] = strtolower ( $inhalt ['datum'] );
		$zeit [$nr] = strtolower ( $inhalt ['zeit'] );
		$edatum [$nr] = strtolower ( $inhalt ['edatum'] );
		$ezeit [$nr] = strtolower ( $inhalt ['ezeit'] );
		$zustand [$nr] = strtolower ( $inhalt ['zustand'] );
		$sortCache [$nr] = strtolower ( $inhalt ['sortCache'] );
	}
	
	array_multisort ( $sortCache, SORT_ASC, $cache );
	
	unlink ( $path_to_core );
	
	for($i = 0; $i < count ( $cache ); $i ++) {
		if (! empty ( $cache [$i] [0] )) {
			$handle = fopen ( $path_to_core, "a" );
			fwrite ( $handle, $cache [$i] [0] . "|" . $cache [$i] [1] . "|" . $cache [$i] [2] . "|" . $cache [$i] [3] . "|" . $cache [$i] [4] . "|" . $cache [$i] [5] );
			fclose ( $handle );
		}
	}
}
function current_state($output = true) {
	// echo "Ausgabealgorithmus startet!"; echo "<br>";
	$eingestellte = get_insert_dates_state ();
	
	$core = core_array ( find_core_path () );
	
	$wochenplan = get_normal_day_state ();
	
	if (is_int ( $eingestellte )) {
		// Es gab einen eingestellten Termin
		if ($core [$eingestellte] [5] == 2) {
			// Dieser sagt, dass die Monitore an sind
			if ($output) {
				echo "<font size='5'><b>" . "Die Monitore sind eingeschaltet." . "</b><br> Der aufgeführte Grund dafür ist: \"" . $core [$eingestellte] [0] . "\"" . "</font>";
				?>
				<br>
				<br>
				<br>
				Der gesamte Termin sieht wie folgt aus:
				<table border="2">
					<tr>
						<th>Name der Veranstalltung</th>
						<th><b><?php echo $core[$eingestellte][0];?> </b></th>
					</tr>
					<tr>
						<th>Start Datum der Veranstaltung</th>
						<th><b><?php echo $core[$eingestellte][1];?> </b></th>
					</tr>
					<tr>
						<th>Start Zeitpunkt der Veranstaltung</th>
						<th><b><?php echo $core[$eingestellte][2];?> </b></th>
					</tr>
					<tr>
						<th>End Datum der Veranstaltung</th>
						<th><b><?php echo $core[$eingestellte][3];?> </b></th>
					</tr>
					<tr>
						<th>End Zeitpunkt der Veranstaltung</th>
						<th><b><?php echo $core[$eingestellte][4]?> </b></th>
					</tr>
				</table>
				<?php
			}
			return true;
		} else {
			
			// Dieser sagt, dass die Monitore aus sind
			if ($output) {
				echo "<font size='5'><b>" . "Die Monitore sind ausgeschaltet" . "</b><br> Der aufgeführte Grund dafür ist: \"" . $core [$eingestellte] [0] . "\"" . "</font>";
				?>
				<br>
				<br>
				<br>
				Der gesamte Termin sieht wie folgt aus:
				<table border="2">
					<tr>
						<th>Name der Veranstalltung</th>
						<th><b><?php echo $core[$eingestellte][0];?> </b></th>
					</tr>
					<tr>
						<th>Start Datum der Veranstaltung</th>
						<th><b><?php echo $core[$eingestellte][1];?> </b></th>
					</tr>
					<tr>
						<th>Start Zeitpunkt der Veranstaltung</th>
						<th><b><?php echo $core[$eingestellte][2];?> </b></th>
					</tr>
					<tr>
						<th>End Datum der Veranstaltung</th>
						<th><b><?php echo $core[$eingestellte][3];?> </b></th>
					</tr>
					<tr>
						<th>End Zeitpunkt der Veranstaltung</th>
						<th><b><?php echo $core[$eingestellte][4]?> </b></th>
					</tr>
				</table>
				<?php
			}
			return false;
		}
	} else if ($wochenplan) {
		
		// Es gibt keinen eingestellten Termin, aber die Moniore sind trotzdem angeschaltet
		if ($output)
			echo "<font size='5'><b>" . "Die Monitore sind regulär eingeschaltet." . "</b></font>";
		return true;
	} else {
		
		// Es gibt keinen eingestellten Termin und die Monitore sind aus
		if ($output)
			echo "<font size='5'><b>" . "Die Monitore sind regulär ausgeschaltet." . "</b></font>";
		return false;
	}
	// echo "Die Funktion wei�t keine Abbruchbedingung auf!"; echo"<br>";
	// echo "Kritischer Fehler!"; echo"<br>";
	return null;
}
function get_normal_day_state() {
	// Die Aktuelle Zeit, zur Abfrage
	$uhrJetzt = date ( "H" ) . date ( "i" );
	if ($uhrJetzt [0] == 0) {
		$uhrJetzt = $uhrJetzt [1] . $uhrJetzt [2] . $uhrJetzt [3];
	}
	// Typecast auf Integer-Variable, da voher ein Typecast auf den Typ String statt fand
	$uhrJetzt = intval ( $uhrJetzt );
	if (! defined ( 'STARTUHR' ) || ! defined ( 'ENDUHR' )) {
		die ( "Es wurde ein Erheblicher Fehler in der Berechnungsroutine fest gestellt!" );
	}
	$t = date ( "w" ); // Hole den aktuellen Tag
	if ($t >= "1" && $t <= "5") {
		// echo "Tag-Match bei dem Wochen-Algorithmus an Tag: ".$t; echo "</br>";
		if ($uhrJetzt >= STARTUHR && $uhrJetzt <= ENDUHR) {
			// echo "Zeit-Match bei dem Wochen-Algorithmus"; echo "</br>";
			return true;
		} else {
			// echo "Kein Zeit-Match bei dem Wochen-Algorithmus"; echo "</br>";
			return false;
		}
	} else {
		// echo "Kein Tag-Match bei dem Wochen-Algorithmus" echo "</br>";
		return false;
	}
}


function get_vpm_config() {
	\Main\read_config();
	return require MODS . "vpm.mod/config.php";
}
// Berechnung der eingetragenen Daten (plan-Algorithmus)
// Für den heutigen Tag
function get_insert_dates_state($custom_core = null) {
	if ($custom_core === null)
		$core = core_array ( find_core_path () );
	else
		$core = $custom_core;
	
	$datum_jetzt_check = date ( "Y" ) . date ( "m" ) . date ( "d" ) . date ( "H" ) . date ( "i" );
	// echo "plan-Algorithmus startet!"; echo "</br>";
	// echo "Inhalt des Übergebenen core arrays"; var_dump($core); echo "</br>";
	for($i = 0; $i < count ( $core ); $i ++) {
		$core [$i] ["datumCheck"] = $core [$i] [1] [6] . $core [$i] [1] [7] . $core [$i] [1] [8] . $core [$i] [1] [9] . $core [$i] [1] [3] . $core [$i] [1] [4] . $core [$i] [1] [0] . $core [$i] [1] [1] . $core [$i] [2] [0] . $core [$i] [2] [1] . $core [$i] [2] [3] . $core [$i] [2] [4];
		$core [$i] ["enddatumCheck"] = $core [$i] [3] [6] . $core [$i] [3] [7] . $core [$i] [3] [8] . $core [$i] [3] [9] . $core [$i] [3] [3] . $core [$i] [3] [4] . $core [$i] [3] [0] . $core [$i] [3] [1] . $core [$i] [4] [0] . $core [$i] [4] [1] . $core [$i] [4] [3] . $core [$i] [4] [4];
		// echo "Startdatum: "; echo $core[$i]["datumCheck"]."</br>";
		// echo "Enddatum: "; echo $core[$i]["enddatumCheck"]."</br></br>";
		if (($core [$i] ["datumCheck"] <= $datum_jetzt_check && $core [$i] ["enddatumCheck"] >= $datum_jetzt_check)) {
			// log("Es gab eine �bereinstimmung bei dem Letzten Genannten Termin!");
			// echo "Erfolg des plan-Algorithmus! Inhaltes des Cores an der Stelle "; echo $i; echo " :"; var_dump($core[$i]); echo "</br>";
			return $i;
		} else if ($core [$i] ["datumCheck"] > $datum_jetzt_check) {
			return false;
		}
	}
	// echo "Kein Erfolg des plan-Algorithmus"; echo "</br>";
	return false;
}
function core_locked() {
	// try to find a MLockMonitor in db "Manual"
	return false;
}
function make_download($path_to_core, $type = "", $name = "core.csv") {
	if( !empty ($path_to_core) && file_exists ($path_to_core) && basename($path_to_core, ".csv") === "core" ) {
		if(!is_readable($path_to_core)) die('Auf die Datei konnte nicht zugegriffen werden!');
		$size = filesize($path_to_core);
		$name = rawurldecode($name);
		$known_mime_types=array(
				"csv" => "text/csv"
		);

		if($type==''){
			$file_extension = strtolower(substr(strrchr($path_to_core,"."),1));
			if(array_key_exists($file_extension, $known_mime_types)){
				$type=$known_mime_types[$file_extension];
			} else {
				$type="application/force-download";
			};
		};
		@ob_end_clean();
		if(ini_get('zlib.output_compression'))
			ini_set('zlib.output_compression', 'Off');
			header('Content-Type: ' . $type);
			header('Content-Disposition: attachment; filename="'.$name.'"');
			header("Content-Transfer-Encoding: binary");
			header('Accept-Ranges: bytes');

			if(isset($_SERVER['HTTP_RANGE']))
			{
				list($a, $range) = explode("=",$_SERVER['HTTP_RANGE'],2);
				list($range) = explode(",",$range,2);
				list($range, $range_end) = explode("-", $range);
				$range=intval($range);
				if(!$range_end) {
					$range_end=$size-1;
				} else {
					$range_end=intval($range_end);
				}

				$new_length = $range_end-$range+1;
				header("HTTP/1.1 206 Partial Content");
				header("Content-Length: $new_length");
				header("Content-Range: bytes $range-$range_end/$size");
			} else {
				$new_length=$size;
				header("Content-Length: ".$size);
			}

			$chunksize = 1*(1024*1024);
			$bytes_send = 0;
			if ($file = fopen($path_to_core, 'r'))
			{
				if(isset($_SERVER['HTTP_RANGE']))
					fseek($path_to_core, $range);

					while(!feof($file) &&
							(!connection_aborted()) &&
							($bytes_send<$new_length)
							)
					{
						$buffer = fread($file, $chunksize);
						echo($buffer);
						flush();
						$bytes_send += strlen($buffer);
					}
					fclose($file);
			} else
				die('Error - can not open file.');

				/*
				 ob_clean();
				 header('Content-type: text/csv');
				 header('Content-Disposition: attachment; filename="monitorsteuerung.csv"');

				 // ob_clean();
				 flush();

				 $file = file($website->returnCore());

				 $core = array();
				 for($i = 0 ; $i < count($file) ; $i++) {
				 $download = $file[$i];
				 $download .= "\r\n";
				 echo $download;
				 }
				 */
				return true;


	} else {
		return false;
	}

}

?>