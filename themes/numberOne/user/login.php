<?php

if (session_status () !== PHP_SESSION_ACTIVE)
	session_start ();

if(file_exists($_SESSION['autoload'])) {
	require_once($_SESSION['autoload']);
}

// var_dump($logic);
Main\return_new_dialogbox("", "280px", "300px" , "1000px", "1200px", "required/login.php", "");

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
var submit;
var index_table;

function test() {
	alert("das ist ein Test");
}

function update_table() {
	$(index_table).load(window.location.href + to_index_table);
}

$(document).ready(function(){
	submit = document.getElementById('submit');
	var username = document.getElementById('name');
	var password = document.getElementById('pass');
	index_table = document.getElementById('body_table');
	var dialog_echo = document.getElementById('dialogbox_echo');
	
	
	// Die Standardbelegung der Passwort und Namensfelder
	var default_text_name = "Nutzername";
	var default_text_pass = "Password";
	$(username).val(default_text_name);
	$(username).blur(function(){
	    if($(this).val()==""){
	        $(this).val(default_text_name);
	    }
	});
	$(username).focus(function(){
	    if(default_text_name === null){
	    	default_text_name = $(this).val();
	    }
	    if($(this).val() == default_text_name){
	        $(this).val("");
	    }
	});
	$(password).val(default_text_pass);
	$(password).blur(function(){
	    if($(this).val()==""){
	        $(this).val(default_text_pass);
	    }
	});
	$(password).focus(function(){
	    if(default_text_pass === null){
	    	default_text_pass = $(this).val();
	    }
	    if($(this).val() == default_text_pass){
	        $(this).val("");
	    }
	});




	// Der eventlistener auf submit
	$(submit).click(function(){
		var name = $('#name').val();
		var pass = $('#pass').val();
		var keeplogdin = $('#keeplogdin').val();
		if(name === default_text_name) {
			$('#dialogbox_echo').html('<br>Denk dran, <b>Deinen</b> Nutzernamen ein zu geben!');
			$('#name').focus();
			return false;
		} else if(name === "") {
			$('#dialogbox_echo').html('<br>Du musst einen Nutzernamen eingeben!');
			$('#name').focus();
			return false;
		}
		if(pass === default_text_pass) {
			$('#dialogbox_echo').html('<br>Denk dran, <b>Dein</b> Passwort ein zu geben!');
			$('#pass').focus();
			return false;
		}else if(pass === "") {
			$('#dialogbox_echo').html('<br>Denk dran, <b>Dein</b> Passwort ein zu geben!');
			$('#pass').focus();
			return false;
		}
		$('#dialogbox_echo').html('<br><b">Einen Moment geduld bitte!</b>');
		var output = new Array(	"login",
								name,
								pass,
								document.getElementById('keeplogdin').checked);
		
		$.post( 
			php_pass,
			{ output: output },
			function(data) {
				$('#dialogbox_echo').html(data);
			}
		);
		return false;
	});
});

</script>