<?php
//留言簿后台
class GuestbookAction extends GlobalAction {
	
    public function index() {
		$idea = D("Guestbook");
		// 查询字段
		$fields = '*';
		// 查询条件
		$where = '';
// 构造查询条件
if (isset($_POST['key']))
	{ 
	 $key=trim($_POST['key']);
	}
	else
	{ $key=''; }
if (isset($_POST['condition']))
	{ 
	 $condition=trim($_POST['condition']);
	}
	else
	{ $condition=''; }
	switch ($condition){
		case "uname":
		  $where['uname']= array('like','%'.$key.'%');
		  break;
		case "title":
		  $where['title']= array('like','%'.$key.'%');
		  break;
		case "text":
		  $where['content']= array('like','%'.$key.'%');
		  break;
		case "reply":
		  $where['reply']= array('like','%'.$key.'%');
		  break;
	default:
	    $where['content']= array('like','%'.$key.'%');
	  }
// 构造查询条件结束
		// 所有数据量
		$count = $idea->where($where)->field('id')->count(); 
		//每页显示的行数
        $listRows = '10';
        import("ORG.Util.Page");
        $page = new Page($count, $listRows);
		$list = $idea->where($where)->field($fields)->limit($page->firstRow.', '.$page->listRows)->order('id desc')->findall();
		$p = $page->show(); 
		// 模板输出
		if($list) $this->assign("info",$list);
        $this->assign('page',$p);
        $this->assign("title","留言簿");
		$this->display();
    }
 
//删除留言
	public function del() {
		if(empty($_GET['id'])){
			$this->error('删除项不存在');
		}
		$id = (int)$_GET['id'];
		$Form=D("Guestbook");
		$where['id']=$id;
		$result=$Form->where($where)->delete();
		$this->assign('jumpUrl',__URL__.'/index');
		if(false!==$result){
			$this->success('留言删除成功!');
		}else{
			$this->error('留言删除失败!');
		}

	}

//批量删除留言
	public function DelAll() {
		if(empty($_GET['str'])){
			$this->ajaxReturn('','您未选中任何项！',0);
		}
		$Form=D("Guestbook");
		$del_id = $_GET['str'];
		$where['id']=array('in',$del_id);  //删除条件
		$result=$Form->where($where)->delete();
		if($result){
			$this->ajaxReturn('','所选留言删除成功',1);
		}else{
			$this->ajaxReturn('','批量删除操作有误',0);
		}
	}

	//审核通过一条留言
	public function Passed()
	{
		$ID = (empty($_GET['ID']) ? '' : intval($_GET['ID']));
		$Form=D("Guestbook");
		$Form->setField('passed',1,'id='.$ID,false); //设置单个字段的值
		$this->success('留言审核成功!');
	}
	//取消审核方法
	public function CancelPassed() {
		$ID = (empty($_GET['ID']) ? '' : intval($_GET['ID']));
		$Form=D("Guestbook");
		$Form->setField('passed',0,'id='.$ID,false); //设置单个字段的值
		$this->success('您已取消审核该留言!');
	}
//全选审核
	function PassAll()
	{
		if(empty($_GET['str'])){
			$this->ajaxReturn('','您未选中任何项！',0);
		}
		$Form=D("Guestbook");
		$id_str = $_GET['str'];
		$where['id']=array('in',$id_str);  //审核条件
		$data['passed'] = 1;
		$result=$Form->where($where)->save($data);
		if($result){
			$this->ajaxReturn('','所选留言审核成功!',1);
		}else{
			$this->ajaxReturn('','已经审核或批量审核操作有误',0);
		}
	}

	public function Reply()
	{
		$ID=trim($_GET['ID']);
		$msg=trim($_GET['msg']);
		$this->assign("ID",$ID);
		$this->assign("msg",$msg);
		$this->display();
	}

	public function save_reply()
	{
		$ID = $_POST['ID'];
		$Form=D("Guestbook");
		$data['reply']    = trim($_POST['reply']);
		$data['replytime']= time();
		$where['id'] = $ID;
		$result=$Form->where($where)->save($data);  //更新id=$id的记录
		$this->success('您已成功回复该留言!');
	}
}
?>