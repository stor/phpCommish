<?php
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
	//$sql = 'SELECT * FROM v_table WHERE t_division_ID = ' . $division ;
	// sql to use when selecting from plain sql
	$sql = 'SELECT'
        . ' t_division_ID,'
        . ' t_team_name, '
        . ' count(t_team_name) n_games, '
        . ' sum(win) s_win, '
        . ' sum(draw) s_draw, '
        . ' sum(loss) s_loss, '
        . ' sum(h_td) s_h_td, '
        . ' sum(a_td) s_a_td, '
        . ' sum(td_diff) s_td_diff, '
        . ' sum(h_cas) s_h_cas, '
        . ' sum(a_cas) s_a_cas, '
        . ' sum(cas_diff) s_cas_diff, '
        . ' sum(pts) s_pts'
        . ' FROM '
        . ' (('
        . ' SELECT '
        . ' t_division_ID,'
        . ' ta.t_team_name, '
        . ' t_game_h_td h_td, '
        . ' t_game_a_td a_td, '
        . ' t_game_h_td-t_game_a_td td_diff,'
        . ' t_game_h_cas h_cas, '
        . ' t_game_a_cas a_cas, '
        . ' t_game_h_cas-t_game_a_cas cas_diff,'
        . ' (t_game_h_td>t_game_a_td) win, '
        . ' (t_game_h_td=t_game_a_td) draw, '
        . ' (t_game_h_td<t_game_a_td) loss,'
        . ' ((t_game_h_td-t_game_a_td)=0)+((t_game_h_td-t_game_a_td)>0)*3 pts'
        . ' FROM t_game ga, t_team ta'
        . ' WHERE ga.t_h_team_ID = ta.t_team_ID'
        . ' ) UNION ALL ('
        . ' SELECT '
        . ' t_division_ID,'
        . ' tb.t_team_name, '
        . ' t_game_a_td h_td, '
        . ' t_game_h_td a_td, '
        . ' t_game_a_td-t_game_h_td td_diff,'
        . ' t_game_a_cas h_cas, '
        . ' t_game_h_cas a_cas, '
        . ' t_game_a_cas-t_game_h_cas cas_diff,'
        . ' (t_game_h_td<t_game_a_td) win, '
        . ' (t_game_h_td=t_game_a_td) draw, '
        . ' (t_game_h_td>t_game_a_td) loss,'
        . ' ((t_game_a_td-t_game_h_td)=0)+((t_game_a_td-t_game_h_td)>0)*3 pts'
        . ' FROM t_game gb, t_team tb'
        . ' WHERE gb.t_a_team_ID = tb.t_team_ID'
        . ' ))'
        . ' AS r_table'
		. ' WHERE t_division_ID = ' . $division
        . ' GROUP BY t_team_name'
        . ' ORDER BY s_pts DESC, s_td_diff DESC, s_cas_diff DESC;'; 
	$r_tab = mysql_query($sql) or die (b_error("View table query failed!\n",$sql));
	echo "<table summary=\"Results table\" bgcolor=#ffffff border=1 cellpadding=0 cellspacing=0>\n";
	echo "<tr><td colspan=9 align=center><b><i>Table</i></b></td></tr>\n";
	echo "<tr><th align=left>Team</th><th>Played</th><th>W</th><th>D</th><th>L</th><th>TD</th><th>TD diff</th><th>Cas.</th><th>Cas diff</th><th>Points</th></tr>\n";
	while ($n_tab = mysql_fetch_array($r_tab)) {
		echo "<tr><td>" . $n_tab[1] . "</td>\n";
		echo "<td align=center>" . $n_tab[2] . "</td>\n";
		echo "<td align=center>" . $n_tab[3] . "</td>\n";
		echo "<td align=center>" . $n_tab[4] . "</td>\n";
		echo "<td align=center>" . $n_tab[5] . "</td>\n";
		echo "<td align=center>" . $n_tab[6] . "-" . $n_tab[7] . "</td>\n";
		echo "<td align=center>" . $n_tab[8] . "</td>\n";
		echo "<td align=center>" . $n_tab[9] . "-" . $n_tab[10] . "</td>\n";
		echo "<td align=center>" . $n_tab[11] . "</td>\n";
		echo "<td align=center>" . $n_tab[12] . "</td>\n";
		echo "</tr>";
	}
	echo "</table>";
}
function t_gate_stat($division){

	// Code contributed by Tom
	// Prints: BEST AND WORST GATE in a league
	// Given the division id
  	// :::Display Statistic 1

	$q_td = 'SELECT ta.t_team_name, tb.t_team_name, t_game_date, t_game_gate FROM t_team as ta, t_team as tb, t_game WHERE '
					 . ' ta.t_team_ID=t_h_team_ID AND tb.t_team_ID=t_a_team_ID AND '
					 . ' t_h_team_ID <> 50 AND t_a_team_ID <> 50 AND t_division_ID = ' . $division
					 . ' ORDER BY t_game_gate DESC LIMIT 1';
	$r_td = mysql_query($q_td) or die ("Player query failed! " . mysql_error());

	echo "<table summary=\"Statistic 3\" bgcolor=#ffffff border=1 cellpadding=0 cellspacing=0>\n";
	echo "<tr><td colspan=9 align=center><b><i>Famous and Infamous Match</i></b></td></tr>\n";
	echo "<tr><th>Category</th><th align=left>Match</th><th>Date</th><th>Gate</th></tr>\n";
	while ($n_tab = mysql_fetch_array($r_td)) {
		echo "<tr><td> Famous </td>\n";
		echo "<td>" . $n_tab[0] . " vs " . $n_tab[1] ."</td>\n";
		echo "<td align=center>" . $n_tab[2] . "</td>\n";
		echo "<td align=center>" . $n_tab[3] . "</td>\n";
		echo "</tr>";
	}
	$q_td = 'SELECT ta.t_team_name, tb.t_team_name, t_game_date, t_game_gate FROM t_team as ta, t_team as tb, t_game WHERE '
					 . ' ta.t_team_ID=t_h_team_ID AND tb.t_team_ID=t_a_team_ID AND '
					 . ' t_h_team_ID <> 50 AND t_a_team_ID <> 50 AND t_division_ID = ' . $division
					 . ' ORDER BY t_game_gate ASC LIMIT 1';
	$r_td = mysql_query($q_td) or die ("Player query failed! " . mysql_error());

	while ($n_tab = mysql_fetch_array($r_td)) {
		echo "<tr><td> Infamous </td>\n";
		echo "<td>" . $n_tab[0] . " vs " . $n_tab[1] ."</td>\n";
		echo "<td align=center>" . $n_tab[2] . "</td>\n";
		echo "<td align=center>" . $n_tab[3] . "</td>\n";
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
	$q_td = 'SELECT t_team_name, min(t_game_h_win) FROM ((SELECT t_h_team_ID, t_game_h_win FROM t_game gh WHERE '
					 . ' t_h_team_ID <> 50 AND t_division_ID = ' . $division
					 . ' ) UNION ALL (SELECT t_a_team_ID, t_game_a_win FROM t_game ga WHERE '
					 . ' t_a_team_ID <> 50 AND t_division_ID = ' . $division
					 . ' )) AS tga, t_team WHERE t_h_team_ID=t_team_ID GROUP BY t_h_team_ID '
					 . ' ORDER BY min(t_game_h_win) ASC ';
	$r_td = mysql_query($q_td) or die ("Player query failed! " . mysql_error());

	echo "<table summary=\"Statistic 3\" bgcolor=#ffffff border=1 cellpadding=0 cellspacing=0>\n";
	echo "<tr><td colspan=9 align=center><b><i>Worst Winnings One Match</i></b></td></tr>\n";
	echo "<tr><th align=left>Team</th><th>Worst Winnings</th></tr>\n";
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

	echo "<table summary=\"Statistic 3\" bgcolor=#ffffff border=1 cellpadding=0 cellspacing=0>\n";
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

	echo "<table summary=\"Statistic 3\" bgcolor=#ffffff border=1 cellpadding=0 cellspacing=0>\n";
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

	echo "<table summary=\"Statistic 3\" bgcolor=#ffffff border=1 cellpadding=0 cellspacing=0>\n";
	echo "<tr><td colspan=9 align=center><b><i>Top Brutal</i></b></td></tr>\n";
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
					 . ' t_h_team_ID <> 50 AND t_division_ID = ' . $_POST['division']
					 . ' ) UNION ALL (SELECT t_a_team_ID, t_game_h_cas FROM t_game ga WHERE '
					 . ' t_a_team_ID <> 50 AND t_division_ID = ' . $_POST['division']
					 . ' )) AS tga, t_team WHERE t_h_team_ID=t_team_ID GROUP BY t_h_team_ID '
					 . ' ORDER BY sum(t_game_a_cas) DESC LIMIT 1';
	$r_td = mysql_query($q_td) or die ("Player query failed! " . mysql_error());
	$n_score = mysql_fetch_array($r_td);
	$worst_cas_agst= $n_score[0];

	$q_td = 'SELECT * FROM ( ('
	. ' SELECT t_team_name, sum(t_game_a_cas) AS cas_agst FROM ((SELECT t_h_team_ID, t_game_a_cas FROM t_game gh WHERE '
					 . ' t_h_team_ID <> 50 AND t_division_ID = ' . $_POST['division']
					 . ' ) UNION ALL (SELECT t_a_team_ID, t_game_h_cas FROM t_game ga WHERE '
					 . ' t_a_team_ID <> 50 AND t_division_ID = ' . $_POST['division']
					 . ' )) AS tga, t_team WHERE t_h_team_ID=t_team_ID '
					 . ' GROUP BY t_h_team_ID ) AS tgb ) '
					 . ' WHERE cas_agst = ' . $worst_cas_agst ;

	$r_td = mysql_query($q_td) or die ("Player query failed! " . mysql_error());

	echo "<table summary=\"Statistic 3\" bgcolor=#ffffff border=1 cellpadding=0 cellspacing=0>\n";
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

	echo "<table summary=\"Statistic 3\" bgcolor=#ffffff border=1 cellpadding=0 cellspacing=0>\n";
	echo "<tr><td colspan=9 align=center><b><i>Winnings</i></b></td></tr>\n";
	echo "<tr><th align=left>Team</th><th>Category</th><th>Winnings</th></tr>\n";
	while ($n_tab = mysql_fetch_array($r_td)) {
		echo "<tr><td>" . $n_tab[0] . "</td>\n";
		echo "<td align=center>Best Overall</td>\n";
		echo "<td align=center>" . $n_tab[1] . "</td>\n";
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
		echo "<td align=center>" . $n_tab[1] . "</td>\n";
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
					echo "<td align=center>" . $n_tab[1] . "</td>\n";
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
		echo "<td align=center>" . $n_tab[1] . "</td>\n";
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
	$r_td = mysql_query($q_td) or die ("Player query failed! " . mysql_error());

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
function b_error($e_text,$e_sql) {
	echo "<table bgcolor=#ffffff border=1>\n";
	echo "<tr><td>" . $e_text . "</td></tr>\n";
	echo "<tr><td>" . mysql_error() . "</td></tr>\n";
	echo "<tr><td>" . $e_sql . "</td></tr>\n";
	echo "</table>\n";
}