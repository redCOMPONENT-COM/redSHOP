<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Install
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Install class
 *
 * @since  2.0.6
 */
class RedshopInstall
{
	/**
	 * Install state key
	 *
	 * @var   string
	 */
	const REDSHOP_INSTALL_STATE_NAME = 'redshop.install.tasks';

	/**
	 * Get list of available tasks for clean install
	 *
	 * @return  array|mixed
	 *
	 * @since   2.0.6
	 */
	public static function getInstallTasks()
	{
		$app   = JFactory::getApplication();
		$tasks = $app->getUserState(self::REDSHOP_INSTALL_STATE_NAME, null);

		if (is_null($tasks))
		{
			$tasks = array(
				array(
					'text' => JText::_('COM_REDSHOP_INSTALL_STEP_SYNCHRONIZE_USERS'),
					'func' => 'RedshopInstall::synchronizeUser'
				),
				array(
					'text' => JText::_('COM_REDSHOP_INSTALL_STEP_TEMPLATE_DATA'),
					'func' => 'RedshopInstall::templateData'
				),
				array(
					'text' => JText::_('COM_REDSHOP_INSTALL_STEP_TEMPLATE_FILES'),
					'func' => 'RedshopInstall::templateFiles'
				),
				array(
					'text' => JText::_('COM_REDSHOP_INSTALL_STEP_INTEGRATE_SH404SEF'),
					'func' => 'RedshopInstall::integrateSh404sef'
				),
				array(
					'text' => JText::_('COM_REDSHOP_INSTALL_STEP_HANDLE_CONFIG'),
					'func' => 'RedshopInstall::handleConfig'
				)
			);

			$app->setUserState(self::REDSHOP_INSTALL_STATE_NAME, $tasks);
		}

		return $tasks;
	}

	/**
	 * Method for get remaining tasks
	 *
	 * @return  mixed  List of remaining tasks
	 *
	 * @since   2.0.6
	 */
	public static function getRemainingTasks()
	{
		return JFactory::getApplication()->getUserState(self::REDSHOP_INSTALL_STATE_NAME, null);
	}

	/**
	 * Method for synchronize Joomla User to redSHOP user
	 *
	 * @return  int   Number of synchronized user.
	 *
	 * @since   2.0.6
	 */
	public static function synchronizeUser()
	{
		$db          = JFactory::getDbo();
		$query       = $db->getQuery(true)
			->select('u.*')
			->from($db->qn('#__users', 'u'))
			->leftJoin($db->qn('#__redshop_users_info', 'ru') . ' ON ' . $db->qn('ru.user_id') . ' = ' . $db->qn('u.id'))
			->where($db->qn('ru.user_id') . ' IS NULL');
		$joomlaUsers = $db->setQuery($query)->loadObjectList();

		if (empty($joomlaUsers))
		{
			return 0;
		}

		$userHelper = rsUserHelper::getInstance();

		foreach ($joomlaUsers as $joomlaUser)
		{
			$name = explode(" ", $joomlaUser->name);

			$post               = array();
			$post['user_id']    = $joomlaUser->id;
			$post['email']      = $joomlaUser->email;
			$post['email1']     = $joomlaUser->email;
			$post['firstname']  = $name[0];
			$post['lastname']   = (isset($name[1]) && $name[1]) ? $name[1] : '';
			$post['is_company'] = (Redshop::getConfig()->get('DEFAULT_CUSTOMER_REGISTER_TYPE') == 2) ? 1 : 0;
			$post['password1']  = '';
			$post['billisship'] = 1;

			$userHelper->storeRedshopUser($post, $joomlaUser->id, 1);
		}

		return count($joomlaUsers);
	}

	/**
	 * Method for insert demo templates
	 *
	 * @return  boolean
	 *
	 * @since   2.0.6
	 */
	public static function templateData()
	{
		// Start template demo content
		$redTemplate = Redtemplate::getInstance();
		$q           = "INSERT IGNORE INTO `#__redshop_template` (`template_id`, `template_name`, `template_section`, `template_desc`, `published`) VALUES
					(8, 'grid', 'category', '" . $redTemplate->getInstallSectionTemplate('grid') . "', 1),
					(5, 'list', 'category', '" . $redTemplate->getInstallSectionTemplate('list') . "', 1),
					(26, 'product2', 'product', '" . $redTemplate->getInstallSectionTemplate('product2') . "', 1),
					(9, 'product', 'product', '" . $redTemplate->getInstallSectionTemplate('product') . "', 1),
					(29, 'newsletter1', 'newsletter', '" . $redTemplate->getInstallSectionTemplate('newsletter1') . "', 1),
					(10, 'cart', 'cart', '" . $redTemplate->getInstallSectionTemplate('cart') . "', 1),
					(11, 'review', 'review', '" . $redTemplate->getInstallSectionTemplate('review') . "', 1),
					(13, 'manufacturer_listings', 'manufacturer','" . $redTemplate->getInstallSectionTemplate('manufacturer_listings') . "', 1),
					(14, 'manufacturer_products', 'manufacturer_products','" . $redTemplate->getInstallSectionTemplate('manufacturer_products') . "', 1),
					(15, 'order_list', 'order_list', '" . $redTemplate->getInstallSectionTemplate('order_list') . "', 1),
					(16, 'order_detail', 'order_detail', '" . $redTemplate->getInstallSectionTemplate('order_detail') . "', 1),
					(23, 'related_products', 'related_product', '" . $redTemplate->getInstallSectionTemplate('related_products') . "', 1),
					(17, 'order_receipt', 'order_receipt', '" . $redTemplate->getInstallSectionTemplate('order_receipt') . "', 1),
					(18, 'manufacturer_detail', 'manufacturer_detail', '" . $redTemplate->getInstallSectionTemplate('manufacturer_detail') . "', 1),
					(22, 'frontpage_category', 'frontpage_category', '" . $redTemplate->getInstallSectionTemplate('frontpage_category') . "', 1),
					(24, 'add_to_cart1', 'add_to_cart', '" . $redTemplate->getInstallSectionTemplate('add_to_cart1') . "', 1),
					(25, 'add_to_cart2', 'add_to_cart', '" . $redTemplate->getInstallSectionTemplate('add_to_cart2') . "', 1),
					(27, 'accessory', 'accessory_template', '" . $redTemplate->getInstallSectionTemplate('accessory') . "', 1),
					(28, 'attributes', 'attribute_template', '" . $redTemplate->getInstallSectionTemplate('attributes') . "', 1),
					(100,'my_account_template','account_template','" . $redTemplate->getInstallSectionTemplate('my_account_template') . "',1),
					(101, 'catalog', 'catalog', '" . $redTemplate->getInstallSectionTemplate('catalog') . "', 1),
					(102, 'catalog_sample', 'product_sample', '" . $redTemplate->getInstallSectionTemplate('catalog_sample') . "', 1),
					(103, 'wishlist_list','wishlist_template','" . $redTemplate->getInstallSectionTemplate('wishlist_list') . "',1),
					(105,'wishlist_mail','wishlist_mail_template','" . $redTemplate->getInstallSectionTemplate('wishlist_mail') . "',1),
					(115,'wrapper','wrapper_template','" . $redTemplate->getInstallSectionTemplate('wrapper') . "',1),
					(125,'giftcard_listing','giftcard_list','" . $redTemplate->getInstallSectionTemplate('giftcard_listing') . "',1),
					(135,'giftcard','giftcard','" . $redTemplate->getInstallSectionTemplate('giftcard') . "',1),
					(110, 'ask_question', 'ask_question_template', '" . $redTemplate->getInstallSectionTemplate('ask_question') . "', 1),
					(111, 'ajax_cart_box', 'ajax_cart_box', '" . $redTemplate->getInstallSectionTemplate('ajax_cart_box') . "', 1),
					(112, 'ajax_cart_detail_box', 'ajax_cart_detail_box', '" . $redTemplate->getInstallSectionTemplate('ajax_cart_detail_box') . "', 1),
					(200, 'shipping_pdf', 'shipping_pdf', '" . $redTemplate->getInstallSectionTemplate('shipping_pdf') . "', 1),
					(251, 'order_print', 'order_print', '" . $redTemplate->getInstallSectionTemplate('order_print') . "', 1),
					(252, 'clicktell_sms_message', 'clicktell_sms_message', '" . $redTemplate->getInstallSectionTemplate('clicktell_sms_message') . "', 1),
					(260, 'redproductfinder', 'redproductfinder', '" . $redTemplate->getInstallSectionTemplate('redproductfinder') . "', 1),
					(265, 'quotation_detail', 'quotation_detail', '" . $redTemplate->getInstallSectionTemplate('quotation_detail') . "', 1),
					(334, 'newsletter_products', 'newsletter_product', '" . $redTemplate->getInstallSectionTemplate('newsletter_products') . "', 1),
					(280, 'catalogue_cart', 'catalogue_cart', '" . $redTemplate->getInstallSectionTemplate('catalogue_cart') . "', 1),
					(281, 'catalogue_order_detail', 'catalogue_order_detail', '" . $redTemplate->getInstallSectionTemplate('catalogue_order_detail') . "', 1),
					(282, 'catalogue_order_receipt', 'catalogue_order_receipt', '" . $redTemplate->getInstallSectionTemplate('catalogue_order_receipt') . "', 1),
					(289, 'empty_cart', 'empty_cart', '" . $redTemplate->getInstallSectionTemplate('empty_cart') . "', 1),
					(320, 'compare_product', 'compare_product', '" . $redTemplate->getInstallSectionTemplate('compare_product') . "', 1),
					(353, 'payment_method', 'redshop_payment', '" . $redTemplate->getInstallSectionTemplate('payment_method') . "', 1),
					(354, 'shipping_method', 'redshop_shipping', '" . $redTemplate->getInstallSectionTemplate('shipping_method') . "', 1),
					(355, 'shipping_box', 'shippingbox', '" . $redTemplate->getInstallSectionTemplate('shippingbox') . "',1),
					(356, 'category_product_template', 'categoryproduct', '" . $redTemplate->getInstallSectionTemplate('category_product_template') . "', 1),
					(357, 'change_cart_attribute_template', 'change_cart_attribute', '" . $redTemplate->getInstallSectionTemplate('change_cart_attribute_template') . "', 1),
					(358, 'onestep_checkout', 'onestep_checkout', '" . $redTemplate->getInstallSectionTemplate('onestep_checkout') . "', 1),
					(359, 'attributes_listing1', 'attributewithcart_template', '" . $redTemplate->getInstallSectionTemplate('attributes_listing1') . "', 1),
					(360, 'checkout', 'checkout', '" . $redTemplate->getInstallSectionTemplate('checkout') . "',1),
					(371, 'product_content', 'product_content_template', '" . $redTemplate->getInstallSectionTemplate('product_content') . "',1),
					(372, 'quotation_cart_template', 'quotation_cart', '" . $redTemplate->getInstallSectionTemplate('quotation_cart_template') . "',1),
					(370, 'quotation_request_template', 'quotation_request', '" . $redTemplate->getInstallSectionTemplate('quotation_request_template') . "',1),
					(450, 'billing_template', 'billing_template', '" . $redTemplate->getInstallSectionTemplate('billing_template') . "',1),
					(451, 'shipping_template', 'shipping_template', '" . $redTemplate->getInstallSectionTemplate('shipping_template') . "',1),
					(460, 'private_billing_template', 'private_billing_template', '" . $redTemplate->getInstallSectionTemplate('private_billing_template') . "',1),
					(461, 'company_billing_template', 'company_billing_template', '" . $redTemplate->getInstallSectionTemplate('company_billing_template') . "',1),
					(550, 'stock_note', 'stock_note', '" . $redTemplate->getInstallSectionTemplate('stock_note') . "',1),
					(551, 'login', 'login', '" . $redTemplate->getInstallSectionTemplate('login') . "',1)";

		$db = JFactory::getDbo();

		return $db->setQuery($q)->execute();
	}

	/**
	 * Method for convert templates from DB to files.
	 *
	 * @return  boolean
	 *
	 * @since   2.0.6
	 */
	public static function templateFiles()
	{
		$db        = JFactory::getDbo();
		$query     = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_template'));
		$templates = $db->setQuery($query)->loadObjectList();

		foreach ($templates as $template)
		{
			$templateName            = $template->template_name;
			$template->template_name = strtolower($template->template_name);
			$template->template_name = str_replace(" ", "_", $template->template_name);
			$templateFile            = RedshopHelperTemplate::getTemplatefilepath($template->template_section, $template->template_name, true);

			if (!JFile::exists($templateFile))
			{
				$fp = fopen($templateFile, "w");
				fwrite($fp, $template->template_desc);
				fclose($fp);
			}

			$templateContent = file_get_contents($templateFile);

			if (!strstr($templateContent, '{product_subtotal}') && !strstr($templateContent, '{product_subtotal_excl_vat}'))
			{
				if (strstr($templateContent, '{subtotal}') || strstr($templateContent, '{order_subtotal}'))
				{
					$templateContent = str_replace("{subtotal}", "{product_subtotal}", $templateContent);
					$templateContent = str_replace("{order_subtotal}", "{product_subtotal}", $templateContent);
				}

				if (strstr($templateContent, '{subtotal_excl_vat}') || strstr($templateContent, '{order_subtotal_excl_vat}'))
				{
					$templateContent = str_replace("{subtotal_excl_vat}", "{product_subtotal_excl_vat}", $templateContent);
					$templateContent = str_replace("{order_subtotal_excl_vat}", "{product_subtotal_excl_vat}", $templateContent);
				}
			}

			if (!strstr($templateContent, '{shipping_excl_vat}'))
			{
				if (strstr($templateContent, '{shipping}'))
				{
					$templateContent = str_replace('{shipping}', '{shipping_excl_vat}', $templateContent);
				}

				if (strstr($templateContent, '{shipping_with_vat}'))
				{
					$templateContent = str_replace('{shipping_with_vat}', '{shipping}', $templateContent);
				}
			}

			$fp = fopen($templateFile, "w");
			fwrite($fp, $templateContent);
			fclose($fp);

			if ($template->template_id && $template->template_name != $templateName)
			{
				$query->clear()
					->update($db->qn('#__redshop_template'))
					->set($db->qn('template_name') . ' = ' . $template->template_name)
					->where($db->qn('template_id') . ' = ' . $template->template_id);

				$db->setQuery($query)->execute();
			}
		}

		return true;
	}

	/**
	 * Method for integrate with com_sh404sef extension if necessary.
	 *
	 * @return  boolean
	 *
	 * @throws  Exception
	 *
	 * @since   2.0.3
	 */
	public static function integrateSh404sef()
	{
		if (!JComponentHelper::isInstalled('com_sh404sef'))
		{
			return true;
		}

		// Install the sh404SEF router files
		JLoader::import('joomla.filesystem.file');
		JLoader::import('joomla.filesystem.folder');

		$sh404SEFAdmin    = JPATH_SITE . '/administrator/components/com_sh404sef';
		$redShopSefFolder = JPATH_SITE . '/administrator/components/com_redshop/extras';

		if (!JFile::copy($redShopSefFolder . '/sh404sef/language/com_redshop.php', $sh404SEFAdmin . '/language/plugins/com_redshop.php'))
		{
			throw new Exception(JText::_('COM_REDSHOP_FAILED_TO_COPY_SH404SEF_PLUGIN_LANGUAGE_FILE'));
		}

		return true;
	}

	/**
	 * Handle config
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public static function handleConfig()
	{
		// Only loading from legacy when version is older than 1.6
		if (version_compare(RedshopHelperJoomla::getManifestValue('version'), '1.6', '<'))
		{
			// Load configuration file from legacy file.
			Redshop::getConfig()->loadLegacy();
		}

		// Try to load distinct if no config found.
		Redshop::getConfig()->loadDist();
	}
}
