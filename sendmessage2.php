<?php
/*In here we are going to try to update the database with the message which was sent from ViewProfile.php with the contact form*/

//CONNECT TO DB
require "db_connection.php";

session_start();

//find out who the message is going to be sent to and who the message is being sent from
$sender = $_SESSION['email'];
$receiver = mysql_real_escape_string($_REQUEST['emailaddressofviewed']);

//run a query to retreive a message which would belong to the same thread
$threadquery = "SELECT * FROM messages WHERE (sender='$sender' AND receiver='$receiver') OR (sender='$receiver' AND receiver='$sender') LIMIT 0, 1";
$threadresult = mysql_query($threadquery) or die(mysql_error());

//if there aren't any previous messages, this will be a new thread
if(mysql_num_rows($threadresult) == 0) {
	//find out what the highest numbered thread is so far
	$threadcheckquery = "SELECT thread FROM messages ORDER BY thread DESC LIMIT 0, 1";
	$threadcheckresult = mysql_query($threadcheckquery) or die(mysql_error());
	
	//assign the thread to be one more than the highest one used so far
	$currenthighest = mysql_result($threadcheckresult, 0, "thread");
	$thread = $currenthighest + 1;
}
//otherwise these two have communicated in the past so find out what the thread number is
else {
	$thread = mysql_result($threadresult, 0, "thread");
}

//set $unread equal to one since this message will have been unread by the receiver
$unread = 1;

//find out what the message said
$contents = htmlentities(mysql_real_escape_string($_REQUEST['message']));

//now insert this message into the messages table
$messagequery = "INSERT INTO messages (thread, sender, receiver, contents, unread) VALUES ('$thread', '$sender', '$receiver', '$contents', '$unread')";
$messageresult = mysql_query($messagequery) or die(mysql_error());

//now that the message has been sent, we need to make a notification for the receiver
mysql_query("UPDATE userinfo SET notifications=(notifications + 1) WHERE emailaddress = '$receiver'") or die(mysql_error());

//newsfeed entry
$select_query2="SELECT * FROM userinfo WHERE emailaddress ='$receiver'";
$result2=mysql_query($select_query2);
$row2=mysql_fetch_array($result2);
$first=$row2['firstname'];
$last=$row2['lastname'];

$select_query="SELECT * FROM userinfo WHERE emailaddress ='$sender'";
$result=mysql_query($select_query);
$row=mysql_fetch_array($result);
$firstname=$row['firstname'];
$lastname=$row['lastname'];
$type = "message";
$newsfeedsignupquery=mysql_query("INSERT INTO newsfeed (firstname, lastname, owner,type,thread) VALUES ('$firstname', '$lastname', '$receiver','$type','$thread')");

//now update the newsfeed table with the message so that it can be pulled for the notifications


//now send them back to whence they came
//redirect them to whence they came
header("Location: viewprofile.php?first=$first&last=$last&view=contact&action=messagesent");

?>