<?php
   class NoticeModel extends Model
   {
	   	// 自动验证设置
	protected $_validate	 =	 array(
		array('title','require','请输入标题！'),
		array('content','require','内容不能为空！'),
		array('title','','该标题已经存在',0,'unique','add'),
		);
   }
?>