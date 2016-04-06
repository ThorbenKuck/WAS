<?php
$core = Vpm\core_array( Vpm\find_core_path() );
?>

<button id="delete_submit"> Lösche!
</button>
<form id="delete_select">
	<table border="1">
		<tr>
			<th>Name</th>
			<th>Startdatum</th>
			<th>Startzeit</th>
			<th>Enddatum</th>
			<th>Endzeit</th>
			<th>Zustand der Monitore</th>
			<th>Löschen?</th>
		</tr>
		
		<?php
		for($i = 0; $i < count ( $core ); $i ++) {
			?>
			<tr>
				<td><?php echo $core[$i][0]; ?></td>
				<td><?php echo $core[$i][1]; ?></td>
				<td><?php echo $core[$i][2]; ?></td>
				<td><?php echo $core[$i][3]; ?></td>
				<td><?php echo $core[$i][4]; ?></td>
				<td>
				<?php 
				if ($core [$i] [5] == 1) {
					echo "Aus";
				} else {
					echo "An";
				}
				?>
				</td>
				<td><input type="checkbox" name="checkbox<?php echo $i; ?>" id="checkbox<?php echo $i; ?>" value="<?php echo $i;?>"></input></td>
			</tr>
			<?php
		}
		?>
		
	
	</table>
</form>

