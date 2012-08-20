<?php
/*
    Interaction 用户交互类
	Author:黄妃
*/
class InteractionAction extends  GlobalAction{
//---用户搜索记录入库 --- 熊彦
	public function in_search_record(){
		return false;
	}

//---浏览楼盘入库 --- 熊彦
    public function in_browser_record($info_id)
	{
		//$info_id = intval($_REQUEST['info_id']);
		if(empty($info_id)){
			return;
		}
        $userid = $this->uid;
		if($userid == 0){
			//未登录则记录至cookie
			$browserArr = array();
			if(cookie::is_set('TF_Browser_record')){
				$browserArr = cookie::get('TF_Browser_record');
				$key = array_search(intval($info_id), $browserArr);
				if($key){
					unset($browserArr[$key]);
				}
				if(count($browserArr)>=10){
					array_pop($browserArr);
				}
				array_push($browserArr, $info_id);
			}else{
				$browserArr[0] = $info_id; 
			}
			cookie::set('TF_Browser_record', $browserArr );
			return;
		}
		//检测是否已经浏览过
		$Browser = M('Browser_record');
		$map['user_id'] = $userid ;
		$count = $Browser->where($map)->count();
		if( $count >= 10 ){
			$Browser->where($map)->order('id asc')->limit('1')->delete(); //超过10条则删除  xiongyan 修改
		}
		$data['user_id'] = $userid;
		$data['info_id'] = $info_id;
		$data['update_time'] = time();

		$where['user_id'] = $userid ;
		$where['info_id'] = $info_id ;
		if($Browser->where($where)->find()){
			$rs = $Browser->where($where)->save($data);     //修改
		}else{
			$rs = $Browser->add($data);					  //添加
		}
		if($rs !== false){
			return true;
		}else{
            return false;
	    }
	}

//---收藏楼盘入库 ---黄妃
    public function in_favorite()
	{
		$info_id = intval($_GET['info_id']);
		if(empty($info_id)){
			ajaxMsg('参数出错！', $status='error', $title='', $data='');
			exit;
		}
        $userid = $this->uid;
		if(empty($userid)){
			ajaxMsg('你还没有登录，登录后才能收藏！', 'error','',$data='notLogin'); //未登录
			exit;
		}
		//检测是否已经收藏
		$Table = D('Favorite');
		$map['user_id'] = $userid ;
		$map['info_id'] = $info_id ;
		$id = $Table->where($map)->find();
		if(!empty($id)){
			ajaxMsg('你已收藏过该楼盘，不需要重复操作！', 'error');
			exit;
		}
		$data['user_id'] = $userid ;
		$data['info_id'] = $info_id ;
		if($Table->add($data)){
			ajaxMsg('收藏成功！', 'success');
		}else{
			ajaxMsg('收藏失败！', 'error');
	    }
	}

//---添加小区印象标签 --- 万超
	public function in_xqyx_tag()
	{
		/*if(false === $this->userInfo){
			ajaxMsg('请先登录，谢谢！', $status='error');
			exit;
		}*/
		
		//1.获取楼盘info id---印象内容:tag
		$info_id = intval($_POST['info_id']); //echo $id;
		$tag     = htmlspecialchars(trim($_POST['lp_xqyx_tag'])); //echo $tag; 
		
		//2.检查数据,tag中文长度不能大于5;
		//echo $tag;
		if( strlen($tag) > 15){
			ajaxMsg('输入过长！', 'error');
			return;
		}

		//3.查询标签内是否存在此标签,如果是则count+1只要更新数据就可以了, 否则新插入一条数据
		 $table=M("Tag");
		 $map['tag_name'] = $tag;
		 $map['info_id']  = $info_id;
		 $find_tag = $table->field('tag_name')->where($map)->find();
		
		 if($find_tag){ //存在相同的小区印象标签
			$submit=$table->setInc( 'tag_count',"info_id=".$info_id." and tag_name='".$tag."'" , 1 ); //给相同的标签增加次数
		 }else{//不存在,则新插入一个标签
			//3.1插入数据
			$data['tag_name'] = $tag;
			$data['info_id']  = $info_id;
			$data['tag_count']= 1;
			$submit=$table->add($data);
		 }

		//4.提示失败或成功插入
		if($submit){
			ajaxMsg('提交成功,谢谢您的参与！', 'success');
			return;
		}else{
			ajaxMsg('提交失败,请过一段时间再试！', 'error');
		}
		
	}

//---纠错入库 --- 熊彦
	 public function in_correction(){
		$info_id = intval($_GET['info_id']);
		if(empty($info_id)){
			ajaxMsg('未选中任何信息！', $status='error');
			exit;
		}
        $userid = $this->uid;
		if(empty($userid)){
			ajaxMsg('请先登录！', $status='error');
			exit;
		}
		//检测是否已经提交
		$Table = D('Correction');
		$map['user_id'] = $userid ;
		$map['info_id'] = $info_id ;
		$id = $Table->where($map)->find();
		if(!empty($id)){
			ajaxMsg('你已提交过该信息的纠错意见！', 'error');
			exit;
		}
		$data['user_id'] = $userid ;
		$data['info_id'] = $info_id ;
		if($Table->add($data)){
			ajaxMsg('纠错意见提交成功！', 'success');
		}else{
			ajaxMsg('纠错意见提交失败！', 'error');
	    }
	 }

//---保存搜索条件 --- 熊彦
	 public function save_search_map(){
		$currMap = Session::get('clientMap');
		if($this->userInfo !== false){
			$data['key']     = base64_encode(serialize($currMap));  //序列化后的文本比较长
			$data['user_id'] = $this->uid;
			$Table = M('Searching');
			$where['user_id'] = $data['user_id'];
			if($Table->where($where)->find()){
				$rs = $Table->where($where)->save($data);     //修改
			}else{
				$rs = $Table->add($data);					  //添加
			}
		}else{
			cookie::set('clientMap', $currMap, 3600*24*30 );  //长时间保存
			$rs = true;
		}
		if($rs !== false){
			ajaxMsg('搜索条件保存成功！', 'success');
		}else{
			ajaxMsg('搜索条件保存失败！', 'error');
	    }
	 }

//---获取搜索条件 --- 熊彦
	 public function get_search_map(){
		if($this->userInfo !== false){
			$where['user_id'] = $this->uid;
			$rs = M('Searching')->where($where)->find();
			$currMap = unserialize(base64_decode($rs['key']));
		}else{
			$currMap = cookie::get('clientMap');
		}
		return $currMap;
	 }	

//---获取浏览记录 --- 熊彦
    public function get_browser_record()
	{
        $userid     = $this->uid;
		$browserArr = array();
		if($userid == 0){
			//未登录则读取cookie记录
			if(cookie::is_set('TF_Browser_record')){
				$browserArr = cookie::get('TF_Browser_record');
			}
		}else{
			$Model= new Model('Browser_record');
			$map['user_id'] = $userid;
			$list = $Model->field('info_id')->where($map)->order('update_time desc')->limit('0,10')->findAll();
			//dump($list);
			foreach($list as $v){
				$browserArr[] =  $v['info_id'];
			}
		}
		return $browserArr;
	}

}