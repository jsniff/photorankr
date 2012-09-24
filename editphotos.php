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

    $myemail = $_SESSION['email'];
    
    $findreputationme = mysql_query("SELECT user_id,reputation,profilepic,firstname,lastname FROM userinfo WHERE emailaddress = '$myemail'");
    $reputationme = number_format(mysql_result($findreputationme,0,'reputation'),2);
    $sessionpic = mysql_result($findreputationme,0,'profilepic');
    $sessionfirst =  mysql_result($findreputationme,0,'firstname');
    $sessionlast =  mysql_result($findreputationme,0,'lastname');
    $sessionid =  mysql_result($findreputationme,0,'user_id');
    $sessionname = mysql_result($findreputationme,0,'firstname') ." ". mysql_result($findreputationme,0,'lastname');
    $currenttime = time();

//Grab Overall Portfolio Ranking

    $userphotos="SELECT * FROM photos WHERE emailaddress = '$myemail'";
    $userphotosquery=mysql_query($userphotos);
    $numphotos=mysql_num_rows($userphotosquery);
    $emailaddress = mysql_result($userphotosquery,0,'emailaddress');
    
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
    
    $scorequery = "UPDATE userinfo SET totalscore = '$portfoliopoints' WHERE emailaddress = '$emailaddress'";    
    $scoreresult = mysql_query($scorequery);
    
    }
    
    else if ($portfoliovotes < 1) {
    $portfolioranking="N/A";
    }	
    
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
        $newabout = mysql_real_escape_string($_POST['about']);
        $newtag1 = mysql_real_escape_string($_POST['tag1']);
        $newtag2 = mysql_real_escape_string($_POST['tag2']);
        $newtag3 = mysql_real_escape_string($_POST['tag3']);
        $newset = mysql_real_escape_string($_POST['addtoset']);
        
        $imageid = $_GET['imageid'];

		//update the database with the new information
		$updatequery = "UPDATE photos SET caption='$newcaption', location='$newlocation', price='$newprice', camera='$newcamera', about='$newabout',";
        
        if($newtag1) {
            $updatequery .= "tag1 = '$newtag1',"; 
        }
        
        if($newtag2) {
            $updatequery .= "tag2 = '$newtag2',"; 
        }
        
        if($newtag3) {
            $updatequery .= "tag3 = '$newtag3',"; 
        }
        
        if($newfocallength) {
            $updatequery .= "focallength = '$newfocallength',"; 
        }
        
        if($newshutterspeed) {
            $updatequery .= "shutterspeed = '$newshutterspeed',"; 
        }
        
        if($newaperture) {
            $updatequery .= "aperture = '$newaperture',"; 
        }
        
        if($newlens) {
            $updatequery .= "lens = '$newlens',"; 
        }
        
        if($newfilter) {
            $updatequery .= "filter = '$newfilter',"; 
        }
        
        if($newlens) {
            $updatequery .= "lens = '$newlens',"; 
        }
        
        $updatequery .= " set_id = concat(set_id,'$newset') WHERE id='$imageid'";
		mysql_query($updatequery) or die(mysql_error());
}


if($_GET['view'] == "saveexhibit") {

    $newtitle = mysql_real_escape_string($_POST['title']);
    $newabout = mysql_real_escape_string($_POST['aboutset']);
    $set = $_GET['set'];
    $updatequery = mysql_query("UPDATE sets SET title='$newtitle', about='$newabout' WHERE id='$set'") or die(mysql_error());
        
}

//check if the viewer is the same as the viewee
if($myemail != $emailaddress ) {
	header("Location: myprofile.php");
}

if($_SESSION['loggedin'] != 1) {
	header("Location: newest.php");
}

//QUERY FOR NOTIFICATIONS
$currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$emailaddress'";
$currentnotsquery = mysql_query($currentnots);
$currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");

if($_GET['action'] == "delete") {
        $imageid = $_GET['imageid'];
        $deletequery = mysql_query("DELETE FROM photos WHERE id='$imageid'") or die(mysql_error());
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
  
  //EDIT PHOTO
  
    if($_GET['edit'] == 'save') {
        
        $imageid = $_GET['imageid'];
        
        $caption = mysql_real_escape_string($_POST['caption']);
        $location = mysql_real_escape_string($_POST['location']);
        $price = mysql_real_escape_string($_POST['price']);
        $exhibit = mysql_real_escape_string($_POST['exhibit']);
        $about = mysql_real_escape_string($_POST['about']);

        $editquery = mysql_query("UPDATE photos SET (caption = '$caption', location = '$location', price = '$price', about = '$about') WHERE id = '$imageid' AND emailaddress = '$myemail'");
        
    }

  
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Edit Portfolio | PhotoRankr</title>

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
  <script type="text/javascript" src="js/jquery.wookmark.js"></script>        
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

     <style type="text/css">
    
        label {
            
            padding:5px;
            
        }
    
     </style>

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

<body id="body" style="background-color:rgb(255,255,255);min-width:1220px;">

<?php navbarnew(); ?>

<!--Here the Grid Container Begins-->
<div class="container_24 container-margin" style="margin-top:70px;">

<div class="grid_15 pull_2">

    <?php
        
        $cat = htmlentities($_GET['cat']);
        $searchterm = htmlentities($_GET['searchterm']);

        $querycount = mysql_query("SELECT * FROM photos WHERE emailaddress = '$myemail' ORDER BY id DESC");
        $countnumberimages = mysql_num_rows($querycount);
        
        $setscount = mysql_query("SELECT * FROM sets WHERE owner = '$myemail' ORDER BY id DESC");
        $countnumbersets = mysql_num_rows($setscount);
        
    ?>	

	<div class="grid_14 pull_1" style="float:left;">
		<h1 style="font-size:22px;padding-bottom:15px;font-weight:200;">Your Portfolio | <?php echo $countnumberimages; ?> photos</h1>
        <div style="padding-left:30px;padding-bottom:10px;margin-top:-10px;font-size:16px;"><span style="font-size:13px;color:#333;">Avg. Portfolio Score:</span> <?php echo $portfolioranking; ?>&nbsp;&nbsp;&nbsp;<span style="font-size:13px;color:#333;">Total # Favorites:</span> <?php echo $portfoliofaves; ?>&nbsp;&nbsp;&nbsp;<span style="font-size:13px;color:#333;">Exhibits:</span> <?php echo $countnumbersets; ?></div>
	</div>	
    
    <div class="grid_14 roundedright" style="background-color:#eeeff3;height:35px;width:750px;">

<a style="text-decoration:none;color:black;" href="editphotos.php"><div class="clicked" style="width:130px;height:35px;border-right:1px solid #ccc;border-left:1px solid #ccc;float:left;<?php if($cat == '' && !$searchterm) {echo'background-color:#bbb;color:white;';} ?>"><div style="font-size:16px;font-weight:200;margin-top:10px;text-align:center;">Newest</div></div></a>

<a style="text-decoration:none;color:black;" href="editphotos.php?cat=top"><div class="clicked" style="width:130px;height:35px;border-right:1px solid #ccc;border-left:1px solid #ccc;float:left;<?php if($cat == 'top') {echo'background-color:#bbb;color:white;';} ?>"><div style="font-size:16px;font-weight:200;margin-top:10px;text-align:center;">Top Ranked</div></div></a>

<a style="text-decoration:none;color:black;" href="editphotos.php?cat=faved"><div class="clicked" style="width:130px;height:35px;border-right:1px solid #ccc;float:left;<?php if($cat == 'faved') {echo'background-color:#bbb;color:white;';} ?>"><div style="font-size:16px;font-weight:200;margin-top:10px;text-align:center;">Most Favorited</div></div></a>

<a style="text-decoration:none;color:black;" href="editphotos.php?cat=exts"><div class="clicked" style="width:130px;height:35px;border-right:1px solid #ccc;float:left;<?php if($cat == 'exts') {echo'background-color:#bbb;color:white;';} ?>"><div style="font-size:16px;font-weight:200;margin-top:10px;text-align:center;">Exhibits</div></div></a>

<div style="width:160px;height:35px;float:left;"><div style="font-size:16px;font-weight:100;margin-top:-2px;text-align:center;margin-left:5px;">
<form class="navbar-search" method="GET">
<input class="searcheditphotos" style="position:relative;margin-left:2px;margin-top:0px;" name="searchterm" type="text" placeholder="Search for a photo&#8230" >
</form></div></div></div>	
    
	<div class="grid_21 pull_1" style="float:left;" >
	
    <?php
                
        if($cat == '' && !$searchterm) {
        $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$myemail' ORDER BY id DESC LIMIT 12");
        }
        
        elseif($cat == 'top') {
        $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$myemail' ORDER BY (points/votes) DESC LIMIT 12");
        }
        
        elseif($cat == 'faved') {
        $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$myemail' ORDER BY faves DESC LIMIT 12");
        }
        
        elseif($cat == 'exts') {
        $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$myemail' ORDER BY id DESC LIMIT 12");
        }
        
        elseif($cat == '' && $searchterm) {
        $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$myemail' AND concat(caption,location,tag1,tag2,tag3,tag4) LIKE '%$searchterm%' ORDER BY views DESC LIMIT 21");
        }
        
        $numberimages = mysql_num_rows($query);
        
        echo'
        <div id="thepics" style="position:relative;width:780px;margin-left:15px;font-size:13px;">
        <div id="main" role="main">
        <ul id="tiles">';
    
    if($cat != 'exts') {
    
        for($iii=0; $iii < $numberimages; $iii++) {
        
                $image[$iii] = mysql_result($query, $iii, "source");
                $imageThumb[$iii] = str_replace("userphotos/","../userphotos/medthumbs/", $image[$iii]);
                $id = mysql_result($query, $iii, "id");
                $price = mysql_result($query, $iii, "price");
                if($price != 'Not For Sale') {
                    $price = '$' . $price;
                }
                elseif($price == 'Not For Sale') {
                    $price = 'NFS';
                }
                elseif($price == '.00' || $price == '') {
                    $price = 'Free';
                }
                $caption = mysql_result($query, $iii, "caption");
                $points = mysql_result($query, $iii, "points");
                $votes = mysql_result($query, $iii, "votes");
                $faves = mysql_result($query, $iii, "faves");
                $score = number_format(($points/$votes),2);
                $faveemail = mysql_result($query, $iii, "emailaddress");
                $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$faveemail'");
                $firstname = mysql_result($query, 0, "firstname");
                $lastname = mysql_result($query, 0, "lastname");
                $reputation = mysql_result($query, 0, "lastname");
                $fullname = $firstname . " " . $lastname;
                list($width, $height) = getimagesize($image[$iii]);
                $imgratio = $height / $width;
                $heightls = $height / 3.2;
                $widthls = $width / 3.2;
                
                if($widthls < 240) {
                    $heightls = $heightls * ($heightls/$widthls);
                    $widthls = 250;
                }
                
                echo'<a style="text-decoration:none;color:#000;" href="editphotos.php?imageid=',$id,'';if($cat) {echo'&cat=',$cat,'';} if($searchterm) {echo'&searchterm=',$searchterm,'';} echo'"><li class="fPic"'; 
                
                if($cat == '') {
                    echo'id="',$id,'"';
                }
                
                elseif($cat == 'top') {
                    echo'id="',$score,'"';
                }
                
                elseif($cat == 'faved') {
                    echo'id="',$id,'"';
                }
                
                echo'
                style="padding:5px;margin-right:10px;margin-top:10px;list-style-type: none;width:240px;
"><img onmousedown="return false" oncontextmenu="return false;" src="http://photorankr.com/',$imageThumb[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /><div style="padding:3px;"><div style="float:left;">',$caption,'</div><div style=float:right;font-size:13px;font-weight:500;">',$price,'</div><br /><span style="font-size:14px;">',$score,'/</span><span style="font-size:12px;color:#444;">10.0</span><br /><i class="icon-heart"></i>&nbsp;',$faves,' favorites</div></li></a>';
        
        } //end of for loop
        
    } //end cat != 'exts'
    
    if($cat == 'exts') {
    
        //select all exhibits of user
        
        $allsetsquery = "SELECT * FROM sets WHERE owner = '$myemail' ORDER BY id DESC";
        $allsetsrun = mysql_query($allsetsquery);
        $numbersets = mysql_num_rows($allsetsrun);
            
        echo'<div style="position:relative;top:15px;margin-left:7px;">';
        
        for($iii=0; $iii < $numbersets; $iii++) {

            $setname[$iii] = mysql_result($allsetsrun, $iii, "title");
            $setcover = mysql_result($allsetsrun, $iii, "cover");
            $set_id[$iii] = mysql_result($allsetsrun, $iii, "id");
            $setname2[$iii] = (strlen($setname[$iii]) > 30) ? substr($setname[$iii],0,27). " &#8230;" : $setname[$iii];
            $pulltopphoto = mysql_query("SELECT source FROM photos WHERE set_id = '$set_id[$iii]' ORDER BY votes DESC LIMIT 5");
            if($setcover == '') {
            $setcover = mysql_result($pulltopphoto, 0, "source");
        }

            $thumb1 = mysql_result($pulltopphoto, 1, "source");
            $thumb1 = str_replace("userphotos/","userphotos/medthumbs/",$thumb1);
            $thumb2 = mysql_result($pulltopphoto, 2, "source");
            $thumb2 = str_replace("userphotos/","userphotos/medthumbs/",$thumb2);
            $thumb3 = mysql_result($pulltopphoto, 3, "source");
            $thumb3 = str_replace("userphotos/","userphotos/medthumbs/",$thumb3);
            $thumb4 =mysql_result($pulltopphoto, 4, "source");
            $thumb4 = str_replace("userphotos/","userphotos/medthumbs/",$thumb4);

        list($width, $height) = getimagesize($setcover);
        $imgratio = $height / $width;
        $heightls = $height / 3.2;
        $widthls = $width / 3.2;
        
if($widthls < 240) {
    $heightls = $heightls * ($heightls/$widthls);
    $widthls = 250;
}
        
        //grab all photos in the exhibit
        
        $grabphotos = "SELECT * FROM photos WHERE emailaddress = '$useremail' AND set_id = '$set_id[$iii]'";
        $grabphotosrun = mysql_query($grabphotos);
        $numphotosgrabbed = mysql_num_rows($grabphotosrun);


    echo'<li style="width:240px;list-style-type:none;"><a style="text-decoration:none;" href="editphotos.php?cat=exts&set=',$set_id[$iii],'">
    
    <div style="width:100%;">
    
    <div style="padding-top:5px;padding-left:3px;font-size:13px;text-decoration:none;color:#000;font-weight:200;"><span style="font-size:15px;font-weight:400;">',$setname2[$iii],'</span><br />',$numphotosgrabbed,' Photos</div>
<hr />

    <img style="margin-top:2px;" onmousedown="return false" oncontextmenu="return false;" src="http://www.photorankr.com/',$setcover,'" alt="',$setname[$iii],'" height="',$heightls,'px" width="',$widthls,'px" />';
    
    if($thumb4) {
        echo'
            <div>
            <img style="float:left;padding:5px;" src="http://www.photorankr.com/',$thumb1,'" width="110" height="110" />
            <img style="float:left;padding:5px;" src="http://www.photorankr.com/',$thumb2,'" width="110" height="110" />
            <img style="float:left;padding:5px;" src="http://www.photorankr.com/',$thumb3,'" width="110" height="110" />
            <img style="float:left;padding:5px;" src="http://www.photorankr.com/',$thumb4,'" width="110" height="110" />
            </div>';
    }
    
    echo'
    </a>
    
    </li><br />';

    
} //end of for loop
                
    echo'</div>';
                            
    } //end $cat == 'exts'
    
         echo'</ul>';
        
    ?>
    
    <!-- Once the page is loaded, initalize the plug-in. -->
  <script type="text/javascript">
    $(document).ready(new function() {
      // Prepare layout options.
      var options = {
        autoResize: true, // This will auto-update the layout when the browser window is resized.
        container: $('#main'), // Optional, used for some extra CSS styling
        offset: 4, // Optional, the distance between grid items
        itemWidth: 250 // Optional, the width of a grid item
      };
      
      // Get a reference to your grid items.
      var handler = $('#tiles li');
      
      // Call the layout function.
      handler.wookmark(options);
      
    });
  </script>

    
 <?php      
        
        //AJAX CODE HERE
echo'
   <div class="grid_6 push_9" style="top:20px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;">Loading More Photos&hellip;</div>
   </div>';


echo '<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-500) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMorePortfolioPics").show();
				$.ajax({
					url: "loadMoreEditPhotos.php?lastPicture=" + $(".fPic:last").attr("id")+"&cat=',$cat,'",
					success: function(html) {
						if(html) {
							$("#thepics").append(html);
							$("div#loadMorePortfolioPics").hide();
						}
					}
				});
				last = $(".fPic:last").attr("id");
			}
		}
	});
</script>';
    
        echo'</div>';
        echo'</div>';
    
    ?>
    
    </div>
    
</div>


<!--PHOTO INFORMATION-->

<?php

//Get imageid 
    $imageid = $_GET['imageid'];
    
    if(!$imageid) {
    
        if($cat == '') {
            $firstquery = mysql_query("SELECT id FROM photos WHERE emailaddress = '$myemail' ORDER BY id DESC LIMIT 1");
            $imageid = mysql_result($firstquery,0,'id');
        }
        
        elseif($cat == 'top') {
            $firstquery = mysql_query("SELECT id FROM photos WHERE emailaddress = '$myemail' ORDER BY (points/votes) DESC LIMIT 1");
            $imageid = mysql_result($firstquery,0,'id');       
        }
        
        elseif($cat == 'faved') {
            $firstquery = mysql_query("SELECT id FROM photos WHERE emailaddress = '$myemail' ORDER BY faves DESC LIMIT 1");
            $imageid = mysql_result($firstquery,0,'id');    
        }

    }
    
    $imagequery = "SELECT * FROM photos WHERE id = '$imageid'";
    $result = mysql_query($imagequery);


$emailaddress = mysql_result($result,0,'emailaddress');
$caption = mysql_result($result,0,'caption');
$caption = (strlen($caption) > 22) ? substr($caption,0,18). "&#8230;" : $caption;
$location = mysql_result($result,0,'location');
$source = mysql_result($result,0,'source');
$about = mysql_result($result,0,'about');
$id = mysql_result($result, 0, "id");
$points = mysql_result($result, 0, "points");
$votes = mysql_result($result, 0, "votes");
$score = number_format(($points/$votes),2);
                
list($width, $height) = getimagesize($source);
        $imgratio = $height / $width;
        $heightls = $height / 3.2;
        $widthls = $width / 3.2;

$source = str_replace('userphotos/','userphotos/medthumbs/',$source);
$camera = mysql_result($result,0,'camera');
$regprice=mysql_result($result, 0, "price");
$price=mysql_result($result, 0, "price");

$exquery = mysql_query("SELECT source FROM photos WHERE set_id = '$exhibit' AND emailaddress = '$myemail' ORDER BY (points/votes) DESC LIMIT 0,3");
$expic1 = mysql_result($exquery,0,'source');
$exthumb1 = str_replace("userphotos/","userphotos/medthumbs/",$expic1);
$expic2 = mysql_result($exquery,1,'source');
$exthumb2 = str_replace("userphotos/","userphotos/medthumbs/",$expic2);
$expic3 = mysql_result($exquery,2,'source');
$exthumb3 = str_replace("userphotos/","userphotos/medthumbs/",$expic3);

$focallength = mysql_result($result,0,'focallength');
$shutterspeed = mysql_result($result,0,'shutterspeed');
$aperture = mysql_result($result,0,'aperture');
$lens = mysql_result($result,0,'lens');
$filter = mysql_result($result,0,'filter');

$tag1 = mysql_result($result,0,'tag1');
$tag2 = mysql_result($result,0,'tag2');
$tag3 = mysql_result($result,0,'tag3');
$tag4 = mysql_result($result,0,'tag4');

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

if ($price == "0.00") {$price='Free';}  
elseif ($price == "Not For Sale") {$price='NFS';}  
elseif ($price == "") {$price='';} 
else {$price = '$' . $price; }

//Exhibit Information
$set = $_GET['set'];
if(!$set) {

    $pullset = mysql_query("SELECT id FROM sets WHERE owner = '$myemail' ORDER BY id DESC LIMIT 1");
    $set = mysql_result($pullset,0,'id');

}
$setinfo = mysql_query("SELECT title, about, cover, faves FROM sets WHERE id = '$set'");
$settitle = mysql_result($setinfo,0,'title');
$settitle = (strlen($settitle) > 22) ? substr($settitle,0,18). "&#8230;" : $settitle;
$setfaves = mysql_result($setinfo,0,'faves');
$aboutset = mysql_result($setinfo,0,'about');
if($cat == 'exts') {
    $source = mysql_result($setinfo,0,'cover');
    if(!$source) {
        $getexphoto = mysql_query("SELECT source FROM photos WHERE set_id = '$set' AND emailaddress = '$myemail' ORDER BY (points/votes) DESC LIMIT 1");
        $source = mysql_result($getexphoto,0,'source');
    }
    $source = str_replace("userphotos/","userphotos/medthumbs/",$source);
}

?>

<script>
var url = 'http://www.site.com/234234234';
var id = url.substring(url.lastIndexOf('/') + 1);
</script>

    
    <?php  if($cat != 'exts') { ?>

		<div class="grid_7" style="position:fixed;margin-left:750px;margin-top:35px;">
        
        <?php
        
            if($_GET['view'] == 'saveinfo') {
            
                echo'<div class="label label-success" style="font-size:16px;padding:10px;width:270px;text-align:center;margin-top:-40px;margin-bottom:6px;margin-left:5px;">Photo Saved</div>';
                
            }
            
            if($_GET['action'] == 'delete') {
            
                echo'<div class="label label-important" style="font-size:16px;padding:10px;width:270px;text-align:center;margin-top:-40px;margin-bottom:6px;margin-left:3px;">Photo Deleted</div>';
                
            }
        
        ?>
            
			<div class="grid_7 box"> <!--ID Tag-->
            
                <div style="float:left;width:65px;height:75px;overflow:hidden;"><a href="fullsize.php?imageid=<?php echo $imageid; ?>"><img class="roundedall" src="http://photorankr.com/<?php echo $source; ?>" alt="<?php echo $caption; ?>" style="width:80px;" /></a></div>
                
            <div style="float:left;padding:10px;line-height:23px;">

                 <?php echo $caption; ?>
                 <div style="padding:0px;"><?php echo $score; ?><span style="color:#555;font-size:12px;">/10.0</span></div>
                        
            </div>

				<div>
                    
                    <div style="padding:5px;margin-top:0px;">
                                            
                        <form action="?imageid=',$id,'';if($cat) {echo'&cat=',$cat,'';} echo'&view=saveinfo" method="post" />
                        
                                            
                        <div style="clear:both;">
                            <label>Caption:</label> <input style="padding:5px;width:240px;" type="text" name="caption" value="<?php echo $caption; ?>">
                        </div>
                        
                        <div>
                            <label>Location:</label> <input style="padding:5px;width:240px;" type="text" name="location" value="<?php echo $location; ?>">
                        </div>
                        
                        <div>
                            <label>Price:</label> 
                                                          
                                <select id="price" name="price" style="width:180px;" onchange="showOtherPrice()">
                                <option value="<?php echo $regprice; ?>">Choose a price:</option>
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
                                
                                &nbsp;&nbsp;&nbsp;<?php echo $price; ?> 
                                                                
                        </div>
                        
                        <div>
                            <label>Add to exhibit:</label> 
                            
                            <select name="addtoset" onchange="showTags(this.value)" style="width:245px;">
                                <option value="" style="display:none;">Choose an exhibit:</option>';
                            <?php
                            
                                //select all sets associated with user email
                                $setsquery = "SELECT * FROM sets WHERE owner = '$myemail'";
                                $setsqueryrun = mysql_query($setsquery);
                                $setscount = mysql_num_rows($setsqueryrun);

                                for($iii=0; $iii < $setscount; $iii++) {
                                    $settitle = mysql_result($setsqueryrun, $iii, "title");
                                    $setid = mysql_result($setsqueryrun, $iii, "id");
                                    echo'<option value="',$setid,'">',$settitle,'</option>';
                                }
                            
                            ?>
                                
                            </select> 
                            
                        </div>
                    
                    </div>
                    
                        <div>
                            <label>About Photo:</label> <textarea style="padding:5px;width:240px;height:60px;" name="about"><?php echo $about; ?></textarea>
                        </div>
                    
                    
                    
                    <script type="text/javascript">
                
                function newimage() {
                       var url = location.hash;
                       url = url.substring(1,url.length);
                       var url = "http://photorankr.com/" + url; 
                       document.getElementById('image').src = url;
                    }
            
                    </script
                    
				</div>
            
		</div>

            
			<div class="grid_7" style="text-align:center;"><!--Rank and stats-->
				<div class="grid_7" style="padding-top:7px;">
                
                    <button class="btn btn-success" type="submit" style="margin-left:-25px;width:110px;padding:7px;font-size:14px;">Save Photo</button>
                    
                </form>
                    
                    <a class="btn btn-primary" style="margin-left:10px;width:110px;padding:7px;font-size:14px;opacity:.8;" data-toggle="modal" data-backdrop="static" href="#editphoto">More Info</a>
                    
        </div>
	</div>
    
        <?php } ?>

                
           <?php
               
                 if($cat == 'exts') {
                 
                    if($set) {
                 
                        echo'<div class="grid_7" style="position:fixed;margin-left:750px;margin-top:70px;">';
                        
                        if($_GET['view'] == 'saveexhibit') {
            
                echo'<div class="label label-success" style="font-size:16px;padding:10px;width:270px;text-align:center;margin-top:-40px;margin-bottom:6px;margin-left:5px;">Exhibit Saved</div>';
                
                        }

                        echo'
                            
                            <div class="grid_7 box"> <!--ID Tag-->
            
                            <div style="float:left;width:65px;"><a href="myprofile.php?view=exhibits&set=',$set,'"><img class="roundedall" src="http://photorankr.com/',$source,'" alt="<?php echo $caption; ?>" style="width:80px;" /></a></div>
                
                            <div style="float:left;padding:10px;line-height:23px;">

                            <a style="color:#000;" href="myprofile.php?view=exhibits&set=',$set,'">',$settitle,'</a>
                            
                            <div style="padding:0px;"><i class="icon-heart"></i> ',$setfaves,'<span style="color:#555;font-size:12px;"> favorites</span></div>
                            
                            </div>           
                    
                        <div style="padding:5px;top:20px;">
                        
                         <form action="?';if($cat) {echo'&cat=',$cat,'';} echo'&set=',$set,'&view=saveexhibit" method="post" />
                         
                         <div style="clear:both;">
                            <label>Exhibit title:</label> <input style="padding:5px;width:240px;" type="text" name="title" value="',$settitle,'">
                        </div>

                        <div>
                            <label>About exhibit:</label> <textarea style="padding:5px;width:240px;height:80px;" name="aboutset">',$aboutset,'</textarea>
                        </div>
                        
                        <button class="btn btn-success" type="submit" style="width:256px;padding:7px;font-size:16px;text-align:center;">Save Exhibit</button>
                        
                        </form>
                        
                        </div>
                        
                        </div>';
                        
                    }
                }
                        
                ?>
      
    </div>	
</div>	

<!--Edit Photo Modal-->
<div class="modal hide fade" id="editphoto" style="overflow-y:scroll;overflow-x:hidden;border:5px solid rgba(102,102,102,.8)">
<?php

echo'
<div class="modal-header" style="background-color:#111;color:#fff;">
<a style="float:right" class="btn btn-success" data-dismiss="modal">Close</a>
<img style="margin-top:-2px;" src="graphics/aperture_white.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">Edit your photo\'s information below:</span>
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:550px;height:500px;overflow-x:hidden;">
		
<img style="border: 1px solid black;margin-left:10px;margin-top:10px;" src="http://photorankr.com/',$source,'" 
height="100px" width="100px" />

<div style="width:550px;height:700px;margin-left:130px;margin-top:-100px;overflow-x:hidden;padding-bottom:80px;">

<form action="?imageid=',$id,'';if($cat) {echo'&cat=',$cat,'';} echo'&view=saveinfo" method="post" />
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
    Keywords:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input style="width:80px;" name="tag1" value="',$tag1,'">&nbsp;&nbsp;<input style="width:80px;" name="tag2" value="',$tag2,'">&nbsp;&nbsp;<input style="width:80px;" name="tag3" value="',$tag3,'">

    <br /><br />
    About this Photo:&nbsp;
    <br /><br />
    <textarea style="width:380px" rows="4" cols="60" name="about">',$about,'</textarea>
    <br />
    </span>
    <button class="btn btn-success" type="submit">Save Info</button>
    </form>
     <a style="position: relative; top: -28px; left: 280px;" href="editphotos.php?imageid=',$id,'';if($cat) {echo'&cat=',$cat,'';} echo'&action=delete"><button class="btn btn-danger">Delete Photo</button></a>

</div>
</div>
</div>';
    
?>

</div>


 </body>
 </html>  
