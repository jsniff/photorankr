<?php

//connect to the database
require "db_connection.php";
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
    $currenttime = time();
    $lowerbound = $currenttime - 86400;
    $lowerboundforfaves = $currenttime - 186400;

?>

<!DOCTYPE HTML>
<head>
	<meta charset = "UTF-8">
    <meta name="Generator" content="EditPlus">
    <meta name="Author" content="PhotoRankr, PhotoRankr.com">
    <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
    <meta name="Description" content="Featured photography from the PhotoRankr galleries.">
    <meta name="viewport" content="width=1200" />
    
	<title> PhotoRankr Galleries </title>
    
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="css/960grid.css"/>
	<link rel="stylesheet" type="text/css" href="css/reset.css"/>   
    <link rel="stylesheet" type="text/css" href="css/main3.css"/>
	<link rel="stylesheet" media='screen and (max-width:640px)' href="css/640.css"/>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="js/bootstrap.js"></script>
	<script src="js/modernizer.js"></script>

	<style type="text/css">
		.show
		{
			display:block !important;
		}
		
		#notify
		{
			width:40px;
			margin: 0 0 0 5px;
			background:#d96f62;
			padding: 5px;
		}
		#notify:hover
		{
			background: rgba(255,255,255,.55);
		}
		#drawer
		{
			width:0px;
			background: url('graphics/noise.png');
			color:#fff;
			white-space: normal;
			font-size: 10px;
			position:fixed;
			height:100%;
			box-shadow: inset 0 0 5px rgba(0,0,0,.25);
			border-radius:0 5px 5px 0;
			margin: 5px 0 0 -5px;
			z-index: 1000;
		}
		.notifications
		{
			font-family:"helvetica neue", helvetica, arial,sans-serif; 
			font-size:20px;
			font-weight: 500;
			color:#fff;
			margin-left: -200px;
			width:200px;

		}
		.test
		{
			height:250px;
			background: rgba(200,200,200,.6);
			box-shadow: 0 0 2px #666;
			margin: 4px 20px 0 0;
		}
		.test2
		{
			height:50px;
			background: rgba(200,200,200,.6);
			box-shadow: 0 0 2px #666;
			margin: 7px 4px !important;
			width:125px;
			float: right;
		}
		.x
		{
			background:none !important;
			color:#222 !important;
			padding: 0 !important;
			box-shadow: 0 0 0 !important;
			margin:10px 5px 0 5px !important;
			border: none !important;
			font-size: 14px;
		}
         .statoverlay

{
background-attachment: scroll;
background-clip: border-box;
background-color: 
rgba(0, 0, 0, 0.848438);
background-image: none;
background-origin: padding-box;
color: rgb(255, 255, 255);
bottom: 0px;
display: block;
font-family: 'Helvetica Neue', 'Helvetica Neue', Helvetica, Arial, sans-serif;
font-size: 14px;
font-style: normal;
font-variant: normal;
font-weight: normal;
line-height: 0px;
margin-bottom: 0px;
margin-left: 0px;
margin-right: 0px;
margin-top: 0px;
overflow-x: hidden;
overflow-y: hidden;
padding-bottom: 0px;
padding-left: 0px;
padding-right: 0px;
padding-top: 0px;
white-space: nowrap;
width: 270px;
-moz-box-shadow: 1px 1px 5px #888;
-webkit-box-shadow: 1px 1px 5px #888;
box-shadow: 1px 1px 5px #888;
}
	</style>
    
<script type="text/javascript">
jQuery(document).ready(function(){
     jQuery("#featuredPhotos").live("click", function(event) {
         jQuery("#featuredPhotosDiv").show();
         jQuery("#featuredMarketPhotosDiv").hide();
         jQuery("#featuredPhotogsDiv").hide();
         jQuery("#featuredExhibitsDiv").hide();
    });
    jQuery("#featuredMarketPhotos").live("click", function(event) {        
         jQuery("#featuredMarketPhotosDiv").show();
         jQuery("#featuredPhotogsDiv").hide();
         jQuery("#featuredPhotosDiv").hide();
         jQuery("#featuredExhibitsDiv").hide();
    });
    jQuery("#featuredPhotogs").live("click", function(event) {        
         jQuery("#featuredPhotogsDiv").show();
         jQuery("#featuredMarketPhotosDiv").hide();
         jQuery("#featuredPhotosDiv").hide();
         jQuery("#featuredExhibitsDiv").hide();
    });
    jQuery("#featuredExhibits").live("click", function(event) {  
         jQuery("#featuredExhibitsDiv").show();
         jQuery("#featuredPhotogsDiv").hide();
         jQuery("#featuredMarketPhotosDiv").hide();
         jQuery("#featuredPhotosDiv").hide();
    });
    jQuery("#trendingPhotos").live("click", function(event) {        
         jQuery("#trendingPhotosDiv").show();
         jQuery("#trendingPhotogsDiv").hide();
         jQuery("#trendingExhibitsDiv").hide();
    });
    jQuery("#trendingPhotogs").live("click", function(event) {        
         jQuery("#trendingPhotogsDiv").show();
         jQuery("#trendingPhotosDiv").hide();
         jQuery("#trendingExhibitsDiv").hide();
    });
    jQuery("#trendingExhibits").live("click", function(event) {   
         jQuery("#trendingExhibitsDiv").show();
         jQuery("#trendingPhotogsDiv").hide();
         jQuery("#trendingPhotosDiv").hide();
    });
    jQuery("#freshPhotos").live("click", function(event) {        
         jQuery("#freshPhotosDiv").show();
         jQuery("#freshPhotogsDiv").hide();
         jQuery("#freshExhibitsDiv").hide();
    });
    jQuery("#freshPhotogs").live("click", function(event) {        
         jQuery("#freshPhotosDiv").hide();
         jQuery("#freshPhotogsDiv").show();
         jQuery("#freshExhibitsDiv").hide();
    });
    jQuery("#freshExhibits").live("click", function(event) {        
         jQuery("#freshPhotosDiv").hide();
         jQuery("#freshPhotogsDiv").hide();
         jQuery("#freshExhibitsDiv").show();
    });
    
    jQuery("#topRankedPhotos").live("click", function(event) {        
         jQuery("#topRankedPhotosDiv").show();
         jQuery("#topRankedPhotogsDiv").hide();
         jQuery("#topRankedExhibitsDiv").hide();
    });
    jQuery("#topRankedPhotogs").live("click", function(event) {        
         jQuery("#topRankedPhotogsDiv").show();
         jQuery("#topRankedPhotosDiv").hide();
         jQuery("#topRankedExhibitsDiv").hide();
    });
    jQuery("#topRankedExhibits").live("click", function(event) {   
         jQuery("#topRankedExhibitsDiv").show();
         jQuery("#topRankedPhotogsDiv").hide();
         jQuery("#topRankedPhotosDiv").hide();
    });
});
</script>

</head>
<body style="overflow-x:hidden; background-image:url('graphics/paper.png');">
<?php include_once("analyticstracking.php") ?>

<?php navbar(); ?>

<?php 
    if(!$email) {
        echo'
        <div id="registerBar">
            <img style="width:16px;padding:4px;margin-top:-3px;" src="graphics/tick 2.png" />
            <a href="register.php">Register for free today to begin sharing and selling.
            </a> 
        </div>
        <div style="clear:both;padding-bottom:20px;"></div>';
    }
?>

	<!--Galleries Cover Content-->
	<div class="container_24" style="margin-top:40px;min-width:1200px;max-width: 99%;position: relative;overflow-x: hidden;overflow-y: hidden;">
    
    <!---Featured Div---->
    <div class="galleryBlock" style="margin-top:33px;margin-left:68px;">
    
        <div class="galleryToolbar">
            <ul>
                <li style="width:272px;background-color:rgba(0,0,0,.1);-webkit-border-radius: 4px;-moz-border-radius: 2px;border-radius: 2px;padding-left:8px;margin-left:0px;text-align:left;"><img style="float:left;width:20px;height:20px;" src="graphics/star.png" />&nbsp;&nbsp;Featured</li>
                <li id="featuredPhotos" style="width:134px;cursor:pointer;"><img src="graphics/camera2.png" /> Photos</li>
                <li id="featuredMarketPhotos" style="width:134px;"><img src="graphics/tag.png" /> Marketplace</li>
                <li id="featuredPhotogs" style="width:134px;"><img src="graphics/user.png" /> Photographers</li>
                <li id="featuredExhibits" style="width:134px;"><img src="graphics/grid.png" /> Exhibits</li>
            </ul>
        </div>
    
    <div id="featuredPhotosDiv">
        <div class="galleryGut">
        <?php
            $featuredquery = mysql_query("SELECT * FROM photos WHERE time > ($currenttime - 450000) ORDER BY faves DESC LIMIT 0,15");
            $bigcaption = mysql_result($featuredquery,0,'caption');
            $bigcaption = (strlen($bigcaption) > 23) ? substr($bigcaption,0,20). " &#8230;" : $bigcaption;
            $bigranking = number_format((mysql_result($featuredquery,0,'points')/mysql_result($featuredquery,0,'votes')),2);
            $bigphoto = mysql_result($featuredquery,0,'source');
            $bigphotoid = mysql_result($featuredquery,0,'id');
            $bigphoto = str_replace("userphotos/","userphotos/medthumbs/",$bigphoto);
            echo'<div class="bigphoto"><a href="fullsize.php?imageid=',$bigphotoid,'"><img src="https://photorankr.com/',$bigphoto,'" /></a></div>';
            for($iii=1; $iii < 13; $iii++) {
                $smallphoto = mysql_result($featuredquery,$iii,'source');
                $smallcaption = mysql_result($featuredquery,$iii,'caption');
                $smallranking = number_format((mysql_result($featuredquery,$iii,'points')/mysql_result($featuredquery,$iii,'votes')),2);
                $imageid = mysql_result($featuredquery,$iii,'id');
                $smallphoto = str_replace("userphotos/","userphotos/medthumbs/",$smallphoto);
                echo'<div class="galleryContainer">
                        <a style="text-decoration:none;" href="fullsize.php?imageid=',$imageid,'">
                        <div class="galleryContainerOverlay">
                            <header style="font-weight:300;font-size:13px;position:relative;top:20px;left:15px;width:100px;">',$smallcaption,'<br /><br />',$smallranking,'</header>
                        </div>
                            <img class="smallphoto" src="https://photorankr.com/',$smallphoto,'" />
                        </a>
                    </div>';
            }
        
        ?>
        </div>
       <div class="statoverlay" style="z-index:1;background-color:white;position:relative;left:0px;top:-65px;width:280px;height:30px;">
                <div style="line-spacing:1.48;padding:5px;color:#4A4A4A;">
                    <div style="float:left;padding-top:10px;">
                        <span style="font-size:15px;font-weight:500;"><?php echo $bigranking; ?></span>&nbsp;&nbsp;<span style="font-weight:300;font-size:15px;"><?php echo $bigcaption; ?></span>
                    </div>
                </div>
            </div>
    </div>

    <div id="featuredMarketPhotosDiv">
        <div class="galleryGut">
        <?php
            $featuredquery = mysql_query("SELECT * FROM photos WHERE time > ($currenttime - 450000) AND price > '1.00' AND market_worthy = 0 ORDER BY faves DESC LIMIT 0,15");
            $bigcaption = mysql_result($featuredquery,0,'caption');
            $bigcaption = (strlen($bigcaption) > 23) ? substr($bigcaption,0,20). " &#8230;" : $bigcaption;
            $bigprice = mysql_result($featuredquery,0,'price');
            $bigphoto = mysql_result($featuredquery,0,'source');
            $bigphotoid = mysql_result($featuredquery,0,'id');
            $bigphoto = str_replace("userphotos/","userphotos/medthumbs/",$bigphoto);
            echo'<div class="bigphoto"><a href="fullsize.php?imageid=',$bigphotoid,'"><img src="https://photorankr.com/',$bigphoto,'" /></a></div>';
            for($iii=1; $iii < 13; $iii++) {
                $smallphoto = mysql_result($featuredquery,$iii,'source');
                $smallranking = number_format((mysql_result($featuredquery,0,'points')/mysql_result($featuredquery,0,'votes')),2);
                $imageid = mysql_result($featuredquery,$iii,'id');
                $price = mysql_result($featuredquery,$iii,'price');
                $smallcaption = mysql_result($featuredquery,$iii,'caption');
                $smallphoto = str_replace("userphotos/","userphotos/medthumbs/",$smallphoto);
                echo'<div class="galleryContainer">
                        <a style="text-decoration:none;" href="fullsize.php?imageid=',$imageid,'">
                        <div class="galleryContainerOverlay">
                            <header>',$smallcaption,'<br /><br />$',$price,'</header>
                        </div>
                            <img class="smallphoto" src="https://photorankr.com/',$smallphoto,'" />
                        </a>
                    </div>';            
            }
        
        ?>
        </div>
        <div class="statoverlay" style="z-index:1;background-color:white;position:relative;left:0px;top:-65px;width:280px;height:30px;">
                <div style="line-spacing:1.48;padding:5px;color:#4A4A4A;">
                    <div style="float:left;padding-top:10px;">
                        <span style="font-size:15px;font-weight:500;">$<?php echo $bigprice; ?></span>&nbsp;&nbsp;<span style="font-weight:300;font-size:15px;"><?php echo $bigcaption; ?></span>
                    </div>
                </div>
            </div>
        </div>
    
     <div id="featuredPhotogsDiv">
        <div class="galleryGut">
        <?php
            $featureduserquery = mysql_query("SELECT user_id,firstname,lastname,profilepic,reputation FROM userinfo WHERE emailaddress NOT IN ('msniff16@gmail.com') ORDER BY totalscore DESC LIMIT 0,15");
            $bigname = mysql_result($featureduserquery,0,'firstname') ." ". mysql_result($featureduserquery,0,'lastname');
            $userphoto = mysql_result($featureduserquery,0,'profilepic');
            $biguserid = mysql_result($featureduserquery,0,'user_id');
            $bigrep = mysql_result($featureduserquery,0,'reputation');
            echo'<div class="bigphoto"><a href="viewprofile.php?userid=',$biguserid,'"><img src="https://photorankr.com/',$userphoto,'" /></a></div>';
            for($iii=1; $iii < 13; $iii++) {
                $smallphoto = mysql_result($featureduserquery,$iii,'profilepic');
                $userid = mysql_result($featureduserquery,$iii,'user_id');
                echo'<div class="galleryContainer">
                        <a style="text-decoration:none;" href="viewprofile.php?u=',$userid,'">
                        <div class="galleryContainerOverlay">
                            <header>',mysql_result($featureduserquery,$iii,'firstname'),'<br />',mysql_result($featureduserquery,$iii,'lastname'),'<br /><br />',number_format(mysql_result($featureduserquery,$iii,'reputation'),2),'</header>
                        </div>
                            <img class="smallphoto" src="https://photorankr.com/',$smallphoto,'" />
                        </a>
                    </div>'; 
            }
        
        ?>
        </div>
        <div class="statoverlay" style="z-index:1;background-color:white;position:relative;left:0px;top:-65px;width:280px;height:30px;">
                <div style="line-spacing:1.48;padding:5px;color:#4A4A4A;">
                    <div style="float:left;padding-top:10px;">
                        <span style="font-size:15px;font-weight:500;"><?php echo $bigrep; ?></span>&nbsp;&nbsp;<span style="font-weight:300;font-size:15px;"><?php echo $bigname; ?></span>
                    </div>
                </div>
            </div>

        </div>
    
    <div id="featuredExhibitsDiv">
        <div class="galleryGut">
        <?php
            $allSets = mysql_query("SELECT * FROM sets ORDER BY views DESC LIMIT 0,13");
            $set_id = mysql_result($allSets, 0, "id");
            $setowner = mysql_result($allSets, 0, "owner");
            $ownerid = mysql_query("SELECT user_id FROM userinfo WHERE emailaddress = '$setowner'");
            $userid = mysql_result($ownerid, 0, "user_id");
            $pullTopExhibit = mysql_query("SELECT source FROM photos WHERE set_id = '$set_id' ORDER BY votes DESC LIMIT 1");
            $setcover = mysql_result($allSets, 0, "cover");
            $settitle = mysql_result($allSets, 0, "title");
            $settitle = (strlen($settitle) > 23) ? substr($settitle,0,20). " &#8230;" : $settitle;
            $avgscore = mysql_result($allSets, 0, "avgscore");
            if($setcover == '') {
                $setcover = mysql_result($pullTopExhibit, 0, "source");
            }
            echo'<div class="bigphoto"><a href="viewprofile.php?u=',$userid,'&view=exhibits&setid=',$set_id,'"><img style="height:265px;" src="https://photorankr.com/',$setcover,'" /></a></div>';
            for($iii=1; $iii < 13; $iii++) {
                $smallphotoid = mysql_result($allSets,$iii,'id');
                $setowner = mysql_result($allSets, $iii, "owner");
                $setTitle = mysql_result($allSets, $iii, "title");
                $avgSetScore = mysql_result($allSets, $iii, "avgscore");
                $ownerid = mysql_query("SELECT user_id FROM userinfo WHERE emailaddress = '$setowner'");
                $userid = mysql_result($ownerid, 0, "user_id");
                $pullexsource = mysql_query("SELECT source FROM photos WHERE set_id = '$smallphotoid' ORDER BY votes DESC LIMIT 1");
                $smallphoto = mysql_result($pullexsource,0,'source');
                $smallphoto = str_replace("userphotos/","userphotos/medthumbs/",$smallphoto);
                echo'<div class="galleryContainer">
                        <a style="text-decoration:none;" href="viewprofile.php?u=',$userid,'&view=exhibits&setid=',$smallphotoid,'">
                        <div class="galleryContainerOverlay">
                            <header>',$setTitle,'<br /><br />',$avgSetScore,'</header>
                        </div>
                            <img class="smallphoto" src="https://photorankr.com/',$smallphoto,'" />
                        </a>
                    </div>';
            }
        
        ?>
        </div>
        <div class="statoverlay" style="z-index:1;background-color:white;position:relative;left:0px;top:-65px;width:280px;height:30px;">
                <div style="line-spacing:1.48;padding:5px;color:#4A4A4A;">
                    <div style="float:left;padding-top:10px;">
                        <span style="font-size:15px;font-weight:500;"><?php echo $avgscore; ?></span>&nbsp;&nbsp;<span style="font-weight:300;font-size:15px;"><?php echo $settitle; ?></span>
                    </div>
                </div>
            </div>

    </div>

</div>
    
    <!---Trending Div---->
    <div class="galleryBlock" style="margin-top:50px;margin-left:68px;">
    
        <div class="galleryToolbar">
            <ul>
                <a style="color:#333;" href="trending.php"><li style="width:272px;-webkit-border-radius: 4px;-moz-border-radius: 2px;border-radius: 2px;padding-left:8px;margin-left:0px;text-align:left;"><img style="float:left;width:20px;height:20px;" src="graphics/graph.png" />&nbsp;&nbsp;Trending</li>
                </a>
                <li id="trendingPhotos" style="width:134px;"><img src="graphics/camera2.png" /> Photos </li>
                <li id="trendingPhotogs" style="width:134px;"><img src="graphics/user.png" /> Photographers</li>
                <li id="trendingExhibits" style="width:134px;"><img src="graphics/grid.png" /> Exhibits </li>
            </ul>
        </div>
    
    <div id="trendingPhotosDiv">
        <div class="galleryGut">
        <?php
            $trendquery = mysql_query("SELECT * FROM photos ORDER BY score DESC LIMIT 0,30");
            $bigcaption = mysql_result($trendquery,0,'caption');
            $bigcaption = (strlen($bigcaption) > 23) ? substr($bigcaption,0,20). " &#8230;" : $bigcaption;
            $bigranking = number_format((mysql_result($trendquery,0,'points')/mysql_result($trendquery,0,'votes')),2);
            $bigphoto = mysql_result($trendquery,0,'source');
            $bigphotoid = mysql_result($trendquery,0,'id');
            $bigphoto = str_replace("userphotos/","userphotos/medthumbs/",$bigphoto);
            echo'<div class="bigphoto"><a href="fullsize.php?imageid=',$bigphotoid,'"><img src="https://photorankr.com/',$bigphoto,'" /></a></div>';
            for($iii=1; $iii < 13; $iii++) {
                $smallphoto = mysql_result($trendquery,$iii,'source');
                $owner = mysql_result($trendquery,$iii,'emailaddress');
                $imageid = mysql_result($trendquery,$iii,'id');
                $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$owner'");
                $smallphoto = str_replace("userphotos/","userphotos/medthumbs/",$smallphoto);
                $smallcaption = mysql_result($trendquery,$iii,'caption');
                $smallranking = number_format(mysql_result($trendquery,$iii,'points')/mysql_result($trendquery,$iii,'votes'),2);
                echo'<div class="galleryContainer">
                        <a style="text-decoration:none;" href="fullsize.php?imageid=',$imageid,'">
                        <div class="galleryContainerOverlay">
                            <header style="font-weight:300;font-size:13px;position:relative;top:20px;left:15px;width:100px;">',$smallcaption,'<br /><br />',$smallranking,'</header>
                        </div>
                            <img class="smallphoto" src="https://photorankr.com/',$smallphoto,'" />
                        </a>
                    </div>';
                }
        
        ?>
        </div>
        
       <div class="statoverlay" style="z-index:1;background-color:white;position:relative;left:0px;top:-65px;width:280px;height:30px;">
                <div style="line-spacing:1.48;padding:5px;color:#4A4A4A;">
                    <div style="float:left;padding-top:10px;">
                        <span style="font-size:15px;font-weight:500;"><?php echo $bigranking; ?></span>&nbsp;&nbsp;<span style="font-weight:300;font-size:15px;"><?php echo $bigcaption; ?></span>
                    </div>
                </div>
            </div>
    </div>
    
    <div id="trendingPhotogsDiv">
        <div class="galleryGut">
        <?php
            $bigprofilepic = mysql_result($ownerquery, 0, "profilepic");
            $bigtrendingid = mysql_result($ownerquery, 0, "user_id");
            $bigrep = number_format(mysql_result($ownerquery, 0, "reputation"),2);
            $bigtrendname =  mysql_result($ownerquery, 0, "firstname") ." ". mysql_result($ownerquery, 0, "lastname");
            echo'<div class="bigphoto"><a href="fullsize.php?imageid=',$bigtrendingid,'"><img src="https://photorankr.com/',$bigprofilepic,'" /></a></div>';
            for($iii=1; $iii < 30; $iii++) {
                $owner = mysql_result($trendquery, $iii-1, "emailaddress");
                $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$owner'");
                $trendingprofilepic = mysql_result($ownerquery, 0, "profilepic");
                $userid = mysql_result($ownerquery, 0, "user_id");
                if($prevlist[$userid] > 0) {
                    continue;
                }
                $prevlist[$userid] += 1; 
                $counter += 1;
                if($counter > 12) {
                    break;
                }
                
                 echo'<div class="galleryContainer">
                        <a style="text-decoration:none;" href="viewprofile.php?u=',$userid,'">
                        <div class="galleryContainerOverlay">
                            <header>',mysql_result($ownerquery,0,'firstname'),'<br />',mysql_result($ownerquery,0,'lastname'),'<br /><br />',number_format(mysql_result($ownerquery,0,'reputation'),2),'</header>
                        </div>
                            <img class="smallphoto" src="https://photorankr.com/',$trendingprofilepic,'" />
                        </a>
                    </div>'; 

            }
        
        ?>
        </div>
        
      <div class="statoverlay" style="z-index:1;background-color:white;position:relative;left:0px;top:-65px;width:280px;height:30px;">
                <div style="line-spacing:1.48;padding:5px;color:#4A4A4A;">
                    <div style="float:left;padding-top:10px;">
                        <span style="font-size:15px;font-weight:500;"><?php echo $bigrep; ?></span>&nbsp;&nbsp;<span style="font-weight:300;font-size:15px;"><?php echo $bigtrendname; ?></span>
                    </div>
                </div>
            </div>

    </div>
    
     <div id="trendingExhibitsDiv">
        <div class="galleryGut">
        <?php
            $allTrendSets3 = mysql_query("SELECT * FROM sets ORDER BY avgscore DESC LIMIT 0,13");
            $trendset_id3 = mysql_result($allTrendSets3, 0, "id");
            $trendsetowner3 = mysql_result($allTrendSets3, 0, "owner");
            $trendownerid = mysql_query("SELECT user_id FROM userinfo WHERE emailaddress = '$trendsetowner'");
            $trenduserid3 = mysql_result($ownerid, 0, "user_id");
            $pullTrendingExhibit3 = mysql_query("SELECT source FROM photos WHERE set_id = '$trendset_id3' ORDER BY votes DESC LIMIT 1");
            $trendsetcover3 = mysql_result($allTrendSets3, 0, "cover");
            $trendsettitle3 = mysql_result($allTrendSets3, 0, "title");
            $trendsettitle3 = (strlen($trendsettitle3) > 23) ? substr($trendsettitle3,0,20). " &#8230;" : $trendsettitle3;
            $trendavgscore = mysql_result($allTrendSets3, 0, "avgscore");
            if($trendsetcover3 == '') {
                $trendsetcover3 = mysql_result($pullTrendingExhibit3, 0, "source");
            }
            echo'<div class="bigphoto"><a href="viewprofile.php?u=',$trenduserid3,'&view=exhibits&setid=',$trendset_id3,'"><img style="height:265px;" src="https://photorankr.com/',$trendsetcover3,'" /></a></div>';
            for($iii=1; $iii < 13; $iii++) {
                $trendsmallphotoid3 = mysql_result($allTrendSets3,$iii,'id');
                $trendsetowner2 = mysql_result($allTrendSets3, $iii, "owner");
                $trendownerid3 = mysql_query("SELECT user_id FROM userinfo WHERE emailaddress = '$trendsetowner2'");
                $trenduserid3 = mysql_result($trendownerid3, 0, "user_id");
                $trendpullexsource = mysql_query("SELECT source FROM photos WHERE set_id = '$trendsmallphotoid3' ORDER BY votes DESC LIMIT 1");
                $trendsmallphoto = mysql_result($trendpullexsource,0,'source');
                $trendsmallphoto = str_replace("userphotos/","userphotos/medthumbs/",$trendsmallphoto);
                $setTitle = mysql_result($allTrendSets3, $iii, "title");
                $avgSetScore = mysql_result($allTrendSets3, $iii, "avgscore");
                echo'<div class="galleryContainer">
                        <a style="text-decoration:none;" href="viewprofile.php?u=',$trenduserid2,'&view=exhibits&setid=',$trendsmallphotoid2,'">
                        <div class="galleryContainerOverlay">
                            <header>',$setTitle,'<br /><br />',$avgSetScore,'</header>
                        </div>
                            <img class="smallphoto" src="https://photorankr.com/',$trendsmallphoto,'" />
                        </a>
                    </div>';
            }
        
        ?>
        </div>
        <div class="statoverlay" style="z-index:1;background-color:white;position:relative;left:0px;top:-65px;width:280px;height:30px;">
                <div style="line-spacing:1.48;padding:5px;color:#4A4A4A;">
                    <div style="float:left;padding-top:10px;">
                        <span style="font-size:15px;font-weight:500;"><?php echo $trendavgscore; ?></span>&nbsp;&nbsp;<span style="font-weight:300;font-size:15px;"><?php echo $trendsettitle3; ?></span>
                    </div>
                </div>
            </div>
    </div>

</div>
    
    <!---Fresh Div---->
    <div class="galleryBlock" style="margin-top:55px;margin-left:68px;">
    
        <div class="galleryToolbar">
            <ul>
                <a style="color:#333;" href="newest.php"><li style="width:272px;-webkit-border-radius: 4px;-moz-border-radius: 2px;border-radius: 2px;padding-left:8px;margin-left:0px;text-align:left;"><img style="float:left;width:20px;height:20px;" src="graphics/clock.png" />&nbsp;&nbsp;Newest</li></a>
                <li id="freshPhotos" style="width:134px;"><img src="graphics/camera2.png" /> Photos</li>
                <li id="freshPhotogs" style="width:134px;"><img src="graphics/user.png" /> Photographers</li>
                <li id="freshExhibits" style="width:134px;"><img src="graphics/grid.png" /> Exhibits </li>
            </ul>
        </div>
    
    <div id="freshPhotosDiv">
        <div class="galleryGut">
        <?php
            $newestquery = mysql_query("SELECT * FROM photos WHERE time > ($currenttime - 100000) ORDER BY views DESC");
            $bigphoto = mysql_result($newestquery,0,'source');
            $bigcaption = mysql_result($newestquery,0,'caption');
            $bigcaption = (strlen($bigcaption) > 23) ? substr($bigcaption,0,20). " &#8230;" : $bigcaption;
            $bigranking = number_format((mysql_result($newestquery,0,'points')/mysql_result($newestquery,0,'votes')),2);
            $bigphoto = str_replace("userphotos/","userphotos/medthumbs/",$bigphoto);
            $bigphotoid = mysql_result($newestquery,0,'id');
            echo'<div class="bigphoto"><a href="fullsize.php?imageid=',$bigphotoid,'"><img src="https://photorankr.com/',$bigphoto,'" /></a></div>';
            for($iii=1; $iii < 13; $iii++) {
                $smallphoto = mysql_result($newestquery,$iii,'source');
                $imageid = mysql_result($newestquery,$iii,'id');
                $smallphoto = str_replace("userphotos/","userphotos/medthumbs/",$smallphoto);
                $smallcaption = mysql_result($newestquery,$iii,'caption');
                $smallranking = number_format(mysql_result($newestquery,$iii,'points')/mysql_result($newestquery,$iii,'votes'),2);
                echo'<div class="galleryContainer">
                        <a style="text-decoration:none;" href="fullsize.php?imageid=',$imageid,'">
                        <div class="galleryContainerOverlay">
                            <header style="font-weight:300;font-size:13px;position:relative;top:20px;left:15px;width:100px;">',$smallcaption,'<br /><br />',$smallranking,'</header>
                        </div>
                            <img class="smallphoto" src="https://photorankr.com/',$smallphoto,'" />
                        </a>
                    </div>';
            }
        
        ?>
        </div>
        
       <div class="statoverlay" style="z-index:1;background-color:white;position:relative;left:0px;top:-65px;width:280px;height:30px;">
                <div style="line-spacing:1.48;padding:5px;color:#4A4A4A;">
                    <div style="float:left;padding-top:10px;">
                        <span style="font-size:15px;font-weight:500;"><?php echo $bigranking; ?></span>&nbsp;&nbsp;<span style="font-weight:300;font-size:15px;"><?php echo $bigcaption; ?></span>
                    </div>
                </div>
            </div>
    </div>
    
    <div id="freshPhotogsDiv">
        <div class="galleryGut">
        <?php
            $newestusersquery = mysql_query("SELECT * FROM userinfo WHERE profilepic != 'profilepics/default_profile.jpg' ORDER BY user_id DESC LIMIT 15");
            $bigprofilepic = mysql_result($newestusersquery, 0, "profilepic");
            $bignewid = mysql_result($newestusersquery, 0, "user_id");
            $bigrep = number_format(mysql_result($newestusersquery, 0, "reputation"),2);
            $bignewname =  mysql_result($newestusersquery, 0, "firstname") ." ". mysql_result($newestusersquery, 0, "lastname");
            echo'<div class="bigphoto"><a href="fullsize.php?imageid=',$bignewid,'"><img src="https://photorankr.com/',$bigprofilepic,'" /></a></div>';
            for($iii=1; $iii < 13; $iii++) {
                $owner = mysql_result($newestusersquery, $iii, "emailaddress");
                $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$owner'");
                $newestprofilepic = mysql_result($newestusersquery, $iii, "profilepic");
                $userid = mysql_result($newestusersquery, $iii, "user_id");

                 echo'<div class="galleryContainer">
                        <a style="text-decoration:none;" href="viewprofile.php?u=',$userid,'">
                        <div class="galleryContainerOverlay">
                            <header>',mysql_result($ownerquery,0,'firstname'),'<br />',mysql_result($ownerquery,0,'lastname'),'<br /><br />',number_format(mysql_result($ownerquery,0,'reputation'),2),'</header>
                        </div>
                            <img class="smallphoto" src="https://photorankr.com/',$newestprofilepic,'" />
                        </a>
                    </div>'; 
            }
        
        ?>
        </div>
        
        <div class="statoverlay" style="z-index:1;background-color:white;position:relative;left:0px;top:-65px;width:280px;height:30px;">
                <div style="line-spacing:1.48;padding:5px;color:#4A4A4A;">
                    <div style="float:left;padding-top:10px;">
                        <span style="font-size:15px;font-weight:500;"><?php echo $bigrep; ?></span>&nbsp;&nbsp;<span style="font-weight:300;font-size:15px;"><?php echo $bignewname; ?></span>
                    </div>
                </div>
            </div>
    </div>
    
     <div id="freshExhibitsDiv">
        <div class="galleryGut">
        <?php
            $allTrendSets = mysql_query("SELECT * FROM sets ORDER BY id DESC");
            $set_id = mysql_result($allTrendSets, 0, "id");
            $setowner = mysql_result($allTrendSets, 0, "owner");
            $ownerid = mysql_query("SELECT user_id FROM userinfo WHERE emailaddress = '$setowner'");
            $userid = mysql_result($ownerid, 0, "user_id");
            $pullTrendingExhibit = mysql_query("SELECT source FROM photos WHERE set_id = '$set_id' ORDER BY votes DESC LIMIT 1");
            $setcover = mysql_result($allTrendSets, 0, "cover");
            $newsettitle = mysql_result($allTrendSets, 0, "title");
            $newsettitle = (strlen($newsettitle) > 23) ? substr($newsettitle,0,20). " &#8230;" : $newsettitle;
            $avgscore2 = mysql_result($allTrendSets, 0, "avgscore");
            if($setcover == '') {
                $setcover = mysql_result($pullTrendingExhibit, 0, "source");
            }
            echo'<div class="bigphoto"><a href="viewprofile.php?u=',$userid,'&view=exhibits&setid=',$set_id,'"><img style="height:265px;" src="https://photorankr.com/',$setcover,'" /></a></div>';
            for($iii=1; $iii < 13; $iii++) {
                $smallphotoid = mysql_result($allTrendSets,$iii,'id');
                $setowner = mysql_result($allTrendSets, $iii, "owner");
                $ownerid = mysql_query("SELECT user_id FROM userinfo WHERE emailaddress = '$setowner'");
                $userid = mysql_result($ownerid, 0, "user_id");
                $pullexsource2 = mysql_query("SELECT source FROM photos WHERE set_id = '$smallphotoid' ORDER BY votes DESC LIMIT 1");
                $smallphoto2 = mysql_result($pullexsource2,0,'source');
                $smallphoto2 = str_replace("userphotos/","userphotos/medthumbs/",$smallphoto2);
                $setTitle = mysql_result($allTrendSets, $iii, "title");
                $avgSetScore = mysql_result($allTrendSets, $iii, "avgscore");
                echo'<div class="galleryContainer">
                        <a style="text-decoration:none;" href="viewprofile.php?u=',$userid,'&view=exhibits&setid=',$smallphotoid,'">
                        <div class="galleryContainerOverlay">
                            <header>',$setTitle,'<br /><br />',$avgSetScore,'</header>
                        </div>
                            <img class="smallphoto" src="https://photorankr.com/',$smallphoto2,'" />
                        </a>
                    </div>';
            }
        
        ?>
        </div>
        <div class="statoverlay" style="z-index:1;background-color:white;position:relative;left:0px;top:-65px;width:280px;height:30px;">
                <div style="line-spacing:1.48;padding:5px;color:#4A4A4A;">
                    <div style="float:left;padding-top:10px;">
                        <span style="font-size:15px;font-weight:500;"><?php echo $avgscore2; ?></span>&nbsp;&nbsp;<span style="font-weight:300;font-size:15px;"><?php echo $newsettitle; ?></span>
                    </div>
                </div>
            </div>
    </div>
    
</div>


<!---Top Ranked Div---->
    <div class="galleryBlock" style="margin-top:65px;margin-left:68px;padding-bottom:20px;">
    
        <div class="galleryToolbar">
            <ul>
                <a style="color:#333;" href="topranked.php"><li style="width:272px;-webkit-border-radius: 4px;-moz-border-radius: 2px;border-radius: 2px;padding-left:8px;margin-left:0px;text-align:left;"><img style="float:left;width:20px;height:20px;" src="graphics/award.png" />&nbsp;&nbsp;Top Ranked</li></a>
                <li id="topRankedPhotos" style="width:134px;"><img src="graphics/camera2.png" /> Photos</li>
                <li id="topRankedPhotogs" style="width:134px;"><img src="graphics/user.png" /> Photographers</li>
                <li id="topRankedExhibits" style="width:134px;"><img src="graphics/grid.png" /> Exhibits</li>
            </ul>
        </div>
    
    <div id="topRankedPhotosDiv">
        <div class="galleryGut">
        <?php
            $featuredquery = mysql_query("SELECT * FROM photos WHERE views > 70 AND faves > 6 ORDER BY (points/votes) DESC LIMIT 0,15");
            $bigcaption = mysql_result($featuredquery,0,'caption');
            $bigcaption = (strlen($bigcaption) > 23) ? substr($bigcaption,0,20). " &#8230;" : $bigcaption;
            $bigranking = number_format((mysql_result($featuredquery,0,'points')/mysql_result($featuredquery,0,'votes')),2);
            $bigphoto = mysql_result($featuredquery,0,'source');
            $bigphotoid = mysql_result($featuredquery,0,'id');
            $bigphoto = str_replace("userphotos/","userphotos/medthumbs/",$bigphoto);
            echo'<div class="bigphoto"><a href="fullsize.php?imageid=',$bigphotoid,'"><img src="https://photorankr.com/',$bigphoto,'" /></a></div>';
            for($iii=1; $iii < 13; $iii++) {
                $smallphoto = mysql_result($featuredquery,$iii,'source');
                $smallranking = number_format((mysql_result($featuredquery,0,'points')/mysql_result($featuredquery,0,'votes')),2);
                $imageid = mysql_result($featuredquery,$iii,'id');
                $smallphoto = str_replace("userphotos/","userphotos/medthumbs/",$smallphoto);
                $smallcaption = mysql_result($featuredquery,$iii,'caption');
                $smallranking = number_format(mysql_result($featuredquery,$iii,'points')/mysql_result($featuredquery,$iii,'votes'),2);
                echo'<div class="galleryContainer">
                        <a style="text-decoration:none;" href="fullsize.php?imageid=',$imageid,'">
                        <div class="galleryContainerOverlay">
                            <header style="font-weight:300;font-size:13px;position:relative;top:20px;left:15px;width:100px;">',$smallcaption,'<br /><br />',$smallranking,'</header>
                        </div>
                            <img class="smallphoto" src="https://photorankr.com/',$smallphoto,'" />
                        </a>
                    </div>';
            }
        
        ?>
        </div>
        <div class="statoverlay" style="z-index:1;background-color:white;position:relative;left:0px;top:-65px;width:280px;height:30px;">
                <div style="line-spacing:1.48;padding:5px;color:#4A4A4A;">
                    <div style="float:left;padding-top:10px;">
                        <span style="font-size:15px;font-weight:500;"><?php echo $bigranking; ?></span>&nbsp;&nbsp;<span style="font-weight:300;font-size:15px;"><?php echo $bigcaption; ?></span>
                    </div>
                </div>
            </div>
        </div>
    
     <div id="topRankedPhotogsDiv">
        <div class="galleryGut">
        <?php
            $featureduserquery = mysql_query("SELECT user_id,firstname,lastname,profilepic,reputation FROM userinfo ORDER BY reputation DESC LIMIT 0,15");
            $bigname = mysql_result($featureduserquery,0,'firstname') ." ". mysql_result($featureduserquery,0,'lastname');
            $userphoto = mysql_result($featureduserquery,0,'profilepic');
            $biguserid = mysql_result($featureduserquery,0,'user_id');
            $bigrep = mysql_result($featureduserquery,0,'reputation');
            echo'<div class="bigphoto"><a href="viewprofile.php?userid=',$biguserid,'"><img src="https://photorankr.com/',$userphoto,'" /></a></div>';
            for($iii=1; $iii < 13; $iii++) {
                $smallphoto = mysql_result($featureduserquery,$iii,'profilepic');
                $userid = mysql_result($featureduserquery,$iii,'user_id');
                
                echo'<div class="galleryContainer">
                        <a style="text-decoration:none;" href="viewprofile.php?u=',$userid,'">
                        <div class="galleryContainerOverlay">
                            <header>',mysql_result($featureduserquery,$iii,'firstname'),'<br />',mysql_result($featureduserquery,$iii,'lastname'),'<br /><br />',number_format(mysql_result($featureduserquery,$iii,'reputation'),2),'</header>
                        </div>
                            <img class="smallphoto" src="https://photorankr.com/',$smallphoto,'" />
                        </a>
                    </div>'; 
            }
        
        ?>
        </div>
        <div class="statoverlay" style="z-index:1;background-color:white;position:relative;left:0px;top:-65px;width:280px;height:30px;">
                <div style="line-spacing:1.48;padding:5px;color:#4A4A4A;">
                    <div style="float:left;padding-top:10px;">
                        <span style="font-size:15px;font-weight:500;"><?php echo $bigrep; ?></span>&nbsp;&nbsp;<span style="font-weight:300;font-size:15px;"><?php echo $bigname; ?></span>
                    </div>
                </div>
            </div>
    </div>
    
    <div id="topRankedExhibitsDiv">
        <div class="galleryGut">
        <?php
            $allSets = mysql_query("SELECT * FROM sets ORDER BY faves DESC LIMIT 0,13");
            $set_id = mysql_result($allSets, 0, "id");
            $setowner = mysql_result($allSets, 0, "owner");
            $ownerid = mysql_query("SELECT user_id FROM userinfo WHERE emailaddress = '$setowner'");
            $userid = mysql_result($ownerid, 0, "user_id");
            $pullTopExhibit = mysql_query("SELECT source FROM photos WHERE set_id = '$set_id' ORDER BY votes DESC LIMIT 1");
            $setcover = mysql_result($allSets, 0, "cover");
            $settitle = mysql_result($allSets, 0, "title");
            $settitle = (strlen($settitle) > 23) ? substr($settitle,0,20). " &#8230;" : $settitle;
            $avgscore = mysql_result($allSets, 0, "avgscore");
            if($setcover == '') {
                $setcover = mysql_result($pullTopExhibit, 0, "source");
            }
            echo'<div class="bigphoto"><a href="viewprofile.php?u=',$userid,'&view=exhibits&setid=',$set_id,'"><img style="height:265px;" src="https://photorankr.com/',$setcover,'" /></a></div>';
            for($iii=1; $iii < 13; $iii++) {
                $smallphotoid = mysql_result($allSets,$iii,'id');
                $setowner = mysql_result($allSets, $iii, "owner");
                $ownerid = mysql_query("SELECT user_id FROM userinfo WHERE emailaddress = '$setowner'");
                $userid = mysql_result($ownerid, 0, "user_id");
                $pullexsource = mysql_query("SELECT source FROM photos WHERE set_id = '$smallphotoid' ORDER BY votes DESC LIMIT 1");
                $smallphoto = mysql_result($pullexsource,0,'source');
                $smallphoto = str_replace("userphotos/","userphotos/medthumbs/",$smallphoto);
                 $setTitle = mysql_result($allSets, $iii, "title");
                $avgSetScore = mysql_result($allSets, $iii, "avgscore");
                echo'<div class="galleryContainer">
                        <a style="text-decoration:none;" href="viewprofile.php?u=',$userid,'&view=exhibits&setid=',$smallphotoid,'">
                        <div class="galleryContainerOverlay">
                            <header>',$setTitle,'<br /><br />',$avgSetScore,'</header>
                        </div>
                            <img class="smallphoto" src="https://photorankr.com/',$smallphoto,'" />
                        </a>
                    </div>';

            }
        
        ?>
        </div>
        <div class="statoverlay" style="z-index:1;background-color:white;position:relative;left:0px;top:-65px;width:280px;height:30px;">
                <div style="line-spacing:1.48;padding:5px;color:#4A4A4A;">
                    <div style="float:left;padding-top:10px;">
                        <span style="font-size:15px;font-weight:500;"><?php echo $avgscore; ?></span>&nbsp;&nbsp;<span style="font-weight:300;font-size:15px;"><?php echo $settitle; ?></span>
                    </div>
                </div>
            </div>
    </div>

</div>
</div>
<br /><br />
<br /><br />

<?php footer(); ?>
    
</body>

 <!--Mobile Redirect-->
    <script type="text/javascript">
        if (screen.width <= 600) {
            window.location = "http://mobile.photorankr.com";
        }
    </script>

</html> 