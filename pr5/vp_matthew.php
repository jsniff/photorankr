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

    //QUERY FOR NOTIFICATIONS
    $currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$email'";
    $currentnotsquery = mysql_query($currentnots);
    $currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");

    //notifications query reset 
    if($currentnotsresult > 0) {
    $notsquery = "UPDATE userinfo SET notifications = 0 WHERE emailaddress = '$email6'";
    $notsqueryrun = mysql_query($notsquery); }

//User information
$userinfo = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$email'");
$profilepic = mysql_result($userinfo,0,'profilepic');
$firstname= mysql_result($userinfo,0,'firstname');
$lastname = mysql_result($userinfo,0,'lastname');
$fullname = $firstname ." ". $lastname;
$age = mysql_result($userinfo,0,'age');
$gender = mysql_result($userinfo,0,'gender');
$location = mysql_result($userinfo,0,'location');
$camera = mysql_result($userinfo,0,'camera');
$facebookpage = mysql_result($userinfo,0,'facebookpage');
$twitterpage = mysql_result($userinfo,0,'twitterpage');
$bio = mysql_result($userinfo,0,'bio');
$quote = mysql_result($userinfo,0,'quote');
$reputation = mysql_result($userinfo,0,'reputation');
$reputation = number_format($reputation,1);
$profileviews = mysql_result($userinfo,0,'profileviews');

//Blog Information
$blogquery = mysql_query("SELECT * FROM blog WHERE emailaddress = '$email' ORDER BY id DESC");
$numblogposts = mysql_num_rows($blogquery);
$newestpost =  mysql_result($blogquery,0,'content');
$posttime =  mysql_result($blogquery,0,'time');
$postdate = '10/24/12';

    //Photos
    $userphotosquery = mysql_query("SELECT points,votes,faves,price,sold,width,height,views FROM photos WHERE emailaddress = '$email'");
    $numphotos = mysql_num_rows($userphotosquery);
    for($iii = 0; $iii < $numphotos; $iii++) {
		$points = mysql_result($userphotosquery, $iii, "points");
        $votes = mysql_result($userphotosquery, $iii, "votes");
        $totalfaves = mysql_result($userphotosquery, $iii, "faves");
        $price = mysql_result($userphotosquery, $iii, "price");
        $views = mysql_result($userphotosquery, $iii, "views");
        $width = mysql_result($userphotosquery, $iii, "width");
        $height = mysql_result($userphotosquery, $iii, "height");
        $sold = mysql_result($userphotosquery, $iii, "sold");
        $portfoliopoints += $points;
        $portfoliovotes += $votes;
        $portfoliofaves += $totalfaves;
        $portfolioprice += $price;
        $portfoliowidth += $width;
        $portfolioheight += $height;
        $portfoliosold += $sold;
        $portfolioviews += $views;
        if($width && $height) {
            $numresphotos += 1;
        }
    }
    if($portfoliovotes > 0) {
        $portfolioranking=($portfoliopoints/$portfoliovotes);
        $portfolioranking=number_format($portfolioranking, 2, '.', '');
    }
    elseif($portfoliovotes < 1) {
        $portfolioranking="N/A";
    }
        $avgprice = number_format(($portfolioprice/$numphotos), 2);
        $avgwidth = number_format(($portfoliowidth/$numresphotos), 0);
        $avgheight = number_format(($portfolioheight/$numresphotos), 0);
        $portfolioviews = number_format($portfolioviews, 0);

//Portfolio Information
    $followersquery="SELECT * FROM userinfo WHERE following LIKE '%$email%'";
	$followersresult=mysql_query($followersquery);
	$numberfollowers = mysql_num_rows($followersresult);
    
    //Grab Overall Portfolio Ranking
    $userphotos="SELECT * FROM photos WHERE emailaddress = '$email'";
    $userphotosquery=mysql_query($userphotos);
    $numphotos=mysql_num_rows($userphotosquery);
    
    for($iii = 0; $iii < $numphotos; $iii++) {
		$points = mysql_result($userphotosquery, $iii, "points");
        $votes = mysql_result($userphotosquery, $iii, "votes");
        $totalfaves = mysql_result($userphotosquery, $iii, "faves");
        $portfoliopoints+=$points;
        $portfoliovotes+=$votes;
        $portfoliofaves+=$totalfaves;
        }
    
    if ($portfoliovotes > 0) {
    $portfolioranking=($portfoliopoints/$portfoliovotes);
    $portfolioranking=number_format($portfolioranking, 2, '.', '');
    
    $scorequery = "UPDATE userinfo SET totalscore = '$portfoliopoints' WHERE emailaddress = '$email'";    
    $scoreresult = mysql_query($scorequery);
    
    }
    
    else if ($portfoliovotes < 1) {
    $portfolioranking="N/A";
    }	
    
    //Number Following
    $emailquery=("SELECT following FROM userinfo WHERE emailaddress ='$email'");
	$followresult=mysql_query($emailquery);
	$followinglist=mysql_result($followresult, 0, "following");
	$followingquery="SELECT * FROM userinfo WHERE emailaddress IN ($followinglist)";
	$followingresult = mysql_query($followingquery);
	$numberfollowing = mysql_num_rows($followingresult);


    //Get Views & URI
    $view = htmlentities($_GET['view']);
    $action = htmlentities($_GET['action']);
    $option = htmlentities($_GET['option']);  
    $uri = $_SERVER['REQUEST_URI'];

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
    <link rel="stylesheet" type="text/css" href="css/vpstyle.css"/>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="css/960grid.css"/>
	<link rel="stylesheet" type="text/css" href="css/reset.css"/>    
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.wookmark.js"></script>            
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
  
  
  <title>PhotoRankr - View Profile</title>
  
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
  
//Drop down for portfolio pages
jQuery(document).ready(function(){
    jQuery("#showViews").live("click", function(event) {        
         jQuery("#portfolioViews").toggle();
    });
    jQuery("#hideViews").live("click", function(event) {        
         jQuery("#portfolioViews").hide();
    });
})
    
</script>

</head>

<body style="overflow-x:hidden; background-color: #fff;">

<?php navbar(); ?>
    
    <!------------------------WHITE TOP HALF------------------------>
    <div class="tophalf">
        <div class="container_24" style="width:1120px;position:relative;left:30px;">
            
            <!------------------------PROFILE PICTURE------------------------>    
            <div class="profileBox">
                <div id="profilePicture">
                    <img src="https://photorankr.com/<?php echo $profilepic ?>" />
                </div>
                <div id="nameLabel">
                    <header><span style="font-weight:500;font-size:18px;"><?php echo $reputation; ?></span> <?php echo $fullname ?></header>
                </div>
                <div id="followBlock">
                    <a class="buttonNew" style="text-decoration:none;color:#000;width:100px;"><img style="width:15px;margin:-5px 4px 0px 2px;" src="graphics/tick 2.png" />Follow</a>
                    <a class="buttonNew" style="color:#000;width:100px;text-decoration:none;"><img style="width:15px;margin:-5px 4px 0px 2px;" src="graphics/comment_1.png" />Message</a>
                </div>
            </div>
            
        <div class="profileRightSide">
            <!------------------------STATS BOXES------------------------>   
            <div class="topHalfBlock">
                <div id="bioText">
                    <header>About Me</header>
                    <?php echo $bio; ?>
                </div>
            </div>
            
            <div class="topHalfBlock">
                <div id="bioText" style="padding-left:12px;">
                    <header> Reputation </header>
                    <ul>
                        <li><span id="statTxt"><img style="width:12px;" src="graphics/rep_i.png" /> Rep: <?php echo $reputation; ?></span></li>
                        <li><span id="statTxt"><img style="width:12px;" src="graphics/camera.png"> Photos: <?php echo $numphotos; ?></span></li>
                        <li><span id="statTxt"> <img style="width:12px;" src="graphics/rank_prof.png"> Avg Score: <?php echo $portfolioranking; ?></span></li>
                        <li><span id="statTxt"> <img style="width:14px;" src="graphics/eye.png"> Views: <?php echo $profileviews; ?></span></li>
                        <li><span id="statTxt"> <img style="width:20px;margin-left:-8px;" src="graphics/network_i.png"> Followers: <?php echo $numberfollowers; ?></span></li>
                    </ul>
                </div>
            </div>
            
            <div class="topHalfBlock">
                <div id="bioText" style="padding:5px 0px 5px 0px;">
                    <header>My Network</header>
                    <?php
                    
                        $followersquery="SELECT * FROM userinfo WHERE following LIKE '%$email%' ORDER BY reputation DESC";
                        $followingquery=mysql_query($followersquery);
                        $numberfollowing = mysql_num_rows($followingquery);
                        
                        for($iii = 0; $iii < $numberfollowing; $iii++) {
                            $followingpic = mysql_result($followingquery, $iii, "profilepic");
                            $followingid = mysql_result($followingquery, $iii, "user_id");
		
                            echo '   
                            <div style="width:52px;height:52px;overflow:hidden;float:left;"><a style="text-decoration:none;" href="http://photorankr.com/viewprofile.php?u=',$followingid,'">
                            <img onmousedown="return false" oncontextmenu="return false;" style="min-height:52px;min-width:52px;padding:1px;" src="http://www.photorankr.com/',$followingpic,'" width="50" /></a></div>';
        
                        }
                      
                    ?>
                </div>
            </div>
            
            <div class="topHalfBlock" style="width:197px;">
                <div id="bioText" style="padding:5px 0px 5px 0px;">
                    <header>My Store</header>
                    <?php
                        $storephotos = mysql_query("SELECT source,id,caption,price FROM photos WHERE emailaddress = '$email' AND price != '.00' ORDER BY points DESC LIMIT 0,6");
                        $numphotos = mysql_num_rows($storephotos);
        
                        if($numphotos == 6) {
                            for($ii=0;$ii<=5;$ii++) {
                                $source = mysql_result($storephotos,$ii,'source');
                                $source = str_replace('userphotos','userphotos/medthumbs/',$source);
                                $price = mysql_result($storephotos,$ii,'price');
                                $price = number_format($price,0);
                                $caption = mysql_result($storephotos,$ii,'caption');
                                $caption = (strlen($caption) > 18) ? substr($caption,0,15). " &#8230;" : $caption;      

                                $imageid = mysql_result($storephotos,$ii,'id');

                                echo'<li style="list-style-type:none;">
                                     <div class="storeContainer">
                                     <div class="storeContainerOverlay">
                                     <header> $',$price,' </header>
                                     <header> ',$caption,' </header>
                                     </div>
                                     <img src="https://photorankr.com/',$source,'"/>
                                     </div>	
                                     </li>';
                            }
                        }
                    ?>
                </div>
            </div>
            
            <div class="topHalfBlock" style="width:198px;">
                <div id="bioText">
                    <header>My Groups</header>
                    Put groups here!
                </div>
            </div>
            
            
            <!------------------------TOP 6 PHOTOS-  
        <?php 
            $topphotos = mysql_query("SELECT source,id FROM photos WHERE emailaddress = '$email' ORDER BY faves DESC LIMIT 0,4");
            $numphotos = mysql_num_rows($topphotos);
        
            echo'<div id="topPhotosBlock">';
            if($numphotos == 4) {
                echo'<div id="profileBigText">Top Photos</div>';
                for($ii=0;$ii<=3;$ii++) {
                    $source = mysql_result($topphotos,$ii,'source');
                    $source = str_replace('userphotos','userphotos/medthumbs/',$source);
                    $imageid = mysql_result($topphotos,$ii,'imageid');

                    echo'<div style="float:left;width:115px;height:88px;overflow:hidden;margin-top:1px;margin-right:1px;">
                            <img src="https://photorankr.com/',$source,'" style="width:115px;height:110px;" />
                        </div>';
                
                }
            }
            echo'</div>';
        ?>----------------------->  
        
        </div><!---end of right side profile-->
        
        <!---------------------NAV ELEMENTS----------------->
         <div class="profileBottomNav">
            <ul>
                <a href="profile.php"><li id="hideViews"><?php if($view == '') {echo'<div class="oval"><img style="width:15px;padding-bottom:5px;" src="graphics/home.png" /> Home</div>';} else {echo'<img style="width:15px;padding-bottom:5px;" src="graphics/home.png" /> Home';} ?></li></a>
              <li id="showViews"><?php if($view == 'portfolio') {echo'<div class="oval"><img style="width:15px;padding-bottom:5px;" src="graphics/grid.png" /> Portfolio</div>';} else {echo'<img style="width:15px;padding-bottom:5px;" src="graphics/grid.png" /> Portfolio';} ?></li>
                <a href="profile.php?view=store"><li id="hideViews"><?php if($view == 'store') {echo'<div class="oval"><img style="width:15px;padding-bottom:5px;" src="graphics/tag.png" /> Store</div>';} else {echo'<img style="width:15px;padding-bottom:5px;" src="graphics/tag.png" /> Store';} ?></li></a>
                <a href="profile.php?view=faves"><li id="hideViews"><?php if($view == 'faves') {echo'<div class="oval"><img style="width:16px;padding-bottom:5px;" src="graphics/heart.png" /> Favorites</div>';} else {echo'<img style="width:16px;padding-bottom:5px;" src="graphics/heart.png" /> Favorites ';} ?></li></a>
               <a href="profile.php?view=network"> <li id="hideViews"><?php if($view == 'network') {echo'<div class="oval"><img style="width:15px;padding-bottom:5px;" src="graphics/user.png" /> Network</div>';} else {echo'<img style="width:15px;padding-bottom:5px;" src="graphics/user.png" /> Network';} ?></li></a>
                <a href="profile.php?view=about"><li id="hideViews"><?php if($view == 'about') {echo'<div class="oval"><img style="width:7px;padding-bottom:5px;" src="graphics/info.png" /> About</div>';} else {echo'<img style="width:7px;padding-bottom:5px;" src="graphics/info.png" /> About';} ?></li></a>
               <a href="profile.php?view=blog"><li id="hideViews"><?php if($view == 'blog') {echo'<div class="oval"><img style="width:15px;padding-bottom:5px;" src="graphics/list 1.png" /> Blog</div>';} else {echo'<img style="width:15px;padding-bottom:5px;" src="graphics/list 1.png" /> Blog';} ?></li></a>
            </ul>
         </div>

       </div> 
    </div>
    
    <!-----------------------PORTFOLIO BOTTOM HALF------------------------>
    <div class="container_24" style="width:1120px;position:relative;left:30px;">
        <!--determine where arrow should be placed based on the view--->
        <div class="upArrow" <?php if($view == '') {echo'style="left:272px;"';} 
                                   elseif($view == 'portfolio') {echo'style="left:485px;"';} 
                                   elseif($view == 'store') {echo'style="left:415px;"';} 
                                   elseif($view == 'network') {echo'style="left:485px;"';} 
                                   elseif($view == 'about') {echo'style="left:560px;"';} 
                                   elseif($view == 'blog') {echo'style="left:620px;"';} 
        ?>></div>
        
        <!-------Hidden box for portfolio views--------->
        <div id="portfolioViews">test</div>
        
        <!--------------------------Portfolio View---------------------------->
        <?php
        if($view == '') {   
        
        echo'
            <!--BEGIN CONTAINER-->
            <div style="margin:10px auto 0 auto;width:1100px;padding-left:30px;">

<!--LEFT COL-->
	<div id="leftCol">

		<!--top photos-->
		<div id="topPhotos">
			<header> ',$firstname,'s Top Photos </header>

			<!--Top Pics go here-->';
                $topphotos = mysql_query("SELECT source,id,caption FROM photos WHERE emailaddress = '$email' ORDER BY faves DESC LIMIT 0,6");
                $numphotos = mysql_num_rows($topphotos);
                echo'<div id="topPhotoContainer">';

                if($numphotos == 6) {
                    
                    for($ii=0;$ii<=5;$ii++) {
                        $source = mysql_result($topphotos,$ii,'source');
                        $source = str_replace('userphotos','userphotos/medthumbs/',$source);
                        $caption = mysql_result($topphotos,$ii,'caption');
                        $caption = (strlen($caption) > 18) ? substr($caption,0,15). " &#8230;" : $caption;

                        $imageid = mysql_result($topphotos,$ii,'id');

                        echo'<div class="topPhoto">
                             <header> ',$caption,' </header>
                             <a href="fullsizeview.php?imageid=',$imageid,'"><img style="min-height:165px;" src="https://photorankr.com/',$source,'" /></a>
                             <div class="statOverlay"></div>
                             </div>';
                        }
                    }
                echo'
			</div>
		</div>
        
        <!--portfolio-->
		<div id="portfolio">
			<header> ',$firstname,'s Portfolio </header>
			
			<nav>
				<ul>
					<a href=""><li> Favorites </li></a>
					<a href=""><li> Collections </li></a>
					<a href=""><li> Exhibits </li></a>
					<a  href=""><li style="border-right:none;"> Portfolio </li></a>
					<form>
						<input style="width:7em;margin-top:3px;" type="text" placeholder="search"/ >
					</form>
				</ul>
			</nav>';

                //portfolio photo queries
                $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$email' ORDER BY id DESC LIMIT 0,21");
                $numresults = mysql_num_rows($query);
                
            echo'
                <div id="main">
                <ul id="tiles">';

                for($iii=0; $iii < $numresults && $iii < 6; $iii++) {
                    $image = mysql_result($query, $iii, "source");
                    $image= '../' . $image;
                    $imageThumb = str_replace("../userphotos/","userphotos/medthumbs/", $image);
                    $id = mysql_result($query, $iii, "id");
                    $caption = mysql_result($query, $iii, "caption");
                    $points = mysql_result($query, $iii, "points");
                    $votes = mysql_result($query, $iii, "votes");
                    $faves = mysql_result($query, $iii, "faves");
                    $score = number_format(($points/$votes),2);
                    $faveemail = mysql_result($query, $iii, "emailaddress");
                    $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$faveemail'");
                    $fullname = $firstname . " " . $lastname;
                    
                    list($width, $height) = getimagesize($image);
                    $imgratio = $height / $width;
                    $heightls = $height / 3.7;
                    $widthls = $width / 3.7;
                    if($widthls < 150) {
                        $heightls = $heightls * ($heightls/$widthls);
                        $widthls = 200;
                    }

                   echo'<div class="topPhoto">
                             <header> ',$caption,' </header>
                             <a href="fullsizeview.php?imageid=',$imageid,'"><img style="min-width:200px;" src="https://photorankr.com/',$imageThumb,'" /></a>
                             <div class="statOverlay" ></div>
                        </div>';       	
            
      } //end for loop
        
    echo'
        </ul>
        
    </div>
    
        </div>
        </div>

    <!--END LEFTCOL-->
    
    <!--RIGHT COL-->
	<div id="rightCol">

		<!--displays groups-->
		<div id="groupsDisplay">
			<!--top photos-->
			<header>', $firstname,'s Groups <div> ',$numgroups,' </div> </header>

			<!--Top Pics go here-->
			<div id="groupContainer">

				<div class="Group">
						
					<div>
						<img src="img/img-8.jpg">
					</div>
					<header><img src="graphics/groups_b.png"/> Group Name </header>
					<img class="holder-TR" src="graphics/holder_bl_i.png">
					<img class="holder-BL" src="graphics/holder_tr_i.png">
				</div>
				
				<div class="Group">
						
					<div>
						<img src="img/img-8.jpg">
					</div>
					<header><img src="graphics/groups_b.png"/> Group Name </header>
					<img class="holder-TR" src="graphics/holder_bl_i.png">
					<img class="holder-BL" src="graphics/holder_tr_i.png">
				</div>

				<div class="Group">
						
					<div>
						<img src="img/img-8.jpg">
					</div>
					<header><img src="graphics/groups_b.png"/> Group Name </header>
					<img class="holder-TR" src="graphics/holder_bl_i.png">
					<img class="holder-BL" src="graphics/holder_tr_i.png">
				</div>

				<div class="Group">
						
					<div>
						<img src="img/img-8.jpg">
					</div>
					<header><img src="graphics/groups_b.png"/> Group Name </header>
					<img class="holder-TR" src="graphics/holder_bl_i.png">
					<img class="holder-BL" src="graphics/holder_tr_i.png">
				</div>

				<div class="Group">
						
					<div>
						<img src="img/img-8.jpg">
					</div>
					<header><img src="graphics/groups_b.png"/> Group Name </header>
					<img class="holder-TR" src="graphics/holder_bl_i.png">
					<img class="holder-BL" src="graphics/holder_tr_i.png">
				</div>

				<div class="Group">
						
					<div>
						<img src="img/img-8.jpg">
					</div>
					<header><img src="graphics/groups_b.png"/> Group Name </header>
					<img class="holder-TR" src="graphics/holder_bl_i.png">
					<img class="holder-BL" src="graphics/holder_tr_i.png">
				</div>

			</div>
		</div>


		<!--Profile store-->
		<div id="profileStore">
			<header> ',$firstname,'s Store </header>';

                $storephotos = mysql_query("SELECT source,id,caption,price FROM photos WHERE emailaddress = '$useremail' AND price != '.00' ORDER BY points DESC LIMIT 0,6");
                $numphotos = mysql_num_rows($storephotos);
                echo'<div id="topPhotoContainer">';

                if($numphotos == 6) {
                    
                    for($ii=0;$ii<=5;$ii++) {
                        $source = mysql_result($storephotos,$ii,'source');
                        $source = str_replace('userphotos','userphotos/medthumbs/',$source);
                        $price = mysql_result($storephotos,$ii,'price');
                        $price = number_format($price,0);
                        $caption = mysql_result($storephotos,$ii,'caption');
                        $caption = (strlen($caption) > 18) ? substr($caption,0,15). " &#8230;" : $caption;

                        $imageid = mysql_result($storephotos,$ii,'id');

                        echo'<li>
			<div class="storeContainer">
				<div class="storeContainerOverlay">
					<header> $',$price,' </header>
					<header> ',$caption,' </header>
				</div>
				<img src="https://photorankr.com/',$source,'"/>

			</div>	
		</li>';
                        }
                    }
            echo'
		</div>

	</div>
<!--END RIGHT COL-->

</div>
<!--END CONTAINER-->';
 
    } //end of home portal view
    ?>
        
        <!--------------------------Portfolio View---------------------------->
        <?php
        if($view == 'portfolio') {   
              
        if($option == '') {        
        $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$email' ORDER BY id DESC LIMIT 0,21");
        $numresults = mysql_num_rows($query);
        }
        
        elseif($option == 'top') {
        $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$email' AND views > 20 ORDER BY (points/votes) DESC LIMIT 0,21");
        $numresults = mysql_num_rows($query);
        }
                
        elseif($option == 'fave') {
        $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$email' ORDER BY faves DESC LIMIT 0,21");
        $numresults = mysql_num_rows($query);
        }
        
    echo'
    <div id="thepics" style="position:relative;left:-60px;top:10px;width:1250px;">
    <div id="main">
    <ul id="tiles">';

        for($iii=0; $iii < $numresults; $iii++) {
              
                $image = mysql_result($query, $iii, "source");
                $image= '../' . $image;
                $imageThumb = str_replace("../userphotos/","userphotos/medthumbs/", $image);
                $id = mysql_result($query, $iii, "id");
                $price = mysql_result($query, $iii, "price");
                if($price != 'Not For Sale') {
                    $price = '$' . $price;
                }
                elseif($price == 'Not For Sale') {
                    $price = 'NFS';
                }
                elseif($price == '.00' || $price == '') {
                    $price = 'Free';
                }
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
                $heightls = $height / 3.2;
                $widthls = $width / 3.2;
                
                list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
    $heightls = $height / 3.3;
    $widthls = $width / 3.3;
    if($widthls < 235) {
    $heightls = $heightls * ($heightls/$widthls);
    $widthls = 280;
    }

		echo '
        <a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$id,'&v=n"><li class="fPic" id="',$id,'" style="list-style-type: none;width:280px;"><img style="min-width:280px;" src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />
        
            <div class="statoverlay" style="z-index:1;background-color:rgba(0,0,0,.8);position:relative;top:0px;width:280px;height:30px;"><div style="line-spacing:1.48;padding:5px;color:white;"><div style="float:left;"<span style="font-size:18px;font-weight:100;">',$score,'</span>&nbsp;&nbsp;<span style="font-weight:100;font-size:16px;">',$caption,'</span></div><div style="float:right;"><span style="font-size:13px;">',$price,'</span></div></div><br/></div>';       	
            
      } //end for loop
        
    echo'
        </ul>';
        
?>

<!-- Once the page is loaded, initalize the plug-in. -->
  <script type="text/javascript">
    $(document).ready(new function() {
      // Prepare layout options.
      var options = {
        autoResize: true, // This will auto-update the layout when the browser window is resized.
        container: $('#main'), // Optional, used for some extra CSS styling
        offset: 10, // Optional, the distance between grid items
        itemWidth: 280 // Optional, the width of a grid item
      };
      
      // Get a reference to your grid items.
      var handler = $('#tiles li');
      
      // Call the layout function.
      handler.wookmark(options);
      
    });
  </script>

</div>
</div>
    
<?php      
        
        //AJAX CODE HERE
echo'
   <div class="grid_6 push_9" style="top:150px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;"><img src="graphics/load.gif" /></div>
   </div>';


echo '<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMorePortfolioPics").show();
				$.ajax({
					url: "loadMorePortfolioPics3.php?lastPicture=" + $(".fPic:last").attr("id"),
					success: function(html) {
						if(html) {
							$("#thepics").append(html);
							$("div#loadMorePortfolioPics").hide();
						}
					}
				});
				last = $(".fPic:last").attr("id");
			}
		}
	});
</script>';
    
        echo'</div>';
        echo'</div>';
    } //end portfolio view
    
        ?>
        
        
         <!--------------------------Portfolio View---------------------------->
        <?php
        if($view == 'store') {  
        
        echo'<div style="width:1180px;overflow:hidden;position:relative;left:-40px;top:8px;">';
              
        if($option == '') {
            $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$email' ORDER BY id DESC");
        }
        elseif($option == 'faved') {
            $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$email' ORDER BY faves DESC");
        }
        elseif($option == 'top') {
            $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$email' ORDER BY (points/votes) DESC");
        }
        elseif($option == 'sold') {
            $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$email' AND sold = 1 ORDER BY (points/votes) DESC");
        }
            $numresults = mysql_num_rows($query);
        
    echo'
    <div id="thepics" style="float:left;top:10px;width:910px;">
    <div id="main">
    <ul id="tiles">';

        for($iii=0; $iii < $numresults; $iii++) {
              
                $image = mysql_result($query, $iii, "source");
                $image= '../' . $image;
                $imageThumb = str_replace("../userphotos/","userphotos/medthumbs/", $image);
                $id = mysql_result($query, $iii, "id");
                $price = mysql_result($query, $iii, "price");
                if($price != 'Not For Sale') {
                    $price = '$' . $price;
                }
                elseif($price == 'Not For Sale') {
                    $price = 'NFS';
                }
                elseif($price == '.00' || $price == '') {
                    $price = 'Free';
                }
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
                $heightls = $height / 3.2;
                $widthls = $width / 3.2;
                
                list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
    $heightls = $height / 3.3;
    $widthls = $width / 3.3;
    if($widthls < 235) {
    $heightls = $heightls * ($heightls/$widthls);
    $widthls = 280;
    }

		echo '
        <a style="text-decoration:none;color:#333;" href="fullsizemarket.php?imageid=',$id,'&v=n"><li class="fPic" id="',$id,'" style="list-style-type: none;width:280px;"><img style="min-width:280px;" src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />
        
            <div class="statoverlay" style="z-index:1;background-color:rgba(0,0,0,.8);position:relative;top:0px;width:280px;height:30px;"><div style="line-spacing:1.48;padding:5px;color:white;"><div style="float:left;"<span style="font-size:18px;font-weight:100;">',$score,'</span>&nbsp;&nbsp;<span style="font-weight:100;font-size:16px;">',$caption,'</span></div><div style="float:right;"><span style="font-size:13px;">',$price,'</span></div></div><br/></div>';       	
            
      } //end for loop
        
    echo'
        </ul>';
        
?>

<!-- Once the page is loaded, initalize the plug-in. -->
  <script type="text/javascript">
    $(document).ready(new function() {
      // Prepare layout options.
      var options = {
        autoResize: true, // This will auto-update the layout when the browser window is resized.
        container: $('#main'), // Optional, used for some extra CSS styling
        offset: 10, // Optional, the distance between grid items
        itemWidth: 280 // Optional, the width of a grid item
      };
      
      // Get a reference to your grid items.
      var handler = $('#tiles li');
      
      // Call the layout function.
      handler.wookmark(options);
      
    });
  </script>

</div>
</div>
    
<?php      
        
        //AJAX CODE HERE
echo'
   <div class="grid_6 push_9" style="top:150px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;"><img src="graphics/load.gif" /></div>
   </div>';


echo '<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMorePortfolioPics").show();
				$.ajax({
					url: "loadMorePortfolioPics3.php?lastPicture=" + $(".fPic:last").attr("id"),
					success: function(html) {
						if(html) {
							$("#thepics").append(html);
							$("div#loadMorePortfolioPics").hide();
						}
					}
				});
				last = $(".fPic:last").attr("id");
			}
		}
	});
</script>';
    
        
    //Right Sidebar of Stats

echo'<div class="grid_6 filled rounded shadow" style="float:left;width:240px;">';
        
        echo'<div class="cartText" style="padding:10px;">Your Store</div>';

         //Search the Store
        echo'<div class="grid_6" style="">
            <form method="GET">
                <input id="searchStore" name="searchword" placeholder="Search your store&hellip;" type="text" />
            </form>
        </div>';
        
        echo'
            <ul>
                <li id="stattitle"># Photos: <span id="stat">',$numphotos,'</span></li>
                <li id="stattitle">Avg. Photo Price: <span id="stat">$',$avgprice,'</span></li>
                <li id="stattitle">Avg. Portfolio Score: <span id="stat">',$portfolioranking,'</span></li>
                <li id="stattitle">Photos Sold: <span id="stat">',$portfoliosold,'</span></li>
                <li id="stattitle">Photo Views: <span id="stat">',$portfolioviews,'</span></li>

                <li id="stattitle">Avg. Resolution: <span id="stat">',$avgwidth,' X ',$avgheight,'</span></li>
        </div>';
        
    //Store Filters
     echo'<div class="grid_6" style="float:left;width:240px;">';
        if($option== '') {
            echo'<a style="text-decoration:none;" href="profile.php?view=store"><div class="rectangle"><div style="padding-top:7px;text-align:center;">Newest</div><div class="arrow-leftStore" style="float:right;margin-right:288px;margin-top:-21px;"></div></div></a>';
        }
        
        else {
             echo'<a style="text-decoration:none;" href="profile.php?view=store"><div class="rectangle"><div style="padding-top:7px;text-align:center;">Newest</div></div></a>';
        }
        
        if($option == 'faved') {
            echo'<a style="text-decoration:none;" href="profile.php?view=store&option=faved"><div class="rectangle"><div style="padding-top:7px;text-align:center;">Most Favorited</div><div class="arrow-leftStore" style="float:right;margin-right:288px;margin-top:-21px;"></div></div></a>';
        }
        
        else {
             echo'<a style="text-decoration:none;" href="profile.php?view=store&option=faved"><div class="rectangle"><div style="padding-top:7px;text-align:center;">Most Favorited</div></div></a>';
        }
        
        if($option == 'top') {
            echo'<a style="text-decoration:none;" href="profile.php?view=store&option=top"><div class="rectangle"><div style="padding-top:7px;text-align:center;">Top Ranked</div><div class="arrow-leftStore" style="float:right;margin-right:288px;margin-top:-21px;"></div></div></a>';
        }
        
        else {
             echo'<a style="text-decoration:none;" href="profile.php?view=store&option=top"><div class="rectangle"><div style="padding-top:7px;text-align:center;">Top Ranked</div></div></a>';
        }
        
        if($option == 'sold') {
            echo'<a style="text-decoration:none;" href="profile.php?view=store&option=sold"><div class="rectangle"><div style="padding-top:7px;text-align:center;">Sold</div><div class="arrow-leftStore" style="float:right;margin-right:288px;margin-top:-21px;"></div></div></a>';
        }
        
        else {
             echo'<a style="text-decoration:none;" href="profile.php?view=store&option=sold"><div class="rectangle"><div style="padding-top:7px;text-align:center;">Sold</div></div></a>';
        }
        
    echo'</div>';


            echo'</div>';

        } //end of view == 'store'
        
        echo'</div>';
        
    ?>
        
    </div><!---end of bottom half container---->
    
</body>
</html>
