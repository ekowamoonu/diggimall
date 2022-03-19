<?php ob_start();


function decryptCookie($value){
   
   $key='a,s,d,#,4,32,][*&^..#&&*';
   $crypttext=base64_decode($value);
   $iv_size=mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
   $iv=mcrypt_create_iv($iv_size,MCRYPT_RAND);
   $decrypttext=mcrypt_decrypt(MCRYPT_RIJNDAEL_256,$key,$crypttext,MCRYPT_MODE_ECB,$iv);

   return $decrypttext;

}//end decryption

if(!isset($_COOKIE['seller_logged_in'])){header("Location: seller-registration");}
else{
  $id=(int)decryptCookie($_COOKIE['seller_logged_in']);
}


//include database connection
include('functions.php'); 
include('conn'.DS.'db_connection.php'); 
include('classes'.DS.'querying_class.php');
include('classes'.DS.'form_class.php');

$query_guy=new DataQuery();
$form_man=new FormDealer();

$log_error="";

//if item is removed
if(isset($_GET['remove'])){
    $itm_id=$form_man->cleanString($_GET['remove']);
    $delete=$query_guy->delete_by_id("PRODUCTS","PRODUCT_ID",$itm_id);

    if($delete){header("Location: seller-dashboard");}
}


if(isset($_GET['item'])){

  $item_id=$form_man->cleanString($_GET['item']);

    /*Get all records about item*/
  $items=$query_guy->find_by_id("PRODUCTS","PRODUCT_ID",$item_id);

  $product_name=$items['PRODUCT_NAME'];
  $product_code=$items['PRODUCT_CODE'];
  $price=$items['PRODUCT_PRICE'];
  $product_discount=$items['DISCOUNT'];

   //discount checks
    if(empty($product_discount)||$product_discount==null||$product_discount=='null'||$product_discount==0){
        
        $product_price=$price;
        $discount="No Discount Applied";
        $new_price="Original";
    }

    else if($product_discount>0){

           $actual_discount=$product_discount*100;//get original percentage from decimal
           $discount=$actual_discount."%";
           $product_price=$price*100/(100-$actual_discount);
           $new_price=$price;

    }

  $new_price = ($new_price=="Original")?"Orginal":number_format($new_price,2);

  //$available_stock=$items['AVAILABLE_STOCK'];
  $pre=$items['PRE_ORDER'];
  $available_stock=($pre==0)?$items['AVAILABLE_STOCK']:"Pre-Order";
  $ordered_quantity_so_far=$items['TOTAL_ORDERED_QUANTITY'];
  $product_description=$items['PRODUCT_DESCRIPTION'];

  if($available_stock<=0&&$pre==0){$log_error=$form_man->showError("Item Is Out Of Stock!",1);}

  //get product small images
  $small_image=$query_guy->find_by_id("PRODUCT_SMALL_IMAGE","PDS_ID",$item_id);
  $small_img="pro_images_small".DS.$small_image['SMALL_IMAGE_FILE'];

}

else{
 header("Location: item-management?item=1");
}





/*product-updates*/
include("inc/product-updates.php");


/************************************************************************************/
   //intialise reading variable
   $item_list="";

  /*all sellers itemss*/
   $seller_items=$query_guy->find_products_by_seller($id);

   $number_of_items=mysqli_num_rows($seller_items);

   if($number_of_items==0){$item_list='<li><a href="#">You Have No Items In Your Inventory</a></li>';}
   else{

         while($item=mysqli_fetch_assoc($seller_items)){
               
               $item_name=ucfirst($item['PRODUCT_NAME']);
               $itemid=$item['PRODUCT_ID'];

               $item_list.='<li><a href="item-management?item='.$itemid.'">'.$item_name.'</a></li>';
         }//end else

   }//end if items are not empty

   /**********************************************************************************/


?>

<?php include("inc/header.php"); ?>
<link rel="stylesheet" href="css/item-management.css"/>
<link rel="stylesheet" href="css/nav.css"/>

<title>Item Management</title>
<script type="text/javascript">
           function restrict(elem){
            
              var tf= document.getElementById(elem);
              var rx= new RegExp;
              
              if(elem==="name")
              {rx=/[^0-9]/gi;}
             
              
              tf.value=tf.value.replace(rx,"");
              
            }
</script>

</head>


 <body>

<!--site navigation-->
<?php include("inc/seller-nav.php"); ?>


<div class="container-fluid profile-pic-container">
	<div class="row">
		<div class="col-md-3 items-list-side">
      <h4>Your Items</h4>
       <div class="item-details-side text-left">
             <ul class="nav">
                <?php echo $item_list; ?>
            </ul>
      </div>

  </div>

        <!--seller's products-->
        <div class="col-md-9 items-list-col">
        	<h4><?php echo ucfirst($product_name); ?> Management</h4>

          <?php echo $log_error; ?>

            <div class="col-md-4 col-sm-4  text-center">
                <div class="item-box">
                    <img class="img img-responsive" src="<?php echo $small_img; ?>"/>
                </div>
                <div class="item-details text-left">
                     <ul class="nav">
                          <li>Product Name: <span><?php echo $product_name; ?></span></li>
                          <li>Product Code: <span><?php echo $product_code; ?></span></li>
                          <li>Original Price: <b>&#162;</b> <span><?php echo number_format($product_price,2); ?></span></li>
                          <li>Discount: <span><?php echo $discount; ?></span></li>
                          <li>New Product Price: <b>&#162;</b> <span><?php echo $new_price; ?></span></li>
                          <!-- <li>Category: <span>Phones</span></li> -->
                          <li>Available Stock: <span><?php echo $available_stock; ?></span></li>
                          <li>Ordered Quantity: <span><?php echo $ordered_quantity_so_far; ?></span></li>
                          <li>Description: <span><?php echo substr($product_description,0,10)."...."; ?></span></li>
                      </ul>
                <a href="item-management?remove=<?php echo $item_id; ?>&&item=<?php echo $item_id; ?>" class="btn btn-danger">Remove This Item</a>
                </div>
            </div><!--item box ends-->

            <div class="col-md-8 col-sm-8 update-forms">

              <div class="row">
                <form class="form-inline" action="item-management?item=<?php echo $item_id; ?>" method="post"> 
                    <div class="form-group">
                      <input type="text" placeholder="Change Product Name" name="p_name" class="form-control"/>
                       <input name="name_submit" type="submit" class="btn btn-success" value="Change Name"/>
                    </div>
                </form>
              </div>

               <div class="row">
                <form class="form-inline" action="item-management?item=<?php echo $item_id; ?>" method="post"> 
                    <div class="form-group">
                      <input type="text" placeholder="Your Current Available Stock" name="p_stock" class="form-control"/>
                       <input name="stock_submit" type="submit" class="btn btn-success" value="Update Stock"/>
                    </div>
                </form>
              </div>

               <div class="row">
                <form class="form-inline" action="item-management?item=<?php echo $item_id; ?>" method="post"> 
                    <div class="form-group">
                      <input type="text" placeholder="Enter New Product Price" name="p_price" class="form-control"/>
                       <input name="price_submit" type="submit" class="btn btn-success" value="Change Price"/>
                    </div>
                </form>
              </div>

              <div class="row">
                <form class="form-inline" action="item-management?item=<?php echo $item_id; ?>" method="post" enctype="multipart/form-data"> 
                    <div class="form-group">
                      <input type="text" name="discount" id="discount" onkeyup="restrict('discount');" placeholder="Discount % (eg 20): no percentage sign" class="form-control"/>
                       <input type="submit" name="discount_submit" class="btn btn-success" value="Apply Discount"/>
                    </div>
                </form>
              </div>

                <div class="row">
                <form class="form-inline" action="item-management?item=<?php echo $item_id; ?>" method="post"> 
                    <div class="form-group">
                      <input type="text" placeholder="Enter New Product Code" name="p_code" class="form-control"/>
                       <input name="code_submit" type="submit" class="btn btn-success" value="Change Code"/>
                    </div>
                </form>
              </div>


               <div class="row">
                <form class="form-inline" action="item-management?item=<?php echo $item_id; ?>" method="post"> 
                    <div class="form-group">
                      <textarea class="form-control" id="p_description" name="p_description" placeholder="Enter New Product Description"><?php echo $product_description; ?></textarea>
                       <input style="margin-top:10px;" name="description_submit" type="submit" class="btn btn-success" value="Update Descriptions"/>
                    </div>
                </form>
              </div>

              <div class="row">
                <form class="form-inline" action="item-management?item=<?php echo $item_id; ?>" method="post" enctype="multipart/form-data"> 
                    <div class="form-group">
                      <input type="file" name="small_image" class="form-control"/>
                       <input type="submit" name="small_image_submit" class="btn btn-success" value="Upload Image"/>
                    </div>
                </form>
              </div>

              <div class="row">
                <form class="form-inline" action="item-management?item=<?php echo $item_id; ?>" method="post" enctype="multipart/form-data"> 
                    <div class="form-group">
                      <input type="file" name="large_image" class="form-control"/>
                       <input type="submit" name="large_image_submit" class="btn btn-success" value="Zoom Image"/>
                    </div>
                </form>
              </div>

        

           </div>
        	
       </div><!--end col-md-5-->

	</div>
</div>


<!--contains actual footer-->
<?php //include("inc/copyright2.php"); ?>


<?php include("inc/footer.php"); ?>

<script type="text/javascript">
    //display submit button only after  a new profile pic has been selected
    $(function(){
          $("#submit_pic").hide();

          $("#profile_pic").change(function(){
            $("#submit_pic").fadeIn();
          });
    });

</script>
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
<script type="text/javascript">
  CKEDITOR.replace( 'p_description' );
</script>

</body>
</html>