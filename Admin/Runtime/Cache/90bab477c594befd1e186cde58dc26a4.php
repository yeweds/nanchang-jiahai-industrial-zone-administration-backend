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
  <div class="bodyTitleText">修改管理员信息</div>
</div>
<br>
<table width="500" cellSpacing=1 cellPadding=2 align="center" border=0 >
<form action="__URL__/update/id/<?php echo ($listone["id"]); ?>" method="post" name="typeForm">
  <tr>
    <td width="112" height="30" >管理用户名：</td>
    <td width="365" ><?php echo ($listone["admin"]); ?></td>
  </tr>
  <tr>
    <td class="tright" >密码：</td>
    <td ><input name="pwd" type="text" id="pwd"></td>
  </tr>
  <tr>
    <td>全名:</td>
    <td><input type="text" name='realname' id='realname' value='<?php echo ($listone["realname"]); ?>'></td>
  </tr>
  <tr>
    <td><label for="types">选择权限: </label></td>
    <td>
        <SELECT NAME="types" id="types" >
          <option value="1">超级管理员</option>
          <option value="2">普通管理员</option>
          <option value="8">编辑</option>
        </SELECT>
    </td>
  </tr>
  <!-- <tr>
    <td>最后登录时间：</td>
    <td><input name="login_time" type="text" id="login_time" value="<?php echo ($listone["login_time"]); ?>"></td>
  </tr>
  <tr>
    <td>最后登录IP：</td>
    <td><input name="ip" type="text" id="ip" value="<?php echo ($listone["ip"]); ?>"></td>
  </tr> -->
  <tr>
    <td height="45" ></td>
    <td class="center"><div style="width:85%;margin:5px">
      <input name="id" type="hidden" id="id" value="<?php echo ($id); ?>">
  	  <input name="old_pwd" type="hidden" id="old_pwd" value="<?php echo ($listone["pwd"]); ?>">
      <input name="SaveEdit" type="submit" class="df_button" id="SaveEdit" value=" 修改 " />
      &nbsp;&nbsp;</div>
    </td>
  </tr>
  </form>
</table>
</body>
</html>