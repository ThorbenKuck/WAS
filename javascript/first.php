<?php header("Content-type: application/javascript"); ?>

<?php
if(SUCCES) {
	$first_path = THEMES_NAME . "/" . ACTIVE_THEME_NAME . "/index.php";
}
echo "var first_path = '{$first_path}';";
?>

function test() {
	return "erfolgreich";
}

document.addEventListener('DOMContentLoaded', function () {
  if (Notification.permission !== "granted")
    Notification.requestPermission();
});


$(document).ready(function(){
	
	var body_content = document.getElementById("body_content");
	var nav_info = document.getElementById("nav-info");
	$(body_content).load(window.location.href + first_path);
	$(nav_info).html("<b>Home</b>");
});



function notifyMe(body, title) {
  if (!Notification) {
    alert('Desktop notifications not available in your browser. Try another browser.'); 
    return;
  }

  if (Notification.permission !== "granted")
    Notification.requestPermission();
  else {
    var notification = new Notification(title, {
      icon: '',
      body: body,
    });

    notification.onclick = function () {
      window.focus();
    };

  }

}


