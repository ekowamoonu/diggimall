<?php ob_start();

/*setcookie("shopper_name","",time()-10);
setcookie("shopper_id","",time()-10);*/

/*$shoppers_name="Awura";
$shoppers_id=3;*/

include("inc/cookie_checker.php");


/*tutorials*/
/*if(!isset($_COOKIE['tuts'])){

	setcookie("tuts","tutorials",time()+2628002);
	header("Location: howtoshop");
}else{$a=0;}
*/

/*checker to wishlist heart if user is logged in or hide if user is not logged in*/
if(isset($_COOKIE['logged_in'])){
   $buyer_id=(int)decryptCookie($_COOKIE['logged_in']);
   $wishy_flag=true;
}else{
  $wishy_flag=false;
}

//welcome greetings
if(date("H")<12){
	$greeting="Good Morning";
}
elseif(date("H")>=12&&date("H")<=16){
	$greeting="Good Afternoon";
}
else{
	$greeting="Good Evening";
}

//night market and pharmaceuticals closed till tomorrow
if(date("D")=="Sun"||date("H")>=19){
   
   $nightmarket="<div class='container-fluid' style='background-color:#C0392B;color:white;'><div class='row'><div class='col-md-12'><marquee><h4 class='text-left;' style='font-weight:bold;'><i class='fa fa-file-o'></i> Delivery for Drugs & Night Market Meals Resume Tomorrow</h4></marquee></div></div></div>";

}elseif(date("H")<=8){
   $nightmarket="<div class='container-fluid' style='background-color:#C0392B;color:white;'><div class='row'><div class='col-md-12'><marquee><h4 class='text-left;' style='font-weight:bold;'><i class='fa fa-file-o'></i> Delivery for Drugs & Night Market Meals Begin at 9:00am</h4></marquee></div></div></div>";
}
else{
    
   $nightmarket="";
}

 /*$nightmarket="<div class='container-fluid' style='background-color:#337ab7;color:white;'><div class='row'><div class='col-md-12'><marquee><h4 class='text-left;' style='font-weight:bold;'><i class='fa fa-file-o'></i> Delivery for Drugs & Night Market Meals Resume Tomorrow</h4></marquee></div></div></div>";*/



include('functions.php'); 
include('conn'.DS.'db_connection.php'); 
include('classes'.DS.'querying_class.php');

$connection=new DB_Connection();
$query_guy=new DataQuery();




/*
   1.get all maincategories 
   2.get all sub-categories using ajax based on an on onchange event
   3.get all trending items based on othered quantities
   4.get all more to love items based on users initial purchase
   --if initial purchase cookie is not set, select random from the database
   5.get all latest arrivals based on the product upload date
   6.get all featured sellers based the seller_status
*/

   /*get all main categories*/
   $main_categories=$query_guy->find_all_main_categories("MAIN_CATEGORY");

   $main_list="";
   $picture_list="";
   while($main_results=mysqli_fetch_assoc($main_categories)){
     $main_cat_name=$main_results['MAIN_CATEGORY_NAME'];
     $main_cat_id=$main_results['MAIN_CATEGORY_ID'];
     $main_cat_image=$main_results['MAIN_CATEGORY_IMAGE'];

     $main_list.='<option value="'.$main_cat_id.'">'.ucfirst($main_cat_name).'</option>';

     $picture_list.='<div class="col-xs-12">
     					<a href="mall?main_cat='.$main_cat_id.'&sub_cat=default">
			             <div class="category-image">
                             <img class="img-responsive" src="images/category_images/'.$main_cat_image.'"/>
				             <p>'.ucfirst($main_cat_name).'</p>
			             </div>
			            </a>
		            </div>
                ';
   }

    /*get random categories for first column*/
   $main_random_categories=mysqli_query(DB_Connection::$connection,"SELECT * FROM MAIN_CATEGORY ORDER BY RAND() LIMIT 4");
   
   $first_column_random_categories="";
   $second_column_random_categories="";
   $limit_counter=0;
   while($main_results=mysqli_fetch_assoc($main_random_categories)){
     $main_cat_name=$main_results['MAIN_CATEGORY_NAME'];
     $main_cat_id=$main_results['MAIN_CATEGORY_ID'];
     $main_cat_image=$main_results['MAIN_CATEGORY_IMAGE'];

    if($limit_counter>1){

    	$second_column_random_categories.='<div class="row">
					 					<div class="col-md-12">
					 						<a href="mall?main_cat='.$main_cat_id.'&sub_cat=default">
								             <div class="category-image">
					                             <img class="img-responsive" src="images/category_images/'.$main_cat_image.'"/>
				            					 <p>'.ucfirst($main_cat_name).'</p>
								             </div>
								            </a>
					 					</div>
					 				</div>';

    }else{

    	 $first_column_random_categories.='<div class="row">
					 					<div class="col-md-12">
					 						<a href="mall?main_cat='.$main_cat_id.'&sub_cat=default">
								             <div class="category-image">
					                             <img class="img-responsive" src="images/category_images/'.$main_cat_image.'"/>
				            					 <p>'.ucfirst($main_cat_name).'</p>
								             </div>
								            </a>
					 					</div>
					 				</div>';

    }
   

      $limit_counter++;
  }


/*selecting hottest product*/
$hottest=mysqli_query(DB_Connection::$connection,"SELECT * FROM PRODUCTS ORDER BY TOTAL_ORDERED_QUANTITY DESC LIMIT 1");
$hottest_item=mysqli_fetch_assoc($hottest);

$hottest_item_id=$hottest_item['PRODUCT_ID'];
$hottest_item_seller_id=$hottest_item['SEL_ID'];
$hottest_item_name=$hottest_item['PRODUCT_NAME'];

//get hottest item image
$get_hottest_item_image=$query_guy->find_by_id("PRODUCT_SMALL_IMAGE",'PDS_ID',$hottest_item_id);
$hottest_small_image="pro_images_small".DS.$get_hottest_item_image['SMALL_IMAGE_FILE'];

//get hottest item seller
$get_hottest_item_seller_name=$query_guy->find_by_id("SELLERS",'SELLER_ID',$hottest_item_seller_id);
$hottest_item_seller=ucfirst($get_hottest_item_seller_name['SELLER_NAME']);

//get product price and discount
$hottest_price=$hottest_item['PRODUCT_PRICE'];
$hottest_discount=$hottest_item['DISCOUNT'];


    if(empty($hottest_discount)||$hottest_discount==null||$hottest_discount=='null'||$hottest_discount==0){
		        
		        $hnormal_price=$hottest_price;
		        $hottest_pricing='<span class="selling-price">GH&#162;'.number_format($hnormal_price,2).'</span>';
		    }

		    else if($hottest_discount>0){

                    //if there is a discount then the price in the db is the discounted price
		           $hactual_discount=$hottest_discount*100;//get original percentage from decimal
		           $hnormal_price=$hottest_price*100/(100-$hactual_discount);
		           $hnew_price=$hottest_price;
		           $hottest_pricing='<span class="cancelled-price">GH&#162; '.number_format($hnormal_price,2).' </span><span class="selling-price"><b>GH&#162; '.number_format($hnew_price,2).'</b></span>';

                  }

/*************************************************************************************************************/

/***********************************TRENDING ITEMS***********************************************/
//limit 8
$trending_items='<ul class="thumbnails">';
$find_trending_query=mysqli_query(DB_Connection::$connection,"SELECT * FROM PRODUCTS ORDER BY TOTAL_ORDERED_QUANTITY DESC LIMIT 8");
$i=0;
	while($trending=mysqli_fetch_assoc($find_trending_query)){

		$trending_id=$trending['PRODUCT_ID'];
		$trending_name=(strlen($trending['PRODUCT_NAME'])>20)?substr($trending['PRODUCT_NAME'],0,27)."...":$trending['PRODUCT_NAME'];
		$trending_price=$trending['PRODUCT_PRICE'];

		/*get sellers name*/
		$product_seller=$trending['SEL_ID'];
	    $seller_finder=$query_guy->find_by_id("SELLERS","SELLER_ID",$product_seller);
		$seller_name=strtoupper($seller_finder['SELLER_NAME']);

		/********************DISCOUNTS AND PRICING*********************/

		  $db_price=$trending['PRODUCT_PRICE'];
		  $product_discount=$trending['DISCOUNT'];

		   //discount checks
		    if(empty($product_discount)||$product_discount==null||$product_discount=='null'||$product_discount==0){
		        
		        $normal_price=$db_price;
		        $discount="";
		        $pricing='<span class="selling-price">GH&#162;'.number_format($normal_price,2).'</span>';
		    }

		    else if($product_discount>0){

		           $actual_discount=$product_discount*100;//get original percentage from decimal
		           $discount='<div class="discount-percentage"><span class="inner border-radius">'.$actual_discount.'% Off!</span></div>';
		           $normal_price=$db_price*100/(100-$actual_discount);
		           $new_price=$db_price;
		           $pricing='<span class="cancelled-price">GH&#162; '.number_format($normal_price,2).' </span> <span class="selling-price">GH&#162; '.number_format($new_price,2).'</span>';

		    }

		/****************END PRICING**************************/


	   /*adding to wishlist*/
	   $add_to_wishlist= ($wishy_flag)?'<span onclick="add_to_wishlist(\''.$trending_id.'\',\''.$buyer_id.'\');" class="wishy w'.$trending_id.'" title="Add to my Wishlist"><i class="fa fa-heart fa-2x"></i></span>':"";

	    //get image
		$trending_small_image_finder=$query_guy->find_by_id("PRODUCT_SMALL_IMAGE","PDS_ID",$trending_id);
		$trending_small_image="pro_images_small".DS.$trending_small_image_finder['SMALL_IMAGE_FILE'];

		$i++;

		  //check if number is 5
		if($i==4){

			$trending_items.=' <li class="col-md-3 col-lg-3 col-sm-3">
									<div class=" text-center">
										<div class="item-box">'.$add_to_wishlist.'
										    '.$discount.'
											<a href="product-detail?detail='.$trending_id.'"><img class="img img-responsive" src="'.$trending_small_image.'"/></a>
										</div>
										<div class="item-details">
											<h5 >'.$pricing.'</h5>
									 	     <p>'.ucfirst($trending_name).'</p>
									 	     <p class="sellers-name">'.$seller_name.'</p>
									    </div>
										</div>
									</li> 	
								</ul>
						   </div>
						 <div class="item">
                           <ul class="thumbnails">';
				

		  }//end if i=4
		  else if($i==8){

		  	  	$trending_items.=' <li class="col-md-3 col-lg-3 col-sm-3">
									<div class=" text-center">
										<div class="item-box">'.$add_to_wishlist.'
										'.$discount.'
											<a href="product-detail?detail='.$trending_id.'"><img class="img img-responsive" src="'.$trending_small_image.'"/></a>
										</div>
										<div class="item-details">
											<h5 >'.$pricing.'</h5>
									 	     <p>'.ucfirst($trending_name).'</p>
									 	     <p class="sellers-name">'.$seller_name.'</p>
									    </div>
									</div>
								  </li>
								</ul>';


		  }//end if i=8
		  else{

		  	 $trending_items.=' <li class="col-md-3 col-lg-3 col-sm-3">
									<div class=" text-center">
										<div class="item-box">'.$add_to_wishlist.'
										'.$discount.'
											<a href="product-detail?detail='.$trending_id.'"><img class="img img-responsive" src="'.$trending_small_image.'"/></a>
										</div>
										<div class="item-details">
											<h5 >'.$pricing.'</h5>
									 	     <p>'.ucfirst($trending_name).'</p>
									 	     <p class="sellers-name">'.$seller_name.'</p>
									    </div>
									</div>
								</li>';



		  }

    }//end while loop




/***********************************************************************************************/
/***********************************MORE TO LOVE***********************************************/
$recommended_items="";
if(isset($_COOKIE['recommended_sub'])){

	$rec_id=mysqli_real_escape_string(DB_Connection::$connection,$_COOKIE['recommended_sub']);
    
    $find_recommended_query=mysqli_query(DB_Connection::$connection,"SELECT * FROM PRODUCTS WHERE SUB_CAT_ID=".$rec_id." LIMIT 4");

	while($recommended=mysqli_fetch_assoc($find_recommended_query)){

		$recommended_id=$recommended['PRODUCT_ID'];
		$recommended_name=(strlen($recommended['PRODUCT_NAME'])>20)?substr($recommended['PRODUCT_NAME'],0,27)."...":$recommended['PRODUCT_NAME'];
		$recommended_price=$recommended['PRODUCT_PRICE'];

		/*get sellers name*/
		$rproduct_seller=$recommended['SEL_ID'];
	    $rseller_finder=$query_guy->find_by_id("SELLERS","SELLER_ID",$rproduct_seller);
		$rseller_name=strtoupper($rseller_finder['SELLER_NAME']);

		/********************DISCOUNTS AND PRICING*********************/

		  $db_price=$recommended['PRODUCT_PRICE'];
		  $product_discount=$recommended['DISCOUNT'];

		   //discount checks
		    if(empty($product_discount)||$product_discount==null||$product_discount=='null'||$product_discount==0){
		        
		        $normal_price=$db_price;
		        $discount="";
		        $pricing='<span class="selling-price">GH&#162;'.number_format($normal_price,2).'</span>';
		    }

		    else if($product_discount>0){

		           $actual_discount=$product_discount*100;//get original percentage from decimal
		           $discount='<div class="discount-percentage"><span class="inner border-radius">'.$actual_discount.'% Off!</span></div>';
		           $normal_price=$db_price*100/(100-$actual_discount);
		           $new_price=$db_price;
		           $pricing='<span class="cancelled-price">GH&#162; '.number_format($normal_price,2).' </span> <span class="selling-price">GH&#162; '.number_format($new_price,2).'</span>';

		    }

		/****************END PRICING**************************/
		 /*adding to wishlist*/
	   $add_to_wishlist= ($wishy_flag)?'<span onclick="add_to_wishlist(\''.$recommended_id.'\',\''.$buyer_id.'\');" class="wishy w'.$recommended_id.'" title="Add to my Wishlist"><i class="fa fa-heart fa-2x"></i></span>':"";

	    //get image
		$recommended_small_image_finder=$query_guy->find_by_id("PRODUCT_SMALL_IMAGE","PDS_ID",$recommended_id);
		$recommended_small_image="pro_images_small".DS.$recommended_small_image_finder['SMALL_IMAGE_FILE'];

		$recommended_items.='<div class="col-md-3 col-sm-3 main-item-box text-center">
							<div class="item-box">'.$add_to_wishlist.'
							'.$discount.'
								<a href="product-detail?detail='.$recommended_id.'"><img class="img img-responsive" src="'.$recommended_small_image.'"/></a>
							</div>
							<div class="item-details">
								 <h5 >'.$pricing.'</h5>
							 	 <p>'.ucfirst($recommended_name).'</p>
							 	 <p class="sellers-name">'.$rseller_name.'</p>
						    </div>
						</div>';

    }

}//if recommeded cookie has been set
else{
    
    $find_recommended_query=mysqli_query(DB_Connection::$connection,"SELECT * FROM PRODUCTS ORDER BY RAND() LIMIT 4");

	while($recommended=mysqli_fetch_assoc($find_recommended_query)){

		$recommended_id=$recommended['PRODUCT_ID'];
		$recommended_name=(strlen($recommended['PRODUCT_NAME'])>20)?substr($recommended['PRODUCT_NAME'],0,27)."...":$recommended['PRODUCT_NAME'];
		$recommended_price=$recommended['PRODUCT_PRICE'];

		/*get sellers name*/
		$rproduct_seller=$recommended['SEL_ID'];
	    $rseller_finder=$query_guy->find_by_id("SELLERS","SELLER_ID",$rproduct_seller);
		$rseller_name=strtoupper($rseller_finder['SELLER_NAME']);

			/********************DISCOUNTS AND PRICING*********************/

		  $db_price=$recommended['PRODUCT_PRICE'];
		  $product_discount=$recommended['DISCOUNT'];

		   //discount checks
		    if(empty($product_discount)||$product_discount==null||$product_discount=='null'||$product_discount==0){
		        
		        $normal_price=$db_price;
		        $discount="";
		        $pricing='<span class="selling-price">GH&#162;'.number_format($normal_price,2).'</span>';
		    }

		    else if($product_discount>0){

		           $actual_discount=$product_discount*100;//get original percentage from decimal
		           $discount='<div class="discount-percentage"><span class="inner border-radius">'.$actual_discount.'% Off!</span></div>';
		           $normal_price=$db_price*100/(100-$actual_discount);
		           $new_price=$db_price;
		           $pricing='<span class="cancelled-price">GH&#162; '.number_format($normal_price,2).' </span> <span class="selling-price">GH&#162; '.number_format($new_price,2).'</span>';

		    }

		/****************END PRICING**************************/

		 /*adding to wishlist*/
	   $add_to_wishlist= ($wishy_flag)?'<span onclick="add_to_wishlist(\''.$recommended_id.'\',\''.$buyer_id.'\');" class="wishy w'.$recommended_id.'" title="Add to my Wishlist"><i class="fa fa-heart fa-2x"></i></span>':"";

	    //get image
		$recommended_small_image_finder=$query_guy->find_by_id("PRODUCT_SMALL_IMAGE","PDS_ID",$recommended_id);
		$recommended_small_image="pro_images_small".DS.$recommended_small_image_finder['SMALL_IMAGE_FILE'];

		$recommended_items.='<div class="col-md-3 col-sm-3 main-item-box text-center">
							<div class="item-box">'.$add_to_wishlist.'
							'.$discount.'
								<a href="product-detail?detail='.$recommended_id.'"><img class="img img-responsive" src="'.$recommended_small_image.'"/></a>
							</div>
							<div class="item-details">
								 <h5 >'.$pricing.'</h5>
							 	 <p>'.ucfirst($recommended_name).'</p>
							 	  <p class="sellers-name">'.$rseller_name.'</p>
						    </div>
						</div>';

    }//else while loop
}






/*********************************latest arrivals***********************************************/
$latest_items="";
$find_latest_query=mysqli_query(DB_Connection::$connection,"SELECT * FROM PRODUCTS ORDER BY UPLOAD_DATE DESC LIMIT 4");

while($latest=mysqli_fetch_assoc($find_latest_query)){

	$latest_id=$latest['PRODUCT_ID'];
	$latest_name=(strlen($latest['PRODUCT_NAME'])>20)?substr($latest['PRODUCT_NAME'],0,27)."...":$latest['PRODUCT_NAME'];
	$latest_price=$latest['PRODUCT_PRICE'];

	/*get sellers name*/
	$lproduct_seller=$latest['SEL_ID'];
	$lseller_finder=$query_guy->find_by_id("SELLERS","SELLER_ID",$lproduct_seller);
	$lseller_name=strtoupper($lseller_finder['SELLER_NAME']);

		/********************DISCOUNTS AND PRICING*********************/

		  $db_price=$latest['PRODUCT_PRICE'];
		  $product_discount=$latest['DISCOUNT'];

		   //discount checks
		    if(empty($product_discount)||$product_discount==null||$product_discount=='null'||$product_discount==0){
		        
		        $normal_price=$db_price;
		        $discount="";
		        $pricing='<span class="selling-price">GH&#162;'.number_format($normal_price,2).'</span>';
		    }

		    else if($product_discount>0){

		           $actual_discount=$product_discount*100;//get original percentage from decimal
		           $discount='<div class="discount-percentage"><span class="inner border-radius">'.$actual_discount.'% Off!</span></div>';
		           $normal_price=$db_price*100/(100-$actual_discount);
		           $new_price=$db_price;
		           $pricing='<span class="cancelled-price">GH&#162; '.number_format($normal_price,2).' </span> <span class="selling-price">GH&#162; '.number_format($new_price,2).'</span>';

		    }

		/****************END PRICING**************************/

		 /*adding to wishlist*/
	   $add_to_wishlist= ($wishy_flag)?'<span onclick="add_to_wishlist(\''.$latest_id.'\',\''.$buyer_id.'\');" class="wishy w'.$latest_id.'" title="Add to my Wishlist"><i class="fa fa-heart fa-2x"></i></span>':"";

    //get image
	$latest_small_image_finder=$query_guy->find_by_id("PRODUCT_SMALL_IMAGE","PDS_ID",$latest_id);
	$latest_small_image="pro_images_small".DS.$latest_small_image_finder['SMALL_IMAGE_FILE'];

	$latest_items.='<div class="col-md-3 col-sm-3 main-item-box text-center">
						<div class="item-box">'.$add_to_wishlist.'
						'.$discount.'
							<a href="product-detail?detail='.$latest_id.'"><img class="img img-responsive" src="'.$latest_small_image.'"/></a>
						</div>
						<div class="item-details">
							 <h5 >'.$pricing.'</h5>
						 	 <p>'.ucfirst($latest_name).'</p>
						 	 <p class="sellers-name">'.$lseller_name.'</p>
					    </div>
					</div>';

}



/**/

?>

<?php include("inc/header.php"); ?>
<link rel="stylesheet" href="css/index_main.css"/>
<link rel="stylesheet" href="css/nav.css"/>
<title>Diggimall - Shop Amazing Items At Affordable Prices In A Classy Experience</title>

</head>


 <body>

 <!--site navigation-->
 <?php include("inc/nav.php"); ?>
 <?php echo $nightmarket; ?>

 <!--body-->

 <!--small screen sizes-->
 <!--welcome message-->
 <div class="container-fluid welcome-greeting hidden-sm hidden-md hidden-lg">
 	<section class="row">
 		<div class="col-xs-12 text-center">
 			<p><?php echo $greeting." ".ucfirst($shoppers_name); ?>! What Would You Like To Buy?</p>
 		</div>
 		<div class="col-xs-12">
 				<form id="small_form" action="mall" class="form-inline child" method="get">

					<div class="form-group">
					   <select class="form-control"  id="main_categories_small" name="main_cat" title="Shop For..."> <!-- class="selectpicker" -->
							<option value="default">Item Categories...</option>
							<?php echo $main_list; ?>
						</select>
					</div>

					<div class="form-group">
						<select class="form-control"  id="specific_item_small" name="sub_cat" title="Specific Item...">
							<option value="default">Narrow Your Selection...</option>
						]</select>
					</div>

					<div class="form-group">
						<input type="submit" name="shop_now" class="btn btn-info btn-block" value="Shop Now"/>

					</div>

				 </form>

 		</div>
 	</section>
 </div>
<!--end welcome message-->

<!--main category listings-->
<div class="container-fluid category-listings-container hidden-sm hidden-md hidden-lg">
	<section class="row text-center">
		<h4 style="padding:0;margin:0;padding-bottom:5px;">Or Browse By Our Categories</h4>
		<p style="font-size:30px;margin:0;"><i class="fa fa-angle-down"></i></p>
	</section>
	<section class="row">

  <?php echo $picture_list; ?>

	</section>
</div>

<!--end listings-->

 <!--end small screen sizes-->

 <!--large screen sizes form-->

<div class="container-fluid welcome-question-container hidden-xs">
	<section class="row">
		<div class="col-md-4">
			<p> <?php echo $greeting." ".ucfirst($shoppers_name); ?>! What Would You Like To Buy?</p>
		</div>
		<div class="col-md-8">
			<div class="row">
				<form id="quick_search_form" action="mall" class="form-inline child" method="get">

					<div class="col-md-4">
						<div class="form-group">
							<select class="form-control" id="main_categories" name="main_cat" title="Shop For..."> <!-- class="selectpicker" -->
							  <option value="default">Item Departments...</option>
								            	<?php echo $main_list; ?>
							</select>
						</div>
					</div>

					<div class="col-md-4">
						 <div class="form-group">
							<select class="form-control" id="specific_item" name="sub_cat" title="Specific Item...">
								<option value="default">Narrow Your Selection...</option>
							 </select>
					     </div>
					</div>

					<div class="col-md-4">
						<input type="submit" name="shop_now" class="btn btn-info" value="Shop Now"/>
					</div>

				</form>
			</div>
		</div><!--end col-md-12-->
	</section>
</div>

 <!--end large screen sizes-->



 <!--slider-container-->
 <div class="container-fluid slider-container hidden-xs">
 	<div class="row">
 		<div class="col-md-12">
 		  <div class="slider-box">
            <div id="slider-carousel" class="carousel slide" data-ride="carousel">
					   <ol class="carousel-indicators hidden-sm hidden-xs">
						  <li data-target="#slider-carousel" data-slide-to="0" class="active"></li>
						  <li data-target="#slider-carousel" data-slide-to="1"></li>
						  <li data-target="#slider-carousel" data-slide-to="2"></li>
					    </ol>
								
						 <div class="carousel-inner main-carousel">

							 			<div class="item active hidden-xs hidden-sm active">
											 <a href="mall?main_cat=2&&sub_cat="><img src="ads/womensfashion.jpg" class="img img-responsive"/></a>
										</div>

										<div class="item hidden-xs hidden-sm">
											<a href="mall?main_cat=&&sub_cat=33"><img src="ads/nightmarket.jpg" class="img img-responsive"/></a>
										</div>
													
										<div class="item hidden-xs hidden-sm">
											<a href="mall?main_cat=7&&sub_cat="><img src="ads/electronics3.jpg" class="img img-responsive"/></a>
										</div> 

										<a href="#slider-carousel" class="left control-carousel" data-slide="prev">
										  <i class="fa fa-angle-left"></i>
										</a>
										<a href="#slider-carousel" class="right control-carousel" data-slide="next">
										   <i class="fa fa-angle-right"></i>
										</a>
											
					     </div> <!--end carousel inner-->
								
									
			 </div> <!--end slider carousel-->
            
          </div>
 		</div>
 	</div>
 </div>
 <!--end slider container-->

 <!--large screen sizes category listings-->
 <div class="container-fluid below-slider-div hidden-xs">
 	<section class="row">
 		<div class="col-md-3">
 			<div class="welcome-signup text-center">
 				<h2>Welcome <?php echo ucfirst($shoppers_name); ?></h2>
 				<p>Sign In For The Best Shopping Experience</p>
 				<a href="user-registration" class="btn btn-info"><i class="fa fa-lock"></i> Sign In Securely</a>
 			</div>
 		</div>
 		<div class="col-md-3">

 			<div class="popular-categories">
              
              <h3>Popular departments</h3>

              <?php echo $first_column_random_categories; ?>

 				<!-- <div class="row">
 					<div class="col-md-12">
 						<a href="mall?main_cat='.$main_cat_id.'&sub_cat=default">
			             <div class="category-image">
                             <img class="img-responsive" src="images/category_images/computing.jpg"/>
				             <p>Electronics</p>
			             </div>
			            </a>
 					</div>
 				</div>-->

 		

 			</div><!--end first popular categories-->

 		</div><!--end first popular cat column-->
 		<div class="col-md-3">
 			<div class="popular-categories">
              
              <h3>Other departments</h3>

 				
              <?php echo $second_column_random_categories; ?>

 				<!-- <div class="row">
 					<div class="col-md-12">
 						<a href="mall?main_cat='.$main_cat_id.'&sub_cat=default">
			             <div class="category-image">
                             <img class="img-responsive" src="images/category_images/computing.jpg"/>
				             <p>Electronics</p>
			             </div>
			            </a>
 					</div>
 				</div>-->

 			</div><!--end first popular categories-->
 		</div><!--end second popular cat column-->
 		<div class="col-md-3">
 			<div class="row most-popular-item">
 				<div class="col-md-12">
 					<div class="popular-categories-2a popular">
 						<h3>Hottest Deal</h3>
 						<div class="hottest-product">
 							<a href="product-detail?detail=<?php echo $hottest_item_id; ?>"><img src="<?php echo $hottest_small_image; ?>" class="img-responsive"/></a>
 						</div>
 					 </div>
 				</div>
 		    </div>
 		    <div class="row">
 				<div class="col-md-12">
 					<div class="popular-categories-2b popular">
 						<p><b><?php echo (strlen($hottest_item_name)>26)?substr($hottest_item_name,0,16)."..":$hottest_item_name; ?></b></p>
 						<p>Damage: <?php echo $hottest_pricing; ?></p>
 						<p>Vendor: <a href="vendor?shop=<?php echo $lproduct_seller; ?>"><?php echo $hottest_item_seller; ?></a></p>
 			        </div>
 				</div>
 		    </div>
 	     </div>
 	</section>
 </div>


 <!--end lss category listings-->

<!--treding items head-->
 <div class="container hot-items-container">
 	<div class="row">
 		<div class="col-md-12">
 			<h3>Trending Items</h3>
 		</div>
 	</div>
 </div>


<!--trending items slider-->
<div class="container trending-items-slider-container">
	<div class="row">
		 <div id="trendingCarousel" class="trendingCarousel carousel slide" data-ride="carousel">
              <div class="carousel-inner">
                            

                      <div class="item active">
                        <?php echo $trending_items; ?>
					 </div><!--end second item-->
        <a class="left carousel-control" href="#trendingCarousel" role="button" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
        <a class="right carousel-control" href="#trendingCarousel" role="button" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>

	     </div><!--end carousel inner-->
       </div><!--end trendinfg carousel-->
	</div><!--end row-->
</div><!--end trending main container-->


<!--ad space-->

<div class="container-fluid ad-container">
	<div class="row">
		<div class="ad-box">
		</div>
	</div>
</div>

<!--end ad space-->


<!--recommended items head-->
 <div class="container-fluid recommended-items-container">
 	<div class="row">
 		<div class="col-md-12 text-center">
 			<h4>Recommended for You</h4>
 	   </div>
 	</div>
 </div>


<!--recommended items slider-->
<div class="container trending-items-slider-container">
	<div class="row">
		<?php echo $recommended_items; ?>
	</div>
</div>


<!--ad space-->

<div class="container-fluid ad-container">
	<div class="row">
		<div class="ad-box">
		</div>
	</div>
</div>

<!--end ad space-->



<!--latest items head-->
 <div class="container-fluid recommended-items-container">
 	<div class="row">
 		<div class="col-md-12 text-center">
 			<h4>Latest Arrivals</h4>
 	   </div>
 	</div>
 </div>


<!--latest items container-->
<div class="container trending-items-slider-container">
	<div class="row">
		<?php echo $latest_items; ?>
	</div>
</div>


<!--featured sellers-->
<!--  <div class="container recommended-items-container">
 	<div class="row">
 		<div class="col-md-12">
 			<h3>Featured Sellers</h3>
 			<a href="#" class="btn btn-default pull-right">Become A Featured Seller</a>
 		</div>
 	</div>
 </div> -->


<!--featured sellers slider-->
 <!-- <div class="container trending-items-slider-container">
	<div class="row">
		 <div id="featuredsellers" class="featuredsellers carousel slide" data-ride="carousel">
              <div class="carousel-inner">
                            

                      <div class="item active">
                         <ul class="thumbnails">
                            <li class="col-md-3 col-lg-3 col-sm-3">
								<div class=" text-center">
									<div class="item-box">
										<a href="#"><img class="img img-responsive" src="images/seller1.jpg"/></a>
										
									</div>
									<div class="item-details">
									 <h5 >600 items</h5>
								 	 <p>Kwame Djekson</p>
								    
								    </div>
								</div>
							</li>

							<li class="col-md-3 col-lg-3 col-sm-3">
								<div class="text-center">
									<div class="item-box">
										<a href="#"><img class="img img-responsive"  src="images/seller2.jpg"/></a>
										
									</div>
									<div class="item-details">
									 <h5 >70 items</h5>
								 	 <p>Ama Jonah</p>
								    
								    </div>
								</div>
							</li>

							<li class="col-md-3 col-lg-3 col-sm-3">
								<div class="text-center">
									<div class="item-box">
										<a href="#"><img class="img img-responsive"  src="images/seller3.jpg"/></a>
										
									</div>
									<div class="item-details">
									 <h5 >100 items</h5>
								 	 <p>Ekow Mensah</p>
								    
								    </div>
								</div>
							</li>

							<li class="col-md-3 col-lg-3 col-sm-3">
								<div class="text-center">
									<div class="item-box">
										<a href="#"><img class="img img-responsive"  src="images/seller4.jpg"/></a>
										
									</div>
									<div class="item-details">
									 <h5 >10 items</h5>
								 	 <p>Sandra Quayson</p>
								    
								    </div>
								</div>
							</li>
						</ul> -->
					<!-- </div> --><!--end first item active

					 <div class="item">
                         <ul class="thumbnails">
                            <li class="col-md-3 col-lg-3 col-sm-3">
								<div class=" text-center">
									<div class="item-box">
										<a href="#"><img class="img img-responsive" src="images/seller1.jpg"/></a>
										
									</div>
									<div class="item-details">
									 <h5 >600 items</h5>
								 	 <p>Kwame Djekson</p>
								    
								    </div>
								</div>
							</li>

							<li class="col-md-3 col-lg-3 col-sm-3">
								<div class="text-center">
									<div class="item-box">
										<a href="#"><img class="img img-responsive"  src="images/seller2.jpg"/></a>
										
									</div>
									<div class="item-details">
									 <h5 >70 items</h5>
								 	 <p>Ama Jonah</p>
								    
								    </div>
								</div>
							</li>

							<li class="col-md-3 col-lg-3 col-sm-3">
								<div class="text-center">
									<div class="item-box">
										<a href="#"><img class="img img-responsive"  src="images/seller3.jpg"/></a>
										
									</div>
									<div class="item-details">
									 <h5 >100 items</h5>
								 	 <p>Ekow Mensah</p>
								    
								    </div>
								</div>
							</li>

							<li class="col-md-3 col-lg-3 col-sm-3">
								<div class="text-center">
									<div class="item-box">
										<a href="#"><img class="img img-responsive"  src="images/seller4.jpg"/></a>
										
									</div>
									<div class="item-details">
									 <h5 >10 items</h5>
								 	 <p>Sandra Quayson</p>
								    
								    </div>
								</div>
							</li>
						</ul> -->
					<!-- </div> --><!--end second item-->
        <!-- <a class="left carousel-control" href="#featuredsellers" role="button" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
        <a class="right carousel-control" href="#featuredsellers" role="button" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a> -->

	     <!-- </div> --><!--end carousel inner-->
       <!-- </div> --><!--end trendinfg carousel-->
	<!-- </div> --><!--end row-->
<!-- </div> --><!--end trending main container-->



<!--ad space-->

<div class="container-fluid ad-container">
	<div class="row">
		<div class="ad-box">
		</div>
	</div>
</div>

<!--end ad space-->

<!--hungry much navbar fixed-->
<nav role="navigation" class="navbar navbar-default navbar-fixed-bottom hidden-md hidden-lg" id="hungry_nav">
	<div class="container-fluid">
		<div class="row">
			    <div class="col-xs-5">
					<img src="images/hungry_bowl.png" class="img img-responsive"/>
				</div>
				<div class="col-xs-7 text-center">
					<p><button class="btn btn-danger"><i style="color:white;" class="fa fa-bullseye fa-2x fa-spin"></i> <br/> Hungry? Tap Here</button>  </p>
				</div>
		</div>
	</div>
</nav>


<!--hungry form-->
<div class="dark-opacity">
	<div class="white-div">
		<div class="image-div">
			<i class="fa fa-times close_dark_opacity"></i>
			<img src="images/bowl_of_food/bowl<?php echo rand(1,6); ?>.gif" class="img img-responsive"/>
			<p class="text-center">Clearly state the food order you want</p>
		</div>
		<div class="details-needed">

			<div class="form-group"><input type="text" class="form-control hungry_name"  placeholder="Your name"/></div>
			<div class="form-group"><input type="text" class="form-control hungry_number" placeholder="Your phone number"/></div>
			<div class="form-group"> <select class="form-control hungry_hall">
                            <optgroup label="To My Hall">
                            <option value="default">Your hall</option>
                            <option value="Jean Nelson Aka">Jean Nelson Aka</option>
                            <option value="Alex Kwapong">Alex Kwapong</option>
                            <option value="Hilla Limann">Hilla Limann</option>
                            <option value="Elizabeth Sey">Elizabeth Sey</option>
                            <option value="Ish 1">International Students Hostel 1</option>
                            <option value="Ish 2">International Students Hostel 2</option>
                            <option value="Jubilee">Jubilee</option>
                            <option value="Pentagon Blk A">Pentagon Blk A</option>
		                    <option value="Pentagon Blk B">Pentagon Blk B</option>
		                    <option value="Pentagon Blk C">Pentagon Blk C</option>
                            <option value="Bani">Bani</option>
                            <option value="Evandy">Evandy</option>
                            <option value="TF">TF</option>
                            <option value="Volta">Volta</option>
                            <option value="Sarbah Hall Main">Sarbah Hall (Main)</option>
                            <option value="Sarbah Hall Annex A">Sarbah Hall (Annex A)</option>
                            <option value="Sarbah Hall Annex B">Sarbah Hall (Annex B)</option>
                            <option value="Sarbah Hall Annex C">Sarbah Hall (Annex C)</option>
                            <option value="Sarbah Hall Annex D">Sarbah Hall (Annex D)</option>
                            <option value="Akuafo Hall Main">Akuafo Hall (Main)</option>
                            <option value="Akuafo Hall Annex A">Akuafo Hall (Annex A)</option>
                            <option value="Akuafo Hall Annex B">Akuafo Hall (Annex B</option>
                            <option value="Akuafo Hall Annex C">Akuafo Hall (Annex C)</option>
                            <option value="Akuafo Hall Annex D">Akuafo Hall (Annex D)</option>
                            <option value="Legon Hall Main">Legon Hall (Main)</option>
                            <option value="Legon Hall Annex A">Legon Hall (Annex A)</option>
                            <option value="Legon Hall Annex B">Legon Hall (Annex B)</option>
                            <option value="Legon Hall Graduate Hostel">Legon Hall (Graduate Hostel)</option>
                            <option value="Valco">Valco Trust Hostel</option>
                            <option value="Commonwealth">Commonwealth</option>
                          </optgroup>
                          <optgroup label="To My Lecture Hall">
                            <option value="Business Sch.">Business School</option>
                            <option value="Engineering Sch.">Engineering School</option>
                            <option value="Statistics Dpt.">Statistics Department</option>
                            <option value="Law Sch.">Law School</option>
                            <option value="JQB">JQB &amp; Around</option>
                            <option value="NNB">NNB</option>
                            <option value="Chemistry">Chemistry Department &amp; Around</option>
                          </optgroup>
                           
                  </select></div><!--end form group-->
                <div class="form-group">
                	<label class="control-label"><i class="fa fa-file-o"></i> I want to order for...</label>
                	<textarea class="form-control hungry_order"></textarea>
                </div> 

                 <div class="form-group">
                 	<p class="sending_status"><i class="fa fa-circle"></i> We will process your order once we receive it and get back to you
                 	in no time.</p>
                 </div>

                <div class="form-group">
                	<button class="btn btn-danger btn-block send_hungry_order"> <i class="fa fa-location-arrow"></i> Send Order</button>
                </div> 
		</div><!--end details needed-->
	</div>
</div>
<span class="appear"></span>
<span class="disappear"></span>
<span class="shake"></span>

<div class="container-fluid hidden-sm hidden-md hidden-lg" style="background-color:#eee;padding:10px;margin-bottom:-13px;">
  <div class="row">
    <div class="col-md-12 text-center">
       <h4><a style="color:black;" href="#small_form">Search for an Item <i class="fa fa-search"></i></a></h4>
    </div>
  </div>
</div>



<!--contains actual footer-->
<?php include("inc/copyright.php"); ?>


<?php include("inc/footer.php"); ?>

<script type="text/javascript" src="js/category_parser.js"></script>
<script type="text/javascript">

var add_to_wishlist;

$(function(){
 add_to_wishlist=function(a,b){
        
        /*
           a- product id
           b-customer_id
        */
         var product_id=a;
         var customer_id=b;

    
         $.post("inc/reviews.php",{product_id:product_id,customer_id:customer_id},function(data){
            
            $(".w"+product_id).addClass('big');
           

         });

   };

});
</script>
<script type="text/javascript">
$(function(){

	 $("#hungry_nav").click(function(){
            
            $("#hungry_nav").attr("style","display:none;");
            window.scrollTo(0,0);
           $(".dark-opacity").attr("style","display:block");
           setTimeout(function(){
           	        $(".white-div").removeClass("disappear");
           			$(".white-div").addClass("appear");

           },600);

	 });


	 $(".dark-opacity .close_dark_opacity").click(function(){
	 	   $(".white-div").addClass("disappear");
	 	   $(".white-div").removeClass("appear");
	 	       setTimeout(function(){
           			$(".dark-opacity").attr("style","display:none");
           			 $("#hungry_nav").attr("style","display:block;");
           },300)
	 });
});
</script>
<script type="text/javascript">
$(function(){
  
  $(".send_hungry_order").click(function(){
       
        $(".send_hungry_order").prop("disabled",true);

       var hungry_name=$(".hungry_name").val();
       var hungry_number=$(".hungry_number").val();
       var hungry_hall=$(".hungry_hall").val();
       var hungry_order=$(".hungry_order").val();
       
       if(hungry_name==""||hungry_number==""||hungry_hall=="default"||hungry_order==""){
        		$(".white-div").addClass("shake");
        		setTimeout(function(){
           			$(".white-div").removeClass("shake");
           },400)
       }else{
       	        $(".sending_status").html("<span style='font-size:16px;font-weight:bold;'><i class='fa fa-location-arrow'></i> Sending your order...</span>"); 
       	         $.post("inc/category_parser.php",{hungry_name:hungry_name,hungry_number:hungry_number,hungry_hall:hungry_hall,hungry_order:hungry_order},function(data){ 
                  
                    
                   
         }).success(function(data){

         	   $(".sending_status").html("<span style='color:green;font-size:16px;font-weight:bold;'><i class='fa fa-check'></i> Order Successfully Received...</span>");  
               //alert(data);
			 	       setTimeout(function(){
			 	       	    $(".send_hungry_order").prop("disabled",false);
			 	       	    $(".white-div").addClass("disappear");
			 	            $(".white-div").removeClass("appear");
			 	            setTimeout(function(){$(".dark-opacity").attr("style","display:none"); $("#hungry_nav").attr("style","display:block;");},300);
		           			
		           },60000);
         }).fail(function(){

         	  $(".sending_status").html("<span style='color:red;font-size:16px;font-weight:bold;'><i class='fa fa-times'></i> Please Check Your Connection...</span>"); 
         });//end fail function

       }//end else

  });

});
</script>
</body>
</html>