<?php
define('__GIROCHECKOUT_SDK_DEBUG__',true);

/**
 * Sample code for GiroCheckout integration of a transaction through the GS Paypage
 *
 * @filesource
 * @package Samples
 * @version $Revision: 109 $ / $Date: 2015-06-01 13:37:30 +0200 (Mo, 01 Jun 2015) $
 */
require '../vendor/autoload.php';
use girosolution\GiroCheckout_SDK\GiroCheckout_SDK_Request;
use girosolution\GiroCheckout_SDK\helper\GiroCheckout_SDK_TransactionType_helper;

/**
 * configuration of the merchants identifier, project and password
 * this information can be found in the GiroCockpit's project settings
 */
$merchantID = 0;        // Your merchant ID (Verkaufer-ID)
$projectID = 0;         // Your project ID (Projekt-ID)
$projectPassword = "";  // Your project password

/* init giropay transaction and parameters */
try {
  
	$request = new GiroCheckout_SDK_Request(GiroCheckout_SDK_TransactionType_helper::TRANS_TYPE_PAYPAGE_TRANSACTION );
	$request->setSecret($projectPassword);
	$request->addParam('merchantId',$merchantID)
	        ->addParam('projectId',$projectID)
	        ->addParam('merchantTxId',"123456")
	        ->addParam('amount',500)
	        ->addParam('currency','EUR')
	        ->addParam('purpose','Best. 123 Kd. 202')
          ->addParam('orderid', 'Test/171212/000007')
	        ->addParam('pagetype', 2)  // 0=def, 1=pay, 2=donate
	        //->addParam('single', 1)
	        //->addParam('expirydate','2020-04-01')
          ->addParam('paymethods', '1,2,6,23,12,11')
          //->addParam('payprojects', '123,124')
          ->addParam('freeamount', '1')
          ->addParam('minamount', '1')
          ->addParam('maxamount', '10000')
          ->addParam('fixedvalues', '["10000","20000","50050"]')
          //->addParam('type', 'AUTH')  // Default SALE
	        ->addParam('description','API-Aufruf zum Test')
	        ->addParam('organization','Mustermann Tester GbR')
          ->addParam('projectlist', json_encode(array("Startprojekt", "Projekt 2", "Projekt X")))
          //->addParam('otherpayments', json_encode(array( array("id"=>14, "url" => "https://www.paypal.de", "position" => 10), array("id"=>11, "url" => "https://www.visa.com", "position" => 2) )))
	        ->addParam('locale','de')
	        ->addParam('test',1)
	        ->addParam('certdata',1)  // Optional to request certificate data
          //->addParam('paydirektShippingFirstName', 'Max')
          //->addParam('paydirektShippingLastName', 'Mustermann')
          //->addParam('paydirektShippingZipCode', '10000')
          //->addParam('paydirektShippingCity', 'Musterstadt')
          //->addParam('paydirektShippingCountry', 'DE')
	        //->addParam('pkn','create')
          ->addParam('successUrl','https://www.my-domain.de/girocheckout/success.php')
          ->addParam('backUrl',   'https://www.my-domain.de/girocheckout/back.php')
          ->addParam('failUrl',   'https://www.my-domain.de/girocheckout/fail.php')
          ->addParam('notifyUrl', 'https://www.my-domain.de/girocheckout/notify.php')
	        //the hash field is auto generated by the SDK
	        ->submit();
  
  //echo "<pre>";print_r($request->getResponseRaw());echo "</pre>";

  
	/* if transaction succeeded update your local system and redirect the customer */
	if($request->requestHasSucceeded()) {
    $request->getResponseParam('rc');
    $request->getResponseParam('msg');
    $request->getResponseParam('reference');
    $request->getResponseParam('url');

    $request->redirectCustomerToPaymentProvider();
	}
	
	/* if the transaction did not succeed update your local system, get the responsecode and notify the customer */
	else {
    $request->getResponseParam('rc');
    $request->getResponseParam('msg');
    $request->getResponseMessage($request->getResponseParam('rc'),'DE');
    
    echo "<pre>";print_r($request->getResponseRaw());echo "</pre>";
	}
}
catch (Exception $e) { echo $e->getMessage(); }