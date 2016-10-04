<?php

if (session_status () !== PHP_SESSION_ACTIVE)
	session_start ();
?>

<head>
	<title>Info-Fenster</title>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
	<style type="text/css">
		body {
			color: white;
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
			width: 100%;
			text-align: left;
			background-color: rgba(255, 255, 255, 0.1);
			color: lightgreen;
			position: relative;
		}

		table {
			word-break: break-all;
			width: 100%;
			border: 1px solid #ff3d1f;
		}

		th {
			border: 1px solid #fff176;
		}

		#one {
			width: 30%;
		}

		#two {
			width: 60%;
		}
	</style>
</head>


<?php

echo "<br>";

if (isset ($_SESSION['System']['info']) && isset ($_SESSION['System']['settings'])) {
	$settings_content = $_SESSION['System']['settings'];
	$info_content = $_SESSION['System']['info'];
}
function output ($key, $value, $search_for_bool_integers = false) {
	echo "<th id='one'>" . $key . "</th><th id='two'>";
	if (is_array ($value)) {
		echo " array(" . count ($value) . ")  {" . "<br>";
		foreach ( $value as $key2 => $value2 ) {
			echo $key2 . "=" . $value2;
			echo " , ";
		}
		echo "}" . "<br>";
	} else {
		if ($search_for_bool_integers && ($value == "1" || $value == "0")) {
			$to_echo = "<b style='color:red;'>false</b>";
			if ($value == "1") {
				$to_echo = "<b style='color:green;'>true</b>";
			}
			echo $to_echo;
		} else {
			echo $value;
		}
	}
	echo "</th>";
}

?>

<body>
<details><summary><div id="debug_header">Settings</div></summary>
<div id="debug_content">
	<hr>
	<?php
	if (!empty ($settings_content)) {

		if (is_array ($settings_content)) {
			echo "<table>";
			foreach ( $settings_content as $key => $value ) {
				echo "<tr>";
				output ($key, $value, true);
				echo "</tr>";
			}
			echo "</table>";
		}
	} else {
		echo "session [CONFIG] existiert: ";
		var_dump (isset ($_SESSION ['System']['settings']));
		echo "<br>";
		echo "session [CONFIG] leer: ";
		var_dump (empty ($_SESSION ['System']['settings']));
		echo "<br>";
		echo "<hr><hr>";
		echo "<b style='color:red;'>" . "Die Konfigurations-Session konnte nicht ausgelesen werden" . "</b><hr>";
	}
	?>
</div>
</details>
<br><br>
<details>
	<summary>
		<div id="debug_header">Info</div>
	</summary>
	<div id="debug_content">
		<hr>
		<?php
		if (!empty ($info_content)) {
			if (is_array ($info_content)) {
				echo "<table>";
				foreach ( $info_content as $key => $value ) {
					echo "<tr>";
					output ($key, $value);
					echo "</tr>";
				}
				echo "</table>";
			} else {
				output ("Warning!", "The info has NOT been correctly passed!");
			}
		} else {
			echo "session [CONFIG] existiert: ";
			var_dump (isset ($_SESSION ['System']['info']));
			echo "<br>";
			echo "session [CONFIG] leer: ";
			var_dump (empty ($_SESSION ['System']['info']));
			echo "<br>";
			echo "<hr><hr>";
			echo "<b style='color:red;'>" . "Die Konfigurations-Session konnte nicht ausgelesen werden" . "</b><hr>";
		}
		?>
	</div>
</details>
</body>