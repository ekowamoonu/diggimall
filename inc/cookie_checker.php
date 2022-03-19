<?php

/*function to encrypt and decrypt cookies*/
function encryptCookie($value){

// $key='a,s,d,#,4,32,][*&^..#&&*';
  // $text=$value;
  // $iv_size=mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
  // $iv=mcrypt_create_iv($iv_size,MCRYPT_RAND);
  // $crypttext=mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB,$iv);

  // return trim(base64_encode($text));
  return $value;

}//end encryption

function decryptCookie($value){
   
  // $key='a,s,d,#,4,32,][*&^..#&&*';
  // $text=$value;
  // $iv_size=mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
  // $iv=mcrypt_create_iv($iv_size,MCRYPT_RAND);
  // $crypttext=mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB,$iv);

  // return trim(base64_encode($text));
  return $value;

}//end decryption

if((!isset($_COOKIE['shopper_name'])||!isset($_COOKIE['shopper_id']))&&!isset($_GET['sharefollower'])&&!isset($_GET['vendorfollower'])){
   header("Location: index");
}
else if((!isset($_COOKIE['shopper_name'])||!isset($_COOKIE['shopper_id']))&&isset($_GET['sharefollower'])){
    
    //get link of that product
    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; 
    $new_link=str_replace("&sharefollower=2", "", $actual_link);//for redirection to the product that was shared
    
    setcookie("sharelink",$new_link,time()+500);
    header("Location: continue");
}
else if((!isset($_COOKIE['shopper_name'])||!isset($_COOKIE['shopper_id']))&&isset($_GET['vendorfollower'])){
    
     /*insert shoppers name*/
         $add_shopper="INSERT INTO VISITORS(VISITOR_NAME) VALUES('Friend')";
         $add_shopper_query=mysqli_query(DB_Connection::$connection,$add_shopper);

         if($add_shopper_query){

          $shp_name="Friend";
          $shoppers_name=$shp_name;
          $shpper_id=mysqli_insert_id(DB_Connection::$connection);
          $shp_id=encryptCookie($shpper_id);
          $shoppers_id=(int)decryptCookie($shp_id);

          setcookie("shopper_name",$shp_name,time()+31556926);
          setcookie("shopper_id",$shp_id,time()+31556926);
        }
}

else{
	    $shoppers_name=htmlentities($_COOKIE['shopper_name']);
	    $shoppers_id=(int)decryptCookie($_COOKIE['shopper_id']);

}




?>