<?php
/**
 +------------------------------------------------------------------------------
 * AdvPage  高级分页类
 +------------------------------------------------------------------------------
 * @author 万超
 +------------------------------------------------------------------------------
 */

import( "ORG.Util.Page"); //导入分页类

class AdvPage extends Page{
	//public $url;//ajax加载入的dom对像的id
	//$id:     ajax获取分页数据后放入该id的容器中;
	//$url:    当前页面的路径
	//$length: 显示数字的长度 1 2 3 4 5
	//protected $config  =	array('header'=>'now','prev'=>'<<','next'=>'>>','first'=>'<','last'=>'>','theme'=>'%upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');

//---输出形式重写---
	public function show_ajax($id,$url,$length){

		//如果存在自定义显示长度
		if($length){
		 $this->rollPage=$length;
		}

		 if(0 == $this->totalRows) return '';
				$p = C('VAR_PAGE');
				$nowCoolPage      = ceil($this->nowPage/$this->rollPage);
				//$url  =  $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?").$this->parameter;
				$url=$url.'?'.$this->parameter;
				$parse = parse_url($url);
				if(isset($parse['query'])) {
					parse_str($parse['query'],$params);
					unset($params[$p]);
					$url   =  $parse['path'].'?'.http_build_query($params);
				}
				//上下翻页字符串
				$upRow   = $this->nowPage-1;
				$downRow = $this->nowPage+1;
				if ($upRow>0){
					$upPage="<a href='#'  class='uppage' onclick=\"AdvPage('".$id."','".$url."&".$p."=$upRow)'); return false;\">&lt;&lt;</a>";
				}else{
					$upPage="&nbsp;<span class='dis'>&lt;&lt;</span>";
				}

				if ($downRow <= $this->totalPages){
					$downPage="<a href='#' class='downpage'  onclick=\"AdvPage('".$id."','".$url."&".$p."=$downRow'); return false;\">&gt;&gt;</a>";
				}else{
					$downPage="&nbsp;<span  class='dis'>&gt;&gt;</span>";
				}
				// << < > >>
				if($nowCoolPage == 1){
					$theFirst = "&nbsp;<span class='dis'>&lt;</span>";
					$prePage = "&nbsp;<span class='dis'>&lt;&lt;</span>";
				}else{
					$preRow =  $this->nowPage-$this->rollPage;
					$prePage = "<a href='#' class='uppage' onclick=\"AdvPage('".$id."','".$url."&".$p."=$preRow'); return false;\">&lt;&lt;</a>";
					$theFirst = "<a href='#' class='uppage'  onclick=\"AdvPage('".$id."','".$url."&".$p."=1'); return false;\" >&lt;</a>";
				}
				if($nowCoolPage == $this->coolPages){
					$nextPage = "&nbsp;<span class='dis'>&gt;&gt;</span>";
					$theEnd="&nbsp;<span class='dis'>&gt;</span>";
				}else{
					$nextRow = $this->nowPage+$this->rollPage;
					$theEndRow = $this->totalPages;
					$nextPage = "<a href='#' class='downpage' onclick=\"AdvPage('".$id."','".$url."&".$p."=$nextRow'); return false;\" >&gt;&gt;</a>";
					$theEnd = "<a href='#' class='downpage' onclick=\"AdvPage('".$id."','".$url."&".$p."=$theEndRow'); return false;\" >&gt;</a>";
				}
				// 1 2 3 4 5
				$linkPage = "";
				for($i=1;$i<=$this->rollPage;$i++){
					$page=($nowCoolPage-1)*$this->rollPage+$i;
					if($page!=$this->nowPage){
						if($page<=$this->totalPages){
							$linkPage .= "&nbsp;<a href='#' onclick=\"AdvPage('".$id."','".$url."&".$p."=$page'); return false;\">&nbsp;".$page."&nbsp;</a>";
						}else{
							break;
						}
					}else{
						if($this->totalPages != 1){
							$linkPage .= "&nbsp;<span class='current'>".$page."</span>";
						}
					}
				}
				/*$pageStr	 =	 str_replace(
					array('%header%','%nowPage%','%totalRow%','%totalPage%','%upPage%','%downPage%','%first%','%prePage%','%linkPage%','%nextPage%','%end%'),
					array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$upPage,$downPage,$theFirst,$prePage,$linkPage,$nextPage,$theEnd),$this->config['theme']);*/
				$pageStr=$theFirst.$upPage.$linkPage.$downPage.$theEnd; 
				if($linkPage==''){
					return;
				}
				return $pageStr;
	}


//---用于只显示 上一页和下一页的简洁分页导航---
	public function show_simple($id,$url){

		 if(0 == $this->totalRows) return '';
				$p = C('VAR_PAGE');
				$nowCoolPage      = ceil($this->nowPage/$this->rollPage);
				//$url  =  $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?").$this->parameter;
				$url=$url.'?'.$this->parameter;
				$parse = parse_url($url);
				if(isset($parse['query'])) {
					parse_str($parse['query'],$params);
					unset($params[$p]);
					$url   =  $parse['path'].'?'.http_build_query($params);
				}
				//上下翻页字符串
				$upRow   = $this->nowPage-1;
				$downRow = $this->nowPage+1;

				if ($upRow>0){
					$upPage="<span class='lou_name_page_up'> <a href='#' onclick=\"AdvPage('".$id."','".$url."&".$p."=$upRow'); return false;\"></a></span>";
				}else{
					$upPage="<span class='lou_name_page_updis'></span>";
				}

				if ($downRow <= $this->totalPages){
					$downPage="<span class='lou_name_page_down'><a href='#' onclick=\"AdvPage('".$id."','".$url."&".$p."=$downRow'); return false;\"></a></span>";
				}else{
					$downPage="<span class='lou_name_page_downdis'></span>";
				}

				$pageStr= $upPage.$downPage; 
				return $pageStr;
	}
	
	
//---复杂分页    ->样式: 首页 上一页  共154页/20条 第 1 2 3 4 5 6 7 8 9  页  下一页 尾页
	public function show_complex($id,$url,$length){
		
		//如果存在自定义显示长度
		if($length){
		 $this->rollPage=$length;
		}

		 if(0 == $this->totalRows) return '';
				$p = C('VAR_PAGE');
				$nowCoolPage      = ceil($this->nowPage/$this->rollPage);

				//$url  =  $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?").$this->parameter;
				$url=$url.'?'.$this->parameter;
				$parse = parse_url($url);
				if(isset($parse['query'])) {
					parse_str($parse['query'],$params);
					unset($params[$p]);
					$url   =  $parse['path'].'?'.http_build_query($params);
				}
				//上下翻页字符串
				$upRow   = $this->nowPage-1;
				$downRow = $this->nowPage+1;
				if ($upRow>0){
					$upPage="&nbsp;<span class='blue_a'><a href='#'  class='uppage' onclick=\"AdvPage('".$id."','".$url."&".$p."=$upRow)'); return false;\">上一页</a></span>";
				}else{
					$upPage="&nbsp;<span class='dis'>上一页</span>";
				}

				if ($downRow <= $this->totalPages){
					$downPage="&nbsp;<span class='blue_a'><a href='#' class='downpage'  onclick=\"AdvPage('".$id."','".$url."&".$p."=$downRow'); return false;\">下一页</a></span>";
				}else{
					$downPage="&nbsp;<span  class='dis'>下一页</span>";
				}
				// << < > >>
				if($nowCoolPage == 1){
					$theFirst = "&nbsp;<span class='dis'>首页</span>";
					$prePage = "&nbsp;<span class='dis'>上一页</span>";
				}else{
					$preRow =  $this->nowPage-$this->rollPage;
					$prePage = "&nbsp;<a href='#' class='uppage' onclick=\"AdvPage('".$id."','".$url."&".$p."=$preRow'); return false;\">上一页</a>";
					$theFirst = "<span class='blue_a'><a href='#' class='uppage'  onclick=\"AdvPage('".$id."','".$url."&".$p."=1'); return false;\" >首页</a></span>";
				}
				if($nowCoolPage == $this->coolPages){
					$nextPage = "&nbsp;<span class='dis'>下一页</span>";
					$theEnd="&nbsp;<span class='dis'>尾页</span>";
				}else{
					$nextRow = $this->nowPage+$this->rollPage;
					$theEndRow = $this->totalPages;
					$nextPage = "<a href='#' class='downpage' onclick=\"AdvPage('".$id."','".$url."&".$p."=$nextRow'); return false;\" >下一页</a>";
					$theEnd = "<span class='blue_a'><a href='#' class='downpage' onclick=\"AdvPage('".$id."','".$url."&".$p."=$theEndRow'); return false;\" >尾页</a></span>";
				}
				// 1 2 3 4 5
				$linkPage = "";
				for($i=1;$i<=$this->rollPage;$i++){
					$page=($nowCoolPage-1)*$this->rollPage+$i;
					if($page!=$this->nowPage){
						if($page<=$this->totalPages){
							$linkPage .= "&nbsp;<span class='gray_a'><a href='#' onclick=\"AdvPage('".$id."','".$url."&".$p."=$page'); return false;\">&nbsp;".$page."&nbsp;</a></span>";
						}else{
							break;
						}
					}else{
						if($this->totalPages != 1){
							$linkPage .= "&nbsp;<span class='current'>".$page."</span>";
						}
					}
				}
				/*$pageStr	 =	 str_replace(
					array('%header%','%nowPage%','%totalRow%','%totalPage%','%upPage%','%downPage%','%first%','%prePage%','%linkPage%','%nextPage%','%end%'),
					array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$upPage,$downPage,$theFirst,$prePage,$linkPage,$nextPage,$theEnd),$this->config['theme']);*/
				if($linkPage==''){
					$linkPage="<span class='current'>1</span>";
					
				};
				$pageStr=$theFirst.$upPage.' <sapn class="blue_12px_f">共'.($this->totalPages).'页/'.($this->listRows).'条</span>  <span class="gray_12px_f">第 '.$linkPage.' 页</span> '.$downPage.$theEnd; 
	/*			if($linkPage==''){
					return;
				}*/
				return $pageStr;		
		
		
	}

//---复杂分页    ->样式: 首页 上一页    下一页 尾页
	public function show_base($id,$url,$length){
		
		//如果存在自定义显示长度
		if($length){
		 $this->rollPage=$length;
		}

		 if(0 == $this->totalRows) return '';
				$p = C('VAR_PAGE');
				$nowCoolPage      = ceil($this->nowPage/$this->rollPage);
				//$url  =  $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?").$this->parameter;
				$url=$url.'?'.$this->parameter;
				$parse = parse_url($url);
				if(isset($parse['query'])) {
					parse_str($parse['query'],$params);
					unset($params[$p]);
					$url   =  $parse['path'].'?'.http_build_query($params);
				}
				//上下翻页字符串
				$upRow   = $this->nowPage-1;
				$downRow = $this->nowPage+1;
				if ($upRow>0){
					$upPage="<span class='black_a'><a href='#'  onclick=\"AdvPage('".$id."','".$url."&".$p."=$upRow)'); return false;\">上一页</a></span>&nbsp;|";
				}else{
					$upPage="&nbsp;<span class='gray_12px_f'>上一页</span>&nbsp; <span class='gray_12px_f'>|</span>";
				}

				if ($downRow <= $this->totalPages){
					$downPage="&nbsp;<span class='black_a'><a href='#'  onclick=\"AdvPage('".$id."','".$url."&".$p."=$downRow'); return false;\">下一页</a></span>";
				}else{
					$downPage="&nbsp;<span  class='gray_12px_f'>下一页</span>";
				}
				// << < > >>
				if($nowCoolPage == 1){
					$theFirst = "&nbsp;<span class='jian_left_dis'></span>";
					$prePage = "&nbsp;<span class='gray_12px_f'>上一页</span>";
				}else{
					$preRow =  $this->nowPage-$this->rollPage;
					$prePage = "<a href='#' class='uppage' onclick=\"AdvPage('".$id."','".$url."&".$p."=$preRow'); return false;\">上一页</a>";
					$theFirst = "<span class='jian_left'><a href='#' class='uppage'  onclick=\"AdvPage('".$id."','".$url."&".$p."=1'); return false;\" ></a></span>";
				}
				if($nowCoolPage == $this->coolPages){
					$nextPage = "&nbsp;<span class='dis'>下一页</span>";
					$theEnd="&nbsp;<span class='jian_right_dis'></span>";
				}else{
					$nextRow = $this->nowPage+$this->rollPage;
					$theEndRow = $this->totalPages;
					$nextPage = "<a href='#' class='downpage' onclick=\"AdvPage('".$id."','".$url."&".$p."=$nextRow'); return false;\" >下一页</a>";
					$theEnd = "<span class='jian_right'><a href='#' class='downpage' onclick=\"AdvPage('".$id."','".$url."&".$p."=$theEndRow'); return false;\" ></a></span>";
				}
				$html="每页显示 <span class='gray_a'><a  href='#' onclick=\"AdvPage('".$id."','".$url."&".$p."=$nextRow&r=5'); return false;\" >5</a><sapn> | <span class='gray_a'><a  href='#'  onclick=\"AdvPage('".$id."','".$url."&".$p."=$nextRow&r=10'); return false;\" >10</a><sapn> | <span class='gray_a'><a  href='#'  onclick=\"AdvPage('".$id."','".$url."&".$p."=$nextRow&r=15'); return false;\" >15</a><sapn> | <span class='gray_a'><a  href='#'  onclick=\"AdvPage('".$id."','".$url."&".$p."=$nextRow&r=20'); return false;\" >20</a><sapn> 条";

				$pageStr=$theFirst.'<span class="font">'.$upPage.$downPage.'</span>'.$theEnd.$html; 
	/*			if($linkPage==''){
					return;
				}*/
				return $pageStr;		
		
		
	}
	
	
	
	
	

}


?>