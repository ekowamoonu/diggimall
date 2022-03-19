<?php ob_start();

//include database connection
include('functions.php'); 
include('conn'.DS.'db_connection.php'); 

include("inc/cookie_checker.php");

//shopper id for cart
//logged in for checkout

/*$shoppers_name="Awura";
$shoppers_id=3;*/


include('classes'.DS.'querying_class.php');
include('classes'.DS.'form_class.php');

$connection=new DB_Connection();
$query_guy=new DataQuery();
$form_man=new FormDealer();
$query_guy=new DataQuery();

if(isset($_GET['shop']))
{
  $vendor_id=$form_man->cleanString($_GET['shop']);
}else{
  header("Location: mall");
}
/*******************/

//check if visitor is logged in
if(isset($_COOKIE['logged_in'])){
  
  $follow_link='<p><a style="color:white;" href="vendor?shop='.$vendor_id.'&&customer=2&&shopnow=039474738&&products=97&&useragent=browserprofile&&time=223438uuf0834983034n3098349848&&follow='.$vendor_id.'&&vendor=5"><i style="color:white" class="fa fa-heart"></i> Click here to Follow this Vendor</a></p>';

}
else{
  $follow_link='<p><a style="color:white;" onclick="alert(\'Oops! You Need to login First to enable this feature\');window.location=\'user-registration\';" href="#"><i style="color:white" class="fa fa-heart"></i> Click here to Follow this Vendor</a></p>';
}
/*********************************/

if(isset($_GET['follow'])){

  $buyer_id=(int)decryptCookie($_COOKIE['logged_in']);
  $insert_following=mysqli_query(DB_Connection::$connection,"INSERT INTO FOLLOWED_SHOPS(SHOP_ID,CSTMR_ID) VALUES('{$vendor_id}','$buyer_id')");
  
  if($insert_following){
    $follow_link='<p><a style="color:white;"  href="#"><i style="color:white" class="fa fa-heart"></i> You are now Following this Vendor</a></p>';
  }

}



//get seller_detail
 //get seller of this item
  $seller=$query_guy->find_by_id("SELLERS","SELLER_ID",$vendor_id);
  $seller_name=ucfirst($seller['SELLER_NAME']);
  $seller_photo=$seller['SELLER_PROFILE_PIC'];
  $seller_type=$seller['SELLER_TYPE'];
  $seller_hall=$seller['SELLER_HALL'];
  $seller_about=$seller['SELLER_ABOUT'];


  $seller_items=$query_guy->find_products_by_seller($vendor_id);
  
  $list_of_products="";

  while($list=mysqli_fetch_assoc($seller_items)) {
      
      $product_id=$list['PRODUCT_ID'];
      $product_name=ucfirst($list['PRODUCT_NAME']);
      $product_price=$list['PRODUCT_PRICE'];

      //get product small images
      $small_image=$query_guy->find_by_id("PRODUCT_SMALL_IMAGE","PDS_ID",$product_id);
      $small_img="pro_images_small".DS.$small_image['SMALL_IMAGE_FILE'];

      $list_of_products.=' <div class="col-md-3 col-sm-3 main-item-box text-center">
                              <div class="item-box">
                                <a href="product-detail?detail='.$product_id.'"><img class="img img-responsive" src="'.$small_img.'"/></a>
                              </div>
                              <div class="item-details">
                                 <h5 >GH&#162; '.$product_price.'</h5>
                                 <p>'.$product_name.'m</p>
                              </div>
                          </div>';

  }


?>


<?php include("inc/header.php"); ?>
<link rel="stylesheet" href="css/vendor.css"/>
<link rel="stylesheet" href="css/nav.css"/>
<title><?php echo $seller_name; ?></title>

</head>


 <body>

<!--site navigation-->
<?php include("inc/nav.php"); ?>

<div class="container-fluid landing-container">
  <div class="row">
   <div class="col-md-12 text-center">
    <div class="landing">
       <h1 class="wow animated fadeInDown"><?php echo $seller_name; ?></h1>
       <p>DiggiMall Shop</p>
    </div><!--=end landing-->
   </div>
 </div>
</div>

<div class="container-fluid follow-div">
  <div class="row">
    <div class="col-md-12 text-right">
      <?php echo $follow_link; ?><!-- <span style="color:white;font-size:18px;"><i class="fa fa-user"></i> 45 followers</span> -->
    </div>
  </div>
</div>

<!--seller details container-->
<div class="container-fluid profile-container">
  <div class="row">
    <div class="col-md-3 col-sm-3">
      <div class="profile-pic">
         <img src="seller_photos/<?php echo $seller_photo; ?>" style="max-width:100%;"  class="img-responsive" alt="Seller's Diggimall Photo"/>
         <p class="first-p"><b>Business:</b> <?php echo $seller_type; ?></p>
         <p><b>Hall/Location:</b> <?php echo $seller_hall; ?></p>
         <p><b>About Me:</b> <?php echo $seller_about; ?></p>
      </div>
    </div>
    <div class="col-md-9 col-sm-9 items">
      <div>
        <h4>Products in My Shop</h4>
        <div class="row">

          <?php echo $list_of_products; ?>

        </div><!--end embedded row-->
      </div>
    </div>
  </div>
</div>

<div class="container-fluid" style="background-color:#eee;padding:10px;margin-bottom:-12px;">
  <div class="row">
    <div class="col-md-12 text-center">
      <h4><a href="mall">Continue Shopping <i class="fa fa-long-arrow-right"></i></a></h4>
    </div>
  </div>
</div>


<!--contains actual footer-->
<?php include("inc/copyright.php"); ?>


<?php include("inc/footer.php"); ?>
<script>
        new WOW().init();
</script>
</body>
</html>