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
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'order.php' );
require_once (JPATH_COMPONENT.DS.'helpers'.DS.'product.php' );
require_once (JPATH_COMPONENT.DS.'helpers'.DS.'extra_field.php' );
require_once (JPATH_COMPONENT.DS.'helpers'.DS.'helper.php');
include_once (JPATH_COMPONENT.DS.'helpers'.DS.'cart.php');
include_once (JPATH_COMPONENT.DS.'helpers'.DS.'user.php');

jimport( 'joomla.application.component.controller' );
/**
 * Order Detail Controller
 *
 * @static
 * @package		redSHOP
 * @since 1.0
 */
class order_detailController extends JController
{
	function __construct( $default = array())
	{
		parent::__construct( $default );
		$this->_producthelper 	= new producthelper();
		$this->_redshopMail = new redshopMail();
		$this->_order_functions = new order_functions();
		$this->_extraField = new extraField();
		$this->_redhelper = new redhelper();
		$this->_userhelper = new rsUserhelper();
		$this->_carthelper = new rsCarthelper();
	}
	/*
	 *  bookinvoice function
	 */
	function bookinvoice()
	{
		// Economic Integration start for invoice generate and book current invoice
//		if(ECONOMIC_INTEGRATION==1)
//		{
//			$order_id = JRequest::getInt ( 'order_id' );
//			$economic = new economic();
//			$bookinvoicepdf = $economic->bookInvoiceInEconomic($order_id);
//			if(is_file($bookinvoicepdf))
//			{
//				$ret = $this->_redshopMail->sendEconomicBookInvoiceMail($order_id,$bookinvoicepdf);
//			}
//		}
		// End Economic
	}
	/*
	 *  process payment function for creditcard payment
	 */
	function process_payment(){

		global $mainframe;
		$db = jFactory::getDBO();
		$session =& JFactory::getSession();
		$model = $this->getModel('order_detail');

		$redconfig = new Redconfiguration();

		$request = JRequest::get('request');

		// Get Order Detail
		$order =  $this->_order_functions->getOrderDetails($request['order_id']);

		// get Billing and Shipping Info

		$billingaddresses = $this->_order_functions->getBillingAddress($order->user_id);
		$d['billingaddress'] = $billingaddresses;

		$shippingaddresses = $this->_order_functions->getOrderShippingUserInfo($order->order_id);
		$d['shippingaddress'] = $shippingaddresses;
	 	$Itemid = JRequest::getVar('Itemid');
		if(isset($billingaddresses))
		{
			if(isset($billingaddresses->country_code))
			{
				$billingaddresses->country_2_code = $redconfig->getCountryCode2($billingaddresses->country_code);
				$d ["billingaddress"]->country_2_code = $billingaddresses->country_2_code;
			}
			if(isset($billingaddresses->state_code))
		 	{
		 		$billingaddresses->state_2_code = $billingaddresses->state_code;//$redconfig->getCountryCode2($billingaddresses->state_code);
		 		$d ["billingaddress"]->state_2_code = $billingaddresses->state_2_code;
		 	}
		}
		if(isset($shippingaddresses))
		{
			if(isset($shippingaddresses->country_code))
		 	{
		 		$shippingaddresses->country_2_code = $redconfig->getCountryCode2($shippingaddresses->country_code);
		 		$d ["shippingaddress"]->country_2_code = $shippingaddresses->country_2_code;
		 	}
			if(isset($shippingaddresses->state_code))
		 	{
		 		$shippingaddresses->state_2_code = $shippingaddresses->state_code;//$redconfig->getCountryCode2($shippingaddresses->state_code);
		 		$d ["shippingaddress"]->state_2_code = $shippingaddresses->state_2_code;
		 	}
		}

		// get  data for credit card
		$ccdata['order_payment_name'] = $request['order_payment_name'];
		$ccdata['creditcard_code'] = $request['creditcard_code'];
		$ccdata['order_payment_number'] = $request['order_payment_number'];
		$ccdata['order_payment_expire_month'] = $request['order_payment_expire_month'];
		$ccdata['order_payment_expire_year'] = $request['order_payment_expire_year'];
		$ccdata['credit_card_code'] = $request['credit_card_code'];

		// Create session
		$session->set('ccdata',$ccdata);
		$ccdata = $session->get('ccdata');


		$values['order_shipping']	= $order->order_shipping;
		$values['order_number']		= $request['order_id'];
		$values['order_tax']		= $order->order_tax;
		$values['shippinginfo']		= $d ["shippingaddress"];
		$values['billinginfo']		= $d ["billingaddress"];
		$values['order_total']		= $order->order_total;
		$values['order_subtotal']	= $order->order_subtotal;
		$values["order_id"]         = $request['order_id'];
		$values['payment_plugin']	= $request['payment_method_id'];
		$values['order']	=	$order;


		// call payment plugin
		JPluginHelper::importPlugin('redshop_payment');
		$dispatcher =& JDispatcher::getInstance();

		$results = $dispatcher->trigger('onPrePayment_'.$values['payment_plugin'],array( $values['payment_plugin'], $values ));//die();
		$paymentResponse = $results[0];


				$paymentResponse->log			  = $paymentResponse->message;
				$paymentResponse->msg			  = $paymentResponse->message;

		 if($paymentResponse->responsestatus == "Success" || $values['payment_plugin'] == "")
		{

				$paymentResponse->order_status_code				  = 'C';
				$paymentResponse->order_payment_status_code		  = 'Paid';
				$paymentResponse->order_id       =  $request['order_id'];

				// chnage order status
				$this->_order_functions->changeorderstatus($paymentResponse);

		}



       // update order payment table with  credit card details
      	$model->update_ccdata($request['order_id'], $paymentResponse->transaction_id);
      	$model->resetcart();

		$link = 'index.php?option=com_redshop&view=order_detail&Itemid=' . $Itemid . '&oid='.$request['order_id'];
		$mainframe->redirect($link,$paymentResponse->message);



	}



	/*
	 * Notify payment function
	 */
	function notify_payment(){


		$mainframe = & JFactory::getApplication( 'site' );
		$db = jFactory::getDBO();
		$request=JRequest::get('request');


		$Itemid = JRequest::getVar('Itemid');
		require_once ( JPATH_BASE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'order.php');
	 	$objOrder = new order_functions();


		JPluginHelper::importPlugin('redshop_payment');
		$dispatcher =& JDispatcher::getInstance();

		$results = $dispatcher->trigger('onNotifyPayment'.$request['payment_plugin'],array( $request['payment_plugin'], $request ));

		$msg	=	$results[0]->msg;
		if(array_key_exists("order_id_temp",$results[0]))
		{
			$order_id=	$results[0]->order_id_temp;
		} else {
			$order_id=	$results[0]->order_id;
		}


		$objOrder->changeorderstatus($results[0]);
		$model = $this->getModel('order_detail');
		$resetcart = $model->resetcart();

		/*
		 * Plugin will trigger onAfterNotifyPayment
		 */
		$dispatcher->trigger('onAfterNotifyPayment'.$request['payment_plugin'],array($request['payment_plugin'], $order_id ));

		if($request['payment_plugin'] !="rs_payment_worldpay")
		{
			# new checkout flow
			$redirect_url = JRoute::_(JURI::base()."index.php?option=com_redshop&view=order_detail&layout=receipt&Itemid=$Itemid&oid=".$order_id);
			$this->setRedirect($redirect_url,$msg);
		}

	}

	function copyorderitemtocart()
	{
		global $mainframe;
		$order_item_id = JRequest::getInt('order_item_id');

		$orderItem = $this->_order_functions->getOrderItemDetail(0,0,$order_item_id);
		$row = (array)$orderItem[0];

		$subscription_id = 0;
		$row['quantity'] = $row['product_quantity'];
		if($row['is_giftcard']==1)
		{
			$row['giftcard_id'] = $row['product_id'];
		}
		else
		{
			$product_data =  $this->_producthelper->getProductById($row['product_id']);
			if($product_data->product_type=='subscription')
			{
				$productSubscription = $this->_producthelper->getUserProductSubscriptionDetail($row['order_item_id']);
				if($productSubscription->subscription_id!="")
				{
					$subscription_id = $productSubscription->subscription_id;
				}
			}
			$generateAttributeCart = $this->_carthelper->generateAttributeFromOrder($row['order_item_id'],0,$row['product_id'],$row['product_quantity']);
			$generateAccessoryCart = $this->_carthelper->generateAccessoryFromOrder($row['order_item_id'],$row['product_id'],$row['product_quantity']);

			$row['cart_attribute'] = $generateAttributeCart;
			$row['cart_accessory'] = $generateAccessoryCart;
			$row['subscription_id'] = $subscription_id;
			$row['sel_wrapper_id'] = $row['wrapper_id'];
			$row['category_id'] = 0;
		}
		$result = $this->_carthelper->addProductToCart($row);
		if(is_bool($result) && $result)
		{
			$Itemid = JRequest::getVar('Itemid');
			$Itemid = $this->_redhelper->getCartItemid($Itemid);
			$this->_carthelper->cartFinalCalculation();
			$mainframe->redirect('index.php?option=com_redshop&view=cart&Itemid='.$Itemid);
		}
		else
		{
			$ItemData = $this->_producthelper->getMenuInformation(0,0,'','product&pid='.$row['product_id']);
			if(count($ItemData)>0)
			{
				$Itemid = $ItemData->id;
			} else {
				$Itemid = $this->_redhelper->getItemid($row['product_id']);
			}
			$errmsg = ($result) ? $result : JText::_("COM_REDSHOP_PRODUCT_NOT_ADDED_TO_CART");
			if(JError::isError(JError::getError()))
			{
				$error = JError::getError();
				$errmsg = $error->message;
			}
			$returnlink = "index.php?option=com_redshop&view=product&pid=".$row["product_id"]."&Itemid=".$Itemid;
			$mainframe->redirect($returnlink,$errmsg);
		}
	}

	function reorder()
	{
		global $mainframe;
		$session =& JFactory::getSession();
		$post = JRequest::get('post');
		$order_id = (isset($post['order_id'])) ? $post['order_id'] : JRequest::getInt('order_id');
		$Itemid = JRequest::getVar('Itemid');
		$Itemid = $this->_redhelper->getCartItemid($Itemid);

		$returnmsg = "";
		if($order_id)
		{
			//First Empty Cart and then oder it again
			$cart['idx'] = 0;
			$session->set( 'cart',$cart );

			$orderItem = $this->_order_functions->getOrderItemDetail($order_id);
			for($i=0;$i<count($orderItem);$i++)
			{
				$row = (array)$orderItem[$i];
				$subscription_id = 0;
				$row['quantity'] = $row['product_quantity'];
				if($row['is_giftcard']==1)
				{
					$row['giftcard_id'] = $row['product_id'];
					$row['reciver_name']=$row['giftcard_user_name'];
					$row['reciver_email']=$row['giftcard_user_email'];
				}
				else
				{
					$product_data =  $this->_producthelper->getProductById($row['product_id']);
					if($product_data->product_type=='subscription')
					{
						$productSubscription = $this->_producthelper->getUserProductSubscriptionDetail($row['order_item_id']);
						if($productSubscription->subscription_id!="")
						{
							$subscription_id = $productSubscription->subscription_id;
						}
					}
					$generateAttributeCart = $this->_carthelper->generateAttributeFromOrder($row['order_item_id'],0,$row['product_id'],$row['product_quantity']);
					$generateAccessoryCart = $this->_carthelper->generateAccessoryFromOrder($row['order_item_id'],$row['product_id'],$row['product_quantity']);

					$row['cart_attribute'] = $generateAttributeCart;
					$row['cart_accessory'] = $generateAccessoryCart;
					$row['subscription_id'] = $subscription_id;
					$row['sel_wrapper_id'] = $row['wrapper_id'];
					$row['category_id'] = 0;
				}
				$result = $this->_carthelper->addProductToCart($row);
				if(is_bool($result) && $result)
				{
					$returnmsg .= $row['order_item_name'].": ".JText::_("COM_REDSHOP_PRODUCT_ADDED_TO_CART")."<br>";
				}
				else
				{
					$ItemData = $this->_producthelper->getMenuInformation(0,0,'','product&pid='.$row['product_id']);
					if(count($ItemData)>0)
					{
						$Itemid = $ItemData->id;
					} else {
						$Itemid = $this->_redhelper->getItemid($row['product_id']);
					}
					$errmsg = ($result) ? $result : JText::_("COM_REDSHOP_PRODUCT_NOT_ADDED_TO_CART");
					if(JError::isError(JError::getError()))
					{
						$error = JError::getError();
						$errmsg = $error->message;
					}
					$returnmsg .= $row['order_item_name'].": ".$errmsg."<br>";
					$returnlink = "index.php?option=com_redshop&view=product&pid=".$row["product_id"]."&Itemid=".$Itemid;
				}
			}
			$this->_carthelper->cartFinalCalculation();
		}
		$cart = $session->get( 'cart');
		if(!$cart || !array_key_exists("idx",$cart) || ($cart && $cart['idx']<=0))
		{
			$mainframe->redirect($returnlink);
		}
		else
		{
			$mainframe->redirect("index.php?option=com_redshop&view=cart&Itemid=".$Itemid,$returnmsg);
		}
	}

	/*
	 *  payment function
	 */
	function payment()
	{
		global  $mainframe;
		$redconfig = new Redconfiguration();
		$Itemid = JRequest::getVar('Itemid');
		$order_id = JRequest::getInt ( 'order_id' );
		$option = JRequest::getVar ( 'option' );

		$order = $this->_order_functions->getOrderDetails($order_id);
		$paymentInfo = $this->_order_functions->getOrderPaymentDetail($order_id);

		if(count($paymentInfo)>0)
		{
			$paymentmethod 	= $this->_order_functions->getPaymentMethodInfo($paymentInfo[0]->payment_method_class);
			if(count($paymentmethod)>0)
			{
				$paymentparams = new JRegistry( $paymentmethod[0]->params );
				$is_creditcard = $paymentparams->get('is_creditcard',0);


				if($is_creditcard)
				{
					JHTML::Script('credit_card.js', 'components/com_redshop/assets/js/',false);	?>

					<form action="<?php echo JRoute::_('index.php?option=com_redshop&view=checkout') ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" onsubmit="return CheckCardNumber(this);">
					<?php echo $cardinfo = $this->_carthelper->replaceCreditCardInformation($paymentInfo[0]->payment_method_class); ?>
						<div style="float: right;">
						<input type="hidden" name="option" value="com_redshop" />
						<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
						<input type="hidden" name="task" value="process_payment" />
						<input type="hidden" name="view" value="order_detail" />
						<input type="submit" name="submit" class="greenbutton" value="<?php echo JText::_('COM_REDSHOP_PAY');?>"/>
						<input type="hidden" name="ccinfo" value="1"/>
						<input type="hidden" name="users_info_id" value="<?php echo $order->user_info_id; ?>" />
						<input type="hidden" name="order_id" value="<?php echo $order->order_id; ?>" />
						<input type="hidden" name="payment_method_id" value="<?php echo $paymentInfo[0]->payment_method_class; ?>" />
						</div>
					</form><?php
				} else {

					$link = 'index.php?option=com_redshop&view=checkout&format=final&oid='.$order_id.'&Itemid='.$Itemid;
					$this->setRedirect($link);
				}
			}
		}
	}
}	?>