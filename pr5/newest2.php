<?php

//connect to the database
require "db_connection.php";
require "functionsnav.php";

//start the session
session_start();

    // if login form has been submitted
    if (htmlentities($_GET['action']) == "login") { 
        login();
    }
    else if(htmlentities($_GET['action']) == "logout") { 
        logout();
    }

    $email = $_SESSION['email'];

//QUERY FOR NOTIFICATIONS
$currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$email'";
$currentnotsquery = mysql_query($currentnots);
$currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");

//notifications query reset 
if($currentnotsresult > 0) {
$notsquery = "UPDATE userinfo SET notifications = 0 WHERE emailaddress = '$email6'";
$notsqueryrun = mysql_query($notsquery); }

//DISCOVER SCRIPT
    
  //get the users information from the database
  $likesquery = "SELECT * FROM userinfo WHERE emailaddress='$email'";
  $likesresult = mysql_query($likesquery) or die(mysql_error());
  $discoverseen = mysql_result($likesresult, 0, "discoverseen");

  //find out what they like
  $likes = mysql_result($likesresult, 0, "viewLikes");
    if($likes=="") {
		$nolikes = 1;
        		
	}

  $likes .= "  ";
  $likes .= mysql_result($likesresult, 0, "buyLikes");

  //create an array from what they like
  $likesArray = explode("  ", $likes);

  //loop through the array to format the likes in the proper format for the query
  $formattedLikes = "%";
  for($iii=0; $iii < count($likesArray); $iii++) {
    $formattedLikes .= $likesArray[$iii];
    $formattedLikes .= "%";
  }

    //make an array of the photos they have already seen
  if($discoverseen != "") {
    $discoverArray = explode(" ", $discoverseen);
    $discoverFormatted = "";
    for($iii=0; $iii < count($discoverArray)-1; $iii++) {
      $discoverFormatted .= "'";
      $discoverFormatted .= $discoverArray[$iii];
      $discoverFormatted .= "', ";
    }
    $discoverFormatted .= "'";
    $discoverFormatted .= $discoverArray[count($discoverArray)-1];
    $discoverFormatted .= "'";
  }
  
  //select the image that they will be seeing next
  //delineate between whether they have used discover feature before
  if($discoverseen != "") {     //get the photos that match this person's view interests
    $viewquery = "SELECT *, MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$formattedLikes') AS matching FROM photos WHERE MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$formattedLikes') AND id NOT IN(" . $discoverFormatted . ") ORDER BY matching DESC, points DESC LIMIT 0, 1";
    $viewresult = mysql_query($viewquery) or die(mysql_error());
  }
  else {
    //get the photos that match this person's view interests
    $viewquery = "SELECT *, MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$formattedLikes') AS matching FROM photos WHERE MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$formattedLikes') ORDER BY matching DESC, points DESC LIMIT 0, 1";
    $viewresult = mysql_query($viewquery) or die(mysql_error());
  }

  $discoverimage = mysql_result($viewresult, 0, "id");
  
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="https://www.w3.org/1999/xhtml">

<head>

 <meta name="Generator" content="EditPlus">
  <meta name="Author" content="PhotoRankr, PhotoRankr.com">
  <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
  <meta name="Description" content="A gallery of the newest photography, photographers, and exhibits on PhotoRankr.">
     <meta name="viewport" content="width=1200" /> 


  <link rel="stylesheet" type="text/css" href="market/css/bootstrapNew.css" />
  <link rel="stylesheet" href="market/css/reset.css" type="text/css" />
  <link rel="stylesheet" href="market/css/text.css" type="text/css" />
  <link rel="stylesheet" href="960_24.css" type="text/css" />
  <script type="text/javascript" href="js/bootstrap-dropdown.js"></script>
  <script type="text/javascript">
    document.write("\<script src='//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js' type='text/javascript'>\<\/script>");
  </script>  
  <script type="text/javascript" src="js/jquery.wookmark.js"></script>        
  <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
  
  <title>PhotoRankr - Newest Photography</title>

  
<script type="text/javascript">
  $(function() {
  // Setup drop down menu
  $('.dropdown-toggle').dropdown();
 
  // Fix input element click problem
  $('.dropdown input, .dropdown label').click(function(e) {
    e.stopPropagation();
  });
});

</script>

<style type="text/css">


 .statoverlay

{
opacity:.7;
filter:alpha(opacity=40);
z-index:1;
transition: opacity .5s;
-moz-transition: opacity .5s;
-webkit-transition: opacity .5s;
-o-transition: opacity .5s;
}



 .statoverlay2

{
opacity:.6;
filter:alpha(opacity=40);
z-index:1;
transition: opacity .5s;
-moz-transition: opacity .5s;
-webkit-transition: opacity .5s;
-o-transition: opacity .5s;
}
                         

.item {
  margin: 10px;
  float: left;
  border: 2px solid transparent;
}

.item:hover {
  margin: 10px;
  float: left;
  border: 2px solid black;
}

</style>

<!--GOOGLE ANALYTICS CODE-->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-28031297-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'https://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
  
</script>


</head>
<body style="overflow-x:hidden; background-color: #eeeff3;min-width:1220px;">

<?php navbarnew(); ?>

   <!--big container-->
    <div id="container" class="container_24" >
    

<!--DIFFERENT GALLERY VIEWS-->

<?php  

if(isset($_GET['view'])){
$view = htmlentities($_GET['view']);
}

        echo'<br /><br /><br /><br />
        <div style="margin-left:-70px;font-size:15px;font-weight:200;font-family:"Helvetica Neue",Helvetica,Arial;">
        
        <a class="pxbutton" style="text-decoration:none;margin-right:15px;';if($view == '') {echo'padding:10px;-moz-border-radius: 5px;-webkit-border-radius: 5px;border-radius: 5px;background-color:#000;color:#fff;opacity:.9;';} else {echo'';} echo'" href="newest.php">Newest Photos</a> 
        
        <a class="pxbutton" style="text-decoration:none;margin-right:15px;';if($view == 'prs') {echo'padding:10px;-moz-border-radius: 5px;-webkit-border-radius: 5px;border-radius: 5px;background-color:#000;color:#fff;opacity:.9;';} else {echo'';} echo'" href="newest.php?view=prs">Newest Photographers</a>
        
        <a class="pxbutton" style="text-decoration:none;margin-right:15px;';if($view == 'exts') {echo'padding:10px;-moz-border-radius: 5px;-webkit-border-radius: 5px;border-radius: 5px;background-color:#000;color:#fff;opacity:.9;';} else {echo'';} echo'" href="newest.php?view=exts">Newest Exhibits</a>'; 
        
	if($_SESSION['loggedin'] == 1) {
	       
		 echo'<a class="pxbutton" style="text-decoration:none;margin-right:15px;';if($view == 'following') {echo'padding:10px;-moz-border-radius: 5px;-webkit-border-radius: 5px;border-radius: 5px;background-color:#000;color:#fff;opacity:.9;';} else {echo'';} echo'" href="newest.php?view=following">Following</a>';
	}
        
	echo'
        </div>
                
         <script>
                    function submitTime(sel) {
                        sel.form.submit();
                    }
                </script>';
                
                if($view == '') {
                
                    $cat = htmlentities($_GET['c']);

                echo'            
                    <!-- Select Basic -->
                    <form action="newest.php" method="get">';
                    
                        echo'
                        <select name="c"  onchange="submitTime(this)" class="input-large" style="width:140px;margin-top:-33px;margin-left:525px;">
                        
                        <option value=""'; if($cat == '') {echo'selected value=""';} echo'>All Photos</option>
                        
                        <option value="aerial"'; if($cat == 'aerial') {echo'selected value=""';} echo'>Aerial</option>
                        
                        <option value="animal"'; if($cat == 'animal') {echo'selected value=""';} echo'>Animal</option>
                        
                        <option value="architecture"'; if($cat == 'architecture') {echo'selected value=""';} echo'>Architecture</option>
                        
                        <option value="astro"'; if($cat == 'astro') {echo'selected value=""';} echo'>Astro</option>
                        
                        <option value="automotive"'; if($cat == 'automotive') {echo'selected value=""';} echo'>Automotive</option>
                        
                        <option value="bw"'; if($cat == 'bw') {echo'selected value=""';} echo'>Black & White</option>
                        
                        <option value="cityscape"'; if($cat == 'cityscape') {echo'selected value=""';} echo'>Cityscape</option>
                        
                        <option value="fashion"'; if($cat == 'fashion') {echo'selected value=""';} echo'>Fashion</option>
                        
                        <option value="fineart"'; if($cat == 'fineart') {echo'selected value=""';} echo'>Fine Art</option>
                        
                        <option value="fisheye"'; if($cat == 'fisheye') {echo'selected value=""';} echo'>Fish Eye</option>
                        
                        <option value="food"'; if($cat == 'food') {echo'selected value=""';} echo'>Food</option>
                        
                        <option value="HDR"'; if($cat == 'HDR') {echo'selected value=""';} echo'>HDR</option>
                        
                        <option value="historical"'; if($cat == 'historical') {echo'selected value=""';} echo'>Historical</option>
                        
                        <option value="industrial"'; if($cat == 'industrial') {echo'selected value=""';} echo'>Industrial</option>
                        
                        <option value="landscape"'; if($cat == 'landscape') {echo'selected value=""';} echo'>Landscape</option>
                        
                        <option value="longexposure"'; if($cat == 'longexposure') {echo'selected value=""';} echo'>Long Exposure</option>
                        
                        <option value="macro"'; if($cat == 'macro') {echo'selected value=""';} echo'>Macro</option>
                        
                        <option value="monochrome"'; if($cat == 'monochrome') {echo'selected value=""';} echo'>Monochrome</option>
                        
                        <option value="nature"'; if($cat == 'nature') {echo'selected value=""';} echo'>Nature</option>
                        
                        <option value="news"'; if($cat == 'news') {echo'selected value=""';} echo'>News</option>
                        
                        <option value="night"'; if($cat == 'night') {echo'selected value=""';} echo'>Night</option>
                        
                        <option value="panorama"'; if($cat == 'panorama') {echo'selected value=""';} echo'>Panorama</option>
                        
                        <option value="people"'; if($cat == 'people') {echo'selected value=""';} echo'>People</option>
                        
                        <option value="scenic"'; if($cat == 'scenic') {echo'selected value=""';} echo'>Scenic</option>
                        
                        <option value="sports"'; if($cat == 'sports') {echo'selected value=""';} echo'>Sports</option>
                        
                        <option value="stilllife"'; if($cat == 'stilllife') {echo'selected value=""';} echo'>Still Life</option>
                        
                        <option value="transportation"'; if($cat == 'transportation') {echo'selected value=""';} echo'>Transportation</option>
                        
                        <option value="war"'; if($cat == 'war') {echo'selected value=""';} echo'>War</option>
                        
                        </select>';

                    echo'    
                    </form>';
                } 
        
        
if($view == '') {
        
    //DISPLAY 20 NEWEST OF ALL PHOTOS
        
    if($cat == '') {
        $result = mysql_query("SELECT * FROM photos ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'aerial') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Aerial%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'animal') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Animal%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'architecture') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Architecture%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'astro') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Astro%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'automotive') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Automotive%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'bw') {
        $result = mysql_query("SELECT * FROM photos WHERE singlestyletags LIKE '%B&W%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'cityscape') {
        $result = mysql_query("SELECT * FROM photos WHERE singlestyletags LIKE '%cityscape%' ORDER BY id DESC LIMIT 0, 16");
    }
    
     elseif($cat == 'fashion') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Fashion%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    
     elseif($cat == 'fineart') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Fine Art%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'fisheye') {
        $result = mysql_query("SELECT * FROM photos WHERE singlestyletags LIKE '%Fisheye%' ORDER BY id DESC LIMIT 0, 16");
    }
    
     elseif($cat == 'food') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Food%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'HDR') {
        $result = mysql_query("SELECT * FROM photos WHERE singlestyletags LIKE '%HDR%' ORDER BY id DESC LIMIT 0, 16");
    }
    
     elseif($cat == 'historical') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Historical%' ORDER BY id DESC LIMIT 0, 16");
    }
    
     elseif($cat == 'industrial') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Industrial%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'landscape') {
        $result = mysql_query("SELECT * FROM photos WHERE singlestyletags LIKE '%Landscape%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'longexposure') {
        $result = mysql_query("SELECT * FROM photos WHERE singlestyletags LIKE '%Long Exposure%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'macro') {
        $result = mysql_query("SELECT * FROM photos WHERE singlestyletags LIKE '%Macro%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'monochrome') {
        $result = mysql_query("SELECT * FROM photos WHERE singlestyletags LIKE '%Monochrome%' ORDER BY id DESC LIMIT 0, 16");
    }
    
     elseif($cat == 'nature') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Nature%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'news') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%News%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'night') {
        $result = mysql_query("SELECT * FROM photos WHERE singlestyletags LIKE '%Night%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'panorama') {
        $result = mysql_query("SELECT * FROM photos WHERE singlestyletags LIKE '%Panorama%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'people') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%People%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'scenic') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Scenic%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'sports') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Sports%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'stilllife') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Still Life%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'transportation') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%Transportation%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    elseif($cat == 'war') {
        $result = mysql_query("SELECT * FROM photos WHERE singlecategorytags LIKE '%War%' ORDER BY id DESC LIMIT 0, 16");
    }
    
    $numberofpics=mysql_num_rows($result);
    
    echo'
    <div id="thepics" style="position:relative;margin-left:-130px;top:-20px;width:1240px;">
    <div id="main" role="main">
    <ul id="tiles">';
    
for($iii=1; $iii <= 16; $iii++) {
	$image = mysql_result($result, $iii-1, "source");
    $imageThumb=str_replace("userphotos/","userphotos/medthumbs/", $image);
	$id = mysql_result($result, $iii-1, "id");
    $caption = mysql_result($result, $iii-1, "caption");
     $caption = (strlen($caption) > 28) ? substr($caption,0,25). " &#8230;" : $caption;
    $points = mysql_result($result, $iii-1, "points");
    $price = mysql_result($result, $iii-1, "price");
    if($price != 'Not For Sale') {
        $price = '$' . $price;
    }
    elseif(!$price || $price == 'Not For Sale') {
        $price = 'NFS';
    }
    elseif($price == '.00') {
        $price = 'Free';
    }
    $votes = mysql_result($result, $iii-1, "votes");
    $score = number_format(($points/$votes),2);
    $owner = mysql_result($result, $iii-1, "emailaddress");
    $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$owner'");
    $firstname = mysql_result($ownerquery, 0, "firstname");
    $lastname = mysql_result($ownerquery, 0, "lastname");
    $fullname = $firstname . " " . $lastname;

	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
    $heightls = $height / 4.3;
    $widthls = $width / 4.3;
    if($widthls < 255) {
    $heightls = $heightls * ($heightls/$widthls);
    $widthls = 270;
    }

		echo '
        <a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$id,'&v=n"><li class="fPic" id="',$id,'" style="padding:0px;margin-right:10px;margin-top:10px;list-style-type: none;width:270px;
"><img src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />

<div class="statoverlay" style="z-index:1;background-color:black;position:relative;top:0px;width:270px;height:30px;"><div style="line-spacing:1.48;padding:5px;color:white;"><div style="float:left;"<span style="font-size:18px;font-weight:100;">',$score,'</span>&nbsp;&nbsp;<span style="font-weight:100;font-size:16px;">',$caption,'</span></div><div style="float:right;"><span style="font-size:13px;">',$price,'</span></div></div><br/></div>
';
       
	    
      } //end for loop
        
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
        offset: 4, // Optional, the distance between grid items
        itemWidth: 290 // Optional, the width of a grid item
      };
      
      // Get a reference to your grid items.
      var handler = $('#tiles li');
      
      // Call the layout function.
      handler.wookmark(options);
      
    });
  </script>


<?php

echo'
</div>
</div>

<!--AJAX CODE HERE-->
   <div class="grid_6 push_9" style="padding-top:50px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;">Loading More Photos&hellip;</div>
   </div>';


echo '<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMorePics").show();
				$.ajax({
					url: "loadMoreNewPicsTest.php?lastPicture=" + $(".fPic:last").attr("id")+"&c=',$cat,'",
					success: function(html) {
						if(html) {
							$("#thepics").append(html);
							$("div#loadMorePics").hide();
						}
					}
				});
				last = $(".fPic:last").attr("id");
			}
		}
	});
</script>';

} //end of view == ''



elseif($view == 'prs') {
$prsquery="SELECT * FROM userinfo WHERE (profilepic != 'https://www.photorankr.com/profilepics/default_profile.jpg' AND profilepic != 'profilepics/default_profile.jpg') ORDER BY user_id DESC";
$prsresult=mysql_query($prsquery);

echo'<div id="container" style="width:1210px;margin-left:-112px;top:15px;">';
for($iii=1; $iii <= 16; $iii++) {
	$profpic = mysql_result($prsresult, $iii-1, "profilepic");
    if($profpic == 'https://www.photorankr.com/profilepics/default_profile.jpg') {
    $profpic = 'profilepics/default_profile.jpg';
    }
    $firstname = mysql_result($prsresult, $iii-1, "firstname");
	$lastname = mysql_result($prsresult, $iii-1, "lastname");
    $fullname = $firstname . " " . $lastname;
    $fullname = ucwords($fullname);
	$userid = mysql_result($prsresult, $iii-1, "user_id");

		echo '<div class="fPic" id="',$id,'" style="float:left;margin-right:20px;margin-top:20px;width:280px;height:280px;overflow:hidden;"><a style="text-decoration:none;" href="viewprofile.php?u=',$userid,'">
        
        <div class="statoverlay" style="z-index:1;left:0px;top:215px;position:relative;background-color:black;width:280px;height:40px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-family:helvetica neue,arial;font-weight:100;font-size:22px;">',$fullname,'</span></div>
        
        <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-75px;min-height:290px;min-width:280px;" src="',$profpic,'" alt="',$fullname,'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';

    } //end for loop
    echo'</div>';
    
} //end of view == 'prs'



elseif($view == 'exts') {
$query="SELECT * FROM sets ORDER BY id DESC LIMIT 0,16";
$result=mysql_query($query);
$numberexhibits=mysql_num_rows($result);

echo'
    <div id="thepics" style="position:relative;margin-left:-125px;top:20px;width:1240px;">
    <div id="main" role="main">
    <ul id="tiles">';
    
    for($iii=1; $iii <= $numberexhibits; $iii++) {
	$coverpic = mysql_result($result, $iii-1, "cover");
    $coverpic2 = str_replace("userphotos/","userphotos/medthumbs/",$coverpic);

    $caption = mysql_result($result, $iii-1, "title");
    $set_id = mysql_result($result, $iii-1, "id");
    $pulltopphoto = mysql_query("SELECT source FROM photos WHERE set_id = '$set_id' ORDER BY votes DESC LIMIT 5");

    if($coverpic == '') {
        $coverpic = mysql_result($pulltopphoto, 0, "source");
        $coverpic2 = str_replace("userphotos/","userphotos/medthumbs/",$coverpic);
    }

    $thumb1 = mysql_result($pulltopphoto, 1, "source");
    $thumb1 = str_replace("userphotos/","userphotos/medthumbs/",$thumb1);
    $thumb2 = mysql_result($pulltopphoto, 2, "source");
    $thumb2 = str_replace("userphotos/","userphotos/medthumbs/",$thumb2);
    $thumb3 = mysql_result($pulltopphoto, 3, "source");
    $thumb3 = str_replace("userphotos/","userphotos/medthumbs/",$thumb3);
    $thumb4 =mysql_result($pulltopphoto, 4, "source");
    $thumb4 = str_replace("userphotos/","userphotos/medthumbs/",$thumb4);

    list($width, $height) = getimagesize($coverpic);
    $imgratio = $height / $width;
    $heightls = $height / 3.2;
    $widthls = $width / 3.2;
        
    if($widthls < 240) {
        $heightls = $heightls * ($heightls/$widthls);
        $widthls = 250;
    }

    $owner = mysql_result($result, $iii-1, "owner");
    $exhibitquery = mysql_query("SELECT * FROM photos WHERE set_id = '$set_id'");
    $numberphotos = mysql_num_rows($exhibitquery);
    
    if($numberphotos < 1) {
        continue;
    }
   
    for($i = 0; $i < $numberphotos; $i++) {
    $points += mysql_result($exhibitquery, $i, "points");
    $votes += mysql_result($exhibitquery, $i, "votes");
    }
    
    $score = number_format(($points/$votes),2);
    $price = mysql_result($exhibitquery, $iii, "price");
    if($price != 'Not For Sale') {
                    $price = '$' . $price;
                }
                elseif($price == 'Not For Sale') {
                    $price = 'NFS';
                }
    
    $avgscorequery = mysql_query("UPDATE sets SET avgscore = '$score' WHERE id = '$set_id'");
    
    $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$owner'");
    $firstname = mysql_result($ownerquery, 0, "firstname");
    $lastname = mysql_result($ownerquery, 0, "lastname");
    $fullname = $firstname . " " . $lastname;
    
	$userid = mysql_result($ownerquery, 0, "user_id");
    
    echo'<li style="width:240px;list-style-type:none;position:relative;" class="fPic" id="',$set_id,'"><a style="text-decoration:none;" href="viewprofile.php?u=',$userid,'&view=exhibits&set=',$set_id,'">
    
        <div style="padding-top:5px;padding-left:3px;font-size:13px;text-decoration:none;color:#000;font-weight:200;"><span style="font-size:15px;font-weight:400;">',$caption,'</span><br />',$numberphotos,' Photos</div>
<hr />

    <img style="margin-top:-6px;" onmousedown="return false" oncontextmenu="return false;" src="https://www.photorankr.com/',$coverpic2,'" alt="',$setname[$iii],'" height="',$heightls,'px" width="',$widthls,'px" />';
    
    
    
    if($thumb4) {
        echo'
            <div style="margin-top:5px;">
            <img style="float:left;padding:4px;" src="https://www.photorankr.com/',$thumb1,'" width="112" height="110" />
            <img style="float:left;padding:4px;" src="https://www.photorankr.com/',$thumb2,'" width="112" height="110" />
            <img style="float:left;padding:4px;" src="https://www.photorankr.com/',$thumb3,'" width="112" height="110" />
            <img style="float:left;padding:4px;" src="https://www.photorankr.com/',$thumb4,'" width="112" height="110" />
            </div>';
    }
    
    echo'
    </a>
    
    </li>';
        
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
        itemWidth: 290 // Optional, the width of a grid item
      };
      
      // Get a reference to your grid items.
      var handler = $('#tiles li');
      
      // Call the layout function.
      handler.wookmark(options);
      
    });
  </script>

    
 <?php

echo'
</div>

<!--AJAX CODE HERE-->
   <div class="grid_13 push_9" style="padding-top:50px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;">Loading More Exhibits&hellip;</div>
   </div>';


echo '<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMorePics").show();
				$.ajax({
					url: "loadMoreNewExhibits.php?lastPicture=" + $(".fPic:last").attr("id"),
					success: function(html) {
						if(html) {
							$("#thepics").append(html);
							$("div#loadMorePics").hide();
						}
					}
				});
				last = $(".fPic:last").attr("id");
			}
		}
	});
</script>';



} //end of view == 'exts'


if($view == 'following') {
        
    //DISPLAY 20 NEWEST FROM FOLLOWING
    
    //FOLLOWING LIST
    $followingquery = "SELECT following FROM userinfo WHERE emailaddress='$email'";
    $followingresult = mysql_query($followingquery);
    $followinglistowner = mysql_result($followingresult, 0, "following");   
    
    $query="SELECT * FROM photos WHERE emailaddress IN ($followinglistowner) ORDER BY id DESC LIMIT 0, 16";
    $result=mysql_query($query);
    $numberofpics=mysql_num_rows($result);
         
    echo'<div id="thepics">';
    echo'<div id="container" style="width:1210px;margin-left:-112px;top:15px;">';
    
for($iii=1; $iii <= 16; $iii++) {
	$image = mysql_result($result, $iii-1, "source");
    $imageThumb=str_replace("userphotos/","userphotos/medthumbs/", $image);
	$id = mysql_result($result, $iii-1, "id");
    $caption = mysql_result($result, $iii-1, "caption");
     $caption = (strlen($caption) > 28) ? substr($caption,0,25). " &#8230;" : $caption;
    $points = mysql_result($result, $iii-1, "points");
    $votes = mysql_result($result, $iii-1, "votes");
    $score = number_format(($points/$votes),2);
    $owner = mysql_result($result, $iii-1, "emailaddress");
    $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$owner'");
    $firstname = mysql_result($ownerquery, 0, "firstname");
    $lastname = mysql_result($ownerquery, 0, "lastname");
    $fullname = $firstname . " " . $lastname;
    $profilepic = mysql_result($ownerquery, 0, "profilepic");
    
	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
    $heightls = $height / 2.5;
    $widthls = $width / 2.5;

		echo '<div class="fPic" id="',$id,'" style="float:left;margin-right:20px;margin-top:20px;width:280px;height:280px;overflow:hidden;"><a style="text-decoration:none;" href="https://photorankr.com/fullsize.php?image=',$image,'&v=n">
        
          <div class="statoverlay" style="z-index:1;left:0px;top:235px;position:relative;background-color:black;width:280px;height:50px;"><p style="line-spacing:1.48;padding:5px;color:white;"><img src="',$profilepic,'" width="30" />&nbsp;&nbsp;<span style="font-family:helvetica neue,arial;font-weight:100;font-size:20px;">',$caption,'</span><br/></div>
        
        <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-75px;min-height:300px;min-width:280px;" src="https://photorankr.com/',$imageThumb,'" alt="',$caption,'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
       
	    
      } //end for loop
      echo'</div>';
            echo'</div>';


echo'
<!--AJAX CODE HERE-->
   <div class="grid_6 push_9" style="padding-top:50px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;">Loading More Photos&hellip;</div>
   </div>';


echo '<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMorePics").show();
				$.ajax({
					url: "loadMoreNewPicsFollowing.php?lastPicture=" + $(".fPic:last").attr("id")+"&useremail=',$email,'",
					success: function(html) {
						if(html) {
							$("#thepics").append(html);
							$("div#loadMorePics").hide();
						}
					}
				});
				last = $(".fPic:last").attr("id");
			}
		}
	});
</script>';

} //end of view == 'following'

?>

</div>

</body>
</html>