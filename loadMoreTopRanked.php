<?php 

require("db_connection.php");

echo'<script type="text/javascript" src="js/jquery.wookmark.js"></script>';

if($_GET['lastPicture']) {
	$query = "SELECT * FROM photos WHERE points < ".$_GET['lastPicture']." ORDER BY points DESC LIMIT 8";
	$mysqlquery = mysql_query($query) or die(mysql_error());


//DISPLAY 20 NEWEST OF ALL PHOTOS


echo'<div id="main" role="main">
    <ul id="tiles">';
    
for($iii=1; $iii <= 8; $iii++) {
	$image = mysql_result($mysqlquery, $iii-1, "source");
	$imageThumb = str_replace("userphotos/","userphotos/medthumbs/", $image);
    $points = mysql_result($result, $iii-1, "points");
    $caption = mysql_result($mysqlquery, $iii-1, "caption");
    $caption = (strlen($caption) > 28) ? substr($caption,0,25). " &#8230;" : $caption;
    $points = mysql_result($mysqlquery, $iii-1, "points");
    $id = mysql_result($mysqlquery, $iii-1, "id");
    $votes = mysql_result($mysqlquery, $iii-1, "votes");
    $score = number_format(($points/$votes),2);
    $price = mysql_result($mysqlquery, $iii-1, "price");
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
        <a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$id,'&v=r"><li class="fPic" id="',$points,'" style="padding:5px;margin-right:10px;margin-top:10px;list-style-type: none;width:270px;
"><img src="http://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" /><div style="padding:3px;"><div style="float:left;">',$caption,'</div><div style="float:right;font-size:13px;font-weight:500;">$',$price,'</div></div></li></a>';   
  
	    
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
