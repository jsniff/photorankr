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
<title> Standard Content License Agreement | PhotoRankr</title>

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
<h1 style="font-size:30px;">Standard Content License Agreement</h1>
</br></br>
<p1>
This Agreement governs the terms by which members and clients of PhotoRankr, Inc. obtain the right to use photographic content provided by members of the PhotoRankr community through the web site located at <a href="www.photorankr.com">www.photorankr.com</a> (the &#34;Site&#34;).  This Content License Agreement is in addition to the Terms of Use applicable to the Site.  In the event of any inconsistency between this Agreement and the Terms of Use (both of which are incorporated into this Agreement by reference), the terms of this Agreement shall govern. 
</p1></br></br>
<span style="font-size:20px"><b>1.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Background</u></b></span></br></br>(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	This document contains many important provisions that affect your rights and obligations.  By selecting the correct box at the end of this Agreement and clicking &#34;I Agree&#34; or otherwise signifying your acceptance, you accept this Agreement either for yourself or on behalf of your employer or the entity that is identified as the member account holder, and agree to be bound by its provisions.  If you are accepting on behalf of your employer or the entity that is the member account holder, you represent and warrant that you have full legal authority to bind your employer or such other entity.  If you do not have such authority or you do not accept or agree with these terms, do not accept the Agreement and do not download the Content. </br></br>(b)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	In this Agreement: </br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(i)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<b><i>&#34;you&#34;</i></b> or the <b><i>&#34;Client&#34;</b></i> means you or, if you are accepting on behalf of your employer or member account entity, then &#34;you&#34; means that employer or entity and affiliates; </br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(ii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<b><i>&#34;PhotoRankr&#34;</i></b> or <b><i>&#34;we&#34;</i></b> means PhotoRankr, Inc., operator of the Site; and </br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(iii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<b><i>&#34;Content&#34;</b></i> means any photographic image together with any accompanying material. </br></br>&copy;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	This Agreement is set up as a user-determined document where you will choose to enter into either our standard royalty-free content license (the &#34;Standard License&#34;) or an extended license where one or more of the restrictions of the Standard License are amended for your proposed use of the Content (an &#34;Extended License&#34;).  At the end of this Agreement you will have the opportunity to select a &#34;Standard License&#34; or an &#34;Extended License.&#34;  The options for the Extended License uses are dependent upon the Content and whether the supplier of the Content has opted-in to the extended license options.  If you do not specify an Extended License or there is no Extended License option for the Content you have requested, your download of Content will be subject to the Standard License. </br></br>
<span style="font-size:20px"><b>2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Standard License Terms</u></b></span></br></br>	We hereby grant to you a perpetual, non-exclusive, non-transferable worldwide license to use the Content for the Permitted Uses (as defined below).  Unless the activity or use is a Permitted Use, you cannot do it.  All other rights in and to the Content, including, without limitation, all copyright and other intellectual property rights relating to the Content, are retained by PhotoRankr or the supplier of the Content, as the case may be. </br></br>
<span style="font-size:20px"><b>3.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Permitted Standard License Uses</u></b></span>
</br></br>(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	You may only use the Content for those advertising, promotional and other specified purposes which are Permitted Uses (as defined below).  For clarity, you may not use the Content in products for resale, license, or other distribution, unless: </br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(i)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	the proposed use is allowable under an Extended License which is available for the Content; or </br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(ii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	if the original Content has been fundamentally modified or transformed sufficiently that it constitutes an original work entitling the author or artist to copyright protection under applicable law, and where the primary value of such transformed or derivative work is not recognizable as the Content nor is the Content capable of being downloaded, extracted, or accessed by a third party as a stand-alone file (satisfaction of these conditions will constitute the work as a &#34;Permitted Derivative Work&#34; for the purposes of this Agreement).  For example, you cannot superficially modify the Content, print it on a t-shirt, mug, poster, template or other item, and sell it to others for consumption, reproduction, or re-sale.  These uses will not be permitted as or constitute Permitted Derivative Works.  If there is any doubt that a work is a Permitted Derivative Work, you should either obtain an Extended License or contact <a href="mailto:legal@photorankr.com">legal@photorankr.com</a> for guidance.  Any use of the Content that is not a Permitted Use shall constitute infringement of copyright. </br></br>(b)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Seat Restrictions</u>.  Only you are permitted to use the Content, although you may transfer files containing Content or Permitted Derivative Works to your clients, printers, or ISP for the purpose of reproduction for Permitted Uses, provided that such parties shall have no further or additional rights to use the Content and cannot access or extract it from any file you provide.  You may install and use the Content in only one location at a time, although subject to the Prohibited Uses and the other terms of this Agreement, you are entitled to utilize the Permitted Uses an unlimited number of times.  You may physically transfer the Content and its archives from one location to another, in which case you may use the Content at the new location instead.  If you require the Content to be in more than one location or accessible by more than one person, you must download the Content from the Site for each such use or obtain an Extended License for a multi-seat license for the Content. You may make one (1) copy of the Content solely for back-up purposes, and you must reproduce all proprietary notices on this single back-up copy. </br></br>(c)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Permitted Uses</u>.  Subject to the restrictions described under Prohibited Uses below, the following are &#34;Permitted Uses&#34; of Content: </br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(i)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	advertising and promotional projects, including printed materials, product packaging, presentations, film and video presentations, commercials, catalogues, brochures, promotional greeting cards and promotional postcards (i.e., not for resale or license); </br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(ii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	entertainment applications, such as books and book covers, magazines, newspapers, editorials, newsletters, and video, broadcast and theatrical presentations; </br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(iii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	online or electronic publications, including web pages to a maximum of 1200x800 pixels for image or illustration Content or to a maximum of 640x480 for video Content; </br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(iv)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	prints, posters (i.e., a hardcopy) and other reproductions for personal use or promotional purposes specified in (i) above, but not for resale, license or other distribution; and </br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(v)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	any other uses approved in writing by PhotoRankr. </br></br>	If there is any doubt that a proposed use is a Permitted Use, you should contact <a href="mailto:legal@photorankr.com">legal@photorankr.com</a> for guidance. </br></br><span style="font-size:20px"><b>4.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Standard License Prohibitions</u></b></span></br></br>(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Prohibited Uses</u>.  You may not do anything with the Content that is not expressly permitted in the preceding section or permitted by an Extended License.  By way of example and for greater certainty, the following are &#34;Prohibited Uses&#34; and you may not: </br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(i)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	use the Content in design template applications intended for resale, whether on-line or not, including, without limitation, website templates, Flash templates, business card templates, electronic greeting card templates, and brochure design templates; </br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(ii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	use or display the Content on websites or other venues designed to induce or involving the sale, license or other distribution of &#34;on demand&#34; products, including postcards, mugs, t-shirts, posters, and other items (this includes custom designed websites, as well as sites such as www.cafepress.com); </br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(iii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	use the Content in any posters (printed on paper, canvas or any other media) or other items for resale, license, or other distribution for profit; </br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(iv)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	use any of the Content as part of a trademark, designmark, tradename, business name, service mark, or logo; </br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(v)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	incorporate the Content in any product that results in a re-distribution or re-use of the Content (such as electronic greeting card web sites, web templates and the like) or is otherwise made available in a manner such that a person can extract or access or reproduce the Content as an electronic file; </br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(vi)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	use the Content in a fashion that is considered by PhotoRankr (acting reasonably) as, or under applicable law as, pornographic, obscene, immoral, infringing, defamatory, or libelous in nature, or that would be reasonably likely to bring any person or property reflected in the Content into disrepute; </br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(vii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	use or display any Content that features a model or person in a manner: (1) that would lead a reasonable person to think that such person uses or personally endorses any business, product, service, cause, association, or other endeavor; or (2) except where accompanied by a statement that indicates that the Content is being used for illustrative purposes only and any person depicted in the Content is a model, that depicts such person in a potentially sensitive subject matter, including, but not limited to mental and physical health issues, social issues, sexual or implied sexual activity or preferences, substance abuse, crime, physical or mental abuse or ailments, or any other subject matter that would be reasonably likely to be offensive or unflattering to any person reflected in the Content, unless the Content itself clearly and undisputedly reflects the model or person in such potentially sensitive subject matter in which case the Content may be used or displayed in a manner that portrays the model or person in the same context and to the same degree depicted in the Content itself; </br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(viii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	to the extent that source code is contained within the Content, reverse engineer, decompile, or disassemble any part of such source code; </br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(viiii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	remove any notice of copyright, trademark or other proprietary right from any place where it is on or embedded in the Content; </br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(x)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	sublicense, resell, rent, lend, assign, gift or otherwise transfer or distribute the Content or the rights granted under this Agreement; </br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(xi)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	install and use the Content in more than one location at a time or post a copy of the Content on a network server or web server for use by other users; </br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(xii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	use or display the Content in an electronic format that enables it to be downloaded or distributed via mobile devices or shared in any peer-to-peer or similar file sharing arrangement; </br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(xiii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	use Content identified as &#34;Editorial Use Only,&#34; for any commercial, promotional, endorsement, advertising, or merchandising use.  For clarification, in this Agreement &#34;Editorial Use Only&#34; of Content means use relating to events that are newsworthy or of general interest and expressly excludes any advertorial sections (i.e., sections or supplements featuring brand and/or product names or sections or supplements in relation to which you receive a fee from a third-party advertiser or sponsor); </br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(xiiii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	use the Content for editorial purposes without including the following credit adjacent to the Content or in audio/visual production credits: &#34;� PhotoRankr.com/PhotoRankr Member&#39;s Name&#34; or</br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(xv)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	either individually or in combination with others, reproduce the Content, or an element of the Content, in excess of 500,000 times without obtaining an Extended License, in which event you shall be required to pay an additional royalty fee equal to U.S. $0.01 for each reproduction which is in excess of 500,000 reproductions.  This additional royalty does not apply to advertisements in websites or to broadcast by television, webcast or theatrical production. </br></br><span style="font-size:20px"><b>5.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Excess Reproduction Run</u></b></span></br></br>	In the event you contravene subparagraph 4(a)(xv) above without purchasing an Extended License, you further agree to notify PhotoRankr in the event that you (or a combination of you and others involved with you) reproduce the Content, or an element of the Content in excess of 500,000 times.  Such disclosure notice must be sent to PhotoRankr each and every month after which the Content, or an element of the Content, has been reproduced in aggregate over the term of this Agreement in excess of 500,000 times.  Each such notice must contain the number of reproductions made in any particular month, provided however the first such notice will only be require disclosure of those reproductions which are in excess of 500,000.  PhotoRankr shall invoice you for the fees associated with such excess use and you agree to pay such invoice within 30 days of receipt. </br></br><span style="font-size:20px"><b>6.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Term of Agreement</u></b></br></br></span>(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  	This Agreement is effective until it is terminated.  You can terminate this Agreement by destroying the Content and any Permitted Derivative Works, along with any copies or archives of it or accompanying materials (if applicable), and ceasing to use the Content for any purpose. The Agreement also terminates without notice from PhotoRankr if at any time you fail to comply with any of its terms.  Upon termination, you must immediately:</br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(i)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	cease using the Content and for any purpose; </br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(ii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	destroy or delete all copies and archives of the Content or accompanying materials; and </br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(iii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	if requested, confirm to PhotoRankr in writing that you have complied with these requirements. </br></br>(b)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	PhotoRankr reserves the right to elect at a later date to revoke or amend the license granted by this Agreement and replace the Content with an alternative for any reason.  Upon notice, sent to the address or contact information provided by you for your member account, or such other address as you may advise us in writing to use, from time to time, of such replacement, the license for the replaced Content immediately terminates for any products that do not already exist, and this license automatically applies to the replacement Content.  You agree not to use the replaced Content, or any Permitted Derivative Works, for future products and to take all reasonable steps to discontinue use of the replaced Content, or any Permitted Derivative Works, in products that already exist. </br></br>(c)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	Upon notice from PhotoRankr, or upon your knowledge that any Content is subject to a threatened, potential, or actual claim of infringement of another's right for which PhotoRankr may be liable, you must immediately and at your own expense:</br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(i)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	stop using the Content; </br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(ii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	delete or remove the Content from your premises, computer systems and storage (electronic or physical); and </br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(iii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	ensure that your clients, printers or ISPs do likewise.  PhotoRankr shall provide you with replacement Content (which shall be determined by PhotoRankr in its reasonable commercial judgment) free of charge, but subject to the other terms and conditions of this Agreement. </br></br><span style="font-size:20px"><b>7.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>PhotoRankr Representations and Warranties</u></b></span></br></br>(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	PhotoRankr warrants that, except in respect of Content identified as &#34;Editorial Use Only,&#34;: </br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(i)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	your use of the Content in accordance with this Agreement and in the form delivered by PhotoRankr will not infringe on any copyright, moral right, trademark, or other intellectual property right and will not violate any right of privacy or right of publicity; and</br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(ii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	all necessary model, minor, and/or property releases for use of the Content in the manner authorized under this Agreement have been obtained.  You acknowledge that no releases are generally obtained for Content that is identified as &#34;Editorial Use Only&#34; and that some jurisdictions provide legal protection against a person's image, likeness, or property being used for commercial purposes when they have not provided a release.  For Content identified as &#34;Editorial Use Only,&#34; PhotoRankr neither grants any right nor makes any warranty with regard to the use of names, people, trademarks, trade dress, logos, registered, designs, or works of art or architecture depicted therein.  In such cases, you shall be solely responsible for determining whether release(s) is/are required in connection with any proposed use of the Content identified as &#34;Editorial Use Only,&#34; and shall be responsible for obtaining such release(s). </br></br>(b)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	While we have made reasonable efforts to correctly categorize, keyword, caption, and title the Content, PhotoRankr does not warrant the accuracy of such information.  Additionally, PhotoRankr does not warrant the accuracy of any metadata that may be provided with the Content. </br></br>(c)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>DISCLAIMER</u>.  OTHER THAN AS EXPRESSLY PROVIDED IN SECTION 7(a), THE CONTENT IS PROVIDED &#34;AS IS&#34; WITHOUT REPESENTATION, WARRANTY, OR CONDITION OF ANY KIND, EITHER EXPRESS OR IMPLIED, INCLUDING, BUT NOT LIMITED TO THE IMPLIED REPRESENTATIONS, WARRANTIES OR CONDITIONS OF MERCHANTABILITY, OR FITNESS FOR A PARTICULAR PURPOSE.  PHOTORANKR DOES NOT REPRESENT OR WARRANT THAT THE CONTENT WILL MEET YOUR REQUIREMENTS OR THAT ITS USE WILL BE UNINTERRUPTED OR ERROR FREE.  THE ENTIRE RISK AS TO THE QUALITY AND PERFORMANCE OF THE CONTENT IS WITH YOU.  SHOULD THE CONTENT PROVE DEFECTIVE, YOU (AND NOT PHOTORANKR) ASSUME THE ENTIRE RISK AND COST OF ALL NECESSARY CORRECTIONS.</br></br>(d)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	Certain jurisdictions do not allow the exclusion of implied warranties, so the above exclusion may not apply to you.  You have specific rights under this warranty, but you may have others, which vary from jurisdiction to jurisdiction. </br></br><span style="font-size:20px"><b>8.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>PhotoRankr Indemnification and Limitation of Liability</u></b></span></br></br>(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	Provided that the Content is only used in accordance with this Agreement and you are not otherwise in breach of this Agreement and as your sole and exclusive remedy for breach of the representations and warranties set forth in Section 7(a) above, PhotoRankr shall, subject to the terms of Sections 8(b), (c), (d) and (e) defend, indemnify and hold harmless you, your parent, subsidiaries and affiliates and respective directors, officers and employees from all damages, liabilities and expenses (including reasonable outside legal fees), arising out of or connected with any actual or threatened lawsuit, claim, or legal proceeding alleging that the possession, distribution or use of the Content by you is in breach of the representations and warranties set forth in Section 7(a) above.  The foregoing states PhotoRankr&#39;s entire indemnification obligation under this Agreement. </br></br>(b)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	The indemnification set out in Section 8(a) above is conditioned on your prompt notification in writing to PhotoRankr of such claim and our right to assume the handling, settlement or defense of any claim or litigation.  You agree to cooperate with PhotoRankr in the defense of any such claim or litigation and shall have the right to participate in such litigation at your sole expense.  PhotoRankr shall not be liable for legal fees and other costs incurred prior to the notice of the claim. </br></br>(c)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>DISCLAIMER</u>.  IN NO EVENT SHALL PHOTORANKR OR ANY OF ITS AFFILIATES OR CONTENT PROVIDERS OR THEIR RESPECTIVE DIRECTORS, OFFICERS, EMPLOYEES, SHAREHOLDERS, PARTNERS, OR AGENTS BE LIABLE FOR ANY INCIDENTAL, INDIRECT, PUNITIVE, EXEMPLARY, OR CONSEQUENTIAL DAMAGES WHATSOEVER (INCLUDING DAMAGES FOR LOSS OF PROFITS, INTERRUPTION, LOSS OF BUSINESS INFORMATION, OR ANY OTHER PECUNIARY LOSS) IN CONNECTION WITH ANY CLAIM, LOSS, DAMAGE, ACTION, SUIT, OR OTHER PROCEEDING ARISING UNDER OR OUT OF THIS AGREEMENT, INCLUDING WITHOUT LIMITATION YOUR USE OF, RELIANCE UPON, ACCESS TO, OR EXPLOITATION OF THE CONTENT, OR ANY PART THEREOF, OR ANY RIGHTS GRANTED TO YOU HEREUNDER, EVEN IF WE HAVE BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES, WHETHER THE ACTION IS BASED ON CONTRACT, TORT (INCLUDING NEGLIGENCE), INFRINGEMENT OF INTELLECTUAL PROPERTY RIGHTS OR OTHERWISE.  NO ACTION, REGARDLESS OF FORM OR NATURE, ARISING OUT OF THIS AGREEMENT MAY BE BROUGHT BY OR ON BEHALF OF YOU MORE THAN TWO (2) YEARS AFTER THE CAUSE OF ACTION FIRST AROSE. </br></br>(d)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	NOTWITHSTANDING ANY OTHER TERM HEREIN, PHOTORANKR SHALL NOT BE LIABLE FOR ANY DAMAGES, COSTS OR LOSSES ARISING AS A RESULT OF MODIFCATIONS MADE TO THE CONTENT BY YOU OR THE CONTEXT IN WHICH THE CONTENT IS USED BY YOU. </br></br>(e)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	NOTWITHSTANDING ANYTHING ELSE IN THIS AGREEMENT, THE TOTAL MAXIMUM AGGREGATE LIABILITY OF PHOTORANKR UNDER THIS AGREEMENT AND ANY OTHER AGREEMENT UNDER WHICH YOU HAVE LICENSED THE SAME CONTENT, REGARDLESS OF THE FILE SIZE, OR THE USE OR EXPLOITATION OF ANY OR ALL OF THE CONTENT IN ANY MANNER WHATSOEVER AND THE OBLIGATION OF PHOTORANKR UNDER SECTION 8(a) SHALL BE LIMITED TO AN AGGREGATE OF FIVE HUNDRED ($500) U.S. DOLLARS.  FOR GREATER CLARITY, PHOTORANKR&#39;S LIABILITY TO YOU IN RESPECT OF THE CONTENT SHALL NOT EXCEED FIVE HUNDRED ($500) U.S. DOLLARS, REGARDLESS OF THE NUMBER OF TIMES THAT YOU LICENSE THE SAME CONTENT FROM PHOTORANKR. </br></br>(f)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	SOME JURISDICTIONS DO NOT ALLOW FOR THE LIMITATION OR EXCLUSION OF LIABILITY FOR INCIDENTAL OR CONSEQUENTIAL DAMAGES, SO THE ABOVE LIMITATION OR EXCLUSION MAY NOT APPLY TO YOU. </br></br><span style="font-size:20px"><b>9.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Your Indemnification</u></b></span></br></br>	You agree to indemnify, defend and hold PhotoRankr, its affiliates, its Content providers, and their respective directors, officers, employees, shareholders, partners, and agents (collectively, the &#34;PhotoRankr Parties&#34;) harmless from and against any and all claims, liability, losses, damages, costs and expenses (including reasonable legal fees on a solicitor and client basis) incurred by any PhotoRankr Party as a result of or in connection with any breach or alleged breach by you or anyone acting on your behalf of any of the terms of this Agreement. </br></br><span style="font-size:20px"><b>10.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Miscellaneous</u></b></span></br></br>(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Incorporation</u>.  You specifically agree and acknowledge that you have, in addition to the terms of this Agreement, reviewed the terms of the Membership Agreement and Terms of Use and any other agreements which may be incorporated by reference therein, and to the extent of their incorporation in this Agreement you agree to be bound by them. </br></br>(b)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Waiver</u>.  PhotoRankr&#39;s failure to insist upon or enforce strict performance of any provision of this Agreement shall not be construed as a waiver of any provision or right. </br></br>(c)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Nonassignability</u>.  This Agreement is personal to you and is not assignable by you without PhotoRankr&#39;s prior written consent.  PhotoRankr may assign this Agreement without your consent to any other party so long as such party agrees to be bound by its terms. </br></br>(d)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Tax Liability</u>.  You agree to pay and be responsible for any and all sales taxes, use taxes, value added taxes and duties imposed by any jurisdiction as a result of the license granted to you, or of your use of the Content, pursuant to this Agreement. </br></br>(e)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Invalid Provisions</u>.  If any provision of this Agreement is found to be invalid or otherwise unenforceable under any applicable law, such invalidity or unenforceability shall not be construed to render any other provisions contained herein as invalid or unenforceable, and all such other provisions shall be given full force and effect to the same extent as though the invalid or unenforceable provision were not contained herein.</br></br>(f)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Applicable Law</u>.  This Agreement shall be governed by and construed in accordance with the laws of the State of Delaware without regard to the conflict of law principles of Delaware or any other jurisdiction.  This Agreement will not be governed by the United Nations Convention on Contracts for the International Sale of Goods, the application of which is expressly excluded.  You consent to service of any required notice or process upon you by registered mail or overnight courier with proof of delivery notice, addressed to the address or contact information provided by you at the time the Content was downloaded, or such other address as you may advise us in writing to use, from time to time.</br></br>(g)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Jurisdiction</u>.  Each party irrevocably and unconditionally submits to the non-exclusive jurisdiction of the courts of the State of Delaware.  Each party waives any right it has to object to an action being brought in those courts, to claim that the action has been brought in an inconvenient forum or to claim that those courts do not have jurisdiction.</br></br>(h)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Entire Agreement</u>.	This Agreement contains all the terms of the license agreement and no terms or conditions may be added or deleted unless made in writing and signed by an authorized representative of both parties.  In the event of any inconsistency between the terms contained herein and other agreements you have with PhotoRankr, the terms of this Agreement shall govern.</br></br>(i)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Section Headings</u>.  The descriptive headings of this Agreement are for convenience only and shall be of no force or effect in construing or interpreting any of the provisions of this Agreement.</br></br><span style="font-size:20px"><b>11.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Contact</u></b></span></br></br>	If you have concerns relating to this Agreement, please contact PhotoRankr at <a href="mailto:legal@photorankr.com">legal@photorankr.com</a>.  </br></br><span style="font-size:20px"><b>12.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Acknowledgement</u></b></span></br></br>	YOU ACKNOWLEDGE THAT YOU HAVE READ THIS AGREEMENT, UNDERSTAND IT, AND HAD AN OPPORTUNITY TO SEEK INDEPENDENT LEGAL ADVICE PRIOR TO AGREEING TO IT.  IN CONSIDERATION OF PHOTORANKR AGREEING TO PROVIDE THE CONTENT, YOU AGREE TO BE BOUND BY THE TERMS AND CONDITIONS OF THIS AGREEMENT.  YOU FURTHER AGREE THAT IT IS THE COMPLETE AND EXCLUSIVE STATEMENT OF THE AGREEMENT BETWEEN YOU AND PHOTORANKR, WHICH SUPERSEDES ANY PROPOSAL OR PRIOR AGREEMENT, ORAL OR WRITTEN, AND ANY OTHER COMMUNICATION BETWEEN YOU AND PHOTORANKR RELATING TO THE SUBJECT OF THIS AGREEMENT. 
</br></br>
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

