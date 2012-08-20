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
  <div class="bodyTitleText">添加管理员</div>
</div>
<br>
<table width="500" cellSpacing=1 cellPadding=2 align="center" border=0 >
<form action="__URL__/insert" method="post" name="typeForm">
  <tr>
    <td width="112" height="30" >管理用户名：</td>
    <td width="365" ><input name="adminname" type="text" id="adminname" size="13" /></td>
  </tr>
  <tr>
    <td class="tright" >密码：</td>
    <td ><input name="pwd" type="password" id="pwd" 
      size="13" /></td>
  </tr>
  <tr>
    <td>
      <label for="types">选择权限--></label>
    </td>
    <td>
      <SELECT NAME="types" id="types" >
        <option value="1">超级管理员</option>
        <option value="2">普通管理员</option>
        <option value="8">编辑</option>
      </SELECT>
    </td>
  </tr>
  <tr>
    <td height="45" ></td>
    <td class="center"><span class="tright">
      <input type="submit" class="df_button" value="添加管理员"/>
    </span></td>
  </tr>
  </form>
</table>
</body>
</html>