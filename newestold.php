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


$query="SELECT * FROM photos ORDER BY id DESC LIMIT 0, 16";
$result=mysql_query($query);
$numberofpics=mysql_num_rows($result);

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


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

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
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="http://masonry.desandro.com/jquery.masonry.min.js"></script>
<script type="text/javascript" src="https://raw.github.com/desandro/imagesloaded/master/jquery.imagesloaded.min.js"></script>
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
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
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

if(isset($_GET['view'])){
$view = htmlentities($_GET['view']);
}

        echo'<br /><br /><br /><br /><div style="text-align:center;font-size:14px;font-weight:200;"><div style="margin-left:20px;"><a class="green" style="text-decoration:none;color:#000;';if($view == '') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="newest.php">Newest Photos</a> | <a class="green" style="text-decoration:none;color:#000;';if($view == 'prs') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="newest.php?view=prs">Newest Photographers</a> | <a class="green" style="text-decoration:none;color:#000;';if($view == 'exts') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="newest.php?view=exts">Newest Exhibits</a></div></div>';
        
        
if($view == '') {
        
    //DISPLAY 20 NEWEST OF ALL PHOTOS
         
    echo'<div id="thepics">';
    echo'<div id="container" style="width:1210px;margin-left:-112px;top:15px;">';
    
for($iii=1; $iii <= 16; $iii++) {
	$image = mysql_result($result, $iii-1, "source");
    $imageThumb=str_replace("userphotos/","userphotos/medthumbs/", $image);
	$id = mysql_result($result, $iii-1, "id");
    $caption = mysql_result($result, $iii-1, "caption");
     $caption = (strlen($caption) > 28) ? substr($caption,0,25). " &#8230;" : $caption;
    $points = mysql_result($result, $iii-1, "points");
    $votes = mysql_result($result, $iii-1, "votes");
    $score = number_format(($points/$votes),2);
    $owner = mysql_result($result, $iii-1, "emailaddress");
    $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$owner'");
    $firstname = mysql_result($ownerquery, 0, "firstname");
    $lastname = mysql_result($ownerquery, 0, "lastname");
    $fullname = $firstname . " " . $lastname;

	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
    $heightls = $height / 2.5;
    $widthls = $width / 2.5;

		echo '<div class="fPic" id="',$id,'" style="float:left;margin-right:20px;margin-top:20px;width:280px;height:280px;overflow:hidden;"><a style="text-decoration:none;" href="http://photorankr.com/fullsize.php?image=',$image,'&v=n">
        
          <div class="statoverlay" style="z-index:1;left:0px;top:240px;position:relative;background-color:black;width:280px;height:40px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-family:helvetica neue,arial;font-weight:100;font-size:20px;">',$caption,'</span><br/></div>
        
        <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-75px;min-height:300px;min-width:280px;" src="http://photorankr.com/',$imageThumb,'" alt="',$caption,'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
       
	    
      } //end for loop
      echo'</div>';
            echo'</div>';


echo'
<!--AJAX CODE HERE-->
   <div class="grid_6 push_9" style="padding-top:50px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;">Loading More Photos&hellip;</div>
   </div>';


echo '<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMorePics").show();
				$.ajax({
					url: "loadMoreNewPics.php?lastPicture=" + $(".fPic:last").attr("id"),
					success: function(html) {
						if(html) {
							$("#thepics").append(html);
							$("div#loadMorePics").hide();
						}
					}
				});
				last = $(".fPic:last").attr("id");
			}
		}
	});
</script>';

} //end of view == ''



elseif($view == 'prs') {
$prsquery="SELECT * FROM userinfo WHERE (profilepic != 'http://www.photorankr.com/profilepics/default_profile.jpg' AND profilepic != 'profilepics/default_profile.jpg') ORDER BY user_id DESC";
$prsresult=mysql_query($prsquery);

echo'<div id="container" style="width:1210px;margin-left:-112px;top:15px;">';
for($iii=1; $iii <= 16; $iii++) {
	$profpic = mysql_result($prsresult, $iii-1, "profilepic");
    if($profpic == 'http://www.photorankr.com/profilepics/default_profile.jpg') {
    $profpic = 'profilepics/default_profile.jpg';
    }
    $firstname = mysql_result($prsresult, $iii-1, "firstname");
	$lastname = mysql_result($prsresult, $iii-1, "lastname");
    $fullname = $firstname . " " . $lastname;
    $fullname = ucwords($fullname);
	$userid = mysql_result($prsresult, $iii-1, "user_id");

		echo '<div class="fPic" id="',$id,'" style="float:left;margin-right:20px;margin-top:20px;width:280px;height:280px;overflow:hidden;"><a style="text-decoration:none;" href="viewprofile.php?u=',$userid,'">
        
        <div class="statoverlay" style="z-index:1;left:0px;top:215px;position:relative;background-color:black;width:280px;height:40px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-family:helvetica neue,arial;font-weight:100;font-size:22px;">',$fullname,'</span></div>
        
        <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-75px;min-height:290px;min-width:280px;" src="',$profpic,'" alt="',$fullname,'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';

    } //end for loop
    echo'</div>';
    
} //end of view == 'prs'



elseif($view == 'exts') {
$query="SELECT * FROM sets ORDER BY id DESC LIMIT 0,30";
$result=mysql_query($query);
$numberexhibits=mysql_num_rows($result);

echo'<div id="container" style="width:1210px;margin-left:-112px;top:15px;">';
for($iii=1; $iii <= $numberexhibits; $iii++) {
	$coverpic = mysql_result($result, $iii-1, "cover");
    $caption = mysql_result($result, $iii-1, "title");
    $set_id = mysql_result($result, $iii-1, "id");
    if($coverpic == '') {
    $coverpic = 'graphics/no_cover.png';
    }
    $owner = mysql_result($result, $iii-1, "owner");
    $exhibitquery = mysql_query("SELECT * FROM photos WHERE set_id = '$set_id'");
    $numberphotos = mysql_num_rows($exhibitquery);
   
    for($i = 0; $i < $numberphotos; $i++) {
    $points += mysql_result($exhibitquery, $i, "points");
    $votes += mysql_result($exhibitquery, $i, "votes");
    }
    
    $score = number_format(($points/$votes),2);
    
    $avgscorequery = mysql_query("UPDATE sets SET avgscore = '$score' WHERE id = '$set_id'");
    
    $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$owner'");
    $firstname = mysql_result($ownerquery, 0, "firstname");
    $lastname = mysql_result($ownerquery, 0, "lastname");
    $fullname = $firstname . " " . $lastname;
    
	$userid = mysql_result($ownerquery, 0, "user_id");
    
    echo'
    <div class="fPic" id="',$id,'" style="float:left;margin-right:20px;margin-top:20px;width:280px;height:280px;overflow:hidden;"><a style="text-decoration:none;" href="http://photorankr.com/viewprofile.php?u=',$userid,'&view=exhibits&set=',$set_id,'">
        
        <div class="statoverlay" style="z-index:1;left:0px;top:210px;position:relative;background-color:black;width:280px;height:70px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-family:helvetica neue,arial;font-weight:100;font-size:18px;">',$caption,'</span><br/><span style="font-family:helvetica,arial;font-weight:100;font-size:12px;">By: ',$fullname,'</p></div>
        
        <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-75px;min-height:290px;min-width:280px;" src="http://photorankr.com/',$coverpic,'" alt="',$caption,'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
        
    } //end for loop
    echo'</div>';

} //end of view == 'exts'

?>

</div>
<br /><br />

<?php footer(); ?>

</body>
</html>