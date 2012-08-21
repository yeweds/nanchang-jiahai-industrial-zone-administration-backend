<?php
  //公告管理
  class NoticeAction extends GlobalAction
  {
	public function index(){
		$Article=M('Notice');    
		$listRows="20";
		import("ORG.Util.Page");
		$count=$Article->count();
		$p=new Page($count,$listRows);
		$page=$p->show();

	    $list =$Article->order("id desc")->limit($p->firstRow.','.$p->listRows)->findall();
		if($list!==false){		
			$this->assign('list',$list);
			$this->assign('page',$page);
		}
		Cookie::set ( '_currentUrl_', __SELF__ ); //用于返回
		$this->display();
	}
//添加表单
	public function add(){
		$this->display('add');
	}
//添加入库
	public function Insert(){
		 $newArticle =  D('Notice');
		 $data = $newArticle->create();
		 if($data){
			 $data['title']      = trim($_POST['title']);
			 $data['content']    = $_POST['content'];
			 $data['add_time']   = time();
		 }else{
			$this->error($newArticle->getError());
		 }
			if($newArticle->add($data))
			{
				 $this->assign("jumpUrl",__URL__."/index");
				 $this->success("公告添加成功!");
			}
	}

//编辑表单
	public function edit()
	{
		if(!is_numeric($_GET['id'])) {
			 $this->error('编辑项不存在');
		}
		$id = intval($_GET["id"]);
		$map['id'] = $id;
		$editVo    = M('Notice')->where($map)->find();
		$this->assign("vo",$editVo);
		$this->display();
	}

//用于保存编辑后的信息
	public function save()
	{
		$id=intval($_POST['id']);
		if (!$id) $this->error('编辑项不存在');
		$Pages=D("Notice");
		if( $Pages->create()) { 
            if($Pages->save()){ 
            	$this->assign('jumpUrl',__URL__);
				$this->success("公告编辑成功!");
            }else{ 
                $this->error("公告编辑失败!"); 
            } 
        }else{ 
            $this->error($Pages->getError()); 
        } 
	}
//---删除信息---
	function del()
	{
		if(!is_numeric($_GET['id'])){
			$this->error('删除项不存在');
		}
			$Article = M("Notice");
			$map['id'] = $_GET['id'];
			$rs=$Article->where($map)->delete();
			$this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
			if($rs)
			{
				$this->success('删除成功!');
			}else
				$this->error('删除失败!');

	}
//---全选删除---
	function delAll()
	{
		if(empty($_GET['str'])){
			$this->ajaxReturn('','您未选中任何项！',0);
		}
		$Form = M("Notice");
		$del_id = $_GET['str'];
		$where['id']=array('in',$del_id);  //删除条件
		$result=$Form->where($where)->delete();
		if($result){
			$this->ajaxReturn('','所选信息删除成功',1);
		}else{
			$this->ajaxReturn('','批量删除操作有误',0);
		}
	}
	
  }
?>