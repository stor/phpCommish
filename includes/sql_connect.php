<?php
$c_bb = mysql_connect('localhost','demo','demo') or die ("Could not connect to MySQL! " . mysql_error());
$b_bb = mysql_select_db('phpc_lrb5',$c_bb) or die ("Could not select Bloodbowl database! " . mysql_error());
$nbsp = '&nbsp;';
