<?php
ini_set('max_input_time', 300);


//CONNECT TO DB
require "db_connection.php";

$emailcomment = mysql_real_escape_string($_POST['emailcomment']);		
$emailfave = mysql_real_escape_string($_POST['emailfave']);		
$emailfollow = mysql_real_escape_string($_POST['emailfollow']);	

echo'Settings saved!';	

?>