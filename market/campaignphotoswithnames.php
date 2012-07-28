<?php

//connect to the database
require "db_connection.php";

//start the session
//session_start();

//find out which campaign they are trying to look at
$campaignID = htmlentities($_GET['id']);

//recalculate the score for each of the photos
$scorequery = "SELECT * FROM campaignphotos WHERE campaign='$campaignID'";
$scoreresult = mysql_query($scorequery);

//find out how many votes have been cast
$totalvotes = 0;
for($iii=0; $iii < mysql_num_rows($scoreresult); $iii++) {
	$totalvotes += mysql_result($scoreresult, $iii, "votes");
}

$Scorequery = "UPDATE photos SET score = CASE ";

//loop through the photos and calculate the score for each
for($iii=0; $iii < mysql_num_rows($scoreresult); $iii++) {
	$points = mysql_result($scoreresult, $iii, "points");
	$votes = mysql_result($scoreresult, $iii, "votes");
	$avg = $points / $votes;
	$voteshare = $votes / $totalvotes;
	$score = $avg * $voteshare;
	$source = mysql_result($scoreresult, $iii, "source");

	$Scorequery .= "WHEN source='$source' THEN '$score' ";
}

//end the score update query
$Scorequery .= "END;";

//update the database
mysql_query($Scorequery) or die(mysql_error());

?>

<!DOCTYPE html>
<head>
 <meta name="description" content="View all of the photos from the campaign your are viewing on Photorankr">
<meta name="keywords" content="photorankr, view, photos, campaigns, campaign, photography">
<meta name="author" content="The PhotoRankr Team">
	<title>View all of the photos from this campaign on PhotoRankr</title>
	  <link rel="stylesheet" type="text/css" href="css/bootstrapnew.css" />
 <link rel="stylesheet" href="css/reset.css" type="text/css" />
  <link rel="stylesheet" href="css/text.css" type="text/css" />
  <link rel="stylesheet" href="css/960_24.css" type="text/css" />
  <script type="text/javascript" src="js/jquery.js"></script>   
  <script src="js/bootstrap-dropdown.js" type="text/javascript"></script>
  <script src="js/bootstrap-collapse.js" type="text/javascript"></script>
  <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
  <style type="text/css">


 .statoverlay {
opacity:.0;
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

<!--NAVIGATION BAR-->
<div class="navbar" style="z-index:10;padding-top:0px;font-size:16px;width:100%;">
	<div class="navbar-inner">
		<div class="container">
			    <ul class="nav">
					<li><a style="color:#fff;" class="brand" style="margin-top:10px;margin-right:20px;" href="index.php"><div style="margin-top:-2px"><img src="graphics/logo.png" width="160" /></div></a></li>
					<li><a style="color:#fff;margin-top:2px;" style="color:rgb(56,85,103);margin-right:20px;" href="trending.php">All Campaigns</a></li>
					
					<li class="dropdown">
					<li class="dropdown active">
						<a style="color:#fff;margin-top:2px;margin-right:20px;" href="myprofile.php" class="dropdown-toggle" data-toggle="dropdown">My Campaigns<b class="caret"></b></a>
						<ul class="dropdown-menu" data-dropdown="dropdown">
							<li><a style="color:#fff;" href="myprofile.php?view=news">News</a></li>
							<li><a style="color:#fff;" href="myprofile.php">Photography</a></li>
                            <li><a style="color:#fff;" href="myprofile.php?view=info">Information</a></li>
							<li><a style="color:#fff;" href="myprofile.php?view=upload">Upload</a></li>
							<li><a style="color:#fff;" href="myprofile.php?view=followers">Followers</a></li>
							<li><a style="color:#fff;" href="myprofile.php?view=following">Following</a></li>
							<li><a style="color:#fff;" href="myprofile.php?view=faves">Favorites</a></li>
							<li><a style="color:#fff;" href="myprofile.php?view=', $view, '&action=logout">Log Out</a></li>
						</ul>

						<li class="dropdown active">
						<a style="color:#fff;margin-top:2px;margin-right:20px;" href="myprofile.php" class="dropdown-toggle" data-toggle="dropdown">My Account<b class="caret"></b></a>
						<ul class="dropdown-menu" data-dropdown="dropdown">
							<li><a style="color:#fff;" href="myprofile.php?view=news">News</a></li>
							<li><a style="color:#fff;" href="myprofile.php">Photography</a></li>
                            <li><a style="color:#fff;" href="myprofile.php?view=info">Information</a></li>
							<li><a style="color:#fff;" href="myprofile.php?view=upload">Upload</a></li>
							<li><a style="color:#fff;" href="myprofile.php?view=followers">Followers</a></li>
							<li><a style="color:#fff;" href="myprofile.php?view=following">Following</a></li>
							<li><a style="color:#fff;" href="myprofile.php?view=faves">Favorites</a></li>
							<li><a style="color:#fff;" href="myprofile.php?view=', $view, '&action=logout">Log Out</a></li>
						</ul>

					</li>
				</div> 
   			</div>
		</div>
	<!--/END NAVBAR-->

	<div id="container" class="container_24">
		<div class="grid_24 pull_2" style="width: 1140px;">

<?php

//select the photos in this campaign
$photosquery = "SELECT * FROM campaignphotos WHERE campaign=".$campaignID." LIMIT 12";
$photosresult = mysql_query($photosquery);

//create a variable for the list of photographers
$photographeremaillist = "'";

$photo[12];
$average[12];
$id[12];

//loop through the result to get all of the necessary information 
for($iii=0; $iii < mysql_num_rows($photosresult); $iii++) {
	//get the information for the current photo
	$photo[$iii] = mysql_result($photosresult, $iii, "source");
	$points = mysql_result($photosresult, $iii, "points");
	$votes = mysql_result($photosresult, $iii, "votes");
	$average[$iii] = $points / $votes;
	$id[$iii] = mysql_result($photosresult, $iii, "id");
}

//select all of the campaigns information
$campaignquery = "SELECT * FROM campaigns WHERE id='$campaignID' LIMIT 1";
$campaignresult = mysql_query($campaignquery);

//find the title and description of this campaign
$title = mysql_result($campaignresult, 0, "title");
$description = mysql_result($campaignresult, 0, "description");

//display the title and description

//if the campaign is over
if(mysql_result($campaignresult, 0, "endtime") <= time()) {
	//display "this campaign is over"

	//find out who the winner was
	$winneremail = mysql_result($campaignresult, 0, "winneremail");

	//if a winner has been selected
	if($winner != "") {
		//query this person from the database
		$winnerquery = "SELECT firstname, lastname FROM userinfo WHERE emailaddress='$winneremail' LIMIT 1";
		$winnerresult = mysql_query($winnerquery);

		//find this persons first and last name
		$winnerfirst = mysql_result($winneresult, 0, "firstname");
		$winnerlast = mysql_result($winneresult, 0, "lastname");

		//display "Scott Fink won!"
	}
	//otherwise a winner hasn't been selected
	else {
		//display "no winner has been chosen yet"
	}
}
//otherwise the campaign is not over yet 
else {
	//find out how much time is left in the campaign
	$timeleft          = mysql_result($campaignresult, 0, "endtime") - time();
	//find out how many days hours minutes are left
	$daysleft          = floor($timeleft / (24*60*60));
	$timeleft          -= 24*60*60*$daysleft;
	$hoursleft         = floor($timeleft / (24*60));
	$timeleft          -= 24*60*$hoursleft;
	$minutesleft       = floor($timeleft / 60);

	//display how much time is left in the campaign

	//display a link to uploadcampaignphoto.php?id=$campaignID
}

?>

</div>
</div>
</body>
</html>