<?php
/**
 +------------------------------------------------------------------------------
 * Login  前台登录类
 +------------------------------------------------------------------------------
 * @author 熊彦
 +------------------------------------------------------------------------------
 */
class LoginAction extends GlobalAction{
//显示登录界面
	public function index(){
		if($this->userInfo !== false){
				$this->error('您已经登录,无需再次登入!');
		}
		$this->assign('TitleMsg','用户登录');
		if(isset($_GET['fromUrl'])){
			$this->assign('fromUrl',$_GET['fromUrl']); //登录来源
		}
		$this->display('login');
	}

//检验登陆方法
	public function checkLogin(){
		$email = trim($_POST['email']);
		$pwd = $_POST['password'];
		$this->assign('waitSecond','5');
		//$this->assign('jumpUrl',__URL__.'/index/');
		if(md5($_POST['checkcode']) != $_SESSION['verify']){
			$this->error('验证码错误!');
		}
		if(empty($email)){
			$this->error('请输入您注册时用的邮箱!');
		}
		if(empty($pwd)){	
			$this->error('请输入密码!');
		}
			$user	=	D("User");
			$where['email'] = $email;
			$vo = $user->where($where)->find();
			if(!$vo)	 {
				$this->error('该账户不存在!');
			}
			if( $vo['pwd'] != md5($pwd) ){
				$this->error('密码不正确!');
				exit();
			}
//登陆成功后修改IP - begin
			   $user->login_record($vo['id']);
//登陆成功后修改IP - end
				C('COOKIE_EXPIRE' , 60*60*24*30);  //修改cookie保存时间
				Cookie::set('uid',$vo['id']);
				Cookie::set('userShell',md5($vo['pwd'].ShellSuffix));
				Cookie::set('logTime',time());

		if(isset($_GET['fromUrl']) && $_GET['fromUrl']!=''){
			$jumpUrl = base64_decode($_GET['fromUrl']);
		}else{
			//$jumpUrl = __APP__.'/Account/';
			$jumpUrl = __APP__.'/';   
		}
				$this->assign('jumpUrl',$jumpUrl);
				$this->success('恭喜您,登陆成功!');

	}

	public function forgetPassword(){
//找回密码
		$this->assign('TitleMsg','找回密码');
		$this->display();
	}

	public function sendPassword(){
//发送密码
		$email = trim($_POST['email']);
		$code  = $_POST['checkcode'];
		if(empty($email)){
			$this->error('请输入您注册时使用的邮箱!');
		}
		if(md5($code) != $_SESSION['verify']){
			$this->error('验证码错误!');
		}
			$user	=	D("User");
			$where['email'] = $email;
			$vo = $user->where($where)->find();
			if(!$vo)	 {
				$this->error('该账户不存在!');
			}
//发送密码至Email开始
        $subject	=	C('cfg_sitename')."给您发来的找回密码函";
		$body	=	"恭喜您，成功找回腾房网www.tengfang.net登录账户和密码。请惠存！<br>";
		$body	.=	"账&nbsp;&nbsp;户：".$email."<br>";
		$body	.=	"密&nbsp;&nbsp;码：".$vo['pwd']."<br>";
		$body	.=	" &nbsp; ——腾房小叶";
		try {
			   if(sendemail ($email,$subject,$body)){
					$this->assign('waitSecond',5);
					$this->assign('jumpUrl',__APP__.'/Login/');
					$this->success('密码函发送中~,请去您的邮箱查看密码！');
				}else{
					 throw new Exception('邮件发送失败！');
					$msg = '因为种种原因，您可能没有收到找回密码函！';
				}
		} catch (Exception $e) {
				$msg = '出错原因: '.$e->getMessage();
		}
        
		$this->error($msg);
//发送密码至email	结束
	}


	//Ajax检验登陆方法
	public function checkLoginAjax(){
		$email = trim($_POST['email']);
		$pwd = $_POST['password'];
		$this->assign('waitSecond','5');

		if($_POST['hideVerify'] != 1){   //是否启用验证码
			if(md5($_POST['checkcode']) != $_SESSION['verify']){
				ajaxMsg('验证码错误！', $status='error');
				return;
			}
		}
		if(empty($email)){
			ajaxMsg('请输入您注册时用的邮箱!', $status='error');
			return;
		}
		if(empty($pwd)){
			ajaxMsg('请输入密码!', $status='error');
			return;
		}
			$user	=	D("User");
			$where['email'] = $email;
			$vo = $user->where($where)->find();
			if(!$vo){
				ajaxMsg('该账户不存在!', $status='error');
				return;
			}
			if( $vo['pwd'] != md5($pwd) ){
				ajaxMsg('密码不正确!', $status='error');
				return;
			}
//登陆成功后修改IP - begin
			   $user->login_record($vo['id']);
//登陆成功后修改IP - end
				C('COOKIE_EXPIRE' , 60*60*24*30);  //修改cookie保存时间
				Cookie::set('uid',$vo['id']);
				Cookie::set('userShell',md5($vo['pwd'].ShellSuffix));
				Cookie::set('logTime',time());

				ajaxMsg('恭喜您,登陆成功!', $status='success', '', $vo['username']);

	}

//---ajax检验是否已登录，用于js交互
	public function isLoginAjax(){  
		if($this->userInfo !== false){
			echo "loginSuccess";
		}else{
			echo "notLogin";
		}
 	}

}
?>