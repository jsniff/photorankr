<!DOCTYPE HTML>
<head>
	<meta charset = "UTF-8">
	<title> Sell, share and discover brilliant photography </title>
	<link href = "css/main2.css" rel="stylesheet" type="text/css"/>
	<link href = "css/grid.css" rel="stylesheet" type="text/css"/>
	<link href = "css/reset.css" rel="stylesheet" type="text/css"/>
	<link href = "css/normalize.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" media='screen and (max-width:640px)' href="css/640.css"/>

	<script src="js/modernizer.js"></script>
	 <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
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
	</style>
</head>
<body id="body" >
<!-- Left Nav -->

<!--Main Content-->

<div id="Main">
	<div id="leftBar" style="height:100%;width:70px;">
	<ul>
		<li><img src="graphics/aperature_new copy.png" style="width:55px;"/><img src="graphics/logo_text.png" style="margin-top:-18px;width:60px!important;
    height:12px !important;"/> </li>
		<li> <img src="graphics/galleries_b.png"/><p> Gallery </p><div class="arrow-right"></div></li>
		<li><img src="graphics/news_b.png"/> <p> News </p> </li>
		<li><img src="graphics/groups_b.png"/> <p> Groups </p> </li>
		<li><img src="graphics/market_b.png"/> <p> Market </p> </li>
		<li> <img src="graphics/blog_b.png"/> Blog </li>
	</ul>


</div>

<div class="CNav">
	<ul>
		<li> Cover </li>
		<li> Newest </li>
		<li> Trending </li>
		<li> Top Ranked </li>
		<li> Discover </li>
		<li><form >
					<input type="text"/>
					<img src="graphics/glass.png" width="20px"/>
				</form>	
			</li>
				
	</ul>
</div>
<div class="container_custom" style="margin:50px 0 0 120px;width:1080px;">
	
	<div class="bloc_4" style="float:right;width:24.07%;margin:45px 0 5% 0;display:block;">

		<!--ID TAG-->
		<div id="Tag">
			<div id="topHalf">
				<img src="img/profilePic.jpg"/>
				<header> Noah Willard </header>
				<button id="follow"> Follow </button>
			</div>
			<div id="bottomHalf">  
				<header> Photos: 345 </header>
				<header> Rep: 34.1 </header>
			</div>	
		</div>

		<!--STATS BAR-->
		<div id="statsBar">
			<ul class="numbers">
				<li> 8.9<span>/10</span> </li>
				<li> 6 </li>
				<li> 6 </li>
				<li> $5 </li>
			</ul>
			<ul>
				<li id="rankButton">  <img src="graphics/rank_b_c.png" /> Rank </li>
				<li> <img src="graphics/collection_b_c.png"/>  Collect </li>
				<li> <img src="graphics/fave_b_c.png"/> Fave </li>
				<li> <img src="graphics/market_b_c.png"/> Purchase </li>
			</ul>

			<ul id="Rank">
				<li> 1 </li>
				<li> 2 </li>
				<li> 3 </li>
				<li> 4 </li>
				<li> 5 </li>
				<li> 6 </li>
				<li> 7 </li>
				<li> 8 </li>
				<li> 9 </li>
				<li> 10 </li>
			</ul>

		</div>

		

		<!--PREVIEW BAR-->
		<div id="nextPhotos">
			<header> More new photos </header>
				<div id="nextPhotosInner">
					<div id="arrowLeft"> 
						‹
					</div>
					<div id="arrowRight"> 
						›
					</div>	
					<div id="nextPhotosContainer">	
							<img src="img/test4.jpg">
							<img src="img/test2.jpg">
							<img src="img/test3.jpg">
					</div>
			</div>
		</div>


		<!--SHARE BAR-->
		<div id="shareBar">
			<ul>
				<li style="border-radius:5px 0 0 5px;width:47px;text-align:center;"> <img src="graphics/share_b.png" style="opacity:1;box-shadow: none;padding-bottom:2px;width:21px;height:19px;margin:0 0 0 12px;"/> Share  </li>
				<li > <img src="graphics/twitter_s.png"/> </li>
				<li > <img src="graphics/facebook_s.png"/> </li>
				<li > <img src="graphics/pinterest_s.png"/> </li>
				<li> <img src="graphics/more_s.png"/> </li>
			</ul>
		</div>
		<!--PHOTO STORY-->

		<div id="photoStory">
			<header> Behind the Lens </header>
			<p> It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English.</p>
		</div>

		<!--ABOUT PHOTO-->
		<div id="AboutPhoto">
			<header> About </header>
			<ul>
				<li> <img src="graphics/views.png"/> Views <span> 1,548 </span> 	</li>
				<li> <img src="graphics/camera.png"/> Camera <span> Nikon D5100 </span></li>
				<li> <img src="graphics/lens.png"/> Lens <span> Nikkor VR 18-55mm </span> </li>
				<li> <img src="graphics/aperature.png"/> Aperature <span> f/4.5 </span> </li>
				<li> <img src="graphics/shutterSpeed.png"/> Shutter Speed <span> 1/800sec </span></li>
				<li> <img src="graphics/focalLength.png"/> Focal Length <span> 18mm </span></li>
				<li> <img src="graphics/captureDate.png" style="width:16px;margin-left:-3px;"/> Capture Date <span> 7-3-12 </span></li>
				<li> <img src="graphics/copyright.png" style="width:15px;margin-left:-2px;"/> Copyright <span> Noah Willard </span></li>
				<li> <img src="graphics/location.png" style="width:10px;margin: 0 8px 0 0;"/> Location: <span>Nashville, TN </span></li>
			</ul>

		</div>

		<!--PHOTO TAGS-->
		<div id="Tags">

		</div>	
	</div>
	
	<!--TITLE-->
	<div class="bloc_12" style="float:left;display:block;width:74.07%;" id="title">
		<header> "Fireworks" <img src="graphics/uploadDate.png"/>  <span> uploaded 5 hours ago </span></header>
	</div>

	<!--IMAGE-->
	<div class="bloc_12" style="float:left;display:block;width:74.07%;" id="imgDisplay">
		<img src="img/test1	.jpg"/>
	</div>

	<!--COMMENTS-->
	<div class="bloc_12" style="float:left;width:74.07%;">

		<!--COMMENT BLOCK-->	
		<div id="comment">
			<div class="commentTag">
				<img src="img/profilePic.jpg"/>
				<header> Rep: 84.9 </header>
			</div>
			<div class="commentName">
				<header> Noah Willard </header>
				<img src="graphics/uploadDate.png"/>
				<p> 3 minutes ago </p>
			</div>
			<div class="commentBody">
				<p style="padding:5px;border-bottom: 1px solid #d6d6d6;"> Tell Noah what you think... </p>
			</div>
		</div>

		<!--COMMENT BLOCK-->	
		<div id="comment">
			<div class="commentTag">
				<img src="img/profilePic.jpg"/>
				<header> Rep: 84.9 </header>
			</div>
			<div class="commentName">
				<header> Noah Willard </header>
				<img src="graphics/uploadDate.png"/>
				<p> 3 minutes ago </p>
			</div>
			<div class="commentBody">
				<p> And scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. </p>
			</div>
		</div>

		

	</div>

	
</div>
		



</body>
<script type="text/javascript">

(function(){

	$('#rankButton').on('hover', function() {
		$('#Rank').toggleClass('OPEN');

	});
	$('#Rank').on('hover', function() {
			$('#Rank').toggleClass('OPEN');
		});

})();

</script>
</html>
			