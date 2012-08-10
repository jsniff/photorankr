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
//session_start();

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
		<div class="grid_24 pull_2" style="width: 1140px;top:65px;">
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
	for($iii=0; $iii < mysql_num_rows($allcampaignsresult); $iii++) {
		//find out all the info about this campaign
		$endtime           = mysql_result($allcampaignsresult, $iii, "endtime");
		$quote[$iii]       = mysql_result($allcampaignsresult, $iii, "quote");
		$title[$iii]       = mysql_result($allcampaignsresult, $iii, "title");
		$description[$iii] = mysql_result($allcampaignsresult, $iii, "description");
		$id[$iii]          = mysql_result($allcampaignsresult, $iii, "id");
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

		echo '
		<div class="phototitle fPic" id="',$id[$iii],'" style="width:280px;height:280px;overflow:hidden; margin-right: 20px;">
			<a href="campaignphotos.php?id=',$id[$iii],'">
        		<div class="statoverlay" style="z-index:1;left:0px;top:200px;position:relative;background-color:black;width:280px;height:80px;"><p style="line-spacing:1.48;padding:5px;color:white;">"',$title[$iii],'"<br />Reward: $', $quote[$iii], '<br />Time Left:'; if($daysleft > 0) {echo $daysleft, ' days, ', $hoursleft, ' hours, ', $minutesleft, ' minutes';} elseif($daysleft < 0) {echo'This campaign is over.';} echo'</p></div>
        		<img style="position:relative;top:-95px;min-height:300px;min-width:280px;" src="', $coverphoto[$iii], '" height="',$heightls,'px" width="',$widthls,'px" />
        	</a>
        </div>';
	}

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
	for($iii=0; $iii < mysql_num_rows($allcampaignsresult); $iii++) {
		//find out all the info about this campaign
		$quote[$iii]       = mysql_result($allcampaignsresult, $iii, "quote");
		$title[$iii]       = mysql_result($allcampaignsresult, $iii, "title");
		$description[$iii] = mysql_result($allcampaignsresult, $iii, "description");
		$id[$iii]          = mysql_result($allcampaignsresult, $iii, "id");
		$coverphoto[$iii]  = mysql_result($allcampaignsresult, $iii, "winnerphoto");
        $coverphotoid[$iii]  = mysql_result($allcampaignsresult, $iii, "winnerphoto");
        $coverquery = mysql_query("SELECT source FROM campaignphotos WHERE id = '$coverphotoid[$iii]'");
        $coverphoto[$iii] = mysql_result($coverquery, 0, "source");
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

		echo '
		<div class="phototitle fPic" id="',$id[$iii],'" style="width:280px;height:280px;overflow:hidden; margin-right: 20px;">
			<a href="campaignphotos.php?id=',$id[$iii],'">
        		<div class="statoverlay" style="z-index:1;left:0px;top:200px;position:relative;background-color:black;width:280px;height:80px;"><p style="line-spacing:1.48;padding:5px;color:white;">"',$title[$iii],'"<br />Reward: $', $quote[$iii], '<br />This campaign is over.</p></div>
        		<img style="position:relative;top:-95px;min-height:240px;min-width:240px;" src="', $coverphoto[$iii], '" height="',$heightls,'px" width="',$widthls,'px" />
        	</a>
        </div>';
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
<div class="grid_3" style="position:fixed;right:100px;">
<div id="accordion2" class="accordion" style="margin-top:80px;width:150px;">

<div class="accordion-group">
<div style="background-color:#eeeff3;" class="accordion-heading dropshadow">
<a class="accordion-toggle" style="color:#21608E;font-weight:bold;" href="viewcampaigns.php"> All </a>
</div>
<div id="collapseOne" class="accordion-body collapse">
</div>
</div>

<div class="accordion-group">
<div style="background-color:#eeeff3;" class="accordion-heading dropshadow">
<a class="accordion-toggle" style="color:#21608E;font-weight:bold;" href="viewcampaigns.php?view=current">Current</a>
</div>
<div id="collapseOne" class="accordion-body collapse">
</div>
</div>

<div class="accordion-group">
<div style="background-color:#eeeff3;" class="accordion-heading dropshadow">
<a class="accordion-toggle" style="color:#21608E;font-weight:bold;" href="viewcampaigns.php?view=previous">Previous</a>
</div>
<div id="collapseTwo" class="accordion-body collapse">
</div>
</div>


</div>



</div>

<div class="grid_24" style="padding-bottom:30px;">
<?php footer(); ?>     
</div>    
     
</div>

</body>
</html>