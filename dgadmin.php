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
include('classes'.DS.'admin_class.php');

$query_guy=new DataQuery();
$form_man=new FormDealer();
$filtering=new Filter();
$admin=new AdminAction();

/*get all diggimall statistics*/

//get number of registered buyers
$number_of_buyers=$query_guy->CountNumber("BUYERS");

//get number of confirmed sellers
$number_of_confirmed_sellers=$admin->count_confirmed_sellers();

//get number of unconfirmed sellers
$number_of_unconfirmed_sellers=$admin->count_unconfirmed_sellers();

//get number of orders for today
/*get todays date and time first*/
$order_date=strftime("%Y-%m-%d %H:%M:%S", time());
$order_year=date("Y",strtotime($order_date));//eg 2016
$order_month=date("F",strtotime($order_date));//full representation of month
$order_number_date=date("j",strtotime($order_date));//date eg 16,21

$number_of_orders_today=$admin->count_orders_today($order_year,$order_month,$order_number_date);

//number of products on diggimall
$number_of_products=$query_guy->CountNumber("PRODUCTS");


/*categories management*/
   /*get all main categories*/
   $main_categories=$query_guy->find_all_main_categories("MAIN_CATEGORY");

   $main_list="";
   while($main_results=mysqli_fetch_assoc($main_categories)){
     $main_cat_name=$main_results['MAIN_CATEGORY_NAME'];
     $main_cat_id=$main_results['MAIN_CATEGORY_ID'];

     $main_list.='<option value="'.$main_cat_id.'">'.ucfirst($main_cat_name).'</option>';

}

//get all buyers
 /*get all main categories*/
   $all_buyers=mysqli_query(DB_Connection::$connection,"SELECT * FROM BUYERS ORDER BY BUYER_NUMBER_OF_ORDERS DESC");

   $buyer_list="";
   while($buyer_results=mysqli_fetch_assoc($all_buyers)){
	  
	  $buyer_name=$buyer_results['BUYER_NAME'];
	  $buyer_hall=$buyer_results['BUYER_HALL'];
	  $buyer_phone=$buyer_results['BUYER_PHONE'];
	  $buyer_email=$buyer_results['BUYER_EMAIL'];
	  $buyer_num_orders=$buyer_results['BUYER_NUMBER_OF_ORDERS'];

       $buyer_list.='<tr>';
	   $buyer_list.=' <td>'.ucfirst($buyer_name).'</td>';
	   $buyer_list.=' <td>'.$buyer_hall.'</td>';
	   $buyer_list.=' <td>'.$buyer_phone.'</td>';
	   $buyer_list.=' <td>'.$buyer_email.'</td>';
	   $buyer_list.=' <td>'.$buyer_num_orders.'</td>';
	   $buyer_list.='</tr>'; 

}


/******************************/
/*categories management*/
$log_error="";
//main category
if(isset($_POST['add_main_category'])){
	if(!$form_man->emptyField($_POST['main_name'])){

		$main_category_name=$form_man->cleanString($_POST['main_name']);
		$add_new_category=mysqli_query(DB_Connection::$connection,"INSERT INTO MAIN_CATEGORY(MAIN_CATEGORY_NAME,COMMISSION_PERCENTAGE) VALUES('{$main_category_name}',0)");

		if($add_new_category){$log_error="<h4 style='color:green;'>Main category added</h4>";}

	}
}

//remove main category
if(isset($_POST['remove_main_category'])){
	if(!$form_man->emptyField($_POST['main_cat'])){

		$remove_main_category=$query_guy->delete_by_id("MAIN_CATEGORY","MAIN_CATEGORY_ID",$_POST['main_cat']);

		if($remove_main_category){$log_error="<h4 style='color:green;'>Main category removed</h4>";}

	}
}

/*sub categories*/
//adding new sub category
if(isset($_POST['add_sub_category'])){
	if(!$form_man->emptyField($_POST['sub_name'])&&!$form_man->emptyField($_POST['main_category'])){

		$sub_category_name=$form_man->cleanString($_POST['sub_name']);
		$parent_id=$_POST['main_category'];
		$add_new_sub_category=mysqli_query(DB_Connection::$connection,"INSERT INTO SUB_CATEGORY(PARENT_CATEGORY_ID,SUB_CATEGORY_NAME,SUB_COMMISSION_PERCENTAGE) VALUES('{$parent_id}','{$sub_category_name}',0)");

		if($add_new_sub_category){$log_error="<h4 style='color:green;'>Sub category added</h4>";}

	}
}

//remove sub category
if(isset($_POST['remove_sub_category'])){
	if(!$form_man->emptyField($_POST['sub_cat'])){

		$remove_sub_category=$query_guy->delete_by_id("SUB_CATEGORY","SUB_CATEGORY_ID",$_POST['sub_cat']);

		if($remove_sub_category){$log_error="<h4 style='color:green;'>Sub category removed</h4>";}

	}
}

/********sub set*********/
//add new subset
if(isset($_POST['add_subset'])){
	if(!$form_man->emptyField($_POST['sub_set'])&&!$form_man->emptyField($_POST['sub_cat2'])){

		$sub_set_name=$form_man->cleanString($_POST['sub_set']);
		$parent_sub_id=$_POST['sub_cat2'];

		$add_new_sub_set=mysqli_query(DB_Connection::$connection,"INSERT INTO SUB_SET(PARENT_SUB_CATEGORY_ID,SUB_SET_NAME,SUB_SET_COMMISSION_PERCENTAGE) VALUES('{$parent_sub_id}','{$sub_set_name}',0)");

		if($add_new_sub_set){$log_error="<h4 style='color:green;'>Sub set added</h4>";}

	}
}

//removing sub set
if(isset($_POST['remove_subset'])){
	if(!$form_man->emptyField($_POST['narrowed_cat'])){

		$remove_sub_set=$query_guy->delete_by_id("SUB_SET","SUB_SET_ID",$_POST['narrowed_cat']);

		if($remove_sub_set){$log_error="<h4 style='color:green;'>Sub set removed</h4>";}

	}
}

///promo
$promo_log="";

//tigo
if(isset($_POST['tigo'])){
     if(!$form_man->emptyField($_POST['tigo_airtime'])){

     	$tigo=$form_man->cleanString($_POST['tigo_airtime']);
     	$upload_date=strftime("%Y-%m-%d %H:%M:%S", time());

     	$update=mysqli_query(DB_Connection::$connection,"UPDATE PROMO SET AIRTIME='{$tigo}', UPLOAD_DATE='{$upload_date}' WHERE NETWORK='tigo'");
     	if($update){$promo_log="<h4 style='color:green;'>Tigo Airtime Uploaded</h4>";}
     }
}

//airtel
if(isset($_POST['airtel'])){
     if(!$form_man->emptyField($_POST['airtel_airtime'])){

     	$airtel=$form_man->cleanString($_POST['airtel_airtime']);
     	$upload_date=strftime("%Y-%m-%d %H:%M:%S", time());

     	$update=mysqli_query(DB_Connection::$connection,"UPDATE PROMO SET AIRTIME='{$airtel}', UPLOAD_DATE='{$upload_date}' WHERE NETWORK='airtel'");
     	if($update){$promo_log="<h4 style='color:green;'>Airtel Airtime Uploaded</h4>";}
     }
}

//vodafone
if(isset($_POST['vodafone'])){
     if(!$form_man->emptyField($_POST['vodafone_airtime'])){

     	$vodafone=$form_man->cleanString($_POST['vodafone_airtime']);
     	$upload_date=strftime("%Y-%m-%d %H:%M:%S", time());

     	$update=mysqli_query(DB_Connection::$connection,"UPDATE PROMO SET AIRTIME='{$vodafone}', UPLOAD_DATE='{$upload_date}' WHERE NETWORK='vodafone'");
     	if($update){$promo_log="<h4 style='color:green;'>Vodafone Airtime Uploaded</h4>";}
     }
}


//vodafone
if(isset($_POST['mtn'])){
     if(!$form_man->emptyField($_POST['mtn_airtime'])){

     	$mtn=$form_man->cleanString($_POST['mtn_airtime']);
     	$upload_date=strftime("%Y-%m-%d %H:%M:%S", time());

     	$update=mysqli_query(DB_Connection::$connection,"UPDATE PROMO SET AIRTIME='{$mtn}', UPLOAD_DATE='{$upload_date}' WHERE NETWORK='mtn'");
     	if($update){$promo_log="<h4 style='color:green;'>Mtn Airtime Uploaded</h4>";}
     }
}



?>

<?php include("inc/header.php"); ?>
<link rel="stylesheet" href="css/dgadmin.css"/>
<link rel="stylesheet" href="css/nav.css"/>
<title>Administrator</title>

</head>


<body>

 <!--site navigation-->
<?php include("inc/admin-nav.php"); ?>


<!--site statistics-->

<div class="container-fluid statistics-container">
	<div class="row">
		<div class="col-md-2">
			<h2><?php echo $number_of_buyers; ?></h2>
			<p>Number Of Registered Buyers</p>
		</div>
		<div class="col-md-2">
			<h2><?php echo $number_of_confirmed_sellers; ?></h2>
			<p>Number Of Confirmed Sellers</p>
		</div>
		<div class="col-md-2">
			<h2><?php echo $number_of_unconfirmed_sellers; ?></h2>
			<p>Number Of Unconfirmed Sellers</p>
		</div>
		<div class="col-md-2">
			<h2><?php echo $number_of_orders_today; ?></h2>
			<p>Number Of Orders Today</p>
		</div>
		<div class="col-md-2">
			<h2><?php echo $number_of_products; ?></h2>
			<p>Number Of Products On DiggiMall</p>
		</div>
	</div>
</div>

<!--managing categories-->
<div class="container-fluid">
	<div class="row">
		<?php echo $log_error; ?>
		<!--all main categories-->
		<div class="col-md-4">
           <h3>Manage Main Categories</h3>
		   <!--adding main category-->
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <div class="form-group">
				   <input type="text" class="form-control" name="main_name" placeholder="main category name"/>
				</div>
				<div class="form-group">
                  <input type="submit" class="btn btn-success" value="add main category" name="add_main_category"/>
                </div>
            </form>

			<!--removing main category-->
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <div class="form-group">
				   <select class="form-control" name="main_cat">
						<option value="default"></option>
						<?php echo $main_list; ?>
				   </select>
				</div>
				<div class="form-group">
                  <input type="submit" class="btn btn-danger" value="remove main category" name="remove_main_category"/>
                </div>
             </form>

			<!--removing main category-->
		</div>

		<!--all sub categories-->
        <div class="col-md-4">
        	<h3>Managing Sub Categories</h3>

            <!--add new sub category-->
           <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	        	<div class="form-group">
				   <select class="form-control"  name="main_category">
							<option value="default">Choose Main Category</option>
							<?php echo $main_list; ?>
				   </select>
			    </div>
	            <div class="form-group">
			    	<input type="text" class="form-control" name="sub_name" placeholder="enter sub category name"/>
			    </div>
	             <div class="form-group">
			    	<input type="submit" class="btn btn-success" value="add sub category" name="add_sub_category"/>
			    </div>
		 </form>

		    <!--removing sub category-->
		    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		    	<div class="form-group">
				   <select class="form-control" id="main_cat"  name="main_cat">
							<option value="default">Choose Main Category</option>
							<?php echo $main_list; ?>
				   </select>
			    </div>
			    <div class="form-group">
		             <select class="form-control" id="sub_cat" name="sub_cat" >
						<option value="default">Choose Sub Category</option>
					</select> 
			   </div>
	            <div class="form-group">
			    	<input type="submit" class="btn btn-danger" value="remove sub category" name="remove_sub_category"/>
			    </div>
			</form>

      </div>

        <!--all sub sets-->
        <div class="col-md-4">
        	<h3>Managing Sub Sets</h3>

        	<!--adding a subset-->
        	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		    	<div class="form-group">
				   <select class="form-control" id="main_cat2"  name="main_cat2">
							<option value="default">Choose Main Category</option>
							<?php echo $main_list; ?>
				   </select>
			    </div>
			    <div class="form-group">
		             <select class="form-control" id="sub_cat2" name="sub_cat2" >
						<option value="default">Choose Sub Category</option>
					</select> 
			    </div>
			    <div class="form-group">
			    	<input type="text" class="form-control" name="sub_set" placeholder="enter new subset name"/>
			    </div>
	            <div class="form-group">
			    	<input type="submit" class="btn btn-success" value="add sub set" name="add_subset"/>
			    </div>
			</form>

			<!--removing a subset-->
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				  <div class="form-group">
		             <select class="form-control" id="narrowed_cat" name="narrowed_cat" >
						<option value="default">Choose Narrowed Category</option>
					</select> 
			     </div>
			      <div class="form-group">
			    	<input type="submit" class="btn btn-danger" value="remove sub set" name="remove_subset"/>
			    </div>
			</form>

        </div>
	</div>
</div>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-4">
			<h3>Promo Credits</h3>
           <?php echo $promo_log; ?>

			<form action="dgadmin" method="post">
				<div class="form-group"><input type="text" class="form-control" placeholder="tigo" name="tigo_airtime"/></div>
				<div class="form-group"><input type="submit" class="btn btn-primary" name="tigo" value="upload tigo"/></div>
			</form>

		    <form action="dgadmin" method="post">
				<div class="form-group"><input type="text" class="form-control" placeholder="airtel" name="airtel_airtime"/></div>
				<div class="form-group"><input type="submit" class="btn btn-primary" name="airtel" value="upload airtel"/></div>
			</form>

		    <form action="dgadmin" method="post">
				<div class="form-group"><input type="text" class="form-control" placeholder="vodafone" name="vodafone_airtime"/></div>
				<div class="form-group"><input type="submit" class="btn btn-primary" name="vodafone" value="upload vodafone"/></div>
			</form>

			<form action="dgadmin" method="post">
				<div class="form-group"><input type="text" class="form-control" placeholder="mtn" name="mtn_airtime"/></div>
				<div class="form-group"><input type="submit" class="btn btn-primary" name="mtn" value="upload mtn"/></div>
			</form>

		</div>
	</div>
</div>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<h3>List Of Registered Buyers</h3>
		   <table class="table table-striped table-hover">
			<thead>
			<tr style="color:#049372;">
				<th>Buyers Name</th>
				<th>Hall</th>
				<th>Contact</th>
				<th>Buyer Email</th>
				<th>Number Of Orders</th>
			</tr>
			</thead>

			<tbody>
			 <?php echo $buyer_list; ?>
			</tbody>
		</table>
		</div>
	</div>
</div>



 	


<?php include("inc/footer.php"); ?>

<script type="text/javascript">
  $(function(){
  	
 
   //getting sub categories
   $("#main_cat,#main_cat2").change(function(){
       
          var chosen_one=$(this);
          if($(this[this.selectedIndex]).val()!="default"){

                  //$(".form-legend").append('<i class="fa fa-spinner fa-spin" style="color:red;"></i>');

                  var category_id=$(this[this.selectedIndex]).val();

                  $.post("inc/category_parser.php",{category_id:category_id},function(data){
                      
                    $("#sub_cat,#sub_cat2").html('<option value="default">Sub Categories List</option>'+data);
                      
                  });


          }//end first if

      });


   //getting sub set
   $("#sub_cat,#sub_cat2").change(function(){
       
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