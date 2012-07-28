<?php

//log them out if they try to logout
session_start();

if($_GET['action'] == "logout") {
	$_SESSION['loggedin'] = 0;
	session_destroy();
}

//connect to the database
require "db_connection.php";

//start session
session_start();
//if the login form is submitted
if ($_GET['action'] == "login") { // if login form has been submitted

	// makes sure they filled it in
	if(!$_POST['emailaddress'] | !$_POST['password']) {
		die('You did not fill in a required field.');
	}

	// checks it against the database
	if (!get_magic_quotes_gpc()) {
   	$_POST['emailaddress'] = addslashes($_POST['emailaddress']);
    	}
    	$check = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '".$_POST['emailaddress']."'")or die(mysql_error());
	//Gives error if user dosen't exist

	$check2 = mysql_num_rows($check);

	if ($check2 == 0) {

        	die('That user does not exist in our database. <a href=signin.php>Click Here to Register</a>');

        }
	$info = mysql_fetch_array($check);    
	if($_POST['password'] == $info['password']){

	//then redirect them to the same page as signed in and set loggedin to 1
	$_SESSION['loggedin']=1; 
	$_SESSION['email']=$_POST['emailaddress'];
    $email = $_SESSION['email'];
	}
    
	//gives error if the password is wrong
    	if ($_POST['password'] != $info['password']) {
die('Incorrect password, please try again. <a href="lostpassword.php"> Lost your password?</a>');	}
}


//find the current time
$currenttime = time();

//find out which view they are looking at
$view = htmlentities($_GET['view']);

$email6 = $_SESSION['email'];

//QUERY FOR NOTIFICATIONS
$currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$email6'";
$currentnotsquery = mysql_query($currentnots);
$currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");

//DE-HIGHLIGHT NOTIFICATIONS IF CLICKED ON

if(isset($_GET['newsid'])){
$newsid = htmlentities($_GET['newsid']);
$idformatted = $newsid . " ";
$unhighlightquery = "UPDATE userinfo SET unhighlight = CONCAT(unhighlight,'$idformatted') WHERE emailaddress = '$email6'";
$unhighlightqueryrun = mysql_query($unhighlightquery);

//notifications query reset 
if($currentnotsresult > 0) {
$notsquery = "UPDATE userinfo (SET notifications = 0) WHERE emailaddress = '$email6'";
$notsqueryrun = mysql_query($notsquery); }
}

//DISCOVER SCRIPT

    $useremail = $_SESSION['email'];
    
  //get the users information from the database
  $likesquery = "SELECT * FROM userinfo WHERE emailaddress='$useremail'";
  $likesresult = mysql_query($likesquery) or die(mysql_error());
  $discoverseen = mysql_result($likesresult, 0, "discoverseen");

  //find out what they like
  $likes = mysql_result($likesresult, 0, "viewLikes");
    if($likes=="") {
		$nolikes = 1;
        		
	}
  $likes .= "  ";
  $likes .= mysql_result($likesresult, 0, "buyLikes");

  //create an array from what they like
  $likesArray = explode("  ", $likes);

  //loop through the array to format the likes in the proper format for the query
  $formattedLikes = "%";
  for($iii=0; $iii < count($likesArray); $iii++) {
    $formattedLikes .= $likesArray[$iii];
    $formattedLikes .= "%";
  }

    //make an array of the photos they have already seen
  if($discoverseen != "") {
    $discoverArray = explode(" ", $discoverseen);
    $discoverFormatted = "";
    for($iii=0; $iii < count($discoverArray)-1; $iii++) {
      $discoverFormatted .= "'";
      $discoverFormatted .= $discoverArray[$iii];
      $discoverFormatted .= "', ";
    }
    $discoverFormatted .= "'";
    $discoverFormatted .= $discoverArray[count($discoverArray)-1];
    $discoverFormatted .= "'";
  }
  
  //select the image that they will be seeing next
  //delineate between whether they have used discover feature before
  if($discoverseen != "") {     //get the photos that match this person's view interests
    $viewquery = "SELECT *, MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$formattedLikes') AS matching FROM photos WHERE MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$formattedLikes') AND id NOT IN(" . $discoverFormatted . ") ORDER BY matching DESC, points DESC LIMIT 0, 1";
    $viewresult = mysql_query($viewquery) or die(mysql_error());
  }
  else {
    //get the photos that match this person's view interests
    $viewquery = "SELECT *, MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$formattedLikes') AS matching FROM photos WHERE MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$formattedLikes') ORDER BY matching DESC, points DESC LIMIT 0, 1";
    $viewresult = mysql_query($viewquery) or die(mysql_error());
  }

  $discoverimage = mysql_result($viewresult, 0, "id");

?>


<!DOCTYPE html>
<head>
	<title>View all of the campaigns on PhotoRankr</title>
    <link rel="stylesheet" type="text/css" href="bootstrapnew.css" />
    <link rel="stylesheet" href="reset.css" type="text/css" />
    <link rel="stylesheet" href="text.css" type="text/css" />
    <link rel="stylesheet" href="960_24.css" type="text/css" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  	<script src="market/js/bootstrap.js" type="text/javascript"></script>
  	<script src="market/js/bootstrap-dropdown.js" type="text/javascript"></script>
  	<script src="market/js/bootstrap-collapse.js" type="text/javascript"></script>
  	<link rel="shortcut icon" type="image/x-png" href="market/graphics/favicon.png"/>
    
    <!--Navbar Dropdowns-->
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
    
  <style type="text/css">


 .statoverlay {
opacity:.7;
filter:alpha(opacity=40);
z-index:1;
transition: opacity .5s;
-moz-transition: opacity .5s;
-webkit-transition: opacity .5s;
-o-transition: opacity .5s;
}
 
.statoverlay:hover {
opacity:.7;
}                           

.item {
  margin: 10px;
  float: left;
  border: 2px solid transparent;
}

.item:hover {
  margin: 10px;
  float: left;
  border: 2px solid black;
}

</style>
</head>

<body style="overflow-x:hidden; background-color: #eeeff3;">

<!--NAVIGATION BAR-->
<div class="navbar" style="z-index:10;min-width:1220px;padding-top:0px;font-size:16px;width:100%;">
	<div class="navbar-inner">
		<div class="container">
			    <ul class="nav">
					<li><a style="color:#fff;" class="brand" style="margin-top:10px;margin-right:20px;" href="../index.php"><div style="margin-top:-2px"><img src="../logo.png" width="160" /></div></a></li>
                    
                    <?php               
                     if($_SESSION['loggedin'] == 1) {
                     echo'
                     <li style="color:#fff;margin-top:1px;" class="dropdown active, navbartext"><a style="color:rgb(56,85,103);margin-right:20px;" href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <span style="font-size:18px;padding-right:3px;color:#fff;margin-top:2px;"><span style="position:relative;top:4px;"><i class="icon-exclamation-sign icon-white"></i></span> ',$currentnotsresult,'</a>
                    <ul class="dropdown-menu" data-dropdown="dropdown">';


$email7 = $_SESSION['email'];

//NOTIFICATION QUERIES  
$emailquery=("SELECT following FROM userinfo WHERE emailaddress ='$email7'");
$followresult=mysql_query($emailquery);
$followinglist=mysql_result($followresult, 0, "following");

$notsquery = "SELECT * FROM newsfeed WHERE (owner = '$email7' AND emailaddress != '$email7') OR following = '$email7' ORDER BY id DESC";
$notsresult = mysql_query($notsquery);
$numnots = mysql_num_rows($notsresult);

$ctype = 'campaign';
$campaignnews = "SELECT * FROM newsfeed WHERE type = '$ctype' ORDER BY id DESC";
$campaignnewsquery = mysql_query($campaignnews);
$numcamps = mysql_num_rows($campaignnewsquery);

$cetype = 'campaignended';
$campaignendednews = "SELECT * FROM newsfeed WHERE type = '$cetype' AND campaignentree LIKE '%$email7%' ORDER BY id DESC";
$campaignendednewsquery = mysql_query($campaignendednews);
$numendcamps = mysql_num_rows($campaignendednewsquery);

$fetype = 'feedback';
$campaignfeedbacknews = "SELECT * FROM newsfeed WHERE type = '$fetype' AND campaignentree LIKE '%$email7%' ORDER BY id DESC";
$campaignfeedbacknewsquery = mysql_query($campaignfeedbacknews);
$numfeedcamps = mysql_num_rows($campaignfeedbacknewsquery);

//DECIDE WHICH NOTIFICATIONS TO WHITEN (ONES ALREADY CLICKED ON)
$unhighlightquery = "SELECT * FROM userinfo WHERE emailaddress = '$email7'";
$unhighlightqueryrun = mysql_query($unhighlightquery);
$whitenlist=mysql_result($unhighlightqueryrun, 0, "unhighlight");


if($numnots > 1) {
echo'<div style="width:500px;height:400px;overflow-y:scroll;font-size:14px;">';

for ($iii=1; $iii <= 20; $iii++) {
$notsarray = mysql_fetch_array($notsresult);
$campaignarray =  mysql_fetch_array($campaignnewsquery);
$campaignendedarray =  mysql_fetch_array($campaignendednewsquery);
$campaignfeedbackarray =  mysql_fetch_array($campaignfeedbacknewsquery);
$firstname4 = $notsarray['firstname'];
$lastname4 = $notsarray['lastname'];
$fullname4 = $firstname4 . " " . $lastname4;
$fullname4 = ucwords($fullname4);
$type = $notsarray['type'];
$typecamp = $campaignarray['type'];
$typecampended = $campaignendedarray['type'];
$typecampfeedback = $campaignfeedbackarray['type'];
$id = $notsarray['id'];
$typecampid = $campaignarray['id'];
$typecampendedid = $campaignendedarray['id'];
$typecampfeedbackid = $campaignfeedbackarray['id'];

//SEARCH IF ID IS IN UNHIGHLIGHT LIST
$search_string = $whitenlist;
$regex = $id;
$match=strpos($whitenlist,$regex);


if($match < 1) {

if($typecamp) {
$caption4 = $campaignarray['caption'];
$source= $campaignarray['source'];
$quotecampquery = mysql_query("SELECT quote FROM campaigns WHERE id = '$source'");
$quotecamp = mysql_result($quotecampquery, 0, "quote");
$phrase = 'New Campaign: (Reward $' . $quotecamp . ')  "' . $caption4 . '"';
$phrase = (strlen($phrase) > 56) ? substr($phrase,0,53). " &#8230;" : $phrase;
    
$newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
echo'<a style="text-decoration:none" href="campaignphotos.php?id=',$source,'&newsid=',$typecampid,'"><div id="photoshadow2"><img src="graphics/smallcampaignicon.png" height="50" width="50" />&nbsp;<span style="color:black;">',$phrase,'</span></div></a><br />';
}

elseif($typecampended) {
$caption4 = $campaignendedarray['caption'];
$source= $campaignendedarray['source'];
$phrase = 'Campaign Winner Picked: "'.$caption4.'"';
$phrase = (strlen($phrase) > 56) ? substr($phrase,0,53). " &#8230;" : $phrase;
    
$newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
echo'<a style="text-decoration:none" href="campaignphotos.php?id=',$source,'&newsid=',$typecampendedid,'"><div id="photoshadow2"><img src="graphics/smallcampaignicon.png" height="50" width="50" />&nbsp;<span style="color:black;">',$phrase,'</span></div></a><br />';
}

elseif($typecampfeedback) {
$caption4 = $campaignfeedbackarray['caption'];
$source= $campaignfeedbackarray['source'];
$phrase = 'Campaign Feedback: "'.$caption4.'"';
$phrase = (strlen($phrase) > 56) ? substr($phrase,0,53). " &#8230;" : $phrase;
    
$newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
echo'<a style="text-decoration:none" href="campaignphotos.php?id=',$source,'&newsid=',$$typecampfeedbackid,'"><div id="photoshadow2"><img src="graphics/smallcampaignicon.png" height="50" width="50" />&nbsp;<span style="color:black;">',$phrase,'</span></div></a><br />';
}

elseif($type == "comment") {
$caption4 = $notsarray['caption'];
$source= $notsarray['source'];
$newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
echo'<a style="text-decoration:none" href="fullsize.php?image=',$source,'&id=',$id,'"><div id="photoshadowhighlight2"><img src="graphics/newsfeedcomment.png" height="50" width="50" />&nbsp;<img src="http://www.photorankr.com/',$newsource,'" height="50" width="50" />&nbsp;<span style="color:white">',$fullname4,' commented on your photo</span></div></a><br />';
}

elseif($type == "message") {
$ownermessage = $notsarray['owner'];
$thread = $notsarray['thread'];
$newaccount = "SELECT * FROM userinfo WHERE emailaddress = '$ownermessage'";
$accountresult = mysql_query($newaccount); 
$accountrow = mysql_fetch_array($accountresult);
$profilepic4 = $accountrow['profilepic'];
if($profilepic4 == "") {
$profilepic4 = "profilepics/default_profile.jpg";
}
echo'<a style="text-decoration:none" href="myprofile.php?view=viewthread&thread=',$thread,'&id=',$id,'"><div id="photoshadowhighlight2"><img src="graphics/messagesicon.png" height="50" width="50" />&nbsp;<img src="',$profilepic4,'" height="50" width="50" />&nbsp;<span style="color:white">',$fullname4,' sent you a message</span></div></a><br />';
}

elseif($type == "reply") {
$ownermessage = $notsarray['owner'];
$thread = $notsarray['thread'];
$newaccount = "SELECT * FROM userinfo WHERE emailaddress = '$ownermessage'";$accountresult = mysql_query($newaccount); 
$accountrow = mysql_fetch_array($accountresult);
$profilepic4 = $accountrow['profilepic'];
if($profilepic4 == "") {
$profilepic4 = "profilepics/default_profile.jpg";
}
echo'<a style="text-decoration:none" href="myprofile.php?view=viewthread&thread=',$thread,'&id=',$id,'"><div id="photoshadowhighlight2"><img src="graphics/messagesicon.png" height="50" width="50" />&nbsp;<img src="',$profilepic4,'" height="50" width="50" />&nbsp;<span style="color:white">',$fullname4,' replied to your message</span></div></a><br />';
}

elseif($type == "fave") {
$caption4 = $notsarray['caption'];
$source= $notsarray['source'];
$newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
echo'<a style="text-decoration:none" href="fullsize.php?image=',$source,'&id=',$id,'"><div id="photoshadowhighlight2"><img src="graphics/newsfeedfavorite.png" height="50" width="50" />&nbsp;<img src="http://www.photorankr.com/',$newsource,'" height="50" width="50" />&nbsp;<span style="color:white">',$fullname4,' favorited your photo</span></div></a><br />';
}

elseif($type == "trending") {
$caption4 = $notsarray['caption'];
$source= $notsarray['source'];
$newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
echo'<a style="text-decoration:none" href="fullsize.php?image=',$source,'&id=',$id,'"><div id="photoshadowhighlight2"><img src="graphics/newsfeedtrending.png" height="50" width="50" />&nbsp;<img src="http://www.photorankr.com/',$newsource,'" height="50" width="50" />&nbsp;<span style="color:white">Your photo is now trending</span></div></a><br />';
}

elseif($type == "follow") {
$caption4 = $notsarray['caption'];
$followeremail= $notsarray['emailaddress'];
$newaccount = "SELECT * FROM userinfo WHERE emailaddress = '$followeremail'";
$accountresult = mysql_query($newaccount); 
$accountrow = mysql_fetch_array($accountresult);
$ownerid = $accountrow['user_id'];
$profilepic4 = $accountrow['profilepic'];
if($profilepic4 == "") {
$profilepic4 = "profilepics/default_profile.jpg";
}
echo'<a style="text-decoration:none" href="viewprofile.php?u=',$ownerid,'&id=',$id,'"><div id="photoshadowhighlight2"><img src="graphics/newsfeednewfollower.png" height="50" width="50" />&nbsp;<img src="',$profilepic4,'" height="50" width="50" />&nbsp;<span style="color:white">',$fullname4,' is now following your photography</span></div></a><br />';
}
} //end if statement

elseif($match > 0) {

if($type == "comment") {
$caption4 = $notsarray['caption'];
$source= $notsarray['source'];
$newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
echo'<a style="text-decoration:none" href="fullsize.php?image=',$source,'&id=',$id,'"><div id="photoshadow2"><img src="graphics/newsfeedcomment.png" height="50" width="50" />&nbsp;<img src="http://www.photorankr.com/',$newsource,'" height="50" width="50" />&nbsp;<span style="color:black">',$fullname4,' commented on your photo</span></div></a><br />';
}

elseif($type == "message") {
$ownermessage = $notsarray['owner'];
$thread = $notsarray['thread'];
$newaccount = "SELECT * FROM userinfo WHERE emailaddress = '$ownermessage'";
$accountresult = mysql_query($newaccount); 
$accountrow = mysql_fetch_array($accountresult);
$profilepic4 = $accountrow['profilepic'];
if($profilepic4 == "") {
$profilepic4 = "profilepics/default_profile.jpg";
}
echo'<a style="text-decoration:none" href="myprofile.php?view=viewthread&thread=',$thread,'&id=',$id,'"><div id="photoshadow2"><img src="graphics/messagesicon.png" height="50" width="50" />&nbsp;<img src="',$profilepic4,'" height="50" width="50" />&nbsp;<span style="color:black">',$fullname4,' sent you a message</span></div></a><br />';
}

elseif($type == "reply") {
$ownermessage = $notsarray['owner'];
$thread = $notsarray['thread'];
$newaccount = "SELECT * FROM userinfo WHERE emailaddress = '$ownermessage'";$accountresult = mysql_query($newaccount); 
$accountrow = mysql_fetch_array($accountresult);
$profilepic4 = $accountrow['profilepic'];
if($profilepic4 == "") {
$profilepic4 = "profilepics/default_profile.jpg";
}
echo'<a style="text-decoration:none" href="myprofile.php?view=viewthread&thread=',$thread,'&id=',$id,'"><div id="photoshadow2"><img src="graphics/messagesicon.png" height="50" width="50" />&nbsp;<img src="',$profilepic4,'" height="50" width="50" />&nbsp;<span style="color:black">',$fullname4,' replied to your message</span></div></a><br />';
}

elseif($type == "fave") {
$caption4 = $notsarray['caption'];
$source= $notsarray['source'];
$newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
echo'<a style="text-decoration:none" href="fullsize.php?image=',$source,'&id=',$id,'"><div id="photoshadow2"><img src="graphics/newsfeedfavorite.png" height="50" width="50" />&nbsp;<img src="http://www.photorankr.com/',$newsource,'" height="50" width="50" />&nbsp;<span style="color:black">',$fullname4,' favorited your photo</span></div></a><br />';
}

elseif($type == "trending") {
$caption4 = $notsarray['caption'];
$source= $notsarray['source'];
$newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
echo'<a style="text-decoration:none" href="fullsize.php?image=',$source,'&id=',$id,'"><div id="photoshadow2"><img src="graphics/newsfeedtrending.png" height="50" width="50" />&nbsp;<img src="http://www.photorankr.com/',$newsource,'" height="50" width="50" />&nbsp;<span style="color:black">Your photo is now trending</span></div></a><br />';
}

elseif($type == "follow") {
$caption4 = $notsarray['caption'];
$followeremail= $notsarray['emailaddress'];
$newaccount = "SELECT * FROM userinfo WHERE emailaddress = '$followeremail'";
$accountresult = mysql_query($newaccount); 
$accountrow = mysql_fetch_array($accountresult);
$ownerid = $accountrow['user_id'];
$profilepic4 = $accountrow['profilepic'];
if($profilepic4 == "") {
$profilepic4 = "profilepics/default_profile.jpg";
}
echo'<a style="text-decoration:none" href="viewprofile.php?u=',$ownerid,'&id=',$id,'"><div id="photoshadow2"><img src="graphics/newsfeednewfollower.png" height="50" width="50" />&nbsp;<img src="',$profilepic4,'" height="50" width="50" />&nbsp;<span style="color:black">',$fullname4,' is now following your photography</div></a><br /></span>';
}
} //end ifelse statement

} //end of for loop
echo'</div>';

}

elseif($numnots < 1) {
echo'<div style="position:relative;width:400px;height:80px;overflow-y:scroll;font-size:14px;top: 30px;">';
echo'<div style="font-size:16px;color:white;text-align:center;">You have no new notifications &#8230;</div>';
echo'</div>';
}




echo'
    </ul>
    </li>'; 
            }
     
             if($_SESSION['loggedin'] == 1) {
echo'
     <li ><a style="color:#fff;margin-top:2px;" href="newsfeed.php">News</a></li>'; }

     ?>

     
     <li class="dropdown">

						<a style="color:#fff;margin-top:2px;" href="trending.php" class="dropdown-toggle" data-toggle="dropdown">Galleries<b class="caret" style="background-color:#1a618a;
"></b></a>
						<ul class="dropdown-menu" data-dropdown="dropdown">
							<li><a style="color:#fff;" href="trending.php">Trending</a></li>
							<li><a style="color:#fff;" href="newest.php">Newest</a></li>
                            <li><a style="color:#fff;" href="topranked.php">Top Ranked</a></li>
                        </ul>
                </li>
                    
                    
					<li style="background-color:#587fa2;"><a style="color:#fff;margin-top:2px;" href="viewcampaigns.php">Campaigns</a></li>
                                     <li ><a style="color:#fff;margin-top:2px;" href="http://photorankr.com/blog/post">Blog</a></li>

                      <?php
                    
@session_start();

if($_SESSION['loggedin'] !== 1) {
echo'
     <li ><a style="color:#fff;margin-top:2px;" href="signup.php?action=disc">Discover</a></li>'; }

if($_SESSION['loggedin'] == 1) {

	echo '			
                <li><a style="color:#fff;margin-top:2px;" href="';
                    if($nolikes) {echo 'myprofile.php?view=editinfo&action=discover#discover';}else { echo 'discover.php?image=',$discoverimage;} echo '">Discover</a></li>
                         
                    <li class="dropdown">
						<a style="color:#fff;margin-top:2px;" href="../myprofile.php" class="dropdown-toggle" data-toggle="dropdown">My Profile<b class="caret" style="background-color:#1a618a;
"></b></a>
						<ul class="dropdown-menu" data-dropdown="dropdown">
							<li><a style="color:#fff;" href="myprofile.php">Portfolio</a></li>
                            <li><a style="color:#fff;" href="myprofile.php?view=info">Information</a></li>
							<li><a style="color:#fff;" href="myprofile.php?view=upload">Upload</a></li>
							<li><a style="color:#fff;" href="myprofile.php?view=followers">Followers</a></li>
							<li><a style="color:#fff;" href="myprofile.php?view=following">Following</a></li>
							<li><a style="color:#fff;" href="myprofile.php?view=faves">Favorites</a></li>
                            <li><a style="color:#fff;" href="myprofile.php?view=messages">Messages</a></li>
							<li><a style="color:#fff;" href="newest.php?action=logout">Log Out</a></li>
						</ul>'; 
} else {
				echo '	
                <li class="dropdown">
                <a style="color:#fff;margin-top:2px;" href="../signin.php" class="dropdown-toggle" data-toggle="dropdown">Log In<b class="caret"></b></a>
						<ul class="dropdown-menu" data-dropdown="dropdown">
							<li><a style="color:#fff;margin-left:-29px;font-size:15px;" href="signin.php">Register for free today</a></li>
							<li><br/></li>
							<form name="login_form" method="post" action="viewcampaigns.php?action=login">
							<li style="margin-left: 5px; margin-right: 5px; width: 185px;"><span style="color: white; margin-bottom: 5px;margin-left:10px;">Email: <br /></span><input type="text" style="width:150px;margin-top:3px;margin-left:10px;" name="emailaddress" /></li>
							<li><span style="color:white;margin-left:-16px;">Password: <br /></span><input type="password" style="width:150px;margin-top:3px;margin-left:-16px;" name="password"/></li>
                        <li style="margin-left: 110px;"><input type="submit" class="btn btn-success" value="Sign In" id="loginButton"/></li>
						</ul>';
} ?>
					</li>
					<form class="navbar-search" action="../search.php" method="get">
						<input type="text" style="width:150px;border-color:#fff;background-color:#fff;margin-left:20px;" class="search-query" name="searchterm" placeholder="Search">
					 </form>
					 
				</ul>
			
		</div> <!--/end boostrap divs navbar-->
        
    </div>
</div>


	<div id="container" class="container_24">
		<div class="grid_24 pull_2" style="width: 1140px;margin-top:70px;">
        
       <div class="well dropshadow" style="font-size:15px;font-family:helvetica, arial;"><img style="margin-top:-6px;" src="market/graphics/logocampaign.png" width="260" />&nbsp;&nbsp;&nbsp;&nbsp;Campaigns allow you to submit photography to businesses and individuals looking for specific photography. All campaigns are open and you may submit as many photos as you wish. To enter a campaign, click on one that interests you and click on the green upload button. When the campaign is over, a winner will be announced, and seventy percent of the prize money will be awarded to the winner.<br /><a href="viewcampaigns.php?view=winners"><div style="margin-top:10px;font-size:16px;">View Past Winners</div></a></div> 
 
        
<?php

//if the view wasn't set
if($view == "") {
	//they are viewing the ongoing campaigns

	//select all the campaigns that are still live and show the one closest to ending first
	$allcampaignsquery = "SELECT * FROM campaigns WHERE endtime > '$currenttime' ORDER BY endtime ASC";
	$allcampaignsresult = mysql_query($allcampaignsquery);

	//now group photos by their campaign which will be used later on
	$randphotoquery = "SELECT source, campaign FROM campaignphotos GROUP BY campaign";
	$randphotoresult = mysql_query($randphotoquery);

	//loop through the results to create arrays of the needed campaign info and of a photo to display
	for($iii=0; $iii < mysql_num_rows($allcampaignsresult); $iii++) {
		//find out all the info about this campaign
		$endtime           = mysql_result($allcampaignsresult, $iii, "endtime");
		$quote[$iii]       = mysql_result($allcampaignsresult, $iii, "quote");
		$title[$iii]       = mysql_result($allcampaignsresult, $iii, "title");
		$description[$iii] = mysql_result($allcampaignsresult, $iii, "description");
		$photoid[$iii]          = mysql_result($allcampaignsresult, $iii, "id");
		$timeleft          = $endtime - time();
		//find out how many days hours minutes are left
		$daysleft          = floor($timeleft / (24*60*60));
		$timeleft          -= 24*60*60*$daysleft;
    	$hoursleft         = floor($timeleft / (60*60));
		$timeleft          -= 60*60*$hoursleft;
		$minutesleft       = floor($timeleft / 60);

		//find the photo in $randphotoresult where the campaign id matches
		for($jjj=0; $jjj < mysql_num_rows($randphotoresult); $jjj++) {
			//if the current photo matches
			if(mysql_result($randphotoresult, $jjj, "campaign") == $photoid[$iii]) {
				//then it is the photo we want
				$coverphoto[$iii] = mysql_result($randphotoresult, $jjj, "source");
                $findme   = 'photorankr.com';
                $pos = strpos($coverphoto[$iii], $findme);
                if($pos !== false) {
                $coverphoto[$iii] = str_replace("userphotos/","userphotos/", $coverphoto[$iii]);
            }
        else{
        $coverphoto[$iii] = str_replace("userphotos/","market/userphotos/", $coverphoto[$iii]);
    }
			}
		}
    $coverphoto[$iii] = str_replace("userphotos/","userphotos/medthumbs/", $coverphoto[$iii]);

		list($width, $height) = getimagesize("market/" . $coverphoto[$iii]);
		$imgratio = $height / $width;
    	$heightls = $height / 2.5;
    	$widthls = $width / 2.5;

    	//if there aren't any photos in the campaign at all, set it to the default
		if($coverphoto[$iii] == "") {
			$coverphoto[$iii] = "graphics/nophotopost.png";
			$heightls = 280;
			$widthls = 280;
		}

		echo '
		<div class="phototitle fPic" id="',$photoid[$iii],'" style="width:280px;height:280px;overflow:hidden; margin-right: 30px;">
			<a href="campaignphotos.php?id=',$photoid[$iii],'">
        		<div class="statoverlay" style="z-index:1;left:0px;top:200px;position:relative;background-color:black;width:280px;height:80px;"><p style="line-spacing:1.48;padding:5px;color:white;">"',$title[$iii],'"<br />Reward: $', $quote[$iii], '<br />Time Left: ', $daysleft, ' days, ', $hoursleft, ' hours, ', $minutesleft, ' minutes</p></div>
        		<img style="position:relative;top:-95px;min-height:300px;min-width:280px;" src="', $coverphoto[$iii], '" height="',$heightls,'px" width="',$widthls,'px" />
        	</a>
        </div>';
	}

	//now display all the campaigns with the variables created above with a link to campaignphotos.php?id=id[$iii]
}
//otherwise they are looking at the past campaigns

elseif($view == winners) {

echo'
<div style="float:left;font-size:22px;margin-bottom:15px;">Past Campaign Winners:</div><br /><br />';

$winquery = mysql_query("SELECT * FROM campaigns WHERE winnerphoto != '' ORDER BY id ASC");
$numwins = mysql_num_rows($winquery);

for($iii=0;$iii < $numwins; $iii++) {
$campaign = mysql_result($winquery,$iii,'title');
$id = mysql_result($winquery,$iii,'id');
$winnerphoto = mysql_result($winquery,$iii,'winnerphoto');
$winneremail = mysql_result($winquery,$iii,'winneremail');

$coverquery = mysql_query("SELECT source,caption FROM campaignphotos WHERE id = '$winnerphoto'");
$coverphoto = mysql_result($coverquery,0,'source');
$winningphotocaption = mysql_result($coverquery,0,'caption');
$coverphoto = str_replace("userphotos/","market/userphotos/medthumbs/", $coverphoto);

$profquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$winneremail'");
$num = mysql_num_rows($profquery);
$profilephoto = mysql_result($profquery,0,'profilepic');
$firstname= mysql_result($profquery,0,'firstname');
$lastname = mysql_result($profquery,0,'lastname');
$winnername = $firstname . " " . $lastname;
$winnerid = mysql_result($profquery,0,'user_id');

echo'<div class="phototitle well" style="width:860px;font-size:17px;font-family:helvetica neue, arial;">
<img class="dropshadow" style="border:1px solid black;" src="',$coverphoto,'" height="170" width="170" />
<div style="margin-left:20px;float:right;width:650px;height:170px;">
Campaign: <span style="font-size:22px;">"<a style="color:black;" href="campaignphotos.php?id=',$id,'">',$campaign,'</a>"</span><br /><br />
Winner: &nbsp;<img class="dropshadow" style="border: 1px solid black;" src="',$profilephoto,'" width="60" height="60" />&nbsp;&nbsp;<a style="color:black;" href="viewprofile.php?u=',$winnerid,'"><span style="font-size:15px;">',$winnername,'</span></a><br /><br />
Winning Photo: <span style="font-size:15px;">"<a style="color:black;" href="fullsizecampaign.php?id=',$winnerphoto,'">',$winningphotocaption,'</a>"</span><br />

</div>
</div>';

}

}

else {

	//select all the campaigns that are still live and show the one closest to ending first
	$allcampaignsquery = "SELECT * FROM campaigns WHERE endtime <= '$currenttime' ORDER BY endtime DESC LIMIT 16";
	$allcampaignsresult = mysql_query($allcampaignsquery);

	//now group photos by their campaign which will be used later on
	$randphotoquery = "SELECT source, campaign FROM campaignphotos GROUP BY campaign";
	$randphotoresult = mysql_query($randphotoquery);

	//loop through the results to create arrays of the needed campaign info and of a photo to display
	for($iii=0; $iii < mysql_num_rows($allcampaignsresult); $iii++) {
		//find out all the info about this campaign
		$quote[$iii]       = mysql_result($allcampaignsresult, $iii, "quote");
		$title[$iii]       = mysql_result($allcampaignsresult, $iii, "title");
		$description[$iii] = mysql_result($allcampaignsresult, $iii, "description");
		$photoid[$iii]          = mysql_result($allcampaignsresult, $iii, "id");
		$coverphotoid[$iii]  = mysql_result($allcampaignsresult, $iii, "winnerphoto");
        $coverquery = mysql_query("SELECT source FROM campaignphotos WHERE id = '$coverphotoid[$iii]'");
        $coverphoto[$iii] = mysql_result($coverquery, 0, "source");
		$winner            = mysql_result($allcampaignsresult, $iii, "winneremail");

		//if a winner hasn't been selected yet for this campaign
		if($coverphoto[$iii] == "") {
			//find the photo in $randphotoresult where the campaign id matches
			for($jjj=0; $jjj < mysql_num_rows($randphotoresult); $jjj++) {
				//if the current photo matches
				if(mysql_result($randphotoresult, $jjj, "campaign") == $photoid[$iii]) {
					//then it is the photo we want
					$coverphoto[$iii] = mysql_result($randphotoresult, $jjj, "source");
				}
			}
		}	
		//otherwise, a winner has been selected so find out their first and last name
		else {
			//add this person to the winners emailaddress list
			$winneremaillist .= $winner;
			$winneremaillist .= "',";
		}	
    $coverphoto[$iii] = str_replace("userphotos/","userphotos/medthumbs/", $coverphoto[$iii]);

		list($width, $height) = getimagesize($coverphoto[$iii]);
		$imgratio = $height / $width;
    	$heightls = $height / 2.5;
    	$widthls = $width / 2.5;

    	//if there aren't any photos in the campaign at all, set it to the default
		if($coverphoto[$iii] == "") {
			$coverphoto[$iii] = "graphics/nophotopost.png";
			$heightls = 280;
			$widthls = 280;
		}

		echo '
		<div class="phototitle fPic" id="',$photoid[$iii],'" style="width:280px;height:280px;overflow:hidden;">
			<a href="campaignphotos.php?id=',$photoid[$iii],'">
        		<div class="statoverlay" style="z-index:1;left:0px;top:200px;position:relative;background-color:black;width:280px;height:80px;"><p style="line-spacing:1.48;padding:5px;color:white;">"',$title[$iii],'"<br />Reward: $', $quote[$iii], '<br />This campaign is over.</p></div>
        		<img style="position:relative;top:-95px;min-height:300px;min-width:280px;" src="market/', $coverphoto[$iii], '" height="',$heightls,'px" width="',$widthls,'px" />
        	</a>
        </div>';
	}

	//take the trailing comma off of the $winneremaillist
	$winneremaillist = substr($winneremaillist, 0, -1);

	//select all of the photographers in the list
	$photographerquery = "SELECT firstname, lastname, emailaddress FROM userinfo WHERE emailaddress IN($winneremaillist) LIMIT 16";
	$photographerresult = mysql_query($photographerquery);

	//loop through all of the photos to match the emailaddresses together
	for($iii=0; $iii < mysql_num_rows($allcampaignsresult); $iii++) {
		//loop through those photographers
		for($jjj=0; $jjj < mysql_num_rows($photographerresult); $jjj++) {
			//if the current emailaddresses are a match
			if(mysql_result($allcampaignsresult, $iii, "winneremail") == mysql_result($photographerresult, $jjj, "emailaddress")) {
				//set the first and last name variables
				$winnerfirst[$iii] = mysql_result($photographerresult, $jjj, "firstname");
				$winnerlast[$iii] = mysql_result($photographerresult, $jjj, "lastname");
			}
		}	
	}
}

?>
<div class="grid_3" style="position:fixed;right:100px;">
<div id="accordion2" class="accordion" style="margin-top:20px;width:150px;">

<div class="accordion-group">
<div class="accordion-heading">
<a class="accordion-toggle" style="background-color:#1a618a;color:white;" href="viewcampaigns.php">Current</a>
</div>
<div id="collapseOne" class="accordion-body collapse">
</div>
</div>

<div class="accordion-group">
<div class="accordion-heading">
<a class="accordion-toggle" style="background-color:#1a618a;color:white;" href="viewcampaigns.php?view=previous">Previous</a>
</div>
<div id="collapseTwo" class="accordion-body collapse">
</div>
</div>


</div>
</div>


<!--Footer begin-->   
<div class="grid_24" style="height:30px;margin-top:30px;background-color:rgb:(238,239,243);text-align:center;padding-top:10px;padding-bottom:20px; background-color:none;text-decoration:none;">
<p style="text-decoration:none;">
</br></br>
Copyright&nbsp;&copy;&nbsp;2012&nbsp;PhotoRankr, Inc.&nbsp;&nbsp;
<a href="http://photorankr.com/about.php">About</a>&nbsp;&nbsp;                                       
<a href="http://photorankr.com/terms.php">Terms</a>&nbsp;&nbsp;
<a href="http://photorankr.com/privacy.php">Privacy</a>&nbsp;&nbsp;
<a href="http://photorankr.com/help.php">Help<a>&nbsp;&nbsp;
<a href="http://photorankr.com/contact.php">Contact&nbsp;Us<a>
<br />
<br />
</p>                   
</div>
<!--Footer end-->


</body>
</html>