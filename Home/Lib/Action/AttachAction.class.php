<?php
/**
 +------------------------------------------------------------------------------
 * Attach  附件图片查看类
 +------------------------------------------------------------------------------
 * @author 黄妃
 +------------------------------------------------------------------------------
 */
class AttachAction extends GlobalAction{

	//---全部展示--- xiongyan 修改 2012-7-24
	public function index(){
        
		$info_id = intval($_GET['info_id']);  
		A('General')->view_inc($info_id);  //信息页共用

		//---相册相关---
		//获取商家名和区域ID
        $map_lp['id'] = $info_id; 
        $array = M('Shop')->where($map_lp)->find();
		//var_dump($array);
		$lpid = $array['lpid'];
		$lpname = $array['lpname'];
        $range_id = $array['range_id'];

		$map['info_id'] = $info_id; 
        $t_attach = M('Attach');
		$map_cl['pid'] = 15; //调附件分类
		$map_cl['id'] = array('not in','12,33'); //调附件分类
		$list_class = M('Class')->field('id,name')->where($map_cl)->findall();
		//dump($list_class);

		$count_all = 0 ; //总数
		$map_att['info_id'] = $info_id;
		foreach($list_class as $k=>$v){
			$curr_id = $v['id'];
			$map_att['class_id'] = $curr_id;
			$list[$curr_id] = $t_attach->where($map_att)->order('id desc')->limit('0,7')->findAll();  //列表
			$count[$curr_id] = $t_attach->where($map_att)->count();  //统计
			$count_all += $count[$curr_id];  //总数
		}

		$this->assign('info_id',$info_id);
		$this->assign('lpid',$lpid);
		$this->assign('range_id',$range_id);
		$this->assign('lpname',$lpname);

		$this->assign('list',$list);			 //附件列表
		$this->assign('list_class',$list_class); //分类列表
        $this->assign('count',$count);           //附件分类统计
		$this->assign('count_all',$count_all);
		$this->display();
    }

//---根据class_id全部展示---
    public function viewall(){
        $lpid = intval($_GET['info_id']);
		$cid = intval($_GET['cid']);
		$rid = intval($_GET['rid']);
		if(empty($lpid) ){
			$this->error('参数错误！');
			exit;
	    }
		if(!empty($cid)){
			$map['info_id'] = $lpid ;
			$map['class_id'] = $cid ;
			$list = M('Attach')->where($map)->order('id desc')->findAll();
			//获取楼盘名
			$map_lp['lpid'] = $lpid; 
			$map_lp['range_id'] = $rid;
			$lpname = D('New_loupan')->where($map_lp)->getField('lpname');
			//获取图片类型
			$map_tpye['id'] = $rid;
			$type_name = D('Class')->where($map_tpye)->getField('name');

			$num=intval($_GET['num']);
			$index = 0 ;
			$total = count($list) ;
			foreach($list as $vo){
				if(($index+1)==$num){
					$currPic = U(__APP__.'/Public/watermark?show='.base64_encode($vo['savepath'].$vo['savename']) ); //生成地址
					$str =  '{"img":"'.$currPic.'","total":"'.$total.'"}';
					break;
				}
				$index++;
			}
			echo $str ;
		}else{
			unset($map);
			$map['info_id'] = $lpid ;
			$list = D('Attach')->where($map)->order('id desc')->findAll();
			$num=intval($_GET['num']);
			$index = 0 ;
			$total = count($list) ;
			foreach($list as $vo){
				if(($index+1)==$num){
					$currPic = U(__APP__.'/Public/watermark?show='.base64_encode($vo['savepath'].$vo['savename']) ); //生成地址
					$str =  '{"img":"'.$currPic.'","total":"'.$total.'"}';
					break;
				}
				$index++;
			}
			echo $str ;
		}
        /*$this->assign('lpname',$lpname);
		$this->assign('type_name',$type_name);
		$this->assign('lpid',$lpid);
		$this->assign('cid',$cid);
		$this->assign('rid',$rid);
		$this->assign('list',$list);
		$this->display();*/
	}

//---根据id只展示一条,查看上条下条---
    public function viewone(){
        $lpid = intval($_GET['lpid']);
		$id = intval($_GET['id']);
		$rid = intval($_GET['rid']);
		$cid = intval($_GET['cid']);
		if(empty($lpid) || empty($id)){
			$this->error('参数错误！');
			exit;
	    }
		
        //获取楼盘名
        $map_lp['lpid'] = $lpid; 
		$map_lp['range_id'] = $rid;
        $lpname = D('New_loupan')->where($map_lp)->getField('lpname');
        
		$Table = M('Attach');
		//获取图片类型
		$map_tpye['id'] = $rid;
        $type_name = D('Class')->where($map_tpye)->getField('name');
        //读取记录
		$map['info_id'] = $lpid ;
		$map['id'] = $id ;
		$map['class_id'] = $cid ;
		$list = $Table->where($map)->find();
		if(count($list) <= 0)
			exit;

		if(($id+1)>=0){//是否有下一条
			$map_next['info_id'] = $lpid ;
            $map_next['id'] = $id+1 ;
			$map_next['class_id'] = $cid ;
			$list_next = $Table->where($map_next)->find();
			if(count($list_next)>0){
                $nextid = $id+1 ;
			}else{
                $nextid = '' ;
			}
		}else{
            $nextid = '' ;
		}
		
        
        if(($id-1)>=0){//是否有上一条
			$map_prev['info_id'] = $lpid ;
            $map_prev['id'] = $id-1 ;
			$map_prev['class_id'] = $cid ;
			$list_prev = $Table->where($map_prev)->find();
			if(count($list_prev)>0){
                $previd = $id-1 ;
			}else{
                $previd = '' ;
			}
		}else{
            $previd = '' ;
		}

        $this->assign('lpname',$lpname);
		$this->assign('type_name',$type_name);
		$this->assign('range_id',$rid);
        $this->assign('previd',$previd);
		$this->assign('nextid',$nextid);
		$this->assign('lpid',$lpid);
		$this->assign('cid',$cid);
		$this->assign('rid',$rid);
        $this->assign('list',$list);
		$this->display();
	}


//通过info_id 获取附件路径  -- xy 添加
	public function getAttachByInfoId($info_id, $class_id = 0, $is_defautl=0){
			$map_['id'] = $info_id;
			if( $is_defautl==1 ){
				//默认
				$vo_lp = M('Shop')->field('id,default_attach_id')->where($map_)->find();
				$map['id'] = $vo_lp['default_attach_id'];
			}else{
				if($class_id != 0){
					$map['class_id']= $class_id;
				}
			}
			$rs = M('Attach')->where($map)->find();
			$pic = ( $rs ? trim($rs['savepath'],'.').'thumb_'.$rs['savename'] : "/Public/Upload/no.png" ) ;
			//$pic = ( $rs ? trim($rs['savepath'],'.').$rs['savename'] : "/Public/Upload/no.png" ) ;
			//return  $rs['savepath'].$pic;
			return  $pic;
	}

	//通过info_id 获取附件总数  -- xy 添加
	public function getAttachCount($info_id){
			$map['info_id'] = $info_id;
			$rs = M('Attach')->where($map)->count();
			return  $rs;
	}

//通过info_id 获取附件路径  -- xy 添加
	public function getLogoByInfoId($info_id){
			$map_['id'] = $info_id;
			$vo = M('Shop')->field('id,logo_url')->where($map_)->find();
            $att_rs = $vo['logo_url']; 
			if($att_rs != 'no.png'){
				$att_rs = $att_rs;  #不用缩略图 $att_rs = "thumb_".$att_rs;
		    }
			return  C('cfg_img_path')."Shop/".$att_rs ;
	} 
 
}
?>