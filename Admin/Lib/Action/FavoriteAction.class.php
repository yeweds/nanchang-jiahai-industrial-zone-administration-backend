<?php
  //收藏管理后台类
  class FavoriteAction extends GlobalAction
  {
//---收藏首页---
	public function index(){
		$Table = M('Favorite');    
		$listRows="20";
		import("ORG.Util.Page");
		$count=$Table->count();
		$p=new Page($count,$listRows);
		$page=$p->show();

	    $list =$Table->order("id desc")->limit($p->firstRow.','.$p->listRows)->findall();
		if($list!==false){		
			$this->assign('list',$list);
			$this->assign('page',$page);
		}
		Cookie::set ( '_currentUrl_', __SELF__ ); //用于返回
		$this->display();
	}


//---删除信息---
	function del()
	{
		if(!is_numeric($_GET['id'])){
			$this->error('删除项不存在');
		}
			$Article = M("Favorite");
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
		$Form = M("Favorite");
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