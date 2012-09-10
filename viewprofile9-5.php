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

    $email = $_SESSION['email'];
    $currenttime = time();
    
//QUERY FOR NOTIFICATIONS
$currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$email'";
$currentnotsquery = mysql_query($currentnots);
$currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");

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
  
  
  //GRAB USER INFORMATION
  $userid = htmlentities($_GET['u']);
  $userquery = mysql_query("SELECT * FROM userinfo WHERE user_id = '$userid'");
  $profilepic = mysql_result($userquery,0,'profilepic'); 
  $useremail = mysql_result($userquery,0,'emailaddress');
  $firstname = mysql_result($userquery,0,'firstname');
  $fullname = mysql_result($userquery,0,'firstname')." ".mysql_result($userquery,0,'lastname'); 
  $age = mysql_result($userquery,0,'age');
  $gender = mysql_result($userquery,0,'gender');
  $location = mysql_result($userquery,0,'location');
  $camera = mysql_result($userquery,0,'camera');
  $about = mysql_result($userquery,0,'about');
  $quote = mysql_result($userquery,0,'quote');
  $fbook = mysql_result($userquery,0,'fbook');
  $twitter = mysql_result($userquery,0,'twitter');
  $faves = mysql_result($userquery,0,'faves');
  $reputation = number_format(mysql_result($userquery,0,'reputation'),1);
  $profileviews = mysql_result($userquery,0,'profileviews');
    
  //ADD PAGEVIEW TO THEIR PROFILE
  $profileviewquery = mysql_query("UPDATE userinfo SET profileviews = (profileviews + 1) WHERE user_id = '$userid'");

  //GET VIEW
  $view = htmlentities($_GET['view']);
  
    //PORTFOLIO RANKING

    $followersquery="SELECT * FROM userinfo WHERE following LIKE '%$useremail%'";
	$followersresult=mysql_query($followersquery);
	$numberfollowers = mysql_num_rows($followersresult);
    
    //Grab Overall Portfolio Ranking
    $userphotos="SELECT * FROM photos WHERE emailaddress = '$useremail'";
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
    
    //NUMBER FOLLOWING
    $emailquery=("SELECT following FROM userinfo WHERE emailaddress ='$useremail'");
	$followresult=mysql_query($emailquery);
	$followinglist=mysql_result($followresult, 0, "following");
	$followingquery="SELECT * FROM userinfo WHERE emailaddress IN ($followinglist)";
	$followingresult = mysql_query($followingquery);
	$numberfollowing = mysql_num_rows($followingresult);


if(isset($_GET['view'])) {
	$view=htmlentities($_GET['view']); //get which tab of profile they are looking at
}

 //FOLLOWING QUERIES
$follow;
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
        		$message = 'You have a new follower on PhotoRankr! Visit their photography here: http://photorankr.com/viewprofile.php?u='.$sessionuserid;
        		$headers = 'From:PhotoRankr <photorankr@photorankr.com>';
                if($foundsetting > 0) {
        		mail($to, $subject, $message, $headers);   
                }
		}
	}
}

        if($_GET['action'] == 'comment') {
    
            $blogid = htmlentities($_GET['blogid']);
            $comment = mysql_real_escape_string($_POST['comment']);
                    
            $commentinsertion = mysql_query("INSERT INTO blogcomments (comment,blogid,emailaddress) VALUES ('$comment','$blogid','$email')");
            
            $type = 'blogcomment';
            $blogcommentnewsfeed = mysql_query("INSERT INTO newsfeed (emailaddress,type,source,owner,time) VALUES ('$email','$type','$blogid','$useremail','$currenttime')");
            
            //notifications query     
            $notsquery = "UPDATE userinfo SET notifications = (notifications + 1) WHERE emailaddress = '$useremail'";
            $notsqueryrun = mysql_query($notsquery); 

            
            echo '<META HTTP-EQUIV="Refresh" Content="0; URL=viewprofile.php?u=',$userid,'&view=blog#',$blogid,'">';
            exit();

    
        }


//Grab OWNERS reputation score
    
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
    

    
   if($ranking2 > 145) {
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


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

 <meta property="og:image" content="http://photorankr.com/<?php echo $profilepic; ?>">
   <title><?php echo $fullname; ?> | PhotoRankr</title>
   <meta name="Generator" content="EditPlus">
  <meta name="Author" content="PhotoRankr, PhotoRankr.com">
  <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
  <meta name="Description" content="PhotoRankr allows photographers of all skill levels to sell and share their work. Create your photostream cutomized to what you want to see. Add photos to your favorites, rank them, and watch them trend. Build your portfolio with Photorankr.">

  <link rel="stylesheet" type="text/css" href="css/bootstrapNew.css" />
    <link rel="stylesheet" href="960_24.css" type="text/css" />
        <link rel="stylesheet" href="css/style.css" type="text/css" />
  <link rel="stylesheet" href="text2.css" type="text/css" />

  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
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
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
  
</script>


</head>
<body style="overflow-x:hidden;min-width:1220px;">

<?php navbarnew(); ?>  
<div class="container_24"><!--START CONTAINER-->


<!--Following Modal-->
<div class="modal hide fade" id="fwmodal" style="overflow:hidden;border:5px solid rgba(102,102,102,.8);">
      
<?php
if($_SESSION['loggedin'] !== 1) {

echo'
<div class="modal-header" style="background-color:#111;color:#fff;">
<a style="float:right" class="btn btn-success" data-dismiss="modal">Close</a>
<img style="margin-top:-2px;" src="graphics/aperture_white.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">Please log in to follow ',$fullname,'</span>
  </div>
  
<div modal-body" style="width:450px;height:145px;">

<div id="content" style="font-size:16px;width:560px;font-family:helvetica,arial;font-weight:100;background-color:rgb(245,245,245);">
		
<img class="roundedall" style="margin-left:20px;margin-top:20px;" src="',$profilepic,'" 
height="100px" width="100px" />

<div style="width:350px;margin-left:140px;margin-top:-85px;line-height:1.48;">
',$firstname,' ',$lastname,'<br />                 

',$numphotos,' photos <br />

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
        if($email == $useremail) {
       echo'
<div class="modal-header" style="background-color:#111;color:#fff;">
<a style="float:right" class="btn btn-success" data-dismiss="modal">Close</a>
<img style="margin-top:-2px;" src="graphics/aperture_white.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">Oops, you accidentally tried to follow yourself.</span>
  </div>

<div modal-body" style="width:450px;height:145px;">

<div id="content" style="font-size:16px;width:560px;font-family:helvetica,arial;font-weight:100;background-color:rgb(245,245,245);">
		
<img class="roundedall" style="margin-left:20px;margin-top:20px;" src="',$profilepic,'" 
height="100px" width="100px" />

<div style="width:350px;margin-left:140px;margin-top:-85px;line-height:1.48;">
',$firstname,' ',$lastname,'<br />                 

',$numphotos,' photos <br />

Avg. Portfolio: ',$portfolioranking,' <br /><br /><br />

</div>
</div>';


        }
        
        
        else {
		//MAKE SURE FOLLOWER ISN'T ADDED TWICE
		$search_string=$prevemails;
		$regex="/$useremail/";
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
',$firstname,' ',$lastname,'<br />                 

',$numphotos,' photos <br />

Avg. Portfolio: ',$portfolioranking,' <br /><br /><br />

</div>
</div>';
		} 

else {
            
			echo'
<div class="modal-header" style="background-color:#111;color:#fff;">
<a style="float:right" class="btn btn-success" href="viewprofile.php?u=', $userid,'&fw=1">Close</a>
<img style="margin-top:-2px;" src="graphics/aperture_white.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">You are now following ',$firstname,' ',$lastname,'</span>
  </div>

<div modal-body" style="width:450px;height:145px;">

<div id="content" style="font-size:16px;width:560px;font-family:helvetica,arial;font-weight:100;background-color:rgb(245,245,245);">
		
<img class="roundedall" style="margin-left:20px;margin-top:20px;" src="',$profilepic,'" 
height="100px" width="100px" />

<div style="width:350px;margin-left:140px;margin-top:-85px;line-height:1.48;">
',$firstname,' ',$lastname,'<br />                 

',$numphotos,' photos <br />

Avg. Portfolio: ',$portfolioranking,' <br /><br /><br />

</div>
</div>';
            
  }
    }
} 
        
        
        
?>

</div>
</div>


<!--LEFT SIDEBAR-->
<div class="grid_24" style="width:1120px;">
<div class="grid_4 pull_2 rounded" style="background-color:#eeeff3;position:relative;top:80px;width:250px;margin-left:10px;">

<div style="width:240px;height:140px;">
<div class="roundedall" style="float:left;overflow:hidden;margin-left:15px;margin-top:15px;">
<img src="<?php echo $profilepic; ?>" alt="<?php echo $fullname; ?>" height="120" width="120"/>
</div>
<a data-toggle="modal" href="#fwmodal" data-backdrop="static" class="btn btn-success" style="float:left;width:70px;margin-top:40px;margin-left:10px;font-size:14px;font-weight:150;">Follow</a>
<a class="btn btn-primary" style="float:left;width:70px;margin-top:7px;margin-left:10px;font-size:14px;font-weight:150;" href="viewprofile.php?u=<?php echo$userid; ?>&view=promote">Promote</a>
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
<hr style="font-size:50px;">
<a style="text-decoration:none;color:black;font-weight:100;" href="viewprofile.php?u=<?php echo $userid; ?>&view=about"><div style="width:250px;margin-top:-10px;padding-bottom:4px;">
<span class="green" style="text-align:center;font-size:24px;padding-left:15px;<?php if($view == 'about') {echo'color:#6aae45;';} ?>">About&nbsp;&nbsp;<img style="float:right;padding-top:5px;padding-right:20px;" src="graphics/info.png" height="30" width="30"></span>
</div></a>

<hr>
<a style="text-decoration:none;color:black;font-weight:100;" href="viewprofile.php?u=<?php echo $userid; ?>&view=network"><div style="width:250px;margin-top:-10px;padding-bottom:4px;">
<span class="green" style="text-align:center;font-size:24px;padding:15px;<?php if($view == 'network') {echo'color:#6aae45;';} ?>">Network&nbsp;&nbsp;<img style="float:right;padding-top:5px;padding-right:20px;" src="graphics/follower.png" height="30" width="50"></span>
</div></a>

<hr>
<a style="text-decoration:none;color:black;font-weight:100;" href="viewprofile.php?u=<?php echo $userid; ?>&view=favorites"><div style="width:250px;margin-top:-10px;padding-bottom:4px;">
<span class="green" style="text-align:center;font-size:24px;padding:15px;<?php if($view == 'favorites') {echo'color:#6aae45;';} ?>">Favorites&nbsp;&nbsp;<img style="float:right;padding-top:5px;padding-right:20px;" src="graphics/fave.png" height="30" width="30"></span>
</div></a>

<hr>
<a style="text-decoration:none;color:black;font-weight:100;" href="viewprofile.php?u=<?php echo $userid; ?>&view=contact"><div style="width:250px;margin-top:-10px;padding-bottom:4px;">
<span class="green" style="text-align:center;font-size:24px;padding:15px;<?php if($view == 'contact') {echo'color:#6aae45;';} ?>">Contact&nbsp;&nbsp;<img style="float:right;padding-top:5px;padding-right:20px;"src="graphics/contact.png" height="30" width="30"></span>
</div></a>
</div>

</div><!--end 4 grid-->

<div class="grid_18 roundedright pull_1" style="background-color:#eeeff3;height:50px;margin-top:80px;width:800px;margin-left:-43px;">

<a style="text-decoration:none;color:black;" href="viewprofile.php?u=<?php echo $userid; ?>"><div class="clicked" style="width:150px;height:50px;border-right:1px solid #ccc;border-left:1px solid #ccc;float:left;<?php if($view == '') {echo'background-color:#bbb;color:white;';} ?>"><div style="font-size:18px;font-weight:100;margin-top:10px;text-align:center;">Activity</div></div></a>

<a style="text-decoration:none;color:black;" href="viewprofile.php?u=<?php echo $userid; ?>&view=portfolio"><div class="clicked" style="width:150px;height:50px;border-right:1px solid #ccc;border-left:1px solid #ccc;float:left;<?php if($view == 'portfolio') {echo'background-color:#bbb;color:white;';} ?>"><div style="font-size:18px;font-weight:100;margin-top:10px;text-align:center;">Portfolio</div></div></a>

<a style="text-decoration:none;color:black;" href="viewprofile.php?u=<?php echo $userid; ?>&view=store"><div class="clicked" style="width:150px;height:50px;border-right:1px solid #ccc;float:left;<?php if($view == 'store') {echo'background-color:#bbb;color:white;';} ?>"><div style="font-size:18px;font-weight:100;margin-top:10px;text-align:center;">Store</div></div></a>

<a style="text-decoration:none;color:black;" href="viewprofile.php?u=<?php echo $userid; ?>&view=blog"><div class="clicked" style="width:150px;height:50px;border-right:1px solid #ccc;float:left;<?php if($view == 'blog') {echo'background-color:#bbb;color:white;';} ?>"><div style="font-size:18px;font-weight:100;margin-top:10px;text-align:center;">Blog</div></div></a>

<div style="width:150px;height:50px;float:left;"><div style="font-size:18px;font-weight:100;margin-top:6px;text-align:center;">
<form class="navbar-search" action="viewprofile.php?u=<?php echo $userid; ?>&view=search" method="post">
<input class="search" style="position:relative;margin-left:15px;margin-top:0px;" name="searchterm" type="text" placeholder="Search Portfolio&#8230">
</form></div></div>

<?php

     if($view == '') {
    
        $activityquery = mysql_query("SELECT * FROM newsfeed WHERE hide <> 1 AND (emailaddress = '$useremail' OR owner = '$useremail') AND type IN ('follow','comment','fave','photo') ORDER BY id DESC LIMIT 13");
        
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
            $newwidth = $width/2.2;
            $newheight = $height/2.2;
            
            if($newwidth < 295) {
                $newheight = $newheight * ($newheight/$newwidth);
                $newwidth = 340;
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
                    
                   echo'<li class="fPic" id="',$id,'" style="padding:5px;margin-top:10px;list-style-type: none;width:340px;
">

                    <div style="width:100%;"><div style="float:left;height:60px;"><img style="max-height:40px;" src="',$commenterpic,'" /></div>&nbsp;&nbsp;<div style="float:left;padding-left:8px;width:280px;"><img src="graphics/upload.png" width="25" />&nbsp;&nbsp;',$commentername,' uploaded "',$caption,'"
                    
                    <div style="color:#555;font-weight:500;margin-left:0px;">';if($time > 0) {echo'',converttime($time),'';} echo'</div> 

                    </div>
                    <hr /></div>
                    
                    <a href="fullsize.php?imageid=',$sourceid,'"><img src="',$source,'" width="',$newwidth,'px" height="',$newheight,'px" /></a>
                    </li>';
               
                }
                
                elseif($type == 'follow') {
                
                        
                
                      echo'<li class="fPic" id="',$id,'" style="padding:5px;margin-top:10px;list-style-type: none;width:340px;
">

                     <div style="width:100%;"><div style="float:left;height:60px;"><img style="max-height:40px;" src="',$profilepic,'" /></div>&nbsp;&nbsp;<div style="float:left;padding-left:8px;width:280px;"><img src="graphics/follower.png" width="35" />&nbsp;&nbsp;<a href="viewprofile.php?u=',$cnid,'">',$firstname,' ',$lastname,'</a> followed <a href="viewprofile.php?u=',$ownerid,'">',$followername,'</a>
                     
                    <div style="color:#555;font-weight:500;">';if($time > 0) {echo'',converttime($time),'';} echo'</div>

                     </div>
                     <hr /></div>
                     
                     <div><a href="viewprofile.php?u=',$ownerid,'"><img style="float:left;max-height:100px;" src="',$followpic,'" /></a>
                     
                     <div style="float:left;width:220px;height:90px;font-size:18px;margin-left:10px;margin-top:40px;"><i><div style="text-align:center;">',$followername,'</div></i></div>
                     </div>
                     
                     <div style="width:360px;">';
                    if($numprofilepics > 3){echo'<img style="padding:3px;" src="',$profileimage,'" height="160" width="160" /><img style="padding:3px;" src="',$profileimage2,'" height="160" width="160" /><img style="padding:3px;" src="',$profileimage3,'" height="160" width="160" /><img style="padding:3px;" src="',$profileimage4,'" height="160" width="160" />';}
                    echo'</div>
                     
                     </li>
                     <br />';
                    
                }
                
                elseif($type == 'comment') {
                    
                     echo'<li class="fPic" id="',$id,'" style="padding:5px;margin-top:10px;list-style-type: none;width:340px;
">

                    <div style="width:100%;"><div style="float:left;height:60px;"><img style="max-height:40px;" src="',$commenterpic,'" /></div>&nbsp;&nbsp;<div style="float:left;padding-left:8px;width:270px;"><img src="graphics/comment.png" width="25" />&nbsp;&nbsp;<a href="viewprofile.php?u=',$commenterid,'">',$commentername,'</a> commented on <a href="viewprofile.php?u=',$cnid,'">',$cn,'\'s</a> photo
                    
                    <div style="color:#555;font-weight:500;">';if($time > 0) {echo'',converttime($time),'';} echo'</div>

                    </div>
                    <hr /></div>
                    
                    <a href="fullsize.php?imageid=',$sourceid,'">
                    
                    <img src="',$source,'" width="',$newwidth,'px" height="',$newheight,'px" />                    
                    </a>';
                    
                    if($comment) {
                    echo'
                    <div style="font-size:15px;width:320px;padding:10px;margin-top:20px;">"',$comment,'"</div>';
                    }
                    
                    echo'
                    </li>
                    <br />';
                
                
                }
                
                elseif($type == "blogcomment") {
                
                    
                
                }
                
                elseif($type == "fave") {
                
                    echo'<li class="fPic" id="',$id,'" style="padding:5px;margin-top:10px;list-style-type: none;width:340px;
">
                        <div style="width:100%;"><div style="float:left;height:60px;"><img style="max-height:40px;" src="',$commenterpic,'" /></div>&nbsp;&nbsp;
                        <div style="float:left;padding-left:8px;width:280px;"><img src="graphics/fave.png" width="25" />&nbsp;&nbsp;<a href="viewprofile.php?u=',$commenterid,'">',$commentername,'</a> favorited <a href="viewprofile.php?u=',$cnid,'">',$cn,'\'s </a> photo
                    
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
        itemWidth: 360 // Optional, the width of a grid item
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
					url: "loadMoreViewActivity.php?lastPicture=" + $(".fPic:last").attr("id")+"&user=',$useremail,'",
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

    elseif($view == 'portfolio') {
    
        $option = htmlentities($_GET['option']);    
    
        echo'<br /><br /><br /><br /><div style="width:760px;text-align:center;font-size:14px;font-weight:200;"><div style="margin-left:20px;"><a class="green" style="text-decoration:none;color:#000;';if($option == '') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="viewprofile.php?u=',$userid,'&view=portfolio">Newest</a> | <a class="green" style="text-decoration:none;color:#000;';if($option == 'top') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="viewprofile.php?u=',$userid,'&view=portfolio&option=top">Top Ranked</a> | <a class="green" style="text-decoration:none;color:#000;';if($option == 'fave') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="viewprofile.php?u=',$userid,'&view=portfolio&option=fave">Most Favorited</a> | <a class="green" style="text-decoration:none;color:#000;" href="viewprofile.php?u=',$userid,'&view=exhibits">Exhibits</a></div></div>';
        
        if($option == '') {        
        $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$useremail' ORDER BY id DESC LIMIT 0,21");
        $numresults = mysql_num_rows($query);
        }
        
        elseif($option == 'top') {
        $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$useremail' AND views > 20 ORDER BY (points/votes) DESC LIMIT 0,21");
        $numresults = mysql_num_rows($query);
        }
                
        elseif($option == 'fave') {
        $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$useremail' ORDER BY faves DESC LIMIT 0,21");
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
               
                if($widthls < 205) {
                    $heightls = $heightls * ($heightls/$widthls);
                    $widthls = 250;
                }

                echo '   

        <a style="text-decoration:none;" href="fullsizeview.php?imageid=',$id,'"><li class="fPic" id="',$id,'" style="padding:5px;margin-right:10px;margin-top:10px;list-style-type: none;width:240px;
"><img onmousedown="return false" oncontextmenu="return false;" src="http://photorankr.com/',$imageThumb[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /><p><span style="font-size:16px;">',$score,'</span>/10&nbsp;&nbsp;',$caption,'<br /><i class="icon-heart"></i>&nbsp;',$faves,' favorites</p></li></a>';
	    
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
   <div id="loadMorePics" class="grid_24" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;">Loading More Photos&hellip;</div>
   </div>';


echo '<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMorePortfolioPicsVP").show();
				$.ajax({
					url: "../loadMorePortfolioPicsVP3.php?lastPicture=" + $(".fPic:last").attr("id"),
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
</script>

</div>
</div>';

}


elseif($view == 'exhibits') {
    
 echo'<br /><br /><br /><br /><div style="width:760px;text-align:center;font-size:14px;font-weight:200;"><div style="margin-left:20px;"><a class="green" style="text-decoration:none;color:#000;" href="viewprofile.php?u=',$userid,'&view=portfolio">Newest</a> | <a class="green" style="text-decoration:none;color:#000;" href="viewprofile.php?u=',$userid,'&view=portfolio&option=top">Top Ranked</a> | <a class="green" style="text-decoration:none;color:#000;" href="viewprofile.php?u=',$userid,'&view=portfolio&option=fave">Most Favorited</a> | <a class="green" style="text-decoration:none;color:#000;';if($view == 'exhibits') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="viewprofile.php?u=',$userid,'&view=exhibits">Exhibits</a></div></div>';


        if(isset($_GET['set'])){
		$set = mysql_real_escape_string($_GET['set']);
	}

//select all exhibits of user
$allsetsquery = "SELECT * FROM sets WHERE owner = '$useremail'";
$allsetsrun = mysql_query($allsetsquery);
$numbersets = mysql_num_rows($allsetsrun);
echo'<div style="margin-top:0px">';


if($set == '' & $numbersets > 0) {

for($iii=0; $iii < $numbersets; $iii++) {
$setname[$iii] = mysql_result($allsetsrun, $iii, "title");
$setcover = mysql_result($allsetsrun, $iii, "cover");
$set_id[$iii] = mysql_result($allsetsrun, $iii, "id");
$setname2[$iii] = (strlen($setname[$iii]) > 30) ? substr($setname[$iii],0,27). " &#8230;" : $setname[$iii];
if($setcover == '') {
$setcover = "profilepics/nocoverphoto.png";
}
        list($width, $height) = getimagesize($setcover);
        $imgratio = $height / $width;
        $heightls = $height / 3.5;
        $widthls = $width / 3.5;
        
//grab all photos in the exhibit
$grabphotos = "SELECT * FROM photos WHERE emailaddress = '$useremail' AND set_id = '$set_id[$iii]'";
$grabphotosrun = mysql_query($grabphotos);
$numphotosgrabbed = mysql_num_rows($grabphotosrun);


    echo'<div style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a style="text-decoration:none;" href="viewprofile.php?u=',$userid,'&view=exhibits&set=',$set_id[$iii],'">

    <div class="statoverlay" style="z-index:1;left:0px;top:190px;position:relative;background-color:black;width:245px;height:70px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">',$setname2[$iii],'</span><br><span style="font-size:14px;font-family:helvetica,arial;font-weight:100;">Number Photos: ',$numphotosgrabbed,'<br></span></p></div>

    <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:265px;min-width:245px;" src="http://www.photorankr.com/',$setcover,'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
    
} //end of set == '' view
echo'</div>';

} //end of set == '' view


elseif($set != '') {
//increment exhibit view count
$updateexviews = mysql_query("UPDATE sets SET views = (views+1) WHERE id = '$set'"); 

//get exhibit mode
if(isset($_GET['mode'])){
		$mode = ($_GET['mode']);
	}
if($mode == '') {
//grab all photos in the exhibit
$grabphotos = "SELECT * FROM photos WHERE emailaddress = '$useremail' AND set_id = '$set'";
$grabphotosrun = mysql_query($grabphotos);
$numphotosgrabbed = mysql_num_rows($grabphotosrun);

//grab about this set
$aboutset = "SELECT * FROM sets WHERE owner = '$useremail' AND id = '$set' LIMIT 0,1";
$aboutsetrun = mysql_query($aboutset);
$aboutarray = mysql_fetch_array($aboutsetrun);
$aboutset = $aboutarray['about'];
$settitle = $aboutarray['title'];
$setcover = $aboutarray['cover'];
if($setcover == '') {
$setcover = 'profilepics/nocoverphoto.png';
}

echo'<div class="grid_18" style="width:770px;margin-top:0px;margin-left:-10px;padding:35px;">

<div class="grid_14 well" style="width:735px;line-height:25px;"><span style="font-size:25px;font-family:helvetica,arial;font-weight:200;">',$settitle,'</span><br /><br />';
if($aboutset) {echo'
    <span style="font-size:16px;font-family:helvetica,arial;font-weight:200;">',        $aboutset,'</span>';
}
echo'</div>';

for($iii=0; $iii < $numphotosgrabbed; $iii++) {
    $insetname[$iii] = mysql_result($grabphotosrun, $iii, "caption");
    $insetsource[$iii] = mysql_result($grabphotosrun, $iii, "source");
    $newsource = str_replace("userphotos/","userphotos/medthumbs/", $insetsource[$iii]);
    $caption = mysql_result($grabphotosrun, $iii, "caption");
    $faves = mysql_result($grabphotosrun, $iii, "faves");
    $points = mysql_result($grabphotosrun, $iii, "points");
    $votes = mysql_result($grabphotosrun, $iii, "votes");
    $score = number_format(($points/$votes),2);
    
            list($width, $height) = getimagesize($insetsource[$iii]);
            $imgratio = $height / $width;
            $heightls = $height / 3.5;
            $widthls = $width / 3.5;
                
    echo'<div style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a style="text-decoration:none;" href="fullsizeview.php?image=',$insetsource[$iii],'">

    <div class="statoverlay" style="z-index:1;left:0px;top:180px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">',$caption,'</span><br><span style="font-size:14px;font-family:helvetica,arial;font-weight:100;">Score: ',$score,'<br>Favorites: ',$faves,'</span></p></div>

    <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:265px;min-width:245px;" src="',$newsource,'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
 
    } //end for loop

    echo'</div>';
    echo'</div>';

   } //end of no exhibit mode
   
   }

   }     
   
   


elseif($view == 'promote'){

    echo'<div class="grid_18" style="width:770px;margin-top:0px;margin-left:-10px;padding:35px;background-color:rgba(245,245,245,0.6);">

    <div class="well" style="font-size:16px;font-family:helvetica neue, gill sans, helvetica;">Help promote ',$fullname,'\'s  photography by sharing it:<br /><br />

    <!--FB-->
    <div class="fb_share">
    <a name="fb_share" type="box_count" share_url="http://photorankr.com/viewprofile.php?u=',$userid,'"
      href="http://www.facebook.com/sharer.php"></a>
    <script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>
</div>


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


    
elseif($view == 'about') {
        
        echo'
        <div class="span9" style="margin-top:30px;">
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
        </div>';
    
    }
    
    
    
    elseif($view == 'blog') {
    
            $blogquery = mysql_query("SELECT * FROM blog WHERE emailaddress = '$useremail' ORDER BY id DESC");
            $numblogposts = mysql_num_rows($blogquery);
            
            echo'<div class="grid_18" style="margin:auto;border:1px solid #ccc;margin-top:30px;margin-left:45px;">';
            
            if($numblogposts == 0) {
                echo'<div style="font-size:18px;font-weight:200;padding:40px;text-align:center;">',$firstname,' has no blog posts yet&#8230;</a></div>';
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
                                
                                echo'<div><a href="viewprofile.php?u=',$commenterid,'"><img src="',$commenterpic,'" alt="',$commentername,'" height="30" width="30" /><span style="font-weight:bold;color:#3e608c;font-size:12px;padding-left:10px;">',$commentername,'</a></span>&nbsp;&nbsp;',$comment,'</div><hr>';
                            }
                    echo'
                    <form action="viewprofile.php?u=',$userid,'&view=blog&action=comment&blogid=',$id,'" method="POST">
                    <div style="width:620px;"><img style="float:left;padding:10px;" src="',$myprofilepic,'" height="30" width="30" />
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
            echo'</div>';
        }
        
    
    elseif($view == 'store') {
    
            echo'<div id="container" class="grid_18" style="width:770px;margin-top:10px;padding-left:20px;">
            
                <script>
                    function submitPrice(sel) {
                    sel.form.submit();
                }
                    function submitRank(sel) {
                    sel.form.submit();
                }
                    function submitCategory(sel) {
                    sel.form.submit();
                }
                    function submitStyle(sel) {
                    sel.form.submit();
                }
                </script>';
                
                $option = htmlentities($_GET['sort']);
                
            ?>
            
            <div style="font-size:14px;font-weight:200;float:left;padding:22px;">Search By:</div>

<div style="margin-top:15px;">
    <div class="control-group" style="float:left;">
          <!-- Select Basic -->
          <label class="control-label"></label>
          <div class="controls">
           <form action="viewprofile.php?u=<?php echo $userid; ?>&view=store&sort=price" method="post">
            <select name="price"  onchange="submitPrice(this)" class="input-large" style="width:100px;">
              <option>Price</option>
              <option>High - Low</option>
              <option>Low - High</option>
            </select>
            </form>
        </div>
    </div>

    <div class="control-group" style="float:left;">
          <!-- Select Basic -->
          <label class="control-label"></label>
          <div class="controls">
           <form action="viewprofile.php?u=<?php echo $userid; ?>&view=store&sort=rank" method="post">
            <select name="rank"  onchange="submitRank(this)" class="input-large" style="width:100px;margin-left:15px;">
              <option>Rank</option>
              <option>High - Low</option>
              <option>Low - High</option>
            </select>
            </form>
        </div>
    </div>

            
<div class="control-group" style="float:left;">
          <!-- Select Basic -->
          <label class="control-label"></label>
          <div class="controls">
           <form action="viewprofile.php?u=<?php echo $userid; ?>&view=store&sort=category" method="post">
            <select name="category"  onchange="submitCategory(this)" class="input-large" style="width:100px;margin-left:15px;">
            <option>Category</option>
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
            </form>
        </div>
    </div>

    <div class="control-group" style="float:left;">
          <!-- Select Basic -->
          <label class="control-label"></label>
          <div class="controls">
           <form action="viewprofile.php?u=<?php echo $userid; ?>&view=store&sort=style" method="post">
            <select name="style"  onchange="submitStyle(this)" class="input-large" style="width:100px;margin-left:15px;">
            <option>Style</option>
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
            </form>
        </div>
    </div>
</div>

<?php
        
        
        if($option == 'price') {        
            $choice = $_POST['price'];
            
            if($choice == 'High - Low') {
                    $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$useremail' AND price != ('Not For Sale') ORDER BY price DESC");
                    $numresults = mysql_num_rows($query);
            }
          
            if($choice == 'Low - High') {
                    $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$useremail' AND price != ('Not For Sale') ORDER BY price ASC");
                    $numresults = mysql_num_rows($query);
            }
            
        }
        
        elseif($option == 'rank'  || $option == '') {
            $choice = $_POST['rank'];
           
            if($choice == 'High - Low' || $choice == '') {
                    $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$useremail' AND price != ('Not For Sale') ORDER BY (points/votes) DESC");
                    $numresults = mysql_num_rows($query);
            }
          
            if($choice == 'Low - High') {
                    $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$useremail' AND price != ('Not For Sale') ORDER BY (points/votes) ASC");
                    $numresults = mysql_num_rows($query);
            }
            
        }
                
        elseif($option == 'category') {
            $choice = $_POST['category'];
          
                    $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$useremail' AND price != ('Not For Sale') AND (singlecategorytags) LIKE '%$choice%' ORDER BY views DESC");
                    $numresults = mysql_num_rows($query);
            
        }
        
        elseif($option == 'style') {
             $choice = $_POST['style'];
             
                    $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$useremail' AND price != ('Not For Sale') AND (singlestyletags) LIKE '%$choice%' ORDER BY views DESC");
                    $numresults = mysql_num_rows($query);
                    
        }
        
        echo'<div id="thepics" style="position:relative;top:-15px;">';
        echo'<div id="container">';

        for($iii=0; $iii < $numresults; $iii++) {
              
                $image[$iii] = mysql_result($query, $iii, "source");
                $imageThumb[$iii] = str_replace("userphotos/","../userphotos/medthumbs/", $image[$iii]);
                $id = mysql_result($query, $iii, "id");
                $caption = mysql_result($query, $iii, "caption");
                $points = mysql_result($query, $iii, "points");
                $votes = mysql_result($query, $iii, "votes");
                $price = mysql_result($query, $iii, "price");
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

                <div class="fPic" id="',$id,'" style="width:245px;height:230px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a style="text-decoration:none;" href="http://photorankr.com/fullsizemarket.php?imageid=',$id,'">

                <div class="statoverlay" style="z-index:1;left:0px;top:155px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">',$caption,'</span><br><span style="font-size:20px;font-family:helvetica,arial;font-weight:100;">$',$price,'</span></p></div>

                <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:245px;min-width:245px;" src="http://www.photorankr.com/',$imageThumb[$iii],'" alt="',$caption,'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
	    
                } //end for loop      
        
        echo'</div>';
        echo'</div>';

  
    echo'</div>';
    }
    

    elseif($view == 'network') {
    
        $option = htmlentities($_GET['option']);    
    
        echo'<br /><br /><br /><br /><div style="width:760px;text-align:center;font-size:14px;font-weight:200;"><div style="margin-left:20px;"><a class="green" style="text-decoration:none;color:#000;';if($option == '') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="viewprofile.php?u=',$userid,'&view=network">Following</a> | <a class="green" style="text-decoration:none;color:#000;';if($option == 'followers') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="viewprofile.php?u=',$userid,'&view=network&option=followers">Followers</a></div></div>';
        
        if($option == '') {
            $query = mysql_query("SELECT following FROM userinfo WHERE emailaddress = '$useremail'");
            $followinglist = mysql_result($query, 0, "following");
            $followingquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress IN ($followinglist)");
            $numberfollowing = mysql_num_rows($followingquery);
        }
        
        elseif($option == 'followers') {
        $followersquery="SELECT * FROM userinfo WHERE following LIKE '%$useremail%'";
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

                <div style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a style="text-decoration:none;" href="http://photorankr.com/viewprofile.php?u=',$followingid,'">

                <div class="statoverlay" style="z-index:1;left:0px;top:210px;position:relative;background-color:black;width:245px;height:35px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:18px;font-family:helvetica,arial;font-weight:100;">',$fullname,'</span></p></div>

                <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-35px;min-height:245px;min-width:245px;" src="http://www.photorankr.com/',$followingpic,'" alt="',$fullname,'" height="245" width="245" /></a></div>';
        
        }
        echo'</div>';
    }
    
    
    elseif($view == 'favorites') {
    
    $favesquery = "SELECT * FROM userinfo WHERE emailaddress = '$useremail' LIMIT 0, 1";
	$favesresult = mysql_query($favesquery) or die(mysql_error());
	$faves = mysql_result($favesresult, 0, "faves");
    
    //run the query returning the results in the order in which they were favorited starting at the photo specified by $x
	$favephotosquery = "SELECT * FROM photos WHERE source IN ($faves) ORDER BY FIELD(source, $faves) DESC LIMIT 9";
	$newresult = mysql_query($favephotosquery);	
    $numberofpics2 = mysql_num_rows($newresult);
    
        $query = mysql_query("SELECT * FROM photos WHERE source IN ($faves) ORDER BY FIELD (source, $faves) DESC LIMIT 9");
        $numresults = mysql_num_rows($query);
        echo'<div id="thepics">';
        echo'<div id="container" class="grid_18" style="width:770px;margin-top:0px;padding-left:20px;">';

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

                <div class="fPic" id="',$id,'" style="width:245px;height:230px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a style="text-decoration:none;" href="http://photorankr.com/fullsize.php?image=', $image[$iii], '">

                <div class="statoverlay" style="z-index:1;left:0px;top:155px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">',$caption,'</span><br><span style="font-size:14px;font-family:helvetica,arial;font-weight:100;">Score: ',$score,'<br>Favorites: ',$faves,'</span></p></div>

                <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:245px;min-width:245px;" src="http://www.photorankr.com/',$imageThumb[$iii],'" alt="',$caption,'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
	    
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
					url: "../loadMoreFavePicsVP3.php?lastPicture=" + $(".fPic:last").attr("id")+"&user=', $userid, '",
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
    
    
    elseif($view == 'search') {
        
        $searchterm = htmlentities(mysql_real_escape_string($_POST['searchterm']));
        $query = mysql_query("SELECT * FROM photos WHERE concat(caption,location,tag1,tag2,tag3,tag4) LIKE '%$searchterm%' AND emailaddress = '$useremail' ORDER BY (views) DESC");
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

                <div class="fPic" id="',$id,'" style="width:245px;height:230px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a style="text-decoration:none;" href="http://photorankr.com/fullsize.php?image=', $image[$iii], '">

                <div class="statoverlay" style="z-index:1;left:0px;top:155px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">',$caption,'</span><br><span style="font-size:14px;font-family:helvetica,arial;font-weight:100;">Score: ',$score,'<br>Favorites: ',$faves,'</span></p></div>

                <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:245px;min-width:245px;" src="http://www.photorankr.com/',$imageThumb[$iii],'" alt="',$caption,'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
	    
                } //end for loop      
        
        echo'</div>';
        echo'</div>';
    
    }
    
    
    elseif($view == 'contact') {
    
        echo'<div class="grid_16" style="margin-left:20px;font-family: arial; font-size: 18px;font-weight:200;margin-top:20px;">';
	if($_SESSION['loggedin'] == 1) {
	    
		echo' <div style="position:absolute; font-size: 25px; font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
		line-height: 28px; color: #333333;">
    
		<span style="font-size:20px;">Send ',$fullname,' a message:</span>
        <br /><br />
		<form method="post" action="sendmessage2.php" />
		<textarea cols="95" rows="10" style="width:650px" name="message"></textarea>
    		<br />
    		<br />
		<input type="submit" class="btn btn-success" value="Send Message"/>
		<input type="hidden" name="emailaddressofviewed" value="',$useremail,'" />
		</form>';
	}
	else {
    		echo' <div style="font-size: 20px;margin-left:100px;text-align:center;margin-top:150px;font-weight:200;font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
		line-height: 18px;
		color: #333333;">';
		echo 'You must be signed in to contact this person.</div>';
	}

	if($_GET['action'] == "messagesent") {
		echo '<div style="font-size: 20px;">Message Sent!</div>';
	}
    echo '</div>';
    
    
    }
    
?>

</div><!--end grid 18-->

</div><!--end 24 grid-->

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
