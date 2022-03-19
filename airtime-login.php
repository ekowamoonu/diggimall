<?php ob_start();

header("Location: mall");
/*if(!isset($_COOKIE['shopper_name'])||!isset($_COOKIE['shopper_id'])){
   
   header("Location: index");
}

else{
      $shoppers_name=$_COOKIE['shopper_name'];
      $shoppers_id=$_COOKIE['shopper_id'];
}
*/

include('functions.php'); 
include('conn'.DS.'db_connection.php'); 
include('classes'.DS.'form_class.php');
include('classes'.DS.'querying_class.php');

$connection=new DB_Connection();
$form_man=new FormDealer();
$query_guy=new DataQuery();

$log_error="";


/*user logging in*/

if(isset($_POST['login'])){
    if($form_man->emptyField($_POST['username'])||
       $form_man->emptyField($_POST['password'])
      ){
       $log_error=$form_man->showError("Illegal Login Attempt!",1);
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
                            $promo_logged_id=$record['BUYER_ID'];
                            setcookie("promo_logged_in",$promo_logged_id,time()+420);
                            header("Location: promo");
                        }
            else{
                 $log_error=$form_man->showError("Illegal Login Attempt!",1);
            }

       }

    }



?>

<?php include("inc/header.php"); ?>
<link rel="stylesheet" href="css/user-registration.css"/>
<link rel="stylesheet" href="css/nav.css"/>
<link rel="stylesheet" href="css/bootstrap-select.min.css"/>
<link type="text/css" rel="stylesheet" href="clock_assets/flipclock.css" />
<title>DiggiPromo</title>

</head>


 <body>

<!--site navigation-->
<?php include("inc/nav.php"); ?>

<div class="container-fluid" style="margin-top:40px;">
  <div class="row">
    <div class="col-md-6 col-sm-6 col-md-offset-4">
      <div class="clock-builder-output"></div>
        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript" src="clock_assets/flipclock.js"></script>
        <style text="text/css">body .flip-clock-wrapper ul li a div div.inn, body .flip-clock-small-wrapper ul li a div div.inn { color: #CCCCCC; background-color: #333333; } body .flip-clock-dot, body .flip-clock-small-wrapper .flip-clock-dot { background: #323434; } body .flip-clock-wrapper .flip-clock-meridium a, body .flip-clock-small-wrapper .flip-clock-meridium a { color: #323434; }</style>
        <script type="text/javascript">
        $(function(){
          FlipClock.Lang.Custom = { days:'Days', hours:'Hours', minutes:'Minutes', seconds:'Seconds' };
          var opts = {
            clockFace: 'HourCounter',
            countdown: true,
            language: 'Custom'
          };  
          var countdown = 1473284340 - ((new Date().getTime())/1000); // from: 09/07/2016 09:39 pm +0000
          countdown = Math.max(1, countdown);
          $('.clock-builder-output').FlipClock(countdown, opts);
        });
        </script>
    </div>
  </div>
</div>


<!--forms-->
<div class="container-fluid registration-forms-container">
  <!-- <div class="row" style="margin-bottom:30px;margin-top:5px;">
    <div class="col-md-4 col-sm-4 col-md-offset-4">
      <img src="images/free.png" class="img img-responsive"/>
    </div>
  </div> -->

  

	<div class="row">

       <div class="col-md-4 col-sm-4 col-md-offset-4 auth-form md5">
        <?php echo $log_error; ?>
    			<h3>Random Free Airtime (Ends At 9pm Today) <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i> </h3>
    			<form class="form" action="airtime-login" method="post">
                   <label class="control-label">Username/Phone Number:</label>
                   <input type="text" name="username" class="form-control"/>

                   <label class="control-label">Password:</label>
                   <input type="password" name="password" class="form-control"/>

                   <input type="submit" name="login" value="Login" class="btn btn-success pull-right"/>
    			</form>
            
		</div>


	</div><!--end login row-->
</div>


  <!--extra items head-->
 <div class="container done-container">
 	<div class="row">
 		<div class="col-md-7 col-md-offset-5">
 			<h3>Not Signed Up?</h3>
 		</div>
 	</div>
 	
 </div>


<!--call to action-->
 <div class="container call-to-action">
 	<div class="row">
 		<div class="col-md-7 col-md-offset-3"><button class="btn btn-danger btn-block" onclick="window.location='user-registration';">   Sign Up Here <i class="fa fa-arrow-right"></i></button> </div>
 	</div>
 </div>








<!--contains actual footer-->
<?php include("inc/copyright2.php"); ?>


<?php //include("inc/footer.php"); ?>
<script src="js/bootstrap.min.js"></script>

</body>
</html>