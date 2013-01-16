<?php

require "db_connection.php";

$imagequery = mysql_query("SELECT source FROM photos ORDER BY id DESC LIMIT 16");

$imageSrc = mysql_result($imagequery,$iii,'source');

$width = "300";

if (is_numeric($width) && isset($imageSrc)){
header('Content-type: image/jpeg');
makeThumb($imageSrc, $width);
}

function makeThumb($src,$newWidth) {

$srcImage = imagecreatefromjpeg($src);
$width = imagesx($srcImage);
$height = imagesy($srcImage);

$newHeight = floor($height*($newWidth/$width));

$newImage = imagecreatetruecolor($newWidth,$newHeight);

imagecopyresized($newImage,$srcImage,0,0,0,0,$newWidth,$newHeight,$width,$height);

imagejpeg($newImage,$imagea);

//$cover = imagecreatefromstring($newImage);
   //echo'<img src="',$imagea,'" width="200" />';


}

?>


