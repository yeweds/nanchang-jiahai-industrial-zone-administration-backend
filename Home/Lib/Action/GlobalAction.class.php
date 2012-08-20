<?php 
/**
 +------------------------------------------------------------------------------
 * Global  前台全局基类
 +------------------------------------------------------------------------------
 * @author 熊彦 <cnxiongyan@gmail.com>
 +------------------------------------------------------------------------------
 */
class GlobalAction extends Action  
{
	public $userInfo = false;	//用户信息
	protected $gid = 0;	      //用户组ID
	protected $uid = 0;	  //用户ID
	function _initialize()
	{
		header("Content-Type:text/html; charset=utf-8");
		if(C('cfg_status')=='0'){    //是否暂停
			die(C('cfg_stop_msg'));
			exit();
		}

//用户初始化开始
        if(Cookie::is_set('uid'))  $this->uid =  intval(Cookie::get('uid'));
		if($this->uid!=0){
					$checkShell = cookie::get('userShell');
					$userInfo	= $this->checkUser($this->uid , $checkShell);  //是否登录
					//dump($userInfo);
			//得到用户信息
		      $this->gid = $userInfo['role_id'];
			  $this->userInfo = $userInfo;
			  $this->assign('userInfo', $userInfo);
		}
	   //dump($this->userInfo);
	   //$this->assign('u_name', $userInfo['username']);  //用户名
	   //
	   //$this->assign('uid', $this->uid);
//用户初始化结束
	}

//检验用户是否登录受保护方法
protected  function checkUser($uid,$checkShell){
		$login = M('User');
		$map['id'] = $uid;
		$rs = $login->where($map)->find();
		if($rs){
					if(  $checkShell != md5($rs['pwd'].ShellSuffix)){
					cookie::delete('logTime');
					cookie::delete('uid');
					cookie::delete('userShell');
					$this->error('该用户不合法！');
		}else{
					$onlineTime = cookie::get('logTime');
					$allowTime   = C('cfg_loginTime');  //允许在线时间
					if ( (time() - $onlineTime) > $allowTime ) {  
							return false;  
					}else{
							cookie::set('logTime',time());	   //更新登录时间为当前时间
					}
							return $rs;
				}
		} else{
					return false;  
		}
}
 
 //获取用户ID
	protected function getUid(){
		return $this->uid;
	}

//获取用户组ID
	protected function getGid(){
		return $this->gid;
	}

//统计用户点评等信息
	protected function countUserInfo(){
		$curr_uid = $this->uid;  //当前用户
		if(!$curr_uid) 
			return false;
		$map['user_id'] = $curr_uid;
		$vo['sum_advice'] = M("Advice")->where($map)->count(); //咨询数
		$vo['sum_reviews'] = M("Reviews")->where($map)->count(); //评论数
		$vo['sum_favorite'] = M("Favorite")->where($map)->count(); //收藏数
		return $vo;
	}

//返回图片路径
public function join_url($path,$name,$type){	
		if($type=='thumb'){
			$url='http://'.$_SERVER['HTTP_HOST'].substr($path,1).'thumb_'.$name;
		}else if($type=='gray'){
			$url='http://'.$_SERVER['HTTP_HOST'].substr($path,1).'gray_'.$name;
		}else{
			$url='http://'.$_SERVER['HTTP_HOST'].substr($path,1).$name;
		}
		return $url;
	}	

/*析构函数，扩展计划任务
	public function __destruct(){
		$Cron = 	A('Guest');
		$Cron ->run();
	}
*/     
}
?>