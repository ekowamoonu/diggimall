<?php ob_start();

/*setcookie("shopper_name","",time()-10);
setcookie("shopper_id","",time()-10);*/

include("inc/cookie_checker.php");

/*checker to wishlist heart if user is logged in or hide if user is not logged in*/
if(isset($_COOKIE['logged_in'])){
   $buyer_id=(int)decryptCookie($_COOKIE['logged_in']);
   $wishy_flag=true;
}else{
  $wishy_flag=false;
}

include('functions.php'); 
include('conn'.DS.'db_connection.php'); 
include('classes'.DS.'querying_class.php');
include('classes'.DS.'form_class.php');
include('classes'.DS.'pagination.php');
include('classes'.DS.'filter_class.php');

$query_guy=new DataQuery();
$form_man=new FormDealer();
$filtering=new Filter();

/****************pagination****************************/
//1.the current page number($current_page)
$page=!empty($_GET['page'])?(int)$_GET['page']:1;

//2.records per page($per_page)
$per_page=28;

//find number of all photos
$total_count=$query_guy->countNumber("PRODUCTS");


$pagination=new Pagination($page,$per_page,$total_count);

$p_links="";//variable for pagination links


/**************************************************************************************/
$all_items="";
if(!isset($_GET['sub_cat'])&&!isset($_GET['main_cat'])){

	//get all products
	$all_products=$query_guy->find_all("PRODUCTS",$per_page,$pagination->pagination_offset());
	$vstr_id= $shoppers_id;

	while($product=mysqli_fetch_assoc($all_products)){

	$product_id=$product['PRODUCT_ID'];
	$product_name=(strlen($product['PRODUCT_NAME'])>20)?substr($product['PRODUCT_NAME'],0,20)."...":$product['PRODUCT_NAME'];
	$product_price=$product['PRODUCT_PRICE'];

  /*get sellers name*/
  $product_seller=$product['SEL_ID'];
  $seller_finder=$query_guy->find_by_id("SELLERS","SELLER_ID",$product_seller);
 $seller_name=(strlen($seller_finder['SELLER_NAME'])>15)?substr(strtoupper($seller_finder['SELLER_NAME']),0,15)."...":strtoupper($seller_finder['SELLER_NAME']);

    /********************DISCOUNTS AND PRICING*********************/

      $db_price=$product['PRODUCT_PRICE'];
      $product_discount=$product['DISCOUNT'];

       //discount checks
        if(empty($product_discount)||$product_discount==null||$product_discount=='null'||$product_discount==0){
            
            $normal_price=$db_price;
            $discount="";
            $pricing='<span class="selling-price">GH&#162;'.number_format($normal_price,2).'</span>';
            $purchase_price=$normal_price;
        }

        else if($product_discount>0){

               $actual_discount=$product_discount*100;//get original percentage from decimal
               $discount='<div class="discount-percentage"><span class="inner border-radius">'.$actual_discount.'% Off!</span></div>';
               $normal_price=$db_price*100/(100-$actual_discount);
               $new_price=$db_price;
               $pricing='<span class="cancelled-price">GH&#162; '.number_format($normal_price,2).' </span> <span class="selling-price">GH&#162; '.number_format($new_price,2).'</span>';
               $purchase_price=$new_price;
        }

    /****************END PRICING**************************/

   /*adding to wishlist*/
   $add_to_wishlist= ($wishy_flag)?'<span onclick="add_to_wishlist(\''.$product_id.'\',\''.$buyer_id.'\');" class="wishy w'.$product_id.'" title="Add to my Wishlist"><i class="fa fa-heart fa-2x"></i></span>':"";


    //get image
	$product_small_image_finder=$query_guy->find_by_id("PRODUCT_SMALL_IMAGE","PDS_ID",$product_id);
	$product_small_image="pro_images_small".DS.$product_small_image_finder['SMALL_IMAGE_FILE'];

	$all_items.='<div class="col-md-3 col-sm-4 main-item-box text-center">
							<div class="item-box">'.$add_to_wishlist.'
              '.$discount.'
								<a href="product-detail?detail='.$product_id.'"><img class="img img-responsive" src="'.$product_small_image.'"/></a>
							</div>
							<div class="item-details">
								 <h4>'.$pricing.'<img class="loading'.$product_id.'" src="images/mall_loading.gif" style="display:none;"/></span></h4>
							 	 <p>'.ucfirst($product_name).'</p>
                 <p class="sellers-name">'.$seller_name.'</p>
							 	 <button class="btn btn-default" onclick="mall_now(\''.$vstr_id.'\',\''.$product_id.'\',\''.$purchase_price.'\');"> Add To My Bag</button>
						    </div>
				    </div>';

     }//end while

  			    //pagination here
 				include('inc'.DS.'mall_pagination.php');
 				

}//and if not isset
else{
      
      //intitialise these for pagination links by checking which if they are set or not
      $s_cat=(empty($_GET['sub_cat'])||!isset($_GET['sub_cat'])||!is_numeric($_GET['sub_cat']))?"":$_GET['sub_cat'];
      $m_cat=(empty($_GET['main_cat'])||!isset($_GET['main_cat'])||!is_numeric($_GET['main_cat']))?"":$_GET['main_cat'];

	  //run search filter
	  $all_products=$filtering->run_mall_search($per_page,$pagination->pagination_offset());

	/*$check=mysqli_query(DB_Connection::$connection,"SELECT * FROM PRODUCTS WHERE SUB_CAT_ID=".$s_cat);
	echo mysqli_num_rows($check);*/

	  $vstr_id= $shoppers_id;

		while($product=mysqli_fetch_assoc($all_products)){

		$product_id=$product['PRODUCT_ID'];
		$product_name=(strlen($product['PRODUCT_NAME'])>20)?substr($product['PRODUCT_NAME'],0,20)."...":$product['PRODUCT_NAME'];
		$product_price=$product['PRODUCT_PRICE'];

      /*get sellers name*/
      $product_seller=$product['SEL_ID'];
      $seller_finder=$query_guy->find_by_id("SELLERS","SELLER_ID",$product_seller);
      $seller_name=(strlen($seller_finder['SELLER_NAME'])>15)?substr(strtoupper($seller_finder['SELLER_NAME']),0,15)."...":strtoupper($seller_finder['SELLER_NAME']);

        /********************DISCOUNTS AND PRICING*********************/

      $db_price=$product['PRODUCT_PRICE'];
      $product_discount=$product['DISCOUNT'];

       //discount checks
        if(empty($product_discount)||$product_discount==null||$product_discount=='null'||$product_discount==0){
            
            $normal_price=$db_price;
            $discount="";
            $pricing='<span class="selling-price">GH&#162;'.number_format($normal_price,2).'</span>';
            $purchase_price=$normal_price;
        }

        else if($product_discount>0){

               $actual_discount=$product_discount*100;//get original percentage from decimal
               $discount='<div class="discount-percentage"><span class="inner border-radius">'.$actual_discount.'% Off!</span></div>';
               $normal_price=$db_price*100/(100-$actual_discount);
               $new_price=$db_price;
               $pricing='<span class="cancelled-price">GH&#162; '.number_format($normal_price,2).' </span> <span class="selling-price">GH&#162; '.number_format($new_price,2).'</span>';
               $purchase_price=$new_price;
        }

    /****************END PRICING**************************/

    /*adding to wishlist*/
   $add_to_wishlist= ($wishy_flag)?'<span onclick="add_to_wishlist(\''.$product_id.'\',\''.$buyer_id.'\');" class="wishy w'.$product_id.'" title="Add to my Wishlist"><i class="fa fa-heart fa-2x"></i></span>':"";
	

	    //get image
		$product_small_image_finder=$query_guy->find_by_id("PRODUCT_SMALL_IMAGE","PDS_ID",$product_id);
		$product_small_image="pro_images_small".DS.$product_small_image_finder['SMALL_IMAGE_FILE'];

		$all_items.='<div class="col-md-3 col-sm-4 main-item-box text-center">
								<div class="item-box">'.$add_to_wishlist.'
                '.$discount.'
									<a href="product-detail?detail='.$product_id.'"><img class="img img-responsive" src="'.$product_small_image.'"/></a>
								</div>
								<div class="item-details">
									 <h4>'.$pricing.' <span><img class="loading'.$product_id.'" src="images/mall_loading.gif" style="display:none;"/></span></h4>
								 	 <p>'.ucfirst($product_name).'</p>
                    <p class="sellers-name">'.$seller_name.'</p>
								 	 <button class="btn btn-default" onclick="mall_now(\''.$vstr_id.'\',\''.$product_id.'\',\''.$purchase_price.'\');">Add To My Bag</button>
							    </div>
					    </div>';


	     }//end while
    
           //pagination here
	     //include the appropriate pagination links based on whic get url is set
         if($s_cat=="") {
           include('inc'.DS.'mainmall_pagination.php');
          
         }
         else{
           include('inc'.DS.'submall_pagination.php');
           /* echo "set";*/
         }


}


/*
   1.get all maincategories 
   2. use a nested query to get all subcategories
*/

 /*get all main categories*/
  $main_categories=$query_guy->find_all_main_categories("MAIN_CATEGORY");

  $main_list="";
   while($main_results=mysqli_fetch_assoc($main_categories)){

     $main_cat_name=(strlen($main_results['MAIN_CATEGORY_NAME'])>20)?substr($main_results['MAIN_CATEGORY_NAME'],0,27)."..":$main_results['MAIN_CATEGORY_NAME'];
     $main_cat_id=$main_results['MAIN_CATEGORY_ID'];

     $main_list.='<li class="dropdown">';
     $main_list.='  <a class="dropdown-toggle" data-toggle="dropdown">'.ucfirst($main_cat_name).'<i class="fa fa-plus pull-right"></i></a>';
     $main_list.='   <ul class="dropdown-menu">';
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
/*****************************************************************************/

/*get all sellers*/
$query="SELECT * FROM SELLERS WHERE SELLER_ACCESS=1 ORDER BY SELLER_NAME ASC";/*." ORDER BY MAIN_CATEGORY_NAME ASC";*/
$all_sellers=mysqli_query(DB_Connection::$connection,$query);

$vendors="";

while($dg_seller=mysqli_fetch_assoc($all_sellers)){
    
    $dg_seller_id=$dg_seller['SELLER_ID'];
    $dg_seller_name=$dg_seller['SELLER_NAME'];

    $vendors.='<li><a href="vendor?shop='.$dg_seller_id.'"> <i class="fa fa-user"></i> '.ucfirst($dg_seller_name).'</a></li>';
}



?>
<?php include("inc/header.php"); ?>
<link rel="stylesheet" href="css/mall.css"/>
<link rel="stylesheet" href="css/nav.css"/>

<title>Mall -All Products Display</title>
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

<div class="bag-added-container text-center">
  <h3>Item Added To Bag <i style="color:green;" class="fa fa-check"></i></h3> 
  <h2><a class="btn btn-success proceed_to_checkout" href="#">Proceed To Delivery <i class="fa fa-long-arrow-right"></i></a> <a class="btn btn-danger continue">Continue Shopping <i class="fa fa-shopping-bag"></i></a><h2>
</div>



<div class="container-fluid main-fluid">
	<div class="row"></div><!--for ads -->

	<div class="row"><!--main body-->

		<div class="col-md-3 items-list-side">
			<h4 class="hidden-xs hidden-sm visible-md-block visible-lg-block">More Variety</h4>
      <div class="row">
          <div class="col-xs-6"><h3 class="hidden-lg visible-xs-block visible-sm-block hidden-md btn drawer"  data-toggle="modal" data-target="#filter">All Categories <i class="fa fa-arrow-right"></i></h3></div>
          <div class="col-xs-6"> <h3 class="hidden-lg visible-xs-block visible-sm-block hidden-md btn drawer drawer2"  data-toggle="modal" data-target="#vendors"><b>Vendors List <i class="fa fa-arrow-right"></i></b></h3></div>
      </div><!--end see all vendors and categories row-->
			
     
			<div class="category-details-side text-left hidden-xs hidden-sm visible-md-block visible-lg-block">
	             <ul class="nav">
	             	<?php echo $main_list; ?>
	              
	            </ul>
	        </div>
		</div><!--end categories list-->

		<div class="col-md-9 items-list-col">
        	<h4 class="hidden-xs hidden-sm visible-md-block visible-lg-block">Cool Products!</h4>
           <div class="mall-banner hidden-xs">
          </div>
   
        	<h4 class="quick-h4">
        		  <div class="input-group">
                   <span class="input-group-addon"><i class="fa fa-search"></i></span>
        		   <input type="text" onkeyup="quick_mall_search('<?php echo $shoppers_id; ?>');" class="form-control" placeholder="Search For An Item" id="quick_item_search"/>
              </div>
        	</h4>
          <div class="autosuggest">
          </div>

         
            <div class="col-md-12 col-sm-12 md12">
            	<div class="dynamic-list">

            		<?php echo $all_items; ?>

            	</div><!--end dynamic list-->
           </div><!--item box ends-->

	</div><!--end second row-->


</div><!--end main container-->


                    <div class="container-fluid pagination-container text-center">
                      <div class="row pagination-row">
                        <nav class"pagination-nav">
                          <ol class="pagination pagination-lg">

                          <?php echo $p_links; ?>

                          </ol>
                        </nav>
                      </div>
                    </div>


  <!--extra items head-->
<!--  <div class="container-fluid done-container">
 	<div class="row">
 		<div class="col-md-7 col-md-offset-3 text-center">
 			<h3>Done Shopping?</h3>
 		</div>
 	</div>
 	
 </div> -->

<!--   <h5><a href="bag" class="btn btn-danger">Done Shopping? Proceed <i class="fa fa-arrow-right"></i></a></h5> -->


<!--call to action-->
 <!-- <div class="container call-to-action">
 	<div class="row">
 		<div class="col-md-7 col-md-offset-3"><a class="btn btn-danger btn-block" href="checkout">Proceed To Delivery <i class="fa fa-arrow-right"></i></a> </div>
 	</div>
 </div> -->

		    

                    <!--edit call_line modal-->
                    <div class="modal fade" id="filter" tabindex="-1"><!--modal div begins-->
                      <div class="modal-dialog"><!--modal dialog begins-->
                        <div class="modal-content"><!-- modal-content begins -->
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span>&times; Close</span></button>
                            <h3 class="modal-title">Products Filter</h3>
                          </div>
                          <div class="modal-body"><!-- modal-body begins -->
                             <div class="drawer-body col-md-3">
					                <div class="category-details-side text-left">
    						             <ul class="nav">
    						             	<?php echo $main_list; ?>
    						              
    						            </ul>
          						        </div>
          	        				 </div>
                       
                          </div><!-- modal-body ends -->
                        </div><!-- modal-content ends -->
                      </div><!--modal dialog ends-->
                    </div><!--modal div ends-->

                     <!--edit call_line modal-->
                    <div class="modal fade" id="vendors" tabindex="-1"><!--modal div begins-->
                      <div class="modal-dialog"><!--modal dialog begins-->
                        <div class="modal-content"><!-- modal-content begins -->
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span>&times; Close</span></button>
                            <h3 class="modal-title">DiggiMall Vendors</h3>
                          </div>
                          <div class="modal-body"><!-- modal-body begins -->
                             <div class="drawer-body col-md-3">
                          <div class="category-details-side text-left">
                             <ul class="nav">
                              <?php echo $vendors; ?>
                              
                            </ul>
                              </div>
                             </div>
                       
                          </div><!-- modal-body ends -->
                        </div><!-- modal-content ends -->
                      </div><!--modal dialog ends-->
                    </div><!--modal div ends-->





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
<script type="text/javascript">
 $(function(){
    $(".dropdown").click(            
        function() {
            $('.dropdown-menu', this).stop( true, true ).slideToggle("fast");
            $(this).toggleClass('open');        
        });

});
</script>
<script type="text/javascript" src="js/mallnow.js"></script>
<script type="text/javascript" src="js/category_parser.js"></script>
<script type="text/javascript">
$(function(){


   $(".drawer").click(function(){

   	      $(".drawer-body").css("left","0px");

   });

    $(".drawer-body p").click(function(){

   	       $(".drawer-body").css("left","-900px");
   });

    $(".continue").click(function(){
      
      $(".bag-added-container").attr("style","display:none;");

    });

});
</script>
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
      $(".proceed_to_checkout").click(function(){
            $("#checkout_options").modal("show");
      });
  });
</script>
</body>
</html>