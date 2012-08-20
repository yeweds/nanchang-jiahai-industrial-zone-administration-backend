<?php
/**
 +------------------------------------------------------------------------------
 * Story  故事前端类
 +------------------------------------------------------------------------------
 * @author 熊彦
 +------------------------------------------------------------------------------
 */
class StoryAction extends  GlobalAction{

//---名人列表页---
	public function index(){
		
		if($_GET['rows']){
		    $listRows = intval($_GET['rows']);;
		}else{
			$rows=10;
		}

		$Table = M('Story');

		//右侧新闻列表 s
		$news_clhl = A("Index")->get_news_list( $num = 3, $class_id = 19, $is_pic = True ); //商家活动
		$news_hlzx = A("Index")->get_news_list( $num = 8, $class_id = 14 ); //婚礼资讯

		$this->assign("news_clhl", $news_clhl);
		$this->assign("news_hlzx", $news_hlzx);
		//右侧新闻列表 e

		if(!$wmap){	
			//所属栏目名称
			$dump['partname'] = "爱情故事" ;
			$dump['partid'] = $classid ;
			 //查出标题----------------------------
			unset($map);
		}else{
			$dump['partname'] = "爱情故事" ;
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
		$listRows= $rows;						   //2.每页显示的条数
		$p  = new Page( $totalRows, $listRows );
		$page= $p->show(); 
		//=====end 分页=====

		$list =  $Table->field('id,title,remark,boy_name,girl_name,add_time')->where($map)->order('sortrank desc,id desc')
			->limit($p->firstRow.','.$p->listRows)->findall();  //默认按照时间倒序
		
		$Public = A("Public"); 
		if($list){
			foreach($list as $k=>$v){
				$story_pic = $Public->get_attach_one($info_id=$v['id'], $model_name="story", $class_id = 0, $is_defautl=1);
				$list[$k]['thumb_pic'] = $story_pic['thumb'] ;
				//$list[$k]['big_pic']   = $story_pic['big'] ;
				$list[$k]['remark']    = mb_substr($v['remark'],0,80,"utf8") ;
			}
			$this->assign('list',$list); //故事标题列表
		}
		
		$this->assign('page',$page); 	
		
		// 标题/关键字/描述
		$page_info['title']=$partname['title_content'].' - '.C('cfg_sitename'); 
		$page_info['keywords']=$partname['meta_keyword'].' - '.C('cfg_metakeyword');
		$page_info['description']=$partname['meta_content'].' - '.C('cfg_metakeyword');
		$this->assign('page_info',$page_info);				

		$this->assign('news',$dump);
		$this->display('story_list');
    }

//---内页/详情---
	public function view(){
		$info_id = intval($_GET['id']);
		if(empty($info_id)){
			$this->error('参数错误');   
		}
		$Table = D('Story');
		$rs = $Table->setInc('hits','id='.$info_id,'1');  //更新人气

		$map['id'] = $info_id ;
		//$map['ispublish'] = 1 ;
		$vo = $Table->where($map)->find(); 	//该商家详细信息
		if(!$vo) $this->error('您请求的页面不存在或已被删除！');
		
		unset($map);
		//图片s
		$map['info_id'] = $info_id;
		$map['model_name'] = "story";
		$fields= "id,savepath,savename,info_id,upload_time,remark";
		$pic_list = M("Attach")->field($fields)->where($map)->limit("0,7")->order("id desc")->findall();
		$this->assign("pic_list", $pic_list); //幻灯图片
		if($pic_list){
			foreach($pic_list as $v){
				//$target_tmp[] = '"'."gs-".$v['id'].'"';
				$target_tmp[] = '"'.trim($v["savepath"],".").$v["savename"].'"';
			}
			$target = implode($target_tmp ,",");
			//形如["xixi-8","xixi-21","xixi-22","xixi-23","xixi-24","xixi-25","xixi-26","xixi-27","xixi-28"]
			$this->assign("target", $target);
		}
		//dump($pic_list);

		//$pic_all = M("Attach")->field($fields)->where($map)->limit("0,50")->order("id desc")->findall();
		//$this->assign("pic_all", $pic_all); //所有图片列表
		//图片e

		$reviews_list = A("Reviews")->getReviewsList($model_name = 'story', $num = 6, $info_id); //获取点评
		if($reviews_list){
			$this->assign("reviews_list", $reviews_list);
		}

		$this->assign("vo", $vo);
		//dump($vo);
		$this->display('view');
    }

}
?>