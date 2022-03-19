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


  //if requirement is provided
 if(isset($_POST['visitor_id'])&&isset($_POST['product_id'])&&isset($_POST['product_price'])&&isset($_POST['quantity'])&&isset($_POST['requirements'])){

 	if(!empty($_POST['visitor_id'])&&!empty($_POST['product_id'])&&!empty($_POST['product_price'])&&!empty($_POST['quantity'])&&!empty($_POST['requirements'])){
           
           $visitor_id=$_POST['visitor_id'];
           $product_id=$form_man->cleanString($_POST['product_id']);
           $quantity=$form_man->cleanString($_POST['quantity']);
           $product_price=$_POST['product_price'];
           $requirements=$form_man->cleanString($_POST['requirements']);

           //add to bag
           $bag_insert="INSERT INTO BAG_ITEMS(VSTR_ID,PRDT_ID,ORDERED_AMOUNT,PRODUCT_PRICE,REQUIREMENTS) VALUES(";
           $bag_insert.="'{$visitor_id}',";
           $bag_insert.="'{$product_id}',";
           $bag_insert.="'{$quantity}',";
           $bag_insert.="'{$product_price}',";
           $bag_insert.="'{$requirements}')";

		      $bag_query=mysqli_query(DB_Connection::$connection,$bag_insert);
       //echo mysqli_error(DB_Connection::$connection);

		   if($bag_query){

		   	   /*get number of items in shoppers bag*/
				$shopping_bag_query=mysqli_query(DB_Connection::$connection,"SELECT COUNT(*) FROM BAG_ITEMS WHERE VSTR_ID=".$visitor_id);
				$bag_array=mysqli_fetch_array($shopping_bag_query);
				$number_of_items=array_shift($bag_array);

				echo "Your shopping bag (".$number_of_items." distinct orders)";

		   }//end if bag query

      }//end if not empty
 }//end main if


  //if requirement is not provided
 if(isset($_POST['visitor_id2'])&&isset($_POST['product_id2'])&&isset($_POST['product_price2'])&&isset($_POST['quantity2'])){

 	if(!empty($_POST['visitor_id2'])&&!empty($_POST['product_id2'])&&!empty($_POST['product_price2'])&&!empty($_POST['quantity2'])){
           
           $visitor_id2=$_POST['visitor_id2'];
           $product_id2=$form_man->cleanString($_POST['product_id2']);
           $quantity2=$form_man->cleanString($_POST['quantity2']);
           $product_price2=$_POST['product_price2'];
         

           //add to bag
           $bag_insert="INSERT INTO BAG_ITEMS(VSTR_ID,PRDT_ID,ORDERED_AMOUNT,PRODUCT_PRICE,REQUIREMENTS) VALUES(";
           $bag_insert.="'{$visitor_id2}',";
           $bag_insert.="'{$product_id2}',";
           $bag_insert.="'{$quantity2}',";
           $bag_insert.="'{$product_price2}',";
           $bag_insert.="'no requirements')";

		   $bag_query=mysqli_query(DB_Connection::$connection,$bag_insert);

		   if($bag_query){

		   	   /*get number of items in shoppers bag*/
				$shopping_bag_query=mysqli_query(DB_Connection::$connection,"SELECT COUNT(*) FROM BAG_ITEMS WHERE VSTR_ID=".$visitor_id2);
				$bag_array=mysqli_fetch_array($shopping_bag_query);
				$number_of_items=array_shift($bag_array);

				echo "Your shopping bag (".$number_of_items." distinct orders)";

		   }//end if bag query

      }//end if not empty
 }//end main if



 /*******************************************mall shopping**************************************************************/

  //if requirement is provided
 if(isset($_POST['mallvisitor_id'])&&isset($_POST['mallproduct_id'])&&isset($_POST['mallproduct_price'])){

  if(!empty($_POST['mallvisitor_id'])&&!empty($_POST['mallproduct_id'])&&!empty($_POST['mallproduct_price'])){
           
           $mallvisitor_id=$_POST['mallvisitor_id'];
           $mallproduct_id=$form_man->cleanString($_POST['mallproduct_id']);
           $mallproduct_price=$_POST['mallproduct_price'];
           //add to bag
           $bag_insert2="INSERT INTO BAG_ITEMS(VSTR_ID,PRDT_ID,ORDERED_AMOUNT,PRODUCT_PRICE,REQUIREMENTS) VALUES(";
           $bag_insert2.="'{$mallvisitor_id}',";
           $bag_insert2.="'{$mallproduct_id}',";
           $bag_insert2.="'1',";
           $bag_insert2.="'{$mallproduct_price}',";
           $bag_insert2.="'none')";

          $bag_query2=mysqli_query(DB_Connection::$connection,$bag_insert2);
       //echo mysqli_error(DB_Connection::$connection);

       if($bag_query){

                   echo "1";
         }//end if bag query

      }//end if not empty
 }//end main if










 ?>