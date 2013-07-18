<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

/**
 * redSHOP template manager
 *
 * @package  RedSHOP
 * @since    2.5
 */
class Redtemplate
{
	public $redshop_template_path;

	/**
	 * load initial files
	 */
	public function __construct()
	{
		$this->redshop_template_path = JPATH_SITE . "/components/com_redshop/templates";

		if (!is_dir($this->redshop_template_path))
		{
			chmod(JPATH_SITE . "/components/com_redshop", 0755);

			JFolder::create($this->redshop_template_path, 0755);
		}
	}

	/**
	 * Method to get Template
	 *
	 * @param   string   $section  Set section Template
	 * @param   integer  $tid      Template Id
	 * @param   string   $name     Template Name
	 *
	 * @return  object             Template Array
	 */
	public function getTemplate($section = '', $tid = 0, $name = "")
	{
		$db = JFactory::getDBO();
		$and = "";

		if ($tid != 0)
		{
			$and = "AND template_id IN (" . $tid . ") ";
		}

		$and .= ($name != "") ? " AND template_name = '" . $name . "'" : "";

		$query = "SELECT * "
			. "FROM #__redshop_template "
			. "WHERE template_section "
			. "LIKE '" . $section . "' AND published=1 "
			. $and
			. "ORDER BY template_id ASC ";
		$db->setQuery($query);
		$re = $db->loadObjectList();

		for ($i = 0; $i < count($re); $i++)
		{
			$re[$i]->template_desc = $this->readtemplateFile($re[$i]->template_section, $re[$i]->template_name);
		}

		return $re;
	}

	/**
	 * Method to read Template from file
	 *
	 * @param   string   $section   Template Section
	 * @param   string   $filename  Template File Name
	 * @param   boolean  $is_admin  Check for administrator call
	 *
	 * @return  string              Template Content
	 */
	public function readtemplateFile($section, $filename, $is_admin = false)
	{
		$file_path = $this->getTemplatefilepath($section, $filename, $is_admin);

		if (file_exists($file_path))
		{
			$content = implode("", file($file_path));

			return $content;
		}

		return "";
	}

	/**
	 * Method to get Template file path
	 *
	 * @param   string   $section   Template Section
	 * @param   string   $filename  Template File Name
	 * @param   boolean  $is_admin  Check for administrator call
	 *
	 * @return  string              Template File Path
	 */
	public function getTemplatefilepath($section, $filename, $is_admin = false)
	{
		$app = JFactory::getApplication();
		$tempate_file = "";
		$template_view = $this->getTemplateView($section);
		$layout = JRequest::getVar('layout');

		if (!$is_admin && $section != 'categoryproduct')
		{
			$tempate_file = JPATH_SITE . '/templates/' . $app->getTemplate() . "/html/com_redshop/$template_view/$section/$filename.php";
		}
		else
		{
			$tempate_file = JPATH_SITE . '/templates/' . $app->getTemplate() . "/html/com_redshop/$section/$filename.php";
		}

		if (!file_exists($tempate_file))
		{
			if ($section == 'categoryproduct' && $layout == 'categoryproduct')
			{
				$templateDir = JPATH_SITE . "/components/com_redshop/templates/$section/$filename.php";
			}

			if ($template_view && $section != 'categoryproduct')
			{
				$templateDir = JPATH_SITE . "/components/com_redshop/views/$template_view/tmpl/$section";
				@chmod(JPATH_SITE . "/components/com_redshop/views/$template_view/tmpl", 0755);
			}

			else
			{
				$templateDir = $this->redshop_template_path . '/' . $section;

				@chmod($this->redshop_template_path, 0755);
			}

			if (!is_dir($templateDir))
			{
				JFolder::create($templateDir, 0755);
			}

			$tempate_file = "$templateDir/$filename.php";
		}

		return $tempate_file;
	}

	/**
	 * Template View selector
	 *
	 * @param   string  $section  Template Section
	 *
	 * @return  string            Template Joomla view name
	 */
	public function getTemplateView($section)
	{
		$section = strtolower($section);
		$view = "";

		switch ($section)
		{
			case 'product':
			case 'related_product':
			case 'product_sample':
			case 'accessory_template':
			case 'attribute_template':
			case 'attributewithcart_template':
			case 'review':
			case 'wrapper_template':
			case 'compare_product':
				$view = "product";
				break;
			case 'categoryproduct':
			case 'category':
			case 'frontpage_category':
				$view = "category";
				break;
			case 'catalog':
			case 'catalog_sample':
				$view = "catalog";
				break;
			case 'manufacturer':
			case 'manufacturer_detail':
			case 'manufacturer_products':

				$view = "manufacturers";
				break;
			case 'cart':
			case 'add_to_cart':
			case 'ajax_cart_detail_box':
			case 'ajax_cart_box':
			case 'empty_cart':
				$view = "cart";
				break;

			case 'account_template':
				$view = "account";
				break;

			case 'private_billing_template':
			case 'company_billing_template':
			case 'billing_template':
			case 'shipping_template':
				$view = "registration";
				break;

			case 'wishlist_template':
			case 'wishlist_mail_template':
				$view = "wishlist";
				break;
			case 'newsletter':
			case 'newsletter_product':
				$view = "newsletter";
				break;
			case 'order_list':
			case 'order_detail':
			case 'order_receipt':
				$view = "orders";
				break;
			case 'giftcard':
				$view = "giftcard";
				break;
			case 'checkout':
			case 'onestep_checkout':
				$view = "checkout";
				break;
			case 'ask_question_template':
				$view = "ask_question";
				break;
			default:
				return false;
		}

		return $view;
	}

	/**
	 * Method to parse joomla content plugin onContentPrepare event
	 *
	 * @param   string  $string  Joomla content
	 *
	 * @return  string           Modified content
	 */
	public function parseredSHOPplugin($string = "")
	{
		global $context;

		$o = new stdClass;
		$o->text = $string;
		JPluginHelper::importPlugin('content');

		$dispatcher = JDispatcher::getInstance();

		$x = array();

		$results = $dispatcher->trigger('onContentPrepare', array($context, &$o, &$x, 0));

		return $o->text;
	}

	/**
	 * Collect Template Sections for installation
	 *
	 * @param   string   $template_name  Template Name
	 * @param   boolean  $setflag        Set true if you want html special character in template content
	 *
	 * @return  string                   redSHOP Template Contents
	 */
	public function getInstallSectionTemplate($template_name, $setflag = false)
	{
		$tempate_file = JPATH_SITE . "/components/com_redshop/templates/rsdefaulttemplates/$template_name.php";

		if (file_exists($tempate_file))
		{
			$handle = fopen($tempate_file, "r");
			$contents = fread($handle, filesize($tempate_file));
			fclose($handle);

			if ($setflag)
			{
				return "<pre/>" . htmlspecialchars($contents) . "</pre>";
			}
			else
			{
				return $contents;
			}
		}
	}

	/**
	 * Collect list of redSHOP Template
	 *
	 * @param   string  $sectionvalue  Template Section selected value
	 *
	 * @return  array                 Template Section List options
	 */
	public function getTemplateSections($sectionvalue = "")
	{
		$optionsection = array();
		$optionsection[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT'));
		$optionsection[] = JHTML::_('select.option', 'product', JText::_('COM_REDSHOP_PRODUCT'));
		$optionsection[] = JHTML::_('select.option', 'related_product', JText::_('COM_REDSHOP_RELATED_PRODUCT'));
		$optionsection[] = JHTML::_('select.option', 'category', JText::_('COM_REDSHOP_Category'));
		$optionsection[] = JHTML::_('select.option', 'manufacturer', JText::_('COM_REDSHOP_Manufacturer'));
		$optionsection[] = JHTML::_('select.option', 'manufacturer_detail', JText::_('COM_REDSHOP_MANUFACTURER_DETAIL'));
		$optionsection[] = JHTML::_('select.option', 'manufacturer_products', JText::_('COM_REDSHOP_MANUFACTURER_PRODUCTS'));
		$optionsection[] = JHTML::_('select.option', 'newsletter', JText::_('COM_REDSHOP_Newsletter'));
		$optionsection[] = JHTML::_('select.option', 'newsletter_product', JText::_('COM_REDSHOP_NEWSLETTER_PRODUCTS'));
		$optionsection[] = JHTML::_('select.option', 'empty_cart', JText::_('COM_REDSHOP_EMPTY_CART'));
		$optionsection[] = JHTML::_('select.option', 'cart', JText::_('COM_REDSHOP_Cart'));
		$optionsection[] = JHTML::_('select.option', 'add_to_cart', JText::_('COM_REDSHOP_ADD_TO_CART'));
		$optionsection[] = JHTML::_('select.option', 'catalog', JText::_('COM_REDSHOP_CATALOG'));
		$optionsection[] = JHTML::_('select.option', 'product_sample', JText::_('COM_REDSHOP_PRODUCT_SAMPLE'));
		$optionsection[] = JHTML::_('select.option', 'order_list', JText::_('COM_REDSHOP_ORDER_LIST'));
		$optionsection[] = JHTML::_('select.option', 'order_detail', JText::_('COM_REDSHOP_ORDER_DETAIL'));
		$optionsection[] = JHTML::_('select.option', 'order_receipt', JText::_('COM_REDSHOP_ORDER_RECEIPT'));
		$optionsection[] = JHTML::_('select.option', 'review', JText::_('COM_REDSHOP_Review'));
		$optionsection[] = JHTML::_('select.option', 'frontpage_category', JText::_('COM_REDSHOP_FRONTPAGE_CATEGORY'));
		$optionsection[] = JHTML::_('select.option', 'attribute_template', JText::_('COM_REDSHOP_ATTRIBUTE_TEMPLATE'));
		$optionsection[] = JHTML::_('select.option', 'attributewithcart_template', JText::_('COM_REDSHOP_ATTRIBUTE_WITH_CART_TEMPLATE'));
		$optionsection[] = JHTML::_('select.option', 'accessory_template', JText::_('COM_REDSHOP_ACCESSORY_TEMPLATE'));
		$optionsection[] = JHTML::_('select.option', 'account_template', JText::_('COM_REDSHOP_ACCOUNT_TEMPLATE'));
		$optionsection[] = JHTML::_('select.option', 'wishlist_template', JText::_('COM_REDSHOP_WISHLIST_TEMPLATE'));
		$optionsection[] = JHTML::_('select.option', 'wishlist_mail_template', JText::_('COM_REDSHOP_WISHLIST_MAIL_TEMPLATE'));
		$optionsection[] = JHTML::_('select.option', 'wrapper_template', JText::_('COM_REDSHOP_WRAPPER_TEMPLATE'));
		$optionsection[] = JHTML::_('select.option', 'ajax_cart_detail_box', JText::_('COM_REDSHOP_AJAX_CART_DETAIL_BOX'));
		$optionsection[] = JHTML::_('select.option', 'ajax_cart_box', JText::_('COM_REDSHOP_AJAX_CART_BOX_TMP'));
		$optionsection[] = JHTML::_('select.option', 'ask_question_template', JText::_('COM_REDSHOP_ASK_QUESTION_TEMPLATE'));
		$optionsection[] = JHTML::_('select.option', 'giftcard_list', JText::_('COM_REDSHOP_GIFTCARD_LIST_TEMPLATE'));
		$optionsection[] = JHTML::_('select.option', 'giftcard', JText::_('COM_REDSHOP_GIFTCARD_TEMPLATE'));
		$optionsection[] = JHTML::_('select.option', 'shipping_pdf', JText::_('COM_REDSHOP_SHIPPING_PDF_TEMPLATE'));
		$optionsection[] = JHTML::_('select.option', 'clicktell_sms_message', JText::_('COM_REDSHOP_CLICKTELL_SMS_MESSAGE'));
		$optionsection[] = JHTML::_('select.option', 'order_print', JText::_('COM_REDSHOP_ORDER_PRINT_TEMPLATE'));
		$optionsection[] = JHTML::_('select.option', 'redproductfinder', JText::_('COM_REDSHOP_redPRODUCTFINDER'));
		$optionsection[] = JHTML::_('select.option', 'quotation_detail', JText::_('COM_REDSHOP_QUOTATION_DETAIL_TEMPLATE'));
		$optionsection[] = JHTML::_('select.option', 'quotation_cart', JText::_('COM_REDSHOP_QUOTATION_CART'));
		$optionsection[] = JHTML::_('select.option', 'quotation_request', JText::_('COM_REDSHOP_QUOTATION_REQUEST_TEMPLATE'));
		$optionsection[] = JHTML::_('select.option', 'catalogue_cart', JText::_('COM_REDSHOP_CATALOGUE_CART_TEMPLATE'));
		$optionsection[] = JHTML::_('select.option', 'catalogue_order_detail', JText::_('COM_REDSHOP_CATALOGUE_ORDER_DETAIL_TEMPLATE'));
		$optionsection[] = JHTML::_('select.option', 'catalogue_order_receipt', JText::_('COM_REDSHOP_CATALOGUE_ORDER_RECEIPT_TEMPLATE'));
		$optionsection[] = JHTML::_('select.option', 'compare_product', JText::_('COM_REDSHOP_COMPARE_PRODUCT_TEMPLATE'));
		$optionsection[] = JHTML::_('select.option', 'clickatell', JText::_('COM_REDSHOP_CLICKATELL'));
		$optionsection[] = JHTML::_('select.option', 'redshop_payment', JText::_('COM_REDSHOP_PAYMENT_METHOD_TEMPLATE'));
		$optionsection[] = JHTML::_('select.option', 'redshop_shipping', JText::_('COM_REDSHOP_SHIPPING_METHOD_TEMPLATE'));
		$optionsection[] = JHTML::_('select.option', 'shippingbox', JText::_('COM_REDSHOP_SHIPPING_BOX_TEMPLATE'));
		$optionsection[] = JHTML::_('select.option', 'onestep_checkout', JText::_('COM_REDSHOP_ONESTEP_CHECKOUT_TEMPLATE'));
		$optionsection[] = JHTML::_('select.option', 'categoryproduct', JText::_('COM_REDSHOP_PRODUCT_CATEGORY_TEMPLATE'));
		$optionsection[] = JHTML::_('select.option', 'change_cart_attribute', JText::_('COM_REDSHOP_CHANGE_CART_ATTRIBUTE_TEMPLATE'));
		$optionsection[] = JHTML::_('select.option', 'searchletter', JText::_('COM_REDSHOP_LETTER_SEARCH_TEMPLATE'));
		$optionsection[] = JHTML::_('select.option', 'crmorder_receipt', JText::_('COM_REDSHOP_redCRM_ORDER_RECIEPT'));
		$optionsection[] = JHTML::_('select.option', 'checkout', JText::_('COM_REDSHOP_CHECKOUT_TEMPLATE'));
		$optionsection[] = JHTML::_('select.option', 'product_content_template', JText::_('COM_REDSHOP_PRODUCT_CONTENT'));
		$optionsection[] = JHTML::_('select.option', 'billing_template', JText::_('COM_REDSHOP_BILLING_TEMPLATE'));
		$optionsection[] = JHTML::_('select.option', 'private_billing_template', JText::_('COM_REDSHOP_PRIVATE_BILLING_TEMPLATE'));
		$optionsection[] = JHTML::_('select.option', 'company_billing_template', JText::_('COM_REDSHOP_COMPANY_BILLING_TEMPLATE'));
		$optionsection[] = JHTML::_('select.option', 'shipping_template', JText::_('COM_REDSHOP_SHIPPING_TEMPLATE'));
		$optionsection[] = JHTML::_('select.option', 'shippment_invoice_template', JText::_('COM_REDSHOP_SHIPPMENT_INVOICE_TEMPLATE'));
		$optionsection[] = JHTML::_('select.option', 'stock_note', JText::_('COM_REDSHOP_STOCK_NOTE_TEMPLATE'));

		// Sort array alphabetically
		sort($optionsection);

		if ($sectionvalue != "")
		{
			$sectionname = "";

			for ($i = 0; $i < count($optionsection); $i++)
			{
				if ($optionsection[$i]->value == $sectionvalue)
				{
					$sectionname = $optionsection[$i]->text;
					break;
				}
			}

			return $sectionname;
		}
		else
		{
			return $optionsection;
		}
	}

	/**
	 * Collect Mail Template Section Select Option Value
	 *
	 * @param   string  $sectionvalue  Selected Section Name
	 *
	 * @return  array                 Mail Template Select list options
	 */
	public function getMailSections($sectionvalue = "")
	{
		$optiontype = array();
		$optiontype[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT'));
		$optiontype[] = JHTML::_('select.option', 'order', JText::_('COM_REDSHOP_ORDER_MAIL'));
		$optiontype[] = JHTML::_('select.option', 'catalogue_order', JText::_('COM_REDSHOP_CATALOGUE_ORDER_MAIL'));
		$optiontype[] = JHTML::_('select.option', 'order_special_discount', JText::_('COM_REDSHOP_ORDER_SPECIAL_DISCOUNT_MAIL'));
		$optiontype[] = JHTML::_('select.option', 'order_status', JText::_('COM_REDSHOP_ORDER_STATUS_CHANGE'));
		$optiontype[] = JHTML::_('select.option', 'register', JText::_('COM_REDSHOP_REGISTRATION_MAIL'));
		$optiontype[] = JHTML::_('select.option', 'product', JText::_('COM_REDSHOP_PRODUCT_INFORMATION'));
		$optiontype[] = JHTML::_('select.option', 'status_of_password_reset', JText::_('COM_REDSHOP_STSTUS_OF_PASSWORD_RESET'));
		$optiontype[] = JHTML::_('select.option', 'tax_exempt_approval_mail', JText::_('COM_REDSHOP_TAX_EXEMPT_APPROVAL_MAIL'));
		$optiontype[] = JHTML::_('select.option', 'tax_exempt_disapproval_mail', JText::_('COM_REDSHOP_TAX_EXEMPT_DISAPPROVAL_MAIL'));
		$optiontype[] = JHTML::_('select.option', 'tax_exempt_waiting_approval_mail', JText::_('COM_REDSHOP_TAX_EXEMPT_WAITING_APPROVAL_MAIL'));
		$optiontype[] = JHTML::_('select.option', 'catalog', JText::_('COM_REDSHOP_CATALOG_SEND_MAIL'));
		$optiontype[] = JHTML::_('select.option', 'catalog_first_reminder', JText::_('COM_REDSHOP_CATALOG_FIRST_REMINDER'));
		$optiontype[] = JHTML::_('select.option', 'catalog_second_reminder', JText::_('COM_REDSHOP_CATALOG_SECOND_REMINDER'));
		$optiontype[] = JHTML::_('select.option', 'catalog_coupon_reminder', JText::_('COM_REDSHOP_CATALOG_COUPON_REMINDER'));
		$optiontype[] = JHTML::_('select.option', 'colour_sample_first_reminder', JText::_('COM_REDSHOP_CATALOG_SAMPLE_FIRST_REMINDER'));
		$optiontype[] = JHTML::_('select.option', 'colour_sample_second_reminder', JText::_('COM_REDSHOP_CATALOG_SAMPLE_SECOND_REMINDER'));
		$optiontype[] = JHTML::_('select.option', 'colour_sample_third_reminder', JText::_('COM_REDSHOP_CATALOG_SAMPLE_THIRD_REMINDER'));
		$optiontype[] = JHTML::_('select.option', 'colour_sample_coupon_reminder', JText::_('COM_REDSHOP_CATALOG_SAMPLE_COUPON_REMINDER'));
		$optiontype[] = JHTML::_('select.option', 'first_mail_after_order_purchased', JText::_('COM_REDSHOP_FIRST_MAIL_AFTER_ORDER_PURCHASED'));
		$optiontype[] = JHTML::_('select.option', 'second_mail_after_order_purchased', JText::_('COM_REDSHOP_SECOND_MAIL_AFTER_ORDER_PURCHASED'));
		$optiontype[] = JHTML::_('select.option', 'third_mail_after_order_purchased', JText::_('COM_REDSHOP_THIRD_MAIL_AFTER_ORDER_PURCHASED'));
		$optiontype[] = JHTML::_('select.option', 'economic_inoice', JText::_('COM_REDSHOP_ECONOMIC_INVOICE'));
		$optiontype[] = JHTML::_('select.option', 'newsletter_confirmation', JText::_('COM_REDSHOP_NEWSLETTER_CONFIRMTION'));
		$optiontype[] = JHTML::_('select.option', 'newsletter_cancellation', JText::_('COM_REDSHOP_NEWSLETTER_CANCELLATION'));
		$optiontype[] = JHTML::_('select.option', 'mywishlist_mail', JText::_('COM_REDSHOP_WISHLIST_MAIL'));
		$optiontype[] = JHTML::_('select.option', 'ask_question_mail', JText::_('COM_REDSHOP_ASK_QUESTION_MAIL'));
		$optiontype[] = JHTML::_('select.option', 'downloadable_product_mail', JText::_('COM_REDSHOP_DOWNLOADABLE_PRODUCT_MAIL'));
		$optiontype[] = JHTML::_('select.option', 'giftcard_mail', JText::_('COM_REDSHOP_GIFTCARD_MAIL'));
		$optiontype[] = JHTML::_('select.option', 'invoice_mail', JText::_('COM_REDSHOP_INVOICE_MAIL'));
		$optiontype[] = JHTML::_('select.option', 'quotation_mail', JText::_('COM_REDSHOP_QUOTATION_MAIL'));
		$optiontype[] = JHTML::_('select.option', 'quotation_user_register', JText::_('COM_REDSHOP_QUOTATION_USER_REGISTER_MAIL'));
		$optiontype[] = JHTML::_('select.option', 'request_tax_exempt_mail', JText::_('COM_REDSHOP_REQUEST_TAX_EXEMPT_MAIL'));
		$optiontype[] = JHTML::_('select.option', 'subscription_renewal_mail', JText::_('COM_REDSHOP_SUBSCRIPTION_RENEWAL_MAIL'));
		$optiontype[] = JHTML::_('select.option', 'review_mail', JText::_('COM_REDSHOP_REVIEW_MAIL'));
		$optiontype[] = JHTML::_('select.option', 'notify_stock_mail', JText::_('COM_REDSHOP_NOTIFY_STOCK'));

		// Sort array alphabetically
		sort($optiontype);

		if ($sectionvalue != "")
		{
			$sectionname = "";

			for ($i = 0; $i < count($optiontype); $i++)
			{
				if ($optiontype[$i]->value == $sectionvalue)
				{
					$sectionname = $optiontype[$i]->text;
					break;
				}
			}

			return $sectionname;
		}
		else
		{
			return $optiontype;
		}
	}

	/**
	 * Collect redSHOP costume field section select list option
	 *
	 * @param   string  $sectionvalue  Selected option Value
	 *
	 * @return  array                 Costume field Select list options
	 */
	public function getFieldSections($sectionvalue = "")
	{
		$optionsection   = array();
		$optionsection[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT'));
		$optionsection[] = JHTML::_('select.option', '1', JText::_('COM_REDSHOP_PRODUCT'));
		$optionsection[] = JHTML::_('select.option', '2', JText::_('COM_REDSHOP_CATEGORY'));
		$optionsection[] = JHTML::_('select.option', '7', JText::_('COM_REDSHOP_CUSTOMER_ADDRESS'));
		$optionsection[] = JHTML::_('select.option', '8', JText::_('COM_REDSHOP_COMPANY_ADDRESS'));
		$optionsection[] = JHTML::_('select.option', '9', JText::_('COM_REDSHOP_COLOR_SAMPLE'));
		$optionsection[] = JHTML::_('select.option', '10', JText::_('COM_REDSHOP_MANUFACTURER'));
		$optionsection[] = JHTML::_('select.option', '11', JText::_('COM_REDSHOP_SHIPPING'));
		$optionsection[] = JHTML::_('select.option', '12', JText::_('COM_REDSHOP_PRODUCT_USERFIELD'));
		$optionsection[] = JHTML::_('select.option', '13', JText::_('COM_REDSHOP_GIFTCARD_USERFIELD'));
		$optionsection[] = JHTML::_('select.option', '14', JText::_('COM_REDSHOP_CUSTOMER_SHIPPING_ADDRESS'));
		$optionsection[] = JHTML::_('select.option', '15', JText::_('COM_REDSHOP_COMPANY_SHIPPING_ADDRESS'));
		$optionsection[] = JHTML::_('select.option', '17', JText::_('COM_REDSHOP_PRODUCTFINDER_DATEPICKER'));
		$optionsection[] = JHTML::_('select.option', '16', JText::_('COM_REDSHOP_QUOTATION'));
		$optionsection[] = JHTML::_('select.option', '18', JText::_('COM_REDSHOP_PAYMENT_GATEWAY'));
		$optionsection[] = JHTML::_('select.option', '19', JText::_('COM_REDSHOP_SHIPPING_GATEWAY'));

		if ($sectionvalue != "")
		{
			$sectionname = "";

			for ($i = 0; $i < count($optionsection); $i++)
			{
				if ($optionsection[$i]->value == $sectionvalue)
				{
					$sectionname = $optionsection[$i]->text;
					break;
				}
			}

			return $sectionname;
		}
		else
		{
			return $optionsection;
		}
	}

	/**
	 * Collect Costume field type select list options
	 *
	 * @param   string  $sectionvalue  Selected field type section
	 *
	 * @return  array                 Costume field type option list
	 */
	public function getFieldTypeSections($sectionvalue = "")
	{
		$optiontype = array();
		$optiontype[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT'));
		$optiontype[] = JHTML::_('select.option', '1', JText::_('COM_REDSHOP_TEXT_FIELD'));
		$optiontype[] = JHTML::_('select.option', '2', JText::_('COM_REDSHOP_TEXT_AREA'));
		$optiontype[] = JHTML::_('select.option', '3', JText::_('COM_REDSHOP_CHECKBOX'));
		$optiontype[] = JHTML::_('select.option', '4', JText::_('COM_REDSHOP_RADIOBOX'));
		$optiontype[] = JHTML::_('select.option', '5', JText::_('COM_REDSHOP_SINGLE_SELECT_BOX'));
		$optiontype[] = JHTML::_('select.option', '6', JText::_('COM_REDSHOP_MULTI_SELECT_BOX'));
		$optiontype[] = JHTML::_('select.option', '7', JText::_('COM_REDSHOP_SELECT_COUNTRY_BOX'));
		$optiontype[] = JHTML::_('select.option', '8', JText::_('COM_REDSHOP_WYSIWYG'));
		$optiontype[] = JHTML::_('select.option', '9', JText::_('COM_REDSHOP_MEDIA'));
		$optiontype[] = JHTML::_('select.option', '10', JText::_('COM_REDSHOP_DOCUMENTS'));
		$optiontype[] = JHTML::_('select.option', '11', JText::_('COM_REDSHOP_IMAGE'));
		$optiontype[] = JHTML::_('select.option', '12', JText::_('COM_REDSHOP_DATE_PICKER'));
		$optiontype[] = JHTML::_('select.option', '13', JText::_('COM_REDSHOP_IMAGE_WITH_LINK'));
		$optiontype[] = JHTML::_('select.option', '15 ', JText::_('COM_REDSHOP_SELECTION_BASED_ON_SELECTED_CONDITIONS'));

		if ($sectionvalue != "")
		{
			$sectionname = "";

			for ($i = 0; $i < count($optiontype); $i++)
			{
				if ($optiontype[$i]->value == $sectionvalue)
				{
					$sectionname = $optiontype[$i]->text;
					break;
				}
			}

			return $sectionname;
		}
		else
		{
			return $optiontype;
		}
	}

	/**
	 * Method to parse mod_redshop_lettersearch module parameter.
	 *
	 * @return void
	 */
	public function GetlettersearchParameters()
	{
		$db = Jfactory::getDBO();
		$sel = 'SELECT params from #__extensions where element = "mod_redshop_lettersearch" ';
		$db->setQuery($sel);
		$params = $db->loadResult();
		$letterparamArr = array();
		$allparams = explode("\n", $params);

		for ($i = 0; $i < count($allparams); $i++)
		{
			$letter_param = explode('=', $allparams[$i]);

			if (!empty($letter_param))
			{
				$letterparamArr[$letter_param[0]] = $letter_param[1];
			}
		}

		return $letterparamArr;
	}
}
