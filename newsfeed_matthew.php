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
  
    //Fix Sidebar Left
    $(window).scroll(function(){
    if  ($(window).scrollTop() >= 229){
          $('#suggestedphotos').css({position:'fixed',top:400});
    } 
    });
    
    </script>

</head>
<body style="overflow-x:hidden;background-color:#eee;">

<?php navbar(); ?>

<!-----------------------Begin Container---------------------->
<div id="newsbg" class="container_24" style="width:1050px;position:relative;left:35px;">

<!-----------------------Begin Left Column---------------------->

<div class="grid_9" style="margin-top:70px;">

    <!-----------------------Title------------------------------->
    
    <div class="newsTitle">My News</div>

    <!--------------------Menu Options--------------------------->
    
    <ul class="followBoxes menu">
        <a class="link" href="newsfeed.php"><li><img src="graphics/galleries_b.png" />All News</li></a>
        <a class="link" href="newsfeed.php?view=uploads"><li><img src="graphics/camera.png" />Uploads</li></a>
        <a class="link" href="newsfeed.php?view=comments"><li><img src="graphics/collection_i.png" />Comments</li></a>
        <a class="link" href="newsfeed.php?view=favorites"><li><img src="graphics/fave_i.png" />Favorites</li></a>
    </ul>
    
    <!-------------------Suggested Photographers----------------->
    <?php
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
        
        echo'<ul class="followBoxes">';
        
		for($iii=0; $iii < 4; $iii++) {
            $firstname = mysql_result($displayresult, $iii, "firstname");
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
             
            $numownerphotosquery = mysql_query("SELECT * FROM photos WHERE emailaddress = '$profileemail' ORDER BY (points/votes) DESC");
            $numownerphotos = mysql_num_rows($numownerphotosquery);
            $photo1 = mysql_result($numownerphotosquery,0,'source');
            $photo1 = str_replace('userphotos/','userphotos/medthumbs/',$photo1);
            $photo2 = mysql_result($numownerphotosquery,1,'source');
            $photo2 = str_replace('userphotos/','userphotos/medthumbs/',$photo2);
            $photo3 = mysql_result($numownerphotosquery,2,'source');
            $photo3 = str_replace('userphotos/','userphotos/medthumbs/',$photo3);
            $photo4 = mysql_result($numownerphotosquery,3,'source');
            $photo4 = str_replace('userphotos/','userphotos/medthumbs/',$photo4);           
            
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
        ?>
        
        <script type="text/javascript">
        //Show Follower Images
        jQuery(document).ready(function(){
            jQuery("#showImages<?php echo $profileid; ?>").live("click", function(event) {        
                jQuery("#hiddenImages<?php echo $profileid; ?>").toggle();
            });
        });
        </script>
        
        <?php

                    echo'<li>
                            <img style="float:left;" src="https://photorankr.com/',$profilepic,'" />
                            <div class="innerFollowBox">
                                <div id="name" style="width:245px;">
                                    ',$name,' <br />
                                    <span id="smaller">Followed by ',$numberfollowers,' photographers</span>
                                </div>
                                
                                <!--Top Images from this Photographer---->';
                                if($numownerphotos > 4) {
                                echo'<div style="float:left;width:250px;font-size:13px;">
                                        <div style="color:#3b5998;cursor:pointer;" id="showImages',$profileid,'">Top Images from ',$firstname,'</div>
                                     </div>
                                     </div>
                                     <div id="hiddenImages',$profileid,'" class="hiddenFollowerImages">
                                        <img src="https://photorankr.com/',$photo1,'" />
                                        <img src="https://photorankr.com/',$photo2,'" />
                                        <img src="https://photorankr.com/',$photo3,'" />
                                        <img src="https://photorankr.com/',$photo4,'" />
                                     </div>';
                                }
                             echo'
                        </li>';
		}
        
                
        echo'</ul>
        
        <!---------------------Trending Photography------------------->';
                   
            echo'<div class="trendingBox">
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
			
                        echo'<div class="fPic" id="',$views,'" style="float:left;width:169px;height:169px;padding-left:1px;padding-bottom:1px;overflow:hidden;">
                        
						<a href="viewprofile.php?u=',$profileid,'"><img style="min-width:169px;min-height:169px;" src="https://photorankr.com/',$trendingimage2,'" /></a>
                        
                        <div style="height:15px;background-color:rgba(34,34,34,.8);width:169px;position:relative;top:-30px;padding:8px;color:#fff;font-weight:300;font-size:14px;">',$caption,'</div>
                        
                    </div>';            
                        
                        }
                    
                echo'</div>';
                

?>

</div>

<!-----------------------End Left Column---------------------->

<!-----------------------Begin Newsfeed----------------------->

  <!--NEWSFEED-->
    <div class="grid_14" id="thepics" style="width:650px;margin-top:70px;position:relative;left:30px;">
    <div id="main" style=">
    <ul id="tiles">
    
<?php
if(isset($_GET['view'])) {
$view=htmlentities($_GET['view']);
}

$followresult = mysql_query("SELECT following FROM userinfo WHERE emailaddress = '$email'");
$followlist = mysql_result($followresult, 0, "following");
$newsfeedquery = "SELECT * FROM newsfeed WHERE (owner IN ($followlist) OR emailaddress IN ($followlist)) AND emailaddress NOT IN ('$email','') AND type NOT IN ('reply','message') ORDER BY id DESC LIMIT 0,40";
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
    $newsemail = $newsrow['emailaddress'];    
    $photoowner = $newsrow['owner'];
    $time = $newsrow['time'];
    $time = converttime($time);
    $emailfollowing = $newsrow['following'];
    $type = $newsrow['type'];
    $source = $newsrow['source'];
    $firstname = $newsrow['firstname'];
    $lastname = $newsrow['lastname'];
    $idcomment = $newsrow['imageid'];
    
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
    $phrase = "<a href='viewprofile.php?u=" . $ownerid . "'>" . $ownerfull . "</a> uploaded a photo";
    
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
    $tag1 = mysql_result($imageinfo,0,'tag1');
    $tag2 = mysql_result($imageinfo,0,'tag2');
    $tag3 = mysql_result($imageinfo,0,'tag3');
    $tag4 = mysql_result($imageinfo,0,'tag4');
    
    list($width, $height) = getimagesize($image2);
    $width = ($width / 3.2);
    $height = ($height / 3.2);

    echo'<div class="grid_16">
         <!--Profile Picture-->
         <div class="profilepic"><img src="https://photorankr.com/',$ownerprofilepic,'" /></div>
         
         <!--Content Box-->
         <div class="newsContainer">
            <div class="newsTriangle"></div>
                <div class="newsItem">
                    <!--Top Controls-->
                    <div class="newsControls">
                        <div class="newsName">',$phrase,'</div>
                        <div class="newsTools">
                            <span id="time">',$time,'</span>
                            <img src="graphics/rank_b_c.png">
                            <img src="graphics/fave_b_c.png">
                            <img src="graphics/market_b_c.png">
                        </div>
                    </div>
                    <!--Content-->
                    <div class="newsContent">
                        <img src="https://photorankr.com/',$image,'" />
                    </div>
                    <!---Stats--->
                    <div class="newsStats">
                        Stats
                        <ul>
                            <li><img src="graphics/view.png" width="15" /> Views: ',$views,'</li>';
                        
                        if($camera) {
                            echo'<li><img src="graphics/camera.png" width="15" /> Camera: ',$camera,'</li>';
                        }
                        if($lens) {
                            echo'<li><img src="graphics/lens.png" width="15" /> Lens: ',$lens,'</li>';
                        }
                        if($focallength) {
                            echo'<li><img src="graphics/focal-length.png" width="15" /> Focal Length: ',$focallength,'</li>';
                        }
                        if($aperture) {
                            echo'<li><img src="graphics/aperature.png" width="15" /> Aperture: ',$aperture,'</li>';
                        }
                        if($shutterspeed) {
                            echo'<li><img src="graphics/shutter-speed.png" width="15" /> Shutter Speed: ',$shutterspeed,'</li>';
                        }   
                        echo'
                        </ul>
                    </div>
                    <!--About Photo-->';
                    if($about) {
                    echo'<div style="font-size:14px;font-weight:700;padding:10px 0 0 20px;">Behind the lens</div>
                        <div class="newsAbout">
                           ',$about,'
                        </div>';
                    }
                    if($tag1 || $tag2 || $tag3 || $tag4) {
                    echo'<div class="newsTagBox">
                        <ul class="tags">';
                            if($tag1) {
                                echo'<li><a href="#">',$tag1,'</a></li>';
                            }
                            if($tag2) {
                                echo'<li><a href="#">',$tag2,'</a></li>';
                            }
                            if($tag3) {
                                echo'<li><a href="#">',$tag3,'</a></li>';
                            }
                            if($tag4) {
                                echo'<li><a href="#">',$tag4,'</a></li>';
                            }
                            echo'
                        </ul>
                    </div>';
                    }
                 echo'
                </div>
         <!--End Content Box-->
         </div>
         
         </div>';
    
    } //end type upload
    
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
		$id = $newsrow['id'];
		$getimageid = mysql_query("SELECT id FROM photos WHERE source = '$image'");
		$oldimageid = mysql_result($getimageid,0,'id');
	   
		$imageinfo = mysql_query("SELECT * FROM photos WHERE (id = '$image' OR id = '$oldimageid')");
		$views = mysql_result($imageinfo,0,'views');
		$points = mysql_result($imageinfo,0,'points');
		$about = mysql_result($imageinfo,0,'about');
		$imageID = mysql_result($imageinfo,0,'id');
		$source = mysql_result($imageinfo,0,'source');
		$votes = mysql_result($imageinfo,0,'votes');
		$rank = ($points / $votes);
		$rank = number_format($rank,2);
        
        ?>
        
        <script type="text/javascript">
        //Comment jQuery Script
        $(function() {
        $("#commentform<?php echo $id; ?>").submit(function() 
        {
        var firstname = '<?php echo $sessionfirst; ?>';
        var lastname = '<?php echo $sessionlast; ?>';
        var email = '<?php echo $email; ?>';
        var userpic = '<?php echo $sessionpic; ?>';
        var viewerid = '<?php echo $sessionid; ?>';
        var imageid = '<? echo $imageID; ?>';
        var comment = $("#comment<?php echo $id; ?>").val();
        var dataString = 'firstname='+ firstname + '&lastname=' + lastname + '&email=' + email + '&comment=' + comment + '&userpic=' + userpic + '&viewerid=' + viewerid + '&imageid=' + imageid;
        $("#flash").show();
        $("#flash").fadeIn(400).html();
        $.ajax({
        type: "POST",
        url: "newsfeedcomment.php",
        data: dataString,
        cache: false,
        success: function(html){
        $("ol#update<?php echo $id; ?>").append(html);
        $("ol#update li:last").fadeIn("slow");
        $("#flash").hide();
        }
        });
        return false;
        }); });
                
        //Javascript for showing hidden comments
        jQuery(document).ready(function(){
            jQuery("#commentOption<?php echo $id; ?>").live("click", function(event) {        
                jQuery("#hiddenComments<?php echo $id; ?>").toggle();
            });
        });
        
        //Display textarea
        $(function() 
        {
        $("#comment<?php echo $id; ?>").focus(function()
        {
        $(this).animate({"height": "85px",}, "fast" );
        $("#button_block<?php echo $id; ?>").slideDown("fast");
        return false;
        });
        
        $("#comment<?php echo $id; ?>").focusout(function()
        {
        $(this).animate({"height": "20px",}, "fast" );
        $("#button_block<?php echo $id; ?>").slideUp("fast");
        return false;
        });

        
        });
        </script>
        
        <style type="text/css">
            #hiddenComments<?php echo $id; ?> {
                display:none;
            }
            #commentOption<?php echo $id; ?> {
                font-size:13px;
                padding-top:10px;
                font-weight:700;
                cursor:pointer;
            }
            #button_block<?php echo $id; ?> {
                display:none;
            }
            #button<?php echo $id; ?> {
                background-color:#33C33C;
                color:#ffffff;
                font-size:13px;
                font-weight:bold;
                padding:3px;
                margin-left:40px;
            }
        </style>
        
        <?php
        
        //Get user's comment
        $lastcommentquery = mysql_query("SELECT comment FROM comments WHERE id = '$idcomment' LIMIT 0,1");
        $lastcomment = mysql_result($lastcommentquery,0,'comment');
        
         //All Previous Comments
        $grabcomments = mysql_query("SELECT * FROM comments WHERE imageid = '$imageID' ORDER BY id DESC");
        $numcomments = mysql_num_rows($grabcomments);
		
		$imagenew=str_replace("userphotos/","userphotos/medthumbs/", $source);
		$fullname = "<a href='viewprofile.php?u=" . $commenterid . "'>" . $firstname . " " . $lastname ."</a>";
		list($width, $height) = getimagesize($image);
		$imgratio = $height / $width;
		$height = $imgratio * $maxwidth;
		$phrase = $fullname . " commented on " . $ownerfull . "'s photo";
		
		list($width, $height) = getimagesize($source);
		$width = ($width / 2.5);
		$height = ($height / 2.5);
        
        echo'<div class="grid_16">
         <!--Profile Picture-->
         <div class="profilepic"><img src="https://photorankr.com/',$commenterpic,'" /></div>
         
         <!--Content Box-->
         <div class="newsContainer">
            <div class="newsTriangle"></div>
                <div class="newsItem">
                    <!--Top Controls-->
                    <div class="newsControls">
                        <div class="newsName">',$fullname,' > ',$ownerfull,'</div>
                        <div class="newsTools">
                            <span id="time">',$time,'</span>
                            <img src="graphics/rank_b_c.png">
                            <img src="graphics/fave_b_c.png">
                            <img src="graphics/market_b_c.png">
                        </div>
                    </div>
                    <!--Content-->
                    <div class="commentPhoto">
                        <img src="https://photorankr.com/',$source,'" />
                    </div>
                    <div class="commentBox">
                        <blockquote>
                            <p>',$lastcomment,'</p>
                        </blockquote>';
                        //If previous comments, display option to show them
                    if($numcomments > 1) {
                        echo'<div id="commentOption',$id,'">Show all ',$numcomments,' comments</div>';
                    }
                    echo'
                    </div>
                    <!--Comment Box-->
                    <div class="postCommentBox">
                         <form action="#" id="commentform',$id,'" method="post" style="margin-top:5px;padding-bottom:5px;"> 
                        <img style="float:left;" src="https://photorankr.com/',$sessionpic,'" height="30" width="30"  />
                        <textarea id="comment',$id,'"  style="margin-left:5px;width:445px;height:20px;" placeholder="Leave feedback for ',$firstname,'&#8230;"></textarea>
                        <div id="button_block',$id,'">
                         <input type="submit" id="button',$id,'" class="btn btn-success" value=" Comment "/>
                        </div>
                        </form>
                    </div>
                    
                    <!--AJAX COMMENTS-->
                        <ol id="update',$id,'" class="timeline">
                        </ol>
                    
                    <!--Previous Comments-->
                    <div id="hiddenComments',$id,'" class="previousComments">
                        <ul class="indPrevComment">';

                             for($iii = 0; $iii < $numcomments; $iii++) {
                                $prevcomment = mysql_result($grabcomments,$iii,'comment');
                                $commentid = mysql_result($grabcomments,$iii,'id');
                                $commenttime = mysql_result($grabcomments,$iii,'time');
                                $commenttime = converttime($commenttime);
                                $commenteremail = mysql_result($grabcomments,$iii,'commenter');
                                $commenterinfo = mysql_query("SELECT user_id,firstname,lastname,profilepic,reputation FROM userinfo WHERE emailaddress = '$commenteremail'");
                                $commentername = mysql_result($commenterinfo,0,'firstname') ." ". mysql_result($commenterinfo,0,'lastname');
                                $commenterid = mysql_result($commenterinfo,0,'user_id');
                                $commenterpic = mysql_result($commenterinfo,0,'profilepic');            
                                $commenterrep = number_format(mysql_result($commenterinfo,0,'reputation'),2);

                                echo'<li style="overflow:hidden;"> 
                                        <div style="width:35px;float:left;"><img src="https://photorankr.com/',$commenterpic,'" height="35" width="35" /></div>
                                        <div style="width:460px;float:left;" id="commenterName" style="float:left;"><a href="viewprofile.php?u=',$commenterid,'">',$commentername,'</a>
                                        <div style="float:right">',$commenttime,'</div>
                                        <div id="commentText">
                                            ',$prevcomment,'
                                        </div>
                                        </div>
                                     
                                     </li>';
                        
                            }
                            
                            echo'
                        </ul>
                    </div>
                    
                </div>
         <!--End Content Box-->
         </div>
         
         </div>';
        
    } //end type == 'comments'
    
        
    elseif($type == 'follow') {
    
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

         echo'<div class="grid_16">
         <!--Profile Picture-->
         <div class="profilepic"><img src="https://photorankr.com/',$commenterpic,'" /></div>
         
         <!--Content Box-->
         <div class="newsContainer">
            <div class="newsTriangle"></div>
                <div class="newsItem"s>
                    <!--Top Controls-->
                    <div class="newsControls">
                        <div class="newsName">',$phrase,'</div>
                        <br />
                        <div class="newsTools">
                            <span id="time">',$time,'</span>
                        </div>
                    </div>
                    <!--Content-->
                    <div id="followContent">';
                       if($numownerphotos > 2) {
                        echo'
                            <img style="border:1px solid white;" src="https://photorankr.com/',$flowimage1,'" />
                            <img style="border:1px solid white;" src="https://photorankr.com/',$flowimage2,'"  />
                            <img style="border:1px solid white;" src="https://photorankr.com/',$flowimage3,'" />';
                        }
                        else {
                            echo'<div style="text-align:center;font-size:14px;margin-top:40px;">',$ownerfirst,' just joined!</div>';
                        }
                       echo'
                    </div>
                </div>
         <!--End Content Box-->
         </div>
         
         </div>';
    
    } //end type == 'follow'
        
    } //end view == ''
    
} //end of for loop
    
    echo'</ul>';
    
?>

<!-----------------------End Newsfeed----------------------->

<!-----------------------End of Container--------------------->
</div>
</body>
</html>