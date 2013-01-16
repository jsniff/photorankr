<?php

require "db_connection.php";
require "functions.php";




    $imageid = '350';
    $imagequery = mysql_query("SELECT source FROM photos WHERE id = '$imageid'");
    $image = mysql_result($imagequery,0,'source');
     $width = "300";
      $height = "300";

echo $imagequery;
//echo $image;


//$image = $_POST['image'];
//$width = $_POST['width'];
//$height = $_POST['height'];
//$image = str_replace("userphotos/","userphotos/bigphotos/", $image);
//$path = $image;

//RESIZE IMAGE

    $iThumbnailWidth = $width;
    $iThumbnailHeight = $height;
    $iMaxWidth = $width;
    $iMaxHeight = $height;

if ($iMaxWidth && $iMaxHeight) $sType = 'scale';
else if ($iThumbnailWidth && $iThumbnailHeight) $sType = 'exact';

            $img = imagecreatefromjpeg($image);  
            echo $img;
            echo "yep";


        if(preg_match('/[.](jpg)$/', $image)) {  
            $img = imagecreatefromjpeg($image);  
        } 
        
        else if (preg_match('/[.](gif)$/', $image)) {  
            $img = imagecreatefromgif($image);  
        } 
        
        else if (preg_match('/[.](jpeg)$/', $image)) {  
            $img = imagecreatefromjpeg($image);  
        }
        
        else if (preg_match('/[.](png)$/', $image)) {  
            $img = imagecreatefrompng($image);  
        } 
        

                                
        if($image) {
          // echo "working";
            $iOrigWidth = imagesx($img);
            $iOrigHeight = imagesy($img);

        if($sType == 'scale') {
          //  echo "working";
            $fScale = min($iMaxWidth/$iOrigWidth,$iMaxHeight/$iOrigHeight);
            echo $fScale;
 
        if($fScale < 1) {
 
            $iNewWidth = floor($fScale*$iOrigWidth);
            $iNewHeight = floor($fScale*$iOrigHeight);
 
            $tmpimg = imagecreatetruecolor($iNewWidth,$iNewHeight);
 
            imagecopyresampled($tmpimg, $img, 0, 0, 0, 0,
            $iNewWidth, $iNewHeight, $iOrigWidth, $iOrigHeight);
 
            imagedestroy($img);
            $img = $tmpimg;
        echo $img;
        echo "check";
                echo "working";

            
            header('Content-Type: image/jpeg');
            header('Content-Disposition: attachment; filename="photograph.jpg"');

            imagejpeg($img, NULL, 75);
        
        }     
 
    }else if ($sType == "exact") {
                echo "working";

 
        $fScale = max($iThumbnailWidth/$iOrigWidth,
              $iThumbnailHeight/$iOrigHeight);
 
        if ($fScale < 1) {
 
            $iNewWidth = floor($fScale*$iOrigWidth);
            $iNewHeight = floor($fScale*$iOrigHeight);
 
            $tmpimg = imagecreatetruecolor($iNewWidth,
                            $iNewHeight);
            $tmp2img = imagecreatetruecolor($iThumbnailWidth,
                            $iThumbnailHeight);
 
            imagecopyresampled($tmpimg, $img, 0, 0, 0, 0,
            $iNewWidth, $iNewHeight, $iOrigWidth, $iOrigHeight);
 
            if ($iNewWidth == $iThumbnailWidth) {
 
                $yAxis = ($iNewHeight/2)-
                    ($iThumbnailHeight/2);
                $xAxis = 0;
 
            } else if ($iNewHeight == $iThumbnailHeight)  {
 
                $yAxis = 0;
                $xAxis = ($iNewWidth/2)-
                    ($iThumbnailWidth/2);
 
            } 
 
            imagecopyresampled($tmp2img, $tmpimg, 0, 0,
                       $xAxis, $yAxis,
                       $iThumbnailWidth,
                       $iThumbnailHeight,
                       $iThumbnailWidth,
                       $iThumbnailHeight);
 
            imagedestroy($img);
            imagedestroy($tmpimg);
            $img = $tmp2img;
          //  echo $img;
        }    
 
    }


}
    

?>