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
  
  
  //GRAB USER INFORMATION
  $userid = htmlentities($_GET['u']);
  $userquery = mysql_query("SELECT * FROM userinfo WHERE user_id = '$userid'");
  $profilepic = mysql_result($userquery,0,'profilepic'); 
  $useremail = mysql_result($userquery,0,'emailaddress'); 
  $fullname = mysql_result($userquery,0,'firstname')." ".mysql_result($userquery,0,'lastname'); 
  $age = mysql_result($userquery,0,'age');
  $gender = mysql_result($userquery,0,'gender');
  $location = mysql_result($userquery,0,'location');
  $camera = mysql_result($userquery,0,'camera');
  $about = mysql_result($userquery,0,'about');
  $quote = mysql_result($userquery,0,'quote');
  $fbook = mysql_result($userquery,0,'fbook');
  $twitter = mysql_result($userquery,0,'twitter');
  $faves = mysql_result($userquery,0,'faves');

  
  $view = htmlentities($_GET['view']);
  
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

 <meta name="Generator" content="EditPlus">
  <meta name="Author" content="PhotoRankr, PhotoRankr.com">
  <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
  <meta name="Description" content="A gallery of the newest photography, photographers, and exhibits on PhotoRankr.">
     <meta name="viewport" content="width=1200" /> 

  <link rel="stylesheet" type="text/css" href="bootstrapnew.css" />
 <link rel="stylesheet" href="reset.css" type="text/css" />
  <link rel="stylesheet" href="text.css" type="text/css" />
  <link rel="stylesheet" href="960_24.css" type="text/css" />
  <script type="text/javascript" src="jquery.js"></script>   
  <script src="bootstrap-dropdown.js" type="text/javascript"></script>
  <script src="bootstrap-collapse.js" type="text/javascript"></script>
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
opacity:.6;
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
opacity:.6;
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
<body style="overflow-x:hidden;min-width:1220px;">

<?php navbar(); ?>  
<div class="container_24"><!--START CONTAINER-->

<!--LEFT SIDEBAR-->
<div class="grid_24" style="width:1120px;">
<div class="grid_4 pull_1 rounded" style="background-color:#eeeff3;position:relative;top:80px;height:500px;width:250px;">

<div style="width:240px;height:140px;">
<div class="circle" style="float:left;overflow:hidden;margin-left:15px;margin-top:15px;">
<img src="<?php echo $profilepic; ?>" height="160" width="160"/>
</div>
<a class="btn btn-success" style="float:left;width:70px;margin-top:40px;margin-left:10px;font-size:14px;font-weight:150;" href="#">Follow</a>
<a class="btn btn-primary" style="float:left;width:70px;margin-top:7px;margin-left:10px;font-size:14px;font-weight:150;" href="#">Promote</a>
</div>

<div style="width:250px;margin-top:0px;">
<div style="font-size:18px;text-align:center;font-weight:200;"><?php echo $fullname; ?></div>
</div>

<div style="width:250px;height:100px;margin-top:0px;">

</div>

<a href="viewprofile3.php?u=<?php echo $userid; ?>&view=about"><div style="width:250px;border-top:dotted;margin-top:10px;">
<span style="text-align:center;font-size:24px;padding:15px;">About&nbsp;&nbsp;<img style="margin-left:70px;" src="graphics/info.png" height="35" width="35"></span>
</div></a>

<a href="viewprofile3.php?u=<?php echo $userid; ?>&view=network"><div style="width:250px;border-top:dotted;margin-top:10px;">
<span style="text-align:center;font-size:24px;padding:15px;">Network&nbsp;&nbsp;<img style="margin-left:70px;" src="graphics/info.png" height="35" width="35"></span>
</div></a>

<a href="viewprofile3.php?u=<?php echo $userid; ?>&view=favorites"><div style="width:250px;border-top:dotted;margin-top:10px;">
<span style="text-align:center;font-size:24px;padding:15px;">Favorites&nbsp;&nbsp;<img style="margin-left:70px;" src="graphics/info.png" height="35" width="35"></span>
</div></a>

<a href="viewprofile3.php?u=<?php echo $userid; ?>&view=contact"><div style="width:250px;border-top:dotted;margin-top:10px;">
<span style="text-align:center;font-size:24px;padding:15px;">Contact&nbsp;&nbsp;<img style="margin-left:70px;" src="graphics/contact.png" height="35" width="35"></span>
</div></a>

</div><!--end 4 grid-->

<div class="grid_18 roundedright" style="background-color:#eeeff3;height:60px;margin-top:80px;width:800px;margin-left:-45px;">

<a style="text-decoration:none;color:black;" href="viewprofile3.php?u=<?php echo $userid; ?>"><div class="clicked" style="width:180px;height:60px;border-right:1px solid #ccc;border-left:1px solid #ccc;float:left;<?php if($view == '') {echo'background-color:#bbb;color:white;';} ?>"><div style="font-size:25px;font-weight:100;margin-top:10px;text-align:center;">Portfolio</div></div></a>

<a style="text-decoration:none;color:black;" href="viewprofile3.php?u=<?php echo $userid; ?>&view=store"><div class="clicked" style="width:180px;height:60px;border-right:1px solid #ccc;float:left;<?php if($view == 'store') {echo'background-color:#bbb;color:white;';} ?>"><div style="font-size:25px;font-weight:100;margin-top:10px;text-align:center;">Store</div></div></a>

<a style="text-decoration:none;color:black;" href="viewprofile3.php?u=<?php echo $userid; ?>&view=blog"><div class="clicked" style="width:180px;height:60px;border-right:1px solid #ccc;float:left;<?php if($view == 'blog') {echo'background-color:#bbb;color:white;';} ?>"><div style="font-size:25px;font-weight:100;margin-top:10px;text-align:center;">Blog</div></div></a>

<div style="width:180px;height:60px;float:left;"><div style="font-size:25px;font-weight:100;margin-top:6px;text-align:center;">
<form class="navbar-search" action="viewprofile3.php?u=<?php echo $userid; ?>&view=search" method="post">
<input class="search" style="position:relative;margin-left:15px;margin-top:2px;" name="searchterm" type="text">
</form></div></div>

<?php

    if($view == '') {
    
        $option = htmlentities($_GET['option']);    
    
        echo'<br /><br /><br /><br /><div style="width:760px;text-align:center;font-size:14px;font-weight:200;"><div style="margin-left:20px;"><a class="green" style="text-decoration:none;color:#aaa;" href="viewprofile3.php?u=',$userid,'">Newest</a> | <a class="green" style="text-decoration:none;color:#aaa;" href="viewprofile3.php?u=',$userid,'&option=top">Top Ranked</a> | <a class="green" style="text-decoration:none;color:#aaa;" href="viewprofile3.php?u=',$userid,'&option=fave">Most Favorited</a> | <a class="green" style="text-decoration:none;color:#aaa;" href="viewprofile3.php?u=',$userid,'&option=exhibits">Exhibits</a></div></div>';
        
        if($option == '') {        
        $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$useremail' ORDER BY id DESC");
        $numresults = mysql_num_rows($query);
        }
        
        elseif($option == 'top') {
        $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$useremail' AND views > 20 ORDER BY (points/votes) DESC");
        $numresults = mysql_num_rows($query);
        }
                
        elseif($option == 'fave') {
        $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$useremail' ORDER BY faves DESC");
        $numresults = mysql_num_rows($query);
        }
        
        echo'<div id="thepics">';
        echo'<div id="container" class="grid_18" style="width:770px;margin-top:-10px;padding-left:20px;">';

        for($iii=0; $iii < $numresults; $iii++) {
              
                $image[$iii] = mysql_result($query, $iii, "source");
                $imageThumb[$iii] = str_replace("userphotos/","../userphotos/medthumbs/", $image[$iii]);
                $id = mysql_result($query, $iii, "id");
                $caption = mysql_result($query, $iii, "caption");
                $points = mysql_result($query, $iii, "points");
                $votes = mysql_result($query, $iii, "votes");
                $faves = mysql_result($query, $iii, "faves");
                $score = number_format(($points/$votes),2);
                $faveemail = mysql_result($query, $iii, "emailaddress");
                $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$faveemail'");
                $firstname = mysql_result($query, 0, "firstname");
                $lastname = mysql_result($query, 0, "lastname");
                $reputation = mysql_result($query, 0, "lastname");
                $fullname = $firstname . " " . $lastname;
                list($width, $height) = getimagesize($image);
                $imgratio = $height / $width;
                $heightls = $height / 3.5;
                $widthls = $width / 3.5;

                echo '   

                <div class="fPic" id="',$id,'" style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="http://photorankr.com/fullsize.php?image=', $image[$iii], '">

                <div class="statoverlay" style="z-index:1;left:0px;top:155px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:16px;font-weight:100;">',$caption,'</span><br><span style="font-size:14px;font-weight:100;">Score: ',$score,'<br>Favorites: ',$faves,'</span></p></div>

                <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:245px;min-width:245px;" src="http://www.photorankr.com/',$imageThumb[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
	    
                } //end for loop      
        
        echo'</div>';
        echo'</div>';

        }
        
    
    elseif($view == 'about') {
        
        echo'
        <div class="span9" style="margin-top:30px;">
        <table class="table">
        <tbody>';

        if($age) {
        echo'
        <tr>
        <td>Age:</td>
        <td>',$age,'</td>
        </tr>'; }

        if($location) {
        echo'
        <tr>
        <td>From:</td>
        <td>',$location,'</td>
        </tr>'; }

        if($gender) {
        echo'
        <tr>
        <td>Gender:</td>
        <td>',$gender,'</td>
        </tr>'; }

        if($camera) {
        echo'
        <tr>
        <td>Camera:</td>
        <td>',$camera,'</td>
        </tr>'; }

        if($fbook) {
        echo'
        <tr>
        <td>Facebook Page:</td>
        <td><a href="',$fbook,'">',$fbook,'</a></td>
        </tr>'; }

        if($twitter) {
        echo'
        <tr>
        <td>Twitter:</td>
        <td><a href="',$twitter,'">',$twitter,'</a></td>
        </tr>'; }

        if($quote) {
        echo'
        <tr>
        <td>Quote:</td>
        <td>',$quote,'</td>
        </tr>'; }

        if($about) {
        echo'
        <tr>
        <td>About:</td>
        <td>',$about,'</td>
        </tr>'; }

        echo'
        </tbody>
        </table>
        </div>';
    
    }

    elseif($view == 'network') {
    
        $option = htmlentities($_GET['option']);    
    
        echo'<br /><br /><br /><br /><div style="width:760px;text-align:center;font-size:14px;font-weight:200;"><div style="margin-left:20px;"><a class="green" style="text-decoration:none;color:#aaa;" href="viewprofile3.php?u=',$userid,'&view=network">Following</a> | <a class="green" style="text-decoration:none;color:#aaa;" href="viewprofile3.php?u=',$userid,'&view=network&option=followers">Followers</a></div></div>';
        
        if($option == '') {
            $query = mysql_query("SELECT following FROM userinfo WHERE emailaddress = '$useremail'");
            $followinglist = mysql_result($query, 0, "following");
            $followingquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress IN ($followinglist)");
            $numberfollowing = mysql_num_rows($followingquery);
        }
        
        elseif($option == 'followers') {
        $followersquery="SELECT * FROM userinfo WHERE following LIKE '%$useremail%'";
        $followingquery=mysql_query($followersquery);
        $numberfollowing = mysql_num_rows($followingquery);
        }
        
        echo'<div style="margin-left:20px;">';
        for($iii = 0; $iii < $numberfollowing; $iii++) {
		$followingpic = mysql_result($followingquery, $iii, "profilepic");
		$followingfirst = mysql_result($followingquery, $iii, "firstname");
		$followinglast = mysql_result($followingquery, $iii, "lastname");
        $fullname = $followingfirst . " " . $followinglast;
        $fullname = ucwords($fullname);
        $followingid = mysql_result($followingquery, $iii, "user_id");
		
                echo '   

                <div style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="http://photorankr.com/fullsize.php?image=', $image[$iii], '">

                <div class="statoverlay" style="z-index:1;left:0px;top:210px;position:relative;background-color:black;width:245px;height:35px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:18px;font-weight:100;">',$fullname,'</span></p></div>

                <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-35px;min-height:245px;min-width:245px;" src="http://www.photorankr.com/',$followingpic,'" height="245" width="245" /></a></div>';
        
        }
        echo'</div>';
    }
    
    
    elseif($view == 'favorites') {
    
        $query = mysql_query("SELECT * FROM photos WHERE source IN ($faves) ORDER BY FIELD (source, $faves) DESC LIMIT 9");
        $numresults = mysql_num_rows($query);
        echo'<div id="thepics">';
        echo'<div id="container" class="grid_18" style="width:770px;margin-top:0px;padding-left:20px;">';

        for($iii=0; $iii < $numresults; $iii++) {
              
                $image[$iii] = mysql_result($query, $iii, "source");
                $imageThumb[$iii] = str_replace("userphotos/","../userphotos/medthumbs/", $image[$iii]);
                $id = mysql_result($query, $iii, "id");
                $caption = mysql_result($query, $iii, "caption");
                $points = mysql_result($query, $iii, "points");
                $votes = mysql_result($query, $iii, "votes");
                $faves = mysql_result($query, $iii, "faves");
                $score = number_format(($points/$votes),2);
                $faveemail = mysql_result($query, $iii, "emailaddress");
                $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$faveemail'");
                $firstname = mysql_result($query, 0, "firstname");
                $lastname = mysql_result($query, 0, "lastname");
                $reputation = mysql_result($query, 0, "lastname");
                $fullname = $firstname . " " . $lastname;
                list($width, $height) = getimagesize($image);
                $imgratio = $height / $width;
                $heightls = $height / 3.5;
                $widthls = $width / 3.5;

                echo '   

                <div class="fPic" id="',$id,'" style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="http://photorankr.com/fullsize.php?image=', $image[$iii], '">

                <div class="statoverlay" style="z-index:1;left:0px;top:155px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:16px;font-weight:100;">',$caption,'</span><br><span style="font-size:14px;font-weight:100;">Score: ',$score,'<br>Favorites: ',$faves,'</span></p></div>

                <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:245px;min-width:245px;" src="http://www.photorankr.com/',$imageThumb[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
	    
                } //end for loop      
        
        echo'</div>';
        echo'</div>';
    
    }
    
    
        elseif($view == 'favorites') {
    
        $query = mysql_query("SELECT * FROM photos WHERE source IN ($faves) ORDER BY FIELD (source, $faves) DESC LIMIT 9");
        $numresults = mysql_num_rows($query);
        echo'<div id="thepics">';
        echo'<div id="container" class="grid_18" style="width:770px;margin-top:0px;padding-left:20px;">';

        for($iii=0; $iii < $numresults; $iii++) {
              
                $image[$iii] = mysql_result($query, $iii, "source");
                $imageThumb[$iii] = str_replace("userphotos/","../userphotos/medthumbs/", $image[$iii]);
                $id = mysql_result($query, $iii, "id");
                $caption = mysql_result($query, $iii, "caption");
                $points = mysql_result($query, $iii, "points");
                $votes = mysql_result($query, $iii, "votes");
                $faves = mysql_result($query, $iii, "faves");
                $score = number_format(($points/$votes),2);
                $faveemail = mysql_result($query, $iii, "emailaddress");
                $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$faveemail'");
                $firstname = mysql_result($query, 0, "firstname");
                $lastname = mysql_result($query, 0, "lastname");
                $reputation = mysql_result($query, 0, "lastname");
                $fullname = $firstname . " " . $lastname;
                list($width, $height) = getimagesize($image);
                $imgratio = $height / $width;
                $heightls = $height / 3.5;
                $widthls = $width / 3.5;

                echo '   

                <div class="fPic" id="',$id,'" style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="http://photorankr.com/fullsize.php?image=', $image[$iii], '">

                <div class="statoverlay" style="z-index:1;left:0px;top:155px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:16px;font-weight:100;">',$caption,'</span><br><span style="font-size:14px;font-weight:100;">Score: ',$score,'<br>Favorites: ',$faves,'</span></p></div>

                <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:245px;min-width:245px;" src="http://www.photorankr.com/',$imageThumb[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
	    
                } //end for loop      
        
        echo'</div>';
        echo'</div>';
    
    }
    
    
    elseif($view == 'search') {
        
        $searchterm = htmlentities(mysql_real_escape_string($_POST['searchterm']));
        $query = mysql_query("SELECT * FROM photos WHERE concat(caption,location,tag1,tag2,tag3,tag4) LIKE '%$searchterm%' AND emailaddress = '$useremail' ORDER BY (views) DESC");
        $numresults = mysql_num_rows($query);
        echo'<div id="thepics">';
        echo'<div id="container" class="grid_18" style="width:770px;margin-top:0px;padding-left:20px;">';

        for($iii=0; $iii < $numresults; $iii++) {
              
                $image[$iii] = mysql_result($query, $iii, "source");
                $imageThumb[$iii] = str_replace("userphotos/","../userphotos/medthumbs/", $image[$iii]);
                $id = mysql_result($query, $iii, "id");
                $caption = mysql_result($query, $iii, "caption");
                $points = mysql_result($query, $iii, "points");
                $votes = mysql_result($query, $iii, "votes");
                $faves = mysql_result($query, $iii, "faves");
                $score = number_format(($points/$votes),2);
                $faveemail = mysql_result($query, $iii, "emailaddress");
                $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$faveemail'");
                $firstname = mysql_result($query, 0, "firstname");
                $lastname = mysql_result($query, 0, "lastname");
                $reputation = mysql_result($query, 0, "lastname");
                $fullname = $firstname . " " . $lastname;
                list($width, $height) = getimagesize($image);
                $imgratio = $height / $width;
                $heightls = $height / 3.5;
                $widthls = $width / 3.5;

                echo '   

                <div class="fPic" id="',$id,'" style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="http://photorankr.com/fullsize.php?image=', $image[$iii], '">

                <div class="statoverlay" style="z-index:1;left:0px;top:155px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:16px;font-weight:100;">',$caption,'</span><br><span style="font-size:14px;font-weight:100;">Score: ',$score,'<br>Favorites: ',$faves,'</span></p></div>

                <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:245px;min-width:245px;" src="http://www.photorankr.com/',$imageThumb[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
	    
                } //end for loop      
        
        echo'</div>';
        echo'</div>';
    
    }
    
    
    elseif($view == 'contact') {
    
        echo'<div class="grid_16" style="margin-left:20px;font-family: arial; font-size: 18px;font-weight:200;margin-top:20px;">';
	if($_SESSION['loggedin'] == 1) {
	    
		echo' <div style="position:absolute; font-size: 25px; font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
		line-height: 28px; color: #333333;">
    
		<span style="font-size:20px;">Send this photographer a message:</span>
        <br />
		<form method="post" action="../sendmessage2.php" />
		<textarea cols="95" rows="10" style="width:650px" name="message"></textarea>
    		<br />
    		<br />
		<input type="submit" class="btn btn-success" value="Send Message"/>
		<input type="hidden" name="emailaddressofviewed" value="',$emailaddress,'" />
		</form>';
	}
	else {
    		echo' <div style="font-size: 20px;text-align:center;margin-top:150px;font-weight:200;font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
		line-height: 18px;
		color: #333333;">';
		echo 'You must be signed in to contact this person.</div>';
	}

	if($_GET['action'] == "messagesent") {
		echo '<div style="font-size: 20px;">Message Sent!</div>';
	}
    echo '</div>';
    
    
    }
    
?>

</div><!--end grid 18-->

</div><!--end 24 grid-->

</div>
</body>
</html>
