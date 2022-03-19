<?php

//call_line update
if(isset($_POST['call_line_submit'])){
  if(!$form_man->emptyField($_POST['call_line'])){ 
    $call_line=$form_man->cleanString($_POST['call_line']);
    $results=$query_guy->update_affiliates("AFFILIATE_PHONE",$call_line,$id);

    //update in buyers table too
    $buyer_query=mysqli_query(DB_Connection::$connection,"UPDATE BUYERS SET BUYER_PHONE='{$call_line}' WHERE BUYER_PHONE='{$affiliate_phone}'");

    $record=$query_guy->find_by_id("AFFILIATES","AFFILIATE_ID",$id);
    $affiliate_phone=$record['AFFILIATE_PHONE'];

    $log_error= $results?$form_man->showError("Successful Call Line Update!",2):$form_man->showError("Update Failed! ",1);
  

  }
}

//whatsapp line update
if(isset($_POST['whatsapp_submit'])){
  if(!$form_man->emptyField($_POST['whatsapp'])){ 
    $whatsapp=$form_man->cleanString($_POST['whatsapp']);
    $results=$query_guy->update_affiliates("AFFILIATE_WHATSAPP",$whatsapp,$id);

    $record=$query_guy->find_by_id("AFFILIATES","AFFILIATE_ID",$id);
    $affiliate_whatsapp=$record['AFFILIATE_WHATSAPP'];

    $log_error= $results?$form_man->showError("Whatsapp Line Updated!",2):$form_man->showError("Update Failed! ",1);
  

  }
}

//hall update
if(isset($_POST['hall_submit'])){
  if(!$form_man->emptyField($_POST['hall'])){ 
    $hall=$form_man->cleanString($_POST['hall']);
    $results=$query_guy->update_affiliates("AFFILIATE_HALL",$hall,$id);

    $record=$query_guy->find_by_id("AFFILIATES","AFFILIATE_ID",$id);
    $affiliate_hall=$record['AFFILIATE_HALL'];

    $log_error= $results?$form_man->showError("Your Hall Details Changed!",2):$form_man->showError("Update Failed! ",1);
  

  }
}


//mobile money update
if(isset($_POST['mobile_submit'])){
  if(!$form_man->emptyField($_POST['mobile_vendor'])&&!$form_man->emptyField($_POST['mobile_account'])){ 

    $mobile_vendor=$form_man->cleanString($_POST['mobile_vendor']);
    $mobile_account=$form_man->cleanString($_POST['mobile_account']);

    /*perform updates*/
    $query_guy->update_affiliates("AFFIL_MOBILE_MONEY_VENDOR",$mobile_vendor,$id);
    $query_guy->update_affiliates("AFFIL_MOBILE_MONEY_ACCOUNT",$mobile_account,$id);

    $record=$query_guy->find_by_id("AFFILIATES","AFFILIATE_ID",$id);
    $affiliate_mm_vendor=$record['AFFIL_MOBILE_MONEY_VENDOR'];
    $affiliate_mm_account=$record['AFFIL_MOBILE_MONEY_ACCOUNT'];

    $log_error= $query_guy?$form_man->showError("Your Mobile Money Details Processed!",2):$form_man->showError("Update Failed! ",1);
  

  }
}



//username update
if(isset($_POST['username_submit'])){
  if(!$form_man->emptyField($_POST['new_username'])){ 
    $username=$form_man->cleanString($_POST['new_username']);
    $results=$query_guy->update_affiliates("AFFILIATE_USERNAME",$username,$id);

    //update in buyers table too
    $buyer_query=mysqli_query(DB_Connection::$connection,"UPDATE BUYERS SET BUYER_USERNAME='{$username}' WHERE BUYER_PHONE='{$affiliate_phone}'");

    $record=$query_guy->find_by_id("AFFILIATES","AFFILIATE_ID",$id);
    $affiliate_username=$record['AFFILIATE_USERNAME'];

    $log_error= $results?$form_man->showError("Username Changed!",2):$form_man->showError("Update Failed! Username Exists",1);
  

  }
}


//password update
if(isset($_POST['password_submit'])){
  if(!$form_man->emptyField($_POST['new_password'])){ 

    $password=$form_man->cleanString($_POST['new_password']);
    //hash new password
    $new_password=password_hash($password,PASSWORD_BCRYPT,['cost'=>11]);

    $results=$query_guy->update_affiliates("AFFILIATE_PASSWORD",$new_password,$id);

    //update in buyers table too
    $buyer_query=mysqli_query(DB_Connection::$connection,"UPDATE BUYERS SET BUYER_PASSWORD='{$new_password}' WHERE BUYER_PHONE='{$affiliate_phone}'");

    $log_error= $results?$form_man->showError("Password Changed!",2):$form_man->showError("Update Failed!",1);
  

  }
}



//email update
if(isset($_POST['email_submit'])){
  if(!$form_man->emptyField($_POST['new_email'])){ 
    $email=$form_man->cleanString($_POST['new_email']);
    $results=$query_guy->update_affiliates("AFFILIATE_EMAIL",$email,$id);

    //update in buyers table too
    $buyer_query=mysqli_query(DB_Connection::$connection,"UPDATE BUYERS SET BUYER_EMAIL='{$email}' WHERE BUYER_PHONE='{$affiliate_phone}'");

    $record=$query_guy->find_by_id("AFFILIATES","AFFILIATE_ID",$id);
    $affiliate_email=$record['AFFILIATE_EMAIL'];

    $log_error= $results?$form_man->showError("Successful Email Update!",2):$form_man->showError("Update Failed! ",1);
  

  }
}




?>