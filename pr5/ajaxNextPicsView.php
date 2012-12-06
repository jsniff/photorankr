<?php

//connect to the database
require "db_connection.php";
require "functions.php";

$currentid = mysql_real_escape_string(htmlentities($_GET['image']));
$useremail = mysql_real_escape_string(htmlentities($_GET['email']));

//GET PHOTO SOURCES AND ID'S
$newid = $currentid;
$getnextimages = mysql_query("SELECT id,source FROM photos WHERE id > $newid AND emailaddress = '$useremail' ORDER BY id ASC LIMIT 0,3");
$nextimg1 = mysql_result($getnextimages,0,'source');
$nextimg1 = str_replace('userphotos/','userphotos/thumbs/',$nextimg1);
$nextimg1id = mysql_result($getnextimages,0,'id');

$nextimg2 = mysql_result($getnextimages,1,'source');
$nextimg2 = str_replace('userphotos/','userphotos/thumbs/',$nextimg2);
$nextimg2id = mysql_result($getnextimages,1,'id');

$nextimg3 = mysql_result($getnextimages,2,'source');
$nextimg3 = str_replace('userphotos/','userphotos/thumbs/',$nextimg3);
$nextimg3id = mysql_result($getnextimages,2,'id');

//TRANSMIT DATA VIA JSON
$jsondata = json_encode(array("nextimg1" => $nextimg1, "nextimg1id" => $nextimg1id, "nextimg2" => $nextimg2, "nextimg2id" => $nextimg2id, "nextimg3" => $nextimg3, "nextimg3id" => $nextimg3id));
    
echo $jsondata;

?>