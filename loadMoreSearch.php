<?php 

require("db_connection.php");

$searchterm = htmlentities($_GET['searchterm']);

if($_GET['lastPicture']) {

	$query = mysql_query("SELECT * FROM photos WHERE concat(caption,location,tag1,tag2,tag3,tag4) LIKE '%$searchterm%' AND views < ".$_GET['lastPicture']." ORDER BY views DESC LIMIT 0,20");
    $numphotos = mysql_num_rows($query);

    //DISPLAY 20 NEWEST OF ALL PHOTOS
     echo'<div id="container">';
        for($iii=0; $iii < 20 && $iii < $numphotos; $iii++) {
            $image = mysql_result($query,$iii,'source');
            $imagemed = str_replace('userphotos/','userphotos/medthumbs/',$image);
            $caption = mysql_result($query,$iii,'caption');
            $caption = (strlen($caption) > 30) ? substr($caption,0,27). " &#8230;" : $caption;
            $ranking = (mysql_result($query,$iii,'points')/mysql_result($query,$iii,'votes'));
            $ranking = number_format($ranking,1);
            $faves = mysql_result($query,$iii,'faves');
            $views = mysql_result($query,$iii,'views');
            $useremail = mysql_result($query,$iii,'emailaddress');

            $query2 =  mysql_query("SELECT user_id,firstname,lastname,profilepic FROM userinfo WHERE emailaddress = '$useremail'");
        $numresults = mysql_num_rows($query);
            $photographer = mysql_result($query2,0,'firstname')." ".mysql_result($query2,0,'lastname');
            $profilepic = mysql_result($query2,0,'profilepic');
            $userid = mysql_result($query2,0,'user_id');
            
            list($width,$height) = getimagesize($image);
            $width = $width/4.5;
            $height = $height/4.5;
            
            echo'<div class="fPic" id="',$views,'" style="padding:15px;float:left;width:300px;">';
            
            if($faves > 5 || $points > 120 || $views > 100) {
            echo'
            <div style="margin-top:-50px;"><img style="max-width:300px;max-height:300px;" src="',$imagemed,'" height="',$height,'" width="',$width,'" />
            <img style="margin-top:',$height,'px;margin-left:',$newwidth-55,'px;" src="graphics/toplens2.png" height="85" /></div>';
            }
            else {
            echo'
           <img style="max-width:300px;max-height:300px;" src="',$imagemed,'" height="',$height,'" width="',$width,'" />';            
            }
            
        echo'
            </div><div style="margin-left:30px;float:left;margin-top:',$height/2,';"><span style="font-size:24px;color:black;">"<a style="color:black;" href="fullsize.php?image=',$image,'">',$caption,'</a>"</span><br /><br /><img src="',$profilepic,'" width="40" height="40" />&nbsp;&nbsp;<a href="viewprofile.php?u=',$userid,'" style="color:#3e608c;font-weight:bold;font-size:14px;">',$photographer,'</a><br />Photo Rank:&nbsp;<span style="font-size:22px;">',$ranking,'</span><span style="opacity:.7;">/10</span><br />Favorites: <span style="font-size:22px;">',$faves,'</span><br />Views: <span style="font-size:22px;">',$views,'</span><br /><br /></div><hr>';
            
    }
    
    echo'</div>';

}//end if clause

?>
