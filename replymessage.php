<?php
/*In here we are going to try to update the database with the message which was sent from ViewProfile.php with the contact form*/

//CONNECT TO DB
require "db_connection.php";

session_start();

//find out who the message is going to be sent to and who the message is being sent from
$sender = $_SESSION['email'];
$receiver = mysql_real_escape_string($_REQUEST['emailaddressofviewed']);

//newsfeed entry
$idquery = mysql_query("SELECT user_id,firstname,lastname FROM userinfo WHERE emailaddress = '$receiver'");
$userid = mysql_result($idquery,0,'user_id');
$recfirst = mysql_result($idquery,0,'firstname');
$reclast = mysql_result($idquery,0,'lastname');

//run a query to retreive a message which would belong to the same thread
$threadquery = "SELECT * FROM messages WHERE (sender='$sender' AND receiver='$receiver') OR (sender='$receiver' AND receiver='$sender') LIMIT 0, 1";
$threadresult = mysql_query($threadquery) or die(mysql_error());

$thread = mysql_result($threadresult, 0, "thread");

//set $unread equal to one since this message will have been unread by the receiver
$unread = 1;

//find out what the message said
$contents = htmlentities(mysql_real_escape_string($_REQUEST['message']));

//now insert this message into the messages table
$messagequery = "INSERT INTO messages (thread, sender, receiver, contents, unread) VALUES ('$thread', '$sender', '$receiver', '$contents', '$unread')";
$messageresult = mysql_query($messagequery) or die(mysql_error());

//now that the message has been sent, we need to make a notification for the receiver
mysql_query("UPDATE userinfo SET notifications=(notifications + 1) WHERE emailaddress = '$receiver'") or die(mysql_error());

//newsfeed entry
$select_query="SELECT * FROM userinfo WHERE emailaddress ='$sender'";
$result=mysql_query($select_query);
$row=mysql_fetch_array($result);
$firstname=$row['firstname'];
$lastname=$row['lastname'];
$type = "reply";
$newsfeedsignupquery=mysql_query("INSERT INTO newsfeed (firstname, lastname, owner,type,thread) VALUES ('$firstname', '$lastname', '$receiver','$type','$thread')");

//Mail email to reciever
    $to = '"' . $recfirst . ' ' . $reclast . '"' . '<'.$receiver.'>';
    $subject = $firstname . " " . $lastname . " replied to your message on PhotoRankr";
    $favemessage = $contents. "
        
To view the message, login and click here: http://photorankr.com/myprofile.php?view=viewthread&thread=".$thread;
    $headers = 'From:PhotoRankr <photorankr@photorankr.com>';
          
    mail($to, $subject, $favemessage, $headers); 


//now update the newsfeed table with the message so that it can be pulled for the notifications

//redirect them to whence they came
header("Location: myprofile.php?view=viewthread&thread=$thread&action=messagesent#bottom");

?>