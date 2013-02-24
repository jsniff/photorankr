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

if($searchword) {
    $result = "SELECT * FROM photos WHERE 
    concat(id,tag1,tag2,tag3,tag4,singlestyletags,singlecategorytags,caption) LIKE '%$searchword%'";
    }
    
    //find out what lowest/max price of photos are
    if($searchword && !$minprice && !$maxprice) {
        $minpricequery = $result;
        $minpricequery .= " ORDER BY price ASC LIMIT 0,1";
        $minpricequery = mysql_query($minpricequery);
        $minpriceholder = mysql_result($minpricequery,0,'price');
        $maxpricequery = $result;
        $maxpricequery .= " ORDER BY price DESC LIMIT 0,1";
        $maxpricequery = mysql_query($maxpricequery);
        $maxpriceholder = mysql_result($maxpricequery,0,'price');
    }
        
if($minprice) {
    $result .= " AND price > $minprice";
}

if($maxprice) {
    $result .= " AND price < $maxprice";
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

if($maxrep) {
    $result .= " AND userinfo.reputation < $maxrep";
}
                
if($minrep) {
    $result .= " AND userinfo.reputation > $minrep";
}
    
    $result .= " ORDER BY id DESC";
    $result2 = $result;
    $result2 = mysql_query($result2);
    $numsearchresults2 = mysql_num_rows($result2);
    $result .= " LIMIT 0,16";
    $resultrun = mysql_query($result);
    $numsearchresults = mysql_num_rows($resultrun);

  
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="https://www.w3.org/1999/xhtml">

<head>

    <meta name="Generator" content="EditPlus">
    <meta name="Author" content="PhotoRankr, PhotoRankr.com">
    <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
    <meta name="Description" content="Purchase and explore the PhotoRankr Market for the most unique stock photography on the web.">
    <meta name="viewport" content="width=1200" /> 
    
    <title>The PhotoRankr Market</title>

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
<?php include_once("analyticstracking.php") ?>

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
            <form action="#search" method="get" id="frm1">
                <input type="hidden" name="minPrice" value="<?php echo $minprice; ?>" />
                <input type="hidden" name="maxPrice" value="<?php echo $maxprice; ?>" />
                <input type="hidden" name="minWidth" value="<?php echo $minwidth; ?>" />
                <input type="hidden" name="maxWidth" value="<?php echo $maxwidth; ?>" />
                <input type="hidden" name="minHeight" value="<?php echo $minheight; ?>" />               
                <input type="hidden" name="minRep" value="<?php echo $minrep; ?>" />
                <input type="hidden" name="maxRep" value="<?php echo $maxrep; ?>" />               
                <input type="hidden" name="quality" value="<?php echo $quality; ?>" />
                <input type="hidden" name="minRank" value="<?php echo $minrank; ?>" />
                <input type="hidden" name="maxRank" value="<?php echo $maxrank; ?>" />
                <input id="searchBar" type="text" name="term" placeholder="Search by keyword or image ID&hellip;" value="<?php echo $searchword; ?>" />
                <div id="gbqfbw" style="cursor:pointer;">
                    <button onClick="formSubmit()" id="gbqfb" class="gbqfb" aria-label="PR Search" style="cursor:pointer;">
                        <span class="gbqfi" style="cursor:pointer;">Search</span> 
                    </button>
                </div>
            </form>
        </div>
    
    <?php
    //only show if search is perfomed
    if($searchword) {
   ?>
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
            <input type="hidden" name="minWidth" value="<?php echo $minwidth; ?>" />               
            <input type="hidden" name="maxWidth" value="<?php echo $maxwidth; ?>" />
            <input type="hidden" name="minHeight" value="<?php echo $minheight; ?>" />
            <input type="hidden" name="minRep" value="<?php echo $minrep; ?>" />
            <input type="hidden" name="maxRep" value="<?php echo $maxrep; ?>" />               
            <input type="hidden" name="quality" value="<?php echo $quality; ?>" />
            <input type="hidden" name="minRank" value="<?php echo $minrank; ?>" />
            <input type="hidden" name="maxRank" value="<?php echo $maxrank; ?>" />
            
            <div class="leftIntDrop">
                <header>Price</header>
                <div>
                    Min <input type="text" name="minPrice" placeholder="<?php echo $minpriceholder; ?>" value="<?php echo $minprice; ?>" />  Max <input type="text" name="maxPrice" placeholder="<?php echo $maxpriceholder; ?>" value="<?php echo $maxprice; ?>" />
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
                
                <div id="gbqfbw" style="margin-top:-15px;margin-left:-15px;">
                    <button style="float:right;" onClick="formSubmit()" id="gbqfb" class="gbqfb" aria-label="PR Search">
                        <span class="gbqfi">Refine</span> 
                    </button>
                </div>  
                    
            </div>
            
            </form>
            
        </div>
        
    </div>
    
    <!--end of if search word-->
    <?php 
        }
        
        else {
            echo'
                <div style="width:720px;height:40px;">
                 </div></div>';
        
        }
    ?>
    
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
    
     echo'<!--Favorites Title-->
        <div class="grid_18 bigText" style="position:relative;top:20px;">Favorites</div>';
                        
        echo'<div class="grid_18" style="height:320px;overflow:hidden;margin-top:30px;margin-left:-35px;">';

        echo'<div style="width:1200px;">';
            
            $favequery = mysql_query("SELECT * FROM photos WHERE time > ($currenttime - 286400) ORDER BY faves DESC LIMIT 0,14");
            
            for($iii=0;$iii<14;$iii++) {
	    if($iii > 3) {
		flush();
	     }
                $source = mysql_result($favequery,$iii,'source');
                $sourceThumb = str_replace("userphotos/","userphotos/medthumbs/", $source);
                list($width, $height) = getimagesize($source);
                $imgratio = $height / $width;
                $heightls = $height / 3.3;
                $widthls = $width / 3.3;
                
                echo'<div style="float:left;height:160px;max-width:180px;padding-left:1px;padding-bottom:1px;overflow:hidden;">
						<img style="height:160px;" src="https://photorankr.com/',$sourceThumb,'" width="',$widthls,'px" />
                    </div>';
            }
            
        echo'</div></div>';
        
     //Popular Title
        echo'<div class="grid_18 bigText" style="position:relative;top:20px;">Popular</div>';
                
        echo'<div class="grid_18" style="height:320px;overflow:hidden;margin-top:30px;margin-left:-35px;">';

        echo'<div style="width:1200px;">';
            
            $trendquery = mysql_query("SELECT * FROM photos WHERE time > ($currenttime - 286400) ORDER BY (points/votes) DESC LIMIT 0,14");
            
            for($iii=0;$iii<14;$iii++) {
	     if($iii > 3) {
		flush();
	     }
                $source = mysql_result($trendquery,$iii,'source');
                $sourceThumb = str_replace("userphotos/","userphotos/medthumbs/", $source);
                list($width, $height) = getimagesize($source);
                $imgratio = $height / $width;
                $heightls = $height / 3.3;
                $widthls = $width / 3.3;
                
                echo'<div style="float:left;height:160px;max-width:180px;padding-left:1px;padding-bottom:1px;overflow:hidden;">
						<img style="height:160px;" src="https://photorankr.com/',$sourceThumb,'" width="',$widthls,'px" />
                    </div>';
            }
            
        echo'</div></div>';
        
        
     echo'<!--Top Ranked Title-->
        <div class="grid_18 bigText" style="position:relative;top:20px;">Top Ranked</div>';
                        
        echo'<div class="grid_18" style="height:320px;overflow:hidden;margin-top:30px;margin-left:-35px;">';

        echo'<div style="width:1200px;">';
            
            $topquery = mysql_query("SELECT * FROM photos WHERE faves > 7 ORDER BY RAND() DESC LIMIT 0,14");
            
            for($iii=0;$iii<14;$iii++) {
	     if($iii > 3) {
		flush();
	     }
                $source = mysql_result($topquery,$iii,'source');
                $sourceThumb = str_replace("userphotos/","userphotos/medthumbs/", $source);
                list($width, $height) = getimagesize($source);
                $imgratio = $height / $width;
                $heightls = $height / 3.3;
                $widthls = $width / 3.3;
                
                echo'<div style="float:left;height:160px;max-width:180px;padding-left:1px;padding-bottom:1px;overflow:hidden;">
						<img style="height:160px;" src="https://photorankr.com/',$sourceThumb,'" width="',$widthls,'px" />
                    </div>';
            }
            
        echo'</div></div>';
    
    } //end view == ''
    
    elseif(!$searchword && $cat) {
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
	
	if($cat == 'bw') {
	      $cat = 'Black and White';
	}
        
        //Search Title
            echo'<div class="grid_18 bigText" style="position:relative;top:30px;"><a style="text-decoration:none;color:#333;" name="cat">',ucwords($cat),'</a></div>';
                     
    echo'<div class="grid_18" style="margin-left:-40px;">';
    echo'<div id="thepics" style="position:relative;top:50px;width:740px;">
    <div id="main">
    <ul id="tiles">';
    
        flush();
            
    for($iii=1; $iii < $numresults; $iii++) {
	$image = mysql_result($result, $iii-1, "source");
    $imageThumb=str_replace("userphotos/","userphotos/medthumbs/", $image);
	$views = mysql_result($result, $iii-1, "views");
    $caption = mysql_result($result, $iii-1, "caption");
    $caption = (strlen($caption) > 19) ? substr($caption,0,17). " &#8230;" : $caption;
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
    if($widthls < 205) {
    $heightls = $heightls * ($heightls/$widthls);
    $widthls = 240;
    }

		echo'
        <a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$id,'&v=n"><li class="fPic" id="',$views,'" style="list-style-type: none;width:240px;"><img style="min-width:240px;" src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />
            
        <div class="marketunderlay" style="float:right;position:relative;top:0px;width:240px;height:30px;">
            <div style="line-spacing:1.48;padding:5px;color:#4A4A4A;">
                <div style="float:left;">
                    <span style="font-size:15px;font-weight:500;">',$ranking,'</span>&nbsp;&nbsp;<span style="font-weight:500;font-size:13px;">',$caption,'</span>
                </div>
                <div style="float:right;">
                     <span style="font-weight:500;font-size:13px;"><img style="margin-top:-4px;padding:3px;width:12px;" src="graphics/tag.png" /> ',$price,'</span>
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
        itemWidth: 240 // Optional, the width of a grid item
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
   <div class="grid_6 push_6" style="padding-top:55px;padding-bottom:25px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;"><img style="width:50px;" src="graphics/LoadingGIF.gif" /></div>
   </div>';




echo'<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMorePics").show();
				$.ajax({
					url: "loadMoreMarketCats.php?lastPicture=" + $(".fPic:last").attr("id")+"&cat=',$cat,'"+"&views=',$views,'",
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
    
    if(!$searchword) {
    
        echo'
            <!--End Galleries-->
    </div>
                    
        <!--Quick Links to Categories-->
        <div class="grid_8 filled rounded shadow" style="margin-top:30px;margin-left:20px;">
             <div class="cartText">Categories</div>
             <div style="width:340px;padding-top:5px;">
             <ul id="list" style="float:left;width:160px;margin-left:20px;">
                <li><a href="?category=aerial#cat">Aerial</a></li>
                <li><a href="?category=animal#cat">Animal</a></li>
                <li><a href="?category=architecture#cat">Architecture</a></li>
                <li><a href="?category=automotive#cat">Automotive</a></li>
                <li><a href="?category=bw#cat">Black & White</a></li>
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
    </div>';
    
    }
   
   elseif($searchword) {
   
    //Search Title
        if($cat) {
            echo'<div class="grid_18 bigText" style="position:relative;top:20px;"><a style="text-decoration:none;color:#333;" name="cat">',ucwords($cat),'</a></div>';
        }
        elseif($searchword) {
            echo'<div class="grid_18 bigText" style="position:relative;top:20px;"><a style="text-decoration:none;color:#333;" name="search">',$numsearchresults2,' results for ',$searchword,'</a></div>';
        }
    
    echo'
    <div id="thepics" style="position:relative;left:-90px;top:85px;width:1210px;padding-bottom:20px;">
    <div id="main">
    <ul id="tiles">';
    
        flush();
            
    for($iii=1; $iii <= $numsearchresults; $iii++) {
	$image = mysql_result($resultrun, $iii-1, "source");
    $imageThumb=str_replace("userphotos/","userphotos/medthumbs/", $image);
	$id = mysql_result($resultrun, $iii-1, "id");
    $caption = mysql_result($resultrun, $iii-1, "caption");
    $caption = (strlen($caption) > 26) ? substr($caption,0,24). " &#8230;" : $caption;
    $points = mysql_result($resultrun, $iii-1, "points");
    $price = mysql_result($resultrun, $iii-1, "price");
    if($price != 'Not For Sale') {
        $price = '$' . $price;
    }
    elseif(!$price || $price == 'Not For Sale') {
        $price = 'NFS';
    }
    elseif($price == '.00') {
        $price = 'Free';
    }
    $votes = mysql_result($resultrun, $iii-1, "votes");
    $ranking = number_format($points/$votes,2);
    $score = number_format(($points/$votes),2);
    $owner = mysql_result($resultrun, $iii-1, "emailaddress");
    $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$owner'");
    $firstname = mysql_result($ownerquery, 0, "firstname");
    $lastname = mysql_result($ownerquery, 0, "lastname");
    $fullname = $firstname . " " . $lastname;
    $width = mysql_result($resultrun,$iii-1,'width');
    $height = mysql_result($resultrun,$iii-1,'height');
    $classification = mysql_result($resultrun,$iii-1,'classification');
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
        <a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$id,'&v=n"><li class="fPic" id="',$id,'" style="list-style-type: none;width:275px;"><img style="min-width:275px;" src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />
            
        <div class="marketunderlay" style="float:right;position:relative;top:0px;width:275px;height:30px;">
            <div style="line-spacing:1.48;padding:5px;color:#4A4A4A;">
                <div style="float:left;">
                    <span style="font-size:15px;font-weight:500;">',$ranking,'</span>&nbsp;&nbsp;<span style="font-weight:500;font-size:13px;">',$caption,'</span>
                </div>
                <div style="float:right;">
                     <span style="font-weight:500;font-size:13px;"><img style="margin-top:-4px;padding:3px;width:12px;" src="graphics/tag.png" /> ',$price,'</span>
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
					url: "loadMoreMarketPics.php?lastPicture=" + $(".fPic:last").attr("id")+"&category=',$cat,'"+"&views=',$views,'"+"&sw=',$searchword,'"+"&license=',$license,'"+"&maxPrice=',$maxprice,'"+"&minPrice=',$minprice,'"+"&minWidth=',$minwidth,'"+"&maxWidth=',$maxwidth,'"+"&minHeight=',$minheight,'"+"&maxHeight=',$maxheight,'"+"&minRep=',$minrep,'"+"&maxRep=',$maxrep,'"+"&quality=',$quality,'"+"&minRank=',$minrank,'"+"&maxRank=',$maxrank,'",
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

//add footer if no search performed
    if(!$searchword) {
        echo'</div><br /><br /><br />';
        footer();
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