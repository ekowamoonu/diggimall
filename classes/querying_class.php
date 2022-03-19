 <?php

      /*main querying class*/
     class DataQuery extends DB_Connection{
          
            //find all returns the whole table record
         public function find_all($table,$per_page,$offset){//selects everything from table
            $query="SELECT * FROM ".$table." ORDER BY RAND() DESC LIMIT ".$per_page." OFFSET ".$offset;/*." ORDER BY MAIN_CATEGORY_NAME ASC";*/
            $results=mysqli_query(parent::$connection,$query);
               
            return $results;
         }

          //find all returns the whole table record
     	   public function find_all_main_categories($table){//selects everything from table
     	   	  $query="SELECT * FROM ".$table;/*." ORDER BY MAIN_CATEGORY_NAME ASC";*/
     	   	  $results=mysqli_query(parent::$connection,$query);
               
     	   	  return $results;
     	   }

         //get all list of sellers
          public function get_sellers(){//selects everything from table
            $query="SELECT * FROM SELLERS ORDER BY SELLER_NAME ASC";/*." ORDER BY MAIN_CATEGORY_NAME ASC";*/
            $results=mysqli_query(parent::$connection,$query);
               
            return $results;
         }

           //get all list of sellers
          public function get_affiliates(){//selects everything from table
            $query="SELECT * FROM AFFILIATES ORDER BY AFFILIATE_NAME ASC";/*." ORDER BY MAIN_CATEGORY_NAME ASC";*/
            $results=mysqli_query(parent::$connection,$query);
               
            return $results;
         }


             
          //find by id returns just one row as an associative array
     	   public function find_by_id($table,$column,$id){//selects everything from table by id
          if($id==""){

             $query="SELECT * FROM ".$table." WHERE ".$column."='null'";
             $results=mysqli_query(parent::$connection,$query);

          }else{

             $query="SELECT * FROM ".$table." WHERE ".$column."=".$id;
             $results=mysqli_query(parent::$connection,$query);

          }
     	   	
      
         /* if(!$results){echo mysqli_error(parent::$connection);}*/

     	   	$results_set=mysqli_fetch_assoc($results);

           return $results_set;
     	   }

         public function update_sellers($column,$value,$id){
              
              $query="UPDATE SELLERS SET ".$column."='{$value}' WHERE SELLER_ID=".$id;
              $results=mysqli_query(parent::$connection,$query);

              return $results?true:false;

        }


         public function update_affiliates($column,$value,$id){
              
              $query="UPDATE AFFILIATES SET ".$column."='{$value}' WHERE AFFILIATE_ID=".$id;
              $results=mysqli_query(parent::$connection,$query);

              return $results?true:false;

        }

         public function update_products($column,$value,$id){
              
              $query="UPDATE PRODUCTS SET ".$column."='{$value}' WHERE PRODUCT_ID=".$id;
              $results=mysqli_query(parent::$connection,$query);

              return $results?true:false;

           }

        public function update_product_image($value,$id,$size){
              
              if($size=="s"){
                  $query="UPDATE PRODUCT_SMALL_IMAGE SET SMALL_IMAGE_FILE='{$value}' WHERE PDS_ID=".$id;
                  $results=mysqli_query(parent::$connection,$query);

                   return $results?true:false;

              }else{

                   $query="UPDATE PRODUCT_LARGE_IMAGE SET LARGE_IMAGE_FILE='{$value}' WHERE PDL_ID=".$id;
                   $results=mysqli_query(parent::$connection,$query);

                   return $results?true:false;
               }
            

           }


          //find by col returns all records
          public function find_products_by_seller($id){//selects everything from table by id
          $query="SELECT * FROM PRODUCTS WHERE SEL_ID=".$id." ORDER BY PRODUCT_NAME";
          $results=mysqli_query(parent::$connection,$query);

           return $results;
         }

          //find sellers orders
          public function find_orders_by_seller($id){//selects everything from table by id
          $query="SELECT * FROM ORDERS WHERE SLR_ID=".$id." AND (DELIVERY_STATUS='confirmed' OR DELIVERY_STATUS='pending') ORDER BY ORDER_DATE_FULL DESC";
          $results=mysqli_query(parent::$connection,$query);

           return $results;
         }

          //find sellers orders
          public function find_orders_by_affiliate($id){//selects everything from table by id
          $query="SELECT * FROM ORDERS WHERE BUYER_REFEROR_ID='{$id}' ORDER BY ORDER_DATE_FULL DESC";
          $results=mysqli_query(parent::$connection,$query);
          
          echo mysqli_error(parent::$connection);
           return $results;
         }

          //find by col returns all records
          public function find_products_by_seller_and_key_word($id,$key){//selects everything from table by id
          $query="SELECT * FROM PRODUCTS WHERE SEL_ID=".$id." AND PRODUCT_NAME LIKE '%{$key}%' OR PRODUCT_CODE LIKE '%{$key}%' ORDER BY PRODUCT_NAME";
          $results=mysqli_query(parent::$connection,$query);

           return $results;
         }

           //find by col returns all records
          public function find_products_in_mall($key){//selects everything from table by id
          $query="SELECT * FROM PRODUCTS WHERE PRODUCT_NAME LIKE '%{$key}%' OR PRODUCT_DESCRIPTION LIKE '%{$key}%' ORDER BY PRODUCT_NAME";
          $results=mysqli_query(parent::$connection,$query);

           return $results;
         }


         //find by col returns all records
         public function find_by_col($table,$column,$id){//selects everything from table by id
          $query="SELECT * FROM ".$table." WHERE ".$column."=".$id;
          $results=mysqli_query(parent::$connection,$query);

           return $results;
         }

        
         //find by col returns all records
         public function find_by_paginating_col($table,$column,$id,$per_page,$offset){//selects everything from table by id
          $query="SELECT * FROM ".$table." WHERE ".$column."=".$id." LIMIT ".$per_page." OFFSET ".$offset;
          $results=mysqli_query(parent::$connection,$query);

           return $results;
         }


        //find by col_and sum returns a summation of a particular column
         public function find_by_col_and_sum($sum_variable,$as_variable,$table,$column,$id){//selects everything from table by id
          $query="SELECT SUM(".$sum_variable.") AS ".$as_variable." FROM ".$table." WHERE ".$column."=".$id;
          $results=mysqli_query(parent::$connection,$query);

           return $results;
         }

  
           //deleting from database
           public function delete_by_id($table,$column,$id){
                $query="DELETE FROM ".$table." WHERE ".$column."=".$id;
                $results=mysqli_query(parent::$connection,$query);

               return $results?true:false;

           }


           //count number of rows in table
           public function countNumber($table){
              $query="SELECT COUNT(*) FROM ".$table;
              $result=mysqli_query(parent::$connection,$query);
              $set=mysqli_fetch_array($result);

              return array_shift($set);
          
           }
 }


     ?>