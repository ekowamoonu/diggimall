<?php ob_start();

include("inc/cookie_checker.php");
//shopper id for cart
//logged in for checkout

//include database connection
include('functions.php'); 
include('conn'.DS.'db_connection.php'); 
include('classes'.DS.'querying_class.php');
include('classes'.DS.'form_class.php');

$query_guy=new DataQuery();
$form_man=new FormDealer();

/*****************random number generator*************************/
function crypto_rand_secure($min,$max)
{
    $range = $max - $min;
    if($range < 0)
      return $min;
    $log = log($range, 2);
    $bytes = (int) ($log/8) + 1; //length in bytes
    $bits = (int) $log + 1; //length in bits
    $filter = (int) (1 << $bits) - 1; //set all lower bits to 1
    do{
      $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
      $rnd = $rnd & $filter; //discard irrelevant bits
    }while($rnd >= $range);
    
    return $min + $rnd;
}


function getToken($length = 8)
{
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet .= "0123456789";
    for($i=0;$i<$length;$i++){
      $token .= $codeAlphabet[crypto_rand_secure(0,strlen($codeAlphabet))];
    }
    
    return $token;
}
/*******************************************/

$first_head="";
$second_info="";
$checkout_form="";
$checkout_prompt="";
  //get total amount
   $total_query=$query_guy->find_by_col_and_sum("ORDERED_AMOUNT*PRODUCT_PRICE","TOTAL_COST","BAG_ITEMS","VSTR_ID",$shoppers_id);
   $get_feedback=mysqli_fetch_assoc($total_query);
   $total_damage=$get_feedback['TOTAL_COST']; 

   if($total_damage==0||$total_damage==""||$total_damage==0.00||$total_damage=0.0){
   	  header("Location: mall");
   }
   else{
   	  $total_damage=$get_feedback['TOTAL_COST']; 
   }


  /***********************************THE CHECKING OUT PROCESS************************************************/ 

  //Guest Checkout
  /*
  1. Process the Guest Form
  2. Read All Items From Cart Using The Shopping ID
  3.Calculate total cost of order as you read
  4.Get Product Details As You Read
  --deduct from available stock if pre order is 0
  3. Insert Into Orders Table On By One As You Read
  4. Once Reading Is Complete.. clear the user's shopping bag using the shoppers id
  5. Redirect To A Page For The User To Shop Again Or Complete Shopping
  6. - shop again -> mall.php
	 -complete shopping -> destroy shoppers cookie and go back to welcome page	

  */

$guest_info="An account will be automatically created for you to Speed up your Checkout next time.";
 
 if(isset($_POST['guest_submit'])){

	if($form_man->emptyField($_POST['g_name'])||
		$form_man->emptyField($_POST['g_phone'])||
		$form_man->emptyField($_POST['g_hall'])||
    $form_man->emptyField($_POST['g_username'])||
     $form_man->emptyField($_POST['g_password'])
		){
             
             $guest_info="<span style='color:red;'>Incomplete Checkout Details</span>";
	}//end first nested if
   else{

   	      $guest_name=$form_man->cleanString($_POST['g_name']);
   	      $guest_phone=$form_man->cleanString($_POST['g_phone']);
   	      $guest_hall=$form_man->cleanString($_POST['g_hall']);
          $guest_username=$form_man->cleanString($_POST['g_username']);
          $gst_password=$form_man->cleanString($_POST['g_password']);
          $guest_password=password_hash($gst_password,PASSWORD_BCRYPT,['cost'=>11]);

          //referor id
          $buyer_referor= $form_man->emptyField($_POST['b_referor'])?"N/A":$form_man->cleanString($_POST['b_referor']);

          /*firstly, create the buyers account with the guest checkout details*/
          /*insert guest details into the database*/
          $buyer_insert="INSERT INTO BUYERS(BUYER_USERNAME,BUYER_PASSWORD,BUYER_NAME,BUYER_PHONE,BUYER_WHATSAPP,BUYER_EMAIL,BUYER_HALL,BUYER_ROOM_NUMBER,BUYER_NUMBER_OF_ORDERS,REFEROR_ID) VALUES(";
          $buyer_insert.="'{$guest_username}',";
          $buyer_insert.="'{$guest_password}',";
          $buyer_insert.="'{$guest_name}',";
          $buyer_insert.="'{$guest_phone}',";
          $buyer_insert.="'N/A',";
          $buyer_insert.="'N/A',";
          $buyer_insert.="'{$guest_hall}',";
          $buyer_insert.="'N/A',";
          $buyer_insert.="0,";
          $buyer_insert.="'{$buyer_referor}')";

          $buyer_query=mysqli_query(DB_Connection::$connection,$buyer_insert);
          
          //get buyer unique id after insert
          $byr_id=mysqli_insert_id(DB_Connection::$connection);


   	      //shopping id
   	      $shop_id=$shoppers_id;

   	      //get date details
   	      $order_date=strftime("%Y-%m-%d %H:%M:%S", time());
    		  $order_year=date("Y",strtotime($order_date));//eg 2016
    		  $order_month=date("F",strtotime($order_date));//full representation of month
    		  $order_number_date=date("j",strtotime($order_date));//date eg 16,21
    		  $order_week_day=date("l",strtotime($order_date));//full rep of day of the week

   	      //reading all items form cart
		  $shoppers_items="SELECT * FROM BAG_ITEMS WHERE VSTR_ID=".$shop_id;
		  $shoppers_query=mysqli_query(DB_Connection::$connection,$shoppers_items);
		  $bag_list="";

		   while($get_bag=mysqli_fetch_assoc($shoppers_query)){

   	 	 	     $bag_item_price=$get_bag['PRODUCT_PRICE'];
   	 	 	     $bag_item_quantity=$get_bag['ORDERED_AMOUNT'];
   	 	 	     $bag_item_requirements=$get_bag['REQUIREMENTS'];
   	 	 	     $bag_item_total=$bag_item_price*$bag_item_quantity;

   	 	 	     $bag_item_id=$get_bag['PRDT_ID'];

   	 	 	     //get bag item details (name,main,sub,subset)
                 $bag_name_checker=$query_guy->find_by_id("PRODUCTS","PRODUCT_ID",$bag_item_id);
                 $bag_item_name=ucfirst($bag_name_checker['PRODUCT_NAME']);
                 $bag_item_main=$bag_name_checker['MAIN_CAT_ID'];
                 $bag_item_sub=$bag_name_checker['SUB_CAT_ID'];
                 $bag_item_ss=$bag_name_checker['SUB_S_ID'];
                 $bag_item_seller_id=$bag_name_checker['SEL_ID'];
                 $bag_item_pre_order=$bag_name_checker['PRE_ORDER'];

                 //for recommended cookie
                 $recommended_sub=$bag_item_sub;

                 //if not pre_order ..delete from avaiable stock and update ordered quantity for that product
                 if($bag_item_pre_order==0){
                 	$available_stock_deduction="UPDATE PRODUCTS SET AVAILABLE_STOCK=AVAILABLE_STOCK-".$bag_item_quantity.", TOTAL_ORDERED_QUANTITY=TOTAL_ORDERED_QUANTITY+".$bag_item_quantity." WHERE PRODUCT_ID=".$bag_item_id;
                    $available_stock_query=mysqli_query(DB_Connection::$connection,$available_stock_deduction);
                 }

                 //if pre_order ..update ordered quantity for that product
                 else if($bag_item_pre_order==1){
                   $available_stock_deduction="UPDATE PRODUCTS SET TOTAL_ORDERED_QUANTITY=TOTAL_ORDERED_QUANTITY+".$bag_item_quantity." WHERE PRODUCT_ID=".$bag_item_id;
                    $available_stock_query=mysqli_query(DB_Connection::$connection,$available_stock_deduction);
                 }


              $random_token=getToken(6);

              //now insert items into orders table
		   	      $order_insert="INSERT INTO ORDERS(PT_ID,MC_ID,SC_ID,SS_ID,SLR_ID,CUSTOMER_ID,CUSTOMER_NAME,CUSTOMER_PHONE,CUSTOMER_HALL,";
		   	      $order_insert.="ORDERED_QUANTITY,TOTAL_COST_OF_ORDER,SPECIAL_REQUEST,ORDER_DATE_FULL,ORDER_YEAR,ORDER_MONTH,ORDER_NUMBER_DATE,ORDER_WEEK_DAY,DELIVERY_STATUS,RANDOM_TOKEN) VALUES(";
		   	      $order_insert.="'{$bag_item_id}',";
		   	      $order_insert.="'{$bag_item_main}',";
		   	      $order_insert.="'{$bag_item_sub}',";
		   	      $order_insert.="'{$bag_item_ss}',";
		   	      $order_insert.="'{$bag_item_seller_id}',";
              $order_insert.="'{$byr_id}',";
		   	      $order_insert.="'{$guest_name}',";
		   	      $order_insert.="'{$guest_phone}',";
		   	      $order_insert.="'{$guest_hall}',";
		   	      $order_insert.="'{$bag_item_quantity}',";
		   	      $order_insert.="'{$bag_item_total}',";
		   	      $order_insert.="'{$bag_item_requirements}',";
		   	      $order_insert.="'{$order_date}',";
		   	      $order_insert.="'{$order_year}',";
		   	      $order_insert.="'{$order_month}',";
		   	      $order_insert.="'{$order_number_date}',";
		   	      $order_insert.="'{$order_week_day}',";
		   	      $order_insert.="'pending',";
              $order_insert.="'{$random_token}')";

				  $order_query=mysqli_query(DB_Connection::$connection,$order_insert);

				  if($order_query){
                       
                       $checkout_prompt='<div class="container checkout-prompt">
                                            <div class="row">
                                              <div class="col-md-12">
                                                <h4 class="text-center"><img src="images/mall_loading.gif" class="img img-responsive"/>Processing Your Order....</h4>
                                              </div>
                                            </div>
                                          </div>';

                       $guest_info="<span style='color:green;'>Checkout Complete! Notifying DiggiMall...just a few seconds</span>";

				  }
		          
	       }//end if fetching shoppers query

    /*clear shoppers details from*/
    $bag_clearance=$query_guy->delete_by_id("BAG_ITEMS","VSTR_ID", $shoppers_id);

    setcookie("recommended_sub",$recommended_sub,time()+31556926);

    /*set  buyer cookie*/
    $lgd_id=encryptCookie($byr_id);
    setcookie("logged_in",$lgd_id,time()+31556926);
    setcookie("sms_number",$guest_phone,time()+400);
    setcookie("sms_name",$guest_name,time()+400);

    if($bag_clearance){

    	header("Refresh: 3;url='thankyou'");
    }
   	      

   }//end first nested else

}//end main if	 


?>


<?php include("inc/header.php"); ?>
<link rel="stylesheet" href="css/checkout.css"/>
<link rel="stylesheet" href="css/nav.css"/>
<title>Checkout</title>
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

<?php echo $checkout_prompt; ?>


<div class="container checkout-main">

	<div class="col-md-3">
		<div class="row">
			<div class="blue-div">
				<h2 class="text-center">Total Purchase</h2>
				<h2 class="text-center"><b><a href="bag" style="color:white;">GH&#162; <?php echo number_format($total_damage,2); ?></a></b></h2>
				<ul class="nav hidden-xs">
					<li><i class="fa fa-check"></i> Flexible Payment</li>
					<li><i class="fa fa-check"></i> Fast Delivery Time</li>
					<li><i class="fa fa-check"></i> Refundable Cash</li>
					<li><i class="fa fa-check"></i> Returnable Goods</li>
				</ul>
        <h4 class="text-center"><a href="bag" style="color:white;text-decoration:underline;">Not Sure? Review Your Order</a></h4>
			</div>
		</div>
		<div class="row hidden-xs hidden-sm">
			<div class="grey-div">
				<h3 class="text-center">Need Help?</h3>
				<p class="text-center">We are here to support you right from the moment when you order an item to making sure
					you receive it.</p>
				<p class="text-center" style="padding-top:20px;"><b><i class="fa fa-phone"></i> 0209058871 , 0209134512</b></p>
				<p class="text-center"><b><i class="fa fa-envelope"></i> support@diggimall.com</b></p>
			</div>
		</div>
	</div>

	<div class="col-md-9">
     <div class="checkout-forms">


      
             <!--  <div class="col-md-4"><img src="images/checkout.jpg" class="img img-responsive checkoutimg"/></div> -->
    
      
    			<div class="row">
    				 <div class="guest">
    					<div class="col-md-8">
    						<h3 style="color:black;">New To Diggimall?</h3>
    						<p><?php echo $guest_info; ?></p>
    					</div>
    					<div class="col-md-4"><img src="images/checkout.jpg" class="img img-responsive checkoutimg"/></div>
    			 	 </div>
    			</div><!--end row-->

    			<div class="row guest-checkout">
    			   <form class="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    				<div class="col-md-6">
    					   <label class="control-label">Your Name (For Delivery Identification):</label>
    		               <input type="text" name="g_name" class="form-control"/>

    		               <label class="control-label" for="phone">Call Line (To Contact You):</label>
    		               <input type="text" name="g_phone" class="form-control"/>

                             <label class="control-label">Your Location/Hall:</label>
                    <select class="form-control" name="g_hall">
                            <optgroup label="To My Hall">
                            <option value="default"></option>
                            <option value="Jean Nelson Aka">Jean Nelson Aka</option>
                            <option value="Alex Kwapong">Alex Kwapong</option>
                            <option value="Hilla Limann">Hilla Limann</option>
                            <option value="Elizabeth Sey">Elizabeth Sey</option>
                            <option value="Ish 1">International Students Hostel 1</option>
                            <option value="Ish 2">International Students Hostel 2</option>
                            <option value="Jubilee">Jubilee</option>
                            <option value="Pentagon">Pentagon</option>
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
                           
                  </select>
                           
    				</div>
    				<div class="col-md-6">
    				          
                       <label class="control-label">Who told you about us? (optional):</label>
                       <input type="text" name="b_referor" class="form-control" placeholder="Enter Person's Phone Number"/>

                       <label class="control-label" for="username">Your Username (it's one-time):</label>
                       <input type="text" name="g_username" class="form-control"/>

    		               <label class="control-label">Your Password:</label>
    		               <input name="g_password" type="password" class="form-control"/>

    		              <input type="submit" name="guest_submit" value="Deliver My Order!" class="btn btn-success pull-right"/>
    				</div>
    			 </form> 
			</div>


		</div><!--end checkout forms-->

   </div><!--col-md-9-->
</div>

<!--forms-->
<!-- <div class="container-fluid registration-forms-container">
	<div class="row">
       <div class="col-md-5 col-sm-5 col-md-offset-2 auth-form md5">
			<h3>New Shopper? Signup Here <i class="fa fa-user"></i></h3>
			<form class="form" action="contact.php" method="post">
			   <label class="control-label" for="name">Your Name:</label>
               <input type="text" name="rname" class="form-control"/>

               <label class="control-label" for="email">Email:</label>
               <input type="email" name="remail" class="form-control"/>

               <label class="control-label" for="phone">Phone:</label>
               <input type="text" name="rphone" class="form-control"/>

                <label class="control-label" for="phone">Your Hall:</label>
                <select class="selectpicker form-control">
				  <option value="default">Choose Your Hall</option>
				  <option value="">Phones</option>
				  <option value="">Phones</option>
				  <option value="">Phones</option>
				  <option value="">Phones</option>
				  <option value="">Phones</option>
				</select>


               <label class="control-label" for="email" style="margin-top:7%;">Username:</label>
               <input type="email" name="remail" class="form-control"/>

               <label class="control-label" for="phone">Password:</label>
               <input type="text" name="rphone" class="form-control"/>

               <input type="submit" name="project_submit" value="Join Diggimall Shoppers!" class="btn btn-success pull-right"/>
			</form>
            
		</div>
		<div class="col-md-5">
		    <img src="images/seller.jpg" class="img img-responsive whyimg"/>
			<p class="first-p">Hello Gilbert! Please Take Note</p>
			<p class="others">
				<ul class="nav whylist">
					<li><i class="fa fa-check"></i> More convenience and faster checking out.</li>
					<li><i class="fa fa-check"></i> A more personalised shopping experience.</li>
					<li><i class="fa fa-check"></i> Better shopping recommendations.</li>
				</ul>
			</p>
	   </div>

	</div> --><!--end login row-->

<!-- </div> -->

  <!--extra items head-->
 <div class="container done-container">
 	<div class="row">
 		<div class="col-md-7 col-md-offset-3 text-center">
 			<h3>Not So Sure Of What You Have Ordered?</h3>
 		</div>
 	</div>
 	
 </div>


<!--call to action-->
 <div class="container call-to-action">
 	<div class="row">
 		<div class="col-md-7 col-md-offset-3"><a href="bag" class="btn btn-danger btn-block">   Review You Shopping Bag <i class="fa fa-arrow-right"></i></a> </div>
 	</div>
 </div>








<!--contains actual footer-->
<?php include("inc/copyright2.php"); ?>


<?php include("inc/footer.php"); ?>
</body>
</html>