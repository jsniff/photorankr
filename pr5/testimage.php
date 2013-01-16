<?php

     require "db_connection.php"; 

$imagequery = mysql_query("SELECT source FROM photos ORDER BY id DESC LIMIT 16");
$imageSrc = mysql_result($imagequery,$iii,'source');
$source ="https://photorankr.com/".$imageSrc;


//$a = 'test.jpg';

//'https://photorankr.com/userphotos/1356720485001.jpg


<img src="show_image.php?file=<?php echo urlencode('test.jpg'); ?>" />
?>


