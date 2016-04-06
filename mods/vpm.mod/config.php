<?php 
if (! defined ( 'PATH_TO_VPM_MOD' )) {
	/**
	 * The absolute path to the vpm-mod
	 */
	define ( 'PATH_TO_VPM_MOD', dirname ( __FILE__ ) . '/' );
	$_SESSION ['PATH_TO_VPM_MOD'] = PATH_TO_VPM_MOD;
}

if (! defined ( 'PATH_TO_VPM_CORE' )) {
	/**
	 * The absolute path to the vpm-core
	 */
	define ( 'PATH_TO_VPM_CORE', dirname ( __FILE__ ) . '/core/core.csv' );
	$_SESSION ['PATH_TO_VPM_CORE'] = PATH_TO_VPM_CORE;
}

if (! defined ( 'VPM_FREE_DAYS' )) {
	/**
	 * The absolute path to the vpm-free-day-file
	 */
	define ( 'VPM_FREE_DAYS', dirname ( __FILE__ ) . '/ferientage/' );
	$_SESSION ['VPM_FREE_DAYS'] = VPM_FREE_DAYS;
}

if (! defined ( 'VPM_CONNECT' )) {
	/**
	 * The absolute path to the vpm-connect folder
	 */
	define ( 'VPM_CONNECT', dirname ( __FILE__ ) . '/connect/' );
	$_SESSION ['VPM_CONNECT'] = VPM_CONNECT;
}

// Zur Logik der Berechnung

if (! defined ( 'STARTUHR' )) {
	define ( 'STARTUHR', 715 );
}

if (! defined ( 'ENDUHR' )) {
	define ( 'ENDUHR', 1615 );
}

if (! defined ( 'CONNECTION-IP' )) {
	define ( 'CONNECTION-IP', "192.168.178.17" );
}
?>