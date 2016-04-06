<?php

Main\include_css ( "mods/infoboxes.mod/css/info.css", false );

if(Main\is_logdin())  {
	require 'infoboxen_logdin.php';
} else {
	require 'infoboxen_not_logdin.php';
}


Main\debug_input("(Title): Das ist ein Test");


?>