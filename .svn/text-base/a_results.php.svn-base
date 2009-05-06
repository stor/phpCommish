<?php
session_start();
include "includes/library.inc.php";
include "includes/sql_connect.php";
if (isset($_POST['save'])) {
	// Save game-data
	$sql = "UPDATE t_game SET "
		. " t_game_played = '" . $_SESSION['game']['played']
		. "', t_game_status = " . ($_SESSION['userschema'] == 1 ? 3 : 2 )
		. ", t_game_h_td = " . (("" <> $_SESSION['game']['h_td']) ? $_SESSION['game']['h_td'] : "NULL")
		. ", t_game_a_td = " . (("" <> $_SESSION['game']['a_td']) ? $_SESSION['game']['a_td'] : "NULL")
		. ", t_game_h_cas = " . (("" <> $_SESSION['game']['h_cas']) ? $_SESSION['game']['h_cas'] : "NULL")
		. ", t_game_a_cas = " . (("" <> $_SESSION['game']['a_cas']) ? $_SESSION['game']['a_cas'] : "NULL")
		. ", t_game_gate = " . (("" <> $_SESSION['game']['gate']) ? $_SESSION['game']['gate'] : "NULL")
		. ", t_game_h_win = " . (("" <> $_SESSION['game']['h_win']) ? $_SESSION['game']['h_win'] : "NULL")
		. ", t_game_a_win = " . (("" <> $_SESSION['game']['a_win']) ? $_SESSION['game']['a_win'] : "NULL")
		. " WHERE t_game_ID = " . $_SESSION['game']['gameid'];
	$u_game = get_sqlresult($sql, "Update game data"); 
	// Save team-data into table: t_team_mod
	foreach ($_SESSION['game']['home'] as $this_player) {
		// Do some stuff
		if (isset($this_player['cp'])) {
			$sql = "SELECT t_player_ID from t_player_action WHERE t_player_ID = " . $this_player['id'] . " AND t_game_ID = " . $_SESSION['game']['gameid'];
			$q_player = get_sqlresult($sql, "Check player, home.");
			if (mysql_num_rows($q_player)) {
				$sql = "UPDATE t_player_action SET " 
					. "t_player_action_cp = " . (("" <> $this_player['cp']) ? $this_player['cp'] : "NULL")
					. ", t_player_action_td = " . (("" <> $this_player['td']) ? $this_player['td'] : "NULL")
					. ", t_player_action_in = " . (("" <> $this_player['in']) ? $this_player['in'] : "NULL")
					. ", t_player_action_ca = " . (("" <> $this_player['ca']) ? $this_player['ca'] : "NULL")
					. ", t_player_action_mv = " . (("" <> $this_player['mv']) ? $this_player['mv'] : "NULL")
					. ", t_player_action_rs = " . (("" <> $this_player['rs']) ? $this_player['rs'] : "NULL")
					. ", t_player_action_ps = " . (("" <> $this_player['ps']) ? $this_player['ps'] : "NULL")
					. ", t_player_action_bl = " . (("" <> $this_player['bl']) ? $this_player['bl'] : "NULL")
					. ", t_player_action_fl = " . (("" <> $this_player['fl']) ? $this_player['fl'] : "NULL")
					. ", t_injury_ID = " . (("" <> $this_player['inj']) ? $this_player['inj'] : "NULL")
					. " WHERE t_player_ID = " . $this_player['id'] . " AND t_game_ID = " . $_SESSION['game']['gameid'];
			} else {
				$sql = "INSERT INTO t_player_action VALUES (". $this_player['id'] . ", " . $_SESSION['game']['gameid'] 
					. "," . (("" <> $this_player['cp']) ? $this_player['cp'] : "NULL")
					. "," . (("" <> $this_player['td']) ? $this_player['td'] : "NULL")
					. "," . (("" <> $this_player['in']) ? $this_player['in'] : "NULL")
					. "," . (("" <> $this_player['ca']) ? $this_player['ca'] : "NULL")
					. "," . (("" <> $this_player['mv']) ? $this_player['mv'] : "NULL")
					. "," . (("" <> $this_player['rs']) ? $this_player['rs'] : "NULL")
					. "," . (("" <> $this_player['ps']) ? $this_player['ps'] : "NULL")
					. "," . (("" <> $this_player['bl']) ? $this_player['bl'] : "NULL")
					. "," . (("" <> $this_player['fl']) ? $this_player['fl'] : "NULL")
					. "," . (("" <> $this_player['inj']) ? $this_player['inj'] : "NULL")
					. ");";
			}
			$i_home = get_sqlresult($sql,"Insert playeractions, home.");
		}
	}
	foreach ($_SESSION['game']['away'] as $this_player) {
		// Do some stuff
		if (isset($this_player['cp'])) {
			$sql = "SELECT t_player_ID from t_player_action WHERE t_player_ID = " . $this_player['id'] . " AND t_game_ID = " . $_SESSION['game']['gameid'];
			$q_player = get_sqlresult($sql, "Check player, away.");
			if (mysql_num_rows($q_player)) {
				$sql = "UPDATE t_player_action SET " 
					. "t_player_action_cp = " . (("" <> $this_player['cp']) ? $this_player['cp'] : "NULL")
					. ", t_player_action_td = " . (("" <> $this_player['td']) ? $this_player['td'] : "NULL")
					. ", t_player_action_in = " . (("" <> $this_player['in']) ? $this_player['in'] : "NULL")
					. ", t_player_action_ca = " . (("" <> $this_player['ca']) ? $this_player['ca'] : "NULL")
					. ", t_player_action_mv = " . (("" <> $this_player['mv']) ? $this_player['mv'] : "NULL")
					. ", t_player_action_rs = " . (("" <> $this_player['rs']) ? $this_player['rs'] : "NULL")
					. ", t_player_action_ps = " . (("" <> $this_player['ps']) ? $this_player['ps'] : "NULL")
					. ", t_player_action_bl = " . (("" <> $this_player['bl']) ? $this_player['bl'] : "NULL")
					. ", t_player_action_fl = " . (("" <> $this_player['fl']) ? $this_player['fl'] : "NULL")
					. ", t_injury_ID = " . (("" <> $this_player['inj']) ? $this_player['inj'] : "NULL")
					. " WHERE t_player_ID = " . $this_player['id'] . " AND t_game_ID = " . $_SESSION['game']['gameid'];
			} else {
				$sql = "INSERT INTO t_player_action VALUES (". $this_player['id'] . ", " . $_SESSION['game']['gameid'] 
					. "," . (("" <> $this_player['cp']) ? $this_player['cp'] : "NULL")
					. "," . (("" <> $this_player['td']) ? $this_player['td'] : "NULL")
					. "," . (("" <> $this_player['in']) ? $this_player['in'] : "NULL")
					. "," . (("" <> $this_player['ca']) ? $this_player['ca'] : "NULL")
					. "," . (("" <> $this_player['mv']) ? $this_player['mv'] : "NULL")
					. "," . (("" <> $this_player['rs']) ? $this_player['rs'] : "NULL")
					. "," . (("" <> $this_player['ps']) ? $this_player['ps'] : "NULL")
					. "," . (("" <> $this_player['bl']) ? $this_player['bl'] : "NULL")
					. "," . (("" <> $this_player['fl']) ? $this_player['fl'] : "NULL")
					. "," . (("" <> $this_player['inj']) ? $this_player['inj'] : "NULL")
					. ");";
			}
			$i_home = get_sqlresult($sql,"Insert playeractions, away.");
		}
	}
	// Save player-data into table: t_player_action
}
if (isset($_POST['clear'])) {
	// Unset session arrays
	// Clear the table of previously saved data. The game is not dropped, it must still be possible to enter results.
	$sql = "UPDATE t_game SET "
		. " t_game_played = NULL"
		. ", t_game_status = 1"
		. ", t_game_h_td = NULL"
		. ", t_game_a_td = NULL"
		. ", t_game_h_cas = NULL"
		. ", t_game_a_cas = NULL"
		. ", t_game_gate = NULL"
		. ", t_game_h_win = NULL"
		. ", t_game_a_win = NULL"
		. " WHERE t_game_ID = " . $_SESSION['game']['gameid'];
	$c_game = get_sqlresult($sql,"Clear saved game data.");
	// Unset session variables to clear the cache of data.
	unset($_SESSION['game']['home']);
	unset($_SESSION['game']['gate']);
	//  Remove from t_team_mod
	//  Remove from t_player_action
}
if (isset($_POST['gameid'])) {
	unset($_SESSION['game']);
	unset($xml);
	$_SESSION['game']['gameid'] = $_POST['gameid'];
	$_SESSION['game']['fixtureid'] = $_SESSION['fixtureID'];
	$sql = "SELECT t_game_ID, th.t_user_ID t_h_user_ID, th.t_team_name t_h_team_name, th.t_team_ID t_h_team_ID, ta.t_user_ID t_a_user_ID, ta.t_team_name t_a_team_name, ta.t_team_ID t_a_team_ID,  t_game_scheduled, t_game_status"
		." FROM t_game g, t_team th, t_team ta"
		." WHERE g.t_h_team_ID = th.t_team_ID"
		." AND g.t_a_team_ID = ta.t_team_ID"
		." AND t_game_ID = " . $_POST['gameid'];
	$game = get_sqlresult($sql, "Get game");
	$n_game = mysql_fetch_array($game);
	$_SESSION['game']['scheduled'] = $n_game['t_game_scheduled'];
	$_SESSION['game']['homename'] = $n_game['t_h_team_name'];
	$_SESSION['game']['homeid'] = $n_game['t_h_team_ID'];
	$_SESSION['game']['homeuserid'] = $n_game['t_h_user_ID'];
	$_SESSION['game']['homeff'] = 0;
	$_SESSION['game']['awayname'] = $n_game['t_a_team_name'];
	$_SESSION['game']['awayid'] = $n_game['t_a_team_ID'];
	$_SESSION['game']['awayuserid'] = $n_game['t_a_user_ID'];
	$_SESSION['game']['awayff'] = 0;
}
if (isset($_POST['readfile'])) {
	$xml = simplexml_load_file($_FILES['results']['tmp_name']);
	if ($xml->result[0]['team'] == $_SESSION['game']['homename'] || $xml->result[0]['team'] == $_SESSION['game']['awayname']) {
		//$_SESSION['game']['xmlver'] = $xml['version'];
		$_SESSION['game']['played'] = (string)$xml['date'];
		//$_SESSION['game']['auth'] = $xml['authorized'];
		$_SESSION['game']['gate'] = (string)$xml->gate;
		foreach ($xml->result as $result) {
			$teamname = $result['team'];
			if ($teamname == $_SESSION['game']['homename']) {
				$teamid = "home";
			} else {
				$teamid = "away"; 
			}
			//$bb_name = $result->client['name'];
			//$bb_version = $result->client['version'];
			//$teamrating = $result->rating;
			$_SESSION['game'][substr($teamid,0,1) . '_td'] = (string)$result->score;
			//$completions = $result->completions;
			$_SESSION['game'][substr($teamid,0,1) . '_win'] = (string)$result->winnings;
			$_SESSION['game'][$teamid . 'ff'] = (string)$result->fanfactor;
			$_SESSION['game'][$teamid . '_luck'] = (string)$result->luck;
			$_SESSION['game'][$teamid . '_bh'] = (string)$result->casualties['bh'];
			$_SESSION['game'][$teamid . '_si'] = (string)$result->casualties['si'];
			$_SESSION['game'][$teamid . '_ki'] = (string)$result->casualties['k'];
			foreach ($result->players->performance as $performance) {
				$playernum = $performance['player'];
				foreach ($_SESSION['game'][$teamid] as &$this_player) {
					if ($this_player['num'] == $playernum) { break; }
				}
				$this_player['cp'] = (string)$performance->completions;
				$this_player['td'] = (string)$performance->touchdowns;
				$this_player['rs'] = (string)$performance->rushing;
				$this_player['ps'] = (string)$performance->passing;
				$this_player['bl'] = (string)$performance->blocks;
				$this_player['ca'] = (string)$performance->casualties;
				$injurytype = $performance->injury['type'];
				switch ($injurytype) {
					case "si":
						$this_player['inj'] = 1;
						break;
					case "d":
						$this_player['inj'] = 2;
						break;
				}
				$injuryeffect = $performance->injury->effect;
				switch ($injuryeffect) {
					case "m":
						$this_player['inj_eff'] = 1;
						break;
					case "m,n":
						$this_player['inj_eff'] = 2;
						break;
					case "m,-ma":
						$this_player['inj_eff'] = 3;
						break;
					case "m,-av":
						$this_player['inj_eff'] = 4;
						break;
					case "m,-ag":
						$this_player['inj_eff'] = 5;
						break;
					case "m,-st":
						$this_player['inj_eff'] = 6;
						break;
					case "dead":
						$this_player['inj_eff'] = 7;
						break;
				}
				$this_player['fl'] = (string)$performance->fouls;
				$this_player['mv'] = (string)$performance->mvps;
				//Writeout playerdata to database
			}
		}
		unset($xml);
	} else {
		$_SESSION['admin_err'] = "Error: Teams not found in file.";
	}
}
if (isset($_POST['h_winnings'])) {
	$_SESSION['game']['h_win'] = $_POST['h_winnings'];
	$_SESSION['game']['homeff'] =  $_POST['h_ff'];
}
if (isset($_POST['a_winnings'])) {
	$_SESSION['game']['a_win'] = $_POST['a_winnings'];
	$_SESSION['game']['awayff'] =  $_POST['a_ff'];
}
if (isset($_POST['clear_h'])) {
	unset($_SESSION['game']['home'][$_POST['playerid']]['changed']);
	unset($_SESSION['game']['home'][$_POST['playerid']]['cp']);
	$_SESSION['game']['h_td'] = $_SESSION['game']['h_td'] - $_SESSION['game']['home'][$_POST['playerid']]['td'];
	unset($_SESSION['game']['home'][$_POST['playerid']]['td']);
	unset($_SESSION['game']['home'][$_POST['playerid']]['in']);
	$_SESSION['game']['h_cas'] = $_SESSION['game']['h_cas'] - $_SESSION['game']['home'][$_POST['playerid']]['ca'];
	unset($_SESSION['game']['home'][$_POST['playerid']]['ca']);
	unset($_SESSION['game']['home'][$_POST['playerid']]['mv']);
	unset($_SESSION['game']['home'][$_POST['playerid']]['rs']);
	unset($_SESSION['game']['home'][$_POST['playerid']]['ps']);
	unset($_SESSION['game']['home'][$_POST['playerid']]['bl']);
	unset($_SESSION['game']['home'][$_POST['playerid']]['fl']);
	unset($_SESSION['game']['home'][$_POST['playerid']]['inj']);
	unset($_SESSION['game']['home'][$_POST['playerid']]['inj_eff']);
}
if (isset($_POST['clear_a'])) {
	unset($_SESSION['game']['away'][$_POST['playerid']]['changed']);
	unset($_SESSION['game']['away'][$_POST['playerid']]['cp']);
	unset($_SESSION['game']['away'][$_POST['playerid']]['td']);
	$_SESSION['game']['a_td'] = $_SESSION['game']['a_td'] - $_SESSION['game']['away'][$_POST['playerid']]['td'];
	unset($_SESSION['game']['away'][$_POST['playerid']]['in']);
	$_SESSION['game']['a_cas'] = $_SESSION['game']['a_cas'] - $_SESSION['game']['away'][$_POST['playerid']]['ca'];
	unset($_SESSION['game']['away'][$_POST['playerid']]['ca']);
	unset($_SESSION['game']['away'][$_POST['playerid']]['mv']);
	unset($_SESSION['game']['away'][$_POST['playerid']]['rs']);
	unset($_SESSION['game']['away'][$_POST['playerid']]['ps']);
	unset($_SESSION['game']['away'][$_POST['playerid']]['bl']);
	unset($_SESSION['game']['away'][$_POST['playerid']]['fl']);
	unset($_SESSION['game']['away'][$_POST['playerid']]['inj']);
	unset($_SESSION['game']['away'][$_POST['playerid']]['inj_eff']);
}
if (isset($_POST['played'])) {
	$_SESSION['game']['played'] = $_POST['played'];
	$_SESSION['game']['gate'] = $_POST['gate'];
}
if (isset($_POST['playerid'])) {
	if (isset($_POST['home'])) {
		$this_player = &$_SESSION['game']['home'];
		$_SESSION['game']['h_td'] = $_SESSION['game']['h_td'] + $_POST['td'];
		$_SESSION['game']['h_cas'] = $_SESSION['game']['h_cas'] + $_POST['cas'];
	} elseif (isset($_POST['away'])) {
		$this_player = &$_SESSION['game']['away'];
		$_SESSION['game']['a_td'] = $_SESSION['game']['a_td'] + $_POST['td'];
		$_SESSION['game']['a_cas'] = $_SESSION['game']['a_cas'] + $_POST['cas'];
	}
	$this_player[$_POST['playerid']]['changed'] = 1;
	$this_player[$_POST['playerid']]['cp'] = $_POST['cpl'];
	$this_player[$_POST['playerid']]['td'] = $_POST['td'];
	$this_player[$_POST['playerid']]['in'] = $_POST['int'];
	$this_player[$_POST['playerid']]['ca'] = $_POST['cas'];
	$this_player[$_POST['playerid']]['mv'] = $_POST['mvp'];
	$this_player[$_POST['playerid']]['rs'] = $_POST['rush'];
	$this_player[$_POST['playerid']]['ps'] = $_POST['pass'];
	$this_player[$_POST['playerid']]['bl'] = $_POST['block'];
	$this_player[$_POST['playerid']]['fl'] = $_POST['foul'];
	$this_player[$_POST['playerid']]['inj'] = $_POST['inj'];
	$this_player[$_POST['playerid']]['inj_eff'] = (2 == $_POST['inj'] ? 7 : $_POST['inj_type']) ;
}
select_forms();
head_std("Admin");
body_top();
echo "<script type=\"text/javascript\" src=\"includes/wz_tooltip.js\"></script>";
if (isset($_SESSION['userID'])) { // We must be logged in to even se something here
	// Draw the results screen
	echo "<div align=center>\n";
	echo "<table border=1 cellspacing=0 cellpadding=0 width=800>";
		echo "<tr><td>\n";
		// Draw the tabs and links to other admin-functions
		echo "<table cellspacing=0 cellpadding=0>";
			echo "<tr>\n";
			echo "<td>\n";
			echo "<table border=1 cellspacing=0 cellpadding=0 width=200>";
				echo "<tr><td align=center><a href=admin.php><B>LEAGUES</B></a></td></tr>";
			echo "</table>\n</td>";
			// Selectable tabs (links)
			echo "<td>\n";
			echo "<table border=1 cellspacing=0 cellpadding=0 width=200>";
				echo "<tr><td align=center><a href=a_teams.php><B>TEAMS</B></a></td></tr>";
			echo "</table>\n</td>";
			// Selected tab
			echo "<td align=center width=200><B>RESULTS</B></a></td>";
			echo "<td>\n";
			echo "<table border=1 cellspacing=0 cellpadding=0 width=200>";
				echo "<tr><td align=center>";
				echo "<a href=a_users.php><B>USERS</B></a>";
				echo "</td></tr>";
			echo "</table>\n</td>";
			echo "</tr>";
			echo "<tr><td colspan=4 align=center>";
			// Start of results-code
			if (isset($_SESSION['fixtureID'])) {
				// View the add results screen if fixture is selected
				results_forms();
			} else {
				// Else ask fixture to be selected
				echo "<P><B>Select fixture to enter results.</B>";
			}
			echo "</td></tr>\n";
			// Display errors if set.
			echo "<tr><td align=center colspan=4><B><I>" . $_SESSION['admin_err'] . "</I></B></td></tr>\n";
			unset($_SESSION['admin_err']);
		echo "</table></td></tr>\n";
	echo "</table>\n";
}
echo "</body>\n</html>";
?>