<?php 
class TousuAction extends GlobalAction
{

    public function index() //complain list
    {
        // dump($_POST);exit;
        if(isset($_POST['title'])){
            $field = trim($_POST['field']);
            $u_name= trim($_POST['title']);
            $where = $field." like '%".$u_name."%'";
        }else{
            $where='';
        }
        // dump($where);exit;
        $Brand = D("Admin"); 
        $field = '*'; // 如果是查询的视图,必须写明所查列
        $count= $Brand->where($where)->count();
        import("ORG.Util.Page"); //导入分页类 
        $listRows = 20; 
        $p= new Page($count,$listRows); 
 
        $list=$Brand->field($field)->where($where)->order("id asc")->limit($p->firstRow.', '.$p->listRows)
            ->select(); 
        $page=$p->show();
        foreach ($list as $key => $value) {
            $list[$key]['role_id_cn'] = $this->role_arr[$value['role_id']];
        }
        if($list){
            $this->assign('list',$list); 
        }
        // dump($list);exit;
        $this->assign('page',$page); 
        $this->display();
    }

    public function log()
    {
        // dump($_POST);exit;
        if(isset($_POST['title'])){
            $field = trim($_POST['field']);
            $u_name= trim($_POST['title']);
            $where = $field." like '%".$u_name."%'";
        }else{
            $where='';
        }
        // dump($where);exit;
        $Brand = D("Admin_log"); 
        $field = '*'; // 如果是查询的视图,必须写明所查列
        $count= $Brand->where($where)->count();
        import("ORG.Util.Page"); //导入分页类 
        $listRows = 20; 
        $p= new Page($count,$listRows); 
 
        $list=$Brand->field($field)->where($where)->order("id asc")->limit($p->firstRow.', '.$p->listRows)
            ->select(); 
        $page=$p->show();

        $AdminModel = M("Admin");
        foreach ($list as $key => $value) {
            $whr['id'] = $value['admin_id'];
            $admin = $AdminModel->where($whr)->field('role_id')->find();
            // dump($admin);
            $list[$key]['role_id_cn'] = $this->role_arr[$admin['role_id']];
        }
        if($list){
            $this->assign('list',$list); 
        }
        // dump($list);exit;
        $this->assign('page',$page); 
        $this->display();
    }

    //增加管理员
    public function add()
    {
        $this->display();
    }

    //管理员信息入库
    public function insert()
    {
        $admin=D("Admin");
        $username =trim($_POST['adminname']);
        $password = $_POST['pwd'];
        $types = $_POST['types'];
        // dump($_POST);exit;
        if(empty($username))
        {
            $this->error('请输入管理员账号');
        }
        if(empty($password))
        {
            $this->error('管理员密码不能为空');
        }
        $map['admin'] = $username;
        if($admin->where($map)->count()>=1)
        {
            $this->error('该用户已经存在，请重新选择用户名！');
        }
        $vo = $admin->create();
        if(false === $vo) {
            $this->error($admin->getError());
        }       
        $vo['admin']= $username;
        $vo['pwd']  = md5($password);
        $vo['types']= $types;
        $vo['add_time'] = time();
        $id = $admin->add($vo);
        $this->assign('waitSecond',3);  
        $this->assign('jumpUrl',__URL__.'/index');
        if($id) { //保存成功
            $this->success('管理员添加成功！');
        }else { 
            $this->error('管理员添加失败！');
        }
    }

    public function edit()
    {
        $id= $_GET['id'];
        $admin=D("Admin");
        $list=$admin->find($id);
        $this->assign('listone',$list);
        // dump($list);exit;
        $this->assign('id',$id); 
        $this->display('edit_admin');   
    }

    public function update()
    {
        $id=$_POST['id'];
        $password = trim($_POST['pwd']);
        $types = $_POST['types'];
        $admin=D("Admin");
        $vo = $admin->create();
        if(false === $vo) {
            $this->error($admin->getError());
        }
        if(empty($password)){
            $vo['pwd']  = trim($_POST['old_pwd']);
        }else{
            $vo['pwd']  = md5($password);
        }
        $vo['role_id']= $types;
        // dump($vo);exit;
        $result=$admin->where('id='.$id)->save($vo);
        $this->assign('waitSecond',3);  
        if($result){
            $this->assign('jumpUrl',__URL__.'/index');
            $this->success('管理员信息修改成功！');
        }else{
            $this->error($admin->getError());
        }
    }

    //delete
    public function Del()
    {
        $id=$_GET['id'];
        if(empty($id)){
            $this->error('删除项不存在');
        }
        $class=D("Admin");
        $map['id'] = $id;
        $admin_arr = $class->where($map)->field('admin')->find();

        // dump($admin_arr);exit;
        $result=$class->where($map)->delete();
        $this->assign('jumpUrl',__URL__.'/index');
        if(false!==$result)
        {
            $this->success($admin_arr['admin'] . ' 删除成功!');
        }
        else
        {
            $this->error($admin_arr['admin'] . ' 删除失败!');
        }
    }

    //全选删除
    function Delall()
    {
        // dump($_POST);
        if(empty($_POST['key'])){
            $this->ajaxReturn('','您未选中任何项！',0);
        }
        $Form=D("Admin");
        foreach($_POST['key'] as $id) //循环删除
        {
        $result=$Form->deleteById($id);
        }
       $this->ajaxReturn('','所选管理员删除成功',1);
    }

    public function EditPwd() //修改密码
    {
        $id= $_SESSION[C('USER_AUTH_KEY')];
        $admin=D("Admin");
        $list=$admin->find($id,'admin,id');
        $this->assign('listone',$list);
        $this->display('./Tpl/default/Admin/AdminUser/edit_pwd.html');  
    }

    public function update_pwd()  //保存修改
    {
        $id=$_POST['id'];
        $password = $_POST['pwd'];
        if(empty($password))
        {
            $this->error('管理员密码不能为空');
        }
        if($password != $_POST['pwd2']){
            $this->error('两次输入密码的不一样!');
        }
        $admin=D("Admin");
        $vo = $admin->create();
        if(false === $vo) {
            $this->error($admin->getError());
        }
        $vo['pwd']  = substr(md5($password),8,16);
        $result=$admin->save($vo,'id='.$id);
        $this->assign('waitSecond',3);  
        if($result){
            $this->assign('jumpUrl',__URL__.'/adminManage');
            $this->success('恭喜您，密码修改成功！');
        }else{
            $this->error($admin->getError());
        }
    }
}
?>