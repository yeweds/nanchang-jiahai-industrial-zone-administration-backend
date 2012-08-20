<?php
/**
 +------------------------------------------------------------------------------
 * General 传统楼盘类
 +------------------------------------------------------------------------------
 * @author 熊彦
 +------------------------------------------------------------------------------
 */
class GeneralAction extends GlobalAction{

//-------------------------------------------------------------------------
//--- 传统楼盘页面 ---
//-------------------------------------------------------------------------
//获取索引中的ID, 用于搜索
	public function get_result_id($key){
			$key = strtolower($key);
			if (!$key) return;

			$Table = M("Info");
			$map['head_py'] = array('like',"%".$key."%");
			$map['pinyin']  = array('like',"%".$key."%");
			$map['title']   = array('like',"%".$key."%");
			$map['_logic']  = 'or';
			$where['_complex'] = $map;
			$where['class_id']  = array('eq',7); //只查楼盘

 			$field = 'info_id';
			$list = $Table->where($where)->field($field)->limit('0,30')->findall();
			$vo = array();
			if($list){
				foreach($list as $v){
					$vo[] = $v['info_id'];
				}
			}
			//查楼盘表 -- S
			$Lp   = M("New_loupan");
			$map_['lp_full_name']  = array('like',"%".$key."%");
			$list_lp = $Lp->where($map_)->field($field)->limit('0,5')->findall();
			if($list_lp){
				foreach($list_lp as $v){
					$vo[] = $v['info_id'];
				}
			}
			//查楼盘表 -- E
			return $vo;
	}

	//楼盘列表页
	public function ct_list(){
		$type = trim($_GET['search_type']);
		$key  = urldecode($_GET['key']);
		if(!empty($key)){
			$where['lpname']  = array('like',"%".$key."%");
			//$where['lp_full_name']  = array('like',"%".$key."%");
			$info_id_arr = $this->get_result_id($key);  //获取ID
			if( count($info_id_arr)>0 ){
				$where['info_id']   = array('in', $info_id_arr);  //通过INFO_ID查
			}
			$where['_logic']  = 'or';
			$map['_complex'] = $where;
		}else{
			$map = null;
		}

		$range_id = 0 ;        //默认不限
		$map['is_ershou'] = 0 ; //非2手

		if(isset($_GET['str'])){
			$str = trim($_GET['str']);
			$tmp_map = explode('-', $str);
		}
		if(count($tmp_map)>2){
			$this->error('参数有误!');
		}
		if($tmp_map[0] == 'p'){
			switch($tmp_map[1]){
				case 1 : $price_range = '0,4000'; break;
				case 2 : $price_range = '4000,6000'; break;
				case 3 : $price_range = '6000,8000'; break;
				case 4 : $price_range = '8000,10000'; break;
				case 5 : $price_range = '10000,99999'; break;
				//case 6 : $price_range = '10000,99999'; break;
				default :
					 $price_range = '0,99999'; //不限
			}
			$map['lpprice'] = array('between', $price_range );
		}
		if(isset($_GET['lp_price_range'])){
			$priceArr = explode('-', $_GET['lp_price_range']);
			$min_price = intval( trim($priceArr[0]) );
			$max_price = $priceArr[1]==0 ? 99999: intval( trim($priceArr[1]) );  //缺省最大价格
			$map['lpprice']  = array('between', $min_price.','.$max_price);
		}

		if($tmp_map[0] == 'a'){
			$map['range_id'] = $tmp_map[1];  //区域
		}else if(!empty($_GET['range_id'])){
			$range_id = intval($_GET['range_id']);
			$map['range_id'] = array('eq', $range_id);
		}

		if(!empty($_GET['lp_wuyetype'])){
			$lp_wuyetype = $_GET['lp_wuyetype'];
			$map['lpwuyetype'] = array('like', "%".$lp_wuyetype."%");
		}
		if(!empty($_GET['room_hx'])){
			switch($_GET['room_hx']){
				case '1' : $room_hx = '一居'; break;
				case '2' : $room_hx = '二居'; break;
				case '3' : $room_hx = '三居'; break;
				case '4' : $room_hx = '四居'; break;
				case '5' : $room_hx = '五居'; break;
				case '6' : $room_hx = '六居'; break;
				case 'gt7' : $room_hx = '七居'; break;
				default :
					 $room_hx = ''; //不限户型
			}
			$map['lp_zhutui'] = array('like', "%".$room_hx."%");
		}
		if(!empty($_GET['room_area'])){
			$areaArr = explode('-', $_GET['room_area']);
			$min_area = intval(trim($areaArr[0])) ;
			$max_area = intval(trim($areaArr[1])) ;  //缺省最大面积
			$map_room['rarea'] = array('between', $min_area.','.$max_area);
			$map_room['_string'] = " (rstatus='可预售') or (rstatus='商品房退房上市') "; //只查可售房源
			$room = M('New_room')->field('lpid,info_id')->where($map_room)->group('lpid')->findall();
			foreach($room as $v){
				if($v['info_id']){
					$room_arr_id[] = $v['info_id']; 
				}
			}
			//echo M('New_room')->getlastsql();
			$map['info_id'] = array('in', $room_arr_id);
			//$map['_string'] = ' ( lp_min_area <= '.$min_area.')  OR ( lp_max_area >= '.$max_area.') ';
		}
		if($_GET['lp_state']==1 ){
			$lp_state = '在售';  //销售状态
			$map['lp_state']  = 1;
		}else if ($_GET['lp_state']==2){
			$lp_state = '待售';
			$map['lp_state']  = 2;
		}else{
			$lp_state = '所有';  
		}

		//dump($map);
		if(isset($_GET['ord'])){
			$ord_str = trim($_GET['ord']);
			$tmp_ord = explode('-', $ord_str);
			if($tmp_ord[0] == 'p'){
				$ord = 'lpprice '.$tmp_ord[1];
			}elseif($tmp_ord[0] == 'rk'){
				$ord = 'inserttime '.$tmp_ord[1]; //入库时间
			}elseif($tmp_ord[0] == 'kp'){
				$ord = 'inserttime desc,lp_state asc,range_id asc';    //开盘时间
			}else{
				$ord = 'lptimeb '.$tmp_ord[1];    //开盘时间
			}
		}else{
			$ord = 'lpyouxian desc,lp_state asc,range_id asc';
		}

		$Table   = M("New_loupan");

		//=====显示分页=======
		import("ORG.Util.Page");
		$count = $Table->where($map)->count(); //1.总的记录数
		$listRows = 10;					//2.每页显示的条数
		$p  = new Page( $count, $listRows );
		$page= $p->show(); 
		//=====end 分页=====

		//要据当前面面显示相应条数标签
		$list = $Table->where($map)->order($ord)->limit($p->firstRow.','.$p->listRows)->findall();  //获取楼盘
		//echo $Table->getlastsql();
		$Attach  = A('Attach'); //实例化附件类
		$Reviews = M('Reviews');
		$TableMx = M('Reviews_mx');
	    if($list){
			foreach($list as $k=>$v){
					$info_id = $v['info_id'];
					//--默认图片
					$att_rs = $Attach->getAttachByInfoId($info_id, $class_id = 0, $is_defautl=1);
					//if($att_rs != 'no.png'){
					//	$att_rs = "thumb_".$att_rs;  //不用缩略图
					//}
					$list[$k]['default_pic'] = $att_rs;
					//--计算总附件数
					$list[$k]['count_pic'] = $Attach->getAttachCount($info_id);
					//--总微评论数
					$map_r['info_id'] = $info_id;
					$rs = $Reviews->field('id')->where($map_r)->find();
					$map_p['reviews_id'] = intval($rs['id']);
					$list[$k]['sum_reviews'] = $TableMx->where($map_p)->count();  //总微评论数
			}
			$this->assign('list',$list);
			$this->assign('page',$page);	
		}
        $area_list = A('Index')->area_list();   //取得区域列表
		$area_list = array_slice ( $area_list, 0 , 10 );
		$this->assign('area_list',$area_list); 
		
		//最新开盘
		$kp_list = $Table->field('lpname,info_id')->where(null)->order('lptimeb desc')->limit('0,6')->findall();  //获取楼盘
		$this->assign('kp_list',$kp_list);

		//获取热门楼盘
		$hotLp = $this->hot_lp_inc();
		$this->assign('hot_list',$hotLp);
/*		//获取友情链接
		$foot_links = getLinks($act=1,$num=20); // 1文字
		$this->assign('foot_links',$foot_links);
*/
		$this->assign('TitleMsg','楼盘列表');
		$this->assign('userInfo', $this->userInfo);  //用户信息
		$this->display();
	}


	//传统楼盘详细页
	public function ct_view(){
		$info_id = intval($_GET['info_id']);  
		//halt($info_id);
		$Table = D('Info');
		$Table->setInc('hits', 'info_id='.$info_id, '1');  //更新人气
		
		//记录点击历史,7代表楼盘
		$this->get_hits($click_id=$info_id,$class_id=7,$table_name='new_loupan');		
		
		//计算土地成本
		$map_gtj['info_id']   = $info_id;
		//$map_gtj['ispublish'] = 1;
		$rs = M('Gtj')->field('id,title,bianhao,chengbenjia')->where($map_gtj)->find();
		$td_chengben = ($rs ? $rs['chengbenjia'].'元/平方米' : '未知');
		$this->assign('td_chengben', $td_chengben);
		$this->assign('td_vo', $rs);  //土地信息
		
		$this->view_inc($info_id);  //调信息页共用
		unset($map);
		$map['info_id'] = $info_id;
		$map_rs = M('News_mapping')->field('info_id,news_id')->where($map)->findAll();
		foreach($map_rs as $v){
			$news_id_arr[] = $v['news_id'];
		}
		$map_news['id'] = array('in',$news_id_arr);
		$map_news['ispublish'] = 1;
		$news_list = M('News')->field('id,class_id,title,add_time')->where($map_news)->order('id desc')->limit('0,9')->findall();  //新闻列表
		if($news_list){
			$this->assign("news_list", $news_list);
		}
		
		$same_price_list = $this->get_same_lp($info_id, $same_type='price', $num=6);  //同价位
		$same_range_list = $this->get_same_lp($info_id, $same_type='range_id', $num=6);  //同区域
		$same_type_list = $this->get_same_lp($info_id, $same_type='lp_type', $num=6);  //同类型
		$same_point_list = $this->get_same_lp($info_id, $same_type='map_point', $num=9);  //同坐标
		$this->assign("same_price_list", $same_price_list);
		$this->assign("same_range_list", $same_range_list);
		$this->assign("same_type_list", $same_type_list);
		$this->assign("same_point_list", $same_point_list);

		unset($map);
		$map['info_id'] = $info_id;
		$map['prolisence']= array( 'neq', '' );
		$vo_prolisence = M('New_loudong')->field('proname,prolisence,prosaletime')->where($map)->order('prosaletime desc')->limit(1)->find();
		$this->assign("prolisence", $vo_prolisence); //许可证

		if(is_file("./3d/".$info_id."/index.html")){
			$this->assign("zxkf_info_id", $info_id);   //用于给内内嵌iframe传参
		}
		
		$tg_list = $this->getTuiguang($info_id);
		$this->assign("bg_list", $tg_list['bg_list']);
		$this->assign("dx_list", $tg_list['dx_list']);
		$this->assign('TitleMsg', '详细信息,实景看房');
		$this->display();

	}

	//传统楼盘详细页--价格分析
	public function ct_jgfx(){
		$info_id = intval($_GET['info_id']);  
		$this->view_inc($info_id);  //信息页共用

		$list = array();
		$Table = M('Price_record');
		unset($map);
		$map['info_id'] = 	$info_id;
		$currLp = M('New_loupan')->field('lpname,lpprice')->where($map)->find();      //楼盘当前价格等信息
		$this->assign("currLp", $currLp);
		//dump($currLp);
		
		$map['lptype']  = 	1;  //住宅
		$list = $Table->where($map)->order('month desc')->limit('0,6')->findall();
	//计算涨幅 
		$upSpan = 0;
		if(count($list)>=2){
			$upSpan  = 100 * ( $list[0]['month_price'] - $list[1]['month_price'] ) / $list[1]['month_price'];
			$upSpan  = round($upSpan, 2);    
		}
		//dump($upSpan);
		foreach ($list as $k=>$v){
			//$arrData[$k]['1'] = date('Y-m',$v['add_time']);  //时间
			$arrData[$k]['1'] = $v['month'];  //时间
			$arrData[$k]['2'] = $v['month_price'];  //该楼盘均价
			$arrData[$k]['3'] = $v['price_city'];  //南昌市均价  //$arrData[$k]['3'] = 4800;  //南昌市均价
			$arrData[$k]['4'] = $v['price_area'];  //该区域均价
		}
		krsort($arrData);   //键值从小到大排序
		//echo $Table->getlastsql();
		//dump($arrData);

		import('@.Other.Charts');  
		$Charts = new Charts();

		//Initialize <graph> element
		$strXML = "<graph caption='价格分析' numberPrefix='' formatNumberScale='5' rotateValues='5' decimalPrecision='0' baseFont='宋体' baseFontSize='12' outCnvBaseFontSize='12' showValues='0' yAxisMinValue='1500' >";
		//Initialize <categories> element - necessary to generate a multi-series chart
		$strCategories = "<categories>";
		//Initiate <dataset> elements   2AD62A
		$strDataCurr = "<dataset seriesName='该楼盘均价' color='1D8BD1'>";
		$strDataPrev = "<dataset seriesName='南昌市均价' color='F1683C'>";
		$strDataArea = "<dataset seriesName='该区域均价' color='2AD62A'>";
		
		//Iterate through the data  
		foreach ($arrData as $arSubData) {
			//Append <category name='...' /> to strCategories
			$strCategories .= "<category name='" . $arSubData[1] . "' />";
			//Add <set value='...' /> to both the datasets
			$strDataCurr .= "<set value='" . $arSubData[2] . "' />";
			$strDataPrev .= "<set value='" . $arSubData[3] . "' />";
			$strDataArea .= "<set value='" . $arSubData[4] . "' />";
		}
		
		//Close <categories> element
		$strCategories .= "</categories>";
		
		//Close <dataset> elements
		$strDataCurr .= "</dataset>";
		$strDataPrev .= "</dataset>";
		$strDataArea .= "</dataset>";
		
		//Assemble the entire XML now
		$strXML .= $strCategories . $strDataCurr . $strDataPrev . $strDataArea ."</graph>";
		
		//Create the chart - MS Line Chart with data contained in strXML
		$strCharts =  $Charts->renderChart("__PUBLIC__/FusionCharts/FCF_MSLine.swf", "", $strXML, "productSales", 600, 300); //生成线型图

		$this->assign("strCharts", $strCharts);
//---最近销售记录---
		$Table_day = M('New_loupan_temp');
		unset($map);
		$map['info_id'] = 	$info_id;
		$sellRecord = $Table_day->where($map)->limit('0,5')->order('id desc')->findAll();
		if($sellRecord){
			$Table_sell = M('Sell');
			$Table_room = M('New_room');
			foreach( $sellRecord as $k=>$v ){
				$map_['lpid'] = $v['lpid'];
				$map_['sell_time'] = $v['inserttime'];
				$sellRecord[$k]['sell_num']  = $Table_sell->where($map_)->count();

				$rs = $Table_room->where($map_)->sum('rarea');  //面积
				$sellRecord[$k]['sell_area'] = ($rs == null ? 0: $rs) ;
			}
		}
		$this->assign("sellRecord", $sellRecord);
		//dump($sellRecord);
		$this->assign("upSpan", $upSpan);

		$this->display();
	}

	//传统楼盘详细页--生活配套
	public function ct_shpt(){
		$info_id = intval($_GET['info_id']);
		$this->view_inc($info_id);  //信息页共用
		$this->display();
	}

	//传统楼盘详细页--在线看房
	public function ct_zxkf(){
		$info_id = intval($_GET['info_id']);
		$this->view_inc($info_id);  //信息页共用
		$this->assign('TitleMsg','3D在线看房');
		if(is_file("./3d/".$info_id."/index.html")){
			$this->assign("zxkf_info_id", $info_id);   //用于给内内嵌iframe传参
		}
		$this->display();
	}

	//传统楼盘详细页--报名看房
	public function ct_bmkf(){
		$info_id = intval($_GET['info_id']);
		$this->view_inc($info_id);  //信息页共用
		$this->display();
	}

	
	//用于传统非列表页共用
	public function view_inc($info_id){
		$Table = D('Info');
		$map['info_id'] = $info_id;
		$vo = $Table->where($map)->find();
		unset($map);
		if(!$vo) $this->error('找不到该信息');
		$class_id = intval($vo['class_id']);
		$area_vo = M('Area')->where('id='.$vo['range_id'])->find();
		$vo['area_name'] = ($area_vo ? $area_vo['name'] : '未知/其它');
		$this->assign("vo", $vo);

		$Class = D("Class");
		$vo_cls = $Class->where('id='.$class_id)->find();
		if(!$vo_cls) $this->error('找不到附加表');
		$Table_add = M( trim($vo_cls['table_name']) ); //实例化附加表数据模型类
		$map['info_id'] = intval($info_id);
		$vo_add = $Table_add->where($map)->find();
	//dump($vo);
		if($vo_add['lp_tese'] != ''){
			$vo_add['lp_tese'] = explode(',', $vo_add['lp_tese']);   //楼盘特色
		}
		$LP = A('Loupan');
		//---取得该楼盘更新时间---
		$vo_add['lp_update_time'] = $LP->lp_update_time($info_id);  
		//---取得该楼盘已看及想看人数---
		$seeArr = $LP->lp_order_see($info_id);  
		$vo_add['seecount'] = $seeArr['seecount'] ;
		$vo_add['wantsee']  = $seeArr['wantsee'] ;
		
		$vo_add['shiyi_all'] = strip_tags($vo_add['shiyi']) ;
		$vo_add['shiyi']  =  mb_substr($vo_add['shiyi_all'],0,180,'utf8')."..";

		$this->assign("vo_add", $vo_add);

		//---取得区域列表
        $area_list= A('Index')->area_list();
		$this->assign('area_list',$area_list); 
		$this->assign('features_list',C('cfg_features')); //用于特色搜索列表
		//---取得编辑点评列表
        $map['info_id'] = $info_id;  
        $vo_reviews = M('Reviews')->field('id')->where($map)->find();
		unset($map);
		$map['reviews_id'] = intval($vo_reviews['id']);
		$map['content']    = array('neq','该网友仅打了分，未留下任何高见.');  //内容不为空
		$map['is_hide']    = 0; //非禁用
        $reviews_list = M('Reviews_mx')->where($map)->order('is_editor DESC, id DESC')->limit('0,16')->findAll(); // 查点评明细
		if($reviews_list){ $this->assign('reviews_list' ,$reviews_list); }
		//dump($reviews_list);    //2011-7-15
/*
		$goodReviews = A('Reviews')->getReviewsList($g_or_b = 'g', $num = 1, $is_editor = 1, $type=1, $vo_reviews['id']); // 好评明细;
        $badReviews  = A('Reviews')->getReviewsList($g_or_b = 'b', $num = 1, $is_editor = 1, $type=1, $vo_reviews['id']); // 差评明细;
		if($goodReviews){ $this->assign('goodReviews',$goodReviews[0]); }
		if($badReviews){ $this->assign('badReviews' ,$badReviews[0]); }
*/
		if( !is_file("./Public/Xml/i".$info_id.".xml") ){
			A('Reviews')->makePointXml($info_id);  // xml文件不存在则生成
		}
		//--默认图片
		$Attach = A('Attach');
		$att_rs = $Attach->getAttachByInfoId($info_id, $class_id = 0, $is_defautl=1);
		//if($att_rs != 'no.png'){
		//	$att_rs = "thumb_".$att_rs;  不用缩略图
		//}
		$default_pic = "<img src=\"".$att_rs."\" width=\"255\" height=\"205\" alt=\"缩略图\" />";
		$this->assign('default_pic',$default_pic); //默认缩略图片
		//--计算总附件数
		$count_pic = $Attach->getAttachCount($info_id);
		$this->assign('count_pic',$count_pic);

		$AttCid  = array(13,14,15,16,17,18,35);
		$t_attach = M('Attach');
		$map_att = array();
		foreach($AttCid as $attv){
			$map_att['info_id']  = $info_id;
			$map_att['class_id'] = $attv;
			$count_att[$attv] = $t_attach->where($map_att)->count();
		}
		$this->assign('count_hx',$count_att[13]); //户型图
		$this->assign('count_sj',$count_att[14]); //实景图
		$this->assign('count_xg',$count_att[15]); //效果图
		$this->assign('count_gh',$count_att[16]); //规划图
		$this->assign('count_wz',$count_att[17]); //位置图
		$this->assign('count_pt',$count_att[18]); //配套图
		$this->assign('count_bg',$count_att[35]); //报广图

		//计算涨幅
		//$upSpan = $LP->getUpSpan($info_id);
		//$this->assign('up_span',$upSpan);
		
		//$sell_month_ph = $LP->lp_month();   //本月排行
		//$this->assign('sell_month_ph',$sell_month_ph);
		//--记录浏览记录
		A('Interaction')->in_browser_record($info_id);
	
		$this->assign('flashXmlUrl', "i".$info_id.".xml?".time()); //路径后加当前时间，以免重复

		$this->assign('userInfo', $this->userInfo);  //用户信息

		$chewei_remark = $this->chewei_remark($info_id, $parking_num= $vo_add['lpcar']); //车位说明
		$this->assign('chewei_remark', $chewei_remark); 
		$rongjilv_remark = $this->rongjilv_remark($vo_add['lprongjilv']); //容积率说明
		$this->assign('rongjilv_remark', $rongjilv_remark); 
		$wuyefei_remark = $this->wuyefei_remark($vo_add['lpwuyefei']);  //物业费说明
		$this->assign('wuyefei_remark', $wuyefei_remark); 
		$zdmj_remark = $this->zdmj_remark($vo_add['zhandi_area']);   //占地面积
		$this->assign('zdmj_remark', $zdmj_remark); 

	}

	public function ct_shpt_inc() //生活配套内嵌
	{
		$id = intval($_GET['info_id']);
		$Table = D('Info');
		$map['info_id'] = $id;
		$vo = $Table->where($map)->find();
		unset($map);
		$map['info_id'] = $id;
		$vo_add = M('New_loupan')->field('lpaddr,lptel,lpsheshi')->where($map)->find();
		unset($map);
		
        $condition['info_id'] = array('eq', $id);
        //$condition['class_id']= 7; //表
		$point = M('Map')->field('point_x, point_y')->where($condition)->find();  //查地图表
		if($point){
			$vo['point_x'] = $point['point_x'];
			$vo['point_y'] = $point['point_y'];
		}
		//dump($vo);
		$this->assign("vo", $vo);
		$this->assign("vo_add", $vo_add);
		$this->display();
	}

//人工荐房表单
	public function rg_jianfang(){
		$area_list = A('Index')->area_list();   //取得区域列表
		$this->assign("area_list", $area_list);
		$wuyetype  = C('cfg_wuyetype');         //物业类型
		$this->assign("wuyetype", $wuyetype);

		$this->display();
	}

//人工荐房列表，用于首页
	public function rg_jianfang_list(){

		$area_list = A('Index')->area_list();   //取得区域列表
		$this->assign("area_list", $area_list);
		$wuyetype  = C('cfg_wuyetype');         //物业类型
		$this->assign("wuyetype", $wuyetype);
		
		//查询列表
		$Table = M('Rg_jianfang');
		$listRows="20";
		import("ORG.Util.Page");
		$count=$Table->count();
		$p=new Page($count,$listRows);
		$page=$p->show();

	    $list =$Table->order("id desc")->limit($p->firstRow.','.$p->listRows)->findall();
		if($list!==false){		
			$this->assign('list',$list);
			$this->assign('page',$page);
		}
		$this->display();
	}

//保存人工荐房
	public function insert_rgjf(){

		/*if( md5($_POST["CheckCode"]) != $_SESSION['verify']){
			$this->error('验证码错误！请刷新验证码再重新提交.');
		}*/
		$Guest = M("Rg_jianfang");  //人工荐房保存
		// 检查数据插入
		$data = $Guest->create();
		if(false === $data) {
        	$this->error($Guest->getError());
        }
		$uname  = trim($_POST['uname']);
		$tel    = trim($_POST['tel']);
		if(empty($uname) || empty($tel)){
			ajaxMsg('请留下您的联系方式，以便我们荐房人员经过专业分析后将最适合您的楼盘告知您.', 'error');
			return;
		}
		// 处理数据安全
		//$data['expect'] = htmlspecialchars(trim($_POST['expect']));
		$data['add_time'] = time();

		if($Guest->add($data)) {
			//$this->assign('waitSecond',10);	
			//$this->assign('jumpUrl',__APP__.'/Index');
			ajaxMsg('人工荐房申请提交成功，请耐心等待本站客服与您联系！', 'success');
			return;
		} else {
			ajaxMsg('人工荐房申请提交失败,请联系管理员！', 'error');
			return;
		}
	}

	//热门楼盘，用于传统页下方(大家都在看)
	public function hot_lp_inc(){
		$list = S('HotLpInc');
		if(!$list){
			$Table   = M("New_loupan");
			$map = null;
			$list = $Table->field('info_id,lpname,lpprice,lp_zhutui')->where($map)->order('lpyouxian desc')->limit('0,14')->findall();  
			$Attach  = A('Attach'); //实例化附件类
			if($list){
				foreach($list as $k=>$v){
						$info_id = $v['info_id'];
						//--默认图片
						$att_rs = $Attach->getAttachByInfoId($info_id, $class_id = 0, $is_defautl=1);
						//if($att_rs != 'no.png'){
						//	$att_rs = "thumb_".$att_rs;  //不用缩略图
						//}
						$list[$k]['default_pic'] = $att_rs;
				}
				S('HotLpInc', $list, 3600*24*7 );  //缓存7天	
			}
		}
		return $list;
	}

//获取同类型楼盘
	public function get_same_lp($info_id, $same_type='price', $num=6){
		$info_id  = intval($info_id);
		$Lp = M('New_loupan');
		$map['info_id'] = $info_id;
		$vo = $Lp->where($map)->find();
		if(!$vo){
			return  false;
		}
		$map_s['info_id'] = array('neq', $info_id );  //排除自己
		if($same_type == 'price'){
		  //同价位
		  $price_min = ($vo['lpprice']>0 ? $vo['lpprice']-1000 : 0 );
		  $price_max = ($vo['lpprice']>0 ? $vo['lpprice']+1000 : 1000 );
		  $map_s['lpprice'] = array('between', $price_min.','.$price_max );
		  $list = $Lp->field('info_id,lpname,lpprice,lp_zhutui')->where($map_s)->order('lpyouxian desc')->limit('0,'.$num)->findall();
		}else if($same_type == 'range_id'){
		  //同区域
		  $map_s['range_id'] = array('eq', intval($vo['range_id']) );
		  $list = $Lp->field('info_id,lpname,lpprice,range_id,lp_zhutui')->where($map_s)->order('lpyouxian desc')->limit('0,'.$num)->findall();
		}else if($same_type == 'lp_type'){
		  //同类型
		  $map_s['lpwuyetype'] = array('like', "%".$vo['lpwuyetype']."%");
		  $list = $Lp->field('info_id,lpname,lpprice,lp_zhutui')->where($map_s)->order('lpyouxian desc')->limit('0,'.$num)->findall();

		}elseif($same_type == 'map_point'){
		  //坐标相近(周边楼盘)
		  $Map = M('Map');
		  $curr_where['info_id'] = $info_id;
		  $curr_where['class_id']= 7 ; // 仅楼盘
		  $curr_map = $Map->field('info_id,point_x,point_y')->where($curr_where)->find();
		  $x_min = ($curr_map['point_x'] - 0.020 );
		  $x_max = ($curr_map['point_x'] + 0.020 );
		  $y_min = ($curr_map['point_y'] - 0.020 );
		  $y_max = ($curr_map['point_y'] + 0.020 );
		  unset($curr_where);
		  $curr_where['class_id']= 7 ; // 仅楼盘
		  $curr_where['point_x'] = array('between', $x_min.','.$x_max );
		  $curr_where['point_y'] = array('between', $y_min.','.$y_max );
		  $arr = $Map->field('info_id,point_x,point_y')->where($curr_where)->findAll();
		  //dump($arr);
		  foreach($arr as $v){
			if($v['info_id'] != $info_id)  $info_arr[] = $v['info_id'];
		  }
		  $map_s['info_id'] = array('in', $info_arr );
		  $list = $Lp->field('info_id,lpname,lpprice,lp_zhutui')->where($map_s)->order('lpyouxian desc')->limit('0,'.$num)->findall();
		}else{
			$list = false;
		}
		//echo $Lp->getlastsql();
		return $list;
		
	}

//---用于小棉袄---
	//获取可销售数据明细
	public function can_sell_mx(){
		$info_id  = intval($_GET['info_id']);
		//$info_id  = 186;

		$map_m['info_id'] = $info_id;
		//$id_mapping = M('Id_mapping')->field('info_id,gold_url,xjx_url,ncx_url,range_id')->where($map_m)->find();
		$vo = M('New_loupan')->where($map_m)->find();
		
		$Room = M('New_room');
		//$r_map['lpid'] = $v['lpid'];
		//$r_map['roomid'] = $v['roomid'];
		$map_r['info_id'] = $info_id;
		//$map_r['rstatus'] = "可预售";
		$map_r['_string'] = " (rstatus='可预售') or (rstatus='商品房退房上市') ";

		if(isset($_GET['type'])){
			$type = trim($_GET['type']);
			if($vo['range_id'] == 13) {
				$this->error('南昌县的楼盘暂时无法区分住宅及非住宅！');
				return;
			}else if($vo['range_id'] == 14){
				//新建县
				if($type == 'zhuzhai'){
					$map_r['rtype'] = '住宅';
				}else if($type == 'fei_zhuzhai'){
					$map_r['rtype'] = '非住宅';
				}
			}else{
				if($type == 'zhuzhai'){
					$map_r['rtype'] = 0;
				}else if($type == 'fei_zhuzhai'){
					$map_r['rtype'] = 1;
				}
			}
		}
		if(isset($_GET['area'])){
			$areaArr = explode('-', $_GET['area']);
			$min_area = intval(trim($areaArr[0])) ;
			$max_area = intval(trim($areaArr[1])) ;  //缺省最大面积
			$map_r['rarea'] = array('between', $min_area.','.$max_area);
		}

		//=====显示分页=======
		import("ORG.Util.Page");
		$count = $Room->where($map_r)->count(); //1.总的记录数
		$listRows = 25;					//2.每页显示的条数
		$p  = new Page( $count, $listRows );
		$page= $p->show(); 
		//=====end 分页=====
		$fields = 'info_id,lpid,roomid,proid,rroomtype,rarea,rroomnum,money';
		$rList = $Room->field($fields)->where($map_r)->limit($p->firstRow.','.$p->listRows)->order('id desc')->findAll();
		//echo $Room->getlastsql();
		if($rList){
			$Ld = M('New_loudong');
			foreach($rList as $k=>$v){
				$map_ld['info_id'] = $info_id;
				$map_ld['proid']   = $v['proid'];
				$rs  =  $Ld->field('prolisence,prosaletime,proname,avgprice,noavgprice')->where($map_ld)->find();
				$rList[$k]['prolisence'] =  ($rs? $rs['prolisence']: '');
				$rList[$k]['prosaletime'] =  ($rs? $rs['prosaletime']: '');
				$rList[$k]['proname'] =  ($rs? $rs['proname']: '');
				$rList[$k]['proprice'] =  ($rs? $rs['avgprice']: '');
				$rList[$k]['pronoprice'] =  ($rs? $rs['noavgprice']: '');
			}
			$this->assign("list", $rList);
		}
		//dump($rList);
		$this->assign("page", $page);
		$this->assign("info_id", $info_id); //楼盘info_id
		$this->assign("vo", $vo);

		$this->display();
	
	}
//更多开盘
	public function more_kanpan(){
		$Lp = M('New_loupan');
		$map['is_ershou'] = 0 ; //非2手
		$map['lptimee'] = array('neq','待定');
		$list = $Lp->field('info_id,lpname,lptimee')->where($map)->order('lptimee desc')->limit('0,16')->findAll();
		$this->assign("list", $list);
		//dump($list);
		$this->display();
	}

//---获取预售许可证号---
	public function get_prolisence(){
		$info_id = intval($_GET['info_id']);
		//$map['info_id'] = array('neq', 0 );
		$map['info_id'] = $info_id;
		$map['prolisence']= array( 'neq', '' );
		$list = M('New_loudong')->field('proname,prolisence,prosaletime')->where($map)->order('prosaletime desc')->limit(9)->findAll(); 
		//return $list;
		//dump($list);
		$this->assign("list", $list ); //

		$vo = M('New_loupan')->field('lpname,lpprice')->where('info_id='.$info_id)->find();
		$this->assign("lpname", $vo['lpname']); //楼盘名

		$this->display();
	}

//车位说明
	public function chewei_remark($info_id, $parking_num){
		$map['info_id'] = $info_id;
		$rs = M('New_loupan')->where($map)->find();
		$count = intval($rs['sum_hs']);
		if(!$count){
			$Room = M('New_room');
			$count = $Room->where($map)->count(); //1.总的记录数
		}
		$parking_num = intval($parking_num);
		$sub_num = $parking_num - $count;  //车位房间差
		if($parking_num==0){
			$remark = '未知'; //未知情况默认为适中
		}else if( $sub_num > 50){
			$remark = '<font color=green>充裕</font>';
		}else if( $sub_num <=50 && $sub_num >= -50 ){
			$remark = '<font color=#00cc66>平常</font>';
		}else{
			$remark = '<font color=red>紧张</font>';
		}
		return $remark;
	}

//容积率说明
	public function rongjilv_remark($num){
		if( $num > 3 ){
			$remark = '<font color=red>拥挤</font>';
		}else if( ($num <= 3.0 && $num >= 2.0) ){
			$remark = '<font color=#ff3333>生活</font>';  
		}else if( ($num < 3.0 && $num >= 1.0) ){
			$remark = '<font color=#00cc66>舒服</font>';  
		}else if($num==0){
			$remark = '未知'; //未知情况默认为适中
		}else{
			$remark = '<font color=green>很爽</font>';
		}
		return $remark;
	}

//物业费说明
	public function wuyefei_remark($num){
		if( $num >= 2.5 ){
			$remark = '<font color=red>贵哦</font>';
		}else if( ($num < 2.5 && $num >= 1.5)){
			$remark = '<font color=#00cc66>一般</font>';  //未知情况默认为适中
		}else if($num==0){
			$remark = '未知';
		}else{
			$remark = '<font color=green>便宜</font>';
		}
		return $remark;
	}

//占地面积
	public function zdmj_remark($area){
		//占地面积 100亩以下  小型社区（淡绿）
        //100-199亩  中型社区（淡绿）
        //200-399亩  大型社区（淡绿）
        //400亩以上  超大型社区（淡绿）
		//$map['info_id'] = $info_id;  //1平方米=0.0015亩
		$area_mu = $area * 0.0015 ;  //换算成亩
		if( $area_mu >= 400 ){
			$remark = '<font color=#00cc66>超大型社区</font>';
		}else if(($area_mu < 400 && $area_mu >= 200)){
			$remark = '<font color=#00cc66>大型社区</font>';  
		}else if(($area_mu < 200 && $area_mu >= 100)){
			$remark = '<font color=#00cc66>中型社区</font>';  
		}else if($area_mu==0){
			$remark = '未知';
		}else{
			$remark = '<font color=#00cc66>小型社区</font>';
		}
		return $remark;
	}

//获取最近报广和短信等推广信息
	public function getTuiguang($info_id){
		//$timeRange = strtotime($dataArr[0]).','.strtotime($dataArr[1]); //时间范围
	//近期报广-------
			$map_att['class_id'] = 35; //报广类
			$map_att['info_id'] = $info_id;
			//$map_att['upload_time']  =  array('between', $timeRange);
			$bg_list = M('Attach')->field('*')->where($map_att)->order('upload_time desc')->limit(8)->findAll();  //列表
			//$this->assign("bg_list", $bg_list); 
	//近期短信--start
			unset($map);
			//$map['inserttime'] = array('between', "'".$dataArr[0]."','".$dataArr[1]."'" );
			$map['info_id']    = $info_id;
			$dx_list = M('Xma_dx')->where($map)->limit(6)->order('inserttime desc')->findall();
			//$this->assign("dx_list", $dx_list); 
	//近期短信 --end
		    return array('bg_list'=>$bg_list, 'dx_list'=> $dx_list);
	}

	//生成公共静态文件
	public function make_public_html(){
		$htmlpath = 'Home/Tpl/default/Html/';
		C('HTML_FILE_SUFFIX', '.html');        // 默认静态文件后缀
		//--获取热门楼盘
		$hotLp = $this->hot_lp_inc();
		$this->assign('hot_list', $hotLp);
		$this->buildHtml($htmlfile='hot_lp_inc', $htmlpath, $templateFile='Public:hot_lp_inc');
		
		//strip_whitespace(content)
			
		//--获取友情链接
		$foot_links = getLinks($act=1,$num=30); // 1文字
		$this->assign('foot_links',$foot_links);
		$this->buildHtml($htmlfile='footer_link_inc', $htmlpath, $templateFile='Public:footer_link_inc');

		//--热点专题-->热点专题栏目ID：20
		unset($map);
		$News = M("News");
		$map['class_id'] = 20 ;
		$map['ispublish'] = 1 ;
		$map['pic_url'] = array('neq','') ;
		$lp_rdzt_index = $News->field('id,pic_url,title,remark,redirecturl')->where($map)->order('pr desc,id desc')->limit('0,1')->find();
		unset($map); 
		$map['class_id'] = 20 ;
		$map['ispublish'] = 1 ;
		$map['id'] = array('neq',$lp_rdzt_index['id'])  ;
        $rs = $News->where($map)->order('pr desc,id desc')->limit('0,6')->findAll();

		foreach($rs as $k=>$vo){
			$lp_rdzt[$k]['id'] = $vo['id'];
			$lp_rdzt[$k]['pic_url'] = $vo['pic_url'];
			if(mb_strlen($vo['title'],'utf8')>25){
				$lp_rdzt[$k]['title'] = mb_substr($vo['title'],0,25,'utf8').'..';//标题只显示25个
			}else{
				$lp_rdzt[$k]['title'] =  $vo['title'] ;
			}	
			$lp_rdzt[$k]['remark'] = trim(html2text(str_replace("http://","",$vo['remark'])));
			$lp_rdzt[$k]['remark'] = str_replace("%20","",$lp_rdzt[$k]['remark']);
			$lp_rdzt[$k]['redirecturl'] = $vo['redirecturl'];
		}		
		$dump['lp_rdzt_index'] = $lp_rdzt_index;
		$dump['lp_rdzt'] = $lp_rdzt;
        $dump['rdzt_cid'] = 20;
		//dump($dump);
		$this->assign('dump',$dump);
		$this->buildHtml($htmlfile='zhuangti_inc', $htmlpath, $templateFile='General:zhuangti_inc'); //专题

		echo "公用html生成成功！";
	}

}
?>
