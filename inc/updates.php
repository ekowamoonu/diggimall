<?php

//call_line update
if(isset($_POST['call_line_submit'])){
  if(!$form_man->emptyField($_POST['call_line'])){ 
    $call_line=$form_man->cleanString($_POST['call_line']);
    $results=$query_guy->update_sellers("SELLER_PHONE",$call_line,$id);

    //update in buyers table too
    $buyer_query=mysqli_query(DB_Connection::$connection,"UPDATE BUYERS SET BUYER_PHONE='{$call_line}' WHERE BUYER_PHONE='{$seller_phone}'");

    $record=$query_guy->find_by_id("SELLERS","SELLER_ID",$id);
    $seller_phone=$record['SELLER_PHONE'];

    $log_error= $results?$form_man->showError("Successful Call Line Update!",2):$form_man->showError("Update Failed! ",1);
  

  }
}

//whatsapp line update
if(isset($_POST['whatsapp_submit'])){
  if(!$form_man->emptyField($_POST['whatsapp'])){ 
    $whatsapp=$form_man->cleanString($_POST['whatsapp']);
    $results=$query_guy->update_sellers("SELLER_WHATSAPP",$whatsapp,$id);

    $record=$query_guy->find_by_id("SELLERS","SELLER_ID",$id);
    $seller_whatsapp=$record['SELLER_WHATSAPP'];

    $log_error= $results?$form_man->showError("Whatsapp Line Updated!",2):$form_man->showError("Update Failed! ",1);
  

  }
}

//hall update
if(isset($_POST['hall_submit'])){
  if(!$form_man->emptyField($_POST['hall'])){ 
    $hall=$form_man->cleanString($_POST['hall']);
    $results=$query_guy->update_sellers("SELLER_HALL",$hall,$id);

    $record=$query_guy->find_by_id("SELLERS","SELLER_ID",$id);
    $seller_hall=$record['SELLER_HALL'];

    $log_error= $results?$form_man->showError("Your Hall Details Changed!",2):$form_man->showError("Update Failed! ",1);
  

  }
}


//mobile money update
if(isset($_POST['mobile_submit'])){
  if(!$form_man->emptyField($_POST['mobile_vendor'])&&!$form_man->emptyField($_POST['mobile_account'])){ 

    $mobile_vendor=$form_man->cleanString($_POST['mobile_vendor']);
    $mobile_account=$form_man->cleanString($_POST['mobile_account']);

    /*perform updates*/
    $query_guy->update_sellers("MOBILE_MONEY_VENDOR",$mobile_vendor,$id);
    $query_guy->update_sellers("MOBILE_MONEY_ACCOUNT",$mobile_account,$id);

    $record=$query_guy->find_by_id("SELLERS","SELLER_ID",$id);
    $seller_mm_vendor=$record['MOBILE_MONEY_VENDOR'];
    $seller_mm_account=$record['MOBILE_MONEY_ACCOUNT'];

    $log_error= $query_guy?$form_man->showError("Your Mobile Money Details Processed!",2):$form_man->showError("Update Failed! ",1);
  

  }
}


//bank update
if(isset($_POST['bank_submit'])){
  if(!$form_man->emptyField($_POST['bank_name'])&&
    !$form_man->emptyField($_POST['bank_account_name'])&&
    !$form_man->emptyField($_POST['bank_account_number'])){ 

    $bank=$form_man->cleanString($_POST['bank_name']);
    $bank_accname=$form_man->cleanString($_POST['bank_account_name']);
    $bank_accnumber=$form_man->cleanString($_POST['bank_account_number']);

    /*perform updates*/
    $query_guy->update_sellers("BANK_NAME",$bank,$id);
    $query_guy->update_sellers("BANK_ACCOUNT_NAME",$bank_accname,$id);
    $query_guy->update_sellers("BANK_ACCOUNT_NUMBER",$bank_accnumber,$id);

    $record=$query_guy->find_by_id("SELLERS","SELLER_ID",$id);
    $seller_bank_name=$record['BANK_NAME'];
    $seller_bankacc_name=$record['BANK_ACCOUNT_NAME'];
    $seller_bankacc_number=$record['BANK_ACCOUNT_NUMBER'];

    $log_error= $query_guy?$form_man->showError("Bank Account Details Successfully Processed!",2):$form_man->showError("Update Failed! ",1);
  
  

  }
}


//username update
if(isset($_POST['username_submit'])){
  if(!$form_man->emptyField($_POST['new_username'])){ 
    $username=$form_man->cleanString($_POST['new_username']);
    $results=$query_guy->update_sellers("SELLER_USERNAME",$username,$id);

    //update in buyers table too
    $buyer_query=mysqli_query(DB_Connection::$connection,"UPDATE BUYERS SET BUYER_USERNAME='{$username}' WHERE BUYER_PHONE='{$seller_phone}'");

    $record=$query_guy->find_by_id("SELLERS","SELLER_ID",$id);
    $seller_username=$record['SELLER_USERNAME'];

    $log_error= $results?$form_man->showError("Username Changed!",2):$form_man->showError("Update Failed! Username Exists",1);
  

  }
}


//password update
if(isset($_POST['password_submit'])){
  if(!$form_man->emptyField($_POST['new_password'])){ 

    $password=$form_man->cleanString($_POST['new_password']);
    //hash new password
    $new_password=password_hash($password,PASSWORD_BCRYPT,['cost'=>11]);

    $results=$query_guy->update_sellers("SELLER_PASSWORD",$new_password,$id);

    //update in buyers table too
    $buyer_query=mysqli_query(DB_Connection::$connection,"UPDATE BUYERS SET BUYER_PASSWORD='{$new_password}' WHERE BUYER_PHONE='{$seller_phone}'");

    $log_error= $results?$form_man->showError("Password Changed!",2):$form_man->showError("Update Failed!",1);
  

  }
}

//about update
if(isset($_POST['about_submit'])){
  if(!$form_man->emptyField($_POST['about'])){ 

    $about=$form_man->cleanString($_POST['about']);

    $results=$query_guy->update_sellers("SELLER_ABOUT",$about,$id);

    $record=$query_guy->find_by_id("SELLERS","SELLER_ID",$id);
    $seller_about=$record['SELLER_ABOUT'];

    $log_error= $results?$form_man->showError("You Have Successfully Written Your About Profile !",2):$form_man->showError("Update Failed!",1);
  

  }
}


//email update
if(isset($_POST['email_submit'])){
  if(!$form_man->emptyField($_POST['new_email'])){ 
    $email=$form_man->cleanString($_POST['new_email']);
    $results=$query_guy->update_sellers("SELLER_EMAIL",$email,$id);

    //update in buyers table too
    $buyer_query=mysqli_query(DB_Connection::$connection,"UPDATE BUYERS SET BUYER_EMAIL='{$email}' WHERE BUYER_PHONE='{$seller_phone}'");

    $record=$query_guy->find_by_id("SELLERS","SELLER_ID",$id);
    $seller_email=$record['SELLER_EMAIL'];

    $log_error= $results?$form_man->showError("Successful Email Update!",2):$form_man->showError("Update Failed! ",1);
  

  }
}




?>