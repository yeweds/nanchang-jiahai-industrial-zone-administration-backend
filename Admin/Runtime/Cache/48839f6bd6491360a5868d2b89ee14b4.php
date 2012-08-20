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
  <div class="bodyTitleText">管理员管理</div>
</div>
<script type="text/javascript">
	function child(id){
		location.href = URL+"/index/b_classid/"+id;
	}
</script>
</head>
<body>

<table width="95%"  border="0" cellspacing="0" cellpadding="0"  align="center">
  <tr>
    <td width="21%" height="30"><input type="button" name="delete" value="删除选中" class="df_button_green" onClick="delAll()"> </td>
  <td><div id="result" class="none result" style="font-family:微软雅黑,Tahoma;letter-spacing:2px"></div></td>
  </tr>
</table>

<table width="95%" border="0" align="center" cellpadding="1" cellspacing="2">
<form action="__URL__/insert" method="post" name="TypeForm">
<tr>	<td width="15%" align="right" class="tright">管理用户名：</td>
	<td width="12%">
<input name="adminname" type="text" id="adminname" size="13">	</td>
	<td width="7%" class="tright">密码：</td>
	<td width="13%"><input name="pwd" type="password" id="pwd" 
      size="13" /></td>
    <td width="7%" class="tright"><!--类型：--></td>
	<td width="14%"><!--<SELECT NAME="types" id="types" >
	<option value="1">超级管理员</option>
	<option value="2">普通管理员</option>
	<option value="5">网站客服</option>
	</SELECT>-->
	 </td>
	<td class="tright">	  <input type="submit" class="df_button" value="添加管理员"/></td>
	</tr>
</FORM>  
</table>

<table width="95%" class="datalist fixwidth">
  <?php if(!isset($list)): ?><tr>
    <td height="25" colspan="7" >暂无记录</td>
  </tr><?php endif; ?>
  <?php if(isset($list)): ?><tr>
    <td width="38" height="25" align="center">
      <input name="chkAll" type="checkbox" id="check" value="checkbox"  onclick="selAll(this,'key[]')"></td>
    <td width="63" align="center">ID</td>
    <td width="152" align="center">管理员用户名</td>
    <td width="165" align="center">类型</td>
    <td width="189" align="center">最后登录时间</td>
    <td width="112" align="center">最后登录IP</td>
    <td width="192" height="25" align="center">操作</td>
  </tr>
<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><tr>
    <td height="25" align="center"><input name="key[]" type="checkbox" id="key[]" value="<?php echo ($vo["id"]); ?>" /></td>
    <td align="center"><?php echo ($vo["id"]); ?></td>
    <td><?php echo ($vo["admin"]); ?></td>
    <td><?php echo ($vo["role_id"]); ?></td>
    <td align="center"><?php echo (date('Y-m-d H',$vo["login_time"])); ?></td>
    <td align="center"><?php echo ($vo["ip"]); ?></td>
    <td height="25" align="center">[<a href="__URL__/edit/id/<?php echo ($vo["id"]); ?>">编辑</a>] [<a href="javascript:del(<?php echo ($vo["id"]); ?>)">删除</a>] </td>
  </tr><?php endforeach; endif; else: echo "" ;endif; ?><?php endif; ?>
</table>
<div class="page"><?php echo ($page); ?></div>

</body>
</html>