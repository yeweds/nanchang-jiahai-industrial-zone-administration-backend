<?php
/**
 +------------------------------------------------------------------------------
 * Tools  购房相关工具类
 +------------------------------------------------------------------------------
 * @author 黄妃
 +------------------------------------------------------------------------------
 */
class ToolsAction extends GlobalAction{
	
	public function index(){
		$this->display();
    }

//---等额本息还款法
	public function debx()
	{
		$this->display('');
	}
//---购房能力评估
	public function gfnl()
	{
		$this->display('');
	}
//---等额本金还款法
	public function debj()
	{
		$this->display('');
	}
//---公积金贷款计算器
	public function gjjjsq()
	{
		$this->display('');
	}
//---税费计算器
	public function sfjsq()
	{
		$this->display('');
	}
//---提前还贷计算器
	public function tqhd()
	{
		$this->display('');
	}
//---房屋合同样本
	public function hetong()
	{
		$this->display('');
	}
}
?>