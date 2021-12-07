<?php

    //PHP LITE CONNECTION FILE
    
    $dbFileName = 'siteList.db';
    $dbFile = __DIR__ .  "/data/" . $dbFileName;
	//die($dbFile);
	
    
    if(file_exists($dbFile)) {
        try {
            $db = new PDO("sqlite:" . $dbFile);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (Exception $ex) {
            TRIGGER_ERROR($ex->getMessage(), E_USER_ERROR);
        }
    } else {
        // $file = $dbFileName;
        // $folder = $dbFile;
        // $fh = fopen($folder,'a');
        // fwrite($fh, '/* Database Created for '.$file . ' */');
        // fclose($fh);
        // header("Location: extra/");
        // exit("Database Created");
        
        TRIGGER_ERROR("ERROR: FILE NOT FOUND", E_USER_WARNING);
        echo '<a href="pma/" target="_top">Create Database File</a>';
        header("Location: ../.phpLiteAdmin/");
        exit;
    }
    
    FUNCTION br2nl($string) {
        RETURN PREG_REPLACE("#<br\s*?/?>#i", "\n", $string);
    }
    
    header("Content-Type: text/html; charset=utf-8");
    // session_start();


?>