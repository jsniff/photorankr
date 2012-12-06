<?php

//connect to the database
require "../db_connection.php";
require "functions.php";

//start the session
session_start();

    // if login form has been submitted
    if(htmlentities($_GET['action']) == "login") { 
        login();
    }
    elseif(htmlentities($_GET['action']) == "logout") { 
        logout();
    }

    $email = $_SESSION['email'];

    //Time
    $currenttime = time();
    $lowerbound = $currenttime - 86400;
    $lowerboundforfaves = $currenttime - 186400;

?>

<!DOCTYPE HTML>
<head>
	<meta charset = "UTF-8">
	<title> Sell, share and discover brilliant photography </title>
	 <link rel="stylesheet" type="text/css" href="css/style.css"/>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="css/960grid.css"/>
	<link rel="stylesheet" type="text/css" href="css/reset.css"/>   
	<link rel="stylesheet" media='screen and (max-width:640px)' href="css/640.css"/>

	<script src="js/modernizer.js"></script>
	<style type="text/css">
		.show
		{
			display:block !important;
		}
		
		#notify
		{
			width:40px;
			margin: 0 0 0 5px;
			background:#d96f62;
			padding: 5px;
		}
		#notify:hover
		{
			background: rgba(255,255,255,.55);
		}
		#drawer
		{
			width:0px;
			background: url('graphics/noise.png');
			color:#fff;
			white-space: normal;
			font-size: 10px;
			position:fixed;
			height:100%;
			box-shadow: inset 0 0 5px rgba(0,0,0,.25);
			border-radius:0 5px 5px 0;
			margin: 5px 0 0 -5px;
			z-index: 1000;
		}
		.notifications
		{
			font-family:"helvetica neue", helvetica, arial,sans-serif; 
			font-size:20px;
			font-weight: 500;
			color:#fff;
			margin-left: -200px;
			width:200px;

		}
		.test
		{
			height:250px;
			background: rgba(200,200,200,.6);
			box-shadow: 0 0 2px #666;
			margin: 4px 20px 0 0;
		}
		.test2
		{
			height:50px;
			background: rgba(200,200,200,.6);
			box-shadow: 0 0 2px #666;
			margin: 7px 4px !important;
			width:125px;
			float: right;
		}
		.x
		{
			background:none !important;
			color:#222 !important;
			padding: 0 !important;
			box-shadow: 0 0 0 !important;
			margin:10px 5px 0 5px !important;
			border: none !important;
			font-size: 14px;
		}
	</style>
</head>
<body style="overflow-x:hidden; background-color: #fff;">

<?php navbar(); ?>

	<!--Galleries Cover Content-->
	<div class="container_24" style="margin-top:40px;">

		<?php
            
            //Trending Photography
            $trendquery = mysql_query("SELECT * FROM photos WHERE time > $lowerbound ORDER BY score DESC LIMIT 0,13");
            
            echo'<div class="grid_12 push_8 bigText" style="position:relative;top:20px;">Trending</div>';
            
            echo'<div class="grid_12 push_8" style="width:112%;height:320px;overflow:hidden;margin-top:20px;">';
            
            echo'<div style="width:850px;">';
            
            for($iii=0;$iii<12;$iii++) {
                $source = mysql_result($trendquery,$iii,'source');
                $sourceThumb = str_replace("userphotos/","userphotos/medthumbs/", $source);
                $source = "../" . $source;
                list($width, $height) = getimagesize($source);
                $imgratio = $height / $width;
                $heightls = $height / 3.3;
                $widthls = $width / 3.3;
                
                echo'<div style="float:left;height:160px;max-width:180px;padding-left:1px;padding-bottom:1px;overflow:hidden;">
						<img style="height:160px;" src="https://photorankr.com/',$sourceThumb,'" width="',$widthls,'px" />
                    </div>';
            }
            
            echo'</div></div>';
            
            
               //Popular Photography
            $popquery = mysql_query("SELECT * FROM photos WHERE time > $lowerboundforfaves AND faves > 2 ORDER BY RAND() LIMIT 0,12");
            
            echo'<div class="grid_12 push_8 bigText" style="position:relative;top:20px;">Popular</div>';
            
            echo'<div class="grid_12 push_8" style="width:112%;height:320px;overflow:hidden;margin-top:20px;">';
            
            echo'<div style="width:850px;">';
            
            for($iii=0;$iii<12;$iii++) {
                $source = mysql_result($popquery,$iii,'source');
                $sourceThumb = str_replace("userphotos/","userphotos/medthumbs/", $source);
                $source = "../" . $source;
                list($width, $height) = getimagesize($source);
                $imgratio = $height / $width;
                $heightls = $height / 3.3;
                $widthls = $width / 3.3;
                
                echo'<div style="float:left;height:160px;max-width:180px;padding-left:1px;padding-bottom:1px;overflow:hidden;">
						<img style="height:160px;" src="https://photorankr.com/',$sourceThumb,'" width="',$widthls,'px" />
                    </div>';

            }
            
            echo'</div></div>';
            
            
            //Newest Photography
            
			$newestquery = mysql_query("SELECT * FROM photos WHERE time > $lowerbound ORDER BY time DESC LIMIT 0,13");
            
            echo'<div style="clear:both;font-weight:300;color:#333;font-size:30px;padding-top:20px;"><a style="color:#333;" href="newest.php">Fresh</a></div>';
            
            echo'<div class="grid_24" style="width:112%;height:230px;overflow:hidden;margin-top:20px;">';
            
            echo'<div style="width:1450px;">';
            
            for($iii=0;$iii<12;$iii++) {
                $source = mysql_result($newestquery,$iii,'source');
                $sourceThumb = str_replace("userphotos/","userphotos/medthumbs/", $source);
                $source = "../" . $source;
                list($width, $height) = getimagesize($source);
                $imgratio = $height / $width;
                $heightls = $height / 3.3;
                $widthls = $width / 3.3;
                
                echo'<div style="float:left;height:230px;padding-left:1px;padding-bottom:1px;overflow:hidden;">
						<img style="height:240px;" src="https://photorankr.com/',$sourceThumb,'" width="',$widthls,'px" />
                    </div>';

            }
            
            echo'</div></div>';
            
                                    
            //Discover Photography
            $discoverquery = mysql_query("SELECT * FROM photos WHERE faves > 6 and points > 50 ORDER BY RAND() LIMIT 0,12");
            
            echo'<div style="clear:both;float:bottom;margin-top:20px;font-weight:300;color:#333;font-size:30px;padding-top:20px;text-decoration:none;"><a style="color:#333;" href="discover.php">Discover</a></div>';
            
            echo'<div class="grid_24" style="width:112%;height:230px;overflow:hidden;margin-top:20px;">';
            
            echo'<div style="width:1450px;">';
            
            for($iii=0;$iii<12;$iii++) {
                $source = mysql_result($discoverquery,$iii,'source');
                $sourceThumb = str_replace("userphotos/","userphotos/medthumbs/", $source);
                $source = "../" . $source;
                list($width, $height) = getimagesize($source);
                $imgratio = $height / $width;
                $heightls = $height / 3.3;
                $widthls = $width / 3.3;
                
                echo'<div style="float:left;height:230px;padding-left:1px;padding-bottom:1px;overflow:hidden;">
						<img style="height:240px;" src="https://photorankr.com/',$sourceThumb,'" width="',$widthls,'px" />
                    </div>';

            }
            
            echo'</div></div>';
            

		?>

	</div>


</body>	
 <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script>

(function(){
	var drawer = $('#drawer'),
		leftBar = $('#leftBar');

	$.fn.openDrawer = function(speed) {
		return $(this).toggle({
			'width' : 200,
		}, speed || 400 );	
	}
	$.fn.openBar = function(speed) {
		return $(this).animate({
			'width' : 280,
		}, speed || 400 );	
	}
	
	$('#notify').on('click', drawer.toggle( 500, leftBar.openBar), function() {
		drawer.openDrawer(500), leftBar.openBar(500)
	});
	
})();

</script>

</html> 