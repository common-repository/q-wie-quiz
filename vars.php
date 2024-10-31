<?php 

 $bad = 30;     //per cent
 $middle = 70;  //per cent

 $quest_templ = "Frage %q_act% / %q_total%";   //%q_act%: act. question no., %q_total%: total questions
 $show_ausw = true;      //true: shows parsed string of , false: shows nothing
 $show_assess = true;    //true: shows text of assessment, false: shows nothing
 $ausw_templ = "Sie haben %ansok% von %q_total% Fragen richtig beantwortet.\n\n";   //%ansok%: count of right answers, %q_total%: total questions

 $ausw_url = "";
 $ausw_file = "";
 $ausw_target = "_self";

?>