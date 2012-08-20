<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>南昌婚庆网 - 打造属于我自己的创意婚礼 - 南昌婚纱摄影</title>
<meta name="keywords" content="南昌婚庆,南昌婚纱,南昌婚庆公司,南昌婚纱摄影">
<meta name="description" content="提供南昌婚庆公司_南昌婚纱摄影 南昌新娘跟妆 婚宴酒店 鲜花婚车 摄像摄影 新娘化妆跟妆 结婚婚礼优惠打折价格套餐信息">
<base target="_blank" />

</head>
<script type="text/javascript">
//指定当前组模块URL地址
var URL = '__URL__';
var APP	 =	 '__APP__';
var PUBLIC = '__PUBLIC__';
var SELF = '__SELF__';
</script>
<script type="text/javascript" src="../Public/Js/base.js"></script><!--前台公共JS函数-->
<script type="text/javascript" src="__PUBLIC__/Js/jquery.min.js"></script>

<link href="../Public/Css/news.css" rel="stylesheet" type="text/css" />
<link href="../Public/Css/base.css" rel="stylesheet" type="text/css" />
<link href="../Public/Css/class_com.css" rel="stylesheet" type="text/css" />
<link href="../Public/Css/select_box.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../Public/Js/pic_scroll.js"></script><!--楼盘展示效果-->

<body>

<!--start0791新闻公共头部-->
<!--%%当前是否在首页嵌入为1在首页-->
<?php if(($hide)  ==  "0"): ?><!--start腾房公共头部-->
<!-- 公共顶部 -->
<div id="tf_head">
	<div class="th_center white_a">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
	    <tr>
	      <td width="64%"><a href="__APP__/">0791婚庆网首页</a> | <a href="__APP__/News/">南昌婚庆资讯</a> | <a href="__APP__/Shop">商家大全</a> | <a href="__APP__/Check_error/" target="_blank">欢迎纠错</a></td>
	      <td width="5%">&nbsp;</td>
	      <td width="31%" align="right">
          <?php if($userInfo){ ?> 
您好， <strong><?php echo ($userInfo["username"]); ?></strong> <a href="__APP__/Account/logout/">退出</a>
<?php } else{ ?>
          <a href="__APP__/Reg/">注册</a> | <a href="__APP__/Login/index/fromUrl/<?php echo (base64_encode(__SELF__)); ?>">登录</a><?php } ?>	
          
           | <a href="#" onclick="bookmark()">加入收藏</a>&nbsp;</td>
        </tr>
      </table>
	</div>
</div>
<!--end腾房公共头部-->
<div id="head_nv">
  <div id="head_photo1">
    <div class="height_5px"></div>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr class="green_nv_a">
        <td width="50" height="20" align="center" class="green_nv_a">&nbsp;</td>
        <td height="20" align="center" class="green_nv_a"><a href="__APP__/news-list-21">每日焦点</a></td>
        <td align="center" class="green_nv_a"><a href="__APP__/news-list-26">婚礼资讯</a></td>
        <td width="100" align="center" class="green_nv_a">&nbsp;</td>
        <td align="center" class="green_nv_a"><a href="__APP__/news-list-19">商家活动</a></td>
        <td align="center" class="green_nv_a"><a href="__APP__/news-list-13">婚礼百货</a></td>
        <td width="100" align="center">&nbsp;</td>
        <td align="center" class="green_nv_a"><a href="__APP__/news-list-29">蜜月旅行</a></td>
        <td align="center" class="green_nv_a"><a href="__APP__/news-list-htpk">话题 P K</a></td>
      </tr>
      <tr class="green_nv_a">
        <td height="20" align="center" class="green_nv_a">&nbsp;</td>
        <td align="center" class="green_nv_a"><a href="__APP__/news-list-27">时尚婚礼</a></td>
        <td align="center" class="green_nv_a"><a href="__APP__/news-list-28">婚俗礼仪</a></td>
        <td width="56" align="center" class="green_nv_a">&nbsp;</td>
        <td align="center" class="green_nv_a"><a href="__APP__/news-list-12">0791独家</a></td>
        <td height="20" align="center" class="green_nv_a"><a href="__APP__/news-list-rdtj">热点推荐</a></td>
        <td width="65" align="center">&nbsp;</td>
        <td align="center" class="green_nv_a"><a href="__APP__/Reviews/">商家点评</a></td>
        <td align="center" class="green_nv_a"><a href="__APP__/Ask/">解答疑问</a></td>
      </tr>
    </table>
  </div>
</div>
<div class="height_18px_text"></div>

<div id="head_search">
  <div class="pic_nv_bn"><a href="__APP__/"><img src="../Public/Images/logo_news.png" title="南昌婚庆网" alt="南昌婚庆网" width="280" height="80" border="0" /></a></div>
  
<div class="width_5px_float"></div>
  <div id="search_inc">
  <div class="height_15px_text"></div>
    <table width="590" border="0" cellspacing="0" cellpadding="0">
       <form action="__APP__/Search" method="post" id="searchForm" onsubmit="return submit_search();">
      <tr>
        <td width="9" align="right">&nbsp;</td>
        <td width="145" height="50"><div id="search_type_inc"></div></td>
        <td width="351" height="50"><label for="key"></label>
        <input type="text" name="key" id="key" /></td>
        <td width="" height="50"><input type="image" name="search_sub" id="search_sub" src="../Public/Images/search_news_bt.png" /></td>
      </tr>
      </form>
    </table>
  </div>
  <div class="width_5px_float"></div>

</div>
<div class="height_10px"></div>
<script type="text/javascript" src="../Public/Js/select_box.js"></script><!--下拉框对象-->
<script type="text/javascript">
//下拉框新闻
optionNewsTextA=['新闻','商家'];//room_hx
optionNewsValueA=['news','loupan'];
var optionNews={id:'search_type_inc',inputId:'search_type',defText:'新闻',defVal:'news',text:optionNewsTextA,value:optionNewsValueA,beforeHtml:'',afterHtml:'',width:'auto',height:
'auto'};
var optionRoomAreaObject;//房屋面积
$(function(){
	optionNewsObject=new selectBox(optionNews);
});


//提交搜索
function submit_search(){
	var key = $("input:[name='key']").val();
	if(key == '请输入搜索条件'){
		key = '';
	}
	key = encodeURI(encodeURI(key));
	var search_type = $("input:[name='search_type']").val();
	if(search_type == 'news'){
		location.href = '__APP__/Search/index?&search_type='+ search_type +'&key='+ key ;
	}else{
		location.href = '__APP__/Shop/index?&search_type='+ search_type +'&key='+ key ;
	}
	return false;
}

</script>

<?php else: ?>

	<!--start腾房公共头部-->
<div id="tf_head">
	<div class="th_center white_a">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
	    <tr>
	      <td width="64%"><a href="__APP__/">0791婚庆网首页</a> | <a href="__APP__/News/">南昌婚庆资讯</a> | <a href="__APP__/Shop">商家大全</a> | <a href="__APP__/Check_error/" target="_blank">欢迎纠错</a></td>
	      <td width="5%">&nbsp;</td>
	      <td width="31%" align="right">
          <?php if($userInfo){ ?> 
您好， <strong><?php echo ($userInfo["username"]); ?></strong> <a href="__APP__/Account/logout/">退出</a>
<?php } else{ ?>
          <a href="__APP__/Reg/">注册</a> | <a href="__APP__/Login/index/fromUrl/<?php echo (base64_encode(__SELF__)); ?>">登录</a><?php } ?>	
          
           | <a href="#" onclick="bookmark()">加入收藏</a>&nbsp;</td>
        </tr>
      </table>
	</div>
</div>
<!--end腾房公共头部-->

<div id="head_nv">
  <div id="head_photo1">
    <div class="height_5px"></div>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr class="green_nv_a">
        <td width="50" height="20" align="center" class="green_nv_a">&nbsp;</td>
        <td height="20" align="center" class="green_nv_a"><a href="__APP__/news-list-21">每日焦点</a></td>
        <td align="center" class="green_nv_a"><a href="__APP__/news-list-26">婚礼资讯</a></td>
        <td width="100" align="center" class="green_nv_a">&nbsp;</td>
        <td align="center" class="green_nv_a"><a href="__APP__/news-list-19">商家活动</a></td>
        <td align="center" class="green_nv_a"><a href="__APP__/news-list-13">婚礼百货</a></td>
        <td width="100" align="center">&nbsp;</td>
        <td align="center" class="green_nv_a"><a href="__APP__/news-list-29">蜜月旅行</a></td>
        <td align="center" class="green_nv_a"><a href="__APP__/news-list-htpk">话题 P K</a></td>
      </tr>
      <tr class="green_nv_a">
        <td height="20" align="center" class="green_nv_a">&nbsp;</td>
        <td align="center" class="green_nv_a"><a href="__APP__/news-list-27">时尚婚礼</a></td>
        <td align="center" class="green_nv_a"><a href="__APP__/news-list-28">婚俗礼仪</a></td>
        <td width="56" align="center" class="green_nv_a">&nbsp;</td>
        <td align="center" class="green_nv_a"><a href="__APP__/news-list-12">0791独家</a></td>
        <td height="20" align="center" class="green_nv_a"><a href="__APP__/news-list-rdtj">热点推荐</a></td>
        <td width="65" align="center">&nbsp;</td>
        <td align="center" class="green_nv_a"><a href="__APP__/Reviews/">商家点评</a></td>
        <td align="center" class="green_nv_a"><a href="__APP__/Ask">解答疑问</a></td>
      </tr>
    </table>
  </div>
</div>
<!--<div class="height_10px"></div> -->
<!-- 全屏广告 S -->

<!-- 全屏广告 E -->
<div class="height_10px"></div>

 <!--start 浮动图层-->
<div id="head_search_inc">
  <table width="960" border="0" cellpadding="0" cellspacing="0" id="head_search_bg">
   <form method="get" action="__APP__/Shop" id="search_form" target="_blank">{__NOTOKEN__}
    <tr>
      <td width="269" height="100"><a href="__APP__/" title="南昌婚庆网"><img src="../Public/Images/logo.png" alt="南昌婚庆网LOGO" width="269" height="100" border="0" /></a></td>
      <td width="620" height="100" align="left" valign="middle" ><table width="600" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td height="35"><table width="100%" border="0" cellpadding="0" cellspacing="0" >
            <tr>
              <td width="140" height="35" align="left" bgcolor="#FFFFFF"><div id="shop_cls_id"></div></td>
              <td height="35" bgcolor="#FFFFFF"><input name="key" type="text" class="input_max" id="key" autocomplete="off" value="请输入搜索条件" /></td>
              <td width="" height="35" bgcolor="#FFFFFF">
                <input type="submit" width="80" height="40" class="sub" id="sub" onMouseDown="this.className='sub sub_d'" onMouseOut="this.className='sub'"   onmousemove="this.className='sub sub_h'" value="" />
             </td>
            </tr>
          </table>
            <div class="height_10px"></div>
          <table width="100%" border="0" cellpadding="0" cellspacing="0" >
            <tr>
              <td width="140" height="35" align="left" bgcolor="#FFFFFF"><div id="range_id_inc"></div></td>
              <td width="140" height="35" align="left" bgcolor="#FFFFFF"><div id="lp_price_range_inc"></div></td>
              <td width="">&nbsp;</td>
              <!-- <td width="23" height="35"><input type="radio" name="lp_state" id="lp_state1" value="1" /></td>
              <td width="33" height="35"><label for="lp_state1">在售</label></td>
              <td width="20" height="35"><input type="radio" name="lp_state" id="lp_state2" value="2" /></td>
              <td width="37" height="35"><label for="lp_state2">待售</label></td>
              <td width="20" height="35"><input name="lp_state" type="radio" id="lp_state3" value="" checked="checked" /></td>
              <td width="36" height="35"><label for="lp_state3">全部</label></td> -->
              <td width="" height="35"><!--<div id="sea_more">&nbsp;&nbsp;更多搜索条件</div> --></td>
            </tr>
        </table></td>
        </tr>
      </table></td>
    </tr>
    </form>
  </table>
</div>
<div class="height_8px"></div>

<!--<div class="height_10px"></div> -->
<script type="text/javascript" src="../Public/Js/select_box.js"></script><!--下拉框对象-->
<script type="text/javascript">
//============配置区=====================
var WEBROOT='__ROOT__';
var loadImage= '../Public/Images/load.gif'; //-----ajax加载时显示的图片
var loadImageF= '../Public/Images/load_f.png'; //-----ajax加载时显示的图片
var mapLoadURL="__URL__/map";  //加载地图所在的网页路径
var op=1;
var loadAdTime=3000;  //加载广告图片的时间
var isMoveSea=0;           //检查鼠标是否在搜索图层上
var isMoveSea1=0;        //兼容控件
var isUseHelpOnce='1';  //是否关闭新手上路2011-5-24
//============end==配置=======================
</script>

<script type="text/javascript" src="__PUBLIC__/Data/search_data.js"></script><!--搜索列表数据缓存-->
<!-- 用于词联想导入库开始 -->
	<link type="text/css" href="__PUBLIC__/Js/Util/jquery.autocomplete.css" rel="stylesheet" />
	<script type="text/javascript" src="__PUBLIC__/Js/Util/jquery.autocomplete.js"></script>
<!-- 用于词联想导入库结束 -->

<script type="text/javascript">
var priceHtml='<div class="custum_price">  <table width="220" border="0" cellpadding="0" cellspacing="0">    <tr>      <td width="63" height="25" align="right"><label for="min_price"></label>      <input name="min_price" type="text" class="select_min_input" id="min_price" /></td>      <td width="10" height="25" align="center">-</td>      <td width="53" height="25"><label for="max_price"></label>      <input name="max_price" type="text" class="select_min_input" id="max_price" /></td>      <td width="42">元</td>      <td width="82" height="25"><img src="../Public/Images/select_ok.gif" width="60" height="25" style="cursor:pointer" onclick="sendPrice()" /></td>    </tr>  </table></div>';

//要创建的全局下拉框对象列表相关值

var optionRangeId={id:'range_id_inc',inputId:'range_id',defText:'-不限外景地-',defVal:'',text:optionRangeIdTextA,value:optionRangeIdValueA,beforeHtml:'',afterHtml:'',width:'138',height:'auto'};
//商家类型
var optionLpWuyetype={id:'shop_cls_id',inputId:'shop_cls_id',defText:'-商家类型-',defVal:'',text:optionLpWuyetypeTextA,value:optionLpWuyetypeValueA,beforeHtml:'',afterHtml:'',width:'138',height:'auto'};
//价格
var  optionLpPriceRange={id:'lp_price_range_inc',inputId:'lp_price_range',defText:'-预算价格-',defVal:'',text:optionLpPriceRangeTextA,value:optionLpPriceRangeValueA,beforeHtml:'',afterHtml:priceHtml,width:'220',height:'auto'};


//要创建的全局下拉框对象
var optionRangeIdObject;//区域
var optionLpWuyetypeObject;//物业类型
var  optionLpPriceRangeObject;//均价
$(function(){
	optionRangeIdObject=new selectBox(optionRangeId);
	optionLpWuyetypeObject=new selectBox(optionLpWuyetype);
	optionLpPriceRangeObject=new selectBox(optionLpPriceRange);
});

//价格范围自定义传入值;
function sendPrice(){
	var minPrice=$('#min_price').val().replace(/[ ]+/g,'');
	var maxPrice=$('#max_price').val().replace(/[ ]+/g,'');
	if(minPrice==''||maxPrice==''){
		alert('请输入数字');
		return;
	}
	
	if(/[^0-9]+/.test(minPrice)||/[^0-9]+/.test(minPrice)){
		alert('请输入有效数字');
		return;	
	}
	if(Number(minPrice)>Number(maxPrice)){
		alert('您的输入不符合要求');
		return;		
		
	}

	var price = minPrice+'-'+maxPrice;
	optionLpPriceRangeObject.sendValue(text=price,value=price)		
}
</script>

<script type="text/javascript">
$(function(){

	/*------------输入框效果---------------*/
	$("input").focus(function(){
		var val=$(this).val();
		switch(val){
			case '请输入搜索条件':	$(this).val('');		
			break;
		}
	});
	$("#key").blur(function(){
		if($(this).val()==''){
			$(this).val('请输入搜索条件');
		}
	});	
	/*------------end----输入框效果---------------*/
	
		//增加搜索键盘支持
	   $('#search').keyup(function(event) {
			  if(event.keyCode==13){    
					$("#sub").click();
			  }	   
	   });
	
 //词联想-----------start 
  //输入框事件
	function format(msg) {
		return " [" + msg.area + "]" + msg.label;
	}
	
	$("#key").autocomplete('__APP__/index.php/Index/get_info_mnemonic/', {
		multiple: false,
		dataType: "json",
		minChars: 1,
		parse: function(data) {
			return $.map(data, function(row) {
				return {
					data: row,
					value: row.name,
					result: row.label
				}
			});
		},
		formatItem: function(item) {
			return format(item);
		}
	});

//词联想-----------end 	


//提交普通搜索 -- xiongy
$("#search_form").submit(function(){
		var wd = $("#key").val();
		if(wd == '请输入搜索条件') wd ='' ;

		$("#key").val(wd);
		var lp_state=$('#lp_state').val();
		if(!lp_state){ lp_state='';}
		var url='__APP__/Shop?key='+encodeURI($('#key').val())+'&range_id='+encodeURI($('#range_id').val())+'&shop_cls_id='+encodeURI($('#shop_cls_id').val())+'&price_range='+encodeURI($('#lp_price_range').val());
		//alert($('#lp_price_range').val());
		window.open(url,'_blank');
		return false;
});

});
</script>
<style type="text/css" >
#head_nv_inc {
	background-image: url(../Public/Images/header_nv_bg.png);
	background-repeat: repeat-x;
	height: 50px;
	width: 960px;
	background-color: #FFF;
	margin-right: auto;
	margin-left: auto;
	text-align: left;
}
.head_nv_left {
	background-image: url(../Public/Images/header_nv_bgleft.png);
	background-repeat: no-repeat;
	background-position: left top;
}
.head_nv_right {
	background-image: url(../Public/Images/header_nv_bgright.png);
	background-repeat: no-repeat;
	background-position: right top;
}
.head_nv_c {
	background-image: url(../Public/Images/header_nv_c.png);
	background-repeat: no-repeat;
	height: 50px;
	width: 100px;
	color: #FFF;
	font-size: 14px;
	font-family: "微软雅黑";
	text-align: center;
	line-height: 35px;
	cursor:pointer;
}
.head_nv_m {
	background-image: url(../Public/Images/head_nv_m.png);
	background-repeat: no-repeat;
	height: 50px;
	width: 100px;
	font-size: 14px;
	text-align: center;
	line-height: 35px;
	cursor:pointer;	
}
.head_nv {
	height: 50px;
	width: 100px;
	font-size: 14px;
	text-align: center;
	line-height: 35px;
	cursor:pointer;
}
#head_search_inc {
	height: 110px;
	width: 960px;
	margin-right: auto;
	margin-left: auto;
	background-color: #FFF;
}

#range_id_inc {
	height: 35px;
	width: 140px;
	float: left;
}
#shop_cls_id {
	height: 35px;
	width: 140px;
}
#lp_price_range_inc {
	height: 35px;
	width: 140px;
}
.input_max {
	line-height: 33px;
	height: 33px;
	width: 398px;
	color: #F20018;
	border: 0px solid #FFF;
	float: left;
	padding-top: 0px;
	padding-right: 0px;
	padding-bottom: 0px;
	padding-left: 10px;
}
#head_search_bg {
	background-image: url(../Public/Images/head_search_bg.png);
	background-color: #FFF;
	background-repeat: no-repeat; font-weight:normal;
}

.sub{width:80px;height:40px;padding:0;border:0; background:#fff url(../Public/Images/sea_button.png) 0 0px;cursor:pointer;}
.sub_h{background:#fff url(../Public/Images/sea_button_m.png) 0 0px;}
.sub_d{background:#fff url(../Public/Images/sea_button_c.png) 0 0px;}
</style>

    <div id="head_ad">
    <!-- 通栏广告start -->
		<!-- include file="Ad:index_search_end"   -->
	<!-- 通栏广告end -->
		<div class="clear"></div>
    </div>
     <!--end 浮动图层--><?php endif; ?>
<!--end0791新闻公共头部-->

<div class="content_inc">
  <div class="width_300px_float">
   <!--start幻灯片--><!--end幻灯片-->
  <script type="text/javascript">
    var t = n = 0, count;
    $(document).ready(function(){    
        count=$("#banner_list a").length;
        $("#banner_list a:not(:first-child)").hide();
        $("#banner_info").html($("#banner_list a:first-child").find("img").attr('alt'));
        $("#banner_info").click(function(){window.open($("#banner_list a:first-child").attr('href'), "_blank")});
        $("#banner li").click(function() {
            var i = $(this).text() - 1;//获取Li元素内的值，即1，2，3，4
            n = i;
            if (i >= count) return;
            $("#banner_info").html($("#banner_list a").eq(i).find("img").attr('alt'));
            $("#banner_info").unbind().click(function(){window.open($("#banner_list a").eq(i).attr('href'), "_blank")})
            $("#banner_list a").filter(":visible").fadeOut(500).parent().children().eq(i).fadeIn(1000);
            document.getElementById("banner").style.background="";
            $(this).toggleClass("on");
            $(this).siblings().removeAttr("class");
        });
        t = setInterval("showAuto()", 4000);
        $("#banner").hover(function(){clearInterval(t)}, function(){t = setInterval("showAuto()", 4000);});
    })
    
    function showAuto()
    {
        n = n >=(count - 1) ? 0 : ++n;
        $("#banner li").eq(n).trigger('click');
    }
</script>   
<div class="hdp_inc">
<!--幻灯片开始-->  
<div id="banner" class="slide_pic">    
        <!--<div id="banner_bg"></div>  标题背景-->
        <!--<div id="banner_info"></div> 标题-->
        <ul>
            <li class="on">1</li>
            <li>2</li>
            <li>3</li>
            <li>4</li>
            <li>5</li>
            <li>6</li>                        
        </ul>
       <div id="banner_list">
		  <?php if(is_array($news["lp_hdp"]["titles"])): $i = 0; $__LIST__ = $news["lp_hdp"]["titles"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><a href="<?php echo (get_arc_url($vo["id"], $vo['redirecturl'])); ?>" target="_blank">
			 <img src="__PUBLIC__/Upload/News/<?php echo ($vo["pic_url"]); ?>" width="292" height="230" class="padding_2px" title="<?php echo (mystr_replace($vo["title"])); ?>" alt="幻灯" />
			 </a><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
    </div>
<!--幻灯片结束-->  

   <div class="height_30px_text" style="width:90%;margin-left:5%;text-align:center"><a href="<?php echo (get_arc_url($news["lp_hdp"]["h"]["id"], $news['lp_hdp']['h']['redirecturl'])); ?>"><span class="green_14pxf_yahei"><?php echo (mb_substr($news["lp_hdp"]["h"]["title"],0,17,'utf-8')); ?></span></a></div>
   <div class="height_22px_text" style="width:90%;margin-left:5%;">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo (mb_substr($news["lp_hdp"]["h"]["remark"],0,38,'utf-8')); ?><a href="<?php echo (get_arc_url($news["lp_hdp"]["h"]["id"], $news['lp_hdp']['h']['redirecturl'])); ?>"><span class="red_s_a">[详细]</span></a></div>
</div>
    <div class="clear"></div><!--自动拉伸width_300px_float-->
  </div>
  <div class="width_15px_float"></div>
  <div class="width_360px_float">  
    
    
    <!--今日要闻标题-->
      <div id="yaoweng_title">
        <div class="yaoweng_title_nv_nor" id="jryw">今日焦点</div>
        <!-- <div class="yaoweng_title_nv_nor" id="qgls">全国楼市</div> -->
      </div>
      <div class="height_5px"></div>
      <!--今日要闻内容-->
<div id="jryw_inc">
          <div class="height_40px_text" align="center"><span class="green_20pxf_yahei green_a"><a href="<?php echo (get_arc_url($news["jryw"]["h"]["id"], $news['jryw']['h']['redirecturl'])); ?>" title="<?php echo (mystr_replace($news["jryw"]["h"]["title"])); ?>"><?php echo (mb_substr($news["jryw"]["h"]["title"],0,17,'utf-8')); ?></a></span></div><!--今日要闻头条-->
          <div class="height_20px_text" align="center"><span class="green_a">
          
          <?php if(is_array($news["jryw"]["t"])): $i = 0; $__LIST__ = $news["jryw"]["t"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><a href="<?php echo (get_arc_url($vo["id"], $vo['redirecturl'])); ?>" title="<?php echo (mystr_replace($vo["title"])); ?>">[<?php echo (mb_substr($vo["title"],0,12,'utf-8')); ?>] </a><?php endforeach; endif; else: echo "" ;endif; ?>
          
        </span></div>
          <div class="height_5px"></div>
          <!--循环输出-->
          <?php if(is_array($news["jryw"]["titles"])): $i = 0; $__LIST__ = $news["jryw"]["titles"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><div class="height_22px_text font_14px"><span class="<?php echo (title_style($vo["title_color"])); ?>"><strong><?php echo (($vo["tag"])?($vo["tag"]):'推荐'); ?></strong> | <a href="<?php echo (get_arc_url($vo["id"], $vo['redirecturl'])); ?>" title="<?php echo (mystr_replace($vo["title"])); ?>"><?php echo (mb_substr($vo["title"],0,22,'utf-8')); ?></a></span></div><?php endforeach; endif; else: echo "" ;endif; ?>
          <!--循环-->
          
</div>
 <!--end今日要闻-->

 <div class="clear"></div><!--自动拉伸width_360px_float-->
  </div>
  <div class="width_15px_float"></div>
  
  <div class="width_270px_float">
    <div class="width_270_4bian">
        <div class="title_style1" id="tfxc_title">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="85" height="30" class="title_nv_style1">0791独家</td>
              <td width="85" height="30">&nbsp;</td>
              <td height="30">&nbsp;</td>
              <td width="44" height="30" class="black_a"><a href="__APP__/news-list-<?php echo ($news["tfdj"]["id"]); ?>">更多&gt;&gt;</a></td>
            </tr>
          </table>
        </div>
          
        <div id="tfxc_inc">
          <div class="height_5px"></div>
          <!--循环输出-->
          <?php if(is_array($news["tfdj"]["titles"])): $i = 0; $__LIST__ = $news["tfdj"]["titles"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><div class="height_22px_text">&nbsp;·<span class="<?php echo (title_style($vo["title_color"])); ?>"><a href="<?php echo (get_arc_url($vo["id"], $vo['redirecturl'])); ?>" title="<?php echo (mystr_replace($vo["title"])); ?>"><?php echo (mb_substr($vo["title"],0,20,'utf-8')); ?></a></span></div><?php endforeach; endif; else: echo "" ;endif; ?>
          <!--循环-->            
            <div class="height_5px"></div>                             
        </div>
        <div class="clear"></div><!--自动拉伸width_270_4bian-->
      </div>
    <div class="clear"></div><!--自动拉伸width_270px_float-->
  </div>
	<div class="clear"></div>
</div>

<!--start---此入插入通栏广告二-->
<center>
<?php if(($hide)  ==  "1"): ?><!-- <div class="ad_head" style="padding-top:0px">
		<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="960" height="70">
          <param name="movie" value="http://img.tengfang.net/ad/flash/qyh0421.swf" />
          <param name="quality" value="high" />
          <param name="wmode" value="transparent">  这里代码可使Flash背景透明  
		  <embed src="http://img.tengfang.net/ad/flash/qyh0421.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="960" height="70" wmode="transparent"></embed>
        </object>
</div> --><?php endif; ?>
</center>
<!--end---此入插入通栏广告二-->

<div class="height_10px"></div>
<div class="content_inc">
  <div class="width_300px_float"><!--start活动提醒--><!--end活动提醒--><!--0791活动-->
    <div id="tfhd">
      <div class="title_style5" id="tfxc_title2">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="94" height="30" align="center" class="green_a"><a href="__APP__/news-list-<?php echo ($news["sjhd"]["id"]); ?>" class="green_14px_yahei">商家活动</a></td>
	        <td width="202" height="30" align="right" class="black_a"><a href="http://weibo.com/tengfangwang" target="_blank" class="black_12px" rel="nofollow">关注0791婚庆网微博</a>&nbsp;&nbsp;</td>
          </tr>
        </table>
      </div>
      <div class="height_5px"></div>
      <div class="tfhd_inc">
      		<div class="width_90px_float">
      		  <div class="height_5px"></div>
      		  <a href="__APP__/news-list-<?php echo ($news["sjhd"]["id"]); ?>"><img src="../Public/Images/show_tfhd.jpg" width="90" height="100" border="0"  alt="0791活动"/></a>
      		  <div class="height_5px"></div>
</div>
            
            <div class="width_190px_float">
                <!--循环输出-->
              <?php if(is_array($news["sjhd"]["titles"])): $i = 0; $__LIST__ = $news["sjhd"]["titles"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><div class="height_22px_text">&nbsp;·<span class="<?php echo (title_style($vo["title_color"])); ?>"><a href="<?php echo (get_arc_url($vo["id"], $vo['redirecturl'])); ?>" title="<?php echo (mystr_replace($vo["title"])); ?>"><?php echo (mb_substr($vo["title"],0,14,'utf-8')); ?></a></span></div><?php endforeach; endif; else: echo "" ;endif; ?>
              <!--循环--> 
              <div class="clear"></div>
          </div>
      		<div class="clear"></div>
      </div>
      <div class="height_5px"></div>
      <div class="tfhd_inc hide" id="zxlp_inc">
		<div class="clear"></div>
      </div>      
      <div class="clear"></div>
    </div>
    <!--end活动-->
    <div class="clear"></div><!--自动拉伸width_300px_float-->
  </div>
  <div class="width_15px_float"></div>
  <div class="width_645px_float">
    <div class="width_360px_float"><!--楼盘导购标题-->
<div id="daogou_title" class="heigh_30px_white">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="100" height="30" class="title_nv" id="tfkx">婚礼百货</td>
      <td width="190" height="30">&nbsp;</td>
      <td width="63" class="black_a"></td>
    </tr>
  </table>
</div>
<!--楼盘导购内容-->
<div id="tfkx_inc" class="relative" >
<div class="more black_a"><a href="__APP__/news-list-<?php echo ($news["hlbh"]["id"]); ?>">更多&gt;&gt;</a></div>
  <div class="height_5px"></div>
          <!--循环输出-->
		<?php if(is_array($news["hlbh"]["titles"])): $i = 0; $__LIST__ = $news["hlbh"]["titles"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><div class="height_22px_text font_14px">·<span class="<?php echo (title_style($vo["title_color"])); ?>"><a href="__APP__/news-<?php echo ($vo["id"]); ?>" title="<?php echo (mystr_replace($vo["title"])); ?>"><?php echo (mb_substr($vo["title"],0,24,'utf-8')); ?></a></span></div><?php endforeach; endif; else: echo "" ;endif; ?>
          <!--循环-->  
</div>
<div class="height_10px"></div>
<div class="clear"></div><!--自动拉伸width_360px_float-->
    </div>
    <div class="width_15px_float"></div>
    <div class="width_270px_float">
      <div class="width_270_4bian">

        <div class="title_style1" id="rdtj_title">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="85" height="30" class="title_nv_style1" id="rdtj">热点推荐</td>
              <td height="30">&nbsp;</td>
              <td width="44" height="30" ></td>
            </tr>
          </table>
          <div class="clear"></div><!--自动拉伸width_270_4bian-->
        </div>
        <div id="rdtj_inc" class="relative">
        
        <div class="more black_a"><a href="__APP__/news-list-<?php echo ($news["rdtj"]["id"]); ?>">更多&gt;&gt;</a></div>
        
                  <div class="height_5px"></div>
        
        <table width="98%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="51%" rowspan="2" align="center" valign="middle">
            <div id="photo_94_124"><a href="__APP__/news-<?php echo ($news["rdtj"]["h"]["id"]); ?>"><img src="__PUBLIC__/Upload/News/thumb_<?php echo ($news["rdtj"]["h"]["pic_url"]); ?>" width="120" height="90" vspace="2" border="0" alt="热点" /></a></div>
            </td>
            <td width="49%" class="red_a height_20px_text"><strong><a href="__APP__/news-<?php echo ($news["rdtj"]["h"]["id"]); ?>" title="<?php echo (mystr_replace($news["rdtj"]["h"]["title"])); ?>"><?php echo (mb_substr($news["rdtj"]["h"]["title"],0,18,'utf-8')); ?></a></strong></td>
          </tr>
          <tr>
            <td><span class="height_18px_text"> &nbsp;&nbsp;&nbsp;<span class="gray_12px_fs"><?php echo (mb_substr($news["rdtj"]["h"]["remark"],0,24,'utf-8')); ?>...</span><span class="red_s_a"><a href="__APP__/news-<?php echo ($news["rdtj"]["h"]["id"]); ?>">[详细]</a></span></span></td>
          </tr>
        </table>
          <!--循环输出-->
          <?php if(is_array($news["rdtj"]["titles"])): $i = 0; $__LIST__ = $news["rdtj"]["titles"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><div class="height_22px_text">&nbsp;·<span class="<?php echo (title_style($vo["title_color"])); ?>"><a href="__APP__/news-<?php echo ($vo["id"]); ?>" title="<?php echo (mystr_replace($vo["title"])); ?>"><?php echo (mb_substr($vo["title"],0,20,'utf-8')); ?></a></span></div><?php endforeach; endif; else: echo "" ;endif; ?>
          <!--循环--> 
                                           
        </div>
        <div class="clear"></div><!--自动拉伸width_270_4bian-->
      </div>
      
      <div class="clear"></div><!--自动拉伸width_270px_float-->
    </div>
    <div class="clear"></div><!--自动拉伸width_645px_float-->
  </div>
<div class="clear"></div><!--自动拉伸content_inc-->
<!-- 广告位3开始 -->

<!-- 广告位3结束 -->
</div>

<div class="height_10px"></div>

<div class="content_style2_inc">

  <div class="width_100bf_4bian">
    <div class="title_style5" id="tfxc_title3">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="150" height="30" align="center" class="green_a"><a href="__APP__/Pic/index/cid/18" class="green_14px_yahei">婚纱摄影套系展示</a></td>
          <td width="808" height="30" align="right" class="black_a"><a href="__APP__/Pic/index/cid/18" class="black_12px">更多..</a>&nbsp;</td>
        </tr>
      </table>
    </div>
    <div class="height_10px"></div>
    <div class="width_940px_auto">
    <!--lp3d-->
    <?php if(is_array($pic_list[18])): $i = 0; $__LIST__ = array_slice($pic_list[18],0,10,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><a href="__APP__/pics-<?php echo ($vo["id"]); ?>"><img src="<?php echo ($vo["thumb_pic"]); ?>" alt="<?php echo ($vo["title"]); ?>" width="125" height="125" border="0" class="img_box float_left" title="<?php echo (mystr_replace($vo["title"])); ?>" /></a>
      <div class="width_10px_float"></div><?php endforeach; endif; else: echo "" ;endif; ?>
	<div class="width_5px_float"></div>
    <div class="clear"></div>
    <div class="height_10px"></div>
    <div class="clear"></div>
    </div>
  </div> <!--end width_100bf_4bian-->


  <div class="width_100bf_4bian">
    <div class="title_style5" id="tfxc_title3">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="125" height="30" align="center" class="green_a"><a href="__APP__/Pic/index/cid/35" class="green_14px_yahei">流行婚纱礼服</a></td>
          <td width="833" height="30" align="right" class="black_a"><a href="__APP__/Pic/index/cid/35" class="black_12px">更多..</a>&nbsp;</td>
        </tr>
      </table>
    </div>
    <div class="height_10px"></div>
    <div class="width_940px_auto">
    <!--lp3d-->
    <?php if(is_array($pic_list[35])): foreach($pic_list[35] as $key=>$vo): ?><h1><a href="__APP__/pics-<?php echo ($vo["id"]); ?>"><img src="<?php echo ($vo["thumb_pic"]); ?>" alt="<?php echo ($vo["title"]); ?>" width="125" height="125" border="0" class="img_box float_left" title="<?php echo (mystr_replace($vo["title"])); ?>" /></a>
      </h1>
      <div class="width_10px_float"></div><?php endforeach; endif; ?>
    <div class="width_5px_float"></div>
    <div class="clear"></div>
    <div class="height_10px"></div>
    <div class="clear"></div>
    </div>
  </div> <!--end width_100bf_4bian-->


  <div class="width_100bf_4bian">
    <div class="title_style5" id="tfxc_title3">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="150" height="30" align="center" class="green_a"><a href="__APP__/news-list-37" class="green_14px_yahei">婚庆现场视频鉴赏</a></td>
          <td width="808" height="30" align="right" class="black_a"><a href="__APP__/news-list-37" class="black_12px">更多..</a>&nbsp;</td>
        </tr>
      </table>
    </div>
    <div class="height_10px"></div>
    <div class="width_940px_auto">
    <!--lp3d-->
    <?php if(is_array($tv_list)): $i = 0; $__LIST__ = array_slice($tv_list,0,10,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><a href="__APP__/news-<?php echo ($vo["id"]); ?>"><img src="__PUBLIC__/Upload/News/<?php echo ($vo["pic_url"]); ?>" alt="<?php echo ($vo["title"]); ?>" width="140" height="125" border="0" class="img_box float_left" title="<?php echo (mystr_replace($vo["title"])); ?>" /></a>
	  <!-- <br /><?php echo ($vo["title"]); ?> -->
      <div class="width_10px_float"></div><?php endforeach; endif; else: echo "" ;endif; ?>
    <div class="width_5px_float"></div>
    <div class="clear"></div>
    <div class="height_10px"></div>
    <div class="clear"></div>
    </div>
  </div> <!--end width_100bf_4bian-->


  
  <div class="height_10px"></div>
  <div class="width_315px_g4b">
    <div class="heigh_30px_white">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="82" height="30" class="title_nv">奇闻轶事</td>
          <td width="193" height="30" class="title_nv_nor">&nbsp;</td>
          <td width="44" height="30"><span class="black_a"><a href="__APP__/news-list-<?php echo ($news["qwys"]["id"]); ?>">更多&gt;&gt;</a></span>    
        </tr>
      </table>
    </div>
          <div class="height_5px"></div>
          <!--循环输出-->
          <?php if(is_array($news["qwys"]["titles"])): $i = 0; $__LIST__ = $news["qwys"]["titles"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><div class="height_22px_text">&nbsp;·<span class="<?php echo (title_style($vo["title_color"])); ?>"><a href="__APP__/news-<?php echo ($vo["id"]); ?>" title="<?php echo (mystr_replace($vo["title"])); ?>"><?php echo (mb_substr($vo["title"],0,24,'utf-8')); ?></a></span></div><?php endforeach; endif; else: echo "" ;endif; ?>
          <!--循环-->            
            <div class="height_5px"></div> 
          <div class="clear"></div>
  </div>
  <div class="width_5px_float"></div>
  <div class="width_315px_g4b">
    <div class="heigh_30px_white" id="dazui_title">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
      	  <td width="80" height="30" class="title_nv_nor" id="hlbh">爱情故事</td>
          <!-- <td width="80" class="title_nv_nor" id="xbcp">小编踩盘</td> -->
          <td height="30" >&nbsp;</td>
          <td width="45" height="30"></td>          
        </tr>
      </table>
</div>
	<div id="dazui_inc" class="relative">
    	  <div class="more black_a"><a href="__APP__/news-list-<?php echo ($news["love"]["id"]); ?>">更多&gt;&gt;</a></div>
          <div class="height_5px"></div>
          <!--循环输出-->
          <?php if(is_array($news["love"]["titles"])): $i = 0; $__LIST__ = $news["love"]["titles"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><div class="height_22px_text">&nbsp;·<span class="<?php echo (title_style($vo["title_color"])); ?>"><a href="__APP__/news-<?php echo ($vo["id"]); ?>" title="<?php echo (mystr_replace($vo["title"])); ?>"><?php echo (mb_substr($vo["title"],0,24,'utf-8')); ?></a></span></div><?php endforeach; endif; else: echo "" ;endif; ?>
          <!--循环-->            
            <div class="height_5px"></div>
        </div>
             
    <div class="clear"></div>
  </div>
  <div class="width_5px_float"></div>
  <div class="width_315px_g4b">
    <div class="heigh_30px_white">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="82" height="30" class="title_nv">婚庆专题</td>
          <td width="193" height="30" class="title_nv_nor">&nbsp;</td>
          <td width="44" height="30"><span class="black_a"><a href="__APP__/news-list-14">更多&gt;&gt;</a></span>          
        </tr>
      </table>
    </div>
                  <div class="height_5px"></div>
        
<table width="98%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="51%" rowspan="2" align="center" valign="middle">
            <div id="photo_94_124"><a href="<?php echo (get_arc_url($news["hqzt"]["h"]["id"],$news['hqzt']['h']['redirecturl'])); ?>"><img src="__PUBLIC__/Upload/News/thumb_<?php echo ($news["hqzt"]["h"]["pic_url"]); ?>" width="120" height="90" vspace="2" border="0" alt="热点" /></a></div>
            </td>
            <td width="49%" class="red_a height_20px_text"><strong><a href="<?php echo (get_arc_url($news["hqzt"]["h"]["id"], $news['hqzt']['h']['redirecturl'])); ?>" title="<?php echo (mystr_replace($news["hqzt"]["h"]["title"])); ?>" target="_blank"><?php echo (mb_substr($news["hqzt"]["h"]["title"],0,18,'utf-8')); ?></a></strong></td>
          </tr>
          <tr>
            <td><span class="height_18px_text"> &nbsp;&nbsp;&nbsp;<span class="gray_12px_fs"><?php echo (mb_substr($news["hqzt"]["h"]["remark"],0,24,'utf-8')); ?>...</span><span class="red_s_a"><a href="<?php echo (get_arc_url($news["hqzt"]["h"]["id"], $news['hqzt']['h']['redirecturl'])); ?>" target="_blank">[详细]</a></span></span></td>
          </tr>
        </table>
        <div class="height_10px"></div>
          <!--循环输出-->
          <?php if(is_array($news["hqzt"]["titles"])): $i = 0; $__LIST__ = $news["hqzt"]["titles"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><div class="height_22px_text">&nbsp;·<span class="<?php echo (title_style($vo["title_color"])); ?>"><a href="<?php echo (get_arc_url($vo["id"], $vo['redirecturl'])); ?>" title="<?php echo (mystr_replace($vo["title"])); ?>" target="_blank"><?php echo (mb_substr($vo["title"],0,24,'utf-8')); ?></a></span></div><?php endforeach; endif; else: echo "" ;endif; ?>
          <div class="height_9px"></div>
          <!--循环--> 

    <div class="clear"></div>
  </div>
  <div class="clear"></div>
</div>

 <!--<div class="height_10px"></div>
<div class="ad_width_960px">
    -- 通栏广告start 
	-- 通栏广告end 
  <div class="clear"></div>
</div> -->
<div class="height_10px"></div>
<!--中部第一大块start-->
<div class="content_style2_inc">
<!--左边start--->
  <img src="../Public/Images/tfhd_bg.png" width="960" height="40" alt="0791互动">
  <div class="height_5px"></div>
 <div class="width_680px_float_noborder">
  <div class="width_680px_float">
    <div class="title_style5" id="tfxc_title4">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="125" height="30" align="center" class="green_a"><a href="__APP__/Reviews/" class="green_14px_yahei">商家点评</a></td>
          <td width="833" height="30" align="right" class="black_a"><a href="__APP__/news-list-<?php echo ($news["cjxw"]["id"]); ?>" class="black_12px">&nbsp;</a><a href="__APP__/Reviews/" class="black_12px">更多&gt;&gt;&nbsp;</a></td>
        </tr>
      </table>
    </div><div class="height_40px_text" align="center"><strong><span class="green_14px_yahei green_a">
    <?php if(is_array($news["zxdp"])): $i = 0; $__LIST__ = array_slice($news["zxdp"],0,1,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><a href="__APP__/Reviews/view/id/<?php echo ($vo["id"]); ?>" title="[<?php echo ($vo["lpname"]); ?>] <?php echo (mystr_replace($vo["content"])); ?>"><?php echo (mb_substr($vo["content"],0,30,'utf-8')); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
    </span></strong></div>
    <div class="width_5px_float"></div>
    <div class="width_340px_float">
        <?php if(is_array($news["zxdp"])): $i = 0; $__LIST__ = array_slice($news["zxdp"],1,9,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><div class="height_22px_text"><span >&nbsp;·<a href="__APP__/Reviews/view/id/<?php echo ($vo["id"]); ?>" title="[<?php echo ($vo["lpname"]); ?>] <?php echo (mystr_replace($vo["content"])); ?>"><?php echo (mb_substr($vo["content"],0,25,'utf-8')); ?></a></span> </div><?php endforeach; endif; else: echo "" ;endif; ?>
          <div class="height_10px"></div>
    </div>

    <div class="width_340px_float">
        <?php if(is_array($news["zxdp"])): $i = 0; $__LIST__ = array_slice($news["zxdp"],10,9,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><div class="height_22px_text"><span >&nbsp;·<a href="__APP__/Reviews/view/id/<?php echo ($vo["id"]); ?>" title="[<?php echo ($vo["lpname"]); ?>] <?php echo (mystr_replace($vo["content"])); ?>"><?php echo (mb_substr($vo["content"],0,25,'utf-8')); ?></a></span> </div><?php endforeach; endif; else: echo "" ;endif; ?>
          <div class="height_10px"></div>
    </div>
      <!--新房价格标题--><!--新房价格内容-->
   <div class="height_10px_float"></div>
   <!--区域价格标题--><!--区域价格内容-->
   <div class="clear"></div><!--自动拉伸width_210px_float-->
</div>
  <div class="height_5px_float"></div>
  <div class="width_335px_float_4b">
    <div class="title_style5" id="tfxc_title6">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="81" height="30" align="center" class="green_a"><a href="__APP__/Reviews/dp_list" class="green_14px_yahei">高人气排行</a></td>
          <td width="187" height="30" align="right" class="black_a">&nbsp;</td>
        </tr>
      </table>
    </div>
    <div class="height_5px"></div>
<div class="width_320px_float">
    <div class="height_5px"></div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
          <td width="42" height="23" align="center" valign="middle" ></td>
          <td height="23" valign="middle">商家名称</td>
          <td height="23" valign="middle">人气</td>
          <td height="23" valign="middle">点击</td>
        </tr>
	  <?php if(is_array($news["rqdp"])): $k = 0; $__LIST__ = array_slice($news["rqdp"],0,4,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$k;$mod = ($k % 2 )?><?php if(($k)  <  "4"): ?><tr>
          <td width="42" height="23" align="center" valign="middle" class="buttom_green" ><?php echo ($k); ?></td>
          <td height="23" valign="middle" title="<?php echo ($vo["name"]); ?>"><a href="__APP__/Reviews/ct_list/info_id/<?php echo ($vo["id"]); ?>"><span class="green_a"><?php echo (mb_substr($vo["name"],0,8,'utf-8')); ?>点评</span></a></td>
          <td height="23" valign="middle"><?php echo ($vo["dp_hits"]); ?></td>
          <td height="23" valign="middle"><span class="orange_12px_f"><?php echo ($vo["dp_count"]); ?></span></td>
        </tr>
        <?php else: ?>
        <tr>
          <td width="42" height="23" align="center" valign="middle" class="buttom_gray" ><?php echo ($k); ?></td>
          <td height="23" valign="middle" title="<?php echo ($vo["name"]); ?>"><a href="__APP__/Reviews/ct_list/info_id/<?php echo ($vo["id"]); ?>"><span class="green_a"><?php echo (mb_substr($vo["name"],0,8,'utf-8')); ?>点评</span></a></td>
          <td height="23" valign="middle"><?php echo ($vo["dp_hits"]); ?></td>
          <td height="23" valign="middle"><span class="orange_12px_f"><?php echo ($vo["dp_count"]); ?></span></td>
        </tr><?php endif; ?><?php endforeach; endif; else: echo "" ;endif; ?>
      </table>
    </div>
<div class="height_5px_float"></div>
 <div class="clear"></div>
  </div>
  <div class="width_5px_float"></div>
<div class="width_335px_float_4b">
    <div class="title_style5" id="tfxc_title6">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="81" height="30" align="center" class="green_a"><a href="__APP__/Reviews/dp_list" class="green_14px_yahei">高参与排行</a></td>
          <td width="187" height="30" align="right" class="black_a">&nbsp;</td>
        </tr>
      </table>
    </div>
    <div class="height_5px"></div>
<div class="width_320px_float">
    <div class="height_5px"></div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
          <td width="42" height="23" align="center" valign="middle" ></td>
          <td height="23" valign="middle">商家名称</td>
          <td height="23" valign="middle">人气</td>
          <td height="23" valign="middle">评论</td>
        </tr>
	  <?php if(is_array($news["prdp"])): $k = 0; $__LIST__ = array_slice($news["prdp"],0,4,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$k;$mod = ($k % 2 )?><?php if(($k)  <  "4"): ?><tr>
          <td width="42" height="23" align="center" valign="middle" class="buttom_green" ><?php echo ($k); ?></td>
          <td height="23" valign="middle" title="<?php echo ($vo["name"]); ?>"><a href="__APP__/Reviews/ct_list/info_id/<?php echo ($vo["id"]); ?>"><span class="green_a"><?php echo (mb_substr($vo["name"],0,8,'utf-8')); ?>点评</span></a></td>
          <td height="23" valign="middle"><?php echo ($vo["dp_hits"]); ?></td>
          <td height="23" valign="middle"><span class="orange_12px_f"><?php echo ($vo["dp_count"]); ?></span></td>
        </tr>
        <?php else: ?>
        <tr>
          <td width="42" height="23" align="center" valign="middle" class="buttom_gray" ><?php echo ($k); ?></td>
          <td height="23" valign="middle" title="<?php echo ($vo["name"]); ?>"><a href="__APP__/Reviews/ct_list/info_id/<?php echo ($vo["id"]); ?>"><span class="green_a"><?php echo (mb_substr($vo["name"],0,8,'utf-8')); ?>点评</span></a></td>
          <td height="23" valign="middle"><?php echo ($vo["dp_hits"]); ?></td>
          <td height="23" valign="middle"><span class="orange_12px_f"><?php echo ($vo["dp_count"]); ?></span></td>
        </tr><?php endif; ?><?php endforeach; endif; else: echo "" ;endif; ?>
      </table>
    </div>
<div class="height_5px_float"></div>
 <div class="clear"></div>
  </div>  
<div class="clear"></div>
</div>
<!--左边end--->
  <div class="width_10px_float"></div>
    <div class="width_270px_auto">
      <div class="title_style5" id="tfxc_title5">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="81" height="30" align="center" class="green_a">婚庆问题咨询</td>
            <td width="187" height="30" align="right" class="black_a"><a href="__APP__/Ask" class="black_12px">更多&gt;&gt;</a>&nbsp;</td>
          </tr>
        </table>
      </div>
      <div class="height_10px"></div>
      <div class="lszx_gs" id="xsph2">
        <div class="width_90px_float">
          <div class="height_5px"></div>
          <a href="__APP__/Law/"><img src="../Public/Images/show_fcwd.jpg" width="80" height="90" border="0"  alt="婚庆问答"/></a></div>
        <div class="width_160px_float">
          <!--循环输出-->
          <div class="height_22px_text">&nbsp;&nbsp;&nbsp;是否在为想去拍摄婚纱照却又因为有许多疑问而烦恼呢？请咨询0791hunqing.com有专人将为您详细、亲切的释疑
            <div class="clear"></div></div>
          <!--循环-->
          <div class="clear"></div>
        </div>
        <div class="clear"></div>
      </div>
          <!--start律师在线-->
      <div class="height_10px"></div>
      <div class="width_10px_float"></div>
      <div id="lawer">
          <div id="lawer1">
          
          <?php if(is_array($news["law"])): $i = 0; $__LIST__ = $news["law"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><div class="height_30px_text"><span class="list_style2_qpic"><strong><?php echo (mb_substr($vo["ask"],0,16,'utf-8')); ?></strong></span></div>
              <div class="height_20px_text bg_g4b" style="width:238px"><span class="black_a">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo (mb_substr(strip_tags($vo["reply"]),0,31,'utf-8')); ?>...<span class="red_s_a"><a href="__APP__/Law/detail/id/<?php echo ($vo["id"]); ?>">[详细]</a></span></span></div><?php endforeach; endif; else: echo "" ;endif; ?>
          
          </div>
          <div id="lawer2"></div>
          
      </div>
      <div class="height_10px_float"></div>
<script type="text/javascript"> 
var speedLawer;
var lawer; 
var lawer2; 
var lawer1; 
var Mylawer;
$(function(){
speedLawer=40;
lawer=document.getElementById("lawer"); 
lawer2=document.getElementById("lawer2"); 
lawer1=document.getElementById("lawer1"); 
lawer2.innerHTML = lawer1.innerHTML;
Mylawer=setInterval(MarqueeLawer,(speedLawer)); 

lawer.onmouseover=function() {clearInterval(Mylawer)} 
lawer.onmouseout=function() {Mylawer=setInterval(MarqueeLawer,speedLawer)} 
});

function MarqueeLawer(){ 
	if(lawer2.offsetTop-lawer.scrollTop<=0) {
	  lawer.scrollTop-=lawer1.offsetHeight; 
	}else{ 
	  lawer.scrollTop++;
	} 
} 

</script>         

    <!--end律师在线-->
    <div class="clear"></div>
    </div>
<!--20120791网招聘s -->

<!--20120791网招聘e -->
    
<!--右边start--><!--右边end---> 
  <div class="clear"></div><!--自动拉伸width_270px_float--> 
</div>
<!--中部第一大块结束-->
<div class="clear"></div><!--自动拉伸width_960px-->
<div class="height_10px"></div>
<!--中部第二大块开始-->
<!--热门推荐start-->
<div class="width_960px" id="lptj_inc">
<!--楼盘推荐标题--><!--楼盘推荐内容-->
 <div class="height_10px"></div>
<!--滑动start-->
<div class="lptj_outside">
<div class="height_10px"></div>

<div class="hot_lp" >

     	
      <div class="infiniteCarousel">
      <div class="wrapper">
       <ul id='imgscroll'>
       
		<?php if(is_array($news["shop_rdtj"])): $i = 0; $__LIST__ = $news["shop_rdtj"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><li><!-- 此URL转到楼盘的传统页--><a href="__APP__/shop-<?php echo ($vo["id"]); ?>" title="全称：<?php echo ($vo["name"]); ?> &#10;电话：<?php echo ($vo["tel"]); ?>"><img src="<?php echo ($vo["pic_url"]); ?>" width="145" height="100" border="0" alt="<?php echo ($vo["lpname"]); ?>" /></a><div class="black_h5" title="<?php echo ($vo["lpname"]); ?>"><?php echo (mb_substr($vo["lpname"],0,10,'utf-8')); ?></div>
					   <!--<div class="black_h5"><span class="gray_12px_f">主推:</span></div>-->
       <div class="black_h5">
	   <span class="green_12px_f" title="<?php echo ($vo["tel"]); ?>">电话：<?php if(($vo["tel"])  ==  "待定"): ?>未知<?php else: ?><strong><?php echo (substr($vo["tel"],0,14)); ?></strong><?php endif; ?></span>
       </div></li><?php endforeach; endif; else: echo "" ;endif; ?> 

       </ul>
      </div>
     </div> 
        
        
        
   </div>
<div class="clear"></div><!--自动拉伸width_960px-->
</div>
<!--滑动end-->
  <div class="clear"></div><!--自动拉伸width_960px-->
</div>
<!--热门推荐end-->
 <div class="clear"></div><!--自动拉伸width_960px-->
<!--中部第二大块结束-->
<div class="height_10px"></div>
<!--中部第三大块开始-->
<div class="content_style2_inc">

<img src="../Public/Images/dczx_bg.png" width="960" height="40" alt="地产资讯">
<div class="height_5px"></div>
<div class="width_315px_g4b">
    <div class="heigh_30px_white">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="82" height="30" class="title_nv">婚礼资讯</td>
          <td width="193" height="30" class="title_nv_nor">&nbsp;</td>
          <td width="44" height="30"><span class="black_a"><a href="__APP__/news-list-<?php echo ($news["hlzx"]["id"]); ?>">更多&gt;&gt;</a></span>          
        </tr>
      </table>
    </div>
    <div class="height_10px"></div>
    
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
         <tr>
           <td width="130" rowspan="2" align="center" valign="middle"><a href="__APP__/news-<?php echo ($news["hlzx"]["h"]["id"]); ?>"><img src="__PUBLIC__/Upload/News/thumb_<?php echo ($news["hlzx"]["h"]["pic_url"]); ?>" width="115" height="90" border="0" title="<?php echo (mystr_replace($news["hlzx"]["h"]["title"])); ?>" alt="图片:婚礼资讯" /></a></td>
           <td height="30">&nbsp;</td>
           <td width="190" height="30" align="center"><span class="red_s_a" title="<?php echo (mystr_replace($news["hlzx"]["h"]["title"])); ?>"><strong><a href="__APP__/news-<?php echo ($news["hlzx"]["h"]["id"]); ?>"><?php echo (mb_substr($news["hlzx"]["h"]["title"],0,13,'utf-8')); ?></a></strong></span></td>
           <td height="30">&nbsp;</td>
         </tr>
         <tr>
           <td height="50">&nbsp;</td>
           <td width="190" height="50">&nbsp;&nbsp;&nbsp;<span class="gray_12px_fs" title="<?php echo (mystr_replace($news["hlzx"]["h"]["remark"])); ?>"><?php echo (mb_substr($news["hlzx"]["h"]["remark"],0,21,'utf-8')); ?>...</span><span class="red_s_a"><a href="__APP__/news-<?php echo ($news["hlzx"]["h"]["id"]); ?>">[详细]</a></span></td>
           <td height="50">&nbsp;</td>
         </tr>
         <tr>
           <td colspan="4">&nbsp;</td>
         </tr>
         <tr>
           <td height="138" colspan="4" valign="top"><?php if(is_array($news["hlzx"]["titles"])): $i = 0; $__LIST__ = $news["hlzx"]["titles"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><div class="height_22px_text">&nbsp;<span class="<?php echo (title_style($vo["title_color"])); ?>" title="<?php echo (mystr_replace($vo["title"])); ?>"><a href="__APP__/news-<?php echo ($vo["id"]); ?>">&middot;<?php echo (mb_substr($vo["title"],0,26,'utf-8')); ?></a></span></div><?php endforeach; endif; else: echo "" ;endif; ?></td>
         </tr>
    </table>
       <div class="height_10px"></div>
    <div class="clear"></div>
  </div>
<div class="width_5px_float"></div>
<div class="width_315px_g4b">
  <div class="heigh_30px_white">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="82" height="30" class="title_nv">时尚婚礼</td>
        <td width="193" height="30" class="title_nv_nor">&nbsp;</td>
        <td width="44" height="30"><span class="black_a"><a href="__APP__/news-list-<?php echo ($news["sshl"]["id"]); ?>">更多&gt;&gt;</a></span>                
      </tr>
    </table>
  </div>
  <div class="height_10px"></div>
  
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
         <tr>
           <td width="130" rowspan="2" align="center" valign="middle"><a href="__APP__/news-<?php echo ($news["sshl"]["h"]["id"]); ?>"><img src="__PUBLIC__/Upload/News/thumb_<?php echo ($news["sshl"]["h"]["pic_url"]); ?>" width="115" height="90" border="0" title="<?php echo (mystr_replace($news["sshl"]["h"]["title"])); ?>" alt="图片:时尚婚礼"/></a></td>
           <td height="30">&nbsp;</td>
           <td width="190" height="30" align="center"><span class="red_s_a" title="<?php echo (mystr_replace($news["sshl"]["h"]["title"])); ?>"><strong><a href="__APP__/news-<?php echo ($news["sshl"]["h"]["id"]); ?>"><?php echo (mb_substr($news["sshl"]["h"]["title"],0,13,'utf-8')); ?></a></strong></span></td>
           <td height="30">&nbsp;</td>
         </tr>
         <tr>
           <td height="50">&nbsp;</td>
           <td width="190" height="50"><span class="height_22px_text">&nbsp;&nbsp;&nbsp;<span class="gray_12px_fs" title="<?php echo (mystr_replace($news["sshl"]["h"]["remark"])); ?>"><?php echo (mb_substr($news["sshl"]["h"]["remark"],0,21,'utf-8')); ?>...</span><span class="red_s_a"><a href="__APP__/news-<?php echo ($news["sshl"]["h"]["id"]); ?>">[详细]</a></span></td>
           <td height="50">&nbsp;</td>
         </tr>
         <tr>
           <td colspan="4">&nbsp;</td>
         </tr>
         <tr>
           <td height="138" colspan="4" valign="top"><?php if(is_array($news["sshl"]["titles"])): $i = 0; $__LIST__ = $news["sshl"]["titles"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><div class="height_22px_text">&nbsp;<span class="<?php echo (title_style($vo["title_color"])); ?>" title="<?php echo (mystr_replace($vo["title"])); ?>"><a href="__APP__/news-<?php echo ($vo["id"]); ?>">&middot;<?php echo (mb_substr($vo["title"],0,26,'utf-8')); ?></a></span></div><?php endforeach; endif; else: echo "" ;endif; ?></td>
         </tr>
    </table>
  <div class="height_10px"></div>
  <div class="clear"></div>
</div>
<div class="width_5px_float"></div>
<div class="width_315px_g4b">
  <div class="heigh_30px_white">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="82" height="30" class="title_nv">婚礼秘书</td>
        <td width="193" height="30" class="title_nv_nor">&nbsp;</td>
        <td width="44" height="30"><span class="black_a"><a href="__APP__/news-list-<?php echo ($news["hlms"]["id"]); ?>">更多&gt;&gt;</a></span>        
      </tr>
    </table>
  </div>
  <div class="height_10px"></div>
  
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="130" rowspan="2" align="center" valign="middle"><a href="__APP__/news-<?php echo ($news["hlms"]["h"]["id"]); ?>"><img src="__PUBLIC__/Upload/News/thumb_<?php echo ($news["hlms"]["h"]["pic_url"]); ?>" width="115" height="90" border="0" title="<?php echo (mystr_replace($news["hlms"]["h"]["title"])); ?>" alt="图片:婚礼秘书"/></a></td>
            <td height="30">&nbsp;</td>
            <td width="190" height="30" align="center"><span class="red_s_a" title="<?php echo (mystr_replace($news["hlms"]["h"]["title"])); ?>"><strong><a href="__APP__/news-<?php echo ($news["hlms"]["h"]["id"]); ?>"><?php echo (mb_substr($news["hlms"]["h"]["title"],0,13,'utf-8')); ?></a></strong></span></td>
            <td height="30">&nbsp;</td>
          </tr>
          <tr>
            <td height="50">&nbsp;</td>
            <td width="190" height="50"><span class="height_22px_text">&nbsp;&nbsp;&nbsp;<span class="gray_12px_fs" title="<?php echo (mystr_replace($news["hlms"]["h"]["remark"])); ?>"><?php echo (mb_substr($news["hlms"]["h"]["remark"],0,21,'utf-8')); ?>...</span><span class="red_s_a"><a href="__APP__/news-<?php echo ($news["hlms"]["h"]["id"]); ?>">[详细]</a></span></td>
            <td height="50">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4">&nbsp;</td>
          </tr>
          <tr>
            <td height="138" colspan="4" valign="top"><?php if(is_array($news["hlms"]["titles"])): $i = 0; $__LIST__ = $news["hlms"]["titles"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><div class="height_22px_text">&nbsp;<span class="<?php echo (title_style($vo["title_color"])); ?>" title="<?php echo (mystr_replace($vo["title"])); ?>"><a href="__APP__/news-<?php echo ($vo["id"]); ?>">&middot;<?php echo (mb_substr($vo["title"],0,26,'utf-8')); ?></a></span></div><?php endforeach; endif; else: echo "" ;endif; ?></td>
          </tr>
    </table>
  <div class="height_10px"></div>
  <div class="clear"></div>
</div>
<div class="clear"></div>
<div class="height_5px"></div>
<div class="width_315px_g4b">
  <div class="heigh_30px_white">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="82" height="30" class="title_nv">婚房装修</td>
        <td width="193" height="30" class="title_nv_nor">&nbsp;</td>
        <td width="44" height="30"><span class="black_a"><a href="__APP__/news-list-<?php echo ($news["hfzx"]["id"]); ?>">更多&gt;&gt;</a></span>        
      </tr>
    </table>
  </div>
  <div class="height_10px"></div>
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
         <tr>
           <td width="130" rowspan="2" align="center" valign="middle"><a href="__APP__/news-<?php echo ($news["hfzx"]["h"]["id"]); ?>"><img src="__PUBLIC__/Upload/News/thumb_<?php echo ($news["hfzx"]["h"]["pic_url"]); ?>" width="115" height="90" border="0" title="<?php echo (mystr_replace($news["hfzx"]["h"]["title"])); ?>" alt="图片:婚房装修"/></a></td>
           <td height="30">&nbsp;</td>
           <td width="190" height="30" align="center"><span class="red_s_a" title="<?php echo (mystr_replace($news["hfzx"]["h"]["title"])); ?>"><strong><a href="__APP__/news-<?php echo ($news["hfzx"]["h"]["id"]); ?>"><?php echo (mb_substr($news["hfzx"]["h"]["title"],0,13,'utf-8')); ?></a></strong></span></td>
           <td height="30">&nbsp;</td>
         </tr>
         <tr>
           <td height="50">&nbsp;</td>
           <td width="190" height="50"><span class="height_22px_text">&nbsp;&nbsp;&nbsp;<span class="gray_12px_fs" title="<?php echo (mystr_replace($news["hfzx"]["h"]["remark"])); ?>"><?php echo (mb_substr($news["hfzx"]["h"]["remark"],0,21,'utf-8')); ?>...</span><span class="red_s_a"><a href="__APP__/news-<?php echo ($news["hfzx"]["h"]["id"]); ?>">[详细]</a></span></td>
           <td height="50">&nbsp;</td>
         </tr>
         <tr>
           <td colspan="4">&nbsp;</td>
      </tr>
         <tr>
           <td height="138" colspan="4" valign="top">
          <?php if(is_array($news["hfzx"]["titles"])): $i = 0; $__LIST__ = $news["hfzx"]["titles"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><div class="height_22px_text"><span class="<?php echo (title_style($vo["title_color"])); ?>" title="<?php echo (mystr_replace($vo["title"])); ?>"><a href="__APP__/news-<?php echo ($vo["id"]); ?>">&nbsp;&middot;<?php echo (mb_substr($vo["title"],0,26,'utf-8')); ?></a></span> </div><?php endforeach; endif; else: echo "" ;endif; ?></td>
      </tr>
    </table>
  
  <div class="height_10px"></div>
  <div class="clear"></div>
</div>
<div class="width_5px_float"></div>
<div class="width_315px_g4b">
  <div class="heigh_30px_white">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="82" height="30" class="title_nv">婚俗礼仪</td>
        <td width="193" height="30" class="title_nv_nor">&nbsp;</td>
        <td width="44" height="30"><span class="black_a"><a href="__APP__/news-list-<?php echo ($news["hsly"]["id"]); ?>">更多&gt;&gt;</a></span>        
      </tr>
    </table>
  </div>
  <div class="height_10px"></div>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
         <tr>
           <td width="130" rowspan="2" align="center" valign="middle"><a href="__APP__/news-<?php echo ($news["hsly"]["h"]["id"]); ?>"><img src="__PUBLIC__/Upload/News/thumb_<?php echo ($news["hsly"]["h"]["pic_url"]); ?>" width="115" height="90" border="0" title="<?php echo (mystr_replace($news["hsly"]["h"]["title"])); ?>" alt="图片:婚俗礼仪"/></a></td>
           <td height="30">&nbsp;</td>
           <td width="190" height="30" align="center"><span class="red_s_a" title="<?php echo (mystr_replace($news["hsly"]["h"]["title"])); ?>"><strong><a href="__APP__/news-<?php echo ($news["hsly"]["h"]["id"]); ?>"><?php echo (mb_substr($news["hsly"]["h"]["title"],0,13,'utf-8')); ?></a></strong></span></td>
           <td height="30">&nbsp;</td>
         </tr>
         <tr>
           <td height="50">&nbsp;</td>
           <td width="190" height="50"><span class="height_22px_text">&nbsp;&nbsp;&nbsp;<span class="gray_12px_fs" title="<?php echo (mystr_replace($news["hsly"]["h"]["remark"])); ?>"><?php echo (mb_substr($news["hsly"]["h"]["remark"],0,21,'utf-8')); ?>...</span><span class="red_s_a"><a href="__APP__/news-<?php echo ($news["hsly"]["h"]["id"]); ?>">[详细]</a></span></td>
           <td height="50">&nbsp;</td>
         </tr>
         <tr>
           <td colspan="4">&nbsp;</td>
         </tr>
         <tr>
           <td height="138" colspan="4" valign="top"><?php if(is_array($news["hsly"]["titles"])): $i = 0; $__LIST__ = $news["hsly"]["titles"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><div class="height_22px_text"><span class="<?php echo (title_style($vo["title_color"])); ?>" title="<?php echo (mystr_replace($vo["title"])); ?>"><a href="__APP__/news-<?php echo ($vo["id"]); ?>">&nbsp;&middot;<?php echo (mb_substr($vo["title"],0,26,'utf-8')); ?></a></span></div><?php endforeach; endif; else: echo "" ;endif; ?></td>
         </tr>
    </table>
  <div class="height_10px"></div>
  <div class="clear"></div>
</div>
<div class="width_5px_float"></div>
<div class="width_315px_g4b">
  <div class="heigh_30px_white">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="82" height="30" class="title_nv">蜜月旅行</td>
        <td width="193" height="30" class="title_nv_nor">&nbsp;</td>
        <td width="44" height="30"><span class="black_a"><a href="__APP__/news-list-<?php echo ($news["mylx"]["id"]); ?>">更多&gt;&gt;</a></span>        
      </tr>
    </table>
  </div>
  <div class="height_10px"></div>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
         <tr>
           <td width="130" rowspan="2" align="center" valign="middle"><a href="__APP__/news-<?php echo ($news["mylx"]["h"]["id"]); ?>"><img src="__PUBLIC__/Upload/News/thumb_<?php echo ($news["mylx"]["h"]["pic_url"]); ?>" width="115" height="90" border="0" title="<?php echo (mystr_replace($news["mylx"]["h"]["title"])); ?>" alt="图片:蜜月旅行"/></a></td>
           <td height="30">&nbsp;</td>
           <td width="190" height="30" align="center"><span class="red_s_a" title="<?php echo (mystr_replace($news["mylx"]["h"]["title"])); ?>"><strong><a href="__APP__/news-<?php echo ($news["mylx"]["h"]["id"]); ?>"><?php echo (mb_substr($news["mylx"]["h"]["title"],0,13,'utf-8')); ?></a></strong></span></td>
           <td height="30">&nbsp;</td>
         </tr>
         <tr>
           <td height="50">&nbsp;</td>
           <td width="190" height="50"><span class="height_22px_text">&nbsp;&nbsp;&nbsp;<span class="gray_12px_fs" title="<?php echo (mystr_replace($news["mylx"]["h"]["remark"])); ?>"><?php echo (mb_substr($news["mylx"]["h"]["remark"],0,21,'utf-8')); ?>...</span><span class="red_s_a"><a href="__APP__/news-<?php echo ($news["mylx"]["h"]["id"]); ?>">[详细]</a></span></td>
           <td height="50">&nbsp;</td>
         </tr>
         <tr>
           <td colspan="4">&nbsp;</td>
         </tr>
         <tr>
           <td height="138" colspan="4" valign="top"><?php if(is_array($news["mylx"]["titles"])): $i = 0; $__LIST__ = $news["mylx"]["titles"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><div class="height_22px_text">&nbsp;<span class="<?php echo (title_style($vo["title_color"])); ?>" title="<?php echo (mystr_replace($vo["title"])); ?>"><a href="__APP__/news-<?php echo ($vo["id"]); ?>">&middot;<?php echo (mb_substr($vo["title"],0,26,'utf-8')); ?></a></span></div><?php endforeach; endif; else: echo "" ;endif; ?></td>
         </tr>
    </table>
  <div class="height_10px"></div>
  <div class="clear"></div>
</div>
  <div class="clear"></div>
 <div class="height_5px"></div>
 <div class="width_315px_g4b">
    <div class="heigh_30px_white" >
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="81" height="30" align="center" class="title_nv">婆媳关系</td>
          <td width="187" height="30" align="right" class="black_a"><a href="__APP__/news-list-<?php echo ($news["pxgx"]["id"]); ?>" class="black_12px">更多&gt;&gt;</a>&nbsp;</td>
        </tr>
      </table>
    </div>
    <div class="height_5px"></div>
    <div class="height_5px"></div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
         <tr>
           <td width="130" rowspan="2" align="center" valign="middle"><a href="__APP__/news-<?php echo ($news["pxgx"]["h"]["id"]); ?>"><img src="__PUBLIC__/Upload/News/thumb_<?php echo ($news["pxgx"]["h"]["pic_url"]); ?>" width="115" height="90" border="0" title="<?php echo (mystr_replace($news["pxgx"]["h"]["title"])); ?>" alt="图片:婆媳关系"/></a></td>
           <td height="30">&nbsp;</td>
           <td width="190" height="30" align="center"><span class="red_s_a" title="<?php echo (mystr_replace($news["pxgx"]["h"]["title"])); ?>"><strong><a href="__APP__/news-<?php echo ($news["pxgx"]["h"]["id"]); ?>"><?php echo (mb_substr($news["pxgx"]["h"]["title"],0,13,'utf-8')); ?></a></strong></span></td>
           <td height="30">&nbsp;</td>
         </tr>
         <tr>
           <td height="50">&nbsp;</td>
           <td width="190" height="50"><span class="height_22px_text">&nbsp;&nbsp;&nbsp;<span class="gray_12px_fs" title="<?php echo (mystr_replace($news["pxgx"]["h"]["remark"])); ?>"><?php echo (mb_substr($news["pxgx"]["h"]["remark"],0,21,'utf-8')); ?>...</span><span class="red_s_a"><a href="__APP__/news-<?php echo ($news["pxgx"]["h"]["id"]); ?>">[详细]</a></span></td>
           <td height="50">&nbsp;</td>
         </tr>
         <tr>
           <td colspan="4">&nbsp;</td>
        </tr>
         <tr>
           <td height="138" colspan="4" valign="top">
          <?php if(is_array($news["pxgx"]["titles"])): $i = 0; $__LIST__ = $news["pxgx"]["titles"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><div class="height_22px_text"><span class="<?php echo (title_style($vo["title_color"])); ?>" title="<?php echo (mystr_replace($vo["title"])); ?>"><a href="__APP__/news-<?php echo ($vo["id"]); ?>">&nbsp;&middot;<?php echo (mb_substr($vo["title"],0,26,'utf-8')); ?></a></span> </div><?php endforeach; endif; else: echo "" ;endif; ?></td>
        </tr>
      </table>
<div class="height_5px_float"></div>
 <div class="clear"></div>
  </div>
 <div class="width_5px_float"></div>
  <div class="width_315px_g4b">
    <div class="heigh_30px_white">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="81" height="30" align="center" class="title_nv">育儿宝典</td>
          <td width="187" height="30" align="right" class="black_a"><a href="__APP__/news-list-<?php echo ($news["yebd"]["id"]); ?>" class="black_12px">更多&gt;&gt;</a>&nbsp;</td>
        </tr>
      </table>
    </div>
    <div class="height_5px"></div>
    <div class="height_5px"></div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
         <tr>
           <td width="130" rowspan="2" align="center" valign="middle"><a href="__APP__/news-<?php echo ($news["yebd"]["h"]["id"]); ?>"><img src="__PUBLIC__/Upload/News/thumb_<?php echo ($news["yebd"]["h"]["pic_url"]); ?>" width="115" height="90" border="0" title="<?php echo (mystr_replace($news["yebd"]["h"]["title"])); ?>" alt="图片:育儿宝典"/></a></td>
           <td height="30">&nbsp;</td>
           <td width="190" height="30" align="center"><span class="green_nv_a" title="<?php echo (mystr_replace($news["yebd"]["h"]["title"])); ?>"><strong><a href="__APP__/news-<?php echo ($news["yebd"]["h"]["id"]); ?>" class="green_a"><?php echo (mb_substr($news["yebd"]["h"]["title"],0,13,'utf-8')); ?></a></strong></span></td>
           <td height="30">&nbsp;</td>
         </tr>
         <tr>
           <td height="50">&nbsp;</td>
           <td width="190" height="50"><span class="height_22px_text">&nbsp;&nbsp;&nbsp;<span class="gray_12px_fs" title="<?php echo (mystr_replace($news["yebd"]["h"]["remark"])); ?>"><?php echo (mb_substr($news["yebd"]["h"]["remark"],0,21,'utf-8')); ?>...</span><span class="red_s_a"><a href="__APP__/news-<?php echo ($news["yebd"]["h"]["id"]); ?>">[详细]</a></span></td>
           <td height="50">&nbsp;</td>
         </tr>
         <tr>
           <td colspan="4">&nbsp;</td>
        </tr>
         <tr>
           <td height="138" colspan="4" valign="top">
          <?php if(is_array($news["yebd"]["titles"])): $i = 0; $__LIST__ = $news["yebd"]["titles"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><div class="height_22px_text"><span class="<?php echo (title_style($vo["title_color"])); ?>" title="<?php echo (mystr_replace($vo["title"])); ?>"><a href="__APP__/news-<?php echo ($vo["id"]); ?>">&nbsp;&middot;<?php echo (mb_substr($vo["title"],0,26,'utf-8')); ?></a></span> </div><?php endforeach; endif; else: echo "" ;endif; ?></td>
        </tr>
      </table>

<div class="height_5px_float"></div>
 <div class="clear"></div>
  </div>
  <div class="width_5px_float"></div>
  <div class="width_315px_g4b">
    <div class="heigh_30px_white" >
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="81" height="30" align="center" class="title_nv">话题PK</td>
          <td width="187" height="30" align="right" class="black_a"><a href="__APP__/news-list-<?php echo ($news["cjxw"]["id"]); ?>" class="black_12px">&nbsp;</a><a href="__APP__/news-list-htpk" class="black_12px">更多&gt;&gt;</a>&nbsp;</td>
        </tr>
      </table>
    </div>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr align="center">
        <td height="43" colspan="3" class="red_14px_f"><strong><span class="green_a"><a href="__APP__/News/htpk_view/id/<?php echo ($news["htpk"]["id"]); ?>" class="green_14px_yahei"><?php echo ($news["htpk"]["title"]); ?></a></span></strong></td>
      </tr>
      <tr>
        <td width="18" height="55">&nbsp;</td>
        <td width="231" height="55">&nbsp;&nbsp;&nbsp;<span class="height_20px_text"><?php echo ($news["htpk"]["content"]); ?></span><span class="red_s_a"><a href="__APP__/News/htpk_view/id/<?php echo ($news["htpk"]["id"]); ?>">[详细]</a></span></td>
        <td width="21" height="55">&nbsp;</td>
      </tr>
      <tr align="center">
        <td height="145" colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="120">&nbsp;</td>
            <td height="120" align="center" valign="bottom"><span class="blue_s_text"><?php echo ($news["htpk"]["b_num"]); ?>票</span><br>              <img src="../Public/Images/pk_blue.png" width="45" height="<?php echo ($news["htpk"]["b_num_per"]); ?>" alt="蓝"></td>
            <td height="120" align="center">&nbsp;</td>
            <td height="120" align="center" valign="bottom"><span class="red_s_text"><?php echo ($news["htpk"]["r_num"]); ?>票</span><br>
              <img src="../Public/Images/pk_red.png" width="45" height="<?php echo ($news["htpk"]["r_num_per"]); ?>" alt="红"></td>
            <td height="120">&nbsp;</td>
          </tr>
          <tr>
            <td height="39">&nbsp;</td>
            <td height="39" align="center" class="blue_s_text">反方观点</td>
            <td height="39" align="center">&nbsp;</td>
            <td height="39" align="center" class="red_s_text">正方观点</td>
            <td height="39">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr align="center">
        <td colspan="3"></td>
      </tr>
    </table>
    <div class="clear"></div>
  </div>
  <div class="clear"></div>
 <div class="height_10px"></div>
 <a href="__APP__/shop-list-5"><img src="../Public/Images/sygzs_bg.png" width="960" height="40" border="0" alt="摄影工作室"></a>
 <div class="width_958px_4bian">
 <?php if(is_array($shop_list[5])): $i = 0; $__LIST__ = $shop_list[5];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><div class="width_240px_float">&nbsp;&nbsp;<a href="__APP__/shop-<?php echo ($vo["id"]); ?>" title="<?php echo ($vo["name"]); ?>"> <?php echo (mb_substr(trim($vo["name"]),0,18,'utf-8')); ?></a></div><?php endforeach; endif; else: echo "" ;endif; ?>   
<div class="clear"></div>
 </div>
 <div class="height_10px"></div>
 <a href="__APP__/shop-list-6"><img src="../Public/Images/hsyl_bg.png" width="960" height="40" border="0" alt="婚纱影楼"></a>
 <div class="width_958px_4bian">
<?php if(is_array($shop_list[6])): $i = 0; $__LIST__ = $shop_list[6];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><div class="width_240px_float">&nbsp;&nbsp;<a href="__APP__/shop-<?php echo ($vo["id"]); ?>" title="<?php echo ($vo["name"]); ?>"> <?php echo (mb_substr(trim($vo["name"]),0,18,'utf-8')); ?></a></div><?php endforeach; endif; else: echo "" ;endif; ?>
  <div class="clear"></div><!--自动拉伸width_960px-->
  </div>
 <div class="height_10px"></div>
 <a href="__APP__/shop-list-7"><img src="../Public/Images/hqgs_bg.png" width="960" height="40" border="0" alt="婚庆公司"></a>
 <div class="width_958px_4bian">
  <?php if(is_array($shop_list[7])): $i = 0; $__LIST__ = $shop_list[7];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><div class="width_240px_float">&nbsp;&nbsp;<a href="__APP__/shop-<?php echo ($vo["id"]); ?>" title="<?php echo ($vo["name"]); ?>"> <?php echo (mb_substr(trim($vo["name"]),0,18,'utf-8')); ?></a></div><?php endforeach; endif; else: echo "" ;endif; ?>   
<div class="clear"></div>
 </div>
 <div class="height_10px"></div>
 <a href="__APP__/shop-list-8"><img src="../Public/Images/hslf_bg.png" width="960" height="40" border="0" alt="婚纱礼服"></a>
 <div class="width_958px_4bian">
  <?php if(is_array($shop_list[8])): $i = 0; $__LIST__ = $shop_list[8];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><div class="width_240px_float">&nbsp;&nbsp;<a href="__APP__/shop-<?php echo ($vo["id"]); ?>" title="<?php echo ($vo["name"]); ?>"> <?php echo (mb_substr(trim($vo["name"]),0,18,'utf-8')); ?></a></div><?php endforeach; endif; else: echo "" ;endif; ?>   
<div class="clear"></div>
 </div>
 <div class="height_10px"></div>
 <a href="__APP__/shop-list-11"><img src="../Public/Images/hjss_bg.png" width="960" height="40" border="0" alt="婚戒首饰"></a>
 <div class="width_958px_4bian">
  <?php if(is_array($shop_list[11])): $i = 0; $__LIST__ = $shop_list[11];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><div class="width_240px_float">&nbsp;&nbsp;<a href="__APP__/shop-<?php echo ($vo["id"]); ?>" title="<?php echo ($vo["name"]); ?>"> <?php echo (mb_substr(trim($vo["name"]),0,18,'utf-8')); ?></a></div><?php endforeach; endif; else: echo "" ;endif; ?>   
<div class="clear"></div>
 </div>

 <div class="height_10px"></div>
 <!--左边start---><!--左边end--><!--右边start--><!--右边end-->
  <div class="clear"></div><!--自动拉伸width_680px_float-->
</div>
<!--中部第三大块结束--> 
<div class="clear"></div><!--自动拉伸width_270px_float--><!--中部第四大块开始--><!--中部第四大块结束-->
<div class="clear"></div><!--自动拉伸width_960px-->

<div class="clear"></div><!--自动拉伸width_960px-->

<!--中部第六大块开始-->

<!--中部第六大块结束-->
<div class="clear"></div><!--自动拉伸width_960px--><!--中部第七大块开始-->
<!--报广展示start--><!--报广展示end--> 
<!--中部第七大块结束-->


<!--%%当前是否在首页嵌入-->


    <div class="height_5px"></div>
<!--友情连接目录start-->
      
<div id="big" onMouseOver="zhuan()" onMouseOut="jixu()" class="width_960px_top_bottom_1px">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td  width="7%" bgcolor="#F20018">&nbsp;</td>
            <td width="93%" bgcolor="#E3E3E3">&nbsp;</td>
          </tr>
		  </table>
  <div id="con_zzjs_net">
    <ul id="tags">
<li class="selectTag"><a onMouseOver="selectTag('tagContent0',this)" href="javascript:void(0)">合 </a></li>
<li><a onMouseOver="selectTag('tagContent1',this)" href="javascript:void(0)"> 作 </a></li>
<li><a onMouseOver="selectTag('tagContent2',this)" href="javascript:void(0)"> 朋 </a></li>
<li><a onMouseOver="selectTag('tagContent3',this)" href="javascript:void(0)"> 友 </a></li>
<li><a onMouseOver="selectTag('tagContent4',this)" href="javascript:void(0)"> 们 </a></li>
<li><a href="__APP__/About/link">更多&gt;&gt;</a></li>
    </ul>
  <div id="tagContent">
      <div class="tagContent selectTag" id="tagContent0">
	  <!-- 1 s -->
	  <div class="height_10px_float"></div>
        <div class="clear"></div><!--自动拉伸width_960px-->
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border_1px_top_bottom">
      <tr>
         <td height="55" class="height_22px_text black_a">
         <?php if(is_array($lp_link[0])): $k = 0; $__LIST__ = $lp_link[0];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$k;$mod = ($k % 2 )?><?php if(($k)  !=  "1"): ?>&nbsp;<?php endif; ?> <a href="<?php echo ($vo["url"]); ?>" target="_blank"><?php echo ($vo["webname"]); ?></a> |<?php endforeach; endif; else: echo "" ;endif; ?>
         </td>
        </tr>
      </table>
       <div class="height_10px_float"></div> 
         <div class="clear"></div><!--自动拉伸width_960px-->
	 <!-- 1 e -->
	  </div>
      <div class="tagContent" id="tagContent1">
	  <!-- 2 s -->
	  <div class="height_10px_float"></div>
        <div class="clear"></div><!--自动拉伸width_960px-->
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border_1px_top_bottom">
      <tr>
         <td height="55" class="height_22px_text black_a">
         <?php if(is_array($lp_link[1])): $k = 0; $__LIST__ = $lp_link[1];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$k;$mod = ($k % 2 )?><?php if(($k)  !=  "1"): ?>&nbsp;<?php endif; ?> <a href="<?php echo ($vo["url"]); ?>" target="_blank"><?php echo ($vo["webname"]); ?></a> |<?php endforeach; endif; else: echo "" ;endif; ?>
         </td>
        </tr>
      </table>
       <div class="height_10px_float"></div> 
         <div class="clear"></div><!--自动拉伸width_960px-->
	 <!-- 2 e -->
	  </div>
      <div class="tagContent" id="tagContent2">
	  <!-- 3 s -->
	  <div class="height_10px_float"></div>
        <div class="clear"></div><!--自动拉伸width_960px-->
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border_1px_top_bottom">
      <tr>
         <td height="55" class="height_22px_text black_a">
         <?php if(is_array($lp_link[2])): $k = 0; $__LIST__ = $lp_link[2];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$k;$mod = ($k % 2 )?><?php if(($k)  !=  "1"): ?>&nbsp;<?php endif; ?> <a href="<?php echo ($vo["url"]); ?>" target="_blank"><?php echo ($vo["webname"]); ?></a> |<?php endforeach; endif; else: echo "" ;endif; ?>
         </td>
        </tr>
      </table>
       <div class="height_10px_float"></div> 
         <div class="clear"></div><!--自动拉伸width_960px-->
	 <!-- 3 e -->
	  </div>
	  <div class="tagContent" id="tagContent3">
	  <!-- 4 s -->
	  <div class="height_10px_float"></div>
        <div class="clear"></div><!--自动拉伸width_960px-->
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border_1px_top_bottom">
      <tr>
         <td height="55" class="height_22px_text black_a">
         <?php if(is_array($lp_link[3])): $k = 0; $__LIST__ = $lp_link[3];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$k;$mod = ($k % 2 )?><?php if(($k)  !=  "1"): ?>&nbsp;<?php endif; ?> <a href="<?php echo ($vo["url"]); ?>" target="_blank"><?php echo ($vo["webname"]); ?></a> |<?php endforeach; endif; else: echo "" ;endif; ?>
         </td>
        </tr>
      </table>
       <div class="height_10px_float"></div> 
         <div class="clear"></div><!--自动拉伸width_960px-->
	 <!-- 4 e -->
	  </div>
	  <div class="tagContent" id="tagContent4">
	  <!-- 5 s -->
	  <div class="height_10px_float"></div>
        <div class="clear"></div><!--自动拉伸width_960px-->
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border_1px_top_bottom">
      <tr>
         <td height="55" class="height_22px_text black_a">
         <?php if(is_array($lp_link[4])): $k = 0; $__LIST__ = $lp_link[4];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$k;$mod = ($k % 2 )?><?php if(($k)  !=  "1"): ?>&nbsp;<?php endif; ?> <a href="<?php echo ($vo["url"]); ?>" target="_blank"><?php echo ($vo["webname"]); ?></a> |<?php endforeach; endif; else: echo "" ;endif; ?>
         </td>
        </tr>
      </table>
       <div class="height_10px_float"></div> 
         <div class="clear"></div><!--自动拉伸width_960px-->
	 <!-- 5 e -->
	  </div>
  </div>
</div>
<script type="text/javascript">
var obig=document.getElementById("big");
function selectTag(showContent,selfObj){
   var tag = document.getElementById("tags").getElementsByTagName("li");
  var taglength = tag.length;
  for(i=0; i<taglength; i++){
    tag[i].className = "";
  }
  selfObj.parentNode.className = "selectTag";
  for(i=0; j=document.getElementById("tagContent"+i); i++){
    j.style.display = "none";
  }
  document.getElementById(showContent).style.display = "block";
}
 var x=0;
 function scrollTag(){
 var tags=document.getElementById("tags").getElementsByTagName("a");
  if(x<2){x=x+1}
  else
  x=0;
  var tag = document.getElementById("tags").getElementsByTagName("li");
  var taglength = tag.length;
  for(i=0; i<taglength; i++){
    tag[i].className = "";
  }
tags[x].parentNode.className = "selectTag";
for(i=0; j=document.getElementById("tagContent"+i);i++){
   j.style.display="none";
 }
 document.getElementById("tagContent"+x).style.display="block";
}
var scrolll=setInterval(scrollTag,2000);
function zhuan(){
	clearInterval(scrolll);
}
function jixu(){
	scrolll=setInterval(scrollTag,2000);
}
</script>
</div>
    
    <!--end友情链接-->
    
    
    <!--页脚start-->
	<div id="tf_foot"  align="center">
      <br />
      <span class="green_a">
			<br />
	      <span><a href="http://www.0791hunqing.com">南昌婚庆</a></span> - 
          <span><a href="__APP__/About/about_yijian">恭候您的意见</a></span> - 
          <span><a href="__APP__/About/about_lianxi">联系我们</a></span> - 
          <span><a href="__APP__/About/about_us">关于我们</a></span> - 
          <span><a href="__APP__/About/about_guanggao">广告服务</a></span> - 
          <span><a href="__APP__/About/sitemap">站点地图</a></span> - 
          <span><a href="http://test.0791hunqing.com/">旧版入口</a></span>
       </span>
  <br />
      <br />
     <span >本站郑重声明：南昌婚庆网所载文章、数据仅供参考，使用前请核实，风险自负。<?php echo (C("cfg_icp")); ?></span>
        <br />
        <br />
         <span >Copyright 南昌婚庆网 0791hunqing.com All Rights Reserved 版权所有 复制必究</span>
        <br />
        <br />
		<div class="clear"></div><!--自动拉伸width_960px-->
</div>

<!-- baidu统计 -->
<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F7e2c4ac76f6f7ec6294a2c006018361c' type='text/javascript'%3E%3C/script%3E"));
</script>
<!-- baidu统计 -->
</body>
</html>
<script type="text/javascript">
	$(function(){

		//1.今日要闻与全国楼市加载效果------------------start---------------------------------------
		var last_jryw=$('#yaoweng_title .yaoweng_title_nv_nor:eq(0)');
		$('#yaoweng_title .yaoweng_title_nv_nor').mouseover(function(){
			if($(this).hasClass('yaoweng_title_nv_nor')){
				//加载栏目,并更换样式
				var id="jryw_inc";
				var url=APP+'/News/ajax_jryw/name/'+$(this).attr('id');
				domAjax(id,url);	
				
				last_jryw.removeClass('yaoweng_title_nv').addClass('yaoweng_title_nv_nor');	
				last_jryw=$(this);						
				$(this).removeClass('yaoweng_title_nv_nor').addClass('yaoweng_title_nv');			
			}
			
		});
			
		$('#yaoweng_title .yaoweng_title_nv_nor:eq(0)').removeClass('yaoweng_title_nv_nor').addClass('yaoweng_title_nv');
		//1.今日要闻与全国楼市加载效果---------------end------------------------------------------
		

		//2.大嘴谈房与小编踩盘加载效果------------------start---------------------------------------
		var last_lpdg=$('#dazui_title .title_nv_nor:eq(0)');
		$('#dazui_title .title_nv_nor').mouseover(function(){
			if($(this).hasClass('title_nv_nor')){
				//加载栏目,并更换样式
				var id="dazui_inc";
				var url=APP+'/News/ajax_dztf/name/'+$(this).attr('id');
				domAjax(id,url);	
				
				last_lpdg.removeClass('title_nv').addClass('title_nv_nor');	
				last_lpdg=$(this);						
				$(this).removeClass('title_nv_nor').addClass('title_nv');			
			}
			
		});
			
		$('#dazui_title .title_nv_nor:eq(0)').removeClass('title_nv_nor').addClass('title_nv');
		//2.大嘴谈房与小编踩盘加载效果---------------end------------------------------------------	
		
		
		//8.土地出让/土地交易/楼盘规划加载效果------------------start---------------------------------------
		var last_tdcr=$('#tdcr_title .title_nv_nor:eq(0)');
		$('#tdcr_title .title_nv_nor').mouseover(function(){
			if($(this).hasClass('title_nv_nor')){
				//加载栏目,并更换样式
				var id="tdcr_inc";
				var url=APP+'/News/ajax_tdcr/name/'+$(this).attr('id');
				domAjax(id,url);	
				
				last_tdcr.removeClass('title_nv').addClass('title_nv_nor');	
				last_tdcr=$(this);						
				$(this).removeClass('title_nv_nor').addClass('title_nv');			
			}
			
		});
			
		$('#tdcr_title .title_nv_nor:eq(0)').removeClass('title_nv_nor').addClass('title_nv');
		//8.土地出让/土地交易/楼盘规划加载效果加载效果---------------end------------------------------------------	
		
		//最新楼备与每日成交排行榜加载效果------------------start---------------------------------------
		var last_xsph=$('#xsph_title .title_nv_style3_nor:eq(0)');
		$('#xsph_title .title_nv_style3_nor').mouseover(function(){
			if($(this).hasClass('title_nv_style3_nor')){
				//加载栏目,并更换样式
				var id=$(this).attr('id');
				if(id=='xsph'){
					$('#xsph_inc').css('display','block');
					$('#zxlp_inc').css('display','none');
				}else{
					$('#xsph_inc').css('display','none');
					$('#zxlp_inc').css('display','block');	
				}
				
				last_xsph.removeClass('title_nv_style3').addClass('title_nv_style3_nor');	
				last_xsph=$(this);						
				$(this).removeClass('title_nv_style3_nor').addClass('title_nv_style3');			
			}
			
		});
			
		$('#xsph_title .title_nv_style3_nor:eq(0)').removeClass('title_nv_style3_nor').addClass('title_nv_style3');
		//最新楼备与每日成交排行榜加载效果加载效果---------------end------------------------------------------												
	});
	
	
	</script> 
  <script type="text/javascript">
		<!--//--><![CDATA[//><!--
			
		
$(document).ready(function(){
		/*var scrollPic_02 = new ScrollPic();
		scrollPic_02.scrollContId   = "hot_lp_pic"; //内容容器ID
		scrollPic_02.arrLeftId      = "hot_lpleft_pic";//左箭头ID
		scrollPic_02.arrRightId     = "hot_lpright_pic"; //右箭头ID

		scrollPic_02.frameWidth     = 800;//显示框宽度
		scrollPic_02.pageWidth      = 318; //翻页宽度

		scrollPic_02.speed          = 10; //移动速度(单位毫秒，越小越快)
		scrollPic_02.space          = 10; //每次移动像素(单位px，越大越快)
		scrollPic_02.autoPlay       = true; //自动播放
		scrollPic_02.autoPlayTime   = 5; //自动播放间隔时间(秒)
		
	
		//scrollPic_02.initialize(); //初始化

				/*导入默认南昌新房价格查询;*/
		domAjax('getrange_price','__APP__/News/getrange_price/b/4000/e/5000','get');
		/*导入默认南昌区域价格查询;*/
		domAjax('getquyu_price','__APP__/News/getquyu_price/range_id/3','get');
});


//pk
function pkShow(type,event){

	//type='red'支持红方
	//type='blue'支持蓝方
	var id='pk'+type;
	var color;
	if(type=='red'){
		color='#FF6600';
	}else{
		color='#0090B9';	
	}

	//3.根据上面获取的信息，定位弹出框的位置;
	var left=event.x;
	var top=$('body').scrollTop()+event.y;	


	var html='<div id="'+id+'" style="position: absolute;top:'+top+'px;left:'+left+'px;width:100px;height:100px;color:'+color+';font-size: 100px;font-family:Arial, Helvetica, sans-serif;z-index:100;"><b>+1</b></div>';
	//创建一个dom模型容器
	if(!$('#'+id).width()){
	    $(html).appendTo('body');
	}

	//创建动画
	$('#'+id).animate({top:top-200,fontSize:80,opacity:0},800,function(){
		
		//增加票数
		$('#'+id+'_piao').html(Number($('#'+id+'_piao').html())+1);
		//显示比例
		var red_piao=Number($('#pkred_piao').html());
		var blue_piao=Number($('#pkblue_piao').html());
		var redBai=Math.round(red_piao/(red_piao+blue_piao)*100);
		//显示文字比例
		$('#pkred_num').html(redBai+'%');
		$('#pkblue_num').html((100-redBai)+'%');
		//显示图像比例
		$('#pkred_pic').width=redBai;
		$('#pkblue_pic').width=100-redBai;

		$('#'+id).remove();//移除div
	});
	
}

</script>