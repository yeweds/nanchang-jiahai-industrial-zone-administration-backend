<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>0791婚庆网 SystemManager</title>
<link rel="stylesheet" href="../Public/Css/login.css" type="text/css" media="all" />
<script type="text/javascript">
function fleshVerify(){
//重载验证码
var timenow = new Date().getTime();
document.getElementById('verifyImg').src= '__URL__/verify/'+timenow;
}
</script>
</head>
<body>
<div class="main">
  <div class="login">
    <form action="__URL__/checkLogin" method="post" >
      <input type="hidden" name="dopost" value="login">
      <div class="inputbox">
        <dl>
          <dt>用 户 名：</dt>
          <dd>
            <input type="text" name="username" id="username" size="20" onfocus="this.style.borderColor='#239fe3'" onblur="this.style.borderColor='#dcdcdc'" />
          </dd>
        </dl>
        <dl>
          <dt>密　　码：</dt>
          <dd>
            <input type="password" name="password" id="password" size="20" onfocus="this.style.borderColor='#239fe3'" onblur="this.style.borderColor='#dcdcdc'"/>
          </dd>
        </dl>
		
		<dl>
          <dt>验 证 码：</dt>
          <dd>
            <input type="text" name="seccode" id="seccode" size="11" onfocus="this.style.borderColor='#239fe3'" onblur="this.style.borderColor='#dcdcdc'" />
          <A HREF="javascript:fleshVerify()"><IMG SRC="__URL__/verify/" name="verifyImg" BORDER="0" align="absmiddle" id="verifyImg" title="请点图片刷新验证码"></a>          </dd>
        </dl>
		
        <dl>
          <dt>&nbsp;</dt>
          <dd>
            <input name="submit" type="submit" value="" class="input" />
          </dd>
        </dl>
      </div>
      <div class="butbox">
        <dl>
          <dt></dt>
          <dd>&nbsp;</dd>
        </dl>
      </div>
      <div style="clear:both"></div>
    </form>
	 <div style="height:90px; margin-left:60px; margin-top:40px;"></div>
  </div>
</div>
<div class="copyright">Copyright &copy;2012 Powered by <a href="http://www.0791hunqing.com/">0791hunqing.Com</a></div>

</body>
</html>