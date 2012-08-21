<?php
/**
 +------------------------------------------------------------------------------
 * Ask  在线咨询类
 +------------------------------------------------------------------------------
 * @author 万超/胡庆斌
 +------------------------------------------------------------------------------
 */
class AskAction extends GlobalAction{

	function _initialize()
	{
		parent:: _initialize();
		 //广告
		$this->assign('AD_top',$this->AD['news_com_top']);           //新闻首页头部广告
		$this->assign('COL',$this->COL);
		$this->assign('userInfo', $this->userInfo);  //用户信息
	}

 	//每个广告以模板方式存放，按编号列顺序
		public $AD=array(
			'news_index_top'=>'Ad:news_index_top',               //新闻首页顶部广告
			'news_index_center'=>'Ad:news_index_center',	    //新闻首页中间广告
			'news_com_top'=>'Ad:news_com_top',					//新闻共用头部广告
			'news_list_right'=>'Ad:news_list_right',					//新闻列表页面右边栏广告
			'news_xi_right'=>'Ad:news_xi_right',						//新闻详细页面右边栏广告
			'news_xi_center'=>'Ad:news_xi_center',					//新闻详细页面中间广告->放置在内容里面

		);

		//栏目id配置
		public $COL=array(
			'jryw'    =>'jryw',  //今日要闻
			'ldtj'    =>'ldtj',  //热点推荐
			'tdcr'    =>'tdcr',  //土地出让
			'tdcj'    =>'tdcj',  //土地成交
			'htpk'    =>'htpk', //话题pk
			'rdzt'    =>14,     //热点专题

		);

  //首页
	public function index(){

               $askTable = M('lawyer_ask');
       	       $replyTable = M('lawyer_reply');
       	       $lawyerTable = M('Lawyer');
       	 //根据条件查询
       	       $where['is_hide'] = 0;
       	       if(isset($_GET['search'])){
                     $key = urldecode(trim($_GET['key']));
                     if($key == '' || $key == '请输入关键字'){     //提交的查询关键字为空或者关键字为默认的关键字酒吧条件设置为空
                     	  $where[$_GET['search']] = array('like','%%');
                     }else{
                     	  $where[$_GET['search']] = array('like','%'.$key.'%');
                     }
           //   dump($where);
       	       }

       	//排序
       	       if(isset($_GET['ord'])){
       	       	       if($_GET['ord'] == 'desc'){
       	       	       	     $order = 'time desc';
       	       	       }else{
       	       	       	     $order = 'time asc';
       	       	       }
       	       }else{
       	       	       $order = 'time desc';

       	       }

        //flag表示显示已回复或者未回复的内容 0表示显示全部 1表示显示已回复的问题 2表示显示未回复的问题
       	       if(isset($_GET['flag'])){
                     $flag = intval($_GET['flag']);
                     $this->assign('flag',$flag);
       	       }else{
       	       	     $this->assign('flag',0);
       	       }

       	 //查询(分页)
       	       $count = $askTable->where($where)->count();
       	       $listRows = 10;
       	       import('ORG.Util.Page');
               $page = new Page($count,$listRows);  //总数 每页显示数
		       $show = $page->show();
		       $askList = $askTable->where($where)->order($order)->limit($page->firstRow.','.$page->listRows)->findAll();
       	//dump($askList);
               foreach($askList as $key=>$value){
               	  //将内容里面的图片显示出来
               	      if(preg_match('/\<img [^\>]*\/\>/',$askList[$key]['content'])){
                               preg_match_all('/src="[0-9a-zA-Z_.\/]*"/',$askList[$key]['content'],$picture);
                               $askList[$key]['pictures'] = $picture;
               	       	      
               	       }
					    $askList[$key]['content'] = strip_tags($askList[$key]['content']);

               	     //根据问题的id 找出对应的回复
               	       $map['ask_id'] = array('eq',$value['id']);
               	       $askList[$key]['count_reply'] = $replyTable->where($map)->count(); //计算回复的次数
               	       $askList[$key]['reply'] = $replyTable->where($map)->order('time asc')->findAll();  //找出所有回复

               	     //判断是否有flag 有的话就要筛选
               	   //  dump($askList);

               	       if(isset($flag)){
               	       	    if($flag == 1){   //只显示已经回复的
               	       	    	   if($askList[$key]['reply']==NULL){
               	       	    	   	     //  $this->assign('flag',$flag);
               	       	    	   	       unset($askList[$key]);
               	       	    	   	       continue;
               	       	    	   }
               	       	    }
               	       	    if($flag == 2){   //只显示未回复的
               	       	    	 if($askList[$key]['reply'] !=NULL){
               	       	    	 	      // $this->assign('flag',$flag);
               	       	    	 	       unset($askList[$key]);
               	       	    	   	       continue;
               	       	    	 }
               	       	    }
               	       }

               	     //根据回复的律师的id 找出律师的相关信息
               	       foreach($askList[$key]['reply'] as $k=>$v){
                            if(preg_match('/\<img [^\>]*\/\>/',$v['content'])){
                               preg_match_all('/src="[0-9a-zA-Z_.\/]*"/',$v['content'],$replypicture);
                               $askList[$key]['reply'][$k]['replypictures'] = $replypicture;
               	       	      

               	              }
							   $askList[$key]['reply'][$k]['content'] =strip_tags($v['content']);
               	       	         $lawyerList = $lawyerTable->field('id,name,lvsuo')->where('id='.$v['lawyer_id'])->find();  //找出回复律师的姓名 律所等信息
               	       	         $askList[$key]['reply'][$k]['lawyer'] = $lawyerList;
               	       }
               }
      //dump($askList);
           //根据条件显示本周推荐律师 只显示五个
               $lawyerMap = '';
               $fields = 'id,name,telephone,profession,head';
               $lawyers = $lawyerTable->where($lawyerMap)->limit('5')->field($fields)->findAll();
             // dump($lawyers);

       //判断登录的用户是否为律师
               $lawyerLogin = $this->checkLawyer();
               if($lawyerLogin){
               	    $this->assign('lawyerLogin',1);
               }else{
               	    $this->assign('fromUrl',base64_encode(__APP__.'/Law/index'));  //律师没有登录的话就跳转到登陆页面 并且指定跳转页面的地址 登录以后跳转过来
               }
               $this->assign('lawyers',$lawyers);
               $this->assign('list',$askList);
               $this->assign('page',$show);

			// 标题/关键字/描述
			$page_info['title']='南昌婚庆顾问,南昌婚庆网提供免费婚庆婚礼咨询'; 
			$page_info['keywords']='婚庆顾问,婚礼咨询';
			$page_info['description']='南昌婚庆网在线咨询栏目是为有婚庆、婚礼咨询需求的个人或团体提供在线咨询服务，您可以免费咨询专业的婚庆咨询师。';
			$this->assign('page_info',$page_info);
		       $this->display();

    }

  //回复
    public function reply(){
   //先判断该律师是否填写了个人的基本信息
    	$lawyerTable = M('Lawyer');
    	$map['id'] = array('eq',$this->getUid());
    	$rs = $lawyerTable->where($map)->select();
    	if(!$rs){
    		  $this->assign('jumpUrl',__URL__.'/lawyer_reg');
    		  $this->error('您还没有填写基本信息,请填写了基本信息以后再回复');
    	}
		if(!isset($_GET['id'])){
			$this->error('参数错误');
		}
		$id=intval($_GET['id']);
		if($id<1){
			$this->error('参数错误');
		}
		$content=trim($_POST['content']);
		if($content == ''){
			  $this->error('回复内容不能为空！');
		}
		$replyTable = M('lawyer_reply');
		$data['content'] = $content;
		$data['time'] = time();
		$data['ask_id'] = $id;
		$data['lawyer_id'] = $this->getUid();
    //判断是否已经回复
        $replyMap['ask_id'] = array('eq',$id);
        $replyMap['lawyer_id'] = array('eq',$this->getUid());
        $reply = $replyTable->where($replyMap)->find();
        if($reply){  //说明已经回复了
               $this->assign('jumpUrl',__URL__.'/detail/id/'.$reply[0]['ask_id']);
        	   $this->error('你已经回复了该信息，不能再回复');
        }
		//$this->assign('waitSecond', 60);
        if($replyTable->add($data)){
			   //echo $replyTable->getlastsql();
        	   $this->success('回复成功');
        }else{
        	   $this->error('回复失败，请重新回复');
        }
		

	}

//删除回复
   public function delReply(){
   	       if(!$this->checkLawyer()){
   	       	      $this->error('你还没有登录，不能执行此操作');
   	       }
   	       if(isset($_GET['id'])){
                  $replyTable = M('lawyer_reply');
                  $where['id'] = array('eq',intval($_GET['id']));
                /*防止通过url传id过来将别的信息删除
                  $list = $replyTable->where($where)->find();
  	      	      if($list['lawyer_id']!=$this->getUid()){
  	      	  	       $this->error('非法操作');
  	      	      }*/
  	      	      if($replyTable->where($where)->delete()){
  	      	      	   $this->success('删除成功');
  	      	      }else{
  	      	      	   $this->error('删除失败，请重试');
  	      	      }
   	       }else{
   	       	      $this->error('参数错误');
   	       }
   }

 //删除提问
   public function delAsk(){
   	       if(!$this->checkLawyer()){
   	       	      $this->error('你还没有登录，不能执行此操作');
   	       }
   	       if(isset($_GET['id'])){
                 $askTable = M('lawyer_ask');
       	         $replyTable = M('lawyer_reply');
       	         $map['id'] = array('eq',intval($_GET['id']));  //删除问题
       	         $where['ask_id'] = array('eq',intval($_GET['id']));  //删除回复
       	         if($askTable->where($map)->delete() && $replyTable->where($where)->delete()){
       	         	       $this->assign('jumpUrl',__URL__.'/index');
       	         	       $this->success('删除成功');
       	         }else{
       	         	       $this->error('删除失败，请重试');
       	         }
   	       }else{
   	       	     $this->error('参数错误');
   	       }
   }
  //咨询
    public function ask(){
    	    $askTable = M('lawyer_ask');
    	    $data['title'] = trim($_POST['title']);
    	    if(isset($_POST['contents'])){
    	         $data['content'] = trim($_POST['contents']);
    	    }else{
    	    	 $data['content'] = trim($_POST['content']);
    	       //除去开始的p标签
    	    	 $len = strlen($data['content']);
                 $data['content'] = substr($data['content'],3,$len-7);
    	    }

    	    $data['time'] = time();
    	    $user_id = intval(Cookie::get('uid'));
            $data['user_id'] = $user_id;    //如果没有cookie值 就默认为0（游客）
            $data['name'] = trim($_POST['name']);
            $data['link'] = trim($_POST['link']);
            if($data['title']==''||$data['name']==''||$data['link']==''){
            	     $this->error('请将信息填写完整');
            }
            if(md5($_POST['verify'])!=Session::get('verify')){
            	     $this->error('验证码错误');
            }
          // dump($data);
            if($rs=$askTable->add($data)){
				A('Statistics')->remind($show_id=$rs ,$come_url=$_SERVER["HTTP_REFERER"],$user_id=$this->getUid(),$type='law');  //进行统计
            	     $this->assign('jumpUrl',__URL__);
            	     $this->success('咨询成功，请静待婚庆咨询师的回复');
            }else{
            	    $this->error('咨询失败，请重试!');
            }
    }

    //详细页面
     public function detail(){
     	     if(isset($_GET['id'])){
     	     	  $id = intval($_GET['id']);
                  $where['id'] = $id;
                  $askTable = M('lawyer_ask');
       	          $replyTable = M('lawyer_reply');
       	          $lawyerTable = M('Lawyer');
       	        //找出提问的信息
       	          $list = $askTable->where($where)->find();
                  if(empty($list)){
                  	    $this->error('错误参数');
                  }
       	        //根据提问找出回复
       	          $count = $replyTable->where('ask_id='.$id)->count();
       	          $replyList = $replyTable->where('ask_id='.$id)->order('time asc')->findAll();
       	          foreach($replyList as $k=>$v){
       	          	      $lawyerList = $lawyerTable->field('id,name,lvsuo')->where('id='.$v['lawyer_id'])->find();  //找出回复律师的姓名 律所等信息
               	       	  $replyList[$k]['lawyer'] = $lawyerList;
       	          }
       	   // dump($list);
       	    //dump($replyList);

       	        //根据条件显示本周推荐律师 只显示五个
                  $lawyerMap = '';
                  $fields = 'id,name,telephone,profession,head';
                   $lawyers = $lawyerTable->where($lawyerMap)->limit('5')->field($fields)->findAll();
             // dump($lawyers);

             //判断登录的用户是否为律师
                  $lawyerLogin = $this->checkLawyer();
                  if($lawyerLogin){
               	      $this->assign('lawyerLogin',1);
               	      $this->assign('loginId',$this->getUid());
                  }else{
                  	  $this->assign('fromUrl',base64_encode(__APP__.'/Law/detail/id/'.$id));  //律师没有登录的话就跳转到登陆页面 并且指定跳转页面的地址 登录以后跳转过来
                  }
                  $this->assign('lawyers',$lawyers);
       	          $this->assign('k',1);
       	          $this->assign('count',$count);
       	          $this->assign('list',$list);
       	          $this->assign('replyList',$replyList);
				  
				// 标题/关键字/描述
				$page_info['title']=$list['title'].'-婚庆咨询详情 - 南昌婚庆网'; 
				$page_info['keywords']='婚庆顾问,婚礼咨询,婚庆疑问';
				$page_info['description']=$list['title'].$list['content'];
				$this->assign('page_info',$page_info);				  
				  
       	          $this->display();

     	     }else{
     	     	  $this->error('参数错误');
     	     }

     }

   //添加图片页面
    public function add_pictures(){
    	    $this->display();
    }

    //律师介绍页面
    public function lawyer_intro(){
    	    if(isset($_GET['id'])){
    	    	$askTable = M('lawyer_ask');
       	        $replyTable = M('lawyer_reply');
    	    	$lawyerTable = M('Lawyer');
    	   //根据id找出律师的信息
    	    	$id = intval($_GET['id']);
    	    	if($id == $this->getUid()){
    	    		   $this->assign('lawyerId',1);
    	    	}
    	    	$where['id'] = array('eq',$id);
    	    	$list = $lawyerTable->where($where)->find();
    	// dump($list);

    	   //找出这个律师所有回复的记录
    	        $map['lawyer_id'] = array('eq',$id);
    	        $count = $replyTable->where($map)->count();
       	        $listRows = 10;
       	        import('ORG.Util.Page');
                $page = new Page($count,$listRows);  //总数 每页显示数
		        $show = $page->show();
    	        $replyList = $replyTable->where($map)->limit($page->firstRow.','.$page->listRows)->order('time desc')->findAll();
                foreach($replyList as $key=>$value){
                	    $replyList[$key]['ask'] = $askTable->where('id='.$replyList[$key]['ask_id'])->field('id,title,content,time,name')->find();
                }
       // dump($replyList);
                $this->assign('page',$show);
                $this->assign('replyList',$replyList);
    	        $this->assign('list',$list);
    	        $lawyerLogin = $this->checkLawyer();
    	        if($lawyerLogin){
               	    $this->assign('lawyerLogin',1);
                }
				// 标题/关键字/描述
				$page_info['title']=$list['name'].'律师-房产法律咨询-腾房网'; 
				$page_info['keywords']='法律咨询,房产纠纷,房产官司,房产法规';
				$page_info['description']=$list['name'].'律师-专长:'.$list['profession'];
				$this->assign('page_info',$page_info);	
    	        $this->display();
    	    }else{
    	    	$this->error('参数错误');
    	    }

    }

  public function editReply(){
  	      if(!$this->checkLawyer()){
  	      	      $this->assign('jumpUrl',__URL__);
  	      	      $this->error('请先登录');
  	      }
  	      if(isset($_GET['id'])){
  	      	  $id = intval($_GET['id']);
  	      	  $replyTable = M('lawyer_reply');
  	      	  $map['id'] = array('eq',$id);
  	      	  $List = $replyTable->where($map)->find();
  	      	 /* if($List['lawyer_id']!=$this->getUid()){
  	      	  	    $this->error('非法操作');
  	      	  }*/
  	      	  $askTable = M('lawyer_ask');
  	      	  $where['id'] = array('eq',$List['ask_id']);
  	      	  $askList = $askTable->where($where)->find();
  	      	  $this->assign('askList',$askList);
  	      	  $this->assign('list',$List);
  	      	  $this->assign('prev_url',base64_encode($_SERVER['HTTP_REFERER']));
  	      	  $this->display();
  	      }else{
  	      	  $this->error('参数错误');
  	      }
  }
   //修改回答
   public function edit_reply(){
   	       if(isset($_POST)){
   	       	    $id = intval($_GET['id']);
				if($id<1){
					$this->error('参数错误');
				}		

   	       	    $where['id'] = $id;
   	       	    $replyTable = M('lawyer_reply');
   	       	    $data['content'] = trim($_POST['content']);
   	       	    $data['time'] = time();
   	       	    $ask_id = intval($_GET['ask_id']);
				if( empty($data['content']) ){
					$this->error('回复不能为空');
				}
                if($replyTable->where($where)->save($data)){
                	     $this->assign('jumpUrl',base64_decode($_GET['prev_url']));
                	     $this->success('修改成功');
                }else{
                	     $this->error('修改失败，请重新修改');
                }
   	       }else{
   	       	    $this->error('参数错误');
   	       }
   }

   //判读到底应该是进去那个页面（修改或者注册）
    public function lawyerMessage(){
    	    $lawyerTable = M('Lawyer');
    	    $map['id'] = array('eq',$this->getUid());
    	    $rs = $lawyerTable->where($map)->select();
    	    if(!$rs){
    	            $url = __URL__."/lawyer_reg";
			        header("Location: ".$url);
    	    }else{
                   $url = __URL__."/lawyer_edit";
			       header("Location: ".$url);
    	    }
    }

  //律师修改信息页面
    public function lawyer_edit(){
            if($this->checkLawyer()){
            	  if(isset($_GET['id'])){   //如果是从律师管理处传过来的id  判读当前用户是否为管理员
            	  	     $gid = $this->getUid();
                 	     $lawyerTable = M('Lawyer');
                 	     $gwhere['id'] = array('eq',$gid);
                 	     $glist = $lawyerTable->where($gwhere)->find();
                 	     if($glist['is_admin'] != 1){   //管理员才能从那里传参数
                 	             $this->error('非法操作');
                 	     }
                 	 	 $this->assign('is_admin',1);
                 	     $id = intval($_GET['id']);
            	  }else{
            	  	     $id = $this->getUid();
            	  }
                 	 $lawyerTable = M('Lawyer');
                 	 $where['id'] = array('eq',$id);
                 	 $list = $lawyerTable->where($where)->find();
                 	 if($list['is_admin'] == 1){   //是管理员的话就显示管理链接
                 	 	    $this->assign('is_admin',1);
                 	 }
                 	 $this->assign('myId',$this->getUid()); //用户判断是否显示修改密码
                 	 $this->assign('list',$list);
                 	// dump($list);
                 	 $this->display();
            }else{
            	  $uid = $this->getUid();
            	  if($uid == 0){     //表示没有登录
            	  	   $this->assign('jumpUrl',__APP__.'/Login/index');
            	       $this->error('你还没有登录，请先登录');
            	  }else{
            	  	   $this->error('您不是律师无权操作');
            	  }

            }
    }
//修改头像
   public function editHead(){
            $lawyerTable = M('Lawyer');
    	    $map['id'] = array('eq',$this->getUid());
    	    $rs = $lawyerTable->where($map)->find();
    	    $this->assign('list',$rs);
    	   // dump($rs);
    	    $this->display();
   }

   public function updateHead(){
   	       $id = $_POST['id'];
   	       $map['id'] = array('eq',$id);
   	       $lawyerTable = M('Lawyer');
   	       $list = $lawyerTable->where($map)->find();
   	       @unlink('./Public/user/'.$list['head']);
   	       $upInfo = $this->upHead();
    	 //dump($upInfo);
    	  $data['head'] = $upInfo[0]['savename'];
          if($lawyerTable->where($map)->save($data)){
          	        $this->assign('jumpUrl',__URL__.'/lawyer_edit');
          	        $this->success('修改头像成功');
          }else{
          	        $this->error('修改头像失败，请重试');
          }
   }
//提交修改信息
    public function lawyer_update(){
    	    if(isset($_POST)){
    	    	 $id = $_POST['id'];
    	    	 $where['id'] = array('eq',$id);
    	    	 $lawyerTable = M('Lawyer');
    	    	 $data['name'] = trim($_POST['name']);
                 $data['sex'] = $_POST['sex'];
    	    	 $data['confirm'] = trim($_POST['confirm']);
    	    	 $data['educate'] = trim($_POST['educate']);
    	    	 $data['position'] = trim($_POST['position']);
    	    	 $data['lvsuo'] = trim($_POST['lvsuo']);
    	    	 $data['diqu'] = trim($_POST['diqu']);
    	    	 $data['profession'] = trim($_POST['profession']);
    	    	 $data['telephone'] = trim($_POST['telephone']);
    	    	 $data['mail'] = trim($_POST['mail']);
    	    	 $data['address'] = trim($_POST['address']);
    	    	 $data['intro'] = trim($_POST['content']);
    	    	 if($data['confirm']==''||$data['lvsuo']==''||$data['profession']==''||$data['intro']==''){
    	    	 	     $this->error('请将信息填写完整');
    	    	 }
    	    	 if($lawyerTable->where($where)->save($data)){
    	    	 	   $this->success('修改信息成功');
    	    	 }else{
    	    	 	   $this->assign('jumpUrl',__URL__.'/lawyer_edit');
    	    	       $this->error('修改信息失败');
    	    	 }
    	    }else{
    	    	 $this->error('非法操作');
    	    }
    }

  //律师管理
    public function lawyerAdmin(){
    	    if($this->checkLawyer()){
                  $id = $this->getUid();
                  $lawyerTable = M('Lawyer');
                  $map['id'] = array('eq',$id);
                  $rs = $lawyerTable->where($map)->find();
                  if($rs['is_admin'] != 1){
                  	     $this->error('您不是管理员，无权操作');
                  }
                  $fields = '*';
                  $lawyerList = $lawyerTable->field($fields)->findAll();
                  $this->assign('list',$lawyerList);
                  $this->display();
    	    }else{
    	    	  $this->assign('jumpUrl',__URL__);
    	    	  $this->error('您无权操作此页面');
    	    }
    }

    //删除律师
    public function delLawyer(){
    	    if($this->checkLawyer()){
                  $id = $this->getUid();
                  $lawyerTable = M('Lawyer');
                  $userTable = M('User');
                  $map['id'] = array('eq',$id);
                  $rs = $lawyerTable->where($map)->find();
                  if($rs['is_admin'] != 1){
                  	     $this->error('您不是管理员，无权操作');
                  }
                  if(isset($_GET['id'])){
                       $did = intval($_GET['id']);
                       $delMap['id'] = array('eq',$did);
                       if($lawyerTable->where($delMap)->delete() && $userTable->where($delMap)->delete()){
                       	     $this->assign('jumpUrl',__URL__.'/lawyerAdmin');
                       	     $this->success('删除成功');
                       }else{
                       	     $this->error('删除失败');
                       }
                  }else{
                  	   $this->error('参数错误');
                  }
    	    }else{
    	    	 $this->assign('jumpUrl',__URL__);
    	    	 $this->error('你无权操作');
    	    }
    }

//修改密码
   public function editPwd(){
   	       if($this->checkLawyer()){
               	     $lawyerTable = M('Lawyer');
                     $userTable = M('User');
                     $lawyermap['id'] = array('eq',$this->getUid());
                     $lawyerlist = $lawyerTable->where($lawyermap)->find();
                     if($lawyerlist['is_admin'] == 1){
                     	    $this->assign('is_admin',1);
                     }
                     $userMap['id'] = array('eq',$this->getUid());
                     $userList = $userTable->where($userMap)->find();
                     $this->assign('list',$userList);
                     $this->display();

   	       }else{
   	       	   $this->assign('jumpUrl',__URL__);
   	       	   $this->error('您无权操作此页面');
   	       }
   }

 //更新密码
    public function updatePwd(){
    	    $id = $this->getUid();
    	    $where['id'] = array('eq',$id);
    	    $userTable = M('User');
    	    $list = $userTable->where($where)->field('id,pwd')->find();
    	  //  dump($list);
    	    if(md5($_POST['pwd'])!=$list['pwd']){
    	    	    $this->error('旧密码错误');
    	    }
    	    if(trim($_POST['npwd'])==''){
    	    	    $this->error('密码不能为空');
    	    }
    	    if($_POST['npwd']!=$_POST['npwd2']){
    	    	    $this->error('两次密码不一致');
    	    }
    	    $data['id'] = $this->getUid();
    	    $data['pwd'] = md5(trim($_POST['npwd']));
    	    if($userTable->save($data)){
    	    	 cookie::delete('userShell');
    	    	 Cookie::set('userShell',md5($data['pwd'].ShellSuffix));
    	    	 $this->assign('jumpUrl',__URL__.'/lawyer_edit');
                 $this->success('修改密码成功');
    	    }else{
    	    	 $this->error('修改密码失败');
    	    }
    }

  //添加律师
    public function addLawyer(){
    	      if($this->checkLawyer()){
                  $id = $this->getUid();
                  $lawyerTable = M('Lawyer');
                  $map['id'] = array('eq',$id);
                  $rs = $lawyerTable->where($map)->find();
                  if($rs['is_admin'] != 1){
                  	     $this->error('您不是管理员，无权操作');
                  }
                  $this->display();
    	      }else{
    	      	  $this->assign('jumpUrl',__URL__);
    	      	  $this->error('您无权操作');
    	      }
    }
  //律师账号入库
   public function insertLawyer(){
		   $verify = $_POST['verify'];
		   if(md5($verify) != $_SESSION['verify']){
				$this->error('验证码错误！');
		   }
		   if(trim($_POST['email'])==''||trim($_POST['pwd'])==''){
		   	       $this->error('请将信息填写完整');
		   }
		   if(trim($_POST['pwd'])!=trim($_POST['pwd2'])){
		   	        $this->error('两次密码不一致');
		   }
		   $userTable = M('User');
		   $vo['email'] = trim($_POST['email']);
		   $remap['email'] = array('eq',$vo['email']);
		   $record = $userTable->where($remap)->find();
		   if($record){
		   	       $this->error('已经存在该账户');
		   }
		   $vo['username']= trim($_POST['name']);
		   $vo['realname']= trim($_POST['name']);
		   $vo['pwd']= md5(trim($_POST['pwd']));
		   $vo['role_id'] = 9;
		   $vo['reg_time'] = time();
		   $insertId = $userTable->add($vo);
		   if($insertId){
		   	     $data['id'] = $insertId;
		   	     $data['name'] = $vo['username'];
		   	     $data['mail']= $vo['email'];
		   	     $data['telephone'] = trim($_POST['telephone']);
		   	     $data['is_admin'] = $_POST['is_admin'];
		   	     $data['address'] = trim($_POST['address']);
		   	     if(M('Lawyer')->add($data)){
		   	     	   $this->assign('jumpUrl',__URL__.'/lawyerAdmin');
		   	     	   $this->success('添加律师成功');
		   	     }else{
		   	     	 $this->error('添加律师失败');
		   	     }
		   }else{
		   	     $this->error('添加帐户失败');
		   }
   }

  //修改页面的图片
    public function pageSet(){
             if($this->checkLawyer()){
                  $id = $this->getUid();
                  $lawyerTable = M('Lawyer');
                  $map['id'] = array('eq',$id);
                  $rs = $lawyerTable->where($map)->find();
                  if($rs['is_admin'] != 1){
                  	     $this->error('您不是管理员，无权操作');
                  }
                  //echo APP_TMPL_PATH;
                  $this->display();
    	    }else{
    	    	  $this->assign('jumpUrl',__URL__);
    	    	  $this->error('您无权操作此页面');
    	    }
    }

//替换图片
  public function pageChange(){
             if($this->checkLawyer()){
                  $id = $this->getUid();
                  $lawyerTable = M('Lawyer');
                  $map['id'] = array('eq',$id);
                  $rs = $lawyerTable->where($map)->find();
                  if($rs['is_admin'] != 1){
                  	     $this->error('您不是管理员，无权操作');
                  }
                  $upInfo = $this->upAd();
                  $save_name = $upInfo[0]['savename'];
                // echo $save_name;
                  $file = './Home/Tpl/default/Ask/ad.html';
                  $contents = '<img src="../Public/Images/law_xiangqing/'.$save_name.'" width="966" height="136" />';
                  $r=@chmod($file,0777);
	              if(!is_writeable($file)){
		                $this->error("广告文件不支持写入，无法修改页面！");
		                exit();
	              }
		          file_put_contents($file,$contents);
		          $this->clearCache('./Home/Runtime');
		          $this->assign('jumpUrl',__URL__.'/pageSet');
		          $this->success('替换成功');
    	    }else{
    	    	  $this->assign('jumpUrl',__URL__);
    	    	  $this->error('您无权操作此页面');
    	    }
  }

 //删除缓存
 public function clearCache($path) {
		//echo $path;
       // import("@.ORG.Dir");
        import('ORG.Io.Dir');   //导入图像类
		if(is_dir($path)==true ){
			$dir = new Dir($path,$pattern='*');
			$dir->delDir($path);  //删除目录，包括文件
		}
  }

 //律师注册成用户以后进入的填写信息页面
    public function  lawyer_reg(){
    	    if($this->checkLawyer()){   //只有注册成为用户 并且用户组是律师的人才能进入此页面填写信息
    	    	$lawyerTable = M('Lawyer');
    	    	$map['id'] = array('eq',$this->getUid());
    	    	$rs = $lawyerTable->where($map)->find();
    	    	if($rs){
    	    		 $this->assign('jumpUrl',__URL__.'/lawyer_edit');
    	    		 $this->error('您已经填写了律师信息，不必重新填写。');
    	    	}
    	    	$this->display();
    	    }else{
    	    	$this->error('您无权操作此页面');
    	    }

    }

 //将律师注册信息入库
   public function lawyer_insert(){
   	       if($this->checkLawyer()){   //判断是否为律师提交过来的信息
                if(isset($_POST)){
                      $lawyerTable = M('Lawyer');
                      $data['id'] = $this->getUid();   //律师表的id对应用户表的id
                      $data['name'] = trim($_POST['name']);
                      $data['sex'] = $_POST['sex'];
                      $data['confirm'] = trim($_POST['confirm']);
    	    	      $data['educate'] = trim($_POST['educate']);
    	    	      $data['position'] = trim($_POST['position']);
    	    	      $data['lvsuo'] = trim($_POST['lvsuo']);
    	    	      $data['diqu'] = trim($_POST['diqu']);
    	    	      $data['profession'] = trim($_POST['profession']);
    	    	      $data['telephone'] = trim($_POST['telephone']);
    	    	      $data['mail'] = trim($_POST['mail']);
    	    	      $data['address'] = trim($_POST['address']);
    	    	      $data['intro'] = trim($_POST['content']);
    	    	     //dump($data);
    	    	      if($data['confirm']==''||$data['lvsuo']==''||$data['profession']==''||$data['intro']==''){
    	    	 	       $this->error('请将信息填写完整');
    	    	      }
    	    	      $upInfo = $this->upHead();
    	    	     //dump($upInfo);
    	    	      $data['head'] = $upInfo[0]['savename'];
    	    	     // dump($data);
    	    	      if($lawyerTable->add($data)){
    	    	      	   $this->assign('jumpUrl',__URL__);
    	    	      	   $this->success('填写信息成功');
    	    	      }else{
    	    	      	   $this->error('填写信息失败，请重新填写');
    	    	      }
                }
   	       }else{
   	       	    $this->assign('jumpUrl',__URL__);
   	       	    $this->error('您不是律师，无权操作，请先登录');
   	       }
   }

	//普通验证码显示
	public function verify(){
		import('ORG.Util.Image');   //导入图像类
		if(isset($_REQUEST['adv'])){
			Image::showAdvVerify();
		}else{
			Image::buildImageVerify();
		}
	}


//判断登录的用户是否是律师
	protected function checkLawyer(){
		   $user_id = $this->getUid();   //用户id
		   $role_id = $this->getGid();   //用户组id
		   $flag = false;
		   if($user_id!=0 && $role_id == 9){   //律师的组id为9
                     $flag = true;
		   }
		   return $flag;
	}
//上传广告图
   	protected function upAd(){
			import('ORG.Net.UploadFile');
			$upload=new UploadFile();
			$upload->maxSize='1000000';  //是指上传文件的大小，默认为-1,不限制上传文件大小bytes
			$upload->savePath='./Home/Tpl/default/Public/Images/law_xiangqing/';       //一切路径以入口文件为主 路径建议大家以主文件平级目录或者平级目录的子目录来保存
			$upload->saveRule=time;    //上传文件的文件名保存规则  time uniqid  com_create_guid  uniqid
			$upload->uploadReplace=true;     //如果存在同名文件是否进行覆盖
			$upload->allowExts=array('jpg','jpeg','png','gif','bmp','psd');     //准许上传的文件后缀
		//	$upload->allowTypes=array('image/png','image/jpg','image/pjpeg','image/gif','image/jpeg');  //检测mime类型
			if($upload->upload()){
					$info=$upload->getUploadFileInfo();
					return $info;
			}else{
				$this->error($upload->getErrorMsg());
			}
   	}
//上传头像
   protected function upHead(){
			import('ORG.Net.UploadFile');
			$upload=new UploadFile();
			$upload->maxSize='1000000';  //是指上传文件的大小，默认为-1,不限制上传文件大小bytes
			$upload->savePath='./Public/Upload/U/';       //一切路径以入口文件为主 路径建议大家以主文件平级目录或者平级目录的子目录来保存
			$upload->saveRule=time;    //上传文件的文件名保存规则  time uniqid  com_create_guid  uniqid
			$upload->allowExts=array('jpg','jpeg','png','gif','bmp','psd');     //准许上传的文件后缀
			//$upload->allowTypes=array('image/png','image/jpg','image/pjpeg','image/gif','image/jpeg');  //检测mime类型
            $upload->thumb=true;   //是否开启图片文件缩略
			$upload->thumbMaxWidth='200';  //以字串格式来传，如果你希望有多个，那就在此处，用,分格，写上多个最大宽
			$upload->thumbMaxHeight='240';	//最大高度

            $upload->thumbPrefix='';//缩略图文件前缀
			if($upload->upload()){
		     	    $info=$upload->getUploadFileInfo();
					return $info;
			}else{
				$this->error($upload->getErrorMsg());
			}

		}

}
?>