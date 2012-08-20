//------------------回顶部,楼盘，地图，新手上路操作
function gotoPosition(){
	
	
	var id='goto_inc_1';
	//首先根据当前页面滚动位置决定要显示那些内容.
	var page_height=$(document).height();
	var browse_height=$(window).height();
	var browse_scrollTop=$(window).scrollTop(); //获取滚动位置
	var html;
	var position=165;
	var height_one=60;
	
	//不存在则写入
	if($('#'+id).width()==null){		
		
			//第一次全部写入html 
			var tonew='<div class="tonew"></div>';
			var step='<div id="step_inc_1" class="step_inc"><div class="step1"></div> <div class="step2"></div><div class="step3"></div></div>';		
			var totop='<div class="totop" onmouseover="this.className=\'totop_m\'" onmouseout="this.className=\'totop\'" ></div>';
			var tomap='<div class="tomap" onmouseover="this.className=\'tomap_m\'" onmouseout="this.className=\'tomap\'" ></div>';
			var tomapdown='<div class="tomapdown" onmouseover="this.className=\'tomapdown_m\'" onmouseout="this.className=\'tomapdown\'" ></div>';
			var toloupan='<div class="toloupan" onmouseover="this.className=\'toloupan_m\'" onmouseout="this.className=\'toloupan\'" ></div>';
			html='<div class="goto_inc" id="'+id+'">'+tonew+step+totop+tomap+tomapdown+toloupan+'</div>';		
			$('body').append(html);
			
			//隐藏不需要的图层
			$('#step_inc_1').hide();
			$('#'+id+' .totop').hide();
			$('#'+id+' .tomap').hide();
			$('#'+id+' .tomapdown').hide();
			$('#'+id+' .toloupan').hide();

			//添加鼠标事件------
			$('#'+id+' .totop').click(function(){
						$('body').animate({scrollTop: 0}, 1000);	  
						$(window).scrollTop(0);	
						setTimeout("gotoPosition()",2000);
		
			});		
			
			$('#'+id+' .tomap').click(function(){
						offset=$('#map').offset();
						$('body').animate({scrollTop: offset.top}, 1000);	  
						$(window).scrollTop(offset.top);	
						setTimeout("gotoPosition()",2000);

			});		
			
			$('#'+id+' .tomapdown').click(function(){
						offset=$('#map').offset();
						$('body').animate({scrollTop:offset.top}, 1000);	  
						$(window).scrollTop(offset.top);		
						setTimeout("gotoPosition()",2000);

			});		
					
			$('#'+id+' .toloupan').click(function(){
						offset=$('#loupan').offset();
						$('body').animate({scrollTop:offset.top}, 1000);	  
						$(window).scrollTop(offset.top);	
						setTimeout("gotoPosition()",2000);

			});												

			//-------------------------
			 gotoPosition.prototype.show=0;
			  $('#'+id+' .tonew').mouseover(function(){	
					gotoPosition.prototype.show=1;
					if(!$(this).hasClass('tonew_m')){
						  
						  $(this).removeClass().addClass('tonew_m');
						  $('#step_inc_1').show();
						   
					}
				  
			  });
			  $('#'+id+' .tonew').mouseout(function(){
				  setTimeout(function(){
					  if(gotoPosition.prototype.show!=1){
						  $('#'+id+' .tonew_m').removeClass().addClass('tonew');
						  $('#step_inc_1').hide();
					  }
				  },1000);
				  
				  gotoPosition.prototype.show=0;
				  
			  });			
		
			  $('#step_inc_1').mouseenter(function(){	
					  gotoPosition.prototype.show=1;	
					   $('#'+id+' .tonew').removeClass().addClass('tonew_m');		
						  $('#step_inc_1').show();						  		  				  
			  });
			  
			  $('#step_inc_1').mouseleave(function(){	
					  gotoPosition.prototype.show=0;	
					  setTimeout(function(){
						  if(gotoPosition.prototype.show==0){	
							  $('#'+id+' .tonew_m').removeClass().addClass('tonew');	
						  $('#step_inc_1').hide();											  
						  }
					  },1000);
			  });								
							
							
			
			//---------------------------
			//step1--
			 $('#'+id+' .step1').mouseover(function(){
					if(!$(this).hasClass('step1_dis')){		
					  $(this).removeClass().addClass('step1_m');	
					}
				});
				
			 $('#'+id+' .step1').mouseout(function(){
					if(!$(this).hasClass('step1_dis')){
						 $(this).removeClass().addClass('step1');	
					}
				});	
				
			  $('#'+id+' .step1').click(function(){
					if(!$(this).hasClass('step1_dis')){
						promptIntroGoTo(1);
					}  
				});		
			//step2--
			   $('#'+id+' .step2').mouseover(function(){
					if(!$(this).hasClass('step2_dis')){		
					  $(this).removeClass().addClass('step2_m');	
					}
				});
				
			   $('#'+id+' .step2').mouseout(function(){
					if(!$(this).hasClass('step2_dis')){
						 $(this).removeClass().addClass('step2');	
					}
				});	
				
			   $('#'+id+' .step2').click(function(){
					if(!$(this).hasClass('step2_dis')){
						promptIntroGoTo(4);
					}  
				});		
			//step3--
				$('#'+id+' .step3').mouseover(function(){
					if(!$(this).hasClass('step3_dis')){		
					  $(this).removeClass().addClass('step3_m');	
					}
				});
				
				$('#'+id+' .step3').mouseout(function(){
					if(!$(this).hasClass('step3_dis')){
						 $(this).removeClass().addClass('step3');	
					}
				});	
				
				$('#'+id+' .step3').click(function(){
					if(!$(this).hasClass('step3_dis')){
						promptIntroGoTo(7);
					}  
				});									

	}	
	//--------------写入html完毕-------------------------------
	
	
	//--------------根据页面长度来显示相关滑块--------------
	//1.说明已打开楼盘详情	
	if(page_height>1400){
		
			//在楼盘页显示回到地图与顶部
			if(browse_scrollTop>755){
	
						$('#'+id+' .totop').show();
						$('#'+id+' .tomap').show();
						$('#'+id+' .tomapdown').hide();
						$('#'+id+' .toloupan').hide();
	
			//显示回顶部和回楼盘	
			}else if(browse_scrollTop>(755-400)){
				
						$('#'+id+' .totop').show();
						$('#'+id+' .tomap').hide();
						$('#'+id+' .tomapdown').hide();
						$('#'+id+' .toloupan').show();			
		
			//在顶部，显示回地图与楼盘	
			}else{
				
						$('#'+id+' .totop').hide();
						$('#'+id+' .tomap').hide();
						$('#'+id+' .tomapdown').show();
						$('#'+id+' .toloupan').show();			
	
			}	
		

	//说明最少已打开地图页面	
	}else if(page_height>850){
			
			//显示回到地图
			if(browse_scrollTop<(755-browse_height+100)){
	
						$('#'+id+' .totop').hide();
						$('#'+id+' .tomapdown').show();
	
			//显示回顶部	
			}else if(browse_scrollTop>(755-400)){
				
						$('#'+id+' .totop').show();
						$('#'+id+' .tomapdown').hide();		
	
			//否则什么也不显示	
			}else{
				
						$('#'+id+' .totop').hide();
						$('#'+id+' .tomapdown').hide();				
		
			}
				

	//第一次显示 只有第一屏时
	}
    //-------end-------根据页面长度来显示相关滑块--------------	
	
	
	
   //---1024*768模式下运行--------//
   if($('#'+id).width()!=null){
		if($(window).width()<1100){
		  $('#'+id).css('right','0px')
		}else{
		   $('#'+id).css('left',($('#ad').offset().left+974)+'px')
		}
	 }

	
	//--用这个判断表示step图层已加载--并根据位置来决定是否显示--//
	if($('#step_inc_1').width()!=null){

		if($('body').height()<800){
			$('#step_inc_1 .step2').removeClass().addClass('step2_dis');
			$('#step_inc_1 .step3').removeClass().addClass('step3_dis');

		}else if($('body').height()<1500){
			$('#step_inc_1 .step2_dis').removeClass().addClass('step2');
			$('#step_inc_1 .step3').removeClass().addClass('step3_dis');	
		}else{		
			$('#step_inc_1 .step3_dis').removeClass().addClass('step3');				
		}

	}		
	
	
	 

}
