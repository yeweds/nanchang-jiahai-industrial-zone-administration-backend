<?php 
class PagesModel extends Model 
{
	// 自动验证设置
	protected $_validate	 =	 array(
		array('subject','require','标题必须'),
		array('content','require','内容必须'),
	
	);
	public $_auto		=	array(
		array('add_time','time',self::MODEL_INSERT,'function'),
		array('subject','dhtml','ALL','function'),
	);
}
?>