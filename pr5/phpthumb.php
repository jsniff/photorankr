<?php
    require "db_connection.php"; 

    $iii = 1;

$imagequery = mysql_query("SELECT source FROM photos ORDER BY id DESC LIMIT 16");
$imageSrc = mysql_result($imagequery,$iii,'source');


    require_once('phpthumb/phpthumb.class.php');

     $phpThumb = new phpThumb();

    $capture_raw_data = false;  $phpThumb->resetObject();
    $phpThumb->setSourceFilename($imageSrc); // your source image file
    $output_filename = $tpath; // output file path
    $phpThumb->setParameter('w', 100); // thumbnail width
    $phpThumb->setParameter('q', 100); // thumbnail quality
    $phpThumb->setParameter('config_output_format', 'jpeg'); // preferred thumbnail format
           //  echo "working";

 
     if ($phpThumb->GenerateThumbnail()){
echo "working";
        if($phpThumb->RenderToFile($output_filename)){
                echo "working";

            // success
        } else {
            $msg = "Error during resizing \n" . $phpThumb->fatalerror . '  ' . $phpThumb->debugmessages;
        }
    } else {
        $msg = "Error with file\n" . $phpThumb->fatalerror . '  ' . $phpThumb->debugmessages;
     }
?>