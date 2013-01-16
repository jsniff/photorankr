<?php

require "db_connection.php"; 


$num_columns = 4; // the number of thumbnails per row
$thumb_width = 400;
$thumb_height = 300;


// the actual number of results


// create a large empty image that will fit all thumbnails


// fetch the images one by one

$x = 0;
$y = 0;


$imagequery = mysql_query("SELECT source FROM photos ORDER BY id DESC LIMIT 16");

$num_photos = mysql_num_rows($imagequery);
$num_rows = ceil($num_photos / $num_columns);

$gallery_width = $num_columns * $thumb_width;
$gallery_height = $num_rows * $thumb_height;

$gallery = imagecreatetruecolor($gallery_width, $gallery_height);



for($iii=0; $iii < 16; $iii++){
$imagetrial = mysql_result($imagequery,$iii,'source');
$image = imagecreatefromstring($imagetrial);
echo $image;

  // the variable is now a valid image resource
  if ($image !== FALSE) {
    //echo "working";
    // grab the size of the image
    $image_width = imagesx($image);
    $image_height = imagesy($image);

    // draw and resize the image to the next position in the gallery
    imagecopyresized($gallery, $image, $x, $y, 0, 0, $thumb_width, $thumb_height, $image_width, $image_height);

    // move the next drawing position to the right
    $x += $thumb_width;

    // if it has reached the far-right then move down a row and reset the x position
    if ($x >= $gallery_width) {     
        $y += $thumb_height;
        $x = 0;
    }

    // destroy the resource to free the memory
    imagedestroy($image);
  }
}
mysql_free_result($imagequery);

// send the gallery image to the browser in JPEG
header('Content-Type: image/jpeg');
imagejpeg($gallery);
?>
