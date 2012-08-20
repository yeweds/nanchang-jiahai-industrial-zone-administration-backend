<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>0791hunqing</title>
<link href="../Public/Css/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript">
//指定当前组模块URL地址
var URL = '__URL__';
var APP	 =	 '__APP__';
var PUBLIC = '__PUBLIC__';
var SELF = '__SELF__';
</script>
<script type="text/javascript" src="../Public/Js/Base_qf.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/jquery.min.js"></script>
</head>

<body>
<script>
function gopart(){
    var cid = document.getElementById("class_id").value;
	if(cid>0){
	    window.location.href="__URL__/index/class_id/"+cid+"/";
	}
}

//提交搜索
	function submitSearch(){
		var s_key  = $("#s_key").val();
		s_key = $.trim(s_key);    //去空格
 		var s_type = $("#s_type").val();
		window.location.href= URL+"/index?&s_key="+ s_key +"&s_type=" + s_type;
	}
</script>
<div class="bodyTitle">
	<div class="bodyTitleLeft"></div>
  <div class="bodyTitleText">相册管理</div>
</div>

<h3 class="marginbot">
<a href="__URL__/" class="sgbtn">返回相册管理</a> 
<a href="#" onClick="delAll()" class="sgbtn">－删除选中</a>
<a href="__URL__/add" class="sgbtn">＋添加相册</a>
<span>
&nbsp; 请输入关键字：<input name="s_key" id="s_key" title="标题查询" style="width:280px;"  />
	<select name="s_type" id="s_type"/>
		<option value="title">按标题</option>
		<option value="id">按相册ID</option>
		<option value="shop_id">按商家ID</option>
		<option value="class_id">按相册分类ID</option>
	</select>  <a href="#" onClick="submitSearch()" class="sgbtn">我要查询</a>
</span>

<div id="result" class="none result" style="letter-spacing:2px"></div>
</h3>

<table id="articlelist" class="datalist fixwidth">
	  <tr align="center">
	    <td width="6%"><input type="checkbox" onClick="selAll(this,'id[]')" value="选择全部" class="checkbox">选择</td>
	    <td width="4%">ID</th>
		<td width="30%">标题</td>
		<td width="20%">商家(shop_id)</td>
		<td width="10%"> 发布时间</td>
		<td width="10%">编辑</td>
        <td>操作</td>
	  </tr>
      <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><tr >
	     <td align="center"><input name="id[]" type="checkbox" id="id[]" value="<?php echo ($vo["id"]); ?>" /></td>
		 <td height="22" align="center"><?php echo ($vo["id"]); ?></td>
		 <td align="left"><a href="<?php echo (C("cfg_domain")); ?>/index.php/pics-<?php echo ($vo["id"]); ?>" target="_blank" style="color:#3C3C3C;" 
		 title="<?php echo ($vo["remark"]); ?>"><?php echo ($vo["title"]); ?></a></td>
		 <td><?php echo (($vo["shop_name"])?($vo["shop_name"]):"未关联"); ?>  <?php echo ($vo["shop_id"]); ?></td>
		 <td align="center"><?php echo (date("Y-m-d",$vo["add_time"])); ?></td>
		 <td align="center"><?php echo ($vo["username"]); ?></td>
		 <td align="center">[<a href="javascript:PopWindow('__URL__/addAttach/info_id/'+<?php echo ($vo["id"]); ?>,600,700);" >添加图片</a>]&nbsp;[<a href="javascript:edit(<?php echo ($vo["id"]); ?>)" >编辑</a>]&nbsp;[<a href="javascript:del(<?php echo ($vo["id"]); ?>)">删除</a>]</td>
	  </tr><?php endforeach; endif; else: echo "" ;endif; ?>
</table>
	  <div class="page"><?php echo ($page); ?></div>

</body>
</html>