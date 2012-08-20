<?php
  //点评管理
  class ReviewsAction extends GlobalAction
  {
	public function index(){
		$Article=M('Reviews');    
		$listRows="20";
		import("ORG.Util.Page");
		$count=$Article->count();
		$p=new Page($count,$listRows);
		$page=$p->show();

	    $list =$Article->order("id desc")->limit($p->firstRow.','.$p->listRows)->findall();
		if($list){
			foreach($list as $k=>$v){
				$list[$k]['content'] = trim(strip_tags($v['content']));
			}
			$this->assign('list',$list);
			$this->assign('page',$page);
		}
		Cookie::set( '_currentUrl_', __SELF__ ); //用于返回
		$this->display();
	}

//编辑表单
	public function edit()
	{
		if(!is_numeric($_GET['id'])) {
			 $this->error('编辑项不存在');
		}
		$id = intval($_GET["id"]);
		$map['id'] = $id;
		$editVo    = M('Reviews')->where($map)->find();
		$this->assign("vo",$editVo);
		$this->display();
	}

//用于保存编辑后的信息
	public function save()
	{
		$id=intval($_POST['id']);
		if (!$id) $this->error('编辑项不存在');
		$Pages=D("Reviews");
		if( $Pages->create()) { 
            if($Pages->save()!==false){ 
            	$this->assign('jumpUrl', Cookie::get('_currentUrl_') );
				$this->success("点评编辑成功!");
            }else{ 
                $this->error("点评编辑失败!"); 
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
		$map['id'] = $_GET['id'];
		$rs = M("Reviews")->where($map)->delete();
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
		$Form = M("Reviews");
		$del_id = $_GET['str'];
		$where['id']=array('in',$del_id);  //删除条件
		$result=$Form->where($where)->delete();
		if($result){
			$this->ajaxReturn('','所选信息删除成功',1);
		}else{
			$this->ajaxReturn('','批量删除操作有误',0);
		}
	}

//隐藏某条明细
	public function hide_mx(){
		if(!is_numeric($_GET['id'])) {
			 $this->error('参数不存在');
		}
		$Table = D("Reviews");
		$map['id'] = intval($_GET["id"]);
		$rs = $Table->where($map)->find();
		if($rs['is_editor']==1){
			$this->error("编辑点评不能隐藏!"); 
			return;
		}
		$data['is_hide'] = 1;
        if($Table->where($map)->save($data) !== false){ 
            	$this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
				$this->success("点评隐藏成功!");
        }else{ 
                $this->error("点评隐藏失败!"); 
        } 

	}
//显示某条明细
	public function show_mx(){
		if(!is_numeric($_GET['id'])) {
			 $this->error('参数不存在');
		}
		$Table = D("Reviews");
		$map['id'] = intval($_GET["id"]);
		$data['is_hide'] = 0;
        if($Table->where($map)->save($data) !== false){ 
            	$this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
				$this->success("点评显示成功!");
        }else{ 
                $this->error("点评显示失败!"); 
        } 

	}
	
  }
?>