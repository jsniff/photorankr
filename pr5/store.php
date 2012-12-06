<?php

//connect to the database
require "db_connection.php";
require "functions.php";

//start the session
session_start();

    // if login form has been submitted
    if (htmlentities($_GET['action']) == "login") { 
        login();
    }
    else if(htmlentities($_GET['action']) == "logout") { 
        logout();
    }

    $email = $_SESSION['email'];

    //Personal Information
    $storeinfo = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$email'");
    $firstname = mysql_result($storeinfo,0,'firstname');
    $lastname = mysql_result($storeinfo,0,'lastname');    
    $fullname = $firstname . " " . $lastname;
    $profilepic = mysql_result($storeinfo,0,'profilepic');
    
    //Stats
    $reputation =  mysql_result($storeinfo,0,'reputation');
    
    //Photos
    $userphotosquery = mysql_query("SELECT points,votes,faves,price,sold,width,height,views FROM photos WHERE emailaddress = '$email'");
    $numphotos = mysql_num_rows($userphotosquery);
    for($iii = 0; $iii < $numphotos; $iii++) {
		$points = mysql_result($userphotosquery, $iii, "points");
        $votes = mysql_result($userphotosquery, $iii, "votes");
        $totalfaves = mysql_result($userphotosquery, $iii, "faves");
        $price = mysql_result($userphotosquery, $iii, "price");
        $views = mysql_result($userphotosquery, $iii, "views");
        $width = mysql_result($userphotosquery, $iii, "width");
        $height = mysql_result($userphotosquery, $iii, "height");
        $sold = mysql_result($userphotosquery, $iii, "sold");
        $portfoliopoints += $points;
        $portfoliovotes += $votes;
        $portfoliofaves += $totalfaves;
        $portfolioprice += $price;
        $portfoliowidth += $width;
        $portfolioheight += $height;
        $portfoliosold += $sold;
        $portfolioviews += $views;
        if($width && $height) {
            $numresphotos += 1;
        }
    }
    if($portfoliovotes > 0) {
        $portfolioranking=($portfoliopoints/$portfoliovotes);
        $portfolioranking=number_format($portfolioranking, 2, '.', '');
    }
    elseif($portfoliovotes < 1) {
        $portfolioranking="N/A";
    }
        $avgprice = number_format(($portfolioprice/$numphotos), 2);
        $avgwidth = number_format(($portfoliowidth/$numresphotos), 0);
        $avgheight = number_format(($portfolioheight/$numresphotos), 0);
        $portfolioviews = number_format($portfolioviews, 0);
    //Time
    $currenttime = time();
    
    //View
    $view = mysql_real_escape_string(htmlentities($_GET['view']));

    
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="https://www.w3.org/1999/xhtml">

<head>

 <meta name="Generator" content="EditPlus">
  <meta name="Author" content="PhotoRankr, PhotoRankr.com">
  <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
  <meta name="Description" content="A gallery of the newest photography, photographers, and exhibits on PhotoRankr.">
     <meta name="viewport" content="width=1200" /> 

	 <link rel="stylesheet" type="text/css" href="css/style.css"/>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="css/960grid.css"/>
	<link rel="stylesheet" type="text/css" href="css/reset.css"/> 
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
    
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.wookmark.js"></script>            
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>

    <title><?php echo $fullname; ?>'s Store</title>

<!--GOOGLE ANALYTICS CODE-->
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-28031297-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'https://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>

</head>
<body style="overflow-x:hidden; background-color:rgb(244, 244, 244);min-width:1220px;">

<?php navbar(); ?>

   <!--big container-->
    <div id="container" class="container_24">

        <div class="grid_24" style="width:1180px;">
        
    <?php
        
        if($view == '') {
            $result = mysql_query("SELECT * FROM photos WHERE emailaddress = '$email' ORDER BY id DESC");
        }
        elseif($view == 'faved') {
            $result = mysql_query("SELECT * FROM photos WHERE emailaddress = '$email' ORDER BY faves DESC");
        }
        elseif($view == 'top') {
            $result = mysql_query("SELECT * FROM photos WHERE emailaddress = '$email' ORDER BY (points/votes) DESC");
        }
        elseif($view == 'sold') {
            $result = mysql_query("SELECT * FROM photos WHERE emailaddress = '$email' AND sold = 1 ORDER BY (points/votes) DESC");
        }
            
        echo'<div class="grid_18" style="margin-top:30px;margin-left:-35px;">';
        
        echo'<div class="commentTitle" style="margin-top:-40px;margin-left:0px;z-index:100;font-size:26px;text-align:center;font-weight:300;padding:6px 4px 10px 4px;margin-bottom:43px;width:775px;">Your Personal Store</div>';
         
        echo'<div id="thepics" style="width:800px;">
             <div id="main">';
            
            for($iii = 0; $iii < 12; $iii++) {
                $source = mysql_result($result,$iii,'source');
                $sourceThumb = str_replace("userphotos/","userphotos/medthumbs/", $source);
                $source = "../" . $source;
                $price = mysql_result($result,$iii,'price');
                $views = mysql_result($result, $iii, "views");
                $id = mysql_result($result, $iii, "id");
                list($width, $height) = getimagesize($source);
                $imgratio = $height / $width;
                $heightls = $height / 2.2;
                $widthls = $width / 2.2;
                
                echo'<div class="fPic" id="',$views,'" style="float:left;height:240px;width:260px;padding-left:1px;padding-bottom:1px;overflow:hidden;">
						<img style="height:260px;min-width:260px;" src="https://photorankr.com/',$sourceThumb,'" width="',$widthls,'px" />
                    
                    <div style="height:30px;background-color:rgba(34,34,34,.8);width:260px;position:relative;top:-50px;padding:8px;">
                    <span style="color:white;font-size:14px;font-weight:300;">$',$price,'</span>
                    </div>    
                    
                    </div>';
            }
            
        echo'</div>
             </div>

    <!--AJAX CODE HERE-->
   <div class="grid_9 push_5" style="padding-top:50px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:18px; font-weight:300;">Loading More Photos&hellip;</div>
   </div>';


echo'<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMorePics").show();
				$.ajax({
					url: "loadMoreMarketPics.php?lastPicture=" + $(".fPic:last").attr("id")+"&c=',$cat,'"+"&views=',$views,'"+"&sw=',$searchword,'",
					success: function(html) {
						if(html) {
							$("#thepics").append(html);
							$("div#loadMorePics").hide();
						}
					}
				});
				last = $(".fPic:last").attr("id");
			}
		}
	});
</script>

</div>';


//Right Sidebar

echo'<div class="grid_7 filled rounded shadow" style="position:fixed;margin-left:100px;margin-top:30px;">
        <div class="cartText">Your Photos</div>
            <ul>
                <li id="stattitle"># Photos: <span id="stat">',$numphotos,'</span></li>
                <li id="stattitle">Avg. Photo Price: <span id="stat">$',$avgprice,'</span></li>
                <li id="stattitle">Avg. Portfolio Score: <span id="stat">',$portfolioranking,'</span></li>
                <li id="stattitle">Photos Sold: <span id="stat">',$portfoliosold,'</span></li>
                <li id="stattitle">Photo Views: <span id="stat">',$portfolioviews,'</span></li>

                <li id="stattitle">Avg. Resolution: <span id="stat">',$avgwidth,' X ',$avgheight,'</span></li>
        </div>';
     
     //Search the Store
        echo'<div class="grid_7" style="position:fixed;margin-left:100px;margin-top:290px;>
            <form method="GET">
                <input id="searchStore" name="searchword" placeholder="Search your store&hellip;" type="text" />
            </form>
        </div>';
        
    //Store Filters
     echo'<div class="grid_7" style="position:fixed;margin-left:100px;margin-top:330px;">';
        if($view == '') {
            echo'<a style="text-decoration:none;" href="store.php"><div class="rectangle"><div style="padding-top:7px;text-align:center;">Newest</div><div class="arrow-left" style="float:right;margin-right:289px;margin-top:-22px;"></div></div></a>';
        }
        
        else {
             echo'<a style="text-decoration:none;" href="store.php"><div class="rectangle"><div style="padding-top:7px;text-align:center;">Newest</div></div></a>';
        }
        
        if($view == 'faved') {
            echo'<a style="text-decoration:none;" href="store.php?view=faved"><div class="rectangle"><div style="padding-top:7px;text-align:center;">Most Favorited</div><div class="arrow-left" style="float:right;margin-right:289px;margin-top:-22px;"></div></div></a>';
        }
        
        else {
             echo'<a style="text-decoration:none;" href="store.php?view=faved"><div class="rectangle"><div style="padding-top:7px;text-align:center;">Most Favorited</div></div></a>';
        }
        
        if($view == 'top') {
            echo'<a style="text-decoration:none;" href="store.php?view=top"><div class="rectangle"><div style="padding-top:7px;text-align:center;">Top Ranked</div><div class="arrow-left" style="float:right;margin-right:289px;margin-top:-22px;"></div></div></a>';
        }
        
        else {
             echo'<a style="text-decoration:none;" href="store.php?view=top"><div class="rectangle"><div style="padding-top:7px;text-align:center;">Top Ranked</div></div></a>';
        }
        
        if($view == 'sold') {
            echo'<a style="text-decoration:none;" href="store.php?view=sold"><div class="rectangle"><div style="padding-top:7px;text-align:center;">Sold</div><div class="arrow-left" style="float:right;margin-right:289px;margin-top:-22px;"></div></div></a>';
        }
        
        else {
             echo'<a style="text-decoration:none;" href="store.php?view=sold"><div class="rectangle"><div style="padding-top:7px;text-align:center;">Sold</div></div></a>';
        }
        
    echo'</div>';

                        
?>

        </div>

    </div><!--end of container-->

</body>
</html>