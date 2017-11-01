<?php
/**
 * A PHP eWAY Rapid API library implementation.
 * This class is an example of how to connect to eWAY's Rapid API.
 *
 * Requires PHP 5.3 or greater with the cURL extension
 *
 * @see https://eway.io/api-v3/
 * @version 1.2
 * @package eWAY
 * @author eWAY
 * @copyright (c) 2015, Web Active Corporation Pty Ltd
 */

namespace eWAY;

/**
 * eWAY Rapid 3.1 Library
 *
 * Check examples for usage
 *
 * @package eWAY
 */
class RapidAPI
{

    /**
     * @var string the eWAY endpoint
     */
    private $url;

    /**
     * @var bool true if using eWAY sandbox
     */
    private $sandbox;

    /**
     * @var string the eWAY API key
     */
    private $apiKey;

    /**
     * @var string the eWAY API password
     */
    private $apiPassword;

    /**
     * @var bool true to turn off cURL SSL_VERIFYPEER for testing
     * NOTE: only available on sandbox
     */
    private $disableSslVerify;

    /**
     * @var string raw last request sent to eWAY
     */
    private $lastRequest;

    /**
     * @var string raw last response from eWAY
     */
    private $lastResponse;

    /**
     * @var string last URL connected to
     */
    private $lastUrl;


    /**
     * RapidAPI constructor
     *
     * @param string $apiKey your eWAY Rapid API Key
     * @param string $apiPassword your eWAY Rapid API Password
     * @param array $params set options for connecting to eWAY
     *      $params['sandbox'] to true to use the sandbox for testing
     *      $params['disable_ssl_verification'] to true to disable SSL verification in sandbox
     */
    public function __construct($apiKey, $apiPassword, $params = array())
    {
        if (strlen($apiKey) === 0 || strlen($apiPassword) === 0) {
            die("Username and Password are required");
        }

        $this->apiKey = $apiKey;
        $this->apiPassword = $apiPassword;
        $this->url = 'https://api.ewaypayments.com/';
        $this->sandbox = false;

        if (count($params)) {
            if (isset($params['sandbox']) && $params['sandbox']) {
                $this->url = 'https://api.sandbox.ewaypayments.com/';
                $this->sandbox = true;
            }
            if (isset($params['disable_ssl_verification'])
                    && $params['disable_ssl_verification']
                    && $this->sandbox == true) {
                $this->disableSslVerify = true;
            }
        }
    }

    /**
     * Create a request for a Transparent Redirect Access Code
     *
     * @see https://eway.io/api-v3/#transparent-redirect
     * @param eWAY\CreateAccessCodeRequest $request
     * @return object decoded response from eWAY
     */
    public function CreateAccessCode($request)
    {
        $jsonRequest = $this->fixObjtoJSON($request);
        $response = $this->PostToRapidAPI("AccessCodes", $jsonRequest);
        return $response;
    }

    /**
     * Create an AccessCode & Redirect URL for a Responsive Shared Page payment
     *
     * @see https://eway.io/api-v3/#responsive-shared-page
     * @param eWAY\CreateAccessCodesSharedRequest $request
     * @return object decoded response from eWAY
     */
    public function CreateAccessCodesShared($request)
    {
        $jsonRequest = $this->fixObjtoJSON($request);
        $response = $this->PostToRapidAPI("AccessCodesShared", $jsonRequest);
        return $response;
    }

    /**
     * Get the result from an AccessCode after a customer has completed
     * a transaction with either Responsive Shared or Transparent Redirect.
     *
     * @param eWAY\GetAccessCodeResultRequest|string $request either a GetAccessCodeResultRequest
     *  containing the access code or the access code itself.
     * @return object decoded response from eWAY
     */
    public function GetAccessCodeResult($request)
    {
        // Fallback on using the GET variable (old behaviour)
        if ((empty($request)
                || (is_a($request, 'eWAY\GetAccessCodeResultRequest')
                && empty($request->AccessCode)))
                && isset($_GET['AccessCode'])) {
            $request = $_GET['AccessCode'];
        }
        if (empty($request)
                && !isset($request->AccessCode)
                && empty($request->AccessCode)) {
            die('No access code provided!');
        }
        // Legacy method
        if (is_a($request, 'eWAY\GetAccessCodeResultRequest')) {
            $response = $this->PostToRapidAPI("AccessCode/" . $request->AccessCode, '', false);
        } else {
            $response = $this->PostToRapidAPI("AccessCode/" . $request, '', false);
        }
        return $response;
    }

    /**
     * Perform a Direct Payment
     *
     * Note: Before being able to send credit card data via the direct API, eWAY
     * must enable it on the account. To be enabled on a live account eWAY must
     * receive proof that the environment is PCI-DSS compliant or use Client
     * Side Encryption
     *
     * @see https://eway.io/api-v3/#direct-connection
     * @param eWAY\CreateDirectPaymentRequest $request
     * @return object decoded response from eWAY
     */
    public function DirectPayment($request)
    {
        $jsonRequest = $this->fixObjtoJSON($request);
        $response = $this->PostToRapidAPI("Transaction", $jsonRequest);
        return $response;
    }

    /**
     * Performs a refund
     * Note: Before accessing the direct refund API you must add the Refund
     *  ability to your API user role.
     *
     * @see https://eway.io/api-v3/#refunds
     * @param eWAY\CreateRefundRequest $request
     * @return object decoded response from eWAY
     */
    public function Refund($request)
    {
        $transactionID = $request->Refund->TransactionID;
        if (empty($transactionID)) {
            die("Refund transaction ID missing");
        }
        $jsonRequest = $this->fixObjtoJSON($request);
        $response = $this->PostToRapidAPI("Transaction/$transactionID/Refund", $jsonRequest);
        return $response;
    }

    /**
     * Queries a transaction using either the Transaction ID or Access Code
     *
     * @see https://eway.io/api-v3/#transaction-query
     * @param string $request access code or transaction ID
     * @return object decoded response from eWAY
     */
    public function TransactionQuery($request)
    {
        if (empty($request)) {
            die('No Transaction ID or Access Code provided!');
        }
        $response = $this->PostToRapidAPI("Transaction/" . $request, '', false);

        return $response;
    }

    /**
     * Captures a pre-auth
     *
     * @see https://eway.io/api-v3/#capture-a-payment
     * @param eWAY\CaptureRequest $request
     * @return object decoded response from eWAY
     */
    public function CapturePayment($request)
    {
        $jsonRequest = $this->fixObjtoJSON($request);
        $response = $this->PostToRapidAPI("CapturePayment", $jsonRequest);
        return $response;
    }

    /**
     * Cancels a pre-auth
     *
     * @see https://eway.io/api-v3/#cancel-an-authorisation
     * @param eWAY\CancelRequest $request
     * @return object decoded response from eWAY
     */
    public function CancelAuthorisation($request)
    {
        $jsonRequest = $this->fixObjtoJSON($request);
        $response = $this->PostToRapidAPI("CancelAuthorisation", $jsonRequest);
        return $response;
    }

    /**
     * Fetches the message associated with a Response Code
     *
     * @param string $code
     * @return string
     */
    public function getMessage($code)
    {
        return ResponseCode::getMessage($code);
    }

    /**
     * Formats the request into JSON
     *
     * @param object $request
     * @return string JSON encoded string
     */
    private function fixObjtoJSON($request)
    {
        // Nest options correctly
        if (isset($request->Options) && count($request->Options->Option)) {
            $i = 0;
            $tempClass = new \stdClass();
            foreach ($request->Options->Option as $Option) {
                $tempClass->Options[$i] = $Option;
                $i++;
            }
            $request->Options = $tempClass->Options;
        }
        
        // Format and nest LineItems correctly
        if (isset($request->Items) && count($request->Items->LineItem)) {
            $i = 0;
            $tempClass = new \stdClass();
            foreach ($request->Items->LineItem as $LineItem) {
                // must be strings
                if (isset($LineItem->Quantity)) {
                    $LineItem->Quantity = (string)($LineItem->Quantity);
                }
                if (isset($LineItem->UnitCost)) {
                    $LineItem->UnitCost = strval($LineItem->UnitCost);
                }
                if (isset($LineItem->Tax)) {
                    $LineItem->Tax = strval($LineItem->Tax);
                }
                if (isset($LineItem->Total)) {
                    $LineItem->Total = strval($LineItem->Total);
                }
                $tempClass->Items[$i] = $LineItem;
                $i++;
            }
            $request->Items = $tempClass->Items;
        }

        // fix blank issue
        if (isset($request->RedirectUrl)) {
            $request->RedirectUrl = str_replace(' ', '%20', $request->RedirectUrl);
        }
        if (isset($request->CancelUrl)) {
            $request->CancelUrl = str_replace(' ', '%20', $request->CancelUrl);
        }

        $jsonRequest = json_encode($request);

        return $jsonRequest;
    }

    /**
     * A function for doing a Curl GET/POST
     *
     * This will die in event of an error!
     *
     * @param string $path the path for this request
     * @param sring $request JSON encoded request body
     * @param boolean $isPost set to false to perform a GET
     * @return object response from eWAY
     */
    private function PostToRapidAPI($path, $request, $isPost = true)
    {
        $this->lastRequest = $request;
        $this->lastResponse = '';

        $url = $this->url . $path;
        $ch = curl_init($url);
        $this->lastUrl = $url;

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'User-Agent: eWAY-PHP-1.2'
        ));
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $this->apiKey . ":" . $this->apiPassword);
        if ($isPost) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        } else {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        if ($this->disableSslVerify) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        // Ucomment for CURL debugging
        //curl_setopt($ch, CURLOPT_VERBOSE, true);

        $response = curl_exec($ch);

        $this->lastResponse = $response;

        if (curl_errno($ch) != CURLE_OK) {
            echo "<h2>Connection Error: " . curl_error($ch) . " URL: $url</h2><pre>";
            die();
        } else {
            $info = curl_getinfo($ch);
            if ($info['http_code'] == 401 || $info['http_code'] == 404 || $info['http_code'] == 403) {
                $__is_in_sandbox = $this->sandbox ? ' (Sandbox)' : ' (Live)';
                echo "<h2>Please check the API Key and Password $__is_in_sandbox</h2><pre>";
                die();
            }

            curl_close($ch);
            $decode = json_decode($response);
            if ($decode === null) {
                die("Error decoding response from eWAY");
            }

            return $decode;
        }
    }

    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function setApiPassword($password)
    {
        $this->apiPassword = $password;
    }

    public function setSandbox($sandbox)
    {
        $this->sandbox = $sandbox;
    }

    public function setDisableSslVerify($disableSslVerify)
    {
        $this->disableSslVerify = $disableSslVerify;
    }

    public function getLastRequest()
    {
        return $this->lastRequest;
    }

    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    public function getLastUrl()
    {
        return $this->lastUrl;
    }
}

/**
 * Class containing translations of Response Codes
 */
class ResponseCode
{
    /**
     * @see https://eway.io/api-v3/#response-amp-error-codes
     * @var array response codes
     */
    private static $codes = array(
        'F7000' => 'Undefined Fraud Error',
        'V5000' => 'Undefined System',
        'A0000' => 'Undefined Approved',
        'A2000' => 'Transaction Approved',      // Successful
        'A2008' => 'Honour With Identification', // Successful
        'A2010' => 'Approved For Partial Amount', // Successful
        'A2011' => 'Approved VIP',              // Successful
        'A2016' => 'Approved Update Track 3',   // Successful
        'V6000' => 'Undefined Validation Error',
        'V6001' => 'Invalid Customer IP',
        'V6002' => 'Invalid DeviceID',
        'V6011' => 'Invalid Amount',
        'V6012' => 'Invalid Invoice Description',
        'V6013' => 'Invalid Invoice Number',
        'V6014' => 'Invalid Invoice Reference',
        'V6015' => 'Invalid Currency Code',
        'V6016' => 'Payment Required',
        'V6017' => 'Payment Currency Code Required',
        'V6018' => 'Unknown Payment Currency Code',
        'V6021' => 'Cardholder Name Required',
        'V6022' => 'Card Number Required',
        'V6023' => 'CVN Required',
        'V6031' => 'Invalid Card Number',
        'V6032' => 'Invalid CVN',
        'V6033' => 'Invalid Expiry Date',
        'V6034' => 'Invalid Issue Number',
        'V6035' => 'Invalid Start Date',
        'V6036' => 'Invalid Month',
        'V6037' => 'Invalid Year',
        'V6040' => 'Invalid Token Customer Id',
        'V6041' => 'Customer Required',
        'V6042' => 'Customer First Name Required',
        'V6043' => 'Customer Last Name Required',
        'V6044' => 'Customer Country Code Required',
        'V6045' => 'Customer Title Required',
        'V6046' => 'Token Customer ID Required',
        'V6047' => 'RedirectURL Required',
        'V6051' => 'Invalid Customer First Name',
        'V6052' => 'Invalid Customer Last Name',
        'V6053' => 'Invalid Customer Country Code',
        'V6054' => 'Invalid Customer Email',
        'V6055' => 'Invalid Customer Phone',
        'V6056' => 'Invalid Customer Mobile',
        'V6057' => 'Invalid Customer Fax',
        'V6058' => 'Invalid Customer Title',
        'V6059' => 'Redirect URL Invalid',
        'V6060' => 'Invalid TokenCustomerID',
        'V6061' => 'Invalid Customer Reference',
        'V6062' => 'Invalid Customer Company Name',
        'V6063' => 'Invalid Customer Job Description',
        'V6064' => 'Invalid Customer Street1',
        'V6065' => 'Invalid Customer Street2',
        'V6066' => 'Invalid Customer City',
        'V6067' => 'Invalid Customer State',
        'V6068' => 'Invalid Customer Postalcode',
        'V6069' => 'Invalid Customer Email',
        'V6070' => 'Invalid Customer Phone',
        'V6071' => 'Invalid Customer Mobile',
        'V6072' => 'Invalid Customer Comments',
        'V6073' => 'Invalid Customer Fax',
        'V6074' => 'Invalid Customer Url',
        'V6075' => 'Invalid ShippingAddress First Name',
        'V6076' => 'Invalid ShippingAddress Last Name',
        'V6077' => 'Invalid ShippingAddress Street1',
        'V6078' => 'Invalid ShippingAddress Street2',
        'V6079' => 'Invalid ShippingAddress City',
        'V6080' => 'Invalid ShippingAddress State',
        'V6081' => 'Invalid ShippingAddress PostalCode',
        'V6082' => 'Invalid ShippingAddress Email',
        'V6083' => 'Invalid ShippingAddress Phone',
        'V6084' => 'Invalid ShippingAddress Country',
        'V6091' => 'Unknown Country Code',
        'V6100' => 'Invalid Card Name',
        'V6101' => 'Invalid Card Expiry Month',
        'V6102' => 'Invalid Card Expiry Year',
        'V6103' => 'Invalid Card Start Month',
        'V6104' => 'Invalid Card Start Year',
        'V6105' => 'Invalid Card Issue Number',
        'V6106' => 'Invalid Card CVN',
        'V6107' => 'Invalid AccessCode',
        'V6108' => 'Invalid CustomerHostAddress',
        'V6109' => 'Invalid UserAgent',
        'V6110' => 'Invalid Card Number',
        'V6111' => 'Unauthorised API Access, Account Not PCI Certified',
        'V6112' => 'Redundant card details other than expiry year and month',
        'V6113' => 'Invalid transaction for refund',
        'V6114' => 'Gateway validation error',
        'V6115' => 'Invalid DirectRefundRequest, Transaction ID',
        'V6116' => 'Invalid card data on original TransactionID',
        'V6117' => 'Invalid CreateAccessCodeSharedRequest, FooterText',
        'V6118' => 'Invalid CreateAccessCodeSharedRequest, HeaderText',
        'V6119' => 'Invalid CreateAccessCodeSharedRequest, Language',
        'V6120' => 'Invalid CreateAccessCodeSharedRequest, LogoUrl',
        'V6121' => 'Invalid TransactionSearch, Filter Match Type',
        'V6122' => 'Invalid TransactionSearch, Non numeric Transaction ID',
        'V6123' => 'Invalid TransactionSearch,no TransactionID or AccessCode specified',
        'V6124' => 'Invalid Line Items. The line items have been provided however the totals do not match the TotalAmount field',
        'V6125' => 'Selected Payment Type not enabled',
        'V6126' => 'Invalid encrypted card number, decryption failed',
        'V6127' => 'Invalid encrypted cvn, decryption failed',
        'V6128' => 'Invalid Method for Payment Type',
        'V6129' => 'Transaction has not been authorised for Capture/Cancellation',
        'V6130' => 'Generic customer information error',
        'V6131' => 'Generic shipping information error',
        'V6132' => 'Transaction has already been completed or voided, operation not permitted',
        'V6133' => 'Checkout not available for Payment Type',
        'V6134' => 'Invalid Auth Transaction ID for Capture/Void',
        'V6135' => 'PayPal Error Processing Refund',
        'V6140' => 'Merchant account is suspended',
        'V6141' => 'Invalid PayPal account details or API signature',
        'V6142' => 'Authorise not available for Bank/Branch',
        'V6150' => 'Invalid Refund Amount',
        'V6151' => 'Refund amount greater than original transaction',
        'V6152' => 'Original transaction already refunded for total amount',
        'V6153' => 'Card type not support by merchant',
        'D4401' => 'Refer to Issuer',
        'D4402' => 'Refer to Issuer, special',
        'D4403' => 'No Merchant',
        'D4404' => 'Pick Up Card',
        'D4405' => 'Do Not Honour',
        'D4406' => 'Error',
        'D4407' => 'Pick Up Card, Special',
        'D4409' => 'Request In Progress',
        'D4412' => 'Invalid Transaction',
        'D4413' => 'Invalid Amount',
        'D4414' => 'Invalid Card Number',
        'D4415' => 'No Issuer',
        'D4419' => 'Re-enter Last Transaction',
        'D4421' => 'No Method Taken',
        'D4422' => 'Suspected Malfunction',
        'D4423' => 'Unacceptable Transaction Fee',
        'D4425' => 'Unable to Locate Record On File',
        'D4430' => 'Format Error',
        'D4431' => 'Bank Not Supported By Switch',
        'D4433' => 'Expired Card, Capture',
        'D4434' => 'Suspected Fraud, Retain Card',
        'D4435' => 'Card Acceptor, Contact Acquirer, Retain Card',
        'D4436' => 'Restricted Card, Retain Card',
        'D4437' => 'Contact Acquirer Security Department, Retain Card',
        'D4438' => 'PIN Tries Exceeded, Capture',
        'D4439' => 'No Credit Account',
        'D4440' => 'Function Not Supported',
        'D4441' => 'Lost Card',
        'D4442' => 'No Universal Account',
        'D4443' => 'Stolen Card',
        'D4444' => 'No Investment Account',
        'D4451' => 'Insufficient Funds',
        'D4452' => 'No Cheque Account',
        'D4453' => 'No Savings Account',
        'D4454' => 'Expired Card',
        'D4455' => 'Incorrect PIN',
        'D4456' => 'No Card Record',
        'D4457' => 'Function Not Permitted to Cardholder',
        'D4458' => 'Function Not Permitted to Terminal',
        'D4460' => 'Acceptor Contact Acquirer',
        'D4461' => 'Exceeds Withdrawal Limit',
        'D4462' => 'Restricted Card',
        'D4463' => 'Security Violation',
        'D4464' => 'Original Amount Incorrect',
        'D4466' => 'Acceptor Contact Acquirer, Security',
        'D4467' => 'Capture Card',
        'D4475' => 'PIN Tries Exceeded',
        'D4482' => 'CVV Validation Error',
        'D4490' => 'Cutoff In Progress',
        'D4491' => 'Card Issuer Unavailable',
        'D4492' => 'Unable To Route Transaction',
        'D4493' => 'Cannot Complete, Violation Of The Law',
        'D4494' => 'Duplicate Transaction',
        'D4496' => 'System Error',
        'D4497' => 'MasterPass Error Failed',
        'D4498' => 'PayPal Create Transaction Error Failed',
        'D4499' => 'Invalid Transaction for Auth/Void'
    );

    /**
     * Fetches the message associated with a Response Code
     *
     * @param string $code
     * @return string
     * @static
     */
    public static function getMessage($code)
    {
        if (isset(ResponseCode::$codes[$code])) {
            return ResponseCode::$codes[$code];
        } else {
            return $code;
        }
    }
}

/**
 * Base eWAY Request class
 */
abstract class Request
{
    public $Customer;

    public $ShippingAddress;
    public $Items;
    public $Options;

    public $Payment;

    /**
     * @var string The action to perform with this request (defaults to ProcessPayment)
     * One of: ProcessPayment, CreateTokenCustomer, ​UpdateTokenCustomer, TokenPayment, Authorise
     */
    public $Method;

    /**
     * @var string The type of transaction you’re performing. (defaults to Purchase)
     * One of: Purchase, MOTO, Recurring
     */
    public $TransactionType;

    /**
     * @var string The customer’s IP address. Defaults to $_SERVER["REMOTE_ADDR"]
     */
    public $CustomerIP;

    /**
     * @var string The identification name/number for the device or application
     * used to process the transaction.
     */
    public $DeviceID;

    /**
     * @var string The partner ID generated from a partner agreement with eWAY
     */
    public $PartnerID;

    public function __construct()
    {
        $this->Customer = new Customer();
        $this->ShippingAddress = new ShippingAddress();
        $this->Payment = new Payment();
        if (isset($_SERVER["REMOTE_ADDR"]) && !empty($_SERVER["REMOTE_ADDR"])) {
            $this->CustomerIP = $_SERVER["REMOTE_ADDR"];
        }
        $this->DeviceID = 'eWAY-php-1.2';
    }
}

/**
 * Contains details to create a Transparent Redirect Access Code
 */
class CreateAccessCodeRequest extends Request
{
    /**
     * @var string The web address the customer is redirected to with the result
     * of the action
     */
    public $RedirectUrl;

    /**
     * @var bool Setting this to "True" will process a PayPal Checkout payment
     */
    public $CheckoutPayment;

    /**
     * @var string When CheckoutPayment is set to "True" you must specify a
     * CheckoutURL for the customer to be returned to after logging in to their
     * PayPal account
     */
    public $CheckoutURL;
}

/**
 * Contains details to create a Responsive Shared Page Redirect
 */
class CreateAccessCodesSharedRequest extends Request
{
    /**
     * @var string The URL that the shared page redirects to after a payment is
     * processed
     */
    public $RedirectUrl;

    /**
     * @var string The URL that the shared page redirects to if a customer
     * cancels the transaction
     */
    public $CancelUrl;

    /**
     *
     * @var string The URL of the merchant's logo to display on the shared page
     */
    public $LogoUrl;

    /**
     * @var string Short text description to be placed under the logo on the shared page
     */
    public $HeaderText;

    /**
     * @var bool When set to false, cardholders will be able to edit the
     * information on the shared page even if it’s sent through in the server
     * side reques
     */
    public $CustomerReadOnly;

    /**
     * @var string Language code determines the language that the shared page will be
     * displayed in. One of EN or ES
     */
    public $Language;

    /**
     *
     * @var string Set the theme of the Responsive Shared Page from 12
     * predetermined themes (default is Default)
     * One of: Default, Bootstrap, BootstrapAmelia, BootstrapCerulean, BootstrapCosmo,
     * BootstrapCyborg, BootstrapFlatly, BootstrapJournal, BootstrapReadable,
     * BootstrapSimplex, BootstrapSlate, BootstrapSpacelab, BootstrapUnited
     */
    public $CustomView;

    /**
     * @var bool Set whether the customers phone should be confirmed using Beagle Verify
     * (an SMS is sent to the customer's phone)
     */
    public $VerifyCustomerPhone;

    /**
     * @var bool Set whether the customers email should be confirmed using Beagle Verify
     */
    public $VerifyCustomerEmail;
}

/**
 * Contains details to complete a Direct Payment
 */
class CreateDirectPaymentRequest extends Request
{
    public function __construct()
    {
        parent::__construct();
        $this->Customer = new CardCustomer();
    }
}

/**
 * Contains details to complete a Refund
 */
class CreateRefundRequest
{
    public $Refund;
    public $Customer;

    public $ShippingAddress;
    public $Items;
    public $Options;

    public $CustomerIP;
    public $DeviceID;
    public $PartnerID;

    public function __construct()
    {
        $this->Refund = new Refund();
        $this->Customer = new RefundCustomer();
        $this->ShippingAddress = new ShippingAddress();
        if (isset($_SERVER["REMOTE_ADDR"]) && !empty($_SERVER["REMOTE_ADDR"])) {
            $this->CustomerIP = $_SERVER["REMOTE_ADDR"];
        }
        $this->DeviceID = 'eWAY-php-1.2';
    }
}

/**
 * Contains details of a Customer
 */
class Customer
{
    /**
     * @var string An eWAY-issued ID that represents the Token customer to be
     * loaded for this action
     */
    public $TokenCustomerID;

    /**
     * @var string The merchant’s reference for this customer
     */
    public $Reference;

    /**
     *
     * @var string The customer’s title, empty string allowed
     * One of: Mr., Ms., Mrs., Miss, Dr., Sir., Prof.
     */
    public $Title;

    public $FirstName;
    public $LastName;
    public $CompanyName;
    public $JobDescription;
    public $Street1;
    public $Street2;
    public $City;
    public $State;
    public $PostalCode;

    /**
     * @var string The customer’s country. This should be the two letter
     * ISO 3166-1 alpha-2 code in lower case.
     * e.g. au for Australia
     */
    public $Country;

    public $Email;
    public $Phone;
    public $Mobile;
    public $Comments;
    public $Fax;
    public $Url;
}

/**
 * Contains details of a Customer with card details (for Direct only)
 */
class CardCustomer extends Customer
{
    public function __construct()
    {
        $this->CardDetails = new CardDetails();
    }
}

/**
 * Contains details of a Customer with card details (for Direct only)
 */
class RefundCustomer extends Customer
{
    public function __construct()
    {
        $this->CardDetails = new RefundCardDetails();
    }
}

/**
 * Contains details of Shipping Address
 */
class ShippingAddress
{
    public $FirstName;
    public $LastName;
    public $Street1;
    public $Street2;
    public $City;
    public $State;

    /**
     * @var string The customer’s country. This should be the two letter
     * ISO 3166-1 alpha-2 code in lower case.
     * e.g. au for Australia
     */
    public $Country;

    public $PostalCode;
    public $Email;
    public $Phone;

    /**
     * @var string The method used to ship the customer’s order
     * One of: Unknown, LowCost, DesignatedByCustomer, International, Military,
     * NextDay, StorePickup, TwoDayService, ThreeDayService, Other
     */
    public $ShippingMethod;
}

/**
 * Contains details of Items
 */
class Items
{
    public $LineItem = array();
}

/**
 * Contains details of a Line Item
 */
class LineItem
{
    public $SKU;
    public $Description;
    public $Quantity;
    public $UnitCost;
    public $Tax;
    public $Total;
}

/**
 * Contains details of Options
 */
class Options
{
    public $Option = array();
}

/**
 * Contains details of an Option
 */
class Option
{
    public $Value;
    public function __construct($value = '')
    {
        $this->Value = $value;
    }
}

/**
 * Contains details of a Payment
 */
class Payment
{
    /**
     * @var int The amount of the transaction in the lowest denomination for the
     * currency (e.g. a $27.00 transaction would have a TotalAmount value of ‘2700’).
     *
     * The value of this field must be 0 for the CreateTokenCustomer and
     * UpdateTokenCustomer methods
     */
    public $TotalAmount;

    public $InvoiceNumber;
    public $InvoiceDescription;
    public $InvoiceReference;

    /**
     * @var string The 3 character code that represents the currency that this
     * transaction is to be processed in. Default is merchant's default currency.
     * e.g. AUD for Australian Dollar
     */
    public $CurrencyCode;
}

/**
 * Contains details of a Refund
 */
class Refund extends Payment
{
    public $TransactionID;
}

/**
 * Contains details to request the result of an Access Code
 * GetAccessCodeResult can now be called with just the Access Code.
 * @deprecated since version 1.1
 */
class GetAccessCodeResultRequest
{
    public $AccessCode;

    public function __construct($accessCode = '')
    {
        $this->AccessCode = $accessCode;
    }
}

/**
 * Contains details to capture a pre-auth
 */
class CaptureRequest
{
    public $Payment;
    public $TransactionID;

    public function __construct()
    {
        $this->Payment = new Payment();
    }
}

/**
 * Contains details to cancel a pre-auth
 */
class CancelRequest
{
    public $TransactionID;
}

/**
 * Contains details of a credit card
 */
class CardDetails
{
    public $Name;
    public $Number;
    public $ExpiryMonth;
    public $ExpiryYear;
    public $StartMonth;
    public $StartYear;
    public $IssueNumber;
    public $CVN;
}

/**
 * Contains details of a credit card for a refund
 */
class RefundCardDetails
{
    public $ExpiryMonth;
    public $ExpiryYear;
}
