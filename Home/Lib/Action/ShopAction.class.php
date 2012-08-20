<?php
/**
 +------------------------------------------------------------------------------
 * Shop  前台商家类
 +------------------------------------------------------------------------------
 * @author  熊彦
 +------------------------------------------------------------------------------
 */
class ShopAction extends GlobalAction{
	
	private $shop_pid = 3;  //商家大类ID
//---商家列表页---
	public function index(){

		$Table = M('Shop');
		if(isset($_GET['rows'])){
		    $listRows = intval($_GET['rows']);;
		}else{
			$rows = 10;
		}
		//右侧新闻列表 s
		$news_clhl = A("Index")->get_news_list( $num = 3, $class_id = 19, $is_pic = True ); //商家活动
		$news_hlzx = A("Index")->get_news_list( $num = 8, $class_id = 0 ); //婚礼资讯

		$this->assign("news_clhl", $news_clhl);
		$this->assign("news_hlzx", $news_hlzx);
		//右侧新闻列表 e

		if( isset($_GET['cid']) ){
			$classId = intval( $_GET['cid'] );  //分类id
		}
		if( isset($_GET['shop_cls_id']) ){
			$classId = intval( $_GET['shop_cls_id'] );  //分类id,用于搜索
		}

		$key  = urldecode($_GET['key']);
		if(!empty($key)){
			$where['name']  = array('like',"%".$key."%");
			//$where['lp_full_name']  = array('like',"%".$key."%");
			$info_id_arr = $this->get_result_id($key);  //获取ID
			if( count($info_id_arr)>0 ){
				$where['id']   = array('in', $info_id_arr);  //通过INFO_ID查
			}
			$where['_logic']  = 'or';
			$map['_complex'] = $where;
		}else{
			$map = null;
		}
		if( isset($_GET['range_id']) && !empty($_GET['range_id']) ){
			$wj_range = trim($_GET['range_id']);
			$map['wj_range']  = array('like',"%".$wj_range."%"); //外景地
		}
		if(isset($_GET['price_range']) && !empty($_GET['price_range']) ){
			$priceArr = explode('-', $_GET['price_range']);
			$min_price = intval( trim($priceArr[0]) );
			$max_price = $priceArr[1]==0 ? 99999: intval( trim($priceArr[1]) );  //缺省最大价格
			$map['price_min']  = array('egt', $min_price);
			$map['price_max']  = array('elt', $max_price);
		}

		if($classId){	
			//所属栏目名称
			$map_cls['id'] = $classId;
			$partname = M('Class')->field('name,title_content,meta_keyword,description')->where($map_cls)->find();
			$dump['partname'] = $partname['name'] ;
			$dump['partid'] = $classId;
			 //查出标题----------------------------
			unset($map);
		    $map['class_id'] = $classId;
		}else{
			$dump['partname'] = "全部商家";
			$dump['partid']   = $classId;
			$partname['title_content'] = "全部商家,商家列表";
			$partname['meta_keyword'] = "商家,婚庆公司,房产新闻,楼市新闻";
			$partname['meta_content'] = "南昌婚庆网为您提供最新最快的全国婚庆新闻信息";
		}

		//=====start 分页=======
		import("ORG.Util.Page");
		$totalRows = $Table->where($map)->count(); //1.总的记录数
		//echo $Table->getlastsql();
		$listRows= $rows;						   //2.每页显示的条数
		$p  = new Page( $totalRows, $listRows );
		$page= $p->show(); 
		//=====end 分页=====

		$list =  $Table->field('id,name,sub_title,remark,tel,logo_url,star,hits,add_time,dp_count,dp_hits')->where($map)
			->order('sortrank desc,id desc')->limit($p->firstRow.','.$p->listRows)->findall();  
		//默认按照时间倒序
		
		$Public = A("Public"); 
		if($list){
			foreach($list as $k=>$v){
				/*//显示商家默认图片
				$story_pic = $Public->get_attach_one($info_id=$v['id'], $model_name="shop", $class_id = 0, $is_defautl=1);
				$list[$k]['thumb_pic'] = $story_pic['thumb'] ;
				//$list[$k]['big_pic']   = $story_pic['big'] ; */
				//显示logo
				$list[$k]['thumb_pic'] = ( !empty($v['logo_url']) ? C("cfg_img_path").'Shop/'.$v['logo_url'] : "/Public/Upload/no.png") ;
				//生成星星
				$star_str = "";
				for($i=1;$i<= $v['star']; $i++ ){
					$star_str = $star_str."★"; 
				}
				$list[$k]['star'] = $star_str;
				$remark = trim(html2text($v['remark']));
				$list[$k]['remark'] = mb_substr($remark,0,80,'utf8');
			}
			$this->assign('list',$list); //故事标题列表
		}
		//dump($list);
		//echo $Table->getlastsql();
		$this->assign('page',$page); 	
		
		// 标题/关键字/描述
		$page_info['title']=$partname['title_content'].' - '.C('cfg_sitename'); 
		$page_info['keywords']=$partname['meta_keyword'].' - '.C('cfg_metakeyword');
		$page_info['description']=$partname['meta_content'].' - '.C('cfg_metakeyword');
		$this->assign('page_info',$page_info);				

		$this->assign('news',$dump);
		$this->display('shop_list');
    }

//---内页/详情---
	public function view(){
		$info_id = intval($_GET['id']);
		if(empty($info_id)){
			$this->error('参数错误');   
		}
		$Table = D('Shop');
		$rs = $Table->setInc('hits','id='.$info_id,'1');  //更新人气

		$map['id'] = $info_id ;
		//$map['ispublish'] = 1 ;
		$vo = $Table->where($map)->find(); 	//该商家详细信息
		if(!$vo) $this->error('您请求的页面不存在或已被删除！');

		$Attach = M("Attach");

		unset($map);
		$map['id'] = $vo['class_id'];
		$rs = M('Class')->field('name')->where($map)->find();
		$vo['shop_type'] = ($rs? $rs['name'] : '未知'); //分类名
		
		$area_txt = array('南昌市区','南昌郊区','省内','省外','国际路线');
		$area_val = array('1','2','3','4','5');

		$vo['area_name'] = str_replace($area_val,$area_txt, $vo['wj_range']) ;   //外景区域

		unset($map);
		$right_list = $this->shop_class_list($num=10);  //右侧列表

		//dump($right_list);
		//图片s
		$map_xc1['shop_id'] = $info_id;
		$map_xc1['class_id'] = 16; 
		$xc1_arr = M('Pic')->field('id,shop_id,title')->where($map_xc1)->limit("0,12")->select();
		$Public = A("Public"); 
		if($xc1_arr){
			foreach($xc1_arr as $k=>$v){
				$rs1 = $Public->get_attach_one($v['id'], $model_name="pics", $class_id = 0, $is_defautl=1);
				$xc1_arr[$k]['thumb_pic'] = $rs1['thumb'] ;
				$xc1_arr[$k]['big_pic']   = $rs1['big'] ;
			}
			$this->assign("xc1_list", $xc1_arr); //相册1列表
		}

		//echo $Attach->getlastsql();
		//dump($pic_list);
		//-----------------------------------------------------
		//unset($info_arr_ids);
		unset($map);
		$map_xc2['shop_id'] = $info_id;
		$map_xc2['class_id'] = 17; 
		$xc2_arr = M('Pic')->field('id,shop_id,title')->where($map_xc2)->limit("0,12")->select();
		$Public = A("Public"); 
		if($xc2_arr){
			foreach($xc2_arr as $k=>$v){
				$rs1 = $Public->get_attach_one($v['id'], $model_name="pics", $class_id = 0, $is_defautl=1);
				$xc2_arr[$k]['thumb_pic'] = $rs1['thumb'] ;
				$xc2_arr[$k]['big_pic']   = $rs1['big'] ;
			}
			$this->assign("xc2_list", $xc2_arr); //相册2列表
		}

		//图片e

		unset($map);
		$map['info_id'] = $info_id;
		$map_rs = M('News_mapping')->field('info_id,news_id')->where($map)->findAll();
		foreach($map_rs as $v){
			$news_id_arr[] = $v['news_id'];
		}
		$map_news['id'] = array('in', $news_id_arr);
		$map_news['ispublish'] = 1;
		$news_list = M('News')->field('id,class_id,title,add_time')->where($map_news)->order('id desc')->limit('0,8')->findall();  //新闻列表
		if($news_list){
			$this->assign("news_list", $news_list);
		}
		//最新活动

		$reviews_list = A("Reviews")->getReviewsList($model_name = 'shop', $num = 10, $info_id); //获取点评
		if($reviews_list){
			$this->assign("reviews_list", $reviews_list);
		}

		$vo['star'] = $this->make_star( $star_num = $vo['star'] ); //转星
		$vo['content']  = htmlspecialchars_decode($vo['content'], ENT-QUOTES ); //出库


		//--默认图片
		//$Attach = A('Attach');
		//$att_rs = $Attach->getAttachByInfoId($info_id, $class_id = 0, $is_defautl=1);
		$att_rs = $vo['logo_url']; 
		if($att_rs != 'no.png'){
			$att_rs = $att_rs;  #不用缩略图 "thumb_".
		}
		$default_pic = "<img src=\"".C('cfg_img_path')."Shop/".$att_rs."\" width=\"255\" height=\"205\" alt=\"缩略图\" />";
		$this->assign('default_pic',$default_pic); //默认缩略图片

		$this->assign("vo", $vo);
		$this->assign("right_list", $right_list);
		$this->display();
    }

/* 生成星星 */
	public function make_star($star_num){
		$star_str = "";
		for($i=1;$i<= $star_num; $i++ ){
			$star_str = $star_str."★"; 
		}
		return $star_str;
	}

//获取右侧列表
	public function shop_class_list($num=10){
		
		$Class = M ('Class');
		$map['pid'] = $this->shop_pid;
		$class_list = $Class->field('id,name')->where($map)->select();
		if (! $class_list) {
			$this->error ( '商家分类不存在,请先添加分类!' );
		}
		unset($map);

		$Table = M("Shop");
		foreach($class_list as $k=>$v){

			$map['class_id'] = $v['id'];
			$fields= "id,name,sub_title,star,hits,add_time";
			$list = $Table->field($fields)->where($map)->limit("0,".$num)->order("sortrank desc,id asc")->findall();
			$class_list[$k]['_child'] = $list;
		}

		return $class_list;

	}


//---收藏商家入库 ---
    public function in_favorite()
	{
		$info_id = intval($_GET['info_id']);
		if(empty($info_id)){
			$this->error( '参数出错!' );
			exit;
		}
        $userid = $this->uid;
		if(empty($userid)){
			$this->error( '你还没有登录，登录后才能收藏!' ); //未登录
			exit;
		}
		//检测是否已经收藏
		$Table = M('Favorite');
		$map['user_id'] = $userid ;
		$map['info_id'] = $info_id ;
		$map['model_name'] = "shop" ;
		$id = $Table->where($map)->find();
		if(!empty($id)){
			$this->error( '你已收藏过该楼盘，不需要重复操作!' );
			exit;
		}
		$data['user_id'] = $userid ;
		$data['info_id'] = $info_id ;
		$data['model_name'] = "shop" ;
		if($Table->add($data)){
			$this->success( '收藏成功!' );
		}else{
			$this->error( '收藏失败!' );
	    }
	}
	

//---保存申请咨询入库 ---
    public function save_advice(){
		$info_id = intval($_POST['info_id']);
		if(empty($info_id)){
			$this->error( '参数出错!' );
			exit;
		}
		$Table = D('Advice');
		$data = $Table->create();
		if(empty($data['tel'])) $this->error( '请留下联系方式!' );
		if(empty($data['content'])) $this->error( '内容不能为空!' );
		
		//dump($_POST);
		$data['reply_type'] = implode( $_POST['reply_type'] , ','); //回复类型
		$data['add_time'] = time();
		$data['shop_id']  = $info_id; //商家id
		$data['model_name'] = "shop"; //模块名
		$data['user_id']  = $this->uid;
		if($Table->add($data)){
			$this->success( '申请已成功提交!' );
		}else{
			$this->error( '提交申请失败!' );
	    }
	}

//获取索引中的ID, 用于搜索
	public function get_result_id($key){
			$key = strtolower($key);
			if (!$key) return;

			$Table = M("Shop");
			$map['head_py'] = array('like',"%".$key."%");
			$map['pinyin']  = array('like',"%".$key."%");
			$map['name']   = array('like',"%".$key."%");
			$map['sub_title']  = array('like',"%".$key."%");
			$map['_logic']  = 'or';
			$where['_complex'] = $map;
			//$where['class_id']  = array('eq',7); //只查楼盘

 			$field = 'id';
			$list = $Table->where($where)->field($field)->limit('0,30')->findall();
			$vo = array();
			if($list){
				foreach($list as $v){
					$vo[] = $v['id'];
				}
			}
			return $vo;
	}
}
?>