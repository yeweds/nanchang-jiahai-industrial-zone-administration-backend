<?php
/**
 +------------------------------------------------------------------------------
 * Flash  幻灯管理
 +------------------------------------------------------------------------------
 * @author  熊彦
 +------------------------------------------------------------------------------
 */
 class FlashAction extends GlobalAction
 {	
    public function index()
	{
		$flash = M('flash');
		$list = $flash->select();
		$this->assign('list',$list);
		$this->display('index');
	}
	
	public function add()
	{
		$sum = M('flash')->count();
		if($sum>=5)
			$this->error('已达到幻灯图片的最大数，不能超过5张!');
		$this->display('add');
	}
	
	public function doadd()
	{
		//dump($_POST);
		$data['title'] = trim($_POST['title']);
		if($_FILES['pic']['size']>0){
			$fileinfo = A('Attach')->up_hd(); //处理上传幻灯
			if(count($fileinfo)>0){
				$data['pic'] = $fileinfo[0]['savename'];  
			}
		}
		//$data['pic'] = $_POST['pic']; 
		$data['url'] = $_POST['url']; 
		$data['status'] = $_POST['status']; 
		$data['rank'] = $_POST['rank']; 
		$flash = M('flash');
		if($flash->add($data))
		{
			$this->assign('jumpUrl', U('Flash/index') );
			$this->success('操作成功!');
		}
		$this->error('操作失败!');
	}
	
	public function edit()
	{
		$flash = M('flash');
		$list = $flash->where('id='.$_GET['id'])->find();
		$this->assign('list',$list);
		$this->display('edit');
	}
	
	public function doedit()
	{
		$data['title'] = trim($_POST['title']);
		if($_FILES['pic_reup']['size']>0){
			$fileinfo = A('Attach')->up_hd(); //处理上传幻灯
			if(count($fileinfo)>0){
				$data['pic'] = $fileinfo[0]['savename'];  
			}
		}
		//$data['pic'] = $_POST['pic']; 
		$data['url'] = $_POST['url']; 
		$data['status'] = $_POST['status']; 
		$data['rank'] = $_POST['rank']; 
		$data['id'] = $_POST['id']; 
		$flash = M('flash');
		if($flash->save($data) !== false)
		{
			$this->assign('jumpUrl', U('Flash/index') );
			$this->success('操作成功!');
		}
		$this->error('操作失败!');
	}
	
	public function del()
    {
		$flash = M('flash');
		if($flash->where('id='.$_GET['id'])->delete())
		{
			$this->assign('jumpUrl', U('Flash/index') );
			$this->success('操作成功!');
		}
		$this->error('操作失败!');
    }
	
	public function status()
	{
		$status=M('flash');
		if($_GET['status'] == 0)
		{
			$status->where( 'id='.$_GET['id'] )->setField( 'status',1); 
		}
		elseif($_GET['status'] == 1)
		{
			$status->where( 'id='.$_GET['id'] )->setField( 'status',0); 
		}
		else
		{
			$this->error('非法操作!');
		}
		$this->redirect('index');
	}
}

?>