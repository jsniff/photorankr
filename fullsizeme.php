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

    $myemail = $_SESSION['email'];
    
    $findreputationme = mysql_query("SELECT reputation,profilepic,firstname,lastname FROM userinfo WHERE emailaddress = '$myemail'");
    $reputationme = number_format(mysql_result($findreputationme,0,'reputation'),2);
    $sessionpic = mysql_result($findreputationme,0,'profilepic');
    $sessionfirst =  mysql_result($findreputationme,0,'firstname');
    $sessionlast =  mysql_result($findreputationme,0,'lastname');
    $sessionname = mysql_result($findreputationme,0,'firstname') ." ". mysql_result($findreputationme,0,'lastname');
    
    
    //GET THE IMAGE
$image=addslashes($_GET['image']);

//add to the views column
$updatequery = mysql_query("UPDATE photos SET views=views+1 WHERE source='$image'") or die(mysql_error());

if($_GET['view'] == "saveinfo") {

		$newcaption = mysql_real_escape_string($_POST['caption']);
		$newlocation = mysql_real_escape_string($_POST['location']);
		$newcamera = mysql_real_escape_string($_POST['camera']);
        $newprice = mysql_real_escape_string($_POST['price']);
        $newfocallength = mysql_real_escape_string($_POST['focallength']);
        $newshutterspeed = $_POST['shutterspeed'];
        $newaperture = mysql_real_escape_string($_POST['aperture']);
        $newlens = mysql_real_escape_string($_POST['lens']);
        $newfilter = mysql_real_escape_string($_POST['filter']);
        $newcopyright = mysql_real_escape_string($_POST['copyright']);
        $newabout = mysql_real_escape_string($_POST['about']);
        $newtag1 = mysql_real_escape_string($_POST['tag1']);
        $newtag2 = mysql_real_escape_string($_POST['tag2']);
        $newtag3 = mysql_real_escape_string($_POST['tag3']);

		//update the database with the new information
		$updatequery = "UPDATE photos SET caption='$newcaption', location='$newlocation', price='$newprice', tag1 = '$newtag1', tag2 = '$newtag2', tag3 = '$newtag3', camera='$newcamera', focallength='$newfocallength', shutterspeed='$newshutterspeed', aperture='$newaperture', lens='$newlens', filter='$newfilter', about='$newabout', copyright='$newcopyright' WHERE source='$image'";
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
$changeprice = mysql_result($result, 0, "price");
$sold=mysql_result($result, 0, "sold");
$camera = $row['camera'];
if($camera) {
$camera = '<a style="color:black;" href="search.php?searchterm='.$camera.'">' . $camera . '</a>';
}
$faves= $row['faves'];
$exhibit = $row['set_id'];

$exquery = mysql_query("SELECT source FROM photos WHERE set_id = '$exhibit' AND emailaddress = '$myemail' ORDER BY (points/votes) DESC LIMIT 0,3");
$expic1 = mysql_result($exquery,0,'source');
$exthumb1 = str_replace("userphotos/","userphotos/medthumbs/",$expic1);
$expic2 = mysql_result($exquery,1,'source');
$exthumb2 = str_replace("userphotos/","userphotos/medthumbs/",$expic2);
$expic3 = mysql_result($exquery,2,'source');
$exthumb3 = str_replace("userphotos/","userphotos/medthumbs/",$expic3);

$exhibitname = $row['sets'];
$views = $row['views'];
$focallength = $row['focallength'];
$shutterspeed = $row['shutterspeed'];
$aperture = $row['aperture'];
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
    $singlestyletagsfinal = $singlestyletagsfinal . '<a style="color:black;" href="search.php?searchterm='.$singlestyletagsarray[$iii].'">' . $singlestyletagsarray[$iii] . '</a>' . ", "; }
    }
    for($iii=0; $iii < count($singlecategorytagsarray); $iii++) {
        if($singlecategorytagsarray[$iii] != '') {
        $singlecategorytagsfinal = $singlecategorytagsfinal . '<a style="color:black;" href="search.php?searchterm='.$singlecategorytagsarray[$iii].'">' . $singlecategorytagsarray[$iii] . '</a>' . ", "; }
    }
    
$keywords = $tag1 . $tag2 . $tag3 . $tag4 . $singlestyletagsfinal . $singlecategorytagsfinal;
$keywords = substr_replace($keywords ," ",-2);


//find how many photos the photographer has
$numberofpics = mysql_query("SELECT * FROM photos WHERE emailaddress='$emailaddress'");
$numberofpics = mysql_num_rows($numberofpics);

if ($price == "0.00") {$price='Free';}  
elseif ($price == "Not For Sale") {$price='NFS';}  
elseif ($price == "") {$price='';} 
else {$price = '$' . $price; }

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
$fullname = $firstname ." ". $lastname;
$profilepic=$row['profilepic'];
$profilescore=$row['totalscore'];
$promos = mysql_result($nameresult,0,'promos');

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
        
To view the photo, click here: http://www.photorankr.com/fullsizeme.php?image=".$imagelink2;
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
  
  
  //COMMENT QUERIES

if(htmlentities($_POST['comment']) && $_SESSION['loggedin'] == 1) {
    
    $currenttime = time();
    $comment = mysql_real_escape_string(htmlentities($_POST['comment']));
    $insertcomment = mysql_query("INSERT INTO comments (comment,commenter,photoowner,imageid,time) VALUES ('$comment','$myemail','$myemail','$imageID','$currenttime')");
    
    
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
            $subject = $sessionname . " also commented on " . $firstname . " " . $lastname ."'s photo on PhotoRankr";
            
            $headers = 'From:PhotoRankr <photorankr@photorankr.com>';
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n"; 
            
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
        $newsfeedcomment = mysql_query("INSERT INTO newsfeed (firstname, lastname, emailaddress,owner,type,source) VALUES ('$sessionfirst', '$sessionlast', '$myemail','$myemail','$type','$image')") or die();
            
    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=fullsizeme.php?image=', $image, '&v=', $view, '">';
	exit();

}

if(htmlentities($_GET['action']) == 'deletecomment' && $_SESSION['loggedin'] == 1) {
    
    $commentid = htmlentities($_GET['cid']);
    $deletecomment = mysql_query("DELETE FROM comments WHERE id = '$commentid'");

}

  
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Edit Photo "<?php echo $caption; ?>" | PhotoRankr</title>

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

<div style="width:550px;height:700px;margin-left:130px;margin-top:-100px;overflow-x:hidden;padding-bottom:80px;">

<form action="fullsizeme.php?image=',$image,'&view=saveinfo" method="post" enctype="multipart/form-data">
    Basic Information:
    <br />
    <br />
    <span style="font-size:14px;">
    Caption:&nbsp;&nbsp; <input name="caption" value="',$caption,'">
    <br /><br />
    Camera:&nbsp;&nbsp;&nbsp;<input name="camera" value="',$camera,'">
    <br /><br />
    Location:&nbsp;&nbsp;<input type="location" name="location" value="',$location,'">
    <br /><br />
    Current Price:&nbsp;&nbsp;&nbsp;';
    ?>
            
    <span style="font-size:16px;"><?php echo $price; ?></span>
    
    <?php
    echo'
    <br /><br />
    Change Price:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="price" style="margin-top:5px;">
    <option value="',$changeprice,'">Choose a Price:</option>
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
    <br /><br />
    Shutter Speed:&nbsp;<input name="shutterspeed" value="',$shutterspeed,'">
    <br /><br />
    Aperture:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="aperture" value="',$aperture,'">
    <br /><br />
    Lens:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="lens" value="',$lens,'">
    <br /><br />
    
Filter:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="lens" value="',$lens,'">
    <br /><br />
    Keywords:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input style="width:80px;" name="tag1" value="',$tagbox1,'">&nbsp;&nbsp;<input style="width:80px;" name="tag2" value="',$tagbox2,'">&nbsp;&nbsp;<input style="width:80px;" name="tag3" value="',$tagbox3,'">

    <br /><br />
    Copyright:&nbsp;&nbsp;
    <input type="radio" name="copyright" value="owner"/> ',$firstname,' ',$lastname,'&nbsp;&nbsp;&nbsp;
    <input type="radio" name="copyright" value="cc" /> Creative Commons<br />
    <br /><br />
    About this Photo:&nbsp;
    <br /><br />
    <textarea style="width:380px" rows="4" cols="60" name="about">',$about,'</textarea>
    <br />
    </span>
    <button class="btn btn-success" type="submit">Save Info</button>
    </form>
     <a style="position: relative; top: -28px; left: 280px;" href="http://photorankr.com/fullsizeme.php?image=', $image, '&action=delete"><button class="btn btn-danger">Delete Photo</button></a>

</div>
</div>
</div>';
    
?>

</div>



<body id="body" style="background-color:rgb(245,245,245);min-width:1220px;">

<?php navbarnew(); ?>

<!--Here the Grid Container Begins-->
<div class="container_24 container-margin" style="margin-top:70px;">
<div class="grid_15 pull_2">	
	<div class="grid_14 pull_1" style="float:left;">
		<h1 style="font-size:22px;padding-bottom:15px;font-weight:200;"> <?php echo $caption ?> </h1>
	</div>	
	<div class="grid_21 pull_1" style="float:left;" >
	<img onmousedown="return false" oncontextmenu="return false;" src="<?php echo $image; ?>" class="image" alt=<?php echo $caption; ?>" height="<?php echo $newheight; ?>px" width="<?php echo $newwidth; ?>px" />	
    
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
                    <input style="float:left;width:495px;height:20px;position:relative;top:10px;" type="text" name="comment" placeholder="Reply to your feedback&#8230;" />
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
            <div class="grid_16" style="width:610px;margin-top:20px;">
            <a href="viewprofile.php?u=',$commenterid,'"><div style="float:left;"><img class="roundedall" src="',$commenterpic,'" alt="',$commentername,'" height="40" width="35"/></a></div>
            <div style="float:left;padding-left:6px;width:560px;">
                <div style="float:left;color:#3e608c;font-size:14px;font-family:helvetica;font-weight:500;border-bottom: 1px solid #ccc;width:560px;"><div style="float:left;"><a href="viewprofile.php?u=',$commenterid,'">',$commentername,'</a> &nbsp;<span style="font-size:16px;font-weight:100;color:black;margin-top:2">|</span>&nbsp;<span style="color:#333;font-size:12px;">Rep: ',$commenterrep,'</span></div>&nbsp;&nbsp;&nbsp;
                    <div class="progress progress-success" style="float:left;width:110px;height:7px;opacity:.8;margin:7px;">
                    <div class="bar" style="width:',$commenterrep,'%;">
                    </div></div>';
                 if($email == $emailaddress) {
                    echo'
                        <div style="float:right;font-size:12px;font-weight:500;"><a style="color#ccc;text-decoration:none;" href="fullsizeme.php?image=',$image,'&action=deletecomment&cid=',$commentid,'">X</a></div>';
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


<!--PHOTOGRAPHER ID-->

		<div class="grid_7 push_4" style="margin-top:50px;">
			<div class="grid_7 box"> <!--ID Tag-->
				<div style="height:120px;">
					<div class="roundedall" style="float:left;overflow:hidden;margin-left:5px;margin-top:5px;">
					<img src="<?php echo $profilepic; ?>" alt="<?php echo $fullname; ?>" height="95" width="95" />
				</div>

			<div id="namewrap">
				<h1 id="name" style="top:5px;"> <?php echo $fullname; ?> </h1>
				<div class="progress progress-success" style="width:110px;height: 10px;margin-top:2px;">
                <div class="bar" style="width:<?php echo $reputationme; ?>%;"> 
                </div></div>

				<h1 id="rep" style="margin-top:-20px;"> Rep: &nbsp <?php echo $reputationme; ?> </h1>
			</div>	
            
            <?php
                
                if($reputationme > 60) {
                    echo'<img style="margin-top:-25px;margin-left:-30px;" src="graphics/toplens.png" height="75" />';
                }
            
            ?>
            
		</div>
	
            


			</div>
            
			<div class="grid_7" style="text-align:center;"><!--Rank and stats-->
				<div class="grid_7" style="padding-top:7px;">
				
                        <a class="btn btn-warning" style="width:260px;padding:10px;font-size:16px;" data-toggle="modal" data-backdrop="static" href="#editphoto">Edit Photo</a>
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
					<li> <img docgraphics/heart_dark.png"/> <span id="stat"> Faves: </span> <span class="numbers"><?php echo $faves; ?></span> </li>
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
                    
                    if($view == '') {
                    echo'<span style="font-family:helvetica;font-weight:100;font-size:14px;">Browse More of Your Portfolio:</span>';
                    }
                    
            ?>
				
				<div id="images" style="margin-top:5px;">
					<a href="fullsizeme.php?image=<?php echo $imageOne; ?>&v=<?php echo $view; ?>"><img src="<?php echo $imageOneThumb; ?>" id="nextimg1"/></a>
				</div>
				<div class="nextimg">
					<a href="fullsizeme.php?image=<?php echo $imageTwo; ?>&v=<?php echo $view; ?>"><img src="<?php echo $imageTwoThumb; ?>" id="nextimg2"/></a>
				</div>
				<div class="nextimg">	
					<a href="fullsizeme.php?image=<?php echo $imageOne; ?>&v=<?php echo $view; ?>"><img src="<?php echo $imageThreeThumb; ?>"id="nextimg3"/></a>
				</div>
				<a style="text-decoration:none;" href="fullsizeme.php?image=<?php echo $imageBefore; ?>&v=<?php echo $view; ?>"><div class="grid_1" id="hover_arrow_left">
				</div></a>
					<a style="text-decoration:none;" href="fullsizeme.php?image=<?php echo $imageOne; ?>&v=<?php echo $view; ?>"><div class="grid_1" id="hover_arrow_right">
				</div></a>
				</div>
			
	<?php		      
                //CHECK FOR OPT-IN
                
             if($promos == 'optin') {
                
			echo'
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
                        <a href="fullsizeme.php?image=',$expic1,'"><img style="float:left;padding:2px;" src="',$exthumb1,'" height="80" width="80" /></a> 
                        <a href="fullsizeme.php?image=',$expic2,'"><img style="float:left;padding:2px;" src="',$exthumb2,'" height="80" width="80" /></a> 
                        <a href="fullsizeme.php?image=',$expic3,'"><img style="float:left;padding:2px;" src="',$exthumb3,'" height="80" width="80" /></a>                         
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

 </body>
 </html>  
