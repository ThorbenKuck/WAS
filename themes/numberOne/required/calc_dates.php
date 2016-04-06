<?php
?>

<p>
	Wollen sie automatisch errechnete Feiertage einfügen? <br> Ein
	Algorithmus berechnet alle Ferientage dieses Jahres!<br>
	<br> Bis (einschließlich) welches Jahr sollen die Feiertage
	berechnetwerden?
</p>
<form>
	<select name="jahrestop" id="year">
		<?php
		$jahr = intval ( date ( "Y" ) );
		for($i = 0; $i < 4; $i ++) {
			?>
			<option value=" <?php  echo $jahr + $i; ?>"><?php echo $jahr + $i; ?></option>
			<?php
		}
		?>
	</select>

</form>
<br>
<br>
<button id="submit" style="width: 100px; height: 50px;">Tage Einfügen!</button>
<br>
<p>
	<b style="color: orange;">Ein kleines Wort Der Warung!</b><br> Es wird
	kein Fehler geworfen, wenn alle / einige Daten, die sie einfügen,
	bereits belegt sind!
</p>