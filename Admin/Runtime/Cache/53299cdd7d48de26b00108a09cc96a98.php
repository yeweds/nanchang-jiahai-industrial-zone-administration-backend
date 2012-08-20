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
<link href="../Public/Css/main.css" rel="stylesheet" type="text/css" />
<div class="bodyTitle">
<div class="bodyTitleLeft"></div>
<div class="bodyTitleText">商家管理</div>
</div>

<script language="JavaScript" type="text/JavaScript">
function gopart(){
    var cid = document.getElementById("class_id").value;
	window.location.href="__URL__/index/class_id/"+cid;
}
//提交搜索
	function submitSearch(){
		var s_key  = $("#s_key").val();
		s_key = $.trim(s_key);    //去空格
 		var s_type = $("#s_type").val();
		window.location.href= URL+"/index/s_key/"+ s_key +"/s_type/" + s_type;
	}
</script>

<h3 class="marginbot"><a href="__APP__/Shop/" class="sgbtn">返回商家管理</a>
<a href="__URL__/add/classid/<?php echo ($class["id"]); ?>/level/<?php echo ($class['level']); ?>"
	class="sgbtn">+添加商家</a> 
	<a href="#" onClick="delAll()" class="sgbtn">－删除选中</a> 
	<a href="javascript:history.go(-1)"
	style="float: right">返回&gt;&gt;&nbsp;&nbsp;&nbsp;</a> 
	<select name="class_id" id="class_id" onchange="javascript:gopart();">
	<option value="0">选择所有</option>
	<?php if(is_array($class)): $i = 0; $__LIST__ = $class;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
</select> 

<span>
&nbsp;  请输入关键字：<input name="s_key" id="s_key" />
	<select name="s_type" id="s_type"/>
		  <option value="name">按全称</option>
		  <option value="sub_title">按简称</option>
		  <option value="id">按商家ID</option>
	</select>  <a href="#" onClick="submitSearch()" class="sgbtn">我要查询</a>
</span>

<div id="result" class="none result" style="letter-spacing:2px"></div>
</h3>
<table id="articlelist" class="datalist fixwidth">
	<tr align="center">
		<td width="6%">
		<input type="checkbox" onClick="selAll(this,'id[]')" value="选择全部" class="checkbox">选择</td>
		<td width="5%">ID</td>
		<td width="21%">商家名称</td>
		<td width="13%">所属栏目</td>
		<td width="13%">信誉星级</td>
		<td width="11%">发布时间</td>
		<td width="13%">权重</td>
		<td>操作</td>
	</tr>
	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><tr>
		<td align="center"><input name="id[]" type="checkbox" id="id[]"
			value="<?php echo ($vo["id"]); ?>" /></td>
		<td height="22" align="center"><?php echo ($vo["id"]); ?></td>
		<td align="left">
		<a href="<?php echo (C("cfg_domain")); ?>/shop-<?php echo ($vo["id"]); ?>" target="_blank" style="color: #3C3C3C;" title="<?php echo ($vo["sub_title"]); ?>">
		  <?php echo ($vo["name"]); ?></a>
		</td>
		<td align="center">
		<a href="__URL__/index/class_id/<?php echo ($vo["class_id"]); ?>" title="<?php echo ($vo["name"]); ?>"
			style="color: blue;"><?php echo ($vo["classname"]); ?></a></td>
		<td align="center"><?php $num=($vo['star']? $vo['star']:3);  
		 for($x=0; $x<$num; $x++){ 
		 	echo '★';
		 }
		?></td>
		<td align="center"><?php echo (date("Y-m-d",$vo["add_time"])); ?></td>
		<td align="center"><?php echo ($vo["sortrank"]); ?></td>
		<td align="center">
		[<a href="__URL__/edit/id/<?php echo ($vo["id"]); ?>/class_id/<?php echo ($vo["class_id"]); ?>">编辑</a>]&nbsp;&nbsp;[<a
			href="__APP__/Pic/index/shop_id/<?php echo ($vo["id"]); ?>">相册</a>]&nbsp;&nbsp;[<a
			href="javascript:del(<?php echo ($vo["id"]); ?>)">删除</a>]</td>
	</tr><?php endforeach; endif; else: echo "" ;endif; ?>
</table>
<div class="page"><?php echo ($page); ?></div>
</body>
</html>