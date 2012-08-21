<?php
class LoginRecordModel extends Model
{
	public function record($mid) {
		load('extend'); //导入扩展函数
		$ip = get_client_ip();
			$data["uid"] = $mid;
			$data["login_ip"] = $ip;
			$data["login_time"] = time();
			$this->add($data);
	}

}
?>