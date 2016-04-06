<?php

?>
<nav>
	<div class="menu-item alpha" id="toHome">
		<h4>
			<a><span style="font-size: 1em;" aria-hidden="true"
				data-icon="&#xe069;" data-js-prompt="&amp;#xe069;"></span> Home</a>
		</h4>
		<p>Sie befinden sich in:</p>
		<p id="nav-info"></p>
	</div>

	<div class="menu-item">
		<h4>
			<a><span style="font-size: 1em;" aria-hidden="true"
				data-icon="&#xe060;" data-js-prompt="&amp;#xe060;"></span> Neu</a>
		</h4>
		<ul>
			<li id="new"><a>Termin Erstellen</a></li>
			<li id="upload"><a>CSV Hochladen</a></li>
			<li id="calc"><a>Errechnete Tage einfügen</a></li>
		</ul>
	</div>

	<div class="menu-item">
		<h4>
			<a><span style="font-size: 1emTHEMES_NAME;" aria-hidden="true"
				data-icon="&#xe01c;" data-js-prompt="&amp;#xe01c;"></span> Löschen</a>
		</h4>
		<ul>
			<li id="delete"><a>Einen Termin</a></li>
			<li id="reset"><a>Alle Termine</a></li>
		</ul>
	</div>

	<div class="menu-item" id="download">
		<h4>
			<a><span style="font-size: 1em;" aria-hidden="true"
				data-icon="&#xe083;" data-js-prompt="&amp;#xe083;"></span> CSV
				Herunterladen</a>
		</h4>
	</div>
	<div class="menu-item" id="admin">
		<h4>
			<a><span style="font-size: 1em;" aria-hidden="true"
				data-icon="&#xe08c;" data-js-prompt="&amp;#xe08c;"></span>
				Admin-Zugriff</a>
		</h4>
	</div>
	<div class="menu-item" id="now">
		<h4>
			<a><span style="font-size: 1em;" aria-hidden="true"
				data-icon="&#xe05e;" data-js-prompt="&amp;#xe05e;"></span>
				Monitorzustand</a>
		</h4>
	</div>
	<div class="menu-item" id="help">
		<h4>
			<a><span style="font-size: 1em;" aria-hidden="true"
				data-icon="&#xe05d;" data-js-prompt="&amp;#xe05d;"></span> Hilfe</a>
		</h4>

	</div>
	<div class="menu-item" id="settings">
		<h4>
			<a><span style="font-size: 1em;" aria-hidden="true"
				data-icon="&#xe09a;" data-js-prompt="&amp;#xe09a;"></span>
				Einstellungen</a>
		</h4>
	</div>
	<div class="menu-item" id="logout">
		<h4>
			<a><span style="font-size: 1em;" aria-hidden="true"
				data-icon="&#xe065;" data-js-prompt="&amp;#xe065;"></span> Logout</a>
		</h4>
	</div>
	<div class="menu-item" id="impressum">
		<h4>
			<a><span style="font-size: 1em;" aria-hidden="true"
				data-icon="&#xe08b;" data-js-prompt="&amp;#xe08b;"></span> Impressum</a>
		</h4>
	</div>
</nav>




<script>

<?php

$home_nav = THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/index.php";
$new_date_nav = THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/newdate/new.php";
$upload_nav = THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/newdate/upload.php";
$delete_one = THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/delete/one.php";
$reset_nav = THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/delete/all.php";
$calc_nav = THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/newdate/calc.php";
$download_nav = THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/download/index.php";
$stand_nav = THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/current/stand.php";
$logout_nav = THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/user/logout.php";
$admin_nav = THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/user/admin.php";
$help_nav = THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/user/help.php";
$settings_nav = THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/user/settings.php";
$impressum_nav = THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/impressum/impressum.php";
$user_has_admin_privilege = Main\is_admin() || Main\is_root();
$to_vpm_users = "vpm_users.php";
echo "var dele_nav = '{$delete_one}';";
echo "var home_nav = '{$home_nav}';";
echo "var new_date_nav = '{$new_date_nav}';";
echo "var upload_nav = '{$upload_nav}';";
echo "var reset_nav = '{$reset_nav}';";
echo "var calc_nav = '{$calc_nav}';";
echo "var download_nav = '{$download_nav}';";
echo "var now_nav = '{$stand_nav}';";
echo "var logout_nav = '{$logout_nav}';";
echo "var admin_nav = '{$admin_nav}';";
echo "var help_nav = '{$help_nav}';";
echo "var settings_nav = '{$settings_nav}';";
echo "var impressum_nav = '{$impressum_nav}';";
echo "var user_has_admin_privileges = Boolean('{$user_has_admin_privilege}');";
echo "var to_vpm_users = '{$to_vpm_users}';";

?>



$(document).ready(function() {
	var newDate		= document.getElementById("new");
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
	var logout		= document.getElementById( "logout" );
	
	var body_content = document.getElementById("body_content");
	var nav_info = document.getElementById("nav-info");
	console.log("init_nav_logic");
	
	$(newDate).click(function() {
		$(body_content).load(window.location.href + new_date_nav);
		$(nav_info).html("<b>Neuen Termin erstellen</b>");
	});

	$(home).click(function() {
		$(body_content).load(window.location.href + home_nav);
		$(nav_info).html("<b>Home</b>");
	});

	$(upload).click(function() {
		$(body_content).load(window.location.href + upload_nav);
		$(nav_info).html("<b>Upload</b>");
	});

	$(dele).click(function() {
		$(body_content).load(window.location.href + dele_nav);
		$(nav_info).html("<b>Lösche einen Termin</b>");
	});

	$(reset).click(function() {
		$(body_content).load(window.location.href + reset_nav);
		$(nav_info).html("<b>Lösche alle Termine</b>");
	});

	$(calc).click(function() {
		$(body_content).load(window.location.href + calc_nav);
		$(nav_info).html("<b>Errechne Feiertage</b>");
	});

	$(download).click(function() {
		$(body_content).load(window.location.href + download_nav);
		$(nav_info).html("<b>Download CSV</b>");
	});

	$(now).click(function() {
		$(body_content).load(window.location.href + now_nav);
		$(nav_info).html("<b>Monitorzustand</b>");
	});

	$(logout).click(function() {
		$(body_content).load(window.location.href + logout_nav);
		$(nav_info).html("<b>Abmelden</b>");
	});
	
	$(admin).click(function() {
		if(!user_has_admin_privileges) {
			$(body_content).load(window.location.href + admin_nav);
			$(nav_info).html("<b>Administrativer Bereich</b>");
		} else {
			window.open(to_vpm_users,'_blank');
		}
	});

	$(help).click(function() {
		$(body_content).load(window.location.href + help_nav);
		$(nav_info).html("<b>Hilfe</b>");
	});

	$(settings).click(function() {
		$(body_content).load(window.location.href + settings_nav);
		$(nav_info).html("<b>Einstellungen</b>");
	});

	$(impressum).click(function() {
		$(body_content).load(window.location.href + impressum_nav);
		$(nav_info).html("<b>Impressum</b>");
	});
	
	
	console.log("init_nav_logic_end");

});

</script>
