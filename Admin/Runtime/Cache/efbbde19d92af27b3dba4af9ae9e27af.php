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
  <div class="bodyTitleText">添加新闻</div>
</div>
<h3 class="marginbot"><a href="__URL__/" class="sgbtn">返回新闻管理</a></h3>
		
          <table width="100%">
		  <form method="post" name="form1" action="__URL__/Insert" enctype ="multipart/form-data" onSubmit="return checkForm();">
            <tr>
              <td width="13%">主标题</td>
              <td width="87%">
                <input name="title" type="text"  class="txt" id="title" size="60"/>(长)
				颜色：<select name="title_color" id="title_color">
				<option value="">-无-</option>
				<option value="r" style="background-color:#ff0000;color:#ff0000">red</option>
				<option value="g" style="background-color:#006600;color:#006600">green</option>
				<option value="b" style="background-color:#0000ff;color:#0000ff">blue</option>
				</select>
				</td>
            </tr>
            <tr>
              <td width="13%">副标题</td>
              <td width="87%">
                <input name="sub_title" type="text"  class="txt" id="sub_title" size="50"/>(短)
				<label for="isshow_sub_title">
				<input class='np' type='checkbox' name='isshow_sub_title' id='isshow_sub_title' value='1' 
				<?php if(empty($vo["isshow_sub_title"])): ?><?php else: ?>checked<?php endif; ?> >设置为首页显示副标</label>
			  </td>
            </tr>
			<tr>
            <td width="105">自定义属性</td>
            <td width="526">
            	<input class='np' type='checkbox' name='flag[]' id='flagsh' value='h' />头条,置顶[h]
				<input class='np' type='checkbox' name='flag[]' id='flagsc' value='c' />推荐[c]
				<input class='np' type='checkbox' name='flag[]' id='flagsf' value='f' />幻灯[f]
				<input class='np' type='checkbox' name='flag[]' id='flagsa' value='a' />焦点[a]
				<input class='np' type='checkbox' name='flag[]' id='flagss' value='s' />滚动[s]
				<input class='np' type='checkbox' name='flag[]' id='flagsb' value='b' />加粗[b]
				<input class='np' type='checkbox' name='flag[]' id='flagsp' value='p' />图片[p]
            </td>
           </tr>
		   <tr>
          <td width="90">TAG标签</td>
          <td><input name="tag" type="text" id="tags" value="" style="width:300px" />(','号分开，单个标签小于12字节)&nbsp;&nbsp;
            权重(排序)：<input name="sortrank" type="text" id="weight" style="width:50px" value="0" />(越大越靠前,最大1000)</td>
           </tr>
		   <tr>
          <td width="90">关键字</td>
          <td width="448"><input type="text" name="keywords" id="keywords" style="width:80%" value="" />手动填写用","分开<br/></td>
        </tr>

		   <tr>
            <td width="90">文章来源</td>
            <td width="240">
            	<input name="source" type="text" id="source" style="width:160px" value="南昌婚庆网" size="16"/> &nbsp;&nbsp;作者：
				<input name="author" type="text" id="author" style="width:160px" value="<?php echo ($realname); ?>" size="16"/> ( 均可修改 )
            </td>
          </tr>
			<tr>
              <td width="13%">上传缩略图片</td>
              <td width="87%"><input name="pic" type="file"  class="txt" id="pic" size="50"/>&nbsp;建议尺寸:228*196像素</td>
            </tr>
			<tr>
			<tr>
              <td width="13%">栏目</td>
              <td width="87%">
                <select name="class_id" id="class_id" value="<?php echo ($vo["class_id"]); ?>"><?php echo ($vo["name"]); ?>
					<?php if(isset($list_class)): ?><?php if(is_array($list_class)): $i = 0; $__LIST__ = $list_class;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo_class): ++$i;$mod = ($i % 2 )?><?php if(($vo_class["_child"])  !=  ""): ?><optgroup label="<?php echo ($vo_class["name"]); ?>">
							<?php if(is_array($vo_class["_child"])): $i = 0; $__LIST__ = $vo_class["_child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub): ++$i;$mod = ($i % 2 )?><option value="<?php echo ($sub["id"]); ?>">|- <?php echo ($sub["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
						</optgroup>
						<?php else: ?>
						<option value="<?php echo ($vo_class["id"]); ?>"><?php echo ($vo_class["name"]); ?></option>
							<?php if(is_array($vo_class["_child"])): $i = 0; $__LIST__ = $vo_class["_child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub): ++$i;$mod = ($i % 2 )?><option value="<?php echo ($sub["id"]); ?>">|- <?php echo ($sub["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?><?php endif; ?><?php endforeach; endif; else: echo "" ;endif; ?><?php endif; ?>
				</select> 注: 若该分类下有子分类，则不允许选中
            </tr>
			<tr>
              <td width="13%">关联商家</td>
              <td width="87%">
                <select name="info_id[]" id="info_id[]" size="10" multiple="multiple">
				   <option value=''>a null-不关联</option>
					<?php if(isset($loupan_list)): ?><?php if(is_array($loupan_list)): $i = 0; $__LIST__ = $loupan_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo_loupan): ++$i;$mod = ($i % 2 )?><option value="<?php echo ($vo_loupan["id"]); ?>" ><?php echo (substr($vo_loupan["head_py"],0,1)); ?>&nbsp;&nbsp;<?php echo ($vo_loupan["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?><?php endif; ?>
				</select>
				(默认不关联)
				</td>
            </tr>
            <tr>
              <td class="red">转向链接</td>
              <td>
                <input name="redirecturl" size="70" id="redirecturl" />
              地址前必须带有 http:// 仅在需要跳转时使用，平时留空</td>
            </tr>
            <tr>
              <td>摘要</td>
              <td>
                <textarea name="remark" cols="60" rows="4" id="remark"></textarea>
              简单概要描述，可以留空</td>
            </tr>
            <tr>
              <td>内容</td>
              <td><!-- 编辑器调用开始 --><textarea id="my_editor" name="content" style="width:700px;height:300px;"></textarea>
			  <!-- 编辑器调用结束 --> </td>
            </tr>
			<tr>
              <td>是否发布</td>
              <td><label for="ispub_0"><input type="radio" name="ispublish" id="ispub_0" value="0" checked/>否</label>&nbsp;
			  &nbsp;<label for="ispub_1"><input type="radio" name="ispublish" id="ispub_1" value="1"/>是</label>
			  </td>
            </tr>
			<tr>
              <td width="13%">编辑</td>
              <td width="87%"><input type="hidden" name="user_id" size="5" value="<?php echo ($user_id); ?>"/><?php echo ($username); ?></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>
                <input type="submit"  value="确定新增" class="df_button" />
                <input type="reset"  value="还原重填" class="df_button" /></td>
            </tr>
          </form></table>
        <p class="i">删除不可恢复,谨慎操作</p>
<br>
</body>
</html>