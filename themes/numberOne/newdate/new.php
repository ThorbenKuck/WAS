<?php

if (session_status () !== PHP_SESSION_ACTIVE)
	session_start ();

if(file_exists($_SESSION['autoload'])) {
	require_once($_SESSION['autoload']);
}

// var_dump($logic);
Main\return_new_dialogbox("", "600px", "700px" , "1000px", "1200px", "required/newdate.php", "", true);

?>
<script>
<?php

  $to_index = THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/index.php";
  $to_pass_new_vars = THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/required/pass_new_variables.php";
  $to_index_table = THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/required/index_table.php";
  echo "var php_index = '{$to_index}';";
  echo "var php_pass = '{$to_pass_new_vars}';";
  echo "var to_index_table = '{$to_index_table}';";

?>
var abbort;
var submit;
var index_table;

function test() {
	alert("das ist ein Test");
}

function hide_old_input()  {
	$(abbort).hide();
	$(submit).hide();
}

function show_old_input() {
	$( abbort ).show( "slow", function() {
		// Animation complete.
	});
	$( submit ).show( "slow", function() {

	});
}

function update_table() {
	$(index_table).load(window.location.href + to_index_table);
}


$(document).ready(function(){
	submit = document.getElementById('submit');
	abbort = document.getElementById('abbort');
	index_table = document.getElementById('body_table');
	var dialog_echo = document.getElementById('dialogbox_echo');

	
	$(submit).click(function(){
		//Der Veranstalltungsname
		var name = $('#veranstalltungs_name').val();

		//Das Start-Datum
		var start_tag = $('#start_tag').val();
		if(start_tag.length < 2) {
			start_tag = '0' + start_tag;
		}
		var start_monat = $('#start_monat').val();
		if(start_monat.length < 2) {
			start_monat = '0' + start_monat;
		}
		var start_jahr = $('#start_jahr').val();
		var start_stunde = $('#start_stunde').val();
		if(start_stunde.length < 2) {
			start_stunde = '0' + start_stunde;
		}
		var start_minute = $('#start_minute').val();
		if(start_minute.length < 2) {
			start_minute = '0' + start_minute;
		}

		//Das End-Datum
		var end_tag = $('#end_tag').val();
		if(end_tag.length < 2) {
			end_tag = '0' + end_tag;
		}
		var end_monat = $('#end_monat').val();
		if(end_monat.length < 2) {
			end_monat = '0' + end_monat;
		}
		var end_jahr = $('#end_jahr').val();
		var end_stunde = $('#end_stunde').val();
		if(end_stunde.length < 2) {
			end_stunde = '0' + end_stunde;
		}
		var end_minute = $('#end_minute').val();
		if(end_minute.length < 2) {
			end_minute = '0' + end_minute;
		}

		//Der Monitorzustand
		var monitor_zustand = $('#monitor_zustand').val();

		//Check Zustände
		var start_check = start_jahr + start_monat + start_tag + start_stunde + start_minute;
		var end_check = end_jahr + end_monat + end_tag + end_stunde + end_minute;
		
		//leerer Name
		if(name == "") {
			$('#dialogbox_echo').html('<br><b style="color:red;">Bitte gebe einen Namen für die Veranstalltung ein!</b>');
					
			$('#veranstalltungs_name').focus();
			return false;
		}
		//Enddatum vor Startdatum
		if(end_check < start_check) {
			$('#dialogbox_echo').html('<br><b style="color:red;">Das End-Datum liegt vor dem Start-Datum!</b>');
			$('#end_tag').focus();
			return false;
		}
		$('#dialogbox_echo').html('<br><b style="color:green;">Einen Moment geduld bitte!</b>');
		var output = new Array(	"new_date",
								name,
								start_tag,
								start_monat,
								start_jahr,
								start_stunde,
								start_minute,
								end_tag,
								end_monat,
								end_jahr,
								end_stunde,
								end_minute,
								monitor_zustand);
		
		$.post( 
			php_pass,
			{ output: output },
			function(data) {
				$('#dialogbox_echo').html(data);
			}
		);
		update_table();
		return false;
	});

	$(abbort).click(function() {
		var body_content = document.getElementById("body_content");
		var nav_info = document.getElementById("nav-info");
		$(body_content).load(window.location.href + php_index);
		$(nav_info).html("<b>Home</b>");
		return false;
	});
});

</script>