<?php



class Webfront{
	
	private $actionfront;

	
	function __construct(Actionfront $actionfront){
		
		$this->actionfront = $actionfront;
	}
	
	function printPage(){
		
		echo <<<HTML
<!DOCTYPE html>
<html>
  <head>
  
    <meta charset="UTF-8">
		
    <link rel="stylesheet" type="text/css" href="lzstyle.css">
  <title>
    Lztech(ALPHA)
  </title>
  
  <script>
  
  var wanip = '';
  var loadingimg = '<img src="loading2.gif">';
  var bwmonip = '';
  
  function getRouterInfo(){
	  document.getElementById("errorz").innerHTML = '';
	  try{
	  	var ip = document.getElementById("ip").value;
	  	var xmlhttp;
	  	var xmlDoc;
	  	if (window.XMLHttpRequest){
			//Superior Browsers and IE7+
			xmlhttp=new XMLHttpRequest();
	  	}else{
	  		//Terrible browsers you would never use, IE6,IE5
	  		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  	}
	  	xmlhttp.onreadystatechange=function(){
	  	//This function gets called on our object's state change
	  		if(xmlhttp.readyState==1){
	  			document.getElementById("loading1").innerHTML = loadingimg;
	  		}
	  		if(xmlhttp.readyState==2){
	  			document.getElementById("loading1").innerHTML = loadingimg;
	  		}
	  		if(xmlhttp.readyState==3){
	  			document.getElementById("loading1").innerHTML = loadingimg;
	  		}
	  		if(xmlhttp.readyState==4 && xmlhttp.status==200){
	
	  			//update the web interface here with our server's response
	  			xmlDoc = xmlhttp.responseXML;
	  			if(xmlDoc.getElementsByTagName("commandsummary").length>0){
	
	  			
	  				wanip = ip;	
	  			
		  			document.getElementById("bri").innerHTML = xmlDoc.getElementsByTagName("shbri")[0].childNodes[0].nodeValue;
		  			document.getElementById("arp").innerHTML = xmlDoc.getElementsByTagName("sharp")[0].childNodes[0].nodeValue;
		  			document.getElementById("int").innerHTML = xmlDoc.getElementsByTagName("shint")[0].childNodes[0].nodeValue;
		  			document.getElementById("con").innerHTML = xmlDoc.getElementsByTagName("shcon")[0].childNodes[0].nodeValue;
		  			document.getElementById("log").innerHTML = xmlDoc.getElementsByTagName("shlog")[0].childNodes[0].nodeValue;
		  			document.getElementById("clo").innerHTML = xmlDoc.getElementsByTagName("shclo")[0].childNodes[0].nodeValue;
		  			document.getElementById("rou").innerHTML = xmlDoc.getElementsByTagName("shrou")[0].childNodes[0].nodeValue;
		  			document.getElementById("run").innerHTML = xmlDoc.getElementsByTagName("shrun")[0].childNodes[0].nodeValue;
		   			document.getElementById("qos").innerHTML = xmlDoc.getElementsByTagName("shqos")[0].childNodes[0].nodeValue;
		  			document.getElementById("acc").innerHTML = xmlDoc.getElementsByTagName("sh100")[0].childNodes[0].nodeValue;
		  			document.getElementById("ver").innerHTML = xmlDoc.getElementsByTagName("shver")[0].childNodes[0].nodeValue;
		  			
		  			document.getElementById("featurecheck").innerHTML = xmlDoc.getElementsByTagName("featurecheck")[0].childNodes[0].nodeValue;
		  			document.getElementById("intsum").innerHTML = xmlDoc.getElementsByTagName("intsum")[0].childNodes[0].nodeValue;
		  			document.getElementById("workint").innerHTML = xmlDoc.getElementsByTagName("workint")[0].childNodes[0].nodeValue;
		  			document.getElementById("tablecheck").innerHTML = xmlDoc.getElementsByTagName("tablecheck")[0].childNodes[0].nodeValue;
	  			
	  			}else if (xmlDoc.getElementsByTagName("connect_error").length>0){
	
	  				document.getElementById("errorz").innerHTML = xmlDoc.getElementsByTagName("connect_error")[0].getAttribute("error");
	  			}else if (xmlDoc.getElementsByTagName("range_error").length>0){
	
	  				document.getElementById("errorz").innerHTML = xmlDoc.getElementsByTagName("range_error")[0].getAttribute("error");
	  				
	  			}else if (xmlDoc.getElementsByTagName("invalid_ip_error").length>0){
	
	  				document.getElementById("errorz").innerHTML = xmlDoc.getElementsByTagName("invalid_ip_error")[0].getAttribute("error");
	  			}
	  		}
	  		if(xmlhttp.readyState!=1 && xmlhttp.readyState!=2 && xmlhttp.readyState!=3) document.getElementById("loading1").innerHTML = '';
	  		
	  		
	  	}
	  	
	  	xmlhttp.open("POST",window.location,true);
	  	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
	  	xmlhttp.send("ip="+ip);
	  }catch(err){
	  	alert(err.message);
	  }
  
  }
  

  function pingtrace(){
	  try{
	  	var ip = document.getElementById("iptrace").value;
	  	var action = '';
	  	var int = document.getElementById("interfaces");
	  	var selectedint = int.options[int.selectedIndex].value;
	  	if(document.getElementById("traceroute").checked){
	  		action = "traceroute";
	  	}else action = "ping";
	  	var xmlhttp;
	  	if (window.XMLHttpRequest){
			//Superior Browsers and IE7+
			xmlhttp=new XMLHttpRequest();
	  	}else{
	  		//Terrible browsers you would never use, IE6,IE5
	  		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  	}
	  	xmlhttp.onreadystatechange=function(){
	  	//This function gets called on our object's state change
	  	
	  		if(xmlhttp.readyState==1){
	  			document.getElementById("loading2").innerHTML = loadingimg;
	  		}
	  		if(xmlhttp.readyState==2){
	  			document.getElementById("loading2").innerHTML = loadingimg;
	  		}
	  		if(xmlhttp.readyState==3){
	  			document.getElementById("loading2").innerHTML = loadingimg;
	  		}
	  		if(xmlhttp.readyState==4 && xmlhttp.status==200){
	  			//update the web interface here with our server's response
	  			xmlDoc = xmlhttp.responseXML;
	  			if(xmlDoc.getElementsByTagName("ptresult").length>0){
	  				document.getElementById("pingtraceio").innerHTML=xmlDoc.getElementsByTagName("ptresult")[0].childNodes[0].nodeValue;
	  			}else if(xmlDoc.getElementsByTagName("pterror").length>0){
	  				document.getElementById("pterrorz").innerHTML=xmlDoc.getElementsByTagName("pterror")[0].getAttribute("error");
	  			}else if(xmlDoc.getElementsByTagName("range_error").length>0){
	  				document.getElementById("pterrorz").innerHTML=xmlDoc.getElementsByTagName("range_error")[0].getAttribute("error");
	  			}else if(xmlDoc.getElementsByTagName("invalid_ip_error").length>0){
	  				document.getElementById("pterrorz").innerHTML=xmlDoc.getElementsByTagName("invalid_ip_error")[0].getAttribute("error");
	  			}else if(xmlDoc.getElementsByTagName("invalid_action_error").length>0){
	  				document.getElementById("pterrorz").innerHTML=xmlDoc.getElementsByTagName("invalid_action_error")[0].getAttribute("error");
	  			}else if(xmlDoc.getElementsByTagName("invalid_int_error").length>0){
	  				document.getElementById("pterrorz").innerHTML=xmlDoc.getElementsByTagName("invalid_int_error")[0].getAttribute("error");
	  			}
	  		}
	  		if(xmlhttp.readyState!=1 && xmlhttp.readyState!=2 && xmlhttp.readyState!=3) document.getElementById("loading2").innerHTML = '';
	  	}
	  	
	  	xmlhttp.open("POST",window.location,true);
	  	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
	  	xmlhttp.send("iptrace="+ip+"&pt="+action+"&interface="+selectedint+"&wanip="+wanip);
	  }catch(err){
	  	alert(err.message);
	  }
  
  }
  
	function showProgram(p){
	    if(p=="lztechmain"){
	  		document.getElementById("lztechmain").style.display="block";
	  		document.getElementById("bwmon").style.display="none";
	  		document.getElementById("lztechbutton").style.borderWidth="1px 1px 4px 1px";
	  		document.getElementById("bwmonbutton").style.borderWidth="1px 1px 1px 1px";
	  	}else if(p=="bwmon"){
	  	  	document.getElementById("bwmon").style.display="block";
	  		document.getElementById("lztechmain").style.display="none";
	  		document.getElementById("bwmonbutton").style.borderWidth="1px 1px 4px 1px";
	  		document.getElementById("lztechbutton").style.borderWidth="1px 1px 1px 1px";
	  	}
  	
  	
	}
	
  
	function selectText(containerid) {
        if (document.selection) {
            var range = document.body.createTextRange();
            range.moveToElementText(document.getElementById(containerid));
            range.select();
        } else if (window.getSelection) {
            var range = document.createRange();
            range.selectNode(document.getElementById(containerid));
            window.getSelection().addRange(range);
        }
    }
 <!-- BWMON SCRAPPED ..FOR NOW------------------------------------------------------
  	//-----------bwmon functions-----------------//
  	
  	function bwmonWan(){
	  try{
	  	var ip = document.getElementById("bwmonip").value;
	  	bwmonip = ip;
	  	var xmlhttp;
	  	if (window.XMLHttpRequest){
			//Superior Browsers and IE7+
			xmlhttp=new XMLHttpRequest();
	  	}else{
	  		//Terrible browsers you would never use, IE6,IE5
	  		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  	}
	  	xmlhttp.onreadystatechange=function(){
	  	//This function gets called on our object's state change
	  	
	  		if(xmlhttp.readyState==1){
	  			document.getElementById("bwmonloading1").innerHTML = loadingimg;
	  		}
	  		if(xmlhttp.readyState==2){
	  			document.getElementById("bwmonloading1").innerHTML = loadingimg;
	  		}
	  		if(xmlhttp.readyState==3){
	  			document.getElementById("bwmonloading1").innerHTML = loadingimg;
	  		}
	  		if(xmlhttp.readyState==4 && xmlhttp.status==200){
	  			//update the web interface here with our server's response
	  			xmlDoc = xmlhttp.responseXML;
	  			
	  			document.getElementById("bwmoncreate2").innerHTML = xmlDoc.getElementsByTagName("step2")[0].getAttribute("step2");
	  			bwmonip = xmlDoc.getElementsByTagName("step2")[0].getAttribute("bwmonip");
	  		}
	  		if(xmlhttp.readyState!=1 && xmlhttp.readyState!=2 && xmlhttp.readyState!=3) document.getElementById("bwmonloading1").innerHTML = '';
	  	}
	  	
	  	xmlhttp.open("POST",window.location,true);
	  	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
	  	xmlhttp.send("bwmonip="+ip);
	  }catch(err){
	  	alert(err.message);
	  }
	  
  }
  
	function bwmonExecute(){
	  	try{
	  		var ip = bwmonip;
		  	var int = document.getElementById("bwmonint");
	  		var selectedint = int.options[int.selectedIndex].value;
	  		var interval = document.getElementById("bwmoninterval");
	  		var selectedinterval = interval.options[interval.selectedIndex].value;
	  		var lifecycle = document.getElementById("bwmonlifecycle");
	  		var selectedlifecycle = lifecycle.options[lifecycle.selectedIndex].value;
	   		var cronname = document.getElementById("cronname").value;
	  		
		  	var xmlhttp;
		  	if (window.XMLHttpRequest){
				//Superior Browsers and IE7+
				xmlhttp=new XMLHttpRequest();
		  	}else{
		  		//Terrible browsers you would never use, IE6,IE5
		  		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		  	}
		  	xmlhttp.onreadystatechange=function(){
		  	//This function gets called on our object's state change
		  	
		  		if(xmlhttp.readyState==1){
		  			document.getElementById("bwmonloading2").innerHTML = loadingimg;
		  		}
		  		if(xmlhttp.readyState==2){
		  			document.getElementById("bwmonloading2").innerHTML = loadingimg;
		  		}
		  		if(xmlhttp.readyState==3){
		  			document.getElementById("bwmonloading2").innerHTML = loadingimg;
		  		}
		  		if(xmlhttp.readyState==4 && xmlhttp.status==200){
		  			//update the web interface here with our server's response
		  			xmlDoc = xmlhttp.responseXML;
		  			
		  			document.getElementById("bwmoncreate2").innerHTML = xmlDoc.getElementsByTagName("croncomplete")[0].getAttribute("croncomplete");
		  		}
		  		if(xmlhttp.readyState!=1 && xmlhttp.readyState!=2 && xmlhttp.readyState!=3) document.getElementById("bwmonloading2").innerHTML = '';
		  	}
		  	
		  	xmlhttp.open("POST",window.location,true);
		  	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
		  	xmlhttp.send("bwmonipexe="+ip+"&int="+selectedint+"&interval="+selectedinterval+"&lifecycle="+selectedlifecycle+"&cronname="+cronname);
		  }catch(err){
		  	alert(err.message);
		  }
		  
  }
  
	function showBwOption(i){
		
		if(i=="bwmonsearch"){
			document.getElementById(i).style.display="block";
			document.getElementById("bwmoncreate").style.display="none";
	  		document.getElementById("bwslink").style.borderBottomWidth="4px";
	  		document.getElementById("bwclink").style.borderBottomWidth="1px";
		}else{
			document.getElementById("bwmoncreate").style.display="block";
			document.getElementById("bwmonsearch").style.display="none";
	  		document.getElementById("bwslink").style.borderBottomWidth="1px";
	  		document.getElementById("bwclink").style.borderBottomWidth="4px";
		}
			
	
	}
  	-->
  </script>
		
  </head>
		
  <body>
		
  	<div id = "main">
		
		
  		<div id = "header">
		
  			<div id = "header-content">
  				Lz Tech_(v.0.0.1 alpha)
  			</div>

  		</div>
  		
  		<div id = "program-tuple">
  			<a href="#" id = "lztechbutton" style = "border-width:1px 1px 4px 1px" onclick="showProgram('lztechmain');return false;">LZ Tech Main</a>
  			<!--<a href="#" id = "bwmonbutton" onclick="showProgram('bwmon');return false;">Bandwidth Monitor</a> BWMON SCRAPPED ..FOR NOW -->
  				  				
  		</div>
  		
  		<div id = "lztechmain">
		
	  		<div id = "input">
				
	  			<table>
	  				<tr>
						<td>IP: <input type="text" id = "ip"></td>
						<td><input type='submit' value='Go' onclick="getRouterInfo()"></td>
						<td height="60" id="loading1"></td>
					</tr>
				</table>
	
			    <br />
			    <span id = "errorz" style="color:red"></span>
	
	  		</div>
			
	  		<div id = "output">
	
				<div class = "hud-container">
					<div class = "hud">
						<div class = "hud-title">
							Router Summary
						</div>
						<div class = "hud-box">
							<div class = "hud-table" id = "featurecheck">
	
							</div>
							<div class = "hud-table" id = "intsum">
	
							</div>
							<div class = "hud-table" id = "tablecheck">
	
							</div>
						</div>
					</div>
				</div>
				
				<div class = "hud-container">
					<div class = "hud">
						<div class = "hud-title">
							Ping/Traceroute Test
						</div>
						<div class = "hud-box" id = "workint">
							
						</div>
					</div>
	  			</div>
	  			
	  			<div class = "hud-container">
	  				
	  				<div class = "hud">
	  					
	  					<div class = "hud-title">
	  						
	  						Command Summary
	  					
	  					</div>
	  					
	  					<div class = "hud-box">
			
				  			<div class = "command_box">
				  				<div class = "command_title">
				  				</div>
						  			<div class = "commands" id = "bri" onclick="selectText('bri')">
	
						
						  			</div>
				  			</div>
				  
				  			<div class = "command_box">
				  				<div class = "command_title">
				  				</div>
						  			<div class = "commands" id="arp" onclick="selectText('arp')">
				
						
						  			</div>
				  			</div>
						
				  			<div class = "command_box">
				  				<div class = "command_title">
				  				</div>
						  			<div class = "commands" id="int" onclick="selectText('int')">
				
						  			</div>
				  			</div>
				  
				   			<div class = "command_box">
				  				<div class = "command_title">
				  				</div>
						  			<div class = "commands" id="con" onclick="selectText('con')">
				
				
						  			</div>
				  			</div>
				  
				   			<div class = "command_box">
				  				<div class = "command_title">
				  				</div>
						  			<div class = "commands" id="log" onclick="selectText('log')">
	
				
						  			</div>
				  			</div>
				  			
				   			<div class = "command_box">
				  				<div class = "command_title">
				  				</div>
						  			<div class = "commands" id="clo" onclick="selectText('clo')">
	
						  			</div>
				  			</div>
				  
				   			<div class = "command_box">
				  				<div class = "command_title">
				  				</div>
						  			<div class = "commands" id="rou" onclick="selectText('rou')">
	
						  			</div>
				  			</div>
				  
				  			<div class = "command_box">
				  				<div class = "command_title">
				  				</div>
						  			<div class = "commands" id="run" onclick="selectText('run')">
	
						  			</div>
				  			</div>
				  
				  			<div class = "command_box">
				  				<div class = "command_title">
				  				</div>
						  			<div class = "commands" id="qos" onclick="selectText('qos')">
	
						  			</div>
				  			</div>
				  
				   			<div class = "command_box">
				  				<div class = "command_title">
				  				</div>
						  			<div class = "commands" id="acc" onclick="selectText('acc')">
	
						  			</div>
				  			</div>
				  
				  			<div class = "command_box">
				  				<div class = "command_title">
				  				</div>
						  			<div class = "commands" id="ver" onclick="selectText('ver')">
	
						  			</div>
				  			</div>
				  			
				  		</div>
			  			
			  		</div>
		  			
		  		</div>
			
	  		</div>
	  		
	  	</div>
<!-- BW MONITOR SCRAPPED ..FOR NOW--------------------------------------------------------------------
	  	
	  	<div id = "bwmon" style="display:none">
	  	
	  		<div id = "bwmoninput">
	  			
	  			
	  			<div id="bwmonlinks">
	  				
	  				<ul>
		  				<li><a href="#" id="bwslink" style="border-bottom:4px solid #000" onclick="showBwOption('bwmonsearch')">Search</a></li>
		  				<li><a href="#" id="bwclink" onclick="showBwOption('bwmoncreate')">Create</a></li>
		  			</ul>

	  			
	  			</div>

	  			<div id="bwmoncreate" style="display:none">
	  			
	  				<div id="bwmoncreate1">

		  		
			  			<h4>Enter the WAN IP of the circuit you wish to monitor</h4>
						
			  			<table>
			  				<tr>
								<td>IP: <input type="text" id = "bwmonip"></td>
								<td><input type='submit' value='Go' onclick="bwmonWan()"></td>
								<td height="60" id="bwmonloading1"></td>
							</tr>
						</table>
			
					    <br />
					    <span id = "errorz" style="color:red"></span>
				    
				    </div>
				    
					<div id = "bwmoncreate2">
				
					</div>
					
				</div>
				
				<div id="bwmonsearch">
				
			  			<h4>Search for an existing Monitor</h4>
						
			  			<table>
			  				<tr>
								<td>Name: <input type="text" id = "bwmonip"></td>
								<td><input type='submit' value='Go' onclick="bwmonWan()"></td>
								<td height="60" id="bwmonloading1"></td>
							</tr>
						</table>
			
					    <br />
					    <span id = "errorz" style="color:red"></span>
				
				
				</div>
				
				
	  		</div>
	  	</div> -->
  		
  		<!--<div id = "footer">

  		</div>-->
  	</div>
		
		
  </body>
		
		
</html>
HTML;
	}
	

}
?>