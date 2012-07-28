<?php

require "db_info.php";

$con=mysql_connect(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD) 
 
 or die("<p>Error connecting to the database: " . mysql_error() . "</p>");

 mysql_select_db(DATABASE_NAME) or die(mysql_error()); 

?>