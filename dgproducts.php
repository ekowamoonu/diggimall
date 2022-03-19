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
if(isset($_GET['delete'])){
   
   $sid=$form_man->cleanString($_GET['delete']);

   $update=$query_guy->delete_by_id("PRODUCTS","PRODUCT_ID",$sid);

   if($update){
              $log_error="<h2 style='color:green;'>Product Removed</h2>";
  }

}

$get_products="SELECT * FROM PRODUCTS ORDER BY PRODUCT_NAME ASC";
$run_products_query=mysqli_query(DB_Connection::$connection,$get_products);
$list="";



         while($item=mysqli_fetch_assoc($run_products_query)){
                 
                 $product_id=$item['PRODUCT_ID'];
                 $product_name=$item['PRODUCT_NAME'];

                 $list.='<p><b>(ID:</b> '. $product_id.') '.$product_name.' <a href="dgproducts?delete='.$product_id.'" > Delete</a></p>';
        
         }//end else




?>

<?php include("inc/header.php"); ?>
<link rel="stylesheet" href="css/dgadmin.css"/>
<link rel="stylesheet" href="css/nav.css"/>
<title>Administrator-Products</title>

</head>


<body>

 <!--site navigation-->
<?php include("inc/admin-nav.php"); ?>


<!--body-->
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">

      <?php echo $log_error; ?>

     <?php echo $list; ?>

    </div>
  </div>
</div>

 	


<?php include("inc/footer.php"); ?>



</body>
</html>