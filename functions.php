<?php
    define("DS",DIRECTORY_SEPARATOR);


    function gaby_timeago_detector($time_ago)
   {
    $time_ago = strtotime($time_ago);
    $cur_time   = time();
    $time_elapsed   = $cur_time - $time_ago;
    $seconds    = $time_elapsed ;
    $minutes    = round($time_elapsed / 60 );
    $hours      = round($time_elapsed / 3600);
    $days       = round($time_elapsed / 86400 );
    $weeks      = round($time_elapsed / 604800);
    $months     = round($time_elapsed / 2600640 );
    $years      = round($time_elapsed / 31207680 );
    
    // Seconds
    if($seconds <= 60){return "just now";}

    //Minutes
    else if($minutes <=60){
        if($minutes==1){
            return "1 minute ago";
        }
        else{
            return "$minutes minutes ago";
        }
    }
    //Hours
    else if($hours <=24){
        if($hours==1){
            return "1 hour ago";
        }else{
            return "$hours hrs ago";
        }
    }
    //Days
    else if($days <= 7){
        if($days==1){
            return "yesterday";
        }else{
            return "$days days ago";
        }
    }
    //Weeks
    else if($weeks <= 4.3){
        if($weeks==1){
            return "1 week ago";
        }else{
            return "$weeks weeks ago";
        }
    }
    //Months
    else if($months <=12){
        if($months==1){
            return "1 month ago";
        }else{
            return "$months months ago";
        }
    }
    //Years
    else{
        if($years==1){
            return "1 year ago";
        }else{
            return "$years years ago";
        }
    }
}

/*number of stars parser*/
function number_of_stars($number){
   
   $value=ceil($number);

   switch ($value) {
       case 1:
           return '<i style="color:gold;" class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i  class="fa fa-star"></i>';
           break;

       case 2:
           return '<i style="color:gold;" class="fa fa-star"></i><i style="color:gold;" class="fa fa-star"></i><i class="fa fa-star"></i><i  class="fa fa-star"></i><i  class="fa fa-star"></i>';
       break;

       case 3:
           return '<i style="color:gold;" class="fa fa-star"></i><i style="color:gold;" class="fa fa-star"></i><i style="color:gold;" class="fa fa-star"></i><i  class="fa fa-star"></i><i  class="fa fa-star"></i>';
       break;

       case 4:
           return '<i style="color:gold;" class="fa fa-star"></i><i style="color:gold;" class="fa fa-star"></i><i style="color:gold;" class="fa fa-star"></i><i style="color:gold;" class="fa fa-star"></i><i  class="fa fa-star"></i>';
       break;

       case 5:
           return '<i style="color:gold;" class="fa fa-star"></i><i style="color:gold;" class="fa fa-star"></i><i style="color:gold;" class="fa fa-star"></i><i style="color:gold;" class="fa fa-star"></i><i style="color:gold;" class="fa fa-star"></i>';
       break;
       
       default:
           return '<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i  class="fa fa-star"></i>';
           break;
   }

}


?>