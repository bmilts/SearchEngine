<?php
ob_start();

try {
    $con = new PDO("mysql:dbname=searchEngine;host=127.0.0.1;port=3306;", "bmilts", "");
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    
}
catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
}

?>