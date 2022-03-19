<?php ob_start();

include("inc/cookie_checker.php");

/*check if affiliate is already logged in*/
if(isset($_COOKIE['affiliate_logged_in'])){header("Location: affiliate-dashboard");}


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
		$form_man->emptyField($_POST['b_email'])||
		$form_man->emptyField($_POST['b_phone'])||
		$form_man->emptyField($_POST['b_hall'])||
		$form_man->emptyField($_POST['b_username'])||
		$form_man->emptyField($_POST['b_password'])||
	    $form_man->emptyField($_POST['b_cpassword'])
		){
             
             $log_error=$form_man->showError("Oops! You Left Some Fields Empty! Kindly Refill The Form",1);
	}//end first nested if
   else{

   	      $affiliate_name=$form_man->cleanString($_POST['b_name']);
   	      $affiliate_email=$form_man->cleanString($_POST['b_email']);
   	      $affiliate_phone=$form_man->cleanString($_POST['b_phone']);
   	      $affiliate_hall=$form_man->cleanString($_POST['b_hall']);
   	      $affiliate_username=$form_man->cleanString($_POST['b_username']);
   	      $password=$form_man->cleanString($_POST['b_cpassword']);
   	      $affiliate_password=password_hash($password,PASSWORD_BCRYPT,['cost'=>11]);

   	      $affiliate_whatsapp= $form_man->emptyField($_POST['b_whatsapp'])?"N/A":$form_man->cleanString($_POST['b_whatsapp']);
   	      $affiliate_room= $form_man->emptyField($_POST['b_room'])?"N/A":$form_man->cleanString($_POST['b_room']);

          /*firstly update the affiliate id from the database*/
          $daffil="SELECT * FROM IDS";
          $daffil_query=mysqli_query(DB_Connection::$connection,$daffil);
          $daffil_results=mysqli_fetch_assoc($daffil_query);
          $current=$daffil_results['AFFILIATE_IDS'];
          $current++;
          $id=$current;
          $applicant_id="dpme".$id;

   	      /*insert AFFILIATE details into the database*/
   	      $affiliate_insert="INSERT INTO AFFILIATES(AFFILIATE_DIGGI_ID,AFFILIATE_USERNAME,AFFILIATE_PASSWORD,AFFILIATE_NAME,AFFILIATE_PHONE,AFFILIATE_WHATSAPP,AFFILIATE_EMAIL,AFFILIATE_HALL,AFFILIATE_ROOM_NUMBER,AFFILIATE_PROFILE_PHOTO) VALUES(";
   	      $affiliate_insert.="'{$applicant_id}',";
          $affiliate_insert.="'{$affiliate_username}',";
   	      $affiliate_insert.="'{$affiliate_password}',";
   	      $affiliate_insert.="'{$affiliate_name}',";
   	      $affiliate_insert.="'{$affiliate_phone}',";
   	      $affiliate_insert.="'{$affiliate_whatsapp}',";
   	      $affiliate_insert.="'{$affiliate_email}',";
   	      $affiliate_insert.="'{$affiliate_hall}',";
   	      $affiliate_insert.="'{$affiliate_room}','avatar.png')";

		      $affiliate_query=mysqli_query(DB_Connection::$connection,$affiliate_insert);

         /*insert into buyers table*/
          $affil_buyer_insert="INSERT INTO BUYERS(BUYER_USERNAME,BUYER_PASSWORD,BUYER_NAME,BUYER_PHONE,BUYER_WHATSAPP,BUYER_EMAIL,BUYER_HALL,BUYER_ROOM_NUMBER,BUYER_NUMBER_OF_ORDERS) VALUES(";
          $affil_buyer_insert.="'{$affiliate_username}',";
          $affil_buyer_insert.="'{$affiliate_password}',";
          $affil_buyer_insert.="'{$affiliate_name}',";
          $affil_buyer_insert.="'{$affiliate_phone}',";
          $affil_buyer_insert.="'{$affiliate_whatsapp}',";
          $affil_buyer_insert.="'{$affiliate_email}',";
          $affil_buyer_insert.="'{$affiliate_hall}',";
          $affil_buyer_insert.="'{$affiliate_room}',";
          $affil_buyer_insert.="0)";

          $affil_buyer_query=mysqli_query(DB_Connection::$connection,$affil_buyer_insert);

         if($affiliate_query&&$affil_buyer_query){

            /*now increase the webby id value*/
             $affil_update="UPDATE IDS SET AFFILIATE_IDS=AFFILIATE_IDS+1";
             $affil_update_results=mysqli_query(DB_Connection::$connection,$affil_update);

             //after application message
             $alert_text="Thank you for taking your time to apply for DiggiMall. We will go through your application and get back to you within 24-48hrs. Kindly monitor your email you provided and expect our feedback.";
             $alert_text.="Also, you won't be able to login and access your dashboard till we finalise your application but hey, keep your username and password somewhere safe and feel free to shop <b><a href='mall' style='color:white;'>the mall</a></b> while we process your application. Feel free to download our brochure <b><a href='tutorials/diggimall_brochure_t1.pdf' download='diggimall_brochure_t1.pdf' style='color:white;'>here</a></b>";
        
                            //send simple notification email to diggimall
                           $to= "diggimallgh@gmail.com";     
                           $from=$affiliate_email;
                           $subject='NEW AFFILIATE APPLICATION!';
                           $message='NAME: '.$affiliate_name.
                                    'PHONE: '.$affiliate_phone.'
                                     EMAIL: '.$affiliate_email;
                                                
                           $headers = "From: $from\n";
                           $headers .= "MIME-Version: 1.0\n";
                           $headers .= "Content-type: text/plain; charset=iso-8859-1\n";

                           mail($to, $subject, $message, $headers);
                      
                          $log_error = $form_man->showError("Awesome! ".ucfirst($affiliate_name)." ".$alert_text,2);
         }

         else{
            $log_error = $form_man->showError("Error In Processing Your Request, Your Username Is Taken",1);  
         }

        

   }//end first nested else

}//end main if


/*affiliate logging in*/

if(isset($_POST['login'])){
    if($form_man->emptyField($_POST['username'])||
       $form_man->emptyField($_POST['password'])
      ){
       $log_error=$form_man->showError("Illegal Login Attempt!",1);
    }

    else{
            $username=$form_man->cleanString($_POST['username']);
            $password=$form_man->cleanString($_POST['password']);


            $pass_check="SELECT * FROM AFFILIATES WHERE (AFFILIATE_USERNAME='{$username}' OR AFFILIATE_PHONE='{$username}') AND AFFILIATE_ACCESS=1";//select record from table using username
            $res=mysqli_query(DB_Connection::$connection,$pass_check);

           /* if(!$res){echo "failed".mysqli_error(DB_Connection::$connection);}*/
            $record= mysqli_fetch_assoc($res);

            if(password_verify($password,$record['AFFILIATE_PASSWORD'])){

                            /*$_SESSION['login_id']=$record['POTO_ID'];*/
                            $logged_id=$record['AFFILIATE_ID'];
                            setcookie("affiliate_logged_in",$logged_id,time()+31556926);
                            header("Location: affiliate-dashboard");
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
<title>Associate Authentication</title>

</head>


 <body>

<!--site navigation-->
<?php include("inc/nav.php"); ?>

<!--forms-->
<div class="container-fluid registration-forms-container">
	<div class="row">
       <div class="col-md-5 col-sm-5 col-md-offset-2 auth-form md5">
        <?php echo $log_error; ?>
    			<h3>Login Here <i class="fa fa-key"></i></h3>
    			<form class="form" action="associate-authentication" method="post">
                   <label class="control-label">Username/Phone Number:</label>
                   <input type="text" name="username" class="form-control"/>

                   <label class="control-label">Password:</label>
                   <input type="password" name="password" class="form-control"/>

                   <input type="submit" name="login" value="Login" class="btn btn-success pull-right"/>
    			</form>
            
		</div>
		<div class="col-md-5">
		    <img src="images/why.png" class="img img-responsive whyimg" style="float:left;"/>
			<p class="first-p">Why Join This Program?</p>
			<p class="others">
				<ul class="nav whylist">
					<li><i class="fa fa-check"></i> Make Some Money Leveraging On Your Network.</li>
					<li><i class="fa fa-check"></i> Be A Sales Person At You Own Convenience!</li>
					<li><i class="fa fa-check"></i> Regular Discounts For You.</li>
					<li><i class="fa fa-check"></i> The Opportunities Are Endless.</li>
				</ul>
			</p>
	   </div>

	</div><!--end login row-->

	<div class="row  auth-form2"><!--start newuser row-->
	    <div class="col-md-5 col-sm-5 col-md-offset-2 md5">
			<h3>Want To Join This Program? Signup Here <i class="fa fa-user"></i></h3>
			<form class="form" action="associate-authentication" method="post">
			   <label class="control-label">Your Name:</label>
               <input type="text" name="b_name" class="form-control"/>

               <label class="control-label">Email:</label>
               <input type="email" name="b_email" class="form-control"/>

               <label class="control-label" for="phone">Phone:</label>
               <input type="text" name="b_phone" class="form-control"/>

               <label class="control-label" for="phone">Whatsapp (optional):</label>
               <input type="text" name="b_whatsapp" class="form-control"/>

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
            			<!-- 	  <option value="Non-Resident">Non-resident</option> -->
      	        </select>

			        <label class="control-label">Room Number (optional):</label>
               <input type="text" name="b_room" class="form-control"/>

               <label class="control-label">Username:</label>
               <input type="text" name="b_username" class="form-control"/>

               <label class="control-label">Password:</label>
               <input type="password" name="b_password" class="form-control"/>

               <label class="control-label">Confirm Password:</label>
               <input type="password" name="b_cpassword" class="form-control"/>

               <input type="submit" name="user_submit" value="Join Diggimall Prime!" class="btn btn-success pull-right"/>
			</form>
		</div>
		<div class="col-md-5"><!-- <img src="images/shop-now-image.jpg" class="img img-responsive shopimg"/> --></div>
     </div>

</div>

  <!--extra items head-->
 <div class="container done-container">
 	<div class="row">
 		<div class="col-md-7 col-md-offset-5">
 			<h3>Done Signing Up?</h3>
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