var buy_now;
$(document).ready(function(){

   var snd = new Audio("js/bag.mp3");

   //buy now function
   buy_now=function(a,b,c){

        /*
            a-visitor id
            b-product id
            c-product price
        */

        /*show loading gif*/
        $(".loading").attr("style","display:inline;");

        var visitor_id=a;
        var product_id=b;
        var product_price=c;
        var quantity=$("#quantity").val();
        var requirements=$("#requirements").val();

        

        if(quantity==0||quantity==""||quantity<0){

            $(".loading").attr("style","display:none;");
            alert("Kindly Specify An Order Quantity");

        }else{

                 if((quantity!=0||quantity!="")&&requirements!=""){//if user enters some requirements
                     
                     //make an ajax request
                      $.post("inc/buynow.php",{visitor_id:visitor_id,product_id:product_id,product_price:product_price,quantity:quantity,requirements:requirements},function(data){
                        
                      snd.play(); 
                      $(".loading").attr("style","display:none;");
                      $(".bag-added-container").attr("style","display:block;");
                      snd.currentTime=0;

                      //change shopping item number display
                      $(".item_number").html(data);

                      //hide the bag container after 5 seconds
                     /* setTimeout(function(){
                         $(".bag-added-container").attr("style","top:-80px");
                      },10000);*/
                        
                    });//call the hide funtion after success ajax request



               }//end nested if
  /*********************************************************************************************************************************/             
               else if((quantity!=0||quantity!="")&&requirements==""){//if user specifies no requirements
                  //make an ajax request
                      $.post("inc/buynow.php",{visitor_id2:visitor_id,product_id2:product_id,product_price2:product_price,quantity2:quantity},function(data){
                        
                      snd.play(); 
                      $(".loading").attr("style","display:none;");
                      $(".bag-added-container").attr("style","display:block;");
                      snd.currentTime=0;

                      //change shopping item number display
                      $(".item_number").html(data);

                      //hide the bag container after 5 seconds
                     /* setTimeout(function(){
                         $(".bag-added-container").attr("style","display:block;");
                      },10000);*/
                        
                    });//call the hide funtion after success ajax request
               }//end nested else
        }//end main else

    };

   });



