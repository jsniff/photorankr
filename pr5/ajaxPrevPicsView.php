<?php

//connect to the database
require "db_connection.php";
require "functions.php";

$currentid = mysql_real_escape_string(htmlentities($_GET['image']));
$useremail = mysql_real_escape_string(htmlentities($_GET['email']));

//GET PHOTO SOURCES AND ID'S
$newid = $currentid;
$getprevimages = mysql_query("SELECT id,source FROM photos WHERE id < $newid AND emailaddress = '$useremail' ORDER BY id DESC LIMIT 0,3");
$previmg1 = mysql_result($getprevimages,0,'source');
$previmg1 = str_replace('userphotos/','userphotos/thumbs/',$previmg1);
$previmg1id = mysql_result($getprevimages,0,'id');

$previmg2 = mysql_result($getprevimages,1,'source');
$previmg2 = str_replace('userphotos/','userphotos/thumbs/',$previmg2);
$previmg2id = mysql_result($getprevimages,1,'id');

$previmg3 = mysql_result($getprevimages,2,'source');
$previmg3 = str_replace('userphotos/','userphotos/thumbs/',$previmg3);
$previmg3id = mysql_result($getprevimages,2,'id');

//TRANSMIT DATA VIA JSON
$jsondata = json_encode(array("previmg1" => $previmg1, "previmg1id" => $previmg1id, "previmg2" => $previmg2, "previmg2id" => $previmg2id, "previmg3" => $previmg3, "previmg3id" => $previmg3id));
    
echo $jsondata;

?>