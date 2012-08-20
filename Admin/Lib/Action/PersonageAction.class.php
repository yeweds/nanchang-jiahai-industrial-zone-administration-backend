<?php
class PersonageAction extends GlobalAction{
/**
 +------------------------------------------------------------------------------
 * Personage  名人堂管理类
 +------------------------------------------------------------------------------
 * @author 熊彦
 +------------------------------------------------------------------------------
 */

//	初始化列表,查询方法
	public function index(){
		if(isset($_POST['title'])){
			$field = trim($_POST['field']);
			$u_name= trim($_POST['title']);
			$where = $field." like '%".$u_name."%'";
		}else{
			$where='';
		}
//		点击标题排序,利用session正序或倒序
		if(isset($_GET['sort']) && isset($_GET['id'])){
			$sort = $_GET['id']." ".$_GET['sort'];
			Session::set('sort',$_GET['sort']);
		}else{
			$sort = "id DESC";
		}
		$Form=D("Personage");
        $count = $Form->where($where)->field('id')->count(); 
        //每页显示的行数
        $listRows = '20';
        //需要查询哪些字段
        $fields = 'id,user_id,realname,email,address,tel,sex,qq,weibo';
        import("ORG.Util.Page");
        $p = new Page($count,$listRows);
        $list = $Form->where($where)->field($fields)->limit($p->firstRow.', '.$p->listRows)->order($sort)->findall();

//登陆IP - begin
	    if($list){
			$this->assign('list',$list);
		}
//登陆IP - end

        $page = $p->show();
        $this->assign('page',$page);
		Cookie::set ( '_currentUrl_', __SELF__ ); //用于返回
		$this->display();
	}

//添加名人
	public function add(){
		$this->display();
	}

//添加名人入库
	public function Insert(){
		$Form=D("Personage");
		$data = $Form->create();
		if(empty($data['realname']))
			$this->error('请填写姓名!');

		if($_FILES['pic']['size']>0){
			$fileinfo = $this->_upload(); //处理上传
			if(count($fileinfo)>0){
				$data['pic'] = $fileinfo[0]['savename'];  
			}
		}
		$data['remark'] = text2html($data['remark']);
		$data['work_experience'] = text2html($data['work_experience']);
		$data['add_time'] = time();
		$rs = $Form->add($data);
		$this->assign('waitSecond',3);
		if($rs)
		{
			$this->assign('jumpUrl',__URL__);
			$this->success('添加名人成功!');
		}
		else
		{
			$this->error('添加名人失败!');
		}
	}

//	单选删除
	public function Del()
	{
		$id=$_GET['id'];
		if(empty($id))
			$this->error('删除项不存在');
		$user=D("Personage");
		$result=$user->where('id='.$id)->delete();
		if(false!==$result)
		{
			$this->assign('jumpUrl',__URL__);
			$this->success('数据删除成功!');
			//$this->redirect('','class');
		}
		else
		{
			$this->error('删除出错!');
		}
	}
	

//批量删除
	public function DelAll() {
		if(empty($_GET['str'])){
			$this->ajaxReturn('','您未选中任何项！',0);
		}
		$Form=D("Personage");
		$del_id = $_GET['str'];
		$where['id']=array('in',$del_id);  //删除条件
		$result=$Form->where($where)->delete();
		if($result){
			$this->ajaxReturn('','所选用户删除成功',1);
		}else{
			$this->ajaxReturn('','批量删除操作有误',0);
		}
	}

//  初始化编辑页面
	public function edit()
	{
		$id=$_GET['id'];
		$Form=D("Personage");
		$map['id'] = $id;
		$user=$Form->where($map)->find();

		$this->assign('user',$user);
		$this->display('edit_user');
	}

//	修改用户资料
	public function update()
	{
		$Form=D("Personage");
		if(isset($_GET['uri'])){
			Session::set('uri',$uri);
		}
//		dump(empty($uri));exit;
        if($data = $Form->create()) {
       	    $id = $_POST['id'];
			if($_FILES['pic_reup']['size']>0){
				$fileinfo = $this->_upload(); //处理上传
				if(count($fileinfo)>0){
					$data['pic'] = $fileinfo[0]['savename'];  
				}
			}
			$data['remark'] = text2html($data['remark']);
			$data['work_experience'] = text2html($data['work_experience']);

			$rs = $Form->where('id='.$id)->save($data);
			//echo $Form->getlastsql();
			$this->assign('waitSecond',3);
			if($rs===false){
				$this->error('名人资料修改失败！');
			}else{

				$this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
				$this->success('名人资料修改成功！');
			}
		}else{
			$this->error($Form->getError());
		}
	}

//---附件上传  xiongyan
	public function _upload(){
		ini_set('memory_limit','10M'); 
		import("ORG.Net.UploadFile");
		$upload = new UploadFile();
		//设置上传文件大小
		$upload->maxSize = 2096000;
        //覆盖之前头像
		$upload->uploadReplace = true;
        //是否多张上传
		$upload->supportMulti = false;
		//设置上传文件类型
		$upload->allowExts = array('jpg','gif','png','jpeg');
		$upload->savePath = './Public/Upload/Mingren/';   //绝对路径
		//设置上传文件规则 
        $upload->saveRule = 'uniqid';
	    //设置需要生成缩略图，仅对图像文件有效
		$upload->thumb =  true;
		$upload->thumbPath = $upload->savePath;
		//设置需要生成缩略图的文件后缀
		//$upload->thumbSuffix =  '';  //直接覆盖
		//$upload->thumbPrefix =  '';  //直接覆盖

		$upload->thumbMaxWidth =  200; 
		$upload->thumbMaxHeight=  227;  //最大高度

		if($upload->upload()){
				$info = $upload->getUploadFileInfo();
				return $info;
		}else{
			 //捕获上传异常
             $this->error($upload->getErrorMsg()); 
		}
	}
}
?>