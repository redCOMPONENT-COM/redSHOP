<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;



/**
 * Order Detail Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class RedshopControllerOrder_detail extends RedshopController
{
	/**
	 * Constructor
	 *
	 * @param   array  $default  config
	 */
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->_producthelper   = productHelper::getInstance();
		$this->_redshopMail     = redshopMail::getInstance();
		$this->_order_functions = order_functions::getInstance();
		$this->_extraField      = extraField::getInstance();
		$this->_redhelper       = redhelper::getInstance();
		$this->_userhelper      = rsUserHelper::getInstance();
		$this->_carthelper      = rsCarthelper::getInstance();
	}

	/**
	 *  bookinvoice function
	 *
	 * @return void
	 */
	public function bookinvoice()
	{
	}

	/**
	 * Process payment function for creditcard payment.
	 *
	 * @return  void
	 */
	public function process_payment()
	{
		$app     = JFactory::getApplication();
		$db      = JFactory::getDbo();
		$session = JFactory::getSession();
		$model   = $this->getModel('order_detail');

		$redconfig = Redconfiguration::getInstance();

		$request = $this->input->getArray();

		// Get Order Detail
		$order = $this->_order_functions->getOrderDetails($request['order_id']);

		// Get Billing and Shipping Info
		$billingaddresses     = $this->_order_functions->getBillingAddress($order->user_id);
		$d['billingaddress']  = $billingaddresses;

		$shippingaddresses    = RedshopHelperOrder::getOrderShippingUserInfo($order->order_id);
		$d['shippingaddress'] = $shippingaddresses;

		$Itemid               = $this->input->getInt('Itemid');

		if (isset($billingaddresses))
		{
			if (isset($billingaddresses->country_code))
			{
				$billingaddresses->country_2_code = $redconfig->getCountryCode2($billingaddresses->country_code);
				$d ["billingaddress"]->country_2_code = $billingaddresses->country_2_code;
			}

			if (isset($billingaddresses->state_code))
			{
				$billingaddresses->state_2_code = $billingaddresses->state_code;
				$d ["billingaddress"]->state_2_code = $billingaddresses->state_2_code;
			}
		}

		if (isset($shippingaddresses))
		{
			if (isset($shippingaddresses->country_code))
			{
				$shippingaddresses->country_2_code = $redconfig->getCountryCode2($shippingaddresses->country_code);
				$d ["shippingaddress"]->country_2_code = $shippingaddresses->country_2_code;
			}

			if (isset($shippingaddresses->state_code))
			{
				$shippingaddresses->state_2_code = $shippingaddresses->state_code;
				$d ["shippingaddress"]->state_2_code = $shippingaddresses->state_2_code;
			}
		}

		// Get  data for credit card
		$ccdata['order_payment_name']         = $request['order_payment_name'];
		$ccdata['creditcard_code']            = $request['creditcard_code'];
		$ccdata['order_payment_number']       = $request['order_payment_number'];
		$ccdata['order_payment_expire_month'] = $request['order_payment_expire_month'];
		$ccdata['order_payment_expire_year']  = $request['order_payment_expire_year'];
		$ccdata['credit_card_code']           = $request['credit_card_code'];
		$ccdata['selectedCardId']             = $this->input->getString('selectedCard');

		// Create session
		$session->set('ccdata', $ccdata);
		$ccdata = $session->get('ccdata');

		$values['order_shipping'] = $order->order_shipping;
		$values['order_number']   = $request['order_id'];
		$values['order_tax']      = $order->order_tax;
		$values['shippinginfo']   = $d ["shippingaddress"];
		$values['billinginfo']    = $d ["billingaddress"];
		$values['order_total']    = $order->order_total;
		$values['order_subtotal'] = $order->order_subtotal;
		$values["order_id"]       = $request['order_id'];
		$values['payment_plugin'] = $request['payment_method_id'];
		$values['order']          = $order;

		// Call payment plugin
		JPluginHelper::importPlugin('redshop_payment');
		$dispatcher = RedshopHelperUtility::getDispatcher();

		$results = $dispatcher->trigger('onPrePayment_' . $values['payment_plugin'], array($values['payment_plugin'], $values));
		$paymentResponse = $results[0];

		$paymentResponse->log = $paymentResponse->message;
		$paymentResponse->msg = $paymentResponse->message;

		if ($paymentResponse->responsestatus == "Success" || $values['payment_plugin'] == "")
		{
			$paymentResponse->order_status_code = (isset($paymentResponse->status)) ? $paymentResponse->status : 'C';
			$paymentResponse->order_payment_status_code = (isset($paymentResponse->paymentStatus)) ? $paymentResponse->paymentStatus : 'Paid';
			$paymentResponse->order_id = $request['order_id'];

			// Change order status
			$this->_order_functions->changeorderstatus($paymentResponse);
		}

		// Update order payment table with  credit card details
		$model->update_ccdata($request['order_id'], $paymentResponse->transaction_id);
		$model->resetcart();

		$link = 'index.php?option=com_redshop&view=order_detail&Itemid=' . $Itemid . '&oid=' . $request['order_id'];
		$app->redirect(JRoute::_($link, false), $paymentResponse->message);

	}

	/**
	 * Notify payment function
	 *
	 * @return  void
	 */
	public function notify_payment()
	{
		$app     = JFactory::getApplication();
		$db      = JFactory::getDbo();
		$request = $this->input->getArray();
		$Itemid  = $this->input->getInt('Itemid');
		$objOrder = order_functions::getInstance();

		JPluginHelper::importPlugin('redshop_payment');
		$dispatcher = RedshopHelperUtility::getDispatcher();

		$results = $dispatcher->trigger(
			'onNotifyPayment' . $request['payment_plugin'],
			array(
				$request['payment_plugin'],
				$request
			)
		);

		$msg = $results[0]->msg;

		if (array_key_exists("order_id_temp", $results[0]))
		{
			$order_id = $results[0]->order_id_temp;
		}
		else
		{
			$order_id = $results[0]->order_id;
		}

		// Change Order Status based on resutls
		$objOrder->changeorderstatus($results[0]);

		$model     = $this->getModel('order_detail');
		$resetcart = $model->resetcart();

		/*
		 * Plugin will trigger onAfterNotifyPayment
		 */
		$dispatcher->trigger(
			'onAfterNotifyPayment' . $request['payment_plugin'],
			array(
				$request['payment_plugin'],
				$order_id
			)
		);

		JPluginHelper::importPlugin('system');
		$dispatcher->trigger('afterOrderNotify', array($results));

		if ($request['payment_plugin'] == "rs_payment_payer")
		{
			die("TRUE");
		}

		if ($request['payment_plugin'] != "rs_payment_worldpay")
		{
			// New checkout flow
			$redirect_url = JRoute::_(
			        JUri::base() . "index.php?option=com_redshop&view=order_detail&layout=receipt&Itemid=$Itemid&oid=" . $order_id, false
            );

			$this->setRedirect($redirect_url, $msg);
		}
	}

	/**
	 * Copy Order Item to Cart
	 *
	 * @param   array    $row       Order Item information if not empty
	 * @param   boolean  $redirect  If true will redirect to cart else not.
	 *
	 * @return  mixed    void / boolean
	 */
	public function copyOrderItemToCart($row = array(), $redirect = true)
	{
		// Import redSHOP Product Plugin
		JPluginHelper::importPlugin('redshop_product');
		$dispatcher = RedshopHelperUtility::getDispatcher();
		$app        = JFactory::getApplication();

		// If empty then load order item detail from order table
		if (empty($row))
		{
			$order_item_id = $this->input->getInt('order_item_id');

			$orderItem = $this->_order_functions->getOrderItemDetail(0, 0, $order_item_id);
			$row = (array) $orderItem[0];
		}

		// Event Trigger on reordering cart item
		$dispatcher->trigger('onReorderCartItem', array(&$row));

		$subscription_id = 0;
		$row['quantity'] = $row['product_quantity'];

		if ($row['is_giftcard'] == 1)
		{
			$row['giftcard_id']   = $row['product_id'];
			$row['reciver_name']  = $row['giftcard_user_name'];
			$row['reciver_email'] = $row['giftcard_user_email'];
		}
		else
		{
			$product_data = $this->_producthelper->getProductById($row['product_id']);

			if ($product_data->product_type == 'subscription')
			{
				$productSubscription = $this->_producthelper->getUserProductSubscriptionDetail($row['order_item_id']);

				if ($productSubscription->subscription_id != "")
				{
					$subscription_id = $productSubscription->subscription_id;
				}
			}

			$generateAttributeCart = $this->_carthelper->generateAttributeFromOrder($row['order_item_id'], 0, $row['product_id'], $row['product_quantity']);
			$generateAccessoryCart = $this->_carthelper->generateAccessoryFromOrder($row['order_item_id'], $row['product_id'], $row['product_quantity']);

			$row['cart_attribute']  = $generateAttributeCart;
			$row['cart_accessory']  = $generateAccessoryCart;
			$row['subscription_id'] = $subscription_id;
			$row['sel_wrapper_id']  = $row['wrapper_id'];
			$row['category_id']     = 0;

			if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "orderMergeImages/" . $row['attribute_image']))
			{
				$newMedia = JPATH_ROOT . '/components/com_redshop/assets/images/mergeImages/' . $row['attribute_image'];
				$oldMedia = JPATH_ROOT . '/components/com_redshop/assets/images/orderMergeImages/' . $row['attribute_image'];
				copy($oldMedia, $newMedia);
			}

			$row['attributeImage'] = $row['attribute_image'];

			if (JFile::exists(JPATH_COMPONENT_SITE . "/assets/images/product_attributes/" . $row['attribute_image']))
			{
				$row['hidden_attribute_cartimage'] = REDSHOP_FRONT_IMAGES_ABSPATH . "product_attributes/" . $row['attribute_image'];
			}
		}

		$result = $this->_carthelper->addProductToCart($row);

		if (is_bool($result) && $result)
		{
			// Set success message for product line
			$app->enqueueMessage($row['order_item_name'] . ": " . JText::_("COM_REDSHOP_PRODUCT_ADDED_TO_CART"));

			if ($redirect)
			{
				// Do final cart calculations
				$this->_carthelper->cartFinalCalculation();

				$app->redirect(JRoute::_('index.php?option=com_redshop&view=cart&Itemid=' . RedshopHelperUtility::getCartItemId(), false));
			}
		}
		else
		{
			$ItemData = $this->_producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $row['product_id']);

			if (count($ItemData) > 0)
			{
				$Itemid = $ItemData->id;
			}
			else
			{
				$Itemid = RedshopHelperUtility::getItemId($row['product_id']);
			}

			$errorMessage = ($result) ? $result : JText::_("COM_REDSHOP_PRODUCT_NOT_ADDED_TO_CART");

			if (JError::isError(JError::getError()))
			{
				$errorMessage = JError::getError()->getMessage();
			}

			$app->redirect(
				JRoute::_('index.php?option=com_redshop&view=product&pid=' . $row['product_id'] . '&Itemid=' . $Itemid, false),
				$errorMessage
			);
		}
	}

	/**
	 * On Reorder Order
	 *
	 * @return  void
	 */
	public function reorder()
	{
		$app     = JFactory::getApplication();
		$orderId = $this->input->getInt('order_id');

		if ($orderId)
		{
			// First Empty Cart and then oder it again
			$cart['idx'] = 0;
			JFactory::getSession()->set('cart', $cart);

			$orderItem = $this->_order_functions->getOrderItemDetail($orderId);

			for ($i = 0, $in = count($orderItem); $i < $in; $i++)
			{
				$row = (array) $orderItem[$i];

				// Copy Order Item to cart
				$this->copyOrderItemToCart($row, false);
			}

			$this->_carthelper->cartFinalCalculation();
		}

		$app->redirect(JRoute::_('index.php?option=com_redshop&view=cart&Itemid=' . RedshopHelperUtility::getCartItemId(), false));
	}

	/**
	 *  payment function
	 *
	 * @return void
	 */
	public function payment()
	{
		$app       = JFactory::getApplication();
		$redconfig = Redconfiguration::getInstance();
		$Itemid    = $this->input->getInt('Itemid');
		$order_id  = $this->input->getInt('order_id');

		$order       = $this->_order_functions->getOrderDetails($order_id);
		$paymentInfo = $this->_order_functions->getOrderPaymentDetail($order_id);

		if (count($paymentInfo) > 0)
		{
			$paymentmethod = $this->_order_functions->getPaymentMethodInfo($paymentInfo[0]->payment_method_class);

			if (count($paymentmethod) > 0)
			{
				$paymentparams = new JRegistry($paymentmethod[0]->params);
				$is_creditcard = $paymentparams->get('is_creditcard', 0);

				if ($is_creditcard)
				{
					JHtml::script('com_redshop/credit_card.js', false, true);    ?>

				<form action="<?php echo JRoute::_('index.php?option=com_redshop&view=checkout', false) ?>" method="post"
				      name="adminForm" id="adminForm" enctype="multipart/form-data"
				      onsubmit="return CheckCardNumber(this);">
					<?php echo $cardinfo = $this->_carthelper->replaceCreditCardInformation($paymentInfo[0]->payment_method_class); ?>
					<div>
						<input type="hidden" name="option" value="com_redshop"/>
						<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
						<input type="hidden" name="task" value="process_payment" />
						<input type="hidden" name="view" value="order_detail"/>
						<input type="submit" name="submit" class="greenbutton btn btn-primary"
						       value="<?php echo JText::_('COM_REDSHOP_PAY'); ?>"/>
						<input type="hidden" name="ccinfo" value="1"/>
						<input type="hidden" name="users_info_id" value="<?php echo $order->user_info_id; ?>"/>
						<input type="hidden" name="order_id" value="<?php echo $order->order_id; ?>"/>
						<input type="hidden" name="payment_method_id"
						       value="<?php echo $paymentInfo[0]->payment_method_class; ?>"/>
					</div>
					</form>
				<?php
				}
				else
				{
					$link = 'index.php?option=com_redshop&view=order_detail&layout=checkout_final&oid=' . $order_id . '&Itemid=' . $Itemid;
					$this->setRedirect($link);
				}
			}
		}
	}

	/**
	 * Get order payament status using ajax
	 */
	public function AjaxOrderPaymentStatusCheck()
	{
		$orderId = $this->input->post->getInt('id');

		$orderPaymentStatus = RedshopEntityOrder::load($orderId)->get('order_payment_status');

		$status = JText::_('COM_REDSHOP_PAYMENT_STA_UNPAID');

		if ($orderPaymentStatus == 'Paid')
		{
			$status = JText::_('COM_REDSHOP_PAYMENT_STA_PAID');
		}

		ob_clean();
		echo $status;
	}
}
