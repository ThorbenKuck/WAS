<?php

if (session_status () !== PHP_SESSION_ACTIVE)
	session_start ();

if(file_exists($_SESSION['autoload'])) {
	require_once($_SESSION['autoload']);
}

// var_dump($logic);

Main\return_new_dialogbox("", "800px", "600px" , "1000px", "1200px", "required/delete_dates.php", "", true);


?>

<script>
<?php
$to_pass_new_vars = THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/required/pass_new_variables.php";
$to_index_table = THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/required/index_table.php";
$current_input = THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/required/delete_dates.php";
echo "var php_pass = '{$to_pass_new_vars}';";
echo "var to_index_table = '{$to_index_table}';";
echo "var current_input = '{$current_input}';";
?>
var delete_submit;
var index_table;
var dialog_content;

function update_table() {
	$(index_table).load(window.location.href + to_index_table);
	var body_content = document.getElementById("body_content");
	var nav_info = document.getElementById("nav-info");
	$(body_content).load(window.location.href + php_index);
	$(nav_info).html("<b>Home</b>");
}

$(document).ready(function() {
	delete_submit = document.getElementById('delete_submit');
	index_table = document.getElementById('body_table');
	dialog_content = document.getElementById('dialog_content');
	$(delete_submit).click(function() {
		var fields = $(":input:checkbox").serializeArray();
		var output = new Array("delete_one", fields);
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