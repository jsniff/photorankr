<?php

//connect to the database
require "db_connection.php";

require "functionscampaigns3.php";
    // if login form has been submitted
    if (htmlentities($_GET['action']) == "login") {
        login();
    }
    else if(htmlentities($_GET['action']) == "logout") {
        logout();
    }

if($_GET['signup']) {
//find the posted information
$repemail = mysql_real_escape_string(htmlentities($_POST['repemail']));
$password = mysql_real_escape_string(htmlentities($_POST['password']));




//if they didnt fill the whole form in
if(!($repemail && $password)) {
//close the connection and send them back to sign up with a message that there was a failure
mysql_close();
echo '<META HTTP-EQUIV="Refresh" Content="0; URL=campaignnewuser.php?error=1">';
exit();
}
//otherwise they filled the whole form in
else {
//check the database for a similar emailaddress
$checkquery = "SELECT id FROM campaignusers WHERE repemail='$repemail' LIMIT 0, 1";
$checkresult = mysql_query($checkquery);

//if there is a matching emailaddress
if(mysql_num_rows($checkresult) > 0) {
//close the connection and send them back to sign up with a message that there was a failure
mysql_close();
echo '<META HTTP-EQUIV="Refresh" Content="0; URL=campaignnewuser.php?error=2">';
exit();	
}
//otherwise this is a new person
else {
//insert them into the database
$newuserquery = "INSERT INTO campaignusers (repemail, password) VALUES ('$repemail', '$password')";
$newuserresult = mysql_query($newuserquery);

//start the session
session_start();

//set the session variables to show that they are signed in
$_SESSION['loggedin'] = 2;
$_SESSION['repemail'] = $repemail;
}
}
}
//end sign up script

//start the session
session_start();

//make sure they are logged in
if($_SESSION['loggedin'] != 2) {
//if they arent logged in send them away
mysql_close();
echo '<META HTTP-EQUIV="Refresh" Content="0; URL=campaignnewuser.php?error=3">';
exit();	
}

?>
<!DOCTYPE html>
<html>
<head>
<meta name="description" content="Create a Campaign for the photo you need. Creating your campaign is free.">
<meta name="keywords" content:"campaign, create, image, photo, photography, stock photos">
<meta name="author" content="The PhotoRankr Team">
<title>Create a Campaign on PhotoRankr to get photos that match your needs</title>
	<title>View all of the campaigns on PhotoRankr</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrapNew.css" />
    <link rel="stylesheet" href="css/reset.css" type="text/css" />
    <link rel="stylesheet" href="css/text.css" type="text/css" />
    <link rel="stylesheet" href="css/960_24.css" type="text/css" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  	<script src="js/bootstrap.js" type="text/javascript"></script>
  	<script src="js/bootstrap-dropdown.js" type="text/javascript"></script>
  	<script src="js/bootstrap-collapse.js" type="text/javascript"></script>
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

<!--GOOGLE ANALYTICS CODE-->
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

<style type="text/css">

.content
{
margin:30px 40px;
color:#000000;
font-size:16px;
z-index:3;
font-family: 'helvetica neue'; helvetica;
}

div.transbox
{
width:300px;
height:300px;
margin:30px -50px;
background-color:#ffffff;
border:1px solid black;
opacity:1;
-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
filter:alpha(opacity=100);
z-index:1;
float:left;
font-family: 'helvetica neue'; helvetica;
}


div.smalltransbox
{
width:270px;
height:130px;
margin:30px 0px;
background-color:#ffffff;
border:1px solid black;
opacity:1;
-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
filter:alpha(opacity=100);
z-index:1;
float:left;
font-family: 'helvetica neue'; helvetica;
}

div.bigtransbox
{
width:500px;
height:500px;
overflow-y:scroll;
font-family:'helvetica neue', helvetica, gill sans, arial;
margin-left:auto;
margin-right: auto;
text-align:center;
background-color:#fff;
border:1px solid black;
z-index:1;
font-family: 'helvetica neue'; helvetica;
}

</style>

</head>

<body style="background-image:url('graphics/NYC.jpg');background-size: 100%;
background-repeat:no-repeat;">

<!--NAVIGATION BAR-->
<?php navbarsweet(); ?>
<!--/END NAVBAR-->

<br /><br /><br />

<!--START OF CONTAINER-->
<div class="container">

<div class="grid_24" style="width:960px;margin-left:auto;margin-right:auto;margin-top:30px;">


<div class="bigtransbox" >
<p style="font-size:22px;padding:6px;margin-top:10px;">Create Your Campaign</p>
<form method="post" action="managecampaign.php?create=true">
<div class="content">
<?php
          
              if(isset($_GET['action'])){
                $action = htmlentities($_GET['action']);
                }
                
            if($action == 'createfail') {
            echo'<div style="font-size:16px;color:red;text-align:center;margin-top:-15px;">Please fill out all of the fields.</div><br />';
            }
            
        ?>

Campaign Title: <br/><input style="margin-top:5px;width:400px;" type="text" name="title" value="<?php echo htmlentities($_POST['title']); ?>" placeholder='"Bridge over troubled water"'/><br />
Description of Photo: <br/><textarea style="margin-top:5px;width:400px;" rows="4" cols="100" name="description"  value="<?php echo htmlentities($_POST['description']); ?>"><?php echo htmlentities($_POST['description']); ?></textarea><br />
Example/Logo Link: <br /><input style="margin-top:5px;width:400px;" type="text" name="examplelink" placeholder="Optional link to a similar photo or your company logo" /><br />

What will you be using the photo for? <br /><span style="font-size:14px;"><input type="checkbox" name="use[]" value="print" /> Print (magazine, newspaper, brochure, etc.) &nbsp;&nbsp;&nbsp; <input type="checkbox" name="use[]" value="webuse" /> Web Use &nbsp;&nbsp;&nbsp; <input type="checkbox" name="use[]" value="emailpromotion" /> Email Promotion &nbsp;&nbsp;&nbsp; <input type="checkbox" name="use[]" value="personaluse" /> Personal Use &nbsp;&nbsp;&nbsp; </span><br /><br />
Choose the license you would like for your photo: <br /><span style="font-size:14px;"><input type="checkbox" name="license[]" value="nonexclusive" /> Non-Exclusive &nbsp;&nbsp;&nbsp; <input type="checkbox" name="license[]" value="exclusive" /> Exclusive &nbsp;&nbsp;&nbsp;</span><br /><br/ >
Location of Use: <br/><input style="margin-top:5px;width:400px;" type="text" name="location" value="<?php echo htmlentities($_POST['location']); ?>"'/><br />
Length of Use: <br/><input style="margin-top:5px;width:400px;" type="text" name="lengthofuse"'/><br />
Additional Terms: <br/><textarea style="margin-top:5px;width:400px;" rows="4" cols="100" name="additionalterms"></textarea><br />
Budget: &nbsp;&nbsp;
<span style="style="margin-top:5px;">
<span class="add-on">$</span>
<input id="appendedPrependedInput" class="span1" type="text" name="budget" size="16" value="<?php echo htmlentities($_POST['budget']); ?>">
<span class="add-on">.00</span>
</span><br /><br />

Time frame: &nbsp;&nbsp;
<select class="span2" style="height:30px;" name="timeframe">
<option value="1">1 Day</option>
<option value="2">2 Days</option>
<option value="3">3 Days</option>
<option value="4">4 Days</option>
<option value="5">5 Days</option>
<option value="6">6 Days</option>
<option value="7">One Week</option>
<option value="14">Two Weeks</option>
<option value="30">One Month</option>
</select>
<br /><br />
<?php
            
 //Campaign Agreement Modal
    
        echo'<div class="modal hide fade" id="campaignagreement" style="overflow-y:scroll;overflow-x:hidden;width:850px;margin-left:-400px;">

<div class="modal-header">
<a style="float:right" class="btn btn-primary" data-dismiss="modal">Close</a>
<img style="margin-top:-4px;" src="graphics/logocampaign.png" width="220" />&nbsp;&nbsp;<span style="font-size:16px;">Campaign Content License Agreement</span>
</div>
<div modal-body" style="width:700px;">
<div id="content" style="font-size:16px;width:830px;height:400px;overflow-x:hidden;margin-top:10px;margin-left:10px;">
<div>
<pre style="font-family:helvetica,arial;font-size:13px;padding-left:10px;margin-right:20px;">
<div style="text-align:center;font-size:15px;font-weight:bold;">INTELLECTUAL PROPERTY LICENSE AGREEMENT</div>';
echo
htmlentities("

    In the event the Licensee selects as the Image, the image, or images, as the case may be, submitted by the Licensor as part of a PhotoRankr Tender, then the Licensee and Licensor will be deemed to enter into a separate and identical binding agreement in relation to the license of the Image and the rights of the Licensor in relation to such Image.

    For the avoidance of doubt:

    This Agreement is in addition to the terms applicable to the Site, which includes without limitation the terms of use, non disclosure agreement, privacy policy, or any other policy or procedure communicated by PhotoRankr from time to time;

    PhotoRankr and its third party providers will not be a party to this separate agreement and will have no liability whatsoever in relation to the performance or failure to perform of a Licensor or Licensee under the terms of this separate agreement.

    The agreement of the Licensor to the terms and conditions set out below is shown by clicking on the 'Agree' button.  By clicking on the 'Agree' button below, you represent and warrant that you have read and understood all of the terms and conditions set forth below and agree to be bound by them.  If you do not agree to such terms, you should not click the 'Agree' button below.  If you click on the 'Agree' button on behalf of your employer, you represent and warrant that you have full legal authority to bind your employer or such other entity.  If you do not have such authority, you should not click the 'Agree' button below.

1.  Definitions

    Unless inconsistent with the context, the following expressions shall have the following meanings:

    'Agreement' means this Intellectual Property License Agreement;

    'Business Day' means any day which is not a Saturday, Sunday, or a national holiday in the United States;

    'Image' means the image or images, as the case may be, which the Licensee selects as the winning image pursuant to the PhotoRankr Tender;

    'PhotoRankr' means PhotoRankr, Inc., a corporation of the State of Delaware, with registered agent located at 160 Greentree Drive, Suite 101, Dover, Delaware 19904;

    'PhotoRankr Tender' means a tender held by the Licensor on the Site, pursuant to which prospective photographers submit images for review and consideration by the Licensor;

    'Intellectual Property' means the Image; 

    'Licensee' means 'Sample Licensee,' identified also by the username 'John Doe'; 

    'Licensor' means 'Campaign Winner' identified also by the username 'Campaign Winner'; 

    'Site' means www.photorankr.com. 

2.  Interpretation 

    In these terms and conditions, unless the context otherwise indicates:

(a) References to any statute, ordinance, or other law shall include all regulations and other instruments thereunder and all consolidations, amendments, re-enactments, or replacements thereof.

(b) Words importing the singular shall include the plural and vice versa, words importing a gender shall include other genders and references to a person shall be construed as references to an individual, firm, body corporate, association (whether incorporated or not), government, and governmental, semi-governmental, and local authority or agency.

(c) Where any word or phrase is given a defined meaning in these terms and conditions, any other part of speech or other grammatical form in respect of such word or phrase shall have a corresponding meaning.

3.  License

(a) Unless otherwise agreed by the Licensor and the Licensee in writing, the Licensor grants the Licensee a license to use the Image as follows:

    (i) Term:  {#Months/Years}, starting from $today;

    (ii)    Territory of Use: $location;

    (iii)   Permitted Uses: $uses;

    (iv)    Exclusive or Non-Exclusive Use: $licenses;

    (v) if the Permitted Uses in (iii) above include Web Advertising, Digital Banners, Social Media, Web Video, E-mail Promotion and Electronic Brochure, Apps, E-Book, Corporate, Retail and Promotional Site, then worldwide territory is hereby granted;

    (vi)    Additional Terms: {AdditionalTerms}

(b) The Licensee must only use the Image as expressly permitted by this Agreement.

(c) Notwithstanding any other provision of this Agreement, the Licensee must not use the Image for any pornographic use, in a manner which is obscene or immoral, for any unlawful purpose, to defame any person, or to violate any person’s right to privacy or publicity.

4.  Third Party Rights

(a) The Licensor agrees, represents and warrants that:

    (i) the Image does not infringe any reputation or intellectual property right of a third party;

    (ii)    all relevant authors have agreed not to assert their moral rights (personal rights associated with authorship of a work under applicable law) in the Image;

    (iii)   if the Image incorporates the intellectual property rights of a third party, then the Licensor has obtained a license from the relevant third party to incorporate the intellectual property rights of that third party in the Image ('Third Party License');

    (iv)    the Third Party License permits the Licensee with a worldwide, royalty free, perpetual right to display, distribute, and reproduce (in any form) the intellectual property rights of the third party contained in the Image.

(b) In the event that the Third Party License is capable of assignment to the Licensee, then the Licensee hereby assigns and transfers to the Licensor, and the Licensee hereby agrees to take an assignment and transfer thereof, the Third Party License and all of the rights and obligations of the Licensor under the Third Party License.

5.  Indemnity

    The Licensor must indemnify and keep indemnified the Licensee from and against all loss, cost, expense (including legal costs and attorneys’ fees) or liability whatsoever incurred by the Licensee arising from any claim, demand, suit, action, or proceeding by any person against the Licensee where such loss or liability arose out of an infringement, or alleged infringement, of the intellectual property rights of any person, which occurred by reason of the license of the Image by the Licensor.

6.  Liability of PhotoRankr and Its Third Party Providers

    Both the Licensor and the Licensee acknowledge and agree that: 

(a) PhotoRankr and its Third Party Providers are not parties to this Agreement; and

(b) each of PhotoRankr and its Third Party Providers shall each not be liable or responsible for any breach of this Agreement by any one or more of the Licensee and the Licensor.

7.  Representations and Warranties

(a) The Image is provided 'as is,' and, to the fullest extent permitted under the applicable law, the Licensor hereby expressly disclaims any and all warranties of any kind or nature, whether express, implied, or statutory.

(b) The Licensee acknowledges and confirms that the Licensor does not make any warranty or representation that the Image will satisfy the requirements of the Licensee.

8.  Termination

    Notwithstanding any other provisions of this Agreement, the Licensor has the right to immediately terminate this Agreement and the license granted hereunder if the Licensee has breached any of its obligations under this Agreement.

9.  Assignment

    This Agreement is personal to each of the Licensee and the Licensor, and may not be assigned without the prior written consent of the other party.

10. Further Assurances

    Each of the parties will upon request by any other party hereto at any time and from time to time, execute, sign, and deliver all documents, and do all things necessary or appropriate to evidence or carry out the intent and purposes of these Terms.

11. Entire Agreement

    These Terms, and any attachments thereto, including, but not limited to, releases from models, property owners, and minors, constitute the entire agreement between the parties and supersedes all prior representations, agreements, statements, and understanding, whether verbal or in writing.

12. Notices

    A notice or other communication given under this Agreement, including, but not limited to, a request, demand, consent, or approval, to or by a party to this Agreement: 

(a)     must be in legible writing and in English;

(b)     must be addressed to the addressee at the mailing address or e-mail address set forth below or to any other mailing address or e-mail address a party notifies to the others in writing:

    (i) if to the Licensor: 
        Mailing Address: {LicensorAddress}
        E-mail Address: {LicensorE-mailAddress}




    (ii)    if to the Licensee: 
        Mailing Address: {LicenseeAddress}
        E-mail Address: {LicenseeE-mailAddress}


(c) without limiting any other means by which a party may be able to prove that a notice has been received by another party, a notice is deemed to be received:

    (i) if sent by hand, when delivered to the addressee;

    (ii)    if by mail, three Business Days from, and including, the date of postmark; or

    (iii)   if by e-mail transmission, on receipt by the sender of an e-mail acknowledgment or read receipt generated by the e-mail client to which the email was sent, but if the delivery or receipt is on a day which is not a Business Day or is after 5.00 p.m. (addressee's time) it is deemed to be received at 9.00 a.m. on the following Business Day.

13. Miscellaneous

(a) Tax Liability.  Licensee agrees to pay and be responsible for any and all sales taxes, use taxes, value added taxes and duties imposed by any jurisdiction as a result of the license granted to Licensee, or Licensee's use of the Image, pursuant to this Agreement. 

(b) Severability.  If any provision of this Agreement is found to be invalid or otherwise unenforceable under any applicable law, such invalidity or unenforceability shall not be construed to render any other provisions contained herein as invalid or unenforceable, and all such other provisions shall be given full force and effect to the same extent as though the invalid or unenforceable provision were not contained herein.

(c) Applicable Law.  This Agreement shall be governed by and construed in accordance with the laws of the State of Delaware without regard to the conflict of law principles of Delaware or any other jurisdiction.  This Agreement will not be governed by the United Nations Convention on Contracts for the International Sale of Goods, the application of which is expressly excluded.  

(d) Arbitration.  Any controversy or claim arising out of or relating to this contract, or the breach thereof, shall be determined by arbitration administered by the American Arbitration Association in accordance with its International Arbitration Rules.  The number of arbitrators shall be one.  The arbitration shall be held, and the award shall be rendered, in English.

(e) Waiver.  No action of Licensor, other than express written waiver, may be construed as a waiver of any provision of this Agreement.  A delay on the part of Licensor in the exercise of its rights or remedies will not operate as a waiver of such rights or remedies, and a single or partial exercise by Licensor of any such rights or remedies will not preclude other or further exercise of that right or remedy.  A waiver of a right or remedy by Licensor on any one occasion will not be construed as a bar to or waiver of rights or remedies on any other occasion.

(f) Section Headings.  The descriptive headings of this Agreement are for convenience only and shall be of no force or effect in construing or interpreting any of the provisions of this Agreement.

14. Electronic Acceptance

    The parties have executed this Agreement as of the date of the Licensor clicking the 'Agree' button.

THE LICENSOR

{LicensorName}
{LicensorUserName}
{LicensorE-mailAddress}


THE LICENSEE

{LicenseeName}
{LicenseeUserName}
{LicenseeE-mailAddress}


");

echo'
</pre>
</div>

</div>
</div>
</div>';
            
?>
<input style="margin-left:0px;" type="checkbox" name="terms" value="terms" />&nbsp;&nbsp;<span style="font-size:13px;">By checking here, you agree to the <b><a data-toggle="modal" data-backdrop="static" href="#campaignagreement">LICENSE AGREEMENT<a/></b>.</br></span>
<br />
<input class="btn btn-success" style="width:150px;height:35px;" type="submit" value="START CAMPAIGN" />
</div>
</form>
</div><br/ >
<div style="text-align:center;color:white;">
Copyright&nbsp;&copy;&nbsp;2012&nbsp;PhotoRankr, Inc.&nbsp;&nbsp;
<br /><br />
</div>
</div>



</body>

</div><!--/end of container-->
</html>

<?php

mysql_close();

?>

