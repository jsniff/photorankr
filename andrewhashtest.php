<?php

//connect to the database
require "db_connection.php";
// require "functionsnav.php";
// require "timefunction.php";

//start the session
session_start();

    // if login form has been submitted
    if (htmlentities($_GET['action']) == "login") { 
        login();
    }
    else if(htmlentities($_GET['action']) == "logout") { 
        logout();
    }

    $email = $_SESSION['email'];
    
    if (!$_SESSION['email']) {
        header("Location: signup.php");
        exit();
    } 
	
$userid = 1792;
//while($userid<1818){
	$userinfo = mysql_query("SELECT * FROM userinfo WHERE user_id = '$userid'");
	$firstname= mysql_result($userinfo,0,'firstname');
	$lastname= mysql_result($userinfo,0,'lastname');
	$pass= mysql_result($userinfo,0,'password');
	$email= mysql_result($userinfo,0,'emailaddress');
	$pass = "marketplace2012";
	if($pass==NULL){
		print "user id: $userid does not exist <br>";
	}else{

		$salt1 = "@bRa";
		$salt2 = "Cad@bra!";
		$shatemp = sha1($salt1.$pass.$salt2);
		for($i=0;$i<20000;$i++){
			$shatemp = sha1($shatemp);
		}
		echo "pass is $shatemp <br>";
		echo "$firstname $lastname $email $pass <br>";
		//mysql_query("UPDATE userinfo SET password='$shatemp' WHERE user_id='$userid'");
		
	}
	$userid++;
//}
	
	print "<br><br>hashing done!";
?>