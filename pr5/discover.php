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
    
    //Get views
    if(isset($_GET['users'])){
        $users = htmlentities($_GET['users']);
    }
    if(isset($_GET['photos'])){
        $photos = htmlentities($_GET['photos']);
    }
    
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

	 <link rel="stylesheet" type="text/css" href="css/style.css"/>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="css/960grid.css"/>
	<link rel="stylesheet" type="text/css" href="css/reset.css"/>  
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.wookmark.js"></script>            
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
  
  <title>PhotoRankr - Discover Photography</title>

<style type="text/css">

.statoverlay

{
background-attachment: scroll;
background-clip: border-box;
background-color: 
rgba(0, 0, 0, 0.848438);
background-image: none;
background-origin: padding-box;
bottom: 0px;
color: 
rgb(255, 255, 255);
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
width: 240px;
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
<body style="overflow-x:hidden; background-color: #222;">

<?php navbar(); ?>

   <!--big container-->
    <div id="container" class="container_24" style="width:1200px;">
    
    <div style="font-size:44px;font-weight:300;margin-left:60px;margin-top:60px;color:#fff;">Discover Amazing Photography</div>
    
<?php
    
    $result = mysql_query("SELECT * FROM photos WHERE faves > 6 ORDER BY RAND() LIMIT 0,16 ");
    
    if($users == '' && $keyword == '') {

echo'
    <div id="thepics" style="position:relative;top:45px;left:40px;width:750px;float:left;">
    <div id="main">
    <ul id="tiles">';
        
for($iii=1; $iii <= 16; $iii++) {
	$image = mysql_result($result, $iii-1, "source");
    $imageThumb=str_replace("userphotos/","userphotos/medthumbs/", $image);
    $image = "../" . $image;
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
    $heightls = $height / 3.3;
    $widthls = $width / 3.3;
    if($widthls < 235) {
    $heightls = $heightls * ($heightls/$widthls);
    $widthls = 245;
    }

		echo '
        <a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$id,'&v=n"><li class="fPic" id="',$id,'" style="list-style-type: none;width:240px;"><img style="min-width:240px;" src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />
        
            <div class="statoverlay" style="top:0px;height:50px;position:relative;">
            <p style="font-weight:100;font-size:20px;padding-top:15px;padding-left:5px;">',$caption,'</p>
            <p style="font-weight:100;font-size:16px;margin-top:20px;padding-left:5px;">',$score,'<span style="font-size:14px;">/10.0</span></p>
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
        offset: 4, // Optional, the distance between grid items
        itemWidth: 240 // Optional, the width of a grid item
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
</script>

<!--Search/Discover Button-->
<div style="float:left;position:fixed;margin-left:850px;margin-top:120px;">
        <form method="GET">
            <input id="searchBox" name="searchword" placeholder="Begin your search&hellip;" type="text" />
        </form>
            <br /><div style="color:#fff;font-size:25px;text-align:center;padding-bottom:5px;font-weight:300;">or</div><br />
    <a class="btn btn-primary" style="padding:15px;width:275px;font-size:23px;font-weight:300;" href="#">Click to Discover</a>
</div>';

} //end of view == ''

elseif($photos != ''){

    $result = mysql_query("SELECT * FROM photos WHERE concat(caption,location,camera,tag1,tag2,tag3,tag4) LIKE '%$photos%' ORDER BY views DESC LIMIT 0,16 ");
        
    echo'
    <div id="thepics" style="position:relative;left:-50px;top:20px;width:1150px;">
    <div id="main" role="main">
    <ul id="tiles">';
    
for($iii=1; $iii <= 16; $iii++) {
	$image = mysql_result($result, $iii-1, "source");
    $image = '../' . $image;
    $imageThumb=str_replace("../userphotos/","../userphotos/medthumbs/", $image);
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
    $views = mysql_result($result, $iii-1, "views");
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
    if($widthls < 225) {
    $heightls = $heightls * ($heightls/$widthls);
    $widthls = 250;
    }

		echo '
        <a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$id,'&v=n"><li class="fPic" id="',$views,'" style="padding:5px;margin-right:10px;margin-top:10px;list-style-type: none;width:250px;
"><img style="-moz-border-radius: 3px;-webkit-border-radius: 3px;border-radius: 3px;" src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />

<div class="statoverlay" style="z-index:1;background-color:white;position:relative;top:0px;width:250px;height:30px;"><div style="line-spacing:1.48;padding:5px;color:#4A4A4A;"><div style="float:left;"<span style="font-size:16px;font-weight:bold;">',$score,'</span>&nbsp;&nbsp;<span style="font-weight:bold;font-size:12px;">',$caption,'</span></div><div style="float:right;"><span style="font-size:12px;">',$price,'</span></div></div><br/></div>
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
        itemWidth: 270 // Optional, the width of a grid item
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
					url: "loadMoreDiscover.php?lastPicture=" + $(".fPic:last").attr("id")+"&keyword=',$photos,'",
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

} //end of photos view

elseif($users != '') {
        
        $searchterm = $users;
        $searchterm = explode(" ",$searchterm);
        $query =  mysql_query("SELECT * FROM userinfo WHERE firstname LIKE '%".$searchterm[0]."%' AND lastname  LIKE '%".$searchterm[1]."%' OR lastname LIKE '%".$searchterm[0]."%'");
        $numresults = mysql_num_rows($query);
        echo'<div style="font-size:18px;margin-top:10px;padding:15px;font-weight:lighter;">Photographers | ',$numresults,' Results</div>'; 

        echo'<div class="grid_20">';

        for($iii=0; $iii<$numresults; $iii++) {
            $photographer = mysql_result($query,$iii,'firstname')." ".mysql_result($query,$iii,'lastname');
            $profilepic = mysql_result($query,$iii,'profilepic'); 
            $userid = mysql_result($query,$iii,'user_id'); 
            $reputation = mysql_result($query,$iii,'reputation'); 
            $useremail = mysql_result($query,$iii,'emailaddress'); 
            $followersquery="SELECT * FROM userinfo WHERE following LIKE '%$useremail%'";
            $followersresult=mysql_query($followersquery);
            $numberfollowers = mysql_num_rows($followersresult);
            $userphotos="SELECT * FROM photos WHERE emailaddress = '$useremail' ORDER BY (points/votes) DESC";
            $userphotosquery=mysql_query($userphotos);
            $profileimage = mysql_result($userphotosquery,0,'source'); 
            $profileimage = str_replace('userphotos/','userphotos/thumbs/',$profileimage);
            $profileimage2 = mysql_result($userphotosquery,1,'source');
            $profileimage2 = str_replace('userphotos/','userphotos/thumbs/',$profileimage2);
            $profileimage3 = mysql_result($userphotosquery,2,'source');
            $profileimage3 = str_replace('userphotos/','userphotos/thumbs/',$profileimage3);
            $profileimage4 = mysql_result($userphotosquery,3,'source');
            $profileimage4 = str_replace('userphotos/','userphotos/thumbs/',$profileimage4);
            $numphotos=mysql_num_rows($userphotosquery);
                for($ii = 0; $ii < $numphotos; $ii++) {
                    $points = mysql_result($userphotosquery, $ii, "points");
                    $votes = mysql_result($userphotosquery, $ii, "votes");
                    $totalfaves = mysql_result($userphotosquery, $ii, "faves");
                    $portfoliopoints+=$points;
                    $portfoliovotes+=$votes;
                    $portfoliofaves+=$totalfaves;
                }
                if($portfoliovotes > 0) {
                    $portfolioranking=($portfoliopoints/$portfoliovotes);
                    $portfolioranking=number_format($portfolioranking, 2, '.', '');
                }
                elseif($portfoliovotes = 0){$portfolioranking="N/A";}
            
            echo'<div style="clear:both;border-bottom:1px solid #ccc;"><div style="padding:15px;float:left;"><img src="../',$profilepic,'" height="100" width="100" alt="',$photographer,'" />';
                            
                if($reputation > 60) {
                    echo'<img style="margin-top:-10px;margin-left:10px;" src="../graphics/toplens.png" height="75" />';
                }
            
        echo'
            </div><div style="float:left;margin-top:15px;"><a style="color:#3e608c;font-weight:bold;font-size:14px;" href="viewprofile.php?u=',$userid,'">',$photographer,'</a><br />Reputation: ',$reputation,'<br />Followers: ',$numberfollowers,'<br />Portfolio Ranking: ',$portfolioranking,'<br />Favorites: ',$portfoliofaves,'</div><div style="float:left;margin-top:15px;margin-left:30px;">';if($numphotos > 3){echo'<img style="padding:3px;" src="../',$profileimage,'" height="100" width="100" /><img style="padding:3px;" src="../',$profileimage2,'" height="100" width="100" /><img style="padding:3px;" src="../',$profileimage3,'" height="100" width="100" /><img style="padding:3px;" src="../',$profileimage4,'" height="100" width="100" />';}echo'</div><hr></div>';
        }
        
        echo'</div>';

} //end of users view

?>

</div><!--end of container-->

    <script src="js/bootstrap-dropdown.js"></script>
    <script type="text/javascript">  
        $(document).ready(function () {  
            $('.dropdown-toggle').dropdown();  
        });  
    </script> 

<?php footer(); ?>    
    
</body>
</html>