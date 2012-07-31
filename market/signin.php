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

<!DOCTYPE HTML>
<html>
<head>
 <meta name="description" content="Contact the awesome PhotoRankr team about partnerships, ideas for improving the site, and promotions">
 <meta name="keywords" content="contact us, contact, improve, photorankr, campaigns">
 <meta name="author" content="The PhotoRankr Team">
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

<body style="background-color: #EEE"">

<!--NAVIGATION BAR-->
<?php navbarnew(); ?>

<div class="container">

<div class="grid_24">
<?php

if($_GET['action'] == disc) {
echo'<div class="well" style="font-size:18px;font-family:helvetica, arial;margin-top:50px;margin-left:60px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Discover great photography that pertains to your interests. Login below or <a href="signin.php">register today</a> to begin.</div>

<div style="margin-top:70px;margin-left:260px;padding-bottom:150px;">
<form name="login_form" method="post" action="discover.php?action=login">
<div class="well" style="width:380px;padding-top:50px;padding-bottom:50px;padding-left:40px;">
<span style="font-size:18px;font-family:helvetica, arial;margin-left:0px;">Email: </span><input type="text" style="width:200px;margin-left:40px;" name="emailaddress" /><br />
<span style="font-size:18px;font-family:helvetica, arial;">Password: </span>&nbsp<input type="password" style="width:200px;" name="password"/><br >
<input type="submit" class="btn btn-success" style="margin-left:250px;" value="sign in" id="loginButton"/>
</div>
</form>
</div>';
}

?>
</div>



</div>
</body>
</html>
