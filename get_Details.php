<?php

require_once 'include/DB_Functions.php';
$db = new DB_Functions();



if (isset($_GET['item_id'])) {
 
    // receiving the get params
    $item_id = $_GET['item_id'] ;
   // echo $item_id  ;
   $db->get_Details($item_id) ;


   
} else {
    
    // required get params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters item id is missing!";
    echo json_encode($response);
}




?> 