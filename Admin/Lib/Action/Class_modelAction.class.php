<?php 
class Class_modelAction extends GlobalAction
{
	public function add_form()//增加字段对应的表单
	{
		$addForm=D("Model_field");
        if($data = $addForm->create()) {
        	$data['model_id']=$_POST['model_id'];
			$data['fieldname']= strtolower($_POST['fieldname']); //字段名转换为小写
			$data['addtable']=$_POST['addtable'];
			$data['fieldtype']=$_POST['fieldtype'];
			$data['maxlen']=$_POST['maxlen'];
			$data['isnull']=$_POST['isnull'];
			$data['iserror']=$_POST['iserror'];
			$data['pattern']=$_POST['pattern'];
			$data['content'] = $_POST['content'];	
        	if($addForm->add($data)){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	public function save_form()//修改字段对应的表单
	{
		$addForm=D("Model_field");
        if($data = $addForm->create()) {
		$fid = $_POST['fid'];
		//$data['model_id']=$_POST['model_id'];
		$data['fieldname'] = strtolower($_POST['fieldname']); //字段名转换为小写
		$data['addtable']=$_POST['addtable'];
		$data['fieldtype']=$_POST['fieldtype'];
		$data['maxlen']=$_POST['maxlen'];
		$data['isnull']=$_POST['isnull'];
		$data['iserror']=$_POST['iserror'];
		$data['pattern']=$_POST['pattern'];
		$data['content'] = $_POST['content'];	
        	if($addForm->where('id='.$fid)->save($data)){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	public function del_form($id)//删除字段对应的表单
	{
		$Form = D("Model_field");
		$result = $Form->where('id='.$id)->delete();
		if(false!==$result){
				return 1;  //删除成功
			}else{
				return 0;
			}
	}
	public function del_field()//删除字段
	{
		$del_id = $_GET['id'];
        $Form = D("Model_field");
		$delfield = $Form->find($del_id);
		$addtable =  trim($delfield['addtable']);  //去前后空格
		$del_field=  trim($delfield['fieldname']);
		$condition['addtable'] = $addtable;
		$condition['fieldname']= $del_field;
        $listfield = $Form->where($condition)->field('id')->findAll();
        if(count($listfield)<=1)   {   //当表单表里只有一个时删除
       	 	$trueTable = "b_".$addtable;  //附加表名
			//--------------------  
			$form = new model();
			$rs = $form->Execute(" ALTER TABLE `$trueTable` DROP `$del_field` ");
			if(false==$rs){
					$this->error('附加表中字段删除失败！');
				}
        }
		$result = $this->del_form($del_id); // 调用删除字段对应的表单方法
        if($result==0){
         	$this->error('字段对应的表单删除失败！');	
        }else {     
			$this->success('字段删除成功！');  //删除成功
        }
	}	
	
	public function index() //内容模型管理首页
	{
		if(isset($_POST['title']))
		{ 
			$name=trim($_POST['title']);
			$where['typename'] = $name; 
		}
		else { $where=''; }
		$Form=D("Class_model");
        //计算所有的行数
        $count = $Form->where($where)->count();
        //每页显示的行数
        $listRows = '20';
        $fields = '*';//需要查询哪些字段
        import("ORG.Util.Page"); //导入分页类 
        //通过类的构造函数来改变page的参数。$count为总数，$listrows为每一页的显示条目。
        $p = new Page($count,$listRows);
        $list = $Form->where($where)->field($fields)->order('id desc')->limit($p->firstRow.','.$p->listRows)->findAll(); 
        $page = $p->show();
        //模板输出
        $this->assign('list',$list);
        $this->assign('page',$page); 
		$this->display('index_model');
		Cookie::set ( '_currentUrl_', __SELF__ ); //用于返回
		return;
	}
	
	public function add()
	{
		$this->display('add_model');
	}

	public function insert()
	{
		$Form    = D("Class_model");
		$name    = trim($_POST['typename']);
		$addtable= trim($_POST['addtable']);
		if(empty($name)){
			$this->error("请输入内容模型名称");
		}
		if($addtable==''){
			$this->error("附加表名不能为空");
		}	
		$this->assign('jumpUrl',__APP__.'/Class_model');
        	if($vo = $Form->create()) {  
$Form->startTrans();  //开启事务
		    if($modelid=$Form->add($vo)){
	     		$addfrom = D('Model_field');
		     		$newfields[0]['fieldname']= 'aid';
		     		$newfields[0]['fieldtype']= 'int(11)';
		     		$newfields[0]['model_id']= $modelid;
		     		$newfields[0]['addtable']= $addtable;   		
		     		$newfields[0]['issystem']= 1;
		     		$newfields[1]['fieldname']= 'info_id';
		     		$newfields[1]['fieldtype']= 'int(11)';
		     		$newfields[1]['model_id']= $modelid;
		     		$newfields[1]['addtable']= $addtable;
		     		$newfields[1]['issystem']= 1;	
		     		$newfields[2]['fieldname']= 'content';
		     		$newfields[2]['fieldtype']= 'text';
		     		$newfields[2]['model_id']= $modelid;
		     		$newfields[2]['addtable']= $addtable;
		     		$newfields[2]['issystem']= 1;	
				foreach ($newfields as $newF){
					     if(!$addfrom->add($newF))	{
							 $Form->where('id='.$modelid)->delete(); 
						     //当附加表添加出错时，删除对应主表中记录
							$this->error('附加表固化字段写入表单表时出错,请联系管理员!');
$Form->rollback(); //事务回滚
						 }
				}

  //检测数据库是否存在附加表，不存在则新建一个
  $trueTable="b_".$addtable;  //真实表名
  $add = new model();
  $add_rs = $add->Execute("CREATE TABLE IF NOT EXISTS `{$trueTable}` (`aid` int(11) NOT NULL AUTO_INCREMENT,\r\n `info_id` int(11) NOT NULL default '0',\r\n `content` text NOT NULL,\r\n PRIMARY KEY  (`aid`))ENGINE=MyISAM  DEFAULT CHARSET=utf8;");
 if(!$add_rs){ 
		$Form->rollback(); //事务回滚
 }
$Form->commit();//事务提交
				$this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );				
				$this->success('内容模型添加成功！');
			}else{
				$this->error('内容模型添加失败！');
			}
		}else{
			$this->error($Form->getError());
		}
	}

//打开编辑模型页面并自动生成附加表
	public function edit() 
	{
		$id = $_GET['id'];
		$Form=D("Class_model");
		$list=$Form->find($id);
		$this->assign('listone',$list);
	//查询附加表	
	        $addtable= trim($list['addtable']); 
	        $trueTable="b_".$addtable;		
			$add = D('Model_field');  //查表单表中附加表相同的字段
		    $addwhere['model_id'] = $id; //查询条件
			$listadd  = $add->where($addwhere)->field("*")->order('sortrank desc')->findAll();
			$this->assign('listadd',$listadd);
	//查附加表结束
		$this->display('edit_model');
	}
	
	public function update()
	{
		$Form=D("Class_model");
	    $id =$_POST["id"];
		if(empty($_POST['typename'])){
			$this->error("内容模型名称不能为空");
		}
        if($vo = $Form->create()) {
       	    //$vo['id']=$_POST['id'];
			if($Form->where('id='.$id)->save($vo)){	
		   //删除原MODEL和数据表，并生成新的
		   $oldtable = trim($vo['oldtable']);
		   $addtable = trim($vo['addtable']);
           //$this->make_model($oldtable,'del');
           //$this->make_model($addtable,'add');
/*        $delTable = new model();   //删除表
		$delTable->Execute(" Drop TABLE `$trueTable` ");*/
			$this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
            $this->success(L('_UPDATE_SUCCESS_'));		
			}else{
				$this->error('数据写入错误！');
			}
		}else{
			$this->error($Form->getError());
		}		
	}
	//delete
	public function Del()
	{
		$id=$_GET['id'];
		if(empty($id)){
			$this->error('删除项不存在');
		}
		$Area  = D("Class_model");
		$result= $Area->where('id='.$id)->delete();
		$this->assign('jumpUrl',__APP__.'/Class_model');		
		if(false!==$result)
		{
			$Form = D("Model_field");
			$condition['model_id']=$id;
			$Form->where($condition)->delete(); //删除所有模型ID相同的表单
			$this->success('模型数据删除成功!');
		}
		else
		{
			$this->error('模型删除出错!');
		}
	}
	//全选删除
	public function Delall()
	{
		$Cmodel=D("Class_model");
	     foreach  ($_POST['key'] as $did ) //循环删除
	      {
			    $result=$Cmodel->where('id='.$did)->delete();
			    $Form = D("Model_field");
				$condition['model_id']=$did;
				$Form->where($condition)->delete(); //删除所有模型ID相同的表单
		  }		  
	   $this->ajaxReturn('','删除成功',1);
	}
	//修改状态
	public function upstate()
	{
		if(empty($_GET['dopost'])) $dopost="";
		else { $dopost= $_GET['dopost']; }
		$isdefault = (empty($isdefault) ? '0' : $isdefault);
		$ID = (empty($_GET['ID']) ? '' : intval($_GET['ID']));
		$Form=D("Class_model");
		if($dopost=="show")
		{
			//$Form->Execute("update b_modelclass set ishide=1 where id='$ID'");
			$Form->setField('ishide',1,'id='.$ID,false);
			$this->success('操作成功!');
			exit();
		}
		else if($dopost=="hide")
		{
			$Form->setField('ishide',0,'id='.$ID,false); //设置单个字段的值
			$this->success('操作成功!');
			exit();
		}
    }
	//打开增加字段页面
	public function model_field_add()
	{
		$this->assign('addtab_id',$_GET['id']);
		$this->assign('addtab_name',$_GET['name']);   //附加表名称
		$this->display('add_field');
	}
	//打开编辑字段页面
	public function edit_field()
	{
		$id = $_GET['id'];
		$Form=D("Model_field");
		$list=$Form->find($id);
		$this->assign('listone',$list);
		$this->display('edit_field');
	}
	//保存增加字段
	public function insert_field()
	{
		$ID = $_POST['model_id'];
		$addtable =  trim($_POST['addtable']);  //去前后空格
        $trueTable = "b_".$addtable;  //增加表名
		if(empty($_POST['fieldname'])){
			$this->error('新增字段名不能为空！');
		}
	//增加字段配置信息
	$fieldname = $_POST['fieldname'];
	$dtype   = $_POST['fieldtype'];
	$dfvalue = trim($_POST['dfvalue']);
	$isnull = ($_POST['isnull']==1 ? "true" : "false");
	$mxlen = $_POST['maxlen'];
	
	//检测被修改的字段类型
	$fieldinfos = GetFieldMake($dtype,$fieldname,$dfvalue,$mxlen);
	$ntabsql = $fieldinfos[0];
  $buideType = $fieldinfos[1];

  		$xy = D('Model_field');   //统计表单表中表名相同字段名相同的个数
		$where['addtable']  = $addtable; //
		$where['fieldname'] = $fieldname; //
		$list = $xy->where($where)->count();
		//if($list<1){
			$form = new model();
			@$rs = $form->Execute(" ALTER TABLE `$trueTable` ADD $ntabsql ");
			//die($rs);
			//if(!$rs) $this->error('增加字段失败!'); 
		//}
	  //增加到表单属性表
		  $result = $this->add_form(); 
		  if($result == 0){
			$this->error('增加字段对应的表单属性失败!');
		  }
	  //--------------------  
		$this->success('增加字段操作成功!');
	
	$this->assign('waitSecond',5); 	
	$this->assign('jumpUrl',__URL__.'/edit/id/'.$ID);
	
	}
	//保存修改字段
	public function update_field()
	{
		$MID = $_POST['mid'];
		$addtable =  trim($_POST['addtable']);  //去前后空格
        $trueTable = "b_".$addtable;  //增加表名
  //--------------------  
  $form = new model();   
$form->startTrans();  //开启事务
  //检测数据库是否存在附加表，不存在则新建一个
  $tabsql = "CREATE TABLE IF NOT EXISTS  `{$trueTable}`( `aid` int(11) NOT NULL AUTO_INCREMENT,\r\n `info_id` int(11) NOT NULL default '0',\r\n `content` text NOT NULL,\r\n";
  $tabsql .= " PRIMARY KEY  (`aid`), KEY `".$trueTable."_index` (`info_id`)\r\n) ENGINE=InnoDB DEFAULT CHARSET=utf8; ";
  $form->Execute($tabsql);
    //以下为修改字段

	//修改字段配置信息
	$fieldname = strtolower($_POST['fieldname']); //字段名转换为小写
	$oldfield = trim(strtolower($_POST['oldfield']));
	$dtype   = $_POST['fieldtype'];
	$dfvalue = trim($_POST['dfvalue']);
	$isnull = ($_POST['isnull']==1 ? "true" : "false");
	$mxlen = $_POST['maxlen'];
	
	//检测被修改的字段类型
	$fieldinfos = GetFieldMake($dtype,$fieldname,$dfvalue,$mxlen);
	$ntabsql   = $fieldinfos[0];
    $buideType = $fieldinfos[1];
    $rs = $form->Execute(" ALTER TABLE `$trueTable` CHANGE `$oldfield` ".$ntabsql );
	
	if($rs==false){
     $form->rollback(); //事务回滚
	 //$this->error('修改字段失败!');
    }
    //保存修改到表单属性表
      $result = $this->save_form(); 
      if($result == 0){
	    $form->rollback(); //事务回滚
      	//$this->error('修改字段对应的表单属性失败!');
      }
     //----------
$form->commit();//事务提交
	
	//检测旧数据类型，并替换为新配置	
	$this->assign('waitSecond',3);
	$this->assign('jumpUrl',__URL__.'/edit/id/'.$MID);
	$this->success('修改字段操作成功!');
	}
	
	//弹出复制模型窗口
	public function copymodel()
	{
		$id=$_GET['id'];
		$Form = D("Class_model");
		$list = $Form->find($id);
		$list['typename'] = $list['typename'].'1';
		//$list['addtable'] = $list['addtable'].'1';
		$this->assign('listone',$list);
		
		$add_form = D('Model_field');   //查表单表中模型ID相同的字段
		$addwhere['model_id'] = $id; //
		$listmodel  = $add_form->where($addwhere)->field('*')->order('sortrank desc')->findAll();
		if($listmodel){
			$this->assign('listadd',$listmodel);
		}
		//dump($listmodel);
	
	if(isset($_GET['act']) and $_GET['act']=='savecopy'){   
		$id=$_GET['id'];
		$Form = D("Class_model");
		$list = $Form->find($id);
		$list['typename'] = $list['typename'].'1';
		$list['id'] = ''; //置空主键
		//$list['addtable'] = $list['addtable'].'1';
		
		$add_form = D('Class_model');   //查表单表中模型ID相同的字段
		$addwhere['model_id'] = $id; //
		$listmodel  = $add_form->where($addwhere)->field("*")->order('sortrank desc')->findAll();

		$addtable= $list['addtable'];
	//保存新的模型记录
	$this->assign('waitSecond',3);
		if($modelid = $Form->add($list)){
			foreach ($listmodel as $v){
				$v['model_id'] = $modelid;
				$v['id'] = '';
				//$v['addtable'] = $addtable;
				$add_form->add($v);
				}			 
		  		$this->success('模型复制成功！');
		    }else {
		    	$this->error('写入新模型记录时出错！');
		    }
		}
		$this->display('copy_model');
	}
	
}
?>