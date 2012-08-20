<?php 
/* 系统模型 */
class SystemModel extends Model 
{
	//public $_validate = array(
	//	array('name','require','角色名称必填'),
	//);

//获取信息ID
	public function getInfoId(){
		$map['name'] = "info_id";
		$rs = $this->where($map)->find();
		return $rs['curr_value'];
	}	

//更新信息ID
	public function setInfoId(){
		$map['name'] = "info_id";
		$rs = $this->setInc('curr_value', $map, '1');  //更新全局id
		return $rs;
	}

}
?>