<?php
if (session_status () !== PHP_SESSION_ACTIVE)
	session_start ();

if (isset ( $_SESSION ['functions'] )) {
	require_once $_SESSION ['functions'] . "index.php";
} else if (defined ( 'FUNCTIONS' )) {
	require_once FUNCTIONS . 'index.php';
} else {
	die ( "Ein, bereits bekannter Fehler wurde fest gestellt!" . "<br>" . "Unser Team arbeitet an der Behebung dieses Fehlers!" . "<br>" . "Um die Webiste weiter verwenden zu können, drücken sie bitte \"F5\", oder laden sie die Seite neu." . "<br>" . " Wir bitten um ihr Verständniss!" );
}

if (Main\integrated ()) {
	if (! Main\init ())
		die ( "Es gab einen Kritischen Fehler, beim Initialisieren der Funktionen" );
} else {
	die ( "Es gab einen Unbekannten Fehler, beim Einbinden der Funktionen" );
}

Vpm\sort_core ();
// Main\return_new_error ( "Halli Hallo" );
// Main\return_new_warning ( "Halli Hallo nochmal!" );
// Main\return_new_dialogbox("", "500px", "500px", "1000px", "1000px", "required/newdate.php", "")
?>