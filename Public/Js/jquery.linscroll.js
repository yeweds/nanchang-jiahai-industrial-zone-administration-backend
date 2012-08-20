/*
--用法--
$('#scrollContent').setScroll( //scrollContent为滚动层的ID   
{img:scroll_bk.gif',width:10},//背景图及其宽度   
{img:scroll_arrow_up.gif',height:3},//up image   
{img:scroll_arrow_down.gif',height:3},//down image   
{img:scroll_bar.gif',height:25}//bar image


*/

jQuery.fn.setScroll = function(_scroll,_scroll_up,_scroll_down,_scroll_bar,right){  

	if(right){//右边距--字符串类型
		var right=right;
	}else{
		right='';
	}
    this.each(function(){  
          
        var _bar_margin = 3;  
          
        //create scroll dom  
        var _scroll_control = jQuery('<div class="scroll_zone">').width(_scroll.width).css({'position':'absolute','float':'none',margin:0,padding:0}).css('background','url('+_scroll.img+')'); 
		 //$('.scroll_zone.').css({'right':right});
        var _scroll_control_up = jQuery('<img class="scroll_down">').attr('src',_scroll_up.img).width(_scroll.width).css({'z-index':'1000','position':'absolute', 'top':'0','right':right,'float':'none',margin:0,padding:0});  
        var _scroll_control_down = jQuery('<img class="scroll_down">').attr('src',_scroll_down.img).width(_scroll.width).css({'z-index':'1000','position':'absolute', 'right':right,'bottom':'0','float':'none',margin:0,padding:0});  
        var _scroll_control_bar =  jQuery('<img class="scroll_bar">').attr('src',_scroll_bar.img).width(_scroll.width).css({'z-index':'1500','position':'absolute','right':right,'float':'none',margin:0,padding:0,height:_scroll_bar.height+'px'}).css('top',_scroll_up.height+_bar_margin+'px');  
          
        _scroll_control.append(_scroll_control_up);  
        _scroll_control.append(_scroll_control_bar);  
        _scroll_control.append(_scroll_control_down);  
          
        var _oheight = jQuery(this).css('height').substring(0,jQuery(this).css('height').indexOf('px'));  
        var _owidth = jQuery(this).width();  
        var _ocontent = jQuery(this).html();  
          
        if(jQuery(this).attr('scrollHeight')<=_oheight) return;  
          
        var _content_zone = jQuery('<div>').html(_ocontent).css({ width:_owidth-10+'px',height:_oheight+'px',overflow:'hidden','float':'none',margin:0,padding:0});  
          
        jQuery(this).css({'overflow':'hidden'});  
        jQuery(this).empty().append(_content_zone).css({position:'relative'}).append(_scroll_control.css({left:_owidth-_scroll.width+'px',top:'0',height:_oheight+'px',margin:0,padding:0}));  
  
        //register drag event  
        jQuery(this).find('.scroll_bar')  
        .mousedown(  
            function(){  
                jQuery(document).mousemove(  
                    function(e){  
                      var _content = _content_zone.get(0);  
                      var lastProgress = _scroll_control_bar.attr('progress');  
                      _scroll_control_bar.attr('progress',e.pageY);  
                      var nowProgress = _scroll_control_bar.css('top');  
                      nowProgress = nowProgress.substring(0,nowProgress.indexOf('px'));  
                      nowProgress = Number(nowProgress) + Number(e.pageY-lastProgress);  
                      var preProgress = nowProgress/(_oheight-_scroll_up.height-_scroll_down.height-_scroll_bar.height-(2*_bar_margin));  
                      _content.scrollTop = ((_content.scrollHeight - _content.offsetHeight) * preProgress);  
                      if(nowProgress<(_scroll_up.height+_bar_margin) || nowProgress > (_oheight-(_scroll_down.height+_scroll_bar.height+_bar_margin))) return false;  
                      try{_scroll_control_bar.css('top',nowProgress+'px');}catch(e){}  
                      return false;  
                    }  
                );  
                return false;  
            }  
        )  
        .mouseout(  
            function(){  
                jQuery(document).mouseup(  
                    function(){  
                        jQuery(document).unbind('mousemove');  
                     }  
                )  
            }  
        )  
          
    });   
}