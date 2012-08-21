<?php
/**
 +------------------------------------------------------------------------------
 * Reg  用户注册类
 +------------------------------------------------------------------------------
 * @author 熊彦
 +------------------------------------------------------------------------------
 */
class RegAction extends  GlobalAction{
//注册表单页
	public function index(){
		if(C('cfg_allow_reg')==false){
			$this->error("抱歉，本站管理员已暂时关闭注册功能！");
			exit();
		}
		$this->assign('TitleMsg','用户注册');
		$this->display('reg');
	}

//注册成功页	
	public function regok($msg){
	    $this->assign('msg',$msg);
		$this->assign('TitleMsg','注册成功');
		$this->display('Reg:regok');
	}

//保存添加用户
	public function Insert(){
		$email = trim($_POST['email']);
		$uname = $_POST['username'];
		$pwd    = $_POST['pwd'];
		$verify = $_POST['checkcode'];
		if(md5($verify) != $_SESSION['verify']){
				$this->error('验证码错误！');
				return;
		}
				$user = D('User');
				if($vo = $user->create()) {
					$vo['username']= $uname;
					$vo['realname']= $uname;
					$vo['pwd']= md5($pwd);
					$vo['role_id'] = $_POST['groupid'];
					$vo['reg_time'] = time();
					$id = $user->add($vo);
					echo $user->getlastsql();
					if($id){
						$user->login_record($id);	 //记录最后登录时间和IP
						Session::set('uname',$uname);
						Session::set('pwd',$pwd);
						C('COOKIE_EXPIRE' , 60*60*24*30);  //修改cookie保存时间
						Cookie::set('uid',$id);
						Cookie::set('userShell',md5($vo['pwd'].ShellSuffix));
						Cookie::set('logTime',time());


						//注册成功邮件通知--------------------------
						$msg = $this->sendRegMail($email, $uname, $pwd);
						//----------------------------------------	
						$this->regok($msg);
					}else{
						$this->error('数据写入错误！');
					}
				}else{
					$this->error($user->getError());
				}
	}

//ajax保存添加用户
	public function insert_ajax(){
		$email = trim($_POST['email']);
		$uname = $_POST['username'];
		$pwd    = $_POST['pwd'];
		$verify = $_POST['checkcode'];
		if(md5($verify) != $_SESSION['verify']){
				ajaxMsg('验证码错误！', 'error');
				return;
		}
		$user = D('User');
		$vo = $user->create();
		if(false !== $vo) {
			ajaxMsg( $user->getError() , 'error');
			return;
		}
					$vo['email']   = $email;
					$vo['username']= $uname;
					$vo['realname']= $uname;
					$vo['pwd']= md5($pwd);
					$vo['role_id'] = $_POST['groupid'];
					$vo['reg_time'] = time();
					$vo['sex'] = $_POST['sex'];

					if($id = $user->add($vo)){
						$user->login_record($id);	 //记录最后登录时间和IP
						Session::set('uname',$uname);
						Session::set('pwd',$pwd);
						Cookie::set('uid',$id);
						Cookie::set('userShell',md5($pwd.ShellSuffix));
						Cookie::set('logTime',time());


		//注册成功邮件通知--------------------------
		$msg = $this->sendRegMail($email, $uname, $pwd);
		//----------------------------------------	
		//$this->regok($msg);
		ajaxMsg('注册成功！您已经自动登录', $status='success', $title='', $data= $msg );
		return;
					}else{
						ajaxMsg('注册信息写入失败！', 'error');
						return;
					}
	}


//发送注册邮件
	public function sendRegMail($email, $uname, $pwd){
		//注册成功邮件通知--------------------------
		$msg = '系统未启用注册邮件通知！';
		if(C('cfg_reg_sendmail') === true){
				$title	=	"恭喜您！已注册成为0791hunqing.Com用户！";
				$body	=	"欢迎您，成为南昌婚庆网0791hunqing.Com用户！<br>";
				$body	.=	"登录账号：".$email."<br>";
				$body	.=	"用户名：".$uname."<br>";
				$body	.=	"密&nbsp;&nbsp;码：".$pwd."<br>";
				$body	.=	" &nbsp; ——南昌婚庆网小罗";
				try {
					   if(sendemail ($email, $title, $body)){
							$msg = '邮件状态:注册成功通知邮件已发送至您的邮箱！';
						}else{
							 throw new Exception('邮件发送失败！');
						}
				} catch (Exception $e) {
						$msg =  '邮件状态: '.$e->getMessage();
				}
		}
		//----------------------------------------	
		return $msg;
	}

}
?>