<?php ob_start();

include("inc/cookie_checker.php");


if(!isset($_COOKIE['logged_in'])){

    header("Location: user-registration");
}else{

  $buyer_id=(int)decryptCookie($_COOKIE['logged_in']);
}


include('functions.php'); 
include('conn'.DS.'db_connection.php'); 
include('classes'.DS.'form_class.php');
include('classes'.DS.'querying_class.php');
include('classes'.DS.'user_profile_class.php');

$connection=new DB_Connection();
$form_man=new FormDealer();
$query_guy=new DataQuery();
$user=new UserProfile($buyer_id);//create a new user profile instance with the buyer id

if(isset($_GET['delete'])){
   
   $del=$form_man->cleanString($_GET['delete']);
   $delete_query=mysqli_query(DB_Connection::$connection,"DELETE FROM WISHLIST WHERE WISHLIST_ID=".$del." AND CUSTOMER_ID=".$buyer_id);

}

if(isset($_GET['unfollow'])){
   
   $del_shop=$form_man->cleanString($_GET['unfollow']);
   $unfollow_query=mysqli_query(DB_Connection::$connection,"DELETE FROM FOLLOWED_SHOPS WHERE FOLLOWED_SHOP_ID=".$del_shop." AND CSTMR_ID=".$buyer_id);

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


/*Get and update user details*/
$user->set_username();

$log_error="<h4>Your Shopping Info</h4>";

/*if user clicks join shoppers*/
if(isset($_POST['update_submit'])){

  if($form_man->emptyField($_POST['profile_name'])||
    $form_man->emptyField($_POST['email'])||
    $form_man->emptyField($_POST['phone'])||
    $form_man->emptyField($_POST['whatsapp'])||
    $form_man->emptyField($_POST['hall'])||
    $form_man->emptyField($_POST['username'])||
    $form_man->emptyField($_POST['password'])
    ){
             
             $log_error="<h4 style='background-color:red;'>Fill Every Empty Input</h4>";
  }//end first nested if
   else{

          $buyer_name=$form_man->cleanString($_POST['profile_name']);
          $buyer_email=$form_man->cleanString($_POST['email']);
          $buyer_phone=$form_man->cleanString($_POST['phone']);
          $buyer_whatsapp=$form_man->cleanString($_POST['whatsapp']);
          $buyer_hall=$form_man->cleanString($_POST['hall']);
          $buyer_username=$form_man->cleanString($_POST['username']);
          $password=$form_man->cleanString($_POST['password']);
          $buyer_password=password_hash($password,PASSWORD_BCRYPT,['cost'=>11]);

          /*insert buyer details into the database*/
          $buyer_insert="UPDATE BUYERS SET BUYER_USERNAME='{$buyer_username}', ";
          $buyer_insert.="BUYER_PASSWORD='{$buyer_password}',";
          $buyer_insert.="BUYER_NAME='{$buyer_name}',";
          $buyer_insert.="BUYER_PHONE='{$buyer_phone}',";
          $buyer_insert.="BUYER_HALL='{$buyer_hall}',";
          $buyer_insert.="BUYER_WHATSAPP='{$buyer_whatsapp}',";
          $buyer_insert.="BUYER_EMAIL='{$buyer_email}'";
          $buyer_insert.=" WHERE BUYER_ID=".$buyer_id;
  

         $buyer_query=mysqli_query(DB_Connection::$connection,$buyer_insert);

        /* echo mysqli_error(DB_Connection::$connection);*/
          
         $log_error = "<h4>Profile Updated!</h4>"; 

         header("Refresh: 0.5;url='user-profile'");

   }//end first nested else

}//end main if


/*get wishlist*/
$wishlist=$user->retrieve_wishlist();
$wishlist_display="";

if(mysqli_num_rows($wishlist)<=0){

   $wishlist_display="<h5 class='text-center sadface'><img src='images/sadface.png' class='img-responsive'/></h5><h4 style='padding:0;margin:0;letter-spacing:1px;' class='text-center' style='margin-top:90px;color:#eee;'>Oops! Your Wishlist is empty. <a href='mall'> Add Your Favorite Items To This List as you Shop</a></h4>";

}else{

    while($wlist=mysqli_fetch_assoc($wishlist)){
     
     $wishlist_id=$wlist['WISHLIST_ID'];
     $item_id=$wlist['PDCT_ID'];

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
               $discount='<div class="discount-percentage"><span class="inner border-radius">-'.$actual_discount.'%</span></div>';
               $normal_price=$db_price*100/(100-$actual_discount);
               $new_price=$db_price;
               $pricing='<span class="cancelled-price">GH&#162; '.number_format($normal_price,2).' </span> <span class="selling-price">GH&#162; '.number_format($new_price,2).'</span>';
               $purchase_price=$new_price;
        }


        //get product small images
      $small_image=$query_guy->find_by_id("PRODUCT_SMALL_IMAGE","PDS_ID",$item_id);
      $small_img="pro_images_small".DS.$small_image['SMALL_IMAGE_FILE'];

     $wishlist_display.='<div class="col-md-4 col-sm-4 main-item-box text-center">
                          <div class="item-box">
                          '.$discount.'
                           <a href="product-detail?detail='.$item_id.'"><img class="img img-responsive" src="'.$small_img.'"/></a>
                          </div>
                          <div class="item-details">
                             <h5 >'.$pricing.'</h5>
                             <p>'.ucfirst($product_name).'</p>
                          </div>
                          <span title="Remove From Wishlist"><a href="user-profile?delete='.$wishlist_id.'"><i class="fa fa-close fa-2x"></i></a></span>
                       </div>';

  }

}//end else

/***********************************************/
//get tickets
$tickets=$user->retrieve_tickets();
$tickets_display="";

if(mysqli_num_rows($tickets)<=0){

   $tickets_display="<h5 class='text-center sadface'><img src='images/sadface.png' class='img-responsive'/></h5><h4 style='padding:0;margin:0;letter-spacing:1px;' class='text-center' style='margin-top:90px;color:#eee;'>Oops! You Have not Purchased Any Tickets OR Your payment hasn't been confirmed Yet. <a href='mall'> Purchase Some Tickets</a></h4>";

}else{

    while($tilist=mysqli_fetch_assoc($tickets)){
     
     $tickets_id=$tilist['ORDER_ID'];
     $item_id=$tilist['PT_ID'];
     $random_token=$tilist['RANDOM_TOKEN'];

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
               $discount='<div class="discount-percentage"><span class="inner border-radius">-'.$actual_discount.'%</span></div>';
               $normal_price=$db_price*100/(100-$actual_discount);
               $new_price=$db_price;
               $pricing='<span class="cancelled-price">GH&#162; '.number_format($normal_price,2).' </span> <span class="selling-price">GH&#162; '.number_format($new_price,2).'</span>';
               $purchase_price=$new_price;
        }


        //get product small images
      $small_image=$query_guy->find_by_id("PRODUCT_SMALL_IMAGE","PDS_ID",$item_id);
      $small_img="pro_images_small".DS.$small_image['SMALL_IMAGE_FILE'];

      /*watermarking a ticket*/
      $source_file=$small_img;
      $file=$tickets_id."_watermarked_".$small_image['SMALL_IMAGE_FILE'];
      $destination_file="watermarks/".$tickets_id."_watermarked_".$small_image['SMALL_IMAGE_FILE'];
      $watermark_text=$random_token;

     // $user->watermark_ticket($source_file,$watermark_text,$destination_file);
      $user->generateWatermark($source_file,$destination_file,$tickets_id,$random_token,"arial.ttf");



     $tickets_display.='<div class="col-md-3 col-sm-3 main-item-box text-center">
                          <div style="min-height:auto;max-height:530px;">
                           <a href="product-detail?detail='.$item_id.'"><img class="img img-responsive" src="'.$small_img.'"/></a>
                          </div>
                          <div class="text-left">
                          <p style="background-color:#D91E18;color:white;padding:3px;font-size:17px;">Write these codes down if you can, It will be required at the Program</p>
                             <p style="background-color:#eee;padding:4px;font-size:17px;margin-top:-10px;"><span>Verification Code:</span> <b>'.$random_token.'</b></p>
                             <p style="background-color:#eee;padding:4px;font-size:17px;margin-top:-10px;"><span>Ticket Number #:</span> <b>'.$tickets_id.'</b></p>
                             <p><a href="'.$destination_file.'" download="'.$file.'" class="btn btn-primary"><i class="fa fa-download"></i> DOWNLOAD TICKET</a></p>
                       </div></div>';

  }

}//end else


/************************************************/


//get pending orders
$pending_list="";
$pending=$user->get_all_pending_orders();

while($pending_orders=mysqli_fetch_assoc($pending)){
    
    $pending_order_id=$pending_orders['ORDER_ID'];
    $pending_product_id=$pending_orders['PT_ID'];
    $pending_order_date=date("M d h:ia",strtotime($pending_orders['ORDER_DATE_FULL']));
    $pending_total_cost=$pending_orders['TOTAL_COST_OF_ORDER'];
    $pending_quantity=$pending_orders['ORDERED_QUANTITY'];

    //get product name and picture
    $pending_items=$query_guy->find_by_id("PRODUCTS","PRODUCT_ID",$pending_product_id);
    $pending_product_name=$pending_items['PRODUCT_NAME'];

    //get product small images
    $pending_small_image=$query_guy->find_by_id("PRODUCT_SMALL_IMAGE","PDS_ID",$pending_product_id);
    $pending_small_img="pro_images_small".DS.$pending_small_image['SMALL_IMAGE_FILE'];

    $pending_list.='      <tr>
                          <td>'.$pending_order_id.'</td>
                            <td>
                              <div class="col-md-2 order-image-box">
                                <img src="'.$pending_small_img.'" class="img-responsive"/>
                                <p>'.ucfirst($pending_product_name).' ('.$pending_quantity.')</p>
                              </div>
                            </td>
                            <td>'.$pending_total_cost.'</td>
                            <td>'.$pending_order_date.'</td>
                          </tr>';

}


//get packaged orders
$packaged_list="";
$packaged=$user->get_all_packaged_orders();

while($packaged_orders=mysqli_fetch_assoc($packaged)){
    
    $packaged_order_id=$packaged_orders['ORDER_ID'];
    $packaged_product_id=$packaged_orders['PT_ID'];
    $packaged_order_date=date("M d h:ia",strtotime($packaged_orders['ORDER_DATE_FULL']));
    $packaged_total_cost=$packaged_orders['TOTAL_COST_OF_ORDER'];
    $packaged_quantity=$packaged_orders['ORDERED_QUANTITY'];

    //get product name and picture
    $packaged_items=$query_guy->find_by_id("PRODUCTS","PRODUCT_ID",$packaged_product_id);
    $packaged_product_name=$packaged_items['PRODUCT_NAME'];

    //get product small images
    $packaged_small_image=$query_guy->find_by_id("PRODUCT_SMALL_IMAGE","PDS_ID",$packaged_product_id);
    $packaged_small_img="pro_images_small".DS.$packaged_small_image['SMALL_IMAGE_FILE'];

    $packaged_list.='      <tr>
                             <td>'.$packaged_order_id.'</td>
                            <td>
                              <div class="col-md-2 order-image-box">
                                <img src="'.$packaged_small_img.'" class="img-responsive"/>
                                <p>'.ucfirst($packaged_product_name).' ('.$packaged_quantity.')</p>
                              </div>
                            </td>
                            <td>'.$packaged_total_cost.'</td>
                            <td>'.$packaged_order_date.'</td>
                          </tr>';

}



//get confirmed orders
$confirmed_list="";
$confirmed=$user->get_all_confirmed_orders();

while($confirmed_orders=mysqli_fetch_assoc($confirmed)){
    
    $confirmed_order_id=$confirmed_orders['ORDER_ID'];
    $confirmed_product_id=$confirmed_orders['PT_ID'];
    $confirmed_order_date=date("M d h:ia",strtotime($confirmed_orders['ORDER_DATE_FULL']));
    $confirmed_total_cost=$confirmed_orders['TOTAL_COST_OF_ORDER'];
    $confirmed_quantity=$confirmed_orders['ORDERED_QUANTITY'];

    //get product name and picture
    $confirmed_items=$query_guy->find_by_id("PRODUCTS","PRODUCT_ID",$confirmed_product_id);
    $confirmed_product_name=$confirmed_items['PRODUCT_NAME'];

    //get product small images
    $confirmed_small_image=$query_guy->find_by_id("PRODUCT_SMALL_IMAGE","PDS_ID",$confirmed_product_id);
    $confirmed_small_img="pro_images_small".DS.$confirmed_small_image['SMALL_IMAGE_FILE'];

    $confirmed_list.='    <tr>
                            <td>'.$confirmed_order_id.'</td>
                            <td>
                              <div class="col-md-2 order-image-box">
                                <img src="'.$confirmed_small_img.'" class="img-responsive"/>
                                <p>'.ucfirst($confirmed_product_name).' ('.$confirmed_quantity.')</p>
                              </div>
                            </td>
                            <td>'.$confirmed_total_cost.'</td>
                            <td>'.$confirmed_order_date.'</td>
                          </tr>';

}

//get packaged orders
$onroute_list="";
$onroute=$user->get_all_onroute_orders();

while($onroute_orders=mysqli_fetch_assoc($onroute)){
    
    $onroute_order_id=$onroute_orders['ORDER_ID'];
    $onroute_product_id=$onroute_orders['PT_ID'];
    $onroute_order_date=date("M d h:ia",strtotime($onroute_orders['ORDER_DATE_FULL']));
    $onroute_total_cost=$onroute_orders['TOTAL_COST_OF_ORDER'];
    $onroute_quantity=$onroute_orders['ORDERED_QUANTITY'];

    //get product name and picture
    $onroute_items=$query_guy->find_by_id("PRODUCTS","PRODUCT_ID",$onroute_product_id);
    $onroute_product_name=$onroute_items['PRODUCT_NAME'];

    //get product small images
    $onroute_small_image=$query_guy->find_by_id("PRODUCT_SMALL_IMAGE","PDS_ID",$onroute_product_id);
    $onroute_small_img="pro_images_small".DS.$onroute_small_image['SMALL_IMAGE_FILE'];

    $onroute_list.='      <tr>
                           <td>'.$onroute_order_id.'</td>
                            <td>
                              <div class="col-md-2 order-image-box">
                                <img src="'.$onroute_small_img.'" class="img-responsive"/>
                                <p>'.ucfirst($onroute_product_name).' ('.$onroute_quantity.')</p>
                              </div>
                            </td>
                            <td>'.$onroute_total_cost.'</td>
                            <td>'.$onroute_order_date.'</td>
                          </tr>';

}



//get delivered orders
$delivered_list="";
$delivered=$user->get_all_delivered_orders();

while($delivered_orders=mysqli_fetch_assoc($delivered)){
    
    $delivered_order_id=$delivered_orders['ORDER_ID'];
    $delivered_product_id=$delivered_orders['PT_ID'];
    $delivered_order_date=date("M d h:ia",strtotime($delivered_orders['ORDER_DATE_FULL']));
    $delivered_total_cost=$delivered_orders['TOTAL_COST_OF_ORDER'];
    $delivered_quantity=$delivered_orders['ORDERED_QUANTITY'];

    //get product name and picture
    $delivered_items=$query_guy->find_by_id("PRODUCTS","PRODUCT_ID",$delivered_product_id);
    $delivered_product_name=$delivered_items['PRODUCT_NAME'];

    //get product small images
    $delivered_small_image=$query_guy->find_by_id("PRODUCT_SMALL_IMAGE","PDS_ID",$delivered_product_id);
    $delivered_small_img="pro_images_small".DS.$delivered_small_image['SMALL_IMAGE_FILE'];

    $delivered_list.='      <tr>
                            <td>'.$delivered_order_id.'</td>
                            <td>
                              <div class="col-md-2 order-image-box">
                                <img src="'.$delivered_small_img.'" class="img-responsive"/>
                                <p>'.ucfirst($delivered_product_name).' ('.$delivered_quantity.')</p>
                              </div>
                            </td>
                            <td>'.$delivered_total_cost.'</td>
                            <td>'.$delivered_order_date.'</td>
                          </tr>';

}

/*retrieve all followed shops*/
$shops=$user->retrieve_all_followed_shops();
$followed_shop_list='';

while($shop=mysqli_fetch_assoc($shops)){
   
  $fshop_id=$shop['FOLLOWED_SHOP_ID'];
  $shop_id=$shop['SHOP_ID'];

  $seller=$query_guy->find_by_id("SELLERS","SELLER_ID",$shop_id);
  $seller_name=ucfirst($seller['SELLER_NAME']);
  $seller_photo=$seller['SELLER_PROFILE_PIC'];
  $seller_hall=$seller['SELLER_HALL'];

  $followed_shop_list.=' <div class="col-md-3 col-sm-3 main-item-box text-center">
                              <div class="item-box">
                                <a href="vendor?shop='.$shop_id.'"><img class="img img-responsive" src="seller_photos/'.$seller_photo.'"/></a>
                              </div>
                              <div class="item-details">
                                 <p style="font-size:18px;padding-top:10px;">'.$seller_name.'</p>
                              </div>
                              <span title="Unfollow this Vendor"><a href="user-profile?unfollow='.$fshop_id.'"><i class="fa fa-reply fa-2x"></i></a></span>
                        </div>';


}



?>


<?php include("inc/header.php"); ?>
<link rel="stylesheet" href="css/user-profile.css"/>
<link rel="stylesheet" href="css/nav.css"/>
<title><?php echo ucfirst($user->name); ?>-My DiggiMall Account</title>

</head>


 <body>

<!--site navigation-->
<?php include("inc/nav.php"); ?>

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

<div class="container-fluid landing-container">
  <div class="row">
   <div class="col-md-12 text-center">
    <div class="landing">
       <h1 class="wow animated fadeInDown"><?php echo $user->name; ?></h1>
       <p><?php echo $user->phone; ?> - <?php echo $user->hall; ?> </p>
    </div><!--=end landing-->
   </div>
 </div>
</div>

<div class="container-fluid follow-div">
  <div class="row">
    <div class="col-md-12 text-right">
      <p><a href="#myprofile"><i class="fa fa-lock"></i> Click Here To Manage Your Shopping Profile</a></p>
    </div>
  </div>
</div>

<div class="container shopping-activities">
  <div class="row">
    <div class="col-md-12">
      <div class="wishlist">
        <h4><i style="color:red;" class="fa fa-heart"></i> My Wishlist</h4>
      </div>
    </div>
  </div><!--end wishlist row-->
  <div class="row">
    <div class="col-md-12 wishlist-list">
       <div class="row">

         <div class="col-md-8" style="padding:15px;">
           <div class="row">
                 
                 <?php echo $wishlist_display; ?>

               
              <!--end item box-->

           </div><!--end nested row-->
         </div><!--end col-md-8-->
          
          <div class="col-md-4">
            <div class="profile-form" id="myprofile">
             <?php echo $log_error; ?>
              <form class="form" action="user-profile" method="post">
                <div class="form-group">
                  <label class="control-label">Name</label>
                  <input type="text" class="form-control" value="<?php echo $user->name; ?>" name="profile_name"/>
                </div>
                <div class="form-group">
                  <label class="control-label">Phone</label>
                  <input type="text" class="form-control" value="<?php echo $user->phone; ?>" name="phone"/>
                </div>
                <div class="form-group">
                  <label class="control-label">Whatsapp</label>
                  <input type="text" id="whatsapp" class="form-control" value="<?php echo $user->whatsapp; ?>" name="whatsapp"/>
                </div>
                <div class="form-group">
                  <label class="control-label">Email</label>
                  <input type="text" id="email" class="form-control" value="<?php echo $user->email; ?>" name="email"/>
                </div>
                <div class="form-group">
                  <label class="control-label">Hall</label>
                  <select class="form-control" name="hall"/>
                      <option value="default"></option>
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
                      <option value="Old Pent">Old Pent</option>
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
                      <option value="Valco">Valco Hostel</option>
                      <option value="Commonwealth">Commonwealth</option>
                 </select>
                </div>
                <div class="form-group">
                  <label class="control-label">Username</label>
                  <input type="text" class="form-control" value="<?php echo $user->get_username(); ?>" name="username"/>
                </div>
                <div class="form-group">
                  <label class="control-label">Change/Maintain Your Password</label>
                  <input type="password" class="form-control" name="password"/>
                </div>
                <div class="form-group">
                  <input type="submit" class="btn btn-info pull-right" value="Update Profile" name="update_submit"/>
                </div>
              </form>
            </div>
          </div>
   

       </div>
    </div>
  </div><!--end wishlistings-->

  <div class="row orders-row" id="ordersrow">
    <div class="col-md-12">
      <div class="orders">
        <h4><i class="fa fa-file"></i> My Orders</h4>
        <h4 style="background-color:#F9690E;padding:10px;height:auto;color:white">Have you Paid? <button onclick="location.reload();" class="btn btn-default">Yes</button> <button class="btn btn-default" data-toggle="modal" data-target="#howtopay">No</button></h4>
           <ul class="nav nav-tabs">
                <li class="active"><a href="#pending" data-toggle="tab">Pending</span></a></li>
                <li><a href="#confirmed" data-toggle="tab">Confirmed</span></a></li>
                <li><a href="#packaged" data-toggle="tab">Packaged</span></a></li>
                <li><a href="#onroute" data-toggle="tab">On route</span></a></li>
                <li><a href="#delivered" data-toggle="tab">Delivered</span></a></li>
                <li><a href="#return_policy" data-toggle="tab">Return Policy</span></a></li>
            </ul>
            <div id="myTabContent" class="tab-content">
                    <div class="tab-pane active in" id="pending">
                      <h4  class="hidden-sm hidden-md hidden-lg">You may have to Tilt the screen for a Better table view</h4>
                      <table class="table table-striped table-bordered">
                        <thead>
                          <tr>
                            <th>Order ID</th>
                            <th>Item (s)</th>
                            <th>Total Cost (GH&#162;)</th>
                            <th>Order Date</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php echo ($pending_list=="")?"<tr><td>You have Not made Any Orders Yet <a href='mall'>Shop Now</a></td></tr>":$pending_list; ?>
                        </tbody>
                      </table>
                        
                    </div><!--end pending-->
                    <div class="tab-pane fade" id="confirmed">
                       <table class="table table-striped table-bordered">
                        <thead>
                          <tr>
                            <th>Order ID</th>
                            <th>Item (s)</th>
                            <th>Total Cost (GH&#162;)</th>
                            <th>Order Date</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php echo ($confirmed_list=="")?"<tr><td>You have no Confirmed orders yet</td></tr>":$confirmed_list; ?>
                        </tbody>
                      </table>
                    </div><!--end packaged-->
                    <div class="tab-pane fade" id="packaged">
                       <table class="table table-striped table-bordered">
                        <thead>
                          <tr>
                            <th>Order ID</th>
                            <th>Item (s)</th>
                            <th>Total Cost (GH&#162;)</th>
                            <th>Order Date</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php echo ($packaged_list=="")?"<tr><td>You have no Packaged orders</td></tr>":$packaged_list; ?>
                        </tbody>
                      </table>
                    </div><!--end packaged-->
                    <div class="tab-pane fade" id="onroute">
                       <table class="table table-striped table-bordered">
                        <thead>
                          <tr>
                            <th>Order ID</th>
                            <th>Item (s)</th>
                            <th>Total Cost (GH&#162;)</th>
                            <th>Order Date</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php echo ($onroute_list=="")?"<tr><td>You have no orders On route</td></tr>":$onroute_list; ?>
                        </tbody>
                      </table>
                    </div><!--end on route-->
                    <div class="tab-pane fade" id="delivered">
                      <table class="table table-striped table-bordered">
                        <thead>
                          <tr>
                            <th>Order ID</th>
                            <th>Item (s)</th>
                            <th>Total Cost (GH&#162;)</th>
                            <th>Order Date</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php echo ($delivered_list=="")?"<tr><td>No orders have been Delivered just yet</td></tr>":$delivered_list; ?>
                        </tbody>
                      </table>
                    </div><!--end delivered-->
                    <div class="tab-pane fade" id="return_policy">
                        <h4>Can I return a product?</h4>
                        <p>Yes, you can return a product for a refund, within 6 hours after receiving your original order except for Meals.</p>
                        
                        <h4>How to return a product</h4>
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
                    </div><!--end return policy-->
            </div><!--end my tab content-->
      </div>
    </div>
  </div><!--end orders-->

 <div class="row">
    <div class="col-md-12">
      <div class="wishlist">
        <h4><i style="color:red;" class="fa fa-file"></i> My Tickets</h4>
      </div>
    </div>
  </div><!--end tickets row -->

  <div class="row" style="margin-bottom:20px;">
      <?php echo $tickets_display; ?>
  </div>   <!--end wishlist display-->

  <div class="row shops-followed-row">
    <div class="col-md-12">
      <div class="shops-followed">
        <h4><i class="fa fa-star"></i> Vendors I follow</h4>
        <?php echo ($followed_shop_list=="")?"<h4>You are not following any Vendors yet</h4>":$followed_shop_list; ?>

      </div><!--end shops followed-->
    </div>
  </div>
</div>

<!-- <div class="col-md-4 profile">
  <div class="profile-details">
    hello
  </div>
</div>
 -->

  <!--First Time Modal Window-->
                    <div class="modal fade" id="welcome_modal" tabindex="-1"><!--modal div begins-->
                      <div class="modal-dialog"><!--modal dialog begins-->
                        <div class="modal-content"><!-- modal-content begins -->
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span>&times; Close</span></button>
                            <h3 class="modal-title"><img src="images/welcomeemoji.png" class="img img-responsive"/> Welcome Aboard! <?php echo ucfirst($user->name); ?></h3>
                          </div>
                          <div class="modal-body"><!-- modal-body begins -->
                            <p>Your <b>DiggiMall Account</b> is all Set and ready to go.  Giving you the <b>Best Shopping Experience</b>. These are the Cool Features you have Access to that other Unregistered Shoppers don't have:</p>
                            <hr/>
                            <p><i class="fa fa-check"></i> You can Checkout Lightening Fast.</p>
                            <p><i class="fa fa-check"></i> You can Add Build A <b>Wishlist</b>.</p>
                            <p><i class="fa fa-check"></i> You can <b>Submit Product Reviews</b> &amp; Rate Products On DiggimMall.</p>
                            <p><i class="fa fa-check"></i> You can <b>Track your Orders</b> every step of the way.</p>
                            <p><i class="fa fa-check"></i> You can <b>Follow</b> your Favorite Vendors.</p>
                            <p><i class="fa fa-check"></i> Better Shopping <b>Recommendations</b>.</p>
                            <p><i class="fa fa-lock"></i> Above all, all your Shopping Activities and details are totally <b>Private and Secure</b></p>
                            <p>Don't Hesitate to contact us when you got any Challenges or Issues. We will be glad to help you out</p>
                            <hr/>
                           
                           
                          </div><!-- modal-body ends -->
                        </div><!-- modal-content ends -->
                      </div><!--modal dialog ends-->
                    </div><!--modal div ends-->


</div>

<!--contains actual footer-->
<?php include("inc/copyright.php"); ?>


<?php include("inc/footer.php"); ?>
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
<script type="text/javascript">
$(function(){

if($("#email,#whatsapp").val()=="N/A"||$("#email,#whatsapp").val()==""){

     $("#email,#whatsapp").attr("style","border:2px solid red;");
}

});
</script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript">
$(function(){
    
    if($.cookie('pop')==null){
      $("#welcome_modal").modal("show");
      $.cookie('pop','350');
    }
});
</script>
</body>
</html>