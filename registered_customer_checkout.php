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


/*check if user is already logged in*/   
//if already logged in, then just display a deliver my order button
if(isset($_COOKIE['logged_in'])){

	$first_head="Returning Customer";
	$second_info="You are already logged in. Simply Click 'Deliver My Order'";
	$checkout_form='<form class="form" action="registered_customer_checkout" method="post" style="padding-top:10px;">
					   	<div class="col-md-6 col-md-offset-3 col-lg-offset-3 col-lg-6">
				            <input type="submit" name="user_checkout" value="Deliver My Order!" class="btn btn-block btn-success"/>
						</div>
					 </form> ';

}//end if isset cookie logged in
else{

    $first_head="Registered User";
	$second_info="Simply Login And Click 'Deliver My Order'";
	$checkout_form=' <form class="form" action="registered_customer_checkout" method="post">
							<div class="col-md-6">
								   <label class="control-label">Username/Phone:</label>
					               <input type="text" name="username" class="form-control"/>

					               <label class="control-label">Password:</label>
					               <input type="password" name="password" class="form-control"/>
                          <input type="submit" name="now_logging_submit" value="Login" class="btn btn-success pull-right"/>

							</div>
					 </form> ';

}//and if not logged in


 /**************************************************************************************************************/
 //if user is now logging in
 /*user logging in*/

if(isset($_POST['now_logging_submit'])){
    if($form_man->emptyField($_POST['username'])||
       $form_man->emptyField($_POST['password'])
      ){
       $second_info="<span style='color:red'>Illegal Login Attempt!</span>";
    }

    else{
            $username=$form_man->cleanString($_POST['username']);
            $password=$form_man->cleanString($_POST['password']);


            $pass_check="SELECT * FROM BUYERS WHERE BUYER_USERNAME='{$username}' OR BUYER_PHONE='{$username}'";//select record from table using username
            $res=mysqli_query(DB_Connection::$connection,$pass_check);

           /* if(!$res){echo "failed".mysqli_error(DB_Connection::$connection);}*/
            $record= mysqli_fetch_assoc($res);

            if(password_verify($password,$record['BUYER_PASSWORD'])){

                            /*$_SESSION['login_id']=$record['POTO_ID'];*/
                            $lgd_id=encryptCookie($record['BUYER_ID']);
                            $logged_id=$lgd_id;
                            setcookie("logged_in",$logged_id,time()+31556926);
                            $second_info="<span style='color:green'>Cool! Login Successful..Page Will Reload In A Moment</span>";
                            header("Refresh: 4;url='registered_customer_checkout'");
                        }
            else{
                 $second_info="<span style='color:red'>Illegal Login Attempt!</span>";
            }

       }

    } 

  /***********************************THE CHECKING OUT PROCESS************************************************/ 

  

/**************************************************************************************************************************/

/**************************************************REGISTERED USER CHECKOUT**********************************************/
  //User Checkout
  /*
  1. Get User Details From Database using logged_in cookie
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

 if(isset($_POST['user_checkout'])){

   	      //shopping id
   	      $shop_id=$shoppers_id;
   	      $user_id=(int)decryptCookie($_COOKIE['logged_in']);

   	      //find user details
   	      $user_checker=$query_guy->find_by_id("BUYERS","BUYER_ID",$user_id);
   	      $buy_id=$user_checker['BUYER_ID'];
          $bname=$user_checker['BUYER_NAME'];
          $bphone=$user_checker['BUYER_PHONE'];
          $bhall=$user_checker['BUYER_HALL'];
          $breferror=$user_checker['REFEROR_ID'];
          $buyer_referror=($breferror=="null"||$breferror==""||empty($breferror))?"N/A":$breferror;

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

                 //update number of orders for customer
                 $buyer_orders_update=mysqli_query(DB_Connection::$connection,"UPDATE BUYERS SET BUYER_NUMBER_OF_ORDERS=BUYER_NUMBER_OF_ORDERS+".$bag_item_quantity." WHERE BUYER_ID=".$user_id);

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
		   	      $order_insert.="ORDERED_QUANTITY,TOTAL_COST_OF_ORDER,SPECIAL_REQUEST,ORDER_DATE_FULL,ORDER_YEAR,ORDER_MONTH,ORDER_NUMBER_DATE,ORDER_WEEK_DAY,DELIVERY_STATUS,BUYER_REFEROR_ID,RANDOM_TOKEN) VALUES(";
		   	      $order_insert.="'{$bag_item_id}',";
		   	      $order_insert.="'{$bag_item_main}',";
		   	      $order_insert.="'{$bag_item_sub}',";
		   	      $order_insert.="'{$bag_item_ss}',";
		   	      $order_insert.="'{$bag_item_seller_id}',";
		   	      $order_insert.="'{$user_id}',";
		   	      $order_insert.="'{$bname}',";
		   	      $order_insert.="'{$bphone}',";
		   	      $order_insert.="'{$bhall}',";
		   	      $order_insert.="'{$bag_item_quantity}',";
		   	      $order_insert.="'{$bag_item_total}',";
		   	      $order_insert.="'{$bag_item_requirements}',";
		   	      $order_insert.="'{$order_date}',";
		   	      $order_insert.="'{$order_year}',";
		   	      $order_insert.="'{$order_month}',";
		   	      $order_insert.="'{$order_number_date}',";
		   	      $order_insert.="'{$order_week_day}',";
		   	      $order_insert.="'pending','{$buyer_referror}',";
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
                       $second_info="<span style='color:green;'>Checkout Complete! Notifying DiggiMall...just a few seconds</span>";

				  }
		          
	       }//end if fetching shoppers query

			    /*clear shoppers details from*/
			    $bag_clearance=$query_guy->delete_by_id("BAG_ITEMS","VSTR_ID", $shoppers_id);
			    setcookie("recommended_sub",$recommended_sub,time()+31556926);
          setcookie("sms_number",$bphone,time()+400);
          setcookie("sms_name",$bname,time()+400);

			    if($bag_clearance){

			    	header("Refresh: 3;url='thankyou'");
			    }
   	      

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


          <div class="row">
            <div class="guest">
              <div class="col-md-8">
                <h3 style="color:black;"><?php echo $first_head; ?></h3>
                <p><?php echo $second_info; ?></p>
              </div>
            </div>
          </div><!--end row-->

          <div class="row guest-checkout">
             <?php echo $checkout_form; ?>
          </div>

          <div class="row">
            <div class="col-md-12"><img src="images/checkout.jpg" style="margin:100px auto;" class="img img-responsive checkoutimg"/></div>
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