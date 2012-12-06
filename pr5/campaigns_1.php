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
    
?>    


<!DOCTYPE HTML>
<head>
	<meta charset="UTF-8">
	<title> Photographers, find work and challenge your creativity. Need a photo? Ask a netowork of photographers. </title>
	<link rel="stylesheet" href="css/camapaigns.css" type="text/css"/>
	<link rel="stylesheet" href="css/960_24_col.css" type="text/css"/>
	<link rel="stylesheet" href="css/bootstrapNew.css" type="text/css"/>
	<link rel="stylesheet" href="css/reset.css" />

	<style type="text/css" >
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
</head>
<body id="body">

<?php navbarnew(); ?>

<!--begin the container-->
<div class="container_24" id="container_margin">
	<div style="background-color:rgb(245,245,245);z-index:999;height:6.5em;width:100%;position:fixed;">
			<div class="navbar-inner" style="width:920px;min-height:0px;margin-left:-.25em;background:none;">
				<div class="container" id="subnav">
					<ul class="navbar">
						<li class="nav"> 
							<div class="subnav_button">
								<img src="graphics/buttonA.png" style="height:37px;border-radius: 5px 0 0 5px; "/>
								<h1 class="subnav_head"> Newest Campaigns </h1>
							</div>
						</li>
						<li class="nav" style="margin-left:-10px;">
							<div class="subnav_button">
								<img src="graphics/buttonB.png" style="height:37px;"/>
								<h1 class="subnav_head"> Trending Campaigns </h1>
							</div>
						</li>
						<li class="nav" style="margin-left:-10px;">
							<div class="subnav_button">
								<img src="graphics/buttonC.png" style="height:37px;"/>
								<h1 class="subnav_head"> My Campaigns </h1>
							</div>
						</li>
						<li class="nav margin_none" style="margin-left:-10px;;">
							<div class="subnav_button" >
								<img src="graphics/buttonD.png" style="height:37px;border-radius: 0 5px 5px 0;"/>
								<h1 class="subnav_head"> Sort Campaigns </h1>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	<div class="grid_24">		
	<div class="grid_11 pull_2" id="campaign_col_left" style="margin-left:4.4em;">
		<a href="campaign_view.php"> <div class="grid_12 campaign_bloc">
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
</div>



</body>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>	
	<script type="text/javascript" src="js/bootstrap-dropdown.js"></script>
			

<script type="text/javascript">  
        $(document).ready(function () {  
            $('.dropdown-toggle').dropdown();  
        });  
   </script> 
</html>	