<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Install
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
	 * @var  null|array
	 */
	public static $tasks = null;

	/**
	 * Get list of available tasks for clean install
	 *
	 * @return  array|mixed
	 *
	 * @since   2.0.6
	 *
	 * @throws  Exception
	 */
	public static function getInstallTasks()
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
				'text' => JText::_('COM_REDSHOP_INSTALL_STEP_INTEGRATE_SH404SEF'),
				'func' => 'RedshopInstall::integrateSh404sef'
			),
			array(
				'text' => JText::_('COM_REDSHOP_INSTALL_STEP_HANDLE_CONFIG'),
				'func' => 'RedshopInstall::handleConfig'
			)
		);

		JFactory::getApplication()->setUserState(self::REDSHOP_INSTALL_STATE_NAME, $tasks);

		return $tasks;
	}

	/**
	 * Method for get remaining tasks
	 *
	 * @return  mixed  List of remaining tasks
	 *
	 * @since   2.0.6
	 *
	 * @throws  Exception
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
	 * @throws  Exception
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

			RedshopHelperUser::storeRedshopUser($post, $joomlaUser->id, 1);
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
		$db     = JFactory::getDbo();
		$query  = $db->getQuery(true)
			->select('COUNT(id)')
			->from($db->qn('#__redshop_template'));
		$result = $db->setQuery($query)->loadResult();

		if ($result)
		{
			return true;
		}

		// Start template demo content
		$query = "INSERT IGNORE INTO `#__redshop_template` (`id`, `name`, `section`, `published`) VALUES
			(8, 'grid', 'category', 1),
			(5, 'list', 'category', 1),
			(26, 'product2', 'product',1),
			(9, 'product', 'product', 1),
			(29, 'newsletter1', 'newsletter', 1),
			(10, 'cart', 'cart', 1),
			(11, 'review', 'review', 1),
			(13, 'manufacturer_listings', 'manufacturer',1),
			(14, 'manufacturer_products', 'manufacturer_products',1),
			(15, 'order_list', 'order_list', 1),
			(16, 'order_detail', 'order_detail',1),
			(23, 'related_products', 'related_product',1),
			(17, 'order_receipt', 'order_receipt',1),
			(18, 'manufacturer_detail', 'manufacturer_detail',1),
			(22, 'frontpage_category', 'frontpage_category',1),
			(24, 'add_to_cart1', 'add_to_cart',1),
			(25, 'add_to_cart2', 'add_to_cart',1),
			(27, 'accessory', 'accessory_template',1),
			(28, 'attributes', 'attribute_template', 1),
			(100,'my_account_template','account_template',1),
			(101, 'catalog', 'catalog',1),
			(102, 'catalog_sample', 'product_sample',1),
			(103, 'wishlist_list','wishlist_template',1),
			(105, 'wishlist_mail','wishlist_mail_template',1),
			(115, 'wrapper','wrapper_template',1),
			(125, 'giftcard_listing','giftcard_list',1),
			(135, 'giftcard','giftcard',1),
			(110, 'ask_question', 'ask_question_template', 1),
			(111, 'ajax_cart_box', 'ajax_cart_box', 1),
			(112, 'ajax_cart_detail_box', 'ajax_cart_detail_box', 1),
			(200, 'shipping_pdf', 'shipping_pdf', 1),
			(251, 'order_print', 'order_print', 1),
			(252, 'clicktell_sms_message', 'clicktell_sms_message', 1),
			(260, 'redproductfinder', 'redproductfinder',1),
			(265, 'quotation_detail', 'quotation_detail', 1),
			(334, 'newsletter_products', 'newsletter_product', 1),
			(280, 'catalogue_cart', 'catalogue_cart', 1),
			(281, 'catalogue_order_detail', 'catalogue_order_detail', 1),
			(282, 'catalogue_order_receipt', 'catalogue_order_receipt', 1),
			(289, 'empty_cart', 'empty_cart', 1),
			(320, 'compare_product', 'compare_product', 1),
			(353, 'payment_method', 'redshop_payment', 1),
			(354, 'shipping_method', 'redshop_shipping', 1),
			(355, 'shipping_box', 'shippingbox', 1),
			(356, 'category_product_template', 'categoryproduct', 1),
			(357, 'change_cart_attribute_template', 'change_cart_attribute', 1),
			(358, 'onestep_checkout', 'onestep_checkout', 1),
			(359, 'attributes_listing1', 'attributewithcart_template', 1),
			(360, 'checkout', 'checkout', 1),
			(371, 'product_content', 'product_content_template', 1),
			(372, 'quotation_cart_template', 'quotation_cart', 1),
			(370, 'quotation_request_template', 'quotation_request', 1),
			(450, 'billing_template', 'billing_template', 1),
			(451, 'shipping_template', 'shipping_template', 1),
			(460, 'private_billing_template', 'private_billing_template', 1),
			(461, 'company_billing_template', 'company_billing_template', 1),
			(550, 'stock_note', 'stock_note', 1),
			(551, 'login', 'login', 1)";

		$db->setQuery($query)->execute();

		$query     = $db->getQuery(true)
			->select($db->qn('id'))
			->from($db->qn('#__redshop_template'))
			->order($db->qn('id'));
		$templates = $db->setQuery($query)->loadColumn();

		foreach ($templates as $templateId)
		{
			/** @var RedshopTableTemplate $table */
			$table = RedshopTable::getAdminInstance('Template', array('ignore_request' => true), 'com_redshop');

			$table->load($templateId);
			$table->templateDesc = RedshopHelperTemplate::getDefaultTemplateContent($table->section);
			$table->store();
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
	 *
	 * @throws  Exception
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

	/**
	 * Method for get specific available version of installation.
	 *
	 * @param   string  $version  Version specific.
	 *
	 * @return  array
	 *
	 * @since   2.1.0
	 */
	public static function getUpdateTasks($version = null)
	{
		if (null === $version)
		{
			return array();
		}

		$tasks = self::loadUpdateTasks();

		if (empty($tasks) || !isset($tasks[$version]))
		{
			return array();
		}

		return $tasks[$version];
	}

	/**
	 * Method for get all available version of installation.
	 *
	 * @return  array  List of update tasks.
	 *
	 * @since   2.1.0
	 */
	public static function loadUpdateTasks()
	{
		if (null !== self::$tasks)
		{
			return self::$tasks;
		}

		$updatePath = JPATH_COMPONENT_ADMINISTRATOR . '/updates';

		$files    = JFolder::files($updatePath, '.php', false, true);
		$versions = array();

		foreach ($files as $file)
		{
			$version = new stdClass;

			$version->version = JFile::stripExt(basename($file));

			require_once $file;

			$version->class = 'RedshopUpdate' . str_replace(array('.', '-'), '', $version->version);
			$version->path  = $file;

			/** @var RedshopInstallUpdate $updateClass */
			$updateClass    = new $version->class;
			$classTasks     = $updateClass->getTasksList();
			$version->tasks = array();

			if (empty($classTasks))
			{
				continue;
			}

			foreach ($classTasks as $classTask)
			{
				$version->tasks[] = array('text' => JText::_($classTask->name), 'func' => $version->class . '.' . $classTask->func);
			}

			$versions[$version->version] = $version;
		}

		uksort($versions, 'version_compare');
		$versions = array_reverse($versions);

		self::$tasks = $versions;

		return self::$tasks;
	}
}
