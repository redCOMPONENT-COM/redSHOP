<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::load('RedshopHelperHelper');
JLoader::load('RedshopHelperCart');
JLoader::load('RedshopHelperProduct');
JLoader::load('RedshopHelperExtra_field');

JLoader::load('RedshopHelperAdminExtra_field');
JLoader::load('RedshopHelperAdminConfiguration');
JLoader::load('RedshopHelperAdminOrder');
JLoader::load('RedshopHelperAdminQuotation');
JLoader::load('RedshopHelperAdminImages');

class redshopMail
{
	public $_table_prefix = null;

	public $db = null;

	public $_carthelper = null;

	public $_redhelper = null;

	protected static $mailTemplates = array();

	public function __construct()
	{
		$this->_db = JFactory::getDbo();

		$this->_table_prefix = '#__redshop_';

		$this->_carthelper      = new rsCarthelper;
		$this->_redhelper       = new redhelper;
		$this->_order_functions = new order_functions;
	}

	/**
	 * Method to get mail section
	 *
	 * @param   int     $tid        Template id
	 * @param   string  $section    Template section
	 * @param   string  $extraCond  Extra condition for query
	 *
	 * @return  array
	 */
	public function getMailtemplate($tid = 0, $section = '', $extraCond = '')
	{
		$key = $tid . '_' . $section . '_' . serialize($extraCond);

		if (!array_key_exists($key, self::$mailTemplates))
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('*')
				->from($db->qn('#__redshop_mail'))
				->where('published = 1');

			if ($tid)
			{
				$query->where('mail_id = ' . (int) $tid);
			}

			if ($section)
			{
				$query->where('mail_section = ' . $db->q($section));
			}

			if ($extraCond)
			{
				$query->where($extraCond);
			}

			self::$mailTemplates[$key] = $db->setQuery($query)->loadObjectlist();
		}

		return self::$mailTemplates[$key];
	}

	/**
	 * sendOrderMail function.
	 *
	 * @param   int  $order_id  Order ID.
	 *
	 * @return bool
	 */
	public function sendOrderMail($order_id, $onlyAdmin = false)
	{
		$redconfig = new Redconfiguration;
		$producthelper = new producthelper;
		$session = JFactory::getSession();

		$config = JFactory::getConfig();

		// Set the e-mail parameters
		$from = $config->get('mailfrom');
		$fromname = $config->get('fromname');
		$user = JFactory::getUser();

		if (USE_AS_CATALOG)
		{
			$mailinfo = $this->getMailtemplate(0, "catalogue_order");
		}
		else
		{
			$mailinfo = $this->getMailtemplate(0, "order");
		}

		if (count($mailinfo) > 0)
		{
			$message = $mailinfo[0]->mail_body;
			$subject = $mailinfo[0]->mail_subject;
		}
		else
		{
			return false;
		}

		$cart = '';
		$row = $this->_order_functions->getOrderDetails($order_id);

		$orderpayment = $this->_order_functions->getOrderPaymentDetail($order_id);
		$paymentmethod = $this->_order_functions->getPaymentMethodInfo($orderpayment[0]->payment_method_class);

		$paymentmethod = $paymentmethod[0];

		// It is necessory to take billing info from order user info table
		// Order mail output should reflect the checkout process"

		$message = str_replace("{order_mail_intro_text_title}", JText::_('COM_REDSHOP_ORDER_MAIL_INTRO_TEXT_TITLE'), $message);
		$message = str_replace("{order_mail_intro_text}", JText::_('COM_REDSHOP_ORDER_MAIL_INTRO_TEXT'), $message);

		$message = $this->_carthelper->replaceOrderTemplate($row, $message);
		$rowitem = $this->_order_functions->getOrderItemDetail($order_id);

		$manufacturer_email = array();
		$supplier_email = array();
		$reddesign_attachment = array();
		$cartArr = array();

		$cart_mdata           = '';

		for ($i = 0; $i < count($rowitem); $i++)
		{
			$product          = $producthelper->getProductById($rowitem[$i]->product_id);
			$manufacturerData = $producthelper->getSection("manufacturer", $product->manufacturer_id);

			if (count($manufacturerData) > 0)
			{
				if ($manufacturerData->manufacturer_email != '')
				{
					$manufacturer_email[$i] = $manufacturerData->manufacturer_email;
				}
			}

			$supplierData = $producthelper->getSection("supplier", $product->supplier_id);

			if (count($supplierData) > 0)
			{
				if ($supplierData->supplier_email != '')
				{
					$supplier_email[$i] = $supplierData->supplier_email;
				}
			}
		}

		$arr_discount_type = array();
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

		$search[]        = "{discount_type}";
		$replace[]       = $discount_type;
		$split_amount    = 0;

		$issplitdisplay  = '';
		$issplitdisplay2 = '';

		if ($row->split_payment)
		{
			$issplitdisplay = "<br/>" . JText::_('COM_REDSHOP_RECEIPT_PARTIALLY_PAID_AMOUNT') . ": "
				. $producthelper->getProductFormattedPrice($split_amount);
			$issplitdisplay2 = "<br/>" . JText::_('COM_REDSHOP_REMAINING_PARTIALLY_AMOUNT') . ": "
				. $producthelper->getProductFormattedPrice($split_amount);
		}

		$orderdetailurl   = JURI::root() . 'index.php?option=com_redshop&view=order_detail&oid=' . $order_id . '&encr=' . $row->encr_key;
		$search[]         = "{order_detail_link}";
		$replace[]        = "<a href='" . $orderdetailurl . "'>" . JText::_("COM_REDSHOP_ORDER_MAIL") . "</a>";

		$billingaddresses = $this->_order_functions->getOrderBillingUserInfo($order_id);
		$message          = str_replace($search, $replace, $message);
		$message          = $this->imginmail($message);
		$thirdpartyemail  = $billingaddresses->thirdparty_email;

		if ($session->get('isredcrmuser'))
		{
			$email = $user->email;
		}
		else
		{
			$email = $billingaddresses->user_email;
		}

		$search[]      = "{order_id}";
		$replace[]     = $row->order_id;
		$search[]      = "{order_number}";
		$replace[]     = $row->order_number;
		$search_sub[]  = "{order_id}";
		$replace_sub[] = $row->order_id;
		$search_sub[]  = "{order_number}";
		$replace_sub[] = $row->order_number;
		$search_sub[]  = "{shopname}";
		$replace_sub[] = SHOP_NAME;
		$search_sub[]  = "{order_date}";
		$replace_sub[] = $redconfig->convertDateFormat($row->cdate);
		$subject       = str_replace($search_sub, $replace_sub, $subject);

		// Send the e-mail
		if ($email != "")
		{
			$mailbcc = array();

			if (trim($mailinfo[0]->mail_bcc) != "")
			{
				$mailbcc = explode(",", $mailinfo[0]->mail_bcc);
			}

			$bcc      = (trim(ADMINISTRATOR_EMAIL) != '') ? explode(",", trim(ADMINISTRATOR_EMAIL)) : array();
			$bcc      = array_merge($bcc, $mailbcc);
			$fullname = $billingaddresses->firstname . " " . $billingaddresses->lastname;

			if ($billingaddresses->is_company == 1 && $billingaddresses->company_name != "")
			{
				$fullname = $billingaddresses->company_name;
			}

			$subject = str_replace("{fullname}", $fullname, $subject);
			$subject = str_replace("{firstname}", $billingaddresses->firstname, $subject);
			$subject = str_replace("{lastname}", $billingaddresses->lastname, $subject);
			$message = str_replace("{fullname}", $fullname, $message);
			$message = str_replace("{firstname}", $billingaddresses->firstname, $message);
			$message = str_replace("{lastname}", $billingaddresses->lastname, $message);
			$body    = $message;

			// As only need to send email to administrator,
			// Here variables are changed to use bcc email - from redSHOP configuration - Administrator Email
			if ($onlyAdmin)
			{
				$email           = $bcc;
				$thirdpartyemail = '';
				$bcc             = null;
			}

			if ($thirdpartyemail != '')
			{
				if (!JMail::getInstance()->sendMail($from, $fromname, $thirdpartyemail, $subject, $body, 1, null, $bcc))
				{
					$this->setError(JText::_('COM_REDSHOP_ERROR_SENDING_CONFIRMATION_MAIL'));
				}
			}

			if (!JMail::getInstance()->sendMail($from, $fromname, $email, $subject, $body, 1, null, $bcc))
			{
				$this->setError(JText::_('COM_REDSHOP_ERROR_SENDING_CONFIRMATION_MAIL'));
			}
		}

		// As email only need to send admin no need to send email to others.
		if ($onlyAdmin)
		{
			return true;
		}

		if (MANUFACTURER_MAIL_ENABLE)
		{
			sort($manufacturer_email);

			for ($man = 0; $man < count($manufacturer_email); $man++)
			{
				if (!JMail::getInstance()->sendMail($from, $fromname, $manufacturer_email[$man], $subject, $body, 1))
				{
					$this->setError(JText::_('COM_REDSHOP_ERROR_SENDING_CONFIRMATION_MAIL'));
				}
			}
		}

		if (SUPPLIER_MAIL_ENABLE)
		{
			sort($supplier_email);

			for ($sup = 0; $sup < count($supplier_email); $sup++)
			{
				if (!JMail::getInstance()->sendMail($from, $fromname, $supplier_email[$sup], $subject, $body, 1))
				{
					$this->setError(JText::_('COM_REDSHOP_ERROR_SENDING_CONFIRMATION_MAIL'));
				}
			}
		}

		// Invoice mail send
		if (INVOICE_MAIL_ENABLE && $row->order_payment_status == "Paid")
		{
			$this->sendInvoiceMail($order_id);
		}

		return true;
	}

	public function sendOrderSpecialDiscountMail($order_id)
	{
		$redconfig     = new Redconfiguration;
		$producthelper = new producthelper;
		$extra_field   = new extra_field;

		$config        = JFactory::getConfig();
		$mailbcc       = array();
		$mailinfo      = $this->getMailtemplate(0, "order_special_discount");

		if (count($mailinfo) > 0)
		{
			$message = $mailinfo[0]->mail_body;
			$subject = $mailinfo[0]->mail_subject;

			if (trim($mailinfo[0]->mail_bcc) != "")
			{
				$mailbcc = explode(",", $mailinfo[0]->mail_bcc);
			}
		}
		else
		{
			return false;
		}

		$cart = '';

		$manufacturer_email = array();

		$row              = $this->_order_functions->getOrderDetails($order_id);
		$billingaddresses = $this->_order_functions->getOrderBillingUserInfo($order_id);
		$orderpayment     = $this->_order_functions->getOrderPaymentDetail($order_id);
		$paymentmethod    = $this->_order_functions->getPaymentMethodInfo($orderpayment[0]->payment_method_class);
		$paymentmethod    = $paymentmethod[0];
		$partialpayment   = $this->_order_functions->getOrderPartialPayment($order_id);
		$message          = $this->_carthelper->replaceOrderTemplate($row, $message);

		// Set order paymethod name
		$search[]       = "{shopname}";
		$replace[]      = SHOP_NAME;
		$search[]       = "{payment_lbl}";
		$replace[]      = JText::_('COM_REDSHOP_PAYMENT_METHOD');
		$search[]       = "{payment_method}";
		$replace[]      = "";
		$search[]       = "{special_discount}";
		$replace[]      = $row->special_discount . '%';
		$search[]       = "{special_discount_amount}";
		$replace[]      = $producthelper->getProductFormattedPrice($row->special_discount_amount);
		$search[]       = "{special_discount_lbl}";
		$replace[]      = JText::_('COM_REDSHOP_SPECIAL_DISCOUNT');

		$orderdetailurl = JURI::root() . 'index.php?option=com_redshop&view=order_detail&oid=' . $order_id . '&encr=' . $row->encr_key;
		$search[]       = "{order_detail_link}";
		$replace[]      = "<a href='" . $orderdetailurl . "'>" . JText::_("COM_REDSHOP_ORDER_MAIL") . "</a>";

		if ($paymentmethod->element == "rs_payment_banktransfer" || $paymentmethod->element == "rs_payment_banktransfer_discount"
			|| $paymentmethod->element == "rs_payment_banktransfer2" || $paymentmethod->element == "rs_payment_banktransfer3"
			|| $paymentmethod->element == "rs_payment_banktransfer4" || $paymentmethod->element == "rs_payment_banktransfer5")
		{
			$paymentpath = JPATH_SITE . '/plugins/redshop_payment/' . $paymentmethod->element . '.xml';
			$paymentparams = new JRegistry($paymentmethod->params);
			$txtextra_info = $paymentparams->get('txtextra_info', '');

			$search[] = "{payment_extrainfo}";
			$replace[] = $txtextra_info;
		}

		$message  = str_replace($search, $replace, $message);
		$message  = $this->imginmail($message);

		$email    = $billingaddresses->user_email;
		$from     = $config->get('mailfrom');
		$fromname = $config->get('fromname');
		$body     = $message;
		$subject  = str_replace($search, $replace, $subject);

		if ($email != "")
		{
			$bcc = null;

			if (trim(ADMINISTRATOR_EMAIL) != '')
			{
				$bcc = explode(",", trim(ADMINISTRATOR_EMAIL));
			}

			$bcc = array_merge($bcc, $mailbcc);

			if (SPECIAL_DISCOUNT_MAIL_SEND == '1')
			{
				if (!JMail::getInstance()->sendMail($from, $fromname, $email, $subject, $body, 1, null, $bcc))
				{
					$this->setError(JText::_('COM_REDSHOP_ERROR_SENDING_CONFIRMATION_MAIL'));
				}
			}
		}

		if (MANUFACTURER_MAIL_ENABLE)
		{
			sort($manufacturer_email);

			for ($man = 0; $man < count($manufacturer_email); $man++)
			{
				if (!JMail::getInstance()->sendMail($from, $fromname, $manufacturer_email[$man], $subject, $body, 1))
				{
					$this->setError(JText::_('COM_REDSHOP_ERROR_SENDING_CONFIRMATION_MAIL'));
				}
			}
		}

		return true;
	}

	public function createMultiprintInvoicePdf($oid)
	{
		$order_functions = new order_functions;
		$shippinghelper  = new shipping;
		$extra_field     = new extra_field;
		$redconfig       = new Redconfiguration;
		$redTemplate     = new Redtemplate;
		$producthelper   = new producthelper;
		$message         = "";

		$pdfObj = RedshopHelperPdf::getInstance();
		$pdfObj->SetTitle('Shipped');

		// Changed font to support Unicode Characters - Specially Polish Characters
		$font = 'times';
		$pdfObj->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdfObj->setHeaderFont(array($font, '', 8));

		// Set font
		$pdfObj->SetFont($font, "", 6);

		$order_id = "";

		for ($o = 0; $o < count($oid); $o++)
		{
			$message              = "";
			$order_id             = $oid[$o];
			$OrdersDetail  		  = $order_functions->getOrderDetails($order_id);
			$order_print_template = $redTemplate->getTemplate("order_print");

			if (count($order_print_template) > 0 && $order_print_template[0]->template_desc != "")
			{
				$message = $order_print_template[0]->template_desc;
			}
			else
			{
				$message = '<table style="width: 100%;" border="0" cellpadding="5" cellspacing="0">
				<tbody><tr><td colspan="2"><table style="width: 100%;" border="0" cellpadding="2" cellspacing="0"><tbody>
				<tr style="background-color: #cccccc;"><th align="left">{order_information_lbl}{print}</th></tr><tr></tr
				><tr><td>{order_id_lbl} : {order_id}</td></tr><tr><td>{order_number_lbl} : {order_number}</td></tr><tr>
				<td>{order_date_lbl} : {order_date}</td></tr><tr><td>{order_status_lbl} : {order_status}</td></tr><tr>
				<td>{shipping_method_lbl} : {shipping_method} : {shipping_rate_name}</td></tr><tr><td>{payment_lbl} : {payment_method}</td>
				</tr></tbody></table></td></tr><tr><td colspan="2"><table style="width: 100%;" border="0" cellpadding="2" cellspacing="0">
				<tbody><tr style="background-color: #cccccc;"><th align="left">{billing_address_information_lbl}</th>
				</tr><tr></tr><tr><td>{billing_address}</td></tr></tbody></table></td></tr><tr><td colspan="2">
				<table style="width: 100%;" border="0" cellpadding="2" cellspacing="0"><tbody><tr style="background-color: #cccccc;">
				<th align="left">{shipping_address_info_lbl}</th></tr><tr></tr><tr><td>{shipping_address}</td></tr></tbody>
				</table></td></tr><tr><td colspan="2"><table style="width: 100%;" border="0" cellpadding="2" cellspacing="0">
				<tbody><tr style="background-color: #cccccc;"><th align="left">{order_detail_lbl}</th></tr><tr></tr><tr><td>
				<table style="width: 100%;" border="0" cellpadding="2" cellspacing="2"><tbody><tr><td>{product_name_lbl}</td><td>{note_lbl}</td>
				<td>{price_lbl}</td><td>{quantity_lbl}</td><td align="right">Total Price</td></tr>{product_loop_start}<tr>
				<td><p>{product_name}<br />{product_attribute}{product_accessory}{product_userfields}</p></td>
				<td>{product_wrapper}{product_thumb_image}</td><td>{product_price}</td><td>{product_quantity}</td>
				<td align="right">{product_total_price}</td></tr>{product_loop_end}</tbody></table></td></tr><tr>
				<td></td></tr><tr><td><table style="width: 100%;" border="0" cellpadding="2" cellspacing="2"><tbody>
				<tr align="left"><td align="left"><strong>{order_subtotal_lbl} : </strong></td><td align="right">{order_subtotal}</td>
				</tr>{if vat}<tr align="left"><td align="left"><strong>{vat_lbl} : </strong></td><td align="right">{order_tax}</td>
				</tr>{vat end if}{if discount}<tr align="left"><td align="left"><strong>{discount_lbl} : </strong></td>
				<td align="right">{order_discount}</td></tr>{discount end if}<tr align="left"><td align="left">
				<strong>{shipping_lbl} : </strong></td><td align="right">{order_shipping}</td></tr><tr align="left">
				<td colspan="2" align="left"><hr /></td></tr><tr align="left"><td align="left"><strong>{total_lbl} :</strong>
				</td><td align="right">{order_total}</td></tr><tr align="left"><td colspan="2" align="left"><hr /><br />
				 <hr /></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table>';
			}

			$print_tag = "<a onclick='window.print();' title='" . JText::_('COM_REDSHOP_PRINT') . "'>"
				. "<img src=" . JSYSTEM_IMAGES_PATH . "printButton.png  alt='" . JText::_('COM_REDSHOP_PRINT') . "' title='"
				. JText::_('COM_REDSHOP_PRINT') . "' /></a>";

			$message = str_replace("{print}", $print_tag, $message);
			$message = str_replace("{order_mail_intro_text_title}", JText::_('COM_REDSHOP_ORDER_MAIL_INTRO_TEXT_TITLE'), $message);
			$message = str_replace("{order_mail_intro_text}", JText::_('COM_REDSHOP_ORDER_MAIL_INTRO_TEXT'), $message);

			$message = $this->_carthelper->replaceOrderTemplate($OrdersDetail, $message);

			$pdfObj->AddPage();
			$pdfObj->WriteHTML($message, true, false, true, false, '');
		}

		$invoice_pdfName = "multiprintorder";
		$pdfObj->Output(JPATH_SITE . '/components/com_redshop/assets/document/invoice/' . $invoice_pdfName . ".pdf", "F");

		return $invoice_pdfName;
	}

	function createShippedInvoicePdf($oid)
	{
		$redconfig     = new Redconfiguration;
		$producthelper = new producthelper;
		$extra_field   = new extra_field;
		$config        = JFactory::getConfig();
		$redTemplate   = new Redtemplate;
		$message       = "";
		$subject       = "";
		$cart          = '';

		$pdfObj = RedshopHelperPdf::getInstance();
		$pdfObj->SetTitle('Shipped');
		$pdfObj->SetMargins(8, 8, 8);
		$font = 'times';
		$pdfObj->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdfObj->setHeaderFont(array($font, '', 8));
		$pdfObj->SetFont($font, "", 6);

		$order_id = "";

		for ($o = 0; $o < count($oid); $o++)
		{
			$order_id          = $oid[$o];
			$arr_discount_type = array();
			$mailinfo          = $redTemplate->getTemplate("shippment_invoice_template");

			if (count($mailinfo) > 0)
			{
				$message = $mailinfo[0]->template_desc;
			}
			else
			{
				return false;
			}

			$row           = $this->_order_functions->getOrderDetails($order_id);
			$barcode_code  = $row->barcode;
			$arr_discount  = explode('@', $row->discount_type);
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

			$search[]         = "{discount_type}";
			$replace[]        = $discount_type;

			$message          = str_replace($search, $replace, $message);
			$message          = $this->imginmail($message);
			$user             = JFactory::getUser();
			$billingaddresses = $this->_order_functions->getOrderBillingUserInfo($order_id);
			$email            = $billingaddresses->user_email;
			$userfullname     = $billingaddresses->firstname . " " . $billingaddresses->lastname;
			$message          = $this->_carthelper->replaceOrderTemplate($row, $message);

			echo "<div id='redshopcomponent' class='redshop'>";

			if (strstr($message, "{barcode}"))
			{
				$img_url = REDSHOP_FRONT_IMAGES_RELPATH . "barcode/" . $barcode_code . ".png";

				// For pdf
				if (function_exists("curl_init"))
				{
					$bar_codeIMG = '<img src="' . $img_url . '" alt="Barcode"  border="0" />';
					$message = str_replace("{barcode}", $bar_codeIMG, $message);
				}
			}

			$body = $message;
			$pdfObj->AddPage();
			$pdfObj->WriteHTML($body, true, false, true, false, '');
		}

		$rand = rand();
		$invoice_pdfName = "shipped_" . $rand;
		$pdfObj->Output(JPATH_SITE . '/components/com_redshop/assets/document/invoice/' . $invoice_pdfName . ".pdf", "F");

		return $invoice_pdfName;
	}

	public function sendInvoiceMail($order_id)
	{
		$redconfig         = new Redconfiguration;
		$producthelper     = new producthelper;
		$extra_field       = new extra_field;

		$config            = JFactory::getConfig();
		$message           = "";
		$subject           = "";
		$cart              = '';
		$mailbcc           = null;
		$arr_discount_type = array();

		$mailinfo          = $this->getMailtemplate(0, "invoicefile_mail");

		if (count($mailinfo) > 0)
		{
			$message = $mailinfo[0]->mail_body;
			$subject = $mailinfo[0]->mail_subject;

			if (trim($mailinfo[0]->mail_bcc) != "")
			{
				$mailbcc = explode(",", $mailinfo[0]->mail_bcc);
			}
		}
		else
		{
			return false;
		}

		$row           = $this->_order_functions->getOrderDetails($order_id);
		$barcode_code  = $row->barcode;
		$arr_discount  = explode('@', $row->discount_type);
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

		$search[]         = "{discount_type}";
		$replace[]        = $discount_type;

		// Set order paymethod name
		$search_sub[]     = "{order_id}";
		$replace_sub[]    = $row->order_id;
		$search_sub[]     = "{order_number}";
		$replace_sub[]    = $row->order_number;
		$search_sub[]     = "{shopname}";
		$replace_sub[]    = SHOP_NAME;

		$message          = str_replace($search, $replace, $message);
		$message          = $this->imginmail($message);
		$user             = JFactory::getUser();
		$billingaddresses = $this->_order_functions->getOrderBillingUserInfo($order_id);
		$email            = $billingaddresses->user_email;
		$userfullname     = $billingaddresses->firstname . " " . $billingaddresses->lastname;
		$search_sub[]     = "{fullname}";
		$replace_sub[]    = $userfullname;
		$search_sub[]     = "{order_date}";
		$replace_sub[]    = $redconfig->convertDateFormat($row->cdate);
		$subject          = str_replace($search_sub, $replace_sub, $subject);

		// Set the e-mail parameters
		$from             = $config->get('mailfrom');
		$fromname         = $config->get('fromname');
		$message          = $this->_carthelper->replaceOrderTemplate($row, $message);
		$message          = str_replace("{firstname}", $billingaddresses->firstname, $message);
		$message          = str_replace("{lastname}", $billingaddresses->lastname, $message);
		$body             = $message;
		$body1            = $message;
		$img_url1         = REDSHOP_FRONT_IMAGES_ABSPATH . "barcode/" . $barcode_code . ".png";
		$img_url          = REDSHOP_FRONT_IMAGES_RELPATH . "barcode/" . $barcode_code . ".png";

		// For pdf
		if (function_exists("curl_init"))
		{
			$bar_codeIMG  = '<img src="' . $img_url . '" alt="Barcode"  border="0" />';
			$body         = str_replace("{barcode}", $bar_codeIMG, $body);

			// For mail
			$bar_codeIMG1 = '<img src="' . $img_url1 . '" alt="Barcode"  border="0" />';
			$body1        = str_replace("{barcode}", $bar_codeIMG1, $body1);
		}

		$message = $this->_carthelper->replaceOrderTemplate($row, $message);
		ob_clean();

		echo "<div id='redshopcomponent' class='redshop'>";

		$pdfObj = RedshopHelperPdf::getInstance();
		$pdfObj->SetTitle(JText::_('COM_REDSHOP_INVOICE') . $row->order_id);
		$pdfObj->SetMargins(15, 15, 15);
		$pdfObj->setHeaderFont(array('times', '', 10));
		$pdfObj->AddPage();
		$pdfObj->WriteHTML($body, true, false, true, false, '');

		$invoice_pdfName = $row->order_id;

		$pdfObj->Output(JPATH_SITE . '/components/com_redshop/assets/document/invoice/' . $invoice_pdfName . ".pdf", "F");
		$invoice_attachment = JPATH_SITE . '/components/com_redshop/assets/document/invoice/' . $invoice_pdfName . ".pdf";

		if ((INVOICE_MAIL_SEND_OPTION == 2 || INVOICE_MAIL_SEND_OPTION == 3) && $email != "")
		{
			if (!JMail::getInstance()->sendMail($from, $fromname, $email, $subject, $body1, 1, null, $mailbcc, $invoice_attachment))
			{
				$this->setError(JText::_('COM_REDSHOP_ERROR_SENDING_CONFIRMATION_MAIL'));

				return false;
			}
		}

		if ((INVOICE_MAIL_SEND_OPTION == 1 || INVOICE_MAIL_SEND_OPTION == 3) && ADMINISTRATOR_EMAIL != '')
		{
			$sendto = explode(",", trim(ADMINISTRATOR_EMAIL));

			if (!JMail::getInstance()->sendMail($from, $fromname, $sendto, $subject, $body1, 1, null, $mailbcc, $invoice_attachment))
			{
				$this->setError(JText::_('COM_REDSHOP_ERROR_SENDING_CONFIRMATION_MAIL'));

				return false;
			}
		}

		return true;
	}

	public function sendRegistrationMail(&$data)
	{
		$app = JFactory::getApplication();

		$acl = JFactory::getACL();
		$db  = JFactory::getDbo();
		$me  = JFactory::getUser();

		$mainpassword = JRequest::getVar('password1', '', 'post', 'string', JREQUEST_ALLOWRAW);

		$MailFrom = $app->getCfg('mailfrom');
		$FromName = $app->getCfg('fromname');
		$SiteName = $app->getCfg('sitename');

		/*
	 	 * Time for the email magic so get ready to sprinkle the magic dust...
	 	 */
		$adminEmail   = $me->get('email');
		$adminName    = $me->get('name');
		$maildata     = "";
		$mailsubject  = "";
		$mailbcc      = array();
		$mailtemplate = $this->getMailtemplate(0, "register");

		if (count($mailtemplate) > 0)
		{
			$maildata    = $mailtemplate[0]->mail_body;
			$mailsubject = $mailtemplate[0]->mail_subject;

			if (trim($mailtemplate[0]->mail_bcc) != "")
			{
				$mailbcc = explode(",", $mailtemplate[0]->mail_bcc);
			}
		}
		else
		{
			return false;
		}

		$search    = array();
		$replace   = array();
		$search[]  = "{shopname}";
		$search[]  = "{firstname}";
		$search[]  = "{lastname}";
		$search[]  = "{fullname}";
		$search[]  = "{name}";
		$search[]  = "{username}";
		$search[]  = "{password}";
		$search[]  = "{email}";
		$search[]  = '{account_link}';

		$replace[] = SHOP_NAME;
		$replace[] = $data['firstname'];
		$replace[] = $data['lastname'];
		$replace[] = $data['firstname'] . " " . $data['lastname'];
		$replace[] = $data['name'];
		$replace[] = $data['username'];
		$replace[] = $mainpassword;
		$replace[] = $data['email'];
		$replace[] = '<a href="' . JURI::root() . 'index.php?option=com_redshop&view=account'
			. '" target="_blank">' . JText::_('COM_REDSHOP_ACCOUNT_LINK') . '</a>';

		$mailbody    = str_replace($search, $replace, $maildata);
		$mailsubject = str_replace($search, $replace, $mailsubject);

		if ($MailFrom != '' && $FromName != '')
		{
			$adminName  = $FromName;
			$adminEmail = $MailFrom;
		}

		$bcc = array();

		if ($mailbody && $data['email'] != "")
		{
			if (trim(ADMINISTRATOR_EMAIL) != '')
			{
				$bcc = explode(",", trim(ADMINISTRATOR_EMAIL));
			}

			$bcc = array_merge($bcc, $mailbcc);
			JMail::getInstance()->sendMail($MailFrom, $FromName, $data['email'], $mailsubject, $mailbody, 1, null, $bcc);
		}

		// Tax exempt waiting approval mail
		if (USE_TAX_EXEMPT && $post['tax_exempt'] == 1)
		{
			$this->sendTaxExemptMail("tax_exempt_waiting_approval_mail", $post, $bcc);
		}

		return true;
	}

	public function sendTaxExemptMail($section, $userinfo = array(), $email = "")
	{
		if (USE_TAX_EXEMPT)
		{
			$app          = JFactory::getApplication();

			$MailFrom     = $app->getCfg('mailfrom');
			$FromName     = $app->getCfg('fromname');
			$mailbcc      = null;
			$maildata     = $section;
			$mailsubject  = $section;
			$mailtemplate = $this->getMailtemplate(0, $section);

			if (count($mailtemplate) > 0)
			{
				$maildata    = html_entity_decode($mailtemplate[0]->mail_body, ENT_QUOTES);
				$mailsubject = html_entity_decode($mailtemplate[0]->mail_subject, ENT_QUOTES);

				if (trim($mailtemplate[0]->mail_bcc) != "")
				{
					$mailbcc = explode(",", $mailtemplate[0]->mail_bcc);
				}
			}

			$search    = array();
			$replace   = array();

			$search[]  = "{username}";
			$search[]  = "{shopname}";
			$search[]  = "{name}";
			$search[]  = "{company_name}";
			$search[]  = "{address}";
			$search[]  = "{city}";
			$search[]  = "{zipcode}";
			$search[]  = "{country}";
			$search[]  = "{phone}";
			$replace[] = $userinfo['username'];
			$replace[] = SHOP_NAME;
			$replace[] = $userinfo['firstname'] . ' ' . $userinfo['lastname'];

			if ($userinfo['is_company'] == 1)
			{
				$replace[] = $userinfo['company_name'];
			}
			else
			{
				$replace[] = "";
			}

			$replace[] = $userinfo['address'];
			$replace[] = $userinfo['city'];
			$replace[] = $userinfo['zipcode'];
			$replace[] = $this->_order_functions->getCountryName($userinfo['country_code']);
			$replace[] = $userinfo['phone'];

			$maildata = str_replace($search, $replace, $maildata);

			if ($email != "")
			{
				JMail::getInstance()->sendMail($MailFrom, $FromName, $email, $mailsubject, $maildata, 1, null, $mailbcc);
			}
		}

		return true;
	}

	function sendSubscriptionRenewalMail($data = array())
	{
		$app           = JFactory::getApplication();

		$producthelper = new producthelper;
		$redconfig     = new Redconfiguration;

		$MailFrom      = $app->getCfg('mailfrom');
		$FromName      = $app->getCfg('fromname');
		$SiteName      = $app->getCfg('sitename');

		$user_email    = "";
		$firstname     = "";
		$lastname      = "";
		$maildata      = "";
		$mailsubject   = "";
		$mailbcc       = null;
		$mailtemplate  = $this->getMailtemplate(0, "subscription_renewal_mail");

		if (count($mailtemplate) > 0)
		{
			$mailtemplate = $mailtemplate[0];
			$maildata     = $mailtemplate->mail_body;
			$mailsubject  = $mailtemplate->mail_subject;

			if (trim($mailtemplate->mail_bcc) != "")
			{
				$mailbcc = explode(",", $mailtemplate->mail_bcc);
			}
		}
		else
		{
			return false;
		}

		$userdata = $this->_order_functions->getBillingAddress($data->user_id);

		if (count($userdata) > 0)
		{
			$user_email = $userdata->user_email;
			$firstname  = $userdata->firstname;
			$lastname   = $userdata->lastname;
		}

		$product             = $producthelper->getProductById($data->product_id);
		$productSubscription = $producthelper->getProductSubscriptionDetail($data->product_id, $data->subscription_id);

		$search    = array();
		$replace   = array();
		$search[]  = "{shopname}";
		$search[]  = "{firstname}";
		$search[]  = "{lastname}";
		$search[]  = "{product_name}";
		$search[]  = "{subsciption_enddate}";
		$search[]  = "{subscription_period}";
		$search[]  = "{subscription_price}";
		$search[]  = "{product_link}";

		$replace[] = SHOP_NAME;
		$replace[] = $firstname;
		$replace[] = $lastname;
		$replace[] = $product->product_name;
		$replace[] = $redconfig->convertDateFormat($data->end_date);
		$replace[] = $productSubscription->subscription_period . " " . $productSubscription->period_type;
		$replace[] = $producthelper->getProductFormattedPrice($productSubscription->subscription_price);

		$producturl  = JURI::root() . 'index.php?option=com_redshop&view=product&pid=' . $data->product_id;

		$replace[]   = "<a href='" . $producturl . "'>" . $product->product_name . "</a>";

		$maildata    = str_replace($search, $replace, $maildata);

		$mailsubject = str_replace($search, $replace, $mailsubject);

		if ($user_email != "")
		{
			JMail::getInstance()->sendMail($MailFrom, $FromName, $user_email, $mailsubject, $maildata, 1, null, $mailbcc);
		}

		return true;
	}

	public function imginmail($message)
	{
		$uri   = JFactory::getURI();

		$url   = $uri->root();

		$data1 = $data = $message;

		preg_match_all("/\< *[img][^\>]*[.]*\>/i", $data, $matches);

		foreach ($matches[0] as $match)
		{
			preg_match_all("/(src|height|width)*= *[\"\']{0,1}([^\"\'\ \>]*)/i", $match, $m);

			$images[]         = array_combine($m[1], $m[2]);

			$imagescur        = array_combine($m[1], $m[2]);

			$imagescurarray[] = $imagescur['src'];
		}

		$imagescurarray = @array_unique($imagescurarray);

		if ($imagescurarray)
		{
			foreach ($imagescurarray as $change)
			{
				if (strpos($change, 'http') === false)
				{
					$data1 = str_replace($change, $url . $change, $data1);
				}
			}
		}

		return $data1;
	}

	public function sendQuotationMail($quotation_id, $status = 0)
	{
		$uri             = JURI::getInstance();
		$url             = $uri->root();
		$redconfig       = new Redconfiguration;
		$producthelper   = new producthelper;
		$extra_field     = new extra_field;
		$quotationHelper = new quotationHelper;
		$config          = JFactory::getConfig();
		$mailinfo        = $this->getMailtemplate(0, "quotation_mail");
		$mailbcc         = array();

		if (count($mailinfo) > 0)
		{
			$message = $mailinfo[0]->mail_body;
			$subject = $mailinfo[0]->mail_subject;

			if (trim($mailinfo[0]->mail_bcc) != "")
			{
				$mailbcc = explode(",", $mailinfo[0]->mail_bcc);
			}
		}

		else
		{
			return false;
		}

		$template_start  = "";

		$template_end    = "";

		$template_middle = "";

		$cart            = '';

		$template_sdata = explode('{product_loop_start}', $message);

		$extraField = new extraField;
		$fieldArray = $extraField->getSectionFieldList(17, 0, 0);

		if (count($template_sdata) > 0)
		{
			$template_start = $template_sdata[0];

			if (count($template_sdata) > 1)
			{
				$template_edata = explode('{product_loop_end}', $template_sdata[1]);

				if (count($template_edata) > 1)
				{
					$template_end = $template_edata[1];
				}

				if (count($template_edata) > 0)
				{
					$template_middle = $template_edata[0];
				}
			}
		}

		$row = $quotationHelper->getQuotationDetail($quotation_id);

		if (count($row) <= 0)
		{
			return false;
		}

		$rowitem = $quotationHelper->getQuotationProduct($quotation_id);

		for ($i = 0; $i < count($rowitem); $i++)
		{
			$product_id                   = $rowitem[$i]->product_id;
			$product                      = $producthelper->getProductById($product_id);
			$product_name                 = "<div class='product_name'>" .
				$rowitem[$i]->product_name . "</div>";
			$product_total_price          = "<div class='product_price'>" .
				$producthelper->getProductFormattedPrice(($rowitem[$i]->product_price * $rowitem[$i]->product_quantity)) . "</div>";
			$product_price                = "<div class='product_price'>" .
				$producthelper->getProductFormattedPrice($rowitem[$i]->product_price) . "</div>";
			$product_price_excl_vat       = "<div class='product_price'>" .
				$producthelper->getProductFormattedPrice($rowitem[$i]->product_excl_price) . "</div>";
			$product_quantity             = '<div class="update_cart">' .
				$rowitem[$i]->product_quantity . '</div>';
			$product_total_price_excl_vat = "<div class='product_price'>" .
				$producthelper->getProductFormattedPrice(($rowitem[$i]->product_excl_price * $rowitem[$i]->product_quantity)) . "</div>";
			$cart_mdata                   = $template_middle;
			$wrapper_name                 = "";

			if ($rowitem[$i]->product_wrapperid)
			{
				$wrapper = $producthelper->getWrapper($product_id, $rowitem[$i]->product_wrapperid);

				if (count($wrapper) > 0)
				{
					$wrapper_name = $wrapper[0]->wrapper_name;
				}

				$wrapper_name = JText::_('COM_REDSHOP_WRAPPER') . ": " . $wrapper_name;
			}

			$product_image_path = '';

			if ($product->product_full_image)
			{
				if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $product->product_full_image))
				{
					$product_image_path = $product->product_full_image;
				}
				else
				{
					if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . PRODUCT_DEFAULT_IMAGE))
					{
						$product_image_path = PRODUCT_DEFAULT_IMAGE;
					}
				}
			}
			else
			{
				if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . PRODUCT_DEFAULT_IMAGE))
				{
					$product_image_path = PRODUCT_DEFAULT_IMAGE;
				}
			}

			if ($product_image_path)
			{
				$thumbUrl = RedShopHelperImages::getImagePath(
								$product_image_path,
								'',
								'thumb',
								'product',
								CART_THUMB_WIDTH,
								CART_THUMB_HEIGHT,
								USE_IMAGE_SIZE_SWAPPING
							);
				$product_image = "<div  class='product_image'><img src='" . $thumbUrl . "'></div>";
			}
			else
			{
				$product_image = "<div  class='product_image'></div>";
			}

			$cart_mdata = str_replace("{product_name}", $product_name, $cart_mdata);
			$cart_mdata = str_replace("{product_s_desc}", $product->product_s_desc, $cart_mdata);
			$cart_mdata = str_replace("{product_thumb_image}", $product_image, $cart_mdata);

			$product_note = "<div class='product_note'>" . $wrapper_name . "</div>";
			$cart_mdata = str_replace("{product_wrapper}", $product_note, $cart_mdata);
			$product_userfields = $quotationHelper->displayQuotationUserfield($rowitem[$i]->quotation_item_id, 12);

			$cart_mdata = str_replace("{product_userfields}", $product_userfields, $cart_mdata);
			$cart_mdata = str_replace("{product_number_lbl}", JText::_('COM_REDSHOP_PRODUCT_NUMBER'), $cart_mdata);
			$cart_mdata = str_replace("{product_number}", $product->product_number, $cart_mdata);
			$cart_mdata = str_replace(
				"{product_attribute}",
				$producthelper->makeAttributeQuotation(
					$rowitem[$i]->quotation_item_id,
					0,
					$rowitem[$i]->product_id,
					$row->quotation_status
				),
				$cart_mdata
			);
			$cart_mdata = str_replace(
				"{product_accessory}",
				$producthelper->makeAccessoryQuotation(
					$rowitem[$i]->quotation_item_id,
					$row->quotation_status
				),
				$cart_mdata
			);

			// ProductFinderDatepicker Extra Field Start
			$cart_mdata = $producthelper->getProductFinderDatepickerValue($cart_mdata, $product_id, $fieldArray);

			// ProductFinderDatepicker Extra Field End
			if ($row->quotation_status == 1 && !SHOW_QUOTATION_PRICE)
			{
				$cart_mdata = str_replace("{product_price_excl_vat}", "", $cart_mdata);
				$cart_mdata = str_replace("{product_price}", " ", $cart_mdata);
				$cart_mdata = str_replace("{product_total_price}", " ", $cart_mdata);
				$cart_mdata = str_replace("{product_subtotal_excl_vat}", " ", $cart_mdata);
			}
			else
			{
				$cart_mdata = str_replace("{product_price_excl_vat}", $product_price_excl_vat, $cart_mdata);
				$cart_mdata = str_replace("{product_price}", $product_price, $cart_mdata);
				$cart_mdata = str_replace("{product_total_price}", $product_total_price, $cart_mdata);
				$cart_mdata = str_replace("{product_subtotal_excl_vat}", $product_total_price_excl_vat, $cart_mdata);
			}

			$cart_mdata = str_replace("{product_quantity}", $product_quantity, $cart_mdata);
			$cart .= $cart_mdata;
		}

		$message = $template_start . $cart . $template_end;

		$search[]  = "{quotation_note}";
		$replace[] = $row->quotation_note;
		$search[]  = "{shopname}";
		$replace[] = SHOP_NAME;
		$search[]  = "{quotation_id}";
		$replace[] = $row->quotation_id;
		$search[]  = "{quotation_number}";
		$replace[] = $row->quotation_number;
		$search[]  = "{quotation_date}";
		$replace[] = $redconfig->convertDateFormat($row->quotation_cdate);
		$search[]  = "{quotation_status}";
		$replace[] = $quotationHelper->getQuotationStatusName($row->quotation_status);

		$billadd = '';

		if ($row->user_id != 0)
		{
			$message = $this->_carthelper->replaceBillingAddress($message, $row);
		}
		else
		{
			if ($row->quotation_email != "")
			{
				$billadd .= JText::_("COM_REDSHOP_EMAIL") . ' : ' . $row->quotation_email . '<br />';
			}

			$message = str_replace("{billing_address_information_lbl}", JText::_('COM_REDSHOP_BILLING_ADDRESS_INFORMATION_LBL'), $message);

			if (strstr($message, "{quotation_custom_field_list}"))
			{
				$billadd .= $extra_field->list_all_field_display(16, $row->user_info_id, 1, $row->quotation_email);
				$message = str_replace("{quotation_custom_field_list}", "", $message);
			}
			else
			{
				$message = $extra_field->list_all_field_display(16, $row->user_info_id, 1, $row->quotation_email, $message);
			}
		}

		$search[]     = "{billing_address}";
		$replace[]    = $billadd;
		$total_lbl    = '';
		$subtotal_lbl = '';
		$vat_lbl      = '';

		if ($row->quotation_status != 1 || ($row->quotation_status == 1 && SHOW_QUOTATION_PRICE))
		{
			$total_lbl    = JText::_('COM_REDSHOP_TOTAL_LBL');
			$subtotal_lbl = JText::_('COM_REDSHOP_QUOTATION_SUBTOTAL');
			$vat_lbl      = JText::_('COM_REDSHOP_QUOTATION_VAT');
		}

		$message = str_replace('{total_lbl}', $total_lbl, $message);
		$message = str_replace('{quotation_subtotal_lbl}', $subtotal_lbl, $message);
		$message = str_replace('{quotation_vat_lbl}', $vat_lbl, $message);
		$message = $this->_carthelper->replaceLabel($message);

		$search[] = "{quotation_note}";

		$replace[] = $row->quotation_note;

		if ($row->quotation_status == 1 && !SHOW_QUOTATION_PRICE)
		{
			$quotation_subtotal = " ";
			$quotation_total = " ";
			$quotation_discount = " ";
			$quotation_vat = " ";
			$quotation_subtotal_excl_vat = "";
		}
		else
		{
			$tax = $row->quotation_tax;

			if ((float) VAT_RATE_AFTER_DISCOUNT)
			{
				$Discountvat             = ((float) VAT_RATE_AFTER_DISCOUNT * $row->quotation_discount) / (1 + (float) VAT_RATE_AFTER_DISCOUNT);
				$row->quotation_discount = $row->quotation_discount - $Discountvat;
				$tax                     = $tax - $Discountvat;
			}

			if ((float) VAT_RATE_AFTER_DISCOUNT)
			{
				$sp_discount             = ($row->quotation_special_discount * ($row->quotation_subtotal + $row->quotation_tax)) / 100;
				$Discountspvat           = ($sp_discount * (float) VAT_RATE_AFTER_DISCOUNT) / (1 + (float) VAT_RATE_AFTER_DISCOUNT);
				$DiscountspWithotVat     = $sp_discount - $Discountspvat;
				$row->quotation_discount = $row->quotation_discount + $DiscountspWithotVat;
				$tax                     = $tax - $Discountspvat;
			}

			$quotation_subtotal_excl_vat       = $producthelper->getProductFormattedPrice($row->quotation_subtotal);
			$quotation_subtotal_minus_discount = $producthelper->getProductFormattedPrice($row->quotation_subtotal - $row->quotation_discount);
			$quotation_subtotal                = $producthelper->getProductFormattedPrice($row->quotation_subtotal);
			$quotation_total                   = $producthelper->getProductFormattedPrice($row->quotation_total);
			$quotation_discount                = $producthelper->getProductFormattedPrice($row->quotation_discount);
			$quotation_vat                     = $producthelper->getProductFormattedPrice($row->quotation_tax);
		}

		$search[]  = "{quotation_subtotal}";
		$replace[] = $quotation_subtotal;
		$search[]  = "{quotation_total}";
		$replace[] = $quotation_total;
		$search[]  = "{quotation_subtotal_minus_discount}";
		$replace[] = $quotation_subtotal_minus_discount;
		$search[]  = "{quotation_subtotal_excl_vat}";
		$replace[] = $quotation_subtotal_excl_vat;
		$search[]  = "{quotation_discount}";
		$replace[] = $quotation_discount;
		$search[]  = "{quotation_vat}";
		$replace[] = $quotation_vat;

		$quotationdetailurl = JURI::root() . 'index.php?option=com_redshop&view=quotation_detail&quoid=' . $quotation_id . '&encr='
			. $row->quotation_encrkey;

		$search[] = "{quotation_detail_link}";

		$replace[] = "<a href='" . $quotationdetailurl . "'>" . JText::_("COM_REDSHOP_QUOTATION_DETAILS") . "</a>";

		$message = str_replace($search, $replace, $message);

		$message = $this->imginmail($message);

		$email = $row->quotation_email;

		// Set the e-mail parameters
		$from = $config->get('mailfrom');
		$fromname = $config->get('fromname');

		$body = $message;

		$subject = str_replace($search, $replace, $subject);

		// Send the e-mail

		if ($email != "")
		{
			$bcc = array();

			if (trim(ADMINISTRATOR_EMAIL) != '')
			{
				$bcc = explode(",", trim(ADMINISTRATOR_EMAIL));
			}

			$bcc = array_merge($bcc, $mailbcc);

			if (!JMail::getInstance()->sendMail($from, $fromname, $email, $subject, $body, 1, null, $bcc))
			{
				$this->setError('ERROR_SENDING_QUOTATION_MAIL');
			}
		}

		if ($status != 0)
		{
			$quotationHelper->updateQuotationStatus($quotation_id, $status);
		}

		return true;
	}

	public function sendNewsletterConfirmationMail($subscription_id)
	{
		if (NEWSLETTER_CONFIRMATION)
		{
			$config   = JFactory::getConfig();
			$url      = JURI::root();
			$option   = JRequest::getVar('option', '', 'request');
			$mailbcc  = null;
			$mailinfo = $this->getMailtemplate(0, "newsletter_confirmation");

			if (count($mailinfo) > 0)
			{
				$message = $mailinfo[0]->mail_body;
				$subject = $mailinfo[0]->mail_subject;

				if (trim($mailinfo[0]->mail_bcc) != "")
				{
					$mailbcc = explode(",", $mailinfo[0]->mail_bcc);
				}
			}

			else
			{
				return false;
			}

			$query = "SELECT * FROM " . $this->_table_prefix . "newsletter_subscription " .
				"WHERE subscription_id = " . (int) $subscription_id;

			$this->_db->setQuery($query);

			$list      = $this->_db->loadObject();

			$link      = '<a href="' . $url . 'index.php?option=com_redshop&view=newsletter&sid=' . $subscription_id . '">' .
				JText::_('COM_REDSHOP_CLICK_HERE') . '</a>';

			$search[]  = "{shopname}";

			$replace[] = SHOP_NAME;

			$search[]  = "{link}";

			$replace[] = $link;

			$search[]  = "{name}";

			$replace[] = $list->name;

			$email     = $list->email;

			$subject   = str_replace($search, $replace, $subject);

			$message   = str_replace($search, $replace, $message);

			$from      = $config->get('mailfrom');

			$fromname  = $config->get('fromname');

			// Send the e-mail
			if ($email != "")
			{
				if (!JMail::getInstance()->sendMail($from, $fromname, $email, $subject, $message, 1, null, $mailbcc))
				{
					$this->setError(JText::_('COM_REDSHOP_ERROR_SENDING_CONFIRMATION_MAIL'));
				}
			}
		}

		return true;
	}

	public function sendNewsletterCancellationMail($email = "")
	{
		$config = JFactory::getConfig();
		$mailinfo = $this->getMailtemplate(0, "newsletter_cancellation");
		$mailbcc = null;

		if (count($mailinfo) > 0)
		{
			$message = $mailinfo[0]->mail_body;
			$subject = $mailinfo[0]->mail_subject;

			if (trim($mailinfo[0]->mail_bcc) != "")
			{
				$mailbcc = explode(",", $mailinfo[0]->mail_bcc);
			}
		}
		else
		{
			return false;
		}

		$search[]  = "{shopname}";

		$replace[] = SHOP_NAME;

		$subject   = str_replace($search, $replace, $subject);

		$message   = str_replace($search, $replace, $message);

		$from      = $config->get('mailfrom');

		$fromname  = $config->get('fromname');

		// Send the e-mail
		if ($email != "")
		{
			JMail::getInstance()->sendMail($from, $fromname, $email, $subject, $message, 1, null, $mailbcc);
		}

		return true;
	}

	public function sendAskQuestionMail($ansid)
	{
		$producthelper = new producthelper;
		$uri           = JURI::getInstance();
		$url           = $uri->root();
		$option        = JRequest::getVar('option');
		$subject       = "";
		$data_add      = "";
		$mailbcc       = null;

		$mailinfo = $this->getMailtemplate(0, "ask_question_mail");
		$ans      = $producthelper->getQuestionAnswer($ansid);

		if (count($mailinfo) > 0)
		{
			$data_add = $mailinfo[0]->mail_body;
			$subject  = $mailinfo[0]->mail_subject;
			$mailbcc  = explode(",", $mailinfo[0]->mail_bcc);
		}

		if (count($ans) > 0)
		{
			$ans        = $ans[0];
			$fromname   = $ans->user_name;
			$from       = $ans->user_email;
			$email      = explode(",", trim(ADMINISTRATOR_EMAIL));
			$question   = $ans->question;
			$answer     = "";
			$telephone  = "";
			$address    = "";
			$product_id = $ans->product_id;

			if ($ans->parent_id)
			{
				$answer  = $ans->question;
				$qdetail = $producthelper->getQuestionAnswer($ans->parent_id);

				if (count($qdetail) > 0)
				{
					$config     = JFactory::getConfig();
					$from       = $config->get('mailfrom');
					$fromname   = $config->get('fromname');

					$qdetail    = $qdetail[0];
					$question   = $qdetail->question;
					$email      = $qdetail->user_email;
					$product_id = $qdetail->product_id;
					$address    = $qdetail->address;
					$telephone  = $qdetail->telephone;
				}
			}

			$product     = $producthelper->getProductById($product_id);

			$link        = JRoute::_($url . "index.php?option=com_redshop&view=product&pid=" . $product_id);

			$data_add    = str_replace("{product_name}", $product->product_name, $data_add);
			$data_add    = str_replace("{product_desc}", $product->product_desc, $data_add);
			$product_url = "<a href=" . $link . ">" . $product->product_name . "</a>";
			$data_add    = str_replace("{product_link}", $product_url, $data_add);
			$data_add    = str_replace("{user_question}", $question, $data_add);
			$data_add    = str_replace("{answer}", $answer, $data_add);
			$data_add    = str_replace("{user_address}", $address, $data_add);
			$data_add    = str_replace("{user_telephone}", $telephone, $data_add);
			$subject     = str_replace("{user_question}", $question, $subject);
			$subject     = str_replace("{shopname}", SHOP_NAME, $subject);
			$subject     = str_replace("{product_name}", $product->product_name, $subject);

			if ($email)
			{
				if (JMail::getInstance()->sendMail($from, $fromname, $email, $subject, $data_add, $mode = 1, null, $mailbcc))
				{
					return true;
				}
			}
		}

		return false;
	}

	public function sendEconomicBookInvoiceMail($order_id = 0, $bookinvoicepdf = "")
	{
		if ($order_id == 0)
		{
			return false;
		}

		$redconfig = new Redconfiguration;

		$config    = JFactory::getConfig();
		$from      = $config->get('mailfrom');
		$fromname  = $config->get('fromname');

		$mailinfo  = $this->getMailtemplate(0, "economic_inoice");
		$data_add  = "economic inoice";
		$subject   = "economic_inoice";
		$mailbcc   = null;

		if (count($mailinfo) > 0)
		{
			$data_add = $mailinfo[0]->mail_body;
			$subject = $mailinfo[0]->mail_subject;

			if (trim($mailinfo[0]->mail_bcc) != "")
			{
				$mailbcc = explode(",", $mailinfo[0]->mail_bcc);
			}
		}

		$orderdetail = $this->_order_functions->getOrderDetails($order_id);
		$user_billinginfo = $this->_order_functions->getOrderBillingUserInfo($order_id);

		$search[] = "{name}";
		$search[] = "{order_number}";
		$search[] = "{order_comment}";
		$search[] = "{order_id}";
		$search[] = "{order_date}";

		if ($user_billinginfo->is_company == 1 && $user_billinginfo->company_name != '')
		{
			$replace[] = $user_billinginfo->company_name;
		}
		else
		{
			$replace[] = $user_billinginfo->firstname . " " . $user_billinginfo->lastname;
		}

		$replace[] = $orderdetail->order_number;
		$replace[] = $orderdetail->customer_note;
		$replace[] = $orderdetail->order_id;
		$replace[] = $redconfig->convertDateFormat($orderdetail->cdate);

		$data_add = str_replace($search, $replace, $data_add);

		$attachment[] = $bookinvoicepdf;

		if ($user_billinginfo->user_email != "")
		{
			JMail::getInstance()->sendMail($from, $fromname, $user_billinginfo->user_email, $subject, $data_add, 1, null, $mailbcc, $attachment);
		}

		if (ADMINISTRATOR_EMAIL != '')
		{
			$sendto = explode(",", trim(ADMINISTRATOR_EMAIL));
			JMail::getInstance()->sendMail($from, $fromname, $sendto, $subject, $data_add, 1, null, $mailbcc, $attachment);
		}

		return true;
	}

	public function sendRequestTaxExemptMail($data, $username = "")
	{
		if (ADMINISTRATOR_EMAIL != '')
		{
			$mailinfo = $this->getMailtemplate(0, "request_tax_exempt_mail");
			$data_add = "";
			$subject = "";
			$mailbcc = null;

			if (count($mailinfo) > 0)
			{
				$data_add = $mailinfo[0]->mail_body;
				$subject = $mailinfo[0]->mail_subject;

				if (trim($mailinfo[0]->mail_bcc) != "")
				{
					$mailbcc = explode(",", $mailinfo[0]->mail_bcc);
				}
			}

			$config = JFactory::getConfig();
			$from = $config->get('mailfrom');
			$fromname = $config->get('fromname');

			$state_name = $this->_order_functions->getStateName($data->state_code);
			$country_name = $this->_order_functions->getCountryName($data->country_code);

			$data_add = str_replace("{vat_number}", $data->vat_number, $data_add);
			$data_add = str_replace("{username}", $username, $data_add);
			$data_add = str_replace("{company_name}", $data->company_name, $data_add);
			$data_add = str_replace("{country}", $country_name, $data_add);
			$data_add = str_replace("{state}", $state_name, $data_add);
			$data_add = str_replace("{phone}", $data->phone, $data_add);
			$data_add = str_replace("{zipcode}", $data->zipcode, $data_add);
			$data_add = str_replace("{address}", $data->address, $data_add);
			$data_add = str_replace("{city}", $data->city, $data_add);

			$sendto = explode(",", trim(ADMINISTRATOR_EMAIL));
			JMail::getInstance()->sendMail($from, $fromname, $sendto, $subject, $data_add, 1, null, $mailbcc);
		}
	}

	public function sendCatalogRequest($catalog = array())
	{
		$maildata = $this->getMailtemplate(0, "catalog");
		$data_add = "";
		$subject = "";
		$mailbcc = null;

		if (count($mailinfo) > 0)
		{
			$data_add = $mailinfo[0]->mail_body;
			$subject = $mailinfo[0]->mail_subject;

			if (trim($mailinfo[0]->mail_bcc) != "")
			{
				$mailbcc = explode(",", $mailinfo[0]->mail_bcc);
			}
		}

		$config = JFactory::getConfig();
		$from = $config->get('mailfrom');
		$fromname = $config->get('fromname');

		$query = "SELECT * FROM  " . $this->_table_prefix . "media "
			. "WHERE media_section='catalog' "
			. "AND media_type='document' "
			. "AND section_id = " . (int) $catalog->catalog_id . " "
			. "AND published = 1 ";

		$this->_db->setQuery($query);
		$catalog_data = $this->_db->loadObjectlist();
		$attachment = array();

		for ($p = 0; $p < count($catalog_data); $p++)
		{
			$attachment[] = REDSHOP_FRONT_DOCUMENT_RELPATH . 'catalog/' . $catalog_data[$p]->media_name;
		}

		$data_add = str_replace("{name}", $catalog->name, $data_add);

		if (JMail::getInstance()->sendMail($from, $fromname, $catalog->email, $subject, $data_add, 1, null, $mailbcc, $attachment))
		{
			return true;
		}

		else
		{
			return false;
		}
	}
}
