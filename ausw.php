<?php
 include "parsedata.php";
 ob_start();
 include "template.php";
 $inc = ob_get_contents();
 ob_clean();
?>