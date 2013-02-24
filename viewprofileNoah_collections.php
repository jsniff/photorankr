<!-- PHP & MYSQL -->


<!--END PHP & MYSQL -->

<!DOCTYPE HTML>
<head>
	<meta charset = "UTF-8">
	<meta name="viewport" content="width=1280px">
	<title> Sell, share and discover brilliant photography </title>
	<link href = "css/bootstrap1.css" rel="stylesheet" type="text/css"/>
	<link href = "css/main2 devCollections.css" rel="stylesheet" type="text/css"/>
	<link href = "css/grid.css" rel="stylesheet" type="text/css"/>
	<link href = "css/reset.css" rel="stylesheet" type="text/css"/>
	<link href = "css/normalize.css" rel="stylesheet" type="text/css"/>
	
	<link rel="stylesheet" media='screen and (max-width:640px)' href="css/640.css"/>
	<link href="graphics/favicon.png" type="image/x-png" rel="shortcut icon"></link>
	<script src="js/modernizer.js"></script>
	<style type="text/css">

.fixedTop
{
	position: fixed;
	top: 41px;
	width: 1162px;
}
.fixedTopCollection
{
	position: fixed !important;
	top: 82px !important;
	width: 760px;
	display: block;
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
@-moz-document url-prefix() {
	#button {
		margin-top:-70px;
	}
	#followBtn
	{
		padding-right:13px !important;
	}
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
	<a id="menuBtn" href="#"><img style="height:27px;" src="graphics/menu_i.png"/></a>
	<div id="left_bar" style="height:100%;">

	<ul>
		
		<a href="galleries.php"><li> <img src="graphics/galleries_b.png"/><p> Galleries </p><div class="arrow-right"></div></li></a>
		<a href="newsfeed.php"><li><img src="graphics/news_b.png"/> <p> News </p> </li></a>
		<a href="groups.php"><li><img src="graphics/groups_b.png"/> <p> Groups </p> </li></a>
		<a href="market.php"><li><img src="graphics/market_b.png"/> <p> Market </p> </li></a>
		<a href="blog.php"><li> <img src="graphics/blog_b.png"/> <p>Blog</p>    </li></a>
	</ul>

</div>

<div class="topNav">
	<div class="center">
	<ul>
		<a style="padding:0;background:none;	"href=""><li> <img src="graphics/logo_big_w.png"/></li></a>
		<li id="searchTopNav" style="margin-left:10em;">
			<form >
				<input type="text" placeholder="Search" onkeyup="showResult(this.value)"/>
				<img src="graphics/search_i.png" style="width:20px;float:right;position:relative;top:-27px;"/>
			</form>	
		</li>
		<a href="" class="dropdown" style="display:block;padding:.425em .5em;margin-left:17em;font-size:14px;border-right:1px solid #666;background: rgba(255,255,255,.05);">
			<li>
		 		<img style="width:30px;border:1px solid #eee;margin: -6px 5px 0 0;border-radius:17px;" src="img/profilePic.jpg"/>
		  			Noah Willard 
		  	</li>
		</a> 
		<li class="dropdown" id="accountmenu" style="width:2.25em;border-right: 1px solid #666;">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#"> <img style="width:21px;margin-top:-4px;" src="graphics/menu.png"/> </a>
				
				<ul class="dropdown-menu" style="background: url('graphics/paper.png'), #eee;box-shadow: 0 2px 10px 1px #333;
				width:150px;margin-top:10px;left:-60px;">
					<div class="triangle"> </div> 
					<a class="topBarElement" style="margin-top:-3px;" href=""><li> Profile </li></a>
					<a class="topBarElement" href=""><li> Portfolio </li></a>
					<a class="topBarElement" href=""><li> Store </li></a>
					<a class="topBarElement" href=""><li> Messages </li></a>
				</ul>
		</li>
		<li class="dropdown" id="accountmenu" style="width:2.45em;padding:.15em 0 .4em;margin:0;border-right: 1px solid #666;">
			<a class="dropdown-toggle" id="notifications" data-toggle="dropdown" href="#"> <span> 8 </span> </a>
				<ul class="dropdown-menu">
					<a href=""><li>asd </li></a>
					<a href=""><li>asd </li></a>
					<a href=""><li>asd </li></a>
					<a href=""><li>asd </li></a>
				</ul>
		</li>
		<li class="dropdown" id="accountmenu" style="width:2.25em;padding:.15em 0 0;margin:0;border-right: 1px solid #666;">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#"> <img style="width:21px;margin-top:-6px;" src="graphics/fave_w.png"> </a>
			<ul class="dropdown-menu" >
					<a href=""><li>asd </li></a>
					<a href=""><li>asd </li></a>
					<a href=""><li>asd </li></a>
					<a href=""><li>asd </li></a>
				</ul>
		</li>

		<!-- <a href="" style="width:2em;margin-left:3.5em;"><li id="status"> <img src="graphics/status_i.png"> </li></a> -->
		<a href="" style="background:none;margin-left:2em;"><li id="upload"><button class="btn-2" id="upload"><span>Upload </span> <img src="graphics/upload_i.png"> </button> </li></a>

		<!-- <a href="" style="margin-left:14em;font-size:14px;"><li> <img style="width:32px;border:1px solid #eee;margin: 1px 5px 0 0;border-radius:17px;" src="img/profilePic.jpg"/> Noah Willard </li></a> 
		<a href=""><li> <img style="width:23px;margin-top:5px;" src="graphics/topMenu_i.png"/> </li></a>
		<a href=""><li id="notifications"><span> 8 </span>  </li></a>			
		<a href=""><li id="status" style="margin-left:2em;"> <img src="graphics/status_i.png"> </li></a>
		<a href=""><li id="upload"><button class="btn-2" id="upload"><span>Upload </span> <img src="graphics/upload_i.png"> </button> </li></a> -->
	</ul>
</div>
</div>

<!--BEGIN PAGE CONTENT OUTSIDE CONTAINER-->


<!--END CONTENT OUTSIDE CONTAINER-->

<!--BEGIN CONTAINER-->

<div class="container_custom" id="main_container" style="margin:40px auto 0 auto;width:1172px;">

	<!--BEGIN PAGE CONTENT IN CONTAINER-->
	<div class="container_custom" style="min-height:800px;width:1162px;">
	
		<!--TOP CONTAINER SECTION-->
		<div id="topContainer1">

			<!--ID CARD-->
			<div id="idcard">
				
				<a href=""><header> Noah Willard </header></a>
				<div id="profilePicContainer">
					<img src="img/profilePic.jpg"/>
				</div>

				<ul style="margin-top:-150px;">
					<li> 
						<img src="graphics/rep_c.png"/> 
						<header> 89.5 </header>
						<p> Rep. </p>
					</li>
					<li> 
						<img src="graphics/camera_c.png"/> 
						<header> 1,243 </header>
						<p> Pictures </p>
					</li>
					<li style="margin-left:5px;"> 
						<img style="width:65px;margin-left:-5px;" src="graphics/network_c.png"/> 
						<header> 89.5 </header>
						<p> Followers </p>
					</li>
				</ul>
				<ul style="margin-top:-80px;">
					<li class="row2" style="margin-left:10px !important;"> 
						<img style="width:30px;" src="graphics/fave_i.png"/> 
						<h1> 90</h1>
						<p> Faves </p>
					</li >
					<li class="row2"> 
						<img style="width:30px;" src="graphics/rank_i.png"/> 
						<h1 style="margin-top:7px;"> 89.5 </h1>
						<p> Avg. Rank </p>
					</li>
					<li class="row2"> 
						<img style="width:30px;" src="graphics/rank_i.png"/> 
						<h1 style="margin-top:7px;"> 89.5 </h1>
						<p> Avg. Rank </p>
					</li>
					<li class="row2"> 
						<img style="width:30px;margin-top:5px;" src="graphics/views.png"/>
						<h1 style="margin-top:14px;"> 89.5 </h1>
						<p> Views </p>
					</li>
				</ul>

				<div class="profileBtnContainer" id="button">
					<a href=""><button id="followBtn" style="padding:6px 15px 6px 45px;margin: 0px 0 3px 0;" class="btn-2"> <img style="width:33px;margin: -3px 36px 0 -39px;" src="graphics/addNetwork_i_w.png"/>Follow </button></a>
					<a href=""><button style="padding:6px 16px 6px 38px;" class="btn-2"> <img style="width:28px;margin: -2px 23px 0 -32px;" src="graphics/message_i_w.png"/>Message </button></a>
				</div>
				

			</div>

			<!--ACTIVITY-->
			<div id="Activity">

				<a href=""><header> Activity </header></a>

				<ul>
					<a href=""><li> <img style="width:25px;" src="graphics/comment_i.png"/>   <span> Noah commented on "John Smith's" photo </span><br /> <span style="margin-top:-10px;"> "The red muffin" </span> <span class="time"> <img src="graphics/time.png"/> 5 minutes ago </span></li></a>
					<a href=""><li> <img style="width:25px;" src="graphics/fave_b.png"/>  <span> Noah commented on "John Smith's" photo </span><br /> <span style="margin-top:-10px;"> "The red muffin" </span> <span class="time"> <img src="graphics/time.png"/> 5 minutes ago </span></li></a>
					<a href=""><li> <img style="width:33px;" src="graphics/addNetwork_i.png"/> <span style='margin:-20px 0 10px 45px;'> Noah followed John Smith </span> </li></a>
					<a href=""><li> <img style="width:25px;" src="graphics/collection_i.png"/><span style='margin:-27px 0 35px 45px;'>Noah created the collection </span>  <span> "The red muffin" </span></li></a>	
					<a href=""><li> <img style="width:25px;" src="graphics/collection_i.png"/> <span style='margin:-27px 0 35px 45px;'>Noah added "The red muffin" by Jihn smithselon</span> <span> to the collection "The red muffin" <img style="width:20px;margin: -3px 0 0 0;opacity:.33;" src="graphics/camera_2.png"/>  23 </span></li></a>		
					<a href=""><li> <img style="width:25px;" src="graphics/collection_i.png"/><span style='margin:-27px 0 35px 45px;'> Noah created the exhibit  </span> <span> to the collection "The red muffin" </span></li></a>
				</ul>

			</div>

			<!--SNAPSHOT-->
			<div id="SnapShot">

				<a href=""><header> Network <span> 90 followers</span></header></a>

				<ul>
					<li> <div class="picCircle"><img src="img/profilePic.jpg"></div> <span> Noah Willard  </span></li>
					<li> <div class="picCircle"><img src="img/profilePic.jpg"></div><span> Noah Willard  </span></li>
					<li> <div class="picCircle"><img src="img/profilePic.jpg"></div><span> Noah Willard  </span></li>
					<li> <div class="picCircle"><img src="img/profilePic.jpg"></div><span> Noah Willard  </span></li>
					<li> <div class="picCircle"><img src="img/profilePic.jpg"></div><span> Noah Willard  </span></li>
					<li> <div class="picCircle"><img src="img/profilePic.jpg"></div><span> Noah Willard  </span></li>
					<li> <div class="picCircle"><img src="img/profilePic.jpg"></div><span> Noah Willard  </span></li>
					<li> <div class="picCircle"><img src="img/profilePic.jpg"></div><span> Noah Willard  </span></li>
					<li> <div class="picCircle"><img src="img/profilePic.jpg"></div><span> Noah Willard  </span></li>
					<li> <div class="picCircle"><img src="img/profilePic.jpg"></div><span> Noah Willard  </span></li>
					<li> <div class="picCircle"><img src="img/profilePic.jpg"></div><span> Noah Willard  </span></li>
						<li> <div class="picCircle"><img src="img/profilePic.jpg"></div><span> Noah Willard  </span></li>	
				</ul>

			</div>
		</div>

		<!--BEGIN PROFILE NAV-->
		<div id="cookie">
		<div id="profileNav">

			<ul>
				<a href=""><li> Portfolio <img src=""/> </li></a>
				<a href=""><li> Collections <img src=""/> </li></a>
				<a href=""><li> Store <img src=""/> </li></a>
				<a href=""><li> About <img src=""/> </li></a>
				<a href=""><li> Blog <img src=""/> </li></a>
				<a href=""><li> Network <img src=""/> </li></a>
				<a href=""><li> Groups <img src=""/> </li></a>

					<li>
						<form action="searchProfile.php">
							<input type="text">
						</form>
					</li>
			</ul>

		</div>

		<!--BEGIN PROFILE SUBNAV-->

		<!--BEGIN SUBNAV PORFOLIO-->
		<div id="subnavPortfolio">

			<ul>
				<a style="width:10em;" href="#" id="PList" ><li style="width:10em;"> Noah's Collections <img style="width:8px;margin-left:10px;" id="A1" src="graphics/arrowRight_w.png"/> </li> </a> 

				<li  id="subNavList1" style="width:0;">
					<ul>
						<a href="#"><li> Newest </li></a>
						<a href="#"><li> Top Ranked </li></a>
						<a href="#"><li> Most Fave'd </li></a>
					</ul>	
				</li>
				<a style="float:right;margin:-8px -70px 0 20px;z-index:10001;"href="#" ><li> <button id="newCollection" class="btn-2"> + Collection </button> </li> </a>
			</ul>

		</div>	 
	</div>
		<!--BEGIN BOTTOM CONTAINER-->

		<div id="bottomContainer" style="background:#eee;">

			<!--LEFT COLUMN-->

			<div id="leftWrapper">

				<div id="columnLeft">

					<!--BEGIN COLLECTIONS LIST-->

					<ul id="collections_list"> 

						
						<!--COLLECTIONS ITEM-->

						<a href="" class="collections_list_selected">
							<li>   
								<span> New York Collection <span> 56 photos </span></span>
								<ul class="aCollection" >
									<li> <img src="img/test1.jpg"/> </li>
									<li> <img src="img/test2.jpg"/> </li>
									<li> <img src="img/test3.jpg"/> </li>
								</ul>

							</li>
						</a>

						
						<!--COLLECTIONS ITEM-->

						<a href="" class="collections_list">
							<li>   
								<span> New York Collection <span> 56 photos </span></span>
								<ul class="aCollection" >
									<li> <img src="img/test1.jpg"/> </li>
									<li> <img src="img/test2.jpg"/> </li>
									<li> <img src="img/test3.jpg"/> </li>
								</ul>

							</li>
						</a>


						<!--COLLECTIONS ITEM-->

						<a href="" class="collections_list">
							<li>   
								<span> New York Collection <span> 56 photos </span></span>
								<ul class="aCollection" >
									<li> <img src="img/test1.jpg"/> </li>
									<li> <img src="img/test2.jpg"/> </li>
									<li> <img src="img/test3.jpg"/> </li>
								</ul>

							</li>
						</a>


						<!--COLLECTIONS ITEM-->

						<a href="" class="collections_list">
							<li>   
								<span> New York Collection <span> 56 photos </span></span>
								<ul class="aCollection" >
									<li> <img src="img/test1.jpg"/> </li>
									<li> <img src="img/test2.jpg"/> </li>
									<li> <img src="img/test3.jpg"/> </li>
								</ul>

							</li>
						</a>


						<!--COLLECTIONS ITEM-->

						<a href="" class="collections_list">
							<li>   
								<span> New York Collection <span> 56 photos </span></span>
								<ul class="aCollection" >
									<li> <img src="img/test1.jpg"/> </li>
									<li> <img src="img/test2.jpg"/> </li>
									<li> <img src="img/test3.jpg"/> </li>
								</ul>

							</li>
						</a>


						<!--COLLECTIONS ITEM-->

						<a href="" class="collections_list">
							<li>   
								<span> New York Collection <span> 56 photos </span></span>
								<ul class="aCollection" >
									<li> <img src="img/test1.jpg"/> </li>
									<li> <img src="img/test2.jpg"/> </li>
									<li> <img src="img/test3.jpg"/> </li>
								</ul>

							</li>
						</a>

					</ul>

				</div>

			</div>


		
				<!--RIGHT COLUMN HERE-->

				<div id="rightColumn">

					<div class="collectionWrapper" >
					<!--BIG COLLECTION-->

					<div class="collectionBlock" id="collectionBlock" >

						<header id="collectionTitle"> 
							<h1> New York Collection </h1>
						</header>

<!-- 						<a href=""><h5> About Collection <img src="graphics/downBtn.png"> </h5></a>
 -->
						<ul class="collectionHeader">

							
							<li>

							<h3> Description </h3>

							<p class="descriptionText">  simply dummy text of the printing and typesetting industry. 
								Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when
								 an unknown printer took a galley of type 
								and scrambled it to make a type specimen book. It has survived not only five centuries,
								 but also the l </p>

							</li>


							<li>

							<h3> Photos </h3>

							<p class="collectionStats"> 90 </p>

							</li>


							<li>

							<h3> Views </h3>

							<p class="collectionStats"> 32 </p>

							</li>

						</ul>


						<div class="imgContainerBigCollection">

							<ul class="imgCollectionList">
								<!-- <li> 

									

									<div class="imgContainer"> 
												<!--Overlay goes here 
											<div class="overlay"> 
												<h3> Mah Title Yo </h3>
												<span> Battle Record: 6-8</span>
												<ul style="margin-top:0px;padding:0;">
													<li style="float:left;"> <img src="graphics/fave_w.png" width="15px"/></li>
													<li style="float:left;"> <img src="graphics/fave_w.png" width="15px"/></li>
													<li style="float:left;"> <img src="graphics/fave_w.png" width="15px"/></li>
													<li style="float:left;"> <img src="graphics/fave_w.png" width="15px"/></li>
												</ul>
											</div> 
										<!-- IMAGE GOES HERE
										<img src="img/img-1.jpg">
									</div>
								</li>
								
							</ul>

							<ul>
								<li> </li>
								<li> </li>
								<li> </li>
								<li> </li>
								<li> </li>
							</ul>
 -->
						</div>

					</div>

					</div>
					
				</div>


		</div>








			<!--END CONTENT IN CONTAINER-->
	</div>
<!--END CONATINER-->



	</div>
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
(function(){
	//var header is the div you want to fix
	var $header = $("#cookie");
	var HeaderOffset = $header.position().top;
	$(".container_custom").css({ height: $header.height() });
	console.log(HeaderOffset);
//Main is the container
$("#Main").scroll(function() {
	//Include a fixed-top that sets the desired positition of the div
    if($(this).scrollTop() > HeaderOffset) {
        $header.addClass("fixedTop");
        $('#collectionTitle').addClass("fixedTopCollection");
    } else {
        $header.removeClass("fixedTop");
        $('#collectionTitle').removeClass("fixedTopCollection");
    }
});
	
})();
</script>
<script type="text/javascript">
(function(){
	var count = 0;

 $('#menuBtn').on('click', function() {

 	if(count === 0 ){ 
 	$('#left_bar').animate({ 'width' : 0});
 	count += 1;
 	$('#main_container').animate({ 'width' : 1280});
 	$('.center').animate({'padding-left' : 19});
} else {$('#left_bar').animate({ 'width' : 65});
 	count -= 1;
 	$('#main_container').animate({ 'width' : 1162 });
 	$('.center').animate({'padding-left' : 45});
 }

 });

})();
</script>

<!--TOGGLES COLLECTIONSLIST -->
<script type="text/javascript">
(function(){
	var portfolio = $('#subNavList1'),
		exhibit = $('#collectionTitle'),
		PList = $("#PList"),
		EList = $("#"),
		count = 0,
		count1 = 0;

	PList.on('click', function () {
		if (count === 1){
			portfolio.animate({'width' : 0});
			exhibit.animate({'width' : 785, 'margin-left' : -15});
			count -= 1;
			document.getElementById('A1').src="graphics/arrowRight_w.png" ;
		 } else {
		 	portfolio.animate({'width' : 450});
		 	exhibit.animate({'width' : 450, 'margin-left' : 320});
		 	count += 1;
		 	document.getElementById('A1').src="graphics/arrowLeft_w.png" ;
		 	if (count1 === 1){
		 		count1 -= 1;
		 		document.getElementById('A2').src="graphics/arrowRight_w.png" ;
		 	}
		 }
			
		
		
	});

	EList.on('click', function () {
		if (count1 === 1){
			exhibit.animate({'width' : 0});
			count1 -= 1;
			document.getElementById('A2').src="graphics/arrowRight_w.png" ;

		 } else {
		 	exhibit.animate({'width' : 600});
		 	portfolio.animate({'width' : 0});
		 	count1 += 1;
		 	document.getElementById('A2').src="graphics/arrowLeft_w.png" ;
		 	if (count === 1){count -= 1;document.getElementById('A1').src="graphics/arrowRight_w.png" ;}
		}
	});


})();
</script>