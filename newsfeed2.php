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
    
    if ($_SESSION['loggedin'] != 1) {
        header("Location: signup.php");
        exit();
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
  
  
          if($_GET['action'] == 'comment') {
    
            $blogid = htmlentities($_GET['blogid']);
            $comment = mysql_real_escape_string($_POST['comment']);
                    
            $commentinsertion = mysql_query("INSERT INTO blogcomments (comment,blogid,emailaddress) VALUES ('$comment','$blogid','$email')");
            
            echo '<META HTTP-EQUIV="Refresh" Content="0; URL=newsfeed.php">';
            exit();

    
        }
  
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

 <meta name="Generator" content="EditPlus">
  <meta name="Author" content="PhotoRankr, PhotoRankr.com">
  <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
  <meta name="Description" content="A gallery of the newest photography, photographers, and exhibits on PhotoRankr.">
     <meta name="viewport" content="width=1200" /> 

  <link rel="stylesheet" type="text/css" href="css/bootstrapNew.css" />
 <link rel="stylesheet" href="reset.css" type="text/css" />
  <link rel="stylesheet" href="text2.css" type="text/css" />
  <link rel="stylesheet" href="960_24.css" type="text/css" />
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script src="bootstrap.js" type="text/javascript"></script>
  <script src="bootstrap-dropdown.js" type="text/javascript"></script>
  <script src="bootstrap-collapse.js" type="text/javascript"></script>
  <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>

<title>PhotoRankr - Your Personal News Feed</title>

  
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

<!--HIDDEN COMMENT SCRIPT-->
<script type="text/javascript">   
$(document).ready(function(){
  $(".flip").click(function(){
    $(".panel").slideToggle("slow");
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
<body style="overflow-x:hidden; background-color: #eeeff3;min-width:1220px;">

<?php navbarnew(); ?>

   <!--big container-->
    <div id="container" class="container_24">
    
    
<!--Side Bar-->    
<div style="position:fixed;margin-top:70px;margin-left:-110px;">
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
<div id="accordion2" class="accordion" style="width:150px;"><a href="myprofile.php">
<img class="dropshadow" style="border: 2px solid white;margin-top:5px;" src="',$profilepic,'" height="140" width="145" /></a><div style="font-size:14px;text-align:center;margin-top:5px;">',$shortname,'<br /><span style="font-size:13px;">',$numfollowers,' <i class="icon-user"> </i> <a style="color:black;" href="myprofile.php?view=followers">Followers</a><br />',$numfollowing,' <i class="icon-user"> </i> <a style="color:black;" href="myprofile.php?view=following">Following</a></span></div>';

}

else {
echo'
<div id="accordion2" class="accordion" style="margin-top:60px;width:150px;">
';
}

?>

<!--SIDEBAR-->

<div style="font-size:14px;">
<br />

<a style="color:black;margin-top:5px;" href="newsfeed.php?view=up"><img style="padding-right:10px;" src="graphics/newsfeedupload.png" height="28" width="28" />Uploads</a>
<hr>

<a style="color:black;" href="newsfeed.php?view=cmp"><img style="padding-right:10px;" src="graphics/smallcampaignicon.png" height="28" width="28" />Campaigns</a>
<hr>

<a style="color:black;" href="newsfeed.php?view=tr"><img style="padding-right:10px;" src="graphics/newsfeedtrending.png" height="28" width="28" />Trending</a>
<hr>

<a style="color:black;" href="newsfeed.php?view=fv"><img style="padding-right:10px;" src="graphics/newsfeedfavorite.png" height="28" width="28" />Favorites</a>
<hr>

<a style="color:black;" href="newsfeed.php?view=fw"><img style="padding-right:10px;" src="graphics/newsfeednewfollower.png" height="28" width="28" />Follows</a>
<hr>

<a style="color:black;" href="newsfeed.php?view=cmt"><img style="padding-right:10px;" src="graphics/newsfeedcomment.png" height="28" width="28" />Comments</a>
<hr>
</div>

</div>
</div>

<!--WHO TO FOLLOW-->
<?php
//RECOMMENDATIONS

echo'<div class="grid_4 push_2" style="margin-top:75px;">
<hr style="width:283px;">';

//PHOTOGRAPHERS THEY MIGHT LIKE

	//select all of the people they are following
	$followingquery = "SELECT following FROM userinfo WHERE emailaddress='$email' LIMIT 1";
	$followingresult = mysql_query($followingquery);
	$followinglistowner = mysql_result($followingresult, 0, "following");
    
	//select all the people they are following who aren't themselves
	$str = mysql_real_escape_string("%'%',%'%',%'%',%'%'%',%'%'%");
	$followingquery = "SELECT following FROM userinfo WHERE emailaddress IN($followinglistowner) AND emailaddress NOT IN('$email') AND following LIKE('" . $str . "') ORDER BY RAND() LIMIT 1";
	$followingresult = mysql_query($followingquery) or die(mysql_error());
	$followinglist = mysql_result($followingresult, 0, "following");
	$followingnumber = mysql_num_rows($followingresult);
	
	//if they aren't yet following anyone, just get four random photographers
	if($followingnumber == 0) {
		$displayquery = "SELECT firstname, lastname, profilepic FROM userinfo ORDER BY RAND() LIMIT 4";
		$displayresult = mysql_query($displayquery);
	}
	//else they are following people so go ahead with the original procedure
	else {
		$displayquery = "SELECT firstname, lastname, profilepic,user_id FROM userinfo WHERE emailaddress IN($followinglist) AND emailaddress NOT IN('$email', $followinglistowner, 'support@photorankr.com') ORDER BY RAND() LIMIT 4";		
		$displayresult = mysql_query($displayquery) or die(mysql_error());
        $numdisplayresult = mysql_num_rows($displayresult);
	}
	
	//see if we have enough information to even display this 
		//loop through the people, printing out their name and profile picture
        //echo'<span style="font-size:16px;font-weight:100;padding:15px;">Photographers to Follow:</span>';
        
		for($iii=0; $iii < 4; $iii++) {
			$name = mysql_result($displayresult, $iii, "firstname") . " " . mysql_result($displayresult, $iii, "lastname");
			$profilepic = mysql_result($displayresult, $iii, "profilepic");
            $profileid = mysql_result($displayresult, $iii, "user_id");
            
            if($name == '' || $pofilepic == ''){
            $somequery = mysql_query("SELECT firstname,lastname,profilepic,user_id,emailaddress FROM userinfo WHERE profilepic != 'profilepics/default_profile.jpg' && firstname != 'PhotoRankr' ORDER BY RAND()");
            $firstname = mysql_result($somequery, $iii, "firstname");
            $name = $firstname . " " . mysql_result($somequery, $iii, "lastname");
			$profilepic = mysql_result($somequery, $iii, "profilepic");
            $profileid = mysql_result($somequery, $iii, "user_id");
            $profileemail = mysql_result($somequery, $iii, "emailaddress");
             
            $numownerphotosquery = mysql_query("SELECT * FROM photos WHERE emailaddress = '$profileemail'");
            $numownerphotos = mysql_num_rows($numownerphotosquery);
            
            $followersquery="SELECT * FROM userinfo WHERE following LIKE '%$profileemail%'";
            $followersresult=mysql_query($followersquery);
            $numberfollowers = mysql_num_rows($followersresult);
                
            for($ii = 0; $ii < $numownerphotos; $ii++) {
                $points = mysql_result($numownerphotosquery, $ii, "points");
                $votes = mysql_result($numownerphotosquery, $ii, "votes");
                $totalfaves = mysql_result($numownerphotosquery, $ii, "faves");
                $portfoliopoints+=$points;
                $portfoliovotes+=$votes;
                $portfoliofaves+=$totalfaves;
            }
            if ($portfoliovotes > 0) {
                $portfolioranking=($portfoliopoints/$portfoliovotes);
                $portfolioranking=number_format($portfolioranking, 2, '.', '');    
                }
            else if ($portfoliovotes < 1) {
                $portfolioranking="N/A";
                }	
                
        } 
            
			echo '<div style="width:283px;"><a style="text-decoration:none;" href="viewprofile.php?u=',$profileid,'"><img class="dropshadow" style="border: 2px solid white;" src="',$profilepic,'" height="60" width="60"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-primary" style="width:90px;" href="viewprofile.php?u=',$profileid,'">PORTFOLIO</a><br /><br /><span style="font-size:15px;"><a style="padding-top:20px;" href="viewprofile.php?u=',$profileid,'">',$name,'</a></span><br />
            <div style="font-size:13px;margin-bottom:10px;clear:both;">Photos: ',$numownerphotos,'&nbsp;|&nbsp;Average: ',$portfolioranking,'&nbsp;|&nbsp;Followers: ',$numberfollowers,'</div>
            <hr></a></div>';
		}
        
        
//PHOTOS THEY MIGHT LIKE
//find out all of the photos they have ever favorited
	$favesquery = "SELECT faves from userinfo WHERE emailaddress='$email' LIMIT 1";
	$favesresult = mysql_query($favesquery);
	$faveslistowner = mysql_result($favesresult, 0, "faves");
	
	//select all the photos they have ever favorited
	$favesquery = "SELECT maintags, singlecategorytags, singlestyletags FROM photos WHERE source IN($faveslistowner)";
	$favesresult = mysql_query($favesquery);
	$favesnumber = mysql_num_rows($favesresult);

	//if they actually had favorites
	if($favesnumber != 0) {	
		//loop through the results to create a variable which holds all tags for all the photos they have ever favorited
		$favetags = "";
		for($iii=0; $iii < $favesnumber; $iii++) {
			$favetags .= mysql_result($favesresult, $iii, "maintags");
			$favetags .= mysql_result($favesresult, $iii, "singlecategorytags");
			$favetags .= mysql_result($favesresult, $iii, "singlestyletags");		
		}	

		//now select all of the photos with similar tags that weren't favorited by them
		$favesquery = "SELECT source, MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$favetags') AS matching FROM photos WHERE MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$favetags') AND source NOT IN($faveslistowner) ORDER BY RAND() LIMIT 4";
		$favesresult = mysql_query($favesquery);
	}
	//otherwise they had no faves so pick four random photos
	else {	
		$favesquery = "SELECT source FROM photos ORDER BY RAND() LIMIT 4";
		$favesresult = mysql_query($favesquery);
	}

	//see if they had enough information to display photos
	if(mysql_num_rows($favesresult) >= 4) {
		for($iii=0; $iii < 4; $iii++) {
        $image = mysql_result($favesresult, $iii, "source");
        $image2 = str_replace("userphotos/","userphotos/medthumbs/", $image);
        list($width, $height) = getimagesize($image);
        $imgratio = $height / $width;
        $heightls = $height / 4;
        $widthls = $width / 4;
        $query7 =  mysql_query("SELECT caption FROM photos WHERE source = '$image'");
        $captionarray = mysql_fetch_array($query7);
        $caption = $captionarray['caption'];
        
        $imageinfo = mysql_query("SELECT * FROM photos WHERE source = '$image'");
        $views = mysql_result($imageinfo,0,'views');
        $points = mysql_result($imageinfo,0,'points');
        $votes = mysql_result($imageinfo,0,'votes');
        $rank = ($points / $votes);
        $rank = number_format($rank,2);
        
        list($width, $height) = getimagesize($image);
        $width = ($width / 4.5);
        $height = ($height / 4.5);
			
            echo '<div style="width:283px;"><a style="text-decoration:none;" href="fullsize.php?image=',$image,'"><img class="dropshadow" style="max-width:275px;border: 2px solid white;" src="',$image2,'"  width="',$width,'px" height="',$height,'px" /><br /><br /><a href="#" class="btn btn-primary" style="width:100px;">RECOMMENDED</a><br /><br /><span style="font-size:15px;"><a style="padding-top:20px;" href="fullsize.php?image=',$image,'">"',$caption,'"</a></span><br />
            <div style="font-size:13px;margin-bottom:10px;clear:both;">Views: ',$views,'&nbsp;|&nbsp;Rank: ',$rank,'</div>
            <hr></a></div>';            
		}
	} //end of photos you might like
    
    
    //Blog Post
    
    $blogtitlequery = mysql_query("SELECT title FROM entries ORDER BY id DESC LIMIT 0,1");
    $blogtitle = mysql_result($blogtitlequery,0,'title');
    
    echo '<div id="bar" style="width:283px;"><a style="text-decoration:none;" href="blog/post"><img class="dropshadow" style="background-color:#875752;max-width:275px;border: 2px solid white;padding:3px;" src="graphics/blogtext.png" /><br /><br /><a href="#" class="btn btn-warning" style="width:100px;">READ BLOG</a><br /><br /><span style="font-size:15px;"><a style="padding-top:20px;" href="blog/post">Latest Post: "',$blogtitle,'"</a></span><br />
            <hr></a></div>';  
    

    echo'</div>'; //end of 4 grid
?>


<div>
</div>    
    

<!--NEWSFEED-->
<div class="grid_16 push_4" id="thepics" style="margin-top:75px;border: 1 px solid black;">
<div id="container">

<?php
if(isset($_GET['view'])) {
$view=htmlentities($_GET['view']);
}

//PHOTOSTREAM PHOTOS QUERY
$emailquery=("SELECT following FROM userinfo WHERE emailaddress ='$email'");
$followresult=mysql_query($emailquery);
$followlist=mysql_result($followresult, 0, "following");
$followrow=mysql_fetch_array($followresult);
$following=$followrow['following'];

$newsfeedquery = "SELECT * FROM newsfeed WHERE (owner IN ($followlist) OR emailaddress IN ($followlist)) AND emailaddress NOT IN ('$email') ORDER BY id DESC";
$newsfeedresult = mysql_query($newsfeedquery);
$maxwidth = 400;
               
for($iii=1; $iii <= 80; $iii++) {
    $newsrow = mysql_fetch_array($newsfeedresult);
    $newsemail = $newsrow['emailaddress'];    
    $owner = $newsrow['owner'];
    $emailfollowing = $newsrow['following'];
    $id = $newsrow['id'];
    $type = $newsrow['type'];
    
if($view == '') {

    if ($type == "campaign") {
    $photoid = $newsrow['source'];
	$caption = $newsrow['caption'];
    
    $quotequery = mysql_query("SELECT quote,views FROM campaigns WHERE id = '$photoid'");
    $quote = mysql_result($quotequery,0,'quote');
    $views = mysql_result($quotequery,0,'views');
    $phrase = 'New Campaign: (Reward: $'. $quote .') "'. $caption . '"';
    
    $campphotosquery = mysql_query("SELECT source FROM campaignphotos WHERE campaign = '$photoid' ORDER by (points/votes) DESC");
    $numcampphotos = mysql_num_rows($campphotosquery);
    $flowimage1 = mysql_result($campphotosquery,0,'source');
    $flowimage1=str_replace("userphotos/","campaign/userphotos/medthumbs/", $flowimage1);
    $flowimage2 = mysql_result($campphotosquery,1,'source');
    $flowimage2=str_replace("userphotos/","campaign/userphotos/medthumbs/", $flowimage2);
    $flowimage3 = mysql_result($campphotosquery,2,'source');
    $flowimage3=str_replace("userphotos/","campaign/userphotos/medthumbs/", $flowimage3);
    
    echo '<div class="grid_10 push_1 fPic" id="',$id,'" style="width:600px;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> 
    <img class="dropshadow" style="border: 1px solid white;margin-left:10px;margin-top:10px;" src="graphics/newsfeedcampaignicon.png" height="60" width="60" />&nbsp;&nbsp;<span style="font-size:15px;">',$phrase,'</span>
    <br />
    <div class="phototitle" style="margin-left:85px;height:150px;width:450px;"><a style="text-decoration:none;" href="campaignphotos.php?id=',$photoid,'">';
    
    if($numcampphotos > 2) {
    echo'
    <img style="border:1px solid white;" src="',$flowimage1,'" height="148" width="145" />
    <img style="border:1px solid white;" src="',$flowimage2,'" height="148" width="145" />
    <img style="border:1px solid white;" src="',$flowimage3,'" height="148" width="145" />';
    }
    else {
    echo'<div style="text-align:center;font-size:14px;margin-top:40px;">Less than 3 entries to this campaign so far</div>';
    }
    
    echo'
    </a>
    </div>
    <div style="font-size:13px;margin-left:85px;margin-bottom:10px;clear:both;">Views: ',$views,'&nbsp;|&nbsp;Entries: ',$numcampphotos,'</div>';

    echo '</div>';
    }
                     
    elseif ($type == "photo") {
	$image = $newsrow['source'];
    $imagenew=str_replace("userphotos/","userphotos/medthumbs/", $image);
	$caption = $newsrow['caption'];
    $owner = $newsrow['emailaddress'];
    $ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$owner'";
    $ownerresult = mysql_query($ownersquery); 
    $ownerrow = mysql_fetch_array($ownerresult);
    $ownerfirst = $ownerrow['firstname'];
    $ownerlast = $ownerrow['lastname'];
    $ownerid = $ownerrow['user_id'];
    $ownerprofilepic = $ownerrow['profilepic'];
    $ownerfull = $ownerfirst . " " . $ownerlast;
    $ownerfull = ucwords($ownerfull);
	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    $phrase = "<a href='viewprofile.php?u=" . $ownerid . "'>" . $ownerfull . "</a> uploaded " . '"' . $caption . '"';
    //$phrase2 = (strlen($phrase) > 90) ? substr($phrase,0,87). " &#8230;" : $phrase;
    
    $imageinfo = mysql_query("SELECT * FROM photos WHERE source = '$image'");
    $views = mysql_result($imageinfo,0,'views');
    $points = mysql_result($imageinfo,0,'points');
    $votes = mysql_result($imageinfo,0,'votes');
    $rank = ($points / $votes);
    $rank = number_format($rank,2);
    
    list($width, $height) = getimagesize($image);
    $width = ($width / 2.5);
    $height = ($height / 2.5);

    echo '<div class="grid_10 push_1 fPic" id="',$id,'" style="width:600px;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> 
    <img class="dropshadow" style="border: 1px solid white;margin-left:10px;margin-top:10px;" src="',$ownerprofilepic,'" height="60" width="60" />&nbsp;&nbsp;<span style="font-size:15px;">',$phrase,'</span>
    <br /><a href="fullsize.php?image=',$image,'"><img class="phototitle" style="margin-left:85px;margin-bottom:15px;clear:both;" src="',$imagenew,'" width="',$width,'px" height="',$height,'px" /></a>
    <div style="font-size:13px;margin-left:85px;margin-bottom:10px;clear:both;">Views: ',$views,'&nbsp;|&nbsp;Rank: ',$rank,'</div>';
    echo '</div>';  
	}
    
    elseif ($type == "fave") {
    $owner = $newsrow['owner'];
    $ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$owner'";
    $ownerresult = mysql_query($ownersquery); 
    $ownerrow = mysql_fetch_array($ownerresult);
    $ownerfirst = $ownerrow['firstname'];
    $ownerlast = $ownerrow['lastname'];
    $ownerid = $ownerrow['user_id'];
    $ownerfull = "<a href='viewprofile.php?u=" . $ownerid . "'>" . $ownerfirst . " " . $ownerlast . "</a>";
    $ownerfull = ucwords($ownerfull);
    $firstname = $newsrow['firstname'];
    $commenteremail = $newsrow['emailaddress'];
    $commentersquery = mysql_query("SELECT user_id,profilepic FROM userinfo WHERE emailaddress = '$commenteremail'");
    $commenterpic = mysql_result($commentersquery,0,'profilepic');
    $commenterid = mysql_result($commentersquery,0,'user_id');
    $firstname = ucwords($firstname);
    $lastname = $newsrow['lastname'];
    $lastname = ucwords($lastname);
    $image = $newsrow['source'];
    $imagenew=str_replace("userphotos/","userphotos/medthumbs/", $image);
	$caption = $newsrow['caption'];
	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    $fullname = "<a href='viewprofile.php?u=" . $commenterid . "'>" . $firstname . " " . $lastname . "</a>";
    $phrase = $fullname . " favorited " . '"' . $caption . '"' . " by " . $ownerfull;
    
    $imageinfo = mysql_query("SELECT * FROM photos WHERE source = '$image'");
    $views = mysql_result($imageinfo,0,'views');
    $points = mysql_result($imageinfo,0,'points');
    $votes = mysql_result($imageinfo,0,'votes');
    $rank = ($points / $votes);
    $rank = number_format($rank,2);
    
    //Faves List Modal
    $favelistquery = mysql_query("SELECT firstname,lastname,user_id FROM userinfo WHERE faves LIKE '%$image%'");
    $numfavesinlist = mysql_num_rows($favelistquery);
    for($jjj=0; $jjj<$numfavesinlist; $jjj++) {
    $fvname = mysql_result($favelistquery,$jjj,'firstname') . " " . mysql_result($favelistquery,$jjj,'lastname');
    $fvid = mysql_result($favelistquery,$jjj,'user_id');
    $fvname = '<a href="viewprofile.php?u='.$fvid.'"'.'>' . $fvname . '</a>';
    if($jjj == 0) {
    $fvlist = $fvlist . $fvname;
    } 
    elseif($jjj > 0) {
    $fvlist = $fvlist . ", " .$fvname; }

    }
    
    list($width, $height) = getimagesize($image);
    $width = ($width / 2.5);
    $height = ($height / 2.5);
    
    echo '<div class="grid_10 push_1 fPic" id="',$id,'" style="width:600px;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> 
    <img class="dropshadow" style="border: 1px solid white;margin-left:10px;margin-top:10px;" src="',$commenterpic,'" height="60" width="60" />&nbsp;&nbsp;<span style="font-size:15px;">',$phrase,'</span>
    <br /><a href="fullsize.php?image=',$image,'"><img class="phototitle" style="margin-left:85px;margin-bottom:15px;clear:both;" src="',$imagenew,'" width="',$width,'px" height="',$height,'px" /></a>
    <div style="font-size:13px;margin-left:85px;margin-bottom:10px;clear:both;">Views: ',$views,'&nbsp;|&nbsp;Rank: ',$rank,'&nbsp;|&nbsp;Favorited By: ',$fvlist,'</div>';
    echo '</div>';   
    
    $fvlist = '';
    }
    
    elseif ($type == "trending") {
    $owner = $newsrow['owner'];
    $ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$owner'";
    $ownerresult = mysql_query($ownersquery); 
    $ownerrow = mysql_fetch_array($ownerresult);
    $ownerfirst = $ownerrow['firstname'];
    $ownerlast = $ownerrow['lastname'];
    $ownerprofilepic = $ownerrow['profilepic'];
    $ownerid = $ownerrow['user_id'];
    $ownerfull = "<a href='viewprofile.php?u=" . $ownerid . "'>" . $ownerfirst . " " . $ownerlast . "</a>";
    $ownerfull = ucwords($ownerfull);
    $image = $newsrow['source'];
    $imagenew=str_replace("userphotos/","userphotos/medthumbs/", $image);
	$caption = $newsrow['caption'];
	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    $phrase = '"' . $caption . '"' . " by " . $ownerfull . " is now trending";
    
    $imageinfo = mysql_query("SELECT * FROM photos WHERE source = '$image'");
    $views = mysql_result($imageinfo,0,'views');
    $points = mysql_result($imageinfo,0,'points');
    $votes = mysql_result($imageinfo,0,'votes');
    $rank = ($points / $votes);
    $rank = number_format($rank,2);
    
    list($width, $height) = getimagesize($image);
    $width = ($width / 2.5);
    $height = ($height / 2.5);
    
     echo '<div class="grid_10 push_1 fPic" id="',$id,'" style="width:600px;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> 
    <img class="dropshadow" style="border: 1px solid white;margin-left:10px;margin-top:10px;" src="',$ownerprofilepic,'" height="60" width="60" />&nbsp;&nbsp;<span style="font-size:15px;">',$phrase,'</span>
    <br /><a href="fullsize.php?image=',$image,'"><img class="phototitle" style="margin-left:85px;margin-bottom:15px;clear:both;" src="',$imagenew,'" width="',$width,'px" height="',$height,'px" /></a>
    <div style="font-size:13px;margin-left:85px;margin-bottom:10px;clear:both;">Views: ',$views,'&nbsp;|&nbsp;Rank: ',$rank,'</div>';
    echo '</div>';  
    }
    
    elseif ($type == "blogpost") {
    $owner = $newsrow['emailaddress'];
    $ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$owner'";
    $ownerresult = mysql_query($ownersquery); 
    $ownerrow = mysql_fetch_array($ownerresult);
    $ownerfirst = $ownerrow['firstname'];
    $ownerlast = $ownerrow['lastname'];
    $ownerprofilepic = $ownerrow['profilepic'];
    $ownerid = $ownerrow['user_id'];
    $ownerfull = "<a href='viewprofile.php?u=" . $ownerid . "'>" . $ownerfirst . " " . $ownerlast . "</a>";
    $ownerfull = ucwords($ownerfull);
    $blogid = $newsrow['source'];
    $phrase = $ownerfull . " wrote a new blog post";
    
    $bloginfo = mysql_query("SELECT * FROM blog WHERE id = '$blogid' AND emailaddress = '$owner'");
    $title = mysql_result($bloginfo,0,'title');
    $subject = mysql_result($bloginfo,0,'subject');
    $content = mysql_result($bloginfo,0,'content');
    $content = (strlen($content) > 700) ? substr($content,0,697). " &#8230;" : $content;

    $photo = mysql_result($bloginfo,0,'photo');
    $time = mysql_result($bloginfo,0,'time');
    if($time) {
    $date = date("m-d-Y", $time); }
    
    $profileshotquery = mysql_query("SELECT profilepic FROM userinfo WHERE emailaddress = '$email'");
    $profileshot = mysql_result($profileshotquery,0,'profilepic');
    
     echo '<div class="grid_10 push_1 fPic" id="',$id,'" style="width:600px;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> 
    <img class="dropshadow" style="border: 1px solid white;margin-left:10px;margin-top:10px;" src="',$ownerprofilepic,'" height="60" width="60" />&nbsp;&nbsp;<span style="font-size:15px;">',$phrase,'</span>
    <br /><div style="margin-left:85px;margin-bottom:15px;clear:both;">

            <a href="viewprofile.php?u=',$ownerid,'&view=blog#',$blogid,'">';
            if($photo) {
            echo'<div style="float:left;padding:20px;width:130px;height:130px;"><img src="',$photo,'" height="100" width="100" /></div>
            <div style="text-decoration:underline;color:black;float:left;font-size:20px;font-weight:200;padding-top:30px;width:300px;">',$title,'</div></a>
            <div style="float:left;font-size:15px;font-weight:200;padding-top:15px;">Subject: ',$subject,'&nbsp;|&nbsp;Date: ',$date,'</div>
                       
            <div style="float:left;margin-top:0px;width:450px;padding-left:20px;font-size:15px;font-weight:200;line-height:1.48;">',$content,'<br /><br />
            </div><br />';
            }
            else {
            echo'
            <div style="text-decoration:underline;color:black;float:left;font-size:20px;font-weight:200;padding-top:30px;width:450px;">',$title,'</div></a>
            <div style="float:left;font-size:15px;font-weight:200;padding-top:15px;">Subject: ',$subject,'&nbsp;|&nbsp;Date: ',$date,'</div>
                       
            <div style="float:left;margin-top:0px;width:450px;padding:20px;font-size:15px;font-weight:200;line-height:1.48;">',$content,'<br /><br />
            </div><br />';
            }
            
            
            echo'
                    <div style="float:left;margin-top:15px;margin-left:20px;width:650px;padding:10px;font-size:15px;font-weight:200;line-height:1.48;">
                    <div class="panelblog',$blogid,'">';
                    
                        //Comment Loop
                        $commentquery= mysql_query("SELECT * FROM blogcomments WHERE blogid = '$blogid'");
                        $numcomments = mysql_num_rows($commentquery);
                        
                            for($ii=0; $ii < $numcomments; $ii++) {
                                $comment = mysql_result($commentquery,$ii,'comment');
                                $commenteremail = mysql_result($commentquery,$ii,'emailaddress');
                                $userquery = mysql_query("SELECT user_id,profilepic,firstname,lastname FROM userinfo WHERE emailaddress = '$commenteremail'");
                                $commenterpic = mysql_result($userquery,0,'profilepic');
                                $commenterid = mysql_result($userquery,0,'user_id');
                                $commentername = mysql_result($userquery,0,'firstname')." ".mysql_result($userquery,0,'lastname');
                                
                                echo'<div><a href="viewprofile.php?u=',$commenterid,'"><img src="',$commenterpic,'" height="30" width="30" /><span style="font-weight:bold;color:#3e608c;font-size:12px;padding-left:10px;">',$commentername,'</a></span>&nbsp;&nbsp;',$comment,'</div><hr>';
                            }
                    echo'
                    <form action="newsfeed.php?action=comment&blogid=',$blogid,'" method="POST">
                    <div style="width:450px;"><img style="float:left;padding:10px;" src="',$profileshot,'" height="30" width="30" />
                    <input style="float:left;height:20px;position:relative;top:10px;width:380px;" type="text" name="comment" placeholder="Leave a comment&#8230;" /></div>
                    </form>
                    <br /><br />
                    </div>
                   
                    
                    <a name="',$blogid,'" href="#"><p class="flipblog',$blogid,'" style="font-size:15px;"></a>',$numcomments,' Comments</p>
                    </div>
                     </div>
                     
                    <style type="text/css">
                    p.flipblog',$blogid,' {
                    margin-left:-10px;
                    padding:10px;
                    width:470px;
                    text-align:center;
                    background:white;
                    border:solid 1px #c3c3c3;
                    }

                    p.flipblog',$blogid,':hover {
                    background-color: #ccc;
                    }

                    div.panelblog',$blogid,' {
                    display:none;
                    margin:-10px;
                    width:460px;
                    padding:15px;
                    text-align:left;
                    background:white;
                    border:solid 1px #c3c3c3;
                    }
                    </style>';
                    
                    ?>
                    
                    <!--HIDDEN COMMENT SCRIPT-->
                    <script type="text/javascript">   
                    $(document).ready(function(){
                    $(".flipblog<?php echo $blogid; ?>").click(function(){
                        $(".panelblog<?php echo $blogid; ?>").slideToggle("slow");
                    });
                    });
                    </script>
                    
                    <?php
    
    echo'
    </div></a>';  
    }
    
    elseif ($type == "follow") {
    $email4 = $newsrow['following'];
    $newaccount = "SELECT * FROM userinfo WHERE emailaddress = '$email4'";
    $accountresult = mysql_query($newaccount); 
    $accountrow = mysql_fetch_array($accountresult);
    $profilepic5 = $accountrow['profilepic'];
    $ownerid = $accountrow['user_id'];
    
    $numownerphotosquery = mysql_query("SELECT * FROM photos WHERE emailaddress = '$email4' ORDER by (points/votes) DESC");
    $numownerphotos = mysql_num_rows($numownerphotosquery);
    $flowimage1 = mysql_result($numownerphotosquery,0,'source');
    $flowimage1=str_replace("userphotos/","userphotos/medthumbs/", $flowimage1);
    $flowimage2 = mysql_result($numownerphotosquery,1,'source');
    $flowimage2=str_replace("userphotos/","userphotos/medthumbs/", $flowimage2);
    $flowimage3 = mysql_result($numownerphotosquery,2,'source');
    $flowimage3=str_replace("userphotos/","userphotos/medthumbs/", $flowimage3);
    
    $followersquery="SELECT * FROM userinfo WHERE following LIKE '%$email4%'";
	$followersresult=mysql_query($followersquery);
	$numberfollowers = mysql_num_rows($followersresult);
    
    for($iii = 0; $iii < $numownerphotos; $iii++) {
		$points = mysql_result($numownerphotosquery, $iii, "points");
        $votes = mysql_result($numownerphotosquery, $iii, "votes");
        $totalfaves = mysql_result($numownerphotosquery, $iii, "faves");
        $portfoliopoints+=$points;
        $portfoliovotes+=$votes;
        $portfoliofaves+=$totalfaves;
        }
    
    if ($portfoliovotes > 0) {
    $portfolioranking=($portfoliopoints/$portfoliovotes);
    $portfolioranking=number_format($portfolioranking, 2, '.', '');    
        }
    
    else if ($portfoliovotes < 1) {
    $portfolioranking="N/A";
        }	

    $ownerfirst = $accountrow['firstname'];
    $commenteremail = $accountrow['emailaddress'];
    $commenteremail = $newsrow['emailaddress'];
    $commentersquery = mysql_query("SELECT user_id,profilepic FROM userinfo WHERE emailaddress = '$commenteremail'");
    $commenterpic = mysql_result($commentersquery,0,'profilepic');
    $commenterid = mysql_result($commentersquery,0,'user_id');
    $ownerlast = $accountrow['lastname'];
    $firstname = $newsrow['firstname'];
    $firstname = ucwords($firstname);
    $lastname = $newsrow['lastname'];
    $lastname = ucwords($lastname);
    $fullname = "<a href='viewprofile.php?u=" . $commenterid . "'>" . $firstname . " " . $lastname. "</a>";
    $owner = $newsrow['owner'];
    $ownerfull = "<a href='viewprofile.php?u=" . $ownerid . "'>" . $ownerfirst . " " . $ownerlast . "'s</a>";
    $owner = ucwords($ownerfull);
    if($profilepic5 == "") {
    $profilepic5 = "profilepics/default_profile.jpg";
    }
    $phrase = $fullname . ' is now following ' . $owner ." photography";
    
    list($width, $height) = getimagesize($profilepic5);
    $width = ($width / 3.5);
    $height = ($height / 3.5);

    echo '<div class="grid_10 push_1 fPic" id="',$id,'" style="width:600px;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> 
    <img class="dropshadow" style="border: 1px solid white;margin-left:10px;margin-top:10px;" src="',$commenterpic,'" height="60" width="60" />&nbsp;&nbsp;<span style="font-size:15px;">',$phrase,'</span>
    <br /><a href="viewprofile.php?u=',$ownerid,'"><img class="phototitle" style="margin-left:85px;margin-bottom:15px;" src="',$profilepic5,'" width="',$width,'px" height="',$height,'px" /></a>&nbsp;&nbsp;
    <div class="phototitle" style="height:110px;width:320px;">';
    
    if($numownerphotos > 2) {
    echo'
    <img style="border:1px solid white;" src="',$flowimage1,'" height="108" width="102" />
    <img style="border:1px solid white;" src="',$flowimage2,'" height="108" width="102" />
    <img style="border:1px solid white;" src="',$flowimage3,'" height="108" width="102" />';
    }
    else {
    echo'<div style="text-align:center;font-size:14px;margin-top:40px;">',$ownerfirst,' just joined!</div>';
    }
    
    echo'
    </div>
    <div style="font-size:13px;margin-left:85px;margin-bottom:10px;clear:both;">Photos: ',$numownerphotos,'&nbsp;|&nbsp;Portfolio Average: ',$portfolioranking,'&nbsp;|&nbsp;Followers: ',$numberfollowers,'</div>';

    echo '</div>'; 
    }
    
    elseif ($type == "comment") {
    $owner = $newsrow['owner'];
    $ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$owner'";
    $ownerresult = mysql_query($ownersquery); 
    $ownerrow = mysql_fetch_array($ownerresult);
    $ownerfirst = $ownerrow['firstname'];
    $ownerlast = $ownerrow['lastname'];
    $ownerid = $ownerrow['user_id'];
    $ownerfull = "<a href='viewprofile.php?u=" . $ownerid . "'>" . $ownerfirst . " " . $ownerlast . "</a>";
    $ownerfull = ucwords($ownerfull);
    $firstname = $newsrow['firstname'];
    $commenteremail = $newsrow['emailaddress'];
    $commentersquery = mysql_query("SELECT user_id,profilepic FROM userinfo WHERE emailaddress = '$commenteremail'");
    $commenterpic = mysql_result($commentersquery,0,'profilepic');
    $commenterid = mysql_result($commentersquery,0,'user_id');
    $firstname = ucwords($firstname);
    $lastname = $newsrow['lastname'];
    $lastname = ucwords($lastname);
    $image = $newsrow['source'];
   
    $imageinfo = mysql_query("SELECT * FROM photos WHERE source = '$image'");
    $views = mysql_result($imageinfo,0,'views');
    $points = mysql_result($imageinfo,0,'points');
    $votes = mysql_result($imageinfo,0,'votes');
    $rank = ($points / $votes);
    $rank = number_format($rank,2);
    
    $imagenew=str_replace("userphotos/","userphotos/medthumbs/", $image);
    $fullname = "<a href='viewprofile.php?u=" . $commenterid . "'>" . $firstname . " " . $lastname ."</a>";
    list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    $phrase = $fullname . " commented on " . $ownerfull . "'s photo";
    
    list($width, $height) = getimagesize($image);
    $width = ($width / 2.5);
    $height = ($height / 2.5);
    
    echo '<div class="grid_10 push_1 fPic" id="',$id,'" style="width:600px;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> 
    <img class="dropshadow" style="border: 1px solid white;margin-left:10px;margin-top:10px;" src="',$commenterpic,'" height="60" width="60" />&nbsp;&nbsp;<span style="font-size:15px;">',$phrase,'</span>
    <br /><a href="fullsize.php?image=',$image,'"><img class="phototitle" style="margin-left:85px;margin-bottom:15px;clear:both;" src="',$imagenew,'" width="',$width,'px" height="',$height,'px" /></a>';
    
    $txt=".txt";
	$imagenew=str_replace("userphotos/","", $image);
	$searchchars=array('.jpg','.png','.tiff','.JPG','.jpeg','.JPEG','.gif');
	$imagenew=str_replace($searchchars,"", $imagenew);
	$file = "comments/" . $imagenew . $txt; 
	echo '<br /><br /><div style="margin-left: 85px;padding:15px;width:480px;clear:both;">
    <div class="panel">';
    @include("$file");
    echo'
    </div>
    <p class="flip" style="font-size:15px;">View comment thread</p>';
	if (@file_get_contents($file) == '') {
		echo '<div style="text-align: center;">Be the first to leave a comment!<br /><br /></div>';
	}
	echo '</div>';
    
    echo'
    <br />
    <div style="font-size:13px;margin-left:85px;margin-bottom:10px;clear:both;">Views: ',$views,'&nbsp;|&nbsp;Rank: ',$rank,'</div>';
    echo '</div>';    
    }
    
    } //end view == ''   
    
 
//UPLOAD VIEW
elseif($view == 'up') {
if ($type == "photo") {     
    $image = $newsrow['source'];
    $imagenew=str_replace("userphotos/","userphotos/medthumbs/", $image);
	$caption = $newsrow['caption'];
    $owner = $newsrow['emailaddress'];
    $ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$owner'";
    $ownerresult = mysql_query($ownersquery); 
    $ownerrow = mysql_fetch_array($ownerresult);
    $ownerfirst = $ownerrow['firstname'];
    $ownerlast = $ownerrow['lastname'];
    $ownerid = $ownerrow['user_id'];
    $ownerprofilepic = $ownerrow['profilepic'];
    $ownerfull = $ownerfirst . " " . $ownerlast;
    $ownerfull = ucwords($ownerfull);
	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    $phrase = '<a href="viewprofile.php?u=",$ownerid,"">' . $ownerfull . "</a> uploaded " . '"' . $caption . '"';
    $phrase2 = (strlen($phrase) > 90) ? substr($phrase,0,87). " &#8230;" : $phrase;
    
    $imageinfo = mysql_query("SELECT * FROM photos WHERE source = '$image'");
    $views = mysql_result($imageinfo,0,'views');
    $points = mysql_result($imageinfo,0,'points');
    $votes = mysql_result($imageinfo,0,'votes');
    $rank = ($points / $votes);
    $rank = number_format($rank,2);
    
    list($width, $height) = getimagesize($image);
    $width = ($width / 2.5);
    $height = ($height / 2.5);

    echo '<div class="grid_10 push_1 fPic" id="',$id,'" style="width:600px;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> 
    <img class="dropshadow" style="border: 1px solid white;margin-left:10px;margin-top:10px;" src="',$ownerprofilepic,'" height="60" width="60" />&nbsp;&nbsp;<span style="font-size:15px;">',$phrase2,'</span>
    <br /><a href="fullsize.php?image=',$image,'"><img class="phototitle" style="margin-left:85px;margin-bottom:15px;clear:both;" src="',$imagenew,'" width="',$width,'px" height="',$height,'px" /></a>
    <div style="font-size:13px;margin-left:85px;margin-bottom:10px;clear:both;">Views: ',$views,'&nbsp;|&nbsp;Rank: ',$rank,'</div>';
    echo '</div>';  
	}
} //end view == 'up'  



//TRENDING VIEW
elseif($view == 'tr') {
if ($type == "trending") {     
    $owner = $newsrow['owner'];
    $ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$owner'";
    $ownerresult = mysql_query($ownersquery); 
    $ownerrow = mysql_fetch_array($ownerresult);
    $ownerfirst = $ownerrow['firstname'];
    $ownerlast = $ownerrow['lastname'];
    $ownerprofilepic = $ownerrow['profilepic'];
    $ownerid = $ownerrow['user_id'];
    $ownerfull = "<a href='viewprofile.php?u=" . $ownerid . "'>" . $ownerfirst . " " . $ownerlast . "</a>";
    $ownerfull = ucwords($ownerfull);
    $image = $newsrow['source'];
    $imagenew=str_replace("userphotos/","userphotos/medthumbs/", $image);
	$caption = $newsrow['caption'];
	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    $phrase = '"' . $caption . '"' . " by " . $ownerfull . " is now trending";
    
    $imageinfo = mysql_query("SELECT * FROM photos WHERE source = '$image'");
    $views = mysql_result($imageinfo,0,'views');
    $points = mysql_result($imageinfo,0,'points');
    $votes = mysql_result($imageinfo,0,'votes');
    $rank = ($points / $votes);
    $rank = number_format($rank,2);
    
    list($width, $height) = getimagesize($image);
    $width = ($width / 2.5);
    $height = ($height / 2.5);
    
     echo '<div class="grid_10 push_1 fPic" id="',$id,'" style="width:600px;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> 
    <img class="dropshadow" style="border: 1px solid white;margin-left:10px;margin-top:10px;" src="',$ownerprofilepic,'" height="60" width="60" />&nbsp;&nbsp;<span style="font-size:15px;">',$phrase,'</span>
    <br /><a href="fullsize.php?image=',$image,'"><img class="phototitle" style="margin-left:85px;margin-bottom:15px;clear:both;" src="',$imagenew,'" width="',$width,'px" height="',$height,'px" /></a>
    <div style="font-size:13px;margin-left:85px;margin-bottom:10px;clear:both;">Views: ',$views,'&nbsp;|&nbsp;Rank: ',$rank,'</div>';
    echo '</div>';  
	}
} //end view == 'tr'  


//FAVORITE VIEW
elseif($view == 'fv') {
if ($type == "fave") {     
    $owner = $newsrow['owner'];
    $ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$owner'";
    $ownerresult = mysql_query($ownersquery); 
    $ownerrow = mysql_fetch_array($ownerresult);
    $ownerfirst = $ownerrow['firstname'];
    $ownerlast = $ownerrow['lastname'];
    $ownerid = $ownerrow['user_id'];
    $ownerfull = "<a href='viewprofile.php?u=" . $ownerid . "'>" . $ownerfirst . " " . $ownerlast . "</a>";
    $ownerfull = ucwords($ownerfull);
    $firstname = $newsrow['firstname'];
    $commenteremail = $newsrow['emailaddress'];
    $commentersquery = mysql_query("SELECT user_id,profilepic FROM userinfo WHERE emailaddress = '$commenteremail'");
    $commenterpic = mysql_result($commentersquery,0,'profilepic');
    $commenterid = mysql_result($commentersquery,0,'user_id');
    $firstname = ucwords($firstname);
    $lastname = $newsrow['lastname'];
    $lastname = ucwords($lastname);
    $image = $newsrow['source'];
    $imagenew=str_replace("userphotos/","userphotos/medthumbs/", $image);
	$caption = $newsrow['caption'];
	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    $fullname = "<a href='viewprofile.php?u=" . $commenterid . "'>" . $firstname . " " . $lastname . "</a>";
    $phrase = $fullname . " favorited " . '"' . $caption . '"' . " by " . $ownerfull;
    
    $imageinfo = mysql_query("SELECT * FROM photos WHERE source = '$image'");
    $views = mysql_result($imageinfo,0,'views');
    $points = mysql_result($imageinfo,0,'points');
    $votes = mysql_result($imageinfo,0,'votes');
    $rank = ($points / $votes);
    $rank = number_format($rank,2);
    
    //Faves List Modal
    $favelistquery = mysql_query("SELECT firstname,lastname,user_id FROM userinfo WHERE faves LIKE '%$image%'");
    $numfavesinlist = mysql_num_rows($favelistquery);
    for($jjj=0; $jjj<$numfavesinlist; $jjj++) {
    $fvname = mysql_result($favelistquery,$jjj,'firstname') . " " . mysql_result($favelistquery,$jjj,'lastname');
    $fvid = mysql_result($favelistquery,$jjj,'user_id');
    $fvname = '<a href="viewprofile.php?u='.$fvid.'"'.'>' . $fvname . '</a>';
    if($jjj == 0) {
    $fvlist = $fvlist . $fvname;
    } 
    elseif($jjj > 0) {
    $fvlist = $fvlist . ", " .$fvname; }
    }

    list($width, $height) = getimagesize($image);
    $width = ($width / 2.5);
    $height = ($height / 2.5);
    
    echo '<div class="grid_10 push_1 fPic" id="',$id,'" style="width:600px;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> 
    <img class="dropshadow" style="border: 1px solid white;margin-left:10px;margin-top:10px;" src="',$commenterpic,'" height="60" width="60" />&nbsp;&nbsp;<span style="font-size:15px;">',$phrase,'</span>
    <br /><a href="fullsize.php?image=',$image,'"><img class="phototitle" style="margin-left:85px;margin-bottom:15px;clear:both;" src="',$imagenew,'" width="',$width,'px" height="',$height,'px" /></a>
    <div style="font-size:13px;margin-left:85px;margin-bottom:10px;clear:both;">Views: ',$views,'&nbsp;|&nbsp;Rank: ',$rank,'&nbsp;|&nbsp;Favorited By: ',$fvlist,'</div>';
    echo '</div>'; 
    
      $fvlist = '';  
	}
} //end view == 'fv'  




//FOLLOW VIEW    
elseif($view == 'fw') {
if ($type == "follow") {
    $email4 = $newsrow['following'];
    $newaccount = "SELECT * FROM userinfo WHERE emailaddress = '$email4'";
    $accountresult = mysql_query($newaccount); 
    $accountrow = mysql_fetch_array($accountresult);
    $profilepic5 = $accountrow['profilepic'];
    $ownerid = $accountrow['user_id'];
    
    $numownerphotosquery = mysql_query("SELECT * FROM photos WHERE emailaddress = '$email4' ORDER by (points/votes) DESC");
    $numownerphotos = mysql_num_rows($numownerphotosquery);
    $flowimage1 = mysql_result($numownerphotosquery,0,'source');
    $flowimage1=str_replace("userphotos/","userphotos/medthumbs/", $flowimage1);
    $flowimage2 = mysql_result($numownerphotosquery,1,'source');
    $flowimage2=str_replace("userphotos/","userphotos/medthumbs/", $flowimage2);
    $flowimage3 = mysql_result($numownerphotosquery,2,'source');
    $flowimage3=str_replace("userphotos/","userphotos/medthumbs/", $flowimage3);
    
    $followersquery="SELECT * FROM userinfo WHERE following LIKE '%$email4%'";
	$followersresult=mysql_query($followersquery);
	$numberfollowers = mysql_num_rows($followersresult);
    
    for($iii = 0; $iii < $numownerphotos; $iii++) {
		$points = mysql_result($numownerphotosquery, $iii, "points");
        $votes = mysql_result($numownerphotosquery, $iii, "votes");
        $totalfaves = mysql_result($numownerphotosquery, $iii, "faves");
        $portfoliopoints+=$points;
        $portfoliovotes+=$votes;
        $portfoliofaves+=$totalfaves;
        }
    
    if ($portfoliovotes > 0) {
    $portfolioranking=($portfoliopoints/$portfoliovotes);
    $portfolioranking=number_format($portfolioranking, 2, '.', '');    
        }
    
    else if ($portfoliovotes < 1) {
    $portfolioranking="N/A";
        }	

    $ownerfirst = $accountrow['firstname'];
    $commenteremail = $accountrow['emailaddress'];
    $commenteremail = $newsrow['emailaddress'];
    $commentersquery = mysql_query("SELECT user_id,profilepic FROM userinfo WHERE emailaddress = '$commenteremail'");
    $commenterpic = mysql_result($commentersquery,0,'profilepic');
    $commenterid = mysql_result($commentersquery,0,'user_id');
    $ownerlast = $accountrow['lastname'];
    $firstname = $newsrow['firstname'];
    $firstname = ucwords($firstname);
    $lastname = $newsrow['lastname'];
    $lastname = ucwords($lastname);
    $fullname = "<a href='viewprofile.php?u=" . $commenterid . "'>" . $firstname . " " . $lastname. "</a>";
    $owner = $newsrow['owner'];
    $ownerfull = "<a href='viewprofile.php?u=" . $ownerid . "'>" . $ownerfirst . " " . $ownerlast . "'s</a>";
    $owner = ucwords($ownerfull);
    if($profilepic5 == "") {
    $profilepic5 = "profilepics/default_profile.jpg";
    }
    $phrase = $fullname . ' is now following ' . $owner ." photography";
    
    list($width, $height) = getimagesize($profilepic5);
    $width = ($width / 3.5);
    $height = ($height / 3.5);

    echo '<div class="grid_10 push_1 fPic" id="',$id,'" style="width:600px;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> 
    <img class="dropshadow" style="border: 1px solid white;margin-left:10px;margin-top:10px;" src="',$commenterpic,'" height="60" width="60" />&nbsp;&nbsp;<span style="font-size:15px;">',$phrase,'</span>
    <br /><a href="viewprofile.php?u=',$ownerid,'"><img class="phototitle" style="margin-left:85px;margin-bottom:15px;" src="',$profilepic5,'" width="',$width,'px" height="',$height,'px" /></a>&nbsp;&nbsp;
    <div class="phototitle" style="height:110px;width:320px;">';
    
    if($numownerphotos > 2) {
    echo'
    <img style="border:1px solid white;" src="',$flowimage1,'" height="108" width="102" />
    <img style="border:1px solid white;" src="',$flowimage2,'" height="108" width="102" />
    <img style="border:1px solid white;" src="',$flowimage3,'" height="108" width="102" />';
    }
    else {
    echo'<div style="text-align:center;font-size:14px;margin-top:40px;">',$ownerfirst,' just joined!</div>';
    }
    
    echo'
    </div>
    <div style="font-size:13px;margin-left:85px;margin-bottom:10px;clear:both;">Photos: ',$numownerphotos,'&nbsp;|&nbsp;Portfolio Average: ',$portfolioranking,'&nbsp;|&nbsp;Followers: ',$numberfollowers,'</div>';

    echo '</div>'; 

    }
} //end view == 'fw'    


//COMMENTS VIEW
elseif($view == 'cmt') {
    if ($type == "comment") {     
    $owner = $newsrow['owner'];
    $ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$owner'";
    $ownerresult = mysql_query($ownersquery); 
    $ownerrow = mysql_fetch_array($ownerresult);
    $ownerfirst = $ownerrow['firstname'];
    $ownerlast = $ownerrow['lastname'];
    $ownerid = $ownerrow['user_id'];
    $ownerfull = "<a href='viewprofile.php?u=" . $ownerid . "'>" . $ownerfirst . " " . $ownerlast . "</a>";
    $ownerfull = ucwords($ownerfull);
    $firstname = $newsrow['firstname'];
    $commenteremail = $newsrow['emailaddress'];
    $commentersquery = mysql_query("SELECT user_id,profilepic FROM userinfo WHERE emailaddress = '$commenteremail'");
    $commenterpic = mysql_result($commentersquery,0,'profilepic');
    $commenterid = mysql_result($commentersquery,0,'user_id');
    $firstname = ucwords($firstname);
    $lastname = $newsrow['lastname'];
    $lastname = ucwords($lastname);
    $image = $newsrow['source'];
   
    $imageinfo = mysql_query("SELECT * FROM photos WHERE source = '$image'");
    $views = mysql_result($imageinfo,0,'views');
    $points = mysql_result($imageinfo,0,'points');
    $votes = mysql_result($imageinfo,0,'votes');
    $rank = ($points / $votes);
    $rank = number_format($rank,2);
    
    $imagenew=str_replace("userphotos/","userphotos/medthumbs/", $image);
    $fullname = "<a href='viewprofile.php?u=" . $commenterid . "'>" . $firstname . " " . $lastname ."</a>";
    list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    $phrase = $fullname . " commented on " . $ownerfull . "'s photo";
    
    list($width, $height) = getimagesize($image);
    $width = ($width / 2.5);
    $height = ($height / 2.5);
    
    echo '<div class="grid_10 push_1 fPic" id="',$id,'" style="width:600px;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> 
    <img class="dropshadow" style="border: 1px solid white;margin-left:10px;margin-top:10px;" src="',$commenterpic,'" height="60" width="60" />&nbsp;&nbsp;<span style="font-size:15px;">',$phrase,'</span>
    <br /><a href="fullsize.php?image=',$image,'"><img class="phototitle" style="margin-left:85px;margin-bottom:15px;clear:both;" src="',$imagenew,'" width="',$width,'px" height="',$height,'px" /></a>';
    
    $txt=".txt";
	$imagenew=str_replace("userphotos/","", $image);
	$searchchars=array('.jpg','.png','.tiff','.JPG','.jpeg','.JPEG','.gif');
	$imagenew=str_replace($searchchars,"", $imagenew);
	$file = "comments/" . $imagenew . $txt; 
	echo '<br /><br /><div style="margin-left: 85px;padding:15px;width:480px;clear:both;">
    <div class="panel">';
    @include("$file");
    echo'
    </div>
    <p class="flip" style="font-size:15px;">View comment thread</p>';
	if (@file_get_contents($file) == '') {
		echo '<div style="text-align: center;">Be the first to leave a comment!<br /><br /></div>';
	}
	echo '</div>';
    
    echo'
    <br />
    <div style="font-size:13px;margin-left:85px;margin-bottom:10px;clear:both;">Views: ',$views,'&nbsp;|&nbsp;Rank: ',$rank,'</div>';
    echo '</div>';    
    }
} //end view == 'cmt'    
      
} //end for loop


//CAMPAIGN VIEW
if($view == 'cmp') {
$cmpquery = mysql_query("SELECT * FROM newsfeed WHERE type = 'campaign'ORDER BY id DESC");
$numcamps = mysql_num_rows($cmpquery);
for($i=0; $i < $numcamps; $i++) {
$photoid = mysql_result($cmpquery,$i,'source');
$caption = mysql_result($cmpquery,$i,'caption');

    $quotequery = mysql_query("SELECT quote,views FROM campaigns WHERE id = '$photoid'");
    $quote = mysql_result($quotequery,0,'quote');
    $views = mysql_result($quotequery,0,'views');
    $phrase = 'New Campaign: (Reward: $'. $quote .') "'. $caption . '"';
    
    $campphotosquery = mysql_query("SELECT source FROM campaignphotos WHERE campaign = '$photoid' ORDER by (points/votes) DESC");
    $numcampphotos = mysql_num_rows($campphotosquery);
    $flowimage1 = mysql_result($campphotosquery,0,'source');
    $flowimage1=str_replace("userphotos/","campaign/userphotos/medthumbs/", $flowimage1);
    $flowimage2 = mysql_result($campphotosquery,1,'source');
    $flowimage2=str_replace("userphotos/","campaign/userphotos/medthumbs/", $flowimage2);
    $flowimage3 = mysql_result($campphotosquery,2,'source');
    $flowimage3=str_replace("userphotos/","campaign/userphotos/medthumbs/", $flowimage3);
    
    echo '<div class="grid_10 push_1 fPic" id="',$id,'" style="width:600px;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> 
    <img class="dropshadow" style="border: 1px solid white;margin-left:10px;margin-top:10px;" src="graphics/newsfeedcampaignicon.png" height="60" width="60" />&nbsp;&nbsp;<span style="font-size:15px;">',$phrase,'</span>
    <br />
    <div class="phototitle" style="margin-left:85px;height:150px;width:450px;"><a style="text-decoration:none;" href="campaignphotos.php?id=',$photoid,'">';
    
    if($numcampphotos > 2) {
    echo'
    <img style="border:1px solid white;" src="',$flowimage1,'" height="148" width="145" />
    <img style="border:1px solid white;" src="',$flowimage2,'" height="148" width="145" />
    <img style="border:1px solid white;" src="',$flowimage3,'" height="148" width="145" />';
    }
    else {
    echo'<div style="text-align:center;font-size:14px;margin-top:40px;">Less than 3 entries to this campaign so far</div>';
    }
    
    echo'
    </a>
    </div>
    <div style="font-size:13px;margin-left:85px;margin-bottom:10px;clear:both;">Views: ',$views,'&nbsp;|&nbsp;Entries: ',$numcampphotos,'</div>';

    echo '</div>'; 
    
	}
} //end view == 'cmp'  

?>

</div>    

<?php

echo'
<div id="loadMoreNewsfeedNew" style="display: none; text-align: center; margin-top: 25px;">Loading...</div>';

echo '<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-800) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMorePics").show();
				$.ajax({
					url: "loadMoreNewsfeedNew.php?view=',$view,'&lastPicture=" + $(".fPic:last").attr("id") + "&first=', $firstname, ' + &last=', $lastname, ' + &email=', $email, '",
					success: function(html) {
						if(html) {
							$("#thepics").append(html);
							$("div#loadMoreNewsfeedNew").hide();
						}
					}
				});
				last = $(".fPic:last").attr("id");
			}
		}
	});
</script>';

echo'
</div>
</div>';    

?>


</div>
</body>
</html>