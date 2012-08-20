<?php
/*
    Account 用户中心
	Author: 黄妃
*/
class AccountAction extends  GlobalAction{
	//---初始化 熊彦---
	private $uMap = '';
	function _initialize()
	{
		parent::_initialize();
		if($this->userInfo === false){
			$this->assign('error', '对不起，您还没有登录！');
			$this->redirect('Login/index','');
		}
		$this->assign('userInfo', $this->userInfo);		   //用户信息
		$this->uMap = A('Interaction')->get_search_map();  //获取已保存的搜索条件
		$this->assign('uMap', $this->uMap);		   //用户信息
	}

	//---用户管理中心 首页
	public function index()
	{
		$this->secretary_inc();  //小秘书提示
		$this->favorite_inc();   //收藏
		$this->display();
	}

	//---安全退出
	public function logout() 
    {
        if(isset($this->uid)) {
			//Cookie::destroy();
			cookie::delete('logTime');
			cookie::delete('uid');
			cookie::delete('userShell');
			$this->assign('jumpUrl',__APP__.'/');
            $this->success('您已成功退出!!!');
        }else {
            $this->assign('error', '已经退出！');
			$this->redirect(__URL__.'/login/',''); 
        }
    }

	//---小秘书用户中心提示---
	public function secretary_inc(){
		$currMap = $this->uMap;
		if(!is_array($currMap["range_id"])){
			$rangeName = '不限区域';
		}else{
			$rangeName = getAreaName( $currMap["range_id"][1] );
		}
		//dump($rangeName);
		if(!is_array($currMap["lpwuyetype"])){
			$lpwuyetype = '不限类型';
		}else{
			$lpwuyetype = trim($currMap['lpwuyetype'][1], '%');
		}

		$lpprice_arr = explode(',', $currMap['lpprice'][1]);
		$lpprice_min = $lpprice_arr[0];
		$lpprice_max = $lpprice_arr[1];
		$this->assign('lpprice_min', $lpprice_min);
		$this->assign('lpprice_max', $lpprice_max);
		$this->assign('rangeName', $rangeName);
		$this->assign('lpwuyetype', $lpwuyetype);
	}

	//---用户收藏的楼盘
    public function favorite_inc()
	{
        $userid = $this->uid;
		$Model= new Model();
		$sql = "select A.id,B.info_id,B.lpname,B.lpprice,B.lp_min_price,B.lp_max_price from t_favorite as A join t_new_loupan as B on A.info_id= B.info_id where A.user_id=".$userid." order by A.id desc limit 0,10 " ;
        $list = $Model->query($sql);
		if($list){
			$Loupan = A('Loupan');
			foreach($list as $k=>$v){
				$list[$k]['up_span'] =  $Loupan->getUpSpan($v['info_id']);  //涨跌幅
				$list[$k]['score']   =  $this->getScore($v['info_id']);  //评分点数
			}
			$this->assign('fav_list', $list);
		}
	}

	//---楼盘详细ajax
	public function user_lp_ajax(){
		$map['info_id'] = intval($_GET['info_id']);
		$vo = M('New_loupan')->where($map)->find();
		$this->assign('vo', $vo);
		$this->display();
	}

//用户浏览记录
    public function browser_record()
	{
		$userid = $this->uid;
		$Model= new Model('Browser_record');
		$sql = " select * from __TABLE__ as A left join t_new_loupan as B on A.info_id= B.info_id where A.user_id=".$userid.
			" order by A.id desc limit 0,10 " ;
        $list = $Model->query($sql);
		if($list){
			$Loupan = A('Loupan');
			foreach($list as $k=>$v){
				$list[$k]['up_span'] =  $Loupan->getUpSpan($v['info_id']);  //涨跌幅
				$list[$k]['score']   =  $this->getScore($v['info_id']);     //评分点数
			}
			$this->assign('bro_list', $list);
		}
		//dump($list);
		$this->display();
	}

//获取评分点数
	public function getScore($info_id){
		$map['info_id'] = $info_id;
		$vo = M('Reviews')->where($map)->find();
		$sum = round( ($vo['jiage']+$vo['huanjing']+$vo['diduan']+$vo['huxing']+$vo['peitao']) / 10, 0);
		return ($sum == 0 ? 1 : $sum);
	}


//修改用户信息 
    public function edit()
	{
        $map['id'] = $this->uid;
        $vo = M('User')->where($map)->find();
		$this->assign('UserVo', $vo);
		//dump($vo);
		$this->display();
	}

//保存用户信息 
    public function updateUser()
	{
        $map['id'] = $this->uid;
		$Table = M('User');
		$data = $Table->create();
        $vo = $Table->where($map)->save($data);
		if($vo !== false){
			$this->success('已成功更新您的用户资料!!!');
		}
	}
}