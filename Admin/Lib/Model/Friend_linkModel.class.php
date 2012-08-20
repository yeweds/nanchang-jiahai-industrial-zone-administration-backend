<?php
/**
	explain: 友情链接模型类
	author:xiongyan
	date: 2011-3-20
**/
class Friend_linkModel extends Model {
	// 验证规则
	protected $_validate = array(
		array('webname','require','网站名称必须填写！'),	
		array('url','require','网站地址必须填写！'),
	);
	// 自动填充内容
	protected $_auto = array(
		array('addtime','time','ADD','function'),
	);
}
?>