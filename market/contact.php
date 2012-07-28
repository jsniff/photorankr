<?php
require "functionscampaigns.php"; 
require "db_connection.php";
    // if login form has been submitted
    if (htmlentities($_GET['action']) == "login") { 
        login();
    }
    else if(htmlentities($_GET['action']) == "logout") { 
        logout();
    }
?>
<!DOCTYPE HTML>
<html>
<head>
 <meta name="description" content="Contact the awesome PhotoRankr team about partnerships, ideas for improving the site, and promotions">
 <meta name="keywords" content="contact us, contact, improve, photorankr, campaigns">
 <meta name="author" content="The PhotoRankr Team">
<title>Create a Campaign on PhotoRankr to get photos that match your needs</title>
  <link rel="stylesheet" href="css/bootstrapnew.css" type="text/css" />
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
      filter:alpha(opacity=100); /* For IE8 and earlier */
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
      filter:alpha(opacity=100); /* For IE8 and earlier */
      z-index:1;
      float:left;
      font-family: 'helvetica neue'; helvetica;
    }

    div.bigtransbox
    {
      width:500px;
      height:600px;
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

<body style="background-color: #EEE"">

<!--NAVIGATION BAR-->
<?php navbar(); ?>

<div class="container_24" style="padding-top:80px;"> <!--container begin--->
 <div class="grid_24">
  <div class="faq">
   <h1 class="header">Contact Us</h1>
    </div>
   <div class="grid_20 push_2 " style="margin-left:10px;">
    <div class="grid_22 pull_1 contactboard" style="margin-bottom:10px;">
     <div class="grid_10 contacthelp" style="margin-left:20px;">
      <h1 class="contacth">Problems, or Questions About PhotoRankr? </h1>
      <div class="grid_10" style="background-color:white;height:2px;margin-left:-10px;padding-right:30px;">
      </div>
       <p class="contactp"> E-mail us at <a href="mailto:support@photorankr.com"> support@photorankr.com</a>. We will answer your email as soon as possible.</p>
       <p class="contactp"> Take a look at the <a href="help.php"> FAQ page  </a>  might already have the answer to your question. </p>
     </div>
    <div class="grid_10 contacthelp">
      <h1 class="contacth"> Feedback and Suggestions</h1>
      <div class="grid_10" style="background-color:white;height:2px;margin-left:-20px;padding-right:30px;">
      </div>
      <p class="contactp">If you have any suggestions for improving PhotoRankr please email us at <a href="mailto:photorankr@photorankr.com"> phototrankr@photorankr.com </a>. </p>
      <p class="contactp"> This site is for you and we'd love to hear how you would make it better! </p>
    </div>
    

    <div class="grid_6 push_1 contacthelp" style="float:left;padding-right:15px;margin-left:-20px;">
    <h1 class="contacth2"> Contact information </h1>
      <div class="grid_7" style="background-color:white;height:2px;margin-left:-20px;padding-right:35px;">
      </div>
     <div class="grid_4 push_1  contacthelp1" style="float:left;padding:2px;;margin-left:0px;margin-top:1px;"> 
     <p class="contactp" style="text-align:center;line-height:1.4;">
     					 Address: <br />  
                         PhotoRankr, Inc.<br />
                         160 Greentree Drive, Suite 101<br />
                         Dover, Delaware 19904 </p>
   </div>
      <div class="grid_7 grid_15 answerbox stripe" style="background-color:white;height:2px;margin-left:-20px;padding-right:35px;">
      </div>
        <div class="grid_4 push_1 contacthelp1" style="float:left;padding:1px;;margin-left:0px;margin-top:1px;">
     <p class="contactp" style="text-align:center;line-height:1.4;"> Social: <br /> Twitter <a href="https://twitter.com/PhotoRankr" class="twitter-follow-button" data-show-count="false" data-size="large">Follow @PhotoRankr</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
       <br /> Our <a href="http://www.facebook.com/pages/PhotoRankr/140599622721692">Facebook page</a><br /> Our <a href="https://plus.google.com/102253183291914861528/posts">Google + page</a>.
        </p>
        </div>
       </div>
        <div class="grid_4 push_2" style="width:250px;height:270px;float:left;margin-left:-15px;margin-top:20px;overflow:hidden;border: 5px solid rgba(234,234,239,.9);">
      <a href= "http://photorankr.com/fullsize.php?image=userphotos/dreamscape.jpg&v=r"><img src="dreamscape.jpg" style="margin-left:-15px;"/></a>
     </div>
    <div class="grid_4 push_2" style="width:250px;height:270px;float:left;margin-left:25px;margin-top:20px;overflow:hidden;border: 5px solid rgba(234,234,239,.9);">
     <a href= "http://photorankr.com/fullsize.php?image=userphotos/fortfischersurrealist2.jpg&v=r"> <img src="Fort Fischer Surrealist 2.jpg"  style="margin-left:-110px;" />
    </a> </div> 
     </div>
    </div>
    </div>
    
   </div>
     
     
 </div><!--container end--> 
 <!--Footer begin-->                
             
           

                    <div class="navabar" style="width:100%;background-color:rgb(245,245,245);height:60px;margin-top:10px;box-shadow: inset 0 2px 3px 0px #cccccc">
<div class="grid_24" style="background-color:none;width:100%;height:30px;margin-top:15px;text-align:center;padding-top:10px;padding-bottom:0px; background-color:none;text-decoration:none;">
Copyright&nbsp;&copy;&nbsp;2012&nbsp;PhotoRankr, Inc.&nbsp;&nbsp;
<a href="http://photorankr.com/about.php">About</a>&nbsp;&nbsp;                                       
<a href="http://photorankr.com/terms.php">Terms</a>&nbsp;&nbsp;
<a href="http://photorankr.com/privacy.php">Privacy</a>&nbsp;&nbsp;
<a href="http://photorankr.com/help.php">Help<a>&nbsp;&nbsp;
<a href="http://photorankr.com/contact.php">Contact&nbsp;Us<a>
                                      </p>
                   
                        </div>
                    </div>
                </div> <!--container end-->


                
                
                <!--Footer end--> 
        
  
  </div>
  
</div> <!--Container End-->  
</body>
</html> 