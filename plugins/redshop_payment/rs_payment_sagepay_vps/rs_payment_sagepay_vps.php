<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class PlgRedshop_Paymentrs_Payment_Sagepay_Vps
 *
 * @since  1.5
 */
class PlgRedshop_Paymentrs_Payment_Sagepay_Vps extends JPlugin
{
	protected $autoloadLanguage = true;

	/**
	 * Constructor
	 *
	 * @param   object &$subject   The object to observe
	 * @param   array  $config     An optional associative array of configuration settings.
	 *                             Recognized key values include 'name', 'group', 'params', 'language'
	 *                             (this list is not meant to be comprehensive).
	 */
	public function __construct(&$subject, $config = array())
	{
		JPlugin::loadLanguage('plg_redshop_payment_rs_payment_sagepay_vps');
		parent::__construct($subject, $config);
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment($element, $data)
	{
		// Get user billing information
		$user = JFActory::getUser();

		if ($element != 'rs_payment_sagepay_vps')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		// Get params from plugin
		$strVendorName                = $this->params->get('sagepay_vendorname', '');
		$payment_method               = $this->params->get('payment_method', '');
		$sagepay_vps_transaction_type = $this->params->get('sagepay_vps_transaction_type', 'PAYMENT');
		$order_desc                   = $this->params->get('order_desc', JText::_('COM_REDSHOP_ORDER_ID_LBL') . ":" . $data['order_id']);
		$sagepay_vps_test_status      = $this->params->get('sagepay_vps_test_status', '');
		$sagepay_3dsecure             = $this->params->get('sagepay_3dsecure', '0');
		$app                          = JFactory::getApplication();
		$input                        = $app->input;
		$Itemid                       = $input->getInt('Itemid');

		if ($this->params->get("currency"))
		{
			$currency_main = $this->params->get("currency");
		}
        elseif (Redshop::getConfig()->get('CURRENCY_CODE') != "")
		{
			$currency_main = Redshop::getConfig()->get('CURRENCY_CODE');
		}
		else
		{
			$currency_main = "USD";
		}

		$session         = JFactory::getSession();
		$redirect_ccdata = $session->get('ccdata');

		// Additional Customer Data
		$user_id    = $data['billinginfo']->user_id;
		$remote_add = $_SERVER["REMOTE_ADDR"];

		// Email Settings
		$user_email   = $data['billinginfo']->user_email;
		$order_number = substr($data['order_number'], 0, 16);
		$tax_exempt   = false;

		// Get Credit card Information
		$strCardType   = $redirect_ccdata['creditcard_code'];
		$strCardHolder = substr($redirect_ccdata['order_payment_name'], 0, 100);
		$strCardNumber = substr($redirect_ccdata['order_payment_number'], 0, 20);
		$strExpiryDate = substr($redirect_ccdata['order_payment_expire_month'], 0, 2) . substr($redirect_ccdata['order_payment_expire_year'], -2);
		$strCV2        = substr($redirect_ccdata['credit_card_code'], 0, 4);

		$strTimeStamp    = date("y-m-d-H-i-s", time());
		$intRandNum      = rand(0, 32000) * rand(0, 32000);
		$strVendorTxCode = $strVendorName . "-" . $strTimeStamp . "-" . $intRandNum;

		$strCurrency              = $currency_main;
		$_SESSION["VendorTxCode"] = $strVendorTxCode;

		// Assign Amount
		$tot_amount = $order_total = $data['order']->order_total;
		$amount     = RedshopHelperCurrency::convert($tot_amount, '', $strCurrency);
		$amount     = number_format($amount, 2, '.', '');

		$strPost = "VPSProtocol=2.23";

		// PAYMENT by default.  You can change this in the includes file
		$strPost = $strPost . "&TxType=" . $sagepay_vps_transaction_type;
		$strPost = $strPost . "&Vendor=" . $strVendorName;

		// As generated above
		$strPost = $strPost . "&VendorTxCode=" . $strVendorTxCode;

		// Formatted to 2 decimal places with leading digit but no commas or currency symbols **
		$strPost = $strPost . "&Amount=" . $amount;
		$strPost = $strPost . "&Currency=" . $strCurrency;
		$strPost = $strPost . "&CardHolder=" . $strCardHolder;

		$strPost = $strPost . "&CardNumber=" . $strCardNumber;
		$strPost = $strPost . "&ExpiryDate=" . $strExpiryDate;
		$strPost = $strPost . "&CV2=" . $strCV2;
		$strPost = $strPost . "&CardType=" . $strCardType;

		// Send the IP address of the person entering the card details
		$strPost = $strPost . "&ClientIPAddress=" . $_SERVER['REMOTE_ADDR'];
		$strPost = $strPost . "&Description=" . $order_desc;

		/*
		Billing Details
		This section is optional in its entirety but if one field of the address is provided then all non-optional fields must be provided
		If AVS/CV2 is ON for your account, or, if paypal cardtype is specified and its not via PayPal Express then this section is compulsory
		*/
		$strPost = $strPost . "&BillingFirstnames=" . urlencode($data['billinginfo']->firstname);
		$strPost = $strPost . "&BillingSurname=" . urlencode($data['billinginfo']->lastname);
		$strPost = $strPost . "&BillingAddress1=" . urlencode($data['billinginfo']->address);
		$strPost = $strPost . "&BillingCity=" . urlencode($data['billinginfo']->city);
		$strPost = $strPost . "&BillingPostCode=" . urlencode($data['billinginfo']->zipcode);
		$strPost = $strPost . "&BillingCountry=" . urlencode($data['billinginfo']->country_2_code);

		/*Delivery Details
		This section is optional in its entirety but if one field of the address is provided then all non-optional fields must be provided
		If paypal cardtype is specified then this section is compulsory
		*/
		$strPost = $strPost . "&DeliveryFirstnames=" . urlencode($data['shippinginfo']->firstname);
		$strPost = $strPost . "&DeliverySurname=" . urlencode($data['shippinginfo']->lastname);
		$strPost = $strPost . "&DeliveryAddress1=" . urlencode($data['shippinginfo']->address);
		$strPost = $strPost . "&DeliveryCity=" . urlencode($data['shippinginfo']->city);
		$strPost = $strPost . "&DeliveryPostCode=" . urlencode($data['shippinginfo']->zipcode);
		$strPost = $strPost . "&DeliveryCountry=" . urlencode($data['shippinginfo']->country_2_code);

		if ($sagepay_3dsecure)
		{
			$strPost = $strPost . "&Apply3DSecure=1";
		}
		else
		{
			$strPost = $strPost . "&Apply3DSecure=0";
		}

		$strPost = $strPost . "&AccountType=E";

		if ($payment_method == "SIMULATOR")
		{
			$url = "https://test.sagepay.com/simulator/VSPDirectGateway.asp";
		}
        elseif ($payment_method == "TEST")
		{
			$url = "https://test.sagepay.com/gateway/service/vspdirect-register.vsp";
		}
		else
		{
			$url = "https://live.sagepay.com/gateway/service/vspdirect-register.vsp";
		}

		// Set a one-minute timeout for this script
		set_time_limit(60);
		$output = array();

		// Open the cURL session
		$curlSession = curl_init();
		curl_setopt($curlSession, CURLOPT_URL, $url);
		curl_setopt($curlSession, CURLOPT_HEADER, 0);
		curl_setopt($curlSession, CURLOPT_POST, 1);
		curl_setopt($curlSession, CURLOPT_POSTFIELDS, $strPost);
		curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curlSession, CURLOPT_TIMEOUT, 30);
		curl_setopt($curlSession, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curlSession, CURLOPT_SSL_VERIFYHOST, 1);
		$rawresponse = curl_exec($curlSession);
		$response    = explode(chr(10), $rawresponse);

		// Tokenise the response
		for ($i = 0, $in = count($response); $i < $in; $i++)
		{
			// Find position of first "=" character
			$splitAt = strpos($response[$i], "=");

			// Create an associative (hash) array with key/value pairs ('trim' strips excess whitespace)
			$output[trim(substr($response[$i], 0, $splitAt))] = trim(substr($response[$i], ($splitAt + 1)));
		}

		$strStatus       = $output["Status"];
		$strStatusDetail = $output["StatusDetail"];

		if ($strStatus == "3DAUTH")
		{
			/*
			This is a 3D-Secure transaction, so we need to redirect the customer to their bank
			for authentication.  First get the pertinent information from the response
			*/
			$strMD        = $output["MD"];
			$strACSURL    = $output["ACSURL"];
			$strPAReq     = $output["PAReq"];
			$strPageState = "3DRedirect";
			$returnURL    = JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_sagepay_vps&Itemid=$Itemid&orderid=" . $data['order_id'];

			?>
            <FORM action="<?php echo $strACSURL ?>" method="POST" name="secureform" id="secureform"/>
            <input type="text" name="PaReq" value="<?php echo $strPAReq ?>"/>
            <input type="text" name="TermUrl" value="<?php echo $returnURL ?>"/>
            <input type="text" name="MD" value="<?php echo $strMD ?>"/>
            <input type="text" name="3dsecure" value="1"/>
            </form>
            <script>
                document.getElementById("secureform").submit();
            </script>

			<?php
		}
		else
		{
			$returnURL = JURI::base() . "index.php?tmpl=component&option=com_redshop&view=order_detail&controller=order_detail&task=notify_payment&payment_plugin=rs_payment_sagepay_vps&Itemid=$Itemid&orderid=" . $data['order_id'];

			$values = new stdClass;

			// Update the database and redirect the user appropriately
			if ($strStatus == "OK" || $strStatus == "AUTHENTICATED" || $strStatus == "REGISTERED")
			{
				if ($strStatus == "OK")
					$strDBStatus = "AUTHORISED - The transaction was successfully authorised with the bank.";
                elseif ($strStatus == "AUTHENTICATED")
					$strDBStatus = "AUTHENTICATED - The transaction was successfully 3D-Secure Authenticated and can now be Authorised.";
                elseif ($strStatus == "REGISTERED")
					$strDBStatus = "REGISTERED - The transaction was could not be 3D-Secure Authenticated, but has been registered to be Authorised.";
				$values->responsestatus = 'Success';
				$values->transaction_id = $output['VPSTxId'];
			}
			else
			{
				if ($strStatus == "MALFORMED")
					$strDBStatus = "MALFORMED - The StatusDetail was:" . mysql_real_escape_string(substr($strStatusDetail, 0, 255));
                elseif ($strStatus == "INVALID")
					$strDBStatus = "INVALID - The StatusDetail was:" . mysql_real_escape_string(substr($strStatusDetail, 0, 255));
                elseif ($strStatus == "NOTAUTHED")
					$strDBStatus = "DECLINED - The transaction was not authorised by the bank.";
                elseif ($strStatus == "REJECTED")
					$strDBStatus = "REJECTED - The transaction was failed by your 3D-Secure or AVS/CV2 rule-bases.";
                elseif ($strStatus == "ERROR")
					$strDBStatus = "ERROR - There was an error during the payment process.  The error details are: " . mysql_real_escape_string($strStatusDetail);
				else
					$strDBStatus = "UNKNOWN - An unknown status was returned from Sage Pay.  The Status was: " . mysql_real_escape_string($strStatus) . ", with StatusDetail:" . mysql_real_escape_string($strStatusDetail);
				$values->transaction_id = 0;
				$values->responsestatus = 'Fail';
			} ?>
            <FORM action="<?php echo $returnURL ?>" method="POST" name="secureform" id="secureform"/>
            <input type="text" name="responsestatus" value="<?php echo $values->responsestatus ?>"/>
            <input type="text" name="transaction_id" value="<?php echo $values->transaction_id ?>"/>
            <input type="text" name="responsemessage" value="<?php echo $strDBStatus ?>"/>

            </form>
            <script>
                document.getElementById("secureform").submit();
            </script>
			<?php
		}
	}

	function onNotifyPaymentrs_payment_sagepay_vps($element, $request)
	{
		if ($element != 'rs_payment_sagepay_vps')
		{
			return;
		}

		$verify_status  = $this->params->get("verify_status");
		$invalid_status = $this->params->get("invalid_status");
		$order_id       = $request['orderid'];
		$transresult    = $request['responsestatus'];
		$message        = $request['responsemessage'];
		$values         = new stdClass;

		if ($transresult == "success")
		{
			$values->order_status_code         = $verify_status;
			$values->order_payment_status_code = 'Paid';
		}
		else
		{
			$values->order_status_code         = $invalid_status;
			$values->order_payment_status_code = 'Unpaid';
		}

		$values->order_id       = $order_id;
		$values->transaction_id = $request['transaction_id'];
		$values->msg            = $message;
		$values->log            = $message;

		return $values;
	}
}
