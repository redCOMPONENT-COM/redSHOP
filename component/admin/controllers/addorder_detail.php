<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Add order detail controller
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller.Addorder_detail
 * @since       2.0.6
 */
class RedshopControllerAddorder_detail extends RedshopController
{
	/**
	 * RedshopControllerAddorder_detail constructor.
	 *
	 * @param   array $default Default
	 */
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->input->set('hidemainmenu', 1);
	}

	/**
	 * Save and pay
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public function savepay()
	{
		$this->save(1);
	}

	/**
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public function save_without_sendmail()
	{
		$this->save();
	}

	/**
	 * @param   int $apply Apply
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public function save($apply = 0)
	{
		$post = $this->input->post->getArray();

		$adminproducthelper = RedshopAdminProduct::getInstance();
		$order_functions    = order_functions::getInstance();

		$cid                  = $this->input->post->get('cid', array(0), 'array');
		$post ['order_id']    = $cid [0];
		$model                = $this->getModel('addorder_detail');
		$post['order_number'] = RedshopHelperOrder::generateOrderNumber();

		$orderItem          = $adminproducthelper->redesignProductItem($post);
		$post['order_item'] = $orderItem;

		if (empty($orderItem[0]->product_id))
		{
			$msg = JText::_('COM_REDSHOP_PLEASE_SELECT_PRODUCT');
			$this->setRedirect(
				'index.php?option=com_redshop&view=addorder_detail&user_id=' .
				$post['user_id'] .
				'&shipping_users_info_id=' .
				$post['shipp_users_info_id']
				, $msg
				, 'warning');

			return;
		}

		// Check product Quantity
		$stocknote = '';

		if (Redshop::getConfig()->get('USE_STOCKROOM') == 1)
		{
			$stockroomhelper = rsstockroomhelper::getInstance();

			for ($i = 0, $n = count($orderItem); $i < $n; $i++)
			{
				$quantity    = $orderItem[$i]->quantity;
				$productData = Redshop::product((int) $orderItem[$i]->product_id);

				if ($productData->min_order_product_quantity > 0 && $productData->min_order_product_quantity > $quantity)
				{
					$msg       = $productData->product_name . " " . JText::_('WARNING_MSG_MINIMUM_QUANTITY');
					$stocknote .= sprintf($msg, $productData->min_order_product_quantity) . "<br/>";
					$quantity  = $productData->min_order_product_quantity;
				}
				$currentStock  = $stockroomhelper->getStockroomTotalAmount($orderItem[$i]->product_id);
				$finalquantity = ($currentStock >= $quantity) ? (int) $quantity : (int) $currentStock;

				if ($finalquantity > 0)
				{
					if ($productData->max_order_product_quantity > 0 && $productData->max_order_product_quantity < $finalquantity)
					{
						$msg           = $productData->product_name . " " . JText::_('WARNING_MSG_MAXIMUM_QUANTITY') . "<br/>";
						$stocknote     .= sprintf($msg, $productData->max_order_product_quantity);
						$finalquantity = $productData->max_order_product_quantity;
					}

					$orderItem[$i]->quantity = $finalquantity;
				}
				else
				{
					$stocknote .= $productData->product_name . " " . JText::_('COM_REDSHOP_PRODUCT_OUT_OF_STOCK') . "<br/>";
					unset($orderItem[$i]);
				}
			}

			$orderItem = array_merge(array(), $orderItem);

			if (count($orderItem) <= 0)
			{
				$msg = JText::_('COM_REDSHOP_PRODUCT_OUT_OF_STOCK');
				$this->setRedirect('index.php?option=com_redshop&view=addorder_detail&user_id=' . $post['user_id']
					. '&shipping_users_info_id=' . $post['shipp_users_info_id']
					, $msg
					, 'warning'
				);

				return;
			}
		}

		$order_total = $post['order_total'];

		$order_shipping = RedshopShippingRate::decrypt($post['shipping_rate_id']);

		if (count($order_shipping) > 4)
		{
			$post['order_shipping']     = $order_shipping[3];
			$order_total                = $order_total + $order_shipping[3];
			$post['order_shipping_tax'] = $order_shipping[6];
		}

		$tmporder_total = $order_total;

		if (array_key_exists("issplit", $post) && $post['issplit'])
		{
			$tmporder_total = $order_total / 2;
		}

		$paymentmethod                            = $order_functions->getPaymentMethodInfo($post['payment_method_class']);
		$paymentmethod                            = $paymentmethod[0];
		$paymentparams                            = new JRegistry($paymentmethod->params);
		$paymentinfo                              = new stdclass;
		$post['economic_payment_terms_id']        = $paymentparams->get('economic_payment_terms_id');
		$post['economic_design_layout']           = $paymentparams->get('economic_design_layout');
		$paymentinfo->payment_price               = $paymentparams->get('payment_price', '');
		$paymentinfo->is_creditcard               = $post['economic_is_creditcard'] = $paymentparams->get('is_creditcard', '');
		$paymentinfo->payment_oprand              = $paymentparams->get('payment_oprand', '');
		$paymentinfo->accepted_credict_card       = $paymentparams->get("accepted_credict_card");
		$paymentinfo->payment_discount_is_percent = $paymentparams->get('payment_discount_is_percent', '');

		$cartHelper = rsCarthelper::getInstance();

		$subtotal        = $post['order_subtotal'];
		$update_discount = 0;

		if ($post['update_discount'] > 0)
		{
			$update_discount = $post['update_discount'];

			if ($update_discount > $subtotal)
			{
				$update_discount = $subtotal;
			}

			if ($update_discount != 0)
			{
				$order_total = $order_total - $update_discount;
			}
		}

		$special_discount = $post['special_discount'];

		$subtotal_excl_vat = 0;

		for ($i = 0, $in = count($orderItem); $i < $in; $i++)
		{
			$subtotal_excl_vat = $subtotal_excl_vat + ($orderItem[$i]->prdexclprice * $orderItem[$i]->quantity);
		}

		if (Redshop::getConfig()->get('APPLY_VAT_ON_DISCOUNT'))
		{
			$amt = $subtotal;
		}
		else
		{
			$amt = $subtotal_excl_vat;
		}

		$discount_price                  = ($amt * $special_discount) / 100;
		$post['special_discount']        = $special_discount;
		$post['special_discount_amount'] = $discount_price;

		$order_total = $order_total - $discount_price;

		if (Redshop::getConfig()->get('PAYMENT_CALCULATION_ON') == 'subtotal')
		{
			$paymentAmount = $subtotal;
		}
		else
		{
			$paymentAmount = $order_total;
		}

		$paymentMethod                = $cartHelper->calculatePayment($paymentAmount, $paymentinfo, $order_total);
		$post['ship_method_id']       = urldecode(urldecode($post['shipping_rate_id']));
		$order_total                  = $paymentMethod[0];
		$post['user_info_id']         = $post['users_info_id'];
		$post['payment_discount']     = $paymentMethod[1];
		$post['payment_oprand']       = $paymentinfo->payment_oprand;
		$post['order_discount']       = $update_discount;
		$post['order_total']          = $order_total;
		$post['order_payment_amount'] = $tmporder_total;
		$post['order_payment_name']   = $paymentmethod->name;

		// Save + Pay button pressed
		if ($apply == 1)
		{
			$post['order_payment_status'] = empty($post['order_payment_status']) ? REDSHOP_ORDER_PAYMENT_STATUS_UNPAID : $post['order_payment_status'];
			$post['order_status']         = empty($post['order_status']) ? REDSHOP_ORDER_STATUS_PAID : $post['order_status'];
		}

		if ($row = $model->store($post))
		{
			$msg  = JText::_('COM_REDSHOP_ORDER_DETAIL_SAVED');
			$type = 'success';
		}
		else
		{
			$msg  = JText::_('COM_REDSHOP_ERROR_SAVING_ORDER_DETAIL');
			$type = 'error';
		}

		if ($apply == 1)
		{
			$objorder = order_functions::getInstance();
			$objorder->getpaymentinformation($row, $post);
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=order', $msg . $stocknote, $type);
		}
	}

	/**
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public function cancel()
	{
		$msg = JText::_('COM_REDSHOP_ORDER_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=com_redshop&view=order', $msg);
	}

	public function guestuser()
	{
		$post = $this->input->post->getArray();

		if (!isset($post['billisship']))
		{
			$this->input->set('billisship', 0);
		}

		/** @var RedshopModelAddorder_detail $model */
		$model = $this->getModel('addorder_detail');

		if ($row = $model->storeShipping($post))
		{
			$this->setRedirect(
				'index.php?option=com_redshop&view=addorder_detail&user_id=' . $row->user_id . '&shipping_users_info_id=' . $row->users_info_id
			);
		}

		JFactory::getApplication()->setUserState('com_redshop.addorder_detail.guestuser.username', $this->input->getUsername('username'));

		parent::display();
	}

	/**
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public function changeshippingaddress()
	{
		$shippingadd_id = $this->input->getInt('shippingadd_id', 0);
		$user_id        = $this->input->getInt('user_id', 0);
		$is_company     = $this->input->getInt('is_company', 0);
		$model          = $this->getModel('addorder_detail');

		$htmlshipping = $model->changeshippingaddress($shippingadd_id, $user_id, $is_company);

		echo $htmlshipping;
		JFactory::getApplication()->close();
	}

	/**
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public function getShippingRate()
	{
		$get                  = $this->input->get->getArray();
		$shipping             = RedshopShippingRate::decrypt($get['shipping_rate_id']);
		$order_shipping_class = '';
		$order_shipping       = 0;
		$order_shipping_tax   = '';

		if (count($shipping) > 4)
		{
			$order_shipping       = $shipping[3] - $shipping[6];
			$order_shipping_tax   = $shipping[6];
			$order_shipping_class = $shipping[0];
		}

		echo "<div id='resultShippingClass'>" . $order_shipping_class . "</div>";
		echo "<div id='resultShipping'>" . $order_shipping . "</div>";
		echo "<div id='resultShippingVat'>" . $order_shipping_tax . "</div>";
		JFactory::getApplication()->close();
	}
}
