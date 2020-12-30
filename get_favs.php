<?php

require_once 'include/DB_Functions.php';
$db = new DB_Functions();




if (isset($_POST['user_id'])  ) {
 
     
    return $db->getFavs($_POST['user_id']);



   
} else {
    
    // required get params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters item id is missing!";
    echo json_encode($response);
}




?> 