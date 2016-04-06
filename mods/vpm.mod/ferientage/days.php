<?php

if(!isset($jahr))
	die("Das klappt so nicht!");

$datum_now = date ( "Y-m-d" );
// var_dump(easter_date($jahr)); echo "</br>"; echo "</br>";
$timestamp = easter_date ( $jahr );
// $datum = gmdate("d.m", $timestamp);
$tage = 60 * 60 * 24;
// $zeit = 31556926;
// $ostern = date("d.m.$jahr",mktime(0,0,0,date ("m", easter_date($jahr)),date ("d", easter_date($jahr)),date ("Y", easter_date($jahr))));

$feiertage = array ();

$feiertage [0] ["Begründung"] = "Ostersonntag";
$feiertage [1] ["Begründung"] = "Neujahr";
$feiertage [2] ["Begründung"] = "Heilige Drei Könige";
$feiertage [3] ["Begründung"] = "Karfreitag";
$feiertage [4] ["Begründung"] = "Ostermontag";
$feiertage [5] ["Begründung"] = "Tag der Arbeit";
$feiertage [6] ["Begründung"] = "Himmelfahrt";
$feiertage [7] ["Begründung"] = "Pfingstsonntag";
$feiertage [8] ["Begründung"] = "Pfingstmontag";
$feiertage [9] ["Begründung"] = "Tag der Deutschen Einheit";
$feiertage [10] ["Begründung"] = "Heiligabend";
$feiertage [11] ["Begründung"] = "Erster Weihnachtstag";
$feiertage [12] ["Begründung"] = "Zweiter Weihnachtstag";
$feiertage [13] ["Begründung"] = "Sylvester";
	
// definition der Daten
$feiertage [0] ["Datum"] = date ( "d.m.$jahr", $timestamp ); // Ostersonntag
$feiertage [0] ["check"] = date ( "$jahr.m.d", $timestamp ); // Ostersonntag
$feiertage [1] ["Datum"] = date ( "01.01.$jahr" ); // Neujahr
$feiertage [1] ["check"] = date ( "$jahr-01-01" ); // Neujahr
$feiertage [2] ["Datum"] = date ( "06.01.$jahr" ); // Heilige Drei Könige
$feiertage [2] ["check"] = date ( "$jahr-01-06" ); // Heilige Drei Könige
$feiertage [3] ["Datum"] = date ( "d.m.$jahr", $timestamp - 2 * $tage ); // Karfreitag
$feiertage [3] ["check"] = date ( "$jahr-m-d", $timestamp - 2 * $tage ); // Karfreitag
$feiertage [4] ["Datum"] = date ( "d.m.$jahr", $timestamp + 1 * $tage ); // Ostermontag
$feiertage [4] ["check"] = date ( "$jahr-m-d", $timestamp + 1 * $tage ); // Ostermontag
$feiertage [5] ["Datum"] = date ( "01.05.$jahr" ); // Maifeiertag
$feiertage [5] ["check"] = date ( "$jahr-05-01" ); // Maifeiertag
$feiertage [6] ["Datum"] = date ( "d.m.$jahr", $timestamp + 39 * $tage ); // Himmelfahrt
$feiertage [6] ["check"] = date ( "$jahr-m-d", $timestamp + 39 * $tage ); // Himmelfahrt
$feiertage [7] ["Datum"] = date ( "d.m.$jahr", $timestamp + 49 * $tage ); // Pfingstsonntag
$feiertage [7] ["check"] = date ( "$jahr-m-d", $timestamp + 49 * $tage ); // Pfingstsonntag
$feiertage [8] ["Datum"] = date ( "d.m.$jahr", $timestamp + 50 * $tage ); // Pfingstmontag
$feiertage [8] ["check"] = date ( "$jahr-m-d", $timestamp + 50 * $tage ); // Pfingstmontag
$feiertage [9] ["Datum"] = date ( "03.10.$jahr" ); // Tag der Deutschen Einheit
$feiertage [9] ["check"] = date ( "$jahr-10-03" ); // Tag der Deutschen Einheit
$feiertage [10] ["Datum"] = date ( "24.12.$jahr" ); // Heiligabend
$feiertage [10] ["check"] = date ( "$jahr-12-24" ); // Heiligabend
$feiertage [11] ["Datum"] = date ( "25.12.$jahr" ); // Eerster Weihnachtstag
$feiertage [11] ["check"] = date ( "$jahr-12-25" ); // Eerster Weihnachtstag
$feiertage [12] ["Datum"] = date ( "26.12.$jahr" ); // Zweiter Weihnachtstag
$feiertage [12] ["check"] = date ( "$jahr-12-26" ); // Zweiter Weihnachtstag
$feiertage [13] ["Datum"] = date ( "31.12.$jahr" ); // Zweiter Weihnachtstag
$feiertage [13] ["check"] = date ( "$jahr-12-31" ); // Zweiter Weihnachtstag


// var_dump($feiertage[0]["Begründung"]); echo "</br>"; echo "</br>";
// var_dump($feiertage[0]["Datum"]); echo "</br>"; echo "</br>";
// var_dump($feiertage[4]["Begründung"]); echo "</br>"; echo "</br>";
// var_dump($feiertage[4]["Datum"]); echo "</br>"; echo "</br>";
// var_dump($feiertage); echo "</br>"; echo "</br>";

// die("Bis hier");
?>