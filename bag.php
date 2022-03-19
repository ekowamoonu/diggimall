<?php ob_start();

include("inc/cookie_checker.php");

//include database connection
include('functions.php'); 
include('conn'.DS.'db_connection.php'); 
include('classes'.DS.'querying_class.php');
include('classes'.DS.'form_class.php');

$query_guy=new DataQuery();
$form_man=new FormDealer();

$log_error="";

//if user decides to remove
if(isset($_GET['remove'])&&isset($_GET['bagname'])){
	$remove_id=$form_man->cleanString($_GET['remove']);
	$remove_name=$_GET['bagname'];
	$remove_query=$query_guy->delete_by_id("BAG_ITEMS","BAG_ID",$remove_id);

	if($remove_query){
            
            $log_error=$form_man->showError($remove_name." removed from your shopping bag",2);
            header("Refresh: 1;url='bag'");

	}
}//end if get remove


/*
   1. read all items of user from cart using the shoppers id
   2.get price
   3.get ordered quantity
   4.multiply to get total amount
   5.get requirements
   6.get image

   6.sum all prices
   7.sum all ordered quantities
*/


   //reading all items form cart
   $shoppers_items="SELECT * FROM BAG_ITEMS WHERE VSTR_ID=".$shoppers_id;
   $shoppers_query=mysqli_query(DB_Connection::$connection,$shoppers_items);
   $bag_list="";

   $number_of_bag_items=mysqli_num_rows($shoppers_query);

   if($number_of_bag_items==0){header("Location: mall");}
   else{

   	 	 while($get_bag=mysqli_fetch_assoc($shoppers_query)){

                 $bag_id=$get_bag['BAG_ID'];
   	 	 	     $bag_item_price=$get_bag['PRODUCT_PRICE'];
   	 	 	     $bag_item_quantity=$get_bag['ORDERED_AMOUNT'];
   	 	 	     $bag_item_requirements=$get_bag['REQUIREMENTS'];
   	 	 	     $bag_item_total=$bag_item_price*$bag_item_quantity;

   	 	 	     $bag_item_id=$get_bag['PRDT_ID'];

   	 	 	     //get bag item name
                 $bag_name_checker=$query_guy->find_by_id("PRODUCTS","PRODUCT_ID",$bag_item_id);
                 $bag_item_name=ucfirst($bag_name_checker['PRODUCT_NAME']);

   	 	 	     //get image
				 $bag_small_image_finder=$query_guy->find_by_id("PRODUCT_SMALL_IMAGE","PDS_ID",$bag_item_id);
				 $bag_small_image="pro_images_small".DS.$bag_small_image_finder['SMALL_IMAGE_FILE'];

				 //now use details for bag list
				 $bag_list.='<div class="row cart-row">
									<div class="col-md-3 col-lg-3 col-sm-3 md3">
										<div class="cart-image"><a href="product-detail?detail='.$bag_item_id.'"><img src="'.$bag_small_image.'" class="img img-responsive"/></a></div>
									</div>
									<div class="col-md-6 col-sm-6 col-lg-6 md6">
										<h3>'.$bag_item_name.'</h3>
										<h4 class="first-h4"><b>Price:</b> GH&#162;'.$bag_item_price.'</h4>
										<h4><b>Quantity Ordered:</b><span class="totalor'.$bag_id.'"> '.$bag_item_quantity.'</span></h4>
										<h4><b>Your Requirements:</b> '.$bag_item_requirements.'</h4>
										<hr/>
										<h4 class="h4btn"><button onclick=\'add("'.$bag_id.'");\' class="btn btn-default"><i class="fa fa-plus"></i></button><button onclick=\'minus("'.$bag_id.'");\' class="btn btn-default second"><i class="fa fa-minus"></i></button><span><img class="'.$bag_id.'" src="images/mall_loading.gif" style="display:none;"/></span></h4>
								   </div>
									<div class="col-md-3 col-sm-3 col-lg-3 text-center price">
										<h2 class="total'.$bag_id.'">GH&#162; '.number_format($bag_item_total,2).'</h2>
										<a href="bag?6373847464833847478484848848bagdetails=2&&bag=3&&bagname='.$bag_item_name.'&&remove='.$bag_id.'" class="btn btn-danger">Remove This Item</a>
									</div>
							</div>';


         }//end while
    }//end if not empty
  

  //get total amount
   $total_query=$query_guy->find_by_col_and_sum("ORDERED_AMOUNT*PRODUCT_PRICE","TOTAL_COST","BAG_ITEMS","VSTR_ID",$shoppers_id);
   $get_feedback=mysqli_fetch_assoc($total_query);
   $total_damage=$get_feedback['TOTAL_COST']; 



?>


<?php include("inc/header.php"); ?>
<link rel="stylesheet" href="css/bag.css"/>
<link rel="stylesheet" href="css/nav.css"/>
<title>My Shopping Bag</title>

</head>


 <body>

<!--site navigation-->
<?php include("inc/nav.php"); ?>

<div class="bag-added-container text-center">
	<h3>Ordered Quantity Modified</h3>
</div>



<!--cart container-->
<div class="container cart-container">
	<?php echo $log_error; ?>
	<div class="row"><h4 class="cart-head"><?php echo ucfirst($shoppers_name); ?>, These Are The Items In Your Shopping Bag</h4></div>
	<?php echo $bag_list; ?>
	
    <div class="row cart-row">
		<div class="col-md-3 col-lg-3 col-sm-3 md3">
			<div class="cart-final"></div>
		</div>
		<div class="col-md-6 col-sm-6 col-lg-6 md6">

			<h4 class="first-h4" style="padding-top:40px;"><b>Delivery Cost (Campus Meals):</b> GH&#162; 1.00 (Payment On Delivery)</h4>
			<h4 class="first-h4"><b>Delivery Cost (Other items):</b> GH&#162; 5.00  (Payment Into Our Account)</h4>
			<h4><b style="color:red;">Delivery Cost Is Not Factored In Your Total Cost</b> </h4>
			<h4><b style="color:green;"><i class="fa fa-check"></i> Items Purchased Are Returnable</b></h4>
			<h4><b style="color:green;"><i class="fa fa-check"></i> Delivery To Your Hall</b></h4>
			<h4><b style="color:green;"><i class="fa fa-check"></i> Your Cash Is Refundable</b></h4>
			<hr/>
			
	   </div>
		<div class="col-md-3 col-sm-3 col-lg-3 text-center price">
			<h4>Total</h4>
			<h3><b class="overall_total">GH&#162; <?php echo number_format($total_damage,2); ?></b></h3>
			<!--this hidden input is meant for the bag operations)getting the total-->
			<input type="hidden" id="hidden_total_input" value="<?php echo $total_damage; ?>"/>
			<a href="#" data-toggle="modal" data-target="#checkout_options" class="btn btn-info">CHECKOUT <i class="fa fa-arrow-right"></i></a>
		</div>
	</div>
</div>





  <!--extra items head-->
 <div class="container done-container">
 	<div class="row">
 		<div class="col-md-12 text-center">
 			<h3>Are You Really Done Shopping, <?php echo ucfirst($shoppers_name); ?> ?</h3>
 		</div>
 	</div>
 	
 </div>

 <!--call to action-->
 <div class="container call-to-action">
 	<div class="row">
 		<div class="col-md-6"><a href="mall" class="btn btn-info btn-block"> <i class="fa fa-shopping-bag"></i>  No. Shop More Items</a> </div>
 		<div class="col-md-6"><a  href="checkout" class="btn btn-danger btn-block">   Yes. Checkout <i class="fa fa-arrow-right"></i></a> </div>
 	</div>
 </div>





<!--contains actual footer-->
<?php include("inc/copyright2.php"); ?>


<?php include("inc/footer.php"); ?>
<script type="text/javascript" src="js/jquery.elevateZoom-3.0.8.min.js"></script>
<script type="text/javascript">
$(function(){
	$("#zoom").elevateZoom({easing : true});
});

</script>
<script type="text/javascript" src="js/bag_edit.js"></script>
</body>
</html>