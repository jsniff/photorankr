<?php
 require "db_connection.php";

//$sImagePath = $_GET["file"];
//echo "owk";
//echo "nope";
   $imageid = 88;
  // echo "yes";
  $imagequery = "SELECT * FROM photos WHERE id = '$imageid'";
 // echo "no";
 $imagequeryrun= mysql_query($imagequery);
$image = mysql_result($imagequeryrun,0,'source');
 //   $imagebig = str_replace("userphotos/", "$_SERVER[DOCUMENT_ROOT]/userphotos/", $image);
//echo $imagequeryrun;
//echo $image;
//echo $image;
list($width, $height)=getimagesize($image);
// echo "working";

 $iThumbnailWidth = $width;
 $iThumbnailHeight = $height;
 $iMaxWidth = 2500;
 $iMaxHeight = 2500;
 
//if ($iMaxWidth && $iMaxHeight) $sType = 'scale';
//else if ($iThumbnailWidth && $iThumbnailHeight) $sType = 'exact';
 

$sType = 'scale';

//$img = $image;
//echo "working";
//$imageoriginal = str_replace("userphotos/", "$_SERVER[DOCUMENT_ROOT]/userphotos/bigphotos/", $image);
//echo '<img style="border: 1px solid black;margin-left:10px;margin-top:5px;" src="',$img,'" 
//height="100px" width="100px" />';
//$sExtension = strtolower(end(explode('.', $sImagePath)));
//if ($sExtension == 'jpg' || $sExtension == 'jpeg') {
 
    $img = @imagecreatefromjpeg($image)
      or die("Cannot create new JPEG image");
 
//} else if ($sExtension == 'png') {
 
  //  $img = @imagecreatefrompng($sImagePath)
    //    or die("Cannot create new PNG image");
 
//} else if ($sExtension == 'gif') {
 
  //  $img = @imagecreatefromgif($sImagePath)
    //    or die("Cannot create new GIF image");
 
//}
         //   echo "working";

 if ($img) {
         //echo "workingplease";


     $iOrigWidth = imagesx($img);
     $iOrigHeight = imagesy($img);
//                echo "workingplease";

     //echo "height";

//echo "workingagain"; 
    if ($sType == 'scale') {
      //      echo "working";

        // Get scale ratio
 
     $fScale = min($iMaxWidth/$iOrigWidth,
               $iMaxHeight/$iOrigHeight);
 
        if ($fScale < 1) {
 
             $iNewWidth = floor($fScale*$iOrigWidth);
             $iNewHeight = floor($fScale*$iOrigHeight);
 
             $tmpimg = imagecreatetruecolor($iNewWidth,$iNewHeight);
 
            imagecopyresampled($tmpimg, $img, 0, 0, 0, 0,
            $iNewWidth, $iNewHeight, $iOrigWidth, $iOrigHeight);
 
             imagedestroy($img);
             $img = $tmpimg;
         }     
 }

 
//     } else if ($sType == "exact") {
//             echo "working";

//         $fScale = max($iThumbnailWidth/$iOrigWidth,
//               $iThumbnailHeight/$iOrigHeight);

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
        }    
 
    }

// echo "working";

  //    $name = trial;
header("Content-type: image/jpeg");
   imagejpeg($img);
         //åå imagejpeg($img);
        //readfile($img);

   $photoid=10;
   $caption = 25;
   $img = 10;
   $heightnew=25;
   $widthnew=30;


            // echo'
            //  <div class="fPic" id="',$photoid,'" style="width:245px;height:245px;overflow:hidden;float:left;margin-left:10px;margin-top:30px;">
            // <div class="statoverlay" style="z-index:1;left:0px;top:180px;position:relative;background-color:black;width:245px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:16px;font-weight:100;">',$caption,'</span><br>
            // <form name="download_form" method="post" action="downloadphoto.php">
            //     <input type="hidden" name="image" value="',$img,'">
            //     <button type="submit" name="submit" value="download" class="btn btn-warning" style="margin-top:-45px;opacity:1;margin-left:12px;width:220px;height:35px;font-size:18px;">Download Photo</button>
            // </form>
            // </div>
            //     <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:265px;min-width:245px;" src="',$img,'" height="',$heightnew,'px" width="',$widthnew,'px" /></a></div>';




 
// }
 
?>