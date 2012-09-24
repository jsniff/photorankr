<?php

//connect to the database
require "db_connection.php";
require "functionsnav.php";
require "timefunction.php";

//start the session
session_start();

    // if login form has been submitted
    if (htmlentities($_GET['action']) == "login") { 
        login();
    }
    else if(htmlentities($_GET['action']) == "logout") { 
        logout();
    }

    $email = $_SESSION['email'];
    
    $findreputationme = mysql_query("SELECT user_id,reputation,profilepic,firstname,lastname FROM userinfo WHERE emailaddress = '$email'");
    $reputationme = mysql_result($findreputationme,0,'reputation');
    $sessionpic = mysql_result($findreputationme,0,'profilepic');
    $sessionuserid =  mysql_result($findreputationme,0,'user_id');
    $sessionfirst =  mysql_result($findreputationme,0,'firstname');
    $sessionlast =  mysql_result($findreputationme,0,'lastname');
    $sessionid =  mysql_result($findreputationme,0,'user_id');
    $sessionname = mysql_result($findreputationme,0,'firstname') ." ". mysql_result($findreputationme,0,'lastname');
    $currenttime = time();
    
    //GET THE IMAGE
$image = addslashes($_GET['image']);

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
$prevpoints=$row['points'];
$prevvotes=$row['votes'];
$ranking=number_format(($prevpoints/$prevvotes),1);
$imageID=$row['id'];
$price=mysql_result($result, 0, "price");
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
$fullname = (strlen($fullname ) > 14) ? substr($fullname,0,12). " &#8230;" : $fullname;

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

//QUERY FOR NOTIFICATIONS
$currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$email'";
$currentnotsquery = mysql_query($currentnots);
$currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");

//DE-HIGHLIGHT NOTIFICATIONS IF CLICKED ON

if(isset($_GET['id'])){
$id = htmlentities($_GET['id']);
$idformatted = $id . " ";
$unhighlightquery = "UPDATE userinfo SET unhighlight = CONCAT(unhighlight,'$idformatted') WHERE emailaddress = '$email'";
$unhighlightqueryrun = mysql_query($unhighlightquery);

//notifications query reset 
if($currentnotsresult > 0) {
$notsquery = "UPDATE userinfo SET notifications = 0 WHERE emailaddress = '$email'";
$notsqueryrun = mysql_query($notsquery); }
}

if(isset($_GET['v'])){
	$view = htmlentities($_GET['v']);
}
else {
	$view = 't';
}


//TRENDING PHOTOS FOR 
$trendingfeedquery = "SELECT * FROM photos ORDER BY id DESC LIMIT 0,100";
$trendingfeedresult = mysql_query($trendingfeedquery);

for($i=1; $i<99; $i++) {
$feedrow = mysql_fetch_array($trendingfeedresult);
$score = $feedrow['votes'];
$source = $feedrow['source'];
$caption2 = $feedrow['caption'];
$emailaddress3 = $feedrow['emailaddress'];

//userinfo query
$namequery2="SELECT * FROM userinfo WHERE
emailaddress='$emailaddress3'";
$nameresult2=mysql_query($namequery2);
$row2=mysql_fetch_array($nameresult2);
$firstname2=$row2['firstname'];
$lastname2=$row2['lastname'];

$feedtestquery = mysql_query("SELECT * FROM newsfeed WHERE source='$source' AND type='trending'") or die(mysql_error());
$result = mysql_num_rows($feedtestquery);

if ($score > 2 && $result < 1) {
$type4 = "trending";
$newsfeedtrending="INSERT INTO newsfeed (firstname,lastname,caption,owner,type,source,time) VALUES ('$firstname2','$lastname2','$caption2','$emailaddress3','$type4','$source','$currenttime')";
$trendingnewsquery = mysql_query($newsfeedtrending); 
  
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



//get the flags variable and update the database
$f;
if(isset($_GET['f'])) {
$f=htmlentities($_GET['f']);
}
else {$f=0;}
if ($f==1) {
	if($_SESSION['loggedin'] == 1) {
		//run a query to be used to check if the image is already there
		$check = mysql_query("SELECT * FROM userinfo WHERE emailaddress='$email'") or die(mysql_error());
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
		//if the image has already been favorited
		if($match) {
			//tell them so
			/* echo '<div style="position:absolute;  top:100px; left:820px; font-family: lucida grande, georgia; color:black; font-size:15px;">This photo is already in your favorites!</div>'; */
		}
		else {
			$favesquery="UPDATE userinfo SET faves=CONCAT(faves,'$queryimage') WHERE emailaddress='$email'";
			mysql_query($favesquery);
			mysql_query("UPDATE photos SET faves=faves+1 WHERE source='$image'");
            
             //newsfeed query
        $type = "fave";
        $newsfeedfavequery=mysql_query("INSERT INTO newsfeed (firstname,lastname,emailaddress,type,source,caption,owner,time) VALUES ('$viewerfirst', '$viewerlast', '$email','$type','$image','$caption','$emailaddress','$currenttime')");
     
//notifications query     
$notsquery = "UPDATE userinfo SET notifications = (notifications + 1) WHERE emailaddress = '$emailaddress'";
$notsqueryrun = mysql_query($notsquery);       
 
            
//GRAB SETTINGS LIST
$settingquery = "SELECT settings FROM userinfo WHERE emailaddress = '$email'";
$settingqueryrun = mysql_query($settingquery);
$settinglist = mysql_result($settingqueryrun, 0, "settings");
                                  
$setting_string = $settinglist;
$find = "emailfave";
$foundsetting = strpos($setting_string,$find);
            
            //MAIL PHOTOGRAPHER NOTICE THAT THEIR PHOTO HAS BEEN FAVORITED
          $to = '"' . $firstname . ' ' . $lastname . '"' . '<'.$emailaddress.'>';
          $subject = $viewerfirst . " " . $viewerlast . " favorited one of your photos on PhotoRankr";
          $favemessage = $viewerfirst . " " . $viewerlast . " favorited one of your photos on PhotoRankr
        
To view the photo, click here: https://photorankr.com/fullsize.php?image=".$imagelink2;
          $headers = 'From:PhotoRankr <photorankr@photorankr.com>';
          
          if($foundsetting > 0) {
             mail($to, $subject, $favemessage, $headers); 
          }
          
		}
	}
	else {
		header("Location: signin.php");
		exit();
	}
}


//PORTFOLIO RANKING

$followersquery="SELECT * FROM userinfo WHERE following LIKE '%$emailaddress%'";
	$followersresult=mysql_query($followersquery);
	$numberfollowers = mysql_num_rows($followersresult);
    
    //Grab Overall Portfolio Ranking
    $userphotos="SELECT * FROM photos WHERE emailaddress = '$emailaddress'";
    $userphotosquery=mysql_query($userphotos);
    $numphotos=mysql_num_rows($userphotosquery);
    
    for($iii = 0; $iii < $numphotos; $iii++) {
		$points = mysql_result($userphotosquery, $iii, "points");
        $votes = mysql_result($userphotosquery, $iii, "votes");
        $portfoliopoints+=$points;
        $portfoliovotes+=$votes;
        }
    
    if ($portfoliovotes > 0) {
    $portfolioranking=($portfoliopoints/$portfoliovotes);
    $portfolioranking=number_format($portfolioranking, 2, '.', '');
    
    $scorequery = "UPDATE userinfo SET totalscore = '$portfoliopoints' WHERE emailaddress = '$emailaddress'";    
    $scoreresult = mysql_query($scorequery);
    
    }
    
    else if ($portfoliovotes < 1) {
    $portfolioranking="N/A";
    }	

            
 //DISCOVER SCRIPT
    
  //get the users information from the database
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
  
  
//FOLLOWING QUERIES
$follow;
if(isset($_GET['fw'])) {
$follow=$_GET['fw'];
$email=$_SESSION['email'];
}
else {$follow=0;}

if ($follow==1) {
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
$settingquery = "SELECT * FROM userinfo WHERE emailaddress = '$setting'";
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

//COMMENT QUERIES

if(htmlentities($_POST['comment']) && $_SESSION['loggedin'] == 1) {
    
    $currenttime = time();
    $comment = mysql_real_escape_string(htmlentities($_POST['comment']));
    $insertcomment = mysql_query("INSERT INTO comments (comment,commenter,photoowner,imageid,time) VALUES ('$comment','$email','$emailaddress','$imageID','$currenttime')");
    
    //MAIL TO OWNER OF PHOTO
    $settingquery = mysql_query("SELECT settings FROM userinfo WHERE emailaddress = '$emailaddress'");
    $settinglist = mysql_result($settingquery,0,"settings");
    $check = 'emailcomment';
    $foundsetting = strpos($settinglist,$check);
    
    if($emailaddress != $email) {
    $to = '"' . $firstname . ' ' . $lastname . '"' . '<'.$emailaddress.'>';
    $subject = $sessionname ." commented on your photo on PhotoRankr";
    $message = stripslashes($comment) . "
    
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

?>   

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>"<?php echo $caption; ?>" | PhotoRankr</title>

<meta property="og:image" content="http://photorankr.com/<?php echo $image; ?>">
   <meta name="Generator" content="EditPlus">
  <meta name="Author" content="PhotoRankr, PhotoRankr.com">
  <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
  <meta name="Description" content="PhotoRankr allows photographers of all skill levels to sell and share their work. Create your photostream cutomized to what you want to see. Add photos to your favorites, rank them, and watch them trend. Build your portfolio with Photorankr.">

<link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
<link rel="stylesheet" type="text/css" href="css/bootstrapNew.css"/>
<link rel="stylesheet" type="text/css" href="css/reset.css"/>
<link rel="stylesheet" type="text/css" href="css/all.css"/>
<link rel="stylesheet" type="text/css" href="css/reset.css"/>
<link rel="stylesheet" type="text/css" href="css/style.css"/>
<link rel="stylesheet" type="text/css" href="css/960_24_col.css"/>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script src="bootstrap.js" type="text/javascript"></script>
  <script src="bootstrap-dropdown.js" type="text/javascript"></script>
  <script src="bootstrap-collapse.js" type="text/javascript"></script>
  <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>

     <script src="bootstrap-dropdown.js" type="text/javascript"></script>
     <script src="bootstrap-collapse.js" type="text/javascript"></script>
     
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
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'https://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>


<style type="text/css">

.navbar-inner
{
	background-color:#666666;
	background-image:url('graphics/gradient.png');
	background-image:-webkit-linear-gradient(top, #3e3e3e, #232323);
	background-image:-moz-linear-gradient(top, #3e3e3e, #232323);
	background-image:-o-linear-gradient(top,  #3e3e3e, #232323);
	background-image:-ms-linear-gradient(top,  #3e3e3e, #232323);

}

.center.navbar .nav,
.center.navbar .nav > li {
    float:none;
    display:inline-block;
    *display:inline; /* ie7 fix */
    *zoom:1; /* hasLayout ie7 trigger */
    vertical-align: top;
}

.center .navbar-inner {
    text-align:center;
}
.navbar .nav,
.navbar .nav > li {
    float:none;
    display:inline-block;
    *display:inline; /* ie7 fix */
    *zoom:1; /* hasLayout ie7 trigger */
    vertical-align: top;
}
.center .dropdown-menu {
    text-align: left;
}
ul.nav li.dropdown:hover ul.dropdown-menu{
    display: block;    
}

a.menu:after, .dropdown-toggle:after {
  content: none;
}
.search {
box-sizing: initial;
width: 14em;
outline-color: none;
border: 2px solid #6aae45;
-webkit-border-top-left-radius: 5px;
-webkit-border-bottom-left-radius: 5px;
-moz-border-radius-topleft: 5px;
-moz-border-radius-bottomleft: 5px;
border-top-left-radius: 5px;
border-bottom-left-radius: 5px;
font-family: helvetica neue, arial, lucida grande;
font-size: 14px;
background-image: url('noahsimages/glass.png');
background-position: 14.60em 2px;
background-size:1.4em 1.4em;
background-repeat: no-repeat;
}
.notifications
{
	width:1.8em;
	height:1.8em;
	border-radius:.9em;
	background:#efefef;
}
.open .dropdown-menu {
  display: block;
  margin-top:10px;
  }
  #fields
  {
  	border:1px solid white;
  	border-radius:5px;
  	margin:5px;
  	padding-top:5px;

  }
  .formhead
  {
  	margin-left:2em;
  	width:5em;
  	color:white;
  	font: 16px "helvetica neue", helvetica, arial, sans-serif;
  	font-weight:600;
  }
  .dropdown-menu
  {
  	border-color:rgba(25,25,25, .2);
  	border: 3px solid;
  	background-color:rgb(230,230,230);
  	margin-top: 10px;

  }
  ul.nav li.dropdown:hover ul.dropdown-menu{
    display: block;    
}

a.menu:after, .dropdown-toggle:after {
  content: none;
}
.navlist
{
	text-decoration:none;
	font-color:#fff;
	font-family: "helvetica neue", helvetica,"lucida grande", arial, sans-serif;
	font-size:20px;
	margin-top:5px;
}


</style>
</head>


<!--Following Modal-->
<div class="modal hide fade" id="fwmodal" style="overflow:hidden;border:5px solid rgba(102,102,102,.8);">
      
<?php
if($_SESSION['loggedin'] !== 1) {

echo'
<div class="modal-header" style="background-color:#111;color:#fff;">
<a style="float:right" class="btn btn-success" data-dismiss="modal">Close</a>
<img style="margin-top:-2px;" src="graphics/aperture_white.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">Please log in to follow ',$firstname,' ',$lastname,'</span>
  </div>

<div modal-body" style="width:450px;height:145px;">

<div id="content" style="font-size:16px;width:560px;font-family:helvetica,arial;font-weight:100;background-color:rgb(245,245,245);">
		
<img class="roundedall" style="margin-left:20px;margin-top:20px;" src="',$profilepic,'" 
height="100px" width="100px" />

<div style="width:350px;margin-left:140px;margin-top:-85px;line-height:1.48;">
<a style="color:black;" href="viewprofile.php?u=',$user,'">',$firstname,' ',$lastname,'</a><br />                 

',$numberofpics,' photos <br />

Avg. Portfolio: ',$portfolioranking,' <br /><br /><br />

</div>
</div>';

    }
        
        
if($_SESSION['loggedin'] == 1) {
    
		$emailquery=("SELECT * FROM userinfo WHERE emailaddress ='$email'");
		$emailresult=mysql_query($emailquery);
		$prevemails=mysql_result($emailresult, 0, "following");
		$viewerfirst = mysql_result($emailresult, 0, "firstname");
		$viewerlast = mysql_result($emailresult, 0, "lastname");
		if($prevemails == "") {$emailaddressformatted="'". $emailaddress . "'";}
		else {$emailaddressformatted=", '". $emailaddress . "'";}
        
        //MAKE SURE NOT FOLLOWING SELF
        if($email == $emailaddress) {
       echo'
<div class="modal-header" style="background-color:#111;color:#fff;">
<a style="float:right" class="btn btn-success" data-dismiss="modal">Close</a>
<img style="margin-top:-2px;" src="graphics/aperture_white.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">Oops, you accidentally tried to follow yourself</span></div>

<div modal-body" style="width:450px;height:145px;">

<div id="content" style="font-size:16px;width:560px;font-family:helvetica,arial;font-weight:100;background-color:rgb(245,245,245);">
		
<img class="roundedall" style="margin-left:20px;margin-top:20px;" src="',$profilepic,'" 
height="100px" width="100px" />

<div style="width:350px;margin-left:140px;margin-top:-85px;line-height:1.48;">
',$firstname,' ',$lastname,'<br />                 

',$numberofpics,' photos <br />

Avg. Portfolio: ',$portfolioranking,' <br /><br /><br />

</div>
</div>';

        }
        
        
        else {
		//MAKE SURE FOLLOWER ISN'T ADDED TWICE
		$search_string=$prevemails;
		$regex="/$emailaddress/";
		$match=preg_match($regex,$search_string);
		if ($match > 0) {
			echo'
            
<div class="modal-header" style="background-color:#111;color:#fff;">
<a style="float:right" class="btn btn-success" data-dismiss="modal">Close</a>
<img style="margin-top:-2px;" src="graphics/aperture_white.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">You are already following ',$firstname,'</span>
  </div>
  
<div modal-body" style="width:450px;height:145px;">

<div id="content" style="font-size:16px;width:560px;font-family:helvetica,arial;font-weight:100;background-color:rgb(245,245,245);">
		
<img class="roundedall" style="margin-left:20px;margin-top:20px;" src="',$profilepic,'" 
height="100px" width="100px" />

<div style="width:350px;margin-left:140px;margin-top:-85px;line-height:1.48;">
<a style="color:black;" href="viewprofile.php?u=',$user,'">',$firstname,' ',$lastname,'</a><br />                 

',$numberofpics,' photos <br />

Avg. Portfolio: ',$portfolioranking,' <br /><br /><br />

</div>
</div>';
		} 

else {
            
			echo'
<div class="modal-header" style="background-color:#111;color:#fff;">
<a style="float:right" class="btn btn-success"  href="fullsize.php?imageid=', $imageid,'&v=',$view,'&fw=1">Close</a>
<img style="margin-top:-2px;" src="graphics/aperture_white.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">You are now following ',$firstname,' ',$lastname,'</span>
  </div>
  
<div modal-body" style="width:450px;height:145px;">

<div id="content" style="font-size:16px;width:560px;font-family:helvetica,arial;font-weight:100;background-color:rgb(245,245,245);">
		
<img class="roundedall" style="margin-left:20px;margin-top:20px;" src="',$profilepic,'" 
height="100px" width="100px" />

<div style="width:350px;margin-left:140px;margin-top:-85px;line-height:1.48;">
<a style="color:black;" href="viewprofile.php?u=',$user,'">',$firstname,' ',$lastname,'</a><br />                 

',$numberofpics,' photos <br />

Avg. Portfolio: ',$portfolioranking,' <br /><br /><br />

</div>
</div>';
            
  }
    }
} 
        
        
        
?>

</div>
</div>


<!--Favorite Modal-->
<div class="modal hide fade" id="fvmodal" style="overflow:hidden;border:5px solid rgba(102,102,102,.8);">
  
<?php
 
if($_SESSION['loggedin'] !== 1) {

echo'
<div class="modal-header" style="background-color:#111;color:#fff;">
<a style="float:right" class="btn btn-success" data-dismiss="modal">Close</a>
<img style="margin-top:-2px;" src="graphics/aperture_white.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">Please login to favorite this photo</span>
  </div>
 
<div modal-body" style="width:450px;height:145px;">

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
        
        //if tries to favorite own photo
        if($vieweremail == $emailaddress) {
        echo'
<div class="modal-header" style="background-color:#111;color:#fff;">
<a style="float:right" class="btn btn-success" data-dismiss="modal">Close</a>
<img style="margin-top:-2px;" src="graphics/aperture_white.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">Oops, you tried to favorite your own photo.</span>
  </div>

<div modal-body" style="width:450px;height:145px;">

<div id="content" style="font-size:16px;width:560px;font-family:helvetica,arial;font-weight:100;background-color:rgb(245,245,245);height:150px;">
		
<img class="roundedall" style="margin-left:20px;margin-top:20px;" src="',$image,'" 
height="100px" width="100px" />

<div style="width:350px;margin-left:140px;margin-top:-75px;line-height:1.48;">              

',$caption,'<br />

By: 
',$firstname,' ',$lastname,'</a>   

</div>
</div>';
    
    }
        
        else {
        
		//if the image has already been favorited
		if($match) {
			//tell them so
			        echo'
<div class="modal-header" style="background-color:#111;color:#fff;">
<a style="float:right" class="btn btn-success" data-dismiss="modal">Close</a>
<img style="margin-top:-2px;" src="graphics/aperture_white.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">This photo is already in your favorites.</span>
  </div>

<div modal-body" style="width:450px;height:145px;">

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
        
		else {
        
        echo'
<div class="modal-header" style="background-color:#111;color:#fff;">
<a style="float:right" class="btn btn-success" href="fullsize.php?imageid=', $imageid,'&v=',$view,'&f=1">Close</a>
<img style="margin-top:-2px;" src="graphics/aperture_white.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">This photo has been added to your favorites.</span>
  </div>

<div modal-body" style="width:450px;height:145px;">

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
      }  
	} 
  
?>

</div>
</div>

<body style="overflow-x:hidden;min-width:1220px;background-color:rgb(245,245,245);">

<?php navbarnew(); ?>

<!--Here the Grid Container Begins-->
<div class="container_24 container-margin" style="margin-top:70px;">

<div class="grid_15 pull_2">	
	<div class="grid_14 pull_1" style="float:left;">
		<h1 style="font-size:22px;padding-bottom:15px;font-weight:200;"> <?php echo $caption; ?> </h1>
	</div>	
	<div class="grid_21 pull_1" style="float:left;" >
	<img onmousedown="return false" oncontextmenu="return false;" src="<?php echo $image; ?>" alt="<?php echo $caption; ?>" class="image" height="<?php echo $newheight; ?>px" width="<?php echo $newwidth; ?>px" />	
    
                <?php
                    if($faves > 5 || $points > 120 || $views > 100) {
                        echo'<img style="margin-top:-40px;margin-left:',$newwidth-55,'px;" src="graphics/toplens2.png" height="85" />';
                }
                ?>
                
	</div>


<!--COMMENT BOX-->

<div class="grid_16 pull_1 comments-box">
    
    <?php
        
        //ADD COMMENT
        if($_SESSION['loggedin'] == 1) {
        
            echo'
                <form action="" method="POST" />
                    <div style="width:610px;"><img style="float:left;padding:10px;" src="',$sessionpic,'" height="30" width="30" />
                    <input style="float:left;width:495px;position:relative;top:10px;" type="text" name="comment" placeholder="Leave feedback for ',$firstname,'&#8230;" />
                    <input style="float:left;margin-top:11px;margin-left:4px;"  type="submit" class="btn btn-success" value="Post"/>
                    </div>
                </form>';
         
        }
            
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
        
        //SHOW PREVIOUS COMMENTS
        echo'
            <div class="grid_16" style="width:610px;margin-top:20px;">
            <a href="viewprofile.php?u=',$commenterid,'"><div style="float:left;"><img class="roundedall" src="',$commenterpic,'" alt="',$commentername,'" height="40" width="35"/></a></div>
            <div style="float:left;padding-left:6px;width:560px;">
                <div style="float:left;color:#3e608c;font-size:14px;font-family:helvetica;font-weight:500;border-bottom: 1px solid #ccc;width:560px;"><div style="float:left;"><a name="',$commentid,'" href="viewprofile.php?u=',$commenterid,'">',$commentername,'</a> &nbsp;<span style="font-size:16px;font-weight:100;color:black;margin-top:2">|</span>&nbsp;<span style="color:#333;font-size:12px;">Rep: ',$commenterrep,'</span>
                </div>&nbsp;&nbsp;&nbsp;
                    <div class="progress progress-success" style="float:left;width:110px;height:7px;opacity:.8;margin:7px;">
                    <div class="bar" style="width:',$commenterrep,'%;">
                    </div>
                    
                    </div>';
                    
                 if($email == $emailaddress) {
                    echo'
                        <div style="float:right;font-size:12px;font-weight:500;"><a style="color#ccc;text-decoration:none;" href="fullsize.php?image=',$image,'&action=deletecomment&cid=',$commentid,'">X</a></div>';
                }
                
                if($commenterid == $sessionid) {
                    echo'
                        <div style="float:right;padding-right:10px;font-size:12px;font-weight:500;"><a style="color#ccc;text-decoration:none;" href="fullsize.php?image=',$image,'&action=editcomment&cid=',$commentid,'#',$commentid,'"> Edit Comment</a></div>';
                }

                echo'
                </div>
                
                <br />
                <div style="float:left;font-size:11px;color:#777;font-weight:400;padding:2px;">',converttime($commenttime),'</div>
                
                <div style="float:left;width:520px;padding:10px;font-size:13px;font-family:helvetica;font-weight:300;color:#555;">',$comment,'</div>
            </div>';
            
             if($_GET['action'] == 'editcomment' && $commentid == $_GET['cid']) {
                
                    echo'
                    <form action="fullsize.php?image=',$image,'#',$commentid,'" method="POST" />
                    <textarea style="height:55px;width:560px;margin-left:40px;" name="commentedit">',$comment,'</textarea>
                    <input type="hidden" name="commentid" value="',$commentid,'" />
                    <br />
                    <input type="submit" class="btn btn-primary" style="float:right;font-size:12px;" value="Save Edit" />
                    </form>';
                    
                }
            
            echo'
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


<!--PHOTOGRAPHER ID-->

		<div class="grid_7 push_4" style="margin-top:10px;">
			<div class="grid_7 box"> <!--ID Tag-->
				<div style="height:130px;">
					<div class="roundedall" style="float:left;overflow:hidden;margin-left:5px;margin-top:5px;">
					<img src="<?php echo $profilepic; ?>" alt="<?php echo $fullname; ?>" height="95" width="95" />
				</div>

			<div id="namewrap">
				<h1 id="name"><a class="click" href="viewprofile.php?u=<?php echo $user; ?>"><?php echo $fullname; ?></a></h1>
				<a data-toggle="modal" data-backdrop="static" href="#fwmodal"><button style="width:80px;margin-left:15px;" class="btn btn-primary"> Follow </button></a>
				<div class="progress progress-success" style="width:110px;height: 10px;margin-top:10px;">
                <div class="bar" style="width:<?php echo $reputation; ?>%;"> 
                </div></div>

				<h1 id="rep"> Rep: &nbsp <?php echo $reputation; ?> </h1>
			</div>	
            
            <?php
                
                if($reputation > 60) {
                    echo'<img style="margin-top:-90px;margin-left:70px;" src="graphics/toplens.png" height="75" />';
                }
            
            ?>
                
		</div>
	



			</div>
			<div class="grid_7 box underbox" style="text-align:center;"><!--Rank and stats-->
				<div class="grid_7">
				<div class="fixed" style="text">
				<div style="float:left;">

                <?php
            
                //get the ranking variable and update the database
                $ranking=mysql_real_escape_string($_POST['ranking']);
                if($_POST['ranking']) { //if ranking was posted
                $voteremail=$_SESSION['email'];
        
                if($voteremail) {
                $rankcheck = mysql_query("SELECT voters FROM photos WHERE source='$image'") or die(mysql_error());
                $votecheck = mysql_result($rankcheck, 0, "voters");
                $search_string2 = $votecheck;
                $regex=$voteremail;
                $votematch=strpos($search_string2, $regex);
         
                //check if own photo
                if($voteremail == $emailaddress) {
                    $voteself == 1;
                }
        
                //if the image hasn't already been voted on
                if(!$votematch && ($voteremail != $emailaddress)) {
        
                $ranking=mysql_real_escape_string($_POST['ranking']); //make ranking equal to the posted ranking as an integer data type
                
                if ($ranking >= 1 & $ranking <= 10) {  //if ranking makes sense
		
        
                if($reputationme > 70)
                {
                    $prevpoints+=($ranking*2.5);
                    $prevvotes+=2.5;
                    $rankquery="UPDATE photos SET points='$prevpoints', votes='$prevvotes' WHERE source='$image'";
                    mysql_query($rankquery); 
                }
        
                elseif($reputationme > 50 && $reputationme < 70)
                {
                    $prevpoints+=($ranking*2.0);
                    $prevvotes+=2;
                    $rankquery="UPDATE photos SET points='$prevpoints', votes='$prevvotes' WHERE source='$image'";
                    mysql_query($rankquery); 
                }
        
                elseif($reputationme > 30 && $reputationme < 50)
                {
                    $prevpoints+=($ranking*1.5);
                    $prevvotes+=1.5;
                    $rankquery="UPDATE photos SET points='$prevpoints', votes='$prevvotes' WHERE source='$image'";
                    mysql_query($rankquery); 
                }
        
                elseif(reputationme < 30)
                {
                    $prevpoints+=$ranking;
                    $prevvotes+=1;
                    $rankquery="UPDATE photos SET points='$prevpoints', votes='$prevvotes' WHERE source='$image'";
                    mysql_query($rankquery); 
                }
        
            }  //end querying points and votes count
    
            //Add voter's name to database    
            $voter = "'" . $voteremail . "'";
            $voter = ", " . $voter;
            $voter = addslashes($voter);
            $votersquery = mysql_query("UPDATE photos SET voters=CONCAT(voters,'$voter') WHERE source='$image'");
    
            echo '<div style="position: relative; top: 7px; margin-left:-5px; font-size: 14px; font-weight:100; font-family:helvetica;">Thanks for voting!</div>';

            } 
    
            elseif(votematch && ($voteremail != $emailaddress)){
                echo '<div style="position: relative; top: 7px; margin-left:-5px; font-size: 14px; font-weight:100; font-family:helvetica;">You already voted!</div>';

            }
    
            elseif($voteremail == $emailaddress) {
                echo '<div style="position: relative; top: 7px; margin-left:-5px; font-size: 14px; font-weight:100; font-family:helvetica;">Oops, your photo!</div>';

            }
            }
    
        else{
                echo '<div style="position: relative; top: 7px; margin-left:-5px; font-size: 14px; font-weight:100; font-family:helvetica;">Please login to vote</div>';

            }
        }

    ?>

            <script>
                function submitMyForm(sel) {
                sel.form.submit();
                }
            </script>
 
            <?php
 
            if(!$ranking) {
                echo '<div style="position: relative; left: -10px; top: 0px; text-align: center; font-size: 15px; font-family: arial;';
                if($ranking) {echo 'margin-top: 10px;';}
                echo '">
                <form id="Form1" action="', htmlentities($_SERVER['PHP_SELF']), '?imageid=',$imageid, '&v=', $view, '" method="post">
                <select name="ranking" style="width:90px; height:30px;margin-left:15px;margin-top:2px;" onchange="submitMyForm(this)">
                <option value="" style="display:none;">&#8212;</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
                </select>
                </form></div>';
            }

        ?>

            </div>​

				<a class="btn btn-danger" data-toggle="modal" data-backdrop="static" href="#fvmodal" style="padding: .45em 2em .45em 2em;margin-left:-5px;margin-right:5px;"><img src="graphics/heart.png" style="width:20px;height:20px;float:right;"/></a>	
               
                 <?php
                
                if($price != 'NFS') {
                echo'
				<a class="btn btn-primary" style="padding: .45em 1em .45em 1em;" href="fullsizemarket.php?imageid=',$imageID,'"><img src="graphics/cart_white.png" style="width:20px;height:20px;float:right;"/></a>';
                }
                
                ?>
                
			</div>
		</div>
	</div>	
			<div class="grid_8" id="statsbox">
			<div class="grid_4 box underbox">	
				<ul id="stats">
                
                <?php
                    
                    if($prevvotes >=1.0) {
                        $ranking = number_format(($prevpoints/$prevvotes),1);	
                    echo'
					<li> <img src="graphics/rank_icon.png"/> <span id="rank"> Rank: </span> <span class="numbers">',$ranking,'</span><span id="littlenumbers"> /10 </span></li>';
                    }
                    
                    else {
                    echo'
					<li> <img src="graphics/rank_icon.png"/> <span id="rank"> Rank: </span> <span class="numbers">0.0</span><span id="littlenumbers"> /10 </span></li>';
                    }
                    
                ?>
                    
					<br />
					<li> <img src="graphics/heart_dark.png"/> <span id="stat"> Faves: </span> <span class="numbers"><?php echo $faves; ?></span> </li>
					<br />
					<li> <img src="graphics/eye.png"/> <span id="stat"> Views: </span> <span class="numbers"><?php echo $views; ?></span></li>
				</ul>
				</div>
				<div class="grid_2 box underbox float-right" style="width:90px;height:40px;">
					<h1 id="share">Sold:</h1>
						<p id="sharenumber"> <?php echo $sold; ?> </p>
			</div>
			<div class="grid_2 box underbox float-right" style="width:90px;height:40px;"> <!--ML = margin-left -->
					<h1 id="share">Price</h1>
						<p id="sharenumber"> <?php echo $price; ?> </p>
			</div>	
		</div>
        
        
			<div class="grid_7 box underbox"><!--Next photos-->
            
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
				
				<div id="images" style="margin-top:5px;">
					<a href="fullsize.php?image=<?php echo $imageOne; ?>&v=<?php echo $view; ?>"><img src="<?php echo $imageOneThumb; ?>" id="nextimg1"/></a>
				</div>
				<div class="nextimg">
					<a href="fullsize.php?image=<?php echo $imageTwo; ?>&v=<?php echo $view; ?>"><img src="<?php echo $imageTwoThumb; ?>" id="nextimg2"/></a>
				</div>
				<div class="nextimg">	
					<a href="fullsize.php?image=<?php echo $imageThree; ?>&v=<?php echo $view; ?>"><img src="<?php echo $imageThreeThumb; ?>"id="nextimg3"/></a>
				</div>
				<a style="text-decoration:none;" href="fullsize.php?image=<?php echo $imageBefore; ?>&v=<?php echo $view; ?>"><div class="grid_1" id="hover_arrow_left">
				</div></a>
					<a style="text-decoration:none;" href="fullsize.php?image=<?php echo $imageOne; ?>&v=<?php echo $view; ?>"><div class="grid_1" id="hover_arrow_right">
				</div></a>
				</div>
             
    <?php
             
                //CHECK FOR OPT-IN
                
             if($promos == 'optin') {
                
			echo'
			<div class="grid_7 box underbox"><!--Share stuff here-->
					<h1 id="sharelinks"> Share: </h1>
                    
                    <a href="https://www.facebook.com/sharer.php?u=http%3A%2F%2Fphotorankr.com%2Ffullsize.php?imageid=<?php echo $imageid; ?>" type="button" share_url="photorankr.com/fullsize.php?imageid=<?php echo $imageid; ?>"><img src="graphics/facebook.png" style="width:30px;height:30px;margin: 7px 9px 0px 10px;"/></a>
                    <script src="https://static.ak.fbcdn.net/connect.php/js/FB.Share" 
                    type="text/javascript">
                    </script>

					<a href="https://twitter.com/share" data-text="Check out this photo!" data-via="PhotoRankr" data-size="large" data-count="none"><img src="graphics/twitter.png" style="width:30px;height:30px;margin: 7px 9px 0px 5px;"/></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

					<a href="https://pinterest.com/pin/create/button/" class="pin-it-button" count-layout="none"><img src="graphics/pinterest.png" style="width:30px;height:30px;margin: 7px 9px 0px 5px;"/></a>
<script type="text/javascript" src="https://assets.pinterest.com/js/pinit.js"></script>
                    
<a href="https://plus.google.com/102253183291914861528"><img src="graphics/g+.png" style="width:30px;height:30px;margin:7px 9px 0px 8px;"/></a>
                    
			</div>';
            
            }
            
    ?>
		
			<div class="grid_7 box underbox"><!--About photo-->
				<h1> About </h1> 
                    
					<div class="grid_7">
                    
                    <?php
                    
                    if($exhibit) {
                        echo'
						<div style="clear:both;"><h1 class="about">Exhibit: </h1> <p class="aboutinfo"><a class="click" href="viewprofile.php?u=',$user,'&view=exhibits&set=',$exhibit,'"><u>',$exhibitname,'</u></a></p></div>'; 
                    }
                    
                    if($exhibit && $expic1 && $expic2 && $expic3) {
                        echo'
						<div style="clear:both;margin-left:5px;">
                        <a href="fullsize.php?image=',$expic1,'&view=',$view,'"><img style="float:left;padding:2px;" src="',$exthumb1,'" height="80" width="80" /></a> 
                        <a href="fullsize.php?image=',$expic2,'&view=',$view,'"><img style="float:left;padding:2px;" src="',$exthumb2,'" height="80" width="80" /></a> 
                        <a href="fullsize.php?image=',$expic3,'&view=',$view,'"><img style="float:left;padding:2px;" src="',$exthumb3,'" height="80" width="80" /></a>                         
                        </div>';
                    }
                    
                    if($location) {
                        echo'
						<div style="clear:both;"><h1 class="about"> Location: </h1> <p class="aboutinfo">',$location,'</p></div>'; 
                    }
                    
                    if($camera) {
                        echo'
						<div style="clear:both;"><h1 class="about"> Camera: </h1> <p class="aboutinfo">',$camera,'</p></div>'; 
                    }
                    
                    if($lens) {
                        echo'
						<div style="clear:both;"><h1 class="about"> Lens: </h1> <p class="aboutinfo">',$lens,'</p></div>'; 
                    }
                    
                    if($focallength) {
                        echo'
						<div style="clear:both;"><h1 class="about"> Focal Length: </h1> <p class="aboutinfo">',$focallength,'</p></div>'; 
                    }
                    
                    if($aperture) {
                        echo'
						<div style="clear:both;"><h1 class="about"> Aperture: </h1> <p class="aboutinfo">',$aperture,'</p></div>'; 
                    }
                    
                    if($lens) {
                        echo'
						<div style="clear:both;"><h1 class="about"> Lens: </h1> <p class="aboutinfo">',$lens,'</p></div>'; 
                    }
                    
                    if($about) {
                        echo'
						<div style="clear:both;"><h1 class="about"> Behind the Camera </h1> <p class="aboutinfo" style="line-height:20px;margin-left:10px;text-align:justified;">',$about,'</p>
				</div>';	
                    }
                    echo'</div>';
                    
                    if($time > 0) {
                    
                        echo'<div style="clear:both;"><h1 class="about"> &nbsp;Uploaded: </h1> <p class="aboutinfo" style="line-height:20px;margin-left:10px;text-align:justified;">',converttodate($time),'</p></div>';
                    
                    }
                    
                    if($keywords) {
                    echo'
                    <div class="grid_7">
					<h1 class="about"> Keywords: </h1> <p class="aboutinfo">',$keywords,'
                    </p> 
                    </div>';
                    }
                    
                    ?>
                    
		</div>	
	</div>	
</div>	
<br />
<br />

<?php footer(); ?>

<?php 
//add to the views column
$updatequery = mysql_query("UPDATE photos SET views=views+1 WHERE source='$image'") or die(mysql_error());
?>

 </body>
 </html>  
