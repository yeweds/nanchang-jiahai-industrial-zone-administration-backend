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
<style type="text/css" >
	.cachelist td{ height:24px; line-height:24px; }
</style>
<div class="bodyTitle">
	<div class="bodyTitleLeft"></div>
  <div class="bodyTitleText">更新缓存</div>
</div>
<p class="i">修改系统设置后需更新系统缓存</p>
<div style="width:90%; margin:0 auto;" > <input name="checkbox" type="checkbox" class="checkbox" onClick="selAll(this,'id[]')" title="选择全部">选择全部</div> 

<table class="cachelist" width="90%" style="margin:0 auto;" >
<form method="post" name="form1" action="__URL__/submit_cache">
    <?php if(is_array($cache)): $i = 0; $__LIST__ = $cache;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub): ++$i;$mod = ($i % 2 )?><tr>
              <td class="option"><?php echo ($key); ?></td>
            </tr>
            <tr class="nobg">
              <td>
			  <?php if(is_array($sub)): $i = 0; $__LIST__ = $sub;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$dir): ++$i;$mod = ($i % 2 )?><input type="checkbox" name="id[]" value="<?php echo ($dir); ?>" class="checkbox"> <?php echo ($key); ?><br><?php endforeach; endif; else: echo "" ;endif; ?>
			</td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            <tr class="nobg">
             <td><input type="submit"  value="提交更新" class="df_button" /></td>
            </tr>
</form>          
</table>

</body>
</html>