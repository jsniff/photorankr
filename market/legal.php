<?php

//connect to the database
require "db_connection.php";

//start the session
session_start();

require "functionscampaigns3.php"; 
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

	<link rel="stylesheet" href="css/bootstrapnew2.css" type="text/css" />
    <link rel="stylesheet" href="css/reset.css" type="text/css" />
    <link rel="stylesheet" href="css/text.css" type="text/css" />
    <link rel="stylesheet" href="css/960_24.css" type="text/css" />
    <link rel="stylesheet" href="css/index.css" type="text/css"/> 
    <link rel="stylesheet" href="css/itunes.css" type="text/css"/> 
	<link rel="stylesheet" type="text/css" href="css/all.css"/>
	
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
    <script src="http://twitter.github.com/bootstrap/1.4.0/bootstrap-twipsy.js"></script>
    <script src="http://twitter.github.com/bootstrap/1.4.0/bootstrap-popover.js"></script>
    <script src="bootstrap-dropdown.js" type="text/javascript"></script>
    <script src="bootstrap-collapse.js" type="text/javascript"></script>
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
.navbar-inner
{
	text-align:center;
}

.center.navbar .nav,
.center.navbar .nav > li {
    float:none;
    display:inline-block;
    *display:inline; /* ie7 fix */
    *zoom:1; /* hasLayout ie7 trigger */
    vertical-align: top;
}

.center .navbar-inner {
    text-align:center;
}
.navbar .nav,
.navbar .nav > li {
    float:none;
    display:inline-block;
    *display:inline; /* ie7 fix */
    *zoom:1; /* hasLayout ie7 trigger */
    vertical-align: top;
}
.center .dropdown-menu {
    text-align: left;
}

.statoverlay

{
opacity:.0;
filter:alpha(opacity=40);
z-index:1;
transition: opacity .5s;
-moz-transition: opacity .5s;
-webkit-transition: opacity .5s;
-o-transition: opacity .5s;
}
     
.statoverlay:hover
{
opacity:.7;

</style>

</head>

<body style="overflow-x:hidden;">

<?php navbarnew(); ?>

<div class="container_24">    
<br /><br /><br />    
<div class="grid_20 push_1" style="padding-right:15px;border-right:1px solid #aaa;">
<h1>Standard Content License Agreement</h1>
</br></br>
<p1>
This Agreement governs the terms by which members and clients of PhotoRankr, Inc. obtain the right to use photographic content provided by members of the PhotoRankr community through the web site located at <a href="www.photorankr.com">www.photorankr.com</a> (the &#34;Site&#34;).  This Content License Agreement is in addition to the Terms of Use applicable to the Site.  In the event of any inconsistency between this Agreement and the Terms of Use (both of which are incorporated into this Agreement by reference), the terms of this Agreement shall govern. 
</p1>



</br></br>
</br></br>
</div>
<div>
</br></br>
</div>


</div>  <!--end of container-->
</body>
</html>	
