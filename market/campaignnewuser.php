<?php
require "functionscampaigns3.php"; 
require "db_connection.php";
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
 <meta name="description" content="Fill in the form for your new campaign">
 <meta name="keywords" content="campaign, new, photorankr, my">
 <meta name="author" content="The PhotoRankr Team">
	<title>Create a Campaign on PhotoRankr to get photos that match your needs</title>
	<link rel="stylesheet" href="css/bootstrapNew.css" type="text/css" />
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

<body style="background-image:url('graphics/NYC.jpg');background-size: 100%;
background-repeat:no-repeat;">
	
    <?php navbarsweet(); ?>
    
<!--START OF CONTAINER-->
<div class="container_24">

<div class="grid_24" style="width:960px;margin-left:auto;margin-right:auto;">


	<div class="grid_12 push_6" style="margin-top: 15%">
    <div class="well">
		<p style="font-size:22px;padding:6px;margin-top:10px; margin-left: 100px;">Create Your Account:</p>
<?php

if($_GET['error'] == 1) {
	echo "<div style='margin-top: -10px;'><span style='margin-left: 120px;font-size:12px;' class='label label-important'>Please fill in all required fields.</span></div><br />";
}
else if($_GET['error'] == 2) {
	echo "<div style='margin-top: -10px;'><span style='margin-left: 70px;font-size:12px;' class='label label-important'>This user/emailaddress already exists.</span></div><br />";
}
else if($_GET['error'] == 3) {
  echo "<div style='margin-top: -10px;'><span style='margin-left: 105px;font-size:12px;' class='label label-important'>Create an account to view this page.</span></div><br />";
}

?>
		<form method="post" action="createcampaign.php?signup=true">
			<div style="margin-left: 70px;">Email: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input placeholder="Email Address" type="text" name="repemail" /><br /></div>
			<div style="margin-left: 70px;">Password: <input type="password" name="password" /><br /></div>
			<input type="hidden" name="title" value="<?php echo htmlentities($_POST['title']); ?>" />
			<input type="hidden" name="budget" value="<?php echo htmlentities($_POST['budget']); ?>" />
			<input type="hidden" name="description" value="<?php echo htmlentities($_POST['description']); ?>" />
			<div style="margin-left: 205px;margin-top:6px;"><input class="btn btn-success" style="width:150px;height:35px;" type="submit" value="CREATE ACCOUNT" /></div>
		</form>
	</div><br/ >
    </div>
</div>
</div><!--/end of container-->
</body>
</html>
<?php

mysql_close();

?>