<?php

//log them out if they try to logout
session_start();

if($_GET['action'] == "logout") {
	$_SESSION['loggedin'] = 0;
	session_destroy();
}

require "db_connection.php";

$email6 = $_SESSION['email'];

//QUERY FOR NOTIFICATIONS
$currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$email6'";
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


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="reset.css" />
        <link rel="stylesheet" type="text/css" href="text.css" />
        <link rel="stylesheet" type="text/css" href="bootstrapnew.css" />
        <link rel="stylesheet" type="text/css" href="960_24.css" />
        <link rel="stylesheet" type="text/css" href="smoothDivScroll.css" />
        <link rel="stylesheet" media="all" type="text/css" href="Sign Up.css" />  
        <link rel="stylesheet" type="text/css" href="about90.css"/>      
        <link rel="stylesheet" type="text/css" href="zocial.css"/>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <script type="text/javascript" src="bootstrap-dropdown.js"></script>
        <script src="jquery-ui-1.8.18.custom.min.js" type="text/javascript"></script>
        <script src="jquery.mousewheel.min.js" type="text/javascript"></script>
        <script src="jquery.smoothdivscroll-1.2-min.js" type="text/javascript"></script>
        <script src="jquery.exif.js" type="text/javascript" </script>
        
        <script type="text/javascript">
            // Initialize the plugin with no custom options
            $(document).ready(function () {
                              // None of the options are set
                              $("div#makeMeScrollable").smoothDivScroll({});
                              }); 
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

<!--GOOGLE ANALYTICS CODE-->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-28031297-1']);
  _gaq.push(['_setDomainName', 'photorankr.com']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
        
</head>

<body  style="overflow-x:hidden;"> 

        

<!--NAVIGATION BAR-->
<div class="navbar" style="z-index:10;min-width:1220px;padding-top:0px;font-size:16px;width:100%;">
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
        <!--Navbar end-->
<div class="container_24" style="padding-top:80px;"><!--container begin--> 
 <div class="grid_24">  
  <div class="grid_18 title" style="width:900px;"> <h1 class="header"> PhotoRankr is a community and a marketplace for passionate photographers. </h1>
   </div>
   <div class="grid_18" style="height:500px;z-index:0;margin-top:30px;px;float:left;margin-right:20px;">
<p style="font-size:35px;font-family:helvetica neue, helvetica, sans-serif;line-spacing:1.48;color:white;margin-top:20px;px;margin-left:140px;">Learn more about PhotoRankr.<span style="font-size:13px;"></span></p>

  <iframe src="about_frame.html" name="frame_about" style="width:600px;height:455px;margin-top:-90px;margin-left:30px;margin-bottom:10px;border:solid 10px white;border-radius:10px;box-shadow:2px 2px 2px 2px #eeeeee;-webkit-box-shadow:2px 2px 4px 4px #999;" scrolling=no></iframe>
</div>

      <a style="text-decoration:none;" href="frame_divone.html" target="frame_about"><div class="grid_6" id="anchorbox1"> 
      <h1 class="anchorboxtexthead">  Get genuine feedback. </h1>       <p class="anchorboxtextp">You care about your work and so do others. Ranks, favorites, and comments help everyone learn. </p>
     </div></a>

     <a style="text-decoration:none;" href="frame_divtwo.html" target="frame_about"><div class="grid_6" id="anchorbox2">
       <h1 class="anchorboxtexthead">  Find photographers like you. </h1> 
        <p class="anchorboxtextp"> Other photographers out there want to learn from you. You can learn from them. </p> 
      </div></a> 

     <a style="text-decoration:none;" href="frame_divthree.html" target="frame_about"><div class="grid_6" id="anchorbox3">
      <h1 class="anchorboxtexthead">  Re-imagine photography. </h1> 
        <p class="anchorboxtextp"> How you see photography changes. Discover new photos and get inspired. </p> 
      </div></a> 

     <a style="text-decoration:none;" href="frame_divfour.html" target="frame_about"><div class="grid_6" id="anchorbox4">
      <h1 class="anchorboxtexthead">  A market for your best photos. </h1> 
       <p class="anchorboxtextp"> A photograph is more than a commodity; it's art, and it's yours. So you name your photo's price.  </p> 
      </div></a> 

     <a style="text-decoration:none;" href="frame_divfive.html" target="frame_about"><div class="grid_6" id="anchorbox5">
      <h1 class="anchorboxtexthead">  Simple. </h1> 
       <p class="anchorboxtextp"> Photographers, a community, and a marketplace for buyers. That's it. </p>
      </div></a>  

      </div>   
 <div class="grid_24">
  <div class="grid_12" id="anchorGet1" style="margin-right:-20px;float:left;">
   <div class="grid_12 anchorhead1" style="float:left;">
     <h1 class="bigboxheader"> You Are The Curator. </h1>
    </div>
   <div class="grid_11 anchorp">
    <p class="bigboxbody"> It's quick and easy. Upload your photos, rank photos and favorite the ones you want to keep. Ranks and faves drive photos to the trending pages, and higher up in the search results. Our reputation system is based 
   on how the community ranks your photos and how often you contribute to the community by uploading, ranking, favoriting. The higher your rep, the more your ranks count.
   You're passionate about photography, so we want you to jump in to PhotoRankr!  
      </p>
    </div>  
   </div>
  <div class="grid_12" id="anchorFind1" style="float:right;">
    <div class="grid_12 anchorhead1" style="float:left;">
     <h1 class="bigboxheader"> A Different Take On Social Photography.</h1>
      </div>
    <div class="grid_11 anchorp">
     <p class="bigboxbody"> Don't just be social to share, teach too! Photography is the art of communicating an experience, but there are a few rules to it. 
     When you see photos with poor composition or over-saturation, it may be intentional, but 
     the photographer might not know better. Let them know! With each comment or message that photographer will get better
     at communicating in a single glance, a thousand words. Helping one photographer helps photography as much as sharing brilliant photos.</p>
      </div>
   </div> 
   <div class="grid_12" id="anchorRe1" style="float:left;margin-right:-20px;">
    <div class="grid_12 anchorhead1" style="float:left;">
     <h1 class="bigboxheader"> Be Visible In A Crowded Market. </h1>
    </div>
   <div class="grid_11 anchorp">
     <p class="bigboxbody"> You and your photos are visible on PhotoRankr. You have your own space. Digital noise poses a problem for photographers. In a noisy photo, the details blend together.
      On a noisy internet, the individual photos and photographers blend together. With a profile on Photorankr you can direct clients, friends, and family to one 
      place to view and buy your photos. Use our promotion feature to easily spread your work across social networks, email, and more.
      
      </p>
    </div>  
    </div>
   <div class="grid_12" id="anchorA1" style="float:right;">
    <div class="grid_12 anchorhead1" style="float:left;">
     <h1 class="bigboxheader"> Discover. </h1>
    </div>
   <div class="grid_11 anchorp">
    <p class="bigboxbody"> Inspire your own work. Enjoy great photography you want to see. Learn from other photographers. Have you caught a theme? Using our "Discover" feature, 
    the discovery ends only after you have seen every photo. It's simple. Name the types of photography you enjoy, and start clicking. 
     Happen on a great photo, follow that photographer, buy a print if you love it! Connect with photographers. Photorankr is the platform, create something beautiful. </p>
    </div>  
    </div> 
    <div class="grid_12">
     <h1 class="bigboxheader"> Begin and Enjoy </h1>
      <p style="margin-left:60px;margin-top:-20px;"> Click on one of the photos below
   <div class="grid_24" id="dog" style="height:;">
    <div class="grid_7 push_1" style="width:300px;height:250px;float:left;">
      <a href= "http://photorankr.com/fullsize.php?image=userphotos/dreamscape.jpg&v=r"><img src="dreamscape.jpg" width="250px" height="250px" style="border: 10px solid rgba(255,255,255,.5);margin-left:-10px;box-shadow: 2px 2px 2px 2px #cccccc;"/></a>
     </div>
    <div class="grid_7 push_1" style="width:300px;height:250px;float:left;">
     <a href= "http://photorankr.com/fullsize.php?image=userphotos/fortfischersurrealist2.jpg&v=r"> <img src="Fort Fischer Surrealist 2.jpg" width="250px" height="250px"style="border: 10px solid rgba(255,255,255,.5);margin-left:-10px;box-shadow: 2px 2px 2px 2px #cccccc;"/>
    </a> </div> 
    <div class="grid_7 push_1" syle="width:300px;height:250px;float:left;">
     <a href="http://photorankr.com/fullsize.php?image=userphotos/fortfischerview.jpg&v=r"> <img src="Fort Fischer View.jpg" width="250px" height="250px" style="border: 10px solid rgba(255,255,255,.5);margin-left:-10px;box-shadow: 2px 2px 2px 2px #cccccc;" /></a>
    </div> 
   <div> 
    </div>
    </div>
    
    <div class="grid_24" style="margin-top:100px;margin-bottom:20px;height100px;text-align:center;">
    
   <a href="mailto:support@photorankr.com?Subject="Hello Dev Team!"><button class="btn btn-primary">Email the Dev Team!</button></a>

<div style="margin-top:25px;">
     <a href="https://twitter.com/PhotoRankr" class="twitter-follow-button" data-show-count="false" data-size="large">Follow @PhotoRankr</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></div>
       
        <div style="margin-top:25px;margin-left:130px;" class="fb-like" data-href="http://www.photorankr.com" data-send="false" data-width="450" data-show-faces="false" data-font="lucida grande"></div>

<div style="margin-top:25px;">     
        <script src="//platform.linkedin.com/in.js" type="text/javascript"></script>
<script type="IN/FollowCompany" data-id="2597865" data-counter="right"></script></div>
       
       
 

</div>
 </div>
  
  
</div> <!--container end-->   
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

<!--FBOOK SCRIPTS-->                
 <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=433110216717524";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
               
</body>
</html>
        