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


//find the current time
$currenttime = time();

//find out which view they are looking at
$view = htmlentities($_GET['view']);

$email6 = $_SESSION['email'];

//QUERY FOR NOTIFICATIONS
$currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$email6'";
$currentnotsquery = mysql_query($currentnots);
$currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");

//DE-HIGHLIGHT NOTIFICATIONS IF CLICKED ON

if(isset($_GET['newsid'])){
$newsid = htmlentities($_GET['newsid']);
$idformatted = $newsid . " ";
$unhighlightquery = "UPDATE userinfo SET unhighlight = CONCAT(unhighlight,'$idformatted') WHERE emailaddress = '$email6'";
$unhighlightqueryrun = mysql_query($unhighlightquery);

//notifications query reset 
if($currentnotsresult > 0) {
$notsquery = "UPDATE userinfo (SET notifications = 0) WHERE emailaddress = '$email6'";
$notsqueryrun = mysql_query($notsquery); }
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


<!DOCTYPE html>
<head>
	<title>View all of the campaigns on PhotoRankr</title>
     <link rel="stylesheet" type="text/css" href="market/css/bootstrapNew.css" />
 <link rel="stylesheet" href="market/css/reset.css" type="text/css" />
  <link rel="stylesheet" href="market/css/text.css" type="text/css" />
   <link rel="stylesheet" href="css/style.css" type="text/css" />
  <link rel="stylesheet" href="960_24.css" type="text/css" />
  	<link rel="stylesheet" type="text/css" href="market/css/all.css"/>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  	<script src="market/js/bootstrap.js" type="text/javascript"></script>
  	<script src="market/js/bootstrap-dropdown.js" type="text/javascript"></script>
  	<script src="market/js/bootstrap-collapse.js" type="text/javascript"></script>
  	<link rel="shortcut icon" type="image/x-png" href="market/graphics/favicon.png"/>
    
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


 .statoverlay {
opacity:.7;
filter:alpha(opacity=40);
z-index:1;
transition: opacity .5s;
-moz-transition: opacity .5s;
-webkit-transition: opacity .5s;
-o-transition: opacity .5s;
}
 
.statoverlay:hover {
opacity:.7;
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
</head>

<body style="overflow-x:hidden;">

<?php navbarnew(); ?>

	<div class="container_24">
		<div class="grid_24" style="margin-top:65px;">
    
    <?php

echo'
    
<div class="grid_18 roundedright underbox" style="background-color:#eeeff3;height:40px;margin-top:10px;width:940px;">

<a style="text-decoration:none;color:black;" href="viewcampaigns.php"><div class="clicked" style="width:170px;height:40px;border-right:1px solid #ccc;float:left;';if($view == '' && !$_GET['searchterm']) {echo'background-color:#bbb;color:white;';}echo'"><div style="font-size:16px;font-weight:200;margin-top:8px;text-align:center;">All Campaigns</div></div></a>

<a style="text-decoration:none;color:black;" href="viewcampaigns.php?view=current"><div class="clicked" style="width:170px;height:40px;border-right:1px solid #ccc;border-left:1px solid #ccc;float:left;';if($view == 'current') {echo'background-color:#bbb;color:white;';}echo'"><div style="font-size:16px;font-weight:200;margin-top:8px;text-align:center;">Current Campaigns</div></div></a>

<a style="text-decoration:none;color:black;" href="viewcampaigns.php?view=previous"><div class="clicked" style="width:170px;height:40px;border-right:1px solid #ccc;float:left;';if($view == 'previous') {echo'background-color:#bbb;color:white;';}echo'"><div style="font-size:16px;font-weight:200;margin-top:8px;text-align:center;">Past Campaigns</div></div></a>

<a style="text-decoration:none;color:black;" href="viewcampaigns.php?view=winners"><div class="clicked" style="width:170px;height:40px;border-right:1px solid #ccc;float:left;';if($view == 'winners') {echo'background-color:#bbb;color:white;';}echo'"><div style="font-size:16px;font-weight:200;margin-top:8px;text-align:center;">Past Winners</div></div></a>

<div style="width:180px;height:40px;float:left;margin-left:5px;"><div style="font-size:22px;font-weight:100;margin-top:-2px;text-align:center;">
<form class="navbar-search" method="GET">
<input class="searchcampaigns" style="position:relative;margin-left:15px;margin-top:2px;font-family:helvetica;font-size:13px;font-weight:200;color:black;" name="searchterm" placeholder="Search Campaigns&nbsp;.&nbsp;.&nbsp;.&nbsp;" type="text">
</form></div></div>

<br /><br /><br /><br />';
 
        
//if the view wasn't set
if($view == "" || $view == "current") {

if($view == "") {
    //select all the campaigns sort descending
	$allcampaignsquery = "SELECT * FROM campaigns ORDER BY endtime DESC";
	$allcampaignsresult = mysql_query($allcampaignsquery);
}
//view is current
elseif($view == "current") {
	//they are viewing the ongoing campaigns

	//select all the campaigns that are still live and show the one closest to ending first
	$allcampaignsquery = "SELECT * FROM campaigns WHERE endtime > '$currenttime' ORDER BY endtime ASC";
	$allcampaignsresult = mysql_query($allcampaignsquery);
    
    if(mysql_num_rows($allcampaignsresult) == 0) {  
        echo'<div class="grid_24" style="margin-top:100px;text-align:center;font-size:18px;font-family:helvetica,arial;font-weight:200;">There are currently no campaigns running</div>';
    }
    
}

if($view == "" && $_GET['searchterm']) {
    //select all the campaigns sort descending
    $searchterm = htmlentities($_GET['searchterm']);
	$allcampaignsquery = "SELECT * FROM campaigns WHERE concat(title,description) LIKE '%$searchterm%' ORDER BY endtime DESC";
	$allcampaignsresult = mysql_query($allcampaignsquery);
}

	//now group photos by their campaign which will be used later on
	$randphotoquery = "SELECT source, campaign FROM campaignphotos GROUP BY campaign";
	$randphotoresult = mysql_query($randphotoquery);

	//loop through the results to create arrays of the needed campaign info and of a photo to display
	for($iii=0; $iii < mysql_num_rows($allcampaignsresult); $iii++) {
		//find out all the info about this campaign
		$endtime           = mysql_result($allcampaignsresult, $iii, "endtime");
		$quote[$iii]       = (mysql_result($allcampaignsresult, $iii, "quote") * .7);
        $repemail[$iii]       = mysql_result($allcampaignsresult, 0, "repemail");
		$title[$iii]       = mysql_result($allcampaignsresult, $iii, "title");
        $title[$iii] = (strlen($title[$iii]) > 25) ? substr($title[$iii],0,23). " &#8230;" : $title[$iii];
		$description[$iii] = mysql_result($allcampaignsresult, $iii, "description");
		$photoid[$iii]          = mysql_result($allcampaignsresult, $iii, "id");
        $topphotosquery =    mysql_query("SELECT source FROM campaignphotos WHERE campaign = '$photoid[$iii]' ORDER BY (points/votes) DESC");
        $numentries = mysql_num_rows($topphotosquery);
        
        $image1 = mysql_result($topphotosquery, 0, "source");
        if(strpos($image1,'http://') === false) {
            $image1 = 'market/' . $image1;
        }
        if($image1 == '' || $image1 == 'market/') {
            $image1 = 'graphics/nophotopost.png';
        }

        $image2 = mysql_result($topphotosquery, 1, "source");
        if(strpos($image2,'http://') === false) {
            $image2 = 'market/' . $image2;
        }
        if($image2 == '' || $image2 == 'market/') {
            $image2 = 'graphics/nophotopost.png';
        }
        
        $image3 = mysql_result($topphotosquery, 2, "source");
        if(strpos($image3,'http://') === false) {
            $image3 = 'market/' . $image3;
        }
        if($image3 == '' || $image3 == 'market/') {
            $image3 = 'graphics/nophotopost.png';
        }
        
        $campaignusersquery= mysql_query("SELECT logo,name FROM campaignusers WHERE repemail = '$repemail[$iii]'");
        $logo = 'market/' . mysql_result($campaignusersquery, 0, "logo");
            if($logo == 'market/') {
                $logo = 'market/graphics/no_logo.png';
            }
        $name =              mysql_result($campaignusersquery, 0, "name");
        
		$timeleft          = $endtime - time();
		//find out how many days hours minutes are left
		$daysleft          = floor($timeleft / (24*60*60));
		$timeleft          -= 24*60*60*$daysleft;
    	$hoursleft         = floor($timeleft / (60*60));
		$timeleft          -= 60*60*$hoursleft;
		$minutesleft       = floor($timeleft / 60);

		//find the photo in $randphotoresult where the campaign id matches
		for($jjj=0; $jjj < mysql_num_rows($randphotoresult); $jjj++) {
			//if the current photo matches
			if(mysql_result($randphotoresult, $jjj, "campaign") == $photoid[$iii]) {
				//then it is the photo we want
				$coverphoto[$iii] = mysql_result($randphotoresult, $jjj, "source");
                $findme   = 'photorankr.com';
                $pos = strpos($coverphoto[$iii], $findme);
                if($pos !== false) {
                $coverphoto[$iii] = str_replace("userphotos/","userphotos/", $coverphoto[$iii]);
            }
        else{
        $coverphoto[$iii] = str_replace("userphotos/","market/userphotos/", $coverphoto[$iii]);
    }
			}
		}
    $coverphoto[$iii] = str_replace("userphotos/","userphotos/medthumbs/", $coverphoto[$iii]);

		list($width, $height) = getimagesize("market/" . $coverphoto[$iii]);
		$imgratio = $height / $width;
    	$heightls = $height / 2.5;
    	$widthls = $width / 2.5;

    	//if there aren't any photos in the campaign at all, set it to the default
		if($coverphoto[$iii] == "") {
			$coverphoto[$iii] = "graphics/nophotopost.png";
			$heightls = 280;
			$widthls = 280;
		}

    echo '<div class="rounded" style=" background-color: #eeeff3;width:930px;height:150px;padding:10px;">
                <div style="float:left;width:130px;height:130px;padding:8px;"><img src="',$logo,'" height="130" width="130" /></div>
                    <div style="float:left;border-left:1px solid #ccc;height:130px;margin-left:8px;margin-top:8px;">
                        
                        <div style="padding:15px;width:200px;float:left;margin-top:-10px;">
                        <span style="font-size:16px;font-weight:200;"><a style="text-decoration:none;color:#3e608c;" href="campaignphotos.php?id=',$photoid[$iii],'">',$title[$iii],'</a></span><br /><br />',$name,'<br />Ends&nbsp;|&nbsp;'; if($daysleft > 0) {echo $daysleft,' days,', $hoursleft, ' hour';} else {echo '<span style="color:red;font-weight:200;">Campaign Over.</span>';} echo'<br />Photographers: 20<br />Entries: ',$numentries,'                     </div>
                        
                        <div style="width:470px;float:left;border-left:1px solid #ccc;"> 
                            <div style="margin-left:10px;">
                            <img style="padding:5px;" src="',$image1,'" height="120" width="120" />
                            <img style="padding:5px;" src="',$image2,'" height="120" width="120" />
                            <img style="padding:5px;" src="',$image3,'" height="120" width="120" />
                            </div>
                        </div>
                        
                        <div style="float:left;border-left:1px solid #ccc;height:130px;width:90px;margin-left:-50px;">
                            <div style="padding-left:16px;padding-top:30px;text-align:center;">
                            <span style="font-size:20px;font-weight:200;">Reward</span><br ><br /><span style="font-size:16px;font-weight:200;">$',$quote[$iii],'</span>
                            </div>
                        </div>
                        
                    </div>
        
		</div><br />';
        
	}
    
	//now display all the campaigns with the variables created above with a link to campaignphotos.php?id=id[$iii]
}
//otherwise they are looking at the past campaigns

elseif($view == 'winners') {

$winquery = mysql_query("SELECT * FROM campaigns WHERE winnerphoto != '' ORDER BY id ASC");
$numwins = mysql_num_rows($winquery);

echo'<div style="margin-top:-20px;">';

for($iii=0;$iii < $numwins; $iii++) {
$campaign = mysql_result($winquery,$iii,'title');
$id = mysql_result($winquery,$iii,'id');
$winnerphoto = mysql_result($winquery,$iii,'winnerphoto');
$winneremail = mysql_result($winquery,$iii,'winneremail');

$coverquery = mysql_query("SELECT source,caption FROM campaignphotos WHERE id = '$winnerphoto'");
$coverphoto = mysql_result($coverquery,0,'source');
$winningphotocaption = mysql_result($coverquery,0,'caption');
$coverphoto = str_replace("userphotos/","market/userphotos/medthumbs/", $coverphoto);

$profquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$winneremail'");
$num = mysql_num_rows($profquery);
$profilephoto = mysql_result($profquery,0,'profilepic');
$firstname= mysql_result($profquery,0,'firstname');
$lastname = mysql_result($profquery,0,'lastname');
$winnername = $firstname . " " . $lastname;
$winnerid = mysql_result($profquery,0,'user_id');

echo'<div class="rounded" style="background-color:#eeeff3;width:930px;height:150px;padding:10px;font-size:15px;font-family:helvetica neue,helvetica,arial;margin-top:20px;font-weight:200;">
<img class="dropshadow" style="float:left;border:1px solid black;margin-top:10px;margin-left:5px;" src="',$coverphoto,'" height="130" width="130" />
<div style="margin-left:50px;float:left;width:650px;height:160px;">
Campaign: <span style="font-size:22px;">"<a style="color:black;" href="campaignphotos.php?id=',$id,'">',$campaign,'</a>"</span><br /><br />
Winner: &nbsp;<img class="dropshadow" style="border: 1px solid black;" src="',$profilephoto,'" width="40" height="40" />&nbsp;&nbsp;<a style="color:black;" href="viewprofile.php?u=',$winnerid,'"><span style="font-size:15px;">',$winnername,'</span></a><br /><br />
Winning Photo: <span style="font-size:15px;"><a style="color:black;" href="fullsizecampaign.php?id=',$winnerphoto,'">',$winningphotocaption,'</a></span><br />

</div>
</div>';

}

echo'</div>';

}

else {

	//select all the campaigns that are still live and show the one closest to ending first
	$allcampaignsquery = "SELECT * FROM campaigns WHERE endtime <= '$currenttime' ORDER BY endtime DESC LIMIT 16";
	$allcampaignsresult = mysql_query($allcampaignsquery);

	//now group photos by their campaign which will be used later on
	$randphotoquery = "SELECT source, campaign FROM campaignphotos GROUP BY campaign";
	$randphotoresult = mysql_query($randphotoquery);

	//loop through the results to create arrays of the needed campaign info and of a photo to display
	for($iii=0; $iii < mysql_num_rows($allcampaignsresult); $iii++) {
		//find out all the info about this campaign
		$quote[$iii]       = (mysql_result($allcampaignsresult, $iii, "quote")*.7);
		$title[$iii]       = mysql_result($allcampaignsresult, $iii, "title");
		$description[$iii] = mysql_result($allcampaignsresult, $iii, "description");
		$photoid[$iii]          = mysql_result($allcampaignsresult, $iii, "id");
        $title[$iii]       = mysql_result($allcampaignsresult, $iii, "title");
        $title[$iii] = (strlen($title[$iii]) > 25) ? substr($title[$iii],0,23). " &#8230;" : $title[$iii];
		$description[$iii] = mysql_result($allcampaignsresult, $iii, "description");
		$photoid[$iii]          = mysql_result($allcampaignsresult, $iii, "id");
        $topphotosquery =    mysql_query("SELECT source FROM campaignphotos WHERE campaign = '$photoid[$iii]' ORDER BY (points/votes) DESC");
        $numentries = mysql_num_rows($topphotosquery);
        
        $image1 = mysql_result($topphotosquery, 0, "source");
        if(strpos($image1,'http://') === false) {
            $image1 = 'market/' . $image1;
        }
        if($image1 == '' || $image1 == 'market/') {
            $image1 = 'graphics/nophotopost.png';
        }

        $image2 = mysql_result($topphotosquery, 1, "source");
        if(strpos($image2,'http://') === false) {
            $image2 = 'market/' . $image2;
        }
        if($image2 == '' || $image2 == 'market/') {
            $image2 = 'graphics/nophotopost.png';
        }
        
        $image3 = mysql_result($topphotosquery, 2, "source");
        if(strpos($image3,'http://') === false) {
            $image3 = 'market/' . $image3;
        }
        if($image3 == '' || $image3 == 'market/') {
            $image3 = 'graphics/nophotopost.png';
        }
        
        
        $campaignusersquery= mysql_query("SELECT logo,name FROM campaignusers WHERE repemail = '$repemail[$iii]'");
        $logo = 'market/' . mysql_result($campaignusersquery, 0, "logo");
            if($logo == 'market/') {
                $logo = 'market/graphics/no_logo.png';
            }
        $name =              mysql_result($campaignusersquery, 0, "name");

		$coverphotoid[$iii]  = mysql_result($allcampaignsresult, $iii, "winnerphoto");
        $coverquery = mysql_query("SELECT source FROM campaignphotos WHERE id = '$coverphotoid[$iii]'");
        $coverphoto[$iii] = mysql_result($coverquery, 0, "source");
		$winner            = mysql_result($allcampaignsresult, $iii, "winneremail");

		//if a winner hasn't been selected yet for this campaign
		if($coverphoto[$iii] == "") {
			//find the photo in $randphotoresult where the campaign id matches
			for($jjj=0; $jjj < mysql_num_rows($randphotoresult); $jjj++) {
				//if the current photo matches
				if(mysql_result($randphotoresult, $jjj, "campaign") == $photoid[$iii]) {
					//then it is the photo we want
					$coverphoto[$iii] = mysql_result($randphotoresult, $jjj, "source");
				}
			}
		}	
		//otherwise, a winner has been selected so find out their first and last name
		else {
			//add this person to the winners emailaddress list
			$winneremaillist .= $winner;
			$winneremaillist .= "',";
		}	
    $coverphoto[$iii] = str_replace("userphotos/","market/userphotos/medthumbs/", $coverphoto[$iii]);

		list($width, $height) = getimagesize($coverphoto[$iii]);
		$imgratio = $height / $width;
    	$heightls = $height / 2.5;
    	$widthls = $width / 2.5;

    	//if there aren't any photos in the campaign at all, set it to the default
		if($coverphoto[$iii] == "") {
			$coverphoto[$iii] = "market/graphics/nophotopost.png";
			$heightls = 280;
			$widthls = 280;
		}

		echo '<div class="rounded" style="background-color:#eeeff3;width:930px;height:150px;padding:10px;">
                <div style="float:left;width:130px;height:130px;padding:8px;"><img src="',$coverphoto[$iii],'" height="130" width="130" /></div>
                    <div style="float:left;border-left:1px solid #ccc;height:130px;margin-left:8px;margin-top:8px;">
                        
                        <div style="padding:15px;width:200px;float:left;margin-top:-10px;">
                        <span style="font-size:16px;font-weight:200;"><a style="text-decoration:none;color:#3e608c;" href="campaignphotos.php?id=',$photoid[$iii],'">',$title[$iii],'</a></span><br /><br />',$name,'<br />Ends&nbsp;|&nbsp;<span style="color:red;font-weight:200;">Campaign is Over.</span><br />Photographers: 20<br />Photos: 143                     </div>
                        
                        <div style="width:470px;float:left;border-left:1px solid #ccc;"> 
                            <div style="margin-left:10px;">
                            <img style="padding:5px;" src="',$image1,'" height="120" width="120" />
                            <img style="padding:5px;" src="',$image2,'" height="120" width="120" />
                            <img style="padding:5px;" src="',$image3,'" height="120" width="120" />
                            </div>
                        </div>
                        
                        <div style="float:left;border-left:1px solid #ccc;height:130px;width:90px;margin-left:-50px;">
                            <div style="padding-left:16px;padding-top:30px;text-align:center;">
                            <span style="font-size:20px;font-weight:200;">Reward</span><br ><br /><span style="font-size:16px;font-weight:200;">$',$quote[$iii],'</span>
                            </div>
                        </div>
                        
                    </div>
        
		</div><br />';
	}

	//take the trailing comma off of the $winneremaillist
	$winneremaillist = substr($winneremaillist, 0, -1);

	//select all of the photographers in the list
	$photographerquery = "SELECT firstname, lastname, emailaddress FROM userinfo WHERE emailaddress IN($winneremaillist) LIMIT 16";
	$photographerresult = mysql_query($photographerquery);

	//loop through all of the photos to match the emailaddresses together
	for($iii=0; $iii < mysql_num_rows($allcampaignsresult); $iii++) {
		//loop through those photographers
		for($jjj=0; $jjj < mysql_num_rows($photographerresult); $jjj++) {
			//if the current emailaddresses are a match
			if(mysql_result($allcampaignsresult, $iii, "winneremail") == mysql_result($photographerresult, $jjj, "emailaddress")) {
				//set the first and last name variables
				$winnerfirst[$iii] = mysql_result($photographerresult, $jjj, "firstname");
				$winnerlast[$iii] = mysql_result($photographerresult, $jjj, "lastname");
			}
		}	
	}
}

?>

</div>


</body>
</html>