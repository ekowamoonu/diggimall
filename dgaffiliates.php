<?php ob_start();

/*setcookie("shopper_name","",time()-10);
setcookie("shopper_id","",time()-10);*/

include("inc/cookie_checker.php");

/*if(!isset($_COOKIE['affiliate_logged_in'])){header("Location: affiliate-registration");}
else{
  $id=$_COOKIE['affiliate_logged_in'];
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

$log_error="";

/*if isset confirmed*/
if(isset($_GET['confirm'])){
   
   $sid=$form_man->cleanString($_GET['confirm']);

   $update=$query_guy->update_affiliates("AFFILIATE_ACCESS","1",$sid);

   if($update){

      $affiliate_details=$query_guy->find_by_id("AFFILIATES","AFFILIATE_ID",$sid);
      $affiliate_email=$affiliate_details['AFFILIATE_EMAIL'];
      $affiliate_name=ucfirst($affiliate_details['AFFILIATE_NAME']);
       //send simple notification email to affiliate
                           $to= $affiliate_email;     
                           $from="DiggiMall";
                           $subject='ACCESS CONFIRMED!';
                           $message='Hello '.$affiliate_name.'! You have successfully been confirmed as a DiggiMall Prime Affiliate.';
                           $message.=' You can now login to you dashboard to track your progress.';
                           $message.='Cheers! From DiggiMall';
                          
                                                
                           $headers = "From: $from\n";
                           $headers .= "MIME-Version: 1.0\n";
                           $headers .= "Content-type: text/plain; charset=iso-8859-1\n";

                           mail($to, $subject, $message, $headers);
                          
                           $log_error="<h2 style='color:green;'>Affiliate Confirmed</h2>";
  }

}


/*get all diggimall affiliates*/
   $affiliates="";

  /*all affiliates*/
   $affiliate_list=$query_guy->get_affiliates();

         while($item=mysqli_fetch_assoc($affiliate_list)){
                 
                 $affiliate_name=ucfirst($item['AFFILIATE_NAME']);
                 $affil_id=$item['AFFILIATE_ID'];
                 $affiliate_phone=$item['AFFILIATE_PHONE'];
                 $affiliate_email=$item['AFFILIATE_EMAIL'];
                 $affiliate_hall=$item['AFFILIATE_HALL'];
                 $affiliate_mobile_money=$item['AFFIL_MOBILE_MONEY_ACCOUNT'];

                 //check confirmed and unconfirmed
                 $affiliate_status=($item['AFFILIATE_ACCESS']=='0')?"unconfirmed":"confirmed";
                  
                 $affiliates.='<tr>'; 
                 $affiliates.=' <td>'.$affiliate_name.'</td>'; 
                 $affiliates.=' <td>'.$affiliate_phone.'</td>'; 
                 $affiliates.=' <td class="hidden-xs hidden-sm">'.$affiliate_email.'</td>'; 
                 $affiliates.=' <td class="hidden-xs hidden-sm">'.$affiliate_hall.'</td>'; 
                 $affiliates.=' <td class="hidden-xs hidden-sm">'.$affiliate_mobile_money.'</td>'; 
                 $affiliates.=' <td>'.$affiliate_status.'</td>'; 
                 $affiliates.=' <td><a href="dgaffiliates?confirm='.$affil_id.'" class="btn btn-danger"> Confirm affiliate</a></td>'; 
                 $affiliates.='</tr>'; 

         }//end else




?>

<?php include("inc/header.php"); ?>
<link rel="stylesheet" href="css/dgadmin.css"/>
<link rel="stylesheet" href="css/nav.css"/>
<title>Administrator-Affiliates</title>

</head>


<body>

 <!--site navigation-->
<?php include("inc/admin-nav.php"); ?>


<!--body-->
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">

      <?php echo $log_error; ?>

        <table class="table table-striped table-hover">
              <thead>
                <tr style="color:#049372;">
                  <th>Affiliate Name</th>
                  <th>Phone</th>
                  <th class="hidden-xs hidden-sm">Email</th>
                  <th class="hidden-xs hidden-sm">Hall</th>
                  <th class="hidden-xs hidden-sm">Mobile Money</th>
                  <th>Status</th>
                  <th>Change Status</th>
                </tr>
              </thead>

              <tbody>

               <?php echo $affiliates; ?>
              </tbody>
          </table>

    </div>
  </div>
</div>

 	


<?php include("inc/footer.php"); ?>


</body>
</html>