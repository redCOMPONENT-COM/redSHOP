<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT . '/helpers/order.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/mail.php';
require_once JPATH_SITE . '/components/com_redshop/helpers/helper.php';
require_once JPATH_SITE . '/components/com_redshop/helpers/tcpdf/tcpdf.php';
require_once JPATH_SITE . '/components/com_redshop/helpers/tcpdf/PDFMerger.php';

class orderController extends JController
{
	public function multiprint_order()
	{
		$mypost = JRequest::getVar('cid');

		$order_function = new order_functions;

		$invoicePdf = $order_function->createMultiprintInvoicePdf($mypost);
		?>
    <script type="text/javascript">
			<?php if ($invoicePdf != "")
		{
			if (file_exists(REDSHOP_FRONT_DOCUMENT_RELPATH . "invoice/" . $invoicePdf . ".pdf"))
			{
				?>
            window.open("<?php echo REDSHOP_FRONT_DOCUMENT_ABSPATH?>invoice/<?php echo $invoicePdf?>.pdf");

				<?php
			}
		}

		for ($i = 0; $i < count($mypost); $i++)
		{
			if (file_exists(JPATH_COMPONENT_SITE . "/assets/labels/label_" . $mypost[$i] . ".pdf"))
			{
				?>
            window.open("<?php echo JURI::root()?>/components/com_redshop/assets/labels/label_<?php echo $mypost[$i]?>.pdf");

				<?php }
		} ?>

        window.parent.location = 'index.php?option=com_redshop&view=order';
    </script>
	<?php
	}

	public function cancel()
	{
		$option = JRequest::getVar('option');
		$this->setRedirect('index.php?option=' . $option . '&view=order');
	}

	public function update_status()
	{
		$model = $this->getModel('order');
		$model->update_status();
	}

	public function allstatus()
	{
		$session = JFactory::getSession();
		$post = JRequest::get('post');
		$option = $post['option'];
		$merge_invoice_arr = array();

		$session->clear('updateOrderIdPost');
		$session->set('updateOrderIdPost', $post);
		$session->set('merge_invoice_arr', $merge_invoice_arr);

		$this->setRedirect('index.php?option=' . $option . '&view=order&layout=previewlog');

		return;
	}

	public function updateOrderStatus()
	{
		$session = JFactory::getSession();
		$post = $session->get('updateOrderIdPost');
		$merge_invoice_arr = $session->get('merge_invoice_arr');
		$rand_invoice_name = JRequest::getVar('rand_invoice_name', '');
		$order_functions = new order_functions;
		$cnt = JRequest::getInt('cnt', 0);
		$order_id = $post['cid'];

		$responcemsg = "";

		for ($i = $cnt, $j = 0; $j < 1; $j++)
		{
			if (!isset($order_id[$i]))
			{
				$pdf = new PDFMerger;
				$merge_invoice_arr = $session->get('merge_invoice_arr');

				for ($m = 0; $m < count($merge_invoice_arr); $m++)
				{
					if (file_exists(JPATH_SITE . '/components/com_redshop/assets/document'
						. '/invoice/shipped_' . $merge_invoice_arr[$m] . '.pdf'))
					{
						$pdf->addPDF(JPATH_SITE . '/components/com_redshop/assets/document'
							. '/invoice/shipped_' . $merge_invoice_arr[$m] . '.pdf', 'all'
						);
					}
				}

				$pdf->merge('file', JPATH_SITE . '/components/com_redshop/assets/document'
					. '/invoice/shipped_' . $rand_invoice_name . '.pdf'
				);

				for ($m = 0; $m < count($merge_invoice_arr); $m++)
				{
					if (file_exists(JPATH_SITE . '/components/com_redshop/assets/document'
						. '/invoice/shipped_' . $merge_invoice_arr[$m] . '.pdf'))
					{
						unlink(JPATH_ROOT . '/components/com_redshop/assets/document/invoice/shipped_' . $merge_invoice_arr[$m] . '.pdf'
						);
					}
				}

				$session->set('merge_invoice_arr', null);

				break;
			}

			$returnmsg = $order_functions->orderStatusUpdate($order_id[$i], $post);

			// For shipped pdf generation
			if ($post['order_status_all'] == "S" && $post['order_paymentstatus' . $order_id[$i]] == "Paid")
			{
				$pdfObj = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A5', true, 'UTF-8', false);
				$pdfObj->SetTitle('Shipped');
				$pdfObj->SetAuthor('redSHOP');
				$pdfObj->SetCreator('redSHOP');
				$pdfObj->SetMargins(8, 8, 8);
				$font = 'times';
				$pdfObj->setImageScale(PDF_IMAGE_SCALE_RATIO);
				$pdfObj->setHeaderFont(array($font, '', 8));
				$pdfObj->SetFont($font, "", 6);

				$invoice = $order_functions->createShippedInvoicePdf($order_id[$i]);
				$session->set('merge_invoice_arr', $order_id[$i]);
				$pdfObj->AddPage();
				$pdfObj->WriteHTML($invoice, true, false, true, false, '');

				$invoice_pdfName = "shipped_" . $order_id[$i];
				$merge_invoice_arr[] = $order_id[$i];
				$session->set('merge_invoice_arr', $merge_invoice_arr);

				$pdfObj->Output(
					JPATH_SITE . '/components/com_redshop/assets/document'
					. '/invoice/' . $invoice_pdfName . ".pdf", "F");
			}

			$responcemsg .= "<div>" . ($i + 1) . ": " . JText::_('COM_REDSHOP_ORDER_ID') . " " . $order_id[$i] . " -> ";
			$errmsg = '';

			if ($returnmsg)
			{
				$responcemsg .= "<span style='color: #00ff00'>" . JText::_('COM_REDSHOP_ORDER_STATUS_SUCCESSFULLY_UPDATED') . $errmsg . "</span>";
			}
			else
			{
				$responcemsg .= "<span style='color: #ff0000'>" . JText::_('COM_REDSHOP_ORDER_STATUS_UPDATE_FAIL') . $errmsg . "</span>";
			}

			$responcemsg .= "</div>";
		}

		$responcemsg = "<div id='sentresponse'>" . $responcemsg . "</div>";
		echo $responcemsg;
		exit;
	}

	public function bookInvoice()
	{
		$post = JRequest::get('post');
		$bookInvoiceDate = $post ['bookInvoiceDate'];
		$order_id = JRequest::getCmd('order_id');
		$ecomsg = JText::_('COM_REDSHOP_INVOICE_NOT_BOOKED_IN_ECONOMIC');

		// Economic Integration start for invoice generate and book current invoice
		if (ECONOMIC_INTEGRATION == 1)
		{
			$economic = new economic;
			$bookinvoicepdf = $economic->bookInvoiceInEconomic($order_id, 0, 0, $bookInvoiceDate);

			if (is_file($bookinvoicepdf))
			{
				$redshopMail = new redshopMail;
				$ecomsg = JText::_('COM_REDSHOP_SUCCESSFULLY_BOOKED_INVOICE_IN_ECONOMIC');
				$ret = $redshopMail->sendEconomicBookInvoiceMail($order_id, $bookinvoicepdf);
			}
		}

		// End Economic
		$this->setRedirect('index.php?option=com_redshop&view=order', $ecomsg);
	}

	public function createInvoice()
	{
		if (ECONOMIC_INTEGRATION == 1 && ECONOMIC_INVOICE_DRAFT != 2)
		{
			$order_id = JRequest::getCmd('order_id');
			$order_function = new order_functions;
			$paymentInfo = $order_function->getOrderPaymentDetail($order_id);

			if (count($paymentInfo) > 0)
			{
				$payment_name = $paymentInfo[0]->payment_method_class;
				$paymentArr = explode("rs_payment_", $paymentInfo[0]->payment_method_class);

				if (count($paymentArr) > 0)
				{
					$payment_name = $paymentArr[1];
				}

				$economicdata['economic_payment_method'] = $payment_name;
				$paymentmethod = $order_function->getPaymentMethodInfo($paymentInfo[0]->payment_method_class);

				if (count($paymentmethod) > 0)
				{
					$paymentparams = new JRegistry($paymentmethod[0]->params);
					$economicdata['economic_payment_terms_id'] = $paymentparams->get('economic_payment_terms_id');
					$economicdata['economic_design_layout'] = $paymentparams->get('economic_design_layout');
					$economicdata['economic_is_creditcard'] = $paymentparams->get('is_creditcard');
				}
			}

			$economic = new economic;
			$economicdata ['split_payment'] = 0;
			$invoiceHandle = $economic->createInvoiceInEconomic($order_id, $economicdata);

			if (ECONOMIC_INVOICE_DRAFT == 0)
			{
				$bookinvoicepdf = $economic->bookInvoiceInEconomic($order_id, 1);

				if (is_file($bookinvoicepdf))
				{
					$redshopMail = new redshopMail;
					$ret = $redshopMail->sendEconomicBookInvoiceMail($order_id, $bookinvoicepdf);
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
			exit;
		}

		$producthelper = new producthelper;
		$order_function = new order_functions;

		$model = $this->getModel('order');
		$data = $model->export_data();
		$product_count = array();
		$db = JFactory::getDBO();

		$cid = JRequest::getVar('cid', array(0), 'method', 'array');
		$order_id = implode(',', $cid);
		$where = "";

		$sql = "SELECT order_id,count(order_item_id) as noproduct FROM `#__redshop_order_item`  " . $where . " GROUP BY order_id";

		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-type: text/x-csv");
		header("Content-type: text/csv");
		header("Content-type: application/csv");
		header('Content-Disposition: attachment; filename=Order.csv');

		$db->setQuery($sql);
		$no_products = $db->loadObjectList();

		for ($i = 0; $i < count($data); $i++)
		{
			$product_count [] = $no_products [$i]->noproduct;
		}

		$no_products = max($product_count);

		$shipping_helper = new shipping;
		ob_clean();

		echo "Order number, Order status, Order date , Shipping method , Shipping user, Shipping address, Shipping postalcode,
		Shipping city, Shipping country, Company name, Email ,Billing address, Billing postalcode, Billing city, Billing country,
		Billing User ,";

		for ($i = 1; $i <= $no_products; $i++)
		{
			echo JText::_('COM_REDSHOP_PRODUCT_NAME') . $i . ' ,';
			echo JText::_('COM_REDSHOP_PRODUCT') . ' ' . JText::_('COM_REDSHOP_PRODUCT_PRICE') . $i . ' ,';
			echo JText::_('COM_REDSHOP_PRODUCT_ATTRIBUTE') . $i . ' ,';
		}

		echo "Order Total\n";

		for ($i = 0; $i < count($data); $i++)
		{
			$billing_info = $order_function->getOrderBillingUserInfo($data [$i]->order_id);

			$details = explode("|", $shipping_helper->decryptShipping(str_replace(" ", "+", $data[$i]->ship_method_id)));

			echo $data [$i]->order_id . ",";
			echo utf8_decode($order_function->getOrderStatusTitle($data [$i]->order_status)) . " ,";
			echo date('d-m-Y H:i', $data [$i]->cdate) . " ,";

			echo str_replace(",", " ", $details[1]) . "(" . str_replace(",", " ", $details[2]) . ") ,";
			$shipping_info = $order_function->getOrderShippingUserInfo($data [$i]->order_id);

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

			for ($it = 0; $it < count($no_items); $it++)
			{
				echo str_replace(",", " ", utf8_decode($no_items [$it]->order_item_name)) . " ,";
				echo "\"" . REDCURRENCY_SYMBOL . "\"" . $no_items [$it]->product_final_price;

				$product_attribute = $producthelper->makeAttributeOrder($no_items [$it]->order_item_id, 0, $no_items [$it]->product_id, 0, 1);
				$product_attribute = strip_tags(str_replace(",", " ", $product_attribute->product_attribute));

				echo trim(utf8_decode($product_attribute)) . " ,";
			}

			$temp = $no_products - count($no_items);

			if ($temp >= 0)
			{
				echo str_repeat(' ,', $temp * 3);
			}

			echo "\"" . REDCURRENCY_SYMBOL . "\"" . $data [$i]->order_total . "\n";
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
			exit;
		}

		$producthelper = new producthelper;
		$order_function = new order_functions;
		$model = $this->getModel('order');

		$product_count = array();
		$db = JFactory::getDBO();

		$cid = JRequest::getVar('cid', array(0), 'method', 'array');
		$data = $model->export_data($cid);
		$order_id = implode(',', $cid);
		$where = "";

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

		for ($i = 0; $i < count($data); $i++)
		{
			$product_count [] = $no_products [$i]->noproduct;
		}

		$no_products = max($product_count);

		echo "Order id,Buyer name,Email Id, PhoneNumber,Billing Address ,Billing City,Billing State,Billing Country,BillingPostcode,
		Shipping Address,Shipping City,Shipping State,Shipping Country,ShippingPostCode,Order Status,Order Date,";

		for ($i = 1; $i <= $no_products; $i++)
		{
			echo JText::_('PRODUCT_NAME') . $i . ',';
			echo JText::_('PRODUCT') . ' ' . JText::_('PRODUCT_PRICE') . $i . ',';
			echo JText::_('PRODUCT_ATTRIBUTE') . $i . ',';
		}

		echo "Shipping Cost,Order Total\n";

		for ($i = 0; $i < count($data); $i++)
		{
			$shipping_address = $order_function->getOrderShippingUserInfo($data [$i]->order_id);

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

			for ($it = 0; $it < count($no_items); $it++)
			{
				echo $no_items [$it]->order_item_name . ",";
				echo REDCURRENCY_SYMBOL . $no_items [$it]->product_final_price . ",";

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

			echo REDCURRENCY_SYMBOL . $shippingcost . ",";
			echo REDCURRENCY_SYMBOL . $data [$i]->order_total . "\n";
		}

		exit ();
	}

	public function generateParcel()
	{
		$order_function = new order_functions;
		$post = JRequest::get('post');
		$specifiedSendDate = $post ['specifiedSendDate'];
		$order_id = JRequest::getCmd('order_id');

		$generate_label = $order_function->generateParcel($order_id, $specifiedSendDate);

		if ($generate_label == "success")
		{
			$sussces_message = JText::_('COM_REDSHOP_XML_GENERATED_SUCCESSFULLY');
			$this->setRedirect('index.php?option=com_redshop&view=order', $sussces_message);
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=order', $generate_label);
		}
	}

	public function download_token()
	{
		$post = JRequest::get('post');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		$model = $this->getModel();

		$download_id_arr = $post ['download_id'];

		for ($i = 0; $i < count($download_id_arr); $i++)
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
		$cid = JRequest::getVar('cid', array(0), 'method', 'array');
		$model = $this->getModel();
		$model->gls_export($cid);
	}

	public function business_gls_export()
	{
		$cid = JRequest::getVar('cid', array(0), 'method', 'array');
		$model = $this->getModel();
		$model->business_gls_export($cid);
	}
}
