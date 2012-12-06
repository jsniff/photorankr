<?php

//connect to the database
require "../db_connection.php";
require "functions.php";

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
  
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="https://www.w3.org/1999/xhtml">

<head>

 <meta name="Generator" content="EditPlus">
  <meta name="Author" content="PhotoRankr, PhotoRankr.com">
  <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
  <meta name="Description" content="A gallery of the newest photography, photographers, and exhibits on PhotoRankr.">
     <meta name="viewport" content="width=1200" /> 

    <link rel="stylesheet" type="text/css" href="css/style.css"/>
    <link rel="stylesheet" type="text/css" href="css/campaigns.css"/>
    <link rel="stylesheet" type="text/css" href="css/campaign_view.css"/>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="css/960grid.css"/>
	<link rel="stylesheet" type="text/css" href="css/reset.css"/>    
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.wookmark.js"></script>            
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
  
  <title>PhotoRankr - Campaigns</title>

<style type="text/css">


.statoverlay
{
-moz-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
-webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
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

.position 	
{
margin:42px 0 0 15px;
color:#fff;
}

.margin_none
{
margin-left: -5em;
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


</head>
<body style="overflow-x:hidden; background-color: #eeeff3;">

<?php navbar(); ?>

<!--DIFFERENT GALLERY VIEWS-->

<?php  

if(isset($_GET['id'])){
$id = htmlentities($_GET['id']);
}

if(!$id) {
        
echo'
<div class="container_24" id="container_margin">
<div style="background-color:rgb(245,245,245);z-index:999;height:6.5em;width:100%;position:fixed;">
			<div class="navbar-inner" style="width:920px;min-height:0px;margin-left:-.25em;background:none;">
				<div class="container" id="subnav">
					<ul class="navbar">
						<li class="nav"> 
							<div class="subnav_button">
								<img src="../graphics/buttonA.png" style="height:37px;border-radius: 5px 0 0 5px; "/>
								<h1 class="subnav_head"> Newest Campaigns </h1>
							</div>
						</li>
						<li class="nav" style="margin-left:-10px;">
							<div class="subnav_button">
								<img src="../graphics/buttonB.png" style="height:37px;"/>
								<h1 class="subnav_head"> Trending Campaigns </h1>
							</div>
						</li>
						<li class="nav" style="margin-left:-10px;">
							<div class="subnav_button">
								<img src="../graphics/buttonC.png" style="height:37px;"/>
								<h1 class="subnav_head"> My Campaigns </h1>
							</div>
						</li>
						<li class="nav margin_none" style="margin-left:-10px;;">
							<div class="subnav_button" >
								<img src="../graphics/buttonD.png" style="height:37px;border-radius: 0 5px 5px 0;"/>
								<h1 class="subnav_head"> Sort Campaigns </h1>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	<div class="grid_24">		
	<div class="grid_11 pull_2" id="campaign_col_left" style="margin-left:4.4em;">
		<a href="campaigns.php?id=32"> <div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div></a>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 3px;z-index:1000;"/>
					</div>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>
</div>
		
		<div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 5px;"/>
					</div>/>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>
</div>
		<div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 5px;"/>
					</div>/>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>
</div>
		<div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 5px;"/>
					</div>/>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>
</div>
		<div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 5px;"/>
					</div>/>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>
</div>
		<div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 5px;"/>
					</div>/>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>
</div>
		<div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 5px;"/>
					</div>/>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>
</div>
		<div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 5px;"/>
					</div>/>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>
</div>
		<div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 5px;"/>
					</div>/>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>
</div>
		<div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 5px;"/>
					</div>/>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>
</div>
		<div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 5px;"/>
					</div>/>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>
</div>
		<div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 5px;"/>
					</div>/>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>
</div>
		<div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 5px;"/>
					</div>/>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>
</div>
		<div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 5px;"/>
					</div>/>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>
</div>
		<div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 5px;"/>
					</div>/>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>
</div>
		<div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 5px;"/>
					</div>/>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>
</div>
		<div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 5px;"/>
					</div>/>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>
</div>
		<div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 5px;"/>
					</div>/>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>
</div>
</div>
	<div class="grid_11" id="campaign_col_right" style="margin-left:-.05em;">
				<div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 5px;"/>
					</div>/>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>
</div>
<div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 5px;"/>
					</div>/>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>	
</div>
		<div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 5px;"/>
					</div>/>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>
</div>

		<div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 5px;"/>
					</div>/>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>
</div>

		<div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 5px;"/>
					</div>/>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>
</div>

		<div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 5px;"/>
					</div>/>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>
</div>

		<div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 5px;"/>
					</div>/>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>
</div>

		<div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 5px;"/>
					</div>/>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>
</div>

		<div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 5px;"/>
					</div>/>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>
</div>

		<div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 5px;"/>
					</div>/>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>
</div>

		<div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 5px;"/>
					</div>/>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>
</div>
		<div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 5px;"/>
					</div>/>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>
</div>
		<div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 5px;"/>
					</div>/>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>
</div>
		<div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 5px;"/>
					</div>/>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>
</div>
		<div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 5px;"/>
					</div>/>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>
</div>
		<div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 5px;"/>
					</div>/>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>
</div>

		<div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 5px;"/>
					</div>/>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>
</div>

		<div class="grid_12 campaign_bloc">
			<div class="wrapper">
				<div class="grid_1 push_10" id="banner">
					<img src="graphics/banner_plus.png"/>
				</div>	
				<h1> Photo of Mt. Kilimanjaro </h1> <!--title-->
			</div>
			<div class="wrapper">
				<div class="grid_14">
					<div class="grid_10" style="margin-left:-.4em;">
					<div class="grid_1 arrows">
					<img src="graphics/arrow_left.png"style="width:20px;margin:10px 0 0 6px;"/>
				</div>

					<div class="grid_3" style="overflow:hidden;">
						<img src="images/campaign_1.jpg" style="margin: 20px 0 0 0;width:500px;height:100px;"/>
					</div>
					
					<div class="grid_3">
						<div style="overflow:hidden;">
						<img src="images/campaign_2.jpg" style="margin: 20px 5px 0 ;width:500px;height:100px;"/>
					</div>
					</div>
				<div class="grid_1 arrows">
					<img src="graphics/arrow_right.png" style="width:20px;margin: 10px 0 0 5px;"/>
					</div>/>
				</div>	
			</div>
				<div class="grid_8">
					<h1 id="top_ranked_entries"> Top Ranked Entries </h1>
				</div>		
		<div class="grid_4" style="margin: -7.55em 0em 0 0; border-top: 1px solid #aaa; background-color:#ccc;border-radius: 0 0 3px 0;">
			<div class="grid_4 stat" style="margin:-1px 0;">
				<h1>$90</h1>
				<p> REWARD</p>
			</div>	

			<div class="grid_4 push_1 stat" style="margin-left:-40px;">
				<h1>125</h1>
				<p> ENTRIES</p>
			</div>	
						<div class="grid_4 stat" style="margin-left:0;padding-left:1.5em;">
				<h1> 5 <span class="small_text">days</span> 23 <span class="small_text">hours</span></h1>
				<p style="margin-top:-1.4em;"> REMAINING IN CAMPAIGN</p>
			</div>	
		</div>
	</div>
</div>';
  
} //end of no id 

elseif($id) {

echo'
<div class="container_24" id="container_margin">
	<div style="background-color:rgb(245,245,245);z-index:999;height:6.5em;width:100%;position:fixed;">
			<div class="navbar-inner" style="width:920px;min-height:0px;margin-left:-.25em;background:none;">
				<div class="container" id="subnav">
					<ul class="navbar">
						<li class="nav"> 
							<a href="campaigns.php"> <div class="subnav_button">
								<img src="../graphics/buttonA.png" style="height:37px;border-radius: 5px 0 0 5px; "/>
								<h1 class="subnav_head"> Newest Campaigns </h1>
							</div></a>
						</li>
						<li class="nav" style="margin-left:-10px;">
							<div class="subnav_button">
								<img src="../graphics/buttonB.png" style="height:37px;"/>
								<h1 class="subnav_head"> Trending Campaigns </h1>
							</div>
						</li>
						<li class="nav" style="margin-left:-10px;">
							<div class="subnav_button">
								<img src="../graphics/buttonC.png" style="height:37px;"/>
								<h1 class="subnav_head"> My Campaigns </h1>
							</div>
						</li>
						<li class="nav margin_none" style="margin-left:-10px;;">
							<div class="subnav_button" >
								<img src="../graphics/buttonD.png" style="height:37px;border-radius: 0 5px 5px 0;"/>
								<form class="navbar-search" action="#" method="get"> 
									<input id="nav_pad" class="search" name="searchterm" type="text"/>
 								
						</form>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	<div class="grid_24" id="grid_margin">
		<div class="grid_19" id="campaign_info">
			<div class="grid_3 container" style="height:100%;">
			<ul id="tabs-titles">
    			<li class="current tabz">
    				<div id="brief">
    					<img src="graphics/brief.png" style="width:30px;margin: 0 0 5px 0;"/>
    				</div>
        			Brief
    			</li>
    			<li class="tabz">
    				<div id="feedback">
    					<img src="graphics/feedback.png" style="width:30px;margin: 0 0 5px 0;"/>
    				</div>
        			Feedback
    			</li>
    			<li class="tabz">
    				<div id="participants">
    					<img src="graphics/participants.png" style="width:30px;margin: 0 0 5px 0;"/>
    				</div>
        			Participants
    			</li>
			</ul>
		</div>	
			<ul id="tabs-contents">
   			 	<li>
        			<div class="content">
        				<div class="line">
        					<h1> Insert Title </h1>
        					<button class="btn btn-success" style="float:right;margin:-37px 0 1px 0;width:80px;"><p class="button_text">Add </p><div class="grid_1" id="bookmark">
								<img src="graphics/plus.png"/>
							</div></button>	
							<button class="btn btn-primary" style="float:right;margin:-37px 7px 1px 0;width:100px;"> <p class="button_text">Upload </p><div class="grid_1" id="upload" style="margin:0px 0 0 0;">
								<img src="graphics/upload_1.png"/>
							</div></button>	
        				
        				</div>
        				<p style="padding:10px 20px;"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys 
        					standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make 
        					a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, 
        				</p>	
        			</div>
    			</li>
    			<li>
       				 <div class="content">
       				 		<div class="line">
        					<h1> Insert Names Feedback </h1>
        					<button class="btn btn-success" style="float:right;margin:-37px 0 1px 0;width:80px;"><p class="button_text">Add </p><div class="grid_1" id="bookmark">
								<img src="graphics/plus.png"/>
							</div></button>	
							<button class="btn btn-primary" style="float:right;margin:-37px 7px 1px 0;width:100px;"> <p class="button_text">Upload </p><div class="grid_1" id="upload" style="margin:0px 0 0 0;">
								<img src="graphics/upload_1.png"/>
							</div></button>	
        				</div>
    			
       				 <div class="feedback">
        					<div class="grid_3" style="padding-top:3%;border-top:1px solid rgb(102,102,102);">
        						<p style="font-size:12px;"><u> Feedback from: </u> </p>
        						<p> Noah Willard </p>
        					</div>
        					<div class="grid_12" style="border-top:1px solid rgb(102,102,102);border-left:1px solid rgb(102,102,102);padding:5px 5px 5px 10px;margin-left:-15px;">
        						<p> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys 
        					standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make 
        					a type specimen book. I dsfasdfasd fasdfasd f asdf sdf sdf sdf </p>
        					</div>
        				</div>	
        				 <div class="feedback">
        					<div class="grid_3" style="padding-top:3%;border-top:1px solid rgb(102,102,102); ">
        						<p style="font-size:12px;"><u> Feedback from: </u> </p>
        						<p> Noah Willard </p>
        					</div>
        					<div class="grid_12" style="border-top:1px solid rgb(102,102,102);border-left:1px solid rgb(102,102,102);padding: 5px 5px 5px 10px;margin-left:-15px;">
        						<p> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys 
        					standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make 
        					a type specimen book. I </p>
        					</div>
        				</div>				
       				 </div>
    			</li>
    			<li>
        			<div class="content">
       				 		<div class="line">
        					<h1> Participating Photographers </h1>
        					<button class="btn btn-success" style="float:right;margin:-37px 0 1px 0;width:80px;"><p class="button_text">Add </p><div class="grid_1" id="bookmark">
								<img src="graphics/plus.png"/>
							</div></button>	
							<button class="btn btn-primary" style="float:right;margin:-37px 7px 1px 0;width:100px;"> <p class="button_text">Upload </p><div class="grid_1" id="upload" style="margin:0px 0 0 0;">
								<img src="graphics/upload_1.png"/>
							</div></button>	
        				</div>
        				

        			</div>
    			</li>
			</ul>
		</div>
		<div class="grid_4" style="height:100%;margin: 5px 0 5px 0;border-left: 1px #666;">
			<div class="grid_4 stats">
				<p> <span class="big_text"> $90 </span> </p>
				<p> Reward </p>
			</div>
			<div class="grid_4 stats">
				<p><span class="big_text"> 90 </p>
				<p> Entries </p>
				
			</div>
			<div class="grid_4 stats">
				
				<p><span class="big_text"> 1 </span> week<span class="big_text">  2  </span> days </p></span>
				<p> Left in Campaign </p>
			</div>
			<div class="grid_4 stats">
				
				<p> <span class="big_text">201 </p></span>
				<p> Views </p>
			</div>
		</div>
		<div class="grid_3" id="personal">
			
		</div>
	</div>';

} //end of id

?>

</div><!--end of 24 container-->

    <script src="js/bootstrap-dropdown.js"></script>
    <script type="text/javascript">  
        $(document).ready(function () {  
            $('.dropdown-toggle').dropdown();  
        });  
    </script> 
    
</body>
</html>