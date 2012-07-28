<?php

//connect to the database
require "db_connection.php";

$keywords = mysql_real_escape_string( $_POST["keywords"] );
$userid = htmlentities($_GET['u']);
$userinfoquery = mysql_query("SELECT * FROM userinfo WHERE user_id = '$userid'");
$useremail = mysql_result($userinfoquery,0,'emailaddress');

$query = mysql_query("SELECT * FROM 
photos
 WHERE emailaddress = '$useremail' &&
caption
 LIKE '%". $keywords ."%'");

$arr = array();
while($row = mysql_fetch_array($query))
{
	$arr[] = array( "source" => $row["source"], "title" => $row["caption"], "price" => $row["price"] );
}

echo json_encode( $arr );

?>