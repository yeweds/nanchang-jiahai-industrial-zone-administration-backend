<?php
class CommentModel extends Model{
	// 验证规则
	protected $_validate = array(
		array('usern','require','请输入您的大名！'),
		array('content','require','评论内容不能为空！'),	
		array('CheckCode','require','请输入验证码！'),
	);
}
?>