<?php

//give this file some extra time to upload the photo
ini_set('max_input_time', 3200);

//connect to the database
require "db_connection.php";
require 'configcampaigns.php';
require 'functionscampaigns.php';

//start the session
session_start();
$email = $_SESSION['email'];

//if they have one of the required file types
if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/jpg")
|| ($_FILES["file"]["type"] == "image/JPG")
|| ($_FILES["file"]["type"] == "image/pjpeg")
|| ($_FILES["file"]["type"] == "image/png")) && ($_FILES["file"]["size"] < 45000000) 
&& $_SESSION['loggedin'] == 1)
{
	if ($_FILES["file"]["error"] > 0) {
        die("There was an error uploading the photo. Please try again");
    }
  	else {
        //CHECK TO MAKE SURE INPUT IS ALL ENTERED
		$caption = mysql_real_escape_string(htmlentities($_POST['caption']));
        $terms = mysql_real_escape_string(htmlentities($_POST['terms']));
		$campaignID = mysql_real_escape_string(htmlentities($_GET['campaign']));

		if (!$caption || !$campaignID) {
			mysql_close();
         	header("location:campaignphotos.php?id=$campaignID&view=upload&action=uploadfailure");
        	exit();
		}		

    	$filename = $_FILES['file']['name'];  
    	$newfilename=str_replace(" ","",$filename);
   		$newfilename=str_replace("#","",$newfilename);
   		$newfilename=str_replace("&","",$newfilename);
   		$newfilename=str_replace("'","",$newfilename);
   		$newfilename=strtolower($newfilename);
    	$newfilename=str_replace("?","",$newfilename);	
    	$newfilename=str_replace("'","",$newfilename);
   		$newfilename=str_replace("#","",$newfilename);
   		$newfilename=str_replace(":","",$newfilename);
   		$newfilename=str_replace("*","",$newfilename);
   		$newfilename=str_replace("<","",$newfilename);
      	$newfilename=str_replace(">","",$newfilename);
    	$newfilename=str_replace("(","",$newfilename);
   		$newfilename=str_replace(")","",$newfilename);
   		$newfilename=str_replace("^","",$newfilename);
   		$newfilename=str_replace("%","",$newfilename);
   		$newfilename=str_replace("$","",$newfilename);
    	$newfilename=str_replace("@","",$newfilename);
    	$newfilename=str_replace("!","",$newfilename);
    	$newfilename=str_replace("=","",$newfilename);
    	$newfilename=str_replace("|","",$newfilename);
    	$newfilename=str_replace(";","",$newfilename);
    	$newfilename=str_replace("[","",$newfilename);
    	$newfilename=str_replace("{","",$newfilename);
    	$newfilename=str_replace("}","",$newfilename);
    	$newfilename=str_replace("]","",$newfilename);
    	$newfilename=str_replace("~","",$newfilename);
   		$newfilename=str_replace("`","",$newfilename);
   		$newfilename=str_replace("_","",$newfilename);
   		$newfilename=str_replace("©","",$newfilename);
		$currenttime = time();
		$newfilename = $currenttime . $newfilename;
   		$source = $_FILES['file']['tmp_name'];  
   		$target = $path_to_image_directory . $newfilename;  

    	if (file_exists("upload/" . $_FILES["file"]["name"])) {
            mysql_close();
         	header("location:campaignphotos.php?id=$campaignID&view=upload&action=uploadfailure");
       		exit();
   		}
    	else {
      		move_uploaded_file($source, $target);
			    chmod ($target, 0644);
      		createMedThumbnail($newfilename); 
      		watermarkpic($newfilename);  
      	}

		$target = $database_path_directory . $newfilename;
		//insert the file information into the database
		$insertquery="INSERT INTO campaignphotos (emailaddress, source, campaign, caption) VALUES ('$email', '$target', '$campaignID', '$caption')";
		mysql_query($insertquery);

        mysql_close();
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=campaignphotos.php?id=', $campaignID,'&view=upload&action=uploadsuccess">';
		exit();
    }
}
else {
    mysql_close();
    $campaignID = mysql_real_escape_string(htmlentities($_GET['campaign']));
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=campaignphotos.php?id=', $campaignID, '&view=upload&action=uploadfailure">';
	exit();
}

?>