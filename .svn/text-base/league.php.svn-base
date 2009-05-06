<?php
session_start();
include "includes/library.inc.php";
include "includes/sql_connect.php";
select_forms();
head_std("Leagues");
body_top();
if (isset($_SESSION['fixtureID'])) {
	echo "<div align=center>\n";
	echo "<table><tr><td valign=top>\n";
	viewtable($_SESSION['fixtureID']);
	t_gate_stat($_SESSION['fixtureID']);
	t_winnings_worst($_SESSION['fixtureID']);
	echo "<table><tr><td>\n";
	t_offense_best($_SESSION['fixtureID']);
	echo "\n</td><td>\n";
	t_offense_worst($_SESSION['fixtureID']);
	echo "\n</td></tr></table>\n";
	echo "<table><tr><td>\n";
	t_cas_best($_SESSION['fixtureID']);
	echo "\n</td><td>\n";
	t_cas_worst($_SESSION['fixtureID']);
	echo "\n</td></tr></table>\n";
	t_winnings_stat($_SESSION['fixtureID']);
	echo "\n</td><td valign=top>\n";
	viewresults($_SESSION['fixtureID'],3);
	echo "\n</td></tr></table>\n";
	echo "</div>\n";
}
echo "</body>\n</html>";
?>