<?php

//product_name update
if(isset($_POST['name_submit'])){
  if(!$form_man->emptyField($_POST['p_name'])){ 
    $p_name=$form_man->cleanString($_POST['p_name']);
    $results=$query_guy->update_products("PRODUCT_NAME",$p_name,$item_id);

    $record=$query_guy->find_by_id("PRODUCTS","PRODUCT_ID",$item_id);
    $product_name=$record['PRODUCT_NAME'];

    $log_error= $results?$form_man->showError("Product Name Changed!",2):$form_man->showError("Update Failed! ",1);
  

  }
}

//product_stock update
if(isset($_POST['stock_submit'])){
  if(!$form_man->emptyField($_POST['p_stock'])){ 
    $p_stock=$form_man->cleanString($_POST['p_stock']);
    $results=$query_guy->update_products("AVAILABLE_STOCK",$p_stock,$item_id);

    $stock_update_date=strftime("%Y-%m-%d %H:%M:%S", time());
    $update_results=$query_guy->update_products("UPLOAD_DATE",$stock_update_date,$item_id);
    $update_pre=$query_guy->update_products("PRE_ORDER","0",$item_id);

    $record=$query_guy->find_by_id("PRODUCTS","PRODUCT_ID",$item_id);
    $available_stock=$record['AVAILABLE_STOCK'];

    $log_error= $results?$form_man->showError("Your Stock Updated!",2):$form_man->showError("Update Failed! ",1);
  

  }
}

//product_price update
if(isset($_POST['price_submit'])){
  if(!$form_man->emptyField($_POST['p_price'])){ 
    $p_price=$form_man->cleanString($_POST['p_price']);

    $results=$query_guy->update_products("PRODUCT_PRICE",$p_price,$item_id);
    $discount_results=$query_guy->update_products("DISCOUNT",0,$item_id);

    $record=$query_guy->find_by_id("PRODUCTS","PRODUCT_ID",$item_id);
   
    $product_discount=$record['DISCOUNT'];
    $price=$record['PRODUCT_PRICE'];

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

    $log_error= $results?$form_man->showError("Product Price Changed!",2):$form_man->showError("Update Failed! ",1);
  

  }
}


//product_price update
if(isset($_POST['code_submit'])){
  if(!$form_man->emptyField($_POST['p_code'])){ 
    $p_code=$form_man->cleanString($_POST['p_code']);
    $results=$query_guy->update_products("PRODUCT_CODE",$p_code,$item_id);

    $record=$query_guy->find_by_id("PRODUCTS","PRODUCT_ID",$item_id);
    $product_code=$record['PRODUCT_CODE'];

    $log_error= $results?$form_man->showError("Product Code Updated!",2):$form_man->showError("Update Failed! ",1);
  

  }
}


//product_description update
if(isset($_POST['description_submit'])){
  if(!$form_man->emptyField($_POST['p_description'])){ 
    $p_description=$_POST['p_description'];
    $results=$query_guy->update_products("PRODUCT_DESCRIPTION",$p_description,$item_id);

    $record=$query_guy->find_by_id("PRODUCTS","PRODUCT_ID",$item_id);
    $product_description=$record['PRODUCT_DESCRIPTION'];

    $log_error= $results?$form_man->showError("Description Modified!",2):$form_man->showError("Update Failed! ",1);
  

  }
}


//product small image update
if(isset($_POST['small_image_submit'])){
  if(!$form_man->emptyField($_FILES['small_image']['name'])&&!$form_man->illegalExt($_FILES['small_image']['name'])){ 

    $small_img_name=$form_man->cleanString($_FILES['small_image']['name']);
    $small_img_tmpname=$_FILES['small_image']['tmp_name'];
    $location="pro_images_small".DS;

    $results=$query_guy->update_product_image($small_img_name,$item_id,"s");

    move_uploaded_file($small_img_tmpname,$location.$small_img_name);


     $small_image=$query_guy->find_by_id("PRODUCT_SMALL_IMAGE","PDS_ID",$item_id);
     $small_img="pro_images_small".DS.$small_image['SMALL_IMAGE_FILE'];

     $log_error= $results?$form_man->showError("Smaller Image Of Item Modified!",2):$form_man->showError("Update Failed! ",1);


  

  }
}

//product large image update
if(isset($_POST['large_image_submit'])){
  if(!$form_man->emptyField($_FILES['large_image']['name'])&&!$form_man->illegalExt($_FILES['large_image']['name'])){ 

    $large_img_name=$form_man->cleanString($_FILES['large_image']['name']);
    $large_img_tmpname=$_FILES['large_image']['tmp_name'];
    $location="pro_images_large".DS;

    $results=$query_guy->update_product_image($large_img_name,$item_id,"l");

    move_uploaded_file($large_img_tmpname,$location.$large_img_name);


    $log_error= $results?$form_man->showError("Zoom Image Of Item Modified!",2):$form_man->showError("Update Failed! ",1);


  

  }
}

//discounts
if(isset($_POST['discount_submit'])){
  if(!$form_man->emptyField($_POST['discount'])){ 
    $p_discount=$form_man->cleanString($_POST['discount']);
    
    /* convert user input to decimal by dividing by 100
       find the new price:
       -multiply converted discount with original price
       -substract answer from original price
       -update original price with new price
       -re read prices
     */

    $discount=$p_discount/100;

    //calc discount
    $old_price=$price;
    /*echo $old_price;*/
    $percentage_of_old_price=$discount*$old_price;
    $new_price=$old_price-$percentage_of_old_price;

    $discount_results=$query_guy->update_products("DISCOUNT",$discount,$item_id);
    $new_results=$query_guy->update_products("PRODUCT_PRICE",$new_price,$item_id);

    $record=$query_guy->find_by_id("PRODUCTS","PRODUCT_ID",$item_id);
    $price=$record['PRODUCT_PRICE'];
    $product_discount=$record['DISCOUNT'];

    if(empty($product_discount)||$product_discount==null||$product_discount=='null'||$product_discount==0){
        
        $product_price=$price;
        $discount="No Discount Applied";
        $new_price="Original";
    }

    elseif($product_discount>0){

           $actual_discount=$product_discount*100;//get original percentage from decimal
           $discount=$actual_discount."%";
           $product_price=$price*100/(100-$actual_discount);
           $new_price=$price;

    }

    $log_error= ($new_results&&$discount_results)?$form_man->showError("Product Price Discounted!",2):$form_man->showError("Discount Application Failed! ",1);
  

  }
}









?>