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
      <title>PhotoRankr - Upload. Rank. Discover.</title>
      <meta name="viewport" content="width=1200" /> 
      <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
      
  <meta name="Description" content="PhotoRankr is the premier photographer's network. We allow photographers of all skill levels to sell and share their work. Create your photostream cutomized to what you want to see. Add photos to your favorites, rank them, and watch them trend. Build your portfolio with Photorankr.">


        <link rel="stylesheet" type="text/css" href="reset.css" />
        <link rel="stylesheet" type="text/css" href="text.css" />
        <link rel="stylesheet" type="text/css" href="bootstrapnew.css" />
        <link rel="stylesheet" type="text/css" href="960_24.css" />
        <link rel="stylesheet" type="text/css" href="smoothDivScroll3.css" />
        <link rel="stylesheet" media="all" type="text/css" href="Sign Up.css" />     
        <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>   
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <script type="text/javascript" src="bootstrap-dropdown.js"></script>
        <script src="jquery-ui-1.8.18.custom.min.js" type="text/javascript"></script>
        <script src="jquery.mousewheel.min.js" type="text/javascript"></script>
        <script src="jquery.smoothdivscroll-1.2-min.js" type="text/javascript"></script>
        
        <script type="text/javascript">
            // Initialize the plugin with no custom options
            $(document).ready(function () {
                              // None of the options are set
                              $("div#makeMeScrollable").smoothDivScroll({});
                              }); 
            </script>
        
        <style type="text/css">
            
            #makeMeScrollable
            {
                border:10px solid white;
                width:1150px;
                height:300px;
                position:relative;
                box-shadow: 1px 1px 8px 2px #999999;
                -webkit-box-shadow: 1px 1px 8px 2px #999999;
            }
            #makeMeScrollable div.scrollableArea img
            {
                z-index:0;
                position: relative;
                float: left;
                margin: 0;
                padding: 0;
                /* If you don't want the images in the scroller to be selectable, try the following
                 block of code. It's just a nice feature that prevent the images from
                 accidentally becoming selected/inverted when the user interacts with the scroller. */
                -webkit-user-select: none;
                -khtml-user-select: none;
                -moz-user-select: none;
                -o-user-select: none;
                user-select: none;
            } 
            
            .about:hover{
            opacity:.8;
            }
            
            .scrollpics
            {
                width:393px;
                height:300px;
            }
            
            .inner
            {
                position:relative;
                top:-150px;
            }
            
            .statoverlay
            {
                opacity:.0;
                filter:alpha(opacity=40);
                z-index:1;
                transition: opacity 2s;
         -moz-transition: opacity 1s;
         -webkit-transition: opacity 1s;
                -o-transition: opacity 1s;
            }
            
            .statoverlay:hover
            {
                opacity:.5;
                
            }
            
            .navbartext
            {
                font-family:Helvetica-light, Helvetica, Arial, sans-serif;
            }
            
            .titletext
            {
                font-size:50px;
                font-family:Helvetica-Light, Helvetica, Arial, sans-serif;
                color:rgb(182,195,205);
            }
            
            .titletextbig
            {
                font-size:40px;
                font-family:Helvetica-light, Helvetica, Arial, sans-serif;
                color:#1B9C74;
            }
            
            .signupbutton
            {
                width:265px;
                height:46px;
                margin-left:0px;
                margin-top:-65px;
                border-bottom-left-radius:10px;
                border-bottom-right-radius:10px;
                background-color:#1B628F;
                z-index:1;               
                transition: width .1s;
-moz-transition: box-shadow .01s; /* Firefox 4 */
-webkit-transition:  box-shadow .1s; /* Safari and Chrome */
-o-transition:  box-shadow .1s; /* Opera */
                box-shadow: inset 0 4px 6px rgba(255,255,255,.4);
            -moz-box-shadow: inset 0 4px 6px rgba(255,255,255,.4);
            -o-box-shadow: inset 0 4px 6px rgba(255,255,255,.4);
            -webkit-box-shadow: inset 0 4px 6px rgba(255,255,255,.4);
            }
            .signupbutton:hover
            {
                background-color:rgb(250,250,250);
                background-color:rgba(27,98,143,.8);
                box-shadow: inset 0 2px 4px rgba(0,0,0,.8);
   -moz-box-shadow: inset 0 2px 4px rgba(0,0,0,.8);
   -o-box-shadow: inset 0 2px 4px rgba(0,0,0,.8);
   -webkit-box-shadow: inset 0 2px 4px rgba(0,0,0,.8);
                
            }
            p.buttontext
            {
                padding:6px;
                text-align:center;
                color:rgb(255,255,255);
                font:helvetica-light,helvetica,arial,sans-serif;
                font-size:24px;z-index:1;
                border:none;
            }
        
            .footer
            {
                display:inline;
                color:rgb(56,85,103);
                margin-right:20px;
                font-size:14px;
            }
            
            </style>
            
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

<body style="min-width:1100px;" class="background">
        
        <!--Navbar begin-->
        <div class="navbar" style="padding-top:0px;min-width:1220px;z-index:10;font-size:16px;width:100%;">
            <div class="navbar-inner">
                <div class="container">
                    <ul class="nav">
                        <li style="margin-left:30px;"><a style="color:#fff;" class="brand" href="index.php"><div style="margin-top:-2px;"><img src="logo.png" width="160" /></div></a></li>
                       
                         <?php               
                     if($_SESSION['loggedin'] == 1) {
                     echo'
                     <li style="color:#fff;margin-top:1px;" class="dropdown active"><a style="color:rgb(56,85,103);" href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <span style="font-size:18px;padding-right:3px;color:#fff;margin-top:2px;"><span style="position:relative;top:4px"><i class="icon-exclamation-sign icon-white"></i></span> ',$currentnotsresult,'</a>
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
							<li><a style="color:#fff;margin-left:-29px;" href="signin.php">Register for free today</a></li>
							<li><br/></li>
							<form name="login_form" method="post" action="newest.php?action=login">
							<li style="margin-left: 5px; margin-right: 5px; width: 185px;"><span style="color: white; margin-bottom: 5px;margin-left:10px;">Email: <br /></span><input type="text" style="width:150px;margin-top:3px;margin-left:10px;" name="emailaddress" /></li>
							<li><span style="color:white;margin-left:-16px;">Password: <br /></span><input type="password" style="width:150px;margin-top:3px;margin-left:-16px;" name="password"/></li>
                        <li style="margin-left: 110px;"><input type="submit" class="btn btn-success" value="Sign In" id="loginButton"/></li>		
                        </form>				</ul>';
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
        
<div class="container_24">

    <div class="grid_24" style="padding-top:10px;padding-bottom:20px;margin-top:60px;">
     <h1 style="color:#8E9094;font-weight:100;font-family:Helvetica-Neue-Light,Helvetica-light,Helvetica,Arial,Sans-Serif;font-size30px;"> Share your photos with a community.&nbsp; Bring them to market. </h1>
    </div>   
    <br />
   <div class="grid_7 push_8" style="height:20px;margin-left:-30px;padding-bottom:15px;"> 
   <h1 style="color:#1B628F;margin-top:-20px;padding-left:20px;float:left;font-weight:600;font-family:Helvetica-Neue-Light,Helvetica-light,Helvetica,Arial,Sans-Serif;font-size30px;"> On </h1>
   <img src="graphics/logoteal.png" width="200px" height="40px" style="margin-top:-22px;float:right;"/>
   </div> 
 <div class="grid_20 pull_2" style="width:1000px;height:300px;padding:5px;border-radius:5px;margin-left:-20px;border:2px solid rgb(72,76,85) opacity:.5;">  
     <div id="makeMeScrollable">
       <div class="scrollingHotSpotLeft" style="display: block; opacity: 0;"></div>
                 <div class="scrollingHotSpotRight" style="opacity: 0;display:block;"></div>
                        <div class="scrollWrapper">
                            <div class="scrollableArea" style="width:250px">
                                <div>
                                    <img class="scrollpics" style="inline;" id="gnome" alt="Demo image" src="userphotos/medthumbs/1338838122dsc1425.jpg" />
                                        <div class="statoverlay" style="position:absolute;top:240px;background-color:black;width:393px;height:60px;"><p style="margin:5px;color:white;">"Metallic Green Bee on Cactus Flower"<br>By "Rick Hartigan"</br>Score: 9.4</p></div>
                                        </div>
                                
                                <div>
                                    <img class="scrollpics" style="inline;" id="gnome" alt="Demo image" src="userphotos/medthumbs/1339113141banff1of1.jpg" />
                                        <div class="statoverlay" style="position:absolute;top:240px;left:393px;background-color:black;width:393px;height:60px;"><p style="margin:5px;color:white;">"Banff"<br>By "Ryan VanDever"</br>Score: 9.4</p></div>
                                        </div>
                                
                                <div>
                                    <img class="scrollpics" style="inline;" id="gnome" alt="Demo image" src="userphotos/medthumbs/1337394489flyover-2.jpg
">
                                        <div class="statoverlay" style="position:absolute;top:240px;left:786px;background-color:black;width:393px;height:60px;"><p style="margin:5px;color:white;">"Barred Owl"<br>By "Mike Delgado"</br>Score: 8.5</p></div>
                                        </div>
                                
                                <div>
                                    <img class="scrollpics" style="inline;" id="gnome" alt="Demo image" src="userphotos/medthumbs/1337017711fb32a.jpg">
                                        <div class="statoverlay" style="position:absolute;top:240px;left:1179px;background-color:black;width:393px;height:60px;"><p style="margin:5px;color:white;">"Stunning Visitor"<br>By "William Brennan"</br>Score: 8.6</p></div>
                                        </div>
                                
                                <div>
                                    <img class="scrollpics" style="inline;" id="gnome" alt="Demo image" src="userphotos/medthumbs/streetsofcobh.jpg">
                                        <div class="statoverlay" style="position:absolute;top:240px;left:1572px;background-color:black;width:393px;height:60px;"><p style="margin:5px;color:white;">"Streets of Cobh"<br>By "Ryan Ferrera"</br>Score: 9.3</p></div>
                                        </div>
                                <div>
                                <img class="scrollpics" style="inline;" id="gnome" alt="Demo image" src="userphotos/medthumbs/nature3222012.jpg">
                                        <div class="statoverlay" style="position:absolute;top:240px;left:1572px;background-color:black;width:393px;height:60px;"><p style="margin:5px;color:white;">"Majayjay Falls"<br>By "Leandro Rivero"</br>Score: 7.9</p></div>
                                        </div>
                                 <div>
                                   <img class="scrollpics" style="inline;" id="gnome" alt="Demo image" src="userphotos/medthumbs/fb51.jpg">
                                        <div class="statoverlay" style="position:absolute;top:240px;left:1572px;background-color:black;width:393px;height:60px;"><p style="margin:5px;color:white;">"Female Bluedasher Smiling"<br>By "William Brennan"</br>Score: 8.0</p></div>
                                        </div>
                                    <div>    
                                    <img class="scrollpics" style="inline;" id="gnome" alt="Demo image" src="userphotos/medthumbs/1338221056ruta-7.jpg">
                                        <div class="statoverlay" style="position:absolute;top:240px;left:1572px;background-color:black;width:393px;height:60px;"><p style="margin:5px;color:white;">"Sunrise at Devil's Backbone"<br>By "Ricardo Cardenas"</br>Score: 9.3</p></div>
                                        </div>
                                    <div>
                                    <img class="scrollpics" style="inline;" id="gnome" alt="Demo image" src="userphotos/medthumbs/tripthelight.jpg">
                                        <div class="statoverlay" style="position:absolute;top:240px;left:1572px;background-color:black;width:393px;height:60px;"><p style="margin:5px;color:white;">"Lateralus"<br>By "Andy H Hems"</br>Score: 8.0</p></div>
                                        </div>
                                      <div>  
                                     <img class="scrollpics" style="inline;" id="gnome" alt="Demo image" src="userphotos/medthumbs/light.jpg ">
                                        <div class="statoverlay" style="position:absolute;top:240px;left:1572px;background-color:black;width:393px;height:60px;"><p style="margin:5px;color:white;">"Baywalk Sunset"<br>By Leandro Rivero"</br>Score: 8.5</p></div>
                                        </div>
                                                                                  
                                            </div>
                        </div>
                    </div>
                </div>
           <div class="grid_24 pull_2" style="padding-top:20px;padding-bottom:30px;z-index:2;">
           <div class="grid_1"></div>
            <div class="grid_21"> <img style="width:830px;height:180px;float:right;" src="graphics/equation4.png"/>
             </div>
                <div class="grid_3" style="float:right;margin-right:-20px;margin-top:-50px;">
                    <div style=" box-shadow: 1px 1px 8px 2px #999999;
                -webkit-box-shadow: 1px 1px 8px 2px #999999;background-color:#1B628F;margin-left:-10:px;opacity:1;width:265px;height:36px;border-top-left-radius:10px;border-top-right-radius:10px;">
                        <div p style="z-index:2;padding-top:6px;text-align:center;color:white;font-size:24px;opacity:1;width:260px;height:36px;border-top-left-radius:10px;border-radius-top-right:10px;">Sign Up Free<p>
                    </div>
                    </div>
                    <div style=" box-shadow: 1px 1px 8px 2px #999999;
                -webkit-box-shadow: 1px 1px 8px 2px #999999;padding-top:8px;padding-bottom:8px;margin-top:0px;width:265px;height:282px;background-color:rgb(255,255,255);z-index:-1;border-bottom-left-radius:10px;border-bottom-right-radius:10px;">
                       
                        
                         
                           <form action="signin.php?action=signuponboard" method="post" style="width:180px;margin-left:25px;">
                           <div>
                            <p style="font-size:14px;margin-top:0px;">&nbsp;First Name: <input style="font-size:13px;margin-bottom:8px;border-radius:5px;background-color:rgb(238,239,243);" type="text" name="firstname" placeholder="First Name" /> &nbsp;Last Name:</p>
                           </div> 
                            <input style="font-size:13px;margin-bottom:8px;border-radius:5px;background-color:rgb(238,239,243);" type="text" name="lastname" placeholder="Last Name" /><br />
                            <p style="font-size:14px;margin:0px;">&nbsp;E-mail address:</p>
                            <input style="font-size:13px;margin-bottom:8px;border-radius:5px;background-color:rgb(238,239,243);" type="text" name="email" placeholder="E-mail address" /><br />
                            <p style="font-size:14px;margin:0px;">&nbsp;Password:</p>
                            <input style="font-size:13px;border-radius:8px;background-color:rgb(238,239,243);" type="password" name="password" placeholder="Password" /><br />
                    </div>
                    <div>
                        <button class="signupbutton" type="submit" style="text-decoration:none;color:#fff;" ><p class="buttontext">Join PhotoRankr</p></button>
                    </div>
                    </form>
                    
                    
                      <div style="position:relative; top:16px;left:40px;"><a href="about.php"><img class="about" src="graphics/learnmore.png" height="30" width="188" /></a>
                      <br /><br />
                      <a href="https://www.facebook.com/pages/PhotoRankr/140599622721692"><img class="about" src="graphics/frontfbbutton.png" height="30" width="188" /></a>
                      <br /><br />
                       <a href="https://twitter.com/PhotoRankr" data-show-count="false" data-size="large"><img class="about" src="graphics/fronttwitterbutton.png" height="30" width="188" /></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                      </div>
                
                </div>

                
            </div> 
            
            
          
            
            <div class="grid_24 pull_2" style="height:240px;width:1177px;margin-left:-20px;margin-top:-220px;position:relative;z-index:2;padding-left:-20px;background-color:rgba(194,163,94,.2);border-radius:10px;
   -moz-box-shadow: 0 4px 6px rgba(0,0,0,.4);
   -o-box-shadow: 0 4px 6px rgba(0,0,0,.4);
   -webkit-box-shadow: 0 4px 6px rgba(0,0,0,.4);
   z-index:-1;">
                <div class="grid_5" style="float:left;">
                    <div class="square3">
                        <div class="box1">
                            <h1 class="content"> Discover </h1>
                             <br />
                            <div class="box2">
                                <p class="textbox"> PhotoRankr is about discovering new photos, and the chance to be discovered as a great photographer. Browse the latest uploads in the Newest Gallery. Or you can see the photos and photographers people are ranking and favoriting the most at the moment in the Trending Gallery. Watch as the best photos work their way up to the Top Ranked Gallery.</p>
                            </div>	
                        </div>
                    </div>
                </div>
                <div class="grid_15" style="float:right;">
                    <div class="square4">
                        <div class="box3">
                            <h1 class= "content1"> Grow </h1>
                            <div class="box4">
                             <br />
                                <p class="textbox"> PhotoRankr is a community of photographers. Follow other photographers to see their work; you'll be surprised with how many return the favor. Get valuable feedback from other members as they comment, rank, and favorite your photos. Enjoy a newsfeed of photographers you follow and a personalized viewing experience, all in your profile.   </p>
                        </div>
                    </div>
                </div>
            </div>
                <div class="grid_4 pull_1" style="float:right;">
                    <div class="square5">
                        <div class="box5">
                          
                            <h1 class= "content"> Store, Share, Sell </h1>
                            <div class="box6">
                             <br />
                                <p class="textbox"> PhotoRankr is a marketplace. We offer unlimited uploads, a maximum file size of 15mb per file, and the ability to name your own price on prints and digital copies you choose to sell. PhotoRankr lets you personalize your profile to make it an excellent reference place for your friends and potential clients. Even better: it's all free.  </p>
                            </div>
                        </div>
                    </div> 
                </div>
              <div class="grid_4" style="float:right;background-color:blue;"> 
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
            
            
             
            
            
