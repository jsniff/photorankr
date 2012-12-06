<?php 

require("db_connection.php");
require "functions.php";


if($_GET['lastPicture']) {
    
        $cat = htmlentities($_GET['c']);
        $searchword = htmlentities($_GET['sw']);
        
    if($searchword) {
        $query = "SELECT * FROM photos WHERE concat(caption,location,tag1,tag2,tag3,tag4,singlecategorytags,singlestyletags) LIKE '%$searchword%' AND views < ".$_GET['lastPicture']." ORDER BY views DESC LIMIT 0, 9";
    }

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

//DISPLAY 20 NEWEST OF ALL PHOTOS
        
        echo'<div id="thepics" style="width:740px;">
             <div id="main">';
            
            for($iii = 0; $iii < 9; $iii++) {
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
             </div>';
             
}//end if clause

?>
