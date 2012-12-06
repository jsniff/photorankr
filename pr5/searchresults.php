<?php

//connect to the database
require "db_connection.php";

$searchword = mysql_real_escape_string(htmlentities($_GET['q']));

if (strlen($searchword)>1) {

//Search Photos
$searchquery = mysql_query("SELECT source,caption FROM photos WHERE concat(caption,location,tag1,tag2,tag3,tag4) LIKE '%$searchword%' ORDER BY views DESC");
$numresults = mysql_num_rows($searchquery);

//Search Users
$searchusersquery = mysql_query("SELECT firstname,lastname,profilepic,user_id FROM userinfo WHERE concat(firstname,lastname) LIKE '%$searchword%' ORDER BY reputation DESC");
$numuserresults = mysql_num_rows($searchusersquery);

echo'<div style="width:288px;border:1px solid #000;background-color:rgba(0,0,0,.5);position:relative;top:-37px;z-index:1000;">

<div style="background-color:rgba(0,0,0,.2);text-align:left;"><strong>Photos | ',$numresults,' Results</strong></div>';
for($iii=0; $iii<6 && $iii<$numresults; $iii++) {
	$source = mysql_result($searchquery,$iii,'source');
    $source = str_replace('userphotos/','userphotos/thumbs/',$source);
	$caption = mysql_result($searchquery,$iii,'caption'); 
    $caption = (strlen($caption) > 35) ? substr($caption,0,32). " &#8230;" : $caption;

	echo'<a style="text-decoration:none;" href="fullsize.php?imageid=',$imageid,'"><div class="livebox"><img src="../',$source,'" style="width:40px;" />&nbsp;&nbsp;',$caption,'</div></a>';
}

echo'<div style="background-color:rgba(0,0,0,.5);text-align:left;"><strong>Members | ',$numuserresults,' Results</strong></div>';
for($iii=0; $iii<5 && $iii<$numuserresults; $iii++) {
	$profilepic = mysql_result($searchusersquery,$iii,'profilepic');
    $name = mysql_result($searchusersquery,$iii,'firstname') ." ". mysql_result($searchusersquery,$iii,'lastname');
	$user_id = mysql_result($searchusersquery,$iii,'user_id');
	echo'<a style="text-decoration:none;" href="viewprofile.php?u=',$user_id,'"><div class="livebox"><img src="../',$profilepic,'" />&nbsp;&nbsp;',$name,'</div></a>';
}
echo'</div>';

}

?>
