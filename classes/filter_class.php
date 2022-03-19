 <?php

      /*main filter class*/
     class Filter extends DB_Connection{
            

           //running the search filter
           public function run_mall_search($per_page,$offset){
              $by_sub_cat=mysqli_real_escape_string(parent::$connection,$_GET['sub_cat']);
              $by_main_cat=mysqli_real_escape_string(parent::$connection,$_GET['main_cat']);
            

              $query="SELECT * FROM PRODUCTS ";
              $conditions=array();

              if($by_sub_cat!=""&&$by_sub_cat!="default"&&is_numeric($by_sub_cat)){
                $conditions[]="SUB_CAT_ID=".$by_sub_cat;
              }
              if($by_main_cat!=""&&$by_main_cat!="default"&&is_numeric($by_main_cat)){
                $conditions[]="MAIN_CAT_ID=".$by_main_cat;
              }
        
              $sql=$query;

              if(count($conditions)>0){
                $sql.=" WHERE ".implode(' AND ',$conditions)." ORDER BY UPLOAD_DATE DESC LIMIT ".$per_page." OFFSET ".$offset;
              }

            
             $result=mysqli_query(parent::$connection,$sql);

            //echo $result?"setttt":"not settt".mysqli_error(parent::$connection);
            
            return $result;

           }//function ends here


                  //running the search filter
           public function run_accounts_order_search($id){

              $slr_id=$id;
              $by_main_cat=$_POST['main_cat'];
              $by_sub_cat=$_POST['sub_cat'];
              $by_narrowed_cat=$_POST['narrowed_cat'];
              $by_p_id=$_POST['p_name'];
              $by_year=$_POST['year'];
              $by_month=$_POST['month'];
              $by_date=$_POST['date'];
              $by_day_of_the_week=$_POST['day_of_week'];
              $by_b_name=$_POST['b_name'];
              $by_b_hall=$_POST['b_hall'];
              $by_status=$_POST['delivery_status'];

              $query="SELECT * FROM ORDERS WHERE SLR_ID=".$slr_id." AND ";
              $conditions=array();

              if($by_main_cat!="default"){
                $conditions[]="MC_ID=".$by_main_cat;
              }
              if($by_sub_cat!="default"){
                $conditions[]="SC_ID=".$by_sub_cat;
              }
              if($by_narrowed_cat!="default"){
                 $conditions[]="SS_ID=".$by_narrowed_cat;

              }
               if($by_p_id!="default"){
                 $conditions[]="PT_ID=".$by_p_id;
              }
              if($by_year!="default"){
                 $conditions[]="ORDER_YEAR='{$by_year}' ";
              }

              if($by_month!="default"){
                 $conditions[]="ORDER_MONTH='{$by_month}' ";
              }
              if($by_date!="default"){
                $conditions[]="ORDER_NUMBER_DATE='{$by_date}' ";
              }

               if($by_day_of_the_week!="default"){
                $conditions[]="ORDER_WEEK_DAY='{$by_day_of_the_week}' ";
              }

               if($by_b_name!="default"){
                $conditions[]="CUSTOMER_NAME='{$by_b_name}' ";
              }

              if($by_b_hall!="default"){
                $conditions[]="CUSTOMER_HALL='{$by_b_hall}' ";
              }

               if($by_status!="default"){
                $conditions[]="DELIVERY_STATUS='{$by_status}' ";
              }

              $sql=$query;

              if(count($conditions)>0){
                $sql.=implode(' AND ',$conditions)." ORDER BY ORDER_DATE_FULL DESC";
              }

            $result=mysqli_query(parent::$connection,$sql);

            //echo $result?"setttt":"not settt".mysqli_error(parent::$connection);
            
            return $result;

           }//function ends here




           //running the delivery search filter
           public function run_delivery_accounts_order_search(){

              $by_main_cat=$_POST['main_cat'];
              $by_sub_cat=$_POST['sub_cat'];
              $by_narrowed_cat=$_POST['narrowed_cat'];
              $by_year=$_POST['year'];
              $by_month=$_POST['month'];
              $by_date=$_POST['date'];
              $by_day_of_the_week=$_POST['day_of_week'];
              $by_s_id=$_POST['s_name'];
              $by_b_name=$_POST['b_name'];
              $by_b_hall=$_POST['b_hall'];
              $by_status=$_POST['delivery_status'];

              $query="SELECT * FROM ORDERS WHERE ";
              $conditions=array();

              if($by_main_cat!="default"){
                $conditions[]="MC_ID=".$by_main_cat;
              }
              if($by_sub_cat!="default"){
                $conditions[]="SC_ID=".$by_sub_cat;
              }
              if($by_narrowed_cat!="default"){
                 $conditions[]="SS_ID=".$by_narrowed_cat;
             }
              
              if($by_year!="default"){
                 $conditions[]="ORDER_YEAR='{$by_year}' ";
              }

              if($by_month!="default"){
                 $conditions[]="ORDER_MONTH='{$by_month}' ";
              }
              if($by_date!="default"){
                $conditions[]="ORDER_NUMBER_DATE='{$by_date}' ";
              }

               if($by_day_of_the_week!="default"){
                $conditions[]="ORDER_WEEK_DAY='{$by_day_of_the_week}' ";
              }

              if($by_s_id!="default"){
                $conditions[]="SLR_ID=".$by_s_id;
              }

               if($by_b_name!="default"){
                $conditions[]="CUSTOMER_NAME='{$by_b_name}' ";
              }

              if($by_b_hall!="default"){
                $conditions[]="CUSTOMER_HALL='{$by_b_hall}' ";
              }

              if($by_status!="default"){
                $conditions[]="DELIVERY_STATUS='{$by_status}' ";
              }


              $sql=$query;

              if(count($conditions)>0){
                $sql.=implode(' AND ',$conditions)." ORDER BY ORDER_DATE_FULL DESC";
              }

            $result=mysqli_query(parent::$connection,$sql);

            //echo $result?"setttt":"not settt".mysqli_error(parent::$connection);
            
            return $result;

           }//function ends here


            //running the delivery search filter
           public function run_admin_accounts_order_search(){

              $by_order_id=$_POST['order_id'];
              $by_main_cat=$_POST['main_cat'];
              $by_sub_cat=$_POST['sub_cat'];
              $by_narrowed_cat=$_POST['narrowed_cat'];
              $by_year=$_POST['year'];
              $by_month=$_POST['month'];
              $by_date=$_POST['date'];
              $by_day_of_the_week=$_POST['day_of_week'];
              $by_s_id=$_POST['s_name'];
              $by_b_name=$_POST['b_name'];
              $by_b_hall=$_POST['b_hall'];
              $by_status=$_POST['delivery_status'];

              $query="SELECT * FROM ORDERS WHERE ";
              $conditions=array();

              if($by_order_id!=0){
                $conditions[]="ORDER_ID=".$by_order_id;
              }

              if($by_main_cat!="default"){
                $conditions[]="MC_ID=".$by_main_cat;
              }
              if($by_sub_cat!="default"){
                $conditions[]="SC_ID=".$by_sub_cat;
              }
              if($by_narrowed_cat!="default"){
                 $conditions[]="SS_ID=".$by_narrowed_cat;
             }
              
              if($by_year!="default"){
                 $conditions[]="ORDER_YEAR='{$by_year}' ";
              }

              if($by_month!="default"){
                 $conditions[]="ORDER_MONTH='{$by_month}' ";
              }
              if($by_date!="default"){
                $conditions[]="ORDER_NUMBER_DATE='{$by_date}' ";
              }

               if($by_day_of_the_week!="default"){
                $conditions[]="ORDER_WEEK_DAY='{$by_day_of_the_week}' ";
              }

              if($by_s_id!="default"){
                $conditions[]="SLR_ID=".$by_s_id;
              }

               if($by_b_name!="default"){
                $conditions[]="CUSTOMER_NAME='{$by_b_name}' ";
              }

              if($by_b_hall!="default"){
                $conditions[]="CUSTOMER_HALL='{$by_b_hall}' ";
              }

               if($by_status!="default"){
                $conditions[]="DELIVERY_STATUS='{$by_status}' ";
              }

              $sql=$query;

              if(count($conditions)>0){
                $sql.=implode(' AND ',$conditions)." ORDER BY ORDER_DATE_FULL DESC";
              }

            $result=mysqli_query(parent::$connection,$sql);

            //echo $result?"setttt":"not settt".mysqli_error(parent::$connection);
            
            return $result;

           }//function ends here



                  //running the search filter
           public function run_affiliate_accounts_order_search($diggi_id){

              $dg_id=$diggi_id;
              $by_main_cat=$_POST['main_cat'];
              $by_sub_cat=$_POST['sub_cat'];
              $by_narrowed_cat=$_POST['narrowed_cat'];
              $by_year=$_POST['year'];
              $by_month=$_POST['month'];
              $by_date=$_POST['date'];
              $by_day_of_the_week=$_POST['day_of_week'];
              $by_b_name=$_POST['b_name'];
              $by_b_hall=$_POST['b_hall'];
              $by_status=$_POST['delivery_status'];

              $query="SELECT * FROM ORDERS WHERE BUYER_REFEROR_ID='{$dg_id}' AND ";
              $conditions=array();

              if($by_main_cat!="default"){
                $conditions[]="MC_ID=".$by_main_cat;
              }
              if($by_sub_cat!="default"){
                $conditions[]="SC_ID=".$by_sub_cat;
              }
              if($by_narrowed_cat!="default"){
                 $conditions[]="SS_ID=".$by_narrowed_cat;

              }
              if($by_year!="default"){
                 $conditions[]="ORDER_YEAR='{$by_year}' ";
              }

              if($by_month!="default"){
                 $conditions[]="ORDER_MONTH='{$by_month}' ";
              }
              if($by_date!="default"){
                $conditions[]="ORDER_NUMBER_DATE='{$by_date}' ";
              }

               if($by_day_of_the_week!="default"){
                $conditions[]="ORDER_WEEK_DAY='{$by_day_of_the_week}' ";
              }

               if($by_b_name!="default"){
                $conditions[]="CUSTOMER_NAME='{$by_b_name}' ";
              }

              if($by_b_hall!="default"){
                $conditions[]="CUSTOMER_HALL='{$by_b_hall}' ";
              }

               if($by_status!="default"){
                $conditions[]="DELIVERY_STATUS='{$by_status}' ";
              }

              $sql=$query;

              if(count($conditions)>0){
                $sql.=implode(' AND ',$conditions)." ORDER BY ORDER_DATE_FULL DESC";
              }

            $result=mysqli_query(parent::$connection,$sql);

            //echo $result?"setttt":"not settt".mysqli_error(parent::$connection);
            
            return $result;

           }//function ends here


      //running the night market search filter
           public function run_night_accounts_order_search(){

              $by_b_name=$_POST['b_name'];
              $by_b_hall=$_POST['b_hall'];
              $by_status=$_POST['delivery_status'];

              $query="SELECT * FROM ORDERS WHERE SC_ID=33 AND ";
              $conditions=array();


               if($by_b_name!="default"){
                $conditions[]="CUSTOMER_NAME='{$by_b_name}' ";
              }

              if($by_b_hall!="default"){
                $conditions[]="CUSTOMER_HALL='{$by_b_hall}' ";
              }

              if($by_status!="default"){
                $conditions[]="DELIVERY_STATUS='{$by_status}' ";
              }


              $sql=$query;

              if(count($conditions)>0){
                $sql.=implode(' AND ',$conditions)." ORDER BY ORDER_DATE_FULL DESC";
              }

            $result=mysqli_query(parent::$connection,$sql);

            //echo $result?"setttt":"not settt".mysqli_error(parent::$connection);
            
            return $result;

           }//function ends here


           //running the delivery search filter
           public function run_hungry_accounts_order_search(){

              $by_year=$_POST['year'];
              $by_month=$_POST['month'];
              $by_date=$_POST['date'];
              $by_day_of_the_week=$_POST['day_of_week'];
              $by_b_name=$_POST['b_name'];
              $by_b_hall=$_POST['b_hall'];
              $by_status=$_POST['delivery_status'];

              $query="SELECT * FROM HUNGRY_ORDERS WHERE ";
              $conditions=array();

              
              if($by_year!="default"){
                 $conditions[]="HUNGRY_ORDER_YEAR='{$by_year}' ";
              }

              if($by_month!="default"){
                 $conditions[]="HUNGRY_ORDER_MONTH='{$by_month}' ";
              }
              if($by_date!="default"){
                $conditions[]="HUNGRY_ORDER_NUMBER_DATE='{$by_date}' ";
              }

               if($by_day_of_the_week!="default"){
                $conditions[]="HUNGRY_ORDER_WEEK_DAY='{$by_day_of_the_week}' ";
              }

               if($by_b_name!="default"){
                $conditions[]="HUNGRY_CUSTOMER_NAME='{$by_b_name}' ";
              }

              if($by_b_hall!="default"){
                $conditions[]="HUNGRY_CUSTOMER_HALL='{$by_b_hall}' ";
              }

               if($by_status!="default"){
                $conditions[]="DELIVERY_STATUS='{$by_status}' ";
              }

              $sql=$query;

              if(count($conditions)>0){
                $sql.=implode(' AND ',$conditions)." ORDER BY FULL_ORDER_DATE DESC";
              }

            $result=mysqli_query(parent::$connection,$sql);

            //echo $result?"setttt":"not settt".mysqli_error(parent::$connection);
            
            return $result;

           }//function ends here

           
        

     }


     ?>