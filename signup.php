<?php

//connect to the database
require "db_connection.php";
require "functions.php";

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

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Login to PhotoRankr</title>
<meta name="Sign Up for PhotoRankr"></meta>


        <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
		<link rel="stylesheet" href="960_24.css" type="text/css" />
		<link rel="stylesheet" href="css/style.css" type="text/css" />
		<link rel="stylesheet" href="text2.css" type="text/css" />
        <link rel="stylesheet" type="text/css" href="css/main3.css" />

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
  <script src="bootstrap.js" type="text/javascript"></script>
  <script src="bootstrap-dropdown.js" type="text/javascript"></script>
  <script src="bootstrap-collapse.js" type="text/javascript"></script>
  <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
     
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
<body style="background-image:url('img/longview.jpg');background-size: 100%;
background-repeat:no-repeat;overflow-x:hidden;">
<?php include_once("analyticstracking.php") ?>

<?php navbar(); ?>

<div class="container_24" style="padding-top:80px;"> <!--container begin--->
<div class="grid_24">
       
<?php

if($_GET['action'] == '') {
echo'<div class="grid_24" style="text-shadow: 2px 2px 15px #333;list-style-type:none;color:white;font-family:helvetica neue;font-size:26px;line-height:1.28;font-weight:100;margin-top:50px;text-align:center;">Login to your PhotoRankr Account<br /><span style="font-size:15px;font-weight:300;"><a class="click2" style="font-size:15px;color:#fff;" href="signup3.php">Or Sign Up Free Today</a></span></div><br /><br /><br />

<div class="grid_16 push_4" style="text-align:center;margin-top:30px;">
<form name="login_form" method="post" action="profile.php?action=login">

<input type="text" style="width:220px;background-color:white;padding:8px;font-family:helvetica;font-size:14px;font-weight:100;color:black;" name="emailaddress" placeholder="Email Address" />
&nbsp;&nbsp;&nbsp;
<input type="password" style="width:220px;background-color:white;padding:8px;font-family:helvetica;font-size:14px;font-weight:100;color:black;" name="password" placeholder="Password" />
<input type="submit" class="btn btn-success" style="float:right;position:relative;left:-15px;padding:8px;padding-left:14px;padding-right:14px;font-size:14px;" value="Login" id="loginButton"/>

</form>
</div>';

}

if($_GET['action'] == disc) {
echo'<div class="well" style="font-size:18px;font-family:helvetica, arial;margin-top:50px;margin-left:60px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Discover great photography that pertains to your interests. Login below or <a href="signin.php">register today</a> to begin.</div>

<div style="margin-top:70px;margin-left:260px;padding-bottom:150px;">
<form name="login_form" method="post" action="discover.php?action=login">
<div class="well" style="width:380px;padding-top:50px;padding-bottom:50px;padding-left:40px;">
<span style="font-size:18px;font-family:helvetica, arial;margin-left:0px;">Email: </span><input type="text" style="width:200px;margin-left:40px;" name="emailaddress" /><br />
<span style="font-size:18px;font-family:helvetica, arial;">Password: </span>&nbsp<input type="password" style="width:200px;" name="password"/><br >
<input type="submit" class="btn btn-success" style="margin-left:250px;" value="sign in" id="loginButton"/>
</div>
</form>
</div>';
}

if($_GET['action'] == camp) {
echo'<div class="well" style="font-size:18px;font-family:helvetica, arial;margin-top:50px;margin-left:60px;line-height:25px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img style="margin-top:-6px;" src="campaign/graphics/logocampaign.png" width="260" /> let you submit photos to businesses and individuals with specific photo needs. When the campaign ends, the business or individual pays the winning photographer. To enter a campaign, login below or <a href="signin.php">register today</a>.</div>

<div style="margin-top:70px;margin-left:260px;padding-bottom:150px;">
<form name="login_form" method="post" action="viewcampaigns.php?action=login">
<div class="well" style="width:380px;padding-top:50px;padding-bottom:50px;padding-left:40px;">
<span style="font-size:18px;font-family:helvetica, arial;margin-left:0px;">Email: </span><input type="text" style="width:200px;margin-left:40px;" name="emailaddress" /><br />
<span style="font-size:18px;font-family:helvetica, arial;">Password: </span>&nbsp<input type="password" style="width:200px;" name="password"/><br >
<input type="submit" class="btn btn-success" style="margin-left:250px;" value="sign in" id="loginButton"/>
</div>
</form>
</div>';
}

//came from campaign upload
if($_GET['action'] == campupload) {
$campaignID = htmlentities($_GET['id']);
echo'<div class="well" style="font-size:18px;font-family:helvetica, arial;margin-top:50px;margin-left:60px;line-height:25px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img style="margin-top:-6px;" src="campaign/graphics/logocampaign.png" width="260" /> let you submit photos to businesses and individuals with specific photo needs. When the campaign ends, the business or individual pays the winning photographer. To upload a photo, login below or <a href="signin.php">register today</a>.</div>

<div style="margin-top:70px;margin-left:260px;padding-bottom:150px;">
<form name="login_form" method="post" action="uploadcampaignphoto.php?id=',$campaignID,'&action=login">
<div class="well" style="width:380px;padding-top:50px;padding-bottom:50px;padding-left:40px;">
<span style="font-size:18px;font-family:helvetica, arial;margin-left:0px;">Email: </span><input type="text" style="width:200px;margin-left:40px;" name="emailaddress" /><br />
<span style="font-size:18px;font-family:helvetica, arial;">Password: </span>&nbsp<input type="password" style="width:200px;" name="password"/><br >
<input type="submit" class="btn btn-success" style="margin-left:250px;" value="sign in" id="loginButton"/>
</div>
</form>
</div>';
}

//lost password. passwords did not match
if($_GET['action'] == lp) {
echo'<div class="well" style="font-size:18px;font-family:helvetica, arial;margin-top:50px;margin-left:60px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Oops, you accidentally entered a wrong password. <a href="signin.php">Lost your password?</a></div>

<div style="margin-top:70px;margin-left:260px;padding-bottom:150px;">
<form name="login_form" method="post" action="profile.php?action=login">
<div class="well" style="width:380px;padding-top:50px;padding-bottom:50px;padding-left:40px;">
<span style="font-size:18px;font-family:helvetica, arial;margin-left:0px;">Email: </span><input type="text" style="width:200px;margin-left:40px;" name="emailaddress" /><br />
<span style="font-size:18px;font-family:helvetica, arial;">Password: </span>&nbsp<input type="password" style="width:200px;" name="password"/><br >
<input type="submit" class="btn btn-success" style="margin-left:250px;" value="sign in" id="loginButton"/>
</div>
</form>
</div>';
}

//user does not exist
if($_GET['action'] == nu) {
echo'<div class="well" style="font-size:18px;font-family:helvetica, arial;margin-top:50px;margin-left:60px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The email address you entered does not currently exist in our database. Click <a href="signin.php">here</a> to register.</div>

<div style="margin-top:70px;margin-left:260px;padding-bottom:150px;">
<form name="login_form" method="post" action="profile.php?action=login">
<div class="well" style="width:380px;padding-top:50px;padding-bottom:50px;padding-left:40px;">
<span style="font-size:18px;font-family:helvetica, arial;margin-left:0px;">Email: </span><input type="text" style="width:200px;margin-left:40px;" name="emailaddress" /><br />
<span style="font-size:18px;font-family:helvetica, arial;">Password: </span>&nbsp<input type="password" style="width:200px;" name="password"/><br >
<input type="submit" class="btn btn-success" style="margin-left:250px;" value="sign in" id="loginButton"/>
</div>
</form>
</div>';
}

//forgot to fill in email
if($_GET['action'] == fie) {
echo'<div class="well" style="font-size:18px;font-family:helvetica, arial;margin-top:50px;margin-left:60px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Oops, you may have accidentally forgot to enter your email address.</div>

<div style="margin-top:70px;margin-left:260px;padding-bottom:150px;">
<form name="login_form" method="post" action="profile.php?action=login">
<div class="well" style="width:380px;padding-top:50px;padding-bottom:50px;padding-left:40px;">
<span style="font-size:18px;font-family:helvetica, arial;margin-left:0px;">Email: </span><input type="text" style="width:200px;margin-left:40px;" name="emailaddress" /><br />
<span style="font-size:18px;font-family:helvetica, arial;">Password: </span>&nbsp<input type="password" style="width:200px;" name="password"/><br >
<input type="submit" class="btn btn-success" style="margin-left:250px;" value="sign in" id="loginButton"/>
</div>
</form>
</div>';
}

//forgot to fill in password
if($_GET['action'] == fip) {
echo'<div class="well" style="font-size:18px;font-family:helvetica, arial;margin-top:50px;margin-left:60px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Oops, you may have accidentally forgot to enter your password.</div>

<div style="margin-top:70px;margin-left:260px;padding-bottom:150px;">
<form name="login_form" method="post" action="profile.php?action=login">
<div class="well" style="width:380px;padding-top:50px;padding-bottom:50px;padding-left:40px;">
<span style="font-size:18px;font-family:helvetica, arial;margin-left:0px;">Email: </span><input type="text" style="width:200px;margin-left:40px;" name="emailaddress" /><br />
<span style="font-size:18px;font-family:helvetica, arial;">Password: </span>&nbsp<input type="password" style="width:200px;" name="password"/><br >
<input type="submit" class="btn btn-success" style="margin-left:250px;" value="sign in" id="loginButton"/>
</div>
</form>
</div>';
}

?>       
        
 </div>             
 </div><!--container end--> 
        
  
  </div>
  
</div> <!--Container End-->  
</body>
</html> 