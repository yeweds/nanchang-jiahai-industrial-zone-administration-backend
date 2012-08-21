<?php
/**
 +------------------------------------------------------------------------------
 * Attach  附件管理类
 +------------------------------------------------------------------------------
 * @author 熊彦 <cnxiongyan@gmail.com>
 +------------------------------------------------------------------------------
 */
class AttachAction extends GlobalAction{
//---附件列表---
	public function index() 
	{ 
		$Brand = M("Attach"); 
		$fields = '*';  // 如果查视图,须写明所查列
		$where = array();
		if(isset($_GET['model_name'])){
			$where['model_name'] = $_GET['model_name'];
		}
		if(isset($_GET['upload_time'])){
			$currTime = strtotime($_GET['upload_time']);
			$oneDayRange = ($currTime-1).','.($currTime+3600*24-1); //时间范围
			$where['upload_time']  =  array('between', $oneDayRange);
		}
		if( isset($_GET['s_key']) && !empty($_GET['s_type']) ){
			$field = trim($_GET['s_type']);
			$key   = trim($_GET['s_key']);
			if($field == 'savename'){
				$where[$field] = array('like', "%".$key."%");
			}else{
				$where[$field] = array('eq', $key);
			}
		}
		$count= $Brand->where($where)->count();
		import("ORG.Util.Page"); //导入分页类 
		$listRows = 20;
		$p = new Page($count,$listRows); 
		$list=$Brand->where($where)->field($fields)->limit($p->firstRow.','.$p->listRows)
			  ->order('id desc')->findAll(); 
		$page=$p->show();
		if($list){
			$this->assign('list',$list); 
		}
		$this->assign('page',$page); 
		Cookie::set ( '_currentUrl_', __SELF__ ); //用于返回
		$this->display();
	}

//---打开增加附件页面---
	public	function add() 
	{
		$this->display();
	}

//---添加商家附件入库  -- 黄妃
	public function insert(){
			 $array = explode('|',$_POST['attach_arr']);
			 $fileinfo = $this->_upload($width=270, $height=220);
			 //print_r($fileinfo);
			 if(count($array)!=3 || count($fileinfo) <= 0 ){
			     exit;
			 }
			 //图片类型名称$array[2];
			 $data['class_id']  = $array[0];//类别ID
			 $data['curr_path'] = $array[1];//父路径
			 $data['info_id'] = trim($_POST['info_id']);
			 $data['user_id'] = intval($this->getUid()); //用户id
			 $data['remark'] = trim($_POST['remark']) ;
			 $uptime = empty($_POST['upload_time']) ? time() : strtotime($_POST['upload_time']);
			 $data['model_name'] = trim($_POST['model_name']);  //数据模型名(表名)
			 $index = 0;
			 $Table = D('Attach');
			 $Table->startTrans();   //启动事务
			 foreach($fileinfo as $vo){
				 $data['name'] = $vo['name'] ;//原名
				 $data['type'] = $vo['type'] ;
				 $data['size'] = $vo['size'] ;
				 $data['extension'] = $vo['extension'] ;
				 $data['savepath']  = $vo['savepath'] ;
				 $data['savename']  = $vo['savename'] ;
				 $data['upload_time'] = $uptime;
				 //dump($data);
				 if ($Table->add($data)){ 
					$index++;
				 }
		     }
			 if(count($fileinfo)==$index){
			     $this->success($array[2].'添加成功！');
			 }else{ 
				 $Table->rollback();   //回滚事务
				 $this->error($array[2].'添加失败！');
			 }
	}

//---打开编辑附件页面---
	public	function edit() 
	{
		$id = intval($_GET["id"]);
		$map['id'] = $id;
		$rs = M("Attach")->where($map)->find();
		unset($map);
		$map['id'] = $rs['info_id'];
		if($rs && $rs['model_name']=="pics" ){
			$vo = M("Pic")->field('title')->where($map)->find();
		}else if($rs && $rs['model_name']=="shop" ){
			$vo = M("Shop")->field('name as title')->where($map)->find();
		}else{
			$vo = M("Story")->field('title')->where($map)->find();
		}

		$rs['title'] = $vo['title'];
		//dump($rs);
		$this->assign('vo',$rs); 
		$this->display();
	}

//---更新附件---
	public function update()
	{
		$id = $_POST["id"];
		$map['id'] = $id;
		$Table = M("Attach");
		$data = $Table->create();
		$data['upload_time'] = strtotime($data['upload_time']);
		$result = $Table->where($map)->save($data);  //更新id=$id的记录
		$this->assign('waitSecond',3);
        if(false!==$result){
			$this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
			$this->success('附件信息更新成功!');
		}else{
			$this->error('附件信息更新失败!');
		}
	}
	
//删除信息
	public function del()
	{
		$id=$_GET['id'];
		if(empty($id))
			$this->error('删除项不存在');
		$map['id'] = $id;
		$result= $this->del_attach($id); //先删图片或其它附件
		if(false!==$result){
			M("Attach")->where($map)->delete();
			$this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
			$this->success('附件删除成功!');
		}else{
			$this->error('附件删除有误!');
		}
	}
	
//全选删除
	public function delAll()
	{
		if(empty($_GET['str'])){
			$this->ajaxReturn('','您未选中任何项！',0);
		}
		$Form = D("Attach");
		$del_id = $_GET['str'];
		$del_arr= implode(',', $del_id);
		if(is_array($del_arr)){
			foreach($del_arr as $v){
				$this->del_attach($v); //先循环删图片或其它附件
			}
		}

		$where['id']=array('in',$del_id);  //删除条件
		$result=$Form->where($where)->delete();
		if($result){
			$this->ajaxReturn('','所选附件删除成功',1);
		}else{
			$this->ajaxReturn('','所选附件删除失败',0);
		}
	}

//删除文件	
	private function del_attach($id){
		if($rs = M('Attach')->where('id='.$id)->find()){
			$file = $rs['savepath'].$rs['savename'];
			if(file_exists($file)){
				if(unlink($file)){
					    @unlink($rs['savepath'].'thumb_'.$rs['savename']);  //删除缩略图
						return true;
				}
			}	
		}
		return false;
	}

//根据一条商家(故事)id删除文件	
	public function del_attach_byid($id, $model_name="shop") {
		$aT = M ( 'Attach' );
		$map['info_id']    = $id;
		$map['model_name'] = $model_name;
		if ($rs = $aT->where($map)->findAll()) {
			//先删除数据表里的数据
			$r = $aT->where($map)->delete();
			//一条一条删除	
			foreach ( $rs as $v ) {
				$file = $v['savepath'] . $v['savename'];
				$thumb_file = $v ['savepath'] . 'thumb_' . $v ['savename'];
				//$gray_file = $v ['savepath'] . 'gray_' . $v ['savename'];
				if (file_exists ( $file )) {
					@unlink ( $file );
					@unlink ( $thumb_file );
					//unlink ( $gray_file );
				}
			}
		}
	}

//-------------------------------------------------

//幻灯上传方法-----
	public function up_hd()
	{
		ini_set('memory_limit','20M'); 
		import("ORG.Net.UploadFile");
		$upload = new UploadFile();
		//设置上传文件大小
		$upload->maxSize = 2096000;
		$upload->uploadReplace = true;
        //是否多张上传
		$upload->supportMulti = false;
		//设置上传文件类型
		$upload->allowExts = array('jpg','gif','png','jpeg');
		$upload->savePath = './Public/Upload/hd/';
		//设置上传文件规则 
        $upload->saveRule = 'uniqid';
	    //设置需要生成缩略图，仅对图像文件有效
		$upload->thumb =  true;
		$upload->thumbPath = $upload->savePath;
		//设置需要生成缩略图的文件后缀
		$upload->thumbSuffix =  '';  //直接覆盖
		$upload->thumbMaxWidth =  770; 
		$upload->thumbMaxHeight=  310;  //最大高度 770 × 310

		if($upload->upload()){
				$info = $upload->getUploadFileInfo();
				return $info;
		}else{
			 //捕获上传异常
             $this->error($upload->getErrorMsg()); 
		}
	}
//-------------------------------------------------

//---附件上传  xiongyan
	public function _upload($width=270, $height=220){
		ini_set('memory_limit','50M'); 
		import("ORG.Net.UploadFile");
		$upload = new UploadFile();
		//设置上传文件大小
		$upload->maxSize = 4096000;
        //覆盖之前头像
		$upload->uploadReplace = true;
        //是否多张上传
		$upload->supportMulti = false;
		//设置上传文件类型
		$upload->allowExts = array('jpg','gif','png','jpeg');
		//$upload->savePath = './Public/Upload/';
		$upload->savePath = $this->getUpdir("./Public/Upload/");
		//$upload->autoSub = true;    
		//$upload->subType = 'date';  //子目录创建方式，默认为hash，可以设置为hash或者date
		//$upload->dateFormat = 'Ym'; //子目录方式为date的时候指定日期格式
		//设置上传文件规则 
        $upload->saveRule = 'uniqid';
	    //设置需要生成缩略图，仅对图像文件有效
		$upload->thumb =  true;
		$upload->thumbPath = $upload->savePath;
		//设置需要生成缩略图的文件后缀
		$upload->thumbSuffix =  '';  //直接覆盖
		$upload->thumbMaxWidth =  $width; 
		$upload->thumbMaxHeight=  $height;  //最大高度

		if($upload->upload()){
				$info = $upload->getUploadFileInfo();
				return $info;
		}else{
			 //捕获上传异常
             $this->error($upload->getErrorMsg()); 
		}
	}

//建立上传文件夹
	public function create($dir)
	{
			if (!is_dir($dir))
			{
				$temp = explode('/',$dir);
				$cur_dir = '';
				for($i=0;$i<count($temp);$i++)
				{
					$cur_dir .= $temp[$i].'/';
					if (!is_dir($cur_dir))
					{
						@mkdir($cur_dir,0777);
						@fopen("$cur_dir/index.htm","a");
					}
				}
			}
	}
//获得上传路径
	public function getUpdir($updir="./Public/Upload/" , $dirtype="2"){
			//$updir //上传目录  $dirtype //目录保存方式1：年/月/日;2:年/月;默认:年
			switch($dirtype){
				case '1':
					$m_dir=date('Ym')."/".date('d')."/";
					break;
				case '2':
					$m_dir=date('Ym')."/";
					break;
				default:
					$m_dir=date('Y')."/";
					break;
			}
			//设置上传的路径
			$upload_path =$updir.$m_dir;
			//建立文件夹
			$this->create($upload_path);
			return $upload_path;
	}
}

?>