<?php ob_start();

//function to encrypt cookie
function encryptCookie($value){

  $key='a,s,d,#,4,32,][*&^..#&&*';
  $text=$value;
  $iv_size=mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
  $iv=mcrypt_create_iv($iv_size,MCRYPT_RAND);
  $crypttext=mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB,$iv);

  return trim(base64_encode($crypttext));

}

include('functions.php'); 
include('conn'.DS.'db_connection.php'); 

$connection=new DB_Connection();


   	     /*insert shoppers name*/
   	     $add_shopper="INSERT INTO VISITORS(VISITOR_NAME) VALUES('Friend')";
   	     $add_shopper_query=mysqli_query(DB_Connection::$connection,$add_shopper);

   	     if($add_shopper_query){

   	     	$shp_name="Friend";
   	     	$shpper_id=mysqli_insert_id(DB_Connection::$connection);
          $shp_id=encryptCookie($shpper_id);

   	     	setcookie("shopper_name",$shp_name,time()+31556926);
   	     	setcookie("shopper_id",$shp_id,time()+31556926);

          //get whatsapp link that was shared
          $link=$_COOKIE['sharelink'];

   	     	header("Location: ".$link);
   	     }




?>
