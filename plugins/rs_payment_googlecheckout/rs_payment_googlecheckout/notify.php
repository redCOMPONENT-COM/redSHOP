<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

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

// load system plugin group
JPluginHelper::importPlugin ( 'system' );

// trigger the onBeforeStart events
$mainframe->triggerEvent ( 'onBeforeStart' );
$lang = & JFactory::getLanguage ();
$mosConfig_lang = $GLOBALS ['mosConfig_lang'] = strtolower ( $lang->getBackwardLang () );
// Adjust the live site path


/*** END of Joomla config ***/

// redshop language file
JPlugin::loadLanguage ( 'com_redshop' );

require_once ( JPATH_BASE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'order.php');
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'redshop.cfg.php');
$objOrder = new order_functions();

 chdir("..");
  require_once('library/googleresponse.php');
  require_once('library/googlemerchantcalculations.php');
  require_once('library/googlerequest.php');
  require_once('library/googlenotificationhistory.php');

  //Definitions
$gcplugin = JPluginHelper::getPlugin('redshop_payment','rs_payment_googlecheckout');
$params = $gcplugin->params;
$param = new JParameter($params);
$server_type = $param->get('is_test','sandbox');
$merchant_id = $param->get('merchant_id');
$merchant_key = $param->get('merchant_key');
$currency = 'USD';  // set to GBP if in the UK


  //Create the response object
  $Gresponse = new GoogleResponse($merchant_id, $merchant_key);

  //Retrieve the XML sent in the HTTP POST request to the ResponseHandler
  $xml_response = isset($HTTP_RAW_POST_DATA)?
                    $HTTP_RAW_POST_DATA:file_get_contents("php://input");

  //If serial-number-notification pull serial number and request xml
  if(strpos($xml_response, "xml") == FALSE){
    //Find serial-number ack notification
    $serial_array = array();
    parse_str($xml_response, $serial_array);
    $serial_number = $serial_array["serial-number"];

    //Request XML notification
    $Grequest = new GoogleNotificationHistoryRequest($merchant_id, $merchant_key, $server_type);
    $raw_xml_array = $Grequest->SendNotificationHistoryRequest($serial_number);
    if ($raw_xml_array[0] != 200){
      //Add code here to retry with exponential backoff
    } else {
      $raw_xml = $raw_xml_array[1];
    }
    $Gresponse->SendAck($serial_number, false);
  }
  else{
    //Else assume pre 2.5 XML notification
    //Check Basic Authentication
    $Gresponse->SetMerchantAuthentication($merchant_id, $merchant_key);
    /*$status = $Gresponse->HttpAuthentication();
    if(! $status) {
      die('authentication failed');
    }*/
    $raw_xml = $xml_response;
    $Gresponse->SendAck(null, false);
  }

  if (get_magic_quotes_gpc()) {
    $raw_xml = stripslashes($raw_xml);
  }
mail("gunjan@redweb.dk","googlecheckout responce",$raw_xml);
  //Parse XML to array
  list($root, $data) = $Gresponse->GetParsedXML($raw_xml);

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