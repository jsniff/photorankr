<?php

//connect to the database
require "db_connection.php";

//start the session
session_start();
$repemail = $_SESSION['repemail'];

require "functionscampaigns3.php"; 
    // if login form has been submitted
    if (htmlentities($_GET['action']) == "login") { 
        login();
    }
    else if(htmlentities($_GET['action']) == "logout") { 
        logout();
    }

//find out which campaign they are trying to look at
$campaignID = htmlentities($_GET['id']);

$finduserquery = mysql_query("SELECT repemail FROM campaigns WHERE id = '$campaignID'");
$owneremail = mysql_result($finduserquery,0,'repemail');

//User information
$userquery = mysql_query("SELECT * FROM campaignusers WHERE repemail = '$owneremail'");
$logo = mysql_result($userquery,0,'logo');
if($logo == '') {
$logo = 'graphics/nologo.png';
}
$name = mysql_result($userquery,0,'name');
$password = mysql_result($userquery,0,'password');

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
          
           //notifications query     
           $notsquery = mysql_query("UPDATE userinfo SET campaign_notifications = (notifications + 1) WHERE emailaddress= '$useremail'");
        
        $prevlist = $prevlist . " " . $useremail;
        }
        
        //insert into newsfeed
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
	if(!($starttime)) {
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
        $notsquery = mysql_query("UPDATE userinfo SET campaign_notifications = (notifications + 1)");
        
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

if(mysql_num_rows($scoreresult)) {
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
	$scoreID = mysql_result($scoreresult, $iii, "id");
	$avg = $points / $votes;
	$voteshare = $votes / $totalvotes;
	$score = $avg * $voteshare + $scoreID / 1000;
	$source = mysql_result($scoreresult, $iii, "source");

	$Scorequery .= "WHEN source='$source' THEN '$score' ";
}

//end the score update query
$Scorequery .= "END;";

//update the database
mysql_query($Scorequery) or die(mysql_error());
}
}


if(isset($_GET['newsid'])){
$newsid = htmlentities($_GET['newsid']);
$idformatted = $newsid . " ";
$unhighlightquery = "UPDATE userinfo SET unhighlight = CONCAT(unhighlight,'$idformatted') WHERE emailaddress = '$useremail'";
$unhighlightqueryrun = mysql_query($unhighlightquery);

//notifications query reset 
if($currentnotsresult > 0) {
$notsquery = "UPDATE userinfo SET notifications = 0 WHERE emailaddress = '$useremail'";
$notsqueryrun = mysql_query($notsquery); }
} 


if(htmlentities($_GET['action']) == 'savecampaign') {

    $title = mysql_real_escape_string(htmlentities($_POST['title']));
    $location = mysql_real_escape_string(htmlentities($_POST['location']));
	$lengthofuse = mysql_real_escape_string(htmlentities($_POST['lengthofuse']));                                
	$additionalterms = mysql_real_escape_string(htmlentities($_POST['additionalterms']));
	$terms = mysql_real_escape_string(htmlentities($_POST['terms']));
    $examplelink = mysql_real_escape_string(htmlentities($_POST['examplelink']));
    
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
    
    $updatecampaignquery = mysql_query("UPDATE campaigns SET title = '$title', description = '$description', license = '$licenses', used = '$uses', location = '$location', lengthofuse = '$lengthofuse', additionalterms = '$additionalterms', examplelink = '$examplelink' WHERE id = '$campaignID'");

}


?>
<!DOCTYPE html>
<head>
	<title>View all of the photos from this campaign on PhotoRankr</title>
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

<?php navbarsweet(); ?>

	<div id="container" class="container_24">
		<div class="grid_24" style="width: 1120px;top:65px;">
  
<?php

$view = htmlentities($_GET['view']);

//select all of the campaigns information
$campaignquery = "SELECT * FROM campaigns WHERE id='$campaignID' LIMIT 1";
$campaignresult = mysql_query($campaignquery);

if(mysql_num_rows($campaignresult) == 0) {
	mysql_close();
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=account.php?view=mycampaigns&option=create">';
	exit();	
}

//find the title and description of this campaign
$title = mysql_result($campaignresult, 0, "title");
$description = mysql_result($campaignresult, 0, "description");
$quote = mysql_result($campaignresult, 0, "quote");
$licenses = str_replace(" ", ", ", mysql_result($campaignresult, 0, "license"));
$uses = str_replace(" ", ", ", mysql_result($campaignresult, 0, "used"));
$location = mysql_result($campaignresult, 0, "location");
$lengthofuse = mysql_result($campaignresult, 0, "lengthofuse");
$examplelink = mysql_result($campaignresult, 0, "examplelink");
$additionalterms = mysql_result($campaignresult, 0, "additionalterms");

 //NUMBER PHOTOS IN CAMPAIGN
        $numphotosquery = mysql_query("SELECT * FROM managecampaign WHERE campaign = '$campaignID'");
        $numphotos = mysql_num_rows($numphotosquery);
        
//TITLE
echo'<div style="font-size: 25px;font-family:helvetica;font-weight:200;margin-top:80px;">',$title,'&nbsp;&nbsp;|&nbsp;&nbsp;$',$quote,'</div><br />';


//NAVBAR              
echo'
<div class="grid_18 roundedright" style="background-color:#eeeff3;height:60px;margin-top:0px;width:940px;">

<a style="text-decoration:none;color:black;" href="managecampaign.php?id=',$campaignID,'&view=brief"><div class="clicked" style="width:230px;height:60px;border-right:1px solid #ccc;float:left;';if($view == 'brief') {echo'background-color:#bbb;color:white;';}echo'"><div style="font-size:22px;font-weight:100;margin-top:10px;text-align:center;">Campaign Brief</div></div></a>

<a style="text-decoration:none;color:black;" href="managecampaign.php?id=',$campaignID,'"><div class="clicked" style="width:230px;height:60px;border-right:1px solid #ccc;border-left:1px solid #ccc;float:left;';if($view == '') {echo'background-color:#bbb;color:white;';}echo'"><div style="font-size:22px;font-weight:100;margin-top:10px;text-align:center;">Submissions</div></div></a>

<a style="text-decoration:none;color:black;" href="managecampaign.php?id=',$campaignID,'&view=photogs"><div class="clicked" style="width:230px;height:60px;border-right:1px solid #ccc;float:left;';if($view == 'photogs') {echo'background-color:#bbb;color:white;';}echo'"><div style="font-size:22px;font-weight:100;margin-top:10px;text-align:center;">Photographers</div></div></a>

<div style="width:180px;height:60px;float:left;margin-left:3px;"><div style="font-size:22px;font-weight:100;margin-top:6px;text-align:center;">
<form class="navbar-search" method="GET">
<input class="search" style="position:relative;margin-left:15px;margin-top:2px;font-family:helvetica;font-size:14px;font-weight:100;color:black;" name="searchterm" placeholder="Search Campaigns&nbsp;.&nbsp;.&nbsp;.&nbsp;" type="text">
</form></div></div>';


if($view == 'brief') {

//display the title and description
echo '<div class="dropshadow well grid_24" style="text-align: left; width: 860px;margin-top:20px;">
	<div class="grid_18 alpha">';
    if($logo != 'graphics/nologo.png') {
    echo'
	<img style="border: 1px solid black;" src="',$logo,'" height="80" width="80" />';
    }
    
    echo'
    
    <table class="table">
    <tbody>
    
    <form action="managecampaign.php?id=',$campaignID,'&view=brief&action=savecampaign" method="POST">
    
    <tr>
    <td>Campaign Title:</td>
    <td><input style="width:300px;" type="text" name="title" value="',$title,'" placeholder="Bridge over troubled water" /></td>
    </tr>

    <tr>
    <td>Description of Photo: </td>
    <td><textarea style="width:600px;height:150px;" rows="4" cols="100" name="description">',$description,'</textarea></td>
    </tr>
    
    <tr>
    <td>Example/Logo Link:</td>
    <td><input style="width:300px;" type="text" name="examplelink" value="',$examplelink,'" /></td>
    </tr>
    
    <tr>
    <td>What will you be using the photo for? </td>
    <td><input type="checkbox" name="use[]" value="print" /> Print (magazine, newspaper, brochure, etc.) &nbsp;&nbsp;&nbsp; <input type="checkbox" name="use[]" value="webuse" /> Web Use &nbsp;&nbsp;&nbsp;<br /> <input type="checkbox" name="use[]" value="emailpromotion" /> Email Promotion &nbsp;&nbsp;&nbsp; <input type="checkbox" name="use[]" value="personaluse" /> Personal Use </td>
    </tr>
    
    <tr>
    <td>Choose the license you would like for your photo:</td>
    <td><input type="checkbox" name="license[]" value="nonexclusive" /> Non-Exclusive &nbsp;&nbsp;&nbsp; <input type="checkbox" name="license[]" value="exclusive" /> Exclusive &nbsp;&nbsp;&nbsp;</td>
    </tr>
    
    <tr>
    <td>Location of Use:</td>
    <td><input style="margin-top:5px;width:300px;" type="text" name="location" value="',$location,'" /></td>
    </tr>
    
    <tr>
    <td>Length of Use:</td>
    <td><input style="margin-top:5px;width:300px;" type="text" name="lengthofuse" value="',$lengthofuse,'" /></td>
    </tr>
    
    <tr>
    <td>Additional Terms:</td>
    <td><textarea style="margin-top:5px;width:300px;" rows="4" cols="100" name="additionalterms">',$additionalterms,'</textarea></td>
    </tr>
    
    <tr>
    <td>Budget: </td>
    <td>$',$quote,'</td>
    </tr>
    
    <tr>
    <td>Time frame:</td>
    <td>',$timeframe,'
    </td>
    </tr>
    
    <tr>
    <td><input class="btn btn-success" style="width:150px;height:35px;" type="submit" value="Save Campaign" /></td>
    </tr>
    </form>
    
    </tbody>
    </table>';


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
        
		echo '<div style="font-size: 17px; margin-top: -15px;"><b>Winner</b>: ',$fullname,'</div>
        <div style="font-size: 17px;"><b>Chosen Photo:</b> "<a href="fullsizeme.php?id=',$winnerphotoid,'">',$winnerphototitle,'</a>"</div>';
	}
	//otherwise a winner hasn't been selected
	else {
		//display "no winner has been chosen yet"
		echo '<h3 style="font-size: 18px; margin-top: -15px;">No winner has been selected yet.</h3>';
	}
	echo '</div>';
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

    //Campaign Agreement Modal
    
        echo'<div class="modal hide fade" id="campaignagreement" style="overflow-y:scroll;overflow-x:hidden;width:850px;margin-left:-400px;">

        <div class="modal-header">
        <a style="float:right" class="btn btn-primary" data-dismiss="modal">Close</a>
        <img style="margin-top:-4px;" src="graphics/logoteal.png" height="28" width="100" />&nbsp;&nbsp;<span style="font-size:16px;">INTELLECTUAL PROPERTY LICENSE AGREEMENT</span>
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


    (i) Term:  $lengthofuse, starting from $today;

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
        {LicensorName}
	{LicensorUserName}
	{LicensorE-mailAddress}



    (ii) if to the Licensee: 
        {LicenseeName}
	{LicenseeUserName}
	{LicenseeE-mailAddress}

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
{LicenseeE-mailAddress}");

echo'
</pre>
</div>
    

</div>
</div>
</div>';

    
    echo'
         <a data-toggle="modal" data-backdrop="static" href="#campaignagreement"><button class="btn btn-success"><b>LICENSE AGREEMENT</b></button></a>';
    echo '</div>';    
}

echo '</div>';

//GET MESSAGE
$getmessage = "SELECT comments FROM campaigns WHERE id = '$campaignID'";
$getmessagequery  = mysql_query($getmessage);
$message = mysql_result($getmessagequery, 0, "comments");

if($message != '') {
echo'
<div class="grid_18 dropshadow well" style="postion:relative;float:top;width:860px;"><span style="font-size:16px;font-weight:bold;">Buyer Feedback:<br /></span>
<span style="font-size:14px;">',$message,'</span>
</div>';
}


} //end of brief view

elseif($view == 'photogs') {

//FACEPILE
$query3 = mysql_query("SELECT * FROM campaignphotos WHERE campaign = '$campaignID'");
$numfaces = mysql_num_rows($query3);

echo'<div class="grid_18 dropshadow well" style="margin-top:40px;width:890px;">
<div style="font-size:18px;font-family:helvetica neue,arial;font-weight:200;padding-bottom:5px;">Photographers in this competition:</div>';
for($iii=0; $iii < $numfaces; $iii++) {
    $facemail = mysql_result($query3,$iii,'emailaddress');
    $pos = strpos($prevlist,$facemail);
        if($pos !== false) {
            continue;
        }
     $query4 = mysql_query("SELECT profilepic,user_id FROM userinfo WHERE emailaddress = '$facemail'");
     $facephoto = mysql_result($query4,0,'profilepic');
     $faceid = mysql_result($query4,0,'user_id');
    $facephoto = 'http://photorankr.com/' . $facephoto;
    
    echo'<a class="hover" href="viewprofile.php?u=',$faceid,'"><img class="item" src="',$facephoto,'" height="100" width="100" /></a>';    
        
    $prevlist = $prevlist . $facemail;
}
echo'</div>';

} // end of photog view



elseif($view == '') {

      $sort = htmlentities($_GET['sort']); 
    
        echo'<br /><br /><br /><br /><br /><div style="width:940px;text-align:center;font-size:14px;font-weight:200;"><div style="margin-left:20px;"><a class="green" style="text-decoration:none;'; if($sort == 'newest') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="managecampaign.php?id=',$campaignID,'&sort=newest">Newest Entries</a> | <a class="green" style="text-decoration:none;color:#333;'; if($sort == '') {echo'color:#6aae45;';} else {echo'color:#333;';} echo'" href="managecampaign.php?id=',$campaignID,'">Top Ranked Entries</a></div></div>';
        
         if(htmlentities($_GET['action']) == 'rank') {
        $id = mysql_real_escape_string($_POST['imageid']);
        $ranking = mysql_real_escape_string($_POST['ranking']);
        $rankquery = mysql_query("UPDATE campaignphotos SET votes = 1, points = '$ranking' WHERE id = '$id'");
    
    }
    
    if(htmlentities($_GET['action']) == 'delete') {
        $id = mysql_real_escape_string($_POST['imageid']);
        $imagecaption = mysql_real_escape_string($_POST['caption']);
        $imagethumb = mysql_real_escape_string($_POST['thumb']);

        $deletequery = mysql_query("UPDATE campaignphotos SET trash = 1 WHERE id = '$id'");
        
        echo'<div style="font-size:16px;font-weight:400;margin-top:20px;margin-left:35px;"><img src="',$imagethumb,'" height="40" width="40" />&nbsp;&nbsp;"',$imagecaption,'" Deleted</div>';
    
    }
    
    if(htmlentities($_GET['action']) == 'comment') {
        $id = mysql_real_escape_string($_POST['imageid']);
        $imagecaption = mysql_real_escape_string($_POST['caption']);
        $comment = mysql_real_escape_string(htmlentities($_POST['comment']));
        $imagethumb = mysql_real_escape_string($_POST['thumb']);

        $commentquery = mysql_query("INSERT INTO campaigncomments (comment,campaign,emailaddress,imageid) VALUES ('$comment','$campaignID','$repemail','$id')");
        
        echo'<div style="font-size:16px;font-weight:400;margin-top:20px;margin-left:35px;"><img src="',$imagethumb,'" height="40" width="40" />&nbsp;&nbsp;"',$imagecaption,'" Comment Posted</div>';
            
    }
                
//select the photos in this campaign
if($sort == "newest") {
	$photosquery = "SELECT * FROM campaignphotos WHERE campaign=".$campaignID." AND trash <> '1' ORDER BY id DESC LIMIT 12";
}
elseif($sort == '') {
	$photosquery = "SELECT * FROM campaignphotos WHERE campaign=".$campaignID." AND trash <> '1' ORDER BY score DESC, id DESC LIMIT 12";
}
$photosresult = mysql_query($photosquery);

echo '<div id="thepics" class="grid_18" style="width:940px;margin-left:-45px;margin-top:-30px;padding:35px;">';
//loop through the result to get all of the necessary information 
for($iii=0; $iii < mysql_num_rows($photosresult); $iii++) {
	//get the information for the current photo
	$photo[$iii] = mysql_result($photosresult, $iii, "source");
    $photo[$iii] = str_replace("userphotos/","userphotos/medthumbs/", $photo[$iii]);
	$points = mysql_result($photosresult, $iii, "points");
	$votes = mysql_result($photosresult, $iii, "votes");
	$average[$iii] = number_format(($points / $votes),2);
	$id = mysql_result($photosresult, $iii, "id");
	$caption[$iii] = mysql_result($photosresult, $iii, "caption");

	list($width, $height) = getimagesize($photo[$iii]);
	$imgratio = $height / $width;
   	$heightls = $height / 2.5;
   	$widthls = $width / 2.5;

	echo '
    
    <div class="fPic" id="',$id,'" style="width:280px;overflow:hidden;float:left;margin-left:30px;margin-top:30px;"><a href="fullsizeme.php?id=',$id,'">
                
                <div style="width:280px;height:280px;overflow:hidden;">
                <div class="statoverlay" style="z-index:1;left:0px;top:215px;position:relative;background-color:black;width:280px;height:90px;"><p style="line-spacing:1.48;padding:5px;color:white;"><span style="font-size:18px;font-weight:100;">',$caption[$iii],'</span><br><span style="font-size:15px;font-weight:100;">Rank: ',$average[$iii],'<br></div>

                <img onmousedown="return false" oncontextmenu="return false;" style="position:relative;top:-90px;min-height:300px;min-width:280px;" src="',$photo[$iii],'" height="',$heightls,'px" width="',$widthls,'px" /></a>
                <br />      
                </div><br />
                
                <!--DROPDOWN FEEDBACK-->
                    <div class="panel',$id,'">
                    
                 <div style="font-size:15px;font-family:helvetica;font-weight:200;">Feedback:</div>   
                 
                <form action="managecampaign.php?id=',$campaignID,'&sort=',$sort,'&action=comment" method="post">
                <textarea style="width:255px;height:70px;" rows="4" cols="60" name="comment"></textarea>
                <input type="hidden" name="imageid" value="',$id,'" />
                <input type="hidden" name="caption" value="',$caption[$iii],'" />
                <input type="hidden" name="thumb" value="',$photo[$iii],'" /> 
                <div style="width:260px;padding:4px;"><button style="width:60px;float:right;" class="btn btn-success"type="submit">Post</a></div>
                </form>
                
                
                <br />
             
        <div style="width:260px;margin-top:20px;">
            <div style="float:left;margin-left:30px;"><img src="../graphics/rank_icon.png"/> <span id="rank"> Rank: </span> <span class="numbers">', $average[$iii],'</span><span id="littlenumbers"> /10 </span></div>
            
            <script>
                function submitMyForm(sel) {
                    sel.form.submit();
                }
            </script>
            
           <div style="float:left;"><form id="Form1" action="managecampaign.php?id=',$campaignID,'&sort=',$sort,'&action=rank" method="post">
            <select name="ranking" style="width:95px; height:30px;margin-left:15px;margin-top:-3px;" onchange="submitMyForm(this)">
            <option value="" style="display:none;">&#8212;</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
            </select>
            <input type="hidden" name="imageid" value="',$id,'" />
            </form>
           </div>
        </div>
        
        <div style="float:left;">
        <form action="managecampaign.php?id=',$campaignID,'&sort=',$sort,'&action=delete" method="post">
         <input type="hidden" name="imageid" value="',$id,'" />
         <input type="hidden" name="caption" value="',$caption[$iii],'" />
         <input type="hidden" name="thumb" value="',$photo[$iii],'" /> 
        <button type="submit" style="width:110px;padding:5px;margin-top:15px;margin-left:5px;" class="btn btn-danger">Delete</button></div>
        </form>
        
        <br />

        <div style="flaot:left;"><a style="width:110px;padding:5px;margin-top:15px;margin-left:10px;" class="btn btn-warning" href="fullsizeme.php?id=',$id,'">Buy</a></div>
        
        <br/>
        
        </div>
                    
                    <a name="',$id,'" href="#"><p class="flip',$id,'" style="font-size:15px;font-weight:200;"></a>Give Feedback</p>
                    
                    
                    <style type="text/css">
                    p.flip',$id,' {
                    padding:10px;
                    width:258px;
                    clear:both;
                    text-align:center;
                    background:white;
                    border:solid 1px #c3c3c3;
                    }

                    p.flip',$id,':hover {
                    background-color: #ccc;
                    }

                    div.panel',$id,' {
                    display:none;
                    clear:both;
                    padding:300px;
                    padding:5px;
                    text-align:left;
                    background:white;
                    border:solid 1px #c3c3c3;
                    }
                    </style>
                    
                    <!--HIDDEN COMMENT SCRIPT-->
                    <script type="text/javascript">   
                    $(document).ready(function(){
                    $(".flip',$id,'").click(function(){
                        $(".panel',$id,'").slideToggle("slow");
                    });
                    });
                    </script>
                    
                </div>

                
        ';
    
}
echo '</div>';
echo '<br /><div class="grid_24" id="loadMorePicsView" style="display: none; text-align: center;font-family:arial,helvetica neue; font-size:15px;">Loading More Photos&hellip;</div>';

echo '<script>

var last = 0;

	$(window).scroll(function(){
		if($(window).scrollTop() > $(document).height() - $(window).height()-100) {
			if(last != $(".fPic:last").attr("id")) {
				$("div#loadMorePicsView").show();
				$.ajax({
					url: "loadMorePicsView.php?lastPicture=" + $(".fPic:last").attr("id")+"&view=', $_GET['view'], '",
					success: function(html) {
						if(html) {
							$("#thepics").append(html);
							$("div#loadMorePicsView").hide();
						}
					}
				});
				last = $(".fPic:last").attr("id");
			}
		}
	});
</script>';


} //end of submissions view

?>


</body>
</html>
<?php

mysql_close();

?>