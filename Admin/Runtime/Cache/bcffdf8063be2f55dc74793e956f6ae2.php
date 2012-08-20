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
  <div class="bodyTitleText">编辑相册</div>
</div>
<h3 class="marginbot"><a href="__URL__/" class="sgbtn">返回相册管理</a>
<a href="javascript:PopWindow('__URL__/addAttach/info_id/<?php echo ($vo["id"]); ?>',600,700);" class="sgbtn">+添加图片(附件)</a>
<a href="javascript:history.go(-1);" style="float:right;"><strong>返回&gt;&gt;</strong></a>
</h3>

          <table width="100%">  
		  <form method="post" name="form1" action="__URL__/save" enctype ="multipart/form-data" onSubmit="return checkForm();">
          <input name="id" type="hidden" id="id" value="<?php echo ($vo["id"]); ?>" />
            <tr>
              <td width="13%">主标题</td>
              <td width="87%">
                <input name="title" type="text"  class="txt" id="title" size="60" value="<?php echo ($vo["title"]); ?>"/>
                <font color="red">*</font></td>
            </tr>
		    <tr>
              <td>权重(排序)</td>
		      <td><input name="sortrank" type="text" id="weight" style="width:50px" value="<?php echo ($vo["sortrank"]); ?>" />
		        (越大越靠前,最大9000)</td>
	        </tr>
			<tr>
              <td width="15%" height="30">所属栏目：</td>
              <td width="85%" height="30">
                <select name="class_id" id="class_id" >
					<?php if(is_array($class)): $i = 0; $__LIST__ = $class;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cvo): ++$i;$mod = ($i % 2 )?><option value="<?php echo ($cvo["id"]); ?>" <?php if($cvo['id']==$vo['class_id']){echo 'selected';} ?>><?php echo ($cvo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?> 
			</select> (相册类型)</td>
			</tr>
		<tr>
          <td width="13%">关键字</td>
          <td width="87%"><input type='text' name="keywords" id="keywords" style="width:60%" value="<?php echo ($vo["keywords"]); ?>" />手动填写用","分开</td>
        </tr>
		   <tr>
            <td width="13%">作者/来源</td>
            <td width="87%"><input name="author" type="text" id="author" style="width:160px" value="<?php echo ($vo["author"]); ?>" size="16"/>            </td>
          </tr>
			<tr>
              <td width="13%">关联商家</td>
              <td width="87%">
                <select name="shop_id" id="shop_id" size="10">
					<option value=''>a&nbsp;&nbsp;Null-不关联</option>
					<?php if(isset($loupan_list)): ?><?php if(is_array($loupan_list)): $i = 0; $__LIST__ = $loupan_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo_loupan): ++$i;$mod = ($i % 2 )?><option value="<?php echo ($vo_loupan["id"]); ?>" <?php if( $vo_loupan['id']==$vo['shop_id'] ) echo 'selected'; ?>
><?php echo (substr($vo_loupan["head_py"],0,1)); ?>&nbsp;&nbsp;<?php echo ($vo_loupan["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?><?php endif; ?>
				</select><br />(仅单选，当前已关联商家：<?php echo ($vo["mapping_name"]); ?> <font color="red"><?php echo ($vo["shop_id"]); ?></font>)
            </tr>
            <tr>
              <td>摘要</td>
              <td>
                <textarea name="remark" cols="60" rows="5" id="remark"><?php echo ($vo["remark"]); ?></textarea>
              简单概要描述，可以留空</td>
            </tr>
			<tr>
              <td>发布时间</td>
              <td><input type="text" name="add_time"  value="<?php echo (date('Y-m-d H:i',$vo["add_time"])); ?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})"/>(发布时间可修改)</td>
            </tr>
			<tr>
              <td>点击量</td>
              <td><input name="hits" type="text" id="hits" value="<?php echo ($vo["hits"]); ?>" size="5" /></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>
                <input type="submit"  value="提交更新" class="df_button" />
                <input type="reset" value="还原重填" class="df_button" /></td>
            </tr>
          </form>
		  <tr>
              <td width="13%">编辑</td>
              <td width="87%"><?php echo ($vo["username"]); ?></td>
            </tr>
		  </table>
        <p class="i">删除不可恢复,谨慎操作</p>
        <br>
</body>
</html>