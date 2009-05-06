<?php ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">



<html>

<head>
		<meta http-equiv="content-type" content="text/html;charset=ISO-8859-1">
		<title>View basic teams</title>

<?php

	include 'header.inc.php';
	include 'header_rules.inc.php';
	include 'edit/sql_connect.php';
	include 'edit/library.inc.php';

?>

</head>

<body bgcolor="#203264">

<P>

<?php

// get teams

$q_team = 'SELECT * FROM t_race';

$r_team = mysql_query($q_team) or die ("Team query failed");

// for each team do

while ($team = mysql_fetch_array($r_team)) {

	echo "<center><table border=1 width=640 cellpadding=1 cellspacing=1 bgcolor=FFFFFF><tr><td>\n";
	echo "<table>\n<tr>\n<td><b>";
	echo strtoupper($team['t_race_name']) . " TEAMS";
	echo "</b></td>\n"; echo "<td rowspan=2><img src=\"viewlogo.php?race=" . $team['t_race_ID'] . "\" width=110>";
	echo "</td>\n</tr>";

	echo "<tr>\n<td>";
	
	echo htmlentities($team['t_race_description'],ENT_QUOTES,'UTF-8') . "<br>\n";
	
	echo "</td>\n</tr>\n<tr>\n<td colspan=2>";
	
	// get positions
	
	echo "<table>\n<tr>\n<th>Qty</th><th align=left>Title</th><th>Cost</th><th>MA</th><th>ST</th><th>AG</th><th>AV</th><th align=left>Skills</th><th>Normal</th><th>Double</th>\n</tr>\n";
	
	$q_pos = 'SELECT * FROM t_positions WHERE t_race_ID = ' . $team['t_race_ID'];
	
	$r_pos = mysql_query($q_pos) or die ("Positions query failed");
	
	// for each position do
	
	while ($pos = mysql_fetch_array($r_pos)) {
		
		echo "<tr>\n";
		
		echo "<td align=center width=20>0-" . $pos['t_positions_qty'] . "</td>";
		
		echo "<td width=150>" . $pos['t_positions_title'] . "</td>";
		
		echo "<td align=right width=40>" . number_format($pos[8]) . "</td>";
		
		echo "<td align=center width=20>" . $pos[4] . "</td>";
		
		echo "<td align=center width=20>" . $pos[5] . "</td>";
		
		echo "<td align=center width=20>" . $pos[6] . "</td>";
		
		echo "<td align=center width=20>" . $pos[7] . "</td>";
		
		 // get skills
		
		$q_skill = 'SELECT p.t_skill_ID, t_skill_name, t_skill_desc, t_skill_type FROM `t_positions_skill` p, `t_skill` s'
		
				. ' WHERE p.t_skill_ID = s.t_skill_ID'
		
				. ' AND p.t_positions_ID = ' . $pos['t_positions_ID'] . ';';
		
		$r_skill = mysql_query($q_skill) or die ("Skill query failed");
		
		// for each skill do
		
		echo "<td width=200>";
		
		$p_skill = "";
		
		while ($skill = mysql_fetch_array($r_skill)) {
		
					 $p_skill = $p_skill . $skill['t_skill_name'] . ", " ;
		
		}// end
		
		if ($p_skill == "" ) {
		
		  echo "&nbsp;";
		
		} else {
		
		  echo substr($p_skill,0,strlen($p_skill)-2);
		
		}
		echo "</td>";
		// get skill types
		$q_skill = 'SELECT t_skill_type, t_skill_type_d FROM t_positions_skill_type WHERE t_position_ID= ' . $pos['t_positions_ID']
					. ' ORDER BY t_skill_type_d, t_skill_type';
		$r_skill = mysql_query($q_skill) or die ("Skill type query failed!" . mysql_error() . " : " . $q_skill);
		$n_skill = ""; $d_skill = "";
		while ($skill = mysql_fetch_array($r_skill)) {
			$n_skill = $n_skill . (($skill['t_skill_type_d'] == 0) ? $skill['t_skill_type'] : "");
			$d_skill = $d_skill . (($skill['t_skill_type_d'] == 1) ? $skill['t_skill_type'] : "");
		}
		echo "<td>" . (($n_skill == "") ? "&nbsp;" : $n_skill) . "</td>" ;
		echo "<td>" . (($d_skill == "") ? "&nbsp;" : $d_skill) . "</td>" ;
		echo "</td>\n</tr>\n";
		
	}// end
	
	echo "</table>\n</td>\n</tr>\n<tr>\n<td>Re-roll counter: ";
	
	echo number_format($team['t_race_rerollprice']);
	
	echo " gold pieces each</td>\n</tr>\n</table>\n";
	
	echo "</td>\n</tr>\n</table>\n";
	
	echo "</td>\n</tr>\n<tr>\n<td>&nbsp;</td>\n</tr>\n<tr>\n<td colspan=2>\n";
	
};//end

?>

</td>	

</tr>

</table>

</center>

</body>

</html>

