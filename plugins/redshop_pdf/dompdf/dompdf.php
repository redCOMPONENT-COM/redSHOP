<?php
/**
 * @package     Redshopb.Plugin
 * @subpackage  redshop_pdf
 *
 * @copyright   Copyright (C) 2012 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('JPATH_BASE') or die;

// Load redSHOP library
jimport('redshop.library');

// Load domPDF library
JLoader::import('helper', __DIR__ . '/helper');

/**
 * PlgRedshop_PdfDomPDF class.
 *
 * @package  Redshopb.Plugin
 * @since    1.0.0
 */
class PlgRedshop_PdfDomPDF extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 */
	protected $autoloadLanguage = true;

	/**
	 * Event for create PDF file of order.
	 *
	 * @param   int      $orderId  Id of order.
	 * @param   string   $pdfHtml  Html template of PDF
	 * @param   string   $code     Code when generate PDF.
	 * @param   boolean  $isEmail  Is generate for use in Email?
	 *
	 * @return  string            Name of PDF file.
	 *
	 * @since   1.0.0
	 */
	public function onRedshopOrderCreateInvoicePdf($orderId = 0, $pdfHtml = '', $code = 'F', $isEmail = false)
	{
		if (!$orderId || empty($pdfHtml))
		{
			return false;
		}

		// Load payment languages
		RedshopHelperPayment::loadLanguages();

		// Changed font to support Unicode Characters - Specially Polish Characters
		$pdfObj = new PlgRedshop_PdfDomPDFHelper;
		$pdfObj->loadHtml($pdfHtml, 'utf-8');
		$pdfObj->render();
		$invoiceFolder = JPATH_SITE . '/components/com_redshop/assets/document/invoice/';

		if (!$isEmail)
		{
			ob_end_clean();

			if ($code == 'F')
			{
				file_put_contents($invoiceFolder . '/' . $orderId . ".pdf", $pdfObj->output());

				return $orderId;
			}

			$pdfObj->stream($invoiceFolder . '/' . $orderId . ".pdf");

			return $orderId;
		}

		$invoiceFolder .= $orderId;
		$invoicePdf = 'invoice-' . round(microtime(true) * 1000);

		// Delete currently order invoice
		if (JFolder::exists($invoiceFolder))
		{
			JFolder::delete($invoiceFolder);
		}

		JFolder::create($invoiceFolder);

		ob_end_clean();

		if ($code == 'F')
		{
			file_put_contents($invoiceFolder . '/' . $invoicePdf . ".pdf", $pdfObj->output());

			return $orderId;
		}

		$pdfObj->stream($invoiceFolder . '/' . $invoicePdf . ".pdf");

		return $invoicePdf;
	}

	/**
	 * Event for create PDF file of multi-order.
	 *
	 * @param   array   $orderIds  Id of order.
	 * @param   string  $pdfHtml   Html template of PDF
	 *
	 * @return  string
	 *
	 * @since  1.0.0
	 */
	public function onRedshopOrderCreateMultiInvoicePdf($orderIds = array(), $pdfHtml = '')
	{
		if (empty($orderIds) || empty($pdfHtml))
		{
			return '';
		}

		$cartHelper = rsCarthelper::getInstance();

		// Changed font to support Unicode Characters - Specially Polish Characters
		$pdfObj   = new PlgRedshop_PdfDomPDFHelper;
		$messages = array();

		foreach ($orderIds as $orderId)
		{
			$ordersDetail = RedshopHelperOrder::getOrderDetails($orderId);
			$message = $pdfHtml;

			$printTag = "<a onclick='window.print();' title='" . JText::_('COM_REDSHOP_PRINT') . "'>"
				. "<img src=" . JSYSTEM_IMAGES_PATH . "printButton.png  alt='" . JText::_('COM_REDSHOP_PRINT') . "' title='"
				. JText::_('COM_REDSHOP_PRINT') . "' /></a>";

			$message = str_replace("{print}", $printTag, $message);
			$message = str_replace("{order_mail_intro_text_title}", JText::_('COM_REDSHOP_ORDER_MAIL_INTRO_TEXT_TITLE'), $message);
			$message = str_replace("{order_mail_intro_text}", JText::_('COM_REDSHOP_ORDER_MAIL_INTRO_TEXT'), $message);
			$message = $cartHelper->replaceOrderTemplate($ordersDetail, $message, true);
			$message .= '<div class="dom-pdf-page-break"></div>';

			$message = RedshopHelperMail::imgInMail($message);

			$messages[] = $message;
		}

		$pdfObj->loadHtml('<style>.dom-pdf-page-break {page-break-after: always;}</style>' . implode('', $messages), 'utf-8');
		$pdfObj->render();

		$invoicePdfName = "multiprintorder" . round(microtime(true) * 1000);

		ob_end_clean();

		file_put_contents(JPATH_SITE . '/components/com_redshop/assets/document/invoice/' . $invoicePdfName . ".pdf", $pdfObj->output());
		$storeFiles = array('index.html', '' . $invoicePdfName . '.pdf');

		foreach (glob(JPATH_SITE . "/components/com_redshop/assets/document/invoice/*") as $file)
		{
			if (!in_array(basename($file), $storeFiles))
			{
				unlink($file);
			}
		}

		return $invoicePdfName;
	}

	/**
	 * Event for create gift card Pdf file.
	 *
	 * @param   object  $giftCard  Gift card data.
	 * @param   string  $template  HTML code of template.
	 *
	 * @return  string             Name of generated PDF file.
	 *
	 * @since   1.0.0
	 */
	public function onRedshopOrderCreateGiftCard($giftCard = null, $template = '')
	{
		if (empty($giftCard) || empty($template))
		{
			return '';
		}

		$pdf = new PlgRedshop_PdfDomPDFHelper;
		$pdf->loadHtml($template, 'utf-8');
		$pdf->render();
		$pdfName = time();
		ob_end_clean();
		file_put_contents(JPATH_SITE . '/components/com_redshop/assets/orders/' . $pdfName . ".pdf", $pdf->output());

		return $pdfName;
	}

	/**
	 * Event for create shipped invoice PDF file of order.
	 *
	 * @param   int     $orderId  Id of order.
	 * @param   string  $pdfHtml  Html template of PDF
	 *
	 * @return  string            Name of PDF file.
	 *
	 * @since   1.0.0
	 */
	public function onRedshopPdfCreateShippedInvoice($orderId = 0, $pdfHtml = '')
	{
		if (!$orderId || empty($pdfHtml))
		{
			return false;
		}

		// Load payment languages
		RedshopHelperPayment::loadLanguages();

		// Changed font to support Unicode Characters - Specially Polish Characters
		$pdfObj = new PlgRedshop_PdfDomPDFHelper;
		$pdfObj->loadHtml($pdfHtml, 'utf-8');
		$pdfObj->render();

		$pdfName = 'shipped_' . $orderId;
		ob_end_clean();
		file_put_contents(JPATH_SITE . '/components/com_redshop/assets/document/invoice/' . $pdfName . ".pdf", $pdfObj->output());

		return $pdfName;
	}

	/**
	 * Event for generate stock note PDF of order.
	 *
	 * @param   object  $orderData  Order detail
	 * @param   string  $pdfHtml    Html template of PDF
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function onRedshopOrderGenerateStockNotePdf($orderData = null, $pdfHtml = '')
	{
		if (empty($orderData) || empty($pdfHtml))
		{
			return;
		}

		// Load payment languages
		RedshopHelperPayment::loadLanguages();

		// Changed font to support Unicode Characters - Specially Polish Characters
		$pdfObj = new PlgRedshop_PdfDomPDFHelper;
		$pdfObj->loadHtml($pdfHtml, 'utf-8');
		$pdfObj->render();

		ob_end_clean();

		$pdfObj->stream('order_stock_note_' . $orderData->order_id . '.pdf');
	}

	/**
	 * Event for generate invoice PDF of order.
	 *
	 * @param   object  $orderData  Order detail
	 * @param   string  $pdfHtml    Html template of PDF
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function onRedshopOrderGenerateShippingPdf($orderData = null, $pdfHtml = '')
	{
		if (empty($orderData) || empty($pdfHtml))
		{
			return;
		}

		// Load payment languages
		RedshopHelperPayment::loadLanguages();

		// Changed font to support Unicode Characters - Specially Polish Characters
		$pdfObj = new PlgRedshop_PdfDomPDFHelper;
		$pdfObj->loadHtml($pdfHtml, 'utf-8');
		$pdfObj->render();

		ob_end_clean();

		$pdfObj->stream('Order_' . $orderData->order_id . '.pdf');
	}
}
