<?php ob_start();

include("inc/cookie_checker.php");



include('functions.php'); 
include('conn'.DS.'db_connection.php'); 
include('classes'.DS.'querying_class.php');
include('classes'.DS.'form_class.php');
include('classes'.DS.'filter_class.php');
include('classes'.DS.'admin_class.php');

$query_guy=new DataQuery();
$form_man=new FormDealer();



 ?>

<?php include("inc/header.php"); ?>
<link rel="stylesheet" href="css/faqs.css"/>
<link rel="stylesheet" href="css/nav.css"/>
<link rel="stylesheet" href="css/bootstrap-select.min.css"/>
<title>FAQs</title>

</head>


 <body>

<!--site navigation-->
<?php include("inc/nav.php"); ?>

 <div class="container-fluid faq-head-container">
 	<div class="row">
 		<div class="col-md-12">
 			<div class="faq-head text-center">
 				<h3>Fueled By The Passion To Answer Your Questions</h3>
 			</div>
 		</div>
 	</div>
 </div>


<!--actual container-->
<div class="container main-container">

	<div class="row faq-row">
		<div class="col-md-4">
			<div class="user-question"><h3>What Is DiggiMall?</h3></div>
		</div>
		<div class="col-md-8">
			<div class="diggimall-answer">
				<p>DiggiMall is an online mall developed specifically for students on tertiary campuses. 
				  Our mission is to make online purchasing a natural instinct for students. DiggiMall also
				  exists to help students who engage in selling activities on campus reach a larger market
				  for their items.
				</p>
			</div>
		</div>
	</div>

	<div class="row faq-row">
		<div class="col-md-4">
			<div class="user-question"><h3>How exactly does DiggiMall help me as a student?</h3></div>
		</div>
		<div class="col-md-8">
			<div class="diggimall-answer">
			   <p>That depends on the kind of student you are. If you are a student who engages in some sort of
			   selling business on campus, DiggiMall will help you market your items, sell your items, manage your inventory
			   and deliver your items to customers as well, for you. 
			   </p>
			   <p>
			   	For a student who doesn't necessarily do business, you can purchase all the items you need at every point in time
			   	on DiggiMall and have it delivered directly to you in your hall. Items right from groceries to electronics.
			   </p>
			</div>
		</div>
	</div>


	<div class="row faq-row">
		<div class="col-md-4">
			<div class="user-question"><h3>Why should I Use DiggiMall As A Seller?</h3></div>
		</div>
		<div class="col-md-8">
			<div class="diggimall-answer">
				<p>If you need more customers and want more money from the items you sell on campus, then DiggiMall 
				is for you. Because, on DiggiMall you are showcasing your items to the about 40,000 students
				on your campus without breaking a sweat.</p>
				<p>This beats the traditional way of going round to different halls if you want to sell your items.
				  Its simply a larger market, more money, no stress method for doing your business as a student. 	
				</p>
			</div>
		</div>
	</div>

   <div class="row faq-row">
		<div class="col-md-4">
			<div class="user-question"><h3>Why should I make a purchase on diggimall?</h3></div>
		</div>
		<div class="col-md-8">
			<div class="diggimall-answer">
				<p>Because items you order will be delivered right to you. You also don't face any risk when you purchase
				an item on DiggiMall because you pay only on delivery of the item. You can also return the item if you are not pleased
			   with it by following our return policy. </p>
			   <p>Taking into account the time you will spend going round doing your shopping, money you will spend on transport and
			   	the energy you will use up if you do your shopping traditionally, purchasing on DiggiMall makes you more efficient by
			   	drastically giving you more time to focus on other things and buy anything you need with just a button click.
               </p>
			</div>
		</div>
	</div>


	<div class="row faq-row">
		<div class="col-md-4">
			<div class="user-question"><h3>Are Your Operations Legal?</h3></div>
		</div>
		<div class="col-md-8">
			<div class="diggimall-answer">
				<p>Our operations are legal and we put all measures in place to adhere to laws which govern online transactions.</p>
			</div>
		</div>
	</div>


	<div class="row faq-row">
		<div class="col-md-4">
			<div class="user-question"><h3>How Does The Whole Buying Process Work?</h3></div>
		</div>
		<div class="col-md-8">
			<div class="diggimall-answer">
				<p>Once you are on ug.diggimall.com, click on 'mall' in the navigation.</p>
			    <p>Browse through the products to find what you need and click 'Add To Bag'</p>
			   <p>You can also click on the image of the product to see more details.</p>
			  <p>Click on 'checkout' in the navigation menu or 'Done Shopping? Procced' red link at the lower right 
			  	portion of your screen</p>
			</div>
		</div>
	</div>


	<div class="row faq-row">
		<div class="col-md-4">
			<div class="user-question"><h3>How Do I Sell My Items On DiggiMall?</h3></div>
		</div>
		<div class="col-md-8">
			<div class="diggimall-answer">
				<p>Selling on DiggiMall is an extremely easy process, but first you need to notify us of your interest by
				filling the registration form <a href="seller-registration">here</a>. We will get back to you for a short get to know 
				each other session. From there, you can go directly to uploading your items and making money!!.
				</p>
			</div>
		</div>
	</div>

    <div class="row faq-row">
		<div class="col-md-4">
			<div class="user-question" id="payments"><h3>How Do I Make Payments?</h3></div>
		</div>
		<div class="col-md-8">
			<div class="diggimall-answer">
				<p>Order payments will be made directly to DiggiMall through MTN Mobile Money</p>
				<p><i class="fa fa-check"></i> Dial *170#</p>
				<p><i class="fa fa-check"></i> Select PayBill (No. 2 on the menu)</p>
				<p><i class="fa fa-check"></i> Select General Payment (No. 6 on the menu)</p>
				<p><i class="fa fa-check"></i> Enter "Diggimall" as payment code</p>
				<p><i class="fa fa-check"></i> Enter Amount</p>
				<p><i class="fa fa-check"></i> Enter Reference Number (This your Order ID), which will be given to you after order is made</p>
			    <p><i class="fa fa-check"></i> Enter Your Mobile Money Pin</p>
			    <p><i class="fa fa-check"></i> Wait for confirmation within 48hrs</p>
			</div>
		</div>
	</div>


</div>


  <!--extra items head-->
 <div class="container done-container">
 	<div class="row">
 		<div class="col-md-7 col-md-offset-3 text-center">
 			<h3>Still Have Questions? Contact Support On 0209058871</h3>
 		</div>
 	</div>
 	
 </div>


<!--call to action-->
 <div class="container call-to-action">
 	<div class="row">
 		<div class="col-md-7 col-md-offset-3"><button class="btn btn-danger btn-block"> Back To The Mall <i class="fa fa-arrow-right"></i></button> </div>
 	</div>
 </div>








<!--contains actual footer-->
<?php include("inc/copyright.php"); ?>


<?php include("inc/footer.php"); ?>
<script type="text/javascript" src="js/bootstrap-select.min.js"></script>
</body>
</html>