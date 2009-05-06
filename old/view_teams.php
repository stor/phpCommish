<?php
session_start();
//if ($_POST['team'] == FALSE) {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
		<meta http-equiv="content-type" content="text/html;charset=ISO-8859-1">
		<title>View teams</title>
<?php
include 'header.inc.php';
include 'header_view.inc.php';
?>
</head>
<body bgcolor="#203264">
<P>
<center><table summary="Main View">
<tr><td bgcolor="#ffffff" align=center>
<?php
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
</td>
</tr>
<tr><td bgcolor="#ffffff" align=center>
<form method=post action="<?php echo $PHP_SELF ?>">
Javabowl compatible<input type="checkbox" value= 1 name="jbb"<?php echo ( ($_POST['jbb'] == 1) ? " checked" : "" ) ?>>
<?php
  // :If no team are selected:
  //  View the select team dropdown
  $q_team = 'SELECT * FROM t_team t, t_division_team d WHERE t.t_team_ID = d.t_team_ID AND d.t_division_ID = ' . $_SESSION['divisionid'] . ' ORDER BY t_team_name';
  $r_team = mysql_query($q_team) or die ("Team query failed! " . mysql_error());
	echo "<select name=team onchange=\"this.form.submit()\">\n";
	echo ( !$_POST['team'] ? "<option value=0 selected>Select team</option>\n" : "" ) ;
	while ($n_team = mysql_fetch_array($r_team)) {
				echo "<option value=" . $n_team['t_team_ID'] . ( ($_POST['team'] == $n_team['t_team_ID']) ? " selected" : "" ) . ">" . $n_team['t_team_name'] . "</option>\n";
	}
	echo "</select>";
?>

<?php //<br><input type="Submit" name="submit" value="View team >>"></TD></tr> ?>
</form>
<?php
if (isset($_POST['team'])) {
//	include 'header.inc.php';
//	include 'header_view.inc.php';
	// : View the team, players and all :
	// : Get basic team stats :
	$q_team_d = 'SELECT t_team_name, t_team_coach, t_team_rerolls, t_team_fanfactor, t_team_assistant_coaches, t_team_cheerleaders, t_team_apothecary, t_team_treasury, t_race_name, t_race_rerollprice FROM t_team t, t_race r'
        . ' WHERE t_team_ID = ' . $_POST['team'] . ' AND t.t_race_ID = r.t_race_ID';
	$r_team_d = mysql_query($q_team_d) or die ("Team details query failed! " . mysql_error());
	$n_team_d = mysql_fetch_array($r_team_d);
?>
</table>
<CENTER><FONT FACE="helvetica" SIZE=4 COLOR="#FFFFFF">
<H1><B><?php echo $n_team_d['t_team_name']; ?></B></H1>
<P>Race: <?php echo ucwords(strtolower($n_team_d['t_race_name'])); ?></P>
<P>Coached By: <?php echo $n_team_d['t_team_coach']; ?></P>
<P><FORM method=post action="view_teams_preview.php?team=<?php echo $_POST['team'] . '&jbb=' . $_POST['jbb'];?>">
<input type=hidden name=team value="<?php echo $_POST['team'] ;?>">
<input type=submit value="Print/Export team">
</FORM></P>
<?php
printroster($_POST['team'], $_POST['jbb']);
// : End if
}
?>
</table>
</center>
</body>
</html>
