
<?php 

require("db_connection.php");
    
//GET FOLLOWERS 
$_SESSION['email']=$_POST['emailaddress'];
$emailquery=("SELECT following FROM userinfo WHERE emailaddress ='$email'");
$followresult=mysql_query($emailquery);
$followlist=mysql_result($followresult, 0, "following");
$followrow=mysql_fetch_array($followresult);
$following=$followrow['following'];

if($_GET['lastPicture']) {
	$newsfeedquery = "SELECT * FROM newsfeed WHERE id < ".$_GET['lastPicture']." AND owner in ($followlist) ORDER BY id DESC LIMIT 0, 10";
    $newsfeedresult = mysql_query($newsfeedquery);

$maxwidth = 400;

for($iii=1; $iii <= 14; $iii++) {
    $newsrow = mysql_fetch_array($newsfeedresult);
    $newsemail = $newsrow['emailaddress'];    
    $owner = $newsrow['owner'];
    $emailfollowing = $newsrow['following'];
    $id = $newsrow['id'];
    $type = $newsrow['type'];
    $isfollowing = strpos($followlist,$newsemail);
    $isfollowing2 = strpos($followlist,$owner);
    $isfollowing3 = strpos($followlist,$emailfollowing);
    
    if ($type == "campaign") {
    $photoid = $newsrow['source'];
	$caption = $newsrow['caption'];
    $phrase = 'New Campaign: "' . $caption . '"';
    $phrase2 = (strlen($phrase) > 56) ? substr($phrase,0,53). " &#8230;" : $phrase;
    
    echo '<div class="grid_3 push_1 fPic photoshadow" id="',$id,'" style="margin-top:30px;width:250px;height:295px;"> 
    <a style="text-decoration:none" href="http://www.photorankr.com/campaignphotos.php?id=',$photoid,'"><img onmousedown="return false" oncontextmenu="return false;" src="graphics/newsfeedcampaignicon.png" width="250" height="250" />
    <br /><div style="margin-top:5px;width:50px;height:0px;padding-left:2px;"><img src="graphics/smallcampaignicon.png" height="35" width="35" /></div><div style="color:#333;font-size:14px;font-family:arial,helvetica neue;padding-left:40px;text-align:left;">
    ',$phrase2,'</div></a>';
    echo '</div>';   
    }
    
    if ($isfollowing !== FALSE OR $isfollowing2 !== FALSE OR $isfollowing3 !== FALSE) {
        
    if ($type == "photo") {
	$image = $newsrow['source'];
    $imagethumb = str_replace("userphotos/","userphotos/medthumbs/", $image);
	$caption = $newsrow['caption'];
    $owner = $newsrow['emailaddress'];
    $ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$owner'";
    $ownerresult = mysql_query($ownersquery); 
    $ownerrow = mysql_fetch_array($ownerresult);
    $ownerfirst = $ownerrow['firstname'];
    $ownerlast = $ownerrow['lastname'];
    $ownerfull = $ownerfirst . " " . $ownerlast;
    $ownerfull = ucwords($ownerfull);
	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    $phrase = $ownerfull . " uploaded " . '"' . $caption . '"';
    $phrase2 = (strlen($phrase) > 56) ? substr($phrase,0,53). " &#8230;" : $phrase;
    
    echo '<div class="grid_3 push_1 fPic photoshadow" id="',$id,'" style="margin-top:30px;width:250px;height:295px;"> 
    <a style="text-decoration:none" href="http://www.photorankr.com/fullsize.php?image=',$image,'"><img onmousedown="return false" oncontextmenu="return false;" src="http://www.photorankr.com/',$imagethumb,'" width="250" height="250" />
    <br /><div style="margin-top:5px;width:50px;height:0px;padding-left:2px;"><img src="graphics/newsfeedarrow.png" height="35" width="35" /></div><div style="color:#333;font-size:14px;font-family:arial,helvetica neue;padding-left:40px;text-align:left;">
    ',$phrase2,'</div></a>';
    echo '</div>';  

	}
    
    elseif ($type == "exhibit") {
	$caption = $newsrow['caption'];
    $owner = $newsrow['emailaddress'];
    $newaccount = "SELECT * FROM userinfo WHERE emailaddress = '$owner'";
    $accountresult = mysql_query($newaccount); 
    $accountrow = mysql_fetch_array($accountresult);
    $ownerid = $accountrow['user_id'];
    $newssetquery = "SELECT * FROM sets WHERE owner = '$owner' AND title = '$caption' LIMIT 0,1";
    $newssetresult = mysql_query($newssetquery); 
    $newssetarray = mysql_fetch_array($newssetresult);
    $cover = $newssetarray['cover'];
    if($cover == '') {
    $cover = "profilepics/nocoverphoto.png";
    }
    $newssetid = $newssetarray['id'];
    $ownerrow = mysql_fetch_array($ownerresult);
    $firstname = $newsrow['firstname'];
    $firstname = ucwords($firstname);
    $lastname = $newsrow['lastname'];
    $lastname = ucwords($lastname);
    $ownerfull = $firstname . " " . $lastname;
    $phrase = $ownerfull . " created the exhibit " . '"' . $caption . '"';
    $phrase2 = (strlen($phrase) > 56) ? substr($phrase,0,53). " &#8230;" : $phrase;
    
    echo '<div class="grid_3 push_1 fPic photoshadow" id="',$id,'" style="margin-top:30px;width:250px;height:295px;"> 
    <a style="text-decoration:none" href="http://www.photorankr.com/viewprofile.php?u=',$ownerid,'&ex=y&set=',$newssetid,'"><img onmousedown="return false" oncontextmenu="return false;" src="http://www.photorankr.com/',$cover,'" width="250" height="250" />
    <br /><div style="margin-top:5px;width:50px;height:0px;padding-left:2px;"><img src="graphics/newsfeedarrow.png" height="35" width="35" /></div><div style="color:#333;font-size:14px;font-family:arial,helvetica neue;padding-left:40px;text-align:left;">
    ',$phrase2,'</div>';
    echo '</div></a>';  

	}
    
    elseif ($type == "fave") {
    $owner = $newsrow['owner'];
    $ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$owner'";
    $ownerresult = mysql_query($ownersquery); 
    $ownerrow = mysql_fetch_array($ownerresult);
    $ownerfirst = $ownerrow['firstname'];
    $ownerlast = $ownerrow['lastname'];
    $ownerfull = $ownerfirst . " " . $ownerlast;
    $ownerfull = ucwords($ownerfull);
    $firstname = $newsrow['firstname'];
    $firstname = ucwords($firstname);
    $lastname = $newsrow['lastname'];
    $lastname = ucwords($lastname);
    $image = $newsrow['source'];
    $imagethumb = str_replace("userphotos/","userphotos/medthumbs/", $image);
	$caption = $newsrow['caption'];
	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    $fullname = $firstname . " " . $lastname;
    $phrase = $fullname . " favorited " . '"' . $caption . '"' . " by " . $ownerfull;
    $phrase2 = (strlen($phrase) > 56) ? substr($phrase,0,53). " &#8230;" : $phrase;
    
    echo '<div class="grid_3 push_1 fPic photoshadow" id="',$id,'" style="margin-top:30px;width:250px;height:295px;"> 
    <a style="text-decoration:none" href="http://www.photorankr.com/fullsize.php?image=',$image,'"><img onmousedown="return false" oncontextmenu="return false;" src="http://www.photorankr.com/',$imagethumb,'" width="250" height="250" />
    <br /><div style="margin-top:5px;width:50px;height:0px;padding-left:2px;"><img src="graphics/newsfeedfavorite.png" height="35" width="35" /></div><div style="color:#333;font-size:14px;font-family:arial,helvetica neue;padding-left:40px;text-align:left;">
    ',$phrase2,'</div></a>';
    echo '</div>';   
    }
    
    elseif ($type == "campaign") {
    $photoid = $newsrow['source'];
	$caption = $newsrow['caption'];
    $phrase = 'New Campaign: "' . $photoid . '"';
    $phrase2 = (strlen($phrase) > 56) ? substr($phrase,0,53). " &#8230;" : $phrase;
    
    echo '<div class="grid_3 push_1 fPic photoshadow" id="',$id,'" style="margin-top:30px;width:250px;height:295px;"> 
    <a style="text-decoration:none" href="http://www.photorankr.com/campaignphotos.php?id=',$photoid,'"><img onmousedown="return false" oncontextmenu="return false;" src="graphics/newsfeedcampaignicon.png" width="250" height="250" />
    <br /><div style="margin-top:5px;width:50px;height:0px;padding-left:2px;"><img src="graphics/campaignicon.png" height="35" width="35" /></div><div style="color:#333;font-size:14px;font-family:arial,helvetica neue;padding-left:40px;text-align:left;">
    ',$phrase2,'</div></a>';
    echo '</div>';   
    }
    
    elseif ($type == "trending") {
    $owner = $newsrow['owner'];
    $ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$owner'";
    $ownerresult = mysql_query($ownersquery); 
    $ownerrow = mysql_fetch_array($ownerresult);
    $ownerfirst = $ownerrow['firstname'];
    $ownerlast = $ownerrow['lastname'];
    $ownerfull = $ownerfirst . " " . $ownerlast;
    $ownerfull = ucwords($ownerfull);
    $image = $newsrow['source'];
    $imagethumb = str_replace("userphotos/","userphotos/medthumbs/", $image);
	$caption = $newsrow['caption'];
	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    $phrase = '"' . $caption . '"' . " by " . $ownerfull . " is now trending";
    $phrase2 = (strlen($phrase) > 56) ? substr($phrase,0,53). " &#8230;" : $phrase;
    
    echo '<div class="grid_3 push_1 fPic photoshadow" id="',$id,'" style="margin-top:30px;width:250px;height:295px;"> 
    <a style="text-decoration:none" href="http://www.photorankr.com/fullsize.php?image=',$image,'"><img onmousedown="return false" oncontextmenu="return false;" src="http://www.photorankr.com/',$imagethumb,'" width="250" height="250" />
    <br /><div style="margin-top:5px;width:50px;height:0px;padding-left:2px;"><img src="graphics/newsfeedtrending.png" height="35" width="35" /></div><div style="color:#333;font-size:14px;font-family:arial,helvetica neue;padding-left:40px;text-align:left;">
    ',$phrase2,'</div></a>';
    echo '</div>';   
    }
    
    elseif ($type == "follow") {
    $email4 = $newsrow['following'];
    $newaccount = "SELECT * FROM userinfo WHERE emailaddress = '$email4'";
    $accountresult = mysql_query($newaccount); 
    $accountrow = mysql_fetch_array($accountresult);
    $profilepic5 = $accountrow['profilepic'];
    $ownerfirst = $accountrow['firstname'];
    $ownerlast = $accountrow['lastname'];
    $firstname = $newsrow['firstname'];
    $firstname = ucwords($firstname);
    $lastname = $newsrow['lastname'];
    $lastname = ucwords($lastname);
    $owner = $newsrow['owner'];
    $owner = ucwords($owner);
    if($profilepic5 == "") {
    $profilepic5 = "profilepics/default_profile.jpg";
    }

 echo '<div class="grid_3 push_1 fPic photoshadow" id="',$id,'" style="margin-top:30px;width:250px;height:295px;"> 
    <a style="text-decoration:none" href="http://www.photorankr.com/viewprofile.php?first=',$ownerfirst,'&last=',$ownerlast,'"><img src="',$profilepic5,'" width="250" height="250" />
    <br /><div style="margin-top:5px;width:50px;height:0px;padding-left:2px;"><img src="graphics/newsfeednewfollower.png" height="35" width="35" /></div><div style="color:#333;font-size:14px;font-family:arial,helvetica neue;padding-left:40px;text-align:left;">
    ', $firstname, ' ',$lastname,' is now following ',$owner,'</div></a>';
    echo '</div>'; 

    }
    
    elseif ($type == "comment") {
    $owner = $newsrow['owner'];
    $ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$owner'";
    $ownerresult = mysql_query($ownersquery); 
    $ownerrow = mysql_fetch_array($ownerresult);
    $ownerfirst = $ownerrow['firstname'];
    $ownerlast = $ownerrow['lastname'];
    $ownerfull = $ownerfirst . " " . $ownerlast;
    $ownerfull = ucwords($ownerfull);
    $firstname = $newsrow['firstname'];
    $firstname = ucwords($firstname);
    $lastname = $newsrow['lastname'];
    $lastname = ucwords($lastname);
    $image = $newsrow['source'];
    $imagethumb = str_replace("userphotos/","userphotos/medthumbs/", $image);
    $fullname = $firstname . " " . $lastname;
    list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    $phrase = $fullname . " commented on " . $ownerfull . "'s photo";
    $phrase2 = (strlen($phrase) > 56) ? substr($phrase,0,53). " &#8230;" : $phrase;
    
    echo '<div class="grid_3 push_1 fPic photoshadow" id="',$id,'" style="margin-top:30px;width:250px;height:295px;"> <a style="text-decoration:none" href="http://www.photorankr.com/fullsize.php?image=',$image,'"><img onmousedown="return false" oncontextmenu="return false;" src="http://www.photorankr.com/',$imagethumb,'" width="250" height="250" />
    <br /><div style="margin-top:5px;width:50px;height:0px;padding-left:2px;"><img src="graphics/newsfeedcomment.png" height="35" width="35" /></div><div style="color:#333;font-size:14px;font-family:arial,helvetica neue;padding-left:40px;text-align:left;">
    ',$phrase2,'</div>';
    echo '</div></a>';  
    }    
    
     } // end of $isfollowing to make sure in following list
   
    
} // end for loop

}
   // }
                                                                                                        
//end grid_24 div
?>

</div>