<?php
/**
 * payread_post_api.php - API to send transactions to payread
 *
 * This file contains all the methods you will need to be able to send transactions
 * to PAYER.
 *
 * This file should NOT be modified since new versions might overwrite
 * changes made by you.
 *
 *
 * Filename: payread_post_api.php
 * Project:  payread_post_api
 * Latest change: 2012-04-13
 * Modified by: bihla (master)
 * Verified by: mwall
 *
 * @author  Payer Installation <installation@payer.se>
 * @package payread_post_api
 *
 $Id: payread_post_api.php,v 1.23 2012/08/15 15:18:41 bihla Exp $
 $Log: payread_post_api.php,v $
 Revision 1.23  2012/08/15 15:18:41  bihla
 v25 add charset if NOT NULL

 Revision 1.22  2012/08/10 11:44:49  bihla
 v24 = added set_options (used when performing a "store")

 Revision 1.21  2012/08/10 11:43:18  bihla
 added set_options (used when performing a "store")

 Revision 1.20  2012/07/18 14:28:14  bihla
 do not set (reset) value if "*" is used. Use "*" in database if you want to use PayReadConf.php value instead.

 Revision 1.19  2012/05/29 09:45:03  bihla
 removed some payment methods among debug types

 Revision 1.18  2012/05/11 08:34:52  bihla
 + allow any payment method to be assigned
 Ver: payer_php_0_2_v22

 Revision 1.17  2012/05/10 09:57:25  bihla
 + added charset handling
 + added/improved firewall
 = get_api_version now only returns api version - no client version
 Ver: payer_php_0_2_v21

 Revision 1.16  2012/05/09 11:51:45  bihla
 payer_php_0_2_v20

 Revision 1.15  2012/05/09 11:50:05  bihla
 + added getKeyA + getKeyB + getChallangeResponse

 Revision 1.14  2012/04/27 08:09:13  bihla
 payer_php_0_2_v18

 Revision 1.13  2012/04/26 09:17:29  bihla
 add_fee can be called without itemNumber set.

 Revision 1.12  2012/04/18 09:11:51  bihla
 handle url encoded callbacks - found in some CMS systems (opencart)

 Revision 1.11  2012/04/13 14:55:22  bihla
 payer_php_0_2_v15 setKeyA setKeyB bug fixed

 Revision 1.10  2012/04/13 10:04:56  bihla
 payer_php_0_2_v14 setKeyA and setKeyB + setAgent added.

 Revision 1.9  2012/04/13 09:44:50  bihla
 added setKeyA + setKeyB (compatible version)

 Revision 1.8  2012/04/12 15:29:28  bihla
 new version => payer_php_0_2_v12

 Revision 1.7  2012/02/24 15:32:53  bihla
 pay-read.se => payer.se

 Revision 1.6  2012/02/24 13:20:46  mwall
 no message

 Revision 1.1  2011/11/28 10:10:25  mwall
 Bug 384 - Drupal 7.9 Ubercart 3.0

 */
class payread_post_api {

	/**#@+  beginning of docblock template area
	 * @access private
	 * @var string
	 * Internal variables and functions
	 */

	var $myAgentId;
	var $myKeys=array();
	var $myPayerServerUrl;
	var $myClientVersion;

	var $myCurrency;
	var $myDescription;
	var $myHideDetails;
	var $myReferenceId;
	var $myMessage;
	var $myCatalogPurchases=array();
	var $myFreeformPurchases=array();
	var $mySubscriptionPurchases=array();
	var $myInfoLines=array();
	var $myBuyerInfo;
	var $myPaymentMethods;
	var $mySuccessRedirectUrl;
	var $myAuthorizeNotificationUrl;
	var $mySettleNotificationUrl;
	var $myRedirectBackToShopUrl;
	var $myDebugMode;
	var $myTestMode;
	var $myLanguage;
	var $myCharSet;
	var $myFirewall;
	
	var $myXmlData;
	var $myChecksum;

	/**
	 * This is the default constructor, used by the POST API to read your
	 * authorization settings.
	 *
	 * You will never have to call this method
	 */
	function payread_post_api() {
		$PayRead_AgentId          = '';
		$PayRead_Key1             = '';
		$PayRead_Key2             = '';
		

		// Set defaults
		$this->myPostApiVersion   = "payer_php_0_2_v25";
		$this->myClientVersion    = null ;
		$this->myAgentId          = $PayRead_AgentId;
		$this->myKeys["A"]        = $PayRead_Key1;
		$this->myKeys["B"]        = $PayRead_Key2;
		$this->myPayerServerUrl   = "https://secure.payer.se/PostAPI_V1/InitPayFlow";
		$this->myCurrency         = "SEK";
		$this->myLanguage         = "sv";
		$this->myDebugMode        = "silent";
		$this->myTestMode         = "true";
		$this->myCharSet	  = null ; // Use database value as default
		$this->myFirewall         = array("192.168.100.1","79.136.103.5","94.140.57.180","94.140.57.181","94.140.57.184");
	}
	function setAgentId($agentid) {
		/* DO NOT USE THIS FUNCTION UNLESS YOU REALLY UNDERSTAND HOW IT WORKS */
		
		$this->myAgentId = $agentid;
		$this->myKeys["A"] = $KeyMap1[$agentid];
		$this->myKeys["B"] = $KeyMap2[$agentid];
	}
	function setAgent($agentid) {
		if ($agentid != "*") $this->myAgentId = $agentid;
	}
	function setKeys($key1, $key2) {
		if ($key1 != "*") $this->myKeys["A"] = $key1;
		if ($key2 != "*") $this->myKeys["B"] = $key2;
	}
	function setKeyA($key) {
		if ($key != "*") $this->myKeys["A"] = $key;
	}
	function setKeyB($key) {
		if ($key != "*") $this->myKeys["B"] = $key;
	}
	function getKeyA() {
		return $this->myKeys["A"];
	}
	function getKeyB() {
		return $this->myKeys["B"];
	}
	function setCharSet($charset) {
		$this->myCharSet=$charset;
	}
	function get_charset() {
		return $this->myCharSet;
	}
	function setClientVersion($version) {
		$this->myClientVersion = $version;
	}
	function getClientVersion() {
		return $this->myClientVersion;
	}

	/**
	 * This is the method that will print out the form data with the necessary parameters as hidden fields.
	 *
	 * example:
	 * 	<input type="hidden" name="payer_agentid" value="get_agentid()">
	 * 	<input type="hidden" name="payer_xml_writer" value="get_api_version()">
	 * 	<input type="hidden" name="payer_data" value="get_xml_data()">
	 * 	<input type="hidden" name="payer_checksum" value="get_checksum()">
	 * @return  nothing
	 */
	function generate_form() {
		echo $this->generate_form_str();
	}
	function return_generate_form() {
		return $this->generate_form_str();
	}

	function generate_form_str() {
		return
			"<input type=\"hidden\" name=\"payer_agentid\" value=\""	. $this->get_agentid()	  	. "\" />\n".
			"<input type=\"hidden\" name=\"payer_xml_writer\" value=\"". $this->get_api_version()	. "\" />\n".
			"<input type=\"hidden\" name=\"payer_data\" value=\""		. $this->get_xml_data()		. "\" />\n".
			( $this->get_charset()==null ? "" : "<input type=\"hidden\" name=\"payer_charset\" value=\"" . $this->get_charset() . "\" />\n" ).
			"<input type=\"hidden\" name=\"payer_checksum\" value=\""	. $this->get_checksum()		. "\" />\n";
	}

	/**
	 * This method will return your agentid (which is the identification id for your shop).
	 * It you want, you can use the generate_form() method instead and then you don't need to call this method. Otherwise you will need to put this in the hidden variable "payread_agentid".
	 * @return int agentid
	 */
	function get_agentid() {
		return $this->myAgentId;
	}

	/**
	 * This method will return which version of the POST API you are using.
	 * It you want, you can use the generate_form() method instead and then you don't need to call this method.	Otherwise you will need to put this in the hidden variable "payread_xml_writer".
	 * @return string api version
	 */
	function get_api_version() {
		return $this->do_encode($this->myPostApiVersion);
	}

	/**
	 * This method will return the xml in base64 format which needs to be posted to PAYER.
	 * It you want, you can use the generate_form() method instead and then you don't need to call this method.	Otherwise you will need to put this in the hidden variable "payread_data".
	 * @return string xml data
	 */
	function get_xml_data() {
		$this->generate_purchase_xml();
		$this->encrypt_data($this->myXmlData);
		return $this->myXmlData;
	}

	/**
	 * This method will return the checksum for the postdata. You need to post this checksum to PAYER.
	 * It you want, you can use the generate_form() method instead and then you don't need to call this method. Otherwise you will need to put this in the hidden variable "payread_checksum
	 * @return string Md5 checksum
	 */
	function get_checksum() {
		$this->myChecksum = $this->checksum_data();
		return $this->myChecksum;
	}

	/**
	 * This method will return the URL to the POST-API located on PAYERs server.
	 * @return string url to PAYER post-asp
	 * @access private
	 */
	function get_server_url() {
		return $this->myPayerServerUrl;
	}
	/**
	 * This method will return the URL to the POST-API located on PAYERs server.
	 * @return string url to PAYER post-asp
	 * @access private
	 */
	function set_server_url($url) {
		$this->myPayerServerUrl = $url;
	}

	/**
	 * This method will set which currency the transaction is in. Use 3 letters in uppercase.
	 * @param string $theCurrency 3 letter uppercase currency (ie "SEK") (required)
	 * @return  nothing
	 */
	function set_currency($theCurrency) {
		if (strlen($theCurrency) < 4) {
			$this->myCurrency = $theCurrency;
		}
	}

	/**
	 * This method will set a general description of the purchase, that will be used in
	 * various situations, e.g. in the Payer admin web, or communication with the
	 * buyer when its impossible to present the full specification.
	 * @param string $theDescription is one-line short description of the purchase. Notice that
	 * this description may be truncated depending on where it is presented, the maximum length
	 * that will be stored by Payer is 255 characters, but try to keep it below 32
	 * characters
	 * @return  nothing
	 */
	function set_description($theDescription) {
		$this->myDescription = $theDescription;
	}

	function set_hide_details($myHideDetails){
		$this->myHideDetails=$myHideDetails;
	}
	/**
	 * This method will set a reference Id for the purchase.
	 * @param string $theReferenceId is the reference Id. It is possible that this
	 * string might be presented to the buyer.
	 * @return  nothing
	 */
	function set_reference_id($theReferenceId) {
		$this->myReferenceId = $theReferenceId;
	}
	/**
	 * This method will set a message for the purchase.
	 * @param string $theMessage is the message. It is possible that this
	 * string might be presented to the buyer and the merchant.
	 * @return  nothing
	 */
	function set_message($theMessage) {
		$this->myMessage = $theMessage;
	}
	/**
	 * This method is not yet used
	 * @access private
	 * @return  nothing
	 */
	function add_catalog_purchase($theLineNumber, $theId, $theQuantity) {
		$this->myCatalogPurchases[] = array("LineNo" => $theLineNumber, "Id" => $theId, "Quantity" => $theQuantity);
	}

	/**
	 * This method must be called at least one call.
	 *
	 * This method will add a product, use this method multiple times per each product the buyer will pay for.
	 * @param string $theLineNumber order of the output lines of the products buyed, starting at 1. (required)
	 * @param string $theDescription decription of the product buyed. (required)
	 * @param int $thePrice price of the product buyed. (required)
	 * @param int $theVat vat of the product buyed. (required)
	 * @param int $theQuantity quantity of the product buyed. (required)
	 * @return  nothing
	 */
	function add_freeform_purchase($theLineNumber, $theDescription, $thePrice, $theVat, $theQuantity) {
		$this->myFreeformPurchases[] = array("LineNo" => $theLineNumber, "Description" => $theDescription, "Price" => $thePrice, "Vat" => $theVat, "Quantity" => $theQuantity);
	}

	/**
	 * This method must be called at least one call.
	 *
	 * This method will add a product, use this method multiple times per each product the buyer will pay for.
	 * extended version of above
	 * @return  nothing
	 */
	function add_freeform_purchase_ex($theLineNumber, $theDescription, $theItemNumber, $thePrice, $theVat, $theQuantity, $theUnit=null, $theAccount=null, $theDistAgentId=null) {
		$theArray = array();
		$theArray["LineNo"]=$theLineNumber;
		$theArray["Description"]=$theDescription;
		if ($theItemNumber != null) {
			$theArray["ItemNumber"]=$theItemNumber;
		}
		$theArray["Price"]=$thePrice;
		$theArray["Vat"]=$theVat;
		$theArray["Quantity"]=$theQuantity;
		if ($theUnit != null) {
			$theArray["Unit"]=$theUnit;
		}
		if ($theAccount != null) {
			$theArray["Account"]=$theAccount;
		}
		if ($theDistAgentId != null) {
			$theArray["AgentId"]=$theDistAgentId;
		}

		$this->myFreeformPurchases[] = $theArray;
	}
	function add_subscription_purchase($theLineNumber,$theDescription,$theItemNumber,$theInitialPrice,$theRecurringPrice,$theVat,$theQuantity,$theUnit,$theAccount,$theStartDate,$theStopDate,$theCount,$thePeriodicity,$theCancelDays) {
		$theArray = array();
		$theArray["LineNo"]=$theLineNumber;
		$theArray["Description"]=$theDescription;
		if ($theItemNumber != null) {
			$theArray["ItemNumber"]=$theItemNumber;
		}
		$theArray["Price"]=$theInitialPrice;
		$theArray["Vat"]=$theVat;
		$theArray["Quantity"]=$theQuantity;
		if ($theUnit != null) {
			$theArray["Unit"]=$theUnit;
		}
		$theArray["Account"]=$theAccount;

		$theArray["RecurringPrice"]=$theRecurringPrice;
		$theArray["StartDate"]=$theStartDate;
		$theArray["Count"]=$theCount;
		$theArray["Periodicity"]=$thePeriodicity;
		$theArray["StopDate"]=$theStopDate;
		$theArray["CancelDays"]=$theCancelDays;


		$this->mySubscriptionPurchases[] = $theArray;
	}

	/**
	 * set_fee
	 * This method is optional
	 *
	 * This method will add (and override) any fixed fees set from Payer website.
	 * @param string $theDescription Is the fee (or discount) description
	 * @param string $thePrice the fee or discount amount charged
	 * @param string $theItemNumber The ItemNumber that will show up in the item number column
	 * @param string $theVat The VAT percentage used to add VAT from the net price (25 std Seden)
	 * @param string $theQuantity The Quantity added - normally and default "1"
	 * @return  nothing
	 */
	function set_fee($theDescription, $thePrice, $theItemNumber="", $theVat=25, $theQuantity=1) {
		$this->add_freeform_purchase_ex(99999, $theDescription, $theItemNumber, $thePrice, $theVat, $theQuantity, $theUnit=null, $theAccount=null, $theDistAgentId=null);
	}


	/**
	 * This method is optional
	 *
	 * This method will add additional information static text that the buyer will see when he goes to PAYER website. Use this method multiple times per each information line.
	 * @param string $theLineNumber which product you want additional information for. (required)
	 * @param string $theText the additional decription of the product buyed. (required)
	 * @return  nothing
	 */
	function add_info_line($theLineNumber, $theText) {
		$this->myInfoLines[] = array("LineNo" => $theLineNumber, "Text" => $theText);
	}

	/**
	 * This method must be called.
	 *
	 * This method set the buyer information that will be posted to PAYER
	 * @param string $theFirstName buyers firstname (optional)
	 * @param string $theLastName buyers lastname (optional)
	 * @param string $theAddressLine1 buyers adressline1 (optional)
	 * @param string $theAddressLine2 buyers adressline1 (optional)
	 * @param string $thePostalcode buyers postalcode (optional)
	 * @param string $theCity buyers city (optional)
	 * @param string $theCountryCode buyers countrycode (optional)
	 * @param string $thePhoneHome buyers phonenumber home (optional)
	 * @param string $thePhoneWork buyers phonenumber work (optional)
	 * @param string $thePhoneMobile buyers phonenumber mobile (optional)
	 * @param string $theEmail buyers email (optional)
	 * @param string $theOrganisation name of the organisation (optional)
	 * @param string $theOrgNr organisation number or social security number (personummer) (optional)
	 * @param string $theCustomerId UserId at Payer (optional)
	 * @param string $theYourReference Contact person at organisation/company (optional)
	 * @param string $theOptions key1=value1,key2=value2 comma separated key->value pairs for special purposes (optional)
	 * @return  nothing
	 */
	function add_buyer_info($theFirstName, $theLastName, $theAddressLine1, $theAddressLine2, $thePostalcode, $theCity, $theCountryCode, $thePhoneHome, $thePhoneWork, $thePhoneMobile, $theEmail, $theOrganisation=null, $theOrgNr=null, $theCustomerId=null, $theYourReference=null, $theOptions=null) {
		$this->myBuyerInfo["FirstName"]    = $theFirstName;
		$this->myBuyerInfo["LastName"]     = $theLastName;
		$this->myBuyerInfo["AddressLine1"] = $theAddressLine1;
		$this->myBuyerInfo["AddressLine2"] = $theAddressLine2;
		$this->myBuyerInfo["Postalcode"]   = $thePostalcode;
		$this->myBuyerInfo["City"]         = $theCity;
		$this->myBuyerInfo["CountryCode"]  = $theCountryCode;
		$this->myBuyerInfo["PhoneHome"]    = $thePhoneHome;
		$this->myBuyerInfo["PhoneWork"]    = $thePhoneWork;
		$this->myBuyerInfo["PhoneMobile"]  = $thePhoneMobile;
		$this->myBuyerInfo["Email"]        = $theEmail;
		$this->myBuyerInfo["Organisation"] = $theOrganisation;
		$this->myBuyerInfo["OrgNr"]        = $theOrgNr;
		$this->myBuyerInfo["CustomerId"]   = $theCustomerId;
		$this->myBuyerInfo["YourReference"]= $theYourReference;
		$this->myBuyerInfo["Options"]      = $theOptions;
	}
	function set_options($theOptions) {
		$this->myBuyerInfo["Options"]=$theOptions;
	}

	/**
	 * This method must be called.
	 *
	 * This method will set the payment method the buyer can use to pay with.
	 * @param string $theMethod Can be set to sms, card, bank, phone, invoice & auto (required)
	 * @return  nothing
	 */
	function add_payment_method($theMethod) {
		$this->myPaymentMethods[] = $theMethod;
	}

	/**
	 * This method is optional
	*
	* If you want the recipt to be handled by your shop, this method will set the URL where the buyer will be redirected. If you don't use this method the buyer will get a recipt on PAYER server.
	* @param string $theUrl URL to your recipt if handled by shop.	(optional)
	* @return  nothing
	*/
	function set_success_redirect_url($theUrl) {
		$this->mySuccessRedirectUrl = $theUrl;
	}

	/**
	 * This method must be called.
	 *
	 * This method will set the URL where your Authorize webpage is located, remember that you will need to respond "TRUE" if everything is ok, or "FALSE" if something goes wrong, on your page.
	 * If "options" is set to "store=true" then this URL will be used to send back the uniqReferenceId
	 * @param string $theUrl URL to your authorize notification page. (required)
	 * @return  nothing
	 */
	function set_authorize_notification_url($theUrl) {
		$this->myAuthorizeNotificationUrl = $theUrl;
	}

	/**
	 * This method must be called.
	 *
	 * This method will set the URL where your Settle webpage is located, remember that you will need to respond "TRUE" if everything is ok, or "FALSE" if something goes wrong, on your page.
	 * @param string $theUrl URL to your settle notification page. (required)
	 * @return  nothing
	 */
	function set_settle_notification_url($theUrl) {
		$this->mySettleNotificationUrl = $theUrl;
	}

	/**
	 * This method must be called.
	 *
	 * This method will set the URL where your frontpage of the shop is located.
	 * @param string $theUrl URL to your frontpage of the shop. (required)
	 * @return  nothing
	 */
	function set_redirect_back_to_shop_url($theUrl) {
		$this->myRedirectBackToShopUrl = $theUrl;
	}

	/**
	 * This method is optional, default value is "silent"
	 *
	 * This method will set the debug mode, if set to verbose you will be able to see the parameters posted at the page where you enter bankcard information
	 * @param string $theDebugMode debug mode, set as "silent"/"brief"/"verbose" (required)
	 * @return  nothing
	 */
	function set_debug_mode($theDebugMode) {
		if (in_array(strtolower($theDebugMode), array("silent","brief","verbose"))) {
			$this->myDebugMode = $theDebugMode;
		}
	}

	/**
	 * This method is optional, default value is true
	 *
	 * This method will set the testmode, if set to true, PAYER will not contact the bank and no money will be taken from the bank account connected to the bankcard, otherwise everything will act like a real transaction.
	 * @param boolean $theDebugMode test mode, set as true/false (required)
	 * @return  nothing
	 */
	function set_test_mode($theTestMode) {
		$lc = strtolower($theTestMode);
		$this->myTestMode = (($theTestMode===true || $lc=="true") ? "true" : "false");
	}

	/**
	 * This method is optional, default value is "sv"
	 *
	 * This method will set which language the buyer will see when he enters bankcard information. The input should be in lowercase and you should enter language code (2 letters) not countrycode ie "sv" not "se".
	 * @param string $theLanguageCode 2 letter uppercase language (ie "sv") (required)
	 * @return  nothing
	 */
	function set_language($theLanguage) {
		if (strlen($theLanguage)==2) {
			$this->myLanguage = $theLanguage;
		}
	}

	function get_request_url(){
		if (empty($_SERVER["REQUEST_URI"])) {
			return ($_SERVER["SERVER_PORT"]=="80" ? "http://" : "https://").$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"];
		}
		return ($_SERVER["SERVER_PORT"]=="80" ? "http://" : "https://").$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
	}

	/**
	 * This method will validate that the callback orginates from PAYERs server. This method should be called from your authorize and settle pages.
	 * @param string $theUrl URL to be validated (required)
	 * @return boolean true/false
	 */
	function validate_callback_url($theUrl) {
		// strip the &md5sum from url
		$pos=strpos($theUrl, "&md5sum");
		if($pos===false) {
			// this case handles opencart and other manipulating $_SERVER vars
			$theUrl=htmlspecialchars_decode($theUrl);
			$strippedUrl = substr($theUrl, 0, strpos($theUrl, "&md5sum"));
		} else {
			$strippedUrl = substr($theUrl, 0, $pos);
		}
		// add the Key1 and Key2 from the stripped url and calculate checksum
		$keyA=$this->myKeys["A"];
		$keyB=$this->myKeys["B"];

		$md5 = strtolower(md5($keyA.$strippedUrl.$keyB));

		// do we find the calculated checksum in in the original URL somewhere ?
		if (strpos(strtolower($theUrl), $md5)>=7) {
			return true; // yes - this is authentic
		}
		return false; // no - this is not a properly signed URL
	}

	/**
	 * This method is a VERY SIMPLE firewall on application level
	 *
	 * This method will validate that the callback orginates from PAYERs server. This method should be called from your authorize and settle pages.
	 * @return boolean true/false
	 */
	function is_valid_ip() {
		$ip=$_SERVER["REMOTE_ADDR"];
		return in_array($ip, $this->myFirewall);
	}
	
	function add_valid_ip($ip) {
		$this->myFirewall[]=$ip;
	}	
	
	function is_valid_callback() {
		return $this->validate_callback_url($this->get_request_url());
	}

	/**
	 * This method will "encrypt" the data using base64
	 * @access private
	 */
	function encrypt_data($theData, $theEncryptionMethod = "base64") {
		switch(strtolower($theEncryptionMethod)) {
			case "base64":
				$this->myXmlData = base64_encode($this->myXmlData);
				break;
		}
	}

	/**
	 * This method will set the checksum
	 * @access private
	 */
	function checksum_data($theAuthMethod = "md5") {
		switch(strtolower($theAuthMethod)) {
			case "md5" :
				return md5($this->myKeys["A"] . $this->myXmlData . $this->myKeys["B"]);
				break;

			case "sha1" :
				return sha1($this->myKeys["A"] . $this->myXmlData . $this->myKeys["B"]);
				break;
		}
	}

	/**
	 * This method will generate the xml data that you need to post p the Post-API.
	 * @access private
	 */
	function generate_purchase_xml() {
		// Header
		$charset = $this->myCharSet == null ? "iso-8859-1" : $this->myCharSet ;
		
		$this->myXmlData  = "<?xml version=\"1.0\" encoding=\"$charset\"?>\n";
		$this->myXmlData .= "<payread_post_api_0_2 ".
								"xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" ".
								"xsi:noNamespaceSchemaLocation=\"payread_post_api_0_2.xsd\"".
								">\n";

		// Seller details
		$this->myXmlData .= "<seller_details>\n" .
								" <agent_id>".$this->do_encode($this->myAgentId)."</agent_id>\n";

		if ($this->myClientVersion!=null) {
			$this->myXmlData .= " <client_version>".$this->do_encode($this->myClientVersion)."</client_version>\n";
		}

		$this->myXmlData .=	"</seller_details>\n";

		// Buyer details
		$this->myXmlData .= "<buyer_details>\n" .
								" <first_name>"		. $this->do_encode($this->myBuyerInfo["FirstName"])		. "</first_name>\n" .
								" <last_name>"		. $this->do_encode($this->myBuyerInfo["LastName"])		. "</last_name>\n" .
								" <address_line_1>"	. $this->do_encode($this->myBuyerInfo["AddressLine1"])	. "</address_line_1>\n" .
								" <address_line_2>"	. $this->do_encode($this->myBuyerInfo["AddressLine2"])	. "</address_line_2>\n" .
								" <postal_code>"	. $this->do_encode($this->myBuyerInfo["Postalcode"])	. "</postal_code>\n" .
								" <city>"			. $this->do_encode($this->myBuyerInfo["City"])			. "</city>\n" .
								" <country_code>"	. $this->do_encode($this->myBuyerInfo["CountryCode"])	. "</country_code>\n" .
								" <phone_home>"		. $this->do_encode($this->myBuyerInfo["PhoneHome"])		. "</phone_home>\n" .
								" <phone_work>"		. $this->do_encode($this->myBuyerInfo["PhoneWork"])		. "</phone_work>\n" .
								" <phone_mobile>"	. $this->do_encode($this->myBuyerInfo["PhoneMobile"])	. "</phone_mobile>\n" .
								" <email>"			. $this->do_encode($this->myBuyerInfo["Email"])			. "</email>\n" .
								" <organisation>"	. $this->do_encode($this->myBuyerInfo["Organisation"])	. "</organisation>\n".
								" <orgnr>"			. $this->do_encode($this->myBuyerInfo["OrgNr"])			. "</orgnr>\n".
								" <customer_id>"	. $this->do_encode($this->myBuyerInfo["CustomerId"])	. "</customer_id>\n".
		( !empty($this->myBuyerInfo["YourReference"]) ?
								" <your_reference>"	. $this->do_encode($this->myBuyerInfo["YourReference"])	. "</your_reference>\n" : "").
		( !empty($this->myBuyerInfo["Options"]) ?
								" <options>"	. $this->do_encode($this->myBuyerInfo["Options"])	. "</options>\n" : "").
		"</buyer_details>\n";
		$this->myXmlData .= "<purchase>\n";
		// Purchase
		$this->myXmlData .= "<currency>" . $this->myCurrency . "</currency>\n";
		// Add RefId if used
		if (!empty($this->myReferenceId)) {
			$this->myXmlData .= "<reference_id>" . $this->do_encode($this->myReferenceId)		. "</reference_id>\n";
		}
		// Add Descr if used
		if (!empty($this->myDescription)) {
			$this->myXmlData .= "<description>" . $this->do_encode($this->myDescription)		. "</description>\n";
		}
		// Add Message if used
		if (!empty($this->myMessage)) {
			$this->myXmlData .= "<message>" . $this->do_encode($this->myMessage)		. "</message>\n";
		}
		// Add myHideDetails
		if (!empty($this->myHideDetails)) {
			$this->myXmlData .= "<hide_details>" . ($this->myHideDetails ? "true" : "false" ) . "</hide_details>\n";
		}
		// Start the Purchase list
		$this->myXmlData .=	"<purchase_list>\n";

		// Purchase list (catalog purchases)
		@reset($this->myCatalogPurchases);
		while( list(, $thePurchase) = @each($this->myCatalogPurchases) ) {
			$this->myXmlData .= "<catalog_purchase>" .
									"<line_number>"	.  $this->do_encode($thePurchase["LineNo"])		. "</line_number>" .
									"<id>"			.  $this->do_encode($thePurchase["Id"])			. "</id>" .
									"<quantity>"	.  $this->do_encode($thePurchase["Quantity"])	. "</quantity>" .
									"</catalog_purchase>\n";
		}

		// Purchase list (freeform purchases)
		@reset($this->myFreeformPurchases);
		while( list(, $thePurchase) = @each($this->myFreeformPurchases) ) {
			$this->myXmlData .= "<freeform_purchase>" .
									" <line_number>"	.  $this->do_encode($thePurchase["LineNo"])			. "</line_number>\n" .
									" <description>"	.  $this->do_encode($thePurchase["Description"])	. "</description>\n" .
			( !empty($thePurchase["ItemNumber"]) ?
										 " <item_number>" . $this->do_encode($thePurchase["ItemNumber"]) . "</item_number>\n" : "") .
									" <price_including_vat>"	.  $this->do_encode($thePurchase["Price"])	. "</price_including_vat>\n" .
									" <vat_percentage>"		.  $this->do_encode($thePurchase["Vat"])	. "</vat_percentage>\n" .
									" <quantity>"	.  $this->do_encode($thePurchase["Quantity"])		. "</quantity>\n"  .
			( !empty($thePurchase["Unit"]) ?
										 " <unit>" . $this->do_encode($thePurchase["Unit"]) . "</unit>\n" : "") .
			( !empty($thePurchase["Account"]) ?
										 " <account>" . $this->do_encode($thePurchase["Account"]) . "</account>\n" : "") .
			( !empty($thePurchase["AgentId"]) ?
										 " <agent_id>" . $this->do_encode($thePurchase["AgentId"]) . "</agent_id>\n" : "") .
									"</freeform_purchase>\n";
		}

		foreach($this->mySubscriptionPurchases AS $thePurchase) {
			$this->myXmlData .=
					" <subscription_purchase>\n" .
					"  <line_number>"	.  $this->do_encode($thePurchase["LineNo"])			. "</line_number>\n" .
					"  <description>"	.  $this->do_encode($thePurchase["Description"])	. "</description>\n" .
					"  <price_including_vat>"	.  $this->do_encode($thePurchase["Price"])	. "</price_including_vat>\n" .
					"  <vat_percentage>"		.  $this->do_encode($thePurchase["Vat"])	. "</vat_percentage>\n" .
					"  <quantity>"	.  $this->do_encode($thePurchase["Quantity"])		. "</quantity>\n"  .
			( !empty($thePurchase["ItemNumber"]) ?
					"  <item_number>" . $this->do_encode($thePurchase["ItemNumber"]) . "</item_number>\n" : "") .
			( !empty($thePurchase["Unit"]) ?
					"  <unit>".$this->do_encode($thePurchase["Unit"])."</unit>\n" : "") .
			( !empty($thePurchase["Account"]) ?
					"  <account>" . $this->do_encode($thePurchase["Account"]) . "</account>\n" : "") .
					"  <recurring_price_including_vat>".$this->do_encode($thePurchase["RecurringPrice"])."</recurring_price_including_vat>\n".
					"  <start_date>".$this->do_encode($thePurchase["StartDate"])."</start_date>\n".
					"  <stop_date>".$this->do_encode($thePurchase["StopDate"])."</stop_date>\n".
					"  <count>".$this->do_encode($thePurchase["Count"])."</count>\n".
					"  <periodicity>".$this->do_encode($thePurchase["Periodicity"])."</periodicity>\n".
					"  <cancel_days>".$this->do_encode($thePurchase["CancelDays"])."</cancel_days>\n".
					" </subscription_purchase>\n";
		}

		// Purchase list (info lines)
		@reset($this->myInfoLines);
		while( list(, $theValues) = @each($this->myInfoLines) ) {
			$this->myXmlData .= "<info_line>" .
									"<line_number>"	.  $this->do_encode($theValues["LineNo"])	. "</line_number>" .
									"<text>"		.  $this->do_encode($theValues["Text"])		. "</text>" .
									"</info_line>\n";
		}

		$this->myXmlData .= "</purchase_list>\n" .
								"</purchase>\n";


		//Processing control
		$this->myXmlData .=
				"<processing_control>\n" ;
		if (!empty($this->mySuccessRedirectUrl))
		$this->myXmlData .=	"<success_redirect_url>"	. $this->do_encode($this->mySuccessRedirectUrl)	. "</success_redirect_url>\n";
		$this->myXmlData .=
				" <authorize_notification_url>"	.  $this->do_encode($this->myAuthorizeNotificationUrl)	. "</authorize_notification_url>\n" .
				" <settle_notification_url>"		.  $this->do_encode($this->mySettleNotificationUrl)	. "</settle_notification_url>\n" .
				" <redirect_back_to_shop_url>" 	.  $this->do_encode($this->myRedirectBackToShopUrl) . "</redirect_back_to_shop_url>\n" .
				"</processing_control>\n";

		// Database overrides
		$this->myXmlData .= "<database_overrides>\n";

		// Payment methods
		$this->myXmlData .= "<accepted_payment_methods>\n";
		@reset($this->myPaymentMethods);
		while( list(, $thePaymentMethod) = @each($this->myPaymentMethods) ) {
			$this->myXmlData .= "<payment_method>"		. $thePaymentMethod		. "</payment_method>\n";
		}
		$this->myXmlData .= "</accepted_payment_methods>\n";

		// Debug mode
		$this->myXmlData .= "<debug_mode>"		. $this->myDebugMode	. "</debug_mode>\n";

		// Test mode
		$this->myXmlData .= "<test_mode>"		. $this->myTestMode		. "</test_mode>\n";

		// Language
		$this->myXmlData .= "<language>"		. $this->myLanguage		. "</language>\n";

		$this->myXmlData .= "</database_overrides>\n";

		// Footer
		$this->myXmlData .= "</payread_post_api_0_2>\n";
	}
	function getChallangeResponse($challange) {
		return md5($this->getKeyA()."$challange");
	}
	function do_encode($data) {
		return htmlspecialchars($data);
	}
}
?>
