<?php
session_start();
include "includes/library.inc.php";
include "includes/sql_connect.php";
if ($_POST['save_team']) {
	set_teamstatus($_POST['teamid'],1);
	$q_team = 'UPDATE t_team SET t_team_name = \'' . $_POST['team_name'] . '\''
			. ', t_team_apothecary = ' . ($_POST['apoth'] ? $_POST['apoth'] : 'NULL')
			. ', t_team_cheerleaders = ' . ($_POST['cheer'] ? $_POST['cheer'] : 'NULL')
			. ', t_team_assistant_coaches = ' . ($_POST['assist'] ? $_POST['assist'] : 'NULL')
			. ', t_team_fanfactor = ' . ($_POST['fanfac'] ? $_POST['fanfac'] : 'NULL')
			. ', t_team_rerolls = ' . ($_POST['reroll'] ? $_POST['reroll'] : 'NULL')
			. ', t_team_treasury = ' . ($_POST['treas'] ? $_POST['treas'] : 'NULL')
			. ', t_team_coach = \'' . $_POST['coach'] . '\''
			. ' WHERE t_team_ID = ' . $_POST['team'] ;
	$r_team = get_sqlresult($q_team, "Update team.");
} // End if save_team
if ($_POST['del_team']) {
	// Display "Are you sure?"-warning
	echo "<HTML><HEAD><TITLE>Are you sure?</TITLE></HEAD>"
		. "<style type=\"text/css\">"
		. "<!--"
		. "body,th,td { font-family: helvetica;"
		. "		   font-size: 75% }"
		. ".bold { font-family: helvetica;"
		. "		font-weight: bold }"
		. "-->"
		. "</style>"
		. "<BODY BGCOLOR=\"#203264\" TEXT=\"#000000\" LINK=\"#000000\" VLINK=\"#000000\">";
	if ( ! $_POST['sure'] && ! $_POST['cancel']) {
		echo "<CENTER><FONT FACE=\"helvetica\" SIZE=4 COLOR=\"#FFFFFF\"><H1>Delete team?</H1><BR>"
			. "<H3>Warning: Deleting the team will also delete all players on that team. <B>You cannot undo this!</B><BR>"
			. "Are you sure?</H3>"
			. "<form method=post action=". $_SERVER['PHP_SELF'] .">"
			. "<input type=hidden name=del_team value=1>"
			. "<input type=hidden name=\"teamid\" value=" . $_POST['team'] . ">"
			. "<input type=\"Submit\" name=\"sure\" value=\"Delete\">"
			. "<input type=\"Submit\" name=\"cancel\" value=\"Cancel\">"
			. "</form>";
	} else {	// Delete team if sure
		echo "<form method=post action=admin.php>";
		if ($_POST['sure']) {
			echo "<CENTER><FONT FACE=\"helvetica\" SIZE=4 COLOR=\"#FFFFFF\"><H1>Deleting team</H1><BR>";
			$team = $_POST['teamid'];
			$q_team = 'DELETE FROM t_team '
					. ' WHERE t_team_ID = ' . $team ;
			$r_team = mysql_query($q_team) or die("Error deleting team! " . mysql_error());
			$q_team = 'DELETE FROM t_player '
					. ' WHERE t_team_ID = ' . $team ;
			$r_team = mysql_query($q_team) or die("Error deleting team! " . mysql_error());
			$q_team = 'DELETE FROM t_team_fixture '
					. ' WHERE t_team_ID = ' . $team ;
		} else {
			echo "<CENTER><FONT FACE=\"helvetica\" SIZE=4 COLOR=\"#FFFFFF\"><H1>Delete canceled!</H1><BR>"
				. "<form method=post action=". $_SERVER['PHP_SELF'] .">"
				. "<input type=hidden name=\"teamid\" value=" . $_POST['teamid'] . ">";
		}
		echo "<input type=\"Submit\" name=\"submit\" value=\"Continue\">";
		echo "</form>";
	}
	echo "</body>";
	echo "</html>";
	die();
} // End if del_team;
if ($_POST['inc_ma'] || $_POST['dec_ma'] || $_POST['inc_st'] || $_POST['dec_st'] || $_POST['inc_ag'] || $_POST['dec_ag'] || $_POST['inc_av'] || $_POST['dec_av']) {
	//Increase or decrease stat-mod
	if ($_POST['player_id']) {
		if ($_POST['inc_ma']) { $_POST['ma_mod']++ ; $_POST['price_add'] = $_POST['price_add'] + 30000 ;}
		if ($_POST['dec_ma']) { $_POST['ma_mod']-- ;}
		if ($_POST['inc_st']) { $_POST['st_mod']++ ; $_POST['price_add'] = $_POST['price_add'] + 50000 ;}
		if ($_POST['dec_st']) { $_POST['st_mod']-- ;}
		if ($_POST['inc_ag']) { $_POST['ag_mod']++ ; $_POST['price_add'] = $_POST['price_add'] + 40000 ;}
		if ($_POST['dec_ag']) { $_POST['ag_mod']-- ;}
		if ($_POST['inc_av']) { $_POST['av_mod']++ ; $_POST['price_add'] = $_POST['price_add'] + 30000 ;}
		if ($_POST['dec_av']) { $_POST['av_mod']-- ;}
		//Editing existing player
		set_teamstatus($_POST['teamid'],1);
		$sql = 'UPDATE t_player'
			. ' SET t_player_MA_mod = ' . (($_POST['ma_mod']) ? $_POST['ma_mod'] : 'NULL')
			. ' , t_player_ST_mod = ' . (($_POST['st_mod']) ? $_POST['st_mod'] : 'NULL')
			. ' , t_player_AG_mod = ' . (($_POST['ag_mod']) ? $_POST['ag_mod'] : 'NULL')
			. ' , t_player_AV_mod = ' . (($_POST['av_mod']) ? $_POST['av_mod'] : 'NULL')
			. ' , t_player_priceadd = ' . (($_POST['price_add']) ? $_POST['price_add'] : 'NULL')
			. ' WHERE t_player_ID = ' . $_POST['player_id'] ;
		$r_sql = get_sqlresult($sql,"Update player stats");
	}
}
if ($_POST['del_skill']) {
	//Delete selected skill from skill list
	$sql = 'DELETE FROM t_player_skill WHERE t_player_ID = ' . $_POST['player_id'] . ' AND t_skill_ID = ' . $_POST['d_skill'] ;
	$r_sql = get_sqlresult($sql,"Dele skill");
  	$q_skill = 'SELECT t_skill_ID, t_skill_name, t_skill_type_d FROM t_skill sk, t_positions_skill_type st'
  			. ' WHERE sk.t_skill_type = st.t_skill_type'
  			. ' AND st.t_position_ID = ' . $_POST['positions_id']
  			. ' AND sk.t_skill_ID = ' . $_POST['d_skill'] ;
  	$r_skill = get_sqlresult($q_skill,"Fetch skills for value update");
  	$n_skill = mysql_fetch_array($r_skill);
  	$newval = $_POST['price_add'] - (($n_skill['t_skill_type_d']==0) ? 20000 : 30000);
	set_teamstatus($_POST['teamid'],1);
  	$sql = 'UPDATE t_player'
  		. ' SET t_player_priceadd = ' . $newval
  		. ' WHERE t_player_ID = ' . $_POST['player_id'];
  	$r_sql = get_sqlresult($sql,"Update player value");
/*	$sql = 'SELECT * FROM t_player pl, t_positions_skill_type st, t_player_skill sk'
		. ' WHERE pl.t_position_ID = st.t_position_ID'
		. ' AND pl.
*/
}
if ($_POST['new_skill']) {
	//Add selected skill
	if ($_POST['a_skill'] > 0) {
		$sql = 'INSERT INTO t_player_skill VALUES(' . $_POST['player_id'] . ', ' . $_POST['a_skill'] . ')';
		$r_sql = get_sqlresult($sql,"Add skill");
  		$q_skill = 'SELECT t_skill_ID, t_skill_name, t_skill_type_d FROM t_skill sk, t_positions_skill_type st'
  				. ' WHERE sk.t_skill_type = st.t_skill_type'
  				. ' AND st.t_position_ID = ' . $_POST['positions_id']
  				. ' AND sk.t_skill_ID = ' . $_POST['a_skill'] ;
  		$r_skill = get_sqlresult($q_skill,"Fetch skill for value update");
  		$n_skill = mysql_fetch_array($r_skill);
  		$newval = $_POST['price_add'] + (($n_skill['t_skill_type_d']==0) ? 20000 : 30000);
		set_teamstatus($_POST['teamid'],1);
  		$sql = 'UPDATE t_player'
  			. ' SET t_player_priceadd = ' . $newval
  			. ' WHERE t_player_ID = ' . $_POST['player_id'];
  		$r_sql = get_sqlresult($sql,"Update player value");
	}
}
if ($_POST['sav_player']) {
	//update or save the player
	if ($_POST['player_id']) {

        //Editing existing player
        $niggling = $_POST['niggling'];
        if ($_POST['cniggling']) {
                if ($niggling) {
                        $niggling++;
                } else {
                        $niggling = 1;
                }
        }
        $sql = 'UPDATE t_player'
            . ' SET t_player_niggling = ' . ($niggling ? $niggling : 'NULL')
			. ' , t_player_name = \'' . ($_POST['player_name'] ? $_POST['player_name'] : '') . '\''
			. ' , t_player_miss = ' . ($_POST['miss'] ? $_POST['miss'] : 'NULL')
			. ' , t_player_comp = ' . ($_POST['complete'] ? $_POST['complete'] : 'NULL')
			. ' , t_player_td = ' . ($_POST['touchdowns'] ? $_POST['touchdowns'] : 'NULL')
			. ' , t_player_int = ' . ($_POST['intercept'] ? $_POST['intercept'] : 'NULL')
			. ' , t_player_cas = ' . ($_POST['casuality'] ? $_POST['casuality'] : 'NULL')
			. ' , t_player_mvp = ' . ($_POST['mvp'] ? $_POST['mvp'] : 'NULL')
			. ' , t_player_priceadd = ' . ($_POST['price_add'] ? $_POST['price_add'] : '0')
			. ' WHERE t_player_ID = ' . $_POST['player_id'] ;
		$r_sql = get_sqlresult($sql,"Updating player");
	} else {
		//Adding new player
		$sql = 'INSERT INTO t_player VALUES(NULL, ' . $_POST['teamid'] . ', ' . $_POST['player'] . ', \'' . $_POST['player_name'] . '\', ' . $_POST['positions_id']
			. ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0)';
		$r_sql = get_sqlresult($sql, "Add player");
	}
	set_teamstatus($_POST['teamid'],1);
}
if ($_POST['del_player']) {
	//delete the player from the roster
	$sql = 'DELETE FROM t_player WHERE t_player_ID = ' . $_POST['player_id'] ;
	$r_sql = get_sqlresult($sql,"Deleting player");
	set_teamstatus($_POST['teamid'],1);
}
select_forms();
head_std("Edit team");
body_top();
/*
Variables from the team form:
$team : t_team_ID
$coach, $team_name, $apoth, $cheer, $assist, $fanfac, $reroll, $treas
*/

// Main form for editing team
  	/* Vars from this form:
  	Hidden:
  		$teamid	- t_team_ID
  		$player - t_player_num
  		$player_id 	- t_player_ID
		$positions_id
  	Normal:
  		$player_name - t_player_name
		$niggling
		$miss
		$complete
		$touchdowns
		$intercept
		$casualty
		$mvp
  	Submits:
  		$inc_ma
  		$dec_ma
  		$inc_st
  		$dec_st
  		$inc_ag
  		$dec_ag
  		$inc_av
  		$dec_av
  		$del_skill
  		$sav_skill
  		$del_player
  		$sav_player
	*/
echo "<div align=center><table><tr><td width=130><a href=a_teams.php><-- Back to team list</a></td><td align=center>\n";
echo "<form method=post action=". $_SERVER['PHP_SELF'] .">";
$edit_team = $_POST['teamid'];
// : Display the edit boxes :
//  : Get basic team stats for the edit boxes :
$team = $edit_team ;
$q_team_d = 'SELECT t_team_name, t_team_coach, t_team_rerolls, t_team_fanfactor, t_team_assistant_coaches, t_team_cheerleaders, t_team_apothecary, t_team_treasury, t.t_race_ID, t_race_name, t_race_rerollprice FROM t_team t, t_race r'
    . ' WHERE t_team_ID = ' . $team . ' AND t.t_race_ID = r.t_race_ID';
$r_team_d = get_sqlresult($q_team_d, "Get team details");
$n_team_d = mysql_fetch_array($r_team_d);
$race_id = $n_team_d['t_race_ID'];
echo "<H2>Editing team</h2>\n";
echo "</td></tr><tr><td colspan=2>\n";
echo "<TABLE BORDER=1 BGCOLOR=\"#FFFFD0\" BORDERCOLORLIGHT=\"#FFFFF0\" BORDERCOLORDARK=\"#BFBF90\">";
echo "<TR BGCOLOR=\"#D0D0A0\"><TH class=edit>#</TH><th class=edit>Player Name</TH><th class=edit>Position</TH><th class=edit>ma</TH><th class=edit>st</TH><th class=edit>ag</TH><th class=edit>av</TH><th class=edit>Player Skills</TH><th class=edit>ij</TH><th class=edit>cp</TH><th class=edit>td</TH><th class=edit>in</TH><th class=edit>cs</TH><th class=edit>vp</TH><th class=edit>sp</TH><th class=edit>Cost</TH></TR>";
// : Get and display the players :
$q_player = 'SELECT * FROM t_player pl, t_positions po WHERE t_team_ID = ' . $team
		. ' AND pl.t_positions_ID = po.t_positions_ID'
		. ' ORDER BY t_player_num';
$r_player = get_sqlresult($q_player, "Fetch players");
$n_player = mysql_fetch_array($r_player);
for ($p_num = 1; $p_num <= 16; $p_num++) {
	// : Sub-form :
	// Edit players on team
  	echo "<TR ALIGN=CENTER>\n";
  	/* Vars from this form:
  	Hidden:
  		$edit_team	- t_team_ID
  		$player - t_player_num
  		$player_id 	- t_player_ID
  		$positions_id
  		$skill
  		$price_add
  	Normal:
  		$player_name - t_player_name
		$niggling
		$miss
		$complete
		$touchdowns
		$intercept
		$casualty
		$mvp
  	Submits:
  		$inc_ma
  		$dec_ma
  		$inc_st
  		$dec_st
  		$inc_ag
  		$dec_ag
  		$inc_av
  		$dec_av
  		$del_skill
  		$sav_skill
  		$del_player
  		$sav_player
	*/
  	echo "<td class=edit class=edit>";
  	echo "<form method=post action=". $_SERVER['PHP_SELF'] .">";
	echo "<input type=hidden name=\"teamid\" value=" . $team . ">\n" ;
	echo "<input type=hidden name=\"player\" value=" . $p_num . ">\n" ;
	echo "<input type=hidden name=\"price_add\" value=" . $n_player['t_player_priceadd'] . ">\n" ;
	echo "<input type=hidden name=\"positions_id\" value=" . $n_player[4] . ">\n" ;
	echo $p_num . "</TD>";
  	if ($n_player['t_player_num']==$p_num) {
  		echo "<td class=edit ALIGN=LEFT>";
		echo "<input type=hidden name=\"player_id\" value=" . $n_player['t_player_ID'] . ">";
		echo "<input type=text size=20 name=\"player_name\" value=\"" . $n_player['t_player_name'] . "\"></TD>";
  		echo "<td class=edit ALIGN=LEFT>" . $n_player['t_positions_title'] . "</TD>";

		echo "<input type=hidden name=ma_mod value=" . $n_player['t_player_MA_mod'] . ">" ;
  		echo ($n_player['t_player_MA_mod'] != 0) ? "<td class=edit class=edit><B>" : "<td class=edit class=edit>" ;
		echo "<input type=submit name=dec_ma value=\"-\">";
  		echo $n_player['t_positions_MA'] + $n_player['t_player_MA_mod'] ;
		echo "<input type=submit name=inc_ma value=\"+\">";
  		echo ($n_player['t_player_MA_mod'] != 0) ? "</B></TD>" : "</TD>" ;


		echo "<input type=hidden name=st_mod value=" . $n_player['t_player_ST_mod'] . ">" ;
		echo ($n_player['t_player_ST_mod'] != 0) ? "<td class=edit class=edit><B>" : "<td class=edit class=edit>" ;
		echo "<input type=submit name=dec_st value=\"-\">";
  		echo $n_player['t_positions_ST'] + $n_player['t_player_ST_mod'] ;
		echo "<input type=submit name=inc_st value=\"+\">";
  		echo ($n_player['t_player_ST_mod'] != 0) ? "</B></TD>" : "</TD>" ;


		echo "<input type=hidden name=ag_mod value=" . $n_player['t_player_AG_mod'] . ">" ;
  		echo ($n_player['t_player_AG_mod'] != 0) ? "<td class=edit class=edit><B>" : "<td class=edit class=edit>" ;
		echo "<input type=submit name=dec_ag value=\"-\">";
  		echo $n_player['t_positions_AG'] + $n_player['t_player_AG_mod'] ;
		echo "<input type=submit name=inc_ag value=\"+\">";
  		echo ($n_player['t_player_AG_mod'] != 0) ? "</B></TD>" : "</TD>" ;


		echo "<input type=hidden name=av_mod value=" . $n_player['t_player_AV_mod'] . ">" ;
  		echo ($n_player['t_player_AV_mod'] != 0) ? "<td class=edit class=edit><B>" : "<td class=edit class=edit>" ;
		echo "<input type=submit name=dec_av value=\"-\">";
  		echo $n_player['t_positions_AV'] + $n_player['t_player_AV_mod'] ;
		echo "<input type=submit name=inc_av value=\"+\">";
  		echo ($n_player['t_player_AV_mod'] != 0) ? "</B></TD>\n" : "</TD>\n" ;

  		echo "<td class=edit ALIGN=LEFT>";
		$skill_list = "";
		// : List positions skills :
  		$q_skill = 'SELECT t_skill_name FROM t_positions_skill ps, t_skill s WHERE t_positions_ID = ' . $n_player['t_positions_ID']
  				.  ' AND ps.t_skill_ID = s.t_skill_ID ORDER BY t_skill_name';
  		$r_skill = get_sqlresult($q_skill, "Fetch positions skills");
  		while($n_skill = mysql_fetch_array($r_skill)){
  			$skill_list =  $skill_list . "<i>" . $n_skill[0] . "</i>, ";
  		} // while
		$cols=0;
  		if ($n_player['t_player_MA_mod'] != 0) {
				 $skill_list = "ma" . sprintf("%+d, ", $n_player['t_player_MA_mod']) ;
				 $cols=1;
		}
  		if ($n_player['t_player_ST_mod'] != 0) {
				 $skill_list =  $skill_list . "st" . sprintf("%+d, ", $n_player['t_player_ST_mod']) ;
				 $cols=1;
		}
  		if ($n_player['t_player_AG_mod'] != 0) {
				 $skill_list =  $skill_list . "ag" . sprintf("%+d, ", $n_player['t_player_AG_mod']) ;
				 $cols=1;
		}
  		if ($n_player['t_player_AV_mod'] != 0) {
				 $skill_list =  $skill_list . "av" . sprintf("%+d, ", $n_player['t_player_AV_mod']) ;
				 $cols=1;
		}
		echo "<table border=0 cellspacing=0><tr>";
		echo ($skill_list == "" ) ? "" : "<td class=edit class=edit>" . $skill_list . "</td>" ;
		// : List skills :
  		$q_skill = 'SELECT t_skill_name, ps.t_skill_ID FROM t_player_skill ps, t_skill s WHERE t_player_ID = ' . $n_player['t_player_ID']
  				.  ' AND ps.t_skill_ID = s.t_skill_ID ORDER BY t_skill_name';
  		$r_skill = get_sqlresult($q_skill, "Fetch skills");
		$skill_list = "";
		unset($d_skill);
  		while($n_skill = mysql_fetch_array($r_skill)){
  			// : Delete skill form :
			$cols++;
		  	echo "<td class=edit class=edit>";
			$d_skill = $n_skill[1] ;
		  	echo $n_skill[0] . ", ";
			echo "</td>";
  		} // while
		// : New Skill form :
		echo "<td class=edit class=edit>" . (($d_skill) ? "<input type=hidden name=d_skill value=" . $d_skill . "><input type=submit name=del_skill value=\"X\">" : $nbsp ) . "</td>";
		echo "</tr><tr><td class=edit colspan=" . ++$cols . ">";
	  	echo "<select name=a_skill>";
		echo "<option value=0> </option>";
  		$q_skill = 'SELECT sk.t_skill_ID, t_skill_name, t_skill_type_d FROM t_skill sk'
		        . ' JOIN t_positions_skill_type st'
        		. ' ON sk.t_skill_type = st.t_skill_type'
		        . ' LEFT JOIN t_positions_skill ps'
		        . ' ON ps.t_positions_ID = st.t_position_ID'
		        . ' AND ps.t_skill_ID = sk.t_skill_ID'
		        . ' LEFT JOIN t_player_skill psk'
		        . ' ON psk.t_player_ID = ' . $n_player['t_player_ID']
		        . ' AND psk.t_skill_ID = sk.t_skill_ID'
		        . ' WHERE st.t_position_ID = ' . $n_player['t_positions_ID']
		        . ' AND ps.t_skill_ID IS NULL'
		        . ' AND psk.t_skill_ID IS NULL'
		        . ' ORDER BY t_skill_type_d, t_skill_name';
  		$r_skill = get_sqlresult($q_skill, "Fetch skills");
  		while($n_skill = mysql_fetch_array($r_skill)){
  			echo "<option value=" . $n_skill['t_skill_ID'] . ">" . $n_skill['t_skill_name'] . (($n_skill['t_skill_type_d']==0) ? "" : " (D)") . "</option>";
  		}
  		echo "</select>";
  		echo "<input type=submit name=new_skill value=\"Add skill\"></td></tr></table>";
  		echo "</TD>\n";

		// :Niggling/Miss game ************* modified by Tom, adapted for the edit-form by Stein
		echo "<td class=edit align=right>";
		if ($n_player['t_player_niggling'] == 0) {
			echo $nbsp;
		} else {
			echo "n";
			for ($i=2; $i<=$n_player['t_player_niggling']; $i++) { echo ",n"; }
		}
		echo $nbsp . "N:<INPUT TYPE=CHECKBOX NAME=cniggling VALUE=1><BR>" ;
  		echo "M:<INPUT TYPE=CHECKBOX NAME=miss VALUE=1" . (($n_player['t_player_miss'] == 0) ? ">" : " CHECKED>");
		echo "<input type=hidden name=niggling value=" . $n_player['t_player_niggling'] . ">" ;
  		echo "</TD>";

  		echo "<td class=edit class=edit><input type=text size=1 name=complete value=" . $n_player['t_player_comp'] . "></TD>";// CP
  		echo "<td class=edit class=edit><input type=text size=1 name=touchdowns value=" . $n_player['t_player_td'] . "></TD>";// TD
  		echo "<td class=edit class=edit><input type=text size=1 name=intercept value=" . $n_player['t_player_int'] . "></TD>";// IN
  		echo "<td class=edit class=edit><input type=text size=1 name=casuality value=" . $n_player['t_player_cas'] . "></TD>";// CA
  		echo "<td class=edit class=edit><input type=text size=1 name=mvp value=" . $n_player['t_player_mvp'] . "></TD>";// MVP
		$player_spp =  $n_player['t_player_comp'] + $n_player['t_player_td'] * 3 + $n_player['t_player_int'] * 2 + $n_player['t_player_cas'] * 2 + $n_player['t_player_mvp'] * 5 ;
  		echo "<td class=edit class=edit>" . $player_spp . "</TD>";// SPP
  		$player_price = $n_player['t_positions_price'] + $n_player['t_player_priceadd'] ;
  		echo "<td class=edit ALIGN=RIGHT>" . $player_price . "</TD>";//Player value
     	$n_player = mysql_fetch_array($r_player);
  	} else {
		echo "<td class=edit ALIGN=LEFT><input type=text size=20 name=\"player_name\"></TD>";
		echo "<td class=edit class=edit><SELECT NAME=positions_id>\n";
		$q_pot = 'SELECT t_positions_ID, t_positions_title FROM t_positions WHERE t_race_ID = ' . $race_id ;
		$r_pot = get_sqlresult($q_pot, "Get positions");
		while($n_pot = mysql_fetch_array($r_pot)){
			echo "<option value=" . $n_pot[0] . ">" . $n_pot[1] . "</option>\n" ;
		} // while
		echo "</SELECT>"
			."</TD>"
			."<td class=edit class=edit>&nbsp;</TD>"
			."<td class=edit class=edit>&nbsp;</TD>"
			."<td class=edit class=edit>&nbsp;</TD>"
			."<td class=edit class=edit>&nbsp;</TD>"
			."<td class=edit class=edit>&nbsp;</TD>"
			."<td class=edit class=edit>&nbsp;</TD>"
			."<td class=edit class=edit>&nbsp;</TD>"
			."<td class=edit class=edit>&nbsp;</TD>"
			."<td class=edit class=edit>&nbsp;</TD>"
			."<td class=edit class=edit>&nbsp;</TD>"
			."<td class=edit class=edit>&nbsp;</TD>"
			."<td class=edit class=edit>&nbsp;</TD>"
			."<td class=edit class=edit>&nbsp;</TD>";
	}
	// submit main player form
	echo "<td class=edit class=edit><input type=submit name=sav_player value=\"Save\"><input type=submit name=del_player value=\"Delete\"></form></td></TR>\n";
}
$q_player = 'SELECT sum(t_positions_price) price, sum(t_player_priceadd) priceadd FROM t_player pl, t_positions po WHERE t_team_ID = ' . $team
        . ' AND pl.t_positions_ID = po.t_positions_ID'
        . ' GROUP BY t_team_ID LIMIT 0, 30';
$r_player = get_sqlresult($q_player,"Summing player values");
$n_player = mysql_fetch_array($r_player);
$team_price = $n_player['price']
  				+ $n_player['priceadd']
  				+ $n_team_d['t_race_rerollprice'] * $n_team_d['t_team_rerolls']
				+ $n_team_d['t_team_fanfactor'] * 10000
				+ $n_team_d['t_team_assistant_coaches'] * 10000
				+ $n_team_d['t_team_cheerleaders'] * 10000
				+ $n_team_d['t_team_apothecary'] * 50000;
$team_rating = $team_price / 10000 ;
echo "<TR ALIGN=CENTER><td class=edit ALIGN=RIGHT BGCOLOR=\"#D0D0A0\" COLSPAN=2>";
echo "<form method=post action=". $_SERVER['PHP_SELF'] .">";
echo "<input type=hidden name=\"team\" value=" .$team .">";
echo "<input type=hidden name=\"teamid\" value=" .$team .">";
echo "Team Name:</TD><td class=edit COLSPAN=5><input type=text size=20 name=\"team_name\" value=\"" .$n_team_d['t_team_name'] ."\"></TD><td class=edit ALIGN=RIGHT BGCOLOR=\"#D0D0A0\" COLSPAN=2>Re-Rolls:</TD><td class=edit COLSPAN=2><input type=text size=5 name=\"reroll\" value=\"" .$n_team_d['t_team_rerolls'] ."\"</TD><td class=edit BGCOLOR=\"#D0D0A0\" COLSPAN=4>x $" .$n_team_d['t_race_rerollprice'] / 1000  ."k =</TD><td class=edit ALIGN=RIGHT>" .$n_team_d['t_race_rerollprice'] * $n_team_d['t_team_rerolls'] ."</TD>";
echo "<td rowspan=7 class=edit>";
echo ( (1==$_SESSION['userschema']) ? '<input type="Submit" name="del_team" value="Delete team"><BR>' : '&nbsp;');
echo "<input type=\"Submit\" name=\"save_team\" value=\"Save team\"></TD></TR>";
echo "<TR ALIGN=CENTER><td class=edit ALIGN=RIGHT BGCOLOR=\"#D0D0A0\" COLSPAN=2>Race:</TD><td class=edit COLSPAN=5>" .ucwords(strtolower($n_team_d['t_race_name'])) ."</TD><td class=edit ALIGN=RIGHT BGCOLOR=\"#D0D0A0\" COLSPAN=2>Fan Factor:</TD><td class=edit COLSPAN=2><input type=text size=5 name=\"fanfac\" value=\"" .$n_team_d['t_team_fanfactor'] ."\"</TD><td class=edit BGCOLOR=\"#D0D0A0\" COLSPAN=4>x $10k =</TD><td class=edit ALIGN=RIGHT>" .$n_team_d['t_team_fanfactor'] * 10000  ."</TD></TR>";
echo "<TR ALIGN=CENTER><td class=edit ALIGN=RIGHT BGCOLOR=\"#D0D0A0\" COLSPAN=2>Team Rating:</TD><td class=edit COLSPAN=5>" .$team_rating ."</TD><td class=edit ALIGN=RIGHT BGCOLOR=\"#D0D0A0\" COLSPAN=2>Assistant Coaches:</TD><td class=edit COLSPAN=2><input type=text size=5 name=\"assist\" value=\"" .$n_team_d['t_team_assistant_coaches'] ."\"</TD><td class=edit BGCOLOR=\"#D0D0A0\" COLSPAN=4>x $10k =</TD><td class=edit ALIGN=RIGHT>" .$n_team_d['t_team_assistant_coaches'] * 10000 ."</TD></TR>";
echo "<TR ALIGN=CENTER><td class=edit ALIGN=RIGHT BGCOLOR=\"#D0D0A0\" COLSPAN=2>Treasury:</TD><td class=edit COLSPAN=5><input type=text size=20 name=\"treas\" value=\"" .$n_team_d['t_team_treasury'] ."\"></TD><td class=edit ALIGN=RIGHT BGCOLOR=\"#D0D0A0\" COLSPAN=2>Cheerleaders:</TD><td class=edit COLSPAN=2><input type=text size=5 name=\"cheer\" value=\"" .$n_team_d['t_team_cheerleaders'] ."\"</TD><td class=edit BGCOLOR=\"#D0D0A0\" COLSPAN=4>x $10k =</TD><td class=edit ALIGN=RIGHT>" .$n_team_d['t_team_cheerleaders'] * 10000 ."</TD></TR>";
echo "<TR ALIGN=CENTER><td class=edit ALIGN=RIGHT BGCOLOR=\"#D0D0A0\" COLSPAN=2>Coach:</TD><td class=edit COLSPAN=5>" . $n_team_d['t_team_coach'] . "</TD><td class=edit ALIGN=RIGHT BGCOLOR=\"#D0D0A0\" COLSPAN=2>Apothecary:</TD><td class=edit COLSPAN=2><input type=text size=5 name=\"apoth\" value=\"" .$n_team_d['t_team_apothecary'] ."\"</TD><td class=edit BGCOLOR=\"#D0D0A0\" COLSPAN=4>x $50k =</TD><td class=edit ALIGN=RIGHT>" .$n_team_d['t_team_apothecary'] * 50000 ."</TD></TR>";
echo "<TR ALIGN=CENTER><td class=edit COLSPAN=7 ROWSPAN=2>&nbsp;</TD><td class=edit ALIGN=RIGHT BGCOLOR=\"#D0D0A0\" COLSPAN=2>Team Wizard:</TD><td class=edit COLSPAN=2>0</TD><td class=edit BGCOLOR=\"#D0D0A0\" COLSPAN=4>x $50k =</TD><td class=edit ALIGN=RIGHT>0</TD></TR>";
echo "<TR ALIGN=CENTER><td class=edit ALIGN=RIGHT BGCOLOR=\"#D0D0A0\" COLSPAN=8>TOTAL COST OF TEAM:</TD><td class=edit ALIGN=RIGHT>" .$team_price ."</FONT></TD></TR>";
echo "<TR>";
echo "</TABLE>";
echo "</FORM>";
// End forms
echo "</td></tr></table></div>";
echo "</body>";
echo "</html>";
