<?php


//connect to the database
require "db_connection.php";
require "functionsnav.php";

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
    
    if ($_SESSION['loggedin'] != 1) {
        header("Location: signin.php");
        exit();
    } 

navbarnew();

?>





<!DOCTYPE html>
<html>

<link rel="stylesheet" type="text/css" href="css/bootstrapNew.css" />
  <link rel="stylesheet" href="text2.css" type="text/css" />
  <link rel="stylesheet" href="960_24.css" type="text/css" />
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script src="bootstrap.js" type="text/javascript"></script>
  <script src="bootstrap-dropdown.js" type="text/javascript"></script>
  <script src="bootstrap-collapse.js" type="text/javascript"></script>
  <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>


<p>
      <a href="https://manage.stripe.com/oauth/authorize?response_type=code&client_id=ca_07cmwOi7PRZWc7S0trIrH5wxJ0qlkMT6&scope=admin" target="blank" class="medium grey button">
      <span>Connect with Stripe</span>
      </a>
    </p>

</html>


