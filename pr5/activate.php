<?php

//connect to the database
require "db_connection.php";
require "functions.php";
require "timefunction.php";
    
    // if login form has been submitted
    if (htmlentities($_GET['action']) == "login") { 
        login();
    }
    else if(htmlentities($_GET['action']) == "logout") { 
        logout();
    }

    //start the session
    @session_start();
    $email = $_SESSION['email'];
    $currenttime = time();
    
    
    if (isset($_GET['email']) && preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/',
 $_GET['email'])) {
 $emailaddress = $_GET['email'];
}
if (isset($_GET['key']) && (strlen($_GET['key']) == 32))
 //The Activation key will always be 32 since it is MD5 Hash
 {
 $key = $_GET['key'];
}

if (isset($emailaddress) && isset($key)) {

 // Update the database to set the "activation" field to null

 $query_activate_account = "UPDATE userinfo SET activation = NULL WHERE (emailaddress ='$emailaddress' AND activation='$key') LIMIT 1";
 $result_activate_account = mysql_query($query_activate_account);

 // Print a customized message:
 if ($result_activate_account) //if update query was successfull
 {
 
 header('Location: http://photorankr.com/index.php?action=activated');

 } else {
 echo '<div style="color:#000;font-size:28px;font-weight:300;">Oops !Your account could not be activated. Please recheck the link or contact the system administrator.</div>';

 }

 mysql_close();

} else {
 echo '<div>Error Occured .</div>';
}
    
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    
  <title>CollegeCambio - By Students, For Students</title>

  <meta name="Generator" content="EditPlus">
  <meta name="Author" content="CollegeCambio, CollegeCambio.com">
  <meta name="Keywords" content="student marketplace, fund books, find rides, find housing, find tutors, textbooks, rides, housing, find dorm items, dorm items">
  <meta name="Description" content="An exclusive college marketplace for the College of William & Mary">
  <meta name="viewport" content="width=1200" />

  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
  <link rel="stylesheet" type="text/css" href="css/960grid.css" />
  <link rel="stylesheet" type="text/css" href="css/style.css" />
   <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script src="js/bootstrap.js" type="text/javascript"></script>
  <script src="js/bootstrap-dropdown.js" type="text/javascript"></script>
  <script src="js/bootstrap-collapse.js" type="text/javascript"></script>
  <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
  
  
<style type="text/css">
  
  .box {
  
  padding:10px;-webkit-border-radius: 4px;
-moz-border-radius: 4px;
border-radius: 4px;
border-top-left-radius: 4px;
border-top-right-radius: 4px;
border-bottom-right-radius: 4px;
border-bottom-left-radius: 4px;
background: white;
background: -webkit-gradient(linear,left top,left bottom,color-stop(0%,white),color-stop(100%,#DDD));
background: -webkit-linear-gradient(top,white 0,#DDD 100%);
background: -moz-linear-gradient(top,white 0,#DDD 100%);
background: -ms-linear-gradient(top,white 0,#DDD 100%);
background: -o-linear-gradient(top,white 0,#DDD 100%);
background: linear-gradient(top,white 0,#DDD 100%);
filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='white',endColorstr='#DDD',GradientType=0);
border-left: solid 1px #EEE;
border-right: solid 1px #EEE;
border-bottom: solid 1px #CCC;
-webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, .1);
-moz-box-shadow: 0 1px 0 rgba(0, 0, 0, .1);
box-shadow: 0 1px 0 rgba(0, 0, 0, .1);
  
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

</head>

<body style="overflow-x:hidden; background-image: url('images/wren.jpg'); background-size:100%;">

<?php navbarnew(); ?>

<div class="container_24">


</div> <!--end of container-->

</body>
</html>