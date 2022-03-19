<?php ob_start();

function decryptCookie($value){
   
  return $value;

}//end decryption

if(!isset($_COOKIE['seller_logged_in'])){header("Location: seller-registration");}
else{
  $id=(int)decryptCookie($_COOKIE['seller_logged_in']);
}

//include database connection
include('functions.php'); 
include('conn'.DS.'db_connection.php'); 
include('classes'.DS.'querying_class.php');
include('classes'.DS.'form_class.php');

$query_guy=new DataQuery();
$form_man=new FormDealer();

$log_error="";

/*check for toggles*/
if(isset($_GET['toggle_online'])){
   $query_guy->update_sellers("AVAILABILITY",1,$id);
}
else if(isset($_GET['toggle_offline'])){
   $query_guy->update_sellers("AVAILABILITY",0,$id);
}



/*Get all records about seller*/
$record=$query_guy->find_by_id("SELLERS","SELLER_ID",$id);

$seller_username=$record['SELLER_USERNAME'];
$seller_name=ucfirst($record['SELLER_NAME']);
$seller_phone=$record['SELLER_PHONE'];
$seller_whatsapp=$record['SELLER_WHATSAPP'];
$seller_email=$record['SELLER_EMAIL'];
$seller_hall=$record['SELLER_HALL'];
$seller_profile_pic=$record['SELLER_PROFILE_PIC'];
$seller_about=$record['SELLER_ABOUT'];
$seller_mm_vendor=$record['MOBILE_MONEY_VENDOR'];
$seller_mm_account=$record['MOBILE_MONEY_ACCOUNT'];
$seller_bank_name=$record['BANK_NAME'];
$seller_bankacc_name=$record['BANK_ACCOUNT_NAME'];
$seller_bankacc_number=$record['BANK_ACCOUNT_NUMBER'];

/*check whether seller is offline or online*/
$seller_availability=$record['AVAILABILITY'];
$on_off_line= ($seller_availability==1)?'<i class="fa fa-circle" style="color:green;"></i> (online) ':'<i class="fa fa-circle" style="color:red;"></i> (offline) ';


/*toggling*/
/*
1. firstly, check to see availablity to display appropriate toggle icon
2. once the user click to toggle and icon. make the appropriate update with the get request 
3. after, get the new update by querying de database again
*/

 if($seller_availability==0){
   $toggler='<i class="fa fa-toggle-off fa-2x" style="color:grey;" onclick="window.location=\'seller-dashboard?toggle_online=good\'"></i>';
}
else{
    $toggler='<i class="fa fa-toggle-on fa-2x" style="color:#049372;" onclick="window.location=\'seller-dashboard?toggle_offline=good\'"></i>';
}

 

 /*all profile update*/
 include('inc/updates.php'); 

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
                          $locate="seller_photos".DS;

                          //immediately update view with new pic
                          $query_guy->update_sellers("SELLER_PROFILE_PIC",$new_photo,$id);
                          if(move_uploaded_file($new_details, $locate.$new_photo)){

                             /*$success= $query_guy?$query_guy->success_message("Poto Face"):"Update Failed ";*/
                              header("Refresh: 0.5;url='seller-dashboard'");
                           }//end if move_uploaded_file

        
        
    }
  }
}


/*get all products sold by the seller*/
//intialise reading variable
   $item_list="";

  /*all sellers itemss*/
   $seller_items=$query_guy->find_products_by_seller($id);

   $number_of_items=mysqli_num_rows($seller_items);

   if($number_of_items==0){$item_list='<h4>You Have No Items In Your Inventory</h4>';}
   else{

         while($item=mysqli_fetch_assoc($seller_items)){
                 
                 $item_name=ucfirst($item['PRODUCT_NAME']);
                 $itemid=$item['PRODUCT_ID'];
                 $available=$item['AVAILABLE_STOCK'];
                 $price=$item['PRODUCT_PRICE'];
                 $description=$item['PRODUCT_DESCRIPTION'];

                $item_list.='<h4>'.$item_name.'<span class="item-overlay text-right"><a class="btn btn-info" href=\'item-management?item='.$itemid.'\'"><i class="fa fa-check"></i>  Manage</a> <button class="btn btn-info extra"><i class="fa fa-plus"></i>  View Details</button> </span></h4>';
                $item_list.='<div class="item-details">';
                $item_list.=' <ul class="nav">';
                $item_list.='<li>Available Quantity: <span>'.$available.'</span></li>';
                $item_list.='<li>Price: <b>GHS</b> <span>'.number_format($price,2).'</span></li>';
                $item_list.='<li>Description: <span>'.substr($description,0,10)."...".'</span></li>';
                $item_list.='</ul></div>';
         }//end else

   }//end if items are not empty

   /**********************************************************************************/



?>



<?php include("inc/header.php"); ?>
<link rel="stylesheet" href="css/seller-dashboard.css"/>
<link rel="stylesheet" href="css/nav.css"/>

<title>Dashboard</title>

</head>


 <body>

<!--site navigation-->
<?php include("inc/seller-nav.php"); ?>

<div class="container-fluid share-shop">
  <div class="row">
    <div class="col-md-4">
      <?php  $share_link= "http://ug.diggimall.com/vendor?shop=".$id."&vendorfollower=2"; ?>
      <p><b><a style="color:white;" href="whatsapp://send?text=I%20am%20<?php echo strtoupper($seller_name); ?>%20and%20You%20can%20Visit%20my%20Shop%20on%20Diggimall!%20to%20See%20all%20My%20Cool%20Products!%20<?php echo urlencode($share_link); ?>" data-action="share/whatsapp/share">Share My Shop On Whatsapp<i class="fa fa-whatsapp  fa-fw"></i></a></b></p>
      <p><b><a style="color:white;" href="<?php echo 'http://ug.diggimall.com/vendor?shop='.$id; ?>">View My Shop <i class="fa fa-arrow-right"></i> </a></b></p>
    </div>
  </div>
</div>

<div class="container-fluid profile-pic-container">
	<div class="row">
		<div class="col-md-3">
			<div class="profile-upload-box  text-center">
			 	<img src="seller_photos/<?php echo $seller_profile_pic; ?>" style="max-width:100%;" alt="Seller's Diggimall Photo"/>
			 	<p style="margin-top:8px;"><?php echo $seller_name; ?></p> 
				<p><?php echo $on_off_line; ?> <span style="color:#999;"><?php echo $seller_email; ?></span></p> 
        <p><?php echo $toggler; ?></p>
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
           	 	<li data-toggle="modal" data-target="#phone">Phone: <span><?php echo $seller_phone; ?></span></li>
           	 	<li data-toggle="modal" data-target="#whatsapp">Whatsapp: <span><?php echo $seller_whatsapp; ?></span></li>
              <li data-toggle="modal" data-target="#email">Email: <span><?php echo $seller_email; ?></span></li>
              <li data-toggle="modal" data-target="#hall">My Hall: <span><?php echo $seller_hall; ?></span></li>
           	 	<li data-toggle="modal" data-target="#mobile_money">Mobile Money Vendor: <span><?php echo $seller_mm_vendor; ?></span></li>
              <li data-toggle="modal" data-target="#mobile_money">Mobile Money Account: <span><?php echo $seller_mm_account; ?></span></li>
              <li data-toggle="modal" data-target="#bank">Bank: <span><?php echo $seller_bank_name; ?></span></li>
           	 	<li data-toggle="modal" data-target="#bank">Bank Account Name: <span><?php echo $seller_bankacc_name; ?></span></li>
              <li data-toggle="modal" data-target="#bank">Bank Account Number: <span><?php echo substr($seller_bankacc_number,0,5)."******"; ?></span></li>
           	 	<li data-toggle="modal" data-target="#username">Username: <span><?php echo substr($seller_username,0,3)."****"; ?></span></li>
           	 	<li data-toggle="modal" data-target="#password">Password: <span><?php echo "****"; ?></span></li>
           	 	<li data-toggle="modal" data-target="#about">About: <span><?php echo substr($seller_about,0,12)."...."; ?></span></li>
           	 </ul>
           </div>

        </div>

        <!--seller's products-->
        <div class="col-md-5 items-list-col">

          <?php echo $log_error; ?>
         
        	<h4>My items</h4>

        	<h5>
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-search"></i> Search</span>
              <input type="text" class="form-control" placeholder="Quick Item Search" onkeyup="quick_search('<?php echo $id; ?>');" id="quick_item_search"/>
            </div>
          </h5>
            
            <div class="actual-list">

              <?php echo $item_list; ?>
        	</div>

       </div><!--end col-md-5-->

         <!--tutorial box-->
         <div class="col-md-4">
         	<div class="tutorials-box">
	         	<div align="center" class="embed-responsive embed-responsive-16by9">
				   <!--  <video class="embed-responsive-item" controls>
				        <source src="tutorials/tutorial.mp4" type="video/mp4">
				    </video> -->
				</div>
				<h4>We will be uploading a video tutorial for you.</h4>
				<h4>You <!-- can also  -->download the 
          <a href="tutorials/diggimall_brochure_t1.pdf" download="diggimall_brochure_t1.pdf">Diggimall Brochure</a> and the 
           <a href="tutorials/dashboard_manual.pdf" download="dashboard_manual.pdf">Dashboard Manual</a> right here for now.
        <!--   and <a href="tutorials/tutorial.mp4" download="tutorial.mp4">this tutorial video</a> -->
        </h4>
			</div>
         </div>
         <!--end tutorial-->
	</div>
</div>

<!--this file contains all update modals-->
<!--styles in nav.css -->
<?php include('inc/dashboard_modals.php'); ?>


  <!--First Time Modal Window-->
                    <div class="modal fade" id="welcome_modal" tabindex="-1"><!--modal div begins-->
                      <div class="modal-dialog"><!--modal dialog begins-->
                        <div class="modal-content"><!-- modal-content begins -->
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span>&times; Close</span></button>
                            <h3 class="modal-title"><img src="images/welcomeemoji.png" class="img img-responsive"/> Welcome Aboard! <?php echo ucfirst($seller_name); ?></h3>
                          </div>
                          <div class="modal-body"><!-- modal-body begins -->
                            <p>Your <b>DiggiMall Vendor Account</b> is all Set and ready to go.  Giving you the <b>Best Selling Experience</b>. These are the Cool Features you Have on DiggiMall that other Vendors don't have:</p>
                            <hr/>
                            <p><i class="fa fa-check"></i> Display Your Products to 10,000s of Students on Campus.</p>
                            <p><i class="fa fa-check"></i> Track Your Sales from the <b>My Orders</b> page.</p>
                            <p><i class="fa fa-check"></i> Allow us to Handle <b>Deliveries</b> for you if you want.</p>
                            <p><i class="fa fa-check"></i> Make More Sales!</p>
                            <p><i class="fa fa-check"></i> Click on <b>New Item</b> in the navigation to upload a new product</p>
                            <p><i class="fa fa-lock"></i> Above all, all your Products, Shopping and Account details are <b>Private and Secure</b></p>
                            <p>Don't Hesitate to contact us when you got any Challenges or Issues. We will be glad to help you out</p>
                            <hr/>
                           
                           
                          </div><!-- modal-body ends -->
                        </div><!-- modal-content ends -->
                      </div><!--modal dialog ends-->
                    </div><!--modal div ends-->

<!--contains actual footer-->
<?php //include("inc/copyright2.php"); ?>


<?php include("inc/footer.php"); ?>

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
   //display submit button only after  a new profile pic has been selected
    $(document).ready(function(){
          $(".item-details").hide();

             /* $(".actual-list h4").click(function(){
            $(this).next().slideToggle(200);
          });*/

    });

</script>

<script type="text/javascript">
   //display submit button only after  a new profile pic has been selected
    $(document).on("click",".actual-list h4",function(){
            $(this).next().slideToggle(200);
     });

</script>

<script type="text/javascript" src="js/category_parser.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript">
$(function(){
    
    if($.cookie('seller_pop')==null){
      $("#welcome_modal").modal("show");
      $.cookie('seller_pop','350');
    }
});
</script>
</body>
</html>