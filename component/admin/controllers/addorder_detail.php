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

		$cid                  = $this->input->post->get('cid', array(0), 'array');
		$post ['order_id']    = $cid [0];
		$model                = $this->getModel('addorder_detail');
		$post['order_number'] = RedshopHelperOrder::generateOrderNumber();

		$orderItem          = RedshopHelperProduct::redesignProductItem($post);
		$post['order_item'] = $orderItem;

		if (empty($orderItem[0]->product_id))
		{
			$msg = JText::_('COM_REDSHOP_PLEASE_SELECT_PRODUCT');
			$this->setRedirect(
				'index.php?option=com_redshop&view=addorder_detail&user_id=' .
				$post['user_id'] .
				'&shipping_users_info_id=' .
				$post['shipp_users_info_id'],
				$msg,
				'warning'
			);

			return;
		}

		// Check product Quantity
		$stockNote                = '';

		if (Redshop::getConfig()->get('USE_STOCKROOM') == 1)
		{
			for ($i = 0, $n = count($orderItem); $i < $n; $i++)
			{
				$quantity    = $orderItem[$i]->quantity;
				$productData = Redshop::product((int) $orderItem[$i]->product_id);

				if ($productData->min_order_product_quantity > 0 && $productData->min_order_product_quantity > $quantity)
				{
					$msg       = $productData->product_name . ' ' . JText::_('WARNING_MSG_MINIMUM_QUANTITY');
					$stockNote .= sprintf($msg, $productData->min_order_product_quantity) . "<br/>";
					$quantity  = $productData->min_order_product_quantity;
				}

				$currentStock  = RedshopHelperStockroom::getStockroomTotalAmoun($orderItem[$i]->product_id);
				$finalquantity = ($currentStock >= $quantity) ? (int) $quantity : (int) $currentStock;

				if ($finalquantity > 0)
				{
					if ($productData->max_order_product_quantity > 0 && $productData->max_order_product_quantity < $finalquantity)
					{
						$msg           = $productData->product_name . " " . JText::_('WARNING_MSG_MAXIMUM_QUANTITY') . "<br/>";
						$stockNote     .= sprintf($msg, $productData->max_order_product_quantity);
						$finalquantity = $productData->max_order_product_quantity;
					}

					$orderItem[$i]->quantity = $finalquantity;
				}
				else
				{
					$stockNote .= $productData->product_name . ' ' . JText::_('COM_REDSHOP_PRODUCT_OUT_OF_STOCK') . "<br/>";
					unset($orderItem[$i]);
				}
			}

			$orderItem = array_merge(array(), $orderItem);

			if (count($orderItem) <= 0)
			{
				$msg = JText::_('COM_REDSHOP_PRODUCT_OUT_OF_STOCK');
				$this->setRedirect('index.php?option=com_redshop&view=addorder_detail&user_id=' . $post['user_id']
					. '&shipping_users_info_id=' . $post['shipp_users_info_id'],
					$msg,
					'warning'
				);

				return;
			}
		}

		$orderTotal = $post['order_total'];

		$orderShipping = RedshopShippingRate::decrypt($post['shipping_rate_id']);

		if (count($orderShipping) > 4)
		{
			$post['order_shipping']     = $orderShipping[3];
			$orderTotal                = $orderTotal + $orderShipping[3];
			$post['order_shipping_tax'] = $orderShipping[6];
		}

		$tmpOrderTotal = $orderTotal;

		if (array_key_exists("issplit", $post) && $post['issplit'])
		{
			$tmpOrderTotal = $orderTotal / 2;
		}

		$paymentmethod                            = RedshopHelperOrder::getPaymentMethodInfo($post['payment_method_class']);
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
		$updateDiscount = 0;

		if ($post['update_discount'] > 0)
		{
			$updateDiscount = $post['update_discount'];

			if ($updateDiscount > $subtotal)
			{
				$updateDiscount = $subtotal;
			}

			if ($updateDiscount != 0)
			{
				$orderTotal = $orderTotal - $updateDiscount;
			}
		}

		$specialDiscount = $post['special_discount'];

		$subTotalExcludeVat = 0;

		for ($i = 0, $in = count($orderItem); $i < $in; $i++)
		{
			$subTotalExcludeVat = $subTotalExcludeVat + ($orderItem[$i]->prdexclprice * $orderItem[$i]->quantity);
		}

		if (Redshop::getConfig()->get('APPLY_VAT_ON_DISCOUNT'))
		{
			$amt = $subtotal;
		}
		else
		{
			$amt = $subTotalExcludeVat;
		}

		$discountPrice                   = ($amt * $specialDiscount) / 100;
		$post['special_discount']        = $specialDiscount;
		$post['special_discount_amount'] = $discountPrice;

		$orderTotal = $orderTotal - $discountPrice;

		if (Redshop::getConfig()->get('PAYMENT_CALCULATION_ON') == 'subtotal')
		{
			$paymentAmount = $subtotal;
		}
		else
		{
			$paymentAmount = $orderTotal;
		}

		$paymentMethod                = $cartHelper->calculatePayment($paymentAmount, $paymentinfo, $orderTotal);
		$post['ship_method_id']       = urldecode(urldecode($post['shipping_rate_id']));
		$orderTotal                  = $paymentMethod[0];
		$post['user_info_id']         = $post['users_info_id'];
		$post['payment_discount']     = $paymentMethod[1];
		$post['payment_oprand']       = $paymentinfo->payment_oprand;
		$post['order_discount']       = $updateDiscount;
		$post['order_total']          = $orderTotal;
		$post['order_payment_amount'] = $tmpOrderTotal;
		$post['order_payment_name']   = $paymentmethod->name;

		// Save + Pay button pressed
		if ($apply == 1)
		{
			$post['order_payment_status'] = empty($post['order_payment_status']) ? 'Unpaid' : $post['order_payment_status'];
			$post['order_status'] = empty($post['order_status']) ? 'P' : $post['order_status'];
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
			// @TODO Consider about this method name. get should return value instead of "set"
			RedshopHelperOrder::getPaymentInformation($row, $post);
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=order', $msg . $stockNote, $type);
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
