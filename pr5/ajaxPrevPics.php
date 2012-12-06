<?php

//connect to the database
require "db_connection.php";
require "functions.php";

$currentid = mysql_real_escape_string(htmlentities($_GET['image']));

//GET PHOTO SOURCES AND ID'S
$newid = $currentid;
$getprevimages = mysql_query("SELECT id,source FROM photos WHERE id = $newid LIMIT 0,1");
$previmg1 = mysql_result($getprevimages,0,'source');
$previmg1 = str_replace('userphotos/','userphotos/thumbs/',$previmg1);
$previmg1id = mysql_result($getprevimages,0,'id');

$newid2 = $currentid-1;
$getprevimages2 = mysql_query("SELECT id,source FROM photos WHERE id = $newid2 LIMIT 0,1");
$previmg2 = mysql_result($getprevimages2,0,'source');
$previmg2 = str_replace('userphotos/','userphotos/thumbs/',$previmg2);
$previmg2id = mysql_result($getprevimages2,0,'id');

$newid3 = $currentid-2;
$getprevimages3 = mysql_query("SELECT id,source FROM photos WHERE id = $newid3 LIMIT 0,1");
$previmg3 = mysql_result($getprevimages3,0,'source');
$previmg3 = str_replace('userphotos/','userphotos/thumbs/',$previmg3);
$previmg3id = mysql_result($getprevimages3,0,'id');

//TRANSMIT DATA VIA JSON
$jsondata = json_encode(array("previmg1" => $previmg1, "previmg1id" => $previmg1id, "previmg2" => $previmg2, "previmg2id" => $previmg2id, "previmg3" => $previmg3, "previmg3id" => $previmg3id));
    
echo $jsondata;

?>