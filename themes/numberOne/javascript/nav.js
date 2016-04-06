$(document).ready(function(){
	var newDate	= document.getElementById("new");
	var upload		= document.getElementById( "upload" );
	var calc		= document.getElementById( "calc" );
	var dele		= document.getElementById( "delete" );
	var reset		= document.getElementById( "reset" );
	var download	= document.getElementById( "download" );
	var admin		= document.getElementById( "admin" );
	var now			= document.getElementById( "now" );
	var help		= document.getElementById( "help" );
	var settings	= document.getElementById( "settings" );
	var logout		= document.getElementById( "logout" );
	var impressum	= document.getElementById( "impressum" );
	var home 		= document.getElementById( "toHome" );
	var login		= document.getElementById( "login" );
	
	var body_content = document.getElementById("body_content");
	var nav_info = document.getElementById("nav-info");
	console.log("Start");
	
	$(newDate).click(function() {
		var erg = '<?php echo $_SESSION["abspath"]?>';;
		alert(erg);
	});
			
//			function(){
//		$(body_content).load(window.location.href + '<?php echo THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/navigate/newDate.php"; ?>');
//		$(nav_info).html("<b>Neuen Termin erstellen</b>");
//	});
//	
//	$(upload).click(function(){
//		$(body_content).load(window.location.href + '<?php echo THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/navigate/upload.php"; ?>');
//		$(nav_info).html("<b>CSV-Hochladen</b>");
//	});
//	
//	$(calc).click(function(){
//		$(body_content).load(window.location.href + '<?php echo THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/navigate/calc.php"; ?>');
//		$(nav_info).html("<b>Errechnete Tage einfügen</b>");
//	});
//	
//	$(dele).click(function(){
//		$(body_content).load(window.location.href + '<?php echo THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/navigate/delete.php"; ?>');
//		$(nav_info).html("<b>Einen Termin löschen</b>");
//	});
//	 
//	$(reset).click(function(){
//		$(body_content).load(window.location.href + '<?php echo THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/navigate/reset.php"; ?>');
//		$(nav_info).html("<b>Alle Termine löschen</b>");
//	});
//	
//	$(download).click(function(){
//		$(body_content).load(window.location.href + '<?php echo THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/navigate/download.php"; ?>');
//		$(nav_info).html("<b>CSV-Download</b>");
//	});
//	
//	$(admin).click(function(){
//		$(body_content).load(window.location.href + '<?php echo THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/navigate/admin.php"; ?>');
//		$(nav_info).html("<b>Administrativer Bereich</b>");
//	});
//	
//	$(now).click(function(){
//		$(body_content).load(window.location.href + '<?php echo THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/navigate/now.php"; ?>');
//		$(nav_info).html("<b>Monitorzustand</b>");
//	});
//	
//	$(help).click(function(){
//		$(body_content).load(window.location.href + '<?php echo THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/navigate/help.php"; ?>');
//		$(nav_info).html("<b>Hilfe</b>");
//	});
//	
//	$(settings).click(function(){
//		$(body_content).load(window.location.href + '<?php echo THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/navigate/settings.php"; ?>');
//		$(nav_info).html("<b>Einstellungen</b>");
//	});
//	
//	$(logout).click(function(){
//		$(body_content).load(window.location.href + '<?php echo THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/navigate/logout.php"; ?>');
//		$(nav_info).html("<b>Auslogen</b>");
//	});
//	
//	$(login).click(function(){
//		$(body_content).load(window.location.href + '<?php echo THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/navigate/login.php"; ?>');
//		$(nav_info).html("<b>Einlogen</b>");
//	});
//	
//	
//	$(impressum).click(function(){
//		$(body_content).load(window.location.href + '<?php echo THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/navigate/impressum.php"; ?>');
//		$(nav_info).html("<b>Impressum</b>");
//	});
	console.log("end");
});