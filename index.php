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

if($ip){

	$tel;
	try{
		
		$actionfront->ip = $ip;
		$tel = $actionfront->connectToRouter($ip);
	
		$actionfront->shipintbri = $actionfront->executeCommand($tel,"sh ip int bri");
		$actionfront->sharp = $actionfront->executeCommand($tel,"sh arp");
		$actionfront->shint = $actionfront->executeCommand($tel,"sh int");
		$actionfront->shcontrollers = $actionfront->executeCommand($tel,"sh controllers serial 0");
		$actionfront->shlog = $actionfront->executeCommand($tel,"sh log");
		$actionfront->shclock = $actionfront->executeCommand($tel,"sh clock");
		$actionfront->shiproute = $actionfront->executeCommand($tel,"sh ip route");
		$actionfront->shrun = $actionfront->executeCommand($tel,"sh run");
		$actionfront->shpolicy = $actionfront->executeCommand($tel,"sh policy-map int");
		$actionfront->shaccess100 = $actionfront->executeCommand($tel,"sh access-list 100");
		$actionfront->shver = $actionfront->executeCommand($tel,"sh ver");
		
		$actionfront->getInterfaces($actionfront->shipintbri);
		$webfront->printPage();
		$tel->disconnect();

	}catch (Exception $e){

		$actionfront->tryerror = "Something's gone awry.." . $e->getMessage();
		if($tel)$tel->disconnect();
		$webfront->printPage();
	
	}

	
}else if($actionfront->iptraceip){
	
}else $webfront->printPage();




?>


