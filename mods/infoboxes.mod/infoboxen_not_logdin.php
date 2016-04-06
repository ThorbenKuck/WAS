
<div class="container">
	<div id="info_logout">
		<div id="before">Anmelden</div>
		<div id="after">Sie sind aktuell nicht Angemeldet!
		</div>
	</div>
	<div id="info_helpme">
		<?php if(defined('THEME_NAME')) { ?>
			<div id="before">Zum Theme:</div>
			<div id="after">Das aktuelle geladene theme ist: <b>"<?php echo THEME_NAME; ?>"</b><br>
							Der Author des themes ist: <b>"<?php echo THEME_AUTHOR; ?>"</b><br>
							Die Version des themes ist: <b>"<?php echo THEME_VERSION . " [" . THEME_TYPE . "]"; ?>"</b>
			</div>
		<?php } else {?>
			<div id="before">Zum Theme:</div>
			<div id="after"> Es konnten keine Informationen geladen werden
			</div>
		<?php }?>
	</div>
	<div id="info_info">
		<div id="before">Hinweis:</div>
		<div id="after">Mit "<b>*</b>" hinterlegte Daten, sind errechnete, nicht manuell eingetragene Daten.
		
		</div>
	</div>
</div>
