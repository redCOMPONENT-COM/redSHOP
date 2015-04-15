<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Order CSV export and send email after order update
 *
 * @since  1.3.3.1
 */
class PlgRedshop_ProductInvoicePdf extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * This method will trigger when redSHOP order status will be updated.
	 *
	 * @param   object  $data  Order Status Information
	 *
	 * @return  void
	 */
	public function onAjaxOrderStatusUpdate($orderId, $data, &$response)
	{
		if ($data['order_status_all'] !== 'S')
		{
			return;
		}

		if ($data['order_status_all'] == 'S' && $data['order_paymentstatus' . $orderId] != "Paid")
		{
			$response['message'] .= '<li class="red text-error">'
						. JText::sprintf("PLG_REDSHOP_PRODUCT_INVOICEPDF_CREATE_FAIL", "<span class=\"badge badge-important\">" . $orderId . "</span>")
					. '</li>';

			return;
		}

		$pdfObj = RedshopHelperPdf::getInstance();

		$pdfObj->SetTitle('Shipped');
		$pdfObj->SetMargins(20, 85, 20);

		$font = 'times';
		$pdfObj->setHeaderFont(array($font, '', 8));
		$pdfObj->SetFont($font, "", 6);

		$invoice = $this->createShippedInvoicePdf($orderId);

		// Writing Body area
		$pdfObj->AddPage();
		$pdfObj->WriteHTML($invoice, true, false, true, false, '');

		$invoice_pdfName = 'shipped_' . $orderId;
		$pdfObj->Output(JPATH_SITE . '/components/com_redshop/assets/document/invoice/' . $invoice_pdfName . ".pdf", "F");

		ob_end_clean();

		// Set response message
		$message = $response['message'] . '<li class="success text-success">'
			. JText::sprintf("PLG_REDSHOP_PRODUCT_INVOICEPDF_CREATED", "<span class=\"badge badge-success\">" . $orderId . "</span>")
			. '</li>';

		$response['orderId'] = $orderId;
		$mergeOrderIds[]     = $orderId;

		$app   = JFactory::getApplication();
		$index = $app->getUserState("com_redshop.order.batch.invoicepdf.currentIndex", 0);

		// Last call
		if ($index == count($data['cid']) - 1)
		{
			$mergedPdf = $this->mergeShippingPdf($mergeOrderIds);
			$message   .= '<li class="success text-success"><a target="_blank" href="' . $mergedPdf . '">' . $mergedPdf . '</a></li>';

			$index = 0;
		}
		else
		{
			$index++;
		}

		// Set current index in user state
		$app->setUserState("com_redshop.order.batch.invoicepdf.currentIndex", $index);

		$response['message'] = $message;
	}

	public function createShippedInvoicePdf($orderId)
	{
		$orderHelper   = new order_functions;
		$redconfig     = new Redconfiguration;
		$producthelper = new producthelper;
		$extra_field   = new extra_field;
		$config        = JFactory::getConfig();
		$redTemplate   = new Redtemplate;
		$carthelper    = new rsCarthelper;
		$redshopMail   = new redshopMail;
		$message       = "";
		$subject       = "";
		$cart          = '';

		$arr_discount_type = array();

		$mailinfo = $redTemplate->getTemplate("shippment_invoice_template");

		if (count($mailinfo) > 0)
		{
			$message = $mailinfo[0]->template_desc;
		}
		else
		{
			return false;
		}

		$row = $orderHelper->getOrderDetails($orderId);

		$barcode_code = $row->barcode;
		$arr_discount = explode('@', $row->discount_type);
		$discount_type = '';

		for ($d = 0; $d < count($arr_discount); $d++)
		{
			if ($arr_discount[$d])
			{
				$arr_discount_type = explode(':', $arr_discount[$d]);

				if ($arr_discount_type[0] == 'c')
				{
					$discount_type .= JText::_('COM_REDSHOP_COUPON_CODE') . ' : ' . $arr_discount_type[1] . '<br>';
				}

				if ($arr_discount_type[0] == 'v')
				{
					$discount_type .= JText::_('COM_REDSHOP_VOUCHER_CODE') . ' : ' . $arr_discount_type[1] . '<br>';
				}
			}
		}

		if (!$discount_type)
		{
			$discount_type = JText::_('COM_REDSHOP_NO_DISCOUNT_AVAILABLE');
		}

		$search[] = "{discount_type}";
		$replace[] = $discount_type;

		$message          = str_replace($search, $replace, $message);

		$message          = $redshopMail->imginmail($message);
		$user             = JFactory::getUser();
		$billingaddresses = $orderHelper->getOrderBillingUserInfo($orderId);
		$email            = $billingaddresses->user_email;
		$userfullname     = $billingaddresses->firstname . " " . $billingaddresses->lastname;
		$message          = $carthelper->replaceOrderTemplate($row, $message);

		echo "<div id='redshopcomponent' class='redshop'>";

		if (strstr($message, "{barcode}"))
		{
			$img_url = REDSHOP_FRONT_IMAGES_RELPATH . "barcode/" . $barcode_code . ".png";

			if (function_exists("curl_init"))
			{
				$bar_codeIMG = '<img src="' . $img_url . '" alt="Barcode"  border="0" />';
				$message = str_replace("{barcode}", $bar_codeIMG, $message);
			}
		}

		$body = $message;

		return $body;
	}

	/**
	 * Merge Shipping Information PDF
	 *
	 * @return  void  Set PDF path on the viewport
	 */
	public function mergeShippingPdf($mergeOrderIds)
	{
		$pdfLocation   = 'components/com_redshop/assets/document/invoice/';
		$pdfRootPath   = JPATH_SITE . '/' . $pdfLocation;

		JArrayHelper::toInteger($mergeOrderIds);

		$pdf = RedshopHelperPdf::getPDFMerger();

		for ($m = 0; $m < count($mergeOrderIds); $m++)
		{
			$pdfName = $pdfRootPath . 'shipped_' . $mergeOrderIds[$m] . '.pdf';

			if (file_exists($pdfName))
			{
				$pdf->addPDF($pdfName, 'all');
			}
		}

		$mergedPdfFile = 'shipped_' . rand() . '.pdf';

		$pdf->merge('file', $pdfRootPath . $mergedPdfFile);

		for ($m = 0; $m < count($mergeOrderIds); $m++)
		{
			$pdfName = $pdfRootPath . 'shipped_' . $mergeOrderIds[$m] . '.pdf';

			if (file_exists($pdfName))
			{
				unlink($pdfName);
			}
		}

		return JUri::root() . $pdfLocation . $mergedPdfFile;
	}
}
