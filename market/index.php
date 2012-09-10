<?php

//connect to the database
require "db_connection.php";
require "functionscampaigns3.php"; 


//Login from front page
session_start();

if ($_GET['action'] == "log_in") { // if login form has been submitted

	/// makes sure they filled it in
        if(!htmlentities($_POST['emailaddress']) | !htmlentities($_POST['password'])) {
            die('You did not fill in a required field.');
        }

        $check = mysql_query("SELECT * FROM campaignusers WHERE repemail = '".mysql_real_escape_string($_POST['emailaddress'])."'")or die(mysql_error());
        //Gives error if user dosen't exist

        $check2 = mysql_num_rows($check);
    
        if ($check2 == 0) {
            die('That user does not exist in our database. <a href="campaignnewuser.php">Click Here to Register</a> or <a href="lostpassword.php">here to recover a forgotten password</a>.');
        }

        $info = mysql_fetch_array($check);

        if(mysql_real_escape_string($_POST['password']) == mysql_real_escape_string($info['password'])){
            //then redirect them to the same page as signed in and set loggedin to 1
            $_SESSION['loggedin'] = 2;
            $_SESSION['repemail'] = mysql_real_escape_string($_POST['emailaddress']);
        }
        //gives error if the password is wrong
        else if (mysql_real_escape_string($_POST['password']) != mysql_real_escape_string($info['password'])) {
            die('Incorrect password, please try again. <a href="lostpassword.php"> Lost your password?</a>');   
        }

    }


//start the session
session_start();

    // if login form has been submitted
    if (htmlentities($_GET['action']) == "login") { 
        login();
    }
    else if(htmlentities($_GET['action']) == "logout") { 
        logout();
    }
    
    $repemail = $_SESSION['repemail'];

    //Grab the view
    $category = htmlentities($_GET['c']);     
     
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>PhotoRankr Market - Stock Photos Done Differently</title>

    <link rel="stylesheet" href="css/bootstrapNew.css" type="text/css" />
    <link rel="stylesheet" href="css/reset.css" type="text/css" />
    <link rel="stylesheet" href="css/text.css" type="text/css" />
    <link rel="stylesheet" href="css/960_24.css" type="text/css" />
    <link rel="stylesheet" href="css/index.css" type="text/css"/> 
    <link rel="stylesheet" type="text/css" href="css/all.css"/>

    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
    <script src="http://twitter.github.com/bootstrap/1.4.0/bootstrap-twipsy.js"></script>
    <script src="http://twitter.github.com/bootstrap/1.4.0/bootstrap-popover.js"></script>
    <script src="bootstrap-dropdown.js" type="text/javascript"></script>
    <script src="bootstrap-collapse.js" type="text/javascript"></script>
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>


  	<!--Navbar Dropdowns-->
  	<script type="text/javascript">
 	 $(function() {
  	// Setup drop down menu
  	$('.dropdown-toggle').dropdown();
  	// Fix input element click problem
  	$('.dropdown input, .dropdown label').click(function(e) {
    e.stopPropagation();
 		 });
	});
    </script>

	<!--GOOGLE ANALYTICS CODE-->
	<script type="text/javascript">
  	var _gaq = _gaq || [];
  	_gaq.push(['_setAccount', 'UA-28031297-1']);
  	_gaq.push(['_trackPageview']);
  	(function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
 	 })();
	</script>


</head>
<body style="overflow-x:hidden; background-color:#fff;">

<?php navbarsweet(); ?>


	
<!--CONTAINER-->
<div class="container_24">    


<!--VIEWING BOX-->
<div class="grid_24" style="float:left;margin-left:-100px;margin-top:70px;width:1150px;">

<div class="grid_5 rounded" style="left:-30px;"><div style="background-color:#eeeff3;font-size:15px;text-align:center;font-family:helvetica;font-weight:400;">Advanced Search</div><br />
    
<form action="" method="GET">

    <div class="control-group" style="float:left;">
          <!-- Select Basic -->
          <label class="control-label"></label>
          <div class="controls">
            <select name="rank"  class="input-large" style="width:150px;height:30px;margin-left:15px;margin-top:-3px;">
              <option value="">Rank</option>
              <option value="hightolow">High - Low</option>
              <option value="lowtohigh">Low - High</option>
            </select>
        </div>
    </div>
    
<div class="control-group" style="float:left;">
          <!-- Select Basic -->
          <label class="control-label"></label>
          <div class="controls">
            <select name="category"  onchange="submitCategory(this)" class="input-large" style="width:150px;height:30px;margin-left:15px;margin-top:-3px;">
            <option value="">Category</option>
            <option value="Advertising">Advertising</option>
            <option value="Aerial">Aerial</option>
            <option value="Animal">Animal</option>
            <option value="Architecture">Architecture</option>
            <option value="Astro">Astro</option>
            <option value="Aura">Aura</option>
            <option value="Automotive">Automotive</option>
            <option value="Botanical">Botanical</option>
            <option value="Candid">Candid</option>
            <option value="Commercial">Commercial</option>
            <option value="Corporate">Corporate</option>
            <option value="Documentary">Documentary</option>
            <option value="Fashion">Fashion</option>
            <option value="Fine Art">Fine Art</option>
            <option value="Food">Food</option>
            <option value="Historical">Historical</option>
            <option value="Industrial">Industrial</option>
            <option value="Musical">Musical</option>
            <option value="Nature">Nature</option>
            <option value="News">News</option>
            <option value="Night">Night</option>
            <option value="People">People</option>
            <option value="Scenic">Scenic</option>
            <option value="Sports">Sports</option>
            <option value="Still Life">Still Life</option>
            <option value="Transportation">Transportation</option>
            <option value="Urban">Urban</option>
            <option value="War">War</option>
            </select>
        </div>
    </div>

    <div class="control-group" style="float:left;">
          <!-- Select Basic -->
          <label class="control-label"></label>
          <div class="controls">
            <select name="style"  onchange="submitStyle(this)" class="input-large" style="width:150px;height:30px;margin-left:15px;margin-top:-3px;">
            <option value="">Style</option>
            <option value="B&W">Black and White</option>
            <option value="Cityscape">Cityscape</option>
            <option value="Fisheye">Fisheye</option>
            <option value="HDR">HDR</option>
            <option value="Illustration">Illustration</option>
            <option value="InfraredUV">Infrared/UV</option>
            <option value="Landscape">Landscape</option>
            <option value="Long Exposure">Long Exposure</option>
            <option value="Macro">Macro</option>
            <option value="Miniature">Miniature</option>
            <option value="Monochrome">Monochrome</option>
            <option value="Motion Blur">Motion Blur</option>
            <option value="Night">Night</option>
            <option value="Panorama">Panorama</option>
            <option value="Photojournalism">Photojournalism</option>
            <option value="Portrait">Portrait</option>
            <option value="Stereoscopic">Stereoscopic</option>
            <option value="Time Lapse">Time Lapse</option>
            </select>
        </div>
    </div>

<hr>

 <!--SLIDERS-->    
	
	<style>
	#demo-frame > div.demo { padding: 10px !important; };
	</style>
					<input id= "values" type="hidden" name="c" value="";>
					<input id= "values2" type="hidden" name="c2" value="";>

					<input id= "reputationvalues" type="hidden" name="rc" value="";>
					<input id= "reputationvalues2" type="hidden" name="rc2" value="";>

                    <input id= "downloadvalues" type="hidden" name="dc" value="";>
					<input id= "downloadvalues2" type="hidden" name="dc2" value="";>


	<script>

	$(function() {
		$( "#slider-range" ).slider({

			range: true,
			min: 0,
			max: 200,
			values: [0, 150 ],
			slide: function( event, ui ) {
				$( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
						$( "#values" ).val(ui.values[ 0 ]);
						$( "#values2" ).val(ui.values[ 1 ]);

			}
		});

		$( "#amount" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) +
			" - $" + $( "#slider-range" ).slider( "values", 1 ) );

		$( "#column" ).val($( "#slider-range" ).slider( "values", 0 ));

				$( "#column2" ).val($( "#slider-range" ).slider( "values", 1 ));



	});
	</script>

<div class="demo">

<div style="padding:20px;margin-left:-30px;">
	<label for="amount" style="text-align:center;margin-left:40px;font-size:15px;font-family:helvetica neue,arial,lucida grande;padding:10px;margin-top:-35px;">Price range: 
    
    <a style="text-decoration:none;" href="#" id="pricepopover" rel="popover" data-content="Adjust the slider on the right to return photos that are inside that price range." data-original-title="What is price range?">(?)</a>
    <script>  
    $(function ()  
    { $("#pricepopover").popover();  
    });  
    </script>
    
    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</label>  
	<input id="amount" style="margin-left:15px;text-align:center;border:0; color:#333; padding:5px; background-color:white; width: 150px; font-size: 15px;position:relative;top:-20px;" />     
<div id="slider-range" style="width:150px;margin-left:30px;margin-top:-10px;"></div>
</div>
<br />
</div><!-- End demo -->

<script>

	$(function() {
		$( "#slider-range2" ).slider({

			range: true,
			min: 0,
			max: 100,
			values: [ 0, 100 ],
			slide: function( event, ui ) {
				$( "#amount2" ).val(ui.values[ 0 ] + " - " + ui.values[ 1 ] );
						$( "#reputationvalues" ).val(ui.values[ 0 ]);
						$( "#reputationvalues2" ).val(ui.values[ 1 ]);

			}
		});

		$( "#amount2" ).val($( "#slider-range2" ).slider( "values", 0 ) +
			" - " + $( "#slider-range2" ).slider( "values", 1 ) );

	
		$( "#reputationcolumn" ).val($( "#slider-range2" ).slider( "values", 0 ));

				$( "#reputationcolumn2" ).val($( "#slider-range2" ).slider( "values", 1 ));



	});
	</script>

<div class="demo2">

<div style="padding:20px;margin-left:-30px">
	<label for="amount2" style="text-align:center;margin-left:16px;font-size:15px;font-family:helvetica neue,arial,lucida grande;padding:10px;margin-top:-45px;">Rep. range: 
    
    <a style="text-decoration:none;" href="#" id="reppopover" rel="popover" data-content="Adjust the slider on the right to return photos that are from photographers inside that reputation range. Photographers with a higher reputation generally submit higher quality images." data-original-title="What is reputation range?">(?)</a>
    <script>  
    $(function ()  
    { $("#reppopover").popover();  
    });  
    </script>
    
    </label>  
	<input id="amount2" style="text-align:center;margin-top:-25px;margin-left:15px;border:0; color:#333; padding:5px; background-color:white;position:relative;top:10px;width: 150px;font-size: 15px;" />     
<div id="slider-range2" style="width:150px;margin-left:30px;margin-top:10px;"></div>
</div>
<br />

</div><!-- End demo -->


<script>

	$(function() {
		$( "#slider-range4" ).slider({

			range: true,
			min: 0,
			max: 500,
			values: [ 0, 50],
			slide: function( event, ui ) {
				$( "#amount4" ).val(ui.values[ 0 ] + " - " + ui.values[ 1 ] );
						$( "#downloadvalues" ).val(ui.values[ 0 ]);
						$( "#downloadvalues2" ).val(ui.values[ 1 ]);

			}
		});

		$( "#amount4" ).val($( "#slider-range4" ).slider( "values", 0 ) +
			" - " + $( "#slider-range4" ).slider( "values", 1 ) );

	
		$( "#downloadcolumn" ).val($( "#slider-range4" ).slider( "values", 0 ));

				$( "#downloadcolumn2" ).val($( "#slider-range4" ).slider( "values", 1 ));



	});
	</script>

<div class="demo4">

<div style="padding:20px;margin-left:-30px">
	<label for="amount4" style="text-align:center;margin-left:15px;font-size:15px;font-family:helvetica neue,arial,lucida grande;padding:10px;margin-top:-45px;">Download range: 
    
     <a style="text-decoration:none;" href="#" id="downloadpopover" rel="popover" data-content="Adjust the slider on the right to return photos that have been downloaded that number of times." data-original-title="What is download range?">(?)</a>
    <script>  
    $(function ()  
    { $("#downloadpopover").popover();  
    });  
    </script>
    
    </label>  
	<input id="amount4" style="text-align:center;margin-left:15px;margin-top:-10px;border:0; color:#333; padding:5px; background-color:white;width: 150px; font-size: 15px;" /> 
<div id="slider-range4" style="width:150px;margin-left:30px;margin-top:10px;"></div>
</div>
<br />
</div><!-- End demo -->

<!--END SLIDERS-->


<hr>
    
        <div class="control-group" style="float:left;">
          <!-- Select Basic -->
          <label class="control-label"></label>
          <div class="controls">
            <select name="resolution"  onchange="submitRank(this)" class="input-large" style="width:150px;height:30px;margin-left:15px;margin-top:-3px;">
              <option value=''>Resolution</option>
              <option value="3050" >3000 - 5000</option>
              <option value="1530" >1500 - 3000</option>
              <option value="15" > < 1500 </option>
            </select>
        </div>
    </div>
    
        <div class="control-group" style="float:left;">
          <!-- Select Basic -->
          <label class="control-label"></label>
          <div class="controls">
            <select name="orientation"  onchange="submitRank(this)" class="input-large" style="width:150px;height:30px;margin-left:15px;margin-top:-3px;">
              <option value=''>Orientation</option>
              <option value="vertical" >Vertical</option>
              <option value="horizontal" >Horizontal</option>
            </select>
        </div>
    </div>

<hr>


<button type="submit" class="btn btn-success" style="width:170px;padding:8px;margin-left:10px;margin-top:-10px;margin-bottom:10px;">Search</button>
</form>

</div><!--end left sidebar-->






<div class="grid_23 roundedright" style="background-color:#eeeff3;height:45px;position;relative;top:80px;width:925px;">
<a style="text-decoration:none;color:black;" href="?c=trending"><div class="clicked" style="width:150px;height:45px;border-right:1px solid #ccc;float:left;<?php if($category == 'trending') {echo'background-color:#bbb;color:white;';} ?>"><div style="font-size:17px;font-family:helvetica;font-weight:100;margin-top:10px;text-align:center;">Trending</div></div></a>

<a style="text-decoration:none;color:black;" href="?c=pop"><div class="clicked" style="width:150px;height:45px;border-right:1px solid #ccc;float:left;<?php if($category == 'pop') {echo'background-color:#bbb;color:white;';} ?>"><div style="font-size:17px;font-family:helvetica;font-weight:100;margin-top:10px;text-align:center;">Popular</div></div></a>

<a style="text-decoration:none;color:black;" href="?c=newest"><div class="clicked" style="width:150px;height:45px;border-right:1px solid #ccc;float:left;<?php if($category == 'newest') {echo'background-color:#bbb;color:white;';} ?>"><div style="font-size:17px;font-family:helvetica;font-weight:100;margin-top:10px;text-align:center;">Newest</div></div></a>

<a style="text-decoration:none;color:black;" href="?c=top"><div class="clicked" style="width:150px;height:45px;border-right:1px solid #ccc;float:left;<?php if($category == 'top') {echo'background-color:#bbb;color:white;';} ?>"><div style="font-size:17px;font-family:helvetica;font-weight:100;margin-top:10px;text-align:center;">Top Ranked</div></div></a>

<a style="text-decoration:none;color:black;" href="?c=exhibits"><div class="clicked" style="width:150px;height:45px;border-right:1px solid #ccc;float:left;<?php if($category == 'exhibits') {echo'background-color:#bbb;color:white;';} ?>"><div style="font-size:17px;font-family:helvetica;font-weight:100;margin-top:10px;text-align:center;">Top Exhibits</div></div></a>

<a style="text-decoration:none;color:black;" href="?c=personal"><div class="clicked" style="width:150px;height:45px;float:left;<?php if($category == 'personal') {echo'background-color:#bbb;color:white;';} ?>"><div style="font-size:17px;font-family:helvetica;font-weight:100;margin-top:10px;text-align:center;">Personal</div></div></a>


<?php

if($category != 'exhibits') {

$sort = htmlentities($_GET['sort']);
if($category == 'newest') {
$query = mysql_query("SELECT * FROM photos WHERE price != 'Not For Sale' ORDER BY id DESC LIMIT 0,60");
$numresults = mysql_num_rows($query);
}

elseif($category == 'top') {
$query = mysql_query("SELECT * FROM photos WHERE price != ('Not For Sale') ORDER BY points DESC LIMIT 0,60");
$numresults = mysql_num_rows($query);
}

elseif($category == 'trending' || $category == '') {
$query = mysql_query("SELECT * FROM photos WHERE price != ('Not For Sale') ORDER BY score DESC LIMIT 0,60");
$numresults = mysql_num_rows($query);
}

elseif($category == 'pop') {
$query = mysql_query("SELECT * FROM photos WHERE views > 120 AND price != ('Not For Sale') ORDER BY faves DESC LIMIT 0,60");
$numresults = mysql_num_rows($query);
}

elseif($category == 'deal') {
$deal = '10.00';
$twoweeksago = time() - 1209600;
$query = mysql_query("SELECT * FROM photos WHERE price < '$deal' AND time > $twoweeksago ORDER BY points DESC LIMIT 0,60");
$numresults = mysql_num_rows($query);
}

if($category == 'personal') {

        if($_SESSION['loggedin'] != 2) {
    
            echo'<div style="font-size:16px;text-align:center;margin-top:100px;font-family:helvetica;font-weight:100;">Follow photographer\'s work and personalize your photography feed by logging in above or <a href="">registering</a> for free today.</div>';
            
        }
      
    elseif($_SESSION['loggedin'] == 2) {
    
        echo'<br /><br /><br /><div style="width:910px;text-align:center;font-size:14px;font-weight:200;"><a class="green" style="text-decoration:none;color:#000;';if($option == '') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'href="index.php?c=personal">Following</a> | <a class="green" style="text-decoration:none;color:#000;';if($option == 'top') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'href="index.php?c=personal&option=pref">Preferred Work</a></div><br />';
        
        if($option == ''){
    
        $followcheckquery = mysql_query("SELECT following FROM campaignusers WHERE repemail = '$repemail'");
        $followcheck = mysql_result($followcheckquery,0,'following');
        $individuals = explode(" ", $followcheck);
        $numinds = count($individuals);
        
        for($iii=0; $iii < $numinds; $iii++) {
            
            $actual = mysql_query("SELECT user_id FROM userinfo WHERE user_id = '$individuals[$iii]'");
            $actualcheck = mysql_result($actual,0,'user_id');
        
                if(!$actualcheck) {
                    continue;
                }
            
            elseif($iii < ($numinds-2)) {
                $finallist = $finallist . $individuals[$iii] . ",";
            }
            
            else {
                $finallist .= $individuals[$iii];
            }
        }   
                    
$query = mysql_query("SELECT * FROM photos JOIN userinfo ON photos.emailaddress = userinfo.emailaddress WHERE userinfo.user_id IN ($finallist) ORDER BY id DESC LIMIT 0,60");
$numresults = mysql_num_rows($query);
        
        } //end of if option == ''
        
        elseif($option == 'pref') {
        
            
        
        } //end option == 'pref'
    
    } //end if logged in 

}

$searchterm = htmlentities($_GET['searchterm']);
if($c == '' && $searchterm != '') {
$query = mysql_query("SELECT * FROM photos WHERE concat(caption, tag, camera, tag1, tag2, tag3, tag4, singlecategorytags, singlestyletags, location, country, about, sets, maintags, settags) LIKE '%$searchterm%' AND price != ('Not For Sale') ORDER BY (views) DESC LIMIT 0,60");
$numresults = mysql_num_rows($query);

if($numresults < 1) {
echo'<div style="font-size:16px;text-align:center;margin-top:50px;font-family:helvetica;font-weight:100;">No results found for "',$searchterm,'."</div>';
}
}

$resolution = htmlentities($_GET['resolution']);
$orientation = htmlentities($_GET['orientation']);
$license = htmlentities($_GET['license']);
$keyword = htmlentities($_GET['keyword']);
$cat = $_GET['category'];
$rank = $_GET['rank'];
$price = $_GET['price'];
$style = $_GET['style'];
$lowerprice = htmlentities($_GET['c']);
    if(!$lowerprice) {
        $lowerprice = 0;
    }
$higherprice = htmlentities($_GET['c2']);
$lowerrep = htmlentities($_GET['rc']);
$higherrep = htmlentities($_GET['rc2']);
$lowerdown = htmlentities($_GET['dc']);
    if(!$lowerdown) {
        $lowerdown = 0;
    }
$higherdown = htmlentities($_GET['dc2']);
$searchterm = htmlentities($_GET['searchterm']);
    if($resolution || $orientation || $license || $keyword || $cat || $higherprice || $lowerrep || $higherrep || $lowerdown || $higherdown && !$category || $searchterm) {
                
                if($searchterm) {
$query ="SELECT * FROM photos JOIN userinfo ON photos.emailaddress = userinfo.emailaddress WHERE concat(caption, tag, photos.camera, tag1, tag2, tag3, tag4, singlecategorytags, singlestyletags, photos.location, country, about, photos.sets, maintags, settags) LIKE '%$searchterm%' AND price != ('Not For Sale')";

                }

                else{
                $query = "SELECT * FROM photos JOIN userinfo ON photos.emailaddress = userinfo.emailaddress WHERE";
}



                if(!empty($cat)) {
                $query .= " AND singlecategorytags LIKE '%$cat%'";
                }
                
                if(!empty($style)) {
                $query .= " AND singlestyletags LIKE '%$style%'";
                }

                if(!empty($higherprice) && empty($cat) && empty($style)) {
                $query .= " price < $higherprice";
                }
                elseif(!empty($higherprice)) {
                $query .= " AND price < $higherprice";
                }
                
                if(!empty($lowerprice)) {
                $query .= " AND price > $lowerprice";
                }
                
                if(!empty($higherdown)) {
                $query .= " AND sold < $higherdown";
                }
                
                if(!empty($lowerdown)) {
                $query .= " AND sold > $lowerdown";
                }
                
                if(!empty($higherrep)) {
                $query .= " AND userinfo.reputation < $higherrep";
                }
                
                if(!empty($lowerrep)) {
                $query .= " AND userinfo.reputation > $lowerrep";
                }
      
                if(!empty($keyword)) {
                $query .= " AND concat(caption,tag1,tag2,tag3,tag4,singlestyletags,singlecategorytags) LIKE '%$keyword%'";
                }
                
                if(!empty($resolution)) {
                
                    if($resolution == '3050') {
                        $query .= " AND (width > 3000 OR height > 3000)";
                    }
                    elseif($resolution == '1530') {
                        $query .= " AND (width > 1500 OR height > 1500)";
                    }
                    elseif($resolution == '15') {
                        $query .= " AND (width < 1500 OR height < 1500)";
                    }
                } 
                
                if(!empty($orientation)) {
                
                    if($orientation == 'vertical') {
                        $query .= " AND (width < height)";
                    }
                    elseif($orientation == 'horizontal') {
                        $query .= " AND (width > height)";
                    }
                }
                                
                if(!$rank) {
                $query .= " ORDER BY (views)";
                }
                
                elseif($rank) {
                
                    if($rank == 'hightolow') {
                        $query .= " ORDER BY (points/votes) DESC";
                    }
                    elseif($rank == 'lowtohigh') {
                        $query .= " ORDER BY (points/votes) ASC";
                    }
                }
                 $query .= " LIMIT 0,60";

                            
                $query = mysql_query($query);
                                        
                $numresults = mysql_num_rows($query);
                                
            if($numresults < 1) {
                echo'<div style="font-size:16px;text-align:center;margin-top:100px;font-family:helvetica;font-weight:100;">No results found. Please try a different search above or <a href="createcampaign.php">begin a campaign</a> to crowdsource your photo request.</div>';
            } 
            elseif($numresults > 0) { 
                echo'<br /><br /><br /><div style="font-size:16px;text-align:center;font-family:helvetica;font-weight:100;width:910px;">',$numresults,' photos found</div></br >';
            }
            

} //end of advanced search block


//AJAX Container
echo'<div id="thepics" style="padding-left:20px;">';

for($iii=0; $iii < $numresults; $iii++) {
    $imagebig[$iii] = mysql_result($query,$iii,'source');
    $imagebig[$iii] = str_replace("userphotos/", "$_SERVER[DOCUMENT_ROOT]/userphotos/", $imagebig[$iii]);
    $imagebig2[$iii] = str_replace("$_SERVER[DOCUMENT_ROOT]/userphotos/", "http://photorankr.com/userphotos/medthumbs/", $imagebig[$iii]);
    $price = mysql_result($query,$iii,'price');
    if($price == 'Not For Sale') {
    $price = 'Not For Sale';
    }
    elseif($price == '0.00') {
    $price = 'Free';
    }
    else {
    $price = '$' . $price;
    }
    $title = mysql_result($query,$iii,'caption');
    $imageid = mysql_result($query,$iii,'id');
    $owner = mysql_result($query,$iii,'emailaddress');
    $sold = mysql_result($query,$iii,'sold');
    $points = mysql_result($query,$iii,'points');
    $votes = mysql_result($query,$iii,'votes');
    $rating = ($points/$votes);
    $score = mysql_result($query,$iii,'score');
    $views = mysql_result($query,$iii,'views');
    $license = mysql_result($query,$iii,'license');
    if($license == '') {
    $license = 'Royalty Free';
    }
    $rating = number_format($rating,2);
    $rating2 = number_format($rating,1);
    $fullres = mysql_result($query,$iii,'height')." X ".mysql_result($query,$iii,'width');

    $ownerquery = mysql_query("SELECT firstname,lastname,profilepic FROM userinfo WHERE emailaddress = '$owner'");
    $ownerpic = mysql_result($ownerquery,0,'profilepic');
    $ownerpic = 'http://photorankr.com/' . $ownerpic;
    $firstname = mysql_result($ownerquery,0,'firstname');
    $lastname = mysql_result($ownerquery,0,'lastname');
    $fullname = $firstname . " " . $lastname;
    
    list($height,$width) = getimagesize($imagebig[$iii]);
    $widthnew = $width / 8.25;
    $heightnew = $height / 8.25;
    $widthmed = $width / 5.5;
    $heightmed = $height / 5.5;

    echo'<div class="fPic"  id="',$imageid,'" style="width:180px;height:200px;overflow:hidden;float:left;border-top:1px solid #ccc;"><br /><a href="fullsize2.php?imageid=',$imageid,'"><div style="width:',$heightnew,'px;"><img id="popover',$iii,'" rel="popover" data-content="<span style=font-family:helvetica;font-weight:200;font-size:13px;>Rating: ',$rating,'<br />Full Resolution: ',$fullres,'<br />Photographer: ',$fullname,'</span><br /><br /><img src=',$imagebig2[$iii],' height=',$widthmed,'px width=',$heightmed,'px  />" data-original-title="',$title,'" onmousedown="return false" oncontextmenu="return false;" class="phototitletest" style="margin-top:20px;clear:right;float:bottom;margin:auto;" src="',$imagebig2[$iii],'" height="',$widthnew,'px" width="',$heightnew,'px" /></a>
    <div style="text-align:center;font-size:14px;clear:both;padding-top:10px;">',$price,'&nbsp;|&nbsp;',$rating2,'
     </div></div>';
     ?>
     
    <script>  
    $(function ()  
    { $("#popover<?php echo $iii; ?>").popover();  
    });  
    </script>
    
    <?php
    echo'
    </div>';
}

echo'</div>';

echo'
<!--AJAX CODE HERE-->
   <div style="padding-top:50px;text-align:center;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:18px;padding-bottom:20px;"><b>Loading More Results&hellip;</b></div>
   </div>';


echo '<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMorePics").show();
				$.ajax({
					url: "loadMoreMarket.php?lastPicture=" + $(".fPic:last").attr("id")+"&score=',$score,'"+"&views=',$views,'"+"&search=',$searchterm,'"+"&points=',$points,'",
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
</script>';

} //end category != exhibits
 

if($category == 'exhibits') {

for($iii=0; $count < 40; $iii++) {
    $exquery = "SELECT * FROM sets ORDER BY avgscore DESC";
    $exqueryrun = mysql_query($exquery);
    $owner = mysql_result($exqueryrun, $iii-1, "owner");

$exinfo = "SELECT * FROM userinfo WHERE emailaddress = '$owner'"; 
$exinforun = mysql_query($exinfo);
$firstname = mysql_result($exinforun, 0, "firstname");
$lastname = mysql_result($exinforun, 0, "lastname");
$fullname = $firstname . " " . $lastname;
$fullname = ucwords($fullname);
$user_id = mysql_result($exinforun, 0, "user_id");
$exhibit_id = mysql_result($exqueryrun, $iii-1, "id");
$captionorig = mysql_result($exqueryrun, $iii-1, "title");
$caption = (strlen($captionorig) > 28) ? substr($captionorig,0,28). " &#8230;" : $captionorig;
$coverpic = mysql_result($exqueryrun, $iii-1, "cover");
if($coverpic == '') {
    continue;
    }
   $count += 1; 
  
    $imagebig = str_replace("userphotos/", "$_SERVER[DOCUMENT_ROOT]/userphotos/", $coverpic);
    $imagebig2 = str_replace("$_SERVER[DOCUMENT_ROOT]/userphotos/", "http://photorankr.com/userphotos/medthumbs/", $imagebig);
    list($height,$width) = getimagesize($imagebig);
    $widthnew = $width / 6.5;
    $heightnew = $height / 6.5;
    $widthmed = $width / 5;
    $heightmed = $height / 5;
    
		echo'<div style="width:230px;height:250px;overflow:hidden;float:left;">
			<a href="viewprofile.php?u=',$user_id,'&view=exhibits&set=',$exhibit_id,'">
            <img class="phototitle" onmousedown="return false" oncontextmenu="return false;" 
            
            id="popover',$count,'" rel="popover" data-content="<p>Photographer: ',$fullname,'</p>';
            
            
            $exphotos = mysql_query("SELECT * FROM photos WHERE set_id = '$exhibit_id'");
            $numphotos = mysql_num_rows($exphotos);
            echo'<div style=width:350px;height:auto;>';
            for($ii=0;$ii < 9 && $ii < $numphotos;$ii++){
                $thumbsource = mysql_result($exphotos,$ii,'source');
                $thumbsource = str_replace("userphotos/", "http://photorankr.com/userphotos/medthumbs/", $thumbsource);
                echo'            
                <img style=float:left;margin:5px; src=',$thumbsource,' height=100px width=100px />';
            }
            
            echo'
            </div>
            <div style=clear:both;>&nbsp;&nbsp;</div>" data-original-title="',$captionorig,'" onmousedown="return false" oncontextmenu="return false;" class="phototitle" style="margin-right:30px;margin-top:20px;clear:right;"
            
            src="',$imagebig2,'" height="',$widthnew,'px" width="',$heightnew,'px" /></a><br /><div style="text-align:center;font-size:14px;clear:both;">"',$caption,'"</div>';
            ?>
            
             <script>  
             $(function ()  
                { $("#popover<?php echo $count; ?>").popover();  
                });  
             </script>
        
        <?php
            echo'
            </div>'; 
        
    } //end of for loop

} //end exhibit view


?>

</div><!--end 18 grid-->



</div><!--end 24 grid-->
<!--Javascripts-->
<script type="text/javascript" src="js/mocha.js"></script>    
<script src="js/bootstrap.js" type="text/javascript"></script>
<script src="js/bootstrap-dropdown.js" type="text/javascript"></script>
<script src="js/bootstrap-collapse.js" type="text/javascript"></script>
<!--HIDDEN UPLOAD INFORMATION SCRIPT-->
<script type="text/javascript">   
$(document).ready(function(){
  $(".flip2").click(function(){
    $(".panel2").slideToggle("slow");
  });
});
</script>


</div><!--end container-->



</body>
</html>


<!--Auto DropDown
        <script>
        function submitMyForm(sel) {
        // alert(sel.options[sel.selectedIndex].value);
        	sel.form.submit();
        }
        </script>
        <form id="Form1" action="http://www.google.com/">
        <select onchange="submitMyForm(this)">
            <option>Choose...</option>
            <option>Red</option>
            <option>Blue</option>
        </select>
        <noscript>
            <input type="submit" id="Submit1" />
        </noscript>
        </form>-->
