<?php ob_start();

if(!isset($_COOKIE['shopper_name'])||!isset($_COOKIE['shopper_id'])){
   
   header("Location: index");
}

else{
      $shoppers_name=$_COOKIE['shopper_name'];
      $shoppers_id=$_COOKIE['shopper_id'];
}


if(!isset($_COOKIE['promo_logged_in'])){
   
   header("Location: airtime-login");
}




include('functions.php'); 
include('conn'.DS.'db_connection.php'); 
include('classes'.DS.'form_class.php');
include('classes'.DS.'querying_class.php');

$connection=new DB_Connection();
$form_man=new FormDealer();
$query_guy=new DataQuery();


//tigo
$promo_tigo=mysqli_query(DB_Connection::$connection,"SELECT * FROM PROMO WHERE NETWORK='tigo'");
$tigo_result=mysqli_fetch_assoc($promo_tigo);
$tigo_airtime=$tigo_result['AIRTIME'];
$tigo_date=date("h:ia",strtotime($tigo_result['UPLOAD_DATE']));

//airtel
$promo_airtel=mysqli_query(DB_Connection::$connection,"SELECT * FROM PROMO WHERE NETWORK='airtel'");
$airtel_result=mysqli_fetch_assoc($promo_airtel);
$airtel_airtime=$airtel_result['AIRTIME'];
$airtel_date=date("h:ia",strtotime($airtel_result['UPLOAD_DATE']));


//vodafone
$promo_vodafone=mysqli_query(DB_Connection::$connection,"SELECT * FROM PROMO WHERE NETWORK='vodafone'");
$vodafone_result=mysqli_fetch_assoc($promo_vodafone);
$vodafone_airtime=$vodafone_result['AIRTIME'];
$vodafone_date=date("h:ia",strtotime($vodafone_result['UPLOAD_DATE']));

//mtn
$promo_mtn=mysqli_query(DB_Connection::$connection,"SELECT * FROM PROMO WHERE NETWORK='mtn'");
$mtn_result=mysqli_fetch_assoc($promo_mtn);
$mtn_airtime=$mtn_result['AIRTIME'];
$mtn_date=date("h:ia",strtotime($mtn_result['UPLOAD_DATE']));



?>

<?php include("inc/header.php"); ?>
<link rel="stylesheet" href="css/user-registration.css"/>
<link rel="stylesheet" href="css/nav.css"/>
<link rel="stylesheet" href="css/bootstrap-select.min.css"/>
<title>Free Airtime!!</title>

</head>
<style type="text/css">
  .col-md-8 h3{

    margin-top:60px;
    padding:10px;
    letter-spacing: 2px;

  }

.auth-form hr{
margin-bottom:20px;
}  

</style>


 <body>

<!--site navigation-->
<?php include("inc/nav.php"); ?>


<!--forms-->
<div class="container-fluid registration-forms-container">
  <div class="row" style="margin-bottom:30px;margin-top:30px;">
    <div class="col-md-4 col-sm-4 col-md-offset-4">
      <img src="images/url.png" class="img img-responsive"/>
    </div>
  </div>

	<div class="row">
       
       <div class="col-md-6 col-sm-6 col-md-offset-3 auth-form md5">

           <div class="row">
             <div class="col-md-4"> <img src="images/tigo.png" class="img img-responsive"/></div>
             <div class="col-md-8"><h3  style="color:white;background-color:#3498DB;"><?php echo $tigo_airtime; ?></h3>
              <p style="font-weight:bold;color:grey">Uploaded at: <?php echo $tigo_date; ?></p>
             </div>
           </div>
            <hr/>
           <div class="row">
             <div class="col-md-4"> <img src="images/airtel.png" class="img img-responsive"/></div>
              <div class="col-md-8"><h3  style="color:white;background-color:#96281B;"><?php echo $airtel_airtime; ?></h3>
                <p style="font-weight:bold;color:grey">Uploaded at: <?php echo $airtel_date; ?></p>
              </div>
           </div>
            <hr/>
           <div class="row">
             <div class="col-md-4"> <img src="images/vodafone.jpg" class="img img-responsive"/></div>
              <div class="col-md-8"><h3  style="color:white;background-color:#F22613;"><?php echo $vodafone_airtime; ?></h3>
                <p style="font-weight:bold;color:grey">Uploaded at: <?php echo $vodafone_date; ?></p>
              </div>
           </div>
            <hr/>
           <div class="row">
             <div class="col-md-4"> <img src="images/mtn.jpg" class="img img-responsive"/></div>
             <div class="col-md-8"><h3  style="color:white;background-color:#F5AB35;"><?php echo $mtn_airtime; ?></h3>
              <p style="font-weight:bold;color:grey">Uploaded at: <?php echo $mtn_date; ?></p>
             </div>
           </div>
            
		  </div>


	</div><!--end login row-->
</div>


  <!--extra items head-->
 <div class="container done-container">
 	<div class="row">
 		<div class="col-md-7 col-md-offset-5">
 			<h3>Want To See Some Cool Items?</h3>
 		</div>
 	</div>
 	
 </div>


<!--call to action-->
 <div class="container call-to-action">
 	<div class="row">
 		<div class="col-md-7 col-md-offset-3"><button class="btn btn-danger btn-block" onclick="window.location='mall';">   Hit The Mall <i class="fa fa-arrow-right"></i></button> </div>
 	</div>
 </div>








<!--contains actual footer-->
<?php include("inc/copyright2.php"); ?>


<?php include("inc/footer.php"); ?>
<script type="text/javascript" src="js/bootstrap-select.min.js"></script>
</body>
</html>