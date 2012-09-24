<?php 

require("db_connection.php");
echo'<script type="text/javascript" src="js/jquery.wookmark.js"></script>';

session_start();
$useremail = $_SESSION['email'];
$cat = $_GET['cat'];

if($_GET['lastPicture']) {

    if($cat == '') {
        $query = mysql_query("SELECT * FROM photos WHERE id < ".$_GET['lastPicture']." AND emailaddress='$useremail' ORDER BY id DESC LIMIT 0, 9");
    }
    
    elseif($cat == 'top') {
        $query = mysql_query("SELECT * FROM photos WHERE (points/votes) < ".$_GET['lastPicture']." AND emailaddress='$useremail' ORDER BY (points/votes) DESC LIMIT 0, 9");
    }
    
    elseif($cat == 'faved') {
        $query = mysql_query("SELECT * FROM photos WHERE faves < ".$_GET['lastPicture']." AND emailaddress='$useremail' ORDER BY faves DESC LIMIT 0, 9");
    }
        
	$numberimages = mysql_num_rows($query);

	//DISPLAY 20 NEWEST OF ALL PHOTOS

    echo'
        <div id="main" role="main">
        <ul id="tiles">';
    
	if($cat != 'exts') {
    
        for($iii=0; $iii < $numberimages; $iii++) {
        
                $image[$iii] = mysql_result($query, $iii, "source");
                $imageThumb[$iii] = str_replace("userphotos/","../userphotos/medthumbs/", $image[$iii]);
                $id = mysql_result($query, $iii, "id");
                $price = mysql_result($query, $iii, "price");
                if($price != 'Not For Sale') {
                    $price = '$' . $price;
                }
                elseif($price == 'Not For Sale') {
                    $price = 'NFS';
                }
                elseif($price == '.00' || $price == '') {
                    $price = 'Free';
                }
                $caption = mysql_result($query, $iii, "caption");
                $points = mysql_result($query, $iii, "points");
                $votes = mysql_result($query, $iii, "votes");
                $faves = mysql_result($query, $iii, "faves");
                $score = number_format(($points/$votes),2);
                $faveemail = mysql_result($query, $iii, "emailaddress");
                $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$faveemail'");
                $firstname = mysql_result($query, 0, "firstname");
                $lastname = mysql_result($query, 0, "lastname");
                $reputation = mysql_result($query, 0, "lastname");
                $fullname = $firstname . " " . $lastname;
                list($width, $height) = getimagesize($image[$iii]);
                $imgratio = $height / $width;
                $heightls = $height / 3.2;
                $widthls = $width / 3.2;
                
                if($widthls < 240) {
                    $heightls = $heightls * ($heightls/$widthls);
                    $widthls = 250;
                }
                
                echo'<a style="text-decoration:none;color:#000;" href="editphotos.php?imageid=',$id,'';if($cat) {echo'&cat=',$cat,'';} echo'"><li class="fPic"'; 
                
                if($cat == '') {
                    echo'id="',$id,'"';
                }
                
                elseif($cat == 'top') {
                    echo'id="',$score,'"';
                }
                
                elseif($cat == 'faved') {
                    echo'id="',$faves,'"';
                }
                
                echo'
                style="padding:5px;margin-right:10px;list-style-type: none;width:240px;
"><img onmousedown="return false" oncontextmenu="return false;" src="http://photorankr.com/',$imageThumb[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /><div style="padding:3px;"><div style="float:left;">',$caption,'</div><div style=float:right;font-size:13px;font-weight:500;">',$price,'</div><br /><span style="font-size:14px;">',$score,'/</span><span style="font-size:12px;color:#444;">10.0</span><br /><i class="icon-heart"></i>&nbsp;',$faves,' favorites</div></li></a>';
        
        } //end of for loop
        
    } //end cat != 'exts'
    
         echo'</ul>';
        
    ?>
    
    <!-- Once the page is loaded, initalize the plug-in. -->
  <script type="text/javascript">
    $(document).ready(new function() {
      // Prepare layout options.
      var options = {
        autoResize: true, // This will auto-update the layout when the browser window is resized.
        container: $('#main'), // Optional, used for some extra CSS styling
        offset: 4, // Optional, the distance between grid items
        itemWidth: 250 // Optional, the width of a grid item
      };
      
      // Get a reference to your grid items.
      var handler = $('#tiles li');
      
      // Call the layout function.
      handler.wookmark(options);
      
    });
  </script>

<?php
      	
	echo'</div>';
    
}//end if clause

?>
