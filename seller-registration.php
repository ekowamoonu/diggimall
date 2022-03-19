<?php ob_start();

include("inc/cookie_checker.php");

/*check if seller is already logged in*/
if(isset($_COOKIE['seller_logged_in'])){header("Location: seller-dashboard");}


/*********************************/

include('functions.php'); 
include('conn'.DS.'db_connection.php'); 
include('classes'.DS.'form_class.php');
include('classes'.DS.'querying_class.php');

$connection=new DB_Connection();
$form_man=new FormDealer();
$query_guy=new DataQuery();

$log_error="";


/*if user clicks join sellers*/
if(isset($_POST['seller_submit'])){

	if($form_man->emptyField($_POST['s_name'])||
		$form_man->emptyField($_POST['s_email'])||
		$form_man->emptyField($_POST['s_phone'])||
		$form_man->emptyField($_POST['s_hall'])||
		$form_man->emptyField($_POST['s_level'])||
		$form_man->emptyField($_POST['s_type'])||
		$form_man->emptyField($_POST['s_username'])||
		$form_man->emptyField($_POST['s_password'])||
	    $form_man->emptyField($_POST['s_cpassword'])
		){
             
             $log_error=$form_man->showError("Oops! You Left Some Fields Empty! Kindly Refill The Form",1);
	}//end first nested if
   else{

   	      $seller_name=$form_man->cleanString($_POST['s_name']);
   	      $seller_email=$form_man->cleanString($_POST['s_email']);
   	      $seller_phone=$form_man->cleanString($_POST['s_phone']);
   	      $seller_hall=$form_man->cleanString($_POST['s_hall']);
   	      $seller_level=$form_man->cleanString($_POST['s_level']);
   	      $seller_type=$form_man->cleanString($_POST['s_type']);
   	      $seller_username=$form_man->cleanString($_POST['s_username']);
   	      $password=$form_man->cleanString($_POST['s_cpassword']);
   	      $seller_password=password_hash($password,PASSWORD_BCRYPT,['cost'=>11]);

          /*register seller as  buyer too into the database*/
          $buyer_insert="INSERT INTO BUYERS(BUYER_USERNAME,BUYER_PASSWORD,BUYER_NAME,BUYER_PHONE,BUYER_WHATSAPP,BUYER_EMAIL,BUYER_HALL,BUYER_ROOM_NUMBER,BUYER_NUMBER_OF_ORDERS) VALUES(";
          $buyer_insert.="'{$seller_username}',";
          $buyer_insert.="'{$seller_password}',";
          $buyer_insert.="'{$seller_name}',";
          $buyer_insert.="'{$seller_phone}',";
          $buyer_insert.="'{$seller_phone}',";
          $buyer_insert.="'{$seller_email}',";
          $buyer_insert.="'{$seller_hall}',";
          $buyer_insert.="'0000',";
          $buyer_insert.="0)";

         $buyer_query=mysqli_query(DB_Connection::$connection,$buyer_insert);


                      /*insert buyer details into the database*/
                      $seller_insert="INSERT INTO SELLERS(SELLER_USERNAME,SELLER_PASSWORD,SELLER_NAME,SELLER_PHONE,SELLER_EMAIL,SELLER_HALL,SELLER_LEVEL,SELLER_PROFILE_PIC,SELLER_TYPE) VALUES(";
                      $seller_insert.="'{$seller_username}',";
                      $seller_insert.="'{$seller_password}',";
                      $seller_insert.="'{$seller_name}',";
                      $seller_insert.="'{$seller_phone}',";
                      $seller_insert.="'{$seller_email}',";
                      $seller_insert.="'{$seller_hall}',";
                      $seller_insert.="'{$seller_level}',";
                      $seller_insert.="'avatar.png',";
                      $seller_insert.="'{$seller_type}')";

                     $seller_query=mysqli_query(DB_Connection::$connection,$seller_insert);

                  if($seller_query&&$buyer_query){


                           //send simple notification email to diggimall
                           $to= "diggimallgh@gmail.com";     
                           $from=$seller_email;
                           $subject='NEW SELLER APPLICATION!';
                           $message='NAME: '.$seller_name.
                                    'PHONE: '.$seller_phone.'
                                     EMAIL: '.$seller_email;
                                                
                           $headers = "From: $from\n";
                           $headers .= "MIME-Version: 1.0\n";
                           $headers .= "Content-type: text/plain; charset=iso-8859-1\n";

                           mail($to, $subject, $message, $headers);

                           //after application message
                     $alert_text="Thank you for taking your time to apply as a seller on DiggiMall. We will go through your application and get back to you as in 24-48hrs. Kindly monitor your email you provided and expect our feedback.";
                     $alert_text.=" Also, you won't be able to login and access your dashboard till we finalise your application but hey, keep your username and password somewhere safe and feel free to shop <b><a href='mall' style='color:white;'>the mall</a></b> while we process your application. Download our brochure <b><a href='tutorials/diggimall_brochure_t1.pdf' download='diggimall_brochure_t1.pdf' style='color:white;'>here</a></b>";
                     $log_error = $form_man->showError("Awesome ".ucfirst($seller_name)."! ".$alert_text,2);
                  }//end if seller query
                  else{
                    $log_error= $form_man->showError("Error In Processing Your Request, Your Username Is Taken",1); 
                  }//end else

 
		  
         

   }//end first nested else

}//end main if




/*seller logging in*/

if(isset($_POST['login'])){
    if($form_man->emptyField($_POST['username'])||
       $form_man->emptyField($_POST['password'])
      ){
       $log_error=$form_man->showError("Illegal Login Attempt!",1);
    }

    else{
            $username=$form_man->cleanString($_POST['username']);
            $password=$form_man->cleanString($_POST['password']);
             

            $pass_check="SELECT * FROM SELLERS WHERE (SELLER_USERNAME='{$username}' OR SELLER_PHONE='{$username}') AND SELLER_ACCESS=1";//select record from table using username
            $res=mysqli_query(DB_Connection::$connection,$pass_check);

           /* if(!$res){echo "failed".mysqli_error(DB_Connection::$connection);}*/
            $record = mysqli_fetch_assoc($res);

            if(password_verify($password,$record['SELLER_PASSWORD'])){

                            /*$_SESSION['login_id']=$record['POTO_ID'];*/
                            $slr_logged_in=encryptCookie($record['SELLER_ID']);
                            $seller_logged_id=$slr_logged_in;
                            setcookie("seller_logged_in",$seller_logged_id,time()+31556926);
                            header("Location: seller-dashboard");
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
            $user_check="SELECT * FROM SELLERS WHERE SELLER_PHONE='{$user}'";
            $res=mysqli_query(DB_Connection::$connection,$user_check);
            
           /* if(!$res){echo "failed".mysqli_error(DB_Connection::$connection);}*/

            if(mysqli_num_rows($res)>=1){
                            
                            /*get signup email*/
                             $email_finder=mysqli_fetch_assoc($res);
                             $reset_email=$email_finder['SELLER_EMAIL'];

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
                            $update2=mysqli_query(DB_Connection::$connection,"UPDATE SELLERS SET SELLER_PASSWORD='{$new_password}' WHERE SELLER_PHONE='{$user}'");

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
<link rel="stylesheet" href="css/seller-registration.css"/>
<link rel="stylesheet" href="css/nav.css"/>
<link rel="stylesheet" href="css/bootstrap-select.min.css"/>
<title>Seller Application</title>

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
        <h4>Login here (Vendors)</h4>
         <form class="form" action="seller-registration" method="post">
               <label class="control-label">Username/Phone Number:</label>
               <input type="text" name="username" class="form-control"/>

               <label class="control-label">Password:</label>
               <input type="password" id="pass" name="password" class="form-control"/> 
                
               <input type="submit" name="login" value="Login" class="btn btn-success pull-right"/>
               <p class="show-forgot-form" style="color:green;font-weight:bold;cursor:pointer;">Forgot Your Password? Reset it here <i class="fa fa-long-arrow-down"></i></p>   
      </form>
      </div><!--end row-->

      <div style="display:none;" id="forgot-form" class="row login-row">
        <h4>Provide Your Signup Phone Number</h4>
         <form  class="form login-form" action="seller-registration" method="post">
                   <label class="control-label">Phone Number:</label>
                   <input type="text" name="user" class="form-control"/>
                   <input type="submit" name="reset" value="Reset Password" class="btn btn-success pull-right"/>
                  <p style="color:green;font-weight:bold;cursor:pointer;">New Password will be sent to your Signup email</p>
         </form>
      </div><!--end row-->
      
   </div><!--end col-md-6-->

    <div class="col-md-6">
      <h4>Want To Sell Your Items? Register Here</h4>
      <form class="form signup-form" action="seller-registration" method="post">
         <label class="control-label">Your Name/Name Of Your Business:</label>
               <input type="text" name="s_name" class="form-control"/>

               <label class="control-label">Email:</label>
               <input type="email" name="s_email" class="form-control"/>

               <label class="control-label">Call Number:</label>
               <input type="text" name="s_phone" class="form-control"/>

               
                <label class="control-label">Your Hall / Location:</label>
                <select class="form-control" name="s_hall">
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
                    <option value="Non-Resident">Non-resident</option>
               </select>

               <label class="control-label">Your Year Group:</label>
                 <select class="form-control" name="s_level">
                  <option value="default"></option>
                  <option value="2013-2017">2013-2017</option>
                  <option value="2014-2018">2014-2018</option>
                  <option value="2015-2019">2015-2019</option>
                  <option value="2016-2020">2016-2020</option>
                  <option value="Graduate Student">Graduate Student</option>
                  <option value="Other">Other</option>
                 </select>

                 <label class="control-label">Type Of Business:</label>
                 <select class="form-control" name="s_type">
                  <option value="default"></option>
                  <option value="Individual / Small Scale">Individual / Small Scale</option>
                  <option value="Medium Scale">Medium Scale</option>
                  <option value="Commercial / Large Scale">Commercial / Large Scale</option>
                 </select>


               <label class="control-label">Username:</label>
               <input type="text" name="s_username" class="form-control"/>

               <label class="control-label" for="phone">Password:</label>
               <input type="password" name="s_password" class="form-control"/>

               <label class="control-label" for="phone">Confirm Password:</label>
               <input type="password" name="s_cpassword" class="form-control"/>

               <input type="submit" name="seller_submit" value="Join Diggimall Sellers!" class="btn btn-success pull-right"/>
      </form>
    </div><!--end col-md-6-->
  </div>
</div>



<!--why shop div-->
<div class="container-fluid why-shop">
  <div class="row">
    <div class="col-md-2 why-image">
       <img src="images/seller.jpg" class="img img-responsive"/>
    </div>
    <div class="col-md-2 text-center why-reason">
      <h4 class="first-h4"><i class="fa fa-check fa-2x"></i></h4>
      <h4>No more Delivery Headaches</h4>
    </div>
    <div class="col-md-2 text-center why-reason">
      <h4 class="first-h4"><i class="fa fa-check fa-2x"></i></h4>
      <h4>Market of over 40,000 students</h4>
    </div>
    <div class="col-md-2 text-center why-reason">
      <h4 class="first-h4"><i class="fa fa-check fa-2x"></i></h4>
      <h4>Sell to the whole Campus of on one platform</h4>
    </div>
     <div class="col-md-2 text-center why-reason">
      <h4 class="first-h4"><i class="fa fa-check fa-2x"></i></h4>
      <h4>Efficiently Track your business</h4>
    </div>
    <div class="col-md-2 text-center why-reason">
      <h4 class="first-h4"><i class="fa fa-check fa-2x"></i></h4>
      <h4>Get Closer to your Customers</h4>
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

<!-- $("#passwordfield").on("keyup",function(){
    if($(this).val())
        $(".glyphicon-eye-open").show();
    else
        $(".glyphicon-eye-open").hide();
    });

$(".glyphicon-eye-open").mousedown(function(){
                $("#passwordfield").attr('type','text');
            }).mouseup(function(){
              $("#passwordfield").attr('type','password');
            }).mouseout(function(){
              $("#passwordfield").attr('type','password');
            }); -->