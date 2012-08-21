<?php

//give this file some extra time to upload the photo
ini_set('max_input_time', 3200);

//CONNECT TO DB
require "db_connection.php";
require 'config.php';
require 'functions.php';

//start the session and grab the users information
@session_start();
$email = $_SESSION['email'];

//if they have one of the required file types
if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpg")
|| ($_FILES["file"]["type"] == "image/JPG")
|| ($_FILES["file"]["type"] == "image/pjpeg")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/png"))&& ($_FILES["file"]["size"] < 45000000) 
&& $_SESSION['loggedin']==1)
{
	if ($_FILES["file"]["error"] > 0)
    	{
    		die("There was an error uploading the photo. Please try again");
    	}
  	else
    	{
        	//CHECK TO MAKE SURE INPUT IS ALL ENTERED
            $name=mysql_real_escape_string($_POST['caption']);
        	$location=mysql_real_escape_string($_POST['location']);
        	$tag1=mysql_real_escape_string($_POST['tag1']);		
            $tag2=mysql_real_escape_string($_POST['tag2']);		
            $tag3=mysql_real_escape_string($_POST['tag3']);		
            $tag4=mysql_real_escape_string($_POST['tag4']);	
            $addtoset = mysql_real_escape_string($_POST['addtoset']);
        	$singlestyletags = $_POST['singlestyletags'];	
        	$singlecategorytags = $_POST['singlecategorytags'];
            $camera=mysql_real_escape_string($_POST['camera']);
        	$addtoset=mysql_real_escape_string($_POST['addtoset']);	
        	$focallength=mysql_real_escape_string($_POST['focallength']);
            $shutterspeed=mysql_real_escape_string($_POST['shutterspeed']);
        	$aperture=mysql_real_escape_string($_POST['aperture']);
        	$setcover = mysql_real_escape_string($_POST['setcover']);
        	$lens=mysql_real_escape_string($_POST['lens']);
        	$filter=mysql_real_escape_string($_POST['filter']);
        	$about=mysql_real_escape_string($_POST['about']);
		    $price=mysql_real_escape_string($_POST['price']);
        	$voters = "'support@photorankr.com'";
        	$voters = addslashes($voters);
            
            //LICENSE STUFF
            $baselicense = mysql_real_escape_string($_POST['market']);
            $cc = mysql_real_escape_string($_POST['cc']);
            $license = $baselicense . " " . $cc;
            $ccmods = mysql_real_escape_string($_POST['ccmods']);
            $cccom = mysql_real_escape_string($_POST['cccom']);
            
            $extendedlicense = $_POST['extendedlicenses'];
            while($use[$iii]) {
                $extendedlicenses .= mysql_real_escape_string(htmlentities($extendedlicense[$iii]));
                $extendedlicenses .= " ";
                $iii++;
            }
            $extendedlicenses = substr($extendedlicenses, 0, -1);
            
            if (!$name | !$camera) 
		{
         		header("location:myprofile.php?view=upload&action=uploadfailure");
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
            list($width,$height) = getimagesize($source);
 
    		$target = $path_to_image_directory . $newfilename;   
            
    		if (file_exists("upload/" . $_FILES["file"]["name"]))
      		{
         		header("location:myprofile.php?view=upload&action=uploadfailure");
        		exit();
      		}
    		else
      		{
      			move_uploaded_file($source, $target);
			    chmod ($target, 0644);
            
      			createThumbnail($newfilename); 
      			createMedThumbnail($newfilename); 
      			watermarkpic($newfilename);  
      		}

        	//Get set_id
        	$set_idquery = "SELECT * FROM sets WHERE owner = '$email' AND title = '$addtoset'";
        	$set_idrun = mysql_query($set_idquery);
        	$setidarray = mysql_fetch_array($set_idrun);
        	$set_id = $setidarray['id'];

        	//Concatenate single photo box tags
        	$numbersinglestyletags = count($singlestyletags);
    		for($i=0; $i < $numbersinglestyletags; $i++)
    		{
      			$singlestyletags2 = $singlestyletags2 . " " . mysql_real_escape_string($singlestyletags[$i]) . " ";
    		}
        	$numbersinglecategorytags = count($singlecategorytags);
    		for($i=0; $i < $numbersinglecategorytags; $i++)
    		{
       			$singlecategorytags2 = $singlecategorytags2 . " " . mysql_real_escape_string($singlecategorytags[$i]) . " ";
        	}
        	$numbersettags = count($settags);
        	for($i=0; $i < $numbersettags; $i++)
        	{
            		$settags2 = $settags2 . " " . mysql_real_escape_string($settags[$i]) . " ";
        	}
        	$numbermaintags = count($maintags);
        	for($i=0; $i < $numbermaintags; $i++)
        	{
        		$maintags2 = $maintags2 . " " . mysql_real_escape_string($maintags[$i]) . " ";
        	}
            

		$target = $path_to_medimage_directory . $newfilename;
		//insert the file information into the database
		$insertquery="INSERT INTO photos (source, caption, emailaddress, tag, time, price, location, country, tag1, tag2, tag3, tag4, camera, focallength, shutterspeed, aperture, lens, filter, about, copyright, sets, maintags, settags, set_id, singlestyletags, singlecategorytags,width,height,license,extendedoptions,ccmods,cccom)
		VALUES ('$target', '$name', '$email', '$tag', '$currenttime', '$price', '$location', '$country', '$tag1', '$tag2', '$tag3', '$tag4', '$camera', '$focallength', '$shutterspeed', '$aperture', '$lens', '$filter', '$about', '$copyright', '$addtoset', '$maintags2','$settags2','$set_id','$singlestyletags2','$singlecategorytags2','$width','$height','$license','$extendedlicenses','$ccmods','$cccom')";
		mysql_query($insertquery);

        	//userinfo query
        	$namequery="SELECT * FROM userinfo WHERE emailaddress='$email'";
        	$nameresult=mysql_query($namequery);
        	$row=mysql_fetch_array($nameresult);
        	$firstname=$row['firstname'];
        	$lastname=$row['lastname'];

        	//cover photo query
        	if ($setcover != '') {
        		$coverquery = "UPDATE sets SET cover='$target' WHERE owner = '$email' AND title = '$addtoset'";
        		$coverqueryrun = mysql_query($coverquery);
        	}

        	//newsfeed query
        	$type = "photo";
        	$newsfeedquery=mysql_query("INSERT INTO newsfeed (firstname, lastname,emailaddress,type,source,caption) VALUES ('$firstname','$lastname','$email','$type','$target','$name')");

		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=myprofile.php?view=upload&action=uploadsuccess">';
		exit();
    	}
}
else
{
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=myprofile.php?view=upload&action=uploadfailure">';
	exit();
}

?>