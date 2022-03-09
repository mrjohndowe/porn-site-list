<?php
	
	echo '<link src="../.global/assets/css/sb-admin-2.css" rel="stylesheet"/>';
	echo '<link src="../.global/assets/css/sb-admin-2.min.css" rel="stylesheet"/>';
	require_once 'extra/header';
	$id = isset($_GET['id']) ? $_GET['id'] : '';
	$id = intval($id);
	$page = isset($_GET['p']) ? $_GET['p'] : 1;
	$self = $_SERVER['PHP_SELF'];
	$rows = getSiteInfo($id);
	
	//$domain = !empty($row['domain']) ? $row['domain']: "";
	foreach($rows as $row):
		$domain = $row['domain'];
		$path = $row['path'];
		$displayName = $row['display_name'];
		$subdomain = $row['subdomain'];
		$host = $row['host'];
		$tld = $row['tld'];
		$protocol = $row['protocol'];
	endforeach;
	
	
	
	if(!empty($_POST)){
		$domainEdit = !isset($_POST['domain']) ? urlencode($_POST['domain']) : $domain;
		$domainEdit = stripslashes($domainEdit);
		$pathEdit = !empty($_POST['path']) ? $_POST['path'] : $path;
		$displayNameEdit = !empty($_POST['displayName']) ? $_POST['displayName'] : $displayName;
		$protocolEdit = !empty($_POST['protocol']) ? $_POST['protocol'] : $protocol;
		$subdomainEdit = !empty($_POST['subdomain']) ? $_POST['subdomain'] : $subdomain;
		$hostEdit = !empty($_POST['host']) ? $_POST['host'] : $host;
		$tldEdit = !empty($_POST['tld']) ? $_POST['tld'] : $tld;
		$activeEdit = $_POST['active'];
		
		/* UPDATE "sites" SET "id"='620', "protocol"='https', "domain"='teen-homemade.com', "path"=NULL, "subdomain"=NULL, "host"='teen-homemade', "tld"='com', "parent_domain"=NULL, "active"='1', "timestamp"='2021-11-24 07:26:33' WHERE "rowid" = 620 */
		
		$q = "UPDATE sites SET id = :id, protocol = :protocol, domain = :domain, path = :path, subdomain = :sub, host = :host, tld = :tld, active = :active, display_name = :display_name WHERE rowid = :id";
		$p = [
			':id' => $id,
			':domain' => $domainEdit,
			':protocol' => $protocolEdit,
			':path' => $pathEdit,
			':sub' => $subdomainEdit,
			':host' => $hostEdit,
			':tld' => $tldEdit,
			':active' => $activeEdit,
			':display_name' => $displayNameEdit
		];
		$stmt = $db->prepare($q);
		$result = $stmt->execute($p);
		
		$location = $_SERVER['HTTP_REFERER'];
		
		///header("Location: $location");
		//header("Location: index.php");
		//header("Location: $self?id=$id");
		header("Location: index.php?page_no=$page");
		exit;
	}else{
		
	
	switch($row['active']){
		case 1:
			$activeYes = 'checked';
			$activeNo = '';
			break;
		case 0:
			$activeYes = '';
			$activeNo = 'checked';
			break;
	}
	

?>
	<div style="width:500px;" class="container container-pad">
		<h1>Edit Database</h1>
		<form class="alert alert-info" method="post" action="<?=$self.'?id='.$id.'&p='.$page?>">
			
			<label for="displayName">Edit Display Name  - <?=$displayName?></label>
			<input value="<?=$displayName?>" type="text" name="displayName" class="form-control" required placeholder="<?=$displayName?>"/></br>
			
			<label for="protocol">Edit Protocol  - <?=$protocol?></label>
			<input value="<?=$protocol?>" type="text" name="protocol" class="form-control" required placeholder="<?=$protocol?>"/></br>
			
			
			<label for="domain">Edit Domain - <?=$domain?></label>
			<input value="<?=$domain?>" required class="form-control" type="text" name="domain" placeholder="<?=$domain?>" /></br>
			
			<label for="path">Edit Path - <?=$path?></label>
			<input class="form-control" type="text" name="path" placeholder="<?=$path?>"/><br>
			<label for="subdomain">Edit Subdomain - <?=$subdomain?></label>
			<input class="form-control" type="text" name="subdomain" placeholder="<?=$subdomain?>"/><br>
			<label for="host">Edit Host - <?=$host?></label>
			<input class="form-control" type="text" name="host" placeholder="<?=$host?>"/><br>
			<label for="tld">Edit TLD - <?=$tld?></label>
			<input class="form-control" max="3" type="text" name="tld" placeholder="<?=$tld?>"/><br>
			<label for="active">Active Status</label></br>
			YES <input <?=$activeYes?> type="radio" name="active" value="1">
			NO <input <?=$activeNo?> type="radio" name="active" value="0">
			<br>
			<button class="bs-callout bs-callout-blue" name="submit" value="submit">Confirm Edit</button>
			
		</form>
	</div>
	<? } ?>