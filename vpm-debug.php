<?php

if (session_status () !== PHP_SESSION_ACTIVE)
	session_start ();
?>

<head>
<title>Debug-Fenster</title>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<style type="text/css">
body {
	color: yellow;
	margin: 0;
	background-color: black;
}

#debug_header {
	top: 0px;
	margin: 0;
	width: 100%;
	height: 30px;
	text-align: center;
	background-color: rgba(255, 255, 255, 0.2);
	color: red;
	background-color: rgba(255, 255, 255, 0.2)
}

#debug_content {
	margin-top: 5px;
	width: 100%;
	text-align: center;
	background-color: rgba(255, 255, 255, 0.1);
	color: lightgreen;
	position: relative;
	text-align: center;
}
</style>
</head>



<?php 

echo "<br>";

if (! empty ( $_SESSION ['debug_content'] ) && isset ( $_SESSION ['debug_content'] )) {
	$debug_content = $_SESSION ['debug_content'];
}
function output($what) {	
	if (is_array ( $what )) {
		for($i = 0; $i < count ( $what ); $i ++) {
			output ( $what [$i]);
			echo "<br>";
		}
	} else if (strpos ( $what, "(Type)" ) !== false) {
		echo $what;
	} else if (strpos ( $what, "(Name)" ) !== false) {
		echo $what;
	} else if (strpos ( $what, "(Place)" ) !== false) {
		echo $what;
	} else if (strpos ( $what, "(Ort)" ) !== false) {
		echo "<b style='color:white;'>" . $what . "</b>";
	} else if (strpos ( $what, "(Title)" ) !== false) {
		echo "<b style='font-size:30px;'>" . $what . "</b>";
	} else if (strpos ( $what, "(Info)" ) !== false) {
		echo "<i style='color:white;'>" . $what . "</i>";
	} else if(strpos ( $what, "(Notice)" ) !== false) {
		echo "<br><b style='color:white;'>" . $what . "</b>";
	} else if(strpos ( $what, "(Warning)" ) !== false) {
		echo "<br><b style='color:yellow;'>" . $what . "</b>";
	} else if(strpos ( $what, "(Error)" ) !== false) {
		echo "<br><b style='color:red;'>" . $what . "</b>";
	} else if(strpos ( $what, "(Fatal-Error)" ) !== false) {
		echo "<br><u><b style='color:red;font-size:20px;'>" . $what . "</b></u>";
	} else {
		echo $what;
	}
}

?>

<body>
	<div id="debug_header">Debug-Fenster</div>
	<div id="debug_content">
	<hr>
	<?php
	if (! empty ( $debug_content )) {
		echo "session [debug_content] existiert: ";
		var_dump ( isset ( $_SESSION ['debug_content'] ) );
		echo "<br>";
		echo "session [debug_content] leer: ";
		var_dump ( empty ( $_SESSION ['debug_content'] ) );
		echo "<br>";
		
		echo "<hr><hr>";
		
		if (is_array ( $debug_content )) {
			for($i = 0; $i < count ( $debug_content ); $i ++) {
				output ( $debug_content [$i] );
				echo "<hr>";
			}
		} else {
			output ( $debug_content );
		}
	} else {
		echo "<b style='color:red;'>" . "Der Inhalt der Session wurde aus Sicherheitsgründen geleert! Bitte Lade die Haupt-Seite erneut um eine neue Debug-Auswertung zu erhalten!" . "</b><hr>";
	}
	?>
	</div>
</body>

<?php

unset ( $_SESSION ['debug_content'] );
session_write_close ();

?>