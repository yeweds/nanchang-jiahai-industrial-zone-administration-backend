/*
* Author:      Marco Kuiper (http://www.marcofolio.net/)
*/
// Speed of the automatic slideshow
var slideshowSpeed = 4000;//自动播放的时间间隔

// Variable to store the images we need to set as background
// which also includes some text and url's.
//在这里设置幻灯片内容===========================
var photos = [ /*{
		"title" : "招聘",
		"image" : "url("+ PUBLIC +"/Images/ad1.jpg?v3)",
		//"url" : APP+"/Ad/view/info_id/19", //幸福时光
		"url" : "http://nc.tengfang.net/lp-94", //招聘 http://topic.tengfang.net/zhuanti/zhaopin
		"firstline" : "Going on",
		"secondline" : "vacation?"
	}, {
		"title" : "棕榈湾",
		"image" : "url("+ PUBLIC +"/Images/ad3.jpg?v3)",
		"url" : APP+"/lp-104", //保利
		"firstline" : "Get out and be",
		"secondline" : "active"
	}, {
		"title" : "金色学府",
		"image" : "url("+ PUBLIC +"/Images/ad4.jpg?v3)",
		"url" : APP+"/General/ct_view/info_id/163", 
		"firstline" : "Take a fresh breath of",
		"secondline" : "nature"
	}, */{
		"title" : "Italian pizza",
		"image" : "url("+ PUBLIC +"/Images/ad2_ldhy.jpg?v3)",
		"url" : "http://topic.tengfang.net/zhuanti/haiyuxiangting/", //绿地海域
		"firstline" : "Enjoy some delicious",
		"secondline" : "food"
	},{
		"title" : "时间广场",
		"image" : "url("+ PUBLIC +"/Images/ad5.jpg?v4)",
		"url"	: APP+"/lp-192", //保利半山http://topic.tengfang.net/zhuanti/blymjs/index.html
		"firstline" : "Or still busy at",
		"secondline" : "work?"
	}
];


$(document).ready(function() {
	$("#adimg").bind("click",function(){
		location.href="http://nc.tengfang.net/lp-192";
	});
	// Backwards navigation
	$("#back").click(function() {
		stopAnimation();
		navigate("back");
		//alert("sdf");
	});
	
	// Forward navigation
	$("#next").click(function() {
		stopAnimation();
		navigate("next");
	});
	
	var interval;
	//在播放与暂停之间切换,点击第一次，暂停,第二次，播放
	$("#control").toggle(function(){
		stopAnimation();
	}, function() {
		// Change the background image to "pause"
		$(this).removeClass('control_play').addClass('control_pause');//改为暂停按钮
		//alert('ssdf');
		// Show the next image
		//navigate("next");
		
		// Start playing the animation
		interval = setInterval(function() {
			navigate("next");
		}, slideshowSpeed);
	});
	
	
	//var activeContainer = 1;
	var currentImg = 0;//当前图像
	var animating = false;//判断是否暂停
	var navigate = function(direction) {
			$("#adimg").unbind("click");
		// Check if no animation is running. If it is, prevent the action
		if(animating) {
			return;
		}
		
		// Check which current image we need to show
		if(direction == "next") {
			currentImg++;
			if(currentImg == photos.length + 1) {
				currentImg = 1;
			}
		} else {
			currentImg--;
			if(currentImg == 0) {
				currentImg = photos.length;
			}
		}
		
		/* Check which container we need to use
		var currentContainer = activeContainer;
		if(activeContainer == 1) {
			activeContainer = 2;
		} else {
			activeContainer = 1;
		}*/
		
		showImage(photos[currentImg - 1]);
		
		
	};
		//alert(photos[currentImg].image);	
	//var currentZindex = -1;
	var showImage = function(photoObject) {
		animating = true;
		
		//1.先是渐隐图层
		$("#adimg").fadeTo(200,0.5,function(){
			
			//2.改变图层背景
			$("#adimg").css(
				"background-image",photoObject.image
			);	
			
		    //3.最后再渐入显示图层
			$("#adimg").fadeTo(200,1);							
	
		});
		//alert("url(../Images/" + photoObject.image + ")");
		

		// Make sure the new container is always on the background
		//currentZindex--;
		//alert(photoObject.image);	
		// Set the background image of the new active container

		$("#adimg").bind("click", function(){
			location.href=photoObject.url;  //暂去
		});
		//alert(photoObject.url);
		// Hide the header text
		//$("#headertxt").css({"display" : "none"});
		
		// Set the new header text
		//$("#firstline").html(photoObject.firstline);
		//$("#secondline")
			//.attr("href", photoObject.url)
			//.html(photoObject.secondline);
		//$("#pictureduri")
			//.attr("href", photoObject.url)
			//.html(photoObject.title);
		
		
		// Fade out the current container
		// and display the header text when animation is complete
		//$("#adimg" + currentContainer).fadeOut(function() {
			//setTimeout(function() {
			//	$("#headertxt").css({"display" : "block"});
			//animating = false;
			//}, 500);
		//});
		setTimeout(function() {
		  animating =  false;
		},500);
		
	};
	
	//停止播放动画
	var stopAnimation = function() {
		// Change the background image to "play"
		$('#control').removeClass('control_pause').addClass('control_play');// 改为播放按钮
		//alert('sdfsdfsdfsdf');
		
		// Clear the interval
		clearInterval(interval);
	};
	
	// We should statically set the first image
	//navigate("next");
	
	// Start playing the animation周期性的演示动画
	var tt=setTimeout(function(){
	interval = setInterval(function() {
		navigate("next");
	}, slideshowSpeed);
	
	},loadAdTime);
	
});