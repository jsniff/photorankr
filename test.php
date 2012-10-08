<?php

//connect to the database
require "db_connection.php";
require "functionsnav.php";
require "timefunction.php";

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
    
    $findreputationme = mysql_query("SELECT user_id,reputation,profilepic,firstname,lastname FROM userinfo WHERE emailaddress = '$email'");
    $reputationme = mysql_result($findreputationme,0,'reputation');
    $sessionpic = mysql_result($findreputationme,0,'profilepic');
    $sessionuserid =  mysql_result($findreputationme,0,'user_id');
    $sessionfirst =  mysql_result($findreputationme,0,'firstname');
    $sessionlast =  mysql_result($findreputationme,0,'lastname');
    $sessionid =  mysql_result($findreputationme,0,'user_id');
    $sessionname = mysql_result($findreputationme,0,'firstname') ." ". mysql_result($findreputationme,0,'lastname');
    $currenttime = time();

echo $sessionfirst;
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>"<?php echo $caption; ?>" | PhotoRankr</title>

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
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
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

<script type="text/javascript" >

$(function() {
$(".submit").click(function() 
{
var name = '<?php echo $sessionfirst ." ". $sessionlast; ?>';
var email = '<?php echo $email; ?>';
var comment = $("#comment").val();
var dataString = 'name='+ name + '&email=' + email + '&comment=' + comment;
if(email=='' || comment=='')
{
alert('Please Give Valid Details');
}
else
{
$("#flash").show();
$("#flash").fadeIn(400).html('<img src="ajax-loader.gif" />Loading Comment...');
$.ajax({
type: "POST",
url: "commentajax.php",
data: dataString,
cache: false,
success: function(html){
$("ol#update").append(html);
$("ol#update li:last").fadeIn("slow");
$("#flash").hide();
}
});
}return false;
}); });

</script>


<!--GOOGLE ANALYTICS CODE-->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-28031297-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'https://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>

<body style="overflow-x:hidden;min-width:1220px;background-color:rgb(245,245,245);">

<?php navbarnew(); ?>

<div class="grid_24">
    <ol id="update" class="timeline">
    </ol>
</div>
<div id="flash"></div>
<div >
<form action="#" method="post" style="margin-top:100px;">
<textarea id="comment"></textarea><br />
<input type="submit" class="submit" value=" Submit Comment " />
</form>
</div>

</body>
</html>  
