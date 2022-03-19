<?php ob_start();

/*setcookie("shopper_name","",time()-10);
setcookie("shopper_id","",time()-10);*/

include("inc/cookie_checker.php");

/*if(!isset($_COOKIE['seller_logged_in'])){header("Location: seller-registration");}
else{
  $id=$_COOKIE['seller_logged_in'];
}*/


include('functions.php'); 
include('conn'.DS.'db_connection.php'); 
include('classes'.DS.'querying_class.php');
include('classes'.DS.'form_class.php');
include('classes'.DS.'filter_class.php');

$query_guy=new DataQuery();
$form_man=new FormDealer();
$filtering=new Filter();

/*select all years*/
$years="";
				 $years_query="SELECT DISTINCT ORDER_YEAR FROM ORDERS ORDER BY ORDER_YEAR DESC";
				 $years_query_process=mysqli_query(DB_Connection::$connection,$years_query);

					   while($fetch_years=mysqli_fetch_assoc($years_query_process)){

                            $year=$fetch_years['ORDER_YEAR'];
					   		$years.='<option value="'.$year.'">'.$year.'</option>';	
					   }

/*Get all main categories*/		
   /*get all main categories*/
   $main_categories=$query_guy->find_all_main_categories("MAIN_CATEGORY");

   $main_list="";
   while($main_results=mysqli_fetch_assoc($main_categories)){
     $main_cat_name=$main_results['MAIN_CATEGORY_NAME'];
     $main_cat_id=$main_results['MAIN_CATEGORY_ID'];

     $main_list.='<option value="'.$main_cat_id.'">'.ucfirst($main_cat_name).'</option>';

}	

  /*get all buyers*/
 	 $buyers_query="SELECT DISTINCT CUSTOMER_NAME FROM ORDERS ORDER BY CUSTOMER_NAME DESC";
	 $buyers_query_process=mysqli_query(DB_Connection::$connection,$buyers_query);

   $buyers="";
   while($bresults=mysqli_fetch_assoc($buyers_query_process)){
    
     $bname=$bresults['CUSTOMER_NAME'];

     $buyers.='<option value="'.$bname.'">'.ucfirst($bname).'</option>';

}	


/*********get all sellers *******************/
   $sellers="";

  /*all sellers*/
   $seller_list=$query_guy->get_sellers();

         while($item=mysqli_fetch_assoc($seller_list)){
                 
                 $seller_name=ucfirst($item['SELLER_NAME']);
                 $slrid=$item['SELLER_ID'];
                  
                 $sellers.='<option value="'.$slrid.'">'.ucfirst($seller_name).'</option>'; 

         }//end else
	   

$small_screen_order_list="";

$title="<h4>These Are Your Orders from DiggiMall To Be Delivered</h4>";
//if orders have not been filtered
if(!isset($_POST['filter_submit'])){


      $packaged_orders_query="SELECT * FROM HUNGRY_ORDERS WHERE DELIVERY_STATUS='pending'  ORDER BY FULL_ORDER_DATE DESC";
      $sellers_items=mysqli_query(DB_Connection::$connection,$packaged_orders_query);


      if(mysqli_num_rows($sellers_items)==0){ $small_screen_order_list='<div class="col-md-5 col-sm-5 small-screen-display">No New Orders To Deliver</div>';}
      else{
      			
                 $pending_orders=mysqli_num_rows($sellers_items);
                 $title="<h4 style='background-color:#D91E18;'>You Have ".$pending_orders." orders!</h4>";


		      	 while($orders=mysqli_fetch_assoc($sellers_items)){
                         
                         $order_id=$orders['HUNGRY_ORDER_ID'];
				      	 $customer_name=$orders['HUNGRY_CUSTOMER_NAME'];
				      	 $customer_hall=$orders['HUNGRY_CUSTOMER_HALL'];
				      	 $customer_phone=$orders['HUNGRY_CUSTOMER_PHONE'];
				      	 $customer_request=$orders['HUNGRY_ORDER_REQUEST'];
				      	 $status=$orders['DELIVERY_STATUS'];
				      	 $order_date=date("D d M- h:ia",strtotime($orders['FULL_ORDER_DATE']));
				    
				      

				      	 //change status button
				      	 $on_status_button='<button onclick="onroute_this_hunger(\''.$order_id.'\');" class="btn btn-danger"><i class="fa fa-check"></i> On route</button>';
				      	 $status_button='<button onclick="deliver_this_hunger(\''.$order_id.'\');" class="btn btn-danger"><i class="fa fa-check"></i> Delivered & Accepted</button>';
				      	 $rstatus_button='<button onclick="return_this_hunger(\''.$order_id.'\');" class="btn btn-danger"><i class="fa fa-check"></i> Returned</button>';
				       
				        //small screen
				         $small_screen_order_list.='<div class="col-md-5 col-sm-5 small-screen-display">
				         								<p><b>Order ID:</b> <span><b>'.$order_id.'</b></span></p>
														<p><b>Customer Name:</b> <span><b>'.$customer_name.'</b></span></p>
														<p><b>Customer Hall:</b> <span><b>'.$customer_hall.'</b></span></p>
														<p><b>Phone Number:</b> <span><b><a href="tel:'.$customer_phone.'">'.$customer_phone.'</a></b></span></p>
														<p><b>Order:</b> <span><b>'.$customer_request.'</b></span></p>
														<p><b>Time:</b> <span><b>'.$order_date.'</b></span></p>
														<p><b>Delivery Status:</b> <span><b class="status'.$order_id.'">'.$status.'</b></span></p>
														<div class="btn-group">
													    '.$on_status_button.'
													    '.$status_button.'
														'.$rstatus_button.'
														</div>
													</div>';


		      }//end while
      }//end else orders available


}//end if not isset
else{

	    $sellers_items=$filtering->run_hungry_accounts_order_search();
	    /*echo mysqli_num_rows($sellers_items);*/
	    //if filter returns null
	if(mysqli_num_rows($sellers_items)==0){ $small_screen_order_list='<div class="col-md-5 col-sm-5 small-screen-display">No Orders</div>';}
	else{

                 $orders_found=mysqli_num_rows($sellers_items);
                 $title="<h4>".$orders_found." Orders Found!</h4>";
            
             	 while($orders=mysqli_fetch_assoc($sellers_items)){

				           $order_id=$orders['HUNGRY_ORDER_ID'];
				      	 $customer_name=$orders['HUNGRY_CUSTOMER_NAME'];
				      	 $customer_hall=$orders['HUNGRY_CUSTOMER_HALL'];
				      	 $customer_phone=$orders['HUNGRY_CUSTOMER_PHONE'];
				      	 $customer_request=$orders['HUNGRY_ORDER_REQUEST'];
				      	 $status=$orders['DELIVERY_STATUS'];
				      	 $order_date=date("D d M- h:ia",strtotime($orders['FULL_ORDER_DATE']));
				    
				      

				      	 //change status button
				      	 $on_status_button='<button onclick="onroute_this_hunger(\''.$order_id.'\');" class="btn btn-danger"><i class="fa fa-check"></i> On route</button>';
				      	 $status_button='<button onclick="deliver_this_hunger(\''.$order_id.'\');" class="btn btn-danger"><i class="fa fa-check"></i> Delivered & Accepted</button>';
				      	 $rstatus_button='<button onclick="return_this_hunger(\''.$order_id.'\');" class="btn btn-danger"><i class="fa fa-check"></i> Returned</button>';
				       
				        //small screen
				         $small_screen_order_list.='<div class="col-md-5 col-sm-5 small-screen-display">
				         								<p><b>Order ID:</b> <span><b>'.$order_id.'</b></span></p>
														<p><b>Customer Name:</b> <span><b>'.$customer_name.'</b></span></p>
														<p><b>Customer Hall:</b> <span><b>'.$customer_hall.'</b></span></p>
														<p><b>Phone Number:</b> <span><b><a href="tel:'.$customer_phone.'">'.$customer_phone.'</a></b></span></p>
														<p><b>Order:</b> <span><b>'.$customer_request.'</b></span></p>
														<p><b>Time:</b> <span><b>'.$order_date.'</b></span></p>
														<p><b>Delivery Status:</b> <span><b class="status'.$order_id.'">'.$status.'</b></span></p>
														<div class="btn-group">
													    '.$on_status_button.'
													    '.$status_button.'
														'.$rstatus_button.'
														</div>
													</div>';


		      }//end while

	}//end nested
}//end else isset


?>

<?php include("inc/header.php"); ?>
<link rel="stylesheet" href="css/dgorders.css"/>
<link rel="stylesheet" href="css/nav.css"/>
<title>Administrator</title>

</head>


<body>

 <!--site navigation-->
<?php include("inc/admin-nav.php"); ?>

<!--body-->
<div class="container-fluid main-container">
<div class="row">
	<div class="col-md-3 list">
	<h4>Deliveries To Make</h4>	

	  <div class="sidebox">
		<form role="form" class="filter-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

				  <!--   <div class="row">
				       <div class="form-group" style="margin-top:5%;">
						  <label class="col-lg-3 control-label">Order ID</label>
						  <div class="col-lg-9">
							<input type="number" class="form-control" name="order_id" min="0"/>
						  </div>
					   </div>
					</div> -->

					<div class="row">
				       <div class="form-group" style="margin-top:5%;">
						  <label class="col-lg-3 control-label">Start Date</label>
						  <div class="col-lg-9">
							<input type="datetime" class="form-control" name="start_date"/>
						  </div>
					   </div>
					</div>

					 <div class="row">
				       <div class="form-group" style="margin-top:5%;">
						  <label class="col-lg-3 control-label">Year</label>
						  <div class="col-lg-9">
							<select name="year" class="form-control">
							<option value="default"></option>
							<?php echo $years; ?>
						   </select>
						  </div>
					   </div>
					</div>

					 <div class="row">
				       <div class="form-group" style="margin-top:5%;">
						  <label class="col-lg-3 control-label">Month</label>
						  <div class="col-lg-9">
							<select name="month" class="form-control">
							<option value="default"></option>
							<option value="January">January</option>
							<option value="February">February</option>
							<option value="March">March</option>
							<option value="April">April</option>
							<option value="May">May</option>
							<option value="June">June</option>
							<option value="July">July</option>
							<option value="August">August</option>
							<option value="September">September</option>
							<option value="October">October</option>
							<option value="November">November</option>
							<option value="December">December</option>
						   </select>
						  </div>
					   </div>
					</div>

				    <div class="row">
				       <div class="form-group" style="margin-top:5%;">
						  <label class="col-lg-3 control-label">Date</label>
						  <div class="col-lg-9">
							<select name="date" class="form-control">
							<option value="default"></option>
							<script type="text/javascript">
							  var i=1;
							  for(i=1;i<32;i++){

							  	  document.write('<option value="'+i+'">'+i+'</option>');
							  }
							</script>
						   </select>
						  </div>
					   </div>
				  </div>

				  <div class="row">
				       <div class="form-group" style="margin-top:5%;">
						  <label class="col-lg-3 control-label">Day Of Week</label>
						  <div class="col-lg-9">
							<select name="day_of_week" class="form-control">
							<option value="default"></option>
							<option value="Monday">Monday</option>
							<option value="Tuesday">Tuesday</option>
							<option value="Wednesday">Wednesday</option>
							<option value="Thursday">Thursday</option>
							<option value="Friday">Friday</option>
							<option value="Saturday">Saturday</option>
							<option value="Sunday">Sunday</option>
						   </select>
						  </div>
					   </div>
				  </div>



			         <div class="row">
				       <div class="form-group" style="margin-top:5%;">
						  <label class="col-lg-3 control-label">Buyer's Name</label>
						  <div class="col-lg-9">
							<select name="b_name" class="form-control">
							<option value="default"></option>
							<?php echo $buyers; ?>
						   </select>
						  </div>
					   </div>
					 </div>

					 
                   <div class="row" style="margin-top:4%;">
				       <div class="form-group">
						 <input type="submit" class="btn btn-success btn-block" name="filter_submit" value="Sort Orders"/>
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
  $(function(){
  	
 
   //getting sub categories
   $("#main_cat").change(function(){
       
          var chosen_one=$(this);
          if($(this[this.selectedIndex]).val()!="default"){

                  //$(".form-legend").append('<i class="fa fa-spinner fa-spin" style="color:red;"></i>');

                  var category_id=$(this[this.selectedIndex]).val();

                  $.post("inc/category_parser.php",{category_id:category_id},function(data){
                      
                    $("#sub_cat").html('<option value="default">Sub Categories List</option>'+data);
                      
                  });


          }//end first if

      });


   //getting sub set
   $("#sub_cat").change(function(){
       
          var chosen_one=$(this);
          if($(this[this.selectedIndex]).val()!="default"){

                  //$(".form-legend").append('<i class="fa fa-spinner fa-spin" style="color:red;"></i>');

                  var sub_cat_id=$(this[this.selectedIndex]).val();

                  $.post("inc/category_parser.php",{sub_category_id:sub_cat_id},function(data){
                      
                    $("#narrowed_cat").html('<option value="default">Narrowed Categories</option>'+data);
                      
                  });


          }//end first if

      });
  });
</script>
<script type="text/javascript">
var package_this;
var return_this;
$(function(){
    
    deliver_this_hunger=function(a){
    	//a - order id

    	var order_id=a;

    	  $.post("inc/category_parser.php",{delivery_order_id_hunger:order_id},function(data){
                      
                    $(".status"+order_id).html('Delivered');

                      
                });

    }


     return_this_hunger=function(a){
    	//a - order id

    	var order_id=a;

    	  $.post("inc/category_parser.php",{reorder_id_hunger:order_id},function(data){
                      
                    $(".status"+order_id).html('Returned');

                      
                });


    }



    onroute_this_hunger=function(a){
    	//a - order id

    	var order_id=a;

    	  $.post("inc/category_parser.php",{onorder_id_hunger:order_id},function(data){
                      
                    $(".status"+order_id).html('Order On route');

                      
                });


    }

});
</script>
</body>
</html>