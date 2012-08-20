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

<div class="bodyTitle">
	<div class="bodyTitleLeft"></div>
  <div class="bodyTitleText">单页管理</div>
</div>
<h3 class="marginbot"><a href="__URL__/add" class="sgbtn">＋添加单页</a></h3>
		
	<table class="datalist fixwidth" >
	<form name="form1" method="post" action="__URL__/submit">
		<tr>
			<th><input type="checkbox" onClick="selAll(this, 'id[]')" value="选择全部" class="checkbox">选择</th>
			<th>标题</th>
			<th>提交时间</th>
			<th>操作</th>
		</tr>
		<?php if(isset($list)): ?><?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><tr>
			<td class="option"><?php if(($vo['id'])  >  "5"): ?><input name="id[]" type="checkbox" id="id[]" value="<?php echo ($vo["id"]); ?>" class="checkbox"><?php endif; ?><?php echo ($vo["id"]); ?></td>
			<td><?php echo ($vo["subject"]); ?>							</td>
			<td><?php echo (date("Y-m-d",$vo["add_time"])); ?></td>
			<td><a href="__URL__/edit/id/<?php echo ($vo["id"]); ?>"><img src="../Public/Images/edit.gif" alt="编辑" align="absmiddle" /></a>
			  <?php if(($vo['id'])  >  "5"): ?>　<a href="__URL__/del/id/<?php echo ($vo["id"]); ?>" onclick="return confirm('确定删除吗，此操作不可恢复');"><img src="../Public/Images/del.gif" alt="删除" align="absmiddle" /></a><?php endif; ?></td>
		</tr><?php endforeach; endif; else: echo "" ;endif; ?>
			<tr class="nobg">
				<td colspan="7"><?php echo ($page); ?></td>
			</tr><?php else: ?>
		  <tr><td colspan="7" align="center">无内容</td>
		  </tr><?php endif; ?>
			</form></table>
<p class="i">删除不可恢复,谨慎操作</p>
<br>
</body>
</html>