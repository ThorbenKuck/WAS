<?php

if (session_status () !== PHP_SESSION_ACTIVE)
	session_start ();

if(file_exists($_SESSION['autoload'])) {
	require_once($_SESSION['autoload']);
}

// var_dump($logic);
Main\return_new_dialogbox("", "600px", "400px" , "1000px", "1200px", "required/calc_dates.php", "", true);


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

function update_table() {
	$(index_table).load(window.location.href + to_index_table);
}

$(document).ready(function(){
	submit = document.getElementById('submit');
	index_table = document.getElementById('body_table');
	var dialog_echo = document.getElementById('dialogbox_echo');
	
	$(submit).click(function(){
		//Der Veranstalltungsname
		var year = $('#year').val();
		console.log(year);
		var output = new Array(	"calc",
								year);
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