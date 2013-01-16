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


<!DOCTYPE HTML>
<head>
	<title>Photo Sharing Meets the Market</title>
	<meta name="Keywords" content="photos, sharing photos, photo sharing, photography, stock photography, stock, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos, social stock, photo licensing, royalty free photos, crowdsource, crowdsourcing photos, crowdsourced photos">

<meta name="Description" content="PhotoRankr is a social marketplace for photography that makes buying and selling photos as easy as it is to share them. Photographers share, view, rank, comment, favorite photos, and watch them trend. Image buyers can find custom and creative content by searching the marketplace using social filters such as trending and photographer reputation or by creating a Campaign that crowd-sources photography needs.">

	<meta charset = "UTF-8">
	<title> Sell, share and discover brilliant photography </title>
	<link href = "css/main2 2.css" rel="stylesheet" type="text/css"/>
	<link href = "css/grid.css" rel="stylesheet" type="text/css"/>
	<link href = "css/reset.css" rel="stylesheet" type="text/css"/>
	<link href = "css/normalize.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" media='screen and (max-width:640px)' href="css/640.css"/>
	<link href = "css/bootstrap1.css" rel="stylesheet" type="text/css"/>
	<script src="js/modernizer.js"></script>
    
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

	<style type="text/css">

		/*.arrow-right {
	width: 0; 
	height: 0; 
	border-top: 13px solid transparent;
	border-bottom: 13px solid transparent;
	box-shadow: inset 0 0 1px #999;
	border-right: 13px solid rgba(245,245,245,1);
	position: absolute;
	top:33px;
	left:75px;
}*/

.fixedTop
		{
			position: fixed;
			top: 0px;
			}
				.triangle
	{
		width: 0; 
		height: 0; 
		border-left: 11px solid transparent;
		border-right: 11px solid transparent;
		border-bottom: 12px solid #ddd;
		position: relative;
		top:-16px;
		left:107px;
	}
	.triangleLeft
	{
		width: 0; 
		height: 0; 
		border-top: 15px solid transparent;
		border-bottom: 15px solid transparent;
		border-right: 16px solid #eee;
		position: relative;
		top:50px;
		left:310px;
		z-index: 1000;
	}
	#spec{
		background: none;float:left;height:55px !important;width:55px !important;margin:-11px 0 0 100px !important;
	}
	#spec:hover
	{
		background: none;
	}
	#spec img
	{
		width: 182px !important	;
		height:42px !important;
	}
    .scroll
    {
        position:relative !important;
        margin:-45px 0 0 0 !important;
    }
    .statoverlay

{
background-attachment: scroll;
background-clip: border-box;
background-color: 
rgba(0, 0, 0, 0.848438);
background-image: none;
background-origin: padding-box;
color: rgb(255, 255, 255);
bottom: 0px;
display: block;
font-family: 'Helvetica Neue', 'Helvetica Neue', Helvetica, Arial, sans-serif;
font-size: 14px;
font-style: normal;
font-variant: normal;
font-weight: normal;
line-height: 0px;
margin-bottom: 0px;
margin-left: 0px;
margin-right: 0px;
margin-top: 0px;
overflow-x: hidden;
overflow-y: hidden;
padding-bottom: 0px;
padding-left: 0px;
padding-right: 0px;
padding-top: 0px;
white-space: nowrap;
width: 270px;
-moz-box-shadow: 1px 1px 5px #888;
-webkit-box-shadow: 1px 1px 5px #888;
box-shadow: 1px 1px 5px #888;
}

	</style>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script type="text/javascript" src="js/jquery.wookmark.js"></script>

</head>
<body style="background-image:url('graphics/paper.png');background-repeat:repeat;">
<!-- Left Nav -->

<!--Main Content-->

<div>


<div class="CNav" style="position:fixed;top:0;left:0;z-index:10000;">
<div class="homeNav" style="width:100%;z-index:10000;">
	<ul>
		<li id="spec"> <img src="graphics/logo_big_w.png" style="height:55px;margin-top:-1px;width:55px"/> </li>
		

		<li class="dropdown" id="accountmenu" style="text-align:center;margin-top:-2px;"> 
			<a class="dropdown-toggle" data-toggle="dropdown" href="#"> Log In </b></a>
				<ul class="dropdown-menu" style="background: url('graphics/paper.png'), rgba(235,235,235,.9);box-shadow: 0 2px 10px 1px #333;
				width:225px;margin-top:10px;text-align:center;">
					<li style="text-align:center;margin:0;">
					<div class="triangle"> </div> 
						<form class="logIn" action="profile.php?action=log_in" method="POST">
							<legend> Log In </legend>
							<legend class="FP"> <a style="color:#888;margin-left:-10px;" href="mailto:support@photorankr.com?subject=Lost Password">Forgot you password? Got it covered. </a></legend>
							<label for="username"> Email </label>	
							<input class="logInInput" type="text" name="emailaddress">
							<label for="password"> Password </label>
							<input class="logInInput" type="password" name="password">
							<input type="submit" value="Log In " class="logInBtn">
						</form>
					</li>
				</ul>
			</li>	
				
	</ul>
</div></div>
<div class="container_custom" style="clear:both;margin:60px auto 0 auto;width:1180px;padding-left:80px;">
	<!--WHERE WOOKMARK GOES-->
	<div id="picContainer">
		<div id="searchHome">
			<header> Find amazing <br />  <span>photos </span></header>
			<form action="#" method="get">
				<input style="float:left;" type="text" name="searchterm" placeholder="Search for amazing photography&hellip;">&nbsp;
                 <div id="gbqfbw" style="float:left;cursor:pointer;">
                    <button style="float:left;cursor:pointer;" onClick="formSubmit()" id="gbqfb" class="gbqfb" aria-label="PR Search">
                        <span class="gbqfi" style="cursor:pointer;"> Search </span> 
                    </button>
                </div>
			</form>
		</div>
		<?php
            
            if(!$searchterm) {
                $imagesquery = mysql_query("SELECT * FROM photos ORDER BY score DESC LIMIT 17");
            }
            else{
                 $imagesquery = mysql_query("SELECT * FROM photos WHERE concat(tag1,tag2,tag3,tag4,singlestyletags,singlecategorytags) LIKE '%$searchterm%' ORDER BY views DESC LIMIT 17");
            }
                
                echo'<div id="thepics" style="position:relative;left:-12px;top:-20px;z-index:1;">
                     <div id="main" role="main">
                     <ul id="tiles">';
                
                for($iii = 1; $iii <= 16; $iii++) {
                    $image = mysql_result($imagesquery, $iii-1, "source");
                    $imageThumb=str_replace("userphotos/","userphotos/medthumbs/", $image);
                    $id = mysql_result($imagesquery, $iii-1, "id");
		    $views = mysql_result($imagesquery, $iii-1, "views");
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
                        $widthls = 240;
                    }
         
	if($searchterm) {
	echo '
        <a style="text-decoration:none;color:#333;" href="fullsizemarket.php?imageid=',$id,'&v=n"><li class="fPic" id="',$views,'" style="list-style-type: none;width:240px;"><img id="frontimg" style="min-width:240px;" src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" /></a>
        
            <div class="marketunderlay" style="float:right;position:relative;top:0px;width:240px;height:30px;">
            <div style="line-spacing:1.48;padding:5px;color:#4A4A4A;">
                <div style="float:left;">
                    <span style="font-size:15px;font-weight:500;">',$score,'</span>&nbsp;&nbsp;<span style="font-weight:500;font-size:13px;">',$caption,'</span>
                </div>
                <div style="float:right;">
                     <span style="font-weight:500;font-size:13px;"><img style="margin-top:-4px;padding:3px;width:12px;" src="graphics/tag.png" /> $',$price,'</span>
                </div>
            </div>
        </div>
        </li>';   
	}
	else {           
         echo '
        <a style="text-decoration:none;color:#333;" href="fullsizemarket.php?imageid=',$id,'&v=n"><li class="fPic" id="',$id,'" style="list-style-type: none;width:240px;"><img id="frontimg" style="min-width:240px;" src="https://photorankr.com/',$imageThumb,'" height="',$heightls,'px" width="',$widthls,'px" /></a>
        
            <div class="marketunderlay" style="float:right;position:relative;top:0px;width:240px;height:30px;">
            <div style="line-spacing:1.48;padding:5px;color:#4A4A4A;">
                <div style="float:left;">
                    <span style="font-size:15px;font-weight:500;">',$score,'</span>&nbsp;&nbsp;<span style="font-weight:500;font-size:13px;">',$caption,'</span>
                </div>
                <div style="float:right;">
                     <span style="font-weight:500;font-size:13px;"><img style="margin-top:-4px;padding:3px;width:12px;" src="graphics/tag.png" /> $',$price,'</span>
                </div>
            </div>
        </div>
        </li>';   
	}    	
                                      
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
        offset: 10, // Optional, the distance between grid items
        itemWidth: 245 // Optional, the width of a grid item
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
   <div class="grid_6 push_13" style="padding-top:25px;padding-bottom:25px;">
   <div id="loadMorePics" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;"><img style="width:50px;" src="LoadingGIF.gif" /></div>
   </div>

<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMorePics").show();
				$.ajax({
					url: "loadMoreTrendingFront.php?lastPicture=" + $(".fPic:last").attr("id")+"&searchterm=<?php echo $searchterm; ?>",
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

	</div>

	<!--FORM-->
	<div id="formContainer" style="border-left:none;">
            <hgroup>
			<header> 
				<img src="graphics/logo_big.png">		
			</header>
			<h1> Photo sharing meets the market </h1>
		</hgroup>
        
     <?php
        
            if(htmlentities($_GET['action']) == 'notactivated') {
                    
                        echo'<div class="grid_10 pull_4" id="bar_container" style="color:green;font-size:16px;font-weight:bold;text-align:center;margin-bottom:8px;">Please check your email to finish registration, thank you.</div>';
                        
            }
            
            elseif(htmlentities($_GET['action']) == 'activated') {
                    
                        echo'<div class="grid_10 pull_4" id="bar_container" style="color:green;font-size:18px;font-weight:bold;text-align:center;margin-bottom:8px;">You may now login to your account</div>';
                        
            }
        ?>
    
    
		<ul id="valProp">
			<li id="homeSocialA"><img style="width:18px;margin-top:-4px;padding:3px;" src="graphics/groups_b.png" /> Join a network of people who share your passion. <span> </span>
				<ul id="homeSocial">
					<li> <img style="width:15px;margin-top:-5px;" src="graphics/user.png" /><b> Personalized Profile </b> <br />  Profile lets you display your portfolio, manage market sales, and view reputation and statistics. </li>
					<li> <img style="width:15px;margin-top:-5px;" src="graphics/list 1.png" /> <b> News Feed </b> <br /> Follow photographers to view their uploads, activity, and photo sales.  </li>
					<li> <img style="width:15px;margin-top:-5px;" src="graphics/groups_b.png" /> <b> Groups </b><br /> Network with others about photos, techniques, and equipment.</li>
				</ul>


			</li>

			<li id="homePersonalA"><img style="width:16px;margin-top:-4px;padding:3px;" src="graphics/picture.png" /> Easily sell your best work. <span> </span>
				<ul id="homePersonal">
					<li> <img style="width:15px;margin-top:-5px;" src="graphics/cloud.png" /> <b>Open Platform</b><br /> If your images adhere to our guidelines they're automatically on the market. </li>
					<li> <img style="width:15px;margin-top:-5px;" src="graphics/tag.png" /> <b> Pricing </b> <br />Name the base price for your photos. </li>
					<li> <img style="width:15px;margin-top:-5px;" src="graphics/file down.png" /> <b> Licensing </b> <br /> Control which of your photos are available for commercial or editorial licensing or not for sale. </li>
				</ul>

			</li>
			<li id="homeMarketA"><img style="width:15px;margin-top:-4px;padding:3px;" src="graphics/tag.png" /> Purchase high-quality photos on our marketplace. <span> </span>
				<ul id="homeMarket">
					<li> <img style="width:15px;margin-top:-5px;" src="graphics/tick 2.png" /> <b>Simple licensing and pricing options </b><br /> Purchase commercial and editorial photos priced according to resolution level.</li>
					<li> <img style="width:15px;margin-top:-5px;" src="graphics/search.png" /> <b>Powerful Search</b> <br /> Quickly find the image you need with our simple and powerful marketplace search features. </li>
					<li> <img style="width:15px;margin-top:-5px;" src="graphics/star.png" /> <b>Social Marketplace </b><br /> Find fresh and creative content by searching social metrics such as top-ranked photos.</li>
					
				</ul>


			</li>
		</ul>

<a id="signUpBtnA" href="https://photorankr.com/register.php"><div id="signUpBtn" style="height:25px;"> Sign Up </div></a>
		<div id="miniFooter">
			<ul>
				<a style="text-decoration:none;color:#333;" href="about.php"><li> About </li></a>
				<a style="text-decoration:none;color:#333;" href="contact.php"><li> Contact </li></a>
				<a style="text-decoration:none;color:#333;" href="privacy.php"><li> Privacy Policy </li></a>
				<a style="text-decoration:none;color:#333;" href="terms.php"><li> Terms </li></a>
				<a style="text-decoration:none;" href="https://www.facebook.com/pages/PhotoRankr/140599622721692?fref=ts"><li style="width:31px;z-index: 1000;padding-left:5px;margin: -4px 0 0 0;"> <img style="height:25px;border-radius: 3px 0 0 3px;" src="graphics/facebook_s.png"/></li></a>
				<a style="text-decoration:none;" href="https://twitter.com/PhotoRankr"><li style="width:33px;padding-left:0;padding-right:5px;margin: -4px 0 0 0px;"> <img  style="height:25px;border-radius:0 3px 3px 0;" src="graphics/twitter_s.png"/></li></a>
			</ul>
		</div>
	</div>
    
    </div>  <!--end form container-->
    
<!--END CONTAINER-->
</div>
<!--END MAIN-->
</body>
<!--JAVASCRIPT-->
<script type="text/javascript" src="js/bootstrap.js"></script>

<script type="text/javascript">  
        $(document).ready(function () {  
            $('.dropdown-toggle').dropdown();  
        });  
   </script> 
   <script type="text/javascript">

    /*$(document).ready(function () {
   		$("#signUpBtnA").on('click', function () {
   			var url = $(this).attr('href');
   			$('#formContainer1').load(url);
   		});
   	})();*/
   </script>
   <script type="text/javascript">  
    
    /*load in onboarding form
        $(document).ready(function(){
            // load index page when the page loads
            $("#formContainer").load("indexfirst.php");
            $("#sendInfo").click(function(){
            //load form page on click
                $("#formContainer").load("indexform.php");
            });

        });*/
    
        (function(){
        	var count = 1
        	$("#homeSocialA").on('click',function(){
        		if(count === 1){
        		$("#homeSocial").animate({
        			'height' : 205
        		});
        		$('#formContainer').addClass('scroll');
        		count -= 1;}
        		else {
        			$("#homeSocial").animate({
        			'height' : 0

        		});
        			count += 1;
        		}
        	});

        })();
        (function(){
        	var count = 1
        	$("#homePersonalA").on('click',function(){
        		if(count === 1){
        		$("#homePersonal").animate({
        			'height' : 200
        		});
        		$('#formContainer').addClass('scroll');
        		count -= 1;}
        		else {
        			$("#homePersonal").animate({
        			'height' : 0

        		});
        			count += 1;
        		}
        	});

        })(); 
        (function(){
        	var count = 1
        	$("#homeMarketA").on('click',function(){
        		if(count === 1){
        		$("#homeMarket").animate({
        			'height' : 225
        		});
        		$('#formContainer').addClass('scroll');
        		count -= 1;}
        		else {
        			$("#homeMarket").animate({
        			'height' : 0

        		});
        			count += 1;
        		}
        	});

        })() 
   </script> 
   
    <!--Mobile Redirect-->
    <script type="text/javascript">
        if (screen.width <= 600) {
            window.location = "http://mobile.photorankr.com";
        }
    </script>
    
</html>