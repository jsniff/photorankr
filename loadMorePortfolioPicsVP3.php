<?php 

require("db_connection.php");

echo'<script type="text/javascript" src="js/jquery.wookmark.js"></script>';
	
if($_GET['lastPicture']) {
	$lastpicID = mysql_real_escape_string($_GET['lastPicture']);
	$query = mysql_query("SELECT emailaddress FROM photos WHERE id='$lastpicID' LIMIT 0, 1");
	$emailaddress = mysql_result($query, 0, "emailaddress");
	
	$query = "SELECT * FROM photos WHERE id <'$lastpicID' AND emailaddress='$emailaddress' ORDER BY id DESC LIMIT 0, 9";
	$mysqlquery = mysql_query($query) or die(mysql_error());
	$numphotos = mysql_num_rows($mysqlquery);

	//DISPLAY 20 NEWEST OF ALL PHOTOS

    echo'
    <div id="main" role="main">
    <ul id="tiles">';
    
	for($iii=0; $iii < 9 && $iii < $numphotos; $iii++) {
		$image[$iii] = mysql_result($mysqlquery, $iii, "source");
    $imageThumb[$iii] = str_replace("userphotos/","userphotos/medthumbs/", $image[$iii]);
    $imageThumb[$iii] = str_replace(".JPG",".jpg", $imageThumb[$iii]);
	$id = mysql_result($mysqlquery, $iii, "id");
    $price = mysql_result($mysqlquery, $iii, "price");
                if($price != 'Not For Sale') {
                    $price = '$' . $price;
                }
                elseif($price == 'Not For Sale') {
                    $price = 'NFS';
                }
                elseif($price == '.00' || $price == '') {
                    $price = 'Free';
                }
    $caption = mysql_result($mysqlquery, $iii, "caption");
    $points = mysql_result($mysqlquery, $iii, "points");
    $votes = mysql_result($mysqlquery, $iii, "votes");
    $faves = mysql_result($mysqlquery, $iii, "faves");
    $score = number_format(($points/$votes),2);
	list($width, $height) = getimagesize($image[$iii]);
	$imgratio = $height / $width;
    $heightls = $height / 3.5;
    $widthls = $width / 3.5;
    
    if($widthls < 240) {
        $heightls = $heightls * ($heightls/$widthls);
        $widthls = 250;
    }

echo'

<a style="text-decoration:none;color:#000;" href="fullsizeview.php?imageid=',$id,'"><li class="fPic photobox" id="',$id,'" style="padding:5px;margin-right:10px;margin-top:10px;list-style-type: none;width:240px;
"><img onmousedown="return false" oncontextmenu="return false;" src="http://photorankr.com/',$imageThumb[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /><div style="padding:3px;"><div style="float:left;">',$caption,'</div><div style=float:right;font-size:13px;font-weight:500;">',$price,'</div><br /><i class="icon-heart"></i>&nbsp;',$faves,' favorites</div></li></a>';
	    
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
        offset: 4, // Optional, the distance between grid items
        itemWidth: 250 // Optional, the width of a grid item
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