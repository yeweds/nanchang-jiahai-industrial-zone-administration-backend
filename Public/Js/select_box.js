//++++++++++++++++++++下拉框对象+++++++++++++++++++++++++++//
/*
	要满足的功能:
	1.自定义下拉列表框值，对应表单值
	2.可在下列框中除了标准参数外还可自定义增加其它的代码块
	3.自定义隐藏域获取此容器的选择值
	4.增加公共方法可自定义传值给容器对象
	5.样式通过专属css可自由定义样式
*/

	function selectBox(optionValue){
		/*一. 参数说明
		  optionValue：对象
		
		  格式:
         optionValue={id:'some',    											//当前容器id
								 inputId:'some_val',								//当前容器对应的隐藏域id,默认名称为容器id+'_val';
								 defText:'价格范围',									//默认显示的数据文本
								 defVal:'a',												//默认的数据值
								 text:['sd','d','sd','sd','sd','sd','sd','sd'],//下拉数据文本
								 value:['1','2','sd','sd','sd','sd','sd','sd'],//下拉数据值
								 beforeHtml:'',										//在下拉框前面插入的一段自定义html
								 afterHtml:'',											//在下拉框后面插入的一段自定义html
								 width:'auto'											//定义下拉框的宽度，默认与容器宽度相等
								,height:'auto'											//定义下拉框的高度，默认自动撑开
								};		

		*/
		
	
		/*二. 固定外部css名称
			select_box:选择框样式		
			select_default:默认显示的数据项样式
		
		  A.下拉按钮样式
		   selectbn: 下接按钮的class样式名称;
		   selectbn_m: 鼠标移动到下接按钮的class样式名称;
		   selectbn_c:   鼠标点击时下接按钮的class样式名称;
		   
		  B.下拉菜单样式
		   option_box: 下接菜单框的class样式名称;
		   option: 下接菜单项的class样式名称;
		   option_m: 鼠标移动到下接菜单项的class样式名称;
		   option_c:   鼠标点击时下接菜单项的class样式名称;	   
		   
		*/
		
		
		
		/*三. 内部方法与属性
				
			 A. 1.公共方法
					 sendValue=function(text,value){}   //此方法是为外部传入自定义值而设立的;
					 close=function(text,value){}   //此方法是用来关闭下拉列表的;
				 
				 2.私有方法
					 closeBox(){	}            //关闭当前下拉框
					 setLastObject(){}    //存储上一个打开的下拉框实例
					 clearLastObject(){} //清除已打开的下拉框实例
					 
				 3.对象共用方法prototype
				    selectBox.prototype.closeLast(){}  //实例化多个对像后，它们都会共用此方法，此方法用来关闭上一个打开的下拉框
		
		*/
		this.optionValue=optionValue;
		var optionValue=this.optionValue;
		//容器id
		this.id=this.optionValue.id;
		var id=this.id;
		var selectObjectCurrent=this;
		//当前显示的数据项
		this.currentValue=this.optionValue.defVal;
		var currentValue=this.currentValue;
		this.currentText=this.optionValue.defText;
		var currentText=this.currentText;		
		var slideTime=50;//下拉或关闭菜单的执行时间(速度);		
		
		
		
		//写入下拉按钮与下接列表的html------------------------------
		$('#'+this.id).addClass('select_box');	
		var html='<div class="select_default">&nbsp;'+this.optionValue.defText+'</div><div class="selectbn"></div>';
		var optionHtml='';

		//循环输出下拉列表项
		for (i in this.optionValue.text){
		   optionHtml=optionHtml+'<div class="option">&nbsp;'+this.optionValue.text[i]+'</div>';

		}
		optionHtml='<div class="option_box">'+this.optionValue.beforeHtml+optionHtml+this.optionValue.afterHtml+'</div>';
		//添加隐藏域
		html=html+optionHtml+'<input id="'+optionValue.inputId+'" name="'+optionValue.inputId+'"  type="hidden" value="'+this.optionValue.defVal+'" />';
		$('#'+this.id).html(html);
		//------------写入html完毕-------------------
		
		
		
		//初始化下拉框--------------------------------------
		$('#'+id+' .option_box').slideUp(0,function(){			
				$('#'+id+' .option_box').css({'visibility':'visible'});			
		});	
		
		var width=$('#'+id).width();
		var height=$('#'+id).height();
		//1.显示的数据项长宽必须与容器保持一致;
		$('#'+id+' .select_default').css({"height":height+'px',"width":width+'px',"line-height":height+'px'});
		//2.下拉按钮高度必须与容器保持一致;
		$('#'+id+' .selectbn').css({"height":height+'px'});
		//3.下拉框上部距离必须与容器保持一致; 如果存在设置的宽度较大则用
		if(isNaN(parseInt(optionValue.width))){
		    optionValue.width=0;	
		}
		if(width>optionValue.width){
		     var optionWidth=width+'px';
	     }else{
			var optionWidth=optionValue.width+'px';
		 }		
		$('#'+id+' .option_box').css({"top":(height-1)+'px','width':optionWidth});
		//----end ----初始化下拉框----------------
	
	
		
		//------------start----下拉按钮鼠标效果及点击时触发事件---------------------------
		$('#'+this.id+' .selectbn').mouseover(function(){
			if(!$(this).hasClass('selectbn_c')){		
			  $(this).removeClass().addClass('selectbn_m');	
			}
		});
		
		$('#'+this.id+' .selectbn').mouseout(function(){
			if(!$(this).hasClass('selectbn_c')){
				 $(this).removeClass().addClass('selectbn');	
			}
		});	
		
		$('#'+this.id+' .selectbn').click(function(){
			if($(this).hasClass('selectbn_c')){
                closeBox()	 
			}else{
			  $(this).removeClass().addClass('selectbn_c');			  
			  //打开下拉面板
			  $('#'+id+' .option_box').slideDown(slideTime);	
			  
			 $('#'+id).css({'z-index':'1000'});
       		  selectBox.prototype.closeLast()			  
			  setLastObject();				
			}		  
		});			
		
		$('#'+this.id+' .select_default').click(function(){
			if($('#'+id+' .selectbn_c').hasClass('selectbn_c')){
                closeBox()	 
			}else{
			  $('#'+id+' .selectbn').removeClass().addClass('selectbn_c');			  
			  //打开下拉面板
			  $('#'+id+' .option_box').slideDown(slideTime);	
			  
			 $('#'+id).css({'z-index':'1000'});
       		  selectBox.prototype.closeLast()			  
			  setLastObject();				
			}		  
		});				
		//------------end----下拉按钮鼠标效果及点击时触发事件---------------------------		



		//------------start----下拉列表鼠标效果及点击时触发事件---------------------------
		$('#'+this.id+' .option').mouseover(function(){
			 if(!$(this).hasClass('option_c')){
				  $(this).removeClass().addClass('option_m');	
			 }
		});
		
		$('#'+this.id+' .option').mouseout(function(){
			 if(!$(this).hasClass('option_c')){	
				  $(this).removeClass().addClass('option');	
			 }
		});	
		
		$('#'+this.id+' .option').click(function(){
			  $('#'+id+' .option_c').removeClass().addClass('option');	
			  $(this).removeClass().addClass('option_c');				  
			  //触发选择事件:  1.关闭下拉面板  2.将值传给关联的隐藏域
			  currentValue=optionValue.value[$(this).index()];
			  currentText=optionValue.text[$(this).index()];	  
			  closeBox()		  
			  if(optionValue.inputId){
				 $('#'+optionValue.inputId).val(currentValue);	 
			  }else{
			     $('#'+id+'_val').val(currentValue);	  
			  }
			 $('#'+id+' .select_default').html('&nbsp;'+currentText);	
		});	
		//------------start----下拉按钮鼠标效果及点击时触发事件---------------------------			
		
		
		//公共方法---------------------------start------------------------------------	-----		
		//其它外部图层用此方法可向当前下拉框输入值并关闭下接框-----
		this.sendValue=function(text,value){		
              $('#'+id+' .option_c').removeClass().addClass('option');	
			  //触发选择事件:  1.关闭下拉面板  2.将值传给关联的隐藏域
			  currentValue=value;
			  currentText=text;		  
			  closeBox()		  
			  if(optionValue.inputId){
				 $('#'+optionValue.inputId).val(currentValue);	 
			  }else{
			     $('#'+id+'_val').val(currentValue);	  
			  }
			 $('#'+id+' .select_default').html('&nbsp;'+currentText);			
	
		}		
		//----关闭下接框-----
		this.close=function(){		
		//return 'sdfsdf';
			 $('#'+id+' .option_box').slideUp(slideTime);	  
			  $('#'+id+' .selectbn_c').removeClass().addClass('selectbn');	
			  $('#'+id).css({'z-index':''});
			  clearLastObject()			
		}
		//公共方法---------------------------end------------------------------------	-----		
		
		
		
		//对象私有方法---------------------------start------------------------------------
		//----关闭下接框-----
		function closeBox(){		
			 $('#'+id+' .option_box').slideUp(slideTime);	  
			  $('#'+id+' .selectbn_c').removeClass().addClass('selectbn');	
			  $('#'+id).css({'z-index':''});
			  clearLastObject()			
		}		
		//---存储上一个打开对象
		function setLastObject(){			
			selectBox.prototype.closeLastObject=selectObjectCurrent;
		}
		//---清除已打开的对像
		function clearLastObject(){
			selectBox.prototype.closeLastObject='';
		}		
		//对象私有方法---------------------------end------------------------------------		
		
		
		
		
		//对象通用prototype属性方法----------------start---------------------------
		selectBox.prototype.closeLastObject='';   //存储上一个打开的对象
		//关闭上一个已打开的下拉框
		selectBox.prototype.closeLast	=function(){
				//如果存在已打开的下拉框，则关闭
				if(selectBox.prototype.closeLastObject){		
					selectBox.prototype.closeLastObject.close();	
				}				
			}
		//对象通用prototype属性方法--------------end---------------------------

	}