<?php 
/**
 +------------------------------------------------------------------------------
 * Role  角色组管理类
 +------------------------------------------------------------------------------
 * @author 熊彦 <cnxiongyan@gmail.com>
 +------------------------------------------------------------------------------
 */
class RoleAction extends GlobalAction 
{
	public function index()
	{
		$Usergroup=M("Role");
		$list=$Usergroup->order('id desc')->findAll();
		if($list!==false){
			$this->assign('list',$list);
		}
		$this->display();
	}

	public function add(){ 
        //读取系统组列表
	    $group   =  M("Role");
        $groupList  =  $group->field('id,name')->findAll();
		$this->assign('groupList',$groupList);
		$this->display();
	}

//保存新添角色
	public function Insert()
	{
		$fname= trim($_POST['name']);
		if(empty($fname)){
			$this->error('请输入角色组名称');
		}
		$Usergroup=M("Role");
        $data =$Usergroup->create();
		 $data['addtime']=time();
          $list=$Usergroup->add($data);
		  if($list!==false){ 
            	$this->assign('jumpUrl',__URL__);
				$this->success('添加成功');
            }else{ 
                $this->error('添加失败'); 
            } 
	}

	public function edit()
	{
        //读取系统组列表
		$group   =  D("Role");
        $groupList       =  $group->field('id,name')->findAll();  
		$this->assign("groupList",$groupList);
		
		$id=intval($_GET["id"]);
		if (!$id) $this->error('未选中任何项');
		if (in_array($id,array(1,3),true))$this->error('内置用户组不可编辑');
		$list=$group->find($id);
		if (!$list) $this->error('未选中任何项');
		$this->assign('vo',$list);
		$map = null;
		if(isset($_GET['node_id'])){
			$map['id'] = array('like',$_GET['node_id'].'%');
 		}
		
		$gnList = M('Node')->where($map)->order('id')->findAll();  //功能列表
		$AccessList = $group->getAccessList($id);  //已有授权
		foreach($gnList as $k=>$v){
			if( in_array($v['id'], $AccessList ,true))  
				$gnList[$k]['isSelect'] = 1;    //选中
			else
				$gnList[$k]['isSelect'] = 0;
		}
		//dump($gnList);
		$this->assign('gnList',$gnList);
		$this->display();
	}

//修改用户组
	public function Update()
	{
		$map['id']=intval($_GET['id']);
		if (!$map['id']) $this->error('未选中任何项');
		if (in_array($id,array(1,3),true))$this->error('内置用户组不可编辑');
		$fname= trim($_POST['name']);
		if(empty($fname)){
			$this->error('请输入角色组名称');
		}
		$Usergroup=M("Role");
        $data =$Usergroup->create();
		$data['updatetime']=time();
        $list=$Usergroup->save($data);
		  if($list!==false){ 
            	$this->assign('jumpUrl',__URL__);
				$this->success('角色组修改成功');
            }else{ 
                $this->error('修改失败'); 
            } 
	}
//修改权限
	public function updateAccess()
	{
		$groupId = $_GET['gid'];
		//$list = array(101,102,103,104);
		$list = $_POST['access'];
		$Group = D("Role");
		$Group->delAccessList($groupId);
		$rs = $Group->setAccessList($groupId, $list);
		if($result===false) {
			$this->error('角色授权失败！');
		}else {
			$this->success('角色授权成功！');
		}
	}

	public function submit(){
		//防止删除默认用户组1,2,3
		$ID=$_REQUEST['id'];
		if (in_array($ID,array(1,2,3))) $this->error('默认用户组不允许删除');
		//删除组之前将属于此组用户更新到"禁止访问"组，防止用户属于非法用户组
		$U=M('User')->execute("UPDATE __TABLE__ SET `role_id`=2 where `role_id`=".$ID);	
		if($U===false) $this->error('操作失败');
		//更新成功后删除指定数据
		$this->_subAction('Role',"delete",$getid=$ID,$pk='id'); 
	}		

//用户列表 参数: role_id
	public function getUserList(){
		$Table = M("User");
		$map['role_id'] =  $_GET['rid'];
        $count = $Table->where($map)->field('id')->count(); 
        $listRows = '20';
        $fields = '*';
        import("ORG.Util.Page");
        $p = new Page($count,$listRows);
        $list = $Table->where($map)->field($fields)->limit($p->firstRow.', '.$p->listRows)->order('id ASC')->findAll();
        $page = $p->show();
        //模板输出
        $this->assign('list',$list);
        $this->assign('page',$page);
		$this->display();
	}
	
	//	单选删除
	public function Del()
	{
		$id=$_GET['id'];
		if(empty($id))
			$this->error('删除项不存在');
		$User=M("Role");
		$result=$User->where('id='.$id)->delete();
		$this->assign('jumpUrl',__URL__);
		if(false!==$result)
		{
			$this->success('数据删除成功!');
		}else{
			$this->error('删除出错!');
		}
	}
}
?>