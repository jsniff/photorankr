<?php

//connect to the database
require "db_connection.php";
require "functions.php";
$firstname = addslashes($_REQUEST['firstname']);
        $firstname = trim($firstname);
        $firstname = ucwords($firstname);
         $lastname = addslashes($_REQUEST['lastname']);
        $lastname = trim($lastname);
        $lastname = ucwords($lastname);
        $newemail = addslashes($_REQUEST['email']);
        $newemail= trim($newemail);
        $password = addslashes($_REQUEST['password']);
        $password = trim($password);

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
        
    //ACTION SIGNUP
    
    if($_GET['action'] == "signup") { //if they tried to sign up from signin.php
	$firstname = addslashes($_REQUEST['firstname']);
    $firstname = trim($firstname);
    $firstname = ucwords($firstname);
	$lastname = addslashes($_REQUEST['lastname']);
    $optin = addslashes($_REQUEST['optin']);
    $lastname = trim($lastname);
    $lastname = ucwords($lastname);
	$newemail = mysql_real_escape_string($_REQUEST['emailaddress']);
	$password = mysql_real_escape_string($_REQUEST['password']);
	$confirmpassword = mysql_real_escape_string($_REQUEST['confirmpassword']);
	$terms = mysql_real_escape_string($_REQUEST['terms']);
	$mattfollow = "'support@photorankr.com'";
	$originalfave = "'userphotos/paintedbuilding1.jpg'";
	$originalfave = addslashes($originalfave);
	$mattfollow = addslashes($mattfollow);
	$check = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$newemail'");
	$others = mysql_num_rows($check);
    $currenttime = time();
    $ip = $_SERVER['REMOTE_ADDR'];
    
    // Create a unique  activation code
    $activation = md5(uniqid(rand(), true));
                        
	//if they forgot to enter any information
	if(!$_REQUEST['firstname'] or !$_REQUEST['lastname'] or !$_REQUEST['emailaddress'] or !$_REQUEST['password'] or !$_REQUEST['confirmpassword'] or !$_REQUEST['terms']) {
		mysql_close();
        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=signup3.php?error=1">';
        exit();
	}
	else if($password != $confirmpassword) { //if passwords dont match
		mysql_close();
        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=signup3.php?error=2">';
        exit();
	}
	//else if that email address is already in the database
	else if($others != 0) {
		header("Location: lostpassword.php");
	}
	else {
		//put their info in database
        $settinglist = " emailcomment emailreturncomment emailfave emailfollow ";
		$newuserquery = "INSERT INTO userinfo (firstname, lastname, emailaddress, password, following, faves, settings, promos, time, ip, activation) VALUES ('$firstname', '$lastname', '$newemail', '$password', '$mattfollow', '$originalfave','$settinglist','$optin','$currenttime','$ip','$activation')";
		mysql_query($newuserquery);
        
        //Activation Email
        $message = " To activate your PhotoRankr account, please click on this link:\n\n";
        $message .= "https://www.photorankr.com" . '/activate.php?email=' . urlencode($newemail) . "&key=$activation";
        mail($newemail, 'Registration Confirmation', $message, 'From:PhotoRankr <photorankr@photorankr.com>');

        
         //newsfeed query
        $type = "signup";
        $newsfeedsignupquery=mysql_query("INSERT INTO newsfeed (firstname, lastname, emailaddress,type,time) VALUES ('$firstname', '$lastname', '$newemail','$type','$currenttime')");
        
        //SEND REGISTRATION GREETING
        
        $to = $newemail;
        $subject = 'Welcome to PhotoRankr!';
        $message = 'Thank you for signing up with PhotoRankr! You can now upload your own photos and sell them at your own price, follow the best photographers, and become part of a growing community. If you have any questions about PhotoRankr or would like to suggest an improvement, you can email us at photorankr@photorankr.com. We greatly value your feedback and hope you will spread the word about PhotoRankr to your friends by referring them to the site with the link below:
        
		http://photorankr.com/referral.php        

		Again, welcome to the site!

		Sincerely,
		PhotoRankr';
        $headers = 'From:PhotoRankr <photorankr@photorankr.com>';
        mail($to, $subject, $message, $headers);  
        
        //start the session
        @session_start();

        login();

        $email = $_SESSION['email'];
            
    }
}

?>

<html>
<header
	<link href = "css/main2 2.css" rel="stylesheet" type="text/css"/>
<style type="text/css">
.scroll
	{
		position:relative !important;
		margin:-45px 0 0 0 !important;
	}
#formContainer:hover
{
	position:relative !important;
		margin:-45px 0 0 0 !important;
}
</style>

<script type="text/javascript">
	function formSubmit()
    {
        document.getElementById("signUpBtn").submit();
    }
</script>

</header>	

		<hgroup>
			<header> 
				<img src="graphics/logo_big.png">		
			</header>
			<h1> Sign Up Free</h1>
		</hgroup>
        
         <?php if($_GET['error'] == 2) {echo'<div style="font-size:16px;text-align:center;font-weight:400;color:red;padding-bottom:10px;">Oops, your passwords do not match</div>';} 
            elseif($_GET['error'] == 1) {echo'<div style="font-size:16px;text-align:center;font-weight:400;color:red;padding-bottom:10px;">Please fill out all of the information</div>';} 
            if($_GET['action'] == "signup") {echo'<div style="font-size:16px;text-align:center;font-weight:500;color:green;padding-bottom:10px;">Please check your email to finish registration, thank you.</div>';} 
        ?>
        
		<fieldset id="signUp">
            <form id="signUp" action="indexform.php?action=signup" method="post">		
				<label> First Name</label>
				<input type="text" placeholder="First Name" name="firstname" value = "<?php echo $firstname; ?>" />
				<label> Last Name </label>
				<input  type="text"  placeholder="Last Name" name="lastname" value = "<?php echo $lastname; ?>" />
				<label> Email Address </label>
				<input  type="text"  placeholder="Email Address" name="emailaddress" value = "<?php echo $newemail; ?>" />
				<label> Password </label>
				<input type="password"  placeholder="Password" name="password" value = "<?php echo $password; ?>" />
				<label> Confirm Password </label>
				<input type="password"  placeholder="Confirm Password" name="confirmpassword" name="confirmpassword" value = "<?php echo $confirmpassword; ?>" />
				<div id="TNC" style="width:86%;height:60px;overflow-y:scroll;overflow-x:hidden;background:rgba(231,231,231,.9);padding:5px;
			margin-left:19px;margin-top:10px;border-radius:5px;text-align:left;"> 

<!--TERMS--> 

<p>
Terms and Conditions of Use </p>				
<p1>
PLEASE READ THESE TERMS AND CONDITIONS OF USE CAREFULLY.  BY ACCESSING OR USING THIS WEB SITE, YOU AGREE TO BE BOUND BY THESE TERMS OF USE.
</p1>
</br></br>

<span style="font-size:20px"><b>1.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Background</u></b></span>
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Welcome to www.photorankr.com.  PhotoRankr is an online photography community and marketplace.  This document sets forth the Terms of Use <i><b>(&#34;Terms&#34;)</i></b> of the PhotoRankr website.  These Terms apply to your access to, and use of, the Site, your use of any digital photography, and other services provided on or through www.photorankr.com, as well as to your purchase of any digital image downloads.  Before using any of the PhotoRankr services, you are required to read, understand and agree to these terms.  You may only create an account after reading and accepting these terms.  These Terms of Use do not alter in any way the terms or conditions of any other agreement you may have with PhotoRankr.  You should frequently review these Terms of Use and any other applicable policies or guidelines on the Site.  
</br>
</br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PhotoRankr reserves the right to change any provision of these Terms of Use, and any other terms, policy, or guideline governing your use of the Site, at any time in its sole discretion. Such changes will be effective immediately upon posting such revisions on the Site, and you waive any right you may have to receive specific notice of such changes.  Your continued use of this Site or the Services or the purchase of any Products following the posting of such changes will confirm your acceptance thereof.  You should frequently review these Terms of Use and any other applicable policies or guidelines on the Site.
</br></br>
<span style="font-size:20px"><b>2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Definitions</u></b></span> 
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><i>&#34;PhotoRankr&#34;</i></b> or the <b><i>&#34;Client&#34;</b></i> means PHOTORANKR, INC a corporation of the State of Delaware, with registered agent located at 160 Greentree Drive, Suite 101, Dover, Delaware 19904; 
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><i>&#34;Award Amount&#34;</i></b> or <b><i>&#34;we&#34;</i></b> means the amount to be paid by the Campaign Tender Holder for an image or images, as the case may be, as specified in a Campaign;
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><i>&#34;Extended Content License&#34;</b></i> means a licensing type in which the licensee is entitled to use the photographs subject to the Extented Content License Provisions;
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

<span style="font-size:20px"><b>3.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>&#34;Acceptance of Terms&#34;</u></b></span>
</br></br>
(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	The web pages available at PhotoRankr.com and all linked pages <i><b>(&#34;Site&#34;)</i></b>, are owned and operated by PHOTORANKR, INC. <i><b>(&#34;PhotoRankr&#34;)</i></b>, a corporation of the State of Delaware, with registered agent located at 160 Greentree Drive, Suite 101, Dover, Delaware 19904, and are accessed by you under the Terms of Use described below.
</br></br>
(b)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	Please thoroughly and carefully read these terms before using the Services provided by PhotorRankr and the Site.  By accessing the site, viewing any content or using any Services available on the site (as described throughout) you are agreeing to be bound by these terms that govern our relationship with you in relation to the site.  If you disagree with any part of the terms, then you may not access the site. 
</br></br>
(c)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	PhotoRankr reserves the right, at its sole discretion, to modify or replace the Terms of Use, and any other terms, policy, or guideline governing your use of the Site at any time.  Such changes will be effective immediately upon posting such revisions on the Site.  If the alterations constitute a material change to the terms, PhotoRankr will notify you either by posting an announcement on the site or contacting you at the e-mail address linked to your account at the time of the modification or replacement.  PhotoRankr has sole discretion to determine what constitutes a material change.  You shall be responsible for reviewing and becoming familiar with any such modifications.  If you disagree with any modifications to the terms, it is your sole responsibility to immediately discontinue use of the site.  Using any service or viewing any content following notification of a material change to the terms shall constitute your acceptance of the Terms, as modified.  
</br></br>

<span style="font-size:20px"><b>4.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Description of Services</u></b></span>
</br></br>
(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	The Site is an online social photography community and marketplace that enables photographers to post photographs, rank each other&#39;s photographs, provide feedback on each other&#39;s photographs through comments, opinions, and other social media features, promote their work, participate in contests and promotions such as Campaign Tenders, and access and/or purchase other services from time to time made available on the Site (collectively, <i><b>&#34;Services&#34;</i></b>).  Services include, but are not limited to, any of the service and/or Content PhotoRankr makes available to or performs for you, as well as the offering of any materials displayed, transmitted, or performed on the Site or through the Services.  Content <i><b>(&#34;Content&#34;)</i></b> includes, but is not limited to, photographs posted by Users, User comments, messages, text, information, data, graphics, news articles, images, illustrations, and software.
</br></br>
(b)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	Your access to, and use, of the Site may be interrupted from time to time as a result of equipment malfunction, updating, maintenance, or repair of the Site, or any other reason within or outside the control of PhotoRankr.  PhotoRankr reserves the right to suspend or discontinue the availability of the Site and/or any Service and/or remove any Content at any time at its sole discretion and without prior notice.  PhotoRankr may also impose limits on certain features and Services or restrict your access to parts of, or all of, the Site and the Services without notice or liability.  You should not sue or rely upon the Site for storage of your photographs and images, and you are directed to retain your own copies of all Content posted on the Site.
</br></br>

<span style="font-size:20px"><b>5.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Registration and Your Account</u></b></span>
</br></br>
(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	As a condition to using the Services, you are required to open an account with PhotoRankr, provide your name and a valid e-mail address, select a password, and to provide other registration information <i><b>(&#34;Registration Data&#34;)</i></b>.  The Registration Data you provide must be accurate, complete, and current at all times, as PhotoRankr may send important notices about your account or the Services to the e-mail address linked to your account.  Failure to provide accurate, complete, and current Registration Data at all times constitutes a breach of the Terms, which may result in immediate termination of your PhotoRankr account and takedown of all Content you posted on the Site.  
</br></br>
(b)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	You may not use as a Username the name of another person or entity, a name that is not lawfully available for use, a name or trademark that is subject to any rights of another person or entity other than you without appropriate authorization, or a name that is otherwise offensive, vulgar, or obscene.
</br></br>
(c)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	Services are available only to individuals who are either: (1) at least 18 years old, or (2) at least 13 years old and authorized to access the Site by a parent or legal guardian.  Services are not available to Users under the age of 13 years old.  If you are a parent or legal guardian and have authorized a minor about the age of 13 years old to use the Site, you are responsible for the online conduct of such minor and the consequences of any misuse of the Site by the minor.  Parents and legal guardians are warned that the Site does allow display of photographs and images containing implied nudity and/or non-gore violence that may be offensive to some.
</br></br>
(d)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	The Services are for use by individuals who are photographers, photography galleries, agents, and other market intermediaries and entities that represent photographers or sell their works.  The Services are also for use by individuals, entities, and corporations seeking to purchase and/or download User Content.   
</br></br>
(e)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	In order to cooperate with legitimate governmental requests, subpoenas or court orders, to protect PhotoRankr&#39;s systems and customers, to ensure the integrity and operation of PhotoRankr&#39;s business and systems, or to perform analytics on the Site, PhotoRankr may access and disclose any information from your account it considers necessary or appropriate, including, without limitation, User profile information (i.e., name, e-mail address, etc.), IP addressing and traffic information, usage history, and posted content.  You consent to such disclosure and agree that PhotoRankr&#39;s right to disclose any such information as described above shall govern over any contrary terms in any agreement or policy of PhotoRankr.  
</br></br>
(f)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	If you do not qualify for registration you are not permitted to open an account or use the Services.  PhotoRankr reserves the right to refuse service to anyone at any time, with or without cause, in its sole discretion. 
</br></br>

<span style="font-size:20px"><b>6.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Account Security</u></b></span>
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	You are solely responsible for maintaining the confidentiality of the password associated with your PhotoRankr account and for restricting access to your password and to your computer while logged into the Site.  You agree to accept responsibility for all activities that occur under your account or from your computer.  PhotoRankr endeavors to use reasonable security measures to protect against unauthorized access to your account and to any Content you post to the Site.  PhotoRankr cannot, however, guarantee absolute security of your account, your Content, or the personal information we collect, and we cannot promise that our security measures will prevent third-party &#34;hackers&#34; from illegally accessing the Site or its contents.  You agree to immediately notify PhotoRankr of any unauthorized use of your account or password, or any other breach of security, and to accept all risks of unauthorized access to the Registration Data and any other information you provide to PhotoRankr.
</br></br>

<span style="font-size:20px"><b>7.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Use of User Information</u></b>
</br></br></span>
(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  	In the event that you at any time obtain access to any PhotoRankr User information, whether directly from PhotoRankr or otherwise, including User names and e-mails (collectively, <i><b>&#34;User Information&#34;</i></b>), you agree that you may not use any such User Information in any manner except as may be specifically authorized by PhotoRankr to carry out the purpose for which such User Information was provided to you.  Without limiting the foregoing, you may not share such User Information with any third parties or use it for any marketing purposes of any kind.  In no event will PhotoRankr be obligated to provide you with any such User Information.  You agree that this provision shall apply both during and after the term of your use of the Site.
</br></br>

<span style="font-size:20px"><b>8.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>User Conduct</u></b></span>
</br></br>
(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	All Content posted or otherwise submitted to the Site is the sole responsibility of the User from which such Content originates and you acknowledge and agree that you, and not PhotoRankr, are entirely responsible for all Content that you post, or otherwise submit to the Site.  PhotoRankr does not control User submitted Content, except in connection with determining the suitability of photographs for sale in Campaign Tenders and/or the Marketplace and, as such, does not guarantee the accuracy, integrity, or quality of such Content.  You understand that by using the Site you may be exposed to Content that is offensive, indecent, or otherwise personally objectionable.
</br></br>
(b)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	As a condition of use, you promise not to use the Services for any purpose that is unlawful or prohibited by these Terms, or any other purpose not reasonably intended by PhotoRankr.  By way of example, and not as a limitation, you agree not to use the Services:
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(i)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	to abuse, harass, threaten, impersonate, or intimidate any person;
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(ii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	to post or transmit, or cause to be posted or transmitted, any Content that is libelous, defamatory, offensive, obscene, profane, pornographic, harassing, threatening, invasive of privacy or publicity rights, abusive, inflammatory, fraudulent, or that infringes any copyright or other right of any person;
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(iii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	to post Content that would constitute, encourage, or provide instructions for a criminal offense, violate the rights of any party, endanger national security, or that would otherwise create liability or violate any local, state, national or international law;
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(iv)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	to post Content that, in the sole judgment of PhotoRankr, is objectionable, harmful, or which restricts or inhibits any other person from using or enjoying the Site, or which may expose PhotoRankr or its Users to any harm or liability of any nature;
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(v)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	to post Content that impersonates any person or entity or otherwise misrepresents your affiliation with a person or entity;
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(vi)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	to promote or sell Content of another person;
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(vii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	for any purpose (including posting or viewing Content) that is not permitted under the laws of the jurisdiction where you use the Services;
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(viii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	to post or transmit, or cause to be posted or transmitted, any communication or solicitation designed or intended to obtain password, account, or private information from any PhotoRankr User;
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(viiii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	to post private information of any third party, including, but not limited to, addresses, phone numbers, e-mail addresses, Social Security numbers, and credit card numbers;
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(x)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	to create or transmit unwanted &#34;spam&#34; to any person or any URL;
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(xi)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	to advertise to, or solicit, any User, through comments, messages, or otherwise, to buy or sell any products or services, or to use any information obtained from the Services in order to contact, advertise to, solicit, or sell to any User without their prior explicit consent;
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(xii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	to create multiple accounts for the purpose of voting for or against Users&#39; photographs or images;
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(xiii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	to artificially inflate or alter vote counts, comments, or any other Service for any purpose, including for the purpose of giving or receiving money or other compensation in exchange for votes, or for participating in any other organized effort that in any way artificially alters the results of Services;
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(xiv)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	to post copyrighted, trademarked, or patented Content that does not belong to you;
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(xv)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	to post Content that is viruses, corrupted data, or other harmful, disruptive, or destructive files;
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(xvi)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	to engage in political campaigning;
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(xvii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	to sell or otherwise transfer your profile;
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(xviii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	to complete, or attempt to complete, any commercial transaction between Users outside of the Site, including but not limited to the sale and purchase of images relating to any Campaign Tender, current or historical, held on the Site;
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(xviiii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	to use any robot, spider, scraper or other automated means to access the Site for any purpose without our express written permission.  Additionally, you agree that you will not: 
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;		(1)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	 take any action that imposes, or may impose, in the sole discretion of PhotoRankr, an unreasonable or disproportionately large load on PhotoRankr&#39;s infrastructure; 
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;		(2)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	interfere or attempt to interfere with the proper working of the Site or any activities conducted on the Site; or 
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;		(3)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	bypass any measures we may use to prevent or restrict access to the Site.
</br></br>
(c)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	To report a suspected abuse of the Site or a breach of the Terms (other than relating to copyright infringement, which is addressed under &#34;Copyright Complaints&#34; below) please send a written notice to PhotoRankr at the following e-mail address: <a href="mailto:legal@photorankr.com">legal@photorankr.com</a>.
</br></br>
(d)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	You are solely responsible for your interactions with other Users of the Site.  PhotoRankr reserves the right, but has no obligation, to monitor and mediate disputes between you and other Users.  Enforcement of the Content or conduct rules set forth above and in these Terms of Use is solely at PhotoRankr&#39;s discretion, and failure to enforce such rules in some instances does not constitute a waiver of our right to enforce such rules in other instances.  In addition, these rules do not create any private right of action on the part of any third party or any reasonable expectation that the Site will not contain any content that is prohibited by such rules.  Although PhotoRankr has no obligation to screen, edit, or monitor any of the Content posted on the Site, PhotoRankr reserves the right, and has absolute discretion, to remove, screen, or edit any Content hosted on the Site at any time and for any reason without notice.  You are solely responsible for creating backup copies of and replacing any Content you host on the Site at your sole cost and expense.
</br></br>

<span style="font-size:20px"><b>9.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Content Submitted or Made Available for Inclusion on the Service</u></b></span>
</br></br>
(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	Please read this section carefully before posting, uploading, or otherwise submitting any Content to the site.  You retain the copyright in any Content you post on the Site.  PHOTORANKR NEITHER HAS NOR WANTS ANY OWNERSHIP OF YOUR CONTENT.  However, by uploading and/or posting any Content to the Site, you grant PhotoRankr a non-exclusive, non-transferable, worldwide, perpetual, and royalty-free license (with the right to sublicense) to use the Content and the information, including name, that is submitted in connection with such Content, as is reasonably necessary to display the Content, provide the Services and to facilitate, at Content Owner&#39;s direction, the license of Photographs or the sale of Products on the Site, without obtaining permission or license from any third party.
</br></br>
(b)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	You understand and acknowledge that any Content contained in public postings or galleries, will be accessible to the public and could be accessed, indexed, archived, linked to, and republished by others including, without limitation, appearing on other web sites and in search engine results.  The appearance of your Content on other web sites and in search engine results is limited to a low-resolution copy with a PhotoRankr watermark that retains EXIF data, if any.  Therefore, you should be careful about the nature of the Content you post.  PhotoRankr will not be responsible or liable for any third party access to, or use of, the Content you post.  
</br></br>
(c)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	You represent and warrant that: (1) you own or otherwise control all of the rights to the Content that you post or transmit, or you otherwise have the right to post, use, display, distribute and reproduce such Content, and to grant the rights granted herein; (2) the Content you supply is accurate and not misleading; and (3) use and posting of the Content you supply does not violate these Terms of Use and will not violate any rights of, or cause injury to, any person or entity.
</br></br>
(d)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	In consideration of PhotoRankr&#39;s agreement to allow you to post Content to the Site, PhotoRankr&#39;s agreement to publish such Content, and for other valuable consideration, the receipt and sufficiency of which are hereby expressly and irrevocably acknowledged, you agree with PhotoRankr as follows:
</br></br>
You acknowledge that: 
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(i)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	by uploading your photographic or graphic works to PhotoRankr you retain full rights to those works that you had prior to uploading;
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(ii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	by posting Content to the Site you hereby grant to PhotoRankr a  non-exclusive, non-transferable, worldwide, perpetual, and royalty-free license (with the right to sublicense) to use the Content and the information, including name, that is submitted in connection with such Content, as is reasonably necessary to display the Content, provide the Services and to facilitate, at Content Owner&#39;s direction, the license of Photographs or the sale of Products on the Site, without obtaining permission or license from any third party.  This license will exist for the period during which the Content is posted on the Site and will automatically terminate upon the removal of the Content from the Site;
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(iii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	the license granted to PhotoRankr includes the right to use your Content fully or partially for promotional reasons and to distribute and redistribute your Content to other parties, web-sites, applications, and other entities, provided such Content is attributed to you in accordance with the credits (i.e., Username, profile picture, photo title, descriptions, tags, and other accompanying information), if any and as appropriate, all as submitted to PhotoRankr by you;
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(iv)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	PhotoRankr makes no representation and warranty that Content posted on the Site will not be unlawfully copied without your consent.  PhotoRankr does not and generally cannot restrict the ability of Users and visitors to the Site to make low-resolution or &#34;thumbnail&#34; copies of Content posted on the Site, and you hereby expressly authorize PhotoRankr to permit Users and visitors to the Site to make such low-resolution copies of your Content, which includes a PhotoRankr watermark and EXIF data, if any; and
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(v)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	subject to the terms of the foregoing license, you retain full ownership or other rights in your Content and any intellectual property rights or other proprietary rights associated with your Content. 
</br></br>
You represent and warrant that: 
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(i)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	you are the owner of all rights, including all copyrights, in and to all Content you submit to the site;
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(ii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	you have the full and complete right to enter into this agreement and to grant to PhotoRankr the rights in the Content herein granted, and that no further permissions are required from, nor payments required to be made to, any other person in connection with the use by PhotoRankr of the Content as contemplated herein; 
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(iii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	the Content does not defame any person and does not infringe upon the copyright, moral rights, publicity rights, privacy rights, or any other right of any person, or violate any law or judicial or governmental order; and
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(iv)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	you shall not have any right to terminate the permissions granted herein, nor to seek, obtain, or enforce any injunctive or other equitable relief against PhotoRankr, all of which such rights are hereby expressly and irrevocably waived by you in favor of PhotoRankr.
</br></br>

<span style="font-size:20px"><b>10.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Copyright and Limited License</u></b></span>
</br></br>
(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	The Site and all images, text, code, and other materials on the Site and the selection and arrangement thereof (collectively, the <i><b>&#34;Site Materials&#34;</i></b>) are the property of PhotoRankr or its licensors or Users and are protected by United States and international copyright laws.
</br></br>
(b)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	PhotoRankr grants you a limited, non-sublicensable, revocable license to access and use the Site solely in accordance with, and subject to, these Terms and any other applicable terms or agreements you may enter into with PhotoRankr.  Except as otherwise expressly permitted in writing, the license does not include, and you agree to refrain from: 
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(i)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	the collection, copying, or distribution of any portion of the Site or the Site Materials;
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(ii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	any resale, commercial use, commercial exploitation, distribution, public performance, or public display of the Site or any of the Site Materials; 
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(iii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	modifying or otherwise making any derivative uses of the Site or any of the Site Materials; 
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(iv)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	scraping or otherwise using any data mining, robots, or similar data gathering or extraction methods; 
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(v)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	with the exception of your own Content, or others&#39; Content available for purchase and download, the downloading of any portion of the Site, the Site Materials, or any information contained therein; or 
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(vi)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	any use of the Site or the Site Materials other than for their intended purposes.
</br></br>
(c)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	Any use of the Site or of any Site Materials other than as specifically authorized herein, without the express prior written permission of PhotoRankr or the Content Owner, is strictly prohibited and will terminate and constitute a breach of the license granted herein.
</br></br>

<span style="font-size:20px"><b>11.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Repeat Infringer Policy</u></b></span>
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	In accordance with the Digital Millennium Copyright Act and other applicable law, PhotoRankr has adopted a policy of terminating, in appropriate circumstances and at PhotoRankr&#39;s sole discretion, account holders who are deemed to be repeat copyright infringers.  PhotoRankr may also, at its sole discretion, limit access to the Site and/or terminate any account holders who infringe any intellectual property rights of others, whether or not there is any repeat infringement.  
</br></br>

<span style="font-size:20px"><b>12.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Copyright Complaints</u></b></span>
</br></br>
(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	PhotoRankr respects the intellectual property rights of others.  It is PhotoRankr&#39;s policy to respond promptly to any claim that Content posted on the Site infringes the copyright or other intellectual property infringement <i><b>(&#34;Infringement&#34;)</i></b> of any person.  PhotoRankr will use reasonable efforts to investigate notices of alleged Infringement and will take appropriate action under applicable intellectual property law and these Terms where it believes an Infringement has taken place, including removing or disabling access to the Content claimed to be infringing and/or terminating accounts and access to the Site.
</br></br>
(b)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	To notify PhotoRankr of a possible Infringement, you must submit a notice of such Infringement in writing to PhotoRankr&#39;s Designated Agent, as set forth below:
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(i)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	Designated Agent:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tyler Sniff
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(ii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Address of Designated Agent:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PhotoRankr, Inc.</br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 284 Gray Mans Loop</br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Pawleys Island, SC 29585
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(iii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	E-mail address of Designated Agent:&nbsp;&nbsp;&nbsp;	<a href="mailto:dmca@photorankr.com">dmca@photorankr.com</a>	
</br></br>
(c)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	Include in your notice a detailed description of the alleged Infringement sufficient to enable PhotoRankr to make a reasonable determination.  Please note that you may be held accountable for damages (including costs and attorneys&#39; fees) for misrepresenting that any Content is infringing your copyright.
</br></br>
(d)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	If we remove or disable access to Content in response to a notice of Infringement, we will make reasonable attempts to contact the User who posted the affected Content.  If you feel that your Content is not infringing, you may provide PhotoRankr with a counter notice in writing to PhotoRankr&#39;s Designated Agent at <a href="mailto:dmca@photorankr.com">dmca@photorankr.com</a>.  You must include in your counter notice sufficient information to enable PhotoRankr to make a reasonable determination.  Please note that you may be held accountable for damages (including costs and attorneys&#39; fees) if you materially misrepresent that your Content is not infringing the copyrights of others.
</br></br>
(e)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	If you are uncertain as to whether an activity constitutes Infringement, we recommended seeking the advice of a professional attorney licensed to practice law.
</br></br>

<span style="font-size:20px"><b>13.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Campaign Service</u></b></span>
</br></br>
(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	PhotoRankr provides as part of the Services a service whereby Campaign Tender Holders and Image Providers may participate in a Campaign Tender process for the purchase and sale of images in accordance with the terms in this section, the terms of an Intellectual Property License Agreement associated with a Campaign Tender, the Terms of Use in this document, or any other policy or procedure communicated by PhotoRankr from time to time.
</br></br>
(b)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	If the User is using the Campaign Services on behalf of the employer of the User, the User warrants they have the authority to agree to these terms on behalf of their employer and acknowledges that both the User and the employer of the User will be bound jointly and severally in relation to these Terms. 
</br></br>
(c)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Posting a Campaign Tender</u>.  A Campaign Tender Holder may initiate a Campaign Tender.  As part of a Campaign Tender, a Campaign Tender Holder will post a detailed description of the requirements of the Campaign Tender Holder on the Site <i><b>(&#34;Campaign Description&#34;)</i></b>.  A Campaign Description may include information including but not limited to the following: (i) the Award Amount; (ii) the number of images required; (iii) the intended use of the image; (iv) the period of time for which the image or images, as the case may be, is or are intended to be used; and (iv) the cut-off date for the provision of images for review and consideration by the Campaign Tender Holder. 
</br></br>
(d)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Responding to a Campaign Tender</u>.  An Image Provider may respond to a Campaign Tender by uploading via the Site high resolution images which satisfy a Campaign Description.  High resolution images will be securely stored and will not be released to the Campaign Tender Holder prior to their selection (&#34;awarding&#34;) by the Campaign Tender Holder.  An image uploaded by an Image Provider will be post-processed to reduce its size and a digital watermark will be added prior to the display of the altered image on the Site for review and consideration by the Campaign Tender Holder.  A Campaign Tender Holder may, but is not obliged to, provide an Image Provider with a ranking or feedback on any images submitted by the Image Provider in response to a Campaign Tender.  Each of the Campaign Tender Holder and Image Provider acknowledge that: 
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(i)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;there is no limit to the number of entries that an Image Provider may submit in response to a Campaign Description; and
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(ii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a Campaign Tender Holder may select multiple entries submitted by one or more Image Providers, as the winning submission or submissions, as the case may be. 
</br></br>
(e)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Image Selection and License Acceptance</u>.  When a Campaign Tender Holder selects for purchase <i><b>(&#34;Awards&#34;)</i></b> an image entered into the Campaign Tender by an Image Provider, the Campaign Tender Holder and Image Provider will be deemed to have entered into a separate binding Intellectual Property License Agreement in relation to the supply of the image by the Image Provider to the Campaign Tender Holder.  Each of the Campaign Tender Holder and Image Provider acknowledge and agree: 
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(i)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;to be bound by the terms of the Intellectual Property License Agreement into which you have been deemed to have entered by virtue of your participation in the Campaign Tender as either Campaign Tender Holder or Image Provider; and
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(ii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;that PhotoRankr shall not be a party to the Intellectual Property License Agreement between the Campaign Tender Holder and Image Provider and shall bear no liability whatsoever in relation to this agreement or any aspect of it. 
</br></br>
(f)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Fees and Charges</u>.  The Campaign Tender Holder acknowledges, confirms and agrees that the costs to be incurred by the Campaign Tender Holder for using the Services are as follows: 
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(i)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;prior to the publication of a Campaign Tender on the Site, the Award Amount for a single image will be authorized by the Campaign Tender Holder to PhotoRankr by credit card;
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(ii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;upon Campaign Tender Completion and award of a single image, the Award Amount for a single image previously authorized will be payable by the Campaign Tender Holder to PhotoRankr by credit card;
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(iii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;upon Campaign Tender Completion and award of multiple images, the total Award Amount for all images selected for purchase will be payable by the Campaign Tender Holder to PhotoRankr by credit card only and this amount will be comprised of the amount previously authorized to be payable as well as an additional amount payable to reach the required total amount;
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(iv)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;the Image Provider acknowledges, confirms and agrees that: (1) within a period of 30 days after Campaign Tender Completion for images purchased by credit card, PhotoRankr will credit the PayPal account of an Image Provider whose image has been awarded an amount equivalent to the Image Provider Award Amount, and (2) PhotoRankr will retain the Success Fee;
</br></br>	
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(v)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	unless published otherwise on the Site registration as a Campaign Tender Holder is free and registration as an Image Provider is free; and
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(vi)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	each of the Image Provider and the Campaign Tender Holder acknowledge, confirm and agree that: (1) PhotoRankr may, in the sole and absolute discretion of PhotoRankr, increase, decrease, modify, alter, introduce or remove any fee charged by PhotoRankr for the Services, either permanently or temporarily; (2) PhotoRankr will provide 30 days notice to existing Image Providers for any prospective change of the fees charged by PhotoRankr for the Services, and these changes will only affect transactions that take place after the 30 day notice period has elapsed; and (3) unless otherwise stated all fees are in U.S. dollars. 
</br></br>

<span style="font-size:20px"><b>14.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Marketplace Service</u></b></span>
</br></br>
(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	The terms in this section govern the PhotoRankr Marketplace service, whereby Users can post digital photographs and elect sell a Standard Content License Agreement and Extended Content License Provisions in connection with such photographs.  
</br></br>
(b)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>License Agreement</u>.	PhotoRankr&#39;s Marketplace enables any person or entity that owns or is otherwise authorized to license photographs (the <i><b>&#34;Content Owner&#34;</i></b>) to post such Content on the Site and to use PhotoRankr&#39;s Marketplace to facilitate the license of such Media by the Content Owner through the Site.  These licenses are made directly between the User and the Content Owner and are merely facilitated by PhotoRankr.  By posting any Content and indicating the Content may be licensed by Site users, Content Owners agree to be bound by and accept the terms and conditions set forth in this section, which are a part of these Terms of Use.  If you license any Media from a Content Owner through the Site, you agree to the terms of the applicable end user license agreement (the Standard Content License Agreeement or Extended Content License Provisions), which are separate documents and part of these Terms of Use.
</br></br>
(c)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Terms of Sale</u>.  These Terms of Sale shall form a part of the Terms of Use applicable to your purchases of digital images and other products from the Site.  Other than as specifically provided in any separate written agreement between you and PhotoRankr, the Terms of Sale may not be altered, supplemented, or amended by the use of any document, such as purchase orders, and all sales are expressly conditioned upon your agreement to these Terms of Sale.  In the event of any conflict between these Terms of Sale and the other provisions of the Terms of Use, these Terms of Sale shall control.
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(i)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<i>Pricing and Payment Terms</i>.  All prices are listed in U.S. dollars and are valid until altered by us.  PhotoRankr currently accepts all major credit cards.  The secure payment processor is Stripe.  PhotoRankr reserves the right to change the payment methods it accepts at any time without notice.  If we are unable to process a payment using your credit card on file, we may, but are under no obligation to, call you using your telephone number on file to ask whether you wish to use an alternative form of payment.  We may terminate your order and take such other action as appropriate if we are unable to process your credit card payment and you do not provide an alternative form of payment.  You agree to reimburse PhotoRankr for any and all costs incurred in collecting amounts owed by you to PhotoRankr, including, without limitation, attorneys&#39; fees and costs of collection agencies.
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(ii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<i>Product Availability and Pricing</i>.  PhotoRankr may revise or discontinue products or services at any time without prior notice, and products may become unavailable even after an order is placed.  All prices are subject to change without notice.
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(iii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<i>Product Descriptions and Errors</i>.  PhotoRankr attempts to be as accurate as possible and eliminate errors on this Site.  However, we do not warrant that product descriptions, photographs, or other Site Materials are accurate, complete, reliable, current, or error-free.  If a product offered by PhotoRankr is not as described or pictured, your sole remedy is to seek a refund within thirty (30) days of receipt.  In the event of an error, whether on the Site, in an order confirmation, in processing an order, download, or otherwise, we reserve the right to correct such error and charge the correct price or cancel the order, and your sole remedy in the event of such error is to cancel your order.
</br></br>
(d)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	THE FOREGOING RIGHT TO RETURN ANY ORDER WITHIN THIRTY (30) DAYS IS YOUR SOLE AND EXCLUSIVE REMEDY, AND PHOTORANKR&#39;S SOLE AND EXCLUSIVE LIABILITY, WITH RESPECT TO THE PURCHASE OF ANY PRODUCTS FROM THE SITE, AND PHOTORANKR EXPRESSLY DISCLAIMS AND EXCLUDED ALL WARRANTIES, EXPRESS OR IMPLIED, REGARDING THE PURCHASE OF ANY PRODUCTS.  CERTAIN STATE LAWS DO NOT ALLOW LIMITATIONS ON IMPLIED WARRANTIES OR THE EXCLUSION OR LIMITATION OF CERTAIN DAMAGES. IF THESE LAWS APPLY TO YOU, SOME OR ALL OF THE ABOVE DISCLAIMERS, EXCLUSIONS, OR LIMITATIONS MAY NOT APPLY TO YOU, AND YOU MIGHT HAVE ADDITIONAL RIGHTS.
</br></br>
(e)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Rights Granted by Content Owner</u>.  By uploading or posting photographs to be licensed to Site users using PhotoRankr&#39;s shopping cart, the Content Owner hereby grants to PhotoRankr the right to facilitate the licensing by Content Owner of Photographs posted on PhotoRankr to a licensee according to the licensing designation�a Standard Content License or Extended Content License Provisions�identified by the Content Owner upon submission of the Photographs.  The Content Owner can designate the applicable licenses in his or her profile.  
</br></br>
(f)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Content Owner Responsibilities</u>.  Content Owner is responsible for all photographs posted to the Site including without limitation the designation of and update of photographs license types, either personal or commercial.
</br></br>
(g)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Content Submission and Licensing</u>.  Photograph submissions to the Site may be rejected or removed at any time for any reason or for no reason in PhotoRankr&#39;s sole discretion.  photographs designated for commercial license should be free from any distinguishable third party names, trademarks, logos, copyright designs, works of art, architecture, or any other depictions requiring additional rights.  Content Owner understands and agrees that all photographs are licensed directly by the Content Owner to the licensee and the license type and the license fees for photographs within the Site will be chosen by Content Owner.  PhotoRankr merely facilitates and enables such license and is not responsible for the Photographs.  So long as Content Owner has agreed to make the photographs available through the Site there will not be any restraint on the licensing of such photographs through the Site to any Site users to the fullest extent possible, according to the type of license provided by the Content Owner.
</br></br>
(h)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Representations and Warranties of Content Owner</u>.  Content Owner represents and warrants to PhotoRankr that:
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(i)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	Content Owner owns (or has legal right to represent and license) all copyright and other rights, title and interest in and to all Photographs submitted to the Site and has the right to grant all licenses granted herein without violating the rights of any third party;
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(ii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	if the Content Owner who is agreeing to this Terms of Use is an agent of the copyright owner(s), then the Content Owner has been granted full authority of the copyright owner(s) to enter into this agreement;
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(iii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	all information provided to PhotoRankr through the Site or by any all other means concerning all photographs, to the best of its knowledge, is true and accurate; and
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(iv)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	all photographs submitted or posted on the Site by Content Owner have all necessary releases and permissions required to grant the licenses granted under the applicable license, including without limitation valid Model Releases for photographs depicting recognizable people (living or dead), and, where reasonably required, Property Releases for photographs depicting private properties, and without limitation all written permission regarding all distinguishable trademarks.
</br></br>
(i)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Commercial Use License for Digital Downloads</u>.  This section sets forth the terms of the license accompanying downloads of digital images that are purchased for commercial use.
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(i)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	The photographer <i><b>(&#34;Content Provider&#34;)</i></b> grants buyer a perpetual, non-exclusive, non-transferable, worldwide license to use the accompanying image(s) for permitted commercial purposes, defined as: (1) advertising, promotion, brochures, packaging; (2) as part of a commercial website for promotional purposes (maximum 800 x 600 pixels) use; (3) prints, posters, flyers, tear sheets for promotional purposes (not for resale); (4) prints, posters, or other commercial display of photographs; (5) magazines, books, newspapers, other printed publications; (6) and video, broadcast, theatrical.
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(ii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	Buyer may not resell, relicense, redistribute without express written permission from Content Provider.  Use as a derivative work, and reselling or redistributing such derivative work is prohibited.  Images may not be used in a pornographic, obscene, illegal, immoral, libelous or defamatory manner.  Images may not be incorporated into trademarks, logos, or service marks.  Images may not be made available for download.
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(iii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	Content Provider retains all rights, license, copyright, title and ownership of the images.
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(iv)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	There is no warranty, express or implied, with the purchase of this digital image.  Neither Content Provider nor PhotoRankr will be liable for any claims, or incidental, consequential or other damages arising out of this license or buyer&#39;s use of the image.
</br></br>
(j)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Personal Use License for Digital Downloads</u>.  This section sets forth the terms of the license accompanying downloads of digital images that are purchased for personal use.  
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(i)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	Photographer (&#34;Content Provider&#34;) grants buyer a non-exclusive perpetual personal-use license to download and copy the accompanying image(s) subject to the following restrictions: (1) this license is for personal use only.  Personal use means non-commercial use of the images for display on personal websites and computers, or making image prints for personal use; (2) the images may not be used in any way whatsoever in which you charge money, collect fees, or receive any form of remuneration; (3) the images may not be used in advertising; and (4) the images may not be resold, relicensed, or sub-licensed.
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	(ii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	Title and ownership, and all rights now and in the future, of and for the images remain exclusively with the Content Provider.
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(iii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	There are no warranties, express or implied.  The images are provided &#34;as is.&#34;  Neither Content Provider nor PhotoRankr will be liable for any third party claims or incidental, consequential or other damages arising out of this license or buyer&#39;s use of the images.  
</br></br>

<span style="font-size:20px"><b>15.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Trademarks</u></b></span>
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	&#34;PHOTORANKR,&#34; PhotoRankr, photorankr.com, the look and feel of the Site, and other PhotoRankr graphics, logos, designs, page headers, button icons, scripts, and any other Product or Service names, logos, or slogans of PhotoRankr are registered trademarks, trademarks, or trade dress of PhotoRankr (collectively, <i><b>&#34;PhotoRankr&#39;s Marks&#34;</i></b>).  PhotoRankr&#39;s Marks may not be copied, imitated, or used without the prior express written permission of PhotoRankr.  PhotoRankr&#39;s trademarks and trade dress may not be used in connection with any product or service without the prior express written consent of PhotoRankr.
</br></br>

<span style="font-size:20px"><b>16.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Links</u></b></span>
</br></br>
(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	The Services may provide, or third parties may provide, links to other World Wide Web sites or resources.  PhotoRankr provides these links to you only as a convenience, and the inclusion of any link does not imply affiliation or endorsement of any site or any information contained therein.  Because PhotoRankr has no control over such sites and resources, you acknowledge and agree that PhotoRankr is not responsible for the availability of such external sites or resources, and neither endorses nor is responsible or liable for any content, advertising, products, or other materials on, or available from, such sites or resources.  You further acknowledge and agree that PhotoRankr shall not be responsible or liable, directly or indirectly, for any damage or loss caused or alleged to be caused by, or in connection with use of or reliance on, any such content, goods, or services available on or through any such site or resource.  When you leave the Site, you should be aware that PhotoRankr&#39;s terms and policies no longer govern.
</br></br>
(b)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	You may create a text hyperlink to the Site, provided such link does not portray PhotoRankr or any of its Products or Services in a false, misleading, derogatory, or otherwise defamatory manner.  PhotoRankr may revoke this limited right at any time.  Further, you may not frame the Site without PhotoRankr&#39;s express written consent.  
</br></br>

<span style="font-size:20px"><b>17.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>DISCLAIMER OF WARRANTIES</u></b></span>
</br></br>
(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	THE SITE, THE SITE MATERIALS, THE PRODUCTS AND THE SERVICES ARE PROVIDED ON AN &#34;AS IS&#34; AND &#34;AS AVAILABLE&#34; BASIS WITHOUT WARRANTIES OF ANY KIND, EXPRESS OR IMPLIED.  TO THE FULL EXTENT PERMISSIBLE BY APPLICABLE LAW, PHOTORANKR DISCLAIMS ALL OTHER WARRANTIES, EXPRESS OR IMPLIED, INCLUDING, WITHOUT LIMITATION, IMPLIED WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, TITLE AND NONINFRINGEMENT AS TO THE SITE, THE SITE MATERIALS, THE PRODUCTS AND THE SERVICES.
</br></br>
(b)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	PHOTORANKR DOES NOT REPRESENT OR WARRANT THAT THE SITE MATERIALS OR THE SERVICES ARE ACCURATE, COMPLETE, RELIABLE, CURRENT OR ERROR-FREE OR THAT THE SITE, ITS SERVERS OR E-MAIL SENT FROM PHOTORANKR OR THE SITE ARE FREE OF VIRUSES OR OTHER HARMFUL COMPONENTS. PHOTORANKR IS NOT RESPONSIBLE FOR TYPOGRAPHICAL ERRORS OR OMISSIONS RELATING TO PRICING, TEXT, OR PHOTOGRAPHS.  PHOTORANKR ALSO MAKES NO REPRESENTATION OR WARRANTY REGARDING THE AVAILABILITY, RELIABILITY OR SECURITY OF THE SITE AND SHALL NOT BE LIABLE FOR ANY UNAUTHORIZED ACCESS TO OR ANY MODIFICATION, SUSPENSION, UNAVAILABILITY, OR DISCONTINUANCE OF THE SITE OR THE PRODUCTS OR SERVICES PROVIDED THEREON.
</br></br>

<span style="font-size:20px"><b>18.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>LIMITATION OF LIABILITY</u></b></span>
</br></br>
(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	IN NO EVENT SHALL PHOTORANKR OR ITS DIRECTORS, MEMBERS, EMPLOYEES, OR AGENTS BE LIABLE FOR ANY DIRECT, SPECIAL, INDIRECT, OR CONSEQUENTIAL DAMAGES, OR ANY OTHER DAMAGES OF ANY KIND, INCLUDING, BUT NOT LIMITED TO, LOSS OF USE, LOSS OF PROFITS OR LOSS OF DATA, WHETHER IN AN ACTION IN CONTRACT, TORT OR OTHERWISE, ARISING OUT OF OR IN ANY WAY CONNECTED WITH THE USE OF OR INABILITY TO USE OR VIEW THE SITE, THE SERVICES, THE PRODUCTS, THE CONTENT, OR THE SITE MATERIALS CONTAINED IN OR ACCESSED THROUGH THE SITE, INCLUDING ANY DAMAGES CAUSED BY OR RESULTING FROM YOUR RELIANCE ON ANY INFORMATION OBTAINED FROM PHOTORANKR, OR THAT RESULT FROM MISTAKES, OMISSIONS, INTERRUPTIONS, DELETION OF FILES OR E-MAIL, ERRORS, DEFECTS, VIRUSES, DELAYS IN OPERATION OR TRANSMISSION, OR ANY TERMINATION, SUSPENSION OR OTHER FAILURE OF PERFORMANCE, WHETHER OR NOT RESULTING FROM ACTS OF GOD, COMMUNICATIONS FAILURE, THEFT, DESTRUCTION OR UNAUTHORIZED ACCESS TO PHOTORANKR&#39;S RECORDS, PROGRAMS OR SERVICES.
</br></br>
(b)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	IN NO EVENT SHALL THE AGGREGATE LIABILITY OF PHOTORANKR, WHETHER IN CONTRACT, WARRANTY, TORT (INCLUDING NEGLIGENCE, WHETHER ACTIVE, PASSIVE OR IMPUTED), PRODUCT LIABILITY, STRICT LIABILITY OR OTHER THEORY, ARISING OUT OF OR RELATING TO THE USE OF OR INABILITY TO USE THE SITE, THE SERVICES, THE PRODUCTS, THE CONTENT OR THE SITE MATERIALS, EXCEED THE GREATER OF ANY COMPENSATION YOU PAY, IF ANY, TO PHOTORANKR FOR ACCESS TO OR USE OF THE SITE OR THE SERVICES OR FOR THE PURCHASE OF PRODUCTS OR $100.
</br></br>
(c)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	CERTAIN STATE LAWS DO NOT ALLOW LIMITATIONS ON IMPLIED WARRANTIES OR THE EXCLUSION OR LIMITATION OF CERTAIN DAMAGES.  IF THESE LAWS APPLY TO YOU, SOME OR ALL OF THE ABOVE DISCLAIMERS, EXCLUSIONS, OR LIMITATIONS MAY NOT APPLY TO YOU, AND YOU MIGHT HAVE ADDITIONAL RIGHTS.
</br></br>

<span style="font-size:20px"><b>19.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Indemnity</u></b></span>
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	You hereby agree to indemnify and hold harmless PhotoRankr, its affiliated and associated companies, and their respective directors, officers, employees, agents, representatives, independent and dependent contractors, licensees, successors, and assigns from and against all claims, losses, expenses, damages, and costs (including, but not limited to, direct, incidental, consequential, exemplary, and indirect damages), and reasonable attorneys&#39; fees, resulting from, or arising out of: (1) a breach of these Terms; (2) Content posted on the Site; (3) the use of the Services, by you or any person using your account or PhotoRankr Username and password; (4) the sale or use of your Content; or (5) any violation of any rights of a third party.
</br></br>

<span style="font-size:20px"><b>20.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Termination</u></b></span>
</br></br>
(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	PhotoRankr may terminate or suspend any and all Services and/or your PhotoRankr account immediately, without prior notice or liability, for any reason whatsoever, including without limitation if you breach the Terms.  If you violate the Terms of Use, PhotoRankr in its sole discretion may: (1) require you to remedy any violation thereof, and/or (2) take any other actions that PhotoRankr deems appropriate to enforce its rights and pursue available remedies.  Upon termination of your account, your right to use the Services will immediately cease.  
</br></br>
(b)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	If you wish to terminate your PhotoRankr account, you may simply discontinue using the Services, or send an e-mail to <a href="mailto:support@photorankr.com">support@photorankr.com</a> that provides clear written notice of a request to terminate your account.  PhotoRankr will notify you of termination via e-mail.  PhotoRankr may request additional information from you prior to terminating your account.  An account is not terminated in this manner until you receive confirmation of termination from PhotoRankr.  All provisions of the Terms, which by their nature should survive termination, shall survive termination, including, without limitation, ownership provisions, warranty disclaimers, limitations of liability, and indemnity.
</br></br>
(c)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	If PhotoRankr does not have a current, working e-mail address for you, then you may not receive important notices from PhotoRankr regarding your account, which may include notices regarding termination.  It is your responsibility to remove all Content from your account prior to termination.  Upon termination of your account, PhotoRankr will automatically remove all Content posted to your account.
</br></br>

<span style="font-size:20px"><b>21.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Applicable Law</u></b></span>
</br></br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	Your use of the Site is subject to all applicable local, state, national, and international laws and regulations.  The Terms of Use and your use of the Site shall be governed by and construed in accordance with the laws of the State of Delaware, as if made within Delaware between two residents thereof, without resort to Delaware&#39;s conflict of law provisions.  You agree that any action at law or in equity arising out of or relating to these Terms of Use shall be filed only in the state and federal courts located in Kent County, Delaware and you hereby irrevocably and unconditionally consent and submit to the exclusive jurisdiction of such courts over any suit, action or proceeding arising out of these Terms of Use.
</br></br>

<span style="font-size:20px"><b>22.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<u>Miscellaneous</u></b></span>
</br></br>
(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	No agency, partnership, joint venture, or employment is created as a result of the Terms, and you do not have any authority of any kind to bind PhotoRankr in any respect whatsoever.  
</br></br>
(b)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	The failure of either party to exercise in any respect any right provided for herein shall not be deemed a waiver of any further rights hereunder.  
</br></br>
(c)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	PhotoRankr shall not be liable for any failure to perform its obligations hereunder where such failure results from any cause beyond PhotoRankr&#39;s reasonable control, including, without limitation, mechanical, electronic or communications failure or degradation (including &#34;line-noise&#34; interference).  
</br></br>
(d)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	If any provision of these Terms of Use shall be deemed invalid, unlawful, void or for any reason unenforceable, then that provision shall be deemed severable from these Terms of Use and shall not affect the validity and enforceability of any remaining provisions.  
</br></br>
(e)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	PhotoRankr may transfer, assign, or delegate the Terms and its rights and obligations without consent.  
</br></br>
(f)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	Both parties agree that the foregoing Terms are the complete and exclusive statement of the mutual understanding of the parties and supersedes and cancels all previous written and oral agreements, communications, and other understandings relating to the subject matter of the Terms.
</br></br>
			</div>
			<label style="float:right;width:80%;margin-top:18px;"> By checking this you agree to the <a style="color:#6ba548" href="terms.php"> Terms and Conditions </a></label>
			<input type="checkbox" name="terms" value="terms" style="float:left;width:20px;margin-top:26px;margin-left:30px;">
            
            <label style="float:right;width:80%;margin-top:18px;"> Allow PhotoRankr users to promote my photos on other networks such as Pinterest (optional). </label>
			<input type="checkbox" name="optin" value="optin" style="float:left;width:20px;margin-top:26px;margin-left:30px;">


		</fieldset>
		<button onclick="formSubmit()" id="signUpBtn"> Create My Account </button>
    </form>
    
		<div id="miniFooter" style="margin-top:600px;">
			<ul>
				<li> About </li>
				<li> Contact </li>
				<li> Privacy Policy </li>
				<li> Terms </li>
				<li style="width:30px;z-index: 1000;padding-left:5px;margin: -4px 0 0 0;"> <img style="height:25px;border-radius: 3px 0 0 3px;" src="graphics/facebook_s.png"/></li>
				<li style="width:35px;padding-left:0;padding-right:5px;margin: -4px 0 0 0px;"> <img  style="height:25px;border-radius:0 3px 3px 0;" src="graphics/twitter_s.png"/></li>
			</ul>
		</div>
	</div>

<script type="text/javascript">
	

	(function(){
            $("#formContainer").mouseover( function() { $("#formContainer").addClass('scroll');});
        })()


	
</script>

</html>