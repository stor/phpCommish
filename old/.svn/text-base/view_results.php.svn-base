<?php 
session_start();
?>
<html>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<head>
	<meta http-equiv="content-type" content="text/html;charset=ISO-8859-1">
	<title>View Leagues</title>
<?php
include 'header.inc.php';
include 'header_view.inc.php';
?>
</head>
<body bgcolor="#203264">
	<center>
	<P>
	<table summary="Main View">
		<tr>
			<td bgcolor="#ffffff" align=center>
<?php
if (isset($_SESSION['divisionid'])) {
	$q_division = 'SELECT t_league_name , t_season_name , t_division_name FROM t_division d , t_season s , t_league l '
        . ' WHERE d . t_division_ID = ' . $_SESSION['divisionid']
        . ' AND d . t_season_ID = s . t_season_ID '
        . ' AND s . t_league_ID = l . t_league_ID LIMIT 0, 30 ';
	$r_division = mysql_query($q_division) or die ("Division query failed");
	$n_division = mysql_fetch_array($r_division);
	$division_name = $n_division[1];
	//    Display the selected league, season and division*/
	echo "<b><i>League: " . $n_division['t_league_name'] . " / Season: " . $n_division['t_season_name'] . " / Division: " . $n_division['t_division_name'] . "</i></b>\n" ;
?>

		<tr>
			<td valign=top colspan=2 align=center>
<?php
	// :::Display table
	viewtable($_SESSION['divisionid'])
?>
				<br>
				<table summary="Results table" bgcolor=#ffffff border=1 cellpadding=0 cellspacing=0>
					<tr>
						<td colspan=9 align=center><b><i>Results</i></b></td>
					</tr>
					<tr>
						<th>Date</th>
						<th align=left>Home</th>
						<th align=left>Visitors</th>
						<th>Touchdowns</th>
						<th>Casualities</th>
						<th>Gate</th>
						<th>Winnings (H)</th>
						<th>Winnings (V)</th>
						<th align=left>Comment</th>
					</tr>
<?php

	// :::Display results
	$q_res = 'SELECT g.*, h.t_team_name, a.t_team_name FROM `t_game` g, `t_team` h, `t_team` a WHERE'
		. ' t_h_team_ID = h.t_team_ID'
		. ' AND t_a_team_ID = a.t_team_ID'
		. ' AND t_division_ID = ' . $_SESSION['divisionid']
		. ' ORDER BY t_game_date' ;
	$r_res = mysql_query($q_res) or die ("<tr><td colspan=9>Results query failed " . $q_res . "</td></tr>");
	while ($n_res = mysql_fetch_array($r_res)) {
		echo "<tr><td>" . $n_res[4] . "</td>\n";
		echo "<td>" . $n_res[13] . "</td>\n";
		echo "<td>" . $n_res[14] . "</td>\n";
		echo "<td align=center>" . $n_res['t_game_h_td'] . " - " . $n_res['t_game_a_td'] . "</td>\n";
		echo "<td align=center>" . $n_res['t_game_h_cas'] . " - " . $n_res['t_game_a_cas'] ."</td>\n";
		echo "<td align=right>" . number_format($n_res[9]) . "</td>\n";
		echo "<td align=right>" . number_format($n_res[10]) . "</td>\n";
		echo "<td align=right>" . number_format($n_res[11]) . "</td>\n";
		echo "<td>" . $n_res[12] . "</td>\n";
		echo "</tr>";
	}
	echo "</table>";
// :End if
}
?>

	</table>
	</center>
</body>
</html>