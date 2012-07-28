<!DOCTYPE html>

<html>
<head>

	<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="css/960_24_col.css"/>
	<link rel="stylesheet" type="text/css" href="css/reset.css"/>
	<link rel="stylesheet" type="text/css" href="css/all.css"/>
	<link rel="stylesheet" type="text/css" href="css/bootsrap-responsive.css"/>
	<script type="text/javascript" href="js/bootstrap-dropdown.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="http://masonry.desandro.com/jquery.masonry.min.js"></script>
<script type="text/javascript" src="https://raw.github.com/desandro/imagesloaded/master/jquery.imagesloaded.min.js"></script>
<style  type="text/css">
.navbar-inner
{
	text-align:center;
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
</style>
	
</head>

<body>
<!--navbar begin-->
<div class="navbar navbar-fixed-top center">
	<div class="navbar-inner" style="background: #424953;"> 
		<div class="container">
			<ul class="nav">
				<li> <img src="graphics/logo.png"/> </li>
				<li class="navlist"> View </li>
				<li class="navlist"> Discover</li>
				<li class="navlist"> Campaign</li>
				<li class="navlist"> Market</li>
				<li class="navlist"> Blog </li>
				<li class="navlist"> About </li>
				<li class="navlist"> Log In </li>
				<li class="navlist" style="margin-top:5px;">
					<form class="navbar-search" style="margin-top:-10px;" action="search.php" method="get">
						<input type="hidden" name="PHPSESSID" value=""/>
						<input type="text" style="" name="searchterm" placeholder="Search"/> 
					</form> </li>
			</ul>	
		</div>		
	<div>	
</div>
</div>
</div>
<!--navbar end-->

<div class="container_24" style="position:relative;margin-top:50px">
	<div class="grid_6 pull_1">
		<div class="grid_6 container" id="profilebox">
			<img src="pics/profilepic.jpg" style="width:100px;height:150px;float:left;"/>
			<?php 
			//whatever you use to display profile pics
			?>
		<div class="grid_3">
			<h1 id="name"> Noah Willard </h1>	
		</div>
		<div class="grid_3 btn1">
			<h1 class="btntext"> Follow </h1>
		</div>	
		<div class="grid_3 btn1">
			<h1 class="btntext"> Promote </h1>	
		</div>
		<div class="grid_1" id="repcricle"> 
		 <?php //fancy jQuery stuff
		?>
		</div>
		<div class="grid_1" id="avgscore"> 
		<?php //fancy jQuery stuff
		?>
		</div>
		<div class="grid_2" id="stats">
			<p class="statstext"> Followers: <?php //fun followers query ?></p>
			<p class="statstext"> Following: <?php //fun following query ?></p>
			<p class="statstext"> Photos: <?php //fun number of photos query?></p>
		</div>
	</div>
		<div class="grid_6 btn3">
			<h1 class="btntext2"> Upload </h1>
		</div>
		<div class="grid_6 btn3">
			<h1 class="btntext2"> Messages </h1>
		</div>
		<div class="grid_6 btn3">
			<h1 class="btntext2"> Information </h1>
		</div>
		<div class="grid_6 btn3">
			<h1 class="btntext2"> Equipment </h1>
		</div>
		<div class="grid_6 btn3">
			<h1 class="btntext2"> Market Profile </h1>
		</div>
		<div class="grid_6 btn3">
			<h1 class="btntext2"> Followers </h1>
		</div>
		<div class="grid_6 btn3">
			<h1 class="btntext2"> Following </h1>
		</div>
		<div class="grid_6 btn3">
			<h1 class="btntext2"> Contact </h1>
			</div>
		</div>
	<div class="grid_19"  id="canvas">
			<div class="navbar navbar center" style="margin-top:15px;">
				<div class="navbar-inner" style="background: #6BBE44;"> 
					<div class="container" style="width:700px;">
						<ul class="nav">
							<li> <img src="graphics/logo.png"/> </li>
							<li class="subnavlist"> Portfolio </li>
							<li class="linebreak"></li>
							<li class="subnavlist"> Marketplace</li>
							<li class="linebreak"></li>
							<li class="subnavlist"> My Pad </li>
							<li class="linebreak"></li>
							<li class="navlist">
					<form class="navbar-search" action="search.php" method="get">
						<input type="hidden" name="PHPSESSID" value=""/>
						<input type="text" style="" name="searchterm" placeholder="Search"/> 
					</form> </li>
			</ul>
			</div>
		</div>
	</div>


				<div id="container1">

				<?php //insert whatever calls images ?>
				<div class="masonryImage">
					<img src="pics/pic1.jpg"/>
				</div>
				<div class="masonryImage">
					<img src="pics/pic2.jpg"/>
				</div>
				<div class="masonryImage">
					<img src="pics/pic3.jpg"/>
				</div>
				<div class="masonryImage">
					<img src="pics/pic4.jpg"/>
				</div>
				<div class="masonryImage">
					<img src="pics/pic5.jpg"/>
				</div>
				<div class="masonryImage">
					<img src="pics/pic6.jpg"/>
				</div>
				<div class="masonryImage">
					<img src="pics/pic7.jpg"/>
				</div>
				<div class="masonryImage">
					<img src="pics/pic8.jpg"/>
				</div>
				<div class="masonryImage">
					<img src="pics/pic9.jpg"/>
				</div>
				<div class="masonryImage">
					<img src="pics/pic10.jpg"/>
				</div>
				<div class="masonryImage">
					<img src="pics/pic11.jpg"/>
				</div>
				<div class="masonryImage">
					<img src="pics/pic12.jpg"/>
				</div>
				<div class="masonryImage">
					<img src="pics/pic13.jpg"/>
				</div>
				<div class="masonryImage">
					<img src="pics/pic14.jpg"/>
				</div>
				<div class="masonryImage">
					<img src="pics/pic15.jpg"/>
				</div>
				<div class="masonryImage">
					<img src="pics/pic16.jpg"/>
				</div>


		
		</div>
  <script type="text/javascript">

    $(document).ready(function() {

        var $container = $('#container1');
          $container.imagesLoaded(function(){
            $container.masonry({
              itemSelector : '.masonryImage',
              columnWidth : 250     //Added gutter to simulate margin
          });
        });

    });
  </script>


		</div>
	</div>			







</body>
</html>	

