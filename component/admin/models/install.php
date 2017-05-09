<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

/**
 * Model Install
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       2.0.4
 */
class RedshopModelInstall extends RedshopModelList
{
	/**
	 * Method for get all available step of installation.
	 *
	 * @param   string  $type  Type of installation (install, install_discover, update)
	 *
	 * @return  array
	 *
	 * @since   2.0.4
	 */
	public function getSteps($type = 'install')
	{
		$tasks = array(
			array(
				'text' => JText::_('COM_REDSHOP_INSTALL_STEP_HANDLE_CONFIGURATION'),
				'func' => 'handleConfig'
			),
			array(
				'text' => JText::_('COM_REDSHOP_INSTALL_STEP_SYNCHRONIZE_USERS'),
				'func' => 'syncUser'
			),
			array(
				'text' => JText::_('COM_REDSHOP_INSTALL_STEP_TEMPLATE_DATA'),
				'func' => 'templateData'
			),
			array(
				'text' => JText::_('COM_REDSHOP_INSTALL_STEP_TEMPLATE_FILES'),
				'func' => 'templateFiles'
			),
			array(
				'text' => JText::_('COM_REDSHOP_INSTALL_STEP_UPDATE_MENU'),
				'func' => 'updateMenu'
			),
			array(
				'text' => JText::_('COM_REDSHOP_INSTALL_STEP_INTEGRATE_SH404SEF'),
				'func' => 'integrateSh404sef'
			),
		);

		if ($type == 'update')
		{
			$tasks[] = array(
				'text' => JText::_('COM_REDSHOP_INSTALL_STEP_CHECK_DATABASE_STRUCTURE'),
				'func' => 'updateCheckDatabase'
			);
			$tasks[] = array(
				'text' => JText::_('COM_REDSHOP_INSTALL_STEP_UPDATE_OVERRIDE_TEMPLATE'),
				'func' => 'updateOverrideTemplate'
			);
			$tasks[] = array(
				'text' => JText::_('COM_REDSHOP_INSTALL_STEP_CLEAN_OLD_FILES'),
				'func' => 'updateCleanOldFiles'
			);
			$tasks[] = array(
				'text' => JText::_('COM_REDSHOP_INSTALL_STEP_IMAGE_FILE_NAME'),
				'func' => 'updateImageFileNames'
			);
			$tasks[] = array(
				'text' => JText::_('COM_REDSHOP_INSTALL_STEP_UPDATE_SCHEMA'),
				'func' => 'updateDatabaseSchema'
			);
			$tasks[] = array(
				'text' => JText::_('COM_REDSHOP_INSTALL_STEP_UPDATE_CATEGORY'),
				'func' => 'updateCategory'
			);
		}

		return $tasks;
	}

	/**
	 * Method for insert demo templates
	 *
	 * @return  boolean
	 *
	 * @since   2.0.4
	 */
	public function processTemplateDemo()
	{
		// Start template demo content
		$redtemplate = Redtemplate::getInstance();
		$q           = "INSERT IGNORE INTO `#__redshop_template` (`template_id`, `template_name`, `template_section`, `template_desc`, `published`) VALUES
					(8, 'grid', 'category', '" . $redtemplate->getInstallSectionTemplate('grid') . "', 1),
					(5, 'list', 'category', '" . $redtemplate->getInstallSectionTemplate('list') . "', 1),
					(26, 'product2', 'product', '" . $redtemplate->getInstallSectionTemplate('product2') . "', 1),
					(9, 'product', 'product', '" . $redtemplate->getInstallSectionTemplate('product') . "', 1),
					(29, 'newsletter1', 'newsletter', '" . $redtemplate->getInstallSectionTemplate('newsletter1') . "', 1),
					(10, 'cart', 'cart', '" . $redtemplate->getInstallSectionTemplate('cart') . "', 1),
					(11, 'review', 'review', '" . $redtemplate->getInstallSectionTemplate('review') . "', 1),
					(13, 'manufacturer_listings', 'manufacturer','" . $redtemplate->getInstallSectionTemplate('manufacturer_listings') . "', 1),
					(14, 'manufacturer_products', 'manufacturer_products','" . $redtemplate->getInstallSectionTemplate('manufacturer_products') . "', 1),
					(15, 'order_list', 'order_list', '" . $redtemplate->getInstallSectionTemplate('order_list') . "', 1),
					(16, 'order_detail', 'order_detail', '" . $redtemplate->getInstallSectionTemplate('order_detail') . "', 1),
					(23, 'related_products', 'related_product', '" . $redtemplate->getInstallSectionTemplate('related_products') . "', 1),
					(17, 'order_receipt', 'order_receipt', '" . $redtemplate->getInstallSectionTemplate('order_receipt') . "', 1),
					(18, 'manufacturer_detail', 'manufacturer_detail', '" . $redtemplate->getInstallSectionTemplate('manufacturer_detail') . "', 1),
					(22, 'frontpage_category', 'frontpage_category', '" . $redtemplate->getInstallSectionTemplate('frontpage_category') . "', 1),
					(24, 'add_to_cart1', 'add_to_cart', '" . $redtemplate->getInstallSectionTemplate('add_to_cart1') . "', 1),
					(25, 'add_to_cart2', 'add_to_cart', '" . $redtemplate->getInstallSectionTemplate('add_to_cart2') . "', 1),
					(27, 'accessory', 'accessory_template', '" . $redtemplate->getInstallSectionTemplate('accessory') . "', 1),
					(28, 'attributes', 'attribute_template', '" . $redtemplate->getInstallSectionTemplate('attributes') . "', 1),
					(100,'my_account_template','account_template','" . $redtemplate->getInstallSectionTemplate('my_account_template') . "',1),
					(101, 'catalog', 'catalog', '" . $redtemplate->getInstallSectionTemplate('catalog') . "', 1),
					(102, 'catalog_sample', 'product_sample', '" . $redtemplate->getInstallSectionTemplate('catalog_sample') . "', 1),
					(103, 'wishlist_list','wishlist_template','" . $redtemplate->getInstallSectionTemplate('wishlist_list') . "',1),
					(105,'wishlist_mail','wishlist_mail_template','" . $redtemplate->getInstallSectionTemplate('wishlist_mail') . "',1),
					(115,'wrapper','wrapper_template','" . $redtemplate->getInstallSectionTemplate('wrapper') . "',1),
					(125,'giftcard_listing','giftcard_list','" . $redtemplate->getInstallSectionTemplate('giftcard_listing') . "',1),
					(135,'giftcard','giftcard','" . $redtemplate->getInstallSectionTemplate('giftcard') . "',1),
					(110, 'ask_question', 'ask_question_template', '" . $redtemplate->getInstallSectionTemplate('ask_question') . "', 1),
					(111, 'ajax_cart_box', 'ajax_cart_box', '" . $redtemplate->getInstallSectionTemplate('ajax_cart_box') . "', 1),
					(112, 'ajax_cart_detail_box', 'ajax_cart_detail_box', '" . $redtemplate->getInstallSectionTemplate('ajax_cart_detail_box') . "', 1),
					(200, 'shipping_pdf', 'shipping_pdf', '" . $redtemplate->getInstallSectionTemplate('shipping_pdf') . "', 1),
					(251, 'order_print', 'order_print', '" . $redtemplate->getInstallSectionTemplate('order_print') . "', 1),
					(252, 'clicktell_sms_message', 'clicktell_sms_message', '" . $redtemplate->getInstallSectionTemplate('clicktell_sms_message') . "', 1),
					(260, 'redproductfinder', 'redproductfinder', '" . $redtemplate->getInstallSectionTemplate('redproductfinder') . "', 1),
					(265, 'quotation_detail', 'quotation_detail', '" . $redtemplate->getInstallSectionTemplate('quotation_detail') . "', 1),
					(334, 'newsletter_products', 'newsletter_product', '" . $redtemplate->getInstallSectionTemplate('newsletter_products') . "', 1),
					(280, 'catalogue_cart', 'catalogue_cart', '" . $redtemplate->getInstallSectionTemplate('catalogue_cart') . "', 1),
					(281, 'catalogue_order_detail', 'catalogue_order_detail', '" . $redtemplate->getInstallSectionTemplate('catalogue_order_detail') . "', 1),
					(282, 'catalogue_order_receipt', 'catalogue_order_receipt', '" . $redtemplate->getInstallSectionTemplate('catalogue_order_receipt') . "', 1),
					(289, 'empty_cart', 'empty_cart', '" . $redtemplate->getInstallSectionTemplate('empty_cart') . "', 1),
					(320, 'compare_product', 'compare_product', '" . $redtemplate->getInstallSectionTemplate('compare_product') . "', 1),
					(353, 'payment_method', 'redshop_payment', '" . $redtemplate->getInstallSectionTemplate('payment_method') . "', 1),
					(354, 'shipping_method', 'redshop_shipping', '" . $redtemplate->getInstallSectionTemplate('shipping_method') . "', 1),
					(355, 'shipping_box', 'shippingbox', '" . $redtemplate->getInstallSectionTemplate('shippingbox') . "',1),
					(356, 'category_product_template', 'categoryproduct', '" . $redtemplate->getInstallSectionTemplate('category_product_template') . "', 1),
					(357, 'change_cart_attribute_template', 'change_cart_attribute', '" . $redtemplate->getInstallSectionTemplate('change_cart_attribute_template') . "', 1),
					(358, 'onestep_checkout', 'onestep_checkout', '" . $redtemplate->getInstallSectionTemplate('onestep_checkout') . "', 1),
					(359, 'attributes_listing1', 'attributewithcart_template', '" . $redtemplate->getInstallSectionTemplate('attributes_listing1') . "', 1),
					(360, 'checkout', 'checkout', '" . $redtemplate->getInstallSectionTemplate('checkout') . "',1),
					(371, 'product_content', 'product_content_template', '" . $redtemplate->getInstallSectionTemplate('product_content') . "',1),
					(372, 'quotation_cart_template', 'quotation_cart', '" . $redtemplate->getInstallSectionTemplate('quotation_cart_template') . "',1),
					(370, 'quotation_request_template', 'quotation_request', '" . $redtemplate->getInstallSectionTemplate('quotation_request_template') . "',1),
					(450, 'billing_template', 'billing_template', '" . $redtemplate->getInstallSectionTemplate('billing_template') . "',1),
					(451, 'shipping_template', 'shipping_template', '" . $redtemplate->getInstallSectionTemplate('shipping_template') . "',1),
					(460, 'private_billing_template', 'private_billing_template', '" . $redtemplate->getInstallSectionTemplate('private_billing_template') . "',1),
					(461, 'company_billing_template', 'company_billing_template', '" . $redtemplate->getInstallSectionTemplate('company_billing_template') . "',1),
					(550, 'stock_note', 'stock_note', '" . $redtemplate->getInstallSectionTemplate('stock_note') . "',1),
					(551, 'login', 'login', '" . $redtemplate->getInstallSectionTemplate('login') . "',1)";
		$this->_db->setQuery($q);

		return $this->_db->execute();
	}

	/**
	 * Method for convert templates from DB to files.
	 *
	 * @return  boolean
	 *
	 * @since   2.0.4
	 */
	public function processTemplateFiles()
	{
		$db        = $this->_db;
		$query     = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_template'));
		$templates = $db->setQuery($query)->loadObjectList();

		foreach ($templates as $template)
		{
			$templateName            = $template->template_name;
			$template->template_name = strtolower($template->template_name);
			$template->template_name = str_replace(" ", "_", $template->template_name);
			$templateFile            = RedshopHelperTemplate::getTemplateFilePath($template->template_section, $template->template_name, true);

			if (!is_file($templateFile))
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
	 * Method for update menu item id if necessary.
	 *
	 * @return  boolean
	 *
	 * @since   2.0.4
	 */
	public function processUpdateMenuItem()
	{
		$db = $this->_db;

		// For Blank component id in menu table-admin menu error solution - Get redSHOP extension id from the table
		$query = $db->getQuery(true)
			->select('extension_id')
			->from($db->qn('#__extensions'))
			->where($db->qn('name') . ' LIKE ' . $db->quote('%redshop'))
			->where($db->qn('element') . ' = ' . $db->quote('com_redshop'))
			->where($db->qn('type') . ' = ' . $db->quote('component'));

		$extensionId = $db->setQuery($query)->loadResult();

		// Check for component menu item entry
		$query->clear()
			->select('id,component_id')
			->from($db->qn('#__menu'))
			->where($db->qn('menutype') . ' = ' . $db->quote('main'))
			->where($db->qn('path') . ' LIKE ' . $db->quote('%redshop'))
			->where($db->qn('type') . ' = ' . $db->quote('component'));

		$menuItem = $db->setQuery($query)->loadObject();

		// If component Entry found and component_id is same as extension id - no need to update menu item
		$isUpdate = ($menuItem && $menuItem->component_id == $extensionId) ? false : true;

		if (!$isUpdate)
		{
			return true;
		}

		$query->clear()
			->update($db->qn('#__menu'))
			->set($db->qn('component_id') . ' = ' . (int) $extensionId)
			->where($db->qn('menutype') . ' = ' . $db->quote('main'))
			->where($db->qn('path') . ' LIKE ' . $db->quote('%redshop'))
			->where($db->qn('type') . ' = ' . $db->quote('component'));

		// Set the query and execute the update.
		return $db->setQuery($query)->execute();
	}

	/**
	 * Method for integrate with com_sh404sef extension if necessary.
	 *
	 * @return  boolean
	 *
	 * @since   2.0.4
	 */
	public function processIntegrateSh404sef()
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

		return JFile::copy($redShopSefFolder . '/sh404sef/language/com_redshop.php', $sh404SEFAdmin . '/language/plugins/com_redshop.php');
	}

	/**
	 * Method for check database structure when update.
	 *
	 * @return  boolean
	 *
	 * @since   2.0.4
	 */
	public function processUpdateCheckDatabase()
	{
		$installDatabase = new RedshopInstallDatabase;
		$installDatabase->install();

		return true;
	}

	/**
	 * Method for update override template when update.
	 *
	 * @return  boolean
	 *
	 * @since   2.0.4
	 */
	public function processUpdateOverrideTemplate()
	{
		$dir                  = JPATH_SITE . "/templates/";
		$codeDir              = JPATH_SITE . "/code/";
		$files                = JFolder::folders($dir);
		$templates            = array();
		$adminHelpers         = array();
		$adminTemplateHelpers = array();

		if (JFolder::exists($codeDir))
		{
			$codeFiles = JFolder::folders($codeDir);

			foreach ($codeFiles as $key => $value)
			{
				if (JFolder::exists($codeDir . 'administrator/components'))
				{
					$templates[$codeDir . 'administrator/components'] = JFolder::folders($codeDir . 'administrator/components');
				}

				if (JFolder::exists($codeDir . 'administrator'))
				{
					$templates[$codeDir . 'administrator'] = JFolder::folders($codeDir . 'administrator');
				}

				if (JFolder::exists($codeDir . 'components'))
				{
					$templates[$codeDir . 'components'] = JFolder::folders($codeDir . 'components');
				}

				if (JFolder::exists($codeDir))
				{
					$templates[$codeDir] = JFolder::folders($codeDir);
				}

				if (JFolder::exists($codeDir . 'com_redshop/helpers'))
				{
					$adminHelpers[$codeDir . 'com_redshop/helpers'] = JFolder::files($codeDir . 'com_redshop/helpers');
				}
			}
		}

		foreach ($files as $key => $value)
		{
			if (!JFile::exists($dir . $value))
			{
				$templates[$dir . $value] = JFolder::folders($dir . $value);
			}
		}

		$override   = array();
		$jsOverride = array();

		foreach ($templates as $key => $value)
		{
			foreach ($value as $name)
			{
				if (!JFile::exists($key . '/' . $name))
				{
					if (JFolder::exists($key . '/com_redshop'))
					{
						$override[$key . '/com_redshop'] = JFolder::folders($key . '/com_redshop');
					}

					if (JFolder::exists($key . '/html'))
					{
						$override[$key . '/html'] = JFolder::folders($key . '/html');
					}

					if (JFolder::exists($key . '/js/com_redshop'))
					{
						$jsOverride[$key . '/js/com_redshop'] = JFolder::files($key . '/js/com_redshop');
					}

					if (JFolder::exists($key . '/code/com_redshop'))
					{
						$override[$key . '/code/com_redshop'] = JFolder::folders($key . '/code/com_redshop');
					}

					if (JFolder::exists($key . '/code/components/com_redshop'))
					{
						$override[$key . '/code/components/com_redshop'] = JFolder::folders($key . '/code/components/com_redshop');
					}

					if (JFolder::exists($key . '/code/com_redshop/helpers'))
					{
						$adminTemplateHelpers[$key] = JFolder::files($key . '/code/com_redshop/helpers');
					}
				}
			}
		}

		$overrideFolders       = array();
		$overrideLayoutFolders = array();
		$overrideLayoutFiles   = array();

		foreach ($override as $key => $value)
		{
			foreach ($value as $name)
			{
				if ($name == 'layouts')
				{
					$overrideLayoutFolders[$key . '/' . $name] = JFolder::folders($key . '/' . $name);
				}
				elseif (!JFile::exists($key . '/' . $name) && $name != 'layouts' && $name == 'com_redshop' || strpos($name, 'mod_redshop') !== false)
				{
					// Read all files and folders in parent folder
					$overrideFolders[$key . '/' . $name] = array_diff(scandir($key . '/' . $name), array('.', '..'));
				}
			}
		}

		$overrideFiles = array();

		foreach ($overrideFolders as $key => $value)
		{
			foreach ($value as $name)
			{
				if (!JFile::exists($key . '/' . $name))
				{
					$overrideFiles[$key . '/' . $name] = JFolder::files($key . '/' . $name);
				}
				else
				{
					$overrideFiles[$key] = JFolder::files($key);
				}
			}
		}

		foreach ($overrideLayoutFolders as $key => $value)
		{
			foreach ($value as $name)
			{
				if (!JFile::exists($key . '/' . $name) && $name == 'com_redshop')
				{
					$overrideLayoutFiles[$key . '/' . $name] = JFolder::files($key . '/' . $name);
				}
			}
		}

		if (!empty($overrideLayoutFiles))
		{
			foreach ($overrideLayoutFiles as $key => $value)
			{
				foreach ($value as $name)
				{
					if (!JFile::exists($key . '/' . $name))
					{
						$overrideFiles[$key . '/' . $name] = JFolder::files($key . '/' . $name);
					}
				}
			}
		}

		$replaceString = array(
			'new quotationHelper()'                                                        => 'quotationHelper::getInstance()',
			'new order_functions()'                                                        => 'order_functions::getInstance()',
			'new Redconfiguration()'                                                       => 'Redconfiguration::getInstance()',
			'new Redconfiguration'                                                         => 'Redconfiguration::getInstance()',
			'new Redtemplate()'                                                            => 'Redtemplate::getInstance()',
			'new Redtemplate'                                                              => 'Redtemplate::getInstance()',
			'new extra_field()'                                                            => 'extra_field::getInstance()',
			'new rsstockroomhelper()'                                                      => 'rsstockroomhelper::getInstance()',
			'new rsstockroomhelper'                                                        => 'rsstockroomhelper::getInstance()',
			'new shipping()'                                                               => 'shipping::getInstance()',
			'new CurrencyHelper()'                                                         => 'CurrencyHelper::getInstance()',
			'new economic()'                                                               => 'economic::getInstance()',
			'new rsUserhelper()'                                                           => 'rsUserHelper::getInstance()',
			'new rsUserhelper'                                                             => 'rsUserHelper::getInstance()',
			'GoogleAnalytics'                                                              => 'RedshopHelperGoogleanalytics',
			'new quotationHelper'                                                          => 'quotationHelper::getInstance()',
			'new order_functions'                                                          => 'order_functions::getInstance()',
			'new extra_field'                                                              => 'extra_field::getInstance()',
			'new shipping'                                                                 => 'shipping::getInstance()',
			'new CurrencyHelper'                                                           => 'CurrencyHelper::getInstance()',
			'new economic'                                                                 => 'economic::getInstance()',
			'RedshopConfig::scriptDeclaration();'                                          => '',
			'$redConfiguration'                                                            => '$Redconfiguration',
			'require_once JPATH_SITE . \'/components/com_redshop/helpers/redshop.js.php\'' => '',
		);

		$data = Redshop::getConfig()->toArray();
		$temp = JFactory::getApplication()->getUserState('com_redshop.config.global.data');

		if (!empty($temp))
		{
			$data = array_merge($data, $temp);
		}

		$data['BACKWARD_COMPATIBLE_PHP'] = 0;
		$data['BACKWARD_COMPATIBLE_JS']  = 0;
		$config                          = Redshop::getConfig();

		if (!empty($overrideFiles))
		{
			foreach ($overrideFiles as $path => $files)
			{
				foreach ($files as $file)
				{
					$content = file_get_contents($path . '/' . $file);

					foreach ($replaceString as $old => $new)
					{
						if (strstr($content, $old))
						{
							$content = str_replace($old, $new, $content);
							JFile::write($path . '/' . $file, $content);
						}
					}
				}
			}

			// Check site used MVC && Templates Override
			$data['BACKWARD_COMPATIBLE_PHP'] = 1;
		}

		if (!empty($jsOverride))
		{
			// Check site used JS Override
			$data['BACKWARD_COMPATIBLE_JS'] = 1;
		}

		JFactory::getApplication()->setUserState('com_redshop.config.global.data', $data);
		$config->save(new Registry($data));

		$replaceAdminHelper = array(
			'adminorder.php'         => 'order_functions.php',
			'admincategory.php'      => 'product_category.php',
			'adminquotation.php'     => 'quotationhelper.php',
			'adminaccess_level.php'  => 'redaccesslevel.php',
			'adminconfiguration.php' => 'redconfiguration.php',
			'adminmedia.php'         => 'redmediahelper.php',
			'adminimages.php'        => 'redshophelperimages.php',
			'adminmail.php'          => 'redshopmail.php',
			'adminupdate.php'        => 'redshopupdate.php',
			'admintemplate.php'      => 'redtemplate.php',
			'adminstockroom.php'     => 'rsstockroom.php',
			'adminshopper.php'       => 'shoppergroup.php'
		);

		$replaceSiteHelper = array(
			'currency.php'         => 'currencyhelper.php',
			'extra_field.php'      => 'extrafield.php',
			'google_analytics.php' => 'googleanalytics.php',
			'product.php'          => 'producthelper.php',
			'helper.php'           => 'redhelper.php',
			'cart.php'             => 'rscarthelper.php',
			'user.php'             => 'rsuserhelper.php'
		);

		if (!empty($adminHelpers))
		{
			foreach ($adminHelpers as $path => $files)
			{
				foreach ($replaceAdminHelper as $old => $new)
				{
					if (JFile::exists($path . '/' . $old))
					{
						if (!JFolder::exists($codeDir . 'administrator/components/com_redshop/helpers'))
						{
							JFolder::create($codeDir . 'administrator/components/com_redshop/helpers');
						}

						$src  = $codeDir . 'com_redshop/helpers/' . $old;
						$dest = $codeDir . 'administrator/components/com_redshop/helpers/' . $new;
						JFile::move($src, $dest);
					}
				}

				foreach ($replaceSiteHelper as $old => $new)
				{
					if (JFile::exists($path . '/' . $old))
					{
						if (!JFolder::exists($codeDir . 'components/com_redshop/helpers'))
						{
							JFolder::create($codeDir . 'components/com_redshop/helpers');
						}

						$src  = $codeDir . 'com_redshop/helpers/' . $old;
						$dest = $codeDir . 'components/com_redshop/helpers/' . $new;
						JFile::move($src, $dest);
					}
				}
			}
		}

		if (!empty($adminTemplateHelpers))
		{
			foreach ($adminTemplateHelpers as $path => $files)
			{
				foreach ($replaceAdminHelper as $old => $new)
				{
					if (JFile::exists($path . '/code/com_redshop/helpers/' . $old))
					{
						if (!JFolder::exists($path . '/code/administrator/components/com_redshop/helpers'))
						{
							JFolder::create($path . '/code/administrator/components/com_redshop/helpers');
						}

						$src  = $path . '/code/com_redshop/helpers/' . $old;
						$dest = $path . '/code/administrator/components/com_redshop/helpers/' . $new;
						JFile::move($src, $dest);
					}
				}

				foreach ($replaceSiteHelper as $old => $new)
				{
					if (JFile::exists($path . '/code/com_redshop/helpers/' . $old))
					{
						if (!JFolder::exists($path . '/code/components/com_redshop/helpers'))
						{
							JFolder::create($path . '/code/components/com_redshop/helpers');
						}

						$src  = $path . '/code/com_redshop/helpers/' . $old;
						$dest = $path . '/code/components/com_redshop/helpers/' . $new;
						JFile::move($src, $dest);
					}
				}
			}
		}

		return true;
	}

	/**
	 * Method for check database structure when update.
	 *
	 * @return  boolean
	 *
	 * @since   2.0.4
	 */
	public function processUpdateCleanOldFiles()
	{
		$folders = array();
		$files   = array();

		// Clean up old Updates feature
		$folders[] = JPATH_ADMINISTRATOR . '/components/com_redshop/views/update';
		$folders[] = JPATH_ADMINISTRATOR . '/components/com_redshop/extras/sh404sef/sef_ext';
		$folders[] = JPATH_ADMINISTRATOR . '/components/com_redshop/extras/sh404sef/meta_ext';
		$folders[] = JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/barcode';
		$folders[] = JPATH_ADMINISTRATOR . '/components/com_redshop/views/tax_group_detail';
		$folders[] = JPATH_ADMINISTRATOR . '/components/com_redshop/views/accessmanager';
		$folders[] = JPATH_ADMINISTRATOR . '/components/com_redshop/views/accessmanager_detail';

		$files[] = JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/update.php';
		$files[] = JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/accessmanager.php';
		$files[] = JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/accessmanager_detail.php';
		$files[] = JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshopupdate.php';
		$files[] = JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redaccesslevel.php';
		$files[] = JPATH_ADMINISTRATOR . '/components/com_redshop/models/update.php';
		$files[] = JPATH_ADMINISTRATOR . '/components/com_redshop/models/accessmanager.php';
		$files[] = JPATH_ADMINISTRATOR . '/components/com_redshop/models/accessmanager_detail.php';
		$files[] = JPATH_ADMINISTRATOR . '/components/com_redshop/tables/accessmanager_detail.php';

		// Tax group
		$files[] = JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/tax_group_detail.php';
		$files[] = JPATH_ADMINISTRATOR . '/components/com_redshop/models/tax_group_detail.php';
		$files[] = JPATH_ADMINISTRATOR . '/components/com_redshop/tables/tax_group_detail.php';
		$files[] = JPATH_ADMINISTRATOR . '/components/com_redshop/views/tax_group/tmpl/default.php';

		// Remove old Category Detail.
		if (version_compare(RedshopHelperJoomla::getManifestValue('version'), '2.0.5', '<='))
		{
			array_push(
				$files,
				JPATH_ADMINISTRATOR . '/component/admin/controllers/category_detail.php',
				JPATH_ADMINISTRATOR . '/component/admin/models/category_detail.php',
				JPATH_ADMINISTRATOR . '/component/admin/controllers/fields_detail.php',
				JPATH_ADMINISTRATOR . '/component/admin/models/fields_detail.php',
				JPATH_ADMINISTRATOR . '/component/admin/tables/fields_detail.php'
			);

			array_push(
				$folders,
				JPATH_ADMINISTRATOR . '/component/admin/views/category_detail',
				JPATH_LIBRARIES . '/redshop/economic',
				JPATH_ADMINISTRATOR . '/component/admin/views/fields_detail'
			);
		}

		// Remove old Supplier stuff since Refactor.
		if (version_compare(RedshopHelperJoomla::getManifestValue('version'), '2.0.0.6', '<='))
		{
			array_push(
				$files,
				JPATH_ADMINISTRATOR . '/component/admin/controllers/supplier_detail.php',
				JPATH_ADMINISTRATOR . '/component/admin/controllers/tax.php',
				JPATH_ADMINISTRATOR . '/component/admin/controllers/tax_detail.php',
				JPATH_ADMINISTRATOR . '/component/admin/controllers/mass_discount_detail.php',
				JPATH_ADMINISTRATOR . '/component/admin/models/supplier_detail.php',
				JPATH_ADMINISTRATOR . '/component/admin/models/mass_discount_detail.php',
				JPATH_ADMINISTRATOR . '/component/admin/models/tax.php',
				JPATH_ADMINISTRATOR . '/component/admin/models/tax_detail.php',
				JPATH_ADMINISTRATOR . '/component/admin/tables/supplier_detail.php',
				JPATH_ADMINISTRATOR . '/component/admin/tables/mass_discount_detail.php',
				JPATH_ADMINISTRATOR . '/component/admin/tables/tax_detail.php',
				JPATH_ADMINISTRATOR . '/component/admin/views/supplier/tmpl/default.php',
				JPATH_ADMINISTRATOR . '/component/admin/views/mass_discount/tmpl/default.php',
				JPATH_SITE . '/media/com_redshop/css/media.css',
				JPATH_SITE . '/media/com_redshop/css/media-uncompressed.css',
				JPATH_SITE . '/media/com_redshop/js/media.js',
				JPATH_SITE . '/media/com_redshop/js/media-uncompressed.js',
				JPATH_ADMINISTRATOR . '/component/admin/views/order_detail/view.tcpdf.php',
				JPATH_LIBRARIES . '/redshop/helper/tcpdf.php'
			);

			array_push(
				$folders,
				JPATH_ADMINISTRATOR . '/component/admin/views/supplier_detail',
				JPATH_ADMINISTRATOR . '/component/admin/views/tax',
				JPATH_ADMINISTRATOR . '/component/admin/views/mass_discount_detail',
				JPATH_ADMINISTRATOR . '/component/admin/views/tax_detail'
			);
		}

		if (version_compare(RedshopHelperJoomla::getManifestValue('version'), '2.0.0.4', '<='))
		{
			array_push(
				$files,
				JPATH_ADMINISTRATOR . '/component/admin/controllers/question_detail.php',
				JPATH_ADMINISTRATOR . '/component/admin/models/question_detail.php',
				JPATH_ADMINISTRATOR . '/component/admin/tables/question_detail.php',
				JPATH_ADMINISTRATOR . '/component/admin/views/question/tmpl/default.php'
			);

			array_push(
				$folders,
				JPATH_ADMINISTRATOR . '/component/admin/views/question_detail'
			);
		}

		if (version_compare(RedshopHelperJoomla::getManifestValue('version'), '2.0', '<='))
		{
			array_push(
				$folders,
				JPATH_LIBRARIES . '/redshop/config',
				JPATH_ADMINISTRATOR . '/components/com_redshop/views/answer',
				JPATH_ADMINISTRATOR . '/components/com_redshop/views/answer_detail'
			);

			array_push(
				$files,
				JPATH_SITE . '/components/com_redshop/helpers/helper.php',
				JPATH_SITE . '/components/com_redshop/helpers/currency.php',
				JPATH_SITE . '/components/com_redshop/helpers/product.php',
				JPATH_SITE . '/components/com_redshop/helpers/cart.php',
				JPATH_SITE . '/components/com_redshop/helpers/user.php',
				JPATH_SITE . '/components/com_redshop/views/search/tmpl/default.xml',
				JPATH_SITE . '/components/com_redshop/helpers/extra_field.php',
				JPATH_SITE . '/components/com_redshop/helpers/google_analytics.php',
				JPATH_SITE . '/components/com_redshop/helpers/googleanalytics.php',
				JPATH_SITE . '/components/com_redshop/helpers/zip.php',
				JPATH_SITE . '/components/com_redshop/helpers/cron.php',
				JPATH_SITE . '/components/com_redshop/helpers/redshop.js.php',
				JPATH_SITE . '/components/com_redshop/helpers/zipfile.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/answer.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/answer_detail.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/models/answer.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/models/answer_detail.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/access_level.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/category.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/configuration.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/images.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/mail.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/media.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/order.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/quotation.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/stockroom.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/template.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/update.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/shopper.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/xmlcron.php',
				JPATH_LIBRARIES . '/redshop/form/fields/stockroom.php'
			);

			// Remove barcode view for backend
			array_push(
				$folders,
				JPATH_ADMINISTRATOR . '/components/com_redshop/views/barcode'
			);
		}

		if (version_compare(RedshopHelperJoomla::getManifestValue('version'), '1.5.0.5.3', '<='))
		{
			array_push(
				$files,
				JPATH_SITE . '/components/com_redshop/assets/download/product/.htaccess'
			);
		}

		if (version_compare(RedshopHelperJoomla::getManifestValue('version'), '1.5.0.4.3', '<='))
		{
			array_push(
				$files,
				JPATH_ADMINISTRATOR . '/components/com_redshop/tables/navigator_detail.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/views/product_detail/tmpl/default_product_dropdown.php'
			);
		}

		if (version_compare(RedshopHelperJoomla::getManifestValue('version'), '1.5.0.4.2', '<='))
		{
			array_push(
				$folders,
				JPATH_ADMINISTRATOR . '/components/com_redshop/layouts/system'
			);

			array_push(
				$files,
				JPATH_SITE . '/components/com_redshop/views/category/tmpl/searchletter.php'
			);
		}

		if (version_compare(RedshopHelperJoomla::getManifestValue('version'), '1.5.0.1', '<='))
		{
			array_push(
				$folders,
				JPATH_SITE . '/components/com_redshop/assets/js',
				JPATH_SITE . '/components/com_redshop/assets/css',
				JPATH_SITE . '/components/com_redshop/helpers/fonts',
				JPATH_SITE . '/components/com_redshop/helpers/tcpdf',
				JPATH_SITE . '/components/com_redshop/views/epayrelay',
				JPATH_SITE . '/components/com_redshop/views/password',
				JPATH_ADMINISTRATOR . '/components/com_redshop/models/adapters',
				JPATH_ADMINISTRATOR . '/components/com_redshop/views/container',
				JPATH_ADMINISTRATOR . '/components/com_redshop/views/container_detail',
				JPATH_ADMINISTRATOR . '/components/com_redshop/views/customprint',
				JPATH_ADMINISTRATOR . '/components/com_redshop/views/delivery',
				JPATH_ADMINISTRATOR . '/components/com_redshop/views/payment',
				JPATH_ADMINISTRATOR . '/components/com_redshop/views/payment_detail',
				JPATH_ADMINISTRATOR . '/components/com_redshop/views/product_container'
			);

			array_push(
				$files,
				JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/container.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/container_detail.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/customprint.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/delivery.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/order_container.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/payment.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/payment_detail.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/product_container.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/subinstall.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/models/container.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/models/container_detail.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/models/order_container.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/models/payment.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/models/payment_detail.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/models/product_container.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/tables/container_detail.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/tables/payment_detail.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/views/accessmanager/tmpl/noaccess.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/views/order/tmpl/multiprint_order.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/views/order/tmpl/previewlog.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/views/stockroom_detail/tmpl/default_product.php',
				JPATH_ADMINISTRATOR . '/components/com_redshop/assets/js/select_sort.js',
				JPATH_ADMINISTRATOR . '/components/com_redshop/assets/js/related.js',
				JPATH_ADMINISTRATOR . '/components/com_redshop/assets/js/container_search.js',
				JPATH_ADMINISTRATOR . '/components/com_redshop/assets/js/mootools.js',
				JPATH_ADMINISTRATOR . '/components/com_redshop/assets/images/redshop_white.png',
				JPATH_ADMINISTRATOR . '/components/com_redshop/assets/images/j_arrow.png',
				JPATH_ADMINISTRATOR . '/components/com_redshop/assets/images/j_arrow_down.png',
				JPATH_ADMINISTRATOR . '/components/com_redshop/assets/images/ui-icons_222222_256x240.png',
				JPATH_ADMINISTRATOR . '/components/com_redshop/assets/images/ui-icons_228ef1_256x240.png',
				JPATH_SITE . '/components/com_redshop/controllers/password.php',
				JPATH_SITE . '/components/com_redshop/controllers/price_filter.php',
				JPATH_SITE . '/components/com_redshop/helpers/class.img2thumb.php',
				JPATH_SITE . '/components/com_redshop/helpers/graph.php',
				JPATH_SITE . '/components/com_redshop/helpers/pagination.php',
				JPATH_SITE . '/components/com_redshop/helpers/thumb.php',
				JPATH_SITE . '/components/com_redshop/models/password.php',
				JPATH_SITE . '/components/com_redshop/views/price_filter/view.html.php',
				JPATH_SITE . '/components/com_redshop/views/product/tmpl/default_askquestion.php',
				JPATH_LIBRARIES . '/redshop/form/fields/rstext.php'
			);
		}

		// Delete these unused folders
		$this->deleteFolders($folders);

		// Delete these unused files
		$this->deleteFiles($files);

		return true;
	}

	/**
	 * Method to update schema table if necessary. From redshop 1.3.3.1
	 *
	 * @return  mixed
	 *
	 * @since   2.0.4
	 */
	public function processUpdateDatabaseSchema()
	{
		$db          = $this->_db;
		$query       = $db->getQuery(true)
			->select($db->qn('extension_id'))
			->from($db->qn('#__extensions'))
			->where($db->qn('element') . ' = ' . $db->quote('com_redshop'))
			->where($db->qn('type') . ' = ' . $db->quote('component'));
		$componentId = $db->setQuery($query)->loadResult();

		// Skip if there are no redshop install
		if (!$componentId)
		{
			return false;
		}

		$query->clear()
			->select($db->qn('version_id'))
			->from($db->qn('#__schemas'))
			->where($db->qn('extension_id') . ' = ' . $componentId);
		$result = $db->setQuery($query)->loadResult();

		// Skip if there are already schema
		if ($result)
		{
			return $result;
		}

		$query->clear()
			->insert($db->qn('#__schemas'))
			->columns($db->qn(array('extension_id', 'version_id')))
			->values($componentId . ',' . $db->quote('1.1.10'));

		if ($db->setQuery($query)->execute())
		{
			return '1.1.10';
		}

		return false;
	}

	/**
	 * Method for rename image files name to correct format.
	 *
	 * @return  mixed
	 *
	 * @since   2.0.4
	 */
	public function processUpdateImageFileNames()
	{
		$db = JFactory::getDbo();

		/** Update DB */
		$fields = array(
			$db->qn('product_full_image') . ' = REPLACE(' . $db->qn('product_full_image') . ", '%20', '-')",
			$db->qn('product_full_image') . ' = REPLACE(' . $db->qn('product_full_image') . ", ' ', '-')",
			$db->qn('product_thumb_image') . ' = REPLACE(' . $db->qn('product_thumb_image') . ", '%20', '-')",
			$db->qn('product_thumb_image') . ' = REPLACE(' . $db->qn('product_thumb_image') . ", ' ', '-')"
		);

		$query = $db->getQuery(true)
			->update($db->qn('#__redshop_product'))
			->set($fields);
		$db->setQuery($query)->execute();

		/** Update Image Name */
		$path  = JPATH_SITE . '/components/com_redshop/assets/images/product/';
		$files = JFolder::files($path);
		$this->changeImageFileName($files, $path);

		$path  = JPATH_SITE . '/components/com_redshop/assets/images/product/thumb/';
		$files = JFolder::files($path);
		$this->changeImageFileName($files, $path);

		return true;
	}

	/**
	 * Method to update new structure for Category
	 *
	 * @return  mixed
	 *
	 * @since   2.0.5
	 */
	public function processUpdateCategory()
	{
		$db = JFactory::getDbo();
		$check = RedshopHelperCategory::getRootId();

		if (!empty($check))
		{
			return true;
		}

		$root = new stdClass;
		$root->name = 'ROOT';
		$root->parent_id = 0;
		$root->level = 0;
		$root->lft = 0;
		$root->rgt = 1;
		$result = $db->insertObject('#__redshop_category', $root);
		$rootId = $db->insertid();

		$query = $db->getQuery(true)
			->select('c.*')
			->select($db->qn('cx.category_parent_id', 'parent_id'))
			->from($db->qn('#__redshop_category', 'c'))
			->leftJoin($db->qn('#__redshop_category_xref', 'cx') . ' ON ' . $db->qn('c.id') . ' = ' . $db->qn('cx.category_child_id'));
		$categories = $db->setQuery($query)->loadObjectList();

		foreach ($categories as $key => $category)
		{
			if ($category->name == 'ROOT')
			{
				continue;
			}

			$parentId = ($category->parent_id == 0) ? $rootId : $category->parent_id;
			$alias = JFilterOutput::stringURLUnicodeSlug($category->name);

			$fields = array(
					$db->qn('parent_id') . ' = ' . $db->q((int) $parentId),
					$db->qn('alias') . ' = ' . $db->q($alias)
				);
			$conditions = array(
				$db->qn('id') . ' = ' . $db->q((int) $category->id)
			);

			$query = $db->getQuery(true)
				->update($db->qn('#__redshop_category'))
				->set($fields)
				->where($conditions);

			$db->setQuery($query)->execute();
		}

		if ($this->processRebuildCategory($rootId))
		{
			$this->processDeleteCategoryXrefTable();
		}

		return true;
	}

	/**
	 * Method to update new structure for Category
	 *
	 * @param   int  $rootId  Root ID
	 *
	 * @return  mixed
	 *
	 * @since   2.0.5
	 */
	public function processRebuildCategory($rootId)
	{
		$table = RedshopTable::getInstance('Category', 'RedshopTable');

		return $table->rebuild($rootId);
	}

	/**
	 * Method to update new structure for Category
	 *
	 * @return  mixed
	 *
	 * @since   2.0.5
	 */
	public function processDeleteCategoryXrefTable()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)->dropTable('#__redshop_category_xref');

		return $db->setQuery($query);
	}

	/**
	 * Delete folder recursively
	 *
	 * @param   string $folder Folder to delete
	 *
	 * @return  boolean
	 *
	 * @since   2.0.0.2
	 */
	protected function deleteFolder($folder)
	{
		if (!is_dir($folder))
		{
			return true;
		}

		$files = glob($folder . '/*');

		foreach ($files as $file)
		{
			if (is_dir($file))
			{
				if (!$this->deleteFolder($file))
				{
					return false;
				}

				continue;
			}

			if (!unlink($file))
			{
				return false;
			}
		}

		return rmdir($folder);
	}

	/**
	 * Delete folders recursively.
	 *
	 * @param   array $folders Folders
	 *
	 * @return  boolean
	 *
	 * @since   2.0.0.2
	 */
	protected function deleteFolders(array $folders)
	{
		foreach ($folders as $folder)
		{
			if (!$this->deleteFolder($folder))
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Delete files recursively.
	 *
	 * @param   array  $files  Files
	 *
	 * @return  boolean
	 *
	 * @since   2.0.0.2
	 */
	protected function deleteFiles(array $files)
	{
		foreach ($files as $file)
		{
			if (file_exists($file))
			{
				unlink($file);
			}
		}

		return true;
	}

	/**
	 * Change images file name
	 *
	 * @param   array   $files  List files in image folder
	 * @param   string  $path   Path to folder
	 *
	 * @return  void
	 */
	protected function changeImageFileName(&$files, &$path)
	{
		if (empty($files))
		{
			return;
		}

		for ($i = 0; $i < count($files); ++$i)
		{
			$fileName = str_replace(array('%20', ' '), '-', $files[$i]);

			JFile::move($path . $files[$i], $path . $fileName);
		}
	}
}
