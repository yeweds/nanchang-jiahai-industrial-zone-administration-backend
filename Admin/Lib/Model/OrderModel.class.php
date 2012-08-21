<?php
/**
	explain: 订单
	author: xiongyan
	date: 2009-7-2
**/
class OrderModel extends Model{
	// 验证规则
	protected $_validate = array(
		array('u_name','require','请输入您的大名！'),
		array('pro_no','require','请填写正确的产品编号！'),
		array('s_time','require','请输入送花时间！'),
		array('d_tel','require','订花人电话不能为空！'),
		array('s_tel','require','收花人电话不能为空！'),
	);

	// 自动填充内容
	protected $_auto = array(
		array('addtime','time','ADD','function'),
	);
}
?>