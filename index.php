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

require_once 'webfront.php';
require_once 'actionfront.php';

$actionfront = new Actionfront();
$webfront = new Webfront($actionfront);

$ip = $actionfront->getInput();

if($ip[0]){

	
	try{
		
		$tel = $actionfront->connectToRouter($ip[1]);
	
		//$actionfront->shipintbri = $actionfront->executeCommand($tel,"sh ip int bri");
		//$actionfront->sharp = executeCommand($tel,"sh arp");
		//$actionfront->shint = executeCommand($tel,"sh int");
		//$actionfront->shcontrollers = executeCommand($tel,"sh controllers serial 0");
		//$actionfront->shlog = $actionfront->executeCommand($tel,"sh log");
		//$actionfront->shiproute = executeCommand($tel,"sh ip route");
		//$actionfront->shrun = executeCommand($tel,"sh run");
		//$actionfront->shpolicy = executeCommand($tel,"sh policy-map int");
		//$actionfront->shaccess100 = executeCommand($tel,"sh access-list 100");
		//$actionfront->shver = executeCommand($tel,"sh ver");

	}catch (Exception $e){

		$actionfront->tryerror = "Something's gone awry.." . $e->getMessage();
	
	}
	
} else $actionfront->iperror = $ip[1];

$webfront->printPage();




?>


