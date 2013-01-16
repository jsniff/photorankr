<?php

// PHP 5 SOAP. This program only works under PHP5
// Tested under PHP 5.2.11

// URL of the Amazon WSDL file which includes the version namespace
$wsdl='http://webservices.amazon.com/AWSECommerceService/2009-10-01/US/AWSECommerceService.wsdl';

// Your authentication tokens
//$ACCESS_KEY_ID = "<Access Key Removed>";
$ACCESS_KEY_ID = "AKIAJA3M2M25RR4CDTFQ";
$SECRET_ACCESS_KEY = "D0R7+XQ1XxSSiZivDMHPMoejxMjr463VfH/H15iA";

// The method we are using
$method='ItemSearch';

// A useable timestamp
$timestamp = gmdate("Y-m-d\TH:i:s\Z");

// Concatenate the method and timestamp for signature purposes
$method_and_time = $method.$timestamp;

// Generate a signature
$signature = base64_encode(hash_hmac("sha256",$method_and_time,$SECRET_ACCESS_KEY,TRUE));

// The SOAP client
$client = new SoapClient($wsdl);

// Set headers
$headers = array();
$headers[] = new SoapHeader( 'http://security.amazonaws.com/doc/2007-01-01/', 'AWSAccessKeyId', $ACCESS_KEY_ID );
$headers[] = new SoapHeader( 'http://security.amazonaws.com/doc/2007-01-01/', 'Timestamp', $timestamp );
$headers[] = new SoapHeader( 'http://security.amazonaws.com/doc/2007-01-01/', 'Signature', $signature );
$client->__setSoapHeaders($headers);

// The Document/Literal parameter array
$request = array (
'Keywords' => 'iphone 4s',
'SearchIndex' => 'Photo',
'Operation' => $method,
'ResponseGroup' => array('Medium','Images','Reviews')
);

$params=array(
'AWSAccessKeyId' => $ACCESS_KEY_ID,
'AssociateTag' => 'ws',
'Operation' => $method,
'Request' => $request
);

$Result = $client->$method($params);
$loop=1;
foreach($Result->Items->Item as $item) {
$itemNum = $item->ASIN;
echo'<a href="http://www.amazon.com/gp/product/B004V4IWKG/ref=as_li_qf_sp_asin_il?ie=UTF8&camp=1789&creative=9325&creativeASIN=',$itemNum,'&linkCode=as2&tag=photo0085-20"><img border="" src="http://ws.assoc-amazon.com/widgets/q?_encoding=UTF8&ASIN=',$itemNum,'&Format=_SL110_&ID=AsinImage&MarketPlace=US&ServiceVersion=20070822&WS=1&tag=photo0085-20" ></a>';
break;
} 

?>

