var mall_now;
$(document).ready(function(){

   var snd = new Audio("js/bag.mp3");

/**********************************************************************************************************************/
    ///*****************************mall purchases*******************************************/


   //mall now function
   mall_now=function(a,b,c){

        /*
            a-visitor id
            b-product id
            c-product price
        */

        /*show loading gif*/
        $(".loading"+b).attr("style","display:inline;");

        var visitor_id=a;
        var product_id=b;
        var product_price=c;

      

             //make an ajax request to add to bag_items
                $.post("inc/buynow.php",{mallvisitor_id:visitor_id,mallproduct_id:product_id,mallproduct_price:product_price},function(data){
                        
                snd.play(); 

                     
                      $(".loading"+b).attr("style","display:none;");
                      $(".bag-added-container").attr("style","display:block;");
                      snd.currentTime=0;

                      //alert(data);
                      
                      //hide the bag container after 5 seconds
                     /* setTimeout(function(){
                         $(".bag-added-container").attr("style","height:0");
                      },10000);*/
                        
                   });//call the hide funtion after success ajax request

              

      };

   });



