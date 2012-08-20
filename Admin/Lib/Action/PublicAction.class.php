<?php
// 后台登录及相关
class PublicAction extends Action{
    private $sid;
    private $groupid;
    function _initialize()
    {
        $this->sid     = Cookie::get('ht_'.C('cfg_auth_key'));
        $this->groupid = Cookie::get('ht_groupid');
    }
    
    //登录界面
    public function login()
    {
        if(!isset($this->sid) || $this->groupid=='')
        {
            $this->display('login');
        }else{
            header("Content-Type:text/html; charset=utf-8");
            $this->redirect("../admin.php/Index/index",'',1,'您已经登录~');
        }
    }

    //检验登录  
    public function checkLogin()
    {
        $Member = D('Admin');
        $username=trim($_POST['username']);
        $password=trim($_POST['password']);
        $seccode =trim($_POST['seccode']);
        $login_type = $_GET['type'];

        if(empty($username))
        {
            $this->error('用户名不能为空!!!');
        }elseif($password==''){
            $this->error('密码不能为空!!!');
        }elseif(md5($seccode)!=Session::get('verify')){
            $this->error('验证码错误!!!');
        }
        $map=array();
        $map["admin"]=$username;
        $user=$Member->where($map)->find();
        //dump($user);
        if(false===$user)
        {
            $this->error('该用户名不存在!!!');
        }else{

            if($user['pwd']!= md5($password) )
            {
                $this->error('密码错误，请重新输入!');
            }
            //if($user['is_lock']==1)$this->error('用户被锁定');
            //修改管理员最后登录时间及IP
            include_once THINK_PATH.'/Common/extend.php'; //导入扩展函数
            $vo['last_login_ip']   = get_client_ip();
            $vo['last_login_time'] = time();
            // dump($vo);exit;
            $where['id'] = $user['id'];
            $Member->where($where)->save($vo);

            Cookie::set('ht_'.C('cfg_auth_key'), $user['id'], 3600*24*30 );
            Cookie::set('ht_admin',$user['admin'], 3600*24*30 );
            Cookie::set('ht_groupid',$user['role_id'], 3600*24*30 );

            $this->assign('waitSecond',10); 
            $this->assign('jumpUrl',__APP__.'/Index/index');

            //echo "<script>window.parent.location.href='".__APP__."/Index/index'</script>";
            $this->success('登陆成功');
        }
    }

    //普通验证码显示
    public function verify(){
        import('ORG.Util.Image');  //导入图像类
        if(isset($_REQUEST['adv'])){
            Image::showAdvVerify();
        }else{
            Image::buildImageVerify();
        }
    }
    
    //汉字验证码显示
    function zh_verify(){
        import('ORG.Util.Image');
        Image::GBVerify();
    }

    public function authorizationstop() 
    {
        $this->display();
    }

    //---标注地图
    public function mapMark() 
    {
        $this->display();
    }

    //安全退出
    public function logout() 
    {
        $jUrl = __URL__.'/login/';

        if(isset($this->sid)) {
            //Session::destroy();
            Cookie::delete('ht_'.C('cfg_auth_key'));
            Cookie::delete('ht_admin');
            Cookie::delete('ht_groupid');
            $this->assign("jumpUrl", $jUrl);
            $this->success('您已成功退出!!!');
        }else {
            $this->assign('error', '已经退出！');
            $this->redirect($jUrl, ''); 
        }
    }
}
?>