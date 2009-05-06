<?php
include 'header.inc.php';
include 'header_edit.inc.php';
include 'sql_connect.php';
include 'library.inc.php';
?>
</head>
<body bgcolor="#203264">

<center>

<P><table border=1 cellpadding=1 cellspacing=1 bgcolor="#ffffff"><tr><td>

<table summary="Main View">

<tr><td bgcolor="#ffffff" colspan=2 align=center>
<table border=1 cellpadding=0 cellspacing=0>
<tr><th>Username</th><th>User schema</th></tr>
<?php
$sql='SELECT t_user_id, t_user_name, s.t_schema_id, t_schema_name FROM t_user u, t_schema s WHERE u.t_schema_id = s.t_schema_id';
$r_sql = mysql_query($sql) or die(b_error('Select users failed',$sql));
while ($n_sql = mysql_fetch_array($r_sql)) {
	echo "<tr><td>" . $n_sql['t_user_name'] . "</td><td>" . $n_sql['t_schema_name'] . "</td>";
	echo "<td>" . ($_SESSION['userrights']==255 ? "<td><form method=post action=\"" . $PHP_SELF . "\"><input type=submit name=user_edit value=Edit></form>" : "&nbsp;" ) . "</td></tr>";
}
?>
</table>
</td></tr>
</table>
</td></tr>
</table>
</center>
</body>
</html>