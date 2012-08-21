<?php
//单页管理
class PagesAction extends GlobalAction 
{
	public function index()
	{
		$page=D("Pages");
		$count=$page->count();
		import("ORG.Util.Page");
		$listRows=15;
		$p=new page($count,$listRows);
		$list=$page->order('id desc')->limit($p->firstRow.','.$p->listRows)->findAll();
		$page=$p->show();	
		if($list!==false){		
			$this->assign('list',$list);
			$this->assign('page',$page);
		}
		Cookie::set ( '_currentUrl_', __SELF__ ); //用于返回
		$this->display();
	}

	public function add()
	{
		$this->display();
	}

	public function adds()
	{
		$Pages=D("Pages");
        if($data = $Pages->create()) { 
			$data['add_time'] = time();
			$data['content']  = htmlspecialchars($_POST['content'], ENT-QUOTES, UTF-8 ); //入库
            if($Pages->add($data)){ 
            	$this->assign('jumpUrl',__URL__);
				$this->success('单页添加成功');
            }else{ 
                $this->error('单页添加失败');
            } 
        }else{ 
            $this->error($Pages->getError()); 
        } 
	}

	public function edit()
	{
		$id=intval($_GET["id"]);
		if (!$id) $this->error('选中项不存在');
		$Pages=D("Pages");
		$map['id'] = $id;
		$list=$Pages->where($map)->find();
		//$list['content']  = htmlspecialchars_decode($list['content'], ENT-QUOTES ); //出库
		if (!$list) $this->error('选中项不存在');
		$this->assign('vo',$list);
		$this->display();
	}

	public function edits()
	{
		$id=intval($_POST['id']);
		if (!$id) $this->error('选中项不存在');
		$Pages=D("Pages");
		
		if( $data = $Pages->create()) {
			$map['id'] = $id;
			$data['content']  = htmlspecialchars($_POST['content'], ENT-QUOTES, UTF-8 ); //入库
            if($Pages->where($map)->save($data) !== false){ 
				$this->assign('waitSecond', 5);
            	$this->assign('jumpUrl',__URL__);
				$this->success('修改成功');
            }else{ 
                $this->error('修改失败'); 
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
			$Article = M("Pages");
			$map['id'] = $_GET['id'];
			$rs=$Article->where($map)->delete();
			$this->assign( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
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
		$Form = M("Pages");
		$del_id = $_GET['str'];
		$where['id'] = array('in',$del_id);  //删除条件
		$result=$Form->where($where)->delete();
		if($result){
			$this->ajaxReturn('','所选信息删除成功',1);
		}else{
			$this->ajaxReturn('','批量删除操作有误',0);
		}
	}
}
?>