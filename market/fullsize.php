<?php

//connect to the database
require "db_connection.php";
require "functionscampaigns3.php"; 
    // if login form has been submitted
    if (htmlentities($_GET['action']) == "login") { 
        login();
    }
    else if(htmlentities($_GET['action']) == "logout") { 
        logout();
    }

//start the session
session_start();
    
     //GET INFO FROM CURRENT PHOTO ID
    $id = htmlentities($_GET['id']);
    $imagequery = "SELECT source,caption,campaign,points,votes FROM campaignphotos WHERE id = '$id'";
    $imagequeryrun = mysql_query($imagequery);
    $image = mysql_result($imagequeryrun, 0, 'source');
    if($image == '') {
    $image = 'graphics/nophotosubmit.png';
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
    
    $imageNextquery = "SELECT id FROM campaignphotos WHERE campaign = '$campaign' AND id > '$id' LIMIT 1";
    $imageNextqueryrun = mysql_query($imageNextquery);
    $imageNextID = mysql_result($imageNextqueryrun, 0, 'id');
    if($imageNextID == '') {
        $imageNextID = $firstID; 
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
    $imageNext = str_replace("userphotos/","userphotos/medthumbs/", $imageNext);
    if($imageNext == '') {
    $imageNext = 'graphics/nophotosubmit.png';
    }
    
    if($numincamp == 1){
    $imageTwoID = '';
    }
    $imagetwoquery = "SELECT source FROM campaignphotos WHERE campaign = '$campaign' AND id = '$imageTwoID'";
    $imagetwoqueryrun = mysql_query($imagetwoquery);
    $imageTwo = mysql_result($imagetwoqueryrun, 0, 'source');
    $imageTwo = str_replace("userphotos/","userphotos/medthumbs/", $imageTwo);
    if($imageTwo == '') {
    $imageTwo = 'graphics/nophotosubmit.png';
    }
    
    if($numincamp == 1){
    $imageThreeID = '';
    }
    $imagethreequery = "SELECT source FROM campaignphotos WHERE campaign = '$campaign' AND id = '$imageThreeID'";
    $imagethreequeryrun = mysql_query($imagethreequery);
    $imageThree = mysql_result($imagethreequeryrun, 0, 'source');
    $imageThree = str_replace("userphotos/","userphotos/medthumbs/", $imageThree);
    if($imageThree == '') {
    $imageThree = 'graphics/nophotosubmit.png';
    }    
    
    $prevvotes = mysql_result($imagequeryrun, 0, 'votes');
    $prevpoints = mysql_result($imagequeryrun, 0, 'points');
    
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
    
    if(!$_GET['id'] || $_GET['id'] == "") {
	    mysql_close();
        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=trending.php">';
		exit();			
    }


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

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://w3.org/TR/html4/strict.dtd">
<html>
  <head>      
   <meta name="description" content="View the fullsize image of a photo from a campaign">
   <meta name="keywords" content="campaign, view, image, full-size, photo, photography">
   <meta name="author" content="The PhotoRankr Team">
 
	<title>Fullsize Photo - "<?php echo $title; ?>"</title>
  <link rel="stylesheet" type="text/css" href="css/bootstrapNew.css" />
  <link rel="stylesheet" href="css/reset.css" type="text/css" />
  <link rel="stylesheet" href="css/text.css" type="text/css" />
  <link rel="stylesheet" href="css/newfullsize.css" type="text/css" />
  <link rel="stylesheet" href="css/960_24.css" type="text/css" />
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script src="js/bootstrap.js" type="text/javascript"></script>
  <script src="js/bootstrap-dropdown.js" type="text/javascript"></script>
  <script src="js/bootstrap-collapse.js" type="text/javascript"></script>
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
      
        .imageContainer{width:auto;} 
        .imageContainer img {display:block;width:100%;height:auto;}
        
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

<body oncontextmenu="return false;" class="background" style="overflow-x: hidden;">

<?php navbarsweet(); ?>

<div class="container_24" style="padding-bottom:30px;"><!--Grid container begin-->

<?php
$campaigntitlequery = mysql_query("SELECT title from campaigns WHERE id = '$campaign'");
$camptitle = mysql_result($campaigntitlequery,0,'title');
?>
 
<!--TITLE OF PHOTO-->     
<div class="grid_24" style="margin-top:60px;">
<div class="grid_21 pull_2"><div style="margin-top:10px;padding-top:5px;padding-left:3px;line-height:30px;font-size:30px;
"><?php echo '(<a href="campaignphotos.php?id=',$campaign,'">',$camptitle,'</a>) "',$title,'"'; ?>
</div></div></div>

<!--BIG IMAGE BOX-->
<div class="grid_24">

<div class="grid_15 pull_2" style="margin-top:150px;">

<div class="imageContainer" style="margin-top:-135px;">
<img class="phototitle" onmousedown="return false" oncontextmenu="return false;" alt="<?php echo $tags; ?>" src="<?php echo $image; ?>" /></div>
  
</div> 


<!--ARROWS-->
<div class="grid_4 push_6 arrows" style="width:218px;padding:2px;">
<span style="margin-left:30px;"><a style="text-decoration:none;" href="fullsize.php?id=<?php echo $imageBeforeID; ?>"><img src="graphics/arrow left.png" alt="Scroll through photos in their full size glory"
height="50" width="50"/></a></span>

<span style="margin-left:55px;"><a style="text-decoration:none;" href="fullsize.php?id=<?php echo $imageNextID; ?>"><img  src="graphics/arrow right.png" alt="Scroll through photos in their full size glory"
height="50" width="50"/></a></span>

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
 $rankcheck = mysql_query("SELECT voters FROM campaignphotos WHERE source='$image'") or die(mysql_error());
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
		$rankquery="UPDATE campaignphotos SET points='$prevpoints', votes='$prevvotes' WHERE source='$image'";
		mysql_query($rankquery); 
        }
        
        elseif($ultimatereputationme > 50 && $ultimatereputationme < 70)
        {
        $prevpoints+=($ranking*2.0);
		$prevvotes+=2;
		$rankquery="UPDATE campaignphotos SET points='$prevpoints', votes='$prevvotes' WHERE source='$image'";
		mysql_query($rankquery); 
        }
        
        elseif($ultimatereputationme > 30 && $ultimatereputationme < 50)
        {
        $prevpoints+=($ranking*1.5);
		$prevvotes+=1.5;
		$rankquery="UPDATE campaignphotos SET points='$prevpoints', votes='$prevvotes' WHERE source='$image'";
		mysql_query($rankquery); 
        }
        
        elseif($ultimatereputationme > 0 && $ultimatereputationme < 30)
        {
        $prevpoints+=$ranking;
		$prevvotes+=1;
		$rankquery="UPDATE campaignphotos SET points='$prevpoints', votes='$prevvotes' WHERE source='$image'";
		mysql_query($rankquery); 
        }
        
        }  //end querying points and votes count
    
        //Add voter's name to database    
    $voter = "'" . $voteremail . "'";
    $voter = ", " . $voter;
    $voter = addslashes($voter);
    $votersquery = mysql_query("UPDATE campaignphotos SET voters=CONCAT(voters,'$voter') WHERE source='$image'");
    
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
    
   <?php if($imageNextID != '') {echo'<a href="fullsize.php?id=',$imageNextID,'">';} ?>
   <img onmousedown="return false" oncontextmenu="return false;" class="preview" src="<?php echo $imageNext; ?>" height="200" width="210" /></a>
    
    <?php if($imageTwoID != '') {echo'<a href="fullsize.php?id=',$imageTwoID,'">';} ?>
    <img onmousedown="return false" oncontextmenu="return false;" class="preview" style="margin-top:5px;" src="<?php echo $imageTwo; ?>" height="200" width="210" /></a>
    
    <?php if($imageThreeID != '') {echo'<a href="fullsize.php?id=',$imageThreeID,'">';} ?>
    <img onmousedown="return false" oncontextmenu="return false;" class="preview" style="margin-top:5px;" src="<?php echo $imageThree; ?>" height="200" width="210" /></a>
    
    </div>
</div>

</div><!--end of 4 grid-->

<!--Footer begin-->   
<div class="grid_24" style="height:30px;margin-top:30px;background-color:rgb:(238,239,243);text-align:center;padding-top:10px;padding-bottom:20px; background-color:none;text-decoration:none;">
<p style="text-decoration:none;">
</br></br>
<div style="text-align:center;">
Copyright&nbsp;&copy;&nbsp;2012&nbsp;PhotoRankr, Inc.&nbsp;&nbsp;
</div>
<br />
<br />
</p>                   
</div>
<!--Footer end-->


</body>
</html>
      
       
        
    