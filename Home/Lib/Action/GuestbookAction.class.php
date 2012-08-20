<?php
/**
 +------------------------------------------------------------------------------
 * Guestbook  前台留言簿类
 +------------------------------------------------------------------------------
 * @author 熊彦
 +------------------------------------------------------------------------------
 */
class GuestbookAction extends GlobalAction {
//---留言列表	
    public function index() {
		require_once(APP_PATH.'/Common/qunfei_ubb.php'); //UBB处理
		//是否打开留言审核
		if(C('cfg_ischeck')){
		 $where['passed']= 1 ;
		}
		else { $where=NULL; }

		// 构造查询条件
		if (isset($_GET['key']))
			{ 
			 $key=trim($_GET['key']);
			}
			else
			{ $key=''; }
		if (isset($_GET['condition']))
			{ 
			 $condition=trim($_GET['condition']);
			}
			else
			{ $condition=''; }
			switch ($condition){
				case "uname":
				  $where['uname']= array('like','%'.$key.'%');
				  break;
				case "title":
				  $where['title']= array('like','%'.$key.'%');
				  break;
				case "text":
				  $where['content']= array('like','%'.$key.'%');
				  break;
				case "reply":
				  $where['reply']= array('like','%'.$key.'%');
				  break;
			default:
				$where['content']= array('like','%'.$key.'%');
			  }
		// 构造查询条件结束

		$idea = D("Guestbook");
		// 查询字段
		$fields = '*';
		// 所有数据量
		$count = $idea->where($where)->field('id')->count(); 
		//每页显示的行数
        $listRows = '6';
        import("ORG.Util.Page"); 
        $page = new Page($count, $listRows);
		$list = $idea->where($where)->field($fields)->limit($page->firstRow.', '.$page->listRows)->order('id desc')->findall();
		$p = $page->show(); 
		// 模板输出
		if($list) {
			foreach($list as $k=> $v){
				if ($v['hidden']==0) { 
					$list[$k]['content'] = epost($v['content'],$face_dir='../Public/Img/guest/post/'); 
				} else $v['content']= "此留言为悄悄话！";
			} 
			$this->assign("list",$list);
		}
        $this->assign('page',$p);
        //$this->assign("cfg_df_style",C('cfg_df_style'));
		$this->assign('TitleMsg','查看留言');
		$this->display();
    }

///---留言提交表单---
	public function add() {
		//$this->assign("cfg_df_style",C('cfg_df_style'));
		$this->assign('TitleMsg','我要留言');
		$this->display();
	}

///---保存留言入库---
	public function insert() {
		if( md5($_POST["CheckCode"]) != $_SESSION['verify']){
			$this->error('验证码错误！请刷新验证码再重新提交.');
		}
		$pic = ($_POST['ihead']==0 )? '':$_POST['ihead'].".gif";
 
		$Guest = D("Guestbook");
		// 检查数据插入
		$data = $Guest->create();
		if(false === $data) {
        	$this->error($Guest->getError());
        }
			// 处理数据安全
			$data['content'] = htmlspecialchars(trim($_POST['content']));
			$data['pic']     = $pic;
			include_once THINK_PATH.'/Common/extend.php'; //导入扩展函数
			$data['ip']       = get_client_ip();
			$data['add_time'] = time();

			if($rs=$Guest->add($data)) {
				//echo $this->getUid();
				A('Statistics')->remind($show_id=$rs,$come_url=$_SERVER["HTTP_REFERER"],$user_id=$this->getUid(),$type='liuyan');  //进行统计
				$this->assign('jumpUrl',__APP__.'/Index'); 
				$this->success('留言提交成功，感谢您的支持！');
			} else {
				
				$this->error('留言提交失败,请联系管理员！');
			}
	
	}

}
?>