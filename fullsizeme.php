<?php

//log them out if they try to logout
session_start();

if($_GET['action'] == "logout") {
	$_SESSION['loggedin'] = 0;
	session_destroy();
}

$myemail = $_SESSION['email'];

if($_SESSION['loggedin'] != 1) {
	header("Location: trending.php");
	exit();	
}

//connect to the database
require "db_connection.php";
require "functions.php";

$image = htmlentities($_GET['image']);

//add to the views column
$updatequery = mysql_query("UPDATE photos SET views=views+1 WHERE source='$image'") or die(mysql_error());

if($_GET['view'] == "saveinfo") {

		$newcaption = mysql_real_escape_string($_POST['caption']);
		$newlocation = mysql_real_escape_string($_POST['location']);
		$newcamera = mysql_real_escape_string($_POST['camera']);
        $newprice = mysql_real_escape_string($_POST['price']);
        if(!$newprice) {
        $newprice = $price;
        }
        $newfocallength = mysql_real_escape_string($_POST['focallength']);
        $newshutterspeed = $_POST['shutterspeed'];
        $newaperture = mysql_real_escape_string($_POST['aperture']);
        $newlens = mysql_real_escape_string($_POST['lens']);
        $newfilter = mysql_real_escape_string($_POST['filter']);
        $newcopyright = mysql_real_escape_string($_POST['copyright']);
        $newabout = mysql_real_escape_string($_POST['about']);


		//update the database with the new information
		$updatequery = "UPDATE photos SET caption='$newcaption', location='$newlocation', price='$newprice', camera='$newcamera', focallength='$newfocallength', shutterspeed='$newshutterspeed', aperture='$newaperture', lens='$newlens', filter='$newfilter', about='$newabout', copyright='$newcopyright' WHERE source='$image'";
		mysql_query($updatequery) or die(mysql_error());
}

//FIND THE PHOTO IN DATABASE
$image = $_GET['image'];
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
$imageID=$row['id'];
$price=mysql_result($result, 0, "price");
$camera = $row['camera'];
$faves= $row['faves'];
$exhibit = $row['set_id'];
$exhibitname = $row['sets'];
$views = $row['views'];
$focallength = $row['focallength'];
$shutterspeed = $row['shutterspeed'];
$aperture = $row['aperture'];
$lens = $row['lens'];
$filter = $row['filter'];
$copyright = $row['copyright'];
$about = $row['about'];

//find how many photos the photographer has
$numberofpics = mysql_query("SELECT * FROM photos WHERE emailaddress='$emailaddress'");
$numberofpics = mysql_num_rows($numberofpics);

if ($price == "") {$price='.50';}  

//check if the viewer is the same as the viewee
if($myemail != $emailaddress) {
	header("Location: myprofile.php");
}

//FIND THE PHOTOGRAPHER NAME IN DATABASE
$namequery="SELECT * FROM userinfo WHERE emailaddress='$emailaddress'";
$nameresult=mysql_query($namequery);
$row=mysql_fetch_array($nameresult);
$user=$row['user_id'];
$firstname=$row['firstname'];
$lastname=$row['lastname'];
$profilepic=$row['profilepic'];
$profilescore=$row['totalscore'];

//calculate the size of the picture
$maxwidth=850;
$maxheight=850;

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


//FIND THE NEXT FOUR PHOTOS TO BE DISPLAYED

$index = findPicView($emailaddress, $image);

$totalquery = mysql_query("SELECT * FROM photos WHERE emailaddress = '$myemail' ORDER BY id DESC");
$totalpics = mysql_num_rows($totalquery);

$imageBefore = @mysql_result($totalquery, $index-1, "source");
if(!$imageBefore) {
	$imageBefore = @mysql_result($totalquery, $totalpics-1, "source");
}

if($totalpics >= 5) {
	$imageOne = @mysql_result($totalquery, $index+1, "source");
	$imageTwo = @mysql_result($totalquery, $index+2, "source");
	$imageThree = @mysql_result($totalquery, $index+3, "source");
	$imageFour = @mysql_result($totalquery, $index+4, "source");
}
else if($totalpics == 4) {
	$imageOne = @mysql_result($totalquery, $index+1, "source");
	$imageTwo = @mysql_result($totalquery, $index+2, "source");
	$imageThree = @mysql_result($totalquery, $index+3, "source");
	$imageFour = "userphotos/watermarknew.png";
}
else if($totalpics == 3) {
	$imageOne = @mysql_result($totalquery, $index+1, "source");
	$imageTwo = @mysql_result($totalquery, $index+2, "source");
	$imageThree = "userphotos/watermarknew.png";
	$imageFour = "userphotos/watermarknew.png";
}
else if($totalpics == 2) {
	$imageOne = @mysql_result($totalquery, $index+1, "source");
	$imageTwo = "userphotos/watermarknew.png";
	$imageThree = "userphotos/watermarknew.png";
	$imageFour = "userphotos/watermarknew.png";
}
else if($totalpics == 1) {
	$imageOne = "userphotos/watermarknew.png";
	$imageTwo = "userphotos/watermarknew.png";
	$imageThree = "userphotos/watermarknew.png";
	$imageFour = "userphotos/watermarknew.png";
}

$set = 0;
if(!$imageOne) {
	$imageOne = @mysql_result($totalquery, 0, "source");
	$set++;
}
if(!$imageTwo) {
	$imageTwo = @mysql_result($totalquery, $set, "source");
	$set++;
}
if(!$imageThree) {
	$imageThree = @mysql_result($totalquery, $set, "source");
	$set++;
}
if(!$imageFour) {
	$imageFour = @mysql_result($totalquery, $set, "source");
	$set++;
}
		
$imageOneThumb = str_replace("userphotos/","userphotos/thumbs/", $imageOne);
$imageTwoThumb = str_replace("userphotos/","userphotos/thumbs/", $imageTwo);
$imageThreeThumb = str_replace("userphotos/","userphotos/thumbs/", $imageThree);
$imageFourThumb = str_replace("userphotos/","userphotos/thumbs/", $imageFour);		
$imageFiveThumb = str_replace("userphotos/","userphotos/thumbs/", $imageFive);



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
			echo '<div style="position:absolute;  top:65px; left:765px; font-family: lucida grande, georgia; color:black; font-size:20px;">This photo is already in your favorites!</div>';
		}
		else {
			$favesquery="UPDATE userinfo SET faves=CONCAT(faves,'$queryimage') WHERE emailaddress='$vieweremail'";
			mysql_query($favesquery);
			mysql_query("UPDATE photos SET faves=faves+1 WHERE source='$image'");
			echo '<div style="position:absolute; top:65px; left:765px; font-family: lucida grande, georgia; color:black; font-size:20px;">This photo has been added to your favorites!</div>';
            
            //MAIL PHOTOGRAPHER NOTICE THAT THEIR PHOTO HAS BEEN FAVORITED
            
    //GRAB SETTINGS LIST
$settingemail = $_SESSION['email'];
$settingquery = "SELECT * FROM userinfo WHERE emailaddress = '$settingemail'";
$settingqueryrun = mysql_query($settingquery);
$settinglist = mysql_result($settingqueryrun, 0, "settings");
        
$setting_string = $settinglist;
$find = "emailfave";
$foundsetting = strpos($setting_string,$find);
          
            
            $to = $emailaddress;
          $subject = $viewerfirst . " " . $viewerlast . " favorited one of your photos on PhotoRankr";
          $favemessage = $viewerfirst . " " . $viewerlast . " favorited one of your photos on PhotoRankr
        
To view the photo, click here: http://www.photorankr.com/fullsize.php?image=".$imagelink2;
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

//QUERY FOR NOTIFICATIONS
$currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$emailaddress'";
$currentnotsquery = mysql_query($currentnots);
$currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");




//Grab Overall Portfolio Ranking
    $userphotos="SELECT * FROM photos WHERE emailaddress = '$myemail'";
    $userphotosquery=mysql_query($userphotos);
    $numphotos=mysql_num_rows($userphotosquery);
    
    for($iii = 0; $iii < $numphotos; $iii++) {
		$points = mysql_result($userphotosquery, $iii, "points");
        $votes = mysql_result($userphotosquery, $iii, "votes");
        $totalfaves = mysql_result($userphotosquery, $iii, "faves");
        $portfoliopoints+=$points;
        $portfoliovotes+=$votes;
        $portfoliofaves+=$totalfaves;
        }
    
    if ($portfoliovotes > 0) {
    $portfolioranking=($portfoliopoints/$portfoliovotes);
    $portfolioranking=number_format($portfolioranking, 2, '.', '');
    
    $scorequery = "UPDATE userinfo SET totalscore = '$portfoliopoints' WHERE emailaddress = '$myemail'";    
    $scoreresult = mysql_query($scorequery);
    
    }
    
    else if ($portfoliovotes < 1) {
    $portfolioranking="N/A";
    }	
?>


<?php

if($_GET['action'] == "delete") {
		mysql_query("DELETE FROM photos WHERE source='$image'") or die(mysql_error());
		echo '<div style="position:absolute; top:70px; left:350px; font-family: lucida grande, georgia; color:black; font-size:17px; z-index:72983475273459273458972349587293745;">This photo is now being deleted. It will no longer appear on the site or in your profile. Thank you.</div>';
}


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
  <title>Edit Photo "<?php echo $caption; ?>" On PhotoRankr</title>
  <meta name="description" content="View photos on PhotoRankr, the number one site for photographers. Photos aren't cropped, but instead made to look good. You can buy and sell photos through PhotoRankr, as well. These photos are your own." />
  <link rel="stylesheet" type="text/css" href="bootstrapnew.css" />
  <link rel="stylesheet" href="reset.css" type="text/css" />
  <link rel="stylesheet" href="text.css" type="text/css" />
  <link rel="stylesheet" href="newfullsize.css" type="text/css" />
  <link rel="stylesheet" href="960_24.css" type="text/css" />
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

     
</head>


<body class="background" style="overflow-x: hidden;min-width:1220px;">


 <!--NAVIGATION BAR-->
<div class="navbar" style="z-index:10;min-width:1220px;padding-top:0px;font-size:16px;width:100%;">
	<div class="navbar-inner">
		<div class="container">
			    <ul class="nav">
					<li><a style="color:#fff;" class="brand" href="index.php"><div style="margin-top:-2px"><img src="logo.png" width="160" /></div></a></li>
                    
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

                    
					 <li style="background-color:#587fa2;" class="dropdown">
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

if($_SESSION['loggedin'] == 0) {
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
							<li><a style="color:#fff;" href="signin.php">Register Now</a></li>
							<li><br/></li>
							<form name="login_form" method="post" action="newest.php?action=login">
							<li style="margin-left: 5px; margin-right: 5px; width: 200px;"><span style="color: white; margin-bottom: 5px;">Email: </span><input type="text" style="width:100px; margin-left: 40px;" name="emailaddress" /></li>
							<li style="margin-left: 10px;"><span style="color: white">Password: </span>&nbsp<input type="password" style="width:100px; margin-left: 1px;" name="password"/></li>
							<li style="margin-left: 70px;"><input type="submit" value="sign in" id="loginButton"/></li>
							</form>
						</ul>';
} ?>
					</li>
					<form class="navbar-search" action="search.php" method="get">
						<input type="text" style="width:150px;border-color:#fff;background-color:#fff;margin-left:20px;" class="search-query" name="searchterm" placeholder="Search">
					 </form>
					 
				</ul>
			
		</div> <!--/end boostrap divs navbar-->
        
    </div>
</div>

    
<div class="container_24"><!--Grid container begin-->

<!--TITLE OF PHOTO-->     
<div class="grid_24">
<div class="grid_15 pull_2"><div style="margin-top:60px;padding-top:5px;padding-left:3px;line-height:30px;font-size:30px;
"><?php echo '(Your Portfolio) <span style="font-size:23px;">"',$caption,'"</span>'; ?>
</div></div></div>

<!--BIG IMAGE BOX-->
<div class="grid_24">

<div class="grid_15 pull_2" style="margin-top:150px;">

<div class="phototitle" style="height:<?php echo $newheight; ?>px;width:<?php echo $newwidth; ?>px;margin-top:-135px;">
 <a href="http://photorankr.com/<?php echo $image; ?>"  rel="shadowbox;height=<?php echo $height/1.35; ?>;width=<?php echo $width/1.35; ?>" title='"<?php echo $caption; ?>" by: <?php echo $firstname; ?> <?php echo $lastname; ?> (click and drag to scroll)'><img alt="<?php echo $tags; ?>" src="<?php echo $image; ?>" height="<?php echo $newheight; ?>px" width="<?php echo $newwidth; ?>px" /></a></div>
  
</div> 

<!--PHOTOGRAPHER INFORMATION BOX-->    
<div class="grid_4 push_6 photographerinfo" style="width:218px;padding:2px;">
<a href="viewprofile.php?u=<?php echo $user; ?>" style="font-size: 15px; text-decoration:none;">
<div style="position: relative; left: 10px; top: 10px;"><img alt="PhotoRankr is the only place that puts the photographer first" style="border: 1px solid black" src="<?php echo $profilepic; ?>" 
height="80px" width="80px" />
</div> 
<div style="position: relative; left: 100px; top: -67px;">
<?php 
if(strlen($firstname) > 8) {
echo $firstname, '<br/>', $lastname; 

}
else {
echo $firstname, ' ', $lastname; 
}
?></a>
</div>
<a href="myprofile.php">
<span style="position: relative; left: 102px;top:-50px;"><button class="btn btn-primary" style="height: 28px; width: 105px; font-family: arial;font-size:13px;" type="button">MY PROFILE</button></span></a>


<!--Edit Photo Modal-->
<div class="modal hide fade" id="editphoto" style="overflow-y:scroll;overflow-x:hidden;">
<?php

echo'
<div class="modal-header">
<a style="float:right" class="btn btn-primary" data-dismiss="modal">Close</a>
<img style="margin-top:-4px;" src="graphics/logoteal.png" height="28" width="90" />&nbsp;&nbsp;<span style="font-size:16px;">Edit your photo\'s information below:</span>
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:550px;height:500px;overflow-x:hidden;">
		
<img style="border: 1px solid black;margin-left:10px;margin-top:10px;" src="',$image,'" 
height="100px" width="100px" />

<div style="width:550px;height:680px;margin-left:130px;margin-top:-100px;overflow-y:scroll;overflow-x:hidden;">

<form action="fullsizeme.php?image=',$image,'&view=saveinfo" method="post" enctype="multipart/form-data">
    Basic Information:
    <br />
    <br />
    <span style="font-size:14px;">
    Caption:&nbsp;&nbsp; <input name="caption" value="',$caption,'">
    <br />
    Camera:&nbsp;&nbsp;&nbsp;<input name="camera" value="',$camera,'">
    <br />
    Location:&nbsp;&nbsp;<input type="location" name="location" value="',$location,'">
    <br />
    Current Price:';
    ?>
    
    <?php if($price != 'Not For Sale') {echo'$';} ?>
    
    <?php echo $price; ?>
    
    <?php
    echo'
    <br />
    Change Price:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="price" style="margin-top:5px;">
    <option value="',$price,'">Choose a Price:</option>
    <option value=".00">Free</option>
	<option value=".50">$.50</option>
	<option value=".75">$.75</option>
	<option value="1.00">$1.00</option>
	<option value="2.00">$2.00</option>
	<option value="5.00">$5.00</option>
	<option value="10.00">$10.00</option>
    <option value="15.00">$15.00</option>
    <option value="25.00">$25.00</option>
    <option value="50.00">$50.00</option>
    <option value="100.00">$100.00</option>
    <option value="200.00">$200.00</option>
    <option value="Not For Sale">Not For Sale</option>
	</select>
    </span>
    <br />
    <br />
    Advanced Information:
    <br />
    <br />
    <span style="font-size:14px;">
    Focal Length:&nbsp;&nbsp;&nbsp;<input name="focallength" value="',$focallength,'">
    <br />
    Shutter Speed:&nbsp;<input name="shutterspeed" value="',$shutterspeed,'">
    <br />
    Aperture:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="aperture" value="',$aperture,'">
    <br />
    Lens:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="lens" value="',$lens,'">
    <br />
    Filter:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="filter" value="',$filter,'">
    <br />
    Copyright:&nbsp;&nbsp;
    <input type="radio" name="copyright" value="owner"/> ',$firstname,' ',$lastname,'&nbsp;&nbsp;&nbsp;
    <input type="radio" name="copyright" value="cc" /> Creative Commons<br />
    <br />
    About Photo:&nbsp;
    <br />
    <textarea style="width:380px" rows="4" cols="60" name="about"></textarea>
    <br />
    </span>
    <button class="btn btn-success" type="submit">Save Info</button>
    </form>
     <a style="position: relative; top: -28px; left: 280px;" href="http://photorankr.com/fullsizeme.php?image=', $image, '&action=delete"><button class="btn btn-danger">DELETE PHOTO</button></a>
    <br />

</div>
</div>
</div>';
    
?>

</div>


<div style="text-align:center;font-size:13px;margin-top:-25px;">
<a data-toggle="modal" data-backdrop="static" href="#editphoto">
<button class="btn btn-warning" style="width:185px;">EDIT PHOTO</button>
</a>
</div>
<br />
</div>

<!--ARROWS-->
<div class="grid_4 push_6 arrows" style="width:218px;padding:2px;">
<span style="margin-left:30px;"><a style="text-decoration:none;" href="fullsizeme.php?image=<?php echo $imageBefore; ?>&v=<?php echo $view; ?>"><img src="graphics/arrow left.png" alt="Scroll through photos in their full size glory"
height="55" width="40"/></a></span>

<span style="margin-left:75px;"><a style="text-decoration:none;" href="fullsizeme.php?image=<?php echo $imageOne; ?>&v=<?php echo $view; ?>"><img  src="graphics/arrow right.png" alt="Scroll through photos in their full size glory"
height="55" width="40"/></a></span>

</div>

    
<!--PHOTO INFORMATION BOX-->    
<div class="grid_4 push_6 photoinfo" style="padding:2px;width:218px;">
 <br />
 
 
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
		
        
        if($ultimatereputationme > 70 && $ultimatereputationme < 100)
        {
        $prevpoints+=($ranking*2.5);
		$prevvotes+=2.5;
		$rankquery="UPDATE photos SET points='$prevpoints', votes='$prevvotes' WHERE source='$image'";
		mysql_query($rankquery); 
        }
        
        elseif($ultimatereputationme > 50 && $ultimatereputationme < 70)
        {
        $prevpoints+=($ranking*2.0);
		$prevvotes+=2;
		$rankquery="UPDATE photos SET points='$prevpoints', votes='$prevvotes' WHERE source='$image'";
		mysql_query($rankquery); 
        }
        
        elseif($ultimatereputationme > 30 && $ultimatereputationme < 50)
        {
        $prevpoints+=($ranking*1.5);
		$prevvotes+=1.5;
		$rankquery="UPDATE photos SET points='$prevpoints', votes='$prevvotes' WHERE source='$image'";
		mysql_query($rankquery); 
        }
        
        elseif($ultimatereputationme > 0 && $ultimatereputationme < 30)
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

?>
 
 
 <?php
 if(!$ranking) {
	echo '<div style="position: relative; left: -10px; top: 0px; text-align: center; font-size: 15px; font-family: arial;';
	if($ranking) {echo 'margin-top: 10px;';}
	echo '">
	<form action="', htmlentities($_SERVER['PHP_SELF']), '?image=', $image, '&v=', $view, '" method="post">
	<select name="ranking" style="width:60px; height:25px;margin-left:15px;">
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
    &nbsp;
	<button class="btn btn-success" type="submit" style="width:85px; height: 28px; margin-top:-6px; font-family: arial; font-size:14px;">RANK</button>
	</form></div>';
}

if($prevvotes >=1.0) {
	$display=($prevpoints/$prevvotes);	
	echo '<div style="position:relative; left: 30px; top: 0px;">
	<span style="font-size:16px;">Rank:&nbsp;&nbsp;&nbsp;</span><span style="font-size:30px;">',round($display, 1),'</span><span style="opacity: .6; font-size: 18px;">/10.0</span><br />
    <span style="font-size:16px;">Favorites:</span><span style="font-size:30px;margin-left:10px;">',$faves,'</span>
    <br />
    <span style="font-size:16px;">Views:</span><span style="font-size:30px;margin-left:10px;">',$views,'</span></div>
    ';
}
else  {
	echo '<div style="position:relative; left: 30px; top: 0px;"><span style="font-size:16px;">Rank:&nbsp;&nbsp;&nbsp;</span><span style="font-size:30px;">0.0</span><span style="opacity: .6; font-size: 18px;">/10.0</span><br />
    <span style="font-size:16px;">Favorites:</span><span style="font-size:30px;margin-left:10px;">',$faves,'</span>
    <br />
    <span style="font-size:16px;">Views:</span><span style="font-size:30px;margin-left:10px;">',$views,'</span></div>';
}	

?> 



<br />
<!--SOCIAL MEDIA BUTTONS-->
<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style addthis_32x32_style" style="margin-left:5px;margin-top:-20px;">
<a class="addthis_button_preferred_1"></a>
<a class="addthis_button_preferred_2"></a>
<a class="addthis_button_preferred_3"></a>
<a class="addthis_button_compact"></a>
<a class="addthis_counter addthis_bubble_style"></a>
</div>
<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4fce0eeb4e8937f9"></script>
<!-- AddThis Button END -->

</div>

</div><!--end of 24 grid-->






<!--PREVIEWS BOX-->
<div class="grid_15 alpha pull_1 viewer" >


<?php
//in here we will display the arrows and previews

echo '<span style="position: relative; top: 0px; left: 0px;"><a href="fullsizeme.php?image=',$imageOne,'&v=',$view,'"><img class="previews" alt="Preview some of the best photos on the web." src= "http://photorankr.com/', $imageOneThumb, '" height="150" width="190"/></a></span>

<span style="position: relative; top: 0px; left: 0px;"><a href="fullsizeme.php?image=',$imageTwo,'&v=',$view,'"><img class="previews" alt="These previews allow you to see what is coming up next" src= "http://photorankr.com/', $imageTwoThumb, '" height="150" width="190"/></a></span>

<span style="position: relative; top: 0px; left: 0px;"><a href="fullsizeme.php?image=',$imageThree,'&v=',$view,'"><img class="previews" alt="Scroll through photos to find photos to buy" src= "http://photorankr.com/', $imageThreeThumb,'" height="150" width="190"/></a></span>';

?>


</div>

<!--COMMENTS BOX-->    
<div class="grid_15 alpha pull_1 comments" style="padding:2px;">
<div style="position:relative;width:579px;padding-bottom:1px;
background-color:#DCE1E6;font-size:15px;">
            
<?php

$useremail=$_SESSION['email'];
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
    $sql = "SELECT * FROM userinfo WHERE emailaddress = '$useremail'";
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
    if ($yoemail != $emailaddress && $yoemail != $useremail && !$found)
    {
    
//GRAB SETTINGS LIST
$settingemail = $_SESSION['email'];
$settingquery = "SELECT * FROM userinfo WHERE emailaddress = '$settingemail'";
$settingqueryrun = mysql_query($settingquery);
$settinglist = mysql_result($settingqueryrun, 0, "settings");
        
$setting_string = $settinglist;
$find = "emailreturncomment";
$foundsetting = strpos($setting_string,$find);

          $to = $yoemail;
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
	$sql = "SELECT * FROM userinfo WHERE emailaddress = '$useremail'";
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
                    style="width:' . $ultimatereputationme
                     . '%;"></div>
                    </div>' .stripslashes($message). "<hr />";
                  
                  
                  
                  
                  
                                      
                  
    //Write to the file
    @chmod($file,0777);
    fwrite($fp, $outputstring, strlen($outputstring));
    @include("$file");
        $type3 = "comment";
        $newsfeedcommentquery="INSERT INTO newsfeed (firstname, lastname, emailaddress,owner,type,source) VALUES ('$userfirst', '$userlast', '$useremail','$emailaddress','$type3','$image')";
        $commentnewsquery = mysql_query($newsfeedcommentquery);
        
        //notifications query     
$notsquery = "UPDATE userinfo SET notifications = (notifications + 1) WHERE emailaddress = '$emailaddress'";
$notsqueryrun = mysql_query($notsquery);  
   
                
    //MAIL EMAIL TO PHOTOGRAPHER WHOSE PHOTO IS BEING COMMENTED UPON  
    if ($emailaddress != $useremail) {
    

//GRAB SETTINGS LIST
$settingemail = $_SESSION['email'];
$settingquery = "SELECT * FROM userinfo WHERE emailaddress = '$settingemail'";
$settingqueryrun = mysql_query($settingquery);
$settinglist = mysql_result($settingqueryrun, 0, "settings");
        
$setting_string = $settinglist;
$find = "emailcomment";
$foundsetting = strpos($setting_string,$find);

    
        $to = $emailaddress;
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

    echo '<form action="http://photorankr.com/fullsizeme.php?image=', $image, '&v=', $view, '&action=comment" method="post">';

      
    echo '<table>
        <tr>
            <!--<td>Name:</td>
            <td><input type="text" name="name" font-size:17px; border-radius:15px; outline:none;" value=""></input></td>-->
        </tr>
        <tr>
            
            <td><textarea  style="margin-left:8px; margin-top:8px; width:560px; height: 100px;" cols="60" rows="2" name="message" outline:none; border-radius:3px"></textarea></td>
        </tr>
    </table>
    <input type="hidden" name="act" value="post"></input>
    <button type="submit" class="btn btn-success" name="submit" value="Submit">Submit</button>
</form>';
    echo '</div>';

}

echo '<META HTTP-EQUIV="Refresh" Content="0; URL=fullsizeme.php?image=', $image, '&v=', $view, '">';
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
echo '<div style="margin-left: 10px;font-size:15px;">';
@include("$file"); 
echo '</div>';
if (@file_get_contents($file) == '') {
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
<form action="http://photorankr.com/fullsizeme.php?image=', $image, '&v=', $view, '&action=comment" method="post">
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
<p style="margin-left: 5px; margin-bottom: 15px;font-size:16px;">Please sign in above to comment...</p></div>';
}

?>
<?php
}
?>

</div>
</div>

<!--EXTRA PHOTO INFO BOX-->
<div class="grid_4 pull_1 alpha extraphotoinfo" style="width:213px;padding:2px;">

<div style="font-size:16px;padding:4px;">About This Photo:</div>

<!--ADDITIONAL PHOTO INFO--> 
<div style="padding: 5px 5px 5px 5px">
            <div style="font-size:13px">

        <span style="opacity: .8">Camera: </span><?php echo $camera; ?> <br />
        <span style="opacity: .8">Location: </span><?php echo $location; ?> <br />

<?php

        if ($exhibit) {
        echo'
        <span style="opacity: .8">Exhibit: </span><a style="text-decoration:none" href="viewprofile.php?u=',$user,'&ex=y&set=',$exhibit,'">"',$exhibitname,'"</a><br />';
        }
        
        if ($focallength) {
        echo'
        <span style="opacity: .8">Focal Length: </span>',$focallength,' mm <br />';
        }
        
        if ($shutterspeed) {
        echo'
        <span style="opacity: .8">Shutter Speed: </span>',$shutterspeed,' sec <br />';
        }
               if ($aperture) {
        echo'
        <span style="opacity: .8">Aperture: </span>f/',$aperture,'<br />';
        }
        
        if ($lens) {
        echo'
        <span style="opacity: .8">Lens: </span>',$lens,'<br />';
        }
        
        if ($filter) {
        echo'
        <span style="opacity: .8">Filter: </span>',$filter,' <br />';
        }
        
        if ($about) {
        echo'
        <span style="opacity: .8">About this Photo: </span><span style="padding: 5px;">',$about,'</span><br />';
        }
        
        if($copyright == 'owner') {
        $copyright = $firstname . ' ' . $lastname;
        }
        
        elseif($copyright == 'cc') {
        $copyright = "Creative Commons";
        }
        
        elseif($copyright == '') {
        $copyright = $firstname . ' ' . $lastname;
        }
        
        if ($price == '.00') {
        echo'
        <span style="opacity: .8">Price: </span><span style="padding: 5px;">Free</span><br />';
        }
        
        elseif ($price == 'Not For Sale') {
        echo'
        <span style="opacity: .8">Price: </span><span style="padding: 5px;">Not For Sale</span><br />';
        }
        
        else {
        echo'
        <span style="opacity: .8">Price: $</span><span style="padding: 5px;">',$price,'</span><br />';
        }
       
       echo'
        <span style="opacity: .8">Copyright: </span>&#169; ',$copyright,'';

?>

        </div>
        </div>
 <br />
 
</div>


<div class="container_24">

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

              </div>  
            </div>            
    </body>
</html>
      
       
        
    