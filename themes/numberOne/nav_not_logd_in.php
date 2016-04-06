<?php

?>

<nav>
	<div class="menu-item alpha" id="toHome">
		<h4>
			<a><span style="font-size: 1em;" aria-hidden="true" data-icon="&#xe069;" data-js-prompt="&amp;#xe069;"></span> Home</a>
		</h4>
		<p>Sie befinden sich in:</p>
		<p id="nav-info"></p>
	</div>
	<div class="menu-item" id="login">
		<h4>
			<a><span style="font-size: 1em;" aria-hidden="true" data-icon="&#xe066;" data-js-prompt="&amp;#xe066;"></span> Login</a>
		</h4>
	</div>
	<div class="menu-item" id="now">
		<h4>
			<a><span style="font-size: 1em;" aria-hidden="true" data-icon="&#xe05e;" data-js-prompt="&amp;#xe05e;"></span> Monitorzustand</a>
		</h4>
	</div>
	<div class="menu-item" id="help">
		<h4>
			<a><span style="font-size: 1em;" aria-hidden="true" data-icon="&#xe05d;" data-js-prompt="&amp;#xe05d;"></span> Hilfe</a>
		</h4>
	</div>
	<div class="menu-item" id="impressum">
		<h4>
			<a><span style="font-size: 1em;" aria-hidden="true" data-icon="&#xe08b;" data-js-prompt="&amp;#xe08b;"></span> Impressum</a>
		</h4>
	</div>
</nav>







<script>

<?php

$home_nav = THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/index.php";
$login_nav = THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/user/login.php";
$stand_nav = THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/current/stand.php";
$help_nav = THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/user/help.php";
$impressum_nav = THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/impressum/impressum.php";
echo "var home_nav = '{$home_nav}';";
echo "var login_nav = '{$login_nav}';";
echo "var now_nav = '{$stand_nav}';";
echo "var help_nav = '{$help_nav}';";
echo "var impressum_nav = '{$impressum_nav}';";

?>



$(document).ready(function(){
	var now			= document.getElementById( "now" );
	var help		= document.getElementById( "help" );
	var impressum	= document.getElementById( "impressum" );
	var home 		= document.getElementById( "toHome" );
	var login		= document.getElementById( "login" );
	
	var body_content = document.getElementById("body_content");
	var nav_info = document.getElementById("nav-info");
	console.log("init_nav_logic_not_logdin");

	$(home).click(function() {
		$(body_content).load(window.location.href + home_nav);
		$(nav_info).html("<b>Home</b>");
	});

	$(now).click(function() {
		$(body_content).load(window.location.href + now_nav);
		$(nav_info).html("<b>Monitorzustand</b>");
	});

	$(login).click(function() {
		$(body_content).load(window.location.href + login_nav);
		$(nav_info).html("<b>Einloggen</b>");
	});

	$(impressum).click(function() {
		$(body_content).load(window.location.href + impressum_nav);
		$(nav_info).html("<b>Impressum</b>");
	});

	$(help).click(function() {
		$(body_content).load(window.location.href + help_nav);
		$(nav_info).html("<b>Hilfe</b>");
	});
	
	
	console.log("end_nav_logic_not_logdin");

});

</script>
