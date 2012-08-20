//---检查是否为数字 --- xiongyan
    function isNumber(obj)
    {
        if(isNaN(obj.value))
        {
              return false;
        }else{
              return true;    
        }
    }
//---保存搜索条件 --- xiongyan
    function save_search_map(){
		$.get( APP+"/Interaction/save_search_map", null, function(msg){
			 if(msg && msg["success"]){
				msgBox(msg["info"],status='1',title='操作成功',width='300');
			 }else{
				msgBox(msg["info"],status='0',title='操作失败',width='300');
			 }
		},
		"json"
		);
	}
//---重载验证码 --- xiongyan
function fleshVerify(){
	var timenow = new Date().getTime();
	document.getElementById('verify').src= APP + '/Public/verify/'+timenow;
}

//+---------------------------------------------------
//|	打开普通窗口
//+---------------------------------------------------
	function PopWindow(url,width,height)
	{
		window.open(url,"win","width="+width+",height="+height,"top=80,left=100,toolbar=no,scrollbars=no,menubar=no,alwaysRaised=yes");
	}

//加入收藏夹JS开始  --- xiongyan
function bookmark(){
	var stitle=document.title;
	var surl  =document.location.href;
	
	try{
		window.external.addfavorite(surl,stitle);
	}catch(e){
		try{
		window.sidebar.addpanel(stitle,surl,"");
		}catch(e){
		alert("若是Google浏览器,请使用ctrl+d进行添加！");
		}
	}
}
//加入收藏夹JS结束

//是否是数组中存在  --- xiongyan
function in_array(a, v) {
  var i;
  for (i = 0; i < a.length; i++) {
    if (v == a[i]) {
      return true;
    }
  }
  return false;
}

//JS设置cookie
function setcookie(cookieName, cookieValue, seconds, path, domain, secure) {
	var expires = new Date();
	if(cookieValue == '' || seconds < 0) {
		cookieValue = '';
		seconds = -2592000;
	}
	expires.setTime(expires.getTime() + seconds * 1000);
	domain = !domain ? cookiedomain : domain;
	path = !path ? cookiepath : path;
	document.cookie = escape(cookiepre + cookieName) + '=' + escape(cookieValue)
		+ (expires ? '; expires=' + expires.toGMTString() : '')
		+ (path ? '; path=' + path : '/')
		+ (domain ? '; domain=' + domain : '')
		+ (secure ? '; secure' : '');
}

function getcookie(name, nounescape) {
	name = cookiepre + name;
	var cookie_start = document.cookie.indexOf(name);
	var cookie_end = document.cookie.indexOf(";", cookie_start);
	if(cookie_start == -1) {
		return '';
	} else {
		var v = document.cookie.substring(cookie_start + name.length + 1, (cookie_end > cookie_start ? cookie_end : document.cookie.length));
		return !nounescape ? unescape(v) : v;
	}
}
//----ajax公共加载函数-------------------
function domAjax(id,url,type,data){
	var id='#'+id;
	if(type=='post'){
		
		$.post(url,data,function(html){
			
			$(id).html(html);
		});		
		
	}else if(type=='get'){
		
		$.get(url,function(html){
			
			$(id).html(html);
			
		});
		
	}else{
		$(id).load(url);	
	}

}
//---ajax分页跳转公共使用函数--- 万超
	function AdvPage(id,url){
		if(url.indexOf('lpList_ajax')){
			$(id).load(url,{},function(msg){
				//获取地图坐标字符串 由 lpList_ajax 中用php输出
				tPoint = mapJson;   //alert(tPoint[0]['name'] + tPoint[0]['tel']);
				//删除标记
				deleteTag();
				deleteDraw();
				//画标记
				var tg_arr = [182, 19]; //推广info_id xy add
				
				for ( i in tPoint ) {
					var locationMap = new google.maps.LatLng( tPoint[i]['y'], tPoint[i]['x'] );
					var html='';
					infowindow = new google.maps.InfoWindow({ content: html, maxWidth:500 });  //循环外实例化，速度将快N倍
					
					if( in_array(tg_arr, tPoint[i]['id']) ){
						//特殊楼盘
						drawDiv(locationMap, tPoint[i]['name'], tPoint[i]['price'], arrState[i], tPoint[i]['id'], map);
						//1.坐标,2.名称3.价格.4销售状态.5id号.6地图
					}else{
						//alert(tPoint[i]['name']);
						//普通楼盘  //xiongyan修改
						html = "<span style=\"color:#f00;\"><b>"+ tPoint[i]['name'] +"</b></span><br/>  <div style=\"color:#000;\">均价："+ tPoint[i]['price'] +" 元/平米</div> <div style=\"color:#000;\">售楼电话："+ tPoint[i]['tel'] +"</div><br/><a href=\"#\" style=\"color:#000;\" onclick=\"loadDoc('loupan','"+APP+"/Loupan/view/id/"+ tPoint[i]['id'] +"'); return false; \"> 查看详情&gt;&gt;</a>";
						infowindow.content = html;
						addMarker(locationMap, tPoint[i]['name'], infowindow);  
					}
				}

			},
			"json"
			);
		}else{
			$(id).load(url);
		}
		//显示自定义滚动条
	}
	
//--------------start-------提示面板-----------------------------------
//width表示提示框的宽度--- 万超
function msgBox(info,status,title,width,height,autoClose){
	//status= success成功/error失败, info提示信息,title提示标题
	//width:取整数值
	//alert('标题:'+title+'  信息:'+info+' 状态:'+status+' 提示框宽度: '+width+ 'px');
	
	//先向容器内写入内容
	var adImg='<img src="'+PUBLIC+'/Images/hr.jpg" width="100%" height="20" /></br><img src="'+PUBLIC+'/Images/ad_msgbox.jpg" />';
	
	$('#msg_box_title').html(title);
	$('#msg_box_info').html(info+adImg);

	//制定容器宽度与高度
	var width_info=$('#msg_box_info').width();	
	if(width==null){
		var width=300;	
	}else{
		var  width=Number(width);
	}
	if(height==null){
    	var height=$('#msg_box_info').height();	
	}else{
		var  height=Number(height);
	}
		
	if(width<300){
		var width=300;	
	}
	if(width_info>width){//---智能判断
		width=width_info;	
	}
	

	
	if(height<100){//---智能判断
		height=100;	
	}
	
	$('#msg_box_2').width(width);
	$('#msg_box_5').width(width);
	$('#msg_box_8').width(width);
	$('#msg_box_4').height(height);	
	$('#msg_box_5').height(height);	
	$('#msg_box_6').height(height);	
	
	$('#msg_box_inc').width(width+40);
	$('#msg_box_inc').height(height+60);	
	
	
	//1.检查当前文档的高度与宽度
	var browse_width=$(window).width()	
	var browse_height=$(window).height()	
	
	//2.检查当前文档滚动条位置
	var browse_scrollTop=$(window).scrollTop();

	//3.根据上面获取的信息，定位弹出框的位置;
	var right=(browse_width-width)/2;
	var top=(browse_height-height)/2+browse_scrollTop;
	
	//$('#msg_box_inc').css({'top':top,'left':left});
	//获取小秘书图层的位置
	//var secy_top=$('#secy_inc').css('top');
	//var secy_left=$('#secy_inc').css('left');
	//$('#msg_box_inc').css({top:secy_top,left:secy_left});
	
	//$('#msg_box_inc').animate({top:top,right:right,opacity:1},600);
	$('#msg_box_inc').css({'top':(top-20)+'px','right':(right-20)+'px'});
	$('#msg_box_inc').animate({top:top+10,right:right+10},100,function(){
			$('#msg_box_inc').animate({top:top,right:right},50);	
		
	});
	$('#msg_box_inc').show();
	//$('#msg_box_inc').animate({opacity:1},500);
  	if(autoClose==null){
	var t=setTimeout("msgBoxClose()",3000);
	}

}

//关闭提示框调用函数
function msgBoxClose(){
	var width=300;
	var height=100;
	var top=-200;
	var right=0;
	
	$('#msg_box_title').html('');
	$('#msg_box_info').html('');	
	
	$('#msg_box_2').width(width);
	$('#msg_box_5').width(width);
	$('#msg_box_8').width(width);
	$('#msg_box_4').height(height);	
	$('#msg_box_5').height(height);	
	$('#msg_box_6').height(height);	
	
	$('#msg_box_inc').width(width+40);
	$('#msg_box_inc').height(height+60);		
	
	//获取小秘书图层的位置
	var secy_top=$('#secy_inc').css('top');
	var secy_left=$('#secy_inc').css('left');
	$('#msg_box_inc').animate({top:secy_top,right:0},300,function(){
		$('#msg_box_inc').hide();
		$('#msg_box_inc').css({'top':top+'px','right':right+'px'});	
	});	
	
	//$('#msg_box_inc').animate({top:top,right:right},300);
	
}
//--------------end-------提示面板------------------------------------
//------------------start-------小秘书函数--------------------------------------
//打开小秘书
function secyMsgBox(url){
	//var url=APP+'/Secretary/index';
	//用这个判断表示小秘书图层已加载	
	if($('#secy_msg_inc .secy_msg_1').width()!=null){
		return;	
		
	}
	//1.动态加载小秘书图层
	if($('#secy_msg_inc').width()==null){
			//创建图层
			var html='<div id="secy_msg_inc"></div>';
			//创建一个dom模型容器
		   $(html).appendTo('body');		
	}else{
		
		$('#secy_msg_inc').empty();
	}
	
	$('#secy_msg_inc').load(url,function(){
			//获取小秘书图层的位置
			var secy_top=parseInt($('#secy_inc').css('top'));
			$('#secy_msg_inc').css({'top':(secy_top-40)+'px','right':'65px'});		
	});
	
	
}
//关闭小秘书
function  secyMsgBoxClose(){
	$('#secy_msg_inc').remove();
	$('#secy').bind('mouseover',function(){secyMsgBox(APP+'/Secretary/index');}); //添加小秘书事件
	
}
//------------------end-------小秘书函数--------------------------------------

var loginTFHTML='';
var registerTFHTML='';
function loginTF(){
	
	var title='腾房网-用户登录';
	if(loginTFHTML==''){
			$.ajax({
			  url: APP+"/Login/login_ajax",
			  date:'',
			  success: function(html){
					  var info=html;
					  loginTFHTML=html;	
					  msgBox(info,'0',title,320,290,1);			    
			  },
			  dataType: 'html'
			});			
	}else{
		var info=loginTFHTML;
		msgBox(info,'0',title,320,275,1);
	}

}

function registerTF(){
	var title='腾房网-用户注册';
		if(registerTFHTML==''){
			$.ajax({
			  url: APP+"/Reg/reg_ajax",
			  date:'',
			  success: function(html){
					  var info=html;
					  registerTFHTML=html;	
					  msgBox(info,'0',title,440,470,1);			    
			  },
			  dataType: 'html'
			});			
	}else{
		var info=registerTFHTML;
		msgBox(info,'0',title,440,470,1);
	}

}
//-------------------------------

//ajax 检验用户是否已登录
function isLoginAjax(){
	isLogin = false;
	$.get(APP+"/Login/isLoginAjax",'', function(msg){
		 if(msg == "notLogin"){
			 isLogin = false; //未登录
		 }else{
			 isLogin = true;
		 }
	});
	//alert(isLogin);
	return isLogin;
}
//-------------------------------

//---------------1.粘贴函数----------------------------
function copyText(txt) { 
	 var is_success = false; 
     if(window.clipboardData) {  
             window.clipboardData.clearData();  
             window.clipboardData.setData("Text", txt);  
			 is_success = true; 
     } else if(navigator.userAgent.indexOf("Opera") != -1) {  
          window.location = txt;  
		  is_success = true; 
     } else if (window.netscape) {  
          try {  
               netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");  
          } catch (e) {  
               alert("被浏览器拒绝！\n请在浏览器地址栏输入'about:config'并回车\n然后将'signed.applets.codebase_principal_support'设置为'true'");  
          }  
          var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);  
          if (!clip)  
               return;  
          var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);  
          if (!trans)  
               return;  
          trans.addDataFlavor('text/unicode');  
          var str = new Object();  
          var len = new Object();  
          var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);  
          var copytext = txt;  
          str.data = copytext;  
          trans.setTransferData("text/unicode",str,copytext.length*2);  
          var clipid = Components.interfaces.nsIClipboard;  
          if (!clip)  
               return false;  
          clip.setData(trans,null,clipid.kGlobalClipboard);  
          //alert("复制成功！") ;
		  is_success = true; 
     }
	if(is_success == true)
		alert("复制成功！") ;
} 

//图像加载重定尺寸
function ImgResize(dom){
	var img_height=arguments[1]?arguments[1]:100;//默认第二个参数为基本高度，小于此高度不重设
	var height=$(dom).height();
	var width=$(dom).width();
	//var img_height=100; //标准高度
	if(height>img_height){
		var img_width=width/height*img_height;
		$(dom).height(img_height);
		$(dom).width(img_width);
	}
}