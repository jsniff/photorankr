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
PhotoRankr.com<br />
<br />
</p>
<p style="font-size:13px;line-height:1.48;font-family:"Helvetica Neue", Helvetica, sans-serif;">
<b>PLEASE READ THESE TERMS AND CONDITIONS OF USE (&#34;TERMS OF USE&#34;) CAREFULLY.  BY ACCESSING OR USING THIS WEB SITE, YOU AGREE TO BE BOUND BY THESE TERMS OF USE.</b>
<br />
<br />
<br />
</p>
</div>

<div class="grid_5 pull_1" style="border-right:2px solid black;width:750px;height:7325px;margin-top:200px">
<p style="padding-right:50px;font-size:13px;line-height:1.48;font-family:"Helvetica Neue", Helvetica, sans-serif;">
<b>I. GENERAL TERMS</b>
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
</div>

<div class="grid_6" style="margin-left:2px solid black;width:150px;height:7325px;margin-top:200px">
<p style="font-size:13px;line-height:1.48;font-family:"Helvetica Neue", Helvetica, sans-serif;">
<b>SUMMARY</b>
</br>
</br>
By using PhotoRankr, you agree to all of the following terms.
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