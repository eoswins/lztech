<?php

require 'telnet.php';

class Actionfront{
	
	
	
	
	public $interfacelines = '';
	public $interfacelist = array();
	public $ethernetinterfacelist = array();
	public $workinginterfaces = array();
	public $intcount = 0;
	
	public $tryerror = '';
	public $iperror = '';
	private $errors = array("Not a valid IP..","We need a real IP here..","An IP, man, just an IP", "You're not having a very good day, are you?", "Who needs IPs anyway?", "IP goes there..I'm not telling you again, damnit", "Put an IP there or you're fired.", "It puts the IP in the form.", "You're doing this wrong.", "Did I ever tell you about that time I put an IP address in that form up there?");
	private $ippattern = "/^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$/";
	
	/**
	 * 
	 * @param String $ip is the WAN IP we telnet to
	 * 
	 * @return array boolean string IP or int error
	 */
	function confirmIP($ip){
	
		if(preg_match($this->ippattern,$ip,$matches)){
			if($matches[1] >=0 && $matches[1] <=255 && $matches[2] >=0 && $matches[2]<=255 && $matches[3] >=0 && $matches[3] <=255 && $matches[4]>=0 && $matches[4] <=255){
				return array(True,$ip);
			} else return array(False, 1);//Out of range
		}
		return array(False,2);//Not an IP
	
	}
	
	function getConnectInput(){
		if(isset($_POST['ip'])){
			
			return $this->confirmIP($_POST['ip']);
			
		}else return array(False);	
		
	}
	
	/**
	 * Collects and cleanses input for ping/traceroute test
	 * POST data used: Destination IP, Source IP, Action:Ping or Trace, and an interface
	 * Then returns these items in an array if data is clean, or returns array false if something isn't set
	 * 
	 * @return Array(Boolean,String IP or error 1,Boolean,String IP or error 2, String action, String interface
	 */
	function getIPTraceInput(){
		
		$payload = array();
		if(isset($_POST['iptrace']) && isset($_POST['wanip']) && isset($_POST['pt']) && isset($_POST['interface'])){
			
			$payload[] = $this->confirmIP($_POST['iptrace']);
			$payload[] = $this->confirmIP($_POST['wanip']);
			if($_POST['pt']=='ping' || $_POST['pt']=='traceroute'){
				$payload[] = $_POST['pt'];
			}else $payload[] = 2;
			if(preg_match('/^[a-zA-Z0-9\/\:]+$/',$_POST['interface'])){
				$payload[] = $_POST['interface'];
			}else $payload[] = 3;
			
			return $payload;
			
		} else return array(False);
		

	}
	
	function getBwMonInput(){
		
		if(isset($_POST['bwmonip'])){
			
			return $this->confirmIP($_POST['bwmonip']);
			
		}else return array(False);
		
		
	}
	
	function getBwMonExecute(){
		$payload = array();
		if(isset($_POST['bwmonipexe']) && isset($_POST['int']) && isset($_POST['interval']) && isset($_POST['lifecycle']) && isset($_POST['cronname'])) {
			$payload[] = $this->confirmIP($_POST['bwmonipexe']);
			if(preg_match('/^[a-zA-Z0-9\/\:]+$/',$_POST['int'])){
				$payload[] = $_POST['int'];
			}else $payload[] = '2z';
			if(preg_match('/\d{1,2}/',$_POST['interval'])){
				$payload[] = $_POST['interval'];
			}else $payload[] = '3z';
			if(preg_match('/\d{1,2}/',$_POST['lifecycle'])){
				$payload[] = $POST['lifecycle'];
			}else $payload[] = '4z';
			if(preg_match('/[a-zA-Z0-9]{1,30}/',$_POST['cronname'])){
				$payload[] = strtolower($_POST['cronname']);
			}
			else $payload[] = '5z';
			
			return $payload;
			
		}else return array(False);
	}
	
	
	/**
	 * Creates a Telnet object and attempts to log into the router
	 * You will be toggling this command a lot to ensure you
	 * can log into all types of routers with different prompts
	 *
	 * @param string $host is the IP address
	 * @param int $port is the telnet port
	 * @param int $timeout is the connection timeout in seconds
	 * @param array $prompt are the prompts you want to halt reading at
	 * @param string $login is the router login name
	 * @param string $password is the router password
	 * @return Telnet object
	 */
	function connectToRouter($host = "127.0.0.1", $port = 23, $timeout = 1000,$prompt = array('Password:'),$login= "33333", $password = "33333"){
	
		$tel = new Telnet($host, $port, $timeout, $prompt);
		$tel->setPrompt(array(">"));
		$tel->exec($password);
		$tel->setPrompt(array("Password:"));
		$tel->exec("en");
		$tel->setPrompt(array("#"));
		$tel->exec($password);
		$tel->setPrompt(array("--More--","#"));
	
		return $tel;
	
	}
	
	/**
	 *
	 * @param $tel is the Telnet object
	 * @param string $c command to execute
	 * @return string response from server
	 */
	function executeCommand($tel,$c){
	
		$r = $tel->exec($c);
	
		$mor = "--More--";
	
		if($r[1][1]==$mor){
	
			$stillmore;
	
			do{
				$stillmore = $tel->exec(chr(13));
				$r[0] .= '<br />' . $stillmore[0];
			}while($stillmore[1][1] == $mor);

			return $r[0];
	
		}
		return $r[0];
	}
	
	function getInterfaces($s){
		
		//$s = explode('<br />',$s);
		//$s = implode($s);
		
		$pattern = "/<br \/>([\w\d\/\:]+)\s+((?:\d{1,3}\.){1,3}\d{1,3}|unassigned)\s+[\w]+\s+[\w]+\s+(administratively [\w]+|[\w]+)\s+([\w]+)/";
		
		if(preg_match_all($pattern,$s,$m)){
			$organized_array = array();
			
			for($i=0;$i<count($m[0]);$i++){
				$addit = array();
				for($j=0;$j<count($m);$j++){
			
					$addit[] = $m[$j][$i];

				}
				$organized_array[] = $addit;
			
			
			}

			for($i=0;$i<count($m[1]);$i++){
				$this->interfacelist[] = $m[1][$i];
			}
			foreach($this->interfacelist as $interface){
				if(stripos($interface,'ethernet')!== False) $this->ethernetinterfacelist[] = $interface;
			}
			
			$this->interfacelines = $organized_array;
			$this->intcount = count($organized_array);
			$this->getWorkingInterfaces($organized_array);
			
		}else{
			//echo "<span style='color:red'>Regular Expression failed to match</span>";
			$this->interfaces = 0;
			//add another property..non-working interfaces
		}
	
	}
	
	function getWorkingInterfaces($s){
		
		for($i=0; $i<count($s);$i++){
			
			if($s[$i][3] == 'up' && $s[$i][4] == 'up'){
				$this->workinginterfaces[] = $s[$i];
			}
		}
		
	}
	
	
	function arpCheck($a,$ethlist){//gathers an array of devices on network(arp output), traverses them and when a match is found for a gateway it drops it from the ethernet list, if the list if empty we know that ethernet devices exist on each interface
		
		if(empty($ethlist))return false;
		$arpdata = $a;
		$pattern = '/(?:\d{1,3}\.){3}\d{1,3}\s+\d+\s+(?:[a-f0-9]{4}\.){2}[a-f0-9]{4}\s+.+?\s+([a-zA-Z0-9\/\:]+)/'; 
		preg_match_all($pattern, $arpdata,$m);
		
		for($i=0;$i<count($m[1]);$i++){
			for($j=0;$j<count($ethlist);$j++){
				if($m[1][$i]==$ethlist[$j]){//m[1][$i] is an interface name , ie: Ethernet0, ethlist is the list of ethernet interfaces
					unset($ethlist[$j]);
					if(empty($ethlist)) return true;
				}
			}
		}
		return false;
		
		
	}
	
	function officesuiteCheck($config){
		
		$pattern = '/id:ipphone.mitel.com|ip helper-address 10.16/';
		return preg_match($pattern,$config);
		
	}
	
	function failoverCheck($config){
		
		$pattern = '/ip route 64.115.237.192 255.255.255.224 [a-zA-Z0-9\/\:]+ track/';
		return preg_match($pattern, $config);
	}
	
	function priCheck($config){
		
		$pattern = '/pri-group timeslots/';
		return preg_match($pattern,$config);
	}
	
	function fxsHandoffCheck($config){
		
		$pattern = '/mgcp call-agent/';
		return preg_match($pattern,$config);
	}
	
	function getEthernetInterfaces($l){
		
		$etherlist = array();
		foreach($l as $i){
			if(strpos($i,"ethernet") != false){
				$etherlist[] = $i;
			}
		}
		return $etherlist;
	}
	
	function logActivity($log,$clock){
		
		$days=0;
		$difference = 5;
		//linked list this$m[] = array(array('Jan',31),array('Feb',28),array('Mar',31),array('Apr',30),array('May',31),array('Jun',30),array('Jul',31),array('Aug',31),array('Sep',30),array('Oct',31),array('Nov',30),array('Dec',31));
		$logpattern = '/\*([a-zA-z]{3}) (\d{1,2})/';
		$clockpattern = '/([a-zA-z]{3}) (\d{1,2}) \d{4}/';
		
		preg_match_all($logpattern,$log,$m);
		preg_match_all($clockpattern,$clock,$o);
		
		$lastlog[] = $m[1][count($m[1])-1];
		$lastlog[] = $m[2][count($m[2])-1];
		
		$clocklog[] = $o[1][0];
		$clocklog[] = $o[2][0];
		
		
		if($lastlog[0]==$clocklog[0]){
			$days=$clocklog[1]-$lastlog[1];
			if($days <=$difference) return true;
			else return false;
		}else{
			
		}
		
		
	}
	
	function controllerErrors($con){
		
		$pattern = "/Total Data \(last 24 hours\)<br \/>\s+(\d+) Line Code Violations, (\d+) Path Code Violations,<br \/>\s+(\d+) Slip Secs, (\d+) Fr Loss Secs, (\d+) Line Err Secs, (\d+) Degraded Mins,<br \/>\s+(\d+) Errored Secs, (\d+) Bursty Err Secs, (\d+) Severely Err Secs, (\d+) Unavail Secs/";
		preg_match_all($pattern,$con,$m);
		
		if($m){
			for($i=1;$i<count($m)-1;$i++){
				for($j=0;$j<count($m[$i]);$j++){
					if($m[$i][$j]!=0){
						return true;
					}
				}
			}return false;
		}return "?";
	}
	
	function rebooted($ver){
		
		$uptimepattern = "/uptime is (.+?)<br \/>/";
		preg_match($uptimepattern,$ver,$m);
		
		if($m)return $m[1];
		else return "n/a";
		
		
	}


	
	
}

?>