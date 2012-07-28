<?php
//log them out if they try to logout
session_start();

if($_GET['action'] == logout) {
	$_SESSION['loggedin'] = 0;
	session_destroy();
}

//connect to the database
require "db_connection.php";


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



<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
   <meta name="viewport" content="width=1200" /> 

<meta name="Generator" content="EditPlus">
  <meta name="Author" content="PhotoRankr, PhotoRankr.com">
  <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
  <meta name="Description" content="PhotoRankr allows photographers of all skill levels to sell and share their work. Create your photostream cutomized to what you want to see. Add photos to your favorites, rank them, and watch them trend. Build your portfolio with Photorankr.">

<title>Terms and Conditions of Use</title>
<link rel="stylesheet" href="reset.css" type="text/css" />
<link rel="stylesheet" href="text.css" type="text/css" />
<link rel="stylesheet" href="960.css" type="text/css" />
 <link rel="stylesheet" type="text/css" href="bootstrapnew.css" />
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


<body style="overflow-x:hidden; background-color: rgb(250, 250, 250)">




<!--SET PAGE WIDTH AUTOMATICALLY AND ADD LOGO AND SLOGAN-->


<!--NAVIGATION BAR-->
<div class="navbar" style="z-index:10;min-width:1220px;min-width:1100px;padding-top:0px;font-size:16px;width:100%;">
	<div class="navbar-inner">
		<div class="container">
			    <ul class="nav">
					<li><a style="color:#fff;" class="brand" href="index.php"><div style="margin-top:-2px"><img src="logo.png" width="160" /></div></a></li>
                    
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

//DECIDE WHICH NOTIFICATIONS TO WHITEN (ONES ALREADY CLICKED ON)
$unhighlightquery = "SELECT * FROM userinfo WHERE emailaddress = '$email7'";
$unhighlightqueryrun = mysql_query($unhighlightquery);
$whitenlist=mysql_result($unhighlightqueryrun, 0, "unhighlight");


if($numnots > 1) {
echo'<div style="width:500px;height:400px;overflow-y:scroll;font-size:14px;">';

for ($iii=1; $iii <= 20; $iii++) {
$notsarray = mysql_fetch_array($notsresult);
$firstname4 = $notsarray['firstname'];
$lastname4 = $notsarray['lastname'];
$fullname4 = $firstname4 . " " . $lastname4;
$fullname4 = ucwords($fullname4);
$type = $notsarray['type'];
$id = $notsarray['id'];

//SEARCH IF ID IS IN UNHIGHLIGHT LIST
$search_string = $whitenlist;
$regex = $id;
$match=strpos($whitenlist,$regex);

if($match < 1) {
if($type == "comment") {
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
  
  echo'
  <li><a style="color:#fff;margin-top:2px;" href="';
                    if($nolikes) {echo 'myprofile.php?view=editinfo&action=discover#discover';}else { echo 'discover.php?image=',$discoverimage;} echo '">Discover</a></li>';
                    
@session_start();
if($_SESSION['loggedin'] == 1) {

	echo '			
                   
                    <li class="dropdown">                    
                    
						<a style="color:#fff;margin-top:2px;" href="myprofile.php" class="dropdown-toggle" data-toggle="dropdown">My Profile<b class="caret" style="background-color:#1a618a;
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

                <a style="color:#fff;margin-top:2px;" href="signin.php" class="dropdown-toggle" data-toggle="dropdown">Log In<b class="caret"></b></a>
						<ul class="dropdown-menu" data-dropdown="dropdown">
							<li><a style="color:#fff;font-size:15px;margin-left:-29px;" href="signin.php">Register for free today</a></li>
							<li><br/></li>
							<form name="login_form" method="post" action="newest.php?action=login">
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

<!--big container-->
    <div id="container" class="container_12" >

<!--CAUTION! LEGAL TERMS AND CONDITIONS OF USE.  DO NOT EDIT WITHOUT TYLER'S PERMISSION-->	
	
<div class="grid_12 pull_1" style="width:1000px;height:100px;border-bottom:2px solid black;margin-top:100px">
<p style="font-size:13px;line-height:1.48;text-align:center;font-family:"Helvetica Neue",Helvetica,sans-serif;">
<span style="font-size:24px;"><b>Terms and Conditions of Use</span></b><br />
PhotoRankr.com<br />Date of Last Revision: June 9, 2012<br />
<br />
</p>
<p style="font-size:13px;line-height:1.48;font-family:"Helvetica Neue", Helvetica, sans-serif;">
<b>PLEASE READ THESE TERMS AND CONDITIONS OF USE (&#34;TERMS OF USE&#34;) CAREFULLY.  BY ACCESSING OR USING THIS WEB SITE, YOU AGREE TO BE BOUND BY THESE TERMS OF USE.</b><br />
<br />Welcome to PhotoRankr.com.  PhotoRankr is a social photography community and marketplace.  The following document outlines the Terms of Use (&#34;Terms&#34;) of the PhotoRankr website.  These Terms apply to your access to, and use of, the Site, your use of any digital photography, media, other services provided on or through PhotoRankr.com, as well as to your purchase of any photographic prints or digital image downloads.  Before using any of the PhotoRankr services, you are required to read, understand and agree to these terms.  You may only create an account after reading and accepting these terms.  These Terms of Use do not alter in any way the terms or conditions of any other agreement you may have with PhotoRankr.  You should frequently review these Terms of Use and any other applicable policies or guidelines on the Site.  The column on the right provides a summary explanation of individual provisions of the terms of use and is not legally binding.
<br />
<br />
</p>
</div>

<div class="grid_5 pull_1" style="border-right:2px solid black;width:750px;height:7325px;margin-top:200px">
<p style="padding-right:50px;font-size:13px;line-height:1.48;font-family:"Helvetica Neue", Helvetica, sans-serif;">
<b>I. GENERAL TERMS</b><br />
<br /><b>A.  Acceptance Of Terms</b>
<br />The web pages available at PhotoRankr.com and all linked pages (&#34;Site&#34;), are owned and operated by PHOTORANKR, INC. (&#34;PhotoRankr&#34;), a Delaware corporation, and are accessed by you under the Terms of Use described below.<br />
<br />Please thoroughly and carefully read these terms before using the services provided by PhotorRankr, Inc. and the Site.  By accessing the site, viewing any content or using any services available on the site (as each is defined below) you are agreeing to be bound by these terms that govern our relationship with you in relation to the site.  If you disagree with any part of the terms, then you may not access the site. <br />
<br />PhotoRankr reserves the right, at its sole discretion, to modify or replace the Terms of Use, and any other terms, policy, or guideline governing your use of the Site at any time.  Such changes will be effective immediately upon posting such revisions on the Site.  If the alterations constitute a material change to the terms, PhotoRankr will notify you either by posting an announcement on the site, or contacting you at the e-mail address linked to your account at the time of the modification or replacement.  PhotoRankr has sole discretion to determine what constitutes a material change.  You shall be responsible for reviewing and becoming familiar with any such modifications.  If you disagree with any modifications to the terms, it is your sole responsibility to immediately discontinue use of the site.  Using any service or viewing any content following notification of a material change to the terms shall constitute your acceptance of the Terms, as modified.  <br />
<br /><b>B.  Description Of Service</b>
<br />The Site is an online social photography community and marketplace that enables photographers to post photographs, rank each others&#39 photographs, provide feedback on each others&#39 photographs through comments, opinions, and other social media features, promote their work, participate in contests and promotions, and access and/or purchase other services from time to time made available on the Site (collectively, &#34;Services&#34;).  Services include, but are not limited to, any service and/or Content PhotoRankr makes available to or performs for you, as well as the offering of any materials displayed, transmitted, or performed on the Site or through the Services.  Content (&#34;Content&#34;) includes, but is not limited to, photographs posted by users, user comments, messages, text, information, data, graphics, news articles, images, illustrations, and software.<br />
<br />Your access to, and use, of the Site may be interrupted from time to time as a result of equipment malfunction, updating, maintenance, or repair of the Site, or any other reason within or outside the control of PhotoRankr.  PhotoRankr reserves the right to suspend or discontinue the availability of the Site and/or any Service and/or remove any Content at any time at its sole discretion and without prior notice.  PhotoRankr may also impose limits on certain features and Services or restrict your access to parts of, or all of, the Site and the Services without notice or liability.  You should not sue or rely upon the Site for storage of your photographs and images, and you are directed to retain your own copies of all Content posted on the Site.<br />
<br /><b>C.  Registration and Your Account</b>
<br />As a condition to using Services, you are required to open an account with PhotoRankr, provide your name and a valid e-mail address, select a password, and to provide other registration information (&#34;Registration Data&#34;).  The Registration Data you provide must be accurate, complete, and current at all times, as PhotoRankr may send important notices about your account or the Services to the e-mail address linked to your account.  Failure to provide accurate, complete, and current Registration Data at all times constitutes a breach of the Terms, which may result in immediate termination of your PhotoRankr account and takedown of all Content you posted on the Site.  <br />
<br />You may not use as a username the name of another person or entity, a name that is not lawfully available for use, a name or trademark that is subject to any rights of another person or entity other than you without appropriate authorization, or a name that is otherwise offensive, vulgar, or obscene.<br />
<br />Services are available only to individuals who are either: (1) at least 18 years old, or (2) at least 14 years old, and who are authorized to access the Site by a parent or legal guardian.  Services are not available to users under the age of 14 years old.  If you are a parent or legal guardian and have authorized a minor about the age of 14 years old to use the Site, you are responsible for the online conduct of such minor and the consequences of any misuse of the Site by the minor.  Parents and legal guardians are warned that the Site does allow display of photographs and images containing artistic and/or implied nudity and/or nongore violence that may be offensive to some.<br />
<br />The Services are for use by individuals who are photographers, galleries, agents, and other market intermediaries and entities that represent photographers or sell their works.  The Services are also for use by individuals, entities, and corporations seeking to purchase and/or download user Content.   <br />
<br />In order to cooperate with legitimate governmental requests, subpoenas or court orders, to protect PhotoRankr&#39s systems and customers, to ensure the integrity and operation of PhotoRankr&#39s business and systems, or to perform analytics on the Site, PhotoRankr may access and disclose any information from your account it considers necessary or appropriate, including, without limitation, user profile information (i.e., name, e-mail address, etc.), IP addressing and traffic information, usage history, and posted content.  You consent to such disclosure and agree that PhotoRankr&#39s right to disclose any such information as described above shall govern over any contrary terms in any agreement or policy of PhotoRankr.  <br />
<br />If you do not qualify for registration you are not permitted to open an account or use the Services.  PhotoRankr reserves the right to refuse service to anyone at any time, with or without cause, in its sole discretion. <br />
<br /><b>D.  Account Security</b>
<br />You are solely responsible for maintaining the confidentiality of the password associated with your PhotoRankr account and for restricting access to your password and to your computer while logged into the Site.  You agree to accept responsibility for all activities that occur under your account or from your computer.  PhotoRankr endeavors to use reasonable security measures to protect against unauthorized access to your account and to any Content you post to the Site.  We cannot, however, guarantee absolute security of your account, your Content, or the personal information we collect, and we cannot promise that our security measures will prevent third-party &#34;hackers&#34; from illegally accessing the Site or its contents.  You agree to immediately notify PhotoRankr of any unauthorized use of your account or password, or any other breach of security, and to accept all risks of unauthorized access to the Registration Data and any other information you provide to PhotoRankr.<br />
<br /><b>E.  Use Of User Information</b>
<br />In the event that you at any time obtain access to any PhotoRankr user information, whether directly from PhotoRankr or otherwise, including user names and e-mails (collectively, &#34;User Information&#34;), you agree that you may not use any such User Information in any manner except as may be specifically authorized by PhotoRankr to carry out the purpose for which such User Information was provided to you.  Without limiting the foregoing, you may not share such User Information with any third parties or use it for any marketing purposes of any kind.  In no event will PhotoRankr be obligated to provide you with any such User Information.  You agree that this provision shall apply both during and after the term of your use of the Site.<br />
<br /><b>F.  User Conduct</b>
<br />All Content posted or otherwise submitted to the Site is the sole responsibility of the account holder from which such Content originates and you acknowledge and agree that you, and not PhotoRankr, are entirely responsible for all Content that you post, or otherwise submit to the Site.  PhotoRankr does not control user submitted Content and, as such, does not guarantee the accuracy, integrity, or quality of such Content.  You understand that by using the Site you may be exposed to Content that is offensive, indecent, or otherwise personally objectionable.<br />
<br />As a condition of use, you promise not to use the Services for any purpose that is unlawful or prohibited by these Terms, or any other purpose not reasonably intended by PhotoRankr.  By way of example, and not as a limitation, you agree not to use the Services:<br />
<br />1.  To abuse, harass, threaten, impersonate, or intimidate any person;<br />2.  To post or transmit, or cause to be posted or transmitted, any Content that is libelous, defamatory, offensive, obscene, profane, pornographic, harassing, threatening, invasive of privacy or publicity rights, abusive, inflammatory, fraudulent, or that infringes any copyright or other right of any person;<br />3.  To post Content that would constitute, encourage, or provide instructions for a criminal offense, violate the rights of any party, endanger national security, or that would otherwise create liability or violate any local, state, national or international law;<br />4.  To post Content that, in the sole judgment of PhotoRankr, is objectionable, harmful, or which restricts or inhibits any other person from using or enjoying the Site, or which may expose PhotoRankr or its users to any harm or liability of any nature;<br />5.  To post Content that impersonates any person or entity or otherwise misrepresents your affiliation with a person or entity;<br />6.  To promote or sell Content of another person;<br />7.  For any purpose (including posting or viewing Content) that is not permitted under the laws of the jurisdiction where you use the Services;<br />8.  To post or transmit, or cause to be posted or transmitted, any communication or solicitation designed or intended to obtain password, account, or private information from any PhotoRankr user;<br />9.  To post private information of any third party, including, but not limited to, addresses, phone numbers, e-mail addresses, Social Security numbers, and credit card numbers;<br />10.  To create or transmit unwanted &#34;spam&#34; to any person or any URL;<br />11.  To advertise to, or solicit, any user, through comments, messages, or otherwise, to buy or sell any products or services, or to use any information obtained from the Services in order to contact, advertise to, solicit, or sell to any user without their prior explicit consent;<br />12.  To create multiple accounts for the purpose of voting for or against users&#39 photographs or images;<br />13.  To artificially inflate or alter vote counts, comments, or any other Service for any purpose, including for the purpose of giving or receiving money or other compensation in exchange for votes, or for participating in any other organized effort that in any way artificially alters the results of Services;<br />14.  To post copyrighted, trademarked, or patented Content that does not belong to you;<br />15.  To post Content that is viruses, corrupted data, or other harmful, disruptive, or destructive files;<br />16.  To politically campaign;<br />17.  To sell or otherwise transfer your profile;<br />18.  To use any robot, spider, scraper or other automated means to access the Site for any purpose without our express written permission.  Additionally, you agree that you will not:<br />	(i) Take any action that imposes, or may impose, in the sole discretion of PhotoRankr, an unreasonable or disproportionately large load on PhotoRankr&#39s infrastructure;<br />	(ii) Interfere or attempt to interfere with the proper working of the Site or any activities conducted on the Site; or<br />	(iii) Bypass any measures we may use to prevent or restrict access to the Site.<br />
<br />To report a suspected abuse of the Site or a breach of the Terms (other than relating to copyright infringement which is addressed under &#34;Copyright Complaints&#34; below) please send a written notice to PhotoRankr at the following e-mail address: support@PhotoRankr.com.<br />
<br />You are solely responsible for your interactions with other users of the Site.  PhotoRankr reserves the right, but has no obligation, to monitor and mediate disputes between you and other users.  Enforcement of the Content or conduct rules set forth above and in these Terms of Use is solely at PhotoRankr&#39s discretion, and failure to enforce such rules in some instances does not constitute a waiver of our right to enforce such rules in other instances.  In addition, these rules do not create any private right of action on the part of any third party or any reasonable expectation that the Site will not contain any content that is prohibited by such rules.  Although PhotoRankr has no obligation to screen, edit, or monitor any of the Content posted on the Site, PhotoRankr reserves the right, and has absolute discretion, to remove, screen, or edit any Content hosted on the Site at any time and for any reason without notice.  You are solely responsible for creating backup copies of and replacing any Content you host on the Site at your sole cost and expense.<br />
<br /><b>G.  Content Submitted Or Made Available For Inclusion On The Service</b>
<br />Please read this section carefully before posting, uploading, or otherwise submitting any Content to the site.  You retain the copyright in any Content you post on the Site.  PhotoRankr neither has nor wants any ownership of your Content.  However, by uploading and/or posting any Content to the Site, you grant PhotoRankr a nonexclusive, transferable, and fully paid worldwide license (with the right to sublicense) to use the Content and the name that is submitted in connection with such Content, as is reasonably necessary to display the Content, provide the Services and to facilitate, at Content Owner&#39s direction, the license of Photos or the sale of Products on the Site, without obtaining permission or license from any third party.<br />
<br />You understand and acknowledge that any Content contained in public postings or galleries, will be accessible to the public and could be accessed, indexed, archived, linked to, and republished by others including, without limitation, appearing on other web sites and in search engine results.  Therefore, you should be careful about the nature of the Content you post.  PhotoRankr will not be responsible or liable for any third party access to, or use of, the Content you post.  <br />
<br />You represent and warrant that: (1) you own or otherwise control all of the rights to the Content that you post or transmit, or you otherwise have the right to post, use, display, distribute and reproduce such Content, and to grant the rights granted herein; (2) the Content you supply is accurate and not misleading; and (3) use and posting of the Content you supply does not violate these Terms of Use and will not violate any rights of, or cause injury to, any person or entity.<br />
<br />In consideration of PhotoRankr&#39s agreement to allow you to post Content to the Site, PhotoRankr&#39s agreement to publish such Content, and for other valuable consideration, the receipt and sufficiency of which are hereby expressly and irrevocably acknowledged, you agree with PhotoRankr as follows:<br />
<br />1.  You acknowledge that:<br /> 	i.  By uploading your photographic or graphic works to PhotoRankr you retain full rights to those works that you had prior to uploading;<br />	ii.  By posting Content to the Site you hereby grant to PhotoRankr a nonexclusive, transferable, and fully paid worldwide license (with the right to sublicense) to use, distribute, reproduce, modify, adapt, publicly perform and publicly display such Content in connection with the Services.  This license will exist for the period during which the Content is posted on the Site and will automatically terminate upon the removal of the Content from the Site;<br />	iii.  The license granted to PhotoRankr includes the right to use your Content fully or partially for promotional reasons and to distribute and redistribute your Content to other parties, web-sites, applications, and other entities, provided such Content is attributed to you in accordance with the credits (i.e., username, profile picture, photo title, descriptions, tags, and other accompanying information), if any and as appropriate, all as submitted to PhotoRankr by you;<br />	iv.  PhotoRankr makes no representation and warranty that Content posted on the Site will not be unlawfully copied without your consent.  PhotoRankr does not restrict the ability of users and visitors to the Site to make low resolution or &#34;thumbnail&#34; copies of Content posted on the Site and you hereby expressly authorize PhotoRankr to permit users and visitors to the Site to make such low resolution copies of your Content; and<br />	v.  Subject to the terms of the foregoing license, you retain full ownership or other rights in your Content and any intellectual property rights or other proprietary rights associated with your Content. <br />
<br />2.  You represent and warrant that:<br /> 	i.  You are the owner of all rights, including all copyrights, in and to all Content you submit to the site;<br />	ii.  You have the full and complete right to enter into this agreement and to grant to PhotoRankr the rights in the Content herein granted, and that no further permissions are required from, nor payments required to be made to, any other person in connection with the use by PhotoRankr of the Content as contemplated herein; and<br />	iii.  The Content does not defame any person and does not infringe upon the copyright, moral rights, publicity rights, privacy rights, or any other right of any person, or violate any law or judicial or governmental order.<br /><br />
<br />3.  You shall not have any right to terminate the permissions granted herein, nor to seek, obtain, or enforce any injunctive or other equitable relief against PhotoRankr, all of which such rights are hereby expressly and irrevocably waived by you in favor of PhotoRankr.<br />
<br /><b>H.  Copyright And Limited License</b>
<br />The Site and all images, text, code, and other materials on the Site and the selection and arrangement thereof (collectively, the &#34;Site Materials&#34;) are the property of PhotoRankr or its licensors or users and are protected by United States and international copyright laws.<br />
<br />PhotoRankr grants you a limited, nonsublicensable, revocable license to access and use the Site solely in accordance with, and subject to, these Terms and any other applicable terms or agreements you may enter into with PhotoRankr.  Except as otherwise expressly permitted in writing, the license does not include, and you agree to refrain from:<br />	1.  The collection, copying, or distribution of any portion of the Site or the Site Materials;any resale, commercial use, commercial exploitation, distribution, public performance, or public display of the Site or any of the Site Materials;<br />	2.  Modifying or otherwise making any derivative uses of the Site or any of the Site Materials;<br />	3.  Scraping or otherwise using any data mining, robots, or similar data gathering or extraction methods;<br />	4.  With the exception of your own Content, or others&#39 Content available for purchase and download, the downloading of any portion of the Site, the Site Materials, or any information contained therein; or<br />	5.  Any use of the Site or the Site Materials other than for their intended purposes.<br /><br />
<br />Any use of the Site or of any Site Materials other than as specifically authorized herein, without the express prior written permission of PhotoRankr or the Content Owner, is strictly prohibited and will terminate and constitute a breach of the license granted herein.<br />
<br /><b>I.  Repeat Infringer Policy</b>
<br />In accordance with the Digital Millennium Copyright Act and other applicable law, PhotoRankr has adopted a policy of terminating, in appropriate circumstances and at PhotoRankr&#39s sole discretion, account holders who are deemed to be repeat copyright infringers.  PhotoRankr may also, at its sole discretion, limit access to the Site and/or terminate any account holders who infringe any intellectual property rights of others, whether or not there is any repeat infringement.  <br />
<br /><b>J.  Copyright Complaints</b>
<br />PhotoRankr respects the intellectual property rights of others.  It is PhotoRankr&#39s policy to respond promptly to any claim that Content posted on the Site infringes the copyright or other intellectual property infringement (&#34;Infringement&#34;) of any person.  PhotoRankr will use reasonable efforts to investigate notices of alleged Infringement and will take appropriate action under applicable intellectual property law and these Terms where it believes an Infringement has taken place, including removing or disabling access to the Content claimed to be infringing and/or terminating accounts and access to the Site.<br />
<br />To notify PhotoRankr of a possible Infringement, you must submit a notice of such Infringement in writing to PhotoRankr&#39s Designated Agent, as set forth below:<br />
<br />Designated Agent:				Tyler Sniff<br />
<br />Address of Designated Agent:		2030 F Street, NW							Apt. 309							Washington, DC 20006<br />
<br />E-mail Address of Designated Agent:	dmca@PhotoRankr.com	<br />
<br />Include in your notice a detailed description of the alleged Infringement sufficient to enable PhotoRankr to make a reasonable determination.  Please note that you may be held accountable for damages (including costs and attorneys&#39 fees) for misrepresenting that any Content is infringing your copyright.<br />
<br />If we remove or disable access to Content in response to a notice of Infringement, we will make reasonable attempts to contact the user who posted the affected Content.  If you feel that your Content is not infringing, you may provide PhotoRankr with a counter notice in writing to PhotoRankr&#39s Designated Agent at dmca@PhotoRankr.com.  You must include in your counter notice sufficient information to enable PhotoRankr to make a reasonable determination.  Please note that you may be held accountable for damages (including costs and attorneys&#39 fees) if you materially misrepresent that your Content is not infringing the copyrights of others.<br />
<br />If you are uncertain as to whether an activity constitutes Infringement, we recommended seeking the advice of an attorney licensed to practice law in the jurisdiction in which you are located.<br />
<br /><b>K.  Trademarks</b>
<br />&#34;PHOTORANKR,&#34; PhotoRankr, PhotoRankr.com, the look and feel of the Site, and other PhotoRankr graphics, logos, designs, page headers, button icons, scripts, and any other Product or Service names, logos, or slogans of PhotoRankr are registered trademarks, trademarks, or trade dress of PhotoRankr (collectively, the &#34;PhotoRankr&#39s Marks&#34;).  PhotoRankr&#39s Marks may not be copied, imitated, or used without prior express written permission of PhotoRankr.  PhotoRankr&#39s trademarks and trade dress may not be used in connection with any product or service without the prior express written consent of PhotoRankr.  <br />
<br /><b>L.  Links</b>
<br />The Services may provide, or third parties may provide, links to other World Wide Web sites or resources.  PhotoRankr provides these links to you only as a convenience, and the inclusion of any link does not imply affiliation or endorsement of any site or any information contained therein.  Because PhotoRankr has no control over such sites and resources, you acknowledge and agree that PhotoRankr is not responsible for the availability of such external sites or resources, and neither endorses nor is responsible or liable for any content, advertising, products, or other materials on, or available from, such sites or resources.  You further acknowledge and agree that PhotoRankr shall not be responsible or liable, directly or indirectly, for any damage or loss caused or alleged to be caused by, or in connection with use of or reliance on, any such content, goods, or services available on or through any such site or resource.  When you leave the Site, you should be aware that PhotoRankr&#39s terms and policies no longer govern.<br />
<br />You may create a text hyperlink to the Site, provided such link does not portray PhotoRankr or any of its Products or Services in a false, misleading, derogatory, or otherwise defamatory manner.  PhotoRankr may revoke this limited right at any time.  Further, you may not frame the Site without PhotoRankr&#39s express written consent.  <br />
<br /><b>M.  DISCLAIMER OF WARRANTIES</b>
<br />THE SITE, THE SITE MATERIALS, THE PRODUCTS AND THE SERVICES ARE PROVIDED ON AN "AS IS" AND "AS AVAILABLE" BASIS WITHOUT WARRANTIES OF ANY KIND, EXPRESS OR IMPLIED.  TO THE FULL EXTENT PERMISSIBLE BY APPLICABLE LAW, PHOTORANKR DISCLAIMS ALL OTHER WARRANTIES, EXPRESS OR IMPLIED, INCLUDING, WITHOUT LIMITATION, IMPLIED WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, TITLE AND NONINFRINGEMENT AS TO THE SITE, THE SITE MATERIALS, THE PRODUCTS AND THE SERVICES.<br />
<br />PHOTORANKR DOES NOT REPRESENT OR WARRANT THAT THE SITE MATERIALS OR THE SERVICES ARE ACCURATE, COMPLETE, RELIABLE, CURRENT OR ERROR-FREE OR THAT THE SITE, ITS SERVERS OR E-MAIL SENT FROM PHOTORANKR OR THE SITE ARE FREE OF VIRUSES OR OTHER HARMFUL COMPONENTS. PHOTORANKR IS NOT RESPONSIBLE FOR TYPOGRAPHICAL ERRORS OR OMISSIONS RELATING TO PRICING, TEXT, OR PHOTOS.  PHOTORANKR ALSO MAKES NO REPRESENTATION OR WARRANTY REGARDING THE AVAILABILITY, RELIABILITY OR SECURITY OF THE SITE AND SHALL NOT BE LIABLE FOR ANY UNAUTHORIZED ACCESS TO OR ANY MODIFICATION, SUSPENSION, UNAVAILABILITY, OR DISCONTINUANCE OF THE SITE OR THE PRODUCTS OR SERVICES PROVIDED THEREON.<br />
<br /><b>N.  LIMITATION OF LIABILITY</b>
<br />IN NO EVENT SHALL PHOTORANKR OR ITS DIRECTORS, MEMBERS, EMPLOYEES, OR AGENTS BE LIABLE FOR ANY DIRECT, SPECIAL, INDIRECT, OR CONSEQUENTIAL DAMAGES, OR ANY OTHER DAMAGES OF ANY KIND, INCLUDING, BUT NOT LIMITED TO, LOSS OF USE, LOSS OF PROFITS OR LOSS OF DATA, WHETHER IN AN ACTION IN CONTRACT, TORT OR OTHERWISE, ARISING OUT OF OR IN ANY WAY CONNECTED WITH THE USE OF OR INABILITY TO USE OR VIEW THE SITE, THE SERVICES, THE PRODUCTS, THE CONTENT, OR THE SITE MATERIALS CONTAINED IN OR ACCESSED THROUGH THE SITE, INCLUDING ANY DAMAGES CAUSED BY OR RESULTING FROM YOUR RELIANCE ON ANY INFORMATION OBTAINED FROM PHOTORANKR, OR THAT RESULT FROM MISTAKES, OMISSIONS, INTERRUPTIONS, DELETION OF FILES OR E-MAIL, ERRORS, DEFECTS, VIRUSES, DELAYS IN OPERATION OR TRANSMISSION, OR ANY TERMINATION, SUSPENSION OR OTHER FAILURE OF PERFORMANCE, WHETHER OR NOT RESULTING FROM ACTS OF GOD, COMMUNICATIONS FAILURE, THEFT, DESTRUCTION OR UNAUTHORIZED ACCESS TO PHOTORANKR&#39S RECORDS, PROGRAMS OR SERVICES.<br />
<br />IN NO EVENT SHALL THE AGGREGATE LIABILITY OF PHOTORANKR, WHETHER IN CONTRACT, WARRANTY, TORT (INCLUDING NEGLIGENCE, WHETHER ACTIVE, PASSIVE OR IMPUTED), PRODUCT LIABILITY, STRICT LIABILITY OR OTHER THEORY, ARISING OUT OF OR RELATING TO THE USE OF OR INABILITY TO USE THE SITE, THE SERVICES, THE PRODUCTS, THE CONTENT OR THE SITE MATERIALS, EXCEED THE GREATER OF ANY COMPENSATION YOU PAY, IF ANY, TO PHOTORANKR FOR ACCESS TO OR USE OF THE SITE OR THE SERVICES OR FOR THE PURCHASE OF PRODUCTS OR $100.<br />
<br />CERTAIN STATE LAWS DO NOT ALLOW LIMITATIONS ON IMPLIED WARRANTIES OR THE EXCLUSION OR LIMITATION OF CERTAIN DAMAGES.  IF THESE LAWS APPLY TO YOU, SOME OR ALL OF THE ABOVE DISCLAIMERS, EXCLUSIONS, OR LIMITATIONS MAY NOT APPLY TO YOU, AND YOU MIGHT HAVE ADDITIONAL RIGHTS.<br />
<br /><b>O.  Indemnity</b>
<br />You hereby agree to indemnify and hold harmless PhotoRankr, its affiliated and associated companies, and their respective directors, officers, employees, agents, representatives, independent and dependent contractors, licensees, successors, and assigns from and against all claims, losses, expenses, damages, and costs (including, but not limited to, direct, incidental, consequential, exemplary, and indirect damages), and reasonable attorneys&#39 fees, resulting from, or arising out of: (1) a breach of these Terms; (2) Content posted on the Site; (3) the use of the Services, by you or any person using your account or PhotoRankr username and password; (4) the sale or use of your Content; or (5) any violation of any rights of a third party.<br />
<br /><b>P.  Termination</b>
<br />PhotoRankr may terminate or suspend any and all Services and/or your PhotoRankr account immediately, without prior notice or liability, for any reason whatsoever, including without limitation if you breach the Terms.  If you violate the Terms of Use, PhotoRankr in its sole discretion may: (1) require you to remedy any violation thereof, and/or (2) take any other actions that PhotoRankr deems appropriate to enforce its rights and pursue available remedies.  Upon termination of your account, your right to use the Services will immediately cease.  <br />
<br />If you wish to terminate your PhotoRankr account, you may simply discontinue using the Services, or send an e-mail to support@PhotoRankr.com that provides clear written notice of a request to terminate your account.  PhotoRankr will notify you of termination via e-mail.  PhotoRankr may request additional information from you prior to terminating your account.  An account is not terminated in this manner until you receive confirmation of termination from PhotoRankr.  All provisions of the Terms, which by their nature should survive termination, shall survive termination, including, without limitation, ownership provisions, warranty disclaimers, limitations of liability, and indemnity.<br />
<br />If PhotoRankr does not have a current, working e-mail address for you, then you may not receive important notices from PhotoRankr regarding your account, which may include notices regarding termination.  It is your responsibility to remove all Content from your account prior to termination.  Upon termination of your account, PhotoRankr will automatically remove all Content posted to your account.<br />
<br /><b>Q.  Applicable Law</b>
<br />Your use of the Site is subject to all applicable local, state, national, and international laws and regulations.  The Terms of Use and your use of the Site shall be governed by and construed in accordance with the laws of the State of Delaware, as if made within Delaware between two residents thereof, without resort to Delaware&#39s conflict of law provisions.  You agree that any action at law or in equity arising out of or relating to these Terms of Use shall be filed only in the state and federal courts located in Kent County, Delaware and you hereby irrevocably and unconditionally consent and submit to the exclusive jurisdiction of such courts over any suit, action or proceeding arising out of these Terms of Use.<br />
<br /><b>R.  Miscellaneous</b>
<br />No agency, partnership, joint venture, or employment is created as a result of the Terms, and you do not have any authority of any kind to bind PhotoRankr in any respect whatsoever.  The failure of either party to exercise in any respect any right provided for herein shall not be deemed a waiver of any further rights hereunder.  PhotoRankr shall not be liable for any failure to perform its obligations hereunder where such failure results from any cause beyond PhotoRankr&#39s reasonable control, including, without limitation, mechanical, electronic or communications failure or degradation (including &#34;line-noise&#34; interference).  If any provision of these Terms of Use shall be deemed invalid, unlawful, void or for any reason unenforceable, then that provision shall be deemed severable from these Terms of Use and shall not affect the validity and enforceability of any remaining provisions.  PhotoRankr may transfer, assign, or delegate the Terms and its rights and obligations without consent.  Both parties agree that the foregoing Terms are the complete and exclusive statement of the mutual understanding of the parties and supersedes and cancels all previous written and oral agreements, communications, and other understandings relating to the subject matter of the Terms.
<br />
<br /></p>
</div>

<div class="grid_6" style="margin-left:2px solid black;width:150px;height:7325px;margin-top:200px">
<p style="font-size:13px;line-height:1.48;font-family:"Helvetica Neue", Helvetica, sans-serif;">
<b>SUMMARY</b>
</br>
</br>
By using PhotoRankr, you agree to all of the following terms.</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>PhotoRankr provides Services that allow you to post and interact with Photographs, socialize with photographers, and purchase photography and we will develop more features and services in the future. </br></br></br></br></br></br></br></br></br></br>To use PhotoRankr you need to create an account that does not violate other peoples&#39; rights and be at least 18 years old, or 14 years old with your parent or legal guardian&#39;s permission.</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>PhotoRankr is not responsible for the security of your account or your activities on the Site.</br></br></br></br></br></br></br>You may not obtain or use people&#39;s user information.</br></br></br></br></br></br></br>You cannot use PhotoRankr to post pornographic or obscene material, harass people, send spam, undermine the integrity of the photo ranking, or do other unreasonable things.</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>Your photos preserve whatever copyright they had before uploading to PhotoRankr, and you are giving PhotoRankr a license to use your photos to provide a social photography community and marketplace.  </br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>PhotoRankr gives you a limited right to post photos and use the Site&#39; features, but not to copy, modify, or undermine the Site, or use the Site for purposes that PhotoRankr does not intend the Site to be used for.</br></br></br></br></br></br></br></br></br></br></br></br>If you repeatedly post photos that you do not hold the rights for, you will not be allowed to use PhotoRankr.</br></br>If you find people posting photos that they do not hold the rights for, please contact PhotoRankr at dmca@PhotoRankr.com.</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>Please respect PhotoRankr&#39;s trademarks and brand.</br></br></br></br></br></br>PhotoRankr and/or third-parties may provide you with links to other sites that PhotoRankr is not responsible for, and you may only create a link to PhotoRankr for reasonable purposes.  </br></br></br></br></br></br></br></br>PhotoRankr is not providing you with any warranties.  </br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>PhotoRankr is not liable if something goes wrong.  Always retain a backup copy of your photos.</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>If PhotoRankr becomes liable for something you do on the Site, you agree to repay PhotoRankr.</br></br></br></br></br></br>PhotoRankr may stop providing you Services at anytime, and you can terminate your account at anytime by not using PhotoRankr or contacting PhotoRankr.</br></br></br></br></br></br></br></br></br></br></br></br></br>You must follow any applicable laws when you use the Site, and these Terms are governed by Delaware law.</br></br></br></br>You do not work for PhotoRankr and these terms are legally valid to the maximum extent possible.
</p>

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