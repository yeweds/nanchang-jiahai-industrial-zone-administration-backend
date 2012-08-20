<?php
//用户模型2011-4-19
class UserModel extends Model
{
// 验证规则
	protected $_validate = array(
		array('email','require','Email必须填写！'),
		array('username','require','请输入您的大名！'),
		array('email','','该Email已经存在，若忘记密码，请自助找回！',0,'unique',1), // 在新增的时候验证uname字段是否唯一
		
	);

//登录记录
	public function login_record($mid) {
		load('extend'); //导入扩展函数
		$ip = get_client_ip();
			$map["id"] = $mid;
			$data["login_ip"] = $ip;
			$data["login_time"] = time();
			$this->where($map)->save($data);
	}
}
?>
