<?php ob_start();


if(isset($_COOKIE['seller_logged_in']))
{
		setcookie("seller_logged_in","",time()-10);
        header("Location: seller-registration");
}


else if(isset($_COOKIE['affiliate_logged_in'])){
		setcookie("affiliate_logged_in","",time()-10);
        header("Location: associate-authentication");
	
}

/*
else if(isset($_COOKIE['acclog'])){
		setcookie("acclog","",time()-10);
        header("Location: index");
}

else if (isset($_COOKIE['bosslog'])){
	   setcookie("bosslog","",time()-10);
        header("Location: index");
}*/

?>