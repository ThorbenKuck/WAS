<?php
if (session_status () !== PHP_SESSION_ACTIVE)
	session_start ();

require ($_SESSION ['autoload']);
if ($_REQUEST ["output"]) {
	$input;
	$pass = true;
	$input = $_REQUEST ["output"];
	// Wenn ein neuer Termin erstellt werden soll
	if ($input [0] == "new_date") {
		$work = array ();
		$message_pass;
		$message_not_pass;
		// checke ob alle Felder belegt sind
		for($i = 0; $i < count ( $input ); $i ++) {
			$j = $i + 1;
			if (isset ( $input [$j] ) && ! empty ( $input [$j] )) {
				$work [$i] = $input [$j];
			}
		}
		
		$core = array ();
		$core = Vpm\core_array ( Vpm\find_core_path () );
		
		// Alle Daten werden aus der CSV gelesen
		for($i = 0; $i < count ( $core ); $i ++) {
			$core [$i] ["startB"] = $core [$i] [1] [6] . $core [$i] [1] [7] . $core [$i] [1] [8] . $core [$i] [1] [9] . $core [$i] [1] [3] . $core [$i] [1] [4] . $core [$i] [1] [0] . $core [$i] [1] [1];
			$core [$i] ["endB"] = $core [$i] [3] [6] . $core [$i] [3] [7] . $core [$i] [3] [8] . $core [$i] [3] [9] . $core [$i] [3] [3] . $core [$i] [3] [4] . $core [$i] [3] [0] . $core [$i] [3] [1];
			$core [$i] ["kontrolleUhr"] = $core [$i] [2] [0] . $core [$i] [2] [1] . $core [$i] [2] [3] . $core [$i] [2] [4];
			$core [$i] ["endkontrolleUhr"] = $core [$i] [4] [0] . $core [$i] [4] [1] . $core [$i] [4] [3] . $core [$i] [4] [4];
			$core [$i] ["datumCheck"] = $core [$i] [1] [6] . $core [$i] [1] [7] . $core [$i] [1] [8] . $core [$i] [1] [9] . $core [$i] [1] [3] . $core [$i] [1] [4] . $core [$i] [1] [0] . $core [$i] [1] [1] . $core [$i] [2] [0] . $core [$i] [2] [1] . $core [$i] [2] [3] . $core [$i] [2] [4];
			$core [$i] ["enddatumCheck"] = $core [$i] [3] [6] . $core [$i] [3] [7] . $core [$i] [3] [8] . $core [$i] [3] [9] . $core [$i] [3] [3] . $core [$i] [3] [4] . $core [$i] [3] [0] . $core [$i] [3] [1] . $core [$i] [4] [0] . $core [$i] [4] [1] . $core [$i] [4] [3] . $core [$i] [4] [4];
		}
		
		$datum_check = $work [3] . $work [2] . $work [1] . $work [4] . $work [5];
		$edatum_check = $work [8] . $work [7] . $work [6] . $work [9] . $work [10];
		$today_check = date ( "Y" ) . date ( "m" ) . date ( "d" ) . date ( "H" ) . date ( "i" );
		
		// Die Logic, ob alles stimmt
		// �berpr�fung ob ein anderes Datum bereits in diesem Zeitraum liegt
		// $datum_check = Eingegebens startdatum
		// $edatum_check = Eingegebens enddatum
		// [----] = Bereich des eingegebenen datums
		// $core[$a]["datumCheck"] = gespeichertes startdatum variabel
		// $core[$a]["enddatumCheck"] = gespeichertes enddatum variabel
		// [++++] = Bereich des gespeicherten datums
		$nummer;
		for($a = 0; $a < count ( $core ); $a ++) {
			
			// echo $edatum_check."<br>";
			// echo $datum_check."<br>";
			// echo $core[$a]["datumCheck"]."<br>";
			// echo $core[$a]["enddatumCheck"]."<br>";
			// echo $a."<br>";
			
			if (($edatum_check <= $core [$a] ["datumCheck"]) || ($datum_check >= $core [$a] ["enddatumCheck"])) {
				// [----] ... [++++] || [++++] ... [----]
			} else {
				// Alles andere bzw. Das Datum überschneidet sich mit einem anderen
				$pass = false;
				$nummer = $a;
				$a = count ( $core );
			}
		}
		
		if ($datum_check < $today_check)
			$message_pass = "<p style='color:orange;'><b>" . "(Warning): " . "</b>" . "Der Termin-Anfang liegt in der Vergangenheit!" . "<br>" . "Der Termin wird eingefügt, es könnte allerdings zu Verzögerungen kommen!" . "</p>";
		
		if ($edatum_check < $today_check) {
			$message_pass = "<p style='color:orange;'><b>" . "(Warning): " . "</b>" . "Das Termin-Ende liegt in der Vergangenheit!" . "<br>" . "Das hat zur Folge, dass der eingetragene Termin, keine Auswirkung hat!" . "</p>";
		}
		
		if ($pass) {
			echo "<script>hide_old_input();</script>";
			if (Vpm\insert_date_into_core ( $work, Vpm\find_core_path () )) {
				Vpm\sort_core ();
				if (! empty ( $message_pass ))
					echo $message_pass;
				
				echo "<p style='color:green;'>" . "Der Termin wurde erfolgreich eingetragen!" . "</p><br>" . "Sie können nun weitere Termine eintragen!";
				
				echo "<script>show_old_input(); update_table();</script>";
			} else {
				echo "<p style='color:red;'>" . "Es gab einen Fehler beim Eintragen des Termins!" . "</p><br>" . "Versuchen sie es erneut!" . "<br>" . "Sollte das Problem weiterhin bestehen, wenden sie sich bitte an einen Administrator";
			}
		} else {
			if (! empty ( $message_not_pass )) {
				echo $message_not_pass;
			} else {
				echo "<p><b style='color:red;'>" . "Das eingegebene Datum ist bereits belegt!" . "</b></p><br>";
				echo "Der überschneidende Termin ist der Folgende: ";
				?>
<table border="2">
	<tr>
		<th>Name der Veranstalltung</th>
		<th><b><?php echo $core[$nummer][0];?> </b></th>
	</tr>
	<tr>
		<th>Start Datum der Veranstaltung</th>
		<th><b><?php echo $core[$nummer][1];?> </b></th>
	</tr>
	<tr>
		<th>Start Zeitpunkt der Veranstaltung</th>
		<th><b><?php echo $core[$nummer][2];?> </b></th>
	</tr>
	<tr>
		<th>End Datum der Veranstaltung</th>
		<th><b><?php echo $core[$nummer][3];?> </b></th>
	</tr>
	<tr>
		<th>End Zeitpunkt der Veranstaltung</th>
		<th><b><?php echo $core[$nummer][4]?> </b></th>
	</tr>
</table>
<?php
			}
		}
		
		// nutze diese Function um das Datum in den core zu schreiben. +
	} 

	else if ($input [0] == "upload") {
	} 	

	// ###################################
	// # Errechnete Feiertage #
	// ###################################
	else if ($input [0] == "calc") {
		if (! isset ( $input [1] ) || empty ( $input [1] )) {
			echo "Entschuldigung, es gab ein Problem...";
			echo "<script>update_table();</script>";
			return;
		}
		$jahr_check = $input [1];
		$jahr = intval ( date ( "Y" ) ); // dieses Jahr mit int-cast
		                                 // $jahrCheck = intval(date("Y")) + 1;
		                                 // var_dump($jahr); echo "</br>";
		                                 // var_dump($jahrCheck); echo "</br>";
		                                 // echo $jahr_check;
		$datum_now = date ( "Y-m-d" );
		while ( $jahr <= $jahr_check ) {
			$feiertage = Vpm\feiertage_to_array ( $jahr );
			// Begründungen sepperiert der Übersichtshalber
			// Die Struktru von $feiertage ist statisch
			$cache = Vpm\core_array ( Vpm\find_core_path () );
			
			$fail = false; // beschreibt ob der Termin bereits in der Datei vorliegt
			$stelle = 0; // beschreibt die Stelle an der sich das array $all befindet
			
			for($i = 0; $i < count ( $feiertage ); $i ++) {
				if (! empty ( $feiertage [$i] )) {
					if ($feiertage [$i] ["check"] < $datum_now) {
						continue;
					} elseif ($feiertage [$i] ["check"] >= $datum_now) {
						for($j = 0; $j < count ( $cache ); $j ++) {
							// echo "Neuer Termin: ";var_dump($feiertage[$i]["Begründung"]); echo "</br>";
							// echo "Bereits vorhandener Termin: ";var_dump($cache[$j][0]); echo "</br>";
							// var_dump($feiertage[$i]["Datum"]); echo "</br>";
							// var_dump($cache[$j][1]); echo "</br>";
							if ($feiertage [$i] ["Datum"] == $cache [$j] [1]) {
								$fail = true;
								// echo "übereinstimmung gefunden"."</br>";
								break;
							}
						}
						if (! $fail) {
							// in die CSV-Datei schreiben
							$all [0] = "* " . $feiertage [$i] ["Begründung"] . " *";
							$all [1] = $feiertage [$i] ["Datum"] [0] . $feiertage [$i] ["Datum"] [1];
							$all [2] = $feiertage [$i] ["Datum"] [3] . $feiertage [$i] ["Datum"] [4];
							$all [3] = $feiertage [$i] ["Datum"] [6] . $feiertage [$i] ["Datum"] [7] . $feiertage [$i] ["Datum"] [8] . $feiertage [$i] ["Datum"] [9];
							$all [4] = "00";
							$all [5] = "00";
							$all [6] = $feiertage [$i] ["Datum"] [0] . $feiertage [$i] ["Datum"] [1];
							$all [7] = $feiertage [$i] ["Datum"] [3] . $feiertage [$i] ["Datum"] [4];
							$all [8] = $feiertage [$i] ["Datum"] [6] . $feiertage [$i] ["Datum"] [7] . $feiertage [$i] ["Datum"] [8] . $feiertage [$i] ["Datum"] [9];
							$all [9] = "23";
							$all [10] = "59";
							$all [11] = "1";
							
							if (! Vpm\insert_date_into_core ( $all ))
								echo "<b style='color:red;'>(error)" . "</b>" . ": failed to insert \"" . $feiertage [$i] ["Begründung"] . "\" year: " . $jahr . "<br><br>";
						}
					}
				}
				$fail = false;
			}
			
			// Vorbereitung für nächsten Durchlauf!
			unset ( $all );
			unset ( $cache );
			$jahr ++;
			unset ( $ferientage );
		}
		echo "Die Daten wurden Eingefügt!";
		echo "<script>update_table();</script>";
	} 	

	// ###########################
	// # Ein(ige) Date(n) löschen#
	// ###########################
	// Wenn Daten gelöscht werden sollen
	else if ($input [0] == "delete_one") {
		// Hier sind die Felder auf jeden Fall
		$date = array ();
		for($i = 0; $i < count ( $input [1] ); $i ++) {
			if (! empty ( $input [1] [$i] )) {
				$date [$i] ["index"] = $input [1] [$i] ['value'];
			}
		}
		// var_dump($date);
		Vpm\delete_date_from_core ( $date );
		echo "<script>update_table();</script>";
	} 	

	// ######################
	// # Alle Daten Löschen #
	// ######################
	else if ($input [0] == "delete_all") {
		if (Vpm\delete_whole_core ()) {
			echo "<script>update_table();</script>";
		} else {
			echo "<b style='color:red;'" . "Es gab einen riesen Fehler!" . "</b>";
		}
	}  // ####################
	  // # Download starten #
	  // ####################
	else if ($input [0] == "download") {
		if (Vpm\make_download ( Vpm\find_core_path () )) {
			echo "Das ist ein Test";
		} else {
			echo "Der Test ging in die Hose ";
		}
	}  
	// #############
	// # Einloggen #
	// #############
	else if ($input [0] === "login") {
		$username = $input [1];
		$password = $input [2];
		$keep_logdin = $input [3] === 'true';
		$login_try_res = Main\login_user ( $username, $password, $keep_logdin );
		if ($login_try_res) {
			if (Main\is_logdin ()) {
				echo "Sie wurden erfolgreich ein geloggt! Sie werden weiter geleitet ...";
				?>
				<script language="javascript">document.location.reload();</script>
				<?php
			} else {
				echo "Es gab einen kritischen Fehler bei der Validierung. Bitte laden sie die Seite neu und versuchen sie es erneut!";
			}
		} else if( $login_try_res === false) {
			echo "Das Passwort, oder der Benutzername war Falsch!";
		} else {
			echo "Es gab einen fatalen Fehler in der Datenbank-Validierung. Ein Administrator wurde verständigt!";
		}
	}
	// #############
	// # Ausloggen #
	// #############
	else if ($input [0] === "logout") {
		if(Main\logout_user()) {
			if(!Main\is_logdin()) {
				echo "Sie wurden erfolgreich abgemeldet!"."<br>"." Sie werden weiter geleitet ...";
				?>
				<script language="javascript">document.location.reload();</script>
				<?php
			} else {
				echo "Es gab einen kritischen Fehler bei der Validierung. Bitte laden sie die Seite neu und versuchen sie es erneut!";
			}
		} else {
			echo "Es ist ein sehr exotischer Fehler aufgetreten! Versuchen sie, die Website neu zu laden."."<br>"." Sollte dieser Fehler weiterhin bestehen, wenden sie sich bitte an einen Administrator!";
		}
	}
}
?>