<?php
/**
	explain: 留言簿
	author: xiongyan
**/
class GuestbookModel extends Model{
	// 验证规则
	protected $_validate = array(
		array('uname','require','请输入您的大名！'),
		array('content','require','留言内容不能为空,或包含非法字符！'),	
		array('CheckCode','require','验证码必须填写！'),
		array('CheckCode','CheckVerify','验证码错误',0,'callback'),
	);

	// 验证码处理
	public function CheckVerify() {
		return md5($_POST['CheckCode']) == $_SESSION['verify'];
	}
}
?>