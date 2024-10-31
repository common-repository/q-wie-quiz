<?php

include_once "vars.php";

$global_infos = Array();
$user_infos = Array();
$akt_quest = 0;
$_GET['akt_quest'] = 0;

$user_infos["name"] = utf8_decode($_POST['username']);
$global_infos["zusatztext"] = utf8_decode($_POST['zusatztext']);

$q_quiz = $_POST['qid'];
$answers_ok = $_POST['ok'];
$answers_no = $_POST['no'];
$dauer = $_POST['dur'];
if($_POST['q_as_edu']=="true")
  $is_edu = true;
else
  $is_edu = false;

$quiz_path = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$quiz_path = str_replace("ausw.php","",$quiz_path);

$datei = $qpfad."data/".$q_quiz."/fragen.php";
define("QDATEI",$datei);


$answers_ok = trim(str_replace(","," ",$answers_ok));
$answers_ok = explode(" ",$answers_ok);

$answers_no = trim(str_replace(","," ",$answers_no));
$answers_no = explode(" ",$answers_no);

$answers = Array();


$ok_count=0;
$no_count=0;

$ok_list = "";
$no_list = "";

foreach ($answers_ok as $ok) {
  if($ok>0) {
    $answers[$ok] = 0;
    $ok_count = count($answers_ok);
    $ok_list .= "$ok ";
  }
  else
    $ok_count = 0;
}
$ok_list = trim($ok_list);
$ok_list = str_replace(" ",", ",$ok_list);

foreach ($answers_no as $no) {
  $no_elem = explode(":",$no);
  if($no_elem[0]>0) {
    $answers[$no_elem[0]] = $no_elem[1];
    $no_count = count($answers_no);
    $no_list .= $no_elem[0]." ";
  }
}
$no_list = trim($no_list);
$no_list = str_replace(" ",", ",$no_list);

$global_dat = get_record($datei,0);
$_GET['global_dat'] = $global_dat;
$_GET['answers'] = $answers;

$rows = $global_dat[6];
$frage_zahl = $rows;
$prozent = $ok_count/($rows/100);

$global_infos["themenbild"] = $relpfad."data/".$q_quiz."/".$global_dat[7];
$global_infos["titel"] = str_replace("\r\n","<br>",$global_dat[1]);
$global_infos["titel"] = str_replace("\r","<br>",$global_infos["titel"]);
$global_infos["titel"] = str_replace("\n","<br>",$global_infos["titel"]);
$global_infos["fragen_zahl"] = $rows;


$user_infos["ok_count"] = $ok_count;
$user_infos["ok_list"] = $ok_list;
$user_infos["no_count"] = $ok_count;
$user_infos["no_list"] = $no_list;


define("QPROZENT",$prozent);
define("QBAD",$bad);
define("QMIDDLE",$middle);
define("QROWS",$rows);
define("QRELPFAD",$relpfad);

function get_prozent($genauigkeit = 0) {
  $prozent_anz = round(QPROZENT,$genauigkeit);
  echo $prozent_anz;
}


function get_assesment_txt() {
  if(QPROZENT<=QBAD)
    $endtext .= $_GET['global_dat'][2];
  else if((QPROZENT>QBAD) && (QPROZENT<=QMIDDLE))
    $endtext .= $_GET['global_dat'][3];
  else
    $endtext .= $_GET['global_dat'][4];

  echo $endtext;
}


if($dauer<60) {
  $minuten = 0;
  $sekunden = $dauer;
}
else {
  $minuten = floor($dauer/60);
  $sekunden = $dauer % 60;
}

$user_infos["minuten"] = $minuten;
$user_infos["sekunden"] = $sekunden;
$user_infos["dauer"] = $dauer;


function is_question() {
  if($_GET['akt_quest']<QROWS) {
    $_GET['akt_quest']++;
    return true;
  }
  else
    return false;

}

function get_akt_quest() {
  echo $_GET['akt_quest'];
}

function get_question() {
  $akt_rec = get_record(QDATEI,$_GET['akt_quest']);
  echo $akt_rec[1];
}

function get_answers($anztyp = "all", $stil = "br", $param = true) {
  $akt_rec = get_record(QDATEI,$_GET['akt_quest']);



  $akt_quest = $_GET['akt_quest'];
  $answers = $_GET['answers'];


  $code = "";

  if($stil=="br") {
    $zeile_start = "";
    $zeile_middle = " ";
    $zeile_end = "<br>\n";
  }
  else if($stil=="tabelle") {
    $zeile_start = "<tr><td align='left' valign='top'>";
    $zeile_middle = "</td><td align='left' valign='top'>";
    $zeile_end = "</td></tr>\n";
  }
  else if($stil=="liste") {
    $zeile_start = "<li>";
    $zeile_middle = " ";
    $zeile_end = "</li>\n";
  }

  if(($akt_rec[9]!="") && ($param))
    $erklaerung = "<div class='erklaerung'>".$akt_rec[9]."</div>";
  else
    $erklaerung = "";

  if(trim($anztyp)=="all") {
    for($k=1; $k<4; $k++) {
      if($k==$akt_rec[5]) {
        if($answers[$akt_quest]=="0") {
          $code .= "$zeile_start<img src='".QRELPFAD."global/checked_ok.png' border='0' width='14' height='15' alt='radio'>$zeile_middle<span class='antwort_ok antwort_userok'>".$akt_rec[($k+1)]."</span>$erklaerung$zeile_end";
        }
        else
          $code .= "$zeile_start<img src='".QRELPFAD."global/checked_no.png' border='0' width='14' height='15' alt='radio'>$zeile_middle<span class='antwort_ok'>".$akt_rec[($k+1)]."</span>$erklaerung$zeile_end";
      }
      else {
        if($answers[$akt_quest]==$k) {
          $code .= "$zeile_start<img src='".QRELPFAD."global/checked_ok.png' border='0' width='14' height='15' alt='radio'>$zeile_middle<span class='antwort_userno'>".$akt_rec[($k+1)]."</span>$zeile_end";
        }
        else
          $code .= "$zeile_start<img src='".QRELPFAD."global/checked_no.png' border='0' width='14' height='15' alt='radio'>$zeile_middle".$akt_rec[($k+1)]."$zeile_end";
      }
    }
  }
  else if(trim($anztyp)=="user_sel") {
    for($k=1; $k<4; $k++) {
      if($k==$akt_rec[5]) {
        if($answers[$akt_quest]=="0") {
          $code .= "$zeile_start<img src='".QRELPFAD."global/checked_ok.png' border='0' width='14' height='15' alt='radio'>$zeile_middle".$akt_rec[($k+1)]."$zeile_end";
        }
        else
          $code .= "$zeile_start<img src='".QRELPFAD."global/checked_no.png' border='0' width='14' height='15' alt='radio'>$zeile_middle".$akt_rec[($k+1)]."$zeile_end";
      }
      else {
        if($answers[$akt_quest]==$k) {
          $code .= "$zeile_start<img src='".QRELPFAD."global/checked_ok.png' border='0' width='14' height='15' alt='radio'>$zeile_middle".$akt_rec[($k+1)]."$zeile_end";
        }
        else
          $code .= "$zeile_start<img src='".QRELPFAD."global/checked_no.png' border='0' width='14' height='15' alt='radio'>$zeile_middle".$akt_rec[($k+1)]."$zeile_end";
      }
    }
  }
  else {
    for($k=1; $k<4; $k++) {
      if($k==$akt_rec[5]) {
        if($answers[$akt_quest]=="0") {
          $code .= "$zeile_start<img src='".QRELPFAD."global/checked_ok.png' border='0' width='14' height='15' alt='radio'>$zeile_middle<span class='antwort_ok antwort_userok'>".$akt_rec[($k+1)]."</span>$erklaerung$zeile_end";
        }
        else
          $code .= "$zeile_start<img src='".QRELPFAD."global/checked_no.png' border='0' width='14' height='15' alt='radio'>$zeile_middle<span class='antwort_ok'>".$akt_rec[($k+1)]."</span>$erklaerung$zeile_end";
      }
      else {
        if($answers[$akt_quest]==$k) {
          $code .= "$zeile_start<img src='".QRELPFAD."global/checked_ok.png' border='0' width='14' height='15' alt='radio'>$zeile_middle<span class='antwort_userno'>".$akt_rec[($k+1)]."</span>$zeile_end";
        }
        else
          $code .= "$zeile_start<img src='".QRELPFAD."global/checked_no.png' border='0' width='14' height='15' alt='radio'>$zeile_middle".$akt_rec[($k+1)]."$zeile_end";
      }
    }
  }

  echo $code;
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