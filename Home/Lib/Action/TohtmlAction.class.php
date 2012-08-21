<?php
/**
 +------------------------------------------------------------------------------
 * Tohtml 生成公共静态页类
 +------------------------------------------------------------------------------
 * @author 熊彦
 +------------------------------------------------------------------------------
 */
class TohtmlAction extends GlobalAction{
	//栏目id配置
	public $COL=array(
		'jryw'    =>'jryw',  //今日要闻
		'rdtj'      =>'rdtj',  //热点推荐			
		'tdcr'    =>'tdcr',  //土地出让
		'tdcj'     =>'tdcj',  //土地成交
		'htpk'    =>'htpk',//话题pk
		'rdzt'     =>20,     //热点专题
		'dyj'      =>32,	  //大嘴谈房
		'xbcp'    =>45,	  //小编踩盘
		'lssj'      =>42,     //楼市数据(每日成交排行榜)
		'lp_cjsj' =>31,	  //楼盘每日成交数据		
		'qgls'    =>36 ,    //全国楼市
		//'hdtx'    =>32,     //活动提醒
		//'tfxc'     =>32,     //腾房现场
		'tfdj'    =>34,      //腾房独家
		'tfhd'    =>44,     //腾房活动
		'tfkx'    =>21,      //腾房快讯
		'cjxw'    =>22,     //财经新闻
		'fczt'     =>20,     //房产专题
		//'gfgl'    =>32,      //购房攻略
		'gdjh'    =>27,     //观点江湖
		'dcbg'   =>37,     //地产八卦
		'hygc'    =>24,     //行业观察
		//'fqyw'    =>31,     //房企要闻
		'sydc'     =>30,     //商业地产
		//'bssc'     =>21,     //别墅市场
		'zcfg'     =>26,     //政策法规
		'tdsc'     =>25,     //土地市场
		'zytz'     =>23,     //置业投资
		'yxwx'     =>28,     //房企要闻(营销万象)
		'dcjr'      =>39,     //地产金融
		//'lpgh'    =>32,      //楼盘规划
		//'jzjt'    =>32,      //家装讲堂
		//'xwtj'    =>32,      //新闻特荐
	);
	private $htmlpath = 'Home/Tpl/default/Html/'; //静态文件存放目录
		
	public function _initialize()
	{
		parent:: _initialize();
		C('HTML_FILE_SUFFIX', '.html');					// 默认静态文件后缀

		$this->assign('COL',$this->COL);
		//$this->assign('userInfo', $this->userInfo);  //用户信息
	}

//---生成公共头部
	public function make_header()
	{
		$this->buildHtml($htmlfile='header', $htmlpath=$this->htmlpath, $templateFile='Public:news_header'); //专题
		return "header";
	}


}
?>