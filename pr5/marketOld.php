<?php

//connect to the database
require "../db_connection.php";
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
    
    //Time
    $currenttime = time();
    
    //View
    $view = mysql_real_escape_string(htmlentities($_GET['view']));
    
    //Search Word
    $searchword = mysql_real_escape_string(htmlentities($_GET['searchword']));
    
    //Category
    $cat = mysql_real_escape_string(htmlentities($_GET['category']));
    
    //Cart Statistics
    $incart = mysql_query("SELECT * FROM userscart WHERE emailaddress = '$email' ORDER BY id ASC");
    $incartresults = mysql_num_rows($incart);
    
    $marketquery = mysql_query("SELECT * FROM usersmaybe WHERE emailaddress = '$email'");
    $numsavedinmarket = mysql_num_rows($marketquery);
    
    $downloadquery = mysql_query("SELECT * FROM userdownloads WHERE emailaddress = '$email'");
    $numpurchased = mysql_num_rows($downloadquery);

    //Featured Queries
    $freephotos = mysql_query("SELECT * FROM photos WHERE price = '.00' ORDER BY time DESC LIMIT 24");
    $greatdeals = mysql_query("SELECT * FROM photos WHERE faves > 3 and price < '10.00' ORDER BY time DESC LIMIT 24");
    $popularphotos = mysql_query("SELECT * FROM photos WHERE faves > 5 ORDER BY time DESC LIMIT 24");
    $topranked = mysql_query("SELECT * FROM photos WHERE (points/votes) > 8.5 and votes > 5 and time > ($currenttime - 430000) ORDER BY time DESC LIMIT 24");

  
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

    <title>The PhotoRankr Market</title>

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

<!--Drop Down Containers-->

<script type="text/javascript">

jQuery(document).ready(function(){
    jQuery("#greatdeals").live("click", function(event) {        
         jQuery("#greatdealscontainer").toggle();
         jQuery("#freephotoscontainer").hide();
         jQuery("#popularphotoscontainer").hide();
         jQuery("#toprankedcontainer").hide();
         jQuery("#arrow1").toggle();
         jQuery("#arrow2").hide();
         jQuery("#arrow3").hide();
         jQuery("#arrow4").hide();
    });
    jQuery("#freephotos").live("click", function(event) {        
         jQuery("#freephotoscontainer").toggle();
         jQuery("#greatdealscontainer").hide();
         jQuery("#popularphotoscontainer").hide();
         jQuery("#toprankedcontainer").hide();
         jQuery("#arrow2").toggle();
         jQuery("#arrow1").hide();
         jQuery("#arrow3").hide();
         jQuery("#arrow4").hide();
    });
    jQuery("#popularphotos").live("click", function(event) {        
         jQuery("#popularphotoscontainer").toggle();
         jQuery("#freephotoscontainer").hide();
         jQuery("#greatdealscontainer").hide();
         jQuery("#toprankedcontainer").hide();
         jQuery("#arrow3").toggle();
         jQuery("#arrow1").hide();
         jQuery("#arrow2").hide();
         jQuery("#arrow4").hide();
    });
    jQuery("#topranked").live("click", function(event) {        
         jQuery("#toprankedcontainer").toggle();
         jQuery("#freephotoscontainer").hide();
         jQuery("#popularphotoscontainer").hide();
         jQuery("#greatdealscontainer").hide();
         jQuery("#arrow4").toggle();
         jQuery("#arrow1").hide();
         jQuery("#arrow2").hide();
         jQuery("#arrow3").hide();
    });
});

</script>


</head>
<body style="overflow-x:hidden; background-image('graphics/linen.png');min-width:1220px;">

<?php navbar(); ?>

   <!--big container-->
    <div id="container" class="container_24">
    
        <!--Search Bar and Logo-->
        
        <div class="grid_24" style="margin-top:50px;width:1050px;">
        
            <div style="float:left;">
                <img src="https://photorankr.com/graphics/coollogo.png" width="250" />
            </div>
            
            <div style="float:left;margin-left:30px;margin-top:10px;">
                <form method="GET">
                    <input id="searchBox" name="searchword" placeholder="Search the Market&hellip;" type="text" />
                </form>
            </div>
            
            <div style="float:left;margin-left:30px;">
                <div class="topCartText"><a style="text-decoration:none;color:#333;" href="cart.php"> My Cart (<?php echo $incartresults; ?>) </a>
                </div>
                
                <div class="topCartText"><a style="text-decoration:none;color:#333;" href="cart.php?view=purchases"> Purchases (<?php echo $numsavedinmarket; ?>) </a></div>
                
                <div class="topCartText"><a style="text-decoration:none;color:#333;" href="cart.php?view=maybe"> Wish List (<?php echo $numpurchased; ?>) </a>
                </div>
            </div>    
            
            
        </div>


    <!--Clickable Featured Boxes-->
        
    <div class="grid_24" style="width:1120px;margin-top:20px;">
    
    <div style="margin-left:-30px;">
    
        <div id="greatdeals" class="featuredBox">
            <div class="featuredTitle"><p>Great Deals</p></div>
            
            <?php
                for($iii = 0; $iii < 4; $iii++) {
                    $coverphoto = mysql_result($greatdeals,$iii,'source');
                    $coverphoto = str_replace('userphotos/','userphotos/medthumbs/',$coverphoto);
                    $photoinfo =  mysql_result($coverphotoquery,$iii,'about');
                    $imageid = mysql_result($coverphotoquery,$iii,'id');

                    echo'
                        <img id="marketMural" src="https://photorankr.com/',$coverphoto,'" style="width:123px;height:120px;" />';
        
                }
            ?>
            
        </div>
        
        <div id="freephotos" class="featuredBox">
            <div class="featuredTitle"><p>Free this Week</p></div>
            
            <?php
                for($iii = 0; $iii < 4; $iii++) {
                    $coverphoto = mysql_result($freephotos,$iii,'source');
                    $coverphoto = str_replace('userphotos/','userphotos/medthumbs/',$coverphoto);
                    $photoinfo =  mysql_result($coverphotoquery,$iii,'about');
                    $imageid = mysql_result($coverphotoquery,$iii,'id');

                    echo'
                        <img id="marketMural" src="https://photorankr.com/',$coverphoto,'" style="width:123px;height:120px;" />';
        
                }
            ?>
            
        </div>
        
        <div id="popularphotos" class="featuredBox">
            <div class="featuredTitle"><p>Popular</p></div>
            
            <?php
                for($iii = 0; $iii < 4; $iii++) {
                    $coverphoto = mysql_result($popularphotos,$iii,'source');
                    $coverphoto = str_replace('userphotos/','userphotos/medthumbs/',$coverphoto);
                    $photoinfo =  mysql_result($coverphotoquery,$iii,'about');
                    $imageid = mysql_result($coverphotoquery,$iii,'id');

                    echo'
                        <img id="marketMural" src="https://photorankr.com/',$coverphoto,'" style="width:123px;height:120px;" />';
        
                }
            ?>
            
        </div>
        
        <div id="topranked" class="featuredBox">
            <div class="featuredTitle"><p>Top Ranked</p></div>
            
            <?php
                for($iii = 0; $iii < 4; $iii++) {
                    $coverphoto = mysql_result($topranked,$iii,'source');
                    $coverphoto = str_replace('userphotos/','userphotos/medthumbs/',$coverphoto);
                    $photoinfo =  mysql_result($coverphotoquery,$iii,'about');
                    $imageid = mysql_result($coverphotoquery,$iii,'id');

                    echo'
                        <img id="marketMural" src="https://photorankr.com/',$coverphoto,'" style="width:123px;height:120px;" />';
        
                }
            ?>
            
        </div>
        
    </div> 
    
     <!--Featured Containers-->
        
        <div id="arrow1" style="margin-left:90px;" class="featuredArrowUp"></div>
        <div id="greatdealscontainer" class="featuredContainer">
        <div style="width:1080px;margin-left:80px;">
        
            <?php
                for($iii = 0; $iii < 14; $iii++) {
                    $coverphoto = mysql_result($greatdeals,$iii,'source');
                    $coverphoto = str_replace('userphotos/','userphotos/medthumbs/',$coverphoto);
                    $photoinfo =  mysql_result($greatdeals,$iii,'about');
                    $imageid = mysql_result($greatdeals,$iii,'id');
                    $price = mysql_result($greatdeals,$iii,'price');
                    $price = number_format($price,0);

                    echo'
                        <div style="float:left;">
                            <a href="fullsizemarket.php?imageid=',$imageid,'">
                            <img id="marketMural" src="https://photorankr.com/',$coverphoto,'" style="width:150px;height:150px;" />
                            <div style="position:absolute;color:#fff;font-size:45px;font-weight:100;padding:10px;">$',$price,'</div>
                            </a>
                        </div>';
        
                }
            ?>
        </div>
        </div>
        
        <div id="arrow2" style="margin-left:370px;" class="featuredArrowUp"></div>
        <div id="freephotoscontainer" class="featuredContainer">
        <div style="width:1080px;margin-left:80px;">
        
            <?php
                for($iii = 0; $iii < 14; $iii++) {
                    $coverphoto = mysql_result($freephotos,$iii,'source');
                    $coverphoto = str_replace('userphotos/','userphotos/medthumbs/',$coverphoto);
                    $photoinfo =  mysql_result($freephotos,$iii,'about');
                    $imageid = mysql_result($freephotos,$iii,'id');

                    echo'
                        <div style="float:left;">
                            <a href="fullsizemarket.php?imageid=',$imageid,'">
                            <img id="marketMural" src="https://photorankr.com/',$coverphoto,'" style="width:149px;height:150px;" />
                            <div style="position:absolute;color:#fff;font-size:45px;font-weight:100;padding:10px;">Free</div>
                            </a>
                        </div>';
        
                }
            ?>
        </div>
        </div>
        
        <div id="arrow3" style="margin-left:650px;" class="featuredArrowUp"></div>
        <div id="popularphotoscontainer" class="featuredContainer">
        <div style="width:1080px;margin-left:80px;">
        
           <?php
                for($iii = 0; $iii < 14; $iii++) {
                    $coverphoto = mysql_result($popularphotos,$iii,'source');
                    $coverphoto = str_replace('userphotos/','userphotos/medthumbs/',$coverphoto);
                    $photoinfo =  mysql_result($popularphotos,$iii,'about');
                    $imageid = mysql_result($popularphotos,$iii,'id');
                    $price = mysql_result($popularphotos,$iii,'price');
                    $price = number_format($price,0);

                    echo'
                        <div style="float:left;">
                            <a href="fullsizemarket.php?imageid=',$imageid,'">
                            <img id="marketMural" src="https://photorankr.com/',$coverphoto,'" style="width:149px;height:150px;" />
                            <div style="position:absolute;color:#fff;font-size:45px;font-weight:100;padding:10px;">$',$price,'</div>
                            </a>
                        </div>';
        
                }
            ?>
        
        </div>
        </div>
        
        <div id="arrow4" style="margin-left:920px;" class="featuredArrowUp"></div>
        <div id="toprankedcontainer" class="featuredContainer">
        <div style="width:1080px;margin-left:80px;">
        
           <?php
                for($iii = 0; $iii < 14; $iii++) {
                    $coverphoto = mysql_result($topranked,$iii,'source');
                    $coverphoto = str_replace('userphotos/','userphotos/medthumbs/',$coverphoto);
                    $photoinfo =  mysql_result($topranked,$iii,'about');
                    $imageid = mysql_result($topranked,$iii,'id');
                    $price = mysql_result($topranked,$iii,'price');
                    $price = number_format($price,0);

                    echo'
                        <div style="float:left;">
                            <a href="fullsizemarket.php?imageid=',$imageid,'">
                            <img id="marketMural" src="https://photorankr.com/',$coverphoto,'" style="width:149px;height:150px;" />
                            <div style="position:absolute;color:#fff;font-size:45px;font-weight:100;padding:10px;">$',$price,'</div>
                            </a>
                        </div>';
        
                }
            ?>
        
        </div>
        </div>
        
        

    <!--Galleries-->
            
    <div class="grid_18">
  
    <?php

    if(!$searchword && $cat == '') {
        
        //Popular Title
        echo'<div class="grid_18 bigText" style="position:relative;top:20px;">Popular</div>';
                
        echo'<div class="grid_18" style="height:320px;overflow:hidden;margin-top:30px;margin-left:-35px;">';

        echo'<div style="width:1200px;">';
            
            $trendquery = mysql_query("SELECT * FROM photos WHERE time > ($currenttime - 286400) ORDER BY (points/votes) DESC LIMIT 0,14");
            
            for($iii=0;$iii<14;$iii++) {
                $source = mysql_result($trendquery,$iii,'source');
                $sourceThumb = str_replace("userphotos/","userphotos/medthumbs/", $source);
                $source = "../" . $source;
                list($width, $height) = getimagesize($source);
                $imgratio = $height / $width;
                $heightls = $height / 3.3;
                $widthls = $width / 3.3;
                
                echo'<div style="float:left;height:160px;max-width:180px;padding-left:1px;padding-bottom:1px;overflow:hidden;">
						<img style="height:160px;" src="https://photorankr.com/',$sourceThumb,'" />
                    </div>';
            }
            
        echo'</div></div>';
        
        
     echo'<!--Top Ranked Title-->
        <div class="grid_18 bigText" style="position:relative;top:20px;">Top Ranked</div>';
                        
        echo'<div class="grid_18" style="height:320px;overflow:hidden;margin-top:30px;margin-left:-35px;">';

        echo'<div style="width:1200px;">';
            
            $topquery = mysql_query("SELECT * FROM photos WHERE faves > 7 ORDER BY RAND() DESC LIMIT 0,14");
            
            for($iii=0;$iii<14;$iii++) {
                $source = mysql_result($topquery,$iii,'source');
                $sourceThumb = str_replace("userphotos/","userphotos/medthumbs/", $source);
                $source = "../" . $source;
                list($width, $height) = getimagesize($source);
                $imgratio = $height / $width;
                $heightls = $height / 3.3;
                $widthls = $width / 3.3;
                
                echo'<div style="float:left;height:160px;max-width:180px;padding-left:1px;padding-bottom:1px;overflow:hidden;">
						<img style="height:160px;" src="https://photorankr.com/',$sourceThumb,'" />
                    </div>';
            }
            
        echo'</div></div>';
        
        
    echo'<!--Favorites Title-->
        <div class="grid_18 bigText" style="position:relative;top:20px;">Favorites</div>';
                        
        echo'<div class="grid_18" style="height:320px;overflow:hidden;margin-top:30px;margin-left:-35px;">';

        echo'<div style="width:1200px;">';
            
            $favequery = mysql_query("SELECT * FROM photos WHERE time > ($currenttime - 286400) ORDER BY faves DESC LIMIT 0,14");
            
            for($iii=0;$iii<14;$iii++) {
                $source = mysql_result($favequery,$iii,'source');
                $sourceThumb = str_replace("userphotos/","userphotos/medthumbs/", $source);
                $source = "../" . $source;
                list($width, $height) = getimagesize($source);
                $imgratio = $height / $width;
                $heightls = $height / 3.3;
                $widthls = $width / 3.3;
                
                echo'<div style="float:left;height:160px;max-width:180px;padding-left:1px;padding-bottom:1px;overflow:hidden;">
						<img style="height:160px;" src="https://photorankr.com/',$sourceThumb,'"  />
                    </div>';
            }
            
        echo'</div></div>';
    
    } //end view == ''
    
   
   elseif($cat || $searchword) {
   
    if($searchword) {
        $numsearchquery = mysql_query("SELECT * FROM photos WHERE concat(caption,location,tag1,tag2,tag3,tag4,singlecategorytags,singlestyletags) LIKE '%$searchword%'");
        $numsearchresults = mysql_num_rows($numsearchquery);
        $result = mysql_query("SELECT * FROM photos WHERE concat(caption,location,tag1,tag2,tag3,tag4,singlecategorytags,singlestyletags) LIKE '%$searchword%' ORDER BY views DESC LIMIT 0,16");
    }
    
    if($cat == 'aerial') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Aerial%' ORDER BY faves DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'animal') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Animal%' ORDER BY faves DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'architecture') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Architecture%' ORDER BY faves DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'astro') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Astro%' ORDER BY faves DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'automotive') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Automotive%' ORDER BY faves DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'bw') {
        $result = mysql_query("SELECT * FROM photos WHERE singlestyletags LIKE '%B&W%' ORDER BY faves DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'cityscape') {
        $result = mysql_query("SELECT * FROM photos WHERE singlestyletags LIKE '%cityscape%' ORDER BY faves DESC LIMIT 0, 16");
    }
    
     elseif($cat == 'fashion') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Fashion%' ORDER BY faves DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'fineart') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Fine Art%' ORDER BY faves DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'fisheye') {
        $result = mysql_query("SELECT * FROM photos WHERE singlestyletags LIKE '%Fisheye%' ORDER BY faves DESC LIMIT 0, 16");
    }
    
     elseif($cat == 'food') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Food%' ORDER BY faves DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'HDR') {
        $result = mysql_query("SELECT * FROM photos WHERE singlestyletags LIKE '%HDR%' ORDER BY faves DESC LIMIT 0, 16");
    }
    
     elseif($cat == 'historical') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Historical%' ORDER BY faves DESC LIMIT 0, 16");
    }
    
     elseif($cat == 'industrial') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Industrial%' ORDER BY faves DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'landscape') {
        $result = mysql_query("SELECT * FROM photos WHERE singlestyletags LIKE '%Landscape%' ORDER BY faves DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'longexposure') {
        $result = mysql_query("SELECT * FROM photos WHERE singlestyletags LIKE '%Long Exposure%' ORDER BY faves DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'macro') {
        $result = mysql_query("SELECT * FROM photos WHERE singlestyletags LIKE '%Macro%' ORDER BY faves DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'monochrome') {
        $result = mysql_query("SELECT * FROM photos WHERE singlestyletags LIKE '%Monochrome%' ORDER BY faves DESC LIMIT 0, 16");
    }
    
     elseif($cat == 'nature') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Nature%' ORDER BY faves DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'news') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%News%' ORDER BY faves DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'night') {
        $result = mysql_query("SELECT * FROM photos WHERE singlestyletags LIKE '%Night%' ORDER BY faves DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'panorama') {
        $result = mysql_query("SELECT * FROM photos WHERE singlestyletags LIKE '%Panorama%' ORDER BY faves DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'people') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%People%' ORDER BY faves DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'scenic') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Scenic%' ORDER BY faves DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'sports') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Sports%' ORDER BY faves DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'stilllife') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Still Life%' ORDER BY faves DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'transportation') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Transportation%' ORDER BY faves DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'war') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%War%' ORDER BY faves DESC LIMIT 0, 12");
    }
    
        $numresults = mysql_num_rows($result);
        
        //Search Title
        if($cat) {
            echo'<div class="grid_18 bigText" style="position:relative;top:20px;"><a style="text-decoration:none;color:#333;" name="cat">',ucwords($cat),'</a></div>';
        }
        elseif($searchword) {
            echo'<div class="grid_18 bigText" style="position:relative;top:20px;"><a style="text-decoration:none;color:#333;" name="cat">',$numsearchresults,' results for ',$searchword,'</a></div>';
        }
                        
        echo'<div class="grid_18" style="margin-top:30px;margin-left:-35px;">';
        
        echo'<div id="thepics" style="width:740px;">
             <div id="main">';
            
            for($iii = 0; $iii < 12; $iii++) {
                $source = mysql_result($result,$iii,'source');
                $sourceThumb = str_replace("userphotos/","userphotos/medthumbs/", $source);
                $source = "../" . $source;
                $views = mysql_result($result, $iii, "views");
                $id = mysql_result($result, $iii, "id");
                list($width, $height) = getimagesize($source);
                $imgratio = $height / $width;
                $heightls = $height / 2.7;
                $widthls = $width / 2.7;
                
                echo'<div class="fPic" id="',$views,'" style="float:left;height:240px;max-width:240px;padding-left:1px;padding-bottom:1px;overflow:hidden;">
						<a href="fullsizemarket.php?imageid=',$id,'"><img style="height:240px;" src="https://photorankr.com/',$sourceThumb,'" width="',$widthls,'px" /></a>
                    </div>';
            }
            
        echo'</div>
             </div>

    <!--AJAX CODE HERE-->
   <div class="grid_9 push_5" style="padding-top:50px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:18px; font-weight:300;">Loading More Photos&hellip;</div>
   </div>';


echo'<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMorePics").show();
				$.ajax({
					url: "loadMoreMarketPics.php?lastPicture=" + $(".fPic:last").attr("id")+"&c=',$cat,'"+"&views=',$views,'"+"&sw=',$searchword,'",
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
</script>

    </div>';
    
   } 


?>

    <!--End Galleries-->
    </div>
                    
        <!--Quick Links to Categories-->
        <div class="grid_8 filled rounded shadow">
             <div class="cartText">Categories</div>
             <div style="width:340px;padding-top:5px;">
             <ul id="list" style="float:left;width:160px;margin-left:20px;">
                <li><a href="?category=aerial#cat">Aerial</a></li>
                <li><a href="?category=animal#cat">Animal</a></li>
                <li><a href="?category=architecture#cat">Architecture</a></li>
                <li><a href="?category=automotive#cat">Automotive</a></li>
                <li><a href="?category=b&w#cat">Black & White</a></li>
                <li><a href="?category=cityscape#cat">Cityscape</a></li>
                <li><a href="?category=fashion#cat">Fashion</a></li>
                <li><a href="?category=fineart#cat">Fine Art</a></li>
                <li><a href="?category=fisheye#cat">Fisheye</a></li>
                <li><a href="?category=food#cat">Food</a></li>
                <li><a href="?category=HDR#cat">HDR</a></li>
                <li><a href="?category=historical#cat">Historical</a></li>
                <li><a href="?category=industrial#cat">Industrial</a></li>
            </ul>
            <ul id="list" style="float:left;width:120px;">
                <li><a href="?category=landscape#cat">Landscape</a></li>
                <li><a href="?category=longexposure#cat">Long Exposure</a></li>
                <li><a href="?category=macro#cat">Macro</a></li>
                <li><a href="?category=monochrome#cat">Monochrome</a></li>
                <li><a href="?category=nature#cat">Nature</a></li>
                <li><a href="?category=news#cat">News</a></li>
                <li><a href="?category=night#cat">Night</a></li>
                <li><a href="?category=panorama#cat">Panorama</a></li>
                <li><a href="?category=people#cat">People</a></li>
                <li><a href="?category=scenic#cat">Scenic</a></li>
                <li><a href="?category=sports#cat">Sports</a></li>
                <li><a href="?category=stillife#cat">Still Life</a></li>
                <li><a href="?category=transportation#cat">Transportation</a></li>
            </ul>
            </div>
        </div>
    </div>

    
    
    
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