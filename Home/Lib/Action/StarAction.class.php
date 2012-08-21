<?php
/**
 +------------------------------------------------------------------------------
 * Star  名人前端类
 +------------------------------------------------------------------------------
 * @author 熊彦
 +------------------------------------------------------------------------------
 */
class StarAction extends  GlobalAction{

//---名人列表页---
	public function index(){
		
		if($_GET['rows']){
		    $listRows = intval($_GET['rows']);;
		}else{
			$rows= 10;
		}

		$Table = M('Personage');

		//右侧新闻列表 s
		$news_clhl = A("Index")->get_news_list( $num = 3, $class_id = 19, $is_pic = True ); //商家,原潮流婚礼
		$news_hlzx = A("Index")->get_news_list( $num = 8, $class_id = 14 ); //婚礼资讯

		$this->assign("news_clhl", $news_clhl);
		$this->assign("news_hlzx", $news_hlzx);
		//右侧新闻列表 e

		if(!$wmap){	
			//所属栏目名称
			$dump['partname'] = "名人堂" ;
			$dump['partid'] = $classid ;
			 //查出标题----------------------------
			unset($map);
		}else{
			$dump['partname'] = "名人堂" ;
			$dump['partid']   = $classid;
			$partname['title_content'] = "房产新闻,房产要闻,楼市要闻";
			$partname['meta_keyword'] = "房产,楼市,房产新闻,楼市新闻";
			$partname['meta_content'] = "腾房网为您提供最新最快的全国房产新闻信息";
		}
		$map=null;	

		//分页开始	
		//=====显示分页=======
		import("ORG.Util.Page");
		$totalRows = $Table->where($map)->count(); //1.总的记录数
		$listRows= $rows;						  //2.每页显示的条数
		$p  = new Page( $totalRows, $listRows );
		$page= $p->show(); 
		//=====end 分页=====

		//要据当前页面显示相应条数标签
		$list =  $Table->field('id,realname,en_name,weibo,address,pic,position,remark,add_time')->where($map)->order('add_time desc')->limit($p->firstRow.','.$p->listRows)->findall();  //默认按照时间倒序
		if($list){
			foreach($list as $k=>$v){
				$list[$k]['remark'] = strip_tags($v['remark']);
			}
			$this->assign('list',$list); //标题列表
		}
		$this->assign('page',$page); 	
		
		// 标题/关键字/描述
		$page_info['title']=$partname['title_content'].' - '.C('cfg_sitename'); 
		$page_info['keywords']=$partname['meta_keyword'].' - '.C('cfg_metakeyword');
		$page_info['description']=$partname['meta_content'].' - '.C('cfg_metakeyword');
		$this->assign('page_info',$page_info);				

		$this->assign('news',$dump);
		$this->display('star_list');
    }

//---内页/详情---
	public function view(){
		$newsid = intval($_GET['id']);
		if(empty($newsid)){
			$this->error('参数错误');   
		}
		$Table = D('Personage');
		$rs = $Table->setInc('hits','id='.$newsid,'1');  //更新人气

		$map['id'] = $newsid ;
		//$map['ispublish'] = 1 ;
		$vo = $Table->where($map)->find(); 	//该商家详细信息
		if(!$vo) $this->error('您请求的页面不存在或已被删除！');
		
		unset($map);
		$map["pic"] = array("neq",""); //不为空
		$fields= "*";
		$list = M('Attach')->field($fields)->where($map)->limit("0,10")->order("id desc")->findall();

		$this->assign("vo", $vo);
		//dump($vo);
		$this->display('view');
    }

}
?>