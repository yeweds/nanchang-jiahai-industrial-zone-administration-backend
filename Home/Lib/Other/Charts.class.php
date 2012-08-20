<?php
/**
 +------------------------------------------------------------------------------
 * Charts  自定义报表类，主要用于价格分析等
 +------------------------------------------------------------------------------
 * @author 熊彦 <cnxiongyan@gmail.com>
 +------------------------------------------------------------------------------
 */
class Charts extends Think{
	
	public function encodeDataURL($strDataURL, $addNoCacheStr=false) {
		//Add the no-cache string if required
		if ($addNoCacheStr==true) {
			// We add ?FCCurrTime=xxyyzz
			// If the dataURL already contains a ?, we add &FCCurrTime=xxyyzz
			// We replace : with _, as FusionCharts cannot handle : in URLs
			if (strpos($strDataURL,"?")<>0)
				$strDataURL .= "&FCCurrTime=" . Date("H_i_s");
			else
				$strDataURL .= "?FCCurrTime=" . Date("H_i_s");
		}
		// URL Encode it
		return urlencode($strDataURL);
	}


	// datePart function converts MySQL database based on requested mask
	// Param: $mask - what part of the date to return "m' for month,"d" for day, and "y" for year
	// Param: $dateTimeStr - MySQL date/time format (yyyy-mm-dd HH:ii:ss)
	public function datePart($mask, $dateTimeStr) {
		@list($datePt, $timePt) = explode(" ", $dateTimeStr);
		$arDatePt = explode("-", $datePt);
		$dataStr = "";
		// Ensure we have 3 parameters for the date
		if (count($arDatePt) == 3) {
			list($year, $month, $day) = $arDatePt;
			// determine the request
			switch ($mask) {
			case "m": return $month;
			case "d": return $day;
			case "y": return $year;
			}
			// default to mm/dd/yyyy
			return (trim($month . "/" . $day . "/" . $year));
		}
		return $dataStr;
	}


	public function renderChart($chartSWF, $strURL, $strXML, $chartId, $chartWidth, $chartHeight, $debugMode=false, $registerWithJS=false, $setTransparent="") {

		if ($strXML=="")
			$tempData = "//Set the dataURL of the chart\n\t\tchart_$chartId.setDataURL(\"$strURL\")";
		else
			$tempData = "//Provide entire XML data using dataXML method\n\t\tchart_$chartId.setDataXML(\"$strXML\")";

		// Set up necessary variables for the RENDERCAHRT
		$chartIdDiv = $chartId . "Div";
		$ndebugMode = $this->boolToNum($debugMode);
		$nregisterWithJS = $this->boolToNum($registerWithJS);
		$nsetTransparent=($setTransparent?"true":"false");


		// create a string for outputting by the caller
$render_chart = <<<RENDERCHART

		<!-- START Script Block for Chart $chartId -->
		<div id="$chartIdDiv" align="center">
			Chart.
		</div>
		<script type="text/javascript">	
			//Instantiate the Chart	
			var chart_$chartId = new FusionCharts("$chartSWF", "$chartId", "$chartWidth", "$chartHeight", "$ndebugMode", "$nregisterWithJS");
		  chart_$chartId.setTransparent("$nsetTransparent");
		
			$tempData
			//Finally, render the chart.
			chart_$chartId.render("$chartIdDiv");
		</script>	
		<!-- END Script Block for Chart $chartId -->
RENDERCHART;

	  return $render_chart;
	}


//注意 $HTML_chart = <<<HTMLCHART 必须顶格写

	public function renderChartHTML($chartSWF, $strURL, $strXML, $chartId, $chartWidth, $chartHeight, $debugMode=false,$registerWithJS=false, $setTransparent="") {
		// Generate the FlashVars string based on whether dataURL has been provided
		// or dataXML.
		$strFlashVars = "&chartWidth=" . $chartWidth . "&chartHeight=" . $chartHeight . "&debugMode=" . $this->boolToNum($debugMode);
		if ($strXML=="")
			// DataURL Mode
			$strFlashVars .= "&dataURL=" . $strURL;
		else
			//DataXML Mode
			$strFlashVars .= "&dataXML=" . $strXML;
		
		$nregisterWithJS = $this->boolToNum($registerWithJS);
		if($setTransparent!=""){
		  $nsetTransparent=($setTransparent==false?"opaque":"transparent");
		}else{
		  $nsetTransparent="window";
		}
$HTML_chart = <<<HTMLCHART
		<!-- START Code Block for Chart $chartId -->
		<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="$chartWidth" height="$chartHeight" id="$chartId">
			<param name="allowScriptAccess" value="always" />
			<param name="movie" value="$chartSWF"/>		
			<param name="FlashVars" value="$strFlashVars&registerWithJS=$nregisterWithJS" />
			<param name="quality" value="high" />
			<param name="wmode" value="$nsetTransparent" />
			<embed src="$chartSWF" FlashVars="$strFlashVars&registerWithJS=$nregisterWithJS" quality="high" width="$chartWidth" height="$chartHeight" name="$chartId" allowScriptAccess="always" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" wmode="$nsetTransparent" />
		</object>
		<!-- END Code Block for Chart $chartId -->
HTMLCHART;

	  return $HTML_chart;
	}

	// 布尔型转数字型 (1/0)
	public function boolToNum($bVal) {
		return (($bVal==true) ? 1 : 0);
	}

/*
	public function getFCColor() 
	{
		$FC_ColorCounter=0;

		$arr_FCColors[0] = "1941A5" ;//Dark Blue
		$arr_FCColors[1] = "AFD8F8";
		$arr_FCColors[2] = "F6BD0F";
		$arr_FCColors[3] = "8BBA00";
		$arr_FCColors[4] = "A66EDD";
		$arr_FCColors[5] = "F984A1" ;
		$arr_FCColors[6] = "CCCC00" ;//Chrome Yellow+Green
		$arr_FCColors[7] = "999999" ;//Grey
		$arr_FCColors[8] = "0099CC" ;//Blue Shade
		$arr_FCColors[9] = "FF0000" ;//Bright Red 
		$arr_FCColors[10] = "006F00" ;//Dark Green
		$arr_FCColors[11] = "0099FF"; //Blue (Light)
		$arr_FCColors[12] = "FF66CC" ;//Dark Pink
		$arr_FCColors[13] = "669966" ;//Dirty green
		$arr_FCColors[14] = "7C7CB4" ;//Violet shade of blue
		$arr_FCColors[15] = "FF9933" ;//Orange
		$arr_FCColors[16] = "9900FF" ;//Violet
		$arr_FCColors[17] = "99FFCC" ;//Blue+Green Light
		$arr_FCColors[18] = "CCCCFF" ;//Light violet
		$arr_FCColors[19] = "669900" ;//Shade of green

		//accessing the global variables
		global $FC_ColorCounter;
		global $arr_FCColors;
		
		//Update index
		$FC_ColorCounter++;
		//Return color
		return($arr_FCColors[$FC_ColorCounter % count($arr_FCColors)]);
	}
*/
}
?>