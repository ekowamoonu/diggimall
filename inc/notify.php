<?php ob_start();

include('../Smsgh/Api.php');

if(isset($_POST['notify'])){
	if(!empty($_POST['notify'])){

           $h_number = (isset($_COOKIE['sms_number']))?$_COOKIE['sms_number']:"+233279666661";
           $h_name = (isset($_COOKIE['sms_name']))?ucfirst($_COOKIE['sms_name']):"Failed Name";

           //get number to send sms notification to
	       $ptn="/^0/";
	       $h_number_new=preg_replace($ptn,"+233",$h_number);

           $auth = new BasicAuth("eaabmazg", "kzfchjyo");
			// instance of ApiHost
			$apiHost = new ApiHost($auth);

			// instance of AccountApi
			$accountApi = new AccountApi($apiHost);
			// Get the account profile
			// Let us try to send some message
			$messagingApi = new MessagingApi($apiHost);
			try {
			    // Send a quick message
			    $messageResponse = $messagingApi->sendQuickMessage("Diggimall", "+233206839115", "New order just now! http:ug.diggimall.com/dgorders");
			    $messageResponse = $messagingApi->sendQuickMessage("Diggimall", "+233209058871", "New order just now! http:ug.diggimall.com/dgorders");
			    $messageResponse = $messagingApi->sendQuickMessage("Diggimall", "+2335543236033", "New order just now! http:ug.diggimall.com/dgorders");
			    $messageResponse = $messagingApi->sendQuickMessage("Diggimall", $h_number_new, "Hello ".ucfirst($h_name).", we just saw your order on DiggiMall! We will quickly process it and get back to you in a few minutes. Thank you very much!");

			} catch (Exception $ex) {
			    //echo $ex->getTraceAsString();
			    echo "";
			}

	}
}

//header("Location: thankyou");
?>