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
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script type="text/javascript" src="js/jquery.wookmark.js"></script>

</head>
<body id="body" >
<!-- Left Nav -->

<!--Main Content-->

<div id="Main">
	<div id="leftBar" style="height:100%;width:70px;">
	<ul>
		<li><img src="graphics/aperature_dark.png"/><img src="graphics/logo_text.png" style="margin-top:-18px;width:60px!important;
    height:12px !important;"/> </li>
		<li> <img src="graphics/gallery_ic.png"/><p> Gallery </p><div class="arrow-right"></div></li>
		<li><img src="graphics/news_i.png"/> <p> News </p> </li>
		<li><img src="graphics/groups_i.png"/> <p> Groups </p> </li>
		<li><img src="graphics/market_i.png"/> <p> Market </p> </li>
		<!--<li> <img src="graphics/blog.png"/>  </li>-->
	</ul>


</div>

<div class="CNav">
	<ul>
		<li> My News </li>
		<li style="width:150px;"> Around PhotoRankr </li>
		<li><form >
					<input type="text"/>
					<img src="graphics/glass.png" width="20px"/>
				</form>	
			</li>
				
	</ul>
</div>
<div class="container_custom" style="margin:50px 0 0 80px;">
	<div id="newsHeader">
		<header> My News </header>
		<ul>
			<li> <img src="graphics/camera.png"> Uploads </li>
			<li> <img src="graphics/collections.png"> collections </li>
			<li> <img src="graphics/favorite.png"> Favorites </li>
			<li> <img src="graphics/collections.png"> Exhibits </li>
			<li> <img src="graphics/gallery_i.png"> All </li>
		</ul>
	</div>	
	<div id="Content" style="width:1030px;margin-left:130px;">
		<div id="col1">
			<!--COMMENT-->
			<div class="comment">
				<hgroup>
				<img src="img/profilePic.jpg"/>
					<header>
					Noah Willard commented on: 
					</header>
				</hgroup>
				<div class="commentTriangle"></div>
				<div>
					<div class="commentTitle">
						<header> Sunrise over mountains </header>
					</div>
					<img src="img/test2.jpg"/>
					<div class="commentTitle1">
						<header> By Noah Willard </header>
					</div>
				</div>
				<p>
					"Comment comment comment oh I love commenting, seriously though its exciting"
				</p>
			</div>

			<!--IMAGE-->
			<div class="upload">
				<hgroup>
				<img src="img/profilePic.jpg"/>
					<header>
					<img src="graphics/time.png"/> <span> 5 minutes ago </span>Noah Willard uploaded: 
					</header>
						<img class="infoCon" src="graphics/camera.png"/>
					<div>
						
					</div>
				</hgroup>
				<div class="uploadTitle"> 
					<header> Big Fucking Meadow </header>
				</div>
				<img src="img/test.jpg"/>
			</div>
			<!--IMAGE-->
			<div class="upload">
				<hgroup>
				<img src="img/profilePic.jpg"/>
					<header>
					<img src="graphics/time.png"/> <span> 5 minutes ago </span>Noah Willard uploaded: 
					</header>
						<img class="infoCon" src="graphics/camera.png"/>
					<div>
						
					</div>
				</hgroup>
				<div class="uploadTitle"> 
					<header> Big Fucking Meadow </header>
				</div>
				<img src="img/test1.jpg"/>
			</div>
			<!--COMMENT-->
			<div class="comment">
				<hgroup>
				<img src="img/profilePic.jpg"/>
					<header>
					Noah Willard commented on: 
					</header>
				</hgroup>
				<div class="commentTriangle"></div>
				<div>
					<div class="commentTitle">
						<header> Sunrise over mountains </header>
					</div>
					<img src="img/test3.jpg"/>
					<div class="commentTitle1">
						<header> By Noah Willard </header>
					</div>
				</div>
				<p>
					"Comment comment comment oh I love commenting, seriously though its exciting"
				</p>
			</div>
		</div>
		<div id="col2" >
			<!--IMAGE-->
			<div class="upload">
				<hgroup>
				<img src="img/profilePic.jpg"/>
					<header>
					<img src="graphics/time.png"/> <span> 5 minutes ago </span>Noah Willard uploaded: 
					</header>
						<img class="infoCon" src="graphics/camera.png"/>
					<div>
						
					</div>
				</hgroup>
				<div class="uploadTitle"> 
					<header> Big Fucking Meadow </header>
				</div>
				<img src="img/test4.jpg"/>
			</div>
			<!--COMMENT-->
			<div class="comment">
				<hgroup>
				<img src="img/profilePic.jpg"/>
					<header>
					Noah Willard commented on: 
					</header>
				</hgroup>
				<div class="commentTriangle"></div>
				<div>
					<div class="commentTitle">
						<header> Sunrise over mountains </header>
					</div>
					<img src="img/test1.jpg"/>
					<div class="commentTitle1">
						<header> By Noah Willard </header>
					</div>
				</div>
				<p>
					"Comment comment comment oh I love commenting, seriously though its exciting"
				</p>
			</div>


			<!--IMAGE-->
			<div class="upload">
				<hgroup>
				<img src="img/profilePic.jpg"/>
					<header>
					<img src="graphics/time.png"/> <span> 5 minutes ago </span>Noah Willard uploaded: 
					</header>
						<img class="infoCon" src="graphics/camera.png"/>
					<div>
						
					</div>
				</hgroup>
				<div class="uploadTitle"> 
					<header> Big Fucking Meadow </header>
				</div>
				<img src="img/test3.jpg"/>
			</div>
			<!--IMAGE-->
			<div class="upload">
				<hgroup>
				<img src="img/profilePic.jpg"/>
					<header>
					<img src="graphics/time.png"/> <span> 5 minutes ago </span>Noah Willard uploaded: 
					</header>
						<img class="infoCon" src="graphics/camera.png"/>
					<div>
						
					</div>
				</hgroup>
				<div class="uploadTitle"> 
					<header> Big Fucking Meadow </header>
				</div>
				<img src="img/test4.jpg"/>
			</div>
		</div>
	</div>

	
	</div>


	
	</div>
</body>

	<script type="text/javascript">$('#content li').wookmark({offset: 2});</script>





	