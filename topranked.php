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
    
$query="SELECT * FROM photos ORDER BY points DESC LIMIT 0, 20";
$result=mysql_query($query);
$numberofpics=mysql_num_rows($result);

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


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
     <meta name="viewport" content="width=1200" /> 
 <meta name="Generator" content="EditPlus">
  <meta name="Author" content="PhotoRankr, PhotoRankr.com">
  <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
  <meta name="Description" content="PhotoRankr allows photographers of all skill levels to sell and share their work. Create your photostream cutomized to what you want to see. Add photos to your favorites, rank them, and watch them trend. Build your portfolio with Photorankr.">

</script>
  <link rel="stylesheet" type="text/css" href="market/css/bootstrapNew.css" />
  <link rel="stylesheet" href="market/css/reset.css" type="text/css" />
  <link rel="stylesheet" href="market/css/text.css" type="text/css" />
  <link rel="stylesheet" href="css/style.css" type="text/css" />
  <link rel="stylesheet" href="960_24.css" type="text/css" />
  <link rel="stylesheet" type="text/css" href="market/css/all.css"/>
  <script type="text/javascript" href="js/bootstrap-dropdown.js"></script>
  <script type="text/javascript">
    document.write("\<script src='//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js' type='text/javascript'>\<\/script>");
  </script>
  <script type="text/javascript" src="js/jquery.wookmark.js"></script>        
  <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>

<title>Top Ranked</title>

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

</head>

<body oncontextmenu="return false;"  style="overflow-x:hidden; background-color: #eeeff3">

<?php navbarnew();  ?>

    <div id="container" class="container_24" >

<!--TOP PHOTOGRAPHY-->
    
<?php

$view = htmlentities($_GET['v']);

if(isset($_GET['t'])){
		$timesetting = $_GET['t'];
	}


               echo'<br /><br /><br /><br />
        <div style="margin-left:-70px;font-size:15px;font-weight:200;font-family:"Helvetica Neue",Helvetica,Arial;">
        
        <a class="pxbutton" style="text-decoration:none;margin-right:15px;';if($view == '') {echo'padding:10px;-moz-border-radius: 5px;-webkit-border-radius: 5px;border-radius: 5px;background-color:#000;color:#fff;opacity:.9;';} else {echo'';} echo'" href="topranked.php">Top Photos</a> 
        
        <a class="pxbutton" style="text-decoration:none;margin-right:15px;';if($view == 'prs') {echo'padding:10px;-moz-border-radius: 5px;-webkit-border-radius: 5px;border-radius: 5px;background-color:#000;color:#fff;opacity:.9;';} else {echo'';} echo'" href="topranked.php?v=prs">Top Photographers</a>
        
         <a class="pxbutton" style="text-decoration:none;margin-right:15px;';if($view == 'ex') {echo'padding:10px;-moz-border-radius: 5px;-webkit-border-radius: 5px;border-radius: 5px;background-color:#000;color:#fff;opacity:.9;';} else {echo'';} echo'" href="topranked.php?v=ex">Top Exhibits</a>
        
        </div>
        
                <script>
                    function submitTime(sel) {
                        sel.form.submit();
                    }
                </script>';
                
                if($view == '') {
                echo'            
                    <!-- Select Basic -->
                    <label class="control-label"></label>
                    <div class="controls">
                    <form action="topranked.php" method="get">';
                    
                    if($timesetting == '') {
                        echo'
                        <select name="t"  onchange="submitTime(this)" class="input-large" style="width:110px;margin-left:340px;margin-top:-25px;">
                        <option value="" selected value="">All Time</option>
                        <option value="m">This Month</option>
                        <option value="w">This Week</option>
                        </select>';
                     }   
                     elseif($timesetting == 'm') {
                        echo'
                        <select name="t"  onchange="submitTime(this)" class="input-large" style="width:110px;margin-left:420px;margin-top:-35px;">
                        <option value="">All Time</option>
                        <option value="m" selected value="" >This Month</option>
                        <option value="w">This Week</option>
                        </select>';
                     }  
                     elseif($timesetting == 'w') {
                        echo'
                        <select name="t"  onchange="submitTime(this)" class="input-large" style="width:110px;margin-left:420px;margin-top:-35px;">
                        <option value="">All Time</option>
                        <option value="m" >This Month</option>
                        <option value="w" selected value="" >This Week</option>
                        </select>';
                     }  
                    
                    echo'    
                    </form>
                    </div>';
                }    
        

if ($view=='') {

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
    <div id="thepics" style="position:relative;margin-left:-130px;top:-15px;width:1240px;">
    <div id="main" role="main">
    <ul id="tiles">';

for($iii=1; $iii <= 20; $iii++) {
$image = mysql_result($result, $iii-1, "source");
$imageThumb = str_replace("userphotos/","userphotos/medthumbs/", $image);
$caption = mysql_result($result, $iii-1, "caption");
$id = mysql_result($result, $iii-1, "id");
$price = mysql_result($result, $iii-1, "price");
$points = mysql_result($result, $iii-1, "points");
$emailaddress = mysql_result($result, $iii-1, "emailaddress");
$namequery="SELECT * FROM userinfo WHERE emailaddress='$emailaddress'";
$nameresult=mysql_query($namequery);
$row=mysql_fetch_array($nameresult);
$firstname=$row['firstname'];
$lastname=$row['lastname'];
$fullname = $firstname . " " . $lastname;
$fullname = ucwords($fullname);
	
    list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
    $heightls = $height / 4.3;
    $widthls = $width / 4.3;
    if($widthls < 225) {
    $heightls = $heightls * ($heightls/$widthls);
    $widthls = 270;
    }
		echo '
        <a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$id,'&v=r"><li class="fPic photobox" id="',$points,'" style="padding:5px;margin-right:10px;margin-top:10px;list-style-type: none;width:270px;
"><img src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" /><div style="padding:3px;"><div style="float:left;">',$caption,'</div><div style=float:right;font-size:13px;font-weight:500;">$',$price,'</div></div></li></a>';
       

        
    } //end of for loop

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
    
if ($timesetting == '') {

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
					url: "loadMoreTopRanked.php?lastPicture=" + $(".fPic:last").attr("id"),
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

} //end of if
    
} //end of all time if clause  


elseif ($view=='prs') {
//TOP 20 PHOTOGRAPHERS



//get number of photographers with score greater than 700
$query = "SELECT * FROM userinfo WHERE totalscore > 700 AND emailaddress NOT IN ('msniff16@gmail.com','sniff06@aol.com')";
$queryresult = mysql_query($query);
$numresult = mysql_num_rows($queryresult);


//nested for loop get photos from an individual user
for($iii=0; $iii < $numresult; $iii++) {
$owner = mysql_result($queryresult, $iii, "emailaddress");
$tpoints = mysql_result($queryresult, $iii, "totalpoints");
$photocheck = "SELECT * FROM photos WHERE emailaddress = '$owner' ORDER BY (points/votes) DESC";
$photocheckrun = mysql_query($photocheck);
$numphotos = mysql_num_rows($photocheckrun);

//select and calculate score for users with number of photos greater than 16
for($ii=0; $ii < 15; $ii++) {
$singlescore = mysql_result($photocheckrun, $ii, "points");
$votes = mysql_result($photocheckrun, $ii, "votes");
$totalpoints += $singlescore;
$totalvotes += $votes;
    }
    
    $finalaverage = ($totalpoints/$totalvotes);
    
    $averagearray[$iii] =  $finalaverage;
    $emailaddressarray[$iii] = $owner;

} 

//end of for where totalscore > 700

for($i = 0; $i < sizeof($averagearray); $i++){
array_multisort($averagearray,$emailaddressarray);
}


echo'<div id="container" style="width:1140px;position:relative;left:-70px;top:20px;">';
    for($iii=1; $iii <= 20; $iii++) {
    $newquery = "SELECT * FROM userinfo WHERE emailaddress = '$emailaddressarray[$iii]'";
$firstname = mysql_result($queryresult, $iii-1, "firstname");
$user_id = mysql_result($queryresult, $iii-1, "user_id");
$lastname = mysql_result($queryresult, $iii-1, "lastname");
$fullname = $firstname . " " . $lastname;
$fullname = ucwords($fullname);
$profilepic = mysql_result($queryresult, $iii-1, "profilepic");
if($profilepic == 'https://www.photorankr.com/profilepics/default_profile.jpg'){
$profilepic = 'profilepics/default_profile.jpg';
}

echo '<div class="fPic" id="',$id,'" style="float:left;margin-right:20px;margin-top:20px;width:260px;height:260px;overflow:hidden;"><a style="text-decoration:none;" href="viewprofile.php?u=',$user_id,'">
        
        <div class="statoverlay" style="z-index:1;left:0px;top:215px;position:relative;background-color:black;width:260px;height:70px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-family:helvetica neue,arial;font-weight:100;font-size:22px;">#',$iii,'&nbsp;&nbsp;',$fullname,'</span></div>
        
        <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-75px;min-height:280px;min-width:260px;" src="',$profilepic,'" alt="',$fullname,'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
        
    } //end of for loop
echo'</div>'; 
        
} //end of elseif clause


elseif ($view=='ex') {

echo'<div id="container" style="width:1140px;position:relative;left:-70px;top:20px;">';
    for($iii=1; $count < 20; $iii++) {
    $exquery = "SELECT * FROM sets ORDER BY avgscore DESC";
    $exqueryrun = mysql_query($exquery);
    $owner = mysql_result($exqueryrun, $iii-1, "owner");

$exinfo = "SELECT * FROM userinfo WHERE emailaddress = '$owner'"; 
$exinforun = mysql_query($exinfo);
$firstname = mysql_result($exinforun, 0, "firstname");
$lastname = mysql_result($exinforun, 0, "lastname");
$fullname = $firstname . " " . $lastname;
$fullname = ucwords($fullname);
$user_id = mysql_result($exinforun, 0, "user_id");
$exhibit_id = mysql_result($exqueryrun, $iii-1, "id");
$caption = mysql_result($exqueryrun, $iii-1, "title");
$caption = (strlen($caption) > 24) ? substr($caption,0,21). " &#8230;" : $caption;
$coverpic = mysql_result($exqueryrun, $iii-1, "cover");
if($coverpic == '') {
    continue;
    }
   $count += 1; 

    echo'
    <div class="fPic" id="',$id,'" style="float:left;margin-right:20px;margin-top:20px;width:260px;height:260px;overflow:hidden;"><a style="text-decoration:none;" href="viewprofile.php?u=',$user_id,'&view=exhibits&set=',$exhibit_id,'">
        
        <div class="statoverlay" style="z-index:1;left:0px;top:205px;position:relative;background-color:black;width:260px;height:70px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-family:helvetica neue,arial;font-weight:100;font-size:18px;">#',$count,'&nbsp;&nbsp;',$caption,'</span><br/><span style="font-family:helvetica,arial;font-weight:100;font-size:12px;">By: ',$fullname,'</p></div>
        
        <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-75px;min-height:280px;min-width:260px;" src="https://photorankr.com/',$coverpic,'" alt="',$caption,'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
    
        
    } //end of for loop
echo'</div>'; 

        
} //end of elseif clause


?>


</body>
</html>