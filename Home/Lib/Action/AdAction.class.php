<?php
/**
 +------------------------------------------------------------------------------
 * Ad  广告相关类
 +------------------------------------------------------------------------------
 * @author 熊彦
 +------------------------------------------------------------------------------
 */
class AdAction extends GlobalAction{

//***推广info_id列表,若需增加，修改此处***
	public $TGID = '182,19'; 
	
//预留首页
	public function index(){
		$this->display();
    }

//显示广告详情
	public function view(){
		$info_id = intval($_GET['info_id']);
		
		$Table = D('New_loupan');
		$map['info_id'] = $info_id;
		$vo = $Table->where($map)->find();
		if(!$vo){
			echo "<script>alert('未找到该楼盘!');</script>";
			return;
		}
		$tpl = 'view_'.$info_id;
		$this->display($tpl);
    }

//获取推广广告列表
	public function getAdList(){
		$Table = M('View_allsite');
		//$map['is_tuijian'] = 1;
		$map['info_id'] = array('in', $this->TGID );  //推广
		$fields = 'info_id as id,title,range_id,lp_state,lpprice,lpwuyetype,lp_zhutui,lpnosalecount,point_x,point_y';
		$list = $Table->field($fields)->where($map)->order('lpyouxian desc')->limit('0,10')->findAll();
		foreach($list as $k=>$v){
			$list[$k]['is_tuijian'] = 1;
		}
		return $list;
	}


//---报广告展示--- 新闻更多
	public function baoguang_list(){
		$map_att['class_id'] = 35;
		$Table = M('Attach');

		if(isset($_GET['ord'])){
			$ord = $_GET['ord'];
			$s_time = strtotime($ord.'-00');
			if(strstr($ord,'-02')){
				$e_time = $s_time + 3600*24*28; //2月
				//echo date('Y-m-d',$e_time);
			}else{
				$e_time = $s_time + 3600*24*30;
			}
			$map_att['upload_time'] = array('between', $s_time.','. $e_time);
		}
		if(isset($_GET['info_id'])){
			$map_att['info_id'] = intval($_GET['info_id']);
		}
		//--按月查 -S 
		$curr_month = trim($_GET['curr_month']);
		if( isset($_GET['curr_month']) && !empty($curr_month) ){
			$s_time = strtotime($curr_month.'-00');
			if(strstr($curr_month,'-02')){
				$e_time = $s_time + 3600*24*28; //2月
				//echo date('Y-m-d',$e_time);
			}else{
				$e_time = $s_time + 3600*24*30;
			}
			$map_att['upload_time'] = array('between', $s_time.','. $e_time);
		}
		//--按月查 -E 
		$lpname_key = trim($_GET['lpname_key']);
		if(isset($_GET['lpname_key']) && !empty($lpname_key)){
			$Lp = M('New_loupan');
			$rs_lp = $Lp->field('info_id')->where("lpname ='".$lpname_key."'")->find();
			if( !$rs_lp ){
				$rs_list = $Lp->field('info_id')->where("lpname like '%".$lpname_key."%'")->findAll();
				foreach($rs_list as $v){
					$rs_lp[] = $v['info_id'];
				}
				$map_att['info_id'] = array('in', $rs_lp);
			}else{
				$map_att['info_id'] = $rs_lp['info_id'];
			}
		}

		//=====显示分页=======
		import("ORG.Util.Page");
		$count = $Table->where($map_att)->count(); //1.总的记录数
		$listRows = 18;					//2.每页显示的条数
		$p  = new Page( $count, $listRows );
		$page= $p->show(); 
		//=====end 分页=====

		//要据当前面面显示相应条数标签
		$list = $Table->field('*')->where($map_att)->order('upload_time desc')->limit($p->firstRow.','.$p->listRows)->findall();  //列表
		if($list){
				$Table = M('Info');
				foreach($list as $k=>$v){
					$rs = $Table->field('title as lpname')->where('info_id='.$v['info_id'])->find();
					$list[$k]['lpname'] = $rs['lpname'];
				}
				$this->assign('list',$list);			 //附件列表
				$this->assign('page',$page);
		}
		//dump($list);
		$currYear = date('Y-');
		$this->assign('currYear',$currYear);
		for($i=1; $i<=12; $i++){
			if($i<10){
				$yf = '0'.$i;
			}else{
				$yf = $i;
			}
			$month_list[] = $currYear.$yf;
		}
		$this->assign('month_list',$month_list); //月份列表
		$this->display();
	}

//---报广告展示---
	public function baoguang(){
			$map_att['class_id'] = 35;
			$list = M('Attach')->field('*')->where($map_att)->order('upload_time desc')->limit('0,30')->findAll();  //列表
			//$count = $t_attach->where($map_att)->count();  //统计
			if($list){
				$Table = M('Info');
				foreach($list as $k=>$v){
					$rs = $Table->field('title as lpname')->where('info_id='.$v['info_id'])->find();
					$list[$k]['lpname'] = $rs['lpname'];
				}
				$this->assign('list',$list);			 //附件列表
			}
			//dump($list);
			$this->display();
	}

//---报广告显示详细---
	public function baoguang_view(){
			//$map_att['class_id'] = 35;
			$map_att['id'] = $_GET['id'];
			$vo = M('Attach')->field('*')->where($map_att)->find();  //列表
			//dump($vo);
			if($vo){
				$Table = M('Info');
				$rs = $Table->field('title as lpname')->where('info_id='.$vo['info_id'])->find();
				$vo['lpname'] = $rs['lpname'];
				$this->assign('vo',$vo);	
			}
			$this->display();
	}

//---首页报广告展示嵌入---
	public function baoguang_inc(){
			$map_att['class_id'] = 35;
			$list = M('Attach')->field('*')->where($map_att)->order('upload_time desc')->limit('0,30')->findAll();  //列表
			//$count = $t_attach->where($map_att)->count();  //统计
			if($list){
				$Table = M('Info');
				foreach($list as $k=>$v){
					$rs = $Table->field('title as lpname')->where('info_id='.$v['info_id'])->find();
					$list[$k]['lpname'] = $rs['lpname'];
				}
				//$this->assign('list',$list);			 //附件列表
			}else{
				$list = false;
			}
			return $list;
	}
}
?>