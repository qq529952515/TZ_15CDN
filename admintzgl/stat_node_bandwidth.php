<?php
include_once("./head.php");

// 节点ID
$node_id 	= isset($_GET['id'])?$_GET['id']:'';
$date2      = isset($_GET['date2'])?$_GET['date2']:'0';
if($_SESSION['admin_grade']==3||$_SESSION['admin_grade']==4){
    FuncAdmin_LocationLogin();
}
?>
<script type="text/javascript" src="../highcharts-302/jquery/1.8.2/jquery.min.js"></script>
<script src="../highcharts-302/js/highcharts.js"></script>
<script src="../highcharts-302/js/modules/exporting.js"></script>
<script type="text/javascript">	

// 将 GMT 时间转换为本地时间 
// phpLocalTime 时间格式 "2010/12/09 00:00:00"
function  ConvDate(phpLocalTime)
{
	var d=new Date(phpLocalTime); //"2010/12/09 00:00:00");

	day = d.getHours();

	d = d.setHours(8+day);

	d = new Date(d);

	x = d.getTime(); 
	
	return x;
}

function getNodeBandwidthStatData(node_id,timeval)
{
	var xmlhttp;
	
    if (window.XMLHttpRequest)
	{
	  	// code for IE7+, Firefox, Chrome, Opera, Safari
	  	xmlhttp=new XMLHttpRequest();
	}
	else
	{
	  	// code for IE6, IE5
	  	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	var sTimeformat;
	
	if(timeval==1)
	{
		sTimeformat = "%H:%M";
	}
	else
	{
		sTimeformat = "%0m-%0d";
	}
	
	xmlhttp.onreadystatechange=function()
	{
	  	if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{	
			/*
		    var data = [
					{
						name: "United States",
						data: [[1990, 18.9], [1991, 18.7], [1992, 18.4], [1993, 19.3], [1994, 19.5], [1995, 19.3], [1996, 19.4], [1997, 20.2], [1998, 19.8], [1999, 19.9], [2000, 20.4], [2001, 20.1], [2002, 20.0], [2003, 19.8], [2004, 20.4]]
					},
					{
            			name: "Russia", 
			            data: [[1992, 13.4], [1993, 12.2], [1994, 10.6], [1995, 10.2], [1996, 10.1], [1997, 9.7], [1998, 9.5], [1999, 9.7], [2000, 9.9], [2001, 9.9], [2002, 9.9], [2003, 10.3], [2004, 10.5]]
        			}];
			*/	
			var sResponse= xmlhttp.responseText;
			//document.getElementById("textStatDataTable").innerHTML=sResponse;	
			__StatDataSets = eval('('+sResponse+')');
			
			var data = [];
			for(var key in __StatDataSets)
			{
				data.push(__StatDataSets[key]);				
			}

			
			//hdrchart.series = data;
			update_enginConn_chart(__StatDataSets);
			
			/*
			$(function(){			
				var opt ={		
					points:{show:false, clickable:true, hoverable:true},		
					lines:{show:true, lineWidth:1},	
				    grid: { hoverable: true, clickable: true,borderWidth: 1,borderColor: "#E3E6EB" },
					legend: {show:true,backgroundColor:"#FFFFE9",backgroundOpacity:0,container: $("#showChartLegend")},
					xaxis: {show:true,mode:"time", timeformat: sTimeformat}
				}; 
			
				$.plot($("#placeholder"),data,opt);			
			});
			*/
		}
	}

	var postUrl = "request_stat_data.php?mod=realtime&action=bandwidth"+"&node_id=" + node_id +"&timeval="+timeval;
	xmlhttp.open("GET",postUrl,true);
	xmlhttp.send(null);
	return false;
}

function OnSelectDomain()
{
	msgboxOBJ=document.getElementById("msgbox"); 
	msgboxOBJ.style.display="block";	
	document.getElementById("txtGrpName").value="";
}

function OnSelectBandwidthDate()
{
	var txtNodeID = document.getElementById("txtNodeID").value;
	var nBandwidthDateSelect = document.getElementById("BandwidthDateSelect").value;
	
	getNodeBandwidthStatData(txtNodeID,nBandwidthDateSelect);
}

function OnSelectNode()
{
	var txtSelectNode = document.getElementById("SelectNode").value;
	var nDateSelect2 = document.getElementById("BandwidthDateSelect2").value;
	window.location.href="stat_node_bandwidth.php?id="+txtSelectNode+"&date2="+nDateSelect2;
}
function OnSelectBandwidthDate2()
{
	OnSelectNode();
}
</script>

<div style="min-width:1060px;">
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F8F9FA">
  <tr>
    <td width="17" valign="top" background="../images/mail_leftbg.gif"><img src="../images/left-top-right.gif" width="17" height="29" /></td>
    <td valign="top" background="../images/content-bg.gif">
	   <table width="100%" height="31" border="0" cellpadding="0" cellspacing="0" class="left_topbg" id="table2" >
			<tr height="31" class="TabToolTitle">
				<td height="31" width="85"><a href="stat_node_bandwidth.php?id=<?php echo $node_id; ?>"><span class="title_bt">实时带宽</span></a></td>
				<td height="31" width="85"><a href="stat_node_bandwidth_max.php?id=<?php echo $node_id; ?>"><span class="title_bt_active">峰值带宽</span></a></td>
				<td height="31" width="85"><a href="stat_node_day_download.php?id=<?php echo $node_id; ?>"><span class="title_bt_active">日流量统计</span></a></td>
				<td height="31" width="85"><a href="stat_node_conn.php?id=<?php echo $node_id; ?>"><span class="title_bt_active">连接数统计</span></a></td>
				<td height="31" width="85"><a href="stat_node_hostorder.php?id=<?php echo $node_id; ?>"><span class="title_bt_active">最新排名</span></a></td>
				<td width="95%"></td>
			</tr>
        </table>
	</td>
    <td width="16" valign="top" background="../images/mail_rightbg.gif"><img src="../images/nav-right-bg.gif" width="16" height="29" /></td>
  </tr>
  <tr height="500">
	  <td valign="middle" background="../images/mail_leftbg.gif">&nbsp;</td> 
	  <td valign="top">
	   	<table width="800" border="0" class="bottom_btn">
			<tr height="30">
			<td bgcolor="#FFFFE6"><span class="input_tips_txt3"></span>
			<div class="div_search_title" style="padding-right:190px">
			
			<select id="SelectNode" style="width:280px" onChange="OnSelectNode()">
<?php  
	$node_id 	= isset($_GET['id'])?$_GET['id']:'';
	$show_this_name="";
  	$db_link = FikCDNDB_Connect();
	if($db_link)
	{
		$node_id 	= mysql_real_escape_string($node_id); 
		
		$sql = "SELECT * FROM fikcdn_node;"; 
		$result = mysql_query($sql,$db_link);
		if($result)
		{
			$row_count=mysql_num_rows($result);
			for($i=0;$i<$row_count;$i++)
			{
				$this_node_id	= mysql_result($result,$i,"id");	
				$node_name  	= mysql_result($result,$i,"name");	
				$node_ip 		= mysql_result($result,$i,"ip");
				$node_unicom_ip = mysql_result($result,$i,"unicom_ip");
				$group_id 		= mysql_result($result,$i,"groupid");
				
				if(strlen($node_id)<=0) $node_id = $this_node_id;
				
				$sFikIP = $node_ip;
				if(strlen($sFikIP)<=0) $sFikIP=$node_unicom_ip;
				$show_name = $sFikIP.' ('.$node_name.')';
	
				if($this_node_id==$node_id)
				{
					echo '<option value="'.$this_node_id.'" selected="selected" >'.$show_name.'</option>';
					
					$show_this_name = $sFikIP.' - '.$node_name;	
				}
				else
				{
					echo '<option value="'.$this_node_id.'" >'.$show_name.'</option>';
				}
			}
		}
		
		// 计算最大带宽
		$timenow=time();
		$timeval = $timenow-24*60*60;
		$sql = "SELECT max(bandwidth_down),max(bandwidth_up),avg(bandwidth_down),avg(bandwidth_up) FROM realtime_list WHERE node_id=$node_id AND time>=$timeval";
		$result2 = mysql_query($sql,$db_link);
		if($result2 && mysql_num_rows($result2)>0)
		{
			$max1_bandwidth_down = mysql_result($result2,0,"max(bandwidth_down)");
			$max1_bandwidth_up = mysql_result($result2,0,"max(bandwidth_up)");		
			$avg1_bandwidth_down = mysql_result($result2,0,"avg(bandwidth_down)");
			$avg1_bandwidth_up = mysql_result($result2,0,"avg(bandwidth_up)");		
			
			$max1_bandwidth_down = round($max1_bandwidth_down,2);
			$max1_bandwidth_up = round($max1_bandwidth_up,2);
			$avg1_bandwidth_down = round($avg1_bandwidth_down,2);
			$avg1_bandwidth_up = round($avg1_bandwidth_up,2);									
		}
		
		if(strlen($max1_bandwidth_down)<=0) $max1_bandwidth_down=0;
		if(strlen($max1_bandwidth_up)<=0) $max1_bandwidth_up=0;
		if(strlen($avg1_bandwidth_down)<=0) $avg1_bandwidth_down=0;
		if(strlen($avg1_bandwidth_up)<=0) $avg1_bandwidth_up=0;			
		
		// 计算最大带宽
		$timeval = $timenow-24*60*60*3;
		$sql = "SELECT max(bandwidth_down),max(bandwidth_up) FROM realtime_list WHERE node_id=$node_id AND time>=$timeval";
		$result2 = mysql_query($sql,$db_link);
		if($result2 && mysql_num_rows($result2)>0)
		{
			$max7_bandwidth_down = mysql_result($result2,0,"max(bandwidth_down)");
			$max7_bandwidth_up = mysql_result($result2,0,"max(bandwidth_up)");		
	
			$max7_bandwidth_down = round($max7_bandwidth_down,2);
			$max7_bandwidth_up = round($max7_bandwidth_up,2);								
		}	
		
		if(strlen($max7_bandwidth_down)<=0) $max7_bandwidth_down=0;
		if(strlen($max7_bandwidth_up)<=0) $max7_bandwidth_up=0;						
	}
?>
				</select>&nbsp;
				<select id="BandwidthDateSelect" style="width:120px" onChange="OnSelectBandwidthDate()">
				<?php
					$timenow =time();
					for($i=0;$i<3;$i++)
					{
						if($date2==$i){
							echo '<option value="'.$i.'" selected="selected">'.date("Y-m-d",$timenow-($i*60*60*24)).'</option>';
						}else{
							echo '<option value="'.$i.'" >'.date("Y-m-d",$timenow-($i*60*60*24)).'</option>';
						}
					}
				?>
				</select>
			</div></td>
			</tr>
		</table>
		<input id="txtNodeID" type="hidden" size="20" maxlength="256" value="<?php echo $node_id; ?>" />
		
		<table border="0">
		<tr>
		 <td><div id="placeholder" style="width:940px;height:380px; margin:0 auto;"></div></td>
		 </tr>
		</table>
							
		<table width="940" border="0" cellspacing="0" cellpadding="0" style="padding-left:45px">

			<tr>
				<td class="title_line" width="20%" height="25" align="right" style="background-color: whitesmoke"><strong>24小时最大下载带宽：</strong></td>
				<td class="title_line" width="10%" height="25" align="right" style="padding-right: 10px;"><span id="tb_max_mirror_flow_of_1day"><?php echo $max1_bandwidth_down; ?> Mbps</span></td>
				<td class="title_line" width="20%" height="25" align="right" style="background-color: whitesmoke"><strong>24小时平均下载带宽：</strong></td>
				<td class="title_line" width="10%" height="25" align="right" style="padding-right: 10px;"><span id="tb_max_mirror_flow_of_1day"><?php echo $avg1_bandwidth_down; ?> Mbps</span></td>				
				<td class="title_line" width="18%" height="25" align="right" style="background-color: whitesmoke"><strong>3天内最大下载带宽：</strong></td>
				<td class="title_line" width="10%" height="25" align="right" style="padding-right: 10px;"><span id="tb_max_mirror_flow_of_7day"><?php echo $max7_bandwidth_down; ?> Mbps</span></td>
			</tr>
			<tr>
				<td class="title_line" width="20%" height="25" align="right" style="background-color: whitesmoke"><strong>24小时最大上传带宽：</strong></td>
				<td class="title_line" width="10%" height="25" align="right" style="padding-right: 10px;"><span id="tb_avg_mirror_flow_of_1day"><?php echo $max1_bandwidth_up; ?> Mbps</span></td>
				<td class="title_line" width="20%" height="25" align="right" style="background-color: whitesmoke"><strong>24小时平均上传带宽：</strong></td>
				<td class="title_line" width="10%" height="25" align="right" style="padding-right: 10px;"><span id="tb_avg_mirror_flow_of_1day"><?php echo $avg1_bandwidth_up; ?> Mbps</span></td>				
				<td class="title_line" width="18%" height="25" align="right" style="background-color: whitesmoke"><strong>3天内最大上传带宽：</strong></td>
				<td class="title_line" width="10%" height="25" align="right" style="padding-right: 10px;"><span id="tb_avg_mirror_flow_of_7day"><?php echo $max7_bandwidth_up; ?> Mbps</span></td>
			</tr>
		</table>
		
		<br />
		<br />
		<br />		
		
	<table width="1100" border="0" class="bottom_btn">
			<tr height="30" bgcolor="#FFFFE6">
			<td>
			<span class="input_tips_txt3"><?php echo $show_this_name; ?> - 实时带宽详细统计数据</span>
			<div class="div_search_title" style="padding-right:10px">选择日期：			
				<select id="BandwidthDateSelect2" style="width:120px" onChange="OnSelectBandwidthDate2()">
				<?php
					$timenow =time();
					for($i=0;$i<3;$i++)
					{
						if($date2==$i){
							echo '<option value="'.$i.'" selected="selected">'.date("Y-m-d",$timenow-($i*60*60*24)).'</option>';
						}else{
							echo '<option value="'.$i.'" >'.date("Y-m-d",$timenow-($i*60*60*24)).'</option>';
						}
					}
				?>
				</select>
			</div>
			</td>
			</tr>
		</table>
						
		<div id="div_search_result2">
			<table width="800" border="0" class="dataintable" id="domain_table">
				<tr id="tr_domain_title">
					<th align="center" width="150">时间</th> 
					<th align="right" width="80" align="center">用户连接数</th>
					<th align="right" width="80" align="center">源站连接数</th>			
					<th align="right" width="100">用户下载数据</th>
					<th align="right" width="100">用户上传数据</th>
					<th align="right" width="100">用户下载带宽</th>
					<th align="right" width="100" align="center">用户上传带宽</th>
					<th align="right" width="100">源站上传数据</th>
					<th align="right" width="100">源站下载数据</th>
					<th align="right" width="100">源站上传带宽</th>
					<th align="right" width="100" align="center">源站下载带宽</th>		
				</tr>	
<?php				
			$timeval = time()-60*60*24;
			if($db_link){
				$timeval1 = mktime(0,0,0,date("m"),date("d"),date("Y"))-($date2*60*60*24);
				$timeval2 = $timeval1 + 60*60*24;
				//echo "the timeval1 is=". date("Y-m-d H:i:s",$timeval1)."<br/>";
				//echo "the timeval2 is=". date("Y-m-d H:i:s",$timeval2)."<br/>";
				
				$sql = "SELECT * FROM realtime_list where node_id='$node_id' AND time>='$timeval1' AND time<'$timeval2' ORDER BY time DESC"; 
				$result = mysql_query($sql,$db_link);
				if($result){
					$row_count=mysql_num_rows($result);
					for($i=0;$i<$row_count;$i++){
						$id  			= mysql_result($result,$i,"id");	
						$group_id  		= mysql_result($result,$i,"group_id");	
						$this_time  		= mysql_result($result,$i,"time");	
						$down_increase   		= mysql_result($result,$i,"down_increase");	
						$up_increase	= mysql_result($result,$i,"up_increase");
						$upstream_down_increase   		= mysql_result($result,$i,"upstream_down_increase");	
						$upstream_up_increase	= mysql_result($result,$i,"upstream_up_increase");						
						$bandwidth_down	   	= mysql_result($result,$i,"bandwidth_down");		
						$bandwidth_up	   	= mysql_result($result,$i,"bandwidth_up");	
						$upstream_bandwidth_down	   	= mysql_result($result,$i,"upstream_bandwidth_down");		
						$upstream_bandwidth_up	   	= mysql_result($result,$i,"upstream_bandwidth_up");								
						$CurrentUserConnections	   	= mysql_result($result,$i,"CurrentUserConnections");		
						$CurrentUpstreamConnections	   	= mysql_result($result,$i,"CurrentUpstreamConnections");		
						
						echo '<tr bgcolor="#FFFFFF" align="center" onMouseMove="Event_trOnMouseOver(this)" onMouseOut="Event_trOnMouseOut(this)" id="tr_domain_'.$id.'">';
						echo '<td>'.date("Y-m-d H:i:s",$this_time).'</td>';
						echo '<td align="right">'.$CurrentUserConnections.'  个</td>';
						echo '<td align="right">'.$CurrentUpstreamConnections.' 个</td>';		
						echo '<td align="right">'.PubFunc_KBToString($down_increase).'</td>';
						echo '<td align="right">'.PubFunc_KBToString($up_increase).'</td>';
						echo '<td align="right">'.$bandwidth_down.' Mbps</td>';		
						echo '<td align="right">'.$bandwidth_up.' Mbps</td>';							
						
						echo '<td align="right">'.PubFunc_KBToString($upstream_down_increase).'</td>';
						echo '<td align="right">'.PubFunc_KBToString($upstream_up_increase).'</td>';
						echo '<td align="right">'.$upstream_bandwidth_down.' Mbps</td>';		
						echo '<td align="right">'.$upstream_bandwidth_up.' Mbps</td>';												
					}		
				}
			}	
?>								
		 	</table>
		 </div>			
								
	  </td> 
	  <td background="../images/mail_rightbg.gif">&nbsp;</td>
  </tr>

   <tr>
    <td valign="bottom" background="../images/mail_leftbg.gif"><img src="../images/buttom_left2.gif" width="17" height="17" /></td>
    <td background="../images/buttom_bgs.gif"><img src="../images/buttom_bgs.gif" width="17" height="17"></td>
    <td valign="bottom" background="../images/mail_rightbg.gif"><img src="../images/buttom_right2.gif" width="16" height="17" /></td>
  </tr> 
</table>
</div>

<div id="textStatDataTable"></div>

<script type="text/javascript">
var enginConn_chart;

function update_enginConn_chart(data){
	enginConn_chart.redraw();
	var down_data=[];
	var up_data=[];
	var upstream_down_data=[];
	var upstream_up_data=[];

	down_data = __StatDataSets[0];
	up_data = __StatDataSets[1];
	upstream_down_data = __StatDataSets[2];
	upstream_up_data = __StatDataSets[3];
	
	var down_name = down_data['name'];
	var up_name = up_data['name'];
	var upstream_down_name = upstream_down_data['name'];
	var upstream_up_name = upstream_up_data['name'];
	
	var down_num = [];
	var up_num = [];
	var upstream_down_num = [];
	var upstream_up_num = [];
	
	var data_grp = [];
	
	var xData = 0;		
	var yData = 0;	
		
	for(var key in down_data['data'])
	{		
		data_grp = down_data['data'][key];
		
		xData = parseInt(data_grp[0])*1000;	
		yData = parseFloat(data_grp[1]);
				
		down_num.push({ y : yData,x : xData});
	}
	
		
	for(var key in up_data['data'])
	{		
		data_grp = up_data['data'][key];
			
		xData = parseInt(data_grp[0])*1000;	
		yData = parseFloat(data_grp[1]);
				
		up_num.push({ y : yData, x : xData});
	}
	
	for(var key in upstream_down_data['data'])
	{		
		data_grp = upstream_down_data['data'][key];
			
		xData = parseInt(data_grp[0])*1000;	
		yData = parseFloat(data_grp[1]);
				
		upstream_down_num.push({ y : yData, x : xData});
	}
	
	for(var key in upstream_up_data['data'])
	{		
		data_grp = upstream_up_data['data'][key];
			
		xData = parseInt(data_grp[0])*1000;	
		yData = parseFloat(data_grp[1]);
				
		upstream_up_num.push({ y : yData, x : xData});
	}		
		
    for(var k = enginConn_chart.series.length - 1; k >= 0; k--){
         enginConn_chart.series[k].remove();
    }
		
	//var jsonText = JSON.stringify(up_num); 
	//alert(jsonText);
	
	//[{x: 12,y: 10}, {x: 24,y: 45},{x: 34,y: 25},{x: 67,y: 265},{x: 123,y: 365},{x: 233,y: 95},{x: 363,y: 87}],
	
	enginConn_chart.addSeries({
		type: 'area',
		color: '#2ebacb',//'#2f7ed8',
		name: down_name,
		data: down_num,
	});
	
	
	//[{x: 12,y: 310}, {x: 24,y: 345},{x: 34,y: 225},{x: 67,y: 465},{x: 123,y: 78},{x: 233,y: 35},{x: 363,y: 234}],
	enginConn_chart.addSeries({
		type: 'area',
		color: '#f0d52e',//color: '#a8d822',
		name:  up_name,
		data: up_num,
	});
	
	enginConn_chart.addSeries({
		type: 'area',
		color:'#2f7ed8',
		name:  upstream_down_name,
		data: upstream_down_num,
	});
	
	enginConn_chart.addSeries({
		type: 'area',
		color:'#8bbc21',		
		name:  upstream_up_name,
		data: upstream_up_num,
	});		
}

Highcharts.setOptions( {
	global : {
		useUTC : false
	}
});

jQuery(document).ready(function(){
		sLabelName='';
		aryData=[];                          

		enginConn_chart = new Highcharts.Chart({
		   chart: {
				renderTo: 'placeholder',
				defaultSeriesType: 'spline',
                marginRight: 0,
                marginBottom: 40,
				backgroundColor: '#F8F9FA'
		   },                                 

		   title: {
				text: '<span class="input_tips_txt"><strong><?php echo $show_this_name; ?></strong></span>',
				style: {color:'#004499',fontSize:'13px'},
				align: 'center',
				x: -140, //center
				y: 15
		   },
		   /*	
           subtitle: {
               text: '服务器带宽统计',
               x: -20
           },
		   */
		   xAxis: {
				type: 'datetime',
            	lineWidth :2,//自定义x轴宽度  
            	gridLineWidth :0,//默认是0，即在图上没有纵轴间隔线
				dateTimeLabelFormats : {
					second: '%H:%M:%S',
					minute: '%H:%M',
					hour: '%H:%M',
					day: '%m-%d', 
					week: '%m-%d',
					month: '%Y-%m',
					year: '%Y'
				},		
				lineColor : '#3E576F'
		   },

  		   exporting:{
				// 是否允许导出
				enabled:false,
				// 按钮配置
				buttons:{
					// 导出按钮配置
					exportButton:{
						menuItems: null,
						onclick: function() {
							this.exportChart();
						}
					},
					// 打印按钮配置
					printButton:{
						enabled:false
					}
				},
				// 文件名
				filename: '报表',
				// 导出文件默认类型
				type:'application/pdf'
			},
			
    		plotOptions: {
				area: {
					fillOpacity: 0.2,
					lineWidth: 1,
					marker: {
						enabled: false,
						states: {
							hover: {
								enabled: true,
								radius: 5
							}
						}
					},
					shadow: false,
					states: {
						hover: {
							lineWidth: 1
						}
					},
					threshold: null
				}
			},
			/*									   
            plotOptions: {
                area: {
                    fillColor: {
                        linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
                        stops: [
                            [0, Highcharts.getOptions().colors[0]],
                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]				
                        ]
                    },
                    lineWidth: 1,
                    marker: {
                        enabled: false
                    },
                    shadow: false,
                    states: {
                        hover: {
                            lineWidth: 1
                        }
                    },
                    threshold: null
                }
            },
			*/
		   yAxis: {
		   		min: 0,
				labels:{
					// 标签位置
					align: 'right',
					// 标签格式化
					formatter: function(){
						return this.value + ' Mbps';
					}
				},
								  
				title: {
					text: '服务器带宽统计',
					style: {color:'#aaaaaa',fontSize:'12px'},
				},
				showFirstLabel: true,  
				plotLines: [{
						 value: 0,
						 width: 1,
						 color: '#87BED3'
				}]
		   },
		   
		   tooltip: {
		   		enabled: true,
				userHTML: true,
				valueSuffix: 'Mbps',
				formatter: function() { //当鼠标悬置数据点时的格式化提示 
					var myDate = new Date(this.x);
					var strTime = numAddZero((myDate.getMonth()+1),2) + '-' + numAddZero(myDate.getDate(),2) + " " + numAddZero(myDate.getHours(),2) + ':' + numAddZero(myDate.getMinutes(),2) + ':' + numAddZero(myDate.getSeconds(),2); 
					
					//var strTime = myDate.toLocaleString();
	       	        return '<b>' + strTime + '</b><br/><b>' + this.series.name + ': ' + this.y + ' Mbps</b>'; 
				}
		   },
		   
           legend: {
				enabled: true,       
                layout: 'horizontal',
                align: 'right',
                verticalAlign: 'top',
                x: 0,
                y: 0,
                borderWidth: 0
            },		   
   			
			credits: {  
                enabled: false     //去掉highcharts网站url  
           	},
	});
});

var nCurBandwidthDateSelect = document.getElementById("BandwidthDateSelect").value;
getNodeBandwidthStatData(<?php echo $node_id; ?>,nCurBandwidthDateSelect);
</script>
<?php

include_once("./tail.php");
?>
