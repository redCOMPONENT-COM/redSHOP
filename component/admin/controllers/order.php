<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Economic\Economic;


class RedshopControllerOrder extends RedshopController
{
	/**
	 * Method for generate PDF for specific order.
	 *
	 * @return void
	 */
	public function printPDF()
	{
		$app = JFactory::getApplication();
		$orderId = $this->input->getInt('id', 0);

		if (!$orderId)
		{
			$this->setMessage(JText::_('COM_REDSHOP_ORDER_DOWNLOAD_ERROR_MISSING_ORDER_ID'), 'error');
			$this->setRedirect('index.php?option=com_redshop&view=order');
		}

		// Check pdf plugins
		if (!RedshopHelperPdf::isAvailablePdfPlugins())
		{
			$this->setMessage(JText::_('COM_REDSHOP_ERROR_MISSING_PDF_PLUGIN'), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_redshop&view=order'));
		}

		RedshopHelperOrder::generateInvoicePdf($orderId, 'I');

		$app->close();
	}

	public function multiprint_order()
	{
		$orderIds = $this->input->get('cid');

		if (empty($orderIds))
		{
			$this->setMessage(JText::_('COM_REDSHOP_ORDER_DOWNLOAD_ERROR_MISSING_ORDER_ID'), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_redshop&view=order'));
		}

		// Check pdf plugins
		if (!RedshopHelperPdf::isAvailablePdfPlugins())
		{
			$this->setMessage(JText::_('COM_REDSHOP_ERROR_MISSING_PDF_PLUGIN'), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_redshop&view=order', false));
		}

		$invoicePdf = RedshopHelperPdf::createMultiInvoice($orderIds);

		if (empty($invoicePdf))
		{
			$this->setMessage(JText::_('COM_REDSHOP_ERROR_GENERATE_PDF'), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_redshop&view=order', false));
		}

		$invoiceLink = REDSHOP_FRONT_DOCUMENT_ABSPATH . 'invoice/' . $invoicePdf . '.pdf';
		$this->setMessage(JText::sprintf('COM_REDSHOP_ORDER_DOWNLOAD_INVOICE_LINK', $invoiceLink, $invoicePdf . '.pdf'));

		foreach ($orderIds as $orderId)
		{
			if (file_exists(JPATH_COMPONENT_SITE . "/assets/labels/label_" . $orderId . ".pdf"))
			{
				$labelLink = JURI::root() . '/components/com_redshop/assets/labels/label_' . $orderId . '.pdf';
				$this->setMessage(JText::sprintf('COM_REDSHOP_ORDER_DOWNLOAD_LABEL', $labelLink, 'label_' . $orderId . '.pdf'));
			}
		}

		$this->setRedirect(JRoute::_('index.php?option=com_redshop&view=order', false));
	}

	public function cancel()
	{
		$this->setRedirect('index.php?option=com_redshop&view=order');
	}

	public function update_status()
	{
		RedshopHelperOrder::updateStatus();
	}

	/**
	 * Update all Order Status using AJAX
	 *
	 * @param   boolean  $isPacsoft  If true then Pacsoft lable will be created else not
	 *
	 * @return  void
	 */
	public function allstatus($isPacsoft = true)
	{
		ob_end_clean();

		$app = JFactory::getApplication();

		// @todo This needs to be fixed in better way
		$postData              = $this->input->post->getArray();
		$postData['isPacsoft'] = $isPacsoft;

		$app->setUserState("com_redshop.order.batch.postdata", serialize($postData));

		$this->setRedirect('index.php?option=com_redshop&view=order&layout=batch');

		return;
	}

	/**
	 * Update All Order status using AJAX without generating pacsoft label
	 *
	 * @return  void
	 */
	public function allStatusExceptPacsoft()
	{
		$this->allstatus(false);
	}

	/**
	 * Update All Order status AJAX Task
	 *
	 * @return  html  Simply display HTML as AJAX Response
	 */
	public function updateOrderStatus()
	{
		// Force disable error reporting to get clean ajax response
		error_reporting(0);

		$app             = JFactory::getApplication();
		$serialized      = $app->getUserState("com_redshop.order.batch.postdata");
		$post            = unserialize($serialized);
		$orderId         = $this->input->getInt('oid', 0);
		$order_functions = order_functions::getInstance();

		// Change Order Status
		$order_functions->orderStatusUpdate($orderId, $post);

		$response = array(
			'message' => '<li class="success text-success">' . JText::sprintf('COM_REDSHOP_AJAX_ORDER_UPDATE_SUCCESS', $orderId) . '</li>'
		);

		// Trigger when order status changed.
		JPluginHelper::importPlugin('redshop_product');
		JDispatcher::getInstance()->trigger('onAjaxOrderStatusUpdate', array($orderId, $post, &$response));

		ob_clean();
		echo json_encode($response);

		$app->close();
	}

	public function bookInvoice()
	{
		$post            = $this->input->post->getArray();
		$bookInvoiceDate = $post ['bookInvoiceDate'];
		$order_id        = $this->input->getInt('order_id');
		$ecomsg          = JText::_('COM_REDSHOP_INVOICE_NOT_BOOKED_IN_ECONOMIC');
		$msgType         = 'warning';

		// Economic Integration start for invoice generate and book current invoice
		if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1)
		{
			$bookinvoicepdf = Economic::bookInvoiceInEconomic($order_id, 0, $bookInvoiceDate);

			if (JFile::exists($bookinvoicepdf))
			{
				$ecomsg = JText::_('COM_REDSHOP_SUCCESSFULLY_BOOKED_INVOICE_IN_ECONOMIC');
				$msgType = 'message';
				RedshopHelperMail::sendEconomicBookInvoiceMail($order_id, $bookinvoicepdf);
			}
		}

		// End Economic
		$this->setRedirect('index.php?option=com_redshop&view=order', $ecomsg, $msgType);
	}

	public function createInvoice()
	{
		if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1 && Redshop::getConfig()->get('ECONOMIC_INVOICE_DRAFT') != 2)
		{
			$order_id       = $this->input->getInt('order_id');
			$order_function = order_functions::getInstance();
			$paymentInfo    = RedshopHelperOrder::getPaymentInfo($order_id);

			if ($paymentInfo)
			{
				$payment_name = $paymentInfo->payment_method_class;
				$paymentArr = explode("rs_payment_", $paymentInfo->payment_method_class);

				if (count($paymentArr) > 0)
				{
					$payment_name = $paymentArr[1];
				}

				$economicdata['economic_payment_method'] = $payment_name;
				$economicdata['economic_payment_terms_id'] = $paymentInfo->plugin->params->get('economic_payment_terms_id');
				$economicdata['economic_design_layout'] = $paymentInfo->plugin->params->get('economic_design_layout');
				$economicdata['economic_is_creditcard'] = $paymentInfo->plugin->params->get('is_creditcard');
			}

			$economic = economic::getInstance();
			Economic::createInvoiceInEconomic($order_id, $economicdata);

			if (Redshop::getConfig()->get('ECONOMIC_INVOICE_DRAFT') == 0)
			{
				$bookinvoicepdf = Economic::bookInvoiceInEconomic($order_id, 1);

				if (JFile::exists($bookinvoicepdf))
				{
					$ret = RedshopHelperMail::sendEconomicBookInvoiceMail($order_id, $bookinvoicepdf);
				}
			}
		}

		$this->setRedirect('index.php?option=com_redshop&view=order');
	}

	public function export_fullorder_data()
	{
		$extrafile = JPATH_SITE . '/administrator/components/com_redshop/extras/order_export.php';

		if (file_exists($extrafile))
		{
			require_once JPATH_COMPONENT_ADMINISTRATOR . '/extras/order_export.php';
			$orderExport = new orderExport;
			$orderExport->createOrderExport();
			JFactory::getApplication()->close();
		}

		$producthelper = productHelper::getInstance();
		$order_function = order_functions::getInstance();

		$model = $this->getModel('order');
		$data = $model->export_data();
		$product_count = array();
		$db = JFactory::getDbo();

		$where = "";

		$sql = "SELECT order_id,count(order_item_id) as noproduct FROM `#__redshop_order_item`  " . $where . " GROUP BY order_id";

		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-type: text/x-csv");
		header("Content-type: text/csv");
		header("Content-type: application/csv");
		header('Content-Disposition: attachment; filename=Order.csv');

		$db->setQuery($sql);
		$no_products = $db->loadObjectList();

		for ($i = 0, $in = count($data); $i < $in; $i++)
		{
			$product_count [] = $no_products [$i]->noproduct;
		}

		$no_products = max($product_count);

		$shipping_helper = shipping::getInstance();
		ob_clean();

		echo "Order number, Order status, Order date , Shipping method , Shipping user, Shipping address,";
		echo "Shipping postalcode,Shipping city, Shipping country, Company name, Email ,Billing address,";
		echo "Billing postalcode, Billing city, Billing country,Billing User ,";

		for ($i = 1; $i <= $no_products; $i++)
		{
			echo JText::_('COM_REDSHOP_PRODUCT_NAME') . $i . ' ,';
			echo JText::_('COM_REDSHOP_PRODUCT') . ' ' . JText::_('COM_REDSHOP_PRODUCT_PRICE') . $i . ' ,';
			echo JText::_('COM_REDSHOP_PRODUCT_ATTRIBUTE') . $i . ' ,';
		}

		echo "Order Total\n";

		for ($i = 0, $in = count($data); $i < $in; $i++)
		{
			$billing_info = RedshopHelperOrder::getOrderBillingUserInfo($data [$i]->order_id);

			$details = RedshopShippingRate::decrypt($data[$i]->ship_method_id);

			echo $data [$i]->order_id . ",";
			echo utf8_decode($order_function->getOrderStatusTitle($data [$i]->order_status)) . " ,";
			echo date('d-m-Y H:i', $data [$i]->cdate) . " ,";

			if (empty($details))
			{
				echo str_replace(",", " ", $details[1]) . "(" . str_replace(",", " ", $details[2]) . ") ,";
			}
			else
			{
				echo '';
			}

			$shipping_info = RedshopHelperOrder::getOrderShippingUserInfo($data[$i]->order_id);

			echo str_replace(",", " ", $shipping_info->firstname) . " " . str_replace(",", " ", $shipping_info->lastname) . " ,";
			echo str_replace(",", " ", utf8_decode($shipping_info->address)) . " ,";
			echo $shipping_info->zipcode . " ,";
			echo str_replace(",", " ", utf8_decode($shipping_info->city)) . " ,";
			echo $shipping_info->country_code . " ,";
			echo str_replace(",", " ", $shipping_info->company_name) . " ,";
			echo $shipping_info->user_email . " ,";

			echo str_replace(",", " ", utf8_decode($billing_info->address)) . " ,";
			echo $billing_info->zipcode . " ,";
			echo str_replace(",", " ", utf8_decode($billing_info->city)) . " ,";
			echo $billing_info->country_code . " ,";
			echo str_replace(",", " ", $billing_info->firstname) . " " . str_replace(",", " ", $billing_info->lastname) . " ,";

			$no_items = $order_function->getOrderItemDetail($data [$i]->order_id);

			for ($it = 0, $countItem = count($no_items); $it < $countItem; $it++)
			{
				echo str_replace(",", " ", utf8_decode($no_items [$it]->order_item_name)) . " ,";
				echo Redshop::getConfig()->get('REDCURRENCY_SYMBOL') . " " . $no_items [$it]->product_final_price . ",";

				$product_attribute = $producthelper->makeAttributeOrder($no_items [$it]->order_item_id, 0, $no_items [$it]->product_id, 0, 1);
				$product_attribute = strip_tags(str_replace(",", " ", $product_attribute->product_attribute));

				echo trim(utf8_decode($product_attribute)) . " ,";
			}

			$temp = $no_products - count($no_items);

			if ($temp >= 0)
			{
				echo str_repeat(' ,', $temp * 3);
			}

			echo  Redshop::getConfig()->get('REDCURRENCY_SYMBOL') . " " . $data [$i]->order_total . "\n";
		}

		exit ();
	}

	public function export_data()
	{
		/**
		 * new order export for paid customer support
		 */
		$extrafile = JPATH_SITE . '/administrator/components/com_redshop/extras/order_export.php';

		if (file_exists($extrafile))
		{
			require_once JPATH_COMPONENT_ADMINISTRATOR . '/extras/order_export.php';

			$orderExport = new orderExport;
			$orderExport->createOrderExport();
			JFactory::getApplication()->close();
		}

		$producthelper  = productHelper::getInstance();
		$order_function = order_functions::getInstance();
		$model          = $this->getModel('order');

		$product_count = array();
		$db            = JFactory::getDbo();

		$cid      = $this->input->get('cid', array(0), 'array');
		$data     = $model->export_data($cid);
		$order_id = implode(',', $cid);
		$where    = "";

		if ($order_id != 0)
		{
			$where .= " where order_id IN (" . $order_id . ") ";
		}

		$sql = "SELECT order_id,count(order_item_id) as noproduct FROM `#__redshop_order_item`  " . $where . " GROUP BY order_id";

		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-type: text/x-csv");
		header("Content-type: text/csv");
		header("Content-type: application/csv");
		header('Content-Disposition: attachment; filename=Order.csv');

		$db->setQuery($sql);
		$no_products = $db->loadObjectList();

		for ($i = 0, $in = count($data); $i < $in; $i++)
		{
			$product_count [] = $no_products [$i]->noproduct;
		}

		$no_products = max($product_count);

		echo "Order id,Buyer name,Email Id, PhoneNumber,Billing Address ,Billing City,Billing State,Billing Country,BillingPostcode,";
		echo "Shipping Address,Shipping City,Shipping State,Shipping Country,ShippingPostCode,Order Status,Order Date,";

		for ($i = 1; $i <= $no_products; $i++)
		{
			echo JText::_('PRODUCT_NAME') . $i . ',';
			echo JText::_('PRODUCT') . ' ' . JText::_('PRODUCT_PRICE') . $i . ',';
			echo JText::_('PRODUCT_ATTRIBUTE') . $i . ',';
		}

		echo "Shipping Cost,Order Total\n";

		for ($i = 0, $in = count($data); $i < $in; $i++)
		{
			$shipping_address = RedshopHelperOrder::getOrderShippingUserInfo($data[$i]->order_id);

			echo $data [$i]->order_id . ",";
			echo $data [$i]->firstname . " " . $data [$i]->lastname . ",";
			echo $data [$i]->user_email . ",";
			echo $data [$i]->phone . ",";
			$user_address = str_replace(",", "<br/>", $data [$i]->address);
			$user_address = strip_tags($user_address);
			$user_shipping_address = str_replace(",", "<br/>", $shipping_address->address);
			$user_shipping_address = strip_tags($user_shipping_address);

			echo trim($user_address) . ",";
			echo $data [$i]->city . ",";
			echo $data [$i]->state_code . ",";
			echo $data [$i]->country_code . ",";
			echo $data [$i]->zipcode . ",";

			echo trim($user_shipping_address) . ",";
			echo $shipping_address->city . ",";
			echo $shipping_address->state_code . ",";
			echo $shipping_address->country_code . ",";
			echo $shipping_address->zipcode . ",";

			echo $order_function->getOrderStatusTitle($data [$i]->order_status) . ",";
			echo date('d-m-Y H:i', $data [$i]->cdate) . ",";

			$no_items = $order_function->getOrderItemDetail($data [$i]->order_id);

			for ($it = 0, $countItem = count($no_items); $it < $countItem; $it++)
			{
				echo $no_items [$it]->order_item_name . ",";
				echo Redshop::getConfig()->get('REDCURRENCY_SYMBOL') . $no_items [$it]->product_final_price . ",";

				$product_attribute = $producthelper->makeAttributeOrder($no_items [$it]->order_item_id, 0, $no_items [$it]->product_id, 0, 1);
				$product_attribute = strip_tags($product_attribute->product_attribute);

				echo trim($product_attribute) . ",";
			}

			$temp = $no_products - count($no_items);
			echo str_repeat(',', $temp * 3);

			if ($data [$i]->order_shipping != "")
			{
				$shippingcost = $data [$i]->order_shipping;
			}
			else
			{
				$shippingcost = 0;
			}

			echo Redshop::getConfig()->get('REDCURRENCY_SYMBOL') . $shippingcost . ",";
			echo Redshop::getConfig()->get('REDCURRENCY_SYMBOL') . $data [$i]->order_total . "\n";
		}

		exit ();
	}

	public function generateParcel()
	{
		$order_function = order_functions::getInstance();
		$order_id       = $this->input->getInt('order_id');

		$generate_label = $order_function->generateParcel($order_id);

		if ($generate_label == "success")
		{
			$sussces_message = JText::_('COM_REDSHOP_XML_GENERATED_SUCCESSFULLY');
			$this->setRedirect('index.php?option=com_redshop&view=order', $sussces_message, 'success');
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=order', $generate_label, 'error');
		}
	}

	public function download_token()
	{
		$post = $this->input->post->getArray();
		$cid  = $this->input->post->get('cid', array(0), 'array');

		$model = $this->getModel('order');

		$download_id_arr = $post ['download_id'];

		for ($i = 0, $in = count($download_id_arr); $i < $in; $i++)
		{
			$download_id = $download_id_arr [$i];

			$product_download_infinite_var = 'product_download_infinite_' . $download_id;
			$product_download_infinite = $post [$product_download_infinite_var];

			$limit_var = 'limit_' . $download_id;
			$limit = $post [$limit_var];

			$days_var = 'days_' . $download_id;
			$days = $post [$days_var];

			$clock_var = 'clock_' . $download_id;
			$clock = $post [$clock_var];

			$clock_min_var = 'clock_min_' . $download_id;
			$clock_min = $post [$clock_min_var];

			$days = (date("H") > $clock && $days == 0) ? 1 : $days;

			$product_download_days_time = (time() + ($days * 24 * 60 * 60));

			$endtime = mktime(
				$clock, $clock_min, 0, date("m", $product_download_days_time), date("d", $product_download_days_time),
				date("Y", $product_download_days_time)
			);

			// If download product is set to infinit
			$endtime = ($product_download_infinite == 1) ? 0 : $endtime;

			$model->updateDownloadSetting($download_id, $limit, $endtime);
		}

		$this->setRedirect('index.php?option=com_redshop&view=order_detail&cid[]=' . $cid [0]);
	}

	public function gls_export()
	{
		$cid   = $this->input->get('cid', array(0), 'array');
		$model = $this->getModel('order');
		$model->gls_export($cid);
	}

	public function business_gls_export()
	{
		$cid   = $this->input->get('cid', array(0), 'array');
		$model = $this->getModel('order');
		$model->business_gls_export($cid);
	}
}
