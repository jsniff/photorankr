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
    
    //Time
    $currenttime = time();
    
    //View
    $view = mysql_real_escape_string(htmlentities($_GET['view']));
    
    //Search Word
    $searchword = mysql_real_escape_string(htmlentities($_GET['term']));
    
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
    $topranked = mysql_query("SELECT * FROM photos WHERE (points/votes) > 8.5 and votes > 3 and time > ($currenttime - 430000) ORDER BY time DESC LIMIT 24");
    $numphotosquery = mysql_query("SELECT * FROM photos");
    $numphotos = number_format(mysql_num_rows($numphotosquery),2);
    
     //Search Queries
    $license = mysql_real_escape_string(htmlentities($_GET['license']));
    $category = mysql_real_escape_string(htmlentities($_GET['category']));
    $maxprice = mysql_real_escape_string(htmlentities($_GET['maxPrice']));
    $minprice = mysql_real_escape_string(htmlentities($_GET['minPrice']));
    $minwidth = mysql_real_escape_string(htmlentities($_GET['minWidth']));
    $maxwidth = mysql_real_escape_string(htmlentities($_GET['maxWidth']));
    $minheight = mysql_real_escape_string(htmlentities($_GET['minHeight']));
    $maxheight = mysql_real_escape_string(htmlentities($_GET['maxHeight']));
    $minrep = mysql_real_escape_string(htmlentities($_GET['minRep']));
    $maxrep = mysql_real_escape_string(htmlentities($_GET['maxRep']));
    $quality = mysql_real_escape_string(htmlentities($_GET['quality']));
    $minrank = mysql_real_escape_string(htmlentities($_GET['minRank']));
    $maxrank = mysql_real_escape_string(htmlentities($_GET['maxRank']));


// JOIN userinfo ON photos.emailaddress = userinfo.emailaddress
if($searchword) {
    $result = "SELECT * FROM photos WHERE concat(caption,location,tag1,tag2,tag3,tag4,singlecategorytags,singlestyletags) LIKE '%$searchword%'";
    }
        
if($category) {
    $result .= " AND singlecategorytags LIKE '%$category%'";
}

if($minrank) {
    $result .= " AND (points/votes) > $minrank";
}
if($maxrank) {
    $result .= " AND (points/votes) < $maxrank";
}

if($license) {
    $result .= " AND license LIKE '%$license%'";
}

if($maxwidth) {
    $result .= " AND width < $maxwidth";
}

if($minwidth) {
    $result .= " AND width > $minwidth";
}

if($maxheight) {
    $result .= " AND license < $height";
}

if($minheight) {
    $result .= " AND license > $height";
}

if($quality) {
    $result .= " AND quality LIKE '%$quality%'";
}

if($minprice) {
    $result .= " AND price > $minprice";
}

if($maxprice) {
    $result .= " AND price < $maxprice";
}

if($maxrep) {
    $result .= " AND userinfo.reputation < $maxrep";
}
                
// if($minrep) {
    // $result .= " AND userinfo.reputation > $minrep";
// }
    
    $result .= "ORDER BY views DESC";
    $result = mysql_query($result);
    $numsearchresults = mysql_num_rows($result);
  
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
    <link rel="stylesheet" type="text/css" href="css/main3.css"/>
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
    
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
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
    jQuery("#searchTools").live("click", function(event) {        
         jQuery("#searchToolsDiv").toggle();
    });
    jQuery("#myCart").live("click", function(event) {        
         jQuery("#myCartDiv").toggle();
         jQuery("#myWishListDiv").hide();
         jQuery("#myPurchasesDiv").hide();
    });
    jQuery("#myWishList").live("click", function(event) {        
         jQuery("#myWishListDiv").toggle();
         jQuery("#myCartDiv").hide();
         jQuery("#myPurchasesDiv").hide();
    });
    jQuery("#myPurchases").live("click", function(event) {        
         jQuery("#myPurchasesDiv").toggle();
         jQuery("#myWishListDiv").hide();
         jQuery("#myCartDiv").hide();
    });
});

</script>


</head>
<body style="overflow-x:hidden; background-image:url('graphics/linen.png');">

<?php navbar(); ?>

<!---Other container-->
<div class="container_24">
        
    <div class="grid_24" style="margin-left:-30px;">
	<!--Featured Section-->
	<div class="marketBody">

		<!--Welcome-->
		<div id="Welcome">
			<header> The <img src="graphics/blacklogo.png" style="margin-top:-4px;width:250px;" /> Marketplace  <span><?php echo $numphotos; ?><span> <img src="graphics/camera.png">photos <span> and counting  </span></span> </span>	</header>
			<p> A whole social network of photography to choose from</p>
		</div>
		
		<!--Featured Section-->
		<div id="featured">
			<header> Featured 
				<!--<span> 
					<a href=""> Free </a>
					<a href=""> Trending </a>
					<a href=""> Top Ranked </a>
					<a href=""> New </a>
					<a href=""> Nature </a>
				</span>-->
			</header>
			<div id="featuredImgContainer"> 
            <?php 
                $featuredPhotos = mysql_query("SELECT * FROM photos WHERE time > ($currenttime - 200000) ORDER BY faves DESC LIMIT 6");
                for($iii=0;$iii<5;$iii++) {
                    $smallimageid = mysql_result($featuredPhotos,$iii,'id');
                    $source = mysql_result($featuredPhotos,$iii,'source');
                    $source = str_replace("userphotos/","userphotos/medthumbs/",$source);
                    
                    echo'<a href="fullsizemarket.php?imageid=',$smallimageid,'">
                         <div class="featuredImgContainer">
                            <img style="width:140px;height:140px;" src="https://photorankr.com/',$source,'" />
                         </div>
                         </a>';
				}
                $bigsource = mysql_result($featuredPhotos,5,'source');
                $bigcaption = mysql_result($featuredPhotos,5,'caption');
                $bigprice = mysql_result($featuredPhotos,5,'price');
                $bigimageid = mysql_result($featuredPhotos,5,'id');
                $bigranking = number_format((mysql_result($featuredPhotos,0,'points')/mysql_result($featuredPhotos,0,'votes')),2);
                $width = mysql_result($featuredPhotos,5,'width');
                $height = mysql_result($featuredPhotos,5,'height');
                $classification = mysql_result($featuredPhotos,5,'classification');
                if($classification == 'commercial') {
                    $classification = 'C';
                }
                elseif($classification == 'editorial') {
                    $classification = 'C';
                }
                elseif($classification == '') {
                    $classification = 'X';
                }
                $bigsource = str_replace("userphotos/","userphotos/medthumbs/",$bigsource);
                ?>
			</div>
			
            <a href="fullsizemarket.php?imageid=<?php echo $bigimageid; ?>">
            <div id="bigImg"> 
				<img src="https://photorankr.com/<?php echo $bigsource; ?>"/>
			</div>
            </a>
            
        <div class="marketoverlay" style="float:right;margin-right:-5px;position:relative;top:-250px;width:255px;height:30px;">
            <div style="line-spacing:1.48;padding:5px;color:#4A4A4A;">
                <div style="float:left;">
                    <span style="font-size:15px;font-weight:500;">$<?php echo $bigprice; ?></span>&nbsp;&nbsp;
                </div>
                <div style="float:right;"><span style="font-weight:500;font-size:15px;"><?php echo $width; ?> x <?php echo $height; ?> &nbsp; <?php echo $classification; ?></span>
                </div>
            </div>
        </div>
            
        <div class="marketunderlay" style="float:right;margin-right:-5px;position:relative;top:-30px;width:255px;height:30px;">
            <div style="line-spacing:1.48;padding:5px;color:#4A4A4A;">
                <div style="float:left;">
                    <span style="font-size:15px;font-weight:500;"><?php echo $bigranking; ?></span>&nbsp;&nbsp;<span style="font-weight:500;font-size:15px;"><?php echo $bigcaption; ?></span>
                </div>
            </div>
        </div>
        
		</div>

	</div>
    </div>
</div>
 
<!--big container-->
<div id="container" class="container_24" style="width:1220px;overflow:hidden;"> 
    
    <!--Clickable Featured Boxes-->
    <div class="grid_24 push_3" style="width:1120px;margin-top:20px;">
    
    <!-------------------------------MARKET SEARCH------------------------------------->
    <script type="text/javascript">
        function formSubmit() {
            document.getElementById("frm1").submit();
        }
    </script>

    <div style="width:720px;overflow:hidden;margin-left:-28px;float:left;">
    
        <div class="marketSearch">
            <form action="" method="get" id="frm1">
                <input type="hidden" name="minwidth" value="<?php echo $minwidth; ?>" />
                <input type="hidden" name="maxwidth" value="<?php echo $maxwidth; ?>" />
                <input type="hidden" name="minheight" value="<?php echo $minheight; ?>" />               
                <input type="hidden" name="minrep" value="<?php echo $minrep; ?>" />
                <input type="hidden" name="maxrep" value="<?php echo $maxrep; ?>" />               
                <input type="hidden" name="quality" value="<?php echo $quality; ?>" />
                <input type="hidden" name="minrank" value="<?php echo $minrank; ?>" />
                <input type="hidden" name="maxrank" value="<?php echo $maxrank; ?>" />
                <input id="searchBar" type="text" name="term" placeholder="Search by keywords or tags&hellip;" />
                <div id="gbqfbw">
                    <button onClick="formSubmit()" id="gbqfb" class="gbqfb" aria-label="PR Search">
                        <span class="gbqfi">Search</span> 
                    </button>
                </div>
            </form>
        </div>
    
        <!--Left market bar-->
        <div class="leftMarketBar">
            <ul>
                <li>  
                <form action="#" method="get">
                <select name="license" onchange="submitLicense(this)" style="margin-top:-5px;width:120px;">
                    <option value=""> License </option>
                    <option value="editorial"> Editorial </option>
                    <option value="commercial"> Commercial </option>
                </select>
                </form>
                </li>
                <li>  
                 <form action="#" method="get">
                    <?php
                        echo'
                        <select name="categories" onchange="submitCategories(this)" style="margin-top:-5px;width:120px;">
                        <option value=""'; if($cat == '') {echo'selected value=""';} echo'>All Photos</option>
                        
                        <option value="aerial"'; if($cat == 'aerial') {echo'selected value=""';} echo'>Aerial</option>
                        
                        <option value="animal"'; if($cat == 'animal') {echo'selected value=""';} echo'>Animal</option>
                        
                        <option value="architecture"'; if($cat == 'architecture') {echo'selected value=""';} echo'>Architecture</option>
                        
                        <option value="astro"'; if($cat == 'astro') {echo'selected value=""';} echo'>Astro</option>
                        
                        <option value="automotive"'; if($cat == 'automotive') {echo'selected value=""';} echo'>Automotive</option>
                        
                        <option value="bw"'; if($cat == 'bw') {echo'selected value=""';} echo'>Black & White</option>
                        
                        <option value="cityscape"'; if($cat == 'cityscape') {echo'selected value=""';} echo'>Cityscape</option>
                        
                        <option value="fashion"'; if($cat == 'fashion') {echo'selected value=""';} echo'>Fashion</option>
                        
                        <option value="fineart"'; if($cat == 'fineart') {echo'selected value=""';} echo'>Fine Art</option>
                        
                        <option value="fisheye"'; if($cat == 'fisheye') {echo'selected value=""';} echo'>Fish Eye</option>
                        
                        <option value="food"'; if($cat == 'food') {echo'selected value=""';} echo'>Food</option>
                        
                        <option value="HDR"'; if($cat == 'HDR') {echo'selected value=""';} echo'>HDR</option>
                        
                        <option value="historical"'; if($cat == 'historical') {echo'selected value=""';} echo'>Historical</option>
                        
                        <option value="industrial"'; if($cat == 'industrial') {echo'selected value=""';} echo'>Industrial</option>
                        
                        <option value="landscape"'; if($cat == 'landscape') {echo'selected value=""';} echo'>Landscape</option>
                        
                        <option value="longexposure"'; if($cat == 'longexposure') {echo'selected value=""';} echo'>Long Exposure</option>
                        
                        <option value="macro"'; if($cat == 'macro') {echo'selected value=""';} echo'>Macro</option>
                        
                        <option value="monochrome"'; if($cat == 'monochrome') {echo'selected value=""';} echo'>Monochrome</option>
                        
                        <option value="nature"'; if($cat == 'nature') {echo'selected value=""';} echo'>Nature</option>
                        
                        <option value="news"'; if($cat == 'news') {echo'selected value=""';} echo'>News</option>
                        
                        <option value="night"'; if($cat == 'night') {echo'selected value=""';} echo'>Night</option>
                        
                        <option value="panorama"'; if($cat == 'panorama') {echo'selected value=""';} echo'>Panorama</option>
                        
                        <option value="people"'; if($cat == 'people') {echo'selected value=""';} echo'>People</option>
                        
                        <option value="scenic"'; if($cat == 'scenic') {echo'selected value=""';} echo'>Scenic</option>
                        
                        <option value="sports"'; if($cat == 'sports') {echo'selected value=""';} echo'>Sports</option>
                        
                        <option value="stilllife"'; if($cat == 'stilllife') {echo'selected value=""';} echo'>Still Life</option>
                        
                        <option value="transportation"'; if($cat == 'transportation') {echo'selected value=""';} echo'>Transportation</option>
                        
                        <option value="war"'; if($cat == 'war') {echo'selected value=""';} echo'>War</option>
                        </select>';
                        ?>
                    </form>
                </li>
            </ul>
        </div>

        <!-----Search Tools Button----->
        <div class="searchTools" id="searchTools" style="cursor:pointer;"> Search Tools</div>
        
        <!-----Search Options------>
        <div class="leftMarketDrop" id="searchToolsDiv">
            <form action="#" method="get">
            
            <input type="hidden" name="term" value="<?php echo $searchword; ?>" />
            <input type="hidden" name="category" value="<?php echo $category; ?>" />
            <input type="hidden" name="license" value="<?php echo $license; ?>" />
            <input type="hidden" name="minwidth" value="<?php echo $minwidth; ?>" />               
            <input type="hidden" name="maxwidth" value="<?php echo $maxwidth; ?>" />
            <input type="hidden" name="minheight" value="<?php echo $minheight; ?>" />
            <input type="hidden" name="minrep" value="<?php echo $minrep; ?>" />
            <input type="hidden" name="maxrep" value="<?php echo $maxrep; ?>" />               
            <input type="hidden" name="quality" value="<?php echo $quality; ?>" />
            <input type="hidden" name="minRank" value="<?php echo $minrank; ?>" />
            <input type="hidden" name="maxrank" value="<?php echo $maxrank; ?>" />
            
            <div class="leftIntDrop">
                <header>Price</header>
                <div>
                    Min <input type="text" name="minPrice" value="<?php echo $minprice; ?>" />  Max <input type="text" name="maxPrice" value="<?php echo $maxprice; ?>" />
                </div>
                <header>Photo Rank</header>
                <div>
                    Min <input type="text" name="minRank" value="<?php echo $minrank; ?>" />  Max <input type="text" name="maxRank" value="<?php echo $maxrank; ?>" />
                </div>
            </div>
            
            <div class="leftIntDrop">
                <header>Width</header>
                <div>
                    Min <input type="text" name="minWidth" value="<?php echo $minwidth; ?>" />  Max <input type="text" name="maxWidth" value="<?php echo $maxwidth; ?>" />
                </div>
                <header>Height</header>
                <div>
                    Min <input type="text" name="minHeight" value="<?php echo $minheight; ?>" />  Max <input type="text" name="maxHeight" value="<?php echo $maxheight; ?>" />
                </div>
            </div>
            
            <div class="leftIntDrop" style="border-right:none;">
                <header>Photographer Rep</header>
                <div>
                    Min <input type="text" name="minRep" value="<?php echo $minrep; ?>" />  Max <input type="text" name="maxRep" value="<?php echo $maxrep; ?>" />
                </div>
                <header>Quality</header>
                <div>
                <?php 
                    if($quality == 'regular') {
                        echo'
                        <input style="width:15px;height:20px;" checked="checked" type="radio" name="quality" value="regular" /> Regular 
                        <input style="width:15px;height:20px;" type="radio" value="premium" name="quality" > Premium';
                    }
                    elseif($quality == 'premium') {
                        echo'
                        <input style="width:15px;height:20px;" type="radio" name="quality" value="regular" /> Regular 
                        <input style="width:15px;height:20px;" checked="checked" type="radio" value="premium" name="quality" > Premium';
                    }
                    else {
                         echo'
                        <input style="width:15px;height:20px;" type="radio" name="quality" value="regular" /> Regular 
                        <input style="width:15px;height:20px;" type="radio" value="premium" name="quality" > Premium';
                    }
                ?>
                </div>
                
                    <input type="submit" style="float:right;padding:10px;" />            
            </div>
            
            </form>
            
        </div>
        
    </div>
    
    <div style="width:360px;float:left;">
    
        <!--Right market bar-->
        <div class="rightMarketBar">
            <ul>
                <li id="myCart" style="cursor:pointer;"> <img src="graphics/cart_b.png" /> Cart <span style="font-weight:500;"><?php echo $incartresults; ?></span></li>
                <li id="myWishList" style="cursor:pointer;"> <img src="graphics/star.png" /> Wishlist <span style="font-weight:500;"><?php echo $numsavedinmarket; ?></span></li>
                <li id="myPurchases" style="border:none;width:115px;cursor:pointer;"> <img src="graphics/bag.png" /> Purchases <span style="font-weight:500;"><?php echo $numpurchased; ?></span></li>
            </ul>
        </div>
        
        <div class="rightMarketDrop uiScrollableAreaTrack invisible_elem" id="myCartDiv">
             <?php
                $cartquery = mysql_query("SELECT * FROM userscart WHERE emailaddress = '$email' ORDER BY id DESC");
                $numresults = mysql_num_rows($cartquery);
                echo'<div class="uiScrollableAreaGripper">';
                for($iii=0;$iii<$numresults;$iii++) {
                    $source = mysql_result($cartquery,$iii,'source');
                    $price = mysql_result($cartquery,$iii,'price');
                    $caption= mysql_result($cartquery,$iii,'caption');
                    $size = mysql_result($cartquery,$iii,'size');
                    $imageid = mysql_result($cartquery,$iii,'imageid');
                    echo'<div style="padding:3px;clear:both;overflow:hidden;">
                            <a href="fullsizemarket.php?imageid=',$imageid,'"><img style="float:left;width:100px;height:100px;" src="',$source,'" /></a>
                            <div class="commentTriangle" style="margin-top:-10px;"></div>
                            <div style="width:225px;float:left;padding-left:10px;height:75px;margin-top:25px;border-bottom:1px solid #aaa;">
                                <span style="width:15px;">$',$price,' ',$caption,'<br /><span style="font-size:14px;color:#666;">',$size,'</span></span>
                            </div>
                         </div>';
                }
                echo'</div>';
            ?>
        </div>
        
        <div class="rightMarketDrop" id="myWishListDiv">
            <?php
                $cartquery = mysql_query("SELECT * FROM usersmaybe WHERE emailaddress = '$email' ORDER BY id DESC");
                $numresults = mysql_num_rows($cartquery);
                echo'<div class="uiScrollableAreaGripper">';
                for($iii=0;$iii<$numresults;$iii++) {
                    $source = mysql_result($cartquery,$iii,'source');
                    $price = mysql_result($cartquery,$iii,'price');
                    $caption= mysql_result($cartquery,$iii,'caption');
                    $size = mysql_result($cartquery,$iii,'size');
                    $imageid = mysql_result($cartquery,$iii,'imageid');
                    echo'<div style="padding:3px;clear:both;">
                            <a href="fullsizemarket.php?imageid=',$imageid,'"><img style="float:left;width:100px;height:100px;" src="',$source,'" /></a>
                            <div class="commentTriangle" style="margin-top:-10px;"></div>
                            <div style="width:225px;float:left;padding-left:10px;height:75px;margin-top:25px;border-bottom:1px solid #aaa;">
                                <span style="width:15px;">$',$price,' ',$caption,'<br /><span style="font-size:14px;color:#666;">',$size,'</span></span>
                            </div>
                         </div>';
                }
                echo'</div>';
            ?>
        </div>

        <div class="rightMarketDrop" id="myPurchasesDiv">
            <?php
                $cartquery = mysql_query("SELECT * FROM userdownloads WHERE emailaddress = '$email' ORDER BY id DESC");
                $numresults = mysql_num_rows($cartquery);
                echo'<div class="uiScrollableAreaGripper">';
                for($iii=0;$iii<$numresults;$iii++) {
                    $source = mysql_result($cartquery,$iii,'source');
                    $price = mysql_result($cartquery,$iii,'price');
                    $caption= mysql_result($cartquery,$iii,'caption');
                    $size = mysql_result($cartquery,$iii,'size');
                    $imageid = mysql_result($cartquery,$iii,'imageid');
                    echo'<div style="padding:3px;clear:both;">
                            <a href="fullsizemarket.php?imageid=',$imageid,'"><img style="float:left;width:100px;height:100px;" src="',$source,'" /></a>
                            <div class="commentTriangle" style="margin-top:-10px;"></div>
                            <div style="width:225px;float:left;padding-left:10px;height:75px;margin-top:25px;border-bottom:1px solid #aaa;">
                                <span style="width:15px;">$',$price,' ',$caption,'<br /><span style="font-size:14px;color:#666;">',$size,'</span></span>
                            </div>
                         </div>';
                }
                echo'</div>';
            ?>
        </div>

    </div>

    <!--Galleries-->
            
    <div class="grid_18">
  
    <?php

    if(!$searchword && $cat == '') {
    ?>
    
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

    <!--Clickable Featured Boxes-->
        
    <div class="grid_24" style="width:1120px;margin-top:20px;">
    
    <div style="margin-left:-30px;">
    
        <div id="greatdeals" class="featuredBox">
            <div class="featuredTitle"><p>Great Deals</p></div>
            
            <?php
                for($iii = 0; $iii < 4; $iii++) {
                    $coverphoto = mysql_result($greatdeals,$iii,'source');
                    $coverphoto = str_replace('userphotos/','userphotos/thumbs/',$coverphoto);
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
                    $coverphoto = str_replace('userphotos/','userphotos/thumbs/',$coverphoto);
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
                    $coverphoto = str_replace('userphotos/','userphotos/thumbs/',$coverphoto);
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
                    $coverphoto = str_replace('userphotos/','userphotos/thumbs/',$coverphoto);
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
                    $coverphoto = str_replace('userphotos/','userphotos/thumbs/',$coverphoto);
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
                    $coverphoto = str_replace('userphotos/','userphotos/thumbs/',$coverphoto);
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
                    $coverphoto = str_replace('userphotos/','userphotos/thumbs/',$coverphoto);
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
                    $coverphoto = str_replace('userphotos/','userphotos/thumbs/',$coverphoto);
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
            
      </div></div>
      
<?php
    
    } //end view == ''
    
   
   elseif($cat || $searchword) {
   
    //Search Title
        if($cat) {
            echo'<div class="grid_18 bigText" style="position:relative;top:20px;"><a style="text-decoration:none;color:#333;" name="cat">',ucwords($cat),'</a></div>';
        }
        elseif($searchword) {
            echo'<div class="grid_18 bigText" style="position:relative;top:20px;"><a style="text-decoration:none;color:#333;" name="cat">',$numsearchresults,' results for ',$searchword,'</a></div>';
        }
    
    echo'
    <div id="thepics" style="position:relative;left:-90px;top:85px;width:1210px;">
    <div id="main">
    <ul id="tiles">';
            
    for($iii=1; $iii < $numsearchresults && $iii < 16; $iii++) {
	$image = mysql_result($result, $iii-1, "source");
    $imageThumb=str_replace("userphotos/","userphotos/medthumbs/", $image);
	$id = mysql_result($result, $iii-1, "id");
    $caption = mysql_result($result, $iii-1, "caption");
    $caption = (strlen($caption) > 28) ? substr($caption,0,25). " &#8230;" : $caption;
    $points = mysql_result($result, $iii-1, "points");
    $price = mysql_result($result, $iii-1, "price");
    if($price != 'Not For Sale') {
        $price = '$' . $price;
    }
    elseif(!$price || $price == 'Not For Sale') {
        $price = 'NFS';
    }
    elseif($price == '.00') {
        $price = 'Free';
    }
    $votes = mysql_result($result, $iii-1, "votes");
    $ranking = number_format($points/$votes,2);
    $score = number_format(($points/$votes),2);
    $owner = mysql_result($result, $iii-1, "emailaddress");
    $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$owner'");
    $firstname = mysql_result($ownerquery, 0, "firstname");
    $lastname = mysql_result($ownerquery, 0, "lastname");
    $fullname = $firstname . " " . $lastname;
    $width = mysql_result($result,$iii-1,'width');
    $height = mysql_result($result,$iii-1,'height');
    $classification = mysql_result($result,$iii-1,'classification');
    if($classification == 'commercial') {
        $classification = 'C';
    }
    elseif($classification == 'editorial') {
        $classification = 'C';
    }
    elseif($classification == '') {
        $classification = 'X';
    }
	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
    $heightls = $height / 3.3;
    $widthls = $width / 3.3;
    if($widthls < 235) {
    $heightls = $heightls * ($heightls/$widthls);
    $widthls = 275;
    }

		echo'
        <div class="marketoverlay" style="margin-top:10px;float:right;margin-bottom:-10px;width:275px;height:30px;">
            <div style="line-spacing:1.48;padding:5px;color:#4A4A4A;">
                <div style="float:left;">
                    <span style="font-size:15px;font-weight:500;">',$price,'</span>&nbsp;&nbsp;
                </div>
                <div style="float:right;"><span style="font-weight:500;font-size:15px;">', $width,' x ',$height,' &nbsp; ',$classification,'</span>
                </div>
            </div>
        </div>
        
        <a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$id,'&v=n"><li class="fPic" id="',$id,'" style="list-style-type: none;width:275px;"><img style="min-width:275px;" src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />
            
        <div class="marketunderlay" style="float:right;position:relative;top:0px;width:275px;height:30px;">
            <div style="line-spacing:1.48;padding:5px;color:#4A4A4A;">
                <div style="float:left;">
                    <span style="font-size:15px;font-weight:500;">',$ranking,'</span>&nbsp;&nbsp;<span style="font-weight:500;font-size:15px;">',$caption,'</span>
                </div>
            </div>
        </div>';
            
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
        itemWidth: 275 // Optional, the width of a grid item
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
   <div class="grid_6 push_11" style="padding-top:25px;padding-bottom:25px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;"><img style="width:50px;" src="graphics/LoadingGIF.gif" />
   </div>
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
</script>';
    
   } 


?>
  
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