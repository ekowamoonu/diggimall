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


  //if review is submitted
 if(isset($_POST['buyers_id'])&&isset($_POST['pdt_id'])&&isset($_POST['number_of_stars'])&&isset($_POST['review_content'])){

 	if(!empty($_POST['buyers_id'])&&!empty($_POST['pdt_id'])&&!empty($_POST['number_of_stars'])&&!empty($_POST['review_content'])){
           
           /*getting the details*/
           $buyers_id=$form_man->cleanString($_POST['buyers_id']);
           $pdt_id=$form_man->cleanString($_POST['pdt_id']);
           $number_of_stars=$form_man->cleanString($_POST['number_of_stars']);
           $review_content=$form_man->cleanString($_POST['review_content']);

           $review_date=strftime("%Y-%m-%d %H:%M:%S", time());

           /*getting buyers details*/
           $buyer_details=$query_guy->find_by_id("BUYERS","BUYER_ID",$buyers_id);
           $buyer_name= $buyer_details['BUYER_NAME'];
           $buyer_hall= $buyer_details['BUYER_HALL'];

           $review_query="INSERT INTO REVIEWS(PDT_ID,REVIEWER_ID,REVIEW_CONTENT,REVIEWER_RATING,REVIEW_DATE) VALUES";
           $review_query.="('{$pdt_id}','{$buyers_id}','{$review_content}','{$number_of_stars}','{$review_date}')";

           $run_review_query=mysqli_query(DB_Connection::$connection,$review_query);

           echo $run_review_query?1:"failed to add review";
        

      }//end if not empty
 }//end main if


/*wishlist*/
 if(isset($_POST['product_id'])&&isset($_POST['customer_id'])){

  if(!empty($_POST['product_id'])&&!empty($_POST['customer_id'])){
           
           /*getting the details*/
           $customer_id=$form_man->cleanString($_POST['customer_id']);
           $product_id=$form_man->cleanString($_POST['product_id']);

           $wishlist_query="INSERT INTO WISHLIST(PDCT_ID,CUSTOMER_ID) VALUES ('{$product_id}','{$customer_id}')";
           $run_wishlist_query=mysqli_query(DB_Connection::$connection,$wishlist_query);

           echo $run_wishlist_query?1:"failed to add to wishlist";
        

      }//end if not empty
 }//end main if



 ?>