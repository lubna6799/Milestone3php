<?php

require_once 'include/DB_Functions.php';
$db = new DB_Functions();




if (isset($_POST['user_id']) && isset($_POST['shops']) && isset($_POST['products'] )&& isset($_POST['product_name'] )&& isset($_POST['shop_name'] ) && isset($_POST['price'] ) ) {
 
     
    echo json_encode($db->storeFavs($_POST['user_id'], $_POST['shops'], $_POST['products'], $_POST['product_name'], $_POST['shop_name'], $_POST['price']));
    


   
} else {
    
    // required get params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters item id is missing!";
    echo json_encode($response);
}




?> 