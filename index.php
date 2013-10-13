<?php 

/*
 * Program: Lztech
 * Written by: e0s
 * Github src: https://github.com/eoswins/lztech
 * 
 * Web Application written in PHP that accesses routers via telnet and echoes commands out the web interface to be easily copied
 * Makes use of telnet.php written by Dalibor Andzakovic@https://github.com/ngharo/Random-PHP-Classes/blob/master/Telnet.class.php
 * And Extended by e0s to allow for multiple prompts
 */




$ip='';
$shipintbri = '';
$shint = '';
$shcontrollers = '';
$shlog = '';
$shiproute = '';
$shpolicy = '';
$shaccess100 = '';
$shver = '';

$tryerror = '';
$iperror = array("Not a valid IP..","We need a real IP here..","An IP, man, just an IP", "You're not having a very good day, are you?", "Who needs IPs anyway?", "IP goes there..I'm not telling you again, damnit", "Put an IP there or you're fired.", "It puts the IP in the form.", "You're doing this wrong.", "Did I ever tell you about that time I put an IP address in that form up there?");

if($_POST['submitted']==1){
	
	$pattern = "/^([0-9]{1,3}).([0-9]{1,3}).([0-9]{1,3}).([0-9]{1,3})$/";
	if(preg_match($pattern,$_POST['ip'],$matches)){
		if($matches[1] >=0 && $matches[1] <=255 && $matches[2] >=0 && $matches[2]<=255 && $matches[3]>=0 && $matches[3] <=255 && $matches[4]>=0 && $matches[4] <=255){
			
			$ip = $matches[0];
			
			require_once 'telnet.php';
			require_once 'functions.php';
			
			try{

				$tel = connectToRouter($ip);
				
				$shipintbri = executeCommand($tel,"sh ip int bri");
				$shint = executeCommand($tel,"sh int");
				$shcontrollers = executeCommand($tel,"sh controllers t1");
				$shlog = executeCommand($tel,"sh log");
				$shiproute = executeCommand($tel,"sh ip route");
				$shrun = executeCommand($tel,"sh run");
				$shpolicy = executeCommand($tel,"sh policy-map int");
				$shaccess100 = executeCommand($tel,"sh access-list 100");
				$shver = executeCommand($tel,"sh ver");
			}catch (Exception $e){
				
				$tryerror = "Something's gone awry.." . $e->getMessage();
				
			}
			
		}
	} else $reerrormsg = $iperror[rand(0,count($iperror)-1)];
}
?>
<?php 

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
		  	IP: <input type="text" name="ip" value="$ip">
		    <input type="submit" value="Submit">
		    <input type="hidden" name="submitted" value="1">
		    </form>

		    <br />
		    <span style="color:red">$reerrormsg</span>
		    <span style="color:red">$tryerror</span>
  		
  		
  		</div>
  		
  		<div id = "output">
  	
  			<div class = "command_box">  			
  				<div class = "command_title">  	
  					Show ip interface brief	
  				</div>
		  			<div class = "commands">
		  				$shipintbri
		  				
		  			</div>
  			</div>
  
  			<div class = "command_box">  			
  				<div class = "command_title">  	
  					Show interfaces
  				</div>
		  			<div class = "commands">
		  				$shint
		  			</div>
  			</div>
  			
   			<div class = "command_box">  			
  				<div class = "command_title">  	
  					Show controllers t1	
  				</div>
		  			<div class = "commands">
		  				$shcontrollers
		  			</div>
  			</div>
  			
   			<div class = "command_box">  			
  				<div class = "command_title">  	
  					Show log
  				</div>
		  			<div class = "commands">
		  				$shlog
		  			</div>
  			</div>
  			
   			<div class = "command_box">  			
  				<div class = "command_title">  	
  					Show ip route
  				</div>
		  			<div class = "commands">
		  				$shiproute
		  			</div>
  			</div>
  			
  			<div class = "command_box">  			
  				<div class = "command_title">  	
  					Show run	
  				</div>
		  			<div class = "commands">
		  				$shrun
		  			</div>
  			</div>
  			
  			<div class = "command_box">  			
  				<div class = "command_title">  	
  					Show policy-map int
  				</div>
		  			<div class = "commands">
		  				$shpolicy
		  			</div>
  			</div>
  			
   			<div class = "command_box">  			
  				<div class = "command_title">  	
  					Show access-list 100	
  				</div>
		  			<div class = "commands">
		  				$shaccess100
		  			</div>
  			</div>
  			
  			<div class = "command_box">  			
  				<div class = "command_title">  	
  					Show version
  				</div>
		  			<div class = "commands">
		  				$shver
		  			</div>
  			</div>
  	
  		</div>
  	</div>
  	

  
  </body>


</html>
HTML;
?>
   

