<?php 
//include database connection
include('../functions.php'); 
include('../conn'.DS.'db_connection.php'); 
include('../classes'.DS.'querying_class.php');
include('../classes'.DS.'form_class.php');
include('../Smsgh/Api.php');

$connection=new DB_Connection();
$query_guy=new DataQuery();
$form_man=new FormDealer();

 ?>

 <?php

 //getting the list of all institutions from a country
 if(isset($_POST['category_id'])){

 	if(!empty($_POST['category_id'])){

 		$rex="";
        $category=$_POST['category_id'];
 		$records=mysqli_query(DB_Connection::$connection,"SELECT * FROM SUB_CATEGORY WHERE PARENT_CATEGORY_ID=".$category/*." ORDER BY PRODUCT_NAME ASC"*/);

 		while($cat_array=mysqli_fetch_assoc($records)){

 			    $sub_cat_id=$cat_array['SUB_CATEGORY_ID'];
                $sub_cat_name=$cat_array['SUB_CATEGORY_NAME'];

 			    $rex.="<option value='{$sub_cat_id}'>".ucfirst($sub_cat_name)."</option>";
 		}

 		echo $rex;

 	}
 }


 //getting the list of all institutions from a country
 if(isset($_POST['sub_category_id'])){

 	if(!empty($_POST['sub_category_id'])){

 		$rex="";
        $category=$_POST['sub_category_id'];
 		$records=mysqli_query(DB_Connection::$connection,"SELECT * FROM SUB_SET WHERE PARENT_SUB_CATEGORY_ID=".$category/*." ORDER BY PRODUCT_NAME ASC"*/);

 		while($cat_array=mysqli_fetch_assoc($records)){

 			    $sub_set_id=$cat_array['SUB_SET_ID'];
                $sub_set_name=$cat_array['SUB_SET_NAME'];

 			    $rex.="<option value='{$sub_set_id}'>".ucfirst($sub_set_name)."</option>";
 		}

 		echo $rex;

 	}
 }


  //getting the list by seller id and keyword from dashboard 
 if(isset($_POST['search_item'])&&isset($_POST['seller_id'])){

 	if(!empty($_POST['search_item'])&&!empty($_POST['seller_id'])){

                   $id=$form_man->cleanString($_POST['seller_id']);
                   $key=$form_man->cleanString($_POST['search_item']);
				   //intialise reading variable
				   $item_list="";

				  /*all sellers itemss*/
				   $seller_items=$query_guy->find_products_by_seller_and_key_word($id,$key);

				   $number_of_items=mysqli_num_rows($seller_items);

				   if($number_of_items==0){$item_list='<h4>No Items Match Your Search</h4>';}
				   else{

				         while($item=mysqli_fetch_assoc($seller_items)){
				                 
				                 $item_name=ucfirst($item['PRODUCT_NAME']);
				                 $itemid=$item['PRODUCT_ID'];
				                 $available=$item['AVAILABLE_STOCK'];
				                 $price=$item['PRODUCT_PRICE'];
				                 $description=$item['PRODUCT_DESCRIPTION'];

				                $item_list.='<h4>'.$item_name.'<span class="item-overlay text-right"><a class="btn btn-info" href=\'item-management?item='.$itemid.'\'"><i class="fa fa-check"></i>  Manage</a> <button class="btn btn-info extra"><i class="fa fa-plus"></i>  View Details</button> </span></h4>';
				                $item_list.='<div class="item-details">';
				                $item_list.=' <ul class="nav">';
				                $item_list.='<li>Available Quantity: <span>'.$available.'</span></li>';
				                $item_list.='<li>Price: <b>GHS</b> <span>'.number_format($price,2).'</span></li>';
				                $item_list.='<li>Description: <span>'.substr($description,0,10)."...".'</span></li>';
				                $item_list.='</ul></div>';
				         }//end else

				   }//end if items are not empty

	     echo $item_list;

 	}
 }




  //getting the list by seller id from dashboard 
 if(isset($_POST['seller_id_empty'])){

 	if(!empty($_POST['seller_id_empty'])){

                   $id=$form_man->cleanString($_POST['seller_id_empty']);

				   //intialise reading variable
				   $item_list="";

				  /*all sellers itemss*/
				   $seller_items=$query_guy->find_products_by_seller($id);

				   $number_of_items=mysqli_num_rows($seller_items);

				   if($number_of_items==0){$item_list='<h4>No Items Match Your Search</h4>';}
				   else{

				         while($item=mysqli_fetch_assoc($seller_items)){
				                 
				                 $item_name=ucfirst($item['PRODUCT_NAME']);
				                 $itemid=$item['PRODUCT_ID'];
				                 $available=$item['AVAILABLE_STOCK'];
				                 $price=$item['PRODUCT_PRICE'];
				                 $description=$item['PRODUCT_DESCRIPTION'];

				                $item_list.='<h4>'.$item_name.'<span class="item-overlay text-right"><a class="btn btn-info" href=\'item-management?item='.$itemid.'\'"><i class="fa fa-check"></i>  Manage</a> <button class="btn btn-info extra"><i class="fa fa-plus"></i>  View Details</button> </span></h4>';
				                $item_list.='<div class="item-details">';
				                $item_list.=' <ul class="nav">';
				                $item_list.='<li>Available Quantity: <span>'.$available.'</span></li>';
				                $item_list.='<li>Price: <b>GHS</b> <span>'.number_format($price,2).'</span></li>';
				                $item_list.='<li>Description: <span>'.substr($description,0,10)."...".'</span></li>';
				                $item_list.='</ul></div>';
				         }//end else

				   }//end if items are not empty

	     echo $item_list;

 	}
 }

 /***************************************************mall search*************************************************************/
  //getting the list by seller id and keyword from dashboard 
 if(isset($_POST['search_item2'])&&isset($_POST['vstr_id'])){

 	if(!empty($_POST['search_item2'])&&!empty($_POST['vstr_id'])){

              
                   $key=$form_man->cleanString($_POST['search_item2']);
                   $vstr_id=$_POST['vstr_id'];
				   //intialise reading variable
				  // $item_list="";
				   $suggest_list="";

				  /*all sellers itemss*/
				   $search_items=$query_guy->find_products_in_mall($key);

				   $number_of_items=mysqli_num_rows($search_items);

				   if($number_of_items==0){$item_list='<h4>No Items Match Your Search</h4>';}
				   else{

				        while($product=mysqli_fetch_assoc($search_items)){

						$product_id=$product['PRODUCT_ID'];
						$product_name=$product['PRODUCT_NAME'];
						$product_price=$product['PRODUCT_PRICE'];

					    //get image
						/*$product_small_image_finder=$query_guy->find_by_id("PRODUCT_SMALL_IMAGE","PDS_ID",$product_id);
						$product_small_image="pro_images_small".DS.$product_small_image_finder['SMALL_IMAGE_FILE'];

						$item_list.='<div class="col-md-3 col-sm-4 main-item-box text-center">
												<div class="item-box">
													<a href="product-detail?detail='.$product_id.'"><img class="img img-responsive" src="'.$product_small_image.'"/></a>
												</div>
												<div class="item-details">
													 <h4>GhC '.$product_price.' <span><img class="loading'.$product_id.'" src="images/mall_loading.gif" style="display:none;"/></span></h4>
												 	 <p>'.ucfirst($product_name).'</p>
												 	 <button class="btn btn-default" onclick="mall_now(\''.$vstr_id.'\',\''.$product_id.'\',\''.$product_price.'\');"><i class="fa fa-shopping-bag"></i> Add To My Bag</button>
											    </div>
									    </div>';
*/
						$suggest_list.='<p><a href="product-detail?detail='.$product_id.'">'.ucfirst($product_name).'</a></p>';

					     }//end while

				   }//end else

	     echo $suggest_list;

 	}
 }


/********update order status********/
if(isset($_POST['order_id'])){

 	if(!empty($_POST['order_id'])){
         
         $or_id=$_POST['order_id'];
         $query=mysqli_query(DB_Connection::$connection,"UPDATE ORDERS SET DELIVERY_STATUS='packaged' WHERE ORDER_ID=".$or_id);

         echo "1";

   }
}

/********update order status if delivered********/
if(isset($_POST['delivery_order_id'])){

 	if(!empty($_POST['delivery_order_id'])){
         
         $or_id=$_POST['delivery_order_id'];
         $query=mysqli_query(DB_Connection::$connection,"UPDATE ORDERS SET DELIVERY_STATUS='delivered' WHERE ORDER_ID=".$or_id);

         echo "1";

   }
}


/********update order status if returned********/
if(isset($_POST['reorder_id'])){

 	if(!empty($_POST['reorder_id'])){
         
         $or_id=$_POST['reorder_id'];
         $query=mysqli_query(DB_Connection::$connection,"UPDATE ORDERS SET DELIVERY_STATUS='returned' WHERE ORDER_ID=".$or_id);

         echo "1";

   }
}

/********update order status if confirmed********/
if(isset($_POST['conorder_id'])){

 	if(!empty($_POST['conorder_id'])){
         
         $or_id=$_POST['conorder_id'];
         $query=mysqli_query(DB_Connection::$connection,"UPDATE ORDERS SET DELIVERY_STATUS='confirmed' WHERE ORDER_ID=".$or_id);

         echo "1";

   }
}


/********update order status if onroute********/
if(isset($_POST['onorder_id'])){

 	if(!empty($_POST['onorder_id'])){
         
         $or_id=$_POST['onorder_id'];
         $query=mysqli_query(DB_Connection::$connection,"UPDATE ORDERS SET DELIVERY_STATUS='on route' WHERE ORDER_ID=".$or_id);

         echo "1";

   }
}

/********update order status if onroute********/
if(isset($_POST['crossorder_id'])){

 	if(!empty($_POST['crossorder_id'])){
         
         $or_id=$_POST['crossorder_id'];
         $query=mysqli_query(DB_Connection::$connection,"UPDATE ORDERS SET DELIVERY_STATUS='crosschecked' WHERE ORDER_ID=".$or_id);

         echo "1";

   }
}


/********update order status if ticket is checked********/
if(isset($_POST['check_order_id'])){

 	if(!empty($_POST['check_order_id'])){
         
         $check_id=$_POST['check_order_id'];
         $query=mysqli_query(DB_Connection::$connection,"UPDATE ORDERS SET DELIVERY_STATUS='checked' WHERE ORDER_ID=".$check_id);

         echo "1";

   }
}

/*send hungry order*/

if(isset($_POST['hungry_name'])&&isset($_POST['hungry_number'])&&isset($_POST['hungry_hall'])&&isset($_POST['hungry_order'])){

 	if(!empty($_POST['hungry_name'])&&!empty($_POST['hungry_number'])&&!empty($_POST['hungry_hall'])&&!empty($_POST['hungry_order'])){
         
        $h_name=$form_man->cleanString($_POST['hungry_name']);
        $h_number=$form_man->cleanString($_POST['hungry_number']);
        $h_hall=$form_man->cleanString($_POST['hungry_hall']);
        $h_order=$form_man->cleanString($_POST['hungry_order']);

        //get number to send sms notification to
        $ptn="/^0/";
        $h_number_new=preg_replace($ptn,"+233",$h_number);

        //echo $h_number_new;

         //get date details
   	      $order_date=strftime("%Y-%m-%d %H:%M:%S", time());
    	  $order_year=date("Y",strtotime($order_date));//eg 2016
    	  $order_month=date("F",strtotime($order_date));//full representation of month
    	  $order_number_date=date("j",strtotime($order_date));//date eg 16,21
    	  $order_week_day=date("l",strtotime($order_date));//full rep of day of the week

    	   //now insert items into orders table
		   	      $order_insert="INSERT INTO HUNGRY_ORDERS(HUNGRY_CUSTOMER_NAME,HUNGRY_CUSTOMER_PHONE,HUNGRY_CUSTOMER_HALL,HUNGRY_ORDER_REQUEST,
		   	      FULL_ORDER_DATE,HUNGRY_ORDER_YEAR,HUNGRY_ORDER_MONTH,HUNGRY_ORDER_NUMBER_DATE,HUNGRY_ORDER_WEEK_DAY,DELIVERY_STATUS) VALUES(";
 				  $order_insert.="'{$h_name}',";
		   	      $order_insert.="'{$h_number}',";
		   	      $order_insert.="'{$h_hall}',";
		   	      $order_insert.="'{$h_order}',";
		   	      $order_insert.="'{$order_date}',";
		   	      $order_insert.="'{$order_year}',";
		   	      $order_insert.="'{$order_month}',";
		   	      $order_insert.="'{$order_number_date}',";
		   	      $order_insert.="'{$order_week_day}',";
		   	      $order_insert.="'pending')";

				  $order_query=mysqli_query(DB_Connection::$connection,$order_insert);

				  if($order_query){

				  		$auth = new BasicAuth("eaabmazg", "kzfchjyo");
						// instance of ApiHost
						$apiHost = new ApiHost($auth);

						// instance of AccountApi
						$accountApi = new AccountApi($apiHost);
						// Get the account profile
						// Let us try to send some message
						$messagingApi = new MessagingApi($apiHost);
						try {
						    // Send a quick message
						    $messageResponse = $messagingApi->sendQuickMessage("Diggimall", "+233206839115", "New order just now! http:ug.diggimall.com/hungry_orders");
						    $messageResponse = $messagingApi->sendQuickMessage("Diggimall", "+233209058871", "New order just now! http:ug.diggimall.com/hungry_orders");
						    $messageResponse = $messagingApi->sendQuickMessage("Diggimall", "+2335543236033", "New order just now! http:ug.diggimall.com/hungry_orders");
						    $messageResponse = $messagingApi->sendQuickMessage("Diggimall", $h_number_new, "Hello ".ucfirst($h_name).", we just saw your order on DiggiMall! We will quickly process it and get back to you in a few minutes. Thank you very much!");
						  

						} catch (Exception $ex) {
						   // echo $ex->getTraceAsString();
						  echo "";
						}
				  	  echo 1;
				  }

				

   }
}

/****************************************************/
/********update order status if delivered********/
if(isset($_POST['delivery_order_id_hunger'])){

 	if(!empty($_POST['delivery_order_id_hunger'])){
         
         $or_id_hunger=$_POST['delivery_order_id_hunger'];
         $query=mysqli_query(DB_Connection::$connection,"UPDATE HUNGRY_ORDERS SET DELIVERY_STATUS='delivered' WHERE HUNGRY_ORDER_ID=".$or_id_hunger);

         echo "1";

   }
}


/********update order status if returned********/
if(isset($_POST['reorder_id_hunger'])){

 	if(!empty($_POST['reorder_id_hunger'])){
         
         $or_id_hunger=$_POST['reorder_id_hunger'];
         $query=mysqli_query(DB_Connection::$connection,"UPDATE HUNGRY_ORDERS SET DELIVERY_STATUS='returned' WHERE HUNGRY_ORDER_ID=".$or_id_hunger);

         echo "1";

   }
}

/********update order status if onroute********/
if(isset($_POST['onorder_id_hunger'])){

 	if(!empty($_POST['onorder_id_hunger'])){
         
         $or_id_hunger=$_POST['onorder_id_hunger'];
         $query=mysqli_query(DB_Connection::$connection,"UPDATE HUNGRY_ORDERS SET DELIVERY_STATUS='on route' WHERE HUNGRY_ORDER_ID=".$or_id_hunger);

         echo "1";

   }
}









 ?>