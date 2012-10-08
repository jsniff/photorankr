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
<html>
<head>
	<meta charset="UTF-8">
	<title>Photographers, find work and challenge your creativity. Need a photo? Ask a netowork of photographers.</title> 
	<link rel="stylesheet" href="css/custom_grid.css" />	
	<link rel="stylesheet" href="css/960_24_col.css" />
	<link rel="stylesheet" href="css/bootstrap.css"/>
	<link rel="stylesheet" href="css/campaign_view.css"/>
	<link rel="stylesheet" href="css/reset.css"/>
	<style type="text/css">

	</style>
<body id="body">

<?php navbarnew(); ?>

<div class="container_24" id="container_margin">
	<div style="background-color:rgb(245,245,245);z-index:999;height:6.5em;width:100%;position:fixed;">
			<div class="navbar-inner" style="width:920px;min-height:0px;margin-left:-.25em;background:none;">
				<div class="container" id="subnav">
					<ul class="navbar">
						<li class="nav"> 
							<a href="campaigns.php"> <div class="subnav_button">
								<img src="graphics/buttonA.png" style="height:37px;border-radius: 5px 0 0 5px; "/>
								<h1 class="subnav_head"> Newest Campaigns </h1>
							</div></a>
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
        				<p style="padding:10px 20px;"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's 
        					standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make 
        					a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, 
        				</p>	
        			</div>
    			</li>
    			<li>
       				 <div class="content">
       				 		<div class="line">
        					<h1> Insert Name's Feedback </h1>
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
        						<p> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's 
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
        						<p> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's 
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
	</div>			





</body>
 <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>	
	<script type="text/javascript" src="js/bootstrap-dropdown.js"></script>
<script type="text/javascript">
	var tabs = $('#tabs-titles li'); //grab tabs
var contents = $('#tabs-contents li'); //grab contents

tabs.bind('click',function(){
  contents.hide(); //hide all contents
  tabs.removeClass('active');
tabs.removeClass('current');    //remove 'current' classes
  $(contents[$(this).index()]).show(); //show tab content that matches tab title index
  $(this).addClass('active');
$(this).addClass('current');    //add current class on clicked tab title
});
</script>			

<script type="text/javascript">  
        $(document).ready(function () {  
            $('.dropdown-toggle').dropdown();  
        });  
   </script> 
  </html> 

