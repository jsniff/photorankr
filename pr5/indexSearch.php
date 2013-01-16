<?php

//connect to the database
require "db_connection.php";
require "functionsnav.php";

?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.wookmark.js"></script>

<?php

//Get search term
$searchterm = mysql_real_escape_string(htmlentities($_GET['searchterm']));

echo $test.$searchterm;

if(!$searchterm) {
    $imagesquery = mysql_query("SELECT * FROM photos ORDER BY score DESC LIMIT 17");
}
else{
    $imagesquery = mysql_query("SELECT * FROM photos WHERE concat(tag1,tag2,tag3,tag4,singlestyletags,singlecategorytags) LIKE '%$searchterm%' ORDER BY (points/votes) DESC LIMIT 17");
}

echo'<div id="thepics" style="position:relative;top:-20px;z-index:1;">
                     <div id="main" role="main">
                     <ul id="tiles">';
                
                for($iii = 1; $iii <= 16; $iii++) {
                    $image = mysql_result($imagesquery, $iii-1, "source");
                    $imageThumb=str_replace("userphotos/","userphotos/medthumbs/", $image);
                    $image = "https://photorankr.com/".$image;
                    $id = mysql_result($imagesquery, $iii-1, "id");
                    $caption = mysql_result($imagesquery, $iii-1, "caption");
                    $caption = (strlen($caption) > 18) ? substr($caption,0,16). " &#8230;" : $caption;
                    $price = mysql_result($result, $iii-1, "price");
                    if($price != 'Not For Sale') {
                        $price = '$' . $price;
                    }
                    elseif($price == 'Not For Sale') {
                        $price = 'NFS';
                    }
                    elseif($price == '.00') {
                        $price = 'Free';
                    }
                    $points = mysql_result($imagesquery, $iii-1, "points");
                    $votes = mysql_result($imagesquery, $iii-1, "votes");
                    $price = mysql_result($imagesquery, $iii-1, "price");
                    $score = number_format(($points/$votes),2);
                    $owner = mysql_result($result, $iii-1, "emailaddress");
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
                                      
                }
                            
            ?>
            
            </ul>
    
    <!-- Once the page is loaded, initalize the plug-in. -->
  <script type="text/javascript">
    $(document).ready(new function() {
      // Prepare layout options.
      var options = {
        autoResize: true, // This will auto-update the layout when the browser window is resized.
        container: $('#main'), // Optional, used for some extra CSS styling
        offset: 10, // Optional, the distance between grid items
        itemWidth: 245 // Optional, the width of a grid item
      };
      
      // Get a reference to your grid items.
      var handler = $('#tiles li');
      
      // Call the layout function.
      handler.wookmark(options);
      
    });
  </script>
            
</div>
</div>
            
    <!--AJAX CODE HERE-->
   <div class="grid_6 push_9" style="padding-top:50px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;">Loading More Photos&hellip;</div>
   </div>

<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMoreTrendingPics").show();
				$.ajax({
					url: "loadMoreTrendingFront.php?lastPicture=" + $(".fPic:last").attr("id"),
					success: function(html) {
						if(html) {
							$("#thepics").append(html);
							$("div#loadMoreTrendingPics").hide();
						}
					}
				});
				last = $(".fPic:last").attr("id");
			}
		}
	});
</script>    

</html>