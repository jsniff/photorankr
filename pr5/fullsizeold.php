<?php

//connect to the database
require "../db_connection.php";
require "functions.php";
require "timefunction.php";

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

?>


<!DOCTYPE HTML>
<head>
	<meta charset = "UTF-8">
	<title> Sell, share and discover brilliant photography </title>
	<link href = "css/main.css" rel="stylesheet" type="text/css"/>
	<link href = "css/grid.css" rel="stylesheet" type="text/css"/>
	<link href = "css/reset.css" rel="stylesheet" type="text/css"/>
	<link href = "css/normalize.css" rel="stylesheet" type="text/css"/>
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
<body id="body" >

<?php navbar(); ?>

<div class="container_custom"style="margin:50px 0 0 110px;;">
	<div class="bloc_10" style="float:left;">
			<hgroup class=" title">  
				<header> <?php echo $caption; ?> </header>	
			
			</hgroup>
			
			<div class="bloc_12">
				<img src="../<?php echo $image; ?>" style="max-width:795px;max-height:900px;float:left;border-radius:5px;box-shadow: 0 1px 2px #333;margin: 0 0 0 10px;"/>
			</div>
            
            <!--Comments Box-->
            <div class="bloc_12">
				 <?php
        
        //AJAX COMMENT
        if($_SESSION['loggedin'] == 1) {
            echo'
            <div style="width:630px;"> 
            <form action="#" method="post" style="margin-top:5px;padding-bottom:25px;">        
            <img style="float:left;padding:10px;" src="',$sessionpic,'" height="30" width="30" />
            <textarea id="comment" style="float:left;width:495px;position:relative;top:10px;height:20px;" type="text" placeholder="Leave feedback for ',$firstname,'&#8230;"></textarea>
            <br /><br />
            <input style="float:left;margin-left:8px;margin-top:-24px;" type="submit" type="submit" class="submit btn btn-success" value="Post"/>
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
            $commenteremail = mysql_result($grabcomments,$iii,'commenter');
            $commenterinfo = mysql_query("SELECT user_id,firstname,lastname,profilepic,reputation FROM userinfo WHERE emailaddress = '$commenteremail'");
            $commentername = mysql_result($commenterinfo,0,'firstname') ." ". mysql_result($commenterinfo,0,'lastname');
            $commenterid = mysql_result($commenterinfo,0,'user_id');
            $commenterpic = mysql_result($commenterinfo,0,'profilepic');
            $commenterrep = number_format(mysql_result($commenterinfo,0,'reputation'),2);
        
        
        echo'
            <div class="grid_16" style="width:610px;margin-top:20px;">
            <a href="viewprofile.php?u=',$commenterid,'"><div style="float:left;"><img class="roundedall" src="',$commenterpic,'" alt="',$commentername,'" height="40" width="35"/></a></div>
            <div style="float:left;padding-left:6px;width:560px;">
                <div style="float:left;color:#3e608c;font-size:14px;font-family:helvetica;font-weight:500;border-bottom: 1px solid #ccc;width:560px;"><div style="float:left;"><a name="',$commentid,'" href="viewprofile.php?u=',$commenterid,'">',$commentername,'</a> &nbsp;<span style="font-size:16px;font-weight:100;color:black;margin-top:2">|</span>&nbsp;<span style="color:#333;font-size:12px;">Rep: ',$commenterrep,'</span>
                </div>&nbsp;&nbsp;&nbsp;
                    <div class="progress progress-success" style="float:left;width:110px;height:7px;opacity:.8;margin:7px;">
                    <div class="bar" style="width:',$commenterrep,'%;">
                    </div>
                    
                    </div>';
                    
                 if($email == $emailaddress) {
                    echo'
                        <div style="float:right;font-size:12px;font-weight:500;"><a style="color#ccc;text-decoration:none;" href="fullsize.php?image=',$image,'&action=deletecomment&cid=',$commentid,'">X</a></div>';
                }
                
                if($commenterid == $sessionid) {
                    echo'
                        <div style="float:right;padding-right:10px;font-size:12px;font-weight:500;"><a style="color#ccc;text-decoration:none;" href="fullsize.php?image=',$image,'&action=editcomment&cid=',$commentid,'#',$commentid,'"> Edit Comment</a></div>';
                }

                echo'
                </div>
                
                <br />
                <div style="float:left;font-size:11px;color:#777;font-weight:400;padding:2px;">',converttime($commenttime),'</div>
                
                <div style="float:left;width:520px;padding:10px;font-size:13px;font-family:helvetica;font-weight:300;color:#555;">',$comment,'</div>
            </div>';
            
             if($_GET['action'] == 'editcomment' && $commentid == $_GET['cid']) {
                
                    echo'
                    <form action="fullsize.php?image=',$image,'#',$commentid,'" method="POST" />
                    <textarea style="height:55px;width:560px;margin-left:40px;" name="commentedit">',$comment,'</textarea>
                    <input type="hidden" name="commentid" value="',$commentid,'" />
                    <br />
                    <input type="submit" class="btn btn-primary" style="float:right;font-size:12px;" value="Save Edit" />
                    </form>';
                    
                }
            
            echo'
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
		<div class="bloc_5" style="float:right;margin-top:50px;margin-right:80px;">
		<div class="bloc_5 Info">
					<img src="../<?php echo $profilepic; ?>" />
					<div style=""> <?php echo $fullname; ?> </div>
					<p> Rep: <?php echo $reputation; ?> </p>
                    <div style="float:left;"><a data-toggle="modal" data-backdrop="static" href="#fwmodal"><button style="width:80px;margin-left:15px;" class="btn btn-primary"> Follow </button></a></div>
							
		</div>	
			<div class="bloc_5 Info" >
				<ul>
					<li>
						<img src="graphics/rank.png" />
						<p> 3.45/10 </p>
						<p> Rank </p>
					</li>
					<li>
						<img src="graphics/collections.png" />
						
						<p> 599 </p>
						<p> Collect </p>
					</li>
					<li>
						<img src="graphics/cart.png" />
						
						<p> $56 </p>
						<p> Purchase </p>
					</li>
					<li>
						<img src="graphics/favorite.png" />
						
						<p> 897 </p>
						<p> Favorite </p>
					</li>
					
				</ul>			
		</div>
		<div class="bloc_5 aboutFS" id="aboutFS" style="margin-top:110px !important;" >
			<header> About </header>
			<ul>
                <?php 
                if($views) {
				echo'<li><img src="graphics/view.png"/>  Views: <span style="margin-left:38px;">',$views,'</span></li>';
                }
                if($camera) {
				echo'<li><img src="graphics/camera.png"/> Camera: <span style="margin-left:28px;">',$camera,'</span></li>';
                }
                if($aperture) {
				echo'<li><img src="graphics/aperature.png"/> Aperture: <span style="margin-left:24px;">',$aperture,'</span></li>';
                }
                if($focallength) {
				echo'<li> <img src="graphics/focal-length.png"/> Focal Length:  <span style="margin-left:3px;">',$focallength,'</span> </li>';
                }
                if($lens) {
				echo'<li> <img src="graphics/lens.png"/> Lens: <span style="margin-left:42px;">',$lens,'</span> </li>';
                }
                if($shutterspeed) {
				echo'<li> <img src="graphics/shutter-speed.png"/> Shutter: <span style="margin-left:30px;">',$shutterspeed,'</span> </li>';
                }
                if($uploaded) {
				echo'<li> <img src="graphics/time.png"/> Uploaded: <span style="margin-left:18px;">',$uploaded,'</span> </li>';
                }
                ?>
			</ul>
		</div>
		</div>
		</div>
	</div>