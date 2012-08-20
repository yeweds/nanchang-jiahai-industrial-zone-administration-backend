<?php
  //文档及搜索关键词管理 -- xiongyan
  class KeywordsAction extends GlobalAction
  {
	public function index(){
		$Article=M('Keywords');    
		$listRows="20";
		import("ORG.Util.Page");
		$count=$Article->count();
		$p=new Page($count,$listRows);
		$page=$p->show();

	    $list =$Article->order("id desc")->limit($p->firstRow.','.$p->listRows)->findall();
		if($list!==false){		
			$this->assign('list',$list);
			$this->assign('page',$page);
		}
		Cookie::set ( '_currentUrl_', __SELF__ ); //用于返回
		$this->display();
	}

//添加表单
	public function add(){
			$this->display('add');
	}

//添加入库
	public function Insert(){
		 $Table =  D('Keywords');
		 $data = $Table->create();
		 if(empty($data['keyword'])){
			$this->error('请输入关键字！');
		 }
		 if($data){
			 $data['keyword']      = trim($_POST['keyword']);
			 //$data['add_time']   = time();
		 }else{
			$this->error($Table->getError());
		 }

		if($Table->add($data))
		{
			 $this->assign("jumpUrl",__URL__."/index");
			 $this->success("文档关键词添加成功!");
		}
	}

//编辑表单
  public function edit()
	{
       if(!is_numeric($_GET['id'])) {
			 $this->error('编辑项不存在');
	   }
	   $map['id'] = intval($_GET['id']);
	   $editVo = M('Keywords')->where($map)->find();
	   $this->assign("vo",$editVo);
	   $this->display();
	}

//用于保存编辑后的信息
	public function save()
	{
		$id=intval($_POST['id']);
		if (!$id) $this->error('编辑项不存在');
		$Pages=D("Keywords");
		if( $Pages->create()) { 
            if($Pages->save()!==false){ 
            	$this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
				$this->success("文档关键词编辑成功!");
            }else{ 
                $this->error("文档关键词编辑失败!"); 
            } 
        }else{ 
            $this->error($Pages->getError()); 
        } 
	}

//---删除---
	function del()
	{
		if(!is_numeric($_GET['id'])){
			$this->error('删除项不存在');
		}
			$Article = M("Keywords");
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
		$Form = M("Keywords");
		$del_id = $_GET['str'];
		$where['id']=array('in',$del_id);  //删除条件
		$result=$Form->where($where)->delete();
		if($result){
			$this->ajaxReturn('','所选信息删除成功',1);
		}else{
			$this->ajaxReturn('','批量删除操作有误',0);
		}
	}

//---全选保存---
	public function saveAll()
	{
		//dump($_POST);
		$dopost = $_POST['dopost'];
		if(empty($dopost))
		{
			$dopost = '';
		}
		//保存批量更改
		if($dopost=='saveall' && is_array($_POST['id']) )
		{
			$aids  = $_POST['id'];
			$Table = M("Keywords");
			foreach($aids as $aid)
			{
				$rpurl = $_POST['rpurl_'.$aid];
				$rpurlold = $_POST['rpurlold_'.$aid];
				$keyword = $_POST['keyword_'.$aid];

				//删除项目
				if(!empty(${'isdel_'.$aid}))
				{
					$Table->where("id='$aid'")->delete();
					continue;
				}

				//禁用项目
				$staold = ${'staold_'.$aid};
				$sta = empty(${'isnouse_'.$aid}) ? 1 : 0;
				if($staold!=$sta)
				{
					$map['id'] = $aid;
					$data['sta'] = $sta;
					$data['rpurl'] = $rpurl;
					$Table->where($map)->save($data);
					//$query1 = "update `#@__keywords` set sta='$sta',rpurl='$rpurl' where aid='$aid' ";
					continue;
				}

				//更新链接网址
				if($rpurl!=$rpurlold)
				{
					$map_['id'] = $aid;
					$data_['rpurl'] = $rpurl;
					$Table->where($map_)->save($data_);
					//$query1 = "update `#@__keywords` set rpurl='$rpurl' where aid='$aid' ";
				}
			}
			$this->assign( "jumpUrl",  Cookie::get ( '_currentUrl_' ) );
			$this->success("已完成指定的更改！");
			exit();
		}else{
			//未选取
			$this->error('未选中任何项！');
		}

	}

//获取替换关键词后的字符串
	public function getKeywordedStr($str){
		$Table = M('Keywords');
		$field = 'id,keyword,rpurl,sta';
		$map = null;
		$list = $Table->where($map)->field($field)->findall();	
	
		foreach($list as $k=>$v){
				if($v['sta'] == '1'){
					if( $rs = strstr($str, $v['rpurl']) ){ 
						//strstr("Hello world!","world");
						$str = str_replace( $v['rpurl'], $v['keyword'], $str);
					}
					$str = str_replace( $v['keyword'], $v['rpurl'], $str);
				}
		}
		return $str;
	}

//写楼盘名到关键字表
	public function writeLpName(){
		$Table = M('New_loupan');
		$K     = M('Keywords');

		$field = 'info_id,lpname';
		$map = null;
		$list = $Table->where($map)->field($field)->findall();	

		foreach($list as $k=>$v){
				$data['keyword'] = $v['lpname'];
				$data['rpurl']   = '<a href="http://nc.tengfang.net/lp-'.$v['info_id'].'">'.$v['lpname'].'</a>';
				$data['rank']    = 30;
				$data['sta']     = 1;
				$rs = $K->add($data);
				echo $rs;
		}
		echo '关键字转移成功';
	}
	
  }
?>