<?php
 
/*THIS IS STRICTLY EXAMPLE SOURCE CODE. IT IS ONLY MEANT TO 
QUICKLY DEMONSTRATE THE CONCEPT AND THE USAGE OF THE ADAPTIVE 
PAYMENTS API. PLEASE NOTE THAT THIS IS *NOT* PRODUCTION-QUALITY 
CODE AND SHOULD NOT BE USED AS SUCH.
 
THIS EXAMPLE CODE IS PROVIDED TO YOU ONLY ON AN "AS IS" 
BASIS WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, EITHER 
EXPRESS OR IMPLIED, INCLUDING WITHOUT LIMITATION ANY WARRANTIES 
OR CONDITIONS OF TITLE, NON-INFRINGEMENT, MERCHANTABILITY OR 
FITNESS FOR A PARTICULAR PURPOSE. PAYPAL MAKES NO WARRANTY THAT 
THE SOFTWARE OR DOCUMENTATION WILL BE ERROR-FREE. IN NO EVENT 
SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY 
DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR 
CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT 
OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; 
OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF 
LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT 
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF 
THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY 
OF SUCH DAMAGE.
*/
 
	/* TODO
 	* Using PHP SOAP 5.3
	*Modifiy/add other fields to the class structure based on payment types/API requirements
 * the http headers and request body contains sample data, please replace the data with valid data.
	*/
 
 
//turn php errors on
ini_set("track_errors", true);
 
//set PayPal Endpoint to sandbox
$api_endpoint = trim("https://svcs.sandbox.paypal.com/AdaptivePayments/Preapproval/");
$wsdl = trim("https://svcs.sandbox.paypal.com/AdaptivePayments?wsdl");
 
/*
*******************************************************************
PayPal API Credentials
Replace <API_USERNAME> with your API Username
Replace <API_PASSWORD> with your API Password
Replace <API_SIGNATURE> with your Signature
*******************************************************************
*/
 
//PayPal API Credentials
//$API_UserName = "photorankr_api2.photorankr.com"; //TODO
//$API_Password = "GDXGAJQZK7DFFRFY"; //TODO
//$API_Signature = "AIloodktrq1eS0t7zyszxtmBoLm6Ah08o2sBNi3Yd6Fc8C1lQYOTKa1y"; //TODO
  // Set up your API credentials, PayPal end point, and API version.
  $API_UserName = urlencode('photorankr_api2.photorankr.com');
  $API_Password = urlencode('GDXGAJQZK7DFFRFY');
  $API_Signature = urlencode('AIloodktrq1eS0t7zyszxtmBoLm6Ah08o2sBNi3Yd6Fc8C1lQYOTKa1y');

//$API_UserName = urlencode('photorankr_api2.photorankr.com');
 // $API_Password = urlencode('GDXGAJQZK7DFFRFY');
 // $API_Signature = urlencode('AIloodktrq1eS0t7zyszxtmBoLm6Ah08o2sBNi3Yd6Fc8C1lQYOTKa1y');


//Default App ID for Sandbox	
$API_AppID = "APP-80W284485P519543T";
$API_MessageProtocol = "SOAP11";
 
//class declaration of complex data type RequestEnvelope
class RequestEnvelope {
    public $detailLevel;
    public $errorLanguage;
}
 
//class declaration of complex data type PreapprovalRequest
class PreapprovalRequest { 
public $requestEnvelope;
public $cancelUrl;
public $currencyCode;  
public $dateOfMonth;
public $dayofWeek;
public $endingDate;
public $ipnNotificationUrl;
public $maxAmountPerPayment;
public $maxNumberOfPayments;
public $maxNumberOfPaymentsPerPeriod;
public $maxTotalAmountOfAllPayments;
public $memo; 
public $paymentPeriod;
public $pinType;
public $returnUrl;  
public $startingDate;
}  
 
try {	
	//create instance of the complex types classes
	$xrequestEnvelope = new RequestEnvelope();
 
 
	//Creating instance of the PreapprovalRequest to be the payload for the Preapproval api call with required field
	$params = new PreapprovalRequest();
 
   	$params->requestEnvelope = $xrequestEnvelope;   	  	
 
   	$params->cancelUrl = 'http://www.ebay.com';
   	$params->currencyCode = 'USD';
   	$params->returnUrl = 'http://www.ebay.com';
   	$params->endingDate = '2011-07-03T07:00:00';
   	$params->maxTotalAmountOfAllPayments = '500.0';
   	$params->memo = 'preapproval';
	$params->startingDate = '2010-12-03T07:00:00';
 
//API credential http headers
	$http_headers = "X-PAYPAL-SECURITY-USERID: " . $API_UserName . "\r\n" .
                    "X-PAYPAL-SECURITY-SIGNATURE: " . $API_Signature . "\r\n" .
                 	"X-PAYPAL-SECURITY-PASSWORD: " . $API_Password . "\r\n" .
                   	"X-PAYPAL-APPLICATION-ID: " . $API_AppID . "\r\n" .
   	                "X-PAYPAL-MESSAGE-PROTOCOL: " .$API_MessageProtocol. "\r\n";
 
 
$opts = array( 'http' => array('method'=>'POST','header'=>$http_headers));
 
//creating the stream context option containing the http headers
$ctx = stream_context_create($opts);
 
$soapClient = new SoapClient($wsdl,
							array('location' => $api_endpoint, 
                                  'uri' => "urn:Preapproval",
                                  'soap_version' => SOAP_1_1, 
                                  'trace' => 1, //debugging option
                                  'stream_context' => $ctx)); //adding the stream context option containing the http headers
 
$response = $soapClient->Preapproval($params);
 
//The $response is a PreapprovalResponse type.
//Retriving few information from the response
 
$preapprovalKey = $response->preapprovalKey;
$ackCode  = $response->responseEnvelope->ack;
 
 
 $paypalURL = "https://www.sandbox.paypal.com/webscr?cmd=_ap-preapproval&preapprovalkey=" .$preapprovalKey;
    echo '<p><a href="' . $paypalURL . '" target="_blank">' . $paypalURL . '</a></p>';
 
 
} catch (SoapFault $e) { 
   echo "Error Id : ||" . $e->detail->FaultMessage->error->errorId. "<br/>";
   echo "Error Message : ||" . $e->detail->FaultMessage->error->message;	
}
 
?>