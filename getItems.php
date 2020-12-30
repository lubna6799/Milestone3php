<?php
require_once 'include/DB_Functions.php';
$db = new DB_Functions();
if(isset($_GET['getItems'])) {
    $db-> getItems() ;
    
   
    

} else{
    $response["error"] = TRUE;
    $response["error_msg"] = "get Items not set!";
    echo json_encode($response);

}

?>