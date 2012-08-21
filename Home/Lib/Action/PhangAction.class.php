<?php
/**
 +------------------------------------------------------------------------------
 * Attach  排行榜类
 +------------------------------------------------------------------------------
 * @author 黄妃
 +------------------------------------------------------------------------------
 */
class PhangAction extends GlobalAction{
	public function index()
	{
		//dump($this->getpriceph());
		$this->getmap('lpid=822','2011-03-31','','',20);
	}
/*
   根据时间段得出范围内的楼盘销售排行
   $timeb:时间起点
   $timee:时间结点
   $len:返回个数
   时间格式 2011-03-03 
*/
    public function getmax($timeb,$timee,$len=20)
	{
        if(empty($timeb) || empty($timee)){
			exit;		
		}
		$Model = new Model();
		/*$sql = " select lpid,range_id from t_sell where sell_time between '$timeb' and '$timee' and length(lpname)>0 group by lpid ";
		$list = $Model->query($sql);//得到时间段内的所有销售楼盘
		$arr = array();
		$index = 0 ;
        foreach($list as $vo){  //重建数组 $key为 楼盘名|区域  $value为时间段内销售总数
			$lpid = $vo['lpid'] ;
			$range_id = $vo['range_id'] ;
			//$sql = "select count(*) from t_sell where lpid='$lpid' and range_id='$range_id' and length(lpname)>0 group by roomid" ;	
			$sql = "select count(*) from t_sell where lpid='$lpid' and length(lpname)>0 and sell_time between '$timeb' and '$timee' group by roomid" ;	
			$cc = $Model->query($sql);
			$count = count($cc); //得到该楼盘的销售套数
			$aa = $Model->query("select name from t_area where id='$range_id'");
			$quyu = $aa[0]['name']; //得到该楼盘的区域
            //$ll = $Model->query("select lpname from t_new_loupan where lpid like '%$lpid%' and range_id='$range_id' and length(lpname)>0 ");
			$ll = $Model->query("select lpname from t_new_loupan where lpid='$lpid' and length(lpname)>0 ");
			$lpname = $ll[0]['lpname'] ; //得到该楼盘的楼盘名称
			if(empty($lpname)){
				continue;
			}
			$key = $lpname.'|'.$quyu ;
            $arr[$key]= $count ;   
			$index++ ;
		}*/
		$sql = " select info_id,lpid,range_id,lpname from t_new_loupan group by lpid ";
		$list = $Model->query($sql);//所有楼盘
		$arr = array();
		$index = 0 ;
		$T_area = M('Area');
		foreach($list as $vo){  //重建数组 $key为 楼盘名|区域  $value为时间段内销售总数
			$info_id = $vo['info_id'] ;
			$lpid = $vo['lpid'] ;
			$range_id = intval($vo['range_id']) ;
			$lpname = $vo['lpname'] ;
			if(strpos($lpid,',')>0){
				$sql = "select count(*) from t_sell where lpid in ($lpid) and length(lpname)>0 and sell_time between '$timeb' and '$timee' group by roomid" ;
				//echo $sql;
			}else{
				$sql = "select count(*) from t_sell where lpid='$lpid' and length(lpname)>0 and sell_time between '$timeb' and '$timee' group by roomid" ;	
			}
			$cc = $Model->query($sql);
			$count = count($cc); //得到该楼盘的销售套数
			$map_area['id'] = $range_id;
			$rs_area = $T_area->field('name')->where($map_area)->find();
			$quyu = $rs_area['name']; //得到该楼盘的区域
            //$ll = $Model->query("select lpname from t_new_loupan where lpid like '%$lpid%' and range_id='$range_id' and length(lpname)>0 ");
			if(empty($lpname)){
				continue;
			}
			$key = $lpname.'|'.$quyu .'*' .$info_id ;
            $arr[$key]= $count ;   
			$index++ ;
		}
		arsort($arr);//从大到小排序保存索引
		if(count($arr)>$len){
			return array_slice($arr,0,$len,true);//只截取$max个
		}else{
			return $arr;
		}
	}
/*
   楼盘均价对比
   $len:返回个数
*/
	public function lp_compare($info_idb,$info_ide)
	{
		
	}
/*
   近期住宅成交均价排行
   $len:返回个数
*/
    public function getpriceph($len=20)
	{
        $Model = new Model();
		$arr = array();        //可根据优先级设定排列先后或者重点加亮
		$list = $Model->query(" select * from t_new_loupan where lpprice>0 and lpnosalecount>0 order by lpprice asc"); //lpprice为0或者空的叫编辑部打电话问
		foreach($list as $vo){//重建数组
			$range_id = $vo['range_id'];
			$qq = $Model->query("select name from t_area where id='$range_id'"); //得到该楼盘的区域
			$quyu = $qq[0]['name'] ;
			//获取该楼盘上一次价格进行分析比较
			$sql = "select * from t_new_loupan_temp where lpid='$lpid' and range_id='$range_id' order by id desc limit 0,1" ;
			$pp = $Model->query($sql) ;
			$priceb = $pp[0]['lpprice'] ;
			if($priceb>$lpprice){ //价格变动标志
				$change = '-';
			}
			if($priceb<$lpprice){
				$change = '+';
			}
			if($priceb=$lpprice){
				$change = '=';
			}
			$arr[$index]['quyu'] = $quyu ;
			$arr[$index]['xingzheng'] = $vo['lpxingzheng']; ;
			$arr[$index]['info_id'] = $vo['info_id']; ;
			$arr[$index]['lpid'] = $vo['lpid'] ;
			$arr[$index]['lpname'] = $vo['lpname'] ;
			$arr[$index]['lpprice'] = $vo['lpprice'];
			$arr[$index]['lpnosalecount'] = $vo['lpnosalecount'];
			$arr[$index]['change'] = $change;
            $index++;
			if($index>$len)
				break;
		}
		if(count($arr)>$len){
			return array_slice($arr,0,$len,true);//只截取$len个
		}else{
			return $arr;
		}
	}
/*
   根据条件和时间返回符合的信息
   $map:条件 格式 ' area>80 '或' range_id=13,lpname='金色学府' ' 多个条件用,分割
   $group: 'lpid'
   $order: 'lpid desc'
   $time: 时间 格式 '2011-04-07' 必须参数
   $len:返回个数
*/
    public function getmap($map,$time,$group='',$order='',$len='')
	{
         if(empty($time) || $time==''){
			 exit;
		 }
		 if(strpos($map,',')){
			 $arr = explode(',',$map);
		 }else{
		     $arr[0] = $map ;
		 }
		 $sql = " select t_new_room.*,t_sell.lpname from t_new_room,t_sell,t_new_loupan where t_new_room.roomid=t_sell.roomid  and t_sell.sell_time='$time' and " ;
		 foreach($arr as $vo){
			 $sql .= 't_new_room.'.$vo . ' and ' ;
		 }
		 $sql = rtrim($sql," ");
		 $sql = rtrim($sql,"and"); 
		 if(!empty($group)){
             $sql .= ' group by t_new_room.'.$group.' ' ;
		 }
		 if(!empty($order)){
             $sql .= ' order by t_new_room.'.$order.' ' ;
		 }
		 if(!empty($len)){
             $sql .= ' limit 0,'.$len.' ' ;
		 }
		 echo $sql ;
		 $Model = new Model();
		 $list = $Model->query($sql);
		 dump($list);
		 return $list;
	}
/*
    返回一星期商品房成交数量
*/
	public function getweeknum($map,$time,$group='',$order='',$len='')
	{
		 $arr = array();
		 $Sell = D('perday');
		 //商品房
		 $index = 0;
         for($i=-8;$i<-1;$i++){
             $str = $i." day";
			 $datetime = date("Y-m-d",strtotime($str)) ;
			 $map['tjtime'] = $datetime ;
			 $map['type'] = 1 ;
			 $rs = $Sell->field('allsale')->where($map)->findAll();
			 //echo $Sell->getLastSql();
			 $allcount = 0 ;
			 foreach($rs as $vo){
				 $allcount = $allcount + $vo['allsale'];
			 } 
			 if($allcount==0){
				 $list_new[$index]['count'] = $list_new[$index-1]['count'] ;
			 }else{
				 $list_new[$index]['count'] = $allcount ;
			 }
			 $list_new[$index]['time'] = date('m-d',strtotime($i." day")); //$time ;
			 $index++;
		 }
		 $dump['new'] = $list_new ;
		 //二手房
		 $index = 0;
		 for($i=-8;$i<-1;$i++){
             $str = $i." day";
			 $datetime = date("Y-m-d",strtotime($str)) ;
			 $map['tjtime'] = $datetime ;
			 $map['type'] = 0 ;
			 $rs = $Sell->field('allsale')->where($map)->findAll();
			 //echo $Sell->getLastSql();
			 $allcount = 0 ;
			 foreach($rs as $vo){
				 $allcount = $allcount + $vo['allsale'];
			 } 
			 if($allcount==0){
				 $list_esf[$index]['count'] = $list_esf[$index-1]['count'] ;
			 }else{
				 $list_esf[$index]['count'] = $allcount ;
			 }			 
			 $list_esf[$index]['time'] = date('m-d',strtotime($i." day")); //$time ;
			 $index++;
		 }
		 $dump['esf'] = $list_esf ;
		 return $dump ;
	}
/*
   根据价格范围返回符合的楼盘
   $priceb:起始价
   $pricee:结束价
   $len : 默认返回12条 按照楼盘优先级返回
*/
	public function getlpbyprice($priceb,$pricee,$len=24)
	{
		if(empty($priceb) && empty($pricee)){
			return null;
		}
		//判断缓存是否还在
		if(!S('lpprice_list')){
			$this->getalllp();   
		}
        $list = S('lpprice_list');
		//dump($list);
		//只有结束价，>$pricee
		if(empty($priceb) && !empty($pricee)){
			$index = 0 ;
	        foreach($list as $vo){
				if($vo['lpprice']>$pricee){
					if($index==$len)
						break;
                    $arr[$index]['lpid'] = $vo['lpid'];
					$arr[$index]['info_id'] = $vo['info_id'];
					$arr[$index]['lpname'] = $vo['lpname'];
					$arr[$index]['lpprice'] = $vo['lpprice']; 
					$index++;
				}
			}
			return $arr ;
		}
		//只有起始价，<$priceb
		if(!empty($priceb) && empty($pricee)){
			$index = 0 ;
	        foreach($list as $vo){
				if($vo['lpprice']<$priceb){
					if($index==$len)
						break;
                    $arr[$index]['lpid'] = $vo['lpid'];
					$arr[$index]['info_id'] = $vo['info_id'];
					$arr[$index]['lpname'] = $vo['lpname'];
					$arr[$index]['lpprice'] = $vo['lpprice']; 
					$index++;
				}
			}
			return $arr ;
		}
		//有起始价,有结束价，>=$priceb <=$pricee
        if(!empty($priceb) && !empty($pricee)){
			$index = 0 ;
	        foreach($list as $vo){
				if($vo['lpprice']>=$priceb && $vo['lpprice']<$pricee){
					if($index==$len)
						break;
                    $arr[$index]['lpid'] = $vo['lpid'];
					$arr[$index]['info_id'] = $vo['info_id'];
					$arr[$index]['lpname'] = $vo['lpname'];
					$arr[$index]['lpprice'] = $vo['lpprice']; 
					$index++;
				}
			}
			return $arr ;
		}
	}
/*
   根据区域范围返回符合的楼盘
   $name:区域名称
   $range_id:结束价
   $len : 默认返回12条 按照楼盘优先级返回
*/
	public function getlpbyarea($name,$range_id,$len=12)
	{
		if(empty($name) && empty($range_id)){
			return null;
		}
		//判断缓存是否还在
		if(!S('lparea_list')){
			$this->getalllpbyarea();   
		}
        $list = S('lparea_list');
		//dump($list);
		//$range_id
		if(!empty($range_id)){
			//待售楼盘
			//$map['lp_state'] = 2 ;
			$index = 0 ;
	        foreach($list as $vo){
				if($vo['range_id']==$range_id){
					if($index==$len)
						break;
                    $arr[$index]['lpid'] = $vo['lpid'];
					$arr[$index]['range_id'] = $vo['range_id'];
					$arr[$index]['info_id'] = $vo['info_id'];
					$arr[$index]['lpname'] = $vo['lpname'];
					$arr[$index]['lpprice'] = $vo['lpprice']; 
					$arr[$index]['lpxingzheng'] = $vo['lpxingzheng'];
					$arr[$index]['lpyouxian'] = $vo['lpyouxian'];
					$index++;
				}
			}
			return $arr ;
		}
		//$name
		if(!empty($name)){
			$index = 0 ;
	        foreach($list as $vo){
				if($vo['lpxingzheng']==$name){
					if($index==$len)
						break;
                    $arr[$index]['lpid'] = $vo['lpid'];
					$arr[$index]['range_id'] = $vo['range_id'];
					$arr[$index]['info_id'] = $vo['info_id'];
					$arr[$index]['lpname'] = $vo['lpname'];
					$arr[$index]['lpprice'] = $vo['lpprice']; 
					$arr[$index]['lpxingzheng'] = $vo['lpxingzheng'];
					$arr[$index]['lpyouxian'] = $vo['lpyouxian'];
					$index++;
				}
			}
			return $arr ;
		}
	}
/*
    缓存所有楼盘>0的价格,区域
    返回价格从小到大的数组
*/
	public function getalllp()
	{
		$Loupan = M('view_loupan');
		$map['lpprice'] = array('gt',100);
		$list = $Loupan->field('info_id,lpid,title as lpname,lpprice,lpxingzheng,range_id')->where($map)->order('lpprice asc')->findAll();
		//echo $Loupan->getLastSql();
		$index = 0 ;
		foreach($list as $vo){
			$arr[$index]['lpid'] = $vo['lpid'];
			$arr[$index]['info_id'] = $vo['info_id'];
			$arr[$index]['lpname'] = $vo['lpname'];
			$arr[$index]['lpprice'] = $vo['lpprice'];
			$arr[$index]['lpxingzheng'] = $vo['lpxingzheng'];
			$arr[$index]['range_id'] = $vo['range_id'];
			$index++;
		}
		C('DATA_CACHE_TIME', 1000); // 设置缓存有效期
		S('lpprice_list',$arr, C('DATA_CACHE_TIME'));  //查询结果缓存
	}
/*
    缓存所有楼盘的 区域
    返回楼盘优先级从大到小的数组
*/
	public function getalllpbyarea()
	{
		$Loupan = M('new_loupan');
		//$map['lp_state'] = 2 ;
		$map['lpprice'] = array('gt',100);
		$list = $Loupan->field('info_id,lpid,lpname,lpprice,lpxingzheng,range_id,lpyouxian')->order('lpyouxian desc')->findAll();
		$index = 0 ;
		foreach($list as $vo){
			$arr[$index]['lpid'] = $vo['lpid'];
			$arr[$index]['info_id'] = $vo['info_id'];
			$arr[$index]['lpname'] = $vo['lpname'];
			$arr[$index]['lpprice'] = $vo['lpprice'];
			$arr[$index]['lpxingzheng'] = $vo['lpxingzheng'];
			$arr[$index]['range_id'] = $vo['range_id'];
			$arr[$index]['lpyouxian'] = $vo['lpyouxian'];
			$index++;
		}
		$dmup['daishou'] = $arr ;
		//待售楼盘
		$map['lp_state'] = 2 ;
		//$map['lpprice'] = array('gt',100);
		$list = $Loupan->field('info_id,lpid,lpname,lpprice,lpxingzheng,range_id,lpyouxian')->order('lpyouxian desc')->findAll();
		$index = 0 ;
		foreach($list as $vo){
			$arr[$index]['lpid'] = $vo['lpid'];
			$arr[$index]['info_id'] = $vo['info_id'];
			$arr[$index]['lpname'] = $vo['lpname'];
			$arr[$index]['lpprice'] = $vo['lpprice'];
			$arr[$index]['lpxingzheng'] = $vo['lpxingzheng'];
			$arr[$index]['range_id'] = $vo['range_id'];
			$arr[$index]['lpyouxian'] = $vo['lpyouxian'];
			$index++;
		}
		C('DATA_CACHE_TIME', 1000); // 设置缓存有效期
		S('lparea_list',$arr, C('DATA_CACHE_TIME'));  //查询结果缓存
	}
/*
    南昌热销楼盘排行榜
	根据楼盘优先级进行显示（投了广告的优先级越大）
*/
    public function getlphot($len=20)
	{
		/*$Loupan = M('new_loupan');
		$map['lpprice'] = array('gt',100);
		$list = $Loupan->where($map)->order('lpyouxian desc')->limit("0,".$len)->findAll();
		$index = 0 ;
		foreach($list as $vo){
			if($index>=$len)
				break;
			$arr[$index]['id'] = $vo['id'];
			$arr[$index]['info_id'] = $vo['info_id'];
			$arr[$index]['lpid'] = $vo['lpid'];
			$arr[$index]['lpname'] = $vo['lpname'];
			$arr[$index]['lpprice'] = $vo['lpprice'];
			$arr[$index]['lpxingzheng'] = $vo['lpxingzheng'];
			$arr[$index]['range_id'] = $vo['range_id'];
			$arr[$index]['index'] = $index+1;
			$index++;
		}
		*/
		$timeb = date('Y-m-d',strtotime("-31 day"));
		$timee = date('Y-m-d',strtotime("-1 day"));

		$list = $this->getmax( $timeb,$timee,$len );
		$index = 0 ;
		foreach($list as $key=>$vo){
			//echo substr($key,strpos($key,'|')+1,strpos($key,'*')-strpos($key,'|')-1);
			$arr[$index]['lpname'] = substr($key,0,strpos($key,'|'));
			$arr[$index]['lpxingzheng'] = substr($key,strpos($key,'|')+1,strpos($key,'*')-strpos($key,'|')-1);
			$arr[$index]['info_id'] = substr($key,strpos($key,'*')+1);
			$arr[$index]['count'] = $vo ;
			$index++;
		}
		//echo $Loupan->getLastSql();
		return $arr;
	}

/*
    根据区域ID返回区域的待收楼盘，最热楼盘，区域价格走势
    返回楼盘优先级从大到小的数组
*/
	public function getall_by_range_id($range_id,$len=6)
	{
		if(empty($range_id)){
			return null;
		}
		
		$Loupan = M('view_loupan');
		//区域待售楼盘
		$map['lp_state'] = 2 ;
		$map['range_id'] = $range_id ;
		$list_ds = $Loupan->field('info_id,lpid,title as lpname,lpprice,lpxingzheng,range_id,lpyouxian')->where($map)->order('lpyouxian desc')->limit("0,$len")->findAll();
		//echo $Loupan->getLastSql();
		$index = count($list_ds);
		$dump['daishou'] = $list_ds ;
		//区域在售最热楼盘
		unset($map);
		$map['lp_state'] = 1 ;
		$map['range_id'] = $range_id ;		
		$list_zs = $Loupan->field('info_id,lpid,title as lpname,lpprice,lpxingzheng,range_id,lpyouxian')->where($map)->order('lpyouxian desc')->limit("0,$len")->findAll();
		$dump['zaishou'] = $list_zs ;
		//区域价格走势
		$Model = new Model();
		$index = 0 ;
		for($i=-7;$i<=-1;$i++){
			$datetime = date('Y-m-d',strtotime($i." day"));
			$sum_price = 0 ;
			$sql = "select *  from t_view_loupan,t_new_loupan_temp where t_view_loupan.range_id='$range_id' and t_view_loupan.lpid=t_new_loupan_temp.lpid ";
			$sql .= " and t_new_loupan_temp.inserttime='$datetime' ";//and t_new_loupan_temp.lpprice>0" ;
			//echo $sql ;
			$rs = $Model->query($sql);
            $count = count($rs);
			foreach($rs as $vo){
				$sum_price = $sum_price+$vo['lpprice'] ;
				//echo $vo['lpprice']."|".$count;
			}
			//if($sum_price>0){
				$list_quyu[$index]['day'] = date('m-d',strtotime($i." day")); //$datetime ;
				$list_quyu[$index]['count'] = $count ;
				$list_quyu[$index]['avgprice'] = intval($sum_price/$count) ;
				if($list_quyu[$index]['avgprice']==0){
					$list_quyu[$index]['avgprice'] = $list_quyu[$index-1]['avgprice'];
				}
				$index++;
			//}	
		}
		$dump['quyu_price'] = $list_quyu ;
		//dump($list_quyu);
		//全市价格走势
		$index = 0 ;
		for($i=-7;$i<=-1;$i++){
			$datetime = date('Y-m-d',strtotime($i." day"));
			$sum_price = 0 ;
			$sql = "select *  from t_view_loupan,t_new_loupan_temp where  t_view_loupan.lpid=t_new_loupan_temp.lpid ";
			$sql .= " and t_new_loupan_temp.inserttime='$datetime' and t_new_loupan_temp.lpprice>0" ;
			//echo $sql ;
			$rs = $Model->query($sql);
            $count = count($rs);
			foreach($rs as $vo){
				$sum_price = $sum_price+$vo['lpprice'] ;
				//echo $vo['lpprice']."|".$count;
			}
			//if($sum_price>0){
				$list_all[$index]['day'] = date('m-d',strtotime($i." day")); //$datetime ;
				$list_all[$index]['count'] = $count ;
				$list_all[$index]['avgprice'] = intval($sum_price/$count) ;
				if($list_all[$index]['avgprice']==0){
					$list_all[$index]['avgprice'] = $list_all[$index-1]['avgprice'];
				}
				$index++;
			//}	
		}
		$dump['all_price'] = $list_all ;
		//dump($list_all);
		return $dump;
	}
}


?>