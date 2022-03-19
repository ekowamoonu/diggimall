<?php 
//include database connection
include('../functions.php'); 
include('../conn'.DS.'db_connection.php'); 
include('../classes'.DS.'querying_class.php');
include('../classes'.DS.'form_class.php');

$connection=new DB_Connection();
$query_guy=new DataQuery();
$form_man=new FormDealer();

 ?>

 <?php


  //bag additons
 if(isset($_POST['bag_id'])){

 	if(!empty($_POST['bag_id'])){
         
         $id=$_POST['bag_id'];
         $update_query=mysqli_query(DB_Connection::$connection,"UPDATE BAG_ITEMS SET ORDERED_AMOUNT=ORDERED_AMOUNT+1 WHERE BAG_ID=".$id);

         if($update_query){

                $new_amount_query=mysqli_query(DB_Connection::$connection,"SELECT PRODUCT_PRICE,ORDERED_AMOUNT FROM BAG_ITEMS WHERE BAG_ID=".$id);
                $new_array=mysqli_fetch_assoc($new_amount_query);
                $new_ordered=$new_array['ORDERED_AMOUNT'];
                $new_product_price=$new_array['PRODUCT_PRICE'];
                $new_total=$new_array['PRODUCT_PRICE']*$new_array['ORDERED_AMOUNT'];

                echo $new_total.",".$new_ordered.",".$new_product_price;
         }//end if update query

      }//end if not empty
 }//end main if


  //bag deductions
 if(isset($_POST['bag_idm'])){

  if(!empty($_POST['bag_idm'])){
         
         $id=$_POST['bag_idm'];
         $update_query=mysqli_query(DB_Connection::$connection,"UPDATE BAG_ITEMS SET ORDERED_AMOUNT=ORDERED_AMOUNT-1 WHERE BAG_ID=".$id);

         if($update_query){

                $new_amount_query=mysqli_query(DB_Connection::$connection,"SELECT PRODUCT_PRICE,ORDERED_AMOUNT FROM BAG_ITEMS WHERE BAG_ID=".$id);
                $new_array=mysqli_fetch_assoc($new_amount_query);
                $new_ordered=$new_array['ORDERED_AMOUNT'];
                $new_product_price=$new_array['PRODUCT_PRICE'];
                $new_total=$new_array['PRODUCT_PRICE']*$new_array['ORDERED_AMOUNT'];

                echo $new_total.",".$new_ordered.",".$new_product_price;
         }//end if update query

      }//end if not empty
 }//end main if









 ?>