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

    
$query="SELECT * FROM photos ORDER BY id ASC";
$firstresult=mysql_query($query);
$numberofpics=mysql_num_rows($firstresult);

$Time;         //time is in ten hours
$Points;
$source;
$Gravity=1.8;
$secondsPerHour=3600;
$timeIncrementsInHrs=10;

$Scorequery = "UPDATE photos SET score = CASE ";

for($iii = $numberofpics-200; $iii < $numberofpics; $iii++) {
	$currenttime=time();
	$phototime=mysql_result($firstresult, $iii, "time");
	$Time=$currenttime-$phototime;
	$Time/=$secondsPerHour;
	$Time/=$timeIncrementsInHrs;
	$Points=mysql_result($firstresult, $iii, "points");
	$Score=($Points-10)/(pow($Time, $Gravity));
	
	$source=mysql_result($firstresult, $iii, "source");
	
	$Scorequery .= "WHEN source = '$source' THEN '$Score' ";
}

$Scorequery .= "END;";

//echo $Scorequery;

mysql_query($Scorequery) or die(mysql_error());

$realquery="SELECT * FROM photos ORDER BY score DESC LIMIT 0, 30";
$result=mysql_query($realquery);

//QUERY FOR NOTIFICATIONS
$currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$email'";
$currentnotsquery = mysql_query($currentnots);
$currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");

//notifications query reset 
if($currentnotsresult > 0) {
$notsquery = "UPDATE userinfo SET notifications = (notifications - 1) WHERE emailaddress = '$email6'";
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


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "https://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title>PhotoRankr - Trending Photography</title>
     <meta name="viewport" content="width=1200" /> 

  <meta name="Generator" content="EditPlus">
  <meta name="Author" content="PhotoRankr, PhotoRankr.com">
  <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
  <meta name="Description" content="An endless gallery of the latest trending photography on PhotoRankr.">

  <link rel="stylesheet" type="text/css" href="market/css/bootstrapNew.css" />
 <link rel="stylesheet" href="market/css/reset.css" type="text/css" />
  <link rel="stylesheet" href="market/css/text.css" type="text/css" />
   <link rel="stylesheet" href="css/style.css" type="text/css" />
  <link rel="stylesheet" href="960_24.css" type="text/css" />
  	<link rel="stylesheet" type="text/css" href="market/css/all.css"/>

 <script type="text/javascript">
    document.write("\<script src='//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js' type='text/javascript'>\<\/script>");
  </script>
    <script src="bootstrap-dropdown.js" type="text/javascript"></script>
  <script src="bootstrap-collapse.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/jquery.wookmark.js"></script>        

  <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
      
<style type="text/css">

.statoverlay
            {
               -moz-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
-webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
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
   
<script language="JavaScript">
var x=<?php echo $x; ?>;

function slideshowForward() {
x=x+20;
location.href="trending.php?x="+x;
}

function slideshowBackward() {
x=x-20;
location.href="trending.php?x="+x;
}

</script>
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
        
        <a class="pxbutton" style="text-decoration:none;margin-right:15px;';if($view == '') {echo'padding:10px;-moz-border-radius: 5px;-webkit-border-radius: 5px;border-radius: 5px;background-color:#000;color:#fff;opacity:.9;';} else {echo'';} echo'" href="trending.php">Trending Photos</a> 
        
        <a class="pxbutton" style="text-decoration:none;margin-right:15px;';if($view == 'prs') {echo'padding:10px;-moz-border-radius: 5px;-webkit-border-radius: 5px;border-radius: 5px;background-color:#000;color:#fff;opacity:.9;';} else {echo'';} echo'" href="trending.php?view=prs">Trending Photographers</a>
        
        </div>';
       

if($view == '') {

      
    //DISPLAY TRENDING PHOTOS
    
    echo'
    <div id="thepics" style="position:relative;margin-left:-130px;top:0px;width:1240px;">
    <div id="main" role="main">
    <ul id="tiles">';
    
for($iii=1; $iii <= 16; $iii++) {
	$image = mysql_result($result, $iii-1, "source");
    $imageThumb=str_replace("userphotos/","userphotos/medthumbs/", $image);
	$id = mysql_result($result, $iii-1, "id");
    $caption = mysql_result($result, $iii-1, "caption");
    $caption = (strlen($caption) > 23) ? substr($caption,0,20). " &#8230;" : $caption;
    $points = mysql_result($result, $iii-1, "points");
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
    if($widthls < 225) {
    $heightls = $heightls * ($heightls/$widthls);
    $widthls = 270;
    }

		echo '
        <a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$id,'&v=t"><li class="fPic" id="',$id,'" style="padding:5px;margin-right:10px;margin-top:10px;list-style-type: none;width:270px;
"><img style="-moz-border-radius: 3px;-webkit-border-radius: 3px;border-radius: 3px;" src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />

<div class="statoverlay" style="z-index:1;background-color:white;position:relative;top:0px;width:270px;height:30px;"><div style="line-spacing:1.48;padding:5px;color:#4A4A4A;"><div style="float:left;"<span style="font-size:16px;font-weight:bold;">',$score,'</span>&nbsp;&nbsp;<span style="font-weight:bold;font-size:12px;">',$caption,'</span></div><div style="float:right;"><span style="font-size:12px;">',$price,'</span></div></div><br/></div>
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

</div>
</div>

<!--AJAX CODE HERE-->
   <div class="grid_6 push_9" style="padding-top:50px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;">Loading More Photos&hellip;</div>
   </div>
   
<?php

echo'<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMoreTrendingPics").show();
				$.ajax({
					url: "loadMoreTrendingPics.php?lastPicture=" + $(".fPic:last").attr("id"),
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
</script>';    

}

elseif($view == 'prs') {
  
 echo'<div id="container" style="width:1210px;margin-left:-112px;top:15px;">';
for($iii=1; $iii <= 30; $iii++) {
    $owner = mysql_result($result, $iii-1, "emailaddress");
    $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$owner'");
    $profilepic = mysql_result($ownerquery, 0, "profilepic");
    $userid = mysql_result($ownerquery, 0, "user_id");
    $pos = strpos($prevlist, $profilepic);
    if($pos !== false) {
        continue;
    }
    $firstname = mysql_result($ownerquery, 0, "firstname");
    $lastname = mysql_result($ownerquery, 0, "lastname");
    $fullname = $firstname . " " . $lastname;
    $prevlist = $prevlist . $profilepic;
    
        
        echo '<div class="fPic" id="',$id,'" style="float:left;margin-right:20px;margin-top:20px;width:280px;height:280px;overflow:hidden;"><a style="text-decoration:none;" href="viewprofile.php?u=',$userid,'">
        
        <div class="statoverlay" style="z-index:1;left:0px;top:240px;position:relative;background-color:black;width:280px;height:70px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-family:helvetica neue,arial;font-weight:100;font-size:22px;">',$fullname,'</span></div>
        
        <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-75px;min-height:290px;min-width:280px;" src="',$profilepic,'" alt="',$fullname,'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
      
       }//end for loop
      echo'</div>';

}

?>

</div>

 </body>
</html>
