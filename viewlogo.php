<?php
  include 'edit/sql_connect.php';
  $sql = "SELECT t_race_logo FROM t_race WHERE t_race_ID = " . $_REQUEST['race'];
  $r_tab = mysql_query($sql) or die ("Get image query failed!\n" . mysql_error() ."\n" . $sql);
  header("Content-type: image/gif");
  echo mysql_result($r_tab, 0);
?>
