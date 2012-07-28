<?php

require "functionscampaigns.php";


?>
<!DOCTYPE html>
<html>
<head>
 <meta name="description" content="Create a new campaign to get the photo that matches your needs. Instead of you searching for the photo you want, the photographers are looking for you">
 <meta name="keywords" content="campaign, stock photos, photorankr, campaign, images">
 <meta name="author" content="The PhotoRankr team">
	<title>Create a Campaign on PhotoRankr to get photos that match your needs</title>
	<link rel="stylesheet" href="css/bootstrapnew.css" type="text/css" />
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

	<style type="text/css">

	.content 
 	 {
  		color:#000000;
  		font-size:16px;
  		z-index:3;
  		font-family: 'helvetica neue'; helvetica;
  	}

	div.transbox
  	{
        opacity:.7;
  		width:300px;
  		height:300px;
  		margin:30px -50px;
  		background-color:#ffffff;
        -webkit-border-radius: 20px;
        -moz-border-radius: 20px;
        border-radius: 20px;   
  		-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
  		filter:alpha(opacity=100); /* For IE8 and earlier */
  		z-index:1;
  		float:left;
  		font-family: 'helvetica neue'; helvetica;
  	}


	div.smalltransbox
  	{
  		width:270px;
  		height:130px;
  		margin:30px 0px;
  		background-color:#ffffff;
  		border:1px solid black;
  		opacity:1;
  		-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
  		filter:alpha(opacity=100); /* For IE8 and earlier */
  		z-index:1;
  		float:left;
  		font-family: 'helvetica neue'; helvetica;
  	}

  	div.bigtransbox
  	{
        opacity:.7;
  		width:1100px;
  		height:120px;
  		margin:30px -50px;
  		background-color:#ffffff;
        -webkit-border-radius: 20px;
        -moz-border-radius: 20px;
        border-radius: 20px;    
        rgb(0, 0, 0);   
        rgba(0, 0, 0, 0.6);
        filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);
        -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";
  		z-index:1;
  		font-family: 'helvetica neue'; helvetica;
  	}

  	</style>

</head>

<body style="background-image:url('graphics/statepark.jpg');background-size: 100%;
background-repeat:no-repeat;" >

<?php navbar(); ?>


<!--START OF CONTAINER-->
<div class="container">


<div class="grid_24 pull_2" style="padding:6px;">
<div class="bigtransbox">
</div>
<div style="position:relative;top:-155px;font-family:arial,gill sans, helvetica;">
	<span style="font-size:50px;margin-left:100px;font-weight:100;">Get the shot you need. Everytime.</span><br />
	<span style="font-size:16px;">With PhotoRankr campaigns just describe the photo, budget, and time frame you need. Photographers will then compete for your eye.<br />
		At the end of the competition, choose the photo you like the best.</span>
</div>

<div class="grid_24">
	<div class="transbox">
    </div>
    <div style="margin-top:-200px;">
	<p style="font-size:20px;padding:6px;">Start your campaign:</p>
	<form method="post" action="campaignnewuser.php?signup=true">
		<div class="content">
			Campaign Title: <br/><input style="margin-top:5px;" type="text" name="title" placeholder="Bridge over troubled water"/><br />

			Budget: <br />
			<span style="style="margin-top:5px;">
				<span class="add-on">$</span>
				<input id="appendedPrependedInput" class="span1" type="text" name="budget" size="16">
				<span class="add-on">.00</span>
			</span><br />

			Time frame: <br />
			<select class="span2" style="height:30px;" name="timeframe">
    		<option value="1">1 Day</option>
    		<option value="2">2 Days</option>
    		<option value="3">3 Days</option>
    		<option value="4">4 Days</option>
    		<option value="5">5 Days</option>
    		<option value="6">6 Days</option>
    		<option value="1week">One Week</option>
    		<option value="2weeks">Two Weeks</option>
    		<option value="1month">One Month</option>
   			</select>
			<br />
			<input class="btn btn-success" type="submit" value="Start Campaign" />
		</div>
    </div>
	</form>
</div>


<div class="grid_24 pull_2" style="width:1100px;">
	<div class="smalltransbox" style="margin-left:-50px;">
		<br />
		<span style="padding:15px;font-size:16px;">Freaking.</span>
	</div>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<div class="smalltransbox" style="margin-left:100px;">
		<br />
		<span style="padding:15px;font-size:16px;">Amazing.</span>
	</div>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<div class="smalltransbox" style="margin-left:100px;">
		<br />
		<span style="padding:15px;font-size:16px;">Page.</span>
	</div>
</div>



</div><!--/end of container-->
</body>
</html>