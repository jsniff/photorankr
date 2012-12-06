<?php

//connect to the database
require "../db_connection.php";
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

    //QUERY FOR NOTIFICATIONS
    $currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$email'";
    $currentnotsquery = mysql_query($currentnots);
    $currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");

    //notifications query reset 
    if($currentnotsresult > 0) {
    $notsquery = "UPDATE userinfo SET notifications = 0 WHERE emailaddress = '$email6'";
    $notsqueryrun = mysql_query($notsquery); }
    
    //DE-HIGHLIGHT NOTIFICATIONS IF CLICKED ON

    if(isset($_GET['id'])){
        $id = htmlentities($_GET['id']);
        $idformatted = $id . " ";
        $unhighlightquery = "UPDATE userinfo SET unhighlight = CONCAT(unhighlight,'$idformatted') WHERE emailaddress = '$email'";
        $unhighlightqueryrun = mysql_query($unhighlightquery);
    }
    
    //GET THE IMAGE
$image = addslashes($_GET['image']);
$view = htmlentities($_GET['v']);

if(!$image) {

    $imageid = addslashes($_GET['imageid']);
    $imagequery = mysql_query("SELECT source FROM photos WHERE id = '$imageid'");
    $image = mysql_result($imagequery,0,'source');
    
}

if(!$imageid) {

    $imagequery = mysql_query("SELECT id FROM photos WHERE source = '$image'");
    $imageid = mysql_result($imagequery,0,'id');

} 

//if the url does not contain an image send them back to trending
if(!$image) {
	header("Location: trending.php");
	exit();
}

//FIND THE PHOTO IN DATABASE
$query="SELECT * FROM photos where source='$image'";
$result=mysql_query($query);
//if no images match what is in the url, then send them back to trending 
if(mysql_num_rows($result) == 0) {
	header("Location: trending.php");
	exit();
}

$row=mysql_fetch_array($result);
$emailaddress=$row['emailaddress'];
$caption=$row['caption'];
$location=$row['location'];
$country=$row['country'];
$time=$row['time'];
$faves=$row['faves'];
$prevpoints=$row['points'];
$prevvotes=$row['votes'];
$ranking=number_format(($prevpoints/$prevvotes),1);
$imageID=$row['id'];
$price=mysql_result($result, 0, "price");
$camera = mysql_result($result,0,"camera");
if($camera) {
$camera = '<a style="color:black;" href="search.php?searchterm='.$camera.'">' . $camera . '</a>';
}
$faves= $row['faves'];
$views = $row['views'];
$exhibit = $row['set_id'];

$exquery = mysql_query("SELECT source FROM photos WHERE set_id = '$exhibit' AND emailaddress = '$emailaddress' ORDER BY (points/votes) DESC LIMIT 0,3");
$expic1 = mysql_result($exquery,0,'source');
$exthumb1 = str_replace("userphotos/","userphotos/medthumbs/",$expic1);
$expic2 = mysql_result($exquery,1,'source');
$exthumb2 = str_replace("userphotos/","userphotos/medthumbs/",$expic2);
$expic3 = mysql_result($exquery,2,'source');
$exthumb3 = str_replace("userphotos/","userphotos/medthumbs/",$expic3);

$sold = $row['sold'];
$exhibitname = $row['sets'];
$focallength = $row['focallength'];
$shutterspeed = $row['shutterspeed'];
$aperture = $row['aperture'];
$iso = $row['iso'];
$software = $row['software'];
$lens = $row['lens'];
$filter = $row['filter'];
$copyright = $row['copyright'];
$about = $row['about'];

$tag1 = $row['tag1'];

if($tag1) {
$tag1 = '<a style="color:black;" href="search.php?searchterm='.$tag1.'">'.$tag1.'</a>';
$tag1 = $tag1 . ", ";
}

$tag2 = $row['tag2'];

if($tag2) {
$tag2 = '<a style="color:black;" href="search.php?searchterm='.$tag2.'">'.$tag2.'</a>';
$tag2 = $tag2 . ", ";
}

$tag3 = $row['tag3'];

if($tag3) {
$tag3 = '<a style="color:black;" href="search.php?searchterm='.$tag3.'">'.$tag3.'</a>';
$tag3 = $tag3 . ", ";
}

$tag4 = $row['tag4'];

if($tag4) {
$tag4 = '<a style="color:black;" href="search.php?searchterm='.$tag4.'">'.$tag4.'</a>';
$tag4 = $tag4 . ", ";
}

$singlestyletags = $row['singlestyletags'];
$singlecategorytags = $row['singlecategorytags'];
$singlestyletagsarray = explode("  ", $singlestyletags);
$singlecategorytagsarray   = explode("  ", $singlecategorytags);
for($iii=0; $iii < count($singlestyletagsarray); $iii++) {
if($singlestyletagsarray[$iii] != '') {
    $singlestyletagsfinal .= '<a style="color:black;" href="search.php?searchterm='.$singlestyletagsarray[$iii].'">' . $singlestyletagsarray[$iii] . '</a>' . ", "; }
    }
    for($iii=0; $iii < count($singlecategorytagsarray); $iii++) {
        if($singlecategorytagsarray[$iii] != '') {
        $singlecategorytagsfinal .= '<a style="color:black;" href="search.php?searchterm='.$singlecategorytagsarray[$iii].'">' . $singlecategorytagsarray[$iii] . '</a>' . ", "; }
    }
    
$keywords = $tag1 . $tag2 . $tag3 . $tag4 . $singlestyletagsfinal . $singlecategorytagsfinal;
$keywords = substr_replace($keywords ," ",-2);
    

//find how many photos the photographer has
$numberofpics = mysql_query("SELECT * FROM photos WHERE emailaddress='$emailaddress'");
$numberofpics = mysql_num_rows($numberofpics);

$locationandcountry = $location . $country;

if ($price == "0.00") {$price='Free';}  
elseif ($price == "Not For Sale") {$price='NFS';}
elseif ($price == "$NFS") {$price='NFS';}
elseif ($price == "") {$price='';}   
else {$price = '$' . $price; }  

//FIND THE PHOTOGRAPHER NAME IN DATABASE
$namequery="SELECT * FROM userinfo WHERE emailaddress='$emailaddress'";
$nameresult=mysql_query($namequery);
$row=mysql_fetch_array($nameresult);
$user=$row['user_id'];

$firstname=$row['firstname'];
$lastname=$row['lastname'];
$reputation=number_format($row['reputation'],2);
$promos = mysql_result($nameresult,0,'promos');
$fullname = $firstname . " " . $lastname;
$fullname = (strlen($fullname ) > 14) ? substr($fullname,0,12). " &#8230;" :$fullname;

$profilepic=$row['profilepic'];
$profilescore=$row['totalscore'];

//calculate the size of the picture
$maxwidth=800;
$maxheight=800;

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

//if they came from trending
if($view == 't') {
	//find where this pic is in the order
	$index = findPicTrend($image);
	if($index == 0) {
		$testquery = mysql_query("SELECT * FROM photos ORDER BY score ASC");
		$imageBefore = mysql_result($testquery, 0, "source");
		$index++;
		$nextThreeQuery = "SELECT * FROM photos ORDER BY score DESC LIMIT $index, 6";
		$nextThreeResult = mysql_query($nextThreeQuery);
		$imageOne = mysql_result($nextThreeResult, 0, "source");
		$imageOneThumb = str_replace("userphotos/","userphotos/thumbs/", $imageOne);
		$imageTwo = mysql_result($nextThreeResult, 1, "source");
		$imageTwoThumb = str_replace("userphotos/","userphotos/thumbs/", $imageTwo);
		$imageThree = mysql_result($nextThreeResult, 2, "source");
		$imageThreeThumb = str_replace("userphotos/","userphotos/thumbs/", $imageThree);
        	$imageFour = mysql_result($nextThreeResult, 3, "source");
		$imageFourThumb = str_replace("userphotos/","userphotos/thumbs/", $imageFour);
        	$imageFive = mysql_result($nextThreeResult, 4, "source");
		$imageFiveThumb = str_replace("userphotos/","userphotos/thumbs/", $imageFive);
        
	}
	else {
		$index--;
		$nextThreeQuery = "SELECT * FROM photos ORDER BY score DESC LIMIT $index, 7";
		$nextThreeResult = mysql_query($nextThreeQuery);
		$numpics = mysql_num_rows($nextThreeResult);
		$imageBefore = mysql_result($nextThreeResult, $x, "source");
        	
		if(7-$numpics == 1) {
			$resetquery = mysql_query("SELECT * FROM photos ORDER BY score DESC LIMIT 0, 1");
			$imageOne = mysql_result($nextThreeResult, 2, "source");
			$imageTwo = mysql_result($nextThreeResult, 3, "source");
			$imageThree = mysql_result($nextThreeResult, 4, "source");	
			$imageFour = mysql_result($nextThreeResult, 5, "source");	
			$imageFive = mysql_result($resetquery, 0, "source");
		}
		else if(7-$numpics == 2) {
			$resetquery = mysql_query("SELECT * FROM photos ORDER BY score DESC LIMIT 0, 2");
			$imageOne = mysql_result($nextThreeResult, 2, "source");
			$imageTwo = mysql_result($nextThreeResult, 3, "source");
			$imageThree = mysql_result($nextThreeResult, 4, "source");
			$imageFour = mysql_result($resetquery, 0, "source");
			$imageFive = mysql_result($resetquery, 1, "source");
		}
		else if(7-$numpics == 3) {
			$resetquery = mysql_query("SELECT * FROM photos ORDER BY score DESC LIMIT 0, 3");
			$imageOne = mysql_result($nextThreeResult, 2, "source");
			$imageTwo = mysql_result($nextThreeResult, 3, "source");
			$imageThree = mysql_result($resetquery, 0, "source");
			$imageFour = mysql_result($resetquery, 1, "source");
			$imageFive = mysql_result($resetquery, 2, "source");
		}
		else if(7-$numpics == 4) {
			$resetquery = mysql_query("SELECT * FROM photos ORDER BY score DESC LIMIT 0, 4");
			$imageOne = mysql_result($nextThreeResult, 2, "source");
			$imageTwo = mysql_result($resetquery, 0, "source");
			$imageThree = mysql_result($resetquery, 1, "source");
			$imageFour = mysql_result($resetquery, 2, "source");
			$imageFive = mysql_result($resetquery, 3, "source");
		}
		else if(7-$numpics == 5) {
			$resetquery = mysql_query("SELECT * FROM photos ORDER BY score DESC LIMIT 0, 5");
			$imageOne = mysql_result($resetquery, 0, "source");
			$imageTwo = mysql_result($resetquery, 1, "source");
			$imageThree = mysql_result($resetquery, 2, "source");
			$imageFour = mysql_result($resetquery, 3, "source");
			$imageFive = mysql_result($resetquery, 4, "source");
		}
		else {
			$imageOne = mysql_result($nextThreeResult, 2, "source");
			$imageTwo = mysql_result($nextThreeResult, 3, "source");
			$imageThree = mysql_result($nextThreeResult, 4, "source");	
			$imageFour = mysql_result($nextThreeResult, 5, "source");		
			$imageFive = mysql_result($nextThreeResult, 6, "source");
		}
		
		$imageOneThumb = str_replace("userphotos/","userphotos/thumbs/", $imageOne);
		$imageTwoThumb = str_replace("userphotos/","userphotos/thumbs/", $imageTwo);
		$imageThreeThumb = str_replace("userphotos/","userphotos/thumbs/", $imageThree);
		$imageFourThumb = str_replace("userphotos/","userphotos/thumbs/", $imageFour);		
		$imageFiveThumb = str_replace("userphotos/","userphotos/thumbs/", $imageFive);
	}
}

//else if they came from newest
else if($view == 'n') {
	//find where this pic is in the order
	$index = findPicNew($image);
	if($index == 0) {
		$testquery = mysql_query("SELECT * FROM photos ORDER BY id ASC");
		$imageBefore = mysql_result($testquery, 0, "source");
		$index++;
		$nextThreeQuery = "SELECT * FROM photos ORDER BY id DESC LIMIT $index, 5";
		$nextThreeResult = mysql_query($nextThreeQuery);
		$imageOne = mysql_result($nextThreeResult, 0, "source");
		$imageOneThumb = str_replace("userphotos/","userphotos/thumbs/", $imageOne);
		$imageTwo = mysql_result($nextThreeResult, 1, "source");
		$imageTwoThumb = str_replace("userphotos/","userphotos/thumbs/", $imageTwo);
		$imageThree = mysql_result($nextThreeResult, 2, "source");
		$imageThreeThumb = str_replace("userphotos/","userphotos/thumbs/", $imageThree);
        	$imageFour = mysql_result($nextThreeResult, 3, "source");
		$imageFourThumb = str_replace("userphotos/","userphotos/thumbs/", $imageFour);
        	$imageFive = mysql_result($nextThreeResult, 4, "source");
		$imageFiveThumb = str_replace("userphotos/","userphotos/thumbs/", $imageFive);
	}
	else {
		$index--;
		$nextThreeQuery = "SELECT * FROM photos ORDER BY id DESC LIMIT $index, 7";
		$nextThreeResult = mysql_query($nextThreeQuery);
		$numpics = mysql_num_rows($nextThreeResult);
		$imageBefore = mysql_result($nextThreeResult, $x, "source");
        	
		if(7-$numpics == 1) {
			$resetquery = mysql_query("SELECT * FROM photos ORDER BY id DESC LIMIT 0, 1");
			$imageOne = mysql_result($nextThreeResult, 2, "source");
			$imageTwo = mysql_result($nextThreeResult, 3, "source");
			$imageThree = mysql_result($nextThreeResult, 4, "source");	
			$imageFour = mysql_result($nextThreeResult, 5, "source");	
			$imageFive = mysql_result($resetquery, 0, "source");
		}
		else if(7-$numpics == 2) {
			$resetquery = mysql_query("SELECT * FROM photos ORDER BY id DESC LIMIT 0, 2");
			$imageOne = mysql_result($nextThreeResult, 2, "source");
			$imageTwo = mysql_result($nextThreeResult, 3, "source");
			$imageThree = mysql_result($nextThreeResult, 4, "source");
			$imageFour = mysql_result($resetquery, 0, "source");
			$imageFive = mysql_result($resetquery, 1, "source");
		}
		else if(7-$numpics == 3) {
			$resetquery = mysql_query("SELECT * FROM photos ORDER BY id DESC LIMIT 0, 3");
			$imageOne = mysql_result($nextThreeResult, 2, "source");
			$imageTwo = mysql_result($nextThreeResult, 3, "source");
			$imageThree = mysql_result($resetquery, 0, "source");
			$imageFour = mysql_result($resetquery, 1, "source");
			$imageFive = mysql_result($resetquery, 2, "source");
		}
		else if(7-$numpics == 4) {
			$resetquery = mysql_query("SELECT * FROM photos ORDER BY id DESC LIMIT 0, 4");
			$imageOne = mysql_result($nextThreeResult, 2, "source");
			$imageTwo = mysql_result($resetquery, 0, "source");
			$imageThree = mysql_result($resetquery, 1, "source");
			$imageFour = mysql_result($resetquery, 2, "source");
			$imageFive = mysql_result($resetquery, 3, "source");
		}
		else if(7-$numpics == 5) {
			$resetquery = mysql_query("SELECT * FROM photos ORDER BY id DESC LIMIT 0, 5");
			$imageOne = mysql_result($resetquery, 0, "source");
			$imageTwo = mysql_result($resetquery, 1, "source");
			$imageThree = mysql_result($resetquery, 2, "source");
			$imageFour = mysql_result($resetquery, 3, "source");
			$imageFive = mysql_result($resetquery, 4, "source");
		}
		else {
			$imageOne = mysql_result($nextThreeResult, 2, "source");
			$imageTwo = mysql_result($nextThreeResult, 3, "source");
			$imageThree = mysql_result($nextThreeResult, 4, "source");	
			$imageFour = mysql_result($nextThreeResult, 5, "source");		
			$imageFive = mysql_result($nextThreeResult, 6, "source");
		}
		
		$imageOneThumb = str_replace("userphotos/","userphotos/thumbs/", $imageOne);
		$imageTwoThumb = str_replace("userphotos/","userphotos/thumbs/", $imageTwo);
		$imageThreeThumb = str_replace("userphotos/","userphotos/thumbs/", $imageThree);
		$imageFourThumb = str_replace("userphotos/","userphotos/thumbs/", $imageFour);		
		$imageFiveThumb = str_replace("userphotos/","userphotos/thumbs/", $imageFive);
	}
}


//else if they came from topranked
else if($view == 'r') {
	//find where this pic is in the order
	$index = findPicTop($image);
	if($index == 0) {
		$testquery = mysql_query("SELECT * FROM photos ORDER BY points ASC");
		$imageBefore = mysql_result($testquery, 0, "source");
		$index++;
		$nextThreeQuery = "SELECT * FROM photos ORDER BY points DESC LIMIT $index, 5";
		$nextThreeResult = mysql_query($nextThreeQuery);
		$imageOne = mysql_result($nextThreeResult, 0, "source");
		$imageOneThumb = str_replace("userphotos/","userphotos/thumbs/", $imageOne);
		$imageTwo = mysql_result($nextThreeResult, 1, "source");
		$imageTwoThumb = str_replace("userphotos/","userphotos/thumbs/", $imageTwo);
		$imageThree = mysql_result($nextThreeResult, 2, "source");
		$imageThreeThumb = str_replace("userphotos/","userphotos/thumbs/", $imageThree);
        	$imageFour = mysql_result($nextThreeResult, 3, "source");
		$imageFourThumb = str_replace("userphotos/","userphotos/thumbs/", $imageFour);
        	$imageFive = mysql_result($nextThreeResult, 4, "source");
		$imageFiveThumb = str_replace("userphotos/","userphotos/thumbs/", $imageFive);
	}
	else {
		$index--;
		$nextThreeQuery = "SELECT * FROM photos ORDER BY points DESC LIMIT $index, 7";
		$nextThreeResult = mysql_query($nextThreeQuery);
		$numpics = mysql_num_rows($nextThreeResult);
		$imageBefore = mysql_result($nextThreeResult, $x, "source");
        	
		if(7-$numpics == 1) {
			$resetquery = mysql_query("SELECT * FROM photos ORDER BY points DESC LIMIT 0, 1");
			$imageOne = mysql_result($nextThreeResult, 2, "source");
			$imageTwo = mysql_result($nextThreeResult, 3, "source");
			$imageThree = mysql_result($nextThreeResult, 4, "source");	
			$imageFour = mysql_result($nextThreeResult, 5, "source");	
			$imageFive = mysql_result($resetquery, 0, "source");
		}
		else if(7-$numpics == 2) {
			$resetquery = mysql_query("SELECT * FROM photos ORDER BY points DESC LIMIT 0, 2");
			$imageOne = mysql_result($nextThreeResult, 2, "source");
			$imageTwo = mysql_result($nextThreeResult, 3, "source");
			$imageThree = mysql_result($nextThreeResult, 4, "source");
			$imageFour = mysql_result($resetquery, 0, "source");
			$imageFive = mysql_result($resetquery, 1, "source");
		}
		else if(7-$numpics == 3) {
			$resetquery = mysql_query("SELECT * FROM photos ORDER BY points DESC LIMIT 0, 3");
			$imageOne = mysql_result($nextThreeResult, 2, "source");
			$imageTwo = mysql_result($nextThreeResult, 3, "source");
			$imageThree = mysql_result($resetquery, 0, "source");
			$imageFour = mysql_result($resetquery, 1, "source");
			$imageFive = mysql_result($resetquery, 2, "source");
		}
		else if(7-$numpics == 4) {
			$resetquery = mysql_query("SELECT * FROM photos ORDER BY points DESC LIMIT 0, 4");
			$imageOne = mysql_result($nextThreeResult, 2, "source");
			$imageTwo = mysql_result($resetquery, 0, "source");
			$imageThree = mysql_result($resetquery, 1, "source");
			$imageFour = mysql_result($resetquery, 2, "source");
			$imageFive = mysql_result($resetquery, 3, "source");
		}
		else if(7-$numpics == 5) {
			$resetquery = mysql_query("SELECT * FROM photos ORDER BY points DESC LIMIT 0, 5");
			$imageOne = mysql_result($resetquery, 0, "source");
			$imageTwo = mysql_result($resetquery, 1, "source");
			$imageThree = mysql_result($resetquery, 2, "source");
			$imageFour = mysql_result($resetquery, 3, "source");
			$imageFive = mysql_result($resetquery, 4, "source");
		}
		else {
			$imageOne = mysql_result($nextThreeResult, 2, "source");
			$imageTwo = mysql_result($nextThreeResult, 3, "source");
			$imageThree = mysql_result($nextThreeResult, 4, "source");	
			$imageFour = mysql_result($nextThreeResult, 5, "source");		
			$imageFive = mysql_result($nextThreeResult, 6, "source");
		}
		
		$imageOneThumb = str_replace("userphotos/","userphotos/thumbs/", $imageOne);
		$imageTwoThumb = str_replace("userphotos/","userphotos/thumbs/", $imageTwo);
		$imageThreeThumb = str_replace("userphotos/","userphotos/thumbs/", $imageThree);
		$imageFourThumb = str_replace("userphotos/","userphotos/thumbs/", $imageFour);		
		$imageFiveThumb = str_replace("userphotos/","userphotos/thumbs/", $imageFive);
	}
}

?>

<!DOCTYPE HTML>
<head>
	<meta charset = "UTF-8">
	<title> Sell, share and discover brilliant photography </title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
	<link rel="stylesheet" type="text/css" href="css/grid.css"/>
	<link rel="stylesheet" type="text/css" href="css/reset.css"/> 
	<link rel="stylesheet" media='screen and (max-width:640px)' href="css/640.css"/>

	<script src="js/modernizer.js"></script>
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
</head>
<body style="overflow-x:hidden; background-color: #cccddd;">

<?php navbar(); ?>

<div class="container_custom"style="margin:50px 0 0 80px;">
	<div class="bloc_12" style="float:left;display:block;">
			<hgroup class=" title">  
				<header> <?php echo $caption; ?> </header>	
			
			</hgroup>
			
			<div class="bloc_12"style="text-align:center;"><a style="display:block;" href="">
				<img src="../<?php echo $image; ?>" style="max-height:800px;margin:0 auto!important;max-width:800px;border-radius:5px;box-shadow: 0 1px 2px #333;margin: 0 0 0 10px;"/>
				</a>
			</div>
		</div>
		<div class="bloc_5" style="float:right;margin-top:5px;margin-right:45px;padding-left:5px;padding-right:20px;">
		<div class="bloc_5 Info" >
					<img src="../<?php echo $profilepic; ?>" />
					<p> <?php echo $fullname; ?> </p>
					<p> Rep: 90/100</p>
					<p> Photos: 987 </p>
					<button style="float:right;margin:-25px 10px 0 0;padding:5px;"> Follow </button>
							
		</div>	
			<div class="bloc_5 Info">
				<ul>
					<li>
						<img src="graphics/rank_i.png" />
						<p> 3.45<span>/10</span> </p>
						<p> Rank </p>
					</li>
					<li>
						<img src="graphics/collection_i.png" />
						
						<p> 599 </p>
						<p> Collect </p>
					</li>
					<li>
						<img src="graphics/cart_i.png" />
						
						<p> $56 </p>
						<p> Purchase </p>
					</li>
					<li>
						<img src="graphics/fave_i.png" />
						
						<p> 897 </p>
						<p> Favorite </p>
					</li>
					
				</ul>			
		</div>
		<p style="margin-left:15px;">Share <img src="graphics/social.png" width="200px"/></p>
		<div class="bloc_5 storyFS" id="aboutFS">
			<header> Behind the Lens </header>
			<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. </p>
		</div>
        
        <div class="bloc_5"><!--Next photos-->
            
                <?php 
                    
                    if($view == 't') {
                    echo'<span style="font-family:helvetica;font-weight:100;font-size:14px;">Browse More Trending Photos:</span>';
                    }
                    
                    elseif($view == 'n') {
                    echo'<span style="font-family:helvetica;font-weight:100;font-size:14px;">Browse More Newest Photos:</span>';
                    }
                    
                    elseif($view == 'r') {
                    echo'<span style="font-family:helvetica;font-weight:100;font-size:14px;">Browse More Top Ranked Photos:</span>';
                    }
            
                ?>
				
                <div id="images" style="margin-top:5px;padding-left:10px;">
					<a id="nextimg1id" href="fullsize.php?image=<?php echo $imageOne; ?>&v=<?php echo $view; ?>"><img src="../<?php echo $imageOneThumb; ?>" id="nextimg1"/></a>
				</div>
                
				<div class="nextimg">
					<a id="nextimg2id" href="fullsize.php?image=<?php echo $imageTwo; ?>&v=<?php echo $view; ?>"><img src="../<?php echo $imageTwoThumb; ?>" id="nextimg2"/></a>
				</div>
                
				<div class="nextimg">	
					<a id="nextimg3id" href="fullsize.php?image=<?php echo $imageThree; ?>&v=<?php echo $view; ?>"><img src="../<?php echo $imageThreeThumb; ?>"id="nextimg3"/></a>
				</div>
                
				<a href="javascript:ajaxNextPics()"><div class="grid_1" id="hover_arrow_left"></div></a>
                
                <a href="javascript:ajaxPrevPics()"><div id="hover_arrow_right" class="grid_1" id="hover_arrow_right"></div></a>
                
				</div>
                        
		<div class="bloc_5 aboutFS" id="aboutFS">
			<header> About </header>
			<ul>
				<li> <img src="graphics/view.png"/>  Views: <span style="margin-left:38px;"> 7,234 </span> </li>
				<li> <img src="graphics/camera.png"/> Camera: <span style="margin-left:28px;"> Nikon D5100 </span> </li>
				<li> <img src="graphics/aperature.png"/> Aperture: <span style="margin-left:24px;"> f/5.6 </span> </li>
				<li> <img src="graphics/focal-length.png"/> Focal Length:  <span style="margin-left:3px;"> 48mm </span> </li>
				<li> <img src="graphics/lens.png"/> Lens: <span style="margin-left:42px;"> Nikor VR-18-55mm </span> </li>
				<li> <img src="graphics/shutter-speed.png"/> Shutter: <span style="margin-left:30px;"> 1/40sec </span> </li>
				<li> <img src="graphics/time.png"/> Uploaded: <span style="margin-left:18px;"> 5-12-13 </span> </li>	
				<li> <img src="graphics/copyright.png"/> Copyright: <span style="margin-left:18px;"> Noah Willard </span> </li>	
			</ul>
		</div>
		</div>
		</div>
	</div>