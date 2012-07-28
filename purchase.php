<?php
//log them out if they try to logout
session_start();

if($_GET['action'] == logout) {
	$_SESSION['loggedin'] = 0;
	session_destroy();
}
?>


<?php
if($_SESSION['loggedin'] != 1) { //if they aren't logged in, display signin central
echo '<form name="login_form" method="post" action="', htmlentities($_SERVER['PHP_SELF']), '?action=login">
<div id="login">Email: &nbsp<input type="text" style="width:120px" name="emailaddress"/></div>
<div id="login2">Password: &nbsp<input type="password" style="width:120px" name="password"/></div>
<div id="login3"><a href="http://www.photorankr.com/signin.php">Not Registered?</a></div>

<input type="image" src="graphics/signin.png" style="height:35px; width:140px;" id="loginButton"/></form>';
}

else { //if they are logged in, show my profile button
echo '<div class="profileButton">
<a href="http://www.photorankr.com/myprofile.php"><img src="graphics/profileButton.png" height="45" width="180"></img></a>
</div>';
}
?>

<?php

if($_SESSION['loggedin'] == 1) {
echo '
<!--LOGOUT BUTTON-->
<div id="logout">
<a href="',htmlentities($_SERVER['PHP_SELF']),'?action=logout"><img src="graphics/logout.png" style="height:45px; width:170px;"/></a>
</div>';
}

?>

<?php

//CONNECT TO DATABASE
require "db_connection.php";

$itemname = $_POST['item_name'];
echo '<br />';
echo $itemname;
?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" media="print" href="print.css">
<link rel="stylesheet" href="fullsize.css" type="text/css" />
<link rel="stylesheet" href="style.css" type="text/css" />
<link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
<title>Search Photos</title>


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

<body style="overflow-x:hidden">



<!--LOGO AND SLOGAN-->
<div id="header">
<div style="text-align:left">
<div class="logo">
<a href="http://www.photorankr.com/trending.php"><img src="graphics/photorankr2.png"></img></a>
</div>
<div id="slogan">
<a href="http://www.photorankr.com/trending.php"><img src="graphics/slogan.png"></img></a>
</div>
</div>



<!--PAYPAL END-->


<form target="_self" action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="item_name" value="<?php echo $label; ?>">
<input type="hidden" name="amount" value="170" />
<input type="hidden" name="business" value="photorankr@photorankr.com">
<input type="hidden" name="add" value="1">
<input type="hidden" name="cmd" value="_cart">
<input type="hidden" name="item_number" value="<?php echo $imageID; ?>">
<input type="hidden" name="amount" value="<?php echo $price; ?>">
<input type="hidden" name="no_shipping" value="0">
<input type="hidden" name="return" value="http://photorankr.com/ordersuccess.html">
<input type="hidden" name="cancel_return" value="http://photorankr.com/ordercancel.html">
<input type="hidden" name="logo_custom" value="http://photorankr.com/logo.html">
<input type="hidden" name="no_note" value="1">

<input type="submit" name="submit" value="BUY PHOTO NOW"></input>
</form>




<?php

//start session
session_start();
//if the login form is submitted
if (htmlentities($_GET['action']) == "login") { // if login form has been submitted

	// makes sure they filled it in
	if(!htmlentities($_POST['emailaddress']) | !htmlentities($_POST['password'])) {
		die('You did not fill in a required field.');
	}

	// checks it against the database
	/*if (!get_magic_quotes_gpc()) {
   	$_POST['emailaddress'] = addslashes(htmlentities($_POST['emailaddress']));
	$_POST['emailaddress'] = mysql_real_escape_string($_POST['emailaddress']);
    	}*/
    	$check = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '".mysql_real_escape_string($_POST['emailaddress'])."'")or die(mysql_error());
	//Gives error if user dosen't exist

	$check2 = mysql_num_rows($check);
    
?>


<div style="position:absolute; top:0px;left:0px;font-family:lucida grande, georgia, helvetica; font-size: 25px; background:url('graphics/background.png');height:100%;width:100%;">


<?php

	if ($check2 == 0) {
        	die('That user does not exist in our database. <a href=signin.php>Click Here to Register</a> or <a href="lostpassword.php">here to recover a forgotten password</a>.');
        }

	$info = mysql_fetch_array($check);    
	if(mysql_real_escape_string($_POST['password']) == mysql_real_escape_string($info['password'])){

	//then redirect them to the same page as signed in and set loggedin to 1
	$_SESSION['loggedin']=1;
	$_SESSION['email']=$_POST['emailaddress'];
	}
   
	//gives error if the password is wrong
    	else if (mysql_real_escape_string($_POST['password']) != mysql_real_escape_string($info['password'])) {
die('Incorrect password, please try again. <a href="lostpassword.php"> Lost your password?</a>');	}
}

?>

</div>



<!--NAVBAR ITEMS-->

<img style="position:absolute;top:100px;left:20px;z-index:1234213412341234;" src="graphics/navbarnew.png" height="45" width="1200"></img>

<a href="http://www.photorankr.com/trending.php"><img class="allphotos" style="z-index:1234213412341335" src="graphics/trendingbuttonnew.png" height="48" width="282"></img></a>

<a href="http://www.photorankr.com/newest.php"> <img class="newestphotos" src="graphics/newestbuttonnew.png" height="49" width="282"/></a>

<a href="http://www.photorankr.com/topten.php"> <img class="bestphotos" src="graphics/toprankedbuttonnew.png" height="49" width="282"/></a>

<a href="http://www.photorankr.com/photostream.php"> <img class="photostream" src="graphics/photostreambuttonnew.png" height="47" width="281"/></a>

<img class="search" src="graphics/searchbar.png" height="51" width="257"/>

<form action="search.php" method="post">
<input class="searchbox" type="text" name="searchterm"><br />
</form>



</body>
</html>
