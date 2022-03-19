<?php ob_start();

function decryptCookie($value){
   
  return $value;

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

/*check for toggles*/
if(isset($_GET['toggle_online'])){
   $query_guy->update_sellers("AVAILABILITY",1,$id);
}
else if(isset($_GET['toggle_offline'])){
   $query_guy->update_sellers("AVAILABILITY",0,$id);
}



/*****************************************item upload codes************************************************/


/*
   a. get all items sold by user
   1.get all maincategories 
   2.get all sub-categories using ajax based on an on onchange event
   3.get all sub_set using ajax based on an on onchange event
   5.Use a for loop to run through all
   6.Insert into database and upload corressponding pictures at the same time

*/


   /*get all main categories*/
   $main_categories=$query_guy->find_all_main_categories("MAIN_CATEGORY");

   $main_list="";
   while($main_results=mysqli_fetch_assoc($main_categories)){
     $main_cat_name=$main_results['MAIN_CATEGORY_NAME'];
     $main_cat_id=$main_results['MAIN_CATEGORY_ID'];

     $main_list.='<option value="'.$main_cat_id.'">'.ucfirst($main_cat_name).'</option>';

}

/*inserting all products*/
/***************************************************************************************************************************/
if(isset($_POST['product_submit'])){

      $pdt_main_category=$_POST['main_category'];
      $pdt_sub_category=$_POST['sub_category'];
      $pdt_sub_set=$_POST['sub_set'];
      $pdt_name=$_POST['p_name'];
      $pdt_code=$_POST['p_code'];
      $pdt_price=$_POST['p_price'];
      $pdt_description=$_POST['p_description'];
      $pdt_available=$_POST['p_available'];

      /*get photos*/
      $pdt_small_pic=$_FILES['small_pic']['name'];
      $pdt_small_details=$_FILES['small_pic']['tmp_name'];

      $pdt_large_pic=$_FILES['large_pic']['name'];
      $pdt_large_details=$_FILES['large_pic']['tmp_name'];

      $upload_date=strftime("%Y-%m-%d %H:%M:%S", time());

      //upload locations
      $small_pics_location="pro_images_small".DS;
      $large_pics_location="pro_images_large".DS;
      

          //process the product
          $pt_main=$form_man->cleanString($pdt_main_category);
          $pt_sub=$form_man->cleanString($pdt_sub_category);
          $pt_sub_set=$form_man->cleanString($pdt_sub_set);
          $pt_name=$form_man->cleanString($pdt_name);
          $pt_code=$form_man->cleanString($pdt_code);
          $pt_price=$form_man->cleanString($pdt_price);
          $pt_description=$pdt_description;
          $pt_available=$form_man->cleanString($pdt_available);
          $pre_order=($pt_available=="pre-order"||$pt_available=="")?1:0;
          $sel_id=$id;

          //skip empty forms
          /*if($form_man->emptyField($pt_name)||$form_man->emptyField($pt_sub)||$form_man->emptyField($pt_sub_set)||$form_man->emptyField($pt_price)||$form_man->emptyField($pdt_small_pic[$i])){continue;}*/

          //photos
          $pt_small_pic=($form_man->emptyField($pdt_small_pic))?"default_img.png":$pdt_small_pic;
          $pt_small_details=$pdt_small_details;

          $pt_large_pic=$pdt_large_pic;
          $pt_large_details=$pdt_large_details;

                     //proceed to logging this into the database

                     $product_query="INSERT INTO PRODUCTS(MAIN_CAT_ID,SUB_CAT_ID,SUB_S_ID,SEL_ID,PRODUCT_NAME,PRODUCT_CODE,";
                     $product_query.="PRODUCT_DESCRIPTION,PRODUCT_PRICE,AVAILABLE_STOCK,PRE_ORDER,TOTAL_ORDERED_QUANTITY,UPLOAD_DATE) ";
                     $product_query.="VALUES( ";
                     $product_query.="'{$pt_main}', ";
                     $product_query.="'{$pt_sub}', ";
                     $product_query.="'{$pt_sub_set}', ";
                     $product_query.="'{$sel_id}', ";
                     $product_query.="'{$pt_name}', ";
                     $product_query.="'{$pt_code}', ";
                     $product_query.="'{$pt_description}', ";
                     $product_query.="'{$pt_price}', ";
                     $product_query.="'{$pt_available}', ";
                     $product_query.="'{$pre_order}', ";
                     $product_query.="'0', ";
                     $product_query.="'{$upload_date}'";
                     $product_query.=")";

                     $insert_product=mysqli_query(DB_Connection::$connection,$product_query);

           if($insert_product){
              
              $p_id=mysqli_insert_id(DB_Connection::$connection); 

              /*small image insert and upload*/
              $small_image_insert="INSERT INTO PRODUCT_SMALL_IMAGE(PDS_ID,SMALL_IMAGE_FILE) VALUES('{$p_id}','{$pt_small_pic}')";
              $small_insert_query=mysqli_query(DB_Connection::$connection,$small_image_insert);
              move_uploaded_file($pt_small_details,  $small_pics_location. $pt_small_pic);

              /*large image insert and upload*/
              $large_image_insert="INSERT INTO PRODUCT_LARGE_IMAGE(PDL_ID,LARGE_IMAGE_FILE) VALUES('{$p_id}','{$pt_large_pic}')";
              $large_insert_query=mysqli_query(DB_Connection::$connection,$large_image_insert);
              move_uploaded_file($pt_large_details,  $large_pics_location. $pt_large_pic);

              $log_error=$form_man->showError("New Product Uploaded!",2);


        }//end if insert product
}


//intialise reading variable
   $item_list="";

  /*all sellers itemss*/
   $seller_items=$query_guy->find_products_by_seller($id);

   $number_of_items=mysqli_num_rows($seller_items);

   if($number_of_items==0){$item_list='<li><a href="#">You Have No Items In Your Inventory</a></li>';}
   else{

         while($item=mysqli_fetch_assoc($seller_items)){
               
               $item_name=ucfirst($item['PRODUCT_NAME']);
               $item_id=$item['PRODUCT_ID'];

               $item_list.='<li><a href="item-management?item='.$item_id.'">'.$item_name.'</a></li>';
         }//end else

   }//end if items are not empty

   /**********************************************************************************/

?>

<?php include("inc/header.php"); ?>
<link rel="stylesheet" href="css/new-item.css"/>
<link rel="stylesheet" href="css/nav.css"/>

<title>New Item</title>


</head>


 <body>

<!--site navigation-->
<?php include("inc/seller-nav.php"); ?>


<div class="container-fluid profile-pic-container">
	<div class="row">
	

        <!--seller's products-->
        <div class="col-md-12 items-list-col">
        	<h4>Upload New Item Here</h4>
          
          <?php echo $log_error; ?>

            <div class="col-md-8 col-sm-8 new-item-forms">

              <div class="row">
                <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data"> 

                      <div class="form-group">
                        <h3 class="form-legend">New Product Details</h3>
                        <hr/>
                      </div>

                      <div class="details">
                          
                          
                            <label class="control-label" >Main Department</label>
                            <select id="main_category" class="form-control" name="main_category">
                                <option value="default"></option>
                                <?php echo $main_list; ?>
                            </select>
                        

                            <label class="control-label"  >Sub Department</label>
                            <select id="sub_category" class="form-control" name="sub_category">
                            </select>

                            <label class="control-label">Narrow It Down</label>
                            <select class="form-control" id="sub_set" name="sub_set">
                              
                            </select>

                           <label class="control-label">Name Of Product:</label>
                           <input type="text" name="p_name" id="pname" class="form-control"/>

                           <label class="control-label">Product Code: (optional)</label>
                           <input type="text" name="p_code" class="form-control"/>

                           <label class="control-label">Price Per Item (GHS):</label>
                           <input type="text" name="p_price" id="pprice" class="form-control"/>

                           <label class="control-label">Description &amp; Extra Info For Customers:</label>
                           <textarea id="p_description" class="form-control" name="p_description"></textarea>

                           <label style="margin-top:20px;" class="control-label">Available Stock Quantity (simply enter 'pre-order' where necessary):</label>
                           <input type="text" name="p_available" class="form-control"/>

                            <label class="btn btn-info btn-file">
                            <i class="fa fa-camera"></i> Choose Display Image<input type="file" class="small"  id="profile_pic" name="small_pic" style="display:none;"/>
                            </label><span style="color:red;margin-bottom:10px;display:inline-block;" class="small-image"></span><br/>
                            
                            <label class="btn btn-info btn-file">
                            <i class="fa fa-camera"></i> Choose Larger Img (if any)<input type="file" class="large" name="large_pic" id="profile_pic" style="display:none;"/>
                            </label><span style="color:red;margin-bottom:10px;display:inline-block;" class="large-image"></span>

                      </div>

                      <div class="dynamic-form">
                     </div><!--end dynamic form-->

                    <!--   <div class="form-group">
                        <a class="btn btn-danger add-item" href="#"><i class="fa fa-plus-circle"></i> Create New Form</a>
                      </div> -->

                      <div class="form-group">
                        <div class="col-lg-12">
                        <input type="submit" disabled="disabled" class="btn btn-success pull-right upload-this" name="product_submit" value="UPLOAD PRODUCT">
                        </div>
                      </div>

                </form><!--end form-->
              </div><!--end row-->

               
           </div><!--end md-8-->

              <div class="col-md-4 col-sm-4  text-center">
                <div class="item-details text-left">
                     <ul class="nav">
                        <?php echo $item_list; ?>
                      </ul>
                </div>
            </div><!--item box ends-->

        	
       </div><!--end col-md-5-->

	</div>
</div>

<!--this file contains all update modals-->
<!--styles in nav.css -->
<?php //include('inc/dashboard_modals.php'); ?>

<!--contains actual footer-->
<?php include("inc/copyright.php"); ?>


<?php include("inc/footer.php"); ?>

<script type="text/javascript">

 $(function(){

          $(".small").change(function(){

            var smallvalue=$(".small").val();
            $(".small-image").html(smallvalue);
          });


          $(".large").change(function(){

            var largevalue=$(".large").val();
            $(".large-image").html(largevalue);
          });

         function checker(){
            
            if($('.small').get(0).files.length > 0 && $("#pprice").val().length > 0 && $("#pprice").val()!="" && $("#pname").val()!="" && $("#pname").val().length > 0){
              $(".upload-this").prop("disabled",false);
            }

         }

         setInterval(checker,1000);


          /*$(".upload-this").prop("disabled",false);*/
    });

</script>
<script type="text/javascript" src="js/new-item.js"></script>
<script type="text/javascript">

/*var checkEmptiness;
$(function(){

checkEmptiness=function(){
       $(":text ,:file, select, textarea").each(function(){
          if($(this).val()==""){
            alert("Oops! Do not leave any form field empty");
          }
     });a


}

});*/

 </script>
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
<script type="text/javascript">
  CKEDITOR.replace( 'p_description' );
</script>


</body>
</html>