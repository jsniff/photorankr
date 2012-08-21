<?php

//connect to the database
require "db_connection.php";
require "functionstest.php";

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
        
        $query="SELECT * FROM photos ORDER BY id DESC LIMIT 0, 16";
$result=mysql_query($query);
$numberofpics=mysql_num_rows($result);

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

 <meta name="Generator" content="EditPlus">
  <meta name="Author" content="PhotoRankr, PhotoRankr.com">
  <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
  <meta name="Description" content="A gallery of the newest photography, photographers, and exhibits on PhotoRankr.">
     <meta name="viewport" content="width=1200" /> 


  <link rel="stylesheet" type="text/css" href="css/bootstrapNew.css" />
 <link rel="stylesheet" href="market/css/reset.css" type="text/css" />
  <link rel="stylesheet" href="market/css/text.css" type="text/css" />
   <link rel="stylesheet" href="css/style.css" type="text/css" />
  <link rel="stylesheet" href="960_24.css" type="text/css" />
	<script type="text/javascript" href="js/bootstrap-dropdown.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="http://masonry.desandro.com/jquery.masonry.min.js"></script>
<script type="text/javascript" src="https://raw.github.com/desandro/imagesloaded/master/jquery.imagesloaded.min.js"></script>
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
    
    
</head>
<body>

<?php navbartest(); ?>

 <!--big container-->
    <div id="container" class="container_24" >
    

<!--DIFFERENT GALLERY VIEWS-->

<?php  

if(isset($_GET['view'])){
$view = htmlentities($_GET['view']);
}

        echo'<br /><br /><br /><br /><div style="text-align:center;font-size:14px;font-weight:200;"><div style="margin-left:20px;"><a class="green" style="text-decoration:none;color:#000;';if($view == '') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="newest.php">Newest Photos</a> | <a class="green" style="text-decoration:none;color:#000;';if($view == 'prs') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="newest.php?view=prs">Newest Photographers</a> | <a class="green" style="text-decoration:none;color:#000;';if($view == 'exts') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="newest.php?view=exts">Newest Exhibits</a></div></div>';
        
        
if($view == '') {
        
    //DISPLAY 20 NEWEST OF ALL PHOTOS
         
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

	list($width, $height) = getimagesize($image);
	$imgratio = $height / $width;
    $heightls = $height / 2.5;
    $widthls = $width / 2.5;

		echo '<div class="fPic" id="',$id,'" style="float:left;margin-right:20px;margin-top:20px;width:280px;height:280px;overflow:hidden;"><a style="text-decoration:none;" href="http://photorankr.com/fullsize.php?image=',$image,'&v=n">
        
          <div class="statoverlay" style="z-index:1;left:0px;top:240px;position:relative;background-color:black;width:280px;height:40px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-weight:100;font-size:20px;">',$caption,'</span><br/></div>
        
        <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-75px;min-height:300px;min-width:280px;" src="http://photorankr.com/',$imageThumb,'" alt="',$caption,'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
       
	    
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
					url: "loadMoreNewPics.php?lastPicture=" + $(".fPic:last").attr("id"),
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
$prsquery="SELECT * FROM userinfo WHERE (profilepic != 'http://www.photorankr.com/profilepics/default_profile.jpg' AND profilepic != 'profilepics/default_profile.jpg') ORDER BY user_id DESC";
$prsresult=mysql_query($prsquery);

echo'<div id="container" style="width:1210px;margin-left:-112px;top:15px;">';
for($iii=1; $iii <= 16; $iii++) {
	$profpic = mysql_result($prsresult, $iii-1, "profilepic");
    if($profpic == 'http://www.photorankr.com/profilepics/default_profile.jpg') {
    $profpic = 'profilepics/default_profile.jpg';
    }
    $firstname = mysql_result($prsresult, $iii-1, "firstname");
	$lastname = mysql_result($prsresult, $iii-1, "lastname");
    $fullname = $firstname . " " . $lastname;
    $fullname = ucwords($fullname);
	$userid = mysql_result($prsresult, $iii-1, "user_id");

		echo '<div class="fPic" id="',$id,'" style="float:left;margin-right:20px;margin-top:20px;width:280px;height:280px;overflow:hidden;"><a href="viewprofile.php?u=',$userid,'">
        
        <div class="statoverlay" style="z-index:1;left:0px;top:215px;position:relative;background-color:black;width:280px;height:40px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-weight:100;font-size:22px;">',$fullname,'</span></div>
        
        <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-75px;min-height:290px;min-width:280px;" src="',$profpic,'" alt="',$fullname,'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';

    } //end for loop
    echo'</div>';
    
} //end of view == 'prs'



elseif($view == 'exts') {
$query="SELECT * FROM sets ORDER BY id DESC LIMIT 0,30";
$result=mysql_query($query);
$numberexhibits=mysql_num_rows($result);

echo'<div id="container" style="width:1210px;margin-left:-112px;top:15px;">';
for($iii=1; $iii <= $numberexhibits; $iii++) {
	$coverpic = mysql_result($result, $iii-1, "cover");
    $caption = mysql_result($result, $iii-1, "title");
    $set_id = mysql_result($result, $iii-1, "id");
    if($coverpic == '') {
    $coverpic = 'graphics/no_cover.png';
    }
    $owner = mysql_result($result, $iii-1, "owner");
    $exhibitquery = mysql_query("SELECT * FROM photos WHERE set_id = '$set_id'");
    $numberphotos = mysql_num_rows($exhibitquery);
   
    for($i = 0; $i < $numberphotos; $i++) {
    $points += mysql_result($exhibitquery, $i, "points");
    $votes += mysql_result($exhibitquery, $i, "votes");
    }
    
    $score = number_format(($points/$votes),2);
    
    $avgscorequery = mysql_query("UPDATE sets SET avgscore = '$score' WHERE id = '$set_id'");
    
    $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$owner'");
    $firstname = mysql_result($ownerquery, 0, "firstname");
    $lastname = mysql_result($ownerquery, 0, "lastname");
    $fullname = $firstname . " " . $lastname;
    
	$userid = mysql_result($ownerquery, 0, "user_id");
    
    echo'
    <div class="fPic" id="',$id,'" style="float:left;margin-right:20px;margin-top:20px;width:280px;height:280px;overflow:hidden;"><a href="http://photorankr.com/viewprofile.php?u=',$userid,'&view=exhibits&set=',$set_id,'">
        
        <div class="statoverlay" style="z-index:1;left:0px;top:210px;position:relative;background-color:black;width:280px;height:70px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-weight:100;font-size:18px;">',$caption,'</span><br/><span style="font-weight:100;font-size:12px;">By: ',$fullname,'</p></div>
        
        <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-75px;min-height:290px;min-width:280px;" src="http://photorankr.com/',$coverpic,'" alt="',$caption,'" height="',$heightls,'px" width="',$widthls,'px" /></a></div>';
        
    } //end for loop
    echo'</div>';

} //end of view == 'exts'

?>

</div>
<br /><br />

