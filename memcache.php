<?php
require "db_connection.php";
$memcache = new Memcache;
$memcache->connect('localhost', 11211) or die ("Could not connect");

//set the key then check the cache
$key = md5("SELECT source FROM photos ORDER BY id DESC LIMIT 0,10");
$get_result = $memcache->get($key);

//result is in memcache server
if ($get_result) {
echo $get_result['source'];
echo "Data Pulled From Cache";
}

//result is not in memcache server
else {
 // Run the query and get the data from the database then cache it
 $query="SELECT source FROM photos ORDER BY id DESC LIMIT 0,10";
 $result = mysql_query($query);
 $row = mysql_fetch_array($result);
 print_r($row);
 $memcache->set($key, $row, TRUE, 20); // Store the result of the query for 20 seconds
 echo "Data Pulled from the Database";
}


?>

<html>
test!
</html>