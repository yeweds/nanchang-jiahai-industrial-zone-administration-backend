<?php
/**
 +------------------------------------------------------------------------------
 * Sale  销售中心类
 +------------------------------------------------------------------------------
 * @author 万超
 +------------------------------------------------------------------------------
 */
class SaleAction extends GlobalAction{

	function _initialize()
	{
		parent:: _initialize();
		 //广告
		$this->assign('AD_top',$this->AD['news_com_top']);   //新闻首页头部广告
		$this->assign('COL',$this->COL);
		$this->assign('userInfo', $this->userInfo);  //用户信息
	}

	public function index(){
		//路由规则 楼盘id——楼栋id——单元id
		$get_arr=$_GET['id'];
		/*分几种情况
		1. 没有id
		2.只有其中1-2个id
		3.3个id同时存在
		如果属于1，2，则要用一个false表示当前没有值,转而取默认的第一个值
		*/
		//先全部设为false,下面如果有值再赋值
		$info_id=false;         //楼盘id
		$loudong_id=false;  //楼栋id
		$unit_id=false;		   //单元id
		
		if($get_arr!=NULL){

			$get_arr=explode('-',$get_arr);
			//设为整型,防止sql注入
			foreach($get_arr as $k=>$v){
					$get_arr[$k]=intval($v);
			}
			$get_length=count($get_arr);
			if($get_length==3){
				$info_id=$get_arr[0];
				$loudong_id=$get_arr[1];
				$unit_id=$get_arr[2];
			}else if($get_length==2){
				$info_id=$get_arr[0];
				$loudong_id=$get_arr[1];
			}else if($get_length==1){
				$info_id=$get_arr[0];
			}
			
		}
			

		if(!$info_id){ $this->error('请选择一个楼盘'); }  //不存在楼盘id则退出本页面
		//查出当前楼盘的相关信息
		$map['info_id']=$info_id;
		$loupanTable=M('New_loupan');
		$loupan=$loupanTable->where($map)->find();
		$this->assign('loupan',$loupan);  //------assign
		//--默认图片
		$Attach = A('Attach');
		$att_rs = $Attach->getAttachByInfoId($info_id, $class_id = 0, $is_defautl=1);
		$default_pic = "<img src=\"http://nc.tengfang.net/".$att_rs."\" width=\"246\" height=\"200\" alt=\"".$loupan['lpname']."\" />";
		$this->assign('default_pic',$default_pic); //默认缩略图片
		
		
		//1. 根据楼盘查出所有楼栋
		$loudongTable=M('New_loudong');
		$loudong=$loudongTable->field('id,proid,proname,prosalenum,pronoaleanum,proydnum')->where($map)->order('-proname desc')->findAll();
		$this->assign('info_id',$info_id);  //------assign
		$this->assign('loudong',$loudong);  //------assign
	
		//1.1 当前选择的楼栋id,如果不存在楼栋默认选中第1个楼栋
		if(!$loudong_id){
			$loudong_info= $loudong[0];
			$loudong_id=$loudong_info['proid'];
		}else{
			$where['proid']=$loudong_id;
			$loudong_info=$loudongTable->field('id,proid,proname,prosalenum,pronoaleanum,proydnum')->where($where)->find();
		}
		$this->assign('loudong_info',$loudong_info);  //------assign

		
		//2.根据楼栋查出所有单元
		$roomTable=M('New_room');
		$map['proid']=$loudong_id;
		$unit=$roomTable->field('id,runit')->where($map)->group('runit')->order('runit')->findAll();
		
		$py_num=array('一','二','三','四','五','六','七','八','九','十');
		//进行一次中文数字的排序-------
		foreach($unit as $v){  //偷偷的建一个一维数组用于获取对应的单元键值
			$unit_only[]=$v['runit'];	
		}
		$unit_copy=$unit;
		$unit_two=array();
		foreach($py_num as $vo){  //从这里筛选排序
			$k=array_search($vo,$unit_only);
			if($k===false){
			}else{
				$unit_two[]=$unit[$k];
				array_splice($unit,$k,1);
				array_splice($unit_only,$k,1);
			}
		}
		$unit=array_merge($unit_two,$unit);  //合并不在排序范围内的文字,如店面
		//--排序结束
		
		//在一、二、三等后面加上单元
		foreach($unit as $k=>$v){
				if(in_array($v['runit'],$py_num)){
					$unit[$k]['runit']=$v['runit'].'单元';
				}
		}
		
		$this->assign('unit',$unit);  //------assign
		
		//2.1 当前选择的单元id,如果不存在单元默认选中第1个单元
		if(!$unit_id){
			$unit_name=$unit[0]['runit'];
			$unit_id= $unit[0]['id'];
		}else{
			$where['id']=$unit_id;
			$unit_name=$roomTable->field('id,runit')->where($where)->find();
			$unit_name=$unit_name['runit'];
			if(in_array($unit_name,$py_num)){
					$unit_name=$unit_name.'单元';
			}
		}
		$this->assign('unit_name',$unit_name);
		$this->assign('unit_id',$unit_id);  //------assign
		
		
		//3. 要根据单元查出所有的房间
		$room=$roomTable->field('*')->where($map)->order('-rroomnum desc')->findAll();
		$this->assign('room',$room);  //------assign
		//dump($room);
		
		//坐标相近(周边楼盘)
		  unset($where);
		  $Map = M('Map');
		  $curr_where['info_id'] = $info_id;
		  $curr_where['class_id']= 7 ; // 仅楼盘
		  $curr_map = $Map->field('info_id,point_x,point_y')->where($curr_where)->find();
		  $diam=0.09; //直径
		  $x_min = ($curr_map['point_x'] - $diam );
		  $x_max = ($curr_map['point_x'] + $diam );
		  $y_min = ($curr_map['point_y'] - $diam );
		  $y_max = ($curr_map['point_y'] + $diam );
		  unset($curr_where);
		  $curr_where['class_id']= 7 ; // 仅楼盘
		  $curr_where['point_x'] = array('between', $x_min.','.$x_max );
		  $curr_where['point_y'] = array('between', $y_min.','.$y_max );
		  $arr = $Map->field('info_id,point_x,point_y')->where($curr_where)->findAll();
		  //dump($arr);
		  //通过勾股定理求出离中心点的距离
		  foreach($arr as $k=>$v){
			if($v['info_id'] != $info_id){  
				  $x_length=$curr_map['point_x']-$v['point_x'];
				  $y_length=$curr_map['point_y']-$v['point_y'];
				  $info_arr[$v['info_id']]=sqrt($x_length*$x_length+$y_length*$y_length)*100; 
			}
		  }
		  asort($info_arr);  //按离中心点的远近进行排序
		  unset($map);
		  $i=1;
		  foreach($info_arr as $k=>$v){
			  $map['info_id']=$k;
			  $rs=$loupanTable->field('info_id,lpname,constant_price,lp_state,lp_zhutui')->where($map)->find();
			  if(!$rs) continue;
			  $rs['pic']= $Attach->getAttachByInfoId($k, $class_id = 0, $is_defautl=1);
			  $rs['length']=round($v,1);
			  $zb_loupan[]=$rs;
			  if($i==6) break;
			  $i++;
		  }
		  $this->assign('zb_loupan',$zb_loupan);  //------assign
		  
		  //同价位楼盘
		  unset($map);
		  if($loupan['constant_price']){
			  $min_price=$loupan['constant_price']-1000;
			  $max_price=$loupan['constant_price']+1000;
			  $map['constant_price'] = array('between', $min_price.','.$max_price );
			  $price_loupan=$loupanTable->field('info_id,lpname,constant_price,lp_state,lp_zhutui')->where($map)->order('constant_price desc')->limit('0,6')->findAll();
			  foreach($price_loupan as $k=>$v){
				  $price_loupan[$k]['pic']= $Attach->getAttachByInfoId($v['info_id'], $class_id = 0, $is_defautl=1);
			  }
		 	 $this->assign('price_loupan',$price_loupan);  //------assign
		  }
		  
		  //dump($price_loupan);
		  
		  $this->display();

		
	}
	
	//显示房间的详细信息
	public function  view(){
		
		//获取的房间id
		$room_id=intval($_GET['id']);
		if(!$room_id){ $this->error('请选择一个房间再查看'); }
		
		//查出当前房间的信息
		$map['id']=$room_id;
		$roomTable=M('New_room');
		$room=$roomTable->field('*')->where($map)->find();
		$this->assign('room',$room);  //------assign
		//dump($roomTable);
		//3. 要根据单元查出所有的房间
		unset($map);
		$map['info_id']=$room['info_id'];
		$map['proid']=$room['proid'];
		$map['runit']=$room['runit'];
		$rooms=$roomTable->field('*')->where($map)->order('-rroomnum desc')->findAll();
		$this->assign('rooms',$rooms);  //------assign
		
		
		//查出当前楼盘的相关信息
		unset($map);
		$info_id=$room['info_id'];
		$map['info_id']=$info_id;
		$loupanTable=M('New_loupan');
		$loupan=$loupanTable->where($map)->find();
		$this->assign('loupan',$loupan);  //------assign
		//--默认图片
		$Attach = A('Attach');
		$att_rs = $Attach->getAttachByInfoId($info_id, $class_id = 0, $is_defautl=1);
		$default_pic = "<img src=\"http://nc.tengfang.net/".$att_rs."\" width=\"246\" height=\"200\" alt=\"".$loupan['lpname']."\" />";
		$this->assign('default_pic',$default_pic); //默认缩略图片
		
		
		//坐标相近(周边楼盘)
		unset($where);
		$Map = M('Map');
		$curr_where['info_id'] = $info_id;
		$curr_where['class_id']= 7 ; // 仅楼盘
		$curr_map = $Map->field('info_id,point_x,point_y')->where($curr_where)->find();
		$diam=0.09; //直径
		$x_min = ($curr_map['point_x'] - $diam );
		$x_max = ($curr_map['point_x'] + $diam );
		$y_min = ($curr_map['point_y'] - $diam );
		$y_max = ($curr_map['point_y'] + $diam );
		unset($curr_where);
		$curr_where['class_id']= 7 ; // 仅楼盘
		$curr_where['point_x'] = array('between', $x_min.','.$x_max );
		$curr_where['point_y'] = array('between', $y_min.','.$y_max );
		$arr = $Map->field('info_id,point_x,point_y')->where($curr_where)->findAll();
		//dump($arr);
		//通过勾股定理求出离中心点的距离
		foreach($arr as $k=>$v){
		if($v['info_id'] != $info_id){  
			  $x_length=$curr_map['point_x']-$v['point_x'];
			  $y_length=$curr_map['point_y']-$v['point_y'];
			  $info_arr[$v['info_id']]=sqrt($x_length*$x_length+$y_length*$y_length)*100; 
		}
		}
		asort($info_arr);  //按离中心点的远近进行排序
		unset($map);
		$i=1;
		foreach($info_arr as $k=>$v){
		  $map['info_id']=$k;
		  $rs=$loupanTable->field('info_id,lpname,constant_price,lp_state,lp_zhutui')->where($map)->find();
		  if(!$rs) continue;
		  $rs['pic']= $Attach->getAttachByInfoId($k, $class_id = 0, $is_defautl=1);
		  $rs['length']=round($v,1);
		  $zb_loupan[]=$rs;
		  if($i==6) break;
		  $i++;
		}
		$this->assign('zb_loupan',$zb_loupan);  //------assign
		
		//同价位楼盘
		unset($map);
		if($loupan['constant_price']){
		  $min_price=$loupan['constant_price']-1000;
		  $max_price=$loupan['constant_price']+1000;
		  $map['constant_price'] = array('between', $min_price.','.$max_price );
		  $price_loupan=$loupanTable->field('info_id,lpname,constant_price,lp_state,lp_zhutui')->where($map)->order('constant_price desc')->limit('0,6')->findAll();
		  foreach($price_loupan as $k=>$v){
			  $price_loupan[$k]['pic']= $Attach->getAttachByInfoId($v['info_id'], $class_id = 0, $is_defautl=1);
		  }
		 $this->assign('price_loupan',$price_loupan);  //------assign
		}
		
		//查出当前楼盘-单元下的所有房屋

		
		$this->display();
	}

}
?>