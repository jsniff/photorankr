<?php 

require("db_connection.php");
    
if($_GET['lastPicture']) {
    
        $cat = htmlentities($_GET['c']);

    if($cat == '') {
        $query = "SELECT * FROM photos WHERE id < ".$_GET['lastPicture']." ORDER BY id DESC LIMIT 0, 8";
    }
    
     elseif($cat == 'aerial') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%Aerial%' AND id < ".$_GET['lastPicture']." ORDER BY id DESC LIMIT 0, 8";
    }
    
    elseif($cat == 'animal') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%Animal%' AND id < ".$_GET['lastPicture']." ORDER BY id DESC LIMIT 0, 8";
    }
    
    elseif($cat == 'architecture') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%Architecture%' AND id < ".$_GET['lastPicture']." ORDER BY id DESC LIMIT 0, 8";
    }
    
    elseif($cat == 'astro') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%Astro%' AND id < ".$_GET['lastPicture']." ORDER BY id DESC LIMIT 0, 8";
    }
    
    elseif($cat == 'automotive') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%Automotive%' AND id < ".$_GET['lastPicture']." ORDER BY id DESC LIMIT 0, 8";
    }
    
    elseif($cat == 'bw') {
        $query = "SELECT * FROM photos WHERE singlestyletags LIKE '%B&W%' AND id < ".$_GET['lastPicture']." ORDER BY id DESC LIMIT 0, 8";
    }
    
    elseif($cat == 'cityscape') {
        $query = "SELECT * FROM photos WHERE singlestyletags LIKE '%cityscape%' AND id < ".$_GET['lastPicture']." ORDER BY id DESC LIMIT 0, 8";
    }
    
     elseif($cat == 'fashion') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%Fashion%' AND id < ".$_GET['lastPicture']." ORDER BY id DESC LIMIT 0, 8";
    }
    
     elseif($cat == 'fineart') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%Fine Art%' AND id < ".$_GET['lastPicture']." ORDER BY id DESC LIMIT 0, 8";
    }
    
    elseif($cat == 'fisheye') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%Fisheye%' AND id < ".$_GET['lastPicture']." ORDER BY id DESC LIMIT 0, 8";
    }
    
    elseif($cat == 'food') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%Food%' AND id < ".$_GET['lastPicture']." ORDER BY id DESC LIMIT 0, 8";
    }
    
    elseif($cat == 'HDR') {
        $query = "SELECT * FROM photos WHERE singlestyletags LIKE '%HDR%' AND id < ".$_GET['lastPicture']." ORDER BY id DESC LIMIT 0, 8";
    }
    
    elseif($cat == 'historical') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%Historical%' AND id < ".$_GET['lastPicture']." ORDER BY id DESC LIMIT 0, 8";
    }
    
    elseif($cat == 'industrial') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%Industrial%' AND id < ".$_GET['lastPicture']." ORDER BY id DESC LIMIT 0, 8";
    }
    
    elseif($cat == 'landscape') {
        $query = "SELECT * FROM photos WHERE singlestyletags LIKE '%Landscape%' AND id < ".$_GET['lastPicture']." ORDER BY id DESC LIMIT 0, 8";
    }
    
    elseif($cat == 'longexposure') {
        $query = "SELECT * FROM photos WHERE singlestyletags LIKE '%Long Exposure%' AND id < ".$_GET['lastPicture']." ORDER BY id DESC LIMIT 0, 8";
    }
    
    elseif($cat == 'macro') {
        $query = "SELECT * FROM photos WHERE singlestyletags LIKE '%Macro%' AND id < ".$_GET['lastPicture']." ORDER BY id DESC LIMIT 0, 8";
    }
    
    elseif($cat == 'monochrome') {
        $query = "SELECT * FROM photos WHERE singlestyletags LIKE '%Monochrome%' AND id < ".$_GET['lastPicture']." ORDER BY id DESC LIMIT 0, 8";
    }
    
    elseif($cat == 'nature') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%Nature%' AND id < ".$_GET['lastPicture']." ORDER BY id DESC LIMIT 0, 8";
    }
    
    elseif($cat == 'night') {
        $query = "SELECT * FROM photos WHERE singlestyletags LIKE '%Night%' AND id < ".$_GET['lastPicture']." ORDER BY id DESC LIMIT 0, 8";
    }
    
    elseif($cat == 'panorama') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%Panorama%' AND id < ".$_GET['lastPicture']." ORDER BY id DESC LIMIT 0, 8";
    }
    
    elseif($cat == 'people') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%People%' AND id < ".$_GET['lastPicture']." ORDER BY id DESC LIMIT 0, 8";
    }
    
    elseif($cat == 'scenic') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%Scenic%' AND id < ".$_GET['lastPicture']." ORDER BY id DESC LIMIT 0, 8";
    }
    
    elseif($cat == 'sports') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%Sports%' AND id < ".$_GET['lastPicture']." ORDER BY id DESC LIMIT 0, 8";
    }
    
    elseif($cat == 'stilllife') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%Still Life%' AND id < ".$_GET['lastPicture']." ORDER BY id DESC LIMIT 0, 8";
    }
    
    elseif($cat == 'transportation') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%Transportation%' AND id < ".$_GET['lastPicture']." ORDER BY id DESC LIMIT 0, 8";
    }
    
    elseif($cat == 'war') {
        $query = "SELECT * FROM photos WHERE singlecategorytags LIKE '%War%' AND id < ".$_GET['lastPicture']." ORDER BY id DESC LIMIT 0, 8";
    }

	$mysqlquery = mysql_query($query) or die(mysql_error());
    $numpics = mysql_num_rows($mysqlquery);

//DISPLAY 20 NEWEST OF ALL PHOTOS

echo'<div id="container" style="width:1210px;margin-left:-112px; top:15px;">';
for($iii=1; $iii <= $numpics; $iii++) {
	$image = mysql_result($mysqlquery, $iii-1, "source");
	$imageThumb = str_replace("userphotos/","userphotos/medthumbs/", $image);
	$id = mysql_result($mysqlquery, $iii-1, "id");
    $caption = mysql_result($mysqlquery, $iii-1, "caption");
    $caption = (strlen($caption) > 28) ? substr($caption,0,25). " &#8230;" : $caption;
    $points = mysql_result($mysqlquery, $iii-1, "points");
    $votes = mysql_result($mysqlquery, $iii-1, "votes");
    $score = number_format(($points/$votes),2);
    $owner = mysql_result($mysqlquery, $iii-1, "emailaddress");
    $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$owner'");
    $firstname = mysql_result($ownerquery, 0, "firstname");
    $lastname = mysql_result($ownerquery, 0, "lastname");
    $fullname = $firstname . " " . $lastname;

	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
    $heightls = $height / 2.5;
    $widthls = $width / 2.5;


     	echo '<div class="fPic" id="',$id,'" style="float:left;margin-right:20px;margin-top:20px;width:280px;height:280px;overflow:hidden;"><a href="http://photorankr.com/fullsize.php?image=',$image,'&v=n">
        
        <div class="statoverlay" style="z-index:1;left:0px;top:240px;position:relative;background-color:black;width:280px;height:40px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-weight:100;font-size:20px;">',$caption,'</span><br/></div>
        
        <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-75px;min-height:300px;min-width:280px;" src="http://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';       
  
	    
      } //end for loop
      
echo'</div>';

}//end if clause

?>
