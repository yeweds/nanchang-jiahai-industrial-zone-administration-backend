<?php
/**
 +------------------------------------------------------------------------------
 * Global  后台全局基类
 +------------------------------------------------------------------------------
 * @author 熊彦 <cnxiongyan@gmail.com>
 +------------------------------------------------------------------------------
 */
class GlobalAction extends Action{
	private $gid = 0;
	private $uid = 0;
	private $admin;
	function _initialize()
	{
		//初始化时获取用户ID和用户组ID
		$this->gid   =  intval(Cookie::get('ht_groupid'));
		$this->uid   =  intval(Cookie::get('ht_'.C('cfg_auth_key')));
		$this->admin =  Cookie::get('ht_admin');

		if($this->uid==0 || $this->gid==0)
		{
			//$this->redirect('login','Public');
			$this->assign('jumpUrl',__APP__.'/Public/login');
			$this->error('您还没有登录！');
		}
		if ($this->gid != 1){  //非超级管理组则检测权限
			//echo '您不是超管';
			//exit();
		}
	}
	/*
		获取用户ID
	*/
	protected function getUid(){
		return $this->uid;
	}
	/*
		获取用户组ID
	*/
	protected function getGid(){
		return $this->gid;
	}
	/*
		获取用户名
	*/
	protected function getName(){
		return $this->admin;
	}

	/***************************************
	@ 公共操作：普通   删除/锁定/审核
	@ $tableName : 操作数据表, 默认为 $this->name
	@ $act : 操作类型 /  $getid: 条件ID
	***************************************/
	public function _subAction($tableName="",$act="",$getid=null,$pk='id')
	{
		if(isset($_REQUEST[$pk]))   $getid=$_REQUEST[$pk];

		if (!$getid) $this->error('未选择记录') ;
		//如果GETID为数组则需要判断是否有批量操作权限
		if (is_array($getid)) {
				//预留权限检验
		}
		$id = is_array($getid)?$getids:$getid;
		//过滤操作类型
		if (!$act) $this->error('操作类型必须指定');//操作类型必须选择
		$allowAct=array('lock','unlock','passed','unpassed','remove','delete');
		if (!in_array($act,$allowAct))  $this->error('未知操作类型'); //未知操作
		if (!$id) $this->error('ID丢失');

		$TB = M($tableName);
		switch ($act){
			case 'lock':$rs =$TB->execute('UPDATE __TABLE__ SET `status`=0 WHERE `'.$pk.'` IN ('.$id.')'); $say='已设为锁定状态'; break;    //锁定
			case 'unlock':$rs=$TB->execute('UPDATE __TABLE__ SET `status`=1 WHERE `'.$pk.'` IN ('.$id.')'); $say='解锁成功'; break;	 //解锁
			case 'passed':$rs=$TB->execute('UPDATE __TABLE__ SET `is_passed`=1 WHERE `'.$pk.'` IN ('.$id.')'); $say='审核通过'; break;      //审核
			case 'unpassed':$rs=$TB->execute('UPDATE __TABLE__ SET `is_passed`=0 WHERE `'.$pk.'` IN ('.$id.')');$say='已取消审核'; break;   //取消审核
			//case 'remove':$rs=$TB->execute('UPDATE __TABLE__ SET `class_id`='.$category.' WHERE `'.$pk.'` IN ('.$id.')');$say='移动成功'; break;  //移动
			case 'delete': $rs=$TB->execute('DELETE FROM __TABLE__ where `'.$pk.'` IN ('.$id.')'); $say='删除成功'; break;  //删除
		}	
		$this->assign('waitSecond',5);
		if($rs===false){
			$this->error("操作失败");
		}else{
			$this->assign('jumpUrl', Cookie::get ( '_currentUrl_' ) );
	        $this->success($say);
		}

	}
	
	//---获取一个分类信息 [ 参数：class_id ]
	public function getOneClass($class_id){
        $condition['id']   =   $class_id;
        $vo  =  D("Class")->where($condition)->field('id,name,doc_class,level,curr_path')->find();
        return $vo;
	}	
	
	//---获取所有分类列表,最多三级
	public function getClass(){
        $classT=  D("Class")->where($condition)->field('id,pid,name,level,curr_path')->order('pid ASC,sortrank ASC')->findAll();

		$class=array();
		//得到一个数组，将此数据按栏目级别排序
		foreach($classT as $vo){
			//只循环顶级栏目就可以了
			if($vo['level']=='1'){
				array_push($class,$vo);
				//查找所有第二级栏目
				foreach($classT as $voo){
					if($voo['level']=='2'){
						if($voo['pid']==$vo['id']){
							array_push($class,$voo);
													
							//查找所有第三级栏目
							foreach($classT as $vooo){
								if($vooo['level']=='3'){
									if($vooo['pid']==$voo['id']){
										array_push($class,$vooo);
									}
								}
								
							}//end查找所有第三级栏目							
							
							
						}
					}
					
				}//end查找所有第二级栏目
					
			}
			
		}//end查找所有第一级栏目
        return $class;
	}	
	
	
	//根据id值或class_id查出图像,返回图像数组
	public function find_pic($id,$mode,$class_id){
		//$mode: index为查出首张图，all 表示查出所有的图
		
		//如果存在class_id,则以此为主
		if(isset($class_id)){
			$map['class_id']=$class_id;
		}else{
			$map['news_id']=$id;
		}
	
		//查出幻灯片
		$picT=M('attach');
		
		//1.模式为只查出首张图
		if($mode=='index'){
			$pic=$picT->where($map)->order('is_index DESC')->find();
			//替换图片的路径
			 $pic['pic_url']=$this->join_url($pic['savepath'],$pic['savename']);	
			 $pic['thumb_pic_url']=$this->join_url($pic['savepath'],$pic['savename'],"thumb");	//缩略图
			 $pic['gray_pic_url']=$this->join_url($pic['savepath'],$pic['savename'],"gray");//灰度图



		}else{
			$pic=$picT->where($map)->order('is_index DESC,id DESC')->select();
			//替换图片的路径
			foreach($pic as $k=>$v){
				$pic[$k]['pic_url']=$this->join_url($v['savepath'],$v['savename']);	
				$pic[$k]['thumb_pic_url']=$this->join_url($v['savepath'],$v['savename'],"thumb");//缩略图
				$pic[$k]['gray_pic_url']=$this->join_url($v['savepath'],$v['savename'],"gray");//灰度图
			}		
			
		}
		
		return $pic;
		
	}	
	
}
?>