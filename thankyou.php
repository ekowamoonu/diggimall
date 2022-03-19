<?php ob_start();

include("inc/cookie_checker.php");


?>


<?php include("inc/header.php"); ?>
<link rel="stylesheet" href="css/thankyou.css"/>
<link rel="stylesheet" href="css/nav.css"/>
<title>Thank You <?php echo ucfirst($shoppers_name); ?></title>

</head>


 <body>


<div class="container payment hidden-xs hidden-sm">
  <div class="row">
    <div class="col-md-12 text-center payment-col">
      <h4>Thank You So Much <?php echo ucfirst($shoppers_name); ?> ! For Purchasing On DiggiMall</h4>
    </div>
  </div>
</div>

<div class="container thankyou-container hidden-xs hidden-sm">

  <div class="row cart-row">
    <div class="col-md-3 col-lg-3 col-sm-3 md3">
      <div class="cart-final"></div>
    </div>
    <div class="col-md-6 col-sm-6 col-lg-6 md6">

      <h4 class="first-h4" style="padding-top:25px;"><b>If you did not order for food, <a href="#" style="color:red;" data-toggle="modal" data-target="#howtopay">Click here to see how to pay</a></b></h4>
      <h4><b style="color:#999;">We Will Ensure That Your Items Reach You Accordingly. </b> </h4>
      <h4><b style="color:#999;">Allow Us To Give You <a href="tutorials/diggimall_brochure_t1.pdf" download="diggimall_brochure_t1.pdf">A Free E-Copy Of Our Brochure.</a></b></h4>
      <h4 style="padding-top:20px;"><b style="color:green;"><i class="fa fa-check"></i> Delivery for all orders except Meals & Drugs is in 72hours </b></h4>
      <h4><b style="color:green;"><i class="fa fa-check"></i> The Delivery Agent Will Take The Payment Where Necessary</b></h4>
      <h4><b style="color:green;"><i class="fa fa-check"></i> We Sincerely Hope You Shop With Us Again</b></h4>
      
     </div>
    <div class="col-md-3 col-sm-3 col-lg-3 text-center price">
      <h4>What Will You Like To Do?</h4>
      <a href="mall" class="btn btn-info">SHOP AGAIN</a>
      <a href="user-profile#ordersrow" class="btn btn-primary">TRACK MY ORDER</a>
    </div>
  </div>


</div>


     <!--how to order-->
                    <div class="modal fade" id="howtopay" tabindex="-1"><!--modal div begins-->
                      <div class="modal-dialog"><!--modal dialog begins-->
                        <div class="modal-content"><!-- modal-content begins -->
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span>&times; Close</span></button>
                            <h3 class="modal-title"><img src="images/logo_bag.png" class="img img-responsive"/> How To Pay</h3>
                          </div>
                          <div class="modal-body"><!-- modal-body begins -->

                            <p><i class="fa fa-check"></i> Payments for <b>Meals</b> are <b>Cash On Delivery</b></p>
                            <p><i class="fa fa-check"></i> Pay for all other items (Fashion, Tickets, Electronics etc) into our <b>MTN Mobile Money</b> Account (0541952025). </p>
                            <p><i class="fa fa-check"></i> You can't Access/See your ordered Tickets <b>Till you make the payments</b> </p>

                            <hr/>
                            <p>Track &amp; View your <b>ORDER IDs</b> in your DiggiMall Account <a href="user-profile#ordersrow"><b> HERE</b></a></p>
                            <p>Follow the simple steps below to make a payment</p>
                            <p><b><i class="fa fa-file"></i> MTN Mobile Money</b></p>
                            <p><b>1.</b> Dial <b>*170#</b></p>
                            <p><b>2.</b> Select <b>Transfer Money</b> (No. 1 on the menu)</p>
                            <p><b>3.</b> Select <b>Mobile Money User</b> (No. 1 on the menu)</p>
                            <p><b>4.</b> Select <b>Subscriber</b> (No. 1 on the menu)</p>
                            <p><b>5.</b> Enter Mobile Number: <b>0541952025</b></p>
                            <p><b>6.</b> Confirm the mobile number by retyping</p>
                            <p><b>6.</b> Enter Amount</p>
                            <p><b>7.</b> Enter Reference Number (This your <b><a href="user-profile#ordersrow">Order ID</a></b>), which will be given to you after order is made and can be seen in your Diggimall account.</p>
                            <p><b>8.</b> Enter Your <b>Mobile Money Pin</b></p>
                            <p><b>9.</b> Wait for confirmation within <b>48hrs</b></p>
                            <hr/>
                              <p>Track &amp; View your <b>ORDER IDs</b> in your DiggiMall Account <a href="user-profile#ordersrow"><b> HERE</b></a></p>
                           
                          </div><!-- modal-body ends -->
                        </div><!-- modal-content ends -->
                      </div><!--modal dialog ends-->
                    </div><!--modal div ends-->



   <!--how to order-->
                    <div class="modal fade" id="order_received" tabindex="-1"><!--modal div begins-->
                      <div class="modal-dialog"><!--modal dialog begins-->
                        <div class="modal-content"><!-- modal-content begins -->
                          <div class="modal-header">
                            <!-- <button type="button" class="close" data-dismiss="modal"><span>&times; Close</span></button> -->
                            <h3 class="modal-title">Order Received Successfully! <i class="fa fa-check"></i></h3>
                          </div>
                          <div class="modal-body"><!-- modal-body begins -->
                            <h4>Thank you so much <?php echo ucfirst($shoppers_name); ?>! for purchasing on DiggiMall</h4>
                            <hr/>
                            <p><i class="fa fa-check"></i> Kindly give us a few minutes to process your order</b></p>
                            <p><i class="fa fa-check"></i> If you ordered for food, delivery is in <b>40minutes</b></p>
                            <p><i class="fa fa-check"></i> We will quickly contact you when necessary</p>
                            <p><i class="fa fa-check"></i> View your <a class="btn btn-danger" href="user-profile#ordersrow"><b>Diggimall Account Here</b></a> and track the progress of your order</p>
                            <hr/>
                            <p><a href="mall"><i class="fa fa-long-arrow-left"></i> Back To The Mall</a></p>
                            <h3><img src="images/logo_bag.png" class="img img-responsive pull-right"/></h3>
                            <!-- <p>Track &amp; View your <b>ORDER IDs</b> in your DiggiMall Account <a href="user-profile#ordersrow"><b> HERE</b></a></p>
                            <p>Follow the simple steps below to make a payment</p>
                            <p><b><i class="fa fa-file"></i> MTN Mobile Money</b></p>
                            <p><b>1.</b> Dial <b>*170#</b></p>
                            <p><b>2.</b> Select <b>Transfer Money</b> (No. 1 on the menu)</p>
                            <p><b>3.</b> Select <b>Mobile Money User</b> (No. 1 on the menu)</p>
                            <p><b>4.</b> Select <b>Subscriber</b> (No. 1 on the menu)</p>
                            <p><b>5.</b> Enter Mobile Number: <b>0541952025</b></p>
                            <p><b>6.</b> Confirm the mobile number by retyping</p>
                            <p><b>6.</b> Enter Amount</p>
                            <p><b>7.</b> Enter Reference Number (This your <b><a href="user-profile#ordersrow">Order ID</a></b>), which will be given to you after order is made and can be seen in your Diggimall account.</p>
                            <p><b>8.</b> Enter Your <b>Mobile Money Pin</b></p>
                            <p><b>9.</b> Wait for confirmation within <b>48hrs</b></p>
                            <hr/>
                              <p>Track &amp; View your <b>ORDER IDs</b> in your DiggiMall Account <a href="user-profile#ordersrow"><b> HERE</b></a></p>
                            -->
                          </div><!-- modal-body ends -->
                        </div><!-- modal-content ends -->
                      </div><!--modal dialog ends-->
                    </div><!--modal div ends-->





<!--contains actual footer-->
<?php //include("inc/copyright2.php"); ?>


<?php include("inc/footer.php"); ?>
<script type="text/javascript">
$(function(){
    
      $("#order_received").modal("show");

});
</script>
<script type="text/javascript">
  $(function(){
    var notify="token";
   $.post("inc/notify.php",{notify:notify},function(data){ 
             var foo=1;     
       });
});

</script>
</body>
</html>