<?php

    class UserProfile extends DB_Connection{


          
          public $name;
          public $hall;
          public $phone;
          public $whatsapp;
          public $email;
          public $room_number;
          public $referor_id;
          private $user_name;
          private $user_details;
          private $user_id;
         
        //constructor
          function __construct($customer_id){

          	  global $query_guy;//bring in the query guy object
               
              $this->user_id=$customer_id;

          	  $this->user_details=$query_guy->find_by_id("BUYERS","BUYER_ID",$customer_id);

          	  //initialise variables
          	  $this->name=$this->user_details['BUYER_NAME'];
          	  $this->hall=$this->user_details['BUYER_HALL'];
          	  $this->phone=$this->user_details['BUYER_PHONE'];
          	  $this->whatsapp=$this->user_details['BUYER_WHATSAPP'];
          	  $this->email=$this->user_details['BUYER_EMAIL'];
          	  $this->room_number=$this->user_details['BUYER_ROOM_NUMBER'];
          	  $this->referor_id=$this->user_details['REFEROR_ID'];

          }

        //username setter and getter
         public function get_username(){
         	return $this->user_name;
         } 

         public function set_username(){
            $this->user_name=$this->user_details['BUYER_USERNAME'];
         }

         //get wishlist
         public function retrieve_wishlist(){
            
            global $query_guy;
            $wishlist=$query_guy->find_by_col("WISHLIST","CUSTOMER_ID",$this->user_id);
             
            return $wishlist;
             
         }

        //function to run retrieve tickets
         public function retrieve_tickets(){
          $query="SELECT * FROM ORDERS WHERE MC_ID=12 AND DELIVERY_STATUS='confirmed' AND CUSTOMER_ID=".$this->user_id;
          $run_query=mysqli_query(parent::$connection,$query);

          return $run_query;/*WHERE MC_ID=12 AND*/
         }


         /****************************************ORDERS*****************************************************/
         //retrieve all customers orders and sort them according to the latest order
         public function retrieve_all_orders(){
             
               $all_orders=$this->retrieve_query();
               return $all_orders;    
         }

         //function to run retrieve orders query
         private function retrieve_query(){
         	$query="SELECT * FROM ORDERS WHERE CUSTOMER_ID=".$this->user_id;
         	$run_query=mysqli_query(parent::$connection,$query);

         	return $run_query;
         }

         //get all pending orders
         public function get_all_pending_orders(){
         	$query="SELECT * FROM ORDERS WHERE CUSTOMER_ID=".$this->user_id." AND DELIVERY_STATUS='pending' ORDER BY ORDER_DATE_FULL DESC";
         	$run_query=mysqli_query(parent::$connection,$query);

         	return $run_query;
         }

          //get all packaged orders
         public function get_all_packaged_orders(){
         	$query="SELECT * FROM ORDERS WHERE CUSTOMER_ID=".$this->user_id." AND DELIVERY_STATUS='packaged' ORDER BY ORDER_DATE_FULL DESC";
         	$run_query=mysqli_query(parent::$connection,$query);

         	return $run_query;
         }

           //get all delivered orders
         public function get_all_delivered_orders(){
         	$query="SELECT * FROM ORDERS WHERE CUSTOMER_ID=".$this->user_id." AND DELIVERY_STATUS='delivered' ORDER BY ORDER_DATE_FULL DESC";
         	$run_query=mysqli_query(parent::$connection,$query);

         	return $run_query;
         }

           //get all on route orders
         public function get_all_onroute_orders(){
          $query="SELECT * FROM ORDERS WHERE CUSTOMER_ID=".$this->user_id." AND DELIVERY_STATUS='on route' ORDER BY ORDER_DATE_FULL DESC";
          $run_query=mysqli_query(parent::$connection,$query);

          return $run_query;
         }

              //get all on route orders
         public function get_all_confirmed_orders(){
          $query="SELECT * FROM ORDERS WHERE CUSTOMER_ID=".$this->user_id." AND DELIVERY_STATUS='confirmed' ORDER BY ORDER_DATE_FULL DESC";
          $run_query=mysqli_query(parent::$connection,$query);

          return $run_query;
         }

         /*************************************************************************************************/

         //return all followed shops
         public function retrieve_all_followed_shops(){
             
             $all_shops=$this->retrieve_shops_query();
             return $all_shops;
         }

         private function  retrieve_shops_query(){
         	$query="SELECT * FROM FOLLOWED_SHOPS WHERE CSTMR_ID=".$this->user_id;
         	$run_query=mysqli_query(parent::$connection,$query);

         	return $run_query;
         }

          /**************************************************************************************************/
         //collecting all recently viewed items of user
         public function get_all_recently_viewed_items(){
         	$query="SELECT * FROM RECENTLY_VIEWED WHERE CUSTMR_ID=".$this->user_id." ORDER BY VIEWED_DATE DESC";
         	$run_query=mysqli_query(parent::$connection,$query);

         	return $run_query;
         }

         /**********watermarking a ticket*******************/
        /* public function watermark_ticket($SourceFile, $WaterMarkText, $DestinationFile){
              
               //get image size
               list($width, $height) = getimagesize($SourceFile);

               $image_p = imagecreatetruecolor($width, $height);
               $image = imagecreatefromjpeg($SourceFile);
               imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width, $height); 

               $black = imagecolorallocate($image_p, 0, 0, 0);
               $font = 'arial.ttf';
               $font_size = 120; 
               imagettftext($image_p, $font_size, 0, 60, 150, $black, $font, $WaterMarkText);
               if ($DestinationFile<>'') {
                  imagejpeg ($image_p, $DestinationFile, 100); 
               } else {
                  header('Content-Type: image/jpeg');
                  imagejpeg($image_p, null, 100);
               };
               imagedestroy($image); 
               imagedestroy($image_p); 
         }*/

         public function generateWatermark($imageLocation,$saveLocation,$orderId,$ticketCode,$font) {  
                //$original = imagecreatefromjpeg($imageLocation);
                $data = getimagesize($imageLocation);
                $width = $data[0];
                $height = $data[1];
                $original = imagecreatefromstring(file_get_contents($imageLocation));
                $outputImage = imagecreatetruecolor($width, $height+220);
                
                // Allocate A Color For The Text
                $white = imagecolorallocate($outputImage, 255, 255,255);
                $black = imagecolorallocate($outputImage, 0, 150, 0);
                
                // Set Path to Font File
                $font_path =  $font;//'font.TTF';
                
                
                imagefilltoborder($outputImage, 0, 0, $white, $black);
                 
                imagecopymerge($outputImage,$original,0,0,0,0, $width, $height,100);
                
                // Set Text to Be Printed On Image
                $text = "Ticket ID:  ".$orderId."\nTicket code:  ".$ticketCode;
                
                // Print Text On Image
                imagettftext($outputImage, 35, 0, $width/25, $height+100, $white, $font_path, $text);
                  
                imagejpeg($outputImage, $saveLocation);
                
                imagedestroy($original);
                imagedestroy($outputImage);
                 
               }

    }//end class

?>