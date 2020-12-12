<?php
if (isset($_GET['id'])) {
     $productId = $_GET['id'];
 } else {
     // Fallback behaviour goes here
 }

 $connect = mysqli_connect("localhost", "root", "", "android_api");  
 $sql = "SELECT * FROM `shopproduct` WHERE `ProductId` = $productId";  
// $sql = "SELECT shopproduct.ProductId, shopproduct.ShopId, shopproduct.SpecialOffer, shopproduct.Price FROM product INNER JOIN shopproduct ON product.Product_id= shopproduct.ProductId WHERE product.Product_id = $productId";
 $result = mysqli_query($connect, $sql);  
 $json_array = array();  
 $i = 0;
 while($row = mysqli_fetch_assoc($result))  
 {  
      $json_array[] = $row; 
      $sql2 = "SELECT shop.Shop_Id, shop.name, shop.longtitude, shop.latitude FROM shopproduct INNER JOIN shop ON shopproduct.Shopid=shop.Shop_Id WHERE shopproduct.ProductId = ".$productId;
      $result2 = mysqli_query($connect, $sql2);  
      $json_array2 = array();
      while($row2 = mysqli_fetch_assoc($result2)){
        $json_array2[] = $row2; 
        
      }
 
    $i++;
}  

// echo json_encode($json_array2);

// echo json_encode($json_array);
$res_arr = array();
echo '[';

for($i = 0; $i <sizeof($json_array); $i++){
    $tmp =  array(
    "Shop_Id"=>$json_array2[$i]['Shop_Id'], 
    "name"=>$json_array2[$i]['name'],
    "longtitude"=>$json_array2[$i]['longtitude'],
    "latitude"=>$json_array2[$i]['latitude'], 
    "id"=>$json_array[$i]['id'],
    "ShopId"=>$json_array[$i]['ShopId'],
    "ProductId"=>$json_array[$i]['ProductId'], 
    "Price"=>$json_array[$i]['Price'],
    "SpecialOffer"=>$json_array[$i]['SpecialOffer'],);
    echo json_encode($tmp);
    if($i != sizeof($json_array) -1){
        echo ',';
    }
    else
    echo ']';
        // $res_arr = $res_arr + $tmp;
    }

// echo json_encode($res_arr);
    




// for($i = 0; $i < 2; $i++){
// $sql = "SELECT shop.Shop_Id, shop.name, shop.longtitude, shop.latitude FROM shopproduct INNER JOIN shop ON shopproduct.Shopid=shop.Shop_Id WHERE shopproduct.ShopId = ".$json_array[0]['ShopId'] ;
//     $result = mysqli_query($connect, $sql);  
    
//     // echo json_encode(mysqli_fetch_assoc($result));
//     $meaw[] = (mysqli_fetch_assoc($result));
//     echo json_encode($meaw);
//     // echo json_encode($meaw).','.json_encode($json_array) ;
//     // echo substr(json_encode($meaw), 0, -1).',';
//     // echo substr(json_encode($json_array), 1);
//     // echo array_merge($json_array[0], $meaw);
//     // while($row = mysqli_fetch_assoc($result)){
//     //     if($json_array[$i] )
//     //     $tst[] = $row;  
//         // echo json_encode($tst);
//     // };
    

// }


 /*echo '<pre>';  
 print_r(json_encode($json_array));  
 echo '</pre>';*/  
//  echo json_encode($json_array);  
  ?>