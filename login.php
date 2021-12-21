<?php
	require_once 'extra/includes/liteConnect.php';
	require_once 'extra/function.php';
	$id = isset($_GET['id'])?$_GET['id']:null;
	if(!intval($id))exit;
	$pathLink = isset($_GET['p']) ? TRUE : FALSE;
	
	echo 'IS ID: '. $id .' A PATH LINK :'. $pathLink;
	
	echo '<hr>';
	$rows = getSiteInfo($id);
	foreach($rows as $row){
		$protocol = !empty($row['protocol']) ? $row['protocol'].'://' : 'http://';
		$sub = !empty($row['subdomain']) ? $row['subdomain'] : '';
		$host = !empty($row['host']) ? $row['host'] : '';
		$tld = !empty($row['tld']) ? $row['tld'] : '';
		$href = $protocol.$sub.$host.'.'.$tld.'/';
		$path = $pathLink == TRUE && $row['path'] != "null" ? $row['path'] : '';
		$header = $href.$path;
		
<<<<<<< HEAD
<<<<<<< HEAD
		 $approvedDevices = [
            '192.168.1.70', 
			'192.168.1.25', 
			'192.168.1.50'
        ];
		$client = $_SERVER['REMOTE_ADDR'];
		
		if(in_array($client,$approvedDevices)){
			header("Location: $header");
			exit;
		}
		
		
=======
>>>>>>> master
=======
>>>>>>> master
		$clickCount = $row['click_count'];
		$q = "UPDATE sites SET click_count = :click WHERE id = :id";
		$p = [':id' => $id, ':click' => $clickCount + 1];
		$stmt = $db->prepare($q);
		$result = $stmt->execute($p);
		
		header("Location: $header");
		exit;
		
		
	}
	
?>