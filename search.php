<?php

//connect to the database
require 'db_connection.php';

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
		die('Incorrect password, please try again. <a href="lostpassword.php"> Lost your password?</a>');	
	}
}

//log them out if they try to logout
session_start();

if($_GET['action'] == logout) {
	$_SESSION['loggedin'] = 0;
	session_destroy();
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

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>

<meta name="Generator" content="EditPlus">
   <meta name="viewport" content="width=1200" /> 

  <meta name="Author" content="PhotoRankr, PhotoRankr.com">
  <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
  <meta name="Description" content="PhotoRankr allows photographers of all skill levels to sell and share their work. Create your photostream cutomized to what you want to see. Add photos to your favorites, rank them, and watch them trend. Build your portfolio with Photorankr.">

<title>Search Photorankr</title>
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
                        
        ?>
                    
                    <li class="dropdown">
                    
					<li class="dropdown active" style="background-color:#587fa2;">
						<a style="color:#fff;margin-top:2px;margin-right:20px;" href="myprofile.php" class="dropdown-toggle" data-toggle="dropdown">My Profile<b class="caret"></b></a>
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
					</li>
                    
					<li class="navbartext"><form class="navbar-search" action="search.php" method="get">
						<input type="text" style="width:150px;border-color:#fff;background-color:#fff;margin-left:20px;" class="search-query" name="searchterm" placeholder="Search">
					</form></li>
				</ul>
			
		</div> <!--/end boostrap divs navbar-->
    </div>
</div>


<!--big container-->
    <div id="container" class="container_12" >


<?php

//CONNECT TO DATABASE
require "db_connection.php";

//GET SEARCH TERM FORM FORM
$searchterm = $_GET['searchterm'];
trim ($searchterm);

//CHECK TO SEE IF TERM WAS ENTERED
if (!$searchterm){
        echo '<div style="position:absolute; top:300px; left:480px; font-family:lucida grande, georgia, helvetica; font-size:25px;">Please enter a search term.</div>';
}
else {
	//ADD SLASHES TO SEARCH TERM
	if (!get_magic_quotes_gpc())
	{
		$searchterm = addslashes($searchterm);
	}

	//QUERIES
	$photoquery = "SELECT *, MATCH (caption, tag, camera, tag1, tag2, tag3, tag4, singlecategorytags, singlestyletags, location, country, about, sets, maintags, settags) AGAINST ('$searchterm') AS matching FROM photos WHERE MATCH (caption, tag, camera, tag1, tag2, tag3, tag4, singlecategorytags, singlestyletags, location, country, about, sets, maintags, settags) AGAINST ('$searchterm') ORDER BY matching DESC";
	$photoresult = mysql_query($photoquery) or die(mysql_error());

	$profilequery = "SELECT *, MATCH (firstname, lastname) AGAINST ('$searchterm') AS matching FROM userinfo WHERE MATCH (firstname, lastname) AGAINST ('$searchterm') ORDER BY matching DESC";
	$profileresult = mysql_query($profilequery) or die(mysql_error());

	//NUMBER OF ROWS FOUND
	$num_results = mysql_num_rows($photoresult);
	$num_profileresults = mysql_num_rows($profileresult);
       
      //LOOP THROUGH PICTURE RESULTS

	if ($num_results > 0 && $searchterm) {
    
    if ($num_results == 1) {
    echo'
<div style="font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
line-height: 18px; color: #333333; font-size:35px;text-align:center; font-weight:bold; position:relative; top:90px;">
',$num_results,' Photo Found:
</div>'; }

    elseif ($num_results > 1) {
    echo'
<div style="font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
line-height: 18px; color: #333333; font-size:35px;text-align:center; font-weight:bold; position:relative; top:90px;">
',$num_results,' Photos Found for ', $searchterm, ':
</div>'; }


    echo'<div id="container" class="container_24" style="margin-top:100px;">';

		for ($iii=0; $iii <$num_results; $iii++) {
			$photoarray = mysql_fetch_array($photoresult);
			$source = $photoarray['source'];
			$newsource = str_replace("userphotos/","userphotos/medthumbs/", $source);
			$caption = $photoarray['caption'];
			$thisemail = $photoarray['emailaddress'];
			$infoquery = "SELECT firstname, lastname FROM userinfo WHERE emailaddress = '$thisemail'";
			$inforesult = mysql_query($infoquery);
			$infoarray = mysql_fetch_array($inforesult);
			$firstname = $infoarray['firstname'];
			$lastname = $infoarray['lastname'];
            $fullname = $firstname . " " . $lastname;
            $fullname = ucwords($fullname);
 
		
        echo'
		<div class="grid_10 push_1" id="photoshadow" style="margin-top:60px;width:250px;height:320px;">   
			<a href="fullsize.php?image=',$source,'&v=r"><img style="max-height:400px" src="http://www.photorankr.com/',$source,'" name="mynextpicone" width="250" height="250"></img></a><br /><div style="margin-top:0px;font-size:15px;text-align:center;"><br />"',$caption,'"<br />By: ',$fullname,'</div>   
		</div>'; 
		
    /*    elseif(($iii % 2) == 0) {
        echo'
		<div class="grid_10 push_2" id="photoshadow" style="margin-top:60px;width:250px;height:320px;">   
			<a href="fullsize.php?image=',$source,'&v=r"><img style="max-height:400px" src="http://www.photorankr.com/',$source,'" name="mynextpicone" width="250" height="250"></img></a><br /><div style="margin-top:0px;font-size:15px;text-align:center;"><br />"',$caption,'"<br />By: ',$fullname,'</div>   
		</div>'; } */
        
    } //end of for loop
    echo'</div>';
	} //end of if statement

if ($num_results < 1 && $searchterm) {
		echo '<div style="font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
line-height: 18px; color: #333333; font-size:35px;text-align:center; font-weight:bold; position:relative; top:90px;padding-bottom:30px;">
Sorry, No Photos Found  for ', $searchterm, '.</div>';
	}


//LOOP THROUGH PROFILE RESULTS

   echo'<div class="grid_12" style="margin-top:100px;">';
   
	if ($num_profileresults > 0 && $searchterm) {
    
    if ($num_profileresults == 1) {
    echo'
<div style="font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
line-height: 18px; color: #333333; font-size:35px;text-align:center; font-weight:bold; position:relative;">
',$num_profileresults,' Photographer Found  for ', $searchterm, ':
</div>'; }

    elseif ($num_profileresults > 1) {
    echo'
<div style="font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
line-height: 18px; color: #333333; font-size:35px;text-align:center; font-weight:bold; position:relative;">
',$num_profileresults,' Photographers Found  for ', $searchterm, ':
</div>'; }


		for ($iii=0; $iii <$num_profileresults; $iii++) {
			$profilearray = mysql_fetch_array($profileresult);
			$profilepic = $profilearray['profilepic'];
            if (!$profilepic) {
            $profilepic = "profilepics/default_profile.jpg";
            }
            $user = $profilearray['user_id'];
			$firstname = $profilearray['firstname'];
			$lastname = $profilearray['lastname'];
            $fullname = $firstname . " " . $lastname;
            $fullname = ucwords($fullname);
            $tpscore = $profilearray['totalscore'];
        
                    
            //IF NOT TOP PHOTOGRAPHER
            
            if ($tpscore < 550) {
            echo'
		<div class="grid_10 push_1" id="photoshadow" style="margin-top:60px;width:250px;height:320px;">   
			<a href="http://www.photorankr.com/viewprofile.php?u=',$user,'"><img style="max-height:400px" src="',$profilepic,'" name="mynextpicone" width="250" height="250"></img></a><br /><div style="margin-top:0px;font-size:15px;text-align:center;"><br />',$fullname,'</div>   
		</div>'; 
            }
            
             elseif ($tpscore > 550) {
            echo'
		<div class="grid_10 push_1" id="photoshadow" style="margin-top:60px;width:250px;height:320px;">   
			<a href="http://www.photorankr.com/viewprofile.php?u=',$user,'"><img style="max-height:400px" src="',$profilepic,'" name="mynextpicone" width="250" height="250"></img></a><br /><div style="margin-top:0px;font-size:15px;text-align:center;"><br />',$fullname,'<br /><span class="badge badge-warning" style="font-size:13px;background"><i class="icon-star icon-white"> </i> Top Photographer</span></div>   
		</div>'; 
            }
        
		
       /* elseif(($iii % 2) == 0) {
        echo'
		<div class="grid_10 push_1" id="photoshadow" style="margin-top:60px;width:250px;height:320px;">   
			<a href="http://www.photorankr.com/viewprofile.php?u=',$user,'"><img style="max-height:400px" src="',$profilepic,'" name="mynextpicone" width="250" height="250"></img></a><br /><div style="margin-top:0px;font-size:15px;text-align:center;"><br />',$fullname,'</div>   
		</div>'; } */
        
    } //end of for loop
    echo'</div>';
	} //end of if statement
    
    
    
    	if ($num_profileresults < 1 && $searchterm) {
		echo '<div style="font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
line-height: 18px; color: #333333; font-size:35px;text-align:center; font-weight:bold; position:relative; top:30px;padding-bottom:30px;">Sorry, No Photographers Found  for ', $searchterm, '.</div>';
	}    
    
    

}

?>

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

</div>



</body>
</html>
