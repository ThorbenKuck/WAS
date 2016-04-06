
<div class="container">
	<div id="info_login">
		<div id="before">Eingelogt</div>
		<div id="after">Sie sind als <b><?php echo Main\get_username();?></b> angemeldet.
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
		<div id="after">Die ihnen zugeteielten Rechte sind: "<b><?php echo Main\get_user_permissions ();?></b>"
		
		</div>
	</div>
</div>
