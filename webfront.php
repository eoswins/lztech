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
		
  </head>
		
  <body>
		
  	<div id = "main">
		
		
  		<div id = "header">
		
  			<div id = "header-content">
  				Lz Tech_(ALPHA)
  			</div>
		
  		</div>
		
  		<div id = "input">
		   	<form name="ip" method="POST" action="">
		  	IP: <input type="text" name="ip" value="{$this->actionfront->ip}">
		    <input type="submit" value="Submit">
		    <input type="hidden" name="submitted" value="1">
		    </form>
		
		    <br />
		    <span style="color:red">{$this->actionfront->tryerror}</span>
		    <span style="color:red">{$this->actionfront->iperror}</span>
		
		
  		</div>
		
  		<div id = "output">
  		
HTML;
	if(!empty($this->actionfront->interfaces)){
			
			echo "<div id = 'hud'>";
			echo "<div class = 'hud-box'>";
			echo "<div class = 'hud-title'>";
			echo "Interface Summary";
			echo "</div>";
  			echo "<div class = 'hud-table'>"; 
			$header = array("Interface", "Status");
			echo "<table>";
			echo "<tr>";
			echo "<th>Interface</th>";
			echo "<th>IP Address</th>";
			echo "<th>Status</th>";
			echo "</tr>";

			for($i=0;$i<$this->actionfront->intcount;$i++){
				if($i%2==0)$color = "#CCDEEB";
				else $color = "#E6EFF5";
				echo "<tr style='background-color:$color'>";
				if($this->actionfront->interfaces[$i][3] == 'up' && $this->actionfront->interfaces[$i][4] == 'up'){

					echo "<td>" . $this->actionfront->interfaces[$i][1] . "</td>" . "<td>" . $this->actionfront->interfaces[$i][2] . "</td>" . "<td><span style='color:green'>UP</span></td>";
				}else echo "<td>" . $this->actionfront->interfaces[$i][1] . "</td>" . "<td>" . $this->actionfront->interfaces[$i][2] . "</td>" . "<td><span style='color:red'>DOWN</span></td>";
				
				echo "</tr>";
				
			}
			echo "</table>";
			echo "</div>";
			echo "</div>";
			echo "<div class = 'hud-box'>";
			echo "<div class = 'hud-title'>";
			echo "Ping Test";
			echo "</div>";
			echo "<div class = 'hud-table'>";
			echo "<table>";
			echo "<tr>";
			echo "<th>Source Interface</th>";
			echo "<th>Destination IP</th>";
			echo "<th>Go</th>";
			echo "</tr>";
			echo "<tr>";
			echo "<td>";
			echo "<select>";
			for($i=0;$i<count($this->actionfront->workinginterfaces);$i++){
				echo "<option value='test'>" . $this->actionfront->workinginterfaces[$i][1] . "</option>";
			}
			echo "</td>";
			echo "<td><input type='text'></td>";
			echo "<td><input type='submit' value='ping'</td>";
			echo "</tr>";
			echo "</table>";
			echo "</div>";
			echo "</div>";
  			echo "</div>";
  			

  			

		}
	
	echo <<<HTML

  			
  			<div id="allcommands">
		
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
		
		
  </body>
		
		
</html>
HTML;
	}
	

}
?>