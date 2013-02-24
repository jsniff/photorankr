<?php

//connect to the database
require "db_connection.php";
require "functions.php";

$currentid = mysql_real_escape_string(htmlentities($_GET['image']));
$set_id = mysql_real_escape_string(htmlentities($_GET['set_id']));
$email = mysql_real_escape_string(htmlentities($_GET['email']));

//GET PHOTO SOURCES AND ID'S
$newid = $currentid;
$getprevimages = mysql_query("SELECT id,source,caption,price,about FROM photos WHERE emailaddress = '$email' AND set_id = $set_id AND id < $newid ORDER BY id DESC LIMIT 0,1");

//If first image, take them to last image
if(mysql_num_rows($getprevimages) == 0) {
    $getprevimages = mysql_query("SELECT id,source,caption,price,about FROM photos WHERE emailaddress = '$email' AND set_id = $set_id ORDER BY id DESC LIMIT 0,1");
}

$nextimg = mysql_result($getprevimages,0,'source');
$nextimgid = mysql_result($getprevimages,0,'id');
$previmgid = $newid;
$caption = mysql_result($getprevimages,0,'caption');
$price = mysql_result($getprevimages,0,'price');
if($price < 0) {
    $price = "Not For Sale";
}
else {
    $price = "$" . $price;
}
$about = mysql_result($getprevimages,0,'about');


//TRANSMIT DATA VIA JSON
$jsondata = json_encode(array("nextimg" => $nextimg, "nextimgid" => $nextimgid, "previmgid" => $previmgid, "caption" => $caption, "price" => $price, "about" => $about, "exhibitid" => $set_id));
echo $jsondata;

?>