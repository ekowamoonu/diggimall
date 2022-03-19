var add;
var minus;
$(document).ready(function(){

   //var snd = new Audio("js/bag.mp3");

   /*ajax search for items*/
   add=function(a){

          /*a-bag id*/

          /*show loading gif*/
          $("."+a).attr("style","display:inline;");
          var bag_id=a;
          var total=$("#hidden_total_input").val();

                //make an ajax request
                $.post("inc/bag_edit.php",{bag_id:bag_id},function(data){

                        var feed_array=data.split(",");  
                    
                        $(".total"+bag_id).html("GhC "+feed_array[0]);
                        $(".totalor"+bag_id).html(feed_array[1]);

                        /*calculate overallll total using value from hidden input*/
                        //so i get the product price from the ajax request and add to the previous total value
                        var new_total=parseFloat(total)+parseFloat(feed_array[2]);
              
                        $(".overall_total").html("GhC "+new_total);

                        //change the value of the hidden input
                        $("#hidden_total_input").val(new_total);
                        
                        $("."+bag_id).attr("style","display:none;");
                        $(".bag-added-container").attr("style","height:75px");
                          

                        //hide the bag container after 5 seconds
                        setTimeout(function(){
                           $(".bag-added-container").attr("style","height:0");
                        },3000);
                        
                  });//call the hide funtion after success ajax request

           };

  /****************************************************************************************************************************/
  /*********************************************************************************************************************************/
     /*ajax search for items*/
   minus=function(a){

          /*a-bag id*/

          /*show loading gif*/
          $("."+a).attr("style","display:inline;");
          var bag_idm=a;
          var total=$("#hidden_total_input").val();

                //make an ajax request
                $.post("inc/bag_edit.php",{bag_idm:bag_idm},function(data){

                        var feed_array=data.split(",");  
                    
                        $(".total"+bag_idm).html("GhC "+feed_array[0]);
                        $(".totalor"+bag_idm).html(feed_array[1]);

                        /*calculate overallll total using value from hidden input*/
                        //so i get the product price from the ajax request and add to the previous total value
                        var new_total=parseFloat(total)-parseFloat(feed_array[2]);
              
                        $(".overall_total").html("GhC "+new_total);

                        //change the value of the hidden input
                        $("#hidden_total_input").val(new_total);
                        
                        $("."+bag_idm).attr("style","display:none;");
                        $(".bag-added-container").attr("style","height:75px");
                          

                        //hide the bag container after 5 seconds
                        setTimeout(function(){
                           $(".bag-added-container").attr("style","height:0");
                        },3000);
                        
                  });//call the hide funtion after success ajax request

           };

   });



