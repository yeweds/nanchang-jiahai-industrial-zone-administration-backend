<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo ($charset); ?>" />
<meta http-equiv='Refresh' content='<?php echo ($waitSecond); ?>;URL=<?php echo ($jumpUrl); ?>'>
<title>0791婚庆网提示您</title>
<link rel="stylesheet" href="../Public/Css/main.css" type="text/css" media="all" />
<style>
.red{ color: #FF0000;}
.blue{ color:blue;}
</style>
</head>
<body><div id="append"></div>	<div class="container">
		<div class="ajax rtninfo">
			<div class="ajaxbg">
				<h4>0791婚庆网提示您：<?php echo ($msgTitle); ?></h4>
<?php if(isset($message)): ?><?php if(($status)  ==  "0"): ?><p  class="red"><?php echo ($message); ?></p><?php endif; ?>
		<?php if(($status)  ==  "1"): ?><p  class="blue"><?php echo ($message); ?></p><?php endif; ?><?php endif; ?>
	<?php if(isset($error)): ?><p><font color="red"><?php echo ($error); ?></font></p><?php endif; ?>
<p><span style="color:blue;font-weight:bold"><?php echo ($waitSecond); ?></span> 秒后自动跳转,如果不想等待,直接点击 <a HREF="<?php echo ($jumpUrl); ?>">这里跳转</a> </p>
					
							</div>
		</div>
	</div>

</body>
</html>