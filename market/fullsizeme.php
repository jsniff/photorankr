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
$repemail = $_SESSION['repemail'];

    
    if(htmlentities($_GET['action']) == rank) {
        $id = htmlentities($_GET['id']);
        $ranking = mysql_real_escape_string($_POST['ranking']);
        $rankquery = mysql_query("UPDATE campaignphotos SET votes = 1, points = '$ranking' WHERE id = '$id'");
    
    }
    
     //GET INFO FROM CURRENT PHOTO ID
    $id = htmlentities($_GET['id']);
    $imagequery = "SELECT source,caption,campaign,points,votes,emailaddress FROM campaignphotos WHERE id = '$id'";
    $imagequeryrun = mysql_query($imagequery);
        
    $owner = mysql_result($imagequeryrun, 0, 'emailaddress');
    $photogquery = mysql_query("SELECT user_id,firstname,lastname,profilepic,reputation FROM userinfo WHERE emailaddress = '$owner'");
    $fullname = mysql_result($photogquery, 0, 'firstname') ." ". mysql_result($photogquery, 0, 'lastname');
    $reputation = mysql_result($photogquery, 0, 'reputation');
    $profilepic = mysql_result($photogquery, 0, 'profilepic');
    $userid = mysql_result($photogquery, 0, 'user_id');
    
    $image = mysql_result($imagequeryrun, 0, 'source');
    if($image == '') {
    $image = 'graphics/nophotosubmit.png';
    }
	$title = mysql_result($imagequeryrun, 0, 'caption');
    $campaign = mysql_result($imagequeryrun, 0, 'campaign');
     $votes =  mysql_result($imagequeryrun, 0, 'votes');
     $points = mysql_result($imagequeryrun, 0, 'points');
     $imagerank = number_format(($points/$votes),2);
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

$maxwidth=600;
$maxheight=600;

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
  <link rel="stylesheet" type="text/css" href="../css/all.css"/>
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

<?php
$campaigntitlequery = mysql_query("SELECT title from campaigns WHERE id = '$campaign'");
$camptitle = mysql_result($campaigntitlequery,0,'title');
?>
 
<!--Here the Grid Container Begins-->
<div class="container_24 container-margin" style="margin-top:70px;">
<div class="grid_15">	
	<div class="grid_14 pull_1" style="float:left;">
		<h1 style="font-size:22px;padding-bottom:5px;font-weight:200;"> <?php echo $title; ?> </h1>
	</div>	
	<div class="grid_17 pull_1" style="float:left;" >
	<img onmousedown="return false" oncontextmenu="return false;" src="<?php echo $image; ?>" class="image" height="<?php echo $newheight; ?>px" width="<?php echo $newwidth; ?>px" />	
	</div>
    
    <div class="grid_16 pull_1 comments-box">

            <?php
            
                if($_GET['action'] == 'comment') {
                
                    $comment = mysql_real_escape_string(htmlentities($_POST['comment']));
                    $id = htmlentities($_GET['id']);

                    $commentinsertquery = mysql_query("INSERT INTO campaigncomments (comment,campaign,emailaddress,imageid) VALUES ('$comment','$campaign','$repemail','$id')");
            
                    }
                
                $commentquery = mysql_query("SELECT * FROM campaigncomments WHERE imageid = '$id'");
                $numcomments = mysql_num_rows($commentquery);
                
                for($i=0; $i < $numcomments; $i++) {
                    $comment = mysql_result($commentquery,$i,'comment');
                    $commenteremail = mysql_result($commentquery,$i,'emailaddress');
                    $repquery = mysql_query("SELECT logo FROM campaignusers WHERE repemail = '$commenteremail'");
                    $logo = mysql_result($commentquery,$i,'logo');
                    if($logo == '') {
                        $logo = 'graphics/nologo.png';
                    }
                    
                    echo'<div class="grid_16 comment">
                    <div><a href="viewprofile.php?u=',$commenterid,'"><img src="',$logo,'" height="30" width="30" /><span style="font-weight:bold;color:#3e608c;font-size:12px;padding-left:10px;">',$commentername,'</a></span>&nbsp;&nbsp;',$comment,'</div>
                    </div>';
                }
                
                echo'<div style="margin-left:10px;position:relative;top:8px;width:630px;">
                <div style="float:left;"><img  src="',$logo,'" height="40" width="40" /></div>
                <form action="fullsizeme.php?id=',$id,'&action=comment" method="POST">
                <div style="float:left;"><input type="text" style="width:570px;padding:8px;" name="comment" /></div>
                </form>
                </div>';
            
            ?>
        
        <!--COMMENTS BOX-->   
    
  </div>
</div>

	<!--PHOTOGRAPHER BOX-->
    <div class="grid_7 push_2" style="margin-top:10px;">
			<div class="grid_7 box"> <!--ID Tag-->
				<div>
					
                     <div style="float:left;">
<img class="roundedall" src="http://photorankr.com/<?php echo $profilepic; ?>" width="80px" height="80px" /></div>

			<div id="namewrap">
				<h1 id="name"><a class="click" href="viewprofile.php?u=<?php echo $userid; ?>"><?php echo $fullname; ?></a></h1>
				<div class="progress progress-success" style="width:110px;height: 10px;">
                <div class="bar" style="width:<?php echo $reputation; ?>%;"> 
                </div></div>

				<h1 id="rep"> Rep: &nbsp <?php echo $reputation; ?> </h1>
			</div>	
		</div>
        </div>
        
        <div class="grid_7 box underbox">
            <img src="../graphics/rank_icon.png"/> <span id="rank"> Rank: </span> <span class="numbers"><?php echo $imagerank; ?></span><span id="littlenumbers"> /10 </span>
            
            <script>
                function submitMyForm(sel) {
                    sel.form.submit();
                }
            </script>
            
           <div style="float:left;padding-right:25px;"><form id="Form1" action="fullsizeme2.php?id=<?php echo $id; ?>&action=rank" method="post">
            <select name="ranking" style="width:80px; height:30px;margin-left:15px;margin-top:2px;" onchange="submitMyForm(this)">
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
            </form>
           </div>
           
        </div>


    <!--PREVIEWS-->
    
			<div class="grid_7 box underbox"><!--Next photos-->
				
				<div id="images" style="margin-top:5px;">
                
                <?php if($imageNextID != '') {echo'<a href="fullsize.php?id=',$imageNextID,'">';} ?>
                <img onmousedown="return false" oncontextmenu="return false;" src="<?php echo $imageNext; ?>" id="nextimg1"/></a>
				</div>
				<div class="nextimg">
                
                <?php if($imageTwoID != '') {echo'<a href="fullsize.php?id=',$imageTwoID,'">';} ?>
                <img onmousedown="return false" oncontextmenu="return false;" src="<?php echo $imageTwo; ?>" id="nextimg2"/></a>
				</div>
				<div class="nextimg">	
                
                <?php if($imageThreeID != '') {echo'<a href="fullsize.php?id=',$imageThreeID,'">';} ?>
                <img onmousedown="return false" oncontextmenu="return false;" src="<?php echo $imageThree; ?>"id="nextimg3"/></a>
				</div>
                
				<a style="text-decoration:none;" href="fullsize.php?id=<?php echo $imageBeforeID; ?>"><div class="grid_1" id="hover_arrow_left">
				</div></a>
					<a style="text-decoration:none;" href="fullsize.php?id=<?php echo $imageNextID; ?>"><div class="grid_1" id="hover_arrow_right">
				</div></a>
            
            </div>
            
    <div class="grid_7" style="padding-bottom:3px;">      
        <form name="download_form" method="post" action="download.php">
        <input type="hidden" name="price" value="<?php echo $quote; ?>">
        <input type="hidden" name="caption" value="<?php echo $title; ?>">
        <input type="hidden" name="owner" value="<?php echo $photogemail; ?>">
        <input type="hidden" name="campaignnumber" value="<?php echo $campaign; ?>">
        <input type="hidden" name="imageID" value="<?php echo $id; ?>">
        <input type="hidden" name="image" value="<?php echo $image; ?>">
        <input type="hidden" name="width" value="<?php echo $width; ?>">
        <input type="hidden" name="height" value="<?php echo $height; ?>">
        <button type="submit" name="submit" value="DOWNLOAD NOW" class="btn btn-warning" style="margin-left:4px;width:285px; height: 40px; font-family: arial; position: relative; font-size:14px; top: 7px; left: 0px; ">DOWNLOAD NOW</button>
        </form>
    </div>
            
        <div class="grid_7 box underbox">
            <div style="font-size:16px;font-weight:200;"><a style="color:black;" href="campaignphotos.php?id=<?php echo $campaign; ?>">Return to Campaign</a></div>
        </div>
        
			
</div><!--end right sidebar-->






<!--Footer begin-->   
<div class="grid_24">
<?php footer(); ?>               
</div>
<!--Footer end-->


</body>
</html>
      
       
        
    