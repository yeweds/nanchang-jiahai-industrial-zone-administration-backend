/*
	类名: prompt
	功能: 用于实例化显示多个提示框
	author:万超
	date:    2011-5-23
*/


//参数完整示例: promptArgu={title:'step1',content:'step1: 点击搜索',pageX:offset.left+10,pageY:offset.top+10,width:300,height:100,autoClose:0,closeLast:0,notArrow:1};
function  promptBox(promptArgu){
/*一. 参数说明
		  promptArgu：对象
		
		  格式:
		   promptArgu={title:'step1',							//提示框标题
		   							content:'step1: 点击搜索',     //提示框显示内容
									pageX:offset.left+10,            //提示框在页面中的x方向的位置
									pageY:offset.top+10,			//提示框在页面中的y方向的位置
									width:300,  							//提示框的宽度
									height:100,							//提示框的高度
									autoClose:0,							//提示框是否自动关闭，0表示不关闭，x>0表示,x秒后自动关闭窗口						
									closeLast:0,							//打开当前提示框时是否关闭上一个已打开的窗口
									notArrow:1                            //是否显示对话框的箭头,1表示不显示,0表示显示
									};		  	

		*/
		
	
		/*二. 固定外部css名称
			prompt_box_inc: 提示框容器	:包含所有提示框相关html
		
		  A.构造
		  .prompt_box1:  //左上圆角
		  .prompt_box2: 	//
		  .prompt_box3:  //右上圆角
		  .prompt_box4: 
		  .prompt_box5:   //正中间
		  .prompt_box6: 
		  .prompt_box7:  //左下圆角
		  .prompt_box8:  //
		  .prompt_box9: //右下圆角
		  
		  B.功能
		  .prompt_box_title:			//标题
		  .prompt_box_close:			//关闭按钮
		  .prompt_jian_top				//提示框向上箭头
		  .prompt_jian_bottom	    //提示框向下箭头
		  .prompt_jian_left	            //提示框向左箭头
		  .prompt_jian_right	        //提示框向右箭头
		  .prompt_box_info:           //正文内容 
		*/
		
		
		
		/*三. 内部方法与属性
				
			 A. 1.公共方法
				 
				 2.私有方法
					 closeObject(){}            //关闭当前提示框
					 
				 3.对象共用方法prototype
				    spromptBox.prototype.closeCurrentBox(currentId,nextDisplay)(){}  //实例化多个对象后，它们都会共用此方法，此方法用来从外部关闭打开的窗口
		
		*/	
		var title=promptArgu.title;  						//提示标题
		var content=promptArgu.content;				//提示内容
		var pageX=promptArgu.pageX;					//提示框在页中x方向的位置
		var pageY=promptArgu.pageY;					//提示框在页中y方向的位置
		var width=promptArgu.width;					//提示框的宽度
		var height=promptArgu.height;					//提示框的高度
		var autoClose=promptArgu.autoClose;		//是否自动关闭提示框, 为0表示不关闭提示框,若为x>1,则表示x毫秒后自动关闭
		var closeLast=promptArgu.closeLast;        //表示是否关闭上一次已打开的窗口
		var notArrow=promptArgu.notArrow;       //判断是否需要显示箭头,为0是表示显示，1表示不显示
		var top;															//提示框的上边距
		var left;															//提示框的左边距
		var id;															    //当前对象的唯一id
		var idNum;                                                    //当前对象的序列
		var html;														    //组成提示框的html代码
		
		var browse_width=$(window).width();       //浏览器的宽度	
	    var browse_height=$(window).height();		 //浏览器的高度
		var browse_scrollTop=$(window).scrollTop();//当前文档滚动条位置
		var y=pageY-browse_scrollTop;                       //获取相对浏览器y轴位置
		var jianLength=25;     //箭头顶点到超出容器的长度
		var jianApex=28;        //箭头顶点到容器右边或左边的最小距离
		var spilthLength=30; //两个圆角的长度之和,除去这两个圆角，剩余的部分就为正文的长度了		
		var jianClass='';          //存取当前箭头方向的class名称,用于在ie6下做png滤镜效果;		
		var isIE6=false;			 //当前浏览器是否为ie6
		
		//对象通用prototype属性
		//promptBox.prototype.isNextClose        检查是否下一次关闭引导模式       
		//promptBox.prototype.idArr                    数组: 存储上所有已打开的对象序列
		
		
		
		
		
		//通过一个对象通用prototype属性来判断当前是否为第一次创建实例。------
		if(promptBox.prototype.idArr){
			idNum=promptBox.prototype.idArr.length;
			//如果当前对象设置了关闭上一个打开的窗口，那么在这里将其关闭
			if(closeLast){
				$('#prompt_'+promptBox.prototype.idArr[promptBox.prototype.idArr.length-1]).remove();
			}
	
		}else{
			promptBox.prototype.idArr=new Array();   //存储上所有已打开的对象
			idNum=0;
		}	
			
		promptBox.prototype.idArr.push(idNum);     //将当前对象序列入入数组	
		id="prompt_"+idNum;                                      //生成当前对象的唯一id
		//---------------------------------------------------------------------------
		
		
		//----start---写入hmtl-----
		html='<div id="'+id+'" class ="prompt_box_inc"><div class="prompt_box1"></div><div class="prompt_box2"></div><div class="prompt_box_title" >'+title+'</div><div class="prompt_box_close" onmouseover="this.className=\'prompt_box_closem\'" onmouseout="this.className=\'prompt_box_close\'" onmousedown="this.className=\'prompt_box_closec\'"  onclick="promptBox.prototype.closeCurrentBox('+idNum+');"></div><div class="prompt_box3"></div><div class="prompt_box4"></div><div class="prompt_box5"><table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td valign="middle" class="prompt_info">'+content+'</td> </tr>   </table> </div> <div class="prompt_box6"></div> <div class="prompt_box7"></div><div class="prompt_box8">  </div><div class="prompt_box9"></div></div>';
		$('body').prepend(html);
		//--end-----html写入完毕--		



		/*设计思路
		
		1.自动根据窗口显示提示框，分上下左右四个方向自动调整显示,默认向下
		
		*/
			
			//---------strat ----根据触发点的不同，调整提示框在浏览器中的位置-----------------------------
			if(!notArrow){
				//1上部位置够用
				if(y>(height+jianLength)){
					
					//1.1左边位置够用
					if(pageX>width){
						
						//1.1.1	左边位置显示超过浏览器宽度
						if((pageX+jianApex)>browse_width){
							/*位置
							|                       + |
							*/
							top=pageY-height+jianApex;
							left=pageX-width-jianLength;
							$('#'+id+' .prompt_box6').html('<div class="prompt_jian_right"></div>'); //写入右箭头标志
							$(	'#'+id+' .prompt_jian_right').css('bottom','0px');	
							jianClass='.prompt_jian_right';
						
						//1.1.2	左边位置显示没有超过浏览器宽度,	但加上容器宽度就超过了
						}else if((pageX+width)>browse_width){
							/*位置
							|                     +   |
							*/
							top=pageY-height-jianLength;
							left=pageX-width+jianApex;
							$('#'+id+' .prompt_box8').html('<div class="prompt_jian_bottom"></div>'); //写入下箭头标志
							$(	'#'+id+' .prompt_jian_bottom').css('right','0px');	
							jianClass='.prompt_jian_bottom';
						 //1.1.3左边位置加上容器宽度没有超过浏览器宽度
						}else{
							/*位置
							|          +               |
							*/						
							top=pageY-height-jianLength;
							left=pageX-jianApex;
							$('#'+id+' .prompt_box8').html('<div class="prompt_jian_bottom"></div>'); //写入下箭头标志
							$(	'#'+id+' .prompt_jian_bottom').css('left','0px');	
							jianClass='.prompt_jian_bottom';
							
						}
					
					//1.2左边位置不够用  pageX<width
					}else{
						
						left=pageX-jianApex-10;	 //保留10px边距,避免显示效果不好
						//1.1.1	左边位置显示小于0,未进入浏览器显示范围
						if(left<0){
							/*位置
							| +                       |
							*/
							top=pageY-height+jianApex;
							left=pageX+jianLength;
							$('#'+id+' .prompt_box4').html('<div class="prompt_jian_left"></div>'); //写入左箭头标志
							$(	'#'+id+' .prompt_jian_left').css('bottom','0px');	
							jianClass='.prompt_jian_left';
						//1.1.1	左边位置显示大于0
						}else{
							/*位置
							|          +               |
							*/						
							top=pageY-height-jianLength;
							left=10;
							$('#'+id+' .prompt_box8').html('<div class="prompt_jian_bottom"></div>'); //写入上箭头标志
							$(	'#'+id+' .prompt_jian_bottom').css('right',(width+10-jianLength-pageX)+'px');	
							jianClass='.prompt_jian_bottom';
						}					
						
						
					
					}
					
					
					
				//y<(height+jianLength)	上部位置不够用
				}else{
					
					
					if(pageX>width){
						
						left=pageX+jianApex;	
						if((pageX+jianApex)>browse_width){
							/*位置
							|                       + |
							*/
							top=pageY-jianApex;
							left=pageX-width-jianLength;
							$('#'+id+' .prompt_box6').html('<div class="prompt_jian_right"></div>'); //写入右箭头标志
							$(	'#'+id+' .prompt_jian_right').css('top','0px');	
							jianClass='.prompt_jian_right';
							
						}else if((pageX+width)>browse_width){
							/*位置
							|                     +   |
							*/
							top=pageY+jianLength;
							left=pageX-width+jianApex;
							$('#'+id+' .prompt_box2').after('<div class="prompt_jian_top"></div>'); //写入下箭头标志
							$(	'#'+id+' .prompt_jian_top').css('right','15px');	
							jianClass='.prompt_jian_top';
	
						}else{
							/*位置
							|          +               |
							*/	
							left=pageX-jianApex;
							top=pageY+jianLength;
							$('#'+id+' .prompt_box2').after('<div class="prompt_jian_top"></div>'); //写入下箭头标志
							$(	'#'+id+' .prompt_jian_top').css('left','15px');
							jianClass='.prompt_jian_top';
						}
					
					//pageX<width
					}else{
						
						left=pageX-jianApex-10;	 //保留10px边距,避免显示效果不好
						if(left<0){
							/*位置
							| +                       |
							*/
							top=pageY-jianApex;
							left=pageX+jianLength;
							$('#'+id+' .prompt_box4').html('<div class="prompt_jian_left"></div>'); //写入左箭头标志
							$(	'#'+id+' .prompt_jian_left').css('top','0px');	
							jianClass='.prompt_jian_left';
							
						}else{
							/*位置
							|          +               |
							*/						
							top=pageY+jianLength;
							left=10;
							$('#'+id+' .prompt_box2').after('<div class="prompt_jian_top"></div>'); //写入上箭头标志
							$(	'#'+id+' .prompt_jian_top').css('right',(width+10-jianLength-pageX+15)+'px');	
							jianClass='.prompt_jian_top';
						}					
						
						
					
					}		
					
					
				}
			
			//不加箭头	
			}else{
				top=pageY;
				left=pageX;
			}
			//---------end ----根据触发点的不同，调整提示框在浏览器中的位置-----------------------------	
			

			width=width-spilthLength;
			height=height-spilthLength;
			//----将调整好的提示框放入浏览器可视范围
			$('#'+id+' .prompt_box2').css('width',width);
			$('#'+id+' .prompt_box4').css('height',height);
			$('#'+id+' .prompt_box5').css({'width':width,'height':height});
			$('#'+id+' .prompt_box6').css('height',height);
			$('#'+id+' .prompt_box6').css('height',height);
			$('#'+id+' .prompt_box8').css('width',width);
			$('#'+id+' .prompt_box8').css('width',width);
			$('#'+id+' .prompt_box_close').css({'z-index':(1000005+idNum)});
			$('#'+id).css({'top':top+'px','left':left+'px','width':(width+spilthLength)+'px','height':(height+spilthLength)+'px','z-index':(1000005+idNum)});		
			


			//------判断是否为ie6,因为ie6下会要做png滤镜处理.-------------------------------
			if(window.XMLHttpRequest){ //Mozilla, Safari, IE7
				/*if(!window.ActiveXObject){ // Mozilla, Safari,
					alert('Mozilla, Safari');
				}else{
					alert('IE7');
				}*/
			}else {
				isIE6=true;
				//alert('IE6');
			}
		 if(isIE6){
			 if(jianClass=='.prompt_jian_top'){
				 $(	'#'+id+' .prompt_jian_top').css('top','-23px');
				 
			 }
			 
			  DD_belatedPNG.fix('.prompt_box_inc');
			  DD_belatedPNG.fix('.prompt_box1');
			  DD_belatedPNG.fix('.prompt_box2'); 
			  DD_belatedPNG.fix('.prompt_box3'); 		  
			  DD_belatedPNG.fix('.prompt_box4');  
			  DD_belatedPNG.fix('.prompt_box6');  
			  DD_belatedPNG.fix('.prompt_box7');  
			  DD_belatedPNG.fix('.prompt_box8');  
			  DD_belatedPNG.fix('.prompt_box9');  
			  if(!notArrow){
			       DD_belatedPNG.fix(jianClass);  
			  }
		 
		 }
		//------判断是否为ie6,因为ie6下会要做png滤镜处理.--------------end-----------------
		

			//如果存在自关闭的时间，则按指定时间关闭提示框
			if(autoClose){
				setTimeout(function(){closeObject(idNum);},autoClose);
				
			}
			
			
		//=======================定义方法====================
			//关闭当前对象
			function closeObject(idNumValue){
				$('#'+id).remove();//关闭即移除
				promptBox.prototype.idArr[idNumValue]='';
				$('#all').append('<br>'+promptBox.prototype.idArr+'-----id:'+idNumValue);
			}
			
		
		
			promptBox.prototype.isNextClose=false;  //检查是否关闭引导模式
			
			//------------------关闭上一个已打开的下拉框------对象共用 prototype方法-----------------------
			promptBox.prototype.closeCurrentBox=function(currentId,nextDisplay){
				
				/*   if(!promptBox.prototype.isNextClose){		
				  
						var content='<br /><div class="height_5px"></div>&nbsp;&nbsp;&nbsp;&nbsp;您将关闭引导模式! 关闭后, 您随时可在右下角的新手上路中按步骤开启引导模式!<div class="height_5px"></div><div align="center"><img src="'+WEBROOT+'/Home/Tpl/default/Public/Images/intro_bn_sure.png" onclick="promptBox.prototype.closeCurrentBox('+(currentId+1)+',true)"/><img src="'+WEBROOT+'/Home/Tpl/default/Public/Images/intro_no_next.png" onmouseover="if(isDispalyIntro){this.src=\''+WEBROOT+'/Home/Tpl/default/Public/Images/intro_no_next_m.png\';}"  onmouseout="if(isDispalyIntro){this.src=\''+WEBROOT+'/Home/Tpl/default/Public/Images/intro_no_next.png\';}"  onclick="if(isDispalyIntro){this.src=\''+WEBROOT+'/Home/Tpl/default/Public/Images/intro_no_next_c.png\';isDispalyIntro=false;}else{this.src=\''+WEBROOT+'/Home/Tpl/default/Public/Images/intro_no_next.png\';isDispalyIntro=true;}"/></div>';		
						var height=$(window).height();
						var scrollTop=$(window).scrollTop();
						var width=$(window).width();
						var promptArgu={title:'腾房小秘书提示您!',content:content,pageX:width/2-150,pageY:scrollTop+height/2-125,width:300,height:125,autoClose:0,closeLast:1,notArrow:1};	
						new promptBox(promptArgu);		
						promptBox.prototype.isNextClose=true;
														
				   }else{*/
	
						$('#prompt_'+currentId).remove();//关闭即移除
						promptBox.prototype.idArr[currentId]='';
						closeFgDiv();
						
				  // }
				   
				}
			//对象通用prototype方法--------------end---------------------------			


}
//--------------------------end prompt类---------------------------------------


//写入覆盖层---------------
function writeFgDiv(){
	if(!$('#load_overlayer').width()){
	   var html='<div id="load_overlayer" style="position: absolute;top:0px;right:0px;width:'+$(document).width()+'px;height:'+$(document).height()+'px;background-color:black;z-index:1000004;"></div>';
	   $(html).appendTo('body');	
	   $('#load_overlayer').css({'opacity':'0'});	
	   $('#load_overlayer').animate({opacity:0.5},200);
	   $('#load_overlayer').click(function(){
			closeFgDiv();
	   });	
	   $('#secy').unbind('mouseover');  //取消小秘书事件
	   	
	}
}
//关闭覆盖层--------------
function closeFgDiv(){
	$('#load_overlayer').remove();
	$('#secy').bind('mouseover',function(){secyMsgBox(APP+'/Secretary/index');});//添加小秘书事件
}
/*

$(function(){
		if (isUseHelpOnce == '0')
		{
			secyMsgBox(APP+'/Secretary/load_intro');   //调用小秘书提示
		}else{
	   		$('#secy').bind('mouseover',function(){secyMsgBox(APP+'/Secretary/index');});//添加小秘书事件
		}
});


//如果接收小秘书引导则执行此函数
var isDispalyIntro=false;
function DisplayIntro(){
	  isDispalyIntro=true;
	  promptIntroGoTo(1);
}






//---------------------------引导步骤,按num调用步骤---------------------------------
function promptIntroGoTo(num){
	//if(promptIntroGoTo.prototype.num == num) return;
	//promptIntroGoTo.prototype.num = num;
	isDispalyIntro=true;
	var offset;
	var content;
	var promptArgu;
	var setpImage='<img src="'+WEBROOT+'/Home/Tpl/default/Public/Images/step.png"/>';
	var heightSpace='<div style="height:22px; width:100px;font-size:10px;line-height:10px;overflow:hidden;">&nbsp;</div>&nbsp;&nbsp;&nbsp;&nbsp;';
	//步骤标题
	var titleStep1=setpImage+'<img src="'+WEBROOT+'/Home/Tpl/default/Public/Images/1.png"/>';
	var titleStep2=setpImage+'<img src="'+WEBROOT+'/Home/Tpl/default/Public/Images/2.png"/>';
	var titleStep3=setpImage+'<img src="'+WEBROOT+'/Home/Tpl/default/Public/Images/3.png"/>';
	//下一步html
	var next='<div class="height_5px"></div><div align="center"><img src="'+WEBROOT+'/Home/Tpl/default/Public/Images/intro_bn_next.png" onmouseover="this.src=\''+WEBROOT+'/Home/Tpl/default/Public/Images/intro_bn_nextm.png\';"  onmouseout="this.src=\''+WEBROOT+'/Home/Tpl/default/Public/Images/intro_bn_next.png\';"  onclick="this.src=\''+WEBROOT+'/Home/Tpl/default/Public/Images/intro_bn_nextc.png\';promptIntroGoTo('+(num+1)+')"/></div>';
	
	switch(num){
		//step1:搜索页面---------------------
		case 1:   
					  //小秘书说第一名引导的话
					   $('body').animate({scrollTop:0},500);	  
					   $(window).scrollTop(0);		
					  var t=setTimeout(function(){
					  writeFgDiv();					      
					  offset=$('#secy_inc').offset();
					  content=heightSpace+'Hi，你好！我是腾房小秘书,很高兴为您服务，接下来我将带您去熟悉我们的网站功能!'+next;
					  promptArgu={title:titleStep1,content:content,pageX:offset.left+10,pageY:160,width:300,height:120,autoClose:0,closeLast:1};
					  new promptBox(promptArgu);
					  },1000);
					  break;
		
		
		case 2:
					//引导搜索
					offset=$('#index_search_only').offset();
					content=heightSpace+'在这里你可以根据需要输入或选择一些搜索条件作为你查找楼盘的依据'+next
					promptArgu={title:titleStep1,content:content,pageX:offset.left+10,pageY:offset.top+10,width:300,height:120,autoClose:0,closeLast:1};
					new promptBox(promptArgu);	
					closeFgDiv();	
					break;
		
		
		case 3:
					//搜索按钮
					offset=$('#sub').offset();
					content=heightSpace+'点击搜索按钮,将会以地图和列表的形式展现符合您搜索要求的结果!';
					promptArgu={title:titleStep1,content:content,pageX:offset.left+10,pageY:offset.top+10,width:300,height:100,autoClose:0,closeLast:1};
					new promptBox(promptArgu);	
					break;
		
		//step2: 地图页面------------------------------	
		case 4:
					//提示搜索条件
					offset=$('#map').offset();
					$('body').animate({scrollTop: offset.top}, 1000);	  
					$(window).scrollTop(offset.top);							
					writeFgDiv();	
					offset=$('#map_find').offset();
					content=heightSpace+'这里显示您搜索的条件'+next;
					promptArgu={title:titleStep2,content:content,pageX:offset.left+200,pageY:offset.top+10,width:300,height:100,autoClose:0,closeLast:1};
					new promptBox(promptArgu);	
					break;		

		case 5:
					//显示地图搜索结果
					offset=$('#map_find').offset();
					content=heightSpace+'这里显示你搜索到的楼盘在地图上显示的位置'+next;
					promptArgu={title:titleStep2,content:content,pageX:offset.left+200,pageY:offset.top+300,width:300,height:100,autoClose:0,closeLast:1};
					new promptBox(promptArgu);		
					break;	
					
		//case 6:
//				   //提示保存搜索条件
//				   offset=$('#map_find').offset();
//				   content=heightSpace+'点此可保存你的搜索条件'+next;
//				   promptArgu={title:titleStep2,content:content,pageX:offset.left+900,pageY:offset.top,width:300,height:100,autoClose:0,closeLast:1};
//				   new promptBox(promptArgu);	
//				   break;

		case 6:
					//点击进入楼盘详情
					offset=$('#map_find').offset();
					content=heightSpace+'点击楼盘名称进入楼盘详情页';
					promptArgu={title:titleStep2,content:content,pageX:offset.left+700,pageY:offset.top+50,width:300,height:100,autoClose:0,closeLast:1};
					new promptBox(promptArgu);
					closeFgDiv();	
					break;		
		
	    //step3: 楼盘页面------------------------------			
	
		
		case 7:
					offset=$('#loupan').offset();
					$('body').animate({scrollTop: offset.top}, 1000);	  
					$(window).scrollTop(offset.top);				
					//楼盘列表
					writeFgDiv();	
					offset=$('#lou_tag_include').offset();
					content=heightSpace+'这里显示楼盘列表，在此点击可随意切换前往不同楼盘。'+next;
					promptArgu={title:titleStep3,content:content,pageX:offset.left+20,pageY:offset.top+50,width:300,height:120,autoClose:0,closeLast:1};
					new promptBox(promptArgu);	
					break;		
					
		case 8:
					//显示楼盘详情
					offset=$('#lou_include').offset();
					content=heightSpace+'这里显示楼盘的相关信息'+next;
					promptArgu={title:titleStep3,content:content,pageX:offset.left+300,pageY:offset.top+100,width:300,height:120,autoClose:0,closeLast:1};
					new promptBox(promptArgu);	
					break;							
		
//		case 10:
//					//收藏楼盘
//					  offset=$('#lou_include').offset();
//					  content=heightSpace+'如果您对此楼盘兴趣，点此可以将此楼盘收藏！'+next;
//					  promptArgu={title:titleStep3,content:content,pageX:offset.left+700,pageY:offset.top+10,width:300,height:120,autoClose:0,closeLast:1};
//					new promptBox(promptArgu);		
//					break;		
				
		case 9:
					  //楼盘印象
					  offset=$('#lou_yin_write').offset();
					  content=heightSpace+'如果您对这个楼盘比较熟悉,可以在这里发表您对此楼盘的印象！'+next;
					  promptArgu={title:titleStep3,content:content,pageX:offset.left+20,pageY:offset.top+20,width:300,height:120,autoClose:0,closeLast:1};
					  new promptBox(promptArgu);		
					  break;		
		
		case 10:
					 //点评
					  offset=$('#lou_dp_include').offset();
					  content=heightSpace+'这里显示楼盘点评，如果您也参与了点评，有可能这里就会显示您的评论哦！'+next;
					  promptArgu={title:titleStep3,content:content,pageX:offset.left+20,pageY:offset.top+35,width:300,height:120,autoClose:0,closeLast:1};
					  new promptBox(promptArgu);		
					  break;	
		
		
		case 11:
						//滑块
						offset=$('#lou_sha_title').offset();
					   $('body').animate({scrollTop: offset.top-300}, 1000);	  
					   $(window).scrollTop(offset.top-300);		
					    content=heightSpace+'这里内容可多了，每个按钮下都隐藏着一个单独的板块，内容十分精彩，快去点击看看吧！';
						promptArgu={title:titleStep3,content:content,pageX:offset.left+100,pageY:offset.top+5,width:300,height:100,autoClose:0,closeLast:1};
						new promptBox(promptArgu);	
						closeFgDiv();	
						isDispalyIntro=false;//退出引导模式
						break;		

		
	}
	
}*/