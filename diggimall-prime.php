<?php ob_start();

include("inc/cookie_checker.php");


include('functions.php'); 
include('conn'.DS.'db_connection.php'); 
include('classes'.DS.'querying_class.php');
include('classes'.DS.'form_class.php');
include('classes'.DS.'filter_class.php');
include('classes'.DS.'admin_class.php');

$query_guy=new DataQuery();
$form_man=new FormDealer();

 ?>

<?php include("inc/header.php"); ?>
<link rel="stylesheet" href="css/diggimall-prime.css"/>
<link rel="stylesheet" href="css/nav.css"/>

<title>DiggiMall Prime</title>

</head>


 <body>

<!--site navigation-->
<?php include("inc/nav.php"); ?>


<!--Banner Section Begins-->
<section class="banner" id="home">
<img class="img-responsive" alt="DiggiMall Prime Banner Image" src="images/primebanner.jpg">

</section>
<!--//Banner Section Ends-->


<!--Services(Section Two) Begins-->
<section class="services" id="services">
<div class="container contain">
  <div class="row">
    <div class="col-md-12">
	 <h2>We Want To Help You Make Some Money Even As A Student</h2>
		<p class="lead">Build a <span>thriving business</span> and an <span>income system</span>
		with your friends, and network as your customers</p>
    </div>
  </div>

<div class="row points">
	<div class="col-sm-4 col-md-3">
		<div class="thumbnail">
		<img src="images/1.jpg">
		<div>
		<h3>JOIN THE CLASSY AND SMART</h3>
		<p>Be a part of the hundreds of UG students making some money leveraging on their own networks.
		 Its surprisingly easy and free to join.  No long tinz!</p>
		<p><a href="associate-authentication" class="btn btn-info btn-block" role="button">Apply Now</a></p>
		</div>
		</div>		
	</div>
	
	<div class="col-sm-4 col-md-3">
		<div class="thumbnail">
		<img src="images/2.jpg">
		<div>
		<h3>REFERING IS THE NEW EASY</h3>
		<p>Almost everybody makes a purchase everyday. All you have to do is convince
		 your roommates, coursemates or friends to register and buy their stuff from DiggiMall. 
        </p>
		<p><a href="associate-authentication" class="btn btn-info btn-block" role="button">Apply Now</a></p>
		</div>
		</div>		
	</div>
#caf2f4;
	<div class="col-sm-4 col-md-3">
		<div class="thumbnail">
		<img src="images/3.jpg">
		<div>
		<h3>EARNING IS SWEET</h3>
		<p>You could make up to<span> GHC100</span> every week.<br>
	     You don't only get paid for new refferals. You earn on your old customers who stay as well.</p>
		<p><a href="associate-authentication" class="btn btn-info btn-block" role="button">Apply Now</a></p>
		</div>
		</div>		
	</div>
	
	<div class="col-sm-4 col-md-3">
		<div class="thumbnail">
		<img src="images/4.jpeg">
		<div>
		<h3>YOU ARE A PRIME ROYALTY</h3>
		<p>Be treated like the king or queen that you are. Enjoy huge discounts, 
		free deliveries, saturday lunch, and a whole lote of other amazing packages. </p>
		<p><a href="associate-authentication" class="btn btn-info btn-block" role="button">Apply Now</a></p>
		</div>		
	</div>
</div>
</div>
</section>
<!--//Services(Section Two) Ends-->

<!--About(Section Three) -->
<!-- <section class="about" id="about">
	<div class="container">
		<div class="row">
		
			<div class="abouthead col-md-12">
				<h2>MAKE MONEY WITH DIGGIMALL PRIME</h2>
				<p class="lead">Start your own little business with no start-up cost. Yeah, that's right! Leverage on your network, skills, or knowledge.</p>
			</div>
		
			<div class="col-sm-12 col-md-6">
				<img alt="" src="images/iphone.png"/>
			</div>
			
			<div class="col-sm-12 col-xm-12 col-md-6 aboutcont">
				<h3>Connect With Tools You Already Got</h3>
				<p>Make use of your network through:</p>
				<ul><li>Social Media</li>
				<li>Word of Mouth (Your Network)</li></ul>
				<p>How many friends do you have? You should know more than 50 people.</p>
				<ul><li>Your Coursemates</li>
				<li>Your Hallmates</li>
				</ul>
				<p>With the almost 40,000 University of Ghana Students, you can make millions on campus.</p>
			</div>
		</div>
	</div>
</section> -->
<!--//About(Section Three) Ends-->


<section class="what" id="what">
	<div class="container">	
		<div>
				<h2>What is Affiliate Marketing?</h2>
				<h3>The Business, You and The Customer.</h3>
					<p class="lead">Affiliate marketing is the process of earning a commission by promoting other people's (or company's) 
					products and services. You find a product you like, promote it to others, and earn part of the profit 
					for the sales that you make. As an affiliate, you bridge the gap between the Company and its customers 
					and get to share in the company’s profit without any startup capital or fees.</p>

					<p class="lead">You're your own boss. You control when you work, the number of people you want to refer and how much you wish to earn. 
					It all depends on you. But remember, just like any geniune source of income, your input will determine your output.</p>
		</div>
	</div>
</section>

<section>
<!-- Pricing Table Section -->
        <section id="pricing-table">
            <div class="container">
			
			<div>
				<h2 align="middle">DiggiMall Prime Affiliate Levels</h2>
				<p class="lead"> </p>
				<p></p>
			</div>
			
                <div class="row">
                    <div class="pricing">
                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="pricing-table">
                                <div class="pricing-header1">
                                    <p class="pricing-title">Bronze Associate</p>
                                    <p class="pricing-rate"><sup>GH&#162;</sup> 10 <span>/week.</span></p>
                                    <a href="associate-authentication" class="btn btn-custom">Join DiggiMall Prime!</a>
                                </div>

                                <div class="pricing-list">
                                    <ul>
                                        <li><i class="fa fa-file"></i>Min: 5 orders/week</li>
                                        <li><i class="fa fa-signal"></i><span>Regular</span> Discounts</li>
                                        <li><i class="fa fa-user"></i><span>Min:</span> 2 customers</li>
                                        <li><i class="fa fa-smile-o"></i>Twice A Week Free Campus Delivery</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="pricing-table">
                                <div class="pricing-header2">
                                    <p class="pricing-title">Gold Associate</p>
                                    <p class="pricing-rate"><sup>GH&#162;</sup> 25 <span>/week.</span></p>
                                     <a href="associate-authentication" class="btn btn-custom">Join DiggiMall Prime!</a>
                                </div>

                                <div class="pricing-list">
                                    <ul>
                                        <li><i class="fa fa-file"></i>Min: 20 orders/week</li>
                                        <li><i class="fa fa-signal"></i><span>Regular</span> Discounts</li>
                                        <li><i class="fa fa-user"></i><span>Min:</span> 10 customers</li>
                                        <li><i class="fa fa-smile-o"></i>Unlimited Free Campus Delivery</li>
                                        <li><i class="fa fa-smile-o"></i>GH&#162; 5.00 lunch on saturday</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="pricing-table">
                                <div class="pricing-header3">
                                    <p class="pricing-title">Platinum</p>
                                    <p class="pricing-rate"><sup>GH&#162;</sup> 40 <span>/week.</span></p>
                                    <a href="associate-authentication" class="btn btn-custom">Join DiggiMall Prime!</a>
                                </div>

                                <div class="pricing-list">
                                    <ul>
                                        <li><i class="fa fa-file"></i>Min: 40 orders/week</li>
                                        <li><i class="fa fa-user"></i><span>Min:</span> 25 customers</li>
                                        <li><i class="fa fa-signal"></i><span>Regular</span> Discounts</li>
                                        <li><i class="fa fa-smile-o"></i>Unlimited Free Campus Delivery</li>
                                        <li><i class="fa fa-smile-o"></i>+1 Free Outside Campus Delivery</li>
                                        <li><i class="fa fa-smile-o"></i>GH&#162; 7.00 lunch on saturday</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
						
						<div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="pricing-table">
                                <div class="pricing-header4">
                                    <p class="pricing-title">Diamond Associate</p>
                                     <p class="pricing-rate"><sup>GH&#162;</sup> 60 <span>/week.</span></p>
                                     <a href="associate-authentication" class="btn btn-custom">Join DiggiMall Prime!</a>
                                </div>

                                <div class="pricing-list">
                                    <ul>
                                        <li><i class="fa fa-file"></i>Min: 60 orders/week</li>
                                        <li><i class="fa fa-user"></i><span>Min:</span> 40 customers</li>
                                        <li><i class="fa fa-signal"></i><span>Regular</span> Discounts</li>
                                        <li><i class="fa fa-smile-o"></i>Unlimited Free Campus Delivery</li>
                                        <li><i class="fa fa-smile-o"></i>+3 Free Outside Campus Delivery</li>
                                        <li><i class="fa fa-smile-o"></i>GH&#162; 10.00 lunch on saturday</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="pricing-table">
                                <div class="pricing-header5">
                                    <p class="pricing-title">Crown Diamond</p>
                                    <p class="pricing-rate"><sup>GH&#162;</sup> 100 <span>/week.</span></p>
                                   <a href="associate-authentication" class="btn btn-custom">Join DiggiMall Prime!</a>
                                </div>

                                <div class="pricing-list">
                                    <ul>
                                        <li><i class="fa fa-file"></i>Min: 100 orders/week</li>
                                        <li><i class="fa fa-signal"></i><span>Regular</span> Discounts</li>
                                        <li><i class="fa fa-user"></i><span>Min:</span> 60 customers</li>
                                        <li><i class="fa fa-smile-o"></i>Unlimited Deliveries</li>
                                        <li><i class="fa fa-smile-o"></i>GH&#162; 15.00 lunch on saturday</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
		<!-- Pricing Table Section End -->
</section>

<!-- Call to action - one section -->
            <section id="cta" class="cta">
                <div class="container">
                    <div class="row">
                        <div class="col-md-7 cta_text">
                            <h2>Make A Move!</h2>
                            <h3>Let's Find Out How to Push Beyond Limits</h3>
                            <p>It all begins with a click of a button. Whether you are a thriving entrepreneur or you are just in 
                               to make money with us, we are still arm-stretched, ready, and eager to meet you! Feel Free To Contact Us For Your Enquiries.</p>

                            <a href="associate-authentication" class="btn btn-primary btn-lg ctabtn">Join DiggiMall Prime</a>
                        </div><!-- /.col-md-6 -->

                        <div class="col-md-5" align="middle">
                            <img src="images/cedi3.png" class="img-responsive">
                        </div><!-- /.col-md-6 -->

                    </div><!-- /.row -->
                </div><!-- /.container -->
            </section><!-- /.cta-one-section --


<!--contains actual footer-->
<?php include("inc/copyright.php"); ?>


<?php include("inc/footer.php"); ?>
</body>
</html>