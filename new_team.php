<?php
session_start();
include "includes/library.inc.php";
include "includes/sql_connect.php";
if (isset($_POST['save_team'])) {
	$check = true;
	//Check for player names
	reset($_SESSION['players']);
	for ( $i = 1 ; $i <= $_SESSION['team']['num_players'] ; $i++) {
		$this_player = current($_SESSION['players']);
		if (!isset($this_player['name'])) {
			$check = false;
			$playername = "Missing name for one or more players.<BR>";
		}
		if (!isset($this_player['rosterpos'])) {
			$check = false;
			$rosterpos = "Must give all players a roster position.<BR>";
		}
		next($_SESSION['players']);
	}
	if ( (11 > $_SESSION['team']['num_players']) ) {
		$check = false;
		$teamnum = "Must have at least 11 players.<BR>";
	}
	if ( !isset($_SESSION['team']['teamname']) ) {
		$check = false;
		$teamname = "Team must have a name.";
	}
	$sql = "SELECT * FROM t_team WHERE t_team_name = '" . $_SESSION['team']['teamname'] . "'";
	$c_teamname = get_sqlresult($sql, "Check teamname");
	if (mysql_num_rows($c_teamname) > 0) {
		$check = false;
		$t_namedub = "Team name not available.<BR>";
	}
	if (!$check) {
		$_SESSION['admin_err'] = "Team not saved.<BR>" . $rosterpos . $playername . $teamnum . $teamname . $t_namedub;
	} else {
		$tsql = "INSERT INTO t_team VALUES (NULL, "
			. $_SESSION['userID'] . ", " 
			. $_SESSION['team']['raceid'] . ", '"
			. $_SESSION['team']['teamname'] . "', '"
			. $_SESSION['team']['coach'] . "', "
			. $_SESSION['team']['rerolls'] . ", "
			. $_SESSION['team']['fanfactor'] . ", "
			. $_SESSION['team']['a_coach'] . ", "
			. $_SESSION['team']['cheer_l'] . ", "
			. $_SESSION['team']['apoth'] . ", "
			. $_SESSION['team']['treasury'] . ", 1)";
		$i_tsql = get_sqlresult($tsql, "Save team data");
		$sql = "SELECT t_team_ID FROM t_team WHERE t_team_name = '" . $_SESSION['team']['teamname'] . "'";
		$n_sql = get_sqlresult($sql, "Get teamID");
		$p_sql = mysql_fetch_array($n_sql);
		reset($_SESSION['players']);
		$psql = "INSERT INTO t_player VALUES ";
		for ( $i = 1 ; $i <= $_SESSION['team']['num_players'] ; $i++) {
			$this_player = current($_SESSION['players']);
			$sql = "(NULL, "
				. $p_sql['t_team_ID'] . ", "
				. $this_player['rosterpos'] . ", '"
				. $this_player['name'] . "', "
				. $this_player['id'] . ", "
				. "NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),";
			$ssql = $ssql . $sql;
			next($_SESSION['players']);
		}
		$psql = $psql . substr($ssql,0,strlen($ssql)-1);
		$i_psql = get_sqlresult($psql, "Save players");
		$_SESSION['admin_err'] = "Team saved. Go to <a href=a_teams.php>teamlist</a> to see it.";
		$raceid = $_SESSION['team']['raceid'];
		unset($_SESSION['team']);
		unset($_SESSION['players']);
		unset($_SESSION['rosterpos']);
		$sql = "SELECT t_user_fullname FROM t_user WHERE t_user_id = " . $_SESSION['userID'];
		$r_name = get_sqlresult($sql, "Get username");
		$n_name = mysql_fetch_array($r_name);
		$_SESSION['team'] = array("userid" => $_SESSION['userID'], "raceid" => $raceid, "racename" => $_POST['racename'], "rr_price" => $_POST['rr_price'], "coach" => $n_name['t_user_fullname'], "num_players" => 0, "rerolls" => 0, "treasury" => 1000000, "status" => 1,"fanfactor" => 0, "a_coach" => 0, "cheer_l" => 0, "apoth" => 0, "playcount" => 0);
		$_SESSION['rosterpos'] = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0, 10 => 0, 11 => 0, 12 => 0, 13 => 0, 14 => 0, 15 => 0, 16 => 0);
	}
}
if (isset($_POST['roster_p'])) {
	if (isset($_SESSION['players'][$_POST['player_num']]['rosterpos'])) {
		$_SESSION['rosterpos'][$_SESSION['players'][$_POST['player_num']]['rosterpos']] = 0 ;
	}
	$_SESSION['rosterpos'][$_POST['rosterpos']] = 1 ;
	$_SESSION['players'][$_POST['player_num']]['rosterpos'] = $_POST['rosterpos'];
}
if (isset($_POST['team_name'])) {
	if ($_POST['team_name'] != $_SESSION['team']['teamname']) {
		$_SESSION['team']['teamname'] = $_POST['team_name'];
	}
}
if (isset($_POST['player_name'])) {
	if ($_POST['player_name'] != $_SESSION['players'][$_POST['player_num']]['name']) {
		$_SESSION['players'][$_POST['player_num']]['name'] = $_POST['player_name']; 
	}
}
if (isset($_POST['add_player'])) {
	if ($_SESSION['team']['treasury'] >= $_POST['playerprice']) {
		$_SESSION['team']['num_players']++;
		$_SESSION['team']['playcount']++;
		$_SESSION['players'][$_SESSION['team']['playcount']] = array("num" => $_SESSION['team']['playcount'], "id" => $_POST['playerid'], "title" => $_POST['playertitle'], "value" => $_POST['playerprice']) ;
		$_SESSION['team'][$_POST['playerid']]['used']++ ;
		$_SESSION['team']['treasury'] = $_SESSION['team']['treasury'] - $_POST['playerprice'] ;
	} else {
		$_SESSION['admin_err'] = "Not enough money to buy player.";
	}
}
if (isset($_POST['del_player'])) {
	$_SESSION['team']['treasury'] = $_SESSION['team']['treasury'] + $_SESSION['players'][$_POST['player_num']]['value'] ;
	$_SESSION['team']['num_players']--;
	$_SESSION['team'][$_SESSION['players'][$_POST['player_num']]['id']]['used']-- ;
	$_SESSION['rosterpos'][$_SESSION['players'][$_POST['player_num']]['rosterpos']] = 0 ;
	unset($_SESSION['players'][$_POST['player_num']]);
	if (count($_SESSION['players'])==0) {
		unset($_SESSION['players']);
	}
}
if (isset($_POST['update_team'])) {
	if (isset($_POST['add_reroll']) && ($_SESSION['team']['treasury'] >= $_SESSION['team']['rr_price'])) {
		$_SESSION['team']['rerolls']++;
		$_SESSION['team']['treasury'] = $_SESSION['team']['treasury'] - $_SESSION['team']['rr_price'];
	}
	if (isset($_POST['add_fanfact']) && ($_SESSION['team']['treasury'] >= 10000)) {
		$_SESSION['team']['fanfactor']++;
		$_SESSION['team']['treasury'] = $_SESSION['team']['treasury'] - 10000 ;
	}
	if (isset($_POST['add_acoach']) && ($_SESSION['team']['treasury'] >= 10000)) {
		$_SESSION['team']['a_coach']++;
		$_SESSION['team']['treasury'] = $_SESSION['team']['treasury'] - 10000 ;
	}
	if (isset($_POST['add_cheer']) && ($_SESSION['team']['treasury'] >= 10000)) {
		$_SESSION['team']['cheer_l']++;
		$_SESSION['team']['treasury'] = $_SESSION['team']['treasury'] - 10000 ;
	}
	if (isset($_POST['add_apoth']) && ($_SESSION['team']['treasury'] >= 50000)) {
		$_SESSION['team']['apoth'] = 1;
		$_SESSION['team']['treasury'] = $_SESSION['team']['treasury'] - 50000 ;
	}
	if (isset($_POST['rem_reroll'])) {
		$_SESSION['team']['rerolls']--;
		$_SESSION['team']['treasury'] = $_SESSION['team']['treasury'] + $_SESSION['team']['rr_price'];
	}
	if (isset($_POST['rem_fanfact'])) {
		$_SESSION['team']['fanfactor']--;
		$_SESSION['team']['treasury'] = $_SESSION['team']['treasury'] + 10000 ;
	}
	if (isset($_POST['rem_acoach'])) {
		$_SESSION['team']['a_coach']--;
		$_SESSION['team']['treasury'] = $_SESSION['team']['treasury'] + 10000 ;
	}
	if (isset($_POST['rem_cheer'])) {
		$_SESSION['team']['cheer_l']--;
		$_SESSION['team']['treasury'] = $_SESSION['team']['treasury'] + 10000 ;
	}
	if (isset($_POST['rem_apoth'])) {
		$_SESSION['team']['apoth'] = 0;
		$_SESSION['team']['treasury'] = $_SESSION['team']['treasury'] + 50000 ;
	}
}
select_forms();
head_std("New team");
body_top();
echo "<script type=\"text/javascript\" src=\"includes/wz_tooltip.js\"></script>";
echo "<div align=center>\n";
echo "<table border=1 cellspacing=0 cellpadding=0 width=800>";
	echo "<tr><td align=center>\n";
	// Start of the code.
	if (isset($_POST['raceid'])) {
		unset($_SESSION['team']);
		unset($_SESSION['players']);
		unset($_SESSION['rosterpos']);
		$sql = "SELECT t_user_fullname FROM t_user WHERE t_user_id = " . $_SESSION['userID'];
		$r_name = get_sqlresult($sql, "Get username");
		$n_name = mysql_fetch_array($r_name);
		$_SESSION['team'] = array("userid" => $_SESSION['userID'], "raceid" => $_POST['raceid'], "racename" => $_POST['racename'], "rr_price" => $_POST['rr_price'], "coach" => $n_name['t_user_fullname'], "num_players" => 0, "rerolls" => 0, "treasury" => 1000000, "status" => 1,"fanfactor" => 0, "a_coach" => 0, "cheer_l" => 0, "apoth" => 0, "playcount" => 0);
		$_SESSION['rosterpos'] = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0, 10 => 0, 11 => 0, 12 => 0, 13 => 0, 14 => 0, 15 => 0, 16 => 0);
	}
	echo "<P><B>Creating new " . $_SESSION['team']['racename'] . " team.</B>\n";
	echo "<table border=1 cellspacing=0 cellpadding=0>";
		echo "<tr><td>\n";
		echo "<table>\n";
			echo "<tr>\n<th>Qty</th><th align=left>Title</th><th>Cost</th><th>MA</th><th>ST</th><th>AG</th><th>AV</th><th align=left>Skills</th><th>N</th><th>D</th>\n</tr>\n";
			$q_pos = 'SELECT t_positions_ID, t_positions_title, t_positions_qty, t_positions_price, t_positions_MA, t_positions_ST, t_positions_AG, t_positions_AV'
				. ' FROM t_positions WHERE t_race_ID = ' . $_SESSION['team']['raceid'];
			$r_pos = get_sqlresult($q_pos, "Get positions");
			// for each position do
			$n_pos = 0;
			while ($pos = mysql_fetch_array($r_pos)) {
				if (isset($_POST['raceid'])) {
					$n_pos = $n_pos + 1;
					$_SESSION['team'][$pos['t_positions_ID']] = array("qty" => $pos['t_positions_qty'], "used" => 0);
				}
				echo "<tr>\n";
				echo "<td align=center width=40>0-" . $pos['t_positions_qty'] . "</td>";
				echo "<td>" . $pos['t_positions_title'] . "</td>";
				echo "<td align=right>" . number_format($pos['t_positions_price']) . "</td>";
				echo "<td align=center>" . $pos['t_positions_MA'] . "</td>";
				echo "<td align=center>" . $pos['t_positions_ST'] . "</td>";
				echo "<td align=center>" . $pos['t_positions_AG'] . "</td>";
				echo "<td align=center>" . $pos['t_positions_AV'] . "</td>";
				 // get skills
				$q_skill = 'SELECT p.t_skill_ID, t_skill_name, t_skill_desc, t_skill_type FROM `t_positions_skill` p, `t_skill` s'
						. ' WHERE p.t_skill_ID = s.t_skill_ID'
						. ' AND p.t_positions_ID = ' . $pos['t_positions_ID'] . ';';
				$r_skill = get_sqlresult($q_skill, "Get skills");
				// for each skill do
				echo "<td>";
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
				echo "<td>" . (($n_skill == "") ? "&nbsp;" : $n_skill) . "</td>" ;
				echo "<td>" . (($d_skill == "") ? "&nbsp;" : $d_skill) . "</td>\n" ;
				echo "<td>" ;
				if ( $_SESSION['team'][$pos['t_positions_ID']]['qty'] > $_SESSION['team'][$pos['t_positions_ID']]['used'] ) {
					echo "<form method=post action=". $_SERVER['PHP_SELF'] .">" ;
					echo "<input type=hidden name=playerid value=" . $pos['t_positions_ID'] . ">";
					echo "<input type=hidden name=playerprice value=" . $pos['t_positions_price'] . ">";
					echo "<input type=hidden name=playertitle value=" . $pos['t_positions_title'] . ">";
					echo "<input type=submit name=add_player value=\"Add\">";
					echo "</form>\n" ;
				}
				echo "</td></tr>\n" ;
			}// end while
			$_SESSION['team']['n_positions'] = $n_pos;
		echo "</table>\n";
		echo "</td></tr>";
	echo "</table>\n";
	echo "<table><tr><td align=center>\n";
	echo "<B><P>Team treasury: " . number_format($_SESSION['team']['treasury']);
	echo "</td></tr></table>\n";
	echo "<table width=800 border=1 cellspacing=0 cellpadding=0>\n";
		echo "<tr><td align=center valign=top>\n";
		echo "<P><B>Players</B>";
		echo "<table width=350>\n";
			if (isset($_SESSION['players'])) {
				echo "<tr><th>R.Pos</th><th>Name</th><th>Position</th><th>&nbsp;</th></tr>\n";
				reset($_SESSION['players']);
				for ($i = 0; $i < $_SESSION['team']['num_players'];$i++) {
					$this_player = current($_SESSION['players']);
					echo "<tr>\n";
					echo "<td><form method=post action=". $_SERVER['PHP_SELF'] .">";
					echo "<input type=hidden name=roster_p value=1>";
					echo "<input type=hidden name=player_num value=" . $this_player['num'] . ">";
					echo "<select name=rosterpos onChange=\"this.form.submit()\">\n";
					echo ( isset($this_player['rosterpos']) ? "" : "<option value=0>Pos</option>\n" );
					for ($j = 1; $j < 17; $j++) {
						if (($_SESSION['rosterpos'][$j] == 0) or ($this_player['rosterpos'] == $j)) {
							echo "<option value=" . $j . ( $this_player['rosterpos'] == $j ? " selected>" : ">" ) . $j . "</option>\n";
						}
					}
					echo "</select>";
					echo "</form></td>\n";
					echo "<td align=right><form method=post action=". $_SERVER['PHP_SELF'] .">";
					echo "<input type=text name=player_name value = \"" . $this_player['name'] . "\" size=12 onChange=\"this.form.submit()\"></input>";
					echo "<input type=hidden name=player_num value=" . $this_player['num'] . ">";
					echo "</form></td>\n";
					echo "<td align=right><form method=post action=". $_SERVER['PHP_SELF'] .">";
					echo "<B>" . $this_player['title'] . "</B></td>";
					echo "<td align=left><input type=hidden name=player_num value=" . $this_player['num'] . ">";
					echo "<input type=submit name=del_player value=Del>";
					echo "</form></td></tr>\n";
					next($_SESSION['players']);
				}
			}
		echo "</table>\n";
		echo "</td><td align=center valign=top>\n";
		echo "<P><B>Team data</B>";
		echo "<table width=350>\n";
			echo "<tr><td>";
			echo "<form method=post action=". $_SERVER['PHP_SELF'] .">";
			echo "<input type=hidden name=update_team>";
			echo "Team name:</td><td colspan=2><input type=text name=team_name value = \"" . $_SESSION['team']['teamname'] . "\" size=12 onChange=\"this.form.submit()\"></input>";
			echo "</td></tr>";
			echo "<tr><td>\n";
			echo "Rerolls:</td><td align=center>" . $_SESSION['team']['rerolls'] . "</td><td><input type=submit name=add_reroll value=\"Add\">";
			echo ( $_SESSION['team']['rerolls']>0 ? "<input type=submit name=rem_reroll value=Del>" : "") ;
			echo "</td></tr>";
			echo "<tr><td>\n";
			echo "Fan Factor:</td><td align=center>" . $_SESSION['team']['fanfactor'] . "</td><td><input type=submit name=add_fanfact value=\"Add\">";
			echo ( $_SESSION['team']['fanfactor']>0 ? "<input type=submit name=rem_fanfact value=Del>" : "") ;
			echo "</td></tr>";
			echo "<tr><td>\n";
			echo "Assistant coaches:</td><td align=center>" . $_SESSION['team']['a_coach'] . "</td><td><input type=submit name=add_acoach value=\"Add\">";
			echo ( $_SESSION['team']['a_coach']>0 ? "<input type=submit name=rem_acoach value=Del>" : "") ;
			echo "</td></tr>";
			echo "<tr><td>\n";
			echo "Cheerleaders:</td><td align=center>" . $_SESSION['team']['cheer_l'] . "</td><td><input type=submit name=add_cheer value=\"Add\">";
			echo ( $_SESSION['team']['cheer_l']>0 ? "<input type=submit name=rem_cheer value=Del>" : "") ;
			echo "</td></tr>";
			echo "<tr><td>\n";
			echo "Apothecary:</td><td align=center>" . $_SESSION['team']['apoth'] . "</td><td>" . ( ($_SESSION['team']['apoth']==0) ? "<input type=submit name=add_apoth value=\"Add\">" : "" ) ;
			echo ( $_SESSION['team']['apoth']>0 ? "<input type=submit name=rem_apoth value=Del>" : "") ;
			echo "</td></tr>";
		echo "</table>\n";
		echo "</form>";
		echo "</td></tr>\n";
	echo "</table>\n";	
	//	echo "<table border=1 cellpadding=0 cellspacing=0><tr>\n";
	echo "</td></tr>\n";
	echo "<tr><td align=center>\n";
	echo "<table width=800>\n";
		echo "<tr><td>\n";
		echo "<form method=post action=". $_SERVER['PHP_SELF'] .">";
		echo "<input type=hidden name=raceid value=" . $_SESSION['team']['raceid'] . ">";
		echo "<input type=submit name=reset_team value=\"Start over\">\n";
		echo "</form>\n";
		echo "</td><td align=right>\n";
		echo "<form method=post action=". $_SERVER['PHP_SELF'] .">";
		echo "<input type=submit name=save_team value=\"Save team\">\n";
		echo "</form>\n";
		echo "</td></tr>\n";
	echo "</table>\n";
	echo "</td></tr>\n";
	echo "<tr><td>\n";
	echo "<table width=800>\n";
		echo "<tr><td align=center>\n";
		echo "<B>" . $_SESSION['admin_err'] . "</B>";
		unset($_SESSION['admin_err']);
/*		print_r($_SESSION['team']);
		echo "<BR>";
		print_r($_SESSION['players']);*/
		echo "</td></tr>\n";
	echo "</table>\n";
echo "</table>\n";
echo "</div>";
echo "</body>\n</html>";
?>