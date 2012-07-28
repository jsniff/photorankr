<?php

//log them out if they try to logout
session_start();

if($_GET['action'] == "logout") {
	$_SESSION['loggedin'] = 0;
	session_destroy();
}

//connect to the database
require "db_connection.php";
require "functionscampaigns.php";

//start session
session_start();
//if the login form is submitted
if ($_GET['action'] == "login") { // if login form has been submitted

	// makes sure they filled it in
	if(!$_POST['emailaddress'] | !$_POST['password']) {
		die('You did not fill in a required field.');
	}

	// checks it against the database
	if (!get_magic_quotes_gpc()) {
   	$_POST['emailaddress'] = addslashes($_POST['emailaddress']);
    	}
    	$check = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '".$_POST['emailaddress']."'")or die(mysql_error());
	//Gives error if user dosen't exist

	$check2 = mysql_num_rows($check);

	if ($check2 == 0) {

        	die('That user does not exist in our database. <a href=signin.php>Click Here to Register</a>');

        }
	$info = mysql_fetch_array($check);    
	if($_POST['password'] == $info['password']){

	//then redirect them to the same page as signed in and set loggedin to 1
	$_SESSION['loggedin']=1; 
	$_SESSION['email']=$_POST['emailaddress'];
    $email = $_SESSION['email'];
	}
    
	//gives error if the password is wrong
    	if ($_POST['password'] != $info['password']) {
die('Incorrect password, please try again. <a href="lostpassword.php"> Lost your password?</a>');	}
}


    //GET INFO FROM CURRENT PHOTO ID
    $id = htmlentities($_GET['id']);
    $photoid = $_GET['id'];
    $imagequery = "SELECT source,caption,campaign,points,votes FROM campaignphotos WHERE id = '$id'";
    $imagequeryrun = mysql_query($imagequery);
    $image = mysql_result($imagequeryrun, 0, 'source');
    $findme   = 'photorankr.com';
    $pos = strpos($image, $findme);
    if($pos !== false) {
        $image = str_replace("userphotos/","userphotos/", $image);
    }
    else{
        $image = str_replace("userphotos/","market/userphotos/", $image);
    }
    if($image == '') {
    $image = 'market/graphics/submitaphoto.png';
    }
	$title = mysql_result($imagequeryrun, 0, 'caption');
    $campaign = mysql_result($imagequeryrun, 0, 'campaign');
    $numincampquery = mysql_query("SELECT id FROM campaignphotos WHERE campaign = '$campaign'");
    $numincamp = mysql_num_rows($numincampquery);
    $imageLast = "SELECT id FROM campaignphotos WHERE campaign = '$campaign' ORDER BY id DESC LIMIT 1";
    $imageLastquery = mysql_query($imageLast);
    $lastID = mysql_result($imageLastquery, 0, 'id');
    $imageFirst = "SELECT id FROM campaignphotos WHERE campaign = '$campaign' ORDER BY id ASC LIMIT 1";
    $imageFirstquery = mysql_query($imageFirst);
    $firstID = mysql_result($imageFirstquery, 0, 'id');

    
    //GET ID's OF PREVIEWS AND NEXT/BACK FUNCTIONS        
    $imageBeforequery = "SELECT id FROM campaignphotos WHERE campaign = '$campaign' AND id < '$id' ORDER BY id DESC LIMIT 1";
    $imageBeforequeryrun = mysql_query($imageBeforequery);
    $imageBeforeID = mysql_result($imageBeforequeryrun, 0, 'id');
    if($imageBeforeID == '') {
        $imageBeforeID = $lastID;
    }
    if($numincamp == 1){
    $imageBeforeID = $id;
    }
    
    $imageNextquery = "SELECT id FROM campaignphotos WHERE campaign = '$campaign' AND id > '$id' LIMIT 1";
    $imageNextqueryrun = mysql_query($imageNextquery);
    $imageNextID = mysql_result($imageNextqueryrun, 0, 'id');
    if($imageNextID == '') {
        $imageNextID = $firstID; 
    }
    if($numincamp == 1){
    $imageNextID = $id;
    }
    
    $imageTwoquery = "SELECT id FROM campaignphotos WHERE campaign = '$campaign' AND id > '$imageNextID' LIMIT 1";
    $imageTwoqueryrun = mysql_query($imageTwoquery);
    $imageTwoID = mysql_result($imageTwoqueryrun, 0, 'id');
    if($imageTwoID > $lastID) {
        $imageTwoID = $firstID; 
    }
    
    $imageThreequery = "SELECT id FROM campaignphotos WHERE campaign = '$campaign' AND id > '$imageTwoID' LIMIT 1";
    $imageThreequeryrun = mysql_query($imageThreequery);
    $imageThreeID = mysql_result($imageThreequeryrun, 0, 'id');
    if($imageThreeID > $lastID) {
        $imageThreeID = $firstID; 
    }
    
    //GET THE PREVIEW"S SOURCES
    if($numincamp == 1){
    $imageNextID = '';
    }
    $imagenextquery = "SELECT source FROM campaignphotos WHERE campaign = '$campaign' AND id = '$imageNextID'";
    $imagenextqueryrun = mysql_query($imagenextquery);
    $imageNext = mysql_result($imagenextqueryrun, 0, 'source');
    $imageNext = str_replace("userphotos/","market/userphotos/medthumbs/", $imageNext);
    if($imageNext == '') {
    $imageNext = 'market/graphics/submitaphoto.png';
    }

    if($numincamp == 1){
    $imageTwoID = '';
    }
    $imagetwoquery = "SELECT source FROM campaignphotos WHERE campaign = '$campaign' AND id = '$imageTwoID'";
    $imagetwoqueryrun = mysql_query($imagetwoquery);
    $imageTwo = mysql_result($imagetwoqueryrun, 0, 'source');
    $imageTwo = str_replace("userphotos/","market/userphotos/medthumbs/", $imageTwo);
    if($imageTwo == '') {
    $imageTwo = 'market/graphics/submitaphoto.png';
    }

    if($numincamp == 1){
    $imageThreeID = '';
    }
    $imagethreequery = "SELECT source FROM campaignphotos WHERE campaign = '$campaign' AND id = '$imageThreeID'";
    $imagethreequeryrun = mysql_query($imagethreequery);
    $imageThree = mysql_result($imagethreequeryrun, 0, 'source');
    $imageThree = str_replace("userphotos/","market/userphotos/medthumbs/", $imageThree);
    if($imageThree == '') {
    $imageThree = 'market/graphics/submitaphoto.png';
    }

    
    if(!$_GET['id'] || $_GET['id'] == "") {
	    mysql_close();
        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=trending.php">';
		exit();			
    }
    

//GET PREVIOUS VOTES FOR RANKING
$prevvotes = mysql_result($imagequeryrun, 0, 'votes');
$prevpoints = mysql_result($imagequeryrun, 0, 'points');

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
        $newsfeedfavequery=mysql_query("INSERT INTO newsfeed (firstname, lastname, emailaddress,type,source,caption,owner) VALUES ('$viewerfirst', '$viewerlast', '$email','$type','$image','$caption','$emailaddress')");
     
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
"http://w3.org/TR/html4/strict.dtd">
<html>
  <head>       
	<title>Fullsize Photo - "<?php echo $title; ?>"</title>
  <link rel="stylesheet" type="text/css" href="bootstrapnew.css" />
  <link rel="stylesheet" href="reset.css" type="text/css" />
  <link rel="stylesheet" href="text.css" type="text/css" />
  <link rel="stylesheet" href="newfullsize.css" type="text/css" />
  <link rel="stylesheet" href="960_24.css" type="text/css" />
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script src="market/js/bootstrap.js" type="text/javascript"></script>
  <script src="market/js/bootstrap-dropdown.js" type="text/javascript"></script>
  <script src="market/js/bootstrap-collapse.js" type="text/javascript"></script>
  <link rel="shortcut icon" type="image/x-png" href="market/graphics/favicon.png"/>

     <script src="market/bootstrap-dropdown.js" type="text/javascript"></script>
     <script src="market/bootstrap-collapse.js" type="text/javascript"></script>
     
      <!--Navbar Dropdowns-->
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
        
          <style type="text/css">
      
      
        .imageContainer{width:auto;} 
        .imageContainer img {display:block;width:100%;height:auto;}
        
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
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>



     
</head>

<body class="background" style="overflow-x: hidden;min-width:1220px;">

<!--NAVIGATION BAR-->
<div class="navbar" style="z-index:10;padding-top:0px;min-width:1220px;font-size:16px;width:100%;">
	<div class="navbar-inner">
		<div class="container">
			    <ul class="nav">
					<li><a style="color:#fff;" class="brand" style="margin-top:10px;margin-right:20px;" href="../index.php"><div style="margin-top:-2px"><img src="../logo.png" width="160" /></div></a></li>
                    
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

						<a style="color:#fff;margin-top:2px;" href="trending.php" class="dropdown-toggle" data-toggle="dropdown">Galleries<b class="caret" style="background-color:#1a618a;
"></b></a>
						<ul class="dropdown-menu" data-dropdown="dropdown">
							<li><a style="color:#fff;" href="trending.php">Trending</a></li>
							<li><a style="color:#fff;" href="newest.php">Newest</a></li>
                            <li><a style="color:#fff;" href="topranked.php">Top Ranked</a></li>
                        </ul>
                </li>
                    
                    
					<li style="background-color:#587fa2;"><a style="color:#fff;margin-top:2px;" href="viewcampaigns.php">Campaigns</a></li>
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

						<a style="color:#fff;margin-top:2px;" href="../myprofile.php" class="dropdown-toggle" data-toggle="dropdown">My Profile<b class="caret" style="background-color:#1a618a;
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
                <a style="color:#fff;margin-top:2px;" href="../signin.php" class="dropdown-toggle" data-toggle="dropdown">Log In<b class="caret"></b></a>
						<ul class="dropdown-menu" data-dropdown="dropdown">
							<li><a style="color:#fff;margin-left:-29px;font-size:15px;" href="signin.php">Register for free today</a></li>
							<li><br/></li>
							<form name="login_form" method="post" action="viewcampaigns.php?action=login">
							<li style="margin-left: 5px; margin-right: 5px; width: 185px;"><span style="color: white; margin-bottom: 5px;margin-left:10px;">Email: <br /></span><input type="text" style="width:150px;margin-top:3px;margin-left:10px;" name="emailaddress" /></li>
							<li><span style="color:white;margin-left:-16px;">Password: <br /></span><input type="password" style="width:150px;margin-top:3px;margin-left:-16px;" name="password"/></li>
                        <li style="margin-left: 110px;"><input type="submit" class="btn btn-success" value="Sign In" id="loginButton"/></li>
						</ul>';
} ?>
					</li>
					<form class="navbar-search" action="../search.php" method="get">
						<input type="text" style="width:150px;border-color:#fff;background-color:#fff;margin-left:20px;" class="search-query" name="searchterm" placeholder="Search">
					 </form>
					 
				</ul>
			
		</div> <!--/end boostrap divs navbar-->

                    
    </div>
</div>

<div class="container_24" style="padding-bottom:30px;"><!--Grid container begin-->
 


<!--DISCOVER BAR-->
<div style="margin-left: 1em;" class="grid_12 push_12">
</div>
<div class="discover" style="top: 45px; left: 1030px; z-index: 3; width:180px;">
<form action="fullsizecampaign.php?id=<?php echo $photoid; ?>" method="post">
<div class="control-group" style="position: relative; left: 25px; top: 5px;">
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
</div>

<?php
$campaigntitlequery = mysql_query("SELECT title from campaigns WHERE id = '$campaign'");
$camptitle = mysql_result($campaigntitlequery,0,'title');

?>

<!--TITLE OF PHOTO-->     
<div class="grid_24">
<div class="grid_21 pull_2"><div style="margin-top:70px;padding-top:5px;padding-left:3px;line-height:30px;font-size:30px;
"><?php echo '(<a href="campaignphotos.php?id=',$campaign,'">',$camptitle,'</a>) "',$title,'"'; ?>
</div></div></div>

<!--BIG IMAGE BOX-->
<div class="grid_24">

<div class="grid_15 pull_2" style="margin-top:150px;">

<div class="imageContainer" style="margin-top:-135px;">
<img class="phototitle" onmousedown="return false" oncontextmenu="return false;"  alt="<?php echo $tags; ?>" src="<?php echo $image; ?>" /></div>
  
</div> 


<!--ARROWS-->
<div class="grid_4 push_6 arrows" style="width:218px;padding:2px;">
<span style="margin-left:30px;"><a style="text-decoration:none;" href="fullsizecampaign.php?id=<?php echo $imageBeforeID; ?>"><img src="market/graphics/arrow left.png" alt="Scroll through photos in their full size glory"
height="50" width="50"/></a></span>

<span style="margin-left:55px;"><a style="text-decoration:none;" href="fullsizecampaign.php?id=<?php echo $imageNextID; ?>"><img  src="market/graphics/arrow right.png" alt="Scroll through photos in their full size glory"
height="50" width="50"/></a></span>

</div>

<!-- AddThis Button BEGIN -->
<div class="grid_4 push_6 addthis_toolbox addthis_default_style addthis_32x32_style" style="width:218px;top:5px;padding:10px;">
<a class="addthis_button_preferred_1"></a>
<a class="addthis_button_preferred_2"></a>
<a class="addthis_button_preferred_3"></a>
<a class="addthis_button_compact"></a>
<a class="addthis_counter addthis_bubble_style"></a>
</div>
<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4fce0eeb4e8937f9"></script>
<!-- AddThis Button END -->

    
<!--PHOTO INFORMATION BOX-->    
<div class="grid_4 push_6 photoinfo" style="padding:2px;width:218px;">
 <br />
 
 
 <?php

//get the ranking variable and update the database
$ranking=mysql_real_escape_string($_POST['ranking']);
if($_POST['ranking']) { //if ranking was posted
    $voteremail=$_SESSION['email'];
        
    if($voteremail) {
 $rankcheck = mysql_query("SELECT voters FROM campaignphotos WHERE id='$photoid'") or die(mysql_error());
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
		
        
        if($ultimatereputationme > 70 && $ultimatereputationme < 100)
        {
        $prevpoints+=($ranking*2.5);
		$prevvotes+=2.5;
		$rankquery="UPDATE campaignphotos SET points='$prevpoints', votes='$prevvotes' WHERE id='$photoid'";
		mysql_query($rankquery); 
        }
        
        elseif($ultimatereputationme > 50 && $ultimatereputationme < 70)
        {
        $prevpoints+=($ranking*2.0);
		$prevvotes+=2;
		$rankquery="UPDATE campaignphotos SET points='$prevpoints', votes='$prevvotes' WHERE id='$photoid'";
		mysql_query($rankquery); 
        }
        
        elseif($ultimatereputationme > 30 && $ultimatereputationme < 50)
        {
        $prevpoints+=($ranking*1.5);
		$prevvotes+=1.5;
		$rankquery="UPDATE campaignphotos SET points='$prevpoints', votes='$prevvotes' WHERE id='$photoid'";
		mysql_query($rankquery); 
        }
        
        elseif($ultimatereputationme > 0 && $ultimatereputationme < 30)
        {
        $prevpoints+=$ranking;
		$prevvotes+=1;
		$rankquery="UPDATE campaignphotos SET points='$prevpoints', votes='$prevvotes' WHERE id='$photoid'";
		mysql_query($rankquery); 
        }
        
        }  //end querying points and votes count
    
        //Add voter's name to database    
    $voter = "'" . $voteremail . "'";
    $voter = ", " . $voter;
    $voter = addslashes($voter);
    $votersquery = mysql_query("UPDATE campaignphotos SET voters=CONCAT(voters,'$voter') WHERE id='$photoid'");
    
    echo '<div style="position: relative; top: 0px; text-align: center; font-size: 15px; font-family: arial;">Thanks for voting!</div>';

	} 
    
    elseif($votematch && ($voteremail != $emailaddress)){
    	echo '<div style="position: relative; top: 0px; text-align: center; font-size: 15px; font-family: arial;">You already voted!</div>';

    }
    
    elseif($voteremail == $emailaddress) {
    echo '<div style="position: relative;  top: 0px; text-align: center; font-size: 15px; font-family: arial;">Oops, your photo!</div>';

    }
    }
    
    else{
        	echo '<div style="position: relative; top: 0px; text-align: center; font-size: 15px; font-family: arial;">Please login to vote</div>';

    }
       }

//RANKING
if($prevvotes >=1.0) {
	$display=($prevpoints/$prevvotes);	
	echo '<div style="position:relative; left: 30px; top: 0px;">
	<span style="font-size:16px;">Rank:&nbsp;&nbsp;&nbsp;</span><span style="font-size:30px;">',round($display, 1),'</span><span style="opacity: .6; font-size: 18px;">/10.0</span><br />';
    }
else  {
	echo '<div style="position:relative; left: 30px; top: 0px;"><span style="font-size:16px;">Rank:&nbsp;&nbsp;&nbsp;</span><span style="font-size:30px;">0.0</span><span style="opacity: .6; font-size: 18px;">/10.0</span><br />';
    }	
echo'
<br />
</div>';
?> 


<!--PREVIEWS-->
<div class="grid_4">
    <div style="float:left;">
    
    <!--elseif's not working inside one set of php tages here scott, so if's it is in separate tags-->
    
   <?php if($imageNextID != '') {echo'<a href="fullsizecampaign.php?id=',$imageNextID,'">';} ?>
   <?php if($imageThreeID == '') {echo'<a href="uploadcampaignphoto.php?id=',$campaign,'">';} ?>
   <img onmousedown="return false" oncontextmenu="return false;" class="preview" src="<?php echo $imageNext; ?>" height="200" width="210" /></a>
    
    <?php if($imageTwoID != '') {echo'<a href="fullsizecampaign.php?id=',$imageTwoID,'">';} ?>
    <?php if($imageThreeID == '') {echo'<a href="uploadcampaignphoto.php?id=',$campaign,'">';} ?>
    <img onmousedown="return false" oncontextmenu="return false;" class="preview" style="margin-top:5px;" src="<?php echo $imageTwo; ?>" height="200" width="210" /></a>
    
    <?php if($imageThreeID != '') {echo'<a href="fullsizecampaign.php?id=',$imageThreeID,'">';} ?>
    <?php if($imageThreeID == '') {echo'<a href="uploadcampaignphoto.php?id=',$campaign,'">';} ?>
    <img onmousedown="return false" oncontextmenu="return false;" class="preview" style="margin-top:5px;" src="<?php echo $imageThree; ?>" height="200" width="210" /></a>
    
    </div>
</div>

</div><!--end of 4 grid-->
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

</body>
</html>
      
       
        
    