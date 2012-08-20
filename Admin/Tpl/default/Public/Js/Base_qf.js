//全选复选框
//Param: e 触发框，id 复选框ID名 
  function selAll(e, id) {
 		var a = document.getElementsByName(id);
		var l = a.length; while(l--) a[l].checked=e.checked;
	}

//全选复选下拉列表
//Param: id 
	function selAll_multiple(id){
			 var data = document.getElementById(id);
			 var i    = data.options.length;
			 var opt;
			 if (data.value!='') {
				    opt = false;
			  }else{
					opt = true;
			  }
			 while(i--){
					data.options[i].selected= opt;
			  }
	}
    
//检查是否非空
    function notEmpty(obj, msg)
    {
        str = obj.value;
        str1 = "";
        for (i = 0; i < str.length; i++)
        {
                if (str.charAt(i) != " "){
                    str1 = str.substr(i, str.length);
                    break;
                }
        }
    
        if (str1 == "")
        {
            alert(msg);
            obj.value = "";
            obj.focus();
            return false;
        }else{
            return true;
        }
    }
    
    //检查是否为数字
    function isNumber(obj, msg)
    {
        if(isNaN(obj.value))
        {
                if (undefined == msg){
                    msg = "请输入数字！";
                }
                alert(msg);
                obj.select();
                return false;
        }else{
                return true;    
        }
    }
    
    //检查密码是否相同
    function isSamePwd(objPwd1, objPwd2, msg)
    {
        pwd1 = objPwd1.value;
        pwd2 = objPwd2.value;
    
        if (pwd1 != pwd2){
			if (null == msg){
				alert("密码不相同！");
			}else{
				alert(msg);
			}
			 
			objPwd2.value = "";
			objPwd2.focus();
			return false;
        }else{
        	return true;
        }
    }
    
    //检查邮件地址
    function isEmail(obj, msg)
    {
        ch = obj.value;
        if((ch.indexOf("@") < 1) || (ch.indexOf(".") < 1) || (ch.indexOf(".") == ch.length - 1))
        {
			if (null == msg)
			{
				alert("Email不正确！");
			}else{
				alert(msg);
			}
			obj.select();
			return false;
        }else{
			return true;
        }
    }
///-----------------------------------------------------------------
	function del(id){
		if (window.confirm('确实要删吗？请谨慎操作！'))
		{
		window.location.href= URL+"/Del/id/"+id;
		}
	}
   
//全选删除
function delAll(url){
	var U;
	if(url){
		U=url;
	}else{
		U='delAll';	
	}
		
	if (window.confirm('确实要删除选择项吗？')){
      var str="";
         $("input:[name*='id']:checked").each(function(){
             str+=$(this).val()+",";
         });
		str = str.substring(0 , str.length-1); //去掉最后的','
		
		 $.get( URL + '/'+U+'/str/'+ str , '', function(msg){
			//alert(msg);
			var msgObj=eval("("+msg+")");   //转换为json对象 
			//alert(msgObj.info);
			complete(msgObj.info, msgObj.status); //调用成功处理方法
		});
	}
}
   //全选通过
	function PassAll(){
		if (window.confirm('确实要审核通过选中项吗？'))
		{
		  var str="";
			 $("input:[name*='id']:checked").each(function(){
				 str+=$(this).val()+",";
			 });
			str = str.substring(0 , str.length-1); //去掉最后的','
			 $.get( URL + '/PassAll/str/'+ str , '', function(msg){
				var msgObj=eval("("+msg+")");   //转换为json对象 
				complete(msgObj.info,msgObj.status); // 调用成功处理方法
			});
		}
	}
//成功处理方法
	function complete(data,status){
		var pic = PUBLIC+'/Images/ajaxloading.gif';
		$("#result").empty();
		$("#result").show();
		$("#result").html('<img src="'+pic+'"  border="0" alt="loading..." align="absmiddle"> '+data);
		//$("#result").toggle();
		 window.setTimeout(function (){  $("#result").slideUp(1000);	 },3000);
		if (status==1){
		 window.setTimeout(function (){window.location.href= SELF },1000);
		}
	}

	function add(){   //打开添加页面
		window.location.href=URL+"/add";
	}
	function edit(id){  //打开编辑页面
		window.location.href=URL+"/edit/id/"+id;
	}
	function upsort(){  //修改排序
		if (window.confirm('确实要重新排序吗？'))
		{			
			$.post(URL+'/upsort/',$("#list_form").serialize(),function(msg){
				alert(msg);
			});
		  //ThinkAjax.sendForm('formall',URL+'/upsort/',complete,'result');
	    }
	}
	
//+---------------------------------------------------
//|	打开模态窗口，返回新窗口的操作值
//+---------------------------------------------------
	function PopModalWindow(url,width,height)
	{
		var result=window.showModalDialog(url,"win","dialogWidth:"+width+"px;dialogHeight:"+height+"px;center:yes;status:no;scroll:yes;dialogHide:no;resizable:no;help:no;edge:sunken;");
		return result;
	}

//+---------------------------------------------------
//|	打开普通窗口
//+---------------------------------------------------
	function PopWindow(url,width,height)
	{
		window.open(url,"win","width="+width+",height="+height,"top=80,left=100,toolbar=no,scrollbars=no,menubar=no,alwaysRaised=yes");
	}

//+---------------------------------------------------
// 获取返回值方法 value:值,idName:显示框ID
//+---------------------------------------------------
function get_return_val(value,idName){
		$("#"+idName).val(value);
}

//删除图片
function del_pic(id){
	if (window.confirm('确实要删除选择项吗？')){	
		$.get(URL+'/del_pic/id/'+id,function(msg){
			//alert(msg);
			if(msg){
				$('#pic'+id).remove();	
			}else{
				alert('删除失败');	
			}
			
		});
	}

}

//设图片为首张图
function set_pic(id,news_id,th){
	html='<table border="0" cellspacing="0" cellpadding="0" style="float:left" id="pic'+id+'">'+th.html()+'</table>';
	$.get(URL+'/set_pic/id/'+id+'/news_id/'+news_id,function(msg){
		//alert(msg);
		if(msg){
			th.remove();
			$('#set_pic').prepend(html);
		}else{
			alert('设置失败');	
		}	
	});
	
	
}
	