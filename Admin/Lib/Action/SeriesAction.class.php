<?php
  //套系管理
  class SeriesAction extends GlobalAction
  {
	public function index(){
		$Article=M('Series');    
		$listRows="20";
		import("ORG.Util.Page");
		$count=$Article->count();
		$p=new Page($count,$listRows);
		$page=$p->show();

        //判断用户选择的是“按全称”，“按简称”，还是“按ID”来进行搜索
        $map = array();
        if( isset($_GET['s_key']) && !empty($_GET['s_type']) ){
            $type = trim($_GET['s_type']);
            $key = trim($_GET['s_key']);
            if( $type == 'title'){
                $map[$type] = array( 'like', "%".$key."%" );
            }elseif( $type=='sub_title' ){
                $map['title'] = array( 'like', "%".$key."%" );
            }else{
                $map[$type] = array('eq', $key);
            }
        }
        // dump($map);
        // exit;

	    $list =$Article->where($map)->order("id desc")->limit($p->firstRow.','.$p->listRows)->findall();
        // echo $Article->getLastSql();
        // exit;
        // $rs = $Article->find();
        // dump($rs);
        // exit;
        

        //依次找出每个商家的名称
        unset($map);
        $shopTable = M('Shop');
        foreach ($list as $k => $v) {
            $map['id'] = $v['shop_id'];
            // dump($map);
            // exit;
            $rs = $shopTable->where($map)->find();
            // dump($rs);
            // exit;
            $list[$k]['shop_name'] = $rs['name'];
        }

		if($list!==false){		
			$this->assign('list',$list);
			$this->assign('page',$page);
		}
		Cookie::set ( '_currentUrl_', __SELF__ ); //用于返回
		$this->display();
	}

//添加表单
	public function add(){
		$shop_id= intval($_GET['shop_id']);
		if(!$shop_id) $this->error('未知商家！');
		$rs = M('Shop')->where('id='.$shop_id)->find();
		$shop_name = ( $rs? $rs['name'] : "未知" );
		$this->assign('shop_name',$shop_name);
		$this->display('add');
	}

//添加入库
	public function Insert(){
		 $newArticle =  D('Series');
		 $data = $newArticle->create();
		 if($data){
			 $data['title']      = trim($_POST['title']);
			 $data['content']    = $_POST['content'];
			 $data['add_time']   = time();

			$fileinfo = $this->_upload();
			if(count($fileinfo)>0){
				$data['pic_url']  =  $fileinfo[0]['savename']; 
			}

		 }else{
			$this->error($newArticle->getError());
		 }
			if($newArticle->add($data))
			{
				 $this->assign("jumpUrl",__URL__."/index");
				 $this->success("套系添加成功!");
			}
	}

//编辑表单
	public function edit()
	{
		if(!is_numeric($_GET['id'])) {
			 $this->error('编辑项不存在');
		}
		$id = intval($_GET["id"]);
		$map['id'] = $id;
		$editVo    = M('Series')->where($map)->find();
		$this->assign("vo",$editVo);

		$shop_id= intval($editVo['shop_id']);

		$rs = M('Shop')->where('id='.$shop_id)->find();
		$shop_name = ( $rs? $rs['name'] : "未知" );
		$this->assign('shop_name',$shop_name);

		$this->display();
	}

//用于保存编辑后的信息
	public function save()
	{
		$id=intval($_POST['id']);
		if (!$id) $this->error('编辑项不存在');
		$Pages = D("Series");
		if( $data = $Pages->create()) { 
			$fileinfo = $this->_upload();
			if($fileinfo==true){
                 $data['pic_url']  =  $fileinfo[0]['savename']; 
			}else{
                 $data['pic_url']  =  $_POST['pic_url'];
			}

            if( $Pages->where("id=".$id)->save($data) !== false ){ 
            	$this->assign('jumpUrl',__URL__);
				$this->success("套系编辑成功!");
            }else{ 
                $this->error("套系编辑失败!"); 
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
			$Article = M("Series");
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
		$Form = M("Series");
		$del_id = $_GET['str'];
		$where['id']=array('in',$del_id);  //删除条件
		$result=$Form->where($where)->delete();
		if($result){
			$this->ajaxReturn('','所选信息删除成功',1);
		}else{
			$this->ajaxReturn('','批量删除操作有误',0);
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
		$upload->savePath = './Public/Upload/Series/';
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

  }
?>