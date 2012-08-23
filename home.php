<!DOCTYPE HTML>
	<head>
	<title>The World's First Social Photography Marketplace </title>
	<link href="css/bootstrapNew.css" rel="stylesheet" type="text/css"/>
	<link href="css/new.css" rel="stylesheet" type="text/css"/>
	<link href="css/reset.css" rel="stylesheet" type="text/css"/>
	<link href="css/960_24_col.css" rel="stylesheet" type="text/css"/>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>

	<script type="text/javascript" src="http://masonry.desandro.com/jquery.masonry.min.js"></script>
<script src="js/bootstrap-dropdown.js"></script>
	<style type="text/css">

	</style>
	</head>
<body style="background:rgb(245,245,245);">
	<!--Navbar is here-->
<div class="nav">
		<div class="navbar-inner">
			<div class="container" style="height:44px;">
				<ul class="nav">
					<li style="float:left;margin-top:.15em;"> <img src="graphics/logo.png"/></li>
					<li class="dropdown" style="font-size:16px;color:#fff;font-family:helvetica neue,helvetica,arial, sans-serif;float:right;margin-top:.75em;font-weight">
						<!--dropdown stuff that noah gets later-->
						Sign in <b class="caret"> </b>
					</li>	
				</ul>
			</div>
		</div>
	</div>
<div class="container_24">
	<div class="grid_24 pull_3">
		<div class="grid_24">
		</div>
		<div class="grid_17" style="float:left;"><!--images go here-->
		
		</div>
			<div class="grid_16" id="image_column">
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
				<div class="masonryImage">
					<img src="pics/pic17.jpg"/>
				</div>
				<div class="masonryImage">
					<img src="pics/pic18.jpg"/>
				</div>
				<div class="masonryImage">
					<img src="pics/pic19.jpg"/>
				</div>
				<div class="masonryImage">
					<img src="pics/pic20.jpg"/>
				</div>
				<div class="masonryImage">
					<img src="pics/pic21.jpg"/>
				</div>
					<div class="masonryImage">
					<img src="pics/pic22.jpg"/>
				</div>

					<div class="masonryImage">
					<img src="pics/pic23.jpg"/>
				</div>

					<div class="masonryImage">
					<img src="pics/pic24.jpg"/>
				</div>

					<div class="masonryImage">
					<img src="pics/pic25.jpg"/>
				</div>

					<div class="masonryImage">
					<img src="pics/pic26.jpg"/>
				</div>
									<div class="masonryImage">
					<img src="pics/pic27.jpg"/>
				</div>
					<div class="masonryImage">
					<img src="pics/pic28.jpg"/>
				</div>

					<div class="masonryImage">
					<img src="pics/pic29.jpg"/>
				</div>

					<div class="masonryImage">
					<img src="pics/pic30.jpg"/>
				</div>

					<div class="masonryImage">
					<img src="pics/pic31.jpg"/>
				</div>

					<div class="masonryImage">
					<img src="pics/pic32.jpg"/>
				</div>
					<div class="masonryImage">
					<img src="pics/pic33.jpg"/>
				</div>
					<div class="masonryImage">
					<img src="pics/pic34.jpg"/>
				</div>
					<div class="masonryImage">
					<img src="pics/pic35.jpg"/>
				</div>
					<div class="masonryImage">
					<img src="pics/pic36.jpg"/>
				</div>
					<div class="masonryImage">
					<img src="pics/pic37.jpg"/>
				</div>
					<div class="masonryImage">
					<img src="pics/pic38.jpg"/>
				</div>
					<div class="masonryImage">
					<img src="pics/pic39.jpg"/>
				</div>

					<div class="masonryImage">
					<img src="pics/pic40.jpg"/>
				</div>
	</div>
		</div>
		<div class="grid_5 pull_1" style="float:right;margin-top:15px;"><!--sign up form container-->
			<div class="grid_14">
				<h1 id="sign_up_header"> We make <span style="font-weight:400;font-size:40px;"> buying and selling photos  </span>as <span style="font-weight:400;font-size:40px;"> easy </span>as it is to share them </h1>
		</div>
		<div class="grid_14">
			<h1 id="subheader"> (You can share your photos here too) </h1>
			<div class="grid_14"><!--contains searchbar-->
				<h1> Search the simple marketplace</h1>
				<div class="grid_14">
				<form action="post">
					<input id="search_bar" name="search" type="text"/>
					<div id="search_glass">
						<img src="graphics/glass.png"/> 
					</div>


				</div>
			</div>		
			<div class="grid_12"><!--contains form-->
				<h1> Sign up to share and sell your photos </h1>
				<form action="post">
					<input id="first_name" name="firstname" type="text" placeholder="First name"/>
					<input id="last_name" name="lastname" type="text" placeholder="Last name"/>
					<input id="email" name="email" type="text" placeholder="Email"/>
					<input id="password" name="password" type="password" placeholder="Password"/>
					<input type="submit"  class="btn btn-primary" value="Sign me up"/>
				</form>
			</div>	
					




	</div>
</div>


  <script type="text/javascript">

    $(document).ready(function() {

        var $container = $('#container1');
          $container.imagesLoaded(function(){
            $container.masonry({
              itemSelector : '.masonryImage',
              columnWidth : 210     //Added gutter to simulate margin
          });
        });

    });
  </script>
</body>
</html>


