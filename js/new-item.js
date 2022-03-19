$(document).ready(function(){


 
   //getting sub-categories from the main categories
   $("#main_category").change(function(){
       
          var chosen_one=$(this);
          if($(this[this.selectedIndex]).val()!="default"){

                  //$(".form-legend").append('<i class="fa fa-spinner fa-spin" style="color:red;"></i>');

                  var category_id=$(this[this.selectedIndex]).val();

                  $.post("inc/category_parser.php",{category_id:category_id},function(data){

                     chosen_one.next().next().html('<option value="default">Choose A Sub Category</option>'+data);
                      //$(".form-legend").html("Order Details");
                      
                  });


          }//end first if

   });

   //getting the subsets from the sub categories
      $("#sub_category").change(function(){
       
          var chosen_one=$(this);
          if($(this[this.selectedIndex]).val()!="default"){

                  //$(".form-legend").append('<i class="fa fa-spinner fa-spin" style="color:red;"></i>');

                  var sub_category_id=$(this[this.selectedIndex]).val();

                  $.post("inc/category_parser.php",{sub_category_id:sub_category_id},function(data){

                     chosen_one.next().next().html('<option value="default">You Should Narrow It Down</option>'+data);
                      //$(".form-legend").html("Order Details");
                      
                  });


          }//end first if

   });
    

  /*creating dynamic forms code*/
      var dynamic_form=$(".dynamic-form");
      var cloned_form=$(".details").clone(true,true);
      var count=2;

       $(".add-item").click(function(e){ //on add input button click
            
            e.preventDefault();
            var digit=count++;

            $(dynamic_form).append('<div class="form-group"><h3 class="form-legend">Item '+digit+' Details</h3><hr/></div>');
            //$(dynamic_form).append('<div class="form-group"><label class="col-lg-3 control-label">Item Category</label><div class="col-lg-9">');

            cloned_form.clone(true,true).appendTo(dynamic_form);


    });

});

