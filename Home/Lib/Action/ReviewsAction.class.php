<?php
/**
 +------------------------------------------------------------------------------
 * Reviews  前台商家微评类
 +------------------------------------------------------------------------------
 * @author 熊彦
 +------------------------------------------------------------------------------
 */
class ReviewsAction extends GlobalAction{

//---微评首页---
	public function index(){

		$TableMx = M('Reviews');
		$map['model_name'] = 'shop'; //仅查商家
		$map['is_hide']    = 0;     //非禁用
		//$map['content']    = array('neq','该网友仅打了分，未留下任何高见.');  //内容不为空
		
		$listRows="30";
		import("ORG.Util.Page");
		$count=$TableMx->where($map)->count();
		$p=new Page($count,$listRows);
		$page=$p->show();
		//is_editor DESC, id DESC
	    $list =$TableMx->where($map)->order("id DESC")->limit($p->firstRow.','.$p->listRows)->findall();
		// 分类查明细
		if($list!==false){	
			$Lp = M('Shop');
			$ReplyT = M('Reviews_reply');
			
			foreach($list as $k=>$v){
					 
				  //将内容里面的图片显示出来
				  $content = $v['content'];
//dump($content);
				 $picture = $this->getimages($content);
				 $list[$k]['pic'] = $picture;

				 $list[$k]['content']=strip_tags($content); //过滤掉外链等
					//"/(?<=href=)([^\>]*)(?=\>)/"
				  unset($picture);
					
				$rs = $Lp->field('name')->where('id='.$v['info_id'])->find();
				$list[$k]['name'] = $rs ? $rs['name'] : '';
				//查回复
				unset($map);
				$map['reviews_mx_id']=$v['id'];
				$list[$k]['reply']=$ReplyT->where($map)->count();

				if($r['pic_url']==''||$r['pic_url']==NULL){
					//随机取一张图
					$index=mt_rand(1,82);
					$list[$k]['pic_url']='no('.$index.').jpg';	
				}else{
					$list[$k]['pic_url']=$r['pic_url'];
				}

			}
			//dump($list);
			$this->assign('list',$list);
			$this->assign('page',$page);
		}
		$this->display('Reviews/index');
    }

    
//---保存新闻等点评---
	public function insert(){
		
		$seccode =trim($_POST['checkcode']);
        if(md5($seccode)!=Session::get('verify')){
            $this->error('验证码错误!!!');
        }
		$content = trim($_POST['content']);
		if($content==''){
			$this->error("请输入点评内容");
		}
		if (!preg_match("/[\x7f-\xff]/", $content)) { //如果没有中文，出错
			$this->error("请输入点评内容");
		}
		if(stripos($_SERVER["HTTP_REFERER"],'www.0791hunqing.com')===false){  //如果不是从网站发布，出错（有人用机器发布)
			$this->error("请输入点评内容");
		}
		$model_name = trim($_POST['model_name']);
		if(!$model_name) $this->error("参数不正确");

		$info_id = intval($_POST['info_id']);
		if(!$info_id) $this->error("参数不正确");
		if( cookie::get('isReviewed_'.$info_id) === True ){
			$this->error("您刚刚点评过该信息,再次点评需在5分钟之后!");
		}
/*
		if($this->uid <= 1 ) {
			$v_user    = getRandUser();  //假的随机用户
			$this->uid                  = ($this->uid != 1 ?  $v_user['id'] : 1);
			$this->userInfo['username'] = $v_user['username'];  //假用
		}
		if(empty($this->userInfo['username'])){
			//重调一次
			$v_user = getRandUser();  //假的随机用户
			$this->uid                  = $v_user['id'];
			$this->userInfo['username'] = $v_user['username'];  //假用户
		}
		$data['user_name']= $this->userInfo['username'];  //点评用户名
*/

		$Table = M('Reviews');
		$data = $Table->create();
		$data['user_id']    = $this->uid;           //用户ID
		$data['info_id']    = $info_id;             //被点评信息info_ID
		$data['model_name'] = $model_name;          //被点评信息所属表
		
		if(!empty($_POST['username'])){
			$this->userInfo['username'] = $_POST['username'];
		} 
		$data['user_name']= ($this->userInfo['username'] =='' ? '游客': $this->userInfo['username']) ; //点评用户名

		$data['add_time'] = time();
		$data['content']  = $content; 
		//内容限140字

		$rs = $Table->add($data); // 保存明细
			   
		if($rs){
			cookie::set('isReviewed_'.$info_id , true, 60*5 ); //修改cookie保存时间
			$this->success('点评提交成功，谢谢！');
			return;
		}else{
			$this->error('可能因网络繁忙，点评失败！');
			return;
		}
	}

//---保存楼盘评分---
	public function insert_scroe() 
	{
		if($this->uid == 0) {
			//ajaxMsg('请先登录再发表点评！', 'error', '', $data='notLogin'); //未登录
			//return;
			$v_user    = getRandUser();  //假的随机用户
			$this->uid                  = $v_user['id'];
			$this->userInfo['username'] = $v_user['username'];  //假用
		}
		$id = intval($_POST['info_id']);
		
		if(cookie::get('isvote_'.$id) === true && isset($_POST['point_str']) ){
			ajaxMsg('您已经对该楼盘打过分了，一天之内请不要重复打分！', $status='error');
			return;
		}
		$Table = M('Reviews');
        $map['info_id'] = $id;  
        $vo = $Table->field('id')->where($map)->find();
		$dataMain['info_id'] = $id; 
        if($vo){
        	$Table->where($map)->save($dataMain);   //存在则修改
			$reviews_id = $vo['id'];
        }else{
        	$reviews_id = $Table->add($dataMain);   //不存在则添加
        }
        unset($map);
        $pointArr = explode(',', $_POST['point_str']);
        $pointSum = 0;
        foreach ($pointArr as $v){
        	$pointSum += $v;
        }
        $Table_add = M('Reviews_scroe');
		$data = $Table_add->create();
		$data['reviews_id']  = intval($reviews_id) ;   //点评ID
		$data['user_id']  = $this->uid ;               //用户ID
		
		if(empty($this->userInfo['username'])){
			$this->uid                  = $v_user['id'];
			$this->userInfo['username'] = $v_user['username'];  //假用户
		}
		$data['user_name']= $this->userInfo['username'];  //点评用户名
		//$data['user_name']= ($this->userInfo['username'] =='' ? '游客': $this->userInfo['username']) ; //点评用户名
		$data['add_time'] = time();
		$data['point_str']= (!isset($_POST['point_str'])? '6,6,6,6,6' : $_POST['point_str']);

		$arrPoint = explode(',', $data['point_str']); //按顺序拆分
		$data['jiage']    = $arrPoint[0];
		$data['huanjing'] = $arrPoint[1];
		$data['diduan']   = $arrPoint[2];
		$data['huxing']   = $arrPoint[3];
		$data['peitao']   = $arrPoint[4];
		
		if($_POST['g_or_b'] == ''){
			$data['g_or_b']  = ( $pointSum >= 30 ? 'g' :'b' );  //总计大于30为好评
		}else{
			$data['g_or_b']  = $_POST['g_or_b'];
		}
		$rs = $Table_add->add($data); // 保存明细
		//$d = $Table_add->getlastsql();
			   
		if($rs){
			//C('COOKIE_EXPIRE' , 60*60*12 );  
			cookie::set('isvote_'.$id , true, 60*60*12 ); //修改cookie保存时间
			//成功处理
			try{
				$this->updatePoint($reviews_id);   //取平均值更新点数---将来数据量大时，此步可利用凌晨延迟完成
				$this->makePointXml($id);          //根据info_id更新xml备用
			} catch (Exception $e) {
				Log::write('更新微评点数：'.$e->getMessage(), Log::Waring); //直接写入日志信息
			}
			ajaxMsg('评分成功！', $status='success');
			return;
		}else{
			ajaxMsg('评分失败！', 'error');
			return;
		}
	}
//----------------------以下是传统页面方法---------------------------
//传统楼盘详细页--楼盘点评
	public function ct_index(){
		$info_id = intval($_GET['info_id']);  
		A('General')->view_inc($info_id);  //信息页共用
		$map['info_id'] = $info_id;
		$vo_xx = D('Info')->where($map)->find();
		unset($map);
		//点评相关
        $Table = M('Reviews');
		//$info_id = intval($_GET['info_id']);
        $map['info_id'] = $info_id;  
        $vo = $Table->where($map)->find();     //查总账
		$vo['title']= $vo_xx['title'];
		$reviews_id = $vo['id'];
		$this->assign('vo',$vo);

        unset($map);
        $TableMx = M('Reviews_mx');
		$map['reviews_id'] = intval($reviews_id);
		$sum_pepole  = count($TableMx->query("select id FROM __TABLE__ WHERE type=1 AND reviews_id=".$reviews_id." GROUP BY user_id "));  
		//总参与人数
		//$sum_reviews = intval($vo['good'] + $vo['bad']);  //总微评论数
		$sum_reviews = $TableMx->where($map)->count();  //总微评论数
		$this->assign('sum_reviews',$sum_reviews);
		
		//$map['type']   = 1;   //类型为1
		//$map['g_or_b'] = 'g'; //支持

		$sql = "select id FROM __TABLE__ WHERE type=1 AND g_or_b='g' AND reviews_id=".$reviews_id." GROUP BY user_id ";
		$sum_good  = count($TableMx->query($sql));   //总好评人数

		$goumailv    =  round(($sum_good/$sum_pepole) * 100);  //购买率
		
		unset($map);
		$map['reviews_id'] = intval($reviews_id);
		$map['content']    = array('neq','该网友仅打了分，未留下任何高见.');  //内容不为空
		$map['is_hide']    = 0; //非禁用
        $list = $TableMx->where($map)->order('is_editor DESC, id DESC')->limit('0,10')->findAll(); // 查明细

        if($list){
        	$this->assign('list',$list); 
        }
//dump($list);
		$mxCount = $this->getMxCount($reviews_id); //各项统计
		$this->assign('mxCount',$mxCount); 


		$vo_lp = M('Shop')->where('info_id='.$info_id)->find();
		//dump($vo_lp);
		//类型说明

        $this->assign('info_id',$info_id);  
		$this->assign('lpname',$vo_lp['lpname']); 
		$this->assign('typeArr',$this->typeArr);
        $this->assign('sum_pepole',$sum_pepole);     //总参与人数
		$this->assign('sum_reviews',$sum_reviews);   //总微评论数
		$this->assign('goumailv',$goumailv);   //购买率
		//dump($zaishuoGood);
		//dump($listBad);
		$this->assign('userInfo', $this->userInfo); //用户信息
		$this->assign('flashXmlUrl', "i".$info_id.".xml?".time()); //路径后加当前时间，以免重复
		$this->display();
	}	
	//点评楼盘列表
	public function dp_list(){
		$Table   = M("Shop");

		//=====显示分页=======
		import("ORG.Util.Page");
		$count = $Table->where($map)->count(); //1.总的记录数
		$listRows = 10;					//2.每页显示的条数
		$p  = new Page( $count, $listRows );
		$page= $p->show(); 
		//=====end 分页=====

		//要据当前面面显示相应条数标签
		$list = $Table->where($map)->order($ord)->limit($p->firstRow.','.$p->listRows)->findall();  //获取楼盘
		//echo $Table->getlastsql();
		$Attach  = A('Attach'); //实例化附件类
		$Reviews = M('Reviews');
			foreach($list as $k=>$v){
					$info_id = $v['id'];
					//--默认图片
					$att_rs = $Attach->getLogoByInfoId($info_id);
					//if($att_rs != 'no.png'){
					//	$att_rs = "thumb_".$att_rs;  //不用缩略图
					//}
					$list[$k]['default_pic'] = $att_rs;
					//--计算总附件数
					$list[$k]['count_pic'] = $Attach->getAttachCount($info_id);
					//--总微评论数
					$map_r['info_id'] = $info_id;
					$rs = $Reviews->field('id')->where($map_r)->find();
					$map_p['reviews_id'] = intval($rs['id']);
					$list[$k]['sum_reviews'] = $Reviews->where($map_p)->count();  //总微评论数
			}
			$this->assign('list',$list);
			$this->assign('page',$page);		
			
			 //查出现10个评论最多的楼盘
			 unset($map);
			 $lpdp_list=$Reviews->query("SELECT info_id,count(id) as dp_num FROM __TABLE__ WHERE model_name='shop' GROUP BY info_id ORDER BY dp_num DESC LIMIT 0,10");
			 //查出楼盘相关信息
			 
			 foreach($lpdp_list as $k=>$v){
				 $info_id = $v['info_id'];
				 $map['id']=$info_id;
				 $lpdp_list[$k]['pic'] = $Attach->getLogoByInfoId($info_id);		 
				 $lpdp_list[$k]['lp']  = $Table->field('id,name,address,tel,hits')->where($map)->find();
			 }
			 
			 $this->assign('lpdp_list',$lpdp_list);			
			
			$this->display();
	}

//楼盘微评所有
	public function ct_list() 
	{
		//先检查是不是其它地方调用此页面
		$diao_yong	=false;     
		if($_GET['diao_yong']==1){
			$diao_yong	=true;
		}
		
		$info_id = intval($_GET['info_id']);
        $map['id'] = $info_id;
		
		$curr_time = time();
		if($curr_time - S("SHOP_DP_UPTIME".$info_id) > 3600 ){
			S("SHOP_DP_UPTIME".$info_id, time(), 3600);
			$this->updateLpReviews($info_id); //定时更新
		}

	//---楼盘信息 --- start
		$vo_lp = M('Shop')->where($map)->find();
		if(!$vo_lp) $this->error("该页面不存在,或已删除！");
		$this->assign('vo_lp',$vo_lp);
		//dump($vo_lp);
		//--默认图片
		$Attach = A('Attach');
		$att_rs = $Attach->getLogoByInfoId($info_id);
		$default_pic = "<img src=\"".$att_rs."\" width=\"246\" height=\"200\" alt=\"商家\" />";
		$this->assign('default_pic',$default_pic); //默认缩略图片
	//---楼盘信息 --- end 
unset($map);

		//注：为0是表示不限微评类型
        $TableMx = M('Reviews');
        $map['info_id']    = intval($info_id);
		$map['is_hide']    = 0; //非禁用
		$map['model_name'] = 'shop';
		//$map['content']    = array('neq','该网友仅打了分，未留下任何高见.');  //内容不为空
		
		$listRows="16";
		import("ORG.Util.Page");
		$count=$TableMx->where($map)->count();
		$p=new Page($count,$listRows);
		$page=$p->show();

	    $list =$TableMx->where($map)->order("sortrank DESC, id DESC")->limit($p->firstRow.','.$p->listRows)->findall();
		//dump($list);
		// 分类查明细
		if($list!==false){	
			
			$Lp = M('Shop');
			$ReplyT = M('Reviews_reply');
			foreach($list as $k=>$v){
				     
				    $content = $v['content'];
					 //将内容里面的图片显示出来
					$picture = $this->getimages($content);
					$list[$k]['pic'] = $picture;
					$list[$k]['content']=strip_tags($content); //过滤掉外链等
					//"/(?<=href=)([^\>]*)(?=\>)/"

					$rs = $Lp->field('name')->where('id='.$v['info_id'])->find();
					$list[$k]['name'] = $rs ? $rs['name'] : '';
					
					//查回复
					unset($map);
					$map['reviews_mx_id']=$v['id'];
					$list[$k]['reply']=$ReplyT->where($map)->count();
					
					//查用户及头像
					$user_id['id']=$v['user_id'];
					$r=M('User')->field('pic_url')->where($user_id)->find();
					//如果用户没头像就随便给他一个随机的头像
					if($r['pic_url']==''||$r['pic_url']==NULL){
						//随机取一张图
						$index=mt_rand(1,82);
						$list[$k]['pic_url']='no('.$index.').jpg';	
					}else{
						$list[$k]['pic_url']=$r['pic_url'];
					}
					$list[$k]['hits']= $v['hits'] + rand(3,15); //加点假人气
			}
					
			$this->assign('list',$list);
			$this->assign('page',$page);
		}
		
	 //查出现10个评论最多的楼盘
	 unset($map);
	 $lpdp_list=$TableMx->query("SELECT info_id,count(id) as dp_num FROM __TABLE__ WHERE model_name='shop' GROUP BY info_id ORDER BY dp_num DESC LIMIT 0,10");
	 //查出楼盘相关信息
	 
	 foreach($lpdp_list as $k=>$v){
		 $info_id   = $v['info_id'];
		 $map['id'] = $info_id;
		 $lpdp_list[$k]['pic'] = $Attach->getLogoByInfoId($info_id);		 
		 $lpdp_list[$k]['lp']=$Lp->where($map)->field("id,name,address,tel,hits")->find();
	 }
	 $this->assign('lpdp_list',$lpdp_list);
	//dump($lpdp_list);


		$lp_reviews = A("Index")->index_review_list($ord = 'content', $num = 10); //楼盘点评内容
		$this->assign('lp_reviews',$lp_reviews);
		//dump($lp_reviews);

		if($diao_yong){
			$this->display('Reviews/diaoyong_list');  //调用用此模板
		}else{
			$this->display('Reviews/ct_list');
		}
	}
	
		

//传统页面添加入库方法
	public function ct_insert(){

		$content = trim($_POST['lpdp_content']);
		if($content==''){
			$this->error("请输入点评内容");
		}
		if(preg_match("/13807083183/", $content)){
			$this->error("再发垃圾信息,将向网警举报你！");
		}
		
		if (!preg_match("/[\x7f-\xff]/", $content)) { //如果没有中文，出错
			$this->error("请输入点评内容");
		} 
		
		if(stripos($_SERVER["HTTP_REFERER"],'www.0791hunqing.com')===false){  //如果不是从网站发布，出错（有人用机器发布)
			$this->error("请输入点评内容");
		}

		if($this->uid <= 1 ) {
			//ajaxMsg('请先登录再发表点评！', 'error', '', $data='notLogin'); //未登录
			//return;
			$v_user    = getRandUser();  //假的随机用户
			$this->uid                  = ($this->uid != 1 ?  $v_user['id'] : 1);
			$this->userInfo['username'] = $v_user['username'];  //假用
		}
		$info_id = intval($_POST['info_id']);
		if(!$info_id) $this->error("参数不正确");
		

        unset($map);
        $pointArr = explode(',', $_POST['point_str']);
        $pointSum = 0;
        foreach ($pointArr as $v){
        	$pointSum += $v;
        }

        $Table_add = M('Reviews');
		$data = $Table_add->create();
		$data['user_id']    = $this->uid;           //用户ID
		$data['info_id']    = $info_id;             //楼盘info_ID
		$data['model_name'] = "shop";             //模型表
		$data['content']    = $content;           //内容
		
		if(empty($this->userInfo['username'])){
			//重调一次
			$v_user = getRandUser();  //假的随机用户
			$this->uid                  = $v_user['id'];
			$this->userInfo['username'] = $v_user['username'];  //假用户
		}
		//$data['user_name']= $this->userInfo['username'];  //点评用户名
		$data['user_name']= ($this->userInfo['username'] == '' ? '游客': $this->userInfo['username']) ; //点评用户名
		$data['add_time'] = time();
		$data['type']     = ($_POST['type_id'] == null ? 0 : $_POST['type_id']);
		$data['point_str']= (!isset($_POST['point_str'])? '6,6,6,6,6' : $_POST['point_str']);
		
		//内容限140字
		if($_POST['g_or_b'] == ''){
			$data['g_or_b']  = ( $pointSum >= 30 ? 'g' :'b' );  //总计大于30为好评
		}else{
			$data['g_or_b']  = $_POST['g_or_b'];
		}
		$rs = $Table_add->add($data); // 保存明细
		//echo $Table_add->getlastsql();

		if($rs){
			//A('Statistics')->remind($show_id=$rs ,$come_url=$_SERVER["HTTP_REFERER"],$user_id=$this->getUid(),$type='review');  //进行统计
			$this->updateLpReviews($info_id);     //更新统计
			$this->success('点评提交成功，谢谢！');
			return;
		}else{
			$this->error('可能因网络繁忙，点评失败！');
			return;
		}
	}

//正则获取文章中图片
	private function getimages($str)
	{
		$match_str ="/src=\"([^\"]+)/isu";
		preg_match_all($match_str,$str,$out);
		$new_arr = array_unique($out[0]);//去除数组中重复的值 
		if( count($new_arr)>0 ){
			foreach($new_arr as $k=>$v){
				$new_arr[$k] = str_replace('src="', '', $v);
			}
		}
		//dump($new_arr);
		return $new_arr;
	}


//----------------------以下是公共方法---------------------------

//---获取点评列表---
//--g_or_b:类型，num:条数,is_editor:是否编辑点评（1是0否2所有）,reviews_id:点评项目ID
	public function getReviewsList($model_name = 'shop', $num = 5, $info_id=0){
	
		//注：为0是表示不限微评类型
        $TableMx = M('Reviews');
		$map['model_name'] = $model_name; 
		if($info_id){
			$map['info_id'] = intval($info_id);
		}
		$map['content'] = array('neq','');  //内容不为空
		$map['is_hide'] = 0; //非禁用
        $list = $TableMx->where($map)->order('sortrank DESC, id DESC')->limit('0,'.$num)->findAll(); // 查明细
        if($list){
        	return $list;
        }else 
        	return false;
	}

//---取平均值更新点数---
	private function updatePoint($reviews_id){
		$Table = M('Reviews');
		$Table_mx = M('Reviews_mx');
		$Table_sr = M('Reviews_scroe');
		$map['reviews_id'] = $reviews_id;
		$count = $Table_sr->where($map)->count();

		$sum_jiage    = $Table_sr->where($map)->sum('jiage');
		$sum_huanjing = $Table_sr->where($map)->sum('huanjing');
		$sum_diduan   = $Table_sr->where($map)->sum('diduan');
		$sum_huxing   = $Table_sr->where($map)->sum('huxing');
		$sum_peitao   = $Table_sr->where($map)->sum('peitao');

		$map['g_or_b'] = 'g';
		$sum_good   = $Table_mx->where($map)->count();
		$map['g_or_b'] = 'b';
		$sum_bad    = $Table_mx->where($map)->count();

		$data['jiage']    = intval($sum_jiage / $count);
		$data['huanjing'] = intval($sum_huanjing / $count);
		$data['diduan']   = intval($sum_diduan / $count);
		$data['huxing']   = intval($sum_huxing / $count);
		$data['peitao']   = intval($sum_peitao / $count);

		$data['good']     = $sum_good;
		$data['bad']      = $sum_bad;
		$rs = $Table->where('id='.$reviews_id)->save($data);
		echo $Table->getlastsql()."</br>";
		return $rs;
	}

//---获取g/b统计---
	public function getMxCount($reviews_id){
//以后可缓存
		$Table_mx = M('Reviews');
		$map['reviews_id'] = $reviews_id;
		foreach($this->typeArr as $key=>$v){
			$map['type'] = $key;
			// 支持
			$map['g_or_b'] = 'g';
			$countGood[$key] = $Table_mx->where($map)->count();
			// 反对
			$map['g_or_b'] = 'b';
			$countBad[$key]  = $Table_mx->where($map)->count();
		}
		$rs['countGood'] = $countGood;
		$rs['countBad']  = $countBad;
		return $rs;
	}

	public function addReviewsHits(){
		$id = intval($_GET['id']);
		M('Reviews')->setInc('hits', 'id='.$id, '1');  //更新人气
		//echo $id;
	}
	
//更新Reviews表中 每个商家的 点评数
	private function updateLpReviews($info_id){
		$map['info_id'] = $info_id;
		$map['model_name'] = "shop";
		$TableMx = M('Reviews');
		$dp_hits = $TableMx->where($map)->sum('hits'); //总人气
		$dp_count = $TableMx->where($map)->count();     //总数
		
		$data['dp_hits'] = intval($dp_hits);
		$data['dp_count'] = $dp_count;
		//dump($data);
		$map_s['id'] = $info_id;
 		$rs = M('Shop')->where($map_s)->save($data);
		return $rs;
	}

	public function view(){

		if(isset($_GET['id'])){
			$id=intval($_GET['id']);	
		}else{
			$this->error('参数有误！');
		}
		
		$map['id']=$id;
		$TableMx = M('Reviews');
		$UserT   = M('User');
		$Lp      = M('Shop');
		$ReplyT  = M('Reviews_reply');	

		$review=$TableMx->where($map)->find();
		if(!$review) $this->error('您请求的页面不存在或已被删除！');
		
		$TableMx->setInc('hits', 'id='.$id, '1');  //更新人气
		//查头像
		if( empty($review['user_name']) ){
			$user    = getRandUser();  //假的随机用户
			if($review['user_id'] <= 1 ) {
				$map['id'] = ($review['user_id'] != 1 ?  $user['id'] : 1);
				$review['user_name'] = $user['username'];  //假用
			}
		}

		//如果用户没头像就随便给他一个随机的头像
		if($user['pic_url']==''||$user['pic_url']==NULL){
			//随机取一张图
			$index=mt_rand(1,82);
			$review['pic_url']='no('.$index.').jpg';	
		}else{
			$review['pic_url']=$r['pic_url'];
		}

	 //将内容里面的图片显示出来 -- start
		 $content = $review['content'];
		 $picture = $this->getimages($content);
		 $review['pic'] = $picture;
		 $review['content']=$content; 
		 //$review['content']=strip_tags($content); //过滤掉外链等
		 unset($picture);
	//将内容里面的图片显示出来 -- end

		 $this->assign("title_remark", mb_substr( strip_tags($content), 0, 60,"utf8" ).".." ); //用于SEO
		  
	//---楼盘信息 --- start
		unset($map);	
		$info_id = intval($review['info_id']);
        $map['id'] = $info_id;	
		$vo_lp = M('Shop')->where($map)->find(); 
		$review['name'] = $vo_lp ? $vo_lp['name'] : '';
		$this->assign('vo_lp',$vo_lp);

		//dump($vo_lp);
		//--默认图片
		$Attach = A('Attach');
		//$att_rs = $Attach->getAttachByInfoId($info_id, $class_id = 0, $is_defautl=1);
		$att_rs = $Attach->getLogoByInfoId($info_id); //改用logo
		$default_pic = "<img src=\"".$att_rs."\" width=\"246\" height=\"200\"  alt=\"楼盘\" />";
		$this->assign('default_pic',$default_pic); //默认缩略图片
	//---楼盘信息 --- end			
			
			//查回复
			unset($map);
			$map['reviews_mx_id']=$v['id'];
			$list[$k]['reply']=$ReplyT->where($map)->count();
			
		//查回复
		unset($map);
		//$ReplyT = M('Reviews_reply');
		$map['reviews_mx_id']=$id;
		$reply_list = $ReplyT->where($map)->order("id DESC")->select();
		
		//查用户
		unset($map);
		foreach($reply_list as $k=>$v){
			
		if( empty($v['user_name']) ){
			$user    = getRandUser();  //假的随机用户
			if($v['user_id'] <= 1 ) {
				$map['id'] = ($v['user_id'] != 1 ?  $user['id'] : 1);
				$v['user_name'] = $user['username'];  //假用
			}
		}				
		$reply_list[$k]['user_name']= $v['user_name'];
				//如果用户没头像就随便给他一个随机的头像
		if($user['pic_url']==''||$user['pic_url']==NULL){
			//随机取一张图
			$index=mt_rand(1,82);
			$reply_list[$k]['pic_url']='no('.$index.').jpg';	
		}else{
			$reply_list[$k]['pic_url']=$r['pic_url'];
		}


			 //将内容里面的图片显示出来 -- start
			 $content = $v['content'];
			 $picture = $this->getimages($content);
			 $reply_list[$k]['pic'] = $picture;
			 $reply_list[$k]['content']=strip_tags($content); //过滤掉外链等
			  //xy暂去$reply_list[$k]['content']=strip_tags($v['content']);
			 unset($picture);
			//将内容里面的图片显示出来 -- end


			  $rs = $Lp->field('name')->where('info_id='.$v['info_id'])->find();
			  $reply_list[$k]['lpname'] = $rs ? $rs['name'] : '';
	
		}
		
	 //查出现10个评论最多的楼盘
	 unset($map);
	 $lpdp_list=$TableMx->query("SELECT info_id, count(id) as dp_num FROM __TABLE__ WHERE model_name='shop' GROUP BY info_id ORDER BY dp_num DESC LIMIT 0,5");
	 //查出楼盘相关信息
	 
	 foreach($lpdp_list as $k=>$v){
		 $info_id = $v['info_id'];
		 $map['id'] = $info_id;
		 $lpdp_list[$k]['pic'] = $Attach->getAttachByInfoId($info_id, $class_id = 0, $is_defautl=1);		 
		 $lpdp_list[$k]['lp']=$Lp->field('id,name,address,tel,hits')->where($map)->find();
	 }

	 $this->assign('lpdp_list',$lpdp_list);		


		$review['hits'] = $review['hits'] + rand(3,15); //加假人气
		$this->assign('review',$review);
		$this->assign('reply',$reply_list);
		$this->assign('user_id',$this->getUid());
		
		$this->display();
	}
	
//回复点评
	public function insert_reply(){
		
		/*$seccode =trim($_POST['checkcode']);
		if(md5($seccode)!=Session::get('verify')){
			$this->error('验证码错误!!!');
		}
		*/
		$info_id = intval($_POST['info_id']);
		if(!$info_id) $this->error("参数不正确");

		$content=trim($_POST['reply']);
		if($content==''){
			$this->error("请输入回复内容");
		}
		if(preg_match("/13807083183/", $content)){
			$this->error("再发垃圾信息,将向网警举报你！");
		}
		if (!preg_match("/[\x7f-\xff]/", $content)) { //如果没有中文，出错
			$this->error("请输入回复内容");
		} 
		
		if(stripos($_SERVER["HTTP_REFERER"],'www.0791hunqing.com')===false){  //如果不是从网站发布，出错（有人用机器发布)
			$this->error("请输入回复内容");
		}
		
		$ReplyT = M('Reviews_reply');
		$data=$ReplyT->create();
		$data['add_time'] = time();
		$data['content']  = $content;
		$data['info_id']  = $info_id;
		$data['reviews_mx_id'] = $_POST['reviews_mx_id'];
		
		$map_['id'] = $this->uid;
		$curr_user = M('User')->where($map_)->find();
		$data['user_name'] = $curr_user['username'];
		
		if($this->uid <= 1 ) {
			$v_user    = getRandUser();  //假的随机用户
			$this->uid         = ($this->uid != 1 ?  $v_user['id'] : 1);
			$data['user_name'] = $v_user['username'];  //假用
		}
		$data['user_id']= $this->uid;

		$this->assign("jumpUrl",$_SERVER["HTTP_REFERER"]);			
		if($rs=$ReplyT->add($data)){
			A('Statistics')->remind($show_id=$rs ,$come_url=$_SERVER["HTTP_REFERER"],$user_id= $data['user_id'] ,$type='reply');  //进行统计
			$this->success("回复成功");
		}else{
			$this->error("网络繁忙！");
		}

		
	}
	
}
?>