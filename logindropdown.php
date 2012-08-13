<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title> This is a drop dowm </title>
<link rel="stylesheet" type="text/css" href="css/bootstrapNew.css"/>
<link rel="stylesheet" type="text/css" href="css/reset.css"/>
<link rel="stylesheet" type="text/css" href="css/all.css"/>
<link rel="stylesheet" type="text/css" href="css/reset.css"/>
<link rel="stylesheet" type="text/css" href="css/960_24_col.css"/>

<style type="text/css">

.navbar-inner
{
	text-align:center;
	background-color:#666666;
	background-image:url('graphics/gradient.png');
	background-image:-webkit-linear-gradient(top, #3e3e3e, #232323);
	background-image:-moz-linear-gradient(top, #3e3e3e, #232323);
	background-image:-o-linear-gradient(top,  #3e3e3e, #232323);
	background-image:-ms-linear-gradient(top,  #3e3e3e, #232323);

}

.center.navbar .nav,
.center.navbar .nav > li {
    float:none;
    display:inline-block;
    *display:inline; /* ie7 fix */
    *zoom:1; /* hasLayout ie7 trigger */
    vertical-align: top;
}

.center .navbar-inner {
    text-align:center;
}
.navbar .nav,
.navbar .nav > li {
    float:none;
    display:inline-block;
    *display:inline; /* ie7 fix */
    *zoom:1; /* hasLayout ie7 trigger */
    vertical-align: top;
}
.center .dropdown-menu {
    text-align: left;
}
ul.nav li.dropdown:hover ul.dropdown-menu{
    display: block;    
}

a.menu:after, .dropdown-toggle:after {
  content: none;
}
.search {
box-sizing: initial;
width: 14em;
outline-color: none;
border: 2px solid #6aae45;
-webkit-border-top-left-radius: 5px;
-webkit-border-bottom-left-radius: 5px;
-moz-border-radius-topleft: 5px;
-moz-border-radius-bottomleft: 5px;
border-top-left-radius: 5px;
border-bottom-left-radius: 5px;
font-family: helvetica neue, arial, lucida grande;
font-size: 14px;
background-image: url('images/glass.png');
background-position: 14.60em 2px;
background-size:1.4em 1.4em;
background-repeat: no-repeat;
}
.notifications
{
	width:1.8em;
	height:1.8em;
	border-radius:.9em;
	background:#efefef;
}
.open .dropdown-menu {
  display: block;
  margin-top:10px;
  }
  #fields
  {
  	border:1px solid white;
  	border-radius:5px;
  	margin:5px;
  	padding-top:5px;

  }
  .formhead
  {
  	margin-left:2em;
  	width:5em;
  	color:white;
  	font: 16px "helvetica neue", helvetica, arial, sans-serif;
  	font-weight:600;
  }
  .dropdown-menu
  {
  	border-color:rgba(25,25,25, .2);
  	border: 3px solid;
  	background-color:rgb(230,230,230);
  	margin-top: 10px;

  }
  ul.nav li.dropdown:hover ul.dropdown-menu{
    display: block;    
}

a.menu:after, .dropdown-toggle:after {
  content: none;
}
.navlist
{
	text-decoration:none;
	font-color:#fff;
	font-family: "helvetica neue", helvetica,"lucida grande", arial, sans-serif;
	font-size:20px;
	margin-top:5px;
}


</style>
</head>
<body id="body">
<!--The NavbarBegins here-->	
	<div class="navbar navbar-top">
		<div class="navbar-inner">
			<div class="container" style="height:40px;width:1040px;">
				<ul class="nav" style="height:40px;">
					<li class="topcenter"> <a href="index.php"> <img src="graphics/PRlogowithtext.png" style="positon:fixed;top:.05em;left:1em;width:150px;height:35px;margin-top:-5px;"/></a></li>
					<li class=" margint"> <form class="navbar-search"  action="/market/#search" method="get">
<input class="search margint marginl" style="height:1em;padding-right:25px;margin-left:5em;margin-right:5.5em"name="searchterm" type="text">
</form></li>
					<li class="navlist"> <a href="home.php"> Home </a> </li>
					<li> <a href="blog.php"> Blog </a> </li>
					<li class="dropdown topcenter " id="accountmenu">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#"> Photos </b></a>
							<ul class="dropdown-menu">
								<li> <a href="newest.php"> New </a></li>
								<li> <a href="trending.php"> Trending </a></li>
								<li class="divider"></li> 								<li> <a href="topranked.php"> Top Ranked </a></li> 
								<li> <a href="discover.php"> Discover </a> </li>
							</ul>
						</li>
						<li class="dropdown topcenter navlist" id="accountmenu">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"> Market</b> </a>
							<ul class="dropdown-menu">
								<li> <a href="newest.php"> Marketplace </a></li>
								<li> <a href="trending.php"> Campaigns </a></li>
							</ul>
						</li>
						<li class="dropdown navlist" id="accountmenu">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">  Log In </a>
								<ul class="dropdown-menu" style="width:36.5em;padding:5px;margin-left:-30em;border-radius:20px;">
									<div style="width:17.5em;height:15em;border-top-left-radius:15px;border-bottom-left-radius:15px;padding:5px;background-color:#74ba53;float:left">
										<fieldset id="fields">
											<legend class="formhead"> Network </legend>
											<input id="username" type="text" name="username" placeholder="username"/>
											<input id="password" type="password" name="password" placeholder="password"/>
											<a style="padding-bottom:10px;color:#fff;" href="forgotpass.php"> Forgot your password?</a>
										</fieldset>	
										<fieldset>
											 <input class="btn btn-primary" style="clear: left; width: 65%; height: 32px; font-size: 13px;margin-top:10px;" type="submit" name="commit" value="Sign In" />
										</fieldset>	
									</div>
									<div style="width:17.5em;height:15em;border-top-right-radius:15px;border-bottom-right-radius:15px;padding:5px;background-color:#666666;float:right;">
										<fieldset id="fields">
											<legend class="formhead"> Market </legend>
											<input id="username" type="text" name="username" placeholder="username"/>
											<input id="password" type="password" name="password" placeholder="password"/>
											<a style="padding-bottom:10px;color:#fff;" href="forgotpass.php"> Forgot your password?</a>
										</fieldset>	
										<fieldset>
											 <input class="btn btn-warning" style="clear: left; width: 65%; height: 32px; font-size: 13px;margin-top:10px;" type="submit" name="commit" value="Sign In" />
										</fieldset>	
									<div>	
							</ul>	
									
					</ul>			
				</div>
			</div>	
		</div>
	</div>
</div>	
<!--The Navbar Ends Here-->	
<!--Here the Grid Container Begins-->
<div class="container_24 container-margin">
<div class="grid_15">	
	<div class="grid_14" style="color:red;float:left;"style="float:left;">
		<h1 class="title"> Carnagie Hall </h1>
	</div>	
	<div class="grid_14 "style="float:left;" >
	<img src="images/img1.jpg" class="image" style="width:670px;height:800px;"/>	
	</div>

	<div class="grid_14"> <!--information under the photo-->
	</div>	
	<div class="grid_14 comments-box">
		<div class="grid_14 comment">
			<h1> Noah Willard </h1>
			<p> this comment is my comment and it will discuss the image above here.
				Comment here, comment there comment comment everywhere. Comment. Comment. Comment.
				Comment. Comment. Comment. 
			</p>	
		</div>
		<div class="grid_14 comment">
			<h1 id="name"> Noah Willard </h1>
			<p> this comment is my comment and it will discuss the image above here.
				Comment here, comment there comment comment everywhere. Comment. Comment. Comment.
				Comment. Comment. Comment. 
			</p>	
		</div>
		<div class="grid_14 comment">
			<h1> Noah Willard </h1>
			<p> this comment is my comment and it will discuss the image above here.
				Comment here, comment there comment comment everywhere. Comment. Comment. Comment.
				Comment. Comment. Comment. 
			</p>	
		</div>
		<div class="grid_14 comment">
			<h1> Noah Willard </h1>
			<p> this comment is my comment and it will discuss the image above here.
				Comment here, comment there comment comment everywhere. Comment. Comment. Comment.
				Comment. Comment. Comment. 
			</p>	
		</div>
		<div class="grid_14 comment">
			<h1> Noah Willard </h1>
			<p> this comment is my comment and it will discuss the image above here.
				Comment here, comment there comment comment everywhere. Comment. Comment. Comment.
				Comment. Comment. Comment. 
			</p>	
		</div>
	</div>	
	
</div>	
		<div class="grid_7">
			<div class="grid_7 box"> <!--ID Tag-->
				<div>
					<div id="imgborder">
					<img src="images/profilepic.jpg" class="profilepic"/>
				</div>
				<!--<h1 class="name"> oah Willard</h1>-->
			<div id="namewrap">
				<h1 id="name"> Noah Willard</h1>
				<button class="btn btn-primary"> Follow </button>
				<img src="graphics/repbar.png" style="width:110px;margin-top:10px;"/>
				<h1 id="rep"> Rep: &nbsp 70.22 </h1>
			</div>	
		</div>
	



			</div>
			<div class="grid_7 box underbox"><!--Rank and stats-->
				<div class="grid_7">
				<div class="fixed">
				<div style="float:left;">
				<button style="padding: .5em 3em .5em 3em;" class="btn btn-success dropdown-toggle" data-toggle="dropdown"> Rank  <span class="caret"></span>
				</button>
					<ul class="dropdown-menu">
						<li> <a href="#"> 1 </a></li>
						<li> <a href="#"> 2 </a></li>
						<li> <a href="#"> 3 </a></li>
						<li> <a href="#"> 4 </a></li>
						<li> <a href="#"> 5 </a></li>
						<li> <a href="#"> 6 </a></li>
						<li> <a href="#"> 7 </a></li>
						<li> <a href="#"> 8 </a></li>
						<li> <a href="#"> 9 </a></li>
						<li> <a href="#"> 10 </a></li>
					</ul>
    </button>


</div>​
				<button class="btn btn-danger" style="padding: .45em 2em .45em 2em;"> <img src="graphics/heart.png" style="width:20px;height:20px;float:right;"/> </button>	
				<button class="btn btn-primary" style="padding: .45em 1em .45em 1em;"> <img src="graphics/cart_white.png" style="width:20px;height:20px;float:right;"/> </button>
			</div>
		</div>
	</div>	
			<div class="grid_8" id="statsbox">
			<div class="grid_4 box underbox">	
				<ul id="stats">
					<li> <img src="graphics/rank_icon.png"/> <span id="rank"> Rank: </span> <span class="numbers">9.9</span><span id="littlenumbers"> /10 </span></li>
					<br />
					<li> <img src="graphics/heart_dark.png"/> <span id="stat"> Faves: </span> <span class="numbers"> 12</span> </li>
					<br />
					<li> <img src="graphics/eye.png"/> <span id="stat"> Views: </span> <span class="numbers">1002</span></li>
				</ul>
				</div>
				<div class="grid_2 box underbox float-right" style="width:90px;height:40px;">
					<h1 id="share">Shares</h1>
						<p id="sharenumber"> 1234 </p>
			</div>
			<div class="grid_2 box underbox float-right" style="width:90px;height:40px;"> <!--ML = margin-left -->
					<h1 id="share">Trends</h1>
						<p id="sharenumber"> 2 </p>
			</div>	
		</div>
			<div class="grid_7 box underbox"><!--Next photos-->
				
				<div id="images">
					<img src="images/nextimg1.jpg" id="nextimg1"/>
				</div>
				<div class="nextimg">
					<img src="images/nextimg2.jpg" id="nextimg2"/>
				</div>
				<div class="nextimg">	
					<img src="images/nextimg3.jpg"id="nextimg3"/>
				</div>
				<div class="grid_1" id="hover_arrow_left">
				</div>
					<div class="grid_1" id="hover_arrow_right">
				</div>	
				</div>
			
			<div class="grid_7 box underbox"><!--Share stuff here-->
					<h1 id="sharelinks"> Share: </h1>
					<a href=""> <img src="graphics/facebook.png" style="width:30px;height:30px;margin: 7px 9px 0px 10px;"/>
					<a href=""> <img src="graphics/twitter.png" style="width:30px;height:30px;margin: 7px 9px 0px 5px;"/></a>
					<a href=""><img src="graphics/pinterest.png" style="width:30px;height:30px;margin: 7px 9px 0px 5px;"/></a>
					<a href=""> <img src="graphics/g+.png" style="width:30px;height:30px;margin:7px 9px 0px 8px;"/></a>
			</div>
		
			<div class="grid_7 box underbox"><!--About photo-->
				<h1> About </h1>
					<div class="grid_7">
						<h1 class="about"> Location: </h1> <p class="aboutinfo"> Nashville </p>
					</div>
					<div class="grid_7">
						<h1 class="about"> Camera: </h1> <p class="aboutinfo"> Nikon D5100 </p>
					</div>
					<div class="grid_7">
						<h1 class="about"> Lens: </h1> <p class="aboutinfo"> Nikkor 55-200mmVR </p>
					</div>
					<div class="grid_7">
						<h1 class="about"> Focal Length: </h1> <p class="aboutinfo"> 200mm </p>
					</div>
					<div class="grid_7">
						<h1 class="about"> Shutter Speed: </h1> <p class="aboutinfo"> 1/1000/sec </p>
					</div>
					<div class="grid_7">
						<h1 class="about"> Aperture: </h1> <p class="aboutinfo"> f/5 </p>
					</div>
					<div class="grid_7">
						<h1 class="about"> Behind the Camera </h1> <p class="aboutinfo" style="line-height:20px;margin-left:10px;text-align:justified;"> 

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque viverra ante in urna mattis in varius libero rutrum. Mauris viverra commodo lectus at ornare. Nulla vehicula consectetur lacus quis tempor. Aliquam erat volutpat. Nam eu nibh in ligula blandit volutpat id sit amet nisi. Cras sed purus non sem facilisis sollicitudin ac vitae quam. In et diam ac eros placerat accumsan. Praesent pretium eleifend leo eget vestibulum. Integer et dolor sed lorem condimentum blandit et eget justo. Sed pellentesque sollicitudin odio vel tempus. Maecenas tempus congue nibh nec ultricies. Nam a odio nisl, et tincidunt magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.
umst. Duis laoreet euismod ipsum et aliquet. Ut eu felis sit amet diam venenatis euismod. Cras congue, nibh sed vulputate sagittis, arcu quam luctus felis, et suscipit elit mauris et urna. Etiam pellentesque, turpis a lacinia tempor, lorem neque placerat dolor, ut rutrum tortor dui ac velit. Aliquam sodales nibh at enim dapibus id blandit mauris convallis. Phasellus rhoncus sem a dui cursus mollis. Phasellus commodo. 
					</p>
				</div>	
				<div class="grid_7">
					<h1 class="about"> Tags </h1> <p class="aboutinfo"> stress &nbsp&nbsp wood &nbsp&nbsp monochrome &nbsp&nbsp black and  white </p> 

			</div>	
		</div>	
	</div>	
	
		


			





</div>	
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>	
	<script type="text/javascript" src="js/bootstrap-dropdown.js"></script>
			

<script type="text/javascript">  
        $(document).ready(function () {  
            $('.dropdown-toggle').dropdown();  
        });  
   </script> 
 </body>
 </html>  
