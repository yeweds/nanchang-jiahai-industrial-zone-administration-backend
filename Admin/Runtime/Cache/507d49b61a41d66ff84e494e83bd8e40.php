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
<!--导入编辑器开始-->
<link rel="stylesheet" href="__PUBLIC__/Js/Kindeditor/themes/default/default.css" />
<script charset="utf-8" src="__PUBLIC__/Js/Kindeditor/kindeditor.js"></script>
<script charset="utf-8" src="__PUBLIC__/Js/Kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript">
//-- 自定义在线编辑器
	var editor;
	var mini_items = ['source','undo','redo','fontname','fontsize','forecolor',
					  'hilitecolor','bold','italic','emoticons','removeformat',
					  'fullscreen'];  //迷你元素
	var full_items = ['source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'cut', 'copy', 'paste',
        'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
        'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
        'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
        'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
        'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image',
        'flash', 'insertfile', 'table', 'hr', 'emoticons', 'pagebreak',
        'link', 'unlink' ];  //完全元素
	
	KindEditor.ready(function(K) {
		editor = K.create('#my_editor', {
					 themeType : 'default',//样式
					 newlineTag: 'br', //换行格式
					 filterMode: true, //过滤代码
					 uploadJson: '__PUBLIC__/Js/Kindeditor/php/upload_json.php',
					 fileManagerJson : '__PUBLIC__/Js/Kindeditor/php/file_manager_json.php',
					 allowFileManager : true,											 
					 items: full_items  //当前自定义元素列表
		});
	});

	//获取数据	
	function checkForm(){
		editor.sync('my_editor'); //同步编辑器内容
		return true;
	}
</script>
<!-- 编辑器调用结束 -->


<div class="bodyTitle">
	<div class="bodyTitleLeft"></div>
  <div class="bodyTitleText">修改商家</div>
</div>

<h3 class="marginbot">
<a href="__APP__/Shop/" class="sgbtn" >返回商家管理</a>
<!-- <a href="__APP__/Shop/edit_series/id/<?php echo ($shop["id"]); ?>" class="sgbtn">修改最新套系</a> -->
<a href="__APP__/Series/add/shop_id/<?php echo ($shop["id"]); ?>" class="sgbtn">＋发布套系</a>
<!-- <a href="javascript:PopWindow('__URL__/addAttach/info_id/'+<?php echo ($shop["id"]); ?>,600,700);" class="sgbtn">＋添加附件</a> -->
<a href="__APP__/Pic/add//info_id/<?php echo ($shop["id"]); ?>" class="sgbtn">＋添加相册</a>
<a href="javascript:history.go(-1);" style="float:right;"><strong>返回&gt;&gt;</strong></a>
</h3>
<form method="post" name="form1" action="__URL__/save" enctype ="multipart/form-data" onSubmit="return checkForm();">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="15%" height="30">公司名称：</td>
              <td width="85%" height="30">
                <input name="title" type="text"  class="txt" id="title" value="<?php echo ($shop['name']); ?>" size="60"/>
				<font color="red">*</font>
				<input type="hidden" name="id" value="<?php echo ($shop['id']); ?>"/>				</td>
            </tr>
<!--             <tr>
              <td width="15%" height="30">公司类型：</td>
              <td width="85%" height="30">
                <input name="corp_type" type="text"  class="txt" id="title" value="<?php echo ($shop['corp_type']); ?>" size="30"/>
				<font color="red">*</font></td>
            </tr> -->
		   <tr>
		     <td height="30">商家简称：</td>
		     <td height="30"><input name="sub_title" type="text"  class="txt" id="sub_title" value="<?php echo ($shop["sub_title"]); ?>" size="50"/>
		       (副标题)</td>
    	 </tr>
		  <tr>
			  <td height="30">摘要：</td>
			  <td height="30"><textarea name="remark" cols="40" rows="5" id="remark"><?php echo ($shop["remark"]); ?></textarea></td>
    	  </tr>
		   <tr>
		     <td height="30">权重：</td>
		     <td height="30"><input name="sortrank" type="text" id="weight" style="width:50px" value="<?php echo ($shop['sortrank']); ?>" />
	         (越大越靠前,最大1000)</td>
	        </tr>
			<tr>
              <td height="30"><span class="tRight tTop">上传Logo：</span></td>
			  <td height="30"><span id="input_up"><input name="logo_url" type="text" id="logo_url" value="<?php echo ($shop["logo_url"]); ?>" size="40" readonly /> 
    <a href="#" onclick="show_input_reup()"><font color="red">重传</font></a></span>
	<span id="input_reup" style="display:none"><input name="pic_reup" type="file" id="pic_reup" size="40" /> 
    <a href="#" onclick="show_input_up()"><font color="red">返回</font></a></span></td>
    		</tr>
			<tr>
			  <td height="30">图片数量:</td>
			  <td height="30" id="set_pic"><?php echo (($shop["pic_sum"])?($shop["pic_sum"]):"0"); ?>  张图片</td>
             </tr>
			<tr>
              <td width="15%" height="30">所属栏目：</td>
              <td width="85%" height="30">
                <select name="class_id" id="class_id" >
					<?php if(is_array($class)): $i = 0; $__LIST__ = $class;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><option value="<?php echo ($vo["id"]); ?>" <?php if($vo['id']==$class_id){echo 'selected';} ?>><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?> 
			</select> (商家类型)</td>  </tr>
            <tr>
              <td height="30">商家介绍:</td>
              <td height="30">    <!-- 编辑器调用开始 -->
              <textarea id="my_editor" name="content" style="width:700px; height:300px; visibility:hidden;" ><?php echo ($shop["content"]); ?></textarea><!-- 编辑器调用结束 -->  <font color="red">*</font></td>
            </tr>
			 <tr>
               <td height="30">信誉星级:</td>
			   <td><select name="star" id="star">
                   <option value="5" <?php if($shop['star']==5) echo 'selected'; ?> >★★★★★</option>
                   <option value="4" <?php if($shop['star']==4) echo 'selected'; ?> >★★★★</option>
                   <option value="3" <?php if($shop['star']==3) echo 'selected'; ?> >★★★</option>
                   <option value="2" <?php if($shop['star']==2) echo 'selected'; ?> >★★</option>
                   <option value="1" <?php if($shop['star']==1) echo 'selected'; ?> >★</option>
                 </select>               </td>
			</tr>
			<tr>
				<td height="30">商家地址:</td>
				<td><input type="text" name="address" value="<?php echo ($shop['address']); ?>" size="80"/></td>
            </tr>
			 <tr>
            <td height="30">营业时间:</td>
            <td><input type="text" name="yingye_time" value="<?php echo ($shop["yingye_time"]); ?>" size="60"/></td>
            </tr>
			 <tr>
            <td height="30">是否备车:</td>
            <td><input type="text" name="is_needcar" value="<?php echo ($shop["is_needcar"]); ?>" size="10"/>1是，0否</td>
            </tr>
			 <tr>
            <td height="30">交通线路:</td>
            <td><input type="text" name="jiaotong" value="<?php echo ($shop["jiaotong"]); ?>" size="80"/></td>
            </tr>
			 <tr>
            <td height="30">包餐类别:</td>
            <td><input type="text" name="baocan" value="<?php echo ($shop["baocan"]); ?>" size="30"/></td>
            </tr>
			<tr>
            <td height="30">外景区域:</td>
            <td><input class='np' type='checkbox' name='wj_range[]' id='flag1' value='1' <?php if(($a)  ==  "1"): ?>checked<?php endif; ?> >[1]南昌市区
				<input class='np' type='checkbox' name='wj_range[]' id='flag2' value='2' <?php if(($b)  ==  "1"): ?>checked<?php endif; ?> >[2]南昌郊区
				<input class='np' type='checkbox' name='wj_range[]' id='flag3' value='3' <?php if(($c)  ==  "1"): ?>checked<?php endif; ?> >[3]省内
				<input class='np' type='checkbox' name='wj_range[]' id='flag4' value='4' <?php if(($d)  ==  "1"): ?>checked<?php endif; ?> >[4]省外
				<input class='np' type='checkbox' name='wj_range[]' id='flag5' value='5' <?php if(($e)  ==  "1"): ?>checked<?php endif; ?> >[5]国际路线
				</td>
            </tr>
			<tr>
            <td height="30">消费预算范围:</td>
            <td><input type="text" name="price_min" value="<?php echo ($shop["price_min"]); ?>" size="10"/>到<input type="text" name="price_max" value="<?php echo ($shop["price_max"]); ?>" size="10"/> 元</td>
            </tr>
			 <tr>
            <td height="30">网址:</td>
            <td><input type="text" name="website" value="<?php echo ($shop["website"]); ?>" size="60"/>(可不填或填本站商家页)</td>
            </tr>
            <tr>
            <td height="30">电子邮件:</td>
            <td><input type="text" name="email" value="<?php echo ($shop["email"]); ?>" size="40"/></td>
            </tr>
            <tr>
            <td height="30">QQ号 码:</td>
            <td><input type="text" name="qq" value="<?php echo ($shop["qq"]); ?>" /></td>
            </tr>
            <tr> 
            <td height="30">联系电话:</td>
            <td><input type="text" name="tel" value="<?php echo ($shop["tel"]); ?>"/></td>
            </tr>
            <tr>
              <td height="30">商家人气:</td>
              <td><input name="hits" type="text" id="hits" value="<?php echo ($shop["hits"]); ?>"/> 
              点击数可修改 </td>
            </tr>
            <tr>
              <td height="30">入驻时间:</td>
              <td><?php echo (date("Y-m-d H:i:s",$shop["add_time"])); ?></td>
            </tr>
            <tr>
              <td height="40">&nbsp;</td>
              <td height="40">
                <input type="submit"  value="确定修改" class="df_button" />
                <input type="reset"  value="还原重填" class="df_button" /></td>
            </tr>
        </table>
</form>
        
        <br>
</body>
</html>

<script type="text/javascript">
function show_input_reup(){
//重传图片
	$("#input_up").css("display","none");
	$("#input_reup").css("display","inline");
}
function show_input_up(){
//显示文本框
	$("#input_reup").css("display","none");
	$("#input_up").css("display","inline");
}
</script>