<?php

//connect to the database
require "db_connection.php";
require "functionscampaigns3.php"; 

//start the session
session_start();

    // if login form has been submitted
    if (htmlentities($_GET['action']) == "login") { 
        login();
    }
    else if(htmlentities($_GET['action']) == "logout") { 
        logout();
    }
    
    //Grab the view
    $category = htmlentities($_GET['c']);     
     
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>PhotoRankr Market - Stock Photos Done Differently</title>

    <link rel="stylesheet" href="css/bootstrapnew2.css" type="text/css" />
    <link rel="stylesheet" href="css/reset.css" type="text/css" />
    <link rel="stylesheet" href="css/text.css" type="text/css" />
    <link rel="stylesheet" href="css/960_24.css" type="text/css" />
    <link rel="stylesheet" href="css/index.css" type="text/css"/> 
    <link rel="stylesheet" href="css/itunes.css" type="text/css"/> 
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
<body style="overflow-x:hidden;">

<?php navbarnew(); ?>


	
<!--CONTAINER-->
<div class="container">

<!--Featured Photos-->
<div class="grid_24" style="padding-top:20px;margin-left:-130px;width:1200px;">
    
    <?php
        $topfavedphotos = mysql_query("SELECT source,points,votes,price,caption FROM photos ORDER BY faves DESC LIMIT 0,6");
        $source1 = mysql_result($topfavedphotos,0,'source');
        $source1 = str_replace('userphotos/','http://photorankr.com/userphotos/', $source1);
        $source1thumb = str_replace('userphotos/','userphotos/thumbs/', $source1);
        $source2 = mysql_result($topfavedphotos,1,'source');
        $source2 = str_replace('userphotos/','http://photorankr.com/userphotos/', $source2);
        $source2thumb = str_replace('userphotos/','userphotos/thumbs/', $source2);
        $source3 = mysql_result($topfavedphotos,2,'source');
        $source3 = str_replace('userphotos/','http://photorankr.com/userphotos/', $source3);
        $source3thumb = str_replace('userphotos/','userphotos/thumbs/', $source3);
        $source4 = mysql_result($topfavedphotos,3,'source');
        $source4 = str_replace('userphotos/','http://photorankr.com/userphotos/', $source4);
        $source4thumb = str_replace('userphotos/','userphotos/thumbs/', $source4);
        $source5 = mysql_result($topfavedphotos,4,'source');
        $source5 = str_replace('userphotos/','http://photorankr.com/userphotos/', $source5);
        $source5thumb = str_replace('userphotos/','userphotos/thumbs/', $source5);
        $source6 = mysql_result($topfavedphotos,5,'source');
        $source6 = str_replace('userphotos/','http://photorankr.com/userphotos/', $source6);
        $source6thumb = str_replace('userphotos/','userphotos/thumbs/', $source6);
        
        for($iii=0; $iii < 6; $iii++) {
        $price[$iii] = mysql_result($topfavedphotos,$iii,'price');
        $caption[$iii] = mysql_result($topfavedphotos,$iii,'caption');
        $points[$iii] = mysql_result($topfavedphotos,$iii,'points');
        $votes[$iii] = mysql_result($topfavedphotos,$iii,'votes');
        $rank[$iii] = ($points[$iii]/$votes[$iii]);
        $ranking[$iii] = number_format($rank[$iii],2);
        }
        
        echo'
        <div id="gallery" style="border: 2px solid #999;">
        <div class="statoverlay" style="z-index:1;left:0px;top:250px;position:relative;background-color:black;width:800px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:18px;">$',$price[0],'&nbsp;<span style="font-size:20px;">|</span>&nbsp;9.14</span>&nbsp;&nbsp;<span style="font-size:15px;line-height:18px;">"Sunset overlooking mount kilnor next to lake"</span></p></div>
    		   <img style="position:relative;top:-95px;min-width:800px;min-height:300px;" src="',$source1,'" height="80%" />
    		   <img style="position:relative;top:-95px;min-width:800px;min-height:300px;" src="',$source2,'" height="80%" />
    		   <img style="position:relative;top:-95px;min-width:800px;min-height:300px;" src="',$source3,'" height="80%" />
    		   <img style="position:relative;top:-95px;min-width:800px;min-height:300px;" src="',$source4,'" height="80%" />
               <img style="position:relative;top:-95px;min-width:800px;min-height:300px;" src="',$source5,'" height="80%" />
               <img style="position:relative;top:-95px;min-width:800px;min-height:300px;" src="',$source6,'" height="80%" />
               </div>
        
               <div id="thumbs" style="border: 2px solid #999;border-left: 0px;">
    	   	   <img src="',$source1thumb,'" height="100" width="100" />
    		   <img src="',$source2thumb,'" height="100" width="100" />
    		   <img src="',$source3thumb,'" height="100" width="100" />
    		   <img src="',$source4thumb,'" height="100" width="100" />
               <img src="',$source5thumb,'" height="100" width="100" />
               <img src="',$source6thumb,'" height="100" width="100" /> 
                  
	        </div>
            
            <a href="#" id="next"></a>';
    ?>


<!--TOP PHOTOGRAPHERS BOX-->    
    <div class="gradientbox" style="margin-top:-40px;float:right;width:250px;height:300px;">
        <div style="background-color:#1B628F;z-index:2;padding-top:4px;text-align:center;color:white;font-size:16px;opacity:1;width:250px;height:24px;border-top-left-radius:10px;border-top-right-radius:10px;">Featured Photographers<p>
        
        <div style="margin-top:-30px;">
        <?php
        $topphotos = mysql_query("SELECT user_id,firstname,lastname,profilepic FROM userinfo ORDER BY (totalscore) DESC LIMIT 0,5");
        for($iii=0; $iii < 4; $iii++) {
        $profpic = mysql_result($topphotos,$iii,'profilepic');
        $user_id = mysql_result($topphotos,$iii,'user_id');
        $firstname = mysql_result($topphotos,$iii,'firstname');
        $lastname = mysql_result($topphotos,$iii,'lastname');
        $fullname = $firstname . " " . $lastname;
        
        echo'<div style="width:225px;height:65px;"><div><a href="viewprofile.php?u=',$user_id,'"><img style="margin-left:10px;border:1px solid white;" src="http://photorankr.com/',$profpic,'" width="55" height="55" /></div><div style="float:left;font-size:13px;color:white;margin-top:-40px;margin-left:75px;">',$fullname,'</a></div></div>';
        }
        ?>
        </div>
        
    </div>
    
</div>
</div> <!--end 24 grid-->




<!--VIEWING BOX-->
<div class="grid_24" style="float:left;margin-left:-130px;margin-top:20px;">
<div name="content" style="width:1200px;">
<?php navbar2(); ?>


<!--SEARCH SORTING-->
<div style="width:320px;font-size:13px;padding:7px;margin-top:-55px;"><div style="float:left;"><b>Sort By:&nbsp;&nbsp;&nbsp;&nbsp;</b></div>&nbsp;&nbsp;
    
    <script>
        function submitPrice(sel) {
        sel.form.submit();
    }
        function submitRank(sel) {
        sel.form.submit();
    }
   </script>
    
    <div class="control-group" style="float:left;">
          <!-- Select Basic -->
          <label class="control-label"></label>
          <div class="controls">
           <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); if($category != '') {echo '?c=',$category,'';} else {} ?>&sort=price" method="post">
            <select name="price"  onchange="submitPrice(this)" class="input-large" style="width:100px;margin-top:-3px;">
              <option>Price</option>
              <option>High - Low</option>
              <option>Low - High</option>
            </select>
            </form>
        </div>
    </div>

    <div class="control-group" style="float:left;">
          <!-- Select Basic -->
          <label class="control-label"></label>
          <div class="controls">
           <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); if($category != '') {echo '?c=',$category,'';} else {} ?>&sort=rank" method="post">
            <select name="rank"  onchange="submitRank(this)" class="input-large" style="width:100px;margin-left:15px;margin-top:-3px;">
              <option>Rank</option>
              <option>High - Low</option>
              <option>Low - High</option>
            </select>
            </form>
        </div>
    </div>

</div>

<!--ADVANCED SEARCH-->
<div class="panel2" style="margin-top:-45px;">
<span style="font-size:14px;">

<form method="get">
<div class="control-group">
        <!-- Select Basic -->
          <label class="control-label">Resolution:</label>
          <div class="controls" style="padding-top:6px;">
            <select class="input-xlarge" name="resolution">
              <option value="">Choose&#8230;</option>
              <option value="base">Base</option>
              <option value="good">Good</option>
              <option value="great">Great</option>
            </select>
          </div>
        </div>
        
        <div class="control-group">
        <!-- Select Basic -->
          <label class="control-label">Orientation:</label>
          <div class="controls" style="padding-top:6px;">
            <select class="input-xlarge" name="orientation">
             <option value="">Choose&#8230;</option>
              <option value="portrait">Portrait</option>
              <option value="landscape">Landscape</option>
              <option value="panorama">Panorama</option>
            </select>
          </div>
        </div>
        
    <div class="control-group">
        <!-- Select Basic -->
          <label class="control-label">License:</label>
          <div class="controls" style="padding-top:6px;">
            <select class="input-xlarge" name="license">
             <option value="">Choose&#8230;</option>
              <option value="royaltyfree">Royalty Free</option>
              <option value="exlcusive">Exclusive</option>
              <option value="fuckingawesome">Fucking Awesome</option>
            </select>
          </div>
        </div>
        
        <div style="margin-top:-190px;margin-left:320px;width:620;height:190px;">
        
        <label class="control-label">Categories:</label>
          <div class="control-group">
          <!-- Multiple Checkboxes -->
          <div class="controls">
            <label class="checkbox inline">
              <input type="checkbox" value="advertising" name="category[]"> Advertising
            </label>
            <label class="checkbox inline">
              <input type="checkbox" value="animal" name="category[]"> Animal
            </label>
            <label class="checkbox inline">
              <input type="checkbox" value="architecture" name="category[]"> Architecture
            </label>
            <label class="checkbox inline">
              <input type="checkbox" value="automotive" name="category[]"> Automotive
            </label>
            <label class="checkbox inline">
              <input type="checkbox" value="commercial" name="category[]"> Commercial
            </label>
            <label class="checkbox inline">
              <input type="checkbox" value="fashion" name="category[]"> Fashion
            </label>
            
            <br /><br />
           
             <label class="checkbox inline">
              <input type="checkbox" value="fineart" name="category[]"> Fine Art
            </label>
            <label class="checkbox inline">
              <input type="checkbox" value="food" name="category[]"> Food
            </label>
            <label class="checkbox inline">
              <input type="checkbox" value="historical" name="category[]"> Historical
            </label>
            <label class="checkbox inline">
              <input type="checkbox" value="industrial" name="category[]"> Industrial
            </label>
            <label class="checkbox inline">
              <input type="checkbox" value="2" name="category[]"> Musical
            </label>
            <label class="checkbox inline">
              <input type="checkbox" value="3" name="category[]"> Nature
            </label>
            <label class="checkbox inline">
              <input type="checkbox" value="1" name="category[]"> News
            </label>
            
            <br /><br />
            
            <label class="checkbox inline">
              <input type="checkbox" value="2" name="category[]"> Night
            </label>
            <label class="checkbox inline">
              <input type="checkbox" value="3" name="category[]"> Scenic
            </label>
            <label class="checkbox inline">
              <input type="checkbox" value="1" name="category[]"> Sports
            </label>
            <label class="checkbox inline">
              <input type="checkbox" value="2" name="category[]"> Still Life
            </label>
            <label class="checkbox inline">
              <input type="checkbox" value="3" name="category[]"> Transportation
            </label>
            <label class="checkbox inline">
              <input type="checkbox" value="3" name="category[]"> War
            </label>
          </div>

        </div>
        </div>
        
         <div class="control-group">

          <!-- Text input-->
          <label class="control-label" for="input01">Keywords:</label>
          <div class="controls" style="padding-top:6px;">
            <input type="text" style="width:940px;height:32px;" placeholder="Search for photos with these tags" class="input-xlarge" name="keywords">
            <p class="help-block" style="margin-top:-7px;">Separate with a comma (e.g. leaf, boat, etc.)</p>
          </div>
        </div>

                
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
			values: [ 25, 100 ],
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
	<label for="amount" style="float:left;font-size:15px;font-family:helvetica neue,arial,lucida grande;padding:10px;margin-top:-15px;">Price range: 
    
    <a style="text-decoration:none;" href="#" id="pricepopover" rel="popover" data-content="Adjust the slider on the right to return photos that are inside that price range." data-original-title="What is price range?">(?)</a>
    <script>  
    $(function ()  
    { $("#pricepopover").popover();  
    });  
    </script>
    
    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</label>  
	<input id="amount" style="float:left;text-align:center;border:0; color:#333; padding:15px; background-color:white; width: 150px; font-size: 15px;position:relative;top:-10px;" />     
<div id="slider-range" style="float:left;width:750px;margin-left:10px;"></div>
</div>
<br />
</div><!-- End demo -->

<script>

	$(function() {
		$( "#slider-range2" ).slider({

			range: true,
			min: 0,
			max: 100,
			values: [ 20, 80 ],
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
	<label for="amount2" style="float:left;font-size:15px;font-family:helvetica neue,arial,lucida grande;padding:10px;margin-top:-15px;">Reputation Range: 
    
    <a style="text-decoration:none;" href="#" id="reppopover" rel="popover" data-content="Adjust the slider on the right to return photos that are from photographers inside that reputation range. Photographers with a higher reputation generally submit higher quality images." data-original-title="What is reputation range?">(?)</a>
    <script>  
    $(function ()  
    { $("#reppopover").popover();  
    });  
    </script>
    
    </label>  
	<input id="amount2" style="float:left;text-align:center;border:0; color:#333; padding:15px; background-color:white;position:relative;top:-10px;width: 150px;font-size: 15px;" />     
<div id="slider-range2" style="float:left;width:750px;margin-left:10px;"></div>
</div>
<br />

</div><!-- End demo -->


<script>

	$(function() {
		$( "#slider-range4" ).slider({

			range: true,
			min: 0,
			max: 500,
			values: [ 80, 250],
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
	<label for="amount4" style="float:left;font-size:15px;font-family:helvetica neue,arial,lucida grande;padding:10px;margin-top:-15px;">Download Range: 
    
     <a style="text-decoration:none;" href="#" id="downloadpopover" rel="popover" data-content="Adjust the slider on the right to return photos that have been downloaded that number of times." data-original-title="What is download range?">(?)</a>
    <script>  
    $(function ()  
    { $("#downloadpopover").popover();  
    });  
    </script>
    
    </label>  
	<input id="amount4" style="float:left;text-align:center;border:0; color:#333; padding:15px; background-color:white;position:relative;top:-10px;width: 150px; font-size: 15px;" /> 
<div id="slider-range4" style="float:left;width:750px;margin-left:10px;"></div>
</div>
<br />
</div><!-- End demo -->

<!--END SLIDERS-->

<div style="width:900px;">
<input class="btn btn-success" style="width:140px;padding:5px;margin-left:-330px;font-weight:bold;margin-top:20px;" type="submit" value="Search" /></div>
</form>

</span>
</div>
<p class="flip2 coolio" style="margin-left:320px;position:relative;top:-36px;">Advanced Search</p>


<?php
$sort = htmlentities($_GET['sort']);

if($category == 'newest') {
$query = mysql_query("SELECT * FROM photos ORDER BY id DESC LIMIT 0,60");
$numresults = mysql_num_rows($query);
    if($sort == 'price')
    {
        $order=$_POST['price']; 
        
            if($order == 'Low - High') {
                $query = mysql_query("SELECT * FROM photos WHERE price != 'Not For Sale' AND price != '' ORDER BY id DESC, price ASC LIMIT 0,60");
                $numresults = mysql_num_rows($query); 
            }
            
            elseif($order == 'High - Low') {
                $query = mysql_query("SELECT * FROM photos WHERE price != 'Not For Sale' ORDER BY id DESC, price DESC LIMIT 0,60");
                $numresults = mysql_num_rows($query); 
            }
    }
}

elseif($category == 'top') {
$query = mysql_query("SELECT * FROM photos ORDER BY points DESC LIMIT 0,60");
$numresults = mysql_num_rows($query);
}
elseif($category == 'trending' || $category == '') {
$query = mysql_query("SELECT * FROM photos ORDER BY score DESC LIMIT 0,60");
$numresults = mysql_num_rows($query);
}
elseif($category == 'pop') {
$query = mysql_query("SELECT * FROM photos WHERE views > 120 ORDER BY faves DESC LIMIT 0,60");
$numresults = mysql_num_rows($query);
}
elseif($category == 'deal') {
$deal = '10.00';
$twoweeksago = time() - 1209600;
$query = mysql_query("SELECT * FROM photos WHERE price < '$deal' AND time > '$twoweeksago' ORDER BY points DESC LIMIT 0,60");
$numresults = mysql_num_rows($query);
}
$searchterm = htmlentities($_GET['searchterm']);
if($c == '' && $searchterm != '') {
$query = mysql_query("SELECT *, MATCH (caption, tag, camera, tag1, tag2, tag3, tag4, singlecategorytags, singlestyletags, location, country, about, sets, maintags, settags) AGAINST ('$searchterm') AS matching FROM photos WHERE MATCH (caption, tag, camera, tag1, tag2, tag3, tag4, singlecategorytags, singlestyletags, location, country, about, sets, maintags, settags) AGAINST ('$searchterm') ORDER BY (points/votes) DESC");
$numresults = mysql_num_rows($query);
if($numresults < 1) {
echo'<div style="font-size:14px;text-align:center;">No results found for "',$searchterm,'." Try an advanced search above.</div>';
}
}

$resolution = htmlentities($_GET['resolution']);
$orientation = htmlentities($_GET['orientation']);
$license = htmlentities($_GET['license']);
$keywords = htmlentities($_GET['keywords']);
      
          $cat = $_GET['category'];
          foreach($categories as $cat) //loop through the checkboxes
          {
               echo $cat . ' ';
          }
               
$lowerprice = htmlentities($_GET['c']);
$higherprice = htmlentities($_GET['c2']);
$lowerrep = htmlentities($_GET['rc']);
$higherrep = htmlentities($_GET['rc2']);
$lowerdown = htmlentities($_GET['dc']);
$higherdown = htmlentities($_GET['dc2']);

if($resolution || $orientation || $license || $keywords || $cat || $lowerprice || $higherprice || $lowerrep || $higherrep || $lowerdown || $higherdown) {
    
    echo $keywords;
    $query = mysql_query("SELECT * FROM photos WHERE MATCH (caption,location,camera,tag1,tag2,tag3,tag4,singlestyletags,singlecategorytags) AGAINST '%$keywords%' LIMIT 0, 60");
    
    $numresults = mysql_num_rows($query);
    
    if($numresults < 1) {
        echo'<div style="font-size:14px;text-align:center;">No results found. Try a different search above.</div>';
    }

}


//AJAX Container
echo'<div id="thepics">';

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
    $title = "'" . $title . "'";
    $owner = mysql_result($query,$iii,'emailaddress');
    $sold = mysql_result($query,$iii,'sold');
    $points = mysql_result($query,$iii,'points');
    $votes = mysql_result($query,$iii,'votes');
    $rating = ($points/$votes);
    $license = mysql_result($query,$iii,'license');
    if($license == '') {
    $license = 'Royalty Free';
    }
    $rating = number_format($rating,2);
    $rating2 = number_format($rating,1);

    $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$owner'");
    $ownerpic = mysql_result($ownerquery,0,'profilepic');
    $ownerpic = 'http://photorankr.com/' . $ownerpic;
    $firstname = mysql_result($ownerquery,0,'firstname');
    $lastname = mysql_result($ownerquery,0,'lastname');
    $fullname = $firstname . " " . $lastname;
    
    list($height,$width) = getimagesize($imagebig[$iii]);
    $widthnew = $width / 6.5;
    $heightnew = $height / 6.5;
    $widthmed = $width / 5;
    $heightmed = $height / 5;

    echo'<div class="fPic"  id="',$imageid,'" style="width:230px;height:250px;overflow:hidden;float:left;"><a href="fullsize2.php?imageid=',$imageid,'"><img id="popover',$iii,'" rel="popover" data-content="<p>Rating: ',$rating,'</p><p>License: ',$license,'</p><p>Photographer: ',$fullname,'</p><img src=',$imagebig2[$iii],' height=',$widthmed,'px width=',$heightmed,'px  /><p>Downloads: ',$sold,'</p>" data-original-title="',$title,'" onmousedown="return false" oncontextmenu="return false;" class="phototitle" style="margin-right:30px;margin-top:20px;clear:right;" src="',$imagebig2[$iii],'" height="',$widthnew,'px" width="',$heightnew,'px" /></a>
    <div style="text-align:center;font-size:14px;clear:both;">',$price,'&nbsp;|&nbsp;',$rating2,'
     </div>';
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
					url: "loadMoreMarket.php?lastPicture=" + $(".fPic:last").attr("id"),
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


?>

</div>
</div>
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
