<?php
/**
 +------------------------------------------------------------------------------
 * Showlove  婚礼晒台类
 +------------------------------------------------------------------------------
 * @author  熊彦/王一凡
 +------------------------------------------------------------------------------
 */
class ShowloveAction extends GlobalAction{

//---默认列表---
	public function index(){
		$Table = M("User");
		$map['open_blog'] = 1;
		//$map['ispublish'] = 1;

		//=====显示分页=======
		import("ORG.Util.Page");
		$totalRows = $Table->where($map)->count();
		$listRows= "10";
		$p  = new Page( $totalRows, $listRows );
		$page= $p->show(); 

		$list =  $Table->field('*')->where($map)->order('id desc')->limit($p->firstRow.','.$p->listRows)->findall(); 
		if($list){
			foreach($list as $k=>$v){
				$list[$k]['remark'] = strip_tags($v['remark']);
				$list[$k]['thumb_pic'] = "/Public/Upload/".( empty($v['pic_url']) ? "no.png" : "U/".$v['pic_url']);
			}
			$this->assign('list',$list); //标题列表
		}

		$this->assign('page',$page); 	

//dump($list);

		//右侧新闻列表 s
		$news_clhl = A("Index")->get_news_list( $num = 3, $class_id = 19, $is_pic = True ); //商家,原潮流婚礼
		$news_hlzx = A("Index")->get_news_list( $num = 8, $class_id = 14 ); //婚礼资讯

		$this->assign("news_clhl", $news_clhl);
		$this->assign("news_hlzx", $news_hlzx);
		//右侧新闻列表 e

		$this->display();
    }

//---博客公共头部数据---
	public function pub_inc($id){
		$id = intval($id);
		if(!$id){
			$this->error('参数错误');   
		}
		
		$curr_uid = $this->getUid(); //当前用户id
		if($curr_uid == $id && $id!=0){
			$is_me = True;
		}else{
			$is_me = False;
		}
		$this->assign('is_me', $is_me);

		$Form=M("User");
		$map['id'] = $id;
		$vo = $Form->where($map)->find();
		
		if($vo['open_blog'] != 1 ) $this->error('该用户尚未开通婚礼晒台！');  
		if($vo['ispublish'] != 1 ) $this->error('该用户的婚礼晒台，不对外公开！'); 

		$this->assign('uvo',$vo);
    }

//---详情---
	public function view(){
		$info_id = $_GET['id']; //所属blog
		$this->pub_inc($info_id);   //博客公共头部数据
		
		$this->display();
    }


//---上传婚礼跟拍等---
	public function upload_att(){
		$info_id = $_GET['uid']; //所属blog
		$this->pub_inc($info_id);   //博客公共头部数据

		$class_id = intval($_GET['class_id']); //分类id
		if ($class_id<=0)
			$this->error('分类参数有误!');

		$map['pid'] = 9 ;//博客附件大类
		$attach_list = D("Class")->where($map)->order("sortrank ASC,id ASC")->findAll();
		$this->assign('attach_list',$attach_list);
		$this->assign('info_id', $info_id);

		$map_att['info_id'] = $info_id;
		$map_att['model_name'] = "blog";

		$listRows="16";

		$Table = M("Attach");
		import("ORG.Util.Page");
		$count=$Table->where($map_att)->count();
		$p=new Page($count,$listRows);
		$page=$p->show();
	    $list =$Table->where($map_att)->limit($p->firstRow.','.$p->listRows)->findall();//显示已上传

		unset($map);
		$map['id'] = $info_id;
		$vo_lp = M('Shop')->field('id,default_attach_id')->where($map)->find();
		if($list){
			$this->assign('list',$list);
			$this->assign('page',$page);
			$this->assign('df_att_id',$vo_lp['default_attach_id']);
		}

		$this->display();
	}


	//---婚礼祝福---
	public function Guest(){
		$info_id = $_GET['uid']; //所属blog
		$this->pub_inc($info_id);   //博客公共头部数据
		unset($map);
		
		$map['user_id'] = intval($info_id) ;  //当前用户
		
		$idea = M("Guestbook");
		$fields = '*';
		$count = $idea->where($map)->field('id')->count();
        $listRows = '6'; //每页显示的行数
        import("ORG.Util.Page"); 
        $p = new Page($count, $listRows);
		$list = $idea->where($map)->field($fields)->limit($p->firstRow.', '.$p->listRows)->order('id desc')->findall();
		$page = $p->show(); 
		// 模板输出
		if($list) {
			foreach($list as $k=> $v){
				if ($v['hidden']==0) { 
					//$list[$k]['content'] = epost($v['content'],$face_dir='../Public/Img/guest/post/'); 
				} else{
					$list[$k]['content']= "此留言为悄悄话！";
				}
			} 
			$this->assign("list",$list);
		}
        $this->assign('page',$page);

		$this->display();
	}
}
?>