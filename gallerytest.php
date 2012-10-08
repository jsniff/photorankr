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

//QUERY FOR NOTIFICATIONS
$currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$email'";
$currentnotsquery = mysql_query($currentnots);
$currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");

//notifications query reset 
if($currentnotsresult > 0) {
$notsquery = "UPDATE userinfo SET notifications = 0 WHERE emailaddress = '$email6'";
$notsqueryrun = mysql_query($notsquery); }

//DISCOVER SCRIPT
    
  //get the users information from the database
  $likesquery = "SELECT * FROM userinfo WHERE emailaddress='$email'";
  $likesresult = mysql_query($likesquery) or die(mysql_error());
  $discoverseen = mysql_result($likesresult, 0, "discoverseen");

  //find out what they like
  $likes = mysql_result($likesresult, 0, "viewLikes");
    if($likes=="") {
		$nolikes = 1;
        		
	}

  $likes .= "  ";
  $likes .= mysql_result($likesresult, 0, "buyLikes");

  //create an array from what they like
  $likesArray = explode("  ", $likes);

  //loop through the array to format the likes in the proper format for the query
  $formattedLikes = "%";
  for($iii=0; $iii < count($likesArray); $iii++) {
    $formattedLikes .= $likesArray[$iii];
    $formattedLikes .= "%";
  }

    //make an array of the photos they have already seen
  if($discoverseen != "") {
    $discoverArray = explode(" ", $discoverseen);
    $discoverFormatted = "";
    for($iii=0; $iii < count($discoverArray)-1; $iii++) {
      $discoverFormatted .= "'";
      $discoverFormatted .= $discoverArray[$iii];
      $discoverFormatted .= "', ";
    }
    $discoverFormatted .= "'";
    $discoverFormatted .= $discoverArray[count($discoverArray)-1];
    $discoverFormatted .= "'";
  }
  
  //select the image that they will be seeing next
  //delineate between whether they have used discover feature before
  if($discoverseen != "") {     //get the photos that match this person's view interests
    $viewquery = "SELECT *, MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$formattedLikes') AS matching FROM photos WHERE MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$formattedLikes') AND id NOT IN(" . $discoverFormatted . ") ORDER BY matching DESC, points DESC LIMIT 0, 1";
    $viewresult = mysql_query($viewquery) or die(mysql_error());
  }
  else {
    //get the photos that match this person's view interests
    $viewquery = "SELECT *, MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$formattedLikes') AS matching FROM photos WHERE MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$formattedLikes') ORDER BY matching DESC, points DESC LIMIT 0, 1";
    $viewresult = mysql_query($viewquery) or die(mysql_error());
  }

  $discoverimage = mysql_result($viewresult, 0, "id");
  
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="https://www.w3.org/1999/xhtml">

<head>

 <meta name="Generator" content="EditPlus">
  <meta name="Author" content="PhotoRankr, PhotoRankr.com">
  <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
  <meta name="Description" content="A gallery of the newest photography, photographers, and exhibits on PhotoRankr.">
     <meta name="viewport" content="width=1200" /> 


  <link rel="stylesheet" type="text/css" href="market/css/bootstrapNew.css" />
  <link rel="stylesheet" href="market/css/reset.css" type="text/css" />
  <link rel="stylesheet" href="market/css/text.css" type="text/css" />
  <link rel="stylesheet" href="css/style.css" type="text/css" />
  <link rel="stylesheet" href="960_24.css" type="text/css" />
  <link rel="stylesheet" type="text/css" href="market/css/all.css"/>              
  <script type="text/javascript" href="js/bootstrap-dropdown.js"></script>
  <script type="text/javascript">
    document.write("\<script src='//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js' type='text/javascript'>\<\/script>");
  </script>  
  <script type="text/javascript" src="js/jquery.wookmark.js"></script>        
  <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
  
  <title>PhotoRankr - Newest Photography</title>

  
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


 .statoverlay

{
opacity:.7;
filter:alpha(opacity=40);
z-index:1;
transition: opacity .5s;
-moz-transition: opacity .5s;
-webkit-transition: opacity .5s;
-o-transition: opacity .5s;
}



 .statoverlay2

{
opacity:.6;
filter:alpha(opacity=40);
z-index:1;
transition: opacity .5s;
-moz-transition: opacity .5s;
-webkit-transition: opacity .5s;
-o-transition: opacity .5s;
}
                         

.item {
  margin: 10px;
  float: left;
  border: 2px solid transparent;
}

.item:hover {
  margin: 10px;
  float: left;
  border: 2px solid black;
}

</style>

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


</head>
<body style="overflow-x:hidden; background-color: #eeeff3;min-width:1220px;">

<?php navbarnew(); ?>

   <!--big container-->
    <div id="container" class="container_24" >
    

<!--DIFFERENT GALLERY VIEWS-->

<?php  

	$query = mysql_query("SELECT * FROM photos ORDER BY id DESC LIMIT 1000");
	$count = 1;
    
    echo'<div style="width:1060px;margin-left:-50px;margin-top:80px;">';
    
	for($iii=0; $iii<1000; $iii++) {
		$source = mysql_result($query,$iii,'source');
        $source = str_replace('userphotos/','userphotos/medthumbs/',$source);
		$width = mysql_result($query,$iii,'width');
		$height = mysql_result($query,$iii,'height');
        $points = mysql_result($query,$iii,'points');
        $faves = mysql_result($query,$iii,'faves');
		
		$imgratio = $width/$height;
        
        if($faves < 2 || $points < 30) {
            continue;
        }
		
		if($imgratio > 1.5 && $imgratio < 2) {
			echo'<div style="width:250px;height:330px;overflow:hidden;float:left;margin-top:20px;"><img class="frame" style="min-height:300px;" src="',$source,'" />
            <div class="largebox">Caption goes here</div>
            </div>';
		}

		if($imgratio > 1 && $imgratio < 1.5) {
			echo'<div style="width:500px;height:380px;overflow:hidden;float:left;margin-left:20px;margin-top:20px;"><img class="frame" style="min-height:300px;" src="',$source,'" />
            <div class="largebox">Caption goes here</div>
            </div>';
            
		}
        
        if($imgratio > 2.5) {
            echo'<div style="width:1000px;height:280px;overflow:hidden;float:left;margin-left:20px;margin-top:20px;"><img class="frame" style="min-height:300px;" src="',$source,'" />
            <div class="largebox">Caption goes here</div>
            </div>';
        }
        	
	}

    echo'</div>';

?>

</div>

</body>
</html>