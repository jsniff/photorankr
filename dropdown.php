<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title> This is a drop dowm </title>
<link rel="stylesheet" type="text/css" href="css/bootstrapNew.css"/>
<link rel="stylesheet" type="text/css" href="css/all.css"/>
<style type="text/css">

.navbar-inner
{
	text-align:center;
	background-image:url('graphics/gradient.png');
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
.topcenter
{
	margin-top:.05%;
	margin-bottom:.05%;
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
background-image: url('graphics/glass.png');
background-position: 14.60em 2px;
background-size:1.4em 1.4em;
background-repeat: no-repeat;
}
.marginL
{
	margin-left:1em;
}
.marginT
{
	margin-top:-.3em;
}
.margint
{
	margin-top:.1em;
}
.notifications
{
	width:1.8em;
	height:1.8em;
	border-radius:.9em;
	background:#efefef;
}
</style>
</head>
<body>
	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container" style="height:40px;width:1040px;">
				<ul class="nav" style="height:40px;">
					<li class="topcenter"> <a href="index.php"> <img src="graphics/PRlogowithtext.png" style="positon:fixed;top:.05em;left:1em;width:150px;height:35px;margin-top:-5px;"/></a></li>
					<li class=" margint"> <form class="navbar-search"  action="/market/#search" method="get">
<input class="search margint marginl" style="height:1em;padding-right:25px;margin-left:5em;margin-right:5.5em"name="searchterm" type="text">
</form></li>
					<li class="marginL"> <a href="home.php"> Home </a> </li>
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
						<li class="dropdown topcenter" id="accountmenu">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"> Market</b> </a>
							<ul class="dropdown-menu">
								<li> <a href="newest.php"> Marketplace </a></li>
								<li> <a href="trending.php"> Campaigns </a></li>
							</ul>
						</li>
						<li class="dropdown"  id="accountmenu">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"> <div class="notifications"> 1 </div> </a>
								<ul class="dropdown-menu" style="margin-top:-.16em;">
									<li> <a href="magiclogoutfunction.php"> notify me </a> </li>
									<li class="divider"></li>
									<li> <a href="magiclogoutfunction.php"> notify me </a> </li>
									<li class="divider"></li>
									<li> <a href="magiclogoutfunction.php"> notify me </a> </li>
									<li class="divider"></li>
									<li> <a href="magiclogoutfunction.php"> notify me </a> </li>
									<li class="divider"></li>
									<li> <a href="settingsthings.php"> so many notifications!  </a> </li>
								</ul>	
							</li>
						<li class="dropdown topcenter marginT" id="accountmenu">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"> <img src="images/$profilepic.jpg" style="width:30px;height:30px;"/> Noah Willard </a>
								<ul class="dropdown-menu" style="margin-top:-.36em;">
									<li> <a href="magiclogoutfunction.php"> Settings </a> </li>
									<li class="divider"></li>
									<li> <a href="settingsthings.php"> Log Out </a> </li>
								</ul>	
							</li>	
									
					</ul>			
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