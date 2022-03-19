<?php
    //pagination links code
if($pagination->total_pages()>1){

        //previous link buttons
        if($pagination->has_previous_page()){
          $p_links.='<li><a href="mall?redirect=true&&person=person&&cat=3&&items=94858hx59&&sub_cat='.$s_cat.'&&main_cat=&&subset=5&&subitem=6&&page='.$pagination->previous_page().'">Previous &laquo;</a></li>';
        }

        for($i=1;$i<=$pagination->total_pages();$i++){

              if($i==$page){ $p_links.='<li ><a class="p_selected" href="mall?redirect=true&&person=person&&cat=3&&items=94858hx59&&sub_cat='.$s_cat.'&&main_cat=&&subset=5&&subitem=6&&page='.$i.'">'.$i.'</a></li>';}
              else{$p_links.='<li ><a href="mall?redirect=true&&person=person&&cat=3&&items=94858hx59&&sub_cat='.$s_cat.'&&main_cat=&&subset=5&&subitem=6&&page='.$i.'">'.$i.'</a></li>';}
        }    

        //next links buttons
        if($pagination->has_next_page()){
          $p_links.='<li><a href="mall?redirect=true&&person=person&&cat=3&&items=94858hx59&&sub_cat='.$s_cat.'&&main_cat=&&subset=5&&subitem=6&&page='.$pagination->next_page().'">Next &raquo;</a></li>';
        }                   

}//end main if

?>