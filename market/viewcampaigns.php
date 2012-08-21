<?php

//connect to the database
require "db_connection.php";

//start the session
session_start();
$useremail = $_SESSION['emailaddress'];

require "functionscampaigns3.php"; 
    // if login form has been submitted
    if (htmlentities($_GET['action']) == "login") { 
        login();
    }
    else if(htmlentities($_GET['action']) == "logout") { 
        logout();
    }

//find the current time
$currenttime = time();

//find out which view they are looking at
$view = htmlentities($_GET['view']);

?>
<!DOCTYPE html>
<head>
<meta name="description" content="View all of the campaigns currently on PhotoRankr">
<meta name="keywords" content="Campaigns, view campaigns, photorankr,">
<meta name="author" content="The PhotoRankr Team">
	<title>View all of the campaigns on PhotoRankr</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrapNew.css" />
    <link rel="stylesheet" href="css/reset.css" type="text/css" />
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/text.css" type="text/css" />
    <link rel="stylesheet" href="css/960_24.css" type="text/css" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  	<script src="js/bootstrap.js" type="text/javascript"></script>
  	<script src="js/bootstrap-dropdown.js" type="text/javascript"></script>
  	<script src="js/bootstrap-collapse.js" type="text/javascript"></script>
  	<link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
    
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

<body style="overflow-x:hidden; background-color: #eeeff3;">

<?php navbarsweet(); ?>

	<div id="container" class="container_24">
		<div class="grid_24" style="width: 1120px;top:65px;">
<?php

//STATS
/*$numcampquery = mysql_query("SELECT id,starttime,endtime FROM campaigns");
$numcamps = mysql_num_rows($numcampquery);

$avgphotosquery = mysql_query("SELECT campaign FROM campaignphotos");
$numcampphotos = mysql_num_rows($avgphotosquery);

$avgphotossubmitted = number_format(($numcampphotos/$numcamps),0);

for($iii=0; $iii < $numcamps; $iii++) {
$starttime = mysql_result($numcampquery,$iii,'starttime');
$endtime = mysql_result($numcampquery,$iii,'endtime');
$alltime = $alltime + ($endtime - $starttime);
}

$avgtime = ($alltime/$numcamps);  
$avgtime = floor($avgtime / (24*60*60)) . " days";

echo'<div class="dropshadow well" style="font-family:helvetica neue,arial; font-size:16px;">
<img src="http://photorankr.com/graphics/smallcampaignicon.png" width="60" height="60" />
<div style="margin-left:100px;margin-top:-60px;">
Average # Photos Submitted: ',$avgphotossubmitted,'
<br />
Average Campaign Length: ',$avgtime,'
<br />
<a href="createcampaign.php">Get the shot you need</a>
</div>
</div>';
*/

echo'
<div class="grid_18 roundedright" style="background-color:#eeeff3;height:60px;margin-top:80px;width:940px;">

<a style="text-decoration:none;color:black;" href="viewcampaigns.php"><div class="clicked" style="width:230px;height:60px;border-right:1px solid #ccc;float:left;';if($view == '') {echo'background-color:#bbb;color:white;';}echo'"><div style="font-size:22px;font-weight:100;margin-top:10px;text-align:center;">All Campaigns</div></div></a>

<a style="text-decoration:none;color:black;" href="viewcampaigns.php?view=current"><div class="clicked" style="width:230px;height:60px;border-right:1px solid #ccc;border-left:1px solid #ccc;float:left;';if($view == 'current') {echo'background-color:#bbb;color:white;';}echo'"><div style="font-size:22px;font-weight:100;margin-top:10px;text-align:center;">Current Campaigns</div></div></a>

<a style="text-decoration:none;color:black;" href="viewcampaigns.php?view=previous"><div class="clicked" style="width:230px;height:60px;border-right:1px solid #ccc;float:left;';if($view == 'previous') {echo'background-color:#bbb;color:white;';}echo'"><div style="font-size:22px;font-weight:100;margin-top:10px;text-align:center;">Past Campaigns</div></div></a>

<div style="width:180px;height:60px;float:left;margin-left:3px;"><div style="font-size:22px;font-weight:100;margin-top:6px;text-align:center;">
<form class="navbar-search" method="GET">
<input class="search" style="position:relative;margin-left:15px;margin-top:2px;font-family:helvetica;font-size:14px;font-weight:100;color:black;" name="searchterm" placeholder="Search Campaigns&nbsp;.&nbsp;.&nbsp;.&nbsp;" type="text">
</form></div></div>';


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
}
	//now group photos by their campaign which will be used later on
	$randphotoquery = "SELECT source, campaign FROM campaignphotos GROUP BY campaign";
	$randphotoresult = mysql_query($randphotoquery);

	//loop through the results to create arrays of the needed campaign info and of a photo to display
    echo'<div style="margin-top:80px;">';
	for($iii=0; $iii < mysql_num_rows($allcampaignsresult); $iii++) {
		//find out all the info about this campaign
		$endtime           = mysql_result($allcampaignsresult, $iii, "endtime");
		$quote[$iii]       = mysql_result($allcampaignsresult, $iii, "quote");
		$title[$iii]       = mysql_result($allcampaignsresult, $iii, "title");
        $title[$iii] = (strlen($title[$iii]) > 25) ? substr($title[$iii],0,23). " &#8230;" : $title[$iii];
		$description[$iii] = mysql_result($allcampaignsresult, $iii, "description");
        $repemail[$iii] =    mysql_result($allcampaignsresult, $iii, "repemail");
		$id[$iii]            = mysql_result($allcampaignsresult, $iii, "id");
        $topphotosquery =    mysql_query("SELECT source FROM campaignphotos WHERE campaign = '$id[$iii]' ORDER BY (points/votes) DESC");
        $numentries = mysql_num_rows($topphotosquery);
        $image1 =            mysql_result($topphotosquery, 0, "source");
        if($image1 == '') {$image1 = 'graphics/nophotosubmit.png';}
        $image2 =            mysql_result($topphotosquery, 1, "source");
        if($image2 == '') {$image2 = 'graphics/nophotosubmit.png';}
        $image3 =            mysql_result($topphotosquery, 2, "source");
        if($image3 == '') {$image3 = 'graphics/nophotosubmit.png';}
    
        $campaignusersquery= mysql_query("SELECT logo,name FROM campaignusers WHERE repemail = '$repemail[$iii]'");
        $logo =              mysql_result($campaignusersquery, 0, "logo");
            if($logo == '') {
                $logo = 'graphics/nologo.png';
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
			if(mysql_result($randphotoresult, $jjj, "campaign") == $id[$iii]) {
				//then it is the photo we want
				$coverphoto[$iii] = mysql_result($randphotoresult, $jjj, "source");
			}
		}

		list($width, $height) = getimagesize($coverphoto[$iii]);
		$imgratio = $height / $width;
    	$heightls = $height / 2.5;
    	$widthls = $width / 2.5;

    	//if there aren't any photos in the campaign at all, set it to the default
		if($coverphoto[$iii] == "") {
			$coverphoto[$iii] = "graphics/nophotosubmit.png";
			$heightls = 280;
			$widthls = 280;
		}

		echo '<div class="rounded" style="width:930px;height:150px;padding:10px;">
                <div style="float:left;width:130px;height:130px;padding:8px;"><img src="',$logo,'" height="130" width="130" /></div>
                    <div style="float:left;border-left:1px solid #ccc;height:130px;margin-left:8px;margin-top:8px;">
                        
                        <div style="padding:15px;width:200px;float:left;margin-top:-10px;">
                        <span style="font-size:16px;font-weight:200;"><a style="text-decoration:none;color:#3e608c;" href="campaignphotos.php?id=',$id[$iii],'">',$title[$iii],'</a></span><br /><br />',$name,'<br />Ends&nbsp;|&nbsp;'; if($daysleft > 0) {echo $daysleft,' days,', $hoursleft, ' hour';} else {echo '<span style="color:red;font-weight:200;">Campaign Over.</span>';} echo'<br />Photos: ',$numentries,'                     </div>
                        
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
    
    echo'<div>';

	//now display all the campaigns with the variables created above with a link to campaignphotos.php?id=id[$iii]
}
//otherwise they are looking at the past campaigns
else {
	//select all the campaigns that are still live and show the one closest to ending first
	$allcampaignsquery = "SELECT * FROM campaigns WHERE endtime <= '$currenttime' ORDER BY endtime DESC LIMIT 16";
	$allcampaignsresult = mysql_query($allcampaignsquery);

	//now group photos by their campaign which will be used later on
	$randphotoquery = "SELECT source, campaign FROM campaignphotos GROUP BY campaign";
	$randphotoresult = mysql_query($randphotoquery);

	//loop through the results to create arrays of the needed campaign info and of a photo to display
    echo'<div style="margin-top:80px;">';
	for($iii=0; $iii < mysql_num_rows($allcampaignsresult); $iii++) {
		//find out all the info about this campaign
		$endtime           = mysql_result($allcampaignsresult, $iii, "endtime");
		$quote[$iii]       = mysql_result($allcampaignsresult, $iii, "quote");
		$title[$iii]       = mysql_result($allcampaignsresult, $iii, "title");
        $title[$iii] = (strlen($title[$iii]) > 25) ? substr($title[$iii],0,23). " &#8230;" : $title[$iii];
		$description[$iii] = mysql_result($allcampaignsresult, $iii, "description");
        $repemail[$iii] =    mysql_result($allcampaignsresult, $iii, "repemail");
		$id[$iii]            = mysql_result($allcampaignsresult, $iii, "id");
        $topphotosquery =    mysql_query("SELECT source FROM campaignphotos WHERE campaign = '$id[$iii]' ORDER BY (points/votes) DESC");
        $numentries = mysql_num_rows($topphotosquery);
        
            for($i=0; $i < $numentries; $i++) {
                
                $owneremail = mysql_result($topphotosquery, $i, "emailaddress");
                
                if(strpos($checklist,'$owneremail') === false) {
                    $checklist = $checklist . " " . $owneremail;
                    $photogcount += 1;
                }
                
            }
        
        $image1 =            mysql_result($topphotosquery, 0, "source");
        if($image1 == '') {$image1 = 'graphics/nophotosubmit.png';}
        $image2 =            mysql_result($topphotosquery, 1, "source");
        if($image2 == '') {$image2 = 'graphics/nophotosubmit.png';}
        $image3 =            mysql_result($topphotosquery, 2, "source");
        if($image3 == '') {$image3 = 'graphics/nophotosubmit.png';}
        $campaignusersquery= mysql_query("SELECT logo,name FROM campaignusers WHERE repemail = '$repemail[$iii]'");
        $logo =              mysql_result($campaignusersquery, 0, "logo");
            if($logo == '') {
                $logo = 'graphics/nologo.png';
            }
        $name =              mysql_result($campaignusersquery, 0, "name");
		$winner            = mysql_result($allcampaignsresult, $iii, "winneremail");
        

		//if a winner hasn't been selected yet for this campaign
		if($coverphoto[$iii] == "") {
			//find the photo in $randphotoresult where the campaign id matches
			for($jjj=0; $jjj < mysql_num_rows($randphotoresult); $jjj++) {
				//if the current photo matches
				if(mysql_result($randphotoresult, $jjj, "campaign") == $id[$iii]) {
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

		list($width, $height) = getimagesize($coverphoto[$iii]);
		$imgratio = $height / $width;
    	$heightls = $height / 2.5;
    	$widthls = $width / 2.5;

    	//if there aren't any photos in the campaign at all, set it to the default
		if($coverphoto[$iii] == "") {
			$coverphoto[$iii] = "graphics/nophotosubmit.png";
			$heightls = 300;
			$widthls = 280;
		}

		echo '<div class="rounded" style="width:930px;height:150px;padding:10px;">
                <div style="float:left;width:130px;height:130px;padding:8px;"><img src="',$logo,'" height="130" width="130" /></div>
                    <div style="float:left;border-left:1px solid #ccc;height:130px;margin-left:8px;margin-top:8px;">
                        
                        <div style="padding:15px;width:200px;float:left;margin-top:-10px;">
                        <span style="font-size:16px;font-weight:200;"><a style="text-decoration:none;color:#3e608c;" href="campaignphotos.php?id=',$id[$iii],'">',$title[$iii],'</a></span><br /><br />',$name,'<br /><span style="color:red;font-weight:400;">Campaign Over</span><br />Photographers: ',$photogcount,'<br />Entries: ',$numentries,'                    </div>
                        
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
    
    echo'</div>';

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



<div class="grid_24" style="padding-bottom:30px;">
<?php footer(); ?>     
</div>    
     
</div>

</body>
</html>