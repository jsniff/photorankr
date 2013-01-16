<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.wookmark.js"></script>          
    
<?php 

require "db_connection.php";
require "functions.php";


if($_GET['lastPicture']) {
    
    //Search Queries
    $searchword = htmlentities($_GET['sw']);
    $category = mysql_real_escape_string(htmlentities($_GET['category']));

    $license = mysql_real_escape_string(htmlentities($_GET['license']));
    $maxprice = mysql_real_escape_string(htmlentities($_GET['maxPrice']));
    $minprice = mysql_real_escape_string(htmlentities($_GET['minPrice']));
    if(!$minprice) {
        $minprice = 0;
    }
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
    $result = "SELECT * FROM photos WHERE id < ".$_GET['lastPicture']." AND 
    caption LIKE '%$searchword%'";
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

    $result .= " ORDER BY id DESC LIMIT 0,9";
	$result = mysql_query($result) or die(mysql_error());
    $numpics = mysql_num_rows($result);

if(htmlentities($_GET['cat'])) {
    $cat = htmlentities($_GET['cat']);
    
    if($cat == 'aerial') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%Aerial%' AND views < ".$_GET['lastPicture']." ORDER BY views DESC LIMIT 0, 9";
    }
    
    elseif($cat == 'animal') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%Animal%' AND views < ".$_GET['lastPicture']." ORDER BY views DESC LIMIT 0, 9";
    }
    
    elseif($cat == 'architecture') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%Architecture%' AND views < ".$_GET['lastPicture']." ORDER BY views DESC LIMIT 0, 9";
    }
    
    elseif($cat == 'astro') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%Astro%' AND views < ".$_GET['lastPicture']." ORDER BY views DESC LIMIT 0, 9";
    }
    
    elseif($cat == 'automotive') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%Automotive%' AND views < ".$_GET['lastPicture']." ORDER BY views DESC LIMIT 0, 9";
    }
    
    elseif($cat == 'bw') {
        $query = "SELECT * FROM photos WHERE singlestyletags LIKE '%B&W%' AND views < ".$_GET['lastPicture']." ORDER BY views DESC LIMIT 0, 9";
    }
    
    elseif($cat == 'cityscape') {
        $query = "SELECT * FROM photos WHERE singlestyletags LIKE '%cityscape%' AND views < ".$_GET['lastPicture']." ORDER BY views DESC LIMIT 0, 9";
    }
    
     elseif($cat == 'fashion') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%Fashion%' AND views < ".$_GET['lastPicture']." ORDER BY views DESC LIMIT 0, 9";
    }
    
     elseif($cat == 'fineart') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%Fine Art%' AND views < ".$_GET['lastPicture']." ORDER BY views DESC LIMIT 0, 9";
    }
    
    elseif($cat == 'fisheye') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%Fisheye%' AND views < ".$_GET['lastPicture']." ORDER BY views DESC LIMIT 0, 9";
    }
    
    elseif($cat == 'food') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%Food%' AND views < ".$_GET['lastPicture']." ORDER BY views DESC LIMIT 0, 9";
    }
    
    elseif($cat == 'HDR') {
        $query = "SELECT * FROM photos WHERE singlestyletags LIKE '%HDR%' AND views < ".$_GET['lastPicture']." ORDER BY views DESC LIMIT 0, 9";
    }
    
    elseif($cat == 'historical') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%Historical%' AND views < ".$_GET['lastPicture']." ORDER BY views DESC LIMIT 0, 9";
    }
    
    elseif($cat == 'industrial') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%Industrial%' AND views < ".$_GET['lastPicture']." ORDER BY views DESC LIMIT 0, 9";
    }
    
    elseif($cat == 'landscape') {
        $query = "SELECT * FROM photos WHERE singlestyletags LIKE '%Landscape%' AND views < ".$_GET['lastPicture']." ORDER BY views DESC LIMIT 0, 9";
    }
    
    elseif($cat == 'longexposure') {
        $query = "SELECT * FROM photos WHERE singlestyletags LIKE '%Long Exposure%' AND views < ".$_GET['lastPicture']." ORDER BY views DESC LIMIT 0, 9";
    }
    
    elseif($cat == 'macro') {
        $query = "SELECT * FROM photos WHERE singlestyletags LIKE '%Macro%' AND views < ".$_GET['lastPicture']." ORDER BY views DESC LIMIT 0, 9";
    }
    
    elseif($cat == 'monochrome') {
        $query = "SELECT * FROM photos WHERE singlestyletags LIKE '%Monochrome%' AND views < ".$_GET['lastPicture']." ORDER BY views DESC LIMIT 0, 9";
    }
    
    elseif($cat == 'nature') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%Nature%' AND views < ".$_GET['lastPicture']." ORDER BY views DESC LIMIT 0, 9";
    }
    
    elseif($cat == 'night') {
        $query = "SELECT * FROM photos WHERE singlestyletags LIKE '%Night%' AND views < ".$_GET['lastPicture']." ORDER BY views DESC LIMIT 0, 9";
    }
    
    elseif($cat == 'panorama') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%Panorama%' AND views < ".$_GET['lastPicture']." ORDER BY views DESC LIMIT 0, 9";
    }
    
    elseif($cat == 'people') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%People%' AND views < ".$_GET['lastPicture']." ORDER BY views DESC LIMIT 0, 9";
    }
    
    elseif($cat == 'scenic') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%Scenic%' AND views < ".$_GET['lastPicture']." ORDER BY views DESC LIMIT 0, 9";
    }
    
    elseif($cat == 'sports') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%Sports%' AND views < ".$_GET['lastPicture']." ORDER BY views DESC LIMIT 0, 9";
    }
    
    elseif($cat == 'stilllife') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%Still Life%' AND views < ".$_GET['lastPicture']." ORDER BY views DESC LIMIT 0, 9";
    }
    
    elseif($cat == 'transportation') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%Transportation%' AND views < ".$_GET['lastPicture']." ORDER BY views DESC LIMIT 0, 9";
    }
    
    elseif($cat == 'war') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%War%' AND views < ".$_GET['lastPicture']." ORDER BY views DESC LIMIT 0, 9";
    }
    
    $result = mysql_query($query) or die(mysql_error());
    $numpics = mysql_num_rows($result);

} //end if category
        
//DISPLAY 20 NEWEST OF ALL PHOTOS
        
echo'<div id="main">
    <ul id="tiles">';
            
    for($iii=1; $iii < $numpics && $iii < 9; $iii++) {
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
        <a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$id,'&v=n"><li class="fPic" id="',$id,'" style="list-style-type: none;width:275px;"><img style="min-width:275px;" onmousedown="return false" oncontextmenu="return false;"  src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />
            
        <div class="marketunderlay" style="float:right;position:relative;top:0px;width:275px;height:30px;">
            <div style="line-spacing:1.48;padding:5px;color:#4A4A4A;">
                <div style="float:left;">
                    <span style="font-size:15px;font-weight:500;">',$ranking,'</span>&nbsp;&nbsp;<span style="font-weight:500;font-size:15px;">',$caption,'</span>
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
  
<?php

    echo'</div>';

}//end if clause

?>
