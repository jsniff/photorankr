<!DOCTYPE html>
<html>
<head>
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
  		margin:30px 40px;
  		color:#000000;
  		font-size:16px;
  		z-index:3;
  		font-family: 'helvetica neue'; helvetica;
  	}

	div.transbox
  	{
  		width:300px;
  		height:300px;
  		margin:30px -50px;
  		background-color:#ffffff;
  		border:1px solid black;
  		opacity:1;
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
  		width:500px;
  		height:600px;
        font-family:'helvetica neue', helvetica, gill sans, arial;
  		margin-left:auto;
   		margin-right: auto;
  		text-align:center;
  		background-color:#fff;
  		border:1px solid black;
  		z-index:1;
  		font-family: 'helvetica neue'; helvetica;
  	}

  	</style>
</head>
<body>
<?php require "functionscampaigns.php"; navbar(); ?>
	<div id="container" class="container_24" style="margin-top: 30px;">
			<div class="grid_24" style="text-align: center;">
					<h1 style="color: black; font-size-adjust: .51; text-shadow: 1px 1px 0pt rgb(205, 205, 205); font-weight:200; font-size: 60px; font-family: 'Futura Condensed Bold','Arial Narrow','Gill Sans',Arial,'Helvetica Neue',Helvetica,sans-serif">CROWD-SOURCE CREATIVITY</h1>
					<h2 style="line-height: 30px;">Leverage a network of photographers ready to showcase their work. Describe the photo you need and the budget you have. At the end of the campaign, choose the photo that best matches your needs. Pay only if you choose a photo.</h2>
			</div>
			<div class="grid_24">

			</div>
	</div>
</body>
</html>