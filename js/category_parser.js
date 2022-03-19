var quick_search;
var quick_mall_search;
$(document).ready(function(){


  /*for index main page*/
 
   $("#main_categories").change(function(){
       
          var chosen_one=$(this);
          if($(this[this.selectedIndex]).val()!="default"){

                  //$(".form-legend").append('<i class="fa fa-spinner fa-spin" style="color:red;"></i>');

                  var category_id=$(this[this.selectedIndex]).val();

                  $.post("inc/category_parser.php",{category_id:category_id},function(data){
                      
                    $("#specific_item").html('<option value="default">Choose The Specific Item</option>'+data);
                      
                  });


          }//end first if

      });

   /*small version*/

   $("#main_categories_small").change(function(){
       
          var chosen_one=$(this);
          if($(this[this.selectedIndex]).val()!="default"){

                  //$(".form-legend").append('<i class="fa fa-spinner fa-spin" style="color:red;"></i>');

                  var category_id=$(this[this.selectedIndex]).val();

                  $.post("inc/category_parser.php",{category_id:category_id},function(data){
                      
                    $("#specific_item_small").html('<option value="default">Choose The Specific Item</option>'+data);
                      
                  });


          }//end first if

      });


   /*ajax search for items*/
   quick_search=function(a){
      
      //a-seller id
      var search_item=$("#quick_item_search").val();
      var seller_id=a;

         
            $(".actual-list").html("<img src='images/loading.gif' class='img img-responsive loading'/>");

            /*if empty, bring all items*/
            if(search_item==""){

               $.post("inc/category_parser.php",{seller_id_empty:seller_id},function(data){
                      
                    $(".actual-list").html(data);
                      
                  }).success(function(){ $(".item-details").hide();});//call the hide funtion after success ajax request

           }
            else{
                    $.post("inc/category_parser.php",{search_item:search_item,seller_id:seller_id},function(data){
                      
                      $(".actual-list").html(data);
                      
                  }).success(function(){ $(".item-details").hide();});
             }/*else, bring all items*/

   
    };

    /************************************************quick mall search*********************************************************/
       /*ajax search for items*/
   quick_mall_search=function(a){
      
      //a-seller id
      var search_item=$("#quick_item_search").val();
      var vstr=a;
 
          $(".autosuggest").attr("style","display:block;").html("Searching...");
         
          //$(".dynamic-list").html("<img src='images/loading.gif' class='img img-responsive loading'/>");

           
            /*if empty, bring all items*/
            if(search_item==""){

                        $(".autosuggest").attr("style","display:none;");
           }
            else{
                    $.post("inc/category_parser.php",{search_item2:search_item,vstr_id:vstr},function(data){
                      
                     // $(".dynamic-list").html(data);
                     $(".autosuggest").html(data);
                      
                  });
             }/*else, bring all items*/

   
     };

   });



