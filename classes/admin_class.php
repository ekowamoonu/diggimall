 <?php

      /*admin dashboard querying class*/
     class AdminAction extends DB_Connection{
          
          /*site statistics*/

          //get number of confirmed sellers
         public function count_confirmed_sellers(){
              $query="SELECT COUNT(*) FROM SELLERS WHERE SELLER_ACCESS=1";
              $result=mysqli_query(parent::$connection,$query);
              $set=mysqli_fetch_array($result);

              return array_shift($set);
          
           }

        //get number of unconfirmed sellers
        public function count_unconfirmed_sellers(){
            
              $query="SELECT COUNT(*) FROM SELLERS WHERE SELLER_ACCESS=0";
              $result=mysqli_query(parent::$connection,$query);
              $set=mysqli_fetch_array($result);

              return array_shift($set);

        }

      //get number of orders today  
      public function count_orders_today($year='',$month='',$date=''){
            
      $query="SELECT COUNT(*) FROM ORDERS WHERE ORDER_YEAR='{$year}' AND ORDER_MONTH='{$month}' AND ORDER_NUMBER_DATE='{$date}' ";
          $result=mysqli_query(parent::$connection,$query);
          $set=mysqli_fetch_array($result);

           return array_shift($set);

        }
    

    }


     ?>