<?php
/**
 +------------------------------------------------------------------------------
 * Price  前台均价类
 +------------------------------------------------------------------------------
 * @author 熊彦
 +------------------------------------------------------------------------------
 */
class PriceAction extends GlobalAction{

	private $AreaArr = array(
				1=>"市中心",2=>"高新区",3=>"红谷滩",4=>"湾里区",5=>"朝阳洲",6=>"南昌县",
			);

//---更多均价按月显示	
	public function price_month(){
		A('News');  //获取共用头部 初始值
		
		$range_id = intval($_GET['range_id']);
		if(!$range_id)  $this->error("参数有误!");
		$curr_area_name = $this->AreaArr[$range_id];  //当前区域名
		
		$Table = M("Behalf_price");
		$map['range_id'] = $range_id;
		$start_day = $Table->where($map)->min( add_time ); //初始最早日期
		$end_day   = $Table->where($map)->max( add_time ); //初始最晚日期

		$start_day = strtotime( $start_day ); //初始最早日期
		$end_day   = strtotime( $end_day ); //初始最晚日期

		$sub_time  = $end_day - $start_day;
		$sub_month = (date("Y", $end_day)- date("Y",$start_day))*12+(date("m",$end_day)-date("m",$start_day)); //总月数

		$year  = date('Y', $start_day);  //初始年
		$month = date('m', $start_day);  //初始月

//dump($min_price); dump($max_price);  //暂时只显示20天的，多了看不清数字
		for($k = $sub_month; $k>=0; $k--){
			$today = date("Y-m", mktime(0, 0, 0, $month, 1, $year)); //当前月

			//if($start_day < $end_day) break; //无记录则退出
			$map['add_time'] = array('like', $today."%");
			
			$map['range_id'] = $range_id;
			$map['agv_price'] = array('neq', 0 );
			$rs = $Table->where($map)->sum( agv_price );
			$count = $Table->where($map)->count();
			//dump($rs);
			$pr_curr = round( $rs / $count , 1 ) ; //不留小数

			if($pr_curr != 0 ){
				//值不为0
				$list[$k]['add_time'] = $today;
				$list[$k]['ncs'] = $pr_curr;
			}
			$month = $month +1;
		}
		//dump($list);
		//krsort($list);
//-- 获取代表均价数据 end
		// 重置标题/关键字/描述
		$page_info['title']='南昌房产均价,南昌房价--腾房网'; 
		$page_info['keywords']='南昌房产均价,南昌房价,房价';
		$page_info['description']="腾房网为您提供实时的南昌房产均价,南昌房价,房价信息！";
		$this->assign('page_info',$page_info);

		$min_p = $max_p = 0;
		foreach( $list as $k=>$v ){
		   $vv = intval($v['ncs']);
		   if($k == 0){
				$min_p = $vv;
		   }
		   $min_p = ($vv < $min_p ? $vv : $min_p);
		   $max_p = ($max_p > $vv ? $max_p : $vv);
		}
		$min_price   = round($min_p-1000, -2); //最低价
		$max_price   = round($max_p+1000, -2); //最高价


		$labelStep  = 1;
		import('Home.Other.Charts');  
		$Charts = new Charts();

		$strXML = "<graph caption='".$curr_area_name."住宅月度均价' numberSuffix='' formatNumberScale='0' rotateValues='0' decimalPrecision='3' baseFont='宋体' baseFontSize='12' outCnvBaseFontSize='12' showAlternateHGridColor='1' showFCMenuItem='0'    AlternateHGridColor='ff5904' divLineColor='ff5904' divLineAlpha='20' alternateHGridAlpha='5' rotateNames='1' labelStep='".$labelStep."' LineColor='#EE0000' LineThickness='2' anchorRadius='5' yAxisMinValue='".$min_price."' yAxisMaxValue='".$max_price."' >";

		$strDataCurr = "";
		foreach ($list as $subData) {
			$strDataCurr .= "<set name='".$subData['add_time']."' value='". $subData['ncs'] ."' />";
		}
		$strXML .= $strDataCurr ."</graph>";

		$ncsCharts =  $Charts->renderChart("__PUBLIC__/FusionCharts/Line.swf", "", $strXML, "ncsCharts", '900', '250'); //生成线型图
		$this->assign("ncsCharts", $ncsCharts);  //市中心---

//dump($ncsCharts);


		$this->display('price_month');
	}



//---更多均价按月显示	
	public function price_month_all(){
		A('News');  //获取共用头部 初始值
		
		$range_id = intval($_GET['range_id']);
		if(!$range_id)  $this->error("参数有误!");
		
		$Table = M("Behalf_price");
		$map['range_id'] = $range_id;
		$start_day = $Table->where($map)->min( add_time ); //初始最早日期
		$end_day   = $Table->where($map)->max( add_time ); //初始最晚日期

		$start_day = strtotime( $start_day ); //初始最早日期
		$end_day   = strtotime( $end_day ); //初始最晚日期

		$sub_time  = $end_day - $start_day;
		$sub_month = (date("Y", $end_day)- date("Y",$start_day))*12+(date("m",$end_day)-date("m",$start_day)); //总月数

		$year  = date('Y', $start_day);  //初始年
		$month = date('m', $start_day);  //初始月

//dump($min_price); dump($max_price);  //暂时只显示20天的，多了看不清数字
		for($k = $sub_month; $k>0; $k--){
			$today = date("Y-m", mktime(0, 0, 0, $month, 1, $year)); //当前月

			//if($start_day < $end_day) break; //无记录则退出
			$map['add_time'] = array('like', $today."%");
			
			for($i=1; $i<=6; $i++){
				$map['range_id'] = $i;
				$map['agv_price'] = array('neq', 0 );
				$rs = $Table->where($map)->sum( agv_price );
				$count = $Table->where($map)->count();
				//dump($rs);
				$pr[$i] = round( $rs / $count , 1 ) ; //不留小数
			}
			if($pr[1] != 0 ){
				//值不为0
				$list[$k]['add_time'] = $today;
				$list[$k]['ncs'] = $pr[1];
				$list[$k]['gxq'] = $pr[2];
				$list[$k]['hgt'] = $pr[3];
				$list[$k]['wlq'] = $pr[4];
				$list[$k]['cyz'] = $pr[5];
				$list[$k]['ncx'] = $pr[6];
			}
			$month = $month +1;
		}
		//dump($list);
		//krsort($list);
//-- 获取代表均价数据 end
		// 重置标题/关键字/描述
		$page_info['title']='南昌房产均价,南昌房价--腾房网'; 
		$page_info['keywords']='南昌房产均价,南昌房价,房价';
		$page_info['description']="腾房网为您提供实时的南昌房产均价,南昌房价,房价信息！";
		$this->assign('page_info',$page_info);

		for($i=1; $i<=6; $i++){
			$range_map['range_id'] = $i;
			$range_map['agv_price']= array('gt',0);
			$min_p   = $Table->where($range_map)->min( agv_price ); //最低价
			$max_p   = $Table->where($range_map)->max( agv_price ); //最高价
			$min_price[$i]   = round($min_p, -1);
			$max_price[$i]   = round($max_p, -1);
		}
//dump($min_price);
		$labelStep  = 1;
		import('Home.Other.Charts');  
		$Charts = new Charts();

		$strXML = "<graph caption='市中心住宅均价' numberSuffix='' formatNumberScale='0' rotateValues='0' decimalPrecision='3' baseFont='宋体' baseFontSize='12' outCnvBaseFontSize='12' showAlternateHGridColor='1' showFCMenuItem='0'    AlternateHGridColor='ff5904' divLineColor='ff5904' divLineAlpha='20' alternateHGridAlpha='5' rotateNames='1' labelStep='".$labelStep."' LineColor='#EE0000' LineThickness='2' anchorRadius='5' yAxisMinValue='".$min_price[1]."' yAxisMaxValue='".$max_price[1]."' >";

		$strDataCurr = "";
		foreach ($list as $subData) {
			$strDataCurr .= "<set name='".$subData['add_time']."' value='". $subData['ncs'] ."' />";
		}
		$strXML .= $strDataCurr ."</graph>";

		$ncsCharts =  $Charts->renderChart("__PUBLIC__/FusionCharts/Line.swf", "", $strXML, "ncsCharts", '900', '250'); //生成线型图
		$this->assign("ncsCharts", $ncsCharts);  //市中心---

//dump($ncsCharts);
		$strXML = "<graph caption='高新区住宅均价' numberSuffix='' formatNumberScale='0' rotateValues='0' decimalPrecision='3' baseFont='宋体' baseFontSize='12' outCnvBaseFontSize='12' showAlternateHGridColor='1' showFCMenuItem='0'    AlternateHGridColor='ff5904' divLineColor='ff5904' divLineAlpha='20' alternateHGridAlpha='5' rotateNames='1' labelStep='".$labelStep."' LineColor='#EE0000' LineThickness='2' anchorRadius='5' yAxisMinValue='".$min_price[2]."' yAxisMaxValue='".$max_price[2]."' >";

		$strDataCurr = "";
		foreach ($list as $subData) {
			$strDataCurr .= "<set name='".$subData['add_time']."' value='". $subData['gxq'] ."' />";
		}
		$strXML .= $strDataCurr ."</graph>";

		$gxqCharts =  $Charts->renderChart("__PUBLIC__/FusionCharts/Line.swf", "", $strXML, "gxqCharts", '900', '250'); //生成线型图
		$this->assign("gxqCharts", $gxqCharts); //高新---
			
		$strXML = "<graph caption='红谷滩住宅均价' numberSuffix='' formatNumberScale='0' rotateValues='0' decimalPrecision='3' baseFont='宋体' baseFontSize='12' outCnvBaseFontSize='12' showAlternateHGridColor='1' showFCMenuItem='0'    AlternateHGridColor='ff5904' divLineColor='ff5904' divLineAlpha='20' alternateHGridAlpha='5' rotateNames='1' labelStep='".$labelStep."' LineColor='#EE0000' LineThickness='2' anchorRadius='5' yAxisMinValue='".$min_price[3]."' yAxisMaxValue='".$max_price[3]."' >";

		$strDataCurr = "";
		foreach ($list as $subData) {
			$strDataCurr .= "<set name='".$subData['add_time']."' value='". $subData['hgt'] ."' />";
		}
		$strXML .= $strDataCurr ."</graph>";

		$hgtCharts =  $Charts->renderChart("__PUBLIC__/FusionCharts/Line.swf", "", $strXML, "hgtCharts", '900', '250'); //生成线型图
		$this->assign("hgtCharts", $hgtCharts); //红谷滩---

		$strXML = "<graph caption='湾里区住宅均价' numberSuffix='' formatNumberScale='0' rotateValues='0' decimalPrecision='3' baseFont='宋体' baseFontSize='12' outCnvBaseFontSize='12' showAlternateHGridColor='1' showFCMenuItem='0'    AlternateHGridColor='ff5904' divLineColor='ff5904' divLineAlpha='20' alternateHGridAlpha='5' rotateNames='1' labelStep='".$labelStep."' LineColor='#EE0000' LineThickness='2' anchorRadius='5' yAxisMinValue='".$min_price[4]."' yAxisMaxValue='".$max_price[4]."' >";

		$strDataCurr = "";
		foreach ($list as $subData) {
			$strDataCurr .= "<set name='".$subData['add_time']."' value='". $subData['wlq'] ."' />";
		}
		$strXML .= $strDataCurr ."</graph>";

		$wlqCharts =  $Charts->renderChart("__PUBLIC__/FusionCharts/Line.swf", "", $strXML, "wlqCharts", '900', '250'); //生成线型图
		$this->assign("wlqCharts", $wlqCharts); //湾里区---

		$strXML = "<graph caption='朝阳洲住宅均价' numberSuffix='' formatNumberScale='0' rotateValues='0' decimalPrecision='3' baseFont='宋体' baseFontSize='12' outCnvBaseFontSize='12' showAlternateHGridColor='1' showFCMenuItem='0'    AlternateHGridColor='ff5904' divLineColor='ff5904' divLineAlpha='20' alternateHGridAlpha='5' rotateNames='1' labelStep='".$labelStep."' LineColor='#EE0000' LineThickness='2' anchorRadius='5' yAxisMinValue='".$min_price[5]."' yAxisMaxValue='".$max_price[5]."' >";

		$strDataCurr = "";
		foreach ($list as $subData) {
			$strDataCurr .= "<set name='".$subData['add_time']."' value='". $subData['cyz'] ."' />";
		}
		$strXML .= $strDataCurr ."</graph>";

		$cyzCharts =  $Charts->renderChart("__PUBLIC__/FusionCharts/Line.swf", "", $strXML, "cyzCharts", '900', '250'); //生成线型图
		$this->assign("cyzCharts", $cyzCharts); //朝阳洲---

		$strXML = "<graph caption='南昌县住宅均价' numberSuffix='' formatNumberScale='0' rotateValues='0' decimalPrecision='3' baseFont='宋体' baseFontSize='12' outCnvBaseFontSize='12' showAlternateHGridColor='1' showFCMenuItem='0'    AlternateHGridColor='ff5904' divLineColor='ff5904' divLineAlpha='20' alternateHGridAlpha='5' rotateNames='1' labelStep='".$labelStep."' LineColor='#EE0000' LineThickness='2' anchorRadius='5' yAxisMinValue='".$min_price[6]."' yAxisMaxValue='".$max_price[6]."' >";

		$strDataCurr = "";
		foreach ($list as $subData) {
			$strDataCurr .= "<set name='".$subData['add_time']."' value='". $subData['ncx'] ."' />";
		}
		$strXML .= $strDataCurr ."</graph>";

		$ncxCharts =  $Charts->renderChart("__PUBLIC__/FusionCharts/Line.swf", "", $strXML, "ncxCharts", '900', '250'); //生成线型图
		$this->assign("ncxCharts", $ncxCharts); //南昌县---

		$this->display('price_month');
	}


}
?>