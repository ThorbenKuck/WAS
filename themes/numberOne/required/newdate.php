<h1>Erstellen sie ein neuen Termin:</h1>
<form action="" method="post" id="new_date">
	<table border="">
		<tr>
			<th>Wie ist der Name ihres Tages?</th>
			
			
				<th><input type="text" name="neu" id="veranstalltungs_name" /></th>
		</tr>
		<tr>
			<th>Die Veranstaltung beginnt am:</th>
			<th><select name="tag" id="start_tag">
			<?php
			for($i = 1; $i < 32; $i ++) {
				if (date ( "j" ) == $i) {
					?>
					<option value="<?php echo $i ?>" selected><?php echo $i ?></option>
					<?php
				} else {
					?>
					<option value="<?php echo $i ?>"><?php echo $i ?></option>
					<?php
				}
			}
			?>
		</select> <select name="monat" id="start_monat">
			<?php
			$monate = [ 
					1 => "Jannuar",
					2 => "Februar",
					3 => "März",
					4 => "April",
					5 => "Mai",
					6 => "Juni",
					7 => "Juli",
					8 => "August",
					9 => "September",
					10 => "Oktober",
					11 => "November",
					12 => "Dezember" 
			];
			
			for($i = 1; $i < 13; $i ++) {
				if (intval ( date ( "n" ) ) === $i) {
					?>
					<option value="<?php echo $i; ?>" selected><?php echo $monate[$i]; ?></option>
					<?php
				} else {
					?>
					<option value="<?php echo $i; ?>"><?php echo $monate[$i]; ?></option>
					<?php
				}
			}
			
			?>
		</select> <select name="jahr" id="start_jahr">
			<?php
			
			$jahr = date ( "Y" );
			
			for($i = 0; $i < 4; $i ++) {
				
				?>
				<option value="<?php echo $jahr ?>"><?php echo $jahr ?></option>
				<?php
				$jahr ++;
			}
			?>
		</select></th>
		</tr>
		<tr>
			<th>Um</th>
			<th><select name="stunde" id="start_stunde">
			<?php
			
			for($i = 0; $i < 24; $i ++) {
				if ($i >= 0 && $i <= 9) {
					if (intval ( date ( "G" ) ) === $i) {
						?>
						<option value="<?php echo "0".$i; ?>" selected><?php echo "0".$i; ?></option>
						<?php
					} else {
						?>
						<option value="<?php echo "0".$i; ?>"><?php echo "0".$i; ?></option>
						<?php
					}
				} else {
					if (intval ( date ( "G" ) ) === $i) {
						?>
						<option value="<?php echo $i; ?>" selected><?php echo $i; ?></option>
						<?php
					} else {
						?>
						<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
						<?php
					}
				}
			}
			?>
		</select> : <select name="minute" id="start_minute">
			<?php
			for($i = 0; $i < 60; $i ++) {
				if ($i >= 0 && $i <= 9) {
					if (intval ( date ( "i" ) ) === $i) {
						?>
						<option value="<?php echo "0".$i; ?>" selected><?php echo "0".$i; ?></option>
						<?php
					} else {
						?>
						<option value="<?php echo "0".$i; ?>"><?php echo "0".$i; ?></option>
						<?php
					}
				} else {
					if (intval ( date ( "i" ) ) === $i) {
						?>
						<option value="<?php echo $i; ?>" selected><?php echo $i; ?></option>
						<?php
					} else {
						?>
						<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
						<?php
					}
				}
			}
			?>
		</select> Uhr</th>
		</tr>
		<tr>
			<th>Und die Veranstaltung endet am:</th>
			<th><select name="etag" id="end_tag">
			<?php
			for($i = 1; $i < 32; $i ++) {
				if ((intval ( date ( "j" ) ) + 1) == $i) {
					?>
					<option value="<?php echo $i ?>" selected><?php echo $i ?></option>
					<?php
				} else {
					?>
					<option value="<?php echo $i ?>"><?php echo $i ?></option>
					<?php
				}
			}
			?>
		</select> <select name="emonat" id="end_monat">
			<?php
			for($i = 1; $i < 13; $i ++) {
				if (intval ( date ( "t" ) ) === intval ( date ( "j" ) )) {
					if ((intval ( date ( "n" ) ) + 1) === $i) {
						?>
						<option value="<?php echo $i; ?>" selected><?php echo $monate[$i]; ?></option>
						<?php
					} else {
						?>
						<option value="<?php echo $i; ?>"><?php echo $monate[$i]; ?></option>
						<?php
					}
				} else {
					if (intval ( date ( "n" ) ) === $i) {
						?>
						<option value="<?php echo $i; ?>" selected><?php echo $monate[$i]; ?></option>
						<?php
					} else {
						?>
						<option value="<?php echo $i; ?>"><?php echo $monate[$i]; ?></option>
						<?php
					}
				}
			}
			?>
		</select> <select name="ejahr" id="end_jahr">
			<?php
			
			$jahr = date ( "Y" );
			
			for($i = 0; $i < 4; $i ++) {
				
				?>
				<option value="<?php echo $jahr ?>"><?php echo $jahr ?></option>
				<?php
				$jahr ++;
			}
			?>
		</select></th>
		</tr>
		<tr>
			<th>Um</th>
			<th><select name="estunde" id="end_stunde">
			<?php
			
			for($i = 0; $i < 24; $i ++) {
				if ($i >= 0 && $i <= 9) {
					?>
					<option value="<?php echo "0".$i; ?>"><?php echo "0".$i; ?></option>
					<?php
				} else {
					?>
					<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
					<?php
				}
			}
			?>
		</select> : <select name="eminute" id="end_minute">
			<?php
			
			for($i = 0; $i < 60; $i ++) {
				if ($i >= 0 && $i <= 9) {
					?>
					<option value="<?php echo "0".$i; ?>"><?php echo "0".$i; ?></option>
					<?php
				} else {
					?>
					<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
					<?php
				}
			}
			?>
		</select> Uhr</th>
		</tr>
		<?php
		echo '<br>';
		echo '<br>';
		echo '<br>';
		?>
		<tr>
			<th><p>Die Monitore sollen zu diesem Zeitpunkt</p></th>
			<th><select name="tf" id="monitor_zustand">
					<option value="2">An sein</option>
					<option value="1">Aus sein</option>
			</select></th>
		</tr>
	</table>
	<br>
	

	<button id="submit"
		style="padding: 0px; left: 0px; margin-left: 0px; width: 0px; height: 0px; position: relative; bottom: 0px; cursor: pointer; width: 100px; height: 50px; margin-right: 10px; left: 0px; top: 10px; vertical-align: bottom;">
		Erstellen!</button>
	<button id="abbort"
		style="padding: 0px; left: 0px; margin-left: 0px; width: 0px; height: 0px; position: relative; bottom: 0px; cursor: pointer; width: 100px; height: 50px; margin-right: 10px; left: 0px; top: 10px; vertical-align: bottom;">
		Abbrechen</button>
		
		
		
</form>
