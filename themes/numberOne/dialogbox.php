<?php

if(!isset($width) || $width == -1) { 
	$width = '200px'; 
}

if(!isset($height) || $height == -1) {
	$height = '200px'; 
}

if(!isset($max_width) || $max_width == -1) {
	$max_width = $width;
}

if(!isset($max_height) || $max_height == -1) {
	$max_height = $height;
}


if($security) {
	if(!Main\is_logdin())
		die("Sie müssen angemeldet sein, um diese Seite bearbeiten zu können!");
}
if($admin) {
	if(!Main\is_admin() && !Main\is_root())
		die("Ihnen fehlen die Rechte, um diese Seite auf zu rufen!");
}

if(isset($path_to_include)) {
	if(isset($themes)) {
		$path_to_include = $active_theme . $path_to_include;
	}else if(SUCCES === 1) {
		$path_to_include = ACTIVE_THEME . $path_to_include;
	} else if(isset($_SESSION['active_theme']) && $_SESSION['active_theme'] != ACTIVE_THEME) {
		$path_to_include = $_SESSION['active_theme'] . $path_to_include;
	} else {
		die("es gab einen großen Fehler!");
	}
} else {
	unset($path_to_include);
}

?>

<div class="resizable" id="dialogbox_container" style="width: <?php echo $width; ?>; height: <?php echo $height;?>; max-width: <?php echo $max_width; ?>; max-height: <?php echo $max_height; ?>;">
	<div id="top_draggable" class="ui-widget-content" onmousedown="dragStart(this.parentNode, this)">
		<div id="close_dialog">
			<span style="font-size: 15px; text-align: left; width:15px; height:15px; text-align:center;" aria-hidden="true" data-icon="&#xe082;" data-js-prompt="&amp;#xe082;"></span>
		</div>
	
	
	</div>
	<div id="dialogbox_inner">
		<div id="dialog_content">
			<br>
			<?php
			if(!empty($message1))
				echo $message1;
			if(isset($path_to_include)) {
				if(file_exists($path_to_include)) {
					include ($path_to_include);
				} else {
					echo "es gab einen Fehler..";
				}
			}
			else {
				echo "Deine Datei wurde nicht gefunden!";
			}
			if(!empty($message2))
				echo $message2;
			?>
		</div>
		<div id="dialogbox_echo">
		</div>
	</div>
</div>


<script>
<?php 
$to_index = THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/index.php";
echo "var php_index = '{$to_index}';";
?>

$(document).ready(function() {
	
	var x = document.getElementById('close_dialog');
	var body_content = document.getElementById("body_content");
	var nav_info = document.getElementById("nav-info");
	
	$(x).click(function() {
		$(body_content).load(window.location.href + php_index);
		$(nav_info).html("<b>Home</b>");
	});	
});
</script>
