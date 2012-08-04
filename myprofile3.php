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
  
  
  //GRAB USER INFORMATION
  $userquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$email'");
  $profilepic = mysql_result($userquery,0,'profilepic'); 
  $email = mysql_result($userquery,0,'emailaddress'); 
  $fullname = mysql_result($userquery,0,'firstname')." ".mysql_result($userquery,0,'lastname'); 
  $age = mysql_result($userquery,0,'age');
  $gender = mysql_result($userquery,0,'gender');
  $location = mysql_result($userquery,0,'location');
  $camera = mysql_result($userquery,0,'camera');
  $about = mysql_result($userquery,0,'about');
  $quote = mysql_result($userquery,0,'quote');
  $fbook = mysql_result($userquery,0,'fbook');
  $twitter = mysql_result($userquery,0,'twitter');
  $faves = mysql_result($userquery,0,'faves');
  $background = mysql_result($userquery,0,'background');
  $background = str_replace('userphotos/','userphotos/medthumbs/',$background);
  
  $view = htmlentities($_GET['view']);
  
  //UPDATE BACKGROUND IMAGE
  if($_GET['mode'] == 'updatebackground') {
  
        $newbg = $_POST['checked'];
        $newbgquery = mysql_query("UPDATE userinfo SET background = '$newbg' WHERE emailaddress = '$email'");
        
    }
  
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

 <meta name="Generator" content="EditPlus">
  <meta name="Author" content="PhotoRankr, PhotoRankr.com">
  <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
  <meta name="Description" content="A gallery of the newest photography, photographers, and exhibits on PhotoRankr.">
     <meta name="viewport" content="width=1200" /> 

  <link rel="stylesheet" type="text/css" href="bootstrapnew.css" />
  <link rel="stylesheet" href="reset.css" type="text/css" />
  <link rel="stylesheet" href="text.css" type="text/css" />
  <link rel="stylesheet" href="960_24.css" type="text/css" />
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script src="bootstrap.js" type="text/javascript"></script>
  <script src="bootstrap-dropdown.js" type="text/javascript"></script>
  <script src="bootstrap-collapse.js" type="text/javascript"></script>
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
opacity:.6;
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
            
.statoverlay:hover
{
opacity:.6;
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
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
  
</script>


</head>
<body style="overflow-x:hidden;background-image:url('');background-position: center top;background-size:100%;background-repeat:no-repeat;background-attachment:fixed;">

<?php navbar(); ?>  

<div class="container_24"><!--START CONTAINER-->

<!--LEFT SIDEBAR-->
<div class="grid_24" style="width:1120px;">



<div class="grid_4 pull_1 rounded" style="background-color:#eeeff3;position:relative;top:80px;height:500px;width:250px;">

<div style="width:240px;height:140px;">
<div class="circle" style="float:left;overflow:hidden;margin-left:15px;margin-top:15px;">
<img src="<?php echo $profilepic; ?>" height="120" width="120"/>
</div>
<a class="btn btn-success" style="float:left;width:70px;margin-top:40px;margin-left:10px;font-size:14px;font-weight:150;" href="myprofile3.php?view=upload">Upload</a>
<a class="btn btn-primary" style="float:left;width:70px;margin-top:7px;margin-left:10px;font-size:14px;font-weight:150;" href="myprofile3.php?view=promote">Promote</a>
</div>

<div style="width:250px;margin-top:0px;">
<div style="font-size:18px;text-align:center;font-weight:200;"><?php echo $fullname; ?></div>
</div>

<div style="width:250px;height:70px;margin-top:0px;">

</div>

<a style="text-decoration:none;color:black;font-weight:100;" href="myprofile3.php?view=info"><div style="width:250px;border-top:dotted;margin-top:10px;">
<span class="green" style="text-align:center;font-size:24px;padding-left:15px;">Info&nbsp;&nbsp;<img style="float:right;padding-top:5px;padding-right:20px;" src="graphics/info.png" height="30" width="30"></span>
</div></a>

<a style="text-decoration:none;color:black;font-weight:100;" href="myprofile3.php?view=network"><div style="width:250px;border-top:dotted;margin-top:10px;">
<span class="green" style="text-align:center;font-size:24px;padding:15px;">Network&nbsp;&nbsp;<img style="float:right;padding-top:5px;padding-right:20px;" src="graphics/info.png" height="30" width="30"></span>
</div></a>

<a style="text-decoration:none;color:black;font-weight:100;" href="myprofile3.php?view=favorites"><div style="width:250px;border-top:dotted;margin-top:10px;">
<span class="green" style="text-align:center;font-size:24px;padding:15px;">Favorites&nbsp;&nbsp;<img style="float:right;padding-top:5px;padding-right:20px;" src="graphics/info.png" height="30" width="30"></span>
</div></a>

<a style="text-decoration:none;color:black;font-weight:100;" href="myprofile3.php?view=messages"><div style="width:250px;border-top:dotted;margin-top:10px;">
<span class="green" style="text-align:center;font-size:24px;padding:15px;">Messages&nbsp;&nbsp;<img style="float:right;padding-top:5px;padding-right:20px;"src="graphics/messages.png" height="30" width="30"></span>
</div></a>

<a style="text-decoration:none;color:black;font-weight:100;" href="myprofile3.php?view=settings"><div style="width:250px;border-top:dotted;margin-top:10px;">
<span class="green" style="text-align:center;font-size:24px;padding:15px;">Settings&nbsp;&nbsp;<img style="float:right;padding-top:5px;padding-right:20px;"src="graphics/messages.png" height="30" width="30"></span>
</div></a>

</div><!--end 4 grid-->

<div class="grid_18 roundedright" style="background-color:#eeeff3;height:60px;margin-top:80px;width:830px;margin-left:-45px;">

<a style="text-decoration:none;color:black;" href="myprofile3.php"><div class="clicked" style="width:180px;height:60px;border-right:1px solid #ccc;border-left:1px solid #ccc;float:left;<?php if($view == '') {echo'background-color:#bbb;color:white;';} ?>"><div style="font-size:25px;font-weight:100;margin-top:10px;text-align:center;">Portfolio</div></div></a>

<a style="text-decoration:none;color:black;" href="myprofile3.php?view=store"><div class="clicked" style="width:180px;height:60px;border-right:1px solid #ccc;float:left;<?php if($view == 'store') {echo'background-color:#bbb;color:white;';} ?>"><div style="font-size:25px;font-weight:100;margin-top:10px;text-align:center;">Store</div></div></a>

<a style="text-decoration:none;color:black;" href="myprofile3.php?view=blog"><div class="clicked" style="width:180px;height:60px;border-right:1px solid #ccc;float:left;<?php if($view == 'blog') {echo'background-color:#bbb;color:white;';} ?>"><div style="font-size:25px;font-weight:100;margin-top:10px;text-align:center;">Blog</div></div></a>

<div style="width:180px;height:60px;float:left;"><div style="font-size:25px;font-weight:100;margin-top:6px;text-align:center;">
<form class="navbar-search" action="myprofile3.php?view=search" method="post">
<input class="search" style="position:relative;margin-left:15px;margin-top:2px;" name="searchterm" type="text">
</form></div></div>


<?php

    if($view == '') {
    
        $option = htmlentities($_GET['option']);    
    
        echo'<br /><br /><br /><br /><div style="width:760px;text-align:center;font-size:14px;font-weight:200;"><div style="margin-left:20px;"><a class="green" style="text-decoration:none;color:#333;" href="myprofile3.php">Newest</a> | <a class="green" style="text-decoration:none;color:#333;" href="myprofile3.php?option=top">Top Ranked</a> | <a class="green" style="text-decoration:none;color:#333;" href="myprofile3.php?option=fave">Most Favorited</a> | <a class="green" style="text-decoration:none;color:#333;" href="myprofile3.php?view=exhibits">Exhibits</a></div></div>';
        
        if($option == '') {        
        $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$email' ORDER BY id DESC LIMIT 0,21");
        $numresults = mysql_num_rows($query);
        }
        
        elseif($option == 'top') {
        $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$email' AND views > 20 ORDER BY (points/votes) DESC LIMIT 0,21");
        $numresults = mysql_num_rows($query);
        }
                
        elseif($option == 'fave') {
        $query = mysql_query("SELECT * FROM photos WHERE emailaddress = '$email' ORDER BY faves DESC LIMIT 0,21");
        $numresults = mysql_num_rows($query);
        }
        
        echo'<div id="thepics">';
        echo'<div id="container" class="grid_18" style="width:770px;margin-top:-40px;margin-left:-10px;padding:35px;background-color:rgba(245,245,245,0.6);">';

        for($iii=0; $iii < $numresults; $iii++) {
              
                $image[$iii] = mysql_result($query, $iii, "source");
                $imageThumb[$iii] = str_replace("userphotos/","../userphotos/medthumbs/", $image[$iii]);
                $id = mysql_result($query, $iii, "id");
                $caption = mysql_result($query, $iii, "caption");
                $points = mysql_result($query, $iii, "points");
                $votes = mysql_result($query, $iii, "votes");
                $faves = mysql_result($query, $iii, "faves");
                $score = number_format(($points/$votes),2);
                $faveemail = mysql_result($query, $iii, "emailaddress");
                $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$faveemail'");
                $firstname = mysql_result($query, 0, "firstname");
                $lastname = mysql_result($query, 0, "lastname");
                $reputation = mysql_result($query, 0, "lastname");
                $fullname = $firstname . " " . $lastname;
                list($width, $height) = getimagesize($image);
                $imgratio = $height / $width;
                $heightls = $height / 3.5;
                $widthls = $width / 3.5;

                echo '   

                <div class="fPic" id="',$id,'" style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="http://photorankr.com/fullsize.php?image=', $image[$iii], '">

                <div class="statoverlay" style="z-index:1;left:0px;top:155px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:16px;font-weight:100;">',$caption,'</span><br><span style="font-size:14px;font-weight:100;">Score: ',$score,'<br>Favorites: ',$faves,'</span></p></div>

                <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:245px;min-width:245px;" src="http://www.photorankr.com/',$imageThumb[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
	    
                } //end for loop      
        
        echo'</div>';
        echo'</div>';
        
        //AJAX CODE HERE
echo'
   <div class="grid_6 push_9" style="top:20px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;">Loading More Photos&hellip;</div>
   </div>';


echo '<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMorePortfolioPics").show();
				$.ajax({
					url: "loadMorePortfolioPics3.php?lastPicture=" + $(".fPic:last").attr("id"),
					success: function(html) {
						if(html) {
							$("#thepics").append(html);
							$("div#loadMorePortfolioPics").hide();
						}
					}
				});
				last = $(".fPic:last").attr("id");
			}
		}
	});
</script>';
    

        }
        
    
    elseif($view == 'exhibits') {
    
    echo'<br /><br /><br /><br /><div style="width:760px;text-align:center;font-size:14px;font-weight:200;"><div style="margin-left:20px;"><a class="green" style="text-decoration:none;color:#333;" href="myprofile3.php">Newest</a> | <a class="green" style="text-decoration:none;color:#333;" href="myprofile3.php?option=top">Top Ranked</a> | <a class="green" style="text-decoration:none;color:#333;" href="myprofile3.php?option=fave">Most Favorited</a> | <a class="green" style="text-decoration:none;color:#333;" href="myprofile3.php?view=exhibits">Exhibits</a></div></div>';


        if(isset($_GET['set'])){
		$set = mysql_real_escape_string($_GET['set']);
	}
    
    //get exhibit mode
if(isset($_GET['mode'])){
		$mode = ($_GET['mode']);
	}

if($mode == 'added') {
//add checked photos to existing exhibit

if(!empty($_POST['addthese'])) {
    foreach($_POST['addthese'] as $checked) {
        //insert each checked photo into corresponding set
        $checkedset = "UPDATE photos SET set_id = '$set' WHERE source = '$checked'";
        $checkedsetrun = mysql_query($checkedset);
        }
        }
	
echo'<span style="position:relative;margin-top:-130px;font-size: 16px;"><span class="label label-success" style="font-size:16px;" >Your exhibits have been updated successfully!</span><br /><br /><a href="myprofile.php?ex=y">Click here to view them</a><br /><br /></span>';
}

if($mode == 'coverchanged') {
//edit existing exhibit

    $newcaption = mysql_real_escape_string($_POST['caption']);
    $newaboutset = mysql_real_escape_string($_POST['aboutset']);
    $newcover = mysql_real_escape_string($_POST['addthis']);
    
    $exhibitchange = "UPDATE [sets] SET (title = '$newcaption', about = '$newaboutset', cover = '$newcover') WHERE id = '$set' AND owner = '$email'";
    $exhibitrun = mysql_query($exhibitchange);
        	
echo'<span style="position:relative;margin-top:-130px;font-size: 16px;"><span class="label label-success" style="font-size:16px;" >Your exhibit has been updated successfully!</span><br /><br /><a href="myprofile.php?ex=y">Click here to view exhibits</a><br /><br /></span>';
}

//select all exhibits of user
$allsetsquery = "SELECT * FROM sets WHERE owner = '$email'";
$allsetsrun = mysql_query($allsetsquery);
$numbersets = mysql_num_rows($allsetsrun);
echo'<div style="margin-top:-60px">';

if($numbersets == 0) {
echo'<div class="well grid_6 push_4" style="font-size:16px;width:270px;"><a href="myprofile.php?view=upload&cs=n">Click here to create your first exhibit</a></div>'; 
}

if($set == '' & $numbersets > 0) {

echo'<div class="grid_18" style="width:770px;margin-top:20px;margin-left:-10px;padding:35px;background-color:rgba(245,245,245,0.6);"><a href="myprofile3.php?view=upload&cs=n"><button class="btn btn-success">Create New Exhibit</button></a><br /><br />
'; 

for($iii=0; $iii < $numbersets; $iii++) {
$setname[$iii] = mysql_result($allsetsrun, $iii, "title");
$setcover = mysql_result($allsetsrun, $iii, "cover");
$set_id[$iii] = mysql_result($allsetsrun, $iii, "id");
$setname2[$iii] = (strlen($setname[$iii]) > 30) ? substr($setname[$iii],0,27). " &#8230;" : $setname[$iii];
if($setcover == '') {
$setcover = "profilepics/nocoverphoto.png";
}
        list($width, $height) = getimagesize($setcover);
        $imgratio = $height / $width;
        $heightls = $height / 3.5;
        $widthls = $width / 3.5;
        
//grab all photos in the exhibit
$grabphotos = "SELECT * FROM photos WHERE emailaddress = '$email' AND set_id = '$set_id[$iii]'";
$grabphotosrun = mysql_query($grabphotos);
$numphotosgrabbed = mysql_num_rows($grabphotosrun);


    echo'<div style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="myprofile3.php?view=exhibits&set=',$set_id[$iii],'">

    <div class="statoverlay" style="z-index:1;left:0px;top:200px;position:relative;background-color:black;width:245px;height:70px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:16px;font-weight:100;">',$setname2[$iii],'</span><br><span style="font-size:14px;font-weight:100;">Number Photos: ',$numphotosgrabbed,'<br></span></p></div>

    <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:265px;min-width:245px;" src="http://www.photorankr.com/',$setcover,'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
    
} //end of set == '' view
echo'</div>';

} //end of set == '' view


elseif($set != '') {
//get exhibit mode
if(isset($_GET['mode'])){
		$mode = ($_GET['mode']);
	}
if($mode == '') {
//grab all photos in the exhibit
$grabphotos = "SELECT * FROM photos WHERE emailaddress = '$email' AND set_id = '$set'";
$grabphotosrun = mysql_query($grabphotos);
$numphotosgrabbed = mysql_num_rows($grabphotosrun);

//grab about this set
$aboutset = "SELECT * FROM sets WHERE owner = '$email' AND id = '$set' LIMIT 0,1";
$aboutsetrun = mysql_query($aboutset);
$aboutarray = mysql_fetch_array($aboutsetrun);
$aboutset = $aboutarray['about'];
$settitle = $aboutarray['title'];
$setcover = $aboutarray['cover'];
if($setcover == '') {
$setcover = 'profilepics/nocoverphoto.png';
}

echo'<div class="grid_18" style="width:770px;margin-top:20px;margin-left:-10px;padding:35px;background-color:rgba(245,245,245,0.6);">

<div class="well grid_14" style="width:735px;font-size:16px;line-height:25px;margin-top:15px;"><u>Exhibit:</u> "',$settitle,'"<br />
<br /><u>About this exhibit:</u> ',$aboutset,'<br /><br />
<a data-toggle="modal" data-backdrop="static" href="#add"><button class="btn btn-success">Add Photos to Exhibit</button></a>&nbsp;&nbsp;
<a data-toggle="modal" data-backdrop="static" href="#editexhibit"><button class="btn btn-success">Edit Exhibit</button></a></div>';


for($iii=0; $iii < $numphotosgrabbed; $iii++) {
    $insetname[$iii] = mysql_result($grabphotosrun, $iii, "caption");
    $insetsource[$iii] = mysql_result($grabphotosrun, $iii, "source");
    $newsource = str_replace("userphotos/","userphotos/medthumbs/", $insetsource[$iii]);
    $caption = mysql_result($grabphotosrun, $iii, "caption");
    $faves = mysql_result($grabphotosrun, $iii, "faves");
    $points = mysql_result($grabphotosrun, $iii, "points");
    $votes = mysql_result($grabphotosrun, $iii, "votes");
    $score = number_format(($points/$votes),2);
    
            list($width, $height) = getimagesize($insetsource[$iii]);
            $imgratio = $height / $width;
            $heightls = $height / 3.5;
            $widthls = $width / 3.5;
                
    echo'<div style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="fullsizeme.php?image=',$insetsource[$iii],'">

    <div class="statoverlay" style="z-index:1;left:0px;top:180px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:16px;font-weight:100;">',$caption,'</span><br><span style="font-size:14px;font-weight:100;">Score: ',$score,'<br>Favorites: ',$faves,'</span></p></div>

    <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:265px;min-width:245px;" src="',$newsource,'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
 
    } //end for loop

    echo'</div>';
    echo'</div>';

   } //end of no exhibit mode
   
   }
   
   
   
        //Add Photos to Exhibit Modal

echo'<div class="modal hide fade" id="add" style="overflow-y:scroll;overflow-x:hidden;">

<div class="modal-header">
<a style="float:right" class="btn btn-primary" data-dismiss="modal">Close</a>
<img style="margin-top:-4px;" src="graphics/logoteal.png" height="28" width="90" />&nbsp;&nbsp;<span style="font-size:16px;">Add photos to your exhibit below:</span>
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:550px;height:500px;overflow-x:hidden;">
		
<img style="border: 1px solid black;margin-left:10px;margin-top:10px;" src="',$setcover,'" 
height="100px" width="100px" />

<div style="width:540px;margin-left:130px;margin-top:-100px;overflow-y:scroll;overflow-x:hidden;">

<form action="myprofile.php?vie=-exhibits&set=',$set,'&mode=added" method="post" enctype="multipart/form-data">
    <span style="font-size:14px;">
    Exhibit Name:&nbsp;&nbsp;',$settitle,'
    <br />
    <br />
    About this Exhibit:&nbsp;&nbsp;
    ',stripslashes($aboutset),'
    <br />
    Check photos to add to this exhibit:
    <br /><br />';
    $allusersphotos = "SELECT * FROM photos WHERE emailaddress = '$email'";
    $allusersphotosquery = mysql_query($allusersphotos);
    $usernumphotos = mysql_num_rows($allusersphotosquery);


    for($iii = 0; $iii < $usernumphotos; $iii++) {
        $userphotosource[$iii] = mysql_result($allusersphotosquery, $iii, "source");
        $userphotosset[$iii] = mysql_result($allusersphotosquery, $iii, "sets");
        $userphotoscaption[$iii] = mysql_result($allusersphotosquery, $iii, "caption");
        $newsource = str_replace("userphotos/","userphotos/thumbs/", $userphotosource[$iii]);
        if($userphotosset[$iii] == $set) {
        echo'<img src="',$newsource,'" height="70" width="70" />&nbsp;&nbsp;<input type="checkbox" name="addthese[]" value="',      $userphotosource[$iii],'" checked />&nbsp;"',$userphotoscaption[$iii],'"
    <br /><br />'; }
        else {
        echo'<img src="',$newsource,'" height="70" width="70" />&nbsp;&nbsp;<input type="checkbox" name="addthese[]" value="',      $userphotosource[$iii],'" />&nbsp;"',$userphotoscaption[$iii],'"
        <br /><br />'; 
        }    
    
    } //end of for loop

    
    echo'
    </span>
    <button class="btn btn-success" type="submit">Save Exhibit</button>
    </form>
    
    </div>
    </div>
    </div>';
        
    
    }
    
    
    
    elseif($view == 'info') {
        
        echo'
        <div class="span9" style="margin-top:30px;margin-top:0px;margin-left:-5px;padding:67px;background-color:rgba(245,245,245,0.6);">
        <table class="table">
        <tbody>';

        if($age) {
        echo'
        <tr>
        <td>Age:</td>
        <td>',$age,'</td>
        </tr>'; }

        if($location) {
        echo'
        <tr>
        <td>From:</td>
        <td>',$location,'</td>
        </tr>'; }

        if($gender) {
        echo'
        <tr>
        <td>Gender:</td>
        <td>',$gender,'</td>
        </tr>'; }

        if($camera) {
        echo'
        <tr>
        <td>Camera:</td>
        <td>',$camera,'</td>
        </tr>'; }

        if($fbook) {
        echo'
        <tr>
        <td>Facebook Page:</td>
        <td><a href="',$fbook,'">',$fbook,'</a></td>
        </tr>'; }

        if($twitter) {
        echo'
        <tr>
        <td>Twitter:</td>
        <td><a href="',$twitter,'">',$twitter,'</a></td>
        </tr>'; }

        if($quote) {
        echo'
        <tr>
        <td>Quote:</td>
        <td>',$quote,'</td>
        </tr>'; }

        if($about) {
        echo'
        <tr>
        <td>About:</td>
        <td>',$about,'</td>
        </tr>'; }

        echo'
        </tbody>
        </table>
        </div>';
    
    }

    elseif($view == 'network') {
    
        $option = htmlentities($_GET['option']);    
    
        echo'<br /><br /><br /><br /><div style="width:760px;text-align:center;font-size:14px;font-weight:200;"><div style="margin-left:20px;"><a class="green" style="text-decoration:none;color:#aaa;" href="myprofile3.php?view=network">Following</a> | <a class="green" style="text-decoration:none;color:#aaa;" href="myprofile3.php?view=network&option=followers">Followers</a></div></div>';
        
        if($option == '') {
            $query = mysql_query("SELECT following FROM userinfo WHERE emailaddress = '$email'");
            $followinglist = mysql_result($query, 0, "following");
            $followingquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress IN ($followinglist)");
            $numberfollowing = mysql_num_rows($followingquery);
        }
        
        elseif($option == 'followers') {
        $followersquery="SELECT * FROM userinfo WHERE following LIKE '%$email%'";
        $followingquery=mysql_query($followersquery);
        $numberfollowing = mysql_num_rows($followingquery);
        }
        
        echo'<div style="margin-left:20px;">';
        for($iii = 0; $iii < $numberfollowing; $iii++) {
		$followingpic = mysql_result($followingquery, $iii, "profilepic");
		$followingfirst = mysql_result($followingquery, $iii, "firstname");
		$followinglast = mysql_result($followingquery, $iii, "lastname");
        $fullname = $followingfirst . " " . $followinglast;
        $fullname = ucwords($fullname);
        $followingid = mysql_result($followingquery, $iii, "user_id");
		
                echo '   

                <div style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="http://photorankr.com/fullsize.php?image=', $image[$iii], '">

                <div class="statoverlay" style="z-index:1;left:0px;top:210px;position:relative;background-color:black;width:245px;height:35px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:18px;font-weight:100;">',$fullname,'</span></p></div>

                <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-35px;min-height:245px;min-width:245px;" src="http://www.photorankr.com/',$followingpic,'" height="245" width="245" /></a></div>';
        
        }
        echo'</div>';
    }
    
    
    elseif($view == 'favorites') {
    
        $query = mysql_query("SELECT * FROM photos WHERE source IN ($faves) ORDER BY FIELD (source, $faves) DESC LIMIT 9");
        $numresults = mysql_num_rows($query);
        echo'<div id="thepics">';
        echo'<div id="container" class="grid_18" style="width:770px;margin-top:0px;padding-left:20px;padding-right:45px;margin-left:-5px;background-color:rgba(245,245,245,0.6);">';

        for($iii=0; $iii < $numresults; $iii++) {
              
                $image[$iii] = mysql_result($query, $iii, "source");
                $imageThumb[$iii] = str_replace("userphotos/","../userphotos/medthumbs/", $image[$iii]);
                $id = mysql_result($query, $iii, "id");
                $caption = mysql_result($query, $iii, "caption");
                $points = mysql_result($query, $iii, "points");
                $votes = mysql_result($query, $iii, "votes");
                $faves = mysql_result($query, $iii, "faves");
                $score = number_format(($points/$votes),2);
                $faveemail = mysql_result($query, $iii, "emailaddress");
                $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$faveemail'");
                $firstname = mysql_result($query, 0, "firstname");
                $lastname = mysql_result($query, 0, "lastname");
                $reputation = mysql_result($query, 0, "lastname");
                $fullname = $firstname . " " . $lastname;
                list($width, $height) = getimagesize($image);
                $imgratio = $height / $width;
                $heightls = $height / 3.5;
                $widthls = $width / 3.5;

                echo '   

                <div class="fPic" id="',$id,'" style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="http://photorankr.com/fullsize.php?image=', $image[$iii], '">

                <div class="statoverlay" style="z-index:1;left:0px;top:155px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:16px;font-weight:100;">',$caption,'</span><br><span style="font-size:14px;font-weight:100;">Score: ',$score,'<br>Favorites: ',$faves,'</span></p></div>

                <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:245px;min-width:245px;" src="http://www.photorankr.com/',$imageThumb[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
	    
                } //end for loop      
        
        echo'</div>';
        echo'</div>';
    
    }
    
    
        elseif($view == 'favorites') {
    
        $query = mysql_query("SELECT * FROM photos WHERE source IN ($faves) ORDER BY FIELD (source, $faves) DESC LIMIT 9");
        $numresults = mysql_num_rows($query);
        echo'<div id="thepics">';
        echo'<div id="container" class="grid_18" style="width:770px;margin-top:0px;padding-left:20px;">';

        for($iii=0; $iii < $numresults; $iii++) {
              
                $image[$iii] = mysql_result($query, $iii, "source");
                $imageThumb[$iii] = str_replace("userphotos/","../userphotos/medthumbs/", $image[$iii]);
                $id = mysql_result($query, $iii, "id");
                $caption = mysql_result($query, $iii, "caption");
                $points = mysql_result($query, $iii, "points");
                $votes = mysql_result($query, $iii, "votes");
                $faves = mysql_result($query, $iii, "faves");
                $score = number_format(($points/$votes),2);
                $faveemail = mysql_result($query, $iii, "emailaddress");
                $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$faveemail'");
                $firstname = mysql_result($query, 0, "firstname");
                $lastname = mysql_result($query, 0, "lastname");
                $reputation = mysql_result($query, 0, "lastname");
                $fullname = $firstname . " " . $lastname;
                list($width, $height) = getimagesize($image);
                $imgratio = $height / $width;
                $heightls = $height / 3.5;
                $widthls = $width / 3.5;

                echo '   

                <div class="fPic" id="',$id,'" style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="http://photorankr.com/fullsize.php?image=', $image[$iii], '">

                <div class="statoverlay" style="z-index:1;left:0px;top:155px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:16px;font-weight:100;">',$caption,'</span><br><span style="font-size:14px;font-weight:100;">Score: ',$score,'<br>Favorites: ',$faves,'</span></p></div>

                <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:245px;min-width:245px;" src="http://www.photorankr.com/',$imageThumb[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
	    
                } //end for loop      
        
        echo'</div>';
        echo'</div>';
    
    }
    
    
    elseif($view == 'search') {
        
        $searchterm = htmlentities(mysql_real_escape_string($_POST['searchterm']));
        $query = mysql_query("SELECT * FROM photos WHERE concat(caption,location,tag1,tag2,tag3,tag4) LIKE '%$searchterm%' AND emailaddress = '$email' ORDER BY (views) DESC");
        $numresults = mysql_num_rows($query);
        echo'<div id="thepics">';
        echo'<div id="container" class="grid_18" style="width:770px;margin-top:0px;padding-left:20px;">';

        for($iii=0; $iii < $numresults; $iii++) {
              
                $image[$iii] = mysql_result($query, $iii, "source");
                $imageThumb[$iii] = str_replace("userphotos/","../userphotos/medthumbs/", $image[$iii]);
                $id = mysql_result($query, $iii, "id");
                $caption = mysql_result($query, $iii, "caption");
                $points = mysql_result($query, $iii, "points");
                $votes = mysql_result($query, $iii, "votes");
                $faves = mysql_result($query, $iii, "faves");
                $score = number_format(($points/$votes),2);
                $faveemail = mysql_result($query, $iii, "emailaddress");
                $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$faveemail'");
                $firstname = mysql_result($query, 0, "firstname");
                $lastname = mysql_result($query, 0, "lastname");
                $reputation = mysql_result($query, 0, "lastname");
                $fullname = $firstname . " " . $lastname;
                list($width, $height) = getimagesize($image);
                $imgratio = $height / $width;
                $heightls = $height / 3.5;
                $widthls = $width / 3.5;

                echo '   

                <div class="fPic" id="',$id,'" style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;"><a href="http://photorankr.com/fullsize.php?image=', $image[$iii], '">

                <div class="statoverlay" style="z-index:1;left:0px;top:155px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:16px;font-weight:100;">',$caption,'</span><br><span style="font-size:14px;font-weight:100;">Score: ',$score,'<br>Favorites: ',$faves,'</span></p></div>

                <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:245px;min-width:245px;" src="http://www.photorankr.com/',$imageThumb[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
	    
                } //end for loop      
        
        echo'</div>';
        echo'</div>';
    
    }
    
    
    elseif($view == 'messages') {
            
            	//get all the messages that correspond to them by grouping them by thread number
	$messagequery = "SELECT * FROM (SELECT * FROM messages ORDER BY id DESC) AS theorder WHERE (sender='$email' OR receiver='$email') GROUP BY thread ORDER BY id DESC LIMIT 0,20";
	$messageresult = mysql_query($messagequery) or die(mysql_error());
	$numberofmessages = mysql_num_rows($messageresult);

	//if they don't have any messages, display that
	if($numberofmessages == 0) {
		echo '<div style="margin-left: 460px; margin-top: -130px;font-size:16px;">You have no messages!</div>
        <br />
        <div style="margin-left: 280px; margin-top: 20px;font-size:16px;">(Contact photographers through the "contact" tab in their profile)</div></div>';
	}
	//if they do have messages
	else {
		echo '</div>';
	
		echo '<div class="grid_18" style="background-color:rgba(245,245,245,0.6);padding-left:30px;padding-right:90px;padding-bottom:20px;padding-top:20px;margin-left:-45px;">';

		$comma = 0;

		//for loop to go through each row in the result
		for($iii=0; $iii<$numberofmessages; $iii++) {
			//find what the message is and who it was from and who it was to
			$currentmessage[$iii] = mysql_result($messageresult, $iii, "contents");
			$currentsender = mysql_result($messageresult, $iii, "sender");
			$currentreceiver = mysql_result($messageresult, $iii, "receiver");

			//find out more about the person involved who is not them
			//if the last message was not from the person whose profile it is
			if($currentsender != $email) {
				//the other person is whomever the message was from
				if($comma == 0) {
					$otherpeople .= "'" . $currentsender . "'";
					$comma = 1;
				}
				else {
					$otherpeople .= ", '" . $currentsender . "'";
				}
			}
			//otherwise the last message was from the person the person whose profile it is
			else {
				//the other person is whomever the last message was sent to
				if($comma == 0) {
					$otherpeople .= "'" . $currentreceiver . "'";
					$comma = 1;
				}
				else {
					$otherpeople .= ", '" . $currentreceiver . "'";
				}
			}
		}

		//now that we know everyone whose information we will need, lets get it
		$moreinfoquery = "SELECT firstname, lastname, profilepic FROM userinfo WHERE emailaddress IN (" . $otherpeople . ") ORDER BY FIELD(emailaddress, " . $otherpeople . ") LIMIT 0, 20";
		$moreinforesult = mysql_query($moreinfoquery) or die(mysql_error());
		
		//now go through the results to get the information and then display it
        echo'
                    <h3>Your Conversations:</h3>
                    <h6>(Contact photographers through the "contact" tab in their profile)</h6>
                    <br />';


		for($iii=0; $iii<$numberofmessages; $iii++) {
			$otherspic = mysql_result($moreinforesult, $iii, "profilepic");
			$othersfirst = mysql_result($moreinforesult, $iii, "firstname");
			$otherslast = mysql_result($moreinforesult, $iii, "lastname");
			$currentthread = mysql_result($messageresult, $iii, "thread");

			//now lets display the message with the other's profile picture and name
			echo '
			<a href="myprofile.php?view=viewthread&thread=', $currentthread, '" style="text-decoration: none">
			<div class="grid_18" id="messageshadow" style="margin-bottom: 20px; font-family: arial;">
				<div class="grid_3">
					<img src="', $otherspic, '" width="60px" height="60px" alt="profile picture" style="margin-bottom: 5px;"/>
					<br />', 
					$othersfirst, ' ', $otherslast, 
				'</div>
				<div class="grid_15" style="margin-top: -75px; margin-left: 120px;">', $currentmessage[$iii], 
				'</div>
			</div>
			</a>';
		}

		echo '</div>';
	}
}
else if($view == "viewthread") {

//DE-HIGHLIGHT NOTIFICATIONS IF CLICKED ON
if(isset($_GET['id'])){
$id = htmlentities($_GET['id']);
$idformatted = $id . " ";
$unhighlightquery = "UPDATE userinfo SET unhighlight = CONCAT(unhighlight,'$idformatted') WHERE emailaddress = '$email'";
$unhighlightqueryrun = mysql_query($unhighlightquery);

//notifications query reset 
if($currentnotsresult > 0) {
$notsquery = "UPDATE userinfo SET notifications = 0 WHERE emailaddress = '$email'";
$notsqueryrun = mysql_query($notsquery); }
}
	
	//if no thread was sent, tell them no thread found
	if(!isset($_GET['thread'])) {
		echo '<div style="margin-left: 480px; margin-top: -300px;">No thread found!</div></div>';
	}
	//otherwise there is a thread
	else {
		//select all the messages that match the thread number
		$threadquery = "SELECT * FROM messages WHERE thread=".mysql_real_escape_string(htmlentities($_GET['thread']))." ORDER BY id DESC LIMIT 0, 20";
		$threadresult = mysql_query($threadquery) or die(mysql_error());
		$numberofmessages = mysql_num_rows($threadresult);
		
		//if this returns zero messages, then tell them no thread found
		if($numberofmessages == 0) {
			echo '<div style="margin-left: 480px; margin-top: -300px;">No thread found!</div></div>';
		}
		//otherwise there were messages found
		else {
			echo '</div>';
	
			echo '<div class="grid_18" style="background-color:rgba(245,245,245,0.6);padding-left:30px;padding-right:90px;padding-bottom:20px;padding-top:20px;margin-left:-45px;">';

			//find out the other persons email address
			if(mysql_result($threadresult, 0, "sender") == $email) {
				$othersemail = mysql_result($threadresult, 0, "receiver");
			}
			else {
				$othersemail = mysql_result($threadresult, 0, "sender");
			}

			//update the database to show that these messages have been read
			$updatequery = "UPDATE messages SET unread='0' WHERE receiver='$email' AND thread='".mysql_real_escape_string(htmlentities($_GET['thread']))."'";
			mysql_query($updatequery); 

			//find out all the info we need about the other person
			$othersquery = "SELECT firstname, lastname, profilepic, emailaddress FROM userinfo WHERE emailaddress='" . $othersemail . "' LIMIT 0, 1";
			$othersresult = mysql_query($othersquery);
			$otherspic = mysql_result($othersresult, 0, "profilepic");
			$othersfirst = mysql_result($othersresult, 0, "firstname");
			$otherslast = mysql_result($othersresult, 0, "lastname");
			
			//for loop to go through all the messages in reverse order so that the newest one is last
			for($iii=$numberofmessages-1; $iii >= 0; $iii--) {
				//find out who sent the current message in the loop
				$currentsender = mysql_result($threadresult, $iii, "sender");

				//if the current message's sender is the owner of the profile, set the variables as necessary
				if($currentsender == $email) {
					$currentfirst = $firstname;
					$currentlast = $lastname;
					$currentpic = $profilepic;
				}
				//otherwise the other person is the message's sender, so set the variables accordingly
				else {
					$currentfirst = $othersfirst;
					$currentlast = $otherslast;
					$currentpic = $otherspic;
				}
				
				//find out what the current message is
				$currentmessage = mysql_result($threadresult, $iii, "contents");

				//now that we have everything in line, display the message
				echo '
				<div class="grid_18" id="messageshadow2" style="margin-bottom: 20px; font-family: arial;">
					<a href="viewprofile.php?first=', $currentfirst, '&last=', $currentlast, '">
					<div class="grid_3">
						<img src="', $currentpic, '" width="60px" height="60px" alt="profile picture" style="margin-bottom: 5px;"/>
						<br />', 
						$currentfirst, ' ', $currentlast,' 
					</div>
					</a>
					<div class="grid_15" style="margin-top: -75px; margin-left: 120px;">',$currentmessage,'
					</div>
				</div>';			
			}

			//now let's display the box from which they can send a message
			echo' <div class="grid_18" style="font-size: 20px; font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
			line-height: 28px; color: #333333;">
    
			<h3>Reply:</h3>
			<form method="post" action="replymessage.php" />
			<textarea cols="80" rows="4" style="width:715px" name="message"></textarea>
    			<br />
    			<br />
			<button class="btn btn-success" type="submit" value="Send Message">Send Message</button>
			<input type="hidden" name="emailaddressofviewed" value="',$othersemail,'" />
			</form>';

			if(htmlentities($_GET['action'])=="messagesent") {
				echo 'Message Sent!';
			}
			
			echo '</div>';
		}
	}
    
}


elseif($view == 'promote') {

echo'
<div class="grid_18" style="width:770px;margin-top:10px;padding-left:20px;">

<div class="well" style="font-size:16px;font-family:helvetica neue, gill sans, helvetica;">

<!--Referral Success-->';

$refer=htmlentities($_GET['refer']); 

if ($refer == 'referralsuccess') {
$sendname = $_POST['sendname'];
$sendemail = $_POST['email'];
$to = $sendemail;
$subject = "Your Personal Invitation";
$message = "Hi! You've been invited by $sendname to join PhotoRankr, a site for photographers of all skill levels. What makes PhotoRankr different from the other photo sharing sites?

– The ability to choose the price of your photography 
– Unlimited uploads and 100% free
– Follow other photographers with one click, and view your live 'photostream' of photography from those you follow
– Rank other photography and get feedback from other photographers through comments 
– Make your own profile where you can view your entire portfolio, your followers, who's following you, and edit your information

To accept your invitation and begin following photography today, just click the link below:

http://photorankr.com/signin.php

We hope you'll enjoy PhotoRankr as much as we have building it,

Sincerely,
The PhotoRankr Team
";

$headers = 'From:PhotoRankr <photorankr@photorankr.com>';
mail($to, $subject, $message, $headers);

echo '<span style="position:relative;top:0px;font-family:lucida grande, georgia, helvetica; font-size: 16px;" class="label label-success">Referral successfully sent</span><br /><br />';

}


echo'
Help promote your portfolio and your PhotoRankr page by sharing it with your friends:<br /><span style="font-size:13px;">(This will help increase traffic to your specific page, increase sales, and raise the chances of your photos becoming trending.)</span><br /><br />

<!--FB-->
<a name="fb_share" share_url="http://photorankr.com/viewprofile.php?u=',$user,'"></a> 
<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" 
        type="text/javascript">
</script>

<!--TWITTER-->
<div style="position:relative;margin-top:15px;">
<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://photorankr.com/viewprofile.php?u=',$user,'" data-text="Visit my photography site on PhotoRankr!" data-via="PhotoRankr" data-related="PhotoRankr">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
</script></div>

<!--GOOGLE PLUS-->
<div style="position:relative;margin-top:15px;">
<div class="g-plus" data-action="share" data-href="http://photorankr.com/viewprofile.php?u=',$user,'"></div>';
?>

<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
<?php
echo'

<!--TUMBLR-->
<div style="position:relative;margin-top:15px;">
<span id="tumblr_button_abc123"></span>
</div>

<br />

<!--Referral System-->

Invite your friends to join and follow your photography on PhotoRankr:<br /><br />

<div style="position:relative; top:20px; font-family:lucida grande, georgia, helvetica; font-size: 14px;">Your Name:</div>

<div style="position:relative; top:45px; font-family:lucida grande, georgia, helvetica; font-size: 14px;">Send invitation to:</div>

<div style="position:relative; top:-25px; left:160px;">
<form action="myprofile.php?view=promote&refer=referralsuccess" method="POST">
<input style="width:180px;height:22px;" type="text" name="sendname" value="',$firstname,' ',$lastname,'" />
</div>
<div style="position:relative; top:-20px; left:160px;">
<input style="width:180px;height:22px;" type="text" name="email" placeholder="Email Address"/>
</div>
<div style="position:relative; top:-20px; left:263px;">
<button type="submit" name="Submit" class="btn btn-success">Send Invite</button>
</div>
</form>
</div>

</div>
</div>';

}

    
elseif($view == 'settings') {
    
        $action = htmlentities($_GET['action']);

if ($action == 'savesettings') {
    
$emailcomment = mysql_real_escape_string($_POST['emailcomment']);
$emailreturncomment = mysql_real_escape_string($_POST['emailreturncomment']);
$emailfave = mysql_real_escape_string($_POST['emailfave']);		
$emailfollow = mysql_real_escape_string($_POST['emailfollow']);	

$settinglist = $emailcomment . $emailreturncomment . $emailfave . $emailfollow;

$settingquery = "UPDATE userinfo SET settings = '$settinglist' WHERE emailaddress='$email'";
$settingrun = mysql_query($settingquery);

//Grab what they have checked
$settingemail = $_SESSION['email'];
$settingquery = "SELECT * FROM userinfo WHERE emailaddress = '$settingemail'";
$settingqueryrun = mysql_query($settingquery);
$settinglist = mysql_result($settingqueryrun, 0, "settings");

echo'
<div class="grid_18" style="background-color:rgba(245,245,245,0.6);padding-left:30px;padding-right:95px;padding-bottom:20px;padding-top:20px;margin-left:-5px;">
<span style="font-size:16px;">Notification Settings:</span>
<br />
<span class="label label-success" style="font-size:16px;position:relative;top:15px;">Settings Saved</span><br /><br />
<form action="', htmlentities($_SERVER['PHP_SELF']), '?view=settings&action=savesettings" method="post" enctype="multipart/form-data">
<br />';
        
$setting_string = $settinglist;
$find = "emailcomment";
$foundsetting = strpos($setting_string,$find);
if($foundsetting > 0) {
echo'
<input type="checkbox" name="emailcomment" value=" emailcomment " checked />&nbsp;Receive an email when your photo is commented on<br /><br />'; }
else {
echo'
<input type="checkbox" name="emailcomment" value=" emailcomment " />&nbsp;Receive an email when your photo is commented on<br /><br />'; }

$find2 = "emailreturncomment";
$foundsetting2 = strpos($setting_string,$find2);
if($foundsetting2 > 0) {
echo'
<input type="checkbox" name="emailreturncomment" value=" emailreturncomment " checked />&nbsp;Receive an email when another photographer comments on a photo you also commented on<br /><br />'; }
else {
echo'
<input type="checkbox" name="emailreturncomment" value=" emailreturncomment " />&nbsp;Receive an email when another photographer comments on a photo you also commented on<br /><br />'; }

$find3 = "emailfave";
$foundsetting3 = strpos($setting_string,$find3);
if($foundsetting3 > 0) {
echo'
<input type="checkbox" name="emailfave" value=" emailfave " checked />&nbsp;Receive an email when another photographer favorites your photo<br /><br />'; }
else {
echo'
<input type="checkbox" name="emailfave" value=" emailfave " />&nbsp;Receive an email when another photographer favorites your photo<br /><br />'; }

$find4 = "emailfollow";
$foundsetting4 = strpos($setting_string,$find4);
if($foundsetting4 > 0) {
echo'
<input type="checkbox" name="emailfollow" value=" emailfollow " checked />&nbsp;Receive an email when someone follows your photography<br /><br />'; }
else {
echo'
<input type="checkbox" name="emailfollow" value=" emailfollow " />&nbsp;Receive an email when someone follows your photography<br /><br />'; }

echo'
<button type="submit" name="Submit" class="btn btn-success">Save Notification Settings</button>
</form>
</div>
';

}
    
else {
 
 
$settingemail = $_SESSION['email'];
$settingquery = "SELECT * FROM userinfo WHERE emailaddress = '$settingemail'";
$settingqueryrun = mysql_query($settingquery);
$settinglist = mysql_result($settingqueryrun, 0, "settings");

echo'
<div class="grid_18" style="background-color:rgba(245,245,245,0.6);padding-left:30px;padding-right:95px;padding-bottom:20px;padding-top:20px;margin-left:-5px;">
<span style="font-size:16px;">Notification Settings:</span>
<form action="', htmlentities($_SERVER['PHP_SELF']), '?view=settings&action=savesettings" method="post" enctype="multipart/form-data">
<br />';
        
$setting_string = $settinglist;
$find = "emailcomment";
$foundsetting = strpos($setting_string,$find);
if($foundsetting > 0) {
echo'
<input type="checkbox" name="emailcomment" value=" emailcomment " checked/>&nbsp;Receive an email when your photo is commented on<br /><br />'; }
else {
echo'
<input type="checkbox" name="emailcomment" value=" emailcomment " />&nbsp;Receive an email when your photo is commented on<br /><br />'; }

$find2 = "emailreturncomment";
$foundsetting2 = strpos($setting_string,$find2);
if($foundsetting2 > 0) {
echo'
<input type="checkbox" name="emailreturncomment" value=" emailreturncomment " checked />&nbsp;Receive an email when another photographer comments on a photo you also commented on<br /><br />'; }
else {
echo'
<input type="checkbox" name="emailreturncomment" value=" emailreturncomment " />&nbsp;Receive an email when another photographer comments on a photo you also commented on<br /><br />'; }

$find3 = "emailfave";
$foundsetting3 = strpos($setting_string,$find3);
if($foundsetting3 > 0) {
echo'
<input type="checkbox" name="emailfave" value=" emailfave " checked />&nbsp;Receive an email when another photographer favorites your photo<br /><br />'; }
else {
echo'
<input type="checkbox" name="emailfave" value=" emailfave " />&nbsp;Receive an email when another photographer favorites your photo<br /><br />'; }

$find4 = "emailfave";
$foundsetting4 = strpos($setting_string,$find4);
if($foundsetting4 > 0) {
echo'
<input type="checkbox" name="emailfollow" value=" emailfollow " checked />&nbsp;Receive an email when someone follows your photography<br /><br />'; }
else {
echo'
<input type="checkbox" name="emailfollow" value=" emailfollow " />&nbsp;Receive an email when someone follows your photography<br /><br />'; }

echo'
<button type="submit" name="Submit" class="btn btn-success">Save Notification Settings</button>
</form>


<!--Choose Background Photo-->';

if($_GET['mode'] == 'updatebackground') {
echo'<br /><span style="position:relative;margin-top:-130px;font-size: 16px;"><span class="label label-success" style="font-size:16px;" >Background Saved</span><br /><br /<br /><br /></span>';
}

echo'
<a data-toggle="modal" data-backdrop="static" href="#submitfromportfolio"><button style="margin-top:20px;" class="btn btn-success"><b>Choose Background Image</b></button></a>

</div>';

}

//Update Background Modal
echo'<div class="modal hide fade" id="submitfromportfolio" style="overflow-y:scroll;overflow-x:hidden;">

<div class="modal-header">
<a style="float:right" class="btn btn-primary" data-dismiss="modal">Close</a>
<img style="margin-top:-4px;" src="graphics/logoteal.png" height="30" width="100" />&nbsp;&nbsp;<span style="font-size:16px;">Choose your profile background image:</span>
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:550px;height:500px;overflow-x:hidden;">';

if($email != '') {
echo'
<img style="border: 1px solid black;margin-left:10px;margin-top:10px;" src="',$profilepic,'" 
height="100px" width="100px" />

<div style="width:540px;margin-left:130px;margin-top:-125px;overflow-y:scroll;overflow-x:hidden;">

<form action="myprofile3.php?view=settings&mode=updatebackground" method="post">
    <span style="font-size:14px;">
    <br /><br />';
    $allusersphotos = "SELECT * FROM photos WHERE emailaddress = '$email'";
    $allusersphotosquery = mysql_query($allusersphotos);
    $usernumphotos = mysql_num_rows($allusersphotosquery);
    
    for($iii = 0; $iii < $usernumphotos; $iii++) {
        $userphotosource = mysql_result($allusersphotosquery, $iii, "source");
        $userphotosource = str_replace("userphotos/","http://photorankr.com/userphotos/", $userphotosource);
        $userphotosset[$iii] = mysql_result($allusersphotosquery, $iii, "sets");
        $userphotoscaption[$iii] = mysql_result($allusersphotosquery, $iii, "caption");
        $newsource = str_replace("userphotos/","userphotos/thumbs/", $userphotosource);
        
        echo'<img src="',$newsource,'" height="70" width="70" />&nbsp;&nbsp;<input type="checkbox" name="checked" value="',$userphotosource,'" />&nbsp;"',$userphotoscaption[$iii],'"
        <br /><br />'; 
    
    } //end of for loop
    
    
    echo'
    </span>
    <button class="btn btn-success" type="submit">Save Background</button>
    <br />
    <br />
    </form>';
    }
    
    else {
    echo'<div style="text-align:center;margin-top:100px;"><b>Please login or register to upload</b></div>';
    }
    
    echo'
    </div>
    </div>';
    
    }
    
?>

</div><!--end grid 18-->


<?php

    //Edit Exhibit Modal

echo'<div class="modal hide fade" id="editexhibit" style="overflow-y:scroll;overflow-x:hidden;">

<div class="modal-header">
<a style="float:right" class="btn btn-primary" data-dismiss="modal">Close</a>
<img style="margin-top:-4px;" src="graphics/logoteal.png" height="28" width="90" />&nbsp;&nbsp;<span style="font-size:16px;">Edit your exhibit\'s information below:</span>
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:550px;height:500px;overflow-x:hidden;">
		
<img style="border: 1px solid black;margin-left:10px;margin-top:10px;" src="',$setcover,'" 
height="100px" width="100px" />

<div style="width:540px;margin-left:130px;margin-top:-100px;overflow-y:scroll;overflow-x:hidden;">

<form action="', htmlentities($_SERVER['PHP_SELF']), '?ex=y&set=',$set,'&mode=coverchanged" method="post" enctype="multipart/form-data">
    <span style="font-size:14px;">
    Exhibit Name:&nbsp;&nbsp; <input name="caption" value="',$settitle,'">
    <br />
    About this Exhibit:&nbsp;
    <br />
    <textarea style="width:380px;" rows="4" cols="60" name="aboutset">',stripslashes($aboutset),'</textarea>
    <br />
    Change Exhibit Cover Photo (choose one):
    <br /><br />';
    $allusersphotos2 = "SELECT * FROM photos WHERE emailaddress = '$email' AND set_id = '$set'";
    $allusersphotosquery2 = mysql_query($allusersphotos2);
    $usernumphotos2 = mysql_num_rows($allusersphotosquery2);

    for($iii = 0; $iii < $usernumphotos2; $iii++) {
        $userphotosource[$iii] = mysql_result($allusersphotosquery2, $iii, "source");
        $userphotosset[$iii] = mysql_result($allusersphotosquery2, $iii, "sets");
        $userphotoscaption[$iii] = mysql_result($allusersphotosquery2, $iii, "caption");
        $newsource = str_replace("userphotos/","userphotos/thumbs/", $userphotosource[$iii]);
        if($userphotosset[$iii] == $set) {
            echo'<img src="',$newsource,'" height="70" width="70" />&nbsp;&nbsp;<input type="checkbox" name="addthis" value="',$userphotosource[$iii],'" checked />&nbsp;"',$userphotoscaption[$iii],'"
    <br /><br />'; }
        else {
            echo'<img src="',$newsource,'" height="70" width="70" />&nbsp;&nbsp;<input type="checkbox" name="addthis" value="',$userphotosource[$iii],'" />&nbsp;"',$userphotoscaption[$iii],'"
        <br /><br />'; }
        
    } //end of for loop
    
    echo'
    </span>
    <button class="btn btn-success" type="submit">Save Info</button>
    </form>
    
    </div>
    </div>
    </div>';
    
?>


</div><!--end 24 grid-->

</div>


<!--TUMBLR SCRIPTS-->
<script type="text/javascript">
    var tumblr_link_url = "http://photorankr.com/viewprofile.php?u=',$user,'";
    var tumblr_link_name = "My PhotoRankr Portfolio";
    var tumblr_link_description = "Visit and rank my photography on PhotoRankr!";
</script>

<script type="text/javascript">
    var tumblr_button = document.createElement("a");
    tumblr_button.setAttribute("href", "http://www.tumblr.com/share/link?url=" + encodeURIComponent(tumblr_link_url) + "&name=" + encodeURIComponent(tumblr_link_name) + "&description=" + encodeURIComponent(tumblr_link_description));
    tumblr_button.setAttribute("title", "Share on Tumblr");
    tumblr_button.setAttribute("style", "display:inline-block; text-indent:-9999px; overflow:hidden; width:129px; height:20px; background:url('http://platform.tumblr.com/v1/share_3.png') top left no-repeat transparent;");
    tumblr_button.innerHTML = "Share on Tumblr";
    document.getElementById("tumblr_button_abc123").appendChild(tumblr_button);
</script>

<script type="text/javascript" src="http://platform.tumblr.com/v1/share.js"></script>


</body>
</html>
