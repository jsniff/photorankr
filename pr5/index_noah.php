<!DOCTYPE HTML>
<head>
	<meta charset = "UTF-8">
	<title> Sell, share and discover brilliant photography </title>
	<link href = "css/main2 2.css" rel="stylesheet" type="text/css"/>
	<link href = "css/grid.css" rel="stylesheet" type="text/css"/>
	<link href = "css/reset.css" rel="stylesheet" type="text/css"/>
	<link href = "css/normalize.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" media='screen and (max-width:640px)' href="css/640.css"/>
	<link href = "css/bootstrap1.css" rel="stylesheet" type="text/css"/>
	<script src="js/modernizer.js"></script>
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
		position:inherit !important;
		margin:15px 0 0 0 !important;
	}
	</style>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script type="text/javascript" src="js/jquery.wookmark.js"></script>

</head>
<body id="body" >
<!-- Left Nav -->

<!--Main Content-->

<div id="Main">


<div class="CNav" style="position:fixed;top:0;left:0;">
<div class="homeNav" style="width:100%;">
	<ul>
		<li id="spec"> <img src="graphics/logo_big_w.png" style="height:55px;margin-top:-1px;width:55px"/> </li>
		

		<li class="dropdown" id="accountmenu" style="text-align:center;margin-top:-2px;"> 
			<a class="dropdown-toggle" data-toggle="dropdown" href="#"> Log In </b></a>
				<ul class="dropdown-menu" style="background: url('graphics/paper.png'), rgba(235,235,235,.9);box-shadow: 0 2px 10px 1px #333;
				width:225px;margin-top:10px;text-align:center;">
					<li style="text-align:center;margin:0;">
					<div class="triangle"> </div> 
						<form class="logIn">
							<legend> Log In </legend>
							<legend class="FP"> Forgot you password? Got it covered. </legend>
							<label for="username"> Email </label>	
							<input class="logInInput" type="text">
							<label for="password"> Password </label>
							<input class="logInInput" type="password">
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
			<header> Search something <br />  <span>awesome </span></header>
			<form>
				<input type="text" >
			</form>
		</div>
		<div style="height:1200px;background:#ddd;width:755px;"></div>
	</div>

	<!--FORM-->
	<div id="formContainer">
		<hgroup>
			<header> 
				<img src="graphics/logo_big.png">		
			</header>
			<h1> Find out what you can create </h1>
		</hgroup>
		<ul id="valProp">
			<li id="homeSocialA"> Join people who share your passion. <span> </span>
				<ul id="homeSocial">
					<li> <b> News Feed </b> <br /> Follow photographers to view their uploads, activity, and posts  </li>
					<li><b> Groups </b><br /> Get specific about equipment, shooting styles, and photography genres.</li>
					<li> <b> Collections </b> <br />Create collections of your favorite photos from around PhotoRankr</li>
				</ul>


			</li>

			<li id="homePersonalA"> Control your photos' price and license. <span> </span>
				<ul id="homePersonal">
					<li> <b> Pricing </b> <br />These are your photos, so we think you should name the price </li>
					<li> <b> Licensing </b> <br /> You should control how your images can be used, so you can choose the license </li>
					<li> <b> Personalization </b> <br /> Recieve statistics for how many people view, fave, and purchase each of your photos </li>
				</ul>

			</li>
			<li id="homeMarketA"> Buy and sell on our stock image market. <span> </span>
				<ul id="homeMarket">
					<li> <b>An Open Platform</b><br /> As long as your images adhere to our <a href=""/> guidelines </a> it's in the market </li>
					<li> <b>A Social Marketplace </b><br /> Tap into what PhotoRankr's users have deemed the best photos across the social network</li>
					<li> <b>Unprecedented Reach</b> <br /> Need a unique photo? We have a whole network ready to answer your request. </li>
				</ul>


			</li>
		</ul>

<a id="signUpBtnA" href="index_noah2.php"><button id="signUpBtn"> Sign Up </button></a>
		<div id="miniFooter">
			<ul>
				<li> About </li>
				<li> Contact </li>
				<li> Privacy Policy </li>
				<li> Terms </li>
				<li style="width:30px;z-index: 1000;padding-left:5px;margin: -4px 0 0 0;"> <img style="height:25px;border-radius: 3px 0 0 3px;" src="graphics/facebook_s.png"/></li>
				<li style="width:35px;padding-left:0;padding-right:5px;margin: -4px 0 0 0px;"> <img  style="height:25px;border-radius:0 3px 3px 0;" src="graphics/twitter_s.png"/></li>
			</ul>
		</div>
	</div>

</div>
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

    $(document).ready(function () {
   		$("#signUpBtnA").on('click', function () {
   			var url = $(this).attr('href');
   			$('#formContainer1').load(url);
   		});
   	})();
   </script>
   <script type="text/javascript">  
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
</html>