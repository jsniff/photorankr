<?php

//connect to the database
require "db_connection.php";
require "functions.php";

//log them out if they try to logout
session_start();

if($_GET['action'] == logout) {
	$_SESSION['loggedin'] = 0;
	session_destroy();
}

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

//QUERY FOR NOTIFICATIONS
$currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$email'";
$currentnotsquery = mysql_query($currentnots);
$currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
   <meta name="viewport" content="width=1200" /> 

<meta name="Generator" content="EditPlus">
  <meta name="Author" content="PhotoRankr, PhotoRankr.com">
  <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
  <meta name="Description" content="PhotoRankr allows photographers of all skill levels to sell and share their work. Create your photostream cutomized to what you want to see. Add photos to your favorites, rank them, and watch them trend. Build your portfolio with Photorankr.">

<title>Referral</title>
<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="css/style.css" />
  <link rel="stylesheet" href="960_24.css" type="text/css" />
      <link rel="stylesheet" type="text/css" href="css/main3.css" />
  <script type="text/javascript" src="jquery.js"></script>   
  <script src="bootstrap-dropdown.js" type="text/javascript"></script>
  <script src="bootstrap-collapse.js" type="text/javascript"></script>
<link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>

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

</script>

<script type="text/javascript">
  $(function() {
  // Setup drop down menu
  $('.dropdown-toggle').dropdown();
 
  // Fix input element click problem
  $('.dropdown input, .dropdown label').click(function(e) {
    e.stopPropagation();
  });
});
</script>

</head>


<body style="overflow-x:hidden; background-image: url('graphics/paper.png');">


<!--SET PAGE WIDTH AUTOMATICALLY AND ADD LOGO AND SLOGAN-->

<?php navbar(); ?>

<!--big container-->
    <div id="container" class="container_12" >
		<div class="grid_12" style="margin-top:150px">
        
<?php        
        $view=htmlentities($_GET['view']); 


if ($view == 'referralsuccess') {
$name = $_POST["name"];
$sendemail = $_POST["email"];
$to = $sendemail;
$subject = "Your Personal Invitation";
$message = "Hi! You've been invited by $name to join PhotoRankr, a site for photographers of all skill levels. What makes PhotoRankr different from the other photo sharing sites?

– The ability to choose the price of your photography 
– Unlimited uploads and 100% free
– Follow other photographers with one click, and view your live 'photostream' of photography from those you follow
– Rank other photography and get feedback from other photographers through comments 
– Make your own profile where you can view your entire portfolio, your followers, who's following you, and edit your information

To accept your invitation and begin following photography today, just click the link below:

http://www.photorankr.com/signin.php

We hope you'll enjoy PhotoRankr as much as we have building it,

Sincerely,
The PhotoRankr Team
";

$headers = 'From:PhotoRankr <photorankr@photorankr.com>';
mail($to, $subject, $message, $headers);

echo '<div style="position:absolute; top:300px;font-family:lucida grande, georgia, helvetica; font-size: 16px;">Referral Sent!</div>';

}


echo '

<div style="margin-left:100px;">

<div style="position:absolute; top:100px; font-family:helvetica,arial;font-size: 20px; font-weight:lighter;">Help us grow the PhotoRankr community by inviting your friends below:</div>

<div style="position:absolute; top:180px; font-family:lucida grande, georgia, helvetica; font-size: 16px;">Your Name:</div>';

echo '<div style="position:absolute; top:225px; font-family:lucida grande, georgia, helvetica; font-size: 16px;">Send invitation to:</div>';

echo'<div style="position:relative; top:25px; left:220px;">
<form action="', htmlentities($_SERVER['PHP_SELF']), '?view=referralsuccess" method="post">
<input style="width:180px;height:22px;;" type="text" name="name" placeholder="Your Name"/>
</div>
<div style="position:relative; top:10px; left:220px;">
<input style="width:180px;height:22px;" type="text" name="email" placeholder="Email Address"/>
</div>
<div style="position:relative; top:15px; left:322px;">
<button type="submit" name="Submit" class="btn btn-success">Send Invite</button>
</div>
</form>
</div>';

?>
</div>

</div>

</div>


</body>
</html>