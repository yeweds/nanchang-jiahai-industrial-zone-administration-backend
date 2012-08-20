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
</script>
<div class="bodyTitle">
	<div class="bodyTitleLeft"></div>
  <div class="bodyTitleText">新闻管理</div>
</div>

<!-- <a href="__URL__/add" class="sgbtn">＋标注新坐标</a> -->
<table border="0" cellspacing="0" cellpadding="0" style="margin:6px 0px;">
  <tr>
    <td width="21%" height="30"><a href="__URL__/" class="sgbtn">返回新闻管理</a> </td>
 <form method="post" action="__URL__/" id="search" >
  <td width="40%" align="right"><input type="text" name="title" title="标题查询" style="width:300px;" /></td>
    <td width="25%" align="center">
	 <select name="field" class="datainput" id="field">
		<option value="title">按标题</option>
		<option value="id">按新闻ID</option>
     </select>
	</td>
    <td width="14%"><input type="submit" class="df_button_green" value="查 询" ></td>
</form>
  </tr>
</table>

<h3 class="marginbot">
<a href="#" onClick="delAll()" class="sgbtn">－删除选中</a>
<select name="class_id" id="class_id"  onchange="javascript:gopart();">
        <?php if(($typeid)  ==  ""): ?><option>请选择</option><?php endif; ?>
		<?php if(isset($list_class)): ?><?php if(is_array($list_class)): $i = 0; $__LIST__ = $list_class;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo_class): ++$i;$mod = ($i % 2 )?><option value="<?php echo ($vo_class["id"]); ?>" <?php if(($vo_class["id"])  ==  $typeid): ?>selected<?php endif; ?> ><?php echo ($vo_class["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?><?php endif; ?>
</select>
<a href='__URL__/index<?php if(($typeid)  !=  ""): ?>/class_id/<?php echo ($typeid); ?><?php endif; ?>/ispublish/0' class="sgbtn">仅未发布</a>
<a href='__URL__/index<?php if(($typeid)  !=  ""): ?>/class_id/<?php echo ($typeid); ?><?php endif; ?>/ispublish/1' class="sgbtn">仅已发布</a>
<!-- <a href='__URL__/clear<?php if(($typeid)  !=  ""): ?>/class_id/<?php echo ($typeid); ?><?php endif; ?>' class="sgbtn">
清空<?php if(($typeid)  ==  ""): ?>'全部'栏目<?php else: ?>'<?php echo ($typename); ?>'栏目<?php endif; ?>下所有未发布状态(谨慎!不可恢复)</a> -->
</h3>

<div id="result" class="none result" style="letter-spacing:10px"></div>
</h3>

<table id="articlelist" class="datalist fixwidth">
	  <tr align="center">
	    <td width="6%"><input type="checkbox" onClick="selAll(this,'id[]')" value="选择全部" class="checkbox">选择</td>
	    <td width="4%">ID</th>
		<td width="32%">标题(摘要)</td>
		<td width="12%">所属栏目</td>
		<td width="10%">属性</td>
		<td width="10%">发布时间</td>
		<td width="6%">发布状态</td>
		<td width="12%">编辑</td>
        <td>操作</td>
	  </tr>
      <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><tr >
	     <td align="center"><input name="id[]" type="checkbox" id="id[]" value="<?php echo ($vo["id"]); ?>" /></td>
		 <td height="22" align="center"><?php echo ($vo["id"]); ?></td>
		 <td align="left"><a href="<?php echo (C("cfg_domain")); ?>/news-<?php echo ($vo["id"]); ?>" target="_blank" style="color:#3C3C3C;"
		 title="<?php echo ($vo["remark"]); ?>"><?php echo ($vo["title"]); ?></a></td>
		 <td align="center"><a href="__URL__/index/class_id/<?php echo ($vo["class_id"]); ?>" title="<?php echo ($vo["name"]); ?>" style="color:blue;"><?php echo ($vo["name"]); ?></a></td>
		 <td align="center"><?php echo ($vo["flag"]); ?></td>
		 <td align="center"><?php echo (date("Y-m-d",$vo["add_time"])); ?></td>
		 <td align="center"><?php echo ($vo["ispublish"]); ?></td>
		 <td align="center"><?php echo ($vo["username"]); ?></td>
		 <td align="center">[<a href="javascript:edit(<?php echo ($vo["id"]); ?>)" >编辑</a>]&nbsp;[<a href="javascript:del(<?php echo ($vo["id"]); ?>)">删除</a>]</td>
	  </tr><?php endforeach; endif; else: echo "" ;endif; ?>
</table>
	  <div class="page"><?php echo ($page); ?></div>

</body>
</html>