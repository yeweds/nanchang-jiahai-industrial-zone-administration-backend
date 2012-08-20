function initEcAd() {
document.all.AdLayer1.style.posTop = -200;
document.all.AdLayer1.style.visibility = 'visible'
document.all.AdLayer2.style.posTop = -200;
document.all.AdLayer2.style.visibility = 'visible'
MoveLeftLayer('AdLayer1');
MoveRightLayer('AdLayer2');
}
function MoveLeftLayer(layerName) {
var x = 5;
var y = 100;// 左侧广告距离页首高度

var scroll_x;
	  if(document.documentElement.scrollTop) {
		  scroll_x   =   document.documentElement.scrollTop;

	  }else {
		  scroll_x   =   document.body.scrollTop;
	  }

var diff = (scroll_x + y - document.all.AdLayer1.style.posTop)*.40;
var y = scroll_x + y - diff;
eval("document.all." + layerName + ".style.posTop = parseInt(y)");
eval("document.all." + layerName + ".style.posLeft = x");
setTimeout("MoveLeftLayer('AdLayer1');", 20);
}
function MoveRightLayer(layerName) {
var x = 5;
var y = 100;// 右侧广告距离页首高度

var scroll_x;
	  if(document.documentElement.scrollTop) {
		  scroll_x   =   document.documentElement.scrollTop;

	  }else {
		  scroll_x   =   document.body.scrollTop;
	  }

var diff = (scroll_x + y - document.all.AdLayer2.style.posTop)*.40;
var y = scroll_x + y - diff;
eval("document.all." + layerName + ".style.posTop = y");
eval("document.all." + layerName + ".style.posRight = x");
setTimeout("MoveRightLayer('AdLayer2');", 20);
}

document.write("<div id='AdLayer1' style='position: absolute;visibility:hidden;z-index:1'><object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0\" width=\"114\" height=\"240\"><param name=\"movie\" value=\"http://img.tengfang.net/ad/flash/yx0115_ad_left.swf\" /><param name=\"quality\" value=\"high\" /><embed src=\"http://img.tengfang.net/ad/flash/yx0115_ad_left.swf\" quality=\"high\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\" width=\"114\" height=\"240\"></embed></object></div>"
+"<div id='AdLayer2' style='position: absolute;visibility:hidden;z-index:1'><object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0\" width=\"114\" height=\"240\"><param name=\"movie\" value=\"http://img.tengfang.net/ad/flash/yx0115_ad_right.swf\" /><param name=\"quality\" value=\"high\" /><embed src=\"http://img.tengfang.net/ad/flash/yx0115_ad_right.swf\" quality=\"high\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\" width=\"114\" height=\"240\"></embed></object></div>");
initEcAd()

// JavaScript Document
document.writeln("");