<?php

include 'edit/sql_connect.php';

$q_skill = 'SELECT * FROM t_skill ORDER BY t_skill_name';

$r_skill = mysql_query($q_skill) or die ("Skill query failed! " . mysql_error());

echo "<html>\n<head>\n";

include 'header.inc.php';
include 'header_rules.inc.php';

?>
<meta http-equiv="content-type" content="text/html;charset=ISO-8859-1">
</head>

<body bgcolor="#203264">

<?php

echo "</head>\n<body>\n";

echo "<P align=center><table border=1 width=640 cellpadding=1 cellspacing=1 bgcolor=FFFFFF><table summary=\"Skill table\" width=640 bgcolor=#ffffff border=1 cellpadding=0 cellspacing=0>\n";

while ($n_skill = mysql_fetch_array($r_skill)) {

	echo "<tr><td><b>" . $n_skill['t_skill_name'] . "</b><br>";
	switch ($n_skill['t_skill_type']) {
	case "G":
		echo "(General)"; break;
	case "A":
		echo "(Agility)"; break;
	case "P":
		echo "(Passing)"; break;
	case "S":
		echo "(Strength)"; break;
	case "M":
		echo "(Mutation)"; break;
	case "E":
		echo "(Extraordinary)";
	}
	echo "</td><td>" . html_entity_decode($n_skill['t_skill_desc'], ENT_QUOTES) . "</td></tr>\n";

}

echo "</table>\n</table>";

echo "</html>";

?>