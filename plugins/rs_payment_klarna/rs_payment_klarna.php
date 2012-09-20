<?php error_reporting(1);
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

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');

require_once ( JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'order.php');
require_once ( JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'configuration.php');
class plgRedshop_paymentrs_payment_klarna extends JPlugin
{
	var $_table_prefix = null;
   /**
    * Constructor
    *
    * For php4 compatability we must not use the __constructor as a constructor for
    * plugins because func_get_args ( void ) returns a copy of all passed arguments
    * NOT references.  This causes problems with cross-referencing necessary for the
    * observer design pattern.
    */
   	function plgRedshop_paymentrs_payment_klarna(&$subject)
    {
            // load plugin parameters
            parent::__construct( $subject );
            $this->_table_prefix = '#__redshop_';
            $this->_plugin = JPluginHelper::getPlugin( 'redshop_payment', 'rs_payment_klarna' );
            $this->_params = new JParameter( $this->_plugin->params );

    }

    
    
	function onPrePayment($element, $data)
    {
    	if($element!='rs_payment_klarna'){
    		return;
    	}
    	if (empty($plugin))
        {
         	$plugin = $element;
        }

 		$mainframe =& JFactory::getApplication();
 		$paymentpath=JPATH_SITE.DS.'plugins'.DS.'redshop_payment'.DS.$plugin.DS.'addinfoform.php';
		include($paymentpath);
    }
    
    

 
    function onAdditionalInformationrs_payment_klarna($element,$data)
    {
    
    	
    
    		if($element!='rs_payment_klarna'){
	    		return;
	    	}
	    	if (empty($plugin))
	        {
	         	$plugin = $element;
	        }
	        
	        
	        $new_user= true;
	
        	$klarna_social_number= $this->getUser_KlarnaSocial_ref($data['order']->user_id);
        	if($klarna_social_number != "")
        	{
        		$new_user= false;
        		
        	}
	           	 	
			if($new_user)
			{
	        
	        
	        JHTML::Script('additional_info.js', 'plugins/redshop_payment/'.$element.'/',false);	
	        $cardinfo ='';
	        $cardinfo .='<form action="'.JRoute::_('index.php?option=com_redshop&view=checkout&format=final&stap=2&oid='.(int) $data['order_id'].'&Itemid='.$Itemid ).'" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" onsubmit="return CheckAdditionalInfo(this);">';
	        $cardinfo .='<fieldset class="adminform"><legend>'.JText::_('KLARNA_SOCIAL_SECURITY_NUMBER' ).'</legend>';
			$cardinfo .='<table width="100%" border="0" cellspacing="2" cellpadding="2">';
			
			
			$cardinfo .='<tr><td>'.JText::_('SOCIAL_SECURITY_NUMBER').'</td><td><input type="text" name="social_security_number" value=""></td></tr>';
			$cardinfo .='<tr><td colspan="2" align="center"><input type="submit" name="submit" class="greenbutton" value="'.JText::_('BTN_CHECKOUTNEXT').'" /></td></tr>';
			
			//return $cardinfo;
			
			
			} else {
			
			$cardinfo = '<form action="'.JRoute::_('index.php?option=com_redshop&view=checkout&format=final&stap=2&oid='.(int) $data['order_id'].'&Itemid='.$Itemid ).'" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" >';
			$cardinfo .='<table height="100">
								<tr><td>'.JText::_('USER_IS_ALREADY_REGISTERED_IN_REDSHOP_KLARNA').'</td></tr>';
			$cardinfo .='<tr><td colspan="2" align="center"><input type="submit" name="submit" class="greenbutton" value="'.JText::_('BTN_CHECKOUTNEXT').'" /></td></tr>';
			
			}
			
			$cardinfo .= '<input type="hidden" name="option" value="com_redshop" />';
			$cardinfo .= '<input type="hidden" name="Itemid" value="'.$Itemid.'" />';
			$cardinfo .= '<input type="hidden" name="task" value="barintree_checkout_next" />';
			$cardinfo .= '<input type="hidden" name="view" value="checkout" />';
			$cardinfo .= '<input type="hidden" name="adinfo" value="1" />';
			$cardinfo .= '<input type="hidden" name="payment_method_id" value="'.$this->payment_method_id.'" />';
			$cardinfo .= '<input type="hidden" name="new_user" value="'.$new_user.'" />';
			$cardinfo .= '<input type="hidden" name="klarna_social_number" value="'.$klarna_social_number.'" />';
			$cardinfo .= '<input type="hidden" name="order_id" value="'.$data['order_id'].'" />';
			$cardinfo .='</table>';
			$cardinfo .= '</form>';
			echo eval("?>".$cardinfo."<?php ");
    }
    
    function reserveAmountrs_payment_klarna($element, $data)
    {
    
   		$mainframe =& JFactory::getApplication('site');
    	if($element!='rs_payment_klarna'){
    		return;
    	}
    	if (empty($plugin))
        {
         	$plugin = $element;
        }
     
        $Redconfiguration = new Redconfiguration();
    	$producthelper = new producthelper();
       	$order_functions = new order_functions();
       	$order = $order_functions->getOrderDetails($data['order_id']);
       	$order_item = $order_functions->getOrderItemDetail($data['order_id']);
      	
    
       	$class_path=JPATH_SITE.DS.'plugins'.DS.'redshop_payment'.DS.$element.DS.'Klarna.php';
		include($class_path);
        
       
		$transport_path = JPATH_SITE.DS.'plugins'.DS.'redshop_payment'.DS.$element.DS.'transport'.DS.'xmlrpc-3.0.0.beta'.DS.'lib'.DS.'xmlrpc.inc';
		include($transport_path);
		$transport_path1 = JPATH_SITE.DS.'plugins'.DS.'redshop_payment'.DS.$element.DS.'transport'.DS.'xmlrpc-3.0.0.beta'.DS.'lib'.DS.'xmlrpc_wrappers.inc';
		include($transport_path1);
		
		$k = new Klarna();
		$billingInfo = $order_functions->getOrderBillingUserInfo ( $data['order_id'] );
		$shippingInfo = $order_functions->getOrderShippingUserInfo ( $data['order_id'] );
		
		$billingInfo->country_2_code = $Redconfiguration->getCountryCode2($billingInfo->country_code);
		$shippingInfo->country_2_code = $Redconfiguration->getCountryCode2($shippingInfo->country_code);
		
		
		$config = $k->getConfigCountryDetails($billingInfo);
		$klarna_country = $k->getBillingandShippingCountry($billingInfo, $shippingInfo);
	
		/** Configure the Klarna object using the config() method. (Alternative 2) **/
		
		$k->config(
		    $eid = $this->_params->get("eid"),//2109,
		    $secret = $this->_params->get("secret"),//'ddV5tgNS742WWdx',
		    $country = $config['country'],
		    $language = $config['language'],
		    $currency = $config['currency'],
		    $mode = Klarna::BETA,
		    $pcStorage = 'json',
		    $pcURI = '/srv/pclasses.json',
		    $ssl = true,
		    $candice = true
		);
						
		 /**
		 * 2. Add the article(s), shipping and/or handling fee.
		 */
			//$idx =  $cart['idx'];
			for( $i=0 ; $i < count($order_item); $i++ )
			{
				if($order_item[$i]->product_id!="")
				{
				
				$product_id = $order_item[$i]->product_id;
				$product = $producthelper->getProductById($product_id);
				$pvat= $order_item[$i]->product_item_price-$order_item[$i]->product_item_price_excl_vat;
				
				$pvat_in_percent= ($pvat*100)/$order_item[$i]->product_item_price;
				//$product_price = $currencyClass->convert ( $cart[$i]['product_price'], '', $currency_main );
					$k->addArticle(
				    $qty = $order_item[$i]->product_quantity, //Quantity
				    $artNo = $order_item[$i]->order_item_sku, //Article number
				    $title = $order_item[$i]->order_item_name, //Article name/title
				    $price = $order_item[$i]->product_item_price,
				    $vat = $pvat_in_percent, //% VAT
				    $discount = 0,
				    $flags = KlarnaFlags::INC_VAT //Price is including VAT.
				);
				
				}
			}
			
			if($order->order_discount > 0)
			{		
				//Next we might want to add a Disount fee for the product
				$k->addArticle(
				    $qty = 1,
				    $artNo = "",
				    $title = "Discount",
				    $price =-$order->order_discount,
				    $vat = 0,
				    $discount = 0,
				    $flags = KlarnaFlags::INC_VAT//Price is including VAT and is shipment fee
				);
			}
			if($order->order_shipping > 0)			
			{
				//Next we might want to add a shipment fee for the product
				$k->addArticle(
				    $qty = 1,
				    $artNo = "",
				    $title = "Shipping fee",
				    $price = $order->order_shipping,
				    $vat = 0,
				    $discount = 0,
				    $flags = KlarnaFlags::INC_VAT + KlarnaFlags::IS_SHIPMENT //Price is including VAT and is shipment fee
				);
			}
			$payment_price	=	$order->payment_discount;
    	
			if($payment_price>0)
			{
				
				if($order->payment_oprand=='-'){
					$discount_payment_price		=	-$payment_price;
				//$post_variables['discount_amount_cart']	+=	round($currencyClass->convert ( $order_details[0]->payment_discount, '', $currency_main ),2);
	
				}else{
					$discount_payment_price		=	$payment_price;
				
				}	
				//Lastly, we want to use an invoice/handling fee as well
				$k->addArticle(
				    $qty = 1,
				    $artNo = "",
				    $title = "Handling fee",
				    $price = $discount_payment_price,
				    $vat = 0,
				    $discount = 0,
				    $flags = KlarnaFlags::INC_VAT + KlarnaFlags::IS_HANDLING //Price is including VAT and is handling/invoice fee
				);
			}
					
       
       			
			/**
			 * 3. Create and set the address(es).
			 */
			
	
			//echo $data['billinginfo']->country_2_code;die();
			$addr = new KlarnaAddr(
			    $email = $billingInfo->user_email,
			    $telno = '', //We skip the normal land line phone, only one is needed.
			    $cellno = '20 123 456',
			    $fname = $billingInfo->firstname,
			    $lname = $billingInfo->lastname,
			    $careof = '',  //No care of, C/O.
			    $street = $billingInfo->address, //For DE and NL specify street number in houseNo.
			    $zip = $billingInfo->zipcode,
			    $city = $billingInfo->city,
			    $country = $klarna_country['billing_country'],
			    $houseNo = '', //For DE and NL we need to specify houseNo.
			    $houseExt = null //Only required for NL.
			);
			//There are also set/get methods to do the same thing, like:
			$addr->setEmail($billingInfo->user_email);
			if(SHIPPING_METHOD_ENABLE)
			{
				
				$addr1 = new KlarnaAddr(
				    $email = $shippingInfo->user_email,
				    $telno = '', //We skip the normal land line phone, only one is needed.
				    $cellno = '20 123 456',
				    $fname = $shippingInfo->firstname,
				    $lname = $shippingInfo->lastname,
				    $careof = '',  //No care of, C/O.
				    $street = $shippingInfo->address, //For DE and NL specify street number in houseNo.
				    $zip = $shippingInfo->zipcode,
				    $city = $shippingInfo->city,
				    $country = $klarna_country['shipping_country'],
				    $houseNo = '', //For DE and NL we need to specify houseNo.
				    $houseExt = null //Only required for NL.
				);
				$addr1->setEmail($shippingInfo->user_email);
			
			
			}
			
			//Next we tell the Klarna instance to use the address in the next order.
			$k->setAddress(KlarnaFlags::IS_BILLING, $addr); //Billing / invoice address
			if(SHIPPING_METHOD_ENABLE)
			{
				$k->setAddress(KlarnaFlags::IS_SHIPPING, $addr1); //Shipping / delivery address
			}  else {
				
				$k->setAddress(KlarnaFlags::IS_SHIPPING, $addr); //Shipping / delivery address
			}
			
		
		
		
		/**
		 * 4. Specify relevant information from your store. (OPTIONAL)
		 */
		$k->setEstoreInfo(
		    $orderid1 = $data['order_number'], //Maybe the estore's order number/id.
		    $orderid2 = '', //Could an order number from another system?
		    $user = $billingInfo->user_email//Username, email or identifier for the user?
		);
		

		echo $k->checkoutHTML();
		
		/** Shipment type? **/
		
		//Normal shipment is defaulted, delays the start of invoice expiration/due-date.
		$k->setShipmentInfo('delay_adjust', KlarnaFlags::EXPRESS_SHIPMENT);
		
		/**
		 * 6. Invoke reserveAmount and transmit the data.
		 */
		if($data['new_user'])
		{
		$social_security_number = $data['social_security_number'];
		} else {
		$social_security_number = $data['klarna_social_number'];
		
		}
		
		
		try {
		    //Transmit all the specified data, from the steps above, to Klarna.
		    $result = $k->reserveAmount(
		        $pno = $social_security_number,//'0801363945', //Date of birth for DE.
		        $gender = KlarnaFlags::MALE, //The customer is a male.
		        $amount = -1, //Will calculate the amount using the internal goods list.
		        $flags = KlarnaFlags::NO_FLAG, //No specific behaviour like TEST_MODE.
		        $pclass = KlarnaPClass::INVOICE //-1, notes that this is an invoice purchase, for part payment purchase you will have a pclass object on which you use getId().
		    );
		
		    //Check the order status
		    if($result[1] == KlarnaFlags::PENDING) {
				$redirect_url = JURI::base()."index.php?option=com_redshop&view=order_detail&task=notify_payment&payment_plugin=rs_payment_klarna&Itemid=$Itemid&oid=".$data['order_id']."&tid=".$result[0]."&sts=0";
				$mainframe->redirect($redirect_url); 
		    } else {
		    
		    	$this->update_KlarnaSocial_ref($order->user_id, $social_security_number);
		    	$redirect_url = JURI::base()."index.php?option=com_redshop&view=order_detail&task=notify_payment&payment_plugin=rs_payment_klarna&Itemid=$Itemid&oid=".$data['order_id']."&tid=".$result[0]."&sts=1";
				$mainframe->redirect($redirect_url);
		    }

		    
		    //Order is complete, store it in a database.
		}
		catch(Exception $e) {
		    //The purchase was denied or something went wrong, print the message:

			
			$message = $e->getMessage() . " (#" . $e->getCode() . ")";
			
			$redirect_url = JURI::base()."index.php?option=com_redshop&view=order_detail&task=notify_payment&payment_plugin=rs_payment_klarna&Itemid=$Itemid&oid=".$data['order_id']."&tid=".$result[0]."&sts=0&message=".$message;
			$mainframe->redirect($redirect_url, $message);		  
		   
		}
	
  
    }
    
    
	function onNotifyPaymentrs_payment_klarna($element, $request){

    	if($element!='rs_payment_klarna'){
    		return;
    	}
    	
    	if (empty($plugin))
        {
         	$plugin = $element;
        }
    	$db = JFactory::getDBO();
		$request=JRequest::get('request');
		$Itemid = $request["Itemid"];
		$user = JFActory::getUser();
        $user_id = $user->id;
		
		
	
		// Result Response 

	 	$tid = $request["tid"];
		$order_id = $request["oid"];


		$klarna_parameters=$this->getparameters('rs_payment_klarna');
		$paymentinfo = $klarna_parameters[0];
		
		
		
		$paymentparams = new JParameter( $paymentinfo->params );
	 	$verify_status = $paymentparams->get('verify_status','');
	 	$invalid_status = $paymentparams->get('invalid_status','');
		$cancel_status = $paymentparams->get('cancel_status','');
		
		
		if (isset($tid) && $request['sts']==1) 
		{
		 	$values->order_status_code=$verify_status;
		 	$values->order_payment_status_code='Paid';
		 	$values->log=JTEXT::_('ORDER_PLACED');
 		 	$values->msg=JTEXT::_('ORDER_PLACED');
 		 	
 		 	
 		 	

		}else{
			$values->order_status_code=$invalid_status;
			$values->order_payment_status_code='Unpaid';
			if($request['message']!="")
			{
		 	$values->log=$request['message'];
		 	$values->msg=$request['message'];
			} else {
 		 	$values->msg=JTEXT::_('ORDER_PLACED');
 		 	$values->log=JTEXT::_('ORDER_PLACED');
			}
		}

 		$values->transaction_id=$tid;
		$values->order_id=$order_id;
	
		return $values;
 		
    }
    
    function onSetAdditionalInformationrs_payment_klarna($element)
    {
    	
    		if($element!='rs_payment_klarna'){
	    		return;
	    	}
	    	if (empty($plugin))
	        {
	         	$plugin = $element;
	        }
	 		$session =& JFactory::getSession();
    		$addata['social_security_number'] = $data['social_security_number'];
			$session->set('addata',$addata);
			
    }	
			
			
	function getparameters($payment){
			$db = JFactory::getDBO();
			$sql="SELECT * FROM #__plugins WHERE `element`='".$payment."'";
			$db->setQuery($sql);
			$params=$db->loadObjectList();
			return $params;
	}
    

    
	function onCapture_Paymentrs_payment_klarna($element, $data)
    {
    
    	$Redconfiguration = new Redconfiguration();
    	$producthelper = new producthelper();
       	$order_functions = new order_functions();
       	$order = $order_functions->getOrderDetails($data['order_id']);
       	$order_item = $order_functions->getOrderItemDetail($data['order_id']);
      	
    
       	$class_path=JPATH_SITE.DS.'plugins'.DS.'redshop_payment'.DS.$element.DS.'Klarna.php';
		include($class_path);
        
       
		$transport_path = JPATH_SITE.DS.'plugins'.DS.'redshop_payment'.DS.$element.DS.'transport'.DS.'xmlrpc-3.0.0.beta'.DS.'lib'.DS.'xmlrpc.inc';
		include($transport_path);
		$transport_path1 = JPATH_SITE.DS.'plugins'.DS.'redshop_payment'.DS.$element.DS.'transport'.DS.'xmlrpc-3.0.0.beta'.DS.'lib'.DS.'xmlrpc_wrappers.inc';
		include($transport_path1);
		
		$k = new Klarna();
		
		
		$data['billinginfo']->country_2_code = $Redconfiguration->getCountryCode2($data['billinginfo']->country_code);
		$data['shippinginfo']->country_2_code = $Redconfiguration->getCountryCode2($data['shippinginfo']->country_code);
		
		
		$config = $k->getConfigCountryDetails($data['billinginfo'], $data['shippinginfo']);
		$klarna_country = $k->getBillingandShippingCountry($data['billinginfo'], $data['shippinginfo']);
	
		/** Configure the Klarna object using the config() method. (Alternative 2) **/
		
		$k->config(
		    $eid = $this->_params->get("eid"),//2109,
		    $secret = $this->_params->get("secret"),//'ddV5tgNS742WWdx',
		    $country = $config['country'],
		    $language = $config['language'],
		    $currency = $config['currency'],
		    $mode = Klarna::BETA,
		    $pcStorage = 'json',
		    $pcURI = '/srv/pclasses.json',
		    $ssl = true,
		    $candice = true
		);
						
		 /**
		 * 2. Add the article(s), shipping and/or handling fee.
		 */
			//$idx =  $cart['idx'];
			for( $i=0 ; $i < count($order_item); $i++ )
			{
				if($order_item[$i]->product_id!="")
				{
				
				$product_id = $order_item[$i]->product_id;
				$product = $producthelper->getProductById($product_id);
				$pvat= $order_item[$i]->product_item_price-$order_item[$i]->product_item_price_excl_vat;
				
				$pvat_in_percent= ($pvat*100)/$order_item[$i]->product_item_price;
				//$product_price = $currencyClass->convert ( $cart[$i]['product_price'], '', $currency_main );
					$k->addArticle(
				    $qty = $order_item[$i]->product_quantity, //Quantity
				    $artNo = $order_item[$i]->order_item_sku, //Article number
				    $title = $order_item[$i]->order_item_name, //Article name/title
				    $price = $order_item[$i]->product_item_price,
				    $vat = $pvat_in_percent, //% VAT
				    $discount = 0,
				    $flags = KlarnaFlags::INC_VAT //Price is including VAT.
				);
				
				}
			}
			
			if($order->order_discount > 0)
			{		
				//Next we might want to add a Disount fee for the product
				$k->addArticle(
				    $qty = 1,
				    $artNo = "",
				    $title = "Discount",
				    $price =-$order->order_discount,
				    $vat = 0,
				    $discount = 0,
				    $flags = KlarnaFlags::INC_VAT//Price is including VAT and is shipment fee
				);
			}
			if($order->order_shipping > 0)			
			{
				//Next we might want to add a shipment fee for the product
				$k->addArticle(
				    $qty = 1,
				    $artNo = "",
				    $title = "Shipping fee",
				    $price = $order->order_shipping,
				    $vat = 0,
				    $discount = 0,
				    $flags = KlarnaFlags::INC_VAT + KlarnaFlags::IS_SHIPMENT //Price is including VAT and is shipment fee
				);
			}
			$payment_price	=	$order->payment_discount;
    	
			if($payment_price>0)
			{
				
				if($order->payment_oprand=='-'){
					$discount_payment_price		=	-$payment_price;
				//$post_variables['discount_amount_cart']	+=	round($currencyClass->convert ( $order_details[0]->payment_discount, '', $currency_main ),2);
	
				}else{
					$discount_payment_price		=	$payment_price;
				
				}	
				//Lastly, we want to use an invoice/handling fee as well
				$k->addArticle(
				    $qty = 1,
				    $artNo = "",
				    $title = "Handling fee",
				    $price = $discount_payment_price,
				    $vat = 0,
				    $discount = 0,
				    $flags = KlarnaFlags::INC_VAT + KlarnaFlags::IS_HANDLING //Price is including VAT and is handling/invoice fee
				);
			}
					
       
       			
			/**
			 * 3. Create and set the address(es).
			 */
			
	
			//echo $data['billinginfo']->country_2_code;die();
			$addr = new KlarnaAddr(
			    $email = $data['billinginfo']->user_email,
			    $telno = '', //We skip the normal land line phone, only one is needed.
			    $cellno = '20 123 456',
			    $fname = $data['billinginfo']->firstname,
			    $lname = $data['billinginfo']->lastname,
			    $careof = '',  //No care of, C/O.
			    $street = $data['billinginfo']->address, //For DE and NL specify street number in houseNo.
			    $zip = $data['billinginfo']->zipcode,
			    $city = $data['billinginfo']->city,
			    $country = $klarna_country['billing_country'],
			    $houseNo = '', //For DE and NL we need to specify houseNo.
			    $houseExt = null //Only required for NL.
			);
			//There are also set/get methods to do the same thing, like:
			$addr->setEmail($data['billinginfo']->user_email);
			if(SHIPPING_METHOD_ENABLE)
			{
				
				$addr1 = new KlarnaAddr(
				    $email = $data['shippinginfo']->user_email,
				    $telno = '', //We skip the normal land line phone, only one is needed.
				    $cellno = '20 123 456',
				    $fname = $data['shippinginfo']->firstname,
				    $lname = $data['shippinginfo']->lastname,
				    $careof = '',  //No care of, C/O.
				    $street = $data['shippinginfo']->address, //For DE and NL specify street number in houseNo.
				    $zip = $data['shippinginfo']->zipcode,
				    $city = $data['shippinginfo']->city,
				    $country = $klarna_country['shipping_country'],
				    $houseNo = '', //For DE and NL we need to specify houseNo.
				    $houseExt = null //Only required for NL.
				);
				$addr1->setEmail($data['shippinginfo']->user_email);
			
			
			}
			
			//Next we tell the Klarna instance to use the address in the next order.
			$k->setAddress(KlarnaFlags::IS_BILLING, $addr); //Billing / invoice address
			if(SHIPPING_METHOD_ENABLE)
			{
				$k->setAddress(KlarnaFlags::IS_SHIPPING, $addr1); //Shipping / delivery address
			}  else {
				
				$k->setAddress(KlarnaFlags::IS_SHIPPING, $addr); //Shipping / delivery address
			}
			
		
		
			
			/**
			 * 4. Specify relevant information from your store. (OPTIONAL)
			 */
		
			//Set store specific information so you can e.g. search and associate invoices with order numbers.
			$k->setEstoreInfo(
			    $orderid1 = $data['order_number'], //Maybe the estore's order number/id.
			    $orderid2 = '', //Could an order number from another system?
			    $user = $data['billinginfo']->user_email//Username, email or identifier for the user?
			);
			
			//If you don't have the order id available at this stage, you can later use the method updateOrderNo().
			
			/**
			 * 5. Set additional information. (OPTIONAL)
			 */
			
			/** Comment? **/
		
				$k->setComment('RedSHOP Order.');
				//Normal shipment is defaulted, delays the start of invoice expiration/due-date.
				$k->setShipmentInfo('delay_adjust', KlarnaFlags::EXPRESS_SHIPMENT);
				
				/**
				 * 6. Invoke reserveAmount and transmit the data.
				 */
				$rno = $data['order_transactionid'];
				$pno = $this->getUser_KlarnaSocial_ref($order->user_id);
				    
				try {
				    //Transmit all the specified data, from the steps above, to Klarna.
				    $result = $k->activateReservation(
				        $pno = $pno, //Date of birth for DE.
				        $rno,
				        $gender = KlarnaFlags::MALE, //The customer is a male.
				        $ocr = '', //If you reserved an OCR number earlier.
				        $flags = KlarnaFlags::NO_FLAG, //No specific behaviour.
				        $pclass = KlarnaPClass::INVOICE //-1, notes that this is an invoice purchase, for part payment purchase you will have a pclass object which you use getId() from.
				    );
				
				    $risk = $result[0]; //ok or no_risk
				    $invno = $result[1];
				    
				    if($risk =='ok' || $risk =='no_risk')
				    {
				    	$this->update_transaction_id($invno, $data['order_id']);
				    	$values->responsestatus		= 'Success';
					 	$message= JText::_('ORDER_CAPTURED');
				    } else {
				    	$message = JText::_('ORDER_NOT_CAPTURED');
					 	$values->responsestatus		= 'Fail';
				    
				    }
			
					//	
				    //Reservation is activated, proceed accordingly.
				}
				catch(Exception $e) {
				
				    //Something went wrong, print the message:
				    			    
				    $message = $e->getMessage();
					$values->responsestatus		= 'Fail';
				}
				
				
				$values->message 			= $message;
				return $values;
	
    }
    
    
    
		function update_transaction_id($tid, $order_id)
		{
			$db = JFactory::getDBO();
		
		 	$query = "UPDATE `".$this->_table_prefix."order_payment` SET `order_payment_trans_id` ='".$tid."'  WHERE `order_id` =".$order_id."";
			$db->SetQuery($query);
		 	$db->query();
		
		}    
    	function getUser_KlarnaSocial_ref($user_id)
		{
			$db = JFactory::getDBO();
			$query = "SELECT klarna_social_number
							FROM  `".$this->_table_prefix."users_info`
							WHERE  `user_id` = '".$user_id."'
							AND address_type = 'BT'";
			$db->setQuery($query);
			$KlarnaSocial_ref = $db->loadObject();
			
			
			return $KlarnaSocial_ref->klarna_social_number;
			
		}
		
	
    	
    	
    	function update_KlarnaSocial_ref($user_id, $ssn)
    	{
    		$db = JFactory::getDBO();
		
		 	$query = "UPDATE `".$this->_table_prefix."users_info` SET `klarna_social_number` ='".$ssn."'  WHERE `user_id` =".$user_id." AND address_type = 'BT'";
			$db->SetQuery($query);
		 	$db->query();
			
			
    	}
    		
    
    		
}

