<?php
/**
 * Yqjs_recycler  园区介绍回收站管理类
 * @author 熊彦
 */
class Yqjs_recyclerAction extends GlobalAction
{
    private $yqjs_pid = 39;  //园区介绍大类ID
    public function index()
    {
        $Brand = M("Yqjs_recycler"); 
        $Class = M('Class');
        $field = '*';  // 如果查视图,须写明所查列
        $where = array();
        if(isset($_GET['class_id'])){
            $where['class_id'] = $_GET['class_id'];
            $typeid = $_GET['class_id'];
            $typename = $Class->field('name')->where('id='.$typeid)->find();
            $this->assign('typename',$typename['name']);
        }
        if(isset($_GET['ispublish'])){
            $where['ispublish'] = $_GET['ispublish'];
        }
        //所有园区介绍分类
        
        $map['pid'] = $this->yqjs_pid; //Yqjs 大类id
        $list_class = $Class->where($map)->select();

        $fix = C("DB_PREFIX");
        $table = $fix."yqjs_recycler"; 
        $table2 = $fix."class"; 
        $table3 = $fix."user"; 
        $count= $Brand->where($where)->join("$table2 on $table.class_id=$table2.id")->count();
        
        import("ORG.Util.Page"); //导入分页类 
        $listRows = 20;
        $p = new Page($count,$listRows); 
        $list=$Brand->where($where)->join("$table2 on $table.class_id=$table2.id")->join("$table3 on $table.user_id=$table3.id")->field("$table.* ,$table2.name,$table3.username")->limit($p->firstRow.','.$p->listRows)
              ->order("$table.id desc")->select(); 
        //echo $Brand->getLastSql();
        $page=$p->show();
        if($list){
            $index = 0 ;
            foreach($list as $vo){
                $arr[$index]['id'] = $vo['id'];
                $arr[$index]['class_id'] = $vo['class_id'];
                if(strlen($vo['title'])>10){
                    $arr[$index]['title'] = mb_substr($vo['title'],0,10,'utf8').'..';
                }else{
                    $arr[$index]['title'] = $vo['remark'];
                }
                $arr[$index]['name'] = $vo['name'];
                if(strlen($vo['remark'])>10){
                    $arr[$index]['remark'] = mb_substr($vo['remark'],0,10,'utf8').'..';
                }else{
                    $arr[$index]['remark'] = $vo['remark'];
                }
                $arr[$index]['add_time'] = $vo['add_time'];
                $arr[$index]['ispublish'] = $vo['ispublish'];
                // $arr[$index]['username'] = $vo['username'];
                $index++;
            }
            $this->assign('list',$arr); 
            // dump($list);exit;
        }
        $this->assign('page',$page); 
        $this->assign('typeid',$typeid); 
        $this->assign("list_class",$list_class);
        Cookie::set ( '_currentUrl_', __SELF__ ); //用于返回
        $this->display();
    }

    //回收站还原到园区介绍表
    public function edit()
    {
        if(!is_numeric($_GET['id'])) {
             $this->error('编辑项不存在');
        }
        $id = $_GET['id'];
        $Model = M('Yqjs');
        $yqjs_clear = M('Yqjs_recycler');
        $data = $yqjs_clear->field("*")->where("id=".$id)->find();
        //dump($data);
        $result = $Model->add($data);  //添加还原
        if($result>0){
            $result = $yqjs_clear->execute(" delete from __TABLE__ where id=".$id) ;
            if($result>0){
                $this->success("园区介绍还原成功!");
            }else{
                $this->success("园区介绍还原成功!但是回收中的记录删除失败");
            }
        }else{
            $this->success("园区介绍还原失败!");
        }
    }

    //回收站园区介绍永久删除
    function del()
    {
        if(!is_numeric($_GET['id'])){
            $this->error('删除项不存在');
        }
        $yqjs = D("Yqjs_recycler");
        $map['id'] = $_GET['id'];
        $result = $this->del_attach($_GET['id']); //先删图片或其它附件
        if(true==$result){
            $rs = $yqjs->where($map)->delete();
            $this->assign ( 'jumpUrl', __URL__);
            if($rs)
            {
                $this->success('删除成功!');
            }else{
                $this->error('删除失败!');
            }
        }else{
            $this->error('删除图片失败!没有删除');
        }
    }

    //---从园区介绍回收站删除多条园区介绍---
    function delall()
    {
        if(empty($_GET['str'])){
            $this->ajaxReturn('','您未选中任何项！',0);
        }
        $list = explode(',',$_GET['str']);
        // $this->ajaxReturn('', $list, 0);exit;
        $yqjsRecyclerModel = M('Yqjs_recycler');
        foreach($list as $id) //循环删除
        {
            //$result=$Form->deleteById($id);
            unset($map);
            $map['id'] = $id;
            $result = $yqjsRecyclerModel->where($map)->delete();
            if($result){
                $arr .= $id . ',';
            }
        }
        // $exit;
        $this->ajaxReturn('',$arr.'从园区介绍回收站删除成功',1);
    }
    
    //删除附属文件    
    protected function del_attach($id)
    {
        $map['id'] = $id ;
        if($rs = M('Yqjs')->where($map)->find()){
            if(empty($rs['pic_url'])){
                return true;
            }
            $file = './Public/Upload/Yqjs/'.$rs['pic_url'];
            $thumb_file = './Public/Upload/Yqjs/thumb_'.$rs['pic_url'];
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