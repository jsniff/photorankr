<?php

if($_GET['action'] == "mail") {
	//connect to the database
	require "db_connection.php";

	$email = mysql_real_escape_string(htmlentities($_POST['email']));

	$query = "INSERT INTO campaignusers (repemail) VALUES ('$email')";
	mysql_query($query);
}

?>