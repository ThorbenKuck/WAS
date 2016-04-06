<?php
session_start ();
?>

<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
</head>




<?php

if (file_exists ( 'functions/index.php' )) {
	require_once 'functions/index.php';
	if (Main\integrated ()) {
		Main\init ();
	} else
		die ( "Es gab einen Fehler mit der Darstellung!" . "<br>" . "Bitte gedulden sie sich etwas!" . "<br>" . "Die Administratoren wurden verständigt" );
} else
	die ( "Es gab einen Fehler mit der Darstellung!" . "<br>" . "Bitte gedulden sie sich etwas!" . "<br>" . "Die Administratoren wurden verständigt" );

if (DEV_MODE) {
	ini_set ( "display_errors", "1" );
	error_reporting ( E_ALL );
} else {
	ini_set ( "display_errors", "0" );
}

if (! (Main\is_admin () || Main\is_root ())) {
	die ( "Um diese Seite sehen zu können, müssen sie als ein Administrator angemeldet sein!" );
}

var_dump(Main\load_mods());

if(defined('ABSPATH')) {
	mkdir(FUNCTIONS . "main.package/");
}

?>