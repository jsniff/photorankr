<?php

require "db_connection.php";
require "functions.php";

//start the session
session_start();

    // if login form has been submitted
    if (htmlentities($_GET['action']) == "login") { 
        login();
    }
    else if(htmlentities($_GET['action']) == "logout") { 
        logout();
    }
    
    
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

    $useremail = $_SESSION['email'];
    
  //get the users information from the database
  $likesquery = "SELECT * FROM userinfo WHERE emailaddress='$useremail'";
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

  <link rel="stylesheet" type="text/css" href="market/css/bootstrapnew2.css" />
 <link rel="stylesheet" href="market/css/reset.css" type="text/css" />
  <link rel="stylesheet" href="market/css/text.css" type="text/css" />
   <link rel="stylesheet" href="market/css/style.css" type="text/css" />
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
opacity:.0;
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
            
.statoverlay:hover
{
opacity:.7;
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
<body style="overflow-x:hidden; background-color: #fff; min-width:1220px;">


<?php navbarnew(); ?>

   <!--big container-->
    <div id="container" class="container_24" >
    
<div class="grid_3" style="position:fixed;margin-left:955px;">

<?php

if($_SESSION['loggedin'] == 1) {

$profilequery = mysql_query("SELECT profilepic,firstname,lastname FROM userinfo WHERE emailaddress = '$email'");
$profilepic=mysql_result($profilequery, 0, "profilepic");
$firstname=mysql_result($profilequery, 0, "firstname");
$lastname=mysql_result($profilequery, 0, "lastname");
$fullname = $firstname . " " . $lastname;
$shortname = (strlen($fullname) > 19) ? substr($fullname,0,19) : $fullname;

$followingquery = mysql_query("SELECT following FROM userinfo WHERE emailaddress = '$email'");
$followinglist=mysql_result($followingquery, 0, "following");
$followingquery=mysql_query("SELECT * FROM userinfo WHERE emailaddress IN ($followinglist)");
$numfollowing = mysql_num_rows($followingquery);    
$followersquery=mysql_query("SELECT * FROM userinfo WHERE following LIKE '%$email%'");
$numfollowers = mysql_num_rows($followersquery);

echo'
<div id="accordion2" class="accordion" style="margin-top:35px;width:150px;"><a href="myprofile.php">
<img class="dropshadow" style="border: 2px solid white;margin-top:5px;" src="',$profilepic,'" height="140" width="145" /></a><div style="font-size:14px;text-align:center;margin-top:5px;">',$shortname,'<br /><span style="font-size:13px;">',$numfollowers,' <i class="icon-user"> </i> <a style="color:black;" href="myprofile.php?view=followers">Followers</a><br />',$numfollowing,' <i class="icon-user"> </i> <a style="color:black;" href="myprofile.php?view=following">Following</a></span></div>';

}

else {
echo'
<div id="accordion2" class="accordion" style="margin-top:60px;width:150px;">
';
}

?>

<div class="accordion-group">
<div class="accordion-heading">
<a style="background-color:#1a618a;color:white;" class="accordion-toggle" href="newest.php">Photography </a>
</div>
<div id="collapseOne" class="accordion-body collapse">
</div>
</div>

<div class="accordion-group">
<div class="accordion-heading">
<a style="background-color:#1a618a;color:white;" class="accordion-toggle" href="newest.php?view=prs">Photographers</a>
</div>
<div id="collapseTwo" class="accordion-body collapse">
</div>
</div>

<div class="accordion-group">
<div class="accordion-heading">
<a style="background-color:#1a618a;color:white;" class="accordion-toggle" href="newest.php?view=exts">Exhibits</a>
</div>
<div id="collapseThree" class="accordion-body collapse">
</div>
</div>

</div>
</div>

<!--DIFFERENT GALLERY VIEWS-->
<?php

    echo'<div id="container2" style="margin-top:60px;margin-left:0px;">';
for($iii=1; $iii <= 16; $iii++) {
	$image = mysql_result($result, $iii-1, "source");
    $imageThumb=str_replace("userphotos/","userphotos/medthumbs/", $image);
	$id = mysql_result($result, $iii-1, "id");
    $caption = mysql_result($result, $iii-1, "caption");
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
    $heightls = $height / 5;
    $widthls = $width / 5;
    if($widthls < 165) {
    $heightls = $heightls * ($heightls/$widthls);
    $widthls = 240;
    }
                
		echo '<div class="masonryImage">
        
        <img class="phototitle2" src="http://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
	    
      } //end for loop
      
      ?>
      
      </div>
      
        <script type="text/javascript">

    $(document).ready(function() {

        var $container = $('#container2');
          $container.imagesLoaded(function(){
            $container.masonry({
              itemSelector : '.masonryImage',
              columnWidth : 350     //Added gutter to simulate margin
          });
        });

    });
  </script>

<?php
      
      
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


if($view == 'prs') {
$prsquery="SELECT * FROM userinfo WHERE (profilepic != 'http://www.photorankr.com/profilepics/default_profile.jpg' AND profilepic != 'profilepics/default_profile.jpg') ORDER BY user_id DESC";
$prsresult=mysql_query($prsquery);

echo'<div id="container" style="width:1140px;position:relative;left:-120px;top:55px;">';
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

		echo '<div class="phototitle" id="',$id,'" style="width:240px;height:270px;overflow:hidden;background-color:white;color:#1a618a;font-size:15px;"><a href="http://photorankr.com/viewprofile.php?u=',$userid,'"><img onmousedown="return false" oncontextmenu="return false;" style="min-height:240px;min-width:240px;" src="http://photorankr.com/',$profpic,'" height="240" width="240" /></a><br /><div style="margin-top:4px;text-align:center;">',$fullname,'</div></div>';
    } //end for loop
    echo'</div>';
    
} //end of view == 'prs'



elseif($view == 'exts') {
$query="SELECT * FROM sets ORDER BY id DESC";
$result=mysql_query($query);
$numberexhibits=mysql_num_rows($result);

echo'<div id="container" style="width:1140px;position:relative;left:-120px;top:55px;">';
for($iii=1; $iii <= $numberexhibits; $iii++) {
	$coverpic = mysql_result($result, $iii-1, "cover");
    $caption = mysql_result($result, $iii-1, "title");
    $set_id = mysql_result($result, $iii-1, "id");
    if($coverpic == '') {
    $coverpic = 'profilepics/nocoverphoto.png';
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

		echo '<div class="phototitle" id="',$id,'" style="width:240px;height:240px;overflow:hidden;"><a href="http://photorankr.com/viewprofile.php?u=',$userid,'&ex=y&set=',$set_id,'">
        
        <div class="statoverlay2" style="z-index:1;left:0px;top:170px;position:relative;background-color:black;width:240px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;">"',$caption,'"<br>By: ',$fullname,'</br>Exhibit Score: ',$score,'</p></div>
        
        <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-75px;min-height:240px;min-width:240px;" src="http://photorankr.com/',$coverpic,'" height="240" width="240" /></a></div>';
    } //end for loop
    echo'</div>';

} //end of view == 'exts'

?>


<!--Footer begin-->   
<div class="grid_24" style="height:30px;margin-top:30px;background-color:rgb:(238,239,243);text-align:center;padding-top:10px;padding-bottom:20px; background-color:none;text-decoration:none;">
<p style="text-decoration:none;">
</br></br>
Copyright&nbsp;&copy;&nbsp;2012&nbsp;PhotoRankr, Inc.&nbsp;&nbsp;
<a href="http://photorankr.com/about.php">About</a>&nbsp;&nbsp;                                       
<a href="http://photorankr.com/terms.php">Terms</a>&nbsp;&nbsp;
<a href="http://photorankr.com/privacy.php">Privacy</a>&nbsp;&nbsp;
<a href="http://photorankr.com/help.php">Help<a>&nbsp;&nbsp;
<a href="http://photorankr.com/contact.php">Contact&nbsp;Us<a>
<br />
<br />
</p>                   
</div>
<!--Footer end-->

</div>

</body>
</html>