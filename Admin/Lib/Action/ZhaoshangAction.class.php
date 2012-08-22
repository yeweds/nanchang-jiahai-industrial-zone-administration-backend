<?php
/**
 * Zhaoshang  后台招商管理类
 * @author 熊彦
 */
class ZhaoshangAction extends GlobalAction{
    private $zhaoshang_pid = 42;  //招商大类ID

    public function index()
    {
        $zhaoshangModel = M("Zhaoshang"); 
        $Class = D('Class');
        $field = '*';  // 如果查视图,须写明所查列
        $fix = C("DB_PREFIX"); //前缀
        
        if(isset($_POST['title'])){
            $field = trim($_POST['field']);
            $in_key= trim($_POST['title']);
            if($field == 'title'){
                $where[$field] = array('like', "%".$in_key."%" );
            }else{
                $where[$fix.'Zhaoshang.'.$field] = array('eq', $in_key );
            }
        }else{
            $where = array();
        }
        if(isset($_GET['class_id'])){
            $class_id = intval($_GET['class_id']);
            $where['class_id'] = $class_id;
            $typeid = $class_id;
            $typename = $Class->field('name')->where('id='.$typeid)->find();
            $this->assign('typename',$typename['name']);
        }
        if(isset($_GET['ispublish'])){
            $where['h_Zhaoshang.ispublish'] = $_GET['ispublish'];
        }
        //所有招商分类
        // dump($where);exit;

        $map['pid'] = $this->Zhaoshang_pid; //Zhaoshang 大类id
        $list_class = $Class->where($map)->select();
        
        $table = $fix."Zhaoshang"; 
        $table2 = $fix."class"; 
        $table3 = $fix."user"; 
        $count= $zhaoshangModel->where($where)->join("$table2 on $table.class_id=$table2.id")->count();
        // dump($count);exit;
        import("ORG.Util.Page"); //导入分页类 
        $listRows = 20;
        $p = new Page($count,$listRows); 
        $list = $zhaoshangModel->where($where)->join("$table2 on $table.class_id=$table2.id")->join("$table3 on $table.user_id=$table3.id")->field("$table.* ,$table2.name,$table3.username")->limit($p->firstRow.','.$p->listRows)
              ->order("$table.add_time desc,$table.id desc")->select(); 
        // echo $zhaoshangModel->getLastSql();exit;
        // dump($list);exit;
        $page=$p->show();
        if($list){
            foreach($list as $k=>$vo){
                if(strlen($vo['title'])>40){
                    $list[$k]['title'] = mb_substr($vo['title'],0,38,'utf8').'..';
                }else{
                    $list[$k]['title'] = $vo['title'];
                }
            }
            $this->assign('list',$list); 
        }
        $this->assign('page',$page); 
        $this->assign('typeid',$typeid); 
        $this->assign("list_class",$list_class);
        Cookie::set('_currentUrl_', __SELF__ ); //用于返回
        $this->display();
    }

    //添加招商
    public function add()
    {
        $uid   =  $this->getUid();  //来源于Global中
        $username =  $this->getName();
        $map_admin['id'] = $uid;
        $admin = M('Admin')->where($map_admin)->find();
        $this->assign("realname",$admin['realname']); //真实姓名

        //所有招商分类
        $class = D('Class');
        $map=null;
        $map['pid'] = $this->zhaoshang_pid;
        $list_class = $class->field('id,pid,name')->where($map)->select();
        // dump($list_class);exit;
        // echo $class->getLastSql();exit;
        load('extend'); //导入扩展函数
        $tree = list_to_tree($list_class, $pk='id',$pid = 'pid', $child = '_child', $this->zhaoshang_pid );
        $this->assign("list_class",$tree);  //生成无限分类树
        // dump($list_class);exit;
        // dump($tree);exit;

        //关联商家
        // $loupan_list = M('Shop')->field('id,name,pid,head_py')->order('head_py asc')->select();
        // //echo $class->getLastSql();
        // $this->assign("loupan_list",$loupan_list);
        $this->assign("user_id",$uid);
        $this->assign("username",$username);
        $this->display('add');
    }

    //招商入库
    public function Insert()
    {
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        if(empty($title) || empty($content) ){
            $this->assign("jumpUrl",__URL__);
            $this->error("标题和内容不能为空");
        }
        $zhaoshang =  D('Zhaoshang');
        $data = $zhaoshang->create();
        // dump($data);exit;
        if($data){
            $map['title'] = $title;
            $rs = $zhaoshang->where($map)->find();
            if($rs){ //判断招商是否存在
                $this->assign("jumpUrl",__URL__);
                $this->error("该招商已经存在");
            }
            //dump( A('Keywords')->getKeywordedStr($content) );
            $data['title']            = $title;
            $data['remark']           = trim($data['remark']);
            //$data['content']        = $content;  //转换换行、空格
            $data['content']          = A('Keywords')->getKeywordedStr($content);  //关键字加链接
            $data['add_time']         = time();
            $data['isshow_sub_title'] = (isset($_POST['isshow_sub_title']) && $_POST['isshow_sub_title']==1 ? 1 : 0); //是否显示副标
            $fileinfo = $this->_upload();
            if(count($fileinfo)>0){
                $data['pic_url'] =  $fileinfo[0]['savename'];      
            }
            $flags = $_POST['flag'];
            if(count($flags>0)){
                foreach($flags as $f){
                    $flag .= $f.',';
                }
                $data['flag'] = rtrim($flag,',');
                //dump($data['flag']);
            }
        }else{
            $this->error($zhaoshang->getError());
        }

        if($insert_id = $zhaoshang->add($data)) {
            //--关联商家---
            $arr_info_id = $_POST['info_id'];
            if(count($arr_info_id)>0){
                $zhaoshang_mapping = M('Zhaoshang_mapping');
                foreach($arr_info_id as $v){
                    if($v){
                        $rs = M('Shop')->field('name as title')->where('id='.$v)->find();
                        $data_['lpname'] = (empty($rs['title']) ? '': $rs['title']) ;
                        $data_['info_id']= $v;
                        $data_['Zhaoshang_id']= $insert_id;
                        $zhaoshang_mapping->add($data_);
                    }
                }
            } //--关联商家---end

            $this->assign('waitSecond',3);
            $this->assign("jumpUrl",__URL__);
            $this->success("招商添加成功!");
        }
    }

    //编辑招商
    public function edit()
    {
        if(!is_numeric($_GET['id'])) {
             $this->error('编辑项不存在');
        }
        $zhaoshang = M('Zhaoshang');
        $fix = C("DB_PREFIX");
        $table = $fix."Zhaoshang"; 
        $table2 = $fix."class"; 
        $table3 = $fix."user"; 
        $id = intval($_GET["id"]);
        $map['h_Zhaoshang.id'] = $id;
        $editVo=$zhaoshang->where($map)->join("$table2 on $table.class_id=$table2.id")->join("$table3 on $table.user_id=$table3.id")->field("$table.* ,$table2.name,$table3.username")->find();
        unset($map);
        $editVo['content'] = htmlspecialchars($editVo['content']);
        $typename = $editVo['name'] ;
        $info_id = $editVo['info_id'] ; //关联的楼盘
        $this->assign("info_id",$info_id);
        $this->assign("typename",$typename);
        $this->assign("vo",$editVo);

        //自定义属性
        $flag = $editVo['flag'] ;
        if(strstr($flag,'h')){
            $h = 'h';
            $this->assign("h",$h);
        }
        if(strstr($flag,'c')){
            $c = 'c';
            $this->assign("c",$c);
        }
        if(strstr($flag,'f')){
            $f = 'f';
            $this->assign("f",$f);
        }
        if(strstr($flag,'a')){
            $a = 'a';
            $this->assign("a",$a);
        }
        if(strstr($flag,'s')){
            $this->assign("s",'s');
        }
        if(strstr($flag,'b')){
            $this->assign("b",'b');
        }
        if(strstr($flag,'p')){
            $this->assign("p",'p');
        }
        //所有招商分类
        $map = null;
        //$map['pid'] = $this->Zhaoshang_pid;
        $list_class = M("Class")->field('id,pid,name')->where($map)->select();
        load('extend'); //导入扩展函数
        $tree = list_to_tree($list_class, $pk='id',$pid = 'pid', $child = '_child', $this->Zhaoshang_pid );
        $this->assign("list_class",$tree);

        //关联商家
        $loupan_list = D('Shop')->field('id,name,head_py')->order('head_py asc')->select();
        $this->assign("loupan_list",$loupan_list);
        //当前已关联---start
        $map_mp['Zhaoshang_id'] = $id;
        $zhaoshang_mapping_list = M('Zhaoshang_mapping')->where($map_mp)->select();
        if($zhaoshang_mapping_list){
            foreach($zhaoshang_mapping_list as $v){
                $info_id_arr[] = $v['info_id'];
            }
        }

        //dump($editVo);
        $this->assign("info_id_arr",$info_id_arr);
        $this->assign("Zhaoshang_mapping_list",$zhaoshang_mapping_list);
        //当前已关联---end
        $this->display();
    }

    //保存编辑后的招商
    public function save()
    {
        $id=intval($_POST['id']);
        if (!$id) $this->error('编辑项不存在');
        //dump($_POST);
        $Pages=D("Zhaoshang");
        if( $data = $Pages->create()) { 
            $fileinfo = $this->_upload();

            $add_time = strtotime($_POST['add_time']) ; //时间转换
            $data['add_time'] = $add_time;
            $data['isshow_sub_title'] = (isset($_POST['isshow_sub_title']) && $_POST['isshow_sub_title']==1 ? 1 : 0); //是否显示副标
            $flags = $_POST['flag'];
            if(count($flags>0)){
                $data['flag'] = implode(',' , $flags);
                //dump($data['flag']);
            }
            if($fileinfo==true){
                 $data['pic_url'] =  $fileinfo[0]['savename'];   
            }else{
                 $data['pic_url'] =  $_POST['pic_url'];
            }
            $data['remark'] = trim($data['remark']);
            $data['content'] = trim($data['content']);
            $data['content'] = A('Keywords')->getKeywordedStr($data['content']);  //关键字加链接
            //dump($data);

            //--关联商家---
            $arr_info_id = $_POST['info_id'];
            //dump($arr_info_id);
            if(count($arr_info_id)>0){
                 $zhaoshang_mapping = M('Zhaoshang_mapping');
                 $map_mp['Zhaoshang_id'] = $id;
                 $zhaoshang_mapping->where($map_mp)->delete(); //先删再加
                 foreach($arr_info_id as $v){
                    if($v){
                         $rs = M('Shop')->field('name as title')->where('id='.$v)->find();
                         $data_['lpname'] = (empty($rs['title']) ? '': $rs['title']) ;
                         $data_['info_id']= $v;
                         $data_['Zhaoshang_id']= $id;
                         $zhaoshang_mapping->add($data_);
                    }
                 }
            }

            $map['id'] = $id ;
            $rs =$Pages->where($map)->save($data);
            $this->assign('waitSecond', 10);

            if($rs!==false){ 
                $this->assign("jumpUrl", Cookie::get('_currentUrl_') );
                $this->success("招商编辑成功!");
            }else{ 
                $this->error("招商编辑失败!"); 
            } 
        }else{ 
            $this->error($Pages->getError()); 
        } 
    }


    //---删除招商---
    function del()
    {
        if(!is_numeric($_GET['id'])){
            $this->error('删除项不存在');
        }
        $id = trim($_GET['id']) ;
        $map['id'] = $_GET['id'];
        $Model = new Model("Zhaoshang");
        //$result = $this->del_attach($_GET['id']); //先删图片或其它附件
        //if(true==$result){
            //$delSql = "select id,title,class_id,user_id,author,remark,content,sortrank,add_time,pic_url,flag,tag,pr,source";
            //$delSql .=",keywords,click,ispublish,info_id,lpname,redirecturl from __TABLE__ where id=".$id;
            $data = $Model->where($map)->find();
            $result = M('Zhaoshang_recycler')->add($data);
            //echo $Model->getlastsql();
            if($result>0){
                $rs = $Model->where($map)->delete();
                $this->assign ('jumpUrl', __URL__);
                if($rs)
                {
                    $this->success('移动到回收站成功!');
                }else{
                    $this->error('移动到回收站失败1!');
                }
            }else{
                $this->error('移动到回收站失败2!');
            }

        //}else{
        //  $this->error('删除图片失败!没有删除');
        //}
    }

    //---删除多条招商---
    function delall()
    {
        if(empty($_GET['str'])){
            $this->ajaxReturn('','您未选中任何项！',0);
        }
        $list = explode(',',$_GET['str']);
        $Model = new Model('Zhaoshang');
        foreach($list as $id) //循环删除
        {
            //$result=$Form->deleteById($id);
            $result = $Model->execute(" insert into h_Zhaoshang_recycler select * from __TABLE__ where id=".$id);
            if($result>0){
                $result = $Model->execute(" delete from __TABLE__ where id=".$id);      
                if($result){
                    $arr.= $id . ',';
                }
            }
        }
        $this->ajaxReturn('',$arr.'删除成功',1);
    }

//---附件上传  xiongyan
    public function _upload(){
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();
        //设置上传文件大小
        $upload->maxSize = 1024000;
        //覆盖之前头像
        $upload->uploadReplace = true;
        //是否多张上传
        $upload->supportMulti = false;
        //设置上传文件类型
        $upload->allowExts = array('jpg','gif','png','jpeg');
        $upload->savePath = './Public/Upload/Zhaoshang/';
        //设置上传文件规则 
        $upload->saveRule = 'uniqid';
        //设置需要生成缩略图，仅对图像文件有效
        $upload->thumb =  true; 
        //设置需要生成缩略图的文件后缀
        $upload->thumbSuffix =  ''; //直接覆盖
        //设置缩略图最大宽度
        $upload->thumbMaxWidth =  228; 
        //设置缩略图最大高度
        $upload->thumbMaxHeight=  196; 

        if($upload->upload()){
                $info = $upload->getUploadFileInfo();
                return $info;
        }else{
             //捕获上传异常
             //$this->error($upload->getErrorMsg()); 
             return false ;
        }
    }

//删除文件  
    public function del_attach($id){
        $map['id'] = $id ;
        if($rs = M('Zhaoshang')->where($map)->find()){
            if(empty($rs['pic_url'])){
                return true;
            }
            $file = './Public/Upload/Zhaoshang/'.$rs['pic_url'];
            $thumb_file = './Public/Upload/Zhaoshang/thumb_'.$rs['pic_url'];
            if(file_exists($file)){
                if(unlink($file) && unlink($thumb_file)){
                    return true;
                }else{
                    return false;
                }
            }   
        }
        return true;
    }

}

?>