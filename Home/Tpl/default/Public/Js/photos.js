//加载相册效果

function loadPhoto(url,num,title,type){
	  var type=parseInt(type);
	  //alert(type);
		if(isNaN(type)){
			  
			  type=0;
		  }	  
	  
	
		if(parseInt(more_photo[type]['total'])==0){
			
			msgBox('当前相册暂无图片,将进入一相册','0','友情提示');
			type=type+1;
			if(type>(more_photo.length-1)){
				type=0;
			}
			//alert(mo
			//alert('1'+(parseInt(more_photo[type]['total'])));
			//alert('2'+type);
			//alert('sdf'+type);
			//alert('1'+more_photo[type]['type']);
			var tt=setTimeout(function(){loadPhoto(more_photo[type]['url'],1,more_photo[type]['name']+more_photo[type]['typename'],more_photo[type]['type']);},2000);
			
			return;
			//return;
		}
	//alert(type);
	
		var moreHtml='<span style="background-image:url('+WEBROOT+'/Home/Tpl/default/Public/Images/photos_ico.png);background-repeat: repeat-x;text-align:center;width:70px;height30px;	display:inline-block;color:white">&nbsp;</span>';
				
		for(i in more_photo){
			//alert('sdf');
			if(type==more_photo[i]['type']){
				
				moreHtml=moreHtml+'<span style="background-image:url('+WEBROOT+'/Home/Tpl/default/Public/Images/photo_bnd_bg.png);background-repeat: repeat-x;text-align:center;width:70px;height30px;	border-top-width: 1px;	border-right-width: 1px;	border-bottom-width: 1px;	border-left-width: 1px;	border-top-style: solid;	border-right-style: solid;	border-bottom-style: solid;	border-left-style: solid;	border-top-color: #d3d3d3;	border-right-color: #f0f0f0;	border-bottom-color: #d3d3d3;	border-left-color: #d3d3d3;display:inline-block;color:white"><b>'+more_photo[i]['typename']+'('+more_photo[i]['total']+')</b></span>';
			}else{
				moreHtml=moreHtml+'<span onmousemove="$(this).css({\'background-image\':\'url('+WEBROOT+'/Home/Tpl/default/Public/Images/photo_bnm_bg.png)\',\'color\':\'white\'})" onmouseout="$(this).css({\'background-image\':\'url('+WEBROOT+'/Home/Tpl/default/Public/Images/photo_bn_bg.png)\',\'color\':\'white\'})" style="cursor:pointer;background-image:url('+WEBROOT+'/Home/Tpl/default/Public/Images/photo_bn_bg.png);background-repeat: repeat-x;text-align:center;width:70px;height:30px;	border-top-width: 1px;	border-right-width: 1px;	border-bottom-width: 1px;	border-left-width: 1px;	border-top-style: solid;	border-right-style: solid;	border-bottom-style: solid;	border-left-style: solid;	border-top-color: #706e6e;	border-right-color: #4f4f4f;border-bottom-color: #706e6e;	border-left-color:#706e6e;display:inline-block;color: white;" onclick="loadPhoto(\''+more_photo[i]['url']+'\',1,\''+more_photo[i]['name']+more_photo[i]['typename']+'\','+more_photo[i]['type']+');return false">'+more_photo[i]['typename']+'('+more_photo[i]['total']+')</span>';
				
			}
		}
	
	
	
	
	//alert(num+more_photo[0]['url']);
/*		var more1={type:1,data:[{\'name\':\'东湖\',\'typename\':\'实景图\',\'type\':0,\'url\':APP+\'/Attach/loadPhoto/num/1\',\'total\':5},{\'name\':\'东湖\',\'typename\':\'效果图\',\'type\':0,\'url\':APP+\'/Attach/loadPhoto/num/2\',\'total\':5},{\'name\':\'东湖\',\'typename\':\'户型图\',\'type\':0,\'url\':APP+\'/Attach/loadPhoto/num/3\',\'total\':5},{\'name\':\'东湖\',\'typename\':\'交通图\',\'type\':0,\'url\':APP+\'/Attach/loadPhoto/num/4\',\'total\':5}]};*/
	//格式化more内容
	//当前的类型图
/*	alert(url);
	var type=more.type;
	//var data=more.data;
	//more.type=2;
	//alert(data[0]['name']);
	var moreHtml='';
	for(i in more.data){
			//alert('sdf');
			if(more.type==more.data[i]['type']){
				
				moreHtml=moreHtml+'<span class="gray_a">'+more.data[i]['typename']+'('+more.data[i]['total']+')</span>&nbsp;&nbsp;&nbsp;';
			}else{
				moreHtml=moreHtml+'<span class="green_a"><a href="#" onclick="loadPhoto(\''+more.data[i]['url']+'\',3,\''+more.data[i]['name']+more.data[i]['typename']+'\',{type:1,data:[{\'name\':\'东湖\',\'typename\':\'实景图\',\'type\':0,\'url\':\''+APP+'/Attach/loadPhoto/num/\',\'total\':4},{\'name\':\'东湖\',\'typename\':\'效果图\',\'type\':0,\'url\':\''+APP+'/Attach/loadPhoto/num/\',\'total\':4},{\'name\':\'东湖\',\'typename\':\'户型图\',\'type\':0,\'url\':\''+APP+'/Attach/loadPhoto/num/\',\'total\':4},{\'name\':\'东湖\',\'typename\':\'交通图\',\'type\':0,\'url\':\''+APP+'/Attach/loadPhoto/num/\',\'total\':4}]});return false">'+more.data[i]['typename']+'</a>('+more.data[i]['total']+')</span>&nbsp;&nbsp;&nbsp;';
				
			}
		
		
		
	}*/

	//需要的东西: 1.图片容器 2.关闭按钮，显示总张数，当前第几张 3.下一页，上一页按钮 4.加载源url决定
	//图像容器id=load_photo
	//a.检查是否存在图层,如果已存在则不必再创建-----------------------
	var id="load_photo";
	var fgId="load_overlayer"
	

			//默认高度与宽度
			var width=400;
			var height=300;
		
			//先获取浏览器窗口信息
			//a.1.检查当前文档的高度与宽度
			var browse_width=$(window).width()	
			var browse_height=$(window).height()	
			
			//a.2.检查当前文档滚动条位置
			var browse_scrollTop=$(window).scrollTop();	
				
			//a.3 .显示图片容器
			var right=(browse_width-width)/2;
			var top=(browse_height-height)/2+browse_scrollTop;
			
		//如果不存在图层	
	      if($('#'+id).css('width')==undefined){			
		  
					//b.1.先写入一个覆盖层
					var html='<div id="'+fgId+'" style="position: absolute;top:0px;right:0px;width:'+$(document).width()+'px;height:'+$(document).height()+'px;background-color:black;z-index:1000001;"></div>';
				   $(html).appendTo('body');	
				   $('#load_overlayer').css({'opacity':'0'});	
				   $('#load_overlayer').animate({opacity:0.5},200);		
				   
					$('#'+fgId).click(function(){
						loadPhotoClose(id,fgId);
						
					});

				//b.2创建容器图层3f3f3f;#999999
				var html='<div id="'+id+'" style="position: absolute;top:'+top+'px;right:'+right+'px;width:'+width+'px;height:'+height+'px;background-color:#999999;color:#dbdbdb;border:8px solid #dfdfdf;font-size: 12px;font-family:Arial, Helvetica, sans-serif;z-index:1000002;"></div>';
				//创建一个dom模型容器
			   $(html).appendTo('body');	
			   
			   
			   //显示加载样式
				//显示加载动画
				$('#'+id).html('<div class="load_photo_class" style="position: relative;width:'+width+'px;height:'+height+'px;background-color:#999999;"><div  class="close" style="position: absolute;height:40px;top:0px;right:0px;font-size:30px;cursor:pointer;" ><img onclick="loadPhotoClose(\''+id+'\',\''+fgId+'\')" title="关闭" src="'+WEBROOT+'/Home/Tpl/default/Public/Images/photo_close.gif" onmouseover="this.src=\''+WEBROOT+'/Home/Tpl/default/Public/Images/photo_close_m.gif\'" onmouseout="this.src=\''+WEBROOT+'/Home/Tpl/default/Public/Images/photo_close.gif\'"  /></div><div align="center"><br /><br /><br /><br /><br /><br /><br /><br /><img src="'+WEBROOT+'/Home/Tpl/default/Public/Images/ajaxload.gif" /><br  /><br  />正在加载,请稍候...</div></div>');			   
							   					
					
		//如果存在图层			
		  }else{
			isSlowLoad=1;  
		 // isSlowLoadPhoto(id,fgId)
			 var t=setTimeout(function(){isSlowLoadPhoto(id,fgId)},500);
			  
		  }
	//--------end-------------检查是否打开图层


	//2.ajax加载图片入容器
$.get(url+num,function(msg){
		isSlowLoad=0;
		total=msg['total'];
		//防止数字超过范围---
		if(num<1){
			loadPhoto(url,1,title);
			return;
		};
		if(num>total){
			loadPhoto(url,total,title);
			return;
		};	
		//--------------------	
	
		//防止没有数据,
		if(msg==null){
			$('#'+id).remove()	
		    $('#'+fgId).remove();
			return;	
		}
		
		$('#'+id).empty()	
		//var moreHtml=msg['more_photo'];
		
		
		var htmlS='<div class="left" style="position: absolute;height:30px;top:30px;left:10px;font-size:14px;cursor:pointer;"></div><div class="right" style="position: absolute;height:30px;top:30px;right:10px;font-size:14px;cursor:pointer;"></div>';				
		var htmlC='<div  class="page" style="position: absolute;height:30px;bottom:35px;right:0px;font-size:30px;cursor:pointer;" ></div><div  class="close" style="position: absolute;height:40px;top:0px;right:0px;font-size:30px;cursor:pointer;" ><img onclick="loadPhotoClose(\''+id+'\',\''+fgId+'\')" title="关闭" src="'+WEBROOT+'/Home/Tpl/default/Public/Images/photo_close.gif" onmouseover="this.src=\''+WEBROOT+'/Home/Tpl/default/Public/Images/photo_close_m.gif\'" onmouseout="this.src=\''+WEBROOT+'/Home/Tpl/default/Public/Images/photo_close.gif\'"  /></div>'
		
	//background-color:#dfdfdf;
		var html='<div class="load_photo_class" style="position: relative;width:300px;height:200px;"><div style="width:100%;"  align="center"><img class="img" src="'+msg['img']+'" onload="loadPhotoAnimate(\''+id+'\',$(this),'+num+','+msg['total']+',\''+url+'\',\''+title+'\','+type+')"/></div><div class="title" style="heigth:30px;width:100%;line-height:30px; color:#9f9f9f">&nbsp;&nbsp;'+title+'&nbsp;&nbsp; <span class="gray_14px_f"><font color="white">'+num+'</font>/'+msg['total']+'</span>&nbsp;PHOTOS </div><div class="title" style="heigth:30px;width:100%;line-height:30px; padding:0px;padding-top:5px;background-color:#dfdfdf;background-image:url('+WEBROOT+'/Home/Tpl/default/Public/Images/photo_play_bg.png);background-repeat: no-repeat;background-position: right top;color:#9f9f9f">'+moreHtml+'</div>'+htmlS+htmlC+'</div>';
		//关闭按钮及其它
		$(html).appendTo('#'+id);	
		//alert($(	'#'+id).css('width'));
			$('#'+id+' .right').css({'opacity':'0'});
			$('#'+id+' .left').css({'opacity':'0'});
			$('#'+id+' .close').css({'opacity':'0'});
			$('#'+id+' .title').css({'opacity':'0'});
			$('#'+id+' .page').css({'opacity':'0'});
			$('#'+id+' .img').css({'opacity':'0'});
			//$('#'+id+' .load_photo_class').css('background-color','#3f3f3f');
			//$('#'+id+' .load_photo_class').css({'opacity':'0.5'});
		

	},'json');

	//3.根据图片大小调整容器位置

}

var isSlowLoad=1;
//检测是否存在加载较慢
function isSlowLoadPhoto(id,fgId){
//alert(isSlowLoad);
	if(isSlowLoad==1){
		 //    var id='load_photo';

			  //清除不必要的内容
			  $('#'+id+' .load_photo_class').empty();
			  
			  //获取当前图像的高度与宽度
			  var widthId=$('#'+id).width();	  
			  
			  var heightId=$('#'+id).height();
			  
			  var loadImgW=(widthId-100)/2;
			  var loadImgH=(heightId-60)/2;
			  
			  //写入加载图像
				//显示加载动画
				$('#'+id).html('<div  class="close" style="position: absolute;height:40px;top:0px;right:0px;font-size:30px;cursor:pointer;" ><img onclick="loadPhotoClose(\''+id+'\',\''+fgId+'\')" title="关闭" src="'+WEBROOT+'/Home/Tpl/default/Public/Images/photo_close.gif" onmouseover="this.src=\''+WEBROOT+'/Home/Tpl/default/Public/Images/photo_close_m.gif\'" onmouseout="this.src=\''+WEBROOT+'/Home/Tpl/default/Public/Images/photo_close.gif\'"  /></div><div align="center" style="position: absolute;height:60px;width:100px;top:'+loadImgH+'px;left:'+loadImgW+'px;"><img src="'+WEBROOT+'/Home/Tpl/default/Public/Images/ajaxload.gif" /><br  /><br  />正在加载,请稍候...</div>');			
		
		
	}else{
		isSlowLoad=1
	}
	
}
//加载相册效果
function loadPhotoAnimate(id,thisId,num,total,url,title,type){
	
	//alert(url);
	var width=thisId.width();
	var height=thisId.height()+65;
	
		//类型总数
		var typeTotal=more_photo.length;		
		  if(width<(75*(typeTotal+1))){
			    width =75*(typeTotal+1);
			  
	  }	
	
	//先获取浏览器窗口信息
	//a.1.检查当前文档的高度与宽度
	var browse_width=$(window).width()	
	var browse_height=$(window).height()	
	
	//a.2.检查当前文档滚动条位置
	var browse_scrollTop=$(window).scrollTop();	
		
	//a.3 .显示图片容器
	var right=(browse_width-width)/2;
	var top=(browse_height-height)/2+browse_scrollTop;	
	if(top<browse_scrollTop){
		
		top=browse_height+browse_scrollTop-height-10;
		if(top<0){
			top=0;
		}
	}
	

	if(width>0){
		

		  

	$('#'+id+' .load_photo_class').css({'width':width+'px','height':height+'px'});
	$('#'+id).animate({width:width,height:height,right:right,top:top},300,function(){

			$('#'+id+' .right').css({'opacity':'1'});
			$('#'+id+' .left').css({'opacity':'1'});
			$('#'+id+' .close').css({'opacity':'1'});
			$('#'+id+' .title').css({'opacity':'1'});
			$('#'+id+' .page').css({'opacity':'1'});
			$('#'+id+' .img').css({'opacity':'1'});
			$('#'+id+' .img').animate({opacity:1},1500);
			$('#'+id+' .load_photo_class').css('background-color','#3f3f3f');		
	
    });


	$('#'+id+' .right').css({'top':((height-30-30)/2)+'px'});

	
	var pageHtml='';
	if(num!=1){
		var pageHtml='<img onclick="loadPhoto(\''+url+'\',1,\''+title+'\','+type+')" title="第一张" src="'+WEBROOT+'/Home/Tpl/default/Public/Images/photo_left.gif" onmouseover="this.src=\''+WEBROOT+'/Home/Tpl/default/Public/Images/photo_left_m.gif\'" onmouseout="this.src=\''+WEBROOT+'/Home/Tpl/default/Public/Images/photo_left.gif\'"  />'
		var pageHtml=pageHtml+'<img onclick="loadPhoto(\''+url+'\','+(num-1)+',\''+title+'\','+type+')" title="上一张" src="'+WEBROOT+'/Home/Tpl/default/Public/Images/photo_l.gif" onmouseover="this.src=\''+WEBROOT+'/Home/Tpl/default/Public/Images/photo_l_m.gif\'" onmouseout="this.src=\''+WEBROOT+'/Home/Tpl/default/Public/Images/photo_l.gif\'"  />'	;	
		
	}
   if(num!=total){
		pageHtml=pageHtml+'<img  onclick="loadPhoto(\''+url+'\','+(num+1)+',\''+title+'\','+type+')" title="下一张" src="'+WEBROOT+'/Home/Tpl/default/Public/Images/photo_n.gif" onmouseover="this.src=\''+WEBROOT+'/Home/Tpl/default/Public/Images/photo_n_m.gif\'" onmouseout="this.src=\''+WEBROOT+'/Home/Tpl/default/Public/Images/photo_n.gif\'"/>'
		pageHtml=pageHtml+'<img onclick="loadPhoto(\''+url+'\','+total+',\''+title+'\','+type+')" title="最后一张" src="'+WEBROOT+'/Home/Tpl/default/Public/Images/photo_right.gif" onmouseover="this.src=\''+WEBROOT+'/Home/Tpl/default/Public/Images/photo_right_m.gif\'" onmouseout="this.src=\''+WEBROOT+'/Home/Tpl/default/Public/Images/photo_right.gif\'" />'		
		
	}
	$(pageHtml).prependTo('#'+id+' .page');
	
	if(num!=total){
			//}
			$('#'+id+' .right').mousedown(function(){
						loadPhoto(url,(num+1),title,type);								
			});	
	}else{
		if(type>=(typeTotal-1)){
		
				$('#'+id+' .right').html('<img  title="最后一张" style="cursor:none" src="'+WEBROOT+'/Home/Tpl/default/Public/Images/end_pic.gif" />');
		}else{
				$('#'+id+' .right').html('<img  title="查看下一个相册" onclick="loadPhoto(\''+more_photo[type+1]['url']+'\',1,\''+more_photo[type+1]['name']+more_photo[type+1]['typename']+'\','+(type+1)+')"  style="cursor:pointer" src="'+WEBROOT+'/Home/Tpl/default/Public/Images/next_photo.gif" />');
			
		}
		$('#'+id+' .right').mouseover(function(){
			   thisId.css("cursor","");					
			    $('#'+id+' .right').css({'opacity':'1'});
						
			});			
			
			
	}
		

	$('#'+id+' .left').css({'top':((height-30-30)/2)+'px'});	

			  
	if(num!=1){
			$('#'+id+' .left').mousedown(function(){
						loadPhoto(url,(num-1),title,type);								
			});		
	}else{
		
		if(type==0){
		
					$('#'+id+' .left').html('<img  title="第一张" style="cursor:none"  src="'+WEBROOT+'/Home/Tpl/default/Public/Images/start_pic.gif" />');
		}else{
			//loadPhoto(more_photo[type]['url'],1,more_photo[type]['name']+more_photo[type]['typename'],more_photo[type]['type']);
				$('#'+id+' .left').html('<img  title="查看上一个相册" onclick="loadPhoto(\''+more_photo[type-1]['url']+'\',1,\''+more_photo[type-1]['name']+more_photo[type-1]['typename']+'\','+(type-1)+')"  style="cursor:pointer" src="'+WEBROOT+'/Home/Tpl/default/Public/Images/last_photo.gif" />');
			
		}		
		

		$('#'+id+' .left').mouseover(function(){
			   thisId.css("cursor","");					
			    $('#'+id+' .left').css({'opacity':'1'});
						
			});	
	}
	//移动显示
	thisId.mousemove(function(event){
		var x=event.pageX;
		var y=event.pageY;
		//分界线
		if(x<(browse_width/2)){
			if(num!=1){
			   thisId.css("cursor","");					
			   thisId.css("cursor","url(\""+WEBROOT+"/Home/Tpl/default/Public/Images/last.cur\"),auto");				
				
			}else{
				//thisId.css("cursor","url(\""+WEBROOT+"/Home/Tpl/default/Public/Images/none.cur\"),auto");	
				thisId.css("cursor","none");
						

			$('#'+id+' .left').css({'opacity':'1'});

			
		}
		$('#'+id+' .right').css({'opacity':'0'});

		}else{
			//thisId.css("cursor","wait");
			if(num!=total){
				thisId.css("cursor","");		
				 thisId.css("cursor","url(\""+WEBROOT+"/Home/Tpl/default/Public/Images/next.cur\"),auto");	
			}else{
				thisId.css("cursor","none");	
				//thisId.css("cursor","url(\""+WEBROOT+"/Home/Tpl/default/Public/Images/none.cur\"),auto");
					
				    //$('#'+id+' .right').css({'opacity':'1','top':(y-top-25)+'px','left':(x-right)+'px'});
				
			$('#'+id+' .right').css({'opacity':'1'});
		  // }
			
		   }
		   $('#'+id+' .left').css({'opacity':'0'});	
		}

	});
	thisId.mouseout(function(){

			$('#'+id+' .left').css({'opacity':'0'});
			$('#'+id+' .right').css({'opacity':'0'});
	});	
	
	thisId.click(function(event){
		var x=event.pageX;
		//alert(x);
		//分界线
		if(x<(browse_width/2)){
			//上一页
			if(num!=1){
				loadPhoto(url,(num-1),title,type);	
			}
			
		}else{
			//下一页
			if(num!=total){
				loadPhoto(url,(num+1),title,type);	
			}			
		}

	});	
	
	
	}
	
}
//关闭相册
function loadPhotoClose(id,fgId){
		$('#'+id).empty();
		$('#'+id).remove()	
		$('#'+fgId).remove();

}