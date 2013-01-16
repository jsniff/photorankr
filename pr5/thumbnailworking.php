<?php

require "db_connection.php";
require "functions.php";




    $imageid = '350';
    $imagequery = mysql_query("SELECT source FROM photos WHERE id = '$imageid'");
    $imageSrc = mysql_result($imagequery,0,'source');


 //   echo "working";
    //echo $imagequery;
   // echo $imageSrc;
    //echo '<img src= "$imageSrc”>';

//$imageSrc = (string)['image'];
//$width =['width'];

 $width = "300";

// if (is_numeric($width) && isset($imageSrc)){
// header('Content-type: image/jpeg');
// //echo $width;
// makeThumb($imageSrc, $width);
// }

//function makeThumb($src,$newWidth) {

$srcImage = imagecreatefromjpeg($imageSrc);
$width = imagesx($srcImage);
$height = imagesy($srcImage);

$newHeight = floor($height*($newWidth/$width));

$newImage = imagecreatetruecolor($newWidth,$newHeight);

imagecopyresized($newImage,$srcImage,0,0,0,0,$newWidth,$newHeight,$width,$height);

//header('Content-type: image/jpeg');
$a = imagejpeg($newImage);
header('Content-type: image/jpeg');

echo $a;
echo "working";
//echo $image;
//echo "workingplease";



//}
?>



