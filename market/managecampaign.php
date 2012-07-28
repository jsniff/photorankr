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

//start the session
session_start();

$repemail = $_SESSION['repemail'];

//User information
$userquery = mysql_query("SELECT * FROM campaignusers WHERE repemail = '$repemail'");
$logo = mysql_result($userquery,0,'logo');
if($logo == '') {
$logo = 'graphics/nologo.png';
}
$name = mysql_result($userquery,0,'name');
$password = mysql_result($userquery,0,'password');

if($_SESSION['loggedin'] != 2) {
	mysql_close();
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=campaignnewuser.php">';
	exit();			
}

//find out which campaign they are trying to look at
$campaignID = htmlentities($_GET['id']);

//MESSAGE
if(htmlentities($_GET['action']) == 'message') {
$message = mysql_real_escape_string(htmlentities($_POST['message']));
$emailmessage = $message;
$message = $message . "<br />";
$messagequery = "UPDATE campaigns SET comments = CONCAT(comments,'$message') WHERE id = '$campaignID'";
$messagequeryrun = mysql_query($messagequery);

    //SEND NOTIFICATION TO USERS ENTERED IN THIS CAMPAIGN FEEDBACK
    $feednot = mysql_query("SELECT * FROM campaignphotos WHERE campaign = '$campaignID'");
    $numusers = mysql_num_rows($feednot);
    
    $capquery = mysql_query("SELECT title FROM campaigns WHERE id = '$campaignID'");
    $caption = mysql_result($capquery,0,'title');
    
    for($iii=0; $iii < $numusers; $iii++) {
        $useremail = mysql_result($feednot,$iii,'emailaddress');
        $match=strpos($prevlist, $useremail);
        if($match !== false) {
            continue;
        }
        
          //Send email
          $to = $useremail;
          $subject = 'Buyer feedback for "' . $caption . '" campaign';
          $feedbackmessage = 'Feedback: ' . $emailmessage .
'Visit the campaign here: http://photorankr.com/campaignphotos.php?id=' . $campaignID;
          $headers = 'From:PhotoRankr <photorankr@photorankr.com>';
          mail($to, $subject, $feedbackmessage, $headers); 
        
        $prevlist = $prevlist . " " . $useremail;
        }
        
        //Send notification
        $type = 'feedback';
        $feedbacknews = mysql_query("INSERT INTO newsfeed (type,source,caption,campaignentree) VALUES ('$type','$campaignID','$caption','$prevlist')");
    

}

//if they tried to create a new campaign
if($_GET['create']) {
	//get all of the data submitted by the form
	$title = mysql_real_escape_string(htmlentities($_POST['title']));
    $location = mysql_real_escape_string(htmlentities($_POST['location']));
	$lengthofuse = mysql_real_escape_string(htmlentities($_POST['lengthofuse']));
	$additionalterms = mysql_real_escape_string(htmlentities($_POST['additionalterms']));
	$terms = mysql_real_escape_string(htmlentities($_POST['terms']));

	$description = mysql_real_escape_string(htmlentities($_POST['description']));
	$use = $_POST['use'];
	$iii=0;
	while($use[$iii]) {
		$uses .= mysql_real_escape_string(htmlentities($use[$iii]));
		$uses .= " ";
		$iii++;
	}
	$uses = substr($uses, 0, -1);
	$license = $_POST['license'];
	$iii=0;
	while($license[$iii]) {
		$licenses .= mysql_real_escape_string(htmlentities($license[$iii]));
		$licenses .= " ";
		$iii++;
	}
	$licenses = substr($licenses, 0, -1);
	$budget = mysql_real_escape_string(htmlentities($_POST['budget']));
	$timeframe = mysql_real_escape_string(htmlentities($_POST['timeframe']));
	$endtime = time() + 24*60*60*$timeframe;
	$starttime = time();

	//make sure they filled in all of the fields
	if(!($starttime && $endtime && $budget && $title && $description && $repemail && $license && $terms)) {
        header("location:createcampaign.php?action=createfail");
        die();

	}

	//insert it into the database
	$newCampaignQuery = "INSERT INTO campaigns (starttime, endtime, quote, title, description, repemail, license, used, location, lengthofuse, additionalterms) VALUES ('$starttime', '$endtime', '$budget', '$title', '$description', '$repemail', '$licenses', '$uses', '$location', '$lengthofuse', '$additionalterms')";
	$newCampaignResult = mysql_query($newCampaignQuery) or die(mysql_error());
    
    //find out what the id should be
	$idquery = mysql_query("SELECT id FROM campaigns ORDER BY id DESC LIMIT 1") or die(mysql_error());
	$campaignID = mysql_result($idquery, 0, "id");
    
        //newsfeed query
        $type = "campaign";
        $newsfeedfavequery=mysql_query("INSERT INTO newsfeed (type,caption,source) VALUES ('$type','$title','$campaignID')");
        
        //notifications query     
        $notsquery = "UPDATE userinfo SET notifications = (notifications + 1)";
        $notsqueryrun = mysql_query($notsquery);   
        
        //Send out Congrats for ne campaign email
                
                $to = $sendemail;
                $subject = "Your PhotoRankr Campaign Has Begun!";
                $message = 'You\'ve successfully created your photo campaign "' .$title. '" on PhotoRankr. To promote your campaign, visit and login at:

                http://photorankr.com/campaign/managecampaign.php?id=$campaignID

                Warm Regards,
                The PhotoRankr Team
                ';
    
                $headers = 'From:PhotoRankr <photorankr@photorankr.com>';
                mail($to, $subject, $message, $headers);
   


}

if($_GET['view'] != "newest") {
//recalculate the score for each of the photos
$scorequery = "SELECT * FROM campaignphotos WHERE campaign='$campaignID'";
$scoreresult = mysql_query($scorequery);

if(mysql_num_rows($scoreresult) >= 1) {

//find out how many votes have been cast
$totalvotes = 0;
for($iii=0; $iii < mysql_num_rows($scoreresult); $iii++) {
	$totalvotes += mysql_result($scoreresult, $iii, "votes");
}

$Scorequery = "UPDATE campaignphotos SET score = CASE ";

//loop through the photos and calculate the score for each
for($iii=0; $iii < mysql_num_rows($scoreresult); $iii++) {
	$points = mysql_result($scoreresult, $iii, "points");
	$votes = mysql_result($scoreresult, $iii, "votes");
	$avg = $points / $votes;
	$voteshare = $votes / $totalvotes;
	$score = $avg * $voteshare;
	$source = mysql_result($scoreresult, $iii, "source");

	$Scorequery .= "WHEN source='$source' THEN '$score' ";
}

//end the score update query
$Scorequery .= "END;";

//update the database
mysql_query($Scorequery) or die(mysql_error());
}
}

if (htmlentities($_GET['action']) == "editcampaign") {  
	$title = mysql_real_escape_string(htmlentities($_POST['title']));
	$description = mysql_real_escape_string(htmlentities($_POST['description']));
    $editcampaignquery = mysql_query("UPDATE campaigns SET title='$title', description='$description' WHERE id = '$campaignID' AND repemail = '$repemail'");
}



?>

<!DOCTYPE html>
<html>
<head>
	<title>View all of the photos from this campaign on PhotoRankr</title>
	  <link rel="stylesheet" type="text/css" href="css/bootstrapnew.css" />
 <link rel="stylesheet" href="css/reset.css" type="text/css" />
  <link rel="stylesheet" href="css/text.css" type="text/css" />
  <link rel="stylesheet" href="css/960_24.css" type="text/css" />
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script src="js/bootstrap.js" type="text/javascript"></script>
  <script src="js/bootstrap-dropdown.js" type="text/javascript"></script>
  <script src="js/bootstrap-collapse.js" type="text/javascript"></script>
  <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
  
  <!--HIDDEN UPLOAD INFORMATION SCRIPT-->
<script type="text/javascript">   
$(document).ready(function(){
  $(".flip").click(function(){
    $(".panel").slideDown("slow");
  });
});
</script>
  
  <style type="text/css">

 .statoverlay {
opacity:.7;
filter:alpha(opacity=40);
z-index:1;
transition: opacity .5s;
-moz-transition: opacity .5s;
-webkit-transition: opacity .5s;
-o-transition: opacity .5s;
}
            
.statoverlay:hover {
opacity:.7;
}                

.item {
  margin: 10px;
  float: left;
  border: 1px solid black;
}

.item:hover {
  margin: 10px;
  float: left;
  border: 1px solid black;
}

</style>
</head>
<body style="overflow-x:hidden; background-color: #eeeff3;">
	<!--NAVIGATION BAR-->
<?php navbarnew(); ?>
<!--/END NAVBAR-->
	<div id="container" class="container_24">
		<div class="grid_24 pull_1" style="width: 1140px;">

<?php

//select all of the campaigns information
$campaignquery = "SELECT * FROM campaigns WHERE id='$campaignID' AND repemail='$repemail' LIMIT 1";
$campaignresult = mysql_query($campaignquery);

if(mysql_num_rows($campaignresult) == 0) {
	mysql_close();
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=createcampaign.php">';
	exit();	
}

//find the title and description of this campaign
$title = mysql_result($campaignresult, 0, "title");
$description = mysql_result($campaignresult, 0, "description");
$location = mysql_result($campaignresult, 0, "location");
$lengthofuse = mysql_result($campaignresult, 0, "lengthofuse");
$examplelink = mysql_result($campaignresult, 0, "examplelink");
$additionalterms = mysql_result($campaignresult, 0, "additionalterms");

$quote = mysql_result($campaignresult, 0, "quote");
$licenses = str_replace(" ", ", ", mysql_result($campaignresult, 0, "license"));
$uses = str_replace(" ", ", ", mysql_result($campaignresult, 0, "used"));

//display the title and description
echo '<div class="dropshadow well grid_24" style="text-align: left; width: 860px;margin-top:20px;">
	<div class="grid_18 alpha">';
    if($logo != 'graphics/nologo.png') {
    echo'
	<img style="border: 1px solid black;" src="',$logo,'" height="80" width="80" />';
    }
    if($name) {
    echo'&nbsp;&nbsp;<span style="font-size: 16px;position:relative;top:65px;left:-90px;">Buyer: ',$name,'</span><span style="font-size: 30px;position:relative;left:-50px;">"',$title,'"</span><br /><br />';
    }
    if(!$name) {
    echo'<span style="font-size: 30px;">"',$title,'"</span><br />';
    }
    echo'
    <br />
	<span style="font-size: 16px; margin-top: 15px;"><b>Description:</b> ',$description, ' <a href="',$examplelink,'">Click here to see an example photo</a></span><br />
	<span style="font-size: 16px; margin-top: 5px;"><b>Price:</b> $',$quote, '</span><br />
	<span style="font-size: 16px; margin-top: 5px;"><b>License(s)</b> required: ',$licenses, '</span><br />
	<span style="font-size: 16px; margin-top: 5px;"><b>Use(s):</b> ',$uses, '</span><br />
    <span style="font-size: 16px; margin-top: 5px;"><b>Location:</b> ',$location, '</span><br />
	<span style="font-size: 16px; margin-top: 5px;"><b>Length of Use:</b> ',$lengthofuse, '</span><br />
	<span style="font-size: 16px; margin-top: 5px;"><b>Additional Terms:</b> ',$additionalterms,'</span><br />';
    
     //NUMBER PHOTOS IN CAMPAIGN
    $numphotosquery = mysql_query("SELECT * FROM campaignphotos WHERE campaign = '$campaignID'");
    $numphotos = mysql_num_rows($numphotosquery);

//if the campaign is over
if(mysql_result($campaignresult, 0, "endtime") <= time()) {
	//display "this campaign is over"
	echo '
    <span style="font-size: 16px; margin-top: 15px;"><b>Photos Submitted:</b> ',$numphotos,'</span>
    <h3 style="font-size: 18px; margin-top: 15px;color:red"><u>This campaign is already over.</u></h3>';

	//find out who the winner was
	$winneremail = mysql_result($campaignresult, 0, "winneremail");
    $winnerphotoid = mysql_result($campaignresult, 0, "winnerphoto");



	//if a winner has been selected
	if($winneremail != "") {
		//display "Matt Sniff won!"
        $winnernamequery = mysql_query("SELECT firstname,lastname,user_id FROM userinfo WHERE emailaddress = '$winneremail'");
        $winnerfirstname = mysql_result($winnernamequery, 0, "firstname");
        $winnerlastname = mysql_result($winnernamequery, 0, "lastname");
        $userid = mysql_result($winnernamequery, 0, "user_id");
        $fullname = $winnerfirstname . " " . $winnerlastname;
        
        $winnerphotoquery = mysql_query("SELECT caption FROM campaignphotos WHERE id = '$winnerphotoid'");
        $winnerphototitle = mysql_result($winnerphotoquery, 0, "caption");
        
		echo '<div style="font-size: 17px; margin-top: -15px;"><b>Your Winner</b>: ',$fullname,'</div>
        <div style="font-size: 17px;"><b>Your Chosen Photo:</b> "<a href="fullsizeme.php?id=',$winnerphotoid,'">',$winnerphototitle,'</a>"</div>';  
	}
	//otherwise a winner hasn't been selected
	else {
		//display "no winner has been chosen yet"
		echo '<h3 style="font-size: 18px; margin-top: -15px;">No winner has been selected yet.</h3>';
	}
    
    //display a link to edit the campaign
    echo'<div style="font-size:16px;">(Click on a photo to purchase)</div>
    
	</div>';
}
//otherwise the campaign is not over yet 


else {
	//find out how much time is left in the campaign
	$timeleft          = mysql_result($campaignresult, 0, "endtime") - time();
	//find out how many days hours minutes are left
	$daysleft          = floor($timeleft / (24*60*60));
    $timeleft          -= 24*60*60*$daysleft;
    $hoursleft         = floor($timeleft / (60*60));
	$timeleft          -= 60*60*$hoursleft;
	$minutesleft       = floor($timeleft / 60);
    $today = date("F j, Y, g:i a");
        
	//display how much time is left in the campaign
	echo '<span style="font-size: 16px; margin-top: 15px;"><b>Time Left:</b> ', $daysleft, ' days ', $hoursleft, ' hours ', $minutesleft, ' minutes</span><br />
    <span style="font-size: 16px; margin-top: 15px;"><b>Photos Submitted:</b> ',$numphotos,'</span>
    <br /><br />
';
    
    
        //Edit Campaign Modal
    
        echo'<div class="modal hide fade" id="editcampaign" style="overflow-y:scroll;overflow-x:hidden;">

        <div class="modal-header">
        <a style="float:right" class="btn btn-primary" data-dismiss="modal">Close</a>
        <img style="margin-top:-4px;" src="graphics/logoteal.png" height="28" width="100" />&nbsp;&nbsp;<span style="font-size:16px;">Edit your campaign\'s information below:</span>
        </div>
        <div modal-body" style="width:600px;">

        <div id="content" style="font-size:16px;width:550px;height:230px;overflow-x:hidden;margin-top:10px;margin-left:10px;">

        <form action="managecampaign.php?id=',$campaignID,'&action=editcampaign" method="post" enctype="multipart/form-data">

    <span style="font-size:14px;">
    Title:&nbsp;&nbsp; <input name="title" value="',$title,'">
    <br />
    Description:&nbsp;
    <br />
    <textarea style="width:480px" rows="5" cols="60" name="description" >',$description,'</textarea>
    <br />
    </span>
    <button class="btn btn-success" type="submit">Save Info</button>
    </form>
    <br />

</div>
</div>
</div>';


 //Campaign Agreement Modal
    
        echo'<div class="modal hide fade" id="campaignagreement" style="overflow-y:scroll;overflow-x:hidden;width:850px;margin-left:-400px;">

        <div class="modal-header">
        <a style="float:right" class="btn btn-primary" data-dismiss="modal">Close</a>
        <img style="margin-top:-4px;" src="graphics/logoteal.png" height="28" width="100" />&nbsp;&nbsp;<span style="font-size:16px;">Campaign Content License Agreement</span>
        </div>
        <div modal-body" style="width:700px;">
        <div id="content" style="font-size:16px;width:830px;height:400px;overflow-x:hidden;margin-top:10px;margin-left:10px;">
        <div>
        <pre style="font-family:helvetica,arial;font-size:13px;padding-left:10px;margin-right:20px;">
        
        
        <div style="text-align:center;font-size:15px;font-weight:bold;">CAMPAIGN CONTENT LICENSE AGREEMENT</div>';
echo
htmlentities("
	In the event that the Licensee selects as the Image, the image, or images, as the case may be, submitted by the Licensor as part of a PhotoRankr Tender, then the Licensee and Licensor will be deemed to enter into a separate binding agreement in relation to the license of the Image and the rights of the Licensor in relation to such Image.

	For the avoidance of doubt:

	This Agreement is in addition to the terms applicable to the Site, which includes without limitation the terms of use, non disclosure agreement, privacy policy, or any other policy or procedure communicated by PhotoRankr from time to time;

	PhotoRankr and its third party providers will not be a party to this separate agreement and will have no liability whatsoever in relation to the performance or failure to perform of a Licensor or Licensee under the terms of this separate agreement.

	The agreement of the Licensor to the terms and conditions set out below is shown by clicking on the 'Agree' button.  By clicking on the 'Agree' button below, you represent and warrant that you have read and understood all of the terms and conditions set forth below and agree to be bound by them.  If you do not agree to such terms, you should not click the 'Agree' button below.  If you click on the 'Agree' button on behalf of your employer, you represent and warrant that you have full legal authority to bind your employer or such other entity.  If you do not have such authority, you should not click the 'Agree' button below.

1. 	Definitions

	Unless inconsistent with the context, the following expressions shall have the following meanings:

	'Agreement' means this intellectual property license agreement;

	'Business Day' means any day which is not a Saturday, Sunday, or a national holiday in the United States;

	'Image' means the image or images, as the case may be, which the Licensor selects as the winning image pursuant to the PhotoRankr Tender;

	'PhotoRankr' means PhotoRankr, Inc., a corporation of the State of Delaware located at 160 Greentree Drive, Suite 101, Dover, Delaware 19904;

	'PhotoRankr Tender' means a tender held by the Licensor on the Site, pursuant to which prospective photographers submit images for review and consideration by the Licensor;

	 'Intellectual Property' means the Image; 'Licensee' means 'Sample Licensee,' identified also by the username '$repemail'; 'Licensor' means 'Content Creator', identified also by the username 'Content Creator'; 'Site' means www.photorankr.com. 

2. 	Interpretation 

	In these terms and conditions, unless the context otherwise indicates:

(a)	References to any statute, ordinance, or other law shall include all regulations and other instruments thereunder and all consolidations, amendments, re-enactments or replacements thereof.

(b)	Words importing the singular shall include the plural and vice versa, words importing a gender shall include other genders and references to a person shall be construed as references to an individual, firm, body corporate, association (whether incorporated or not), government, and governmental, semi-governmental, and local authority or agency.

(c)	Where any word or phrase is given a defined meaning in these terms and conditions, any other part of speech or other grammatical form in respect of such word or phrase shall have a corresponding meaning.

3.	License

(a)	Unless otherwise agreed by the Licensor and the Licensee in writing, the Licensor grants the Licensee a license to use the Image as follows:

	(i)	Term:  $lengthofuse; starting from: $today;

	(ii)	Territory of Use:  $location; 

	(iii)	Permitted Uses:  $uses;

	(iv)	Exclusive or Non-Exclusive Use: $licenses;

	(v)	if the Permitted Uses in (iii) above include Web Advertising, Digital Banners, Social Media, Web Video, E-mail Promotion and Electronic Brochure, Apps, E-Book, Corporate, Retail and Promotional Site, then worldwide territory is hereby granted;

	(vi)	Additional Terms: $additionalterms
             
             
             
(b)	The Licensee must only use the Image as expressly permitted by this Agreement.

(c)	Notwithstanding any other provision of this Agreement, the Licensee must not use the Image for any pornographic use, in a manner which is obscene or immoral, for any unlawful purpose, to defame any person, or to violate any person’s right to privacy or publicity.

4.	Third Party Rights

(a)	The Licensor agrees, represents and warrants that:

	(i)	the Image does not infringe any reputation or intellectual property right of a third party;

	(ii)	all relevant authors have agreed not to assert their moral rights (personal rights associated with authorship of a work under applicable law) in the Image;

	(iii)	if the Image incorporates the intellectual property rights of a third party, then the Licensor has obtained a license from the relevant third party to incorporate the intellectual property rights of that third party in the Image ('Third Party License');

	(iv)	the Third Party License permits the Licensee with a worldwide, royalty free, perpetual right to display, distribute, and reproduce (in any form) the intellectual property rights of the third party contained in the Image.

(b)	In the event that the Third Party License is capable of assignment to the Licensee, then the Licensee hereby assigns and transfers to the Licensor, and the Licensee hereby agrees to take an assignment and transfer thereof, the Third Party License and all of the rights and obligations of the Licensor under the Third Party License.

5.	Indemnity

	The Licensor must indemnify and keep indemnified the Licensee from and against all loss, cost, expense (including legal costs and attorney's fees) or liability whatsoever incurred by the Licensee arising from any claim, demand, suit, action, or proceeding by any person against the Licensee where such loss or liability arose out of an infringement, or alleged infringement, of the intellectual property rights of any person, which occurred by reason of the license of the Image by the Licensor.

6.	Liability of PhotoRankr and Its Third Party Providers

	Both the Licensor and the Licensee acknowledge and agree that: 

(a)	PhotoRankr and its Third Party Providers are not parties to this Agreement; and

(b)	each of PhotoRankr and its Third Party Providers shall each not be liable or responsible for any breach of this Agreement by any one or more of the Licensee and the Licensor.

7.	Representations and Warranties

(a)	The Image is provided 'as is,' and, to the fullest extent permitted under the applicable law, the Licensor hereby expressly disclaims any and all warranties of any kind or nature, whether express, implied, or statutory.

(b)	The Licensee acknowledges and confirms that the Licensor does not make any warranty or representation that the Image will satisfy the requirements of the Licensee.

8.	Termination

	Notwithstanding any other provisions of this Agreement, the Licensor has the right to immediately terminate this Agreement and the license granted hereunder if the Licensee has breached any of its obligations under this Agreement.

9.	Assignment

	This Agreement is personal to each of the License and the Licensor, and may not be assigned without the prior written consent of the other party.

10.	Further Assurances

	Each of the parties will upon request by any other party hereto at any time and from time to time, execute, sign, and deliver all documents and do all things necessary or appropriate to evidence or carry out the intent and purposes of these Terms.

11.	Entire Agreement

	These Terms, and any attachments thereto, including releases from models, minors, and property owners, constitute the entire agreement between the parties and supersedes all prior representations, agreements, statements, and understanding, whether verbal or in writing.

12.	Notices

	A notice or other communication given under this Agreement, including, but not limited to, a request, demand, consent or approval, to or by a party to this Agreement: 

(a) 	must be in legible writing and in English;

(b) 	must be addressed to the addressee at the mailing address or e-mail address set forth below or to any other mailing address or email address a party notifies to the others in writing:

	(i)	if to the Licensor: 
		Content Creator

	(ii)	if to the Licensee: 
		    $repemail
             
             
		

(c)	without limiting any other means by which a party may be able to prove that a notice has been received by another party, a notice is deemed to be received:

	(i)	if sent by hand, when delivered to the addressee;

	(ii)	if by mail, three Business Days from, and including, the date of postmark; or

	(iii)	if by e-mail transmission, on receipt by the sender of an e-mail acknowledgment or read receipt generated by the e-mail client to which the email was sent, but if the delivery or receipt is on a day which is not a Business Day or is after 5.00 p.m (addressee's time), it is deemed to be received at 9.00 a.m. on the following Business Day.

13.	Miscellaneous

(a)	Tax Liability.  You agree to pay and be responsible for any and all sales taxes, use taxes, value added taxes and duties imposed by any jurisdiction as a result of the license granted to you, or of your use of the Content, pursuant to this Agreement. 

(b)	Invalid Provisions.  If any provision of this Agreement is found to be invalid or otherwise unenforceable under any applicable law, such invalidity or unenforceability shall not be construed to render any other provisions contained herein as invalid or unenforceable, and all such other provisions shall be given full force and effect to the same extent as though the invalid or unenforceable provision were not contained herein.

(c)	Applicable Law.  This Agreement shall be governed by and construed in accordance with the laws of the State of Delaware without regard to the conflict of law principles of Delaware or any other jurisdiction.  This Agreement will not be governed by the United Nations Convention on Contracts for the International Sale of Goods, the application of which is expressly excluded.  You consent to service of any required notice or process upon you by registered mail or overnight courier with proof of delivery notice, addressed to the address or contact information provided by you at the time the Content was downloaded, or such other address as you may advise us in writing to use, from time to time.

(d)	Arbitration.  Any controversy or claim arising out of or relating to this contract, or the breach thereof, shall be determined by arbitration administered by the American Arbitration Association in accordance with its International Arbitration Rules.  The number of arbitrators shall be one.  The place of arbitration shall be New York, NY.  The arbitration shall be held, and the award shall be rendered, in English.

(e)	Waiver.  No action of Licensor, other than express written waiver, may be construed as a waiver of any provision of this Agreement.  A delay on the part of Licensor in the exercise of its rights or remedies will not operate as a waiver of such rights or remedies, and a single or partial exercise by Licensor of any such rights or remedies will not preclude other or further exercise of that right or remedy.  A waiver of a right or remedy by Licensor on any one occasion will not be construed as a bar to or waiver of rights or remedies on any other occasion.

(f)	Severability.  If any provision of this Agreement is found to be invalid or otherwise unenforceable under any applicable law, such invalidity or unenforceability shall not be construed to render any other provisions contained herein as invalid or unenforceable, and all such other provisions shall be given full force and effect to the same extent as though the invalid or unenforceable provision were not contained herein.

(g)	Section Headings.  The descriptive headings of this Agreement are for convenience only and shall be of no force or effect in construing or interpreting any of the provisions of this Agreement.

14.	Acknowledgement

	YOU ACKNOWLEDGE THAT YOU HAVE READ THIS AGREEMENT, UNDERSTAND IT, AND HAD AN OPPORTUNITY TO SEEK INDEPENDENT LEGAL ADVICE PRIOR TO AGREEING TO IT.  IN CONSIDERATION OF PHOTORANKR AGREEING TO PROVIDE THE CONTENT, YOU AGREE TO BE BOUND BY THE TERMS AND CONDITIONS OF THIS AGREEMENT.  YOU FURTHER AGREE THAT IT IS THE COMPLETE AND EXCLUSIVE STATEMENT OF THE AGREEMENT BETWEEN THE LICENSOR AND LICENSEE, WHICH SUPERSEDES ANY PROPOSAL OR PRIOR AGREEMENT, ORAL OR WRITTEN, AND ANY OTHER COMMUNICATION BETWEEN THE LICENSOR AND LICENSEE RELATING TO THE SUBJECT OF THIS AGREEMENT. 

15.	Electronic Acceptance

	The parties have executed this Agreement as of the date of the Licensor clicking the 'Agree' button.");

echo'
</pre>
</div>
    

</div>
</div>
</div>';



	//display a link to edit the campaign
    echo'<a data-toggle="modal" data-backdrop="static" href="#editcampaign"><button style="margin-top: 10px;" class="btn btn-success"><b>Edit Campaign</b></button></a>&nbsp;&nbsp
    <a data-toggle="modal" data-backdrop="static" href="#campaignagreement"><button style="margin-top: 10px;" class="btn btn-success"><b>Campaign Agreement</b></button></a>

<br /><br />

<div>
<div class="panel">

<div style="position:relative;font-weight:bold;padding-bottom:10px;font-family:helvetica neue, lucida grande, georgia; font-size:14px;">Give feedback to photographers:</div>

<form action="managecampaign.php?id=',$campaignID,'&action=message" method="post">
<textarea style="width:825px" rows="4" cols="210" name="message"></textarea>    
<button style="margin-left:725px;" class="btn btn-success" type="submit">Post Comment</button>
</form>
<br />

<div style="padding-right:15px;">';


$refer=htmlentities($_GET['refer']); 

if ($refer == 'referralsuccess') {
$sendemail = $_POST['email'];
$to = $sendemail;
$subject = "Help out my photo campaign on PhotoRankr";
$message = "You've been invited by $repemail to help out their photo campaign on PhotoRankr. To help them out, visit:

http://photorankr.com/campaignphotos.php?id=$campaignID

Sincerely,
The PhotoRankr Team
";

$headers = 'From:PhotoRankr <photorankr@photorankr.com>';
mail($to, $subject, $message, $headers);

echo '<span style="position:relative;top:0px;font-family:lucida grande, georgia, helvetica; font-size: 16px;" class="label label-success">Referral successfully sent</span><br /><br />';

}


echo'

<div style="font-size:14px;margin-top:-20px;font-weight:bold;">Help promote your campaign by sharing it through social media and email:</div><br /><br />

<!--FB-->
<a name="fb_share" share_url="http://photorankr.com/campaignphotos.php?id=',$campaignID,'"></a> 
<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" 
        type="text/javascript">
</script>

<!--TWITTER-->
<div style="position:relative;margin-top:15px;">
<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://photorankr.com/campaignphotos.php?id=',$campaignID,'" data-text="Help out my photo campaign on PhotoRankr" data-via="PhotoRankr" data-related="PhotoRankr">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
</script></div>

<!--GOOGLE PLUS-->
<div style="position:relative;margin-top:15px;">
<div class="g-plus" data-action="share" data-href="http://photorankr.com/campaignphotos.php?id=',$campaignID,'"></div>';
?>

<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
<?php
echo'

<!--TUMBLR-->
<div style="position:relative;margin-top:15px;">
<span id="tumblr_button_abc123"></span>
</div>

<br />

<!--Email System-->

<div style="position:relative; font-family:helvetica neue, lucida grande, georgia; font-size: 14px; font-weight:bold;">Send campaign to:</div>

<form action="managecampaign.php?id=',$campaignID,'&refer=referralsuccess" method="POST">
<div style="position:relative; top:-25px; left:150px;">
<input style="width:180px;height:22px;" type="text" name="email" placeholder="Email Address"/>
</div>
<div style="position:relative; top:-25px; left:223px;">
<button type="submit" name="Submit" class="btn btn-success">Send Campaign</button>
</div>
</form>
</div>

</div>

</div>
</div>
<p class="flip" style="font-size:14px;font-weight:bold;">Post Feedback And Promote Your Campaign Here</p>
</div>';

}

echo '</div>';




//GET MESSAGE
$getmessage = "SELECT comments FROM campaigns WHERE id = '$campaignID'";
$getmessagequery  = mysql_query($getmessage);
$message = mysql_result($getmessagequery, 0, "comments");

if($message != '') {
echo'
<div class="grid_18 dropshadow well" style="postion:relative;float:top;width:860px;"><span style="font-size:16px;font-weight:bold;">Your Feedback:<br /></span>
<span style="font-size:14px;">',$message,'</span>
</div>';
}

//FACEPILE
$query3 = mysql_query("SELECT * FROM campaignphotos WHERE campaign = '$campaignID'");
$numfaces = mysql_num_rows($query3);

echo'<div class="grid_18 dropshadow well" style="postion:relative;float:top;width:860px;">
<div style="font-size:16px;font-damily:helvetica neue,arial;padding-bottom:5px;"><b>Photographers in this competition:</b></div>';
for($iii=0; $iii < $numfaces; $iii++) {
    $facemail = mysql_result($query3,$iii,'emailaddress');
    $pos = strpos($prevlist,$facemail);
        if($pos !== false) {
            continue;
        }
     $query4 = mysql_query("SELECT profilepic,user_id FROM userinfo WHERE emailaddress = '$facemail'");
     $facephoto = mysql_result($query4,0,'profilepic');
    $facephoto = 'http://photorankr.com/' . $facephoto;
    
    echo'<img class="item" src="',$facephoto,'" height="60" width="60" />';    
        
    $prevlist = $prevlist . $facemail;
}
echo'</div>';

//View Options
echo'<div class="grid_18" style="postion:relative;float:top;width:860px;float:left;padding-left:4px;padding-bottom:10px;">';
if($_GET['view'] == "") {echo'
<a class="btn btn-primary" style="width:115px;height:22px;font-size:14px;" href="managecampaign.php?id=',$campaignID,'"><b>Grid View</b></a>&nbsp;&nbsp;
<a class="btn btn-primary" style="width:115px;height:22px;font-size:14px;" href="managecampaign.php?id=',$campaignID,'&style=natural"><b>Natural View</b></a>';
}
elseif($_GET['view'] == "newest") {
echo'
<a class="btn btn-primary" style="width:115px;height:22px;font-size:14px;" href="managecampaign.php?id=',$campaignID,'&view=newest"><b>Grid View</b></a>&nbsp;&nbsp;
<a class="btn btn-primary" style="width:115px;height:22px;font-size:14px;" href="managecampaign.php?id=',$campaignID,'&view=newest&style=natural"><b>Natural View</b></a>';
}
echo'</div>';

if($_GET['action'] == "saved") {
    $savedphotoid = $_GET['pd'];
    $one = '1';
    $savedquery = "UPDATE campaignphotos SET saved = '$one' WHERE id = '$savedphotoid'";
    $savedqueryrun = mysql_query($savedquery);
}

if($_GET['action'] == "remove") {
    $savedphotoid = $_GET['pd'];
    $zero = 'zero';
    $savedquery = "UPDATE campaignphotos SET saved = '$zero' WHERE id = '$savedphotoid'";
    $savedqueryrun = mysql_query($savedquery);
}

//select the photos in this campaign
if($_GET['view'] == "newest") {
	$photosquery = "SELECT * FROM campaignphotos WHERE campaign=".$campaignID." ORDER BY id DESC";
}
elseif($_GET['view'] == "saved") {
    $photosquery = "SELECT * FROM campaignphotos WHERE campaign=".$campaignID." AND saved = '1' ORDER BY id DESC";
}
else {
	$photosquery = "SELECT * FROM campaignphotos WHERE campaign=".$campaignID." ORDER BY score DESC";
}
$photosresult = mysql_query($photosquery);

if($_GET['style'] == "") {
//loop through the result to get all of the necessary information 
for($iii=0; $iii < mysql_num_rows($photosresult); $iii++) {
	//get the information for the current photo
	$photo[$iii] = mysql_result($photosresult, $iii, "source");
	$photo[$iii] = str_replace("userphotos/","userphotos/medthumbs/", $photo[$iii]);
	$points = mysql_result($photosresult, $iii, "points");
	$votes = mysql_result($photosresult, $iii, "votes");
	$average[$iii] = number_format(($points / $votes),2);
	$photoid[$iii] = mysql_result($photosresult, $iii, "id");
    $photoid2[$iii] = mysql_result($photosresult, $iii, "id");
    $caption[$iii] = mysql_result($photosresult, $iii, "caption");

	list($width, $height) = getimagesize($photo[$iii]);
	$imgratio = $height / $width;
   	$heightls = $height / 2.5;
   	$widthls = $width / 2.5;

	echo '
	<div class="phototitle fPic" id="',$photoid[$iii],'" style="width:280px;height:280px;overflow:hidden;">
		<a name="return" href="fullsizeme.php?id=',$photoid[$iii],'">
       		<div class="statoverlay" style="z-index:1;left:0px;top:210px;position:relative;background-color:black;width:280px;height:75px;"><p style="line-spacing:1.48;padding:5px;color:white;">"',$caption[$iii],'"<br />Score: ',$average[$iii],'</p></div>
       		<img style="position:relative;top:-95px;min-height:300px;min-width:280px;" src="', $photo[$iii], '" height="',$heightls,'px" width="',$widthls,'px" />
       	</a>';
        
if($_GET['view'] != 'saved') {
echo'<a href="managecampaign.php?id=',$campaignID,'&pd=',$photoid[$iii],'&action=saved#return"<button class="btn btn-primary" style="z-index:2;position:relative;top:-130px;left:185px;opacity:.9;">Save Photo</button></a>';
}
elseif($_GET['view'] == 'saved') {
echo'<a name="removed" href="managecampaign.php?id=',$campaignID,'&view=saved&pd=',$photoid[$iii],'&action=remove#return"<button class="btn btn-primary" style="z-index:2;position:relative;top:-130px;left:205px;opacity:.9;">Remove</button></a>';
}
    echo'
    </div>';
}

} //end style == ''

if($_GET['style'] == "natural") {
//loop through the result to get all of the necessary information 
for($iii=0; $iii < mysql_num_rows($photosresult); $iii++) {
	//get the information for the current photo
	$photobig[$iii] = mysql_result($photosresult, $iii, "source");
	$points = mysql_result($photosresult, $iii, "points");
	$votes = mysql_result($photosresult, $iii, "votes");
	$average[$iii] = number_format(($points / $votes),2);
	$photoid[$iii] = mysql_result($photosresult, $iii, "id");
    $photoid2[$iii] = mysql_result($photosresult, $iii, "id");
    $caption[$iii] = mysql_result($photosresult, $iii, "caption");

	list($width, $height) = getimagesize($photobig[$iii]);
	$imgratio = $height / $width;
   	$heightls = $height / 2;
   	$widthls = $width / 2;

	echo '
		<a name="return" href="fullsizeme.php?id=',$photoid[$iii],'">
        <img class="phototitle" style="margin-top:20px;" src="',$photobig[$iii], '" height="',$heightls,'px" width="',$widthls,'px" />
       	</a>';
    
}

} //end style == 'natural'

?>

<div class="grid_3" style="position:fixed; right: 100px; margin-left:0px;">
<div id="accordion2" class="accordion" style="margin-top:20px;width:150px;">

<div class="accordion-group">
<div style="background-color:#eeeff3;" class="accordion-heading dropshadow">
<a style="color:#21608E;font-weight:bold;" class="accordion-toggle" href="managecampaign.php?id=<?php echo $campaignID; ?>&view=topranked">Top Ranked</a>
</div>
<div id="collapseOne" class="accordion-body collapse">
</div>
</div>

<div class="accordion-group">
<div style="background-color:#eeeff3;" class="accordion-heading dropshadow">
<a style="color:#21608E;font-weight:bold;" class="accordion-toggle" href="managecampaign.php?id=<?php echo $campaignID; ?>&view=newest">Newest</a>
</div>
<div id="collapseTwo" class="accordion-body collapse">
</div>
</div>

<div class="accordion-group">
<div style="background-color:#eeeff3;" class="accordion-heading dropshadow">
<a style="color:#21608E;font-weight:bold;" class="accordion-toggle" href="managecampaign.php?id=<?php echo $campaignID; ?>&view=saved">Saved Photos</a>
</div>
<div id="collapseTwo" class="accordion-body collapse">
</div>
</div>

<?php
if($_GET['action'] == "saved") {
    echo'<br /><span class="label label-success" style="font-size:14px;font-family:helveetica neue,arial;margin-left:25px;padding:6px;">Photo Saved</span><br />';
    }
elseif($_GET['action'] == "remove") {
    echo'<br /><span class="label label-important" style="font-size:14px;font-family:helvetica neue,arial;margin-left:15px;padding:6px;">Photo Removed</span><br />';
}
?>

</div>
</div>


<!--TUMBLR SCRIPTS-->
<script type="text/javascript">
    var tumblr_link_url = "http://photorankr.com/campaignphotos.php?id=',$campaignID,'";
    var tumblr_link_name = "My PhotoRankr Campaign";
    var tumblr_link_description = "Help out my photo campaign on PhotoRankr";
</script>

<script type="text/javascript">
    var tumblr_button = document.createElement("a");
    tumblr_button.setAttribute("href", "http://www.tumblr.com/share/link?url=" + encodeURIComponent(tumblr_link_url) + "&name=" + encodeURIComponent(tumblr_link_name) + "&description=" + encodeURIComponent(tumblr_link_description));
    tumblr_button.setAttribute("title", "Share on Tumblr");
    tumblr_button.setAttribute("style", "display:inline-block; text-indent:-9999px; overflow:hidden; width:129px; height:20px; background:url('http://platform.tumblr.com/v1/share_3.png') top left no-repeat transparent;");
    tumblr_button.innerHTML = "Share on Tumblr";
    document.getElementById("tumblr_button_abc123").appendChild(tumblr_button);
</script>

<script type="text/javascript" src="http://platform.tumblr.com/v1/share.js"></script>



</body>
</html>
<?php

mysql_close();

?>