<?php
    //pagination links code
if($pagination->total_pages()>1){

        //previous link buttons
        if($pagination->has_previous_page()){
          $p_links.='<li><a href="mall?page='.$pagination->previous_page().'">Previous &laquo;</a></li>';
        }

        for($i=1;$i<=$pagination->total_pages();$i++){

              if($i==$page){ $p_links.='<li ><a class="p_selected" href="mall?page='.$i.'">'.$i.'</a></li>';}
              else{$p_links.='<li ><a href="mall?page='.$i.'">'.$i.'</a></li>';}
        }    

        //next links buttons
        if($pagination->has_next_page()){
          $p_links.='<li><a href="mall?page='.$pagination->next_page().'">Next &raquo;</a></li>';
        }                   

}//end main if

?>