<img src="<?php echo $global_infos["themenbild"]; ?>" border="1" alt="Themenbild: <?php echo $global_infos["titel"]; ?>" width="90" height="90" title="Themenbild: <?php echo $global_infos["titel"]; ?>" id="themenbild" />
<div id="headline"><?php echo $global_infos["titel"]; ?></div>
<div id="infobox">
<?php if($is_edu) echo $user_infos["name"].", "; ?>Sie haben <?php echo $user_infos["ok_count"]; ?> von <?php echo $global_infos["fragen_zahl"] ; ?> Fragen richtig beantwortet.<br />
Das ist eine Quote von <?php get_prozent(0); ?>%.<br />
Zeit: <?php echo $user_infos["dauer"]; ?> Sek., das sind <?php echo $user_infos["minuten"]; ?> Min., <?php echo $user_infos["sekunden"]; ?> Sek.<br />
<br />
<?php get_assesment_txt(); ?><br />
<br />
Hier nochmal alle Fragen und Lösungen im Überblick:<br />
Die richtigen Lösungen sind <b>fett</b> markiert, die <span class="antwort_ok antwort_userok">grüne</span> bzw. <span class="antwort_userno">rote</span> Markierung zeigt Ihre Antwort.<br />
</div>
<br />

<?php while (is_question()) : ?>

  <b>Frage <?php get_akt_quest(); ?>:</b><br />
  <div style="width:450px;"><b><?php get_question(); ?></b></div>
  <?php get_answers("all"); ?>
  <br />

<?php endwhile; ?>


<input type="button" name="druckbtn" class="druck" value="Drucken" onclick="window.print();" />