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
    
    $findreputationme = mysql_query("SELECT reputation,profilepic,firstname,lastname FROM userinfo WHERE emailaddress = '$email'");
    $reputationme = mysql_result($findreputationme,0,'reputation');
    $sessionpic = mysql_result($findreputationme,0,'profilepic');
    $sessionfirst =  mysql_result($findreputationme,0,'firstname');
    $sessionlast =  mysql_result($findreputationme,0,'lastname');
    $sessionname = mysql_result($findreputationme,0,'firstname') ." ". mysql_result($findreputationme,0,'lastname');
    

//check if they aren't logged in
if($_SESSION['loggedin'] != 1) {
	mysql_close();
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=signup.php?action=disc">';
	exit();
}
else {
	$image = htmlentities($_GET['image']);
	if(!$_GET['image'] || $_GET['image'] == "") {
		mysql_close();
		header("Location: myprofile.php?view=editinfo&error=disc#disc");
		exit();			
	}

	//get the users email address
	$useremail = $_SESSION['email'];

	//get the users information from the database
	$likesquery = "SELECT * FROM userinfo WHERE emailaddress='$useremail'";
	$likesresult = mysql_query($likesquery) or die(mysql_error());
	$discoverseen = mysql_result($likesresult, 0, "discoverseen");
    $reputationme = mysql_result($likesresult, 0, "reputation");
        
	//find out what they like
	$likes = mysql_result($likesresult, 0, "viewLikes");
	if($likes=="") {
		mysql_close();
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=myprofile.php?view=editinfo&action=discover#discover">';
		exit();		
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

	//update the database with the image they are seeing right now to reflect that it has been seen
	if($discoverseen != "") {
			$discoverseen .= " ";
			$discoverseen .= $image;
	}
	else {
		$discoverseen = $image;
	}

	$image = mysql_real_escape_string($image);

	$updatequery = mysql_query("UPDATE photos SET views=views+1 WHERE id='$image'") or die(mysql_error());

	$discoverseen = trim($discoverseen);

	$seenquery = "UPDATE userinfo SET discoverseen='$discoverseen' WHERE emailaddress='$useremail'";
	$seenresult = mysql_query($seenquery);

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
	if($discoverseen != "") {			//get the photos that match this person's view interests
		$viewquery = "SELECT *, MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$formattedLikes') AS matching FROM photos WHERE MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$formattedLikes') AND id NOT IN(" . $discoverFormatted . ") ORDER BY matching DESC, points DESC LIMIT 0, 1";
		$viewresult = mysql_query($viewquery) or die(mysql_error());
	}
	else {
		//get the photos that match this person's view interests
		$viewquery = "SELECT *, MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$formattedLikes') AS matching FROM photos WHERE MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$formattedLikes') ORDER BY matching DESC, points DESC LIMIT 0, 1";
		$viewresult = mysql_query($viewquery) or die(mysql_error());
	}

	//display the image itself
	$currentquery = "SELECT source, id FROM photos WHERE id='$image' LIMIT 0, 1";
	$currentresult = mysql_query($currentquery);
    $currentimage = $image;
	$image = mysql_result($currentresult, 0, "source");
    $nextimage = mysql_result($viewresult, 0, "id");
}



//FIND THE PHOTO IN DATABASE
$query="SELECT * FROM photos where source='$image'";
$result=mysql_query($query);
//if no images match what is in the url, then send them back to trending 
if(mysql_num_rows($result) == 0) {
	header("Location: myprofile.php?view=editinfo&error=disc#disc");
	exit();
}

$row=mysql_fetch_array($result);
$emailaddress=$row['emailaddress'];
$caption=$row['caption'];
$location=$row['location'];
$country=$row['country'];
$prevpoints=$row['points'];
$prevvotes=$row['votes'];
$imageID=$row['id'];
$price=mysql_result($result, 0, "price");
$camera = $row['camera'];
$faves= $row['faves'];
$views = $row['views'];
$exhibit = $row['set_id'];
$exhibitname = $row['sets'];
$focallength = $row['focallength'];
$shutterspeed = $row['shutterspeed'];
$aperture = $row['aperture'];
$lens = $row['lens'];
$filter = $row['filter'];
$copyright = $row['copyright'];
$about = $row['about'];
$tag1 = $row['tag1'];
$tag2 = $row['tag2'];
$tag3 = $row['tag3'];
$tag4 = $row['tag4'];
$maintags = $row['maintags'];
$settags = $row['settags'];
$singlecategorytags = $row['singlecategorytags'];
$singlestyletags = $row['singlestyletags'];
$tags = $settags + $maintags + $singlecategorytags + $singlestyletags;

//find how many photos the photographer has
$numberofpics = mysql_query("SELECT * FROM photos WHERE emailaddress='$emailaddress'");
$numberofpics = mysql_num_rows($numberofpics);

$locationandcountry = $location . $country;

if ($price == "") {$price='.25';}  

//FIND THE PHOTOGRAPHER NAME IN DATABASE
$namequery="SELECT * FROM userinfo WHERE emailaddress='$emailaddress'";
$nameresult=mysql_query($namequery);
$row=mysql_fetch_array($nameresult);
$user=$row['user_id'];
$firstname=$row['firstname'];
$lastname=$row['lastname'];
$fullname = $firstname . " " . $lastname;
$fullname = (strlen($fullname ) > 14) ? substr($fullname,0,12). " &#8230;" : $fullname;
$profilepic=$row['profilepic'];
$profilescore=$row['totalscore'];
$reputation=$row['reputation'];

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
$notsquery = "UPDATE userinfo SET notifications = (notifications - 1) WHERE emailaddress = '$email6'";
$notsqueryrun = mysql_query($notsquery); }
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


//get the flags variable and update the database
$f;
if(isset($_GET['f'])) {
$f=htmlentities($_GET['f']);
}
else {$f=0;}
if ($f==1) {
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
		//if the image has already been favorited
		if($match) {
			//tell them so
			/* echo '<div style="position:absolute;  top:100px; left:820px; font-family: lucida grande, georgia; color:black; font-size:15px;">This photo is already in your favorites!</div>'; */
		}
		else {
			$favesquery="UPDATE userinfo SET faves=CONCAT(faves,'$queryimage') WHERE emailaddress='$vieweremail'";
			mysql_query($favesquery);
			mysql_query("UPDATE photos SET faves=faves+1 WHERE source='$image'");
            
             //newsfeed query
        $type = "fave";
        $newsfeedfavequery=mysql_query("INSERT INTO newsfeed (firstname, lastname, emailaddress,type,source,caption,owner) VALUES ('$viewerfirst', '$viewerlast', '$useremail','$type','$image','$caption','$emailaddress')");
     
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
            $to = $emailaddress;
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

            
//Grab VIEWERS reputation score
    
    $toprankedphotos2 = "SELECT * FROM photos WHERE emailaddress = '$email6' ORDER BY points DESC";
    $toprankedphotosquery2 = mysql_query($toprankedphotos2);
    $numtoprankedphotos2 = mysql_num_rows($toprankedphotos2);

    for($i=0;$i<15;$i++){
    $toprankedphotopoints2 = mysql_result($toprankedphotosquery2, $i, "points") + $toprankedphotopoints2;
    }
    
    $userphotos2="SELECT * FROM photos WHERE emailaddress = 'email6'";
    $userphotosquery2=mysql_query($userphotos2);
    $numphotos2=mysql_num_rows($userphotosquery2);
    
    //Gather Total Number of Votes for All Photos (This is Visibility)
    for($ii=0; $ii<$numphotos2;$ii++){
    $totalvotes2 = mysql_result($userphotosquery2, $ii, "votes") + $totalvotes2; 
    }
    

    $followersquery2="SELECT * FROM userinfo WHERE following LIKE '%$email6%'";
	$followersresult2 = mysql_query($followersquery2);
    $numberfollowers2 = mysql_num_rows($followersresult2);
    $totalpgviews2 = $totalvotes2;
    $ranking2 = $toprankedphotopoints2;
    $followerlimit2 =30;
    $totalpgviewslimit2 = 800;
    $rankinglimit2 = 150; 
    $followerweight2 = .3;
    $totalpgviewsweight2 = .4;
    $rankingweight2 = .3; 

    
    if($numberfollowers2 > $followerlimit2) {
    $followerweighted2 = $followerweight2;
    }
    
    else{
    $followerdivisionfactor2 = ($numberfollowers2)/($followerlimit2);    
    $followerweighted2 = $followerweight2*$followerdivisionfactor2;
    }

    if($totalpgviews2 > $totalpgviewslimit2) {
        $totalpgviewsweighted2 = $totalpgviewsweight2;
    }
    
    else {
        $totalpgviewsdivisionfactor2 = ($totalpgviews2)/($totalpgviewslimit2); 
        $totalpgviewsweighted2 = $totalpgviewsweight2*$totalpgviewsdivisionfactor2;

    }
    

    
   if($ranking2 > $rankinglimit2) {
        $rankingweighted2 = $rankingweight2;
    }
    
    elseif($ranking2 > 135) {
        $rankingweighted2 = $rankingweight2 * .95;
    }
    
    elseif($ranking2 <= 135 && $ranking2 > 120) {       
     $rankingweighted2 = $rankingweight2 *.90;
    }
    
    elseif($ranking2 <= 120 && $ranking2 > 105) {
        $rankingweighted2 = $rankingweight2 *.85;
    }
    
    elseif($ranking2 <= 105 && $ranking2 > 90) {
        $rankingweighted2 = $rankingweight2 *.50;
    }
    
    elseif($ranking2 <= 90 && $ranking2 > 75) {
        $rankingweighted2 = $rankingweight2 *.30;
    }
    
    else {
       $rankingweighted2 = $rankingweight2 *.10;
    }
        
    if($numtoprankedphotos2 < 14) { 
    $rankingweighted2 = .1;
    }

    $ultimatereputationme = ($followerweighted2+$rankingweighted2+$totalpgviewsweighted2) * 100;
    
    
    //OWNER'S REPUTATION

    $toprankedphotos = "SELECT * FROM photos WHERE emailaddress = '$emailaddress' ORDER BY points DESC";
    $toprankedphotosquery = mysql_query($toprankedphotos);
    $numtoprankedphotos = mysql_num_rows($toprankedphotos);

    for($i=0;$i<15;$i++){
    $toprankedphotopoints = mysql_result($toprankedphotosquery, $i, "points") + $toprankedphotopoints;
    }
    
    $userphotos="SELECT * FROM photos WHERE emailaddress = '$emailaddress'";
    $userphotosquery=mysql_query($userphotos);
    $numphotos=mysql_num_rows($userphotosquery);
    
    //Gather Total Number of Votes for All Photos (This is Visibility)
    for($ii=0; $ii<$numphotos;$ii++){
    $totalvotes = mysql_result($userphotosquery, $ii, "votes") + $totalvotes; 
    }
    

    $followersquery="SELECT * FROM userinfo WHERE following LIKE '%$emailaddress%'";
	$followersresult=mysql_query($followersquery);
    $numberfollowers = mysql_num_rows($followersresult);
    $totalpgviews= $totalvotes;
    $ranking = $toprankedphotopoints;
    $followerlimit =30;
    $totalpgviewslimit = 800;
    $rankinglimit = 150; 
    $followerweight = .3;
    $totalpgviewsweight = .4;
    $rankingweight = .3; 

    
    if($numberfollowers > $followerlimit) {
    $followerweighted = $followerweight;
    }
    
    else{
    $followerdivisionfactor = ($numberfollowers)/($followerlimit);    
    $followerweighted = $followerweight*$followerdivisionfactor;
    }

    if($totalpgviews > $totalpgviewslimit) {
        $totalpgviewsweighted = $totalpgviewsweight;
    }
    
    else {
        $totalpgviewsdivisionfactor = ($totalpgviews)/($totalpgviewslimit); 
        $totalpgviewsweighted = $totalpgviewsweight*$totalpgviewsdivisionfactor;

    }
    

    
    if($ranking > $rankinglimit) {
        $rankingweighted = $rankingweight;
    }
    
    elseif($ranking > 135) {
        $rankingweighted = $rankingweight* .95;
    }
    
    elseif($ranking <= 135 && $ranking > 120) {       
     $rankingweighted = $rankingweight*.90;
    }
    
    elseif($ranking <= 120 && $ranking > 105) {
        $rankingweighted = $rankingweight*.85;
    }
    
    elseif($ranking <= 105 && $ranking > 90) {
        $rankingweighted = $rankingweight*.50;
    }
    
    elseif($ranking <= 90 && $ranking > 75) {
        $rankingweighted = $rankingweight*.30;
    }
    
    else {
       $rankingweighted = $rankingweight*.10;
    }
        
    if($numtoprankedphotos < 14) { 
    $rankingweighted = .1;
    }

    $ultimatereputation = ($followerweighted+$rankingweighted+$totalpgviewsweighted) * 100;

//DISCOVER SCRIPT

  //get the users information from the database
  $likesquery = "SELECT * FROM userinfo WHERE emailaddress='$useremail'";
  $likesresult = mysql_query($likesquery) or die(mysql_error());
  $discoverseen = mysql_result($likesresult, 0, "discoverseen");

  //find out what they like
  $likes = mysql_result($likesresult, 0, "viewLikes");
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
  
  
  //COMMENT QUERIES

if(htmlentities($_POST['comment']) && $_SESSION['loggedin'] == 1) {
    
    $currenttime = time();
    $comment = mysql_real_escape_string(htmlentities($_POST['comment']));
    $insertcomment = mysql_query("INSERT INTO comments (comment,commenter,photoowner,imageid,time) VALUES ('$comment','$useremail','$emailaddress','$imageID','$currenttime')");
    
    //MAIL TO OWNER OF PHOTO
    $settingquery = mysql_query("SELECT settings FROM userinfo WHERE emailaddress = '$emailaddress'");
    $settinglist = mysql_result($settingquery,0,"settings");
    $check = 'emailcomment';
    $foundsetting = strpos($settinglist,$check);
    
    $to = '"' . $firstname . ' ' . $lastname . '"' . '<'.$emailaddress.'>';
    $subject = $sessionname ." commented on your photo on PhotoRankr";
    $message = stripslashes($comment) . "
    
To view the photo, click here: http://photorankr.com/fullsize.php?image=".$image;
    $headers = 'From:PhotoRankr <photorankr@photorankr.com>';
    
    if($foundsetting > 0 && $emailaddress != $email) {
        mail($to, $subject, $message, $headers);  
    }

    //MAIL TO PREVIOUS COMMENTERS ON PHOTO
    $previouscommenters = mysql_query("SELECT commenter FROM comments WHERE imageid = '$imageID'");
    $numcommenters = mysql_num_rows($previouscommenters);
    $prevemails .= $useremail;
      
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
            $subject = $sessionname . " also commented on " . $firstname . " " . $lastname ."'s photo on PhotoRankr";
            $returnmessage = stripslashes($message) . "
        
To view the photo, click here: http://photorankr.com/fullsize.php?image=".$image;
            
            $headers = 'From:PhotoRankr <photorankr@photorankr.com>';
            
            if($foundsetting > 0 && $sendtoemail != $useremail) {     
                mail($to, $subject, $returnmessage, $headers);
            } 
    
        }
        
        elseif($alreadysent > 0) {
            continue;
        }
        
        $prevemails .= " " . $prevemail;
    
    }
    
        $type = "comment";
        $newsfeedcomment = mysql_query("INSERT INTO newsfeed (firstname, lastname, emailaddress,owner,type,source) VALUES ('$sessionfirst', '$sessionlast', '$useremail','$emailaddress','$type','$image')") or die();
            
    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=discover.php?image=', $currentimage,'">';
	exit();

}

if(htmlentities($_GET['action']) == 'deletecomment' && $_SESSION['loggedin'] == 1) {
    
    $commentid = htmlentities($_GET['cid']);
    $deletecomment = mysql_query("DELETE FROM comments WHERE id = '$commentid'");

}

  
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://w3.org/TR/html4/strict.dtd">
<html>
  <head>       
	<title>Discover - PhotoRankr's way to allow users to find photos they may have never seen before</title>
          <meta name="viewport" content="width=1250" /> 
<link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
<link rel="stylesheet" type="text/css" href="css/bootstrapNew.css"/>
<link rel="stylesheet" type="text/css" href="css/reset.css"/>
<link rel="stylesheet" type="text/css" href="css/all.css"/>
<link rel="stylesheet" type="text/css" href="css/reset.css"/>
<link rel="stylesheet" type="text/css" href="css/style.css"/>
<link rel="stylesheet" type="text/css" href="css/960_24_col.css"/>
<link rel="stylesheet" type="text/css" href="newfullsize.css"/>

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



<?php
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
        $newsfeedfollowquery="INSERT INTO newsfeed (firstname, lastname, emailaddress,following,type,owner) VALUES ('$viewerfirst', '$viewerlast', '$useremail','$emailaddress','$type2','$ownername')";
        $follownewsquery = mysql_query($newsfeedfollowquery);
        
        //notifications query     
$notsquery = "UPDATE userinfo SET notifications = (notifications + 1) WHERE emailaddress = '$emailaddress'";
$notsqueryrun = mysql_query($notsquery);  
        
            
		/*	echo '<div style="position:absolute; top:100px; left:800px; font-family: lucida grande, georgia; color:black; font-size:15px;z-index:72983475273459273458972349587293745;">Now Following ',$firstname,' ',$lastname,'</div>';    */
            
             		//PERSON NOW BEING FOLLOWED
    
//GRAB SETTINGS LIST
$settingemail = $_SESSION['email'];
$settingquery = "SELECT * FROM userinfo WHERE emailaddress = '$settingemail'";
$settingqueryrun = mysql_query($settingquery);
$settinglist = mysql_result($settingqueryrun, 0, "settings");

$setting_string = $settinglist;
$find = "emailfollow";
$foundsetting = strpos($setting_string,$find);
    
        		$to = $emailaddress;
        		$subject = $viewerfirst . " " . $viewerlast . ' is now following your photography on PhotoRankr!';
        		$message = 'You have a new follower on PhotoRankr! Visit their photography here: http://photorankr.com/viewprofile.php?first=' . $viewerfirst . '&last=' . $viewerlast;
        		$headers = 'From:PhotoRankr <photorankr@photorankr.com>';
                if($foundsetting > 0) {
        		mail($to, $subject, $message, $headers);   
                }
		}
	}
}

?>

     
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
<a style="float:right" class="btn btn-success"  href="discover.php?image=',$currentimage,'&fw=1">Close</a>
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
<a style="float:right" class="btn btn-success" href="discover.php?image=',$currentimage,'&f=1">Close</a>
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


<body class="background" style="overflow-x: hidden;">

<?php navbarnew(); ?>
    

<!--Here the Grid Container Begins-->
<div class="container_24 container-margin" style="margin-top:70px;"> 


<!--DISCOVER BAR-->
<div style="margin-left: 1em;" class="grid_12 push_12">
</div>
<div class="discover" style="top: 35px; left: 785px; z-index: 3; width:450px;">
<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>?image=<?php echo $currentimage; ?>" method="post">
<div class="control-group" style="position: relative; left: 30px; top: 20px;">
<select class="span1" style="position:relative;top:4px;" name="ranking">
<option style="display:none;" value="">&mdash;</option>
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
<button class="btn btn-success" type="submit" style="position:relative;margin-left:10px;">RANK</button>
</div>
</form>
<a style="position: relative; text-decoration:none; left: 160px; top: -22px;" href="#fvmodal" data-backdrop="static" data-toggle="modal">
<button class="btn btn-danger" style="width: 120px;margin-left:10px;">FAVORITE</button>
</a>
<a style="position: relative; left: 160px; top: -22px;" href="discover.php?image=<?php echo $nextimage; ?>">
<button class="btn btn-primary" style="width: 120px;">DISCOVER!</button>
</a>
</div>



<div class="grid_15">	
	<div class="grid_14 pull_1" style="float:left;">
		<h1 style="font-size:22px;padding-bottom:15px;font-weight:200;"> <?php echo $caption; ?> </h1>
	</div>	
	<div class="grid_17 pull_1" style="float:left;" >
	<img onmousedown="return false" oncontextmenu="return false;" src="<?php echo $image; ?>" class="image" height="<?php echo $newheight; ?>px" width="<?php echo $newwidth; ?>px" />	
	</div>


<!--COMMENT BOX-->

<div class="grid_16 pull_1 comments-box">
    
    <?php
        
        //ADD COMMENT
        if($_SESSION['loggedin'] == 1) {
        
            echo'
                <form action="" method="POST" />
                    <div style="width:610px;"><img style="float:left;padding:10px;" src="',$sessionpic,'" height="30" width="30" />
                    <input style="float:left;width:495px;height:20px;position:relative;top:10px;" type="text" name="comment" placeholder="Leave feedback for ',$firstname,'&#8230;" />
                    <input style="float:left;margin-top:11px;margin-left:4px;" type="submit" class="btn btn-success" value="Post"/>
                    </div>
                </form>';
         
        }
            
        $grabcomments = mysql_query("SELECT * FROM comments WHERE imageid = '$imageID' ORDER BY id DESC");
        $numcomments = mysql_num_rows($grabcomments);
        
        for($iii = 0; $iii < $numcomments; $iii++) {
        
            $comment = mysql_result($grabcomments,$iii,'comment');
            $commentid = mysql_result($grabcomments,$iii,'id');
            $commenteremail = mysql_result($grabcomments,$iii,'commenter');
            $commenterinfo = mysql_query("SELECT user_id,firstname,lastname,profilepic,reputation FROM userinfo WHERE emailaddress = '$commenteremail'");
            $commentername = mysql_result($commenterinfo,0,'firstname') ." ". mysql_result($commenterinfo,0,'lastname');
            $commenterid = mysql_result($commenterinfo,0,'user_id');
            $commenterpic = mysql_result($commenterinfo,0,'profilepic');
            $commenterrep = number_format(mysql_result($commenterinfo,0,'reputation'),2);
        
        //SHOW PREVIOUS COMMENTS
        echo'
            <div class="grid_16" style="width:610px;margin-top:20px;padding:5px;">
            <a href="viewprofile.php?u=',$commenterid,'"><div style="float:left;"><img class="roundedall" src="',$commenterpic,'" alt="',$commentername,'" height="40" width="35"/></a></div>
            <div style="float:left;padding-left:6px;width:560px;">
                <div style="float:left;color:#3e608c;font-size:14px;font-family:helvetica;font-weight:500;border-bottom: 1px solid #ccc;width:560px;"><div style="float:left;"><a href="viewprofile.php?u=',$commenterid,'">',$commentername,'</a> &nbsp;<span style="font-size:16px;font-weight:100;color:black;margin-top:2">|</span>&nbsp;<span style="color:#333;font-size:12px;">Rep: ',$commenterrep,'</span></div>&nbsp;&nbsp;&nbsp;
                    <div class="progress progress-success" style="float:left;width:110px;height:7px;opacity:.8;margin:7px;">
                    <div class="bar" style="width:',$commenterrep,'%;">
                    </div></div>';
                 if($email == $emailaddress) {
                    echo'
                        <div style="float:right;font-size:12px;font-weight:500;"><a style="color#ccc;text-decoration:none;" href="fullsizeview.php?image=',$image,'&action=deletecomment&cid=',$commentid,'">X</a></div>';
                }
                echo'
                </div>
                <div style="float:left;width:520px;padding:10px;font-size:13px;font-family:helvetica;font-weight:300;color:#555;">',$comment,'</div>
            </div>
            </div>';
            
        }
        
        $image=mysql_real_escape_string($_GET['image']);
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


		<div class="grid_7 push_2" style="margin-top:50px;">
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

                </div>
                
                <div style="float:right;margin-right:10px;"><button class="btn btn-primary" style="padding: .45em 1em .45em 1em;"><a href="fullsizemarket.php?imageid=<?php echo $imageID; ?>"><img src="graphics/cart_white.png" style="width:20px;height:20px;"/></a></button></div>
                
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
			
			<div class="grid_7 box underbox"><!--Share stuff here-->
					<h1 id="sharelinks"> Share: </h1>
                    
                    <a href="https://www.facebook.com/sharer.php?u=http%3A%2F%2Fphotorankr.com%2Ffullsize.php?image=<?php echo $image; ?>" type="button" share_url="photorankr.com/fullsize.php?image=<?php echo $image; ?>"><img src="graphics/facebook.png" style="width:30px;height:30px;margin: 7px 9px 0px 10px;"/></a>
                    <script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" 
                    type="text/javascript">
                    </script>

					<a href="https://twitter.com/share" data-text="Check out this photo!" data-via="PhotoRankr" data-size="large" data-count="none"><img src="graphics/twitter.png" style="width:30px;height:30px;margin: 7px 9px 0px 5px;"/></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

					<a href="http://pinterest.com/pin/create/button/" class="pin-it-button" count-layout="none"><img src="graphics/pinterest.png" style="width:30px;height:30px;margin: 7px 9px 0px 5px;"/></a>
<script type="text/javascript" src="http://assets.pinterest.com/js/pinit.js"></script>
                    
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
<br /><br />

<?php footer(); ?>
          
</body>
</html>
      
       
        
    