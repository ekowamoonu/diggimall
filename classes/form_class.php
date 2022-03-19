 <?php 

      class FormDealer extends DB_Connection{

          //check whether field is empty
     	  public function emptyField($value){
			  	    if(empty($value)||$value==""||$value=="default"){return true;}
			       else{return false;}
               }

          //clean form input
          public function cleanString($val){
				     $cleaned= mysqli_real_escape_string(parent::$connection,$val);
				     $final=strip_tags($cleaned);

				     return $final;
               }


                  
            //check files with illegal extensions
           public function illegalExt($sample){

              $ext= pathinfo($sample, PATHINFO_EXTENSION);

              if($ext=="jpg"||$ext=="jpeg"||$ext=="png"||$ext=="JPEG"||$ext=="PNG"||$ext=="JPG"){return false;}
              else{return true;}
          }


         public function showError($message,$status){
             
                 $msg="";
                 if($status==1){
                   
                     $msg="<div class='container error-container'>
                          <div class='alert alert-dismissible admission-alert alert-danger' role='alert'>
                                <button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'><i class='fa fa-times-circle'></i></span><span class='sr-only'>Close</span></button><br/>
                                 ".$message."
                             </div>
                        </div>";
                 }

                 else{
                   
                     $msg="<div class='container error-container'>
                          <div class='alert alert-dismissible admission-alert alert-success' role='alert'>
                                <button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'><i class='fa fa-times-circle'></i></span><span class='sr-only'>Close</span></button><br/>
                                 ".$message."
                             </div>
                        </div>";
                 }
                 return $msg;

         }
            
     }
   
 ?>