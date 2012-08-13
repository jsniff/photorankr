<?php

//connect to the database
require "db_connection.php";
require "functionsnav.php";

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
    
    $findreputationme = mysql_query("SELECT reputation FROM userinfo WHERE emailaddress = '$email'");
    $reputationme = mysql_result($findreputationme,0,'reputation');
    
    //GET THE IMAGE
$image=addslashes($_GET['image']);
//if the url does not contain an image send them back to trending
if(!isset($_GET['image'])) {
	header("Location: trending.php");
	exit();
}

//add to the views column
$updatequery = mysql_query("UPDATE photos SET views=views+1 WHERE source='$image'") or die(mysql_error());


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
$prevpoints=$row['points'];
$prevvotes=$row['votes'];
$ranking=number_format(($prevpoints/$prevvotes),1);
$imageID=$row['id'];
$price=mysql_result($result, 0, "price");
$camera = $row['camera'];
$faves= $row['faves'];
$views = $row['views'];
$exhibit = $row['set_id'];
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
if($tag1) {$tag1 = $tag1 . ", ";}
$tag2 = $row['tag2'];
if($tag2) {$tag2 = $tag2 . ", ";}
$tag3 = $row['tag3'];
if($tag3) {$tag3 = $tag3 . ", ";}
$tag4 = $row['tag4'];
if($tag4) {$tag4 = $tag4 . ", ";}
$singlestyletags = $row['singlestyletags'];
$singlecategorytags = $row['singlecategorytags'];
$singlestyletagsarray = explode("  ", $singlestyletags);
$singlecategorytagsarray   = explode("  ", $singlecategorytags);
for($iii=0; $iii < count($singlestyletagsarray); $iii++) {
if($singlestyletagsarray[$iii] != '') {
    $singlestyletagsfinal = $singlestyletagsfinal . $singlestyletagsarray[$iii] . ", "; }
    }
    for($iii=0; $iii < count($singlecategorytagsarray); $iii++) {
        if($singlecategorytagsarray[$iii] != '') {
        $singlecategorytagsfinal = $singlecategorytagsfinal . $singlecategorytagsarray[$iii] . ", "; }
    }
    
$keywords = $tag1 . $tag2 . $tag3 . $tag4 . $singlestyletagsfinal . $singlecategorytagsfinal;
$keywords = substr_replace($keywords ," ",-2);
    

//find how many photos the photographer has
$numberofpics = mysql_query("SELECT * FROM photos WHERE emailaddress='$emailaddress'");
$numberofpics = mysql_num_rows($numberofpics);

$locationandcountry = $location . $country;

$price = '$' . $price;
if ($price == "0.00") {$price='Free';}  
if ($price == "Not For Sale") {$price='NFS';}  

//FIND THE PHOTOGRAPHER NAME IN DATABASE
$namequery="SELECT * FROM userinfo WHERE emailaddress='$emailaddress'";
$nameresult=mysql_query($namequery);
$row=mysql_fetch_array($nameresult);
$user=$row['user_id'];
$firstname=$row['firstname'];
$lastname=$row['lastname'];
$reputation=number_format($row['reputation'],2);
$fullname = $firstname . " " . $lastname;
$fullname = (strlen($fullname ) > 14) ? substr($fullname,0,12). " &#8230;" : $fullname;

$profilepic=$row['profilepic'];
$profilescore=$row['totalscore'];

//calculate the size of the picture
$maxwidth=770;
$maxheight=770;

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

$email6 = $_SESSION['email'];

//QUERY FOR NOTIFICATIONS
$currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$email6'";
$currentnotsquery = mysql_query($currentnots);
$currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");

//DE-HIGHLIGHT NOTIFICATIONS IF CLICKED ON

if(isset($_GET['id'])){
$id = htmlentities($_GET['id']);
$idformatted = $id . " ";
$unhighlightquery = "UPDATE userinfo SET unhighlight = CONCAT(unhighlight,'$idformatted') WHERE emailaddress = '$email6'";
$unhighlightqueryrun = mysql_query($unhighlightquery);

//notifications query reset 
if($currentnotsresult > 0) {
$notsquery = "UPDATE userinfo SET notifications = 0 WHERE emailaddress = '$email6'";
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
$newsfeedtrending="INSERT INTO newsfeed (firstname,lastname,caption,owner,type,source) VALUES ('$firstname2','$lastname2','$caption2','$emailaddress3','$type4','$source')";
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
        $email = $_SESSION['email'];
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
        $newsfeedfavequery=mysql_query("INSERT INTO newsfeed (firstname,lastname,emailaddress,type,source,caption,owner) VALUES ('$viewerfirst', '$viewerlast', '$email','$type','$image','$caption','$emailaddress')");
     
//notifications query     
$notsquery = "UPDATE userinfo SET notifications = (notifications + 1) WHERE emailaddress = '$emailaddress'";
$notsqueryrun = mysql_query($notsquery);       
 
            
//GRAB SETTINGS LIST
$settingemail = $_SESSION['email'];
$settingquery = "SELECT * FROM userinfo WHERE emailaddress = '$settingemail'";
$settingqueryrun = mysql_query($settingquery);
$settinglist = mysql_result($settingqueryrun, 0, "settings");
                                  
$setting_string = $settinglist;
$find = "emailfave";
$foundsetting = strpos($setting_string,$find);
            
            //MAIL PHOTOGRAPHER NOTICE THAT THEIR PHOTO HAS BEEN FAVORITED
            $to = '"' . $firstname . ' ' . $lastname . '"' . '<'.$emailaddress.'>';
          $subject = $viewerfirst . " " . $viewerlast . " favorited one of your photos on PhotoRankr";
          $favemessage = $viewerfirst . " " . $viewerlast . " favorited one of your photos on PhotoRankr
        
To view the photo, click here: http://photorankr.com/fullsize.php?image=".$imagelink2;
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
  
?>   

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>"<?php echo $caption; ?>" On PhotoRankr</title>
<link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
<link rel="stylesheet" type="text/css" href="css/bootstrapNew.css"/>
<link rel="stylesheet" type="text/css" href="css/reset.css"/>
<link rel="stylesheet" type="text/css" href="css/all.css"/>
<link rel="stylesheet" type="text/css" href="css/reset.css"/>
<link rel="stylesheet" type="text/css" href="css/style.css"/>
<link rel="stylesheet" type="text/css" href="css/960_24_col.css"/>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
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
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
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
<div class="modal hide fade" id="fwmodal" style="overflow:hidden;">
      
<?php
if($_SESSION['loggedin'] !== 1) {

echo'
<div class="modal-header">
<a style="float:right" class="btn btn-primary" data-dismiss="modal" >Close</a>
<img style="margin-top:-4px;" src="graphics/logoteal.png" height="28" width="90" />&nbsp;&nbsp;<span style="font-size:16px;">Please log in to follow ',$firstname,' ',$lastname,'</span>
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:500px;">
		
<img style="border: 1px solid black;margin-left:10px;margin-top:30px;" src="',$profilepic,'" 
height="100px" width="100px" />

<div style="width:500px;margin-left:130px;margin-top:-90px;">
',$firstname,' ',$lastname,'<br />                 

',$numberofpics,' photos <br />

Portfolio Average: ',$portfolioranking,' <br /><br /><br />

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
<div class="modal-header">
<a style="float:right" class="btn btn-primary" data-dismiss="modal">Close</a>
<img style="margin-top:-4px;" src="graphics/logoteal.png" height="28" width="90" />&nbsp;&nbsp;<span style="font-size:16px;">Oops, you accidentally tried to follow yourself.</span>
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:500px;">
		
<img style="border: 1px solid black;margin-left:10px;margin-top:30px;" src="',$profilepic,'" 
height="100px" width="100px" />

<div style="width:500px;margin-left:130px;margin-top:-90px;">
',$firstname,' ',$lastname,'<br />                 

',$numberofpics,' photos <br />

Portfolio Average: ',$portfolioranking,' <br /><br /><br />

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
<div class="modal-header">
<a style="float:right" class="btn btn-primary" data-dismiss="modal">Close</a>
<img style="margin-top:-4px;" src="graphics/blacklogo.png" height="28" width="130" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;">You are already following ',$firstname,'.</span>
  </div>
  <div modal-body" style="width:450px;height:145px;">

<div id="content" style="font-size:16px;width:350px;">
		
<img style="border: 1px solid black;margin-left:10px;margin-top:20px;" src="',$profilepic,'" 
height="100px" width="100px" />

<div style="width:350px;margin-left:130px;margin-top:-90px;">
',$firstname,' ',$lastname,'<br />                 

',$numberofpics,' photos <br />

Avg. Portfolio: ',$portfolioranking,' <br /><br /><br />

</div>
</div>';
		} 

else {
            
			echo'
<div class="modal-header">
<a style="float:right" class="btn btn-primary" href="fullsize.php?image=', $image,'&v=',$view,'&fw=1">Close</a>
<img style="margin-top:-4px;" src="graphics/logoteal.png" height="28" width="90" />&nbsp;&nbsp;<span style="font-size:16px;">You are now following ',$firstname,' ',$lastname,'</span>
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:500px;">
		
<img class="roundedall" style="margin-top:30px;" src="',$profilepic,'" 
height="100px" width="100px" />

<div style="width:500px;margin-left:130px;margin-top:-90px;">
',$firstname,' ',$lastname,'<br />                 

# Photos:',$numberofpics,'<br />

Portfolio Average: ',$portfolioranking,' <br /><br /><br />

</div>
</div>';
            
  }
    }
} 
        
        
        
?>

</div>
</div>


<!--Favorite Modal-->
<div class="modal hide fade" id="fvmodal" style="overflow:hidden;">
  
<?php
 
if($_SESSION['loggedin'] !== 1) {

echo'
<div class="modal-header">
<a style="float:right" class="btn btn-primary" data-dismiss="modal">Close</a>
<img style="margin-top:-4px;" src="graphics/logoteal.png" height="28" width="90" />&nbsp;&nbsp;<span style="font-size:16px;">Please login to favorite this photo</span>
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:500px;">
		
<img style="border: 1px solid black;margin-left:10px;margin-top:30px;" src="',$image,'" 
height="100px" width="100px" />

<div style="width:500px;margin-left:130px;margin-top:-80px;">
"',$caption,'"<br />                 

By: ',$firstname,' ',$lastname,'<br /><br /><br />

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
<div class="modal-header">
<a style="float:right" class="btn btn-primary" data-dismiss="modal">Close</a>
<img style="margin-top:-4px;" src="graphics/logoteal.png" height="28" width="90" />&nbsp;&nbsp;<span style="font-size:16px;">Oops, you tried to favorite your own photo</span>
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:500px;">
		
<img style="border: 1px solid black;margin-left:10px;margin-top:30px;" src="',$image,'" 
height="100px" width="100px" />

<div style="width:500px;margin-left:130px;margin-top:-80px;">
"',$caption,'"<br />                 

By: ',$firstname,' ',$lastname,'<br /><br /><br />

</div>
</div>';
        }
        
        else {
        
		//if the image has already been favorited
		if($match) {
			//tell them so
			        echo'
<div class="modal-header">
<a style="float:right" class="btn btn-primary" data-dismiss="modal">Close</a>
<img style="margin-top:-4px;" src="graphics/logoteal.png" height="28" width="90" />&nbsp;&nbsp;<span style="font-size:16px;">This photo is already in your favorites</span>
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:500px;">
		
<img style="border: 1px solid black;margin-left:10px;margin-top:30px;" src="',$image,'" 
height="100px" width="100px" />

<div style="width:500px;margin-left:130px;margin-top:-80px;">
"',$caption,'"<br />                 

By: ',$firstname,' ',$lastname,'<br /><br /><br />

</div>
</div>';

		}
        
		else {
        
        echo'
<div class="modal-header">
<a style="float:right" class="btn btn-primary" href="fullsize.php?image=', $image,'&v=',$view,'&f=1">Close</a>
<img style="margin-top:-4px;" src="graphics/logoteal.png" height="28" width="90" />&nbsp;&nbsp;<span style="font-size:16px;">This photo has been added to your favorites</span>
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:500px;">
		
<img style="border: 1px solid black;margin-left:10px;margin-top:30px;" src="',$image,'" 
height="100px" width="100px" />

<div style="width:500px;margin-left:130px;margin-top:-80px;">
"',$caption,'"<br />                 

By: ',$firstname,' ',$lastname,'<br /><br /><br />

</div>
</div>';

        
		} 
      }  
	} 
  
?>

</div>
</div>


<body style="overflow-x:hidden;min-width:1220px;">

<?php navbarnew(); ?>

<!--Here the Grid Container Begins-->
<div class="container_24 container-margin" style="margin-top:70px;">
<div class="grid_15">	
	<div class="grid_14 pull_1" style="color:red;float:left;"style="float:left;">
		<h1 class="title" style="font-size:22px;padding-bottom:15px;font-weight:200;"> <?php echo $caption; ?> </h1>
	</div>	
	<div class="grid_17 pull_1" style="float:left;" >
	<img onmousedown="return false" oncontextmenu="return false;" src="<?php echo $image; ?>" class="image" height="<?php echo $newheight; ?>px" width="<?php echo $newwidth; ?>px" />	
	</div>


	<div class="grid_16 pull_1 comments-box">
		<div class="grid_16 comment">
        
        <!--COMMENTS BOX-->    
            
<?php

$image=mysql_real_escape_string($_GET['image']);
$imagenew=str_replace("userphotos/","", $image);
$imagelink=str_replace(" ","", $image);
$searchchars=array('.jpg','.png','.tiff','.JPG','.jpeg','.JPEG','.gif');
$imagenew=str_replace($searchchars,"", $imagenew);
$txt=".txt";
$file = "comments/" . $imagenew . $txt;

$action = $_GET['action'];
if($action == "comment" && $_SESSION['loggedin']==1) {
    $message  = $_POST ['message'];
    $message = $message . "\n";
    $fp = fopen("$file", 'a');
    
    //SEND EMAILS TO PEOPLE WHO HAVE PREVIOUSLY COMMENTED ON PHOTO
    //GET USEREMAIL (PERSON COMMENTING) FIRSTNAME, LASTNAME
    $sql = "SELECT * FROM userinfo WHERE emailaddress = '$email'";
	$userresult = mysql_query($sql) or die(mysql_error());  
    $poster_id = mysql_result($userresult, 0, "user_id");
	$userfirst = mysql_result($userresult, 0, "firstname");
	$userlast = mysql_result($userresult, 0, "lastname");
    
    
    
    $lines = file($file);
    $numberoflines = count($lines); 
    
    for ($i=1; $i <= $numberoflines; $i++) {
    $data = $lines[$i];
    preg_match('/<a .*?>(.*?)<\/a>/',$data, $match);
    $match = $match[1];
    $newmatch = explode(' ',$match);

    $firstnamematch = $newmatch[0];    
    $lastnamematch = $newmatch[1];

    $emailyo = "SELECT emailaddress FROM userinfo WHERE firstname = '$firstnamematch' AND lastname = '$lastnamematch'";
    $yoquery = mysql_query($emailyo);
    $yoarray = mysql_fetch_array($yoquery);
    $yoemail = $yoarray['emailaddress'];
    
    //YOEMAIL IS CURRENT INDEX EMAIL, EMAILADDRESS IS OWNER'S EMAIL (OF THE PHOTO)
    
    $found = strpos($prevemails,$yoemail);
    if ($yoemail != $emailaddress && $yoemail != $email && !$found)
    {
    
//GRAB SETTINGS LIST
$settingemail = $_SESSION['email'];
$settingquery = "SELECT * FROM userinfo WHERE emailaddress = '$settingemail'";
$settingqueryrun = mysql_query($settingquery);
$settinglist = mysql_result($settingqueryrun, 0, "settings");
        
$setting_string = $settinglist;
$find = "emailreturncomment";
$foundsetting = strpos($setting_string,$find);

         $to = '"' . $firstname . ' ' . $lastname . '"' . '<'.$yoemail.'>';
          $subject = $userfirst . " " . $userlast . " also commented on " . $firstname . " " . $lastname ."'s photo on PhotoRankr";
          $yomessage = stripslashes($message) . "
        
To view the photo, click here: http://photorankr.com/fullsize.php?image=".$imagelink;
          $headers = 'From:PhotoRankr <photorankr@photorankr.com>';
                    if($foundsetting > 0) {
          mail($to, $subject, $yomessage, $headers);
          } 
          $prevemails = $prevemails . $yoemail; 
          
          
    }
}
    
if (!$fp) {
	//The file could not be opened
    echo "We're terribly sorry, there was an error. Please try again!";
    exit;
} 

if (!$message) {
	//The file could not be opened
    echo "We're terribly sorry, there was an error. Please try again!";
    exit;
} 


else {
    	//The file was successfully opened, lets write the comment to it.
    	//their full name is their first name SPACE last name
	//set their first name to their first name and last to last
	$sql = "SELECT * FROM userinfo WHERE emailaddress = '$email'";
	$userresult = mysql_query($sql) or die(mysql_error());  
	$userfirst = mysql_result($userresult, 0, "firstname");
	$userlast = mysql_result($userresult, 0, "lastname");
    	$profilepic=  mysql_result($userresult, 0, "profilepic");
	$name = $userfirst . " " . $userlast;

    if (!$name) {
	//The file could not be opened
    echo "We're terribly sorry, there was an error. Please try again!";
    exit;
    } 
    
    if (!$profilepic) {
	//The file could not be opened
    echo "We're terribly sorry, there was an error. Please try again!";
    exit;
    } 

    $outputstring = "<br />" . '<img src="' . $profilepic . '" width="40" height="40" alt="PhotoRankr is the only place that puts the photographer first" />' . " " . '<a style="text-decoration:none; color: blue;" href="http://photorankr.com/viewprofile.php?u=' . $poster_id . '">' .stripslashes($name). "</a>" . '<br /><br /><div class="progress" style="width:115px;height:8px;">
                    <div class="bar"
                    style="width:' . $reputationme
                     . '%;"></div>
                    </div>' .stripslashes($message). "<hr />";
                  
                  
                  
                                        
    //Write to the file
    @chmod($file,0777);
    fwrite($fp, $outputstring, strlen($outputstring));
    @include("$file");
        $type3 = "comment";
        $newsfeedcommentquery="INSERT INTO newsfeed (firstname, lastname, emailaddress,owner,type,source) VALUES ('$userfirst', '$userlast', '$email','$emailaddress','$type3','$image')";
        $commentnewsquery = mysql_query($newsfeedcommentquery);
        
        if($email != $emailaddress) {
        //notifications query     
$notsquery = "UPDATE userinfo SET notifications = (notifications + 1) WHERE emailaddress = '$emailaddress'";
$notsqueryrun = mysql_query($notsquery);  
   }
                
    //MAIL EMAIL TO PHOTOGRAPHER WHOSE PHOTO IS BEING COMMENTED UPON  
    if ($emailaddress != $email) {
    

//GRAB SETTINGS LIST
$settingemail = $_SESSION['email'];
$settingquery = "SELECT * FROM userinfo WHERE emailaddress = '$settingemail'";
$settingqueryrun = mysql_query($settingquery);
$settinglist = mysql_result($settingqueryrun, 0, "settings");
        
$setting_string = $settinglist;
$find = "emailcomment";
$foundsetting = strpos($setting_string,$find);

    
        $to = '"' . $firstname . ' ' . $lastname . '"' . '<'.$emailaddress.'>';
        $subject = $userfirst . " " . $userlast . " commented on your photo on PhotoRankr";
        $message = stripslashes($message) . "To view the photo, click here: http://photorankr.com/fullsize.php?image=".$imagelink;
        $headers = 'From:PhotoRankr <photorankr@photorankr.com>';
                  if($foundsetting > 0) {
        mail($to, $subject, $message, $headers);  
        
        }
	} 
        
    //We are finished writing, close the file for security / memory management purposes
    fclose($fp);

	echo '<div style="font-size:15px;padding-left:4px;">
	Comment:</div>';

	if(isset($_GET['v'])){
		$view = $_GET['v'];
	}
	else {
		$view = 't';
	}

    echo '<form action="http://photorankr.com/fullsize.php?image=', $image, '&v=', $view, '&action=comment" method="post">';

      
    echo '<table>

        <tr>
        <td><textarea  style="margin-left:8px; margin-top:8px; width:560px; height: 100px;" cols="60" rows="2" name="message" outline:none; border-radius:3px"></textarea></td>
        </tr>
    </table>
    <input type="hidden" name="act" value="post"></input>
    <button type="submit" class="btn btn-success" name="submit" value="Submit">Submit</button>
</form>';
    echo '</div>';

}

echo '<META HTTP-EQUIV="Refresh" Content="0; URL=fullsize.php?image=', $image, '&v=', $view, '">';
	exit();
}
else {
    //We are not trying to post a comment, show the form.
//if the file does not exist, create it 
if(@fopen("$file", 'a')==FALSE) {
}
else {
@fclose("$file");}
@chmod($file,0777);
?>
<div style="font-size:14px;font-weight:200;padding:10px;">
<?php
@include("$file");
?>
</div>
<?php
if (@file_get_contents($file) == '' && $_SESSION['loggedin'] == 1) {
echo '<div style="padding-left: 8px; padding-top: 8px;font-size:16px;">Be the first to leave a comment!</div>';
}
?>

<br><br>
<?php

//if they are logged in allow them to comment
if($_SESSION['loggedin'] == 1) 
{
echo '
<div style="padding-left: 4px;font-size:16px;">Comment:</div>
<form action="http://photorankr.com/fullsize.php?image=', $image, '&v=', $view, '&action=comment" method="post">
    <table>
        <tr>
            <!--<td>Name:</td>
            <td><input type="text" name="name" style="border-radius:15px; outline:none;" value=""></input></td>-->
        </tr>
        <tr>
            <td><textarea style="margin-left:8px; margin-top:8px; width:560px; height: 100px;" cols="60" rows="2" name="message" style="margin-left: 6px; outline:none; border-radius:3px""></textarea></td>
        </tr>
    </table>
    <input type="hidden" name="act" value="post" />
    <button type="submit" name="submit" class="btn btn-success" value="Submit" style="margin-left: 5px; margin-bottom: 15px;">Submit</button>
</form>';
}
else {
echo '
<div>
<p style="margin-left: 5px;font-size:16px;font-weight:200;margin-top:-45px;">Please sign in above to comment...</p></div>';
}

?>
<?php
}
?>
        
        </div>	
    </div>	
  </div>

		<div class="grid_7 push_2" style="margin-top:10px;">
			<div class="grid_7 box"> <!--ID Tag-->
				<div>
					<div id="imgborder">
					<img src="<?php echo $profilepic; ?>" class="profilepic"/>
				</div>

			<div id="namewrap">
				<h1 id="name"><a class="click" href="viewprofile.php?u=<?php echo $user; ?>"><?php echo $fullname; ?></a></h1>
				<a data-toggle="modal" data-backdrop="static" href="#fwmodal"><button style="width:80px;margin-left:15px;" class="btn btn-primary"> Follow </button></a>
				<div class="progress progress-success" style="width:110px;height: 10px;margin-top:10px;">
                <div class="bar" style="width:<?php echo $reputation; ?>%;"> 
                </div></div>

				<h1 id="rep"> Rep: &nbsp <?php echo $reputation; ?> </h1>
			</div>	
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
        
                elseif($reputationme > 0 && $reputationme < 30)
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
    
            echo '<div style="position: relative; top: 0px; text-align: center; font-size: 15px; font-family: arial;">Thanks for voting!</div>';

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
                <form id="Form1" action="', htmlentities($_SERVER['PHP_SELF']), '?image=', $image, '&v=', $view, '" method="post">
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
				<button class="btn btn-primary" style="padding: .45em 1em .45em 1em;"> <img src="graphics/cart_white.png" style="width:20px;height:20px;float:right;"/> </button>
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
						<p id="sharenumber"> <?php if($price == 'Not For Sale') {echo'NFS';} else {echo $price; } ?> </p>
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
					<a href="fullsize.php?image=<?php echo $imageOne; ?>&v=<?php echo $view; ?>"><img src="<?php echo $imageThreeThumb; ?>"id="nextimg3"/></a>
				</div>
				<a style="text-decoration:none;" href="fullsize.php?image=<?php echo $imageBefore; ?>&v=<?php echo $view; ?>"><div class="grid_1" id="hover_arrow_left">
				</div></a>
					<a style="text-decoration:none;" href="fullsize.php?image=<?php echo $imageOne; ?>&v=<?php echo $view; ?>"><div class="grid_1" id="hover_arrow_right">
				</div></a>
				</div>
			
			<div class="grid_7 box underbox"><!--Share stuff here-->
					<h1 id="sharelinks"> Share: </h1>
                    
                    <a href="https://www.facebook.com/sharer.php?u=http%3A%2F%2Fphotorankr.com%2Ffullsize.php?image=<?php echo $image; ?>" type="button" share_url="photorankr.com/fullsize.php?image=<?php echo $image; ?>"><img src="graphics/facebook.png" style="width:30px;height:30px;margin: 7px 9px 0px 10px;"/></a>
                    <script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" 
                    type="text/javascript">
                    </script>

					<a href="https://twitter.com/share" data-text="Check out this photo!" data-via="PhotoRankr" data-size="large" data-count="none"><img src="graphics/twitter.png" style="width:30px;height:30px;margin: 7px 9px 0px 5px;"/></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

					<a href=""><img src="graphics/pinterest.png" style="width:30px;height:30px;margin: 7px 9px 0px 5px;"/></a>
                    
<a href="https://plus.google.com/102253183291914861528"><img src="graphics/g+.png" style="width:30px;height:30px;margin:7px 9px 0px 8px;"/></a>
                    
			</div>
		
			<div class="grid_7 box underbox"><!--About photo-->
				<h1> About </h1>
					<div class="grid_7">
                    
                    <?php
                    
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

 </body>
 </html>  
