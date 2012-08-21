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
    
    //显示登录界面
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
        header("Content-type:text/html;charset=utf-8");
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
            //记录每次登录时间及IP
            include_once THINK_PATH.'/Common/extend.php'; //导入扩展函数
            $vo['last_login_ip']   = get_client_ip();
            $vo['last_login_time'] = time();

            //insert admin log record
            $log['admin_id'] = $user['id'];
            $log['admin_name'] = $username;
            $whr['id'] = $user['id'];
            $realname = $Member->field('realname')->where($whr)->find();
            // dump($realname);exit;
            $log['admin_realname'] = $realname['realname'];
            $log['action'] = "登进";
            $log['login_ip']   = get_client_ip();
            $log['time'] = time();

            // dump($log);exit;
            M("Admin_log")->add($log);
            //end insert admin log record 
            
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
            header("Content-type:text/html;charset=utf-8");
            include_once THINK_PATH.'/Common/extend.php'; //导入扩展函数
            $log['admin_id'] = $this->sid;
            $whr['id'] = $this->sid;
            $admin = M("Admin")->field('admin, realname')->where($whr)->find();
            // dump($admin);exit;
            $log['admin_name'] = $admin['admin'];
            // dump($realname);exit;
            $log['admin_realname'] = $admin['realname'];
            $log['action'] = "登出";
            $log['login_ip']   = get_client_ip();
            $log['time'] = time();

            // dump($log);exit;
            M("Admin_log")->add($log);
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