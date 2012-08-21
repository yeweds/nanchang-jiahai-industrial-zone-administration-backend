<?php
/**
 +------------------------------------------------------------------------------
 * Shop  商家管理类
 +------------------------------------------------------------------------------
 * 熊彦
 */
class ShopAction extends GlobalAction {
	
	private $shop_pid = 3;  //商家大类ID
	private $map = array();

	//商家列表
	public function index() {
		$Class = M ('Class');
		$map['pid'] = $this->shop_pid;
		$class_list = $Class->where ( $map )->select();
		if (! $class_list) {
			$this->error ( '分类不存在，请先添加分类!' );
		}
		unset($map);
		$this->assign ( 'class', $class_list );

		$newsT = M ( "Shop" );
		$classId = intval( $_GET['class_id'] );
		$map = array();
		if ($classId) {
			$map['class_id'] = $classId;
		}

		if( isset($_GET['s_key']) && !empty($_GET['s_type']) ){
			$field = trim($_GET['s_type']);
			$key   = trim($_GET['s_key']);
			if($field == 'name' || $field=='sub_title'){
				$map[$field] = array('like', "%".$key."%");
			}else{
				$map[$field] = array('eq', $key);
			}
		}

		import ( "ORG.Util.Page" ); //导入分页类 
		$count = $newsT->where($map)->count();
		//echo $newsT->getlastsql();
		$listRows = 15;
		$p = new Page ( $count, $listRows );
		$page = $p->show();
		$list = $newsT->field ( 'id,name,class_id,star,sortrank,add_time' )->where($map)->order ( 'id desc' )
				->limit( $p->firstRow . ',' . $p->listRows )->findAll ();
		
		unset($map);
		if ($list) {
			foreach ( $list as $k => $vo ) { //查询两张表中的内容 
				$class_id = $vo ['class_id'];
				$map ['id'] = $class_id;
				$cls = $Class->where ( $map )->find ();
				$list [$k] ['classname'] = $cls['name'];
			}
		}
		//dump($list);
		$this->assign ( 'list', $list );
		$this->assign ( 'page', $page );

		Cookie::set ( '_currentUrl_', __SELF__ ); //用于返回
		$this->display ();
	}

	//添加商家
	public function add() {
		$class =M( 'Class' );
		$map['pid'] = $this->shop_pid;
		$classT = $class->where ( $map )->select ();
		if (! $classT) {
			$this->error ( '分类不存在，请先添加分类!' );
		}
		$this->assign ( 'classT', $classT );
		$uid = intval ( Cookie::get ( C ( 'cfg_auth_key' ) ) );
		//所有商家分类
		$this->assign ( 'class_list', $this->getClass () );
		$this->assign ( "user_id", $uid );
		$this->display ( 'add' );
	}

	//添加商家 公司 和上传多张图片 
	public function Insert() {

		$Table = D ('Shop');
		$data = $Table->create();
		if(!$data){
			$this->error ( $Table->getError () );
		}

		//先添加文档再上传图片
		$title = trim ( $_POST['title'] );
		$data ['name'] = $title;
		$content = trim ( $_POST['content'] );
		$data['content'] = htmlspecialchars($content, ENT-QUOTES, UTF-8 ); //入库
		$data['remark'] = trim($_POST['remark']) != "" ? trim($_POST['remark']):$title;

		$class_id = $_POST ['class_id'];
		
		$sortrank = trim ( $_POST ['sortrank'] );
		if (empty ( $title ) || empty ( $content )) {
			$this->error ( "商家名称和简介不能为空!" );
		}
		if ($class_id == 0) {
			$this->error ( "请选择所属栏目!" );
		}
		if (! is_numeric ( $sortrank )) {
			$this->error ( '权重值必须为整数!' );
		}

		if($_FILES['logo_url']['size']>0){
			$fileinfo = $this->_upload(); //处理上传
			if(count($fileinfo)>0){
				$data['logo_url'] = $fileinfo[0]['savename'];  
			}
		}
		$data ['class_id'] = $class_id;
		$data ['sortrank'] = $sortrank;
		$data ['add_time'] = time (); //当前时间

		$map ['title'] = $title;
		$rs = $Table->where ( $map )->find (); //到数据库中查询判断该商家是否已经存在

		$this->assign ( 'waitSecond', 3 );
		if ($rs) {
			$this->error ( "该商家已存在！" );
		}
		$classT = M('Class');
		$Class = $classT->where('id='.$class_id)->find();
		$data['curr_path']= $Class['curr_path'];
		$data['head_py']  = getPinyin($title, $ishead=1); //获取拼音
	    $data['pinyin']   = getPinyin($title, $ishead=0); //获取全拼

		$flags = $_POST['wj_range'];
		if(count($flags>0)){
			foreach($flags as $f){
				$flag .= $f.',';
			}
			$data['wj_range'] = rtrim($flag,',');
		}

		if (!$lastInsId = $Table->add ( $data )) {
			$this->error ( "商家添加失败！" );
		}else{
			$classT->setInc ( 'count', 'id=' . $class_id, 1 );  //增加统计
			$this->success ( "商家添加成功!" );
		}
	}

	//编辑商家
	public function edit() {
		if (! is_numeric ( $_GET ['id'] )) {
			$this->error ( '编辑项不存在!' );
		}
		$id = intval ( $_GET ['id'] );
		$table = D ( 'Shop' );
		$map ['id'] = $id;
		$shop = $table->where($map)->field ('*')->find(); //查询出商家  还要查询出附加表中的图片 
		if (! $shop) {
			$this->error ( '查询有误!' );
		}
		unset($map);
		//查询附加表   里的图片

		$clas = D ( 'Class' );
		$map['pid'] = $this->shop_pid;
		$class = $clas->where ($map)->select ();
		if (! $class) {
			$this->error ( '暂无分类，请先添加!' );
		}

		$class_id = $_GET ['class_id'];

		$where['info_id'] = $id;
		$where['model_name'] = "shop";
		$pic = M( 'Attach' )->where( $where )->count();
		$shop['pic_sum'] = $pic;
		$this->assign ( 'class_id', $class_id );
		$this->assign ( 'shop', $shop );
		$this->assign ( 'class', $class );
		//自定义属性---
		$flag = $shop['wj_range'] ;
		if(strstr($flag,'1')){
			$this->assign("a",'1');
		}
        if(strstr($flag,'2')){
			$this->assign("b",'1');
		}
		if(strstr($flag,'3')){
			$this->assign("c",'1');
		}
		if(strstr($flag,'4')){
			$this->assign("d",'1');
		}
		if(strstr($flag,'5')){
			$this->assign("e",'1');
		}
		//自定义属性--- end
		$this->display ( 'edit' );
	}

    //保存编辑后的商家
	public function save() {
		$this->assign ( 'waitSecond', 3 );
		$this->assign ( "jumpUrl", Cookie::get('_currentUrl_') );

		//先修改文档再上传图片
		$id = $_POST ['id'];
		$title = trim ( $_POST ['title'] );
		$content = trim ( $_POST ['content'] );
		$sortrank= trim($_POST['sortrank']);
		$class_id = $_POST ['class_id'];
		if (empty ( $title ) || empty ( $content )) {
			$this->error ( "标题和内容不能为空!" );
		}
		if (!is_numeric($sortrank)){
			$this->error ("权重填写有误!");
		}
		$Table = D ( 'Shop' );
		$data = $Table->create();
		if ($data) {
			$data['name']   = $title;
			$data['content']= htmlspecialchars($content, ENT-QUOTES, UTF-8 ); //入库  //转换换行、空格
			$data['remark'] = trim($_POST['remark']) != "" ? trim($_POST['remark']):$title;
			$data ['class_id'] = $class_id;
			if($_FILES['pic_reup']['size']>0){
				$fileinfo = $this->_upload(); //处理上传
				if(count($fileinfo)>0){
					$data['logo_url'] = $fileinfo[0]['savename'];  
				}
			}
		} else {
			$this->error ( $Table->getError() );
		}

		$classT = M('Class');
		$Class = $classT->where('id='.$class_id)->find();
		$data['curr_path']= $Class['curr_path'];

		$data['head_py']  = getPinyin($title, $ishead=1); //获取拼音
	    $data['pinyin']   = getPinyin($title, $ishead=0); //获取全拼

		$flags = $_POST['wj_range'];
		if(count($flags>0)){
			foreach($flags as $f){
				$flag .= $f.',';
			}
			$data['wj_range'] = rtrim($flag,',');
		}

		unset ($map);
		$map['id']= $id;
		$rs = $Table->where($map)->save($data);
		if($rs !== false){
			$this->success ( "修改成功!" );
		}else{
			$this->error ( '商家信息修改失败' );
		}
		
		//$this->image_togray ( $vo ['savepath'], $vo ['savename'], $vo ['extension'] ); //生成灰度图像
		//dump($data);
		
	}

	//---删除商家---
	function del() {
		if (! is_numeric ( $_GET ['id'] )) {
			$this->error ( '删除项不存在' );
		}
		$docT = M ( "Shop" );
		$id = intval ( $_GET ['id'] );
		$map ['id'] = $id;
		$rs = $docT->where ($map)->delete ();
		
		$this->assign("jumpUrl", Cookie::get('_currentUrl_') );
		if ($rs) {
			//给栏目文档数量减 1 个
			M("Class")->setDec('count', 'id=' . $r ['class_id'] );
			//删除图片
			A("Attach")->del_attach_byid($id, $model_name="shop");
			$this->success ( '删除成功!' );
		} else {
			$this->error ( '删除失败!' );
		}
	}

	//---删除多条商家---
	function delall() {
		if (empty ( $_GET ['str'] )) {
			$this->ajaxReturn ( '', '您未选中任何项！', 0 );
		}
		$list = explode ( ',', $_GET ['str'] );
		$docT = D ( "Shop" );
		$classT = M ( "Class" );
		foreach ( $list as $id ) {// 循环删除
			$map ['id'] = $id;
			$r = $docT->where ( $map )->find ();
			$rs = $docT->where ( $map )->delete ();
			$this->assign ( 'jumpUrl', $_SERVER ["HTTP_REFERER"] );
			if ($rs) {
				//给栏目文档数量减 1 个
				$classT->setDec ( 'count', 'id=' . $r['class_id'] );
				//删除图片
				$aT = M ( 'Attach' );
				$map ['info_id'] = $id;
				$map['model_name'] = "shop";
				if ($rs = $aT->where ($map)->findAll ()) {
					//先删除数据表里的数据
					$r = $aT->where ($map)->delete();
					//一条一条删除	
					foreach ( $rs as $v ) {
						$file = $v ['savepath'] . $v ['savename'];
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
		}
		$this->ajaxReturn ( '', $arr . '删除成功', 1 );
	}


	
	//返回路径
	function join_url($path, $name, $type) {
		if ($type) {
			$url = 'http://' . $_SERVER ['HTTP_HOST'] . substr ( $path, 1 ) . 'thumb_' . $name;
		} else {
			$url = 'http://' . $_SERVER ['HTTP_HOST'] . substr ( $path, 1 ) . $name;
		}
		return $url;
	}
	
/*
	public function sheng() {
		//查出所有图像
		$picT = M ( 'Attach' );
		$pic = $picT->findAll ();
		//dump($pic);
		//替换图片的路径
		foreach ( $pic as $vo ) {
			//生成灰度图像
			$this->image_togray ( $vo ['savepath'], $vo ['savename'], $vo ['extension'] );
		}
		$this->display ();
	
	}
	
	public function image_togray($dir, $name, $type) {
		$thumb_name = 'thumb_' . $name;
		$gray_name = $dir . 'gray_' . $name;
		$all_name = $dir . $thumb_name;
		//$all_name=$this->join_url($dir,$name,$type);
		if ($type == 'png') {
			$im = imagecreatefrompng ( $all_name );
		} else if ($type == 'jpg') {
			$im = imagecreatefromjpeg ( $all_name );
		} else if ($type == 'gif') {
			$im = imagecreatefromgif ( $all_name );
		} else if ($type == 'bmp') {
			$im = imagecreatefromwbmp ( $all_name );
		}
		
		$ch = imagefilter ( $im, IMG_FILTER_GRAYSCALE );
		
		if ($type == 'png') {
			$im = imagepng ( $im, $gray_name );
		} else if ($type == 'jpg') {
			$im = imagejpeg ( $im, $gray_name );
		} else if ($type == 'gif') {
			$im = imagegif ( $im, $gray_name );
		} else if ($type == 'bmp') {
			$im = imagewbmp ( $im, $gray_name );
		}
		//imagepng($im, $gray_name);
		imagedestroy ( $im );
	
	}
	
	//设置首图
	public function set_pic() {
		$id = $_GET ['id'];
		$news_id = $_GET ['news_id'];
		
		//先将原先首图设置为否，再将现在的设置为真
		$aT = M ( 'Attach' );
		//废除首图
		$where ['news_id'] = $news_id;
		$where ['is_index'] = 1;
		$data ['is_index'] = 0;
		$r = $aT->where ( $where )->save ( $data );
		if ($r) {
			//重新设置首图
			$map ['id'] = $id;
			$data ['is_index'] = 1;
			$rs = $aT->where ( $map )->save ( $data );
			if ($rs) {
				echo 1;
			} else {
				echo 0;
			}
		} else {
			echo 0;
		}
	
	}
*/

//编辑最新套系
	public function edit_series() {
		if (! is_numeric ( $_GET ['id'] )) {
			$this->error ( '编辑项不存在!' );
		}
		$id = intval ( $_GET ['id'] );
		$table = M ( 'Shop' );
		$map ['id'] = $id;
		$shop = $table->where($map)->field('id,name,sub_title,new_series')->find(); //查询出商家  还要查询出附加表中的图片
		$shop['new_series'] = htmlspecialchars($shop['new_series']); //换实体
		if (! $shop) {
			$this->error( '未找到该商家!' );
		}

		$this->assign ( 'shop', $shop );
		$this->display();
	}

//编辑最新套系
	public function save_series() {
		$this->assign ( 'waitSecond', 3 );
		$this->assign ( "jumpUrl", Cookie::get('_currentUrl_') );

		//先修改文档再上传图片
		$id = $_POST ['id'];
		$content = trim ( $_POST ['new_series'] );

		if (empty( $content )) {
			$this->error ( "最新套系不能为空!" );
		}
		$Table = D( 'Shop' );
		$data = $Table->create();
		if ($data) {
			$data['new_series'] = $content; //转换换行、空格
		} else {
			$this->error ( $Table->getError() );
		}

		unset ($map);
		$map['id']= $id;
		$rs = $Table->where($map)->save($data);
		if($rs !== false){
			$this->success ( "最新套系修改成功!" );
		}else{
			$this->error ( '最新套系修改失败' );
		}
	}

//---附件上传  xiongyan
	public function _upload(){
		ini_set('memory_limit','10M'); 
		import("ORG.Net.UploadFile");
		$upload = new UploadFile();
		//设置上传文件大小
		$upload->maxSize = 2048000;
        //覆盖之前头像
		$upload->uploadReplace = true;
        //是否多张上传
		$upload->supportMulti = false;
		//设置上传文件类型
		$upload->allowExts = array('jpg','gif','png','jpeg');
		$upload->savePath = './Public/Upload/Shop/';   //绝对路径
		//设置上传文件规则 
        $upload->saveRule = 'uniqid';
	    //设置需要生成缩略图，仅对图像文件有效
		$upload->thumb =  true;
		$upload->thumbPath = $upload->savePath;
		//设置需要生成缩略图的文件后缀
		$upload->thumbSuffix =  '';  //直接覆盖
		$upload->thumbPrefix =  '';  //直接覆盖

		$upload->thumbMaxWidth =  225; 
		$upload->thumbMaxHeight=  205;  //最大高度

		if($upload->upload()){
				$info = $upload->getUploadFileInfo();
				return $info;
		}else{
			 //捕获上传异常
             $this->error($upload->getErrorMsg()); 
		}
	}

//---添加附件 黄妃修改---------
	public function addAttach()
	{
		$info_id = intval($_GET['info_id']);
		if(empty($info_id))
		{
			$this->error('参数有误!');
		}

		$map['pid'] = 15 ;//附件大类
		$attach_list = D("Class")->where($map)->order("sortrank ASC,id ASC")->findAll();
		$this->assign('attach_list',$attach_list);
		$this->assign('info_id',$info_id);

		$map_att['info_id'] = $info_id;
		$map_att['model_name'] = "shop";

		$listRows="16";

		$Table = M("Attach");
		import("ORG.Util.Page");
		$count=$Table->where($map_att)->count();
		$p=new Page($count,$listRows);
		$page=$p->show();
	    $list =$Table->where($map_att)->limit($p->firstRow.','.$p->listRows)->findall();//显示已上传

		unset($map);
		$map['id'] = $info_id;
		$vo_lp = M('Shop')->field('id,default_attach_id')->where($map)->find();
		if($list){
			$this->assign('list',$list);
			$this->assign('page',$page);
			$this->assign('df_att_id',$vo_lp['default_attach_id']);
		}

		//dump($list);
		$this->display('add_attach');
	
	}

//---指定该楼盘默认图片 --- xy
	public function setDefaultPic(){
		$att_id = intval($_GET['att_id']);
		$map['id'] = intval($_GET['info_id']);
		$data['default_attach_id'] = $att_id;
		$rs = M('Shop')->where($map)->save($data);
		if($rs !== false){
			$this->success('指定该商家默认图片成功!');
		}else{
			$this->error('指定默认图片失败!');
		}
		
	}

}

?>