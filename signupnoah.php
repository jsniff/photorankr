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

?>

<!DOCTYPE html>
<html>
	<head>
		<title>PhotoRankr - Newest Photography</title>

		  <link rel="stylesheet" type="text/css" href="css/bootstrapNew.css" />
    <link rel="stylesheet" href="960_24.css" type="text/css" />
    <link rel="stylesheet" href="css/style.css" type="text/css" />
  	<link rel="stylesheet" href="text2.css" type="text/css" />
	<link rel="stylesheet" type="text/css" href="css/all.css"/>

  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script src="bootstrap.js" type="text/javascript"></script>
  <script src="bootstrap-dropdown.js" type="text/javascript"></script>
  <script src="bootstrap-collapse.js" type="text/javascript"></script>
  <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>

		<style type="text/css">
			.btn-signup 
			{
  				background-color: hsl(101, 55%, 52%) !important;
  				background-repeat: repeat-x;
  				filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#a9de90", endColorstr="#6bc741");
  				background-image: -khtml-gradient(linear, left top, left bottom, from(#a9de90), to(#6bc741));
  				background-image: -moz-linear-gradient(top, #a9de90, #6bc741);
  				background-image: -ms-linear-gradient(top, #a9de90, #6bc741);
  				background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #a9de90), color-stop(100%, #6bc741));
  				background-image: -webkit-linear-gradient(top, #a9de90, #6bc741);
  				background-image: -o-linear-gradient(top, #a9de90, #6bc741);
 				background-image: linear-gradient(#a9de90, #6bc741);
  				border-color: #6bc741 #6bc741 hsl(101, 55%, 47%);
  				color: #fff !important;
  				text-shadow: 0 1px 1px rgba(102, 102, 102, 0.88);
  				-webkit-font-smoothing: antialiased;
  				padding:1em 2em 1em 2em;
  				font-weight: 500;

}
		.btn-explore {
 			 background-color: hsl(0, 0%, 31%) !important;
  			background-repeat: repeat-x;
  			filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#828282", endColorstr="#4f4f4f");
  			background-image: -khtml-gradient(linear, left top, left bottom, from(#828282), to(#4f4f4f));
  			background-image: -moz-linear-gradient(top, #828282, #4f4f4f);
  			background-image: -ms-linear-gradient(top, #828282, #4f4f4f);
  			background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #828282), color-stop(100%, #4f4f4f));
  			background-image: -webkit-linear-gradient(top, #828282, #4f4f4f);
  			background-image: -o-linear-gradient(top, #828282, #4f4f4f);
  			background-image: linear-gradient(#828282, #4f4f4f);
 			border-color: #4f4f4f #4f4f4f hsl(0, 0%, 26%);
 			color: #fff !important;
  			text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.33);
  			-webkit-font-smoothing: antialiased;
  			padding:1em 2em 1em 2em;
}
.btn-go {
    background-color: hsl(207, 55%, 46%) !important;
  background-repeat: repeat-x;
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#68a3d3", endColorstr="#347bb5");
  background-image: -khtml-gradient(linear, left top, left bottom, from(#68a3d3), to(#347bb5));
  background-image: -moz-linear-gradient(top, #68a3d3, #347bb5);
  background-image: -ms-linear-gradient(top, #68a3d3, #347bb5);
  background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #68a3d3), color-stop(100%, #347bb5));
  background-image: -webkit-linear-gradient(top, #68a3d3, #347bb5);
  background-image: -o-linear-gradient(top, #68a3d3, #347bb5);
  background-image: linear-gradient(#68a3d3, #347bb5);
  border-color: #347bb5 #347bb5 hsl(207, 55%, 42%);
  color: #fff !important;
  text-shadow: 0 1px 1px rgba(255, 255, 255, 0.26);
  -webkit-font-smoothing: antialiased;
  padding:.5em 1.6em .5em 1.6em;
  font-weight:700;
  font-size:14px;
  margin-left: .1em;
}


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


	</head>
<body style="overflow-x:hidden;min-width:1220px;background:url('img/stormysky.jpg');background-size:100%;background-repeat:no-repeat;background-position:0% 35%;">
<?php navbarnew(); ?>  
<!--end navabar here-->
<!--container begin-->
<div id="topbar">
	<h1 id="bigtext"> You are almost ready to join one awesome network. </h1>
	</div>	
<div class="container_24">
	<div class="grid_22 push_1" id="formshell">
		<div class="grid_11" id="form">
		<!--<form action="none" method="">-->
			<h1 id="formhead"> Create your PhotoRankr profile </h1>
			<fieldset id="formfields">
				<!--<legend id="legend"> Create your profile </legend>-->
				<div style="width:20em;margin-bottom:-3em;">				
				<label class="form_label" for="first_name"> First name: </label>
				<input style="padding:.5em;border: .1em dotted #999;" id="firstN" type="text" name="first_name" size="25"/>

				<label class="form_label" id="lastNa" style="float:right;" for="last_name"> Last Name: </label>
				<input style="padding:.5em;border: .1em dotted #999;"  id="lastN" type="text" name="last_name" size="25"/>
				<br />
				</div>
				<div>
				<label class="form_label" for="email"> Email: </label>
				<input style="padding:.5em;border: .1em dotted #999;width:21em;font-size:16px;" type="text" name="email" size="50"/>
				<br />
				</div>
				<div>
				<label class="form_label" for="password"> Password </label>
				<input style="padding:.5em;border: .1em dotted #999;width:21em;font-size:16px;" type="password" name="password"/>
				<br />
				</div>
			<div>
				<label class="form_label" for="confirm_password"> Confirm Password</label>
				<input style="padding:.5em;border: .1em dotted #999;width:21em;font-size:16px;" type="password" name="confirm_password"/> 
			</div>
			<br />
				<input type="checkbox" name="terms" value="agree"/> <span class="form_label">I have read and agree with the <a href="terms.php"> Terms and Conditons. </a></span>
			<div>
				</fieldset>
				<div style="margin:1em 0 0 em;"><button class="btn btn-go centerbtn" style="padding:.8em 10em;font-size:18px;margin: 1em 0 ;"> <!--<input type="submit" style="display:none;"/>--> Join </button>
			</form>		
		</div>
	</div>	
	<div class="grid_9" id="features">
		<h1 id="description" class="borderB" style="padding:0;"> Your PhotoRankr profile gets you: </h1>
		<div class="upload">
			<div class="borderB">
				<img src="graphics/network.png" style="width:100px; float-left;"/>
				<p class="infographic"> The Photographer's Social Network. </p> 
				<p class="smalltext"> Rank and fave photos, follow photographers, and share your knowledge in a personal blog.</p>
			</div>
					<div class="borderB">
				<img src="graphics/cash.png" style="width:110px;padding:10px;"/>
				<p class="infographic"> Unlimited Submissions to PhotoRankr's Marketplace. Keep 70% of each sale. </p>
				<p class="smalltext" style="width:21em;margin-top:.3em;"> Name your price and sumbit any photo you upload to PhotoRankr's stock image market or a campaign.</p>
			</div>
			<div class="borderB">
				<img src="graphics/drag.png" style="width:80px;padding:0 5px;"/>
				<img src="graphics/license.png" style="width:80px;padding:0 5px;"/>
				<img src="graphics/upload.png" style="width:60px;padding:0 15px;"/>
			<p class="infographic"> A Simple Drag and Drop Uploader. </p>
				<p class="smalltext"> Upload, keyword, and license your photos in one place. </p>
			</div>	
			<div class="">
				<img src="graphics/frame.png" style="width:100px;padding:10px 10px;float:left;"/>
				<p class="infographic"> Unlimited Uploads and A Personal Store. </p>
				<p class="smalltext" style="width:15em;margin-left:11em;"> Transactions take place on your profile. At a price you name.</p>
			</div>
	
			</div>
		
		</div>
	</div>
	<!--<div class="grid_22" style="text-align:center;">
	<button class="btn btn-signup"/>Become a PhotoRankr Beta Tester</button>
		<p style="font-size:9px;"> (You get access to cool things)</p>-->
</div>
</div>
<footer class="footer" style="background-color:#c2c2c2;height:40px;margin-top:4em;">
    <div class="container" style="margin:0 auto;">
        <div class="row">
                <ul>
                    <li ><a class="nodec"href="about.php"> About </a> </li>
                    <li ><a class="nodec"href="contact.php">Contact </a></li>
                    <li ><a class="nodec"href="help.php">Help </a></li>
                </ul>
            </div>
  
        </div>
    </div>
</footer>








	</body>
</html>	
