<?php

/**
 * @author Ravi Tamada
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

class DB_Functions {

    private $conn;

    // constructor
    function __construct() {
        require_once 'DB_Connect.php';
        // connecting to database
        $db = new Db_Connect();
        $this->conn = $db->connect();
    }

    // destructor
    function __destruct() {
        
    }

    /**
     * Storing new user
     * returns user details
     */
    public function storeUser($name, $email, $password , $phone , $address) {
        $uuid = uniqid('', true);
        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt

        //$result = $mysqli -> query("INSERT INTO users(unique_id, name, email, encrypted_password, salt, created_at, phoneNumber, address) VALUES($uuid, $name, $email, $encrypted_password, $salt, NOW() , $phone , $address );")
        $stmt = $this->conn->prepare("INSERT INTO users(unique_id, name, email, encrypted_password, salt, created_at, phoneNumber, address) VALUES(?, ?, ?, ?, ?, NOW() , ?,?);");
        $stmt->bind_param("sssssss", $uuid, $name, $email, $encrypted_password, $salt,  $phone , $address );
        $result = $stmt->execute();
        $stmt->close();

        // check for successful store
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            return $user;
        } else {
            return false;
        }
    }

    public function storeFavs($user_id, $shop_id, $product_id, $product_name, $shop_name, $price){
        $id =random_int(100, 100000);
        // return array("data" => "$user_id, $shop_id, $product_id, $product_name, $shop_name, $price");
        //$result = $mysqli -> query("INSERT INTO users(unique_id, name, email, encrypted_password, salt, created_at, phoneNumber, address) VALUES($uuid, $name, $email, $encrypted_password, $salt, NOW() , $phone , $address );")
        
         //$result = $mysqli -> query("INSERT INTO users(unique_id, name, email, encrypted_password, salt, created_at, phoneNumber, address) VALUES($uuid, $name, $email, $encrypted_password, $salt, NOW() , $phone , $address );")
        //  $stmt = $this->conn->prepare("INSERT INTO favs(id, user_id, products, shops, product_name, shop_name, price) VALUES(6, 1, 2, 3, asd, asd, 22222;");
        //  //$stmt->bind_param("sssssss",$id, $user_id, $product_id, $shop_id,  $product_name, $shop_name, $price );
        //  $result = $stmt->execute();
        //  $stmt->close();


        $servername = "localhost";
$username = "root";
$password = "";
$dbname = "m3";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO favs(id, user_id, products, shops, product_name, shop_name, price) VALUES($id, $user_id, $product_id, $shop_id, '$product_name', '$shop_name', '$price');";

if ($conn->query($sql) === TRUE) {
  return array( "New record created successfully" => true);
} else {
    return array( "New record created successfully" => $conn->error, "ssss" => $sql);

}

$conn->close();
        return array("error" => $result);
        // check for successful store
        if ($result) {
            return $array = array("error" => false, "message"=>"added to favs");;
        } else {
            return $array = array("error" => true, "message"=>"cannot add to favs $result xd");;
        }
    }

    public function deleteFavs($item_id){

        $servername = "localhost";
$username = "root";
$password = "";
$dbname = "m3";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$itemId = (int)$item_id;
// sql to delete a record
$sql = "DELETE FROM favs WHERE id=$itemId";

if ($conn->query($sql) === TRUE) {
  return array("msg" =>"Record deleted successfully $sql ");
} else {
  return array("msg" => "Error deleting record: " . $conn->error);
}

$conn->close();
       


        // // check for successful store
        // if ($result) {
        //     return array("error" => false , "message" => "byeee");
        // } else {
        //     return array("error" => true, "message" => "eeeee");
        // }
    }


    public function getFavs($user_id){
         
        $sql = "SELECT * FROM `favs` WHERE `user_id` =".$user_id;
        $result = $this->conn->query($sql);
        $i=0;
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $array[$i]=array(
                "error"=> FALSE ,
                "id" => $row['id'] ,
                "user_id" => $row['user_id'] ,
               "shops"=>$row['shops'] , 
               "products"=>$row['products'] , 
               "product_name" =>$row['product_name'],
               "shop_name" => $row['shop_name'],
               "price" => $row['price']
                );
                $i++;
            }
            echo json_encode($array);
          } else {
            $array["error"] = TRUE;
            $array["error_msg"] = "No Items in database";
            echo json_encode($array);
          }
        
        
      
    }

    /**
     * Get user by email and password
     */
    public function getUserByEmailAndPassword($email, $password) {

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");

        $stmt->bind_param("s", $email);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user password
            $salt = $user['salt'];
            $encrypted_password = $user['encrypted_password'];
            $hash = $this->checkhashSSHA($salt, $password);
            // check for password equality
            if ($encrypted_password == $hash) {
                // user authentication details are correct
                return $user;
            }
        } else {
            return NULL;
        }
    }

    /**
     * Check user is existed or not
     */
    public function isUserExisted($email) {
        $stmt = $this->conn->prepare("SELECT email from users WHERE email = ?");

        $stmt->bind_param("s", $email);

        $stmt->execute();

        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // user existed 
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            return false;
        }
    }

    /**
     * Encrypting password
     * @param password
     * returns salt and encrypted password
     */
    public function hashSSHA($password) {

        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }

    /**
     * Decrypting password
     * @param salt, password
     * returns hash string
     */
    public function checkhashSSHA($salt, $password) {

        $hash = base64_encode(sha1($password . $salt, true) . $salt);

        return $hash;
    }
    public function getItems() {
        
        $sql = "SELECT * FROM items;";
        $result = $this->conn->query($sql);
        $i=0;
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $array[$i]=array(
                "error"=> FALSE ,
                "uid" => $row['id'] ,
                "name" => $row['item_name'] ,
               "image_url"=>$row['image_url'] , 
               "description"=>$row['description']) ;
               $i++;
               
            }
            echo json_encode($array);
          } else {
            $array["error"] = TRUE;
            $array["error_msg"] = "No Items in database";
            echo json_encode($array);
          }
        
        
      
        
        
        
    }
    public function get_Details($item_id) {
        
        $sql = "SELECT DISTINCT shop_items.id, shop_items.price , shops.name,shops.latitude, shops.longitude from 
        shops INNER JOIN shop_items ON shops.id= shop_items.shop_id and shop_items.item_id = $item_id;";
        $result = $this->conn->query($sql);
        $i=0;
      
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $array[$i]=array("error"=> FALSE ,
                  "uid"=> $row['id'] ,   
                "name" => $row['name'] ,
                "lat"=>$row['latitude'] , 
               "long"=>$row['longitude']  ,
               "price"=> $row['price']) ;

               $i++;
               
            
          
       
               
            }
            echo json_encode($array);
          } else {
            $array["error"] = TRUE;
            $array["error_msg"] = "No Items in database";
            echo json_encode($array);
          } 

    }


}

?>
