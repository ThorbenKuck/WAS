var dragEle = null;
var effectedEle = null;

var eleX = 0;
var eleY = 0;

var mouseX = 0;
var mouseY = 0;

document.onmousemove = move;
document.onmouseup = dragStop;

function dragStart(element, element2) {
	
	dragEle = element;
	effectedEle = element2;
	eleX = mouseX - dragEle.offsetLeft;
	eleY = mouseY - dragEle.offsetTop;

}

function dragStop() {
	if(dragEle != null) {
		effectedEle.style.cursor = "pointer";
		dragEle = null;
	}

}

function move(dragEvent) {

	mouseX = document.all ? window.event.clientX : dragEvent.pageX;
	mouseY = document.all ? window.event.clientY : dragEvent.pageY;

	if (dragEle != null) {
		
		effectedEle.style.cursor = "move";
		dragEle.style.left = (mouseX - eleX) + "px";
		dragEle.style.top = (mouseY - eleY) + "px";
		window.getSelection().removeAllRanges();
	}

}