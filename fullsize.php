<?php

//connect to the database
require "../db_connection.php";
require "functions.php";

//start the session
session_start();

    // if login form has been submitted
    if(htmlentities($_GET['action']) == "login") { 
        login();
    }
    elseif(htmlentities($_GET['action']) == "logout") { 
        logout();
    }

    $email = $_SESSION['email'];
    
    $findreputationme = mysql_query("SELECT user_id,reputation,profilepic,firstname,lastname,following FROM userinfo WHERE emailaddress = '$email'");
    $reputationme = mysql_result($findreputationme,0,'reputation');
    $sessionpic = mysql_result($findreputationme,0,'profilepic');
    $sessionuserid =  mysql_result($findreputationme,0,'user_id');
    $sessionfirst =  mysql_result($findreputationme,0,'firstname');
    $sessionlast =  mysql_result($findreputationme,0,'lastname');
    $sessionid =  mysql_result($findreputationme,0,'user_id');
    $sessionfollowing =  mysql_result($findreputationme,0,'following');
    $sessionname = mysql_result($findreputationme,0,'firstname') ." ". mysql_result($findreputationme,0,'lastname');

    //QUERY FOR NOTIFICATIONS
    $currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$email'";
    $currentnotsquery = mysql_query($currentnots);
    $currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");

    //notifications query reset 
    if($currentnotsresult > 0) {
    $notsquery = "UPDATE userinfo SET notifications = 0 WHERE emailaddress = '$email6'";
    $notsqueryrun = mysql_query($notsquery); }
    
    //DE-HIGHLIGHT NOTIFICATIONS IF CLICKED ON

    if(isset($_GET['id'])){
        $id = htmlentities($_GET['id']);
        $idformatted = $id . " ";
        $unhighlightquery = "UPDATE userinfo SET unhighlight = CONCAT(unhighlight,'$idformatted') WHERE emailaddress = '$email'";
        $unhighlightqueryrun = mysql_query($unhighlightquery);
    }
    
    //GET THE IMAGE
$image = addslashes($_GET['image']);
$view = htmlentities($_GET['v']);

if(!$image) {

    $imageid = addslashes($_GET['imageid']);
    $imagequery = mysql_query("SELECT source FROM photos WHERE id = '$imageid'");
    $image = mysql_result($imagequery,0,'source');
    
}

if(!$imageid) {

    $imagequery = mysql_query("SELECT id FROM photos WHERE source = '$image'");
    $imageid = mysql_result($imagequery,0,'id');

} 

//if the url does not contain an image send them back to trending
if(!$image) {
	header("Location: trending.php");
	exit();
}

//FIND THE PHOTO IN DATABASE
$query="SELECT * FROM photos where source='$image'";
$result=mysql_query($query);
//if no images match what is in the url, then send them back to trending 
if(mysql_num_rows($result) == 0) {
	header("Location: trending.php");
	exit();
}

$row=mysql_fetch_array($result);
$emailaddress=$row['emailaddress'];
$caption=$row['caption'];
$location=$row['location'];
$country=$row['country'];
$time=$row['time'];
//$uploaded = converttime($time);
//$date = converttodate($time)
$faves=$row['faves'];
$collected=$row['collected'];
$prevpoints=$row['points'];
$prevvotes=$row['votes'];
$ranking=number_format(($prevpoints/$prevvotes),1);
$imageID=$row['id'];
$price=mysql_result($result, 0, "price");
$price=number_format($price,0);

$camera = mysql_result($result,0,"camera");
if($camera) {
$camera = '<a style="color:black;" href="search.php?searchterm='.$camera.'">' . $camera . '</a>';
}
$faves= $row['faves'];
$views = $row['views'];
$exhibit = $row['set_id'];

$exquery = mysql_query("SELECT source FROM photos WHERE set_id = '$exhibit' AND emailaddress = '$emailaddress' ORDER BY (points/votes) DESC LIMIT 0,3");
$expic1 = mysql_result($exquery,0,'source');
$exthumb1 = str_replace("userphotos/","userphotos/medthumbs/",$expic1);
$expic2 = mysql_result($exquery,1,'source');
$exthumb2 = str_replace("userphotos/","userphotos/medthumbs/",$expic2);
$expic3 = mysql_result($exquery,2,'source');
$exthumb3 = str_replace("userphotos/","userphotos/medthumbs/",$expic3);

$sold = $row['sold'];
$exhibitname = $row['sets'];
$focallength = $row['focallength'];
$shutterspeed = $row['shutterspeed'];
$aperture = $row['aperture'];
$iso = $row['iso'];
$software = $row['software'];
$lens = $row['lens'];
$filter = $row['filter'];
$copyright = $row['copyright'];
$about = $row['about'];

$tag1 = $row['tag1'];

if($tag1) {
$tag1 = '<a style="color:black;" href="search.php?searchterm='.$tag1.'">'.$tag1.'</a>';
$tag1 = $tag1 . ", ";
}

$tag2 = $row['tag2'];

if($tag2) {
$tag2 = '<a style="color:black;" href="search.php?searchterm='.$tag2.'">'.$tag2.'</a>';
$tag2 = $tag2 . ", ";
}

$tag3 = $row['tag3'];

if($tag3) {
$tag3 = '<a style="color:black;" href="search.php?searchterm='.$tag3.'">'.$tag3.'</a>';
$tag3 = $tag3 . ", ";
}

$tag4 = $row['tag4'];

if($tag4) {
$tag4 = '<a style="color:black;" href="search.php?searchterm='.$tag4.'">'.$tag4.'</a>';
$tag4 = $tag4 . ", ";
}

$singlestyletags = $row['singlestyletags'];
$singlecategorytags = $row['singlecategorytags'];
$singlestyletagsarray = explode("  ", $singlestyletags);
$singlecategorytagsarray   = explode("  ", $singlecategorytags);
for($iii=0; $iii < count($singlestyletagsarray); $iii++) {
if($singlestyletagsarray[$iii] != '') {
    $singlestyletagsfinal .= '<a style="color:black;" href="search.php?searchterm='.$singlestyletagsarray[$iii].'">' . $singlestyletagsarray[$iii] . '</a>' . ", "; }
    }
    for($iii=0; $iii < count($singlecategorytagsarray); $iii++) {
        if($singlecategorytagsarray[$iii] != '') {
        $singlecategorytagsfinal .= '<a style="color:black;" href="search.php?searchterm='.$singlecategorytagsarray[$iii].'">' . $singlecategorytagsarray[$iii] . '</a>' . ", "; }
    }
    
$keywords = $tag1 . $tag2 . $tag3 . $tag4 . $singlestyletagsfinal . $singlecategorytagsfinal;
$keywords = substr_replace($keywords ," ",-2);
    

//find how many photos the photographer has
$numberofpics = mysql_query("SELECT * FROM photos WHERE emailaddress='$emailaddress'");
$numberofpics = mysql_num_rows($numberofpics);

$locationandcountry = $location . $country;

if ($price == "0.00") {$price='Free';}  
elseif ($price == "Not For Sale") {$price='NFS';}
elseif ($price == "$NFS") {$price='NFS';}
elseif ($price == "") {$price='';}   
else {$price = '$' . $price; }  

//FIND THE PHOTOGRAPHER NAME IN DATABASE
$namequery="SELECT * FROM userinfo WHERE emailaddress='$emailaddress'";
$nameresult=mysql_query($namequery);
$row=mysql_fetch_array($nameresult);
$user=$row['user_id'];

$firstname=$row['firstname'];
$lastname=$row['lastname'];
$reputation=number_format($row['reputation'],2);
$promos = mysql_result($nameresult,0,'promos');
$fullname = $firstname . " " . $lastname;
$fullname = (strlen($fullname ) > 14) ? substr($fullname,0,12). " &#8230;" :$fullname;

$profilepic=$row['profilepic'];
$profilescore=$row['totalscore'];

//calculate the size of the picture
$maxwidth=800;
$maxheight=800;

list($width, $height)=getimagesize($image);
$imgratio=$width/$height;

if($imgratio > 1) {
	$newwidth=$maxwidth;
	$newheight=$maxwidth/$imgratio;
}
else {
	$newheight=$maxheight;
	$newwidth=$maxheight*$imgratio;
}

   //FOLLOWING QUERIES

$follow=$_GET['fw'];

if ($follow == 1) {
	if($_SESSION['loggedin'] == 1) {
    
		$emailquery=("SELECT * FROM userinfo WHERE emailaddress ='$email'");
		$emailresult=mysql_query($emailquery);
		$prevemails=mysql_result($emailresult, 0, "following");
		$viewerfirst = mysql_result($emailresult, 0, "firstname");
		$viewerlast = mysql_result($emailresult, 0, "lastname");
		if($prevemails == "") {$emailaddressformatted="'". $emailaddress . "'";}
		else {$emailaddressformatted=", '". $emailaddress . "'";}
        
		//MAKE SURE FOLLOWER ISN'T ADDED TWICE
		$search_string=$prevemails;
		$regex="/$emailaddress/";
		$match=preg_match($regex,$search_string);
		if ($match > 0) {
			/*echo '<div style="position:absolute; top:100px; left:800px; font-family: lucida grande, georgia; color:black; font-size:15px;z-index:72983475273459273458972349587293745;">You are already following this photographer</div>'; */
		} 
        
		else {
        
			$followingstring=$prevemails . $emailaddressformatted;
			$followingstring=addslashes($followingstring);
			$followquery = "UPDATE userinfo SET following = '$followingstring' WHERE emailaddress='$email'";
			$followingresult=mysql_query($followquery);
            
             $type2 = "follow";
             $ownername = $firstname . " " . $lastname;
        $newsfeedfollowquery="INSERT INTO newsfeed (firstname, lastname, emailaddress,following,type,owner,time) VALUES ('$viewerfirst', '$viewerlast', '$email','$emailaddress','$type2','$ownername','$currenttime')";
        $follownewsquery = mysql_query($newsfeedfollowquery);
        
        //notifications query     
$notsquery = "UPDATE userinfo SET notifications = (notifications + 1) WHERE emailaddress = '$emailaddress'";
$notsqueryrun = mysql_query($notsquery);  
        
            
		/*	echo '<div style="position:absolute; top:100px; left:800px; font-family: lucida grande, georgia; color:black; font-size:15px;z-index:72983475273459273458972349587293745;">Now Following ',$firstname,' ',$lastname,'</div>';    */
            
             		//PERSON NOW BEING FOLLOWED
    
//GRAB SETTINGS LIST
$settingquery = "SELECT * FROM userinfo WHERE emailaddress = '$emailaddress'";
$settingqueryrun = mysql_query($settingquery);
$settinglist = mysql_result($settingqueryrun, 0, "settings");

$setting_string = $settinglist;
$find = "emailfollow";
$foundsetting = strpos($setting_string,$find);
    
        		$to = '"' . $firstname . ' ' . $lastname . '"' . '<'.$emailaddress.'>';
        		$subject = $viewerfirst . " " . $viewerlast . ' is now following your photography on PhotoRankr!';
        		$message = 'You have a new follower on PhotoRankr! Visit their photography here: https://photorankr.com/viewprofile.php?u='.$sessionuserid;
        		$headers = 'From:PhotoRankr <photorankr@photorankr.com>';
                if($foundsetting > 0) {
        		mail($to, $subject, $message, $headers);   
                }
		}
	}
}  
  
    //Unfollow Query
    if(htmlentities($_GET['uf']) == 1) {

        $followingquery = mysql_query("SELECT following FROM userinfo WHERE emailaddress = '$email'");
        $following = mysql_result($followingquery,0,'following');
        $updatefollowing = "UPDATE userinfo SET following = replace(following, '$emailaddress','') WHERE emailaddress = '$email'";	
        $updaterun = mysql_query($updatefollowing);

    }
    
    //COMMENT QUERIES

if(htmlentities($_POST['comment']) && $_SESSION['loggedin'] == 1) {
    
    $currenttime = time();
    $unformattedcomment = $_POST['comment'];
    $comment = mysql_real_escape_string(htmlentities($_POST['comment']));
    
    //Convert all instances of 'http' to a link
    $comment = trim($comment);
    $comment = make_url($comment);
    
    $insertcomment = mysql_query("INSERT INTO comments (comment,commenter,photoowner,imageid,time) VALUES ('$comment','$email','$emailaddress','$imageID','$currenttime')");
    
    //MAIL TO OWNER OF PHOTO
    $settingquery = mysql_query("SELECT settings FROM userinfo WHERE emailaddress = '$emailaddress'");
    $settinglist = mysql_result($settingquery,0,"settings");
    $check = 'emailcomment';
    $foundsetting = strpos($settinglist,$check);
    
    if($emailaddress != $email) {
    $to = '"' . $firstname . ' ' . $lastname . '"' . '<'.$emailaddress.'>';
    $subject = $sessionname ." commented on your photo on PhotoRankr";
    $message = $unformattedcomment . "
To view the photo, click here: https://photorankr.com/fullsize.php?image=".$image;
    $headers = 'From:PhotoRankr <photorankr@photorankr.com>';
    
        if($foundsetting > 0) {
            mail($to, $subject, $message, $headers);  
        }
        
    }

    //MAIL TO PREVIOUS COMMENTERS ON PHOTO
    $previouscommenters = mysql_query("SELECT commenter FROM comments WHERE imageid = '$imageID'");
    $numcommenters = mysql_num_rows($previouscommenters);
    $prevemails .= $email;
      
    for($iii = 0; $iii < $numcommenters; $iii++) {
        
        $prevemail = mysql_result($previouscommenters,$iii,'commenter');
        $alreadysent = strpos($prevemails, $prevemail);
        
        if($alreadysent < 1 && $prevemail != $emailaddress) {
        
            $settingquery = mysql_query("SELECT firstname,lastname,emailaddress,settings FROM userinfo WHERE emailaddress = '$prevemail'");
            $settinglist = mysql_result($settingquery,0,"settings");
            $foundsetting = strpos($settinglist,"emailreturncomment");
            $sendtofirst = mysql_result($settingquery,0,"firstname");
            $sendtolast = mysql_result($settingquery,0,"lastname");
            $sendtoemail = mysql_result($settingquery,0,"emailaddress");
            
            $to = '"' . $sendtofirst . ' ' . $sendtolast . '"' . '<'.$sendtoemail.'>';
            $subject = $sessionfirst . " " . $sessionlast . " also commented on " . $firstname . " " . $lastname ."'s photo on PhotoRankr";
            $returnmessage = stripslashes($message) . "
        
To view the photo, click here: https://photorankr.com/fullsize.php?image=".$image;
            
            $headers = 'From:PhotoRankr <photorankr@photorankr.com>';
                        
            if($foundsetting > 0 && $sendtoemail != $email) {     
                mail($to, $subject, $returnmessage, $headers);
            } 
    
        }
        
        elseif($alreadysent > 0) {
            continue;
        }
        
        $prevemails .= " " . $prevemail;
    
    }
    
        $type = "comment";
        
        $commentidquery = mysql_query("SELECT id FROM comments WHERE commenter = '$email' ORDER BY id DESC LIMIT 0,1");
        $commentid = mysql_result($commentidquery,0,'id');
        
        $newsfeedcomment = mysql_query("INSERT INTO newsfeed (firstname, lastname, emailaddress,owner,type,source,imageid,time) VALUES ('$sessionfirst', '$sessionlast', '$email','$emailaddress','$type','$image','$commentid','$currenttime')") or die();
            
    //echo '<META HTTP-EQUIV="Refresh" Content="0; URL=fullsize.php?image=', $image, '&v=', $view, '">';
	//exit();

}

//DELETE COMMENT
if(htmlentities($_GET['action']) == 'deletecomment' && $_SESSION['loggedin'] == 1) {
    
    $commentid = htmlentities($_GET['cid']);
    $deletecomment = mysql_query("DELETE FROM comments WHERE id = '$commentid'");

}

//EDIT COMMENT
if($_POST['commentedit']) {

    $commentedit = mysql_real_escape_string($_POST['commentedit']);
    $commentid = mysql_real_escape_string($_POST['commentid']);
    $commenteditquery = mysql_query("UPDATE comments SET comment = '$commentedit' WHERE id = '$commentid' AND commenter = '$email'");
    
}


//ADD TO COLLECTION(S)
if(htmlentities($_GET['action']) == 'savecol' && $_SESSION['loggedin'] == 1) {
        
    if(!empty($_POST['collection'])) {
    
        foreach($_POST['collection'] as $checked) {
            
            $addphoto = $imageid ." ";
            //insert each checked photo into corresponding set
            $checkedcol = mysql_query("UPDATE collections SET photos = CONCAT(photos,'$addphoto') WHERE id = '$checked'");
            
        }
        
         $incrementcount = mysql_query("UPDATE photos SET collected=collected+1 WHERE id=$imageid") or die(mysql_error());
        
        //Mail notice to photographer whose photo was added to a collection
            
        $to = '"' . $firstname . ' ' . $lastname . '"' . '<'.$emailaddress.'>';
        $subject = $sessionfirst . " " . $sessionlast . " added your photo '".$caption."' to a collection on PhotoRankr";
        $returnmessage = stripslashes($message) . "
        
To view the collection, click here: https://photorankr.com/viewprofile.php?u=".$sessionid."&view=collections&set=".$checked;
            
        $headers = 'From:PhotoRankr <photorankr@photorankr.com>';
        
        if($emailaddress != $email) {
            mail($to, $subject, $returnmessage, $headers);
        }
    }
}

//if they came from trending
if($view == 't') {
	//find where this pic is in the order
	$index = findPicTrend($image);
	if($index == 0) {
		$testquery = mysql_query("SELECT * FROM photos ORDER BY score ASC");
		$imageBefore = mysql_result($testquery, 0, "source");
		$index++;
		$nextThreeQuery = "SELECT * FROM photos ORDER BY score DESC LIMIT $index, 6";
		$nextThreeResult = mysql_query($nextThreeQuery);
		$imageOne = mysql_result($nextThreeResult, 0, "source");
		$imageOneThumb = str_replace("userphotos/","userphotos/thumbs/", $imageOne);
		$imageTwo = mysql_result($nextThreeResult, 1, "source");
		$imageTwoThumb = str_replace("userphotos/","userphotos/thumbs/", $imageTwo);
		$imageThree = mysql_result($nextThreeResult, 2, "source");
		$imageThreeThumb = str_replace("userphotos/","userphotos/thumbs/", $imageThree);
        	$imageFour = mysql_result($nextThreeResult, 3, "source");
		$imageFourThumb = str_replace("userphotos/","userphotos/thumbs/", $imageFour);
        	$imageFive = mysql_result($nextThreeResult, 4, "source");
		$imageFiveThumb = str_replace("userphotos/","userphotos/thumbs/", $imageFive);
        
	}
	else {
		$index--;
		$nextThreeQuery = "SELECT * FROM photos ORDER BY score DESC LIMIT $index, 7";
		$nextThreeResult = mysql_query($nextThreeQuery);
		$numpics = mysql_num_rows($nextThreeResult);
		$imageBefore = mysql_result($nextThreeResult, $x, "source");
        	
		if(7-$numpics == 1) {
			$resetquery = mysql_query("SELECT * FROM photos ORDER BY score DESC LIMIT 0, 1");
			$imageOne = mysql_result($nextThreeResult, 2, "source");
			$imageTwo = mysql_result($nextThreeResult, 3, "source");
			$imageThree = mysql_result($nextThreeResult, 4, "source");	
			$imageFour = mysql_result($nextThreeResult, 5, "source");	
			$imageFive = mysql_result($resetquery, 0, "source");
		}
		else if(7-$numpics == 2) {
			$resetquery = mysql_query("SELECT * FROM photos ORDER BY score DESC LIMIT 0, 2");
			$imageOne = mysql_result($nextThreeResult, 2, "source");
			$imageTwo = mysql_result($nextThreeResult, 3, "source");
			$imageThree = mysql_result($nextThreeResult, 4, "source");
			$imageFour = mysql_result($resetquery, 0, "source");
			$imageFive = mysql_result($resetquery, 1, "source");
		}
		else if(7-$numpics == 3) {
			$resetquery = mysql_query("SELECT * FROM photos ORDER BY score DESC LIMIT 0, 3");
			$imageOne = mysql_result($nextThreeResult, 2, "source");
			$imageTwo = mysql_result($nextThreeResult, 3, "source");
			$imageThree = mysql_result($resetquery, 0, "source");
			$imageFour = mysql_result($resetquery, 1, "source");
			$imageFive = mysql_result($resetquery, 2, "source");
		}
		else if(7-$numpics == 4) {
			$resetquery = mysql_query("SELECT * FROM photos ORDER BY score DESC LIMIT 0, 4");
			$imageOne = mysql_result($nextThreeResult, 2, "source");
			$imageTwo = mysql_result($resetquery, 0, "source");
			$imageThree = mysql_result($resetquery, 1, "source");
			$imageFour = mysql_result($resetquery, 2, "source");
			$imageFive = mysql_result($resetquery, 3, "source");
		}
		else if(7-$numpics == 5) {
			$resetquery = mysql_query("SELECT * FROM photos ORDER BY score DESC LIMIT 0, 5");
			$imageOne = mysql_result($resetquery, 0, "source");
			$imageTwo = mysql_result($resetquery, 1, "source");
			$imageThree = mysql_result($resetquery, 2, "source");
			$imageFour = mysql_result($resetquery, 3, "source");
			$imageFive = mysql_result($resetquery, 4, "source");
		}
		else {
			$imageOne = mysql_result($nextThreeResult, 2, "source");
			$imageTwo = mysql_result($nextThreeResult, 3, "source");
			$imageThree = mysql_result($nextThreeResult, 4, "source");	
			$imageFour = mysql_result($nextThreeResult, 5, "source");		
			$imageFive = mysql_result($nextThreeResult, 6, "source");
		}
		
		$imageOneThumb = str_replace("userphotos/","userphotos/thumbs/", $imageOne);
		$imageTwoThumb = str_replace("userphotos/","userphotos/thumbs/", $imageTwo);
		$imageThreeThumb = str_replace("userphotos/","userphotos/thumbs/", $imageThree);
		$imageFourThumb = str_replace("userphotos/","userphotos/thumbs/", $imageFour);		
		$imageFiveThumb = str_replace("userphotos/","userphotos/thumbs/", $imageFive);
	}
}

//else if they came from newest
else if($view == 'n') {
	//find where this pic is in the order
	$index = findPicNew($image);
	if($index == 0) {
		$testquery = mysql_query("SELECT * FROM photos ORDER BY id ASC");
		$imageBefore = mysql_result($testquery, 0, "source");
		$index++;
		$nextThreeQuery = "SELECT * FROM photos ORDER BY id DESC LIMIT $index, 5";
		$nextThreeResult = mysql_query($nextThreeQuery);
		$imageOne = mysql_result($nextThreeResult, 0, "source");
		$imageOneThumb = str_replace("userphotos/","userphotos/thumbs/", $imageOne);
		$imageTwo = mysql_result($nextThreeResult, 1, "source");
		$imageTwoThumb = str_replace("userphotos/","userphotos/thumbs/", $imageTwo);
		$imageThree = mysql_result($nextThreeResult, 2, "source");
		$imageThreeThumb = str_replace("userphotos/","userphotos/thumbs/", $imageThree);
        	$imageFour = mysql_result($nextThreeResult, 3, "source");
		$imageFourThumb = str_replace("userphotos/","userphotos/thumbs/", $imageFour);
        	$imageFive = mysql_result($nextThreeResult, 4, "source");
		$imageFiveThumb = str_replace("userphotos/","userphotos/thumbs/", $imageFive);
	}
	else {
		$index--;
		$nextThreeQuery = "SELECT * FROM photos ORDER BY id DESC LIMIT $index, 7";
		$nextThreeResult = mysql_query($nextThreeQuery);
		$numpics = mysql_num_rows($nextThreeResult);
		$imageBefore = mysql_result($nextThreeResult, $x, "source");
        	
		if(7-$numpics == 1) {
			$resetquery = mysql_query("SELECT * FROM photos ORDER BY id DESC LIMIT 0, 1");
			$imageOne = mysql_result($nextThreeResult, 2, "source");
			$imageTwo = mysql_result($nextThreeResult, 3, "source");
			$imageThree = mysql_result($nextThreeResult, 4, "source");	
			$imageFour = mysql_result($nextThreeResult, 5, "source");	
			$imageFive = mysql_result($resetquery, 0, "source");
		}
		else if(7-$numpics == 2) {
			$resetquery = mysql_query("SELECT * FROM photos ORDER BY id DESC LIMIT 0, 2");
			$imageOne = mysql_result($nextThreeResult, 2, "source");
			$imageTwo = mysql_result($nextThreeResult, 3, "source");
			$imageThree = mysql_result($nextThreeResult, 4, "source");
			$imageFour = mysql_result($resetquery, 0, "source");
			$imageFive = mysql_result($resetquery, 1, "source");
		}
		else if(7-$numpics == 3) {
			$resetquery = mysql_query("SELECT * FROM photos ORDER BY id DESC LIMIT 0, 3");
			$imageOne = mysql_result($nextThreeResult, 2, "source");
			$imageTwo = mysql_result($nextThreeResult, 3, "source");
			$imageThree = mysql_result($resetquery, 0, "source");
			$imageFour = mysql_result($resetquery, 1, "source");
			$imageFive = mysql_result($resetquery, 2, "source");
		}
		else if(7-$numpics == 4) {
			$resetquery = mysql_query("SELECT * FROM photos ORDER BY id DESC LIMIT 0, 4");
			$imageOne = mysql_result($nextThreeResult, 2, "source");
			$imageTwo = mysql_result($resetquery, 0, "source");
			$imageThree = mysql_result($resetquery, 1, "source");
			$imageFour = mysql_result($resetquery, 2, "source");
			$imageFive = mysql_result($resetquery, 3, "source");
		}
		else if(7-$numpics == 5) {
			$resetquery = mysql_query("SELECT * FROM photos ORDER BY id DESC LIMIT 0, 5");
			$imageOne = mysql_result($resetquery, 0, "source");
			$imageTwo = mysql_result($resetquery, 1, "source");
			$imageThree = mysql_result($resetquery, 2, "source");
			$imageFour = mysql_result($resetquery, 3, "source");
			$imageFive = mysql_result($resetquery, 4, "source");
		}
		else {
			$imageOne = mysql_result($nextThreeResult, 2, "source");
			$imageTwo = mysql_result($nextThreeResult, 3, "source");
			$imageThree = mysql_result($nextThreeResult, 4, "source");	
			$imageFour = mysql_result($nextThreeResult, 5, "source");		
			$imageFive = mysql_result($nextThreeResult, 6, "source");
		}
		
		$imageOneThumb = str_replace("userphotos/","userphotos/thumbs/", $imageOne);
		$imageTwoThumb = str_replace("userphotos/","userphotos/thumbs/", $imageTwo);
		$imageThreeThumb = str_replace("userphotos/","userphotos/thumbs/", $imageThree);
		$imageFourThumb = str_replace("userphotos/","userphotos/thumbs/", $imageFour);		
		$imageFiveThumb = str_replace("userphotos/","userphotos/thumbs/", $imageFive);
	}
}


//else if they came from topranked
else if($view == 'r') {
	//find where this pic is in the order
	$index = findPicTop($image);
	if($index == 0) {
		$testquery = mysql_query("SELECT * FROM photos ORDER BY points ASC");
		$imageBefore = mysql_result($testquery, 0, "source");
		$index++;
		$nextThreeQuery = "SELECT * FROM photos ORDER BY points DESC LIMIT $index, 5";
		$nextThreeResult = mysql_query($nextThreeQuery);
		$imageOne = mysql_result($nextThreeResult, 0, "source");
		$imageOneThumb = str_replace("userphotos/","userphotos/thumbs/", $imageOne);
		$imageTwo = mysql_result($nextThreeResult, 1, "source");
		$imageTwoThumb = str_replace("userphotos/","userphotos/thumbs/", $imageTwo);
		$imageThree = mysql_result($nextThreeResult, 2, "source");
		$imageThreeThumb = str_replace("userphotos/","userphotos/thumbs/", $imageThree);
        	$imageFour = mysql_result($nextThreeResult, 3, "source");
		$imageFourThumb = str_replace("userphotos/","userphotos/thumbs/", $imageFour);
        	$imageFive = mysql_result($nextThreeResult, 4, "source");
		$imageFiveThumb = str_replace("userphotos/","userphotos/thumbs/", $imageFive);
	}
	else {
		$index--;
		$nextThreeQuery = "SELECT * FROM photos ORDER BY points DESC LIMIT $index, 7";
		$nextThreeResult = mysql_query($nextThreeQuery);
		$numpics = mysql_num_rows($nextThreeResult);
		$imageBefore = mysql_result($nextThreeResult, $x, "source");
        	
		if(7-$numpics == 1) {
			$resetquery = mysql_query("SELECT * FROM photos ORDER BY points DESC LIMIT 0, 1");
			$imageOne = mysql_result($nextThreeResult, 2, "source");
			$imageTwo = mysql_result($nextThreeResult, 3, "source");
			$imageThree = mysql_result($nextThreeResult, 4, "source");	
			$imageFour = mysql_result($nextThreeResult, 5, "source");	
			$imageFive = mysql_result($resetquery, 0, "source");
		}
		else if(7-$numpics == 2) {
			$resetquery = mysql_query("SELECT * FROM photos ORDER BY points DESC LIMIT 0, 2");
			$imageOne = mysql_result($nextThreeResult, 2, "source");
			$imageTwo = mysql_result($nextThreeResult, 3, "source");
			$imageThree = mysql_result($nextThreeResult, 4, "source");
			$imageFour = mysql_result($resetquery, 0, "source");
			$imageFive = mysql_result($resetquery, 1, "source");
		}
		else if(7-$numpics == 3) {
			$resetquery = mysql_query("SELECT * FROM photos ORDER BY points DESC LIMIT 0, 3");
			$imageOne = mysql_result($nextThreeResult, 2, "source");
			$imageTwo = mysql_result($nextThreeResult, 3, "source");
			$imageThree = mysql_result($resetquery, 0, "source");
			$imageFour = mysql_result($resetquery, 1, "source");
			$imageFive = mysql_result($resetquery, 2, "source");
		}
		else if(7-$numpics == 4) {
			$resetquery = mysql_query("SELECT * FROM photos ORDER BY points DESC LIMIT 0, 4");
			$imageOne = mysql_result($nextThreeResult, 2, "source");
			$imageTwo = mysql_result($resetquery, 0, "source");
			$imageThree = mysql_result($resetquery, 1, "source");
			$imageFour = mysql_result($resetquery, 2, "source");
			$imageFive = mysql_result($resetquery, 3, "source");
		}
		else if(7-$numpics == 5) {
			$resetquery = mysql_query("SELECT * FROM photos ORDER BY points DESC LIMIT 0, 5");
			$imageOne = mysql_result($resetquery, 0, "source");
			$imageTwo = mysql_result($resetquery, 1, "source");
			$imageThree = mysql_result($resetquery, 2, "source");
			$imageFour = mysql_result($resetquery, 3, "source");
			$imageFive = mysql_result($resetquery, 4, "source");
		}
		else {
			$imageOne = mysql_result($nextThreeResult, 2, "source");
			$imageTwo = mysql_result($nextThreeResult, 3, "source");
			$imageThree = mysql_result($nextThreeResult, 4, "source");	
			$imageFour = mysql_result($nextThreeResult, 5, "source");		
			$imageFive = mysql_result($nextThreeResult, 6, "source");
		}
		
		$imageOneThumb = str_replace("userphotos/","userphotos/thumbs/", $imageOne);
		$imageTwoThumb = str_replace("userphotos/","userphotos/thumbs/", $imageTwo);
		$imageThreeThumb = str_replace("userphotos/","userphotos/thumbs/", $imageThree);
		$imageFourThumb = str_replace("userphotos/","userphotos/thumbs/", $imageFour);		
		$imageFiveThumb = str_replace("userphotos/","userphotos/thumbs/", $imageFive);
	}
}

?>

<!DOCTYPE HTML>
<head>

	<meta charset = "UTF-8">
	<title> Sell, share and discover brilliant photography </title>
    
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="css/960grid.css"/>
	<link rel="stylesheet" type="text/css" href="css/reset.css"/> 
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
    <link rel="stylesheet" media='screen and (max-width:640px)' href="css/640.css"/>
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
    
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="js/bootstrap.js" type="text/javascript"></script>
	<script src="js/modernizer.js"></script>
	
    <style type="text/css">
		.show
		{
			display:block !important;
		}
		
		#notify
		{
			width:40px;
			margin: 0 0 0 5px;
			background:#d96f62;
			padding: 5px;
		}
		#notify:hover
		{
			background: rgba(255,255,255,.55);
		}
		#drawer
		{
			width:0px;
			background: url('graphics/noise.png');
			color:#fff;
			white-space: normal;
			font-size: 10px;
			position:fixed;
			height:100%;
			box-shadow: inset 0 0 5px rgba(0,0,0,.25);
			border-radius:0 5px 5px 0;
			margin: 5px 0 0 -5px;
			z-index: 1000;
		}
		.notifications
		{
			font-family:"helvetica neue", helvetica, arial,sans-serif; 
			font-size:20px;
			font-weight: 500;
			color:#fff;
			margin-left: -200px;
			width:200px;

		}
		.test
		{
			height:250px;
			background: rgba(200,200,200,.6);
			box-shadow: 0 0 2px #666;
			margin: 4px 20px 0 0;
		}
		.test2
		{
			height:50px;
			background: rgba(200,200,200,.6);
			box-shadow: 0 0 2px #666;
			margin: 7px 4px !important;
			width:125px;
			float: right;
		}
		.x
		{
			background:none !important;
			color:#222 !important;
			padding: 0 !important;
			box-shadow: 0 0 0 !important;
			margin:10px 5px 0 5px !important;
			border: none !important;
			font-size: 14px;
		}
		/*.arrow-right {
	width: 0; 
	height: 0; 
	border-top: 13px solid transparent;
	border-bottom: 13px solid transparent;
	box-shadow: inset 0 0 1px #999;
	border-right: 13px solid rgba(245,245,245,1);
	position: absolute;
	top:33px;
	left:75px;
}*/
	</style>
    
    <script language="javascript" type="text/javascript">

function createRequestObject() {

    var ajaxRequest;  //ajax variable
	
	try{
		// Opera 8.0+, Firefox, Safari
		ajaxRequest = new XMLHttpRequest();
	} catch (e){
		// Internet Explorer Browsers
		try{
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try{
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e){
				// Something went wrong
				alert("Your browser broke!");
				return false;
			}
		}
	}
    
    return ajaxRequest;
    
}

function ajaxFunction(){
	
    ajaxRequest = createRequestObject();
    
    // Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4  && ajaxRequest.status == 200){
			var ajaxDisplay = document.getElementById('ajaxFave');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
		}
	}
	
	var age = "<?php echo $email; ?>";
    var image = "<?php echo $image; ?>";
	var queryString = "?age=" + age + "&image=" + image;
	ajaxRequest.open("GET", "ajaxfave.php" + queryString, true);
	ajaxRequest.send(null); 

}

function Rank(rank){
	
    ajaxRequest = createRequestObject();
    
	// Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4 && ajaxRequest.status == 200){
			var ajaxDisplay = document.getElementById('ajaxRank');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
		}
	}
	
	var rank = rank;
    var image = "<?php echo $image; ?>";
    var ranker = "<?php echo $email; ?>";
	var queryString = "?rank=" + rank + "&image=" + image + "&ranker=" + ranker;
	ajaxRequest.open("GET", "ajaxrank.php" + queryString, true);
	ajaxRequest.send(null); 

}

function ajaxFollow(){

    ajaxRequest = createRequestObject();
	
    // Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
            var ajaxDisplay = document.getElementById('ajaxFollow');
            
		}
	}
	
	var follower = "<?php echo $email; ?>";
    var followee = "<?php echo $emailaddress; ?>";
	var queryString = "?follower=" + follower + "&followee=" + followee;
	ajaxRequest.open("GET", "ajaxfollow.php" + queryString, true);
	ajaxRequest.send(null); 

}

</script>

<script type="text/javascript" >

$(function() {
$(".submit").click(function() 
{
var firstname = '<?php echo $sessionfirst; ?>';
var lastname = '<?php echo $sessionlast; ?>';
var email = '<?php echo $email; ?>';
var photo = '<?php echo $imageid; ?>';
var userpic = '<?php echo $sessionpic; ?>';
var viewerid = '<?php echo $sessionid; ?>';
var viewerrep = '<?php echo $reputationme; ?>';
var comment = $("#photocomment").val();
var dataString = 'firstname='+ firstname + '&lastname=' + lastname + '&email=' + email + '&comment=' + comment + '&userpic=' + userpic + '&photo=' + photo + '&viewerid=' + viewerid + '&viewerrep=' + viewerrep;
if(email=='' || comment=='')
{
alert('Please Give Valid Details');
}
else
{
//Loading GIF
$.ajax({
type: "POST",
url: "commentajax.php",
data: dataString,
cache: false,
success: function(html){
$("ol#update").append(html);
$("ol#update li:last").fadeIn("slow");
$("#flash").hide();
}
});
}return false;
}); });

var orgimage = <?php echo $imageid; ?>;
var image = <?php echo $imageid; ?>;
var view = "<?php echo $view; ?>";


function ajaxNextPics(){
    
    ajaxRequest = createRequestObject();
	
    // Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
            
            var json = eval('(' + ajaxRequest.responseText +')');
            var nextimg1 = document.getElementById('nextimg1');
            nextimg1.src = json.nextimg3;
            var nextimg1id = document.getElementById('nextimg1id');
            nextimg1id.href = 'fullsize.php?imageid=' + json.nextimg3id +'&view=' + view;
            var nextimg2 = document.getElementById('nextimg2');
            nextimg2.src = json.nextimg2;
            var nextimg2id = document.getElementById('nextimg2id');
            nextimg2id.href = 'fullsize.php?imageid=' + json.nextimg2id +'&view=' + view;
            var nextimg3 = document.getElementById('nextimg3');
            nextimg3.src = json.nextimg1;
            var nextimg3id = document.getElementById('nextimg3id');
            nextimg3id.href = 'fullsize.php?imageid=' + json.nextimg1id +'&view=' + view;

            
		}
                
	}
    
    var queryString = "?image=" + image;
	ajaxRequest.open("GET", "ajaxNextPics.php" + queryString, true);
	ajaxRequest.send(null);
    if(image < orgimage) { 
        image += 1;
    }
}


function ajaxPrevPics(){
        
    ajaxRequest = createRequestObject();
	
    // Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
            
            var json = eval('(' + ajaxRequest.responseText +')');
            var nextimg1 = document.getElementById('nextimg1');
            nextimg1.src = json.previmg1;
            var nextimg1id = document.getElementById('nextimg1id');
            nextimg1id.href = 'fullsize.php?imageid=' + json.previmg1id +'&view=' + view;
            var nextimg2 = document.getElementById('nextimg2');
            nextimg2.src = json.previmg2;
            var nextimg2id = document.getElementById('nextimg2id');
            nextimg2id.href = 'fullsize.php?imageid=' + json.previmg2id +'&view=' + view;
            var nextimg3 = document.getElementById('nextimg3');
            nextimg3.src = json.previmg3;
            var nextimg3id = document.getElementById('nextimg3id');
            nextimg3id.href = 'fullsize.php?imageid=' + json.previmg3id +'&view=' + view;
            
		}
                
	}
    
    var queryString = "?image=" + image;
	ajaxRequest.open("GET", "ajaxPrevPics.php" + queryString, true);
	ajaxRequest.send(null); 
    if(image > 0) {
        image -= 1;
    }
}

</script>
</head>

<!--Collection Modal-->
<div class="modal hide fade" id="collectionmodal" style="overflow:hidden;border:5px solid rgba(102,102,102,.8);">
  
<?php
 
if($_SESSION['loggedin'] !== 1) {

echo'
<div class="modal-header" style="background-color:#111;color:#fff;">
<a style="float:right" class="btn btn-success" data-dismiss="modal">Close</a>
<img style="margin-top:-2px;" src="graphics/aperture_white.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">Please login to add this photo to a collection</span>
  </div>
 
<div id="modal-body" style="width:450px;height:145px;">

<div id="content" style="font-size:16px;width:560px;font-family:helvetica,arial;font-weight:100;background-color:rgb(245,245,245);height:150px;">
		
<img class="roundedall" style="margin-left:20px;margin-top:20px;" src="',$image,'" 
height="100px" width="100px" />

<div style="width:350px;margin-left:140px;margin-top:-75px;line-height:1.48;">              

',$caption,'<br />

By: 
<a style="color:black;" href="viewprofile.php?u=',$user,'">',$firstname,' ',$lastname,'</a><br />   

</div>
</div>';
    
}

    if($_SESSION['loggedin'] == 1) {
		
        echo'
        <div class="modal-header" style="background-color:#111;color:#fff;">
        <a style="float:right" class="btn btn-success" data-dismiss="modal"href="fullsize.php?image=', $image,'&v=',$view,'&f=1">Close</a>
        <img style="margin-top:-2px;" src="graphics/aperture_white.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">Choose a collection to add this photo to:</span>
        </div>

        <div id="modal-body" style="width:450px;">

        <div id="content" style="font-size:16px;width:560px;font-family:helvetica,arial;font-weight:100;background-color:rgb(245,245,245);max-height:25em;overflow-y:scroll;">
		
        <img class="roundedall" style="margin-left:20px;margin-top:20px;" src="',$image,'" 
height="100px" width="100px" />

        <div style="width:350px;margin-left:140px;margin-top:-95px;line-height:1.48;">';           
           
        $vieweremail = $_SESSION['email'];
		$collectioncheck = mysql_query("SELECT id,photos,title FROM collections WHERE owner = '$vieweremail'") or die(mysql_error());
        $numcollections = mysql_num_rows($collectioncheck);
        
        echo'<form action="fullsize.php?imageid=',$imageid,'&v=',$view,'&action=savecol" method="POST" />';
                
        if($numcollections > 0) {
        
        echo'Your collections to add to:<br />
        
        <div style="padding-top:15px;">';
        
        for($iii=0; $iii < $numcollections; $iii++) {
        
            $collectionphotos = mysql_result($collectioncheck,$iii,photos);
            $collectionid = mysql_result($collectioncheck,$iii,id);
            $collectiontitle = mysql_result($collectioncheck,$iii,title);
            $collectioncover = mysql_result($collectioncheck,$iii,cover);
            
            //search if image already in this set
            $match=strpos($collectionphotos, $imageid);
            
            if(!$match) {
            
                if($collectioncover) {
                
                    echo'<img src="',$cover,'" width="80" />';
                }
                
                elseif(!$collectioncover && !$collectionphotos) {
                
                     echo'<img src="graphics/no_photos.png" width="80" />';
                
                }
                
                elseif(!$collectioncover && $collectionphotos) {
                
                     $collphotosarray = explode(" ",$collectionphotos);
                     $firstphoto = mysql_query("SELECT source FROM photos WHERE id = '$collphotosarray[0]'");
                     $source = mysql_result($firstphoto,0,'source'); 
                     $source = str_replace("userphotos/","userphotos/medthumbs/",$source);  
                         
                     echo'<img src="',$source,'" width="80" />';
                
                }
                
                echo'&nbsp;&nbsp;&nbsp;<input type="checkbox" name="collection[]" value="',$collectionid,'">&nbsp;&nbsp;&nbsp;',$collectiontitle,'<br /><br />';
                        
            }
            
            elseif($match) {
            
            echo'<br />';
            
            }
            
        }   
           
        echo'</div><button class="btn btn-success" type="submit" value="Save">Add to collection(s)</button>
        </form>';
        
        }
        
        else {
        
        echo'<div style="padding-top:35px;">You have no collections. <a href="myprofile.php?view=collections&option=newcollection">Create one?</a><br /><br /></div>';
        
        }
        
        echo'
        <br /><br />
        </div>
        </div>';
        
    }
    
        
?>

</div>
</div>

<body id="body" >

<?php navbar(); ?>

<div class="container_custom" style="margin:50px 0 0 120px;width:1080px;">
	
	<div class="bloc_4" style="float:right;width:24.07%;margin:45px 0 0 0;display:block;">

		<!--ID TAG-->
		<div id="Tag">
			<div id="topHalf">
				<img src="../<?php echo $profilepic; ?>" />
				<header style="min-width:100px;"> <?php echo $fullname; ?> </header>
                
                 <?php
                    if($_SESSION['loggedin'] == 1) {
                        $emailquery=("SELECT * FROM userinfo WHERE emailaddress ='$email'");
                        $emailresult=mysql_query($emailquery);
                        $prevemails=mysql_result($emailresult, 0, "following");
                        $viewerfirst = mysql_result($emailresult, 0, "firstname");
                        $viewerlast = mysql_result($emailresult, 0, "lastname");
                        $search_string=$prevemails;
                        $regex="/$emailaddress/";
                        $match=preg_match($regex,$search_string);

                        if($match) {
                            echo'<a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$imageid,'&v=',$view,'&uf=1"><button id="follow"> Following </button></a>';
                        }
                        elseif(!$match) {
                            echo'<a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$imageid,'&v=',$view,'&fw=1"><button id="follow"> Follow </button></a>';
                        }
                    }
                    else {
                        echo'<button id="follow"> Follow </button>';
                    }
                ?>

			</div>
			<div id="bottomHalf">  
				<header> Photos: 345 </header>
				<header> Rep: <?php echo $reputation; ?> </header>
			</div>	
		</div>
        
		<!--STATS BAR-->
		<div id="statsBar">
			<ul class="numbers">
				<li id="ajaxRank"><?php echo $ranking; ?><span>/10</span> </li>
				<li> <?php echo $collected; ?> </li>
				<li id="ajaxFave"> <?php echo $faves; ?> </li>
				<li> <?php echo $price; ?></li>
			</ul>
			<ul>
				<li id="rankButton">  <img src="graphics/rank_b_c.png" /> Rank </li>
				<a style="color:#333;text-decoration:none;" data-toggle="modal" data-backdrop="static" href="#collectionmodal">
                <li><img src="graphics/collection_b_c.png"/>  Collect </li>
                </a>
                
                <?php
                    if($_SESSION['loggedin'] == 1) {
                        $vieweremail = $_SESSION['email'];
                        //run a query to be used to check if the image is already there
                        $check = mysql_query("SELECT * FROM userinfo WHERE emailaddress='$vieweremail'") or die(mysql_error());
                        $viewerfirst = mysql_result($check, 0, "firstname");
                        $viewerlast = mysql_result($check, 0, "lastname");
                        $imagelink2=str_replace(" ","", $image);
                        //create the image variable to be used in the query, appropriately escaped
                        $queryimage = "'" . $image . "'";
                        $queryimage = ", " . $queryimage;
                        $queryimage = addslashes($queryimage);
                        //search for the image in the database as a check for repeats
                        $mycheck = mysql_result($check, 0, "faves");
                        $search_string = $mycheck;
                        $regex=$image;
                        $match=strpos($search_string, $regex);

                        if($match) {
                            echo'<li> <img src="graphics/fave_b_c.png"/> Fave </li>';
                        }
                        elseif(!$match) {
                            echo'<li onclick="ajaxFunction()"> <img src="graphics/fave_b_c.png"/> Fave </li>';
                        }
                    }
                    else {
                        echo'<li> <img src="graphics/fave_b_c.png"/> Fave </li>';
                    }
                ?>
                
                <a style="color:#333;text-decoration:none;" href="fullsizemarket.php?imageid=<?php echo $imageid; ?>"><li> <img src="graphics/market_b_c.png"/> Purchase </li></a>
			</ul>

			<ul id="Rank">
				<li onclick="Rank(1)"> 1 </li>
				<li onclick="Rank(2)"> 2 </li>
				<li onclick="Rank(3)"> 3 </li>
				<li onclick="Rank(4)"> 4 </li>
				<li onclick="Rank(5)"> 5 </li>
				<li onclick="Rank(6)"> 6 </li>
				<li onclick="Rank(7)"> 7 </li>
				<li onclick="Rank(8)"> 8 </li>
				<li onclick="Rank(9)"> 9 </li>
				<li onclick="Rank(10)"> 10 </li>
			</ul>

		</div>

		<!--PREVIEW BAR-->
		<div id="nextPhotos" style="margin-top:10px;">
			<header> 
            <?php 
                    
                    if($view == 't') {
                    echo'<span style="font-family:helvetica;font-weight:100;font-size:14px;">Browse More Trending Photos:</span>';
                    }
                    
                    elseif($view == 'n') {
                    echo'<span style="font-family:helvetica;font-weight:100;font-size:14px;">Browse More Newest Photos:</span>';
                    }
                    
                    elseif($view == 'r') {
                    echo'<span style="font-family:helvetica;font-weight:100;font-size:14px;">Browse More Top Ranked Photos:</span>';
                    }
            
            ?>
            </header>
				<div id="nextPhotosInner">
                    
                    <a href="javascript:ajaxNextPics()">
                        <div id="arrowLeft"> 
                        </div>
                    </a>
                    
                    <a href="javascript:ajaxPrevPics()">
                        <div id="arrowRight"> 
                        </div>	
                    </a>
                    
					<div id="nextPhotosContainer">	
							<a id="nextimg1id" href="fullsize.php?image=<?php echo $imageOne; ?>&v=<?php echo $view; ?>"><img src="https://photorankr.com/<?php echo $imageOneThumb; ?>" id="nextimg1id" /></a>
							<a id="nextimg2id" href="fullsize.php?image=<?php echo $imageTwo; ?>&v=<?php echo $view; ?>"><img src="https://photorankr.com/<?php echo $imageTwoThumb; ?>" id="nextimg2id" /></a>
							<a id="nextimg3id" href="fullsize.php?image=<?php echo $imageThree; ?>&v=<?php echo $view; ?>"><img src="https://photorankr.com/<?php echo $imageThreeThumb; ?>" id="nextimg3id" /></a>
                            
					</div>
			</div>
		</div>
    
    <!--SHARE BAR-->
		<div id="shareBar">
			<ul>
				<li style="border-radius:5px 0 0 5px;width:47px;text-align:center;"> <img src="graphics/share_b.png" style="opacity:1;box-shadow: none;padding-bottom:2px;width:21px;height:19px;margin:0 0 0 12px;"/> Share  </li>
				<li > <img src="graphics/twitter_s.png"/> </li>
				<li > <img src="graphics/facebook_s.png"/> </li>
				<li > <img src="graphics/pinterest_s.png"/> </li>
				<li> <img src="graphics/more_s.png"/> </li>
			</ul>
		</div>
        
    <!--PHOTO STORY-->
    <?php
        if($about) {
		echo'<div id="photoStory">
			<header> Behind the Lens </header>
			<p>',$about,'</p>
		</div>';
        }
    ?>
    
    <!--ABOUT PHOTO-->
		<div id="AboutPhoto">
			<header> About </header>
			<ul>
                <?php 
                if($views) {
				echo'<li><img src="graphics/views.png"/>  Views: <span style="margin-left:38px;">',$views,'</span></li>';
                }
                if($camera) {
				echo'<li><img src="graphics/camera.png"/> Camera: <span style="margin-left:28px;">',$camera,'</span></li>';
                }
                if($aperture) {
				echo'<li><img src="graphics/aperature.png"/> Aperture: <span style="margin-left:24px;">',$aperture,'</span></li>';
                }
                if($focallength) {
				echo'<li> <img src="graphics/focalLength.png"/> Focal Length:  <span style="margin-left:3px;">',$focallength,'</span> </li>';
                }
                if($lens) {
				echo'<li> <img src="graphics/lens.png"/> Lens: <span style="margin-left:42px;">',$lens,'</span> </li>';
                }
                if($shutterspeed) {
				echo'<li> <img src="graphics/shutterSpeed.png"/> Shutter: <span style="margin-left:30px;">',$shutterspeed,'</span> </li>';
                }
                if($date) {
                    echo'<li> <img src="graphics/captureDate.png" style="width:16px;margin-left:-3px;"/> Capture Date <span> <?php echo $date; ?> </span></li>';
                }
                if($fullname) {
                    echo'<li> <img src="graphics/copyright.png" style="width:15px;margin-left:-2px;"/> Copyright <span> <?php echo $fullname; ?> </span></li>';
                }
				if($location) { 
                    echo'<li> <img src="graphics/location.png" style="width:10px;margin: 0 8px 0 0;"/> Location: <span> <?php echo $location; ?> </span></li>';
                }

                ?>
			</ul>
		</div>
        

	</div>
	
	<!--TITLE-->
	<div class="bloc_12" style="float:left;display:block;width:74.07%;" id="title">
		<header> <?php echo $caption; ?> <img src="graphics/uploadDate.png"/>  <span> Uploaded <?php echo $time; ?> </span> </header>
	</div>

	<!--IMAGE-->
	<div class="bloc_12" style="float:left;display:block;width:74.07%;" id="imgDisplay">
		<img src="../<?php echo $image; ?>" />
	</div>

	<!--COMMENTS-->
	<div class="bloc_12" style="float:left;width:74.07%;">

        <?php
        //AJAX COMMENT
        if($_SESSION['loggedin'] == 1) {
            echo'
            <div style="width:790px;"> 
            <form action="#" method="post" style="margin-top:5px;padding-bottom:5px;">        
           
            <div id="comment">
                <div class="commentTag">
				<img src="https://photorankr.com/',$sessionpic,'" height="55" width="50" />
				<header> Rep: ',$reputationme,' </header>
                </div>
            <div>
            
                <textarea id="photocomment" style="margin-top:15px;width:675px;height:65px;" placeholder="Leave feedback for ',$firstname,'&#8230;"></textarea>
                <input style="margin-right:45px;float:right;" type="submit" class="submit btn btn-success" value="Comment"/>
            </div>
            </form>
        
            <!--AJAX COMMENTS-->
            <div class="float:left;"> 
                <ol id="update" class="timeline">
                </ol>
            </div>';
        }
            
        //SHOW PREVIOUS COMMENTS
        $grabcomments = mysql_query("SELECT * FROM comments WHERE imageid = '$imageID' ORDER BY id DESC");
        $numcomments = mysql_num_rows($grabcomments);
        
        for($iii = 0; $iii < $numcomments; $iii++) {
        
            $comment = mysql_result($grabcomments,$iii,'comment');
            $commentid = mysql_result($grabcomments,$iii,'id');
            $commenttime = mysql_result($grabcomments,$iii,'time');
            $commenteremail = mysql_result($grabcomments,$iii,'commenter');
            $commenterinfo = mysql_query("SELECT user_id,firstname,lastname,profilepic,reputation FROM userinfo WHERE emailaddress = '$commenteremail'");
            $commentername = mysql_result($commenterinfo,0,'firstname') ." ". mysql_result($commenterinfo,0,'lastname');
            $commenterid = mysql_result($commenterinfo,0,'user_id');
            $commenterpic = mysql_result($commenterinfo,0,'profilepic');
            $commenterrep = number_format(mysql_result($commenterinfo,0,'reputation'),2);
        
        
        echo'
            <div id="comment" style="width:760px;">
                <div class="commentTag">
				<img src="https://photorankr.com/',$commenterpic,'" height="55" width="50" />
				<header> Rep: ',$commenterrep,' </header>
			</div>
			<div class="commentName">
				<header> <a href="viewprofile.php?u=',$commenterid,'">',$commentername,'</a> </header>
				<img src="graphics/uploadDate.png"/>
				<p> ',$commenttime,' </p>
			</div>
			<div class="commentBody"><p>',$comment,'</p>';
            
                if($commenterid == $sessionid) {
                    echo'
                        <div id="edit"><a href="fullsize.php?image=',$image,'&action=editcomment&cid=',$commentid,'#',$commentid,'"> Edit Comment</a></div>';
                }
                
                 if(($email == $emailaddress) || ($commenteremail == $email)) {
                    echo'
                        <div id="edit"><a href="fullsize.php?image=',$image,'&action=deletecomment&cid=',$commentid,'">Delete Comment</a></div>';
                }
                
                if($_GET['action'] == 'editcomment' && $commentid == $_GET['cid']) {
                
                    echo'
                    <form action="fullsize.php?image=',$image,'#',$commentid,'" method="POST" />
                    <textarea style="height:55px;width:95%;margin-left:10px;margin-top:10px;" name="commentedit">',$comment,'</textarea>
                    <input type="hidden" name="commentid" value="',$commentid,'" />
                    <br />
                    <input type="submit" class="btn btn-primary" style="float:right;font-size:12px;margin-right:10px;margin-bottom:5px;" value="Save Edit" />
                    </form>';
                    
                }
            
            echo'
            </div>
		</div>';
            
        }
        
        $imagenew=str_replace("userphotos/","", $image);
        $imagelink=str_replace(" ","", $image);
        $searchchars=array('.jpg','.png','.tiff','.JPG','.jpeg','.JPEG','.gif');
        $imagenew=str_replace($searchchars,"", $imagenew);
        $txt=".txt";
        $file = "comments/" . $imagenew . $txt;
        echo'
        <div style="float:left;width:520px;padding:10px;font-size:13px;font-family:helvetica;font-weight:300;color:#555;">';
        @include("$file"); 
        echo'</div>';
                
    ?>
        
    </div>	


	</div>

	
</div>

</body>

<?php 
//add to the views column
$updatequery = mysql_query("UPDATE photos SET views=views+1 WHERE source='$image'") or die(mysql_error());
?>

<script type="text/javascript">
(function(){

	$('#rankButton').on('hover', function() {
		$('#Rank').toggleClass('OPEN');

	});
	$('#Rank').on('hover', function() {
			$('#Rank').toggleClass('OPEN');
		});

})();
</script>

</html>
			