<?php

require "db_connection.php"; 

for($iii=0; $iii < 20; $iii++){
$imagequery = mysql_query("SELECT source FROM photos ORDER BY id DESC LIMIT 16");
$imageSrc = mysql_result($imagequery,$iii,'source');

//$thumb_image_file = mysql_result($imagequery,$iii,'source');

        $thumb_image_file=$_SERVER['DOCUMENT_ROOT'].'/thumbs/abc.jpg';
 $width = "300";
        //$thumb_image_file=$_SERVER['DOCUMENT_ROOT'].'/thumbs/abc.jpg';
        if (is_numeric($width) && isset($imageSrc)){
        header('Content-type: image/jpeg');
        makeThumb($imageSrc, $width);
        $img_content=file_get_contents($thumb_image_file);
        //echo "working";
        //echo $thumb_image_file;
      echo $img_content;
   }
 }

     function makeThumb($src,$newWidth,$thumb_image_file) {


               $srcImage = imagecreatefromjpeg($src);
             $width = imagesx($srcImage);
            $height = imagesy($srcImage);

        $newHeight = floor($height*($newWidth/$width));

        $newImage = imagecreatetruecolor($newWidth,$newHeight);

              imagecopyresized($newImage,$srcImage,0,0,0,0,$newWidth,$newHeight,$width,$height);


                 imagejpeg($newImage,$thumb_image_file);
   }


?>
