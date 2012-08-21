<?php
/**
 +------------------------------------------------------------------------------
 * Bus  公交展示
 +------------------------------------------------------------------------------
 * @author 万超
 +------------------------------------------------------------------------------
 */
class BusAction extends GlobalAction{

	//---全部展示--- xiongyan 修改 2011-5-6
	public function index(){
		
		$info_id = intval($_GET['info_id']);
		
		if(empty($info_id)){
             $this->error('参数错误！');
			 exit;
		}		
		$lpT=M('new_loupan');
		$map['id']=$info_id;
		$lp=$lpT->where($map)->find();
		
		$lineT=M('bus_line');
		$busT=M('bus_lp');
		unset($map);
		$map['info_id']=$info_id;
		//查出所有当前楼盘所拥有的公交数，
		$bus=$busT->where($map)->select();
		unset($map);
		//查出线路信息
		foreach($bus as $k=>$v){
			$map['id']=$v['bus_id'];
			$bus[$k]['bus']=$lineT->where($map)->find();
			//将线路站路的值拆分成数组
			$bus[$k]['station']=preg_replace('/([0-9]+\.)|。/','',$bus[$k]['station']);
			$bus[$k]['bus']['bus_line']=preg_replace('/([0-9]+\.)|。/','',$bus[$k]['bus']['bus_line']);
			//$bus[$k]['bus']['bus_line']=str_replace('（','(',$bus[$k]['bus']['bus_line']);
			//$bus[$k]['bus']['bus_line']=str_replace('）',')',$bus[$k]['bus']['bus_line']);
			//$bus[$k]['bus']['bus_line']=str_replace('……','—',$bus[$k]['bus']['bus_line']);
			$bus[$k]['bus']['bus_line']=explode(',',$bus[$k]['bus']['bus_line']);		
			$bus[$k]['bus']['count']=100/count($bus[$k]['bus']['bus_line'])-2;	
			//$bus[$k]['bus']['bus_line']=preg_split('/\,?[0-9]+\./',$bus[$k]['bus']['bus_line']);
		}
		//dump($bus);
		//dump($lp);
		$this->assign("lp",$lp);
        $this->assign("bus",$bus);
		$this->display();
    }


}
?>