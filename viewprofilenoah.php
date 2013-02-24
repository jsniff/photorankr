<?php

//connect to the database
require "db_connection.php";
require "functions.php";
require "timefunction.php";

//start the session
session_start();

    // if login form has been submitted
    if(htmlentities($_GET['action']) == "login") { 
        login();
    }
    elseif(htmlentities($_GET['action']) == "logout") { 
        logout();
    }

    $email = $_SESSION['email'];
    $currenttime = time();


    //QUERY FOR NOTIFICATIONS
    $currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$email'";
    $currentnotsquery = mysql_query($currentnots);
    $currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");
    $sessionfirst =  mysql_result($currentnotsquery,0,'firstname');
    $sessionlast =  mysql_result($currentnotsquery,0,'lastname');
    
    //notifications query reset 
    if($currentnotsresult > 0) {
        $notsquery = "UPDATE userinfo SET notifications = 0 WHERE emailaddress = '$email'";
        $notsqueryrun = mysql_query($notsquery); 
    }
    
    //GRAB USER INFORMATION
    $userid = htmlentities($_GET['u']);
    if(!$userid) {
         header('Location: https://www.photorankr.com/trending.php');
    }
    
     //Query Stats Table 
  $timestampentertimeslicequery = mysql_query("INSERT INTO Statistics (ViewTimeStamp, Person, Type, user_id) VALUES ('$currenttime', '$email', 'profileview', '$userid')");

//User information
$userinfo = mysql_query("SELECT * FROM userinfo WHERE user_id = '$userid'");
$profilepic = mysql_result($userinfo,0,'profilepic');
$profilepicthumb = str_replace("profilepics","profilepics/thumbs",$profilepic);
$usersfirst= mysql_result($userinfo,0,'firstname');
$firstname= mysql_result($userinfo,0,'firstname');
$lastname = mysql_result($userinfo,0,'lastname');
$useremail = mysql_result($userinfo,0,'emailaddress');
$fullname = $firstname ." ". $lastname;
$age = mysql_result($userinfo,0,'age');
$gender = mysql_result($userinfo,0,'gender');
$location = mysql_result($userinfo,0,'location');
$camera = mysql_result($userinfo,0,'camera');
$facebookpage = mysql_result($userinfo,0,'facebookpage');
$twitterpage = mysql_result($userinfo,0,'twitterpage');
$bio = mysql_result($userinfo,0,'bio');
$quote = mysql_result($userinfo,0,'quote');
$reputation = mysql_result($userinfo,0,'reputation');
$userreputation = number_format($reputation,1);
$profileviews = mysql_result($userinfo,0,'profileviews');

//ADD PAGEVIEW TO THEIR PROFILE
$profileviewquery = mysql_query("UPDATE userinfo SET profileviews = (profileviews + 1) WHERE user_id = '$userid'");

//Portfolio Information
    $followersquery="SELECT * FROM userinfo WHERE following LIKE '%$useremail%'";
	$followersresult=mysql_query($followersquery);
	$numberfollowers = mysql_num_rows($followersresult);
    
    //Grab Overall Portfolio Ranking
    $userphotos="SELECT points,votes,faves FROM photos WHERE emailaddress = '$useremail'";
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
    
    $scorequery = "UPDATE userinfo SET totalscore = '$portfoliopoints' WHERE emailaddress = '$useremail'";    
    $scoreresult = mysql_query($scorequery);
    
    }
    
    else if ($portfoliovotes < 1) {
    $portfolioranking="N/A";
    }	
    
    //Number Following
    $followingquery = mysql_query("SELECT following FROM following WHERE emailaddress = '$useremail'");
	$numberfollowing = mysql_num_rows($followingquery);

    //Activity Queries
    $activityquery = mysql_query("SELECT * FROM newsfeed WHERE (emailaddress = '$useremail' OR owner = '$useremail') AND type IN ('follow','comment','fave','photo') ORDER BY id DESC LIMIT 13");

    //Get Views & URI
    $view = htmlentities($_GET['view']);
    $searchword = htmlentities($_GET['searchword']);
    $action = htmlentities($_GET['action']);
    $option = htmlentities($_GET['option']);  

    follow;
if(isset($_GET['fw'])) {
$follow=$_GET['fw'];
}
else {$follow=0;}

if ($follow==1) {
	if($_SESSION['loggedin'] == 1) {
    
		$emailquery=("SELECT * FROM userinfo WHERE emailaddress ='$email'");
		$emailresult=mysql_query($emailquery);
		$prevemails=mysql_result($emailresult, 0, "following");
		$viewerfirst = mysql_result($emailresult, 0, "firstname");
		$viewerlast = mysql_result($emailresult, 0, "lastname");
		if($prevemails == "") {$emailaddressformatted="'". $useremail . "'";}
		else {$emailaddressformatted=", '". $useremail . "'";}
        
		//MAKE SURE FOLLOWER ISN'T ADDED TWICE
		$search_string=$prevemails;
		$regex="/$useremail/";
		$match=preg_match($regex,$search_string);
		if ($match > 0) {

		} 
        
		else {
        
			$followingstring=$prevemails . $emailaddressformatted;
			$followingstring=addslashes($followingstring);
			$followquery = "UPDATE userinfo SET following = '$followingstring' WHERE emailaddress='$email'";
			$followingresult=mysql_query($followquery);
            
            //New following query
            $newfwquery="INSERT INTO following (following,time,emailaddress) VALUES ('$userid','$currenttime','$email')";
            mysql_query($newfwquery);
            
             $type2 = "follow";
             $ownername = $firstname . " " . $lastname;
        $newsfeedfollowquery="INSERT INTO newsfeed (firstname, lastname, emailaddress,following,type,owner,time) VALUES ('$viewerfirst', '$viewerlast', '$email','$useremail','$type2','$ownername','$currenttime')";
        $follownewsquery = mysql_query($newsfeedfollowquery);
        
        //notifications query     
$notsquery = "UPDATE userinfo SET notifications = (notifications + 1) WHERE emailaddress = '$useremail'";
$notsqueryrun = mysql_query($notsquery);  
            
             		//PERSON NOW BEING FOLLOWED
    
//GRAB SETTINGS LIST
$settingquery = "SELECT * FROM userinfo WHERE emailaddress = '$useremail'";
$settingqueryrun = mysql_query($settingquery);
$settinglist = mysql_result($settingqueryrun, 0, "settings");

$setting_string = $settinglist;
$find = "emailfollow";
$foundsetting = strpos($setting_string,$find);
    
        		$to = '"' . $firstname . ' ' . $lastname . '"' . '<'.$useremail.'>';
        		$subject = $viewerfirst . " " . $viewerlast . ' is now following your photography on PhotoRankr!';
        		$message = 'You have a new follower on PhotoRankr! Visit their photography here: https://photorankr.com/viewprofile.php?u='.$sessionuserid;
        		$headers = 'From:PhotoRankr <photorankr@photorankr.com>';
                if($foundsetting > 0) {
        		mail($to, $subject, $message, $headers);   
                }
		}
	}
}

 //Unfollow Query

    if(htmlentities($_GET['uf']) == 1) {

        $followingquery = mysql_query("SELECT following FROM userinfo WHERE emailaddress = '$email'");
        $following = mysql_result($followingquery,0,'following');
        $updatefollowing = "UPDATE userinfo SET following = replace(following,'$useremail','') WHERE emailaddress = '$email'";	
        $updaterun = mysql_query($updatefollowing);

    }

//Exhibit Fave
 
    if($_GET['exfv'] == 1) {
    
        $set = $_GET['set'];
        
        $grabsettitle = mysql_query("SELECT title FROM sets WHERE set = '$set'");
        $settitle = mysql_result($grabsettitle,0,'title');
        
        if($_SESSION['loggedin'] == 1) {
        
            $exhibitfavecheck = mysql_query("SELECT exhibitfaves FROM userinfo WHERE emailaddress = '$email'");
            $faves = mysql_result($exhibitfavecheck,0,'exhibitfaves');
            
            $match=strpos($faves, $set);
        
            if(!$match) {
                $formattedset = '"' . $set . '",';
                $setexfave = mysql_query("UPDATE userinfo SET exhibitfaves = CONCAT(exhibitfaves,'$formattedset') WHERE emailaddress = '$email'");
                $incrementsetfave = mysql_query("UPDATE sets SET faves = (faves + 1) WHERE id = '$set'");
                
                //newsfeed query
                $type = "exhibitfave";
                $newsfeedexhibitfavequery = mysql_query("INSERT INTO newsfeed (firstname,lastname,emailaddress,type,source,owner,time) VALUES ('$sessionfirst', '$sessionlast','$email','$type','$set','$useremail','$currenttime')");
     
                //notifications query     
                $notsquery = "UPDATE userinfo SET notifications = (notifications + 1) WHERE emailaddress = '$useremail'";
                $notsqueryrun = mysql_query($notsquery);       
 
                //GRAB SETTINGS LIST
                $settingquery = "SELECT settings FROM userinfo WHERE emailaddress = '$useremail'";
                $settingqueryrun = mysql_query($settingquery);
                $settinglist = mysql_result($settingqueryrun, 0, "settings");
                                  
                $setting_string = $settinglist;
                $find = "emailfave";
                $foundsetting = strpos($setting_string,$find);
            
                //MAIL PHOTOGRAPHER NOTICE THAT THEIR PHOTO HAS BEEN FAVORITED
                $to = '"' . $sessionfirst . ' ' . $sessionlast . '"' . '<'.$useremail.'>';
                $subject = $sessionfirst . " " . $sessionlast . " favorited one of your exhibits on PhotoRankr";
                $favemessage = $firstname . " " . $lastname . " favorited one of your exhibits on PhotoRankr
        
To view the exhibit, click here: https://photorankr.com/viewprofile.php?u=".$userid."&view=exhibits&set=".$set;
                $headers = 'From:PhotoRankr <photorankr@photorankr.com>';
          
                if($foundsetting > 0) {
                    mail($to, $subject, $favemessage, $headers); 
                }

            } //end of no match
        
        } //end session check

    }


 $toprankedphotos2 = "SELECT * FROM photos WHERE emailaddress = '$useremail' ORDER BY points DESC";
    $toprankedphotosquery2 = mysql_query($toprankedphotos2);
    $numtoprankedphotos2 = mysql_num_rows($toprankedphotos2);

    for($i=0;$i<15;$i++){
    $toprankedphotopoints2 = (mysql_result($toprankedphotosquery2, $i, "points")/mysql_result($toprankedphotosquery2, $i, "votes")) + $toprankedphotopoints2;
    }
        
    $userphotos2="SELECT * FROM photos WHERE emailaddress = '$useremail'";
    $userphotosquery2=mysql_query($userphotos2);
    $numphotos2=mysql_num_rows($userphotosquery2);
    
    //Gather Total Number of Votes for All Photos (This is Visibility)
    for($ii=0; $ii<$numphotos2;$ii++){
    $totalvotes2 = mysql_result($userphotosquery2, $ii, "votes") + $totalvotes2; 
    }
        

    $followersquery2="SELECT * FROM userinfo WHERE following LIKE '%$useremail%'";
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

    $insertquery=mysql_query("UPDATE userinfo SET reputation = $ultimatereputation WHERE emailaddress='$useremail'");
    mysql_query($insertquery);

?>


<!DOCTYPE HTML>
<head>
	<meta charset = "UTF-8">
	<meta name="viewport" content="width=1280px">
	<title> Sell, share and discover brilliant photography </title>
	<link href = "css/bootstrap1.css" rel="stylesheet" type="text/css"/>
	<link href = "css/main2noah.css" rel="stylesheet" type="text/css"/>
    <link href = "css/main2 devCollections.css" rel="stylesheet" type="text/css"/>
	<link href = "css/grid.css" rel="stylesheet" type="text/css"/>
	<link href = "css/reset.css" rel="stylesheet" type="text/css"/>
	<link href = "css/normalize.css" rel="stylesheet" type="text/css"/>
	
	<link rel="stylesheet" media='screen and (max-width:640px)' href="css/640.css"/>
	<link href="graphics/favicon.png" type="image/x-png" rel="shortcut icon"></link>
	<script src="js/modernizer.js"></script>
	<style type="text/css">

.fixedTop
{
	position: fixed;
	top: 50px;
}
::-webkit-input-placeholder 
{
    color:    #444;
}
:-moz-placeholder,
::-moz-placeholder 
{
	color:	#444;
}
@-moz-document url-prefix() {
	#button {
		margin-top:-70px;
	}
	#followBtn
	{
		padding-right:13px !important;
	}
	}
    
    .commentTriangle {
width: 0px;
height: 0px;
float:left;
margin-top:-36px;
margin-left:-387px;
border-style: solid;
border-width: 7.5px 13px 7.5px 0;
border-color: transparent #eee transparent transparent;
z-index:9;
}
      
  .statoverlay

{
background-attachment: scroll;
background-clip: border-box;
background-color: 
rgba(0, 0, 0, 0.848438);
background-image: none;
background-origin: padding-box;
color: rgb(255, 255, 255);
bottom: 0px;
display: block;
font-family: 'Helvetica Neue', 'Helvetica Neue', Helvetica, Arial, sans-serif;
font-size: 14px;
font-style: normal;
font-variant: normal;
font-weight: normal;
line-height: 0px;
margin-bottom: 0px;
margin-left: 0px;
margin-right: 0px;
margin-top: 0px;
overflow-x: hidden;
overflow-y: hidden;
padding-bottom: 0px;
padding-left: 0px;
padding-right: 0px;
padding-top: 0px;
white-space: nowrap;
width: 270px;
-moz-box-shadow: 1px 1px 5px #888;
-webkit-box-shadow: 1px 1px 5px #888;
box-shadow: 1px 1px 5px #888;
}

	</style>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script type="text/javascript" src="js/jquery.wookmark.js"></script>

	<!--ANALYTICS CODE-->

	<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-28031297-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
  
  function createRequestObject() {

    var ajaxRequest;  //ajax variable
	
	try{
		// Opera 8.0+, Firefox, Safari
		ajaxRequest = new XMLHttpRequest();
	} catch (e){
		// Internet Explorer Browsers
		try{
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try{
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e){
				// Something went wrong
				alert("Your browser broke!");
				return false;
			}
		}
	}
    
    return ajaxRequest;
    
}

    //AJAX FAVE
    function ajaxFunction(image){
        var image = image;
        ajaxRequest = createRequestObject();
        // Create a function that will receive data sent from the server
        ajaxRequest.onreadystatechange = function(){
            if(ajaxRequest.readyState == 4  && ajaxRequest.status == 200){
                var ajaxDisplay = document.getElementById('ajaxFave' + image);
                ajaxDisplay.innerHTML = ajaxRequest.responseText;
            }
        }
        var age = "<?php echo $email; ?>";
        var queryString = "?age=" + age + "&image=" + image;
        ajaxRequest.open("GET", "ajaxfavegallery.php" + queryString, true);
        ajaxRequest.send(null); 
    }
    
    function ajaxFollow(){

    ajaxRequest = createRequestObject();
	
    // Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
            var ajaxDisplay = document.getElementById('ajaxFollow');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
		}
	}
	
	var follower = "<?php echo $email; ?>";
    var followee = "<?php echo $emailaddress; ?>";
	var queryString = "?follower=" + follower + "&followee=" + followee;
	ajaxRequest.open("GET", "ajaxfollowprofile.php" + queryString, true);
	ajaxRequest.send(null); 

}

	</script>
    
    <!--Message Modal-->
<div class="modal hide fade" id="messagemodal" style="overflow-y:scroll;overflow-x:hidden;border:5px solid rgba(102,102,102,.8);z-index:100000;">
  
<?php
 
if($_SESSION['loggedin'] !== 1) {

echo'
<div class="modal-header" style="background-color:rgba(234,234,234,.9);color:#333;">
<a style="float:right" class="btn btn-success" data-dismiss="modal">Close</a>
<img style="margin-top:-2px;"  src="graphics/aperture_dark.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">Please login to message ',$firstname,'</span>
  </div>
 
<div modal-body" style="width:450px;height:145px;">

<div id="content" style="font-size:16px;width:560px;font-family:helvetica,arial;font-weight:100;background-color:rgb(252,252,252);height:150px;">
		
<img class="roundedall" style="margin-left:20px;margin-top:20px;" src="',$profilepic,'" 
height="100px" width="100px" />

<div style="width:350px;margin-left:140px;margin-top:-75px;line-height:1.48;">              

',$firstname,' ',$lastname,'<br />

</div>
</div>';
    
}

elseif($_SESSION['loggedin'] == 1) {
    echo'

    <div class="modal-header" style="background-color:rgba(234,234,234,.9);color:#333;">
    <a style="float:right" class="btn btn-success" data-dismiss="modal">Close</a>
    <img style="margin-top:-2px;" src="graphics/aperture_dark.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight: 100;">Message ',$firstname,' below</span>
    </div>

    <div modal-body" style="width:450px;height:190px;">

    <div id="content" style="font-size:16px;width:560px;font-family:helvetica,arial;font-weight:300;background-color:rgb(252,252,252);height:190px;">
		
    <img class="roundedall" style="margin-left:20px;margin-top:20px;" src="',$profilepic,'" 
height="100px" width="100px" />

    <div style="width:350px;margin-left:140px;margin-top:-100px;line-height:1.48;font-size:14px;">              

    Message:<br />
    
    <form method="post" action="sendmessage.php" />
        <textarea style="width:360px;height:70px;" name="message"></textarea>
        <br />   
        <button style="float:right;margin-right:-15px;" type="submit" class="btn btn-success">Send</button>
        <input type="hidden" name="emailaddressofviewed" value="',$useremail,'" />
    </form>

    </div>
    </div>';
    
        } 
 ?>

</div>
</div>

</head>

<body id="body" >
<!-- Left Nav -->

<!--Main Content-->

<div id="Main">
	<a id="menuBtn" href="#"><img style="height:27px;" src="graphics/menu_i.png"/></a>
	<div id="left_bar" style="height:100%;">

	<ul>
		
		<a href="galleries.php"><li> <img src="graphics/galleries_b.png"/><p> Galleries </p><div class="arrow-right"></div></li></a>
		<a href="newsfeed.php"><li><img src="graphics/news_b.png"/> <p> News </p> </li></a>
		<a href="groups.php"><li><img src="graphics/groups_b.png"/> <p> Groups </p> </li></a>
		<a href="market.php"><li><img src="graphics/market_b.png"/> <p> Market </p> </li></a>
		<a href="blog.php"><li> <img src="graphics/blog_b.png"/> <p>Blog</p>    </li></a>
	</ul>

</div>

<div class="topNav">
	<div class="center">
	<ul>
		<a style="padding:0;background:none;	"href=""><li> <img src="graphics/logo_big_w.png"/></li></a>
		<li id="searchTopNav" style="margin-left:10em;">
			<form >
				<input type="text" placeholder="Search" onkeyup="showResult(this.value)"/>
				<img src="graphics/search_i.png" style="width:20px;float:right;position:relative;top:-27px;"/>
			</form>	
		</li>
		<a href="" class="dropdown" style="display:block;padding:.425em .5em;margin-left:17em;font-size:14px;border-right:1px solid #666;background: rgba(255,255,255,.05);">
			<li>
		 		<img style="width:30px;border:1px solid #eee;margin: -6px 5px 0 0;border-radius:17px;" src="img/profilePic.jpg"/>
		  			Noah Willard 
		  	</li>
		</a> 
		<li class="dropdown" id="accountmenu" style="width:2.25em;border-right: 1px solid #666;">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#"> <img style="width:21px;margin-top:-4px;" src="graphics/menu.png"/> </a>
				<ul class="dropdown-menu" style="background: url('graphics/paper.png'), #eee;box-shadow: 0 2px 10px 1px #333;
				width:150px;margin-top:10px;left:-60px;">
					<div class="triangle"> </div> 
					<a class="topBarElement" href=""><li> Profile </li></a>
					<a class="topBarElement" href=""><li> Portfolio </li></a>
					<a class="topBarElement" href=""><li> Store </li></a>
					<a class="topBarElement" href=""><li> Messages </li></a>
				</ul>
		</li>
		<li class="dropdown" id="accountmenu" style="width:2.45em;padding:.15em 0 .4em;margin:0;border-right: 1px solid #666;">
			<a class="dropdown-toggle" id="notifications" data-toggle="dropdown" href="#"> <span> 8 </span> </a>
				<ul class="dropdown-menu">
					<a href=""><li>asd </li></a>
					<a href=""><li>asd </li></a>
					<a href=""><li>asd </li></a>
					<a href=""><li>asd </li></a>
				</ul>
		</li>
		<li class="dropdown" id="accountmenu" style="width:2.25em;padding:.15em 0 0;margin:0;border-right: 1px solid #666;">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#"> <img style="width:21px;margin-top:-6px;" src="graphics/fave_w.png"> </a>
			<ul class="dropdown-menu" >
					<a href=""><li>asd </li></a>
					<a href=""><li>asd </li></a>
					<a href=""><li>asd </li></a>
					<a href=""><li>asd </li></a>
				</ul>
		</li>

		<!-- <a href="" style="width:2em;margin-left:3.5em;"><li id="status"> <img src="graphics/status_i.png"> </li></a> -->
		<a href="" style="background:none;margin-left:2em;"><li id="upload"><button class="btn-2" id="upload"><span>Upload </span> <img src="graphics/upload_i.png"> </button> </li></a>

		<!-- <a href="" style="margin-left:14em;font-size:14px;"><li> <img style="width:32px;border:1px solid #eee;margin: 1px 5px 0 0;border-radius:17px;" src="img/profilePic.jpg"/> Noah Willard </li></a> 
		<a href=""><li> <img style="width:23px;margin-top:5px;" src="graphics/topMenu_i.png"/> </li></a>
		<a href=""><li id="notifications"><span> 8 </span>  </li></a>			
		<a href=""><li id="status" style="margin-left:2em;"> <img src="graphics/status_i.png"> </li></a>
		<a href=""><li id="upload"><button class="btn-2" id="upload"><span>Upload </span> <img src="graphics/upload_i.png"> </button> </li></a> -->
	</ul>
</div>
</div>

<!--BEGIN PAGE CONTENT OUTSIDE CONTAINER-->


<!--END CONTENT OUTSIDE CONTAINER-->

<!--BEGIN CONTAINER-->

<div class="container_custom" id="main_container" style="margin:40px auto 0 auto;width:1172px;">

	<!--BEGIN PAGE CONTENT IN CONTAINER-->
	<div class="container_custom" style="min-height:800px;width:1162px;">
	
		<!--TOP CONTAINER SECTION-->
		<div id="topContainer1">

			<!--ID CARD-->
			<div id="idcard">
				
				<a href=""><header> <?php echo $fullname; ?> </header></a>
				<div id="profilePicContainer">
					<img src="https://photorankr.com/<?php echo $profilepic; ?>"/>
				</div>

				<ul style="margin-top:-150px;">
					<li> 
						<img src="graphics/rep_c.png"/> 
						<header> <?php echo $reputation; ?> </header>
						<p> Rep. </p>
					</li>
					<li> 
						<img src="graphics/camera_c.png"/> 
						<header> <?php echo $numphotos; ?> </header>
						<p> Pictures </p>
					</li>
					<li style="margin-left:5px;"> 
						<img style="width:65px;margin-left:-5px;" src="graphics/network_c.png"/> 
						<header> <?php echo $numberfollowers; ?> </header>
						<p> Followers </p>
					</li>
				</ul>
				<ul style="margin-top:-80px;">
					<li class="row2" style="margin-left:10px !important;"> 
						<img style="width:30px;" src="graphics/fave_i.png"/> 
						<h1>  <?php echo $portfoliofaves; ?> </h1>
						<p> Faves </p>
					</li >
					<li class="row2"> 
						<img style="width:30px;" src="graphics/rank_i.png"/> 
						<h1 style="margin-top:7px;"> <?php echo $portfolioranking; ?> </h1>
						<p> Avg. Rank </p>
					</li>
					<li class="row2"> 
						<img style="width:30px;margin-top:5px;" src="graphics/views.png"/>
						<h1 style="margin-top:14px;"> <?php echo $profileviews; ?> </h1>
						<p> Views </p>
					</li>
				</ul>

				<div class="profileBtnContainer" id="button">
                     <?php
                    if($_SESSION['loggedin'] == 1) {
                        $followingcheck = mysql_query("SELECT * FROM following WHERE emailaddress ='$email' AND following = '$userid'");
                        $numfollowingcheck = mysql_num_rows($followingcheck);
                        
                        if($numfollowingcheck > 0) {
                            echo'<a id="ajaxFollow" onclick="ajaxFollow()"><button id="followBtn" style="padding:6px 15px 6px 45px;margin: 0px 0 3px 0;" class="btn-2"> <i style="position:relative;left:-30px;top:0px;" class="icon-ok icon-white"></i> Following </button></a>';
                        }
                        elseif($numfollowingcheck < 1) {
                            echo'<a id="ajaxFollow" onclick="ajaxFollow()"><button id="followBtn" style="padding:6px 15px 6px 45px;margin: 0px 0 3px 0;" class="btn-2"> <img style="width:33px;margin: -3px 36px 0 -39px;" src="graphics/addNetwork_i_w.png"/> Follow </button></a>';
                        }
                    }
                    else {
                        echo'<a href="register.php" id="ajaxFollow" onclick="ajaxFollow()"><button id="followBtn" style="padding:6px 15px 6px 45px;margin: 0px 0 3px 0;" class="btn-2"> <i style="position:relative;left:-30px;top:0px;" class="icon-ok icon-white"></i> Following </button></a>';
                    }
                ?>
                
					
                    
					<a data-toggle="modal" data-backdrop="static" href="#messagemodal"><button style="padding:6px 16px 6px 38px;" class="btn-2"> <img style="width:28px;margin: -2px 23px 0 -32px;" src="graphics/message_i_w.png"/>Message </button></a>
				</div>
				

			</div>

			<!--ACTIVITY-->
			<div id="Activity">

				<a href=""><header> Activity </header></a>

				<ul class="uiScrollableAreaTrack invisible_elem">
					<?php
                    for($iii=0; $iii <= 20; $iii++) {
                        $firstname = mysql_result($activityquery,$iii,'firstname');
                        $lastname = mysql_result($activityquery,$iii,'lastname');
                        $owneremail = mysql_result($activityquery,$iii,'owner');
                        $fullname = $firstname . " " . $lastname;
                        $fullname = ucwords($fullname);
                        $fullname = (strlen($fullname) > 16) ? substr($fullname,0,14). "&#8230;" : $fullname;
                        $type = mysql_result($activityquery,$iii,'type');
                        $id = mysql_result($activityquery,$iii,'id');
                        $caption = mysql_result($activityquery,$iii,'caption');
                        $source = mysql_result($activityquery,$iii,'source');
                        $time = mysql_result($activityquery,$iii,'time');
                        $time = converttime($time);
                        
                        //Owner Info
                        $getownerinfo = mysql_query("SELECT firstname,lastname,user_id FROM userinfo WHERE emailaddress = '$owneremail'");
                        $ownerfirst = mysql_result($getownerinfo,0,'firstname');
                        $ownerlast = mysql_result($getownerinfo,0,'lastname');
                        $ownerfull = $ownerfirst . " " . $ownerlast;
                        $ownerid = mysql_result($getownerinfo,0,'user_id');
                        
                        $commentphotoquery = mysql_query("SELECT source,id FROM photos WHERE (id = '$source' or source = '$source')");
                        $commentphoto = mysql_result($commentphotoquery,0,'source');
                        $photoid = mysql_result($commentphotoquery,0,'id');

                                    
                        $newsource = str_replace("userphotos/","userphotos/thumbs/", $source);
                        $commentphotosource = str_replace("userphotos/","userphotos/thumbs/", $commentphoto);
                                    
                        $exhibitsource = mysql_query("SELECT cover FROM sets WHERE id = '$source'");
                        $setcover = mysql_result($exhibitsource,$iii,'cover');
                            if(!$setcover) {
                                $pulltopphoto = mysql_query("SELECT source FROM photos WHERE set_id = '$source' ORDER BY votes DESC LIMIT 1");
                                $setcover = mysql_result($pulltopphoto, 0, "source");
                            }
                        $setcover = str_replace("userphotos/","userphotos/thumbs/", $setcover);
                                    
                        $blogcommenteremail = mysql_result($notsquery,$iii,'emailaddress');
                        $followeremail = mysql_result($notsquery,$iii,'emailaddress');
                        
                        if($type == "comment") {
                           echo'<a style="text-decoration:none;" href="fullsize.php?imageid=',$source,'">
                                <div style="padding:3px;clear:both;overflow:hidden;border-bottom:1px solid #aaa;color:#aaa;padding-left:0px;">
                                 <img style="padding-left:2px;float:left;width:80px;height:80px;" src="https://www.photorankr.com/',$commentphotosource,'" />
                                    <div class="commentTriangle" style="margin-top:-20px;"></div>
                                    <div style="width:200px;float:left;padding-left:10px;height:55px;margin-top:10px;text-align:left;font-size:13px;font-weight:300;color:#333;">
                                        <span style="width:15px;"><img src="graphics/comment_1.png" height="15" />&nbsp;&nbsp;&nbsp;<b>',$fullname,'</b> commented on ',$ownerfull,'\'s photo<br /><span style="font-size:12px;color:#666;font-weight:700;">',$time,'</span></span>
                                    </div>
                                </div>
                            </a>';
                        } //end type comments
                        
                        elseif($type == "fave") {
                            echo'<a style="text-decoration:none;" href="fullsize.php?imageid=',$photoid,'">
                                <div style="padding:3px;clear:both;overflow:hidden;border-bottom:1px solid #aaa;color:#aaa;padding-left:0px;">
                                 <img style="padding-left:2px;float:left;width:80px;height:80px;" src="https://www.photorankr.com/',$newsource,'" />
                                    <div class="commentTriangle" style="margin-top:-20px;"></div>
                                    <div style="width:200px;float:left;padding-left:10px;height:55px;margin-top:10px;text-align:left;font-size:13px;font-weight:300;color:#333;">
                                        <span style="width:15px;"><img src="graphics/heart.png" height="15" />&nbsp;&nbsp;&nbsp;<b>',$fullname,'</b> favorited ',$ownerfull,'\'s photo<br /><span style="font-size:12px;color:#666;font-weight:700;">',$time,'</span></span>
                                    </div>
                                </div>
                            </a>';

                        } //end type faves
                        
                         elseif($type == "exhibitfave") {
                            echo'<a style="text-decoration:none;" href="viewprofile.php?u=',$userid,'&view=exhibits&set=',$source,'&id=',$id,'">
                                <div style="padding:3px;clear:both;overflow:hidden;border-bottom:1px solid #aaa;color:#aaa;padding-left:0px;">
                                 <img style="padding-left:2px;float:left;width:80px;height:80px;" src="https://www.photorankr.com/',$setcover,'" />
                                    <div class="commentTriangle" style="margin-top:-20px;"></div>
                                    <div style="width:200px;float:left;padding-left:10px;height:55px;margin-top:10px;text-align:left;font-size:13px;font-weight:300;color:#333;">
                                        <span style="width:15px;"><img src="graphics/grid.png" height="15" />&nbsp;&nbsp;&nbsp;<b>',$fullname,'</b> favorited your exhibit<br /><span style="font-size:12px;color:#666;font-weight:700;">',$time,'</span></span>
                                    </div>
                                </div>
                            </a>';

                        } //end type exhibit faves
                        
                        elseif($type == "trending") {
                            echo'<a style="text-decoration:none;" href="fullsize.php?image=',$source,'&id=',$id,'">
                                <div style="padding:3px;clear:both;overflow:hidden;border-bottom:1px solid #aaa;color:#aaa;padding-left:0px;">
                                 <img style="padding-left:2px;float:left;width:80px;height:80px;" src="https://www.photorankr.com/',$newsource,'" />
                                    <div class="commentTriangle" style="margin-top:-20px;"></div>
                                    <div style="width:200px;float:left;padding-left:10px;height:55px;margin-top:10px;text-align:left;font-size:13px;font-weight:300;color:#333;">
                                        <span style="width:15px;"><img src="graphics/graph.png" height="15" />&nbsp;&nbsp;&nbsp;Your photo is now trending<br /><span style="font-size:12px;color:#666;font-weight:700;">',$time,'</span></span>
                                    </div>
                                </div>
                            </a>';

                        } //end type trending

                        elseif($type == "follow") {
                            $newaccount = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$followeremail'");
                            $ownerid = mysql_result($newaccount,0,'user_id');
                            $profilepic = mysql_result($newaccount,0,'profilepic');
                            if($profilepic == "") {
                                $profilepic = "profilepics/default_profile.jpg";
                            }
                            
                            echo'<a style="text-decoration:none;color:#333;" href="viewprofile.php?u=',$ownerid,'&id=',$id,'">
                                <div style="padding:3px;clear:both;overflow:hidden;border-bottom:1px solid #aaa;color:#aaa;padding-left:0px;">
                                 <img style="padding-left:2px;float:left;width:80px;height:80px;" src="https://www.photorankr.com/',$profilepic,'" />
                                    <div class="commentTriangle" style="margin-top:-20px;"></div>
                                    <div style="width:200px;float:left;padding-left:10px;height:55px;margin-top:10px;text-align:left;font-size:13px;font-weight:300;color:#333;">
                                        <span style="width:15px;"><img src="graphics/user.png" height="15" />&nbsp;&nbsp;&nbsp;<b>',$fullname,'</b> is now following ',$fullname,'\'s photography<br /><span style="font-size:12px;color:#666;font-weight:700;">',$time,'</span></span>
                                    </div>
                                </div>
                            </a>';

                        } //end type follow
                                                
                    } //end notifications for loop
                    ?>
				</ul>

			</div>

			<!--SNAPSHOT-->
			<div id="SnapShot">

				<a href=""><header> Network <span> Following <?php echo $numberfollowing; ?></span></header></a>

				<ul>
                    <?php
                        for($iii=0; $iii<12 & $iii<$numberfollowing;$iii++) {
                            $memberid = mysql_result($followingquery, $iii, 'following');
                            $getuserinfo = mysql_query("SELECT firstname,lastname,profilepic FROM userinfo WHERE user_id = '$memberid'");
                            $profilepic = mysql_result($getuserinfo,0,'profilepic');
                            $fullname = mysql_result($getuserinfo,0,'firstname') ." ". mysql_result($getuserinfo,0,'lastname');
                             $fullname = (strlen($fullname) > 15) ? substr($fullname,0,12). "&#8230;" : $fullname;

                            echo'<li> <a href="viewprofile.php?u=',$memberid,'"><div class="picCircle"><img src="https://photorankr.com/',$profilepic,'"></div></a><span> ',$fullname,'  </span></li>';
                            
                        }
                        
                    ?>
                    
				</ul>

			</div>
		</div>
        
        <?php flush(); ?>

		<!--BEGIN PROFILE NAV-->
		<div id="cookie">
		<div id="profileNav">

			<ul>
				<a href="?u=<?php echo $userid; ?>"><li> Portfolio <img src=""/> </li></a>
				<a href="?u=<?php echo $userid; ?>&view=collections"><li> Collections <img src=""/> </li></a>
				<a href=""><li> Store <img src=""/> </li></a>
				<a href=""><li> About <img src=""/> </li></a>
				<a href=""><li> Blog <img src=""/> </li></a>
				<a href=""><li> Network <img src=""/> </li></a>
				<a href=""><li> Groups <img src=""/> </li></a>

					<li>
						<form id="subnavSearch" action="searchProfile.php">
							<input type="text" placeholder="Search Portfolio&hellip;" >
						</form>
					</li>
			</ul>

		</div>

		<!--BEGIN SUBNAV PORFOLIO-->
		<div id="subnavPortfolio">
        
        <?php if($view == '') {
            
            echo'
			<ul>
				<a href="#" id="PList"><li> Portfolio <img style="width:8px;margin-left:8px;" id="A1" src="graphics/arrowLeft_w.png"/> </li> </a> 

				<li id="subNavList1">
					<ul>
						<a'; if($option == '') {echo'id="subNavPressed"';} echo'href="viewprofile.php?u=',$userid,'"><li> Newest </li></a>
						<a'; if($option == 'top') {echo'id="subNavPressed"';} echo'href="viewprofile.php?u=',$userid,'&option=top"><li> Top Ranked </li></a>
						<a'; if($option == 'fave') {echo'id="subNavPressed"';} echo'href="viewprofile.php?u=',$userid,'&option=fave"><li> Most Faved </li></a>
                        <a'; if($option == 'battles') {echo'id="subNavPressed"';} echo'href="viewprofile.php?u=',$userid,'&option=fave"><li> Battles </li></a>

					</ul>	
				</li>	
					
				<a href="#" id="EList" style=" float:left;"><li> Exhibits <img style="width:8px;margin-left:10px;" id="A2" src="graphics/arrowRight_w.png"/> </li></a>
				<li  id="subNavList2">
					<ul>
						<ul>
						<a href="#"><li> Newest </li></a>
						<a href="#"><li> Top Ranked </li></a>
						<a href="#"><li> Most Faved </li></a>
						<a href="#"><li> Battles </li></a>
					</ul>
					</ul>	
				</li>	
					
					
			</ul>';
        
        }   //end subnav portfolio
        
        elseif($view == 'collections') {
            
            echo'
            <ul>
				<a style="width:10em;" href="#" id="PList" ><li style="width:10em;"> ',$sessionfirst,'\'s Collections <img style="width:8px;margin-left:10px;" id="A1" src="graphics/arrowRight_w.png"/> </li> </a> 

				<li  id="subNavList1" style="width:0;">
					<ul>
						<a href="#"><li> Newest </li></a>
						<a href="#"><li> Top Ranked </li></a>
						<a href="#"><li> Most Faved </li></a>
					</ul>	
				</li>
				<a style="float:right;margin:-8px -70px 0 20px;z-index:10001;"href="#" ><li> <button id="newCollection" class="btn-2"> + Collection </button> </li> </a>
			</ul>';
        
        } //end subnav collections
            
            
        ?>

        <!----End Subnav Here------>
		</div>

		<!-- BOTTOM CONTAINER-->
		<div id="aboutUser">
			
        <!--------------------------Portfolio View---------------------------->
        <?php
        if($view == '') {
        
        if($option == '') {        
        $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$useremail' ORDER BY id DESC LIMIT 0,16");
        $numresults = mysql_num_rows($query);
        }
        
        elseif($option == 'top') {
        $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$useremail' AND views > 20 ORDER BY (points/votes) DESC LIMIT 0,16");
        $numresults = mysql_num_rows($query);
        }
                
        elseif($option == 'fave') {
        $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$useremail' ORDER BY faves DESC LIMIT 0,16");
        $numresults = mysql_num_rows($query);
        }
        
        if($searchword) {
         $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$useremail' AND concat(tag1,tag,2,tag3,tag4,singlestyletags,singlecategorytags,caption) LIKE '%$searchword%' ORDER BY id DESC LIMIT 0,16");
        $numresults = mysql_num_rows($query);
        }

    echo'
    <div id="thepics" style="position:relative;left:-25px;top:10px;width:1185px;">
    <div id="main">
    <ul id="tiles">';
        
        for($iii=0; $iii < $numresults; $iii++) {
              
                $image = mysql_result($query, $iii, "source");
                $imageThumb = str_replace("userphotos/","userphotos/medthumbs/", $image);
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
                $caption = (strlen($caption) > 25) ? substr($caption,0,23). "&#8230;" : $caption;
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
                list($width, $height) = getimagesize($image);
                $imgratio = $height / $width;
                $heightls = $height / 3.2;
                $widthls = $width / 3.2;
                
                list($width, $height) = getimagesize($image);
                $imgratio = $height / $width;
                $heightls = $height / 3.3;
                $widthls = $width / 3.3;
                if($widthls < 235) {
                    $heightls = $heightls * ($heightls/$widthls);
                    $widthls = 280;
                }
                
            //Ajax Faves
            $favecheck = mysql_query("SELECT id FROM favorites WHERE emailaddress = '$email' AND imageid = '$id'"); 
            $favematch = mysql_result($favecheck,0,'id');

    if($option == 'fave') {

        echo '
        <a style="text-decoration:none;color:#333;" href="fullsizeview.php?imageid=',$id,'&v=n"><li class="fPic" id="',$faves,'" style="list-style-type: none;width:280px;">
    
    <div id="outer">
        <img onmousedown="return false" oncontextmenu="return false;" style="min-width:280px;" src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />
        
        <div class="galleryOverlay" id="tooltip">
            <a style="color:#fff;" href="fullsizemarket.php?imageid=',$id,'">
            <img style="width:18px;padding:10px 6px;" src="graphics/whitecart2.png" />
            <span style="font-weight:300!important;font-size:13px;"><span style="font-weight:500;font-size:14px;">',$price,'</span> Download</span>
            </a>';
    
    if($email) {
        if(!$favematch) {
            echo'
            <a style="color:#fff;cursor:pointer;" onclick="ajaxFunction(\'',$image,'\')" id="ajaxFave',$image,'">
            <i style="margin-top:2px;margin-left:10px;" class="icon-heart icon-white"></i>
            <span style="font-weight:300!important;font-size:13px;"> Favorite </span>
            </a>';
        }
        elseif($favematch) {
            echo'
            <i style="margin-top:2px;margin-left:10px;" class="icon-ok icon-white"></i>
            <span style="font-weight:300!important;font-size:13px;"> Favorited </span>';
        }
    }
        
        echo'
        </div>
    </div>
    
            <div class="statoverlay" style="z-index:1;background-color:white;position:relative;top:0px;width:280px;height:30px;">
                <div style="line-spacing:1.48;padding:5px;color:#4A4A4A;">
                    <div style="float:left;padding-top:10px;">
                        <span style="font-size:15px;font-weight:500;">',$score,'</span>&nbsp;&nbsp;<span style="font-weight:300;font-size:15px;">',$caption,'</span>
                    </div>
                </div>
            </div>'; 
        }
        
        elseif($option == 'top') {
           
             echo '
        <a style="text-decoration:none;color:#333;" href="fullsizeview.php?imageid=',$id,'&v=n"><li class="fPic" id="',$score,'" style="list-style-type: none;width:280px;">
    
    <div id="outer">
        <img onmousedown="return false" oncontextmenu="return false;" style="min-width:280px;" src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />
        
        <div class="galleryOverlay" id="tooltip">
            <a style="color:#fff;" href="fullsizemarket.php?imageid=',$id,'">
            <img style="width:18px;padding:10px 6px;" src="graphics/whitecart2.png" />
            <span style="font-weight:300!important;font-size:13px;"><span style="font-weight:500;font-size:14px;">',$price,'</span> Download</span>
            </a>';
    
    if($email) {
        if(!$favematch) {
            echo'
            <a style="color:#fff;cursor:pointer;" onclick="ajaxFunction(\'',$image,'\')" id="ajaxFave',$image,'">
            <i style="margin-top:2px;margin-left:10px;" class="icon-heart icon-white"></i>
            <span style="font-weight:300!important;font-size:13px;"> Favorite </span>
            </a>';
        }
        elseif($favematch) {
            echo'
            <i style="margin-top:2px;margin-left:10px;" class="icon-ok icon-white"></i>
            <span style="font-weight:300!important;font-size:13px;"> Favorited </span>';
        }
    }
        
        echo'
        </div>
    </div>
    
            <div class="statoverlay" style="z-index:1;background-color:white;position:relative;top:0px;width:280px;height:30px;">
                <div style="line-spacing:1.48;padding:5px;color:#4A4A4A;">
                    <div style="float:left;padding-top:10px;">
                        <span style="font-size:15px;font-weight:500;">',$score,'</span>&nbsp;&nbsp;<span style="font-weight:300;font-size:15px;">',$caption,'</span>
                    </div>
                </div>
            </div>'; 
             
        }
        
        else{
             
              echo '
        <a style="text-decoration:none;color:#333;" href="fullsizeview.php?imageid=',$id,'&v=n"><li class="fPic" id="',$id,'" style="list-style-type: none;width:280px;">
    
    <div id="outer">
        <img onmousedown="return false" oncontextmenu="return false;" style="min-width:280px;" src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />
        
        <div class="galleryOverlay" id="tooltip">
            <a style="color:#fff;" href="fullsizemarket.php?imageid=',$id,'">
            <img style="width:18px;padding:10px 6px;" src="graphics/whitecart2.png" />
            <span style="font-weight:300!important;font-size:13px;"><span style="font-weight:500;font-size:14px;">',$price,'</span> Download</span>
            </a>';
    
    if($email) {
        if(!$favematch) {
            echo'
            <a style="color:#fff;cursor:pointer;" onclick="ajaxFunction(\'',$image,'\')" id="ajaxFave',$image,'">
            <i style="margin-top:2px;margin-left:10px;" class="icon-heart icon-white"></i>
            <span style="font-weight:300!important;font-size:13px;"> Favorite </span>
            </a>';
        }
        elseif($favematch) {
            echo'
            <i style="margin-top:2px;margin-left:10px;" class="icon-ok icon-white"></i>
            <span style="font-weight:300!important;font-size:13px;"> Favorited </span>';
        }
    }
        
        echo'
        </div>
    </div>
    
            <div class="statoverlay" style="z-index:1;background-color:white;position:relative;top:0px;width:280px;height:30px;">
                <div style="line-spacing:1.48;padding:5px;color:#4A4A4A;">
                    <div style="float:left;padding-top:10px;">
                        <span style="font-size:15px;font-weight:500;">',$score,'</span>&nbsp;&nbsp;<span style="font-weight:300;font-size:15px;">',$caption,'</span>
                    </div>
                </div>
            </div>'; 
            
        }
            
      } //end for loop
        
    echo'
        </ul>';
        
?>

<!-- Once the page is loaded, initalize the plug-in. -->
  <script type="text/javascript">
    $(document).ready(new function() {
      // Prepare layout options.
      var options = {
        autoResize: true, // This will auto-update the layout when the browser window is resized.
        container: $('#main'), // Optional, used for some extra CSS styling
        offset: 10, // Optional, the distance between grid items
        itemWidth: 280 // Optional, the width of a grid item
      };
      
      // Get a reference to your grid items.
      var handler = $('#tiles li');
      
      // Call the layout function.
      handler.wookmark(options);
      
    });
  </script>

</div>
</div>
    
<?php      
        
   //AJAX CODE HERE
   echo'
   <div class="grid_6 push_11" style="padding-top:25px;padding-bottom:25px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;"><img style="width:50px;" src="graphics/LoadingGIF.gif" /></div>
   </div>';


echo '<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMorePics").show();
				$.ajax({
					url: "loadMorePortfolioPicsVP3.php?lastPicture=" + $(".fPic:last").attr("id")+"&option=',$option,'"+"&emailaddress=',$useremail,'"+"&searchword=',$searchword,'",
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
    
    echo'</div>';
    echo'</div>';
        
        }  //end view == '' (portfolio)
  
        
    /*--------------------------Collections View----------------------------*/
        if($view == 'collections') {
         
            echo'
            <!--LEFT COLUMN-->
            <div id="response">
        
            </div>';
        }
        
    ?>

		</div>

	<!--END CONTENT IN CONTAINER-->
	</div>
<!--END CONATINER-->



	</div>
</div>
<!--END MAIN-->


</body>
<!--JAVASCRIPT-->


<script type="text/javascript" src="js/bootstrap.js"></script>
			

<script type="text/javascript">  
        $(document).ready(function () {  
            $('.dropdown-toggle').dropdown();  
        });  
   </script> 

<script type="text/javascript">
(function(){
	var portfolio = $('#subNavList1'),
		exhibit = $('#subNavList2'),
		PList = $("#PList"),
		EList = $("#EList"),
		count = 1,
		count1 = 0;

	PList.on('click', function () {
		if (count === 1){
			portfolio.animate({'width' : 0});
			count -= 1;
			document.getElementById('A1').src="graphics/arrowRight_w.png" ;
		 } else {
		 	portfolio.animate({'width' : 600});
		 	exhibit.animate({'width' : 0});
		 	count += 1;
		 	document.getElementById('A1').src="graphics/arrowLeft_w.png" ;
		 	if (count1 === 1){
		 		count1 -= 1;
		 		document.getElementById('A2').src="graphics/arrowRight_w.png" ;
		 	}
		 }
			
		
		
	});

	EList.on('click', function () {
		if (count1 === 1){
			exhibit.animate({'width' : 0});
			count1 -= 1;
			document.getElementById('A2').src="graphics/arrowRight_w.png" ;

		 } else {
		 	exhibit.animate({'width' : 600});
		 	portfolio.animate({'width' : 0});
		 	count1 += 1;
		 	document.getElementById('A2').src="graphics/arrowLeft_w.png" ;
		 	if (count === 1){count -= 1;document.getElementById('A1').src="graphics/arrowRight_w.png" ;}
		}
	});


})();

//Load in collections views on right side
$(document).ready(function(){
	// load index page when the page loads
	$("#response").load("loadCollection.php?u=<?php echo $userid; ?>");
	$("#home").click(function(){
	// load home page on click
		$("#response").load("home.html");
	});
});

</script>
<script type="text/javascript">
(function(){
	var count = 0;

 $('#menuBtn').on('click', function() {

 	if(count === 0 ){ 
 	$('#left_bar').animate({ 'width' : 0});
 	count += 1;
 	$('#main_container').animate({ 'width' : 1280});
 	$('.center').animate({'padding-left' : 19});
} else {$('#left_bar').animate({ 'width' : 65});
 	count -= 1;
 	$('#main_container').animate({ 'width' : 1162 });
 	$('.center').animate({'padding-left' : 45});
 }

 });

})();
</script>
</html>