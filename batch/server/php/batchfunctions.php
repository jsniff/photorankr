<?php
ini_set('max_input_time', 300);   

function createThumbnail($filename) {  
		ini_set('max_input_time', 300);
      
        require 'newconfig.php';  
        
        $filename=str_replace("JPG","jpg",$filename);

        if(preg_match('/[.](jpg)$/', $filename)) {  
            $im = imagecreatefromjpeg($path_to_image_directory . $filename);  
        } else if (preg_match('/[.](gif)$/', $filename)) {  
            $im = imagecreatefromgif($path_to_image_directory . $filename);  
        } 
        else if (preg_match('/[.](jpeg)$/', $filename)) {  
            $im = imagecreatefromjpeg($path_to_image_directory . $filename);  
        }
        else if (preg_match('/[.](png)$/', $filename)) {  
            $im = imagecreatefrompng($path_to_image_directory . $filename);  
        } 
      
      
        $ox = imagesx($im);  
        $oy = imagesy($im);  
      
        $nx = $final_width_of_image;  
        $ny = $final_height_of_image;  
      
        $nm = imagecreatetruecolor($nx, $ny);  
      
        imagecopyresampled($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy);  
      
        if(!file_exists($path_to_thumbs_directory)) {  
          if(!mkdir($path_to_thumbs_directory)) {  
               die("There was a problem. Please try again!");  
          }  
           }  
      
        imagejpeg($nm, $path_to_thumbs_directory . $filename);  
        //$tn = '<img src="' . $path_to_thumbs_directory . $filename . '" alt="image" />';  
        //$tn .= '<br />Upload Successful!';  
        //echo $tn;  
chmod ($path_to_thumbs_directory . $filename, 0755);
}  

function watermarkpic($filename) {
	
	ini_set('max_input_time', 300);

	require 'newconfig.php';  

	$watermark = imagecreatefrompng('watermarknew.png');
	$watermarkwidth = imagesx($watermark);
	$watermarkheight = imagesy($watermark);

        $filename=str_replace("JPG","jpg",$filename);

	if(preg_match('/[.](jpg)$/', $filename)) {  
            $originalimage = imagecreatefromjpeg($path_to_image_directory . $filename);  
        } else if (preg_match('/[.](gif)$/', $filename)) {  
            $originalimage = imagecreatefromgif($path_to_image_directory . $filename);  
        }
        else if (preg_match('/[.](jpeg)$/', $filename)) {  
            $originalimage = imagecreatefromjpeg($path_to_image_directory . $filename);  
        }
         else if (preg_match('/[.](png)$/', $filename)) {  
            $originalimage = imagecreatefrompng($path_to_image_directory . $filename);  
        }  

	$originalwidth = imagesx($originalimage);
	$originalheight = imagesy($originalimage);
	
	$maxsize = 1200;
	$imgratio = $originalwidth / $originalheight;

	if($imgratio > 1) {
		$finalwidth = $maxsize;
		$finalheight = $maxsize / $imgratio;
	}
	else {
		$finalheight = $maxsize;
		$finalwidth = $maxsize * $imgratio;
	}	

	$finalimage = imagecreatetruecolor($finalwidth,$finalheight);
	
	imagecopyresampled($finalimage, $originalimage, 0,0,0,0,$finalwidth,$finalheight,$originalwidth,$originalheight);

	imagecopy($finalimage, $watermark, 0, 0, 0, 0, $watermarkwidth, $watermarkheight);

	//now move the file where it needs to go
	if(!file_exists($path_to_medimage_directory)) {  
        	if(!mkdir($path_to_medimage_directory)) {  
               		die("There was a problem. Please try again!");  
          	}  
         } 
	
	 imagejpeg($finalimage, $path_to_medimage_directory . $filename); 	
	
	chmod ($path_to_medimage_directory . $filename, 0644);
}


//Create Medium Thumbnail
function createMedThumbnail($filename) {  
ini_set('max_input_time', 300);
      
        require 'newconfig.php';  
        
        $filename=str_replace("JPG","jpg",$filename);

        if(preg_match('/[.](jpg)$/', $filename)) {  
            $im = imagecreatefromjpeg($path_to_image_directory . $filename);  
        } else if (preg_match('/[.](gif)$/', $filename)) {  
            $im = imagecreatefromgif($path_to_image_directory . $filename);  
        }
        else if (preg_match('/[.](jpeg)$/', $filename)) {  
            $im = imagecreatefromjpeg($path_to_image_directory . $filename);  
        }
         else if (preg_match('/[.](png)$/', $filename)) {  
            $im = imagecreatefrompng($path_to_image_directory . $filename);  
        } 
      
      
        $ox = imagesx($im);  
        $oy = imagesy($im);  
      
        $nx = $final_width_of_medimage;  
        $ny = $final_height_of_medimage;  
      
        $nm = imagecreatetruecolor($nx, $ny);  
      
        imagecopyresampled($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy);  
      
        if(!file_exists($path_to_medthumbs_directory)) {  
          if(!mkdir($path_to_medthumbs_directory)) {  
               die("There was a problem. Please try again!");  
          }  
           }  
      
        imagejpeg($nm, $path_to_medthumbs_directory . $filename);  
        //$tn = '<img src="' . $path_to_medthumbs_directory . $filename . '" alt="image" />';  
        //$tn .= '<br />Upload Successful!';  
        //echo $tn;  
chmod ($path_to_medthumbs_directory . $filename, 0644);
}  

?>