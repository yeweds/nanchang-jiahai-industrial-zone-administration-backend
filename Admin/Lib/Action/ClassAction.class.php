<?php
/**
 * Class  栏目管理类
 * @author 熊彦 <cnxiongyan@gmail.com>
 */
class ClassAction extends GlobalAction{
    //---分类列表
    public function index() 
    { 
        $Brand = D("Class"); 
        $field = '*'; // 如果是查询的视图,这里必须写清楚查那些列
        if(isset($_GET['b_classid']))
        {
            $b_classid = $_GET['b_classid'];
            $where['pid'] = $b_classid;
        }else{
            $b_classid = 0;
            $where['pid'] = $b_classid;
        }
        //获取上级节点
        $vo = $Brand->getById($b_classid);
        if($vo) {
            $this->assign('classlevel',$vo['level']+1);
            $this->assign('classname',$vo['name']);
        }else {
            $this->assign('classlevel',1);
        }
        $count= $Brand->where($where)->count(); 
 
        import("ORG.Util.Page"); //导入分页类 
        if(!empty($_REQUEST['listRows'])) { 
            $listRows = $_REQUEST['listRows']; 
        }else{ 
            $listRows=20;
        }
        $p= new Page($count,$listRows); 
 
        $list=$Brand->where($where)->field($field)->order('sortrank asc')->limit($p->firstRow.','.$p->listRows)->findAll(); 
        $page=$p->show();
        if($list){
            $this->assign('list',$list); 
        }
        $this->assign('page',$page); 
        Cookie::set ( '_currentUrl_', __SELF__ ); //用于返回
        $this->display();
    }


    //---打开增加分类页面
    public  function add() 
    {
        if(isset($_GET['classid'])){    //子类
            $class_id = $_GET['classid'];
            $vo       = $this->getOneClass($class_id);
            $level    = $vo['level'] + 1 ;
        }else{
            $level    =  1 ;   //顶级分类
            $vo['name'] = "顶级分类";
        }
        $this->assign('class_id',$class_id);
        $this->assign('classname', $vo['name']);
        $this->assign('level', $level);
        $this->display('add_class');
    }

    //---添加入库
    public function insert()
    {
        // dump($_POST);exit;
        $classname = trim($_POST['name']);
        if(empty($classname))  $this->error('请输入分类名称');
        
        if(isset($_POST['class_id'])){    //子类
            $class_id = $_POST['class_id'];
            $vo       = $this->getOneClass($class_id);
            $level    = $vo['level']+1;
            $pid      = $class_id;
        }else{                            //大类
            $level = 1;
            $pid   = 0;
        }
        // dump($vo);exit;
        $Table = D("Class"); //查分类表
        $data = $Table->create();
        $data['name'] = $classname;
        $data['pid']  = $pid;
        $data['level']= $level;
        if(!empty($_POST['isnav'])){//是否导航 黄妃添加
            $data['isnav'] = $_POST['isnav']; 
        }

        $this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
        if( $rs = $Table->add($data)){
            unset($data);
            $vo['curr_path'] = isset($vo['curr_path']) ? $vo['curr_path'] : 0;
            $data['curr_path'] = $vo['curr_path'].'>'.$rs ;
            $Table->where('id='.$rs)->save($data);
            $this->success('分类增加成功！');
        }else {
            $this->error('分类增加失败！');
        }
    }

    //---显示编辑分类页面
    public function edit()
    {
        $id     = $_GET['id'];
        $Node   = D("Class"); //查分类表
        $listone= $Node->find($id);
        $this->assign('listone',$listone);
        $this->assign('id',$id);
        $this->assign("level", $listone['level']);
        //---分类树
        $list = $Node->field('id,pid,name')->findall();
        load('extend'); //导入扩展函数
        $tree = list_to_tree($list=$list, $pk='id',$pid = 'pid',$child = '_child');
        //生成无限分类树
        $this->assign("list", $tree);

        $this->display('edit_class');
    }

    public function update()
    {
        $id        = $_GET["id"];
        $classname = $_POST['classname'];
        if(empty($classname)){
            $this->error('请输入分类名称');    
        }
        $class=D("Class");
        $vo = $this->getOneClass($id);
        if(empty($_POST["class_id"])){
            $parent_id   = 0 ;
            $curr_path = $id;
        }else{
            $parent_id   = $_POST['class_id'];  //所属大类
            $parent      = $class->field('id,curr_path,level')->where('id='.$parent_id)->find();
            $curr_path = $parent['curr_path'].','.$id;
        }
            $data = $class->create();
            if(isset($_POST['isnav'])){//是否导航 黄妃添加
                $data['isnav'] = $_POST['isnav']; 
                //echo $_POST['isnav'];
            }
            $data['name']= $classname;
            $data['pid'] = $parent_id;
            $data['curr_path'] = $curr_path ;
            $data['level'] = $parent['level'] + 1 ;
            $map['id']     = $id;
            $result=$class->where($map)->save($data);  //更新id=$id的记录
            //echo $class->getLastSql();
            $this->assign('waitSecond',3);
            if(false!==$result){
                $this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
                $this->success('分类更新成功!');
            }else{
                $this->error('分类更新失败!');
            }
    }
    
    //---删除单个分类
    public function Del()
    {
        $id = $_GET['id'];
        if(empty($id))
            $this->error('删除项不存在!');
        // if( $id < 5 || $id == 15 )
        //     $this->error('系统内置分类，不得删除!');
        $class  = D("Class");
        $result = $class->where('id='.$id)->delete();
        if(false !== $result){
            $condition['pid']=$id;
            $class->where($condition)->delete(); //删除所有子类
            $this->success('分类删除成功!');
        }else{
            $this->error('分类删除出错!');
        }
    }
    
    //---全选删除
    public function delAll()
    {
        if(empty($_GET['str'])){
            $this->ajaxReturn('','您未选中任何项！',0);
        }
        $Form = D("Class");
        $del_id = $_GET['str'];
        $where['id'] = array('in',$del_id);  //删除条件
        $result=$Form->where($where)->delete();
        if($result){
            $this->ajaxReturn('','所选信息删除成功',1);
        }else{
            $this->ajaxReturn('','批量删除操作有误',0);
        }
    }
    
    //---修改排序 
    public function upsort()
    {
        $Form=D("Class");
        $key = $_GET['str'];
        $sortrank = $_POST['sortrank'];
        $num=count($key);
        //die($num);
        for($i=0; $i<$num; $i++) {   //循环修改
            $id=$key[$i];
            if(empty($id))
                $this->error('修改项不存在');
            if($vo = $Form->create()) {
               $vo['sortrank']=$sortrank[$i];
                if($Form->where('id='.$id)->save($vo)){ 
                }else{
                    $this->error('数据写入错误！');
                }
            }
        }       
       $this->ajaxReturn('','排序修改成功',1);    
    }
    
    //---获取一个分类信息 [ 参数：class_id ]
    public function getOneClass($class_id){
        $condition['id']   =   $class_id;
        $vo  =  D("Class")->where($condition)->field('id,name,level,curr_path')->find();
        return $vo;
    }
}