<?php
session_start();
$ht_first = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n"
	. "<html>\n"
	. "<head>\n"
	. "<title>phpCommish lrb5</title>\n"
	. "<meta http-equiv=\"content-type\" content=\"text/html;charset=ISO-8859-1\">"
	. "<style type=\"text/css\">\n"
	. "<!--\n"
	. "body,table,th,td { font-family: helvetica;\n"
	. "					font-size: 10pt}\n"
	. ".bold { font-family: helvetica;\n"
	. "			font-weight: bold }\n"
	. "a:link { color:  #6666FF }\n"
	. "a:visited { color: #666666 }\n"
	. "a:active { color: #999999 }\n"
	. "-->\n"
	. "</style>\n"
	. "<center><IMG SRC=../img/bblogo_l.jpg ALT=\"BloodBowl phpCommish\">";
$ht_sec = "</center>\n"
	. "<P align=\"center\">\n"
	. "<a href=\"../index_rules.php\">League Rules</a> |\n"
	. "<a href=\"../index_view.php\">View Leagues</a> |\n"
	. "<a href=\"index.php\">Edit Leagues</a><BR>\n";
if(isset($_SESSION['userid'])){
	if($_POST['logout']){
		unset($_SESSION['userid']);
		unset($_SESSION['userschema']);
		unset($_SESSION['userrights']);
		header('refresh: 0');
		header('url: '.$_SESSION['PHP_SELF'],false);
		echo "<body bgcolor=#203264></body>";
//		header('Location: '.$_SESSION['PHP_SELF']);
//		die('<p align=center>Back to <a href="' . $_SESSION['PHP_SELF'] . '">phpCommish</a>.</p>');
	}else{
		echo $ht_first . "<form method=post action=".$_SERVER['PHP_SELF']."><input type=image src=\"../img/logout.png\" alt=\"Logout\" name=logout value=\"Logout!\"></form>" . $ht_sec;
	}
}else{
	if(isset($_POST['login'])){
		//They have posted something!
		include 'sql_connect.php';
		$sql = "select * from t_user u, t_schema s where u.t_schema_id = s.t_schema_id AND t_user_name = '" . $_POST['user'] . "' and t_user_pass = MD5('" . $_POST['pass'] . "')";
		$result = mysql_query($sql) or die(b_error('Select user failed',$sql));
		if (mysql_num_rows($result) >= 1) {
			$line=mysql_fetch_array($result);
			//They have sent us the correct login information!
			$_SESSION['userid'] = $line['t_user_id'];
			$_SESSION['userschema'] = $line['s.t_schema_id'];
			$_SESSION['userrights'] = $line['t_schema_rights'];
			header('refresh: 0');
			header('url: '.$_SESSION['PHP_SELF'],false);
			echo "<body bgcolor=#203264></body>";
			die('<p align=center>Back to <a href="' . $_SESSION['PHP_SELF'] . '">phpCommish</a>.</p>');
			//The user has been redirected back to the main page and it should say they they have logged in!
		}else{
			//They failed to send us the correct username or password!
			die($ht_first . $ht_sec . '<center><b>Incorrect username or password!</b></center></body></html>');
	}
	}else{
		echo $ht_first . $ht_sec;
		echo "</head><body bgcolor=#203264><div align=center>";
		echo "<form method=post action=".$_SERVER['PHP_SELF'].">";
		echo "<table>";
		echo "<tr><th colspan=2 align=center bgcolor=#ffffff>You must login to edit!</th></tr>";
		echo "<tr><td bgcolor=#ffffff>Username:</td>";
		echo "<td bgcolor=#ffffff><input type=text name=user></td></tr>";
		echo "<tr><td bgcolor=#ffffff>Password:</td>";
		echo "<td bgcolor=#ffffff><input type=password name=pass></td></tr>";
		echo "<tr><td colspan=2><input type=submit name=login value=\"Login!\"></td></tr>";
		echo "</table></form>";
		echo "</div></body></html>";
		die();
	}
}
