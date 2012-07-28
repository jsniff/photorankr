

</html>
<head>             
<title>My Feed Dialog Page</title>
 </head>        


  <head> 
    <title> 
      New JavaScript SDK
    </title> 
  </head> 
<body> 
 	
<div id="fb-root"></div>
<div id="user-info"></div>
      <div class="fb-login-button" style="position:absolute;top:800px;">Login with Facebook</div>




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
        
       
        
} 
    
    
    
    
    
    
    else {
      //user is not connected to your app or logged out
      button.innerHTML = 'Login';
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



//$attachment = array(
                 //   'message' => 'Hello friends , I got this beautiful facebook profile cover now :)',
                   // 'name' => "Picslanda - The perfect Facebook cover system. ",
                  //  'caption' => $input['fbtitle'],
                  //  'link' => $input['picurl'],
                  //  'description' => 'Picslanda is one of the best image sharing system. You can download the latest trendy face book profile covers from this website.',
                   // 'picture' => $urlParser->fbpostpic($input['ik']),
              //      'actions' => array(array('name' => 'Get Search','link' => 'http://www.photorankr.com')));
//$result = $facebook->api('/me/feed/','post',$attachment);








//$attachment = array(
  //                  'message' => 'Hello friends , I got this beautiful facebook profile cover now :)',
    //                'name' => "Picslanda - The perfect Facebook cover system. ",
      //              'caption' => $input['fbtitle'],
        //            'link' => $input['picurl'],
          //          'description' => 'Picslanda is one of the best image sharing system. You can download the latest trendy face book profile covers from this website.',
                 //   'picture' => $urlParser->fbpostpic($input['ik']),
            //        'actions' => array(array(
                //                             'name' => 'Get Search',
              //                               'link' => 'http://www.google.com'
                  //                           ))
                    //);



</script>



//<script>

                  
                //  </script>
          







<?php
 define('YOUR_APP_ID', '433110216717524');

    //uses the PHP SDK.  Download from https://github.com/facebook/php-sdk
    require 'facebook.php';

    $facebook = new Facebook(array(
      'appId'  => '433110216717524',
      'secret' => '1e48c1da8711b920a081ba37e56e0f1f',
    ));

    $userId = $facebook->getUser();
  
    
   // if($userId) {
    //$attachment = array('message'=> 'I am a Nochbag')
    //$result = $facebook->api('/me/feed/','post',$attachment);
   // $ret_obj = $facebook->api('/me/feed','POST',array('link' => 'www.photorankr.com','message' => 'Posting with the PHP SDK!'));
    //echo '<pre>Post ID: ' . $ret_obj['id'] . '</pre>';
      //  echo "working bitch";
   // }
    
    
if($userId!=0) {
  //  $attachment = array('message'=> 'I am a Nochbag');
  //  $result = $facebook->api('/me/feed/','post',$attachment);

  
   // $api_call = array('method' => 'users.hasAppPermission','userId' => $userId,'ext_perm' => 'publish_stream');
   // $can_post = $facebook->api($api_call);
    //echo $can_post;
    //$can_post = $facebook->api($api_call);
    //echo $can_post;
    

    
    
    //$attachment = array('link'=>'www.photorankr.com','message'=>'Posting with the PHP SDK!');
    
  //  $object = $facebook->api('/me/feed','post', $attachment);
   // echo '<pre>Post ID: ' . $ret_obj['id'] . '</pre>';
    
   // $ret_obj = $facebook->api('/me/feed', 'POST', 'link'=>'www.example.com');
   // echo "working bitch";
    //$cat = "hello guys";
    //echo $cat;
      //'message' => 'Posting with the PHP SDK!'
    
}
    
    
  
    
    
    
    

    
    
    
    
    
    
    
    
    

    $userInfo = $facebook->api('/me');

 ///   echo "<pre>";
   //print_r($userInfo);
  //  echo "</pre>";
  
    
    


    

//$id = $userInfo['id'];
$first_name = $userInfo['first_name'];
$email = $userInfo['email'];
//$password = $userInfo['password'];



// Connecting to database
require "db_info.php";
//mysql_connect('DATABASE_HOST', 'DATABASE_USERNAME', 'DATABASE_PASSWORD');
//mysql_select_db('DATABASE_NAME');


//grab user_id query
$userid = "SELECT * FROM userinfo WHERE email = '$email' LIMIT 0,1";
$useridquery = mysql_query($userid);
$user = mysql_result($useridquery, 0, "user_id");
  //if (response.authResponse) {
//echo'<a href="http://photorankr.com/myprofile.php?u=',$user,'">My Profile</a>';
//}
$con=mysql_connect(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD) 
 
 or die("<p>Error connecting to the database: " . mysql_error() . "</p>");

 mysql_select_db(DATABASE_NAME) or die(mysql_error()); 
 
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

 mysql_select_db(DATABASE_NAME,con);


// Inserting into users table
$test = "INSERT INTO FacebookStuff (FacebookName,FacebookEmail) VALUES ('$first_name','$email')";
$resultquery = mysql_query($test);


//if($test){
// User successfully stored
//echo 'finally is working';

//}
//else
//{
//echo 'wft  is not';
//}
    
    
    //Check Login Status
  //  $params = array(
    //'ok_session' => 'http://photorankr.com',
      //              'no_user' => 'http://photorankr.com/no_user',
        //        'no_session' => 'http://www.photorankr.com/no_session',
          //          );
    
    //$next_url = $facebook->getLoginStatusUrl($params);
    
   // echo $next_url;
    
    //header('Location: '.$next_url);
    
    
    //Redirect them and send the data to myprofile;
    
    
   
    //In myprofile get the value of the data
  
    
   // if($uid ==0) {
     //       //}
    
    
    

 
    echo  'Working';




?>


<html xmlns="http://www.w3.org/1999/xhtml"
xmlns:fb="https://www.facebook.com/2008/fbml">
<head>
<title>My Feed Dialog Page</title>
</head>
<body>
<div id='fb-root'></div>
//<script src='http://connect.facebook.net/en_US/all.js'></script>
//<p><a onclick='postToFeed(); return false;'>Post to Feed</a></p>
//<p id='msg'></p>

//<script> 
FB.init({appId: "433110216717524", status: true, cookie: true});


onset = 1;

if(onset ==1) {

    FB.ui(
          {
          method: 'feed',
          name: 'Facebook Dialogs',
          link: 'http://developers.facebook.com/docs/reference/dialogs/',
          //picture: 'http://fbrell.com/f8.jpg',
          //caption: 'Reference Documentation',
          //description: 'Dialogs provide a simple, consistent interface for applications to interface with users.'
          },
          function(response) {
          if (response && response.post_id) {
          alert('Post was published.');
          } else {
          alert('Post was not published.');
          }
          }
          );  


//onset = 1;




//function postToFeed() {
  
//if(onset ==1){

    // calling the API ...
    //var obj = {
  //  method: 'feed',
  //  link: 'www.photorankr.com',
        //   picture: 'http://fbrell.com/f8.jpg',
  //  name: 'Facebook Dialogs',
   // caption: 'Reference Documentation',
    //description: 'Using Dialogs to interact with users.'
    };
    
    //function callback(response) {
     //   document.getElementById('msg').innerHTML = "Post ID: " + response['post_id'];
   // }
    
    
    
    
 //   FB.ui(obj, callback);
//}


//</script>



<form>
<input type="checkbox" name="vehicle" value="Bike" /> Post to Facebook<br />
<input type="checkbox" name="vehicle" value="Car" /> I have a car 
</form>



<div class="modal-header">
<a style="float:right" class="btn btn-primary" data-dismiss="modal" >Close</a>
<img style="margin-top:-4px;" src="graphics/logoteal.png" height="28" width="90" />&nbsp;&nbsp;<span style="font-size:16px;">Please log in to follow </span>
</div>
<div modal-body" style="width:600px;">

<div id="content" style="font-size:16px;width:500px;">


</div>';











</body>
</html>
