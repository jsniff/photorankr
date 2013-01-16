 <?php
//$ages['Peter'] = "32";
//$ages['Quagmire'] = "30";
//$ages['Joe'] = "34";
//$ages['Peter'] = "50";


 //echo $ages['Peter'];


 
require "db_connection.php";
require "functionsnav.php";
require "timefunction.php";


// $search_array = array('first' => 1, 'second' => 4);
// if (array_key_exists('third', $search_array)) {
//     echo "The 'first' element is in the array";
// }

// else {
// echo "Does not work";
// }

session_start();

    // if login form has been submitted
    if (htmlentities($_GET['action']) == "login") {
        login();
    }
    else if(htmlentities($_GET['action']) == "logout") {
        logout();
    }

    $email = $_SESSION['email'];
   // echo $email;


//amazon code

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



 $userphotos="SELECT * FROM photos WHERE emailaddress = '$email'";
    $userphotosquery=mysql_query($userphotos);
    $numphotos=mysql_num_rows($userphotosquery);
    
$Storeinfo = array();


for($iii = 0; $iii < $numphotos; $iii++){
    //echo $iii;

    $key = mysql_result($userphotosquery, $iii, "camera");
    $value = mysql_result($userphotosquery, $iii, "source");
//echo $key;
//echo $value;

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



//echo $Storeinfo[$key];
   // echo $key;
 

}



// foreach($Storeinfo[$key] as $key => $value){
//         echo $value;
//         echo $key;
//         echo $Storeinfo[$key];

//     }



// function printAll($a) {
//   if (!is_array($a)) {
//     echo $a, ' ';
//     return;
//   }

// foreach ($a as $k => $value) {
//    if($k<10){
//     printAll($k);
//     printAll($value);
//     }
// }
// }


              

function printAll($a) {
    if (!is_array($a)) {
       // echo $a, ' ';
        $thumb1 = str_replace("userphotos/","userphotos/medthumbs/",$a);
        $check = "user";
        //echo $check;
        //echo $thumb1;
//    echo gettype($thumb1), "\n";   
       $pos = strpos($thumb1, $check);
       //echo $pos;
     //  isnan( a)
   // echo gettype($a), "\n";  
if($pos ===false && is_int($a)===false){

    echo "<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />";

echo $a, ' ';
$camera = $a;
}
//$b=showcamera($a);
//echo $b;

//$Result = $client->$method($b);
 //$loop=1;
//  foreach($Result->Items->Item as $item) {
//   //  echo "workingstill";
// $itemNum = $item->ASIN;
// echo'<a href="http://www.amazon.com/gp/product/B004V4IWKG/ref=as_li_qf_sp_asin_il?ie=UTF8&camp=1789&creative=9325&creativeASIN=',$itemNum,'&linkCode=as2&tag=photo0085-20"><img border="" src="http://ws.assoc-amazon.com/widgets/q?_encoding=UTF8&ASIN=',$itemNum,'&Format=_SL110_&ID=AsinImage&MarketPlace=US&ServiceVersion=20070822&WS=1&tag=photo0085-20" ></a>';

// }

//echo $params;
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
}
printAll($Storeinfo);

//$cameratype = "kodak";
function showcamera($cameratype) {
    //echo "working";
    echo $cameratype;
    $request = array (
'Keywords' => $cameratype,
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

//echo $params;
//return $params;



$cat = "12";
return $params;
}


// $Result = $client->$method($params);
// $loop=1;
//  foreach($Result->Items->Item as $item) {
//     echo "workingstill";
// $itemNum = $item->ASIN;
// echo'<a href="http://www.amazon.com/gp/product/B004V4IWKG/ref=as_li_qf_sp_asin_il?ie=UTF8&camp=1789&creative=9325&creativeASIN=',$itemNum,'&linkCode=as2&tag=photo0085-20"><img border="" src="http://ws.assoc-amazon.com/widgets/q?_encoding=UTF8&ASIN=',$itemNum,'&Format=_SL110_&ID=AsinImage&MarketPlace=US&ServiceVersion=20070822&WS=1&tag=photo0085-20" ></a>';



// }










// $array = array('hello',
//                array('world',
//                      '!',
//                      array('whats'),
//                      'up'),
//                array('?'));








 ?>