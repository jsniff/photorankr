<?php

        
    //ACTION SIGNUP
    
    if($_GET['action'] == "signup") { //if they tried to sign up from signin.php
	$firstname = addslashes($_REQUEST['firstname']);
    $firstname = trim($firstname);
    $firstname = ucwords($firstname);
	$lastname = addslashes($_REQUEST['lastname']);
    $optin = addslashes($_REQUEST['optin']);
    $lastname = trim($lastname);
    $lastname = ucwords($lastname);
	$newemail = mysql_real_escape_string($_REQUEST['emailaddress']);
	$password = mysql_real_escape_string($_REQUEST['password']);
	$confirmpassword = mysql_real_escape_string($_REQUEST['confirmpassword']);
	$terms = mysql_real_escape_string($_REQUEST['terms']);
	$mattfollow = "'support@photorankr.com'";
	$originalfave = "'userphotos/paintedbuilding1.jpg'";
	$originalfave = addslashes($originalfave);
	$mattfollow = addslashes($mattfollow);
	$check = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$newemail'");
	$others = mysql_num_rows($check);
    $currenttime = time();
    $ip = $_SERVER['REMOTE_ADDR'];
    
    // Create a unique  activation code
    $activation = md5(uniqid(rand(), true));
                        
	//if they forgot to enter any information
	if(!$_REQUEST['firstname'] or !$_REQUEST['lastname'] or !$_REQUEST['emailaddress'] or !$_REQUEST['password'] or !$_REQUEST['confirmpassword'] or !$_REQUEST['terms']) {
		mysql_close();
		$error = 1;
	}
	
	elseif(strlen($password) < 5) { //if passwords dont match
		mysql_close();
		$error = 4;
	}

	elseif($password != $confirmpassword) { //if passwords dont match
		mysql_close();
		$error = 2;
	}
    	
	elseif(!$terms) { //if passwords dont match
		mysql_close();
		$error = 3;
	}
	
	//else if that email address is already in the database
	else if($others != 0) {
		$error = 5;
	}
	else {
		//put their info in database
        $settinglist = " emailcomment emailreturncomment emailfave emailfollow ";
		$newuserquery = "INSERT INTO userinfo (firstname, lastname, emailaddress, password, following, faves, settings, promos, time, ip) VALUES ('$firstname', '$lastname', '$newemail', '$shapass', '$mattfollow', '$originalfave','$settinglist','$optin','$currenttime','$ip')";
		mysql_query($newuserquery);
        
        /*Activation Email
        $message = " To activate your PhotoRankr account, please click on this link:\n\n";
        $message .= "https://www.photorankr.com" . '/activate.php?email=' . urlencode($newemail) . "&key=$activation";
        mail($newemail, 'Registration Confirmation', $message, 'From:PhotoRankr <photorankr@photorankr.com>');
        */
        
         //newsfeed query
        $type = "signup";
        $newsfeedsignupquery=mysql_query("INSERT INTO newsfeed (firstname, lastname, emailaddress,type,time) VALUES ('$firstname', '$lastname', '$newemail','$type','$currenttime')");
        
        //SEND REGISTRATION GREETING
        
        $to = $newemail;
        $subject = 'Welcome to PhotoRankr!';
        $message = 'Thank you for signing up with PhotoRankr! You can now upload your own photos and sell them at your own price, follow the best photographers, and become part of a growing community. If you have any questions about PhotoRankr or would like to suggest an improvement, you can email us at photorankr@photorankr.com. We greatly value your feedback and hope you will spread the word about PhotoRankr to your friends by referring them to the site with the link below:
        
		http://photorankr.com/referral.php        

		Again, welcome to the site!

		Sincerely,
		PhotoRankr';
        $headers = 'From:PhotoRankr <photorankr@photorankr.com>';
        mail($to, $subject, $message, $headers);  
        
        login();
        
        header('Location: https://photorankr.com/tutorial.php?action=step1');
    }
}
     	<link href = "css/main3.css" rel="stylesheet" type="text/css"/>
 		<link rel="stylesheet" type="text/css" href="css/all.css"/>
<div class="CNav" style="position:fixed;top:0;left:0;z-index:10000;">
<div class="homeNav" style="width:100%;z-index:10000;">
	<ul>
		<li style="width:180px;"> <img src="graphics/logo_big_w.png" style="width:180px!important;margin-top:-42px;margin-left:35px;"/> </li>
	</ul>
</div></div>

    <?php
    
         if($_GET['action'] == "signup") {echo'<div style="text-align:center;">Please check your email to finish registration, thank you.</div>';}
        else {echo'<div style="text-align:center;"> Complete your sign up for free. </div>';}
    ?>
   </h1>
             elseif($error == 3) {echo'<div style="font-size:16px;text-align:center;font-weight:400;color:red;padding-bottom:10px;">Please agree to the terms & conditions</div>';} 
	     elseif($error == 4) {echo'<div style="font-size:16px;text-align:center;font-weight:400;color:red;padding-bottom:10px;">Please choose a password longer than 5 characters</div>';}
 	     elseif($error == 5) {echo'<div style="font-size:16px;text-align:center;font-weight:400;color:red;padding-bottom:10px;">This email address already exists.<br /><a href="lost password.php">Lost password?</a></div>';}

<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;networks such as Pinterest (optional).