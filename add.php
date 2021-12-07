<?php
	echo '<link src="../.global/assets/css/sb-admin-2.css" rel="stylesheet"/>';
	echo '<link src="../.global/assets/css/sb-admin-2.min.css" rel="stylesheet"/>';
	require_once 'extra/header';
	$page = isset($_GET['p']) ? $_GET['p'] : 1;
	$self = $_SERVER['PHP_SELF'];
	
	IF(!EMPTY($_POST)){
		$dataInfos = [
			'displayName' => $_POST['displayName'],
			'protocol' => $_POST['protocol'],
			'domain' => $_POST['domain'],
			'path' => $_POST['path'],
			'subdomain' => $_POST['subdomain'],
			'host' => $_POST['host'],
			'tld' => $_POST['tld'],
			'parent_domain' => null,
			'active' => 1,
		];
		FOREACH($dataInfos as $index => $val){
			define(strtoupper($index),$val);
		}
		
		$q = "INSERT INTO sites(display_name, protocol, domain, path, subdomain, host, tld, parent_domain, active)VALUES(:display_name, :protocol, :domain, :path, :subdomain, :host, :tld, :parent_domain, :active)";
		$p = [
			':display_name' => DISPLAYNAME,
			':protocol' => PROTOCOL ,
			':domain' => DOMAIN ,
			':path' => PATH ,
			':subdomain' => SUBDOMAIN ,
			':host' => HOST ,
			':tld' => TLD ,
			':parent_domain' => PARENT_DOMAIN ,
			':active' => ACTIVE
		];
		$stmt = $db->prepare($q);
		$result = $stmt->execute($p);
		HEADER("Location: index.php");
		exit;
		/* echo '<a href="index.php">RETURN HOME</a>';
		exit("ADDED SUCCESFULLY!"); */
		
	}else{

?>
	<div style="width:500px;" class="container container-pad">
		<h1>Add a Site</h1>
		<form class="alert alert-success" method="post" action="<?=$self?>">
			
			<label for="displayName">Enter Display Name</label>
			<input type="text" name="displayName" class="form-control" required placeholder="EG: Google Search Engine"/></br>
			
			<label for="protocol">Select a Protocol</label>
			<select required name="protocol" class="form-control">
				<option value=""></option>
				<option value="http">HTTP://</option>
				<option value="https">HTTPS://</option>
			</select>
			
			<label for="domain">Enter Domain Name</label>
			<input required class="form-control" type="text" name="domain" placeholder="EG: google.com" /></br>
			
			<label for="path">Enter Path</label>
			<input  class="form-control" type="text" name="path" placeholder="EG:/register/index.php" /></br>
			
			<label for="subdomain">Enter Sub-Domain Name</label>
			<input class="form-control" type="text" name="subdomain" placeholder="EG: www." /></br>
			
			<label for="host">Enter Host Name</label>
			<input required class="form-control" type="text" name="host" placeholder="EG: google" /></br>
			
			<label for="tld">Enter TLD</label>
			<input required class="form-control" type="text" name="tld" placeholder="EG: com" /></br>
			
			<button class="bs-callout bs-callout-red" name="submit" value="submit">Confirm</button>
		
		</form>




<?php } ?>