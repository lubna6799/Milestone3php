<?php
 $connect = mysqli_connect("localhost", "root", "", "android_api");  
 $sql = "SELECT shop-product.price,shop-product.SpecialOffer,shop.longtitude,shop.latitude
 FROM shop
 INNER JOIN shop-product
 ON product.Product_id=shop.Product_id
 INNER JOIN shop
 ON shop.shop-id=shop-product.shop-id
 
 ?>