<?php
/**
 +------------------------------------------------------------------------------
 * Search  搜索类
 +------------------------------------------------------------------------------
 * @author 熊彦
 +------------------------------------------------------------------------------
 */
class SearchAction extends GlobalAction{

//---搜索结果列表页---
	public function index(){

		//dump($_GET);
		$type = trim($_GET['search_type']);
		$key  = urldecode($_GET['key']);
		$Info = M('Info');
		if( $type == 'loupan'){
			$Table = M('New_loupan');
			$map['lpname'] = array('like',"%".$key."%");
			$search_list_tpl = 'Loupan:loupan_search_list'; //传统楼盘页
		}else if($type == 'news'){
			$Table = M('News');
			$map['title'] = array('like',"%".$key."%");
			$map['ispublish'] = 1;  //只显示已发布
			$search_list_tpl = 'News:news_search_list';     //搜新闻

		}else{
			exit;
		}
		//$field = 'id,info_id,lpname,lpxingzheng';
		$listRows="20";
		import("ORG.Util.Page");
		$count=$Table->where($map)->count();
		$p=new Page($count,$listRows);
		$page=$p->show();

		$list = $Table->where($map)->field('*')->limit($p->firstRow.','.$p->listRows)->order('add_time desc')->findall();
		if($list){
			$this->assign('list',$list);
			$this->assign('page',$page);
		}
		$this->assign('search_key',$key);  //关键字

		// 标题/关键字/描述
		$page_info['title']= $key.' 搜索结果'.' - '.C('cfg_sitename').' - '.C('cfg_design'); 
		$page_info['keywords']='房产资讯 - 新闻首页'.' - '.C('cfg_metakeyword');
		$page_info['description']='房产资讯 - 新闻首页'.' - '.C('cfg_metakeyword');
		$this->assign('page_info',$page_info);	
		
		$this->assign('AD_right',A('News')->AD['news_list_right']);         //新闻列表页面右边栏广告

		$this->display($search_list_tpl);
    }
} 
?>