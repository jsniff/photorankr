<?php

//log them out if they try to logout
session_start();

if($_GET['action'] == "logout") {
	$_SESSION['loggedin'] = 0;
	session_destroy();
}

//connect to the database
require "db_connection.php";
require "functionsnav.php";

//start session
session_start();
$email = $_SESSION['email'];


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

        	die('That user does not exist in our database. <a href=signin.php>Click Here to Register</a>');

        }
	$info = mysql_fetch_array($check);    
	if($_POST['password'] == $info['password']){

	//then redirect them to the same page as signed in and set loggedin to 1
	$_SESSION['loggedin']=1; 
	$_SESSION['email']=$_POST['emailaddress'];
    $email = $_SESSION['email'];
	}
    
	//gives error if the password is wrong
    	if ($_POST['password'] != $info['password']) {
die('Incorrect password, please try again. <a href="lostpassword.php"> Lost your password?</a>');	}
}


    //GET INFO FROM CURRENT PHOTO ID
    $id = htmlentities($_GET['id']);
    $photoid = $_GET['id'];
    $imagequery = "SELECT source,caption,campaign,points,votes,emailaddress FROM campaignphotos WHERE id = '$id'";
    $imagequeryrun = mysql_query($imagequery);
    
    $owner = mysql_result($imagequeryrun, 0, 'emailaddress');
    $photogquery = mysql_query("SELECT user_id,firstname,lastname,profilepic,reputation FROM userinfo WHERE emailaddress = '$owner'");
    $fullname = mysql_result($photogquery, 0, 'firstname') ." ". mysql_result($photogquery, 0, 'lastname');
    $reputation = mysql_result($photogquery, 0, 'reputation');
    $profilepic = mysql_result($photogquery, 0, 'profilepic');
    $userid = mysql_result($photogquery, 0, 'user_id');

    $image = mysql_result($imagequeryrun, 0, 'source');
    $findme   = 'photorankr.com';
    $pos = strpos($image, $findme);
    if($pos !== false) {
        $image = str_replace("userphotos/","userphotos/", $image);
    }
    else{
        $image = str_replace("userphotos/","market/userphotos/", $image);
    }
    if($image == '') {
    $image = 'market/graphics/submitaphoto.png';
    }
	$title = mysql_result($imagequeryrun, 0, 'caption');
    $campaign = mysql_result($imagequeryrun, 0, 'campaign');
     $votes =  mysql_result($imagequeryrun, 0, 'votes');
     $points = mysql_result($imagequeryrun, 0, 'points');
     $imagerank = number_format((points/votes),2);
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
    if($numincamp == 1){
    $imageBeforeID = $id;
    }
    
    $imageNextquery = "SELECT id FROM campaignphotos WHERE campaign = '$campaign' AND id > '$id' LIMIT 1";
    $imageNextqueryrun = mysql_query($imageNextquery);
    $imageNextID = mysql_result($imageNextqueryrun, 0, 'id');
    if($imageNextID == '') {
        $imageNextID = $firstID; 
    }
    if($numincamp == 1){
    $imageNextID = $id;
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
    $imageNext = str_replace("userphotos/","market/userphotos/medthumbs/", $imageNext);
    if($imageNext == '') {
    $imageNext = 'market/graphics/submitaphoto.png';
    }

    if($numincamp == 1){
    $imageTwoID = '';
    }
    $imagetwoquery = "SELECT source FROM campaignphotos WHERE campaign = '$campaign' AND id = '$imageTwoID'";
    $imagetwoqueryrun = mysql_query($imagetwoquery);
    $imageTwo = mysql_result($imagetwoqueryrun, 0, 'source');
    $imageTwo = str_replace("userphotos/","market/userphotos/medthumbs/", $imageTwo);
    if($imageTwo == '') {
    $imageTwo = 'market/graphics/submitaphoto.png';
    }

    if($numincamp == 1){
    $imageThreeID = '';
    }
    $imagethreequery = "SELECT source FROM campaignphotos WHERE campaign = '$campaign' AND id = '$imageThreeID'";
    $imagethreequeryrun = mysql_query($imagethreequery);
    $imageThree = mysql_result($imagethreequeryrun, 0, 'source');
    $imageThree = str_replace("userphotos/","market/userphotos/medthumbs/", $imageThree);
    if($imageThree == '') {
    $imageThree = 'market/graphics/submitaphoto.png';
    }

    
    if(!$_GET['id'] || $_GET['id'] == "") {
	    mysql_close();
        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=trending.php">';
		exit();			
    }
    

//GET PREVIOUS VOTES FOR RANKING
$prevvotes = mysql_result($imagequeryrun, 0, 'votes');
$prevpoints = mysql_result($imagequeryrun, 0, 'points');

$email6 = $_SESSION['email'];

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
	<title>Fullsize Photo - "<?php echo $title; ?>"</title>
  	  <link rel="stylesheet" type="text/css" href="css/bootstrapNew.css" />
    <link rel="stylesheet" href="market/css/reset.css" type="text/css" />
    <link rel="stylesheet" href="market/css/text.css" type="text/css" />
    <link rel="stylesheet" type="text/css" href="css/all.css"/>
    <link rel="stylesheet" href="market/css/newfullsize.css" type="text/css" />
    <link rel="stylesheet" href="market/css/960_24.css" type="text/css" />  
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script src="market/js/bootstrap.js" type="text/javascript"></script>
  <script src="market/js/bootstrap-dropdown.js" type="text/javascript"></script>
  <script src="market/js/bootstrap-collapse.js" type="text/javascript"></script>
  <link rel="shortcut icon" type="image/x-png" href="market/graphics/favicon.png"/>

     <script src="market/bootstrap-dropdown.js" type="text/javascript"></script>
     <script src="market/bootstrap-collapse.js" type="text/javascript"></script>
     
      <!--Navbar Dropdowns-->
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

<?php navbarnew(); ?>

<div class="container_24" style="padding-bottom:30px;"><!--Grid container begin-->
 


<?php
$campaigntitlequery = mysql_query("SELECT title from campaigns WHERE id = '$campaign'");
$camptitle = mysql_result($campaigntitlequery,0,'title');

?>

<!--Here the Grid Container Begins-->
<div class="container_24 container-margin" style="margin-top:70px;">
<div class="grid_15">	
	<div class="grid_14 pull_1" style="float:left;"style="float:left;">
		<h1 class="title" style="font-size:22px;padding-bottom:5px;font-weight:200;"> <?php echo $title; ?> </h1>
	</div>	
	<div class="grid_17 pull_1" style="float:left;" >
	<img onmousedown="return false" oncontextmenu="return false;" src="<?php echo $image; ?>" class="image" height="<?php echo $newheight; ?>px" width="<?php echo $newwidth; ?>px" />	
	</div>
    


<!--COMMENTS BOX-->   

        <?php
        if($owner == $email) {

           echo'
             <div class="grid_16 pull_1 comments-box">
                <div class="grid_16 comment">';
        
            if($_GET['action'] == 'comment') {
                
                    $comment = mysql_real_escape_string(htmlentities($_POST['comment']));
                    $id = htmlentities($_GET['id']);

                    $commentinsertquery = mysql_query("INSERT INTO campaigncomments (comment,campaign,photogemail,imageid) VALUES ('$comment','$campaign','$email','$id')");
            
                    }
                
                $commentquery = mysql_query("SELECT * FROM campaigncomments WHERE imageid = '$id'");
                $numcomments = mysql_num_rows($commentquery);
                
                for($i=0; $i < $numcomments; $i++) {
                    $comment = mysql_result($commentquery,$i,'comment');
                    $photogemail = mysql_result($commentquery,$i,'photogemail');
                    $commenteremail = mysql_result($commentquery,$i,'emailaddress');
                    $repquery = mysql_query("SELECT logo FROM campaignusers WHERE repemail = '$commenteremail'");
                    if($photogemail == '') {
                        $logo = mysql_result($repquery,0,'logo');
                            if($logo == '') {
                                $logo = 'market/graphics/nologo.png';
                            }
                    }
                    else {
                        $logo = $profilepic;
                    }
                    
                    echo'<div class="grid_16 comment">
                    <div><img src="',$logo,'" height="30" width="30" /><span style="font-weight:bold;color:#3e608c;font-size:12px;padding-left:10px;">',$commentername,'</span>&nbsp;&nbsp;',$comment,'</div>
                    </div>';
                }
                
                echo'<div style="margin-left:10px;position:relative;top:8px;width:630px;">
                <div style="float:left;"><img  src="',$profilepic,'" height="35" width="35" /></div>
                <form action="fullsizecampaign.php?id=',$id,'&action=comment" method="POST">
                <div style="float:left;"><input type="text" style="width:570px;padding:8px;" name="comment" /></div>
                </form>
                </div>
    
        </div>	
  </div>';
  
  }
  
  ?>
  
</div>



	<!--PHOTOGRAPHER BOX-->
    <div class="grid_7 push_2" style="margin-top:10px;">
			<div class="grid_7 box"> <!--ID Tag-->
				<div>
					<div id="imgborder">
					<img src="http://photorankr.com/<?php echo $profilepic; ?>" class="profilepic"/>
				</div>

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
        </div>


    <!--PREVIEWS-->
    
			<div class="grid_7 box underbox"><!--Next photos-->
				
				<div id="images" style="margin-top:5px;">
                
                <?php if($imageNextID != '') {echo'<a href="fullsizecampaign.php?id=',$imageNextID,'">';} ?>
                <img onmousedown="return false" oncontextmenu="return false;" src="<?php echo $imageNext; ?>" id="nextimg1"/></a>
				</div>
				<div class="nextimg">
                
                <?php if($imageTwoID != '') {echo'<a href="fullsizecampaign.php?id=',$imageTwoID,'">';} ?>
                <img onmousedown="return false" oncontextmenu="return false;" src="<?php echo $imageTwo; ?>" id="nextimg2"/></a>
				</div>
				<div class="nextimg">	
                
                <?php if($imageThreeID != '') {echo'<a href="fullsizecampaign.php?id=',$imageThreeID,'">';} ?>
                <img onmousedown="return false" oncontextmenu="return false;" src="<?php echo $imageThree; ?>"id="nextimg3"/></a>
				</div>
                
				<a style="text-decoration:none;" href="fullsizecampaign.php?id=<?php echo $imageBeforeID; ?>"><div class="grid_1" id="hover_arrow_left">
				</div></a>
					<a style="text-decoration:none;" href="fullsizecampaign.php?id=<?php echo $imageNextID; ?>"><div class="grid_1" id="hover_arrow_right">
				</div></a>
            
            </div>
            
            
        <div class="grid_7 box underbox"><!--Share stuff here-->
					<h1 id="sharelinks"> Share: </h1>
                    
                    <a href="https://www.facebook.com/sharer.php?u=http%3A%2F%2Fphotorankr.com%2Ffullsize.php?image=<?php echo $image; ?>" type="button" share_url="photorankr.com/fullsize.php?image=<?php echo $image; ?>"><img src="graphics/facebook.png" style="width:30px;height:30px;margin: 7px 9px 0px 10px;"/></a>
                    <script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" 
                    type="text/javascript">
                    </script>

					<a href="https://twitter.com/share" data-text="Check out this photo!" data-via="PhotoRankr" data-size="large" data-count="none"><img src="graphics/twitter.png" style="width:30px;height:30px;margin: 7px 9px 0px 5px;"/></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

					<a href=""><img src="graphics/pinterest.png" style="width:30px;height:30px;margin: 7px 9px 0px 5px;"/></a>
                    
<a href="https://plus.google.com/102253183291914861528"><img src="graphics/g+.png" style="width:30px;height:30px;margin:7px 9px 0px 8px;"/></a>
                    
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
      
       
        
    