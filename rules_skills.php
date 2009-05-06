<?php
session_start();
include "includes/library.inc.php";
include "includes/sql_connect.php";
select_forms();
head_std("Rules - Skill List");
body_top();
echo "<div align=center>\n";
echo "<table border=1 cellspacing=0 cellpadding=0 width=800>";
	echo "<tr><td>\n";
	echo "<table cellspacing=0 cellpadding=0>";
		echo "<tr><td width=400 align=center>";
		echo "<table border=1 cellspacing=0 cellpadding=0 width=400>";
			echo "<tr><td align=center>";
			echo "<a href=rules_teams.php><B>TEAMS</B></a>";
			echo "</td></tr>";
		echo "</table>\n</td>\n";
		echo "<td align=center width=400><B>SKILLS</B></a></td>";
		echo "</tr>\n";
		echo "<tr><td colspan=2>\n";
		rules_skills();
		echo "</td></tr>";
	echo "</table>/n";
	echo "</td></tr>";
echo "</table>/n";
echo "</div>";
echo "</body>\n</html>";
?>