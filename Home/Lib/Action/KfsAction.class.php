<?php
/**
 +------------------------------------------------------------------------------
 * Kfs  开发商类 
 +------------------------------------------------------------------------------
 * @author 熊彦
 +------------------------------------------------------------------------------
 */
class KfsAction extends Action{
//---开发商信息列表
	public function index()
	{
        $Table = M('Kfs');
		$map = null;
		//$map['is_hide']  = 0;
		for($i=1; $i<=26; $i++){
			$py = chr( 64 + $i ); //转字母
			$map['head_py'] = array('like', $py.'%');
			$list[$py] = $Table->field('id,name,logo_url')->where($map)->order("id desc")->limit(100)->findall(); 
		}

		//dump($list);
		if($list!==false){		
			$this->assign('list',$list);
		}

		$this->display();
	}

//---详细页
	public function view(){
		$id = intval($_GET['id']);
		$Table = M('Kfs');
		$map['id'] = $id;
		$vo = $Table->where($map)->find();	

//dump($vo);
		if($vo){
			$vo['remark'] = mb_substr( strip_tags($vo['remark']),0,260,'utf-8' ).'..';  //去掉html标签
			$this->assign('vo', $vo); //开发商信息
		}else{
			$this->error('找不到该开发商');
		}
		//项目新闻 -- start
		unset($map);
		$map['info_id'] = array("in", $vo['info_id_str']);
		$map_rs = M('News_mapping')->field('info_id,news_id')->where($map)->findAll();
		foreach($map_rs as $v){
			$news_id_arr[] = $v['news_id'];
		}
		$map_news['id'] = array('in',$news_id_arr);
		$map_news['ispublish'] = 1;
		$news_list = M('News')->field('id,class_id,title,add_time')->where($map_news)->order('id desc')->limit('0,8')->findall();  //新闻列表
		if($news_list){
			$this->assign("news_list", $news_list);
		}
		//dump($news_list);  
		//项目新闻 -- end 

		//项目列表 -- start
		unset($map);
		$map['info_id'] = array("in", $vo['info_id_str']);
		$lp_list = M('New_loupan')->field('info_id,lpname,lpaddr,lp_zhutui,default_attach_id')->where($map)
			->limit('0,5')->findAll();
		$Attach  = A('Attach'); //实例化附件类
		if($lp_list){
				foreach($lp_list as $k=>$v){
						$info_id = $v['info_id'];
						//--默认图片
						$att_rs = $Attach->getAttachByInfoId($info_id, $class_id = 0, $is_defautl=1);
						//if($att_rs != 'no.png'){
						//	$att_rs = "thumb_".$att_rs;  //不用缩略图
						//}
						$lp_list[$k]['default_pic'] = $att_rs;
				}
			$this->assign("lp_list", $lp_list);
		}
		//dump($lp_list);
		$this->display();
	}


//---根据条件获取开发商信息列表
	public function getKfsList($num, $title_len=20 ){
		$Table = M('Kfs');
		$map['is_gongye'] = 0;
        $list = $Table->field('id,bianhao,weizhi,jiaoyishijian,range_id')->where($map)->order('id desc')
			->limit('0,'.$num )->findAll();

		return $list;
	}


}

?>