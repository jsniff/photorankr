<html>
<link rel="stylesheet" type="text/css" href="css/main3.css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

<script type="text/javascript">
//Click anywhere else, make box disappear
jQuery(document).ready(function(){
    jQuery(".container_24").live("click", function(event) {        
         jQuery("#searchDiv").hide();
    });
});

</script>

<?php

//connect to the database
require "db_connection.php";

$searchword = mysql_real_escape_string(htmlentities($_GET['q']));

if (strlen($searchword)>1) {

//Search Photos
$searchquery = mysql_query("SELECT source,caption,points,votes,faves,id FROM photos WHERE concat(caption,location,tag1,tag2,tag3,tag4) LIKE '%$searchword%' ORDER BY views DESC");
$numresults = mysql_num_rows($searchquery);

//Search Users
$searchusersquery = mysql_query("SELECT firstname,lastname,profilepic,user_id,emailaddress,reputation FROM userinfo WHERE concat(firstname,lastname) LIKE '%$searchword%' ORDER BY reputation DESC");
$numuserresults = mysql_num_rows($searchusersquery);

echo'<div id="searchDiv" class="uiScrollableAreaTrack invisible_elem" style="width:320px;height:500px;border:1px solid #666;-webkit-border-radius: 2px;-moz-border-radius: 2px;border-radius: 2px;background-color:rgb(250,250,250);position:absolute;top:30px;overflow:hidden;overflow-y:scroll;">

<div style="border-bottom:1px solid #aaa;text-align:left;font-family:\'helvetica neue\', helvetica, arial;font-size:20px;font-weight:300;padding:5px;">Photos  &mdash; ',$numresults,' Results</div>';

for($iii=0; $iii<6 && $iii<$numresults; $iii++) {
	$source = mysql_result($searchquery,$iii,'source');
    $imageid = mysql_result($searchquery,$iii,'id');
    $source = str_replace('userphotos/','userphotos/thumbs/',$source);
	$caption = mysql_result($searchquery,$iii,'caption'); 
    $caption = (strlen($caption) > 35) ? substr($caption,0,32). " &#8230;" : $caption;
    $ranking = number_format(mysql_result($searchquery,$iii,'points')/mysql_result($searchquery,$iii,'votes'),2);
    $faves = mysql_result($searchquery,$iii,'faves');

	echo'<div class="liveSearchDiv">
                            <a style="overflow:hidden;" href="fullsize.php?imageid=',$imageid,'"><img style="float:left;width:100px;height:100px;" src="https://photorankr.com/',$source,'" />
                            <div class="commentTriangle" style="margin-top:-10px;float:left;"></div>
                            <div style="width:188px;float:left;padding-left:10px;height:75px;margin-top:25px;text-align:left;">
                                <span style="width:15px;"><span style="font-weight:500;">',$ranking,'</span> ',$caption,'<br /><span style="font-size:14px;color:#666;">',$faves,' favorites</span></span>
                            </div>
                            </a>
                         </div>';
}

echo'<div style="border-bottom:1px solid #aaa;text-align:left;font-family:\'helvetica neue\', helvetica, arial;font-size:20px;font-weight:300;padding:5px;">Members &mdash; ',$numuserresults,' Results</div>';
for($iii=0; $iii<5 && $iii<$numuserresults; $iii++) {
    $reputation = mysql_result($searchusersquery,$iii,'reputation');
    $useremailagain = mysql_result($searchusersquery,$iii,'emailaddress');
	$profilepic = mysql_result($searchusersquery,$iii,'profilepic');
    $name = mysql_result($searchusersquery,$iii,'firstname') ." ". mysql_result($searchusersquery,$iii,'lastname');
	$user_id = mysql_result($searchusersquery,$iii,'user_id');
    $numuserphotosquerynewname = mysql_query("SELECT id FROM photos WHERE emailaddress = '$useremailagain'");
    $numuserphotosnewname = mysql_num_rows($numuserphotosquerynewname);

    echo'<div class="liveSearchDiv">
            <a href="viewprofile.php?u=',$user_id,'"><img style="float:left;width:100px;height:100px;" src="https://photorankr.com/',$profilepic,'" />
            <div class="commentTriangle" style="margin-top:-10px;float:left;"></div>
            <div style="width:188px;float:left;padding-left:10px;height:75px;margin-top:25px;text-align:left;">
                <span style="width:15px;"><span style="font-weight:500;">',$reputation,'</span> ',$name,'<br /><span style="font-size:14px;color:#666;">',$numuserphotosnewname,' photos</span></span>
            </div>
            </a>
        </div>';
                    
}

echo'</div>';

}

?>
</html>