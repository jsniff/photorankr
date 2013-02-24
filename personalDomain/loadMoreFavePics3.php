<?php 

require("db_connection.php");

if($_GET['lastPicture']) {
	session_start();
	$emailforthisfile = $_SESSION['email'];

	$lastpic = mysql_real_escape_string($_GET['lastPicture']);
	$user = mysql_real_escape_string($_GET['user']);
	$userquery = "SELECT faves FROM userinfo WHERE emailaddress='$emailforthisfile' LIMIT 0, 1";
	$userquery = mysql_query($userquery);
	$faves = mysql_result($userquery, 0, "faves");

	$favesquery = "SELECT * FROM photos WHERE source IN ($faves) ORDER BY FIELD(source, $faves) DESC";
	$favesresult = mysql_query($favesquery);

	$favesSeen = "";
	$iii=0;
	for(; $iii<mysql_num_rows($favesresult); $iii++) {
		if($lastpic!=mysql_result($favesresult, $iii, "id")) {
			$favesSeen .= "'";
			$favesSeen .= mysql_result($favesresult, $iii, "source");
			$favesSeen .= "',";
		}
		else if($lastpic == mysql_result($favesresult, $iii, "id")) {
			break;
		}
	}
	$favesSeen .= "'";
	$favesSeen .= mysql_result($favesresult, $iii+1, "source");
	$favesSeen .= "'";

	$query = "SELECT * FROM photos WHERE source IN($faves) AND source NOT IN($favesSeen) ORDER BY FIELD(source, $faves) DESC";
	$mysqlquery = mysql_query($query);
	$numphotos = mysql_num_rows($mysqlquery);

	//DISPLAY 20 NEWEST OF ALL PHOTOS

        echo'
        <div id="main" role="main">
        <ul id="tiles">';

	for($iii=1; $iii < 9 && $iii < $numphotos; $iii++) {
		$image[$iii] = mysql_result($mysqlquery, $iii, "source");
    $imageThumb[$iii] = str_replace("userphotos/","userphotos/medthumbs/", $image[$iii]);
    $imageThumb[$iii] = str_replace(".JPG",".jpg", $imageThumb[$iii]);
	$id = mysql_result($mysqlquery, $iii, "id");
    $price = mysql_result($mysqlquery, $iii, "price");
                if($price != 'Not For Sale') {
                    $price = '$' . $price;
                }
                elseif($price == 'Not For Sale') {
                    $price = 'NFS';
                }
                elseif($price == '.00' || $price == '') {
                    $price = 'Free';
                }
    $caption = mysql_result($mysqlquery, $iii, "caption");
    $points = mysql_result($mysqlquery, $iii, "points");
    $votes = mysql_result($mysqlquery, $iii, "votes");
    $faves = mysql_result($mysqlquery, $iii, "faves");
    $faveemail = mysql_result($mysqlquery, $iii, "emailaddress");
    $score = number_format(($points/$votes),2);
    $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$faveemail'");
    $firstname = mysql_result($ownerquery, 0, "firstname");
    $lastname = mysql_result($ownerquery, 0, "lastname");
    $fullname = $firstname . " " . $lastname;
	list($width, $height) = getimagesize($image[$iii]);
	$imgratio = $height / $width;
    $heightls = $height / 3.2;
                $widthls = $width / 3.2;
                
                if($widthls < 205) {
                    $heightls = $heightls * ($heightls/$widthls);
                    $widthls = 250;
                }

                echo '
        <a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$id,'&v=n"><li class="fPic" id="',$id,'" style="list-style-type: none;width:280px;"><img style="min-width:280px;" onmousedown="return false" oncontextmenu="return false;"  src="https://photorankr.com/',$imageThumb[$iii],'" height="',$heightls,'px" width="',$widthls,'px" />
        
            <div class="statoverlay" style="z-index:1;background-color:rgba(0,0,0,.8);position:relative;top:0px;width:280px;height:30px;"><div style="line-spacing:1.48;padding:5px;color:white;"><div style="float:left;"<span style="font-size:18px;font-weight:100;">',$score,'</span>&nbsp;&nbsp;<span style="font-weight:100;font-size:16px;">',$caption,'</span></div><div style="float:right;"><span style="font-size:13px;">',$price,'</span></div></div><br/></div>';                
            } //end for loop      
        
        echo'</ul>';
        
    ?>
    
    <!-- Once the page is loaded, initalize the plug-in. -->
  <script type="text/javascript">
    $(document).ready(new function() {
      // Prepare layout options.
      var options = {
        autoResize: true, // This will auto-update the layout when the browser window is resized.
        container: $('#main'), // Optional, used for some extra CSS styling
        offset: 10, // Optional, the distance between grid items
        itemWidth: 280 // Optional, the width of a grid item
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
