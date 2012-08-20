<?php
/**
 +------------------------------------------------------------------------------
 * Loupan  楼盘详细页类
 +------------------------------------------------------------------------------
 * @author 熊彦
 +------------------------------------------------------------------------------
 */
class LoupanAction extends GlobalAction{
//---楼盘列表,用于详细页左侧---
	public function lp_list_inc(){
		$info_id = intval($_GET['info_id']);

		$Form   = M("View_allsite");
		$map = Session::get('clientMap');  //已保存的用户查询条件


		//=====显示分页=======
		import("@.Other.AdvPage");
		//.......配置参数...........
		$count = $Form->where($map)->count(); //1.总的记录数
		$listRows = 20;					//2.每页显示的条数
		$dom_id='#lou_tag_include';     //4.dom模型id,即ajax要加载入的图层id
		$url="__URL__/lp_list_inc";     //5.当前路径
		$page_length=5;                 //6.每页显示的页数

//确定当前页---
		if(isset($_GET['info_id'])){
			$infoIdList = $Form->field('info_id')->where($map)->order('lpyouxian desc, lp_state asc')->findAll();
			foreach($infoIdList as $v){
				$infoIdArr[] = $v['info_id']; 
			}
			$key = array_search($info_id, $infoIdArr); 
			$curr_p = ceil( ($key+1) / $listRows);
			//dump($curr_p);
		}

		if(isset($curr_p)){
			$_GET['p'] = $curr_p;
		}
//确定当前页---


		//调用高级分页类输出
		//$p   = new AdvPage( $count, $listRows, "info_id=".$info_id );
		$p   = new AdvPage( $count, $listRows );
		//-------------1.总的记录数 2.每页显示的条数 3.参数 如info=350;
		$page= $p->show_simple($dom_id, $url ); //--------------输出分页样式
		//=====end 分页=====
		//Session::set('currPage', $_GET['p']);  //已保存的用户当前页
		//Session::set('currTime', time());      //已保存的用户当前操作时间

		//要据当前面面显示相应条数标签

		$list = A('Index')->getLpListCache($p->firstRow, $listRows, $count);  //获取楼盘缓存列表信息
		//$list = A('Index')->getLpListAdv($p->firstRow, $len=$listRows, $startID= $info_id);  //获取楼盘缓存列表信息

	    if($list){
			$this->assign('list',$list);
			$this->assign('page',$page);	
		}

		//dump($page);
		$this->assign('info_id',$info_id);
		$this->display();
    }

//---楼盘附件切换---
//用于详细页图片切换
	public function switchAttach(){
		$info_id = intval($_POST['info_id']);
		$class_id= ( isset($_POST['class_id']) ? intval($_POST['class_id']) : 0 );   //默认调效果图

		$rs = A('Attach')->getAttachByInfoId($info_id, $class_id);
		//$pic = "thumb_".$rs;
		echo "<img src=\"".$rs."\" width=\"270\" height=\"220\" />";
	}

//---楼盘详细---
	public function view(){
		$info_id = intval($_GET['id']);  

		$Table = D('Info');
		$map['info_id'] = $info_id;
		$vo = $Table->where($map)->find();
		$Table->setInc('hits', 'info_id='.$info_id, '1');  //更新人气
		//$Lp->setInc('hits','info_id='.$info_id,'1');  //更新人气
		unset($map);
		if(!$vo) $this->error('找不到该信息');
		$class_id = intval($vo['class_id']);
		$map['info_id'] = intval($info_id);
		$map['class_id']= $class_id;
		$data = M('Map')->where($map)->find();  //查地图坐标
		unset($map);
		if($data){
			$vo['point_x'] = $data['point_x'];
			$vo['point_y'] = $data['point_y'];
		}
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
		//---取得该楼盘更新时间---
		$vo_add['lp_update_time'] = $this->lp_update_time($info_id);  
		//---取得该楼盘已看及想看人数---
		$seeArr = $this->lp_order_see($info_id);  
		$vo_add['seecount'] = $seeArr['seecount'] ;
		$vo_add['wantsee']  = $seeArr['wantsee'] ;
		$this->assign("vo_add", $vo_add);

		//---取得区域列表
        $area_list= A('Index')->area_list();
		$this->assign('area_list',$area_list); 
		$this->assign('features_list',C('cfg_features')); //用于特色搜索列表
		//---取得编辑点评列表
        $map['info_id'] = $info_id;  
        $vo_reviews = M('Reviews')->field('id')->where($map)->find();
		$goodReviews = A('Reviews')->getReviewsList($g_or_b = 'g', $num = 1, $is_editor = 1, $type=1, $vo_reviews['id']); // 好评明细;
        $badReviews  = A('Reviews')->getReviewsList($g_or_b = 'b', $num = 1, $is_editor = 1, $type=1, $vo_reviews['id']); // 差评明细;

		if($goodReviews){ $this->assign('goodReviews',$goodReviews[0]); }
		if($badReviews){ $this->assign('badReviews' ,$badReviews[0]); }

		if( !is_file("./Public/Xml/i".$info_id.".xml") ){
			A('Reviews')->makePointXml($info_id);  // xml文件不存在则生成
		}
		//--默认图片
		$Attach = A('Attach');
		$att_rs = $Attach->getAttachByInfoId($info_id, $class_id = 0, $is_defautl=1);
		//if($att_rs != 'no.png'){
		//	$att_rs = "thumb_".$att_rs;  不用缩略图
		//}
		$default_pic = "<img src=\"".$att_rs."\" width=\"270\" height=\"220\" />";
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
		$upSpan = $this->getUpSpan($info_id);
		$this->assign('up_span',$upSpan);
		
		$sell_month_ph = $this->lp_month();   //本月排行
		$this->assign('sell_month_ph',$sell_month_ph);
		//--记录浏览记录
		A('Interaction')->in_browser_record($info_id);
	
		$this->assign('flashXmlUrl', "i".$info_id.".xml?".time()); //路径后加当前时间，以免重复
		$this->display();
	}

//---计算涨跌幅---
	public function getUpSpan($info_id){
			$Price_record = M('Price_record');
			$map_['info_id'] = 	$info_id;
			$map_['lptype']  = 	1;  //住宅
			$list_ = $Price_record->where($map_)->order('month desc')->limit('0,2')->findall();
			$upSpan = 0;  //计算涨幅 
			if(count($list_)>=2){
				$upSpan  = 100 * ( $list_[0]['month_price'] - $list_[1]['month_price'] ) / $list_[1]['month_price'];
				$upSpan  = round($upSpan, 2);    
			}
			return $upSpan;
	}

//---用于楼盘详细页底部切换---
	public function lp_spzf() //沙盘找房
	{
		echo '沙盘找房';
	}

    public function lp_jgfx() //价格分析
	{
		$list = array();
		$id = intval($_GET['info_id']);
		$Table = M('Price_record');
		$map['info_id'] = 	$id;
		$currLp = M('New_loupan')->field('lpname,lpprice')->where($map)->find();      //楼盘当前价格等信息
		$this->assign("currLp", $currLp);
		//dump($currLp);
		
		$map['lptype']  = 	1;  //住宅
		$list = $Table->where($map)->order('month desc')->limit('0,3')->findall();
	//计算涨幅 
		$upSpan = 0;
		if(count($list)>=2){
			$upSpan  = 100 * ( $list[0]['month_price'] - $list[1]['month_price'] ) / $list[1]['month_price'];
			$upSpan  = round($upSpan, 2);    
		}

		$Table_month = M('Tj_monthsell');
		$map_['info_id'] = 	$id;
		$t_list = $Table_month->field('info_id,lpname,sell_sum,agv_price,add_time')->where($map_)->limit('0,24')->order('add_time asc')->findAll(); //两年
		
		$min_p   = $Table_month->where($map_)->min( agv_price );
		$min_p   = ($min_p > 1000 ?  $min_p - 1000 : 0);
		$min_p   = round( $min_p , -2); //最低价
		$max_p   = round( $Table_month->where($map_)->max( agv_price ) + 1000 , -2); //最高价

		if($t_list){
			//dump($upSpan);
			foreach ($t_list as $k=>$v){ 
				$arrData[$k]['1'] = substr($v['add_time'],0,'7');  //时间
				$arrData[$k]['2'] = $v['agv_price'];  //住宅均价
				//$arrData[$k]['3'] = ($v['lpnomprice'] ? $v['lpnomprice'] : 0 ) ;  //非住宅均价  
				//$arrData[$k]['3'] = 4800;  //南昌市均价
				//$arrData[$k]['4'] = $v['price_area'];  //该区域均价
			}
			//krsort($arrData);   //键值从小到大排序
		}else{
			$arrData[0][1] = date('Y-m', time()-3600*24*30 );  //前一月时间
			$arrData[1][1] = date('Y-m');  //找不到记录时，默认显示
			//销售套数
			$arrData[0][2] = $currLp['lpprice'];
			$arrData[1][2] = $currLp['lpprice']; 
			//销售均价
			//$arrData[0][3] = 0.1;
			//$arrData[1][3] = 0.1; 
			//销售均价
			//$arrData[0][4] = 0.3;
			//$arrData[1][4] = 0.3; 
		}
		
		import('@.Other.Charts');  
		$Charts = new Charts();

		//Initialize <graph> element
		$strXML = "<graph caption='价格分析' numberPrefix='' formatNumberScale='5' rotateValues='5' decimalPrecision='0' baseFont='宋体' baseFontSize='12' outCnvBaseFontSize='12' showValues='0' yAxisMinValue='".$min_p."' yAxisMaxValue='".$max_p."' rotateNames='1' labelStep='1'>";
		//Initialize <categories> element - necessary to generate a multi-series chart
		$strCategories = "<categories>";
		//Initiate <dataset> elements   2AD62A
		$strDataCurr = "<dataset seriesName='住宅均价' color='1D8BD1'>";
		//$strDataPrev = "<dataset seriesName='非住宅均价' color='F1683C'>";
		//$strDataArea = "<dataset seriesName='该区域均价' color='2AD62A'>";
		
		//Iterate through the data  
		foreach ($arrData as $arSubData) {
			//Append <category name='...' /> to strCategories
			$strCategories .= "<category name='" . $arSubData[1] . "' />";
			//Add <set value='...' /> to both the datasets
			$strDataCurr .= "<set value='" . $arSubData[2] . "' />";
			//$strDataPrev .= "<set value='" . $arSubData[3] . "' />";
			//$strDataArea .= "<set value='" . $arSubData[4] . "' />";
		}
		
		//Close <categories> element
		$strCategories .= "</categories>";
		
		//Close <dataset> elements
		$strDataCurr .= "</dataset>";
		//$strDataPrev .= "</dataset>";
		//$strDataArea .= "</dataset>";
		
		//Assemble the entire XML now
		$strXML .= $strCategories . $strDataCurr . $strDataPrev . $strDataArea ."</graph>";
		
		//Create the chart - MS Line Chart with data contained in strXML
		$strCharts =  $Charts->renderChart("__PUBLIC__/FusionCharts/MSLine.swf", "", $strXML, "productSales", 620, 300); //生成线型图

		$this->assign("strCharts", $strCharts);
//---最近销售记录---
		unset($map);
		$map['info_id'] = 	$id;
		$sellRecord = M('Tj_daysell')->field('info_id,lpname,sell_sum,agv_price,area_sum,add_time')->where($map)
			->limit('0,8')->order('add_time desc')->findAll();
		$this->assign("sellRecord", $sellRecord);
		//dump($sellRecord);
		$this->assign("upSpan", $upSpan);
		$this->display();
	}

	public function lp_zxkf() //在线看房
	{
		$info_id = intval($_GET['info_id']);
		$vo['info_id'] = $info_id;
		if(is_file("./3d/".$info_id."/index.html")){
			$this->assign("vo", $vo);   //用于给内内嵌iframe传参
		}
		$this->display();
	}

	public function lp_lpxx() //楼盘信息
	{
		$info_id = intval($_GET['info_id']);
		$Table = D('Info');
		$map['info_id'] = $info_id;
		$vo = $Table->where($map)->find();
		unset($map);
		$map['info_id'] = $info_id;
		$vo_add = M('New_loupan')->where($map)->find(); 
		//$map = null; //置空，正式上线时去掉，以免信息太少
		$map['ispublish'] = 1 ;
		$news_list = M('News')->field('id,class_id,title,add_time')->where($map)->order('id desc')->limit('0,6')->findall();  //新闻列表
		if($news_list){
			$this->assign("news_list", $news_list);
		}
		//dump($news_list);
		//获取土地成本
		$map_gtj['info_id']   = $info_id;
		//$map_gtj['ispublish'] = 1;
		$rs = M('Gtj')->field('title,bianhao,chengbenjia,ispublish')->where($map_gtj)->find();
		$td_chengben = ($rs && $rs['ispublish']==1 ? $rs['chengbenjia'].'元/平方米' : '未知');
		$this->assign('td_chengben', $td_chengben);
		$vo_add['zd_bianhao'] = $rs['bianhao']; //宗地编号
		$vo_add['lpland']     = $rs['title'];   //使用权证号

		$this->assign("vo", $vo);
		$this->assign("vo_add", $vo_add);
		$this->display();
	}

    public function lp_shpt() //生活配套
	{
		$id = intval($_GET['info_id']);
		$vo['id'] = $id;    
		$this->assign("vo", $vo);   //用于给内内嵌iframe传参
		$this->display();
	}

    public function lp_shpt_inc() //生活配套内嵌
	{
		$id = intval($_GET['info_id']);
		$Table = D('Info');
		$map['info_id'] = $id;
		$vo = $Table->where($map)->find();
		unset($map);
		$map['info_id'] = $id;
		$vo_add = M('New_loupan')->field('lpaddr,lptel,lpsheshi,lpjiaotong')->where($map)->find();
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

//---小区印象标签--- 万超
	public function lp_tag_inc() //ajax分页显示小区印象 
	{
		//ajax分页查看
		//1.获取楼盘info id---分页号page
		$id = intval($_GET['info_id']);
		//根据页数来查询相应内容
		//---取得小区印象标签---
		$tag=M('tag');
		$tag_where['info_id'] = $id;  //查询条件

		//=====显示分页=======
		import("@.Other.AdvPage");
		//.......配置参数...........
		$totalRows=$tag->where($tag_where)->count(); //1.总的记录数
		$listRows= 8;						  //2.每页显示的条数
		$parameter="info_id=".$id;         //3.参数 如info=350;
		$dom_id= '#lou_r_yin';         //4.dom模型id,即ajax要加载入的图层id
		$url="__URL__/lp_tag_inc";      //5.当前路径
		$page_length=3;                 //6.每页显示的页数
		
		//调用高级分页类输出
		$page=new AdvPage($totalRows,$listRows,$parameter);//-------------1.总的记录数 2.每页显示的条数 3.参数 如info=350;
		$page_style=$page->show_ajax($dom_id,$url,$page_length);//--------------输出分页样式
		$this->assign("page_style",$page_style);
		//=====end 分页=====

		//要据当前面面显示相应条数标签
		$lp_tag = $tag->field("tag_name,tag_count")->where($tag_where)->order('tag_count desc,id desc')
				->limit($page->firstRow.','.$listRows)->findAll();
		$this->assign("tag_list",$lp_tag);
//dump($lp_tag);
		$this->display();
	}

//---黄妃修改及添加模板---
	public function lp_bmkf() //报名看房
	{
			$info_id = intval($_GET['info_id']);
			if(empty($info_id)){
				ajaxMsg('未知参数！', $status='error');
				exit;
			}
			//获取楼盘名
			$map['info_id'] = $info_id;
			$lpname = D('Info')->where($map)->getField('title');
			//显示最新10个报名
			$order = D('Order');
			$map_order['lpname'] = $lpname ; 
			$list_order = $order->where($map_order)->order('order_time desc')->limit('0,10')->findAll();
			//团购看房排行榜
			$list_ph = $this->lp_max(false);
            
			$this->assign("info_id", $info_id);
			$this->assign("lpname", $lpname);
			$this->assign("list_ph", $list_ph);
			$this->assign("list_order", $list_order);
			$this->display();
	}

//---保存报名看房 黄妃---
	public function lp_bmkf_insert()
	{
		 $order_arr = $_POST["ordertype"] ;
		 foreach($order_arr as $vo){  //是否多选
			 $ordertype .= $vo.',';
		 }
		 $data['ordertype'] = trim($ordertype,',');//去除首尾逗号
		 $data['info_id'] = trim($_POST["bm_info_id"]) ;
		 $data['lpname']  = trim($_POST["bm_lpname"]) ;
		 $data['name']    = trim($_POST["bm_name"]) ;  //姓名
		 $data['user_id'] = $this->uid;             //用户ID
		 $data['remark'] = trim($_POST["bm_remark"]) ;
		 $data['mobile'] = trim($_POST["bm_mobile"]) ;
		 $data['sex'] = trim($_POST["bm_sex"]) ;
		 $data['email'] = trim($_POST["bm_email"]);
		 $data['order_time'] = time();

		 $t_order = D('Order');//检测是否报过名
		 $map['mobile'] = $data['mobile'];
		 $name = $t_order->where($map)->getField('name');
		 if(!empty($name)){
			 ajaxMsg('请不要重复报名！', $status='error');
			 return;
		 }
		 if(empty($data['name']) || empty($data['mobile']) || empty($data['ordertype'])){
			 ajaxMsg('姓名,手机号必须填写,意向必须选择！', $status='error');
			 return;
		 }
		 if($t_order->add($data)){
			 ajaxMsg('报名成功！', $status='success');
			 exit;
		 }else{
			 ajaxMsg('报名失败！', $status='error');
			 exit;
		 }
	}

//---黄妃添加获取团购排行榜函数---$all:是否获取全部---$max:$all为false时获取前10条---
    public function lp_max($all,$max=10) 
	{
		$order = D('Order');
		$list = $order->group('lpname')->findAll();
		$array = array();
        foreach($list as $vo){ 
             $map['lpname'] = $vo['lpname'];
			 $count = $order->where($map)->count();
             $array[$vo['lpname']] = $count; 
		}
		
		asort($array);//对值从小到大排序保存索引
		arsort($array);//从大到小排序保存索引
		//print_r($array);
		if(!$all){
			if(count($array)>$max){
				return array_slice($array,0,$max,true);//只截取$max个
			}else{
				return $array;
			}
		}
		return $array;
	}

//---黄妃添加查看团购看房留言 函数---
    public function lp_bmly() 
	{
		$lpname = $_POST['seelpname'];
		if(empty($lpname)){
			$this->error('参数出错了！');
			exit;
		}
		$order = D('Order');
		$map['lpname'] = $lpname;
		$map['length(remark)'] = array('gt',0);
		$list = $order->where($map)->findAll();
		//dump($list);
        $this->assign('list',$list);
		$this->display();
	}

//---黄妃获取本月楼盘销售套数排行 函数---
    public function lp_month() 
	{
		$arr = S('SellMonth_PH');
		if(!$arr){
			//本月第一天
			$year = date("Y");
			$month = date("m");
			if(strlen($month)<=1){
				$month = '0'.$month ;
			}
			$timeb = $year.'-'.$month.'-01' ;
			$timee = date("Y-m-d",strtotime("-1 day"));
			$Phang = new PhangAction(); //调用排行类
			//$list = $Phang->getmax('2011-03-31','2011-04-31',10); 测试数据就能dump
			$list = $Phang->getmax($timeb,$timee,20) ; //返回数组格式：$key为 楼盘名|区域  $value为时间段内销售总数
			//重建数组
			$index = 0 ;
			//dump($list);
			foreach($list as $key=>$val){
				//echo $vo;
				$arr[$index]['lpname'] = substr($key,0,strpos($key,"|")); 
				$arr[$index]['quyu']   = substr($key,strpos($key,"|")+1);
				$arr[$index]['info_id']= substr($key,strpos($key,"*")+1);
				$arr[$index]['count'] = $val ;
				$index++;
			}
			S('SellMonth_PH', $arr, 3600*24*7);
		}
		//dump($arr);
		return $arr;
	}

//---黄妃楼盘最新更新日期 函数---
    public function lp_update_time($infoid)
	{
		if(empty($infoid)){
			$update_time = '未知日期';
		}
		$Loupan = M('New_loupan');
		$map['info_id'] = $infoid;
/*
		$rs = $Loupan->field('lpid,range_id')->where($map)->find();
		
		//dump($rs);
		if(!empty($rs['lpid']) && !empty($rs['range_id'])){
			unset($map);
			$map['lpid']     = $rs['lpid'];   //
            $map['range_id'] = $rs['range_id'];
			$vo = M('New_loupan_temp')->field('inserttime')->where($map)->order('id desc')->find();
			$update_time = $vo['inserttime'];
		}else{
			$update_time = '未知日期';
		}
*/
		$rs = $Loupan->field('update_time')->where($map)->find();
		$update_time = date('Y-m-d', $rs['update_time']);
		//dump($update_time);
        return $update_time;
	}

//---黄妃获取该楼盘想去看房人数和已经看过房人数 函数---
    public function lp_order_see($infoid)
	{
		if(empty($infoid)){
			$data['seecount'] = 0;
			$data['wantsee']  = 0;
			return;
		}
		$Loupan = D('New_loupan');
		$map['info_id'] = $infoid;
		$rs = $Loupan->field('seecount,lpname')->where($map)->find();
		//dump($rs);
		if(!empty($rs['seecount'])){
            $data['seecount'] = $rs['seecount'] ;
		}else{
			$data['seecount'] = 0;
		}
		if(!empty($rs['lpname'])){
			unset($map);
			$map['lpname'] = $rs['lpname'];
			$map['ordertype'] = array('like','%现场看房%');
			$data['wantsee'] = M('Order')->where($map)->count();
			//echo $Loupan->getLastSql();
		}else{
			$data['wantsee']  = 0;
		}
		return $data;   //xiongyan 改
	}


}
?>