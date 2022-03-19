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
<link rel="stylesheet" href="css/terms.css"/>
<link rel="stylesheet" href="css/nav.css"/>

<title>Terms</title>

</head>


 <body>

<!--site navigation-->
<?php include("inc/nav.php"); ?>

 <div class="container-fluid faq-head-container">
 	<div class="row">
 		<div class="col-md-12">
 			<div class="faq-head text-center">
 				<h3>Our policies are meant to guide your activities on this platform</h3>
 			</div>
 		</div>
 	</div>
 </div>


<!--actual container-->
<div class="container main-container">

	<div class="row terms-row">
		<h3>Introduction</h3>
		<p>Welcome to ug.diggimall.com website (the "Site"). These terms and conditions ("Terms and Conditions") apply to the Site, and all of its divisions.
          By accessing the Site, you confirm your understanding of the Terms and Conditions. If you do not agree to these Terms and Conditions of use, you shall not use this website. The Site reserves the right, to change, modify, add, or remove portions of these Terms and Conditions of use at any time. 
          Changes will be effective when posted on the Site with no other notice provided. 
          Please check these Terms and Conditions of use regularly for updates. 
          Your continued use of the Site following the posting of changes to these Terms and Conditions of use constitutes your 
          acceptance of those changes. </p>
	  
	   <h3>Use Of The Site</h3>
		<p>You are either at least 18 years of age or are accessing the Site under the supervision of a parent or legal guardian.We grant you a non-transferable and revocable license to use the Site, under the Terms and Conditions described, for the purpose of shopping for personal items sold on the Site. Commercial use or use on behalf of any third party is prohibited, except as explicitly permitted by us in advance.
		 Any breach of these Terms and Conditions shall result in the immediate revocation of the license granted in this paragraph without notice to you.
         Content provided on this site is solely for informational purposes. Product representations expressed on this Site are those of the vendor and us as well. Submissions or opinions expressed on this Site are those of the individual posting such content and may not reflect our opinions.
         Certain services and related features that may be made available on the Site may require registration or subscription. Should you choose to register or subscribe for any such services or related features, you agree to provide accurate and current information about yourself, and to promptly update such information if there are any changes.
          Every user of the Site is solely responsible for keeping passwords and other account identifiers safe and secure. The account owner is entirely responsible for all activities that occur under such password or account. 
          Furthermore, you must notify us of any unauthorized use of your password or account. The Site shall not be responsible or liable, directly or indirectly, in any way for any loss or damage of any kind incurred as a result of, or in connection with, 
          your failure to comply with this section.
         During the registration process, you agree to receive promotional emails from the Site. You can subsequently opt out of receiving such promotional e-mails by clicking on the link at the bottom of any promotional email.</p>
	
	<h3>User Submissions</h3>
		<p>Anything that you submit to the Site and/or provide to us, including but not limited to, questions, 
			reviews, comments, and suggestions (collectively, "Submissions") will become our sole and exclusive property and 
			shall not be returned to you. In addition to the rights applicable to any Submission, when you post comments or 
			reviews to the Site, you also grant us the right to use the name that you submit, in connection with such review,
		 comment, or other content. You shall not use a false e-mail address, pretend to be someone other than yourself or 
		 otherwise mislead us or third parties as to the origin of any Submissions. 
		We may, but shall not be obligated to, remove or edit any Submissions.</p>

     <h3>Order Acceptance & Pricing</h3>
		<p>Please note that there are cases when an order cannot be processed for various reasons. The Site reserves the right 
			to refuse or cancel any order for any reason at any given time. You may be asked to provide additional verifications 
			or information, including but not limited to phone number and address, before we accept the order.
       In order to avoid any fraud or fake orders, we reserve the right to obtain validation of your payment and delivery details
       before providing you with the product and to verify the personal information you shared with us. This verification can take 
       the shape of an identity or place of residence. The absence of an answer following such a demand
        will automatically cause the cancellation of the order within 2 days.We reserve the right to proceed to direct cancellation
         of an order for which we suspect a risk of fraudulent use or fake order.
       We are determined to provide the most accurate pricing information on the Site to our users; 
       however, errors may still occur, such as cases when the price of an item is not displayed correctly on the website. 
       As such, we reserve the right to refuse or cancel any order. In the event that an item is mispriced, we may, at our own
        discretion, either contact you for instructions or cancel your order and notify you of such cancellation. We shall have 
        the right to refuse or cancel any such orders whether or not the order has been confirmed and your credit card charged.</p>


    <h3>Trademarks and Copyrights</h3>
		<p>All intellectual property rights, whether registered or unregistered, in the Site, 
			information content on the Site and all the website design, including, but not limited to text, graphics, software, photos, video, music, sound, and their selection and arrangement, 
			and all software compilations, underlying source code and software shall remain our property. The entire contents of 
			the Site also are protected by copyright as a collective work under Ghanaian copyright laws 
			and international conventions. All rights are reserved.</p>


    <h3>Applicable Laws and Jurisdiction</h3>
		<p>These Terms and Conditions shall be interpreted and governed by the laws in force in Ghana. 
			Each party hereby agrees to submit to the jurisdiction of the Ghanaians 
			courts and to waive any objections based upon venue</p>

        <h3>Termination</h3>
		<p>In addition to any other legal or equitable remedies, we may, without prior notice to you, immediately 
			terminate the Terms and Conditions or revoke any or all of your rights granted under the Terms and Conditions. 
			Upon any termination of this Agreement, you shall immediately cease all access to and use of the Site and we shall, 
			in addition to any other legal or equitable remedies, immediately revoke all password(s) and account identification issued to 
			you and deny your access to and use of this Site in whole or in part. Any termination of this agreement shall not affect the 
			respective rights and obligations (including without limitation, payment obligations) of the parties arising before the date 
			of termination. You furthermore agree that the Site shall not be liable to you or to any other person as a result of any such 
			suspension or termination. If you are dissatisfied with the Site or with any terms, conditions, rules, policies, guidelines, 
			or practices in operating the Site, your sole and exclusive remedy is to discontinue using the Site.</p>
      

	</div>

</div>


  <!--extra items head-->
 <div class="container done-container">
 	<div class="row">
 		<div class="col-md-7 col-md-offset-3 text-center">
 			<h3>We got you covered</h3>
 		</div>
 	</div>
 	
 </div>


<!--call to action-->
 <div class="container call-to-action">
 	<div class="row">
 		<div class="col-md-7 col-md-offset-3"><a href="mall" class="btn btn-danger btn-block"> Back To The Mall <i class="fa fa-arrow-right"></i></a> </div>
 	</div>
 </div>








<!--contains actual footer-->
<?php include("inc/copyright2.php"); ?>


<?php include("inc/footer.php"); ?>
</body>
</html>