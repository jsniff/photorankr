<?php

//connect to the database
require "db_connection.php";

session_start();
if($_GET['action'] == "logout") {
	$_SESSION['loggedin'] = 0;
	session_destroy();
}

$query="SELECT * FROM photos ORDER BY points DESC LIMIT 0, 20";
$result=mysql_query($query);
$numberofpics=mysql_num_rows($result);


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

//QUERY FOR NOTIFICATIONS
$currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$email'";
$currentnotsquery = mysql_query($currentnots);
$currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");

//notifications query reset 
if($currentnotsresult > 0) {
$notsquery = "UPDATE userinfo SET notifications = (notifications - 1) WHERE emailaddress = '$email6'";
$notsqueryrun = mysql_query($notsquery); }

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


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
     <meta name="viewport" content="width=1200" /> 
 <meta name="Generator" content="EditPlus">
  <meta name="Author" content="PhotoRankr, PhotoRankr.com">
  <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
  <meta name="Description" content="PhotoRankr allows photographers of all skill levels to sell and share their work. Create your photostream cutomized to what you want to see. Add photos to your favorites, rank them, and watch them trend. Build your portfolio with Photorankr.">

<script language="JavaScript">
var x=<?php echo $x; ?>;

function slideshowForward() {
x=x+20;
location.href="newest.php?x="+x;
}

function slideshowBackward() {
x=x-20;
location.href="newest.php?x="+x;
}

</script>
<link rel="stylesheet" href="lightbox.css" type="text/css" media="screen" />

<link rel="stylesheet" type="text/css" href="bootstrapnew.css" />
 <link rel="stylesheet" href="reset.css" type="text/css" />
  <link rel="stylesheet" href="text.css" type="text/css" />
  <link rel="stylesheet" href="960_24.css" type="text/css" />
  <script type="text/javascript" src="jquery.js"></script>   
<script src="bootstrap.js" type="text/javascript"></script>

<link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>

<title>Top Ranked</title>

<style type="text/css">

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

<body oncontextmenu="return false;"  style="overflow-x:hidden; background-color: #eeeff3">


<!--NAVIGATION BAR-->
<div class="navbar" style="padding-top:0px;min-width:1220px;z-index:10;font-size:16px;width:100%;">
	<div class="navbar-inner">
		<div class="container">
			    <ul class="nav">
					<li><a class="brand" href="index.php"><div style="margin-top:-2px"><img src="logo.png" width="160" /></div></a></li>
                    
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


                    
					 <li style="background-color:#587fa2;" class="dropdown">
                    <a style="color:#fff;margin-top:2px;" href="newest.php" class="dropdown-toggle" data-toggle="dropdown">Galleries<b class="caret" style="background-color:#1a618a;
"></b></a>
						<ul class="dropdown-menu" data-dropdown="dropdown">
							<li ><a style="color:#fff;margin-top:2px;" href="trending.php">Trending</a></li>
                            <li><a style="color:#fff;margin-top:2px;" href="newest.php">Newest</a></li>
                            <li><a style="color:#fff;margin-top:2px;" href="topranked.php">Top Ranked</a></li>
                        </ul>
                </li>
                
                <li ><a style="color:#fff;margin-top:2px;" href="viewcampaigns.php">Campaigns</a></li>
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

						<a style="color:#fff;margin-top:2px;" style="color:rgb(56,85,103);margin-right:20px;" href="myprofile.php" class="dropdown-toggle" data-toggle="dropdown">My Profile<b class="caret"></b></a>
						<ul class="dropdown-menu" data-dropdown="dropdown">
							<li><a style="color:#fff;" href="myprofile.php">Portfolio</a></li>
                            <li><a style="color:#fff;" href="myprofile.php?view=info">Information</a></li>
							<li><a style="color:#fff;" href="myprofile.php?view=upload">Upload</a></li>
							<li><a style="color:#fff;" href="myprofile.php?view=followers">Followers</a></li>
							<li><a style="color:#fff;" href="myprofile.php?view=following">Following</a></li>
							<li><a style="color:#fff;" href="myprofile.php?view=faves">Favorites</a></li>
                            <li><a style="color:#fff;" href="myprofile.php?view=messages">Messages</a></li>
							<li><a style="color:#fff;" href="newest.php?action=logout">Log Out</a></li>

						</ul>
                        
                        
                        '; 
} else {
				echo '	
                                    <li class="dropdown">

                <a style="color:#fff;margin-top:2px;" href="signin.php" class="dropdown-toggle" data-toggle="dropdown">Log In<b class="caret"></b></a>
						<ul class="dropdown-menu" data-dropdown="dropdown">
							<li><a style="color:#fff;margin-left:-29px;font-size:15px;" href="signin.php">Register for free today</a></li>
							<li><br/></li>
							<form name="login_form" method="post" action="topranked.php?action=login">
							<li style="margin-left: 5px; margin-right: 5px; width: 185px;"><span style="color: white; margin-bottom: 5px;margin-left:10px;">Email: <br /></span><input type="text" style="width:150px;margin-top:3px;margin-left:10px;" name="emailaddress" /></li>
							<li><span style="color:white;margin-left:-16px;">Password: <br /></span><input type="password" style="width:150px;margin-top:3px;margin-left:-16px;" name="password"/></li>
                        <li style="margin-left: 110px;"><input type="submit" class="btn btn-success" value="Sign In" id="loginButton"/></li>
                        </form>
						</ul>';
} ?>
					</li>
					<form class="navbar-search" action="search.php" method="get">
						<input type="text" style="width:150px;border-color:#fff;background-color:#fff;margin-left:20px;" class="search-query" name="searchterm" placeholder="Search">
					 </form>
					 
				</ul>
			
		</div> <!--/end boostrap divs navbar-->
    </div>

</div>


<!--TOP PHOTOGRAPHY-->

<?php

//GET VIEW
if(isset($_GET['v'])){
		$view = $_GET['v'];
	}
    
//Big Container
echo'<div id="container" class="container_24">

//ACCORDION NAVIGATION SYSTEM

<div class="grid_3" style="position:fixed;margin-left:740px;">';

if($_SESSION['loggedin'] == 1) {

$profilequery = mysql_query("SELECT profilepic,firstname,lastname FROM userinfo WHERE emailaddress = '$email'");
$profilepic=mysql_result($profilequery, 0, "profilepic");
$firstname=mysql_result($profilequery, 0, "firstname");
$lastname=mysql_result($profilequery, 0, "lastname");
$fullname = $firstname . " " . $lastname;
$shortname = (strlen($fullname) > 19) ? substr($fullname,0,19) : $fullname;

$followingquery = mysql_query("SELECT following FROM userinfo WHERE emailaddress = '$email'");
$followinglist=mysql_result($followingquery, 0, "following");
$followingquery=mysql_query("SELECT * FROM userinfo WHERE emailaddress IN ($followinglist)");
$numfollowing = mysql_num_rows($followingquery);    
$followersquery=mysql_query("SELECT * FROM userinfo WHERE following LIKE '%$email%'");
$numfollowers = mysql_num_rows($followersquery);

echo'
<div id="accordion2" class="accordion" style="margin-top:60px;width:150px;"><a href="myprofile.php">
<img class="dropshadow" style="border: 2px solid white;margin-top:5px;" src="',$profilepic,'" height="140" width="145" /></a><div style="font-size:14px;text-align:center;margin-top:5px;">',$shortname,'<br /><span style="font-size:13px;">',$numfollowers,' <i class="icon-user"> </i> <a style="color:black;" href="myprofile.php?view=followers">Followers</a><br />',$numfollowing,' <i class="icon-user"> </i> <a style="color:black;" href="myprofile.php?view=following">Following</a></span></div>';

}


else {
echo'
<div id="accordion2" class="accordion" style="margin-top:60px;width:150px;">
';
}

echo'


<div class="accordion-group">
<div class="accordion-heading">
<a style="background-color:#1a618a;color:white;"  class="accordion-toggle" href="#collapseOne" data-parent="#accordion2" data-toggle="collapse">Photography </a>
</div>
<div id="collapseOne" class="accordion-body collapse">
<div style="background-color:#1a618a;color:white;"  class="accordion-inner"> 
<a style="background-color:#1a618a;color:white;"  href="topranked.php">All Time</a>
<br />
<a style="background-color:#1a618a;color:white;"  href="topranked.php?t=m">Month</a>
<br />
<a  style="background-color:#1a618a;color:white;"  href="topranked.php?t=w">Week</a>
<br />
</div>
</div>
</div>

<div class="accordion-group">
<div class="accordion-heading">
<a style="background-color:#1a618a;color:white;"  class="accordion-toggle" href="topranked.php?v=prs">Photographers </a>
</div>
<div id="collapseTwo" class="accordion-body collapse">
</div>
</div>

<div class="accordion-group">
<div class="accordion-heading">
<a style="background-color:#1a618a;color:white;"  class="accordion-toggle" href="topranked.php?v=ex">Exhibits </a>
</div>
<div id="collapseTwo" class="accordion-body collapse">
</div>
</div>

</div>
</div>';
    
    
if ($view=='') {


//DECIDE WHAT TIME VIEW THEY ARE ON
if(isset($_GET['t'])){
		$timesetting = $_GET['t'];
	}



echo'<div style="position:relative;top:0px;font-size:15px;">';

//Time setting is set to all time
if ($timesetting == '') {

$query="SELECT * FROM photos ORDER BY points DESC LIMIT 0, 21";
$result=mysql_query($query);
$numberofpics=mysql_num_rows($result);

echo'<div id="container" style="width:1140px;position:relative;left:-120px;top:40px;">';
    for($iii=1; $iii <= 20; $iii++) {
$image = mysql_result($result, $iii-1, "source");
$imageThumb = str_replace("userphotos/","userphotos/medthumbs/", $image);
$caption = mysql_result($result, $iii-1, "caption");
$emailaddress = mysql_result($result, $iii-1, "emailaddress");
$namequery="SELECT * FROM userinfo WHERE emailaddress='$emailaddress'";
$nameresult=mysql_query($namequery);
$row=mysql_fetch_array($nameresult);
$firstname=$row['firstname'];
$lastname=$row['lastname'];
$fullname = $firstname . " " . $lastname;
$fullname = ucwords($fullname);

list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
    $heightls = $height / 3.5;
    $widthls = $width / 3.5;

        echo'
		<div class="phototitle" style="width:240px;height:330px;overflow:hidden;background-color:white;">
			<a href="fullsize.php?image=',$image,'&v=r"><img onmousedown="return false" oncontextmenu="return false;" style="min-height:240px;max-height:240px;min-width:240px;" src="http://www.photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" /></a><br /><span style="font-size:21px">&nbsp;#',$iii,'.</span><div style="margin-top:0px;font-size:15px;text-align:center;">"',$caption,'"<br />By: ',$fullname,'</div></div>'; 
        
    } //end of for loop
echo'</div>';
    
    
} //end of all time if clause    
    
//Time setting is set to month    
elseif ($timesetting == 'm') {

$lowertimebound = time() - 2419900;
$query="SELECT * FROM photos WHERE time > '$lowertimebound' ORDER BY points DESC LIMIT 0, 21";
$result=mysql_query($query);
$numberofpics=mysql_num_rows($result);

echo'<div id="container" style="width:1140px;position:relative;left:-120px;top:40px;">';
    for($iii=1; $iii <= 20; $iii++) {
$image = mysql_result($result, $iii-1, "source");
$imageThumb = str_replace("userphotos/","userphotos/medthumbs/", $image);
$caption = mysql_result($result, $iii-1, "caption");
$emailaddress = mysql_result($result, $iii-1, "emailaddress");
$namequery="SELECT * FROM userinfo WHERE emailaddress='$emailaddress'";
$nameresult=mysql_query($namequery);
$row=mysql_fetch_array($nameresult);
$firstname=$row['firstname'];
$lastname=$row['lastname'];
$fullname = $firstname . " " . $lastname;
$fullname = ucwords($fullname);

list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
    $heightls = $height / 3.5;
    $widthls = $width / 3.5;

        echo'
		<div class="phototitle" style="width:240px;height:330px;overflow:hidden;background-color:white;">
			<a href="fullsize.php?image=',$image,'&v=r"><img onmousedown="return false" oncontextmenu="return false;" style="min-height:240px;max-height:240px;min-width:240px;" src="http://www.photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" /></a><br /><span style="font-size:21px">&nbsp;#',$iii,'.</span><div style="margin-top:0px;font-size:15px;text-align:center;">"',$caption,'"<br />By: ',$fullname,'</div></div>'; 
        
    } //end of for loop
echo'</div>';

} //end of month if clause

//Time setting is set to week
elseif ($timesetting == 'w') {

$lowertimebound = time() - 604800;
$query="SELECT * FROM photos WHERE time > '$lowertimebound' ORDER BY points DESC LIMIT 0, 21";
$result=mysql_query($query);
$numberofpics=mysql_num_rows($result);

echo'<div id="container" style="width:1140px;position:relative;left:-120px;top:40px;">';
    for($iii=1; $iii <= 20; $iii++) {
$image = mysql_result($result, $iii-1, "source");
$imageThumb = str_replace("userphotos/","userphotos/medthumbs/", $image);
$caption = mysql_result($result, $iii-1, "caption");
$emailaddress = mysql_result($result, $iii-1, "emailaddress");
$namequery="SELECT * FROM userinfo WHERE emailaddress='$emailaddress'";
$nameresult=mysql_query($namequery);
$row=mysql_fetch_array($nameresult);
$firstname=$row['firstname'];
$lastname=$row['lastname'];
$fullname = $firstname . " " . $lastname;
$fullname = ucwords($fullname);

list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
    $heightls = $height / 3.5;
    $widthls = $width / 3.5;

        echo'
		<div class="phototitle" style="width:240px;height:330px;overflow:hidden;background-color:white;">
			<a href="fullsize.php?image=',$image,'&v=r"><img onmousedown="return false" oncontextmenu="return false;" style="min-height:240px;max-height:240px;min-width:240px;" src="http://www.photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" /></a><br /><span style="font-size:21px">&nbsp;#',$iii,'.</span><div style="margin-top:0px;font-size:15px;text-align:center;">"',$caption,'"<br />By: ',$fullname,'</div></div>'; 
        
    } //end of for loop
echo'</div>';

} //end of week if clause
    
      echo'</div>';
        
} //end of if clause



elseif ($view=='prs') {
//TOP 20 PHOTOGRAPHERS



//get number of photographers with score greater than 700
$query = "SELECT * FROM userinfo WHERE totalscore > 700 AND emailaddress NOT IN ('msniff16@gmail.com','sniff06@aol.com')";
$queryresult = mysql_query($query);
$numresult = mysql_num_rows($queryresult);


//nested for loop get photos from an individual user
for($iii=0; $iii < $numresult; $iii++) {
$owner = mysql_result($queryresult, $iii, "emailaddress");
$tpoints = mysql_result($queryresult, $iii, "totalpoints");
$photocheck = "SELECT * FROM photos WHERE emailaddress = '$owner' ORDER BY (points/votes) DESC";
$photocheckrun = mysql_query($photocheck);
$numphotos = mysql_num_rows($photocheckrun);

//select and calculate score for users with number of photos greater than 16
for($ii=0; $ii < 15; $ii++) {
$singlescore = mysql_result($photocheckrun, $ii, "points");
$votes = mysql_result($photocheckrun, $ii, "votes");
$totalpoints += $singlescore;
$totalvotes += $votes;
    }
    
    $finalaverage = ($totalpoints/$totalvotes);
    
    $averagearray[$iii] =  $finalaverage;
    $emailaddressarray[$iii] = $owner;

} 

//end of for where totalscore > 700

for($i = 0; $i < sizeof($averagearray); $i++){
array_multisort($averagearray,$emailaddressarray);
}


echo'<div id="container" style="width:1140px;position:relative;left:-120px;top:40px;">';
    for($iii=1; $iii <= 20; $iii++) {
    $newquery = "SELECT * FROM userinfo WHERE emailaddress = '$emailaddressarray[$iii]'";
$firstname = mysql_result($queryresult, $iii-1, "firstname");
$user_id = mysql_result($queryresult, $iii-1, "user_id");
$lastname = mysql_result($queryresult, $iii-1, "lastname");
$fullname = $firstname . " " . $lastname;
$fullname = ucwords($fullname);
$profilepic = mysql_result($queryresult, $iii-1, "profilepic");
if($profilepic == 'http://www.photorankr.com/profilepics/default_profile.jpg'){
$profilepic = 'profilepics/default_profile.jpg';
}

echo'
		<div class="phototitle" style="width:240px;height:330px;overflow:hidden;background-color:white;">
			<a href="viewprofile.php?u=',$user_id,'"><img onmousedown="return false" oncontextmenu="return false;" src="http://www.photorankr.com/',$profilepic,'" height="240px" width="240px" /></a><br /><span style="font-size:21px">&nbsp;#',$iii,'.</span><div style="margin-top:0px;font-size:15px;text-align:center;">',$fullname,'</div></div>'; 
        
    } //end of for loop
echo'</div>'; 
        
} //end of elseif clause


elseif ($view=='ex') {

echo'<div id="container" style="width:1140px;position:relative;left:-120px;top:40px;">';
    for($iii=1; $count < 20; $iii++) {
    $exquery = "SELECT * FROM sets ORDER BY avgscore DESC";
    $exqueryrun = mysql_query($exquery);
    $owner = mysql_result($exqueryrun, $iii-1, "owner");

$exinfo = "SELECT * FROM userinfo WHERE emailaddress = '$owner'"; 
$exinforun = mysql_query($exinfo);
$firstname = mysql_result($exinforun, 0, "firstname");
$lastname = mysql_result($exinforun, 0, "lastname");
$fullname = $firstname . " " . $lastname;
$fullname = ucwords($fullname);
$user_id = mysql_result($exinforun, 0, "user_id");
$exhibit_id = mysql_result($exqueryrun, $iii-1, "id");
$caption = mysql_result($exqueryrun, $iii-1, "title");
$caption = (strlen($caption) > 28) ? substr($caption,0,28). " &#8230;" : $caption;
$coverpic = mysql_result($exqueryrun, $iii-1, "cover");
if($coverpic == '') {
    continue;
    }
   $count += 1; 
echo'
		<div class="phototitle" style="width:240px;height:330px;overflow:hidden;background-color:white;">
			<a href="viewprofile.php?u=',$user_id,'&ex=y&set=',$exhibit_id,'"><img onmousedown="return false" oncontextmenu="return false;" src="',$coverpic,'" height="240px" width="240px" /></a><br /><span style="font-size:21px">&nbsp;#',$count,'.</span><div style="margin-top:0px;font-size:15px;text-align:center;">"',$caption,'"<br />By: ',$fullname,'</div>
</div>'; 
        
    } //end of for loop
echo'</div>'; 

        
} //end of elseif clause


?>


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