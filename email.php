<?php

function send_email($to='', $from='', $subject='', $html_content='', $text_content='', $headers='') { 
	# Setup mime boundary
	$mime_boundary = 'Multipart_Boundary_x'.md5(time()).'x';

	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\r\n";
	$headers .= "Content-Transfer-Encoding: 7bit\r\n";

	$body	 = "This is a multi-part message in mime format.\n\n";

	# Add in plain text version
	$body	.= "--$mime_boundary\n";
	$body	.= "Content-Type: text/plain; charset=\"charset=us-ascii\"\n";
	$body	.= "Content-Transfer-Encoding: 7bit\n\n";
	$body	.= $text_content;
	$body	.= "\n\n";

	# Add in HTML version
	$body	.= "--$mime_boundary\n";
	$body	.= "Content-Type: text/html; charset=\"UTF-8\"\n";
	$body	.= "Content-Transfer-Encoding: 7bit\n\n";
	$body	.= 
	    
	    <html>
            <body style="text-align:center;background-color:rgb(21,95,47)">


<table border="0" style="border-radius:5px;float:center;background-color:white;width:500px;height:600px;">
  <thead>
    <tr style="height:30px;">
      <th colspan="3"></th>
    </tr>
  </thead>

  <tbody>
    <tr style="height:50px;background-color:black">
      <td style="border-radius:5px;text-align:center;color:white;width:200px;" colspan="3"><img src="http://photorankr.com/graphics/commentbubble.png" width="50" height="50"></td>
    </tr>
    <tr style="height:70px;">
     		<td colspan="3" style="text-align:center;font-size:16px;text-shadow: 5px 5px 15px;font-family:helvetica;font-weight:200;">

		Andy Hems is now following you on PhotoRankr!

		</td>
    </tr>
    <tr style="height:175px;border-radius:5px;">
		<td style="width:50px;"></td>
		<td style="border-radius:5px;background-color:rgb(134,198,85);width:500px;">

		<table border="0">
   		<tr>
     			<td style="padding-left:5px;padding-right:5px;width:500px;height:175px;"><img src="http://photorankr.com/graphics/Hems.png" width:"400: height:100px;></td>
   		</tr>
		</table>
		</td>

		<td style="width:50px;"></td>
    </tr>

    <tr style="height:70px;">
     		<td colspan="3" style="text-align:left;padding-left:20px;font-size:16px;text-shadow: 5px 5px 15px;font-family:helvetica;font-weight:200;">

		You are following two photographers that follow Andy Hems.

		</td>
    </tr>

    <tr style="height:70px;">
     		<td colspan="3" style="text-align:left;padding-left:20px;font-size:16px;text-shadow: 5px 5px 15px;font-family:helvetica;font-weight:200;">

		View Andy Hems' profile on PhotoRankr.  

		</td>
    </tr>


<center><table border="0" style="float:center;background-color:rgb(21,95,47)n;width:750px;height:70px;">
    <tr style="height:70px;">
     		<td colspan="3" border="0" style="background-color:rgb(21,95,47);text-shadow: 10px 10px 10
px;color:white;text-align:left;font-size:10px;font-family:helvetica;font-weight:200;line-height:1.48;padding:20px;">

		 If you believe Andy Hems is engaging in abusive behavior on PhotoRankr, you may contact <a style="color:black;" href="mailto:legal@photorankr.com">legal@photorankr.com</a>.</br></br>
If you do not wish to receive follow notification emails from PhotoRankr, you can unsubscribe by visiting your account settings and managing e-mail notifications.  Please do not reply to this message.  This message is a service e-mail related to your use of PhotoRankr.  For general inquiries or to request support with your PhotoRankr account, please e-mail <a style="color:black;" href="mailto:support@photorankr.com">support@photorankr.com</a>.

		</td>
    </tr>
</table>


</tbody>
</table></center>

</body>
</html>;

	$body	.= "\n\n";

	# Attachments would go here
	# But this whole email thing should be turned into a class to more logically handle attachments, 
	# this function is fine for just dealing with html and text content.

	# End email
	$body	.= "--$mime_boundary--\n"; # <-- Notice trailing --, required to close email body for mime's

	# Finish off headers
	$headers .= "From: $from\r\n";
	$headers .= "X-Sender-IP: $_SERVER[SERVER_ADDR]\r\n";
	$headers .= 'Date: '.date('n/d/Y g:i A')."\r\n";

	# Mail it out
	return mail($to, $subject, $body, $headers);
}

?>