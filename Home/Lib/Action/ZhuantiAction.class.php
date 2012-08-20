<?php
// 专题
class ZhuantiAction extends Action
{
    /**
    +----------------------------------------------------------
    * 首页 指向合适的模板
    +----------------------------------------------------------
    */
    public function xym()
    {
		header('Content-type:text/html;charset=utf-8');
		echo '<body style="margin:0;padding:0;font-family:宋体">';
		$Table=M('reviews_mx');
		$map['info_id']=180;
		$list=$Table->field('id,content')->where($map)->order('pr DESC,add_time DESC')->limit('0,12')->select();
		$rs_str='';
		foreach($list as $k=>$v){
					$str =  strip_tags($v['content']);
					$str =  str_replace(' ','',$str);
					$str =  str_replace('	','',$str);
					$str =  str_replace('&nbsp;','',$str);
					$str =  str_replace("\r\n",'',$str);
					$list[$k]['content'] = $str;
					
				 $rs_str.='<a href="http://nc.tengfang.net/Reviews/view/id/'.$v['id'].'" target="_blank" title="'.$str.'"    style="width:49%;height:25px;line-height:25px;float:left;display:inline-block;font-size:12px;color:black;text-decoration:none">·'.mb_substr($str,0,35,"utf-8").'</a>';
				}
		
		echo $rs_str;
		echo '</body>';
    }

}
?>