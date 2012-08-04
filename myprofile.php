
<?php 

//CONNECT TO DB
require "db_connection.php";

if($_GET['action'] == "signup") { //if they tried to sign up from signin.php
	$firstname = addslashes($_REQUEST['firstname']);
    $firstname = trim($firstname);
    $firstname = ucwords($firstname);
	$lastname = addslashes($_REQUEST['lastname']);
    $lastname = trim($lastname);
    $lastname = ucwords($lastname);
	$newemail = mysql_real_escape_string($_REQUEST['emailaddress']);
	$password = mysql_real_escape_string($_REQUEST['password']);
	$confirmpassword = mysql_real_escape_string($_REQUEST['confirmpassword']);
	$terms = mysql_real_escape_string($_REQUEST['terms']);
	$mattfollow = "'support@photorankr.com'";
	$originalfave = "'userphotos/paintedbuilding1.jpg'";
	$originalfave = addslashes($originalfave);
	$mattfollow = addslashes($mattfollow);
	$check = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$newemail'");
	$others = mysql_num_rows($check);

	//if they forgot to enter any information
	if(!$_REQUEST['firstname'] or !$_REQUEST['lastname'] or !$_REQUEST['emailaddress'] or !$_REQUEST['password'] or !$_REQUEST['confirmpassword'] or !$_REQUEST['terms']) {
		die("You did not complete all required fields.");
	}
	else if($password != $confirmpassword) { //if passwords dont match
		die("Your passwords did not match.");
	}
	//else if that email address is already in the database
	else if($others != 0) {
		header("Location: lostpassword.php");
	}
	else {
		//put their info in database
        $settinglist = " emailcomment emailreturncomment emailfave emailfollow ";
		$newuserquery = "INSERT INTO userinfo (firstname, lastname, emailaddress, password, following, faves, settings) VALUES ('$firstname', '$lastname', '$newemail', '$password', '$mattfollow', '$originalfave','$settinglist')";
		mysql_query($newuserquery);
        
         //newsfeed query
        $type = "signup";
        $newsfeedsignupquery=mysql_query("INSERT INTO newsfeed (firstname, lastname, emailaddress,type) VALUES ('$firstname', '$lastname', '$newemail','$type')");
        
        //SEND REGISTRATION GREETING
        
        $to = $newemail;
        $subject = 'Welcome to PhotoRankr!';
        $message = 'Thank you for signing up with PhotoRankr! You can now upload your own photos and sell them at your own price, follow the best photographers, and become part of a growing community. If you have any questions about PhotoRankr or would like to suggest an improvement, you can email us at photorankr@photorankr.com. We greatly value your feedback and hope you will spread the word about PhotoRankr to your friends and family by referring them to the site with the link below:
        
		http://photorankr.com/referral.php        

		Again, welcome to the site!

		Sincerely,
		PhotoRankr';
        $headers = 'From:PhotoRankr <photorankr@photorankr.com>';
        mail($to, $subject, $message, $headers);  
              
		session_start();
		$_SESSION['email'] = $newemail;
		$_SESSION['loggedin'] = 1;
        
        echo'<div id="example" class="modal hide fade in" style="display:none;width:850px;height:580px;margin-left:-400px;">
            
    <div class="modal-header">
    <a class="close" data-dismiss="modal">x</a>
    <h3 style="vertical-align:middle;font-size:25px;display:inline;">Welcome to&nbsp;</h3><img style="display:inline;margin-top:-4px;" src="graphics/logoteal.png" height="50" width="210" />
    <p style="font-size:15px;">This tutorial shows you how to start using PhotoRankr, the world\'s fastest growing and most passionate online photography community and marketplace.</p>
    </div>

<div class="modal-body" style="width:820px;height:650px;overflow:hidden;">

    <div id="myCarousel" class="carousel slide">
    <!-- Carousel items -->
    <div class="carousel-inner">
    <div class="active item">
	<p style="font-size:15px;line-height:1.48;"><b>Profile.</b>  Upon finishing this tutorial, you will be redirected to your PhotoRankr profile page where you can begin uploading, ranking, discovering, and selling photography.</p>
        <img src="img/Profile.png" style="width:800px;height:350px;"/>
    </div>
    <div class="item">
	<p style="font-size:15px;line-height:1.48;"><b>Gridview.</b>  Your profile has a "Newsfeed" with two different views.  "Gridview" displays in a compact format the activity of photographers you are following, as well as photos you have interacted with.</p>
    <img src="img/Gridview.png" style="width:800px;height:350px;"/>
    </div>
    <div class="item">
	<p style="font-size:15px;line-height:1.48;"><b>Photostream.</b>  Change your Newsfeed to "Photostream" view to view your PhotoRankr news with more emphasis on the photos.</p>
    <img src="img/Photostream.png" style="width:800px;height:350px;"/>
    </div>
    <div class="item">
	<p style="font-size:15px;line-height:1.48;"><b>Upload.</b>  Click the "Upload" icon to upload your work.  First, add tags so your photo gains publicity.  Second, name a price, if any, for your photo.  Finally, add optional information to teach others about your work.</p>
    <img src="img/Upload.png" style="width:800px;height:350px;"/>
    </div>
    <div class="item">
	<p style="font-size:15px;line-height:1.48;"><b>Exhibits.</b>  Use the "Create an Exhibit" feature within the "Upload" section of your profile to create groups of photos with a common artistic theme, subject matter, or technique.</p>
    <img src="img/Exhibit.png" style="width:800px;height:350px;"/>
    </div>
    <div class="item">
	<p style="font-size:15px;line-height:1.48;"><b>About.</b>  Visit the "About" section of your profile to add your camera, photography preferences, a quote that inspires your work, your Facebook and Twitter accounts, and a short biography about you and your work.</p>
    <img src="img/About.png" style="width:800px;height:350px;"/>
    </div>
    <div class="item">
	<p style="font-size:15px;line-height:1.48;"><b>Settings.</b>  Be sure to review your "Settings."  You may change your e-mail preferences and select cover photos for your profile.</p>
    <img src="img/Settings.png" style="width:800px;height:350px;"/>
    </div>
    <div class="item">
	<p style="font-size:15px;line-height:1.48;"><b>Messaging.</b>  The "Messaging" tab of your profile allows you to hold private conversations with photographers with whom you would like to network or socialize.</p>
    <img src="img/Messaging.png" style="width:800px;height:350px;"/>
    </div>
    <div class="item">
	<p style="font-size:15px;line-height:1.48;"><b>Reputation.</b>  Your reputation is important because the greater it is, the more your vote counts on PhotoRankr.  It is based on three factors: number of followers, average portfolio score, and total votes on your photos.</p>
    <img src="img/Reputation.png" style="width:800px;height:350px;"/>
    </div>
    <div class="item">
	<p style="font-size:15px;line-height:1.48;"><b>Trending.</b>  The "Trending" page shows the photos users are ranking the most at the moment.  Your photos become trending when many users rank your photos in a short period of time.</p>
    <img src="img/Trending.png" style="width:800px;height:350px;"/>
    </div>
    <div class="item">
	<p style="font-size:15px;line-height:1.48;"><b>Newest.</b>  Visit "Newest" to find the most recent photos, photographers, and exhibits.  This page is a great place to discover unique photography.</p>
    <img src="img/Newest.png" style="width:800px;height:350px;"/>
    </div>
    <div class="item">
	<p style="font-size:15px;line-height:1.48;"><b>Top Ranked.</b>  The "Top Ranked" page contains the highest-ranked photos and photographers on the Site.  Become a top-ranked photographer by increasing your average portfolio score and amassing at least 1000 profile points.</p>
    <img src="img/TopRanked.png" style="width:800px;height:350px;"/>
    </div>
    <div class="item">
	<p style="font-size:15px;line-height:1.48;"><b>Full Size.</b>  The individual full-size photo pages are what PhotoRankr is all about: the photos.  View, rank, favorite, and comment on photos.  You can also post links to photos to Facebook and other social media sites.</p>
    <img src="img/FullSize.png" style="width:800px;height:350px;"/>
    </div>
    <div class="item">
	<p style="font-size:15px;line-height:1.48;"><b>Discover.</b>  The "Discover" page is a revolutionary way to discover photos and keep your photos relevant.  Clicking the "Discover" button displays photos based on preferences in the "About" section of your profile.</p>
    <img src="img/Discover.png" style="width:800px;height:350px;"/>
    </div>
    <div class="item">
	<p style="font-size:15px;line-height:1.48;"><b>Promote.</b>  Use the "Promote" button on photographers\' profile page to help promote their work using Facebook and other social media sites.</p>
    <img src="img/Promote.png" style="width:800px;height:350px;"/>
    </div>
    <div class="item">
	<p style="font-size:15px;line-height:1.48;"><b>Campaigns.</b>  PhotoRankr Campaigns are a groundbreaking way to sell your work and skills.  Submit photos in response to buyers\' requests for specific photos.  You get paid through PayPal and a secure license is generated instantly.</p>
    <img src="img/Campaigns.png" style="width:800px;height:350px;"/>
    </div>
    <div class="item">
	<p style="font-size:15px;line-height:1.48;"><b>Marketplace.</b>  You may also sell and purchase work through a more traditional marketplace.  PhotoRankr never handles your credit card information because it uses Stripe, a secure payment processor.</p>
    <img src="img/Marketplace.png" style="width:800px;height:350px;"/>
    </div>

    </div>
    <!-- Carousel nav -->
    <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
    <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
    </div>

</div>
</div>';

	}
}

//START SESSION AND CHECK IF THEY ARE LOGGED IN
@session_start();
if ($_SESSION['loggedin'] != 1) {
	header("Location: signin.php");
	exit();
} 

//log them out if they try to logout
session_start();
if($_GET['action'] == "logout") {
	$_SESSION['loggedin'] = 0;
	session_destroy();
}


ini_set('max_input_time', 300);  

//GET USER EMAIL
$email=$_SESSION['email'];

//QUERY FOR USERINFO
$select_query="SELECT * FROM userinfo WHERE emailaddress ='$email'";
$result=mysql_query($select_query);

//GRAB USER'S DATA 
if ($result) {
$row=mysql_fetch_array($result);
$user=$row['user_id'];
$firstname=$row['firstname'];
$lastname=$row['lastname'];
$emailaddress=$row['emailaddress'];
$password=$row['password'];
$age=$row['age'];
$gender=$row['gender'];
$location=$row['location'];
$camera=$row['camera'];
$facebookpage=$row['facebookpage'];
$twitteraccount=$row['twitteraccount'];
$bio=$row['bio'];
$quote=$row['quote'];
$profilepic=$row['profilepic'];
}

//QUERY FOR USER PHOTOS
$query="SELECT * FROM photos WHERE emailaddress ='$email' ORDER BY 'id' ASC";
$newresult=mysql_query($query);
$numberofpics=mysql_num_rows($newresult);

//QUERY FOR NOTIFICATIONS
$currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$emailaddress'";
$currentnotsquery = mysql_query($currentnots);
$currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");


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
    
    //NUMBER FOLLOWING
    $emailquery=("SELECT following FROM userinfo WHERE emailaddress ='$email'");
	$followresult=mysql_query($emailquery);
	$followinglist=mysql_result($followresult, 0, "following");
	$followingquery="SELECT * FROM userinfo WHERE emailaddress IN ($followinglist)";
	$followingresult = mysql_query($followingquery);
	$numberfollowing = mysql_num_rows($followingresult);

if(isset($_GET['view'])) {
	$view=htmlentities($_GET['view']); //get which tab of profile they are looking at
}


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

     $insertquery=mysql_query("UPDATE userinfo SET reputation = $ultimatereputation WHERE emailaddress='$emailaddress'");
    mysql_query($insertquery);



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



<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
 <meta property="og:image" content="http://photorankr.com/<?php echo $profilepic; ?>">
  <title><?php echo $firstname . " " . $lastname; ?> - PhotoRankr</title>
   <meta name="Generator" content="EditPlus">
   <meta name="viewport" content="width=1200" /> 
  <meta name="Author" content="PhotoRankr, PhotoRankr.com">
  <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
  <meta name="Description" content="PhotoRankr allows photographers of all skill levels to sell and share their work. Create your photostream cutomized to what you want to see. Add photos to your favorites, rank them, and watch them trend. Build your portfolio with Photorankr.">

  <link rel="stylesheet" href="reset.css" type="text/css" />
  <link rel="stylesheet" href="text.css" type="text/css" />
  <link rel="stylesheet" href="960_24.css" type="text/css" />
  	<link rel="Stylesheet" type="text/css" href="smoothDivScroll.css" />
  <link rel="shortcut icon" type="image/x-png" href="http://photorankr.com/graphics/favicon.png"/>
  <link rel="stylesheet" href="bootstrapnew.css" type="text/css" media="screen" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
      <script type="text/javascript" src="bootstrap.js"></script>    
  <script src="bootstrap-dropdown.js" type="text/javascript"></script>
  <script src="bootstrap-collapse.js" type="text/javascript"></script>
	<script src="jquery-ui-1.8.18.custom.min.js" type="text/javascript"></script>
	<script src="jquery.mousewheel.min.js" type="text/javascript"></script>
	<script src="jquery.smoothdivscroll-1.2-min.js" type="text/javascript"></script>
    
    <script>
    function load()
{

    $('#example').modal('show');
 
    
}
</script>

<script type="text/javascript">

$('#example').modal('hide');

</script>


<style type="text/css">

        .box {
        height:20px;
        background-color:white;
        padding:6px;
        border: 1px solid black;
        color:black;
        }
        
        .box:hover {
        background-color:#1a618a;
        color:white;
        }

        .statoverlay
        {
        opacity:.0;
        filter:alpha(opacity=40);
        z-index:1;
        transition: opacity .5s;
        -moz-transition: opacity .5s;
        -webkit-transition: opacity .5s;
        -o-transition: opacity .5s;
        }
            
        .statoverlay:hover
        {
        text-decoration:none;
        opacity:.7;
        }           

		#makeMeScrollable
		{
			width:100%;
			height: 60px;
			position: relative;
		}
		
		#makeMeScrollable div.scrollableArea img
		{
			position: relative;
			float: left;
			margin: 0;
			padding: 0;
			/* If you don't want the images in the scroller to be selectable, try the following
			   block of code. It's just a nice feature that prevent the images from
			   accidentally becoming selected/inverted when the user interacts with the scroller. */
			-webkit-user-select: none;
			-khtml-user-select: none;
			-moz-user-select: none;
			-o-user-select: none;
			user-select: none;
		}
                       
.iconhover:hover {
background-color: #fff;
}

.item {
  margin: 10px;
  float: left;
}

</style>

<script language="JavaScript">

var y=0;
var m=0;
var image=<?php echo $image; ?>;

var f;

function follow() {
fw=1;
location.href="http://www.photorankr.com/fullsize.php?image=<?php echo $image; ?>&fw="+fw
}

</script>

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

	<!-- Plugin initialization -->
	<script type="text/javascript">
		// Initialize the plugin with no custom options
		$(document).ready(function () {
			// None of the options are set
			$("div#makeMeScrollable").smoothDivScroll({});
		});
	</script>

<!--HIDDEN UPLOAD INFORMATION SCRIPT-->
<script type="text/javascript">   
$(document).ready(function(){
  $(".flip").click(function(){
    $(".panel").slideDown("slow");
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

<!--AJAX to pull off tags associated with a particular owners set they choose-->

<script type="text/javascript">
function showTags(str)
{
var xmlhttp;    
if (str=="")
  {
  document.getElementById("boxesappear").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("boxesappear").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","gettags.php?q="+str,true);
xmlhttp.send();
}
</script>

 </head>

<body onload="load()" style="background-color: #eeeff3; overflow-x: hidden;min-width:1220px;">


<!--NAVIGATION BAR-->
<div class="navbar" style="z-index:10;min-width:1100px;padding-top:0px;font-size:16px;width:100%;">
	<div class="navbar-inner">
		<div class="container">
			    <ul class="nav">
					<li><a style="color:#fff;" class="brand" style="margin-top:10px;margin-right:20px;" href="index.php"><div style="margin-top:-2px"><img src="logo.png" width="160" /></div></a></li>
                    
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
                echo'
                <li><a style="color:#fff;margin-top:2px;" href="';
                    if($nolikes) {echo 'myprofile.php?view=editinfo&action=discover#discover';}else { echo 'discover.php?image=',$discoverimage;} echo '">Discover</a></li>';
                    ?>
                   
                    <li class="dropdown">
					<li class="dropdown active" style="background-color:#587fa2;">
						<a style="color:#fff;margin-top:2px;margin-right:20px;" href="myprofile.php" class="dropdown-toggle" data-toggle="dropdown">My Profile<b class="caret"></b></a>
						<ul class="dropdown-menu" data-dropdown="dropdown">
							<li><a style="color:#fff;" href="myprofile.php">Portfolio</a></li>
                            <li><a style="color:#fff;" href="myprofile.php?view=info">Information</a></li>
							<li><a style="color:#fff;" href="myprofile.php?view=upload">Upload</a></li>
							<li><a style="color:#fff;" href="myprofile.php?view=followers">Followers</a></li>
							<li><a style="color:#fff;" href="myprofile.php?view=following">Following</a></li>
							<li><a style="color:#fff;" href="myprofile.php?view=faves">Favorites</a></li>
                            <li><a style="color:#fff;" href="myprofile.php?view=messages">Messages</a></li>
							<li><a style="color:#fff;" href="newest.php?action=logout">Log Out</a></li>
						</ul>
					</li>
                    
					<li class="navbartext"><form class="navbar-search" action="search.php" method="get">
						<input type="text" style="width:150px;border-color:#fff;background-color:#fff;margin-left:20px;" class="search-query" name="searchterm" placeholder="Search">
					</form></li>
				</ul>
			
		</div> <!--/end boostrap divs navbar-->
    </div>
</div>




<!--big container-->
<div id="container" class="container_24" style="padding-top:80px;">
                                
                  
		<!--profile picture and navigation-->
		<div class="grid_24">
			
			<!--profile picture-->
			<div class="grid_4 pull_3" style="text-align: center">
				<a href="viewprofile.php?u=<?php echo $user; ?>">
				<img src="<?php echo $profilepic; ?>" height="200" width="200" class="photoshadowreel"/>
				</a>
			</div>
           
        <!--3 cover photos-->
        
  <?php
  
   $gathercovers = "SELECT * FROM userinfo WHERE emailaddress = '$emailaddress'";
   $gathercoversrun = mysql_query($gathercovers);
   $coverslist = mysql_result($gathercoversrun, 0, "coverpics");
   $coverpic = explode(' ',$coverslist);
	if(count($coverpic) < 3) {
		$coverquery = "SELECT * FROM photos WHERE emailaddress='$emailaddress' ORDER BY score DESC LIMIT 0, 3";
		$gathercoversrun = mysql_query($coverquery);
		$coverpic[1] = mysql_result($gathercoversrun, 0, "source");
		$coverpic[2] = mysql_result($gathercoversrun, 1, "source");
		$coverpic[3] = mysql_result($gathercoversrun, 2, "source");
		if(!$coverpic[1]) {$coverpic[1] = "profilepics/nocoverphoto.png";}
		if(!$coverpic[2]) {$coverpic[2] = "profilepics/nocoverphoto.png";}
		if(!$coverpic[3]) {$coverpic[3] = "profilepics/nocoverphoto.png";}
	}

echo'<div class="grid_20" style="width:780px">';
   for($iii=1; $iii < 4; $iii++) {
    
    list($width, $height) = getimagesize($coverpic);
	$imgratio = $height / $width;
    $heightls = $height / 1.5;
    $widthls = $width / 1.5;
 
   
    echo'<div class="photoshadowreel" style="float:left;display:inline;width:256px;height:200px;overflow:hidden;"><img src="',$coverpic[$iii],'" height="256" width="256" /></div>';
   }

echo'</div>';              
                    
  ?>
        
        
		<!--/end profile picture and 24 grid-->
  
  
    
<!--2nd row of divs-->

<div class="grid_24"> 
<div class="grid_4 pull_3"><img src="graphics/shit.png" height="40" width="200" /></div>	
<div class="grid_16"><div class="photoshadowreel" style="height:20px;width:780px;margin-top:10px;font-size:14px;margin-left:-4px;">
<?php
echo'<span style="margin-left:3px;">';
if($quote == '') {
echo'<a href="myprofile.php?view=editinfo">Add your favorite quote</a>';
}
elseif($quote != ''){
$quoteshort = (strlen($quote) > 120) ? substr($quote,0,110). " &#8230;" : $quote;
echo $quoteshort;
}
echo'</span>';
?>
</div></div>	
</div> <!--end of 2nd row of divs-->        

<!--3rd row of divs-->
<div class="grid_24">
<div class="grid_4 pull_3"><div class="photoshadowreel" style="height:210px;width:200px;margin-left:-4px;"> 
<!--Information Box-->
<div style="font-size:16px;text-align:center;margin-top:5px;color:black;"><a style="text-decoration:none;" href="viewprofile.php?u=<?php echo $user; ?>"><?php echo $firstname . " " . $lastname; ?></a></div>
<div style="text-align:center;font-size:13px;margin-top:15px;"><span style="color:black;">Reputation</span> <?php echo number_format($ultimatereputation,2); ?></div>
                    <div class="progress" style="width:135px;height:8px;position:relative;left:30px;margin-top:5px;">
                    <div class="bar"
                    style="width: <?php echo number_format($ultimatereputation,2); ?>%;"> 
                    </div>
                    </div>
                     
                    <div style="text-align:center;font-size:13px;margin-top:5px;"><span style="color:black;">Portfolio Avg</span> <?php echo $portfolioranking; ?></div>
                    <div class="progress" style="width:135px;height:8px;position:relative;left:30px;margin-top:5px;">
                    <div class="bar"
                    style="width: <?php echo $portfolioranking*10; ?>%;"> 
                    </div>
                    </div>
                    
<div style="margin-top:10px;text-align:center;font-family:helvetica neue, lucida grande, gill sans; font-size:13px;"><span style="color:black;">Followers</span> <?php echo $numberfollowers; ?></div>                

<div style="margin-top:10px;text-align:center;font-family:helvetica neue, lucida grande, gill sans; font-size:13px;"><span style="color:black;">Favorited</span> <?php echo $portfoliofaves; ?></div> 

<?php
//top photographer score
            $query="SELECT * FROM userinfo WHERE emailaddress='$emailaddress'";
            $result=mysql_query($query);
            $tparray = mysql_fetch_array($result);
            $tpscore = $tparray['totalscore'];
    if($tpscore > 750) {
    echo'<div style="text-align:center;padding-top:5px;padding-bottom:10px;"><img src="graphics/tophotog.png" height="20" width="80" /></div><br /><br />';
    }
?>

</div></div>

<div class="grid_16"><div style="height:50px;width:780px;"> 

    <div id="makeMeScrollable" style="width:780px;height:50px;overflow:hidden;">
    
		<a href="myprofile.php"><img class="iconhover" style="padding-right:10px;margin-left:3px;<?php if($view == ''){echo'background-color:white';} ?>
" src="graphics/mpphotos.png" alt="Demo image" id="field" height="50"  /></a>
        
		<a href="myprofile.php?view=faves"><img class="iconhover" style="padding-right:10px;margin-left:3px;<?php if($view == 'faves'){echo'background-color:white';} ?>
" src="graphics/mpfavorite.png" alt="Demo image" id="field" height="50"  /></a>
        
		<a href="myprofile.php?view=info"><img class="iconhover" style="padding-right:10px;margin-left:3px;<?php if($view == 'info'){echo'background-color:white';} ?>
" src="graphics/mpinfo.png" alt="Demo image" id="field" height="50"/></a>
        
		<a href="myprofile.php?view=upload"><img class="iconhover" style="padding-right:10px;margin-left:3px;<?php if($view == 'upload'){echo'background-color:white';} ?>
" src="graphics/mpupload.png" alt="Demo image" id="field" height="50"  /></a>
        
		<a href="myprofile.php?view=followers"><img class="iconhover" style="padding-right:10px;margin-left:3px;<?php if($view == 'followers'){echo'background-color:white';} ?>
" src="graphics/mpfollowers.png" alt="Demo image" id="field" height="50" /></a>
        
		<a href="myprofile.php?view=following"><img class="iconhover" style="padding-right:10px;margin-left:3px;<?php if($view == 'following'){echo'background-color:white';} ?>
" src="graphics/mpfollowing.png" alt="Demo image" id="field" height="50" /></a>
        
		<a href="myprofile.php?view=messages"><img class="iconhover" style="padding-right:10px;margin-left:3px;<?php if($view == 'messages'){echo'background-color:white';} ?>
" src="graphics/mpmessaging.png" alt="Demo image" id="field" height="50" /></a>
        
        <a href="myprofile.php?view=settings"><img class="iconhover" style="padding-right:10px;margin-left:3px;<?php if($view == 'settings'){echo'background-color:white';} ?>
" src="graphics/mpsettings.png" alt="Demo image" id="field" height="50" /></a>
	</div>
    
</div></div>
</div> <!--end of 3rd row of divs-->        
   
        
                       
        
 <!--ALL THE VIEWS FOR ICONS-->       
        <!--WHAT IS BEING VIEWED-->
		<div class="grid_24">
			
<?php

if ($view == 'info') { //if they are on the info tab

if($age == 0) {
$age = "N/A";
}

echo'<div class="grid_10 push_4" style="font-family: arial; font-size: 16px; margin-top: -130px; width: 780px; line-height: 25px;">
<div class="well">
Age: ', $age,' 
<br />
<br />
Gender: ', $gender,'
<br />
<br />
From: ', $location,'
<br />
<br />
Camera: ', $camera,'
<br />
<br />
Facebook Page: <a href="',$facebookpage,'">',$facebookpage,'</a>
<br />
<br />
Twitter Account: <a href="',$twitteraccount,'">',$twitteraccount,'</a>
<br />
<br />
Quote: ', $quote,'
<br />
<br />
Bio: ', $bio,'
<br />
<br />
<a href="myprofile.php?view=editinfo"><button class="btn btn-primary">Edit Info</button></a>
</div>
</div>';
}


else if ($view == 'notifications') {  

$emailquery=("SELECT following FROM userinfo WHERE emailaddress ='$emailaddress'");
$followresult=mysql_query($emailquery);
$followinglist=mysql_result($followresult, 0, "following");

$notsquery = "SELECT * FROM newsfeed WHERE (owner = '$emailaddress' AND emailaddress != '$emailaddress') OR following = '$emailaddress' ORDER BY id DESC";
$notsresult = mysql_query($notsquery);
$numnots = mysql_num_rows($notsresult);

//DECIDE WHICH NOTIFICATIONS TO WHITEN (ONES ALREADY CLICKED ON)
$unhighlightquery = "SELECT * FROM userinfo WHERE emailaddress = '$emailaddress'";
$unhighlightqueryrun = mysql_query($unhighlightquery);
$whitenlist=mysql_result($unhighlightqueryrun, 0, "unhighlight");

echo '<div class="grid_15 prefix_6" style="margin-top: -380px;margin-left:-40px;">';

echo'<div style="color:black;font-size:18px;"><b>Notifications:</b></div><br />';

for ($iii=1; $iii <= 20; $iii++) {
$notsarray = mysql_fetch_array($notsresult);
$firstname = $notsarray['firstname'];
$lastname = $notsarray['lastname'];
$fullname = $firstname . " " . $lastname;
$fullname = ucwords($fullname);
$type = $notsarray['type'];
$id = $notsarray['id'];

//SEARCH IF ID IS IN UNHIGHLIGHT LIST
$search_string = $whitenlist;
$regex = $id;
$match=strpos($whitenlist,$regex);

if($match < 1) {
if($type == "comment") {
$caption = $notsarray['caption'];
$source= $notsarray['source'];
$newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
echo'<a style="text-decoration:none" href="fullsize.php?image=',$source,'&id=',$id,'"><div id="photoshadowhighlight"><img src="graphics/newsfeedcomment.png" height="60" width="60" />&nbsp;<img src="http://www.photorankr.com/',$newsource,'" height="60" width="60" />&nbsp;<span style="color:white">',$fullname,' commented on your photo</span></div></a><br /><br />';
}

elseif($type == "fave") {
$caption = $notsarray['caption'];
$source= $notsarray['source'];
$newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
echo'<a style="text-decoration:none" href="fullsize.php?image=',$source,'&id=',$id,'"><div id="photoshadowhighlight"><img src="graphics/newsfeedfavorite.png" height="60" width="60" />&nbsp;<img src="http://www.photorankr.com/',$newsource,'" height="60" width="60" />&nbsp;<span style="color:white">',$fullname,' favorited your photo</span></div></a><br /><br />';
}

elseif($type == "trending") {
$caption = $notsarray['caption'];
$source= $notsarray['source'];
$newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
echo'<a style="text-decoration:none" href="fullsize.php?image=',$source,'&id=',$id,'"><div id="photoshadowhighlight"><img src="graphics/newsfeedtrending.png" height="60" width="60" />&nbsp;<img src="http://www.photorankr.com/',$newsource,'" height="60" width="60" />&nbsp;<span style="color:white">Your photo is now trending</span></div></a><br /><br />';
}

elseif($type == "follow") {
$caption = $notsarray['caption'];
$followeremail= $notsarray['emailaddress'];
$newaccount = "SELECT * FROM userinfo WHERE emailaddress = '$followeremail'";
$accountresult = mysql_query($newaccount); 
$accountrow = mysql_fetch_array($accountresult);
$ownerid = $accountrow['user_id'];
$profilepic = $accountrow['profilepic'];
if($profilepic4 == "") {
$profilepic = "profilepics/default_profile.jpg";
}
echo'<a style="text-decoration:none" href="viewprofile.php?u=',$ownerid,'&id=',$id,'"><div id="photoshadowhighlight"><img src="graphics/newsfeednewfollower.png" height="60" width="60" />&nbsp;<img src="',$profilepic,'" height="60" width="60" />&nbsp;<span style="color:white">',$fullname,' is now following your photography</span></div></a><br /><br />';
}
} //end if statement

elseif($match > 0) {
if($type == "comment") {
$caption = $notsarray['caption'];
$source= $notsarray['source'];
$newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
echo'<a style="text-decoration:none" href="fullsize.php?image=',$source,'&id=',$id,'"><div id="photoshadow"><img src="graphics/newsfeedcomment.png" height="60" width="60" />&nbsp;<img src="http://www.photorankr.com/',$newsource,'" height="60" width="60" />&nbsp;',$fullname,' commented on your photo</div></a><br /><br />';
}

elseif($type == "fave") {
$caption = $notsarray['caption'];
$source= $notsarray['source'];
$newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
echo'<a style="text-decoration:none" href="fullsize.php?image=',$source,'&id=',$id,'"><div id="photoshadow"><img src="graphics/newsfeedfavorite.png" height="60" width="60" />&nbsp;<img src="http://www.photorankr.com/',$newsource,'" height="60" width="60" />&nbsp;',$fullname,' favorited your photo</div></a><br /><br />';
}

elseif($type == "trending") {
$caption = $notsarray['caption'];
$source= $notsarray['source'];
$newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
echo'<a style="text-decoration:none" href="fullsize.php?image=',$source,'&id=',$id,'"><div id="photoshadow"><img src="graphics/newsfeedtrending.png" height="60" width="60" />&nbsp;<img src="http://www.photorankr.com/',$newsource,'" height="60" width="60" />&nbsp;Your photo is now trending</div></a><br /><br />';
}

elseif($type == "follow") {
$caption = $notsarray['caption'];
$followeremail= $notsarray['emailaddress'];
$newaccount = "SELECT * FROM userinfo WHERE emailaddress = '$followeremail'";
$accountresult = mysql_query($newaccount); 
$accountrow = mysql_fetch_array($accountresult);
$ownerid = $accountrow['user_id'];
$profilepic = $accountrow['profilepic'];
if($profilepic4 == "") {
$profilepic = "profilepics/default_profile.jpg";
}
echo'<a style="text-decoration:none" href="viewprofile.php?u=',$ownerid,'&id=',$id,'"><div id="photoshadow"><img src="graphics/newsfeednewfollower.png" height="60" width="60" />&nbsp;<img src="',$profilepic,'" height="60" width="60" />&nbsp;',$fullname,' is now following your photography</div></a><br /><br />';
}
} //end ifelse statement

} //end of for loop
echo'</div>';
} //end of view



else if ($view == 'news') {



//RECOMMENDATIONS

$useremail = $_SESSION['email'];

echo'<div class="grid_4 pull_3" style="margin-top:30px;">
<div style="font-size:16px;padding-left:27px;">Recommendations:</div><br />';

//PHOTOS THEY MIGHT LIKE
//find out all of the photos they have ever favorited
	$favesquery = "SELECT faves from userinfo WHERE emailaddress='$useremail' LIMIT 1";
	$favesresult = mysql_query($favesquery);
	$faveslistowner = mysql_result($favesresult, 0, "faves");
	
	//select all the photos they have ever favorited
	$favesquery = "SELECT maintags, singlecategorytags, singlestyletags FROM photos WHERE source IN($faveslistowner)";
	$favesresult = mysql_query($favesquery);
	$favesnumber = mysql_num_rows($favesresult);

	//if they actually had favorites
	if($favesnumber != 0) {	
		//loop through the results to create a variable which holds all tags for all the photos they have ever favorited
		$favetags = "";
		for($iii=0; $iii < $favesnumber; $iii++) {
			$favetags .= mysql_result($favesresult, $iii, "maintags");
			$favetags .= mysql_result($favesresult, $iii, "singlecategorytags");
			$favetags .= mysql_result($favesresult, $iii, "singlestyletags");		
		}	

		//now select all of the photos with similar tags that weren't favorited by them
		$favesquery = "SELECT source, MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$favetags') AS matching FROM photos WHERE MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$favetags') AND source NOT IN($faveslistowner) ORDER BY RAND() LIMIT 4";
		$favesresult = mysql_query($favesquery);
	}
	//otherwise they had no faves so pick four random photos
	else {	
		$favesquery = "SELECT source FROM photos ORDER BY RAND() LIMIT 4";
		$favesresult = mysql_query($favesquery);
	}

	//see if they had enough information to display photos
	if(mysql_num_rows($favesresult) >= 4) {
		for($iii=0; $iii < 4; $iii++) {
        $image = mysql_result($favesresult, $iii, "source");
        $image2 = str_replace("userphotos/","userphotos/thumbs/", $image);
        list($width, $height) = getimagesize($image);
        $imgratio = $height / $width;
        $heightls = $height / 4;
        $widthls = $width / 4;
        $query7 =  mysql_query("SELECT caption FROM photos WHERE source = '$image'");
        $captionarray = mysql_fetch_array($query7);
        $caption = $captionarray['caption'];
        $caption = (strlen($caption) > 30) ? substr($caption,0,27). "&#8230;" : $caption;
			echo '<div class="photoshadow" style="height:190px;width:190px;background-color:white;text-align:center;font-size:13px;margin-top:20px;"><a style="text-decoration:none;" href="fullsize.php?image=',$image,'"><img src="',$image2,'" height="170" width="190" /><br />"',$caption,'"</a></div>';
		}
	} //end of photos you might like
    
    
//PHOTOGRAPHERS THEY MIGHT LIKE

	//select all of the people they are following
	$followingquery = "SELECT following FROM userinfo WHERE emailaddress='$useremail' LIMIT 1";
	$followingresult = mysql_query($followingquery);
	$followinglistowner = mysql_result($followingresult, 0, "following");

	//select all the people they are following who aren't themselves
	$str = mysql_real_escape_string("%'%',%'%',%'%',%'%'%',%'%'%");
	$followingquery = "SELECT following FROM userinfo WHERE emailaddress IN($followinglistowner) AND emailaddress NOT IN('$useremail') AND following LIKE('" . $str . "') ORDER BY RAND() LIMIT 1";
	$followingresult = mysql_query($followingquery) or die(mysql_error());
	$followinglist = mysql_result($followingresult, 0, "following");
	$followingnumber = mysql_num_rows($followingresult);
	
	//if they aren't yet following anyone, just get four random photographers
	if($followingnumber == 0) {
		$displayquery = "SELECT firstname, lastname, profilepic FROM userinfo ORDER BY RAND() LIMIT 4";
		$displayresult = mysql_query($displayquery);
	}
	//else they are following people so go ahead with the original procedure
	else {
		$displayquery = "SELECT firstname, lastname, profilepic,user_id FROM userinfo WHERE emailaddress IN($followinglist) AND emailaddress NOT IN('$useremail', $followinglistowner, 'support@photorankr.com') ORDER BY RAND() LIMIT 4";		
		$displayresult = mysql_query($displayquery) or die(mysql_error());
        $numdisplayresult = mysql_num_rows($displayresult);
	}
	
	//see if we have enough information to even display this 
		//loop through the people, printing out their name and profile picture
        echo'<br /><br /><div style="font-size:16px;padding-left:40px;">Photographers:</div><br />';
        
		for($iii=0; $iii < 4; $iii++) {
			$name = mysql_result($displayresult, $iii, "firstname") . " " . mysql_result($displayresult, $iii, "lastname");
			$profilepic = mysql_result($displayresult, $iii, "profilepic");
            $profileid = mysql_result($displayresult, $iii, "user_id");
            
            if($name == '' || $pofilepic == ''){
            $somequery = mysql_query("SELECT firstname,lastname,profilepic,user_id FROM userinfo WHERE profilepic != 'http://www.photorankr.com/profilepics/default_profile.jpg' && firstname != 'PhotoRankr' ORDER BY RAND()");
            $name = mysql_result($somequery, $iii, "firstname") . " " . mysql_result($somequery, $iii, "lastname");
			$profilepic = mysql_result($somequery, $iii, "profilepic");
            $profileid = mysql_result($somequery, $iii, "user_id");
             } 
            
			echo '<div class="photoshadow" style="height:190px;width:190px;background-color:white;text-align:center;font-size:13px;margin-top:20px;"><a style="text-decoration:none;" href="viewprofile.php?u=',$profileid,'"><img src="',$profilepic,'" height="170" width="190"/>',$name,'<br /></a></div>';
		}
	

    echo'</div>'; //end of 24 grid


//PHOTOSTREAM PHOTOS QUERY
$emailquery=("SELECT following FROM userinfo WHERE emailaddress ='$email'");
$followresult=mysql_query($emailquery);
$followlist=mysql_result($followresult, 0, "following");
$followrow=mysql_fetch_array($followresult);
$following=$followrow['following'];

$newsfeedquery = "SELECT * FROM newsfeed ORDER BY id DESC";
$newsfeedresult = mysql_query($newsfeedquery);

$maxwidth = 400;
       


//GET NEWS VIEW
if(isset($_GET['s'])){
		$style = $_GET['s'];
	}

echo '<div class="grid_12" style="display:inline;padding-left:10px;margin-top:-130px;">
  <a class="btn btn-primary" style="text-decoration:none;" href="http://www.photorankr.com/myprofile.php?view=news">Grid View</a>&nbsp;&nbsp;&nbsp;
  <a class="btn btn-primary" style="text-decoration:none;" href="http://www.photorankr.com/myprofile.php?view=news&s=p">Photostream</a>&nbsp;&nbsp;&nbsp;
  <a style="display:inline;color:#1a618a;" href="http://surveyanalytics.com/t/AIwvtZNop8">Please Provide Feedback</a>

 </div>'; 


echo'<div class="grid_24 push_3" id="thepics" style="margin-top:-1960px;">';

/* if($followlist == "support@photorankr.com") {
echo'Follow people by clicking the <button class="btn btn-primary">Follow</button> button on someones profile';
} */

        
for($iii=1; $iii <= 80; $iii++) {
    $newsrow = mysql_fetch_array($newsfeedresult);
    $newsemail = $newsrow['emailaddress'];    
    $owner = $newsrow['owner'];
    $emailfollowing = $newsrow['following'];
    $id = $newsrow['id'];
    $type = $newsrow['type'];
    $isfollowing = strpos($followlist,$newsemail);
    $isfollowing2 = strpos($followlist,$owner);
    $isfollowing3 = strpos($followlist,$emailfollowing);
    
    //GRID VIEW (DEFAULT)
    
    if ($style=='') {
    if ($type == "campaign") {
    $photoid = $newsrow['source'];
	$caption = $newsrow['caption'];
    $quotequery = mysql_query("SELECT quote FROM campaigns WHERE id = '$photoid'");
    $quote = mysql_result($quotequery,0,'quote');
    $phrase = 'New Campaign: (Reward: $'. $quote .') "'. $caption . '"';
    $phrase2 = (strlen($phrase) > 56) ? substr($phrase,0,53). " &#8230;" : $phrase;
    
    echo '<div class="grid_3 push_1 fPic photoshadow" id="',$id,'" style="margin-top:30px;width:250px;height:295px;"> 
    <a style="text-decoration:none" href="http://www.photorankr.com/campaignphotos.php?id=',$photoid,'"><img onmousedown="return false" oncontextmenu="return false;" src="graphics/newsfeedcampaignicon.png" width="250" height="250" />
    <br /><div style="margin-top:5px;width:50px;height:0px;padding-left:2px;"><img src="graphics/smallcampaignicon.png" height="35" width="35" /></div><div style="color:#333;font-size:14px;font-family:arial,helvetica neue;padding-left:40px;text-align:left;">
    ',$phrase2,'</div></a>';
    echo '</div>';   
    }
        }
        
        
    elseif ($style=='p') {
    if ($type == "campaign") {
    $photoid = $newsrow['source'];
	$caption = $newsrow['caption'];
    $phrase = 'New Campaign: "' . $caption . '"';
    $phrase2 = (strlen($phrase) > 56) ? substr($phrase,0,53). " &#8230;" : $phrase;
    
    echo '<div class="grid_9 push_3 fPic photoshadow4" id="',$id,'" style="width:600px; height:',$height,'+30px; margin-top:30px; margin-left:15px;overflow: hidden;">
    <a href="http://www.photorankr.com/campaignphotos.php?id=',$photoid,'"><img onmousedown="return false" oncontextmenu="return false;" src="graphics/newsfeedcampaignicon.png" height=', $height, 'px width="600px" /></a>
    <br /><div style="margin-top:5px;color:#333;font-size:17px;font-family:arial,helvetica neue;padding-bottom:3px;"><img src="graphics/smallcampaignicon.png" height="50" width="50" />
    ',$phrase2,'</div>';
    echo '</div>';  
    }
        }

    
    if ($isfollowing !== FALSE OR $isfollowing2 !== FALSE OR $isfollowing3 !== FALSE) {
    
    if ($style=='') {
             
    if ($type == "photo") {
	$image = $newsrow['source'];
    $imagenew=str_replace("userphotos/","userphotos/medthumbs/", $image);
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
    $phrase = $ownerfull . " uploaded " . '"' . $caption . '"';
    $phrase2 = (strlen($phrase) > 56) ? substr($phrase,0,53). " &#8230;" : $phrase;
    
    echo '<div class="grid_3 push_1 fPic photoshadow" id="',$id,'" style="margin-top:30px;width:250px;height:295px;"> 
    <a style="text-decoration:none" href="http://www.photorankr.com/fullsize.php?image=',$image,'"><img onmousedown="return false" oncontextmenu="return false;" src="http://www.photorankr.com/',$imagenew,'" width="250" height="250" />
    <br /><div style="margin-top:5px;width:50px;height:0px;padding-left:2px;"><img src="graphics/newsfeedarrow.png" height="35" width="35" /></div><div style="color:#333;font-size:14px;font-family:arial,helvetica neue;padding-left:40px;text-align:left;">
    ',$phrase2,'</div></a>';
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
    $imagenew=str_replace("userphotos/","userphotos/medthumbs/", $image);
	$caption = $newsrow['caption'];
	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    $fullname = $firstname . " " . $lastname;
    $phrase = $fullname . " favorited " . '"' . $caption . '"' . " by " . $ownerfull;
    $phrase2 = (strlen($phrase) > 56) ? substr($phrase,0,53). " &#8230;" : $phrase;
    
    echo '<div class="grid_3 push_1 fPic photoshadow" id="',$id,'" style="margin-top:30px;width:250px;height:295px;"> 
    <a style="text-decoration:none" href="http://www.photorankr.com/fullsize.php?image=',$image,'"><img onmousedown="return false" oncontextmenu="return false;" src="http://www.photorankr.com/',$imagenew,'" width="250" height="250" />
    <br /><div style="margin-top:5px;width:50px;height:0px;padding-left:2px;"><img src="graphics/newsfeedfavorite.png" height="35" width="35" /></div><div style="color:#333;font-size:14px;font-family:arial,helvetica neue;padding-left:40px;text-align:left;">
    ',$phrase2,'</div></a>';
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
    $imagenew=str_replace("userphotos/","userphotos/medthumbs/", $image);
	$caption = $newsrow['caption'];
	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    $phrase = '"' . $caption . '"' . " by " . $ownerfull . " is now trending";
    $phrase2 = (strlen($phrase) > 56) ? substr($phrase,0,53). " &#8230;" : $phrase;
    
    echo '<div class="grid_3 push_1 fPic photoshadow" id="',$id,'" style="margin-top:30px;width:250px;height:295px;"> 
    <a style="text-decoration:none" href="http://www.photorankr.com/fullsize.php?image=',$image,'"><img onmousedown="return false" oncontextmenu="return false;" src="http://www.photorankr.com/',$imagenew,'" width="250" height="250" />
    <br /><div style="margin-top:5px;width:50px;height:0px;padding-left:2px;"><img src="graphics/newsfeedtrending.png" height="35" width="35" /></div><div style="color:#333;font-size:14px;font-family:arial,helvetica neue;padding-left:40px;text-align:left;">
    ',$phrase2,'</div></a>';
    echo '</div>';   
    }
    
    elseif ($type == "follow") {
    $email4 = $newsrow['following'];
    $newaccount = "SELECT * FROM userinfo WHERE emailaddress = '$email4'";
    $accountresult = mysql_query($newaccount); 
    $accountrow = mysql_fetch_array($accountresult);
    $profilepic5 = $accountrow['profilepic'];
    $ownerid = $accountrow['user_id'];
    $ownerfirst = $accountrow['firstname'];
    $ownerlast = $accountrow['lastname'];
    $firstname = $newsrow['firstname'];
    $firstname = ucwords($firstname);
    $lastname = $newsrow['lastname'];
    $lastname = ucwords($lastname);
    $owner = $newsrow['owner'];
    $owner = ucwords($owner);
    if($profilepic5 == "") {
    $profilepic5 = "profilepics/default_profile.jpg";
    }

 echo '<div class="grid_3 push_1 fPic photoshadow" id="',$id,'" style="margin-top:30px;width:250px;height:295px;"> 
    <a style="text-decoration:none" href="http://www.photorankr.com/viewprofile.php?u=',$ownerid,'"><img src="',$profilepic5,'" width="250" height="250" />
    <br /><div style="margin-top:5px;width:50px;height:0px;padding-left:2px;"><img src="graphics/newsfeednewfollower.png" height="35" width="35" /></div><div style="color:#333;font-size:14px;font-family:arial,helvetica neue;padding-left:40px;text-align:left;">
    ', $firstname, ' ',$lastname,' is now following ',$owner,'</div></a>';
    echo '</div>'; 

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
    $imagenew=str_replace("userphotos/","userphotos/medthumbs/", $image);
    $fullname = $firstname . " " . $lastname;
    list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    $phrase = $fullname . " commented on " . $ownerfull . "'s photo";
    $phrase2 = (strlen($phrase) > 56) ? substr($phrase,0,53). " &#8230;" : $phrase;
    
    echo '<div class="grid_3 push_1 fPic photoshadow" id="',$id,'" style="margin-top:30px;width:250px;height:295px;"> <a style="text-decoration:none" href="http://www.photorankr.com/fullsize.php?image=',$image,'"><img onmousedown="return false" oncontextmenu="return false;" src="http://www.photorankr.com/',$imagenew,'" width="250" height="250" />
    <br /><div style="margin-top:5px;width:50px;height:0px;padding-left:2px;"><img src="graphics/newsfeedcomment.png" height="35" width="35" /></div><div style="color:#333;font-size:14px;font-family:arial,helvetica neue;padding-left:40px;text-align:left;">
    ',$phrase2,'</div>';
    echo '</div></a>';  
    }    
    } //end of grid view


    //PHOTOSTREAM VIEW 
    if ($style=='p') {
    
    echo'<div class="push_1" style="padding-left:10px">';

    if ($type == "photo") {
	$image = $newsrow['source'];
    $imagenew=str_replace("userphotos/","userphotos/medthumbs/", $image);
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
    echo '<div class="grid_9 push_2 fPic photoshadow4" id="',$id,'" style="width:600px; height:',$height,'+30px; margin-top:30px; overflow: hidden;">
    <a href="http://www.photorankr.com/fullsize.php?image=',$image,'"><img onmousedown="return false" oncontextmenu="return false;" src="http://www.photorankr.com/',$imagenew,'" height=', $height, 'px width="600px" /></a>
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
    $imagenew=str_replace("userphotos/","userphotos/medthumbs/", $image);
	$caption = $newsrow['caption'];
	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    $fullname = $firstname . " " . $lastname;
    echo '<div class="grid_9 push_2 fPic photoshadow4" id="',$id,'" style="width:600px; height:',$height,'+30px; margin-top:30px; overflow: hidden;">
    <a href="http://www.photorankr.com/fullsize.php?image=',$image,'"><img onmousedown="return false" oncontextmenu="return false;" src="http://www.photorankr.com/',$imagenew,'" height=', $height, 'px width="600px" /></a>
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
    $imagenew=str_replace("userphotos/","userphotos/medthumbs/", $image);
	$caption = $newsrow['caption'];
	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    echo '<div class="grid_9 push_2 fPic photoshadow4" id="',$id,'" style="width:600px; height:',$height,'+30px; margin-top:30px; overflow: hidden;">
    <a href="http://www.photorankr.com/fullsize.php?image=',$image,'"><img onmousedown="return false" oncontextmenu="return false;" src="http://www.photorankr.com/',$imagenew,'" height=', $height, 'px width="600px" /></a>
    <br /><div style="margin-top:5px;color:#333;font-size:17px;font-family:arial,helvetica neue;padding-bottom:3px;"><img src="graphics/newsfeedtrending.png" height="50" width="50" />
    "',$caption,'" by ',$ownerfull,' is now trending</div>';
    echo '</div>';   
    }
    
    elseif ($type == "follow") {
    $email4 = $newsrow['following'];
    $newaccount = "SELECT * FROM userinfo WHERE emailaddress = '$email4'";
    $accountresult = mysql_query($newaccount); 
    $accountrow = mysql_fetch_array($accountresult);
    $profilepic5 = $accountrow['profilepic'];
    $ownerid = $accountrow['user_id'];
    $ownerfirst = $accountrow['firstname'];
    $ownerlast = $accountrow['lastname'];
    $firstname = $newsrow['firstname'];
    $firstname = ucwords($firstname);
    $lastname = $newsrow['lastname'];
    $lastname = ucwords($lastname);
    $owner = $newsrow['owner'];
    $owner = ucwords($owner);
    if($profilepic5 == "") {
    $profilepic5 = "profilepics/default_profile.jpg";
    }
    echo '<a style="text-decoration:none" href="http://www.photorankr.com/viewprofile.php?u=',$ownerid,'"><div class="grid_9 push_2 fPic photoshadow4" id="',$id,'" style="width:600px; height:48px; margin-top:30px; overflow: hidden;color:#333;font-size:17px;font-family:arial,helvetica neue;padding-bottom:3px;"><img src="graphics/newsfeednewfollower.png" height="50" width="50" />
<img src="',$profilepic5,'" height="50" width="50" />&nbsp;&nbsp;&nbsp;&nbsp;', $firstname, ' ',$lastname,' is now following ',$owner,'</div></a><br />';
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
    $imagenew=str_replace("userphotos/","userphotos/medthumbs/", $image);
    $fullname = $firstname . " " . $lastname;
    list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    echo '<div class="grid_9 push_2 fPic photoshadow4" id="',$id,'" style="width:600px; height:',$height,'+30px; margin-top:30px; overflow: hidden;"><a style="text-decoration:none" href="http://www.photorankr.com/fullsize.php?image=',$image,'">
    <img onmousedown="return false" oncontextmenu="return false;" src="http://www.photorankr.com/',$imagenew,'" height=', $height, 'px width="600px" /></a>
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
        
    echo'</div>';

    } // end of $isfollowing to make sure in following list
    

    } //end of photostream view
    
    } //end for loop
 
echo '</div>';
//end grid_24 div

echo'</div>';


if ($style=='') {

echo'
<div id="loadMoreNewsfeedGrid" style="display: none; text-align: center; margin-top: 25px;">Loading...</div>';


echo '<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMorePics").show();
				$.ajax({
					url: "loadMoreNewsfeedGrid.php?lastPicture=" + $(".fPic:last").attr("id") + "&first=', $firstname, ' + &last=', $lastname, ' + &email=', $email, '",
					success: function(html) {
						if(html) {
							$("#thepics").append(html);
							$("div#loadMoreNewsfeedGrid").hide();
						}
					}
				});
				last = $(".fPic:last").attr("id");
			}
		}
	});
</script>';

echo'
</div>';       
            }


elseif ($style=='p') {

echo'
<div id="loadMoreNewsfeedPS" style="display: none; text-align: center; margin-top: 25px;">Loading...</div>';


echo '<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMorePics").show();
				$.ajax({
					url: "loadMoreNewsfeedPS.php?lastPicture=" + $(".fPic:last").attr("id") + "&first=', $firstname, ' + &last=', $lastname, ' + &email=', $email, '",
					success: function(html) {
						if(html) {
							$("#thepics").append(html);
							$("div#loadMoreNewsfeedPS").hide();
						}
					}
				});
				last = $(".fPic:last").attr("id");
			}
		}
	});
</script>';

echo'
</div>';  
            }


} //end of view




else if ($view == 'editinfo') { //if they are on the edit info tab
echo '<div class="grid_14 push_5" style="margin-top: -130px;margin-left:-40px;font-size:16px;">';
	//see if they have submitted the form
	$action = htmlentities($_GET['action']);
	if ($action == 'submit') {

		//GET UPDATED PROFILE INFORMATION
        if(isset($_POST['firstname'])) {$firstname=mysql_real_escape_string($_POST['firstname']); }
        if(isset($_POST['lastname'])) {$lastname=mysql_real_escape_string($_POST['lastname']); }
		if(isset($_POST['age'])) {$age=mysql_real_escape_string($_POST['age']); }
		if(isset($_POST['gender'])) {$gender=mysql_real_escape_string($_POST['gender']); }
		if(isset($_POST['location'])) {$location = mysql_real_escape_string($_POST['location']);}
		if(isset($_POST['camera'])) {$camera=mysql_real_escape_string($_POST['camera']);}
		if(isset($_POST['facebookpage'])) {$facebookpage=mysql_real_escape_string($_POST['facebookpage']);}
		if(isset($_POST['twitteraccount'])) {$twitteraccount=mysql_real_escape_string($_POST['twitteraccount']);}
        if(isset($_POST['quote'])) {$quote=mysql_real_escape_string($_POST['quote']);}
		if(isset($_POST['bio'])) {$bio=mysql_real_escape_string($_POST['bio']);}
		if(isset($_POST['password'])) {$password=mysql_real_escape_string($_POST['password']);}
		if(isset($_POST['confirmpassword'])) {$confirmpassword=mysql_real_escape_string($_POST['confirmpassword']);}

      $singlestyletags = $_POST['singlestyletags']; 
          $singlecategorytags = $_POST['singlecategorytags'];

          //Concatenate single photo box tags
          $numbersinglestyletags = count($singlestyletags);
        for($i=0; $i < $numbersinglestyletags; $i++)
        {
            $singlestyletags2 = $singlestyletags2 . " " . mysql_real_escape_string($singlestyletags[$i]) . " ";
        }
          $numbersinglecategorytags = count($singlecategorytags);
        for($i=0; $i < $numbersinglecategorytags; $i++)
        {
            $singlecategorytags2 = $singlecategorytags2 . " " . mysql_real_escape_string($singlecategorytags[$i]) . " ";
          }

          $viewLikes = $singlecategorytags2 . "  " . $singlestyletags2;
	
		//check if confirm password and password are same
		if ($confirmpassword != $password) {
			die('Your passwords did not match.');
		}	
		
		//require files that will help with picture uploading and thumbnail creation/display
		require 'config.php';
		require 'functions.php';	
	
		//move the file
		if(isset($_FILES['file'])) {  
  
    			if(preg_match('/[.](jpg)|(jpeg)|(gif)|(png)|(JPG)$/', $_FILES['file']['name'])) {  
        			$filename = $_FILES['file']['name'];  
				$newfilename=str_replace(" ","",$filename);
 				$newfilename=str_replace("#","",$newfilename);		
    				$newfilename=str_replace("&","",$newfilename);
				$newfilename=strtolower($newfilename);
    				$newfilename=str_replace("?","",$newfilename);	
    				$newfilename=str_replace("'","",$newfilename);
    				$newfilename=str_replace("#","",$newfilename);
    				$newfilename=str_replace(":","",$newfilename);
    				$newfilename=str_replace("*","",$newfilename);
    				$newfilename=str_replace("<","",$newfilename);
    				$newfilename=str_replace(">","",$newfilename);
    				$newfilename=str_replace("(","",$newfilename);
    				$newfilename=str_replace(")","",$newfilename);
    				$newfilename=str_replace("^","",$newfilename);
   				$newfilename=str_replace("$","",$newfilename);
    				$newfilename=str_replace("@","",$newfilename);
    				$newfilename=str_replace("!","",$newfilename);
    				$newfilename=str_replace("+","",$newfilename);
    				$newfilename=str_replace("=","",$newfilename);
    				$newfilename=str_replace("|","",$newfilename);
   				$newfilename=str_replace(";","",$newfilename);
    				$newfilename=str_replace("[","",$newfilename);
    				$newfilename=str_replace("{","",$newfilename);
    				$newfilename=str_replace("}","",$newfilename);
    				$newfilename=str_replace("]","",$newfilename);
    				$newfilename=str_replace("~","",$newfilename);
    				$newfilename=str_replace("`","",$newfilename);
				$newfilename=str_replace("?","",$newfilename);
				/*if(preg_match('/[.](jpg)$/', $newfilename)) {  
            				$extension = ".jpg";
        			} else if (preg_match('/[.](gif)$/', $newfilename)) {  
            				$extension = ".gif"; 
        			} else if (preg_match('/[.](png)$/', $newfilename)) {  
            				$extension = ".png";  
        			}*/
        			
                    $time = time();
                    $newfilename = $time . $newfilename;
				$source = $_FILES['file']['tmp_name'];  
        			$profilepic = $path_to_profpic_directory . $newfilename; 
				//$profilepic = $path_to_profpic_directory . $firstname . $lastname . $extension;
  
        			move_uploaded_file($source, $profilepic);  
  
                    createprofthumbdim($profilepic);
        			createprofthumbnail($profilepic);
					chmod($profilepic, 0644);
    			}  
		}  
	
		//update the database with this new information
		if(isset($_POST['bio'])) {
			$infoupdatequery=("UPDATE userinfo SET firstname = '$firstname', lastname = '$lastname', age = '$age', gender = '$gender', location = '$location', camera = '$camera', facebookpage='$facebookpage', twitteraccount='$twitteraccount', quote='$quote', bio='$bio', profilepic='$profilepic', password='$password', viewLikes='$viewLikes' WHERE emailaddress='$email'");
		}
		else {
			$infoupdatequery=("UPDATE userinfo SET firstname = '$firstname', lastname = '$lastname', age = '$age', gender = '$gender', location = '$location', camera = '$camera', facebookpage='$facebookpage', twitteraccount='$twitteraccount', profilepic='$profilepic', password='$password', viewLikes='$viewLikes' WHERE emailaddress='$email'");
		}
		$infoupdateresult=mysql_query($infoupdatequery);

        mysql_close();
        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=myprofile.php?view=editinfo&action=saved">';
        exit();
	}
    else if($action == "saved") {
        echo '<h3>Profile Saved</h3><br />';
    }

echo
'<div class="infoPage2">
<span style="font-size:18px;">Edit your information below:</span>
<br />
<br />
<form action="', htmlentities($_SERVER['PHP_SELF']), '?view=editinfo&action=submit" method="post" enctype="multipart/form-data">
First Name: <input style="width:180px;height:25px;" type="text" name="firstname" value="', $firstname, '"/> 
<br />
<br />
Last Name: <input style="width:180px;height:25px;" type="text" name="lastname" value="', $lastname, '"/>
<br />
<br />
Age: <input style="width:180px;height:25px;" type="text" name="age" value="', $age, '"/>
<br />     
<br />
Gender:
';
if ($gender == 'Male') {
echo '<input type="radio" name="gender" value="Male" checked="checked" /> Male 
<input type="radio" name="gender" value="Female" /> Female';
}
else {
echo '<input type="radio" name="gender" value="Male" /> Male 
<input type="radio" name="gender" value="Female" checked="checked" /> Female';
}
echo '
<br />
<br />
Change Location: <input style="width:180px;height:25px;" type="text" name="location" value="', $location, '"/>
<br />
<br />
Change Camera: <input style="width:180px;height:25px;" type="text" name="camera" value="', $camera, '"/>
<br />
<br />
Change Facebook Page: <input style="width:180px;height:25px;" type="text" name="facebookpage" value="', $facebookpage, '"/>
<br />
<br />
Change Twitter Account: <input style="width:180px;height:25px;" type="text" name="twitteraccount" value="', $twitteraccount, '"/>
<br />
<br />
Edit Quote: 
<br />
<br />
<textarea style="width:500px" rows="2" cols="100" name="quote">',stripslashes($quote),'</textarea>
<br />
<br /> 
Edit Bio: 
<br />
<br />
<textarea style="width:500px" rows="6" cols="100" name="bio">',stripslashes($bio),'</textarea>
<br />
<br /> 
<br />
Change Password:<input type="password" style="width:180px;height:25px;"  name="password" value="', $password, '"/>
<br />
<br />
Confirm Password:<input type="password" style="width:180px;height:25px;"  name="confirmpassword" value="', $password, '"/>
<br />
<br />
Change Profile Picture: <input style="margin-top:20px" type="file"  name="file" value="', $profilepic, '"/>

<br/>
<br />';
if(htmlentities($_GET['action'])=="discover") {
  echo '<a style="text-decoration:none;" name="discover"><span class="label label-important" style="font-size:14px;">Add some preferences so you can use this feature</span></a><br /><br />';
}
echo '
Add your "Discover" viewing preferences:<br />
<span style="font-size:13px">(Selecting multiple values: Hold down command button if on mac, control button if on PC)</span>
<br /><br />
<select style="width:150px;height:150px;" multiple="multiple" name="singlestyletags[]">
    <option value="B&W">Black and White</option>
    <option value="Cityscape">Cityscape</option>
    <option value="Fisheye">Fisheye</option>
    <option value="HDR">HDR</option>
    <option value="Illustration">Illustration</option>
    <option value="InfraredUV">Infrared/UV</option>
    <option value="Landscape">Landscape</option>
    <option value="Long Exposure">Long Exposure</option>
    <option value="Macro">Macro</option>
    <option value="Miniature">Miniature</option>
    <option value="Monochrome">Monochrome</option>
    <option value="Motion Blur">Motion Blur</option>
    <option value="Night">Night</option>
    <option value="Panorama">Panorama</option>
    <option value="Photojournalism">Photojournalism</option>
    <option value="Portrait">Portrait</option>
    <option value="Stereoscopic">Stereoscopic</option>
    <option value="Time Lapse">Time Lapse</option>
    </select>
    <span style="padding-left:70px">
    <select style="width:150px;height:150px;" multiple="multiple" name="singlecategorytags[]">
    <option value="Advertising">Advertising</option>
    <option value="Aerial">Aerial</option>
    <option value="Animal">Animal</option>
    <option value="Architecture">Architecture</option>
    <option value="Astro">Astro</option>
    <option value="Aura">Aura</option>
    <option value="Automotive">Automotive</option>
    <option value="Botanical">Botanical</option>
    <option value="Candid">Candid</option>
    <option value="Commercial">Commercial</option>
    <option value="Corporate">Corporate</option>
    <option value="Documentary">Documentary</option>
    <option value="Fashion">Fashion</option>
    <option value="Fine Art">Fine Art</option>
    <option value="Food">Food</option>
    <option value="Historical">Historical</option>
    <option value="Industrial">Industrial</option>
    <option value="Musical">Musical</option>
    <option value="Nature">Nature</option>
    <option value="News">News</option>
    <option value="Night">Night</option>
    <option value="People">People</option>
    <option value="Scenic">Scenic</option>
    <option value="Sports">Sports</option>
    <option value="Still Life">Still Life</option>
    <option value="Transportation">Transportation</option>
    <option value="Urban">Urban</option>
    <option value="War">War</option>
    </select><br /><br />
<button class="btn btn-primary" type="submit">Save Info</button>
</form>
</div>';

}
else if ($view == 'following') { //if they are on the following tab
//show them who the person who's profile this is is following

//Info Box
echo'
<div class="grid_4 pull_3 photoshadowreel" style="height:50px;width:200px;margin-top:10px;margin-left:1px;">
<div style="font-size:13px;text-align:center;margin-top:15px;"><span style="color:black;">Following</span> ',$numberfollowing,'</div>
</div>';


echo '<div class="grid_24 push_3" style="margin-top: -200px;">'; 
    
    
	for($iii = 0; $iii < $numberfollowing; $iii++) {
		$followingpic = mysql_result($followingresult, $iii, "profilepic");
		$followingfirst = mysql_result($followingresult, $iii, "firstname");
		$followinglast = mysql_result($followingresult, $iii, "lastname");
        $fullname = $followingfirst . " " . $followinglast;
        $fullname = ucwords($fullname);
        $followingid = mysql_result($followingresult, $iii, "user_id");
		
        
        echo '<a href="http://www.photorankr.com/viewprofile.php?u=' . $followingid . '"><div class="grid_3 push_1" id="photoshadow" style="margin-top:30px;width:183px;height:260px;">   
<img src="',$followingpic,'" width="183" height="180"/></a>&nbsp&nbsp&nbsp <a class="photonamelink2" href="http://www.photorankr.com/viewprofile.php?u=',$$followingid,'"><div style="margin-top:0px;font-size:15px;text-align:center;">',$fullname,'</div>
</a></div>';
        
	}

     echo'</div>';
}
else if ($view == 'followers') { //if they are on the followers tab
//show them who is following the person who's profile this is

echo '<div class="grid_24 push_3" style="margin-top: -150px;">'; 


	$followersquery="SELECT * FROM userinfo WHERE following LIKE '%$email%'";
	$followersresult=mysql_query($followersquery);
	$numberfollowers = mysql_num_rows($followersresult);
    
    
	for($iii = 0; $iii < $numberfollowers; $iii++) {
		$followerpic = mysql_result($followersresult, $iii, "profilepic");
		$followerfirst = mysql_result($followersresult, $iii, "firstname");
		$followerlast = mysql_result($followersresult, $iii, "lastname");
        $fullname = $followerfirst . " " . $followerlast;
        $fullname = ucwords($fullname);
        $followerid = mysql_result($followersresult, $iii, "user_id");
        
        echo '<a href="http://www.photorankr.com/viewprofile.php?first=' . $followerid . '"><div class="grid_3 push_1" id="photoshadow" style="margin-top:30px;width:183px;height:260px;">   
<img src="',$followerpic,'" width="183" height="180"/></a>&nbsp&nbsp&nbsp <a class="photonamelink2" href="http://www.photorankr.com/viewprofile.php?u=',$followerid,'"><div style="margin-top:0px;font-size:15px;text-align:center;">',$fullname,'</div>
</a></div>';
	}

     echo '</div>';
}

else if ($view == 'upload') { //if they are on the upload tab
if(isset($_GET['cs'])) {
	$set=htmlentities($_GET['cs']); //see if they are looking at set view
    }
    
    
if($set == '') {

//select all sets associated with user email
$setsemail = $_SESSION['email'];
$setsquery = "SELECT * FROM sets WHERE owner = '$setsemail'";
$setsqueryrun = mysql_query($setsquery);
$setscount = mysql_num_rows($setsqueryrun);

echo '<div class="grid_14 push_6" style="margin-top: -130px;margin-left:-40px;">';

	//upload a photo
	if (htmlentities($_GET['action']) == "uploadsuccess") { 
    
            echo '<span class="label label-success" style="font-size: 16px;">Upload Successful!</span><br /><br />';

    }
    
        else if (htmlentities($_GET['action']) == "uploadfailure") {
        
            echo '<span class="label label-important" style="font-size: 16px;">Please fill out all required items.</span><br /><br />';
        
        }
    
	//display a spot to upload pictures to their gallery
	echo '
		<!--ALLOW USER TO UPLOAD PICTURES-->
	<div id="uploadPhoto">
	<span style="font-size:16px">Upload a Photo or <a href="myprofile.php?view=upload&cs=n">Create an Exhibit</a>:</span>
	<form action="upload_photo.php" method="post" enctype="multipart/form-data">
    <br />
    </div>
    <div class="well" style="font-size:15px;">
    <input type="file" name="file" />
	<input type="hidden" name="MAX_FILE_SIZE" value="1000000000" /> 
    <br />
	* Title:&nbsp;&nbsp;<input type="text" name="caption" />
    <br />
    <br />
    * Camera: <input type="text" value="',$camera,'" name="camera" />
	<br />
	<br />
    Location (city, state/province): <input type="text" value="',$location,'" name="location" />
    <br />
    <br />
    <span style="font-size:16px">Pick some tags (search words) that describe this photo: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="#" id="popover" class="btn btn-primary" rel="popover" data-content="Tags help us help you. By selecting various tags for your photos, we can make sure your photos are seen more often in search and on the discovery page. It helps ensure that your photos will always be seen." data-original-title="Why should I enter tags?">Why?</a>
    <script>  
    $(function ()  
    { $("#popover").popover();  
    });  
    </script>
    </span>
    <br />
    <br />
    <span style="font-size:13px">(Selecting multiple values: Hold down command button if on mac, control button if on PC)</span>
    <br />
    <br />
    <span style="font-size:16px">Style:<span style="padding-left:180px">Categories:</span></span>
    <br />
    <br />
    <select style="width:150px;height:150px;" multiple="multiple" name="singlestyletags[]">
    <option value="B&W">Black and White</option>
    <option value="Cityscape">Cityscape</option>
    <option value="Fisheye">Fisheye</option>
    <option value="HDR">HDR</option>
    <option value="Illustration">Illustration</option>
    <option value="InfraredUV">Infrared/UV</option>
    <option value="Landscape">Landscape</option>
    <option value="Long Exposure">Long Exposure</option>
    <option value="Macro">Macro</option>
    <option value="Miniature">Miniature</option>
    <option value="Monochrome">Monochrome</option>
    <option value="Motion Blur">Motion Blur</option>
    <option value="Night">Night</option>
    <option value="Panorama">Panorama</option>
    <option value="Photojournalism">Photojournalism</option>
    <option value="Portrait">Portrait</option>
    <option value="Stereoscopic">Stereoscopic</option>
    <option value="Time Lapse">Time Lapse</option>
    </select>
    <span style="padding-left:70px;">
    <select style="width:150px;height:150px;" multiple="multiple" name="singlecategorytags[]">
    <option value="Advertising">Advertising</option>
    <option value="Aerial">Aerial</option>
    <option value="Animal">Animal</option>
    <option value="Architecture">Architecture</option>
    <option value="Astro">Astro</option>
    <option value="Aura">Aura</option>
    <option value="Automotive">Automotive</option>
    <option value="Botanical">Botanical</option>
    <option value="Candid">Candid</option>
    <option value="Commercial">Commercial</option>
    <option value="Corporate">Corporate</option>
    <option value="Documentary">Documentary</option>
    <option value="Fashion">Fashion</option>
    <option value="Fine Art">Fine Art</option>
    <option value="Food">Food</option>
    <option value="Historical">Historical</option>
    <option value="Industrial">Industrial</option>
    <option value="Musical">Musical</option>
    <option value="Nature">Nature</option>
    <option value="News">News</option>
    <option value="Night">Night</option>
    <option value="People">People</option>
    <option value="Scenic">Scenic</option>
    <option value="Sports">Sports</option>
    <option value="Still Life">Still Life</option>
    <option value="Transportation">Transportation</option>
    <option value="Urban">Urban</option>
    <option value="War">War</option>
    </select>
    </span>
    <br />
    <br />
    Choose some of your own tags: <br /><br />
    <input style="width:80px;height:20px;" type="text" name="tag1" />
    <input style="width:80px;height:20px;" type="text" name="tag2" />
    <input style="width:80px;height:20px;" type="text" name="tag3" />
    <input style="width:80px;height:20px;" type="text" name="tag4" />
    <br />
    <br />
	Price:&nbsp;&nbsp;<select name="price">
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
	<br />
    <br />
    Copyright:&nbsp;
    <input type="radio" name="copyright" value="owner"/> ',$firstname,' ',$lastname,'&nbsp;&nbsp;&nbsp;
    <input type="radio" name="copyright" value="cc" /> Creative Commons<br />
    <br />
    Add to Exhibit:&nbsp;&nbsp;<select name="addtoset" onchange="showTags(this.value)">
    <option value="" style="display:none;">Choose an exhibit:</option>';
    for($iii=0; $iii < $setscount; $iii++) {
    $settitle = mysql_result($setsqueryrun, $iii, "title");
    echo'<option value="',$settitle,'">',$settitle,'</option>';
    }
    echo'
	</select>
    
	<br />
    <br />
    
    <div id="boxesappear"> </div>';
    
    echo'
    </div> 
            
<div class="panel">
<span style="font-size:14px;">*(Exif data will be automatically pulled)</span>
<br />
<br />
<p>
Focal Length: <input style="width:40px" type="text" value="', $focallength, '" name="focallength" /> mm
	<br />
	<br />
    Shutter Speed (e.g. 1/400): <input style="width:50px" type="text" value="', $shutterspeed, '" name="shutterspeed" /> second
	<br />
	<br />
    Aperture: f/ <input style="width:40px; height: 15px;" type="text" value="', $aperture, '" name="aperture" /> 
	<br />
	<br />
    Lens: <input type="text" value="', $lens, '" name="lens" /> 
	<br />
	<br />
    Filter(s): <input type="text" value="', $filter, '" name="filter" />
	<br />
	<br />
    <div style="line-height:22px;">
    About This Photograph:<textarea style="width:500px" rows="4" cols="60" name="about"></textarea></div> 
    <br />
</p>
</div>
 
<p class="flip">Optional Information</p>

	<br />
	<button type="submit" name="Submit" class="btn btn-success">Upload Now</button>
	</form>
    <br />
    <br />
    <p>(Depending on the size of your photo and internet connection, uploading should take about 1-30 seconds.
<br/>Supported file formats are .jpg, .gif, .png)</p><br /><br /><br /><br />
	</div>
	';
    }
 
elseif($set == 'n') {
echo '<div class="grid_14 push_6" style="margin-top: -130px;margin-left:-40px;">';
echo'
<span style="font-size:16px">Create Your New Exhibit:</span>
<br />
<span style="font-size:14px">(An exhibit is a grouping of specific photos)</span>
';

	if (htmlentities($_GET['ns']) == "success") { 
    echo'<br /><br /><span style="font-size: 20px;"><a href="myprofile.php?view=upload">Add photos to your new exhibit!</a></span><br />';
    }
    
    elseif (htmlentities($_GET['ns']) == "failure") { 
    echo'<br /><br /><span style="font-size: 20px;color:red;">Please fill out all fields!</span><br />';
    }
    
    elseif (htmlentities($_GET['ns']) == "name") { 
    echo'<br /><br /><span style="font-size: 20px;color:red;">You already have an exhibit titled this!</span><br />';
    }
    
    echo'
	<form action="create_set.php" method="post" enctype="multipart/form-data">
    <br />
    <br />
    <div class="well">
	<span style="font-size:16px">Title of exhibit:&nbsp;</span><input type="text" name="title" />
    <br />
    <br />
	<span style="font-size:16px">Pick 2 or more tags (search terms) that describe this exhibit:</span>
    <br />
    <span style="font-size:13px">(Hold down command button if on mac, control button if on PC)</span>
    <br />
    <br />
    <select multiple="multiple" name="maintags[]">
    <option value="Advertising">Advertising</option>
    <option value="Aerial">Aerial</option>
    <option value="Animal">Animal</option>
    <option value="Astro">Astro</option>
    <option value="Aura">Aura</option>
    <option value="Automotive">Automotive</option>
    <option value="B&W">B&W</option>
    <option value="Botanical">Botanical</option>
    <option value="Candid">Candid</option>
    <option value="Cityscape">Cityscape</option>
    <option value="Commercial">Commercial</option>
    <option value="Corporate">Corporate</option>
    <option value="Documentary">Documentary</option>
    <option value="Fashion">Fashion</option>
    <option value="Fine Art">Fine Art</option>
    <option value="Food">Food</option>
    <option value="HDR">HDR</option>
    <option value="Historical">Historical</option>
    <option value="Industrial">Industrial</option>
    <option value="Landscape">Landscape</option>
    <option value="Long Exposure">Long Exposure</option>
    <option value="Macro">Macro</option>
    <option value="Musical">Musical</option>
    <option value="Nature">Nature</option>
    <option value="News">News</option>
    <option value="Night">Night</option>
    <option value="Panorama">Panorama</option>
    <option value="People">People</option>
    <option value="Portrait">Portrait</option>
    <option value="Scenic">Scenic</option>
    <option value="Sports">Sports</option>
    <option value="Still Life">Still Life</option>
    <option value="Time Lapse">Time Lapse</option>
    <option value="Transportation">Transportation</option>
    <option value="Urban">Urban</option>
    <option value="War">War</option>
    </select>
    <br />
    <br />
    Choose some of your own tags: <br /><br />
    <input style="width:80px;height:20px;" type="text" name="settag1" />
    <input style="width:80px;height:20px;" type="text" name="settag2" />
    <input style="width:80px;height:20px;" type="text" name="settag3" />
    <input style="width:80px;height:20px;" type="text" name="settag4" />
<br />
<br />
<div style="line-height:22px;">
About this exhibit:<textarea style="width:500px" rows="4" cols="60" name="about"></textarea></div> 
<br />

<button type="submit" name="Submit" class="btn btn-success">Create Exhibit</button>
</form>
</div> <!--end of well-->
</div>';


echo'</div>';
}
          
} //end of all the upload view
    

elseif ($view == 'settings') {
    
$action = htmlentities($_GET['action']);

if ($action == 'savesettings') {
    
$emailcomment = mysql_real_escape_string($_POST['emailcomment']);
$emailreturncomment = mysql_real_escape_string($_POST['emailreturncomment']);
$emailfave = mysql_real_escape_string($_POST['emailfave']);		
$emailfollow = mysql_real_escape_string($_POST['emailfollow']);	

$settinglist = $emailcomment . $emailreturncomment . $emailfave . $emailfollow;

$settingquery = "UPDATE userinfo SET settings = '$settinglist' WHERE emailaddress='$email'";
$settingrun = mysql_query($settingquery);

//Grab what they have checked
$settingemail = $_SESSION['email'];
$settingquery = "SELECT * FROM userinfo WHERE emailaddress = '$settingemail'";
$settingqueryrun = mysql_query($settingquery);
$settinglist = mysql_result($settingqueryrun, 0, "settings");

echo'
<div class="grid_18 push_5" style="margin-top: -130px;margin-left:-40px;">
<span style="font-size:16px;">Notification Settings:</span>
<br />
<span class="label label-success" style="font-size: 16px;">Settings Saved</span><br /><br />
<form action="', htmlentities($_SERVER['PHP_SELF']), '?view=settings&action=savesettings" method="post" enctype="multipart/form-data">
<br />';
        
$setting_string = $settinglist;
$find = "emailcomment";
$foundsetting = strpos($setting_string,$find);
if($foundsetting > 0) {
echo'
<input type="checkbox" name="emailcomment" value=" emailcomment " checked />&nbsp;Receive an email when your photo is commented on<br /><br />'; }
else {
echo'
<input type="checkbox" name="emailcomment" value=" emailcomment " />&nbsp;Receive an email when your photo is commented on<br /><br />'; }

$find2 = "emailreturncomment";
$foundsetting2 = strpos($setting_string,$find2);
if($foundsetting2 > 0) {
echo'
<input type="checkbox" name="emailreturncomment" value=" emailreturncomment " checked />&nbsp;Receive an email when another photographer comments on a photo you also commented on<br /><br />'; }
else {
echo'
<input type="checkbox" name="emailreturncomment" value=" emailreturncomment " />&nbsp;Receive an email when another photographer comments on a photo you also commented on<br /><br />'; }

$find3 = "emailfave";
$foundsetting3 = strpos($setting_string,$find3);
if($foundsetting3 > 0) {
echo'
<input type="checkbox" name="emailfave" value=" emailfave " checked />&nbsp;Receive an email when another photographer favorites your photo<br /><br />'; }
else {
echo'
<input type="checkbox" name="emailfave" value=" emailfave " />&nbsp;Receive an email when another photographer favorites your photo<br /><br />'; }

$find4 = "emailfollow";
$foundsetting4 = strpos($setting_string,$find4);
if($foundsetting4 > 0) {
echo'
<input type="checkbox" name="emailfollow" value=" emailfollow " checked />&nbsp;Receive an email when someone follows your photography<br /><br />'; }
else {
echo'
<input type="checkbox" name="emailfollow" value=" emailfollow " />&nbsp;Receive an email when someone follows your photography<br /><br />'; }

echo'
<button type="submit" name="Submit" class="btn btn-success">Save Notification Settings</button>
</form>
</div>
';

}
    
else {
 
 
$settingemail = $_SESSION['email'];
$settingquery = "SELECT * FROM userinfo WHERE emailaddress = '$settingemail'";
$settingqueryrun = mysql_query($settingquery);
$settinglist = mysql_result($settingqueryrun, 0, "settings");

echo'
<div class="grid_18 push_4" style="margin-top: -130px;">
<span style="font-size:16px;">Notification Settings:</span>
<form action="', htmlentities($_SERVER['PHP_SELF']), '?view=settings&action=savesettings" method="post" enctype="multipart/form-data">
<br />';
        
$setting_string = $settinglist;
$find = "emailcomment";
$foundsetting = strpos($setting_string,$find);
if($foundsetting > 0) {
echo'
<input type="checkbox" name="emailcomment" value=" emailcomment " checked/>&nbsp;Receive an email when your photo is commented on<br /><br />'; }
else {
echo'
<input type="checkbox" name="emailcomment" value=" emailcomment " />&nbsp;Receive an email when your photo is commented on<br /><br />'; }

$find2 = "emailreturncomment";
$foundsetting2 = strpos($setting_string,$find2);
if($foundsetting2 > 0) {
echo'
<input type="checkbox" name="emailreturncomment" value=" emailreturncomment " checked />&nbsp;Receive an email when another photographer comments on a photo you also commented on<br /><br />'; }
else {
echo'
<input type="checkbox" name="emailreturncomment" value=" emailreturncomment " />&nbsp;Receive an email when another photographer comments on a photo you also commented on<br /><br />'; }

$find3 = "emailfave";
$foundsetting3 = strpos($setting_string,$find3);
if($foundsetting3 > 0) {
echo'
<input type="checkbox" name="emailfave" value=" emailfave " checked />&nbsp;Receive an email when another photographer favorites your photo<br /><br />'; }
else {
echo'
<input type="checkbox" name="emailfave" value=" emailfave " />&nbsp;Receive an email when another photographer favorites your photo<br /><br />'; }

$find4 = "emailfave";
$foundsetting4 = strpos($setting_string,$find4);
if($foundsetting4 > 0) {
echo'
<input type="checkbox" name="emailfollow" value=" emailfollow " checked />&nbsp;Receive an email when someone follows your photography<br /><br />'; }
else {
echo'
<input type="checkbox" name="emailfollow" value=" emailfollow " />&nbsp;Receive an email when someone follows your photography<br /><br />'; }

echo'
<button type="submit" name="Submit" class="btn btn-success">Save Notification Settings</button>
</form>
</div>
';

}

//Change cover photos

$actiontwo = htmlentities($_GET['actiontwo']);

if ($actiontwo == 'savecover') {

$coverphotos = $_POST['addthese'];
for($i=0; $i < 3; $i++)
{
$allcovers = $allcovers . " " . mysql_real_escape_string($coverphotos[$i]);
}

$coversquery = "UPDATE userinfo SET coverpics = '$allcovers' WHERE emailaddress = '$email'";
$coversqueryrun = mysql_query($coversquery) or die(mysql_error());

echo'<div class="grid_12 push_4">
<span class="label label-success" style="font-size: 16px;">Cover Photos Saved</span>
<br />';

$allusersphotos = "SELECT * FROM photos WHERE emailaddress = '$email'";
$allusersphotosquery = mysql_query($allusersphotos);
$usernumphotos = mysql_num_rows($allusersphotosquery);

echo'<div class="grid_12">
<br />
<div style="font-size:16px;">Change Your 3 Cover Photos:</div><span style="font-size:13px;">(Check 3 Photos)</span><br /><br />';

echo'<form action="', htmlentities($_SERVER['PHP_SELF']), '?view=settings&actiontwo=savecover" method="post" enctype="multipart/form-data">';
for($iii = 0; $iii < $usernumphotos; $iii++) {
$userphotosource[$iii] = mysql_result($allusersphotosquery, $iii, "source");
$userphotosset[$iii] = mysql_result($allusersphotosquery, $iii, "sets");
$userphotoscaption[$iii] = mysql_result($allusersphotosquery, $iii, "caption");
$newsource = str_replace("userphotos/","userphotos/thumbs/", $userphotosource[$iii]);

echo'<img src="',$newsource,'" height="70" width="70" />&nbsp;&nbsp;<input type="checkbox" name="addthese[]" value="',$userphotosource[$iii],'" />&nbsp;"',$userphotoscaption[$iii],'"
    <br /><br />'; 
  
} //end of for loop

echo'<button class="btn btn-success" type="submit">Save Cover Photos</button>
</form>
</div>'; //end of grid 12

} //end of savecover view

else {

$allusersphotos = "SELECT * FROM photos WHERE emailaddress = '$email'";
$allusersphotosquery = mysql_query($allusersphotos);
$usernumphotos = mysql_num_rows($allusersphotosquery);

echo'<div class="grid_12 push_4">
<div style="font-size:16px;">Change Your 3 Cover Photos:</div><span style="font-size:13px;">(Check 3 Photos)</span><br /><br />';

echo'<form action="', htmlentities($_SERVER['PHP_SELF']), '?view=settings&actiontwo=savecover" method="post" enctype="multipart/form-data">';
for($iii = 0; $iii < $usernumphotos; $iii++) {
$userphotosource[$iii] = mysql_result($allusersphotosquery, $iii, "source");
$userphotosset[$iii] = mysql_result($allusersphotosquery, $iii, "sets");
$userphotoscaption[$iii] = mysql_result($allusersphotosquery, $iii, "caption");
$newsource = str_replace("userphotos/","userphotos/thumbs/", $userphotosource[$iii]);
$newmedsource = str_replace("userphotos/","userphotos/medthumbs/", $userphotosource[$iii]);

echo'<img src="',$newsource,'" height="70" width="70" />&nbsp;&nbsp;<input type="checkbox" name="addthese[]" value="',$newmedsource,'" />&nbsp;"',$userphotoscaption[$iii],'"
    <br /><br />'; 
  
} //end of for loop

echo'<button class="btn btn-success" type="submit">Save Cover Photos</button>
</form>
</div>'; //end of grid 12
} //end of regular change cover photo view

} //end of view





else if($view == "messages") {

	//get all the messages that correspond to them by grouping them by thread number
	$messagequery = "SELECT * FROM (SELECT * FROM messages ORDER BY id DESC) AS theorder WHERE (sender='$email' OR receiver='$email') GROUP BY thread ORDER BY id DESC LIMIT 0,20";
	$messageresult = mysql_query($messagequery) or die(mysql_error());
	$numberofmessages = mysql_num_rows($messageresult);

	//if they don't have any messages, display that
	if($numberofmessages == 0) {
		echo '<div style="margin-left: 460px; margin-top: -130px;font-size:16px;">You have no messages!</div>
        <br />
        <div style="margin-left: 280px; margin-top: 20px;font-size:16px;">(Contact photographers through the "contact" tab in their profile)</div></div>';
	}
	//if they do have messages
	else {
		echo '</div>';
	
		echo '<div class="grid_24" style="margin-left: 200px; margin-top: -130px;">';

		$comma = 0;

		//for loop to go through each row in the result
		for($iii=0; $iii<$numberofmessages; $iii++) {
			//find what the message is and who it was from and who it was to
			$currentmessage[$iii] = mysql_result($messageresult, $iii, "contents");
			$currentsender = mysql_result($messageresult, $iii, "sender");
			$currentreceiver = mysql_result($messageresult, $iii, "receiver");

			//find out more about the person involved who is not them
			//if the last message was not from the person whose profile it is
			if($currentsender != $email) {
				//the other person is whomever the message was from
				if($comma == 0) {
					$otherpeople .= "'" . $currentsender . "'";
					$comma = 1;
				}
				else {
					$otherpeople .= ", '" . $currentsender . "'";
				}
			}
			//otherwise the last message was from the person the person whose profile it is
			else {
				//the other person is whomever the last message was sent to
				if($comma == 0) {
					$otherpeople .= "'" . $currentreceiver . "'";
					$comma = 1;
				}
				else {
					$otherpeople .= ", '" . $currentreceiver . "'";
				}
			}
		}

		//now that we know everyone whose information we will need, lets get it
		$moreinfoquery = "SELECT firstname, lastname, profilepic FROM userinfo WHERE emailaddress IN (" . $otherpeople . ") ORDER BY FIELD(emailaddress, " . $otherpeople . ") LIMIT 0, 20";
		$moreinforesult = mysql_query($moreinfoquery) or die(mysql_error());
		
		//now go through the results to get the information and then display it
        echo'
                    <h3>Your Conversations:</h3>
                    <h6>(Contact photographers through the "contact" tab in their profile)</h6>
                    <br />';


		for($iii=0; $iii<$numberofmessages; $iii++) {
			$otherspic = mysql_result($moreinforesult, $iii, "profilepic");
			$othersfirst = mysql_result($moreinforesult, $iii, "firstname");
			$otherslast = mysql_result($moreinforesult, $iii, "lastname");
			$currentthread = mysql_result($messageresult, $iii, "thread");

			//now lets display the message with the other's profile picture and name
			echo '
			<a href="myprofile.php?view=viewthread&thread=', $currentthread, '" style="text-decoration: none">
			<div class="grid_18" id="messageshadow" style="margin-bottom: 20px; font-family: arial;">
				<div class="grid_3">
					<img src="', $otherspic, '" width="60px" height="60px" alt="profile picture" style="margin-bottom: 5px;"/>
					<br />', 
					$othersfirst, ' ', $otherslast, 
				'</div>
				<div class="grid_15" style="margin-top: -75px; margin-left: 120px;">', $currentmessage[$iii], 
				'</div>
			</div>
			</a>';
		}

		echo '</div>';
	}
}
else if($view == "viewthread") {

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
	
	//if no thread was sent, tell them no thread found
	if(!isset($_GET['thread'])) {
		echo '<div style="margin-left: 480px; margin-top: -300px;">No thread found!</div></div>';
	}
	//otherwise there is a thread
	else {
		//select all the messages that match the thread number
		$threadquery = "SELECT * FROM messages WHERE thread=".mysql_real_escape_string(htmlentities($_GET['thread']))." ORDER BY id DESC LIMIT 0, 20";
		$threadresult = mysql_query($threadquery) or die(mysql_error());
		$numberofmessages = mysql_num_rows($threadresult);
		
		//if this returns zero messages, then tell them no thread found
		if($numberofmessages == 0) {
			echo '<div style="margin-left: 480px; margin-top: -300px;">No thread found!</div></div>';
		}
		//otherwise there were messages found
		else {
			echo '</div>';
	
			echo '<div class="grid_24" style="margin-left: 200px; margin-top: -130px;">';

			//find out the other persons email address
			if(mysql_result($threadresult, 0, "sender") == $email) {
				$othersemail = mysql_result($threadresult, 0, "receiver");
			}
			else {
				$othersemail = mysql_result($threadresult, 0, "sender");
			}

			//update the database to show that these messages have been read
			$updatequery = "UPDATE messages SET unread='0' WHERE receiver='$email' AND thread='".mysql_real_escape_string(htmlentities($_GET['thread']))."'";
			mysql_query($updatequery); 

			//find out all the info we need about the other person
			$othersquery = "SELECT firstname, lastname, profilepic, emailaddress FROM userinfo WHERE emailaddress='" . $othersemail . "' LIMIT 0, 1";
			$othersresult = mysql_query($othersquery);
			$otherspic = mysql_result($othersresult, 0, "profilepic");
			$othersfirst = mysql_result($othersresult, 0, "firstname");
			$otherslast = mysql_result($othersresult, 0, "lastname");
			
			//for loop to go through all the messages in reverse order so that the newest one is last
			for($iii=$numberofmessages-1; $iii >= 0; $iii--) {
				//find out who sent the current message in the loop
				$currentsender = mysql_result($threadresult, $iii, "sender");

				//if the current message's sender is the owner of the profile, set the variables as necessary
				if($currentsender == $email) {
					$currentfirst = $firstname;
					$currentlast = $lastname;
					$currentpic = $profilepic;
				}
				//otherwise the other person is the message's sender, so set the variables accordingly
				else {
					$currentfirst = $othersfirst;
					$currentlast = $otherslast;
					$currentpic = $otherspic;
				}
				
				//find out what the current message is
				$currentmessage = mysql_result($threadresult, $iii, "contents");

				//now that we have everything in line, display the message
				echo '
				<div class="grid_18" id="messageshadow2" style="margin-bottom: 20px; font-family: arial;">
					<a href="viewprofile.php?first=', $currentfirst, '&last=', $currentlast, '">
					<div class="grid_3">
						<img src="', $currentpic, '" width="60px" height="60px" alt="profile picture" style="margin-bottom: 5px;"/>
						<br />', 
						$currentfirst, ' ', $currentlast,' 
					</div>
					</a>
					<div class="grid_15" style="margin-top: -75px; margin-left: 120px;">',$currentmessage,'
					</div>
				</div>';			
			}

			//now let's display the box from which they can send a message
			echo' <div class="grid_18" style="font-size: 20px; font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
			line-height: 28px; color: #333333;">
    
			<h3>Reply:</h3>
			<form method="post" action="replymessage.php" />
			<textarea cols="80" rows="4" style="width:715px" name="message"></textarea>
    			<br />
    			<br />
			<button class="btn btn-success" type="submit" value="Send Message">Send Message</button>
			<input type="hidden" name="emailaddressofviewed" value="',$othersemail,'" />
			</form>';

			if(htmlentities($_GET['action'])=="messagesent") {
				echo 'Message Sent!';
			}
			
			echo '</div>';
		}
	}
}
   
     
else if($view == "faves") {

//find what their faves are
	$email=$_SESSION['email'];

	$favesquery = "SELECT * FROM userinfo WHERE emailaddress='$email' LIMIT 0, 1";
	$favesresult = mysql_query($favesquery) or die(mysql_error());
	$faves = mysql_result($favesresult, 0, "faves");
    
//run the query returning the results in the order in which they were favorited starting at the photo specified by $x
	$favephotosquery = "SELECT * FROM photos WHERE source IN ($faves) ORDER BY FIELD(source, $faves) DESC";
	$newresult = mysql_query($favephotosquery);	
    $numberofpics2 = mysql_num_rows($newresult);
    

//nested for loop get name of owner of favorited photo
for($iii=0; $iii < $numberofpics2; $iii++) {
$owner = mysql_result($newresult, $iii, "emailaddress");
$ownerarray[$iii] = $owner;
$emailarray[$owner] += 1;
}

$highest=0;
$topFaved="";
for($iii=0; $iii < count($emailarray); $iii++) {
	if($emailarray[$ownerarray[$iii]]>$highest) {
		$highest = $emailarray[$ownerarray[$iii]];
		$topFaved = $ownerarray[$iii];
	}
}
$topfavequery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$topFaved' LIMIT 0,1");
$firstname = mysql_result($topfavequery, 0, "firstname");
$lastname = mysql_result($topfavequery, 0, "lastname");
$tfpuserid = mysql_result($topfavequery, 0, "user_id");
$tfpfull = $firstname . " " . $lastname;


//Info Box
echo'
<div class="grid_4 pull_3 photoshadowreel" style="height:100px;width:200px;margin-top:10px;margin-left:1px;">
<div style="font-size:13px;text-align:center;margin-top:15px;"><span style="color:black;"># Favorites</span> ',$numberofpics2,'</div>
<div style="font-size:13px;text-align:center;margin-top:5px;"><span style="color:black;">Most Favorited Photographer:</span><br />
<a href="viewprofile.php?u=',$tfpuserid,'">',$tfpfull,'</a>
</div>
</div>';

	//create the images to be displayed    
    	

echo'<div id="thepics">';
echo'<div class="grid_16 push_4" id="container" style="width:780px;margin-top:-230px;">';

for($iii=0; $iii <= 8; $iii++) {
	$image[$iii] = mysql_result($newresult, $iii, "source");
    $imageThumb[$iii] = str_replace("userphotos/","userphotos/medthumbs/", $image[$iii]);
	$id = mysql_result($newresult, $iii, "id");
    $caption = mysql_result($newresult, $iii, "caption");
    $points = mysql_result($newresult, $iii, "points");
    $votes = mysql_result($newresult, $iii, "votes");
    $faves = mysql_result($newresult, $iii, "faves");
    $score = number_format(($points/$votes),2);
    $owner = mysql_result($newresult, $iii, "emailaddress");
    $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress =                  '$owner'");
    $firstname = mysql_result($ownerquery, 0, "firstname");
    $lastname = mysql_result($ownerquery, 0, "lastname");
    $fullname = $firstname . " " . $lastname;
	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
    $heightls = $height / 3.5;
    $widthls = $width / 3.5;

echo '

<div class="photoshadow fPic" id="',$id,'" style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="http://www.photorankr.com/fullsize.php?image=', $image[$iii], '">

<div class="statoverlay" style="z-index:1;left:0px;top:160px;position:relative;background-color:black;width:245px;height:90px;"><p style="line-spacing:1.48;padding:5px;color:white;">"',$caption,'"<br>By: ',$fullname,'<br/>Score: ',$score,'<br>Favorites: ',$faves,'</p></div>

<img style="position:relative;top:-90px;min-height:245px;min-width:245px;" src="http://www.photorankr.com/',$imageThumb[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
	    
      } //end for loop
      
echo'</div>';
echo'</div>';
            
            

//AJAX CODE HERE
echo'
   <div class="grid_6 push_9" style="top:20px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;">Loading More Photos&hellip;</div>
   </div>';


echo '<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMoreFavePics").show();
				$.ajax({
					url: "loadMoreFavePics.php?lastPicture=" + $(".fPic:last").attr("id"),
					success: function(html) {
						if(html) {
							$("#thepics").append(html);
							$("div#loadMoreFavePics").hide();
						}
					}
				});
				last = $(".fPic:last").attr("id");
			}
		}
	});
</script>';

}



elseif($view == 'promote'){

echo'
<div class="grid_20 push_4">

<div class="well" style="font-size:16px;margin-top:-150px;font-family:helvetica neue, gill sans, helvetica;">

<!--Referral Success-->';

$refer=htmlentities($_GET['refer']); 

if ($refer == 'referralsuccess') {
$sendname = $_POST['sendname'];
$sendemail = $_POST['email'];
$to = $sendemail;
$subject = "Your Personal Invitation";
$message = "Hi! You've been invited by $sendname to join PhotoRankr, a site for photographers of all skill levels. What makes PhotoRankr different from the other photo sharing sites?

– The ability to choose the price of your photography 
– Unlimited uploads and 100% free
– Follow other photographers with one click, and view your live 'photostream' of photography from those you follow
– Rank other photography and get feedback from other photographers through comments 
– Make your own profile where you can view your entire portfolio, your followers, who's following you, and edit your information

To accept your invitation and begin following photography today, just click the link below:

http://photorankr.com/signin.php

We hope you'll enjoy PhotoRankr as much as we have building it,

Sincerely,
The PhotoRankr Team
";

$headers = 'From:PhotoRankr <photorankr@photorankr.com>';
mail($to, $subject, $message, $headers);

echo '<span style="position:relative;top:0px;font-family:lucida grande, georgia, helvetica; font-size: 16px;" class="label label-success">Referral successfully sent</span><br /><br />';

}


echo'
Help promote your portfolio and your PhotoRankr page by sharing it with your friends:<br /><span style="font-size:13px;">(This will help increase traffic to your specific page, increase sales, and raise the chances of your photos becoming trending.)</span><br /><br />

<!--FB-->
<a name="fb_share" share_url="http://photorankr.com/viewprofile.php?u=',$user,'"></a> 
<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" 
        type="text/javascript">
</script>

<!--TWITTER-->
<div style="position:relative;margin-top:15px;">
<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://photorankr.com/viewprofile.php?u=',$user,'" data-text="Visit my photography site on PhotoRankr!" data-via="PhotoRankr" data-related="PhotoRankr">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
</script></div>

<!--GOOGLE PLUS-->
<div style="position:relative;margin-top:15px;">
<div class="g-plus" data-action="share" data-href="http://photorankr.com/viewprofile.php?u=',$user,'"></div>';
?>

<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
<?php
echo'

<!--TUMBLR-->
<div style="position:relative;margin-top:15px;">
<span id="tumblr_button_abc123"></span>
</div>

<br />

<!--Referral System-->

Invite your friends to join and follow your photography on PhotoRankr:<br /><br />

<div style="position:relative; top:20px; font-family:lucida grande, georgia, helvetica; font-size: 14px;">Your Name:</div>

<div style="position:relative; top:45px; font-family:lucida grande, georgia, helvetica; font-size: 14px;">Send invitation to:</div>

<div style="position:relative; top:-25px; left:160px;">
<form action="myprofile.php?view=promote&refer=referralsuccess" method="POST">
<input style="width:180px;height:22px;" type="text" name="sendname" value="',$firstname,' ',$lastname,'" />
</div>
<div style="position:relative; top:-40px; left:160px;">
<input style="width:180px;height:22px;" type="text" name="email" placeholder="Email Address"/>
</div>
<div style="position:relative; top:-30px; left:263px;">
<button type="submit" name="Submit" class="btn btn-success">Send Invite</button>
</div>
</form>
</div>

</div>
</div>';

}


//Portfolio View
elseif($view=='') { //they are on the photos tab, which is the main tab	

    $topquery = "SELECT * FROM photos WHERE emailaddress='$email' ORDER BY (points/votes) DESC";
	$topresult = mysql_query($topquery);
    
    $favequery = "SELECT * FROM photos WHERE emailaddress='$email' ORDER BY (faves) DESC";
	$faveresult = mysql_query($favequery);
    
	$totalquery = "SELECT * FROM photos WHERE emailaddress='$email' ORDER BY id DESC";
	$totalresult = mysql_query($totalquery);
	$numberofpics = mysql_num_rows($totalresult);
   
    $infoquery = mysql_query("SELECT totalscore FROM userinfo WHERE emailaddress='$email'");
    $totalpoints = mysql_result($infoquery, 0, "totalscore");
    
    $setinfoquery = mysql_query("SELECT * FROM sets WHERE owner='$email'");
    $numsets = mysql_num_rows($setinfoquery);

    
     $insertquery=mysql_query("UPDATE userinfo SET reputation = $ultimatereputation WHERE emailaddress='$emailaddress'");
    mysql_query($insertquery);


//Info Box
echo'
<div class="grid_4 pull_3 photoshadowreel" style="height:100px;width:200px;margin-top:10px;margin-left:1px;">
<div style="font-size:13px;text-align:center;margin-top:15px;"><span style="color:black;"># Photos</span> ',$numberofpics,'</div>
<div style="font-size:13px;text-align:center;margin-top:5px;"><span style="color:black;">Total Points</span> ',$totalpoints,'</div>
<div style="font-size:13px;text-align:center;margin-top:5px;"><span style="color:black;"># Exhibits</span> ',$numsets,'</div>
<div style="font-size:14px;color:black;text-align:center;opacity:.7;">(Click on photos to edit)</div>
</div>';

//GET VIEW (ALL PHOTOS OR EXHIBITS)
if(isset($_GET['ex'])){
		$exhibit = $_GET['ex'];
	}
    
if(isset($_GET['p'])){
		$p = $_GET['p'];
	}
    
    
    //Links
    echo '<div class="grid_18 pull_1" style="padding-left:10px;margin-top:-130px;">
    <a class="btn btn-primary" style="text-decoration:none;" href="http://www.photorankr.com/myprofile.php">My Portfolio</a>&nbsp;&nbsp;&nbsp;
    <a class="btn btn-primary" style="text-decoration:none;" href="http://www.photorankr.com/myprofile.php?ex=y">Exhibits</a>&nbsp;&nbsp;&nbsp;';
    if($exhibit == '') { 
    echo'
        <span style="text-align:center;margin-left:30px;position:relative;top:3px;">
        <a href="http://www.photorankr.com/myprofile.php">Newest</a>&nbsp;&nbsp;&nbsp;
        <a href="http://www.photorankr.com/myprofile.php?p=top">Top Ranked</a>&nbsp;&nbsp;&nbsp;
        <a href="http://www.photorankr.com/myprofile.php?p=fave">Most Favorited</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </span';
    }
    echo'
     <span style="float:right;"><a href="myprofile.php?view=promote"><button style="width:100px;" class="btn btn-warning">PROMOTE</button></a></span>
    </div>'; 

if($exhibit == '') { 

if($p == '') {

    echo'<div id="thepics">';
echo'<div class="grid_16 push_4" id="container" style="width:780px;margin-top:-205px;">';

for($iii=0; $iii < 9 && $iii < $numberofpics; $iii++) {
	$image[$iii] = mysql_result($totalresult, $iii, "source");
    $imageThumb[$iii] = str_replace("userphotos/","userphotos/medthumbs/", $image[$iii]);
    $imageThumb[$iii] = str_replace(".JPG",".jpg", $imageThumb[$iii]);
	$id = mysql_result($totalresult, $iii, "id");
    $caption = mysql_result($totalresult, $iii, "caption");
    $points = mysql_result($totalresult, $iii, "points");
    $votes = mysql_result($totalresult, $iii, "votes");
    $faves = mysql_result($totalresult, $iii, "faves");
    $score = number_format(($points/$votes),2);
	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
    $heightls = $height / 3.5;
    $widthls = $width / 3.5;

echo '

<div class="photoshadow fPic" id="',$id,'" style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="http://www.photorankr.com/fullsizeme.php?image=', $image[$iii], '">

<div class="statoverlay" style="z-index:1;left:0px;top:170px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;">"',$caption,'"<br>Score: ',$score,'<br>Favorites: ',$faves,'</p></div>

<img style="position:relative;top:-75px;min-height:245px;min-width:245px;" src="http://www.photorankr.com/',$imageThumb[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
	    
      } //end for loop
      
echo'</div>';
echo'</div>';
            
//AJAX CODE HERE
echo'
   <div class="grid_6 push_9" style="top:20px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;">Loading More Photos&hellip;</div>
   </div>';


echo '<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMorePortfolioPics").show();
				$.ajax({
					url: "loadMorePortfolioPics.php?lastPicture=" + $(".fPic:last").attr("id"),
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
    
} //end of p == ''


elseif($p == 'top') {

    echo'<div id="thepics">';
echo'<div class="grid_16 push_4" id="container" style="width:780px;margin-top:-205px;">';

for($iii=0; $iii < 9 && $iii < $numberofpics; $iii++) {
	$image[$iii] = mysql_result($topresult, $iii, "source");
    $imageThumb[$iii] = str_replace("userphotos/","userphotos/medthumbs/", $image[$iii]);
    $imageThumb[$iii] = str_replace(".JPG",".jpg", $imageThumb[$iii]);
    $caption = mysql_result($topresult, $iii, "caption");
    $points = mysql_result($topresult, $iii, "points");
    $votes = mysql_result($topresult, $iii, "votes");
    $ratio = ($points/$votes);
    $faves = mysql_result($topresult, $iii, "faves");
    $score = number_format(($points/$votes),2);
	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
    $heightls = $height / 3.5;
    $widthls = $width / 3.5;

echo '

<div class="photoshadow fPic" id="',$ratio,'" style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="http://www.photorankr.com/fullsizeme.php?image=', $image[$iii], '">

<div class="statoverlay" style="z-index:1;left:0px;top:170px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;">"',$caption,'"<br>Score: ',$score,'<br>Favorites: ',$faves,'</p></div>

<img style="position:relative;top:-75px;min-height:245px;min-width:245px;" src="http://www.photorankr.com/',$imageThumb[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
	    
      } //end for loop
      
echo'</div>';
echo'</div>';
            
//AJAX CODE HERE
echo'
   <div class="grid_6 push_9" style="top:20px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;">Loading More Photos&hellip;</div>
   </div>';


echo '<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMoreTopPortfolioPics").show();
				$.ajax({
					url: "loadMoreTopPortfolioPics.php?lastPicture=" + $(".fPic:last").attr("id"),
					success: function(html) {
						if(html) {
							$("#thepics").append(html);
							$("div#loadTopMorePortfolioPics").hide();
						}
					}
				});
				last = $(".fPic:last").attr("id");
			}
		}
	});
</script>';

} //end of p == 'top'    



elseif($p == 'fave') {

echo'<div id="thepics">';
echo'<div class="grid_16 push_4" id="container" style="width:780px;margin-top:-205px;">';

for($iii=0; $iii < 9 && $iii < $numberofpics; $iii++) {
	$image[$iii] = mysql_result($faveresult, $iii, "source");
    $imageThumb[$iii] = str_replace("userphotos/","userphotos/medthumbs/", $image[$iii]);
    $imageThumb[$iii] = str_replace(".JPG",".jpg", $imageThumb[$iii]);
    $caption = mysql_result($faveresult, $iii, "caption");
    $points = mysql_result($faveresult, $iii, "points");
    $votes = mysql_result($faveresult, $iii, "votes");
    $ratio = ($points/$votes);
    $faves = mysql_result($faveresult, $iii, "faves");
    $score = number_format(($points/$votes),2);
	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
    $heightls = $height / 3.5;
    $widthls = $width / 3.5;

echo '

<div class="photoshadow fPic" id="',$faves,'" style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="http://www.photorankr.com/fullsizeme.php?image=', $image[$iii], '">

<div class="statoverlay" style="z-index:1;left:0px;top:170px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;">"',$caption,'"<br>Score: ',$score,'<br>Favorites: ',$faves,'</p></div>

<img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-75px;min-height:245px;min-width:245px;" src="http://www.photorankr.com/',$imageThumb[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
	    
      } //end for loop
      
echo'</div>';
echo'</div>';
            
//AJAX CODE HERE
echo'
   <div class="grid_6 push_9" style="top:20px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;">Loading More Photos&hellip;</div>
   </div>';


echo '<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMoreFavePortfolioPics").show();
				$.ajax({
					url: "loadMoreFavePortfolioPics.php?lastPicture=" + $(".fPic:last").attr("id"),
					success: function(html) {
						if(html) {
							$("#thepics").append(html);
							$("div#loadFaveMorePortfolioPics").hide();
						}
					}
				});
				last = $(".fPic:last").attr("id");
			}
		}
	});
</script>';


}
   
    
    } //end of portfolio view
            

//Create the images to be displayed    
    	if ($numberofpics < 1) {
    		echo '<span style="position:relative;top:10px;left:150px;font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
line-height: 18px;
color: #333333;font-size:16px;">You have no photos yet. Upload photos from the upload tab.</span>';
    	}
        
        
//Exhibit View
elseif($exhibit == 'y') { //start of exhibit view
//Get view

if(isset($_GET['set'])){
		$set = mysql_real_escape_string($_GET['set']);
	}
    
    //get exhibit mode
if(isset($_GET['mode'])){
		$mode = ($_GET['mode']);
	}

if($mode == 'added') {
//add checked photos to existing exhibit

if(!empty($_POST['addthese'])) {
    foreach($_POST['addthese'] as $checked) {
        //insert each checked photo into corresponding set
        $checkedset = "UPDATE photos SET set_id = '$set' WHERE source = '$checked'";
        $checkedsetrun = mysql_query($checkedset);
        }
        }
	
echo'<span style="position:relative;margin-top:-130px;font-size: 16px;"><span class="label label-success" style="font-size:16px;" >Your exhibits have been updated successfully!</span><br /><br /><a href="myprofile.php?ex=y">Click here to view them</a><br /><br /></span>';
}

if($mode == 'coverchanged') {
//edit existing exhibit

    $newcaption = mysql_real_escape_string($_POST['caption']);
    $newaboutset = mysql_real_escape_string($_POST['aboutset']);
    $newcover = mysql_real_escape_string($_POST['addthis']);
    
    $exhibitchange = "UPDATE [sets] SET (title = '$newcaption', about = '$newaboutset', cover = '$newcover') WHERE id = '$set' AND owner = '$email'";
    $exhibitrun = mysql_query($exhibitchange);
        	
echo'<span style="position:relative;margin-top:-130px;font-size: 16px;"><span class="label label-success" style="font-size:16px;" >Your exhibit has been updated successfully!</span><br /><br /><a href="myprofile.php?ex=y">Click here to view exhibits</a><br /><br /></span>';
}

//select all exhibits of user
$allsetsquery = "SELECT * FROM sets WHERE owner = '$email'";
$allsetsrun = mysql_query($allsetsquery);
$numbersets = mysql_num_rows($allsetsrun);
echo'<div style="margin-top:-60px">';

if($numbersets == 0) {
echo'<div class="well grid_6 push_4" style="font-size:16px;width:270px;"><a href="myprofile.php?view=upload&cs=n">Click here to create your first exhibit</a></div>'; 
}

if($set == '' & $numbersets > 0) {

echo'<div class="grid_20 push_4" style="margin-top:-200px;"><a href="myprofile.php?view=upload&cs=n"><button class="btn btn-success">Create New Exhibit</button></a><br /><br />
'; 

for($iii=0; $iii < $numbersets; $iii++) {
$setname[$iii] = mysql_result($allsetsrun, $iii, "title");
$setcover = mysql_result($allsetsrun, $iii, "cover");
$set_id[$iii] = mysql_result($allsetsrun, $iii, "id");
$setname2[$iii] = (strlen($setname[$iii]) > 30) ? substr($setname[$iii],0,27). " &#8230;" : $setname[$iii];
if($setcover == '') {
$setcover = "profilepics/nocoverphoto.png";
}
//grab all photos in the exhibit
$grabphotos = "SELECT * FROM photos WHERE emailaddress = '$email' AND set_id = '$set_id[$iii]'";
$grabphotosrun = mysql_query($grabphotos);
$numphotosgrabbed = mysql_num_rows($grabphotosrun);

echo'<div class="grid_3 photoshadow" style="margin-top:20px;width:235px;height:275px;"> 
 <a style="text-decoration:none" href="http://www.photorankr.com/myprofile.php?ex=y&set=',$set_id[$iii],'">
<img src="http://www.photorankr.com/',$setcover,'" width="235" height="230" />
 <br />
 <div style="color:#333;font-size:16px;font-family:arial,helvetica neue;padding-left:5px;padding-top:5px;text-align:left;">
    "',$setname2[$iii],'"</div>

    <span style="text-decoration:none;">&nbsp;',$numphotosgrabbed,' Photos</span></a>
    ';
    echo '</div>';  
} //end of set == '' view
echo'</div>';

} //end of set == '' view


elseif($set != '') {
//get exhibit mode
if(isset($_GET['mode'])){
		$mode = ($_GET['mode']);
	}
if($mode == '') {
//grab all photos in the exhibit
$grabphotos = "SELECT * FROM photos WHERE emailaddress = '$email' AND set_id = '$set'";
$grabphotosrun = mysql_query($grabphotos);
$numphotosgrabbed = mysql_num_rows($grabphotosrun);

//grab about this set
$aboutset = "SELECT * FROM sets WHERE owner = '$email' AND id = '$set' LIMIT 0,1";
$aboutsetrun = mysql_query($aboutset);
$aboutarray = mysql_fetch_array($aboutsetrun);
$aboutset = $aboutarray['about'];
$settitle = $aboutarray['title'];
$setcover = $aboutarray['cover'];
if($setcover == '') {
$setcover = 'profilepics/nocoverphoto.png';
}

echo'<div style="position:relative;top:-250px;">

<div class="well grid_14 push_4" style="width:755px;font-size:16px;line-height:25px;"><u>Exhibit:</u> "',$settitle,'"<br />
<br /><u>About this exhibit:</u> ',$aboutset,'<br /><br />
<a data-toggle="modal" data-backdrop="static" href="#add"><button class="btn btn-success">Add Photos to Exhibit</button></a>&nbsp;&nbsp;
<a data-toggle="modal" data-backdrop="static" href="#editexhibit"><button class="btn btn-success">Edit Exhibit</button></a></div>';


for($iii=0; $iii < $numphotosgrabbed; $iii++) {
$insetname[$iii] = mysql_result($grabphotosrun, $iii, "caption");
$insetsource[$iii] = mysql_result($grabphotosrun, $iii, "source");
$newsource = str_replace("userphotos/","userphotos/medthumbs/", $insetsource[$iii]);

echo'<div class="grid_3 push_4 photoshadow" style="margin-top:10px;width:245px;height:245px;"> 
 <a style="text-decoration:none" href="http://www.photorankr.com/fullsizeme.php?image=',$insetsource[$iii],'">
<img src="http://www.photorankr.com/',$newsource,'" width="245" height="245" />
    </a>';
echo '</div>'; 
} //end for loop
echo'</div>';

   } //end of no exhibit mode
   
   
   //Add Photos Modal

echo'<div class="modal hide fade" id="add" style="overflow-y:scroll;overflow-x:hidden;">

<div class="modal-header">
<a style="float:right" class="btn btn-primary" data-dismiss="modal">Close</a>
<img style="margin-top:-4px;" src="graphics/logoteal.png" height="28" width="90" />&nbsp;&nbsp;<span style="font-size:16px;">Add photos to your exhibit below:</span>
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:550px;height:500px;overflow-x:hidden;">
		
<img style="border: 1px solid black;margin-left:10px;margin-top:10px;" src="',$setcover,'" 
height="100px" width="100px" />

<div style="width:540px;margin-left:130px;margin-top:-100px;overflow-y:scroll;overflow-x:hidden;">

<form action="', htmlentities($_SERVER['PHP_SELF']), '?ex=y&set=',$set,'&mode=added" method="post" enctype="multipart/form-data">
    <span style="font-size:14px;">
    Exhibit Name:&nbsp;&nbsp;',$settitle,'
    <br />
    <br />
    About this Exhibit:&nbsp;&nbsp;
    ',stripslashes($aboutset),'
    <br />
    Check photos to add to this exhibit:
    <br /><br />';
    $allusersphotos = "SELECT * FROM photos WHERE emailaddress = '$email'";
    $allusersphotosquery = mysql_query($allusersphotos);
    $usernumphotos = mysql_num_rows($allusersphotosquery);


    for($iii = 0; $iii < $usernumphotos; $iii++) {
        $userphotosource[$iii] = mysql_result($allusersphotosquery, $iii, "source");
        $userphotosset[$iii] = mysql_result($allusersphotosquery, $iii, "sets");
        $userphotoscaption[$iii] = mysql_result($allusersphotosquery, $iii, "caption");
        $newsource = str_replace("userphotos/","userphotos/thumbs/", $userphotosource[$iii]);
        if($userphotosset[$iii] == $set) {
        echo'<img src="',$newsource,'" height="70" width="70" />&nbsp;&nbsp;<input type="checkbox" name="addthese[]" value="',      $userphotosource[$iii],'" checked />&nbsp;"',$userphotoscaption[$iii],'"
    <br /><br />'; }
        else {
        echo'<img src="',$newsource,'" height="70" width="70" />&nbsp;&nbsp;<input type="checkbox" name="addthese[]" value="',      $userphotosource[$iii],'" />&nbsp;"',$userphotoscaption[$iii],'"
        <br /><br />'; 
        }    
    
    } //end of for loop

    
    echo'
    </span>
    <button class="btn btn-success" type="submit">Save Exhibit</button>
    </form>
    
    </div>
    </div>
    </div>';
   
} //end of set != '' view  

echo'</div>';
} //end of exhibits view 

} //end of entire photos page


//Edit Exhibit Modal

echo'<div class="modal hide fade" id="editexhibit" style="overflow-y:scroll;overflow-x:hidden;">

<div class="modal-header">
<a style="float:right" class="btn btn-primary" data-dismiss="modal">Close</a>
<img style="margin-top:-4px;" src="graphics/logoteal.png" height="28" width="90" />&nbsp;&nbsp;<span style="font-size:16px;">Edit your exhibit\'s information below:</span>
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:550px;height:500px;overflow-x:hidden;">
		
<img style="border: 1px solid black;margin-left:10px;margin-top:10px;" src="',$setcover,'" 
height="100px" width="100px" />

<div style="width:540px;margin-left:130px;margin-top:-100px;overflow-y:scroll;overflow-x:hidden;">

<form action="', htmlentities($_SERVER['PHP_SELF']), '?ex=y&set=',$set,'&mode=coverchanged" method="post" enctype="multipart/form-data">
    <span style="font-size:14px;">
    Exhibit Name:&nbsp;&nbsp; <input name="caption" value="',$settitle,'">
    <br />
    About this Exhibit:&nbsp;
    <br />
    <textarea style="width:380px;" rows="4" cols="60" name="aboutset">',stripslashes($aboutset),'</textarea>
    <br />
    Change Exhibit Cover Photo (choose one):
    <br /><br />';
    $allusersphotos2 = "SELECT * FROM photos WHERE emailaddress = '$email' AND set_id = '$set'";
    $allusersphotosquery2 = mysql_query($allusersphotos2);
    $usernumphotos2 = mysql_num_rows($allusersphotosquery2);

    for($iii = 0; $iii < $usernumphotos2; $iii++) {
        $userphotosource[$iii] = mysql_result($allusersphotosquery2, $iii, "source");
        $userphotosset[$iii] = mysql_result($allusersphotosquery2, $iii, "sets");
        $userphotoscaption[$iii] = mysql_result($allusersphotosquery2, $iii, "caption");
        $newsource = str_replace("userphotos/","userphotos/thumbs/", $userphotosource[$iii]);
        if($userphotosset[$iii] == $set) {
            echo'<img src="',$newsource,'" height="70" width="70" />&nbsp;&nbsp;<input type="checkbox" name="addthis" value="',$userphotosource[$iii],'" checked /      >&nbsp;"',$userphotoscaption[$iii],'"
    <br /><br />'; }
        else {
            echo'<img src="',$newsource,'" height="70" width="70" />&nbsp;&nbsp;<input type="checkbox" name="addthis" value="',$userphotosource[$iii],'" />&nbsp;"',                $userphotoscaption[$iii],'"
        <br /><br />'; }
        
    } //end of for loop
    
    echo'
    </span>
    <button class="btn btn-success" type="submit">Save Info</button>
    </form>
    
    </div>
    </div>
    </div>
    </div>'; 


?>


		<!--/END WHAT IS BEING VIEWED-->
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
	<!--/end big container-->
</div>


<!--TUMBLR SCRIPTS-->
<script type="text/javascript">
    var tumblr_link_url = "http://photorankr.com/viewprofile.php?u=',$user,'";
    var tumblr_link_name = "My PhotoRankr Portfolio";
    var tumblr_link_description = "Visit and rank my photography on PhotoRankr!";
</script>

<script type="text/javascript">
    var tumblr_button = document.createElement("a");
    tumblr_button.setAttribute("href", "http://www.tumblr.com/share/link?url=" + encodeURIComponent(tumblr_link_url) + "&name=" + encodeURIComponent(tumblr_link_name) + "&description=" + encodeURIComponent(tumblr_link_description));
    tumblr_button.setAttribute("title", "Share on Tumblr");
    tumblr_button.setAttribute("style", "display:inline-block; text-indent:-9999px; overflow:hidden; width:129px; height:20px; background:url('http://platform.tumblr.com/v1/share_3.png') top left no-repeat transparent;");
    tumblr_button.innerHTML = "Share on Tumblr";
    document.getElementById("tumblr_button_abc123").appendChild(tumblr_button);
</script>

<script type="text/javascript" src="http://platform.tumblr.com/v1/share.js"></script>


 </body>
</html>
