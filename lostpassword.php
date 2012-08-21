<?php
require "db_connection.php";
require "functionsnav.php";


//start the session
session_start();

    // if login form has been submitted
    if (htmlentities($_GET['action']) == "login") { 
        login();
    }
    else if(htmlentities($_GET['action']) == "logout") { 
        logout();
    }

    $email = $_SESSION['email'];

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


<!--FRAME SO SITE READJUSTS PROPORTIONALLY-->

<div style="position: absolute; left: 40px; top: 70px;font-family:lucida grande, georgia, helvetica;font-size:20px;text-shadow: 2px 2px 15px #333;list-style-type:none;color:white;font-family:helvetica neue;font-size:26px;line-height:1.28;font-weight:100;margin-top:50px;">
<p>Enter your email address, and you will receive an email with your password shortly.</p>
<form method="post" action="send_password_ac.php">
<input type="text" name="emailaddress"/>
<input type="submit" value="Send" class="btn btn-success" style="margin-top:-7px;" />
</form>



</div>
</body>
</html>