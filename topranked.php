<?php

//connect to the database
require "db_connection.php";
require "functions.php";

//start the session
session_start();

    // if login form has been submitted
    if(htmlentities($_GET['action']) == "login") { 
        login();
    }
    elseif(htmlentities($_GET['action']) == "logout") { 
        logout();
    }

    $email = $_SESSION['email'];
    
//Get view
$view = htmlentities($_GET['view']);

//QUERY FOR NOTIFICATIONS
$currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$email'";
$currentnotsquery = mysql_query($currentnots);
$currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");
$mycheck = mysql_result($currentnotsquery, 0, "faves");

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
    <meta name="Description" content="The top ranked photography on PhotoRankr.">
    <meta name="viewport" content="width=1200" /> 
    
    <title>PhotoRankr - Top Ranked Photography</title>

    <link rel="stylesheet" type="text/css" href="css/style.css"/>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="css/960grid.css"/>
	<link rel="stylesheet" type="text/css" href="css/reset.css"/> 
    <link rel="stylesheet" type="text/css" href="css/main3.css"/>

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.wookmark.js"></script>    
    <script src="js/bootstrap.js"></script>
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
  
<style type="text/css">


 .statoverlay

{
background-attachment: scroll;
background-clip: border-box;
background-color: 
rgba(0, 0, 0, 0.848438);
background-image: none;
background-origin: padding-box;
color: rgb(255, 255, 255);
bottom: 0px;
display: block;
font-family: 'Helvetica Neue', 'Helvetica Neue', Helvetica, Arial, sans-serif;
font-size: 14px;
font-style: normal;
font-variant: normal;
font-weight: normal;
line-height: 0px;
margin-bottom: 0px;
margin-left: 0px;
margin-right: 0px;
margin-top: 0px;
overflow-x: hidden;
overflow-y: hidden;
padding-bottom: 0px;
padding-left: 0px;
padding-right: 0px;
padding-top: 0px;
white-space: nowrap;
width: 270px;
-moz-box-shadow: 1px 1px 5px #888;
-webkit-box-shadow: 1px 1px 5px #888;
box-shadow: 1px 1px 5px #888;
}

</style>

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



</head>
<body style="overflow-x:hidden; background-image:url('graphics/paper.png');">
<?php include_once("analyticstracking.php") ?>

<?php navbar(); ?>

<?php 
    if(!$email) {
        echo'
        <div id="registerBar">
            <img style="width:16px;padding:4px;margin-top:-3px;" src="graphics/tick 2.png" />
            <a href="register.php">Register for free today to begin sharing and selling.
            </a> 
        </div>
        <div style="clear:both;padding-bottom:20px;"></div>';
    }
?>

   <!--big container-->
    <div id="container" class="container_24" style="width:1200px;overflow:hidden;">
    
<div class="galleryToolbar" style="margin-top:70px;margin-left:70px;">
            <ul>
                <a style="color:#333;" href="topranked.php"><li style="width:272px;-webkit-border-radius: 4px;-moz-border-radius: 2px;border-radius: 2px;padding-left:8px;margin-left:0px;text-align:left;"><img style="float:left;width:20px;height:20px;" src="graphics/award.png" />&nbsp;&nbsp;Top Ranked</li></a>
                <a style="color:#333;" href="topranked.php"><li style="width:134px;"><img src="graphics/camera2.png" /> Photos</li></a>
                <a style="color:#333;" href="topranked.php?view=prs"><li style="width:134px;"><img src="graphics/user.png" /> Photographers</li></a>
                <a style="color:#333;" href="topranked.php?view=exts"><li style="width:134px;"><img src="graphics/grid.png" /> Exhibits </li></a>
            <?php

                if(isset($_GET['t'])){
                    $timesetting = $_GET['t'];
                }
    
                echo'
                <script>
                    function submitTime(sel) {
                        sel.form.submit();
                    }
                </script>';
                
                if($view == '') {
                echo'            
                    <!-- Select Basic -->
                    <label class="control-label"></label>
                    <div style="overflow:hidden;width:160px;float:right;margin-top:7px;margin-right:10px;">
                    <form action="topranked.php" method="get">';
                    
                    if($timesetting == '') {
                        echo'
                        <select name="t"  onchange="submitTime(this)" style="width:150px;">
                        <option value="" selected value="">All Time</option>
                        <option value="m">This Month</option>
                        <option value="w">This Week</option>
                        </select>';
                     }   
                     elseif($timesetting == 'm') {
                        echo'
                        <select name="t"  onchange="submitTime(this)" style="width:150px;">
                        <option value="">All Time</option>
                        <option value="m" selected value="" >This Month</option>
                        <option value="w">This Week</option>
                        </select>';
                     }  
                     elseif($timesetting == 'w') {
                        echo'
                        <select name="t"  onchange="submitTime(this)" style="width:150px;">
                        <option value="">All Time</option>
                        <option value="m" >This Month</option>
                        <option value="w" selected value="" >This Week</option>
                        </select>';
                     }  
                    
                    echo'    
                    </form>
                    </div>';

                } 
                ?>
                </li>
            </ul>
        </div>

<!--DIFFERENT GALLERY VIEWS-->

<?php  
        
if($view == '') {
    
    //Time setting is set to all time

if ($timesetting == '') {
    $query="SELECT * FROM photos ORDER BY points DESC LIMIT 0, 21";
    $result=mysql_query($query);
}

elseif ($timesetting == 'm') {
    $lowertimebound = time() - 2419900;
    $query="SELECT * FROM photos WHERE time > '$lowertimebound' ORDER BY points DESC LIMIT 0, 21";
    $result=mysql_query($query);
}

elseif ($timesetting == 'w') {
    $lowertimebound = time() - 604800;
    $query="SELECT * FROM photos WHERE time > '$lowertimebound' ORDER BY points DESC LIMIT 0, 21";
    $result=mysql_query($query);
}

$numberofpics=mysql_num_rows($result);
    
    echo'
    <div id="thepics" style="position:relative;left:40px;top:10px;width:1210px;">
    <div id="main">
    <ul id="tiles">';
        
for($iii=1; $iii <= 16; $iii++) {
	$image = mysql_result($result, $iii-1, "source");
    $imageThumb=str_replace("userphotos/","userphotos/medthumbs/", $image);
	$id = mysql_result($result, $iii-1, "id");
    $caption = mysql_result($result, $iii-1, "caption");
     $caption = (strlen($caption) > 28) ? substr($caption,0,25). " &#8230;" : $caption;
    $points = mysql_result($result, $iii-1, "points");
    $price = mysql_result($result, $iii-1, "price");
    $votes = mysql_result($result, $iii-1, "votes");
    $score = number_format(($points/$votes),2);
    $owner = mysql_result($result, $iii-1, "emailaddress");
    $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$owner'");
    $firstname = mysql_result($ownerquery, 0, "firstname");
    $lastname = mysql_result($ownerquery, 0, "lastname");
    $fullname = $firstname . " " . $lastname;

	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
    $heightls = $height / 3.3;
    $widthls = $width / 3.3;
    if($widthls < 235) {
    $heightls = $heightls * ($heightls/$widthls);
    $widthls = 280;
    }

		
		//Ajax Faves
    $match=strpos($mycheck, $image);
    
		echo '
        <a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$id,'&v=n"><li class="fPic" id="',$points,'" style="list-style-type: none;width:280px;">
    
    <div id="outer">
        <img onmousedown="return false" oncontextmenu="return false;" style="min-width:280px;" src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />
        
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

echo'
</div>
</div>

  <div class="grid_6 push_13" style="padding-top:25px;padding-bottom:25px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;"><img style="width:50px;" src="LoadingGIF.gif" /></div>
   </div>';


echo '<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMorePics").show();
				$.ajax({
					url: "loadMoreTopRanked.php?lastPicture=" + $(".fPic:last").attr("id")+"&c=',$cat,'"+"&email=',$email,'",
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
//get number of photographers with score greater than 700
$topquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress NOT IN ('msniff16@gmail.com') ORDER BY reputation DESC LIMIT 0,33");

echo'<div id="container" style="width:1210px;margin-left:65px;top:10px;">';
for($iii=1; $iii <= 20; $iii++) {
	$profpic = mysql_result($topquery, $iii-1, "profilepic");
    if($profpic == 'https://www.photorankr.com/profilepics/default_profile.jpg') {
    $profpic = 'profilepics/default_profile.jpg';
    }
    $firstname = mysql_result($topquery, $iii-1, "firstname");
    $rep = number_format(mysql_result($topquery, $iii-1, "reputation"),2);
	$lastname = mysql_result($topquery, $iii-1, "lastname");
    $fullname = $firstname . " " . $lastname;
    $fullname = ucwords($fullname);
	$userid = mysql_result($topquery, $iii-1, "user_id");

		echo '
        <a style="text-decoration:none;color:#333;" href="viewprofile.php?u=',$userid,'">
            <div class="fPic" id="',$id,'" style="float:left;width:275px;padding:5px;">
                <img style="min-width:275px;" src="https://photorankr.com/',$profpic,'" />
        
            <div class="statoverlay" style="z-index:1;background-color:white;position:relative;top:0px;width:275px;height:30px;">
                <div style="line-spacing:1.48;padding:5px;color:#4A4A4A;">
                    <div style="float:left;padding-top:10px;">
                        <span style="font-size:15px;font-weight:500;">',$rep,'</span>&nbsp;&nbsp;<span style="font-weight:300;font-size:15px;">',$fullname,'</span>
                    </div>
                </div>
            </div>
        </div>';       	

    } //end for loop
    echo'</div>';
    
} //end of view == 'prs'



elseif($view == 'exts') {
        
        $galleryquery = mysql_query("SELECT * FROM sets ORDER BY avgscore DESC LIMIT 0,30");
        $numgalleries = mysql_num_rows($galleryquery);
        
        echo'<div id="container" style="width:1210px;margin-left:65px;top:10px;">';
        for($iii=0; $iii<$numgalleries; $iii++) {
            
            $id = mysql_result($galleryquery,$iii,'id');
            $name = mysql_result($galleryquery,$iii,'title');
            $about = mysql_result($galleryquery,$iii,'about');
            $avgscore = mysql_result($galleryquery,$iii,'avgscore');
            $photos = mysql_result($galleryquery,$iii,'photos');
            $owner = mysql_result($galleryquery,$iii,'owner');

            $pulltopphoto = mysql_query("SELECT source FROM photos WHERE set_id = $id ORDER BY votes DESC LIMIT 7");
            $numsetphotos = mysql_num_rows($pulltopphoto);
            
             //owner info
             $ownerinfoquery = mysql_query("SELECT user_id FROM userinfo WHERE emailaddress = '$owner'");
            $extowner = mysql_result($ownerinfoquery,0,'user_id');
            
            if($numsetphotos < 6) {
                continue;
            }
            
            echo'<div class="grid_10 gallery" style="width:375px;padding-bottom:10px;padding-top:10px;">
                     <div class="statoverlay" style="z-index:1;background-color:white;position:relative;top:0px;width:358px;height:35px;-webkit-border-radius: 2px;-moz-border-radius: 2px;border-radius: 2px;">
                        <a style="text-decoration:none;color:#333;" href="viewprofile.php?u=',$extowner,'&view=exhibits&set=',$id,'">
                        <div style="margin-top:18px;margin-left:12px;">
                          <span style=\'font-family:"helvetica neue",helvetica,arial;font-weight:200;font-size:18px;\'>',$avgscore,'&nbsp;&nbsp;',$name,'</span>         </div>
                        </a>	
                     </div>';

                $photo1 = mysql_result($pulltopphoto, 0, "source");
                $photo1 = str_replace("userphotos/","userphotos/medthumbs/",$photo1);
                $photo2 = mysql_result($pulltopphoto, 1, "source");
                $photo2 = str_replace("userphotos/","userphotos/medthumbs/",$photo2);
                $photo3 = mysql_result($pulltopphoto, 2, "source");
                $photo3 = str_replace("userphotos/","userphotos/medthumbs/",$photo3);
                $photo4 = mysql_result($pulltopphoto, 3, "source");
                $photo4 = str_replace("userphotos/","userphotos/medthumbs/",$photo4);
                $photo5 = mysql_result($pulltopphoto, 4, "source");
                $photo5 = str_replace("userphotos/","userphotos/medthumbs/",$photo5);
                $photo6 = mysql_result($pulltopphoto, 5, "source");
                $photo6 = str_replace("userphotos/","userphotos/medthumbs/",$photo6);
                
                
                echo'<a style="text-decoration:none;color:#333;" href="viewprofile.php?u=',$extowner,'&view=exhibits&set=',$id,'">
                                
                <div class="omega grid_6" style="width:210px;margin:0;margin-left:-2px;height:400px;overflow:hidden;padding-top:5px;-webkit-border-radius: 2px;-moz-border-radius: 2px;border-radius: 2px;" >	
                    <div class="pic_1" style="padding:3px;">
                        <img src="../',$photo1,'" class="gallery_pic"/>
                    </div>
                    <div class="pic_1" style="padding:3px;">
                        <img src="../',$photo2,'" class="gallery_pic"/>
                    </div>
                    <div class="pic_1" style="padding:3px;">
                        <img src="../',$photo3,'" class="gallery_pic"/>
                    </div>
                    </div>';
    
                echo'<div class="omega grid_4" style="margin:0;height:400px;overflow:hidden;padding-top:5px;-webkit-border-radius: 2px;-moz-border-radius: 2px;border-radius: 2px;">
                     <div class="pic_2" style="padding:3px;">
                        <img src="../',$photo4,'" class="gallery_pic"/>
                    </div>
                    <div class="pic_2" style="padding:3px;">
                        <img src="../',$photo5,'" class="gallery_pic"/>
                    </div>
                    <div class="pic_2" style="padding:3px;">
                        <img src="../',$photo6,'" class="gallery_pic"/>
                    </div>
                    </div>';
            
            echo'</div>
                 </a>';
        
        }
    
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

    <script src="js/bootstrap-dropdown.js"></script>
    <script type="text/javascript">  
        $(document).ready(function () {  
            $('.dropdown-toggle').dropdown();  
        });  
    </script> 
    
</body>
</html>