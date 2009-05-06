<?php
include 'header.inc.php';
include 'header_edit.inc.php';
include 'sql_connect.php';
?>
</head>
<body bgcolor="#203264">

<center>

<P><table border=1 cellpadding=1 cellspacing=1 bgcolor="#ffffff"><tr><td>

<table summary="Main View">

<tr><td bgcolor="#ffffff" colspan=2 align=center>

<?php

// : Delete data check queries & delete queries if no dependencies :

if ($_POST['del_league']) {

  $sql = "select count(*) from t_season where t_league_ID = " . $_POST['league'] ;

  $r_sql = mysql_query($sql) or die ("Count season failed!\n" . $sql);

  $n_sql = mysql_fetch_array($r_sql);

  if ($n_sql[0] > 0) { echo "<h2>Cannot delete league, season(s) still exists!</h2>"; }

  else {

    $sql = "delete from t_league where t_league_ID = " . $_POST['league'] ;

    $r_sql = mysql_query($sql) or die ("Delete league failed!\n" . $sql);

  }

  unset($_POST['league']);

}

if ($_POST['del_season']) {

  $sql = "select count(*) from t_division where t_season_ID = " . $_POST['season'] ;

  $r_sql = mysql_query($sql) or die ("Count division failed!\n" . $sql);

  $n_sql = mysql_fetch_array($r_sql);

  if ($n_sql[0] > 0) { echo "<h2>Cannot delete season, division(s) still exists!</h2>"; }

  else {

    $sql = "delete from t_season where t_season_ID = " . $_POST['season'] ;

    $r_sql = mysql_query($sql) or die ("Delete season failed!\n" . $sql);

  }

  unset($_POST['season']);

}

if ($_POST['del_division']) {

  $sql = "select count(*) from t_game where t_division_ID = " . $_POST['division'] ;

  $r_sql = mysql_query($sql) or die ("Count game failed!\n" . $sql);

  $n_sql = mysql_fetch_array($r_sql);

  if ($n_sql[0] > 0) { echo "<h2>Cannot delete division, game(s) still exists!</h2>"; }

  else {

    $sql = "delete from t_division where t_division_ID = " . $_POST['division'] ;

    $r_sql = mysql_query($sql) or die ("Delete division failed!\n" . $sql);

  }

  unset($_POST['division']);

}

if ($_POST['del_game']) {

  $sql = "delete from t_game where t_game_ID = " . $_POST['gameid'] ;

	$r_sql = mysql_query($sql) or die ("Delete game failed!\n");

}

// : New data save queries

if ($_POST['save_league']) {

  $sql = "insert into t_league values(NULL, '" . $_POST['league_name'] . "')";

  $r_sql = mysql_query($sql) or die ("Insert new league failed!\n" . $sql);

}

if ($_POST['save_season']) {

  $sql = "insert into t_season values(NULL, " . $_POST['league'] . ",'" . $_POST['season_name'] . "','" . date("Y-m-d") . "')";

  $r_sql = mysql_query($sql) or die ("Insert new season failed!\n" . $sql);

}

if ($_POST['save_division']) {

  $sql = "insert into t_division values(NULL, '" . $_POST['division_name'] . "', " . $_POST['season'] . ")";

  $r_sql = mysql_query($sql) or die ("Insert new division failed!\n" . $sql);

}

if ($_POST['save_game']) {

  $sql = "insert into t_game values(NULL," . $_POST['division'] . "," . $_POST['n_h_team'] . "," . $_POST['n_a_team'] . ",'" . $_POST['n_date'] . "'," . $_POST['n_h_td'] . "," . $_POST['n_a_td'] . "," . $_POST['n_h_cas'] . "," . $_POST['n_a_cas'] . "," . $_POST['n_gate'] . "," . $_POST['n_h_win'] . "," . $_POST['n_a_win'] . ",'" . $_POST['n_comment'] . "')";

  $r_sql = mysql_query($sql) or die ("Insert new game failed!\n" . $sql);

}

// : New data input forms :

if ($_POST['new_league']) {

// :If new_league is pressed, display league input-form:

?><form method=post action="<?php echo $PHP_SELF ?>">

League name: <input type="text" name="league_name">

<input type="Submit" name="save_league" value="Save">

</form>

<?php

} elseif ($_POST['new_season']) {

// :If new_season is pressed, display season input-form:

?><form method=post action="<?php echo $PHP_SELF ?>">

<b><i>League: <?php echo $_POST['league_name'] ?> / Season name: <input type="text" name="season_name"></i></b>

<input type=hidden name="league" value="<?php echo $_POST['league'] ?>">

<input type=hidden name="league_name" value="<?php echo $_POST['league_name'] ?>">

<input type="Submit" name="save_season" value="Save">

</form>

<?php

} elseif ($_POST['new_division']) {

// :If new_division is pressed, display division input-form:

?><form method=post action="<?php echo $PHP_SELF ?>">

<b><i>League: <?php echo $_POST['league_name'] ?> / Season: <?php echo $_POST['league_name'] ?> / Division name: <input type="text" name="division_name"></i></b>

<input type=hidden name="league" value="<?php echo $_POST['league'] ?>">

<input type=hidden name="league_name" value="<?php echo $_POST['league_name'] ?>">

<input type=hidden name="season" value="<?php echo $_POST['season'] ?>">

<input type=hidden name="season_name" value="<?php echo $_POST['season_name'] ?>">

<input type="Submit" name="save_division" value="save">

</form>

<?php

} elseif (!$_POST['league']) {

?>

<form method=post action="<?php echo $PHP_SELF ?>">

<?php

  // :If no league are selected:

  //  View the select league dropdown

  $q_league = 'SELECT * FROM t_league';

  $r_league = mysql_query($q_league) or die ("League query failed");

	echo "League: <select name=league>\n";

	while ($n_league = mysql_fetch_array($r_league)) {

				echo "<option value=" . $n_league['t_league_ID'] . ">" . $n_league['t_league_name'] . "</option>\n";

	}

	echo "</select>";

?>

&nbsp;<input type="Submit" name="new_league" value="New"><br>

&nbsp;<input type="Submit" name="submit" value="Next >>"></td></tr>

</form>

<?php

} elseif (!$_POST['season']) {

?>

<form method=post action="<?php echo $PHP_SELF ?>">

<?php

  $q_league = 'SELECT t_league_name FROM t_league WHERE t_league_ID = ' . $_POST['league'] ;

  $r_league = mysql_query($q_league) or die ("Select League query failed" . "<br>" . mysql_error() . "<br>" . $q_league);

	$n_league = mysql_fetch_array($r_league);

	$league_name = $n_league['t_league_name'];

?>

<input type=hidden name="league" value="<?php echo $_POST['league'] ?>">

<input type=hidden name="league_name" value="<?php echo $league_name ?>">

<?php

  // ::if no season are selected::

  //   Display the selected league

?><b><i><input type="submit" name="del_league" size="1" value="X"><?php

  echo "League: " . $league_name . " / </i></b>";

  //   View the select season dropdown

  $q_season = 'SELECT * FROM t_season WHERE t_league_ID = ' . $_POST['league']. ' ORDER BY t_season_start DESC';

  $r_season = mysql_query($q_season) or die ("Select Season query failed" . "<br>" . mysql_error() . "<br>" . $q_league);

	echo "Season: <select name=season>\n";

	while ($n_season = mysql_fetch_array($r_season)) {

				echo "<option value=" . $n_season['t_season_ID'] . ">" . $n_season['t_season_name'] . "</option>\n";

	}

	echo "</select>";

?>

&nbsp;<input type="Submit" name="new_season" value="New"><br>

&nbsp;<input type="Submit" name="submit" value="Next >>"></td></tr>

</form>

<?php

} elseif (!$_POST['division']) {

?>

<form method=post action="<?php echo $PHP_SELF ?>">

<?php

  $q_season = 'SELECT t_season_name FROM t_season WHERE t_season_ID = ' . $_POST['season'];

  $r_season = mysql_query($q_season) or die ("Season query failed");

	$n_season = mysql_fetch_array($r_season);

	$season_name = $n_season['t_season_name'];

?>

<input type=hidden name="league" value="<?php echo $_POST['league'] ?>">

<input type=hidden name="league_name" value="<?php echo $_POST['league_name'] ?>">

<input type=hidden name="season" value="<?php echo $_POST['season'] ?>">

<input type=hidden name="season_name" value="<?php echo $season_name ?>">

<?php

  // :::if no division are selected::

  //    Display the selected league and season

  echo "<b><i>League: " . $_POST['league_name'] . " / ";

?><input type="submit" name="del_season" size="1" value="X"><?php

	echo "Season: " . $_POST['season_name'] . " / </i></b>";

  //	  Display the select division dropdown

  $q_division = 'SELECT * FROM t_division WHERE t_season_ID = ' . $_POST['season'];

  $r_division = mysql_query($q_division) or die ("Division query failed");

	echo "Division: <select name=division>\n";

	while ($n_division = mysql_fetch_array($r_division)) {

				echo "<option value=" . $n_division['t_division_ID'] . ">" . $n_division['t_division_name'] . "</option>\n";

	}

	echo "</select>";

?>

&nbsp;<input type="Submit" name="new_division" value="New"><br>

&nbsp;<input type="Submit" name="submit" value="Next >>"></td></tr>

</form>

<?php

} elseif (!$_POST['game']) {

?><form method=post action="<?php echo $PHP_SELF ?>"><?php

  $q_division = 'SELECT * FROM t_division WHERE t_division_ID = ' . $_POST['division'];

  $r_division = mysql_query($q_division) or die ("Division query failed");

	$n_division = mysql_fetch_array($r_division);

	$division = $n_division[0];

	$division_name = $n_division[1];

  //    Display the selected league, season and division*/

  echo "<b><i>League: " . $_POST['league_name'] . " / ";

	echo "Season: " . $_POST['season_name'] . " / ";

?><b><i><input type="submit" name="del_division" size="1" value="X"><?php

	echo "Division: " . $division_name . "</i></b>\n" ;

	?>

<input type=hidden name="league" value="<?php echo $_POST['league'] ?>">

<input type=hidden name="league_name" value="<?php echo $_POST['league_name'] ?>">

<input type=hidden name="season" value="<?php echo $_POST['season'] ?>">

<input type=hidden name="season_name" value="<?php echo $_POST['season_name'] ?>">

<input type=hidden name="division" value="<?php echo $division ?>">

<input type=hidden name="division_name" value="<?php echo $division_name ?>">

<br>&nbsp;<input type="Submit" name="game" value="View games >>">

</form>

</td></tr></table></td></tr>

<?php

} else {

  // :View games

  echo "<b><i>League: " . $_POST['league_name'] . " / ";

	echo "Season: " . $_POST['season_name'] . " / ";

	echo "Division: " . $_POST['division_name'] . "</i></b>\n" ;

	echo "</td></tr></table></td></tr></table><hr>\n";

	echo "<table summary=\"Results table\" bgcolor=#ffffff border=1 cellpadding=0 cellspacing=0>";

	echo "<tr><th colspan=3>&nbsp;</th><th colspan=2>Touchdowns</th><th colspan=2>Casualities</th><th>&nbsp;</th><th colspan=2>Winnings</th><th colspan=2>&nbsp;</th></tr>\n";

	echo "<tr><th>Date</th><th>Home</th><th>Visitors</th><th>H</th><th>V</th><th>H</th><th>V</th><th>Gate</th><th>H</th><th>V</th><th>Comment</th><th>&nbsp</th></tr>\n";

  // :View form for new game:

	$today = getdate();

	$month = $today['mon'];

	$mday = $today['mday'];

	$year = $today['year'];

  echo "<tr><td valign=middle><form method=post action=" . $PHP_SELF .">";

	echo "<input type=text size=10 align=middle name=n_date value=" . $year . '-' . sprintf('%02u',$month) . '-' . sprintf('%02u',$mday) . "></td>";

	echo "<td><select name=n_h_team>\n";

	$q_team = 'SELECT t_team_ID, t_team_name FROM t_team ORDER BY t_team_name';

	$r_team = mysql_query($q_team) or die ("Get select teams failed");

	while ($n_team = mysql_fetch_array($r_team)) {

	  echo "<option value=" . $n_team['t_team_ID'] . ">" . $n_team['t_team_name'] . "</option>";

	}

	echo "</select></td><td><select name=n_a_team>\n";

	$q_team = 'SELECT t_team_ID, t_team_name FROM t_team ORDER BY t_team_name';

	$r_team = mysql_query($q_team) or die ("Get select teams failed");

	while ($n_team = mysql_fetch_array($r_team)) {

	  echo "<option value=" . $n_team['t_team_ID'] . ">" . $n_team['t_team_name'] . "</option>";

	}

	echo "</select>";

  echo "<td><input type=text align=right name=n_h_td size=3 value=0></td>";

  echo "<td><input type=text align=right name=n_a_td size=3 value=0></td>";

  echo "<td><input type=text align=right name=n_h_cas size=3 value=0></td>";

  echo "<td><input type=text align=right name=n_a_cas size=3 value=0></td>";

  echo "<td><input type=text align=right name=n_gate size=4 value=0></td>";

  echo "<td><input type=text align=right name=n_h_win size=4 value=0></td>";

  echo "<td><input type=text align=right name=n_a_win size=4 value=0><input type=hidden name=division value=" . $_POST['division'] . "></td>";

  echo "<td><input type=text name=n_comment size=20></td>";

	echo "<td><input type=\"Submit\" name=\"save_game\" value=\"Add game\">";

?>

<input type=hidden name="league" value="<?php echo $_POST['league'] ?>">

<input type=hidden name="league_name" value="<?php echo $_POST['league_name'] ?>">

<input type=hidden name="season" value="<?php echo $_POST['season'] ?>">

<input type=hidden name="season_name" value="<?php echo $_POST['season_name'] ?>">

<input type=hidden name="division_name" value="<?php echo $_POST['division_name'] ?>">

<input type=hidden name=game value=1>

<?php

	echo "</form></td>/tr>\n";



	// :View played games:

	$q_games = 'SELECT t_game_ID, h.t_team_name, a.t_team_name, t_game_date, t_game_h_td, t_game_a_td,'

					 . ' t_game_h_cas, t_game_a_cas, t_game_gate, t_game_h_win, t_game_a_win, t_game_comment'

					 . ' FROM t_game g, t_team h, t_team a WHERE t_division_ID = ' . $_POST['division']

					 . ' AND t_h_team_ID = h.t_team_ID'

					 . ' AND t_a_team_ID = a.t_team_ID'

					 . ' ORDER BY t_game_date DESC';

	$r_games = mysql_query($q_games) or die ("Get games query failed!");

	while ($n_games = mysql_fetch_array($r_games)) {

	  	echo "<tr><form method=post action=" . $PHP_SELF ."><input type=hidden name=gameid value=" . $n_games[0] . ">\n" ;

?>

<input type=hidden name="league" value="<?php echo $_POST['league'] ?>">

<input type=hidden name="league_name" value="<?php echo $_POST['league_name'] ?>">

<input type=hidden name="season" value="<?php echo $_POST['season'] ?>">

<input type=hidden name="season_name" value="<?php echo $_POST['season_name'] ?>">

<input type=hidden name="division" value="<?php echo $_POST['division'] ?>">

<input type=hidden name="division_name" value="<?php echo $_POST['division_name'] ?>">

<input type=hidden name=game value=1>

<?php

	  	echo "<td align=center>" . $n_games[3] . "</td>";

		echo "<td>" . $n_games[1] . "</td>";

		echo "<td>" . $n_games[2] . "</td>";

		echo "<td align=center>" . $n_games[4] . "</td>";

		echo "<td align=center>" . $n_games[5] . "</td>";

		echo "<td align=center>" . $n_games[6] . "</td>";

		echo "<td align=center>" . $n_games[7] . "</td>";

		echo "<td align=right>" . $n_games[8] . "</td>";

		echo "<td align=center>" . $n_games[9] . "</td>";

		echo "<td align=center>" . $n_games[10] . "</td>";

		echo "<td>" . $n_games[11] . "</td>";

		echo "<td><input type=Submit name=del_game value=X></form></td></tr>\n";

	}

	echo "</table>\n";

  // :Last End if

};

?>

</table>

</center>

</body>

</html>
