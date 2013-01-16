<?php 

require("db_connection.php");

$searchterm = htmlentities($_GET['searchterm']);
$order = htmlentities($_GET['order']);


if($_GET['lastPicture']) {
    
    if($order == '') {
        $query = mysql_query("SELECT * FROM photos WHERE concat(caption,location,tag1,tag2,tag3,tag4) LIKE '%$searchterm%' AND views < ".$_GET['lastPicture']." ORDER BY (points/votes) DESC LIMIT 0,20");
    }
    if($order == 'faves') {
        $query = mysql_query("SELECT * FROM photos WHERE concat(caption,location,tag1,tag2,tag3,tag4) LIKE '%$searchterm%' AND views < ".$_GET['lastPicture']." ORDER BY faves DESC LIMIT 0,20");
    }
    elseif($order == 'views') {
       $query = mysql_query("SELECT * FROM photos WHERE concat(caption,location,tag1,tag2,tag3,tag4) LIKE '%$searchterm%' AND views < ".$_GET['lastPicture']." ORDER BY views DESC LIMIT 0,20");
    }
	
    $numphotos = mysql_num_rows($query);

    //DISPLAY 20 NEWEST OF ALL PHOTOS
     echo'<div id="container">';
        for($iii=0; $iii < 20 && $iii < $numphotos; $iii++) {
            $image = mysql_result($query,$iii,'source');
            $imageid = mysql_result($query,$iii,'id');
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
            
             echo'<div class="searchItem fPic" id="',$imageid,'">
                        <div style="float:left;width:400px;">
                            <img style="width:',$width,'px;height:',$height,';max-width:300px;max-height:500px;" src="https://photorankr.com/',$imagemed,'" />
                        </div>
                        <div style="float:left;">
                           <span id="subSearchWord" style="margin-left:-10px;">',$caption,'</span><br /><br />
                           <span id="searchMicro"><img style="width:30px;height:30px;" src="https://photorankr.com/',$profilepic,'" /> ',$photographer,'</span>
                           <br /><br />
                           <span id="searchMicro">Rank: ',$ranking,'/10.0</span>
                           <br />
                           <span id="searchMicro">Views: ',$views,'</span>
                           <br />
                           <span id="searchMicro">Faves: ',$faves,'</span>
                           <br />
                        </div>
                     </div>';            
    }
    
    echo'</div>';

}//end if clause

?>
