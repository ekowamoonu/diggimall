<?php

/*get number of items in the cart*/
 //reading all items form cart
   $shoppers_items="SELECT COUNT(*) FROM BAG_ITEMS WHERE VSTR_ID=".$shoppers_id;
   $shoppers_query=mysqli_query(DB_Connection::$connection,$shoppers_items);
   $set=mysqli_fetch_array($shoppers_query);

   $number_of_bag_items= array_shift($set);
?>

<div class="container-fluid contact-nav">
	<div class="row">
		<div class="col-md-12">
			<ul class="nav nav-pills pull-right">
				<li><a href="tel:+233209058871"><i class="fa fa-phone"></i> 0209058871</a></li>
				<li><a href="tel:+233541952025"><i class="fa fa-phone"></i> 0541952025</a></li>
				<li class="hidden-xs hidden-sm"><a href="#" data-toggle="modal" data-target="#howtobuy"><i class="fa fa-file"></i> How to Buy</a></li>
				<li class="hidden-xs hidden-sm"><a href="#" data-toggle="modal" data-target="#howtopay"><i class="fa fa-file"></i> How to Make payment</a></li>
			<!-- 	<li class="hidden-xs hidden-sm"><a href="http://ug.diggimall.com/support"><i class="fa fa-globe"></i> Online Support</a></li> -->
			</ul>
		</div>
	</div>
</div>

  <!--navigation container-->
 <div class="container-fluid navigation-container">
 	<div class="row" >
 		<div class="col-md-12">
 			<nav class="navbar navbar-inverse">
				<div class="navbar-header"> 
				  <button type="button" class="navbar-toggle collapsed" data-target="#collapsemenu" data-toggle="collapse">
				 <!--    <span class="icon-bar"></span>
				    <span class="icon-bar"></span>
				    <span class="icon-bar"></span> -->
				    <i class="fa fa-align-justify fa-fw fa-2x"></i>
				  </button>
				  <a href="index_main" class="navbar-brand"><img src="images/logo.png"  class="img img-responsive"/></a>
				</div>

		        <div class="collapse navbar-collapse pull-right" id="collapsemenu">
				  <ul class="nav navbar-nav">
				  	<li class="hidden-xs"><a href="index_main"><i class="fa fa-home"></i> Home</a></li>
				    <li class="hidden-xs"><a href="bag" class="other-links"> <i class="fa fa-shopping-bag"></i> My Bag</a></li>
				     <li class="actual-links" ><a href="#" data-toggle="modal" data-target="#checkout_options"> Checkout</a></li>
					 <li class="actual-links hidden-xs"  ><a href="mall">Mall</a></li>
					 <li class="actual-links dropdown"  ><a href="#" href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> My Account<span class="caret"></span></a>
					 	<ul class="dropdown-menu">
					        <li><a href="user-registration">Register/Login</a><li>
					        <li><a href="user-profile">My Profile</a><li>
					    </ul>
					 </li>
					<!--  <li class="actual-links" ><a href="user-registration"> Buyers Login</a></li> -->
					 <li class="actual-links" ><a href="seller-registration"> Sellers Portal</a></li>
					<!--  <li class="actual-links dropdown"  ><a href="#" href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-star"></i> DiggiMall Prime<span class="caret"></span></a>
					 	<ul class="dropdown-menu">
					        <li><a href="diggimall-prime">About D-Prime</a><li>
					        <li><a href="associate-authentication">Signup/Login</a><li>
					    </ul>
					 </li> -->
					 <li class="actual-links" ><a href="buyer_logout"> Logout</a></li>
				 </ul>
		       </div>
  
            </nav>
 		</div>
   </div>
 </div>
 <!--end navigation-->

 <!--secondary smaller screen size navigation-->
 <div class="container-fluid text-center secondary-nav hidden-sm hidden-md hidden-lg">
 	<section class="row howto">
 		<div class="col-xs-6"><a href="#" data-toggle="modal" data-target="#howtobuy"><i class="fa fa-file"></i> How To Buy</a></div>
 		<div class="col-xs-6"><a href="#" data-toggle="modal" data-target="#howtopay"><i class="fa fa-file"></i> How To Pay</a></div>
 	</section>
 	<section class="row">
 		<div class="col-xs-3">
          <a href="index_main"> 
        	<h5><i class="fa fa-home"></i></h5>
        	<p>Home</p>
          </a>
        </div>
        <div class="col-xs-3">
          <a href="bag"> 
        	<h5><i class="fa fa-shopping-bag"></i><sup><?php echo $number_of_bag_items; ?></sup></h5>
        	<p>Bag</p>
          </a>
        </div>
         <div class="col-xs-3">
         	<a href="mall">
	        	<h5><i class="fa fa-calendar-o"></i></h5>
	        	<p>Mall</p>
	        </a>
        </div>
          <div class="col-xs-3">
          	<a href="user-registration"> 
	        	<h5><i class="fa fa-user"></i></h5>
	        	<p>Account</p>
	        </a>
        </div>
 	</section>
 </div>

 <!--modals-->
                    <!--how to order-->
                    <div class="modal fade" id="howtobuy" tabindex="-1"><!--modal div begins-->
                      <div class="modal-dialog"><!--modal dialog begins-->
                        <div class="modal-content"><!-- modal-content begins -->
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span>&times; Close</span></button>
                            <h3 class="modal-title"><img src="images/logo_bag.png" class="img img-responsive"/>How To Buy</h3>
                          </div>
                          <div class="modal-body"><!-- modal-body begins -->
                          		<p>Buy on DiggiMall in <b>3 Easy Steps!</b></p>
                          		<hr/>
                             	<p><b>1.</b>  Click/Tap on the Product Image</p>
                             	<p><b>2.</b>  Supply a quantity and Click on the <b>Buy This Now</b> orange Button to Put the Item in your <b>Bag</b></p>
                             	<p><b>3.</b> Proceed To <b>Checkout</b> to provide your Delivery details</p>
                             </ul>
                          </div><!-- modal-body ends -->
                        </div><!-- modal-content ends -->
                      </div><!--modal dialog ends-->
                    </div><!--modal div ends-->

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
                          	<p><i class="fa fa-check"></i> Payments for all other items will be made directly to DiggiMall through <b>MTN Mobile Money</b>. </p>
                          	<hr/>
                          	<p>Oh and yeah! You can just pay through a friend's Mobile Money Account if you don't have one. These are the 8 <b>simple</b> steps:</p>
              							<p><b><i class="fa fa-file"></i> MTN Mobile Money</b></p>
                            <p><b>1.</b> Dial <b>*170#</b></p>
              							<p><b>2.</b> Select <b>Transfer Money</b> (No. 1 on the menu)</p>
              							<p><b>3.</b> Select <b>Mobile Money User</b> (No. 1 on the menu)</p>
              							<p><b>4.</b> Select <b>Subscriber</b> (No. 1 on the menu)</p>
                            <p><b>5.</b> Enter Mobile Number: <b>0541952025</b></p>
                            <p><b>6.</b> Confirm the mobile number by retyping</p>
              							<p><b>6.</b> Enter Amount</p>
              							<p><b>7.</b> Enter Reference Number (This your <b>Order ID</b>), which will be given to you after order is made and can be seen in your Diggimall account.</p>
              						  <p><b>8.</b> Enter Your <b>Mobile Money Pin</b></p>
              						  <p><b>9.</b> Wait for confirmation within <b>48hrs</b></p>
              						  <hr/>
              						  <p>If you have registered, you can easily track your orders from your <a href="user-profile">DiggiMall Account Here</a></p>
                           
                          </div><!-- modal-body ends -->
                        </div><!-- modal-content ends -->
                      </div><!--modal dialog ends-->
                    </div><!--modal div ends-->

                    
                    <!--checkout options modal-->
                    <div class="modal fade" id="checkout_options" tabindex="-1"><!--modal div begins-->
                      <div class="modal-dialog"><!--modal dialog begins-->
                        <div class="modal-content"><!-- modal-content begins -->
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span>&times; Close</span></button>
                            <h3 class="modal-title">Checkout Options</h3>
                          </div>
                          <div class="modal-body"><!-- modal-body begins -->
                              <div class="row">
                                <div class="col-md-6  text-center">
                                  <a href="registered_customer_checkout" class="btn btn-primary">Registered Customer</a>
                                </div>
                                <div class="col-md-6">
                                  <a href="firsttime_customer_checkout" class="btn btn-danger">First time Customer</a>
                                </div>
                              </div>
                          </div><!-- modal-body ends -->
                        </div><!-- modal-content ends -->
                      </div><!--modal dialog ends-->
                    </div><!--modal div ends-->

