<!DOCTYPE html>
<?php
if (session_status () !== PHP_SESSION_ACTIVE)
	session_start ();
?>

	<head>
		<title>Debug-Fenster</title>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
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
				font-size: 26px;
				text-align: center;
				background-color: rgba(255, 255, 255, 0.2);
				color: red;
			}

			#debug_content {
				margin-top: 5px;
				background-color: rgba(255, 255, 255, 0.1);
				color: whitesmoke;
				position: relative;
				padding: 5px;
				line-height: 1.3;
			}

			details .custom {
				color: black;
				margin-top: 5px;
				margin-bottom: 5px;
				border-left: 5px solid cornflowerblue;
				background-color: lightblue;
			}

			summary .custom {
				background-color: black;
				color: white;
			}
		</style>
	</head>


<?php

echo "<br>";
if (isset($_SESSION ['System']['debug'])) {
	$debug_content = $_SESSION ['System']['debug'];
}

$custom_debug_content = null;
if (isset ($debug_content['custom']) && !empty ($debug_content['custom'])) {
	$custom_debug_content = $debug_content['custom'];
}

$system_debug_content = null;
if (isset ($debug_content['system_debug']) && !empty ($debug_content['system_debug'])) {
	$system_debug_content = $debug_content['system_debug'];
}

function starts_with ($word, ...$what) {
	foreach ( $what as $value ) {
		if ((substr ($word, 0, strlen ($value)) === $value)) return true;
	}
	return false;
}

function output ($what) {
	if (is_array ($what)) {
		for ( $i = 0 ; $i < count ($what) ; $i ++ ) {
			output ($what [$i]);
		}
	} else {
		switch ( true ) {
			case starts_with ($what, "(li)"):
				$what = str_replace ("(li)", "", $what);
				echo "   &#8226; " . $what . "<br>";
				break;
			case starts_with ($what, "(Info)"):
				$what = str_replace ("(Info)", "", $what);
			case starts_with ($what, "[Info]"):
				echo "$ <p style='color:white;'><strong>[Info]: " . $what . "</p></strong><br>";
				break;
			case starts_with ($what, "(Title)"):
				$what = str_replace ("(Title)", "", $what);
			case starts_with ($what, "[Title]");
				echo "<p style='color:white; font-size:30px; text-decoration: underline; text-align: center;'><b>" . $what . "</b></p>";
				break;
			case starts_with ($what, "(Notice)"):
				$what = str_replace ("(Notice)", "", $what);
			case starts_with ($what, "[Notice]");
				echo "$ <b style='color:white;'>[<strong style='color:deepskyblue;'>Notice</strong>]: " . $what . "</b><br>";
				break;
			case starts_with ($what, "(Warning)"):
				$what = str_replace ("(Warning)", "", $what);
				$prefix = "$ [<b style='color:yellow;'>Warning</b>]: ";
				echo $prefix . $what . "<br>";
				break;
			case starts_with ($what, "[Warning]"):
				echo "$ <b style='color:yellow;'>" . $what . "</b><br>";
				break;
			case starts_with ($what, "(Error)"):
				$prefix = "$ [<b style='color:red;'>Error</b>]: ";
				$what = str_replace ("(Error)", "", $what);
				echo $prefix . $what . "<br>";
				break;
			case starts_with ($what, "[ERROR]", "[Error]"):
				echo "<b style='color:red;'>" . $what . "</b><br>";
				break;
			case starts_with ($what, "(Fatal-Error)"):
				$prefix = "$ [<b style='color:red;'>FATAL</b>]: ";
				$what = str_replace ("(Fatal-Error)", "", $what);
				echo $prefix . $what . "<br>";
				break;
			case starts_with ($what, "[Fatal-Error]", "[FATAL]", "[Fatal]"):
				echo "<u><b style='color:red;font-size:20px;'>" . $what . "</b></u><br>";
				break;
			case starts_with ($what, "(StackStart)"):
				echo "<details class='custom'><summary class='custom'><strong>" . str_replace ("(StackStart)", "", $what) . "</strong></summary>";
				break;
			case starts_with ($what, "(StackEnd)"):
				echo "</details>";
				break;
			default:
				$prefix = "";
				if (!empty($what)) {
					$prefix = "$ ";
				}
				echo $prefix . $what . "<br>";
				break;
		}
	}
}
?>

	<body>
	<div id="debug_header">Debug-Fenster</div>
	<br>
	<hr>
<?php
if ($system_debug_content !== null) {
	if (is_array ($debug_content)) {
		foreach ( $debug_content as $key => $value ) {
			?>
			<details><summary>
			<div id="debug_header"><?php echo $key; ?></div>
				</summary>
			<div id="debug_content">
				<?php
				if (is_array ($debug_content[$key])) {
					foreach ( $debug_content[$key] as $key2 => $value2 ) {
						output ($value2);
						echo "<hr>";
					}
				} else {
					output ($system_debug_content);
				}
				?>
			</div>
			</details>
			<br>
			<?php
		}
	}
} else {
	echo "debug_content existiert: ";
	var_dump (isset ($_SESSION ['System']['debug']));
	echo "<br>";
	echo "debug_content leer: ";
	var_dump (empty ($_SESSION ['System']['debug']));
	echo "<br>";
	echo "<hr><hr>";
	echo "<b style='color:red;'>" . "Der Inhalt der Session wurde aus Sicherheitsgründen geleert! Bitte Lade die Haupt-Seite erneut um eine neue Debug-Auswertung zu erhalten!" . "</b><hr>";
}

if (isset($_SESSION['System']['settings']['empty_debug_stacktrace_after_execution']) && $_SESSION['System']['settings']['empty_debug_stacktrace_after_execution']) {
	unset ($_SESSION ['System']['debug']);
	session_write_close ();
}

?>