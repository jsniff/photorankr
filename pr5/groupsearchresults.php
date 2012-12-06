
<?php

//connect to the database
require "db_connection.php";

$searchword = mysql_real_escape_string(htmlentities($_GET['q']));
$photog = mysql_real_escape_string(htmlentities($_GET['photog']));

echo'<link rel="stylesheet" type="text/css" href="css/style.css"/>';

//Search Photos
$searchquery = mysql_query("SELECT source,caption FROM photos WHERE concat(caption,location,tag1,tag2,tag3,tag4) LIKE '%$searchword%' AND emailaddress = '$photog' ORDER BY views DESC");
$numresults = mysql_num_rows($searchquery);

echo'<div style="width:300px;max-height:350px;ooverflow-y:scroll;border:1px solid #000;background-color:#fff;z-index:10000;">

<div style="background-color:rgba(0,0,0,.2);"><strong>Photos | ',$numresults,' Results</strong></div>';
for($iii=0; $iii<6 && $iii<$numresults; $iii++) {
	$source = mysql_result($searchquery,$iii,'source');
    $source = str_replace('userphotos/','userphotos/thumbs/',$source);
	$caption = mysql_result($searchquery,$iii,'caption'); 
    $caption = (strlen($caption) > 35) ? substr($caption,0,32). " &#8230;" : $caption;

	echo'<a style="text-decoration:none;" href="fullsize.php?imageid=',$imageid,'"><div class="livebox"><img src="../',$source,'" style="width:40px;" />&nbsp;&nbsp;',$caption,'</div></a>';
}

echo'</div>';


?>
