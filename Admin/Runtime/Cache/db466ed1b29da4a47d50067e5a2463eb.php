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

<body> <!-- 公共头部 -->
<script language="javascript">
  	var SELECT_NAV =  'system';  //选中系统维护
</script>
<link href="../Public/Css/system.css" rel="stylesheet" type="text/css"/>

 <div class="bodyTitle">
	<div class="bodyTitleLeft"></div>
  <div class="bodyTitleText">基本设置</div>
</div>

<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0">
  <tr>
   <td>
 <div class="container">
 <form method="post" action="__URL__/update_cfg" >
  <table class="opt" >
    <tr>
      <td colspan="2"><strong>基本设置(不清楚的项目尽量保持默认)</strong>
	  <!--font color="#FF0000">内核设置尽量不要修改，否则会影响系统运行</font --></p></td>
    </tr>
    <tr>
      <td>系统名称 </td>
      <td><input name="cfg_sitename" type="text" class="txt" id="cfg_sitename" value="<?php echo ($web_config["cfg_sitename"]); ?>" size="40" /></td>
    </tr>
    <tr>
      <td>系统简称</td>
    <td><input name="cfg_shortname" type="text" class="txt" id="cfg_shortname" value="<?php echo ($web_config["cfg_shortname"]); ?>" size="40" /></td>
    </tr>
    <tr>
      <td>访问域名</td>
      <td><input name="cfg_domain" type="text" class="txt" id="siteurl2" value="<?php echo ($web_config["cfg_domain"]); ?>" size="40" />
      <span class="tLeft">后面不得带'/'</span></td>
    </tr>
  <tr>
      <td>公司全称</td>
    <td><input name="cfg_company" type="text" class="txt" id="cfg_company" value="<?php echo ($web_config["cfg_company"]); ?>" /></td>
  </tr> 
    <tr>
      <td><span class="tRight">ICP备案号</span></td>
      <td><input name="cfg_icp" type="text" class="txt" id="cfg_icp" value="<?php echo ($web_config["cfg_icp"]); ?>" /></td>
    </tr> 
    <tr>
      <td>电　　话</td>
      <td><input name="cfg_tel" type="text" class="txt" id="telephone2" value="<?php echo ($web_config["cfg_tel"]); ?>" /></td>
    </tr>
    <tr>
      <td><span class="tRight">联系地址</span></td>
      <td><input name="cfg_address" type="text" class="txt" id="cfg_address" value="<?php echo ($web_config["cfg_address"]); ?>" size="30" /></td>
    </tr>
    <tr>
      <td>系统状态</td>
      <td><select name="cfg_status" id="cfg_status">
        <option value="1" <?php if(($web_config["cfg_status"])  ==  "1"): ?>selected="selected"<?php endif; ?>>正常运行</option>
        <option value="0" <?php if(($web_config["cfg_status"])  ==  "0"): ?>selected="selected"<?php endif; ?>>暂停访问</option>
            </select></td>
    </tr>
    <tr>
      <td>暂停原因</td>
      <td valign="top">简单描述网站暂停访问原因<br>
      <textarea name="cfg_stop_msg" cols="48" rows="3"  id="cfg_stop_msg" class="area"><?php echo ($web_config["cfg_stop_msg"]); ?></textarea></td>
    </tr>
<!--
<tr>
      <td colspan="2" class="borderBottom bgFleet"><strong>内核设置(非开发人员请不要修改，否则会引起系统不稳定)</strong></td>
</tr> 
    <tr>
      <td>默认城市ID</td>
      <td><input name="cfg_city" type="text"  value="<?php echo ($web_config["cfg_city"]); ?>" /> 默认为:1,南昌</td>
    </tr>
    <tr>
      <td>是否需要审核</td>
      <td><select  name="cfg_ischeck"  id="cfg_ischeck">
	<option <?php if(($web_config["cfg_ischeck"])  ==  "true"): ?>selected<?php endif; ?> value="true">是</option>
	<option <?php if(($web_config["cfg_ischeck"])  ==  "false"): ?>selected<?php endif; ?> value="false">否</option>
	</select></td>
    </tr>
    <tr>
      <td>用户权限控制Key</td>
      <td><input name="cfg_auth_key" type="text"  value="<?php echo ($web_config["cfg_auth_key"]); ?>" /> 默认为uid</td>
    </tr> -->
    <tr>
      <td>前台登录有效期</td>
      <td><input name="cfg_loginTime" type="text"  value="<?php echo ($web_config["cfg_loginTime"]); ?>" /> 秒</td>
    </tr>
	<!-- 
	<tr>
		<th colspan="2" class="bgFleet borderBottom">发邮件参数</th>
	<tr>
	<tr>
	  <td>发邮件服务器：</td>
	  <td><input name="cfg_smtp_host" type="text" class="medium bLeftRequire" id="cfg_smtp_host" value="<?php echo ($web_config["cfg_smtp_host"]); ?>" /></td>
	</tr>
	<tr>
		<td>发件人邮箱</td>
		<td><input name="cfg_email" type="text" class="txt" id="email" value="<?php echo ($web_config["cfg_email"]); ?>" /></td>
	</tr>
	<tr>
		<td>用户名：</td>
		<td><input name="cfg_mail_user" type="text" class="medium bLeftRequire" id="cfg_mail_user" value="<?php echo ($web_config["cfg_mail_user"]); ?>"></td>
	<tr>
	<tr>
		<td>密　码：</td>
		<td><input name="cfg_mail_pwd" type="text" class="medium bLeftRequire" id="cfg_mail_pwd" value="<?php echo ($web_config["cfg_mail_pwd"]); ?>"></td>
	<tr>
-->    <tr>
      <td colspan="2"> <input  type="submit" class="df_button" id="btnSubmit" value=" 提交更新 ">
	  &nbsp;&nbsp;
	<input type="reset" class="df_button" value=" 还原重填 "></td>
    </tr> 
 </table>
<span style="display:none">{__NOTOKEN__}</span>
</form>
</div>
 
	</td>
  </tr>
</table>
 
</body>
</html>