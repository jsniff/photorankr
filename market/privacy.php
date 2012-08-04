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
<h1>Privacy Policy</h1>
</br></br>
<p1>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We at photorankr.com, a product of PhotoRankr, Inc., understand that our users are concerned about how their personal information is collected and used. Please be assured that we take privacy very seriously and are committed to safeguarding personal information. This notice describes our Privacy Policy, which covers the personal information that we collect at the photorankr.com site (the "Site"). By visiting or using our Site, you accept the practices described in this Privacy Policy. 
</p1></br></br>
<span style="font-size:20px"><b>1.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>What Types of Personal Information is Collected by PhotoRankr</u>?</b></span></br></br>We collect and maintain personal information on our Site including:
</br></br>
(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <u>Membership Registration Information</u>. We collect the information you supply when you become a Member of the Site, including your name, e-mail address, and the password you select. You may edit this information at any time by logging into your profile page.
</br></br>(b)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <u>Preferences and Suggestions</u>. We collect information and suggestions that you give to us, including information about your preferences.
</br></br>(c)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <u>Publicly Posted Information</u>. We collect information you post on the Site, which is accessible by anyone with Internet access.
</br></br>
(d)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <u>Internet Protocol Address</u>. We also collect and store the Internet Protocol ("IP") addresses of individuals that visit our site. An IP address provides general statistical information, such as browsing activity, areas of greatest interest on the site, general demographic information, and other basic information that we will use for system administration. We use such information to make the Site more interesting and useful to you. In the future, this may include helping advertisers on our site design advertisements our users might like. We normally do not combine this type of information with personally identifiable information such as membership registration information. However, we will combine this information with personally identifiable information to identify a visitor in order to enforce compliance with our Terms and Conditions of Use or to protect our Site, services, Members, and other users, or others.
</br></br>(e)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <u>Financial Information</u>. If you become a Content Provider with the Site or purchase certain services through the Site, you may be required to provide financial information in the form of a valid bank routing number and routing number or credit card number and billing address.</br></br>
<span style="font-size:20px"><b>2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Does PhotoRankr use Cookies</u>?</b></span></br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cookies are packets of information that are stored by your web browser on your computer hard drive during visits to our site. Like other companies, we use cookies for a variety of purposes. Cookies enable us to recognize your browser and save your preferences or passwords. Cookies also allow us to track statistical information that helps us to provide improved resources and services to users. Web browsers usually accept cookies automatically. However, you can change your web browser to prevent automatic acceptance of cookies or disable cookies. If your web browser does not accept cookies, you will not be able to take advantage of some of the site's features or make purchases through the website.</br></br>
<span style="font-size:20px"><b>3.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>How Does PhotoRankr Use My Personal Information</u>?</b></span>
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We use personal information to provide and enhance services, respond to inquiries, and provide personalized content. Our users&#39; names, e-mail addresses, and other information is stored in our database. We may also use your personal information to track user activity so that we may better understand your preferences. We may also use your personal information to contact you about promotions, products, or services that we believe may be of interest to you. If you prefer not to be contacted with this information, please send an e-mail to <a href="mailto:support@photorankr.com">support@photorankr.com</a>. We also store financial information in our database and we may use such financial information to bill you for future membership fees and/or services in accordance with the Terms and Conditions of Use. 
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We also use non-personally identifying information to improve the design and content of our site. We may also use this information to analyze site usage.
</br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We only keep your personal information for as long as it remains relevant or as otherwise required by law. We will disclose information we maintain when required to do so by law or where reasonably necessary to protect our rights or a third party's rights, for example, in response to a court order, a subpoena, a request by a law enforcement agency, or to respond to claims that any content violates the right of third parties.
</br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Your contact information will be made available to the photographer who controls an image when you purchase/license the image.
</br></br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Although we will strive to take appropriate measures to safeguard against unauthorized disclosures of information, we cannot assure you that personally identifiable information that we collect will never be disclosed in a manner inconsistent with this Privacy Policy. Inadvertent disclosures may occur. 
</br></br>
<span style="font-size:20px"><b>5.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Does PhotoRankr Give Personal Information to Third Parties</u>?</b></span></br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We will only share your personal information with those who provide services to the Site. Those persons do not have any right to use your personal information beyond what is necessary to assist us. We will not sell, trade, or otherwise transfer your personal information to any third party. This does not include trusted third parties who assist us in operating our Site, conducting our business, or servicing you, so long as those parties agree to keep this information confidential. We may use a third party credit card payment processing company to bill you for services.
</br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;In the event that PhotoRankr, Inc. is involved in a bankruptcy, merger, acquisition, reorganization, or sale of assets, your information may be sold or transferred as part of such transaction. This Privacy Policy will apply to your information as transferred to the new entity.  We strive to be in full compliance with the California Online Privacy Protection Act at all times. As such, we do not distribute your personal information to third parties without your consent, and all Members of the Site may make changes to their profile and registration information at any time by logging into their profile page.
</br></br>
<span style="font-size:20px"><b>6.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>What Happens if I Disclose My Personal Information in Public Areas on the Site</u>?</b></span></br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We have no control over and cannot protect personal information that users disclose in public areas such as a photographer&#39;s profile. If you disclose your personal information in public areas, it may be collected and used by third parties, without our or your knowledge.</br></br>

<span style="font-size:20px"><b>7.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Is My Personal Information Secure if I Link to Other Web Sites</u>?</b></span></br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The photorankr.com site contains links to sites operated by third parties that are not under the control or supervision of photorankr.com. Neither photorankr.com nor its directors, officers, employees, shareholders, members, or representatives are responsible for the privacy practices of these sites. Once you have left our site, our privacy policy no longer applies. You must read the privacy policy of the other site to see how your personal information will be handled on the third party site.</br></br>

<span style="font-size:20px"><b>8.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>How Does PhotoRankr Protect My Personal Information</u>?</b></span></br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;All personal information is stored in our database. Access to personal information is limited to those individuals who are authorized to use such information for business and administrative purposes. There are some things that you can do to help protect the security of your information as well. For instance, never give out your password, since this is what is used to access all of your account information. Also, remember to sign out of your account and close your browser window when you finish surfing the Internet, so that other people using the same computer won't have access to your information.
</br></br>

<span style="font-size:20px"><b>9.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>How Does PhotoRankr Protect the Privacy of Children</u>?</b></span></br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Our Site and Services are not directed to persons under the age of 13. If you become aware that your child has provided us with personal information without your consent, please contact us at <a href="mailto:privacy@PhotoRankr.com">privacy@photorankr.com</a> Consistent with the Children&#39;s Online Privacy Protection Act, we do not knowingly collect personal information from children. If we become aware that a child under the age of 13 has provided us with personal information, we take steps to remove such information and terminate the child&#39;s account. You can find additional resources for parents and teens about online privacy from the U.S. Federal Trade Commission <a href="http://business.ftc.gov/privacy-and-security/children%E2%80%99s-privacy">here</a>.
</br></br>

<span style="font-size:20px"><b>10.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Can I Update or Change the Personal Information that PhotoRankr has Collected</u>?</b></span></br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Members can access, review, and edit their profile and registration information at any time by logging into their member page.
</br></br>

<span style="font-size:20px"><b>11.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Will the PhotoRankr Privacy Policy Change</u>?</b></span></br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We may change and modify the Privacy Policy at any time. All changes become effective immediately. Notice of changes may be provided to you by posting the effective date at the top of the Privacy Policy page, by e-mail to your e-mail address or in other ways. Your continued use of the Site after such modifications constitutes your acknowledgment of, and agreement to be bound by, the amended Privacy Policy. Please review the Privacy Policy prior to entering personal information on the site.
</br></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We invite you to contact us with any questions or comments regarding your personal information. Please contact us at <a href="mailto:privacy@PhotoRankr.com">privacy@photorankr.com</a> if you have any questions regarding your privacy.
</br></br>
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

