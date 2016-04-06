function close_warning() {
	var warning = document.getElementById("warning");
	var warning_button = document.getElementById("warning_button");
	warning_button.parentNode.removeChild(warning_button);
	warning.style.webkitFilter = "blur(0px)";
	warning.style.height = "0px";
}