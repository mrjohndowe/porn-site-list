<style>
    body {
        inline-size:fit-content;
        background-color: rgba(0, 0, 0, 0.75);
        color: silver;
    }
    
</style>
    

<?php

    

    include_once 'extra/header';
    
    if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip_address = $_SERVER['REMOTE_ADDR'];
    }
    
    $i = 1;
    echo '
        
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <td></td>
                            <td>$_SERVER[<code>NAME</code>]</td>
                            <td>$_SERVER[] => VALUE </td/
                        </tr>
                    </thead>
                    <tbody>
    ';
        
	FOREACH($_SERVER as $name => $val){
	   
	        $i = $i + 1;
	        echo "
	            <tr>
    	            <td>$i</td>
    	            <td>$name</td>
    	            <td>$val</td>
    	       </tr>
	        ";
	    
		//echo '$_SERVER["'.$name . '"] .... <b style="color:crimson;">'. $val.'</b><br>';
	}
	
	echo '</tbody></table></div><hr>';
/*	
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,'http://' . $_SERVER["HTTP_HOST"] . '.profile');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$vars);  //Post Fields
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $headers = [
        'X-Apple-Tz: 0',
        'X-Apple-Store-Front: 143444,12',*/
        //'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        /*'Accept-Encoding: gzip, deflate',
        'Accept-Language: en-US,en;q=0.5',
        'Cache-Control: no-cache',
        'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
        'Host: www.example.com',
        'Referer: http://www.example.com/index.php', //Your referrer address
        'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0',
        'X-MicrosoftAjax: Delta=true'
    ];
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $server_output = curl_exec ($ch);
    
    curl_close ($ch);
    
    print  $server_output ;
    
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    // ...
    
    $response = curl_exec($ch);
    
    // Then, after your curl_exec call:
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($response, 0, $header_size);
    $body = substr($response, $header_size);

*/
?>