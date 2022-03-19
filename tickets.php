<?php ob_start();

function decryptCookie($value){
   
   $key='a,s,d,#,4,32,][*&^..#&&*';
   $crypttext=base64_decode($value);
   $iv_size=mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
   $iv=mcrypt_create_iv($iv_size,MCRYPT_RAND);
   $decrypttext=mcrypt_decrypt(MCRYPT_RIJNDAEL_256,$key,$crypttext,MCRYPT_MODE_ECB,$iv);

   return $decrypttext;

}//end decryption

if(!isset($_COOKIE['seller_logged_in'])){header("Location: seller-registration");}
else{
  $id=(int)decryptCookie($_COOKIE['seller_logged_in']);
}



include('functions.php'); 
include('conn'.DS.'db_connection.php'); 
include('classes'.DS.'querying_class.php');
include('classes'.DS.'form_class.php');
include('classes'.DS.'filter_class.php');

$query_guy=new DataQuery();
$form_man=new FormDealer();
$filtering=new Filter();

/*get specific tickets*/

$small_screen_order_list="";

$title="<h4>List of Tickets Purchased</h4>";
//if orders have not been filtered
if(!isset($_POST['find_ticket'])){


      $confirmed_tickets_query="SELECT * FROM ORDERS WHERE MC_ID=12 AND SLR_ID=".$id." AND DELIVERY_STATUS='confirmed' ORDER BY ORDER_ID ASC";
      $confirmed_tickets=mysqli_query(DB_Connection::$connection,$confirmed_tickets_query);


      if(mysqli_num_rows($confirmed_tickets)==0){ $small_screen_order_list='<div class="col-md-5 col-sm-5 small-screen-display">No Tickets Purchased Yet</div>';}
      else{
      			
                 $number_of_tickets=mysqli_num_rows($confirmed_tickets);
                 $title="<h4 style='background-color:#D91E18;'>You Have ".$number_of_tickets." Purchased Tickets Awaiting!</h4>";


		      	 while($orders=mysqli_fetch_assoc($confirmed_tickets)){

				         
				         $order_id=$orders['ORDER_ID'];
				      	 $customer_name=$orders['CUSTOMER_NAME'];
				  /*     $customer_hall=$orders['CUSTOMER_HALL'];*/
				      	 $customer_phone=$orders['CUSTOMER_PHONE'];
				      	 $status=$orders['DELIVERY_STATUS'];
				      	 $request=($orders['SPECIAL_REQUEST']=="no requirements"||$orders['SPECIAL_REQUEST']==""||$orders['SPECIAL_REQUEST']=="none")?"No Code":$orders['SPECIAL_REQUEST'];
				      	 $pdt_id=$orders['PT_ID'];
				      	 $random_token=$orders['RANDOM_TOKEN'];

				    
				      	  //get product name
				      	 $product_finder=$query_guy->find_by_id("PRODUCTS","PRODUCT_ID",$pdt_id);
				      	 $product_name=ucfirst($product_finder['PRODUCT_NAME']);

				       	  //change ticket border_color depending on status
				      	 if($status=="checked"){
                              $style='style="border-left:6px solid green;"';
                              $status_button="";
				      	 }else{
				      	 	 $style='style="border-left:6px solid red;"';
				      	 	 //change status button
				      	 $status_button='<button onclick="check_this(\''.$order_id.'\');" class="btn btn-primary" style="padding:7px;width:200px;border-radius:0;">MARK TICKET <i class="fa fa-check"></i></button>';
				      	 }

				      	 //get the details of seller
				         /*$product_seller=$product_finder['SEL_ID'];
				      	 $seller_finder=$query_guy->find_by_id("SELLERS","SELLER_ID",$product_seller);
				      	 $seller_name=ucfirst($seller_finder['SELLER_NAME']);*/

						
				   

				        //small screen
				         $small_screen_order_list.='<div class="col-md-5 borderstatus'.$order_id.' col-sm-5 small-screen-display" '.$style.'>
				         								<p><b>Ticket Number#:</b> <span><b>'.$order_id.'</b></span></p>	
														<p><b>Name Of Attendee:</b> <span><b>'.$customer_name.'</b></span></p>
														<p><b>Phone Number:</b> <span><b><a href="tel:'.$customer_phone.'">'.$customer_phone.'</a></b></span></p>
														<p><b>Verification Code:</b> <span><b>'.$random_token.'</b></span></p>
														<p><b>Coupon Code:</b> <span><b>'.$request.'</b></span></p>
														<p><b>Program Ticket:</b> <span><b>'.$product_name.'</b></span></p>
														<p><b>Ticket Status:</b> <span><b class="status'.$order_id.'">'.$status.'</b></span></p>
														<div class="btn-group">
														'.$status_button.'
													    </div>
													</div>';

		      }//end while
      }//end else orders available


}//end if not isset
else{

      $ticket_number=$form_man->cleanString($_POST['ticket_id']);
	  $confirmed_tickets_query="SELECT * FROM ORDERS WHERE (ORDER_ID='{$ticket_number}' OR RANDOM_TOKEN ='{$ticket_number}') AND MC_ID=12";
      $confirmed_tickets=mysqli_query(DB_Connection::$connection,$confirmed_tickets_query);

      //echo mysqli_error(DB_Connection::$connection);
	    //if filter returns null
	if(mysqli_num_rows($confirmed_tickets)==0){ $small_screen_order_list='<div class="col-md-5 col-sm-5 small-screen-display">No Tickets Found!</div>';}
	else{

                 $orders_found=mysqli_num_rows($confirmed_tickets);
                 $title="<h4 style='background-color:#eee;color:black;'>".$orders_found." Ticket Found!</h4>";
            
             	 while($orders=mysqli_fetch_assoc($confirmed_tickets)){

				         $order_id=$orders['ORDER_ID'];
				      	 $customer_name=$orders['CUSTOMER_NAME'];
				  /*     $customer_hall=$orders['CUSTOMER_HALL'];*/
				      	 $customer_phone=$orders['CUSTOMER_PHONE'];
				      	 $request=($orders['SPECIAL_REQUEST']=="no requirements"||$orders['SPECIAL_REQUEST']==""||$orders['SPECIAL_REQUEST']=="none")?"No Code":$orders['SPECIAL_REQUEST'];
				      	 $status=$orders['DELIVERY_STATUS'];
				      	 $pdt_id=$orders['PT_ID'];
				      	 $random_token=$orders['RANDOM_TOKEN'];

				      	  //get product name
				      	 $product_finder=$query_guy->find_by_id("PRODUCTS","PRODUCT_ID",$pdt_id);
				      	 $product_name=ucfirst($product_finder['PRODUCT_NAME']);

				      	  //change ticket border_color depending on status
				      	 if($status=="checked"){
                              $style='style="border-left:6px solid green;"';
                              $status_button="";
				      	 }else{
				      	 	 $style='style="border-left:6px solid red;"';
				      	 	 //change status button
				      	 $status_button='<button onclick="check_this(\''.$order_id.'\');" class="btn btn-primary" style="padding:7px;width:200px;border-radius:0;">MARK TICKET <i class="fa fa-check"></i></button>';
				      	 }

				      	 //get the details of seller
				         /*$product_seller=$product_finder['SEL_ID'];
				      	 $seller_finder=$query_guy->find_by_id("SELLERS","SELLER_ID",$product_seller);
				      	 $seller_name=ucfirst($seller_finder['SELLER_NAME']);*/

						
				   

				        //small screen
				         $small_screen_order_list.='<div class="col-md-5 borderstatus'.$order_id.' col-sm-5 small-screen-display" '.$style.'>
				         								<p><b>Ticket Number:</b> <span><b>'.$order_id.'</b></span></p>	
														<p><b>Name Of Attendee:</b> <span><b>'.$customer_name.'</b></span></p>
														<p><b>Phone Number:</b> <span><b><a href="tel:'.$customer_phone.'">'.$customer_phone.'</a></b></span></p>
														<p><b>Verification Code:</b> <span><b>'.$random_token.'</b></span></p>
														<p><b>Coupon Code:</b> <span><b>'.$request.'</b></span></p>
														<p><b>Program Ticket:</b> <span><b>'.$product_name.'</b></span></p>
														<p><b>Ticket Status:</b> <span><b class="status'.$order_id.'">'.$status.'</b></span></p>
														<div class="btn-group">
														'.$status_button.'
													    </div>
													</div>';


		      }//end while

	}//end nested
}//end else isset


?>

<?php include("inc/header.php"); ?>
<link rel="stylesheet" href="css/motoman.css"/>
<link rel="stylesheet" href="css/nav.css"/>
<title>Tickets Tracking</title>

</head>


<body>

 <!--site navigation-->
<?php include("inc/seller-nav.php"); ?>

<!--body-->
<div class="container-fluid main-container">
<div class="row">
	<div class="col-md-3 list">
	<h4>Find A Ticket Here</h4>	

	  <div class="sidebox">
		<form role="form" class="filter-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                 
                   	<div class="row">
				       <div class="form-group" style="margin-top:5%;">
						  <label class="col-lg-3 control-label">Ticket ID</label>
						  <div class="col-lg-9">
							<input type="text" placeholder="Enter Ticket ID / Verification Code" name="ticket_id" class="form-control">
						  </div>
					   </div>
					</div>
                   
                   <div class="row" style="margin-top:4%;">
				       <div class="form-group">
						 <input style="padding:8px;" type="submit" class="btn btn-danger btn-block" name="find_ticket" value="FIND TICKET"/>
					   </div>
				  </div>

							
		</form>
	  </div>
	</div>

	<!--products display-->
	<div class="col-md-9 details">
		<?php echo $title ?>	

		<!---------------only on small screens-->
		<div class="row small-row"><!-- hidden-lg visible-sm-block visible-md-block  visible-xs-block  -->
			<?php echo $small_screen_order_list; ?>
		</div>
	

	</div>
</div>
</div>


 	


<?php include("inc/footer.php"); ?>
<script type="text/javascript">
   $(function(){
   	
   	   $(".clickable-row").click(function(){
   	   	 window.document.location=$(this).data("href");
   	   });
   });
</script>

<script type="text/javascript">
var package_this;
var return_this;
$(function(){
    
    check_this=function(a){
    	//a - order id

    	var order_id=a;

    	  $.post("inc/category_parser.php",{check_order_id:order_id},function(data){
                      
                    $(".status"+order_id).html('Ticket Checked!');
                    $(".borderstatus"+order_id).attr("style","border-left:4px solid green;");

                      
                });

    }
});
</script>
</body>
</html>