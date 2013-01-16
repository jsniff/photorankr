<?php 

require("db_connection.php");
$emailaddress = htmlentities($_GET['emailaddress']);
$option = htmlentities($_GET['option']);
$view = htmlentities($_GET['view']);
$searchword = htmlentities($_GET['searchword']);

echo'<script type="text/javascript" src="js/jquery.wookmark.js"></script>';
	
if($_GET['lastPicture']) {
    if($option == 'sold') {
        $query = "SELECT * FROM photos WHERE id < ".$_GET['lastPicture']." AND emailaddress='$emailaddress' AND sold = 1 ORDER BY id DESC LIMIT 0, 9";
    }
    elseif($option == 'top') {
        $query = "SELECT * FROM photos WHERE (points/votes) < ".$_GET['lastPicture']." AND emailaddress='$emailaddress' ORDER BY (points/votes) DESC LIMIT 0, 9";
    }
    elseif($option == 'fave' || $option == 'faved') {
        $query = "SELECT * FROM photos WHERE faves < ".$_GET['lastPicture']." AND emailaddress='$emailaddress' ORDER BY faves DESC LIMIT 0, 9";
    }
    elseif($option == 'free') {
        $query = "SELECT * FROM photos WHERE id < ".$_GET['lastPicture']." AND price < .1 AND emailaddress='$emailaddress' ORDER BY id DESC LIMIT 0, 9";
    }
    elseif($searchword) {
         $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$emailaddress' AND concat(tag1,tag,2,tag3,tag4,singlestyletags,singlecategorytags,caption) LIKE '%$searchword%' ORDER BY id DESC LIMIT 0,16");
    }
    else {
	$query = "SELECT * FROM photos WHERE id < ".$_GET['lastPicture']." AND emailaddress='$emailaddress' ORDER BY id DESC LIMIT 0, 9";
    }
	$mysqlquery = mysql_query($query) or die(mysql_error());
	$numphotos = mysql_num_rows($mysqlquery);
	//DISPLAY 20 NEWEST OF ALL PHOTOS

    echo'
    <div id="main" role="main">
    <ul id="tiles">';
    
	for($iii=0; $iii < 9 && $iii < $numphotos; $iii++) {
    $image = mysql_result($mysqlquery, $iii, "source");
    $imageThumb = str_replace("userphotos/","userphotos/medthumbs/", $image);
    $imageThumb = str_replace(".JPG",".jpg", $imageThumb);
	$id = mysql_result($mysqlquery, $iii, "id");
    $price = mysql_result($mysqlquery, $iii, "price");
    $caption = mysql_result($mysqlquery, $iii, "caption");
    $caption = (strlen($caption) > 25) ? substr($caption,0,23). "&#8230;" : $caption;
    $points = mysql_result($mysqlquery, $iii, "points");
    $votes = mysql_result($mysqlquery, $iii, "votes");
    $faves = mysql_result($mysqlquery, $iii, "faves");
    $score = number_format(($points/$votes),2);
	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
    $heightls = $height / 3.2;
    $widthls = $width / 3.2;
    
    if($widthls < 195) {
        $heightls = $heightls * ($heightls/$widthls);
        $widthls = 250;
    }

   if($view == 'store') {
            
            if($option == '' || $option == 'sold') {
            echo'<a style="text-decoration:none;color:#333;" href="fullsizeview.php?imageid=',$id,'&v=n"><li class="fPic" id="',$id,'" style="list-style-type: none;width:280px;"><img style="min-width:280px;" onmousedown="return false" oncontextmenu="return false;"  src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />
            
            <div class="statoverlay" style="z-index:1;background-color:white;position:relative;top:0px;width:280px;height:30px;"><div style="line-spacing:1.48;padding:5px;color:#4A4A4A;"><div style="float:left;"><img style="box-shadow:none;width:15px;" src="graphics/tag.png" /><span style="font-size:16px;font-weight:bold;"> $',$price,'</span>&nbsp;&nbsp;<span style="font-weight:bold;font-size:12px;">',$caption,'</span></div></div><br/></div>'; 
            }
            
            elseif($option == 'faved') {
            echo'<a style="text-decoration:none;color:#333;" href="fullsizeview.php?imageid=',$id,'&v=n"><li class="fPic" id="',$faves,'" style="list-style-type: none;width:280px;"><img style="min-width:280px;" onmousedown="return false" oncontextmenu="return false;"  src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />
            
            <div class="statoverlay" style="z-index:1;background-color:white;position:relative;top:0px;width:280px;height:30px;"><div style="line-spacing:1.48;padding:5px;color:#4A4A4A;"><div style="float:left;"><img style="box-shadow:none;width:15px;" src="graphics/tag.png" /><span style="font-size:16px;font-weight:bold;"> $',$price,'</span>&nbsp;&nbsp;<span style="font-weight:bold;font-size:12px;">',$caption,'</span><img style="margin-left:10px;box-shadow:none;width:13px;" src="graphics/heart.png" /> ',$faves,'</div></div><br/></div>'; 
            }
            
            elseif($option == 'top') {
            echo'<a style="text-decoration:none;color:#333;" href="fullsizeview.php?imageid=',$id,'&v=n"><li class="fPic" id="',$score,'" style="list-style-type: none;width:280px;"><img style="min-width:280px;" onmousedown="return false" oncontextmenu="return false;"  src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />
            
            <div class="statoverlay" style="z-index:1;background-color:white;position:relative;top:0px;width:280px;height:30px;"><div style="line-spacing:1.48;padding:5px;color:#4A4A4A;"><div style="float:left;"><img style="box-shadow:none;width:15px;" src="graphics/tag.png" /><span style="font-size:16px;font-weight:bold;"> $',$price,'</span>&nbsp;&nbsp;<span style="font-weight:bold;font-size:12px;">',$caption,'</span>&nbsp;&nbsp;&nbsp;&nbsp; ',$score,'</div></div><br/></div>'; 
            }
            
        }

        if($option == 'fave') {
            echo'<a style="text-decoration:none;color:#333;" href="fullsizeview.php?imageid=',$id,'&v=n"><li class="fPic" id="',$faves,'" style="list-style-type: none;width:280px;"><img style="min-width:280px;" onmousedown="return false" oncontextmenu="return false;"  src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />
            
            <div class="statoverlay" style="z-index:1;background-color:white;position:relative;top:0px;width:280px;height:30px;"><div style="line-spacing:1.48;padding:5px;color:#4A4A4A;"><div style="float:left;"><img style="box-shadow:none;width:15px;" src="graphics/heart.png" /><span style="font-size:16px;font-weight:bold;"> ',$faves,'</span>&nbsp;&nbsp;<span style="font-weight:bold;font-size:12px;">',$caption,'</span></div></div><br/></div>'; 
        }
        
        elseif($option == 'top' && $view != 'store') {
           echo'<a style="text-decoration:none;color:#333;" href="fullsizeview.php?imageid=',$id,'&v=n"><li class="fPic" id="',$score,'" style="list-style-type: none;width:280px;"><img style="min-width:280px;" onmousedown="return false" oncontextmenu="return false;"  src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />
             
             <div class="statoverlay" style="z-index:1;background-color:white;position:relative;top:0px;width:280px;height:30px;"><div style="line-spacing:1.48;padding:5px;color:#4A4A4A;"><div style="float:left;"<span style="font-size:16px;font-weight:bold;">',$score,'</span>&nbsp;&nbsp;<span style="font-weight:bold;font-size:12px;">',$caption,'</span></div></div><br/></div>';  
        }
        
        elseif($option == '' && $view != 'store') {
             echo'<a style="text-decoration:none;color:#333;" href="fullsizeview.php?imageid=',$id,'&v=n"><li class="fPic" id="',$id,'" style="list-style-type: none;width:280px;"><img style="min-width:280px;" onmousedown="return false" oncontextmenu="return false;"  src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />
             
             <div class="statoverlay" style="z-index:1;background-color:white;position:relative;top:0px;width:280px;height:30px;"><div style="line-spacing:1.48;padding:5px;color:#4A4A4A;"><div style="float:left;"<span style="font-size:16px;font-weight:bold;">',$score,'</span>&nbsp;&nbsp;<span style="font-weight:bold;font-size:12px;">',$caption,'</span></div></div><br/></div>';
        }
            	    
      } //end for loop
      
      echo'</ul>';
      
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

  
      
<?php
      	
	echo '</div>';

}//end if clause

?>