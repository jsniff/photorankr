<?php

//connect to the database
require "../db_connection.php";
require "functions.php";

//start the session
session_start();

    // if login form has been submitted
    if(htmlentities($_GET['action']) == "login") { 
        login();
    }
    elseif(htmlentities($_GET['action']) == "logout") { 
        logout();
    }

    $email = $_SESSION['email'];
    
    //Time
    $time = time();

//QUERY FOR NOTIFICATIONS
$currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$email'";
$currentnotsquery = mysql_query($currentnots);
$currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");

//notifications query reset 
if($currentnotsresult > 0) {
$notsquery = "UPDATE userinfo SET notifications = 0 WHERE emailaddress = '$email6'";
$notsqueryrun = mysql_query($notsquery); }

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="https://www.w3.org/1999/xhtml">

<head>

 <meta name="Generator" content="EditPlus">
  <meta name="Author" content="PhotoRankr, PhotoRankr.com">
  <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
  <meta name="Description" content="A gallery of the newest photography, photographers, and exhibits on PhotoRankr.">
     <meta name="viewport" content="width=1200" /> 

	<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
	 <link rel="stylesheet" type="text/css" href="css/style.css"/>
	<link rel="stylesheet" type="text/css" href="css/960grid.css"/>
	<link rel="stylesheet" type="text/css" href="css/reset.css"/> 
    
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.wookmark.js"></script>            
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
  
  <title>PhotoRankr - Newest Photography</title>

<!--GOOGLE ANALYTICS CODE-->
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-28031297-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'https://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>


<style type="text/css">
.background {
opacity:.2;
}
</style>

</head>
<body style="overflow-x:hidden; background-color: #fff;">    
    
    <?php
            
            //Discover Photography
            $discoverquery = mysql_query("SELECT * FROM photos WHERE faves > 3 and time > ($time - 2000000) ORDER BY faves DESC LIMIT 0,18");
                        
            echo'<div style="height:33.8%;overflow:hidden;width:100%;">';
                        
            for($iii=0;$iii<6;$iii++) {
                $source = mysql_result($discoverquery,$iii,'source');
                $sourceThumb = str_replace("userphotos/","userphotos/medthumbs/", $source);
                $source = "../" . $source;
                list($width, $height) = getimagesize($source);
                $imgratio = $height / $width;
                $heightls = $height / 5;
                $widthls = $width / 5;
                
               echo'
						<img style="float:left;width:20%;" src="https://photorankr.com/',$sourceThumb,'" /> ';

            }
            
            echo'</div>';
            
            echo'<div style="height:33.3%;overflow:hidden;width:100%;">';
                        
            for($iii=6;$iii<12;$iii++) {
                 $source = mysql_result($discoverquery,$iii,'source');
                $sourceThumb = str_replace("userphotos/","userphotos/medthumbs/", $source);
                $source = "../" . $source;
                list($width, $height) = getimagesize($source);
                $imgratio = $height / $width;
                $heightls = $height / 5;
                $widthls = $width / 5;
                
               echo'
						<img style="float:left;width:20%;" src="https://photorankr.com/',$sourceThumb,'" /> ';

            }
            
            echo'</div>';
            
            echo'<div style="height:33.3%;overflow:hidden;width:100%;">';
                        
            for($iii=12;$iii<18;$iii++) {
                $source = mysql_result($discoverquery,$iii,'source');
                $sourceThumb = str_replace("userphotos/","userphotos/medthumbs/", $source);
                $source = "../" . $source;
                list($width, $height) = getimagesize($source);
                $imgratio = $height / $width;
                $heightls = $height / 5;
                $widthls = $width / 5;
                
               echo'
						<img style="float:left;width:20%;" src="https://photorankr.com/',$sourceThumb,'" /> ';

            }
            
            echo'</div>';
    ?>
        
    
    <!---------------Boxes------------->
    <div class="homeBG">
            <div class="bgColumn">
            </div>
           
            <div class="bgColumn">
            </div>
            
            <div class="bgColumn">
            </div>
    </div>
                        
                                        
</body>
</html>