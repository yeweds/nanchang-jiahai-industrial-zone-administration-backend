<?php
/**
 +------------------------------------------------------------------------------
 * Index  前台首页类
 +------------------------------------------------------------------------------
 * @author 熊彦
 +------------------------------------------------------------------------------
 */
class IndexAction extends GlobalAction{
//---首页---
	public function index(){
		
		$NewsAct = A('News');
		$NewsAct->index_pub(1);  //在首页插入新闻页

		//old 首页搜索相关 -- start
		if( !is_file("./Public/Data/search_data.js") ){

		$area_txt = "'南昌市区','南昌郊区','省内','省外','国际路线'";
		$area_val = "'1','2','3','4','5'";
		
		$shop_cls = M("Class")->field('id,name')->where("pid=3")->select();
		foreach($shop_cls as $v){
			$wuyetype_t[] = "'".$v['name']."'";
			$wuyetype_v[] = "'".$v['id']."'";
		}
		$wuyetype_txt = implode(',', $wuyetype_t);
		$wuyetype_val = implode(',', $wuyetype_v);

		$price_txt = "'500以下','500-1000','1000-2000','2000-4000','4000-6000','6000-8000','8000-10000','10000以上'";
		$price_val = "'0-500','500-1000','1000-2000','2000-4000','4000-6000','6000-8000','8000-10000','10000-99999'";
		
		$search_data = "var optionRangeIdTextA=['-不限外景地-',".$area_txt."];\n var optionRangeIdValueA=['',".$area_val."];\n\n";
		$search_data .="var optionLpWuyetypeTextA=['-不限类型-',".$wuyetype_txt."];\n var optionLpWuyetypeValueA=['',".$wuyetype_val."];\n\n";
		$search_data .="var optionLpPriceRangeTextA=['-不限预算-',".$price_txt."];\n var optionLpPriceRangeValueA=['',".$price_val."];\n\n";

			file_put_contents("./Public/Data/search_data.js",$search_data);  //写临时JS文件
		}
		
		//$this->assign('wuyetype_list',C('cfg_wuyetype')); //用于物业类型列表
		//$this->assign('features_list',C('cfg_features')); //用于特色搜索列表 - 房间

		//$lptese = array_slice ( C('cfg_lptese'), 0 , 8 );
		//$this->assign('features_list',$lptese);   //暂用楼盘特色代替  - 楼盘 

		$this->assign('userInfo',$this->userInfo);        //用于获取登录信息
		if(Cookie::is_set('isUseHelpOnce')){
			$isUseHelpOnce = 1;
		}else{
			$isUseHelpOnce = 0;
			Cookie::set('isUseHelpOnce', 1, 3600*24*60); //存两月,是否第一次使用新手上路
		}
		$this->assign('isUseHelpOnce',$isUseHelpOnce);
		//old 首页搜索相关 -- end

//婚纱套系相册等  -- start
		if(S("INDEX_PICLIST")){
			$pic_list = S("INDEX_PICLIST");
		}else{
			$pic_cid = array(18,35,36); //相册分类id
			foreach($pic_cid as $v){
				$pic_list[$v] = $this->get_pic_list( $num = 12, $class_id=$v, $is_pic=False); //按分类查相册
			}
			S("INDEX_PICLIST", $pic_list , 300 );
		}
		//dump($pic_list);
		$this->assign("pic_list", $pic_list); 
//婚纱套系相册等  -- end

		if(S("INDEX_SHOPLIST")){
			$shop_list = S("INDEX_SHOPLIST");
		}else{
			$shop_cid = array(5,6,7,8,9,10,11); //商家分类id
			foreach($shop_cid as $v){
				$shop_list[$v] = $this->get_shop_list( $num = 24, $class_id=$v, $is_pic=False); //商家
			}
			S("INDEX_SHOPLIST", $shop_list , 300 );
		}
		$this->assign("shop_list", $shop_list); 
		//dump($shop_list);

//--获取视频列表
	$tv_list = $this->get_news_list( 12, 37, $is_pic = true, $show_clsname = False);
	//dump($tv_list);
	if($tv_list){
		$this->assign("tv_list", $tv_list); 
	}


//--友情链接--start
		if(S('PUB_FLINK')){
			$lp_link = S('PUB_FLINK');
		}else{
			$all_link = getLinks($act=1,$num=300); // 1文字
			for($li=0;$li<=5;$li++){
				//$lp_link[$li] = getLinks($act=1,$num=42,$class_id=$li); // 1文字
				$start_rows = $li* 42;
				$lp_link[$li] = array_slice ( $all_link, $start_rows , 42 );
			}
			S('PUB_FLINK', $lp_link, 3600);
		}
		$this->assign('lp_link',$lp_link);
//--友情链接--end

		$this->display('index');
    }

//获取商家列表
	public function get_shop_list( $num = 32, $class_id=0, $is_pic=False){
		$Table = M("Shop");
		$map = null;
		if($is_pic === True){
			$map["pic"] = array("neq",""); //不为空
		}
		if($class_id){
			$map['class_id'] = $class_id;
		}
		$fields= "id,name,sub_title,star,hits,add_time";
		$list = $Table->field($fields)->where($map)->limit("0,".$num)->order("sortrank desc,id desc")->findall();
		unset($map);
		if($list){
			$Mapping = M("News_mapping");
			foreach($list as $k=>$v){
				$map['info_id'] = array("eq", $v['id']);
				$rs = $Mapping->where($map)->count();
				$list[$k]['is_huodong'] = ( $rs>0 ? 1 : 0);
			}
			return $list;
		}else
			return False;
	}

//获取新闻列表
	public function get_news_list( $num = 10, $class_id=0, $is_pic = False, $show_clsname = False){
		$Table = M("News");
		$map = null;
		$map['ispublish'] = 1;

		if($class_id){
			$Class = M('Class');
			$sum_pid = $Class->where('pid='.$class_id)->count();

			if($sum_pid){
				$rs = $Class->where('id='.$class_id)->find();
				$map['curr_path'] = array("like", $rs['curr_path']."%" );
			}else{
				$map['class_id'] = $class_id;
			}
			
		}
		if($is_pic){
			$map['pic_url'] = array("neq",""); //不为空
			$map['flag']    = array("like","%p%"); //有图
		}
		$fields= "id,title,sub_title,isshow_sub_title,remark,class_id,user_id,author,pic_url,flag,add_time,title_color,redirecturl,click";
		$list = $Table->field($fields)->where($map)->limit("0,".$num)->order("sortrank desc,id desc")->findall();
		if($list){
			if(!$class_id) { $Class = M('Class'); }
			foreach($list as $k=>$v){
				if( $v['isshow_sub_title']==1 ){
					$list[$k]['title'] = $v['sub_title'];
				}
				if($show_clsname){
					//显示类名
					$rs = $Class->field('name')->where('id='.$v['class_id'])->find();
					$list[$k]['clsname'] = ($rs ? $rs['name'] : '未知');
				}
			}
			return $list;
		}
		else
			return False;
	}


//获取相册列表
	public function get_pic_list( $num = 12, $class_id=0){
		$Table = M("Pic");
		$map = null;
		if($class_id){
			$map['class_id'] = $class_id;
		}
		$fields= "id,title,default_attach_id";
		$list = $Table->field($fields)->where($map)->limit("0,".$num)->order("sortrank desc,id desc")->findall();
		unset($map);
		if($list){
			$Public = A("Public");
			foreach($list as $k=>$v){
				$rs = $Public->get_attach_one($info_id=$v['id'], $model_name="pics", $class_id = 0, $is_defautl=1);
				$list[$k]['thumb_pic'] = $rs['thumb'] ;
				$list[$k]['big_pic']   = $rs['big'] ;
			}
			return $list;
		}else
			return False;
	}

//---首页测试---
	public function search(){
		//old 首页搜索相关 -- start
		if( !is_file("./Public/Data/search_data.js") ){

			$area_list= $this->area_list();   //取得区域列表
		foreach($area_list as $v){
			$area_t[] = "'".strtoupper($v['head_py'])." ".$v['name']."'";
			$area_v[] = "'".$v['id']."'";
		}
		$area_txt = implode(',', $area_t);
		$area_val = implode(',', $area_v);
		
		foreach(C('cfg_wuyetype') as $v){
			$wuyetype_t[] = "'".$v."'";
		}
		$wuyetype_txt = implode(',', $wuyetype_t);

		$price_txt = "'4000以下','4000-6000','6000-8000','8000-10000','10000以上'";
		$price_val = "'0-4000','4000-6000','6000-8000','8000-10000','10000-99999'";
		
		$search_data = "var optionRangeIdTextA=['-不限区域-',".$area_txt."];\n var optionRangeIdValueA=['',".$area_val."];\n\n";
		$search_data .="var optionLpWuyetypeTextA=['-不限类型-',".$wuyetype_txt."];\n var optionLpWuyetypeValueA=['',".$wuyetype_txt."];\n\n";
		$search_data .="var optionLpPriceRangeTextA=['-不限价格-',".$price_txt."];\n var optionLpPriceRangeValueA=['',".$price_val."];\n\n";

			file_put_contents("./Public/Data/search_data.js",$search_data);  //写临时JS文件
		}
		
		//$this->assign('wuyetype_list',C('cfg_wuyetype')); //用于物业类型列表
		//$this->assign('features_list',C('cfg_features')); //用于特色搜索列表 - 房间

		$lptese = array_slice ( C('cfg_lptese'), 0 , 8 );
		$this->assign('features_list',$lptese);   //暂用楼盘特色代替  - 楼盘 

		$this->assign('userInfo',$this->userInfo);        //用于获取登录信息
		if(Cookie::is_set('isUseHelpOnce')){
			$isUseHelpOnce = 1;
		}else{
			$isUseHelpOnce = 0;
			Cookie::set('isUseHelpOnce', 1, 3600*24*60); //存两月,是否第一次使用新手上路
		}
		$this->assign('isUseHelpOnce',$isUseHelpOnce);
		//old 首页搜索相关 -- end

		A('News')->index_pub(0);  //执行新闻页操作
		$this->display('Index:index_news');
    }

//---获取代表均价列表	
	public function get_behalf_price(){
			$Table = M("Behalf_price");
			$curr_hour = date('H');
			if( $curr_hour < 10 ){
				$today = date('Y-m-d', time()- 3600*24*2 ); //十点之前调前两天
			}else{
				$today = date('Y-m-d', time()- 3600*24 ); //前一天
			}
			//$today = '2012-06-21'; // 过年/放假临时处理
			$map['add_time'] = array('eq', $today);
			
			for($i=1; $i<=6; $i++){
				$map['range_id'] = $i;
				$map['agv_price'] = array('neq', 0 );
				$rs = $Table->where($map)->sum( agv_price );
				$count = $Table->where($map)->count();
				//dump($rs);
				$list[$i] = round( $rs / $count , 0 ) ; //不留小数
			}
			//dump($list);
			return $list;
	}


//---获取近期代表均价标题列表	
	public function get_behalf_title(){
			if( S('BEHALF_TITLE') ){
				$list = S('BEHALF_TITLE');
			}else{
				$Table = M("Behalf_price");
				$today = date('Y-m-d', time()- 3600*24 ); //前一天
				$map['add_time'] = array('lt', $today);
				
				$list = $Table->field('add_time')->where($map)->order('add_time desc')->group('add_time')->limit(20)->findAll();
				S('BEHALF_TITLE', $list , 3600*24); //存一天
			}
			//dump($list);
			return $list;
	}


//---通过助记码或楼盘名称查找楼盘信息，并返回id和name转JSON---
   public function get_info_mnemonic(){
			$key = strtolower($_GET["q"]);
			if (!$key) return;
			
			$Table = M("Shop");

			$map['head_py'] = array('like',"%".$key."%");
			$map['pinyin']  = array('like',"%".$key."%");
			$map['name']   = array('like',"%".$key."%");
			$map['sub_title'] = array('like',"%".$key."%"); 
			$map['_logic']  = 'or';
			
 			$field = 'id, name as title, class_id';
			$vo = $Table->where($map)->field($field)->limit('0,15')->findall();

			echo "[ ";
				foreach ($vo as $k=>$v) {
					$area = getClassName($v['class_id']);
					$area = ( $area== '' ? '未知': $area);
					$arr[] ='{ "name":"'.$v['id'].'","label":"'.$v['title'].'","area":"'.$area.'" }';
				}
				echo implode( ", ", $arr ); 
			echo " ]";
    }

//---获取楼盘点评列表，用于首页, 默认随机排序，缓存24小时
	public function index_review_list($ord = 'RAND', $num = 4){
		$list = false; 
		$Lp   = M('Shop');
		$Reviews	  = M('Reviews'); 
		if($ord == 'RAND'){
			//随机取
			$list = S('INDEX_REVIEW_LIST');
			if( !$list ){
				$cache_info_id = F('DP_CACHE_IFNO_ID');
				$map['content']    = array('neq','');  //内容不为空
				$map['model_name'] = array('eq','shop');  //只查商家点评
				$map['is_hide']    = 0; //非禁用
				$map['id']    = array(); //去重复
				$list = $Reviews->field('id,info_id')->where($map)->order('sortrank DESC,id DESC,hits DESC')
					->limit('0,500')->findAll(); // 查点评明细
				

				$min_num    = count($list)-$num ; 
				$rand_start = rand(0, $min_num);  //随机初始值
				//查楼盘
				$info_map['info_id'] = array('in', $info_arr);
				$list = $Lp->field('id,name')->where($info_map)->limit($rand_start.','.$num)->findAll();
				foreach($list as $k=>$v){
					$map_reviews['info_id'] = $v['info_id'];
					$rs = $Reviews->field('id,info_id,dp_count')->where($map_reviews)->find();
					$list[$k]['count'] = $rs['dp_count'];
				}

				S('INDEX_REVIEW_LIST',$list, 3600*24 );  //缓存24hour
			}
		
		}else if($ord == 'pr'){
			//按PR
			$list = S('INDEX_REVIEW_LIST_PR');
			if( !$list ){
				$list= $Reviews->field('id,info_id,dp_count,hits_sum')->order('sortrank DESC')
					->group('info_id')->limit('0,'.$num)->findAll(); // 查点评明细

				foreach($list as $k=>$vo){
					//查楼盘
					$info_id['id'] = $vo['info_id'];
					$r = $Lp->field('lpname,lpxingzheng,range_id')->where($info_id)->find();
					$list[$k]['lpname']    = $r['lpname'];
					$cache_info_id[] = $vo['info_id'];
				}
				F('DP_CACHE_IFNO_ID',$cache_info_id); //记录已存在ID，免重复
				S('INDEX_REVIEW_LIST_PR',$list, 3600*24 );  //缓存24hour
			}
		
		}else if($ord == 'hits'){
			//按人气
				$list= $Lp->field('id,name,dp_count,dp_hits')->order('dp_hits DESC')->limit('0,'.$num)->findAll(); // 查点评明细

				foreach($list as $k=>$vo){
					//查楼盘
					$list[$k]['lpname']    = $vo['name'];
					$list[$k]['dp_hits']   = $vo['dp_hits'] * 33; //假人气 =真*33
				}

		}else if($ord == 'count'){
			//按点评数
				$list= $Lp->field('id,name,dp_count,dp_hits')->order('dp_count DESC')->limit('0,'.$num)->findAll(); // 查点评明细

				foreach($list as $k=>$vo){
					//查楼盘
					$list[$k]['lpname']    = $vo['name'];
					$list[$k]['dp_hits']   = $vo['dp_hits'] * 33; //假人气 =真*33
				}
		}else if($ord == 'content'){
			//调内容
				$map['content']    = array('neq','该网友仅打了分，未留下任何高见.');  //内容不为空
				$map['is_hide']    = 0; //非禁用
				$map['model_name'] = array('eq','shop');  //只查商家点评
				$list= $Reviews->field('id,info_id,user_id,content')->where($map)->order('sortrank DESC,id DESC,hits DESC')
					   ->limit('0,'.$num)->findAll(); // 查点评明细
				foreach($list as $k=>$v){
					$str =  strip_tags($v['content']);
					$str =  str_replace(' ','',$str);
					$str =  str_replace('&nbsp;','',$str);
					$str =  str_replace('\n','',$str);
					$list[$k]['content'] = $str;

					$info_id['id']=$v['info_id'];
					$r = $Lp->field('name,tel,address')->where($info_id)->find();
					$list[$k]['lpname']    = $r['name'];
				}
		}

	    return $list;
	}

//首页开发商等随机列表
	public function kfs_etc_list($type = 'kfs', $num = 6){
		if($type == 'cehua'){
			$Table = M('Xma_cehua');
			$list = $Table->field('id,name')->where($map)->limit('0,'.$num)->order("sortrank desc")->findAll();
			return $list;
		}
		
		$Kfs = M('Kfs');
		if($type == 'kfs'){
			$map['pid']  =  0;
			$map['corp_type'] = 'kfs';
			$count = $Kfs->where($map)->count();
		}else if($type == 'wy'){
			$map['pid']  =  0;
			$map['corp_type'] = 'wy';
			$count = $Kfs->where($map)->count();
		}
		/*
		if( $count <= $num ){
			$rand_start = 0;  //少于要取的记录，则从第一条开始取
		}else{
			$min_num    = $count - $num ; 
			$rand_start = rand(0, $min_num);  //随机初始值
		}*/
		$rand_start = 0; //按排序 默认随机排
		$list = $Kfs->field('id,name')->where($map)->limit($rand_start.','.$num)->order("sortrank Desc")->findAll();
		return $list;

	}

//---取得当前城市的行政区---
	public function area_list($id = 1) {
		if(F('AREA_LIST')){
			$vo = F('AREA_LIST');
		}else{
			$map['pid']  =  $id ; //默认为南昌1
			$vo  =  D("Area")->field('id,name,head_py')->where($map)->order("sortrank ASC,head_py ASC")->findAll();
			F('AREA_LIST', $vo);  //长时间缓存
		}
        return $vo;
	}

//---高级搜索---
    public function adv_search(){
		$this->display();
    }

//---加载地图 index页搜索结果---
	public function map(){
		//dump($_POST);
		$map = null ;
		$range_id = 0 ;        //默认不限
		if(isset($_POST['key'])){
			$s_key= trim($_POST['key']);
			$where['head_py'] = array('like',"%".$s_key."%");
			$where['pinyin']  = array('like',"%".$s_key."%");
			$where['title']   = array('like',"%".$s_key."%");
			$where['_logic']  = 'or';
			$map['_complex'] = $where;
		}

		$map['class_id'] = 7 ; //仅查楼盘
		$map['is_ershou'] = 0 ; //非2手
		//$map['lpnosalecount'] = array('gt', 0);  //仅展示可售大于0的新楼盘

		if(!empty($_POST['range_id'])){
			$range_id = intval($_POST['range_id']);
			$map['range_id'] = array('eq', $range_id);
		}
		if(!empty($_POST['lp_wuyetype'])){
			$lp_wuyetype = $_POST['lp_wuyetype'];
			$map['lpwuyetype'] = array('like', "%".$lp_wuyetype."%");
		}
		if(!empty($_POST['lp_tese'])){
			$lp_tese = $_POST['lp_tese'];
			$map['lp_tese'] = array('like', "%".$lp_tese."%");
		}

		if(isset($_POST['lp_price_range'])){
			$priceArr = explode('-', $_POST['lp_price_range']);
			$min_price = intval( trim($priceArr[0]) );
			$max_price = $priceArr[1]==0 ? 99999: intval( trim($priceArr[1]) );  //缺省最大价格
			$map['lpprice']  = array('between', $min_price.','.$max_price);
		}
		if(!empty($_POST['room_hx'])){
			switch($_POST['room_hx']){
				case '1' : $room_hx = '一居'; break;
				case '2' : $room_hx = '二居'; break;
				case '3' : $room_hx = '三居'; break;
				case '4' : $room_hx = '四居'; break;
				case '5' : $room_hx = '五居'; break;
				case '6' : $room_hx = '六居'; break;
				case 'gt7' : $room_hx = '七居'; break;
				default :
					 $room_hx = ''; //不限
			}
			$map['lp_zhutui'] = array('like', "%".$room_hx."%");
		}
		if(!empty($_POST['room_area'])){
			$areaArr = explode('-', $_POST['room_area']);
			$min_area = intval(trim($areaArr[0])) ;
			$max_area = intval(trim($areaArr[1])) ;  //缺省最大面积
			$map_room['rarea'] = array('between', $min_area.','.$max_area);
			$room = M('New_room')->field('lpid,info_id')->where($map_room)->group('lpid')->findall();
			foreach($room as $v){
				if($v['lpid']){
					$room_arr_id[] = $v['lpid']; 
				}
			}
			$map['info_id'] = array('in', $room_arr_id);
			//$map['_string'] = ' ( lp_min_area <= '.$min_area.')  OR ( lp_max_area >= '.$max_area.') ';
		}
		if($_POST['lp_state']==1 ){
			$lp_state = '在售';
			$map['lp_state']  = 1;
		}else if ($_POST['lp_state']==2){
			$lp_state = '待售';
			$map['lp_state']  = 2;
		}else{
			//$map['lp_state']  = array('neq', 3); //非售完状态
			$lp_state = '所有';
		}
		//dump($map);
		Session::set('clientMap', $map);  //记录条件

		$Form   = M("View_allsite");
        $fields = 'info_id as id,title,lpprice,point_x,point_y';
		$count = $Form->field('id')->where($map)->count(); //1.总的记录数
		$this->setLpListCache();        //择需缓存120条

	    $list = $this->getLpListCache(0, 20, $count);  //获取楼盘缓存列表20条信息
		//$list = $Form->where($map)->field($fields)->order('lpyouxian desc, id desc')->limit('0,20')->findAll();
		//dump($list);
		
		if($list){
			$this->assign('list',$list);  //用于地图
		}

		$areaName = ($range_id==0 ? "不限区域" : getAreaName($range_id));
		$this->assign('areaName',$areaName);  //区域
		$wuyeType = (empty($_POST['lp_wuyetype']) ? "不限物业类型" : $_POST['lp_wuyetype']);
		$this->assign('wuyeType',$wuyeType);  //物业类型
		$this->assign('countInfo',$count);  //总计条数
		$countRoom = $Form->where($map)->sum('lpnosalecount');
		$this->assign('countRoom',$countRoom);  //可售房间总数
		$this->assign('min_price',$min_price);  //最低价
		$this->assign('max_price',$max_price);  //最高价
		$this->assign('lp_state',$lp_state);  //当前状态
		
//dump($list);
    //-------获取多点地图坐标
	 $list_json = $this->makePoint($list);  //生成坐标JSON
	 $this->assign('list_json',$list_json);
     if($list!=false){
		//---推广字符串---start
		$arrTG = '182,19';   // A('Ad')->TGID
		if($count == 1){
			$arrTG .= ','.$list[0]['id'];   //若只一个，也显示
		}
		$this->assign('arrTG',$arrTG);
		//---推广字符串---end
	 }
//-------显示地图	多点结束
	    $this->display();
	}

	public function lpList_ajax() //ajax分页显示楼盘
	{
		$Form = M("View_allsite");
		$map  = Session::get('clientMap');  //已保存的用户查询条件
		//=====显示分页=======
		import("@.Other.AdvPage");
		$count = $Form->where($map)->count(); //1.总的记录数
		$listRows = 20;					//2.每页显示的条数
		$dom_id='#map_content';         //4.dom模型id,即ajax要加载入的图层id
		$url="__URL__/lpList_ajax";     //5.当前路径
		$page_length=5;                 //6.每页显示的页数
		
	
		//调用高级分页类输出
		$p   = new AdvPage( $count, $listRows ); //-------------1.总的记录数 2.每页显示的条数 3.参数 如info=350;
		$page= $p->show_ajax($dom_id, $url, $page_length); //--------------输出分页样式
		//=====end 分页=====

		//要据当前面面显示相应条数标签
		$list = $this->getLpListCache($p->firstRow , $listRows, $count);  //获取楼盘缓存列表信息
		//$list = $Form->where($map)->field($fields)->order('id desc')->limit($p->firstRow.','.$listRows)->findAll();
		//dump($list);
	    if($list){
			$this->assign('list',$list);
			$this->assign('page',$page);	
		}
		//Session::set('oldPage', $_GET['p']);  //已保存的用户当前页
		//Session::set('oldTime', time());    //已保存的用户当前操作时间

  //-------获取多点地图坐标
     if($list!=false){

		$list_json = $this->makePoint($list);  //生成坐标JSON
		//$this->assign('list_json',$list_json);

		//---推广字符串---start
		$arrTG = '182,19';   // A('Ad')->TGID
		if($count == 1){
			$arrTG .= ','.$list[0]['id'];   //若只一个，也显示
		}
		$this->assign('arrTG',$arrTG);
		//---推广字符串---end

		echo "<script>var mapJson=".$list_json."; </script>";  //传给页面
	}
  //-------显示地图	多点结束
		$this->display('lp_list_ajax');

	}

//---生成地图坐标JOSN ---	
	public function makePoint($list){
		 $arrMap = array();
		 $list_json = '[]';
		 if($list!=false){
	/*
			$MapDao = M('Map');  //查地图表 
			$info_id_arr= array();
			foreach ($list as $vomap){  //ID组成字符串
				$info_id_arr[] = intval($vomap['id']);
			}
			$condition['info_id'] = array('in', $info_id_arr);
			//$condition['class_id']= 7; //表
			$pointArr = $MapDao->where($condition)->findAll();
	*/
			$LP = M('New_loupan');
			$arrMap = array();
			foreach($list as $k=>$v){
				if($v['point_x']!='' && $v['point_y'] !=''){
					$arrMap[$k]['x']    = $v['point_x'];
					$arrMap[$k]['y']    = $v['point_y'];
					$arrMap[$k]['id']   = $v['id'];
					$arrMap[$k]['name'] = $v['title'];
					$arrMap[$k]['price']= intval($v['lpprice']);
					$rs_lp = $LP->field('lptel')->where('info_id='.$v['id'])->find();
					$arrMap[$k]['tel']  = trim($rs_lp['lptel']);
				}
			}
			$list_json = json_encode($arrMap); //转成json
		}
		return $list_json;
	}

//---搜索常用关键词缓存策略---
//---keyStr 关键词组合 形如[range_id-key-lp_wuyetype],  list_cache 缓存内容
	public function setLpListCache(){
		$map = Session::get('clientMap');  //已保存的用户查询条件
		$keyStr = serialize($map);
		$lp_cacheName = urlencode('LP'.$keyStr);  //缓存名称
//echo $lp_cacheName;
		$words = "/1水榭花都|2绿地新都会/";   //维护常用关键字
		
		//if( preg_match($words,$keyStr)==1 && !S($lp_cacheName) )
		if( !S($lp_cacheName) ){ 
			//暂不启用常用关键字，不存在则缓存
			$Form   = M("View_allsite");
			$fields = 'info_id as id,title,range_id,lp_state,lpprice,lpwuyetype,lp_zhutui,lpnosalecount,point_x,point_y';
			$list_cache  = $Form->where($map)->field($fields)->order('lpyouxian desc, lp_state asc')->limit('0,120')->findAll(); //缓存120条

			try{
				C('DATA_CACHE_TIME', 1800); // 设置缓存有效期
				
				S($lp_cacheName, $list_cache, C('DATA_CACHE_TIME'));  //查询结果缓存
				if(!S($lp_cacheName)){ 
					 throw new Exception('缓存未完成！');
				}
			} catch (Exception $e) {
				Log::write('搜索结果缓存：'.$e->getMessage(), Log::ERR); //直接写入日志信息
			}
			$rs = true;
		}else{

			$rs = false;
		}
		return $rs;
	}


//---获取楼盘搜索结果缓存---
//---start:int 开始, len:int 截取长度, rs_count:int 结果统计
	public function getLpListCache($start, $len, $rs_count){
		$map = Session::get('clientMap');
		$keyStr = serialize($map);
		$lp_cacheName = urlencode('LP'.$keyStr);  //缓存名称
		$lp_cache = S($lp_cacheName);  //获取缓存
		$is_use_cache = false;         //是否使用缓存
		if(count($lp_cache) >= $rs_count){
			$is_use_cache = true;
		}
		if(count($lp_cache) >= $start+ $len){
			$is_use_cache = true;
		}
		//dump($is_use_cache);
		
		if($lp_cache &&  true === $is_use_cache ){
			//缓存存在且元素够显示
			$list = array_slice ( $lp_cache, $start , $len );	
	    }else{
			$Form   = M("View_allsite");
			$fields = 'info_id as id,title,range_id,lp_state,lpprice,lpwuyetype,lp_zhutui,lpnosalecount,point_x,point_y';
			$list   = $Form->where($map)->field($fields)->order('lpyouxian desc, lp_state asc')->limit($start.', '.$len)->findAll(); 
		}
		//如果条数不足5条则补广告 --- start
		$curr_count = count($list);
		if( $curr_count < 5 ){
			$list_ad = A('Ad')->getAdList();
			foreach($list as $li_v){
				$li_arr[] = $li_v['id'];
			}
			foreach($list_ad as $k=>$v){
				if( in_array($v['id'], $li_arr ) ){
					unset($list_ad[$k]);
				}
			}
			$list_ad = array_slice ( $list_ad, 0 , (5 - $curr_count) );
			if($curr_count > 0){
				$list = array_merge($list, $list_ad); //合并
			}else{
				$list = $list_ad;  //没找到时直接显示广告
			}
		}
		//dump($list);
		//如果条数不足5条则补广告 --- end
		return $list;
	}

//---获取指定ID开始的楼盘搜索结果---
//---firstPage:int 开始, len:int 截取长度, startID:int 开始
	public function getLpListAdv($firstPage, $len, $startID){
		$map = Session::get('clientMap');
		$map['id'] = array('elt', $startID);      //小于等于
		$keyStr = serialize($map);
		$lp_cacheName = urlencode('LP'.$keyStr);  //缓存名称

		if(S($lp_cacheName)){
			$list = array_slice ( S($lp_cacheName), $firstPage , $len );	
	    }else{
			$Form   = M("View_allsite");
			$fields = 'info_id as id,title,range_id,lp_state,lpprice,lpwuyetype,lp_zhutui,lpnosalecount,point_x,point_y';
			$list   = $Form->where($map)->field($fields)->order('lpyouxian desc, lp_state asc')->limit($firstPage.', '.$len)->findAll(); 
		}
		return $list;
	}


}
?>