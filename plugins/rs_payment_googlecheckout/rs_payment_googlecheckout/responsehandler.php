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


  /*$status = $Gresponse->HttpAuthentication();
  if(! $status) {
    die('authentication failed');
  }*/

	$google_order_id = (isset($data[$root]['google-order-number'])) ? $data[$root]['google-order-number']['VALUE'] : 0;
	$orders_payment_status_id = "";
	$log = "";

	mail("gunjan@redweb.dk","googlecheckout ".$root." ".$google_order_id,$xml_response);

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
  switch ($root) {
    case "request-received": {
      break;
    }
    case "error": {
      break;
    }
    case "diagnosis": {
      break;
    }
    case "checkout-redirect": {
      break;
    }
    case "merchant-calculation-callback": {
		//echo "<pre />";
		//print_r($data[$root]);
      // Create the results and send it
      $merchant_calc = new GoogleMerchantCalculations($currency);
		//print_r($merchant_calc);
      // Loop through the list of address ids from the callback
      $addresses = get_arr_result($data[$root]['calculate']['addresses']['anonymous-address']);
      foreach($addresses as $curr_address) {

      	$sent_shipp_info = array();
      	$sent_shipp_info['order_subtotal'] = $data[$root]['shopping-cart']['merchant-private-data']['subtotal']['VALUE'];
      	$sent_shipp_info['is_company'] = 0;
        $sent_shipp_info['currency'] = $curr_id = $curr_address['id'];
        $country = $curr_address['country-code']['VALUE'];
        $sent_shipp_info['country_code'] = $Redconfiguration->getCountryCode2($country,true);
        $sent_shipp_info['city'] = $city = $curr_address['city']['VALUE'];
        $sent_shipp_info['state_code'] = $region = $curr_address['region']['VALUE'];
        $sent_shipp_info['zipcode'] = $postal_code = $curr_address['postal-code']['VALUE'];

        // Loop through each shipping method if merchant-calculated shipping
        // support is to be provided
        if(isset($data[$root]['calculate']['shipping'])) {
          $shipping = get_arr_result($data[$root]['calculate']['shipping']['method']);
          foreach($shipping as $curr_ship) {

          	$merchant_result = new GoogleResult($curr_id);

          	# set true when shipping is free
			$isShippFree = false;
          	if(isset($data[$root]['calculate']['merchant-code-strings']
                ['merchant-code-string'])) {
              $codes = get_arr_result($data[$root]['calculate']['merchant-code-strings']
                  ['merchant-code-string']);
              foreach($codes as $curr_code) {

              	# calculate merchant coupon calculation
				$merchantCouponData = array();
				$merchantCouponData['coupon'] = $curr_code;
				$merchantCouponData['subtotal'] = $data[$root]['shopping-cart']['merchant-private-data']['subtotal']['VALUE'];
              	$couponRate = $objOrder->calculateGoogleCoupon($merchantCouponData);

              	if($isShippFree == false && $couponRate->free_shipping == 1) $isShippFree = true;

              	# End
                //Update this data as required to set whether the coupon is valid, the code and the amount
                $coupons = new GoogleGiftcerts($couponRate->isvalid, $curr_code['code'], $couponRate->rate,$couponRate->message);
                $merchant_result->AddGiftCertificates($coupons);
              }
             }

          	/**
          	 * Merchant calculated shipping rate
          	 */

          	$shipping_rates = $shippingObject->listshippingrates('default_shipping',0,$sent_shipp_info);

          	if(count($shipping_rates) > 0){
		        $shipping_rate_name = $shipping_rates[0]->shipping_rate_name;
		        $shipping_rate_value = $shipping_rates[0]->shipping_rate_value;

	            //Compute the price for this shipping method and address id
	            $price = ($isShippFree) ? 0 : $shipping_rate_value; // Modify this to get the actual price
	            $shippable = "true"; // Modify this as required
          	}else{

	            //Compute the price for this shipping method and address id
	            $price = ($isShippFree) ? 0 : 1; // Modify this to get the actual price
	            $shippable = "false"; // Modify this as required
          	}

          	$name = $curr_ship['name'];
            $merchant_result->SetShippingDetails($name, $price, $shippable);
            # End shipping rate

            # collect shopping cart information
         	$shopping_cart = $data[$root]['shopping-cart'];
            $items = $shopping_cart['items']['item'];
            $merchantTaxdata = array();

            $merchantTaxdata = $sent_shipp_info;
            if(isset($items['item-name'])){

            	$merchantTaxdata['product'][0]->price = $items['unit-price']['VALUE'];

				# merchant-private-item-data
				$merchant_private_item_data = $items['merchant-private-item-data']['VALUE'];

				$private_item_data = explode(";",$merchant_private_item_data);
				# product_number
				$product_id = str_replace("productid=","",$private_item_data[0]);
				$merchantTaxdata['product'][0]->product_id = $product_id;
            }else{
	            for($it=0;$it<count($items);$it++){

		    		$item = $items[$it];

		    		$merchantTaxdata['product'][$it]->price = $item['unit-price']['VALUE'];

					# merchant-private-item-data
					$merchant_private_item_data = $item['merchant-private-item-data']['VALUE'];

					$private_item_data = explode(";",$merchant_private_item_data);

					# product_number
					$product_id = str_replace("productid=","",$private_item_data[0]);
					$merchantTaxdata['product'][$it]->product_id = $product_id;
	            }
            }
            # End collect shopping cart info

            # vat calculation start
            if($data[$root]['calculate']['tax']['VALUE'] == "true") {

            	# order helper function to calculate TAX for google checkout
            	$taxamount = $objOrder->calculateGoogleTax($merchantTaxdata);
            	# End
              //Compute tax for this address id and shipping type
              $amount = $taxamount; // Modify this to the actual tax value
              $merchant_result->SetTaxDetails($amount);
            }
            # End

           	$merchant_calc->AddResult($merchant_result);
          }
        } else {
          $merchant_result = new GoogleResult($curr_id);
          if($data[$root]['calculate']['tax']['VALUE'] == "true") {
            //Compute tax for this address id and shipping type
            $amount = 15; // Modify this to the actual tax value
            $merchant_result->SetTaxDetails($amount);
          }
          $codes = get_arr_result($data[$root]['calculate']['merchant-code-strings']
              ['merchant-code-string']);
          foreach($codes as $curr_code) {
            //Update this data as required to set whether the coupon is valid, the code and the amount
            $coupons = new GoogleGiftcerts("true", $curr_code['code'], 10, "debugtest");
            $merchant_result->AddGiftCertificates($coupons);
          }
          $merchant_calc->AddResult($merchant_result);
        }
      }
      $Gresponse->ProcessMerchantCalculations($merchant_calc);
      break;
    }
    case "new-order-notification": {

    	//echo "<pre/>";
		//print_r($data);
    	# user billing address information
    	$buyer_billing_address = $data[$root]['buyer-billing-address'];

    	$orderInfo = new stdClass();

    	$billing_user = array();
    	$billing_user['email'] = $buyer_billing_address['email']['VALUE'];
    	$billing_user['name'] = $buyer_billing_address['contact-name']['VALUE'];
    	$billing_user['company_name'] = $buyer_billing_address['company-name']['VALUE'];
    	$billing_user['phone'] = $buyer_billing_address['phone']['VALUE'];
    	$billing_user['fax'] = $buyer_billing_address['fax']['VALUE'];
    	$billing_user['address'] = $buyer_billing_address['address1']['VALUE'];
    	$billing_user['address2'] = $buyer_billing_address['address2']['VALUE'];
    	$billing_user['city'] = $buyer_billing_address['city']['VALUE'];
    	$billing_user['state'] = $buyer_billing_address['region']['VALUE'];
    	$billing_user['zipcode'] = $buyer_billing_address['postal-code']['VALUE'];
    	$billing_user['country'] = $buyer_billing_address['country-code']['VALUE'];

    	$orderInfo->billinginfo = $billing_user;
    	# End

    	# time of order placed
    	$orderInfo->timestamp = $data[$root]['timestamp']['VALUE'];

    	# google order number = redSHOP payment transation id
    	$orderInfo->transaction_id = $google_order_id;

    	# google shopping-cart
    	$shopping_cart = $data[$root]['shopping-cart'];

    	$items = $shopping_cart['items']['item'];

    	$cartitems = array();
    	if(isset($items['item-name'])){

			$cartitems[0] = new stdClass();
			$cartitems[0]->product_name = $items['item-name']['VALUE'];
			$cartitems[0]->product_desc = $items['item-description']['VALUE'];
			$cartitems[0]->product_price = $items['unit-price']['VALUE'];
			$cartitems[0]->product_currency = $items['unit-price']['currency'];
			$cartitems[0]->quantity = $items['quantity']['VALUE'];

			# merchant-private-item-data
			$merchant_private_item_data = $items['merchant-private-item-data']['VALUE'];

			$private_item_data = explode(";",$merchant_private_item_data);
			# product_number
			$cartitems[0]->product_id = str_replace("productid=","",$private_item_data[0]);

			# Attribute start
			$attributes = str_replace("attribute=","",$private_item_data[1]);

			$attArray = array();
			if(trim($attributes) != ""){

				$attribute = explode("-",$attributes);
				for($i=0;$i<count($attribute);$i++){
					$attribute_data = $attribute[$i];
					$att = explode(":",$attribute_data);

					$attArray[$i]->attribute_id = $att[0];
					$attArray[$i]->property_id = $att[1];
					$attArray[$i]->subproperty_id = isset($att[2]) ? $att[2] : 0;
				}
			}

			if(count($attArray)>0) $cartitems[0]->attribute = $attArray;
			# End

			# accessory start
			$accessory = str_replace("accessory=","",$private_item_data[2]);
			if(trim($accessory) != "")
				$cartitems[0]->accessory = explode(",",$accessory);
			# End
			# End

    	}else{
	    	for($it=0;$it<count($items);$it++){

	    		$item = $items[$it];

				$cartitems[$it] = new stdClass();
				$cartitems[$it]->product_name = $item['item-name']['VALUE'];
				$cartitems[$it]->product_desc = $item['item-description']['VALUE'];
				$cartitems[$it]->product_price = $item['unit-price']['VALUE'];
				$cartitems[$it]->product_currency = $item['unit-price']['currency'];
				$cartitems[$it]->quantity = $item['quantity']['currency'];

				# merchant-private-item-data
				$merchant_private_item_data = $item['merchant-private-item-data']['VALUE'];

				$private_item_data = explode(";",$merchant_private_item_data);

				# product_number
				$cartitems[0]->product_id = str_replace("productid=","",$private_item_data[0]);

				# Attribute start
				$attributes = str_replace("attribute=","",$private_item_data[1]);

				$attArray = array();
				if(trim($attributes) != ""){

					$attribute = explode("-",$attributes);
					for($i=0;$i<count($attribute);$i++){
						$attribute_data = $attribute[$i];
						$att = explode(":",$attribute_data);

						$attArray[$i]->attribute_id = $att[0];
						$attArray[$i]->property_id = $att[1];
						$attArray[$i]->subproperty_id = isset($att[2]) ? $att[2] : 0;
					}
				}

				if(count($attArray)>0) $cartitems[$it]->attribute = $attArray;
				# End

				# accessory start
				$accessory = str_replace("accessory=","",$private_item_data[2]);
				if(trim($accessory) != "")
					$cartitems[$it]->accessory = explode(",",$accessory);
				# End

				# End
	    	}
    	}

    	$orderInfo->cartitems = $cartitems;
		$orderInfo->merchant_private_data = $shopping_cart['merchant-private-data']['VALUE'];
		# End

		# order-adjustment
		$order_adjustment = $data[$root]['order-adjustment'];

		$orderInfo->merchant_calculation_successful = $order_adjustment['merchant-calculation-successful']['VALUE'];

		# merchant codes calculation like Coupon codes etc
		if(isset($order_adjustment['merchant-codes']['gift-certificate-adjustment'])){

			$gift_certificate_adjustments = $order_adjustment['merchant-codes']['gift-certificate-adjustment'];

			if(isset($gift_certificate_adjustments['code'])){
				$codes[0] = $gift_certificate_adjustments['code']['VALUE'];
			}else{

				for($c=0;$c<count($gift_certificate_adjustments);$c++){

					$gift_certificate_adjustment = $gift_certificate_adjustments[$c];
					$codes[] = $gift_certificate_adjustment['code']['VALUE'];
				}

			}
			$orderInfo->merchant_codes = $codes;
		}
		# End coupon codes

		# shipping rates -> merchant calculated
		$shipping = $order_adjustment['shipping']['merchant-calculated-shipping-adjustment'];
		$shippingrate = array();
		$shippingrate['shipping_name'] = $shipping['shipping-name']['VALUE'];
		$shippingrate['shipping_rate'] = $shipping['shipping-cost']['VALUE'];
		$shippingrate['shipping_rate_currency'] = $shipping['shipping-cost']['currency'];

		$orderInfo->shipping = $shippingrate;
		# End

		# total-tax
		$orderInfo->tax['rate'] = $order_adjustment['total-tax']['VALUE'];
		$orderInfo->tax['currency'] = $order_adjustment['total-tax']['currency'];
		# End

		# adjustment-total
		$orderInfo->adjustment_total['rate'] = $order_adjustment['adjustment-total']['VALUE'];
		$orderInfo->adjustment_total['currency'] = $order_adjustment['adjustment-total']['currency'];
		# End
		# order adjustment End

		# buyer-id = google buyer id
		$orderInfo->buyer_id = $data[$root]['buyer-id']['VALUE'];

		# buyer-shipping-address
		$buyer_shipping_address = $data[$root]['buyer-shipping-address'];

		$shipping_user = array();
    	$shipping_user['email'] = $buyer_shipping_address['email']['VALUE'];
    	$shipping_user['name'] = $buyer_shipping_address['contact-name']['VALUE'];
    	$shipping_user['company_name'] = $buyer_shipping_address['company-name']['VALUE'];
    	$shipping_user['phone'] = $buyer_shipping_address['phone']['VALUE'];
    	$shipping_user['fax'] = $buyer_shipping_address['fax']['VALUE'];
    	$shipping_user['address'] = $buyer_shipping_address['address1']['VALUE'];
    	$shipping_user['address2'] = $buyer_shipping_address['address2']['VALUE'];
    	$shipping_user['city'] = $buyer_shipping_address['city']['VALUE'];
    	$shipping_user['state'] = $buyer_shipping_address['region']['VALUE'];
    	$shipping_user['zipcode'] = $buyer_shipping_address['postal-code']['VALUE'];
    	$shipping_user['country'] = $buyer_shipping_address['country-code']['VALUE'];

    	$orderInfo->shipping['address'] = $shipping_user;
		# End

    	# buyer-marketing-preferences
    	$orderInfo->buyer_marketing_preferences['email_allowed'] = $data[$root]['buyer-marketing-preferences']['email-allowed']['VALUE'];

    	#order-total
    	$orderInfo->order_total['value'] = $data[$root]['order-total']['VALUE'];
    	$orderInfo->order_total['currency'] = $data[$root]['order-total']['currency'];
    	$orderInfo->order_state = $data[$root]['financial-order-state']['VALUE'];

    	$orderInfo->plugin = $gcplugin;
		//print_r($shipping_user);

    	$objOrder->placeGoogleOrder($orderInfo);

    	//mail("gunjan@redweb.dk","sucess","success");

      break;
    }
  	case "authorization-amount-notification": {
      $google_order_number = $data[$root]['google-order-number']['VALUE'];
      //$tracking_data = array("Z12345" => "UPS", "Y12345" => "Fedex");
      $tracking_data = array();
      $GChargeRequest = new GoogleRequest($merchant_id, $merchant_key, $server_type);
      $GChargeRequest->SendChargeAndShipOrder($google_order_number, $tracking_data);
      break;
    }
    case "order-state-change-notification": {

      $new_financial_state = $data[$root]['new-financial-order-state']['VALUE'];
      $new_fulfillment_order = $data[$root]['new-fulfillment-order-state']['VALUE'];

      switch($new_financial_state) {
        case 'REVIEWING': {
        	$orders_status_id = 'P';
			$orders_payment_status_id = 'Unpaid';
			$log = JText::_('GC_ORDER_REVIED');
          break;
        }
        case 'CHARGEABLE': {

          # redshop order status
          $orders_status_id = 'C';
          $orders_payment_status_id = 'Unpaid';
		  $log = JText::_('GC_ORDER_CHARGED');
		  # End

          $Grequest->SendProcessOrder($data[$root]['google-order-number']['VALUE']);
          $Grequest->SendChargeOrder($data[$root]['google-order-number']['VALUE'],'');
          break;
        }
        case 'CHARGING': {
        	$orders_status_id = 'C';
        	$orders_payment_status_id = 'Unpaid';
			$log = JText::_('GC_ORDER_CHARGED');
          break;
        }
        case 'CHARGED': {
        	$orders_status_id = 'C';
			$orders_payment_status_id = 'Paid';
			$log = JText::_('GC_ORDER_CONFIRM');
          break;
        }
        case 'PAYMENT_DECLINED': {
        	$orders_status_id = 'RT';
        	$orders_payment_status_id = 'Unpaid';
			$log = JText::_('GC_ORDER_PAYMENT_DECLINE');
          break;
        }
        case 'CANCELLED': {
        	$orders_status_id = 'X';
        	$orders_payment_status_id = 'Unpaid';
			$log = JText::_('GC_ORDER_PAYMENT_CANCELLED');
          break;
        }
        case 'CANCELLED_BY_GOOGLE': {
        	$orders_status_id = 'X';
        	$orders_payment_status_id = 'Unpaid';
			$log = JText::_('GC_ORDER_PAYMENT_CANCELLED_BY_GOOGLE');
			$Grequest->SendBuyerMessage($data[$root]['google-order-number']['VALUE'],"Sorry, your order is cancelled by Google", true);
          break;
        }
        default:
          break;
      }

      switch($new_fulfillment_order) {
        case 'NEW': {
          break;
        }
        case 'PROCESSING': {
          break;
        }
        case 'DELIVERED': {
        	$orders_status_id = 'S';
			$orders_payment_status_id = 'Paid';
			$log = JText::_('GC_ORDER_SHIPPED');
          break;
        }
        case 'WILL_NOT_DELIVER': {
          break;
        }
        default:
          break;
      }

      $op = $orders_status_id."<br />".$orders_payment_status_id."<br />".$log."<br />".$google_order_id;

      # redSHOP start
		$db =& JFactory::getDBO();

		if ($google_order_id != 0) {
			$query = "SELECT order_id FROM #__redshop_order_payment WHERE order_payment_trans_id = '" . $google_order_id . "'";
			$db->SetQuery ( $query );
			$order_id = $db->loadResult ();

		}

		// make status change array
		$values = new stdClass();
		$values->transaction_id				=	$google_order_id;
		$values->order_id					=	$order_id;
		$values->order_status_code			=	$orders_status_id;
		$values->order_payment_status_code	=	$orders_payment_status_id;
		$values->log						=	$log;
		$values->msg						=	JTEXT::_('ORDER_PLACED');

		// change order status
		$objOrder->changeorderstatus($values);

		# End
		$Gresponse->SendAck();
      break;
    }
    case "charge-amount-notification": {
      //$Grequest->SendDeliverOrder($data[$root]['google-order-number']['VALUE'],
      //    <carrier>, <tracking-number>, <send-email>);
      //$Grequest->SendArchiveOrder($data[$root]['google-order-number']['VALUE'] );
      $Gresponse->SendAck();
      break;
    }
    case "chargeback-amount-notification": {
      $Gresponse->SendAck();
      break;
    }
  	case "order-numbers": {
      break;
    }
  	case "invalid-order-numbers": {
      break;
    }
    case "refund-amount-notification": {
      $Gresponse->SendAck();
      break;
    }
    case "risk-information-notification": {
      $Gresponse->SendAck();
      break;
    }
    default:
      $Gresponse->SendBadRequestStatus("Invalid or not supported Message");
      break;
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