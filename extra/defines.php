<?php
	
		$settings = [
		'username' => $_SESSION['username'],
		// 'session_name' => $_SESSION['name'],
		//'PAGE_TITLE' => 'Big List of Porn Sites',
	
	];
	
	FOREACH($settings as $name => $val){
		define(strtoupper($name),$val);
	}
	
	
	

?>