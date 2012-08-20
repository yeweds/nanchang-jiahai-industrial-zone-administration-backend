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
<link href="../Public/Css/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript">
//指定当前组模块URL地址
	function child(id){
		location.href = URL+"/index/b_classid/"+id;
	}
</script>

<div class="bodyTitle">
	<div class="bodyTitleLeft"></div>
  <div class="bodyTitleText">类别管理</div>
</div>
<table >
  <tr>
    <td width="16%"><input type="button" name="delete" value="删除选中项" class="df_button_green" onClick="delAll()"></td>
    <td><input type="button" id="" name="edit" value="重新排序" class="df_button_green" onClick="upsort()"></td>
	<td><div id="result" class="none result" style="letter-spacing:2px"></div></td>
    </tr>
</table>

<table class="datalist fixwidth">
<form action="" method="post" id="list_form" name="list_form" enctype="multipart/form-data">
  <tr>
    <td  align="left" colspan="6" height="25"><?php if(isset($classname)): ?>[ <?php echo ($classname); ?> ]<?php endif; ?>[ <?php echo ($classlevel); ?>级 ]<B> 栏目分类管理</B><?php if(($classlevel)  !=  "1"): ?><a href="javascript:child('<?php echo ($classid); ?>')" class="sgbtn" >返回上级栏目</a><?php endif; ?></td>
    <td width="501" align="right"><?php if(($classlevel)  !=  "1"): ?><a href="javascript:history.go(-1)">返回&gt;&gt;&nbsp;&nbsp;</a><?php endif; ?></td>
  </tr>
  <?php if(!isset($list)): ?><tr>
    <td height="25" colspan="6">暂无记录</td>
        <td>&nbsp;</td>
  </tr><?php endif; ?>
  <?php if(isset($list)): ?><tr>
    <td width="35" align="center">
      <input name="chkAll" type="checkbox" id="check" value="选择全部"  onClick="selAll(this,'id[]')">    </td>
    <td width="52" align="center">ID</td>
    <td width="189" align="center">分类名</td>
    <td width="73" align="center">排序</td>
    <td width="106" align="center">分类路径</td>
	<td width="84" align="center">是否显示为导航</td>
    <td height="25" align="center">操作</td>
    </tr>
  <?php $i=1;
	foreach($list as $vo){ ?>
  <tr>
    <td align="center"><input name="id[]" type="checkbox" id="id[]" value="<?php echo ($vo["id"]); ?>" /><input name="str[]" type="hidden" id="str[]" value="<?php echo ($vo["id"]); ?>" /></td>
    <td align="center"><?php echo ($vo["id"]); ?></td>
    <td align="left" title="点击查看下一级栏目"><?php
		for($k=1;$k<=$vo['level'];$k++)
			echo "&nbsp;&nbsp;";
		echo "├&nbsp;";
		if($vo['level'] % 2!=0)
			echo "<a href=\"javascript:child('$vo[id]')\">".$vo['name']."</a>";
		else
			echo "<a href=\"javascript:child('$vo[id]')\">".$vo['name']."</a>"
	?>&nbsp;(<?php echo ($vo["count"]); ?>)</td>
    <td align="center"><input name="sortrank[]" type="text" id="sortrank[]" value="<?php echo ($vo["sortrank"]); ?>" size="4"></td>
    <td align="center"><?php echo ($vo["curr_path"]); ?>
	</td>
	<td align="center"><?php if(($vo['isnav'])  ==  "1"): ?>是<?php else: ?>否<?php endif; ?>
	</td>
    <td height="25" align="center"><a href="javascript:child('<?php echo ($vo[id]); ?>')" class="sgbtn">查看下级栏目</a> [<a href="__URL__/add/classid/<?php echo ($vo["id"]); ?>/level/<?php echo ($vo['level']); ?>">添加子类</a>] 
	[<a href="__URL__/edit/id/<?php echo ($vo["id"]); ?>/level/<?php echo ($vo['level']); ?>">编辑</a>] 
	[<a href="javascript:del(<?php echo ($vo["id"]); ?>)">删除</a>] 
    <!-- &nbsp;|&nbsp;&nbsp;[<a href="__APP__/Doc/add/classid/<?php echo ($vo["id"]); ?>/level/<?php echo ($vo['level']); ?>/type/<?php echo ($vo['doc_class']); ?>">添加
<?php if($vo['doc_class']=='doc'){
				echo '文档';
                }else{
                echo '相册';
                }
 ?>
</a>] 
    [<a href="__APP__/Doc/index/class_id/<?php echo ($vo["id"]); ?>/level/<?php echo ($vo['level']); ?>/type/<?php echo ($vo['doc_class']); ?>">查看
<?php if($vo['doc_class']=='doc'){
				echo '文档';
                }else{
                echo '相册';
                }
 ?>
列表<span class="fontRed"><strong>(<?php echo ($vo["count"]); ?>)</strong></span></a>] --> </td>
    </tr>
  <?php $i++;
	} ?><?php endif; ?>
  </form>
</table>
<table width="100" >
  <tr>
    <td width="16%"><input type="button" name="delete2" value="删除选中项" class="df_button_green" onclick="delAll()" /></td>
    <td><input type="button" id="edit" name="edit2" value="重新排序" class="df_button_green" onclick="upsort()" /></td>
    <td><div id="result2" class="none result" style="letter-spacing:2px"></div></td>
  </tr>
</table>
<div class="page"><?php echo ($page); ?></div>
<p class="i">删除不可恢复,谨慎操作</p>
<br>
</body>
</html>