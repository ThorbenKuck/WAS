<?php

/**
 * The configuration for the Framework
 *
 * @TODO Change the config, so that only variables are used and the define-statements are done inside the Vpm-Framework
 * @TODO Change the type to ini
 *
 */

// Der absolute Pfad zu dem Ordner
if (!defined ('ABSPATH')) {
	define ('ABSPATH', dirname (__FILE__) . '/');
	\Main\set_new_info ('root_path', ABSPATH);

// Logischer name des Speicherordners der themes
// heisst genauso wie der Ordner und wird OHNE / geschrieben
	define ('THEMES_NAME', "themes");
	\Main\set_new_info ('themes_root_package_name', THEMES_NAME);
	define ('THEMES', ABSPATH . THEMES_NAME . "/");
	\Main\set_new_info ('themes_root_path', THEMES);

// beschreibt welches Theme aktiv ist
// heisst genauso wie der Unterordner des Ordners THEMES , je nachdem welches Theme angezeigt werden soll
	define ('ACTIVE_THEME_NAME', "numberOne");
	\Main\set_new_info ('active_theme_name', ACTIVE_THEME_NAME);
	define ('ACTIVE_THEME', THEMES . ACTIVE_THEME_NAME . "/");
	\Main\set_new_info ('active_theme_path', ACTIVE_THEME);

// Beschreibt den Speicherort der function
	define ('PACKAGES_NAME', "packages");
	\Main\set_new_info ('package_folder_name', PACKAGES_NAME);
	define ('PACKAGES', ABSPATH . PACKAGES_NAME . "/");
	\Main\set_new_info ('package_folder_root_path', PACKAGES);

// Beschreibt den Speicherort der mods
	define ('MODS_NAME', "mods");
	\Main\set_new_info ('mod_folder_name', MODS_NAME);
	define ('MODS', ABSPATH . MODS_NAME . "/");
	\Main\set_new_info ('mod_folder_root_path', MODS);

// The main class, for getting the Settings and stuff.
	define ('MAIN_CLASS', ABSPATH . "Main.class.php");
	\Main\set_new_info ('main_class_path', MAIN_CLASS);

	define ('TEST_FILE', ABSPATH . "test.php");
	\Main\set_new_info('test_file', TEST_FILE);
}