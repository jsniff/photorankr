<?php//connect to the databaserequire "db_connection.php";require "functions.php";
$firstname = addslashes($_REQUEST['firstname']);        $firstname = trim($firstname);        $firstname = ucwords($firstname);         $lastname = addslashes($_REQUEST['lastname']);        $lastname = trim($lastname);        $lastname = ucwords($lastname);        $newemail = addslashes($_REQUEST['email']);        $newemail= trim($newemail);        $password = addslashes($_REQUEST['password']);        $password = trim($password);//start the sessionsession_start();    // if login form has been submitted    if (htmlentities($_GET['action']) == "login") {         login();    }    else if(htmlentities($_GET['action']) == "logout") {         logout();    }            $email = $_SESSION['email'];
        
    //ACTION SIGNUP
    
    if($_GET['action'] == "signup") { //if they tried to sign up from signin.php
	$firstname = addslashes($_REQUEST['firstname']);
    $firstname = trim($firstname);
    $firstname = ucwords($firstname);
	$lastname = addslashes($_REQUEST['lastname']);
    $optin = addslashes($_REQUEST['optin']);
    $lastname = trim($lastname);
    $lastname = ucwords($lastname);
	$newemail = mysql_real_escape_string($_REQUEST['emailaddress']);
	$password = mysql_real_escape_string($_REQUEST['password']);
	$confirmpassword = mysql_real_escape_string($_REQUEST['confirmpassword']);
	$terms = mysql_real_escape_string($_REQUEST['terms']);
	$mattfollow = "'support@photorankr.com'";
	$originalfave = "'userphotos/paintedbuilding1.jpg'";
	$originalfave = addslashes($originalfave);
	$mattfollow = addslashes($mattfollow);
	$check = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$newemail'");
	$others = mysql_num_rows($check);
    $currenttime = time();
    $ip = $_SERVER['REMOTE_ADDR'];
    
    // Create a unique  activation code
    $activation = md5(uniqid(rand(), true));
                        
	//if they forgot to enter any information
	if(!$_REQUEST['firstname'] or !$_REQUEST['lastname'] or !$_REQUEST['emailaddress'] or !$_REQUEST['password'] or !$_REQUEST['confirmpassword'] or !$_REQUEST['terms']) {
		mysql_close();
        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=register.php?error=1">';
        exit();
	}
	else if($password != $confirmpassword) { //if passwords dont match
		mysql_close();
        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=register.php?error=2">';
        exit();
	}
    else if(!$terms) { //if passwords dont match
		mysql_close();
        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=register.php?error=3">';
        exit();
	}
	//else if that email address is already in the database
	else if($others != 0) {
		header("Location: lostpassword.php");
	}
	else {		//hash password before entering full info		$salt1 = "@bRa";		$salt2 = "Cad@bra!";		$shapass = sha1($salt1.$password.$salt2);		for($i=0;$i<20000;$i++){			$shapass = sha1($shapass);		}
		//put their info in database
        $settinglist = " emailcomment emailreturncomment emailfave emailfollow ";
		$newuserquery = "INSERT INTO userinfo (firstname, lastname, emailaddress, password, following, faves, settings, promos, time, ip, activation) VALUES ('$firstname', '$lastname', '$newemail', '$shapass', '$mattfollow', '$originalfave','$settinglist','$optin','$currenttime','$ip','$activation')";
		mysql_query($newuserquery);
        
        //Activation Email
        $message = " To activate your PhotoRankr account, please click on this link:\n\n";
        $message .= "https://www.photorankr.com" . '/activate.php?email=' . urlencode($newemail) . "&key=$activation";
        mail($newemail, 'Registration Confirmation', $message, 'From:PhotoRankr <photorankr@photorankr.com>');

        
         //newsfeed query
        $type = "signup";
        $newsfeedsignupquery=mysql_query("INSERT INTO newsfeed (firstname, lastname, emailaddress,type,time) VALUES ('$firstname', '$lastname', '$newemail','$type','$currenttime')");
        
        //SEND REGISTRATION GREETING
        
        $to = $newemail;
        $subject = 'Welcome to PhotoRankr!';
        $message = 'Thank you for signing up with PhotoRankr! You can now upload your own photos and sell them at your own price, follow the best photographers, and become part of a growing community. If you have any questions about PhotoRankr or would like to suggest an improvement, you can email us at photorankr@photorankr.com. We greatly value your feedback and hope you will spread the word about PhotoRankr to your friends by referring them to the site with the link below:
        
		http://photorankr.com/referral.php        

		Again, welcome to the site!

		Sincerely,
		PhotoRankr';
        $headers = 'From:PhotoRankr <photorankr@photorankr.com>';
        mail($to, $subject, $message, $headers);  
        
    }
}?><!DOCTYPE html><html>	<head>        <title>Registration Successful</title>		<link rel="stylesheet" href="960_24.css" type="text/css" />		<link rel="stylesheet" href="css/style.css" type=" /css" />
        <link rel="stylesheet" type="text/css" href="css/bootstrap1.css" />		<link rel="stylesheet" href="text2.css" type="text/css" />
     	<link href = "css/main2 2.css" rel="stylesheet" type="text/css"/>
 		<link rel="stylesheet" type="text/css" href="css/all.css"/>
        <link href = "css/reset.css" rel="stylesheet" type="text/css"/>
        <link href = "css/grid.css" rel="stylesheet" type="text/css"/>

		<style type="text/css">			.btn-signup 			{  				background-color: hsl(101, 55%, 52%) !important;  				background-repeat: repeat-x;  				filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#a9de90", endColorstr="#6bc741");  				background-image: -khtml-gradient(linear, left top, left bottom, from(#a9de90), to(#6bc741));  				background-image: -moz-linear-gradient(top, #a9de90, #6bc741);  				background-image: -ms-linear-gradient(top, #a9de90, #6bc741);  				background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #a9de90), color-stop(100%, #6bc741));  				background-image: -webkit-linear-gradient(top, #a9de90, #6bc741);  				background-image: -o-linear-gradient(top, #a9de90, #6bc741); 				background-image: linear-gradient(#a9de90, #6bc741);  				border-color: #6bc741 #6bc741 hsl(101, 55%, 47%);  				color: #fff !important;  				text-shadow: 0 1px 1px rgba(102, 102, 102, 0.88);  				-webkit-font-smoothing: antialiased;  				padding:1em 2em 1em 2em;  				font-weight: 500;}		.btn-explore { 			 background-color: hsl(0, 0%, 31%) !important;  			background-repeat: repeat-x;  			filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#828282", endColorstr="#4f4f4f");  			background-image: -khtml-gradient(linear, left top, left bottom, from(#828282), to(#4f4f4f));  			background-image: -moz-linear-gradient(top, #828282, #4f4f4f);  			background-image: -ms-linear-gradient(top, #828282, #4f4f4f);  			background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #828282), color-stop(100%, #4f4f4f));  			background-image: -webkit-linear-gradient(top, #828282, #4f4f4f);  			background-image: -o-linear-gradient(top, #828282, #4f4f4f);  			background-image: linear-gradient(#828282, #4f4f4f); 			border-color: #4f4f4f #4f4f4f hsl(0, 0%, 26%); 			color: #fff !important;  			text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.33);  			-webkit-font-smoothing: antialiased;  			padding:1em 2em 1em 2em;}.btn-go {    background-color: hsl(207, 55%, 46%) !important;  background-repeat: repeat-x;  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#68a3d3", endColorstr="#347bb5");  background-image: -khtml-gradient(linear, left top, left bottom, from(#68a3d3), to(#347bb5));  background-image: -moz-linear-gradient(top, #68a3d3, #347bb5);  background-image: -ms-linear-gradient(top, #68a3d3, #347bb5);  background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #68a3d3), color-stop(100%, #347bb5));  background-image: -webkit-linear-gradient(top, #68a3d3, #347bb5);  background-image: -o-linear-gradient(top, #68a3d3, #347bb5);  background-image: linear-gradient(#68a3d3, #347bb5);  border-color: #347bb5 #347bb5 hsl(207, 55%, 42%);  color: #fff !important;  text-shadow: 0 1px 1px rgba(255, 255, 255, 0.26);  -webkit-font-smoothing: antialiased;  padding:.5em 1.6em .5em 1.6em;  font-weight:700;  font-size:14px;  margin-left: .1em;}

.fixedTop
		{
			position: fixed;
			top: 0px;
			}
				.triangle
	{
		width: 0; 
		height: 0; 
		border-left: 11px solid transparent;
		border-right: 11px solid transparent;
		border-bottom: 12px solid #ddd;
		position: relative;
		top:-16px;
		left:107px;
	}
	.triangleLeft
	{
		width: 0; 
		height: 0; 
		border-top: 15px solid transparent;
		border-bottom: 15px solid transparent;
		border-right: 16px solid #eee;
		position: relative;
		top:50px;
		left:310px;
		z-index: 1000;
	}
	#spec{
		background: none;float:left;height:55px !important;width:55px !important;margin:-11px 0 0 100px !important;
	}
	#spec:hover
	{
		background: none;
	}
	#spec img
	{
		width: 182px !important	;
		height:42px !important;
	}
	.scroll
	{
		position:relative !important;
		margin:15px 0 0 0 !important;
	}
    .statoverlay

{
background-attachment: scroll;
background-clip: border-box;
background-color: 
rgba(0, 0, 0, 0.848438);
background-image: none;
background-origin: padding-box;
color: rgb(255, 255, 255);
bottom: 0px;
display: block;
font-family: 'Helvetica Neue', 'Helvetica Neue', Helvetica, Arial, sans-serif;
font-size: 14px;
font-style: normal;
font-variant: normal;
font-weight: normal;
line-height: 0px;
margin-bottom: 0px;
margin-left: 0px;
margin-right: 0px;
margin-top: 0px;
overflow-x: hidden;
overflow-y: hidden;
padding-bottom: 0px;
padding-left: 0px;
padding-right: 0px;
padding-top: 0px;
white-space: nowrap;
width: 270px;
-moz-box-shadow: 1px 1px 5px #888;
-webkit-box-shadow: 1px 1px 5px #888;
box-shadow: 1px 1px 5px #888;
}		</style>    <!--GOOGLE ANALYTICS CODE--><script type="text/javascript">  var _gaq = _gaq || [];  _gaq.push(['_setAccount', 'UA-28031297-1']);  _gaq.push(['_trackPageview']);  (function() {    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);  })();  </script>	</head>  <body style="background-image:url('https://photorankr.com/userphotos/13572631675524abouttotakeoff.jpg');background-size: 100%;background-repeat:no-repeat;overflow-x:hidden;"><!--Navbar-->
<div class="CNav" style="position:fixed;top:0;left:0;z-index:10000;">
<div class="homeNav" style="width:100%;z-index:10000;">
	<ul>
		<li id="spec"> <img src="graphics/logo_big_w.png" style="height:55px;margin-top:8px;width:55px"/> </li>
	</ul>
</div></div>
<!--container begin--><div id="topbar" style="height:30px;padding-top:20px;">	<h1 id="bigtext" style="font-family:helvetica;font-weight:200">
    <?php
    
         if($_GET['action'] == "signup") {echo'<div style="text-align:center;">Please check your email to finish registration, thank you.</div>';}
         elseif($_GET['action'] == "activated") {echo'<div style="text-align:center;">Your account has been created, please sign in below.</div>';}
        else {echo'<div style="text-align:center;"> Complete your sign up for free. </div>';}
    ?>
   </h1>	</div>	

<div class="container_24" style="height:70%;">    
    <div class="grid_24" style="text-shadow: 2px 2px 15px #333;list-style-type:none;color:white;font-family:helvetica neue;font-size:26px;line-height:1.28;font-weight:100;margin-top:80px;text-align:center;">Login to your PhotoRankr Account<br /><span style="font-size:15px;font-weight:300;"></div><br /><br /><br />

    <div class="grid_16 push_4" style="text-align:center;margin-top:30px;">
    <form name="login_form" method="post" action="tutorial.php?action=step1&method=login">

    <input type="text" style="width:220px;background-color:white;padding:8px;font-family:helvetica;font-size:14px;font-weight:100;color:black;" name="emailaddress" placeholder="Email Address" />
&nbsp;&nbsp;&nbsp;
    <input type="password" style="width:220px;background-color:white;padding:8px;font-family:helvetica;font-size:14px;font-weight:100;color:black;" name="password" placeholder="Password" />
    <input type="submit" class="btn btn-success" style="float:right;position:relative;left:-15px;padding:8px;padding-left:14px;padding-right:14px;font-size:14px;" value="Login" id="loginButton"/>

    </form>
    </div>

</div>

</body></html>	