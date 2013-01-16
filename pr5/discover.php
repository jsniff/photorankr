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
    
    //Your information
    $findreputationme = mysql_query("SELECT user_id,reputation,profilepic,firstname,lastname,following FROM userinfo WHERE emailaddress = '$email'");
    $reputationme = mysql_result($findreputationme,0,'reputation');
    $sessionpic = mysql_result($findreputationme,0,'profilepic');
    $sessionuserid =  mysql_result($findreputationme,0,'user_id');
    $sessionfirst =  mysql_result($findreputationme,0,'firstname');
    $sessionlast =  mysql_result($findreputationme,0,'lastname');
    
    //Get views
    if(isset($_GET['image'])){
        $image = htmlentities($_GET['image']);
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
  //check if they aren't logged in
if($_SESSION['loggedin'] != 1) {
	mysql_close();
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=signup.php?action=disc">';
	exit();
}
else {
	$image = htmlentities($_GET['image']);
	/*if(!$_GET['image'] || $_GET['image'] == "") {
		mysql_close();
		header("Location: profile.php?view=about&option=editinfo&error=disc#disc");
		exit();			
	}*/

	//get the users information from the database
	$likesquery = "SELECT * FROM userinfo WHERE emailaddress='$email'";
	$likesresult = mysql_query($likesquery) or die(mysql_error());
	$discoverseen = mysql_result($likesresult, 0, "discoverseen");
    $reputationme = mysql_result($likesresult, 0, "reputation");
        
	//find out what they like
	$likes = mysql_result($likesresult, 0, "viewLikes");
	/*if($likes=="") {
		mysql_close();
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=myprofile.php?view=editinfo&action=discover#discover">';
		exit();		
	}*/

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

	//update the database with the image they are seeing right now to reflect that it has been seen
	if($discoverseen != "") {
			$discoverseen .= " ";
			$discoverseen .= $image;
	}
	else {
		$discoverseen = $image;
	}

	$image = mysql_real_escape_string($image);

	$updatequery = mysql_query("UPDATE photos SET views=views+1 WHERE id='$image'") or die(mysql_error());

	$discoverseen = trim($discoverseen);

	$seenquery = "UPDATE userinfo SET discoverseen='$discoverseen' WHERE emailaddress='$email'";
	$seenresult = mysql_query($seenquery);

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
	if($discoverseen != "") {			//get the photos that match this person's view interests
		$viewquery = "SELECT *, MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$formattedLikes') AS matching FROM photos WHERE MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$formattedLikes') AND id NOT IN(" . $discoverFormatted . ") ORDER BY matching DESC, points DESC LIMIT 0, 1";
		$viewresult = mysql_query($viewquery) or die(mysql_error());
	}
	else {
		//get the photos that match this person's view interests
		$viewquery = "SELECT *, MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$formattedLikes') AS matching FROM photos WHERE MATCH(maintags, singlecategorytags, singlestyletags) AGAINST ('$formattedLikes') ORDER BY matching DESC, points DESC LIMIT 0, 1";
		$viewresult = mysql_query($viewquery) or die(mysql_error());
	}

	//display the image itself
	$currentquery = "SELECT source, id FROM photos WHERE id='$image' LIMIT 0, 1";
	$currentresult = mysql_query($currentquery);
    $currentimage = $image;
	$image = mysql_result($currentresult, 0, "source");
    $imageid = mysql_result($currentresult, 0, "id");
    $nextimage = mysql_result($viewresult, 0, "id");
}
  
//DISCOVER PHOTO INFORMATION
$query="SELECT * FROM photos WHERE id='$imageid'";
$result=mysql_query($query);
//if no images match what is in the url, then send them back to trending 
/*if(mysql_num_rows($result) == 0) {
	header("Location: profile.php?view=about&option=editinfo&error=disc#disc");
	exit();
}*/

$row=mysql_fetch_array($result);
$emailaddress=$row['emailaddress'];
$caption=$row['caption'];
$location=$row['location'];
$country=$row['country'];
$prevpoints=$row['points'];
$prevvotes=$row['votes'];
$imageID=$row['id'];
$price=mysql_result($result, 0, "price");
$camera = $row['camera'];
$faves= $row['faves'];
$collected=$row['collected'];
$ranking=number_format(($prevpoints/$prevvotes),1);
$views = $row['views'];
$exhibit = $row['set_id'];
$exhibitname = $row['sets'];
$focallength = $row['focallength'];
$shutterspeed = $row['shutterspeed'];
$aperture = $row['aperture'];
$lens = $row['lens'];
$filter = $row['filter'];
$copyright = $row['copyright'];
$about = $row['about'];
$tag1 = $row['tag1'];
$tag2 = $row['tag2'];
$tag3 = $row['tag3'];
$tag4 = $row['tag4'];
$maintags = $row['maintags'];
$settags = $row['settags'];
$singlecategorytags = $row['singlecategorytags'];
$singlestyletags = $row['singlestyletags'];
$tags = $settags + $maintags + $singlecategorytags + $singlestyletags;

//find how many photos the photographer has
$numberofpics = mysql_query("SELECT * FROM photos WHERE emailaddress='$emailaddress'");
$numberofpics = mysql_num_rows($numberofpics);

$locationandcountry = $location . $country;

if ($price == "") {$price='.25';}  

//FIND THE PHOTOGRAPHER NAME IN DATABASE
$namequery="SELECT * FROM userinfo WHERE emailaddress='$emailaddress'";
$nameresult=mysql_query($namequery);
$row=mysql_fetch_array($nameresult);
$user=$row['user_id'];
$firstname=$row['firstname'];
$lastname=$row['lastname'];
$fullname = $firstname . " " . $lastname;
$fullname = (strlen($fullname ) > 14) ? substr($fullname,0,12). " &#8230;" : $fullname;
$profilepic=$row['profilepic'];
$profilescore=$row['totalscore'];
$reputation=$row['reputation'];

//calculate the size of the picture
$maxwidth=700;
$maxheight=700;

list($width, $height)=getimagesize($image);
$imgratio=$width/$height;

if($imgratio > 1) {
	$newwidth=$maxwidth;
	$newheight=$maxwidth/$imgratio;
}
else {
	$newheight=$maxheight;
	$newwidth=$maxheight*$imgratio;
}

  
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
    <link rel="stylesheet" type="text/css" href="css/main3.css"/>

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


<style type="text/css">
		.show
		{
			display:block !important;
		}
		
		#notify
		{
			width:40px;
			margin: 0 0 0 5px;
			background:#d96f62;
			padding: 5px;
		}
		#notify:hover
		{
			background: rgba(255,255,255,.55);
		}
		#drawer
		{
			width:0px;
			background: url('graphics/noise.png');
			color:#fff;
			white-space: normal;
			font-size: 10px;
			position:fixed;
			height:100%;
			box-shadow: inset 0 0 5px rgba(0,0,0,.25);
			border-radius:0 5px 5px 0;
			margin: 5px 0 0 -5px;
			z-index: 1000;
		}
		.notifications
		{
			font-family:"helvetica neue", helvetica, arial,sans-serif; 
			font-size:20px;
			font-weight: 500;
			color:#fff;
			margin-left: -200px;
			width:200px;

		}
		.test
		{
			height:250px;
			background: rgba(200,200,200,.6);
			box-shadow: 0 0 2px #666;
			margin: 4px 20px 0 0;
		}
		.test2
		{
			height:50px;
			background: rgba(200,200,200,.6);
			box-shadow: 0 0 2px #666;
			margin: 7px 4px !important;
			width:125px;
			float: right;
		}
		.x
		{
			background:none !important;
			color:#222 !important;
			padding: 0 !important;
			box-shadow: 0 0 0 !important;
			margin:10px 5px 0 5px !important;
			border: none !important;
			font-size: 14px;
		}
		/*.arrow-right {
	width: 0; 
	height: 0; 
	border-top: 13px solid transparent;
	border-bottom: 13px solid transparent;
	box-shadow: inset 0 0 1px #999;
	border-right: 13px solid rgba(245,245,245,1);
	position: absolute;
	top:33px;
	left:75px;
}*/
	</style>
    
    <script type="text/javascript">
    //Display textarea
    $(function() 
    {
        $("#photocomment").focus(function()
        {
        $(this).animate({"height": "85px",}, "fast" );
        $("#button_block").slideDown("show");
        return false;
        });
        
        $("#photocomment").focusout(function()
        {
        $(this).animate({"height": "45px",}, "fast" );
        $("#button_block").slideUp("fast");
        return false;
        });        
    });
    </script>
        
        <style type="text/css">
            #photocomment {
                -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25), inset 0 -1px 1px #666;
                -moz-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25), inset 0 -1px 1px #666;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25), inset 0 -1px 1px #666;
                outline:none;
                border:none;
                color:#333;
                font-size:15px;
                font-weight:300;
                -webkit-border-top-left-radius: 2px;
                -webkit-border-top-right-radius: 2px;
                -moz-border-radius-topleft: 2px;
                -moz-border-radius-topright: 2px;
                border-top-left-radius: 2px;
                border-top-right-radius: 2px;
            }
        </style>
    
    <script language="javascript" type="text/javascript">

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

function ajaxFunction(){
	
    ajaxRequest = createRequestObject();
    
    // Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4  && ajaxRequest.status == 200){
			var ajaxDisplay = document.getElementById('ajaxFave');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
		}
	}
	
	var age = "<?php echo $email; ?>";
    var image = "<?php echo $image; ?>";
	var queryString = "?age=" + age + "&image=" + image;
	ajaxRequest.open("GET", "ajaxfave.php" + queryString, true);
	ajaxRequest.send(null); 

}

function Rank(rank){
	
    ajaxRequest = createRequestObject();
    
	// Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4 && ajaxRequest.status == 200){
			var ajaxDisplay = document.getElementById('ajaxRank');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
		}
	}
	
	var rank = rank;
    var image = "<?php echo $image; ?>";
    var ranker = "<?php echo $email; ?>";
	var queryString = "?rank=" + rank + "&image=" + image + "&ranker=" + ranker;
	ajaxRequest.open("GET", "ajaxrank.php" + queryString, true);
	ajaxRequest.send(null); 

}

function ajaxFollow(){

    ajaxRequest = createRequestObject();
	
    // Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
            var ajaxDisplay = document.getElementById('ajaxFollow');
            
		}
	}
	
	var follower = "<?php echo $email; ?>";
    var followee = "<?php echo $emailaddress; ?>";
	var queryString = "?follower=" + follower + "&followee=" + followee;
	ajaxRequest.open("GET", "ajaxfollow.php" + queryString, true);
	ajaxRequest.send(null); 

}

</script>

<script type="text/javascript" >

$(function() {
$(".submit").click(function() 
{
var firstname = '<?php echo $sessionfirst; ?>';
var lastname = '<?php echo $sessionlast; ?>';
var email = '<?php echo $email; ?>';
var photo = '<?php echo $imageid; ?>';
var userpic = '<?php echo $sessionpic; ?>';
var viewerid = '<?php echo $sessionid; ?>';
var viewerrep = '<?php echo $reputationme; ?>';
var comment = $("#photocomment").val();
var dataString = 'firstname='+ firstname + '&lastname=' + lastname + '&email=' + email + '&comment=' + comment + '&userpic=' + userpic + '&photo=' + photo + '&viewerid=' + viewerid + '&viewerrep=' + viewerrep;
if(email=='' || comment=='')
{
alert('Please Give Valid Details');
}
else
{
//Loading GIF
$.ajax({
type: "POST",
url: "commentajax.php",
data: dataString,
cache: false,
success: function(html){
$("ol#update").append(html);
$("ol#update li:last").fadeIn("slow");
$("#flash").hide();
}
});
}return false;
}); });

var orgimage = <?php echo $imageid; ?>;
var image = <?php echo $imageid; ?>;
var view = "<?php echo $view; ?>";


function ajaxNextPics(){
    
    ajaxRequest = createRequestObject();
	
    // Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
            
            var json = eval('(' + ajaxRequest.responseText +')');
            var nextimg1 = document.getElementById('nextimg1');
            nextimg1.src = json.nextimg3;
            var nextimg1id = document.getElementById('nextimg1id');
            nextimg1id.href = 'fullsize.php?imageid=' + json.nextimg3id +'&view=' + view;
            var nextimg2 = document.getElementById('nextimg2');
            nextimg2.src = json.nextimg2;
            var nextimg2id = document.getElementById('nextimg2id');
            nextimg2id.href = 'fullsize.php?imageid=' + json.nextimg2id +'&view=' + view;
            var nextimg3 = document.getElementById('nextimg3');
            nextimg3.src = json.nextimg1;
            var nextimg3id = document.getElementById('nextimg3id');
            nextimg3id.href = 'fullsize.php?imageid=' + json.nextimg1id +'&view=' + view;

            
		}
                
	}
    
    var queryString = "?image=" + image;
	ajaxRequest.open("GET", "ajaxNextPics.php" + queryString, true);
	ajaxRequest.send(null);
    if(image < orgimage) { 
        image += 1;
    }
}


function ajaxPrevPics(){
        
    ajaxRequest = createRequestObject();
	
    // Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
            
            var json = eval('(' + ajaxRequest.responseText +')');
            var nextimg1 = document.getElementById('nextimg1');
            nextimg1.src = json.previmg1;
            var nextimg1id = document.getElementById('nextimg1id');
            nextimg1id.href = 'fullsize.php?imageid=' + json.previmg1id +'&view=' + view;
            var nextimg2 = document.getElementById('nextimg2');
            nextimg2.src = json.previmg2;
            var nextimg2id = document.getElementById('nextimg2id');
            nextimg2id.href = 'fullsize.php?imageid=' + json.previmg2id +'&view=' + view;
            var nextimg3 = document.getElementById('nextimg3');
            nextimg3.src = json.previmg3;
            var nextimg3id = document.getElementById('nextimg3id');
            nextimg3id.href = 'fullsize.php?imageid=' + json.previmg3id +'&view=' + view;
            
		}
                
	}
    
    var queryString = "?image=" + image;
	ajaxRequest.open("GET", "ajaxPrevPics.php" + queryString, true);
	ajaxRequest.send(null); 
    if(image > 0) {
        image -= 1;
    }
}

</script>
</head>

<!--Collection Modal-->
<div class="modal hide fade" id="collectionmodal" style="overflow:hidden;border:5px solid rgba(102,102,102,.8);">
  
<?php
 
if($_SESSION['loggedin'] !== 1) {

echo'
<div class="modal-header" style="background-color:#111;color:#fff;">
<a style="float:right" class="btn btn-success" data-dismiss="modal">Close</a>
<img style="margin-top:-2px;" src="graphics/aperture_white.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">Please login to add this photo to a collection</span>
  </div>
 
<div id="modal-body" style="width:450px;height:145px;">

<div id="content" style="font-size:16px;width:560px;font-family:helvetica,arial;font-weight:100;background-color:rgb(245,245,245);height:150px;">
		
<img class="roundedall" style="margin-left:20px;margin-top:20px;" src="',$image,'" 
height="100px" width="100px" />

<div style="width:350px;margin-left:140px;margin-top:-75px;line-height:1.48;">              

',$caption,'<br />

By: 
<a style="color:black;" href="viewprofile.php?u=',$user,'">',$firstname,' ',$lastname,'</a><br />   

</div>
</div>';
    
}

    if($_SESSION['loggedin'] == 1) {
		
        echo'
        <div class="modal-header" style="background-color:#111;color:#fff;">
        <a style="float:right" class="btn btn-success" data-dismiss="modal"href="fullsize.php?image=', $image,'&v=',$view,'&f=1">Close</a>
        <img style="margin-top:-2px;" src="graphics/aperture_white.png" height="34" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:16px;font-family:helvetica,arial;font-weight:100;">Choose a collection to add this photo to:</span>
        </div>

        <div id="modal-body" style="width:450px;">

        <div id="content" style="font-size:16px;width:560px;font-family:helvetica,arial;font-weight:100;background-color:rgb(245,245,245);max-height:25em;overflow-y:scroll;">
		
        <img class="roundedall" style="margin-left:20px;margin-top:20px;" src="',$image,'" 
height="100px" width="100px" />

        <div style="width:350px;margin-left:140px;margin-top:-95px;line-height:1.48;">';           
           
        $vieweremail = $_SESSION['email'];
		$collectioncheck = mysql_query("SELECT id,photos,title FROM collections WHERE owner = '$vieweremail'") or die(mysql_error());
        $numcollections = mysql_num_rows($collectioncheck);
        
        echo'<form action="fullsize.php?imageid=',$imageid,'&v=',$view,'&action=savecol" method="POST" />';
                
        if($numcollections > 0) {
        
        echo'Your collections to add to:<br />
        
        <div style="padding-top:15px;">';
        
        for($iii=0; $iii < $numcollections; $iii++) {
        
            $collectionphotos = mysql_result($collectioncheck,$iii,photos);
            $collectionid = mysql_result($collectioncheck,$iii,id);
            $collectiontitle = mysql_result($collectioncheck,$iii,title);
            $collectioncover = mysql_result($collectioncheck,$iii,cover);
            
            //search if image already in this set
            $match=strpos($collectionphotos, $imageid);
            
            if(!$match) {
            
                if($collectioncover) {
                
                    echo'<img src="',$cover,'" width="80" />';
                }
                
                elseif(!$collectioncover && !$collectionphotos) {
                
                     echo'<img src="graphics/no_photos.png" width="80" />';
                
                }
                
                elseif(!$collectioncover && $collectionphotos) {
                
                     $collphotosarray = explode(" ",$collectionphotos);
                     $firstphoto = mysql_query("SELECT source FROM photos WHERE id = '$collphotosarray[0]'");
                     $source = mysql_result($firstphoto,0,'source'); 
                     $source = str_replace("userphotos/","userphotos/medthumbs/",$source);  
                         
                     echo'<img src="',$source,'" width="80" />';
                
                }
                
                echo'&nbsp;&nbsp;&nbsp;<input type="checkbox" name="collection[]" value="',$collectionid,'">&nbsp;&nbsp;&nbsp;',$collectiontitle,'<br /><br />';
                        
            }
            
            elseif($match) {
            
            echo'<br />';
            
            }
            
        }   
           
        echo'</div><button class="btn btn-success" type="submit" value="Save">Add to collection(s)</button>
        </form>';
        
        }
        
        else {
        
        echo'<div style="padding-top:35px;">You have no collections. <a href="myprofile.php?view=collections&option=newcollection">Create one?</a><br /><br /></div>';
        
        }
        
        echo'
        <br /><br />
        </div>
        </div>';
        
    }
    
        
?>

</div>
</div>


<body style="overflow-x:hidden; background-image: url('graphics/linen.png');">

<?php navbar(); ?>

   <!--big container-->
    <div id="container" class="container_24" style="width:1200px;overflow:hidden;">
    
       <div class="galleryToolbar" style="margin-top:70px;margin-left:70px;">
            <ul>
                <a style="color:#333;" href="newest.php"><li style="width:550px;-webkit-border-radius: 4px;-moz-border-radius: 2px;border-radius: 2px;padding-left:8px;margin-left:0px;text-align:left;font-size:30px;"><img style="float:left;width:22px;height:22px;" src="graphics/picture.png" />&nbsp;&nbsp;Discover Amazing Photography</li></a>
                <li style="float:right;width:170px;height:20px;margin-right:-10px;"><a style="font-size:14px;font-weight:500;color:#fff;margin-left:10px;" class="btn btn-primary" href="discover.php?image=<?php echo $nextimage; ?>">Click to Discover</a></li>
                <a style="color:#333;" href="profile.php?view=about&option=editinfo&error=disc#disc"><li style="font-size:14px;float:right;width:170px;padding-top:14px;padding-bottom:12px;padding-right:5px;height:20px;margin-top:-1px;margin-right:-4px;"><img style="width:16px;height:16px;" src="graphics/tick 2.png" /> Choose Preferences</li></a>
            </ul>
        </div>
    
<?php
    
    $result = mysql_query("SELECT * FROM photos WHERE faves > 6 ORDER BY RAND() LIMIT 0,19 ");
    
    if($image == '' || $image == 'https://photorankr.com/') {

echo'
    <div id="thepics" style="position:relative;left:40px;top:10px;width:1210px;">
    <div id="main">
    <ul id="tiles">';
        
for($iii=1; $iii <= 19; $iii++) {
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
    $heightls = $height / 3.3;
    $widthls = $width / 3.3;
    if($widthls < 185) {
    $heightls = $heightls * ($heightls/$widthls);
    $widthls = 240;
    }

		echo '
        <a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$id,'&v=n"><li class="fPic" id="',$id,'" style="list-style-type: none;width:280px;"><img style="min-width:280px;" src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" />
        
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
</div>';

} //end of view == ''

elseif($image != ''){
?>
<div class="bloc_4" style="float:right;width:24.07%;margin:45px 0 0 0;display:block;">

		<!--ID TAG-->
		<div id="Tag">
			<div id="topHalf">
				<img src="../<?php echo $profilepic; ?>" />
				<header style="min-width:100px;">
                    <a href="viewprofile.php?u=<?php echo $user; ?>">
                        <?php echo $fullname; ?>
                    </a> 
                </header>
                
                 <?php
                    if($_SESSION['loggedin'] == 1) {
                        $emailquery=("SELECT * FROM userinfo WHERE emailaddress ='$email'");
                        $emailresult=mysql_query($emailquery);
                        $prevemails=mysql_result($emailresult, 0, "following");
                        $viewerfirst = mysql_result($emailresult, 0, "firstname");
                        $viewerlast = mysql_result($emailresult, 0, "lastname");
                        $search_string=$prevemails;
                        $regex="/$emailaddress/";
                        $match=preg_match($regex,$search_string);

                        if($match) {
                            echo'<a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$imageid,'&v=',$view,'&uf=1"><button id="follow"> Following </button></a>';
                        }
                        elseif(!$match) {
                            echo'<a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$imageid,'&v=',$view,'&fw=1"><button id="follow"> Follow </button></a>';
                        }
                    }
                    else {
                        echo'<button id="follow"> Follow </button>';
                    }
                ?>

			</div>
			<div id="bottomHalf">  
				<header><img src="graphics/camera2.png" style="width:15px;margin-top:-3px;padding-right:3px;" /> Photos &mdash; 345 </header>
				<header> Rep &mdash; <?php echo $reputation; ?> </header>
			</div>	
		</div>
        
		<!--STATS BAR-->
		<div id="statsBar" style="width:320px;">
			<ul class="numbers">
				<li id="ajaxRank"><?php echo $ranking; ?><span>/10</span> </li>
				<li> <?php echo $collected; ?> </li>
				<li id="ajaxFave"> <?php echo $faves; ?> </li>
				<li> <?php echo $price; ?></li>
			</ul>
			<ul>
				<li id="rankButton">  <img src="graphics/rank_b_c.png" /> Rank </li>
				<a style="color:#333;text-decoration:none;" data-toggle="modal" data-backdrop="static" href="#collectionmodal">
                <li style="border-right:1px solid #c6c6c6;width:42px;"><img style="width:32px;height:35px;" src="graphics/collection_b_c.png" />  Collect </li>
                </a>
                
                <?php
                    if($_SESSION['loggedin'] == 1) {
                        $vieweremail = $_SESSION['email'];
                        //run a query to be used to check if the image is already there
                        $check = mysql_query("SELECT * FROM userinfo WHERE emailaddress='$vieweremail'") or die(mysql_error());
                        $viewerfirst = mysql_result($check, 0, "firstname");
                        $viewerlast = mysql_result($check, 0, "lastname");
                        $imagelink2=str_replace(" ","", $image);
                        //create the image variable to be used in the query, appropriately escaped
                        $queryimage = "'" . $image . "'";
                        $queryimage = ", " . $queryimage;
                        $queryimage = addslashes($queryimage);
                        //search for the image in the database as a check for repeats
                        $mycheck = mysql_result($check, 0, "faves");
                        $search_string = $mycheck;
                        $regex=$image;
                        $match=strpos($search_string, $regex);

                        if($match) {
                            echo'<li> <img src="graphics/fave_b_c.png"/> Fave </li>';
                        }
                        elseif(!$match) {
                            echo'<li onclick="ajaxFunction()"> <img src="graphics/fave_b_c.png"/> Fave </li>';
                        }
                    }
                    else {
                        echo'<li> <img src="graphics/fave_b_c.png"/> Fave </li>';
                    }
                ?>
                
                <a style="color:#333;text-decoration:none;" href="fullsizemarket.php?imageid=<?php echo $imageid; ?>"><li> <img src="graphics/market_b_c.png"/> Purchase </li></a>
			</ul>

			<ul id="Rank">
				<li onclick="Rank(1)"> 1 </li>
				<li onclick="Rank(2)"> 2 </li>
				<li onclick="Rank(3)"> 3 </li>
				<li onclick="Rank(4)"> 4 </li>
				<li onclick="Rank(5)"> 5 </li>
				<li onclick="Rank(6)"> 6 </li>
				<li onclick="Rank(7)"> 7 </li>
				<li onclick="Rank(8)"> 8 </li>
				<li onclick="Rank(9)"> 9 </li>
				<li onclick="Rank(10)"> 10 </li>
			</ul>

		</div>

    
    <!--ABOUT PHOTO-->
		<div id="AboutPhoto" style="margin-top:150px;">
			<header> About </header>
			<ul>
                <?php 
                 if($exhibit) {
                        echo'
						<li><img src="graphics/grid.png"/>  Exhibit: <a class="click" href="viewprofile.php?u=',$user,'&view=exhibits&set=',$exhibit,'"><u>',$exhibitname,'</u></a></li>'; 
                    }
                    
                    if($exhibit && $expic1 && $expic2 && $expic3) {
                        echo'
						<li style="clear:both;margin-left:5px;overflow:hidden;margin-left:-10px;width:250px;">
                        <a href="fullsize.php?image=',$expic1,'&view=',$view,'"><img style="float:left;padding:2px;" src="https://photorankr.com/',$exthumb1,'" height="80" width="78" /></a> 
                        <a href="fullsize.php?image=',$expic2,'&view=',$view,'"><img style="float:left;padding:2px;" src="https://photorankr.com/',$exthumb2,'" height="80" width="78" /></a> 
                        <a href="fullsize.php?image=',$expic3,'&view=',$view,'"><img style="float:left;padding:2px;" src="https://photorankr.com/',$exthumb3,'" height="80" width="78" /></a> 
                        </li>';
                    }
                if($views) {
				echo'<li><img src="graphics/views.png"/>  Views: <span style="margin-left:38px;">',$views,'</span></li>';
                }
                if($camera) {
				echo'<li><img src="graphics/camera.png"/> Camera: <span style="margin-left:28px;">',$camera,'</span></li>';
                }
                if($aperture) {
				echo'<li><img src="graphics/aperature.png"/> Aperture: <span style="margin-left:24px;">',$aperture,'</span></li>';
                }
                if($focallength) {
				echo'<li> <img src="graphics/focalLength.png"/> Focal Length:  <span style="margin-left:3px;">',$focallength,'</span> </li>';
                }
                if($lens) {
				echo'<li> <img src="graphics/lens.png"/> Lens: <span style="margin-left:42px;">',$lens,'</span> </li>';
                }
                if($shutterspeed) {
				echo'<li> <img src="graphics/shutterSpeed.png"/> Shutter: <span style="margin-left:30px;">',$shutterspeed,'</span> </li>';
                }
                if($date) {
                    echo'<li> <img src="graphics/captureDate.png" style="width:16px;margin-left:-3px;"/> Capture Date <span> <?php echo $date; ?> </span></li>';
                }
                if($fullname) {
                    echo'<li> <img src="graphics/copyright.png" style="width:15px;margin-left:-2px;"/> Copyright <span> <?php echo $fullname; ?> </span></li>';
                }
				if($location) { 
                    echo'<li> <img src="graphics/location.png" style="width:10px;margin: 0 8px 0 0;"/> Location: <span> <?php echo $location; ?> </span></li>';
                }

                ?>
			</ul>
		</div>
        

	</div>
	
	<!--TITLE-->
	<div class="bloc_12" style="float:left;margin-left:70px;display:block;width:800px;" id="title">
		<header> <?php echo $caption; ?> <span> <?php echo $time; ?> </span> <img style="margin-right:4px;" src="graphics/arrow 4.png"/>  </header>
	</div>

	<!--IMAGE-->
	<div class="bloc_12" style="float:left;margin-left:70px;display:block;width:800px;" id="imgDisplay">
		<img style="width:<?php echo $newwidth; ?>;height:<?php echo $newheight; ?>;" src="<?php echo $image; ?>" />
	</div>

	<!--COMMENTS-->
	<div class="bloc_12" style="float:left;width:800px;margin-left:70px;">

        <?php
        //AJAX COMMENT
        if($_SESSION['loggedin'] == 1) {
            echo'
            <div style="width:820px;margin-top:30px;position:relative;left:0px;"> 
            
            <div style="float:left;">
                <img id="commentPhoto" src="https://photorankr.com/',$sessionpic,'" height="55" width="50" />
            </div>
            
            <div style="float:left;margin-left:14px;margin-top:5px;">
                <div class="commentTriangle"></div>
            <form action="#" method="post" style="margin-top:5px;padding-bottom:5px;">        
                <textarea id="photocomment" style="margin-left:0px;margin-top:-10px;width:740px;height:45px;font-size:15px;padding:5px;resize:none;color:#333;" placeholder="Leave feedback for ',$firstname,' &#8230;"></textarea>
                    <div id="button_block">
                        <div class="postCommentBtn">
                            <img style="float:left" src="graphics/comment_1.png" height="16" width="16" />&nbsp;&nbsp;
                            <a href="#" style="color:#333;text-decoration:none;" id="postComment">Post Feedback</a>
                            
                        </div>
                    </div>
            </form>
        </div>
        
            <!--AJAX COMMENTS-->
            <div class="float:left;"> 
                <ol id="update" class="timeline">
                </ol>
            </div>';
        }
            
        //SHOW PREVIOUS COMMENTS
        $grabcomments = mysql_query("SELECT * FROM comments WHERE imageid = '$imageID' ORDER BY id DESC");
        $numcomments = mysql_num_rows($grabcomments);
        
        for($iii = 0; $iii < $numcomments; $iii++) {
        
            $comment = mysql_result($grabcomments,$iii,'comment');
            $commentid = mysql_result($grabcomments,$iii,'id');
            $commenttime = mysql_result($grabcomments,$iii,'time');
            //$commenttime = converttime($commenttime);
            $commenteremail = mysql_result($grabcomments,$iii,'commenter');
            $commenterinfo = mysql_query("SELECT user_id,firstname,lastname,profilepic,reputation FROM userinfo WHERE emailaddress = '$commenteremail'");
            $commentername = mysql_result($commenterinfo,0,'firstname') ." ". mysql_result($commenterinfo,0,'lastname');
            $commenterid = mysql_result($commenterinfo,0,'user_id');
            $commenterpic = mysql_result($commenterinfo,0,'profilepic');
            $commenterrep = number_format(mysql_result($commenterinfo,0,'reputation'),2);
        
        
        echo'
            <div id="comment" style="width:820px;clear:both;margin-left:0px;">
                <div id="commentProfPic">
                    <img src="https://photorankr.com/',$commenterpic,'" height="55" width="50" />
                </div>
            
        <div style="position:relative;left:15px;">
        
            <div class="commentTriangle" style="margin-top:-18px;"></div>

			<div class="commentName">
				<header><span style="font-size:14px;">',$commenterrep,'</span> <a href="viewprofile.php?u=',$commenterid,'">',$commentername,'</a> </header>
				<p> ',$commenttime,' </p>&nbsp;
                <img style="padding-right:3px;" src="graphics/clock.png"/>&nbsp;
			</div>
			<div class="commentBody"><p>',$comment,'</p>';
            
                if($commenterid == $sessionid) {
                    echo'
                        <div id="edit"><a href="fullsize.php?image=',$image,'&action=editcomment&cid=',$commentid,'#',$commentid,'"> Edit Comment</a></div>';
                }
                
                 if(($email == $emailaddress) || ($commenteremail == $email)) {
                    echo'
                        <div id="edit"><a href="fullsize.php?image=',$image,'&action=deletecomment&cid=',$commentid,'">Delete Comment</a></div>';
                }
                
                if($_GET['action'] == 'editcomment' && $commentid == $_GET['cid']) {
                
                    echo'
                    <form action="fullsize.php?image=',$image,'#',$commentid,'" method="POST" />
                    <textarea style="height:55px;width:95%;margin-left:10px;margin-top:10px;" name="commentedit">',$comment,'</textarea>
                    <input type="hidden" name="commentid" value="',$commentid,'" />
                    <br />
                    <input type="submit" class="btn btn-primary" style="float:right;font-size:12px;margin-right:10px;margin-bottom:5px;" value="Save Edit" />
                    </form>';
                    
                }
            
            echo'
            </div>
          </div>
		</div>';
            
        }
        
        $imagenew=str_replace("userphotos/","", $image);
        $imagelink=str_replace(" ","", $image);
        $searchchars=array('.jpg','.png','.tiff','.JPG','.jpeg','.JPEG','.gif');
        $imagenew=str_replace($searchchars,"", $imagenew);
        $txt=".txt";
        $file = "comments/" . $imagenew . $txt;
        echo'
        <div style="float:left;width:520px;padding:10px;font-size:13px;font-family:helvetica;font-weight:300;color:#555;">';
        @include("$file"); 
        echo'</div>';
                
    ?>
        
</div>

</div>

<?php
   
} //end of iamge != '' view


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