<?php

//connect to the database
require "db_connection.php";

//start the session
//session_start();

//find out which campaign they are trying to look at
$campaignID = htmlentities($_GET['id']);

//select all of the campaigns information
$campaignquery = "SELECT * FROM campaigns WHERE id='$campaignID' LIMIT 1";
$campaignresult = mysql_query($campaignquery);

//find out all of the campaigns information
$title = mysql_result($campaignresult, 0, "title");
$description = mysql_result($campaignresult, 0, "description");
$quote = mysql_result($campaignresult, 0, "quote");

//display the campaign information

//if there was an error trying to upload
if(($_GET['action']) == "failure") {
	//display that they didn't fill in all the fields
}
//else if they were successful uploading
else if(($_GET['action']) == "success") {
	//display that it was successful
}

//if they are logged in 
//if($_SESSION['loggedin'] == 1) {
	//display the form to upload a photo to this campaign
	echo '
	<form method="post" action="upload_photo.php?campaign=', $campaignID, '">
		<input name="file" type="file" />
		caption: <input name="caption" type="caption" />
		<input type="submit" />
	</form>'
//}

?>