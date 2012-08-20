<?php
/**
 +------------------------------------------------------------------------------
 * Pic  相册前端类
 +------------------------------------------------------------------------------
 * @author 熊彦
 +------------------------------------------------------------------------------
 */
class PicAction extends  GlobalAction{

//---名人列表页---
	public function index(){
		
		if($_GET['rows']){
		    $listRows = intval($_GET['rows']);;
		}else{
			$rows=10;
		}
		
		if( isset($_GET['cid']) ){
			$classId = intval( $_GET['cid'] );  //分类id
		}

		$Table = M('Pic');
		$map = null;
		if($classId){	
			//所属栏目名称
			$map_cls['id'] = $classId;
			$partname = M('Class')->field('name,title_content,meta_keyword,description')->where($map_cls)->find();
			$dump['partname'] = $partname['name'] ;
			$dump['partid']  = $classId;
		    $map['class_id'] = $classId;
		}else{
			$dump['partname'] = "精彩瞬间";
			$dump['partid']   = $classId;
		}

			

		//分页开始	
		//=====显示分页=======
		import("ORG.Util.Page");
		$totalRows = $Table->where($map)->count(); //1.总的记录数
		$listRows= $rows;						   //2.每页显示的条数
		$p  = new Page( $totalRows, $listRows );
		$page= $p->show(); 
		//=====end 分页=====

		$list =  $Table->field('id,title,remark,add_time')->where($map)->order('sortrank desc,id desc')
			->limit($p->firstRow.','.$p->listRows)->findall();  //默认按照时间倒序
		
		$Public = A("Public"); 
		if($list){
			foreach($list as $k=>$v){
				$story_pic = $Public->get_attach_one($info_id=$v['id'], $model_name="pics", $class_id = 0, $is_defautl=1);
				$list[$k]['thumb_pic'] = $story_pic['thumb'] ;
				$list[$k]['big_pic']   = $story_pic['big'] ;
			}
			$this->assign('list',$list); //
		}
		//dump($list);
		$this->assign('page',$page); 	
				
		$lp_reviews = A("Index")->index_review_list($ord = 'content', $num = 10); //楼盘点评内容
		$dump['lp_reviews'] = $lp_reviews;
		$this->assign('dump',$dump);
		$this->display('pic_list');
    }

//---内页/详情---
	public function view(){
		$info_id = intval($_GET['id']);
		if(empty($info_id)){
			$this->error('参数错误');   
		}
		$Table = M('Pic');
		$rs = $Table->setInc('hits','id='.$info_id,'1');  //更新人气

		$map['id'] = $info_id ;
		//$map['ispublish'] = 1 ;
		$vo = $Table->where($map)->find(); 	//该商家详细信息
		if(!$vo) $this->error('您请求的页面不存在或已被删除！');
		$this->assign("vo", $vo);
		
		unset($map);
		//图片s
		$Attach = M("Attach");
		$map['info_id'] = $info_id;
		$map['model_name'] = "pics";
		$fields= "id,savepath,savename,info_id,upload_time,remark,size";
		$pic_list = $Attach->field($fields)->where($map)->limit("0,30")->order("id desc")->findall();
		
		//正文分页start
		$page_sum = count($pic_list);

		if($page_sum>1){
			$curr_cp = intval($_GET['cp']);
			if($_GET['goto']=="down"){
				$curr_cp++;
			}
			if($_GET['goto']=="up"){
				$curr_cp--;
			}
			if($curr_cp<1 || $curr_cp>$page_sum){
				$curr_cp = 1;
			}

			$attach_id = $pic_list[$curr_cp-1]['id'];
			foreach($pic_list as $k=>$v){
				if($k+1 == $curr_cp){
					$page_tmp[] = "<font color=red>[<a href=\"__APP__/pics-".$info_id."?&cp=".($k+1)."\">".($k+1)."</a>]</font>";
				}else{
					$page_tmp[] = "[<a href=\"__APP__/pics-".$info_id."?&cp=".($k+1)."\">".($k+1)."</a>]";
				}
			}
			$text_page = implode("&nbsp;", $page_tmp);
			$this->assign('page_sum',$page_sum);
			$this->assign('text_page',$text_page);
		}else if($page_sum==1){
			$attach_id = $pic_list[0]['id'];  //仅一张图
		}
		$this->assign("curr_cp", isset($curr_cp)?$curr_cp:1 );		
		//正文分页end

		unset($map);
		$map['id'] = ( isset($attach_id) ? $attach_id : $vo['default_attach_id'] );
		$curr = $Attach->field($fields)->where($map)->find(); //当前图片
		$curr['big']   = ( $curr ? trim($curr['savepath'],'.').$curr['savename'] : "/Public/Upload/no.png" ) ;
		//dump($curr);
		$this->assign("curr", $curr);
		//dump($pic_list);
		//dump($vo);
		$center_id = ($curr_cp>5 ? $curr_cp-5 : 0);
		$sm_pic_srr = array_slice ( $pic_list, $center_id ,5 );
		$this->assign("pic_list", $sm_pic_srr ); //幻灯图片

		$lp_reviews = A("Index")->index_review_list($ord = 'content', $num = 10); //楼盘点评内容
		$dump['lp_reviews'] = $lp_reviews;
		$this->assign("dump", $dump);

		$this->display('view');
    }

}
?>