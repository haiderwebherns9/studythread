<?php
$path = $_SERVER['DOCUMENT_ROOT'];

include_once $path . '/wp-config.php';
include_once $path . '/wp-load.php';
include_once $path . '/wp-includes/wp-db.php';
include_once $path . '/wp-includes/pluggable.php';

$username = get_option('wpjobster_theme_apiuser');
$password = get_option('wpjobster_theme_signature');
$signature = get_option('wpjobster_theme_apipass');

define('USERNAME', $username);
define('PASSWORD', $password);
define('SIGNATURE', $signature);
/*
 *  # MassPay API
The MassPay API operation makes a payment to one or more PayPal account
holders.
This sample code uses Merchant PHP SDK to make API call
*/

function wpjobster_mass_pay($mails,$amounts,$currency){

	$massPayRequest = new MassPayRequestType();
	$massPayRequest->MassPayItem = array();
	for($i=0; $i<count($mails); $i++) {
		$masspayItem = new MassPayRequestItemType();
		/*
		 *  `Amount` for the payment which contains

		* `Currency Code`
		* `Amount`
		*/
		$masspayItem->Amount = new BasicAmountType($currency, $amounts[$i]);	
		$masspayItem->ReceiverEmail = $mails[$i];	
		$massPayRequest->MassPayItem[] = $masspayItem;
	}

	/*
	 *  ## MassPayReq
	Details of each payment.
	`Note:
	A single MassPayRequest can include up to 250 MassPayItems.`
	*/
	$massPayReq = new MassPayReq();
	$massPayReq->MassPayRequest = $massPayRequest;

	/*
	 * 	 ## Creating service wrapper object
	Creating service wrapper object to make API call and loading
	Configuration::getAcctAndConfig() returns array that contains credential and config parameters
	*/
	$paypalService = new PayPalAPIInterfaceServiceService(Configuration::getAcctAndConfig());

	// required in third party permissioning
	if(($_POST['accessToken']!= null) && ($_POST['tokenSecret'] != null)) {
			$cred = new PPSignatureCredential(USERNAME, PASSWORD, SIGNATURE);
		    $cred->setThirdPartyAuthorization(new PPTokenAuthorization($_POST['accessToken'], $_POST['tokenSecret']));
	}

	try {
		/* wrap API method calls on the service object with a try catch */
		if(($_POST['accessToken']!= null) && ($_POST['tokenSecret'] != null)) {
			$massPayResponse = $paypalService->MassPay($massPayReq, $cred);
		}
		else{
			$massPayResponse = $paypalService->MassPay($massPayReq);
		}
	} catch (Exception $ex) {
		include_once("../Error.php");		
	}	
	if(isset($massPayResponse)) {	
		if($massPayResponse->Ack == 'Success'){
			return "Success";
		}elseif($massPayResponse->Ack == 'Failure'){		
			
			$response = $massPayResponse->Errors[0]->ErrorCode.' '.$massPayResponse->Errors[0]->LongMessage;
			return $response;
		}		
	}
}