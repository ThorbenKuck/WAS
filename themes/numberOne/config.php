<?php

if(!defined('AUTOLOAD'))  {
// This will be runned once per site-load
	define ( "AUTOLOAD", ACTIVE_THEME . "/autoload.php" );
}
if(!defined('THEME_NAME'))  {
// Optionale Informationen zum theme
	define ( "THEME_NAME", "Standard" );
}
if(!defined('THEME_AUTHOR'))  {
	define ( "THEME_AUTHOR", "Thorben" );
}
if(!defined('THEME_VERSION'))  {
	define ( "THEME_VERSION", "0.1" );
}
if(!defined('THEME_TYPE'))  {
	define ( "THEME_TYPE", "alpha" );
}

echo $test;
?>