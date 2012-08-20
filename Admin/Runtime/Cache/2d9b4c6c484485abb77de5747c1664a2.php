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
  <div class="bodyTitleText">用户管理</div>
</div>

<table border="0" cellspacing="0" cellpadding="0" style="margin:6px 0px;">
  <tr>
    <td width="21%" height="30"><input type="button" name="delete" value="删除选中用户" class="df_button_green" onClick="delAll()"> </td>
 <form method="post" action="__URL__/" id="search" >
  <td width="22%" align="right"><input type="text" name="title" title="标题查询" /></td>
    <td width="25%" align="center">
	 <select name="field" class="datainput" id="field">
        <option value="username">按用户名</option>
		<option value="id">按用户ID</option>
		<option value="email">登录邮箱</option>
     </select>
	</td>
    <td width="32%"><input type="submit" class="df_button_green" value="查 询" ></td>
</form>
  </tr>
</table>
<div id="result" class="none result" style="letter-spacing:2px"></div>

<table class="datalist fixwidth" border="0">
<tr>
<td width="4%" align="center"><input name="chkAll" type="checkbox" id="check" value="选择全部"  onClick="selAll(this,'id[]')"></td>
<td width="6%" height="25" align="center">ID</td>
<td width="11%" align="center">用户名</td>
<td width="24%" align="center">email(登录账号)</td>
<td width="15%" align="center">上次登陆时间</td>
<td width="15%" align="center">上次登陆IP</td>
<td width="14%" align="center">是否开通晒台</td>
<td width="11%" align="center">操作</td>
</tr>

<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><tr>
<td align="center"><input name="id[]" type="checkbox" id="id[]" value="<?php echo ($vo["id"]); ?>" /></td>
<td height="25" align="center"><?php echo ($vo["id"]); ?></td>
    <td align="center"><?php echo ($vo["username"]); ?></td>
    <td><?php echo ($vo["email"]); ?></td>
    <td align="center"><?php echo (date('Y-m-d H',$vo["login_time"])); ?></td>
    <td align="center"><?php echo ($vo["login_ip"]); ?></td>
    <td align="center"><?php if(($vo["open_blog"])  ==  "1"): ?><font color="red">是</font> [<a href="<?php echo (C("cfg_domain")); ?>/blog-<?php echo ($vo["id"]); ?>" target="_blank">进入</a>]
      <?php else: ?>否<?php endif; ?></td>
    <td align="center">
      [<a href="__URL__/edit/id/<?php echo ($vo["id"]); ?>" >编辑</a>] [<a href="javascript:del(<?php echo ($vo["id"]); ?>)" onClick="return confirm('确定要删除吗?')">删除</a>]	  </td>
  </tr><?php endforeach; endif; else: echo "" ;endif; ?>
</table>
<div class="page"><?php echo ($page); ?></div>

<p class="i">删除不可恢复,谨慎操作</p>
<br>
</body>
</html>