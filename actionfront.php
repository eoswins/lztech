<?php

require_once 'telnet.php';

class Actionfront{
	
	public $ip = '';
	public $sharp = '';
	public $shipintbri = '';
	public $shint = '';
	public $shcontrollers = '';
	public $shlog = '';
	public $shiproute = '';
	public $shpolicy = '';
	public $shaccess100 = '';
	public $shver = '';
	public $shrun = '';
	
	public $tryerror = '';
	public $iperror = '';
	private $errors = array("Not a valid IP..","We need a real IP here..","An IP, man, just an IP", "You're not having a very good day, are you?", "Who needs IPs anyway?", "IP goes there..I'm not telling you again, damnit", "Put an IP there or you're fired.", "It puts the IP in the form.", "You're doing this wrong.", "Did I ever tell you about that time I put an IP address in that form up there?");
	
	
	
	
	
	function getInput(){
		if(isset($_POST['submitted']) && $_POST['submitted']==1){
	
			$pattern = "/^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$/";
			if(preg_match($pattern,$_POST['ip'],$matches)){
				if($matches[1] >=0 && $matches[1] <=255 && $matches[2] >=0 && $matches[2]<=255 && $matches[3] >=0 && $matches[3] <=255 && $matches[4]>=0 && $matches[4] <=255){
	
					$ip = $matches[0];
					return array(True,$ip);
	
	
				} else{
					$this->iperror = "That is waaaaay out of range.";
					return False;
				}
			} else{
				$this->iperror = $this->errors[rand(0,count($this->errors)-1)];
				return False;
			}
			
		}
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
	function connectToRouter($host = "127.0.0.1", $port = 23, $timeout = 10,$prompt = array('Password:'),$login= "33333", $password = "33333"){
	
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
				$stillmore = $tel->exec(chr(32));
				$r[0] .= $stillmore[0];
			}while($stillmore[1][1] == $mor);
	
			return $r[0];
	
		}
		return $r[0];
	}
	
	
}

?>