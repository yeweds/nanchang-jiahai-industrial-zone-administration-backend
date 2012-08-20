<?php
  //申请咨询管理
  class AdviceAction extends GlobalAction
  {
    public function index(){
        $Article=M('Advice');    
        $listRows="20";
        import("ORG.Util.Page");
        $count=$Article->count();
        $p=new Page($count,$listRows);
        $page=$p->show();

        $list =$Article->order("id desc")->limit($p->firstRow.','.$p->listRows)->findall();
        if($list!==false){
            $Shop = M("Shop");
            foreach($list as $k=>$v){
                $map_['id'] = $v['shop_id'];
                $rs = $Shop->field('name')->where($map_)->find();
                $list[$k]['shop_name'] = $rs['name'];
            }
            $this->assign('list',$list);
            $this->assign('page',$page);
        }
        Cookie::set( '_currentUrl_', __SELF__ ); //用于返回
        $this->display();
    }

//编辑表单
    public function edit()
    {
        if(!is_numeric($_GET['id'])) {
             $this->error('编辑项不存在');
        }
        $id = intval($_GET["id"]);
        $map['id'] = $id;
        $editVo    = M('Advice')->where($map)->find();
        $this->assign("vo",$editVo);
        $this->display();
    }

//用于保存编辑后的信息
    public function update()
    {
        $id=intval($_POST['id']);
        if (!$id) $this->error('编辑项不存在');
        $Pages=D("Advice");
        if( $Pages->create()) { 
            if($Pages->save()!==false){ 
                $this->assign('jumpUrl', Cookie::get('_currentUrl_') );
                $this->success("咨询编辑成功!");
            }else{ 
                $this->error("咨询编辑失败!"); 
            } 
        }else{ 
            $this->error($Pages->getError()); 
        } 
    }
//---删除信息---
    function del()
    {
        if(!is_numeric($_GET['id'])){
            $this->error('删除项不存在');
        }
        $map['id'] = $_GET['id'];
        $rs = M("Advice")->where($map)->delete();
        $this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
        if($rs)
        {
            $this->success('删除成功!');
        }else
            $this->error('删除失败!');

    }
//---全选删除---
    function delAll()
    {
        if(empty($_GET['str'])){
            $this->ajaxReturn('','您未选中任何项！',0);
        }
        $Form = M("Advice");
        $del_id = $_GET['str'];
        $where['id']=array('in',$del_id);  //删除条件
        $result=$Form->where($where)->delete();
        if($result){
            $this->ajaxReturn('','所选信息删除成功',1);
        }else{
            $this->ajaxReturn('','批量删除操作有误',0);
        }
    }

//隐藏某条明细
    public function hide_mx(){
        if(!is_numeric($_GET['id'])) {
             $this->error('参数不存在');
        }
        $Table = D("Advice");
        $map['id'] = intval($_GET["id"]);
        $rs = $Table->where($map)->find();
        if($rs['is_editor']==1){
            $this->error("编辑咨询不能隐藏!"); 
            return;
        }
        $data['is_hide'] = 1;
        if($Table->where($map)->save($data) !== false){ 
                $this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
                $this->success("咨询隐藏成功!");
        }else{ 
                $this->error("咨询隐藏失败!"); 
        } 

    }
//显示某条明细
    public function show_mx(){
        if(!is_numeric($_GET['id'])) {
             $this->error('参数不存在');
        }
        $Table = D("Advice");
        $map['id'] = intval($_GET["id"]);
        $data['is_hide'] = 0;
        if($Table->where($map)->save($data) !== false){ 
                $this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
                $this->success("咨询显示成功!");
        }else{ 
                $this->error("咨询显示失败!"); 
        } 

    }

//显示详情
    public function view()
    {
        if(!is_numeric($_GET['id'])) {
             $this->error('咨询项不存在');
        }
        $id = intval($_GET["id"]);
        $map['id'] = $id;
        $editVo    = M('Advice')->where($map)->find();
        $Shop = M("Shop");
        $map_['id'] = $editVo['shop_id'];
        $rs = $Shop->field('name')->where($map_)->find();
        if($rs){
            $editVo['shop_name'] = $rs['name'];
        }
        $this->assign("vo",$editVo);
        $this->display();
    }
    
  }
?>