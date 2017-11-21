<?php
include_once("./head.php");
?>
<script language="javascript" src="../js/calendar.js"></script>
<script type="text/javascript">	
function FikAgent_Modify(){
	var txtRealname=document.getElementById("txtRealname").value;
	var txtAddr=document.getElementById("txtAddr").value;
	var txtPhone=document.getElementById("txtPhone").value;
	var txtRelation=document.getElementById("txtRelation").value;
	var txtQQ=document.getElementById("txtQQ").value;
	
	if (txtRealname.length==0 ){ 
	  	document.getElementById("tipsRealname").innerHTML="请输入姓名";
		document.getElementById("txtRealname").focus();
	  	return false;
	}
	else{
		document.getElementById("tipsRealname").innerHTML="";
	}
	if (txtAddr.length==0 ){ 
	  	document.getElementById("tipsAddr").innerHTML="请输入联系地址";
		document.getElementById("txtAddr").focus();
	  	return false;
	}
	else{
		document.getElementById("tipsAddr").innerHTML="";
	}

	
	if (txtPhone.length==0 ){ 
	  	document.getElementById("tipsPhone").innerHTML="请输入电话号码";
		document.getElementById("txtPhone").focus();
	  	return false;
	}
	else{
		document.getElementById("tipsPhone").innerHTML="";
	}	
	
	if (txtQQ.length==0 ){ 
	  	document.getElementById("tipsQQ").innerHTML="请输入QQ号码";
		document.getElementById("txtQQ").focus();
	  	return false;
	}
	else{
		document.getElementById("tipsQQ").innerHTML="";
	}	
		
	var postURL="./ajax.php?mod=setting&action=modifyinfo";
	var postStr="Realname="+UrlEncode(txtRealname)+"&Relation_sale=" + UrlEncode(txtRelation) + "&Addr=" + UrlEncode(txtAddr) + "&Phone=" + UrlEncode(txtPhone) + 
			    "&QQ=" + UrlEncode(txtQQ);				 
					 
	AjaxClientBasePost("setting","modifyinfo","POST",postURL,postStr);
}

function FikCdn_ClientModifyInfoResult(sResponse){
	var json = eval("("+sResponse+")");
	if(json["Return"]=="True"){		
		window.location.href = "./client_info.php";	
	}else{
		var nErrorNo = json["ErrorNo"];
		var strErr = json["ErrorMsg"];	
	
		if(nErrorNo==30000){
			parent.location.href = "./login.php"; 
		}else{
			var boxURL="msg.php?1.9&msg="+strErr;
			showMSGBOX('',350,100,BT,BL,120,boxURL,'操作提示:');
		}
	}	
}

</script>

<div style="min-width:780px;">
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F8F9FA">
  <tr>
    <td width="17" valign="top" background="../images/mail_leftbg.gif"><img src="../images/left-top-right.gif" width="17" height="29" /></td>
    <td valign="top" background="../images/content-bg.gif">
	   <table width="100%" height="31" border="0" cellpadding="0" cellspacing="0" class="left_topbg" id="table2">
			<tr height="31" class="TabToolTitle">
				<td height="31" width="85"><span class="title_bt">修改资料</span></td>
				<td width="95%"></td>
			</tr>	
        </table>
	</td>
    <td width="16" valign="top" background="../images/mail_rightbg.gif"><img src="../images/nav-right-bg.gif" width="16" height="29" /></td>
  </tr>    
  
<?php
	$client_username 	= $_SESSION['fikcdn_client_username'];
	$db_link = FikCDNDB_Connect();
	if($db_link)
	{
		$client_username = mysql_real_escape_string($client_username);
		
		$sql = "SELECT * FROM fikcdn_client WHERE username='$client_username'";
		$result = mysql_query($sql,$db_link);
		if($result && mysql_num_rows($result)>0)
		{		
			$id  			=mysql_result($result,0,"id");
			$real_name		=mysql_result($result,0,"realname");	
			$phone 			=mysql_result($result,0,"phone");
			$relation_sale 			=mysql_result($result,0,"relation_sale");		
			$qq 			=mysql_result($result,0,"qq");
			$addr 			=mysql_result($result,0,"addr");
			$note			=mysql_result($result,0,"note");
			$money			=mysql_result($result,0,"money");
			$enable_login	=mysql_result($result,0,"enable_login");
			$last_login_ip	=mysql_result($result,0,"last_login_ip");
			$last_login_time=mysql_result($result,0,"last_login_time");
			$login_count	=mysql_result($result,0,"login_count");		
		}
			
		mysql_close($db_link);
	}
?>		
  
  <tr height="500">
	  <td valign="middle" background="../images/mail_leftbg.gif">&nbsp;</td> 
	  <td valign="top">
		  <table width="800" border="0" class="dataintable">
			<tr>
				<th colspan="2" align="left" height="35"></th> 
			</tr>	
			<tr bgcolor="#FFFFFF" onMouseMove="Event_trOnMouseOver(this)" onMouseOut="Event_trOnMouseOut(this)">
				<td width="200" height="35" align="right"><span class="input_tips_txt3">登录用户名：</span></td>
				<td> <label><?php echo $client_username;  ?></label></td>
    		</tr>
			<tr bgcolor="#FFFFFF" onMouseMove="Event_trOnMouseOver(this)" onMouseOut="Event_trOnMouseOut(this)">
				<td width="200" height="35" align="right"><span class="input_tips_txt5" >*</span><span class="input_tips_txt3">姓名：</span></td>
				<td><input id="txtRealname" type="text" size="26" maxlength="64" value="<?php echo $real_name; ?>" /> <span class="input_tips_txt" id="tipsRealname" ></span>  </td>
    		</tr>					
			<tr bgcolor="#FFFFFF" onMouseMove="Event_trOnMouseOver(this)" onMouseOut="Event_trOnMouseOut(this)">
				<td width="200" height="35" align="right"><span class="input_tips_txt5" >*</span><span class="input_tips_txt3">联系地址：</span></td>
				<td><input id="txtAddr" type="text" size="64" maxlength="128" value="<?php echo $addr; ?>" />  <span class="input_tips_txt" id="tipsAddr" name="tipsAddr" ></span> </td>
    		</tr>	
    		<tr bgcolor="#FFFFFF" onMouseMove="Event_trOnMouseOver(this)" onMouseOut="Event_trOnMouseOut(this)">
				<td width="200" height="35" align="right"><span class="input_tips_txt5" >*</span><span class="input_tips_txt3">关联业务员：</span></td>
				<td><input id="txtRelation" name="txtRelation" type="text" size="26" maxlength="16" value="<?php echo $relation_sale; ?>" />  <span class="input_tips_txt" id="tipsPhone" name="tipsPhone" ></span> </td>
    		</tr>			
			<tr bgcolor="#FFFFFF" onMouseMove="Event_trOnMouseOver(this)" onMouseOut="Event_trOnMouseOut(this)">
				<td width="200" height="35" align="right"><span class="input_tips_txt5" >*</span><span class="input_tips_txt3">联系电话：</span></td>
				<td><input id="txtPhone" name="txtPhone" type="text" size="26" maxlength="16" value="<?php echo $phone; ?>" />  <span class="input_tips_txt" id="tipsPhone" name="tipsPhone" ></span> </td>
    		</tr>			
			<tr bgcolor="#FFFFFF" onMouseMove="Event_trOnMouseOver(this)" onMouseOut="Event_trOnMouseOut(this)">
				<td width="200" height="35" align="right"><span class="input_tips_txt5" >*</span><span class="input_tips_txt3">QQ号码：</span></td>
				<td><input id="txtQQ" name="txtQQ" type="text" size="26" maxlength="16" value="<?php echo $qq; ?>"  />  <span class="input_tips_txt" id="tipsQQ" name="tipsQQ" ></span> </td>
    		</tr>
			<tr bgcolor="#FFFFFF" onMouseMove="Event_trOnMouseOver(this)" onMouseOut="Event_trOnMouseOut(this)">
				<td width="200" height="35" align="right"><span class="input_tips_txt3">账户余额：</span></td>
				<td> <label><?php echo $money;  ?> 元</label></td>
    		</tr>																
			<tr bgcolor="#FFFFFF" onMouseMove="Event_trOnMouseOver(this)" onMouseOut="Event_trOnMouseOut(this)">
				<td width="200" height="35" align="center"> </td>
				<td>
				<input name="btn_modify"  id="btn_modify" type="submit" style="width:80px;height:28px"  value="保存" style="cursor:pointer;" onClick="FikAgent_Modify();" /> 
				</td>
    		</tr>
		 </table>
		 <p></p>
		 
		
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

<?php

include_once("./tail.php");
?>
