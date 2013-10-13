<?php

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
?>