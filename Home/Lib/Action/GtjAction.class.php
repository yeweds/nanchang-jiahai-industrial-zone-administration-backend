<?php
/**
 +------------------------------------------------------------------------------
 * Gtj  小棉袄前台 国土转让信息类 
 +------------------------------------------------------------------------------
 * @author 熊彦
 +------------------------------------------------------------------------------
 */
class GtjAction extends Action{
//---国土交易信息列表
	public function index()
	{
        $Table = M('Gtj');
		$map = null;
		//$map['is_hide']  = 0;

		$listRows="20";
		import("ORG.Util.Page");
		$count=$Table->where($map)->count();
		$p=new Page($count,$listRows);
		$page=$p->show();

	    $list =$Table->where($map)->order("id desc")->limit($p->firstRow.','.$p->listRows)->findall(); 

		//dump($list);
		if($list!==false){		
			$this->assign('list',$list); //交易列表
			$this->assign('page',$page);
		}

		$this->display();
	}

//---详细页
	public function view(){
		$gtj_id = intval($_GET['gtj_id']);
		$Table = M('Gtj');
		$map['id'] = $gtj_id;
		$vo = $Table->where($map)->find();
		$range_id=$vo['range_id'];
		$range_name=D('area')->field('name')->where("id =$range_id")->find();
		$vo['range_name']=$range_name['name'];			

		if($vo){
			$this->assign('vo', $vo); //楼盘信息
		}else{
			$this->error('找不到该地块成交信息。');
		}
		$this->display();
	}


//---根据条件获取国土交易信息列表
	public function getGtjList($num, $title_len=20 ){
		$Table = M('Gtj');
		$map['is_gongye'] = 0;
        $list = $Table->field('id,bianhao,weizhi,jiaoyishijian,range_id')->where($map)->order('id desc')
			->limit('0,'.$num )->findAll();

		return $list;
	}



}

?>