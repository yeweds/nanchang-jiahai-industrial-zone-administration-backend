<?php
//短信
class MessagesAction extends  GlobalAction{

	public function index(){
	/*不根据时间段查询
     if(isset($_GET['ord'])){   //根据时间段查询
			$ord = $_GET['ord'];
			$s_time = strtotime($ord.'-00');
			$s_time = date('Y-m-d',$s_time);
			if(strstr($ord,'-02')){
				$e_time = $s_time + 3600*24*28; //2月
			  //  echo date('Y-m-d',$e_time);
			}else{
				$e_time = $s_time + 3600*24*30;
			}
			$e_time = date('Y-m-d',$e_time);
         $where['inserttime'] = array('between', $s_time.','. $e_time);
     }
     */

     if(isset($_POST['key'])){   //根据关键字查询
     	 $key = trim($_POST['key']);
     	 $map['content'] = array('like','%'.$key.'%');
     	 $map['lpname'] = array('like','%'.$key.'%');
     	 $map['_logic'] = 'or';
     	 $where['_complex'] = $map;
     }

     if(isset($_GET['info_id'])){
     	 $info_id = trim($_GET['info_id']);
     	 $where['info_id'] = array('eq',$info_id);
     }

     if(!isset($_POST['key']) && !isset($_GET['info_id'])){
     	 $where = '';
     }
       	      $Article=M('Xma_dx');
		      $listRows="20";
		      import("ORG.Util.Page");
		      $count=$Article->where($where)->count();
		      $p=new Page($count,$listRows);
		      $page=$p->show();

	          $list =$Article->where($where)->order("inserttime desc")->limit($p->firstRow.','.$p->listRows)->findall();
		      if($list!==false){
			           $this->assign('list',$list);
			           $this->assign('page',$page);
		      }
		 //dump($list);
		     /*
		      Cookie::set ( '_currentUrl_', __SELF__ ); //用于返回
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
            */
              $this->assign('id',$info_id); //用于get和post两个条件同时查询
       	      $this->display();

       }

//详细页
	public function  view(){
  		$id= intval($_GET['id']);
  		$map['id'] = $id;
  		$rs = M('Xma_dx')->where($map)->find(); 
		if(!$rs){
			$this->error('短信参数不正确!');
		}
  		$this->assign('notes',$rs);

		// 标题/关键字/描述
		$page_info['title']=$rs['inserttime'].'-'.$rs['lpname'].'-推广短信内容- '.C('cfg_sitename'); 
		$page_info['keywords']= $rs['lpname'].' '.$rs['content'].' '.C('cfg_metakeyword');
		$page_info['description']= $rs['content'].' '.C('cfg_metakeyword');
		$this->assign('page_info',$page_info);		

  	  	$this->display('Messages/view');
  	}

}
?>