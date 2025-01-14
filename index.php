<?php
/**
Author: Javed Ur Rehman
Website: https://www.allphptricks.com
**/
    require_once 'extra/header';
        $ip = $_SERVER['REMOTE_ADDR'];
    $loggedIn = checkLogin($ip);
    //echo $loggedIn;
    $errorMSG = '<hr class="alert "><div class="oaerror danger">Your Device is not on the trusted list.... Please ask the admin to add <b class="alert alert-warning"><u>[[ '.$ip.' ]]</u></b> and then try again.</div>';
    if(!$loggedIn /* || empty($_SESSION['username']) */)exit($errorMSG);

    $username = !empty($_SESSION['username']) ? $_SESSION['username'] : trigger_error("SESSION NOT STARTED", E_USER_NOTICE);
    echo $username;

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

        $q = "INSERT INTO favorite_sites (username, siteid)VALUES(:user, :siteid)";
        $p = [':user' => $username,':siteid'=>$favorite];
        $stmt = $db->prepare($q);
        $result = $stmt->execute($p);
        header("Location: $self?page_no=$page_no");
        exit;
    }
    if($unfavorite){

        $q = "DELETE FROM favorite_sites WHERE username = :user AND siteid = :siteid";
        $p = [':user' => $username,':siteid'=>$unfavorite];
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
            <th style="width:100px;">PATH LINK</th>
            <th style="width:50px;">DOMAIN</th>
            <th style="width:100px;">HOST</th>
            <th style="width:200px;">ACTION <!-- - <a href="<?=$self?>?onlyFavs">Show Favs</a>--></th>
            </tr>
    </thead>
    <tbody>
<?php



    $q = "SELECT * FROM sites WHERE active = 1 $sql ORDER BY click_count DESC , timestamp DESC $limit ";
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

        $target=['_PARENT','_BLANK','_TOP','_SELF'];

        /*Attribute Values:
        ***********************************************************************
        * _BLANK: It opens the link in a new window.
        * _SELF: It opens the linked document in the same frame.
        * _PARENT: It opens the linked document in the parent frameset.
        * _TOP: It opens the linked document in the full body of the window.
        * FRAMENAME: It opens the linked document in the named frame.
        ************************************************************************/

        $subdomain = isset($subdomain) ? $subdomain :'';
        $href = $subdomain.$host . '.' .$tld;
        $href = 'login.php?id='.$id;
        $link = ' <a href="'.$href.'" target="'.$target[1].'">'.$host.'</a>';
        $deleteRow = '<a href="'.$self.'?page_no='.$page_no.'&delete='.$id.'"><i class="myFav fas fa-trash-alt"></i></a>';
        $editRow = '<a href="edit.php?p='.$page_no.'&id='.$id.'"><i class="fas fa-pencil-alt"></i></a>';
        $pathLink = is_null($path) || $path === 'null' || $path ==="/" ? TRUE: FALSE;
        //echo $pathLink;
        $href.$path = 'login.php?p&id='.$id;
        $addPath = str_replace('<i class="fas fa-pencil-alt"></i>','Add Path',$editRow);
        $pathLink =  !$pathLink ? '<a href="'.$href.$path.'" target="'.$target[1].'">'.$displayName.'</a>' : $addPath;



        $myFav = getFavs($username,$id) == true ? 'myFav' : '';
        $fav = getFavs($username,$id) == true ? 'unfav' : 'fav';

        $favorite = '<a class="fav '.$myFav.'" href="?page_no='.$page_no.'&'.$fav.'='.$id.'"><i class="fa fa-heartbeat"></i></a>';

        $approvedDevices = [
            '192.168.1.70', '192.168.1.25', '192.168.1.50','192.168.0.10'
        ];
        $actionList = !in_array($ip , $approvedDevices) && !empty($ip)? $favorite : $favorite." | ".$deleteRow." | ".$editRow ;
        //$onlyFavs = isset($_GET['onlyFavs']) ? true : false;


        echo "<tr>
                  <td>".$id."</td>
                  <td>".$displayName."</td>
                  <td>".$link."</td>
                  <td>".$pathLink."</td>
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