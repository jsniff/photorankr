<?php

//connect to the database
require "db_connection.php";

$keywords = mysql_real_escape_string( $_POST["keywords"] );

$query = mysql_query("SELECT * FROM 
photos
 WHERE 
caption
 LIKE '%". $keywords ."%'");

$arr = array();
while($row = mysql_fetch_array($query))
{
	$arr[] = array( "source" => $row["source"], "title" => $row["caption"], "price" => $row["price"] );
}

echo json_encode( $arr );

?>