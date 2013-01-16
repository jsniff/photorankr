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
    $topranked = mysql_query("SELECT * FROM photos WHERE (points/votes) > 8.5 and votes > 5 and time > ($currenttime - 430000) ORDER BY time DESC LIMIT 24");
    $numphotosquery = mysql_query("SELECT * FROM photos");
    $numphotos = number_format(mysql_num_rows($numphotosquery),2);
  
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
                    $source = mysql_result($featuredPhotos,$iii,'source');
                    $source = str_replace("userphotos/","userphotos/medthumbs/",$source);
                    
                    echo'<div class="featuredImgContainer">
                            <img style="width:140px;height:140px;" src="https://photorankr.com/',$source,'" />
                         </div>';
				}
                $bigsource = mysql_result($featuredPhotos,5,'source');
                $bigcaption = mysql_result($featuredPhotos,5,'caption');
                $bigprice = mysql_result($featuredPhotos,5,'price');
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
			
            <div id="bigImg"> 
				<img src="https://photorankr.com/<?php echo $bigsource; ?>"/>
			</div>
            
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
    <div style="width:720px;overflow:hidden;margin-left:-28px;float:left;">
    
        <div class="marketSearch">
            <form action="" method="get">
                <input id="searchBar" type="text" name="term" placeholder="Search by keywords or tags&hellip;" />
                <div id="gbqfbw">
                    <button id="gbqfb" class="gbqfb" aria-label="PR Search">
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
        <div class="searchTools" id="searchTools"> Search Tools</div>
        
        <!-----Search Options------>
        <div class="leftMarketDrop" id="searchToolsDiv">
            <form action="#" method="get">
            
            <div class="leftIntDrop">
                <header>Price</header>
                <div>
                    Min <input type="text" name="minPrice" />  Max <input type="text" name="maxPrice" />
                </div>
                <header>Photo Rank</header>
                <div>
                    Min <input type="text" name="minRank" />  Max <input type="text" name="maxRank" />
                </div>
            </div>
            
            <div class="leftIntDrop">
                <header>Width</header>
                <div>
                    Min <input type="text" name="minWidth" />  Max <input type="text" name="maxWidth" />
                </div>
                <header>Height</header>
                <div>
                    Min <input type="text" name="minHeight" />  Max <input type="text" name="maxHeight" />
                </div>
            </div>
            
            <div class="leftIntDrop" style="border-right:none;">
                <header>Photographer Rep</header>
                <div>
                    Min <input type="text" name="minRep" />  Max <input type="text" name="maxRep" />
                </div>
                <header>Quality</header>
                <div>
                    <input style="width:15px;height:20px;" type="radio" name="quality" /> Regular <input style="width:15px;height:20px;" type="radio" name="quality" > Premium
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
                <li id="myCart"> <img src="graphics/cart_b.png" /> Cart </li>
                <li id="myWishList"> <img src="graphics/star.png" /> Wishlist </li>
                <li id="myPurchases" style="border:none;width:115px;"> <img src="graphics/bag.png" /> Purchases </li>
            </ul>
        </div>
    
        <div class="rightMarketDrop" id="myCartDiv">cart</div>
        
        <div class="rightMarketDrop" id="myWishListDiv">wish list</div>

        <div class="rightMarketDrop" id="myPurchasesDiv">purchases</div>

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
                $source = mysql_result($topquery,$iii,'source');
                $sourceThumb = str_replace("userphotos/","userphotos/medthumbs/", $source);
                $source = "../" . $source;
                list($width, $height) = getimagesize($source);
                $imgratio = $height / $width;
                $heightls = $height / 3.3;
                $widthls = $width / 3.3;
                
                echo'<div style="float:left;height:160px;max-width:180px;padding-left:1px;padding-bottom:1px;overflow:hidden;">
						<img style="height:160px;" src="https://photorankr.com/',$sourceThumb,'" width="',$widthls,'px" />
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
						<img style="height:160px;" src="https://photorankr.com/',$sourceThumb,'" width="',$widthls,'px" />
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
    


        $numsearchquery = mysql_query("SELECT * FROM photos WHERE concat(caption,location,tag1,tag2,tag3,tag4,singlecategorytags,singlestyletags) LIKE '%$searchword%'");
        $numsearchresults = mysql_num_rows($numsearchquery);
        $result = "SELECT * FROM photos WHERE concat(caption,location,tag1,tag2,tag3,tag4,singlecategorytags,singlestyletags) LIKE '%$searchword%'";
}
//add in ranking functionality 


$minrank = $_GET['minRank'];
$maxrank = $_GET['maxRank'];


echo $minrank;
echo $maxrank;


// if($minrank) {

//     $result .= " AND (points/votes) > $minrank";

// }


// if($maxrank) {
//     $result .= " AND (points/votes) < $maxrank";

// }

// $result.= "ORDER BY views DESC LIMIT 16";
// $finalresult = mysql_result($result);

}






// $pricemax = $_GET['minPrice'];
// $pricemin = $_GET['maxPrice'];

// $maxphotoreput = $_GET['maxRep'];
// $minphotoreput = $_GET['minRep'];




//  $price = mysql_result($result,$iii,'price');



// if($pricemax || $pricemin) {

//     $result .= " AND $price > $minrank";
//     $result .= " AND $price < $maxrank";

// }


// if($maxphotoreput || $minphotorep) {

//     $result .= " AND (points/votes) > $minrank";
//     $result .= " AND (points/votes) < $maxrank";

// }

 
//         if(!empty($higherrep)) {
//                 $result .= " AND userinfo.reputation < $higherrep";
//                 }
                
//                 if(!empty($lowerrep)) {
//                 $result.= " AND userinfo.reputation > $lowerrep";
//                 }


        $numresults = mysql_num_rows($finalresult);
        
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