
<html xmlns="http://www.w3.org/1999/xhtml"
xmlns:fb="https://www.facebook.com/2008/fbml">


<link rel="stylesheet"  href="bootstrap.css" type="text/css" /> 

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

<script src="bootstrap.js" type="text/javascript"></script>

<head>
<script type="text/javascript">


function load()
{

    $('#myModal').modal('show');
 
    
}
</script>

<script type="text/javascript">

$('#close').modal('hide');

</script>



</head>

<body onload="load()">
</body>



<body>


<div class="modal hide" id="myModal" style="height:500px;width:500px;overflow-x:hidden;">
<div class="modal-header">
<a style="float:right" class="btn btn-primary" data-dismiss="modal">Done Bitch</a>

<span style="font-size:18px;">Post your work to Facebook and Twitter</span>
</div>
<div class="modal-body">




<!--redirect to own page
<form action="automatictwitterdifferent.php?action=socialmedia" method="post" style="width:400px;margin-left:25px;">
<div>
<input type="checkbox" name="Facebook" value='value1' />Post uploaded photo to Facebook<br /><br />
<input type="checkbox" name="Twitter"  value = 'value2'/>Post uploaded photo to Twitter<br />
</div>
<div>
<a href="#close"><button class="btn btn-success" type="submit" style="text-decoration:none;color:#fff;" ><p class="buttontext">Submit</p></button></a>
</div>
</form>-->


<div id="fb-root"></div>
<div id="user-info"></div>
<p><button class="btn btn-success" id="fb-auth">Login with Facebook</button></p>

<script>
window.fbAsyncInit = function() {
    FB.init({ appId: '433110216717524', 
            status: true, 
            cookie: true,
            xfbml: true,
            oauth: true});
    
    function updateButton(response) {
        var button = document.getElementById('fb-auth');
		
        if (response.authResponse) {
            //user is already logged in and connected
            var userInfo = document.getElementById('user-info');
            FB.api('/me', function(response) {
                   userInfo.innerHTML = '<img src="https://graph.facebook.com/' 
                   + response.id + '/picture">' + response.email;
                   button.innerHTML = 'Logout';
                   
                   
                   
                   
                   });
            button.onclick = function() {
                FB.logout(function(response) {
                          var userInfo = document.getElementById('user-info');
                          userInfo.innerHTML="";
                          });
            };
            
            
            
            
            
        } else {
            //user is not connected to your app or logged out
            button.innerHTML = 'Login to FB';
            button.onclick = function() {
                FB.login(function(response) {
                         if (response.authResponse) {
                         FB.api('/me', function(response) {
                                var userInfo = document.getElementById('user-info');
                                userInfo.innerHTML = 
                                '<img src="https://graph.facebook.com/' 
                                + response.id + '/picture" style="margin-right:5px"/>' 
                                + response.name;
                                
                                
                                
                                });	   
                         } else {
                         //user cancelled login or did not grant authorization
                         }
                         }, {scope:'email'});  	
            }
        }
    }
    
    // run once with current status and whenever the status changes
    FB.getLoginStatus(updateButton);
    FB.Event.subscribe('auth.statusChange', updateButton);	
};

(function() {
 var e = document.createElement('script'); e.async = true;
 e.src = document.location.protocol 
 + '//connect.facebook.net/en_US/all.js';
 document.getElementById('fb-root').appendChild(e);
 }());

</script>



<div id='fb-root'></div>
<script src='http://connect.facebook.net/en_US/all.js'></script>
<p><a data-dismiss="modal"  onclick='postToFeed(); return false;'>Post to Feed</a></p>
<p data-dismiss="modal" id='msg'></p>

<script> 
FB.init({appId: "433110216717524", status: true, cookie: true});

function postToFeed() {
    
    // calling the API ...
    var obj = {
    method: 'feed',
    link: 'www.photorankr.com',
        //   picture: 'http://fbrell.com/f8.jpg',
  //  name: 'Facebook Dialogs',
   // caption: 'Reference Documentation',
    //description: 'Using Dialogs to interact with users.'
    };
    
    function callback(response) {
        document.getElementById('msg').innerHTML = "Post ID: " + response['post_id'];
    }
    
    FB.ui(obj, callback);
}


</script>
</div>



</p>
</div>

</div>

<a class="btn btn-primary" data-toggle="modal" href="#myModal" >Launch Modal</a>





</body>





<script type="text/javascript">
function facebookloginplease() 
{
    
//document.write("nochbag");    
    

}
</script>











<?php
    
    
    
    if($_GET['action'] == "socialmedia") {
        
        //$facebookcheck = $_REQUEST['Facebook'];
        //$twittercheck = $_REQUEST['Twitter'];
        echo "Nochbag";
        
        //  $value = $_POST['Facebook'];
        $valueone = $_POST['Facebook'];
        $valuetwo = $_POST['Twitter'];
        echo $valueone;
        echo $valuetwo;
        
        
        //check if Facebook has been checked
        
        
        
        
        
        
        if($valueone == "value1") {
            
            echo "<script typ=javascript> facebookloginplease();</script>";

            
            
        }
        
        

    
   
    
    
    }  
    
  
    ?>











</html>
