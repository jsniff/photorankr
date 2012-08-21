<?php
$email=$_REQUEST['email'];
$message=$_REQUEST['message'];
$name=$_REQUEST['name'];

if (!isset($_REQUEST['email']))
{
	header( "Location:http://www.photorankr.com/contact.php");
}
elseif(empty($email) || empty($message)) {

	header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
    header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
    header( "Cache-Control: no-cache, must-revalidate" );
    header( "Pragma: no-cache" );

	?>
	<html>
	<head>
		<title> Form Error </title>
	</head>
	<body>
		<h1> Error </h1>
		<p> It appears that you forgot to enter your name, email, or message. </p>
		<p> Retrun to the <a href="contact.php">Previous Page</button></a></p>
	</body>
	</html>

	<?php	
}

else{
	//$from = 'From:' . $name . '<' . $email . '>';
	mail("photorankr@photorankr.com", "From The Contact Page", $message, "From: $name <$email>");
	header( "Location: http://photorankr.com/contact_success.php" );
}	
?>