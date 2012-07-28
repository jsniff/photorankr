<?php

//give this file some extra time to upload the photo
ini_set('max_input_time', 2200);

//CONNECT TO DB
require "db_connection.php";


$emailquery = mysql_query("SELECT emailaddress FROM userinfo ORDER BY user_id ASC");
$numemails = mysql_num_rows($emailquery);

for($iii=0; $iii < $numemails; $iii++) {
$email = mysql_result($emailquery,$iii,'emailaddress');
$emaillist = $emaillist . $email . ", ";
}

echo $emaillist;

?>