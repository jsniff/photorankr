<?php
 
/** GetExpressCheckoutDetails NVP example; last modified 08MAY23.
 *
 *  Get information about an Express Checkout transaction. 
*/
 
$environment = 'live';	// or 'beta-sandbox' or 'live'
 
/**
 * Send HTTP POST Request
 *
 * @param	string	The API method name
 * @param	string	The POST Message fields in &name=value pair format
 * @return	array	Parsed HTTP Response body
 */
function PPHttpPost($methodName_, $nvpStr_) {
	global $environment;
 
	// Set up your API credentials, PayPal end point, and API version.
	$API_UserName = urlencode('photorankr_api2.photorankr.com');
	$API_Password = urlencode('GDXGAJQZK7DFFRFY');
	$API_Signature = urlencode('AIloodktrq1eS0t7zyszxtmBoLm6Ah08o2sBNi3Yd6Fc8C1lQYOTKa1y');
	$API_Endpoint = "https://api-3t.paypal.com/nvp";
	if("sandbox" === $environment || "beta-sandbox" === $environment) {
		$API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
	}
	$version = urlencode('51.0');
 
	// Set the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
 
	// Turn off the server and peer verification (TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
 
 	$methodName_="GetExpressCheckoutDetails";
	// Set the API operation, version, and API signature in the request.
	$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";
 
	// Set the request as a POST FIELD for curl.
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
 
	// Get response from the server.
	$httpResponse = curl_exec($ch);
 
	if(!$httpResponse) {
		exit('$methodName_ failed: '.curl_error($ch).'('.curl_errno($ch).')');
	}
 
	// Extract the response details.
	$httpResponseAr = explode("&", $httpResponse);
 
	$httpParsedResponseAr = array();
	foreach ($httpResponseAr as $i => $value) {
		$tmpAr = explode("=", $value);
		if(sizeof($tmpAr) > 1) {
			$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
		}
	}
 
	if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
		exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
	}
 
	return $httpParsedResponseAr;
}
 
/**
 * This example assumes that this is the return URL in the SetExpressCheckout API call.
 * The PayPal website redirects the user to this page with a token.
 */
 
// Obtain the token from PayPal.
if(!array_key_exists('token', $_REQUEST)) {
	exit('Token is not received.');
}
 
// Set request-specific fields.
$token = urlencode(htmlspecialchars($_REQUEST['token']));
 
// Add request-specific fields to the request string.
$nvpStr = "&TOKEN=$token";
 
// Execute the API operation; see the PPHttpPost function above.
$httpParsedResponseAr = PPHttpPost('GetExpressCheckoutDetails', $nvpStr);
 
if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
	// Extract the response details.
	$payerID = $httpParsedResponseAr['PAYERID'];
	$street1 = $httpParsedResponseAr["SHIPTOSTREET"];
	if(array_key_exists("SHIPTOSTREET2", $httpParsedResponseAr)) {
		$street2 = $httpParsedResponseAr["SHIPTOSTREET2"];
	}
	$city_name = $httpParsedResponseAr["SHIPTOCITY"];
	$state_province = $httpParsedResponseAr["SHIPTOSTATE"];
	$postal_code = $httpParsedResponseAr["SHIPTOZIP"];
	$country_code = $httpParsedResponseAr["SHIPTOCOUNTRYCODE"];
 
	//exit('Get Express Checkout Details Completed Successfully: '.print_r($httpParsedResponseAr, true));
		
echo'
        <span class="payment-errors" style="font-weight:bold;font-size:15px;"></span>
      <form action= "confirmpaymentpaypal.php" method="POST">
    <div class="form-row" style="margin-left:25px;">           
<input type="hidden" name="token" value="',$token,'">
<input type="hidden" name="identities" value="',$payerID,'">
   <button type="submit" class="button submit btn btn-success" style="font-size:16px;float:left;margin-top:5px;padding-top:10px;padding-bottom:10px;padding-right:40px;padding-left:40px;font-weight:200;">Submit Payment</button>
   </form>
        </div>';


} else  {
	exit('GetExpressCheckoutDetails failed: ' . print_r($httpParsedResponseAr, true));
}

?>

