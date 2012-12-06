<?php

//connect to the database
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

    $email = $_SESSION['email'];
    
    $findreputationme = mysql_query("SELECT user_id,reputation,profilepic,firstname,lastname,following FROM userinfo WHERE emailaddress = '$email'");
    $reputationme = mysql_result($findreputationme,0,'reputation');
    $sessionpic = mysql_result($findreputationme,0,'profilepic');
    $sessionuserid =  mysql_result($findreputationme,0,'user_id');
    $sessionfirst =  mysql_result($findreputationme,0,'firstname');
    $sessionlast =  mysql_result($findreputationme,0,'lastname');
    $sessionid =  mysql_result($findreputationme,0,'user_id');
    $sessionfollowing =  mysql_result($findreputationme,0,'following');
    $sessionname = mysql_result($findreputationme,0,'firstname') ." ". mysql_result($findreputationme,0,'lastname');
    $followersquery = mysql_query("SELECT * FROM userinfo WHERE following LIKE '%$email%'");
    $numberfollowers = mysql_num_rows($followersquery);
    $followingquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress IN ($sessionfollowing)");
    $numberfollowing = mysql_num_rows($followingquery);
    $userphotos = mysql_query("SELECT id FROM photos WHERE emailaddress = '$email'");
    $numphotos = mysql_num_rows($userphotos);
    $currenttime = time();
    
       
    if (!$_SESSION['email']) {
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

//Grab the view
if(isset($_GET['view'])) {
$view=htmlentities($_GET['view']);
}

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


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="https://www.w3.org/1999/xhtml">

<head>

 <meta name="Generator" content="EditPlus">
  <meta name="Author" content="PhotoRankr, PhotoRankr.com">
  <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
  <meta name="Description" content="A gallery of the newest photography, photographers, and exhibits on PhotoRankr.">
     <meta name="viewport" content="width=1200" /> 

	 <link rel="stylesheet" type="text/css" href="css/style.css"/>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="css/960grid.css"/>
	<link rel="stylesheet" type="text/css" href="css/reset.css"/> 
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
    
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.wookmark.js"></script>            
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>

    <title>PhotoRankr - Your Personal News Feed</title>

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
  
//AJAX comments
$(function() {
$(".submit").click(function() 
{
var firstname = '<?php echo $sessionfirst; ?>';
var lastname = '<?php echo $sessionlast; ?>';
var email = '<?php echo $email; ?>';
var userpic = '<?php echo $sessionpic; ?>';
var viewerid = '<?php echo $sessionid; ?>';
var viewerrep = '<?php echo $reputationme; ?>';
var photo = $("#photoid").val();
var comment = $("#comment").val();
var dataString = 'firstname='+ firstname + '&lastname=' + lastname + '&email=' + email + '&comment=' + comment + '&userpic=' + userpic + '&photo=' + photo + '&viewerid=' + viewerid + '&viewerrep=' + viewerrep;
if(email=='' || comment=='')
{
alert('Please Give Valid Details');
}
else
{
$("#flash").show();
$("#flash").fadeIn(400).html();
$.ajax({
type: "POST",
url: "newscommentajax.php",
data: dataString,
cache: false,
success: function(html){
$("ol#update").append(html);
$("ol#update li:last").fadeIn("slow");
$("#flash").hide();
}
});
}return false;
}); });
    
    //Fix Sidebar Left
    $(window).scroll(function(){
    if  ($(window).scrollTop() >= 229){
          $('#suggestedphotos').css({position:'fixed',top:400});
    } 
    });
    
</script>

</head>
<body style="overflow-x:hidden;background-color:rgb(244,244,244);">

<?php navbar(); ?>

   <!--big container-->
    <div id="container" class="container_24" style="width:1040px;">
         
     <div id="newsHeader" style="margin-top:60px;">
		<header> My News </header>
		<ul>
			<li <?php if($view == 'uploads') {echo'style="background: rgba(51,51,51,.5);"';} ?> > <img src="graphics/camera.png"> Uploads </li>
			<li <?php if($view == 'collections') {echo'style="background: rgba(51,51,51,.5);"';} ?> > <img src="graphics/collection_i.png"> Collections </li>
			<li <?php if($view == 'favorites') {echo'style="background: rgba(51,51,51,.5);"';} ?> > <img src="graphics/fave_i.png"> Favorites </li>
			<li <?php if($view == 'exhibits') {echo'style="background: rgba(51,51,51,.5);"';} ?> > <img src="graphics/collection_i.png"> Exhibits </li>
			<li <?php if($view == '') {echo'style="background: rgba(51,51,51,.5);"';} ?> > <img src="graphics/galleries_b.png"> All </li>
		</ul>
	</div>	
                     

    <?php
    
    //SUGGESTED PHOTOS AND PHOTOGRAPHERS

            //Right sidebar
            echo'<div class="grid_7">';
            
            echo'<div class="grid_7 filled rounded shadow">
                    <div class="cartText" style="font-size:22px;">Suggested Photographers</div>';
                    
                    //Suggested photographers
                    
                    //PHOTOGRAPHERS THEY MIGHT LIKE
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
                        $displayquery = "SELECT firstname, lastname, profilepic,user_id FROM userinfo WHERE emailaddress IN($followinglist) AND emailaddress NOT IN('$email',                           $followinglistowner, 'support@photorankr.com') AND reputation > 10 ORDER BY RAND() LIMIT 6";		
                        $displayresult = mysql_query($displayquery) or die(mysql_error());
                        $numdisplayresult = mysql_num_rows($displayresult);
                    }
	
                    //loop through the people, printing out their name and profile picture
        
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
            
                        echo'<div class="fPic" id="',$views,'" style="float:left;height:130px;max-width:140px;padding-left:1px;padding-bottom:1px;overflow:hidden;">
						<a href="viewprofile.php?u=',$profileid,'"><img style="height:130px;width:130px;" src="https://photorankr.com/',$profilepic,'" /></a>
                        
                        <div style="height:10px;background-color:rgba(34,34,34,.8);width:115px;position:relative;top:-25px;padding:8px;color:#fff;font-weight:300;font-size:13px;">',$name,'</div>
                        
                    </div>';
                    
                        }
            
                echo'</div>';
                
                
                //Bottom right sidebar trending photographers
            
            echo'<div class="grid_7 filled rounded shadow">
                    <div class="cartText" style="font-size:22px;">Trending Photography</div>';
                    
                    $trendingnow = mysql_query("SELECT * FROM photos ORDER BY score DESC LIMIT 0,8");
                    
                    for($iii=0; $iii<8; $iii++) {
                        $trendingimage = mysql_result($trendingnow, $iii, 'source');
                        $trendingimagenew = '../' . $trendingimage;
                        $trendingimage2 = str_replace("userphotos/","userphotos/medthumbs/", $trendingimage);
                        $caption = mysql_result($trendingnow,$iii,'caption');
                        $views = mysql_result($trendingnow,$iii,'views');
                        $points = mysql_result($trendingnow,$iii,'points');
                        $votes = mysql_result($trendingnow,$iii,'votes');
                        $about = mysql_result($trendingnow,$iii,'about');
                        $imageid = mysql_result($trendingnow,$iii,'id');
                        $rank = ($points / $votes);
                        $rank = number_format($rank,2);
                                
                        list($width, $height) = getimagesize($trendingimagenew);
                        $width = ($width / 5.5);
                        $height = ($height / 5.5);
			
                        echo'<div class="fPic" id="',$views,'" style="float:left;max-width:130px;height:130px;padding-left:1px;padding-bottom:1px;overflow:hidden;">
                        
						<a href="viewprofile.php?u=',$profileid,'"><img style="width:130px;" src="https://photorankr.com/',$trendingimage2,'" /></a>
                        
                        <div style="height:15px;background-color:rgba(34,34,34,.8);width:115px;position:relative;top:-30px;padding:8px;color:#fff;font-weight:300;font-size:14px;">',$caption,'</div>
                        
                    </div>';            
                        
                        }
                    
                    echo'</div>';
                
                
                //Bottom right sidebar suggested photos
            
             echo'<div class="grid_7 filled rounded shadow" id="suggestedphotos">
                    <div class="cartText" style="font-size:22px;">Suggested Photography</div>';
                    
                    //Suggested photographers
                    
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
		$favesquery = "SELECT source, MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$favetags') AS matching FROM photos WHERE MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$favetags') AND source NOT IN($faveslistowner) ORDER BY RAND() LIMIT 8";
		$favesresult = mysql_query($favesquery);
	}
	//otherwise they had no faves so pick four random photos
	else {	
		$favesquery = "SELECT source FROM photos WHERE faves > 6 ORDER BY RAND() LIMIT 8";
		$favesresult = mysql_query($favesquery);
	}

	//see if they had enough information to display photos
	if(mysql_num_rows($favesresult) >= 4) {
		for($iii=0; $iii < 8; $iii++) {
        $image = mysql_result($favesresult, $iii, "source");
        $imagenew = '../' . $image;
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
        $about = mysql_result($imageinfo,0,'about');
        $imageid = mysql_result($imageinfo,0,'id');
        $rank = ($points / $votes);
        $rank = number_format($rank,2);
        
        list($width, $height) = getimagesize($imagenew);
        $width = ($width / 5.5);
        $height = ($height / 5.5);
			
            echo'<div class="fPic" id="',$views,'" style="float:left;max-width:130px;height:130px;padding-left:1px;padding-bottom:1px;overflow:hidden;">
                        
						<a href="viewprofile.php?u=',$profileid,'"><img style="width:130px;" src="https://photorankr.com/',$image2,'" /></a>
                        
                        <div style="height:15px;background-color:rgba(34,34,34,.8);width:115px;position:relative;top:-30px;padding:8px;color:#fff;font-weight:300;font-size:14px;">',$caption,'</div>
                        
            </div>';    
                 
            }
            
                echo'</div>';
        }   
        
        echo'</div>';
?>

    <!--NEWSFEED-->
    <div class="grid_17 push_2" id="thepics" style="width:750px;margin-top:0px;">
    <div id="main" style=">
    <ul id="tiles">
    
<?php
if(isset($_GET['view'])) {
$view=htmlentities($_GET['view']);
}

$followresult = mysql_query("SELECT following FROM userinfo WHERE emailaddress = '$email'");
$followlist = mysql_result($followresult, 0, "following");
$newsfeedquery = "SELECT * FROM newsfeed WHERE (owner IN ($followlist) OR emailaddress IN ($followlist)) AND emailaddress NOT IN ('$email','') AND type NOT IN ('message','reply') ORDER BY id DESC LIMIT 0,40";
$newsfeedresult = mysql_query($newsfeedquery);
$newsfeedresult2 = mysql_query($newsfeedquery);
$maxwidth = 400;

for($ii=0; $ii <= 19; $ii++) {
    
    if($postemail[mysql_result($newsfeedresult2,$ii,'emailaddress')] < 1) {
        $idlist = $idlist.mysql_result($newsfeedresult2,$ii,'id')." ";
    }
    
    $postemail[mysql_result($newsfeedresult2,$ii,'emailaddress')] += 1;
    
}
               
for($iii=0; $iii <= 39; $iii++) {
    
    $newsrow = mysql_fetch_array($newsfeedresult);
    $id = $newsrow['id'];
    $pos = strpos($idlist,$id);
   
    //if($pos !== FALSE) {

    $newsemail = $newsrow['emailaddress'];    
    $photoowner = $newsrow['owner'];
    $time = $newsrow['time'];
    $time = converttime($time);
    $emailfollowing = $newsrow['following'];
    $type = $newsrow['type'];
    $source = $newsrow['source'];
    $firstname = $newsrow['firstname'];
    $lastname = $newsrow['lastname'];
    
if($view == '') {

    if ($type == "photo") {
	$image = $newsrow['source'];
    $imagenew=str_replace("userphotos/","userphotos/medthumbs/", $image);
    $image2 = "../" . $image;
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
    $phrase = "<a href='viewprofile.php?u=" . $ownerid . "'>" . $ownerfull . "</a> uploaded " . $caption;
    
    $imageinfo = mysql_query("SELECT * FROM photos WHERE source = '$image'");
    $views = mysql_result($imageinfo,0,'views');
    $points = mysql_result($imageinfo,0,'points');
    $votes = mysql_result($imageinfo,0,'votes');
    $faves = mysql_result($imageinfo,0,'faves');
    $about = mysql_result($imageinfo,0,'about');
    $camera = mysql_result($imageinfo,0,'camera');
    $id = mysql_result($imageinfo,0,'id');
    $lens = mysql_result($imageinfo,0,'lens');
    $filter = mysql_result($imageinfo,0,'filter');
    $aperture = mysql_result($imageinfo,0,'aperture');
    $shutterspeed = mysql_result($imageinfo,0,'shutterspeed');
    $rank = ($points / $votes);
    $rank = number_format($rank,2);
    
    list($width, $height) = getimagesize($image2);
    $width = ($width / 3.2);
    $height = ($height / 3.2);

    echo'<div id="uploadItem"> 
			<!--Header with Info-->
			<header> 
				<ul>
					<li> <img src="https://photorankr.com/',$ownerprofilepic,'" height="55" width="55" /> </li>
					<li>
						 <div class="nameEvent">
						 	<header style="width:430px;overflow:hidden;">',$phrase,'</header>
						 	<p><img src="graphics/time.png"/> ',$time,' </p>
						 </div>
					</li>
					<li> </li>
					<li> <span> ',$faves,' </span> <img src="graphics/fave_b_c.png"/> </li>
					<li> <span> ',$rank,'/10 </span> <img src="graphics/rank_b_c.png"/> </li>
					<li> <span> 6 Shares </span> <img src="graphics/share_b.png"/><div class="commentTriangle"></div> </li>
				</ul>
			 </header>

			 <div class="bottomContainer">

			 	<div class="middleImgContainer">
			 		 <a href="fullsize.php?imageid=',$id,'"><img style="padding:4px;" src="https://photorankr.com/',$imagenew,'" width="',$width,'px" height="',$height,'px" /></a>
			 	</div>

			 	<div class="newsInfoCol">
			 		<header> About </header>
			 		<ul>';
                    if($rank) {
				echo'<li><img src="graphics/rank.png" width="15" />  Rank: <span>',$rank,'</span></li>';
                }
                if($views) {
				echo'<li><img src="graphics/view.png" width="15" />  Views: <span>',$views,'</span></li>';
                }
                if($camera) {
				echo'<li><img src="graphics/camera.png" width="15" /> Camera: <span>',$camera,'</span></li>';
                }
                if($aperture) {
				echo'<li><img src="graphics/aperature.png" width="15" /> Aperture: <span>',$aperture,'</span></li>';
                }
                if($focallength) {
				echo'<li> <img src="graphics/focal-length.png" width="15" /> Focal Length:  <span>',$focallength,'</span> </li>';
                }
                if($lens) {
				echo'<li> <img src="graphics/lens.png" width="15" /> Lens: <span>',$lens,'</span> </li>';
                }
                if($shutterspeed) {
				echo'<li> <img src="graphics/shutter-speed.png" width="15" /> Shutter: <span>',$shutterspeed,'</span> </li>';
                }
                if($uploaded) {
				echo'<li> <img src="graphics/time.png" width="15" /> Uploaded: <span>',$uploaded,'</span> </li>';
                }
           
                echo'
                </ul>';
                    
                    if($about) {
			 		echo'<header> Behind the Lens </header>
			 			<article> ',$about,' </article>';
                    }
                
                echo'
			 	</div>
            </div>
        </div>';
    
    } //end type upload
    
    elseif ($type == "comment") {
	$caption = $newsrow['caption'];
    $owner = $newsrow['emailaddress'];
    $ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$photoowner'";
    $ownerresult = mysql_query($ownersquery); 
    $ownerrow = mysql_fetch_array($ownerresult);
    $ownerfirst = $ownerrow['firstname'];
    $ownerlast = $ownerrow['lastname'];
    $ownerid = $ownerrow['user_id'];
    $ownerprofilepic = $ownerrow['profilepic'];
    $ownerfull = $ownerfirst . " " . $ownerlast;
    $ownerfull = ucwords($ownerfull);
    $commenteremail = $newsrow['emailaddress'];
    $commentersquery = mysql_query("SELECT user_id,profilepic FROM userinfo WHERE emailaddress = '$commenteremail'");
    $commenterpic = mysql_result($commentersquery,0,'profilepic');
    $commenterid = mysql_result($commentersquery,0,'user_id');
	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    
    $imageinfo = mysql_query("SELECT * FROM photos WHERE id = '$source' OR source = '$source'");
    $views = mysql_result($imageinfo,0,'views');
    $points = mysql_result($imageinfo,0,'points');
    $about = mysql_result($imageinfo,0,'about');
    $imageID = mysql_result($imageinfo,0,'id');
    $imagesource = mysql_result($imageinfo,0,'source');
    $imagesource = "../" . $imagesource;
    $votes = mysql_result($imageinfo,0,'votes');
    $rank = ($points / $votes);
    $rank = number_format($rank,2);
    $caption = mysql_result($imageinfo,0,'caption');

    $imagenew=str_replace("userphotos/","userphotos/medthumbs/", $imagesource);
    $fullname = "<a href='viewprofile.php?u=" . $commenterid . "'>" . $firstname . " " . $lastname ."</a>";
    list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    $phrase = $firstname . " " . $lastname . " commented on " . $ownerfull . "'s photo";
    
    list($width, $height) = getimagesize($imagesource);
    $width = ($width / 3.2);
    $height = ($height / 3.2);
    
     echo'<div id="uploadItem"> 
			<!--Header with Info-->
			<header> 
				<ul>
					<li> <img src="https://photorankr.com/',$commenterpic,'" height="55" width="55" /> </li>
					<li>
						 <div class="nameEvent">
						 	<header style="width:430px;overflow:hidden;">',$phrase,'</header>
						 	<p><img src="graphics/time.png"/> ',$time,' </p>
						 </div>
					</li>
					<li> </li>
					<li> <span> ',$faves,' </span> <img src="graphics/fave_b_c.png"/> </li>
					<li> <span> ',$rank,'/10 </span> <img src="graphics/rank_b_c.png"/> </li>
					<li> <span> 6 Shares </span> <img src="graphics/share_b.png"/><div class="commentTriangle"></div> </li>
				</ul>
			 </header>

			 <div class="bottomContainer">

			 	<div class="middleImgContainer">
			 		 <a href="fullsize.php?imageid=',$id,'"><img style="padding:4px;" src="https://photorankr.com/',$imagenew,'" width="',$width,'px" height="',$height,'px" /></a>
			 	</div>

			 	<div class="newsInfoCol">
			 		<header> About </header>
			 		<ul>';
                    if($rank) {
				echo'<li><img src="graphics/rank.png" width="15" />  Rank: <span>',$rank,'</span></li>';
                }
                if($views) {
				echo'<li><img src="graphics/view.png" width="15" />  Views: <span>',$views,'</span></li>';
                }
                if($camera) {
				echo'<li><img src="graphics/camera.png" width="15" /> Camera: <span>',$camera,'</span></li>';
                }
                if($aperture) {
				echo'<li><img src="graphics/aperature.png" width="15" /> Aperture: <span>',$aperture,'</span></li>';
                }
                if($focallength) {
				echo'<li> <img src="graphics/focal-length.png" width="15" /> Focal Length:  <span>',$focallength,'</span> </li>';
                }
                if($lens) {
				echo'<li> <img src="graphics/lens.png" width="15" /> Lens: <span>',$lens,'</span> </li>';
                }
                if($shutterspeed) {
				echo'<li> <img src="graphics/shutter-speed.png" width="15" /> Shutter: <span>',$shutterspeed,'</span> </li>';
                }
                if($uploaded) {
				echo'<li> <img src="graphics/time.png" width="15" /> Uploaded: <span>',$uploaded,'</span> </li>';
                }
           
                echo'
                </ul>';
                    
                    if($about) {
			 		echo'<header> Behind the Lens </header>
			 			<article> ',$about,' </article>';
                    }
                
                echo'
			 	</div>';
            
                 //AJAX COMMENT
                if($_SESSION['loggedin'] == 1) {
                    echo'
                    <form action="#" method="post" style="margin-top:5px;padding-bottom:5px;">        
           
                    <div id="comment">
                        <div class="commentTag">
                        <img src="https://photorankr.com/',$sessionpic,'" height="55" width="50" />
                        </div>
                    </div>
            
                        <textarea id="photocomment" style="margin-top:15px;width:615px;height:50px;" placeholder="Leave feedback for ',$firstname,'&#8230;"></textarea>
                        <input style="margin-right:45px;float:right;" type="submit" class="submit btn btn-success" value="Comment"/>
                    </form>
        
                    <!--AJAX COMMENTS-->
                    <div class="float:left;"> 
                        <ol id="update" class="timeline">
                        </ol>
                    </div>';
                }
                
                ?>
                
                <script type="text/javascript">
                //Show Comments
                jQuery(document).ready(function(){
                    jQuery("#showComments<?php echo $id; ?>").live("click", function(event) {        
                    jQuery("#hideComments<?php echo $id; ?>").toggle();
                    });
                });
                </script>
                
                <style type="text/css">
                #hideComments<?php echo $id; ?> {
                    display:none;
                }
                #showComments<?php echo $id; ?> {
                    font-size:14px;
                    width:750px;;
                    text-align:center;
                    height:30px;
                    background-color: rgb(240,240,240);
                    margin-top:5px;
                }
                </style>
    
                <?php
                
                //Previous Comments
                $grabcomments = mysql_query("SELECT * FROM comments WHERE imageid = '$imageID' ORDER BY id DESC");
                $numcomments = mysql_num_rows($grabcomments);
                
                echo'<div id="showComments',$id,'"><div style="padding:10px;">View ',$numcomments,' comments&hellip;</div></div>';
                
                echo'<div id="hideComments',$id,'">';
                    
                    for($iii = 0; $iii < $numcomments; $iii++) {
                    $comment = mysql_result($grabcomments,$iii,'comment');
                    $commentid = mysql_result($grabcomments,$iii,'id');
                    $commenttime = mysql_result($grabcomments,$iii,'time');
                    //$commenttime = converttime($commenttime);
                    $commenteremail = mysql_result($grabcomments,$iii,'commenter');
                    $commenterinfo = mysql_query("SELECT user_id,firstname,lastname,profilepic,reputation FROM userinfo WHERE emailaddress = '$commenteremail'");
                    $commentername = mysql_result($commenterinfo,0,'firstname') ." ". mysql_result($commenterinfo,0,'lastname');
                    $commenterid = mysql_result($commenterinfo,0,'user_id');
                    $commenterpic = mysql_result($commenterinfo,0,'profilepic');
                    $commenterrep = number_format(mysql_result($commenterinfo,0,'reputation'),2);
                    
                    echo'<div id="comment">
                            <div class="commentTag">
                                <img src="https://photorankr.com/',$commenterpic,'"/>
                                <header> Rep: ',$commenterrep,' </header>
                            </div>
                            <div class="commentName">
                                <header><a href-"viewprofile.php?u=',$commenterid,'">',$commentername,'</a></header>
                                <img src="graphics/uploadDate.png"/>
                                <p> ',$commenttime,' </p>
                            </div>
                            <div class="commentBody">
                                <p> ',$comment,' </p>
                            </div>
                        </div>';
                  }
                
            echo'
            </div>
        </div>
    </div>';
    
    } //end type comment 
    
    elseif ($type == "fave" || $type == "discoverfave") {
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
    $image2 = "../" . $image;
	$caption = $newsrow['caption'];

    $fullname = "<a href='viewprofile.php?u=" . $commenterid . "'>" . $firstname . " " . $lastname . "</a>";
    if($type == "fave") {
        $phrase = $fullname . " favorited " . $caption;
    }
    elseif($type == "discoverfave") {
        $phrase = $fullname . " discovered " . $caption;
    }
    $imageinfo = mysql_query("SELECT * FROM photos WHERE source = '$image'");
    $views = mysql_result($imageinfo,0,'views');
    $points = mysql_result($imageinfo,0,'points');
    $votes = mysql_result($imageinfo,0,'votes');
    $about = mysql_result($imageinfo,0,'about');
    $camera = mysql_result($imageinfo,0,'camera');
    $lens = mysql_result($imageinfo,0,'lens');
    $filter = mysql_result($imageinfo,0,'filter');
    $aperture = mysql_result($imageinfo,0,'aperture');
    $shutterspeed = mysql_result($imageinfo,0,'shutterspeed');
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
    
    list($width, $height) = getimagesize($image2);
    $width = ($width / 3.2);
    $height = ($height / 3.2);
    
    echo'
     <div id="uploadItem"> 
			<!--Header with Info-->
			<header> 
				<ul>
					<li> <img src="https://photorankr.com/',$commenterpic,'" height="55" width="55" /> </li>
					<li>
						 <div class="nameEvent">
						 	<header style="width:430px;overflow:hidden;">',$phrase,'</header>
						 	<p><img src="graphics/time.png"/> ',$time,' </p>
						 </div>
					</li>
					<li>  </li>
					<li> <span> ',$faves,' </span> <img src="graphics/fave_b_c.png"/> </li>
					<li> <span> ',$rank,'/10 </span> <img src="graphics/rank_b_c.png"/> </li>
					<li> <span> 6 Shares </span> <img src="graphics/share_b.png"/><div class="commentTriangle"></div> </li>
				</ul>
			 </header>

			 <div class="bottomContainer">

			 	<div class="middleImgContainer">
			 		 <a href="fullsize.php?imageid=',$id,'"><img style="padding:4px;" src="https://photorankr.com/',$imagenew,'" width="',$width,'px" height="',$height,'px" /></a>
			 	</div>

			 	<div class="newsInfoCol">
			 		<header> About </header>
			 		<ul>';
                    if($ownerfull) {
                    echo'<li><img src="graphics/rank.png" width="15" />  By: <span>',$ownerfull,'</span></li>';
                    }
                    if($rank) {
				echo'<li><img src="graphics/rank.png" width="15" />  Rank: <span>',$rank,'</span></li>';
                }
                if($views) {
				echo'<li><img src="graphics/view.png" width="15" />  Views: <span>',$views,'</span></li>';
                }
                if($camera) {
				echo'<li><img src="graphics/camera.png" width="15" /> Camera: <span>',$camera,'</span></li>';
                }
                if($aperture) {
				echo'<li><img src="graphics/aperature.png" width="15" /> Aperture: <span>',$aperture,'</span></li>';
                }
                if($focallength) {
				echo'<li> <img src="graphics/focal-length.png" width="15" /> Focal Length:  <span>',$focallength,'</span> </li>';
                }
                if($lens) {
				echo'<li> <img src="graphics/lens.png" width="15" /> Lens: <span>',$lens,'</span> </li>';
                }
                if($shutterspeed) {
				echo'<li> <img src="graphics/shutter-speed.png" width="15" /> Shutter: <span>',$shutterspeed,'</span> </li>';
                }
                if($uploaded) {
				echo'<li> <img src="graphics/time.png" width="15" /> Uploaded: <span>',$uploaded,'</span> </li>';
                }
           
                echo'
                </ul>';
                    
                    if($about) {
			 		echo'<header> Behind the Lens </header>
			 			<article> ',$about,' </article>';
                    }
                
                echo'
			 	</div>
            </div>
        </div>';

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
    $image = "../" . $image;
	$caption = $newsrow['caption'];
	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
	$height = $imgratio * $maxwidth;
    $phrase = '"' . $caption . '"' . " by " . $ownerfull . " is now trending";
    
    $imageinfo = mysql_query("SELECT * FROM photos WHERE source = '$image'");
    $views = mysql_result($imageinfo,0,'views');
    $points = mysql_result($imageinfo,0,'points');
    $votes = mysql_result($imageinfo,0,'votes');
    $about = mysql_result($imageinfo,0,'about');
    $rank = ($points / $votes);
    $rank = number_format($rank,2);
    
    list($width, $height) = getimagesize($image);
    $width = ($width / 2.5);
    $height = ($height / 2.5);
    
     echo '<div class="grid_16 newsItem fPic" id="',$id,'"> 
    <img class="dropshadow" style="float:left;border: 1px solid white;margin-left:10px;margin-top:10px;" src="',$ownerprofilepic,'" height="60" width="60" />&nbsp;&nbsp;<div style="float:left;font-size:15px;padding:10px;">',$phrase,'';
    
    if($time > 0) {
        echo'
        <br /><span style="font-size:12px;color:#777;font-weight:400;padding:2px;">',converttime($time),'</span>';
    }
    
    echo'
    </div>
    <br /><a href="fullsize.php?image=',$image,'"><img class="phototitle" style="margin-left:85px;margin-bottom:15px;clear:both;" src="https://photorankr.com/',$imagenew,'" width="',$width,'px" height="',$height,'px" /></a>';
    
    if($about) {
        echo'
        <br /><div style="float:left;margin-left:85px;font-size:12px;color:#777;font-weight:400;padding:2px;width:460px;padding-bottom:10px;">',$about,'</div>';
    }
    
    echo'
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
    
     echo '<div class="grid_10 push_1 fPic" id="',$id,'" style="width:600px;"> 
    <img class="dropshadow" style="float:left;border: 1px solid white;margin-left:10px;margin-top:10px;" src="',$ownerprofilepic,'" height="60" width="60" />&nbsp;&nbsp;<div style="float:left;font-size:15px;padding:10px;">',$phrase,'';
    
    if($time > 0) {
        echo'
        <br /><div style="float:left;font-size:12px;color:#777;font-weight:400;padding:2px;">',converttime($time),'</div>';
    }
    
    echo'
    </div>
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
    $newaccount = "SELECT * FROM userinfo WHERE emailaddress = '$emailfollowing'";
    $accountresult = mysql_query($newaccount); 
    $accountrow = mysql_fetch_array($accountresult);
    $profilepic5 = $accountrow['profilepic'];
    $ownerid = $accountrow['user_id'];
    
    $numownerphotosquery = mysql_query("SELECT source FROM photos WHERE emailaddress = '$emailfollowing' ORDER by (points/votes) DESC LIMIT 4");
    $numownerphotos = mysql_num_rows($numownerphotosquery);
    $flowimage1 = mysql_result($numownerphotosquery,0,'source');
    $flowimage1=str_replace("userphotos/","userphotos/medthumbs/", $flowimage1);
    $flowimage2 = mysql_result($numownerphotosquery,1,'source');
    $flowimage2=str_replace("userphotos/","userphotos/medthumbs/", $flowimage2);
    $flowimage3 = mysql_result($numownerphotosquery,2,'source');
    $flowimage3=str_replace("userphotos/","userphotos/medthumbs/", $flowimage3);
    $flowimage4 = mysql_result($numownerphotosquery,3,'source');
    $flowimage4=str_replace("userphotos/","userphotos/medthumbs/", $flowimage4);
    
    $followersquery="SELECT * FROM userinfo WHERE following LIKE '%$emailfollowing%'";
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

     echo'
     <div id="uploadItem"> 
			<!--Header with Info-->
			<header> 
				<ul>
					<li> <img src="https://photorankr.com/',$commenterpic,'" height="55" width="55" /> </li>
					<li>
						 <div class="nameEvent">
						 	<header style="width:750px;overflow:hidden;">',$phrase,'</header>
						 	<p><img src="graphics/time.png"/> ',$time,' </p>
						 </div>
					</li>
                </ul>
			 </header>

			 <div class="bottomContainer">

			 	<div style="width:750px;">';
                
                    if($numownerphotos > 3) {
                        echo'
                            <img style="float:left;padding:1px;" src="https://photorankr.com/',$flowimage1,'" height="185" width="185" />
                            <img style="float:left;padding:1px;" src="https://photorankr.com/',$flowimage2,'" height="185" width="185" />
                            <img style="float:left;padding:1px;" src="https://photorankr.com/',$flowimage3,'" height="185" width="185" />
                            <img style="float:left;padding:1px;" src="https://photorankr.com/',$flowimage4,'" height="185" width="185" />';
                    }
                    else {
                        echo'<div style="text-align:center;font-size:14px;margin-top:40px;">',$ownerfirst,' just joined!</div>';
                    }
                    
                    echo'
			 	</div>
            </div>
            
            <div style="font-size:14px;font-weight:300;margin-left:10px;padding-top:5px;margin-bottom:10px;clear:both;">Photos: ',$numownerphotos,'&nbsp;|&nbsp;Portfolio Average: ',$portfolioranking,'&nbsp;|&nbsp;Followers: ',$numberfollowers,'</div>
            
        </div>';

    }
    
    
     elseif ($type == "exhibitfave") {
    
    $set = $newsrow['source'];
    $setinfo = mysql_query("SELECT title,cover,faves,about FROM sets WHERE id = '$set'");
    $settitle = mysql_result($setinfo,0,'title');
    $setfaves = mysql_result($setinfo,0,'faves');
    $aboutset = mysql_result($setinfo,0,'about');
    $setcover = mysql_result($setinfo,0,'cover');
    $pulltopphoto = mysql_query("SELECT source FROM photos WHERE set_id = '$set' ORDER BY votes DESC");
    
    if($setcover == '') {
        $setcover = mysql_result($pulltopphoto, 0, "source");
        $setcover = str_replace("userphotos/","userphotos/medthumbs/",$setcover);
    }
    
    $numsetphotos = mysql_num_rows($pulltopphoto);
    $thumb1 = mysql_result($pulltopphoto, 1, "source");
    $thumb1 = str_replace("userphotos/","userphotos/medthumbs/",$thumb1);
    $thumb2 = mysql_result($pulltopphoto, 2, "source");
    $thumb2 = str_replace("userphotos/","userphotos/medthumbs/",$thumb2);
    $thumb3 = mysql_result($pulltopphoto, 3, "source");
    $thumb3 = str_replace("userphotos/","userphotos/medthumbs/",$thumb3);
    $thumb4 =mysql_result($pulltopphoto, 4, "source");
    $thumb4 = str_replace("userphotos/","userphotos/medthumbs/",$thumb4);

    $commenteremail = $newsrow['emailaddress'];
    $commentersquery = mysql_query("SELECT user_id,profilepic FROM userinfo WHERE emailaddress = '$commenteremail'");
    $commenterpic = mysql_result($commentersquery,0,'profilepic');
    $commenterid = mysql_result($commentersquery,0,'user_id');
    $firstname = ucwords($newsrow['firstname']);
    $lastname = ucwords($newsrow['lastname']);
    $fullname = "<a href='viewprofile.php?u=" . $commenterid . "'>" . $firstname . " " . $lastname. "</a>";
    $owneremail = $newsrow['owner'];
    $findowner = mysql_query("SELECT user_id,firstname,lastname FROM userinfo WHERE emailaddress = '$owneremail'");
    $ownername = mysql_result($findowner,0,'firstname') ." ". mysql_result($findowner,0,'lastname'); 
    $ownerid = mysql_result($findowner,0,'user_id');
    $ownerfull = "<a href='viewprofile.php?u=" . $ownerid . "'>" . $ownername . "'s</a>";
    $owner = ucwords($ownerfull);
    $phrase = $fullname . ' favorited ' . $owner ." exhibit: <a href='viewprofile.php?u=" . $ownerid . "&view=exhibits&set=" . $set . "'>".$settitle."</a>";
    
    list($width, $height) = getimagesize($setcover);
    $width = ($width / 4.5);
    $height = ($height / 4.5);

    echo '<div class="grid_10 push_1 fPic" id="',$id,'" style="width:600px;"> 
    <img class="dropshadow" style="float:left;border: 1px solid white;margin-left:10px;margin-top:10px;" src="',$commenterpic,'" height="60" width="60" />&nbsp;&nbsp;<div style="float:left;font-size:15px;padding:10px;width:490px;">',$phrase,'';
    
    if($time > 0) {
        echo'
        <br /><div style="float:left;font-size:11px;color:#777;font-weight:400;padding:2px;">',converttime($time),'</div>';
    }
    
    echo'
    </div>
    <br /><a href="viewprofile.php?u=',$ownerid,'&view=exhibits&set=',$set,'"><img class="phototitle" style="margin-left:20px;margin-top:15px;margin-bottom:15px;" src="',$setcover,'" width="140px" /></a>&nbsp;&nbsp;
    <div class="phototitle" style="height:110px;width:320px;">';
    
    if($numsetphotos > 2) {
    echo'
    <a style="clear:both;" href="viewprofile.php?u=',$ownerid,'&view=exhibits&set=',$set,'">
    <img style="border:1px solid white;" src="',$thumb1,'" height="108" width="102" />
    <img style="border:1px solid white;" src="',$thumb2,'" height="108" width="102" />
    <img style="border:1px solid white;" src="',$thumb3,'" height="108" width="102" />
    </a>';
    }
    
    echo'
    </div>';
    
    if($aboutset) {
    echo'
    <div style="width:520px;clear:both;padding:20px;margin-left:65px;">
    <span style="font-weight:400;font-size:15px;">About Exhibit:</span> <span style="font-weight:200;font-size:14px;">',$aboutset,'</span>
    </div>';
    }
    
    echo'
    <div style="font-size:13px;margin-left:85px;margin-bottom:10px;margin-bottom:30px;clear:both;"># Photos: ',$numsetphotos,'&nbsp;|&nbsp;Exhibit Favorites: ',$setfaves,'&nbsp;</div>';

    echo '</div>'; 
    }
    
    elseif($type == "articlecomment") {
    $articleowner = $newsrow['emailaddress'];
    $ownersquery = "SELECT * FROM userinfo WHERE emailaddress = '$articleowner'";
    $ownerresult = mysql_query($ownersquery); 
    $ownerrow = mysql_fetch_array($ownerresult);
    $ownerfirst = $ownerrow['firstname'];
    $ownerlast = $ownerrow['lastname'];
    $ownerprofilepic = $ownerrow['profilepic'];
    $ownerid = $ownerrow['user_id'];
    $ownerfull = "<a href='viewprofile.php?u=" . $ownerid . "'>" . $ownerfirst . " " . $ownerlast . "</a>";
    $ownerfull = ucwords($ownerfull);
    $blogid = $newsrow['source'];
    $articleinfo = mysql_query("SELECT title,contents FROM entries WHERE id = $blogid");
    $articletitle = mysql_result($articleinfo,0,'title');
    $articletitle = "<a href='post.php?a=" . $blogid . "'>" . $articletitle . "</a>";
    $content = mysql_result($articleinfo,0,'contents');
    $content = (strlen($content) > 1250) ? substr($content,0,1247). " &#8230;" : $content;
    $phrase = $ownerfull . " commented on '$articletitle'";
    
    $profileshotquery = mysql_query("SELECT profilepic FROM userinfo WHERE emailaddress = '$email'");
    $profileshot = mysql_result($profileshotquery,0,'profilepic');
    
     echo '<div class="grid_10 push_1 fPic" id="',$id,'" style="width:600px;"> 
   
     <img class="dropshadow" style="float:left;border: 1px solid white;margin-left:10px;margin-top:10px;" src="',$ownerprofilepic,'" height="60" width="60" />
     
     &nbsp;&nbsp;
    
    <div style="float:left;font-size:15px;padding:10px;width:490px;">',$phrase,'</div>
    
        <br /><div style="float:left;font-size:12px;color:#777;font-weight:400;padding-left:10px;">',converttime($time),'</div>
    
    <br />
                           
        <div style="float:left;margin-top:20px;margin-left:85px;width:450px;font-size:15px;font-weight:200;line-height:1.48;">',$content,'<br /><br /></div>

            
    </div></div>'; 
    
        }
    
    } //end view == ''  
    
    elseif($view == 'uploads') {
    
    
    
    } //end view == 'uploads'
    
    elseif($view == 'favorites') {
    
    
    
    } //end view == 'favorites'
    
    elseif($view == 'comments') {
    
    
    
    } //end view == 'comments'
    
    
} //end of for loop
    
    echo'</ul>';
    
?>
    
    <!-- Once the page is loaded, initalize the plug-in. -->
  <script type="text/javascript">
    $(document).ready(new function() {
      // Prepare layout options.
      var options = {
        autoResize: true, // This will auto-update the layout when the browser window is resized.
        container: $('#main'), // Optional, used for some extra CSS styling
        offset: 4, // Optional, the distance between grid items
        itemWidth: 370 // Optional, the width of a grid item
      };
      
      // Get a reference to your grid items.
      var handler = $('#tiles li');
      
      // Call the layout function.
      handler.wookmark(options);
      
    });
  </script>
  
</div></div>

</div><!--end of container-->

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script src="js/bootstrap-dropdown.js"></script>
    <script type="text/javascript">  
        $(document).ready(function () {  
            $('.dropdown-toggle').dropdown();  
        });  
    
</script>

</body>
</html>