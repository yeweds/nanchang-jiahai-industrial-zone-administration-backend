<?php
   class InfoModel extends Model
   {
	   	// 自动验证设置
	protected $_validate	 =	 array(
		array('title','require','请输入名称！'),
		array('class_id','require','请选择所属分类！'),
		array('title','','该名称已经存在',0,'unique','add'),
		);
	// 自动填充设置
	protected $_auto	 =	 array(
		array('add_time','time','ADD','function'),
		);
   }
?>