<?php

include_once "vars.php";

$q_quiz = $_POST['qid'];

$quiz_path = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$quiz_path = str_replace("db_con.php","",$quiz_path);

$datei = "data/".$q_quiz."/fragen.php";

$ask_id = $_POST['ask_id'];
$answer = $_POST['answer'];
$answersok = $_POST['answersok'];
$answersfalse = $_POST['answersfalse'];

if($ask_id=="1")
  $startzeit = time();
else
  $startzeit = $_POST['dur'];

$headline = get_record($datei,0);

if($ask_id>1) {
  $last_rec = get_record($datei,($ask_id-1));
  if($answer==$last_rec[5])
    $korrekt = true;
  else
    $korrekt = false;
}

$rows = $headline[6];
$check = utf8_encode($headline[8]);
$themenbild = $headline[7];

if($ask_id>1) {
  if($korrekt)
    $answersok .= ($ask_id-1).",";
  else {
    if(($answer=="") || ($answer=="undefined"))
      $answer = "-";
    $answersfalse .= ($ask_id-1).":".$answer.",";
  }

}

$akt_rec = get_record($datei,$ask_id);

$quest_templ = str_replace("%q_act%",$ask_id,$quest_templ);
$quest_templ = str_replace("%q_total%",$rows,$quest_templ);

echo "&db_themenbild=".$quiz_path."data/".$q_quiz."/".$themenbild."&";
echo "&db_headline=".utf8_encode($headline[1])."&";
echo "&db_question=".utf8_encode($akt_rec[1])."&";
echo "&db_questcnt=".$quest_templ."&";
echo "&db_questcheck=".($akt_rec[8])."&";
echo "&db_ans1=".utf8_encode($akt_rec[2])."&";
echo "&db_ans2=".utf8_encode($akt_rec[3])."&";
echo "&db_ans3=".utf8_encode($akt_rec[4])."&";
echo "&answersok=".$answersok."&";
echo "&answersfalse=".$answersfalse."&";
echo "&db_mediatyp=".$akt_rec[6]."&";      //"b"=>Bild, "a"=>Audio/MP3
echo "&db_mediadat=".$quiz_path."data/".$q_quiz."/".$akt_rec[7]."&";
echo "&db_checksum=".$check."&";

if($rows>($ask_id-1)) {
  echo "&end_quiz=no&";
  echo "&db_dur=".$startzeit."&";
}
else {
  $dauer = time()-$startzeit;
  $answersok = explode(",",trim($answersok));
  for ($i=0 ; $i<=count($answersok);$i++) {
    if (trim($answersok[$i])== "") {
      unset ( $answersok[$i] );
    }
  }

  $answersfalse = explode(",",trim($answersfalse));
  for ($i=0 ; $i<=count($answersfalse);$i++) {
    if (trim($answersfalse[$i])== "") {
      unset ( $answersfalse[$i] );
    }
  }

  $prozent = round(count($answersok)/($rows/100),0);

  $ausw_templ = str_replace("%ansok%",count($answersok),$ausw_templ);
  $ausw_templ = str_replace("%q_total%",$rows,$ausw_templ);
  if($show_ausw)
    $endtext = $ausw_templ;
  else
    $endtext = "";

  if($show_assess) {
    if($prozent<=$bad)
      $endtext .= $headline[2];
    else if(($prozent>$bad) && ($prozent<=$middle))
      $endtext .= $headline[3];
    else
      $endtext .= $headline[4];
  }

  echo "&end_quiz=yes&";
  echo "&endtext=".utf8_encode($endtext)."&";
  echo "&db_dur=".$dauer."&";
  echo "&ausw_url=".$ausw_url."&";
  echo "&ausw_file=".$ausw_file."&";
  echo "&ausw_target=".$ausw_target."&";

}

function get_record($datei,$recnum) {
  $handle = fopen ($datei,"r");
  $row = 0;
  while ( ($data = fgetcsv ($handle, 1500, ";")) !== FALSE ) {
    $akt_rec = Array();
    $akt_rec = $data;
    if($row==($recnum+1))
      break;
    $row++;
  } //while
  fclose ($handle);

  return $akt_rec;
}

?>