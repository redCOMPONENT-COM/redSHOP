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
 * Order Shipping PDF export
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
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An optional associative array of configuration settings.
	 *                             Recognized key values include 'name', 'group', 'params', 'language'
	 *                             (this list is not meant to be comprehensive).
	 *
	 * @since   1.5
	 */
	public function __construct(&$subject, $config = array())
	{
		$lang          = JFactory::getLanguage();
		$lang->load('plg_redshop_product_invoicepdf', JPATH_ADMINISTRATOR);

		parent::__construct($subject, $config);
	}

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

		$app           = JFactory::getApplication();
		$index         = $app->getUserState("com_redshop.order.batch.invoicepdf.currentIndex", 0);
		$mergeOrderIds = $app->getUserState("com_redshop.order.batch.invoicepdf.orderId", array());
		$message       = $response['message'];

		if ($data['order_status_all'] == 'S' && $data['order_paymentstatus' . $orderId] != "Paid")
		{
			$message .= '<li class="red text-error">'
						. JText::sprintf("PLG_REDSHOP_PRODUCT_INVOICEPDF_CREATE_FAIL", "<span class=\"badge badge-important\">" . $orderId . "</span>")
					. '</li>';
		}
		elseif (RedshopHelperPdf::isAvailablePdfPlugins())
		{
			$invoice = $this->createShippedInvoicePdf($orderId);

			JPluginHelper::importPlugin('redshop_pdf');
			$result = RedshopHelperUtility::getDispatcher()->trigger('onRedshopPdfCreateShippedInvoice', array($orderId, $invoice));

			ob_end_clean();

			// Set response message
			if (in_array(false, $result, true))
			{
				$message .= '<li class="red text-error">'
					. JText::sprintf("PLG_REDSHOP_PRODUCT_INVOICEPDF_CREATE_FAIL", "<span class=\"badge badge-important\">" . $orderId . "</span>")
					. '</li>';
			}
			else
			{
				$message .= '<li class="success text-success">'
					. JText::sprintf("PLG_REDSHOP_PRODUCT_INVOICEPDF_CREATED", "<span class=\"badge badge-success\">" . $orderId . "</span>")
					. '</li>';
			}

			array_push($mergeOrderIds, $orderId);
		}

		// Last call
		if ($index == (count($data['cid']) - 1))
		{
			$mergedPdf = $this->mergeShippingPdf($mergeOrderIds);
			$message   .= '<li><a target="_blank" href="' . $mergedPdf . '">' . $mergedPdf . '</a></li>';

			$index = 0;
		}
		else
		{
			$index++;
		}

		// Set current index in user state
		$app->setUserState("com_redshop.order.batch.invoicepdf.currentIndex", $index);

		// Setting up successfull order ids in queue
		$app->setUserState("com_redshop.order.batch.invoicepdf.orderId", $mergeOrderIds);

		$response['message'] = $message;
	}

	public function createShippedInvoicePdf($orderId)
	{
		$orderHelper   = order_functions::getInstance();
		$carthelper    = rsCarthelper::getInstance();
		$redshopMail   = redshopMail::getInstance();

		$arr_discount_type = array();

		$row = $orderHelper->getOrderDetails($orderId);

		$barcode_code = $row->barcode;
		$arr_discount = explode('@', $row->discount_type);
		$discount_type = '';

		for ($d = 0, $dn = count($arr_discount); $d < $dn; $d++)
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

		$body             = $this->params->get('shippment_invoice_template');

		$search[]         = "{discount_type}";
		$replace[]        = $discount_type;
		$body             = str_replace($search, $replace, $body);

		$body             = $redshopMail->imginmail($body);
		$user             = JFactory::getUser();
		$billingaddresses = RedshopHelperOrder::getOrderBillingUserInfo($orderId);
		$email            = $billingaddresses->user_email;
		$userfullname     = $billingaddresses->firstname . " " . $billingaddresses->lastname;
		$body             = $carthelper->replaceOrderTemplate($row, $body);

		return $body;
	}

	/**
	 * Merge Shipping Information PDF
	 *
	 * @param   array  $mergeOrderIds  List id of order
	 *
	 * @return  string                 Set PDF path on the viewport
	 */
	public function mergeShippingPdf($mergeOrderIds)
	{
		$pdfLocation   = 'components/com_redshop/assets/document/invoice/';
		$pdfRootPath   = JPATH_SITE . '/' . $pdfLocation;

		JArrayHelper::toInteger($mergeOrderIds);

		$pdf = RedshopHelperPdf::getPDFMerger();

		for ($m = 0, $mn = count($mergeOrderIds); $m < $mn; $m++)
		{
			$pdfName = $pdfRootPath . 'shipped_' . $mergeOrderIds[$m] . '.pdf';

			if (file_exists($pdfName))
			{
				$pdf->addPDF($pdfName, 'all');
			}
		}

		$mergedPdfFile = 'shipped_' . rand() . '.pdf';

		$pdf->merge('file', $pdfRootPath . $mergedPdfFile);

		for ($m = 0, $mn = count($mergeOrderIds); $m < $mn; $m++)
		{
			$pdfName = $pdfRootPath . 'shipped_' . $mergeOrderIds[$m] . '.pdf';

			if (JFile::exists($pdfName))
			{
				JFile::delete($pdfName);
			}
		}

		return JUri::root() . $pdfLocation . $mergedPdfFile;
	}
}
