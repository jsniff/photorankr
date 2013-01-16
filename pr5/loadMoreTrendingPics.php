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
"><img style="-moz-border-radius: 3px;-webkit-border-radius: 3px;border-radius: 3px;" src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />

<div class="statoverlay" style="z-index:1;background-color:white;position:relative;top:0px;width:270px;height:30px;"><div style="line-spacing:1.48;padding:5px;color:#4A4A4A;"><div style="float:left;"<span style="font-size:16px;font-weight:bold;">',$score,'</span>&nbsp;&nbsp;<span style="font-weight:bold;font-size:12px;">',$caption,'</span></div><div style="float:right;"><span style="font-size:12px;">',$price,'</span></div></div><br/></div>
';

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
        offset: 10, // Optional, the distance between grid items
        itemWidth: 280 // Optional, the width of a grid item
      };
      
      // Get a reference to your grid items.
      var handler = $('#tiles li');
      
      // Call the layout function.
      handler.wookmark(options);
      
    });
  </script>
  
  </div>
