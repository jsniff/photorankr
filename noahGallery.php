<!-- PHP & MYSQL -->

<?php

if($_SERVER['HTTPS']!="on")
  {
     $redirect= "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
     header("Location:$redirect");
  }
  
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

    //Get search term
    $searchterm = mysql_real_escape_string(htmlentities($_GET['searchterm']));
    
$query="SELECT * FROM photos ORDER BY id DESC LIMIT 0, 16";
$result=mysql_query($query);
$numberofpics=mysql_num_rows($result);

//QUERY FOR NOTIFICATIONS
$currentnots = "SELECT * FROM userinfo WHERE emailaddress = '$email'";
$currentnotsquery = mysql_query($currentnots);
$currentnotsresult = mysql_result($currentnotsquery, 0, "notifications");

//notifications query reset 
if($currentnotsresult > 0) {
$notsquery = "UPDATE userinfo SET notifications = 0 WHERE emailaddress = '$email6'";
$notsqueryrun = mysql_query($notsquery); }
  
?>

<!--END PHP & MYSQL -->

<!DOCTYPE HTML>
<head>
	<meta charset = "UTF-8">
	<meta name="viewport" content="width=1280px">
	<title> Sell, share and discover brilliant photography </title>
	<link href = "css/main2 dev.css" rel="stylesheet" type="text/css"/>
	<link href = "css/grid.css" rel="stylesheet" type="text/css"/>
	<link href = "css/reset.css" rel="stylesheet" type="text/css"/>
	<link href = "css/normalize.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" media='screen and (max-width:640px)' href="css/640.css"/>
	<link href="graphics/favicon.png" type="image/x-png" rel="shortcut icon"></link>
	<script src="js/modernizer.js"></script>
	<style type="text/css">

	.activeTop
	{
		box-shadow: inset 0 0 3px #000 !important;
		background: rgba(102,102,102,.67) !important; 
	}
	.active
	{
		box-shadow: inset 6px 0 6px #444 !important;
		background: rgba(102,102,102,.4) !important;
		color: #333; 
	}
	.activeLeft
	{
		background: rgba(102,102,102,.2); 
		box-shadow: inset 0 0 3px #444 ;
	}
	.lastNavItem
	{
		border-right: 0 !important;
	}
	.firstNavItem
	{
		border-left: 1px solid #444 !important;
	}
	#headerContainer
	{
		float: left;
	}
.open
{
	display: block !important;
	position: relative !important;
	top:-60px !important;
	left:805px !important;
}
.fixedTop
{
	position: fixed;
	top:25px;
}
::-webkit-input-placeholder 
{
    color:    #444;
}
:-moz-placeholder,
::-moz-placeholder 
{
	color:	#444;
}
	</style>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script type="text/javascript" src="js/jquery.wookmark.js"></script>

	<!--ANALYTICS CODE-->

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

<body id="body" >
<!-- Left Nav -->

<!--Main Content-->

<div id="Main">
	<div id="leftBar" style="height:100%;width:70px;">
	<ul>
		<li style="height:85px !important;"><img src="graphics/aperture_new.png" style="height:65px;width:65px;margin-top:10px;"/></li>
		<a href="galleries.php"><li class="activeLeft"> <img src="graphics/galleries_b.png"/><p> Galleries </p><div class="arrow-right"></div></li></a>
		<a href="newsfeed.php"><li><img src="graphics/news_b.png"/> <p> News </p> </li></a>
		<a href="groups.php"><li><img src="graphics/groups_b.png"/> <p> Groups </p> </li></a>
		<a href="market.php"><li><img src="graphics/market_b.png"/> <p> Market </p> </li></a>
		<a href="blog.php"><li> <img src="graphics/blog_b.png"/> Blog    </li></a>
	</ul>

</div>

<div class="CNav">
	<ul>
		<a href="galleries.php"><li class="activeTop firstNavItem"> Cover </li></a>
		<a href="newest.php"><li> Newest </li></a>	
		<a href="trending.php"><li> Trending </li></a>
		<a href="topranked.php"><li> Top Ranked </li></a>
		<a href="discover.php"><li class="lastNavItem"> Discover </li></a>
		<li id="searchCNav">
			<form >
				<input type="text" placeholder="Search" onkeyup="showResult(this.value)"/>
				<img src="graphics/search_i.png" style="width:25px;"/>
			</form>	
		</li>
				
	</ul>
</div>

<!--BEGIN PAGE CONTENT OUTSIDE CONTAINER-->


<!--END CONTENT OUTSIDE CONTAINER-->

<!--BEGIN CONTAINER-->

<div class="container_custom" style="margin:50px auto 0 auto;width:1172px;padding-left:70px;">

	<!--BEGIN PAGE CONTENT IN CONTAINER-->
	<div id="galleryNav">
		<header> PhotoRankr Galleries Cover </header>
	</div>

	<!-- Quick Look Menu -->
	<div id="headerContainer">
	<div id="quickLook">
		<header> Quick Look </header>

		<ul>
			<li id="PhotoList"> <span id="Plist"> <img src ="graphics/camera.png"> Photos  <img id="PlistArrowUp" src="graphics/arrowUp.png"/> </span>
				<ul id="photosList">
					<li> Top photos today <img src="graphics/arrowRight.png"/> </li>
					<li> Newest gallery <img src="graphics/arrowRight.png"/> </li>
					<li> Trending gallery <img src="graphics/arrowRight.png"/> </li>
					<li> Top ranked gallery  <img src="graphics/arrowRight.png"/> </li>
					<li> Your network's recent uploads <img src="graphics/arrowRight.png"/> </li>
				</ul>
			</li>
			<li id="CollectionsList"> <span id="Clist"> <img src ="graphics/collection_b.png"> Collections <img id="ClistArrowUp" src="graphics/arrowDown.png"/> </span>
				<ul id="collectionsList">
					<li> Top collections today <img src="graphics/arrowRight.png"/> </li>
					<li> Newest collections <img src="graphics/arrowRight.png"/> </li>
					<li> Trending collections <img src="graphics/arrowRight.png"/> </li>
					<li> Top Ranked collections <img src="graphics/arrowRight.png"/> </li>
					<li> Your network's recent collections <img src="graphics/arrowRight.png"/> </li>
				</ul>
			</li>
			<li id="ExhibitList"> <span id="Elist"><img src ="graphics/collection_b.png">Exhibits <img id="ElistArrowUp" src="graphics/arrowDown.png"/> </span>
				<ul id="exhibitList">
					<li> Top exhibits today <img src="graphics/arrowRight.png"/> </li>
					<li> Newest exhibits <img src="graphics/arrowRight.png"/> </li>
					<li> Trending exhibits <img src="graphics/arrowRight.png"/> </li>
					<li> Top Ranked exhibits <img src="graphics/arrowRight.png"/> </li>
					<li> Your network's recent exhibits <img src="graphics/arrowRight.png"/> </li>
				</ul>
			</li>			
		</ul>
	</div>
</div>
	<div id="galleryPicContainer" style="padding:5px">
		
		<?php
            
            if(!$searchterm) {
                $imagesquery = mysql_query("SELECT * FROM photos ORDER BY score DESC LIMIT 17");
            }
            else{
                 $imagesquery = mysql_query("SELECT * FROM photos WHERE concat(tag1,tag2,tag3,tag4,singlestyletags,singlecategorytags) LIKE '%$searchterm%' ORDER BY (points/votes) DESC LIMIT 20");
            }
                
                echo'<div id="thepics" style="position:relative;left:-1px;top:-10px;z-index:1;">
                     <div id="main" role="main">
                     <ul id="tiles">';
                
                for($iii = 1; $iii <= 16; $iii++) {
                    $image = mysql_result($imagesquery, $iii-1, "source");
                    $imageThumb=str_replace("userphotos/","userphotos/medthumbs/", $image);
                    $id = mysql_result($imagesquery, $iii-1, "id");
                    $caption = mysql_result($imagesquery, $iii-1, "caption");
                    $caption = (strlen($caption) > 18) ? substr($caption,0,16). " &#8230;" : $caption;
                    $price = mysql_result($result, $iii-1, "price");
                    if($price != 'Not For Sale') {
                        $price = '$' . $price;
                    }
                    elseif($price == 'Not For Sale') {
                        $price = 'NFS';
                    }
                    elseif($price == '.00') {
                        $price = 'Free';
                    }
                    $points = mysql_result($imagesquery, $iii-1, "points");
                    $votes = mysql_result($imagesquery, $iii-1, "votes");
                    $price = mysql_result($imagesquery, $iii-1, "price");
                    $score = number_format(($points/$votes),2);
                    $owner = mysql_result($result, $iii-1, "emailaddress");
                    $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$owner'");
                    $firstname = mysql_result($ownerquery, 0, "firstname");
                    $lastname = mysql_result($ownerquery, 0, "lastname");
                    $fullname = $firstname . " " . $lastname;
    
                    list($width, $height) = getimagesize($image);
                    $imgratio = $height / $width;
                    $heightls = $height / 5;
                    $widthls = $width / 5;

                    if($widthls < 205) {
                        $heightls = $heightls * ($heightls/$widthls);
                        $widthls = 255;
                    }
                    
                   echo '
        <a style="text-decoration:none;color:#333;" href="fullsize.php?imageid=',$id,'&v=n"><li class="fPic" id="',$id,'" style="list-style-type: none;width:262px;"><img id="frontimg" style="margin-bottom: -30px;box-shadow: 0 0 4px #222;min-width:255px;border-radius:5px;z-index:2;" src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" /></a>
        
            <div class="statoverlay" style="z-index:10;background-color:rgba(12,12,12,.61);position:relative;top:0px;width:255px;height:30px;border-radius: 0 0 5px 5px;">
                <div style="line-spacing:1.48;padding:5px;color:#ddd;">
                    <div style="float:left;padding:0;margin-top:-5px;">
                        <span style="display:inline-block;height:22px;color:#ccc;padding: 8px 10px 0 5px;margin:0 0 0 -5px;font-size:18px;font-weight:300;border-right: 1px solid #aaa;"><img src="graphics/rank_w.png" width="15px"/> ', $score ,'
                        </span>&nbsp;&nbsp;<span style="color#ccc;font-weight:300;display:inline:block;font-size:16px;">',$caption,'</span>
                    </div>
                </div>
            </div>
            <div style="padding:2px;"></div>
        </li>';       	
                                      
                }
                            
            ?>
            
            </ul>
    
    <!-- Once the page is loaded, initalize the plug-in. -->
  <script type="text/javascript">
    $(document).ready(new function() {
      // Prepare layout options.
      var options = {
        autoResize: true, // This will auto-update the layout when the browser window is resized.
        container: $('#main'), // Optional, used for some extra CSS styling
        offset: 7, // Optional, the distance between grid items
        itemWidth: 255// Optional, the width of a grid item
      };
      
      // Get a reference to your grid items.
      var handler = $('#tiles li');
      
      // Call the layout function.
      handler.wookmark(options);
      
    });
  </script>
            
</div>
</div>
            
    <!--AJAX CODE HERE-->
   <div class="grid_6 push_9" style="padding-top:50px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;">Loading More Photos&hellip;</div>
   </div>

<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-50) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMoreTrendingPics").show();
				$.ajax({
					url: "loadMoreTrendingFront.php?lastPicture=" + $(".fPic:last").attr("id"),
					success: function(html) {
						if(html) {
							$("#thepics").append(html);
							$("div#loadMoreTrendingPics").hide();
						}
					}
				});
				last = $(".fPic:last").attr("id");
			}
		}
	});
</script>    

	</div>

	</div>
	</div>


	
	<!--END CONTENT IN CONTAINER-->

<!--END CONATINER-->



	</div>
</div>
<!--END MAIN-->


</body>
<!--JAVASCRIPT-->
<script type="text/javascript">
(function () {
	
		

	$(document).on('click', function handler(event) {
		var $target = $(event.target),
			photosList = $("#photosList > li"),
			exhibitsList = $("#exhibitList > li"),
			collectionsList = $("#collectionsList > li"),
			$this = $target;
		
		if (photosList.hasClass('active')) {
			photosList.removeClass('active');
		}

		if ($target.is(photosList)) {
			$this.toggleClass('active');
		} 

		if (collectionsList.hasClass('active')) {
			collectionsList.removeClass('active');
		}

		if ($target.is(collectionsList)) {
			$this.toggleClass('active');
		} 

		if (exhibitsList.hasClass('active')) {
			exhibitsList.removeClass('active');
		}

		if ($target.is(exhibitsList)) {
			$this.toggleClass('active');
		} 

		 

		
	});

})();
(function(){

	var $header = $("#quickLook");
	var HeaderOffset = $header.position().top;
	$("#headerContainer").css({ height: $header.height() });

$("#Main").scroll(function() {
    if($(this).scrollTop() > HeaderOffset) {
        $header.addClass("fixedTop");
    } else {
        $header.removeClass("fixedTop");
    }
});
	
})();
(function(){
        	var count = 0,
        		countA = 1,
        		countB = 1,
        		exhibitList = $('#ExhibitList'),
        		photoList = $('#PhotoList'),
        		collectionsList = $('#CollectionsList'),
        		quickLook = $('#quickLook');



        	$("#Plist").on('click',function(){
        		if(count === 0){
        		photoList.animate({
        			'height' : 60
        		});
        		document.getElementById('PlistArrowUp').src="graphics/arrowDown.png" ;
        		quickLook.animate({'height' :  240 });
        		count += 1;
        	}
        		else {
        			photoList.animate({'height' : 345});
        			quickLook.animate({'height' :  530 });

        			if (countA == 0) {
        			exhibitList.animate({'height' : 60});
        			document.getElementById('ElistArrowUp').src="graphics/arrowDown.png" ;
        			countA += 1;
        			}

        			if (countB === 0) {
        			collectionsList.animate({'height' : 60});
        			countB += 1;
        			document.getElementById('ClistArrowUp').src="graphics/arrowDown.png";

        				}

        			document.getElementById('PlistArrowUp').src="graphics/arrowUp.png" ;
        			count -= 1;
        		}

        		
        	});



        	 
        	$("#Elist").on('click',function(){
        		
        		if(countA === 1){
        		exhibitList.animate({'height' : 345});
        		document.getElementById('ElistArrowUp').src="graphics/arrowUp.png" ;
        		countA -= 1;
        		quickLook.animate({'height' :  530 });

        			if (countB === 0 ){
        				collectionsList.animate({'height' : 60});
        				document.getElementById('ClistArrowUp').src="graphics/arrowDown.png" ;
        				countB += 1;
        				}
        			if (count === 0) {
        				photoList.animate({'height' : 60});
        				document.getElementById('PlistArrowUp').src="graphics/arrowDown.png" ;
        				quickLook.animate({'height' :  530 });
        				count += 1;
        			}
        	}
        		

        		else {
        			exhibitList.animate({
        			'height' : 60

        		});
        			document.getElementById('ElistArrowUp').src="graphics/arrowDown.png" ;
        			quickLook.animate({'height' :  240 });
        			countA += 1;
        		}
        	});





        	$("#Clist").on('click',function(){
        		
        		if(countB === 1){
        		collectionsList.animate({'height' : 345});
        		document.getElementById('ClistArrowUp').src="graphics/arrowUp.png" ;
        		countB -= 1;
        		quickLook.animate({'height' :  530 });

        			if (countA === 0 ){
        				exhibitList.animate({'height' : 60});
        				document.getElementById('ElistArrowUp').src="graphics/arrowDown.png" ;
        				countA += 1;
        				}
        			if (count === 0) {
        				photoList.animate({'height' : 60});
        				document.getElementById('PlistArrowUp').src="graphics/arrowDown.png" ;
        				count += 1;
        			}
        	}
        		
        		else {
        			collectionsList.animate({
        			'height' : 60

        		});
        			quickLook.animate({'height' :  240 });
        			document.getElementById('ClistArrowUp').src="graphics/arrowDown.png" ;
        			countB += 1;
        		}
        	});

        	if (count === 1 && countA === 1 && countB === 1)
        	{
        		$('#quickLook').css('max-height', 220 );
        	}

      })();
      
	
</script>
</html>