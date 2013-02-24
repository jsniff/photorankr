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
<html>
<head>
<meta charset="UTF-8">
<title> Extended Content License Provisions | PhotoRankr</title>

<meta property="og:image" content="http://photorankr.com/<?php echo $image; ?>">
   <meta name="Generator" content="EditPlus">
  <meta name="Author" content="PhotoRankr, PhotoRankr.com">
  <meta name="Keywords" content="photos, sharing photos, photo sharing, photography, photography club, sell photos, sell photography, where to sell my photography, good sites for selling photography, making money from photography, making money off photography, social networking, social network, social networks, where to sell my photos, good sites for selling photos, good site to sell photos, making money from photos">
  <meta name="Description" content="PhotoRankr allows photographers of all skill levels to sell and share their work. Create your photostream cutomized to what you want to see. Add photos to your favorites, rank them, and watch them trend. Build your portfolio with Photorankr.">

<link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
<link rel="stylesheet" type="text/css" href="css/bootstrapNew.css"/>
<link rel="stylesheet" type="text/css" href="css/reset.css"/>
<link rel="stylesheet" type="text/css" href="css/all.css"/>
<link rel="stylesheet" type="text/css" href="css/reset.css"/>
<link rel="stylesheet" type="text/css" href="css/style.css"/>
<link rel="stylesheet" type="text/css" href="css/960_24_col.css"/>
<link rel="stylesheet" type="text/css" href="css/main3.css"/>

  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script src="bootstrap.js" type="text/javascript"></script>
  <script src="bootstrap-dropdown.js" type="text/javascript"></script>
  <script src="bootstrap-collapse.js" type="text/javascript"></script>
  <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>

     <script src="bootstrap-dropdown.js" type="text/javascript"></script>
     <script src="bootstrap-collapse.js" type="text/javascript"></script>
     
     <script type="text/javascript">
  $(function() {
  // Setup drop down menu
  $('.dropdown-toggle').dropdown();
 
  // Fix input element click problem
  $('.dropdown input, .dropdown label').click(function(e) {
    e.stopPropagation();
  });
});
     </script>

<style type="text/css">

.navbar-inner
{
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
background-image: url('noahsimages/glass.png');
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


<body style="overflow-x:hidden;">
<?php include_once("analyticstracking.php") ?>

<?php navbar(); ?>

<div class="container_24">    
<br /><br /><br />    
<div class="grid_19 push_1" style="margin-top:50px;text-align:justify;line-height:1.48;padding-right:20px;border-right:1px solid #aaa;">
<h1 style="font-size:30px;">Extended Content License Provisions</h1></br></br>
<span style="font-size:20px"><b>1.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Background</u></b></span></br></br>(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	This document explains the Extended License options that PhotoRankr offers.  Please carefully read the complete terms of each provision on the Full Size Photo Download page.  In each case where the Standard Content License Agreement is amended by the terms of an Extended License, all other terms and conditions of the Agreement remain in full force and effect, including all Prohibited Uses.</br></br>(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Reproduction and Print Run Limits</u>.  The Standard Content License Agreement limits the amount of times you may print the Content to 500,000 reproductions.  By purchasing this extension, you can make an unlimited number of reproductions.</br></br>(b)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Multi-Seat Licenses</u>.  This option allows you to extend usage of the Content to more than one person within your organization.</br></br>(c)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Items for Resale - Limited Run</u>.  You may purchase Extended Licenses allowing uses of the Content in items for resale, license, or other distribution, including:</br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(i)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	up to 100,000 cards, stationery items, stickers, or paper products;</br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(ii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	up to 10,000 posters, calendars, mugs, or mousepads; or</br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(iii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	up to 2,000 t-shirts, apparel items, games, toys, entertainment goods, or framed artwork.</br></br>
<span style="font-size:20px"><b>2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Electronic Items for Resale - Unlimited Run</u></b></span></br></br>	You may also purchase the option to resell the Content in an unlimited number of electronic templates for e-greeting or similar cards, electronic templates for web or applications development, PowerPoint or Keynote templates, screensavers, and email or brochure templates.</br></br>
<span style="font-size:20px"><b>3.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Extended Legal Guarantee</u></b></span>
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	PhotoRankr&#39;s liability under the Standard Content License Agreement or the Standard Audio Content License Agreement (and any other agreement under which you license the same content) shall be increased from an aggregate of U.S. $500 to an aggregate of U.S. $1,000.</br></br>
<span style="font-size:20px"><b>4.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Legal Provisions</u></b></span></br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	The following are the legal provisions that give effect to the various Extended License options.  These must be read in conjunction with the Standard Content License Agreement, but are set out below for convenience.</br></br>(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Reproduction and Print Run Limits</u>.  Notwithstanding the restriction contained in section 4(a)(xv) of the Standard Content License Prohibitions limiting you to 500,000 reproductions, you shall be entitled with respect to this Content to an unlimited number of reproductions, and the Agreement is deemed amended in that respect.  All other terms and conditions of the Agreement remain in full force and effect, including all Prohibited Uses.</br></br>(b)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Multi-Seat Licenses</u>.  Notwithstanding the restriction contained in section 4(a)(xi) of the Standard Content License Prohibitions limiting you to a single seat or location to use the Content, you shall be entitled with respect to this Content to an unlimited number of seats or users of the Content within your organization, provided all such users are either employees or agree with us to be bound by the Agreement, and that you remain liable for all use by such additional users. The Agreement is hereby deemed amended in that respect. All other terms and conditions of the Agreement remain in full force and effect, including all Prohibited Uses.</br></br>(c)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Items for Resale - Limited Run</u>.  Notwithstanding the restriction contained in section 4(a) of the Standard Content License Prohibitions prohibiting the use or display of the Content in items for resale, you shall be entitled with respect to this specific Content to produce the following items for resale, license, or other distribution:</br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(i)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	up to 100,000 postcards, greeting cards, or other cards, stationery, stickers, and paper products,</br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(ii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	up to 10,000 posters, calendars, or other similar publications, mugs or mousepads,</br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(iii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	or up to 2,000 t-shirts, sweatshirts, or other apparel, games, toys, entertainment goods, framed or mounted artwork in or on which the Content is used or displayed (the &#34;Resale Merchandise&#34;), provided that:</br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;		(1)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	the right to produce the Resale Merchandise in no way grants any right to you or any recipient of the Resale Merchandise in any intellectual property or other rights to the Content;</br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;		(2)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	you agree to indemnify the PhotoRankr Parties from any cost, liability, damages, or expense incurred by any of them relating to or in connection with any of the Resale Merchandise;</br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;		(3)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	any production of Resale Merchandise in excess of the allowed run size is prohibited and requires the Content to be purchased separately; and</br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;		(4)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	all other terms and conditions of the Agreement remain in full force and effect, including all Prohibited Uses.</br></br>(d)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Electronic Items for Resale or Other Distribution - Unlimited Run</u>.  Notwithstanding the restriction contained in section 4(a) of the Standard Content License Prohibitions prohibiting the use or display of the Content in items for resale, you shall be entitled with respect to this specific Content to produce an unlimited number of the following items for resale, license or other distribution:</br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(i)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	electronic templates for e-greeting or similar cards, electronic templates for web or applications development, PowerPoint or Keynote templates, screensavers, and email or brochure templates in or on which the Content is used or displayed (the &#34;E-Resale Merchandise&#34;), provided that:</br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;		(1)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	the right to produce the E-Resale Merchandise in no way grants any rights to you or any recipient of the E-Resale Merchandise in any intellectual property or other rights to the Content;</br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;		(2)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	you agree to indemnify the PhotoRankr Parties from any cost, liability, damages, or expense incurred by any of them relating to, or in connection with, any of the E-Resale Merchandise; and</br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;		(3)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	you agree to indemnify the PhotoRankr Parties from any cost, liability, damages, or expense incurred by any of them relating to, or in connection with, any of the E-Resale Merchandise.</br></br>	(d)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Extended Legal Guarantee</u>.  The references to &#34;FIVE HUNDRED ($500) U.S. DOLLARS&#34; in Section 8(e) of the Standard Content License Agreement shall be deleted in their entirety and replaced with &#34;ONE THOUSAND ($1,000) U.S. DOLLARS.&#34;   </br></br>
</div>

<div class="grid_4 push_2" style="margin-top:50px;">
<p1 style="font-size:20px"><b>General</b></p1>
</br>
</br>
<ul>
<li><a href="http://photorankr.com/terms.php">Terms of Use</a></li>
</br>
<li><a href="http://photorankr.com/privacy.php">Privacy Policy</a></li>
</br>
<li><a href="http://photorankr.com/legal.php">Standard Content License Agreement</a></li>
</br>
<li><a href="http://photorankr.com/extended.php">Extended Content License Provisions</a></li>
</ul> 
</br>
<p1 style="font-size:20px"><b>Releases</b></p1>
</br>
</br>
<ul>

<!--Legal Release Download Forms-->
<form id="legal_form_model" method="post" action="downloadrelease.php">
<input type="hidden" name="doc" value="legaldocs/Model_Release_6_24_2012.pdf">
</form>

<form id="legal_form_property" method="post" action="downloadrelease.php">
<input type="hidden" name="doc" value="legaldocs/Property_Release_6_24_2012.pdf">
</form>


<li><a href="javascript:{}" onclick="document.getElementById('legal_form_model').submit(); return false;">Model Release</a></li>
</br>
<li><a href="javascript:{}" onclick="document.getElementById('legal_form_property').submit(); return false;">Property Release</a></li>
</br>
</ul> 
</div>

</div>  <!--end of container-->

<?php footer(); ?>

</body>
</html>	

