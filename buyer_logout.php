<?php ob_start();


if(isset($_COOKIE['logged_in'])){
    setcookie("logged_in","",time()-10);
     header("Location: user-registration");
}
/*else if(isset($_COOKIE['storelog'])){
		setcookie("storelog","",time()-10);
        header("Location: index");
	
}

else if(isset($_COOKIE['acclog'])){
		setcookie("acclog","",time()-10);
        header("Location: index");
}

else if (isset($_COOKIE['bosslog'])){
	   setcookie("bosslog","",time()-10);
        header("Location: index");
}*/

?>