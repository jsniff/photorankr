<?php

ini_set('max_input_time', 3200);

//connect to the database
require "db_connection.php";
require 'configcampaigns.php';
require 'functionscampaigns3.php';

//start the session
//session_start();
$email = $_SESSION['email'];

//if they have one of the required file types
if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/jpg")
|| ($_FILES["file"]["type"] == "image/JPG")
|| ($_FILES["file"]["type"] == "image/pjpeg")
|| ($_FILES["file"]["type"] == "image/png"))&& ($_FILES["file"]["size"] < 45000000) 
&& $_SESSION['loggedin']==1)
{
	if ($_FILES["file"]["error"] > 0) {
    		die("There was an error uploading the photo. Please try again");
    }
  	else {
       	//CHECK TO MAKE SURE INPUT IS ALL ENTERED
		$caption = mysql_real_escape_string(htmlentities($_POST['caption']));
		$price = mysql_real_escape_string(htmlentities($_POST['price']));
		$campaignID = mysql_real_escape_string(htmlentities($_GET['campaign']));

		if (!$caption | !$price | $price <0) {
			mysql_close();
         	header("location:uploadcampaignphoto.php?action=uploadfailure");
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
         	header("location:uploadcampaignphoto.php?action=uploadfailure");
       		exit();
   		}
    	else {
      		move_uploaded_file($source, $target);
			chmod ($target, 0644);

      		createThumbnail($newfilename); 
      		createMedThumbnail($newfilename); 
      		watermarkpic($newfilename);  
      	}

		$target = $path_to_medimage_directory . $newfilename;
		//insert the file information into the database
		$insertquery="INSERT INTO campaignphotos (source, caption, price, emailaddress, campaign) VALUES ('$target', '$caption', '$price', '$email', '$campaignID')";
		mysql_query($insertquery);

		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=uploadcampaignphoto.php?action=uploadsuccess">';
		exit();
    }
}
else {
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=uploadcampaignphoto.php?action=uploadfailure">';
	exit();
}

?>