<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.controller');

require_once JPATH_ROOT . '/components/com_redshop/helpers/product.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/mail.php';
require_once JPATH_COMPONENT . '/helpers/product.php';

class order_detailController extends JController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		JRequest::setVar('view', 'order_detail');
		JRequest::setVar('layout', 'default');
		JRequest::setVar('hidemainmenu', 1);
		parent::display();
	}

	public function save()
	{
		$post = JRequest::get('post');

		$text_field = JRequest::getVar('text_field', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post["text_field"] = $text_field;

		$option = JRequest::getVar('option', '', 'request', 'string');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		$post ['order_id'] = $cid [0];

		$model = $this->getModel('order_detail');

		if ($model->store($post))
		{
			$msg = JText::_('COM_REDSHOP_ORDER_DETAIL_SAVED');
		}

		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_ORDER_DETAIL');
		}

		$this->setRedirect('index.php?option=' . $option . '&view=order', $msg);
	}

	public function remove()
	{
		$option = JRequest::getVar('option', '', 'request', 'string');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('order_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_ORDER_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=order', $msg);
	}

	public function cancel()
	{
		$option = JRequest::getVar('option', '', 'request', 'string');
		$msg = JText::_('COM_REDSHOP_ORDER_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=' . $option . '&view=order', $msg);
	}

	public function neworderitem()
	{
		$adminproducthelper = new adminproducthelper;
		$stockroomhelper = new rsstockroomhelper;
		$post = JRequest::get('post');
		$tmpl = "";

		if (isset($post['tmpl']))
		{
			$tmpl = $post['tmpl'];
		}

		$option = JRequest::getVar('option', '', 'request', 'string');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		$order_item_id = JRequest::getVar('order_item_id', 0, 'post', '');

		$model = $this->getModel('order_detail');

		$orderItem = $adminproducthelper->redesignProductItem($post);
		$post['order_item'] = $orderItem;

		$product_id = $orderItem[0]->product_id;
		$finalquantity = $quantity = $orderItem[0]->quantity;

		// Check product Quantity
		if (USE_STOCKROOM == 1)
		{
			$currentStock = $stockroomhelper->getStockroomTotalAmount($product_id);

			if ($currentStock >= $quantity)
			{
				$finalquantity = (int) $quantity;
			}
			else
			{
				$finalquantity = (int) $currentStock;
			}
		}

		if ($finalquantity > 0)
		{
			if ($model->neworderitem($post, $finalquantity, $order_item_id))
			{
				if ($order_item_id == 0)
				{
					$msg = JText::_('COM_REDSHOP_ORDER_ITEM_ADDED');
				}
				else
				{
					$msg = JText::_('COM_REDSHOP_QUANTITY_UPDATED');
				}
			}
			else
			{
				if ($order_item_id == 0)
				{
					$msg = JText::_('COM_REDSHOP_ERROR_ADDING_ORDER_ITEM');
				}

				else
				{
					$msg = JText::_('COM_REDSHOP_ERROR_UPDATING_QUANTITY');
				}
			}
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_PRODUCT_OUT_OF_STOCK');
		}

		if ($tmpl)
		{
			$this->setRedirect('index.php?option=' . $option . '&view=order_detail&cid[]=' . $cid[0] . '&tmpl=' . $tmpl, $msg);
		}

		else
		{
			$this->setRedirect('index.php?option=' . $option . '&view=order_detail&cid[]=' . $cid[0], $msg);
		}
	}

	public function delete_item()
	{
		$post = JRequest::get('post');
		$option = JRequest::getVar('option', '', 'request', 'string');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		$model = $this->getModel('order_detail');

		$order_functions = new order_functions;
		$orderItem = $order_functions->getOrderItemDetail($cid[0]);

		if (count($orderItem) == 1 && $orderItem[0]->order_item_id == $post['order_item_id'])
		{
			$model->delete($cid);
			$msg = JText::_('COM_REDSHOP_ORDER_DELETED_SUCCESSFULLY');

			$this->setRedirect('index.php?option=' . $option . '&view=order', $msg);

			return;
		}

		if ($model->delete_item($post))
		{
			$msg = JText::_('COM_REDSHOP_ORDER_ITEM_DELETED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_DELETING_ORDER_ITEM');
		}

		$this->setRedirect('index.php?option=' . $option . '&view=order_detail&cid[]=' . $cid[0], $msg);
	}

	public function updateItem()
	{
		$post = JRequest::get('post');
		$option = JRequest::getVar('option', '', 'request', 'string');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		$model = $this->getModel('order_detail');

		if ($model->updateItem($post))
		{
			$msg = JText::_('COM_REDSHOP_ORDER_ITEM_PRICE_UPDATED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_UPDATING_PRICE');
		}

		$this->setRedirect('index.php?option=' . $option . '&view=order_detail&cid[]=' . $cid[0], $msg);
	}

	public function update_discount()
	{
		$post = JRequest::get('post');
		$option = JRequest::getVar('option', '', 'request', 'string');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		$model = $this->getModel('order_detail');

		if ($model->update_discount($post))
		{
			$msg = JText::_('COM_REDSHOP_DISCOUNT_UPDATED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_UPDATING_DISCOUNT');
		}

		$this->setRedirect('index.php?option=' . $option . '&view=order_detail&cid[]=' . $cid[0], $msg);
	}

	public function special_discount()
	{
		$post = JRequest::get('post');
		$option = JRequest::getVar('option', '', 'request', 'string');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		$model = $this->getModel('order_detail');

		if ($model->special_discount($post))
		{
			$msg = JText::_('COM_REDSHOP_SPECIAL_DISCOUNT_APPLIED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_IN_SPECIAL_DISCOUNT');
		}

		$this->setRedirect('index.php?option=' . $option . '&view=order_detail&cid[]=' . $cid[0], $msg);
	}

	public function update_shippingrates()
	{
		$post = JRequest::get('post');
		$option = JRequest::getVar('option', '', 'request', 'string');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		$model = $this->getModel('order_detail');

		if ($model->update_shippingrates($post))
		{
			$msg = JText::_('COM_REDSHOP_SHIPPING_RATE_UPDATED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_UPDATING_SHIPPING_RATE');
		}

		$this->setRedirect('index.php?option=' . $option . '&view=order_detail&cid[]=' . $cid[0], $msg);
	}

	public function updateShippingAdd()
	{
		$post = JRequest::get('post');
		$suboption = JRequest::getVar('suboption', 'com_redshop', 'request', 'string');
		$view = ($suboption == 'com_redshop') ? 'order_detail' : 'order';

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		$post['order_id'] = $cid[0];

		$model = $this->getModel();

		if ($model->updateShippingAdd($post))
		{
			$msg = JText::_('COM_REDSHOP_SHIPPING_INFORMATION_UPDATED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_UPDATING_SHIPPING_INFORMATION');
		}

		?>
    <script type="text/javascript">

        window.parent.document.location = "index.php?option=<?php echo $suboption;?>&view=<?php echo $view;?>&cid[]=<?php echo $cid[0];?>";

        window.close();
    </script>
	<?php
		exit;
	}

	public function updateBillingAdd()
	{
		$post = JRequest::get('post');
		$option = JRequest::getVar('option', '', 'request', 'string');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		$post['order_id'] = $cid[0];

		$model = $this->getModel();

		if ($model->updateBillingAdd($post))
		{
			$msg = JText::_('COM_REDSHOP_BILLING_INFORMATION_UPDATED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_UPDATING_BILLING_INFORMATION');
		}

		?>
    <script type="text/javascript">

        window.parent.document.location = "index.php?option=com_redshop&view=order_detail&cid[]=<?php echo $cid[0];?>";

        window.close();
    </script>
	<?php
		exit;
	}

	public function createpdf()
	{
		$view = $this->getView('order_detail', 'tcpdf');
		parent::display();
	}

	public function createpdfstocknote()
	{
		$view = $this->getView('order_detail', 'stocknotepdf');
		parent::display();
	}

	public function send_downloadmail()
	{
		$option = JRequest::getVar('option', '', 'request', 'string');
		$cid = JRequest::getVar('cid', array(0), 'get', 'array');
		$tmpl = JRequest::getVar('tmpl', '', 'request', 'string');
		$model = $this->getModel();

		if ($model->send_downloadmail($cid[0]))
		{
			$msg = JText::_('COM_REDSHOP_DOWNLOAD_MAIL_HAS_BEEN_SENT');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_DOWNLOAD_MAIL_FAIL');
		}

		if ($tmpl)
		{
			$this->setRedirect('index.php?option=' . $option . '&view=order_detail&cid[]=' . $cid[0] . '&tmpl=' . $tmpl, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=' . $option . '&view=order_detail&cid[]=' . $cid[0], $msg);
		}
	}

	public function displayProductItemInfo()
	{
		$adminproducthelper = new adminproducthelper;
		$get = JRequest::get('get');

		$product_id = $get['product'];
		$quantity = $get['quantity'];
		$unique_id = $get['unique_id'];
		$user_id = $get['user_id'];
		$newprice = $get['newprice'];

		$response = $adminproducthelper->getProductItemInfo($product_id, $quantity, $unique_id, $user_id, $newprice);
		echo $response;
		exit;
	}

	public function checkoutnext()
	{
		$app = JFactory::getApplication();
		$session = JFactory::getSession();
		require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/order.php';
		require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/configuration.php';

		$redconfig = new Redconfiguration;
		$model = $this->getModel();
		$order_functions = new order_functions;

		$request = JRequest::get('request');

		if ($request['ccinfo'] == 0)
		{
			$redirect_url = JRoute::_(JURI::base() . "index.php?option=com_redshop&view=order_detail&task=edit&cid[]=" . $request['order_id']);
		}

		$order = $order_functions->getOrderDetails($request['order_id']);

		// Send the order_id and orderpayment_id to the payment plugin so it knows which DB record to update upon successful payment

		$userbillinginfo = $order_functions->getOrderBillingUserInfo($request['order_id']);

		$shippingaddresses = $order_functions->getOrderShippingUserInfo($request['order_id']);

		if (isset($shippingaddresses))
		{
			$shippingaddress = $shippingaddresses;

			$shippingaddress->country_2_code = $redconfig->getCountryCode2($shippingaddress->country_code);

			$shippingaddress->state_2_code = $redconfig->getCountryCode2($shippingaddress->state_code);
		}

		if (isset($shippingaddresses))
		{
			$d ["shippingaddress"] = $shippingaddresses;
			$d ["shippingaddress"]->country_2_code = $redconfig->getCountryCode2($d ["shippingaddress"]->country_code);
			$d ["shippingaddress"]->state_2_code = $redconfig->getCountryCode2($d ["shippingaddress"]->state_code);

			$shippingaddresses->country_2_code = $redconfig->getCountryCode2($d ["shippingaddress"]->country_code);
			$shippingaddresses->state_2_code = $redconfig->getCountryCode2($d ["shippingaddress"]->state_code);
		}

		if (isset($userbillinginfo))
		{
			$d ["billingaddress"] = $userbillinginfo;

			if (isset($userbillinginfo->country_code))
			{
				$d ["billingaddress"]->country_2_code = $redconfig->getCountryCode2($userbillinginfo->country_code);
				$userbillinginfo->country_2_code = $redconfig->getCountryCode2($userbillinginfo->country_code);
			}

			if (isset($userbillinginfo->state_code))
			{
				$d ["billingaddress"]->state_2_code = $redconfig->getCountryCode2($userbillinginfo->state_code);
				$userbillinginfo->state_2_code = $redconfig->getCountryCode2($userbillinginfo->state_code);
			}
		}

		$ccdata['order_payment_name'] = $request['order_payment_name'];
		$ccdata['creditcard_code'] = $request['creditcard_code'];
		$ccdata['order_payment_number'] = $request['order_payment_number'];
		$ccdata['order_payment_expire_month'] = $request['order_payment_expire_month'];
		$ccdata['order_payment_expire_year'] = $request['order_payment_expire_year'];
		$ccdata['credit_card_code'] = $request['credit_card_code'];
		$session->set('ccdata', $ccdata);

		$values['order_shipping'] = $order->order_shipping;
		$values['order_number'] = $request['order_id'];
		$values['order_tax'] = $order->order_tax;
		$values['shippinginfo'] = $d ["shippingaddress"];
		$values['billinginfo'] = $d ["billingaddress"];
		$values['order_total'] = $order->order_total;
		$values['order_subtotal'] = $order->order_subtotal;
		$values["order_id"] = $request['order_id'];
		$values['payment_plugin'] = $request['payment_plugin'];
		$values['order'] = $order;

		JPluginHelper::importPlugin('redshop_payment');
		$dispatcher = JDispatcher::getInstance();

		$results = $dispatcher->trigger('onPrePayment_' . $values['payment_plugin'], array($values['payment_plugin'], $values));
		$paymentResponse = $results[0];

		if ($paymentResponse->responsestatus == "Success" || $values['payment_plugin'] == "")
		{
			$paymentResponse->log = $paymentResponse->message;
			$paymentResponse->msg = $paymentResponse->message;
			$paymentResponse->order_status_code = 'C';
			$paymentResponse->order_payment_status_code = 'Paid';
			$paymentResponse->order_id = $request['order_id'];

			$order_functions->changeorderstatus($paymentResponse);
		}

		// Update order payment table with  credit card details

		$model->update_ccdata($request['order_id'], $paymentResponse->transaction_id);

		$redirect_url = JRoute::_(JURI::base() . "index.php?option=com_redshop&view=order_detail&task=edit&cid[]=" . $request['order_id']);
		$app->redirect($redirect_url, $paymentResponse->message);
	}

	/*
	 * Notify payment function
	 */
	public function notify_payment()
	{
		$app = JFactory::getApplication();
		$request = JRequest::get('request');

		require_once JPATH_BASE . '/components/com_redshop/helpers/order.php';
		$objOrder = new order_functions;

		JPluginHelper::importPlugin('redshop_payment');
		$dispatcher = JDispatcher::getInstance();

		$results = $dispatcher->trigger('onNotifyPayment' . $request['payment_plugin'], array($request['payment_plugin'], $request));

		$msg = $results[0]->msg;
		$objOrder->changeorderstatus($results[0]);
		$redirect_url = JRoute::_(JURI::base() . "index.php?option=com_redshop&view=order_detail&task=edit&cid[]=" . $request['orderid']);
		$app->redirect($redirect_url, $msg);
	}

	public function send_invoicemail()
	{
		$redshopMail = new redshopMail;

		$option = JRequest::getVar('option', '', 'request', 'string');
		$cid = JRequest::getVar('cid', array(0), 'get', 'array');
		$tmpl = JRequest::getVar('tmpl', '', 'request', 'string');

		if ($redshopMail->sendInvoiceMail($cid[0]))
		{
			$msg = JText::_('COM_REDSHOP_INVOICE_MAIL_HAS_BEEN_SENT');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_INVOICE_MAIL_FAIL');
		}

		if ($tmpl)
		{
			$this->setRedirect('index.php?option=' . $option . '&view=order_detail&cid[]=' . $cid[0] . '&tmpl=' . $tmpl, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=' . $option . '&view=order_detail&cid[]=' . $cid[0], $msg);
		}
	}
}
