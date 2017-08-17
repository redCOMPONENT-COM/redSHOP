<?php
/**
 * @package     RedSHOP.Libraries
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * redSHOP libraries helper template
 *
 * @package  RedSHOP
 * @since    2.5
 */
class RedshopHelperTemplate
{
	/**
	 * Template array
	 *
	 * @var  array
	 */
	protected static $templates = array();

	/**
	 * Get Template Values
	 *
	 * @param   string $name                 Name template hint
	 * @param   string $templateSection      Template section
	 * @param   string $descriptionSeparator Description separator
	 * @param   string $lineSeparator        Line separator
	 *
	 * @return array|string
	 *
	 * @since  2.0.0.3
	 */
	public static function getTemplateValues($name, $templateSection = '', $descriptionSeparator = '-', $lineSeparator = '<br />')
	{
		$lang = JFactory::getLanguage();
		$path = 'template_tag';

		if ($templateSection == 'mail')
		{
			$path = 'mail_template_tag';
		}

		$result      = RedshopLayoutHelper::render('templates.' . $path, array('name' => $name));
		$jTextPrefix = 'COM_REDSHOP_' . strtoupper($path) . '_' . strtoupper($name) . '_';

		if ($matches = explode('{', $result))
		{
			foreach ($matches as $key => $match)
			{
				$str = strpos($match, '}');

				if ($str !== false)
				{
					$matches[$key] = substr($match, 0, $str);
				}
				else
				{
					unset($matches[$key]);
				}
			}

			if (count($matches) > 0)
			{
				$countItems = 0;

				foreach ($matches as $match)
				{
					$replace  = '';
					$matchFix = strtoupper(str_replace(array(' ', ':'), '_', $match));

					if ($lang->hasKey($jTextPrefix . $matchFix))
					{
						$replace = $jTextPrefix . $matchFix;
					}
					elseif ($lang->hasKey('COM_REDSHOP_TEMPLATE_TAG_' . $matchFix))
					{
						$replace = 'COM_REDSHOP_TEMPLATE_TAG_' . $matchFix;
					}

					if ($replace)
					{
						$result = str_replace(
							'{' . $match . '}',
							str_replace(array('{', '}'), array('_AA_', '_BB_'), JText::sprintf($replace, $descriptionSeparator)) . $lineSeparator,
							$result
						);

						$countItems++;
					}
				}

				$result = str_replace(array('_AA_', '_BB_'), array('{', '}'), $result);
			}
		}

		return $result;
	}

	/**
	 * Get Template Values
	 *
	 * @param   string  $name             Name template hint
	 * @param   string  $templateSection  Template section
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getTemplateTags($name, $templateSection = '')
	{
		$lang = JFactory::getLanguage();
		$path = 'template_tag';

		if ($templateSection == 'mail')
		{
			$path = 'mail_template_tag';
		}

		$result      = RedshopLayoutHelper::render('templates.' . $path, array('name' => $name));
		$jTextPrefix = 'COM_REDSHOP_' . strtoupper($path) . '_' . strtoupper($name) . '_';
		$tags        = array();

		if ($matches = explode('{', $result))
		{
			foreach ($matches as $key => $match)
			{
				$str = strpos($match, '}');

				if ($str !== false)
				{
					$matches[$key] = substr($match, 0, $str);
				}
				else
				{
					unset($matches[$key]);
				}
			}

			if (count($matches) > 0)
			{
				$countItems = 0;

				foreach ($matches as $match)
				{
					$replace  = '';
					$matchFix = strtoupper(str_replace(array(' ', ':'), '_', $match));

					if ($lang->hasKey($jTextPrefix . $matchFix))
					{
						$replace = $jTextPrefix . $matchFix;
					}
					elseif ($lang->hasKey('COM_REDSHOP_TEMPLATE_TAG_' . $matchFix))
					{
						$replace = 'COM_REDSHOP_TEMPLATE_TAG_' . $matchFix;
					}

					$tags[$match] = trim(str_replace('{' . $match . '}', '', JText::sprintf($replace, '')));
				}
			}
		}

		return $tags;
	}

	/**
	 * Method to get Template
	 *
	 * @param   string $section    Set section Template
	 * @param   int    $templateId Template Id
	 * @param   string $name       Template Name
	 *
	 * @return  array              Template Array
	 *
	 * @since  2.0.0.3
	 */
	public static function getTemplate($section = '', $templateId = 0, $name = "")
	{
		JFactory::getLanguage()->load('com_redshop', JPATH_SITE);

		$key = $section . '_' . $templateId . '_' . $name;

		if (!array_key_exists($key, self::$templates))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('*')
				->from($db->qn('#__redshop_template'))
				->where('template_section = ' . $db->quote($section))
				->where('published = 1')
				->order('template_id ASC');

			if ($templateId != 0)
			{
				// Sanitize ids
				$arrayTid = explode(',', $templateId);
				JArrayHelper::toInteger($arrayTid);

				$query->where('template_id IN (' . implode(',', $arrayTid) . ')');
			}

			if ($name != '')
			{
				$query->where('template_name = ' . $db->quote($name));
			}

			$db->setQuery($query);

			self::$templates[$key] = $db->loadObjectList();
		}

		$templates = self::$templates[$key];

		foreach ($templates as $index => $template)
		{
			$userContent = self::readTemplateFile($template->template_section, $template->template_name);

			if ($userContent !== false)
			{
				$templates[$index]->template_desc = $userContent;
			}
		}

		return $templates;
	}

	/**
	 * Method to read Template from file
	 *
	 * @param   string  $section  Template Section
	 * @param   string  $fileName Template File Name
	 * @param   boolean $isAdmin  Check for administrator call
	 *
	 * @return  string              Template Content
	 *
	 * @since  2.0.0.3
	 */
	public static function readTemplateFile($section, $fileName, $isAdmin = false)
	{
		$filePath = self::getTemplateFilePath($section, $fileName, $isAdmin);

		if (file_exists($filePath))
		{
			$content = implode("", file($filePath));

			return $content;
		}

		return false;
	}

	/**
	 * Method to get Template file path
	 *
	 * @param   string  $section  Template Section
	 * @param   string  $fileName Template File Name
	 * @param   boolean $isAdmin  Check for administrator call
	 *
	 * @return  string              Template File Path
	 *
	 * @since  2.0.0.3
	 */
	public static function getTemplateFilePath($section, $fileName, $isAdmin = false)
	{
		$app          = JFactory::getApplication();
		$fileName     = str_replace(array('/', '\\'), '', $fileName);
		$section      = str_replace(array('/', '\\'), '', $section);
		$templateView = self::getTemplateView($section);
		$layout       = $app->input->getString('layout', '');

		if (!$isAdmin && $section != 'categoryproduct')
		{
			$templateFile = JPATH_SITE . '/templates/' . $app->getTemplate() . "/html/com_redshop/$templateView/$section/$fileName.php";
		}
		else
		{
			$templateFile = JPATH_SITE . '/templates/' . $app->getTemplate() . "/html/com_redshop/$section/$fileName.php";
		}

		if (!file_exists($templateFile))
		{
			if ($section == 'categoryproduct' && $layout == 'categoryproduct')
			{
				$templateDir = JPATH_SITE . "/components/com_redshop/templates/$section/$fileName.php";
			}

			if ($templateView && $section != 'categoryproduct')
			{
				$templateDir = JPATH_SITE . "/components/com_redshop/views/$templateView/tmpl/$section";

				try
				{
					chmod(JPath::clean(JPATH_SITE . "/components/com_redshop/views/$templateView/tmpl"), 0755);
				}
				catch (Exception $e)
				{
					JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
				}
			}
			else
			{
				if (!defined('JPATH_REDSHOP_TEMPLATE'))
				{
					// Define redSHOP Template Path
					define('JPATH_REDSHOP_TEMPLATE', JPATH_SITE . "/components/com_redshop/templates");
				}

				$templateDir = JPATH_REDSHOP_TEMPLATE . '/' . $section;

				try
				{
					chmod(JPATH_REDSHOP_TEMPLATE, 0755);
				}
				catch (Exception $e)
				{
					JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
				}
			}

			if (!is_dir($templateDir))
			{
				JFolder::create($templateDir, 0755);
			}

			$templateFile = "$templateDir/$fileName.php";
		}

		return $templateFile;
	}

	/**
	 * Template View selector
	 *
	 * @param   string $section Template Section
	 *
	 * @return  string            Template Joomla view name
	 *
	 * @since  2.0.0.3
	 */
	public static function getTemplateView($section)
	{
		$section = strtolower($section);
		$view    = '';

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
	 * @param   string $string Joomla content
	 *
	 * @return  string           Modified content
	 *
	 * @since  2.0.0.3
	 */
	public static function parseRedshopPlugin($string = "")
	{
		global $context;

		$content       = new stdClass;
		$content->text = $string;
		$temp          = array();

		JPluginHelper::importPlugin('content');
		RedshopHelperUtility::getDispatcher()->trigger('onContentPrepare', array($context, &$content, &$temp, 0));

		return $content->text;
	}

	/**
	 * Collect Template Sections for installation
	 *
	 * @param   string  $templateName Template Name
	 * @param   boolean $setFlag      Set true if you want html special character in template content
	 *
	 * @return  string                  redSHOP Template Contents
	 *
	 * @since  2.0.0.3
	 */
	public static function getInstallSectionTemplate($templateName, $setFlag = false)
	{
		$templateFile = JPATH_SITE . "/components/com_redshop/templates/rsdefaulttemplates/$templateName.php";

		if (!file_exists($templateFile))
		{
			return '';
		}

		$handle   = fopen($templateFile, "r");
		$contents = fread($handle, filesize($templateFile));
		fclose($handle);

		if ($setFlag)
		{
			return "<pre/>" . htmlspecialchars($contents) . "</pre>";
		}

		return $contents;
	}

	/**
	 * Collect list of redSHOP Template
	 *
	 * @param   string $sectionValue Template Section selected value
	 *
	 * @return  array                  Template Section List options
	 *
	 * @since  2.0.0.3
	 */
	public static function getTemplateSections($sectionValue = "")
	{
		$options = array(
			'product'                    => JText::_('COM_REDSHOP_PRODUCT'),
			'related_product'            => JText::_('COM_REDSHOP_RELATED_PRODUCT'),
			'category'                   => JText::_('COM_REDSHOP_CATEGORY'),
			'manufacturer'               => JText::_('COM_REDSHOP_MANUFACTURER'),
			'manufacturer_detail'        => JText::_('COM_REDSHOP_MANUFACTURER_DETAIL'),
			'manufacturer_products'      => JText::_('COM_REDSHOP_MANUFACTURER_PRODUCTS'),
			'newsletter'                 => JText::_('COM_REDSHOP_NEWSLETTER'),
			'newsletter_product'         => JText::_('COM_REDSHOP_NEWSLETTER_PRODUCTS'),
			'empty_cart'                 => JText::_('COM_REDSHOP_EMPTY_CART'),
			'cart'                       => JText::_('COM_REDSHOP_CART'),
			'add_to_cart'                => JText::_('COM_REDSHOP_ADD_TO_CART'),
			'catalog'                    => JText::_('COM_REDSHOP_CATALOG'),
			'product_sample'             => JText::_('COM_REDSHOP_PRODUCT_SAMPLE'),
			'order_list'                 => JText::_('COM_REDSHOP_ORDER_LIST'),
			'order_detail'               => JText::_('COM_REDSHOP_ORDER_DETAIL'),
			'order_receipt'              => JText::_('COM_REDSHOP_ORDER_RECEIPT'),
			'review'                     => JText::_('COM_REDSHOP_REVIEW'),
			'frontpage_category'         => JText::_('COM_REDSHOP_FRONTPAGE_CATEGORY'),
			'attribute_template'         => JText::_('COM_REDSHOP_ATTRIBUTE_TEMPLATE'),
			'attributewithcart_template' => JText::_('COM_REDSHOP_ATTRIBUTE_WITH_CART_TEMPLATE'),
			'accessory_template'         => JText::_('COM_REDSHOP_ACCESSORY_TEMPLATE'),
			'account_template'           => JText::_('COM_REDSHOP_ACCOUNT_TEMPLATE'),
			'wishlist_template'          => JText::_('COM_REDSHOP_WISHLIST_TEMPLATE'),
			'wishlist_mail_template'     => JText::_('COM_REDSHOP_WISHLIST_MAIL_TEMPLATE'),
			'wrapper_template'           => JText::_('COM_REDSHOP_WRAPPER_TEMPLATE'),
			'ajax_cart_detail_box'       => JText::_('COM_REDSHOP_AJAX_CART_DETAIL_BOX'),
			'ajax_cart_box'              => JText::_('COM_REDSHOP_AJAX_CART_BOX_TMP'),
			'ask_question_template'      => JText::_('COM_REDSHOP_ASK_QUESTION_TEMPLATE'),
			'giftcard_list'              => JText::_('COM_REDSHOP_GIFTCARD_LIST_TEMPLATE'),
			'giftcard'                   => JText::_('COM_REDSHOP_GIFTCARD_TEMPLATE'),
			'shipping_pdf'               => JText::_('COM_REDSHOP_SHIPPING_PDF_TEMPLATE'),
			'clicktell_sms_message'      => JText::_('COM_REDSHOP_CLICKTELL_SMS_MESSAGE'),
			'order_print'                => JText::_('COM_REDSHOP_ORDER_PRINT_TEMPLATE'),
			'redproductfinder'           => JText::_('COM_REDSHOP_redPRODUCTFINDER'),
			'quotation_detail'           => JText::_('COM_REDSHOP_QUOTATION_DETAIL_TEMPLATE'),
			'quotation_cart'             => JText::_('COM_REDSHOP_QUOTATION_CART'),
			'quotation_request'          => JText::_('COM_REDSHOP_QUOTATION_REQUEST_TEMPLATE'),
			'catalogue_cart'             => JText::_('COM_REDSHOP_CATALOGUE_CART_TEMPLATE'),
			'catalogue_order_detail'     => JText::_('COM_REDSHOP_CATALOGUE_ORDER_DETAIL_TEMPLATE'),
			'catalogue_order_receipt'    => JText::_('COM_REDSHOP_CATALOGUE_ORDER_RECEIPT_TEMPLATE'),
			'compare_product'            => JText::_('COM_REDSHOP_COMPARE_PRODUCT_TEMPLATE'),
			'clickatell'                 => JText::_('COM_REDSHOP_CLICKATELL'),
			'redshop_payment'            => JText::_('COM_REDSHOP_PAYMENT_METHOD_TEMPLATE'),
			'redshop_shipping'           => JText::_('COM_REDSHOP_SHIPPING_METHOD_TEMPLATE'),
			'shippingbox'                => JText::_('COM_REDSHOP_SHIPPING_BOX_TEMPLATE'),
			'onestep_checkout'           => JText::_('COM_REDSHOP_ONESTEP_CHECKOUT_TEMPLATE'),
			'categoryproduct'            => JText::_('COM_REDSHOP_PRODUCT_CATEGORY_TEMPLATE'),
			'change_cart_attribute'      => JText::_('COM_REDSHOP_CHANGE_CART_ATTRIBUTE_TEMPLATE'),
			'checkout'                   => JText::_('COM_REDSHOP_CHECKOUT_TEMPLATE'),
			'product_content_template'   => JText::_('COM_REDSHOP_PRODUCT_CONTENT'),
			'billing_template'           => JText::_('COM_REDSHOP_BILLING_TEMPLATE'),
			'private_billing_template'   => JText::_('COM_REDSHOP_PRIVATE_BILLING_TEMPLATE'),
			'company_billing_template'   => JText::_('COM_REDSHOP_COMPANY_BILLING_TEMPLATE'),
			'shipping_template'          => JText::_('COM_REDSHOP_SHIPPING_TEMPLATE'),
			'stock_note'                 => JText::_('COM_REDSHOP_STOCK_NOTE_TEMPLATE'),
			'login'                      => JText::_('COM_REDSHOP_LOGIN_TEMPLATE')
		);

		JPluginHelper::importPlugin('system');
		RedshopHelperUtility::getDispatcher()->trigger('onTemplateSections', array(&$options));

		return self::prepareSectionOptions($options, $sectionValue);
	}

	/**
	 * Collect Mail Template Section Select Option Value
	 *
	 * @param   string $sectionValue Selected Section Name
	 *
	 * @return  array                  Mail Template Select list options
	 *
	 * @since  2.0.0.3
	 */
	public static function getMailSections($sectionValue = "")
	{
		$options = array(
			'order'                             => JText::_('COM_REDSHOP_ORDER_MAIL'),
			'catalogue_order'                   => JText::_('COM_REDSHOP_CATALOGUE_ORDER_MAIL'),
			'order_special_discount'            => JText::_('COM_REDSHOP_ORDER_SPECIAL_DISCOUNT_MAIL'),
			'order_status'                      => JText::_('COM_REDSHOP_ORDER_STATUS_CHANGE'),
			'register'                          => JText::_('COM_REDSHOP_REGISTRATION_MAIL'),
			'product'                           => JText::_('COM_REDSHOP_PRODUCT_INFORMATION'),
			'tax_exempt_approval_mail'          => JText::_('COM_REDSHOP_TAX_EXEMPT_APPROVAL_MAIL'),
			'tax_exempt_disapproval_mail'       => JText::_('COM_REDSHOP_TAX_EXEMPT_DISAPPROVAL_MAIL'),
			'tax_exempt_waiting_approval_mail'  => JText::_('COM_REDSHOP_TAX_EXEMPT_WAITING_APPROVAL_MAIL'),
			'catalog'                           => JText::_('COM_REDSHOP_CATALOG_SEND_MAIL'),
			'catalog_first_reminder'            => JText::_('COM_REDSHOP_CATALOG_FIRST_REMINDER'),
			'catalog_second_reminder'           => JText::_('COM_REDSHOP_CATALOG_SECOND_REMINDER'),
			'catalog_coupon_reminder'           => JText::_('COM_REDSHOP_CATALOG_COUPON_REMINDER'),
			'colour_sample_first_reminder'      => JText::_('COM_REDSHOP_CATALOG_SAMPLE_FIRST_REMINDER'),
			'colour_sample_second_reminder'     => JText::_('COM_REDSHOP_CATALOG_SAMPLE_SECOND_REMINDER'),
			'colour_sample_third_reminder'      => JText::_('COM_REDSHOP_CATALOG_SAMPLE_THIRD_REMINDER'),
			'colour_sample_coupon_reminder'     => JText::_('COM_REDSHOP_CATALOG_SAMPLE_COUPON_REMINDER'),
			'first_mail_after_order_purchased'  => JText::_('COM_REDSHOP_FIRST_MAIL_AFTER_ORDER_PURCHASED'),
			'second_mail_after_order_purchased' => JText::_('COM_REDSHOP_SECOND_MAIL_AFTER_ORDER_PURCHASED'),
			'third_mail_after_order_purchased'  => JText::_('COM_REDSHOP_THIRD_MAIL_AFTER_ORDER_PURCHASED'),
			'economic_inoice'                   => JText::_('COM_REDSHOP_ECONOMIC_INVOICE'),
			'newsletter_confirmation'           => JText::_('COM_REDSHOP_NEWSLETTER_CONFIRMTION'),
			'newsletter_cancellation'           => JText::_('COM_REDSHOP_NEWSLETTER_CANCELLATION'),
			'mywishlist_mail'                   => JText::_('COM_REDSHOP_WISHLIST_MAIL'),
			'ask_question_mail'                 => JText::_('COM_REDSHOP_ASK_QUESTION_MAIL'),
			'downloadable_product_mail'         => JText::_('COM_REDSHOP_DOWNLOADABLE_PRODUCT_MAIL'),
			'giftcard_mail'                     => JText::_('COM_REDSHOP_GIFTCARD_MAIL'),
			'invoice_mail'                      => JText::_('COM_REDSHOP_INVOICE_MAIL'),
			'quotation_mail'                    => JText::_('COM_REDSHOP_QUOTATION_MAIL'),
			'quotation_user_register'           => JText::_('COM_REDSHOP_QUOTATION_USER_REGISTER_MAIL'),
			'request_tax_exempt_mail'           => JText::_('COM_REDSHOP_REQUEST_TAX_EXEMPT_MAIL'),
			'subscription_renewal_mail'         => JText::_('COM_REDSHOP_SUBSCRIPTION_RENEWAL_MAIL'),
			'review_mail'                       => JText::_('COM_REDSHOP_REVIEW_MAIL'),
			'notify_stock_mail'                 => JText::_('COM_REDSHOP_NOTIFY_STOCK'),
			'invoicefile_mail'                  => JText::_('COM_REDSHOP_INVOICE_FILE_MAIL')
		);

		JPluginHelper::importPlugin('system');
		RedshopHelperUtility::getDispatcher()->trigger('onMailSections', array(&$options));

		return self::prepareSectionOptions($options, $sectionValue);
	}

	/**
	 * Collect redSHOP costume field section select list option
	 *
	 * @param   string $sectionValue Selected option Value
	 *
	 * @return  array                 Costume field Select list options
	 *
	 * @since  2.0.0.3
	 */
	public static function getFieldSections($sectionValue = "")
	{
		$options = array(
			'1'  => JText::_('COM_REDSHOP_PRODUCT'),
			'2'  => JText::_('COM_REDSHOP_CATEGORY'),
			'7'  => JText::_('COM_REDSHOP_CUSTOMER_ADDRESS'),
			'8'  => JText::_('COM_REDSHOP_COMPANY_ADDRESS'),
			'9'  => JText::_('COM_REDSHOP_COLOR_SAMPLE'),
			'10' => JText::_('COM_REDSHOP_MANUFACTURER'),
			'11' => JText::_('COM_REDSHOP_SHIPPING'),
			'12' => JText::_('COM_REDSHOP_PRODUCT_USERFIELD'),
			'13' => JText::_('COM_REDSHOP_GIFTCARD_USERFIELD'),
			'14' => JText::_('COM_REDSHOP_CUSTOMER_SHIPPING_ADDRESS'),
			'15' => JText::_('COM_REDSHOP_COMPANY_SHIPPING_ADDRESS'),
			'17' => JText::_('COM_REDSHOP_PRODUCTFINDER_DATEPICKER'),
			'16' => JText::_('COM_REDSHOP_QUOTATION'),
			'18' => JText::_('COM_REDSHOP_PAYMENT_GATEWAY'),
			'19' => JText::_('COM_REDSHOP_SHIPPING_GATEWAY'),
			'20' => JText::_('COM_REDSHOP_ORDER')
		);

		return self::prepareSectionOptions($options, $sectionValue);
	}

	/**
	 * Collect Costume field type select list options
	 *
	 * @param   string $sectionValue Selected field type section
	 *
	 * @return  array                 Costume field type option list
	 *
	 * @since  2.0.0.3
	 */
	public static function getFieldTypeSections($sectionValue = "")
	{
		$options = array(
			'1'  => JText::_('COM_REDSHOP_TEXT_FIELD'),
			'2'  => JText::_('COM_REDSHOP_TEXT_AREA'),
			'3'  => JText::_('COM_REDSHOP_CHECKBOX'),
			'4'  => JText::_('COM_REDSHOP_RADIOBOX'),
			'5'  => JText::_('COM_REDSHOP_SINGLE_SELECT_BOX'),
			'6'  => JText::_('COM_REDSHOP_MULTI_SELECT_BOX'),
			'7'  => JText::_('COM_REDSHOP_SELECT_COUNTRY_BOX'),
			'8'  => JText::_('COM_REDSHOP_WYSIWYG'),
			'9'  => JText::_('COM_REDSHOP_MEDIA'),
			'10' => JText::_('COM_REDSHOP_DOCUMENTS'),
			'11' => JText::_('COM_REDSHOP_IMAGE'),
			'12' => JText::_('COM_REDSHOP_DATE_PICKER'),
			'13' => JText::_('COM_REDSHOP_IMAGE_WITH_LINK'),
			'15' => JText::_('COM_REDSHOP_SELECTION_BASED_ON_SELECTED_CONDITIONS')
		);

		return self::prepareSectionOptions($options, $sectionValue);
	}

	/**
	 * Prepare Options for Select list
	 *
	 * @param   array  $options      Associative Options array
	 * @param   string $sectionValue Get single Section name
	 *
	 * @return  mixed   String or array based on $sectionValue
	 *
	 * @since  2.0.0.3
	 */
	public static function prepareSectionOptions($options, $sectionValue)
	{
		// Sort array alphabetically
		asort($options);

		$optionSection   = array();
		$optionSection[] = JHtml::_('select.option', '0', JText::_('COM_REDSHOP_SELECT'));

		if (empty($options))
		{
			return $optionSection;
		}

		foreach ($options as $key => $value)
		{
			$optionSection[] = JHtml::_('select.option', $key, $value);

			if ($sectionValue != "" && $key == $sectionValue)
			{
				return $value;
			}
		}

		return $optionSection;
	}

	/**
	 * Get ExtraFields For Current Template
	 *
	 * @param   array   $fieldNames      Field name list
	 * @param   string  $templateData    Template data
	 * @param   int     $isCategoryPage  Flag change extra fields in category page
	 *
	 * @return  string
	 *
	 * @since   2.0.6
	 */
	public static function getExtraFieldsForCurrentTemplate($fieldNames = array(), $templateData = '', $isCategoryPage = 0)
	{
		$prefix = '{';

		if ($isCategoryPage)
		{
			$prefix = '{producttag:';
		}

		if (empty($fieldNames))
		{
			return '';
		}

		$findFields = array();

		foreach ($fieldNames as $filedName)
		{
			if (strpos($templateData, $prefix . $filedName . "}") !== false)
			{
				$findFields[] = $filedName;
			}
		}

		if (empty($findFields))
		{
			return '';
		}

		return implode(',', RedshopHelperUtility::quote($findFields));
	}

	/**
	 * Method for render hints of field in specific section
	 *
	 * @param   integer  $fieldSection  Field section.
	 * @param   string   $heading       Heading.
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function renderFieldTagHints($fieldSection = 0, $heading = '')
	{
		$tagsSite  = RedshopHelperExtrafields::getSectionFieldList($fieldSection, 1);
		$tagsAdmin = RedshopHelperExtrafields::getSectionFieldList($fieldSection, 0);
		$tags      = array_merge((array) $tagsAdmin, (array) $tagsSite);

		$fieldTags = array();

		foreach ($tags as $tag)
		{
			$fieldTags[$tag->name] = $tag->title;
		}

		return RedshopLayoutHelper::render('templates.tags_hint', array('tags' => $fieldTags, 'header' => $heading));
	}
}
