<!DOCTYPE html>
<html>
<head>
	<title>Stats</title>
</head>
<body>
<div style="font-family:lucida grande, helvetica; font-size:40px;">

<?php
//connect to the database
require "db_connection.php";

$query="SELECT * FROM photos";
$result=mysql_query($query);
$numberpics = mysql_num_rows($result);

echo $numberpics. ' photos';
?>

<br />
<br />

<?php

$querytwo = "SELECT * FROM userinfo";
$resulttwo = mysql_query($querytwo);
$numberusers = mysql_num_rows($resulttwo);

echo $numberusers . ' users <br /><br />';



$querythree = "SELECT * FROM campaignusers";
$resultthree = mysql_query($querythree);
$numbercampusers = mysql_num_rows($resultthree);

echo $numbercampusers . ' reps <br /><br />';

$queryfour = "SELECT * FROM campaignphotos";
$resultfour = mysql_query($queryfour);
$numbercampphotos = mysql_num_rows($resultfour);

echo $numbercampphotos . ' campaign photos <br /><br />';


$queryfive = "SELECT * FROM campaigns";
$resultfive = mysql_query($queryfive);
$numbercamps = mysql_num_rows($resultfive);

echo $numbercamps . ' campaigns <br /><br />';

//INSERT STATS INTO DATABASE FOR GROWTH TRACKING IF 1 DAY ELAPSED
$currenttime = time();

$timequery = mysql_query("SELECT time FROM stats ORDER BY id DESC LIMIT 1");
$oldtime = mysql_result($timequery,0,'time');

if($currenttime > ($oldtime + 10800)) {
$growthquery = mysql_query("INSERT INTO stats (photos,users,reps,campaignphotos,campaigns,time) VALUES ('$numberpics','$numberusers','$numbercampusers','$numbercampphotos','$numbercamps','$currenttime')");      
}

//stats table
echo '<table border="1">';
echo 
	'<tr>
		<td>date</td>
		<td>users</td>
		<td>photos</td>
		<td>reps</td>
		<td>campaigns</td>
		<td>campaign photos</td>
	</tr>';

$query = mysql_query("SELECT * FROM stats ORDER BY id DESC LIMIT 100");
for($iii=0; $iii < mysql_num_rows($query); $iii++) {
	echo '<tr>';
	echo '<td>', mysql_result($query, $iii, date), '</td>';
	echo '<td>', mysql_result($query, $iii, users), '</td>';
	echo '<td>', mysql_result($query, $iii, photos), '</td>';
	echo '<td>', mysql_result($query, $iii, reps), '</td>';
	echo '<td>', mysql_result($query, $iii, campaigns), '</td>';
	echo '<td>', mysql_result($query, $iii, campaignphotos), '</td>';
	echo '</tr>';
}
echo '</table>';

mysql_close();

?>

</div>
</body>
</html>