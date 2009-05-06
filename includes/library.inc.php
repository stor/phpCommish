<?php
// Global constants

// Functions
function set_teamstatus($teamid,$status)
{
	$sql = "UPDATE t_team SET t_team_status = " . $status . " WHERE t_team_ID = " . $teamid;
	$status = get_sqlresult($sql, "Update teamstatus");
}
function view_league()
{
	echo "<table>";
	$sql = "SELECT te.t_team_ID, te.t_team_name, tr.t_race_name FROM t_team te, t_race tr, t_team_fixture tf WHERE te.t_race_ID = tr.t_race_ID"
		. " AND te.t_team_ID = tf.t_team_ID"
		. (isset($_SESSION['fixtureID']) ? " AND t_division_ID = " . $_SESSION['fixtureID'] : "" )
		. " GROUP BY te.t_team_name";
	$teams = get_sqlresult($sql, "Get league teams");
	while ($n_teams = mysql_fetch_array($teams)) {
		echo "<tr><td>";
		echo "<a href=team_preview.php?team=" . $n_teams['t_team_ID'] . "&jbb=0 target=\"_blank\">";
		echo $n_teams['t_team_name'] . ", " . ucwords(strtolower($n_teams['t_race_name'])) . "</a>";
		echo "</td></tr>\n";
	}
	echo "</table>";
}
function update_fixture_team()
{
	if (isset($_POST['r_team'])) {
		$sql = "DELETE FROM t_team_fixture WHERE t_division_ID = " . $_SESSION['fixtureID'] . " AND t_team_ID = " . $_POST['teamid'];
		$del_ffix = get_sqlresult($sql, "Delete from fixture");
	}
	if (isset($_POST['a_team'])) {
		$sql = "INSERT INTO t_team_fixture VALUES (" . $_POST['teamid'] . ", " . $_SESSION['fixtureID'] . ")";
		$add_tfix = get_sqlresult($sql, "Add to fixture");
	}
}
function update_fixture_team_forms()
{
	echo "<table><tr><td valign=top>\n";
	$sql = "SELECT te.t_team_ID, te.t_team_name, tr.t_race_name FROM t_team te, t_race tr, t_team_fixture tf WHERE te.t_race_ID = tr.t_race_ID"
		. " AND te.t_team_ID = tf.t_team_ID"
		. " AND t_division_ID = " . $_SESSION['fixtureID']
		. " GROUP BY te.t_team_name";
	$teams = get_sqlresult($sql, "Get your teams");
	echo "<table>\n";
	echo "<tr><th colspan=2>Fixture teams</th></tr>\n";
	while ($n_teams = mysql_fetch_array($teams)) {
		echo "<tr><td>";
		echo "<form method=post action=" . $_SERVER['PHP_SELF'] . ">\n";
		echo "<input type=hidden name=teamid value=" . $n_teams['t_team_ID'] . ">\n";
		echo $n_teams['t_team_name'] . ", " . ucwords(strtolower($n_teams['t_race_name']));
		echo "</td><td>";
		echo "<input type=submit name=r_team value=\">\"></form>\n";
		echo "</td></tr>\n";
	}
	echo "</table>\n";
	echo "</td><td>\n";
	$sql = "SELECT t.t_team_ID, t.t_team_name, r.t_race_name"
		. " FROM t_race r, t_team t LEFT JOIN" 
		. " (SELECT t.t_team_ID, f.t_division_ID"
		. " FROM t_team t, t_team_fixture f"
		. " WHERE t.t_team_ID = f.t_team_ID"
		. " AND f.t_division_ID = " . $_SESSION['fixtureID'] . ") nt ON t.t_team_ID = nt.t_team_ID"
		. " WHERE t.t_race_ID = r.t_race_ID AND nt.t_team_ID IS NULL"
		. " ORDER BY t.t_team_name";
	$teams = get_sqlresult($sql, "Get other teams");
	echo "<table>\n";
	echo "<tr><th colspan=2>Available teams</th></tr>\n";
	while ($n_teams = mysql_fetch_array($teams)) {
		echo "<tr><td>";
		echo "<form method=post action=" . $_SERVER['PHP_SELF'] . ">\n";
		echo "<input type=hidden name=teamid value=" . $n_teams['t_team_ID'] . ">";
		echo "<input type=submit name=a_team value=\"<\">\n";
		echo "</td><td>\n";
		echo $n_teams['t_team_name'] . ", " . ucwords(strtolower($n_teams['t_race_name']));
		echo "</form></td></tr>\n";
	}
	echo "</table>\n</td></tr></table>\n";
}
function del_league()
{
	if (isset($_POST['del_value'])) {
		switch ($_POST['del_value']) {
		case "Delete fixture":
			//Delete a fixture
			//Check if there's games played in fixture
			$sql = "SELECT * FROM t_game WHERE t_division_ID = " . $_SESSION['fixtureID'];
			$c_game = get_sqlresult($sql, "Check games played");
			if (0 < mysql_num_rows($c_game)) {
				$_SESSION['admin_err'] = "Games exists. Cannot delete fixture: " . $_POST['fixture_name'] ;
			//Check if the checkbox is checked
			} elseif (isset($_POST['confirmdelete'])) {
				//Delete fixture
				$sql = "DELETE FROM t_division WHERE t_division_ID = " . $_SESSION['fixtureID'];
				$d_fix = get_sqlresult($sql, "Delete fixture");
				$sql = "DELETE FROM t_team_fixture WHERE t_division_ID = " . $_SESSION['fixtureID'];
				$d_tfix = get_sqlresult($sql, "Delete teams from fixture");
				$_SESSION['admin_err'] = "Fixture \"" . $_POST['fixture_name'] . "\" deleted.";
				unset($_SESSION['fixtureID']);
			} else {
				//Display error
				$_SESSION['admin_err'] = "Please check \"confirm delete\" to delete fixture.";
			}
			break;
		case "Delete league":
			//Delete a league
			//Check if there's seasons in league
			$sql = "SELECT * FROM t_season WHERE t_league_ID = " . $_POST['leagueid'];
			$c_season = get_sqlresult($sql, "Check for seasons");
			if (0 < mysql_num_rows($c_season)) {
				$_SESSION['admin_err'] = "Seasons exists. Cannot delete league: \"" . $_POST['season_name'] . "\".";
			//Check if the checkbox is checked
			} elseif (isset($_POST['confirmdelete'])) {
				//Delete fixture
				$sql = "DELETE FROM t_league WHERE t_league_ID = " . $_POST['leagueid'];
				$d_leag = get_sqlresult($sql, "Delete league");
				$_SESSION['admin_err'] = "League \"" . $_POST['season_name'] . "\" deleted.";
			} else {
				//Display error
				$_SESSION['admin_err'] = "Please check \"confirm delete\" to delete league.";
			}
			break;
		case "Delete season":
			//Delete a season
			//Check if there's fixtures in season
			$sql = "SELECT * FROM t_division WHERE t_season_ID = " . $_POST['seasonid'];
			$c_div = get_sqlresult($sql, "Check for divisions");
			if (0 < mysql_num_rows($c_div)) {
				$_SESSION['admin_err'] = "Divisions exists. Cannot delete season: \"" . $_POST['fixture_name'] . "\".";
			//Check if the checkbox is checked
			} elseif (isset($_POST['confirmdelete'])) {
				//Delete fixture
				$sql = "DELETE FROM t_season WHERE t_season_ID = " . $_POST['seasonid'];
				$d_leag = get_sqlresult($sql, "Delete season");
				$_SESSION['admin_err'] = "Season \"" . $_POST['fixture_name'] . "\" deleted.";
			} else {
				//Display error
				$_SESSION['admin_err'] = "Please check \"confirm delete\" to delete season.";
			}
		}
	}
}
function new_league()
{
	if (isset($_POST['new_value'])) {
		switch ($_POST['new_value']) {
		case "New fixture":
			//Add a fixture
			//Check if fixture name is used in this season before
			$sql = "SELECT * FROM t_division"
				. " WHERE t_season_ID = " . $_POST['seasonid'] // <-- The season ID for the selected fixture
				. " AND t_division_name = \"". $_POST['fixture_name'] . "\"";
			$fix = get_sqlresult($sql ,"Check fixtures");
			if (0 < mysql_num_rows($fix)) {
				$_SESSION['admin_err'] = "Season already has a fixture with the name: " . $_POST['fixture_name'];
			} else {
				$sql = "INSERT INTO t_division VALUES (NULL, \"" . $_POST['fixture_name'] . "\"," . $_POST['seasonid'] . ")";
				$ins_fix = get_sqlresult($sql,"Insert fixture");
				$sql = "SELECT t_division_ID FROM t_division WHERE t_division_name = \"" . $_POST['fixture_name'] . "\""
					. " AND t_season_ID = " . $_POST['seasonid'];
				$get_fix = get_sqlresult($sql,"Get fixtureID");
				$n_fix = mysql_fetch_array($get_fix);
				$_SESSION['fixtureID'] = $n_fix['t_division_ID'];
				$_SESSION['admin_err'] = "Fixture \"" . $_POST['fixture_name'] . "\" added.";
			}
			break;
		case "New league":
			//Add a league
			//Check if league name is used before
			$sql = "SELECT * FROM t_league WHERE t_league_name = \"". $_POST['league_name'] . "\"";
			$league = get_sqlresult($sql, "Check leagues");
			if (0 < mysql_num_rows($league)) {
				$_SESSION['admin_err'] = "A league exists with the name: " . $_POST['league_name'];
			} else {
				$sql = "INSERT INTO t_league VALUES (NULL, \"" . $_POST['league_name'] . "\")";
				$ins_league = get_sqlresult($sql,"Insert league");
			}
			break;
		case "New season":
			//Add a season
			//Check if season name is used in this league before
			$sql = "SELECT * FROM t_season"
				. " WHERE t_league_ID = " . $_POST['leagueid'] // <-- The league ID for the selected season
				. " AND t_season_name = \"" . $_POST['season_name'] . "\"";
			$season = get_sqlresult($sql, "Check seasons");
			if (0 < mysql_num_rows($season)) {
				$_SESSION['admin_err'] = "League already has a season with the name: " . $_POST['season_name'];
			} else {
				$sql = "INSERT INTO t_season VALUES (NULL," . $_POST['leagueid'] . ",\"" . $_POST['season_name'] . "\", CURDATE())";
				$ins_season = get_sqlresult($sql, "Insert season");
			}
		}
	}
}
function change_league()
{
	echo "<table><tr><td>\n";
	echo "<form method=post action=" . $_SERVER['PHP_SELF'] . ">\n";
	if (isset($_SESSION['fixtureID'])) {
		$r_fix = get_sqlresult('SELECT t_division_name, t_season_ID FROM t_division WHERE t_division_ID = ' . $_SESSION['fixtureID'],'Get fixturename.');
		$n_fix = mysql_fetch_array($r_fix);
		$fix_name = $n_fix['t_division_name'];
		echo "<input type=submit name=del_value value=\"Delete fixture\">"
			. "<input type=hidden name=seasonid value=" . $n_fix['t_season_ID'] . ">\n"
			. "<input type=text size=12 name=fixture_name value=\"" . $fix_name . "\"><input type=submit name=new_value value=\"New fixture\">"
			. "<input type=checkbox name=confirmdelete>confirm delete";
	} else {
		if (!$_POST['league']) {
			echo "<input type=text size=12 name=league_name><input type=submit name=new_value value=\"New league\">";
		} elseif (!$_POST['season']) {
			$r_league = get_sqlresult('SELECT t_league_name FROM t_league WHERE t_league_ID = ' . $_POST['league'],'Get leaguename.');
			$n_league = mysql_fetch_array($r_league);
			$league_name = $n_league['t_league_name'];
			echo "<input type=submit name=del_value value=\"Delete league\">";
			echo "<input type=hidden name=leagueid value=" . $_POST['league'] . ">\n";
			echo "<input type=text size=12 name=season_name value=\"". $league_name . "\"><input type=submit name=new_value value=\"New season\">";
			echo "<input type=checkbox name=confirmdelete>confirm delete";
		} elseif (!$_POST['division']) {
			$r_season = get_sqlresult('SELECT t_season_name, t_season_ID FROM t_season WHERE t_season_ID = ' . $_POST['season'],'Get seasonname.');
			$n_season = mysql_fetch_array($r_season);
			$season_name = $n_season['t_season_name'];
			echo "<input type=submit name=del_value value=\"Delete season\">";
			echo "<input type=hidden name=seasonid value=" . $n_season['t_season_ID'] . ">\n";
			echo "<input type=text size=12 name=fixture_name value=\"". $season_name . "\"><input type=submit name=new_value value=\"New fixture\">";
			echo "<input type=checkbox name=confirmdelete>confirm delete";
		}
	}
	echo "</form>\n";
	echo "</td></tr></table>\n";
}
function rules_skills()
{
	$q_skill = 'SELECT * FROM t_skill ORDER BY t_skill_name';
	$r_skill = mysql_query($q_skill) or die ("Skill query failed! " . mysql_error());
	echo "<table summary=\"Skill table\" width=800 border=1 cellpadding=0 cellspacing=0>\n";
	echo "<tr><th>Skill name</th><th>Description</th></tr>\n";
	while ($n_skill = mysql_fetch_array($r_skill)) {
		echo "<tr><td valign=top><b>" . $n_skill['t_skill_name'] . "</b><br>";
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
	echo "</table>\n";
}
function rules_teams()
{
	// get teams
	$q_team = 'SELECT * FROM t_race';
	$r_team = get_sqlresult($q_team, "Get all team types");
	// for each team do
	while ($team = mysql_fetch_array($r_team)) {
		echo "<center><table width=800><tr><td class=edit>\n";
		echo "<table bgcolor=#FFFFFF>\n<tr>\n<td class=edit><b>";
		echo strtoupper($team['t_race_name']) . " TEAMS";
		echo "</b></td>\n";
		echo "<td rowspan=2><img src=\"viewlogo.php?race=" . $team['t_race_ID'] . "\" width=110>";
		echo "</td>\n</tr>";
		echo "<tr>\n<td class=edit>";
		echo htmlentities($team['t_race_description'],ENT_QUOTES,'UTF-8') . "<br>\n";
		echo "</td>\n</tr>\n<tr>\n<td colspan=2 class=edit>";
		// get positions
		echo "<table>\n<tr>\n<th class=edit>Qty</th><th align=left class=edit>Title</th><th class=edit>Cost</th><th class=edit>MA</th><th class=edit>ST</th><th class=edit>AG</th><th class=edit>AV</th><th align=left class=edit>Skills</th><th class=edit>Normal</th><th class=edit>Double</th>\n</tr>\n";
		$q_pos = 'SELECT * FROM t_positions WHERE t_race_ID = ' . $team['t_race_ID'];
		$r_pos = get_sqlresult($q_pos, "Positions");
		// for each position do
		while ($pos = mysql_fetch_array($r_pos)) {
			echo "<tr>\n";
			echo "<td align=center width=40 class=edit>0-" . $pos['t_positions_qty'] . "</td>";
			echo "<td width=150 class=edit>" . $pos['t_positions_title'] . "</td>";
			echo "<td align=right width=40 class=edit>" . number_format($pos[8]) . "</td>";
			echo "<td align=center width=20 class=edit>" . $pos[4] . "</td>";
			echo "<td align=center width=20 class=edit>" . $pos[5] . "</td>";
			echo "<td align=center width=20 class=edit>" . $pos[6] . "</td>";
			echo "<td align=center width=20 class=edit>" . $pos[7] . "</td>";
			 // get skills
			$q_skill = 'SELECT p.t_skill_ID, t_skill_name, t_skill_desc, t_skill_type FROM `t_positions_skill` p, `t_skill` s'
					. ' WHERE p.t_skill_ID = s.t_skill_ID'
					. ' AND p.t_positions_ID = ' . $pos['t_positions_ID'] . ';';
			$r_skill = mysql_query($q_skill) or die ("Skill query failed");
			// for each skill do
			echo "<td width=250 class=edit>";
			$p_skill = "";
			while ($skill = mysql_fetch_array($r_skill)) {
				$p_skill = $p_skill
					. "<a href=\"javascript:void(0);\" onmouseover=\"Tip('"
					. addslashes(htmlspecialchars_decode($skill['t_skill_desc'],ENT_QUOTES)) . "', PADDING, 5, WIDTH, 600)\" class=nolink>" 
					. $skill['t_skill_name'] . "</a>, " ;
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
			echo "<td class=edit>" . (($n_skill == "") ? "&nbsp;" : $n_skill) . "</td>" ;
			echo "<td class=edit>" . (($d_skill == "") ? "&nbsp;" : $d_skill) . "</td>" ;
			echo "</td>\n</tr>\n";
		}// end
		echo "</table>\n";
		if (isset($_SESSION['userID'])) {
			echo "<form method=post action=new_team.php>";
			echo "<input type=hidden name=raceid value=" . $team['t_race_ID'] . ">";
			echo "<input type=hidden name=racename value=\"" . $team['t_race_name'] . "\">";
			echo "<input type=hidden name=rr_price value=\"" . $team['t_race_rerollprice'] . "\">";
			echo "<input type=submit name=new_team value=\"Create " . $team['t_race_name'] . " team\">";
			echo "</form>\n";
		}
		echo "</td>\n</tr>\n<tr>\n<td class=edit>Re-roll counter: ";
		echo number_format($team['t_race_rerollprice']);
		echo " gold pieces each</td>\n</tr>\n</table>\n";
		echo "</td>\n</tr>\n</table>\n";
		echo "</td>\n</tr>\n<tr>\n<td class=edit>&nbsp;</td>\n</tr>\n<tr>\n<td colspan=2>\n";
	};//end
	echo "</td>\n</tr>\n</table>\n";
}
function body_top()
{
	echo "<body>\n<hr>\n<table width=800 align=center>\n<tr><td width=400 align=center valign=top>\n";
	view_fix($_SESSION['fixtureID']);
	echo "</td>\n<td width=400 align=center valign=top>\n";
	view_user($_SESSION['userID'],$login_err);
	//echo "user-x logout";
	echo "</td>\n</tr>\n</table>\n";
}
function user_forms()
{
	echo "<br><table cellspacing=0 cellpadding=0 width=100%>\n";
	echo "<tr><th>USER</TH><TH>PASS</TH><TH>FULL NAME</TH>";
	echo ( (1==$_SESSION['userschema']) ? "<TH>ROLE</TH>" : "" );
	echo "<TH>&nbsp;</TH></tr>";
	$q_users = "SELECT * FROM t_user, t_schema WHERE t_user.t_schema_id = t_schema.t_schema_id"
		. ( (1==$_SESSION['userschema']) ? '' : ' AND t_user.t_user_ID = ' . $_SESSION['userID']);
	$r_users = mysql_query($q_users) or die(b_error('Get users failed!',$r_users));
	while ($n_users = mysql_fetch_array($r_users)) {
		echo "<tr><td align=center>";
		echo "<form method=post action=" . $_SERVER['PHP_SELF'] . ">";
		echo "<input type=hidden name=\"user_id\" value=" . $n_users['t_user_id'] . ">";
		echo "<input type=text size=8 name=\"user_name\" value=\"" . $n_users['t_user_name'] . "\">";
		echo "</td>\n<td align=center>";
		echo "<input type=password size=8 name=\"user_pass\" value=\"" . $n_users['t_user_pass'] . "\">";
		echo "</td>\n<td align=center>";
		echo "<input type=text size=20 name=\"full_name\" value=\"" . $n_users['t_user_fullname'] . "\">";
		echo "</td>\n";
		if (1==$_SESSION['userschema']) {
			echo "<td align=center>";
			echo "<select name=schema_id>\n";
			echo "<option value=1 ";
			echo (($n_users['t_schema_id'] == 1) ? 'selected' : '' );
			echo ">Commishoner</option>\n";
			echo "<option value=2 ";
			echo (($n_users['t_schema_id'] == 2) ? 'selected' : '' );
			echo ">Coach</option>\n";
			echo "</select>\n";
			echo "</td>\n";
		}
		echo "<td align=center>";
		echo "<input type=submit name=update_user value=\"Update\">";
		echo ( (1==$_SESSION['userschema']) ? "<input type=submit name=del_user value=\"Delete\">" : "" );
		echo "<input type=submit name=chng_pass value=\"Change password\">\n";
		echo "</form>\n";
		echo "</td>\n</tr>";
	}
	if (1==$_SESSION['userschema']) {
		echo "<tr><td>";
		echo "<form method=post action=".$_SERVER['PHP_SELF'].">";
		echo "<input type=text size=8 name=\"user_name\">";
		echo "</td>\n<td>";
		echo "<input type=password size=8 name=\"user_pass\">\n";
		echo "</td>\n<td>";
		echo "<input type=text size=20 name=\"full_name\" value=\"" . $n_users['t_user_fullname'] . "\">";
		echo "</td>\n<td>";
		echo "<select name=schema_id>\n";
		echo "<option value=1>Commishoner</option>\n";
		echo "<option value=2 selected>Coach</option>\n";
		echo "</select>";
		echo "</td>\n<td>";
		echo "<input type=submit name=add_user value=\"Add new\">\n";
		echo "</form>\n";
	}
	echo "</td></tr>\n";
	echo "</table>\n";
}
function user_updates()
// Update users
{
	if (isset($_POST['update_user'])) {
	//
		$sql = "UPDATE t_user SET t_user_name = \"" . $_POST['user_name']
			. "\", t_user_fullname = \"" . $_POST['full_name']
			. "\", t_schema_id = " . $_POST['schema_id']
			. " WHERE t_user_id = " . $_POST['user_id'];
		$r_sql = mysql_query($sql) or die(b_error('Update user failed!',$sql));
		//reload page
		header('refresh: 0');
		header('url: '.$_SESSION['PHP_SELF'],false);
		echo "<body bgcolor=#203264></body></html>";
		die();
	}
	if (isset($_POST['chng_pass'])) {
	//
		$sql = "UPDATE t_user SET t_user_pass = MD5(\"" . $_POST['user_pass'] . "\") WHERE t_user_id = " . $_POST['user_id'];
		$r_sql = mysql_query($sql) or die(b_error('Change password failed!',$sql));
		//reload page
		header('refresh: 0');
		header('url: '.$_SESSION['PHP_SELF'],false);
		echo "<body bgcolor=#203264></body></html>";
		die();
	}
	if (isset($_POST['del_user'])) {
	//
		if ($_POST['user_id'] != 1) {
			$sql = "DELETE FROM t_user WHERE t_user_id = " . $_POST['user_id'];
			$r_sql = mysql_query($sql) or die(b_error('Delete user failed!',$sql));
			//reload page
			header('refresh: 0');
			header('url: '.$_SESSION['PHP_SELF'],false);
			echo "<body bgcolor=#203264></body></html>";
			die();
		}
	}
	if (isset($_POST['add_user'])) {
	//
		$sql = "INSERT INTO t_user VALUES(NULL," 
			. $_POST['schema_id'] . ",\"" 
			. $_POST['user_name']
			. "\", MD5(\"" . $_POST['user_pass'] . "\"),\"" . $_POST['full_name'] . "\")";
		$r_sql = mysql_query($sql) or die(b_error('Add user failed!',$sql));
		//reload page
		header('refresh: 0');
		header('url: '.$_SESSION['PHP_SELF'],false);
		echo "<body bgcolor=#203264></body></html>";
		die();
	}
}
function select_forms()
{
	// Check if fixture is defined
	if (isset($_POST['division'])) {
		$_SESSION['fixtureID'] = $_POST['division'];
		header('refresh: 0');
		header('url: '.$_SESSION['PHP_SELF'],false);
		echo "<body bgcolor=#203264></body></html>";
		die();
	}
	if(isset($_POST['login'])){
		$sql = "select * from t_user u, t_schema s where u.t_schema_id = s.t_schema_id AND t_user_name = '" . $_POST['user'] . "' and t_user_pass = MD5('" . $_POST['pass'] . "')";
		$result = mysql_query($sql) or die(b_error('Select user failed',$sql));
		if (mysql_num_rows($result) >= 1) {
			$line=mysql_fetch_array($result);
			//They have sent us the correct login information!
			$_SESSION['userID'] = $line['t_user_id'];
			$_SESSION['username'] = $_POST['user'];
			$_SESSION['userschema'] = $line[5];
			$_SESSION['userschemaname'] = $line[6];
			//Not in database yet
			//$_SESSION['userrights'] = $line['t_schema_rights'];
			header('refresh: 0');
			header('url: '.$_SESSION['PHP_SELF'],false);
			echo "<body bgcolor=#203264></body>";
			die('<p align=center>Back to <a href="' . $_SESSION['PHP_SELF'] . '">phpCommish</a>.</p>');
			//The user has been redirected back to the main page and it should say they they have logged in!
		}else{
			$_SESSION['login_error'] = "Bad username or password!";
		}
	}
	// Check if user is logged in
	if (isset($_SESSION['userID'])) {
		if (isset($_POST['logout'])) {
			unset($_SESSION['userID']);
			unset($_SESSION['userschema']);
			unset($_SESSION['userrights']);
			// Last siden på nytt
		}
	}
	if (isset($_SESSION['fixtureID'])) {
		if (isset($_POST['changeFix'])) {
			if ($_POST['fixid'] == $_SESSION['fixtureID']) {
//				var_dump($_POST);echo "<br>";var_dump($_SESSION);die();
				unset($_SESSION['fixtureID']);
			} else {
				$_SESSION['fixtureID'] = $_POST['fixid'];
			}
			//- Last siden på nytt
		}
	}
}
function head_std($title)
// Print a std-header with a given title
{
echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=ISO-8859-1\" />
<title>" . $title . "</title>

<script language=\"javascript\" src=\"includes/calendar.js\">
</script>
<style type=\"text/css\">
<!--
body {
	background-color: #203264;
}
body,td,th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 10pt;
	color: #FFFFFF;
}
form { margin-bottom: 0 ; }
.nolink { text-decoration: none; color: #000033 }
.edit { color: #000000 }
.bold { font-family: helvetica;
			font-weight: bold }
.large { font-family: helvetica;
			font-weight: bold;
			font-size: 150% }
.white { color: #FFFFFF }
a:link { color:  #6666FF }
a:visited { color: #666666 }
a:active { color: #999999 }
-->
</style>
<center>
<table>
<tr>
<td>
<IMG SRC=img/bblogo_l.jpg ALT=\"BloodBowl phpCommish\">
</td>
<td>
<a href=rules_teams.php>Rules</a><br>
<a href=league.php>League</a><br>
<a href=admin.php>Admin</a>
</tr>
</table>
</center>
</head>";
}
function results_forms() {
	$injury = array(1 => "si", 2 => "d") ; // Two arrays to convert numbers to the injury text
	$injury[3] = array(1 => "m", 2=> "m,n", 3 => "m,-ma", 4 => "m,-av", 5 => "m,-ag", 6 => "m,-st", 7 => "dead") ;
	if ($_SESSION['fixtureID'] <> $_SESSION['game']['fixtureid']) { // If the fixture is changed, empty the array with the game data
		unset($_SESSION['game']);
	}
	// Display only games that are not approved or filled with data
	$sql = "SELECT t_game_ID, th.t_team_name t_h_team_name, ta.t_team_name t_a_team_name, t_game_scheduled, t_game_status"
		." FROM t_game g, t_team th, t_team ta"
		." WHERE g.t_h_team_ID = th.t_team_ID"
		." AND g.t_a_team_ID = ta.t_team_ID"
		." AND t_division_ID = " . $_SESSION['fixtureID']
		." AND t_game_status = 1"; // Game status 1 equals "Not approved"
	$games = get_sqlresult($sql, "Get games");// Build a dropdown 
	if (0 < mysql_num_rows($games)) {
		echo "<table width=800>\n";
		echo "<tr><td align=center>";
		echo "<form method=post action=" . $_SERVER['PHP_SELF'] . ">\n";
		echo "<select name=gameid onchange=\"this.form.submit()\">\n";
		echo "<option value=0 selected>Choose</option>\n";
		while ($n_games = mysql_fetch_array($games)) {
			echo "<option value=" . $n_games['t_game_ID'] . ">" 
				. $n_games['t_game_scheduled'] . ": " 
				. $n_games['t_h_team_name'] . " - " 
				. $n_games['t_a_team_name'] . "</option>\n";
		}
		echo "</select>";
		echo "</td>\n";
		echo "</form>";  
		echo "</tr>\n";
	} else {
		echo "<P><B>No scheduled or unplayed games in fixture.</B>";
	}
	if (isset($_SESSION['game']['gameid'])) { // gameid get set from the dropdown of unplayed or unfilled games
		if (!isset($_SESSION['game']['home'])) { // If home is not set, then the array is not yet filled with playerdata
			$sql = "SELECT t_player_ID, t_player_num, t_player_name, p.t_positions_ID, t_positions_title FROM t_player p, t_positions po"
				. " WHERE p.t_positions_ID = po.t_positions_ID"
				. " AND t_player_status = 1"
				. " AND t_team_ID = " . $_SESSION['game']['homeid']
				. " ORDER BY t_player_num";
			$players = get_sqlresult($sql, "Get home players.");
			while ($h_players = mysql_fetch_array($players)) {
				$_SESSION['game']['home'][$h_players['t_player_ID']] = array("id" => $h_players['t_player_ID'], "num" => $h_players['t_player_num'], "name" => (("" == $h_players['t_player_name']) ? "Noname" : $h_players['t_player_name']),"position" => $h_players['t_positions_title']); 
			}
			$sql = "SELECT t_player_ID, t_player_num, t_player_name, p.t_positions_ID, t_positions_title FROM t_player p, t_positions po"
				. " WHERE p.t_positions_ID = po.t_positions_ID"
				. " AND t_player_status = 1"
				. " AND t_team_ID = " . $_SESSION['game']['awayid']
				. " ORDER BY t_player_num";
			$players = get_sqlresult($sql, "Get away players.");
			while ($h_players = mysql_fetch_array($players)) {
				$_SESSION['game']['away'][$h_players['t_player_ID']] = array("id" => $h_players['t_player_ID'], "num" => $h_players['t_player_num'], "name" => (("" == $h_players['t_player_name']) ? "Noname" : $h_players['t_player_name']),"position" => $h_players['t_positions_title']); 
			}
		}
		echo "<table border=1 cellspacing=0 cellpadding=0 width=800>\n";
			echo "<form method=post action=" . $_SERVER['PHP_SELF'] . ">\n";
			echo "<tr><th colspan=2>GAME DATA</th></tr>\n";
			echo "<tr><td align=center colspan=2 height=24>Game scheduled: " . $_SESSION['game']['scheduled'] . "</td></tr>\n";
			echo "<tr><td align=center colspan=2>Game played: <input type=text name=played value=\"" . ( isset($_SESSION['game']['played']) ? $_SESSION['game']['played'] : $_SESSION['game']['scheduled'] ) . "\" size=10 onchange=\"this.form.submit()\">"
				. "</td></tr>\n";
			echo "<tr><td align=center colspan=2>Game gate: <input type=text name=gate value=\"" . $_SESSION['game']['gate'] . "\" size=10 onChange=\"this.form.submit()\"></td></tr>\n";
			echo "</form>";
			echo "<tr><td align=center valign=top>\n";
			echo "<P><B>" . strtoupper($_SESSION['game']['homename']) . "</B>";
			echo "<table width=400>\n";
				foreach ($_SESSION['game']['home'] as $this_player) {
					// Do some stuff
					echo "<tr><td colspan=2>" . $this_player['num'] . ". " . $this_player['name'] . ", " . $this_player['position'] . "</td></tr>\n";
					if (isset($this_player['cp'])) {
						echo "<tr><td align=right><I>"
							. (("" <> $this_player['cp']) ? $this_player['cp'] . "/cp " : "")
							. (("" <> $this_player['td']) ? $this_player['td'] . "/td " : "")
							. (("" <> $this_player['in']) ? $this_player['in'] . "/in " : "")
							. (("" <> $this_player['ca']) ? $this_player['ca'] . "/ca " : "")
							. (("" <> $this_player['mv']) ? $this_player['mv'] . "/mv " : "")
							. (("" <> $this_player['rs']) ? $this_player['rs'] . "/rs " : "")
							. (("" <> $this_player['ps']) ? $this_player['ps'] . "/ps " : "")
							. (("" <> $this_player['bl']) ? $this_player['bl'] . "/bl " : "")
							. (("" <> $this_player['fl']) ? $this_player['fl'] . "/fl " : "")
							. ((0 <> $this_player['inj']) ? $injury[$this_player['inj']] . ":" . $injury[3][$this_player['inj_eff']] : "");
						echo "</I></td><td>";
						echo "<form method=post action=" . $_SERVER['PHP_SELF'] . ">\n";
						echo "<input type=hidden name=playerid value=" . $this_player['id'] . ">\n";
						echo "<input type=submit name=clear_h value=X>\n";
						echo "</form></td></tr>\n";
					}
				}
			echo "</table>\n";
			echo "</td><td align=center valign=top>\n";
			echo "<P><B>" . strtoupper($_SESSION['game']['awayname']) . "</B>";
			echo "<table width=400>\n";
				foreach ($_SESSION['game']['away'] as $this_player) {
					// Do some stuff
					echo "<tr><td colspan=2>" . $this_player['num'] . ". " . $this_player['name'] . ", " . $this_player['position'] . "</td></tr>\n";
					if (isset($this_player['cp'])) {
						echo "<tr><td align=right><I>" 
							. (("" <> $this_player['cp']) ? $this_player['cp'] . "/cp " : "")
							. (("" <> $this_player['td']) ? $this_player['td'] . "/td " : "")
							. (("" <> $this_player['in']) ? $this_player['in'] . "/in " : "")
							. (("" <> $this_player['ca']) ? $this_player['ca'] . "/ca " : "")
							. (("" <> $this_player['mv']) ? $this_player['mv'] . "/mv " : "")
							. (("" <> $this_player['rs']) ? $this_player['rs'] . "/rs " : "")
							. (("" <> $this_player['ps']) ? $this_player['ps'] . "/ps " : "")
							. (("" <> $this_player['bl']) ? $this_player['bl'] . "/bl " : "")
							. (("" <> $this_player['fl']) ? $this_player['fl'] . "/fl " : "")
							. ((0 <> $this_player['inj']) ? $injury[$this_player['inj']] . ":" . $injury[3][$this_player['inj_eff']] : "");
						echo "</I></td><td>";
						echo "<form method=post action=" . $_SERVER['PHP_SELF'] . ">\n";
						echo "<input type=hidden name=playerid value=" . $this_player['id'] . ">\n";
						echo "<input type=submit name=clear_a value=X>\n";
						echo "</form></td></tr>\n";
					}
				}
			echo "</table>\n";
			echo "</td></tr>\n";
			echo "<tr><td align=center>\n";
			if (($_SESSION['game']['homeuserid']==$_SESSION['userID']) || ($_SESSION['userschema']==1)) {
				echo "<table cellspacing=0 cellpadding=0>\n";
					echo "<tr><th><a href=\"javascript:void(0);\" onmouseover=\"Tip('Player number')\" class=nolink>No</a></th><th><a href=\"javascript:void(0);\" onmouseover=\"Tip('Complete passes')\" class=nolink>CP</a></th><th><a href=\"javascript:void(0);\" onmouseover=\"Tip('Touchdowns')\" class=nolink>TD</a></th><th><a href=\"javascript:void(0);\" onmouseover=\"Tip('Interceptions')\" class=nolink>IN</a></th><th><a href=\"javascript:void(0);\" onmouseover=\"Tip('Casualities')\" class=nolink>CA</a></th><th><a href=\"javascript:void(0);\" onmouseover=\"Tip('Most valuable player')\" class=nolink>MV</a></th><th><a href=\"javascript:void(0);\" onmouseover=\"Tip('Injury')\" class=nolink>IJ</a></th><th><a href=\"javascript:void(0);\" onmouseover=\"Tip('Injury effect')\" class=nolink>IJ ef</a></th></tr>\n";
					echo "<form method=post action=" . $_SERVER['PHP_SELF'] . ">\n";
					echo "<tr><td>";
					echo "<input type=hidden name=home value=1>";
					echo "<select name=playerid>\n";
					$h_cas = 0; $h_td = 0;
					foreach ($_SESSION['game']['home'] as $this_player) {
						// Do some stuff
						$h_cas = $h_cas + $this_player['ca'];
						$h_td = $h_td + $this_player['td'];
						echo "<option value=" . $this_player['id'] . ">" . $this_player['num'] . "</option>"; 
					}
					echo "</select>";
					echo "</td>";
					echo "<td><input type=text name=cpl size=2></td><td><input type=text name=td size=2></td><td><input type=text name=int size=2></td><td><input type=text name=cas size=2></td><td><input type=text name=mvp size=2></td>";
					echo "<td><select name=inj><option value=0>&nbsp;</option><option value=1>si</option><option value=2>d</option></select></td><td><select name=inj_type><option value=0>&nbsp;</option><option value=1>m</option><option value=2>m,n</option><option value=3>m,-ma</option><option value=4>m,-av</option><option value=5>m,-ag</option><option value=6>m,-st</option><option value=7>dead</option></select></td></tr>\n";
					echo "<tr><th>&nbsp;</th><th><a href=\"javascript:void(0);\" onmouseover=\"Tip('Rushes')\" class=nolink>RS</a></th><th><a href=\"javascript:void(0);\" onmouseover=\"Tip('Attemted passes')\" class=nolink>PS</a></th><th><a href=\"javascript:void(0);\" onmouseover=\"Tip('Blocks')\" class=nolink>BL</a></th><th><a href=\"javascript:void(0);\" onmouseover=\"Tip('Fouls')\" class=nolink>FL</a></th></tr>\n";
					echo "<tr><td>&nbsp;</td><td><input type=text name=rush size=2></td><td><input type=text name=pass size=2></td><td><input type=text name=block size=2></td><td><input type=text name=foul size=2></td>";
					echo "<td colspan=3 align=right><input type=submit name=sub_player value=\"Add\"></td></tr>";
					echo "</form>";
				echo "</table>\n";
			}
			echo "</td><td align=center>";
			if (($_SESSION['game']['awayuserid']==$_SESSION['userID']) || ($_SESSION['userschema']==1)) {
				echo "<table cellspacing=0 cellpadding=0>\n";
					echo "<tr><th><a href=\"javascript:void(0);\" onmouseover=\"Tip('Player number')\" class=nolink>No</a></th><th><a href=\"javascript:void(0);\" onmouseover=\"Tip('Complete passes')\" class=nolink>CP</a></th><th><a href=\"javascript:void(0);\" onmouseover=\"Tip('Touchdowns')\" class=nolink>TD</a></th><th><a href=\"javascript:void(0);\" onmouseover=\"Tip('Interceptions')\" class=nolink>IN</a></th><th><a href=\"javascript:void(0);\" onmouseover=\"Tip('Casualities')\" class=nolink>CA</a></th><th><a href=\"javascript:void(0);\" onmouseover=\"Tip('Most valuable player')\" class=nolink>MV</a></th><th><a href=\"javascript:void(0);\" onmouseover=\"Tip('Injury')\" class=nolink>IJ</a></th><th><a href=\"javascript:void(0);\" onmouseover=\"Tip('Injury effect')\" class=nolink>IJ ef</a></th></tr>\n";
					echo "<form method=post action=" . $_SERVER['PHP_SELF'] . ">\n";
					echo "<tr><td>";
					echo "<input type=hidden name=away value=1>";
					echo "<select name=playerid>\n";
					$a_cas = 0; $a_td = 0;
					foreach ($_SESSION['game']['away'] as $this_player) {
						// Do some stuff
						$a_cas = $a_cas + $this_player['ca'];
						$a_td = $a_td + $this_player['td'];
						echo "<option value=" . $this_player['id'] . ">" . $this_player['num'] . "</option>"; 
					}
					echo "</select>";
					echo "</td>";
					echo "<td><input type=text name=cpl size=2></td><td><input type=text name=td size=2></td><td><input type=text name=int size=2></td><td><input type=text name=cas size=2></td><td><input type=text name=mvp size=2></td>";
					echo "<td><select name=inj><option value=0>&nbsp;</option><option value=1>si</option><option value=2>d</option></select></td><td><select name=inj_type><option value=0>&nbsp;</option><option value=1>m</option><option value=2>m,n</option><option value=3>m,-ma</option><option value=4>m,-av</option><option value=5>m,-ag</option><option value=6>m,-st</option><option value=7>dead</option></select></td></tr>\n";
					echo "<tr><th>&nbsp;</th><th><a href=\"javascript:void(0);\" onmouseover=\"Tip('Rushes')\" class=nolink>RS</a></th><th><a href=\"javascript:void(0);\" onmouseover=\"Tip('Attemted passes')\" class=nolink>PS</a></th><th><a href=\"javascript:void(0);\" onmouseover=\"Tip('Blocks')\" class=nolink>BL</a></th><th><a href=\"javascript:void(0);\" onmouseover=\"Tip('Fouls')\" class=nolink>FL</a></th></tr>\n";
					echo "<tr><td>&nbsp;</td><td><input type=text name=rush size=2></td><td><input type=text name=pass size=2></td><td><input type=text name=block size=2></td><td><input type=text name=foul size=2></td>";
					echo "<td colspan=3 align=right><input type=submit name=sub_player value=\"Add\"></td></tr>";
					echo "</form>";
				echo "</table>\n";
			}
			echo "</td></tr>\n";
			echo "<tr><td align=center class=large colspan=2>\n";
			echo "Touchdowns: " . $h_td . " - " . $a_td;
			echo "</td></tr><tr><td align=center class=large colspan=2>\n";
			echo "Casualities: " . $h_cas . " - " . $a_cas;
			echo "</td></tr><tr><td align=center>\n";
			if (($_SESSION['game']['homeuserid']==$_SESSION['userID']) || ($_SESSION['userschema']==1)) {
				echo "<form method=post action=" . $_SERVER['PHP_SELF'] . ">\n";
				echo "Winnings: <input type=text name=h_winnings align=right value=\"" . $_SESSION['game']['h_win'] . "\" size=12 onChange=\"this.form.submit()\">\n";
				echo " Fanfactor: -1<input type=radio name=h_ff value=-1" . ($_SESSION['game']['homeff'] == -1 ? " checked>" : " onChange=\"this.form.submit()\">") ;
				echo " 0<input type=radio name=h_ff value=0" . ($_SESSION['game']['homeff'] == 0 ? " checked>" : " onChange=\"this.form.submit()\">") ;
				echo " +1<input type=radio name=h_ff value=1" . ($_SESSION['game']['homeff'] == 1 ? " checked>" : " onChange=\"this.form.submit()\">") ;
				echo "</form>\n";
			}
			echo " </td><td align=center> \n";
			if (($_SESSION['game']['awayuserid']==$_SESSION['userID']) || ($_SESSION['userschema']==1)) {
				echo "<form method=post action=" . $_SERVER['PHP_SELF'] . ">\n";
				echo "Winnings: <input type=text name=a_winnings align=right value=\"" . $_SESSION['game']['a_win'] . "\" size=12 onChange=\"this.form.submit()\">\n";
				echo " Fanfactor: -1<input type=radio name=a_ff value=-1" . ($_SESSION['game']['awayff'] == -1 ? " checked>" : " onChange=\"this.form.submit()\">") ;
				echo " 0<input type=radio name=a_ff value=0" . ($_SESSION['game']['awayff'] == 0 ? " checked>" : " onChange=\"this.form.submit()\">") ;
				echo " +1<input type=radio name=a_ff value=1" . ($_SESSION['game']['awayff'] == 1 ? " checked>" : " onChange=\"this.form.submit()\">") ;
				echo "</form>\n";
			}
			echo "</td></tr>\n";
			echo "<tr><td colspan=2 align=center>\n";
			if (($_SESSION['game']['homeuserid']==$_SESSION['userID']) || ($_SESSION['game']['awayuserid']==$_SESSION['userID']) || ($_SESSION['userschema']==1)) {
				echo "<BR><B>Import javabowl results</B>";
				echo "<form method=post action=" . $_SERVER['PHP_SELF'] . " enctype=\"multipart/form-data\">\n";
				echo "<input type=hidden name=readfile value=1>\n";
				echo "Select file, or type filename: ";
				echo "<input type=\"file\" name=\"results\" class=white onChange=\"this.form.submit()\"><br>";
				//echo "<input type=\"submit\" value=\"Send\">";
				echo "</form>\n";
			}
			echo "</td></tr>\n";
			echo "<tr><td>\n";
			echo "<form method=post action=" . $_SERVER['PHP_SELF'] . ">\n";
			echo "<input type=submit name=clear value=\"Clear game-data\">\n";
			echo "</td><td align=right>\n";
			echo "<input type=submit name=save value=\"Save game-data\">\n";
			echo "</td></tr>\n";
		echo "</table>";
		echo "</td></tr>\n";
	echo "</table>\n";
	}
}
function view_fix($fixID)
// Display the selected fixture and the means to change it if the fixID is set, if not display the form to select fixture
{
	if (isset($fixID)) {
		$q_division = 'SELECT t_league_name, l.t_league_ID, t_season_name, s.t_season_ID, t_division_name, d.t_division_ID'
			. ' FROM t_division d , t_season s , t_league l '
	        . ' WHERE d.t_division_ID = ' . $fixID
	        . ' AND d.t_season_ID = s.t_season_ID '
	        . ' AND s.t_league_ID = l.t_league_ID ';
		$r_division = mysql_query($q_division) or die ("Division query failed");
		$n_division = mysql_fetch_array($r_division);
		//    Display the selected league, season and division*/
		echo "<form method=post action=\"" . $_SERVER['PHP_SELF'] . "\">\n";
		echo "<b><i>" . $n_division['t_league_name'] . " / " . $n_division['t_season_name'] . " / \n";
		$q_fix = 'SELECT t_division_ID, t_division_name FROM t_division WHERE t_season_ID = ' . $n_division['t_season_ID'];
		$r_fix = get_sqlresult($q_fix, "Get fixture options");
		echo "<select name=fixid onchange=\"this.form.submit()\">\n";
		while ($n_fix = mysql_fetch_array($r_fix)) {
			echo "<option value=" . $n_fix['t_division_ID'] . (($n_division['t_division_ID']==$n_fix['t_division_ID']) ? " selected":"") . ">" . $n_fix['t_division_name'] . "</option>\n";
		}
		echo "</select>";
		//echo $n_division['t_division_name'] . "</i></b>";
		echo " <input type=hidden name=\"changeFix\" value=\"1\"><input type=submit name=submit value=\"Change fixture\"></form>";
	} else {
	echo "<form method=post action=" . $_SERVER['PHP_SELF'] . ">";
		if (!$_POST['league']) {
			// :If no league are selected:
			//  View the select league dropdown
			$q_league = 'SELECT * FROM t_league';
			$r_league = mysql_query($q_league) or die ("League query failed");
			echo "League: <select name=league onchange=\"this.form.submit()\">\n";
			echo "<option value=0 selected>Choose</option>";
			while ($n_league = mysql_fetch_array($r_league)) {
				echo "<option value=" . $n_league['t_league_ID'] . ">" . $n_league['t_league_name'] . "</option>\n";
			}
		}	elseif (!$_POST['season']) {
			// ::if no season are selected::
			//   Display the selected league
			$q_league = 'SELECT t_league_name FROM t_league WHERE t_league_ID = ' . $_POST['league'];
			$r_league = mysql_query($q_league) or die ("League query failed");
			$n_league = mysql_fetch_array($r_league);
			$league_name = $n_league['t_league_name'];
			echo "<input type=hidden name=\"league\" value=\"" . $_POST['league'] . "\">";
			echo "<input type=hidden name=\"league_name\" value=\"" . $league_name . "\">";
			echo "<b><i>" . $league_name . " / </i></b>";
			//   View the select season dropdown
			$q_season = 'SELECT * FROM t_season WHERE t_league_ID = ' . $_POST['league'] . ' ORDER BY t_season_start DESC';
			$r_season = mysql_query($q_season) or die ("Season query failed");
			echo "Season: <select name=season onchange=\"this.form.submit()\">\n";
			echo "<option value=0 selected>Choose</option>";
			while ($n_season = mysql_fetch_array($r_season)) {
				echo "<option value=" . $n_season['t_season_ID'] . ">" . $n_season['t_season_name'] . "</option>\n";
			}
		} elseif (!$_POST['division']) {
			// if no division are selected::
			// Display the selected league and season
			$q_league = 'SELECT t_league_name FROM t_league WHERE t_league_ID = ' . $_POST['league'];
			$r_league = mysql_query($q_league) or die ("League query failed");
			$n_league = mysql_fetch_array($r_league);
			$league_name = $n_league['t_league_name'];
			$q_season = 'SELECT t_season_name FROM t_season WHERE t_season_ID = ' . $_POST['season'];
			$r_season = mysql_query($q_season) or die ("Season query failed");
			$n_season = mysql_fetch_array($r_season);
			$season_name = $n_season['t_season_name'];
			echo "<input type=hidden name=\"league\" value=\"" . $_POST['league'] . "\">";
			echo "<input type=hidden name=\"league_name\" value=\"" . $_POST['league_name'] . "\">";
			echo "<input type=hidden name=\"season\" value=\"" . $_POST['season'] . "\">";
			echo "<input type=hidden name=\"season_name\" value=\"" . $season_name . "\">";
			echo "<b><i>" . $league_name . " / " . $season_name . " / </i></b>";
			//	  Display the select division dropdown
			$q_division = 'SELECT * FROM t_division WHERE t_season_ID = ' . $_POST['season'];
			$r_division = mysql_query($q_division) or die ("fixture query failed");
			echo "Fixture: <select name=division onchange=\"this.form.submit()\">\n";
			echo "<option value=0 selected>Choose</option>";
			while ($n_division = mysql_fetch_array($r_division)) {
				echo "<option value=" . $n_division['t_division_ID'] . ">" . $n_division['t_division_name'] . "</option>\n";
			}
		}
		echo "</select>\n</form>\n";
	}
}

function view_user($userID,$login_err)
// Display the user logged in identified by the userID, if userID is not set display the logon form.
{
	echo "<form method=post action=".$_SERVER['PHP_SELF'].">";
	if (isset($userID)) {
		echo "User: " . $_SESSION['username'] . ", " . $_SESSION['userschemaname'] . " <input type=hidden name=\"logout\" value=\"1\"><input type=submit name=submit value=\"logout\"></form>";
	} else {
		echo $_SESSION['login_error'];
		unset($_SESSION['login_error']);
		echo "<table>\n";
		echo "<tr>\n";
		echo "<td align=right>Username:</td><td><input type=text name=user size=8></td>\n";
		echo "<td align=right>Password:</td><td><input type=password name=pass size=8></td>\n";
		echo "<td><input type=submit name=login value=\"Login!\"></td>\n</tr>\n";
		echo "</table>\n</form>\n";
	}
}

function printroster($team,$jbb)
// Prints a team roster given the team id
{
$nbsp = '&nbsp;';
echo "<TABLE BORDER=1 BGCOLOR=\"#FFFFD0\" BORDERCOLORLIGHT=\"#FFFFF0\" BORDERCOLORDARK=\"#BFBF90\">";
echo "<FONT FACE=\"helvetica\">";
echo "<TR BGCOLOR=\"#D0D0A0\"><TH>#</TH><TH>Player Name</TH><TH>Position</TH><TH>ma</TH><TH>st</TH><TH>ag</TH><TH>av</TH><TH>Player Skills</TH><TH>ij</TH><TH>cp</TH><TH>td</TH><TH>in</TH><TH>cs</TH><TH>vp</TH><TH>sp</TH><TH>Cost</TH></TR>";
// : Get and display the players :
  $q_team_d = 'SELECT t_team_name, t_team_coach, t_team_rerolls, t_team_fanfactor, t_team_assistant_coaches, t_team_cheerleaders, t_team_apothecary, t_team_treasury, t.t_race_ID, t_race_name, t_race_rerollprice FROM t_team t, t_race r'
            . ' WHERE t_team_ID = ' . $team . ' AND t.t_race_ID = r.t_race_ID';
  $r_team_d = mysql_query($q_team_d) or die ("Team details query failed! " . mysql_error());
  $n_team_d = mysql_fetch_array($r_team_d);
  $race_id = $n_team_d['t_race_ID'];
  $q_player = 'SELECT * FROM t_player pl, t_positions po WHERE t_team_ID = ' . $team
  			. ' AND pl.t_positions_ID = po.t_positions_ID'
  			. ' AND pl.t_player_status = 1'
			. ' ORDER BY t_player_num';
  $r_player = mysql_query($q_player) or die('Error fetching players! ' . mysql_error());
  $n_player = mysql_fetch_array($r_player);
  for ($p_num = 1; $p_num <= 16; $p_num++) {
  	echo "\n<TR ALIGN=CENTER><TD>" . $p_num . "</TD>";
  	if ($n_player['t_player_num']==$p_num) {
		echo "<TD ALIGN=LEFT>" . (($n_player['t_player_name']) ? $n_player['t_player_name'] : $nbsp) . "</TD>";
		echo "<TD ALIGN=LEFT>" . $n_player['t_positions_title'] . "</TD>";
		echo ($n_player['t_player_MA_mod'] != 0) ? "<TD><B>" : "<TD>" ;
		echo $n_player['t_positions_MA'] + $n_player['t_player_MA_mod'] ;
		echo ($n_player['t_player_MA_mod'] != 0) ? "</B></TD>" : "</TD>" ;

		echo ($n_player['t_player_ST_mod'] != 0) ? "<TD><B>" : "<TD>" ;
		echo $n_player['t_positions_ST'] + $n_player['t_player_ST_mod'] ;
		echo ($n_player['t_player_ST_mod'] != 0) ? "</B></TD>" : "</TD>" ;

		echo ($n_player['t_player_AG_mod'] != 0) ? "<TD><B>" : "<TD>" ;
		echo $n_player['t_positions_AG'] + $n_player['t_player_AG_mod'] ;
		echo ($n_player['t_player_AG_mod'] != 0) ? "</B></TD>" : "</TD>" ;

		echo ($n_player['t_player_AV_mod'] != 0) ? "<TD><B>" : "<TD>" ;
		echo $n_player['t_positions_AV'] + $n_player['t_player_AV_mod'] ;
		echo ($n_player['t_player_AV_mod'] != 0) ? "</B></TD>" : "</TD>" ;

		echo "<TD ALIGN=LEFT>";
  		if ($n_player['t_player_MA_mod'] != 0) {
				 $skill_list = "ma" . sprintf("%+d, ", $n_player['t_player_MA_mod']) ;
		}
  		if ($n_player['t_player_ST_mod'] != 0) {
				 $skill_list =  $skill_list . "st" . sprintf("%+d, ", $n_player['t_player_ST_mod']) ;
		}
  		if ($n_player['t_player_AG_mod'] != 0) {
				 $skill_list =  $skill_list . "ag" . sprintf("%+d, ", $n_player['t_player_AG_mod']) ;
		}
  		if ($n_player['t_player_AV_mod'] != 0) {
				 $skill_list =  $skill_list . "av" . sprintf("%+d, ", $n_player['t_player_AV_mod']) ;
		}

		// : List positions skills :
		$skill_list = "";
		if (1==$jbb){ // Shall the skill list be compatible with SkiJunkies JavaBloodBowl client?
			$t_skill_name = 't_skill_name_j';
		} else {
			$t_skill_name = 't_skill_name';
		}
		$q_skill = 'SELECT ' . $t_skill_name . ', t_skill_desc FROM t_positions_skill ps, t_skill s WHERE t_positions_ID = ' . $n_player['t_positions_ID']
						.  ' AND ps.t_skill_ID = s.t_skill_ID ORDER BY t_skill_name';
  		$r_skill = mysql_query($q_skill) or die('Error fetching positions skills! ' . mysql_error() . '\n' . $q_skill);
  		while($n_skill = mysql_fetch_array($r_skill)){
  			$skill_list =  $skill_list . "<i>" . $n_skill[0] . "</i>, ";
  		} // while

  		// : List skills :

  		$q_skill = 'SELECT ' . $t_skill_name . ', t_skill_desc FROM t_player_skill ps, t_skill s WHERE t_player_ID = ' . $n_player['t_player_ID']
  				.  ' AND ps.t_skill_ID = s.t_skill_ID ORDER BY t_skill_name';
  		$r_skill = mysql_query($q_skill) or die('Error fetching skills! ' . mysql_error());
  		$p_value = $n_player['t_positions_price'];
  		while($n_skill = mysql_fetch_array($r_skill)){
  			$skill_list =  $skill_list . $n_skill[0] . ", ";
  		} // while
		if ($skill_list == "" ) {
		  echo $nbsp ;
		} else {
		  $skill_list = substr($skill_list,0,strlen($skill_list)-2);
		}
  		echo $skill_list;
  		echo "</TD>";
		// :Niggling/Miss game ************* modified by Tom
		  echo "<TD>";
		if (($n_player['t_player_niggling'] == 0) && ($n_player['t_player_miss'] == 0)) { echo $nbsp; }
		if ($n_player['t_player_miss'] == 1) {
		    echo "m";
		    if ($n_player['t_player_niggling'] > 0) {
		        for ($i=1; $i<=$n_player['t_player_niggling']; $i++) { echo ",n"; }
		    }
		} else {
		    if ($n_player['t_player_niggling'] > 0) {
		        echo "n";
		        for ($i=2; $i<=$n_player['t_player_niggling']; $i++) { echo ",n"; }
		    }
		}

		//echo ($n_player['t_player_niggling'] == 1) ? "N" : "" ;
		//echo ($n_player['t_player_miss'] == 1) ? "M" : "" ;
  		echo "</TD>";
  		echo "<TD>" . (($n_player['t_player_comp']) ? $n_player['t_player_comp'] : $nbsp) . "</TD>";// CP
  		echo "<TD>" . (($n_player['t_player_td']) ? $n_player['t_player_td'] : $nbsp) . "</TD>";// TD
  		echo "<TD>" . (($n_player['t_player_int']) ? $n_player['t_player_int'] : $nbsp) . "</TD>";// IN
  		echo "<TD>" . (($n_player['t_player_cas']) ? $n_player['t_player_cas'] : $nbsp) . "</TD>";// CA
  		echo "<TD>" . (($n_player['t_player_mvp']) ? $n_player['t_player_mvp'] : $nbsp) . "</TD>";// MVP
			$player_spp =  $n_player['t_player_comp'] + $n_player['t_player_td'] * 3 + $n_player['t_player_int'] * 2 + $n_player['t_player_cas'] * 2 + $n_player['t_player_mvp'] * 5 ;
  		echo "<TD>" . $player_spp . "</TD>";// SPP
  		$player_price = $n_player['t_positions_price'] + $n_player['t_player_priceadd'] ;
  		echo "<TD ALIGN=RIGHT>" . (($n_player['t_player_priceadd']) ? '<B>':'') . $player_price . "</B></TD></TR>";//Player value
     $n_player = mysql_fetch_array($r_player);
  	} else {
		echo "<TD>&nbsp;</TD><TD>&nbsp;</TD><TD>&nbsp;</TD><TD>&nbsp;</TD><TD>&nbsp;</TD><TD>&nbsp;</TD><TD>&nbsp;</TD><TD>&nbsp;</TD><TD>&nbsp;</TD><TD>&nbsp;</TD><TD>&nbsp;</TD><TD>&nbsp;</TD><TD>&nbsp;</TD><TD>&nbsp;</TD><TD>&nbsp;</TD></TR>";
	}
  }
  $q_player = 'SELECT sum(t_positions_price) price, sum(t_player_priceadd) priceadd FROM t_player pl, t_positions po WHERE t_team_ID = ' . $team
        . ' AND pl.t_positions_ID = po.t_positions_ID'
        . ' AND t_player_status = 1'
        . ' GROUP BY t_team_ID';
  $r_player = mysql_query($q_player) or die('Error totaling player values! ' . mysql_error());
  $n_player = mysql_fetch_array($r_player);
  $team_price = $n_player['price']
  				+ $n_player['priceadd']
  				+ $n_team_d['t_race_rerollprice'] * $n_team_d['t_team_rerolls']
				+ $n_team_d['t_team_fanfactor'] * 10000
				+ $n_team_d['t_team_assistant_coaches'] * 10000
				+ $n_team_d['t_team_cheerleaders'] * 10000
				+ $n_team_d['t_team_apothecary'] * 50000 ;
  $team_rating = $team_price / 10000;
  $q_race = 'SELECT t_race_name FROM t_team t JOIN t_race r ON t.t_race_ID = r.t_race_ID WHERE t_team_ID = ' . $team;
  $r_race = mysql_query($q_race) or die('Error getting race name! ' . mysql_error());
  $n_race = mysql_fetch_array($r_race);
?>
<TR ALIGN=CENTER><TD ALIGN=RIGHT BGCOLOR="#D0D0A0" COLSPAN=2>Team Name:</TD><TD COLSPAN=5><?php echo (($n_team_d['t_team_name']) ? $n_team_d['t_team_name'] : $nbsp); ?></TD><TD ALIGN=RIGHT BGCOLOR="#D0D0A0" COLSPAN=2>Re-Rolls:</TD><TD COLSPAN=2><?php echo (($n_team_d['t_team_rerolls']) ? $n_team_d['t_team_rerolls'] : $nbsp ); ?></TD><TD BGCOLOR="#D0D0A0" COLSPAN=4>x $<?php echo $n_team_d['t_race_rerollprice'] / 1000 ; ?>k =</TD><TD ALIGN=RIGHT><?php echo $n_team_d['t_race_rerollprice'] * $n_team_d['t_team_rerolls'] ; ?></TD></TR>
<TR ALIGN=CENTER><TD ALIGN=RIGHT BGCOLOR="#D0D0A0" COLSPAN=2>Race:</TD><TD COLSPAN=5><?php echo ucwords(strtolower($n_race['t_race_name'])); ?></TD><TD ALIGN=RIGHT BGCOLOR="#D0D0A0" COLSPAN=2>Fan Factor:</TD><TD COLSPAN=2><?php echo (($n_team_d['t_team_fanfactor']) ? $n_team_d['t_team_fanfactor'] : $nbsp ); ?></TD><TD BGCOLOR="#D0D0A0" COLSPAN=4>x $10k =</TD><TD ALIGN=RIGHT><?php echo $n_team_d['t_team_fanfactor'] * 10000 ; ?></TD></TR>
<TR ALIGN=CENTER><TD ALIGN=RIGHT BGCOLOR="#D0D0A0" COLSPAN=2>Team Rating:</TD><TD COLSPAN=5><?php echo $team_rating; ?></TD><TD ALIGN=RIGHT BGCOLOR="#D0D0A0" COLSPAN=2>Assistant Coaches:</TD><TD COLSPAN=2><?php echo (($n_team_d['t_team_assistant_coaches']) ? $n_team_d['t_team_assistant_coaches'] : $nbsp ); ?></TD><TD BGCOLOR="#D0D0A0" COLSPAN=4>x $10k =</TD><TD ALIGN=RIGHT><?php echo $n_team_d['t_team_assistant_coaches'] * 10000; ?></TD></TR>
<TR ALIGN=CENTER><TD ALIGN=RIGHT BGCOLOR="#D0D0A0" COLSPAN=2>Treasury:</TD><TD COLSPAN=5><?php echo (($n_team_d['t_team_treasury']) ? $n_team_d['t_team_treasury'] : $nbsp); ?></TD><TD ALIGN=RIGHT BGCOLOR="#D0D0A0" COLSPAN=2>Cheerleaders:</TD><TD COLSPAN=2><?php echo (($n_team_d['t_team_cheerleaders']) ? $n_team_d['t_team_cheerleaders'] : $nbsp); ?></TD><TD BGCOLOR="#D0D0A0" COLSPAN=4>x $10k =</TD><TD ALIGN=RIGHT><?php echo $n_team_d['t_team_cheerleaders'] * 10000; ?></TD></TR>
<TR ALIGN=CENTER><TD ALIGN=RIGHT BGCOLOR="#D0D0A0" COLSPAN=2>Coach:</TD><TD COLSPAN=5><?php echo (($n_team_d['t_team_coach']) ? $n_team_d['t_team_coach'] : $nbsp); ?></TD><TD ALIGN=RIGHT BGCOLOR="#D0D0A0" COLSPAN=2>Apothecary:</TD><TD COLSPAN=2><?php echo (($n_team_d['t_team_apothecary']) ? $n_team_d['t_team_apothecary'] : $nbsp) ; ?></TD><TD BGCOLOR="#D0D0A0" COLSPAN=4>x $50k =</TD><TD ALIGN=RIGHT><?php echo $n_team_d['t_team_apothecary'] * 50000 ; ?></TD></TR>
<TR ALIGN=CENTER><TD COLSPAN=7 ROWSPAN=2>&nbsp;</TD><TD ALIGN=RIGHT BGCOLOR="#D0D0A0" COLSPAN=2>Team Wizard:</TD><TD COLSPAN=2>0</TD><TD BGCOLOR="#D0D0A0" COLSPAN=4>x $50k =</TD><TD ALIGN=RIGHT>0</TD></TR>
<TR ALIGN=CENTER><TD ALIGN=RIGHT BGCOLOR="#D0D0A0" COLSPAN=8>TOTAL COST OF TEAM:</TD><TD ALIGN=RIGHT><?php echo $team_price; ?></FONT></TD></TR>
</TABLE>
<?php
}
function viewtable($division){
// Prints a table of the league standings in a given division
	// sql to use when selecting from views (does not work as there is no views currently)
	$sql = 'SELECT * FROM v_table WHERE t_division_ID = ' . $division ;
	$r_tab = mysql_query($sql) or die (b_error("View table query failed!\n",$sql));
	echo "<table summary=\"Results table\" border=1 cellpadding=1 cellspacing=0>\n";
	echo "<tr><td colspan=10 align=center><b><i>Table</i></b></td></tr>\n";
	echo "<tr><th align=left>Team</th><th>Played</th><th>W</th><th>D</th><th>L</th><th>TD</th><th>TD diff</th><th>Cas.</th><th>Cas diff</th><th>Points</th></tr>\n";
	while ($n_tab = mysql_fetch_array($r_tab)) {
		echo "<tr><td><a href=team_preview.php?team=" . $n_tab[2] . "&jbb=0 target=\"_blank\">" . $n_tab[1] . "</a></td>\n";
		echo "<td align=center>" . $n_tab[3] . "</td>\n";
		echo "<td align=center>" . $n_tab[4] . "</td>\n";
		echo "<td align=center>" . $n_tab[5] . "</td>\n";
		echo "<td align=center>" . $n_tab[6] . "</td>\n";
		echo "<td align=center>" . $n_tab[7] . "-" . $n_tab[8] . "</td>\n";
		echo "<td align=center>" . $n_tab[9] . "</td>\n";
		echo "<td align=center>" . $n_tab[10] . "-" . $n_tab[11] . "</td>\n";
		echo "<td align=center>" . $n_tab[12] . "</td>\n";
		echo "<td align=center>" . $n_tab[13] . "</td>\n";
		echo "</tr>";
	}
	echo "</table>";
}
function viewresults($division, $status){
	// :::Display results
	$q_res = 'SELECT g.t_game_played, h.t_team_name, a.t_team_name, t_game_h_td, t_game_a_td, t_game_h_cas, t_game_a_cas, t_game_gate, t_game_h_win, t_game_a_win, t_game_comment FROM `t_game` g, `t_team` h, `t_team` a WHERE'
		. ' t_h_team_ID = h.t_team_ID'
		. ' AND t_a_team_ID = a.t_team_ID'
		. ' AND t_division_ID = ' . $division
		. ' AND t_game_status = ' . $status
		. ' ORDER BY t_game_played' ;
	$r_res = mysql_query($q_res) or die ("<tr><td colspan=9>Results query failed " . $q_res . "</td></tr>");
	echo "<table summary=\"Game Results\" border=1 cellpadding=2 cellspacing=0>\n";
	echo "<tr><th colspan=9>Game results</th></tr>\n";
	echo "<tr><th>Date</th><th>Home</th><th>Away</th><th>Score</th><th>Cas</th><th>Gate</th><th>Home win</th><th>Away win</th><th>Notes</th></tr>\n";
	while ($n_res = mysql_fetch_array($r_res)) {
		echo "<tr><td>" . $n_res[0] . "</td>\n";
		echo "<td>" . $n_res[1] . "</td>\n";
		echo "<td>" . $n_res[2] . "</td>\n";
		echo "<td align=center>" . $n_res['t_game_h_td'] . " - " . $n_res['t_game_a_td'] . "</td>\n";
		echo "<td align=center>" . $n_res['t_game_h_cas'] . " - " . $n_res['t_game_a_cas'] ."</td>\n";
		echo "<td align=right>" . number_format($n_res[7]) . "</td>\n";
		echo "<td align=right>" . number_format($n_res[8]) . "</td>\n";
		echo "<td align=right>" . number_format($n_res[9]) . "</td>\n";
		echo "<td>" . $n_res[10] . "</td>\n";
		echo "</tr>";
	}
	echo "</table>";
}
function t_gate_stat($division){

	// Code contributed by Tom
	// Prints: BEST AND WORST GATE in a league
	// Given the division id
  	// :::Display Statistic 1

	$q_td = 'SELECT ta.t_team_name, tb.t_team_name, t_game_played, t_game_gate FROM t_team as ta, t_team as tb, t_game WHERE '
					 . ' ta.t_team_ID=t_h_team_ID AND tb.t_team_ID=t_a_team_ID AND '
					 . ' t_h_team_ID <> 50 AND t_a_team_ID <> 50 AND t_division_ID = ' . $division
					 . ' ORDER BY t_game_gate DESC LIMIT 1';
	$r_td = mysql_query($q_td) or die ("Player query failed! " . mysql_error());

	echo "<table summary=\"Statistic 3\" border=1 cellpadding=2 cellspacing=0>\n";
	echo "<tr><td colspan=9 align=center><b><i>Famous and Infamous Match</i></b></td></tr>\n";
	echo "<tr><th>Category</th><th align=left>Match</th><th>Date</th><th>Gate</th></tr>\n";
	while ($n_tab = mysql_fetch_array($r_td)) {
		echo "<tr><td> Famous </td>\n";
		echo "<td>" . $n_tab[0] . " vs " . $n_tab[1] ."</td>\n";
		echo "<td align=center>" . $n_tab[2] . "</td>\n";
		echo "<td align=center>" . number_format($n_tab[3]) . "</td>\n";
		echo "</tr>";
	}
	$q_td = 'SELECT ta.t_team_name, tb.t_team_name, t_game_played, t_game_gate FROM t_team as ta, t_team as tb, t_game WHERE '
					 . ' ta.t_team_ID=t_h_team_ID AND tb.t_team_ID=t_a_team_ID AND '
					 . ' t_h_team_ID <> 50 AND t_a_team_ID <> 50 AND t_division_ID = ' . $division
					 . ' ORDER BY t_game_gate ASC LIMIT 1';
	$r_td = mysql_query($q_td) or die ("Player query failed! " . mysql_error());

	while ($n_tab = mysql_fetch_array($r_td)) {
		echo "<tr><td> Infamous </td>\n";
		echo "<td>" . $n_tab[0] . " vs " . $n_tab[1] ."</td>\n";
		echo "<td align=center>" . $n_tab[2] . "</td>\n";
		echo "<td align=center>" . number_format($n_tab[3]) . "</td>\n";
		echo "</tr>";
	}
	echo "</table>";
}
function t_winnings_worst($division){
	//Worst winnings given the division id
	// Code contributed by Tom
	// Prints: WORST WINNINGS ONE MATCH in a league given the division id
	// ***
	// Does this function do the same as the worst winnings part of the winnings_stat function?
	// ***
	// :::Display Statistic 1
	$q_td = 'SELECT t_game_played, t_team_name, min(t_game_h_win) FROM ((SELECT t_h_team_ID, t_game_h_win, t_game_played FROM t_game gh WHERE '
					 . ' t_h_team_ID <> 50 AND t_division_ID = ' . $division
					 . ' ) UNION ALL (SELECT t_a_team_ID, t_game_a_win, t_game_played FROM t_game ga WHERE '
					 . ' t_a_team_ID <> 50 AND t_division_ID = ' . $division
					 . ' )) AS tga, t_team WHERE t_h_team_ID=t_team_ID GROUP BY t_h_team_ID '
					 . ' ORDER BY min(t_game_h_win) ASC ';
	$r_td = mysql_query($q_td) or die ("Player query failed! " . mysql_error());

	echo "<table summary=\"Worst winnings one match\" border=1 cellpadding=2 cellspacing=0>\n";
	echo "<tr><td colspan=9 align=center><b><i>Worst Winnings One Match</i></b></td></tr>\n";
	echo "<tr><th>Date</th><th align=left>Team</th><th>Winnings</th></tr>\n";
	while ($n_tab = mysql_fetch_array($r_td)) {
		echo "<tr><td>" . $n_tab[0] . "</td>\n";
		echo "<td align=center>" . $n_tab[1] . "</td>\n";
		echo "<td align=center>" . $n_tab[2] . "</td>\n";
		echo "</tr>";
	}
	echo "</table>";
}
function t_offense_best($division){
	// Code contributed by Tom
	// Prints: BEST OFFENSE TOTAL in a league given the division id
	// :::Display Statistic 1

	$q_td = ' SELECT sum(t_game_h_td)  FROM ((SELECT t_h_team_ID, t_game_h_td FROM t_game gh WHERE '
					 . ' t_h_team_ID <> 50 AND t_division_ID = ' . $division
					 . ' ) UNION ALL (SELECT t_a_team_ID, t_game_a_td FROM t_game ga WHERE '
					 . ' t_a_team_ID <> 50 AND t_division_ID = ' . $division
					 . ' )) AS tga, t_team WHERE t_h_team_ID=t_team_ID GROUP BY t_h_team_ID '
					 . ' ORDER BY sum(t_game_h_td) DESC LIMIT 1';
	$r_td = mysql_query($q_td) or die ("Player query failed! " . mysql_error());
	$n_score = mysql_fetch_array($r_td);
	$best_score= $n_score[0];

	$q_td = 'SELECT * FROM ( ('
	. ' SELECT t_team_name, sum(t_game_h_td) AS score FROM ((SELECT t_h_team_ID, t_game_h_td FROM t_game gh WHERE '
					 . ' t_h_team_ID <> 50 AND t_division_ID = ' . $division
					 . ' ) UNION ALL (SELECT t_a_team_ID, t_game_a_td FROM t_game ga WHERE '
					 . ' t_a_team_ID <> 50 AND t_division_ID = ' . $division
					 . ' )) AS tga, t_team WHERE t_h_team_ID=t_team_ID '
					 . ' GROUP BY t_h_team_ID ) AS tgb ) '
					 . ' WHERE score = ' . $best_score ;


	$r_td = mysql_query($q_td) or die ("Player query failed! " . mysql_error());

	echo "<table summary=\"Statistic 3\" border=1 cellpadding=2 cellspacing=0>\n";
	echo "<tr><td colspan=9 align=center><b><i>Best Offense</i></b></td></tr>\n";
	echo "<tr><th align=left>Team</th><th>TDs</th></tr>\n";
	while ($n_tab = mysql_fetch_array($r_td)) {
		echo "<tr><td>" . $n_tab[0] . "</td>\n";
		echo "<td align=center>" . $n_tab[1] . "</td>\n";
		echo "<td align=center>" . $n_tab[2] . "</td>\n";
		echo "</tr>";
	}
	echo "</table>";
}
function t_offense_worst($division){
	// Code contributed by Tom
	// Prints: WORST OFFENSE TOTAL in a league given the division id
	// :::Display Statistic 1

	$q_td = ' SELECT sum(t_game_h_td)  FROM ((SELECT t_h_team_ID, t_game_h_td FROM t_game gh WHERE '
					 . ' t_h_team_ID <> 50 AND t_division_ID = ' . $division
					 . ' ) UNION ALL (SELECT t_a_team_ID, t_game_a_td FROM t_game ga WHERE '
					 . ' t_a_team_ID <> 50 AND t_division_ID = ' . $division
					 . ' )) AS tga, t_team WHERE t_h_team_ID=t_team_ID GROUP BY t_h_team_ID '
					 . ' ORDER BY sum(t_game_h_td) ASC LIMIT 1';
	$r_td = mysql_query($q_td) or die ("Player query failed! " . mysql_error());
	$n_score = mysql_fetch_array($r_td);
	$worst_score= $n_score[0];

	$q_td = 'SELECT * FROM ( ('
	. ' SELECT t_team_name, sum(t_game_h_td) AS score FROM ((SELECT t_h_team_ID, t_game_h_td FROM t_game gh WHERE '
					 . ' t_h_team_ID <> 50 AND t_division_ID = ' . $division
					 . ' ) UNION ALL (SELECT t_a_team_ID, t_game_a_td FROM t_game ga WHERE '
					 . ' t_a_team_ID <> 50 AND t_division_ID = ' . $division
					 . ' )) AS tga, t_team WHERE t_h_team_ID=t_team_ID '
					 . ' GROUP BY t_h_team_ID ) AS tgb ) '
					 . ' WHERE score = ' . $worst_score ;
	$r_td = mysql_query($q_td) or die ("Player query failed! " . mysql_error());

	echo "<table summary=\"Worst offense\" border=1 cellpadding=2 cellspacing=0>\n";
	echo "<tr><td colspan=9 align=center><b><i>Worst Offense</i></b></td></tr>\n";
	echo "<tr><th align=left>Team</th><th>TDs</th></tr>\n";
	while ($n_tab = mysql_fetch_array($r_td)) {
		echo "<tr><td>" . $n_tab[0] . "</td>\n";
		echo "<td align=center>" . $n_tab[1] . "</td>\n";
		echo "<td align=center>" . $n_tab[2] . "</td>\n";
		echo "</tr>";
	}
	echo "</table>";
}
function t_cas_best($division){
	// Code contributed by Tom
	// Prints: BEST CAS TOTAL in a league given the division id
	// :::Display Statistic 1
	$q_td = ' SELECT sum(t_game_h_cas)  FROM ((SELECT t_h_team_ID, t_game_h_cas FROM t_game gh WHERE '
					 . ' t_h_team_ID <> 50 AND t_division_ID = ' . $division
					 . ' ) UNION ALL (SELECT t_a_team_ID, t_game_a_cas FROM t_game ga WHERE '
					 . ' t_a_team_ID <> 50 AND t_division_ID = ' . $division
					 . ' )) AS tga, t_team WHERE t_h_team_ID=t_team_ID GROUP BY t_h_team_ID '
					 . ' ORDER BY sum(t_game_h_cas) DESC LIMIT 1';
	$r_td = mysql_query($q_td) or die ("Player query failed! " . mysql_error());
	$n_score = mysql_fetch_array($r_td);
	$best_cas= $n_score[0];

	$q_td = 'SELECT * FROM ( ('
	. ' SELECT t_team_name, sum(t_game_h_cas) AS cas FROM ((SELECT t_h_team_ID, t_game_h_cas FROM t_game gh WHERE '
					 . ' t_h_team_ID <> 50 AND t_division_ID = ' . $division
					 . ' ) UNION ALL (SELECT t_a_team_ID, t_game_a_cas FROM t_game ga WHERE '
					 . ' t_a_team_ID <> 50 AND t_division_ID = ' . $division
					 . ' )) AS tga, t_team WHERE t_h_team_ID=t_team_ID '
					 . ' GROUP BY t_h_team_ID ) AS tgb ) '
					 . ' WHERE cas = ' . $best_cas ;
	$r_td = mysql_query($q_td) or die ("Player query failed! " . mysql_error());

	echo "<table summary=\"Most brutal\" border=1 cellpadding=2 cellspacing=0>\n";
	echo "<tr><td colspan=9 align=center><b><i>Most Brutal</i></b></td></tr>\n";
	echo "<tr><th align=left>Team</th><th>CAS</th></tr>\n";
	while ($n_tab = mysql_fetch_array($r_td)) {
		echo "<tr><td>" . $n_tab[0] . "</td>\n";
		echo "<td align=center>" . $n_tab[1] . "</td>\n";
		echo "<td align=center>" . $n_tab[2] . "</td>\n";
		echo "</tr>";
	}
	echo "</table>";
}
function t_cas_worst($division){
	// Code contributed by Tom
	// Prints: WORST CAS against TOTAL in a league given the division id
	// :::Display Statistic 1

	$q_td = ' SELECT sum(t_game_a_cas)  FROM ((SELECT t_h_team_ID, t_game_a_cas FROM t_game gh WHERE '
					 . ' t_h_team_ID <> 50 AND t_division_ID = ' . $division
					 . ' ) UNION ALL (SELECT t_a_team_ID, t_game_h_cas FROM t_game ga WHERE '
					 . ' t_a_team_ID <> 50 AND t_division_ID = ' . $division
					 . ' )) AS tga, t_team WHERE t_h_team_ID=t_team_ID GROUP BY t_h_team_ID '
					 . ' ORDER BY sum(t_game_a_cas) DESC LIMIT 1';
	$r_td = mysql_query($q_td) or die ("Player query failed! " . mysql_error());
	$n_score = mysql_fetch_array($r_td);
	$worst_cas_agst= $n_score[0];

	$q_td = 'SELECT * FROM ( ('
	. ' SELECT t_team_name, sum(t_game_a_cas) AS cas_agst FROM ((SELECT t_h_team_ID, t_game_a_cas FROM t_game gh WHERE '
					 . ' t_h_team_ID <> 50 AND t_division_ID = ' . $division
					 . ' ) UNION ALL (SELECT t_a_team_ID, t_game_h_cas FROM t_game ga WHERE '
					 . ' t_a_team_ID <> 50 AND t_division_ID = ' . $division
					 . ' )) AS tga, t_team WHERE t_h_team_ID=t_team_ID '
					 . ' GROUP BY t_h_team_ID ) AS tgb ) '
					 . ' WHERE cas_agst = ' . $worst_cas_agst ;

	$r_td = mysql_query($q_td) or die ("Player query failed! " . mysql_error());

	echo "<table summary=\"Least brutal\" border=1 cellpadding=2 cellspacing=0>\n";
	echo "<tr><td colspan=9 align=center><b><i>Softest Team</i></b></td></tr>\n";
	echo "<tr><th align=left>Team</th><th>CAS Against</th></tr>\n";
	while ($n_tab = mysql_fetch_array($r_td)) {
		echo "<tr><td>" . $n_tab[0] . "</td>\n";
		echo "<td align=center>" . $n_tab[1] . "</td>\n";
		echo "<td align=center>" . $n_tab[2] . "</td>\n";
		echo "</tr>";
	}
	echo "</table>";
}
function t_winnings_stat($division){
	// Code contributed by Tom
	// Prints WINNINGS MIN and MAX in a league given the division id
	// BEST WINNINGS TOTAL
	// :::Display Statistic 1

	$q_td = 'SELECT t_team_name, sum(t_game_h_win) FROM ((SELECT t_h_team_ID, t_game_h_win FROM t_game gh WHERE '
					 . ' t_division_ID = ' . $division
					 . ' ) UNION ALL (SELECT t_a_team_ID, t_game_a_win FROM t_game ga WHERE '
					 . ' t_division_ID = ' . $division
					 . ' )) AS tga, t_team WHERE t_h_team_ID=t_team_ID GROUP BY t_h_team_ID '
					 . ' ORDER BY sum(t_game_h_win) DESC LIMIT 1';
	$r_td = mysql_query($q_td) or die ("Player query failed! " . mysql_error());

	echo "<table summary=\"Winnings\" border=1 cellpadding=2 cellspacing=0>\n";
	echo "<tr><td colspan=9 align=center><b><i>Winnings</i></b></td></tr>\n";
	echo "<tr><th align=left>Team</th><th>Category</th><th>Winnings</th></tr>\n";
	while ($n_tab = mysql_fetch_array($r_td)) {
		echo "<tr><td>" . $n_tab[0] . "</td>\n";
		echo "<td align=center>Best Overall</td>\n";
		echo "<td align=center>" . number_format($n_tab[1]) . "</td>\n";
		echo "</tr>";
	}
	// WORST WINNINGS TOTAL ----------------------------------------- TOM OK
	// :::Display Statistic 1
	$q_td = 'SELECT t_team_name, sum(t_game_h_win) FROM ((SELECT t_h_team_ID, t_game_h_win FROM t_game gh WHERE '
					 . ' t_h_team_ID <> 50 AND t_division_ID = ' . $division
					 . ' ) UNION ALL (SELECT t_a_team_ID, t_game_a_win FROM t_game ga WHERE '
					 . ' t_a_team_ID <> 50 AND t_division_ID = ' . $division
					 . ' )) AS tga, t_team WHERE t_h_team_ID=t_team_ID GROUP BY t_h_team_ID '
					 . ' ORDER BY sum(t_game_h_win) ASC LIMIT 1';
	$r_td = mysql_query($q_td) or die ("Player query failed! " . mysql_error());
	while ($n_tab = mysql_fetch_array($r_td)) {
		echo "<tr><td>" . $n_tab[0] . "</td>\n";
		echo "<td align=center>Worst Overall</td>\n";
		echo "<td align=center>" . number_format($n_tab[1]) . "</td>\n";
		echo "</tr>";
	}
	// BEST WINNINGS ONE MATCH ----------------------------------------- TOM OK
	// :::Display Statistic 1
	$q_td = 'SELECT t_team_name, max(t_game_h_win) FROM ((SELECT t_h_team_ID, t_game_h_win FROM t_game gh WHERE '
					 . ' t_division_ID = ' . $division
					 . ' ) UNION ALL (SELECT t_a_team_ID, t_game_a_win FROM t_game ga WHERE '
					 . ' t_division_ID = ' . $division
					 . ' )) AS tga, t_team WHERE t_h_team_ID=t_team_ID GROUP BY t_h_team_ID '
					 . ' ORDER BY max(t_game_h_win) DESC LIMIT 1';
	$r_td = mysql_query($q_td) or die ("Player query failed! " . mysql_error());

	while ($n_tab = mysql_fetch_array($r_td)) {
					echo "<tr><td>" . $n_tab[0] . "</td>\n";
					echo "<td align=center>Best One Match</td>\n";
					echo "<td align=center>" . number_format($n_tab[1]) . "</td>\n";
					echo "</tr>";
	}

	// WORST WINNINGS ONE MATCH ----------------------------------------- TOM OK
	// :::Display Statistic 1
	$q_td = 'SELECT t_team_name, min(t_game_h_win) FROM ((SELECT t_h_team_ID, t_game_h_win FROM t_game gh WHERE '
					 . ' t_h_team_ID <> 50 AND t_division_ID = ' . $division
					 . ' ) UNION ALL (SELECT t_a_team_ID, t_game_a_win FROM t_game ga WHERE '
					 . ' t_a_team_ID <> 50 AND t_division_ID = ' . $division
					 . ' )) AS tga, t_team WHERE t_h_team_ID=t_team_ID GROUP BY t_h_team_ID '
					 . ' ORDER BY min(t_game_h_win) ASC LIMIT 1';
	$r_td = mysql_query($q_td) or die ("Player query failed! " . mysql_error());

	while ($n_tab = mysql_fetch_array($r_td)) {
		echo "<tr><td>" . $n_tab[0] . "</td>\n";
		echo "<td align=center>Worst One Match</td>\n";
		echo "<td align=center>" . number_format($n_tab[1]) . "</td>\n";
		echo "</tr>";
	}
	echo "</table>";
	// -------------------------------------------------------------------------------
}
// The following functions lists best scorers by player league wide without picking a division first. Should this be so?
// Maybe we should display best both league wide and division wide to see the standing compared to the best in the league.
// Anyway, there should be a narrowing of players at least based on the league
function p_best($league, $division){
	echo "<table>";
	echo "<tr>";//top row
	echo "<td valign=top align=center>";//top left cell

	// BEST TD ----------------------------------------- TOM
	// :::Display Statistic 1

	$q_td = 'SELECT  t_player_name, t_team_name, t_player_td FROM t_player p, t_team t WHERE t_player_td >0 AND p.t_team_ID=t.t_team_ID ORDER BY t_player_td DESC LIMIT 10' ;
	$r_td = mysql_query($q_td) or die ("Player query failed! " . mysql_error());

	echo "<table summary=\"Statistic 1\" bgcolor=#ffffff border=1 cellpadding=0 cellspacing=0>\n";
	echo "<tr><td colspan=9 align=center><b><i>Best Scorers</i></b></td></tr>\n";
	echo "<tr><th align=left>Player</th><th>Team</th><th>TD</th></tr>\n";
	while ($n_tab = mysql_fetch_array($r_td)) {
		echo "<tr><td>" . $n_tab[0] . "</td>\n";
		echo "<td align=center>" . $n_tab[1] . "</td>\n";
		echo "<td align=center>" . $n_tab[2] . "</td>\n";
		echo "</tr>";
	}
	echo "</table>";

	echo "</td>"; //top left cell
	echo "<td valign=top align=center>"; //top mid cell

	// BEST CAS ----------------------------------------- TOM
	// :::Display Statistic 1

	$q_td = 'SELECT  t_player_name, t_team_name, t_player_cas FROM t_player p, t_team t WHERE t_player_cas >0 AND p.t_team_ID=t.t_team_ID ORDER BY t_player_cas DESC LIMIT 10' ;
	$r_td = mysql_query($q_td) or die ("Player query failed! " . mysql_error());

	echo "<table summary=\"Statistic 2\" bgcolor=#ffffff border=1 cellpadding=0 cellspacing=0>\n";
	echo "<tr><td colspan=9 align=center><b><i>Best Hitters</i></b></td></tr>\n";
	echo "<tr><th align=left>Player</th><th>Team</th><th>CAS</th></tr>\n";
	while ($n_tab = mysql_fetch_array($r_td)) {
		echo "<tr><td>" . $n_tab[0] . "</td>\n";
		echo "<td align=center>" . $n_tab[1] . "</td>\n";
		echo "<td align=center>" . $n_tab[2] . "</td>\n";
		echo "</tr>";
	}

	echo "</table>";

	echo "</td>"; //top mid cell
	echo "<td valign=top align=center>"; //top right cell

	// BEST COMP ----------------------------------------- TOM
	// :::Display Statistic 1

	$q_td = 'SELECT  t_player_name, t_team_name, t_player_comp FROM t_player p, t_team t WHERE t_player_comp >0 AND p.t_team_ID=t.t_team_ID ORDER BY t_player_comp DESC LIMIT 10' ;
	$r_td = mysql_query($q_td) or die ("Player query failed! " . mysql_error());

	echo "<table summary=\"Statistic 3\" bgcolor=#ffffff border=1 cellpadding=0 cellspacing=0>\n";
	echo "<tr><td colspan=9 align=center><b><i>Best Passers</i></b></td></tr>\n";
	echo "<tr><th align=left>Player</th><th>Team</th><th>COMP</th></tr>\n";
	while ($n_tab = mysql_fetch_array($r_td)) {
		echo "<tr><td>" . $n_tab[0] . "</td>\n";
		echo "<td align=center>" . $n_tab[1] . "</td>\n";
		echo "<td align=center>" . $n_tab[2] . "</td>\n";
		echo "</tr>";
	}
	echo "</table>";

	echo "</td>"; //top right cell
	echo "</tr>"; //top row
	echo "<tr>"; //mid row
	echo "<td valign=top align=center>"; //mid left cell

	// BEST MVP ----------------------------------------- TOM
	// :::Display Statistic 1

	$q_td = 'SELECT  t_player_name, t_team_name, t_player_mvp FROM t_player p, t_team t WHERE t_player_mvp >0 AND p.t_team_ID=t.t_team_ID ORDER BY t_player_mvp DESC LIMIT 10' ;
	$r_td = mysql_query($q_td) or die ("Player query failed! " . mysql_error());

	echo "<table summary=\"Statistic 3\" bgcolor=#ffffff border=1 cellpadding=0 cellspacing=0>\n";
	echo "<tr><td colspan=9 align=center><b><i>Most MVPs</i></b></td></tr>\n";
	echo "<tr><th align=left>Player</th><th>Team</th><th>MVP</th></tr>\n";
	while ($n_tab = mysql_fetch_array($r_td)) {
		echo "<tr><td>" . $n_tab[0] . "</td>\n";
		echo "<td align=center>" . $n_tab[1] . "</td>\n";
		echo "<td align=center>" . $n_tab[2] . "</td>\n";
		echo "</tr>";
	}
	echo "</table>";

	echo "</td>"; //mid left cell
	echo "<td valign=top align=center>"; //mid mid cell

	// MOST INT ----------------------------------------- TOM
	// :::Display Statistic 1

	$q_td = 'SELECT  t_player_name, t_team_name, t_player_int FROM t_player p, t_team t WHERE t_player_int >0 AND p.t_team_ID=t.t_team_ID ORDER BY t_player_int DESC LIMIT 10' ;
	$r_td = mysql_query($q_td) or die ("Player query failed! " . mysql_error());

	echo "<table summary=\"Statistic 4\" bgcolor=#ffffff border=1 cellpadding=0 cellspacing=0>\n";
	echo "<tr><td colspan=9 align=center><b><i>Most Interceptions</i></b></td></tr>\n";
	echo "<tr><th align=left>Player</th><th>Team</th><th>INT</th></tr>\n";
	while ($n_tab = mysql_fetch_array($r_td)) {
		echo "<tr><td>" . $n_tab[0] . "</td>\n";
		echo "<td align=center>" . $n_tab[1] . "</td>\n";
		echo "<td align=center>" . $n_tab[2] . "</td>\n";
		echo "</tr>";
	}
	echo "</table>";

	echo "</td>"; //mid mid cell
	echo "<td valign=top align=center>"; //mid right cell

	// BEST OVERALL PLAYER ----------------------------------------- TOM
	// :::Display Statistic 1

	$q_td = 'SELECT  t_player_name, t_team_name, t_player_comp, t_player_td,  t_player_int, t_player_cas, t_player_mvp, ( IFNULL(t_player_comp,0)  + IFNULL(t_player_int,0) * 2 + IFNULL(t_player_td,0) * 3 + IFNULL(t_player_cas,0) * 2 + IFNULL(t_player_mvp,0) * 5) AS tomspp FROM t_player p, t_team t WHERE p.t_team_ID=t.t_team_ID AND (t_player_comp >0 OR t_player_int >0 OR t_player_td>0 OR t_player_cas>0 OR t_player_mvp>0) ORDER BY tomspp DESC LIMIT 10' ;
	$r_td = mysql_query($q_td) or die ("Player query failed! " . mysql_error());

	echo "<table summary=\"Statistic 4\" bgcolor=#ffffff border=1 cellpadding=0 cellspacing=0>\n";
	echo "<tr><td colspan=9 align=center><b><i>Best Overall Players</i></b></td></tr>\n";
	echo "<tr><th align=left>Player</th><th>Team</th><th>COMP</th><th>TD</th><th>INT</th><th>CAS</th><th>MVP</th><th>SPP</th></tr>\n";
	while ($n_tab = mysql_fetch_array($r_td)) {
		echo "<tr><td>" . $n_tab[0] . "</td>\n";
		echo "<td align=center>" . $n_tab[1] . "</td>\n";
		echo "<td align=center>" . $n_tab[2] . "</td>\n";
		echo "<td align=center>" . $n_tab[3] . "</td>\n";
		echo "<td align=center>" . $n_tab[4] . "</td>\n";
		echo "<td align=center>" . $n_tab[5] . "</td>\n";
		echo "<td align=center>" . $n_tab[6] . "</td>\n";
		echo "<td align=center>" . $n_tab[7] . "</td>\n";
		echo "</tr>";
	}
	echo "</table>";

	echo "</td>"; //mid right cell
	echo "</tr>"; //mid row
	echo "<tr>"; //bottom row
	echo "<td valign=top align=center>&nbsp;</td><td valign=top align=center>"; //bottom left cell

	// BEST ACTIVE PLAYER (no MVP) ----------------------------------------- TOM
	// :::Display Statistic 1

	$q_td = 'SELECT  t_player_name, t_team_name, t_player_comp, t_player_td,  t_player_int, t_player_cas,'
			. ' ( IFNULL(t_player_comp,0)  + IFNULL(t_player_int,0) * 2 + IFNULL(t_player_td,0) * 3 + IFNULL(t_player_cas,0) * 2 ) AS tommspp'
			. ' FROM t_player p, t_team t'
			. ' WHERE p.t_team_ID=t.t_team_ID'
			. ' AND (t_player_comp >0 OR t_player_int >0 OR t_player_td>0 OR t_player_cas>0 )'
			. ' ORDER BY tommspp DESC LIMIT 10' ;
	$r_td = mysql_query($q_td) or die (b_error("Player query failed! ",$q_td));

	echo "<table summary=\"Statistic 4\" bgcolor=#ffffff border=1 cellpadding=0 cellspacing=0>\n";
	echo "<tr><td colspan=9 align=center><b><i>Best Active Players (no MVP)</i></b></td></tr>\n";
	echo "<tr><th align=left>Player</th><th>Team</th><th>COMP</th><th>TD</th><th>INT</th><th>CAS</th><th>SPP</th></tr>\n";
	while ($n_tab = mysql_fetch_array($r_td)) {
		echo "<tr><td>" . $n_tab[0] . "</td>\n";
		echo "<td align=center>" . $n_tab[1] . "</td>\n";
		echo "<td align=center>" . $n_tab[2] . "</td>\n";
		echo "<td align=center>" . $n_tab[3] . "</td>\n";
		echo "<td align=center>" . $n_tab[4] . "</td>\n";
		echo "<td align=center>" . $n_tab[5] . "</td>\n";
		echo "<td align=center>" . $n_tab[6] . "</td>\n";
		echo "</tr>";
	}
	echo "</table>";

	echo "</td>"; //bottom left cell
	echo "<td valign=top align=center>&nbsp;</td>"; //bottom mid & bottom right cell
	echo "</tr>"; //bottom row
	echo "</table>"; // end outer table
	//---------------------------------------------------- FIN TOM

}
// Smaller multiused functions
function b_error($e_text,$e_sql) {
	echo "<table border=1>\n";
	echo "<tr><td>" . $e_text . "</td></tr>\n";
	echo "<tr><td>" . mysql_error() . "</td></tr>\n";
	echo "<tr><td>" . $e_sql . "</td></tr>\n";
	echo "</table>\n";
}
function get_sqlresult($sql, $queryname)
{
	$result = mysql_query($sql) or die(b_error($queryname . " failed!", $sql));
	return $result;
}