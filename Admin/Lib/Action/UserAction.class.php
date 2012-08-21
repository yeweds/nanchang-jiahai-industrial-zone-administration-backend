<?php
class UserAction extends GlobalAction{

    //列表,查询方法
    public function index()
    {
        // dump($_POST);exit;
        if(isset($_POST['title'])){
            $field = trim($_POST['field']);
            $u_name= trim($_POST['title']);
            $where = $field." like '%".$u_name."%'";
        }else{
            $where='';
        }
        // 点击标题排序,利用session正序或倒序
        if(isset($_GET['sort']) && isset($_GET['id'])){
            $sort = $_GET['id']." ".$_GET['sort'];
            Session::set('sort',$_GET['sort']);
        }else{
            $sort = "id DESC";
        }
        $Form=D("User");
        $count = $Form->where($where)->field('id')->count(); 
        //每页显示的行数
        $listRows = '20';
        //需要查询哪些字段
        $fields = '*';
        import("ORG.Util.Page");
        $p = new Page($count,$listRows);
        $list = $Form->where($where)->field($fields)->limit($p->firstRow.', '.$p->listRows)->order($sort)->findall();

        //登陆IP - begin
        $dao = D("LoginRecord");
        if($list){
            foreach($list as $k=>$v){   
            $map['uid'] =   $v['id'];
            $pLoginRecord = $dao->where($map)->order("login_time DESC")->limit(1)->findAll();
            $thisLoginInfo = $pLoginRecord[0];    //本次登陆
            $list[$k]['last_ip'] = $thisLoginInfo['login_ip']; //生成地区名
            $list[$k]['last_time'] = $thisLoginInfo['login_time']; //生成地区名
            }
        }
        //登陆IP - end

        $page = $p->show();
        //模板输出
        $this->assign('list',$list);
        $this->assign('page',$page);
        Cookie::set( '_currentUrl_', __SELF__ ); //用于返回
        $this->display();
    }

    //  单选删除
    public function Del()
    {
        $id=$_GET['id'];
        if(empty($id))
            $this->error('删除项不存在');
        $user=D("User");
        $result=$user->where('id='.$id)->delete();
        if(false!==$result)
        {
            $this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
            $this->success('数据删除成功!');
        }
        else
        {
            $this->error('删除出错!');
        }
    }
    
    //  锁定用户及删除其名下所有信息
    public function DelUserAndInfo()
    {
        $id=$_GET['id'];
        if(empty($id)){
            $this->error('该用户不存在');
        }
        $this->assign('waitSecond',8);
        $this->assign('jumpUrl',__APP__.'/InfoList'); 
        $user=D("User");
        $result= $user->setField('groupid',0,'id='.$id,false); //锁定用户
        if(false!==$result)
        {
            $Form = D('Info');
            $where['user_id']= $id;
            $allInfo = $Form->findAll($where,'id');
            foreach ($allInfo as $did){
                DeleteInfoById($did['id']);
            }
            $this->success('该用户已锁定其名下所有信息删除成功!');
        }
        else
        {
            $this->error('用户表中数据删除出错!');
        }
    }

    //批量删除
    public function DelAll() 
    {
        if(empty($_GET['str'])){
            $this->ajaxReturn('','您未选中任何项！',0);
        }
        $Form=D("User");
        $del_id = $_GET['str'];
        $where['id']=array('in',$del_id);  //删除条件
        $result=$Form->where($where)->delete();
        if($result){
            $this->ajaxReturn('','所选用户删除成功',1);
        }else{
            $this->ajaxReturn('','批量删除操作有误',0);
        }
    }

    //添加用户页面
    public function add()
    {
        $this->display('add_user');
    }

    //添加用户入库
    public function Insert()
    {
        $uname = trim($_POST['uname']);
        $pwd   = trim($_POST['u_pwd']);
        if( empty($pwd) || empty($uname) ){
                $this->error('用户名和密码不能为空！');    
        }
        $Form=D("User");
        if($vo = $Form->create()) {
            $vo['reg_time'] = time();
            $vo['username']= $uname;
            $vo['realname']= $uname;
            $vo['pwd']= md5($pwd);
            $vo['role_id'] = $_POST['groupid'];

            if($Form->add($vo)){
            $this->assign('jumpUrl',__URL__);
            $this->success('用户添加成功！');
            }else{
                $this->error('修改用户出错！');
            }
        }else{
            $this->error($Form->getError());
        }
    }

    //  初始化编辑页面
    public function edit()
    {
        $id=$_GET['id'];
        $Form=D("User");
        $user=$Form->find($id);

        $dao = D("LoginRecord");
        $map['uid'] =   $user['id'];
        $pLoginRecord = $dao->where($map)->order("login_time DESC")->limit(2)->findAll();
        
        $thisLoginInfo = $pLoginRecord[0];    //本次登陆

        $user['last_ip'] = $thisLoginInfo['login_ip']; //生成地区名
        $user['last_time'] = $thisLoginInfo['login_time']; //生成地区名

        $this->assign('user',$user);
        $this->display('edit_user');
    }

    //  修改用户资料
    public function update()
    {
        $Form=D("User");
        $id = intval($_POST['uid']);
        $pwd   = trim($_POST['u_pwd']);
        if($vo = $Form->create()) {
          
           if(!empty($pwd)){
                $vo['pwd']= md5($pwd);
           }
           $vo['role_id'] = $_POST['groupid'];
           $rs = $Form->where('id='.$id)->save($vo);

           if($rs !== False){

                $this->assign('jumpUrl', Cookie::get ( '_currentUrl_' ) );
                $this->success("修改用户成功");
           
            }else{
                $this->error('修改用户出错！');
            }
        }else{
            $this->error($Form->getError());
        }
    }
}
?>