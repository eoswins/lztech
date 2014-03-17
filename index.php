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

require 'webfront.php';
require 'actionfront.php';

$actionfront = new Actionfront();
$webfront = new Webfront($actionfront);

$cronjobpath = "/home/e0s/krons";//this folder must be created beforehand and given read/write access to user www-data

$ip = $actionfront->getConnectInput();
$iptrace = $actionfront->getIPTraceInput();
$bwmonip = $actionfront->getBwMonInput();
$bwmonexecute = $actionfront->getBwMonExecute();

$rowcolor1 = "#CCDEEB";
$rowcolor2 = "#E6EFF5";



if($ip[0]){
	

	$tel;
	try{
		
		$tel = $actionfront->connectToRouter($ip[1]);
		$bri = str_replace("\x08"," ",$actionfront->executeCommand($tel,"sh ip int bri"));
		$arp = str_replace("\x08"," ",$actionfront->executeCommand($tel,"sh arp"));
		$int = str_replace("\x08"," ",$actionfront->executeCommand($tel,"sh int"));
		$con = str_replace("\x08"," ",$actionfront->executeCommand($tel,"sh controllers t1"));
		$log = str_replace("\x08"," ",$actionfront->executeCommand($tel,"sh log"));
		$clo = str_replace("\x08"," ",$actionfront->executeCommand($tel,"sh clock"));
		$rou = str_replace("\x08"," ",$actionfront->executeCommand($tel,"sh ip route"));
		$run = str_replace("\x08"," ",$actionfront->executeCommand($tel,"sh run"));
		$qos = str_replace("\x08"," ",$actionfront->executeCommand($tel,"sh policy-map int"));
		$acc = str_replace("\x08"," ",$actionfront->executeCommand($tel,"sh access-list 100"));
		$ver = str_replace("\x08"," ",$actionfront->executeCommand($tel,"sh ver"));
		$tel->disconnect();
		
		$actionfront->getInterfaces($bri);
		
		//--------------------------------------------------------------------------------------------------------------
		$officesuite = $actionfront->officesuiteCheck($run)==1 ? 'Yes' : 'No';
		$pri = $actionfront->priCheck($run)==1 ? 'Yes' : 'No';
		$failover = $actionfront->failoverCheck($run) ? 'Yes' : 'No';
		$fxs = $actionfront->fxsHandoffCheck($run) ? 'Yes' : 'No';
		
		$featuretable = '';
		$featuretable .= "<table>
								<tr>
									<th>Feature Check</th>
								</tr>
								<tr style='background-color:$rowcolor1'>
									<td>
										Officesuite
									</td>
									<td>
										$officesuite
									</td>
								</tr>
								<tr style='background-color:$rowcolor2'>
									<td>
										FXS Handoff
									</td>
									<td>
										$fxs
									</td>
								</tr>
								<tr style='background-color:$rowcolor1'>
									<td>
										SIP PRI
									</td>
									<td>
										$pri
									</td>
								</tr>
								<tr style='background-color:$rowcolor2'>
									<td>
										Failover
									</td>
									<td>
										$failover
									</td>
								</tr>
							</table>";
								
		
		
		
		
		
		//--------------------------------------------------------------------------------------------------------------
		$intsum='';
		if(!empty($actionfront->interfacelines)){
			$intsum .= "<table>
								<tr>
									<th>Interface</th>
									<th>IP Address</th>
									<th>Status</th>
								</tr>";
		
			for($i=0;$i<$actionfront->intcount;$i++){
				if($i%2==0)$color = $rowcolor1;
				else $color = $rowcolor2;
				$intsum .= "<tr style='background-color:$color'>";
				if($actionfront->interfacelines[$i][3] == 'up' && $actionfront->interfacelines[$i][4] == 'up'){
		
					$intsum .= "<td>" . $actionfront->interfacelines[$i][1] . "</td>" . "<td>" . $actionfront->interfacelines[$i][2] . "</td>" . "<td><span style='color:green'>UP</span></td>";
				}else $intsum .= "<td>" . $actionfront->interfacelines[$i][1] . "</td>" . "<td>" . $actionfront->interfacelines[$i][2] . "</td>" . "<td><span style='color:red'>DOWN</span></td>";
		
				$intsum .= "</tr>";
		
				}
				$intsum .= "</table>";
			}
			//--------------------------------------------------------------------------------------------------------------
			$workingint = '<div class = "hud-table">
							<table>
								<tr>
									<th>Source Interface</th>
									<th>Action</th>
									<th>Destination IP</th>
								</tr>
								<tr>
									<td>';
			if(!empty($actionfront->interfacelines)){
				$workingint .= "<select id='interfaces'>";
				for($i=0;$i<count($actionfront->workinginterfaces);$i++){
					$workingint .= "<option value='". $actionfront->workinginterfaces[$i][1]."'. name='pt'>" . $actionfront->workinginterfaces[$i][1] . "</option>";
				}
				$workingint .= '</select></td>
									<td>
										<input type="radio" name="action" id="ping" value="ping" checked>Ping<br>
										<input type="radio" name="action" id="traceroute" value="trace">Traceroute
										
									</td>
									<td><input type="text" id="iptrace" value=""></td>
									<td><input type="submit" value="Go" onclick="pingtrace()"></td>
									<td height="60" width="60" id="loading2"></td>
									<td id="pterrorz"></td>
								</tr>
							</table>
						</div>
						<div id = "pingtraceio">
						
							
						</div>';
			}
			//--------------------------------------------------------------------------------------------------------------
			$tablecheck = '';
			$arpcheck = '';
			$uptime = '';
			
			$arpcheck = $actionfront->arpCheck($arp,$actionfront->ethernetinterfacelist) ? "Yes" : "No";
			
			$uptime = $actionfront->rebooted($ver);
			
			if($actionfront->controllerErrors($con)=="?"){
				$connerrors = "No controllers found";
			}else $connerrors = $actionfront->controllerErrors($con) ? "Yes" : "No";
			
			$actionfront->logActivity($log, $clo);
			
			$tablecheck .= 
			"<table>
				<tr>
					<th>Status Check</th>
				</tr>
				<tr style='background-color:$rowcolor1'>
					<td>Ethernet Device Connectivity</td>
					<td>$arpcheck</td>
				</tr>
				<tr style='background-color:$rowcolor2'>
					<td>Recent Log Activity</td>
					<td>Yes</td>
				</tr>
				<tr style='background-color:$rowcolor1'>
					<td>Controller Errors</td>
					<td>$connerrors</td>
				</tr>
				<tr style='background-color:$rowcolor2'>
					<td>Last Router Reboot</td>
					<td>$uptime</td>
				</tr>
			</table>";
		
		header( "content-type: application/xml; charset=UTF-8" );
		$xml = new DOMDocument("1.0","UTF-8");
		$xml_commandsummary = $xml->createElement("commandsummary");
		$xml_bri = $xml->createElement("shbri",$bri);
		$xml_arp = $xml->createElement("sharp",$arp);
		$xml_int = $xml->createElement("shint",$int);
		$xml_con = $xml->createElement("shcon",$con);
		$xml_log = $xml->createElement("shlog",$log);
		$xml_clo = $xml->createElement("shclo",$clo);
		$xml_rou = $xml->createElement("shrou",$rou);
		$xml_run = $xml->createElement("shrun",$run);
		$xml_qos = $xml->createElement("shqos",$qos);
		$xml_acc = $xml->createElement("sh100",$acc);
		$xml_ver = $xml->createElement("shver",$ver);
		
		$xml_featurecheck = $xml->createElement("featurecheck",$featuretable);
		$xml_intsum = $xml->createElement("intsum",$intsum);
		$xml_workint = $xml->createElement("workint",$workingint);
		$xml_tablecheck = $xml->createElement("tablecheck",$tablecheck);
		
		$xml_commandsummary->appendChild($xml_bri);
		$xml_commandsummary->appendChild($xml_arp);
		$xml_commandsummary->appendChild($xml_int);
		$xml_commandsummary->appendChild($xml_con);
		$xml_commandsummary->appendChild($xml_log);
		$xml_commandsummary->appendChild($xml_clo);
		$xml_commandsummary->appendChild($xml_rou);
		$xml_commandsummary->appendChild($xml_run);
		$xml_commandsummary->appendChild($xml_qos);
		$xml_commandsummary->appendChild($xml_acc);
		$xml_commandsummary->appendChild($xml_ver);
		
		$xml_commandsummary->appendChild($xml_featurecheck);
		$xml_commandsummary->appendChild($xml_intsum);
		$xml_commandsummary->appendChild($xml_workint);
		$xml_commandsummary->appendChild($xml_tablecheck);
		
		$xml->appendChild($xml_commandsummary);
		print $xml->saveXML();

		



	}catch (Exception $e){
		
		if($tel)$tel->disconnect();
		
		header( "content-type: application/xml; charset=UTF-8" );
		$xml = new DOMDocument("1.0","UTF-8");
		$xml_connect_error = $xml->createElement("connect_error");
		$xml_connect_error->setAttribute("error", "Something bad happened..." . $e->getMessage());
		
		$xml->appendChild($xml_connect_error);
		
		print $xml->saveXML();

	
	}

	
}elseif($ip[1]==1){//range
	
	header( "content-type: application/xml; charset=UTF-8" );
	$xml = new DOMDocument("1.0","UTF-8");
	$xml_range_error = $xml->createElement("range_error");
	$xml_range_error->setAttribute("error", "IP out of range");
	
	$xml->appendChild($xml_range_error);
	
	print $xml->saveXML();
	
	
}elseif($ip[1]==2){//not an ip
	
	header( "content-type: application/xml; charset=UTF-8" );
	$xml = new DOMDocument("1.0","UTF-8");
	$xml_invalid_error = $xml->createElement("invalid_ip_error");
	$xml_invalid_error->setAttribute("error", "Not a valid IP");
	
	$xml->appendChild($xml_invalid_error);
	
	print $xml->saveXML();
	
}elseif($iptrace[0][0] && $iptrace[1][0] && $iptrace[2]!=2 && $iptrace[3]!=3){
	
		$tel = '';
		try{
			$tel = $actionfront->connectToRouter($iptrace[1][1]);
			$ptresult = str_replace("\x08"," ",$actionfront->executeCommand($tel, $iptrace[2] . ' ' . $iptrace[0][1])); //. " so " . $iptrace[3])));
			header( "content-type: application/xml; charset=UTF-8" );
			$xml = new DOMDocument("1.0","UTF-8");
			$xml_ptresult = $xml->createElement("ptresult",$ptresult);
			$xml->appendChild($xml_ptresult);
			print $xml->saveXML();
			$tel->disconnect();
		}catch(Exception $e){
			$tel->disconnect();
			header( "content-type: application/xml; charset=UTF-8" );
			$xml = new DOMDocument("1.0","UTF-8");
			$xml_ptresult= $xml->createElement("pterror","Something went wrong..." . $e->getMessage());
			$xml->appendChild($xml_pterror);
			print $xml->saveXML();
			
		
		
	}

}elseif($iptrace[0][1]==1 || $iptrace[1][1]==1){
	header( "content-type: application/xml; charset=UTF-8" );
	$xml = new DOMDocument("1.0","UTF-8");
	$xml_range_error = $xml->createElement("range_error");
	$xml_range_error->setAttribute("error", "<span style='color:red'>IP out of range</span>");
	
	$xml->appendChild($xml_range_error);
	
	print $xml->saveXML();

}elseif($iptrace[0][1]==2 || $iptrace[1][1]==2){
	header( "content-type: application/xml; charset=UTF-8" );
	$xml = new DOMDocument("1.0","UTF-8");
	$xml_invalid_error = $xml->createElement("invalid_ip_error");
	$xml_invalid_error->setAttribute("error", "<span style='color:red'>Not a valid IP</span>");
	
	$xml->appendChild($xml_invalid_error);
	
	print $xml->saveXML();

}elseif($iptrace[2]==2){
	header( "content-type: application/xml; charset=UTF-8" );
	$xml = new DOMDocument("1.0","UTF-8");
	$xml_action_error = $xml->createElement("invalid_action_error");
	$xml_action_error->setAttribute("error", "<span style='color:red'>Not a valid action</span>");
	
	$xml->appendChild($xml_action_error);
	
	print $xml->saveXML();
	
}elseif($iptrace[3]==3){
	header( "content-type: application/xml; charset=UTF-8" );
	$xml = new DOMDocument("1.0","UTF-8");
	$xml_int_error = $xml->createElement("invalid_int_error");
	$xml_int_error->setAttribute("error", "<span style='color:red'>Invalid source interface</span>");
	
	$xml->appendChild($xml_int_error);
	
	print $xml->saveXML();
	

}elseif($bwmonip[0]){
	
	
	try{
	
		$tel = $actionfront->connectToRouter($bwmonip[1]);
		$bri = str_replace("\x08"," ",$actionfront->executeCommand($tel,"sh ip int bri"));
		$actionfront->getInterfaces($bri);
		$interfacelist = $actionfront->interfacelist;
		
		
		$bwmonip = $bwmonip[1];		
		$step2 = '<h4>Choose an interface to monitor. Choose an interval to pull bandwidth statistics. Choose the amount of time to keep the monitor alive.</h4>';
		
		$step2.="<table id = 'bwmon-table'>
	  				<tr>
	  					<td>IP:</td>
						<td style='padding-right:20px'>$bwmonip</td>
						<td>Interface:</td>
						<td style='padding-right:20px'><select id = 'bwmonint'>";
		foreach($actionfront->interfacelist as $interface){
			$step2.= "<option value = $interface>$interface</option>";
		}
		$step2.= 		"</td>
						<td>Interval:</td>
						<td style='padding-right:20px'><select id = 'bwmoninterval'><option = value='5'>5m</option><option value = '10>10m</option><option value = '15>15m</option><option value = '20'>20m</option></select></td>
						<td>Life Cycle:</td>
						<td style='padding-right:20px'><select id = 'bwmonlifecycle'><option value = '4'>4h</option><option value = '8'>8h</option><option value = '24'>24h</option>></select></td>
						<td>Name: <input type='text' id ='cronname'></td>
						<td><input type='submit' value='Go' onclick='bwmonExecute()'></td>
						<td height='60' id='bwmonloading2'></td>
						
					</tr>
				</table>";
		header( "content-type: application/xml; charset=UTF-8" );
		$xml = new DOMDocument("1.0","UTF-8");
		$xml_step2 = $xml->createElement("step2");
		$xml_step2->setAttribute("step2", $step2);
		$xml_step2->setAttribute("bwmonip", $bwmonip);
		
		$xml->appendChild($xml_step2);
		
		print $xml->saveXML();
		
	
	}catch (Exception $e){
		
		if($tel)$tel->disconnect();
		
		header( "content-type: application/xml; charset=UTF-8" );
		$xml = new DOMDocument("1.0","UTF-8");
		$xml_connect_error = $xml->createElement("connect_error");
		$xml_connect_error->setAttribute("error", "Something bad happened..." . $e->getMessage());
		
		$xml->appendChild($xml_connect_error);
		
		print $xml->saveXML();
		
		
	}
	
	
	
}

elseif($bwmonexecute[0][0] && $bwmonexecute[1]!='2z' && $bwmonexecute[2]!='3z'&& $bwmonexecute[3]!='4z' && $bwmonexecute[4]!='5z'){
	

	if(file_exists($cronjobpath)){
	
		try{
	
			echo shell_exec("cd $cronjobpath;crontab -l > a.out 2>&1");
			$f = fopen($cronjobpath . '/a.out','a');
			fputs($f,"#" . $bwmonexecute[4] . PHP_EOL);

			fclose($f);
	
			echo shell_exec("cd $cronjobpath;crontab a.out 2>&1");
	
		}catch(Exception $e){
			echo $e->getMessage();
		}
	
	}else echo "file /home/e0s/krons does not exist";
	
	header( "content-type: application/xml; charset=UTF-8" );
	$xml = new DOMDocument("1.0","UTF-8");
	$xml_croncomplete = $xml->createElement("croncomplete");
	$xml_croncomplete->setAttribute("croncomplete", "Added Monitor Successfully");
	$xml->appendChild($xml_croncomplete);
	
	print $xml->saveXML();
	
}
//need to add error conditions for bwmonexecute as well as think of a way to refactor out some of this code and make it cleaner and easier to read.
else{
	$webfront->printPage();

}




?>


