<?php
/**
	explain: 友情链接管理
**/
class LinkAction extends GlobalAction {
	/**
		function: 显示友情链接管理列表
	**/
    public function index() {
    	$linkform = D('Friend_link');
		$field = '*';
		$count= $linkform->count(); 
		import("ORG.Util.Page"); //导入分页类  
		$listRows=20; 
		$p= new Page($count,$listRows); 
		$links = $linkform->field($field)->order('sortrank desc,id asc')->limit($p->firstRow.','.$p->listRows)->findAll(); 
		$page=$p->show();

		if($links) {  
			$this->assign('linkList',$links);
		} 	
    	$this->assign('page',$page);
		Cookie::set ( '_currentUrl_', __SELF__ ); //用于返回
		$this->display();
    }
	
	function add(){
		$this->display();
	}

	/**
		function: 后台保存新增友情链接
		parameter:
	**/
	function insert(){
		$linkform = D('Friend_link');
		$link = $linkform->create();
		if($link==false){
			$this->error($linkform->getError());
		}
			$link['addtime'] = date('Y-m-d H:i:s');

			if($link['urllogo'] == 'http://') {
				$link['urllogo'] = '';
			}
			
		if($linkform->add($link)) {
					$this->success('友情链接添加成功');
		}else {
			$this->error('添加友情链接失败！');
		}
	}
	/**
		function: 显示友情链接编辑
		parameter:
	**/	
	public function edit() {
		$id = $_GET['id'];
		$Form = D("Friend_link");
		$list = $Form->find($id);
		$this->assign('listone',$list);

		$this->display();
	}
	/**
		function: 友情链更新处理
		parameter:
	**/	
	public function update() {
		$Form = D("Friend_link");
		if(empty($_POST['webname'])) {
			$this->error("网站名不能为空！");
			return;
		}
		if(empty($_POST['url'])) {
			$this->error("网站地址不能为空！");
			return;
		}

		$vo = $Form->create();
		if($vo==false){
			$this->error($Form->getError());
		}
		$id = (int)$_POST['id'];
		$where['id'] = $id;
		$vo['passed']   = (int)$_POST['passed'];
		if($Form->where($where)->save($vo)) {	
			$this->assign('jumpUrl',__APP__.'/Link');
			$this->success('修改成功!');		
		} else {
			$this->error('修改友情链接失败！');
			return;
		}
	}

	/**
		function: 删除指定友情链接
		parameter:
	**/
	function del() {
		
		$Form = D('Friend_link');
		$map['id'] = intval($_GET['id']);
		$rs = $Form->where($map)->delete(); // 删除查找到的记录
		$this->assign('jumpUrl',__URL__);
		if($rs){
			$this->success('删除成功');
		} else {
			$this->error('删除项不存在');
		}
	}

//---全选删除---
	function delAll()
	{
		if(empty($_GET['str'])){
			$this->ajaxReturn('','您未选中任何项！',0);
		}
		$Form = M("Friend_link");
		$del_id = $_GET['str'];
		$where['id'] = array('in',$del_id);  //删除条件
		$result=$Form->where($where)->delete();
		if($result){
			$this->ajaxReturn('','所选信息删除成功',1);
		}else{
			$this->ajaxReturn('','批量删除操作有误',0);
		}
	}

	//审核通过一条链接
	public function Passed()
	{
		$ID = (empty($_GET['ID']) ? '' : intval($_GET['ID']));
		$Form=D("Friend_link");
		$Form->setField('passed',1,'id='.$ID,false); //设置单个字段的值
		$this->success('友情链接审核成功!');
	}

	//取消审核方法
	public function CancelPassed() {
		$ID = (empty($_GET['ID']) ? '' : intval($_GET['ID']));
		$Form=D("Friend_link");
		$Form->setField('passed',0,'id='.$ID,false); //设置单个字段的值
		$this->success('您已取消审核该友情链接!');
	}

	//审核通过选中的链接
	public function passAll()
	{
		if(empty($_GET['str'])){
			$this->ajaxReturn('','您未选中任何项！',0);
		}
		$Form = M("Friend_link");
		$del_id = $_GET['str'];
		$map['id'] = array('in',$del_id);  //审核条件
		$data['passed'] = 1; 
		$result=$Form->where($map)->save($data);
		if($result){
			$this->ajaxReturn('','所选链接审核成功!',1);
		}else{
			$this->ajaxReturn('','所选链接未通过审核',0);
		}
	}


}
?>