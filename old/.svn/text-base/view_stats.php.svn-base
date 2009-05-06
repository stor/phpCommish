<?php ?>
<html>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<head>
	<meta http-equiv="content-type" content="text/html;charset=ISO-8859-1">
	<title>View Leagues</title>
<?php
include 'header.inc.php';
include 'header_view.inc.php';
include 'edit/sql_connect.php';
//include 'edit/library.inc.php';
?>
</head>
<body bgcolor="#203264">
	<center>
	<P>
	<table summary="Main View">
		<tr>
			<td bgcolor="#ffffff" align=center>

<?php
if (!$_POST['league']) {
?>

				<form method=post action="<?php echo $PHP_SELF ?>">

<?php
	// :If no league are selected:
	//  View the select league dropdown
	$q_league = 'SELECT * FROM t_league';
	$r_league = mysql_query($q_league) or die ("League query failed");
	echo "League: <select name=league onchange=\"this.form.submit()\">\n";
	echo "<option value=0 selected>Choose</option>";
	while ($n_league = mysql_fetch_array($r_league)) {
		echo "<option value=" . $n_league['t_league_ID'] . ">" . $n_league['t_league_name'] . "</option>\n";
	}
	echo "</select>";
//	echo "<br><input type=\"Submit\" name=\"submit\" value=\"Next >>\"></td>";
?>
				</form>
			</td>
		</tr>

<?php
} elseif ($_POST['season'] == FALSE) {
?>

				<form method=post action="<?php echo $PHP_SELF ?>">

<?php
	$q_league = 'SELECT t_league_name FROM t_league WHERE t_league_ID = ' . $_POST['league'];
	$r_league = mysql_query($q_league) or die ("League query failed");
	$n_league = mysql_fetch_array($r_league);
	$league_name = $n_league['t_league_name'];
?>
					<input type=hidden name="league" value="<?php echo $_POST['league'] ?>">
					<input type=hidden name="league_name" value="<?php echo $league_name ?>">

<?php
	// ::if no season are selected::
	//   Display the selected league
	echo "<b><i>League: " . $league_name . " / </i></b>";
	//   View the select season dropdown
	$q_season = 'SELECT * FROM t_season WHERE t_league_ID = ' . $_POST['league'] . ' ORDER BY t_season_start DESC';
	$r_season = mysql_query($q_season) or die ("Season query failed");
	echo "Season: <select name=season onchange=\"this.form.submit()\">\n";
	echo "<option value=0 selected>Choose</option>";
	while ($n_season = mysql_fetch_array($r_season)) {
		echo "<option value=" . $n_season['t_season_ID'] . ">" . $n_season['t_season_name'] . "</option>\n";
	}
	echo "</select>";
//	echo "<br><input type=\"Submit\" name=\"submit\" value=\"Next >>\"></td></tr>";
?>
				</form>
			</td>
		</tr>

<?php
} elseif ($_POST['division'] == FALSE) {
?>

				<form method=post action="<?php echo $PHP_SELF ?>">

<?php
	$q_league = 'SELECT t_league_name FROM t_league WHERE t_league_ID = ' . $_POST['league'];
	$r_league = mysql_query($q_league) or die ("League query failed");
	$n_league = mysql_fetch_array($r_league);
	$league_name = $n_league['t_league_name'];
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
	echo "<b><i>League: " . $league_name . " / Season: " . $season_name . " / </i></b>";
	//	  Display the select division dropdown
	$q_division = 'SELECT * FROM t_division WHERE t_season_ID = ' . $_POST['season'];
	$r_division = mysql_query($q_division) or die ("Division query failed");
	echo "Division: <select name=division onchange=\"this.form.submit()\">\n";
	echo "<option value=0 selected>Choose</option>";
	while ($n_division = mysql_fetch_array($r_division)) {
		echo "<option value=" . $n_division['t_division_ID'] . ">" . $n_division['t_division_name'] . "</option>\n";
	}
	echo "</select>";
//	echo "<br><input type=\"Submit\" name=\"submit\" value=\"Next >>\"></td></tr>";
?>
				</form>
			</td>
		</tr>
<?php
} else {

	$q_division = 'SELECT * FROM t_division WHERE t_division_ID = ' . $_POST['division'];
	$r_division = mysql_query($q_division) or die ("Division query failed");
	$n_division = mysql_fetch_array($r_division);
	$division_name = $n_division[1];
	//    Display the selected league, season and division*/
	echo "<b><i>League: " . $_POST['league_name'] . " / Season: " . $_POST['season_name'] . " / Division: " . $division_name . "</i></b>\n" ;
	echo "</td></tr>\n";
	echo "<tr><td>";
	t_gate_stat($_POST['division']);
	echo "</td></tr>";
	echo "<tr><td>";
	// Does not work
	//	t_winnings_worst($division);
	echo "</td></tr>";
	echo "</table>";
}
?>

	</table>
	</center>
</body>
</html>