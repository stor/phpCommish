<?php
session_start();
include "includes/library.inc.php";
include "includes/sql_connect.php";
if (isset($_POST['approve_team'])) {
	set_teamstatus($_POST['teamid'],2);
}
select_forms();
head_std("Admin");
body_top();
if (isset($_SESSION['userID'])) {
	echo "<div align=center>\n";
	echo "<table border=1 cellspacing=0 cellpadding=0 width=800>";
		echo "<tr><td>\n";
		echo "<table cellspacing=0 cellpadding=0>";
			echo "<tr>\n";
			echo "<td>\n";
			echo "<table border=1 cellspacing=0 cellpadding=0 width=200>";
				echo "<tr><td align=center><a href=admin.php><B>LEAGUES</B></a></td></tr>";
			echo "</table>\n</td>";
			// Selected tab
			echo "<td align=center width=200><B>TEAMS</B></a></td>";
			// Selectable tabs (links)
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
			echo "<tr><td colspan=4 align=center>";
			if (isset($_SESSION['fixtureID'])) {
				$sql = "SELECT te.t_team_ID, te.t_team_name, tr.t_race_name, tu.t_user_name, te.t_team_status FROM t_team te, t_race tr, t_team_fixture tf, t_user tu"
					. " WHERE te.t_race_ID = tr.t_race_ID"
					. " AND te.t_team_ID = tf.t_team_ID"
					. " AND tu.t_user_id = te.t_user_ID"
					. ((1==$_SESSION['userschema']) ? '' : ' AND t_user_ID = ' . $_SESSION['userID'] )
					. " AND t_division_ID = " . $_SESSION['fixtureID']
					. " GROUP BY te.t_team_name";
			} else {
				$sql = "SELECT te.t_team_ID, te.t_team_name, tr.t_race_name, tu.t_user_name, te.t_team_status FROM t_team te, t_race tr, t_user tu"
					. " WHERE te.t_race_ID = tr.t_race_ID"
					. " AND tu.t_user_id = te.t_user_ID"
					. ((1==$_SESSION['userschema']) ? '' : ' AND tu.t_user_ID = ' . $_SESSION['userID'] )
					. " GROUP BY te.t_team_name";
			}
			$teams = get_sqlresult($sql, "Get your teams");
			echo "<table>\n";
			echo "<tr><th>Status</th><th>Team</th></tr>\n";
			while ($n_teams = mysql_fetch_array($teams)) {
				echo "<tr>\n<td>";
				echo ((1==$n_teams['t_team_status']) ? 'Not approved' : 'Approved') ;
				echo "</td>\n<td>";
				echo "<a href=team_preview.php?team=" . $n_teams['t_team_ID'] . "&jbb=0 target=\"_blank\">" . $n_teams['t_team_name'] . ", " . ucwords(strtolower($n_teams['t_race_name'])) . " (" . $n_teams['t_user_name'] . ")</a>";
				echo "</td>\n<td><form method=post action=edit_team.php>";
				echo "<input type=hidden name=teamid value=" . $n_teams['t_team_ID'] . "><input type=submit name=edit_team value=\"Edit team\">";
				echo "</form></td>\n<td>";
				echo "<form method=post action=" . $_SERVER['PHP_SELF'] . ">";
				echo "<input type=hidden name=teamid value=" . $n_teams['t_team_ID'] . ">";
				echo ((1==$n_teams['t_team_status']) ? ((1==$_SESSION['userschema']) ? '<input type=submit name=approve_team value="Approve">' : '') : '' );
				echo "</form>";
				echo "</td>\n</tr>\n";
			}
			echo "</table>\n";
			// End of actual page
			echo "</td></tr>\n";
		echo "</table></td></tr>\n";
	echo "</table>\n";
}
echo "</body>\n</html>";
?>