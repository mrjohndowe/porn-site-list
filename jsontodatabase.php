<?php


	require_once 'extra/header';
	$jsonFile = 'sites.json';
	$jsonFile = file_get_contents($jsonFile);
	$jsonFile = json_decode($jsonFile,true);
	//$json = sort($jsonFile);
	$countJson = count($jsonFile);
	
	$i = 0;
	foreach($jsonFile as $row){
		$i = $i+1;
		
		$row = $jsonFile[$i];
		$protocol = $row['protocol'];
		$path = $row['path'];
		$subdomain = $row['subdomain'];
		$domain = $row['domain'];
		$host = $row['host'];
		$tld = $row['tld'];
		$parent_domain = $row['parent_domain'];
		$subdomain = isset($subdomain)?$subdomain.'.':'';
		$href = $protocol.'://'.$subdomain.$host.'.'.$tld;
		$link = ' <a href="'.$href.'" target="_top">'.$host.'</a>';
		
		$q = "INSERT INTO sites(id, protocol, path, subdomain, domain, host, tld, parent_domain)VALUES(:id,:protocol,:path,:subdomain,:domain,:host,:tld,:parent_domain)";
		$p = [
			':id' => $i,
			':protocol' => $protocol,
			':subdomain' => $subdomain,
			':domain' => $domain,
			':host' => $host,
			':tld' => $tld,
			':parent_domain' => $parent_domain
		];
		$stmt = $db->prepare($q);
		$result = $stmt->execute($p);
		
		
		
		/* echo '<tr>
			<td>'. $i.'</td>
			<td>'. strtoupper($link) .'</td>
			<td>'. $domain .'</td>
			<td>'. $host .'</td>
			
			</tr>
		'; */
	}
	
?>