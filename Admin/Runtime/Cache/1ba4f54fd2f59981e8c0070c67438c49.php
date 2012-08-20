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
<script language="javascript" src="__PUBLIC__/DatePicker/WdatePicker.js"></script>
<!-- 日历插件 -->
<script>
function checkUpload(){
    if(document.getElementById('attach_arr').value==''){
         alert("请选择图片类型");
		 return false;
	}
	if(document.getElementById('savename').value==''){
         alert("请填写保存名称");
		 return false;
	}
	if(document.getElementById('file').value==''){
         alert("请上传图片");
		 return false;
	}
}

var count= 2 ;   
var maxfile = 5;
function addUpload() {  
	if(count >= maxfile)    return;//限制最多maxfile个文件框
	count++; 
	//自增id不同的HTML对象，并附加到容器最后
	var newDiv =  "<div id=\"divUpload"+ count +"\" >"
		+ " <input id=\"file" + count + "\" type=\"file\" size=\"50\" name=\"file[]\" >"
		+ " <a href=javascript:delUpload('divUpload" + count + "');>删除</a>"
		+ " </div>";   
	  document.getElementById("uploadContent").insertAdjacentHTML("beforeEnd", newDiv);     
}   
    //删除指定元素
function delUpload(diva) {  
	count--; 
	document.getElementById(diva).parentNode.removeChild(document.getElementById(diva));   
}   
</script>
<form action="__APP__/Attach/Insert" enctype ="multipart/form-data" method="post" onsubmit='return checkUpload();'>
<input type="hidden" name="model_name" value="pics" /><!-- 数据模型名 -->
<table cellSpacing="1" cellPadding="2" width="95%" align="center" border="0">
    <tr>
        <th>
		上传附件
        </th>
    </tr>
    <tr>
        <td>
        <select name="attach_arr" id="attach_arr"/>*
		<option value="999|6,999|精彩图片">精彩图片</option><!-- 约定999 -->
          <!-- <option value="">-请选择图片类型-</option>
          <?php if(isset($attach_list)): ?><?php if(is_array($attach_list)): $i = 0; $__LIST__ = $attach_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo_attach): ++$i;$mod = ($i % 2 )?><option value="<?php echo ($vo_attach["id"]); ?>|<?php echo ($vo_attach["curr_path"]); ?>|<?php echo ($vo_attach["name"]); ?>"><?php echo (strtoupper($vo_attach["head_py"])); ?> <?php echo ($vo_attach["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?><?php endif; ?> --> </td>
    </tr>
	<tr>
      <td>
	  上传图片：<a href="javascript:addUpload()">+增加附件框</a>
	  <br><div id="uploadContent">
       <div id='div1'><input id='file1' type='file' size=50 name='file[]'></div>
        </div>
	  </td>
    </tr>
    <tr>
      <td>
	  <input name="info_id" type="hidden" id="info_id" size="40" value="<?php echo ($info_id); ?>">
	  <input name="user_id" type="hidden" id="user_id" size="40" value="<?php echo ($user_id); ?>"> 
	  </td>
    </tr>
	<tr>
      <td>
		  <table>
		  <tr>
			<td>附件说明：<br></TD>
			<td><textarea name='remark' rows='5' cols='40'></textarea></td>
		  </tr>
		  <tr>
		    <td>指定时间：</TD>
		    <td><input name="upload_time" type="text" id="upload_time"  onclick="WdatePicker()" value=""/></td>
		    </tr>
		  </table>
        
	  </td>
    </tr>
	<tr>
      <td>
        <input name="submit" type="submit" id="submit" size="40" value="上传" class="df_button">
		<input name="reset" type="reset" id="reset" size="40" value="重置" class="df_button">
	  </td>
    </tr>
  </table>
</form>
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <th>该相册已经上传</th>
  </tr>
  <tr>
    <td><!-- 熊彦添加 -->
		<div class="page"><?php echo ($page); ?> &nbsp; </div>
		<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 5 )?><div style=" width: 120px; height: 120px; float:left;">
			<a href="<?php echo (trim($vo["savepath"],'.')); ?><?php echo ($vo["savename"]); ?>" alt="查看大图" ><img src="<?php echo (trim($vo["savepath"],'.')); ?>thumb_<?php echo ($vo["savename"]); ?>" height="100" width="100" title="<?php echo ($vo["remark"]); ?>"></a><br/>
			<center>
				<?php if(($vo["id"])  ==  $df_att_id): ?><span style="color:red;">当前默认</span>
				<?php else: ?>
				<a href="__URL__/setDefaultPic/att_id/<?php echo ($vo["id"]); ?>/info_id/<?php echo ($info_id); ?>" >设为默认</a><?php endif; ?>
			</center>
			</div>
			<?php if(($mod)  ==  "5"): ?><div style="clear:both;"></div><br/><?php endif; ?><?php endforeach; endif; else: echo "" ;endif; ?>
		<?php if(!isset($list)): ?>暂无<?php endif; ?>
	</td>
  </tr>
</table>

</body>
</html>