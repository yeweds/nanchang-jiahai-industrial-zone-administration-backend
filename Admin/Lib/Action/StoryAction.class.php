<?php
/**
 +------------------------------------------------------------------------------
 * Story  后台故事管理类
 +------------------------------------------------------------------------------
 * @author 熊彦
 +------------------------------------------------------------------------------
 */
class StoryAction extends GlobalAction{
	private $news_pid = 1;  //故事大类ID

	public function index()
	{
        $Brand = M("Story"); 
		$Class = D('Class');
		$User  = M('User');
		$field = '*';  // 如果查视图,须写明所查列
		$fix = C("DB_PREFIX"); //前缀
		
		if(isset($_POST['s_key'])){
			$field = trim($_POST['s_type']);
			$in_key= trim($_POST['s_key']);
			if($field !== 'id'){
				$where[$field] = array('like', "%".$in_key."%" );
			}else{
				$where[$fix.'story.'.$field] = array('eq', $in_key );
			}
		}else{
			$where = array();
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
				$rs = $User->field('username')->where('id='.$vo['user_id'])->find();
				$list[$k]['username'] = $rs['username'];
			}
			$this->assign('list',$list); 
		}
		$this->assign('page',$page); 
		Cookie::set('_currentUrl_', __SELF__ ); //用于返回
		$this->display();
	}

//添加故事
	public function add(){
		$uid   =  $this->getUid();  //来源于Global中
		$username =  $this->getName();
		$map_admin['id'] = $uid;
		$admin = M('Admin')->where($map_admin)->find();
		$this->assign("realname",$admin['realname']); //真实姓名

		$this->assign("username",$username);
		$this->display('add');
	}

//故事入库
	public function Insert(){
		$title = trim($_POST['title']);
		$content = trim($_POST['content']);
		if(empty($title) || empty($content) ){
			    $this->assign("jumpUrl",__URL__);
				$this->error("标题和内容不能为空");
		}
		$news =  D("Story");
		$data = $news->create();
		if($data){
			$map['title'] = $title;
			$rs = $news->where($map)->find();
			if($rs){ //判断故事是否存在
				$this->assign("jumpUrl",__URL__);
				$this->error("该故事已经存在");
			}
			//dump( A('Keywords')->getKeywordedStr($content) );
			$data['title']      = $title;
			//$data['content']    = $content;  //转换换行、空格
			$data['content']    = A('Keywords')->getKeywordedStr($content);  //关键字加链接
			$data['add_time']   = time();
			//$data['meet_time']  = strtotime($data['meet_time']);
			//$data['isshow_sub_title'] = (isset($_POST['isshow_sub_title']) && $_POST['isshow_sub_title']==1 ? 1 : 0); //是否显示副标
			$fileinfo = $this->_upload();
			if(count($fileinfo)>0){
                $data['pic_url']  =  $fileinfo[0]['savename']; 
				$data['pic_url2'] =  $fileinfo[1]['savename'];  
			}
			$flags = $_POST['flag'];
			if(count($flags>0)){
				foreach($flags as $f){
                    $flag .= $f.',';
				}
                $data['flag'] = rtrim($flag,',');
				//dump($data['flag']);
			}

		}else{
			$this->error($news->getError());
		}
		if($insert_id = $news->add($data))
		{
			$this->assign('waitSecond',3);
			$this->assign("jumpUrl",__URL__);
			$this->success("故事添加成功!");
		}else{
			$this->error('故事添加失败!');
		}
	}

//编辑故事
	public function edit()
	{
        if(!is_numeric($_GET['id'])) {
			 $this->error('编辑项不存在');
		}
		$news = M("Story");
		$fix = C("DB_PREFIX");
		$table = $fix."story"; 
		$table2 = $fix."class"; 
		$table3 = $fix."user"; 
		$id = intval($_GET["id"]);
		$map['h_story.id'] = $id;
		$editVo=$news->where($map)->join("$table2 on $table.class_id=$table2.id")->join("$table3 on $table.user_id=$table3.id")->field("$table.* ,$table2.name,$table3.username")->find();
		unset($map);
		$editVo['content'] = htmlspecialchars($editVo['content']);
		$typename = $editVo['name'] ;
		$info_id = $editVo['info_id'] ; //关联的楼盘
		$this->assign("info_id",$info_id);
		$this->assign("typename",$typename);
		$this->assign("vo",$editVo);

//dump($editVo);
        //所有故事分类
        //$class = D('Class');
		//$map['pid'] = $this->news_pid;
		//$list_class = $class->where($map)->findAll();

		$this->display();
	}

//保存编辑后的故事
	public function save()
	{
		$id=intval($_POST['id']);
		if (!$id) $this->error('编辑项不存在');
		
		$Pages = D("Story");
		if( $data = $Pages->create()) { 
            $fileinfo = $this->_upload();
//dump($fileinfo);
			$add_time = strtotime($_POST['add_time']) ; //时间转换
			$data['add_time'] = $add_time;
			//$data['meet_time']  = strtotime($data['meet_time']);
			//$data['isshow_sub_title'] = (isset($_POST['isshow_sub_title']) && $_POST['isshow_sub_title']==1 ? 1 : 0); //是否显示副标

			if($fileinfo==true){
                 $data['pic_url']  =  $fileinfo[0]['savename'];
				 $data['pic_url2'] =  $fileinfo[1]['savename'];   
			}else{
                 $data['pic_url']  =  $_POST['pic_url'];
				 $data['pic_url2'] =  $_POST['pic_url2'];
			}
			$data['content'] = trim($data['content']);
			$data['content'] = A('Keywords')->getKeywordedStr($data['content']);  //关键字加链接
			//dump($data);

			$map['id'] = $id ;
			$rs =$Pages->where($map)->save($data);
			$this->assign('waitSecond', 10);

            if($rs!==false){ 
            	$this->assign("jumpUrl", Cookie::get('_currentUrl_') );
				$this->success("故事编辑成功!");
            }else{ 
                $this->error("故事编辑失败!"); 
            } 
        }else{ 
            $this->error($Pages->getError()); 
        } 
	}


//---删除故事---
	function del()
	{
		if(!is_numeric($_GET['id'])){
			$this->error('删除项不存在');
		}
		$id = intval($_GET['id']) ;
		$map['id'] = $id;

		$user = new Model("Story");
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

//---删除多条故事---
	function delall()
	{
		if(empty($_GET['str'])){
			$this->ajaxReturn('','您未选中任何项！',0);
		}
		$str = trim($_GET['str']);
		$Model = new Model("Story");
		$sql = " delete from __TABLE__ where id in (".$str.")";
		$result = $Model->execute( $sql );		
		if($result){
			$this->ajaxReturn('','删除成功',1);
		}else{
			$this->ajaxReturn('','删除失败',0);
		}
	    
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
		$upload->savePath = './Public/Upload/Story/';
		//设置上传文件规则 
        $upload->saveRule = 'uniqid';
	    //设置需要生成缩略图，仅对图像文件有效
		$upload->thumb =  true; 
		//设置需要生成缩略图的文件后缀
		$upload->thumbSuffix =  ''; //直接覆盖
		//设置缩略图最大宽度
		$upload->thumbMaxWidth =  268; 
		//设置缩略图最大高度
		$upload->thumbMaxHeight=  178; 

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
		if($rs = M("Story")->where($map)->find()){
			if(empty($rs['pic_url'])){
                return true;
			}
			$file = './Public/Upload/Story/'.$rs['pic_url'];
			$thumb_file = './Public/Upload/Story/thumb_'.$rs['pic_url'];
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
		$map_att['model_name'] = "story"; 
		
		$Table = M("Attach");
		$listRows="16";
		import("ORG.Util.Page");
		$count = $Table->where($map_att)->count();
		$p=new Page($count,$listRows);
		$page=$p->show();

	    $list = $Table->where($map_att)->limit($p->firstRow.','.$p->listRows)->findall();//显示已上传
		unset($map);
		$map['id'] = $info_id;
		$vo_lp = M('Story')->field('id,default_attach_id')->where($map)->find();
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
		$rs = M('Story')->where($map)->save($data);
		if($rs !== false){
			$this->success('指定该故事默认图片成功!');
		}else{
			$this->error('指定默认图片失败!');
		}
		
	}
}

?>