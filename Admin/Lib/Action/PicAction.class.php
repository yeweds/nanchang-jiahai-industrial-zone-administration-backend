<?php
/**
 +------------------------------------------------------------------------------
 * Pic  相册管理类
 +------------------------------------------------------------------------------
 * @author 熊彦
 +------------------------------------------------------------------------------
 */
class PicAction extends GlobalAction{
	private $pic_pid = 15;  //相册大类ID

	public function index()
	{
        $Brand = M("Pic"); 
		$Class = M('Class');
		$User  = M('Admin');
		$Shop  = M('Shop');
		$field = '*';  // 如果查视图,须写明所查列
		$fix   = C("DB_PREFIX"); //前缀
		
		if(isset($_GET['s_key'])){
			$field = trim($_GET['s_type']);
			$in_key= trim($_GET['s_key']);
			if($field == "title"){
				$where[$field] = array('like', "%".$in_key."%" );
			}else{
				$where[$fix.'pic.'.$field] = array('eq', $in_key );
			}
		}else{
			$where = array();
		}

		if(isset($_GET['shop_id'])){
			// from  Shop/index页
			$shop_id = intval($_GET['shop_id']);
			$where[$fix.'pic.shop_id'] = array('eq', $shop_id ); //仅查单个商家的相册
		}

		$count= $Brand->where($where)->count();
        
		import("ORG.Util.Page"); //导入分页类 
		$listRows = 20;
		$p = new Page($count,$listRows); 
		$list=$Brand->where($where)->limit($p->firstRow.','.$p->listRows)->order("add_time desc, id desc")->findAll(); 
		//echo $Brand->getLastSql();
		$page=$p->show();
		if($list){
			foreach($list as $k=>$vo){
				if(strlen($vo['title'])>40){
					$list[$k]['title'] = mb_substr($vo['title'],0,40,'utf8').'..';
				}else{
					$list[$k]['title'] = $vo['title'];
				}
				$rs = $User->field('id,admin as username')->where('id='.$vo['user_id'])->find();
				$list[$k]['username'] = $rs['username'];

				if($vo['shop_id']>0 ){
					$rs = $Shop->where("id=".$vo['shop_id'])->field('id,name,pid,head_py')->find();
					$list[$k]['shop_name'] = $rs['name']; //查已关联商家名
				}

			}
			$this->assign('list',$list); 
		}
		$this->assign('page',$page); 
		Cookie::set('_currentUrl_', __SELF__ ); //用于返回
		$this->display();
	}

//添加相册
	public function add(){

		$map['pid'] = $this->pic_pid;
		$classT = M('Class')->where($map)->select();
		if (! $classT) {
			$this->error ( '分类不存在，请先添加分类!' );
		}
		$this->assign ( 'classT', $classT );
		unset($map);
		//关联商家
		$Shop = M('Shop');
		$loupan_list = $Shop->field('id,name,pid,head_py')->order('head_py asc')->findAll();
		$this->assign("loupan_list",$loupan_list);
		if(isset($_GET['info_id'])){
			$shop_id = intval($_GET['info_id']);
			$rs = $Shop->where("id=".$shop_id)->field('id,name,pid,head_py')->find();
			$this->assign("mapping_name",$rs['name']);
		}
		//--关联商家end

		$uid   =  $this->getUid();  //来源于Global中
		$username =  $this->getName();
		$map_admin['id'] = $uid;
		$admin = M('Admin')->where($map_admin)->find();
		$this->assign("realname",$admin['realname']); //真实姓名

		$this->assign("username",$username);
		$this->display('add');
	}

//相册入库
	public function Insert(){
		$title = trim($_POST['title']);
		$content = trim($_POST['remark']);
		if(empty($title) || empty($content) ){
			    $this->assign("jumpUrl",__URL__);
				$this->error("标题和描述不能为空");
		}
		$news =  D("Pic");
		$data = $news->create();
		if($data){
			$map['title'] = $title;
			$rs = $news->where($map)->find();
			if($rs){ //判断相册是否存在
				$this->assign("jumpUrl",__URL__);
				$this->error("该相册已经存在");
			}
			//dump( A('Keywords')->getKeywordedStr($content) );
			$data['title']      = $title;
			$data['add_time']   = time();


		}else{
			$this->error($news->getError());
		}
		if($insert_id = $news->add($data))
		{
			$this->assign('waitSecond',3);
			$this->assign("jumpUrl",__URL__);
			$this->success("相册添加成功!");
		}else{
			$this->error('相册添加失败!');
		}
	}

//编辑相册
	public function edit()
	{
        if(!is_numeric($_GET['id'])) {
			 $this->error('编辑项不存在');
		}
		$Pic = M("Pic");
		$fix  = C("DB_PREFIX");
		$table  = $fix."pic"; 
		$table2 = $fix."class"; 
		$table3 = $fix."user"; 
		$id = intval($_GET["id"]);
		$map['h_pic.id'] = $id;
		$editVo = $Pic->where($map)->join("$table2 on $table.class_id=$table2.id")->join("$table3 on $table.user_id=$table3.id")->field("$table.* ,$table2.name,$table3.username")->find();
		unset($map);
		//echo $Pic->getlastsql();
		$editVo['class_name'] = $editVo['name'];
		unset($editVo['name']) ;
		$shop_id  = $editVo['shop_id'] ; //关联的楼盘

		unset($map);
		$map['pid'] = $this->pic_pid;
		$class = M('Class')->where($map)->select();
		if (!$class) {
			$this->error ('暂无分类，请先添加!' );
		}
		$this->assign ('class', $class );

		//关联商家
		$loupan_list = M('Shop')->field('id,name,head_py')->order('head_py asc')->findAll();
		$this->assign("loupan_list",$loupan_list);
		//当前已关联---start
		$map_mp['id'] = $shop_id;
		$rs = M('Shop')->field('id,name,head_py')->where($map_mp)->find();
		$editVo['mapping_name'] = $rs['name'];
		//当前已关联---end

		$this->assign("shop_id",$shop_id);
		$this->assign("vo",$editVo);
//dump($editVo);
		$this->display();
	}

//保存编辑后的相册
	public function save()
	{
		$id=intval($_POST['id']);
		if (!$id) $this->error('编辑项不存在');
		//dump($_POST);
		$Pages=D("Pic");
		if( $data = $Pages->create()) { 

			$add_time = strtotime($_POST['add_time']) ; //时间转换
			$data['add_time'] = $add_time;

			$data['remark'] = trim($data['remark']);

			$map['id'] = $id ;
			$rs =$Pages->where($map)->save($data);
			$this->assign('waitSecond', 10);

            if($rs!==false){ 
            	$this->assign("jumpUrl", Cookie::get('_currentUrl_') );
				$this->success("相册编辑成功!");
            }else{ 
                $this->error("相册编辑失败!"); 
            } 
        }else{ 
            $this->error($Pages->getError()); 
        } 
	}


//---删除相册---
	function del()
	{
		if(!is_numeric($_GET['id'])){
			$this->error('删除项不存在');
		}
		$id = trim($_GET['id']) ;
		$map['id'] = $id;

		$user = new Model("Pic");
		$result = $this->del_attach($id); //先删图片或其它附件

		if(false!==$result)
		{
			$result=$user->where($map)->delete();
			if($result){
				$this->assign('jumpUrl',__URL__);
				$this->success('数据删除成功!');
			}else{
				$this->error('删除出错,仅删除了图片!');
			}
		}
		else
		{
			$this->error('删除图片失败!');
		}
	}

//---删除多条相册---
	function delall()
	{
		if(empty($_GET['str'])){
			$this->ajaxReturn('','您未选中任何项！',0);
		}
		$list = explode(',',$_GET['str']);
		$Model = new Model("Pic");
	    foreach($list as $id) //循环删除
	    {
				$result = $Model->execute(" delete from __TABLE__ where id=".$id);		
				if($result){
					$arr.= $id.',';
				}
		}
	    $this->ajaxReturn('',$arr.'删除成功',1);
	}

//---附件上传  xiongyan
	public function _upload(){
		import("ORG.Net.UploadFile");
		$upload = new UploadFile();
		//设置上传文件大小
		$upload->maxSize = 1024000;
        //覆盖之前头像
		$upload->uploadReplace = true;
        //是否多张上传
		$upload->supportMulti = false;
		//设置上传文件类型
		$upload->allowExts = array('jpg','gif','png','jpeg');
		$upload->savePath = './Public/Upload/Pic/';
		//设置上传文件规则 
        $upload->saveRule = 'uniqid';
	    //设置需要生成缩略图，仅对图像文件有效
		$upload->thumb =  true; 
		//设置需要生成缩略图的文件后缀
		$upload->thumbSuffix =  ''; //直接覆盖
		//设置缩略图最大宽度
		$upload->thumbMaxWidth =  200; 
		//设置缩略图最大高度
		$upload->thumbMaxHeight=  200; 

		if($upload->upload()){
				$info = $upload->getUploadFileInfo();
				return $info;
		}else{
			 //捕获上传异常
             //$this->error($upload->getErrorMsg()); 
			 return false ;
		}
	}
//删除文件	
	public function del_attach($id){
		$map['id'] = $id ;
		if($rs = M("Pic")->where($map)->find()){
			if(empty($rs['pic_url'])){
                return true;
			}
			$file = './Public/Upload/Pic/'.$rs['pic_url'];
			$thumb_file = './Public/Upload/Pic/thumb_'.$rs['pic_url'];
			if(file_exists($file)){
				if(unlink($file) && unlink($thumb_file)){
					return true;
				}else{
                    return false;
				}
			}	
		}
		return true;
	}


//---添加附件 xy修改---------
	public function addAttach()
	{
		$info_id = intval($_GET['info_id']);
		if(empty($info_id))
		{
			$this->error('参数有误!');
		}

		$map['pid'] = 15 ;//附件大类
		$attach_list = M("Class")->where($map)->order("sortrank ASC,id ASC")->findAll();
		$this->assign('attach_list',$attach_list);
		$this->assign('info_id',$info_id);

		$map_att['info_id'] = $info_id;
		$map_att['model_name'] = "pics"; 
		
		$Table = M("Attach");
		$listRows="16";
		import("ORG.Util.Page");
		$count = $Table->where($map_att)->count();
		$p=new Page($count,$listRows);
		$page=$p->show();

	    $list = $Table->where($map_att)->limit($p->firstRow.','.$p->listRows)->findall();//显示已上传
		unset($map);
		$map['id'] = $info_id;
		$vo_lp = M('Pic')->field('id,default_attach_id')->where($map)->find();
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
		$rs = M('Pic')->where($map)->save($data);
		if($rs !== false){
			$this->success('指定该相册默认图片成功!');
		}else{
			$this->error('指定默认图片失败!');
		}
		
	}
}

?>