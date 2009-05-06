<?php
session_start();
include "includes/library.inc.php";
include "includes/sql_connect.php";
new_league();
del_league();
if (isset($_POST['sched_rr'])) {
	//Create a round-robin schedule
}
if (isset($_POST['del_game'])) {
	//Create a round-robin schedule
	$sql = "DELETE FROM t_game WHERE t_game_ID = " . $_POST['gameid'];
	$del_game = get_sqlresult($sql, "Delete game");
}
if (isset($_POST['add_game'])) {
	//Add game to games list
	// $_POST['h_teamid']
	// $_POST['a_teamid']
	// $_POST['s_date']
	if ($_POST['h_teamid'] == $_POST['a_teamid']) {
		$_SESSION['admin_err'] = "Error: A team cannot play against itself.";
	} elseif ( ! checkdate(intval(substr($_POST['s_date'],6,2)),intval(substr($_POST['s_date'],8,2)),intval(substr($_POST['s_date'],1,4))) ) {
		$_SESSION['admin_err'] = "Error: Invalid date.";
	} else {
		$sql = "INSERT INTO t_game VALUES (NULL, " . $_SESSION['fixtureID'] . ", " . $_POST['h_teamid'] . ", " . $_POST['a_teamid'] . ", '" . $_POST['s_date']
			. "', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)";
		$ins_game = get_sqlresult($sql, "Insert game");
	}
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
				echo ( (1==$_SESSION['userschema']) ? '<a href=a_users.php>' : '');
				echo "<B>USERS</B>";
				echo ( (1==$_SESSION['userschema']) ? '</a>' : '');
				echo "</td></tr>";
			echo "</table>\n</td>";
			echo "</tr>";
			// Actual page starts here
			//echo "<tr><td colspan=4>\n";
			echo "<tr><td colspan=4 align=center>\n";
			change_league();
			echo "</td></tr>\n";
			echo "<tr><td colspan=4 align=center>" . $_SESSION['admin_err'] ;
			echo "</td></tr>\n";
			if (isset($_SESSION['fixtureID'])) {
				echo "<table border=1 cellspacing=0 cellpadding=0 width=100%>";
					echo "<tr><td>\n";
					echo "<table cellspacing=0 cellpadding=0 width=100%>";
						echo "<tr>\n";
						echo "<td align=center width=50%>\n";
						echo "<table border=1 cellspacing=0 cellpadding=0 width=100%>\n";
							echo "<tr><td align=center><a href=admin.php><B>ADD TEAMS TO FIXTURE</B></a></td></tr>\n";
						echo "</table>\n</td>\n";
						echo "<td align=center width=50%><B>GAME SCHEDULE</B></td>\n";
						echo "</tr>\n";
						echo "<tr><td colspan=2 align=center>\n";
						// Start schedule games
						$sql = "SELECT t_game_ID, th.t_team_name t_h_team_name, ta.t_team_name t_a_team_name, t_game_scheduled, t_game_status"
							." FROM t_game g, t_team th, t_team ta"
							." WHERE g.t_h_team_ID = th.t_team_ID"
							." AND g.t_a_team_ID = ta.t_team_ID"
							." AND t_division_ID = " . $_SESSION['fixtureID'];
						$games = get_sqlresult($sql, "Get games");
						echo "<table>\n";
							echo "<tr><th>Date scheduled</th><th>Home</th><th>&nbsp;</th><th>Away</th><th>Status</th><th>&nbsp;</th></tr>\n";
							if (0 < mysql_num_rows($games)) {
								while ($n_games = mysql_fetch_array($games)) {
									echo "<tr>";
									echo "<form method=post action=" . $_SERVER['PHP_SELF'] . ">\n";
									echo "<input type=hidden name=gameid value=" . $n_games['t_game_ID'] . ">";
									echo "<td>" . $n_games['t_game_scheduled'] . "</td>";
									echo "<td>" . $n_games['t_h_team_name'] . "</td><td> - </td><td>" . $n_games['t_a_team_name'] . "</td>";
									echo "<td>";
									switch ($n_games['t_game_status']) {
									case "1":
										echo "Not played</td><td><input type=submit name=del_game value=Delete></td>";
										break;
									case "2":
										echo "Played, not approved<td>&nbsp;</td>";
										break;
									case "3":
										echo "Played and approved<td>&nbsp;</td>";
										break;
									}
									echo "</td>\n";
									echo "</form>";  
									echo "</tr>\n";
								}
							}
							$sql = "SELECT t.t_team_ID, t_team_name FROM t_team t, t_team_fixture f"
								. " WHERE t.t_team_ID = f.t_team_ID"
								. " AND f.t_division_ID = " . $_SESSION['fixtureID'];
							$team = get_sqlresult($sql, "Get teams");
							if (0 < mysql_num_rows($team)) {
								echo "<tr><td>\n";
								echo "<form name=s_datecontrol method=post action=" . $_SERVER['PHP_SELF'] . ">\n";
								echo "<input type=text value=\"" . date("Y-m-d") . "\" name=s_date size=11>";
								//Calendar control does not work... Argh...
								//echo "<input type=\"button\" value=\"...\" onClick=\"newWindow('s_date')\" id=button1 name=button1>";
								echo "</td>\n";
								echo "<td><select name=h_teamid>\n";
								while ($n_team = mysql_fetch_array($team)) {
									echo "<option value=" . $n_team['t_team_ID'] . ">" . $n_team['t_team_name'] . "</option>\n";
								}
								echo "</select>\n";
								echo "</td><td> - </td>\n";
								$nspool = mysql_data_seek($team,0);
								echo "<td><select name=a_teamid>\n";
								while ($n_team = mysql_fetch_array($team)) {
									echo "<option value=" . $n_team['t_team_ID'] . ">" . $n_team['t_team_name'] . "</option>\n";
								}
								echo "</select>\n";
								echo "</td>\n";
								echo "<td>&nbsp;</td>";
								echo "<td><input type=submit name=add_game value=\"Add game\"></td></tr></form>\n";
								echo "<tr><td colspan=6 align=center>\n";
								echo "<form method=post action=" . $_SERVER['PHP_SELF'] . ">\n";
								echo "<input type=submit name=sched_rr value=\"Create roundrobin schedule\" disabled>";
								echo "</form>";
							}
						echo "</table>\n";
						echo "</td></tr>\n";
						// End schedule games
						//echo "</td></tr>\n";
					echo "</table>\n";
					echo "</td></tr>\n";
				echo "</table>\n";
			}
		echo "</table></td></tr>\n";
	echo "</table>\n";
	unset($_SESSION['admin_err']);
}
echo "</body>\n</html>";
?>