<?php ob_start();

include("inc/cookie_checker.php");

if(isset($_COOKIE['logged_in'])){
  header("Location: user-profile");
}


include('functions.php'); 
include('conn'.DS.'db_connection.php'); 
include('classes'.DS.'form_class.php');
include('classes'.DS.'querying_class.php');

$connection=new DB_Connection();
$form_man=new FormDealer();
$query_guy=new DataQuery();

$log_error="";


/*if user clicks join shoppers*/
if(isset($_POST['user_submit'])){

	if($form_man->emptyField($_POST['b_name'])||
	/*	$form_man->emptyField($_POST['b_email'])||*/
		$form_man->emptyField($_POST['b_phone'])||
		$form_man->emptyField($_POST['b_hall'])||
		$form_man->emptyField($_POST['b_username'])||
		$form_man->emptyField($_POST['b_password'])||
	    $form_man->emptyField($_POST['b_cpassword'])
		){
             
             $log_error=$form_man->showError("Oops! You Left Some Fields Empty! Kindly Refill The Form",1);
	}//end first nested if
   else{

   	      $buyer_name=$form_man->cleanString($_POST['b_name']);
   	      /*$buyer_email=$form_man->cleanString($_POST['b_email']);*/
   	      $buyer_phone=$form_man->cleanString($_POST['b_phone']);
   	      $buyer_hall=$form_man->cleanString($_POST['b_hall']);
   	      $buyer_username=$form_man->cleanString($_POST['b_username']);
   	      $password=$form_man->cleanString($_POST['b_cpassword']);
   	      $buyer_password=password_hash($password,PASSWORD_BCRYPT,['cost'=>11]);

   	    /*  $buyer_whatsapp= $form_man->emptyField($_POST['b_whatsapp'])?"N/A":$form_man->cleanString($_POST['b_whatsapp']);
   	      $buyer_room= $form_man->emptyField($_POST['b_room'])?"N/A":$form_man->cleanString($_POST['b_room']);*/
          $buyer_referor= $form_man->emptyField($_POST['b_referor'])?"N/A":$form_man->cleanString($_POST['b_referor']);

   	      /*insert buyer details into the database*/
   	      $buyer_insert="INSERT INTO BUYERS(BUYER_USERNAME,BUYER_PASSWORD,BUYER_NAME,BUYER_PHONE,BUYER_WHATSAPP,BUYER_EMAIL,BUYER_HALL,BUYER_ROOM_NUMBER,BUYER_NUMBER_OF_ORDERS,REFEROR_ID) VALUES(";
   	      $buyer_insert.="'{$buyer_username}',";
   	      $buyer_insert.="'{$buyer_password}',";
   	      $buyer_insert.="'{$buyer_name}',";
   	      $buyer_insert.="'{$buyer_phone}',";
   	      $buyer_insert.="'N/A',";
   	      $buyer_insert.="'N/A',";
   	      $buyer_insert.="'{$buyer_hall}',";
   	      $buyer_insert.="'N/A',";
   	      $buyer_insert.="0,";
          $buyer_insert.="'{$buyer_referor}')";

		     $buyer_query=mysqli_query(DB_Connection::$connection,$buyer_insert);
          
          $log_error = $buyer_query?$form_man->showError("Awesome! ".ucfirst($buyer_name)." You have Successfully Created your DiggiMall Account. Quickly Login With Your New Details",2):$form_man->showError("Error In Processing Your Request, Your Username Is Taken",1);	

   }//end first nested else

}//end main if


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
                            $lgd_id=encryptCookie($record['BUYER_ID']);
                            $logged_id=$lgd_id;
                            setcookie("logged_in",$logged_id,time()+31556926);
                            header("Location: user-profile");
                        }
            else{
                 $log_error=$form_man->showError("Illegal Login Attempt!",1);
            }

       }

    }


/*password reset*/
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

if(isset($_POST['reset'])){

    if($form_man->emptyField($_POST['user'])
      ){
       $log_error=$form_man->showError("You Left the input Blank!",1);
    }

    else{
            $user=$form_man->cleanString($_POST['user']);
       
            //check if user exists
            $user_check="SELECT * FROM BUYERS WHERE BUYER_PHONE='{$user}'";
            $res=mysqli_query(DB_Connection::$connection,$user_check);
            
           /* if(!$res){echo "failed".mysqli_error(DB_Connection::$connection);}*/

            if(mysqli_num_rows($res)>=1){
                            
                            /*get signup email*/
                             $email_finder=mysqli_fetch_assoc($res);
                             $reset_email=$email_finder['BUYER_EMAIL'];

                            //generate random string and store in a variable
                            //hash random string
                            //update database record using the phone number or email
                            //send random string as email to user with reset email provided
                            /*generate random string*/
                            $generated_password=getToken(7);

                            /*hash generated password*/
                            $new_password=password_hash($generated_password,PASSWORD_BCRYPT,['cost'=>11]);



                            /*update database with new password*/
                            $update=mysqli_query(DB_Connection::$connection,"UPDATE BUYERS SET BUYER_PASSWORD='{$new_password}' WHERE BUYER_PHONE='{$user}'");

                            /*email new password to user*/

            ////Message
            $message = "Cherished Diggi Customer,\r\n";
            $message .= "This is your new login password:\r\n";
            $message .= "-----------------------\r\n";
            $message .= "".$generated_password."\r\n";
            $message .= "-----------------------\r\n";
            $message .= "Please you can change this after you login to your profile.\r\n\r\n";
            $message .= "If you did not request this forgotten password reset, kindly contact us on 0209058871 \r\n\r\n";
            $message .= "Cheers,\r\n";
            $message .= "-- DiggiMall";
      
             //////Headers
            $headers = "From: DiggiMall <webmaster@diggimall.com> \n";
            $headers .= "To-Sender: \n";
            $headers .= "X-Mailer: PHP\n"; // mailer
            $headers .= "Reply-To: webmaster@diggimall.com\n"; // Reply address
            $headers .= "Return-Path: webmaster@diggimall.com\n"; //Return Path for errors
            $headers .= "Content-Type: text/html; charset=iso-8859-1"; //Enc-type
      
            /////Subject
            $subject = "New DiggiMall Account Password";

            $message=str_replace("\r\n","<br/ >",$message);

            mail($reset_email,$subject,$message,$headers);

           $log_error=$form_man->showError("Your Password reset was successful <i class='fa fa-check'></i>. Check your signup email for your New Password",2);
                              
                        }
            else{
                 $log_error=$form_man->showError("Your Details do not exist!",1);
            }

    }



}



?>

<?php include("inc/header.php"); ?>
<link rel="stylesheet" href="css/user-registration.css"/>
<link rel="stylesheet" href="css/nav.css"/>
<link rel="stylesheet" href="css/bootstrap-select.min.css"/>
<title>Authentication</title>

</head>


 <body>

<!--site navigation-->
<?php include("inc/nav.php"); ?>

<div class="container-fluid below-nav">
	<div class="row">
		<div class="col-md-12 text-muted">
			<h3><i class="fa fa-lock"></i> Your Details Are Safe With Us</h3>
		</div>
	</div>
</div>

<div class="container-fluid log-error">
  <div class="row">
    <div class="col-md-12 text-center">
      <p><?php echo $log_error; ?></p>
    </div>
  </div>
</div>


<!--registration container-->
<div class="container registration-container">
  <div class="row">

    <div class="col-md-6">
      <div class="row login-row">
        <h4>Login here (Customers)</h4>
         <form class="form login-form" action="user-registration" method="post">
                   <label class="control-label">Username/Phone Number:</label>
                   <input type="text" name="username" class="form-control"/>

                   <label class="control-label">Password:</label>
                   <input type="password" name="password" class="form-control"/>

                   <input type="submit" name="login" value="Login" class="btn btn-success pull-right"/>
            <p  class="show-forgot-form" style="color:green;font-weight:bold;cursor:pointer;">Forgot Your Password? Reset it here <i class="fa fa-long-arrow-down"></i></p>   
         </form>
      </div><!--end row-->

      <div style="display:none;" id="forgot-form" class="row login-row">
        <h4>Provide Your Signup Phone Number</h4>
         <form  class="form login-form" action="user-registration" method="post">
                   <label class="control-label">Phone Number:</label>
                   <input type="text" name="user" class="form-control"/>
                   <input type="submit" name="reset" value="Reset Password" class="btn btn-success pull-right"/>
                    <p style="color:green;font-weight:bold;cursor:pointer;">New Password will be sent to your Signup email</p>   
         </form>
      </div><!--end row-->
      
   </div><!--end col-md-6-->

    <div class="col-md-6">
      <h4>Don't Have an Account? Create your DiggiMall Account</h4>
       <form class="form signup-form" action="user-registration" method="post">
         <label class="control-label">Your Name:</label>
               <input type="text" name="b_name" class="form-control"/>

           <!--     <label class="control-label">Email:</label>
               <input type="email" name="b_email" class="form-control"/> -->

               <label class="control-label" for="phone">Phone:</label>
               <input type="text" name="b_phone" class="form-control"/>
<!-- 
               <label class="control-label" for="phone">Whatsapp (optional):</label>
               <input type="text" name="b_whatsapp" class="form-control"/> -->

                <label class="control-label">Your Hall:</label>
                <select class="form-control" name="b_hall">
                      <option value="default"></option>
                      <option value="Jean Nelson Aka">Jean Nelson Aka</option>
                      <option value="Alex Kwapong">Alex Kwapong</option>
                      <option value="Hilla Limann">Hilla Limann</option>
                      <option value="Elizabeth Sey">Elizabeth Sey</option>
                      <option value="Ish 1">International Students Hostel 1</option>
                      <option value="Ish 2">International Students Hostel 2</option>
                      <option value="Jubilee">Jubilee</option>
                      <option value="Pentagon Blk A">Pentagon Blk A</option>
                      <option value="Pentagon Blk B">Pentagon Blk B</option>
                      <option value="Pentagon Blk C">Pentagon Blk C</option>
                      <option value="Old Pent">Old Pent</option>
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
                  <!--    <option value="Non-Resident">Non-resident</option> -->
                </select>

            <!--    <label class="control-label">Room Number (optional):</label>
               <input type="text" name="b_room" class="form-control"/> -->


               <label class="control-label">Referral ID Of Your Referor (optional):</label>
               <input type="text" name="b_referor" class="form-control"/>

               <label class="control-label">Username:</label>
               <input type="text" name="b_username" class="form-control"/>

               <label class="control-label">Password:</label>
               <input type="password" name="b_password" class="form-control"/>

               <label class="control-label">Confirm Password:</label>
               <input type="password" name="b_cpassword" class="form-control"/>

               <input type="submit" name="user_submit" value="Create your Account" class="btn btn-success pull-right"/>
      </form>
    </div><!--end col-md-6-->
  </div>
</div>



<!--why shop div-->
<div class="container-fluid why-shop">
  <div class="row">
    <div class="col-md-2 why-image">
       <img src="images/why.png" class="img img-responsive"/>
    </div>
    <div class="col-md-2 text-center why-reason">
      <h4 class="first-h4"><i class="fa fa-check fa-2x"></i></h4>
      <h4>Lightening Fast Checkout</h4>
    </div>
    <div class="col-md-2 text-center why-reason">
      <h4 class="first-h4"><i class="fa fa-check fa-2x"></i></h4>
      <h4>Order Tracking & Profile Management</h4>
    </div>
    <div class="col-md-2 text-center why-reason">
      <h4 class="first-h4"><i class="fa fa-check fa-2x"></i></h4>
      <h4>Promotions & Discounts</h4>
    </div>
     <div class="col-md-2 text-center why-reason">
      <h4 class="first-h4"><i class="fa fa-check fa-2x"></i></h4>
      <h4>Wishlists & Other Features</h4>
    </div>
    <div class="col-md-2 text-center why-reason">
      <h4 class="first-h4"><i class="fa fa-check fa-2x"></i></h4>
      <h4>Follow Your Favorite Shops & Vendors</h4>
    </div>
  </div>
</div>

 

<!--contains actual footer-->
<?php include("inc/copyright.php"); ?>


<?php include("inc/footer.php"); ?>
<script type="text/javascript">
  $(function(){
     
     $(".show-forgot-form").click(function(){
          
          $("#forgot-form").toggle();

     });

  });
</script>
</body>
</html>