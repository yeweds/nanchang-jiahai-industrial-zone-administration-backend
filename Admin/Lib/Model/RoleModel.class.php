<?php 
/* 角色组模型 */
class RoleModel extends Model 
{
	public $_validate = array(
		array('name','require','角色名称必填'),
		);

	public $_auto		=	array(
		array('addtime','time',self::MODEL_INSERT,'function'),
		array('updatetime','time',self::MODEL_UPDATE,'function'),
		);

//获取当前角色所拥有的权限
	public function getAccessList($groupId)
	{
		$table = $this->tablePrefix.'role_access';
		$rs = $this->db->query('SELECT `role_id` FROM '.$table.'  WHERE `role_id`='.$groupId);
		//生成一维数组
		if(is_array($rs)){
			foreach($rs as $k=>$v){
				$list[$k] = $v['node_id'];
			}
		}
		return $list;
	}

//修改当前角色的权限
//Parameter：   $groupId 角色组ID，$list  选中权限功能代码数组
	public function setAccessList($groupId, $list=null)
	{
		if(empty($list)) {
			return true;
		}
		$table = $this->tablePrefix.'role_access';
		$id = implode(',',$list);
		$where = 'a.id ='.$groupId.' AND b.id in('.$id.')';
		$sql = 'INSERT INTO '.$table.' (role_id,node_id) SELECT a.id,b.id FROM '.$this->tablePrefix.'role a, '.$this->tablePrefix.'node b  WHERE '.$where;
		$result = $this->db->execute($sql);
		if($result===false) {
			return false;
		}else {
			return true;
		}

	}

//删除权限列表
	public function delAccessList($groupId)
	{
		$table = $this->tablePrefix.'role_access';
		$result = $this->db->execute('DELETE FROM '.$table.' WHERE `role_id`='.$groupId);
		if($result===false) {
			return false;
		}else {
			return true;
		}
	}
}
?>