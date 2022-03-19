<?php ob_start();

include("inc/cookie_checker.php");


include('functions.php'); 
include('conn'.DS.'db_connection.php'); 
include('classes'.DS.'querying_class.php');
include('classes'.DS.'form_class.php');


$query_guy=new DataQuery();
$form_man=new FormDealer();

?>

<?php include("inc/header.php"); ?>
<link rel="stylesheet" href="css/terms.css"/>
<link rel="stylesheet" href="css/nav.css"/>

<title>Return Policy</title>

</head>


 <body>

<!--site navigation-->
<?php include("inc/nav.php"); ?>

 <div class="container-fluid faq-head-container">
 	<div class="row">
 		<div class="col-md-12">
 			<div class="faq-head text-center">
 				<h3>Our Return Policies are meant to assure you on your purchases on DiggiMall <br/>and prove a guide on how to Return Or Ask for a Refund</h3>
 			</div>
 		</div>
 	</div>
 </div>


<!--actual container-->
<div class="container main-container">

	<div class="row terms-row">
		<h3>Statement from Us</h3>
		<p>We do all our best to make reduce instances where the customer requests for a return and refund for an order made, but as 
		  human as we are, we can't promise that we are rid of mistakes. So in the rare case where a return is strictly necessary, Kindly follow
		  this guide to reclaim your funds or ask for a replacement.
		</p>
	  
	   <h3>Can I return a product?</h3>
		<p>Yes, you can return a product for a refund, within 6 hours after receiving your original order except for Meals &amp; Pharmaceuticals</p>
		
	   <h3>How to return a product</h3>
	   <p>You can request a return by calling support on 054 195 9025 / 0209058871</p>

		<h3>What are the required conditions?</h3>
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

		<h3>What are the next steps?</h3>
		<p>Once your return request done, we will contact you to arrange retrieval of the product. You will also have the choice to deliver yourself the product to one of our pickup Stations.
                            Once the product retrieved, we will proceed to examination.
                           In the unlikely event that an item is returned to us in an unsuitable condition, we will send it back to you without refund.
                            If examination conclusive and conditions respected, we will proceed to refund within maximum 10 business days post retrieval product. You may also opt
                            for a total replacement of the product instead which we will be glad to do for you.
                        </p>

		
	</div>

</div>


  <!--extra items head-->
 <div class="container done-container">
 	<div class="row">
 		<div class="col-md-7 col-md-offset-3 text-center">
 			<h3>We got you covered</h3>
 		</div>
 	</div>
 	
 </div>


<!--call to action-->
 <div class="container call-to-action">
 	<div class="row">
 		<div class="col-md-7 col-md-offset-3"><a href="mall" class="btn btn-danger btn-block"> Back To The Mall <i class="fa fa-arrow-right"></i></a> </div>
 	</div>
 </div>








<!--contains actual footer-->
<?php include("inc/copyright2.php"); ?>


<?php include("inc/footer.php"); ?>
</body>
</html>