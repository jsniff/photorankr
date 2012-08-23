<?php

require 'functionsnav.php';

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>

		<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
		<link rel="stylesheet" href="960_24.css" type="text/css" />
		<link rel="stylesheet" href="css/style.css" type="text/css" />
		<link rel="stylesheet" href="text2.css" type="text/css" />
		<link rel="stylesheet" type="text/css" href="css/bootstrap-responsive.css" />
		    	<script type="text/javascript" src="js/bootstrap-dropdown.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    	<script type="text/javascript" src="js/bootstrap.js"></script>

	<style type="text/css">
		
.navlist
{
	text-decoration:none;
	font-color:#fff;
	font-family: "helvetica neue", helvetica,"lucida grande", arial, sans-serif;
	font-size:20px;
	margin-top:5px;
}
	
.formhead
 {
  	margin-left:2em;
  	width:5em;
  	color:white;
  	font: 16px "helvetica neue", helvetica, arial, sans-serif;
  	font-weight:600;
}

	</style>
	     	
	</head>

<body>
<div>
		<div style="opacity:.8;z-index:1;left:0px:top:0px;position:relative;height:100px;width:100%;background-color:white;text-align:center;">
			<div class="container_24" style="float:center;font-family:helvetica neue; font-size:15px;">
				<div class="grid_7 pull_1" style="border-right:1px solid grey;margin-top:20px;padding-right:10px;"><img src="graphics/blacklogo.png" width="260" height="50"/></div>
				<div class="grid_4 pull_1" style="border-right:1px solid grey;margin-top:20px;padding-right:10px;"><b>Photographers.</b></br>Share and sell photos like never before.</div>
				<div class="grid_4 pull_1" style="border-right:1px solid grey;margin-top:20px;padding-right:10px;"><b>Image Buyers.</b></br>Easy to buy fresh and affordable images.</div>
				<div class="grid_4 pull_1" style="border-right:1px solid grey;margin-top:20px;"><b>Photo Lovers.</b></br>Discover thousands of great photos.</div>
				<div class="grid_2 push_1" style="margin-top:40px;">
       <div>
    <div class="navbar fixed-top" style="width:90px;">
        <div class="navbar-inner" style="width:90px;">
            <div class="container" style="width:90px;">
                <ul class="nav">
           <li class="dropdown" id="menu1">
             <a class="dropdown-toggle" data-toggle="dropdown" href="#menu1">
               Login
                <b class="caret"></b>
             </a>
             <div class="dropdown-menu" style="width:300px;margin-left:-100px;">
               <form style="margin: 0px;float:left;" accept-charset="UTF-8" action="/sessions" method="post"><div style="margin:0;padding:0;display:inline"><input name="utf8" type="hidden" value="&#x2713;" /><input name="authenticity_token" type="hidden" value="4L/A2ZMYkhTD3IiNDMTuB/fhPRvyCNGEsaZocUUpw40=" /></div>
                 <div style="width:5em;">
                 <fieldset class='textbox' style="width:10em;">
                   <input style="margin-top: 8px" type="text" placeholder="Username" />
                   <input style="margin-top: 8px" type="password" placeholder="Passsword" />
                   <input class="btn btn-success" style="padding:.5 5em;" name="commit" type="submit" value="Log In" />
                 </fieldset>
             </div>
               </form>
               <form style="margin: 0px;float:right;" accept-charset="UTF-8" action="/sessions" method="post"><div style="margin:0;padding:0;display:inline"><input name="utf8" type="hidden" value="&#x2713;" /><input name="authenticity_token" type="hidden" value="4L/A2ZMYkhTD3IiNDMTuB/fhPRvyCNGEsaZocUUpw40=" /></div>
                 <fieldset class='textbox'  style="width:10em;">
                   <input style="margin-top: 8px" type="text" placeholder="Username" />
                   <input style="margin-top: 8px" type="password" placeholder="Passsword" />
                   <input class="btn btn-success" name="commit" type="submit" value="Log In" />
                 </fieldset>
               </form>
             </div>
             <ul>
           </div>
        </div>
    </div> 
    

				</div>
					
				</div>
			</div>
		</div>
	<script>$('.carousel').carousel()</script>
	<div id="myCarousel" class="carousel slide" style="position:relative;top:-100px;background-color:white;width:100%;height:550px;">
	<!-- Carousel items -->
	<div style="width:100%;height:550px;">
	<div class="carousel-inner" style="width:100%;height:550px;">
	<div class="active item" style="width:100%;height:550px;">

		<div><img style="float:center;" src="img/baliblue.jpg" width="1300" height="550"/></div>

	<div style="width:1000px;height:350px;position:relative;top:-375px;left:200px;">
		<div class="roundedall" style="float:left;width:400px;height:325px;">
			<div style="width:100%;height:100px;padding-left:10px;padding-right:10px;padding-top:10px;"><p1 style="text-shadow: 5px 5px 15px #333;color:white;font-family:arial,helvetica neue;font-size:30px;line-height:1.48;">Join the best photography network and marketplace.</p1></div>
			
			<form action="signin.php? action=signuponboard" method="post" style="width:100%;height:200px;padding-left:10px;padding-right:10px;padding-top:10px;">
				<div>
					<div style="width:400px;"><input style="float:left;width:150px;font-size:13px;margin-right:10px;margin-bottom:8px;background-color:white;" type="text" name="firstname" placeholder="First name" />
						<input style="float:left;width:150px;font-size:13px;margin-bottom:8px;background-color:white;" type="text" name="lastname" placeholder="Last name" /></div></br>

                            		<input style="width:320px;font-size:13px;margin-bottom:8px;background-color:white;opacity:1.0;" type="text" name="email" placeholder="E-mail address" /><br />
                           		<input style="width:320px;font-size:13px;background-color:white;" type="password" name="password" placeholder="Password" /><br />


<div style="width:100%;">

<form action="signup">
<select name="accounttypeype" style="float:left;width:160px;margin-right:10px;">
<option value="accounttype" selected="selected">Account type</option>
<option value="photographer">Photographer looking to share or sell photos.</option>
<option value="buyer">Buyer looking for fresh and affordable images.</option>
<option value="buyer">Photo lover looking to discover great photos.</option>
</select>
</form>

<button class="btn btn-success" data-toggle="button" style="float:left;width:160px;">Sign Up</button>
</div>

				</div>
			</form>


		</div>
		<div style="margin-top:100px;padding-left:100px;float:left;width:500px;height:100px;">
			<p1 style="text-shadow: 5px 5px 15px #333;color:white;font-family:arial,helvetica neue;font-size:20px;line-height:1.48;">Or begin searching the Marketplace.</p1></br></br>
			<input type="text" class="search5 roundedall" style="padding:13px;font-size:15px;"/>
		</div>
	</div>



	</div>

	<div class="item" style="width:100%;height:550px;"><img style="float:center;" src="img/poolside.jpg" width="1300" height="550"/>
	
	<div style="width:1000px;height:350px;position:relative;top:-375px;left:200px;">
		<div class="roundedall" style="float:left;width:400px;height:325px;">
			<div style="width:100%;height:100px;padding-left:10px;padding-right:10px;padding-top:10px;"><p1 style="text-shadow: 5px 5px 15px #333;color:white;font-family:arial,helvetica neue;font-size:30px;line-height:1.48;">Share your best shots with the world for free . . .</p1></div>
			
			<form action="signin.php? action=signuponboard" method="post" style="width:100%;height:200px;padding-left:10px;padding-right:10px;padding-top:10px;">
				<div>
					<div style="width:400px;"><input style="float:left;width:150px;font-size:13px;margin-right:10px;margin-bottom:8px;background-color:white;" type="text" name="firstname" placeholder="First name" />
						<input style="float:left;width:150px;font-size:13px;margin-bottom:8px;background-color:white;" type="text" name="lastname" placeholder="Last name" /></div></br>

                            		<input style="width:320px;font-size:13px;margin-bottom:8px;background-color:white;opacity:1.0;" type="text" name="email" placeholder="E-mail address" /><br />
                           		<input style="width:320px;font-size:13px;background-color:white;" type="password" name="password" placeholder="Password" /><br />


<div style="width:100%;">

<form action="signup">
<select name="accounttypeype" style="float:left;width:160px;margin-right:10px;">
<option value="accounttype" selected="selected">Account type</option>
<option value="photographer">Photographer looking to share or sell photos.</option>
<option value="buyer">Buyer looking for fresh and affordable images.</option>
<option value="buyer">Photo lover looking to discover great photos.</option>
</select>
</form>

<button class="btn btn-success" data-toggle="button" style="float:left;width:160px;">Sign Up</button>
</div>

				</div>
			</form>


		</div>
		<div style="margin-top:25px;padding-left:100px;float:left;width:500px;height:100px;">
			<p1 style="text-shadow: 5px 5px 15px #333;color:rgb(255,107,253);font-family:arial,helvetica nee;font-size:30px;line-height:1.48;">On the best network for passionate photographers.</p1></br></br>
			<ul style="list-style-type:none;text-shadow: 5px 5px 15px #333;color:white;font-family:helvetica neue;font-size:20px;line-height:1.48;">
				<li><img src="img/checkmark2.png" width="30" height="30"/>Unlimited uploads.  Includes uploading multiple photos at once.</li></br>
				<li><li><img src="img/checkmark2.png" width="30" height="30"/>Personalized store.  Allows you to manage the price and license options of your photos.</li></br>
				<li><li><img src="img/checkmark2.png" width="30" height="30"/>Feedback.  Give and receive feedback on photos through ranking, commenting, and favoriting photos.</li>
			</ul>
		</div>
	</div> <!--End for forms code-->

	
	</div>

	<div class="item" style="width:100%;height:550px;"><img style="float:center;" src="img/palousegold.jpg" width="1300" height="550"/>

<div style="width:1000px;height:350px;position:relative;top:-375px;left:200px;">
		<div class="roundedall" style="float:left;width:400px;height:325px;">
			<div style="width:100%;height:100px;padding-left:10px;padding-right:10px;padding-top:10px;"><p1 style="text-shadow: 5px 5px 15px #333;color:white;font-family:arial,helvetica neue;font-size:30px;line-height:1.48;">Can't find the perfect image?  Join PhotoRankr.</p1></div>
			
			<form action="signin.php? action=signuponboard" method="post" style="width:100%;height:200px;padding-left:10px;padding-right:10px;padding-top:10px;">
				<div>
					<div style="width:400px;"><input style="float:left;width:150px;font-size:13px;margin-right:10px;margin-bottom:8px;background-color:white;" type="text" name="firstname" placeholder="First name" />
						<input style="float:left;width:150px;font-size:13px;margin-bottom:8px;background-color:white;" type="text" name="lastname" placeholder="Last name" /></div></br>

                            		<input style="width:320px;font-size:13px;margin-bottom:8px;background-color:white;opacity:1.0;" type="text" name="email" placeholder="E-mail address" /><br />
                           		<input style="width:320px;font-size:13px;background-color:white;" type="password" name="password" placeholder="Password" /><br />


<div style="width:100%;">

<form action="signup">
<select name="accounttypeype" style="float:left;width:160px;margin-right:10px;">
<option value="accounttype" selected="selected">Account type</option>
<option value="photographer">Photographer looking to share or sell photos.</option>
<option value="buyer">Buyer looking for fresh and affordable images.</option>
<option value="buyer">Photo lover looking to discover great photos.</option>
</select>
</form>

<button class="btn btn-success" data-toggle="button" style="float:left;width:160px;">Sign Up</button>
</div>

				</div>
			</form>


		</div>

		<div style="margin-top:25px;padding-left:100px;float:left;width:500px;height:100px;">
			<p1 style="text-shadow: 5px 5px 15px #333;color:white;font-family:arial,helvetica nee;font-size:30px;line-height:1.48;">Join to create a Campaign that crowd-sources image requests.</p1></br></br>
			<ul style="text-shadow: 5px 5px 15px #333;list-style-type:none;color:white;font-family:helvetica neue;font-size:20px;line-height:1.48;">
				<li><li><img src="img/checkmark2.png" width="30" height="30"/>Satisfy your image need.  Describe the photo needed, the use, and let our photographers do the work.</li></br>
				<li><li><img src="img/checkmark2.png" width="30" height="30"/>Hassle-free.  A tailored license agreement is automatically generated at the end of each Campaign.</li></br>
				<a href="http://photorankr.com/viewcampaigns2.php"><button class="btn btn-success" data-toggle="button" style="float:left;width:160px;">Search Campaigns</button></a></li>
			</ul>
		</div>

	</div>


	</div>
	</div>
	<!-- Carousel nav -->
	<a class="carousel-control left" style="margin-top:60px;" href="#myCarousel" data-slide="prev">&lsaquo;</a>
	<a class="carousel-control right" style="margin-top:60px;" href="#myCarousel" data-slide="next">&rsaquo;</a>
	</div>
        </div> 
</div>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="js/bootstrap-dropdown.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<div id="fb-root"></div>

   
	</br>
	<div class="container_24" style="float:center;margin-top:-175px;">
	<?php footer(); ?>
	</div>
      

<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
</body>  

</html>     
             
            
            