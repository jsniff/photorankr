<?php 

require("db_connection.php");
$email = htmlentities($_GET['email']);

echo'<script type="text/javascript" src="js/jquery.wookmark.js"></script>';
?>

<script type="text/javascript">
  //Create Request Object
function createRequestObject() {
    var ajaxRequest;  //ajax variable
	try{
		// Opera 8.0+, Firefox, Safari
		ajaxRequest = new XMLHttpRequest();
	} catch (e){
		// Internet Explorer Browsers
		try{
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try{
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e){
				// Something went wrong
				alert("Your browser broke!");
				return false;
			}
		}
	}
    
    return ajaxRequest;
    
}
  
//AJAX FAVE
function ajaxFunction(image){
    var image = image;
    ajaxRequest = createRequestObject();
    // Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4  && ajaxRequest.status == 200){
			var ajaxDisplay = document.getElementById('ajaxFave' + image);
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
		}
	}
	var age = "<?php echo $email; ?>";
	var queryString = "?age=" + age + "&image=" + image;
	ajaxRequest.open("GET", "ajaxfavegallery.php" + queryString, true);
	ajaxRequest.send(null); 
}

</script>


<?php
$faves = mysql_query("SELECT faves FROM userinfo WHERE emailaddress = '$email'");
$mycheck = mysql_result($faves, 0, "faves");

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

    //Ajax Faves
    $match=strpos($mycheck, $image);
    
    echo '
        <a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$id,'&v=n"><li class="fPic" id="',$id,'" style="list-style-type: none;width:280px;">
    
    <div id="outer">
        <img style="min-width:280px;" src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />
        
        <div class="galleryOverlay" id="tooltip">
            <a style="color:#fff;" href="fullsizemarket.php?imageid=',$id,'">
            <img style="width:18px;padding:10px 6px;" src="graphics/whitecart2.png" />
            <span style="font-weight:300!important;font-size:13px;"><span style="font-weight:500;font-size:14px;">$',$price,'</span> Download</span>
            </a>';
    
    if($email) {
        if(!$match) {
            echo'
            <a style="color:#fff;cursor:pointer;" onclick="ajaxFunction(\'',$image,'\')" id="ajaxFave',$image,'">
            <i style="margin-top:2px;margin-left:10px;" class="icon-heart icon-white"></i>
            <span style="font-weight:300!important;font-size:13px;"> Favorite </span>
            </a>';
        }
        elseif($match) {
            echo'
            <i style="margin-top:2px;margin-left:10px;" class="icon-ok icon-white"></i>
            <span style="font-weight:300!important;font-size:13px;"> Favorited </span>';
        }
    }
        
        echo'
        </div>
    </div>
    
            <div class="statoverlay" style="z-index:1;background-color:white;position:relative;top:0px;width:280px;height:30px;">
                <div style="line-spacing:1.48;padding:5px;color:#4A4A4A;">
                    <div style="float:left;padding-top:10px;">
                        <span style="font-size:15px;font-weight:500;">',$score,'</span>&nbsp;&nbsp;<span style="font-weight:300;font-size:15px;">',$caption,'</span>
                    </div>
                </div>
            </div>';        
            	    
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
