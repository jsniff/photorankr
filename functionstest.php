<?php

function navbartest() {
echo'
<link rel="stylesheet" href="css/style.css" type="text/css"/> 
<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container" style="height:50px;width:1040px;">
				<ul class="nav" style="height:50px;">
					
                <li> <a href="newsfeed.php">  <img src="graphics/follower.png" height="25" />  News </a> </li>
					<li class="dropdown topcenter"'; if($_SERVER['REQUEST_URI'] == '/newest3.php') {echo'style="color:white;"';} echo'id="accountmenu">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#"> <img src="graphics/follower.png" height="25" /> Photos </b></a>
							<ul class="dropdown-menu" style="margin-top:0px;background-color:#fff;">
                                <li> <a href="trending.php">Trending </a></li>
								<li> <a href="newest.php"> Newest </a></li>
								<li class="divider"></li> 	<li> <a href="topranked.php"> Top Ranked </a></li>';
                                
                                
  //get the users information from the database
  $email = $_SESSION['email'];
  
  $likesquery = "SELECT * FROM userinfo WHERE emailaddress='$email'";
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
                                
                        echo'
								<li> <a href="discover.php?image=',$discoverimage,'"> Discover </a> </li>
							</ul>
						</li>
						<li class="dropdown topcenter" id="accountmenu">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">  <img src="graphics/follower.png" height="25" />  Market</b> </a>
							<ul class="dropdown-menu" style="margin-top:0px;background-color:#fff;">
								<li> <a href="marketplace.php"> Marketplace </a></li>
								<li> <a href="viewcampaigns.php"> Campaigns </a></li>
							</ul>
						</li>
                        <li> <a href="/blog/post"> <img src="graphics/follower.png" height="25" />  Blog </a> </li>
                        
                        <li class="margint"> <a href="index.php"><img class="logo" src="graphics/aperture_white.png" style="position:relative;left:50px;height:35px;margin-top:-5px;padding-right:0px;"/></a></li>
                        
                        <li class="margint" style="margin-left:100px;"> <form class="navbar-search" action="search.php" method="get">
<input class="search3 margint marginl" style="height:1.4em;margin-top:2px;margin-left:0em;padding-right:25px;font-family:helvetica;font-size:13px;font-weight:100;color:black;" name="searchterm" type="text" placeholder="Search for photos & people">
</form></li>';
                        
                        if($_SESSION['loggedin'] != 1) {
                        
                        echo'<li class="dropdown topcenter " id="accountmenu">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="font-family:helvetica;"> Log In </b></a>
                                <ul class="dropdown-menu" style="margin-top:0px;background-color:#fff;width:192px;">
								<li><a style="color:#000;font-size:15px;" href="signin.php">Register for free today</a></li>
                                <li class="divider"></li>';
                            
                                    if(strpos($_SERVER['REQUEST_URI'],'?') !== false) {
                                        echo'<form name="login_form" method="post" action="',htmlentities($_SERVER['REQUEST_URI']),'&action=login">';
                                    }   
                                    else {
                                         echo'<form name="login_form" method="post" action="',htmlentities($_SERVER['REQUEST_URI']),'?action=login">';
                                    }                                
                                echo'
                                <li style="margin-left:15px;color:#000;float:left;">Email: </li>
                                <li><input type="text" style="width:150px;margin-top:3px;margin-left:15px;float:left;" name="emailaddress" /></li>
                                <li><span style="float:left;margin-left:15px;color:#000;float:left;">Password: </li>
                                <input type="password" style="width:150px;margin-top:3px;margin-left:15px;float:left;" name="password"/></li>
                                <li style="margin-left: 110px;float:left;"><input type="submit" class="btn btn-success" value="Sign In" id="loginButton"/></li>
                                </form>
								</ul>
						</li>';
                        
                        }
                        
                        elseif($_SESSION['loggedin'] == 1) {
                            $email = $_SESSION['email'];
                            
                            $profilequery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$email'");
                            $profilepic = mysql_result($profilequery,0,'profilepic');
                        
                        echo'
						<li class="dropdown"  id="accountmenu">';
                                                            
                                //QUERY FOR NOTIFICATION COUNT
                                $currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$email'";
                                $currentnotsquery = mysql_query($currentnots);
                                $currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");

                        echo'
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"> <div class="notifications" style="margin-top:-2px;"><div style="position:relative;top:4px;color:#6aae45;left:10px;font-size:13px;font-weight:bolder;">',$currentnotsresult,'</div></div> </a>
								<ul class="dropdown-menu" style="margin-top:0px;margin-left:-255px;background-color:#fff;">';
								
                                //NOTIFICATIONS
                                
$emailquery=("SELECT following FROM userinfo WHERE emailaddress ='$email'");
$followresult=mysql_query($emailquery);
$followinglist=mysql_result($followresult, 0, "following");

$blogquery = mysql_query("SELECT id FROM blog WHERE emailaddress ='$email'");
$blogidlist = mysql_result($blogquery, 0, "id");

$notsquery = "SELECT * FROM newsfeed WHERE (owner = '$email' AND emailaddress != '$email') OR following = '$email' ORDER BY id DESC";
$notsresult = mysql_query($notsquery);
$numnots = mysql_num_rows($notsresult);

$ctype = 'campaign';
$campaignnews = "SELECT * FROM newsfeed WHERE type = '$ctype' ORDER BY id DESC";
$campaignnewsquery = mysql_query($campaignnews);
$numcamps = mysql_num_rows($campaignnewsquery);

$cetype = 'campaignended';
$campaignendednews = "SELECT * FROM newsfeed WHERE type = '$cetype' AND campaignentree LIKE '%$email%' ORDER BY id DESC";
$campaignendednewsquery = mysql_query($campaignendednews);
$numendcamps = mysql_num_rows($campaignendednewsquery);

$fetype = 'feedback';
$campaignfeedbacknews = "SELECT * FROM newsfeed WHERE type = '$fetype' AND campaignentree LIKE '%$email%' ORDER BY id DESC";
$campaignfeedbacknewsquery = mysql_query($campaignfeedbacknews);
$numfeedcamps = mysql_num_rows($campaignfeedbacknewsquery);

//DECIDE WHICH NOTIFICATIONS TO WHITEN (ONES ALREADY CLICKED ON)
$unhighlightquery = "SELECT * FROM userinfo WHERE emailaddress = '$email'";
$unhighlightqueryrun = mysql_query($unhighlightquery);
$whitenlist=mysql_result($unhighlightqueryrun, 0, "unhighlight");


if($numnots > 1) {
echo'<div style="width:450px;height:350px;overflow-y:scroll;font-size:12px;">';

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


//SEARCH IF ID IS IN UNHIGHLIGHT LIST
$search_string = $whitenlist;
$regex = $id;
$match=strpos($whitenlist,$regex);


if($typecamp) {
$caption4 = $campaignarray['caption'];
$source= $campaignarray['source'];
$quotecampquery = mysql_query("SELECT quote FROM campaigns WHERE id = '$source'");
$quotecamp = mysql_result($quotecampquery, 0, "quote");
$phrase = 'New Campaign: (Reward $' . $quotecamp . ')  "' . $caption4 . '"';
$phrase = (strlen($phrase) > 56) ? substr($phrase,0,53). " &#8230;" : $phrase;
    
$newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
echo'<a style="text-decoration:none" href="campaignphotos.php?id=',$source,'&newsid=',$id,'"><div id="greenshadow"><img style="float:left;padding:5px;" src="graphics/smallcampaignicon.png" height="50" width="50" />&nbsp;<div style="color:black;float:left;margin-top:20px;margin-left:10px;">',$phrase,'</div></div></a><hr>';
}

if($typecampended) {
$caption4 = $campaignendedarray['caption'];
$source= $campaignendedarray['source'];
$phrase = 'Campaign Winner Picked: "'.$caption4.'"';
$phrase = (strlen($phrase) > 56) ? substr($phrase,0,53). " &#8230;" : $phrase;
    
$newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
echo'<a style="text-decoration:none" href="campaignphotos.php?id=',$source,'&newsid=',$id,'"><div id="greenshadow"><img style="float:left;padding:5px;" src="graphics/smallcampaignicon.png" height="50" width="50" />&nbsp;<div style="color:black;float:left;margin-top:20px;margin-left:10px;">',$phrase,'</div></div></a><hr>';
}

if($typecampfeedback) {
$caption4 = $campaignfeedbackarray['caption'];
$source= $campaignfeedbackarray['source'];
$phrase = 'Campaign Feedback: "'.$caption4.'"';
$phrase = (strlen($phrase) > 56) ? substr($phrase,0,53). " &#8230;" : $phrase;
    
$newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
echo'<a style="text-decoration:none" href="campaignphotos.php?id=',$source,'&newsid=',$id,'"><div id="greenshadow"><img style="float:left;padding:5px;" src="graphics/smallcampaignicon.png" height="50" width="50" />&nbsp;<div style="color:black;float:left;margin-top:20px;margin-left:10px;">',$phrase,'</div></div></a><hr>';
}

if($match < 1) {

if($type == "comment") {
$caption4 = $notsarray['caption'];
$source= $notsarray['source'];
$newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
echo'<a style="text-decoration:none" href="fullsize.php?image=',$source,'&id=',$id,'"><div id="greenshadowhighlight"><img style="float:left;padding:5px;" src="http://www.photorankr.com/',$newsource,'" height="50" width="50" />&nbsp;<div style="color:black;float:left;margin-top:20px;margin-left:10px;">',$fullname4,' commented on your photo</div></div></a><hr>';
}

elseif($type == "blogcomment") {
$blogcommenteremail = $notsarray['emailaddress'];
$source= $notsarray['source'];
$blogcommenterquery = mysql_query("SELECT profilepic,firstname,lastname FROM userinfo WHERE emailaddress = '$blogcommenteremail'");
$blogcommenterpic = mysql_result($blogcommenterquery,0,'profilepic');
$blogcommentername = mysql_result($blogcommenterquery,0,'firstname') ." ". mysql_result($blogcommenterquery,0,'lastname');

echo'<a style="text-decoration:none" href="myprofile.php?view=blog&bi=',$source,'#',$source,'"><div id="greenshadowhighlight"><img style="float:left;padding:5px;" src="',$blogcommenterpic,'" height="50" width="50" />&nbsp;<div style="color:black;float:left;margin-top:20px;margin-left:10px;">',$blogcommentername,' commented on your blog post</div></div></a><hr>';
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
echo'<a style="text-decoration:none" href="myprofile.php?view=viewthread&thread=',$thread,'&id=',$id,'"><div id="greenshadowhighlight"><img style="float:left;padding:5px;" src="',$profilepic4,'" height="50" width="50" />&nbsp;<div style="color:black;float:left;margin-top:20px;margin-left:10px;">',$fullname4,' sent you a message</div></div></a><hr>';
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
echo'<a style="text-decoration:none" href="myprofile.php?view=viewthread&thread=',$thread,'&id=',$id,'"><div id="greenshadowhighlight"><img style="float:left;padding:5px;" src="',$profilepic4,'" height="50" width="50" />&nbsp;<div style="color:black;float:left;margin-top:20px;margin-left:10px;">',$fullname4,' replied to your message</div></div></a><hr>';
}

elseif($type == "fave") {
$caption4 = $notsarray['caption'];
$source= $notsarray['source'];
$newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
echo'<a style="text-decoration:none" href="fullsize.php?image=',$source,'&id=',$id,'"><div id="greenshadowhighlight"><img  style="float:left;padding:5px;" src="http://www.photorankr.com/',$newsource,'" height="50" width="50" />&nbsp;<div style="color:black;float:left;margin-top:20px;margin-left:10px;">',$fullname4,' favorited your photo</div></div></a><hr>';
}

elseif($type == "trending") {
$caption4 = $notsarray['caption'];
$source= $notsarray['source'];
$newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
echo'<a style="text-decoration:none" href="fullsize.php?image=',$source,'&id=',$id,'"><div id="greenshadowhighlight"><img style="float:left;padding:5px;" src="http://www.photorankr.com/',$newsource,'" height="50" width="50" />&nbsp;<div style="color:black;float:left;margin-top:20px;margin-left:10px;">Your photo is now trending</div></div></a><hr>';
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
echo'<a style="text-decoration:none" href="viewprofile.php?u=',$ownerid,'&id=',$id,'"><div id="greenshadowhighlight"><img style="float:left;padding:5px;" src="',$profilepic4,'" height="50" width="50" />&nbsp;<div style="color:black;float:left;margin-top:20px;margin-left:10px;">',$fullname4,' is now following your photography</div></div></a><hr>';
}
} //end if statement

elseif($match > 0) {

if($type == "comment") {
$caption4 = $notsarray['caption'];
$source= $notsarray['source'];
$newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
echo'<a style="text-decoration:none" href="fullsize.php?image=',$source,'&id=',$id,'"><div id="greenshadow"><img style="float:left;padding:5px;" src="http://www.photorankr.com/',$newsource,'" height="50" width="50" />&nbsp;<div style="color:black;float:left;margin-top:20px;margin-left:10px;">',$fullname4,' commented on your photo</div></div></a><hr>';
}

elseif($type == "blogcomment") {
$blogcommenteremail = $notsarray['emailaddress'];
$source= $notsarray['source'];
$blogcommenterquery = mysql_query("SELECT profilepic,firstname,lastname FROM userinfo WHERE emailaddress = '$blogcommenteremail'");
$blogcommenterpic = mysql_result($blogcommenterquery,0,'profilepic');
$blogcommentername = mysql_result($blogcommenterquery,0,'firstname') ." ". mysql_result($blogcommenterquery,0,'lastname');

echo'<a style="text-decoration:none" href="myprofile.php?view=blog&bi=',$source,'#',$source,'"><div id="greenshadow"><img style="float:left;padding:5px;" src="',$blogcommenterpic,'" height="50" width="50" />&nbsp;<div style="color:black;float:left;margin-top:20px;margin-left:10px;">',$blogcommentername,' commented on your blog post</div></div></a><hr>';
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
echo'<a style="text-decoration:none" href="myprofile.php?view=viewthread&thread=',$thread,'&id=',$id,'"><div id="greenshadow"><img style="float:left;padding:5px;" style="padding-bottom:5px;" src="',$profilepic4,'" height="50" width="50" />&nbsp;<div style="color:black;float:left;margin-top:20px;margin-left:10px;">',$fullname4,' sent you a message<div></div></a><hr>';
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
echo'<a style="text-decoration:none" href="myprofile.php?view=viewthread&thread=',$thread,'&id=',$id,'"><div id="greenshadow"><img style="float:left;padding:5px;" src="',$profilepic4,'" height="50" width="50" />&nbsp;<div style="color:black;float:left;margin-top:20px;margin-left:10px;">',$fullname4,' replied to your message</div></div></a><hr>';
}

elseif($type == "fave") {
$caption4 = $notsarray['caption'];
$source= $notsarray['source'];
$newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
echo'<a style="text-decoration:none" href="fullsize.php?image=',$source,'&id=',$id,'"><div id="greenshadow"><img style="float:left;padding:5px;" style="float:left;padding:5px;" src="http://www.photorankr.com/',$newsource,'" height="50" width="50" />&nbsp;<div style="color:black;float:left;margin-top:20px;margin-left:10px;">',$fullname4,' favorited your photo</span></div></a><hr>';
}

elseif($type == "trending") {
$caption4 = $notsarray['caption'];
$source= $notsarray['source'];
$newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
echo'<a style="text-decoration:none" href="fullsize.php?image=',$source,'&id=',$id,'"><div id="greenshadow"><img style="float:left;padding:5px;" src="http://www.photorankr.com/',$newsource,'" height="50" width="50" />&nbsp;<div style="color:black;float:left;margin-top:20px;margin-left:10px;">Your photo is now trending</div></div></a><hr>';
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
echo'<a style="text-decoration:none" href="viewprofile.php?u=',$ownerid,'&id=',$id,'"><div id="greenshadow"><img style="float:left;padding:5px;" src="',$profilepic4,'" height="50" width="50" />&nbsp;<div style="color:black;float:left;margin-top:20px;margin-left:10px;">',$fullname4,' is now following your photography</div></a><hr></span>';
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
							</li>
						<li class="dropdown topcenter marginT" id="accountmenu" style="position:relative;">
							<a style="text-decoration:none;" class="dropdown-toggle" data-toggle="dropdown" href="myprofile.php"><img class="roundedall" src="',$profilepic,'" style="width:30px;height:30px;"/></a>
								<ul class="dropdown-menu" style="margin-top:0px;background-color:#fff;width:150px;">
                                    <li> <a href="myprofile.php?view=upload"> Upload </a> </li>
                                    <li class="divider"></li>
                                    <li> <a href="myprofile.php"> Portfolio </a> </li>
                                    <li class="divider"></li>
                                    <li> <a href="myprofile.php?view=store"> My Store </a> </li>
                                    <li class="divider"></li>
                                    <li> <a href="myprofile.php?view=blog"> My Blog</a> </li>
                                    <li class="divider"></li>
									<li> <a href="myprofile.php?view=settings"> Settings </a> </li>
									<li class="divider"></li>';
                                    if(strpos($_SERVER['REQUEST_URI'],'myprofile.php') !== false) {
                                        echo'<li> <a href="newest.php?action=logout"> Log Out </a> </li>';
                                    }
                                    elseif(strpos($_SERVER['REQUEST_URI'],'?') !== false) {
                                        echo'<li> <a href="',$_SERVER['REQUEST_URI'],'&action=logout"> Log Out </a> </li>';
                                    }   
                                    else {
                                         echo'<li> <a href="',$_SERVER['REQUEST_URI'],'?action=logout"> Log Out </a> </li>';
                                    }
                                    echo'
								</ul>	
							</li>	
                                                <li><a style="margin-top:8px;height:4px;padding-bottom:12px;width:35px;color:white;font-size:12px;font-family:helvetica,arial;font-weight:200;" class="btn btn-success" href="myprofile.php?view=upload"><div style="margin-top:-5px;margin-left:-2px;">Upload</div></a></li>	
                                                
					</ul>';
                    
                            }
                
                    echo'
				</div>
			</div>	
		</div>
	</div>';
}

function footer() {

echo'
<link rel="stylesheet" href="css/all.css" type="text/css"/> 
<div class="navbar-fixed-bottom" style="width:100%;background:#ccc;box-shadow: inset 1px 1px 1px #999;">
	<div class="container_24">
	<div class="grid_24" style="margin-top:.1em;">	
	<div class="grid_18 push_2">
		<ul class="footer">
			<li> <a href="about.php"><div class="footer_grid"> About </div> </a> </li>
			<li> <a href="contact.php"><div class="footer_grid">Conatct Us </div></a> </li>
			<li> <a href="help.php"><div class="footer_grid">Help/FAQ </div></a> </li>
			<li> <a href="terms.php"><div class="footer_grid">Terms </div></a> </li>
			<li> <a href="privacy.php"><div class="footer_grid">Privacy Policy </div></a> </li>
			<li> <a href="blog/post"><div class="footer_grid">Blog  </div></a></li>
			<li> <a href="press.php"><div class="footer_grid">Press </div></a></li>
		</ul>
	</div>	
		<div class="grid_4 pull_1" style="margin: 1em 3em 0 0 ">
			<div class="grid_1" style="float:right;"><a class="twitter" href="https://twitter.com/photorankr"><img src="graphics/twitter.png"/> </a>
			</div>
			<div class="grid_1" style="float:right;"><a class="twitter" href="http://www.facebook.com/pages/PhotoRankr/140599622721692"><img src="graphics/facebook.png"/> </a>
			</div>
			<div class="grid_1" style="float:right;"><a class="twitter" href="https://plus.google.com/102253183291914861528/posts"><img src="graphics/g+.png"/> </a>
			</div>
	</div>
<div class="grid_24">
	<p class="copyright" style="margin-top:1em;">PhotoRankr is a trademark of PhotoRankr, Inc. The PhotoRankr Logo is a trademark of PhotoRankr, Inc. </p>
<p class="copyright" style="margin-top:-1.5em;margin-bottom:1em;">Copyright &copy 2012 PhotoRankr, Inc.</p>
</div>
</div>';

}



function navbarnots() {
echo'
<link rel="stylesheet" href="css/style.css" type="text/css"/> 
<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container" style="height:50px;width:1040px;">
				<ul class="nav" style="height:50px;">
					<li class="margint"> <a href="index.php"><img class="logo" src="graphics/coollogo.png" style="position:relative;left:-10px;height:45px;width:186px;margin-top:-8px;padding-right:20px;"/></a></li>
					<li class="margint" style="margin-left:-35px;"> <form class="navbar-search" action="search.php" method="get">
<input class="search3 margint marginl" style="height:1.4em;padding-right:20px;margin-top:2px;margin-left:5em;margin-right:5.5em;font-family:helvetica;font-size:13px;font-weight:100;color:black;" name="searchterm" type="text" placeholder="Search for photos & people">
</form></li>
					<li> <a href="newsfeed.php"> News </a> </li>
					<li class="dropdown topcenter"'; if($_SERVER['REQUEST_URI'] == '/newest3.php') {echo'style="color:white;"';} echo'id="accountmenu">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#"> Photos </b></a>
							<ul class="dropdown-menu" style="margin-top:0px;background-color:#fff;">
                                <li> <a href="trending.php"> Trending </a></li>
								<li> <a href="newest.php"> Newest </a></li>
								<li class="divider"></li> 	<li> <a href="topranked.php"> Top Ranked </a></li>';
                                
                                
  //get the users information from the database
  $email = $_SESSION['email'];
  
  $likesquery = "SELECT * FROM userinfo WHERE emailaddress='$email'";
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
                                
                        echo'
								<li> <a href="discover.php?image=',$discoverimage,'"> Discover </a> </li>
							</ul>
						</li>
						<li class="dropdown topcenter" id="accountmenu">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"> Market</b> </a>
							<ul class="dropdown-menu" style="margin-top:0px;background-color:#fff;">
								<li> <a href="marketplace.php"> Marketplace </a></li>
								<li> <a href="viewcampaigns.php"> Campaigns </a></li>
							</ul>
						</li>
                        <li> <a href="/blog/post"> Blog </a> </li>';
                        
                        if($_SESSION['loggedin'] != 1) {
                        
                        echo'<li class="dropdown topcenter " id="accountmenu">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="font-family:helvetica;"> Log In </b></a>
                                <ul class="dropdown-menu" style="margin-top:0px;background-color:#fff;width:192px;">
								<li><a style="color:#000;font-size:15px;" href="signin.php">Register for free today</a></li>
                                <li class="divider"></li>';
                            
                                    if(strpos($_SERVER['REQUEST_URI'],'?') !== false) {
                                        echo'<form name="login_form" method="post" action="',htmlentities($_SERVER['REQUEST_URI']),'&action=login">';
                                    }   
                                    else {
                                         echo'<form name="login_form" method="post" action="',htmlentities($_SERVER['REQUEST_URI']),'?action=login">';
                                    }                                
                                echo'
                                <li style="margin-left:15px;color:#000;float:left;">Email: </li>
                                <li><input type="text" style="width:150px;margin-top:3px;margin-left:15px;float:left;" name="emailaddress" /></li>
                                <li><span style="float:left;margin-left:15px;color:#000;float:left;">Password: </li>
                                <input type="password" style="width:150px;margin-top:3px;margin-left:15px;float:left;" name="password"/></li>
                                <li style="margin-left: 110px;float:left;"><input type="submit" class="btn btn-success" value="Sign In" id="loginButton"/></li>
                                </form>
								</ul>
						</li>';
                        
                        }
                        
                        elseif($_SESSION['loggedin'] == 1) {
                            $email = $_SESSION['email'];
                            
                            $profilequery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$email'");
                            $profilepic = mysql_result($profilequery,0,'profilepic');
                            $sessionname = mysql_result($profilequery,0,'firstname')." ".mysql_result($profilequery,0,'lastname');
                            $sessionname = (strlen($sessionname) > 13) ? substr($sessionname,0,10). "&#8230;" : $sessionname;
                            
                        //Campaign Notifications
                        
                                $campnots = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$email'");
                                $campnotsresult = mysql_result($campnots, 0, "campaign_notifications");
                                
                        echo'
                        
						<li class="dropdown"  id="accountmenu">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><img src="graphics/campaignnots.png" height="15" />';
                        
                        if($campnotsresult > 0) {
                                
                                echo'
                                <div style="position:relative;top:-27px;color:red;left:12px;font-size:13px;font-weight:bolder;">',$campnotsresult,'</div>';
                            
                            }
                        
                        echo'</a><ul class="dropdown-menu" style="margin-top:3px;margin-left:-215px;background-color:#fff;">';
                        
                        $campaignnews = mysql_query("SELECT * FROM newsfeed WHERE type = 'campaign' OR type = 'campaignended' OR type = 'feedback' ORDER BY id DESC");
                        $numcamps = mysql_num_rows($campaignnews);
                       
                        echo'<div style="width:450px;height:350px;overflow-y:scroll;font-size:12px;font-family:helvetica neue,helvetica,arial;font-weight:200;">';
                         
                        for($iii=1; $iii <= 20; $iii++) {
                            
                            $typecampaign = mysql_result($campaignnews,$iii,'type');
                            $caption = mysql_result($campaignnews,$iii,'caption');
                            $source= mysql_result($campaignnews,$iii,'source');
                            $id= mysql_result($campaignnews,$iii,'id');
                            $newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
                            $campaignentrees = mysql_result($campaignnews,$iii,'campaignentree');
                            $entreematch = strpos($campaignentrees,$email);
                            
                            if($typecampaign == 'campaign') {
                                
                                $quotecampquery = mysql_query("SELECT quote FROM campaigns WHERE id = '$source'");
                                $quotecamp = mysql_result($quotecampquery, 0, "quote");
                                $phrase = 'New Campaign: <b>(Reward $' . $quotecamp . ')  "</b>' . $caption . '"';
                                $phrase = (strlen($phrase) > 56) ? substr($phrase,0,53). " &#8230;" : $phrase;
                                $coverphotoquery = mysql_query("SELECT source FROM campaignphotos WHERE campaign = '$source' ORDER BY (points/votes) DESC LIMIT 0,1");
                                $coverphoto = mysql_result($coverphotoquery,0,'source');
                                $coverphoto = str_replace('userphotos/','market/userphotos/medthumbs/',$coverphoto);

                                echo'<a style="text-decoration:none" href="campaignphotos.php?id=',$source,'&newsid=',$id,'"><div id="greenshadow"><img style="float:left;padding:5px;" src="',$coverphoto,'" height="50" width="50" />&nbsp;<div style="float:left;margin-top:20px;margin-left:10px;">',$phrase,'</div></div></a>';
                            }

                            elseif($typecampaign == 'campaignended' && $entreematch > 0) {
                            
                                $phrase = '<b>Campaign Winner Picked:</b> "'.$caption.'"';
                                $phrase = (strlen($phrase) > 56) ? substr($phrase,0,53). " &#8230;" : $phrase;
                                $selectwinner = mysql_query("SELECT winneremail FROM campaigns WHERE id = '$source'");
                                $winneremail = mysql_result($selectwinner,0,'winneremail');
                                $getwinnerpic = mysql_query("SELECT profilepic FROM userinfo WHERE emailaddress = '$winneremail'");
                                $winnerpic = mysql_result($getwinnerpic,0,'profilepic');
                                
                                echo'<a style="text-decoration:none" href="campaignphotos.php?id=',$source,'&newsid=',$id,'"><div id="greenshadow"><img style="float:left;padding:5px;" src="',$winnerpic,'" height="50" width="50" />&nbsp;<div style="float:left;margin-top:20px;margin-left:10px;">',$phrase,'</div></div></a>';
                            }

                            elseif($typecampaign == 'feedback' && $entreematch > 0) {

                                $phrase = '<b>Campaign Feedback:</b> "'.$caption.'"';
                                $phrase = (strlen($phrase) > 56) ? substr($phrase,0,53). " &#8230;" : $phrase;
                                $coverphotoquery = mysql_query("SELECT source FROM campaignphotos WHERE campaign = '$source' ORDER BY (points/votes) DESC LIMIT 0,1");
                                $coverphoto = mysql_result($coverphotoquery,0,'source');
                                $coverphoto = str_replace('userphotos/','market/userphotos/medthumbs/',$coverphoto);
                                
                                echo'<a style="text-decoration:none" href="campaignphotos.php?id=',$source,'&newsid=',$id,'"><div id="greenshadow"><img style="float:left;padding:5px;" src="',$coverphoto,'" height="50" width="50" />&nbsp;<div style="float:left;margin-top:20px;margin-left:10px;">',$phrase,'</div></div></a>';
                            }

                        }
                        
                        echo'
                        </div>
                        </ul>
                        </li>';

                        
                        //All Other Notifications
                        echo'
						<li class="dropdown"  id="accountmenu">';
                                                            
                                //QUERY FOR NOTIFICATION COUNT
                                
                                $currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$email'";
                                $currentnotsquery = mysql_query($currentnots);
                                $currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");

                        echo'
                        
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><img src="graphics/notification.png" height="25" />';
                            
                            if($currentnotsresult > 0) {
                                
                                echo'
                                <div style="position:relative;top:-27px;color:red;left:12px;font-size:13px;font-weight:bolder;">',$currentnotsresult,'</div>';
                            
                            }   
                            
                                //NOTIFICATIONS
                                
                                $emailquery = mysql_query("SELECT following FROM userinfo WHERE emailaddress ='$email'");
                                $followinglist = mysql_result($emailquery, 0, "following");

                                $blogquery = mysql_query("SELECT id FROM blog WHERE emailaddress ='$email'");
                                $blogidlist = mysql_result($blogquery, 0, "id");

                                $notsquery = mysql_query("SELECT * FROM newsfeed WHERE (owner = '$email' AND emailaddress != '$email') OR following = '$email' ORDER BY id DESC");  
                                $numnots = mysql_num_rows($notsquery);
                    
                            if($numnots > 1) {
                            
                            echo'<ul class="dropdown-menu" style="margin-top:-1px;margin-left:-255px;background-color:#fff;">';
                                
                                //DECIDE WHICH NOTIFICATIONS TO WHITEN (ONES ALREADY CLICKED ON)
                                $unhighlightquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$email'");
                                $whitenlist=mysql_result($unhighlightquery, 0, "unhighlight");

                                
                                    echo'<div style="width:450px;height:350px;overflow-y:scroll;font-size:12px;font-family:helvetica neue,helvetica,arial;font-weight:200;">';

                                for($iii=1; $iii <= 20; $iii++) {
                                
                                    $firstname = mysql_result($notsquery,$iii,'firstname');
                                    $lastname = mysql_result($notsquery,$iii,'lastname');
                                    $fullname = $firstname . " " . $lastname;
                                    $fullname = ucwords($fullname);
                                    $type = mysql_result($notsquery,$iii,'type');
                                    $id = mysql_result($notsquery,$iii,'id');
                                    $caption = mysql_result($notsquery,$iii,'caption');
                                    $source = mysql_result($notsquery,$iii,'source');
                                    $newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
                                    $blogcommenteremail = mysql_query($notsquery,$iii,'emailaddress');
                                    $followeremail = mysql_query($notsquery,$iii,'emailaddress');
                                    $ownermessage = mysql_result($notsquery,$iii,'owner');
                                    $thread = mysql_result($notsquery,$iii,'thread');
                                    
                                    //SEARCH IF ID IS IN UNHIGHLIGHT LIST
                                    $match=strpos($whitenlist,$id);
            
                                    if($match < 1) {
                                        $highlightid = 'greenshadowhighlight';
                                    }
                                    
                                    elseif($match > 0) {
                                        $highlightid = 'greenshadow';
                                    }

                                        if($type == "comment") {

                                            echo'<a style="text-decoration:none" href="fullsize.php?image=',$source,'&id=',$id,'"><div id="',$highlightid,'"><img class="roundednot" style="float:left;padding:5px;" src="http://www.photorankr.com/',$newsource,'" height="50" width="50" />&nbsp;<div style="float:left;margin-top:20px;margin-left:10px;"><img src="graphics/comment.png" height="15" />&nbsp;&nbsp;&nbsp;<b>',$fullname,'</b> commented on your photo</div></div></a>';
                                            
                                        }

                                        elseif($type == "blogcomment") {

                                            $blogcommenterquery = mysql_query("SELECT profilepic,firstname,lastname FROM userinfo WHERE emailaddress = '$blogcommenteremail'");
                                            $blogcommenterpic = mysql_result($blogcommenterquery,0,'profilepic');
                                            $blogcommentername = mysql_result($blogcommenterquery,0,'firstname') ." ". mysql_result($blogcommenterquery,0,'lastname');

                                            echo'<a style="text-decoration:none" href="myprofile.php?view=blog&bi=',$source,'#',$source,'"><div id="',$highlightid,'"><img class="roundednot" style="float:left;padding:5px;" src="',$blogcommenterpic,'" height="50" width="50" />&nbsp;<div style="float:left;margin-top:20px;margin-left:10px;"><b>',$blogcommentername,'</b> commented on your blog post</div></div></a>';
                                            
                                        }

                                        elseif($type == "message") {

                                            $newaccount = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$ownermessage'");
                                            $profilepic = mysql_result($newaccount,0,'profilepic');
                                            if($profilepic == "") {
                                                $profilepic = "profilepics/default_profile.jpg";
                                            }

                                            echo'<a style="text-decoration:none" href="myprofile.php?view=viewthread&thread=',$thread,'&id=',$id,'"><div id="',$highlightid,'"><img class="roundednot" style="float:left;padding:5px;" src="',$profilepic,'" height="50" width="50" />&nbsp;<div style="float:left;margin-top:20px;margin-left:10px;"><img style="margin-left:-5px;" src="graphics/contact.png" height="13" />&nbsp;&nbsp;&nbsp;<b>',$fullname,'</b> sent you a message</div></div></a>';

                                        }

                                        elseif($type == "reply") {

                                            $newaccount = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$ownermessage'");
                                            $profilepic = mysql_result($newaccount,0,'profilepic');
                                            if($profilepic == "") {
                                                $profilepic = "profilepics/default_profile.jpg";
                                            }
                                        
                                            echo'<a style="text-decoration:none" href="myprofile.php?view=viewthread&thread=',$thread,'&id=',$id,'"><div id="',$highlightid,'"><img class="roundednot" style="float:left;padding:5px;" src="',$profilepic,'" height="50" width="50" />&nbsp;<div style="float:left;margin-top:20px;margin-left:10px;"><img style="margin-left:-5px;" src="graphics/contact.png" height="13" />&nbsp;&nbsp;&nbsp;<b>',$fullname,'</b> replied to your message</div></div></a>';

                                        }

                                        elseif($type == "fave") {

                                            echo'<a style="text-decoration:none" href="fullsize.php?image=',$source,'&id=',$id,'"><div id="',$highlightid,'"><img  class="roundednot" style="float:left;padding:5px;" src="http://www.photorankr.com/',$newsource,'" height="50" width="50" />&nbsp;<div style="float:left;margin-top:20px;margin-left:10px;"><img src="graphics/fave.png" height="18" />&nbsp;&nbsp;&nbsp;<b>',$fullname,'</b> favorited your photo</div></div></a>';

                                        }

                                        elseif($type == "trending") {

                                            echo'<a style="text-decoration:none" href="fullsize.php?image=',$source,'&id=',$id,'"><div id="',$highlightid,'"><img class="roundednot" style="float:left;padding:5px;" src="http://www.photorankr.com/',$newsource,'" height="50" width="50" />&nbsp;<div style="float:left;margin-top:20px;margin-left:10px;"><img src="graphics/trending.png" height="18" />&nbsp;&nbsp;&nbsp;Your photo is now trending</div></div></a>';

                                        }

                                        elseif($type == "follow") {

                                            $newaccount = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$followeremail'");
                                            $ownerid = mysql_result($newaccount,0,'user_id');
                                            $profilepic = mysql_result($newaccount,0,'profilepic');
                                            if($profilepic == "") {
                                                $profilepic = "profilepics/default_profile.jpg";
                                            }
                                            
                                            echo'<a style="text-decoration:none" href="viewprofile.php?u=',$ownerid,'&id=',$id,'"><div id="',$highlightid,'"><img class="roundednot" style="float:left;padding:5px;" src="',$profilepic,'" height="50" width="50" />&nbsp;<div style="float:left;margin-top:20px;margin-left:10px;"><img style="margin-left:-10px;" src="graphics/follower.png" height="19" />&nbsp;&nbsp;&nbsp;<b>',$fullname,'</b> is now following your photography</div></div></a>';

                                        }

                                
                                } //end of for loop
                                echo'</div>';
                        
                                echo'
								</ul>';
                                
                                } //numnots > 1
                                
                                echo'<ul></ul></li>
                                
                                
						<li class="dropdown topcenter marginT" id="accountmenu" style="position:relative;">
							<a style="text-decoration:none;" class="dropdown-toggle" data-toggle="dropdown" href="myprofile.php"><div class="profile" style="text-decoration:none;margin-top:-15px;padding:4px;padding-right:8px;"><a style="text-decoration:none;" href="myprofile.php"><img src="',$profilepic,'" style="width:30px;height:30px;"/><span style="font-size:13px;color:white;font-weight:200;">&nbsp;&nbsp;&nbsp;',$sessionname,'</span></a></div></a>
								<ul class="dropdown-menu" style="margin-top:0px;background-color:#fff;width:150px;">
                                    <li> <a href="myprofile.php?view=upload"> Upload </a> </li>
                                    <li class="divider"></li>
                                    <li> <a href="myprofile.php"> My Portfolio </a> </li>
                                    <li class="divider"></li>
                                    <li> <a href="myprofile.php?view=store"> My Store </a> </li>
                                    <li class="divider"></li>
                                    <li> <a href="myprofile.php?view=blog"> My Blog</a> </li>
                                    <li class="divider"></li>
									<li> <a href="myprofile.php?view=settings"> Settings </a> </li>
									<li class="divider"></li>';
                                    if(strpos($_SERVER['REQUEST_URI'],'myprofile.php') !== false) {
                                        echo'<li> <a href="newest.php?action=logout"> Log Out </a> </li>';
                                    }
                                    elseif(strpos($_SERVER['REQUEST_URI'],'?') !== false) {
                                        echo'<li> <a href="',$_SERVER['REQUEST_URI'],'&action=logout"> Log Out </a> </li>';
                                    }   
                                    else {
                                         echo'<li> <a href="',$_SERVER['REQUEST_URI'],'?action=logout"> Log Out </a> </li>';
                                    }
                                    echo'
								</ul>	
							</li>	
					</ul>';	
                    
                            }
                
                    echo'
				</div>
			</div>	
		</div>
	</div>';
}


?>
