<div id="container" class="container_24 push_5" style="border-left:1px solid #333;">

<?php 

require("db_connection.php");

if($_GET['lastPicture']) {
    
//GET FOLLOWERS 
$email = $_SESSION['email'];
$emailquery = "SELECT following FROM userinfo WHERE emailaddress ='$email'";
$followresult=mysql_query($emailquery);
$followlist=mysql_result($followresult, 0, "following");
$followrow=mysql_fetch_array($followresult);
$following=$followrow['following'];

	$newsfeedquery = "SELECT * FROM newsfeed WHERE id < ".$_GET['lastPicture']." ORDER BY id DESC LIMIT 0, 10";
    $newsfeedresult = mysql_query($newsfeedquery);

$maxwidth = 400;

for($iii=1; $iii <= 10; $iii++) {
    $newsrow = mysql_fetch_array($newsfeedresult);
    $id = $newsrow['id'];
    $type = $newsrow['type'];
    $email2 = $newsrow['emailaddress'];
    $isfollowing = strpos($followlist,$email2);
    
    /*if ($isfollowing !== false) { */
        
    if ($type == "photo") {
	$image = $newsrow['source'];
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
    echo '<div class="grid_9 push_2 fPic photoshadowFEED" id="',$id,'" style="width:600px; height:',$height,'+30px; margin-top:40px; overflow: hidden;">
    <a href="http://www.photorankr.com/fullsize.php?image=',$image,'"><img src="http://www.photorankr.com/',$image,'" height=', $height, 'px width="600px" /></a>
    <br /><div style="margin-top:5px;color:#333;font-size:17px;font-family:arial,helvetica neue;padding-bottom:3px;"><img src="graphics/newsfeedarrow.png" height="50" width="50" />
    ',$ownerfull,' uploaded "',$caption,'"</div>';
    echo '</div>';  

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
	$caption = $newsrow['caption'];
	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    $fullname = $firstname . " " . $lastname;
    echo '<div class="grid_9 push_2 fPic photoshadowFEED" id="',$id,'" style="width:600px; height:',$height,'+30px; margin-top:40px; overflow: hidden;">
    <a href="http://www.photorankr.com/fullsize.php?image=',$image,'"><img src="http://www.photorankr.com/',$image,'" height=', $height, 'px width="600px" /></a>
    <br /><div style="margin-top:5px;color:#333;font-size:17px;font-family:arial,helvetica neue;padding-bottom:3px;"><img src="graphics/newsfeedfavorite.png" height="50" width="50" />
    ',$fullname,' favorited "',$caption,'" by ',$ownerfull,'</div>';
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
	$caption = $newsrow['caption'];
	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    echo '<div class="grid_9 push_2 fPic photoshadowFEED" id="',$id,'" style="width:600px; height:',$height,'+30px; margin-top:40px; overflow: hidden;">
    <a href="http://www.photorankr.com/fullsize.php?image=',$image,'"><img src="http://www.photorankr.com/',$image,'" height=', $height, 'px width="600px" /></a>
    <br /><div style="margin-top:5px;color:#333;font-size:17px;font-family:arial,helvetica neue;padding-bottom:3px;"><img src="graphics/newsfeedtrending.png" height="50" width="50" />
    "',$caption,'" by ',$ownerfull,' is now trending</div>';
    echo '</div>';   
    }
    
    elseif ($type == "follow") {
    $email4 = $newsrow['following'];
    $newaccount = "SELECT * FROM userinfo WHERE emailaddress = '$email4'";
    $accountresult = mysql_query($newaccount); 
    $accountrow = mysql_fetch_array($accountresult);
    $profilepic = $accountrow['profilepic'];
    $ownerfirst = $accountrow['firstname'];
    $ownerlast = $accountrow['lastname'];
    $firstname = $newsrow['firstname'];
    $firstname = ucwords($firstname);
    $lastname = $newsrow['lastname'];
    $lastname = ucwords($lastname);
    $owner = $newsrow['owner'];
    $owner = ucwords($owner);
    echo '<a style="text-decoration:none" href="http://www.photorankr.com/viewprofile.php?first=',$ownerfirst,'&last=',$ownerlast,'"><div class="grid_9 push_2 fPic photoshadowFEED" id="',$id,'" style="width:600px; height:48px; margin-top:40px; overflow: hidden;color:#333;font-size:17px;font-family:arial,helvetica neue;padding-bottom:3px;"><img src="graphics/newsfeednewfollower.png" height="50" width="50" />
<img src="',$profilepic,'" height="50" width="50" />&nbsp;&nbsp;&nbsp;&nbsp;', $firstname, ' ',$lastname,' is now following ',$owner,'</div></a><br />';
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
    $fullname = $firstname . " " . $lastname;
    list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    echo '<div class="grid_9 push_2 fPic photoshadowFEED" id="',$id,'" style="width:600px; height:',$height,'+30px; margin-top:40px; overflow: hidden;"><a style="text-decoration:none" href="http://www.photorankr.com/fullsize.php?image=',$image,'">
    <img src="http://www.photorankr.com/',$image,'" height=', $height, 'px width="600px" /></a>
    <br /><br /><div style="margin-top:5px;color:#333;font-size:17px;font-family:arial,helvetica neue;padding-bottom:3px;"><img src="graphics/newsfeedcomment.png" height="50" width="50" />
    ',$fullname,' commented on ',$ownerfull,'&#39;s photo:</div>';
    $txt=".txt";
	$imagenew=str_replace("userphotos/","", $image);
	$searchchars=array('.jpg','.png','.tiff','.JPG','.jpeg','.JPEG','.gif');
	$imagenew=str_replace($searchchars,"", $imagenew);
	$file = "comments/" . $imagenew . $txt; 
	echo '<br /><hr style="color: black" /><div style="margin-left: 5px; height: 100%; overflow-y: scroll;">';
	@include("$file");
	if (@file_get_contents($file) == '') {
		echo '<div style="text-align: center;">Be the first to leave a comment!<br /><br /></div>';
	}
	echo '</div>';
    echo '</div>';  
    }    
    
    
}


    if ($type == "signup") {
    $email5 = $newsrow['emailaddress'];
    $newaccount = "SELECT * FROM userinfo WHERE emailaddress = '$email5'";
    $accountresult = mysql_query($newaccount); 
    $accountrow = mysql_fetch_array($accountresult);
    $profilepic = $accountrow['profilepic'];
    $firstname = $newsrow['firstname'];
    $firstname = ucwords($firstname);
    $lastname = $newsrow['lastname'];
    $lastname = ucwords($lastname);
    echo '<a style="text-decoration:none" href="http://www.photorankr.com/viewprofile.php?first=',$firstname,'&last=',$lastname,'"><div class="grid_9 push_2 fPic photoshadowFEED" id="',$id,'" style="width:600px; height:48px; margin-top:40px; overflow: hidden;color:#333;font-size:17px;font-family:arial,helvetica neue;padding-bottom:3px;"><img src="graphics/newsfeedsignup.png" height="50" width="50" />
<img src="',$profilepic,'" height="50" width="50" />&nbsp;&nbsp;&nbsp;&nbsp;', $firstname, ' ',$lastname,' joined PhotoRankr</div></a><br />';
    }

}
   // }
                                                                                                        
echo '</div>';
//end grid_24 div
?>

</div>