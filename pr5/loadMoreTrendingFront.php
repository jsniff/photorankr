<?php 

require("db_connection.php");

echo'<script type="text/javascript" src="js/jquery.wookmark.js"></script>';

if($_GET['lastPicture']) {
	$lastquery = "SELECT * FROM photos WHERE id=".$_GET['lastPicture']." LIMIT 1";
	$lastresult = mysql_query($lastquery) or die(mysql_error());
	$lastphotoscore = mysql_result($lastresult, 0, "score");

	$query = "SELECT * FROM photos WHERE score<'".$lastphotoscore."' ORDER BY score DESC LIMIT 1,10";
	$mysqlquery = mysql_query($query) or die(mysql_error());

	//DISPLAY 20 NEWEST OF ALL PHOTOS
 	echo'<div id="main" role="main">
                     <ul id="tiles">'; 
	for($iii=1; $iii <= 8; $iii++) {
		$image = mysql_result($mysqlquery, $iii-1, "source");
		$imageThumb = str_replace("userphotos/","userphotos/medthumbs/", $image);
		$id = mysql_result($mysqlquery, $iii-1, "id");
	    	$caption = mysql_result($mysqlquery, $iii-1, "caption");
             $caption = (strlen($caption) > 18) ? substr($caption,0,16). " &#8230;" : $caption;
            $price = mysql_result($mysqlquery, $iii-1, "price");
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
            $heightls = $height / 5;
            $widthls = $width / 5;

            if($widthls < 205) {
                $heightls = $heightls * ($heightls/$widthls);
                $widthls = 240;
            }
                        
    		       echo '
        <a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$id,'&v=n"><li class="fPic" id="',$id,'" style="list-style-type: none;width:240px;"><img id="frontimg" style="min-width:240px;" src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" /></a>
        
            <div class="statoverlay" style="z-index:1;background-color:white;position:relative;top:0px;width:240px;height:30px;">
                <div style="line-spacing:1.48;padding:5px;color:#4A4A4A;">
                    <div style="float:left;padding-top:10px;">
                        <span style="font-size:15px;font-weight:500;">',$score,'</span>&nbsp;&nbsp;<span style="font-weight:300;font-size:15px;">',$caption,' <div style="display:inline;">&nbsp;&nbsp;&nbsp;$',$price,'</div></span>
                    </div>
                </div>
            </div>
            <div style="padding:2px;"></div>
        </li>';       	

	    
      	}  //end for loop
      
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
        offset: 5, // Optional, the distance between grid items
        itemWidth: 245 // Optional, the width of a grid item
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

