<?php

    //connect to the database
    require "db_connection.php";
    require "functions.php";

    $email = mysql_real_escape_string($_GET['follower']);
    $emailaddress = mysql_real_escape_string($_GET['followee']);
    $currenttime = time();
    
    //Followee information
    $followeequery = mysql_query("SELECT firstname,lastname FROM userinfo WHERE emailaddress = '$emailaddress'");
    $firstname = mysql_result($followeequery,0,'firstname');
    $lastname = mysql_result($followeequery,0,'lastname');
    
		$emailquery=("SELECT * FROM userinfo WHERE emailaddress = '$email'");
		$emailresult=mysql_query($emailquery);
		$prevemails=mysql_result($emailresult, 0, "following");
		$viewerfirst = mysql_result($emailresult, 0, "firstname");
		$viewerlast = mysql_result($emailresult, 0, "lastname");
		if($prevemails == "") {$emailaddressformatted="'". $emailaddress . "'";}
		else {$emailaddressformatted=", '". $emailaddress . "'";}
        
		//MAKE SURE FOLLOWER ISN'T ADDED TWICE
		$match=preg_match($emailaddress,$prevemails);
		
        if ($match > 0) {
        
        } 
        
		else {
        
			$emailaddressformatted = addslashes($emailaddressformatted);
			$followquery = "UPDATE userinfo SET following=CONCAT(following,'$emailaddressformatted') WHERE emailaddress = '$email'";
			$followingresult = mysql_query($followquery);
            
            $type2 = "follow";
            $ownername = $firstname . " " . $lastname;
            $newsfeedfollowquery="INSERT INTO newsfeed (firstname, lastname, emailaddress,following,type,owner,time) VALUES ('$viewerfirst', '$viewerlast', '$email','$emailaddress','$type2','$ownername','$currenttime')";
            $follownewsquery = mysql_query($newsfeedfollowquery);
        
            //notifications query     
            $notsquery = "UPDATE userinfo SET notifications = (notifications + 1) WHERE emailaddress = '$emailaddress'";
            $notsqueryrun = mysql_query($notsquery);  
        
            //GRAB SETTINGS LIST
            $settingquery = "SELECT * FROM userinfo WHERE emailaddress = '$email'";
            $settingqueryrun = mysql_query($settingquery);
            $settinglist = mysql_result($settingqueryrun, 0, "settings");
            $viewerfirst = mysql_result($settingqueryrun, 0, "firstname");
            $viewerlast = mysql_result($settingqueryrun, 0, "lastname");
            $viewerid = mysql_result($settingqueryrun, 0, "user_id");

            $setting_string = $settinglist;
            $find = "emailfollow";
            $foundsetting = strpos($setting_string,$find);
    
            $to = '"' . $firstname . ' ' . $lastname . '"' . '<'.$emailaddress.'>';
            $subject = $viewerfirst . " " . $viewerlast . ' is now following your photography on PhotoRankr!';
            $message = 'You have a new follower on PhotoRankr! Visit their photography here: https://photorankr.com/viewprofile.php?u='.$viewerid;
            $headers = 'From:PhotoRankr <photorankr@photorankr.com>';
            if($foundsetting > 0) {
                mail($to, $subject, $message, $headers);   
            }
            
		}
        
        echo'<i style="margin-top:3px;" class="icon-ok icon-white"></i> Following';

?>