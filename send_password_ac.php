<?php

/*
*This file receives the users emailaddress from the lost password 
*and then sends it to the user via email
*/


//connect to the database
require "db_connection.php";
require "functionsnav.php";

//get the posted email address
$email_to = mysql_real_escape_string($_REQUEST['emailaddress']);

//find the user in the database
$userquery = "SELECT * FROM userinfo WHERE emailaddress='$email_to'";
$userresult = mysql_query($userquery);
$count = mysql_num_rows($userresult);

//if there is only one person
if($count == 1) {
	//find their password	
	$password = mysql_result($userresult, 0, "password");
	$subject = "Photorankr Password";
	// From
	$header="from: photorankr@photorankr.com";

	//the message
	$messages= "You requested your password from photorankr.com. \r\n";
	$messages .= "The password associated with this email is $password \r\n";
	$messages .= "Visit photorankr.com now to sign in! \r\n";

	// send email
	$sentmail = mail($email_to,$subject,$messages,$header);
}
// else if $count not equal 1
else {
	echo '<div style="margin-top:200px;text-align:center;font-size:20px;text-shadow: 2px 2px 15px #333;list-style-type:none;color:white;font-family:helvetica neue;font-size:26px;line-height:1.28;font-weight:100;">Oops, your email was not found.</div>';
}

// if your email succesfully sent
if($sentmail){
	echo '<div style="margin-top:200px;text-align:center;font-size:20px;text-shadow: 2px 2px 15px #333;list-style-type:none;color:white;font-family:helvetica neue;font-size:26px;line-height:1.28;font-weight:100;">Your Password Has Been Sent To Your Email Address. You should receive it shortly.</div>';
}
else {
	echo '<div style="margin-top:100px;text-align:center;font-size:20px;text-shadow: 2px 2px 15px #333;list-style-type:none;color:white;font-family:helvetica neue;font-size:26px;line-height:1.28;font-weight:100;">There must have been an error. Please try again.</div>';
}

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

 <link rel="stylesheet" type="text/css" href="css/bootstrapNew.css" />
 <link rel="stylesheet" href="960_24.css" type="text/css" />
 <link rel="stylesheet" href="css/style.css" type="text/css" />
 <link rel="stylesheet" href="text2.css" type="text/css" />
 <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>

<title>Photorankr- Upload. Rank. Discover.</title>

<!--GOOGLE ANALYTICS CODE-->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-28031297-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>


</head>

<body style="background-image:url('img/longview.jpg');background-size: 100%;
background-repeat:no-repeat;overflow-x:hidden;">

<?php navbarnew(); ?>

</body>
</html>