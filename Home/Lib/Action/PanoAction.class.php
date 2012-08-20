<?php
/**
 +------------------------------------------------------------------------------
 * Pano  3D类
 +------------------------------------------------------------------------------
 * @author 熊彦
 +------------------------------------------------------------------------------
 */
class PanoAction extends GlobalAction{

//---列表页---
	public function index(){
		$type = trim($_GET['search_type']);
		$key  = urldecode($_GET['key']);
		if(!empty($key)){
			$map['lpname'] = array('like',"%".$key."%");
		}else{
			$map = null;
		}
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
		if($tmp_map[0] == 'a'){
			$map['range_id'] = $tmp_map[1];  //区域
		}
		//dump($map);
		if(isset($_GET['ord'])){
			$ord_str = trim($_GET['ord']);
			$tmp_ord = explode('-', $ord_str);
			if($tmp_ord[0] == 'p'){
				$ord = 'lpprice '.$tmp_ord[1];
			}else{
				$ord = 'lptimeb '.$tmp_ord[1];
			}
		}else{
			$ord = 'lpyouxian desc';
		}
		//获取已做好的3D楼盘 --start
		$lpArr = A('Empty')->lpArr;
		$lpId  = array_keys($lpArr);
		//获取已做好的3D楼盘 --end
		$map['info_id'] = array('in', $lpId);

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
		$Attach  = A('Attach'); //实例化附件类
		$Reviews = M('Reviews'); 
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
					$rs = $Reviews->field('good,bad')->where($map_r)->find();
					$list[$k]['sum_reviews'] = intval($rs['good'] + $rs['bad']);  
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

		$this->assign('userInfo', $this->userInfo);  //用户信息
		$this->display('ct_list');
    }

//3D详细页
	public function view(){
		if(isset($_GET['info_id'])){
			
		}
		$this->display();
    }
}
?>