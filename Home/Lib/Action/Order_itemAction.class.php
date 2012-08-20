<?php
/**
 +------------------------------------------------------------------------------
 * Order_item  前台团购项目管理类
 +------------------------------------------------------------------------------
 * @author 黄妃
 +------------------------------------------------------------------------------
 */
class Order_itemAction extends GlobalAction{
	public function index() 
	{ 
		//显示用户选中的楼盘活动

		//如果用户已经登录 显示他收藏的活动

        //显示已开启的楼盘活动

		//显示腾房推荐的活动

		//$this->display();
	}
//用户点击确认参与楼盘活动
	public function submit($len=10) 
	{
        $uid = Cookie::get('uid');
		if(empty($uid)){
			//包含用户填写用户名，密码 
		}

	}
//保存用户参与楼盘的活动
	public function insert() 
	{
		$success = false ;
        $uid = Cookie::get('uid');
		if(empty($uid)){
			//包含用户填写用户名，密码 
			$email = trim($_POST['email']);
			$pwd = trim($_POST['pwd']);
			$orderarr = $_POST['orderid'];//有可能选中多项
			if(empty($email) || empty($pwd) || empty($orderarr)){
                 $this->error('提交参数错误');
			}
			$User = D('User');
			$map['username'] = $email ;
			$rs = $User->where($map)->select();
			if(!empty($rs['username'])){
				$this->error('该用户已经存在');
				exit;
			}else{
				$data['username'] = $email ;
				$data['pwd'] = $pwd ;
				if(M('User')->add($data)){//添加用户成功
                     $success = true ;
                     unset($data);
					 $data['username'] = $email ;
					 foreach($orderarr as $orderid){
                         $data['order_id'] = $orderid ;
                         $data['add_time'] = time() ;
						 $typearr = $_POST['typearr|'.$orderid];//有可能选中多项活动类型 ,用$orderid来唯一标识是哪个活动
						 if(empty($typearr) || count($typearr)<=0){
                              $this->error('注册成功，但你没有提交选中活动类型，保存活动失败');
						 }
						 if(M('Order_temp')->add($data)){ //选中活动成功后  Order_item表中活动参与人数字段+1
							 $maporder['id'] = $orderid ;
							 $rs = $User->where($map)->select();
							 $joinnum = $rs['joinnum'] ;
							 M('Order_item')->startTrans();   //启动事务//事务开始 
                             foreach($typearr as $type){
                                  $posb = strpos($joinnum,$type."|");//找到该类型，将|后面的数字加1								  
								  if($posb>0){
									  $strb = substr($joinnum,$posb);//保存截取的前面
									  $pose = strpos($joinnum,',',$posb);//是否是最后一个类型
									  if($pose>0){
                                           $num = substr($joinnum,$posb,$pose)+1;
										   $stre = substr($joinnum,$pose);//保存截取的后面
										   $joinnum = $strb.$type."|".$num.','.$stre ; //重组该字段值
									  }else{
										   $num = substr($joinnum,$posb)+1;
										   $stre = substr($joinnum,$pose);//保存截取的后面
										   $joinnum = $strb.$type."|".$num  ;         //重组该字段值
									  }
                                      $dataorder['joinnum'] = $joinnum ;
									  if(M('Order_item')->where($maporder['id'])->save($dataorder)){

									  }else{
										  M('Order_item')->rollback();   //回滚事务 
									  }
								  }
							 }
							 M('Order_item')->commit();    //提交事务
						 }
					 }
					 
				}else{
					$this->error('注册用户失败');
					exit;
				}
			}
		}
        
        

	}
//获取用户收藏的活动
	public function getfav($len=10) 
	{ 
		$uid = Cookie::get('uid');
		if(empty($uid)){
			exit;
		}
        $username = D('Order_item')->field('username')->where('id='.$uid)->find();
		$Job = D('Order_temp');
		$map['username'] = $username ;
		if(empty($len)){
			$list = $Job->where($map)->findAll();
		}else{
			$list = $Job->where($map)->limit("0,$len")->select(); 
		}
		//echo $Job->getLastSql();
		dump($list);
		//$this->display();
	}
//获取腾房推荐的活动
	public function gettj($len=10) 
	{ 
		$Job = D('Order_item');
		$map['brecommend'] = 1 ;
		if(empty($len)){
			$list = $Job->where($map)->findAll();
		}else{
			$list = $Job->where($map)->limit("0,$len")->select(); 
		}
		//echo $Job->getLastSql();
		dump($list);
		//$this->display();
	}
//获取已开始的活动
	public function getstart($len=10) 
	{ 
		$Job = D('Order_item');
		$map['bstart'] = 1 ;
		if(empty($len)){
			$list = $Job->where($map)->findAll();
		}else{
			$list = $Job->where($map)->limit("0,$len")->select(); 
		}
		//echo $Job->getLastSql();
		dump($list);
		//$this->display();
	}


 }


?>