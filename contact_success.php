


<?php

//connect to the database
require "db_connection.php";
require "functionsnav.php";

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
	<meta charest="UTF-8">
	<title>PhotoRankr - The World's First Social Marketplace for Photography</title>
	<link href="css/960_24_col.css" rel="stylesheet" type="text/css"/>
	<link href="css/bootstrapNew.css" rel="stylesheet" type="text/css"/>
	<link href="css/new.css" rel="stylesheet" type="text/css"/>
	<link href="css/reset.css" rel="stylesheet" type="text/css"/>
	<link href='http://fonts.googleapis.com/css?family=Codystar' rel='stylesheet' type='text/css'>
	<style type="text/css">


	</style>
</head>
<body style="background:#f5f5f5;">
<?php navbarnew(); ?>
<!--Container Begins here-->
<div class="container_24" style="margin-top:60px;min-height:90%;">	
	<div class="grid_24 pull_2">
	<div class="grid_24">
		<div class="grid_10">
		<div class="grid_12 pull_1" style="color:#000;border-left: 6px solid #6bab4c;border-bottom: 6px solid  #6bab4c;padding:0 2em 2em 2em;margin-top:1em;">
			<div class="grid_15" id="send_card">
				<p>Send the PhotoRankr Team</p>
			</div>
			<div class="grid_15">
				<p id="card"><span id="big_a">A</span>&nbsp Post Card</p>
			</div>
			<div class="grid_16" style="margin-top:-1em;">
				<div class="grid_4 pull_1" id="arrow"><img src="graphics/arrows.png"/></div>
			<div class="grid_8 pull_1" style="margin-top:4em;float-right;">
				<h1>Need Help? We've Got You Covered</h1>
				<div class="grid_7 why_send"></div>
				<p class="send_text">If you have a problem, please email us! We will
					do everything we can to solve it for you. It's our job and 
					we love doing it. </p>
			</div>
			<div class="grid_8 pull_1" style="margin-top:.25em;float-right;">
				<h1>Your Suggestion Mean Everything</h1>
				<div class="grid_7 why_send"></div>
				<p class="send_text">Your suggestions make PhotoRankr a better
				place. If you have any for us, please send them 
				to us! 
				</p>
			</div>
			<div class="grid_8 pull_1" style="margin-top:.25em;float-right;">
				<h1>Just Say Hello</h1>
				<div class="grid_7 why_send"></div>
				<p class="send_text">We enjoy hearing from you all. Knowing 
				that you love using PhotoRankr makes the 
				long nights worth it.</p>
			</div>
		</div>
		</div>	
		</div>	
		<div class="grid_12" style="float:right;margin-top:5.25em;">
		<div class="grid_13 push_2"> 
			<div class="grid_15">
				<div style="width:55em;border-radius:3px;margin-left:-6px;"></div>
			</div>		

				<!--make the contact form look as if you are sending a postcard to photorankr-->

		<div class="grid_14"> <img src="graphics/letter.png"/>
				
		 <!--<div class="grid_5" style="background-color:#aaa;height:14.2em;margin-left:-.7em;padding-right:.2em;">-->
		 		
		 </div>	
		</div>
	</div>	
</div>
</div>
</div>
</div>
</div>


<?php footer(); ?>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>	
	<script type="text/javascript" src="js/bootstrap-dropdown.js"></script>
			

<script type="text/javascript">  
        $(document).ready(function () {  
            $('.dropdown-toggle').dropdown();  
        });  



   </script> 
</body>
</html>	

