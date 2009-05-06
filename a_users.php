<?php
session_start();
include "includes/library.inc.php";
include "includes/sql_connect.php";
select_forms();
user_updates();
head_std("Admin");
body_top();
if (isset($_SESSION['userID'])) {
	echo "<div align=center>\n";
	echo "<table border=1 cellspacing=0 cellpadding=0 width=800>";
		echo "<tr><td>\n";
		echo "<table cellspacing=0 cellpadding=0>";
			echo "<tr>\n";
			// Selectable tabs (links)
			echo "<td>\n";
			echo "<table border=1 cellspacing=0 cellpadding=0 width=200>";
				echo "<tr><td align=center><a href=admin.php><B>LEAGUES</B></a></td></tr>";
			echo "</table>\n</td>";
			echo "<td>\n";
			echo "<table border=1 cellspacing=0 cellpadding=0 width=200>";
				echo "<tr><td align=center><a href=a_teams.php><B>TEAMS</B></a></td></tr>";
			echo "</table>\n</td>";
			echo "<td>\n";
			echo "<table border=1 cellspacing=0 cellpadding=0 width=200>";
				echo "<tr><td align=center><a href=a_results.php><B>RESULTS</B></a></td></tr>";
			echo "</table>\n</td>";
			// Selected tab
			echo "<td align=center width=200><B>USERS</B></a></td>";
			echo "</tr>\n";
			echo "<tr><td colspan=4 align=center>\n";
			user_forms();
			echo "</td></tr>\n";
		echo "</table></td></tr>\n";
	echo "</table>\n";
}
echo "</body>\n</html>";
?>