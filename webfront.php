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
  
  function getRouterInfo(){
  try{
  	var ip = document.getElementById("ip").value;
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
  		if(xmlhttp.readyState==4 && xmlhttp.status==200){
  			//update the web interface here with our server's response
  			document.getElementById("pingtraceio").innerHTML=xmlhttp.responseText
  			
  		}
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
  	var ip = document.getElementById("iptraceip").value;
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
  		if(xmlhttp.readyState==4 && xmlhttp.status==200){
  			//update the web interface here with our server's response
  			document.getElementById("pingtraceio").innerHTML=xmlhttp.responseText;
  		}
  	}
  	
  	xmlhttp.open("POST",window.location,true);
  	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
  	xmlhttp.send("iptraceip=test");
  }catch(err){
  	alert(err.message);
  }
  
  }
  
  function showHide(d){
  	if(document.getElementById(d).style.display=='none'){
  		document.getElementById(d).style.display='block';
  	}else document.getElementById(d).style.display='none';
  
  	
  }
  
  </script>
		
  </head>
		
  <body>
		
  	<div id = "main">
		
		
  		<div id = "header">
		
  			<div id = "header-content">
  				Lz Tech_(v.0.0.1 alpha)
  			</div>
		
  		</div>
		
  		<div id = "input">

		  	IP: <input type="text" id = "ip" value="{$this->actionfront->ip}">
		  	<input type='submit' value='Go' onclick="getRouterInfo()">

		
		    <br />
		    <span style="color:red">{$this->actionfront->tryerror}</span>
		    <span style="color:red">{$this->actionfront->iperror}</span>
		
		
  		</div>
		
  		<div id = "output">
  		
HTML;
		echo <<<HTML
		
			<div class = "hud-container">
				<div class = "hud">
					<div class = "hud-title" onclick="showHide('inthide')">
						<span style = "font-size:1.9em">+</span><span style = "text-decoration:underline">Interface Summary</span>
					</div>
					<div class = "hud-box" id = "inthide">
						<div class = "hud-table">
							<table>
								<tr>
									<th>Interface</th>
									<th>IP Address</th>
									<th>Status</th>
								</tr>
HTML;
		
		if(!empty($this->actionfront->interfaces)){

			for($i=0;$i<$this->actionfront->intcount;$i++){
				if($i%2==0)$color = "#CCDEEB";
				else $color = "#E6EFF5";
				echo "<tr style='background-color:$color'>";
				if($this->actionfront->interfaces[$i][3] == 'up' && $this->actionfront->interfaces[$i][4] == 'up'){

					echo "<td>" . $this->actionfront->interfaces[$i][1] . "</td>" . "<td>" . $this->actionfront->interfaces[$i][2] . "</td>" . "<td><span style='color:green'>UP</span></td>";
				}else echo "<td>" . $this->actionfront->interfaces[$i][1] . "</td>" . "<td>" . $this->actionfront->interfaces[$i][2] . "</td>" . "<td><span style='color:red'>DOWN</span></td>";
				
				echo "</tr>";
				
			}
	}
		echo <<<HTML
	
							</table>
						</div>
					</div>
				</div>
				<div class = "hud">
					<div class = "hud-title" onclick="showHide('pthide')">
						<span style = "font-size:1.9em">+</span><span style = "text-decoration:underline">Ping/Traceroute Test</span>
					</div>
					<div class = "hud-box" id = "pthide">
						<div class = "hud-table">
							<table>
								<tr>
									<th>Source Interface</th>
									<th>Action</th>
									<th>Destination IP</th>
								</tr>
								<tr>
									<td>
										<select>
HTML;
		if(!empty($this->actionfront->interfaces)){
			for($i=0;$i<count($this->actionfront->workinginterfaces);$i++){
				echo "<option value='test'>" . $this->actionfront->workinginterfaces[$i][1] . "</option>";
			}
			
		}
		echo <<<HTML
										</select>
									</td>
									<td><form action="">
											<input type="radio" name="action" value="ping">Ping<br>
											<input type="radio" name="action" value="traceroute">Traceroute
										</form>
									</td>
									<td><input type='text' id='iptraceip' value=""></td>
									<td><input type='submit' value='Go' onclick="pingtrace()"></td>
								</tr>
							</table>
						</div>
						<div id = "pingtraceio">
						
							
						</div>
					</div>
				</div>
  			</div>
  			
  			<div class = "hud-container">
  				
  				<div class = "hud">
  					
  					<div class = "hud-title" onclick="showHide('comhide')">
  						
  						<span style = "font-size:1.9em">+</span><span style = "text-decoration:underline">Command Summary</span>
  					
  					</div>
  					
  					<div class = "hud-box" id = "comhide">
		
			  			<div class = "command_box">
			  				<div class = "command_title">
			  					Show ip interface brief
			  				</div>
					  			<div class = "commands" id = "bri">
					  				{$this->actionfront->shipintbri}
			
					
					  			</div>
			  			</div>
			  
			  			<div class = "command_box">
			  				<div class = "command_title">
			  					Show arp
			  				</div>
					  			<div class = "commands" id="arp">
					  				{$this->actionfront->sharp}
			
					
					  			</div>
			  			</div>
					
			  			<div class = "command_box">
			  				<div class = "command_title">
			  					Show interfaces
			  				</div>
					  			<div class = "commands" id="int">
					  				{$this->actionfront->shint}
			
					  			</div>
			  			</div>
			  
			   			<div class = "command_box">
			  				<div class = "command_title">
			  					Show controllers t1
			  				</div>
					  			<div class = "commands" id="con">
					  				{$this->actionfront->shcontrollers}
			
			
					  			</div>
			  			</div>
			  
			   			<div class = "command_box">
			  				<div class = "command_title">
			  					Show log
			  				</div>
					  			<div class = "commands" id="log">
					  				{$this->actionfront->shlog}
			
					  			</div>
			  			</div>
			  			
			   			<div class = "command_box">
			  				<div class = "command_title">
			  					Show clock
			  				</div>
					  			<div class = "commands" id="clock">
					  				{$this->actionfront->shclock}
			
					  			</div>
			  			</div>
			  
			   			<div class = "command_box">
			  				<div class = "command_title">
			  					Show ip route
			  				</div>
					  			<div class = "commands" id="rou">
					  				{$this->actionfront->shiproute}
			
					  			</div>
			  			</div>
			  
			  			<div class = "command_box">
			  				<div class = "command_title">
			  					Show run
			  				</div>
					  			<div class = "commands" id="run">
					  				{$this->actionfront->shrun}
					  			</div>
			  			</div>
			  
			  			<div class = "command_box">
			  				<div class = "command_title">
			  					Show policy-map int
			  				</div>
					  			<div class = "commands" id="map">
					  				{$this->actionfront->shpolicy}
					  			</div>
			  			</div>
			  
			   			<div class = "command_box">
			  				<div class = "command_title">
			  					Show access-list 100
			  				</div>
					  			<div class = "commands" id="acc">
					  				{$this->actionfront->shaccess100}
					  			</div>
			  			</div>
			  
			  			<div class = "command_box">
			  				<div class = "command_title">
			  					Show version
			  				</div>
					  			<div class = "commands" id="ver">
					  				{$this->actionfront->shver}
					  			</div>
			  			</div>
			  			
			  		</div>
		  			
		  		</div>
	  			
	  		</div>
		
  		</div>
  		
  		<div id = "footer">
  		
  		</div>
  	</div>
		
		
  </body>
		
		
</html>
HTML;
	}
	

}
?>