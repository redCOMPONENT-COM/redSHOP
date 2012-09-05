<?php

$my_path = dirname ( __FILE__ );

if (file_exists ( $my_path . "/../../../configuration.php" )) {
	$absolute_path = dirname ( $my_path . "/../../../configuration.php" );
	require_once ($my_path . "/../../../configuration.php");
} elseif (file_exists ( $my_path . "/../../configuration.php" )) {
	$absolute_path = dirname ( $my_path . "/../../configuration.php" );
	require_once ($my_path . "/../../configuration.php");
} elseif (file_exists ( $my_path . "/configuration.php" )) {
	$absolute_path = dirname ( $my_path . "/configuration.php" );
	require_once ($my_path . "/configuration.php");
} else {
	die ( "Joomla Configuration File not found!" );
}

$absolute_path = realpath ( $absolute_path );

define ( '_JEXEC', 1 );
define ( 'JPATH_BASE', $absolute_path );
define ( 'DS', DIRECTORY_SEPARATOR );
define ( 'JPATH_COMPONENT_ADMINISTRATOR', JPATH_BASE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' );
define ( 'JPATH_COMPONENT', JPATH_BASE . DS . 'components' . DS . 'com_redshop' );

// Load the framework

require_once ($absolute_path . DS . 'includes' . DS . 'defines.php');
require_once ($absolute_path . DS . 'includes' . DS . 'framework.php');

// create the mainframe object
$mainframe = & JFactory::getApplication ( 'site' );

// Initialize the framework
$mainframe->initialise ();

require_once ( JPATH_BASE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'order.php');
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'redshop.cfg.php');
$objOrder = new order_functions();
require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'shipping.php');
$shippingObject = new shipping();

require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'configuration.php');
$Redconfiguration = new Redconfiguration();
$Redconfiguration->defineDynamicVars();

$gcplugin = JPluginHelper::getPlugin('redshop_payment','rs_payment_googlecheckout');
$params = $gcplugin->params;
$param = new JParameter($params);
$server_type = $param->get('is_test','sandbox');
$merchant_id = $param->get('merchant_id');
$merchant_key = $param->get('merchant_key');

  chdir("..");
  require_once('library/googleresponse.php');
  require_once('library/googlemerchantcalculations.php');
  require_once('library/googleresult.php');
  require_once('library/googlerequest.php');

  /*$merchant_id = "904328961520536";  // Your Merchant ID
  $merchant_key = "NF9m8HWKoTQwfB0XDEbtUg";  // Your Merchant Key
  $server_type = "sandbox";  // change this to go live*/
  $currency = 'USD';  // set to GBP if in the UK

  $Gresponse = new GoogleResponse($merchant_id, $merchant_key);

  $Grequest = new GoogleRequest($merchant_id, $merchant_key, $server_type, $currency);

  // Retrieve the XML sent in the HTTP POST request to the ResponseHandler
  $xml_response = isset($HTTP_RAW_POST_DATA)?
                    $HTTP_RAW_POST_DATA:file_get_contents("php://input");
  if (get_magic_quotes_gpc()) {
    $xml_response = stripslashes($xml_response);
  }

  list($root, $data) = $Gresponse->GetParsedXML($xml_response);

  $Gresponse->SetMerchantAuthentication($merchant_id, $merchant_key);

	$gnum = "";
  if(isset($data[$root]['google-order-number'])){
		$gnum = $data[$root]['google-order-number']['VALUE'];
	}

  /* Commands to send the various order processing APIs
   * Send charge order : $Grequest->SendChargeOrder($data[$root]
   *    ['google-order-number']['VALUE'], <amount>);
   * Send process order : $Grequest->SendProcessOrder($data[$root]
   *    ['google-order-number']['VALUE']);
   * Send deliver order: $Grequest->SendDeliverOrder($data[$root]
   *    ['google-order-number']['VALUE'], <carrier>, <tracking-number>,
   *    <send_mail>);
   * Send archive order: $Grequest->SendArchiveOrder($data[$root]
   *    ['google-order-number']['VALUE']);
   *
   */
  mail("gunjan@redweb.dk","basic responce".$root,$root);
  switch($root){
    case "new-order-notification": {
      break;
    }
    case "risk-information-notification": {
      break;
    }
    case "charge-amount-notification": {
      break;
    }
    case "authorization-amount-notification": {
      $google_order_number = $data[$root]['google-order-number']['VALUE'];
      $tracking_data = array("Z12345" => "UPS", "Y12345" => "Fedex");
      $GChargeRequest = new GoogleRequest($merchant_id, $merchant_key, $server_type);
      $GChargeRequest->SendChargeAndShipOrder($google_order_number, $tracking_data);
      break;
    }
    case "refund-amount-notification": {
      break;
    }
    case "chargeback-amount-notification": {
      break;
    }
    case "order-numbers": {
      break;
    }
    case "invalid-order-numbers": {
      break;
    }
    case "order-state-cahnge-notification": {
      break;
    }
    default: {
      break;
    }
  }

  /* In case the XML API contains multiple open tags
     with the same value, then invoke this function and
     perform a foreach on the resultant array.
     This takes care of cases when there is only one unique tag
     or multiple tags.
     Examples of this are "anonymous-address", "merchant-code-string"
     from the merchant-calculations-callback API
  */
  function get_arr_result($child_node) {
    $result = array();
    if(isset($child_node)) {
      if(is_associative_array($child_node)) {
        $result[] = $child_node;
      }
      else {
        foreach($child_node as $curr_node){
          $result[] = $curr_node;
        }
      }
    }
    return $result;
  }

  /* Returns true if a given variable represents an associative array */
  function is_associative_array( $var ) {
    return is_array( $var ) && !is_numeric( implode( '', array_keys( $var ) ) );
  }
?>
