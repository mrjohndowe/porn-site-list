<?php
/*
Author: Javed Ur Rehman
Website: https://www.allphptricks.com
*/
require_once 'extra/header';


	$ip = $_SERVER['REMOTE_ADDR'];
	$page_no = isset($_GET['page_no']) ? $_GET['page_no'] : 1 ;
	$delete = isset($_GET['delete']) ? $_GET['delete'] : '';
	$favorite = isset($_GET['fav']) ? $_GET['fav'] : '';
	$unfavorite = isset($_GET['unfav']) ? $_GET['unfav'] : '';
	$total_records_per_page = 15;
    $offset = ($page_no-1) * $total_records_per_page;
	$previous_page = $page_no - 1;
	$next_page = $page_no + 1;
	$adjacents = "2"; 
	
	if($delete){
		$q = "UPDATE sites SET active = 0 WHERE id = :id";
		$p = [':id' => $delete];
		$stmt = $db->prepare($q);
		$result = $stmt->execute($p);
		header("Location: $self?page_no=$page_no");
		exit;
	}
	if($favorite){
		
		$q = "INSERT INTO users (ip_address, siteid)VALUES(:ip, :siteid)";
		$p = [':ip' => $ip,':siteid'=>$favorite];
		$stmt = $db->prepare($q);
		$result = $stmt->execute($p);
		header("Location: $self?page_no=$page_no");
		exit;
	}
	if($unfavorite){
		
		$q = "DELETE FROM users WHERE ip_address = :ip AND siteid = :siteid";
		$p = [':ip' => $ip,':siteid'=>$unfavorite];
		$stmt = $db->prepare($q);
		$result = $stmt->execute($p);
		header("Location: $self?page_no=$page_no");
		exit;
	}
	$search = isset($_GET['Search']) ? $_GET['Search'] : null ;
	$sql = isset($_GET['Search']) ? 'AND host LIKE "%'.$_GET['Search'].'%"' : null;
	//if($sql == null){header("Location: $self");exit;}
	$q = "SELECT COUNT(*) AS total_records FROM sites WHERE active = 1 $sql";
	$stmt = $db->prepare($q);
	$result = $stmt->execute();
	$total_records = $stmt->fetch()['total_records'];
	
	
    $total_no_of_pages = ceil($total_records / $total_records_per_page);
	$total_no_of_pages = $sql != null ? 1 : $total_no_of_pages;
	$second_last = $total_no_of_pages - 1; // total page minus 1
	
	$limit = !isset($sql) ? "LIMIT $offset, $total_records_per_page": '';
	
	$addSite = '<a href="add.php" target="_top">Add New Website</a> ';
	
	$newAddition = $total_records == 0 ? $addSite : "$addSite $total_records Records Found For $search";
	
	$newAddition = $search ? $newAddition : '';

?>
<?=$newAddition?>
<table class="table table-striped table-bordered">
<thead>
<tr>
<th style="width:50px;">No.</th>
<th style="width:100px;">NAME</th>
<th style="width:100px;">LINK</th>
<th style="width:50px;">DOMAIN</th>
<th style="width:100px;">HOST</th>
<th style="width:200px;">ACTION <!-- - <a href="<?=$self?>?onlyFavs">Show Favs</a>--></th>
</tr>
</thead>
<tbody>
<?php
	
	
	
	$q = "SELECT * FROM sites WHERE active = 1 $sql ORDER BY id ASC $limit ";
	$stmt = $db->prepare($q);
	$result = $stmt->execute();
	$rows = $stmt->fetchAll();
	$i = 0;
	foreach($rows as $row){
		$i = $i+1;
		
		$id = $row['id'];
		$displayName = $row['display_name'];
		$protocol = $row['protocol'];
		$path = $row['path'];
		$subdomain = $row['subdomain'];
		$domain = $row['domain'];
		$host = $row['host'];
		$tld = $row['tld'];
		
		
		
		$subdomain = isset($subdomain) ? $subdomain :'';
		$href = $subdomain.$host . '.' .$tld;
		$link = ' <a href="http://'.$href.'" target="_BLANK">'.$host.'</a>';
		$deleteRow = '<a href="'.$self.'?page_no='.$page_no.'&delete='.$id.'"><i class="myFav fas fa-trash-alt"></i></a>';
		$editRow = '<a href="edit.php?p='.$page_no.'&id='.$id.'"><i class="fas fa-pencil-alt"></i></a>';
		
		
		
		$myFav = getFavs($ip,$id) == true ? 'myFav' : '';
		$fav = getFavs($ip,$id) == true ? 'unfav' : 'fav';
		
		$favorite = '<a class="fav '.$myFav.'" href="?page_no='.$page_no.'&'.$fav.'='.$id.'"><i class="fa fa-heartbeat"></i></a>';
		
		$approvedDevices = [
			'192.168.1.70', '192.168.1.25', '192.168.1.50'
		];
		$actionList = !in_array($ip , $approvedDevices) ? $favorite : $favorite." | ".$deleteRow." | ".$editRow ;
		//$onlyFavs = isset($_GET['onlyFavs']) ? true : false;
		
		
		echo "<tr>
			  <td>".$id."</td>
			  <td>".$displayName."</td>
			  <td>".strtoupper($link)."</td>
	 		  <td>".$domain."</td>
		   	  <td>".$host."</td>
		   	  <td>".$actionList."</td>
		   	  </tr>";
	}
    ?>
	</tbody>
</table>

<div style='padding: 10px 20px 0px; border-top: dotted 1px #CCC;'>
<strong>Page <?php echo $page_no." of ".$total_no_of_pages; ?></strong>
</div>

	<ul class="pagination">
		<?php // if($page_no > 1){ echo "<li><a href='?page_no=1'>First Page</a></li>"; } ?>
		
		<li <?php if($page_no <= 1){ echo "class='disabled'"; } ?>>
		<a <?php if($page_no > 1){ echo "href='?page_no=$previous_page'"; } ?>>Previous</a>
		</li>
		   
		<?php 
		if ($total_no_of_pages <= 10){  	 
			for ($counter = 1; $counter <= $total_no_of_pages; $counter++){
				if ($counter == $page_no) {
			   echo "<li class='active'><a>$counter</a></li>";	
					}else{
			   echo "<li><a href='?page_no=$counter'>$counter</a></li>";
					}
			}
		}
		elseif($total_no_of_pages > 10){
			
		if($page_no <= 4) {			
		 for ($counter = 1; $counter < 8; $counter++){		 
				if ($counter == $page_no) {
			   echo "<li class='active'><a>$counter</a></li>";	
					}else{
			   echo "<li><a href='?page_no=$counter'>$counter</a></li>";
					}
			}
			echo "<li><a>...</a></li>";
			echo "<li><a href='?page_no=$second_last'>$second_last</a></li>";
			echo "<li><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
			}

		 elseif($page_no > 4 && $page_no < $total_no_of_pages - 4) {		 
			echo "<li><a href='?page_no=1'>1</a></li>";
			echo "<li><a href='?page_no=2'>2</a></li>";
			echo "<li><a>...</a></li>";
			for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++) {			
			   if ($counter == $page_no) {
			   echo "<li class='active'><a>$counter</a></li>";	
					}else{
			   echo "<li><a href='?page_no=$counter'>$counter</a></li>";
					}                  
		   }
		   echo "<li><a>...</a></li>";
		   echo "<li><a href='?page_no=$second_last'>$second_last</a></li>";
		   echo "<li><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";      
				}
			
			else {
			echo "<li><a href='?page_no=1'>1</a></li>";
			echo "<li><a href='?page_no=2'>2</a></li>";
			echo "<li><a>...</a></li>";

			for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {
			  if ($counter == $page_no) {
			   echo "<li class='active'><a>$counter</a></li>";	
					}else{
			   echo "<li><a href='?page_no=$counter'>$counter</a></li>";
					}                   
					}
				}
		}
	?>
		
		<li <?php if($page_no >= $total_no_of_pages){ echo "class='disabled'"; } ?>>
		<a <?php if($page_no < $total_no_of_pages) { echo "href='?page_no=$next_page'"; } ?>>Next</a>
		</li>
		<?php if($page_no < $total_no_of_pages){
			echo "<li><a href='?page_no=$total_no_of_pages'>Last &rsaquo;&rsaquo;</a></li>";
			} ?>
	</ul>


<footer><? include_once 'extra/footer';?></footer>