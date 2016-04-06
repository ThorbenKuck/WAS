<?php
if (session_status () !== PHP_SESSION_ACTIVE) {
	session_start ();
}

if (isset ( $_SESSION ['autoload'] ))
	require_once $_SESSION ['autoload'];

$core = Vpm\core_array ( Vpm\find_core_path () );

?>

<br>
Alle Einträge:

<table style="align: center; border-radius: 1px;" border="2">
	<tr>
		<th style="background-color: #FFFFFF">ID</th>
		<th style="background-color: #FFFFFF">Veranstalltungsname</th>
		<th style="background-color: #FFFFFF">Startdatum</th>
		<th style="background-color: #FFFFFF">Startzeit</th>
		<th style="background-color: #FFFFFF">Enddatum</th>
		<th style="background-color: #FFFFFF">Endzeit</th>
		<th style="background-color: #FFFFFF">Die Monitore sind</th>
	</tr>
	<?php
	
	// Erzeugen der Tabelle
	
	$tableHigh = count ( $core ) - 1;
	
	$state = array ();
	
	for($i = 0; $i < count ( $core ); $i ++) {
		if ($core [$i] [5] == 1) {
			$state [$i] = '<b>' . "AUS" . '</b>';
		} else {
			$state [$i] = '<b>' . "AN" . '</b>';
		}
	}
	
	for($i = 0; $i <= $tableHigh; $i ++) {
		$j = $i + 1;
		if ($core [$i] [5] == 1 && ! empty ( $core [$i] ) || $core [$i] [5] == 2 && ! empty ( $core [$i] )) {
			echo '<tr>';
			echo '<td height="50" width="50" style="background-color:#FFFFFF; text-align:center">' . $j . '</td>';
			echo '<td height="50" width="50" style="background-color:#FFFFFF; text-align:center">' . $core [$i] [0] . '</td>';
			echo '<td height="50" width="50" style="background-color:#FFFFFF; text-align:center">' . $core [$i] [1] . '</td>';
			echo '<td height="50" width="50" style="background-color:#FFFFFF; text-align:center">' . $core [$i] [2] . '</td>';
			echo '<td height="50" width="50" style="background-color:#FFFFFF; text-align:center">' . $core [$i] [3] . '</td>';
			echo '<td height="50" width="50" style="background-color:#FFFFFF; text-align:center">' . $core [$i] [4] . '</td>';
			
			if ($state [$i] === '<b>' . "AUS" . '</b>') {
				echo '<td height="50" width="50" style="background-color:#F78181; text-align:center">' . '<center>' . $state [$i] . '</center>' . '</td>';
			} else {
				echo '<td height="50" width="50" style="background-color:#90EE90; text-align:center">' . $state [$i] . '</center>' . '</td>';
			}
			echo '</tr>';
		} else {
			echo '<tr>';
			echo '<td height="50" width="50" style="background-color:#FFFFFF; text-align:center">' . $j . '</td>';
			echo '<td height="50" width="50" style="background-color:#FFFFFF"><span style="color:#FF0000">' . "Kein Daten vorhanden!" . '</span></td>';
			echo '<td height="50" width="50" style="background-color:#FFFFFF"><span style="color:#FF0000">' . "Kein Daten vorhanden!" . '</span></td>';
			echo '<td height="50" width="50" style="background-color:#FFFFFF"><span style="color:#FF0000">' . "Kein Daten vorhanden!" . '</span></td>';
			echo '<td height="50" width="50" style="background-color:#FFFFFF"><span style="color:#FF0000">' . "Kein Daten vorhanden!" . '</span></td>';
			echo '<td height="50" width="50" style="background-color:#FFFFFF"><span style="color:#FF0000">' . "Kein Daten vorhanden!" . '</span></td>';
			echo '<td height="50" width="50" style="background-color:#FFFFFF"><span style="color:#FF0000">' . "Kein Daten vorhanden!" . '</span></td>';
			echo '</tr>';
		}
	}
	?>
</table>
<br>
<br>
<br>
<br>