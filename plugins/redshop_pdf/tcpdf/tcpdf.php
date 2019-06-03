<?php
/**
 * @package     Redshopb.Plugin
 * @subpackage  redshop_pdf
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

use Redshop\Order\Template;


defined('JPATH_BASE') or die;

// Load redSHOP library
jimport('redshop.library');

// Load tcPDF library
JLoader::import('helper', __DIR__ . '/helper');

/**
 * PlgRedshop_PdfTcPDF class.
 *
 * @package  Redshopb.Plugin
 * @since    1.0.0
 */
class PlgRedshop_PdfTcPDF extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var boolean
	 */
	protected $autoloadLanguage = true;

	/**
	 * @var string
	 */
	protected $fontFile;

	/**
	 * @var string
	 */
	protected $fontName;

	/**
	 * @var object
	 */
	protected $tcpdf;

	/**
	 * __construct
	 *
	 * @param   mixed $subject subject
	 * @param   array $config  config
	 */
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);

		$this->tcpdf = new PlgRedshop_PdfTcPDFHelper;
		$this->tcpdf->setFontSubsetting(true);
		$this->tcpdf->SetAuthor(JText::_('LIB_REDSHOP_PDF_CREATOR'));
		$this->tcpdf->SetCreator(JText::_('LIB_REDSHOP_PDF_CREATOR'));
		$this->tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$this->tcpdf->SetMargins(8, 8, 8);
	}

	/**
	 * settingTCPDF
	 *
	 * @param   integer $setHeaderFont setHeaderFont
	 * @param   integer $setFont       setFont
	 *
	 * @return void
	 */
	public function settingTCPDF($setHeaderFont = 8, $setFont = 6)
	{
		if ($this->getFont($this->params->get('fontPDF')))
		{
			$path = JPATH_ROOT . '/media/com_redshop/fonts/';

			$this->fontName = TCPDF_FONTS::addTTFfont($path . $this->fontFile, 'TrueTypeUnicode', 32);
		}
		else
		{
			$this->fontName = array_key_exists($this->fontFile, $this->tcpdf->coreFonts) ? $this->fontFile : 'times';
		}

		$this->tcpdf->SetFont($this->fontName, '', $setFont);
		$this->tcpdf->setHeaderFont(array($this->fontName, '', $setHeaderFont));
		$this->tcpdf->AddPage();
	}

	/**
	 * get font
	 *
	 * @param   string $params params
	 *
	 * @return boolean
	 */
	public function getFont($params)
	{
		if (strstr($params, 'ttf'))
		{
			$ext = explode('.', $params);

			$this->fontFile = $ext[1] . '.' . $ext[0];

			return true;
		}

		if (substr($params, -1) == 'i')
		{
			if (substr($params, -2) == 'bi')
			{
				$this->fontFile = str_replace('bi', 'BI', $params);

				return false;
			}

			$this->fontFile = strrev(ucfirst(strrev($params)));

			return false;
		}
		elseif (substr($params, -1) == 'b')
		{
			$this->fontFile = strrev(ucfirst(strrev($params)));

			return false;
		}
		else
		{
			$this->fontFile = $params;

			return false;
		}
	}

	/**
	 * Event for create PDF file of order.
	 *
	 * @param   int     $orderId Id of order.
	 * @param   string  $pdfHtml Html template of PDF.
	 * @param   string  $code    Code when generate PDF.
	 * @param   boolean $isEmail Is generate for use in Email?
	 *
	 * @return  string|boolean            Name of PDF file.
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

		$this->tcpdf->SetTitle(JText::sprintf('PLG_REDSHOP_PDF_TCPDF_INVOICE_TITLE', $orderId));
		$this->settingTCPDF();
		$this->tcpdf->writeHTML($pdfHtml);

		$invoiceFolder = JPATH_SITE . '/components/com_redshop/assets/document/invoice/';

		if (!$isEmail)
		{
			ob_end_clean();
			$this->tcpdf->Output($invoiceFolder . '/' . $orderId . ".pdf", $code);

			return $orderId;
		}

		$invoiceFolder .= $orderId;
		$invoicePdf     = 'invoice-' . round(microtime(true) * 1000);

		// Delete currently order invoice
		if (JFolder::exists($invoiceFolder))
		{
			JFolder::delete($invoiceFolder);
		}

		JFolder::create($invoiceFolder);

		ob_end_clean();

		$this->tcpdf->Output($invoiceFolder . '/' . $invoicePdf . ".pdf", $code);

		return $invoicePdf;
	}

	/**
	 * Event for create PDF file of multi-order.
	 *
	 * @param   array  $orderIds Id of order.
	 * @param   string $pdfHtml  Html template of PDF
	 *
	 * @return  string
	 *
	 * @since   1.0.0
	 */
	public function onRedshopOrderCreateMultiInvoicePdf($orderIds = array(), $pdfHtml = '')
	{
		if (empty($orderIds) || empty($pdfHtml))
		{
			return '';
		}

		RedshopHelperPayment::loadLanguages();

		// Changed font to support Unicode Characters - Specially Polish Characters
		$this->tcpdf->SetTitle(JText::_('PLG_REDSHOP_PDF_TCPDF_MULTI_INVOICE_TITLE'));
		$this->settingTCPDF();

		foreach ($orderIds as $orderId)
		{
			$ordersDetail = RedshopEntityOrder::getInstance($orderId)->getItem();
			$message      = $pdfHtml;

			$printTag = "<a onclick='window.print();' title='" . JText::_('COM_REDSHOP_PRINT') . "'>"
				. "<img src=" . JSYSTEM_IMAGES_PATH . "printButton.png  alt='" . JText::_('COM_REDSHOP_PRINT') . "' title='"
				. JText::_('COM_REDSHOP_PRINT') . "' /></a>";

			$message = str_replace("{print}", $printTag, $message);
			$message = str_replace("{order_mail_intro_text_title}", JText::_('COM_REDSHOP_ORDER_MAIL_INTRO_TEXT_TITLE'), $message);
			$message = str_replace("{order_mail_intro_text}", JText::_('COM_REDSHOP_ORDER_MAIL_INTRO_TEXT'), $message);
			$message = Template::replaceTemplate($ordersDetail, $message, true);

			$this->tcpdf->WriteHTML($message, true, false, true, false, '');
		}

		$invoicePdfName = "multiprintorder" . round(microtime(true) * 1000);
		$this->tcpdf->Output(JPATH_SITE . '/components/com_redshop/assets/document/invoice/' . $invoicePdfName . ".pdf", "F");
		$storeFiles = array('index.html', '' . $invoicePdfName . '.pdf');

		foreach (glob(JPATH_SITE . "/components/com_redshop/assets/document/invoice/*") as $file)
		{
			if (!in_array(basename($file), $storeFiles))
			{
				JFile::delete($file);
			}
		}

		return $invoicePdfName;
	}

	/**
	 * Event for create gift card Pdf file.
	 *
	 * @param   object $giftCard Gift card data.
	 * @param   string $template HTML code of template.
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

		$pdf = new PlgRedshop_PdfTcPDFHelper('P', 'mm', 'A4');

		if (file_exists(REDSHOP_FRONT_IMAGES_RELPATH . 'giftcard/' . $giftCard->giftcard_bgimage) && $giftCard->giftcard_bgimage)
		{
			$pdf->backgroundImage = REDSHOP_FRONT_IMAGES_RELPATH . 'giftcard/' . $giftCard->giftcard_bgimage;
		}

		$this->tcpdf->SetCreator(PDF_CREATOR);
		$this->tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$this->tcpdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$this->tcpdf->SetHeaderMargin(0);
		$this->tcpdf->SetFooterMargin(0);
		$this->tcpdf->setPrintFooter(false);
		$this->tcpdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
		$this->settingTCPDF(PDF_FONT_SIZE_MAIN, 18);
		$this->tcpdf->writeHTML($template, true, false, false, false, '');
		$pdfName = time();
		$this->tcpdf->Output(JPATH_SITE . '/components/com_redshop/assets/orders/' . $pdfName . ".pdf", "F");

		return $pdfName;
	}

	/**
	 * Event for create shipped invoice PDF file of order.
	 *
	 * @param   int    $orderId Id of order.
	 * @param   string $pdfHtml Html template of PDF
	 *
	 * @return  string|boolean            Name of PDF file.
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

		$this->tcpdf->SetTitle(JText::_('PLG_REDSHOP_PDF_TCPDF_SHIPPED_INVOICE_TITLE'));
		$this->tcpdf->SetMargins(20, 85, 20);
		$this->settingTCPDF();

		// Writing Body area
		$this->tcpdf->WriteHTML($pdfHtml, true, false, true, false, '');

		$pdfName = 'shipped_' . $orderId;
		$this->tcpdf->Output(JPATH_SITE . '/components/com_redshop/assets/document/invoice/' . $pdfName . ".pdf", "F");

		return $pdfName;
	}

	/**
	 * Event for generate stock note PDF of order.
	 *
	 * @param   object $orderData Order detail
	 * @param   string $pdfHtml   Html template of PDF
	 *
	 * @return  void.
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
		$this->tcpdf->SetTitle(JText::sprintf('PLG_REDSHOP_PDF_TCPDF_ORDER_STOCK_NOTE_TITLE', $orderData->order_id));
		$this->tcpdf->SetMargins(15, 15, 15);
		$this->tcpdf->SetHeaderData('', '', '', JText::_('COM_REDSHOP_ORDER') . ' ' . $orderData->order_id);
		$this->settingTCPDF(10, 10);
		$this->tcpdf->WriteHTML($pdfHtml);
		$this->tcpdf->Output('order_stock_note_' . $orderData->order_id . '.pdf', 'D');
	}

	/**
	 * Event for generate invoice PDF of order.
	 *
	 * @param   object $orderData Order detail
	 * @param   string $pdfHtml   Html template of PDF
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
		$this->tcpdf->SetTitle(JText::_('COM_REDSHOP_ORDER') . ': ' . $orderData->order_id);
		$this->tcpdf->SetMargins(15, 15, 15);
		$this->tcpdf->SetHeaderData('', '', '', JText::_('COM_REDSHOP_ORDER') . ': ' . $orderData->order_id);
		$this->settingTCPDF(10, 12);
		$this->tcpdf->WriteHTML($pdfHtml);
		$this->tcpdf->Output('Order_' . $orderData->order_id . ".pdf", "D");
	}
}
