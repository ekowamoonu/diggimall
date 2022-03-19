<?php ob_start();

/*setcookie("shopper_name","",time()-10);
setcookie("shopper_id","",time()-10);*/

include("inc/cookie_checker.php");


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

   $update=$query_guy->update_sellers("SELLER_ACCESS","1",$sid);

   if($update){

      $seller_details=$query_guy->find_by_id("SELLERS","SELLER_ID",$sid);
      $seller_email=$seller_details['SELLER_EMAIL'];
      $seller_name=ucfirst($seller_details['SELLER_NAME']);
       //send simple notification email to seller
                           $to= $seller_email;     
                           $from="DiggiMall";
                           $subject='ACCESS CONFIRMED!';
                           $message='Hello '.$seller_name.'! You have successfully been confirmed as a seller on DiggiMall.';
                           $message.=' You can now login to you dashboard to begin uploading your products and making some money.';
                           $message.='Cheers! From DiggiMall';
                          
                                                
                           $headers = "From: $from\n";
                           $headers .= "MIME-Version: 1.0\n";
                           $headers .= "Content-type: text/plain; charset=iso-8859-1\n";

                           mail($to, $subject, $message, $headers);
                          
                           $log_error="<h2 style='color:green;'>Seller Confirmed</h2>";
  }

}


/*get all diggimall sellers*/
   $sellers="";

  /*all sellers*/
   $seller_list=$query_guy->get_sellers();

         while($item=mysqli_fetch_assoc($seller_list)){
                 
                 $seller_name=ucfirst($item['SELLER_NAME']);
                 $slrid=$item['SELLER_ID'];
                 $seller_phone=$item['SELLER_PHONE'];
                 $seller_email=$item['SELLER_EMAIL'];
                 $seller_level=$item['SELLER_LEVEL'];
                 $seller_hall=$item['SELLER_HALL'];
                 $seller_mobile_money=$item['MOBILE_MONEY_ACCOUNT'];
                 $seller_bank_name=$item['BANK_NAME'];
                 $seller_bank_acc_name=$item['BANK_ACCOUNT_NAME'];
                 $seller_bank_acc_num=$item['BANK_ACCOUNT_NUMBER'];

                 //check confirmed and unconfirmed
                 $seller_status=($item['SELLER_ACCESS']=='0')?"unconfirmed":"confirmed";
                  
                 $sellers.='<tr>'; 
                 $sellers.=' <td>'.$seller_name.'</td>'; 
                 $sellers.=' <td>'.$seller_phone.'</td>'; 
                 $sellers.=' <td class="hidden-xs hidden-sm">'.$seller_email.'</td>'; 
                 $sellers.=' <td class="hidden-xs hidden-sm">'.$seller_level.'</td>'; 
                 $sellers.=' <td class="hidden-xs hidden-sm">'.$seller_hall.'</td>'; 
                 $sellers.=' <td class="hidden-xs hidden-sm">'.$seller_mobile_money.'</td>'; 
                 $sellers.=' <td class="hidden-xs hidden-sm">'.$seller_bank_name.'</td>'; 
                 $sellers.=' <td class="hidden-xs hidden-sm">'.$seller_bank_acc_name.'</td>'; 
                 $sellers.=' <td class="hidden-xs hidden-sm">'.$seller_bank_acc_num.'</td>'; 
                 $sellers.=' <td>'.$seller_status.'</td>'; 
                 $sellers.=' <td><a href="dgsellers?confirm='.$slrid.'" class="btn btn-danger"> Confirm Seller</a></td>'; 
                 $sellers.='</tr>'; 

         }//end else




?>

<?php include("inc/header.php"); ?>
<link rel="stylesheet" href="css/dgadmin.css"/>
<link rel="stylesheet" href="css/nav.css"/>
<title>Administrator-Sellers</title>

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
                  <th>Seller Name</th>
                  <th>Phone</th>
                  <th class="hidden-xs hidden-sm">Email</th>
                  <th class="hidden-xs hidden-sm">Level</th>
                  <th class="hidden-xs hidden-sm">Hall</th>
                  <th class="hidden-xs hidden-sm">Mobile Money</th>
                  <th class="hidden-xs hidden-sm">Bank</th>
                  <th class="hidden-xs hidden-sm">Bank Acc Name</th>
                  <th class="hidden-xs hidden-sm">Bank Acc Number</th>
                  <th>Status</th>
                  <th>Change Status</th>
                </tr>
              </thead>

              <tbody>

               <?php echo $sellers; ?>
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