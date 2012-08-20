<?php
/**
 +------------------------------------------------------------------------------
 * Secretary  腾房前台小秘书类
 +------------------------------------------------------------------------------
 * @author 熊彦
 +------------------------------------------------------------------------------
 */
class SecretaryAction extends GlobalAction{

//---秘书首页---
	public function index(){
		//浏览
		$bro_list = $this->browser_ajax();
		$this->assign("bro_list", $bro_list);
		//欢迎词
		//$welcome = (!is_array($fav_list)? $fav_list: $this->welcome() );
		$welcome = $this->welcome();
		$this->assign("welcome", $welcome);
		if($this->userInfo === false){
			$this->assign("noLogin", 1);
		}else{
			$this->assign("userInfo", $this->userInfo);
		}
		$this->display();
    }

//---欢迎---
	public function welcome(){
		$tipArr = array(
			'您好，我是您的腾房小秘书，有事没事，您都可以随时叫我!!!',
			'您好，腾房小秘书在此恭候多时，请问有什么可以帮到您？',
			'提示：腾房网五一全新改版，全新体验，正式上线了！',
			'夏正炎时房正火！'
		);
		$key = array_rand($tipArr, 1);
		$tip = $tipArr[$key];
		//ajaxMsg($tip, 'tip',''); //提醒
		return $tip;
	}

	//---临时加载层---
	public function load_other(){
		//收藏
		//$fav_list = $this->favorite_ajax();
		//$this->assign("fav_list", $fav_list);
		$list = $_POST['list'];
		$tip  = $_POST['tip'];
		if($list){
			$this->assign("list", $list);
		}else{
			$this->assign("tip", $tip);
		}
		$this->assign("title", $title);
		$this->display();
	}

	//---用户收藏的楼盘---
    public function favorite_ajax()
	{
        $title = "你收藏过的楼盘";
		if($this->userInfo === false){
			ajaxMsg('您还没有登录，登录后小秘书才能告知您曾经收藏的楼盘！', 'error', $title );
			return;
		}
		$userid = $this->uid;
		$Model= new Model('Favorite');
		$sql = "select A.id,B.info_id,B.lpname from __TABLE__ as A join t_new_loupan as B on A.info_id= B.info_id where A.user_id=".$userid." order by A.id desc limit 0,5" ;
        $list = $Model->query($sql);
		if(!$list){
			ajaxMsg('您目前还没有收藏任何楼盘！', 'error', $title );
			return;
		}else{
			ajaxMsg('这是您曾经收藏的楼盘，小秘书提醒您泡上一杯清茶，再慢慢查阅！', 'success' , $title, $list);
			return;
		}
	}

	//---用户曾经浏览过的楼盘---
    public function browser_ajax()
	{
		$browser_lp = A('Interaction')->get_browser_record();
		//dump($browser_lp);
		$browser_lp = array_slice ( $browser_lp, 0 , 5 ); //取5条
		
		$Info = M('Info');
		foreach($browser_lp as $k=>$v){
			$map['info_id'] = $v;
			$vo = $Info->field('title as lpname')->where($map)->find();
			$list[$k]['lpname'] = $vo['lpname'];
			$list[$k]['info_id'] = $v;
		}
		return $list;
	}
}
?>