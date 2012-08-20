<?php
/**
 +------------------------------------------------------------------------------
 * Tips  提示相关类
 +------------------------------------------------------------------------------
 * @author 熊彦
 +------------------------------------------------------------------------------
 */
class TipsAction extends GlobalAction{
	
	public function index(){
		$this->display();
    }

//车位说明
	public function lp_chewei(){
		$this->display();
	}

//容积率说明
	public function lp_rongjilv(){
		$this->display();
	}

//物业费说明
	public function lp_wuyefei(){
		$this->display();
	}

//占地面积
	public function lp_zdmj(){
		//占地面积 100亩以下  小型社区（淡绿）
        //100-199亩  中型社区（淡绿）
        //200-399亩  大型社区（淡绿）
        //400亩以上  超大型社区（淡绿）
		$this->display();
	}

//建筑类别
	public function lp_jztype(){
		//1—3层为低层住宅；4—6层为多层住宅；7—9层为中高层住宅；10层以上为高层住宅
		$this->display();
	}

//同一开发商的楼盘
	public function lp_same_kfs(){
		$info_id  = intval($_GET['info_id']);
		$Lp = M('New_loupan');
		$map['info_id'] = $info_id;
		$vo = $Lp->where($map)->find();
		if(!$vo){
			return  false;
		}
		$num = 20 ; //取20条
		$map_s['lpcom'] = array('like', "%".$vo['lpcom']."%"); //条件开发商
		$list = $Lp->field('info_id,lpname,lpprice,lp_zhutui')->where($map_s)->order('lpyouxian desc')->limit('0,'.$num)->findall();
		//echo $Lp->getlastsql();
		if($list){
			$this->assign("list", $list);
		}
		$this->display();
	}

//同一物业的楼盘
	public function lp_same_wuye(){
		$info_id  = intval($_GET['info_id']);
		$Lp = M('New_loupan');
		$map['info_id'] = $info_id;
		$vo = $Lp->where($map)->find();
		if(!$vo){
			return  false;
		}
		if(!empty($vo['lpwycom']) && $vo['lpwycom'] != '暂无资料'){ 
			$num = 30 ; //取20条
			$map_s['lpwycom'] = array('like', "%".$vo['lpwycom']."%");  //物业
			$list = $Lp->field('info_id,lpname,lpprice,lp_zhutui')->where($map_s)->order('lpyouxian desc')->limit('0,'.$num)->findall();
			//echo $Lp->getlastsql();
			if($list){
				$this->assign("list", $list);
			}
		}
		$this->display();
	}


}
?>