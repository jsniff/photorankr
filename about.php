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

?>

<!DOCTYPE html>
<head>
<title> About | PhotoRankr </title>
<link rel="stylesheet" type="text/css" href="css/all.css"/>
<link rel="stylesheet" type="text/css" href="css/bootstrapNew.css"/>
<link rel="stylesheet" type="text/css" href="css/960_24_col.css"/>
<link rel="stylesheet" type="text/css" href="css/reset.css"/>
<link href="css/main3.css" rel="stylesheet" type="text/css"/>
<style type="text/css">
</style>

</head>
<body style="background:#f5f5f5;">
<?php include_once("analyticstracking.php") ?>

<?php navbar(); ?>

<!--Container Begins Here-->
<div class="container_24" style="background:none;margin-top:10px;">
	<div class="grid_24 push_2 topbar">
		<div class="grid_6">
		<div id="our" class="grid_6">
			<h1> Our </h1>
		</div>
		<div id="mission" class="grid_6">
			<h2> MISSION </h2>
		</div>
	</div>
		<div id="mission_statement"class="grid_17">
			<h1 style="font-weight:100;line-height:45px;"> Make buying and selling photos as easy as it is to share them.</h1>
	</div>
	<div class="grid_20">
		<div>
			<div class="grid_18 push_2" id="mynav" style="margin-left:-1em;">
				<a href="#team"><div class="grid_5 navigation">
					<p class="navtext">  The Team</p>
				</div></a> 
				<a href="contact.php"><div class="grid_5 navigation">
					<p class="navtext"> Contact Us</p>
				</div><a/>
				<a href="help.php"><div class="grid_5 navigation">
					<p class="navtext"> Help/FAQ </p>
				</div></a>
			</div>	
		</div>
	<div class="grid_20">
		<div class="grid_20" style="margin-left:-1em;">
			<h1 class="big_box_head"> Why?</h1>
			<div class="grid_20 big_box" style="margin-left:-1em;">
			</div>
			</div>			<div class="grid_9 grid_box">
				<div>
				<h1>Stock Image Sites Fall Short </h1>
				</div>	
			<div class="grid_8" >
				<p class="p_text">It is difficult for photographers to sell their 
photos  through stock websites. They 
pay out little to contributors and shut out
 emerging amateur and semi-pro photographers. Yet these photographers
 continue to produce, upload and share high quality photos.

				</p>
			</div>
		</div>
			<div class="grid_9 grid_box">
				<div>
				<h1> Image Buyers Don't Have It All Easy </h1>
				</div>
				<hr>	
			<div class="grid_8">
				<p class="p_text">People who buy images are limited the photos that are easiest to buy.
Stock image sites are more accessible and convenient than navigating via google to buy photos on mulitple individuals' sites. Many of these sites and social networks host stunning images nobody can find.

				</p>
			</div>
		</div>
		</div>	
	<div class="grid_20 pull_1" style="margin-lef:-1em;">
		<div class="grid_20 push_1" style="margin-left:-1em;">
			<h1 class="big_box_head"> PhotoRankr's Solution </h1>
		</div>
		<div class="grid_20 push_1 big_box_2" style="margin-left:-1em;">
		</div>	
		<div class="grid_20 push_1">
		<div class="grid_18" style="margin:.25em .7em;">	
			<div>
				<h1> A Platform to Share, Sell, and Buy Photography </h1>
				</div>
			<p class="p_text" style="text-align:justify;"> 
			We built a social network for 
			photographers to share their photos, knowledge, and inspiration.
			We also built a marketplace for buyers to purchase the that our photos
			photographers chose to sell. Then, to make it easier for buyers with specific image needs,
			 we linked our social network and marketplace to a crowd-sourcing 
			platform.</p>
		</div>
		<div class="grid_20" style="margin-left:-1em;">
			<h1 class="big_box_head"> The Three Parts of PhotoRankr </h1>
		</div>
		<div class="grid_20 big_box_2" style="margin-left:-1em;background-color:#666">
		</div>
		<div class="grid_18 grid_box">
			<div class="grid_18">
				<h1 class="grid_head"> Crowd-Source Photography</h1>
			</div>
			<div class="grid_18"> <!--see text trick in Smashing book 3-->
				<p class="p_text"> PhotoRankr's campaigns enable buyers to say
 to our global network of photographers: I need 
a photo of _______ by this  date, with this price, 
and these rights. Photographers, if they have a
 photo of _______  and accept the license, can 
submit a photo from their PhotoRankr profile,
 their hard drives, or go shoot out and shoot a 
few photos. If you run a campaign you can use 
campaign-wide and individual feedback to 
funnel photographer's submissions to the photo 
you envision. This way photographerscan exercise 
their creativity and buyers get the best possible
 photo for their needs.
				</p>		
			</div>
	</div>	
			<div class="grid_18 grid_box">
			<div class="grid_18">
				<h1 class="grid_head"> The PhotoRankr Marketplace </h1>
			</div>
			<div class="grid_18"> <!--see text trick in Smashing book 3-->
				<p class="p_text"> PhotoRankr's Market gives everyone the 
opportunity to submit images. We only ask 
you secure model and property releases 
and eliminate any indications of brands
 where necessary. Buyers are able to purchase 
the best photos they never would have seen.

It's simple to upload:
 drag and drop, license and price,
 keyword and go. You can even skip step
 two and just share your photo. Perhaps after
 some feedback from other PhotoRankr 
photographers you will decide to price 
and license your photo  later. 

Buyers, you are able to search by more than
 just category, license
type and keyword. You can sort search results by our network's 
ranking, photographer's reputations, and other social metrics. 
Get social with buying photos! Follow photographers you
 purchase from and receive a customized feed of new images
 up for sale. 

				</p>		
			</div>
	</div>	


			<div class="grid_18 grid_box">
			<div class="grid_18">
				<h1 class="grid_head"> Share, Learn, Grow, and Inspire </h1>
			</div>
			<div class="grid_18"> <!--see text trick in Smashing book 3-->
				<p class="p_text">Creating only a marketplace misses out on the most important part 
of photography: designing the moment. Photographers posses the 
talent to tell a story in a single frame. Sometimes, the moment just
 happens and you get lucky, but we know there is more to
 photography than meets the eye. Share  the moments you capture
 and how you design those moments with others, the equipment 
you use, and why you do it. Inspire others who want to be like you
 on PhotoRankr's Network.
				</p>		
			</div>
	</div>	
</div>
<div class="grid_20">
			<h1 class="big_box_head" style="margin-left:1em;"> The Team </h1>
		</div>
		<div class="grid_20 big_box_2 push_1" style="margin-left:-1em;background-color:#96bd84">
		</div>
		<div class="grid_20 grid_box push_1" id="team">
			<div class="grid_3 pull_1 picbox_container">
				<div class="grid_4 picbox_container ">
				<div class="grid_4 picbox">
					<img src="img/matt.jpg" style="border-radius:5px;"/>
				</div>
				<div class="grid_4 name">
					<h1> Matt Sniff - CTO </h1>
				</div>
				<div class="grid_4 info">
				<p class="text_block"> Matt is a passionate amateur photographer who built
					the code your photos stand on. </p>
				</div>
				</div>
			</div>	
			<div class="grid_3 push_1" style="margin-left:-.3em;">
				<div class="grid_4 picbox_container">
				<div class="grid_4 picbox">
					<img src="img/noah.jpg" style="border-radius:5px;"/>
				</div>
				<div class="grid_4 name">
					<h1> Noah Willard - CPO  </h1>
				</div>
				<div class="grid_4 info">
				<p class="text_block"> Noah designs PhotoRankr's simple look while squeezing in his own 
					photography and blog posts. </p>
				</div>
				</div>
			</div>	
		
			<div class="grid_3 push_3" style="margin-left:-.3em;">
				<div class="grid_4 picbox_container">
				<div class="grid_4 picbox">
					<img src="img/jacob.jpg" style="border-radius:5px;"/>
				</div>
				<div class="grid_4 name">
					<h1>Jacob Sniff - CEO</h1>
				</div>
				<div class="grid_4 info">
				<p class="text_block">
					Jacob is our fearless leader who drives PhotoRankr to be the best online photo-graphy experience. </p>
				</div>
				</div>
			</div>	
			
			</div>
		</div>	


	<!--<div class="grid_16" style="background-image:"><!--make the contact form look as if you are sending a postcard to photorankr
		<div class="grid_16">
			<h1> Contact PhotoRankr HQ</h1>
		</div>	
		<div class="grid_8" style="float:left;">
		<form action="MAILTO:ncwillard@gmail.com" method="post" entype="text/plain">
			<label>Name:</label>
			<input type="text" name="name"/>
			<label>Your Email</label>
			<input type="text" name="email"/>
			<label>Your Message </label>
			<input type="submit" value="Send">
			<input type="reset" value="Start Over">
		</form>	
	</div>
		<div class="grid_4" style="float:right;">
			<h1> Address </h1>
			 	<ul>
			 		<li> photorankr@photorankr.com </li>
			 	</ul>
		</div>-->	

</div>

</div>

</div>

</div>
</div>
</div>



<div style="clear:both;width:100%;">
<?php //footer(); ?>
</div>


</body>
</html>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>	
	<script type="text/javascript" src="js/bootstrap-dropdown.js"></script>
			

<script type="text/javascript">  
        $(document).ready(function () {  
            $('.dropdown-toggle').dropdown();  
        });  



   </script> 