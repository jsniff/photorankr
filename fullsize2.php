<?php

//connect to the database
require "db_connection.php";
require "functionscampaigns3.php"; 
    // if login form has been submitted
    if (htmlentities($_GET['action']) == "login") { 
        login();
    }
    else if(htmlentities($_GET['action']) == "logout") { 
        logout();
    }

//start the session
session_start();

$repemail = $_SESSION['repemail'];
    
     //GET INFO FROM CURRENT PHOTO ID
    $imageid = htmlentities($_GET['imageid']);
    
    //add to the views column
    $updatequery = mysql_query("UPDATE photos SET views=views+1 WHERE   id='$imageid'") or die(mysql_error());
    
    //add to the usermarketviews column
    $buyerupdatequery = mysql_query("UPDATE photos SET buyermarketviews=buyermarketviews+1 WHERE id='$imageid'") or die(mysql_error());

    $imagequery = "SELECT * FROM photos WHERE id = '$imageid'";
    $imagequeryrun= mysql_query($imagequery);
    $image = mysql_result($imagequeryrun,0,'source');
    $owner = mysql_result($imagequeryrun,0,'emailaddress');
    $price = mysql_result($imagequeryrun,0,'price');
    $points = mysql_result($imagequeryrun,0,'points');
    $caption = mysql_result($imagequeryrun,0,'caption');
    $votes = mysql_result($imagequeryrun,0,'votes');
    $ranking = ($points/$votes);
    $ranking = number_format($ranking,2);
    $location = mysql_result($imagequeryrun,0,'location');
    $camera = mysql_result($imagequeryrun,0,'camera');
    $exhibit = mysql_result($imagequeryrun,0,'set_id');
    $about = mysql_result($imagequeryrun,0,'about');
    $tag1 = mysql_result($imagequeryrun,0,'tag1');
    if($tag1) {$tag1 = $tag1 . ", ";}
    $tag2 = mysql_result($imagequeryrun,0,'tag2');
    if($tag2) {$tag2 = $tag2 . ", ";}
    $tag3 = mysql_result($imagequeryrun,0,'tag3');
    if($tag3) {$tag3 = $tag3 . ", ";}
    $tag4 = mysql_result($imagequeryrun,0,'tag4');
    if($tag4) {$tag4 = $tag4 . ", ";}
    $singlestyletags = mysql_result($imagequeryrun,0,'singlestyletags');
    $singlecategorytags = mysql_result($imagequeryrun,0,'singlecategorytags');
    $singlestyletagsarray = explode("  ", $singlestyletags);
    $singlecategorytagsarray   = explode("  ", $singlecategorytags);
    for($iii=0; $iii < count($singlestyletagsarray); $iii++) {
        if($singlestyletagsarray[$iii] != '') {
        $singlestyletagsfinal = $singlestyletagsfinal . $singlestyletagsarray[$iii] . ", "; }
    }
    for($iii=0; $iii < count($singlecategorytagsarray); $iii++) {
        if($singlecategorytagsarray[$iii] != '') {
        $singlecategorytagsfinal = $singlecategorytagsfinal . $singlecategorytagsarray[$iii] . ", "; }
    }
    
    $keywords = $tag1 . $tag2 . $tag3 . $tag4 . $singlestyletagsfinal . $singlecategorytagsfinal;
    $keywords = substr_replace($keywords ," ",-2);
    
    $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$owner'");
    $profilepic = mysql_result($ownerquery,0,'profilepic');
    $profilepic = 'http://photorankr.com/' . $profilepic;
    $firstname = mysql_result($ownerquery,0,'firstname');
    $lastname = mysql_result($ownerquery,0,'lastname');
    $userid = mysql_result($ownerquery,0,'user_id');
    $fullname = $firstname . " " . $lastname;
    $imagebig = str_replace("userphotos/", "$_SERVER[DOCUMENT_ROOT]/userphotos/", $image);
    $imageoriginal = str_replace("userphotos/", "$_SERVER[DOCUMENT_ROOT]/userphotos/bigphotos/", $image);
    $imagebig2 = str_replace("$_SERVER[DOCUMENT_ROOT]/userphotos/", "http://photorankr.com/userphotos/", $imagebig); 
    $title = mysql_result($imagequeryrun,0,'caption');

//calculate the size of the picture

$maxwidth=550;
$maxheight=550;

list($originalwidth, $originalheight)=getimagesize($imageoriginal);

list($width, $height)=getimagesize($imagebig);
$imgratio=$width/$height;

if($imgratio > 1) {
    $newwidth=$maxwidth;
    $newheight=$maxwidth/$imgratio;
}
else {
    $newheight=$maxheight;
    $newwidth=$maxheight*$imgratio;
}
    
    if(!$_GET['imageid'] || $_GET['imageid'] == "") {
	    mysql_close();
        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=trending.php">';
		exit();			
    }
    
 