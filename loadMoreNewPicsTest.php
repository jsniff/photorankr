<?php 

require("db_connection.php");

echo'<script type="text/javascript" src="js/jquery.wookmark.js"></script>';
    
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

echo'<div id="main" role="main">
    <ul id="tiles">';
    
for($iii=1; $iii <= $numpics; $iii++) {
	$image = mysql_result($mysqlquery, $iii-1, "source");
	$imageThumb = str_replace("userphotos/","userphotos/medthumbs/", $image);
	$id = mysql_result($mysqlquery, $iii-1, "id");
    $caption = mysql_result($mysqlquery, $iii-1, "caption");
    $caption = (strlen($caption) > 28) ? substr($caption,0,25). " &#8230;" : $caption;
    $price = mysql_result($mysqlquery, $iii-1, "price");
    if($price != 'Not For Sale') {
        $price = '$' . $price;
    }
    elseif($price == 'Not For Sale') {
        $price = 'NFS';
    }
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
    $heightls = $height / 4.3;
    $widthls = $width / 4.3;
    if($widthls < 225) {
    $heightls = $heightls * ($heightls/$widthls);
    $widthls = 270;
    }

		echo '
        <a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$id,'&v=n"><li class="fPic" id="',$id,'" style="padding:5px;margin-right:10px;margin-top:10px;list-style-type: none;width:270px;
"><img src="http://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" /><div style="padding:3px;"><div style="float:left;">',$caption,'</div><div style=float:right;font-size:13px;font-weight:500;">',$price,'</div></div></li></a>';

  
	    
      } //end for loop
      
echo'
</ul>';

}//end if clause

?>

<!-- Once the page is loaded, initalize the plug-in. -->
  <script type="text/javascript">
    $(document).ready(new function() {
      // Prepare layout options.
      var options = {
        autoResize: true, // This will auto-update the layout when the browser window is resized.
        container: $('#main'), // Optional, used for some extra CSS styling
        offset: 4, // Optional, the distance between grid items
        itemWidth: 290 // Optional, the width of a grid item
      };
      
      // Get a reference to your grid items.
      var handler = $('#tiles li');
      
      // Call the layout function.
      handler.wookmark(options);
      
    });
  </script>
  
  </div>