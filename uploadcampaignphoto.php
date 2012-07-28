<?php
require "functionscampaigns.php"; 
require "db_connection.php";
   
//log them out if they try to logout
session_start();

if($_GET['action'] == "logout") {
	$_SESSION['loggedin'] = 0;
	session_destroy();
}

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

//find out which campaign they are trying to look at
$campaignID = htmlentities($_GET['id']);

if($_SESSION['loggedin'] != 1) {
	mysql_close();
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=signup.php?action=campupload&id=',$campaignID,'">';
	exit();
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

<!DOCTYPE html>
<html>
<head>
	<title>Create a Campaign on PhotoRankr to get photos that match your needs</title>
	<link rel="stylesheet" href="bootstrapnew.css" type="text/css" />
    <link rel="stylesheet" href="reset.css" type="text/css" />
    <link rel="stylesheet" href="text.css" type="text/css" />
    <link rel="stylesheet" href="960_24.css" type="text/css" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  	<script src="campaign/js/bootstrap.js" type="text/javascript"></script>
  	<script src="campaign/js/bootstrap-dropdown.js" type="text/javascript"></script>
  	<script src="campaign/js/bootstrap-collapse.js" type="text/javascript"></script>
  	<link rel="shortcut icon" type="image/x-png" href="campaign/graphics/favicon.png"/>

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

	<style type="text/css">

	.content 
 	 {
  		margin:30px 40px;
  		color:#000000;
  		font-size:16px;
  		z-index:3;
  		font-family: 'helvetica neue'; helvetica;
  	}

	div.transbox
  	{
  		width:300px;
  		height:300px;
  		margin:30px -50px;
  		background-color:#ffffff;
  		border:1px solid black;
  		opacity:1;
  		-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
  		filter:alpha(opacity=100); /* For IE8 and earlier */
  		z-index:1;
  		float:left;
  		font-family: 'helvetica neue'; helvetica;
  	}


	div.smalltransbox
  	{
  		width:270px;
  		height:130px;
  		margin:30px 0px;
  		background-color:#ffffff;
  		border:1px solid black;
  		opacity:1;
  		-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
  		filter:alpha(opacity=100); /* For IE8 and earlier */
  		z-index:1;
  		float:left;
  		font-family: 'helvetica neue'; helvetica;
  	}

  	div.bigtransbox
  	{
  		width:500px;
  		height:600px;
        font-family:'helvetica neue', helvetica, gill sans, arial;
  		margin-left:auto;
   		margin-right: auto;
  		text-align:center;
  		background-color:#fff;
  		border:1px solid black;
  		z-index:1;
  		font-family: 'helvetica neue'; helvetica;
  	}

  	</style>

</head>

<body style="background-image:url('graphics/NYC.jpg');background-size: 100%;
background-repeat:no-repeat;">
	
 <!--NAVIGATION BAR-->
<div class="navbar" style="z-index:10;padding-top:0px;font-size:16px;width:100%;">
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
echo'<a style="text-decoration:none" href="../fullsize.php?image=',$source,'&id=',$id,'"><div id="photoshadowhighlight2"><img src="../graphics/newsfeedcomment.png" height="50" width="50" />&nbsp;<img src="http://www.photorankr.com/',$newsource,'" height="50" width="50" />&nbsp;<span style="color:white">',$fullname4,' commented on your photo</span></div></a><br />';
}

elseif($type == "message") {
$ownermessage = $notsarray['owner'];
$thread = $notsarray['thread'];
$newaccount = "SELECT * FROM userinfo WHERE emailaddress = '$ownermessage'";
$accountresult = mysql_query($newaccount); 
$accountrow = mysql_fetch_array($accountresult);
$profilepic4 = $accountrow['profilepic'];
if($profilepic4 == "") {
$profilepic4 = "../profilepics/default_profile.jpg";
}
echo'<a style="text-decoration:none" href="../myprofile.php?view=viewthread&thread=',$thread,'&id=',$id,'"><div id="photoshadowhighlight2"><img src="../graphics/messagesicon.png" height="50" width="50" />&nbsp;<img src="',$profilepic4,'" height="50" width="50" />&nbsp;<span style="color:white">',$fullname4,' sent you a message</span></div></a><br />';
}

elseif($type == "reply") {
$ownermessage = $notsarray['owner'];
$thread = $notsarray['thread'];
$newaccount = "SELECT * FROM userinfo WHERE emailaddress = '$ownermessage'";$accountresult = mysql_query($newaccount); 
$accountrow = mysql_fetch_array($accountresult);
$profilepic4 = $accountrow['profilepic'];
if($profilepic4 == "") {
$profilepic4 = "../profilepics/default_profile.jpg";
}
echo'<a style="text-decoration:none" href="../myprofile.php?view=viewthread&thread=',$thread,'&id=',$id,'"><div id="photoshadowhighlight2"><img src="../graphics/messagesicon.png" height="50" width="50" />&nbsp;<img src="',$profilepic4,'" height="50" width="50" />&nbsp;<span style="color:white">',$fullname4,' replied to your message</span></div></a><br />';
}

elseif($type == "fave") {
$caption4 = $notsarray['caption'];
$source= $notsarray['source'];
$newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
echo'<a style="text-decoration:none" href="../fullsize.php?image=',$source,'&id=',$id,'"><div id="photoshadowhighlight2"><img src="../graphics/newsfeedfavorite.png" height="50" width="50" />&nbsp;<img src="http://www.photorankr.com/',$newsource,'" height="50" width="50" />&nbsp;<span style="color:white">',$fullname4,' favorited your photo</span></div></a><br />';
}

elseif($type == "trending") {
$caption4 = $notsarray['caption'];
$source= $notsarray['source'];
$newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
echo'<a style="text-decoration:none" href="../fullsize.php?image=',$source,'&id=',$id,'"><div id="photoshadowhighlight2"><img src="../graphics/newsfeedtrending.png" height="50" width="50" />&nbsp;<img src="http://www.photorankr.com/',$newsource,'" height="50" width="50" />&nbsp;<span style="color:white">Your photo is now trending</span></div></a><br />';
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
$profilepic4 = "../profilepics/default_profile.jpg";
}
echo'<a style="text-decoration:none" href="../viewprofile.php?u=',$ownerid,'&id=',$id,'"><div id="photoshadowhighlight2"><img src="../graphics/newsfeednewfollower.png" height="50" width="50" />&nbsp;<img src="',$profilepic4,'" height="50" width="50" />&nbsp;<span style="color:white">',$fullname4,' is now following your photography</span></div></a><br />';
}
} //end if statement

elseif($match > 0) {
if($type == "comment") {
$caption4 = $notsarray['caption'];
$source= $notsarray['source'];
$newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
echo'<a style="text-decoration:none" href="../fullsize.php?image=',$source,'&id=',$id,'"><div id="photoshadow2"><img src="../graphics/newsfeedcomment.png" height="50" width="50" />&nbsp;<img src="http://www.photorankr.com/',$newsource,'" height="50" width="50" />&nbsp;<span style="color:black">',$fullname4,' commented on your photo</span></div></a><br />';
}

elseif($type == "message") {
$ownermessage = $notsarray['owner'];
$thread = $notsarray['thread'];
$newaccount = "SELECT * FROM userinfo WHERE emailaddress = '$ownermessage'";
$accountresult = mysql_query($newaccount); 
$accountrow = mysql_fetch_array($accountresult);
$profilepic4 = $accountrow['profilepic'];
if($profilepic4 == "") {
$profilepic4 = "../profilepics/default_profile.jpg";
}
echo'<a style="text-decoration:none" href="../myprofile.php?view=viewthread&thread=',$thread,'&id=',$id,'"><div id="photoshadow2"><img src="../graphics/messagesicon.png" height="50" width="50" />&nbsp;<img src="',$profilepic4,'" height="50" width="50" />&nbsp;<span style="color:black;">',$fullname4,' sent you a message</span></div></a><br />';
}

elseif($type == "reply") {
$ownermessage = $notsarray['owner'];
$thread = $notsarray['thread'];
$newaccount = "SELECT * FROM userinfo WHERE emailaddress = '$ownermessage'";$accountresult = mysql_query($newaccount); 
$accountrow = mysql_fetch_array($accountresult);
$profilepic4 = $accountrow['profilepic'];
if($profilepic4 == "") {
$profilepic4 = "../profilepics/default_profile.jpg";
}
echo'<a style="text-decoration:none" href="../myprofile.php?view=viewthread&thread=',$thread,'&id=',$id,'"><div id="photoshadow2"><img src="../graphics/messagesicon.png" height="50" width="50" />&nbsp;<img src="',$profilepic4,'" height="50" width="50" />&nbsp;<span style="color:black;">',$fullname4,' replied to your message</span></div></a><br />';
}

elseif($type == "fave") {
$caption4 = $notsarray['caption'];
$source= $notsarray['source'];
$newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
echo'<a style="text-decoration:none" href="../fullsize.php?image=',$source,'&id=',$id,'"><div id="photoshadow2"><img src="../graphics/newsfeedfavorite.png" height="50" width="50" />&nbsp;<img src="http://www.photorankr.com/',$newsource,'" height="50" width="50" />&nbsp;<span style="color:black">',$fullname4,' favorited your photo</span></div></a><br />';
}

elseif($type == "trending") {
$caption4 = $notsarray['caption'];
$source= $notsarray['source'];
$newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
echo'<a style="text-decoration:none" href="../fullsize.php?image=',$source,'&id=',$id,'"><div id="photoshadow2"><img src="../graphics/newsfeedtrending.png" height="50" width="50" />&nbsp;<img src="http://www.photorankr.com/',$newsource,'" height="50" width="50" />&nbsp;<span style="color:black">Your photo is now trending</span></div></a><br />';
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
$profilepic4 = "../profilepics/default_profile.jpg";
}
echo'<a style="text-decoration:none" href="../viewprofile.php?u=',$ownerid,'&id=',$id,'"><div id="photoshadow2"><img src="../graphics/newsfeednewfollower.png" height="50" width="50" />&nbsp;<img src="',$profilepic4,'" height="50" width="50" />&nbsp;<span style="color:black">',$fullname4,' is now following your photography</div></a><br /></span>';
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
                    
                     <?php
@session_start();
if($_SESSION['loggedin'] == 1) {

	echo '
			
                    <li><a style="color:#fff;margin-top:2px;" href="';
                    if($nolikes) {echo '../myprofile.php?view=editinfo&action=discover#discover';}else { echo '../discover.php?image=',$discoverimage;} echo '">Discover</a></li>
                    <li class="dropdown">

						<a style="color:#fff;margin-top:2px;" href="../myprofile.php" class="dropdown-toggle" data-toggle="dropdown">My Profile<b class="caret" style="background-color:#1a618a;
"></b></a>
						<ul class="dropdown-menu" data-dropdown="dropdown">
							<li><a style="color:#fff;" href="../myprofile.php?view=news">News</a></li>
							<li><a style="color:#fff;" href="../myprofile.php">Photography</a></li>
                            <li><a style="color:#fff;" href="../myprofile.php?view=info">Information</a></li>
							<li><a style="color:#fff;" href="../myprofile.php?view=upload">Upload</a></li>
							<li><a style="color:#fff;" href="../myprofile.php?view=followers">Followers</a></li>
							<li><a style="color:#fff;" href="../myprofile.php?view=following">Following</a></li>
							<li><a style="color:#fff;" href="../myprofile.php?view=faves">Favorites</a></li>
							<li><a style="color:#fff;" href="viewcampaigns.php?action=logout">Log Out</a></li>
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
						<input type="text" style="width:180px;border-color:#fff;background-color:#fff;margin-left:20px;" class="search-query" name="searchterm" placeholder="Search">
					 </form>
					 
				</ul>
			
		</div> <!--/end boostrap divs navbar-->
    </div>
</div>

    
<!--START OF CONTAINER-->
<div class="container_24">

<div class="grid_24" style="width:960px;margin-left:auto;margin-right:auto;">


	<div class="grid_13 push_5 well" style="margin-top: 15%">
		<p style="font-size:22px;padding:6px;margin-top:10px; margin-left: 80px;">Upload a photo to this campaign:</p>
<?php
//connect to the database
require "db_connection.php";

//start the session
session_start();

//find out which campaign they are trying to look at
$campaignID = htmlentities($_GET['id']);

//select all of the campaigns information
$campaignquery = "SELECT * FROM campaigns WHERE id='$campaignID' LIMIT 1";
$campaignresult = mysql_query($campaignquery);

//find out all of the campaigns information
$title = mysql_result($campaignresult, 0, "title");
$description = mysql_result($campaignresult, 0, "description");
$quote = mysql_result($campaignresult, 0, "quote");

//display the campaign information
echo "<center><div style='text-align: center; margin-top: -10px; margin-bottom: 10px; width: 500px; font-size: 15px;'>Title: ", $title, "<br />Description: ", $description, "<br />Reward: $", $quote, "<br /></div></center>";

//if there was an error trying to upload
if(($_GET['action']) == "uploadfailure") {
  //display that they didn't fill in all the fields
  echo '<center style=" margin-top: 10px; margin-bottom: 15px;"><span style="font-size: 16px;" class="label label-important">Please fill in all fields.</span></center>';
}
//else if they were successful uploading
else if(($_GET['action']) == "uploadsuccess") {
  //display that it was successful
    echo '<center style=" margin-top: 10px; margin-bottom: 15px;"><span style="font-size: 16px;" class="label label-success"><a style="text-decoration:none;color:white;" href="campaignphotos.php?id=',$campaignID,'&view=newest">Upload successful. Click here to view entry</a></span></center>';
}

	echo '<form method="post" action="upload_photocampaign.php?campaign=', $campaignID, '" enctype="multipart/form-data">
    <div style="margin-left: 140px;"><input name="file" type="file" /><br /></div>
    <div style="margin-left: 100px; font-size: 15px; font-weight: 1.2;">Caption: <input name="caption" type="caption" /><br /></div>
    <input style="margin-left:50px;" type="checkbox" name="terms" value="terms" />&nbsp;&nbsp;<span style="font-size:12px;">By checking here, you agree to the <b><a style="color:black;" data-toggle="modal" data-backdrop="static" href="#campaignagreement">campaign content license agreement</a></b>.</br></span>
    <div style="margin-left: 210px;margin-top:6px;"><button type="submit" class="btn btn-success">Upload Now</button></div>
  </form>';
?>

 <?php
            
 //Campaign Agreement Modal
    
        echo'<div class="modal hide fade" id="campaignagreement" style="overflow-y:scroll;overflow-x:hidden;width:850px;margin-left:-400px;">

        <div class="modal-header">
        <a style="float:right" class="btn btn-primary" data-dismiss="modal">Close</a>
        <img style="margin-top:-4px;" src="campaign/graphics/logocampaign.png" width="220" />&nbsp;&nbsp;<span style="font-size:16px;">Campaign Content License Agreement</span>
        </div>
        <div modal-body" style="width:700px;">
        <div id="content" style="font-size:16px;width:830px;height:400px;overflow-x:hidden;margin-top:10px;margin-left:10px;">
        <div>
        <pre style="font-family:helvetica,arial;font-size:13px;padding-left:10px;margin-right:20px;">
        
        
        <div style="text-align:center;font-size:15px;font-weight:bold;">CAMPAIGN CONTENT LICENSE AGREEMENT</div>';
echo
htmlentities("
	In the event that the Licensee selects as the Image, the image, or images, as the case may be, submitted by the Licensor as part of a PhotoRankr Tender, then the Licensee and Licensor will be deemed to enter into a separate binding agreement in relation to the license of the Image and the rights of the Licensor in relation to such Image.

	For the avoidance of doubt:

	This Agreement is in addition to the terms applicable to the Site, which includes without limitation the terms of use, non disclosure agreement, privacy policy, or any other policy or procedure communicated by PhotoRankr from time to time;

	PhotoRankr and its third party providers will not be a party to this separate agreement and will have no liability whatsoever in relation to the performance or failure to perform of a Licensor or Licensee under the terms of this separate agreement.

	The agreement of the Licensor to the terms and conditions set out below is shown by clicking on the 'Agree' button.  By clicking on the 'Agree' button below, you represent and warrant that you have read and understood all of the terms and conditions set forth below and agree to be bound by them.  If you do not agree to such terms, you should not click the 'Agree' button below.  If you click on the 'Agree' button on behalf of your employer, you represent and warrant that you have full legal authority to bind your employer or such other entity.  If you do not have such authority, you should not click the 'Agree' button below.

1. 	Definitions

	Unless inconsistent with the context, the following expressions shall have the following meanings:

	'Agreement' means this intellectual property license agreement;

	'Business Day' means any day which is not a Saturday, Sunday, or a national holiday in the United States;

	'Image' means the image or images, as the case may be, which the Licensor selects as the winning image pursuant to the PhotoRankr Tender;

	'PhotoRankr' means PhotoRankr, Inc., a corporation of the State of Delaware located at 160 Greentree Drive, Suite 101, Dover, Delaware 19904;

	'PhotoRankr Tender' means a tender held by the Licensor on the Site, pursuant to which prospective photographers submit images for review and consideration by the Licensor;

	 'Intellectual Property' means the Image; 'Licensee' means 'Sample Licensee,' identified also by the username '$repemail'; 'Licensor' means 'Content Creator', identified also by the username 'Content Creator'; 'Site' means www.photorankr.com. 

2. 	Interpretation 

	In these terms and conditions, unless the context otherwise indicates:

(a)	References to any statute, ordinance, or other law shall include all regulations and other instruments thereunder and all consolidations, amendments, re-enactments or replacements thereof.

(b)	Words importing the singular shall include the plural and vice versa, words importing a gender shall include other genders and references to a person shall be construed as references to an individual, firm, body corporate, association (whether incorporated or not), government, and governmental, semi-governmental, and local authority or agency.

(c)	Where any word or phrase is given a defined meaning in these terms and conditions, any other part of speech or other grammatical form in respect of such word or phrase shall have a corresponding meaning.

3.	License

(a)	Unless otherwise agreed by the Licensor and the Licensee in writing, the Licensor grants the Licensee a license to use the Image as follows:

	(i)	Term:  entered above; starting from: entered above;

	(ii)	Territory of Use:  entered above;

	(iii)	Permitted Uses:  entered above;

	(iv)	Exclusive or Non-Exclusive Use: entered above;

	(v)	if the Permitted Uses in (iii) above include Web Advertising, Digital Banners, Social Media, Web Video, E-mail Promotion and Electronic Brochure, Apps, E-Book, Corporate, Retail and Promotional Site, then worldwide territory is hereby granted;

	(vi)	Additional Terms: entered above
             
             
             
(b)	The Licensee must only use the Image as expressly permitted by this Agreement.

(c)	Notwithstanding any other provision of this Agreement, the Licensee must not use the Image for any pornographic use, in a manner which is obscene or immoral, for any unlawful purpose, to defame any person, or to violate any person’s right to privacy or publicity.

4.	Third Party Rights

(a)	The Licensor agrees, represents and warrants that:

	(i)	the Image does not infringe any reputation or intellectual property right of a third party;

	(ii)	all relevant authors have agreed not to assert their moral rights (personal rights associated with authorship of a work under applicable law) in the Image;

	(iii)	if the Image incorporates the intellectual property rights of a third party, then the Licensor has obtained a license from the relevant third party to incorporate the intellectual property rights of that third party in the Image ('Third Party License');

	(iv)	the Third Party License permits the Licensee with a worldwide, royalty free, perpetual right to display, distribute, and reproduce (in any form) the intellectual property rights of the third party contained in the Image.

(b)	In the event that the Third Party License is capable of assignment to the Licensee, then the Licensee hereby assigns and transfers to the Licensor, and the Licensee hereby agrees to take an assignment and transfer thereof, the Third Party License and all of the rights and obligations of the Licensor under the Third Party License.

5.	Indemnity

	The Licensor must indemnify and keep indemnified the Licensee from and against all loss, cost, expense (including legal costs and attorney's fees) or liability whatsoever incurred by the Licensee arising from any claim, demand, suit, action, or proceeding by any person against the Licensee where such loss or liability arose out of an infringement, or alleged infringement, of the intellectual property rights of any person, which occurred by reason of the license of the Image by the Licensor.

6.	Liability of PhotoRankr and Its Third Party Providers

	Both the Licensor and the Licensee acknowledge and agree that: 

(a)	PhotoRankr and its Third Party Providers are not parties to this Agreement; and

(b)	each of PhotoRankr and its Third Party Providers shall each not be liable or responsible for any breach of this Agreement by any one or more of the Licensee and the Licensor.

7.	Representations and Warranties

(a)	The Image is provided 'as is,' and, to the fullest extent permitted under the applicable law, the Licensor hereby expressly disclaims any and all warranties of any kind or nature, whether express, implied, or statutory.

(b)	The Licensee acknowledges and confirms that the Licensor does not make any warranty or representation that the Image will satisfy the requirements of the Licensee.

8.	Termination

	Notwithstanding any other provisions of this Agreement, the Licensor has the right to immediately terminate this Agreement and the license granted hereunder if the Licensee has breached any of its obligations under this Agreement.

9.	Assignment

	This Agreement is personal to each of the License and the Licensor, and may not be assigned without the prior written consent of the other party.

10.	Further Assurances

	Each of the parties will upon request by any other party hereto at any time and from time to time, execute, sign, and deliver all documents and do all things necessary or appropriate to evidence or carry out the intent and purposes of these Terms.

11.	Entire Agreement

	These Terms, and any attachments thereto, including releases from models, minors, and property owners, constitute the entire agreement between the parties and supersedes all prior representations, agreements, statements, and understanding, whether verbal or in writing.

12.	Notices

	A notice or other communication given under this Agreement, including, but not limited to, a request, demand, consent or approval, to or by a party to this Agreement: 

(a) 	must be in legible writing and in English;

(b) 	must be addressed to the addressee at the mailing address or e-mail address set forth below or to any other mailing address or email address a party notifies to the others in writing:

	(i)	if to the Licensor: 
		Content Creator

	(ii)	if to the Licensee: 
		    $repemail
             
             
		

(c)	without limiting any other means by which a party may be able to prove that a notice has been received by another party, a notice is deemed to be received:

	(i)	if sent by hand, when delivered to the addressee;

	(ii)	if by mail, three Business Days from, and including, the date of postmark; or

	(iii)	if by e-mail transmission, on receipt by the sender of an e-mail acknowledgment or read receipt generated by the e-mail client to which the email was sent, but if the delivery or receipt is on a day which is not a Business Day or is after 5.00 p.m (addressee's time), it is deemed to be received at 9.00 a.m. on the following Business Day.

13.	Miscellaneous

(a)	Tax Liability.  You agree to pay and be responsible for any and all sales taxes, use taxes, value added taxes and duties imposed by any jurisdiction as a result of the license granted to you, or of your use of the Content, pursuant to this Agreement. 

(b)	Invalid Provisions.  If any provision of this Agreement is found to be invalid or otherwise unenforceable under any applicable law, such invalidity or unenforceability shall not be construed to render any other provisions contained herein as invalid or unenforceable, and all such other provisions shall be given full force and effect to the same extent as though the invalid or unenforceable provision were not contained herein.

(c)	Applicable Law.  This Agreement shall be governed by and construed in accordance with the laws of the State of Delaware without regard to the conflict of law principles of Delaware or any other jurisdiction.  This Agreement will not be governed by the United Nations Convention on Contracts for the International Sale of Goods, the application of which is expressly excluded.  You consent to service of any required notice or process upon you by registered mail or overnight courier with proof of delivery notice, addressed to the address or contact information provided by you at the time the Content was downloaded, or such other address as you may advise us in writing to use, from time to time.

(d)	Arbitration.  Any controversy or claim arising out of or relating to this contract, or the breach thereof, shall be determined by arbitration administered by the American Arbitration Association in accordance with its International Arbitration Rules.  The number of arbitrators shall be one.  The place of arbitration shall be New York, NY.  The arbitration shall be held, and the award shall be rendered, in English.

(e)	Waiver.  No action of Licensor, other than express written waiver, may be construed as a waiver of any provision of this Agreement.  A delay on the part of Licensor in the exercise of its rights or remedies will not operate as a waiver of such rights or remedies, and a single or partial exercise by Licensor of any such rights or remedies will not preclude other or further exercise of that right or remedy.  A waiver of a right or remedy by Licensor on any one occasion will not be construed as a bar to or waiver of rights or remedies on any other occasion.

(f)	Severability.  If any provision of this Agreement is found to be invalid or otherwise unenforceable under any applicable law, such invalidity or unenforceability shall not be construed to render any other provisions contained herein as invalid or unenforceable, and all such other provisions shall be given full force and effect to the same extent as though the invalid or unenforceable provision were not contained herein.

(g)	Section Headings.  The descriptive headings of this Agreement are for convenience only and shall be of no force or effect in construing or interpreting any of the provisions of this Agreement.

14.	Acknowledgement

	YOU ACKNOWLEDGE THAT YOU HAVE READ THIS AGREEMENT, UNDERSTAND IT, AND HAD AN OPPORTUNITY TO SEEK INDEPENDENT LEGAL ADVICE PRIOR TO AGREEING TO IT.  IN CONSIDERATION OF PHOTORANKR AGREEING TO PROVIDE THE CONTENT, YOU AGREE TO BE BOUND BY THE TERMS AND CONDITIONS OF THIS AGREEMENT.  YOU FURTHER AGREE THAT IT IS THE COMPLETE AND EXCLUSIVE STATEMENT OF THE AGREEMENT BETWEEN THE LICENSOR AND LICENSEE, WHICH SUPERSEDES ANY PROPOSAL OR PRIOR AGREEMENT, ORAL OR WRITTEN, AND ANY OTHER COMMUNICATION BETWEEN THE LICENSOR AND LICENSEE RELATING TO THE SUBJECT OF THIS AGREEMENT. 

15.	Electronic Acceptance

	The parties have executed this Agreement as of the date of the Licensor clicking the checkbox.");

echo'
</pre>
</div>
    

</div>
</div>
</div>';
            
?>


	</div><br/ >
</div>
</div><!--/end of container-->
</body>
</html>
<?php

mysql_close();

?>