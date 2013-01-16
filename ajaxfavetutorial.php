<?php

    //connect to the database
    require "db_connection.php";
    require "functions.php";

    $email = mysql_real_escape_string($_GET['age']);
    $image = mysql_real_escape_string($_GET['image']);
    $currenttime = time();

    //Run a query to be used to check if the image is already there
    
    $check = mysql_query("SELECT * FROM userinfo WHERE emailaddress='$email'") or die(mysql_error());
    $viewerfirst = mysql_result($check, 0, "firstname");
    $viewerlast = mysql_result($check, 0, "lastname");
    $imagelink2=str_replace(" ","", $image);
	
    //create the image variable to be used in the query, appropriately escaped
    $queryimage = "'" . $image . "'";
    $queryimage = ", " . $queryimage;
    $queryimage = addslashes($queryimage);
	
    //search for the image in the database as a check for repeats
    $mycheck = mysql_result($check, 0, "faves");
    $search_string = $mycheck;
    $regex=$image;
    $match=strpos($search_string, $regex);
        
    //If the image hasn't favorited
    
    if(!$match) {
    
        $favesquery="UPDATE userinfo SET faves=CONCAT(faves,'$queryimage') WHERE emailaddress='$email'";
        mysql_query($favesquery);
        mysql_query("UPDATE photos SET faves=faves+1 WHERE source = '$image'");
        
         //current number of favorites
        $numfavesquery = mysql_query("SELECT caption,faves,emailaddress FROM photos WHERE source = '$image'");
        $owner = mysql_result($numfavesquery,0,'emailaddress');
        $caption = mysql_result($numfavesquery,0,'caption');
        $numfaves = mysql_result($numfavesquery,0,'faves');
        echo '<i style="margin-top:3px;" class="icon-ok icon-white"></i> Favorited';
    
        //Grab owner of photo information
        $ownerinfo = mysql_query("SELECT firstname,lastname,emailaddress FROM userinfo WHERE emailaddress = '$owner'");
        $ownerfirstname = mysql_result($ownerinfo,0,'firstname');
        $ownerlastname = mysql_result($ownerinfo,0,'lastname');
        $owneremail = mysql_result($ownerinfo,0,'emailaddress');
            
        //newsfeed query
        $type = "fave";
        $newsfeedfavequery=mysql_query("INSERT INTO newsfeed (firstname,lastname,emailaddress,type,source,caption,owner,time) VALUES ('$viewerfirst', '$viewerlast', '$email','$type','$image','$caption','$owneremail','$currenttime')");
     
        //notifications query     
        $notsquery = "UPDATE userinfo SET notifications = (notifications + 1) WHERE emailaddress = '$owneremail'";
        $notsqueryrun = mysql_query($notsquery); 

        //GRAB SETTINGS LIST
        $settingquery = "SELECT settings FROM userinfo WHERE emailaddress = '$email'";
        $settingqueryrun = mysql_query($settingquery);
        $settinglist = mysql_result($settingqueryrun, 0, "settings");
                                  
        $setting_string = $settinglist;
        $find = "emailfave";
        $foundsetting = strpos($setting_string,$find);
            
            
        //MAIL PHOTOGRAPHER NOTICE THAT THEIR PHOTO HAS BEEN FAVORITED
        $to = '"' . $ownerfirstname . ' ' . $ownerlastname . '"' . '<'.$owneremail.'>';
        $subject = $viewerfirst . " " . $viewerlast . " favorited one of your photos on PhotoRankr";
        $favemessage = $viewerfirst . " " . $viewerlast . " favorited one of your photos on PhotoRankr
        
To view the photo, click here: https://photorankr.com/fullsize.php?image=".$imagelink2;
        $headers = 'From:PhotoRankr <photorankr@photorankr.com>';
          
        if($foundsetting > 0) {
            mail($to, $subject, $favemessage, $headers); 
        }

} //end of match

?>