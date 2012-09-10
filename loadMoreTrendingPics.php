<?php 

require("db_connection.php");

if($_GET['lastPicture']) {
	$lastquery = "SELECT * FROM photos WHERE id=".$_GET['lastPicture']." LIMIT 1";
	$lastresult = mysql_query($lastquery) or die(mysql_error());
	$lastphotoscore = mysql_result($lastresult, 0, "score");

	$query = "SELECT * FROM photos WHERE score<'".$lastphotoscore."' ORDER BY score DESC LIMIT 0,8";
	$mysqlquery = mysql_query($query) or die(mysql_error());

	//DISPLAY 20 NEWEST OF ALL PHOTOS

echo'<div id="main" role="main">
    <ul id="tiles">';
 
	for($iii=1; $iii <= 8; $iii++) {
		$image = mysql_result($mysqlquery, $iii-1, "source");
		$imageThumb = str_replace("userphotos/","userphotos/medthumbs/", $image);
		$id = mysql_result($mysqlquery, $iii-1, "id");
	    	$caption = mysql_result($mysqlquery, $iii-1, "caption");
             $caption = (strlen($caption) > 23) ? substr($caption,0,20). " &#8230;" : $caption;
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
        <a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$id,'&v=t"><li class="fPic" id="',$id,'" style="padding:5px;margin-right:10px;margin-top:10px;list-style-type: none;width:270px;
"><img src="http://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" /><p><span style="font-size:16px;">',$score,'</span>/10&nbsp;&nbsp;',$caption,'</p></li></a>'; 
  
	    
      	}  //end for loop
      
      
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
