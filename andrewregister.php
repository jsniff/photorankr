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
        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=register.php?error=1">';
        exit();
	}
	else if($password != $confirmpassword) { //if passwords dont match
		mysql_close();
        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=register.php?error=2">';
        exit();
	}
	//else if that email address is already in the database
	else if($others != 0) {
		header("Location: lostpassword.php");
	}
	else {
		//put their info in database
        $settinglist = " emailcomment emailreturncomment emailfave emailfollow ";
		$newuserquery = "INSERT INTO userinfo (firstname, lastname, emailaddress, password, following, faves, settings, promos, time, ip, activation) VALUES ('$firstname', '$lastname', '$newemail', '$shapass', '$mattfollow', '$originalfave','$settinglist','$optin','$currenttime','$ip','$activation')";
		mysql_query($newuserquery);
        
        //Activation Email
        $message = " To activate your PhotoRankr account, please click on this link:\n\n";
        $message .= "https://www.photorankr.com" . '/activate.php?email=' . urlencode($newemail) . "&key=$activation";
        mail($newemail, 'Registration Confirmation', $message, 'From:PhotoRankr <photorankr@photorankr.com>');

        
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
        
        //start the session
        @session_start();

        login();

        $email = $_SESSION['email'];
            
    }
}
        <link rel="stylesheet" type="text/css" href="css/main3.css" />
