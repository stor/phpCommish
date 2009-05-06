<?php
session_start();

include 'includes/library.inc.php';
include 'includes/sql_connect.php';

// : View the team, players and all given two variables in the link :
// : Variables are team=<teamID> and jbb=<javabowl-compatible>      :

// : Get basic team stats :

  $q_team_d = 'SELECT t_team_name, t_team_coach, t_team_rerolls, t_team_fanfactor, t_team_assistant_coaches, t_team_cheerleaders, t_team_apothecary, t_team_treasury, t_race_name, t_race_rerollprice FROM t_team t, t_race r'

        . ' WHERE t_team_ID = ' . $_REQUEST['team'] . ' AND t.t_race_ID = r.t_race_ID';
  $r_team_d = mysql_query($q_team_d) or die ("Team details query failed! " . mysql_error());

  $n_team_d = mysql_fetch_array($r_team_d);

?>

<HTML><HEAD>
		<meta http-equiv="content-type" content="text/html;charset=ISO-8859-1">
		<TITLE><?php echo $n_team_d['t_team_name']; ?></TITLE>

</HEAD>

<BODY BGCOLOR="#FFFFFF" TEXT="#000000" LINK="#000000" VLINK="#000000">

<CENTER><FONT FACE="helvetica" SIZE=4 COLOR="#000000">

<H1><B><?php echo $n_team_d['t_team_name']; ?></B></H1>

Race: <?php echo ucwords(strtolower($n_team_d['t_race_name'])); ?><BR>

Coached By: <?php echo $n_team_d['t_team_coach']; ?><BR>

<?php
printroster($_REQUEST['team'],$_REQUEST['jbb']);
?>
<table bordercolorlight="#FFFFF0" bordercolordark="#BFBF90" bgcolor="#ffffd0" border="1">

<tbody><tr align="center" bgcolor="#d0d0a0"><th>Result</th><th>Opponent</th><th>Race</th><th>Score</th><th>Cas</th><th>Gate</th><th>Winnings</th><th>Notes &amp; Highlights</th></tr>

<?php

$q_res = 'SELECT g.*, h.t_team_name home, hr.t_race_name h_race, a.t_team_name visitors, ar.t_race_name a_race FROM t_game g, t_team h, t_team a, t_race hr, t_race ar WHERE'
        . ' t_h_team_ID = h.t_team_ID'
        . ' AND t_a_team_ID = a.t_team_ID'
        . ' AND h.t_race_ID = hr.t_race_ID'
        . ' AND a.t_race_ID = ar.t_race_ID'
        . ' AND (t_h_team_ID = ' . $_REQUEST['team'] . ' OR t_a_team_ID = ' . $_REQUEST['team'] . ')'
        . ' ORDER BY t_game_played';
$r_res = mysql_query($q_res) or die("Error fetching games! " . mysql_error() . " # " . $q_res);

while($n_res = mysql_fetch_array($r_res)){

	echo "<tr><td align=center>" . (($n_res['t_game_h_td'] == $n_res['t_game_a_td']) ? 'Draw' : '' );
	if ($n_res['t_h_team_ID'] == $_REQUEST['team']) {
		echo (($n_res['t_game_h_td'] > $n_res['t_game_a_td']) ? 'Win' : '' );
		echo (($n_res['t_game_h_td'] < $n_res['t_game_a_td']) ? 'Loss' : '' );
		echo "</td><td>" . $n_res['visitors'];
		echo "</td><td>" . ucwords(strtolower($n_res['a_race']));
		echo "</td><td align=center>" . $n_res['t_game_h_td'] . " - " . $n_res['t_game_a_td'];
		echo "</td><td align=center>" . $n_res['t_game_h_cas'] . " - " . $n_res['t_game_a_cas'];
		echo "</td><td align=right>" . $n_res['t_game_gate'];
		echo "</td><td align=right>" . $n_res['t_game_h_win'];
	} else {
		echo (($n_res['t_game_h_td'] < $n_res['t_game_a_td']) ? 'Win' : '' );
		echo (($n_res['t_game_h_td'] > $n_res['t_game_a_td']) ? 'Loss' : '' );
		echo "</td><td>" . $n_res['home'];
		echo "</td><td>" . ucwords(strtolower($n_res['h_race']));
		echo "</td><td align=center>" . $n_res['t_game_a_td'] . " - " . $n_res['t_game_h_td'];
		echo "</td><td align=center>" . $n_res['t_game_a_cas'] . " - " . $n_res['t_game_h_cas'];
		echo "</td><td align=right>" . $n_res['t_game_gate'];
		echo "</td><td align=right>" . $n_res['t_game_a_win'];
	}
	echo "</td><td>" . (($n_res['t_game_comment']) ? $n_res['t_game_comment'] : '&nbsp');
	echo "</td></tr>\n";
} // while



?>

</tbody></table>

</body>

</html>



