var project_id;
var map;
var x,y;
var map_title;
var map_proAddr;
var map_proTel;
var map_status;
var map_localCity;
var map_periph;
var map_icon;   //图标

function showInfo(){
	$('#myMap-info').show();
}
function hideInfo(){
	$('#myMap-info').hide();
}

(function() {
    
    var KMapSearch=window.KMapSearch= function(map_, opts_) {
        this.opts = {
            keyWord:opts_.keyWord || "楼盘",
            latlng: opts_.latlng || new GLatLng(0, 0),
            autoClear:opts_.autoClear || true
        };
        this.map = map_;
        this.gLocalSearch = new google.search.LocalSearch();
        
        this.gLocalSearch.ADDRESS_LOOKUP_DISABLED;
        this.gLocalSearch.setCenterPoint(this.opts.latlng);
        this.gLocalSearch.setResultSetSize(GSearch.LARGE_RESULTSET);
        this.gLocalSearch.setSearchCompleteCallback(this, function() {
			hideInfo();//处理请稍候字样...
            if (this.gLocalSearch.results) {
            	for(var i = 0; i < this.gLocalSearch.results.length; i++) {
				   this.getResult(this.gLocalSearch.results[i]);
                }
                if(index_temp==1){
                	alert("距该楼盘2公里内没有该类型的相关资料！");
                }
                map.setCenter(new GLatLng(x,y));
            }
        });
    }
    var index_temp = 1;
    KMapSearch.prototype.getResult = function(result) {
    	  var titleS = result.title;
		  var address = result.streetAddress;
		  var tels="暂无";
		  var tel = "";
		  if(result.phoneNumbers!=null && result.phoneNumbers!=undefined){
			  for(var j = 0;j<result.phoneNumbers.length;j++){
			  	  var phones = result.phoneNumbers[j];
			  	  if(phones.type=="FAX"){
			  	  	 tel="传真:"+phones.type+",";
			  	  }else{
			  	  	 tel=tel+phones.number+",";
			  	  }
			  }
		  }
		  if(tel==""){
		  	tel = tels;
		  }else{
		  	tel = tel.substring(0,tel.length-1);
		  }
		  var content = "地址："+address+"<br>电话："+tel;
          this.createMarker(result.lat, result.lng, "<b><font color='red'>"+titleS+"</font></b><br><br>"+ content);
    }
	
    KMapSearch.prototype.createMarker = function(lat, lng, content )
    {
	 //---xiongyan 修改--- start  
	   var myicon = new GIcon();
	   myicon.iconSize = new GSize(16, 16);
	   myicon.shadowSize = new GSize(37, 34);
	   myicon.iconAnchor = new GPoint(9, 34);
	   myicon.image=  map_icon ;
       myicon.infoWindowAnchor = new GPoint(13, 2);
       myicon.infoShadowAnchor = new GPoint(18, 18);
	   markerOptions = { icon: myicon };
    //---xiongyan 修改---  end      
	
      	var distance = new GLatLng(lat,lng).distanceFrom(new GLatLng(x,y)  );//计算离中心点的距离
        if(distance<=4000.3){
	        var markerES = new GMarker(new GLatLng(lat,lng) , markerOptions );
	        GEvent.addListener(markerES, "click", function() {
	            markerES.openInfoWindowHtml(content);
	        });
	        markers.push(markerES);
	        map.addOverlay(markerES);
	        index_temp++;
        }
    }
    KMapSearch.prototype.clearAll = function() {
        for (var i = 0; i < markers.length; i++) {
			//alert(i);
            this.map.removeOverlay(markers[i]);
        }
        markers.length = 0;
    }
    KMapSearch.prototype.execute = function(latLng) {
        if (latLng) {
            this.gLocalSearch.setCenterPoint(latLng);
        }
        this.gLocalSearch.execute(this.opts.keyWord);
    }
})();


//正常情况初始化
function initialize() {
	  if (GBrowserIsCompatible()) {
			map = new GMap2(document.getElementById("myMap"));
			map.addControl(new GSmallMapControl()); //导航控制  GLargeMapControl
			map.addControl(new GScaleControl()); //
			map.addControl(new GMapTypeControl()); //位图，卫星
			map.removeMapType(G_HYBRID_MAP); //移除混合类型
			map.removeMapType(G_SATELLITE_MAP); //移除 地图\卫星
			//alert(x)
			map.setCenter(new GLatLng(x, y),14);
			initPoint();
	  }
}
//按城市初始化
function initByCityXY(){
	//alert($("#myMap").html());
	initSearchControl();
	this.localSearch.execute(map_localCity);
}

//初始化搜索器
function initSearchControl(){ 
	this.localSearch = new google.search.LocalSearch(); 
	this.localSearch.setCenterPoint(new GLatLng(x, y));  
	//$("#myMap").html(x);
	this.localSearch.setResultSetSize(google.search.Search.LARGE_RESULTSET);//返回8条数据 
	this.localSearch.setSearchCompleteCallback(this, searchComplete, [this.localSearch]);
}

//搜索回调函数
searchComplete = function(data){
   if (data.results!=null && data.results.length > 0){
		for(var i=0; i<data.results.length; i++){
			var result = data.results[i];
	   }
	   x = result.lat;
	   y= result.lng;
	   initialize();
	   return;	
	}
}
//初始化该楼盘的点
var markerdefine;
function initPoint(){
   var myicon = new GIcon();
   myicon.iconSize = new GSize(20, 34);
   myicon.shadowSize = new GSize(37, 34);
   myicon.iconAnchor = new GPoint(9, 34);
   myicon.infoWindowAnchor = new GPoint(9, 2);
   myicon.infoShadowAnchor = new GPoint(18, 25);
	
   var iconss = new GIcon(myicon);
   
   iconss.image= PUBLIC+ "/Images/Map/lan_"+map_status+".png";
   markerOptions = {icon:iconss};
   var showCont = "<b><font><span style='font-size:12px;color:red' >"+map_title+"</span></font></b><br><br>地址："+map_proAddr+"<br>电话："+map_proTel+'<br>';
	markerdefine = new GMarker(new GLatLng(x,y),markerOptions);
	GEvent.addListener(markerdefine, "click", function() {
		gInfoWindowOptions = {zoomLevel:13};
		markerdefine.openInfoWindowHtml(showCont,gInfoWindowOptions);
	  });
	//markerdefine.openInfoWindowHtml(showCont,{zoomLevel:13});
	map.addOverlay(markerdefine);    //暂时隐藏
	hideInfo();
}

/// 创建信息窗口显示对应给定索引的字母的标记
function createMarkerP(point, index,content) {
	  var marker = new GMarker(point);
	  
	  GEvent.addListener(marker, "click", function() {
		gInfoWindowOptions = {zoomLevel:13};
		marker.openInfoWindowHtml(content,gInfoWindowOptions);
	  });
	  return marker;
}

//开始搜索
var mapSearch;
var markers= new Array(); 
function startSearch(val){  
	showInfo(); //加载中...

	if(! val)
		val = $('#smap-local-type').val();
	if(val == ''){
		alert('请输入查找内容！');
		$('#smap-local-type').focus();
		return;
	}
	//指定图标---start -- xiongyan
	if (val == '小区')
	{
		map_icon = "m_map_zb6.gif";
	}else if( val == '学校' ){
		map_icon = "m_map_zb1.gif";
	}else if(val == '医院'){
		map_icon = "m_map_zb5.gif";
	}else if(val == '餐饮'){
		map_icon = "m_map_zb7.gif";
	}else if(val == '银行'){
		map_icon = "m_map_zb2.gif";
	}else if(val == '商场'){
		map_icon = "m_map_zb4.gif";
	}else if(val == '车站'){
		map_icon = "m_map_zb8.gif";
	}
	map_icon = PUBLIC +"/Images/Map/" + map_icon; 
	//指定图标---end

	mapSearch = new KMapSearch(map, {latlng:new GLatLng(x,y), keyWord:val+"" });
	mapSearch.clearAll();
	mapSearch.execute();
}


//周边楼盘搜索专用
function periphPro(){ 

	if(mapSearch){
		mapSearch.clearAll();
	}
	if(map_periph.length<1){
		alert("没有周边楼盘！");
		return;
	}
	showInfo();
		
	var countCC = 1;

	for(var i = 0;i<map_periph.length;i++){
		var ph = map_periph[i];
		if(ph){
			if(ph.map_x!="" && ph.map_y!=""){
				if(!isNaN(ph.map_x) && !isNaN(ph.map_y)){
					var marker2 = createMarkerP(new GLatLng(ph.map_x,ph.map_y), countCC,'<b><span style="font-size:12px;color:red" >'+ph.property_nm+'</span></b><br><br>地址：'+ph.sale_address+'<br>电话：'+ph.sale_phone+'<br>');
					map.addOverlay(marker2);
					markers.push(marker2);
					countCC++;
				}
			}
		}
	}
	hideInfo();//
	if(countCC==0){
		alert("没有周边楼盘！");
		return;
	}
	map.setCenter(new GLatLng(x,y));
	
}


///************小图操作***********************************************/

//正常情况初始化
function s_initialize() {
	  if (GBrowserIsCompatible()) {
			s_map = new GMap2(document.getElementById("mySmallMap"));
			//s_map.addControl(new GSmallZoomControl()); //导航控制
			//s_map.addControl(new GScaleControl()); //
			s_map.setCenter(new GLatLng(x, y),15);
			s_initPoint();
	  }
}
//按城市初始化
function s_initByCityXY(){
	s_initSearchControl();
	this.localSearch.execute(map_localCity);
}

//初始化搜索器
function s_initSearchControl(){ 
	this.localSearch = new google.search.LocalSearch(); 
	this.localSearch.setCenterPoint(new GLatLng(x, y));  
	this.localSearch.setResultSetSize(google.search.Search.LARGE_RESULTSET);//返回8条数据 
	this.localSearch.setSearchCompleteCallback(this, s_searchComplete, [this.localSearch]);
}

//搜索回调函数
function s_searchComplete(data){
   if (data.results!=null && data.results.length > 0){
		for(var i=0; i<data.results.length; i++){
			var result = data.results[i];
	   }
	   x = result.lat;
	   y = result.lng;
	   s_initialize();
	   return;	
	}
}
//初始化该楼盘的点
var s_markerdefine;
function s_initPoint(){
   var myicon = new GIcon();
   myicon.iconSize = new GSize(20, 34);
   myicon.shadowSize = new GSize(37, 34);
   myicon.iconAnchor = new GPoint(9, 34);
   myicon.infoWindowAnchor = new GPoint(9, 2);
   myicon.infoShadowAnchor = new GPoint(18, 25);
	
   var iconss = new GIcon(myicon);
   
   iconss.image= PUBLIC +"/Images/Map/lan_"+map_status+".png";
   markerOptions = {icon:iconss};
   var showCont = "<b><font color='red'>"+map_title+"</font></b><br><br>地址："+map_proAddr+"<br>电话："+map_proTel;
	s_markerdefine = new GMarker(new GLatLng(x,y),markerOptions);
	/*
	GEvent.addListener(s_markerdefine, "click", function() {
		gInfoWindowOptions = {zoomLevel:13};
		s_markerdefine.openInfoWindowHtml(showCont,gInfoWindowOptions);
	  });
	s_markerdefine.openInfoWindowHtml(showCont,{zoomLevel:13});
	*/
	s_map.addOverlay(s_markerdefine);
	hideInfo();
}
