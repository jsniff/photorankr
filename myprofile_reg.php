<?php

//connect to the database
require "db_connection.php";
require "functionsnav.php";
require "timefunction.php";

//Login from front page
if ($_GET['action'] == "log_in") { // if login form has been submitted

@session_start();

        // makes sure they filled it in
        if(!htmlentities($_POST['emailaddress'])) {
            header('Location: signup.php?action=fie');
            die();
        }
        
        if(!htmlentities($_POST['password'])) {
            header('Location: signup.php?action=fip');
            die();
        }


        $check = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '".mysql_real_escape_string($_POST['emailaddress'])."'")or die(mysql_error());
        //Gives error if user dosen't exist

        $check2 = mysql_num_rows($check);
    
        if ($check2 == 0) {
            header('Location: signup.php?action=nu');
            die(); 
        }

        $info = mysql_fetch_array($check);
        
        if(mysql_real_escape_string($_POST['password']) == mysql_real_escape_string($info['password'])){
            //then redirect them to the same page as signed in and set loggedin to 1
            $_SESSION['loggedin'] = 1;
            $_SESSION['email'] = mysql_real_escape_string($_POST['emailaddress']);
        }
        //gives error if the password is wrong
        else if (mysql_real_escape_string($_POST['password']) != mysql_real_escape_string($info['password'])) {
            header('Location: signup.php?action=lp');
            die();   
        }

}



if($_GET['action'] == "signup") { //if they tried to sign up from signin.php
	$firstname = addslashes($_REQUEST['firstname']);
    $firstname = trim($firstname);
    $firstname = ucwords($firstname);
	$lastname = addslashes($_REQUEST['lastname']);
    $optin = addslashes($_REQUEST['optin']);
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
    $currenttime = time();

	//if they forgot to enter any information
	if(!$_REQUEST['firstname'] or !$_REQUEST['lastname'] or !$_REQUEST['emailaddress'] or !$_REQUEST['password'] or !$_REQUEST['confirmpassword'] or !$_REQUEST['terms']) {
		mysql_close();
        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=signup3.php?error=1">';
        exit();
	}
	else if($password != $confirmpassword) { //if passwords dont match
		mysql_close();
        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=signup3.php?error=2">';
        exit();
	}
	//else if that email address is already in the database
	else if($others != 0) {
		header("Location: lostpassword.php");
	}
	else {
		//put their info in database
        $settinglist = " emailcomment emailreturncomment emailfave emailfollow ";
		$newuserquery = "INSERT INTO userinfo (firstname, lastname, emailaddress, password, following, faves, settings, promos, time) VALUES ('$firstname', '$lastname', '$newemail', '$password', '$mattfollow', '$originalfave','$settinglist','$optin','$currenttime')";
		mysql_query($newuserquery);
        
         //newsfeed query
        $type = "signup";
        $newsfeedsignupquery=mysql_query("INSERT INTO newsfeed (firstname, lastname, emailaddress,type,time) VALUES ('$firstname', '$lastname', '$newemail','$type','$currenttime')");
        
        //SEND REGISTRATION GREETING
        
        $to = $newemail;
        $subject = 'Welcome to PhotoRankr!';
        $message = 'Thank you for signing up with PhotoRankr! You can now upload your own photos and sell them at your own price, follow the best photographers, and become part of a growing community. If you have any questions about PhotoRankr or would like to suggest an improvement, you can email us at photorankr@photorankr.com. We greatly value your feedback and hope you will spread the word about PhotoRankr to your friends and family by referring them to the site with the link below:
        
		https://photorankr.com/referral.php        

		Again, welcome to the site!

		Sincerely,
		PhotoRankr';
        $headers = 'From:PhotoRankr <photorankr@photorankr.com>';
        mail($to, $subject, $message, $headers);  
              
		session_start();
		$_SESSION['email'] = $newemail;
		$_SESSION['loggedin'] = 1;
            
    }
}

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
    
    if ($_SESSION['loggedin'] != 1) {
        header("Location: signup.php");
        exit();
    } 

//QUERY FOR NOTIFICATIONS
$currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$email'";
$currentnotsquery = mysql_query($currentnots);
$currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");

//notifications query reset 
if($currentnotsresult > 0) {
$notsquery = "UPDATE userinfo SET notifications = 0 WHERE emailaddress = '$email6'";
$notsqueryrun = mysql_query($notsquery); }

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
  
  //PORTFOLIO RANKING

$followersquery="SELECT * FROM userinfo WHERE following LIKE '%$email%'";
	$followersresult=mysql_query($followersquery);
	$numberfollowers = mysql_num_rows($followersresult);
    
    //Grab Overall Portfolio Ranking
    $userphotos="SELECT * FROM photos WHERE emailaddress = '$email'";
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
    
    $scorequery = "UPDATE userinfo SET totalscore = '$portfoliopoints' WHERE emailaddress = '$email'";    
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
  
  
  //GRAB USER INFORMATION
  $userquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$email'");
  $profilepic = mysql_result($userquery,0,'profilepic'); 
  $email = mysql_result($userquery,0,'emailaddress'); 
  $userid = mysql_result($userquery,0,'user_id'); 
  $firstname = mysql_result($userquery,0,'firstname');
  $lastname = mysql_result($userquery,0,'lastname');
  $fullname = $firstname." ".$lastname; 
  $age = mysql_result($userquery,0,'age');
  $gender = mysql_result($userquery,0,'gender');
  $location = mysql_result($userquery,0,'location');
  $camera = mysql_result($userquery,0,'camera');
  $about = mysql_result($userquery,0,'bio');
  $quote = mysql_result($userquery,0,'quote');
  $fbook = mysql_result($userquery,0,'facebookpage');
  $twitter = mysql_result($userquery,0,'twitteraccount');
  $faves = mysql_result($userquery,0,'faves');
  $reputation = number_format(mysql_result($userquery,0,'reputation'),1);
  $promos = mysql_result($userquery,0,'promos');
  $password = mysql_result($userquery,0,'password');
  $background = mysql_result($userquery,0,'background');
  $background = str_replace('userphotos/','userphotos/medthumbs/',$background);
  
  $view = htmlentities($_GET['view']);
  
  //UPDATE BACKGROUND IMAGE
  if($_GET['mode'] == 'updatebackground') {
  
        $newbg = $_POST['checked'];
        $newbgquery = mysql_query("UPDATE userinfo SET background = '$newbg' WHERE emailaddress = '$email'");
        
    }
  
          
        if($_GET['action'] == 'comment') {
    
            $blogid = htmlentities($_GET['blogid']);
            $comment = mysql_real_escape_string($_POST['comment']);
                    
            $commentinsertion = mysql_query("INSERT INTO blogcomments (comment,blogid,emailaddress) VALUES ('$comment','$blogid','$email')");
            
            $type = 'blogownercomment';
            $blogcommentnewsfeed = mysql_query("INSERT INTO newsfeed (type,source,owner) VALUES ('$type','$blogid','$email')");
            
            echo '<META HTTP-EQUIV="Refresh" Content="0; URL=myprofile.php?view=blog#',$blogid,'">';
            exit();

    
        }
        
        if($_GET['action'] == 'submitpost') {
    
            $blogtitle = mysql_real_escape_string($_POST['title']);
            $blogsubject = mysql_real_escape_string($_POST['subject']);
            $blogcontent = mysql_real_escape_string($_POST['content']);
            $source = mysql_real_escape_string($_POST['checked']);
            $time = mysql_real_escape_string($_POST['time']);
            
            $bloginsertion = mysql_query("INSERT INTO blog (title,subject,content,photo,emailaddress,time) VALUES ('$blogtitle','$blogsubject','$blogcontent','$source','$email','$time')");
            
            $getblogid = mysql_query("SELECT id FROM blog WHERE emailaddress = '$email' ORDER BY id DESC LIMIT 0,1");
            $lastblogid = mysql_result($getblogid,0,'id');
            
            $blognewsfeed = mysql_query("INSERT INTO newsfeed (firstname,lastname,emailaddress,type,source) VALUES ('$firstname','$lastname','$email','blogpost','$lastblogid')");
            
            echo '<META HTTP-EQUIV="Refresh" Content="0; URL=myprofile.php?view=blog">';
            exit();

    
        }
        
        
         if($_GET['action'] == 'submittomarket') {
    
            $source = mysql_real_escape_string($_POST['checked']);
            $newprice = mysql_real_escape_string($_POST['newprice']);
            
            $marketphotoquery = mysql_query("UPDATE photos SET (price = '$newprice', ) WHERE source = '$source' AND emailaddress = '$email'");
    
        }


//Grab OWNERS reputation score
    
 $toprankedphotos2 = "SELECT * FROM photos WHERE emailaddress = '$email' ORDER BY points DESC";
    $toprankedphotosquery2 = mysql_query($toprankedphotos2);
    $numtoprankedphotos2 = mysql_num_rows($toprankedphotos2);

    for($i=0;$i<15;$i++){
    $toprankedphotopoints2 = (mysql_result($toprankedphotosquery2, $i, "points")/mysql_result($toprankedphotosquery2, $i, "votes")) + $toprankedphotopoints2;
    }
        
    $userphotos2="SELECT * FROM photos WHERE emailaddress = '$email'";
    $userphotosquery2=mysql_query($userphotos2);
    $numphotos2=mysql_num_rows($userphotosquery2);
    
    //Gather Total Number of Votes for All Photos (This is Visibility)
    for($ii=0; $ii<$numphotos2;$ii++){
    $totalvotes2 = mysql_result($userphotosquery2, $ii, "votes") + $totalvotes2; 
    }
    

    $followersquery2="SELECT * FROM userinfo WHERE following LIKE '%$email%'";
	$followersresult2 = mysql_query($followersquery2);
    $numberfollowers2 = mysql_num_rows($followersresult2);
    $totalpgviews2 = $totalvotes2;
    $ranking2 = $toprankedphotopoints2;
    $followerlimit2 = 50;
    $totalpgviewslimit2 = 800;
    $rankinglimit2 = 150; 
    $followerweight2 = .3;
    $totalpgviewsweight2 = .3;
    $rankingweight2 = .4; 

    
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
    

    
   if($ranking2 > 140) {
        $rankingweighted2 = $rankingweight2;
    }
    
    elseif($ranking2 > 135) {
        $rankingweighted2 = $rankingweight2 * .90;
    }
    
    elseif($ranking2 <= 135 && $ranking2 > 120) {       
     $rankingweighted2 = $rankingweight2 *.85;
    }
    
    elseif($ranking2 <= 120 && $ranking2 > 105) {
        $rankingweighted2 = $rankingweight2 *.75;
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
        
    if($numphotos2 < 14) { 
    $rankingweighted2 = .1;
    }

    $ultimatereputation = ($followerweighted2+$rankingweighted2+$totalpgviewsweighted2) * 100;


    $insertquery=mysql_query("UPDATE userinfo SET reputation = $ultimatereputation WHERE emailaddress='$email'");
    mysql_query($insertquery);
    

    //HIDE ACTIVITY QUERY
    
    if(htmlentities($_GET['hide']) == 'yes') {
        
        $newsid = $_GET['id'];
        $hidequery = mysql_query("UPDATE newsfeed SET hide = '1' WHERE id = '$newsid'");
        echo 'good';
    }

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="https://www.w3.org/1999/xhtml">

<head>
   <meta property="og:image" content="https://photorankr.com/<?php echo $profilepic; ?>">
   <title><?php echo $firstname . " " . $lastname; ?> | PhotoRankr</title>
   <meta name="Generator" content="EditPlus">
  <meta name="Author" content="PhotoRankr, PhotoRankr.com">
  <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
  <meta name="Description" content="PhotoRankr allows photographers of all skill levels to sell and share their work. Create your photostream cutomized to what you want to see. Add photos to your favorites, rank them, and watch them trend. Build your portfolio with Photorankr.">

  <link rel="stylesheet" type="text/css" href="css/bootstrapNew.css" />
  <link rel="stylesheet" href="text2.css" type="text/css" />
  <link rel="stylesheet" type="text/css" href="css/style.css" />
  <link rel="stylesheet" href="960_24.css" type="text/css" />
  <script type="text/javascript">
    document.write("\<script src='//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js' type='text/javascript'>\<\/script>");
  </script>
  <script type="text/javascript" src="js/jquery.wookmark.js"></script>        
  <script src="bootstrap.js" type="text/javascript"></script>
  <script src="bootstrap-dropdown.js" type="text/javascript"></script>
  <script src="bootstrap-collapse.js" type="text/javascript"></script>
  <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>

<title>PhotoRankr - Newest Photography</title>

  
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

.show { 
display: block;
}

.hide { 
display: none; 
}

 .statoverlay

{
opacity:.6;
filter:alpha(opacity=40);
z-index:1;
transition: opacity .5s;
-moz-transition: opacity .5s;
-webkit-transition: opacity .5s;
-o-transition: opacity .5s;
}



 .statoverlay2

{
opacity:.6;
filter:alpha(opacity=40);
z-index:1;
transition: opacity .5s;
-moz-transition: opacity .5s;
-webkit-transition: opacity .5s;
-o-transition: opacity .5s;
}
            
.statoverlay:hover
{
opacity:.6;
}                

.item {
  margin: 10px;
  float: left;
  border: 2px solid transparent;
}

.item:hover {
  margin: 10px;
  float: left;
  border: 2px solid black;
}

</style>

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

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=433110216717524";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

</head>
<body style="
background-color:#fff;overflow-x:hidden;min-width:1220px;">

<?php navbarnew(); ?>  

<div class="container_24"><!--START CONTAINER-->

<!--LEFT SIDEBAR-->
<div class="grid_24" style="width:1120px;">



<div class="grid_4 pull_2 rounded" style="background-color:#eeeff3;position:relative;top:80px;width:250px;margin-left:10px;">

<div style="width:240px;height:140px;">
<div style="float:left;overflow:hidden;margin-left:15px;margin-top:15px;">
<a href="viewprofile.php?u=<?php echo $userid; ?>"><img class="roundedall" src="<?php echo $profilepic; ?>" alt="<?php echo $fullname; ?>" height="120" width="120"/></a>
</div>
<a style="float:left;width:70px;margin-top:7px;margin-left:10px;font-size:14px;font-weight:150;margin-top:40px;" class="btn btn-success" href="myprofile.php?view=upload"><p class="button_text">Upload </p><div class="grid_1" id="upload" style="margin: 0px 0 0 0;"><img style="margin-top:-2px;" src="graphics/upload_1.png" height="17"/></div></a>
<a class="btn btn-primary" style="float:left;width:70px;margin-top:7px;margin-left:10px;font-size:14px;font-weight:150;" href="myprofile.php?view=promote">Share</a>
</div>

<?php
    if($reputation > 60) {
        echo'<img style="margin-top:-45px;margin-left:100px;" src="graphics/toplens.png" height="80" />';
    }
?>

<div style="width:250px;margin-top:10px;">
<div style="font-size:20px;text-align:center;font-weight:300;"><?php echo $fullname; ?></div>
</div>

<div style="text-align:center;font-size:14px;font-weight:200;width:250px;height:190px;margin-top:15px;">
<p>Reputation: <span style="font-size:20px;"><?php echo $reputation; ?>/</span><span style="font-size:15px;">100</span></p>
<div class="progress progress-success" style="margin-top:-15px;margin-left:28px;width:195px;height:15px;">
   <div class="bar" style="width: <?php echo $reputation; ?>%;"></div>
   </div>

<div style="margin-left:30px;text-align:center;">
   <div style="float:left;"><p>Avg. Portfolio:&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;</p></div>
   <div style="float:left;margin-top:-4px;"><p><span style="font-size:20px;">#</span> Photos</p></div>
</div>

	<div style="position:relative;top:-25px;margin-left:15px;margin-right:15px;text-align:center;font-size:20px;">
   		<div style="width:50%;float:left;"><?php echo $portfolioranking; ?>/<span style="font-size:15px;">10</span></div>
   		<div style="width:50%;float:left;"><?php echo $numphotos; ?></div>
	</div>

<div style="position:relative;left:10px;top:-15px;margin-left:30px;text-align:center;">
   <div style="float:left;"><p>Favorited:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;</p></div>
   <div style="float:left;"><p>Followers:</p></div>
</div>

	<div style="position:relative;top:-35px;margin-left:15px;margin-right:15px;text-align:center;font-size:20px;">
		<div style="width:50%;float:left;"><?php echo $portfoliofaves; ?></div>
		<div style="width:50%;float:left;"><?php echo $numberfollowers; ?></div>
	</div>

</div>


<div style="position:relative;top:-30px;">
<hr>
<a style="text-decoration:none;color:black;font-weight:100;" href="myprofile.php?view=info"><div style="width:250px;margin-top:-10px;padding-bottom:4px;">
<span class="green" style="text-align:center;font-size:20px;padding-left:15px;<?php if($view == 'info' || $view == 'editinfo') {echo'color:#6aae45;';} ?>">Info&nbsp;&nbsp;<img style="float:right;padding-top:5px;padding-right:20px;" src="graphics/info.png" width="25"></span>
</div></a>

<hr>
<a style="text-decoration:none;color:black;font-weight:100;" href="myprofile.php?view=cart"><div style="width:250px;margin-top:-10px;padding-bottom:4px;">
<span class="green" style="text-align:center;font-size:20px;padding:15px;<?php if($view == 'cart') {echo'color:#6aae45;';} ?>">My Cart&nbsp;&nbsp;<img style="float:right;padding-top:5px;padding-right:20px;" src="market/graphics/cart.png" width="25"></span>
</div></a>

<hr>
<a style="text-decoration:none;color:black;font-weight:100;" href="myprofile.php?view=network"><div style="width:250px;margin-top:-10px;padding-bottom:4px;">
<span class="green" style="text-align:center;font-size:20px;padding:15px;<?php if($view == 'network') {echo'color:#6aae45;';} ?>">Network&nbsp;&nbsp;<img style="float:right;padding-top:5px;padding-right:20px;" src="graphics/follower.png" width="35"></span>
</div></a>

<hr>
<a style="text-decoration:none;color:black;font-weight:100;" href="myprofile.php?view=favorites"><div style="width:250px;margin-top:-10px;padding-bottom:4px;">
<span class="green" style="text-align:center;font-size:20px;padding:15px;<?php if($view == 'favorites') {echo'color:#6aae45;';} ?>">Favorites&nbsp;&nbsp;<img style="float:right;padding-top:5px;padding-right:20px;" src="graphics/fave.png" width="25"></span>
</div></a>

<hr>
<a style="text-decoration:none;color:black;font-weight:100;" href="myprofile.php?view=messages"><div style="width:250px;margin-top:-10px;padding-bottom:4px;">
<span class="green" style="text-align:center;font-size:20px;padding:15px;<?php if($view == 'messages' || $view == 'viewthread') {echo'color:#6aae45;';} ?>">Messages&nbsp;&nbsp;<img style="float:right;padding-top:5px;padding-right:25px;"src="graphics/messages.png" width="25"></span>
</div></a>

<hr>
<a style="text-decoration:none;color:black;font-weight:100;" href="myprofile.php?view=settings"><div style="width:250px;margin-top:-10px;padding-bottom:4px;">
<span class="green" style="text-align:center;font-size:20px;padding:15px;<?php if($view == 'settings') {echo'color:#6aae45;';} ?>">Settings&nbsp;&nbsp;<img style="float:right;padding-top:5px;padding-right:20px;"src="graphics/settings.png" width="25"></span>
</div></a>
</div>

</div><!--end 4 grid-->

<div class="grid_18 roundedright pull_1" style="background-color:#eeeff3;height:50px;margin-top:80px;width:830px;margin-left:-43px;">

<a style="text-decoration:none;color:black;" href="myprofile.php"><div class="clicked" style="width:150px;height:50px;border-right:1px solid #ccc;border-left:1px solid #ccc;float:left;<?php if($view == '') {echo'background-color:#bbb;color:white;';} ?>"><div style="font-size:18px;font-weight:100;margin-top:10px;text-align:center;">My Activity</div></div></a>

<a style="text-decoration:none;color:black;" href="myprofile.php?view=portfolio"><div class="clicked" style="width:150px;height:50px;border-right:1px solid #ccc;border-left:1px solid #ccc;float:left;<?php if($view == 'portfolio') {echo'background-color:#bbb;color:white;';} ?>"><div style="font-size:18px;font-weight:100;margin-top:10px;text-align:center;">My Portfolio</div></div></a>

<a style="text-decoration:none;color:black;" href="myprofile.php?view=store"><div class="clicked" style="width:150px;height:50px;border-right:1px solid #ccc;float:left;<?php if($view == 'store') {echo'background-color:#bbb;color:white;';} ?>"><div style="font-size:18px;font-weight:100;margin-top:10px;text-align:center;">My Store</div></div></a>

<a style="text-decoration:none;color:black;" href="myprofile.php?view=blog"><div class="clicked" style="width:150px;height:50px;border-right:1px solid #ccc;float:left;<?php if($view == 'blog') {echo'background-color:#bbb;color:white;';} ?>"><div style="font-size:18px;font-weight:100;margin-top:10px;text-align:center;">My Blog</div></div></a>

<div style="width:80px;height:50px;float:left;"><div style="font-size:20px;font-weight:100;margin-top:6px;text-align:center;margin-left:10px;">
<form class="navbar-search" action="myprofile.php?view=search" method="post">
<input class="search" style="position:relative;margin-left:15px;margin-top:0px;" name="searchterm" type="text" placeholder="Search Portfolio&#8230" >
</form></div></div>


<?php

    if($view == '') {
    
    
        $activityquery = mysql_query("SELECT * FROM newsfeed WHERE hide <> 1 AND (emailaddress = 
        '$email' OR owner = '$email') AND type IN ('follow','comment','fave','photo') ORDER BY id DESC LIMIT 13");
        
        echo'
    <div id="thepics" style="position:relative;width:810px;margin-left:0px;top:60px;">
    <div id="main" role="main">
    <ul id="tiles">';
        
        for($iii = 0; $iii < 12; $iii++) {
            
            //(strlen($caption) > 28) ? substr($caption,0,25). " &#8230;" : $caption;

            $type = mysql_result($activityquery,$iii,'type');
            $id = mysql_result($activityquery,$iii,'id');
            $owner = mysql_result($activityquery,$iii,'owner');
            $commenter = mysql_result($activityquery,$iii,'emailaddress');
            $commentimageid = mysql_result($activityquery,$iii,'imageid');
            $time = mysql_result($activityquery,$iii,'time');
            
            $getcommentid = mysql_query("SELECT comment FROM comments WHERE id = '$commentimageid'");
            $comment = mysql_result($getcommentid,0,'comment');
            
            $source = mysql_result($activityquery,$iii,'source');
            
            $getimageid = mysql_query("SELECT id FROM photos WHERE source = '$source'");
            $sourceid = mysql_result($getimageid,0,'id');
            list($width,$height) = getimagesize($source);
            $newwidth = $width/3.2;
            $newheight = $height/3.2;
            
            if($newwidth < 195) {
                $newheight = $newheight * ($newheight/$newwidth);
                $newwidth = 240;
            }

            $newsemail = mysql_result($activityquery,$iii,'emailaddress');
            $caption = mysql_result($activityquery,$iii,'caption');
            $followemail = mysql_result($activityquery,$iii,'following');
            
            $following = mysql_query("SELECT user_id,firstname,lastname,emailaddress,profilepic FROM userinfo WHERE emailaddress = '$followemail'");
            $ownerid = mysql_result($following,0,'user_id');
            $followername = mysql_result($following,0,'firstname') ." ". mysql_result($following,0,'lastname');
            $followpic = mysql_result($following,0,'profilepic');
            if($followpic == "") {
                $followpic = "profilepics/default_profile.jpg";
            }
            
            $commenter = mysql_query("SELECT user_id,firstname,lastname,emailaddress,profilepic FROM userinfo WHERE emailaddress = '$commenter'");
            $commenterid = mysql_result($commenter,0,'user_id');
            $commentername = mysql_result($commenter,0,'firstname') ." ". mysql_result($commenter,0,'lastname');
            $commenterpic = mysql_result($commenter,0,'profilepic');
            if($commenterpic == "") {
                $commenterpic = "profilepics/default_profile.jpg";
            }
            
            $cnquery = mysql_query("SELECT user_id,firstname,lastname FROM userinfo WHERE emailaddress = '$owner'");
            $cn = mysql_result($cnquery,0,'firstname') ." ". mysql_result($cnquery,0,'lastname');
            $cnid = mysql_result($cnquery,0,'user_id');
            
            $followerpics = mysql_query("SELECT id,source FROM photos WHERE emailaddress = '$followemail' ORDER BY (points) DESC LIMIT 0,4");
            $numprofilepics = mysql_num_rows($followerpics);
            $profileimage = mysql_result($followerpics,0,'source'); 
            $profileimage = str_replace('userphotos/','userphotos/thumbs/',$profileimage);
            $profileimage2 = mysql_result($followerpics,1,'source');
            $profileimage2 = str_replace('userphotos/','userphotos/thumbs/',$profileimage2);
            $profileimage3 = mysql_result($followerpics,2,'source');
            $profileimage3 = str_replace('userphotos/','userphotos/thumbs/',$profileimage3);
            $profileimage4 = mysql_result($followerpics,3,'source');
            $profileimage4 = str_replace('userphotos/','userphotos/thumbs/',$profileimage4);
    
                        
                if($type == 'photo') {
                    
                   echo'<li class="fPic photobox" id="',$id,'" style="padding:5px;margin-top:10px;list-style-type: none;width:240px;
">

                    <div style="width:100%;"><div style="float:left;height:60px;"><img style="max-height:40px;" src="',$commenterpic,'" /></div>&nbsp;&nbsp;<div style="float:left;padding-left:8px;width:180px;"><img src="graphics/upload.png" width="25" />&nbsp;&nbsp;',$commentername,' uploaded "',$caption,'"
                    
                    <div style="color:#555;font-weight:500;margin-left:0px;">';if($time > 0) {echo'',converttime($time),'';} echo'</div> 

                    </div>
                    <hr /></div>
                    
                    <a href="fullsize.php?imageid=',$sourceid,'"><img src="',$source,'" width="',$newwidth,'px" height="',$newheight,'px" /></a>
                    </li>';
               
                }
                
                elseif($type == 'follow') {
                
                        
                
                      echo'<li class="fPic photobox" id="',$id,'" style="padding:5px;margin-top:10px;list-style-type: none;width:240px;
">

                     <div style="width:100%;"><div style="float:left;height:60px;"><img style="max-height:40px;" src="',$profilepic,'" /></div>&nbsp;&nbsp;<div style="float:left;padding-left:8px;width:180px;"><img src="graphics/follower.png" width="35" />&nbsp;&nbsp;<a href="viewprofile.php?u=',$cnid,'">',$firstname,' ',$lastname,'</a> followed <a href="viewprofile.php?u=',$ownerid,'">',$followername,'</a>
                     
                    <div style="color:#555;font-weight:500;">';if($time > 0) {echo'',converttime($time),'';} echo'</div>

                     </div>
                     <hr /></div>
                     
                     <div><a href="viewprofile.php?u=',$ownerid,'"><img style="float:left;max-height:80px;margin-top:-30px;" src="',$followpic,'" /></a>
                     
                     <div style="width:230px;height:100px;font-size:18px;margin-left:10px;margin-top:40px;"><i><div style="text-align:center;">',$followername,'</div></i></div>
                     </div>
                     
                     <div style="width:240px;">';
                    if($numprofilepics > 3){echo'<img style="padding:3px;" src="',$profileimage,'" height="110" width="110" /><img style="padding:3px;" src="',$profileimage2,'" height="110" width="110" /><img style="padding:3px;" src="',$profileimage3,'" height="110" width="110" /><img style="padding:3px;" src="',$profileimage4,'" height="110" width="110" />';}
                    echo'</div>
                     
                     </li>
                     <br />';
                    
                }
                
                elseif($type == 'comment') {
                    
                     echo'<li class="fPic photobox" id="',$id,'" style="padding:5px;margin-top:10px;list-style-type: none;width:240px;
">

                    <div style="width:100%;"><div style="float:left;height:60px;"><img style="max-height:40px;" src="',$commenterpic,'" /></div>&nbsp;&nbsp;<div style="float:left;padding-left:8px;width:180px;"><img src="graphics/comment.png" width="25" />&nbsp;&nbsp;<a href="viewprofile.php?u=',$commenterid,'">',$commentername,'</a> commented on <a href="viewprofile.php?u=',$cnid,'">',$cn,'\'s</a> photo
                    
                    <div style="color:#555;font-weight:500;">';if($time > 0) {echo'',converttime($time),'';} echo'</div>

                    </div>
                    <hr /></div>
                    
                    <a href="fullsize.php?imageid=',$sourceid,'">
                    
                    <img src="',$source,'" width="',$newwidth,'px" height="',$newheight,'px" />                    
                    </a>';
                    
                    if($comment) {
                    echo'
                    <div style="font-size:15px;width:220px;padding:10px;margin-top:20px;">"',$comment,'"</div>';
                    }
                    
                    echo'
                    </li>
                    <br />';
                
                
                }
                
                elseif($type == "blogcomment") {
                
                    
                
                }
                
                elseif($type == "fave") {
                
                    echo'<li class="fPic photobox" id="',$id,'" style="padding:5px;margin-top:10px;list-style-type: none;width:240px;
">
                        <div style="width:100%;"><div style="float:left;height:60px;"><img style="max-height:40px;" src="',$commenterpic,'" /></div>&nbsp;&nbsp;
                        <div style="float:left;padding-left:8px;width:180px;"><img src="graphics/fave.png" width="25" />&nbsp;&nbsp;<a href="viewprofile.php?u=',$commenterid,'">',$commentername,'</a> favorited <a href="viewprofile.php?u=',$cnid,'">',$cn,'\'s </a> photo
                    
                    <div style="color:#555;font-weight:500;margin-left:0px;">';if($time > 0) {echo'',converttime($time),'';} echo'</div> 

                    </div>
                    <hr /></div>
                    
                    <a href="fullsize.php?imageid=',$sourceid,'"><img src="',$source,'" width="',$newwidth,'px" height="',$newheight,'px" /></a>
                    
                    </li>';
                
                }
        
        } //end of for loop

        
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
        itemWidth: 260 // Optional, the width of a grid item
      };
      
      // Get a reference to your grid items.
      var handler = $('#tiles li');
      
      // Call the layout function.
      handler.wookmark(options);
      
    });
  </script>

    
 <?php       
        
        echo'</div>';
        echo'</div>';
        
echo'

<!--AJAX CODE HERE-->
   <div class="grid_18" style="padding-top:50px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;">Loading&hellip;</div>
   </div>';


echo '<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMorePics").show();
				$.ajax({
					url: "loadMoreMyActivity.php?lastPicture=" + $(".fPic:last").attr("id"),
					success: function(html) {
						if(html) {
							$("#thepics").append(html);
							$("div#loadMorePics").hide();
						}
					}
				});
				last = $(".fPic:last").attr("id");
			}
		}
	});
</script>';
        
}

    if($view == 'portfolio') {
    
        $option = htmlentities($_GET['option']);    
    
        echo'<br /><br /><br /><br /><div style="width:760px;text-align:center;font-size:14px;font-weight:200;"><div style="margin-left:35px;"><a class="green" style="text-decoration:none;color:#333;" href="editphotos.php">Edit Portfolio</a> | <a class="green" style="text-decoration:none;'; if($option == '') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="myprofile.php?view=portfolio">Newest</a> | <a class="green" style="text-decoration:none;color:#333;'; if($option == 'top') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="myprofile.php?view=portfolio&option=top">Top Ranked</a> | <a class="green" style="text-decoration:none;color:#333;'; if($option == 'fave') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="myprofile.php?view=portfolio&option=fave">Most Favorited</a> | <a class="green" style="text-decoration:none;color:#333;" href="myprofile.php?view=exhibits">Exhibits</a></div></div>';
        
        if($_GET['action'] == 'signup') {
        echo'<div class="grid_18" style="width:770px;margin-top:-34px;margin-left:-10px;padding:35px;text-align:center;font-size:16px;font-family:helvetica;font-weight:200;">
        <br /><br />
        Welcome to your new PhotoRankr profile. Here are a couple pointers to get you started:
        <br /><br />
        <a href="myprofile.php?view=editinfo">Click here</a> to finish filling out your photographer profile.
        <br /><br />
        <a href="myprofile.php?view=upload">Click here</a> to begin uploading your best shots.
        <br /><br />
        <a href="newsfeed.php">Click here</a> to view your photostream of your followers on PhotoRankr
        <br />.
        </div>'; 
        }
        
        if($option == '') {        
        $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$email' ORDER BY id DESC LIMIT 0,21");
        $numresults = mysql_num_rows($query);
        }
        
        elseif($option == 'top') {
        $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$email' AND views > 20 ORDER BY (points/votes) DESC LIMIT 0,21");
        $numresults = mysql_num_rows($query);
        }
                
        elseif($option == 'fave') {
        $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$email' ORDER BY faves DESC LIMIT 0,21");
        $numresults = mysql_num_rows($query);
        }
        
    echo'
    <div id="thepics" style="position:relative;width:780px;margin-left:15px;">
    <div id="main" role="main">
    <ul id="tiles">';

        for($iii=0; $iii < $numresults; $iii++) {
              
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

                 echo'<a style="text-decoration:none;color:#000;" href="fullsizeme.php?imageid=',$id,'"><li class="fPic photobox" id="',$id,'" style="padding:5px;margin-right:10px;margin-top:10px;list-style-type: none;width:240px;
"><img onmousedown="return false" oncontextmenu="return false;" src="https://photorankr.com/',$imageThumb[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /><div style="padding:3px;"><div style="float:left;">',$caption,'</div><div style=float:right;font-size:13px;font-weight:500;">',$price,'</div><br /><span style="font-size:14px;">',$score,'/</span><span style="font-size:12px;color:#444;">10.0</span><br /><i class="icon-heart"></i>&nbsp;',$faves,' favorites</div></li></a>';
	    
                } //end for loop 
                
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
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMorePortfolioPics").show();
				$.ajax({
					url: "loadMorePortfolioPics3.php?lastPicture=" + $(".fPic:last").attr("id"),
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

        }
        
    
    elseif($view == 'exhibits') {
    
    echo'<br /><br /><br /><br /><div style="width:760px;text-align:center;font-size:14px;font-weight:200;"><div style="margin-left:20px;"><a class="green" style="text-decoration:none;color:#333;" href="myprofile.php?view=portfolio">Newest</a> | <a class="green" style="text-decoration:none;color:#333;" href="myprofile.php?view=portfolio&option=top">Top Ranked</a> | <a class="green" style="text-decoration:none;color:#333;" href="myprofile.php?view=portfolio&option=fave">Most Favorited</a> | <a class="green" style="text-decoration:none;color:#333;'; if($view == 'exhibits') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="myprofile.php?view=exhibits">Exhibits</a></div></div>';


        if(isset($_GET['set'])){
		$set = mysql_real_escape_string($_GET['set']);
	}
    
    //get exhibit mode
if(isset($_GET['mode'])){
		$mode = ($_GET['mode']);
	}
    
if($mode == 'delete') {

$image = htmlentities($_GET['image']);
$set = htmlentities($_GET['set']);

$getsetid = mysql_query("SELECT set_id FROM photos WHERE source = '$image'");
$set_id = mysql_result($getsetid,0,'set_id');
$newset_id = str_replace($set,"",$set_id);

$deletephotofromset = mysql_query("UPDATE photos SET set_id = '$newset_id' WHERE source = '$image'");

echo '<META HTTP-EQUIV="Refresh" Content="0; URL=myprofile.php?view=exhibits&set=',$set,'">';
exit();

}

elseif($mode == 'added') {
//add checked photos to existing exhibit

if(!empty($_POST['addthese'])) {
    foreach($_POST['addthese'] as $checked) {
        $setnew = $set ." ";
        //insert each checked photo into corresponding set
        $checkedset = "UPDATE photos SET set_id = CONCAT(set_id,'$setnew') WHERE source = '$checked'";
        $checkedsetrun = mysql_query($checkedset);
        }
        }
	
}

elseif($mode == 'coverchanged') {
//edit existing exhibit

    $newcaption = mysql_real_escape_string($_POST['caption']);
    $newaboutset = mysql_real_escape_string($_POST['aboutset']);
    $newcover = mysql_real_escape_string($_POST['addthis']);
        
    $exhibitchange = "UPDATE sets SET about = '$newaboutset', title = '$newcaption', cover = '$newcover' WHERE id = '$set'  AND owner = '$email'";
    $exhibitrun = mysql_query($exhibitchange);
        	
}

elseif($mode == 'deleteexhibit') {

    $deleteexhibit = mysql_query("DELETE FROM sets WHERE id = '$set' AND owner = '$email'");
    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=myprofile.php?view=exhibits">';

}

//select all exhibits of user
$allsetsquery = "SELECT * FROM sets WHERE owner = '$email'";
$allsetsrun = mysql_query($allsetsquery);
$numbersets = mysql_num_rows($allsetsrun);
echo'<div style="margin-top:-60px">';

if($numbersets == 0) {
echo'<div style="font-size:18px;font-weight:200;padding:40px;text-align:center;margin-left:-35px;margin-top:120px;"><a style="color:#333;" href="myprofile.php?view=upload&option=newexhibit">Click here to create your first exhibit.</a></div>';
}

if($set == '' & $numbersets > 0) {

echo'<div class="grid_18" style="width:770px;margin-top:22px;margin-left:-10px;padding:35px;"><a href="myprofile.php?view=upload&option=newexhibit"><button class="btn btn-success">Create New Exhibit</button></a><br /><br /></div>

    <div id="thepics" style="position:relative;width:780px;margin-left:15px;top:110px;">
    <div id="main" role="main">
    <ul id="tiles">';

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
$grabphotos = "SELECT * FROM photos WHERE emailaddress = '$email' AND set_id LIKE '%$set_id[$iii]%'";
$grabphotosrun = mysql_query($grabphotos);
$numphotosgrabbed = mysql_num_rows($grabphotosrun);


    echo'<li class="photobox" style="width:240px;list-style-type:none;"><a style="text-decoration:none;" href="myprofile.php?view=exhibits&set=',$set_id[$iii],'">
    
    <div style="width:100%;">
    
    <div style="padding-top:5px;padding-left:3px;font-size:13px;text-decoration:none;color:#000;font-weight:200;"><span style="font-size:15px;font-weight:400;">',$setname2[$iii],'</span><br />',$numphotosgrabbed,' Photos</div>
<hr />

    <img style="margin-top:-6px;" onmousedown="return false" oncontextmenu="return false;" src="https://www.photorankr.com/',$setcover,'" alt="',$setname[$iii],'" height="',$heightls,'px" width="',$widthls,'px" />';
    
    if($thumb4) {
        echo'
            <div>
            <img style="float:left;padding:5px;" src="https://www.photorankr.com/',$thumb1,'" width="110" height="110" />
            <img style="float:left;padding:5px;" src="https://www.photorankr.com/',$thumb2,'" width="110" height="110" />
            <img style="float:left;padding:5px;" src="https://www.photorankr.com/',$thumb3,'" width="110" height="110" />
            <img style="float:left;padding:5px;" src="https://www.photorankr.com/',$thumb4,'" width="110" height="110" />
            </div>';
    }
    
    echo'
    </a>
    
    </li><br />';
    
} //end of for loop

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

echo'</div>
</div>';

} //end of set == '' view


elseif($set != '') {

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

//grab all photos in the exhibit
$grabphotos = "SELECT * FROM photos WHERE emailaddress = '$email' AND set_id LIKE '%$set%'";
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

echo'<div class="grid_18" style="width:770px;margin-top:22px;margin-left:-10px;padding:35px;">

<div class="grid_14 well" style="position:relative;clear:both;width:735px;line-height:25px;margin-top:15px;"><span style="font-size:25px;font-family:helvetica,arial;font-weight:200;">',$settitle,'</span><br />';
if($aboutset) {echo'
    <br />
    <span style="font-size:16px;font-family:helvetica,arial;font-weight:200;">',        $aboutset,'</span>';
}
echo'
<div style="float:bottom;margin-top:10px;clear:both;">
<a data-toggle="modal" data-backdrop="static" href="#add"><button class="btn btn-success">Add Photos to Exhibit</button></a>&nbsp;&nbsp;
<a data-toggle="modal" data-backdrop="static" href="#editexhibit"><button class="btn btn-success">Edit Exhibit</button></a></div>
</div>';

echo'

    <div id="thepics" style="position:relative;width:780px;clear:both;">
    <div id="main" role="main">
    <ul id="tiles">';

for($iii=0; $iii < $numphotosgrabbed; $iii++) {
    $insetname[$iii] = mysql_result($grabphotosrun, $iii, "caption");
    $insetsource[$iii] = mysql_result($grabphotosrun, $iii, "source");
    $newsource = str_replace("userphotos/","userphotos/medthumbs/", $insetsource[$iii]);
    $caption = mysql_result($grabphotosrun, $iii, "caption");
    $faves = mysql_result($grabphotosrun, $iii, "faves");
    $price = mysql_result($grabphotosrun, $iii, "price");
    if($price != 'Not For Sale') {
                    $price = '$' . $price;
                }
                elseif($price == 'Not For Sale') {
                    $price = 'NFS';
                }
    $points = mysql_result($grabphotosrun, $iii, "points");
    $votes = mysql_result($grabphotosrun, $iii, "votes");
    $score = number_format(($points/$votes),2);
    
        list($width, $height) = getimagesize($insetsource[$iii]);
        $imgratio = $height / $width;
        $heightls = $height / 3.2;
        $widthls = $width / 3.2;
        
        if($widthls < 240) {
            $heightls = $heightls * ($heightls/$widthls);
            $widthls = 250;
        }
                
    echo'<li style="list-style-type:none;width:240px;" class="photobox">

    <a style="text-decoration:none;" href="fullsizeme.php?image=',$insetsource[$iii],'"><img onmousedown="return false" oncontextmenu="return false;"  src="',$newsource,'" alt="',$caption,'" height="',$heightls,'px" width="',$widthls,'px" /></a>
    
    <div style="padding:3px;"><div style="float:left;">',$caption,'</div><div style=float:right;font-size:13px;font-weight:500;">',$price,'&nbsp;&nbsp; <a style="color:#333;text-decoration:none;" href="myprofile.php?view=exhibits&set=',$set,'&image=',$insetsource[$iii],'&mode=delete"><span style="float:right;">X</span></a></div><br /><i class="icon-heart"></i>&nbsp;',$faves,' favorites</div>
        
    </li>';
 
    } //end for loop

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
 
 echo'
    </div>
    </div>';

   
   }
   
   
   
        //Add Photos to Exhibit Modal

echo'<div class="modal hide fade" id="add" style="overflow-y:scroll;overflow-x:hidden;border:5px solid rgba(102,102,102,.8);">

<div class="modal-header" style="background-color:#111;color:#fff;">
<a style="float:right" class="btn btn-success" data-dismiss="modal">Close</a>
<img style="margin-top:-2px;" src="graphics/aperture_white.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">Add photos to your exhibit below:</span>
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:550px;height:500px;overflow-x:hidden;background-color:rgb(245,245,245);">
		
<img style="border: 1px solid black;margin-left:10px;margin-top:10px;" src="',$setcover,'" 
height="100px" width="100px" />

<div style="width:540px;margin-left:130px;margin-top:-100px;overflow-y:scroll;overflow-x:hidden;">

<form action="myprofile.php?view=exhibits&set=',$set,'&mode=added" method="post" enctype="multipart/form-data">
    <span style="font-size:14px;">
    Exhibit Name:&nbsp;&nbsp;',$settitle,'
    <br />
    <br />';
    if($aboutset) {
        echo'
        About this Exhibit:&nbsp;&nbsp;
        ',stripslashes($aboutset),'
        <br /><br />';
    }
    echo'
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
        echo'<img src="',$newsource,'" alt="',$userphotoscaption[$iii],'" height="70" width="70" />&nbsp;&nbsp;<input type="checkbox" name="addthese[]" value="',      $userphotosource[$iii],'" />&nbsp;"',$userphotoscaption[$iii],'"
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
        
    
    }
    
        elseif($view == 'stats') { 
         
         $profileviews = mysql_result($userquery,0,'profileviews');
         $buyerprofileviews = mysql_result($userquery,0,'buyerprofileviews');
        
          $allusersphotosquery = "SELECT * FROM photos WHERE emailaddress = '$email' ORDER BY id DESC";
          $query = mysql_query($allusersphotosquery);
          $numphotos = mysql_num_rows($query);
         
         for($iii=0; $iii<$usernumphotos; $iii++) {
                 
            $photoviews .= mysql_result($query,$iii,'views');
            $usermarketviews .= mysql_result($query,$iii,'usermarketviews');
            $buyermarketviews .= mysql_result($query,$iii,'buyermarketviews');
             $source .= mysql_result($query,$iii,'source');
        
         }
         
         echo'<div class="grid_18" style="margin-top:60px;font-size:16px;">Network Profile Views: ',
         
         $profileviews,'
         <br /><br />Buyer Profile Views: ',
         $buyerprofileviews,'
         
         <br /><br />Total Photo Views: ',
         $photoviews,'
         
         <br /><br />Photographer Market Views: ',
         $usermarketviews,'
         
         <br /><br />Buyer Market Views: ',
         $source,'
         
         </div>';
         
        }
    
    elseif($view == 'info') {
        
        echo'
        <div class="span9" style="margin-top:0px;margin-left:-5px;padding:67px;padding-top:40px;background-color:rgba(245,245,245,0.6);">
        <table class="table">
        <tbody>';

        if($age) {
        echo'
        <tr>
        <td>Age:</td>
        <td>',$age,'</td>
        </tr>'; }

        if($location) {
        echo'
        <tr>
        <td>From:</td>
        <td>',$location,'</td>
        </tr>'; }

        if($gender) {
        echo'
        <tr>
        <td>Gender:</td>
        <td>',$gender,'</td>
        </tr>'; }

        if($camera) {
        echo'
        <tr>
        <td>Camera:</td>
        <td>',$camera,'</td>
        </tr>'; }

        if($fbook) {
        echo'
        <tr>
        <td>Facebook Page:</td>
        <td><a href="',$fbook,'">',$fbook,'</a></td>
        </tr>'; }

        if($twitter) {
        echo'
        <tr>
        <td>Twitter:</td>
        <td><a href="',$twitter,'">',$twitter,'</a></td>
        </tr>'; }

        if($quote) {
        echo'
        <tr>
        <td>Quote:</td>
        <td>',$quote,'</td>
        </tr>'; }

        if($about) {
        echo'
        <tr>
        <td>About:</td>
        <td>',$about,'</td>
        </tr>'; }

        echo'
        </tbody>
        </table>
        <a class="btn btn-success" href="myprofile.php?view=editinfo">Edit Profile</a>
        </div>';
    
    }
    
    
    elseif ($view == 'editinfo') { //if they are on the edit info tab
	//see if they have submitted the form
	$action = htmlentities($_GET['action']);
	if($action == 'submit') {

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
                    chmod($profilepic, 0777);
                    
                    createprofthumbdim($profilepic);
        			createprofthumbnail($profilepic);
					
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
        echo '<div style="margin-top:20px;margin-left:60px;color:#6aae45;float:left;font-size:20px;font-weight:200;">Profile Saved</div><br />';
    }

echo'

        <div class="span9" style="margin-top:0px;;margin-left:-5px;padding:20px;padding-left:67px;background-color:rgba(245,245,245,0.6);">
        <span style="font-size:18px;font-weight:200;">Edit Your Information:</span>
        <br /><br />
        <form action="myprofile.php?view=editinfo&action=submit" method="post" enctype="multipart/form-data">
        <table class="table">
        <tbody>
        
        <tr>
        <td>Firstname:</td>
        <td><input style="width:180px;height:20px;" type="text" name="firstname" value="', $firstname, '"/></td>
        </tr>
        
        <tr>
        <td>Lastname:</td>
        <td><input style="width:180px;height:20px;" type="text" name="lastname" value="', $lastname, '"/></td>
        </tr>

        <tr>
        <td>Age:</td>
        <td><input style="width:50px;height:20px;" type="text" name="age" value="', $age, '"/></td>
        </tr>

        <tr>
        <td>From:</td>
        <td><input style="width:180px;height:20px;" type="text" name="location" value="', $location, '"/></td>
        </tr> 

        <tr>
        <td>Gender:</td>
        <td>';
            if ($gender == 'Male') {
                echo '<input type="radio" name="gender" value="Male" checked="checked" /> Male&nbsp;&nbsp; 
                <input type="radio" name="gender" value="Female" /> Female&nbsp;&nbsp;';
            }
            else {
                echo '<input type="radio" name="gender" value="Male" /> Male&nbsp;&nbsp; 
                <input type="radio" name="gender" value="Female" checked="checked" /> Female&nbsp;&nbsp;';
            }
            echo '</td>
        </tr>

        <tr>
        <td>Camera:</td>
        <td><input style="width:180px;height:20px;" type="text" name="camera" value="', $camera, '"/></td>
        </tr>

        <tr>
        <td>Facebook Page:</td>
        <td><a href="',$facebookpage,'"><input style="width:180px;height:20px;" type="text" name="facebookpage" value="',$fbook,'"/></a></td>
        </tr>

        <tr>
        <td>Twitter:</td>
        <td><a href="',$twitteraccount,'"><input style="width:180px;height:20px;" type="text" name="twitteraccount" value="',$twitter,'"/></a></td>
        </tr>

        <tr>
        <td>Quote:</td>
        <td><textarea style="width:500px" rows="2" cols="100" name="quote">',stripslashes($quote),'</textarea></td>
        </tr>
        
        <tr>
        <td>Change Password:</td>
        <td><input type="password" style="width:180px;height:25px;"  name="password" value="', $password, '"/></td>
        </tr>
        
        <tr>
        <td>Confirm Password:</td>
        <td><input type="password" style="width:180px;height:25px;"  name="confirmpassword" value="', $password, '"/></td>
        </tr>
        
        <tr>
        <td>Change Profile Photo:</td>
        <td><img src="',$profilepic,'" height="30" width="30" />&nbsp;&nbsp;&nbsp;<input type="file"  name="file" value="', $profilepic, '"/></td>
        </tr>
        
        <tr>
        <td>About:</td>
        <td><textarea style="width:500px" rows="6" cols="100" name="bio">',stripslashes($about),'</textarea></td>
        </tr>
        
        <tr>
        <td id="disc">Choose Your Discover Preferences:';
        if($_GET['error'] == 'disc') {echo'<div style="color:red;font-weight:700;"><br /><br /><br /><br />Please choose more photos to discover</div>';}
        echo'</td>
        <td><span style="font-size:13px">(Selecting multiple values: Hold down command button if on mac, control button if on PC)</span>
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
        </td>
        </tr>
                
        </tbody>
        </table>
        <button class="btn btn-success" type="submit">Save Profile</button>
        </form>
        </div>';
        
}

    
    elseif($view == 'cart') {
    
        $option = htmlentities($_GET['option']);    
    
     echo'<br /><br /><br /><br /><div style="width:760px;text-align:center;font-size:14px;font-weight:200;"><div style="margin-left:20px;"><a class="green" style="text-decoration:none;color:#333;'; if($option == '') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="myprofile.php?view=cart">My Cart</a> | <a class="green" style="text-decoration:none;color:#333;'; if($option == 'maybe') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="myprofile.php?view=cart&option=maybe">Maybe Later</a> | <a class="green" style="text-decoration:none;color:#333;'; if($option == 'purchases') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="myprofile.php?view=cart&option=purchases">Purchases</a></div></div>';
    
      if($option == 'maybe') {  
    
        if($_GET['action'] == 'remove') {
        
            $removedphoto = mysql_real_escape_string($_GET['pd']);
            $removephoto = mysql_query("DELETE FROM usersmaybe WHERE id = '$removedphoto' AND emailaddress = '$email'");
         
        }
        
        echo'<div class="grid_18" style="margin:auto;margin-top:30px;margin-left:20px;width:800px;">';

        $marketquery = mysql_query("SELECT * FROM usersmaybe WHERE emailaddress = '$email'");
                $numsavedinmarket = mysql_num_rows($marketquery);
          
                for($iii=0; $iii<$numsavedinmarket; $iii++) {
                        $photo[$iii] = mysql_result($marketquery, $iii, "source");
                        $photo2[$iii] = str_replace("https://photorankr.com/userphotos/","../userphotos/medthumbs/", $photo[$iii]);
                        $photoid[$iii] = mysql_result($marketquery, $iii, "id");
                        $imageid[$iii] = mysql_result($marketquery, $iii, "imageid");
                        $caption = mysql_result($marketquery, $iii, "caption");
                        $caption = strlen($caption) > 30 ? substr($caption,0,27). " &#8230;" : $caption;
                        $price = mysql_result($marketquery, $iii, "price");

                        list($height,$width) = getimagesize($photo2[$iii]);
                        $widthnew = $width / 2.8;
                        $heightnew = $height / 2.8;
                
                echo'
                  <div class="fPic" id="',$id,'" style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;">
                
                <div class="statoverlay" style="z-index:1;left:0px;top:180px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">',$caption,'</span><br><span style="font-size:20px;font-family:helvetica,arial;font-weight:100;">$',$price,'</span></p><a name="removed" href="myprofile.php?view=store&option=maybe&pd=',$photoid[$iii],'&action=remove#return"><button class="btn btn-primary" style="z-index:12;position:relative;top:-52px;float:right;margin-right:5px;">Remove Photo</button></a></div>
                
                <a href="fullsizemarket.php?imageid=',$imageid[$iii],'">
                <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:265px;min-width:245px;" alt="',$caption,'" src="',$photo[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
   
                }
        
        echo'</div>';
        
}


    elseif($option == 'purchases') {  
        
        echo'<div class="grid_18" style="margin:auto;margin-top:30px;margin-left:20px;width:800px;padding-bottom:100px;">';

            $downloadquery = mysql_query("SELECT * FROM userdownloads WHERE emailaddress = '$email'");
            $numpurchased = mysql_num_rows($downloadquery);
          
                for($iii=0; $iii<$numpurchased; $iii++) {
                
                        $photo[$iii] = mysql_result($downloadquery, $iii, "source");
                        $photo2[$iii] = str_replace("https://photorankr.com/userphotos/","userphotos/medthumbs/", $photo[$iii]);
                        $photoid[$iii] = mysql_result($downloadquery, $iii, "id");
                        $imageid[$iii] = mysql_result($downloadquery, $iii, "imageid");
                        $captionquery =  mysql_query("SELECT caption FROM photos WHERE id = '$imageid[$iii]'");
                        $caption = mysql_result($captionquery, 0, "caption");
                        $caption = strlen($caption) > 20 ? substr($caption,0,17). " &#8230;" : $caption;

                        list($height,$width) = getimagesize($photo2[$iii]);
                        $widthnew = $width / 2.8;
                        $heightnew = $height / 2.8;
                
                echo'
                  <div class="fPic" id="',$id,'" style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;">
                            
                  <a href="fullsizemarket.php?imageid=',$imageid[$iii],'">
                  <img onmousedown="return false" oncontextmenu="return false;" style="min-height:265px;min-width:245px;" alt="',$caption,'" src="',$photo2[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /></a>
                  
                  
                   <div class="statoverlay" style="z-index:1;left:0px;top:-60px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">',$caption,'</span><br></p>
                   <form action="downloadphoto.php" method="POST">
                   <input type="hidden" name="image" value="',$photo[$iii],'">
                   <button class="btn btn-primary" style="z-index:12;position:relative;top:-48px;float:right;margin-right:5px;">Download</button></a></div>
                   </form>
                  
                   </div>';
   
                }
        
        echo'</div>';
        
}
        
        
        elseif($option == '') {  
        
$size = mysql_real_escape_string($_POST['size']);

if(!$size) {
    $size = 'Large';
} 

$width = mysql_real_escape_string($_POST['width']);

if(!$width) {
    $width = mysql_real_escape_string($_POST['originalwidth']);
}

$height = mysql_real_escape_string($_POST['height']);

if(!$height) {
    $height = mysql_real_escape_string($_POST['originalheight']);
}

$price = mysql_real_escape_string($_POST['price']);

if(!$price) {
    $price = mysql_real_escape_string($_POST['originalprice']);
}

$imageid = mysql_real_escape_string($_POST['imageid']);

$multiseat = mysql_real_escape_string($_POST['multiseat']);
$unlimited = mysql_real_escape_string($_POST['unlimited']);
$resale = mysql_real_escape_string($_POST['resale']);
$electronic = mysql_real_escape_string($_POST['electronic']);

if($multiseat == 'checked') {
    $licenses = ' Multi-Seat,';
    $price += 20;
}
if($unlimited == 'checked') {
    $licenses = $licenses . ' Unlimited Reproduction / Print Runs,';
    $price += 35;
}
if($resale  == 'checked') {
    $licenses = $licenses . ' Items for Resale,';
    $price += 35;
}
if($electronic == 'checked') {
    $licenses = $licenses . ' Electronic Use,';
    $price += 35;
}

if(!$licenses) {
    $licenses = 'Standard Use';
}
            
        
            echo'<div id="container" class="grid_18" style="width:770px;margin-top:20px;padding-left:20px;">';
            
            
            if(htmlentities($_GET['action']) == 'download') {
               
               $images = $_POST['downloadedimages'];
               $imagesid = $_POST['imagesid'];


               $numberimages = count($images);
    		
                for($i=0; $i < $numberimages; $i++) {

                    $images[$i] = mysql_real_escape_string($images[$i]);
                    $imagesid[$i] = mysql_real_escape_string($imagesid[$i]);
                    
                    $downloadcheck = mysql_query("SELECT * FROM userdownloads WHERE imageid = '$imagesid[$i]'");
                    $downloadcheckrows = mysql_num_rows($downloadcheck);
                    
                    if($downloadcheckrows < 1) {
                    
                        $stickintouserdownloads = mysql_query("INSERT INTO userdownloads (emailaddress,imageid,source) VALUES ('$email','$imagesid[$i]','$images[$i]')");
                        $deletephotofromcart = mysql_query("DELETE FROM userscart WHERE emailaddress = '$email' AND imageid = '$imagesid[$i]'");
                    
                        //Tell them download was successful
                        echo'<div style="font-size:16px;font-weight:200;margin-top:20px;margin-left:35px;"><img src="',$images[$i],'" height="40" width="40" />&nbsp;&nbsp;&nbsp;Photo Saved in Purchases </div>';
                    
                    }
                 
                }
                 
            }
         
         
    //PHOTO CART INFORMATION
    $imagequery = mysql_query("SELECT source,price FROM photos WHERE id = '$imageid'");
    $imagenewsource = mysql_result($imagequery,0,'source');
    $imagenewsource2 = str_replace("userphotos/", "$_SERVER[DOCUMENT_ROOT]/userphotos/",$imagenewsource);
    $imagenewsource3 = str_replace("$_SERVER[DOCUMENT_ROOT]/userphotos/", "https://photorankr.com/userphotos/",$imagenewsource2); 
    $imagenewprice = mysql_result($imagequery,0,'price'); 
    
    //ADD TO CART IN DB
    
        if($_SESSION['loggedin'] != 1) {
        echo'
        <div style="margin-top:70px;margin-left:260px;padding-bottom:150px;">
        <div style="text-align:center;font-size:18px;">Login Below or <a href="signup3.php">Register to Buy:</a></div><br />
        <form name="login_form" method="post" action="fullsizemarket.php?imageid=',$imageid,'&action=login">
        <div class="well" style="width:380px;padding-top:50px;padding-bottom:50px;padding-left:40px;">
        <span style="font-size:18px;font-family:helvetica, arial;margin-left:0px;">Email: </span><input type="text" style="width:200px;margin-left:40px;" name="emailaddress" /><br />
        <span style="font-size:18px;font-family:helvetica, arial;">Password: </span>&nbsp<input type="password" style="width:200px;" name="password"/><br >
        <input type="submit" class="btn btn-success" style="margin-left:250px;" value="sign in" id="loginButton"/>
        </div>
        </form>
        </div>';
        
        }
    
        elseif($_SESSION['loggedin'] == 1) {
       
        if($imageid) {
        $cartcheck = mysql_query("SELECT * FROM userscart WHERE imageid = '$imageid' && emailaddress = '$email'");
        $numincart = mysql_num_rows($cartcheck);
        if($numincart < 1) {
            $stickincart = mysql_query("INSERT INTO userscart (source,size,width,height,license,price,emailaddress,imageid) VALUES ('$imagenewsource3','$size','$width','$height','$licenses','$price','$email','$imageid')");
            }
        }
        
        $incart = mysql_query("SELECT * FROM userscart WHERE emailaddress = '$email' ORDER BY id ASC");
        $incartresults = mysql_num_rows($incart);
        
        for($iii=0; $iii < $incartresults; $iii++) {
            $imagesource[$iii] = mysql_result($incart,$iii,'source');
            $imageprice[$iii] = mysql_result($incart,$iii,'price');
            $imagecartid = mysql_result($incart,$iii,'imageid');
            $imagelicenses = mysql_result($incart,$iii,'license');
            $standard = strpos($imagelicenses,'Standard');
            if($standard === false) { 
                $imagelicenses = substr($imagelicenses, 0, -1); 
            }
            $imagesize = mysql_result($incart,$iii,'size');
            $emailquery = mysql_query("SELECT emailaddress FROM photos WHERE id = '$imagecartid'");
            $photogemail = mysql_result($emailquery,0,'emailaddress');
            $totalcharge = $totalcharge + $imageprice[$iii];
            $cartidlist = $cartidlist.",".$imagecartid;
            list($width, $height)=getimagesize($imagesource[$iii]);
            $width = $width/4;
            $height = $height/4;
            
            echo'
            <div class="span9">
            <a name="',$imagecartid,'" style="text-decoration:none;color:#333;" href="fullsizemarket.php?imageid=',$imagecartid,'">
            <table class="table">
            <thead>
            <tr>
            <th>Photo</th>
            <th>Size</th>
            <th>License(s)</th>
            <th>Price</th>  
            </tr>
            </thead>
            <tbody>
            
            <tr>
            <td><div style="min-width:400px;height:<?php echo $height; ?>px;width:<?php echo $width; ?>px;"><img onmousedown="return false" oncontextmenu="return false;" src="',$imagesource[$iii],'" height=',$height,' width=',$width,' /><br /><br />
           <!-- <div style="text-align:left;"><a style="color:#aaa;font-size:12px;" href="download2.php?imageid=',$imagecartid,'&action=removed">Remove from cart</a></div>--></div>
            </td>
            <td style="width:140px;">',$imagesize,'</td>
            <td style="width:140px;">',$imagelicenses,'</td>
            <td style="width:140px;">$',$imageprice[$iii],'</td>
            </tr>

            
            </tbody>
            </table>
            </a>
            </div>';

        }
        
        /* check if image already in db
        $found = strpos($cartidlist, $imageid);
        
        if($imageid && $found === false) {
        //New image displayed
        echo'
         <div class="span12">
            <a style="text-decoration:none;color:#333;" href="fullsizemarket.php?imageid=',$imageid,'">
            <table class="table">
            <thead>
            <tr>
            <th>Photo</th>
            <th>Size</th>
            <th>Image ID</th>
            <th>License</th>
            <th>Price</th>  
            </tr>
            </thead>
            <tbody>
            <tr>
            <td><div style="width:400px;"><img onmousedown="return false" oncontextmenu="return false;" style="height:25%;" src="',$imagenewsource3,'" /></div></td>
            <td>Medium</td>
            <td>',$imageid,'</td>
            <td>Royalty Free</td>
            <td>$',$imagenewprice,'</td>
            </tr>
            <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            </tr>
            </tbody>
            </table>
            </a>
            </div>
            
            <div><a class="btn btn-success" href="',$_SERVER['HTTP_REFERER'],'">Continue Shopping</a>
            </div>';
        } */
        
        
    if($incartresults > 0) {
        
        echo'<div class="grid_18"><a name="added" style="color:black;text-decoration:none;" href="#"><div style="padding:15px;padding-right:200px;background-color:#ddd;width:100px;margin-left:-0px;margin-top:20px;"><span style="font-size:22px;font-weight:200;">Payment</span></div></a>
        
        <table class="table">
            <thead>
            <tr>
            <th># Photos</th>
            <th>Total Price</th>
            </thead>
            
            <tbody>
        
            <tr>
            <td style="width:760px;">',$incartresults,'</td>
            <td>$',$totalcharge,'</td>
            </tr>
        
            </tbody>
            </table>
        
        
        </div><br />';
        
        //STRIPE PAYMENT FORM AND DOWNLOAD SYSTEM
        
        if($totalcharge > 0) {
        
        echo'
        <div class="grid_20" style="margin-top:35px;">
         <label class="creditcards" style="float:left;font-size:16px;">We accept:&nbsp;&nbsp;<img src="card.jpg" style="width:215px;height:25px;margin-top:0px;border-radius:2px;"/> </label> <br /><br /><br />
         <label style="float:left;font-size:16px;" class="creditcards">Card Number:&nbsp;&nbsp;</label>
         <input style="float:left;font-size:15px;padding:6px;position:relative;top:-7px;width:170px;" type="text" size="20" autocomplete="off" class="card-number" style;"/>
            
                <label style="float:left;padding-left:10px;font-size:16px;" class="creditcards">CVC <span style="font-size:15px;">(Verification #):</span>&nbsp;&nbsp;</label>
                <input style="float:left;font-size:16px;padding:6px;position:relative;top:-7px;width:40px;" type="text" size="4" autocomplete="off" class="card-cvc"/>
                
                <label style="float:left;padding-left:10px;font-size:16px;" class="creditcards" >Expiration: <span style="font-size:15px;"></span>&nbsp;&nbsp;</label>
                <input type="text" style="float:left;width:50px;padding:6px;position:relative;top:-7px;width:30px;font-size:16px;" class="card-expiry-month"/>
                <span style="float:left;font-size:30px;font-weight:100;margin-top:-10px;">&nbsp;/&nbsp;</span>
                <input style="float:left;padding:6px;position:relative;top:-7px;width:60px;font-size:16px;" type="text" class="card-expiry-year"/><br /><br /><br />
               
   <button type="submit" class="button submit btn btn-success" style="font-size:16px;float:left;margin-top:5px;padding-top:10px;padding-bottom:10px;padding-right:40px;padding-left:40px;font-weight:200;">Submit Payment</button>
   <br /><br /><br /><div></div>
        </div>'; 
       
         }
         
         else {
         
         echo'
            <form name="download_form" method="post" action="myprofile.php?view=store&option=cart&action=download">';
          
            foreach($sourcelist as $value) {
                echo '<input type="hidden" name="downloadedimages[]" value="'. $value. '">';
            }
            
            foreach($idlist as $value) {
                echo '<input type="hidden" name="imagesid[]" value="'. $value. '">';
            }
            
            echo'
            <button type="submit" name="submit" value="download" class="button submit btn btn-success"  style="font-size:16px;font-weight:200;width:295px;height:40px;">Download Free</button>
            </form>';
         
         }
        
        }
        
        
        
        
 } //end if logged in

echo'</div>';

        
        }
    
    } //end of cart view
    

    elseif($view == 'store') {
    
        $option = htmlentities($_GET['option']);    
    
        echo'<br /><br /><br /><br /><div style="width:760px;text-align:center;font-size:14px;font-weight:200;"><div style="margin-left:20px;"><a class="green" style="text-decoration:none;'; if($option == '') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="myprofile.php?view=store">Manage Store</a> | <a class="green" style="text-decoration:none;color:#333;'; if($option == 'addtostore') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="myprofile.php?view=store&option=addtostore">Add to Store</a></div></div>';
                
        if($option == '') {        
        
            if(htmlentities($_GET['updateimage'])) {
            
                $imageid = mysql_real_escape_string($_GET['updateimage']);
                $newprice = mysql_real_escape_string($_POST['price']);
                $newlicenses = $_POST['license'];
                $imagecaption = mysql_real_escape_string($_POST['caption']);
                $imagethumb = mysql_real_escape_string($_POST['thumb']);
                
                $numberlicenses = count($newlicenses);
                for($i=0; $i < $numberlicenses; $i++)
                    {
                        $newlicenses2 = $newlicenses2 . mysql_real_escape_string($newlicenses[$i]) . " ";
                    }
                                
                $updateimageinfo = "UPDATE photos "; 
                
                if($newlicenses2 && !$newprice) {
                    $updateimageinfo .= "SET license = '$newlicenses2'";
                }
                
                elseif($newlicenses2 && $newprice) {
                    $updateimageinfo .= "SET license = '$newlicenses2',";
                }
                
                if($newprice && $newlicenses2) {
                    $updateimageinfo .= "price = '$newprice'";
                }
                
                elseif($newprice && !$newlicenses2) {
                    $updateimageinfo .= "SET price = '$newprice'";
                }
                
                $updateimageinfo .= " WHERE id = '$imageid'";
                
                $updateimageinforun = mysql_query($updateimageinfo);   
                        
                echo'<div style="font-size:16px;font-weight:200;margin-top:20px;margin-left:35px;"><img src="',$imagethumb,'" height="40" width="40" />&nbsp;&nbsp;"',$imagecaption,'" Market Information Updated</div>';
                
            }
            
        $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$email' AND price != ('Not For Sale') AND price != (.00) ORDER BY id DESC LIMIT 0,30");
        $numresults = mysql_num_rows($query);
        
        echo'<div id="thepics">';
        echo'<div id="container" class="grid_18" style="width:770px;margin-top:-38px;margin-left:-10px;padding:35px;">';

        for($iii=0; $iii < $numresults; $iii++) {
              
                $image[$iii] = mysql_result($query, $iii, "source");
                $imageThumb[$iii] = str_replace("userphotos/","../userphotos/medthumbs/", $image[$iii]);
                $id = mysql_result($query, $iii, "id");
                $caption = mysql_result($query, $iii, "caption");
                $points = mysql_result($query, $iii, "points");
                $votes = mysql_result($query, $iii, "votes");
                $faves = mysql_result($query, $iii, "faves");
                $price = mysql_result($query, $iii, "price");
                $sold = mysql_result($query, $iii, "sold");
                $score = number_format(($points/$votes),2);
                $faveemail = mysql_result($query, $iii, "emailaddress");
                $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$faveemail'");
                $firstname = mysql_result($query, 0, "firstname");
                $lastname = mysql_result($query, 0, "lastname");
                $reputation = mysql_result($query, 0, "lastname");
                $fullname = $firstname . " " . $lastname;
                list($width, $height) = getimagesize($image[$iii]);
                $imgratio = $height / $width;
                $heightls = $height / 3.5;
                $widthls = $width / 3.5;
                
                $licensecheck = mysql_query("SELECT license FROM photos WHERE id = '$id'");
                $licenseschecked = mysql_result($licensecheck,0,'license');
                
                echo '   

                <div class="fPic" id="',$id,'" style="width:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a style="text-decoration:none;"  href="https://photorankr.com/fullsizemarket.php?imageid=',$id,'">
                
                <div style="width:245px;height:230px;overflow:hidden;">
                <div class="statoverlay" style="z-index:1;left:0px;top:155px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">',$caption,'</span><br><span style="font-size:14px;font-family:helvetica,arial;font-weight:100;">Sold: ',$sold,'<br>Base Price: $',$price,'</span></p></div>

                <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:245px;min-width:245px;" src="https://www.photorankr.com/',$imageThumb[$iii],'" alt="',$caption,'" height="',$heightls,'px" width="',$widthls,'px" /></a>
                <br />      
                </div>    
                    
                    <!--DROPDOWN MANAGE-->
                    <div class="panel',$id,'">
                    
                    
            <script type="text/javascript">
            function showOtherPrice() {
                if (document.getElementById("price3").value == "Other Price")
                    {
                        document.getElementById("otherprice3").className = "show";
                    }
                else if (document.getElementById(\'price',$id,'\').value == \'Not For Sale\')
                    {
                        document.getElementById(\'remove',$id,'\').className = \'show\';
                    }
                else {
                    document.getElementById(\'otherprice',$id,'\').className = \'hide\';
                    }
            }
            </script>
            
        <!--FOR SALE-->
        <table class="table">
        <tbody>
        
        <tr>
        <td>Base Price:</td>
        <td>
            <div>
            <form action="myprofile.php?view=store&updateimage=',$id,'" method="post">
            <select id="price" name="price" style="width:120px;float:left;margin-left:-70px;margin-top:-20px;" onchange="showOtherPrice()">
            <option value="">Price:</option>
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
            </div>
            <div id="otherprice" class="hide" style="margin-left:-150px;width:290px;"><br /><div class="input-prepend input-append" style="float:left;"> 
                <span class="add-on">$</span><input class="span2" id="appendedPrependedInput" size="16" type="text"><span class="add-on">.00</span>
              </div></div>
        </td>
        </tr>
        
        <tr>
        <td colspan="2"><br /><b>Edit Options for Sale:</b></td>
        </tr>';
        
                
        $mystring = $licenseschecked;
        $findme   = 'multiseat';
        $foundlicense = strpos($mystring,$findme);

        if($foundlicense !== false) {
        echo'
            <tr>
            <td><div style="width:150px;">
            <input type="checkbox" name="license[]" value="multiseat" checked />&nbsp;&nbsp;Multi-Seat</div>
            </td>
            <td>+ $20</td>
            </tr>';
        }
        else {
        echo'
            <tr>
            <td><div style="width:150px;">
            <input type="checkbox" name="license[]" value="multiseat" />&nbsp;&nbsp;Multi-Seat</div>
            </td>
            <td>+ $20</td>
            </tr>';
        }    
        
        $mystring = $licenseschecked;
        $findme   = 'printruns';
        $foundlicense = strpos($mystring,$findme);

        if($foundlicense !== false) {
        echo'
            <tr>
            <td><div style="width:150px;">
            <input type="checkbox" name="license[]" value="printruns" checked />&nbsp;&nbsp;Unlimited Reproduction</div>
            </td>
            <td>+ $35</td>
            </tr>';
        }
        else {
        echo'
            <tr>
            <td><div style="width:150px;">
            <input type="checkbox" name="license[]" value="printruns" />&nbsp;&nbsp;Unlimited Reproduction</div>
            </td>
            <td>+ $35</td>
            </tr>';
        }    
        
        $mystring = $licenseschecked;
        $findme   = 'resale';
        $foundlicense = strpos($mystring,$findme);

        if($foundlicense !== false) {
        echo'
            <tr>
            <td><div style="width:150px;">
            <input type="checkbox" name="license[]" value="resale" checked />&nbsp;&nbsp;Allow Resale</div>
            </td>
            <td>+ $35</td>
            </tr>';
        }
        else {
        echo'
            <tr>
            <td><div style="width:150px;">
            <input type="checkbox" name="license[]" value="resale" />&nbsp;&nbsp;Allow Resale</div>
            </td>
            <td>+ $35</td>
            </tr>';
        }   
        
        $mystring = $licenseschecked;
        $findme   = 'electronic';
        $foundlicense = strpos($mystring,$findme);

        if($foundlicense !== false) {
        echo'
            <tr>
            <td><div style="width:150px;">
            <input type="checkbox" name="license[]" value="electronic" checked />&nbsp;&nbsp;Allow Electronic Use</div>
            </td>
            <td>+ $35</td>
            </tr>';
        }
        else {
        echo'
            <tr>
            <td><div style="width:150px;">
            <input type="checkbox" name="license[]" value="electronic" />&nbsp;&nbsp;Allow Electronic Use</div>
            </td>
            <td>+ $35</td>
            </tr>';
        }   
        
        echo'
        <input type="hidden" name="caption" value="',$caption,'" />
        <input type="hidden" name="thumb" value="',$imageThumb[$iii],'" /> 
        
        </tbody>
        </table>
        
        <div style="text-align:center;">
        <button class="btn btn-success" type="submit" style="width:210px;padding:7px;margin-bottom:10px;" href="#">Update Market Info</button>
        </form>
        </div>
        
                    </div>
                    
                    <a name="',$id,'" href="#"><p class="flip',$id,'" style="font-size:15px;font-weight:200;"></a>Manage</p>
                    
                    
                    <style type="text/css">
                    p.flip',$id,' {
                    padding:10px;
                    width:223px;
                    clear:both;
                    text-align:center;
                    background:white;
                    border:solid 1px #c3c3c3;
                    }

                    p.flip',$id,':hover {
                    background-color: #ccc;
                    }

                    div.panel',$id,' {
                    display:none;
                    clear:both;
                    padding:300px;
                    padding:5px;
                    text-align:left;
                    background:white;
                    border:solid 1px #c3c3c3;
                    }
                    </style>
                    
                    <!--HIDDEN COMMENT SCRIPT-->
                    <script type="text/javascript">   
                    $(document).ready(function(){
                    $(".flip',$id,'").click(function(){
                        $(".panel',$id,'").slideToggle("slow");
                    });
                    });
                    </script>
                    
                </div>';


	    
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
				$("div#loadMoreStorePics").show();
				$.ajax({
					url: "loadMoreStorePics.php?lastPicture=" + $(".fPic:last").attr("id"),
					success: function(html) {
						if(html) {
							$("#thepics").append(html);
							$("div#loadMoreStorePics").hide();
						}
					}
				});
				last = $(".fPic:last").attr("id");
			}
		}
	});
</script>';
        
    }
        
        elseif($option == 'addtostore') {  
        
        echo'
            <div class="grid_18" style="margin:auto;border:1px solid #ccc;margin-top:30px;margin-left:20px;">';
            
            if($_GET['action'] == 'submittomarket') {
    
                $source = mysql_real_escape_string($_POST['checked']);
                $newprice = mysql_real_escape_string($_POST['newprice']);
            
                echo'<div style="margin:auto;border:1px solid #ccc;height:150px;">
                <img style="float:left;padding:15px;" src="',$source,'" height="120" width="120" />
                <div style="float:left;padding:15px;margin-top:20px;font-size:14px;font-weight:200;">
                <span style="font-size:16px;font-weight:400;color:green;">Now in Market</span><br />
                New Price: $',$newprice,'<br/>
                New License: Extended License</div>
                </div><br />';
            }
            
            echo'
            
            <script type="text/javascript">
            function showSelect() {
                var select = document.getElementById(\'extended\');
                select.className = \'show\';
                document.getElementById(\'cc\').className = \'hide\';
            }
            function showSelectHide() {
                var select = document.getElementById(\'extended\');
                select.className = \'hide\';
            }
            function showOtherPrice() {
                if (document.getElementById(\'price\').value == \'Other Price\')
                    {
                        document.getElementById(\'otherprice\').className = \'show\';
                    }
                else {
                    document.getElementById(\'otherprice\').className = \'hide\';
                    }
            }
            </script>
            
            
        <div>
        <div style="padding:10px;"><a style="width:150px;padding:8px;float:left;" class="btn btn-success" data-toggle="modal" data-backdrop="static" href="#marketphoto">Add Photo To Market</a></div>
                <form action="myprofile.php?view=store&option=addtostore&action=submittomarket" method="POST">
            <div style="float:left;padding:10px;">
            <select id="price" name="newprice" style="width:150px;margin-top:-15px;margin-left:10px;" onchange="showOtherPrice()">
            <option value="">Choose Price&#8230;</option>
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
            <option value="Other Price">Custom Price</option>
            </select>
            </div>
            <div id="otherprice" class="hide" style="float:left;margin-top:-5px;margin-left:10px;"><div class="input-prepend input-append">
                <span class="add-on">$</span><input class="span2" id="appendedPrependedInput" size="16" type="text"><span class="add-on">.00</span>
              </div></div>
            
         </div>
         <hr>   
 
        <div style="text-align:center;">
        <input type="radio" name="market" value="standard" onclick="showSelectHide();" />&nbsp;&nbsp;Standard License&nbsp;&nbsp;<input style="margin-left:40px;" type="radio" name="market" value="extended" onclick="showSelect();" />&nbsp;&nbsp;Extended License&nbsp;&nbsp;
        </div>
        
        <div id="extended" class="hide">
        
        <br />
        <b style="padding:10px;">Additonal Options for Sale:</b>
        
        <table class="table">
        <tbody>
        
        <tr>
        <td><input type="checkbox" name="multiseat" value="multiseat" />&nbsp;&nbsp;Multi-Seat (Unlimited)</td>
        <td colspan="2">+ $20</td>
        </tr>
        
        <tr>
        <td><input type="checkbox" name="printruns" value="printruns" />&nbsp;&nbsp;Unlimited Reproduction / Print Runs</td>
        <td colspan="2">+ $35</td>
        </tr>
        
        <tr>
        <td><input type="checkbox" name="resale" value="resale" />&nbsp;&nbsp;Items for Resale - Limited Run</td>
        <td colspan="2">+ $35</td>
        </tr>
        
        <tr>
        <td><input type="checkbox" name="electronic" value="electronic" />&nbsp;&nbsp;Unlimited Electronic Use</td>
        <td colspan="2">+ $35</td>
        </tr>
        </div>
        
        </tbody>
        </table>
        </div>
        
            <!--ADD PHOTO TO BLOG POST MODAL-->

            <div class="modal hide fade" id="marketphoto" style="overflow-y:scroll;overflow-x:hidden;border:5px solid rgba(102,102,102,.8);">

            <div class="modal-header" style="background-color:#111;color:#fff;">
            <a style="float:right" class="btn btn-success" data-dismiss="modal">Close</a>
            <img style="margin-top:-2px;" src="graphics/aperture_white.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">Choose a photo to add to your blog post:</span>
            </div>
            <div modal-body" style="width:600px;">

            <div id="content" style="font-size:16px;width:550px;height:500px;overflow-x:hidden;background-color:rgb(245,245,245);">';

            if($email != '') {
            echo'
            <img style="border: 1px solid black;margin-left:10px;margin-top:10px;" src="',$profilepic,'" 
            height="100px" width="100px" />

            <div style="width:540px;margin-left:130px;margin-top:-125px;overflow-y:scroll;overflow-x:hidden;">

            <span style="font-size:14px;">
            <br />';
            $allusersphotos = "SELECT * FROM photos WHERE emailaddress = '$email' AND (price = '0.00' OR price = 'Not For Sale') ORDER BY id DESC";
            $allusersphotosquery = mysql_query($allusersphotos);
            $usernumphotos = mysql_num_rows($allusersphotosquery);
    
            for($iii = 0; $iii < $usernumphotos; $iii++) {
            $userphotosource = mysql_result($allusersphotosquery, $iii, "source");
            $userphotosource = str_replace("userphotos/","https://photorankr.com/userphotos/", $userphotosource);
            $userphotosset[$iii] = mysql_result($allusersphotosquery, $iii, "sets");
            $userphotoscaption[$iii] = mysql_result($allusersphotosquery, $iii, "caption");
            $newsource = str_replace("userphotos/","userphotos/thumbs/", $userphotosource);
        
            echo'<img src="',$newsource,'" height="70" width="70" />&nbsp;&nbsp;<input id="blogphoto" type="radio" name="checked" value="',$userphotosource,'" onclick="showBlogPhoto();" />&nbsp;"',$userphotoscaption[$iii],'"
            <br /><br />'; 
    
        } //end of for loop
    
    
        echo'
        </span>
        <button class="btn btn-success" data-dismiss="modal">Submit Photo</button>
        <br />
        <br />';
        }
        
        else {
        echo'<div style="text-align:center;margin-top:100px;"><b>Please login or register to upload</b></div>';
        }
    
        echo'
        </div>
        </div>
        </div></div>
        
        <div style="padding:10px;float:right;"><button style="width:150px;padding:8px;" class="btn btn-primary" type="submit">Upload Now</button></div>
        </form>    
        </div>';
            
        }
    
    }
    
    

    elseif($view == 'network') {
    
        $option = htmlentities($_GET['option']);    
    
        echo'<br /><br /><br /><br /><div style="width:760px;text-align:center;font-size:14px;font-weight:200;"><div style="margin-left:20px;"><a class="green" style="text-decoration:none;color:#000;';if($option == '') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="myprofile.php?view=network">Following</a> | <a class="green" style="text-decoration:none;color:#000;';if($option == 'followers') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="myprofile.php?view=network&option=followers">Followers</a></div></div>';
        
        if($option == '') {
            $query = mysql_query("SELECT following FROM userinfo WHERE emailaddress = '$email'");
            $followinglist = mysql_result($query, 0, "following");
            $followingquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress IN ($followinglist)");
            $numberfollowing = mysql_num_rows($followingquery);
        }
        
        elseif($option == 'followers') {
        $followersquery="SELECT * FROM userinfo WHERE following LIKE '%$email%'";
        $followingquery=mysql_query($followersquery);
        $numberfollowing = mysql_num_rows($followingquery);
        }
        
        echo'<div style="margin-left:20px;">';
        for($iii = 0; $iii < $numberfollowing; $iii++) {
		$followingpic = mysql_result($followingquery, $iii, "profilepic");
		$followingfirst = mysql_result($followingquery, $iii, "firstname");
		$followinglast = mysql_result($followingquery, $iii, "lastname");
        $fullname = $followingfirst . " " . $followinglast;
        $fullname = ucwords($fullname);
        $followingid = mysql_result($followingquery, $iii, "user_id");
		
                echo '   

                <div style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a style="text-decoration:none;" href="https://photorankr.com/viewprofile.php?u=',$followingid,'">

                <div class="statoverlay" style="z-index:1;left:0px;top:210px;position:relative;background-color:black;width:245px;height:35px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:18px;font-family:helvetica,arial;font-weight:100;">',$fullname,'</span></p></div>

                <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-35px;min-height:245px;min-width:245px;" src="https://www.photorankr.com/',$followingpic,'" height="245" width="245" /></a></div>';
        
        }
        echo'</div>';
    }
    
    
    elseif($view == 'favorites') {
    
        $option = htmlentities($_GET['option']);    
    
        echo'<br /><br /><br /><br /><div style="width:760px;text-align:center;font-size:14px;font-weight:200;"><div style="margin-left:20px;"><a class="green" style="text-decoration:none;'; if($option == '') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="myprofile.php?view=favorites">Photos</a> | <a class="green" style="text-decoration:none;color:#333;'; if($option == 'exts') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="myprofile.php?view=favorites&option=exts">Exhibits</a></div></div>';
    
    if($option == '') {

        $favesquery = "SELECT faves FROM userinfo WHERE emailaddress='$email' LIMIT 0, 1";
        $favesresult = mysql_query($favesquery) or die(mysql_error());
        $faves = mysql_result($favesresult, 0, "faves");
        
        $query = mysql_query("SELECT * FROM photos WHERE source IN ($faves) ORDER BY FIELD (source, $faves) DESC LIMIT 9");
        $numresults = mysql_num_rows($query);
        
        echo'
        <div id="thepics" style="position:relative;width:780px;margin-left:15px;top:0px;">
        <div id="main" role="main">
        <ul id="tiles">';

        for($iii=0; $iii < $numresults; $iii++) {
              
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

                  echo'<a style="text-decoration:none;color:#000;" href="fullsize.php?imageid=',$id,'"><li class="fPic photobox" id="',$id,'" style="padding:5px;margin-right:10px;margin-top:10px;list-style-type: none;width:240px;
"><img onmousedown="return false" oncontextmenu="return false;" src="https://photorankr.com/',$imageThumb[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /><div style="padding:3px;"><div style="float:left;">',$caption,'</div><div style=float:right;font-size:13px;font-weight:500;">',$price,'</div><br /><i class="icon-heart"></i>&nbsp;',$faves,' favorites</div></li></a>';
	    
                } //end for loop      
        
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
					url: "loadMoreFavePics3.php?lastPicture=" + $(".fPic:last").attr("id"),
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

} //end option == ''


    elseif($option == 'exts') {
            
        $favesquery = "SELECT exhibitfaves FROM userinfo WHERE emailaddress='$email' LIMIT 0, 1";
        $favesresult = mysql_query($favesquery) or die(mysql_error());
        $faves = mysql_result($favesresult, 0, 'exhibitfaves');
        $faves = substr($faves, 0, -1);
                        
        $allsetsrun = mysql_query("SELECT * FROM sets WHERE id IN ($faves) ORDER BY FIELD (id, $faves) DESC");
        $numresults = mysql_num_rows($allsetsrun);

        echo'
        
            <div id="thepics" style="position:relative;width:780px;margin-left:15px;top:30px;">
            <div id="main" role="main">
            <ul id="tiles">';

        for($iii=0; $iii < $numresults; $iii++) {
              
            $setname[$iii] = mysql_result($allsetsrun, $iii, "title");
            $setcover = mysql_result($allsetsrun, $iii, "cover");
            $setemail = mysql_result($allsetsrun, $iii, "owner");
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
            $grabphotos = "SELECT * FROM photos WHERE emailaddress = '$setemail' AND set_id = '$set_id[$iii]'";
            $grabphotosrun = mysql_query($grabphotos);
            $numphotosgrabbed = mysql_num_rows($grabphotosrun);
            
            $findsetowner = mysql_query("SELECT user_id FROM userinfo WHERE emailaddress = '$setemail'");
            $setownerid = mysql_result($findsetowner,0,'user_id');


    echo'<li style="width:240px;list-style-type:none;"><a style="text-decoration:none;" href="viewprofile.php?u=',$setownerid,'&view=exhibits&set=',$set_id[$iii],'">
    
    <div style="width:100%;">
    
    <div style="padding-top:5px;padding-left:3px;font-size:13px;text-decoration:none;color:#000;font-weight:200;"><span style="font-size:15px;font-weight:400;">',$setname2[$iii],'</span><br />',$numphotosgrabbed,' Photos</div>
<hr />

    <img style="margin-top:-6px;" onmousedown="return false" oncontextmenu="return false;" src="https://www.photorankr.com/',$setcover,'" alt="',$setname[$iii],'" height="',$heightls,'px" width="',$widthls,'px" />';
    
    if($thumb4) {
        echo'
            <div>
            <img style="float:left;padding:5px;" src="https://www.photorankr.com/',$thumb1,'" width="110" height="110" />
            <img style="float:left;padding:5px;" src="https://www.photorankr.com/',$thumb2,'" width="110" height="110" />
            <img style="float:left;padding:5px;" src="https://www.photorankr.com/',$thumb3,'" width="110" height="110" />
            <img style="float:left;padding:5px;" src="https://www.photorankr.com/',$thumb4,'" width="110" height="110" />
            </div>';
    }
    
    echo'
    </a>
    
    </li><br />';
    
} //end of for loop

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
  
  echo'
  </div>
  </div>';

    }

} //end of faves view
    
    
    elseif($view == 'search') {
        
        $searchterm = htmlentities(mysql_real_escape_string($_POST['searchterm']));
        $query = mysql_query("SELECT * FROM photos WHERE concat(caption,location,tag1,tag2,tag3,tag4) LIKE '%$searchterm%' AND emailaddress = '$email' ORDER BY (views) DESC");
        $numresults = mysql_num_rows($query);
        echo'<div id="thepics">';
        echo'<div id="container" class="grid_18" style="width:770px;margin-top:0px;padding-left:20px;">';
        
        echo'<br /><div style="width:760px;text-align:center;font-size:17px;font-weight:200;"><div style="margin-left:20px;">';
        if($numresults > 0) {echo $numresults . ' Photos Found'; } else {echo'Sorry, No Photos Found For "',$searchterm,'"';}
        echo'
        </div></div>';

        for($iii=0; $iii < $numresults; $iii++) {
              
                $image[$iii] = mysql_result($query, $iii, "source");
                $imageThumb[$iii] = str_replace("userphotos/","../userphotos/medthumbs/", $image[$iii]);
                $id = mysql_result($query, $iii, "id");
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
                $heightls = $height / 3.5;
                $widthls = $width / 3.5;

                echo '   

                <div class="fPic" id="',$id,'" style="width:245px;height:230px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a style="text-decoration:none;" href="fullsizeme.php?image=', $image[$iii], '">

                <div class="statoverlay" style="z-index:1;left:0px;top:155px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">',$caption,'</span><br><span style="font-size:14px;font-family:helvetica,arial;font-weight:100;">Score: ',$score,'<br>Favorites: ',$faves,'</span></p></div>

                <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:245px;min-width:245px;" src="https://www.photorankr.com/',$imageThumb[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
	    
                } //end for loop      
        
        echo'</div>';
        echo'</div>';
    
    }
    
    
    
    elseif($view == 'upload') {
    
                $option = htmlentities($_GET['option']);    
                $set = htmlentities($_GET['cs']); 

                echo'<br /><br /><br /><br /><div style="width:760px;text-align:center;font-size:14px;font-weight:200;"><div style="margin-left:20px;"><a class="green" style="text-decoration:none;';if($option == '') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="myprofile.php?view=upload">Single Upload</a> | <a class="green" style="text-decoration:none;';if($option == 'batch') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="myprofile.php?view=upload&option=batch">Batch Upload</a> | <a class="green" style="text-decoration:none;';if($option == 'newexhibit') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="myprofile.php?view=upload&option=newexhibit">Create an Exhibit</a></div></div>';
                        
                if($option == '') {
                        
                        //select all sets associated with user email
                        $setsemail = $_SESSION['email'];
                        $setsquery = "SELECT * FROM sets WHERE owner = '$setsemail'";
                        $setsqueryrun = mysql_query($setsquery);
                        $setscount = mysql_num_rows($setsqueryrun);

                        //upload a photo
                        if (htmlentities($_GET['action']) == "uploadsuccess") { 
                        
                                $lastupload = mysql_query("SELECT id,source FROM photos WHERE emailaddress = '$email' ORDER BY id DESC LIMIT 1");
                                $lastphotoid = mysql_result($lastupload,0,'id');
                                $lastphotosource = mysql_result($lastupload,0,'source');
                                $lastphotocaption = mysql_result($lastupload,0,'caption');
                                $lastphotosource = str_replace("userphotos/","userphotos/medthumbs/",$lastphotosource);
                                
                                echo '<img style="float:left;padding:10px;margin-left:50px;" src="',$lastphotosource,'" width="100" /><div style="margin-top:20px;margin-left:10px;color:#6aae45;float:left;font-size:16px;font-weight:200;"><strong>Upload Successful</strong>
                                <br />
                                <div style="color:#333;margin-top:5px;">Share this photo?&nbsp;&nbsp;
                                  
                                    <div>
                                              
                                    <div style="float:left;margin-top:5px;">                                                                                                                                                                                                                   
                                    <a name="fb_share" type="button" share_url="https://photorankr.com/fullsizeview.php?imageid=',$lastphotoid,'" href="https://www.facebook.com/sharer.php">Share</a>
<script src="https://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script></div>
                                
                                    &nbsp;&nbsp;

                                    <div style="float:left;padding:2px;margin-left:6px;margin-top:5px;"><a href="https://twitter.com/share" class="twitter-share-button" data-url="https://photorankr.com/fullsizeview.php?imageid=',$lastphotoid,'" data-text="Check out my latest PhotoRankr upload!" data-via="PhotoRankr" data-count="none"></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                                    </div>

                                    </div>
                                
                                </div>
                                
                                </div><br />';
                                
                                                            
                        }
                        
                        if($set == 'n') {
                        
                            if (htmlentities($_GET['ns']) == "success") { 
                                echo'<br /><br /><span style="margin-top:20px;margin-left:60px;font-size:18px;color:#6aae45"><a href="myprofile.php?view=upload">Add photos to your new exhibit below</a></span><br />';
                            }
    
                            elseif (htmlentities($_GET['ns']) == "failure") { 
                                echo'<br /><br /><span style="margin-top:20px;margin-left:60px;font-size: 18px;color:red;">Please fill out all fields</span><br />';
                            }
    
                            elseif (htmlentities($_GET['ns']) == "name") { 
                                echo'<br /><br /><span style="margin-top:20px;margin-left:60px;font-size: 18px;color:red;">You already have an exhibit titled this</span><br />';
                            }
                        
                        }
    
                        else if (htmlentities($_GET['action']) == "uploadfailure") {
                                echo '<div style="margin-top:20px;margin-left:60px;color:red;float:left;font-size:20px;font-weight:200;">Please Fill Out All Required Information.</div><br />';
        
                        }
        
        echo'
        <div id="container" class="grid_18" style="width:770px;margin-top:50px;padding-left:20px;margin-left:10px;">
                           
        <div class="span9" style="margin-top:-58px;;margin-left:-35px;padding:20px;padding-left:67px;">
        <br />
        <div style="font-size:12px;font-family:Helvetica Neue,helvetica,arial;font-weight:200;"><span style="font-size:16px;">* </span>You retain all copyrights to your images. Please do not upload watermarked or copyrighted images if you wish to sell them.</div>
        <br />
        <form action="upload_photo3.php" method="post" enctype="multipart/form-data">
        <table class="table">
        <tbody>
    
        <tr>
        <td>Upload Photo:</td>
        <td><input type="file" name="file" /><input type="hidden" name="MAX_FILE_SIZE" value="2500000000" /></td>
        </tr>
        
        <tr>
        <td>Title:</td>
        <td><input style="width:180px;height:20px;" type="text" name="caption" /></td>
        </tr>
        
        <tr>
        <td>Camera:</td>
        <td><input style="width:180px;height:20px;" type="text" value="',$camera,'" name="camera" /></td>
        </tr>

        <tr>
        <td>Location:</td>
        <td><input style="width:180px;height:20px;" type="text" name="age" placeholder="City, State/Province, Country" /></td>
        </tr>

        <tr>
        <td>Keywords:</td>
        <td><input style="width:80px;height:20px;" type="text" name="tag1" />
            <input style="width:80px;height:20px;" type="text" name="tag2" />
            <input style="width:80px;height:20px;" type="text" name="tag3" />
            <input style="width:80px;height:20px;" type="text" name="tag4" /></td>
        </tr>
        
        <tr>
        <td>Style & Category:</td>
        <td>
            <span style="font-size:13px">(Selecting multiple values: Hold down command button if on mac, control button if on PC)</span>
            <br /><br />
            
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

            <span style="padding-left:70px;">
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
            
            </span>
        </td>
        </tr>
        
        <tr>
        <td>About Photo:</td>
        <td><textarea style="width:500px" rows="4" cols="60" name="about"></textarea></td>
        </tr>

        <tr>
        <td>Add to Exhibit:</td>
        <td>
            <select name="addtoset" onchange="showTags(this.value)" style="width:160px;">
                <option value="" style="display:none;">Choose an exhibit:</option>';
                for($iii=0; $iii < $setscount; $iii++) {
                $settitle = mysql_result($setsqueryrun, $iii, "title");
                echo'<option value="',$settitle,'">',$settitle,'</option>';
            }
            echo'
            </select>
    
            <br />
            <br />
    
            <div id="boxesappear"> </div>
        </td>
        </tr>
    
        
        <tr>
        <td>Marketplace:</td>
        <td>
        
        <input type="radio" name="market" value="forsale" onclick="showSelect();" />&nbsp;&nbsp;For Sale&nbsp;&nbsp;<input style="margin-left:40px;" type="radio" name="market" value="notforsale" onclick="showSelectHide();" />&nbsp;&nbsp;Not For License&nbsp;&nbsp;<input style="margin-left:40px;" type="radio" name="cc" value="cc" onclick="showSelect2();" />&nbsp;&nbsp;Creative Commons&nbsp;
        
        <a href="#" id="popovercc" rel="popover" data-content="Tags help us help you. By selecting various tags for your photos, we can make sure your photos are seen more often in search and on the discovery page. It helps ensure that your photos will always be seen." data-original-title="Creative Commmons?">
        (?)</a>&nbsp;</td>
        </tr>
                
        </tbody>
        </table>
        
        <script>  
            $(function ()  
            { $("#popovercc").popover();  
            });  
        </script>
        
        
        <!--SELECTABLE LICENSES DROPDOWN & OTHER PRICE-->
        
            <script type="text/javascript">
            function showSelect() {
                var select = document.getElementById(\'forsale\');
                select.className = \'show\';
                document.getElementById(\'cc\').className = \'hide\';
            }
            function showSelect2() {
                var select = document.getElementById(\'cc\');
                select.className = \'show\';
                document.getElementById(\'forsale\').className = \'hide\';
            }
            function showSelectHide() {
                var select = document.getElementById(\'forsale\');
                select.className = \'hide\';
                document.getElementById(\'cc\').className = \'hide\';
            }
            function showOtherPrice() {
                if (document.getElementById(\'price\').value == \'Not For Sale\')
                    {
                        document.getElementById(\'otherprice\').className = \'show\';
                    }
                else {
                    document.getElementById(\'otherprice\').className = \'hide\';
                    }
            }
            </script>
            
        <!--FOR SALE-->
        <div id="forsale" class="hide">
        <table class="table">
        <tbody>
        
        <tr>
        <td>Base Price:</td>
        <td>
            <select id="price" name="price" style="width:100px;float:left;" onchange="showOtherPrice()">
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
            <option>Other Price</option>
            </select>
            <div id="otherprice" class="hide" style="float:left;padding-left:20px;"><div class="input-prepend input-append">
                <span class="add-on">$</span><input class="span2" id="appendedPrependedInput" size="16" type="text"><span class="add-on">.00</span>
              </div></div>
        </td>
        </tr>
        
        <tr>
        <td colspan="2"><br /><b>Additonal Options for Sale:</b></td>
        </tr>
        
        <tr>
        <td><input type="checkbox" name="extendedlicenses[]" value="multiseat" />&nbsp;&nbsp;Multi-Seat (Unlimited)</td>
        <td colspan="2">+ $20</td>
        </tr>
        
        <tr>
        <td><input type="checkbox" name="extendedlicenses[]" value="printruns" />&nbsp;&nbsp;Unlimited Reproduction / Print Runs</td>
        <td colspan="2">+ $35</td>
        </tr>
        
        <tr>
        <td><input type="checkbox" name="extendedlicenses[]" value="resale" />&nbsp;&nbsp;Items for Resale - Limited Run</td>
        <td colspan="2">+ $35</td>
        </tr>
        
        <tr>
        <td><input type="checkbox" name="extendedlicenses[]" value="electronic" />&nbsp;&nbsp;Unlimited Electronic Use</td>
        <td colspan="2">+ $35</td>
        </tr>

        </tbody>
        </table>
        </div>
        
        <!--CREATIVE COMMONS-->
        <div id="cc" class="hide">
        <table class="table">
        <tbody>
        
        <tr>
        <td>Allow Modifications of Your Work?</td>
        <td colspan="2"><input type="radio" name="ccmods" value="yes" />&nbsp&nbsp;Yes&nbsp;&nbsp;<input style="margin-left:20px;"  type="radio" name="ccmods" value="no" />&nbsp&nbsp;No&nbsp;&nbsp;<input style="margin-left:20px;" type="radio" name="ccmods" value="sharealike" />&nbsp&nbsp;Share Alike&nbsp;&nbsp;</td>
        </tr>
        
        <tr>
        <td>Allow Commercial Uses of Your Work?</td>
        <td colspan="2"><input type="radio" name="cccom" value="yes" />&nbsp&nbsp;Yes&nbsp;&nbsp;<input style="margin-left:20px;" type="radio" name="cccom" value="no" />&nbsp&nbsp;No&nbsp;&nbsp;</td>
        </tr>
        
        </tbody>
        </table>
        </div>
    
    </tbody>
    </table>

	<br />
	<button type="submit" name="Submit" class="btn btn-success">Upload Now</button>
	</form>';

}
                        
            elseif($option == 'batch') {
                        
            ?>

<!-- Generic page styles -->
<link rel="stylesheet" href="css/style.css">
<!-- Bootstrap styles for responsive website layout, supporting different screen sizes -->
<link rel="stylesheet" href="https://blueimp.github.com/cdn/css/bootstrap-responsive.min.css">
<!-- Bootstrap CSS fixes for IE6 -->
<!--[if lt IE 7]><link rel="stylesheet" href="https://blueimp.github.com/cdn/css/bootstrap-ie6.min.css"><![endif]-->
<!-- Bootstrap Image Gallery styles -->
<link rel="stylesheet" href="https://blueimp.github.com/Bootstrap-Image-Gallery/css/bootstrap-image-gallery.min.css">
<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
<link rel="stylesheet" href="batch/css/jquery.fileupload-ui.css">
<!-- Shim to make HTML5 elements usable in older Internet Explorer versions -->
<!--[if lt IE 9]><script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

<div style="text-align:center;font-size:14px;font-family:helvetica;font-weight:100;margin-left:-35px;margin-top:15px;">Drap and Drop Supported</div>

    <form id="fileupload" action="batch/server/php/" method="POST" enctype="multipart/form-data">
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="row fileupload-buttonbar" style="margin-left:150px;margin-top:15px;">
            <div class="span7">
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="btn btn-success fileinput-button">
                    <i class="icon-plus icon-white"></i>
                    <span>Add files...</span>
                    <input type="file" name="files[]" multiple>
                </span>
                <button type="submit" class="btn btn-primary start">
                    <i class="icon-upload icon-white"></i>
                    <span>Start upload</span>
                </button>
                <button type="reset" class="btn btn-warning cancel">
                    <i class="icon-ban-circle icon-white"></i>
                    <span>Cancel upload</span>
                </button>
                <button type="button" class="btn btn-danger delete">
                    <i class="icon-trash icon-white"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" class="toggle">
            </div>
            <!-- The global progress information -->
            <div class="span5 fileupload-progress fade">
                <!-- The global progress bar -->
                <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:425px;">
                    <div class="bar" style="width:0%;"></div>
                </div>
                <!-- The extended global progress information -->
                <div class="progress-extended">&nbsp;</div>
            </div>
        </div>
        <!-- The loading indicator is shown during file processing -->
        <div class="fileupload-loading"></div>
        <br>
        <!-- The table listing the files available for upload/download -->
        <table role="presentation" class="table table-striped"><tbody class="files" style="width:800px;" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody></table>
    </form>
    </div>
    
<!-- modal-gallery is the modal dialog used for the image gallery -->
<div id="modal-gallery" class="modal modal-gallery hide fade" data-filter=":odd">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h3 class="modal-title"></h3>
    </div>
    <div class="modal-body"><div class="modal-image"></div></div>
    <div class="modal-footer">
        <a class="btn modal-download" target="_blank">
            <i class="icon-download"></i>
            <span>Download</span>
        </a>
        <a class="btn btn-success modal-play modal-slideshow" data-slideshow="5000">
            <i class="icon-play icon-white"></i>
            <span>Slideshow</span>
        </a>
        <a class="btn btn-info modal-prev">
            <i class="icon-arrow-left icon-white"></i>
            <span>Previous</span>
        </a>
        <a class="btn btn-primary modal-next">
            <span>Next</span>
            <i class="icon-arrow-right icon-white"></i>
        </a>
    </div>
</div>

<div style="width:800px;margin-top:-15px;"">
<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td class="preview"><span class="fade"></span></td>
        <td class="name"><span>{%= file.name %}</span></td>
        <div>
        <div>    
	<td colspan="1" class="desc">Name: <input type="text" style="width:120px;" name='{%= "names_" + String(file.name).replace(/([.]+)/gi, '_')%}' required="required"/></td>
        </div>
        <div>
    <td colspan="1" class="desc">Price: 
        <select id="price" style="padding-left:5px;width:120px;" name='{%= "price_" + String(file.name).replace(/([.]+)/gi, '_')%}' style="width:120px;float:left;margin-left:-70px;" onchange="showOtherPrice()">
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
            <option value="Other Price">Choose Price</option>
            <option value="Not For Sale">Not For Sale</option>
            </select>
        </td>

        </div>
	</div>
<td colspan="1" class="desc">Keywords: <input type="text" style="width:120px;" name='{%= "keyword_" + String(file.name).replace(/([.]+)/gi, '_')%}' required="required"/></td>

        <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
        {% if (file.error) { %}
            <td class="error" colspan="2"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</td>
        {% } else if (o.files.valid && !i) { %}

            <td class="start">{% if (!o.options.autoUpload) { %}
                <button class="btn btn-primary" style="width:80px;">
                    <i class="icon-upload icon-white"></i>
                    <span>{%=locale.fileupload.start%}</span>
                </button>
            {% } %}</td>
        {% } else { %}
            <td colspan="2"></td>
        {% } %}
        <td class="cancel">{% if (!i) { %}
            <button class="btn btn-warning" style="width:80px;">
                <i class="icon-ban-circle icon-white"></i>
                <span>{%=locale.fileupload.cancel%}</span>
            </button>
        {% } %}</td>
    </tr>
{% } %}

</script>
</div>

<div style="width:800px;">
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        {% if (file.error) { %}
            <td></td>
            <td class="name"><span>{%=file.name%}</span></td>
            <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
            <td class="error" colspan="2"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</td>        {% } else { %}
            <td class="preview">{% if (file.thumbnail_url) { %}
                <a href="{%=file.url%}" title="{%=file.name%}" rel="gallery" download="{%=file.name%}"><img src="{%=file.thumbnail_url%}"></a>
            {% } %}</td>
            <td class="name">
                <a href="{%=file.url%}" title="{%=file.name%}" rel="{%=file.thumbnail_url&&'gallery'%}" download="{%=file.name%}">{%=file.name%}</a>
            </td>
            <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
            <td colspan="2"></td>
        {% } %}
        <td class="delete">
            <button class="btn btn-danger" data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}">
                <i class="icon-trash icon-white"></i>
                <span>{%=locale.fileupload.destroy%}</span>
            </button>
            <input type="checkbox" name="delete" value="1">
        </td>
    </tr>
{% } %}
</script>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="js/vendor/jquery.ui.widget.js"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="https://blueimp.github.com/JavaScript-Templates/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="https://blueimp.github.com/JavaScript-Load-Image/load-image.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="https://blueimp.github.com/JavaScript-Canvas-to-Blob/canvas-to-blob.min.js"></script>
<!-- Bootstrap JS and Bootstrap Image Gallery are not required, but included for the demo -->
<script src="https://blueimp.github.com/cdn/js/bootstrap.min.js"></script>
<script src="https://blueimp.github.com/Bootstrap-Image-Gallery/js/bootstrap-image-gallery.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="batch/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="batch/js/jquery.fileupload.js"></script>
<!-- The File Upload file processing plugin -->
<script src="batch/js/jquery.fileupload-fp.js"></script>
<!-- The File Upload user interface plugin -->
<script src="batch/js/jquery.fileupload-ui.js"></script>
<!-- The localization script -->
<script src="batch/js/locale.js"></script>
<!-- The main application script -->
<script src="batch/js/main.js"></script>
<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE8+ -->
<!--[if gte IE 8]><script src="batch/js/cors/jquery.xdr-transport.js"></script><![endif]-->   
                
                            
           <?php

                        }
                        
                        elseif($option == 'newexhibit') {
                        
	    
    echo'
    
    <div style="font-size:12px;padding-left:50px;padding-top:20px;font-family:Helvetica Neue,helvetica,arial;font-weight:200;"><span style="font-size:16px;">* </span>Required fields. Please select more than 2 tags. (Selecting multiple values: Hold down command button if on mac, control button if on PC)</div>

	<form action="create_set.php" method="post" enctype="multipart/form-data">
    
    <div class="span9" style="margin-top:30px;padding-left:30px;">
    <table class="table">
    <tbody>
    
    <tr>
    <td>*Title of exhibit:</td>
    <td><input type="text" name="title" /></td>
    </tr>
    
    <tr>
    <td>*Pick Keywords:</td>
    <td>
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
    </td>
    </tr>
    
    <tr>
    <td>*Choose some of your own tags:</td>
    <td>
    <input style="width:80px;height:20px;" type="text" name="settag1" />
    <input style="width:80px;height:20px;" type="text" name="settag2" />
    <input style="width:80px;height:20px;" type="text" name="settag3" />
    <input style="width:80px;height:20px;" type="text" name="settag4" />
    </td>
    </tr>
        
    <tr>
    <td>About this exhibit:</td>
    <td><textarea style="width:500px" rows="4" cols="60" name="about"></textarea></td>
    </tr>
    
    </tbody>
    </table>

<button type="submit" name="Submit" class="btn btn-success">Create Exhibit</button>
</form>
</div>
    
</div> <!--end of well-->
</div>';

            }
                        
                
                    
                echo'</div>';
    }
    
    
    
    
    elseif($view == 'blog') {
    
        //unhighlight query for blog comments
    
            if(isset($_GET['bi'])){
                $id = htmlentities($_GET['bi']);
                $idformatted = $id . " ";
                $unhighlightquery = "UPDATE userinfo SET unhighlight = CONCAT(unhighlight,'$idformatted') WHERE emailaddress = '$email'";
                $unhighlightqueryrun = mysql_query($unhighlightquery);

        //notifications query reset 
        
            if($currentnotsresult > 0) {
                $notsquery = "UPDATE userinfo SET notifications = 0 WHERE emailaddress = '$email'";
                $notsqueryrun = mysql_query($notsquery); }
            }
    
  
         $option = htmlentities($_GET['option']);
  
         echo'<br /><br /><br /><br /><div style="width:760px;text-align:center;font-size:14px;font-weight:200;"><div style="margin-left:20px;"><a class="green" style="text-decoration:none;'; if($option == 'newpost') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="myprofile.php?view=blog&option=newpost">Make New Post</a> | <a class="green" style="text-decoration:none;color:#333;'; if($option == '') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="myprofile.php?view=blog">View Blog</a></div></div>';
         
        echo'<div id="container" class="grid_18" style="width:770px;margin-top:-38px;margin-left:-10px;padding:35px;">';

        if($option == 'newpost') {
        
        $time = time();
       
         echo'
            <script>
             function showBlogPhoto() {
                   var blogphoto = document.getElementById(\'blogphoto\').value;
                }
            </script>
            
            <div class="grid_18" style="margin:auto;border:1px solid #ccc;margin-top:30px;margin-left:20px;">
            <div style="float:left;padding:15px;width:130px;height:130px;"><div style="width:130px;"></div><br /><div style="padding-left:10px;"><a style="width:90px;padding:7px;" class="btn btn-success" data-toggle="modal" data-backdrop="static" href="#blogphoto">Add Photo</a></div></div>
            <div style="float:left;font-size:15px;font-weight:200;padding-top:25px;">Title:<br /><br />Subject:<br /><br />Content (400 words):</div>
           
            <form action="myprofile.php?view=blog&action=submitpost" method="POST">
            
            <div style="float:left;padding:25px;width:350px;"><input style="width:220px;height:20px;" type="text" name="title" placeholder="Title of Blog Post" /><br />
            <input style="width:220px;height:20px;" type="text" name="subject" placeholder="Subject of Blog Post" /></div>
            <input type="hidden" name="time" value="',$time,'" />
            <div style="float:left;margin-top:15px;"><textarea style="width:480px;max-width:480px;" rows="12" cols="60" name="content"></textarea><br /><br />

             <!--ADD PHOTO TO BLOG POST MODAL-->

            <div class="modal hide fade" id="blogphoto" style="overflow-y:scroll;overflow-x:hidden;border:5px solid rgba(102,102,102,.8);"">

            <div class="modal-header" style="background-color:#111;color:#fff;">
            <a style="float:right" class="btn btn-success" data-dismiss="modal">Close</a>
            <img style="margin-top:-2px;" src="graphics/aperture_white.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">Choose a photo to add to your blog post:</span>
            </div>
            <div modal-body" style="width:600px;">

            <div id="content" style="font-size:16px;width:550px;height:500px;overflow-x:hidden;background-color:rgb(245,245,245);">';

            if($email != '') {
            echo'
            <img style="border: 1px solid black;margin-left:10px;margin-top:10px;" src="',$profilepic,'" 
            height="100px" width="100px" />

            <div style="width:540px;margin-left:130px;margin-top:-125px;overflow-y:scroll;overflow-x:hidden;">

            <span style="font-size:14px;">
            <br />';
            $allusersphotos = "SELECT * FROM photos WHERE emailaddress = '$email' ORDER BY id DESC";
            $allusersphotosquery = mysql_query($allusersphotos);
            $usernumphotos = mysql_num_rows($allusersphotosquery);
    
            for($iii = 0; $iii < $usernumphotos; $iii++) {
            $userphotosource = mysql_result($allusersphotosquery, $iii, "source");
            $userphotosource = str_replace("userphotos/","https://photorankr.com/userphotos/", $userphotosource);
            $userphotosset[$iii] = mysql_result($allusersphotosquery, $iii, "sets");
            $userphotoscaption[$iii] = mysql_result($allusersphotosquery, $iii, "caption");
            $newsource = str_replace("userphotos/","userphotos/thumbs/", $userphotosource);
        
            echo'<img src="',$newsource,'" height="70" width="70" />&nbsp;&nbsp;<input id="blogphoto" type="radio" name="checked" value="',$userphotosource,'" onclick="showBlogPhoto();" />&nbsp;"',$userphotoscaption[$iii],'"
            <br /><br />'; 
    
        } //end of for loop
    
    
        echo'
        </span>
        <button class="btn btn-success" data-dismiss="modal">Submit Photo</button>
        <br />
        <br />';
        }
        
        else {
        echo'<div style="text-align:center;margin-top:100px;"><b>Please login or register to upload</b></div>';
        }
    
        echo'
        </div>
        </div>
        </div></div>
    
        
            <div style="text-align:center;"><button style="width:460px;padding:10px;font-size:15px;font-weight:200;" class="btn btn-success" type="submit">Submit Blog Post</button><br /><br /></div>
            </div>
            </form>
            
            </div>';
            
        }
        
        elseif($option == '') {
        
            $blogquery = mysql_query("SELECT * FROM blog WHERE emailaddress = '$email' ORDER BY id DESC");
            $numblogposts = mysql_num_rows($blogquery);
            
            echo'<div class="grid_18" style="margin:auto;border:1px solid #ccc;margin-top:30px;margin-left:20px;">';
            
            if($numblogposts == 0) {
                echo'<div style="font-size:18px;font-weight:200;padding:40px;text-align:center;"><a style="color:#333;" href="myprofile.php?view=blog&option=newpost">You have no blog posts yet. Click here to write your first post.</a></div>';
            }
            
                for($iii=0; $iii < $numblogposts; $iii++) {
                    $id = mysql_result($blogquery,$iii,'id');
                    $title = mysql_result($blogquery,$iii,'title');
                    $subject = mysql_result($blogquery,$iii,'subject');
                    $content = mysql_result($blogquery,$iii,'content');
                    $photo = mysql_result($blogquery,$iii,'photo');
                    $time = mysql_result($blogquery,$iii,'time');
                        
                    if($time) {
                    $date = date("m-d-Y", $time); }
                    
                    
                    if($photo) {
                        echo'
                        <div style="float:left;padding:20px;width:130px;height:130px;"><img src="',$photo,'" height="120" width="120" /></div>
                        <div style="float:left;font-size:20px;font-weight:200;padding-top:30px;width:520px;">',$title,'</div>
                        <div style="float:left;font-size:15px;font-weight:200;padding-top:15px;">Subject: ',$subject,'&nbsp;|&nbsp;Date: ',$date,'</div>
                       
                        <div style="float:left;margin-top:15px;width:650px;padding:20px;font-size:15px;font-weight:200;line-height:1.48;">',$content,'<br /><br />
                        </div><br />';
                    }
                    
                    else {
                        echo'
                        <div style="float:left;font-size:20px;font-weight:200;padding-left:20px;padding-top:30px;width:520px;">',$title,'</div><br />
                        <div style="float:left;font-size:15px;font-weight:200;padding-left:20px;padding-top:15px;">Subject: ',$subject,'&nbsp;|&nbsp;Date: ',$date,'</div>
                       
                        <div style="float:left;margin-top:15px;width:650px;padding:20px;font-size:15px;font-weight:200;line-height:1.48;">',$content,'<br /><br />
                        </div><br />';
                    }
                    
                    echo'
                    <div style="float:left;margin-top:15px;margin-left:20px;width:650px;padding:10px;font-size:15px;font-weight:200;line-height:1.48;">
                    <div class="panelblog',$id,'">';
                    
                        //Comment Loop
                        $commentquery= mysql_query("SELECT * FROM blogcomments WHERE blogid = '$id'");
                        $numcomments = mysql_num_rows($commentquery);
                        
                            for($ii=0; $ii < $numcomments; $ii++) {
                                $comment = mysql_result($commentquery,$ii,'comment');
                                $commenteremail = mysql_result($commentquery,$ii,'emailaddress');
                                $userquery = mysql_query("SELECT user_id,profilepic,firstname,lastname FROM userinfo WHERE emailaddress = '$commenteremail'");
                                $commenterpic = mysql_result($userquery,0,'profilepic');
                                $commenterid = mysql_result($userquery,0,'user_id');
                                $commentername = mysql_result($userquery,0,'firstname')." ".mysql_result($userquery,0,'lastname');
                                
                                echo'<div><a href="viewprofile.php?u=',$commenterid,'"><img src="',$commenterpic,'" height="30" width="30" /><span style="font-weight:bold;color:#3e608c;font-size:12px;padding-left:10px;">',$commentername,'</a></span>&nbsp;&nbsp;',$comment,'</div><hr>';
                            }
                    echo'
                    <form action="myprofile.php?view=blog&action=comment&blogid=',$id,'" method="POST">
                    <div style="width:620px;"><img style="float:left;padding:10px;" src="',$profilepic,'" height="30" width="30" />
                    <input style="float:left;width:440px;height:20px;position:relative;top:10px;" type="text" name="comment" placeholder="Leave a comment&#8230;" /></div>
                    </form>
                    <br /><br />
                    </div>
                    
                    
                    <a name="',$id,'" href="#"><p class="flipblog',$id,'" style="font-size:15px;"></a>',$numcomments,' Comments</p>
                    </div>
                    
                    <style type="text/css">
                    p.flipblog',$id,' {
                    margin:0px;
                    padding:10px;
                    text-align:center;
                    background:white;
                    border:solid 1px #c3c3c3;
                    }

                    p.flipblog',$id,':hover {
                    background-color: #ccc;
                    }

                    div.panelblog',$id,' {
                    display:none;
                    margin:0px;
                    padding:5px;
                    text-align:left;
                    background:white;
                    border:solid 1px #c3c3c3;
                    }
                    </style>'; ?>
                    
                    <!--HIDDEN COMMENT SCRIPT-->
                    <script type="text/javascript">   
                    $(document).ready(function(){
                    $(".flipblog<?php echo $id; ?>").click(function(){
                        $(".panelblog<?php echo $id; ?>").slideToggle("slow");
                    });
                    });
                    </script>
                    
                    <?php
                    
                    echo'
                    <hr>'; 
                
                }
                
            echo'</div>';
        
        }
        
        echo'</div>';
        
    }
    
    
    
    elseif($view == 'messages') {
            
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
	
		echo '<div class="grid_18" style="padding-left:30px;padding-right:90px;padding-bottom:20px;padding-top:20px;margin-left:-45px;">';

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
                    <span style="font-size:18px;font-weight:200;">Your Conversations:</span><br />
                    <span style="font-size:13px;font-weight:200;">(Contact photographers through the "contact" tab in their profile)</span>
                    <br /><br />';


		for($iii=0; $iii<$numberofmessages; $iii++) {
			$otherspic = mysql_result($moreinforesult, $iii, "profilepic");
			$othersfirst = mysql_result($moreinforesult, $iii, "firstname");
			$otherslast = mysql_result($moreinforesult, $iii, "lastname");
			$currentthread = mysql_result($messageresult, $iii, "thread");

			//now lets display the message with the other's profile picture and name
			echo '
			<a href="myprofile.php?view=viewthread&thread=', $currentthread, '" style="text-decoration: none;">
			<div class="grid_18 message" style="margin-bottom:20px; font-family: helvetica neue; font-size:14px;">
				<div  class="grid_3">
					<img src="', $otherspic, '" width="60px" height="60px" alt="profile picture" style="margin-bottom: 5px;"/>
					<br />', 
					$othersfirst, ' ', $otherslast, 
				'</div>
				<div class="grid_15" style="margin-top: -75px; margin-left: 120px;">', $currentmessage[$iii], 
				'</div>
			</div>
            <hr>
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
	
			echo '<div class="grid_18" style="background-color:rgba(245,245,245,0.6);padding-left:30px;padding-right:90px;padding-bottom:20px;padding-top:20px;margin-left:-45px;">';

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
			$othersquery = "SELECT user_id, firstname, lastname, profilepic, emailaddress FROM userinfo WHERE emailaddress='" . $othersemail . "' LIMIT 0, 1";
			$othersresult = mysql_query($othersquery);
			$otherspic = mysql_result($othersresult, 0, "profilepic");
			$othersfirst = mysql_result($othersresult, 0, "firstname");
			$otherslast = mysql_result($othersresult, 0, "lastname");
            $othersid = mysql_result($othersresult, 0, "user_id");
			
			//for loop to go through all the messages in reverse order so that the newest one is last
			for($iii=$numberofmessages-1; $iii >= 0; $iii--) {
				//find out who sent the current message in the loop
				$currentsender = mysql_result($threadresult, $iii, "sender");

				//if the current message's sender is the owner of the profile, set the variables as necessary
				if($currentsender == $email) {
					$currentfirst = $firstname;
					$currentlast = $lastname;
					$currentpic = $profilepic;
                    $currentuserid = $userid;
				}
				//otherwise the other person is the message's sender, so set the variables accordingly
				else {
					$currentfirst = $othersfirst;
					$currentlast = $otherslast;
					$currentpic = $otherspic;
                    $currentuserid = $othersid;
				}
				
				//find out what the current message is
				$currentmessage = mysql_result($threadresult, $iii, "contents");

				//now that we have everything in line, display the message
				echo '
				<div class="grid_18 message" style="margin-bottom: 20px; font-family: arial;">
					<a href="viewprofile.php?u=',$currentuserid,'">
					<div class="grid_3">
						<img src="', $currentpic, '" width="60px" height="60px" alt="profile_picture" style="margin-bottom: 5px;"/><br />',$currentfirst,' ', $currentlast,' 
					</div>
					</a>
					<div class="grid_15" style="margin-top: -55px; margin-left: 120px;">',$currentmessage,'
					</div>
				</div>
                <hr />';			
			}

			//now let's display the box from which they can send a message
			echo' <div class="grid_18" style="font-size: 20px; font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
			line-height: 28px; color: #333333;">
    
			<span style="font-size:16px;">Reply:</span>
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


elseif($view == 'promote') {

echo'
<div class="grid_18" style="width:770px;margin-top:10px;padding-left:20px;">

<div class="well" style="font-size:16px;font-family:helvetica neue, gill sans, helvetica;">

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

https://photorankr.com/signin.php

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
<div class="fb_share">
    <a name="fb_share" type="box_count" share_url="https://photorankr.com/viewprofile.php?u=',$userid,'"
      href="https://www.facebook.com/sharer.php"></a>
    <script src="https://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>
</div>

<!--TWITTER-->
<div style="position:relative;margin-top:15px;">
<a href="https://twitter.com/share" class="twitter-share-button" data-url="https://photorankr.com/viewprofile.php?u=',$user,'" data-text="Visit my photography site on PhotoRankr!" data-via="PhotoRankr" data-related="PhotoRankr">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
</script></div>

<!--GOOGLE PLUS-->
<div style="position:relative;margin-top:15px;">
<div class="g-plus" data-action="share" data-href="https://photorankr.com/viewprofile.php?u=',$user,'"></div>';
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
<div style="position:relative; top:-20px; left:160px;">
<input style="width:180px;height:22px;" type="text" name="email" placeholder="Email Address"/>
</div>
<div style="position:relative; top:-20px; left:263px;">
<button type="submit" name="Submit" class="btn btn-success">Send Invite</button>
</div>
</form>
</div>

</div>
</div>';

}

    
elseif($view == 'settings') {
    
        $action = htmlentities($_GET['action']);

if ($action == 'savesettings') {

//Sharing Settings
$sharing = mysql_real_escape_string($_POST['sharing']);

    if($sharing == 'optin') {
        $optin = 'optin';
        $sharequery = mysql_query("UPDATE userinfo SET promos = '$optin' WHERE emailaddress = '$email'");
    }
    
    elseif($sharing == 'optout') {
        $sharequery = mysql_query("UPDATE userinfo SET promos = '' WHERE emailaddress = '$email'");
    }
    
    
$emailcomment = mysql_real_escape_string(htmlentities($_POST['emailcomment']));
$emailreturncomment = mysql_real_escape_string(htmlentities($_POST['emailreturncomment']));
$emailfave = mysql_real_escape_string(htmlentities($_POST['emailfave']));		
$emailfollow = mysql_real_escape_string(htmlentities($_POST['emailfollow']));	

$settinglist = $emailcomment . $emailreturncomment . $emailfave . $emailfollow;

$settingquery = "UPDATE userinfo SET settings = '".$settinglist."' WHERE emailaddress = '$email'";
//echo '<br /><br /><br /><br />' . $settingquery;
$settingrun = mysql_query($settingquery) or die('Error querying database.');


//Grab what they have checked
$settingemail = $_SESSION['email'];
$settingquery = "SELECT settings,promos FROM userinfo WHERE emailaddress = '$settingemail'";
$settingqueryrun = mysql_query($settingquery);
$settinglist = mysql_result($settingqueryrun, 0, "settings");
$promos = mysql_result($settingqueryrun, 0, "promos");

echo'
<div class="grid_18" style="background-color:rgba(245,245,245,0.6);padding-left:30px;padding-right:95px;padding-bottom:20px;padding-top:20px;margin-left:-5px;">
<span style="font-size:16px;">Notification Settings:</span>
<br />

    <span style="font-size:18px;position:relative;top:15px;font-weight:200;color:green;">Settings Saved</span><br /><br />

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

<span style="font-size:16px;">Allow your photos to be shared on networks such as Facebook & Twitter?</span>
<br /><br />
<form action="', htmlentities($_SERVER['PHP_SELF']), '?view=settings&action=savesettings" method="POST" />';

if($promos == 'optin') { echo'
<input type="radio" name="sharing" value="optin" checked />&nbsp;Yes, allow others to share my work<br /><br /> 
<input type="radio" name="sharing" value="optout" />&nbsp;No, do not allow others to spread my work<br /><br />
<button type="submit" name="Submit" class="btn btn-success">Save Sharing Settings</button>';
}
elseif($promos == '') { echo'
<input type="radio" name="sharing" value="optin" />&nbsp;Yes, allow others to share my work<br /><br /> 
<input type="radio" name="sharing" value="optout" checked />&nbsp;No, do not allow others to spread my work<br /><br />
<button type="submit" name="Submit" class="btn btn-success">Save Sharing Settings</button>';
}

echo'
</form>
</div>';

}
    
else {
 
 
$settingemail = $_SESSION['email'];
$settingquery = "SELECT settings FROM userinfo WHERE emailaddress = '$settingemail'";
$settingqueryrun = mysql_query($settingquery);
$settinglist = mysql_result($settingqueryrun, 0, "settings");

echo'
<div class="grid_18" style="padding-left:30px;padding-right:95px;padding-bottom:20px;padding-top:20px;margin-left:-5px;">
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

<!--OPT IN/OPT-OUT SHARING-->

<span style="font-size:16px;">Allow your photos to be shared on networks such as Facebook & Twitter?</span>
<br /><br />
<form action="', htmlentities($_SERVER['PHP_SELF']), '?view=settings&action=savesettings" method="POST" />';

if($promos == 'optin') { echo'
<input type="radio" name="sharing" value="optin" checked />&nbsp;Yes, allow others to share my work<br /><br /> 
<input type="radio" name="sharing" value="optout" />&nbsp;No, do not allow others to spread my work<br /><br />
<button type="submit" name="Submit" class="btn btn-success">Save Sharing Settings</button>';
}
elseif($promos == '')  { echo'
<input type="radio" name="sharing" value="optin" />&nbsp;Yes, allow others to share my work<br /><br /> 
<input type="radio" name="sharing" value="optout" checked />&nbsp;No, do not allow others to spread my work<br /><br />
<button type="submit" name="Submit" class="btn btn-success">Save Sharing Settings</button>';
}

echo'
</form>

<!--Choose Background Photo-->';

if($_GET['mode'] == 'updatebackground') {
echo'<br /><span style="position:relative;margin-top:-130px;font-size: 16px;"><span class="label label-success" style="font-size:16px;" >Background Saved</span><br /><br /<br /><br /></span>';
}

/*echo'
<a data-toggle="modal" data-backdrop="static" href="#submitfromportfolio"><button style="margin-top:20px;" class="btn btn-success"><b>Choose Background Image</b></button></a>

</div>';*/

}

//Update Background Modal
echo'<div class="modal hide fade" id="submitfromportfolio" style="overflow-y:scroll;overflow-x:hidden;">

<div class="modal-header">
<a style="float:right" class="btn btn-primary" data-dismiss="modal">Close</a>
<img style="margin-top:-4px;" src="graphics/blacklogo.png" height="30" width="100" />&nbsp;&nbsp;<span style="font-size:16px;">Choose your profile background image:</span>
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:550px;height:500px;overflow-x:hidden;">';

if($email != '') {
echo'
<img style="border: 1px solid black;margin-left:10px;margin-top:10px;" src="',$profilepic,'" 
height="100px" width="100px" />

<div style="width:540px;margin-left:130px;margin-top:-125px;overflow-y:scroll;overflow-x:hidden;">

<form action="myprofile.php?view=settings&mode=updatebackground" method="post">
    <span style="font-size:14px;">
    <br /><br />';
    $allusersphotos = "SELECT * FROM photos WHERE emailaddress = '$email'";
    $allusersphotosquery = mysql_query($allusersphotos);
    $usernumphotos = mysql_num_rows($allusersphotosquery);
    
    for($iii = 0; $iii < $usernumphotos; $iii++) {
        $userphotosource = mysql_result($allusersphotosquery, $iii, "source");
        $userphotosource = str_replace("userphotos/","https://photorankr.com/userphotos/", $userphotosource);
        $userphotosset[$iii] = mysql_result($allusersphotosquery, $iii, "sets");
        $userphotoscaption[$iii] = mysql_result($allusersphotosquery, $iii, "caption");
        $newsource = str_replace("userphotos/","userphotos/thumbs/", $userphotosource);
        
        echo'<img src="',$newsource,'" height="70" width="70" />&nbsp;&nbsp;<input type="checkbox" name="checked" value="',$userphotosource,'" />&nbsp;"',$userphotoscaption[$iii],'"
        <br /><br />'; 
    
    } //end of for loop
    
    
    echo'
    </span>
    <button class="btn btn-success" type="submit">Save Background</button>
    <br />
    <br />
    </form>';
    }
    
    else {
    echo'<div style="text-align:center;margin-top:100px;"><b>Please login or register to upload</b></div>';
    }
    
    echo'
    </div>
    </div>';
    
    }
    
?>

</div><!--end grid 18-->


<?php

    //Edit Exhibit Modal

echo'<div class="modal hide fade" id="editexhibit" style="overflow-y:scroll;overflow-x:hidden;border:5px solid rgba(102,102,102,.8);">

<div class="modal-header" style="background-color:#111;color:#fff;">
<a style="float:right" class="btn btn-success" data-dismiss="modal">Close</a>
<img style="margin-top:-2px;" src="graphics/aperture_white.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">Edit your exhibit\'s information below:</span>
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:550px;height:500px;overflow-x:hidden;background-color:rgb(245,245,245);">
		
<img style="border: 1px solid black;margin-left:10px;margin-top:10px;" src="',$setcover,'" 
height="100px" width="100px" />

<div style="width:540px;margin-left:130px;margin-top:-100px;overflow-y:scroll;overflow-x:hidden;">

<form action="', htmlentities($_SERVER['PHP_SELF']), '?view=exhibits&set=',$set,'&mode=coverchanged" method="post" enctype="multipart/form-data">
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
            echo'<img src="',$newsource,'" height="70" width="70" />&nbsp;&nbsp;<input type="checkbox" name="addthis" value="',$userphotosource[$iii],'" checked />&nbsp;"',$userphotoscaption[$iii],'"
    <br /><br />'; }
        else {
            echo'<img src="',$newsource,'" height="70" width="70" />&nbsp;&nbsp;<input type="checkbox" name="addthis" value="',$userphotosource[$iii],'" />&nbsp;"',$userphotoscaption[$iii],'"
        <br /><br />'; }
        
    } //end of for loop
    
    echo'
    </span>
    <div>
    <button style="float:left;" class="btn btn-success" type="submit">Save Info</button>
    </form>
    <div style="float:left;margin-left:180px;"><a class="btn btn-danger" href="myprofile.php?view=exhibits&set=',$set,'&mode=deleteexhibit">Delete Exhibit</a><div>
    </div>
    
    <br /><br />
    
    </div>
    </div>
    </div>';
    
?>


</div><!--end 24 grid-->



</div>


<!--TUMBLR SCRIPTS-->
<script type="text/javascript">
    var tumblr_link_url = "https://photorankr.com/viewprofile.php?u=',$user,'";
    var tumblr_link_name = "My PhotoRankr Portfolio";
    var tumblr_link_description = "Visit and rank my photography on PhotoRankr!";
</script>

<script type="text/javascript">
    var tumblr_button = document.createElement("a");
    tumblr_button.setAttribute("href", "https://www.tumblr.com/share/link?url=" + encodeURIComponent(tumblr_link_url) + "&name=" + encodeURIComponent(tumblr_link_name) + "&description=" + encodeURIComponent(tumblr_link_description));
    tumblr_button.setAttribute("title", "Share on Tumblr");
    tumblr_button.setAttribute("style", "display:inline-block; text-indent:-9999px; overflow:hidden; width:129px; height:20px; background:url('https://platform.tumblr.com/v1/share_3.png') top left no-repeat transparent;");
    tumblr_button.innerHTML = "Share on Tumblr";
    document.getElementById("tumblr_button_abc123").appendChild(tumblr_button);
</script>

<script type="text/javascript" src="https://platform.tumblr.com/v1/share.js"></script>


</body>
</html>