<?php ob_start();

/*setcookie("shopper_name","",time()-10);
setcookie("shopper_id","",time()-10);*/

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
include('classes'.DS.'pagination.php');
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
 	 $buyers_query="SELECT DISTINCT CUSTOMER_NAME FROM ORDERS WHERE SLR_ID=".$id." ORDER BY CUSTOMER_NAME DESC";
	 $buyers_query_process=mysqli_query(DB_Connection::$connection,$buyers_query);

   $buyers="";
   while($bresults=mysqli_fetch_assoc($buyers_query_process)){
    
     $bname=$bresults['CUSTOMER_NAME'];

     $buyers.='<option value="'.$bname.'">'.ucfirst($bname).'</option>';

}	


/*********get all sellers products*******************/
   $item_list="";

  /*all sellers itemss*/
   $seller_items=$query_guy->find_products_by_seller($id);

   $number_of_items=mysqli_num_rows($seller_items);

   if($number_of_items==0){$item_list='<h4>You Have No Items In Your Inventory</h4>';}
   else{

         while($item=mysqli_fetch_assoc($seller_items)){
                 
                 $item_name=ucfirst($item['PRODUCT_NAME']);
                 $itemid=$item['PRODUCT_ID'];
                  
                 $item_list.='<option value="'.$itemid.'">'.ucfirst($item_name).'</option>'; 

         }//end else

   }//end if items are not empty		   

$order_list="";
$small_screen_order_list="";

$title="<h4>These Are Your Orders from DiggiMall</h4>";
//if orders have not been filtered
if(!isset($_POST['filter_submit'])){


      $sellers_items=$query_guy->find_orders_by_seller($id);

      if(mysqli_num_rows($sellers_items)==0){$order_list="You have no new orders yet";$income=0;$total_ordered=0;}
      else{
      			 $income=0; 
                 $total_ordered=0;	
                 $pending_orders=mysqli_num_rows($sellers_items);
                 $title="<h4 style='background-color:#D91E18;'>You Have ".$pending_orders." Orders Awaiting Processing!</h4>";


		      	 while($orders=mysqli_fetch_assoc($sellers_items)){

				         
				         $order_id=$orders['ORDER_ID'];
				      	 $customer_name=$orders['CUSTOMER_NAME'];
				      	 $customer_phone=$orders['CUSTOMER_PHONE'];
				      	 $customer_hall=$orders['CUSTOMER_HALL'];
				      	 $requirements=$orders['SPECIAL_REQUEST'];
				      	 $status=$orders['DELIVERY_STATUS'];
				      	 $quantity=$orders['ORDERED_QUANTITY'];
				      	 $total_ordered+=$orders['ORDERED_QUANTITY'];
				      	 $cost=$orders['TOTAL_COST_OF_ORDER'];
				      	 $income+=$orders['TOTAL_COST_OF_ORDER'];
				      	 $order_date=date("D d M, Y",strtotime($orders['ORDER_DATE_FULL']));
				      	 $pdt_id=$orders['PT_ID'];


				      	 //get product name
				      	 $product_finder=$query_guy->find_by_id("PRODUCTS","PRODUCT_ID",$pdt_id);
				      	 $product_name=ucfirst($product_finder['PRODUCT_NAME']);

				      	 //change status button
				      	 $status_button='<button onclick="package_this(\''.$order_id.'\');" class="btn btn-danger"><i class="fa fa-shopping-bag"></i> Mark As Packaged</button>';

				         $order_list.='<tr>';
				         $order_list.=' <td>'.$order_id.'</td>';
				         $order_list.=' <td>'.$customer_name.'</td>';
				         $order_list.=' <td>'.$customer_phone.'</td>';
				         $order_list.=' <td>'.$customer_hall.'</td>';
				         $order_list.=' <td>'.$product_name.'</td>';
				         $order_list.=' <td>'.$quantity.'</td>';
				         $order_list.=' <td>'.number_format($cost,2).'</td>';
				         $order_list.=' <td>'.$requirements.'</td>';
				         $order_list.=' <td>'.$order_date.'</td>';
				         $order_list.=' <td class="status'.$order_id.'">'.$status.'</td>';
				         $order_list.=' <td>'.$status_button.'</td>';
				         $order_list.='</tr>'; 

				         //small screen
				         $small_screen_order_list.='<div class="col-md-5 col-sm-5 small-screen-display">
				         								<p><b>Order ID:</b> <span><b>'.$order_id.'</b></span></p>	
														<p><b>Customer Name:</b> <span><b>'.$customer_name.'</b></span></p>
														<p><b>Customer Phone:</b> <span><b>'.$customer_phone.'</b></span></p>
														<p><b>Hall:</b> <span><b>'.$customer_hall.'</b></span></p>
														<p><b>Item Name:</b><span><b>'.$product_name.'</b></span></p>
														<p><b>Ordered Quantity:</b><span><b>'.$quantity.'</b></span></p>
														<p><b>To Be Paid (GH&#162;):</b> <span><b>'.number_format($cost,2).'</b></span></p>
														<p><b>Requirements:</b> <span><b>'.$requirements.'</b></span></p>
														<p><b>Order Date:</b> <span><b>'.$order_date.'</b></span></p>
														<p><b>Delivery Status:</b> <span><b class="status'.$order_id.'">'.$status.'</b></span></p>
														<p>'.$status_button.'</p>
													</div>';


		      }//end while
      }//end else orders available


}//end if not isset
else{

	    $sellers_items=$filtering->run_accounts_order_search($id);
	    /*echo mysqli_num_rows($sellers_items);*/
	    //if filter returns null
	if(mysqli_num_rows($sellers_items)==0){ $order_list='<tr><td><b>No orders found for your filter</b></td></tr>';$income=0;$total_ordered=0; }
	else{

                 $income=0; 
                 $total_ordered=0;
                 $orders_found=mysqli_num_rows($sellers_items);
                 $title="<h4>".$orders_found." Orders Found!</h4>";


		      	 while($orders=mysqli_fetch_assoc($sellers_items)){

				         $order_id=$orders['ORDER_ID'];
				      	 $customer_name=$orders['CUSTOMER_NAME'];
				      	 $customer_phone=$orders['CUSTOMER_PHONE'];
				      	 $customer_hall=$orders['CUSTOMER_HALL'];
				      	 $requirements=$orders['SPECIAL_REQUEST'];
				      	 $status=$orders['DELIVERY_STATUS'];
				      	 $quantity=$orders['ORDERED_QUANTITY'];
				      	 $total_ordered+=$orders['ORDERED_QUANTITY'];
				      	 $cost=$orders['TOTAL_COST_OF_ORDER'];
				      	 $income+=$orders['TOTAL_COST_OF_ORDER'];
				      	 $order_date=date("D d M, Y",strtotime($orders['ORDER_DATE_FULL']));
				      	 $pdt_id=$orders['PT_ID'];
                 
				      	 //get product name
				      	 $product_finder=$query_guy->find_by_id("PRODUCTS","PRODUCT_ID",$pdt_id);
				      	 $product_name=ucfirst($product_finder['PRODUCT_NAME']);

				      	 //change status button
				      	 $status_button='<button onclick="package_this(\''.$order_id.'\');" class="btn btn-danger"><i class="fa fa-shopping-bag"></i> Mark As Packaged</button>';

				         $order_list.='<tr>';
				         $order_list.=' <td>'.$order_id.'</td>';
				         $order_list.=' <td>'.$customer_name.'</td>';
				         $order_list.=' <td>'.$customer_phone.'</td>';
				         $order_list.=' <td>'.$customer_hall.'</td>';
				         $order_list.=' <td>'.$product_name.'</td>';
				         $order_list.=' <td>'.$quantity.'</td>';
				         $order_list.=' <td>'.number_format($cost,2).'</td>';
				         $order_list.=' <td>'.$requirements.'</td>';
				         $order_list.=' <td>'.$order_date.'</td>';
				         $order_list.=' <td class="status'.$order_id.'">'.$status.'</td>';
				         $order_list.=' <td>'.$status_button.'</td>';
				         $order_list.='</tr>'; 


				          //small screen
				         $small_screen_order_list.='<div class="col-md-5 col-sm-5 small-screen-display">
				         								<p><b>Order ID:</b> <span><b>'.$order_id.'</b></span></p>
														<p><b>Customer Name:</b> <span><b>'.$customer_name.'</b></span></p>
														<p><b>Customer Phone:</b> <span><b>'.$customer_phone.'</b></span></p>
														<p><b>Hall:</b> <span><b>'.$customer_hall.'</b></span></p>
														<p><b>Item Name:</b><span><b>'.$product_name.'</b></span></p>
														<p><b>Ordered Quantity:</b><span><b>'.$quantity.'</b></span></p>
														<p><b>To Be Paid (GH&#162;):</b> <span><b>'.number_format($cost,2).'</b></span></p>
														<p><b>Requirements:</b> <span><b>'.$requirements.'</b></span></p>
														<p><b>Order Date:</b> <span><b>'.$order_date.'</b></span></p>
														<p><b>Delivery Status:</b> <span><b class="status'.$order_id.'">'.$status.'</b></span></p>
														<p>'.$status_button.'</p>
													</div>';


		      }//end while

	}//end nested
}//end else isset

/*calculate diggimalls commission amount*/
$diggimall_commission=0.04*$income;

/*calculate net income*/
$net_income=$income-$diggimall_commission;

?>

<?php include("inc/header.php"); ?>
<link rel="stylesheet" href="css/my-orders.css"/>
<link rel="stylesheet" href="css/nav.css"/>
<title>My Orders</title>

</head>


<body>

 <!--site navigation-->
<?php include("inc/seller-nav.php"); ?>

<!--body-->
<div class="container-fluid main-container">
<div class="row">
	<div class="col-md-3 list">
	<h4>Order Accounts For Filter</h4>	
	<h4>Total Ordered Quantity: <b> <?php echo $total_ordered; ?></b></h4>	
	<h4>Total Sales: <b>GH&#162; <?php echo number_format($income,2); ?></b></h4>	
	<h4>Commission: <b>GH&#162; <?php echo number_format($diggimall_commission,2); ?></b></h4>
	<h4>My Net Income: <b>GH&#162; <?php echo number_format($net_income,2); ?></b></h4>

	  <div class="sidebox">
		<form role="form" class="filter-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

				 <div class="row">
				       <div class="form-group" style="margin-top:5%;">
						  <label class="col-lg-3 control-label">Product Category</label>
						  <div class="col-lg-9">
							<select name="main_cat" id="main_cat"  class="form-control">
							<option value="default"></option>
							<?php echo $main_list; ?>
						   </select>
						  </div>
					   </div>
					</div>


					<div class="row">
				       <div class="form-group" style="margin-top:5%;">
						  <label class="col-lg-3 control-label">Sub Category<span class="loader"></span></label>
						  <div class="col-lg-9">
							<select name="sub_cat" id="sub_cat" class="form-control">
							<option value="default"></option>
						   </select>
						  </div>
					   </div>
					</div>


					<div class="row">
				       <div class="form-group" style="margin-top:5%;">
						  <label class="col-lg-3 control-label">Narrowed Category<span class="loader"></span></label>
						  <div class="col-lg-9">
							<select name="narrowed_cat" class="form-control" id="narrowed_cat">
							<option value="default"></option>
						   </select>
						  </div>
					   </div>
					</div>

					<div class="row">
				       <div class="form-group" style="margin-top:5%;">
						  <label class="col-lg-3 control-label">Product Name<span class="loader"></span></label>
						  <div class="col-lg-9">
							<select name="p_name" class="form-control">
							<option value="default"></option>
							<?php echo $item_list; ?>
						   </select>
						  </div>
					   </div>
					</div>



				<div class="row" style="margin-top:4%;">
				       <div class="form-group">
						 <input type="submit" class="btn btn-success btn-block" name="filter_submit" value="Sort Orders"/>
					   </div>
				  </div>


				  <div class="row filter-details">
							<div class="form-group">
							<h5 class="form-legend">Sort Orders By Date Details</h3>
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
							<option value="Febrauary">Febrauary</option>
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
						 <input type="submit" class="btn btn-success btn-block" name="filter_submit" value="Sort Orders"/>
					   </div>
				  </div>


							<div class="row filter-details">
							<div class="form-group">
							<h5 class="form-legend">Sort By Buyer and Delivery Details</h3>
						    </div>
						    </div>


			         <div class="row">
				       <div class="form-group">
						  <label class="col-lg-3 control-label">Buyer's Name</label>
						  <div class="col-lg-9">
							<select name="b_name" class="form-control">
							<option value="default"></option>
							<?php echo $buyers; ?>
						   </select>
						  </div>
					   </div>
					 </div>

					 <div class="row">
				       <div class="form-group" style="margin-top:5%;">
						  <label class="col-lg-3 control-label">Hostel</label>
						  <div class="col-lg-9">
						 <select class="form-control" name="b_hall">
						 	<optgroup label="Halls">
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
            				  <option value="Valco">Valco Hostel</option>
            				  <option value="Commonwealth">Commonwealth</option>
            				 </optgroup>
            			  <optgroup label="Lecture Halls">
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
					   </div>
				  </div>


					<div class="row">
				       <div class="form-group" style="margin-top:5%;">
						  <label class="col-lg-3 control-label">Delivery Status</label>
						  <div class="col-lg-9">
							<select name="delivery_status" class="form-control">
							<option value="default"></option>
							<option value="pending">Pending</option>
							<option value="confirmed">Confirmed</option>
							<option value="packaged">Packaged</option>
							<option value="crosschecked">Crosschecked</option>
							<option value="on route">On route</option>
							<option value="delivered">Delivered</option>
							<option value="returned">Returned</option>
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

		<table class="table table-striped table-hover hidden-xs hidden-sm hidden-md visible-lg-block">
			<thead>
			<tr style="color:#049372;">
				<th>ID</th>
				<th>Customer Name</th>
				<th>Customer Phone</th>
				<th>Hall</th>
				<th>Item Name</th>
				<th>Ordered Quantity</th>
				<th>To Be Paid (GH&#162;)</th>
				<th>Requirements</th>
				<th>Order Date</th>
				<th>Delivery Status</th>
				<th>Change Status</th>
			</tr>
			</thead>

			<tbody>

			 <?php echo $order_list; ?>
			</tbody>
		</table>

		<!---only on small screens-->
		<div class="row hidden-lg visible-sm-block visible-md-block  visible-xs-block small-row">
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
$(function(){
    
    package_this=function(a){
    	//a - order id

    	var order_id=a;

    	  $.post("inc/category_parser.php",{order_id:order_id},function(data){
                      
                    $(".status"+order_id).html('Packaged');

                      
                });


    }
});
</script>
</body>
</html>