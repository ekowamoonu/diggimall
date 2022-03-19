<?php ob_start();

include("inc/cookie_checker.php");

/*****************/


//include database connection
include('functions.php'); 
include('conn'.DS.'db_connection.php'); 
include('classes'.DS.'querying_class.php');
include('classes'.DS.'form_class.php');

$query_guy=new DataQuery();
$form_man=new FormDealer();
 
$seller_products="";


if(isset($_GET['detail'])){

  $item_id=$form_man->cleanString($_GET['detail']);

    /*Get all records about item*/
  $items=$query_guy->find_by_id("PRODUCTS","PRODUCT_ID",$item_id);

  $product_name=$items['PRODUCT_NAME'];
  $product_code=$items['PRODUCT_CODE'];
  $db_price=$items['PRODUCT_PRICE'];
  $product_discount=$items['DISCOUNT'];

   //discount checks
    if(empty($product_discount)||$product_discount==null||$product_discount=='null'||$product_discount==0){
        
        $normal_price=$db_price;
        $discount="";
        $pricing='<span class="selling-price">GH&#162;'.number_format($normal_price,2).'</span>';
        $purchase_price=$normal_price;
    }

    else if($product_discount>0){

           $actual_discount=$product_discount*100;//get original percentage from decimal
           $discount='<div class="discount-percentage"><span class="inner border-radius">'.$actual_discount.'% slashed!</span></div>';
           $normal_price=$db_price*100/(100-$actual_discount);
           $new_price=$db_price;
           $pricing='<span class="cancelled-price">GH&#162; '.number_format($normal_price,2).' </span> <span class="selling-price">GH&#162; '.number_format($new_price,2).'</span>';
           $purchase_price=$new_price;
    }

  $seller_id=$items['SEL_ID'];
  $cat_id=$items['MAIN_CAT_ID'];
  $sub_cate_id=$items['SUB_CAT_ID'];

  //display no charges if item is a ticket
  if($cat_id==12){
    $charges="No Delivery Charges On Tickets!";
  }
  else{
    $charges='GH&#162;1.00 delivery (for Campus Meals) - GH&#162;5.00 delivery (other items)';
  }

  //$available_stock=$items['AVAILABLE_STOCK'];
  $pre=$items['PRE_ORDER'];
  $available_stock=($pre==0)?$items['AVAILABLE_STOCK']." Remaining":"Pre-Order";
  $ordered_quantity_so_far=$items['TOTAL_ORDERED_QUANTITY'];
  $product_description=$items['PRODUCT_DESCRIPTION'];

  if($available_stock<=0&&$pre==0){$available_stock="Out Of Stock (Pre-Order Available)";}

  //get product small images
  $small_image=$query_guy->find_by_id("PRODUCT_SMALL_IMAGE","PDS_ID",$item_id);
  $small_img="pro_images_small".DS.$small_image['SMALL_IMAGE_FILE'];

  //get product large images
  $large_image=$query_guy->find_by_id("PRODUCT_LARGE_IMAGE","PDL_ID",$item_id);
  $large_img="pro_images_large/".$large_image['LARGE_IMAGE_FILE'];

  //get seller of this item
  $product_seller=$query_guy->find_by_id("SELLERS","SELLER_ID",$seller_id);
  $seller_name=ucfirst($product_seller['SELLER_NAME']);
  $seller_availability=$product_seller['AVAILABILITY'];
  $seller_photo=$product_seller['SELLER_PROFILE_PIC'];
  $seller_about=$product_seller['SELLER_ABOUT'];

  //get products of seller
  $seller_pdts=mysqli_query(DB_Connection::$connection,"SELECT * FROM PRODUCTS WHERE SEL_ID=".$seller_id." ORDER BY RAND() LIMIT 6");
  while($slrpdt=mysqli_fetch_assoc($seller_pdts)){

  	      $pt_name=$slrpdt['PRODUCT_NAME'];
  	      $pt_id=$slrpdt['PRODUCT_ID'];

  	      $seller_products.='<li><a href="product-detail?detail='.$pt_id.'">'.ucfirst($pt_name).'</a></li>';
  }

  /**************/
/*  if($seller_availability==0&&$pre==0){
  	$delivery="Delivery In 3-4hrs Time";
  }else if($seller_availability==1&&$pre==0){
  	$delivery="Delivery In 30mins-2hrs Time";
  }else if($pre==1){
  	$delivery="Please Check Additional Info For Delivery Time";
  }*/
 /***************/

}

else{
 header("Location: mall");
}



/*Re list all categories for quick navigation*/
/*get all main categories*/
  $main_categories=mysqli_query(DB_Connection::$connection,"SELECT * FROM MAIN_CATEGORY LIMIT 7");

  $main_list="";
   while($main_results=mysqli_fetch_assoc($main_categories)){

     $main_cat_name=(strlen($main_results['MAIN_CATEGORY_NAME'])>25)?substr($main_results['MAIN_CATEGORY_NAME'],0,25)."..":$main_results['MAIN_CATEGORY_NAME'];
     $main_cat_id=$main_results['MAIN_CATEGORY_ID'];

     $main_list.='<li class="dropdown">';
     $main_list.='  <a class="dropdown-toggle" data-toggle="dropdown">'.ucfirst($main_cat_name).'<span class="caret"></span></a>';
     $main_list.='   <ul class="dropdown-menu list-inline">';
     //get all sub categories
     $sub=$query_guy->find_by_col("SUB_CATEGORY","PARENT_CATEGORY_ID", $main_cat_id);
        while($sub_results=mysqli_fetch_assoc($sub))
         {
            $sub_cat_name=ucfirst($sub_results['SUB_CATEGORY_NAME']);
            $sub_cat_id=$sub_results['SUB_CATEGORY_ID'];

            $main_list.='<li><a href="mall?redirect=true&&person=person&&cat=3&&items=94858hx59&&sub_cat='.$sub_cat_id.'&&main_cat='.$main_cat_id.'&&subset=5&&subitem=6">'.$sub_cat_name.'</a></li>';


       }//end nested while

      $main_list.='<li><a href="mall?redirect=true&&person=person&&cat=3&&items=94858hx59&&main_cat='.$main_cat_id.'&&sub_cat=&&subset=5&&subitem=6">All</a></li>';
    $main_list.='  </ul>';
    $main_list.='</li>';

}//end main while



/*get number of items in shoppers bag*/
$shopping_bag_query=mysqli_query(DB_Connection::$connection,"SELECT COUNT(*) FROM BAG_ITEMS WHERE VSTR_ID=".$shoppers_id);
$bag_array=mysqli_fetch_array($shopping_bag_query);
$number_of_items=array_shift($bag_array);

/*select 4 randome items in the same category as this product*/
$related="SELECT * FROM PRODUCTS WHERE MAIN_CAT_ID=".$cat_id." AND PRODUCT_ID <>".$item_id." ORDER BY RAND() LIMIT 4";
$related_query=mysqli_query(DB_Connection::$connection,$related);
$related_list="";

while($r_items=mysqli_fetch_assoc($related_query)){

    $related_id=$r_items['PRODUCT_ID'];
	$related_name=$r_items['PRODUCT_NAME'];
	$related_price=$r_items['PRODUCT_PRICE'];

    //get image
	$related_small_image_finder=$query_guy->find_by_id("PRODUCT_SMALL_IMAGE","PDS_ID",$related_id);
	$related_small_image="pro_images_small".DS.$related_small_image_finder['SMALL_IMAGE_FILE'];

	$related_list.='<div class="col-md-3 col-sm-3 main-item-box text-center">
						<div class="item-box">
							<a href="product-detail?detail='.$related_id.'"><img class="img img-responsive" src="'.$related_small_image.'"/></a>
						</div>
						<div class="item-details">
							 <h5 >GH&#162; '.number_format($related_price,2).'</h5>
						 	 <p>'.ucfirst($related_name).'</p>
					    </div>
					</div>';

}

//facebook sharing
$current_url=$_SERVER['REQUEST_URI'];

/*next three days calculations*/
//meals are received within 40mins
$call_us_now="";
if($cat_id==11){
  
  $call_us_now ='<h6 class="text-center  hidden-sm hidden-md hidden-lg">OR</h6><div class="call-div"><a class="btn btn-default call hidden-sm hidden-md hidden-lg"><i class="fa fa-phone"></i> Call Us To Order</a></div>';
  $seconds_in_three_days=strtotime("+40 minutes");
  $time_in_three_days=date("h:ia",$seconds_in_three_days);
  $delivery="Delivery within 40 minutes";

}elseif($cat_id==9){//pharmaceuticals
  $seconds_in_three_days=strtotime("+2 hours");
 $time_in_three_days=date("h:ia",$seconds_in_three_days);
 $delivery="Delivery within 2 hours";

}elseif($cat_id==12){//TICKETS
 $time_in_three_days="the Shortest time";
 $delivery="Receive This After Payment Is Made";

}
elseif($cat_id!=11&&$cat_id!=9&&$cat_id!=12&&$pre==1){
  $seconds_in_three_days=strtotime("+10 days");
  $time_in_three_days=date("l, F j",$seconds_in_three_days);
  $delivery="Delivery within 10 days";
}
else{
  $seconds_in_three_days=strtotime("+3 days");
  $time_in_three_days=date("l, F j",$seconds_in_three_days);
  $delivery="Delivery within 3 days";
}



//quickly check to make sure buyer is registered and logged in
$review_head="";
$review_form="";
if(isset($_COOKIE['logged_in'])){
   
   $buyers_registration_id=(int)decryptCookie($_COOKIE['logged_in']);

   $review_head="<h4 class='r_head'>Add yours here, ".ucfirst($shoppers_name)."</h4>";
   $review_form='<div class="form-group">
                     <label class="control-label">Number of Stars</label>
                     <input type="number" class="form-control" id="number_of_stars" min="1" max="5"/>
                 </div>
                 <div class="form-group">
                     <label class="control-label">Review</label>
                     <textarea style="max-width:100%;" id="review_content" class="form-control"></textarea>
                  </div>
                  <div class="form-group">
                      <button class="btn btn-info pull-right" onclick="add_review(\''.$buyers_registration_id.'\',\''.$item_id.'\');">Add My Review <i class="fa fa-star"></i></button>
                  </div>';
   
   $wishlist='<i class="fa fa-heart"></i> <span onclick="add_to_wishlist(\''.$item_id.'\',\''. $buyers_registration_id.'\');">Add to Wishlist</span>';
   $follow_link='<p class="follow_link"><a style="color:white;" href="product-detail?detail='.$item_id.'&&customer=2&&shopnow=039474738&&products=97&&useragent=browserprofile&&time=223438uuf0834983034n3098349848&&follow='.$seller_id.'&&vendor=5"><i style="color:white" class="fa fa-mail-forward"></i> Click here to Follow this Item\'s Vendor</a></p>';
}
else{

   $review_head="<h4>".ucfirst($shoppers_name).", you need to Sign in to add a review</h4>";
   $review_form='<a class="btn btn-default" href="user-registration"><i class="fa fa-user"></i> Sign In Securely</a>';
   $wishlist='<i class="fa fa-heart"></i> <span onclick="alert(\'Sign In To Enable This\');">Add to Wishlist</span>';
   $follow_link='<p class="follow_link"><a style="color:white;" onclick="alert(\'Oops! You Need to login First to enable this feature\');" href="#"><i style="color:white" class="fa fa-heart"></i> Click here to Follow this Vendor</a></p>';
   
}//end else if logged in


if(isset($_GET['follow'])){

  $insert_following=mysqli_query(DB_Connection::$connection,"INSERT INTO FOLLOWED_SHOPS(SHOP_ID,CSTMR_ID) VALUES('{$seller_id}','$buyers_registration_id')");
  
  if($insert_following){
    $follow_link='<p class="follow_link"><a style="color:white;"  href="#"><i style="color:white" class="fa fa-heart"></i> You are now Following this Vendor</a></p>';
  }

}

/******************************************************************/

/**get product reviews*/
/*
  1. Calculate reviews average
  2. Get total number of reviews
  3. List all the reviews and style the appropriate number of stars

*/

  $review_list="";
  $get_number_of_views=mysqli_query(DB_Connection::$connection,"SELECT * FROM REVIEWS WHERE PDT_ID=".$item_id." ORDER BY REVIEW_DATE DESC");
  
  if(mysqli_num_rows($get_number_of_views)<=0){
         
         $num_of_reviews=0;
         $overall_number_of_stars="Be the first to rate this Product";
         $review_list="<h4>Rate this product first!</h4>";
     
  }else{

     $get_reviews_query=$query_guy->find_by_col_and_sum('REVIEWER_RATING','total_ratings','REVIEWS','PDT_ID',$item_id);

  /*get overall details*/
  $num_of_reviews=mysqli_num_rows($get_number_of_views);

  $reviews_count=(int)$num_of_reviews;
  $sum=mysqli_fetch_assoc($get_reviews_query);
  $sum_of_review_ratings=$sum['total_ratings'];//get overall total ratings


  /*overall ratings*/
  
  $overall_ratings=$sum_of_review_ratings/$reviews_count;
  $overall_number_of_stars=number_of_stars($overall_ratings);

  $review_list="";
  /*fetch individual ratings*/
  while($review_details=mysqli_fetch_assoc($get_number_of_views)){

      $reviewer_id=$review_details['REVIEWER_ID'];
      $review_content=$review_details['REVIEW_CONTENT'];
      $review_date=date("l, F j/2017",strtotime($review_details['REVIEW_DATE']));


      /*stars*/
      $review_rating=$review_details['REVIEWER_RATING'];
      $number_of_stars=number_of_stars($review_rating);

      /*get buyers details*/
      $buy_details=$query_guy->find_by_id("BUYERS","BUYER_ID",$reviewer_id);
      $reviewer_name=$buy_details['BUYER_NAME'];
      $reviewer_hall=$buy_details['BUYER_HALL'];

      $review_list.='<div class="review-list">
                      <p>'.$number_of_stars.'<p>
                      <p><b>'.ucfirst($reviewer_name).'</b> ('.$review_date.') - '.$reviewer_hall.'</p>
                      <p class="review_words">'.$review_content.'</p>
                    </div>';



  }//end while
  
}//end if there are no reviews

 




?>

<?php include("inc/header.php"); ?>

<meta property="og:url"  content="http://ug.diggimall.com/product-detail?detail=<?php echo $item_id; ?>" />
<meta property="og:type" content="website" />
<meta property="og:title" content="Awesome! Buy A <?php echo ucfirst($product_name); ?> On DiggiMall" />
<meta property="og:description" content="Students in University Of Ghana, you don't need to go through stress to buy items you need. Order on DiggiMall and have it delivered to you right in your hall for a very affordable amount." />
<meta property="og:image"  content="http://ug.diggimall.com/<?php echo $small_img; ?>" />

<link rel="stylesheet" href="css/product-detail.css"/>
<link rel="stylesheet" href="css/nav.css"/>
<title><?php echo "Shop ".$product_name; ?></title>
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:434489,hjsv:5};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'//static.hotjar.com/c/hotjar-','.js?sv=');
</script>


</head>


 <body>

<!--site navigation-->
<?php include("inc/nav.php"); ?>

<!-- <div class="bag-added-container text-center">
	<h3>Awesome <?php echo ucfirst($shoppers_name); ?> ! This Item Has Been Put In Your Bag. <a href="bag">View My Bag</a></h3>
</div>
 -->

<div class="bag-added-container text-center">
   <h3>Item Added To Bag <i style="color:green;" class="fa fa-check"></i></h3> 
  <h2><a class="btn btn-success proceed_to_checkout" href="#">Proceed To Delivery <i class="fa fa-long-arrow-right"></i></a> <a class="btn btn-danger continue">Continue Shopping <i class="fa fa-shopping-bag"></i></a><h2>
</div>

<!--category listings-->
<div class="container-fluid categories-navigation hidden-xs">
  <section class="row">
    <div class="col-md-12">
      <ul class="list-inline nav navbar-nav">
        <?php echo $main_list; ?>
      </ul>
    </div>
  </section>
</div>

<div class="container-fluid secure-info">
  <section class="row">
    <div class="col-md-4"><span class="text-left"><a href="mall?redirect=true&&person=person&&cat=3&&items=94858hx59&&sub_cat=<?php echo $sub_cate_id; ?>&&main_cat=&&subset=5&&subitem=6"><i class="fa fa-arrow-left"></i> Back To The Mall</a></span></div>
    <div class="col-md-8 text-right"><p><span class="text-right"><a href="bag">Proceed to View Your Items <i class="fa fa-arrow-right"></i></a></span></p></div>
  </section>
</div>

<div class="container-fluid follow-container">
  <div class="row">
    <div class="col-md-12 text-right">
       <?php echo $follow_link; ?>
    </div>
  </div>
</div>

<div class="container-fluid divisor">
  <div class="row">
    <div class="col-md-12">
    </div>
  </div>
</div>

<!--**************************************************************************************************************-->

<!--product detail box-->
<div class="container detail-container">
	<div class="row">
		<div class="col-md-5 col-sm-5 image-box">
      <?php echo $discount; ?>
     <!--  <div class="discount-percentage"><span class="inner border-radius">20% off!</span></div> -->
			<img id="zoom" class="img img-responsive hidden-xs hidden-sm visible-md-block visible-lg-block"  src="<?php echo $small_img; ?>"  data-zoom-image="<?php echo $large_img; ?>"/>
		  <img class="img img-responsive visible-xs-block visible-sm-block hidden-md hidden-lg"  src="<?php echo $small_img; ?>"/>
		</div>
		<div class="col-md-1 col-sm-1"></div>
		<div class="col-md-6 col-sm-6">
      
      <div class="product-complete-details-div">
         <div class="row product-name-row">
            <p style="font-size:20px;font-weight:bold;"><?php echo ucfirst($product_name); ?></p>
            <p class="text-muted"><a href="vendor?shop=<?php echo $seller_id; ?>" style="color:grey;">by <?php echo ucfirst($seller_name); ?></a> <b><span class="wishlist"><?php echo $wishlist; ?></span></b></p>
            <p>Product ratings: <?php echo $overall_number_of_stars; ?> <a href="#" onclick="alert('Scroll Down to the Review Section');">(<?php echo $num_of_reviews; ?> reviews)</a></p>
         </div><!--end product name row-->
         <div class="row below-product-name-row">
            <p><i class="fa fa-clock-o"></i> Want it by <?php echo  $time_in_three_days; ?>?</p>
            <p><a href="#" onclick="buy_now('<?php echo $shoppers_id; ?>','<?php echo $item_id; ?>','<?php echo $purchase_price; ?>');">Click To Order this now</a></p>
            <form class="form-inline details-form"> 
              <div class="form-group">
                <input type="number"   placeholder="Quantity" id="quantity" class="form-control"/>
                <input type="text" placeholder="Requests (if any e.g Sizes,Color) " id="requirements" class="form-control"/>
             </div>
          </form>
          <?php  $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; 
                 $share_link= $actual_link."&sharefollower=2";
                  
           ?>

          <p class="share-whatsapp">Share with a friend: <b><a style="color:green;" href="whatsapp://send?text=I%20saw%20this%20Really%20cool%20Item%20on%20Diggimall!%20Buy%20<?php echo ucfirst($product_name); ?> <?php echo urlencode($share_link); ?>" data-action="share/whatsapp/share">on whatsapp<i class="fa fa-whatsapp  fa-fw"></i></a></b></p>
          <p class="shopping-row"><i class="fa fa-shopping-bag fa-2x"></i> <span class="item_number">You have (<?php echo $number_of_items ?> distinct items in your bag)</span></p>
         </div><!--end below-product-name-row-->

         <div class="row pricing-row">
          <div class="col-md-8 price"><p><?php echo $pricing; ?></p></div>
          <div class="col-md-4 buybutton">
            <a class="btn btn-default" onclick="buy_now('<?php echo $shoppers_id; ?>','<?php echo $item_id; ?>','<?php echo $purchase_price; ?>');">Buy This Now</a>
            <!--  <span class="text-center">> --> 
            <?php echo $call_us_now; ?>
          </div>
         </div><!--end pricing row-->
          <div class="loading" style="display:none;"><img src="images/mall_loading.gif" class="img-responsive"/></span></div>
          <div class="row delivery-details-row">
            <div class="delivery-details">
              <div class="row sold-by-row"><div class="col-md-12 text-center">
                <p><a href="vendor?shop=<?php echo $seller_id; ?>" style="color:black;">Click This to See <b><?php echo ucfirst($seller_name); ?>'s Shop</b></a></p>
                <p style="margin-top:-5px;"><?php echo $charges; ?></p>
              </div></div>
              <div class="row policies-row">
                <div class="col-md-4 text-center">
                  <h3><i class="fa fa-money fa-2x"></i></h3>
                  <p>Pay Into Our Mobile Money Account</p>
                </div>
                <div class="col-md-4 text-center">
                  <h3><i class="fa fa-history fa-2x"></i></h3>
                  <p><?php echo $delivery; ?></p>
                </div>
                <div class="col-md-4 text-center">
                  <h3><i class="fa fa-thumbs-up fa-2x"></i></h3>
                  <p>This product is Genuine</p>
                </div>
              </div>
            </div>
          </div><!--end delivery details row-->
      </div>

  	</div><!--end product details div-->
	</div>
</div>

<div class="container-fluid divisor2">
  <div class="row">
    <div class="col-md-12">
    </div>
  </div>
</div>

<!--********************************************************************************************************************-->
<!--additional information-->
<div class="container additional-info-container">
  <div class="row">
     <div class="span12">
  
            <ul class="nav nav-tabs">
                <li class="active"><a href="#p_description" data-toggle="tab"><span class="hidden-xs">Product Description</span><span class="visible-xs"><i class="fa fa-plus-circle"></i> More Info</span></a></li>
                <li><a href="#p_reviews" data-toggle="tab"><span class="hidden-xs">Reviews (<?php echo $num_of_reviews; ?>)</span><span class="visible-xs">Reviews (<?php echo $num_of_reviews; ?>)</span></a></li>
                <li><a href="#p_vendor" data-toggle="tab"><span class="hidden-xs">Vendor Profile</span><span class="visible-xs"><i class="fa fa-male"></i> Vendor</span></a></li>
                <li class="hidden-xs"><a href="#p_return" data-toggle="tab"><span>Return Policy</span></a></li>
            </ul>
            <div id="myTabContent" class="tab-content">
                    <div class="tab-pane active in" id="p_description">
                         <p><?php echo $product_description; ?></p>
                    </div>
                    <div class="tab-pane fade" id="p_reviews">
                      <div class="row">
                        <div class="col-md-6 reviews-div">
                          <?php echo $review_list; ?>
                          <!-- <div class="review-list">
                            <p><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i style="color:grey;" class="fa fa-star"></i><p>
                            <p><b>Kwame Djokoto</b> (20th January, 2016) - Legon Hall</p>
                            <p class="review_words">This product is really genuine. I mean, i simply simply loved it. its awesome.</p>
                          </div> -->
                        </div>
                        <div class="col-md-6 add-review-div">
                          <div class="row">
                            <div class="col-md-5">
                              <h4>Ratings Guide if you have ever used this product</h4>
                              <p>Just ok <i style="color:gold;" class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i  class="fa fa-star"></i></p>
                              <p>Very good <i style="color:gold;" class="fa fa-star"></i><i style="color:gold;" class="fa fa-star"></i><i  class="fa fa-star"></i><i  class="fa fa-star"></i><i  class="fa fa-star"></i></p>
                              <p>Awesome! <i style="color:gold;" class="fa fa-star"></i><i style="color:gold;" class="fa fa-star"></i><i style="color:gold;" class="fa fa-star"></i><i  class="fa fa-star"></i><i  class="fa fa-star"></i></p>
                              <p>Perfect! <i style="color:gold;" class="fa fa-star"></i><i style="color:gold;" class="fa fa-star"></i><i style="color:gold;" class="fa fa-star"></i><i style="color:gold;" class="fa fa-star"></i><i  class="fa fa-star"></i></p>
                              <p>I Simply Loved It! <i style="color:gold;" class="fa fa-star"></i><i style="color:gold;" class="fa fa-star"></i><i style="color:gold;" class="fa fa-star"></i><i style="color:gold;" class="fa fa-star"></i><i style="color:gold;" class="fa fa-star"></i></p>
                            </div>
                            <div class="col-md-7 reviews-form">
                               <?php echo $review_head; ?>
                               <?php echo $review_form; ?>
                              <!--  <div class="form-group">
                                <label class="control-label">Number of stars</label>
                                <input type="number" class="form-control" id="number_of_stars" min="1" max="5"/>
                               </div>
                               <div class="form-group">
                                 <label class="control-label">Review</label>
                                 <textarea style="max-width:100%;" class="form-control"></textarea>
                               </div>
                               <div class="form-group">
                                 <button class="btn btn-info pull-right">Add My Review <i class="fa fa-star"></i></button>
                               </div>-->
                               <div style="display:none;" class="review-loading"><img src="images/loading.gif" class="img-responsive"/></div>
                            </div><!--end reviews form-->
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="p_vendor">
                        <div class="row">
                          <div class="col-md-4">
                            <div class="seller-pic">
                              <a href="vendor?shop=<?php echo $seller_id; ?>"><img src="<?php echo "seller_photos/".$seller_photo; ?>" class="img img-responsive" /></a>
                            </div>
                          </div>
                          <div class="col-md-5">
                            <h4><?php echo ucfirst($seller_name); ?></h4>
                            <p><?php echo substr($seller_about,0,500)."..."; ?></p>
                            <p><a style="background-color:#337ab7; color:white;padding:3px;" href="vendor?shop=<?php echo $seller_id; ?>">Visit <?php echo ucfirst($seller_name); ?>'s Shop On DiggiMall <i class="fa fa-arrow-right"></i></a></p>
                          </div><!--end seller about-->
                          <div class="col-md-3">
                            <h4>Other products by Vendor</h4>
                            <ul class="nav">
                               <?php echo $seller_products; ?>
                            </ul>
                          </div><!--end seller products list-->
                        </div>
                    </div>
                    <div class="tab-pane fade" id="p_return">
                        <h4>Can I return this product?</h4>
                        <p>Yes, you can return this product for a refund, within 6 hours after receiving your original order except for Meals.</p>
                        
                        <h4>How to return this product</h4>
                        <p>You can request a return by calling support on 054 195 9025 / 0209058871</p>

                        <h4>What are the required conditions?</h4>
                        <p>The products in your possession are your responsibility until they are picked up by our dispatch driver or you have dropped it off at a pickup point. Any product that is not properly packed or is damaged will not be eligible for a return, so make sure they are properly taken care of prior to the return! Listed below are the conditions for your return request to be accepted:</p>
                        <ul>
                           <li>The images displayed on Diggimall are the exact depiction of the corresponding product being sold. We do all our best ensure that images are always exact and extensive product descriptions are provided in a way not to trick you or deceive you in ordering the wrong item 
                             or misconceiving the features of the actual product in the possession of the vendor. We shall therefore not be held accountable for mistaken product features on the side of the customer. Therefore, request for returns based on
                             mistaken product features shall not processed.
                          </li>
                          <li>Product must remain sealed, except if the product is defective or damaged</li>
                          <li>Product is still in its original packaging </li>
                          <li>Product is in its original condition and unused </li>
                          <li>Product is not damaged</li>
                          <li>Product label is still attached</li>
                          <li>Product should contain no missing parts</li>
                        </ul>

                        <h4>What are the next steps?</h4>
                        <p> Once your return request done, we will contact you to arrange retrieval of the product. You will also have the choice to deliver yourself the product to one of our pickup Stations.
                            Once the product retrieved, we will proceed to examination.
                           In the unlikely event that an item is returned to us in an unsuitable condition, we will send it back to you without refund.
                            If examination conclusive and conditions respected, we will proceed to refund within maximum 14 business days post retrieval product.</p>
                     </div>
            </div><!--end my tab content-->
       
     </div>
  </div>
</div>







<!--recommended items head-->
 <!--extra items head-->
 <div class="container hot-items-container">
 	<div class="row">
 		<div class="col-md-12">
 			<h3>Shoppers also checked out these products</h3>
 		</div>
 	</div>
 </div>

<!--recommended items slider-->
<div class="container trending-items-slider-container">
	<div class="row">
		<?php echo $related_list; ?>
		
	</div>
</div>


<div class="container-fluid" style="background-color:#eee;padding:10px;margin-bottom:-13px;">
  <div class="row">
    <div class="col-md-12 text-center">
       <h4><a style="color:black;" href="checkout">Done Shopping? Proceed To Checkout <i class="fa fa-long-arrow-right"></i></a></h4>
    </div>
  </div>
</div>



                     

<!--contains actual footer-->
<?php include("inc/copyright.php"); ?>


<?php include("inc/footer.php"); ?>
<script type="text/javascript" src="js/jquery.elevateZoom-3.0.8.min.js"></script>
<script type="text/javascript">
$(function(){
	$("#zoom").elevateZoom({easing : true});
});

</script>
<script type="text/javascript" src="js/buynow.js"></script>
<script type="text/javascript">
$(function(){
    $(".dropdown").hover(            
        function() {
            $('.dropdown-menu', this).stop( true, true ).slideDown("fast");
            $(this).toggleClass('open');        
        },
        function() {
            $('.dropdown-menu', this).stop( true, true ).slideUp("fast");
            $(this).toggleClass('open');       
        }
    );

});
</script>
<!--Adding a review-->
<script type="text/javascript">
var add_review;
var add_to_wishlist;
$(function(){
 
   add_review=function(a,b){
      /*
          a- buyers unique id
          b- product id
          I will also the number of stars input and the review content
      */
       
        /*show loading gif*/
        $(".review-loading").attr("style","display:inline;");

        var buyers_id=a;
        var pdt_id=b;
        var number_of_stars= $("#number_of_stars").val();
        var review_content=$("#review_content").val();


        if(number_of_stars!=""&&number_of_stars<6&&number_of_stars>0&&review_content!=""){

           $.post("inc/reviews.php",{buyers_id:buyers_id,pdt_id:pdt_id,number_of_stars:number_of_stars,review_content:review_content},function(data){
                   
                     $(".review-loading").attr("style","display:none;");
                     $(".r_head").html("Review Submitted <i class='fa fa-check'></i>");
                     /*alert(data);*/

             });

        }else{
          alert("Invalid Review!");
           $(".review-loading").attr("style","display:none;");
        }
       

   };


   add_to_wishlist=function(a,b){
        
        /*
           a- product id
           b-customer_id
        */
         var product_id=a;
         var customer_id=b;

    
         $.post("inc/reviews.php",{product_id:product_id,customer_id:customer_id},function(data){
            
            $(".wishlist").attr("style","opacity:1");
            $(".wishlist").html("<i class='fa fa-heart'></i> Added to your Wishlist");
           

         });

   };


});
</script>
<script type="text/javascript">

$(function(){
  $(".call").click(function(){
       
 /*      var num5="<p><a href='tel:+233543236033'><i class='fa fa-phone'></i> 0543236033</a></p>";
       var num1="<p><a href='tel:+233541952025'><i class='fa fa-phone'></i> 0541952025</a></p>";
       var num2="<p><a href='tel:+233209134512'><i class='fa fa-phone'></i> 0209134512</a></p>";*/
       var num3="<p><a href='tel:+233269297750'><i class='fa fa-phone'></i> 0269297750</a></p>";
       var num4="<p><a href='tel:+233509231207'><i class='fa fa-phone'></i> 0509231207</a></p>";

       $(".call-div").html(num3+num4);


  });

      $(".continue").click(function(){
      
      $(".bag-added-container").attr("style","display:none;");

    });

});

</script>
<script type="text/javascript">
  $(function(){
      $(".proceed_to_checkout").click(function(){
            $("#checkout_options").modal("show");
      });
  });
</script>

</body>
</html>