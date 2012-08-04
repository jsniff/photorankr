<?php

//connect to the database
require "db_connection.php";

//start the session
session_start();

require "functionscampaigns3.php"; 
    // if login form has been submitted
    if (htmlentities($_GET['action']) == "login") { 
        login();
    }
    else if(htmlentities($_GET['action']) == "logout") { 
        logout();
    }

?>

<!DOCTYPE html>

<html>
<head>

	<link rel="stylesheet" href="css/bootstrapnew2.css" type="text/css" />
    <link rel="stylesheet" href="css/reset.css" type="text/css" />
    <link rel="stylesheet" href="css/text.css" type="text/css" />
    <link rel="stylesheet" href="css/960_24.css" type="text/css" />
    <link rel="stylesheet" href="css/index.css" type="text/css"/> 
    <link rel="stylesheet" href="css/itunes.css" type="text/css"/> 
	<link rel="stylesheet" type="text/css" href="css/all.css"/>
	
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
    <script src="http://twitter.github.com/bootstrap/1.4.0/bootstrap-twipsy.js"></script>
    <script src="http://twitter.github.com/bootstrap/1.4.0/bootstrap-popover.js"></script>
    <script src="bootstrap-dropdown.js" type="text/javascript"></script>
    <script src="bootstrap-collapse.js" type="text/javascript"></script>
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
    
    <!--Navbar Dropdowns-->
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

.statoverlay

{
opacity:.0;
filter:alpha(opacity=40);
z-index:1;
transition: opacity .5s;
-moz-transition: opacity .5s;
-webkit-transition: opacity .5s;
-o-transition: opacity .5s;
}
     
.statoverlay:hover
{
opacity:.7;

</style>

</head>

<body style="overflow-x:hidden;">

<?php navbarnew(); ?>

<div class="container_24">    
<br /><br /><br />    
<div class="grid_19 push_1" style="margin-top:50px;text-align:justify;line-height:1.48;padding-right:20px;border-right:1px solid #aaa;">
<h1>Terms and Conditions of Use</h1>
</br></br>
<p1>
PLEASE READ THESE TERMS AND CONDITIONS OF USE CAREFULLY.  BY ACCESSING OR USING THIS WEB SITE, YOU AGREE TO BE BOUND BY THESE TERMS OF USE.
</p1>

</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><i>&#34;Image Provider&#34;</b></i> means a person or entity who submits images in response to a Campaign Tender for review and consideration by the Campaign Tender Holder;
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><i>&#34;Image Provider Award Amount&#34;</b></i> means an amount equivalent to a percentage of the Award Amount that is payable to the Image Provider; 
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><i>&#34;Intellectual Property Rights&#34;</b></i> include all intellectual property rights and industrial property rights throughout the world, including rights in respect of, or in connection with: (1) any Confidential Information; (2) copyright (including future copyright and rights in the nature of or analogous to copyright); (3) right of integrity, rights of attribution, and other rights of an analogous nature which may now exist or which may exist in the future (moral rights); (4) inventions (including patents); (5) trademarks; (6) service marks; and (7) designs; (8) whether or not now existing and whether or not registered or capable of registration and includes any right to apply for the registration of such rights and includes all renewals and extensions;
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><i>&#34;Model Release&#34;</b></i> &#34;Image Provider Award Amount&#34; means a written release signed by or on behalf of any living person or the estate of a deceased person who is depicted in whole or in part in any photographs;
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><i>&#34;Property Release&#34;</b></i> &#34;Image Provider Award Amount&#34; means a written release from the owner and/or occupier of any property that is depicted in whole or in part in any photographs;
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><i>&#34;Site&#34;</b></i> means www.photorankr.com or any other website which is operated by PhotoRankr, and includes the whole or any part of the web pages located at www.photorankr.com (including, but not limited to, any elements of design, underlying code, text, sounds, graphics, animated elements or any other content); 
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><i>&#34;Standard Content License&#34;</b></i> means a licensing type in which the licensee is entitled to use the photographs subject to the Standard Content License Agreement;
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><i>&#34;Success Fee&#34;</b></i> means an amount equivalent to a percentage of the Award Amount that is payable to PhotoRankr; 
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><i>&#34;Tender&#34;</b></i> means a Campaign Tender held by the Campaign Tender Holder on the Site, pursuant to which prospective Image Providers submit images for review and consideration for purchase; 
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><i>&#34;Tender Completion&#34;</b></i> means the date which is the earlier of the following: (1) the date upon which a Campaign Tender Holder selects an image or images which satisfy the requirements of the Campaign Tender Holder as set out in the Image Description; or (2) the date upon which a Campaign Tender closes; 
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><i>&#34;Tender Holder&#34;</b></i> means the person or entity that hosts a Campaign Tender relating to a specific request for imagery, pursuant to which prospective Image Providers submit images; 
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><i>&#34;Terms&#34;</b></i> means these Terms of Use; and
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><i>&#34;User&#34;</b></i> means any User of this Site, including Campaign Tender Holder or an Image Provider.
</br></br>


</br></br>


</br></br>


<span style="font-size:20px"><b>8.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>User Conduct</u></b></span>



&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	In accordance with the Digital Millennium Copyright Act and other applicable law, PhotoRankr has adopted a policy of terminating, in appropriate circumstances and at PhotoRankr&#39;s sole discretion, account holders who are deemed to be repeat copyright infringers.  PhotoRankr may also, at its sole discretion, limit access to the Site and/or terminate any account holders who infringe any intellectual property rights of others, whether or not there is any repeat infringement.  


<span style="font-size:20px"><b>13.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Campaign Service</u></b></span>
(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	PhotoRankr provides as part of the Services a service whereby Campaign Tender Holders and Image Providers may participate in a Campaign Tender process for the purchase and sale of images in accordance with the terms in this section, the terms of an Intellectual Property License Agreement associated with a Campaign Tender, the Terms of Use in this document, or any other policy or procedure communicated by PhotoRankr from time to time.

<span style="font-size:20px"><b>14.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Marketplace Service</u></b></span>
(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	The terms in this section govern the PhotoRankr Marketplace service, whereby Users can post digital photographs and elect sell a Standard Content License Agreement and Extended Content License Provisions in connection with such photographs.  

<span style="font-size:20px"><b>15.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Trademarks</u></b></span>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	&#34;PHOTORANKR,&#34; PhotoRankr, photorankr.com, the look and feel of the Site, and other PhotoRankr graphics, logos, designs, page headers, button icons, scripts, and any other Product or Service names, logos, or slogans of PhotoRankr are registered trademarks, trademarks, or trade dress of PhotoRankr (collectively, <i><b>&#34;PhotoRankr&#39;s Marks&#34;</i></b>).  PhotoRankr&#39;s Marks may not be copied, imitated, or used without the prior express written permission of PhotoRankr.  PhotoRankr&#39;s trademarks and trade dress may not be used in connection with any product or service without the prior express written consent of PhotoRankr.

<span style="font-size:20px"><b>16.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Links</u></b></span>
(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	The Services may provide, or third parties may provide, links to other World Wide Web sites or resources.  PhotoRankr provides these links to you only as a convenience, and the inclusion of any link does not imply affiliation or endorsement of any site or any information contained therein.  Because PhotoRankr has no control over such sites and resources, you acknowledge and agree that PhotoRankr is not responsible for the availability of such external sites or resources, and neither endorses nor is responsible or liable for any content, advertising, products, or other materials on, or available from, such sites or resources.  You further acknowledge and agree that PhotoRankr shall not be responsible or liable, directly or indirectly, for any damage or loss caused or alleged to be caused by, or in connection with use of or reliance on, any such content, goods, or services available on or through any such site or resource.  When you leave the Site, you should be aware that PhotoRankr&#39;s terms and policies no longer govern.

<span style="font-size:20px"><b>17.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>DISCLAIMER OF WARRANTIES</u></b></span>
(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	THE SITE, THE SITE MATERIALS, THE PRODUCTS AND THE SERVICES ARE PROVIDED ON AN &#34;AS IS&#34; AND &#34;AS AVAILABLE&#34; BASIS WITHOUT WARRANTIES OF ANY KIND, EXPRESS OR IMPLIED.  TO THE FULL EXTENT PERMISSIBLE BY APPLICABLE LAW, PHOTORANKR DISCLAIMS ALL OTHER WARRANTIES, EXPRESS OR IMPLIED, INCLUDING, WITHOUT LIMITATION, IMPLIED WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, TITLE AND NONINFRINGEMENT AS TO THE SITE, THE SITE MATERIALS, THE PRODUCTS AND THE SERVICES.

<span style="font-size:20px"><b>18.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>LIMITATION OF LIABILITY</u></b></span>
(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	IN NO EVENT SHALL PHOTORANKR OR ITS DIRECTORS, MEMBERS, EMPLOYEES, OR AGENTS BE LIABLE FOR ANY DIRECT, SPECIAL, INDIRECT, OR CONSEQUENTIAL DAMAGES, OR ANY OTHER DAMAGES OF ANY KIND, INCLUDING, BUT NOT LIMITED TO, LOSS OF USE, LOSS OF PROFITS OR LOSS OF DATA, WHETHER IN AN ACTION IN CONTRACT, TORT OR OTHERWISE, ARISING OUT OF OR IN ANY WAY CONNECTED WITH THE USE OF OR INABILITY TO USE OR VIEW THE SITE, THE SERVICES, THE PRODUCTS, THE CONTENT, OR THE SITE MATERIALS CONTAINED IN OR ACCESSED THROUGH THE SITE, INCLUDING ANY DAMAGES CAUSED BY OR RESULTING FROM YOUR RELIANCE ON ANY INFORMATION OBTAINED FROM PHOTORANKR, OR THAT RESULT FROM MISTAKES, OMISSIONS, INTERRUPTIONS, DELETION OF FILES OR E-MAIL, ERRORS, DEFECTS, VIRUSES, DELAYS IN OPERATION OR TRANSMISSION, OR ANY TERMINATION, SUSPENSION OR OTHER FAILURE OF PERFORMANCE, WHETHER OR NOT RESULTING FROM ACTS OF GOD, COMMUNICATIONS FAILURE, THEFT, DESTRUCTION OR UNAUTHORIZED ACCESS TO PHOTORANKR&#39;S RECORDS, PROGRAMS OR SERVICES.

<span style="font-size:20px"><b>19.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Indemnity</u></b></span>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	You hereby agree to indemnify and hold harmless PhotoRankr, its affiliated and associated companies, and their respective directors, officers, employees, agents, representatives, independent and dependent contractors, licensees, successors, and assigns from and against all claims, losses, expenses, damages, and costs (including, but not limited to, direct, incidental, consequential, exemplary, and indirect damages), and reasonable attorneys&#39; fees, resulting from, or arising out of: (1) a breach of these Terms; (2) Content posted on the Site; (3) the use of the Services, by you or any person using your account or PhotoRankr Username and password; (4) the sale or use of your Content; or (5) any violation of any rights of a third party.

<span style="font-size:20px"><b>20.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Termination</u></b></span>
(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	PhotoRankr may terminate or suspend any and all Services and/or your PhotoRankr account immediately, without prior notice or liability, for any reason whatsoever, including without limitation if you breach the Terms.  If you violate the Terms of Use, PhotoRankr in its sole discretion may: (1) require you to remedy any violation thereof, and/or (2) take any other actions that PhotoRankr deems appropriate to enforce its rights and pursue available remedies.  Upon termination of your account, your right to use the Services will immediately cease.  

<span style="font-size:20px"><b>21.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Applicable Law</u></b></span>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	Your use of the Site is subject to all applicable local, state, national, and international laws and regulations.  The Terms of Use and your use of the Site shall be governed by and construed in accordance with the laws of the State of Delaware, as if made within Delaware between two residents thereof, without resort to Delaware&#39;s conflict of law provisions.  You agree that any action at law or in equity arising out of or relating to these Terms of Use shall be filed only in the state and federal courts located in Kent County, Delaware and you hereby irrevocably and unconditionally consent and submit to the exclusive jurisdiction of such courts over any suit, action or proceeding arising out of these Terms of Use.

<span style="font-size:20px"><b>22.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Miscellaneous</u></b></span>
(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	No agency, partnership, joint venture, or employment is created as a result of the Terms, and you do not have any authority of any kind to bind PhotoRankr in any respect whatsoever.  
</div>

<div class="grid_4 push_2" style="margin-top:50px;">
<p1 style="font-size:20px"><b>General</b></p1>
</br>
</br>
<ul>
<li><a href="http://photorankr.com/market/terms.php">Terms of Use</a></li>
</br>
<li><a href="http://photorankr.com/market/privacy.php">Privacy Policy</a></li>
</br>
<li><a href="http://photorankr.com/market/legal.php">Standard Content License Agreement</a></li>
</br>
<li><a href="http://photorankr.com/market/extended.php">Extended Content License Provisions</a></li>
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

<?php footer(); ?>

</div>  <!--end of container-->

</body>
</html>	
