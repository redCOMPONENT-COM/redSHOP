<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Economic\RedshopEconomic;

class RedshopControllerOrder extends RedshopController
{
	/**
	 * Method for generate PDF for specific order.
	 *
	 * @return void
	 */
	public function printPDF()
	{
		$app     = JFactory::getApplication();
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
		$app        = JFactory::getApplication();
		$serialized = $app->getUserState("com_redshop.order.batch.postdata");
		$post       = unserialize($serialized);
		$orderId    = $this->input->getInt('oid', 0);

		// Change Order Status
		RedshopHelperOrder::orderStatusUpdate($orderId, $post);

		$response = array(
			'message' => '<li class="success text-success">' . JText::sprintf('COM_REDSHOP_AJAX_ORDER_UPDATE_SUCCESS', $orderId) . '</li>'
		);

		// Trigger when order status changed.
		JPluginHelper::importPlugin('redshop_product');
		RedshopHelperUtility::getDispatcher()->trigger('onAjaxOrderStatusUpdate', array($orderId, $post, &$response));

		ob_clean();
		echo json_encode($response);

		$app->close();
	}

	public function bookInvoice()
	{
		$post            = $this->input->post->getArray();
		$bookInvoiceDate = $post ['bookInvoiceDate'];
		$orderId         = $this->input->getInt('order_id');
		$ecomsg          = JText::_('COM_REDSHOP_INVOICE_NOT_BOOKED_IN_ECONOMIC');
		$msgType         = 'warning';

		// Economic Integration start for invoice generate and book current invoice
		if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1)
		{
			$bookInvoicePdf = RedshopEconomic::bookInvoiceInEconomic($orderId, 0, $bookInvoiceDate);

			if (JFile::exists($bookInvoicePdf))
			{
				$ecomsg  = JText::_('COM_REDSHOP_SUCCESSFULLY_BOOKED_INVOICE_IN_ECONOMIC');
				$msgType = 'message';
				Redshop\Mail\Invoice::sendEconomicBookInvoiceMail($orderId, $bookInvoicePdf);
			}
		}

		// End Economic
		$this->setRedirect('index.php?option=com_redshop&view=order', $ecomsg, $msgType);
	}

	public function createInvoice()
	{
		if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1 && Redshop::getConfig()->get('ECONOMIC_INVOICE_DRAFT') != 2)
		{
			$orderId      = $this->input->getInt('order_id');
			$orderEntity  = RedshopEntityOrder::getInstance($orderId);

			if (!$orderEntity->isValid())
			{
				return false;
			}

			$paymentInfo  = $orderEntity->getPayment();

			if (!$paymentInfo->isValid())
			{
				return false;
			}

			$paymentInfo = $paymentInfo->getItem();
			$economicData = array();

			if ($paymentInfo)
			{
				$paymentName = $paymentInfo->payment_method_class;
				$paymentArr  = explode("rs_payment_", $paymentInfo->payment_method_class);

				if (!empty($paymentArr))
				{
					$paymentName = $paymentArr[1];
				}

				$economicData['economic_payment_method']   = $paymentName;
				$economicData['economic_payment_terms_id'] = $paymentInfo->plugin->params->get('economic_payment_terms_id');
				$economicData['economic_design_layout']    = $paymentInfo->plugin->params->get('economic_design_layout');
				$economicData['economic_is_creditcard']    = $paymentInfo->plugin->params->get('is_creditcard');
			}

			RedshopEconomic::createInvoiceInEconomic($orderId, $economicData);

			if (Redshop::getConfig()->getInt('ECONOMIC_INVOICE_DRAFT') == 0)
			{
				$bookInvoicePdf = RedshopEconomic::bookInvoiceInEconomic($orderId, 1);

				if (JFile::exists($bookInvoicePdf))
				{
					$ret = Redshop\Mail\Invoice::sendEconomicBookInvoiceMail($orderId, $bookInvoicePdf);
				}
			}
		}

		$this->setRedirect('index.php?option=com_redshop&view=order');
	}

	public function export_fullorder_data()
	{
		$extraFile = JPATH_SITE . '/administrator/components/com_redshop/extras/order_export.php';

		if (file_exists($extraFile))
		{
			require_once JPATH_COMPONENT_ADMINISTRATOR . '/extras/order_export.php';

			$orderExport = new orderExport;
			$orderExport->createOrderExport();

			exit;
		}

		$producthelper  = productHelper::getInstance();
		$model         = $this->getModel('order');
		$cid           = $this->input->get('cid', array(0), 'array');
		$data          = $model->export_data($cid);
		$product_count = array();
		$db            = JFactory::getDbo();

		$where = "";

		$sql = "SELECT order_id,count(order_item_id) as noproduct FROM `#__redshop_order_item`  " . $where . " GROUP BY order_id";

		$db->setQuery($sql);
		$no_products = $db->loadObjectList();

		for ($index = 0, $in = count($data); $index < $in; $index++)
		{
			$product_count [] = $no_products [$index]->noproduct;
		}

		$no_products = max($product_count);

		$header = array(
			'Order number', 'Order status', 'Order date', 'Shipping method', 'Shipping user', 'Shipping address',
			'Shipping postalcode', 'Shipping city', 'Shipping country', 'Company name', 'Email', 'Billing address',
			'Billing postalcode', 'Billing city', 'Billing country', 'Billing User'
		);

		for ($index = 1; $index <= $no_products; $index++)
		{
			$header[] = JText::_('COM_REDSHOP_PRODUCT_NAME') . $index;
			$header[] = JText::_('COM_REDSHOP_PRODUCT') . ' ' . JText::_('COM_REDSHOP_PRODUCT_PRICE') . $index;
			$header[] = JText::_('COM_REDSHOP_PRODUCT_ATTRIBUTE') . $index;
		}

		$header[] = 'Order Total';
		$rows     = array();
		$rows[]   = $header;

		for ($index = 0, $in = count($data); $index < $in; $index++)
		{
			$billing_info = RedshopEntityOrder::getInstance($data[$index]->order_id)->getBilling()->getItem();

			$details = RedshopShippingRate::decrypt($data[$index]->ship_method_id);

			$row = array();

			// order number
			$row[] = $data [$index]->order_id;

			// order status
			$row[] = utf8_decode(RedshopHelperOrder::getOrderStatusTitle($data [$index]->order_status));

			// order date
			$row[] = date('d-m-Y H:i', $data [$index]->cdate);

			// shipping method
			if (empty($details))
			{
				$row[] = str_replace(",", " ", $details[1]) . "(" . str_replace(",", " ", $details[2]) . ")";
			}
			else
			{
				$row[] = '';
			}

			// shipping user
			$shipping_info = RedshopEntityOrder::getInstance($data[$index]->order_id)->getShipping()->getItem();
			$row[]         = str_replace(",", " ", $shipping_info->firstname) . " " . str_replace(",", " ", $shipping_info->lastname);

			// shipping address
			$row[] = str_replace(",", " ", utf8_decode($shipping_info->address));

			// postal code
			$row[] = $shipping_info->zipcode;

			// city
			$row[] = str_replace(",", " ", utf8_decode($shipping_info->city));

			// country
			$row[] = $shipping_info->country_code;

			// company
			$row[] = str_replace(",", " ", $shipping_info->company_name);

			// email
			$row[] = $shipping_info->user_email;

			// billing address
			$row[] = str_replace(",", " ", utf8_decode($billing_info->address));

			// postal code
			$row[] = $billing_info->zipcode;

			// city
			$row[] = str_replace(",", " ", utf8_decode($billing_info->city));

			// country
			$row[] = $billing_info->country_code;

			// user
			$row[] = str_replace(",", " ", $billing_info->firstname) . " " . str_replace(",", " ", $billing_info->lastname);

			$items = RedshopHelperOrder::getOrderItemDetail($data [$index]->order_id);

			if ($items && !empty($items))
			{
				foreach ($items as $item)
				{
					$row[] = str_replace(",", " ", utf8_decode($item->order_item_name));
					$row[] = Redshop::getConfig()->get('REDCURRENCY_SYMBOL') . " " . $item->product_final_price;

					$product_attribute = $producthelper->makeAttributeOrder($item->order_item_id, 0, $item->product_id, 0, 1);
					$product_attribute = strip_tags(str_replace(",", " ", $product_attribute->product_attribute));

					$row[] = trim(utf8_decode($product_attribute));
				}
			}

			$temp = $no_products - count($items);

			if ($temp >= 0)
			{
				for ($count = 1; $count <= $temp * 3; $count++)
				{
					$row[] = '';
				}
			}

			$row[] = Redshop::getConfig()->get('REDCURRENCY_SYMBOL') . " " . $data [$index]->order_total;

			$rows[] = $row;
		}

		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-type: text/x-csv");
		header("Content-type: text/csv");
		header("Content-type: application/csv");
		header('Content-Disposition: attachment; filename=Order.csv');

		$output = fopen('php://output', 'w');

		foreach ($rows as $row)
		{
			fputcsv($output, $row);
		}

		fclose($output);

		JFactory::getApplication()->close();
	}

	public function export_data()
	{
		/**
		 * new order export for paid customer support
		 */
		$extraFile = JPATH_SITE . '/administrator/components/com_redshop/extras/order_export.php';

		if (JFile::exists($extraFile))
		{
			require_once JPATH_COMPONENT_ADMINISTRATOR . '/extras/order_export.php';

			$orderExport = new orderExport;
			$orderExport->createOrderExport();

			JFactory::getApplication()->close();
		}

		$productHelper = productHelper::getInstance();
		$model         = $this->getModel('order');
		$productCount = array();

		$cid     = $this->input->get('cid', array(0), 'array');
		$data    = $model->export_data($cid);
		$orderId = implode(',', $cid);

		$noProducts = $this->getNoProducts($orderId);

		\Redshop\Environment\Respond\Helper::download('Order.csv');

		foreach ($data as $index => $aData)
		{
			$productCount[] = $noProducts [$index]->noproduct;
		}

		$noProducts = max($productCount);

		echo "Order id,Buyer name,Email Id, PhoneNumber,Billing Address ,Billing City,Billing State,Billing Country,BillingPostcode,";
		echo "Shipping Address,Shipping City,Shipping State,Shipping Country,ShippingPostCode,Order Status,Order Date,";

		for ($i = 1; $i <= $noProducts; $i++)
		{
			echo JText::_('PRODUCT_NAME') . $i . ',';
			echo JText::_('PRODUCT') . ' ' . JText::_('PRODUCT_PRICE') . $i . ',';
			echo JText::_('PRODUCT_ATTRIBUTE') . $i . ',';
		}

		echo "Shipping Cost,Order Total\n";

		foreach ($data as $aData)
		{
			$shipping_address = RedshopEntityOrder::getInstance($aData->order_id)->getShipping()->getItem();

			echo $aData->order_id . ",";
			echo $aData->firstname . " " . $aData->lastname . ",";
			echo $aData->user_email . ",";
			echo $aData->phone . ",";
			$user_address          = str_replace(",", "<br/>", $aData->address);
			$user_address          = strip_tags($user_address);
			$user_shipping_address = str_replace(",", "<br/>", $shipping_address->address);
			$user_shipping_address = strip_tags($user_shipping_address);

			echo trim($user_address) . ",";
			echo $aData->city . ",";
			echo $aData->state_code . ",";
			echo $aData->country_code . ",";
			echo $aData->zipcode . ",";

			echo trim($user_shipping_address) . ",";
			echo $shipping_address->city . ",";
			echo $shipping_address->state_code . ",";
			echo $shipping_address->country_code . ",";
			echo $shipping_address->zipcode . ",";

			echo RedshopHelperOrder::getOrderStatusTitle($aData->order_status) . ",";
			echo date('d-m-Y H:i', $aData->cdate) . ",";

			$noItems = RedshopHelperOrder::getOrderItemDetail($aData->order_id);

			if ($noItems)
			{
				foreach ($noItems as $noItem)
				{
					echo $noItem->order_item_name . ",";
					echo Redshop::getConfig()->getString('REDCURRENCY_SYMBOL') . $noItem->product_final_price . ",";

					$product_attribute = $productHelper->makeAttributeOrder($noItem->order_item_id, 0, $noItem->product_id, 0, 1);
					$product_attribute = strip_tags($product_attribute->product_attribute);

					echo trim($product_attribute) . ",";
				}

				$temp = $noProducts - count($noItems);
				echo str_repeat(',', $temp * 3);
			}

			if ($aData->order_shipping != "")
			{
				$shippingcost = $aData->order_shipping;
			}
			else
			{
				$shippingcost = 0;
			}

			echo Redshop::getConfig()->getString('REDCURRENCY_SYMBOL') . $shippingcost . ",";
			echo Redshop::getConfig()->getString('REDCURRENCY_SYMBOL') . $aData->order_total . "\n";
		}

		JFactory::getApplication()->close();
	}

	public function generateParcel()
	{
		$orderId      = $this->input->getInt('order_id');
		$generalLabel = RedshopHelperOrder::generateParcel($orderId);

		if ($generalLabel == "success")
		{
			$this->setRedirect('index.php?option=com_redshop&view=order', JText::_('COM_REDSHOP_XML_GENERATED_SUCCESSFULLY'), 'success');
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=order', $generalLabel, 'error');
		}
	}

	public function download_token()
	{
		$post            = $this->input->post->getArray();
		$cid             = $this->input->post->get('cid', array(0), 'array');
		$model           = $this->getModel('order');
		$downloadIdArray = $post ['download_id'];

		foreach ($downloadIdArray as $downloadId)
		{
			$product_download_infinite_var = 'product_download_infinite_' . $downloadId;
			$product_download_infinite     = $post [$product_download_infinite_var];

			$limit_var = 'limit_' . $downloadId;
			$limit     = $post [$limit_var];

			$days_var = 'days_' . $downloadId;
			$days     = $post [$days_var];

			$clock_var = 'clock_' . $downloadId;
			$clock     = $post [$clock_var];

			$clock_min_var = 'clock_min_' . $downloadId;
			$clock_min     = $post [$clock_min_var];

			$days = (date("H") > $clock && $days == 0) ? 1 : $days;

			$product_download_days_time = (time() + ($days * 24 * 60 * 60));

			$endtime = mktime(
				$clock, $clock_min, 0, date("m", $product_download_days_time), date("d", $product_download_days_time),
				date("Y", $product_download_days_time)
			);

			// If download product is set to infinit
			$endtime = ($product_download_infinite == 1) ? 0 : $endtime;

			$model->updateDownloadSetting($downloadId, $limit, $endtime);
		}

		$this->setRedirect('index.php?option=com_redshop&view=order_detail&cid[]=' . (int) $cid [0]);
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

	/**
	 * @param   string  $orderId  Order Id
	 *
	 * @return  mixed
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function getNoProducts($orderId = '')
	{
		$db            = JFactory::getDbo();
		$query         = $db->getQuery(true);

		if (!empty($orderId))
		{
			$query->where($db->quoteName('order_id') . ' IN (' . $orderId . ')');
		}

		$query->select($db->quoteName('order_id'))
			->select('COUNT (' . $db->quoteName('order_item_id', 'noproduct') . ') ')
			->from($db->quoteName('#__redshop_order_item'))
			->group($db->quoteName('order_id'));

		return $db->setQuery($query)->loadObjectList();
	}
}
