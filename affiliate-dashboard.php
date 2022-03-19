<?php ob_start();


if(!isset($_COOKIE['affiliate_logged_in'])){header("Location: associate-authentication");}
else{
  $id=$_COOKIE['affiliate_logged_in'];
}

//include database connection
include('functions.php'); 
include('conn'.DS.'db_connection.php'); 
include('classes'.DS.'querying_class.php');
include('classes'.DS.'form_class.php');
include('classes'.DS.'filter_class.php');

$query_guy=new DataQuery();
$form_man=new FormDealer();
$filtering=new Filter();

$log_error="";



/*Get all records about affiliate*/
$record=$query_guy->find_by_id("AFFILIATES","AFFILIATE_ID",$id);

$affiliate_diggi_id=$record['AFFILIATE_DIGGI_ID'];
$affiliate_username=$record['AFFILIATE_USERNAME'];
$affiliate_name=ucfirst($record['AFFILIATE_NAME']);
$affiliate_phone=$record['AFFILIATE_PHONE'];
$affiliate_whatsapp=$record['AFFILIATE_WHATSAPP'];
$affiliate_email=$record['AFFILIATE_EMAIL'];
$affiliate_hall=$record['AFFILIATE_HALL'];
$affiliate_profile_pic=$record['AFFILIATE_PROFILE_PHOTO'];
$affiliate_mm_vendor=$record['AFFIL_MOBILE_MONEY_VENDOR'];
$affiliate_mm_account=$record['AFFIL_MOBILE_MONEY_ACCOUNT'];



 /*all profile update*/
 include('inc/affiliate-updates.php'); 

 //profile photo
if(isset($_POST['photo_submit'])){
  if(!$form_man->emptyField($_FILES['photo']['name'])){ 

    //check whether file is legal
    //open user photos folder and delete the old photo
    //update database table with new photo
    //move new photo to user folder

    if(!$form_man->illegalExt($_FILES['photo']['name'])){

        $new_photo=$form_man->cleanString($_FILES['photo']['name']);
        $new_details=$_FILES['photo']['tmp_name'];
         /*reading user image*/
                          $locate="affil_photos".DS;

                          //immediately update view with new pic
                          $query_guy->update_affiliates("AFFILIATE_PROFILE_PHOTO",$new_photo,$id);
                          if(move_uploaded_file($new_details, $locate.$new_photo)){

                             /*$success= $query_guy?$query_guy->success_message("Poto Face"):"Update Failed ";*/
                              header("Refresh: 0.5;url='affiliate-dashboard'");
                           }//end if move_uploaded_file

        
        
    }
  }
}

/*getting all the main categories*/
/*get all main categories*/
   $main_categories=$query_guy->find_all_main_categories("MAIN_CATEGORY");

   $main_list="";
   while($main_results=mysqli_fetch_assoc($main_categories)){
     $main_cat_name=$main_results['MAIN_CATEGORY_NAME'];
     $main_cat_id=$main_results['MAIN_CATEGORY_ID'];

     $main_list.='<option value="'.$main_cat_id.'">'.ucfirst($main_cat_name).'</option>';

} 


/*select all years*/
$years="";
         $years_query="SELECT DISTINCT ORDER_YEAR FROM ORDERS ORDER BY ORDER_YEAR DESC";
         $years_query_process=mysqli_query(DB_Connection::$connection,$years_query);

             while($fetch_years=mysqli_fetch_assoc($years_query_process)){

                            $year=$fetch_years['ORDER_YEAR'];
                $years.='<option value="'.$year.'">'.$year.'</option>'; 
             }


/*get all buyers*/
$buyers_query="SELECT DISTINCT CUSTOMER_NAME FROM ORDERS WHERE BUYER_REFEROR_ID='$affiliate_diggi_id' ORDER BY CUSTOMER_NAME ASC";
$buyers_query_process=mysqli_query(DB_Connection::$connection,$buyers_query);

   $buyers="";
   while($bresults=mysqli_fetch_assoc($buyers_query_process)){
    
     $bname=$bresults['CUSTOMER_NAME'];

     $buyers.='<option value="'.$bname.'">'.ucfirst($bname).'</option>';


}


/*get all buyers (orders or no orders)*/
$buyers2_query="SELECT * FROM BUYERS WHERE REFEROR_ID='$affiliate_diggi_id' ORDER BY BUYER_NAME ASC";
$buyers2_query_process=mysqli_query(DB_Connection::$connection,$buyers2_query);

   $customer_list="";
   while($bresults=mysqli_fetch_assoc($buyers2_query_process)){
    
     $bname=$bresults['BUYER_NAME'];
     $bphone=$bresults['BUYER_PHONE'];
     $bhall=$bresults['BUYER_HALL'];

     $customer_list.='<tr><td>'.$bname.'</td><td>'.$bphone.'</td><td>'.$bhall.'</td></tr>';


}


//get total number of orders for affiliate for the past week
$get_past="SELECT COUNT(*) FROM ORDERS WHERE  ORDER_DATE_FULL >= DATE(NOW()) - INTERVAL 7 DAY AND BUYER_REFEROR_ID='$affiliate_diggi_id'";
$get_last_query=mysqli_query(DB_Connection::$connection,$get_past);

if(!$get_last_query){echo mysqli_error(DB_Connection::$connection);}
$extract=mysqli_fetch_array($get_last_query);
$past7days=array_shift($extract);

//get number of customers for affiliate
$get_buyers="SELECT COUNT(*) FROM BUYERS WHERE REFEROR_ID='$affiliate_diggi_id'";
$get_buyers_query=mysqli_query(DB_Connection::$connection,$get_buyers);
$extract_buyers=mysqli_fetch_array($get_buyers_query);
$referors=array_shift($extract_buyers);

/*RANK level of associate*/

/*
--bronze: min 2 customers (5 orders)
--gold : min 10 customers (20 orders)
--platinum: min 25 customers (40 orders)
--diamond: min 40 customers (60 orders)
*/

$level="";
$level_color="";

if($referors>=2 && $referors <=9 ){//simple associate(bronze)
      $level="Bronze";
      $level_color="#aa5314";
}
else if($referors>=10 && $referors <=24 ){//gold associate
      $level="Gold";
      $level_color="#E87E04";
}
else if($referors>=25 && $referors <=39 ){//platinum associate
      $level="Platinum";
      $level_color="#049372";
}

else if($referors>=40 && $referors <=59 ){//diamond associate
      $level="Diamond";
      $level_color="#95A5A6";
}


else if($referors>=60){//crown dimaond associate
      $level="Crown Diamond";
      $level_color="#CF000F";
}

else{
      $level="Member";
      $level_color="#913D88";
}



/*----------------------------------AFFILIATE ORDERS FILTERING--------------------------------------------*/

$order_list="";
$small_screen_order_list="";

$title="<h4>These Are Your Orders From Your Customers</h4>";


//if orders have not been filtered
if(!isset($_POST['filter_submit'])){

   $affiliate_orders=$query_guy->find_orders_by_affiliate($affiliate_diggi_id);
      /*echo mysqli_num_rows($sellers_items);*/
      //if filter returns null
  if(mysqli_num_rows($affiliate_orders)==0){ $order_list='<tr><td><b>No orders found</b></td></tr>';$income=0;}
  else{

                 $income=0; 
                 $total_ordered=0;
                 $orders_found=mysqli_num_rows($affiliate_orders);
                 $title="<h4>".$orders_found." Orders Found!</h4>";


             while($orders=mysqli_fetch_assoc($affiliate_orders)){

                 $order_id=$orders['ORDER_ID'];
                 $customer_name=$orders['CUSTOMER_NAME'];
                 $customer_phone=$orders['CUSTOMER_PHONE'];
                 $customer_hall=$orders['CUSTOMER_HALL'];
                 $cost=$orders['TOTAL_COST_OF_ORDER'];
                 $income+=$orders['TOTAL_COST_OF_ORDER'];
                 $order_date=date("D d M, Y",strtotime($orders['ORDER_DATE_FULL']));
                 $pdt_id=$orders['PT_ID'];
                 
                 //get product name
                 $product_finder=$query_guy->find_by_id("PRODUCTS","PRODUCT_ID",$pdt_id);
                 $product_name=ucfirst($product_finder['PRODUCT_NAME']);

                 $order_list.='<tr>';
                 $order_list.=' <td>'.$customer_name.'</td>';
                 $order_list.=' <td>'.$customer_phone.'</td>';
                 $order_list.=' <td>'.$customer_hall.'</td>';
                 $order_list.=' <td>'.$product_name.'</td>';
                 $order_list.=' <td>'.$cost.'</td>';
                 $order_list.=' <td>'.$order_date.'</td>';
                 $order_list.='</tr>'; 


                  //small screen
                 $small_screen_order_list.='<div class="col-md-5 col-sm-5 small-screen-display">
                            <p><b>Customer Name:</b> <span><b>'.$customer_name.'</b></span></p>
                            <p><b>Customer Phone:</b> <span><b>'.$customer_phone.'</b></span></p>
                            <p><b>Hall:</b> <span><b>'.$customer_hall.'</b></span></p>
                            <p><b>Item Name:</b><span><b>'.$product_name.'</b></span></p>
                            <p><b>Amount: GH&#162;</b> <span><b>'.$cost.'</b></span></p>
                            <p><b>Order Date:</b> <span><b>'.$order_date.'</b></span></p>
                          </div>';


          }//end while

  }//end nested

}//end if not isset
else{


      $affiliate_orders=$filtering->run_affiliate_accounts_order_search($affiliate_diggi_id);
      /*echo mysqli_num_rows($sellers_items);*/
      //if filter returns null
  if(mysqli_num_rows($affiliate_orders)==0){ $order_list='<tr><td><b>No orders found for your filter</b></td></tr>';$income=0;}
  else{

                 $income=0; 
                 $total_ordered=0;
                 $orders_found=mysqli_num_rows($affiliate_orders);
                 $title="<h4>".$orders_found." Orders Found!</h4>";


             while($orders=mysqli_fetch_assoc($affiliate_orders)){

                 $order_id=$orders['ORDER_ID'];
                 $customer_name=$orders['CUSTOMER_NAME'];
                 $customer_phone=$orders['CUSTOMER_PHONE'];
                 $customer_hall=$orders['CUSTOMER_HALL'];
                 $cost=$orders['TOTAL_COST_OF_ORDER'];
                 $income+=$orders['TOTAL_COST_OF_ORDER'];
                 $order_date=date("D d M, Y",strtotime($orders['ORDER_DATE_FULL']));
                 $pdt_id=$orders['PT_ID'];
                 
                 //get product name
                 $product_finder=$query_guy->find_by_id("PRODUCTS","PRODUCT_ID",$pdt_id);
                 $product_name=ucfirst($product_finder['PRODUCT_NAME']);

                 $order_list.='<tr>';
                 $order_list.=' <td>'.$customer_name.'</td>';
                 $order_list.=' <td>'.$customer_phone.'</td>';
                 $order_list.=' <td>'.$customer_hall.'</td>';
                 $order_list.=' <td>'.$product_name.'</td>';
                 $order_list.=' <td>'.$cost.'</td>';
                 $order_list.=' <td>'.$order_date.'</td>';
                 $order_list.='</tr>'; 


                  //small screen
                 $small_screen_order_list.='<div class="col-md-5 col-sm-5 small-screen-display">
                            <p><b>Customer Name:</b> <span><b>'.$customer_name.'</b></span></p>
                            <p><b>Customer Phone:</b> <span><b>'.$customer_phone.'</b></span></p>
                            <p><b>Hall:</b> <span><b>'.$customer_hall.'</b></span></p>
                            <p><b>Item Name:</b><span><b>'.$product_name.'</b></span></p>
                            <p><b>Amount: GH&#162;</b> <span><b>'.$cost.'</b></span></p>
                            <p><b>Order Date:</b> <span><b>'.$order_date.'</b></span></p>
                          </div>';


          }//end while

    }

     
}//end else isset

?>



<?php include("inc/header.php"); ?>

<link rel="stylesheet" href="css/affiliate-dashboard.css"/>
<link rel="stylesheet" href="css/nav.css"/>

<title>Affiliate Dashboard</title>

</head>


 <body>

<!--site navigation-->
<?php include("inc/affiliate-nav.php"); ?>


<div class="container-fluid profile-pic-container">

 



	<div class="row">

		<div class="col-md-3">
			<div class="profile-upload-box  text-center">
			 	<img src="affil_photos/<?php echo $affiliate_profile_pic; ?>" style="max-width:100%;" alt="Affiliate's Diggimall Photo"/>
			 	<p style="margin-top:8px;"><?php echo $affiliate_name; ?></p> 
				<p>Associate ID: <b><span style="color:#999;"><?php echo $affiliate_diggi_id; ?></span></b></p> 
        <p><?php //echo $toggler; ?></p>
			      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" method="post">

			    			<label class="btn btn-danger btn-file">
			    				<i class="fa fa-camera"></i> Choose New Pic<input type="file" name="photo" id="profile_pic" style="display:none;"/>
			    			</label>

			          <label class="btn btn-danger btn-submit" id="submit_pic">
			             <i class="fa fa-upload"></i> Upload New Pic<input type="submit" name="photo_submit"  value="photo_submit" style="display:none;"/>
			          </label>
			    </form>
			  
		   </div>
          
           <div class="profile-details-box">
           	 <ul class="nav">
           	 	<li data-toggle="modal" data-target="#phone">Phone: <span><?php echo $affiliate_phone; ?></span></li>
           	 	<li data-toggle="modal" data-target="#whatsapp">Whatsapp: <span><?php echo $affiliate_whatsapp; ?></span></li>
              <li data-toggle="modal" data-target="#email">Email: <span><?php echo $affiliate_email; ?></span></li>
              <li data-toggle="modal" data-target="#hall">My Hall: <span><?php echo $affiliate_hall; ?></span></li>
           	 	<li data-toggle="modal" data-target="#mobile_money">Mobile Money Vendor: <span><?php echo $affiliate_mm_vendor; ?></span></li>
              <li data-toggle="modal" data-target="#mobile_money">Mobile Money Account: <span><?php echo $affiliate_mm_account; ?></span></li>
           	 	<li data-toggle="modal" data-target="#username">Username: <span><?php echo substr($affiliate_username,0,3)."****"; ?></span></li>
           	 	<li data-toggle="modal" data-target="#password">Password: <span><?php echo "****"; ?></span></li>
           	
           	 </ul>
           </div>

    </div>

    <!--affiliate's Customers-->
    <div class="col-md-9 items-list-col">

          <?php echo $log_error; ?>
         <div class="row stats-row">
                  <div class="col-md-4">
                    <div class="associate-status" style="background-color:<?php echo $level_color; ?>;">
                      <h2><?php echo $level; ?> <i class="fa fa-star"></i></h2>
                      <p>Associate Status</p>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="customers">
                      <h2><?php echo $referors; ?> <i class="fa fa-user"></i></h2>
                      <p>Number Of Customers</p>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="orders">
                      <h2><?php echo $past7days; ?> <i class="fa fa-file"></i></h2>
                      <p>Number Of Orders For The Past 7days</p>
                    </div>
                  </div>
         </div><!--end stats row-->

         <div class="row customers-list">
           <div class="col-md-12">
              <table class="table table-striped table-hover">
                      <thead>
                        <tr style="color:#049372;">
                          <th>Customer Name</th>
                          <th>Customer Phone</th>
                          <th>Customer Hall</th>
                        </tr>
                      </thead>

                      <tbody>

                       <?php echo $customer_list; ?>
                      </tbody>
                    </table>

           </div>
         </div><!--end customers list-->


        <div class="row tracking-row"><h4>Customers Tracking</h4></div>

        <div class="row list-row">

             <div class="col-md-4 list">
                <h4 class="click-to-filter">Click Here To Filter</h4>  
                <h4>Sum Total Of Orders: <b>GH&#162; <?php echo number_format($income,2); ?></b></h4> 

                <div class="sidebox">
                    <form role="form" class="filter-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">


                           <div class="row">
                              <div class="form-group" style="margin-top:5%;">
                                  <label class="col-lg-3 control-label">Product Category</label>
                                  <div class="col-lg-9">
                                  <select name="main_cat" id="main_cat" class="form-control">
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

                    <!--<div class="row">
                          <div class="form-group" style="margin-top:5%;">
                            <label class="col-lg-3 control-label">Product Name<span class="loader"></span></label>
                            <div class="col-lg-9">
                            <select name="p_name" class="form-control">
                            <option value="default"></option>
                          <?php //echo $item_list; ?>
                           </select>
                          </div>
                         </div>
                      </div> -->



                    <div class="row" style="margin-top:4%;">
                           <div class="form-group">
                         <input type="submit" class="btn btn-success btn-block" name="filter_submit" value="Sort Orders"/>
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
                          <option value="packaged">Packaged</option>
                          <option value="returned">Returned</option>
                          <option value="delivered">Delivered</option>
                           </select>
                          </div>
                         </div>
                      </div>
                               
                      <div class="row" style="margin-top:4%;">
                           <div class="form-group">
                         <input type="submit" class="btn btn-success btn-block" name="filter_submit" value="Sort Orders"/>
                         </div>
                      </div>

                    </form><!--end form-->
                </div><!--end side box-->
             </div><!--end list-->

             <div class="col-md-8 details">
                  <?php echo $title ?>  

                    <table class="table table-striped table-hover hidden-xs hidden-sm hidden-md visible-lg-block">
                      <thead>
                      <tr style="color:#049372;">
                        <th>Customer Name</th>
                        <th>Customer Phone</th>
                        <th>Customer Hall</th>
                        <th>Name Of Item Purchased</th>
                        <th>Cost (GH&#162;)</th>
                        <th>Order Date</th>
                      </tr>
                      </thead>

                      <tbody>

                       <?php echo $order_list; ?>
                      </tbody>
                    </table>

                  
  
             </div><!--end md-8-->


        </div><!--end table row-->

          <!--only on small screens-->
                    <div class="row hidden-lg visible-sm-block visible-md-block  visible-xs-block small-row">
                      <?php echo $small_screen_order_list; ?>
                    </div>


        	

    </div><!--end col-md-9-->


	</div><!--end row-->

</div>

<!--this file contains all update modals-->
<!--styles in nav.css -->
<?php include('inc/dashboard_modals.php'); ?>

<!--contains actual footer-->
<?php //include("inc/copyright2.php"); ?>


<?php include("inc/footer.php"); ?>

<script type="text/javascript">

    $(function(){
          $(".customers-list").hide();

          $(".customers").click(function(){
               $(".customers-list").slideToggle();
          });

    });

</script>

<script type="text/javascript">

    $(function(){
          $(".sidebox").hide();

          $(".click-to-filter").click(function(){
               $(".sidebox").slideToggle();
          });

    });

</script>

<script type="text/javascript">
    //display submit button only after  a new profile pic has been selected
    $(function(){
          $("#submit_pic").hide();

          $("#profile_pic").change(function(){
            $("#submit_pic").fadeIn();
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

</body>
</html>