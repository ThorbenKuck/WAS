<?php

/**
 * The configuration for the Framework
 * 
 * @todo Change the config, so that only variables are used and the define-statements are done inside the Vpm-Framework
 * 
 */

// Der absolute Pfad zu dem Ordner
if (! defined ( 'ABSPATH' )) {
	/**
	 * The absolute path to the web-s-socket
	 */
	define ( 'ABSPATH', dirname ( __FILE__ ) . '/' );
}

// Beschreibt den Speicherort vieler wichtiger Scripte u$$nd Algorithmen
define ( 'CLASSES', ABSPATH . "classes/" );

// Kann genutzt werden, um zu testen, ob die konfiguration geladen ist.
define ( 'SUCCES', 1 );

// Logischer name des Speicherordners der themes
// heisst genauso wie der Ordner und wird OHNE / geschrieben
define ( 'THEMES_NAME', "themes" );
define ( 'THEMES', ABSPATH . THEMES_NAME . "/" );

// beschreibt welches Theme aktiv ist
// heisst genauso wie der Unterordner des Ordners THEMES , je nachdem welches Theme angezeigt werden soll
define ( 'ACTIVE_THEME_NAME', "numberOne" );
define ( 'ACTIVE_THEME', THEMES . ACTIVE_THEME_NAME . "/" );

// Beschreibt den Speicherort der function
define ( 'FUNCTIONS_NAME', "functions" );
define ( 'FUNCTIONS', ABSPATH . FUNCTIONS_NAME . "/" );

// Beschreibt den Speicherort der mods
define ( 'MODS_NAME', "mods" );
define ( 'MODS', ABSPATH . MODS_NAME . "/" );

// now for css and js
define ( 'CSS_ACTIVE_THEME', THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/" );

// For Debugging
define ( 'DEBUG_MODE', true );

// if  only admins can see the debug frame
define ( 'ADMIN_ONLY_DEBUG', false );

// Enabeling the Developing Mode
define ( 'DEV_MODE', true );

// Wether or not mods should be used
define ( 'MOD_USAGE', true );


// All needs for MySQL
define ( 'MYSQL_USER', "pi" );
define ( 'MYSQL_PASSWORD', "daspasswort" );
define ( 'MYSQL_HOST', "localhost" );
define ( 'MYSQL_DATABASE', "vpm" );

?>
