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
		
  			<div class = "command_box">
  				<div class = "command_title">
  					Show ip interface brief
  				</div>
		  			<div class = "commands" id = "bri" data-clipboard-text="Copy Me!">
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
		
		
  </body>
		
		
</html>
HTML;
	}
	

}
?>