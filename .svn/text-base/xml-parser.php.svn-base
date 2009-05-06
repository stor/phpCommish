<?php
echo '<html><head></head><body><div align=center>';
echo '<form action=xml-parser.php enctype="multipart/form-data" method="post">';
echo 'Select file, or type filename:<br>';
echo '<input type="file" name="results"><br>';
echo '<input type="submit" value="Send">';
echo '</form>';
if ($_FILES['results']['tmp_name']) {
	$xml = simplexml_load_file($_FILES['results']['tmp_name']);
	$repver = $xml['version'];
	$date = $xml['date'];
	$auth = $xml['authorized'];
	$gate = $xml->gate;
	echo '<table border=1 cellspacing=0><tr><td>Report version</td><td>' . $repver . '</td></tr>';
	echo '<tr><td>Game date</td><td>' . $date . '</td></tr>';
	echo '<tr><td>Authorized</td><td>' . $auth . '</td></tr>';
	echo '<tr><td>Game gate</td><td>' . $gate . '</td></tr></table>';
	echo '<table><tr>';
	foreach ($xml->result as $result) {
		$teamname = $result['team'];
		$bb_name = $result->client['name'];
		$bb_version = $result->client['version'];
		$teamrating = $result->rating;
		$teamscore = $result->score;
		$completions = $result->completions;
		$winnings = $result->winnings;
		$fanfactor = $result->fanfactor;
		$luck = $result->luck;
		$cas_bh = $result->casualties['bh'];
		$cas_si = $result->casualties['si'];
		$cas_ki = $result->casualties['k'];
		echo '<td valign=top><table border=1 cellspacing=0><tr>';
		echo '<th>Team</th><th>Client</th><th>Version</th><th>Rating</th><th>Score</th>';
		echo '</tr><tr>';
		echo '<td>' . $teamname . '</td>';
		echo '<td>' . $bb_name . '</td>';
		echo '<td>' . $bb_version . '</td>';
		echo '<td>' . $teamrating . '</td>';
		echo '<td>' . $teamscore . '</td>';
		echo '</tr></table><p>';
		echo '<table border=1 cellspacing=0><tr>';
		echo '<th>complete passes</th><th>Winnings</th><th>FF-change</th><th>Luck</th><th>BH</th><th>SI</th><th>Kills</th>';
		echo '</tr><tr>';
		echo '<td>' . ($completions ? $completions : '&nbsp;'). '</td>';
		echo '<td>' . ($winnings ? $winnings : '&nbsp;') . '</td>';
		echo '<td>' . ($fanfactor ? $fanfactor : '&nbsp;') . '</td>';
		echo '<td>' . ($luck ? $luck : '&nbsp;') . '</td>';
		echo '<td>' . ($cas_bh ? $cas_bh : '&nbsp;') . '</td>';
		echo '<td>' . ($cas_si ? $cas_si : '&nbsp;') . '</td>';
		echo '<td>' . ($cas_ki ? $cas_ki : '&nbsp;') . '</td>';
		echo '</tr></table><p>';
		echo '<table border=1 cellspacing=0><tr>';
		echo '<th>Player nr.</th><th>Comp</th><th>TD</th><th>Rush</th><th>Pass</th><th>Blocks</th><th>Cas</th><th>Inj</th><th>Inj. eff.</th><th>Fouls</th><th>MVP</th>';
		echo '</tr>';
		foreach ($result->players->performance as $performance) {
			$playernum = $performance['player'];
			$completion = $performance->completions;
			$touchdown = $performance->touchdowns;
			$rushing = $performance->rushing;
			$passing = $performance->passing;
			$blocks = $performance->blocks;
			$casualties = $performance->casualties;
			$injurytype = $performance->injury['type'];
			$injuryeffect = $performance->injury->effect;
			$fouls = $performance->fouls;
			$mvp = $performance->mvps;
			//Writeout playerdata to database
			echo '<tr>';
			echo '<td>' . $playernum . '</td>';
			echo '<td>' . ($completion ? $completion : '&nbsp;') . '</td>';
			echo '<td>' . ($touchdown ? $touchdown : '&nbsp;') . '</td>';
			echo '<td>' . ($rushing ? $rushing : '&nbsp;') . '</td>';
			echo '<td>' . ($passing ? $passing : '&nbsp;') . '</td>';
			echo '<td>' . ($blocks ? $blocks : '&nbsp;') . '</td>';
			echo '<td>' . ($casualties ? $casualties : '&nbsp;') . '</td>';
			echo '<td>' . ($injurytype ? $injurytype : '&nbsp;') . '</td>';
			echo '<td>' . ($injuryeffect ? $injuryeffect : '&nbsp;') . '</td>';
			echo '<td>' . ($fouls ? $fouls : '&nbsp;') . '</td>';
			echo '<td>' . ($mvp ? $mvp : '&nbsp;') . '</td>';
			echo '</tr>';
		}
		echo '</table></td>';
		//Writeout teamdata to database
	}
	echo '</tr></table>';
}
echo '</div></body></html>';
?>