<?php
	
	
	
	function getFavs($user,$siteid){
		GLOBAL $db;
		$q = 'SELECT id FROM favorite_sites WHERE username = :user AND siteid = :siteid';
		$p = [':user' => $user,':siteid' => $siteid];
		$stmt = $db->prepare($q);
		$result = $stmt->execute($p);
		$fav = $stmt->fetch();
		$fav = !empty($fav['id']) ? true : false;
		return $fav;
	}
	
	function checkLogin($ip_address){
		
		$approvedIP = [
			'192.168.1.25',
			'192.168.1.55',
			'192.168.0.75',
			'192.168.1.142',
            '192.168.0.10'
		];
		$users = [
			'192.168.1.25' => 'MrJohnDowe',
			'192.168.1.70' => 'MrJohnDowe',
			'192.168.0.75' => 'MrJohnDowe',
			'192.168.0.10' => 'MrJohnDowe',
			$approvedIP[1] => "Sierra Braunns"
		];
		
		$loggedIN = IN_ARRAY($ip_address,$approvedIP) ? TRUE : FALSE;
		$username = $loggedIN == TRUE ? $users[$ip_address] : 'NO USERNAME FOUND';
		//$sessionName = session_name($username);
		if(session_name() !== $username){
			
			session_name($username);
			session_start([
				'cookie_lifetime' => 86400 / 4,
			]);
		}
		$_SESSION['username'] = $username;
		
		
		return $loggedIN;		
	}
	
	function getSiteInfo($id){
		GLOBAL $db;
		
		$q = "SELECT * FROM sites WHERE id = $id";
		$stmt = $db->prepare($q);
		$result = $stmt->execute();
		$row = $stmt->fetchAll();
		
		return $row;
	}
    
    function getIPaddress($ip_address){
        GLOBAL $db;
        
        $q = "SELECT * FROM users WHERE ip_address = $ip_address AND admin = 1";
        $stmt = $db->prepare($q);
        $result = $stmt->execute();
        $row = $stmt->fetchAll();
        
        return $row;
    }

?>