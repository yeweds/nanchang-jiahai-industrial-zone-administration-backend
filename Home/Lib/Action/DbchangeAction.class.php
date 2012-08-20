<?php
/**
 +------------------------------------------------------------------------------
 * Dbchange  转地图坐标类
 +------------------------------------------------------------------------------
 * @author 熊彦
 +------------------------------------------------------------------------------
 */
class DbchangeAction extends GlobalAction{
//---首页---

//更新info_id
	public function setSellInfoId(){
		set_time_limit(0);
		ini_set('memory_limit','120M'); 
		$Sell = M('Sell');
		$Price = M('New_loupan_temp');  //每日成交价
		$Ld   = M('New_loudong');
		$Room   = M('New_room');
		$Info = M('Id_mapping');
		$list = $Info->field('info_id,gold_url,xjx_url,ncx_url,range_id')->findall();
		foreach($list as $v){
			$data['info_id'] = $v['info_id'];
			if($v['range_id'] == 13){
					$map['lpid'] = array('in',$v['ncx_url']);
 					$Sell->where($map)->save($data); //更新楼盘
					$Ld->where($map)->save($data);  //更新楼栋
					$Room->where($map)->save($data);  //更新房间
					$Price->where($map)->save($data);  //更新每日价格Info_id

					$this->setNcx($map, $v['info_id']); //更新南昌县累计套数等
					dump('南昌县：'.$v['ncx_url'].'-'.$v['info_id']);

			}else if($v['range_id'] == 14){
				if($v['xjx_url']){
					$map['lpid'] = array('in',$v['xjx_url']);
 					$Sell->where($map)->save($data);  //更新楼盘
					$Ld->where($map)->save($data);    //更新楼栋
					$Room->where($map)->save($data);  //更新房间
					$Price->where($map)->save($data);  //更新每日价格Info_id
					dump('新建：'.$v['xjx_url'].'-'.$v['info_id']);
				}
			}else{
				if($v['gold_url']){
						$map['lpid'] = array('in',$v['gold_url']);
						//$map['id']   = array('gt', 16690); 
						$Sell->where($map)->save($data);
						$Ld->where($map)->save($data);  //更新楼栋
						$Room->where($map)->save($data);  //更新房间
						$Price->where($map)->save($data);  //更新每日价格Info_id

					dump('南昌市：'.$v['gold_url'].'-'.$v['info_id']);
				}
			}
		}
	}

//更新南昌县累计套数等
	public function setNcx($map, $info_id){
		//$map['lpid'] = array('in',$v['ncx_url']);
		$Old = M('Old_loupan');
		$data['lpsalecount'] = $Old->where($map)->sum('lpsalecount');   //累计成交套数
		$data['lpnosalecount'] = $Old->where($map)->sum('lpnosalecount');  //累计可售套数
		unset($map);
		if($info_id != 0 ){
			$map['info_id'] = $info_id;
			M('New_loupan')->where($map)->save($data);
		}
	}

//更新销售统计(南昌市)
	public function setSellCount(){
		set_time_limit(0);
		ini_set('memory_limit','120M'); 
		$Lp = M('New_loupan');
		//$Info = M('Id_mapping');
		$Room   = M('New_room');

		$list = $Lp->field('lpid,info_id')->findall();
		foreach($list as $v){

			if($v['range_id'] < 13){
				//南昌市
				$map_ks['info_id'] = $v['info_id'];
				$map_ks['_string'] = " (rstatus='可预售') or (rstatus='商品房退房上市') ";
				$ks_count = $Room->where($map_ks)->count();  //1.总的记录数
				
				//累计已售
				$map_bks['info_id'] = $v['info_id'];
				$map_bks['_string'] = " (rstatus='已签合同') or (rstatus='已备案') or (rstatus='已登记权属') or (rstatus='提交备案') ";   
				$bks_count = $Room->where($map_bks)->count();  //1.总的记录数

				$data['lpsalecount'] = $bks_count;  //累计已售
				$data['lpnosalecount'] = $ks_count;
				$map['info_id'] = $v['info_id'];

 				$rs = $Lp->where($map)->save($data);
				dump($rs);
			}
		}
	}

//更新点评info_id
	public function setReviewsInfoId(){
		$T = M('Reviews');
		$MX = M('Reviews_mx');
		$list = $T->field('id,info_id')->findall();
		foreach($list as $v){
			
			if($v['info_id']){
				$data['info_id']  = $v['info_id'];
				$map['reviews_id']= $v['id']; 
 				$MX->where($map)->save($data);

				dump($v['info_id'].'-'.$v['id']);
			}
		}
	
	}

//价格排行
	public function price_ph(){
		ini_set('memory_limit','500M'); 
		set_time_limit(0);
		ignore_user_abort(true);

		$Sell  = M('Sell');
		$map['sell_time'] = array('between', "'2011-01-01','2011-06-30'");
		$map['range_id']  = array('lt', 13);

		$sList = $Sell->field('info_id,lpid,roomid')->where($map)->order('sell_time desc')->group('roomid')->findAll();
		//dump($sList);
		if($sList){
			$Room = M('New_room');
			$Lp = M('New_loupan');
			unset($map);
			foreach ($sList as $k=>$v){
				//$map['lpid']   = $v['lpid'];
				$map['roomid'] = $v['roomid'];
				$rs = $Room->field('rarea')->where($map)->find();  //该楼盘当天销售套数
				$info_id = $v['info_id'];
				$vo = $Lp->field('lpprice')->where('info_id='.$info_id)->find();
				
				if($vo['lpprice'] != 0){
					if($curr_p){
						$curr_p += $rs['rarea']* $vo['lpprice'];
					}else{
						$curr_p = $rs['rarea'] * $vo['lpprice'];
					}
					$list[$info_id] = $curr_p;
				}

			}
			asort($list);   //值从小到大排序
			dump($list);
			//return $arrData;
		}else{
			echo 'false';
		}
	}


   //临时重新生成缩略图
	public function reload_pic(){
		ini_set('memory_limit','500M'); 
		set_time_limit(0);
		ignore_user_abort(true);

		import('ORG.Util.Image');
		$p = new Image();
		$map['id'] = array('lt', 3944);
		$list = M('Attach')->where($map)->order('id asc')->limit('2000,3944')->findAll();
		foreach($list as $v){
			$pic = $v['savepath'].$v['savename'];
			$thumbpic = $v['savepath'].'thumb_'.$v['savename'];
			$rs[] = $p->thumb($pic, $thumbpic, $type='', $maxWidth=270, $maxHeight=220, $interlace=true);
		}
		dump($rs);

		
	}

//重写开发商拼音
	public function writeKfsPy(){
		//set_time_limit(0);
		//ini_set('memory_limit','50M'); 
		echo 1;
		$Table = M('Kfs');
		$field = 'id,name,head_py';
		$map = null;
		$list = $Table->where($map)->field($field)->limit(300)->findall();	
//dump($list);
		foreach($list as $v){
				if(!empty($v['head_py'])){
					$str = ltrim($v['head_py'],'ncs');
					$str = ltrim($str,'nc');
					$str = ltrim($str,'jxs');
					$str = ltrim($str,'jx');
					$data['head_py'] = $str;
					//$data['head_py']  = getPinyin($str, $ishead=1); //获取拼音
					dump($data['head_py']);
					$x['id'] =  $v['id'];
					$rs = $Table->where($x)->save($data);
					echo $rs;
				}
		}		
		echo '开发商拼音重写成功';
	}

}
?>