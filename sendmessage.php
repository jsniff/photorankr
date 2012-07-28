<?php
/*
*This is the file which sends the email to the user when someone
*tries to use the contact form in viewprofile
*/

//connect to the database
require "db_connection.php";

session_start();

//get the posted email address
$email_to = $_REQUEST['emailaddressofviewed'];

//build the email
$subject = "New Message via Photorankr";
$message = $_REQUEST['message'];
$header="from: ";
$from = $_SESSION['email']; 
$header .= $from;
$emailtosend = mail($email_to,$subject, $message, $header);

if($emailtosend) {
	$first=$_REQUEST['firstofviewed'];
	$last=$_REQUEST['lastofviewed'];
	//redirect them to whence they came
	header("Location: viewprofile.php?first=$first&last=$last&view=contact&action=messagesent");
}
else {
	echo 'Message failed';
}

?>