<?php

	function getFavs($ip,$siteid){
		GLOBAL $db;
		$q = 'SELECT count(id) FROM users WHERE ip_address = :ip AND siteid = :siteid';
		$p = [':ip' => $ip,':siteid' => $siteid];
		$stmt = $db->prepare($q);
		$result = $stmt->execute($p);
		$fav = $stmt->fetch()['count(id)'];
		$fav = !empty($fav) ? true : false;
		return $fav;
	}
	
	function getSiteInfo($id){
		GLOBAL $db;
		
		$q = "SELECT * FROM sites WHERE id = $id";
		$stmt = $db->prepare($q);
		$result = $stmt->execute();
		$row = $stmt->fetch();
		
		return $row;
	}

?>