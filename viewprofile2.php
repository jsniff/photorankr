<?php 

//log them out if they try to logou
//viewprofile
@session_start();

if($_GET['action'] == logout) {
	$_SESSION['loggedin'] = 0;
	session_destroy();
}


//CONNECT TO DB
require "db_connection.php";

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

        	die('That user does not exist in our database. <a href=../signin.php>Click Here to Register</a>');

        }
	$info = mysql_fetch_array($check);    
	if($_POST['password'] == $info['password']){

	//then redirect them to the same page as signed in and set loggedin to 1
	$_SESSION['loggedin']=1;
	$_SESSION['email']=$_POST['emailaddress'];

	}
   
	//gives error if the password is wrong
    	if ($_POST['password'] != $info['password']) {
die('Incorrect password, please try again. <a href="../lostpassword.php"> Lost your password?</a>');	}
}

//find out whos profile they are looking at
//GET USER ID
if(isset($_GET['u'])){
$user = htmlentities($_GET['u']);
$namequery = "SELECT * FROM userinfo WHERE user_id = '$user' LIMIT 0,1";
$namequeryrun = mysql_query($namequery);

if(mysql_num_rows($namequeryrun) == 0) {
	//send them back to trending because this user doesn't exist
	header("Location: ../trending.php");
	exit; }

if($namequeryrun) {
$namearray = mysql_fetch_array($namequeryrun);
$firstname = $namearray['firstname'];
$lastname = $namearray['lastname'];
$emailaddress=$namearray['emailaddress'];
$profilepic=$namearray['profilepic'];
$age=$namearray['age'];
$user_id=$namearray['user_id'];
$gender=$namearray['gender'];
$location=$namearray['location'];
$camera=$namearray['camera'];
$facebookpage=$namearray['facebookpage'];
$twitteraccount=$namearrayw['twitteraccount'];
$bio=$namearray['bio'];
$quote = $namearray['quote'];

}
  
    }
    
elseif(isset($_GET['first']) & isset($_GET['last'])) {
$firstname = addslashes($_REQUEST['first']);
$lastname = addslashes($_REQUEST['last']);

$namequery = "SELECT * FROM userinfo WHERE firstname = '$firstname' AND lastname = '$lastname' LIMIT 0,1";
$namequeryrun = mysql_query($namequery);

if(mysql_num_rows($namequeryrun) == 0) {
	//send them back to trending because this user doesn't exist
	header("Location: ../trending.php");
	exit; }

if($namequeryrun) {
$namearray = mysql_fetch_array($namequeryrun);
$firstname = $namearray['firstname'];
$lastname = $namearray['lastname'];
$emailaddress=$namearray['emailaddress'];
$profilepic=$namearray['profilepic'];
$user_id=$namearray['user_id'];
$age=$namearray['age'];
$gender=$namearray['gender'];
$location=$namearray['location'];
$camera=$namearray['camera'];
$facebookpage=$namearray['facebookpage'];
$twitteraccount=$namearrayw['twitteraccount'];
$bio=$namearray['bio'];

}
  
    }

    
else { //send them back to trending because that profile doesn't exist or wasn't specified
header("Location: ../trending.php");
exit;}

//ADD PAGEVIEW TO THEIR PROFILE
$profileviewquery = mysql_query("UPDATE userinfo SET profileviews = (profileviews + 1) WHERE user_id = '$user_id'");


//QUERY FOR USER PHOTOS
$query="SELECT * FROM photos WHERE emailaddress ='$emailaddress' ORDER BY 'id' DESC";
$newresult=mysql_query($query);
$numberofpics=mysql_num_rows($newresult);

ini_set('max_input_time', 300);  

//GET USER EMAIL
$email=$_SESSION['email'];


//DE-HIGHLIGHT NOTIFICATIONS IF CLICKED ON
if(isset($_GET['id'])){
$id = htmlentities($_GET['id']);
$idformatted = $id . " ";
$unhighlightquery = "UPDATE userinfo SET unhighlight = CONCAT(unhighlight,'$idformatted') WHERE emailaddress = '$email'";
$unhighlightqueryrun = mysql_query($unhighlightquery);

//notifications query reset 
if($currentnotsresult > 0) {
$notsquery = "UPDATE userinfo SET notifications = 0 WHERE emailaddress = '$email6'";
$notsqueryrun = mysql_query($notsquery); }
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
    $emailquery=("SELECT following FROM userinfo WHERE emailaddress ='$emailaddress'");
	$followresult=mysql_query($emailquery);
	$followinglist=mysql_result($followresult, 0, "following");
	$followingquery="SELECT * FROM userinfo WHERE emailaddress IN ($followinglist)";
	$followingresult = mysql_query($followingquery);
	$numberfollowing = mysql_num_rows($followingresult);


if(isset($_GET['view'])) {
	$view=htmlentities($_GET['view']); //get which tab of profile they are looking at
}

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
		$Viewerid= mysql_result($emailresult, 0, "id");
		if($prevemails == "") {$emailaddressformatted="'". $emailaddress . "'";}
		else {$emailaddressformatted=", '". $emailaddress . "'";}
        
		//MAKE SURE FOLLOWER ISN'T ADDED TWICE
		$search_string=$prevemails;
		$regex="/$emailaddress/";
		$match=preg_match($regex,$search_string);
		if ($match > 0) {
			echo '<div style="position:absolute; top:165px; left:370px; font-family: lucida grande, georgia; color:black; font-size:17px;z-index:72983475273459273458972349587293745;">You are already following this photographer!</div>';
		} 
		else {
			$followingstring=$prevemails . $emailaddressformatted;
			$followingstring=addslashes($followingstring);
			$followquery = "UPDATE userinfo SET following = '$followingstring' WHERE emailaddress='$email'";
			$followingresult=mysql_query($followquery);
            
             $type2 = "follow";
             $ownername = $firstname . " " . $lastname;
        $newsfeedfollowquery="INSERT INTO newsfeed (firstname, lastname, emailaddress,following,type,owner) VALUES ('$viewerfirst', '$viewerlast', '$email','$emailaddress','$type2','$ownername')";
        $follownewsquery = mysql_query($newsfeedfollowquery);
        
        
        
        //notifications query     
$notsquery = "UPDATE userinfo SET notifications = (notifications + 1) WHERE emailaddress = '$emailaddress'";
$notsqueryrun = mysql_query($notsquery);  
            
        /* echo '<div style="position:absolute; top:165px; left:370px; font-family: lucida grande, georgia; color:black; font-size:17px;z-index:72983475273459273458972349587293745;">Now Following ',$firstname,' ',$lastname,'!</div>'; */
            
             		//MAIL EMAIL TO PERSON NOW BEING FOLLOWED
                    
  //GRAB SETTINGS LIST
$settingemail = $_SESSION['email'];
$settingquery = "SELECT * FROM userinfo WHERE emailaddress = '$settingemail'";
$settingqueryrun = mysql_query($settingquery);
$settinglist = mysql_result($settingqueryrun, 0, "settings");
        
$setting_string = $settinglist;
$find = "emailfollower";
$foundsetting = strpos($setting_string,$find);

    
        		$to = $emailaddress;
        		$subject = $viewerfirst . " " . $viewerlast . ' is now following your photography on PhotoRankr!';
        		$message = 'You have a new follower on PhotoRankr! Visit their photography here: http://photorankr.com/viewprofile.php?u=' . $viewerid;
        		$headers = 'From:PhotoRankr <photorankr@photorankr.com>';
                if($foundsetting > 0) {
        		mail($to, $subject, $message, $headers);   
                }
                
		}
	}
}

    $email6 = $_SESSION['email'];


//QUERY FOR NOTIFICATIONS
$currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$email6'";
$currentnotsquery = mysql_query($currentnots);
$currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");

//notifications query reset 
if($currentnotsresult > 0) {
$notsquery = "UPDATE userinfo SET notifications = (notifications - 1) WHERE emailaddress = '$email6'";
$notsqueryrun = mysql_query($notsquery); }


//Grab VIEWERS reputation score
    
    $toprankedphotos2 = "SELECT * FROM photos WHERE emailaddress = '$emailaddress' ORDER BY points DESC";
    $toprankedphotosquery2 = mysql_query($toprankedphotos2);
    $numtoprankedphotos2 = mysql_num_rows($toprankedphotos2);

    for($i=0;$i<15;$i++){
    $toprankedphotopoints2 = mysql_result($toprankedphotosquery2, $i, "points") + $toprankedphotopoints2;
    }
    
    $userphotos2="SELECT * FROM photos WHERE emailaddress = '$emailaddress'";
    $userphotosquery2=mysql_query($userphotos2);
    $numphotos2=mysql_num_rows($userphotosquery2);
    
    //Gather Total Number of Votes for All Photos (This is Visibility)
    for($ii=0; $ii<$numphotos2;$ii++){
    $totalvotes2 = mysql_result($userphotosquery2, $ii, "votes") + $totalvotes2; 
    }
    

    $followersquery2="SELECT * FROM userinfo WHERE following LIKE '%$emailaddress%'";
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

    $ultimatereputation = ($followerweighted2+$rankingweighted2+$totalpgviewsweighted2) * 100;




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
  <title><?php echo $firstname . " " . $lastname; ?> - PhotoRankr</title>
   <meta name="Generator" content="EditPlus">
   <meta name="viewport" content="width=1200" /> 
  <meta name="Author" content="PhotoRankr, PhotoRankr.com">
  <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
  <meta name="Description" content="PhotoRankr allows photographers of all skill levels to sell and share their work. Create your photostream cutomized to what you want to see. Add photos to your favorites, rank them, and watch them trend. Build your portfolio with Photorankr.">

  <link rel="stylesheet" href="../reset.css" type="text/css" />
  <link rel="stylesheet" href="../text.css" type="text/css" />
  <link rel="stylesheet" href="../960_24.css" type="text/css" />
  	<link rel="Stylesheet" type="text/css" href="../smoothDivScroll.css" />
  <link rel="shortcut icon" type="image/x-png" href="http://photorankr.com/graphics/favicon.png"/>
  <link rel="stylesheet" href="../bootstrapnew.css" type="text/css" media="screen" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script src="../bootstrap.js" type="text/javascript"></script>
    
  <script src="../bootstrap-dropdown.js" type="text/javascript"></script>
  <script src="../bootstrap-collapse.js" type="text/javascript"></script>
	<script src="../jquery-ui-1.8.18.custom.min.js" type="text/javascript"></script>
	<script src="../jquery.mousewheel.min.js" type="text/javascript"></script>
	<script src="../jquery.smoothdivscroll-1.2-min.js" type="text/javascript"></script>

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
location.href="http://photorankr.com/fullsize.php?image=<?php echo $image; ?>&fw="+fw
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
</script

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
xmlhttp.open("GET","../gettags.php?q="+str,true);
xmlhttp.send();
}
</script>

 </head>

<body style="background-color: #eeeff3; overflow-x: hidden;">


<!--NAVIGATION BAR-->
<div class="navbar" style="z-index:10;min-width:1220px;padding-top:0px;min-width:1100px;font-size:16px;width:100%;">
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
							<form name="login_form" method="post" action="viewprofile.php?u=',$user_id,'&action=login">
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




<!--big container-->
<div id="container" class="container_24" style="padding-top:80px;">
		
        
		<!--profile picture and navigation-->
		<div class="grid_24">
			
			<!--profile picture-->
			<div class="grid_4 pull_3" style="text-align: center">
				<a href="../viewprofile.php?u=<?php echo $user_id; ?>">
				<img src="../<?php echo $profilepic; ?>" height="200" width="200" class="photoshadowreel"/>
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
        $coverpic[1] = str_replace(".JPG",".jpg", $coverpic[1]);
        $coverpic[1] = str_replace("userphotos/","userphotos/medthumbs/", $coverpic[1]);
        

		$coverpic[2] = mysql_result($gathercoversrun, 1, "source");
        $coverpic[2] = str_replace(".JPG",".jpg", $coverpic[2]);
        $coverpic[2] = str_replace("userphotos/","userphotos/medthumbs/", $coverpic[2]);


		$coverpic[3] = mysql_result($gathercoversrun, 2, "source");
        $coverpic[3] = str_replace(".JPG",".jpg", $coverpic[3]);
        $coverpic[3] = str_replace("userphotos/","userphotos/medthumbs/", $coverpic[3]);
    
	}
    
    if($coverpic[1] == '') {$coverpic[1] = "profilepics/nocoverphoto.png";}
		if($coverpic[2] == '') {$coverpic[2] = "profilepics/nocoverphoto.png";}
		if($coverpic[3] == '') {$coverpic[3] = "profilepics/nocoverphoto.png";}

echo'<div class="grid_20" style="width:780px">';
   for($iii=1; $iii < 4; $iii++) {
    
    list($width, $height) = getimagesize($coverpic);
	$imgratio = $height / $width;
    $heightls = $height / 1.5;
    $widthls = $width / 1.5;
 
   
    echo'<div class="photoshadowreel" style="float:left;display:inline;width:256px;height:200px;overflow:hidden;"><img  onmousedown="return false" oncontextmenu="return false;" src="../',$coverpic[$iii],'" height="256" width="256" /></div>';
   }

echo'</div>';              
                    
  ?>
        
        
		<!--/end profile picture and 24 grid-->
  
 
<!--Following Modal-->
<div class="modal hide fade" id="fwmodal" style="overflow:hidden;">
      
<?php
if($_SESSION['loggedin'] !== 1) {

echo'
<div class="modal-header">
<a style="float:right" class="btn btn-primary" data-dismiss="modal">Close</a>
<img style="margin-top:-4px;" src="../graphics/logoteal.png" height="28" width="90" />&nbsp;&nbsp;<span style="font-size:16px;">Please log in to follow ',$firstname,' ',$lastname,'</span>
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:500px;">
		
<img style="border: 1px solid black;margin-left:10px;margin-top:30px;" src="',$profilepic,'" 
height="100px" width="100px" />

<div style="width:500px;margin-left:130px;margin-top:-90px;">
',$firstname,' ',$lastname,'<br />                 

',$numberofpics,' photos <br />

Portfolio Average: ',$portfolioranking,' <br /><br /><br /><br />

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
<img style="margin-top:-4px;" src="../graphics/logoteal.png" height="28" width="90" />&nbsp;&nbsp;<span style="font-size:16px;">Oops, you accidentally tried to follow yourself.</span>
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:500px;">
		
<img style="border: 1px solid black;margin-left:10px;margin-top:30px;" src="',$profilepic,'" 
height="100px" width="100px" />

<div style="width:500px;margin-left:130px;margin-top:-90px;">
',$firstname,' ',$lastname,'<br />                 

',$numberofpics,' photos <br />

Portfolio Average: ',$portfolioranking,' <br /><br /><br /><br />

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
<img style="margin-top:-4px;" src="../graphics/logoteal.png" height="28" width="90" />&nbsp;&nbsp;<span style="font-size:16px;">You are already following this photographer</span>
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:500px;">
		
<img style="border: 1px solid black;margin-left:10px;margin-top:30px;" src="',$profilepic,'" 
height="100px" width="100px" />

<div style="width:500px;margin-left:130px;margin-top:-90px;">
',$firstname,' ',$lastname,'<br />                 

',$numberofpics,' photos <br />

Portfolio Average: ',$portfolioranking,' <br /><br /><br /><br />

</div>
</div>';
		} 

else {
            
			echo'
<div class="modal-header">
<a style="float:right" class="btn btn-primary" href="../viewprofile.php?u=', $user_id,'&fw=1">Close</a>
<img style="margin-top:-4px;" src="../graphics/logoteal.png" height="28" width="90" />&nbsp;&nbsp;<span style="font-size:16px;">You are now following ',$firstname,' ',$lastname,'</span>
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:500px;">
		
<img style="border: 1px solid black;margin-left:10px;margin-top:30px;" src="',$profilepic,'" 
height="100px" width="100px" />

<div style="width:500px;margin-left:130px;margin-top:-90px;">
',$firstname,' ',$lastname,'<br />                 

',$numberofpics,' photos <br />

Portfolio Average: ',$portfolioranking,' <br /><br /><br /><br />

</div>
</div>';
            
  }
    }
} 
        
        
        
?>

</div>
</div>
        
    
<!--2nd row of divs-->

<div class="grid_24"> 
<div class="grid_4 pull_3" style="height:40px;width:150px;"><a data-toggle="modal" href="#fwmodal" data-backdrop="static"><button style="margin-top:8px;margin-left:35px;width:125px;" class="btn btn-primary">FOLLOW</button></a></div>	
<div class="grid_16"><div class="photoshadowreel" style="height:20px;width:780px;margin-top:10px;font-size:14px;margin-left:-4px;">
<?php
echo'<span style="margin-left:3px;">';
if($quote == ''){
echo $firstname . ' has not chosen a quote yet';
}
if($quote != ''){
$quoteshort = (strlen($quote) > 120) ? substr($quote,0,110). " &#8230;" : $quote;
echo '<a style="text-decoration:none;" href="viewprofile.php?u=',$user_id,'&view=info#quote">',$quoteshort,'</a>';
}
echo'</span>';
?>
</div></div>	
</div> <!--end of 2nd row of divs-->        

<!--3rd row of divs-->
<div class="grid_24">
<div class="grid_4 pull_3"><div class="photoshadowreel" style="height:210px;width:200px;margin-left:-4px;margin-top:5px;"> 
<!--Information Box-->
<div style="font-size:16px;text-align:center;margin-top:5px;color:black;"><a style="text-decoration:none;" href="../viewprofile.php?u=<?php echo $user_id; ?>"><?php echo $firstname . " " . $lastname; ?></a></div>
<div style="text-align:center;font-size:13px;margin-top:15px;"><span style="color:black;">Reputation</span> <?php echo number_format($ultimatereputation,2); ?></div>
                    <div class="progress" style="width:135px;height:8px;position:relative;left:30px;margin-top:5px;">
                    <div class="bar"
                    style="width: <?php echo $ultimatereputation; ?>%;"> 
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
    echo'<div style="text-align:center;padding-top:5px;padding-bottom:10px;"><img src="../graphics/tophotog.png" height="20" width="80" /></div><br /><br />';
    }
?>

</div></div>


<div class="grid_16"><div style="height:50px;width:780px;"> 

    <div id="makeMeScrollable" style="width:780px;height:50px;overflow:hidden;">
		<a href="../viewprofile.php?u=<?php echo $user_id; ?>"><img class="iconhover" style="padding-right:10px;<?php if($view == ''){echo'background-color:white';} ?>" src="../graphics/mpphotos.png" alt="Demo image" id="field" height="50"  /></a>
        
		<a href="../viewprofile.php?u=<?php echo $user_id; ?>&view=faves"><img class="iconhover" style="padding-right:10px;margin-left:3px;<?php if($view == 'faves'){echo'background-color:white';} ?>" src="../graphics/mpfavorite.png" alt="Demo image" id="field" height="50"  /></a>
        
		<a href="../viewprofile.php?u=<?php echo $user_id; ?>&view=info"><img class="iconhover" style="padding-right:10px;margin-left:3px;<?php if($view == 'info'){echo'background-color:white';} ?>" src="../graphics/mpinfo.png" alt="Demo image" id="field" height="50"/></a>
        
		<a href="../viewprofile.php?u=<?php echo $user_id; ?>&view=followers"><img class="iconhover" style="padding-right:10px;margin-left:3px;<?php if($view == 'followers'){echo'background-color:white';} ?>" src="../graphics/mpfollowers.png" alt="Demo image" id="field" height="50" /></a>
        
		<a href="../viewprofile.php?u=<?php echo $user_id; ?>&view=following"><img class="iconhover" style="padding-right:10px;margin-left:3px;<?php if($view == 'following'){echo'background-color:white';} ?>" src="../graphics/mpfollowing.png" alt="Demo image" id="field" height="50" /></a>
        
		<a href="../viewprofile.php?u=<?php echo $user_id; ?>&view=contact"><img class="iconhover" style="padding-right:10px;margin-left:3px;<?php if($view == 'contact'){echo'background-color:white';} ?>" src="../graphics/mpcontact.png" alt="Demo image" id="field" height="50" /></a>
        
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
<a style="text-decoration:none;color:#333;" name="quote">Quote: ', $quote,'</a>
<br />
<br />
Bio: ', $bio,'
<br />
<br />
</div>
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
		
        
        echo '<a href="http://photorankr.com/viewprofile.php?u=' . $followingid . '"><div class="grid_3 push_1" id="photoshadow" style="margin-top:30px;width:183px;height:260px;">   
<img src="',$followingpic,'" width="183" height="180"/></a>&nbsp&nbsp&nbsp <a class="photonamelink2" href="http://photorankr.com/viewprofile.php?u=',$$followingid,'"><div style="margin-top:0px;font-size:15px;text-align:center;">',$fullname,'</div>
</a></div>';
        
	}

     echo'</div>';
}
else if ($view == 'followers') { //if they are on the followers tab
//show them who is following the person who's profile this is

echo '<div class="grid_24 push_3" style="margin-top: -150px;">'; 


	$followersquery="SELECT * FROM userinfo WHERE following LIKE '%$emailaddress%'";
	$followersresult=mysql_query($followersquery);
	$numberfollowers = mysql_num_rows($followersresult);
    
    
	for($iii = 0; $iii < $numberfollowers; $iii++) {
		$followerpic = mysql_result($followersresult, $iii, "profilepic");
		$followerfirst = mysql_result($followersresult, $iii, "firstname");
		$followerlast = mysql_result($followersresult, $iii, "lastname");
        $fullname = $followerfirst . " " . $followerlast;
        $fullname = ucwords($fullname);
        $followerid = mysql_result($followersresult, $iii, "user_id");
        
        echo '<a href="http://photorankr.com/viewprofile.php?u=' . $followerid . '"><div class="grid_3 push_1" id="photoshadow" style="margin-top:30px;width:183px;height:260px;">   
<img src="',$followerpic,'" width="183" height="180"/></a>&nbsp&nbsp&nbsp <a class="photonamelink2" href="http://photorankr.com/viewprofile.php?u=',$followerid,'"><div style="margin-top:0px;font-size:15px;text-align:center;">',$fullname,'</div>
</a></div>';
	}

     echo '</div>';
}


else if ($view == 'contact') { //if they are on the contact tab

	echo'<div class="grid_10 pull_7" style="font-family: arial; font-size: 20px; margin-top: -740px;">';
	if($_SESSION['loggedin'] == 1) {
	    
		echo' <div style="position:absolute; top: 610px; left: 460px; font-size: 25px; font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
		line-height: 28px; color: #333333;">
    
		<span style="font-size:20px;">Send this photographer a message:</span>
        <br />
		<form method="post" action="../sendmessage2.php" />
		<textarea cols="95" rows="10" style="width:650px" name="message"></textarea>
    		<br />
    		<br />
		<input type="submit" class="btn btn-success" value="Send Message"/>
		<input type="hidden" name="emailaddressofviewed" value="',$emailaddress,'" />
		</form>';
	}
	else {
    		echo' <div style="position:absolute; top: 610px; left: 500px; font-size: 25px; font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
		line-height: 18px;
		color: #333333;">';
		echo 'You must be signed in to contact this person.</div>';
	}

	if($_GET['action'] == "messagesent") {
		echo '<div style="font-size: 20px;">Message Sent!</div>';
	}
    echo '</div>';
    
}

     
else if($view == "faves") {

//find what their faves are
	$email=$_SESSION['email'];

	$favesquery = "SELECT * FROM userinfo WHERE emailaddress='$emailaddress' LIMIT 0, 1";
	$favesresult = mysql_query($favesquery) or die(mysql_error());
	$faves = mysql_result($favesresult, 0, "faves");
    
//run the query returning the results in the order in which they were favorited starting at the photo specified by $x
	$favephotosquery = "SELECT * FROM photos WHERE source IN ($faves) ORDER BY FIELD(source, $faves) DESC LIMIT 9";
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
<a href="../viewprofile.php?u=',$tfpuserid,'">',$tfpfull,'</a>
</div>
</div>';

	//create the images to be displayed    
    	if ($numberofpics2 < 1) {
    		echo '<div style="position:absolute;top:400px;left:340px;font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
line-height: 18px;
color: #333333;font-size:16px;">They do not have any favorites yet.</div>';
    	}

echo'<div id="thepics">';
echo'<div class="grid_16 push_4" id="container" style="width:780px;margin-top:-260px;">';

for($iii=0; $iii < $numberofpics2; $iii++) {
	$image[$iii] = mysql_result($newresult, $iii, "source");
    $imageThumb[$iii] = str_replace("userphotos/","../userphotos/medthumbs/", $image[$iii]);
	$id = mysql_result($newresult, $iii, "id");
    $caption = mysql_result($newresult, $iii, "caption");
    $points = mysql_result($newresult, $iii, "points");
    $votes = mysql_result($newresult, $iii, "votes");
    $faves = mysql_result($newresult, $iii, "faves");
    $score = number_format(($points/$votes),2);
    $faveemail = mysql_result($newresult, $iii, "emailaddress");
    $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$faveemail'");
    $firstname = mysql_result($ownerquery, 0, "firstname");
    $lastname = mysql_result($ownerquery, 0, "lastname");
    $reputation = mysql_result($ownerquery, 0, "lastname");
    $fullname = $firstname . " " . $lastname;
	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
    $heightls = $height / 3.5;
    $widthls = $width / 3.5;

echo '   

<div class="photoshadow fPic" id="',$id,'" style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="http://photorankr.com/fullsize.php?image=', $image[$iii], '">

<div class="statoverlay" style="z-index:1;left:0px;top:160px;position:relative;background-color:black;width:245px;height:90px;"><p style="line-spacing:1.48;padding:5px;color:white;">"',$caption,'"<br>By: ',$fullname,'<br/>Score: ',$score,'<br>Favorites: ',$faves,'</p></div>

<img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:245px;min-width:245px;" src="http://www.photorankr.com/',$imageThumb[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
	    
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
				$("div#loadMoreFavePicsVP").show();
				$.ajax({
					url: "../loadMoreFavePicsVP.php?lastPicture=" + $(".fPic:last").attr("id")+"&user=', $user, '",
					success: function(html) {
						if(html) {
							$("#thepics").append(html);
							$("div#loadMoreFavePicsVP").hide();
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

<div class="well" style="font-size:16px;margin-top:-150px;font-family:helvetica neue, gill sans, helvetica;">Help promote ',$firstname,'\'s  photography by sharing it:<br /><br />

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

</div>
</div>
';

}



//Portfolio View
elseif($view=='') { //they are on the photos tab, which is the main tab	

    $topquery = "SELECT * FROM photos WHERE emailaddress='$emailaddress' ORDER BY (points/votes) DESC";
	$topresult = mysql_query($topquery);
    
    $favequery = "SELECT * FROM photos WHERE emailaddress='$emailaddress' ORDER BY (faves) DESC";
	$faveresult = mysql_query($favequery);
    
	$totalquery = "SELECT * FROM photos WHERE emailaddress='$emailaddress' ORDER BY id DESC";
	$totalresult = mysql_query($totalquery);
	$numberofpics = mysql_num_rows($totalresult);
   
    $infoquery = mysql_query("SELECT totalscore FROM userinfo WHERE emailaddress='$emailaddress'");
    $totalpoints = mysql_result($infoquery, 0, "totalscore");
    
    $setinfoquery = mysql_query("SELECT * FROM sets WHERE owner='$emailaddress'");
    $numsets = mysql_num_rows($setinfoquery);

//$caption = 6;

//$query="SELECT * FROM photos where source='$image'";
//$numberofpics = mysql_query("SELECT * FROM photos WHERE emailaddress='$emailaddress'");
//$namequery="SELECT * FROM userinfo WHERE emailaddress='$emailaddress'";
//$namequery2="SELECT * FROM userinfo WHERE
//$emailaddress='$emailaddress3'";
//$nameresult2=mysql_query($namequery2);




     $insertquery=mysql_query("UPDATE userinfo SET reputation = $ultimatereputation WHERE emailaddress='$emailaddress'");
    mysql_query($insertquery);




//Info Box
echo'
<div class="grid_4 pull_3 photoshadowreel" style="height:100px;width:200px;margin-top:10px;margin-left:-4px;">
<div style="font-size:13px;text-align:center;margin-top:15px;"><span style="color:black;"># Photos</span> ',$numberofpics,'</div>
<div style="font-size:13px;text-align:center;margin-top:5px;"><span style="color:black;">Total Points</span> ',$totalpoints,'</div>
<div style="font-size:13px;text-align:center;margin-top:5px;"><span style="color:black;"># Exhibits</span> ',$numsets,'</div>
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
    <a class="btn btn-primary" style="text-decoration:none;" href="http://photorankr.com/viewprofile.php?u=',$user_id,'">Portfolio</a>&nbsp;&nbsp;&nbsp;
    <a class="btn btn-primary" style="text-decoration:none;" href="http://photorankr.com/viewprofile.php?u=',$user_id,'&ex=y">Exhibits</a>';
        if($exhibit == '') { 
    echo'
         <span style="text-align:center;margin-left:30px;position:relative;top:3px;">
        <a href="http://www.photorankr.com/viewprofile.php?u=',$user_id,'">Newest</a>&nbsp;&nbsp;&nbsp;
        <a href="http://www.photorankr.com/viewprofile.php?u=',$user_id,'&p=top">Top Ranked</a>&nbsp;&nbsp;&nbsp;
        <a href="http://www.photorankr.com/viewprofile.php?u=',$user_id,'&p=fave">Most Favorited</a>&nbsp;&nbsp;&nbsp;
        </span>';
    }
    echo'
    <span style="float:right;"><a href="../viewprofile.php?u=',$user,'&view=promote"><button class="btn btn-warning">Promote ',$firstname,'</button></a></span>
    </div>'; 

if($exhibit == '') { 

if($p == '') {

    echo'<div id="thepics">';
echo'<div class="grid_16 push_4" id="container" style="width:780px;margin-top:-205px;">';

for($iii=0; $iii < 9 && $iii < $numberofpics; $iii++) {
	$image[$iii] = mysql_result($totalresult, $iii, "source");
    $imageThumb[$iii] = str_replace("userphotos/","../userphotos/medthumbs/", $image[$iii]);
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

<div class="photoshadow fPic" id="',$id,'" style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="http://photorankr.com/fullsizeview.php?image=', $image[$iii], '">

<div class="statoverlay" style="z-index:1;left:0px;top:170px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;">"',$caption,'"<br>Score: ',$score,'<br>Favorites: ',$faves,'</p></div>

<img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-75px;min-height:245px;min-width:245px;" src="http://photorankr.com/',$imageThumb[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
	    
      } //end for loop
      
echo'</div>';
echo'</div>';
            
//AJAX CODE HERE
echo'
   <div class="grid_6 push_9" style="top:20px;">
   <div id="loadMorePics" class="grid_24" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;">Loading More Photos&hellip;</div>
   </div>';


echo '<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMorePortfolioPicsVP").show();
				$.ajax({
					url: "../loadMorePortfolioPicsVP.php?lastPicture=" + $(".fPic:last").attr("id"),
					success: function(html) {
						if(html) {
							$("#thepics").append(html);
							$("div#loadMorePortfolioPicsVP").hide();
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

for($iii=0; $iii < 18 && $iii < $numberofpics; $iii++) {
	$image[$iii] = mysql_result($topresult, $iii, "source");
    $imageThumb[$iii] = str_replace("userphotos/","../userphotos/medthumbs/", $image[$iii]);
    $imageThumb[$iii] = str_replace(".JPG",".jpg", $imageThumb[$iii]);
	$id = mysql_result($topresult, $iii, "id");
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

<div class="photoshadow fPic" id="',$ratio,'" style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="http://photorankr.com/fullsizeview.php?image=', $image[$iii], '">

<div class="statoverlay" style="z-index:1;left:0px;top:170px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;">"',$caption,'"<br>Score: ',$score,'<br>Favorites: ',$faves,'</p></div>

<img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-75px;min-height:245px;min-width:245px;" src="http://photorankr.com/',$imageThumb[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
	    
      } //end for loop
      
echo'</div>';
echo'</div>';
            

} //end of p == 'top'


elseif($p == 'fave') {

echo'<div id="thepics">';
echo'<div class="grid_16 push_4" id="container" style="width:780px;margin-top:-205px;">';

for($iii=0; $iii < 18 && $iii < $numberofpics; $iii++) {
	$image[$iii] = mysql_result($faveresult, $iii, "source");
    $imageThumb[$iii] = str_replace("userphotos/","../userphotos/medthumbs/", $image[$iii]);
    $imageThumb[$iii] = str_replace(".JPG",".jpg", $imageThumb[$iii]);
	$id = mysql_result($faveresult, $iii, "id");
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

<div class="photoshadow fPic" id="',$ratio,'" style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="http://photorankr.com/fullsizeview.php?image=', $image[$iii], '">

<div class="statoverlay" style="z-index:1;left:0px;top:170px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;">"',$caption,'"<br>Score: ',$score,'<br>Favorites: ',$faves,'</p></div>

<img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-75px;min-height:245px;min-width:245px;" src="http://photorankr.com/',$imageThumb[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
	    
      } //end for loop
      
echo'</div>';
echo'</div>';
      
 }
 
                       
    } //end of portfolio view
            


        
        
elseif($exhibit == 'y') { //start of exhibit view
//Get view

if(isset($_GET['set'])){
		$set = mysql_real_escape_string($_GET['set']);
	}

//select all exhibits of user
$allsetsquery = "SELECT * FROM sets WHERE owner = '$emailaddress'";
$allsetsrun = mysql_query($allsetsquery);
$numbersets = mysql_num_rows($allsetsrun);
echo'<div style="margin-top:-60px">';

if($numbersets == 0) {
echo'<div class="well" style="font-size:16px;position:relative;left:450px;top:50px;width:130px;">No exhibits yet &#8230;</div>';
}

if($set == '') {

echo'<div class="grid_20 push_4" style="margin-top:-200px;">';

for($iii=0; $iii < $numbersets; $iii++) {
$setname[$iii] = mysql_result($allsetsrun, $iii, "title");
$set_id[$iii] = mysql_result($allsetsrun, $iii, "id");
$setcover = mysql_result($allsetsrun, $iii, "cover");
$setname2[$iii] = (strlen($setname[$iii]) > 30) ? substr($setname[$iii],0,27). " &#8230;" : $setname[$iii];
if($setcover == '') {
$setcover = "../profilepics/nocoverphoto.png";
}

//grab all photos in the exhibit
$grabphotos = "SELECT * FROM photos WHERE emailaddress = '$emailaddress' AND set_id = '$set_id[$iii]'";
$grabphotosrun = mysql_query($grabphotos);
$numphotosgrabbed = mysql_num_rows($grabphotosrun);
    
echo'<div class="grid_3 photoshadow" style="margin-top:20px;width:235px;height:275px;">'; 

echo'
 <a style="text-decoration:none" href="http://photorankr.com/viewprofile.php?u=',$user,'&ex=y&set=',$set_id[$iii],'">
<img onmousedown="return false" oncontextmenu="return false;" src="http://www.photorankr.com/',$setcover,'" width="235" height="230" />
 <br />
 <div style="color:#333;font-size:16px;font-family:arial,helvetica neue;padding-left:5px;padding-top:5px;text-align:left;">
    "',$setname2[$iii],'"</div>

    <span style="text-decoration:none;">&nbsp;',$numphotosgrabbed,' Photos</span></a>
    ';
    echo '</div>';  
}
} //end of set == '' view


elseif($set != '') {
//grab all photos in the exhibit
$grabphotos = "SELECT * FROM photos WHERE emailaddress = '$emailaddress' AND set_id = '$set'";
$grabphotosrun = mysql_query($grabphotos);
$numphotosgrabbed = mysql_num_rows($grabphotosrun);

//grab about this set
$aboutset = "SELECT * FROM sets WHERE owner = '$emailaddress' AND id = '$set' LIMIT 0,1";
$aboutsetrun = mysql_query($aboutset);
$aboutarray = mysql_fetch_array($aboutsetrun);
$aboutset = $aboutarray['about'];
$settitle = $aboutarray['title'];


echo'<div style="position:relative;top:-250px;">

<div class="well grid_14 push_4" style="font-size:16px;width:770px;line-height:25px;"><u>Exhibit:</u> "',$settitle,'"<br /><br /><u>About this exhibit:</u> ',$aboutset,'</div>';

for($iii=0; $iii < $numphotosgrabbed; $iii++) {
$insetname[$iii] = mysql_result($grabphotosrun, $iii, "caption");
$insetsource[$iii] = mysql_result($grabphotosrun, $iii, "source");
$newsource = str_replace("userphotos/","userphotos/thumbs/", $insetsource[$iii]);

echo'<div class="grid_3 push_4 photoshadow" style="margin-top:10px;width:150px;height:150px;"> 
 <a style="text-decoration:none" href="http://photorankr.com/fullsizeview.php?image=',$insetsource[$iii],'">
<img onmousedown="return false" oncontextmenu="return false;" src="http://www.photorankr.com/',$newsource,'" width="150" height="150" />
    </a>';
echo '</div>'; 
}
echo'</div>';
    
} //end of set != '' view

echo'</div>';
} //end of exhibits view


} //end of entire photos page

?>
</div> 




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
