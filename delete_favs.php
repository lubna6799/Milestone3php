<?php

require_once 'include/DB_Functions.php';
$db = new DB_Functions();




if (isset($_POST['item_id'])  ) {
 
     
    echo json_encode( $db->deleteFavs($_POST['item_id']));



   
} else {
    
    // required get params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters item id is missing!";
    echo json_encode($response);
}




?> 