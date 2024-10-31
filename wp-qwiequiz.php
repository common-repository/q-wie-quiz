<?php
/*
Plugin Name: Q wie Quiz
Plugin URI: http://www.q-wie-quiz.de/
Description: Das wordpress Plugin f&uuml;r "Q wie Quiz"
Version: 0.1
Author: Stephan Gaertner
Author URI: http://www.stegasoft.de
*/

$table_style = "border:solid 1px #606060;border-collapse:collapse;padding:2px;";

$qwqversion = "0.1";


//============= INCLUDES ==========================================================
@include_once (dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR. "wp-config.php");
@include_once (dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR."wp-includes/wp-db.php");

$version = get_bloginfo('version');


define('QWQ_URLPATH', WP_CONTENT_URL.'/plugins/'.plugin_basename( dirname(__FILE__)) );
$qwq_plugin_dir = QWQ_URLPATH;


$qwq_options = get_option( "qwq_options" );


//============= Code für Admin-Kopf erzeugen ============================
function qwqjs2adminhead() {
  global $qwq_plugin_dir,$qwq_options;

  $jscript_includes = "\n";
  $jscript_includes .= "<style type='text/css'><!-- .fe_txt { border:solid 1px #5F5F5F; --></style>\n";
  $jscript_includes .= "<script language='JavaScript' src=\"$qwq_plugin_dir/global/jscolor/jscolor.js\" type=\"text/javascript\"></script>\n\n";

  echo $jscript_includes;
}
add_action('admin_head', 'qwqjs2adminhead');



//============= Code für Template-Kopf erzeugen ============================
function qwqjs2head() {
  global $qwq_plugin_dir,$qwq_options;

  $jscript_includes = "\n";
  $jscript_includes .= "<script language='JavaScript' src=\"$qwq_plugin_dir/global/q_embed.js\" type=\"text/javascript\"></script>\n";
  $jscript_includes .= "<link rel='stylesheet' href='$qwq_plugin_dir/global/styles.css' type='text/css' />\n\n";

  echo $jscript_includes;
}
add_action('wp_head', 'qwqjs2head');



//============= Plugin - Button einbauen =====================================
add_action('admin_menu', 'qwq_page');
function qwq_page() {
    add_submenu_page('plugins.php', __('Q wie Quiz'), __('Q wie Quiz'), 10, 'qwqadmin', 'qwq_options_page');
}



//============= Grund-Einstellungen bei Installation speichern ===============
if($qwq_options['qwq_page_id']<1)
  register_activation_hook(__FILE__, 'qwq_install');

function qwq_install() {
  global $qwq_options;

  //Seite anlegen, wenn noch nicht geschehen
  $qwq_page = array();
  $qwq_page['post_title'] = 'Q wie Quiz - Auswertung';
  $qwq_page['post_name'] = 'qwq-auswertung';
  $qwq_page['post_content'] = '[qwqout]';
  $qwq_page['post_status'] = 'publish';
  $qwq_page['post_author'] = 1;
  $qwq_page['post_type'] = 'page';
  $qwq_page['post_category'] = array(0);

  // Insert the post into the database
  $qwq_options['qwq_page_id'] = wp_insert_post( $qwq_page );
  update_option( "qwq_options", $qwq_options );

  $var_code = "<?php \r\n\r\n".
              " \$bad = 30;     //per cent\r\n".
              " \$middle = 70;  //per cent\r\n\r\n".
              " \$quest_templ = \"Frage %q_act% / %q_total%\";   //%q_act%: act. question no., %q_total%: total questions\r\n".
              " \$show_ausw = true;      //true: shows parsed string of $ausw_templ, false: shows nothing\r\n".
              " \$show_assess = true;    //true: shows text of assessment, false: shows nothing\r\n".
              " \$ausw_templ = \"Sie haben %ansok% von %q_total% Fragen richtig beantwortet.\\n\\n\";   //%ansok%: count of right answers, %q_total%: total questions\r\n\r\n".
              " \$ausw_url = \"".get_bloginfo("wpurl")."/\";\r\n".
              " \$ausw_file = \"index.php?page_id=".$qwq_options['qwq_page_id']."\";\r\n".
              " \$ausw_target = \"_self\";\r\n\r\n".
              "?>";

  $fp = fopen (dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR."wp-content/plugins/wp-qwiequiz/vars.php","w+");
  fwrite($fp,$var_code);
  fclose($fp);

}




//============= Tabellen/Optionen loeschen ===================================
if($qwq_options["deinstall"] == "yes")
  register_deactivation_hook(__FILE__, 'qwq_deinstall');
function qwq_deinstall() {
  global $wpdb,$qwq_options;

  if(wp_delete_post($qwq_options['qwq_page_id']) !== FALSE)
    delete_option('qwq_options');
}


//============ Platzhalter ersetzen =========================================
//------------ [qwiequiz] ----------------------------------------------
function qwq_get_params($atts) {
  global $qwq_options,$qwq_plugin_dir;

  if($qwq_options['is_edu']=="yes")
    $db_is_edu = "true";
  else
    $db_is_edu = "false";

  extract(shortcode_atts(array('modul'=>$qwq_options['modul'],'bgcolor'=>$qwq_options['bgcolor'],'bordercolor'=>$qwq_options['bordercolor'],'bordersize'=>$qwq_options['bordersize'],'is_edu'=>$db_is_edu,'subtext'=>$qwq_options['subtext']), $atts));

  $param_array = Array("modul"=>$modul,
                       "bgcolor"=>$bgcolor,
                       "bordercolor"=>$bordercolor,
                       "bordersize"=>$bordersize,
                       "is_edu"=>$is_edu,
                       "subtext"=>$subtext);

  $code = create_script($param_array);
  return $code;
}
add_shortcode('qwiequiz', 'qwq_get_params');

//------------ [qwqout] ----------------------------------------------
function qwq_get_evaluation($atts) {
  global $qwq_options,$qwq_plugin_dir;

  $qpfad = dirname(__FILE__) . DIRECTORY_SEPARATOR;

  $relpfad = $qwq_plugin_dir."/";

  include_once(dirname(__FILE__) . DIRECTORY_SEPARATOR ."ausw.php");

  extract(shortcode_atts(array('modul'=>$qwq_options['modul']), $atts));

  return utf8_encode($inc);

}
add_shortcode('qwqout', 'qwq_get_evaluation');



//============= Seite für Plugin-Administration aufbauen ====================
function qwq_options_page() {
  global $wpdb,$qwq_plugin_dir,$qwqversion;

  if (defined('WPLANG')) {
    $lang = WPLANG;
  }
  if (empty($lang)) {
    $lang = 'de_DE';
  }

  if(!@include_once "lang/".$lang.".php")
    include_once "lang/en_EN.php";


  // Read in existing option value from database
  $qwq_options = get_option( "qwq_options" );
  $qwq_deinstall = $qwq_options["deinstall"];
  $qwq_modul = $qwq_options["modul"];
  $qwq_bgcolor = $qwq_options["bgcolor"];
  $qwq_bordercolor = $qwq_options["bordercolor"];
  $qwq_bordersize = $qwq_options["bordersize"];
  $qwq_is_edu = $qwq_options["is_edu"];
  $qwq_subtext = $qwq_options["subtext"];
  //$qwq_page_id = $qwq_options['qwq_page_id'];

  // See if the user has posted us some information
  // If they did, this hidden field will be set to 'Y'
  if( $_POST[ 'qwq_submit_hidden' ] == "Y" ) {

    // Read their posted value
    $qwq_deinstall = $_POST[ 'qwq_deinstall' ];
    $qwq_modul = $_POST[ 'qwq_modul' ];
    $qwq_bgcolor = $_POST[ 'qwq_bgcolor' ];
    $qwq_bordercolor = $_POST[ 'qwq_bordercolor' ];
    $qwq_bordersize = $_POST[ 'qwq_bordersize' ];
    $qwq_is_edu = $_POST[ 'qwq_is_edu' ];
    $qwq_subtext = $_POST[ 'qwq_subtext' ];


    // Save the posted value in the database
    $qwq_options["deinstall"] = $qwq_deinstall;
    $qwq_options["modul"] = $qwq_modul;
    $qwq_options["bgcolor"] = $qwq_bgcolor;
    $qwq_options["bordercolor"] = $qwq_bordercolor;
    $qwq_options["bordersize"] = $qwq_bordersize;
    $qwq_options["is_edu"] = $qwq_is_edu;
    $qwq_options["subtext"] = $qwq_subtext;


   // $qwq_options['qwq_page_id'] = $qwq_page_id;
    update_option( "qwq_options", $qwq_options );


    // Put an options updated message on the screen

    ?>
    <div class="updated"><p><strong><?php echo $istgespeichert_w; ?></strong></p></div>
    <?php

  } //bei Formularversand


  if($qwq_deinstall=="yes")
    $qwq_deinstall_check = " checked";
  else
    $qwq_deinstall_check = "";

  if($qwq_is_edu=="yes")
    $qwq_is_edu_check = " checked";
  else
    $qwq_is_edu_check = "";



    //---- Module auslesen -------
    $modul_files = "";
    $verz=opendir (dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR."wp-content/plugins/wp-qwiequiz/data");
    while ($file = readdir ($verz)) {
      if(($file!="..") and ($file!=".")) {
        //if(is_dir($file)) {
          if($qwq_modul==$file)
            $modul_files .= '<option value="'.$file.'" selected>'.$file.'</option>';
          else
            $modul_files .= '<option value="'.$file.'" >'.$file.'</option>';
        //}

      }
    } //while


  //============ Now display the options editing screen ===========================
  echo "<div class=\"wrap\">";

  // header
  echo "<h2>" . __( "Q wie Quiz Administration", "qwq_trans_domain" ) . "</h2>";

  // options form

  ?>

  <form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
  <input type="hidden" name="qwq_submit_hidden" value="Y" />

  <table border="0" cellpadding="3" cellspacing="0">
   <tr><td colspan="3"><br /><b><?php echo $allgemeines_w; ?>:</b><br />&nbsp;</td></tr>
   <tr>
    <td style="width:140px;">
    <?php echo $deinstall_w; ?>:</td>
    <td><input type="checkbox" name="qwq_deinstall" value="yes"<?php echo $qwq_deinstall_check; ?> />
    <?php echo $deinstall_hinweis_w; ?></td>
   </tr>
   <tr><td>&nbsp;</td></tr>
   <tr>
    <td><?php echo $modul_w; ?>:</td>
    <td align="left">
    <select name="qwq_modul" size="1" class="fe_txt" style="width:140px;"><?php echo $modul_files; ?></select>
    </td>
   </tr>
   <tr>
    <td valign="middle"><?php echo $bgcolor_w; ?>:</td>
    <td align="left">
     <input type="text" name="colorpicker1" value="" class="color {valueElement:'qwq_bgcolor'} fe_txt" style="width:24px; height:24px; margin-top:10px; background:<?php echo $qwq_bgcolor; ?>;" />
           <input type="text" name="qwq_bgcolor" id="qwq_bgcolor" value="<?php echo $qwq_bgcolor; ?>" class="fe_txt" style="width:60px;" />
    </td>
   </tr>
   <tr>
    <td valign="middle"><?php echo $bordercolor_w; ?>:</td>
    <td align="left">
     <input type="text" name="colorpicker2" value="" class="color {adjust:false,valueElement:'qwq_bordercolor'} fe_txt" style="width:24px; height:24px; margin-top:5px; background:<?php echo $qwq_bordercolor; ?>;" />
           <input type="text" name="qwq_bordercolor" id="qwq_bordercolor" value="<?php echo $qwq_bordercolor; ?>" class="fe_txt" style="width:60px;" />
    </td>
   </tr>
   <tr>
    <td><?php echo $bordersize_w; ?>:</td>
    <td align="left">
    <input type="text" name="qwq_bordersize" value="<?php echo $qwq_bordersize; ?>" class="fe_txt" style="width:24px; margin-top:5px;" />px
    </td>
   </tr>
   <tr>
    <td valign="middle"><?php echo $is_edu_w; ?>:</td>
    <td align="left">
    <input type="checkbox" name="qwq_is_edu" value="yes"<?php echo $qwq_is_edu_check;?> style="margin-top:7px;" onclick="if(this.checked) {document.getElementById('subtextbox').style.visibility='visible';document.getElementById('qwq_subtext').style.visibility='visible'} else {document.getElementById('subtextbox').style.visibility='hidden';document.getElementById('qwq_subtext').style.visibility='hidden'}">
    </td>
   </tr>


   <tr>
    <td valign="middle">&nbsp;</td>
    <td align="left">
     <?php if($qwq_is_edu=="yes") { ?>
      <div id="subtextbox" style="visibility:visible"><?php echo $subtext_w; ?>: <input type="text" name="qwq_subtext" id="qwq_subtext" value="<?php echo $qwq_subtext; ?>" class="fe_txt" style="width:600px; margin-top:5px;" /></div>
     <?php } else { ?>
      <div id="subtextbox" style="visibility:hidden"><?php echo $subtext_w; ?>: <input type="text" name="qwq_subtext" id="qwq_subtext" value="<?php echo $qwq_subtext; ?>" class="fe_txt" style="width:600px; margin-top:5px;visibility:hidden;" /></div>
     <?php } ?>
    </td>
   </tr>

   </table>

  <hr />

  <p class="submit">
  <input type="submit" name="Submit" value="<?php echo $speichern_w; ?>" />
  </p>

  </form>

  <br />
  <?php echo $fußnote_w; ?>


  </div>

  <?
}


function create_script($parameter) {
  global $qwq_options,$qwq_plugin_dir;

  $script_code = '<script type="text/javascript">'."\n";
  $script_code .= 'fquizzer("'.$qwq_plugin_dir.'/","'.$parameter['modul'].'","#'.$parameter['bgcolor'].'","#'.$parameter['bordercolor'].'",'.$parameter['bordersize'].','.$parameter['is_edu'].',"'.$parameter['subtext'].'");'."\n";
  $script_code .= '</script>';

  return $script_code;
}







?>