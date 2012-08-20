<?php
/**
 +------------------------------------------------------------------------------
 * NewsTopic  话题PK管理类
 +------------------------------------------------------------------------------
 * @author 熊彦
 +------------------------------------------------------------------------------
 */
class NewsTopicAction extends GlobalAction{
	public function index()
	{
		$Topic = M('Topic_pk');
		$count = $Topic->count();
		import("ORG.Util.Page"); //导入分页类 
		$listRows = 20;
		$p = new Page($count,$listRows); 
		$list = $Topic->order('add_time desc')->limit($p->firstRow.','.$p->listRows)->findAll(); 
		//echo $Topic->getLastSql();
		$page = $p->show();
		if($list){
			foreach($list as $k=>$vo){
				$title = strip_tags($vo['title']);
				$content = strip_tags(trim($vo['content'])); //去标签
				$list[$k]['title'] = mb_substr($title,0,20,'utf8').'..';//标题只显示18个;
				$list[$k]['content'] = mb_substr($content,0,30,'utf8').'..';;
				$list[$k]['r_content'] = mb_substr($vo['r_content'],0,10,'utf8').'..';;
				$list[$k]['l_content'] = mb_substr($vo['l_content'],0,10,'utf8').'..';;
			}
			$this->assign('list',$list); 
		}
		//dump($list);
		$this->assign('page',$page); 
		Cookie::set ( '_currentUrl_', __SELF__ ); //用于返回
        $this->display();
	}

	public function edit()
	{
		$id = $_GET['id'];
		if(empty($id)){
		    $this->error("参数错误");
		}
        $Topic = M('Topic_pk');
		$map['id'] = $id;
		$vo = $Topic->where($map)->find();
		$this->assign("vo",$vo);
		$this->display();
	}

//添加入库
	public function insert(){
		 $Model =  D('Topic_pk');
		 $data = $Model->create();
		 if($data){
			 $data['add_time']   = time();
		 }else{
			$this->error($Model->getError());
		 }
		if($id = $Model->add($data))
		{
			if($_POST['isnew']==1){ //设置其他话题为往期
				$rs = $Model->execute(" update __TABLE__ set isnew=0 where id<>".$id );
			}
			$this->assign("jumpUrl",__URL__."/index");
			$this->success("话题PK添加成功!");
		}else{
			$this->error("话题PK添加失败!");
		}
	}
//用于保存编辑后的信息
	public function save()
	{
		$id=intval($_POST['id']);
		if (!$id) $this->error('编辑项不存在');

		$Pages = D("Topic_pk");	
		if( $Pages->create()) { 
			$result = $Pages->save() ;
            if($result!==false){ 
				if($_POST['isnew']==1){ //设置其他话题为往期
					$rs = $Pages->execute(" update __TABLE__ set isnew=0 where id<>".$id );
				}
            	$this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
				$this->success("话题PK编辑成功!");
            }else{ 
                $this->error("话题PK编辑失败!"); 
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
		$map['id'] = intval($_GET['id']);
		$rs= M("Topic_pk")->where($map)->delete();
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
		$Form = M("Topic_pk");
		$del_id = $_GET['str'];
		$where['id']=array('in',$del_id);  //删除条件
		$result=$Form->where($where)->delete();
		if($result){
			$this->ajaxReturn('','所选信息删除成功',1);
		}else{
			$this->ajaxReturn('','批量删除操作有误',0);
		}
	}
}
?>