<?php
/**
 +------------------------------------------------------------------------------
 * Statistics  前台统计接口
 +------------------------------------------------------------------------------
 * @author 熊彦 <cnxiongyan@gmail.com>
 +------------------------------------------------------------------------------
 */
class StatisticsAction extends GlobalAction 
{
	public function index(){
		echo 'a';
		$this->remind($show_id=mt_rand(1,35),$come_url=__SELF__,$user_id=0,$type='review');  //进行统计	
		//A('Statistcs')->remind($show_id=$rs ,$come_url=__SELF__,$user_id=$this->getUid(),$type='review');  //进行统计
		/*
	//腾房网管理员的用户id数组
	'TF_ADMIN_ID'=>
	array(1047);*/
	}
	
	//---统计点评数---
	public function remind($show_id,$come_url,$user_id=0,$type='review')
	{
		/*--参数说明--
		    $show_id: 在展示页面的id,根据类型组合成show_url
			$url: 访问的路径
			$user_id: 用户的id
			$type:类型，默认为点评,  回复:reply; 留言: liuyan
			^类型:1为点评,2为回复，3为留言，4为律师咨询
		*/
		//echo 'b';
		$tf_admin_id=C('TF_ADMIN_ID');   //腾房管理员的user_id数组
		if(!isset($come_url)){
			return false; //如果连来路url都不存在,则不用统计
		}
		
		if(in_array($user_id,$tf_admin_id)){
			return false;  //如果是腾房管理员的user_id则不用统计
		}
		
		//开始进行统计
		$data['come_url']=$come_url;
		$data['user_id']   =$user_id;
		$data['show_id']=$show_id;
		$data['is_remind']=1;  //需要提醒
		$data['add_time']=time();
		
		if($type=='review'){
			$data['type']=1;	

		}else if($type=='reply'){
			$data['type']=2;	

		}else if($type=='liuyan'){
			$data['type']=3;	
			
		}else if($type=='law'){
			$data['type']=4;	
			
		}else{
			return false;//如果类型不在范围内也不统计	
		}
		
		//正式插入数据
		$RemindTable=M('remind');
		
		if($RemindTable->add($data)){
			//echo '成功';	
		}else{
			//echo '失败';
		}
		
		
		
		
		
		
		

		
	}
}
?>