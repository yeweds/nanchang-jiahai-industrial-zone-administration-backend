$(function(){
		   $('#lpdp_content').focus(function(){
			     onFocus(this);
			});
		   $('#lpdp_content').blur(function(){
			     onBlur(this);
			});
		   
		   $('#lp_xqyx_tag').focus(function(){
			     onFocus(this);
			});
		   $('#lp_xqyx_tag').blur(function(){
			     onBlur(this);
			});
		  $("#compare").click(function(){
		      var isChecked=$("#compare").attr("checked");
		      //alert(isChecked);
		       if(isChecked){
			       $("#CompareFrame").css("display","inline");
		      }else{
			      $("#CompareFrame").css("display","none");
	         }
		   });
		 $("#Switch").click(function(){
				$("#com_content").toggle();
				var icon_class=$("#Switch").hasClass('icon_big_ph');
				if(!icon_class){
				 var div1 = $('#CompareFrame');
                 div1.css("top", $(window).height() - div1.height()-10);
				 $("#Switch").addClass('icon_big_ph');
				}else{
				 var div1 = $('#CompareFrame');
                 div1.css("top",  div1.height()+10);
				 $("#Switch").removeClass('icon_big_ph');	
			   }
		});
		 $("#compareclose").click(function(){
				$("#CompareFrame").css("display","none");
		});
		 $("#close_lp").click(function(){
				$("#choice_lp").hide();
		});
		 

});


function onFocus(id){
	$(id).addClass('border_focus');
}

function onBlur(id){
	$(id).removeClass('border_focus');
}

function lou_switch_attach( classid ) //缩略图切换
	{
		
		var data;
		if(classid != 0){
			 data = {"info_id": infoid , "class_id": classid};
		}else{
			 data = {"info_id": infoid};
		}
		$("#lou_switch_attach").load('/Loupan/switchAttach', data ,function(){
			//先将将绑定事件移除
			$('#lou_switch_attach').unbind();
			$("#lou_switch_attach").click(function(){
				//loadPhoto('/Attach/viewall/info_id/182/rid/10/num/',1,'绿地新都会图')
				//alert('182');
				//alert('/Attach/viewall/info_id/182/cid/13/rid/10/num');
				switch(classid){
				case 13:loadPhoto('/Attach/viewall/info_id/182/cid/13/rid/10/num/',1,'绿地新都会户型图',2);
					break;
				case 14:loadPhoto('/Attach/viewall/info_id/182/cid/14/rid/10/num/',1,'绿地新都会实景图',0);
					break;
				case 15:loadPhoto('/Attach/viewall/info_id/182/cid/15/rid/10/num/',1,'绿地新都会效果图',1);
					break;
				case 17:loadPhoto('/Attach/viewall/info_id/182/cid/17/rid/10/num/',1,'绿地新都会位置图',3);
					break;
				}
				//alert(classid);
			});
 
		}); //附件类
	}