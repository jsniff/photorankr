<link rel="stylesheet" type="text/css" href="pr5/css/vpstyle.css"/>

<?php
  
require "db_connection.php";
require "functions.php";
require "timefunction.php";

session_start();

    // if login form has been submitted
    if (htmlentities($_GET['action']) == "login") {
        login();
    }
    else if(htmlentities($_GET['action']) == "logout") {
        logout();
    }

    $email = $_SESSION['email'];

// The Document/Literal parameter array

$userphotos="SELECT * FROM photos WHERE emailaddress = '$email'";
$userphotosquery=mysql_query($userphotos);
$numphotos=mysql_num_rows($userphotosquery);
    
$Storeinfo = array();

for($iii = 0; $iii < $numphotos; $iii++){

    $key = mysql_result($userphotosquery, $iii, "camera");
    $value = mysql_result($userphotosquery, $iii, "source");

    if(array_key_exists($key, $Storeinfo)) {
        if(is_array($Storeinfo[$key])) {
            $Storeinfo[$key][] = $value;
        }
        else {
            $Storeinfo[$key] = array($Storeinfo[$key], $value);          
        }
    }
    else {
        $Storeinfo[$key] = array($value);
    }

}
            
    echo'<div style="width:500px;background-color:#000;clear:both;position:relative;top:15px;">';

function printAll($a) {
    echo'<div style="background-color:#000;">';
    if (!is_array($a)) {
        $thumb1 = str_replace("userphotos/","userphotos/thumbs/",$a);
        $check = "user";
        $pos = strpos($thumb1, $check);
        
    if($pos ===false && is_int($a)===false){
        grabPic($a);
    }

    else if($pos!==false && $camera =="") {
        echo'<img style="float:left;padding:2px;" src="',$thumb1,'" height="80" width="80"/>';
    }
    
    return;
    
    }

    foreach($a as $k => $value) {
         if($k<10){
             printAll($k);
             printAll($value);
        }
    }
    echo'</div>';
    echo'<div style="clear:both;"></div>';
}

    echo'</div>';


//Call Function
printAll($Storeinfo);

function grabPic($camera) {

//Amazon code
$wsdl='http://webservices.amazon.com/AWSECommerceService/2009-10-01/US/AWSECommerceService.wsdl';

// Your authentication tokens
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

$request = array (
'Keywords' => $camera,
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
 $loop=0;
  foreach($Result->Items->Item as $item) {
    if($loop > 0){
        break;
    }
  $itemNum = $item->ASIN;
 echo'<a href="http://www.amazon.com/gp/product/B004V4IWKG/ref=as_li_qf_sp_asin_il?ie=UTF8&camp=1789&creative=9325&creativeASIN=',$itemNum,'&linkCode=as2&tag=photo0085-20"><img style="float:left;" border="" src="http://ws.assoc-amazon.com/widgets/q?_encoding=UTF8&ASIN=',$itemNum,'&Format=_SL110_&ID=AsinImage&MarketPlace=US&ServiceVersion=20070822&WS=1&tag=photo0085-20" ></a>';
 $loop += 1;
}

}

 ?>