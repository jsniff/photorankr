<html>
<link rel="stylesheet" type="text/css" href="css/main3.css" />
//Click anywhere else, make box disappear

<script type="text/javascript">

jQuery(document).ready(function(){
    jQuery(".container").live("click", function(event) {        
         jQuery("#livesearchDiv").hide();
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
$searchusersquery = mysql_query("SELECT firstname,lastname,profilepic,user_id FROM userinfo WHERE concat(firstname,lastname) LIKE '%$searchword%' ORDER BY reputation DESC");
$numuserresults = mysql_num_rows($searchusersquery);

echo'<div id="livesearchDiv" class="uiScrollableAreaTrack invisible_elem" style="width:320px;height:500px;border:1px solid #666;-webkit-border-radius: 2px;-moz-border-radius: 2px;border-radius: 2px;background-color:rgb(250,250,250);position:absolute;top:30px;overflow:hidden;overflow-y:scroll;">

<div style="border-bottom:1px solid #aaa;text-align:left;font-family:\'helvetica neue\', helvetica, arial;font-weight:300;padding:5px;">Photos  &mdash; ',$numresults,' Results</div>';

for($iii=0; $iii<6 && $iii<$numresults; $iii++) {
	$source = mysql_result($searchquery,$iii,'source');
    $imageid = mysql_result($searchquery,$iii,'id');
    $source = str_replace('userphotos/','userphotos/thumbs/',$source);
	$caption = mysql_result($searchquery,$iii,'caption'); 
    $caption = (strlen($caption) > 35) ? substr($caption,0,32). " &#8230;" : $caption;
    $ranking = number_format(mysql_result($searchquery,$iii,'points')/mysql_result($searchquery,$iii,'votes'),2);
    $faves = mysql_result($searchquery,$iii,'faves');

	echo'<div id="liveSearchDiv" style="float:left;padding:2px;clear:both;overflow:hidden;width:300px;font-size:14px;font-weight:300;font-family:\'helvetica neue\', helvetica, arial;border-bottom:1px solid #aaa;background-color:rgb(254,254,254);">
                            <a href="fullsize.php?imageid=',$imageid,'"><img style="float:left;width:100px;height:100px;" src="https://photorankr.com/',$source,'" /></a>
                            <div class="commentTriangle" style="margin-top:-10px;float:left;"></div>
                            <div style="width:188px;float:left;padding-left:10px;height:75px;margin-top:25px;text-align:left;">
                                <span style="width:15px;"><span style="font-weight:500;">',$ranking,'</span><a href="fullsize.php?imageid=',$imageid,'"> ',$caption,'</a><br /><span style="font-size:14px;color:#666;">',$faves,' favorites</span></span>
                            </div>
                         </div>';
}

echo'<div style="border-bottom:1px solid #aaa;text-align:left;font-family:\'helvetica neue\', helvetica, arial;font-weight:300;padding:5px;">Members &mdash; ',$numuserresults,' Results</div>';
for($iii=0; $iii<5 && $iii<$numuserresults; $iii++) {
    $reputation = mysql_result($searchusersquery,$iii,'reputation');
    $useremail = mysql_result($searchusersquery,$iii,'emailaddress');
	$profilepic = mysql_result($searchusersquery,$iii,'profilepic');
    $name = mysql_result($searchusersquery,$iii,'firstname') ." ". mysql_result($searchusersquery,$iii,'lastname');
	$user_id = mysql_result($searchusersquery,$iii,'user_id');
    $numuserphotosquery = mysql_query("SELECT id FROM photos WHERE emailaddress = '$useremail'");
    $numuserphotos = mysql_num_rows($numuserphotosquery);

    echo'<div id="liveSearchDiv" style="float:left;padding:2px;clear:both;overflow:hidden;width:310px;font-size:14px;font-weight:300;font-family:\'helvetica neue\', helvetica, arial;border-bottom:1px solid #aaa;background-color:rgb(254,254,254);">
            <a href="viewprofile.php?u=',$user_id,'"><img style="float:left;width:100px;height:100px;" src="https://photorankr.com/',$profilepic,'" /></a>
            <div class="commentTriangle" style="margin-top:-10px;float:left;"></div>
            <div style="width:188px;float:left;padding-left:10px;height:75px;margin-top:25px;text-align:left;">
                <span style="width:15px;"><span style="font-weight:500;">',$reputation,'</span><a href="viewprofile.php?u=',$user_id,'"> ',$name,'</a><br /><span style="font-size:14px;color:#666;">',$numuserphotos,' photos</span></span>
            </div>
        </div>';
                    
}

echo'</div>';

}

?>
</html>