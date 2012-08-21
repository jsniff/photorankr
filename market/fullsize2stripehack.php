<?php

//connect to the database
require "db_connection.php";
require "functionscampaigns3.php";
require_once("stripe/lib/Stripe.php");

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
    
     //GET INFO FROM CURRENT PHOTO ID
    $imageid = htmlentities($_GET['imageid']);
    
    //add to the views column
    $updatequery = mysql_query("UPDATE photos SET views=views+1 WHERE   id='$imageid'") or die(mysql_error());
    
    //add to the usermarketviews column
    $buyerupdatequery = mysql_query("UPDATE photos SET buyermarketviews=buyermarketviews+1 WHERE id='$imageid'") or die(mysql_error());

    $imagequery = "SELECT * FROM photos WHERE id = '$imageid'";
    $imagequeryrun= mysql_query($imagequery);
    $image = mysql_result($imagequeryrun,0,'source');
    $owner = mysql_result($imagequeryrun,0,'emailaddress');
    $price = mysql_result($imagequeryrun,0,'price');
    $points = mysql_result($imagequeryrun,0,'points');
    $caption = mysql_result($imagequeryrun,0,'caption');
    $votes = mysql_result($imagequeryrun,0,'votes');
    $ranking = ($points/$votes);
    $ranking = number_format($ranking,2);
    $location = mysql_result($imagequeryrun,0,'location');
    $camera = mysql_result($imagequeryrun,0,'camera');
    $exhibit = mysql_result($imagequeryrun,0,'set_id');
    $about = mysql_result($imagequeryrun,0,'about');
    $tag1 = mysql_result($imagequeryrun,0,'tag1');
    if($tag1) {$tag1 = $tag1 . ", ";}
    $tag2 = mysql_result($imagequeryrun,0,'tag2');
    if($tag2) {$tag2 = $tag2 . ", ";}
    $tag3 = mysql_result($imagequeryrun,0,'tag3');
    if($tag3) {$tag3 = $tag3 . ", ";}
    $tag4 = mysql_result($imagequeryrun,0,'tag4');
    if($tag4) {$tag4 = $tag4 . ", ";}
    $singlestyletags = mysql_result($imagequeryrun,0,'singlestyletags');
    $singlecategorytags = mysql_result($imagequeryrun,0,'singlecategorytags');
    $singlestyletagsarray = explode("  ", $singlestyletags);
    $singlecategorytagsarray   = explode("  ", $singlecategorytags);
    for($iii=0; $iii < count($singlestyletagsarray); $iii++) {
        if($singlestyletagsarray[$iii] != '') {
        $singlestyletagsfinal = $singlestyletagsfinal . $singlestyletagsarray[$iii] . ", "; }
    }
    for($iii=0; $iii < count($singlecategorytagsarray); $iii++) {
        if($singlecategorytagsarray[$iii] != '') {
        $singlecategorytagsfinal = $singlecategorytagsfinal . $singlecategorytagsarray[$iii] . ", "; }
    }
    
    $keywords = $tag1 . $tag2 . $tag3 . $tag4 . $singlestyletagsfinal . $singlecategorytagsfinal;
    $keywords = substr_replace($keywords ," ",-2);
    
    $ownerquery = mysql_query("SELECT * FROM userinfo WHERE emailaddress = '$owner'");
    $profilepic = mysql_result($ownerquery,0,'profilepic');
    $profilepic = 'http://photorankr.com/' . $profilepic;
    $firstname = mysql_result($ownerquery,0,'firstname');
    $lastname = mysql_result($ownerquery,0,'lastname');
    $userid = mysql_result($ownerquery,0,'user_id');
    $fullname = $firstname . " " . $lastname;
    $imagebig = str_replace("userphotos/", "$_SERVER[DOCUMENT_ROOT]/userphotos/", $image);
    $imageoriginal = str_replace("userphotos/", "$_SERVER[DOCUMENT_ROOT]/userphotos/bigphotos/", $image);
    $imagebig2 = str_replace("$_SERVER[DOCUMENT_ROOT]/userphotos/", "http://photorankr.com/userphotos/", $imagebig); 
    $title = mysql_result($imagequeryrun,0,'caption');


$emailtrial = "tyler.sniff@gmail.com";

 $getstripeinfo = "SELECT * FROM userinfo WHERE emailaddress = '$emailtrial'";
$striperesult = mysql_query($getstripeinfo); 
$stripepubkey = mysql_result($striperesult, 0, 'pubkey');



$getstripeinfo = "SELECT * FROM userinfo WHERE emailaddress = '$emailtrial'";
$striperesult = mysql_query($getstripeinfo); 
$stripekey = mysql_result($striperesult, 0, 'token');



//calculate the size of the picture

$maxwidth=550;
$maxheight=550;

list($originalwidth, $originalheight)=getimagesize($imageoriginal);

list($width, $height)=getimagesize($imagebig);
$imgratio=$width/$height;

if($imgratio > 1) {
    $newwidth=$maxwidth;
    $newheight=$maxwidth/$imgratio;
}
else {
    $newheight=$maxheight;
    $newwidth=$maxheight*$imgratio;
}
    
 
    
    //SAVE PHOTO QUERY
    if($_GET['ml'] == "saved") {
            
            $maybecheckquery=mysql_query("SELECT * FROM maybe WHERE emailaddress = '$repemail'");
            $nummaybesaved = mysql_num_rows($maybecheckquery);

            for($iii=0; $iii < $nummaybesaved; $iii++) {
                $maybeimageid = mysql_result($maybecheckquery,$iii,'imageid');
                $maybesavedlist = $maybesavedlist." ".$maybeimageid;
            }
                
            //MAKE SURE CORRECT BUTTON SHOWS
            $search_string3=$maybesavedlist;
            $regex3="/$imageid/";
            $maybematch=preg_match($regex3,$search_string3);
            
            if($maybematch < 1) {
            $savequery = mysql_query("INSERT INTO maybe (source,caption,price,emailaddress,imageid) VALUES  ('$imagebig2','$caption','$price','$repemail','$imageid')");
            
        }
    }
    
    //REMOVE PHOTO QUERY
    if(htmlentities($_GET['action']) == "removed") { 
        $querycheck = mysql_query("SELECT emailaddress FROM cart WHERE imageid = '$imageid'");
        $emailcheck = mysql_result($querycheck,0,'emailaddress');
        if($repemail == $emailcheck) {
            $removequery = mysql_query("DELETE FROM cart WHERE imageid = '$imageid' AND emailaddress = '$repemail'");
        }
    }

           
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://w3.org/TR/html4/strict.dtd">
<html>
  <head>      
   <meta name="description" content="View the fullsize image of a photo from a campaign">
   <meta name="keywords" content="campaign, view, image, full-size, photo, photography">
   <meta name="author" content="The PhotoRankr Team">
 
	<title>Fullsize Photo - "<?php echo $title; ?>"</title>
 <link rel="stylesheet" href="css/bootstrapNew.css" type="text/css" />
    <link rel="stylesheet" href="css/reset.css" type="text/css" />
    <link rel="stylesheet" href="css/text.css" type="text/css" />
    <link rel="stylesheet" href="css/960_24.css" type="text/css" />
    <link rel="stylesheet" href="css/index.css" type="text/css"/> 
	<link rel="stylesheet" type="text/css" href="css/all.css"/>

    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
    <script src="bootstrap-dropdown.js" type="text/javascript"></script>
    <script src="bootstrap-collapse.js" type="text/javascript"></script>
    <link rel="shortcut icon" type="image/x-png" href="graphics/favicon.png"/>
     
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
            .show { display: block;  }
            .hide { display: none; }
            .click { }
            .clicked {background-color:black;}
            .rollover {border:1px solid transparent;}
            .rollover:hover {border:1px solid black;}
        </style>

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


   <script type="text/javascript" src="https://js.stripe.com/v1/"></script>
        <!-- jQuery is used only for this example; it isn't required to use Stripe -->
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script type="text/javascript">
            // this identifies your website in the createToken call below
           // Stripe.setPublishableKey('pk_NuzRruZd0ks8VMufKgWZtecdiIqFK');
//PhotoRankr Key
//Stripe.setPublishableKey('pk_wyF8CPirmy3KmAv7lmf5gKwV5bElr');


//Tyler's key
//Stripe.setPublishableKey('pk_07nH7wAErP9SujawnAdTmrkb047qv'); 

Stripe.setPublishableKey('pk_0ATtgXPvkNA536m9FWH5xNYZmZLBK'); 


//Stripe.setPublishableKey('sk_07nHg71XdNctNwyNyBzHk9LdiBTpH');
            function stripeResponseHandler(status, response) {
                if (response.error) {
                    // re-enable the submit button
                    $('.submit-button').removeAttr("disabled");
                    // show the errors on the form
                    $(".payment-errors").html(response.error.message);
                } else {
                    var form$ = $("#payment-form");
                    // token contains id, last4, and card type
                    var token = response['id'];
                    // insert the token into the form so it gets submitted to the server
                    form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
                    // and submit
                    form$.get(0).submit();
                }
            }

            $(document).ready(function() {
                $("#payment-form").submit(function(event) {
                    // disable the submit button to prevent repeated clicks
                    $('.submit-button').attr("disabled", "disabled");
                    // createToken returns immediately - the supplied callback submits the form if there are no errors
                    Stripe.createToken({
                        number: $('.card-number').val(),
                        cvc: $('.card-cvc').val(),
                        exp_month: $('.card-expiry-month').val(),
                        exp_year: $('.card-expiry-year').val()
                    }, stripeResponseHandler);
                    return false; // submit from callback
                });
            });

            if (window.location.protocol === 'file:') {
                alert("stripe.js does not work when included in pages served over file:// URLs. Try serving this page over a webserver. Contact support@stripe.com if you need assistance.");
            }
        </script>


















</head>

<body class="background" style="overflow-x: hidden;">

<?php navbarsweet(); ?>



<!--MAYBE LATER MODAL-->
<div class="modal hide fade" id="maybemodal" style="overflow:hidden;">
      
<?php
if($_SESSION['loggedin'] != 2) {

echo'
<div class="modal-header">
<a style="float:right" class="btn btn-primary" data-dismiss="modal" >Close</a>
<img style="margin-top:-4px;float:left;" src="graphics/logomarket.png" width="180" />
  </div>
  <div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:500px;">
		
<img style="border: 1px solid black;margin-left:10px;margin-top:5px;" src="',$imagebig2,'" 
height="100px" width="100px" /> 

<div style="width:500px;margin-left:130px;margin-top:-70px;">
"',$title,'"<br />                 

Please Login to Save this Photo.<br /><br /><br />

</div>
</div>';

    }
        
        
     if($_SESSION['loggedin'] == 2) {
    
		$maybequery=mysql_query("SELECT * FROM maybe WHERE emailaddress ='$repemail'");
        $numsaved = mysql_num_rows($maybequery);
        for($iii=0; $iii < $numsaved; $iii++) {
            $savedimageid = mysql_result($maybequery,$iii,'imageid');
            $prevsavedlist = $prevsavedlist.",".$savedimageid;
        }
        
		//MAKE SURE FOLLOWER ISN'T ADDED TWICE
		$search_string=$prevsavedlist;
		$regex="/$imageid/";
		$match=preg_match($regex,$search_string);
		
        if ($match > 0) {
			
            echo'
                <div class="modal-header">
                <a style="float:right" class="btn btn-primary" data-dismiss="modal">Close</a>
                <img style="margin-top:-4px;" src="graphics/logomarket.png" width="150" />&nbsp;&nbsp;<span style="font-size:16px;">You already saved this photo</span>
                </div>
                <div modal-body" style="width:600px;">

                <div id="content" style="font-size:16px;width:500px;">
		
                <img style="border: 1px solid black;margin-left:10px;margin-top:5px;" src="',$imagebig2,'" 
height="100px" width="100px" />

                <div style="width:500px;margin-left:130px;margin-top:-78px;">
"',$caption,'"<br />                 

                <br /><br /><br />

                </div>
                </div>';
        }

        else {
            
			echo'
                <div class="modal-header">
                <a style="float:right" class="btn btn-primary" href="fullsize2.php?imageid=',$imageid,'&ml=saved">Close</a>
                <img style="margin-top:-4px;" src="graphics/logomarket.png" width="150" /></div>
                
                <div modal-body" style="width:700px;">

                <div id="content" style="font-size:16px;width:500px;">
		
                <img style="border: 1px solid black;margin-left:10px;margin-top:20px;" src="',$imagebig2,'" 
height="100px" width="100px" />

                <div style="width:500px;margin-left:130px;margin-top:-70px;">
                "',$caption,'"<br />                 

                Photo saved. 

               <br /><br /><br />

                </div>
                </div>';
            
        }
    
    }
        
?>

</div>
</div>




<div class="container_24" style="padding-top:55px;padding-bottom:30px;"><!--Grid container begin-->

<?php
$campaigntitlequery = mysql_query("SELECT title from campaigns WHERE id = '$campaign'");
$camptitle = mysql_result($campaigntitlequery,0,'title');
?>
 
<!--TITLE OF PHOTO-->     
<div class="grid_24">
<div class="grid_21 pull_2"><div style="margin-top:10px;padding-top:5px;padding-left:3px;line-height:30px;font-size:25px;"><?php echo'"',$title,'"'; ?>
</div></div></div>

<!--BIG IMAGE BOX-->
<div class="grid_24">

<div class="grid_10 pull_2" style="margin-top:150px;">
<div class="phototitletest" style="height:<?php echo $newheight; ?>px;width:<?php echo $newwidth; ?>px;margin-top:-135px;">
<img onmousedown="return false" oncontextmenu="return false;" alt="<?php echo $tags; ?>" src="<?php echo $imagebig2; ?>" height="<?php echo $newheight; ?>px" width="<?php echo $newwidth; ?>px" /></div>
</div> 

<?php
$smallwidth = number_format(($originalwidth/2.5),0,',','');
$smallheight = number_format(($originalheight/2.5),0,',','');
$smallprice = number_format(($price / 2.5),0,',','');
$medwidth = number_format(($originalwidth/1.3),0,',','');
$medheight = number_format(($originalheight/1.3),0,',','');
$medprice = number_format(($price / 1.5),0,',','');
?>


        <script type="text/javascript">

var rowcount = 1;
var rowcount2= 1;
var rowcount3 = 1;
var smallpricejava = "<? print $smallprice; ?>";
var medpricejava =  "<? print $medprice; ?>";
var largepricejava = "<? print $medprice; ?>";
            function showClicked() {
                var select = document.getElementById('row');
                select.className = 'clicked';
                rowcount++;
                selectedpricephoto = smallpricejava;
                if(rowcount>2){
                    select.className = 'unclicked';
                    rowcount=rowcount-2;
                }
                if(rowcount2>1){
            var select = document.getElementById('row2');
               select.className = 'unclicked';
                }
                if(rowcount3>1){
            var select = document.getElementById('row3');
               select.className = 'unclicked';
                }

            }
                function showClicked2() {
                var select = document.getElementById('row2');
                select.className = 'clicked';
                selectedpricephoto = medpricejava;
                   rowcount2++;
                if(rowcount2>2){
                    select.className = 'unclicked';
                    rowcount2=rowcount2-2;
                }
                 if(rowcount>1){
            var select = document.getElementById('row');
               select.className = 'unclicked';
                }
                if(rowcount3>1){
            var select = document.getElementById('row3');
               select.className = 'unclicked';
                }
            }
                function showClicked3() { 
                var select = document.getElementById('row3');
                select.className = 'clicked';
                selectedpricephoto = largepricejava;
                rowcount3++;
                if(rowcount3>2){
                    select.className = 'unclicked';
                    rowcount3=rowcount3-2;
                }
                 if(rowcount>1){
            var select = document.getElementById('row');
               select.className = 'unclicked';
                }
                 if(rowcount2>1){
            var select = document.getElementById('row2');
               select.className = 'unclicked';
                }
            }
    


        </script>

<div class="grid_4 push_4" style="margin-top:20px;">
<div class="span6">
<table class="table">
<thead>
<tr>
<th>Size</th>
<th>Resolution</th>
<th>Price</th>
</tr>
</thead>
<tbody>
<tr id="row" onclick="showClicked();">
<td>Small</td>
<td><?php echo $smallwidth; ?> X <?php echo $smallheight; ?></td>
<td>$<?php echo $smallprice; ?></td>
</tr>
<tr id="row2" onclick="showClicked2();">  
<td>Medium</td>
<td><?php echo $medwidth; ?> X <?php echo $medheight; ?></td>
<td>$<?php echo $medprice; ?></td>
</tr>
<tr id="row3" onclick="showClicked3();">
<td>Large</td>
<td><?php echo $originalwidth; ?> X <?php echo $originalheight; ?></td>
<td>$<?php echo $price; ?></td>
</tr>
<tr>
<td>License</td>
<td colspan="2"><input style="margin-left:130px;"  type="radio" name="license" value="standard"  onclick="showSelectHide();" /><a style="color:black;text-decoration:none;" href="#" id="licensepopover" rel="popover" data-content="

<span style='font-size:13px;'>A perpetual, non-exclusive, non-transferable, worldwide license to use the Content for the following permitted uses:
</br><ul>
<li>Advertising and promotional projects (printed materials, commercials, etc.)</li>
<li>Entertainment applications (books, editorials, broadcast presentations, etc.)</li>
<li>Online or electronic publications (includes use on web pages up to 1200 x 800 pixels)</li>
<li>Prints, posters, and reproductions for personal or promotional purposes up to 500,000 times</li>

</ul></br>
This license does not allow you to:
</br><ul>
<li>Use the Content in products for resale, license, or other distribution unless original is fundamentally modified</li>
<li>Use the Content in more than one location at a time</li>
<li>Incorporate the Content in any product that results in a re-distribution or re-use of the Content</li>
<li>Use the Content in a format that enables it to be downloaded in any peer-to-peer file sharing arrangement</li>
<li>Use 'Editorial Use Only' for any commercial use</li>
<li>Reproduce the Content in excess of 500,000 times</li>
</ul>
</br>
The Standard Content License Agreement governs this option.
</br></br>
</span>

" data-original-title="What is the Standard License?">&nbsp;&nbsp;&nbsp;Standard</a>

<script>  
    $(function ()  
    { $("#licensepopover").popover();  
    });  
</script>

<input style="margin-left:15px;" type="radio" name="license" value="extended"  onclick="showSelect();"/>&nbsp;&nbsp;&nbsp;Extended Options</td>

</tr>
</tbody>
</table>

        <script type="text/javascript">
            function showSelect() {
                var select = document.getElementById('table');
                select.className = 'show';
            }
            function showSelectHide() {
                var select = document.getElementById('table');
                select.className = 'hide';
            }
        </script>

<div id="table" class="hide">
<table class="table">
<tbody>
<tr>

<td><a style="color:black;text-decoration:none;" href="#" id="multiseatpopover" rel="popover" data-content="<span style='font-size:13px;'>This option allows you to extend usage of the Content to more than one person within your organization, provided that all users are either employees or agree to be bound by the Extended Content License Provisions.
</br></br></span>

" data-original-title="Multi-Seat (Unlimited)">Multi-Seat (Unlimited)</a>

<script>  
    $(function ()  
    { $("#multiseatpopover").popover();  
    });  
</script>

</td>
<td colspan="2">+ 20</td>
</tr>
<tr>
<td><a style="color:black;text-decoration:none;" href="#" id="unlimitprintpopover" rel="popover" data-content="<span style='font-size:13px;'>This Extended License Provision removes the 500,000 limit on reproductions and allows for unlimited reproductions.</br></br>The Extended Content License Provisions govern this option.</br></br></span>

" data-original-title="Unlimited Reproduction / Print Runs">Unlimited Reproduction / Print Runs</a>

<script>  
    $(function ()  
    { $("#unlimitprintpopover").popover();  
    });  
</script>

</td>
<td colspan="2">+ $35</td>
</tr>
<tr>
<td><a style="color:black;text-decoration:none;" href="#" id="resalepopover" rel="popover" data-content="<span style='font-size:13px;'>This option allows you to produce the following items for resale, license, or other distribution:</br>
<ul>
<li>Up to 100,000 cards, stationery items, stickers, or paper products</li>
<li>Up to 10,000 posters, calendars, mugs, or mousepads</li>
<li>Up to 2,000 t-shirts, apparel items, games, toys, entertainment goods, or framed artwork</li>
</br>
The Extended Content License Provisions govern this option.
</br></br>
</span>

" data-original-title="Items for Resale - Limited Run">Items for Resale - Limited Run</a>

<script>  
    $(function ()  
    { $("#resalepopover").popover();  
    });  
</script>

</td>
<td colspan="2">+ $35</td>
</tr>
<tr>
<td><a style="color:black;text-decoration:none;" href="#" id="electronicresalepopover" rel="popover" data-content="<span style='font-size:13px;'>This option allows you to produce the following items for resale, license, or other distribution:</br>
<ul>
<li>Electronic templates for e-greeting or similar cards</li>
<li>Electronic templates for web or applications development</li>
<li>PowerPoint or Keynote templates</li>
<li>Screensavers and e-mail or brochures templates</li>
</ul>
<br>
Under this option:
<ul>
<li>The right to produce the E-Resale Merchandise does not grant any intellectual property or other rights to the Content</li>
<li>You agree to indemnify PhotoRankr from any expense incurred in connection with any E-Resale Merchandise</li>
</ul>
</br>
The Extended Content License Provisions govern this option.
</br></br>
</span>

" data-original-title="Electronic Items for Resale or Other Distribution - Unlimited Run">Electronic Items for Resale or Other Distribution - Unlimited Run</a>

<script>  
    $(function ()  
    { $("#electronicresalepopover").popover();  
    });  
</script>

</td>
<td colspan="2">+ $35</td>
</tr>
<tr>
</tbody>
</table>
</div>

<div>


<?php

        $removequery=mysql_query("SELECT * FROM maybe WHERE emailaddress = '$repemail'");
        $numsavedremove = mysql_num_rows($removequery);

        for($iii=0; $iii < $numsavedremove; $iii++) {
            $removeimageid = mysql_result($removequery,$iii,'imageid');
            $removelist = $removelist." ".$removeimageid;
        }

		//MAKE SURE CORRECT BUTTON SHOWS
		$search_string4=$removelist;
		$regex4="/$imageid/";
		$removematch=preg_match($regex4,$search_string4);
        
        if($removematch > 0) {
        echo'<a class="btn btn-primary" style="margin-left:200px;width:80px;float:left;" href="#"">Photo Saved</a>';
        }
        else {
        echo'<a class="btn btn-success" style="margin-left:200px;width:80px;float:left;" data-toggle="modal" data-backdrop="static" href="#maybemodal">Maybe Later</a>';
        }

		$cartquery=mysql_query("SELECT * FROM cart WHERE emailaddress = '$repemail'");
        $numsaved = mysql_num_rows($cartquery);

        for($iii=0; $iii < $numsaved; $iii++) {
            $savedcartimageid = mysql_result($cartquery,$iii,'imageid');
            $prevsavedcartlist = $prevsavedcartlist." ".$savedcartimageid;
        }

		//MAKE SURE CORRECT BUTTON SHOWS
		$search_string2=$prevsavedcartlist;
		$regex2="/$imageid/";
		$cartmatch=preg_match($regex2,$search_string2);
        
     
        

        if($cartmatch > 0) {
        echo'<a class="btn btn-danger" style="margin-left:10px;width:120px;float:left;" href="fullsize2.php?imageid=',$imageid,'&action=removed">Remove from Cart</a>';
        }
        else {
            echo'<a class="btn btn-success" style="margin-left:10px;width:80px;float:left;" href="download2.php?imageid=',$imageid,'&action=added">Add to Cart</a>';		
}

?>
</div>

<div class="span6" style="margin-left:0px;margin-top:10px;">
<b>Photo Details</b><br /><hr />
<div><span style="float:left;">Photographer:</span><a style="font-weight:bold;color:#3e608c;" href="viewprofile.php?u=<?php echo $userid; ?>"><img style="float:left;margin-top:-10px;margin-left:5px;border: 1px solid rgb(115,115,115);" src="<?php echo $profilepic; ?>" height="30" width="30" /><span style="padding-left:5px;"><?php echo $fullname; ?></span></a></div>
<div style="margin-top:20px;">Photo Rank: <?php echo $ranking; ?>
<br />
<?php 
if($location) {echo'
Location: ',$location,'<br />'; }
if($about) {echo'
About Photo: ',$about,'<br />'; }
if($exhibit) {
$exname = mysql_query("SELECT * FROM sets WHERE id = '$exhibit'");
$exhibitname = mysql_result($exname,0,'title');
echo'
Exhibit: <a href="viewprofile.php?u=',$userid,'&view=exhibits&set=',$exhibit,'">',$exhibitname,'</a><br />'; }
if($keywords) {echo'
Keywords: ',$keywords,'<br />'; }
 ?>
</div>
<br /><br />
</div>
</div>

<!--SIMILAR PHOTOS CODE-->
<?php
$similarquery = mysql_query("SELECT * FROM photos WHERE (caption LIKE '$title' OR location LIKE '$location') ORDER BY (views) DESC");
$numsimilar = mysql_num_rows($similarquery);
if($numsimilar > 4) {
echo'
    <div class="span6">
    <b>Similar Photos:</b><br /><hr />';

    for($iii=0; $iii < $numsimilar && $iii < 5; $iii++) {
        $simphoto = mysql_result($similarquery,$iii,'source');
        $simphoto2 = str_replace("userphotos/", "http://photorankr.com/userphotos/medthumbs/", $simphoto); 
        $simimageid = mysql_result($similarquery,$iii,'id');

        echo'<a href="fullsize2.php?imageid=',$simimageid,'"><img class="rollover" style="float:left;margin-right:3px;" src="',$simphoto2,'" height="85" width="85" /></a>';
        
    }



if($_REQUEST['charge'] != 1) {


echo'

<div class="container_24" style="padding-top:80px;"> <!--container begin-->
<div class="grid_21 push_1 download1">
<div style="font-size:22px;text-align:center;">Download a watermark-free, high resolution copy below:</div>
<br />
<div class="grid_8">
<div class="grid_8 form">
 <div class="grid_8 title">
  <h1 class="titleh" style="text-shadow: 0.05em 0.05em 0.05em #665"> Secure payment with Stripe </h1>
 <div class="grid_7" style="margin-left:5px;background-color:rgb(243,245,246);padding:10px;border-radius:10px;">

        <!-- to display errors returned by createToken -->
        <span class="payment-errors" style="font-weight:bold;font-size:15px;"></span>

    <form action="',htmlentities($_SERVER['PHP_SELF']),'?charge=1" method="POST" id="payment-form">
    <div class="form-row" style="margin-left:25px;">
            
<input type="hidden" name="price" value="',$price,'">
<input type="hidden" name="firstname" value="',$firstname,'">
<input type="hidden" name="lastname" value="',$lastname,'">
<input type="hidden" name="image" value="',$imagebig2,'">
<input type="hidden" name="label" value="',$label,'">
<input type="hidden" name="imageID" value="',$imageID,'">
<input type="hidden" name="customeremail" value="',$customeremail,'">

                <label class="creditcards" style="margin-bottom:10px;">Card Number. We accept:<img src="card.jpg" style="width:215px;height:25px;margin-top:4px;border-radius:2px;"/> </label> 
                <input type="text" size="20" autocomplete="off" class="card-number" style;"/>
            </div>
            <div class="form-row" style="margin-left:25px;">
                <label class="creditcards">CVC (Verification #)</label>
                <input type="text" size="4" autocomplete="off" class="card-cvc"/>
            </div>
            <div class="form-row" style="margin-left:25px;">
                <label class="creditcards" >Expiration (MM/YYYY)</label>
                <input type="text" style="width:50px" size="2" class="card-expiry-month"/>
                <span style="font-size: 22px"> / </span>
                <input type="text" style="width:100px" size="4" class="card-expiry-year"/>
           <div class="">  <h1 class="creditcards1"> Your information is passed through Stripe\'s secure API. We never see it. </h1>   
           
    
    <a href="#" id="learnit" rel="popover" data-content="All payment information is sent directly through Stripe\'s secure API and never touches our servers. Your information is never collected and is securely processed with Stripe. Visit Stripe\'s website to learn more." data-original-title="Secure Payments With Stripe">Learn More</a> 
    </div>     
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>  
    <script src="bootstrap.js" type="text/javascript"></script>

  
    <script>  
    $(function ()  
    { $("#learnit").popover();  
    });  
    </script>
    
</div>
</div>
</div>

   <button type="submit" class="button submit btn btn-success" style="font-size:16px;margin-left:45px;margin-top:22px;padding-top:15px;padding-bottom:15px;padding-right:55px;padding-left:55px;">Submit Payment</button>
  </div> 
          </form>


<div class="grid_8 push_3 image" style="width:300px;"><img src="',$imagebig2,'" style="width:300px;height:300px;"/> 
</div>
<div class="grid_6 push_4 info" style="margin-top:25px;"> 
<h1 class="field"> Price: USD $',$price,'</h1> 
<h2 class="field"> Photographer: ',$firstname,' ',$lastname,'</h2>
<h3 class="field"> Photo: "',$imagebig2,'" </h3>
<h3 class="field"> Stripekey: "',$stripekey,'" </h3>
<h3 class="field"> Stripepubkey: "',$stripepubkey,'" </h3>
<h3 class="field"> Stripepubkey: "',$customeremail,'" </h3>
<h3 class="field"> Image ID: "',$imageID,'"</h3>
</div>
</div>
</div>';



}

if($_GET['charge'] == 1) {

 
//Tyler's Keys
//Stripe::setApiKey("sk_07nHg71XdNctNwyNyBzHk9LdiBTpH");


//PhotoRankr's Keys
//Stripe::setApiKey("jpdzMPMCFihJ43mXpa5I89wrtHDDxtlE");


Stripe::setApiKey("sk_0ATtMj5hZSOV0fjfcktMNACWed09b");


// get the credit card details submitted by the form
$token = $_POST['stripeToken'];

$newprice = 1000;
$imageID = "cat";

$photorankrfee = $newprice*.3;

// create the charge on Stripe's servers - this will charge the user's card
 $charge = Stripe_Charge::create(array(
  "amount" => 1500, 
  "card" => $token,
  "currency" => "usd",
    "application_fee" => 100,
  )
);



echo'<div class="grid_12 push_12 download1" style="margin-top:100px;margin-left:480px;>

<div class="grid_8 push_3 image" style="width:300px;"><img src="',$imagebig2,'" style="width:300px;height:300px;"/> 


<form name="download_form" method="post" action="downloadphoto.php">
<input type="hidden" name="image" value="', $imagebig2, '">
<input type="hidden" name="label" value="', $label, '">
<input type="hidden" name="imageID" value="', $imageID, '">
<input type="hidden" name="customeremail" value="', $customeremail, '">
<div class="grid_24" style="margin-top:30px;">
<button type="submit" name="submit" value="download" class="btn btn-warning" style="width:295px;height:40px;">DOWNLOAD PHOTO</button>
</div>
</form>

</div>';



}





}

?>
</div>




</div><!--end 24 grid-->

<?php footer(); ?>

</div><!--end container-->

<!--Javascripts-->
<script src="js/bootstrap.js" type="text/javascript"></script>

</body>
</html>
      
       
        
    