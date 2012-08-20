//-- 自定义在线编辑器
	var editor;
	var mini_items = ['source','undo','redo','fontname','fontsize','forecolor',
					  'hilitecolor','bold','italic','emoticons','removeformat',
					  'fullscreen'];  //迷你元素
	var full_items = ['source','undo','redo','preview','fontname','fontsize','forecolor',
					  'hilitecolor','bold','italic','underline','strikethrough','justifyleft',
					  'justifycenter','justifyright','justifyfull','insertorderedlist',
					  'insertunorderedlist','indent','outdent','emoticons','removeformat',
					  'fullscreen'];  //完全元素
	
	KindEditor.ready(function(K) {
		editor = K.create(id_name, {
					 themeType : 'default',//样式
					 newlineTag: 'br', //换行格式
					 filterMode: true, //过滤代码
					 uploadJson: '__PUBLIC__/Js/Kindeditor/php/upload_json.php',
					 fileManagerJson : '__PUBLIC__/Js/Kindeditor/php/file_manager_json.php',
					 allowFileManager : true,											 
					 items: mini_items  //当前自定义元素列表
		});
	});

	//获取数据	
	function checkForm(){
		editor.sync('my_editor'); //同步编辑器内容
		return true;
	}
//-- 编辑器调用结束 --