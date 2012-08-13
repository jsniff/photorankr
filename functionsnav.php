<?php
ini_set('max_input_time', 300);   

function createThumbnail($filename) {  
		ini_set('max_input_time', 300);
      
        require 'config.php';  
        
        $filename=str_replace("JPG","jpg",$filename);

        if(preg_match('/[.](jpg)$/', $filename)) {  
            $im = imagecreatefromjpeg($path_to_image_directory . $filename);  
        } else if (preg_match('/[.](gif)$/', $filename)) {  
            $im = imagecreatefromgif($path_to_image_directory . $filename);  
        } 
        else if (preg_match('/[.](jpeg)$/', $filename)) {  
            $im = imagecreatefromjpeg( $filename);  
        }
        else if (preg_match('/[.](png)$/', $filename)) {  
            $im = imagecreatefrompng($path_to_image_directory . $filename);  
        } 
      
      
        $ox = imagesx($im);  
        $oy = imagesy($im);  
      
        $nx = $final_width_of_image;  
        $ny = $final_height_of_image;  
      
        $nm = imagecreatetruecolor($nx, $ny);  
      
        imagecopyresampled($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy);  
      
        if(!file_exists($path_to_thumbs_directory)) {  
          if(!mkdir($path_to_thumbs_directory)) {  
               die("There was a problem. Please try again!");  
          }  
           }  
      
        imagejpeg($nm, $path_to_thumbs_directory . $filename);  
        //$tn = '<img src="' . $path_to_thumbs_directory . $filename . '" alt="image" />';  
        //$tn .= '<br />Upload Successful!';  
        //echo $tn;  
chmod ($path_to_thumbs_directory . $filename, 0644);
}  

function createprofthumbnail($filename) {  
ini_set('max_input_time', 300);
      
        require 'config.php';  
      
        if(preg_match('/[.](jpg)$/', $filename)) {  
            $im = imagecreatefromjpeg( $filename);  
        } else if (preg_match('/[.](gif)$/', $filename)) {  
            $im = imagecreatefromgif( $filename);  
        } else if (preg_match('/[.](jpeg)$/', $filename)) {  
            $im = imagecreatefromjpeg( $filename);  
        }
        else if (preg_match('/[.](png)$/', $filename)) {  
            $im = imagecreatefrompng($filename);  
        }  
      
        $ox = imagesx($im);  
        $oy = imagesy($im);  
        
        $ny = 400;  
	$nx = 400;
      
        $nm = imagecreatetruecolor($nx, $ny);  

        imagecopyresized($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy);  
      
        if(!file_exists($path_to_profpic_directory)) {  
          if(!mkdir($path_to_profpic_directory)) {  
               die("There was a problem. Please try again!");  
          }  
        }  
        
        if(!file_exists($path_to_coverpic_directory)) {  
          if(!mkdir($path_to_coverpic_directory)) {  
               die("There was a problem. Please try again!");  
          }  
        } 
      
        imagejpeg($nm, $filename);  
		chmod ($filename, 0644);
        //$tn = '<img src="' . $path_to_profpic_directory . $filename . '" alt="image" />';  
        //$tn .= '<br />Upload Successful!';  
        //echo $tn;  
}  

function createprofthumbdim($filename) {  
ini_set('max_input_time', 300);
      
        require 'config.php';  
      
        if(preg_match('/[.](jpg)$/', $filename)) {  
            $im = imagecreatefromjpeg( $filename);  
        } else if (preg_match('/[.](gif)$/', $filename)) {  
            $im = imagecreatefromgif( $filename);  
        } else if (preg_match('/[.](jpeg)$/', $filename)) {  
            $im = imagecreatefromjpeg( $filename);  
        }
        else if (preg_match('/[.](png)$/', $filename)) {  
            $im = imagecreatefrompng($filename);  
        }  
      
        $ox = imagesx($im);  
        $oy = imagesy($im);  
        
        $ny = 400;  
		$nx = $ny * $ox / $oy;
      
        $nm = imagecreatetruecolor($nx, $ny);  

        imagecopyresized($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy);  
      
        if(!file_exists($path_to_profpicthumbs_directory)) {  
          if(!mkdir($path_to_profpicthumbs_directory)) {  
               die("There was a problem. Please try again!");  
          }  
        }  
        
        if(!file_exists($path_to_coverpic_directory)) {  
          if(!mkdir($path_to_coverpic_directory)) {  
               die("There was a problem. Please try again!");  
          }  
        } 
      
      	$filename = str_replace("profilepics", "profilepics/thumbs", $filename);
        imagejpeg($nm, $filename);  
		chmod ($filename, 0644);
        //$tn = '<img src="' . $path_to_profpic_directory . $filename . '" alt="image" />';  
        //$tn .= '<br />Upload Successful!';  
        //echo $tn;  
}  

function watermarkpic($filename) {
	
	ini_set('max_input_time', 300);

	require 'config.php';  

	$watermark = imagecreatefrompng('watermarknew.png');
	$watermarkwidth = imagesx($watermark);
	$watermarkheight = imagesy($watermark);

        $filename=str_replace("JPG","jpg",$filename);

	if(preg_match('/[.](jpg)$/', $filename)) {  
            $originalimage = imagecreatefromjpeg($path_to_image_directory . $filename);  
        } else if (preg_match('/[.](gif)$/', $filename)) {  
            $originalimage = imagecreatefromgif($path_to_image_directory . $filename);  
        }
        else if (preg_match('/[.](jpeg)$/', $filename)) {  
            $originalimage = imagecreatefromjpeg($path_to_image_directory . $filename);  
        }
         else if (preg_match('/[.](png)$/', $filename)) {  
            $originalimage = imagecreatefrompng($path_to_image_directory . $filename);  
        }  

	$originalwidth = imagesx($originalimage);
	$originalheight = imagesy($originalimage);
	
	$maxsize = 1200;
	$imgratio = $originalwidth / $originalheight;

	if($imgratio > 1) {
		$finalwidth = $maxsize;
		$finalheight = $maxsize / $imgratio;
	}
	else {
		$finalheight = $maxsize;
		$finalwidth = $maxsize * $imgratio;
	}	

	$finalimage = imagecreatetruecolor($finalwidth,$finalheight);
	
	imagecopyresampled($finalimage, $originalimage, 0,0,0,0,$finalwidth,$finalheight,$originalwidth,$originalheight);

	imagecopy($finalimage, $watermark, 0, 0, 0, 0, $watermarkwidth, $watermarkheight);

	//now move the file where it needs to go
	if(!file_exists($path_to_medimage_directory)) {  
        	if(!mkdir($path_to_medimage_directory)) {  
               		die("There was a problem. Please try again!");  
          	}  
         } 
	
	 imagejpeg($finalimage, $path_to_medimage_directory . $filename); 	
	
	chmod ($path_to_medimage_directory . $filename, 0644);
}

//this function works for trending to find the spot of the pic
function findPicTrend($image) {
	//find out what it was ranked (what number it corresponds to)
	$trendquery = "SELECT * FROM photos ORDER BY score DESC";
	$trendresult = mysql_query($trendquery);
	$totalnumber = mysql_num_rows($trendresult);
	for($iii = 0; $iii < $totalnumber; $iii++)
	{
		$comparison = mysql_result($trendresult, $iii, "source");
		//if the current image matches the function image
		//return the index of where the image is	
		if($comparison == $image)
		{
			return $iii;
		}
	}
}

//this function works for newest to find the spot of the pic
function findPicNew($image) {
	//find out what it was ranked (what number it corresponds to)
	$newquery = "SELECT * FROM photos ORDER BY id DESC";
	$newresult = mysql_query($newquery);
	$totalnumber = mysql_num_rows($newresult);
	for($iii = 0; $iii < $totalnumber; $iii++)
	{
		$comparison = mysql_result($newresult, $iii, "source");
		//if the current image matches the function image
		//return the index of where the image is	
		if($comparison == $image)
		{
			return $iii;
		}
	}
}

//this function works for newest to find the spot of the pic
function findPicTop($image) {
	//find out what it was ranked (what number it corresponds to)
	$newquery = "SELECT * FROM photos ORDER BY points DESC";
	$newresult = mysql_query($newquery);
	$totalnumber = mysql_num_rows($newresult);
	for($iii = 0; $iii < $totalnumber; $iii++)
	{
		$comparison = mysql_result($newresult, $iii, "source");
		//if the current image matches the function image
		//return the index of where the image is	
		if($comparison == $image)
		{
			return $iii;
		}
	}
}

//this function works for myprofile to find the spot of the pic
function findPicMe($email, $image) {
	//find out what it was ranked (what number it corresponds to)
	$newquery = "SELECT * FROM photos WHERE emailaddress = '$email' ORDER BY id DESC";
	$newresult = mysql_query($newquery);
	$totalnumber = mysql_num_rows($newresult);
	for($iii = 0; $iii < $totalnumber; $iii++)
	{
		$comparison = mysql_result($newresult, $iii, "source");
		//if the current image matches the function image
		//return the index of where the image is	
		if($comparison == $image)
		{
			return $iii;
		}
	}
}

//this function works for viewprofile to find the spot of the pic
function findPicView($email, $image) {
	//find out what it was ranked (what number it corresponds to)
	$newquery = "SELECT * FROM photos WHERE emailaddress = '$email' ORDER BY id DESC";
	$newresult = mysql_query($newquery);
	$totalnumber = mysql_num_rows($newresult);
	for($iii = 0; $iii < $totalnumber; $iii++)
	{
		$comparison = mysql_result($newresult, $iii, "source");
		//if the current image matches the function image
		//return the index of where the image is	
		if($comparison == $image)
		{
			return $iii;
		}
	}
}

//this function works for photostream to find the spot of the pic
function findPicStream($followlist, $image) {
	//find out what it was ranked (what number it corresponds to)
	$newquery = "SELECT * FROM photos WHERE emailaddress IN ($followlist) ORDER BY id DESC";
	$newresult = mysql_query($newquery);
	$totalnumber = mysql_num_rows($newresult);
	for($iii = 0; $iii < $totalnumber; $iii++)
	{
		$comparison = mysql_result($newresult, $iii, "source");
		//if the current image matches the function image
		//return the index of where the image is	
		if($comparison == $image)
		{
			return $iii;
		}
	}
}

//Create Medium Thumbnail
function createMedThumbnail($filename) {  
ini_set('max_input_time', 300);
      
        require 'config.php';  
        
        $filename=str_replace("JPG","jpg",$filename);

        if(preg_match('/[.](jpg)$/', $filename)) {  
            $im = imagecreatefromjpeg($path_to_image_directory . $filename);  
        } else if (preg_match('/[.](gif)$/', $filename)) {  
            $im = imagecreatefromgif($path_to_image_directory . $filename);  
        }
        else if (preg_match('/[.](jpeg)$/', $filename)) {  
            $im = imagecreatefromjpeg($path_to_image_directory . $filename);  
        }
         else if (preg_match('/[.](png)$/', $filename)) {  
            $im = imagecreatefrompng($path_to_image_directory . $filename);  
        } 
      
      
        $ox = imagesx($im);  
        $oy = imagesy($im);  
      
        $nx = $final_width_of_medimage;  
        $ny = $final_height_of_medimage;  
      
        $nm = imagecreatetruecolor($nx, $ny);  
      
        imagecopyresampled($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy);  
      
        if(!file_exists($path_to_medthumbs_directory)) {  
          if(!mkdir($path_to_medthumbs_directory)) {  
               die("There was a problem. Please try again!");  
          }  
           }  
      
        imagejpeg($nm, $path_to_medthumbs_directory . $filename);  
        //$tn = '<img src="' . $path_to_medthumbs_directory . $filename . '" alt="image" />';  
        //$tn .= '<br />Upload Successful!';  
        //echo $tn;  
chmod ($path_to_medthumbs_directory . $filename, 0644);
}    


function login() {
    @session_start();

        // makes sure they filled it in
        if(!htmlentities($_POST['emailaddress'])) {
            header('Location: signup.php?action=fie');
            die();
        }
        
        if(!htmlentities($_POST['password'])) {
            header('Location: signup.php?action=fip');
            die();
        }


        $check = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '".mysql_real_escape_string($_POST['emailaddress'])."'")or die(mysql_error());
        //Gives error if user dosen't exist

        $check2 = mysql_num_rows($check);
    
        if ($check2 == 0) {
            header('Location: signup.php?action=nu');
            die(); 
        }

        $info = mysql_fetch_array($check);
        
        if(mysql_real_escape_string($_POST['password']) == mysql_real_escape_string($info['password'])){
            //then redirect them to the same page as signed in and set loggedin to 1
            $_SESSION['loggedin'] = 1;
            $_SESSION['email'] = mysql_real_escape_string($_POST['emailaddress']);
        }
        //gives error if the password is wrong
        else if (mysql_real_escape_string($_POST['password']) != mysql_real_escape_string($info['password'])) {
            header('Location: signup.php?action=lp');
            die();   
        }
}

function logout() {
    session_start();
    $_SESSION['loggedin'] = 0;
    $_SESSION['email'] = "";

    session_destroy();
}

function navbarnew() {
echo'
<link rel="stylesheet" href="css/style.css" type="text/css"/> 
<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container" style="height:50px;width:1040px;">
				<ul class="nav" style="height:50px;">
					<li class="margint"> <a href="newsfeed.php"><img class="logo" src="graphics/coollogo.png" style="position:relative;left:-10px;height:45px;width:186px;margin-top:-8px;padding-right:20px;"/></a></li>
					<li class="margint" style="margin-left:-15px;"> <form class="navbar-search" action="search.php" method="get">
<input class="search3 margint marginl" style="height:1.4em;padding-right:25px;margin-top:2px;margin-left:5em;margin-right:5.5em;font-family:helvetica;font-size:13px;font-weight:100;color:black;" name="searchterm" type="text" placeholder="Search for photos & people">
</form></li>
					<li> <a href="newsfeed.php"> Home </a> </li>
					<li class="dropdown topcenter"'; if($_SERVER['REQUEST_URI'] == '/newest3.php') {echo'style="color:white;"';} echo'id="accountmenu">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#"> Photos </b></a>
							<ul class="dropdown-menu" style="margin-top:0px;background-color:#fff;">
								<li> <a href="newest.php"> Newest </a></li>
								<li> <a href="trending.php"> Trending </a></li>
								<li class="divider"></li> 	<li> <a href="topranked.php"> Top Ranked </a></li>
								<li> <a href="discover.php"> Discover </a> </li>
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
                            $fullname = mysql_result($profilequery,0,'firstname')." ".mysql_result($profilequery,0,'lastname');
                            $fullname = (strlen($fullname) > 14) ? substr($fullname,0,11). "&#8230;" : $fullname;

                        
                        echo'
						<li class="dropdown"  id="accountmenu">';
                                                            
                                //QUERY FOR NOTIFICATION COUNT
                                $currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$email'";
                                $currentnotsquery = mysql_query($currentnots);
                                $currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");

                        echo'
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"> <div class="notifications" style="margin-top:-5px;"><div style="position:relative;top:4px;color:#6aae45;left:10px;font-size:13px;font-weight:bolder;">',$currentnotsresult,'</div></div> </a>
								<ul class="dropdown-menu" style="margin-top:0px;margin-left:-255px;background-color:#fff;">';
								
                                //NOTIFICATIONS
                                
$emailquery=("SELECT following FROM userinfo WHERE emailaddress ='$email'");
$followresult=mysql_query($emailquery);
$followinglist=mysql_result($followresult, 0, "following");

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
							<a class="dropdown-toggle" data-toggle="dropdown" href="myprofile.php"><img src="',$profilepic,'" style="width:30px;height:30px;"/><span style="font-size:13px;color:white;font-weight:200;">&nbsp;&nbsp;&nbsp;',$fullname,'</span></a>
								<ul class="dropdown-menu" style="margin-top:0px;background-color:#fff;width:150px;">
                                    <li> <a href="myprofile.php?view=upload"> Upload </a> </li>
                                    <li class="divider"></li>
                                    <li> <a href="myprofile.php"> Portfolio </a> </li>
                                    <li class="divider"></li>
                                    <li> <a href="myprofile.php?view=store"> My Store </a> </li>
                                    <li class="divider"></li>
                                    <li> <a href="myprofile.php?view=messages"> Messages </a> </li>
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

function navbar() {
echo'
<link rel="stylesheet" href="css/style.css" type="text/css"/> 
<div style="width:100%;height:50px;border-bottom:1px solid #999;min-width:1085px;position:fixed;z-index:15;" class="gunmetal">
<a href="/market"><img class="hover" style="float:left;padding-left:30px;padding-top:2px;" src="market/graphics/logotext.png" width="210" /></a>
<label style="float:left;font-size:13px;color:white;padding-top:15px;padding-left:80px;font-weight:normal;">Search</label>

<form class="navbar-search" action="/market/#search" method="get">
<input class="search" style="position:relative;margin-left:10px;margin-top:2px;" name="searchterm" type="text">
<a href="#search"><input style="margin-top:3px;margin-left:-3px;" type="submit" class="go" value="Go"></a>
</form>

';
@session_start();
if($_SESSION['loggedin'] != 1) {
echo'
<div style="float:right;margin-top:15px;">
<a class="navhover" style="font-weight:lighter;" href="/market">Galleries</a>
<a class="navhover" href="viewcampaigns.php">Market</a>
<a class="navhover" href="blog.php">Blog</a>
 <span class="dropdown">
 <a class="dropdown-toggle navhover" data-toggle="dropdown">Log In</a>
 <ul class="dropdown-menu gunmetal" data-dropdown="dropdown" style="width:200px;margin-left:-120px;margin-top:15px;">
 <li><a style="color:white;margin-left:-29px;font-size:15px;" href="campaignnewuser.php">Register for free today</a></li>
 <li><br/></li>
 <form name="login_form" method="post" action="',htmlentities($_SERVER['PHP_SELF']),'?action=login">
 <li style="margin-left: 5px; margin-right: 0px; width: 185px;color:white;"><span style="margin-bottom: 5px;margin-left:10px;font-size:13px;">Email: <br /></span><input type="text" style="width:150px;margin-top:3px;margin-left:10px;padding:7px;" name="emailaddress" /></li>
 <li><span style="font-size:13px;margin-left:-16px;color:white;">Password: <br /></span><input type="password" style="width:150px;margin-top:3px;margin-left:-16px;padding:7px;" name="password"/></li>
 <li style="margin-left: 110px;"><input type="submit" class="btn btn-success" value="Sign In" id="loginButton"/></li>
 </form>
 </ul>
 </span>
 </div>';
}
elseif($_SESSION['loggedin'] == 1) {
   $email = $_SESSION['email'];

$profilequery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$email'");
$profilepic = mysql_result($profilequery,0,'profilepic');
$fullname = mysql_result($profilequery,0,'firstname')." ".mysql_result($profilequery,0,'lastname');

echo'
<div style="float:right;">

<a href="account.php" class="dropdown-toggle navhover" data-toggle="dropdown" style="position:relative;top:8px;">
<div style="height:50px;width:auto;float:right;">
<img style="border:1px solid #ccc;margin-top:8px;margin-left:3px;" src="',$profilepic,'" height="30" width="30" />
<span style="font-size:13px;font-weight:bold;">',$fullname,'</span></div></a>

<span class="dropdown">
<ul class="dropdown-menu gunmetal" data-dropdown="dropdown" style="width:200px;margin-top:30px;margin-left:-40px;">
<a style="padding:15px;color:white;" href="account.php">Saved Images</a><hr />
<a style="padding:15px;padding-top:0px;color:white;" href="download2.php">Cart</a><hr />
<a style="padding:15px;padding-top:0px;color:white;" href="account.php?view=campaigns">Manage Campaigns</a>
</ul>
</span>

<a class="navhover" style="float:right;margin-top:4px;padding-right:30px;" href="blog.php">Blog</a>
<a class="navhover" style="float:right;margin-top:4px;" href="viewcampaigns.php">Market</a>
<a class="navhover" style="font-weight:lighter;float:right;margin-top:4px;" href="/market">Galleries</a>
<a class="navhover" style="font-weight:lighter;float:right;margin-top:4px;" href="/market">Home</a>

</div>';

}
echo'
</div>
';
}

function footer() {
    echo'
   <link rel="stylesheet" href="css/style.css" type="text/css"/> 
    
    <div class="grid_24" style="height:30px;margin-top:70px;background-color:rgb:(238,239,243);text-align:center;padding-top:10px;background-color:none;text-decoration:none;">
    <hr style="color:#ccc"/>
    </div>

    <span style="color:rgb(117,117,117);">
    <div class="grid_4" style="height:145px;border-right:2px solid #ddd;">
    <p1 class="navgreen">Network</p1>
    <ul style="list-style:none;">
    <li><a class="foot"  style="text-decoration:none;" href="http://photorankr.com/trending.php">Trending</a></li>
    <li><a class="foot"  style="text-decoration:none;" href="http://photorankr.com/newest.php">Newest</a></li>
    <li><a class="foot"  style="text-decoration:none;" href="http://photorankr.com/topranked.php">Top Ranked</a></li>
    <li><a class="foot"  style="text-decoration:none;" href="http://photorankr.com/blog/post.php">Blog</a></li>
    <li><a class="foot"  style="text-decoration:none;" href="http://photorankr.com/discover.php">Discover</a></li>
    </ul> 
    </div>
  
    <div class="grid_4" style="height:145px;border-right:2px solid #ddd;">
    <p1 class="navgreen">Marketplace</p1>
    <ul style="list-style:none;">
    <li><a class="foot"  style="text-decoration:none;" href="http://photorankr.com/market">Marketplace</a></li>
    <li><a class="foot"  style="text-decoration:none;" href="http://photorankr.com/market/viewcampaigns.php">Campaigns</a></li>
    </ul> 
    </div>

    <div class="grid_4" style="height:145px;border-right:2px solid #ddd;">
    <p1 class="navgreen">More</p1>
    <ul style="list-style:none;">
    <li><a class="foot"  style="text-decoration:none;" href="http://photorankr.com/about.php">About</a></li>
    <li><a class="foot"  style="text-decoration:none;" href="http://photorankr.com/contact.php">Contact Us</a></li>
    <li>Careers</li>
    <li>Partners</li>
    </ul> 
    </div>

    <div class="grid_4" style="height:145px;border-right:2px solid #ddd;">
    <p1 class="navgreen">Legal</p1>
    <ul style="list-style:none;">
    <li><a class="foot" style="text-decoration:none;" href="http://photorankr.com/market/terms.php">Terms of Use</a></li>
    <li><a class="foot"  style="text-decoration:none;" href="http://photorankr.com/market/privacy.php">Privacy Policy</a></li>
    <li><a class="foot"  style="text-decoration:none;" href="http://photorankr.com/market/legal.php">Standard Content License Agreement</a></li>
    <li><a class="foot"  style="text-decoration:none;" href="http://photorankr.com/market/extended.php">Extended Content License Provisions</a></li>
    </ul> 
    </div>

    <div class="grid_4">
    <p1 class="navgreen">Support</p1>
    <ul style="list-style:none;">
    <li>(330) 573-3776</li>
    <li><a href="mailto:support@photorankr.com">support@photorankr.com</a></li>
    </ul>
    <p1 class="navgreen">Follow</p1>
    
    <div style="padding-left:25px;width:250px">

	     <div style="margin-right:10px;float:left" class="fb-like" data-href="http://photorankr.com" data-send="false" data-layout="button_count" data-width="300" data-show-faces="false" data-font="arial"></div>

             <div style="margin-right:10px;float:left">
             <a href="https://twitter.com/PhotoRankr" class="twitter-follow-button" data-show-count="false">Follow @PhotoRankr</a>
             <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
             </div>

             <div style="float:left">
             <script src="//platform.linkedin.com/in.js" type="text/javascript"></script>
             <script type="IN/Share"></script> 
             </div>
    </div>
    </div>




</span>

  <div class="grid_24">
<div style="font-size:10px;color:#bbb;text-align:center;">
  </br>
  <p1>PhotoRankr is a trademark of PhotoRankr, Inc. The PhotoRankr Logo is a trademark of PhotoRankr, Inc.</p1>
</br>
<p1>Copyright &#169; 2012 PhotoRankr, Inc.<p1>
</div>
  </div> 
</div>
 
    <br />
    <br />                   
    </div>';
    
}


?>