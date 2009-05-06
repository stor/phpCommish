<?php
session_start();
include "includes/library.inc.php";
include "includes/sql_connect.php";
new_league();
del_league();
update_fixture_team();
select_forms();
head_std("Admin");
body_top();
if (isset($_SESSION['userID'])) {
	echo "<div align=center>\n";
	echo "<table border=1 cellspacing=0 cellpadding=0 width=800>";
		echo "<tr><td>\n";
		echo "<table cellspacing=0 cellpadding=0>";
			echo "<tr>\n";
			// Selected tab
			echo "<td align=center width=200><B>LEAGUES</B></a></td>";
			// Selectable tabs (links)
			echo "<td>\n";
			echo "<table border=1 cellspacing=0 cellpadding=0 width=200>";
				echo "<tr><td align=center><a href=a_teams.php><B>TEAMS</B></a></td></tr>";
			echo "</table>\n</td>";
			echo "<td>\n";
			echo "<table border=1 cellspacing=0 cellpadding=0 width=200>";
				echo "<tr><td align=center><a href=a_results.php><B>RESULTS</B></a></td></tr>";
			echo "</table>\n</td>";
			echo "<td>\n";
			echo "<table border=1 cellspacing=0 cellpadding=0 width=200>";
				echo "<tr><td align=center>";
				echo "<a href=a_users.php><B>USERS</B></a>";
				echo "</td></tr>";
			echo "</table>\n</td>";
			echo "</tr>";
			// Actual page starts here
			//echo "<tr><td colspan=4>\n";
			echo "<tr><td colspan=4 align=center>\n";
			if (1==$_SESSION['userschema']) {
				change_league();
				echo "</td></tr>\n";
				echo "<tr><td align=center>" . $_SESSION['admin_err'] ;
				echo "</td></tr>\n";
				if (isset($_SESSION['fixtureID'])) {
					echo "<table border=1 cellspacing=0 cellpadding=0 width=100%>";
						echo "<tr><td>\n";
						echo "<table cellspacing=0 cellpadding=0 width=100%>";
							echo "<tr>\n";
							echo "<td align=center width=50%><B>ADD TEAMS TO FIXTURE</B></td>\n";
							echo "<td align=center width=50%>\n";
							echo "<table border=1 cellspacing=0 cellpadding=0 width=100%>\n";
								echo "<tr><td align=center><a href=a_schedule.php><B>GAME SCHEDULE</B></a></td></tr>\n";
							echo "</table>\n</td>\n";
							echo "</tr>\n";
							echo "<tr><td colspan=2 align=center>\n";
							update_fixture_team_forms();
							echo "</td></tr>\n";
						echo "</table>\n";
						echo "</td></tr>\n";
					echo "</table>\n";
				}
			} else {
				view_league();
			}
		echo "</table></td></tr>\n";
	echo "</table>\n";
	unset($_SESSION['admin_err']);
}
echo "</body>\n</html>";
?>