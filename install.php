<?php
/**
 * @package    RedSHOP.Installer
 *
 * @copyright  Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Script file of redSHOP component
 *
 * @package  RedSHOP.Installer
 *
 * @since    1.2
 */
class Com_RedshopInstallerScript
{
	/**
	 * Status of the installation
	 *
	 * @var  [type]
	 */
	public $status = null;

	public $installer = null;

	/**
	 * Method to install the component
	 *
	 * @param   object  $parent  class calling this method
	 *
	 * @return void
	 */
	public function install($parent)
	{
		// Install extensions
		$this->installLibraries($parent);
		$this->installModules($parent);
		$this->installPlugins($parent);

		// $parent is the class calling this method

		require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/template.php';

		$this->com_install();

		$this->handleCSSFile();
	}

	/**
	 * method to uninstall the component
	 *
	 * @param   object  $parent  class calling this method
	 *
	 * @return void
	 */
	public function uninstall($parent)
	{
		// Uninstall extensions
		$this->uninstallLibraries($parent);
		$this->uninstallModules($parent);
		$this->uninstallPlugins($parent);
	}

	/**
	 * method to update the component
	 *
	 * @param   object  $parent  class calling this method
	 *
	 * @return void
	 */
	public function update($parent)
	{
		// $parent is the class calling this method

		require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/template.php';
		$this->com_install();

		// Install extensions
		$this->installLibraries($parent);
		$this->installModules($parent);
		$this->installPlugins($parent);

		$this->handleCSSFile();
	}

	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @param   object  $type    type of change (install, update or discover_install)
	 * @param   object  $parent  class calling this method
	 *
	 * @return void
	 */
	public function preflight($type, $parent)
	{
		if ($type == "update")
		{
			$this->updateschema();
		}
	}

	/**
	 * method to update schema table
	 *
	 * @return void
	 */
	public function updateschema()
	{
		$db = JFactory::getDBO();
		$db->setQuery("SELECT extension_id FROM #__extensions WHERE element ='com_redshop' AND type = 'component'");
		$component_Id = $db->loadResult();

		if ($component_Id != "" && $component_Id != "0")
		{
			$db->setQuery("SELECT * FROM #__schemas WHERE extension_id ='" . $component_Id . "'");
			$total_result = $db->loadResult();

			if (count($total_result) == 0)
			{
				$insert_schema = "insert into #__schemas set extension_id='" . $component_Id . "',version_id='1.1.10'";
				$db->setQuery($insert_schema);
				$db->query();
			}
		}
	}

	/**
	 * Method to run after an install/update/uninstall method
	 *
	 * @param   object  $type    type of change (install, update or discover_install)
	 * @param   object  $parent  class calling this method
	 *
	 * @return void
	 */
	public function postflight($type, $parent)
	{
		// Install Module and Plugin
		$installer  = $parent->getParent();
		$source     = $installer->getPath('source');
		$pluginPath = $source . '/plugins';
	}

	/**
	 * Handle redSHOP Demo CSS file for Demo Content
	 *
	 * @return  void
	 */
	private function handleCSSFile()
	{
		$categoryTemplate = JPATH_SITE . '/components/com_redshop/views/category/tmpl/category/category_template_column.php';

		if (file_exists($categoryTemplate))
		{
			$demoCSS    = JPATH_SITE . '/components/com_redshop/assets/css/redshop-update.css';
			$redSHOPCSS = JPATH_SITE . '/components/com_redshop/assets/css/redshop.css';
			unlink($redSHOPCSS);
			rename($demoCSS, $redSHOPCSS);
		}
	}

	/**
	 * Main redSHOP installer Events
	 *
	 * @return  void
	 */
	private function com_install()
	{
		$db = JFactory::getDBO();

		// The redshop.cfg.php creation or update
		$this->redshopHandleCFGFile();

		// Get the redshop_users_info
		$q = "SHOW COLUMNS FROM #__redshop_users_info";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the user_email column
			if (!array_key_exists('user_email', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_users_info ADD COLUMN `user_email` VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the address column
			if (!array_key_exists('address', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_users_info ADD COLUMN `address` VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the city column
			if (!array_key_exists('city', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_users_info ADD COLUMN `city` VARCHAR( 50 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the zipcode column
			if (array_key_exists('zipcode', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_users_info CHANGE `zipcode` `zipcode` VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the phone column
			if (!array_key_exists('phone', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_users_info ADD COLUMN `phone` VARCHAR( 50 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the company_name column
			if (!array_key_exists('company_name', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_users_info ADD COLUMN `company_name` VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the requesting_tax_exempt column
			if (!array_key_exists('requesting_tax_exempt', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_users_info ADD COLUMN `requesting_tax_exempt` TINYINT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the ean_number column
			if (!array_key_exists('ean_number', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_users_info ADD COLUMN `ean_number` VARCHAR( 250 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the braintree_vault_number column
			if (!array_key_exists('braintree_vault_number', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_users_info ADD COLUMN `braintree_vault_number` VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the accept_terms_conditions column
			if (!array_key_exists('accept_terms_conditions', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_users_info ADD COLUMN `accept_terms_conditions` TINYINT( 4 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the veis_vat_number column
			if (!array_key_exists('veis_vat_number', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_users_info ADD COLUMN `veis_vat_number` VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the veis_status column
			if (!array_key_exists('veis_status', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_users_info ADD COLUMN `veis_status` VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Syncronise users
		$this->userSynchronization();

		// Get the current columns
		$q = "SHOW COLUMNS FROM #__redshop_media";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if an upgrade is needed

			// Check if we have the media_alternate_text column
			if (!array_key_exists('media_alternate_text', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_media ADD `media_alternate_text` VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the ordering column
			if (!array_key_exists('ordering', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_media ADD `ordering` int(11) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}
		}

		$q = "SHOW COLUMNS FROM #__redshop_supplier";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the supplier_email column
			if (!array_key_exists('supplier_email', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_supplier ADD `supplier_email` VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current columns
		$q = "SHOW COLUMNS FROM #__redshop_economic_accountgroup";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the economic_service_nonvat_account column
			if (!array_key_exists('economic_service_nonvat_account', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_economic_accountgroup ADD `economic_service_nonvat_account` VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the economic_discount_product_number column
			if (!array_key_exists('economic_discount_product_number', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_economic_accountgroup CHANGE `economic_discount_novat_account` `economic_discount_product_number` VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the economic_discount_nonvat_account column
			if (!array_key_exists('economic_discount_nonvat_account', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_economic_accountgroup  CHANGE `economic_service_nonvat_account` `economic_discount_nonvat_account` VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the economic_discount_vat_account column
			if (!array_key_exists('economic_discount_vat_account', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_economic_accountgroup ADD `economic_discount_vat_account` VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current INDEX
		$q = "SHOW INDEX FROM #__redshop_product_category_xref";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Key_name');

		if (is_array($cols))
		{
			// Check if we have the ref_category column
			if (!array_key_exists('ref_category', $cols))
			{
				$q = "ALTER IGNORE TABLE `#__redshop_product_category_xref` ADD INDEX `ref_category` ( `product_id` )";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current columns
		$q = "SHOW COLUMNS FROM #__redshop_cart";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the section column
			if (!array_key_exists('section', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_cart ADD `section` VARCHAR( 250 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current columns
		$q = "SHOW COLUMNS FROM #__redshop_customer_question";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the telephone column
			if (!array_key_exists('telephone', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_customer_question ADD `telephone` VARCHAR( 50 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the telephone column
			if (!array_key_exists('address', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_customer_question ADD `address` VARCHAR( 250 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current columns Total Price excl price
		$q = "SHOW COLUMNS FROM #__redshop_stockroom_amount_image ";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if an upgrade is needed

			// Check if we have the stock_amount_image_tooltip column
			if (!array_key_exists('stock_amount_image_tooltip', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_stockroom_amount_image ADD `stock_amount_image_tooltip` TEXT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the stockroom_id column
			if (!array_key_exists('stockroom_id', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_stockroom_amount_image ADD `stockroom_id` INT(11) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current columns
		$q = "SHOW COLUMNS FROM #__redshop_xml_export";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if an upgrade is needed

			// Check if we have the parent_name column
			if (!array_key_exists('parent_name', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_xml_export ADD `parent_name` VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the element_name column
			if (!array_key_exists('element_name', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_xml_export ADD `element_name` VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the published column
			if (!array_key_exists('published', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_xml_export ADD published TINYINT(4) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the use_to_all_users column
			if (!array_key_exists('use_to_all_users', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_xml_export ADD use_to_all_users TINYINT(4) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the xmlexport_billingtag column
			if (!array_key_exists('xmlexport_billingtag', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_xml_export ADD `xmlexport_billingtag` text NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the billing_element_name column
			if (!array_key_exists('billing_element_name', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_xml_export ADD `billing_element_name` VARCHAR(255) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the xmlexport_shippingtag column
			if (!array_key_exists('xmlexport_shippingtag', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_xml_export ADD `xmlexport_shippingtag` text NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the shipping_element_name column
			if (!array_key_exists('shipping_element_name', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_xml_export ADD `shipping_element_name` VARCHAR(255) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the xmlexport_orderitemtag column
			if (!array_key_exists('xmlexport_orderitemtag', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_xml_export ADD `xmlexport_orderitemtag` text NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the orderitem_element_name column
			if (!array_key_exists('orderitem_element_name', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_xml_export ADD `orderitem_element_name` VARCHAR(255) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the xmlexport_stocktag column
			if (!array_key_exists('xmlexport_stocktag', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_xml_export ADD `xmlexport_stocktag` text NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the stock_element_name column
			if (!array_key_exists('stock_element_name', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_xml_export ADD `stock_element_name` VARCHAR(255) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the xmlexport_prdextrafieldtag column
			if (!array_key_exists('xmlexport_prdextrafieldtag', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_xml_export ADD `xmlexport_prdextrafieldtag` text NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the prdextrafield_element_name column
			if (!array_key_exists('prdextrafield_element_name', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_xml_export ADD `prdextrafield_element_name` VARCHAR(255) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the xmlexport_on_category column
			if (!array_key_exists('xmlexport_on_category', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_xml_export ADD `xmlexport_on_category` TEXT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current columns
		$q = "SHOW COLUMNS FROM #__redshop_xml_import";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the published column
			if (!array_key_exists('published', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_xml_import ADD published TINYINT(4) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the xmlexport_billingtag column
			if (!array_key_exists('xmlexport_billingtag', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_xml_import ADD xmlexport_billingtag TEXT NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the xmlexport_shippingtag column
			if (!array_key_exists('xmlexport_shippingtag', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_xml_import ADD xmlexport_shippingtag TEXT NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the xmlexport_orderitemtag column
			if (!array_key_exists('xmlexport_orderitemtag', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_xml_import ADD xmlexport_orderitemtag TEXT NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the xmlimport_stocktag column
			if (!array_key_exists('xmlimport_stocktag', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_xml_import ADD xmlimport_stocktag TEXT NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the xmlexport_billingtag column
			if (array_key_exists('xmlexport_billingtag', $cols) && !array_key_exists('xmlimport_billingtag', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_xml_import CHANGE `xmlexport_billingtag` `xmlimport_billingtag` TEXT NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the xmlexport_shippingtag column
			if (array_key_exists('xmlexport_shippingtag', $cols) && !array_key_exists('xmlimport_shippingtag', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_xml_import CHANGE `xmlexport_shippingtag` `xmlimport_shippingtag` TEXT NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the xmlexport_orderitemtag column
			if (array_key_exists('xmlexport_orderitemtag', $cols) && !array_key_exists('xmlimport_orderitemtag', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_xml_import CHANGE `xmlexport_orderitemtag` `xmlimport_orderitemtag` TEXT NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the xmlimport_prdextrafieldtag column
			if (!array_key_exists('xmlimport_prdextrafieldtag', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_xml_import ADD xmlimport_prdextrafieldtag TEXT NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the element_name column
			if (!array_key_exists('element_name', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_xml_import ADD element_name varchar(255) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the billing_element_name column
			if (!array_key_exists('billing_element_name', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_xml_import ADD billing_element_name varchar(255) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the shipping_element_name column
			if (!array_key_exists('shipping_element_name', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_xml_import ADD shipping_element_name varchar(255) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the orderitem_element_name column
			if (!array_key_exists('orderitem_element_name', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_xml_import ADD orderitem_element_name varchar(255) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the stock_element_name column
			if (!array_key_exists('stock_element_name', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_xml_import ADD stock_element_name varchar(255) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the prdextrafield_element_name column
			if (!array_key_exists('prdextrafield_element_name', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_xml_import ADD prdextrafield_element_name varchar(255) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current columns
		$q = "SHOW COLUMNS FROM #__redshop_product_subscribe_detail";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if an upgrade is needed

			// Check if we have the renewal_reminder column
			if (!array_key_exists('renewal_reminder', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_subscribe_detail ADD `renewal_reminder` TINYINT( 1 ) NOT NULL  DEFAULT '1'";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the order_item_id column
			if (!array_key_exists('order_item_id', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_subscribe_detail ADD `order_item_id` INT( 11 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current columns
		$q = "SHOW COLUMNS FROM #__redshop_product_accessory";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if an upgrade is needed

			// Check if we have the oprand column
			if (!array_key_exists('oprand', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_accessory ADD `oprand` char(1) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the oprand column
			if (!array_key_exists('ordering', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_accessory ADD `ordering` int( 11 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the category_id column
			if (!array_key_exists('category_id', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_accessory ADD `category_id` int( 11 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the setdefault_selected column
			if (!array_key_exists('setdefault_selected', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_accessory ADD `setdefault_selected` TINYINT( 4 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current columns
		$q = "SHOW COLUMNS FROM #__redshop_product_related";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if an upgrade is needed

			// Check if we have the oprand column
			if (!array_key_exists('ordering', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_related ADD `ordering` int( 11 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current columns for category
		$q = "SHOW COLUMNS FROM #__redshop_category";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if an upgrade is needed

			// Check if we have the category_short_description column
			if (!array_key_exists('category_short_description', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_category ADD `category_short_description` longtext NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the category_more_template column
			if (!array_key_exists('category_more_template', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_category ADD `category_more_template` varchar(255) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the category_back_full_image column
			if (!array_key_exists('category_back_full_image', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_category ADD `category_back_full_image` varchar(250) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the compare_template_id column
			if (!array_key_exists('compare_template_id', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_category ADD `compare_template_id` varchar(255) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `append_to_global_seo` column
			if (!array_key_exists('append_to_global_seo', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_category ADD `append_to_global_seo` ENUM( 'append', 'prepend', 'replace' ) NOT NULL DEFAULT 'append'";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `canonical_url` column
			if (!array_key_exists('canonical_url', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_category ADD `canonical_url` text NOT NULL";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current columns for fields_data
		$q = "SHOW COLUMNS FROM #__redshop_fields_value";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if an upgrade is needed

			// Check if we have the alt_text column
			if (!array_key_exists('alt_text', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_fields_value ADD `alt_text` varchar(255) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the image_link column
			if (!array_key_exists('image_link', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_fields_value ADD `image_link` text NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the alt_text column
			if (array_key_exists('alt_text', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_fields_value DROP `alt_text` ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the image_link column
			if (array_key_exists('image_link', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_fields_value DROP `image_link` ";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current columns for fields_data
		$q = "SHOW COLUMNS FROM #__redshop_fields_data";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if an upgrade is needed

			// Check if we have the alt_text column
			if (!array_key_exists('alt_text', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_fields_data ADD `alt_text` varchar(255) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the image_link column
			if (!array_key_exists('image_link', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_fields_data ADD `image_link` varchar(255) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the user_email column
			if (!array_key_exists('user_email', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_fields_data ADD `user_email` varchar(255) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current columns
		$q = "SHOW COLUMNS FROM #__redshop_tax_rate";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if an upgrade is needed

			// Check if we have the tax_group_id column
			if (!array_key_exists('tax_group_id', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_tax_rate ADD tax_group_id INT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the is_eu_country column
			if (!array_key_exists('is_eu_country', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_tax_rate ADD is_eu_country TINYINT( 4 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current columns
		$q = "SHOW COLUMNS FROM #__redshop_product_rating";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if an upgrade is needed

			// Check if we have the email column
			if (!array_key_exists('email', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_rating ADD email VARCHAR(200) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the username column
			if (!array_key_exists('username', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_rating ADD username VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the company_name column
			if (!array_key_exists('company_name', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_rating ADD company_name VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current columns for redshop coupons
		$q = "SHOW COLUMNS FROM #__redshop_coupons";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the coupon_left column
			if (!array_key_exists('coupon_left', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_coupons ADD COLUMN coupon_left INT NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the free_shipping column
			if (!array_key_exists('free_shipping', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_coupons ADD COLUMN free_shipping TINYINT( 4 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the subtotal column
			if (!array_key_exists('subtotal', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_coupons ADD COLUMN subtotal INT NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the order_id column
			if (!array_key_exists('order_id', $cols))
			{
				$q = "ALTER TABLE `#__redshop_coupons` ADD `order_id` INT NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current columns for redshop manufacturer
		$q = "SHOW COLUMNS FROM #__redshop_manufacturer";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the ordering column
			if (!array_key_exists('ordering', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_manufacturer ADD COLUMN ordering INT NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the manufacturer_email column
			if (!array_key_exists('manufacturer_email', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_manufacturer ADD COLUMN manufacturer_email VARCHAR( 255 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the product_per_page column
			if (!array_key_exists('product_per_page', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_manufacturer ADD COLUMN product_per_page INT( 11 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the manufacturer_url column
			if (!array_key_exists('manufacturer_url', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_manufacturer ADD COLUMN manufacturer_url VARCHAR( 255 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the excluding_category_list column
			if (!array_key_exists('excluding_category_list', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_manufacturer ADD COLUMN excluding_category_list TEXT NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current columns for redshop product attribute
		$q = "SHOW COLUMNS FROM #__redshop_product_attribute";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the attribute_published column
			if (!array_key_exists('attribute_published', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_attribute ADD COLUMN `attribute_published` INT NOT NULL DEFAULT '1'";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the attribute_required column
			if (!array_key_exists('attribute_required', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_attribute ADD COLUMN `attribute_required` TINYINT NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the ordering column
			if (!array_key_exists('ordering', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_attribute ADD COLUMN `ordering` INT NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the attribute_set_id column
			if (!array_key_exists('attribute_set_id', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_attribute ADD COLUMN `attribute_set_id` INT NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the allow_multiple_selection column
			if (!array_key_exists('allow_multiple_selection', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_attribute ADD COLUMN `allow_multiple_selection` TINYINT(1) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the attribute_set_id column
			if (!array_key_exists('hide_attribute_price', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_attribute ADD COLUMN `hide_attribute_price` TINYINT(1) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the display_type column
			if (!array_key_exists('display_type', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_attribute ADD COLUMN `display_type` VARCHAR(255) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current columns for redshop product attribute property
		$q = "SHOW COLUMNS FROM #__redshop_product_attribute_property";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the ordering column
			if (!array_key_exists('ordering', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_attribute_property ADD COLUMN `ordering` INT NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Change if we have the ordering column
			if (!array_key_exists('property_number', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_attribute_property ADD COLUMN `property_number` VARCHAR( 255 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the setdefault_selected column
			if (!array_key_exists('setdefault_selected', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_attribute_property ADD COLUMN `setdefault_selected` TINYINT(4) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the setrequire_selected column
			if (!array_key_exists('setrequire_selected', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_attribute_property ADD COLUMN `setrequire_selected` TINYINT(3) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the setmulti_selected column
			if (!array_key_exists('setmulti_selected', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_attribute_property ADD COLUMN `setmulti_selected` TINYINT(4) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the setdisplay_type column
			if (!array_key_exists('setdisplay_type', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_attribute_property ADD COLUMN `setdisplay_type` VARCHAR(255) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the property_published column
			if (!array_key_exists('property_published', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_attribute_property ADD `property_published` TINYINT NOT NULL DEFAULT '1' ";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current columns for #__redshop_product_attribute_price
		$q = "SHOW COLUMNS FROM #__redshop_product_attribute_price";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the discount_price column
			if (!array_key_exists('discount_price', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_attribute_price ADD `discount_price` DECIMAL(12,4) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the discount_price column
			if (array_key_exists('discount_price', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_attribute_price CHANGE `discount_price` `discount_price` DOUBLE NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the product_price column
			if (array_key_exists('product_price', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_attribute_price CHANGE `product_price` `product_price` DOUBLE NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the price_quantity_end column
			if (array_key_exists('price_quantity_end', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_attribute_price CHANGE `price_quantity_end` `price_quantity_end` BIGINT NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Change if we have the discount_start_date column
			if (!array_key_exists('discount_start_date', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_attribute_price ADD `discount_start_date` INT(11) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the discount_end_date column
			if (!array_key_exists('discount_end_date', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_attribute_price ADD `discount_end_date` INT(11) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current columns for redshop product subattribute color
		$q = "SHOW COLUMNS FROM #__redshop_product_subattribute_color";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the attribute_required column
			if (array_key_exists('media_mimetype', $cols))
			{
				$q = "ALTER IGNORE TABLE `#__redshop_product_subattribute_color` DROP `media_mimetype`";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the ordering column
			if (!array_key_exists('ordering', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_subattribute_color ADD COLUMN `ordering` INT NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the subattribute_color_number column
			if (!array_key_exists('subattribute_color_number', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_subattribute_color ADD COLUMN `subattribute_color_number` VARCHAR( 255 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the subattribute_color_price column
			if (array_key_exists('subattribute_color_price', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_subattribute_color CHANGE `subattribute_color_price` `subattribute_color_price` DOUBLE NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the setdefault_selected column
			if (!array_key_exists('setdefault_selected', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_subattribute_color ADD COLUMN `setdefault_selected` TINYINT(4) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the subattribute_color_title column
			if (!array_key_exists('subattribute_color_title', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_subattribute_color ADD COLUMN `subattribute_color_title` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the subattribute_color_main_image column
			if (!array_key_exists('subattribute_color_main_image', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_subattribute_color ADD COLUMN `subattribute_color_main_image` VARCHAR( 255 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the subattribute_published column
			if (!array_key_exists('subattribute_published', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_subattribute_color ADD `subattribute_published` TINYINT NOT NULL DEFAULT '1' ";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current columns for redshop product_voucher
		$q = "SHOW COLUMNS FROM #__redshop_product_voucher";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the voucher_left column
			if (!array_key_exists('voucher_left', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_voucher ADD COLUMN voucher_left INT NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the voucher_code column
			if (array_key_exists('voucher_code', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_voucher CHANGE `voucher_code` `voucher_code` VARCHAR( 255 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current columns for redshop product_voucher
		$q = "SHOW COLUMNS FROM #__redshop_product_voucher_transaction";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the voucher_code column
			if (array_key_exists('voucher_code', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_voucher_transaction CHANGE `voucher_code` `voucher_code` VARCHAR( 255 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the product_id column
			if (!array_key_exists('product_id', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_voucher_transaction ADD `product_id` VARCHAR( 50 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current columns for redshop product_price
		$q = "SHOW COLUMNS FROM #__redshop_product_price";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the price_quantity_start column
			if (!array_key_exists('price_quantity_start', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_price ADD COLUMN price_quantity_start INT NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the price_quantity_end column
			if (!array_key_exists('price_quantity_end', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_price ADD COLUMN price_quantity_end INT NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the price_quantity_end column
			if (array_key_exists('price_quantity_end', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_price CHANGE `price_quantity_end` `price_quantity_end` BIGINT NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the discount_price column
			if (!array_key_exists('discount_price', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_price ADD discount_price DECIMAL( 12, 4 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the discount_start_date column
			if (!array_key_exists('discount_start_date', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_price ADD discount_start_date INT(11) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the discount_end_date column
			if (!array_key_exists('discount_end_date', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_price ADD discount_end_date INT(11) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			if (array_key_exists('product_price', $cols))
			{
				$q = "ALTER TABLE `#__redshop_product_price` CHANGE `product_price` `product_price` DECIMAL( 12, 4 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current columns
		$q = "SHOW COLUMNS FROM #__redshop_state";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the checked_out column
			if (!array_key_exists('checked_out', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_state ADD COLUMN `checked_out` INT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the checked_out_time column
			if (!array_key_exists('checked_out_time', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_state ADD COLUMN `checked_out_time` DATETIME NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the show_state column
			if (!array_key_exists('show_state', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_state ADD COLUMN `show_state` INT NOT NULL DEFAULT '2'";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current columns
		$q = "SHOW COLUMNS FROM #__redshop_product";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the quantity_selectbox_value column
			if (!array_key_exists('quantity_selectbox_value', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD COLUMN quantity_selectbox_value VARCHAR( 255 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the product_download_clock column
			if (!array_key_exists('product_download_clock', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD COLUMN product_download_clock INT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the checked_out column
			if (!array_key_exists('checked_out', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD COLUMN `checked_out` INT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the checked_out_time column
			if (!array_key_exists('checked_out_time', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD COLUMN `checked_out_time` DATETIME NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the product_download_clock_min column
			if (!array_key_exists('product_download_clock_min', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD COLUMN product_download_clock_min INT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the product_parent_id column
			if (!array_key_exists('product_parent_id', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD COLUMN product_parent_id INT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the   	product_special column
			if (!array_key_exists('product_special', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD COLUMN  product_special tinyint(4) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the visited column in Product Table
			if (!array_key_exists('visited', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD COLUMN visited int(11) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the product_download column in Product Table
			if (!array_key_exists('product_download', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD product_download TINYINT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the weight column in Product Table
			if (!array_key_exists('weight', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD `weight` float(10,3) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the   	discount_price column
			if (!array_key_exists('discount_price', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD COLUMN  discount_price double NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `expired` column
			if (!array_key_exists('expired', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD COLUMN  `expired` TINYINT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `product_tax_group_id` column
			if (!array_key_exists('product_tax_group_id', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD COLUMN  `product_tax_group_id` INT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `discount_stratdate` column
			if (!array_key_exists('discount_stratdate', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD COLUMN `discount_stratdate` INT(11) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `discount_enddate` column
			if (!array_key_exists('discount_enddate', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD COLUMN `discount_enddate` INT(11) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `not_for_sale` column
			if (!array_key_exists('not_for_sale', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD COLUMN `not_for_sale` TINYINT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `use_discount_calc` column
			if (!array_key_exists('use_discount_calc', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD COLUMN `use_discount_calc` TINYINT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `discount_calc_method` column
			if (!array_key_exists('discount_calc_method', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD COLUMN `discount_calc_method` VARCHAR( 255 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `discount_calc_unit` column
			if (array_key_exists('discount_calc_unit', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product DROP COLUMN `discount_calc_unit` ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `min_order_product_quantity` column
			if (!array_key_exists('min_order_product_quantity', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD COLUMN `min_order_product_quantity` INT( 11 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `max_order_product_quantity` column
			if (!array_key_exists('max_order_product_quantity', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD COLUMN `max_order_product_quantity` INT( 11 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `attribute_set_id` column
			if (!array_key_exists('attribute_set_id', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD COLUMN `attribute_set_id` INT( 11 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `product_length` column
			if (!array_key_exists('product_length', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD COLUMN `product_length` decimal(10,2) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `product_height` column
			if (!array_key_exists('product_height', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD COLUMN `product_height` decimal(10,2) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `product_width` column
			if (!array_key_exists('product_width', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD COLUMN `product_width` decimal(10,2) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `product_availability_date` column
			if (!array_key_exists('product_availability_date', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD COLUMN `product_availability_date` VARCHAR( 255 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `product_diameter` column
			if (!array_key_exists('product_diameter', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD COLUMN `product_diameter` DECIMAL( 10, 2 )  NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `use_range` column
			if (!array_key_exists('use_range', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD COLUMN `use_range` TINYINT NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `product_download_days` column
			if (!array_key_exists('product_download_days', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD COLUMN `product_download_days` INT( 11 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `product_download_limit` column
			if (!array_key_exists('product_download_limit', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD COLUMN `product_download_limit` INT( 11 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `product_download_infinite` column
			if (!array_key_exists('product_download_infinite', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD COLUMN `product_download_infinite` TINYINT NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `product_back_full_image` column
			if (!array_key_exists('product_back_full_image', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD COLUMN `product_back_full_image` VARCHAR( 250 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `product_back_thumb_image` column
			if (!array_key_exists('product_back_thumb_image', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD COLUMN `product_back_thumb_image` VARCHAR( 250 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `product_preview_image` column
			if (!array_key_exists('product_preview_image', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD COLUMN `product_preview_image` VARCHAR( 250 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `product_preview_back_image` column
			if (!array_key_exists('product_preview_back_image', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD COLUMN `product_preview_back_image` VARCHAR( 250 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `accountgroup_id` column
			if (!array_key_exists('accountgroup_id', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD COLUMN `accountgroup_id` INT( 11 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `preorder` column
			if (!array_key_exists('preorder', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD COLUMN `preorder` VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `append_to_global_seo` column
			if (!array_key_exists('append_to_global_seo', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD `append_to_global_seo` ENUM( 'append', 'prepend', 'replace' ) NOT NULL DEFAULT 'append'";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `canonical_url` column
			if (!array_key_exists('canonical_url', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product ADD `canonical_url` text NOT NULL";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current columns
		$q = "SHOW COLUMNS FROM #__redshop_product_discount_calc";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the area_start column
			if (array_key_exists('area_start', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_discount_calc CHANGE `area_start` `area_start` float(10,2) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the area_end column
			if (array_key_exists('area_end', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_discount_calc CHANGE `area_end` `area_end` float(10,2) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the discount_calc_unit column
			if (!array_key_exists('discount_calc_unit', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_discount_calc ADD COLUMN discount_calc_unit varchar(255) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the area_start_converted column
			if (!array_key_exists('area_start_converted', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_discount_calc ADD COLUMN area_start_converted float(20,8) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}
			else
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_discount_calc CHANGE  `area_start_converted`  `area_start_converted` FLOAT( 20, 8 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the area_end_converted column
			if (!array_key_exists('area_end_converted', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_discount_calc ADD COLUMN area_end_converted float(20,8) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}
			else
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_discount_calc CHANGE  `area_end_converted`  `area_end_converted` FLOAT( 20, 8 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current columns
		$q = "SHOW COLUMNS FROM #__redshop_shipping_rate";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the shipping_rate_weight_start column
			if (!array_key_exists('shipping_rate_weight_start', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shipping_rate ADD COLUMN shipping_rate_weight_start decimal(10,2) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the deliver_type column
			if (!array_key_exists('deliver_type', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shipping_rate ADD COLUMN deliver_type INT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the economic_displaynumber column
			if (!array_key_exists('economic_displaynumber', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shipping_rate ADD COLUMN economic_displaynumber VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the shipping_rate_weight_end column
			if (!array_key_exists('shipping_rate_weight_end', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shipping_rate ADD COLUMN shipping_rate_weight_end decimal(10,2) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the company_only column
			if (!array_key_exists('company_only', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shipping_rate ADD COLUMN company_only TINYINT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the apply_vat column
			if (!array_key_exists('apply_vat', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shipping_rate ADD COLUMN apply_vat TINYINT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the shipping_rate_on_product column
			if (!array_key_exists('shipping_rate_on_product', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shipping_rate ADD COLUMN shipping_rate_on_product LONGTEXT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the shipping_rate_on_category column
			if (!array_key_exists('shipping_rate_on_category', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shipping_rate ADD COLUMN shipping_rate_on_category LONGTEXT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the shipping_rate_on_product column
			if (array_key_exists('shipping_rate_on_product', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shipping_rate CHANGE `shipping_rate_on_product` `shipping_rate_on_product` LONGTEXT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the shipping_rate_on_category column
			if (array_key_exists('shipping_rate_on_category', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shipping_rate CHANGE `shipping_rate_on_category` `shipping_rate_on_category` LONGTEXT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the shipping_rate_on_category column
			if (array_key_exists('shipping_rate_country', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shipping_rate CHANGE `shipping_rate_country` `shipping_rate_country` LONGTEXT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the shipping_location_info column
			if (!array_key_exists('shipping_location_info', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shipping_rate ADD COLUMN `shipping_location_info` LONGTEXT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the shipping_rate_length_start column
			if (!array_key_exists('shipping_rate_length_start', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shipping_rate ADD COLUMN `shipping_rate_length_start` decimal(10,2) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the shipping_rate_length_end column
			if (!array_key_exists('shipping_rate_length_end', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shipping_rate ADD COLUMN `shipping_rate_length_end` decimal(10,2) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the shipping_rate_width_start column
			if (!array_key_exists('shipping_rate_width_start', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shipping_rate ADD COLUMN `shipping_rate_width_start` decimal(10,2) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the shipping_rate_width_end column
			if (!array_key_exists('shipping_rate_width_end', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shipping_rate ADD COLUMN `shipping_rate_width_end` decimal(10,2) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the shipping_rate_height_start column
			if (!array_key_exists('shipping_rate_height_start', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shipping_rate ADD COLUMN `shipping_rate_height_start` decimal(10,2) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the shipping_rate_height_end column
			if (!array_key_exists('shipping_rate_height_end', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shipping_rate ADD COLUMN `shipping_rate_height_end` decimal(10,2) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the shipping_tax_group_id column
			if (!array_key_exists('shipping_tax_group_id', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shipping_rate ADD COLUMN `shipping_tax_group_id` INT( 11 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the shipping_rate_zip_start column
			if (array_key_exists('shipping_rate_zip_start', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shipping_rate CHANGE `shipping_rate_zip_start` `shipping_rate_zip_start` VARCHAR( 20 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the shipping_rate_zip_end column
			if (array_key_exists('shipping_rate_zip_end', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shipping_rate CHANGE `shipping_rate_zip_end` `shipping_rate_zip_end` VARCHAR( 20 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the shipping_rate_state column
			if (!array_key_exists('shipping_rate_state', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shipping_rate ADD `shipping_rate_state` LONGTEXT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the shipping_rate_on_shopper_group column
			if (!array_key_exists('shipping_rate_on_shopper_group', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shipping_rate ADD `shipping_rate_on_shopper_group` LONGTEXT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the shipping_rate_on_shopper_group column
			if (!array_key_exists('consignor_carrier_code', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shipping_rate ADD `consignor_carrier_code` VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current columns
		$q = "SHOW COLUMNS FROM #__redshop_shopper_group";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the shopper_group_customer_type column
			if (!array_key_exists('shopper_group_customer_type', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shopper_group ADD COLUMN shopper_group_customer_type TINYINT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the shopper_group_portal column
			if (!array_key_exists('shopper_group_portal', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shopper_group ADD COLUMN shopper_group_portal TINYINT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the shopper_group_categories column
			if (!array_key_exists('shopper_group_categories', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shopper_group ADD COLUMN shopper_group_categories LONGTEXT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the show_price_without_vat column
			if (!array_key_exists('show_price_without_vat', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shopper_group ADD COLUMN show_price_without_vat TINYINT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the shopper_group_categories column
			if (array_key_exists('shopper_group_categories', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shopper_group CHANGE `shopper_group_categories` `shopper_group_categories` LONGTEXT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the shopper_group_url column
			if (!array_key_exists('shopper_group_url', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shopper_group ADD COLUMN shopper_group_url VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the shopper_group_logo column
			if (!array_key_exists('shopper_group_logo', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shopper_group ADD COLUMN shopper_group_logo VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the shopper_group_introtext column
			if (!array_key_exists('shopper_group_introtext', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shopper_group ADD COLUMN shopper_group_introtext LONGTEXT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the default_shipping column
			if (!array_key_exists('default_shipping', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shopper_group ADD COLUMN default_shipping TINYINT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the default_shipping_rate column
			if (!array_key_exists('default_shipping_rate', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shopper_group ADD COLUMN default_shipping_rate FLOAT( 10, 2 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the apply_vat  column changed
			if (array_key_exists('apply_vat', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shopper_group CHANGE `apply_vat` `tax_exempt_on_shipping` TINYINT( 4 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			if (array_key_exists('tax_exempt_on_shipping', $cols))
			{
				$q = "ALTER TABLE `#__redshop_shopper_group` DROP `tax_exempt_on_shipping`";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the shopper_group_cart_checkout_itemid column
			if (!array_key_exists('shopper_group_cart_checkout_itemid', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shopper_group ADD COLUMN shopper_group_cart_checkout_itemid INT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the shopper_group_cart_itemid column
			if (!array_key_exists('shopper_group_cart_itemid', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shopper_group ADD COLUMN shopper_group_cart_itemid INT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			/* Check if we have the apply_vat_on_show_price  column changed*/
			if (array_key_exists('apply_vat_on_show_price', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shopper_group CHANGE `apply_vat_on_show_price` `tax_exempt` TINYINT( 4 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			if (array_key_exists('tax_exempt', $cols))
			{
				$q = "ALTER TABLE `#__redshop_shopper_group` DROP `tax_exempt`";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `tax_group_id` column
			if (!array_key_exists('tax_group_id', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shopper_group ADD `tax_group_id` INT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `apply_product_price_vat` column
			if (!array_key_exists('apply_product_price_vat', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shopper_group ADD `apply_product_price_vat` INT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `shopper_group_quotation_mode` column
			if (!array_key_exists('shopper_group_quotation_mode', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shopper_group ADD `shopper_group_quotation_mode` TINYINT(4) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `show_price` column
			if (!array_key_exists('show_price', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shopper_group ADD `show_price` VARCHAR(255) NOT NULL DEFAULT 'global'";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `use_as_catalog` column
			if (!array_key_exists('use_as_catalog', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shopper_group ADD `use_as_catalog` VARCHAR(255) NOT NULL DEFAULT 'global'";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `is_logged_in` column
			if (!array_key_exists('is_logged_in', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shopper_group ADD `is_logged_in` INT(11) NOT NULL DEFAULT '1'";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the `is_logged_in` column
			if (array_key_exists('is_logged_in', $cols))
			{
				$q = "ALTER IGNORE TABLE `#__redshop_shopper_group` DROP `is_logged_in` ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the shopper_group_introtext column
			if (!array_key_exists('shopper_group_manufactures', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shopper_group ADD COLUMN shopper_group_manufactures TEXT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current columns
		$q = "SHOW COLUMNS FROM #__redshop_discount";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the start_date column
			if (!array_key_exists('start_date', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_discount ADD COLUMN start_date double NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the end_date column
			if (!array_key_exists('end_date', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_discount ADD COLUMN end_date double NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the discount_amount column
			if (array_key_exists('discount_amount', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_discount CHANGE `discount_amount` `discount_amount` DECIMAL( 10, 4 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current columns for newsletter subscription
		$q = "SHOW COLUMNS FROM #__redshop_newsletter_subscription";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the checkout column
			if (!array_key_exists('checkout', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_newsletter_subscription ADD COLUMN checkout TINYINT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current columns for wrapper
		$q = "SHOW COLUMNS FROM #__redshop_wrapper";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the category_id column
			if (!array_key_exists('category_id', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_wrapper ADD category_id VARCHAR( 250 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current columns for Extra Fields
		$q = "SHOW COLUMNS FROM #__redshop_fields";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the required column
			if (!array_key_exists('required', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_fields ADD COLUMN required TINYINT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the ordering column
			if (!array_key_exists('ordering', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_fields ADD `ordering` INT(11) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the display_in_product column
			if (!array_key_exists('display_in_product', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_fields ADD `display_in_product` TINYINT(4) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the display_in_checkout column
			if (!array_key_exists('display_in_checkout', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_fields ADD `display_in_checkout` TINYINT(4) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the redshop_orders
		$q = "SHOW COLUMNS FROM #__redshop_orders";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the mail1_status column
			if (!array_key_exists('mail1_status', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_orders ADD COLUMN mail1_status TINYINT( 1 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the referral_code column
			if (!array_key_exists('referral_code', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_orders ADD COLUMN referral_code varchar( 50 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the customer_message column
			if (!array_key_exists('customer_message', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_orders ADD COLUMN customer_message varchar( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the shop_id column
			if (!array_key_exists('shop_id', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_orders ADD COLUMN shop_id VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the order_discount_vat column
			if (!array_key_exists('order_discount_vat', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_orders ADD COLUMN order_discount_vat DECIMAL( 10, 3 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the mail2_status column
			if (!array_key_exists('mail2_status', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_orders ADD COLUMN mail2_status TINYINT( 1 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the mail3_status column
			if (!array_key_exists('mail3_status', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_orders ADD COLUMN mail3_status TINYINT( 1 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the track_no column
			if (!array_key_exists('track_no', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_orders ADD `track_no` VARCHAR( 250 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the special_discount column
			if (!array_key_exists('special_discount', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_orders ADD COLUMN special_discount DECIMAL( 10, 2 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the special_discount_amount column
			if (!array_key_exists('special_discount_amount', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_orders ADD COLUMN special_discount_amount DECIMAL( 10, 2 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the payment_discount column
			if (!array_key_exists('payment_discount', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_orders ADD COLUMN payment_discount DECIMAL( 10, 2 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the payment_oprand column
			if (!array_key_exists('payment_oprand', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_orders ADD COLUMN payment_oprand VARCHAR(50) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the discount_type column
			if (!array_key_exists('discount_type', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_orders ADD COLUMN discount_type VARCHAR(255) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the is_booked column
			if (!array_key_exists('is_booked', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_orders ADD COLUMN is_booked TINYINT( 1 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the order_label_create column
			if (!array_key_exists('order_label_create', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_orders ADD COLUMN order_label_create TINYINT( 1 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the vm_order_number column
			if (!array_key_exists('vm_order_number', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_orders ADD COLUMN vm_order_number VARCHAR( 32 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the analytics_status column
			if (!array_key_exists('analytics_status', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_orders ADD COLUMN `analytics_status` INT( 1 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the tax_after_discount column
			if (!array_key_exists('tax_after_discount', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_orders ADD COLUMN `tax_after_discount` DECIMAL( 10, 3 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the barcode column
			if (!array_key_exists('barcode', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_orders ADD COLUMN `barcode` VARCHAR(13) NOT NULL AFTER `order_number`";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the coupon_discount column
			if (!array_key_exists('coupon_discount', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_orders ADD COLUMN `coupon_discount` DECIMAL( 12, 2 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the order_discount column
			if (!array_key_exists('order_discount', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_orders ADD COLUMN `order_discount` DECIMAL( 12, 2 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the requisition_number column
			if (!array_key_exists('requisition_number', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_orders ADD COLUMN `requisition_number` VARCHAR(255) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the bookinvoice_number column
			if (!array_key_exists('bookinvoice_number', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_orders ADD COLUMN `bookinvoice_number` INT(11) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the bookinvoice_date column
			if (!array_key_exists('bookinvoice_date', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_orders ADD COLUMN `bookinvoice_date` INT(11) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the recuuring_subcription_id column
			if (!array_key_exists('recuuring_subcription_id', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_orders ADD COLUMN `recuuring_subcription_id` VARCHAR( 500 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the redshop_order accessory
		$q = "SHOW COLUMNS FROM #__redshop_order_acc_item";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the order_acc_price column
			if (!array_key_exists('order_acc_price', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_order_acc_item ADD COLUMN order_acc_price DECIMAL( 15,4 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the order_acc_vat column
			if (!array_key_exists('order_acc_vat', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_order_acc_item ADD COLUMN order_acc_vat DECIMAL( 15,4 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the redshop_order attribute Item
		$q = "SHOW COLUMNS FROM #__redshop_order_attribute_item";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the order_acc_price column
			if (!array_key_exists('stockroom_id', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_order_attribute_item ADD COLUMN stockroom_id VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the order_acc_vat column
			if (!array_key_exists('stockroom_quantity', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_order_attribute_item ADD COLUMN stockroom_quantity VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the redshop_order_item
		$q = "SHOW COLUMNS FROM #__redshop_order_item";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the stockroom_id column
			if (!array_key_exists('stockroom_id', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_order_item ADD COLUMN stockroom_id  INT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the wrapper_id column
			if (!array_key_exists('wrapper_id', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_order_item ADD wrapper_id INT( 11 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the wrapper_price column
			if (!array_key_exists('wrapper_price', $cols))
			{
				$q = "ALTER IGNORE TABLE `#__redshop_order_item` ADD `wrapper_price` DECIMAL(10,2) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the is_giftcard column
			if (!array_key_exists('is_giftcard', $cols))
			{
				$q = "ALTER IGNORE TABLE `#__redshop_order_item` ADD `is_giftcard` TINYINT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the giftcard_user_name column
			if (!array_key_exists('giftcard_user_name', $cols))
			{
				$q = "ALTER IGNORE TABLE `#__redshop_order_item` ADD `giftcard_user_name` VARCHAR(255) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the giftcard_user_email column
			if (!array_key_exists('giftcard_user_email', $cols))
			{
				$q = "ALTER IGNORE TABLE `#__redshop_order_item` ADD `giftcard_user_email` VARCHAR(255) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the product_item_price_excl_vat column
			if (!array_key_exists('product_item_price_excl_vat', $cols))
			{
				$q = "ALTER IGNORE TABLE `#__redshop_order_item` ADD `product_item_price_excl_vat` DECIMAL(10,3) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the stockroom_id column
			if (array_key_exists('stockroom_id', $cols))
			{
				$q = "ALTER IGNORE TABLE `#__redshop_order_item` CHANGE `stockroom_id` `stockroom_id` VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the customer_note column
			if (!array_key_exists('customer_note', $cols))
			{
				$q = "ALTER IGNORE TABLE `#__redshop_order_item` ADD `customer_note` TEXT NOT NULL AFTER `order_status`";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the product_item_old_price column
			if (!array_key_exists('product_item_old_price', $cols))
			{
				$q = "ALTER IGNORE TABLE `#__redshop_order_item` ADD `product_item_old_price` DECIMAL( 10, 4 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the is_split column
			if (!array_key_exists('product_purchase_price', $cols))
			{
				$q = "ALTER IGNORE TABLE `#__redshop_order_item` ADD `product_purchase_price` decimal(10,4) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the attribute_image column
			if (!array_key_exists('attribute_image', $cols))
			{
				$q = "ALTER TABLE `#__redshop_order_item` ADD `attribute_image` TEXT NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the discount_calc_data  column
			if (!array_key_exists('discount_calc_data', $cols))
			{
				$q = "ALTER TABLE `#__redshop_order_item` ADD `discount_calc_data` TEXT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the order_item_name  column
			if (array_key_exists('order_item_name', $cols))
			{
				$q = "ALTER TABLE `#__redshop_order_item` CHANGE `order_item_name` `order_item_name` VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the stockroom_quantity  column
			if (!array_key_exists('stockroom_quantity', $cols))
			{
				$q = "ALTER TABLE `#__redshop_order_item` ADD `stockroom_quantity` VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the redshop_stockroom
		$q = "SHOW COLUMNS FROM #__redshop_stockroom";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the delivery_time column
			if (!array_key_exists('delivery_time', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_stockroom ADD COLUMN delivery_time VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			if (array_key_exists('stockroom_name', $cols))
			{
				$q = "ALTER TABLE `#__redshop_stockroom` CHANGE `stockroom_name` `stockroom_name` VARCHAR( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			if (array_key_exists('show', $cols))
			{
				$q = "ALTER TABLE `#__redshop_stockroom` CHANGE `show` `show_in_front` TINYINT( 1 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			if (!array_key_exists('min_stock_amount', $cols))
			{
				$q = "ALTER TABLE #__redshop_stockroom ADD  `min_stock_amount` INT( 11 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the product_stockroom_xref
		$q = "SHOW COLUMNS FROM #__redshop_product_stockroom_xref ";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the delivery_time column
			if (!array_key_exists('preorder_stock', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_stockroom_xref ADD COLUMN preorder_stock INT( 11 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			if (!array_key_exists('ordered_preorder', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_stockroom_xref ADD COLUMN ordered_preorder INT( 11 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the product_stockroom_xref
		$q = "SHOW COLUMNS FROM #__redshop_product_attribute_stockroom_xref ";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the delivery_time column
			if (!array_key_exists('preorder_stock', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_attribute_stockroom_xref ADD COLUMN preorder_stock INT( 11 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			if (!array_key_exists('ordered_preorder', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_attribute_stockroom_xref ADD COLUMN ordered_preorder INT( 11 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}
		}

		$q = "SHOW COLUMNS FROM #__redshop_order_users_info";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the phone column
			if (!array_key_exists('phone', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_order_users_info ADD COLUMN `phone` VARCHAR( 50 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the address column
			if (!array_key_exists('address', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_order_users_info ADD COLUMN `address` VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the zipcode column
			if (array_key_exists('zipcode', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_order_users_info CHANGE `zipcode` `zipcode` VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the city column
			if (!array_key_exists('city', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_order_users_info ADD COLUMN `city` VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the user_email column
			if (!array_key_exists('user_email', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_order_users_info ADD COLUMN `user_email` VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the company_name column
			if (!array_key_exists('company_name', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_order_users_info ADD COLUMN `company_name` VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the requesting_tax_exempt column
			if (!array_key_exists('requesting_tax_exempt', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_order_users_info ADD COLUMN `requesting_tax_exempt` TINYINT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the ean_number column
			if (!array_key_exists('ean_number', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_order_users_info ADD COLUMN `ean_number` VARCHAR( 250 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the thirdparty_email column
			if (!array_key_exists('thirdparty_email', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_order_users_info ADD COLUMN `thirdparty_email` VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}
		}

		$q = "SHOW COLUMNS FROM #__redshop_shipping_rate";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the phone column
			if (!array_key_exists('shipping_class', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_shipping_rate CHANGE COLUMN `shipping_id` `shipping_class` VARCHAR(255) NOT NULL";
				$db->setQuery($q);
				$db->query();

				$query = "SELECT s.shipping_class, r.shipping_rate_id FROM #__redshop_shipping_rate AS r "
					. "LEFT JOIN #__redshop_shipping_method AS s ON r.shipping_class = s.shipping_id ";
				$db->setQuery($query);
				$list = $db->loadObjectlist();

				for ($i = 0; $i < count($list); $i++)
				{
					if ($list[$i]->shipping_class != "")
					{
						$query = 'UPDATE #__redshop_shipping_rate SET shipping_class="' . $list[$i]->shipping_class . '" '
							. 'WHERE shipping_rate_id="' . $list[$i]->shipping_rate_id . '" ';
						$db->setQuery($query);
						$db->query();
					}
				}
			}
		}

		$ratesql = "INSERT IGNORE INTO `#__redshop_shipping_rate` (`shipping_rate_id`, `shipping_rate_name`, `shipping_class`, `shipping_rate_country`, `shipping_rate_zip_start`, `shipping_rate_zip_end`, `shipping_rate_weight_start`, `company_only`, `apply_vat`, `shipping_rate_weight_end`, `shipping_rate_volume_start`, `shipping_rate_volume_end`, `shipping_rate_ordertotal_start`, `shipping_rate_ordertotal_end`, `shipping_rate_priority`, `shipping_rate_value`, `shipping_rate_package_fee`, `shipping_location_info`, `shipping_rate_length_start`, `shipping_rate_length_end`, `shipping_rate_width_start`, `shipping_rate_width_end`, `shipping_rate_height_start`, `shipping_rate_height_end`, `shipping_rate_on_product`, `shipping_rate_on_category`, `shipping_tax_group_id`, `shipping_rate_state`) VALUES
						(1, 'Demo Rate', 'default_shipping', '', '', '', 0.00, 0, 0, 0.00, 0.00, 0.00, 0.000, 0.000, 0, 0.00, 0.00, '', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', '', 0, '')";
		$db->setQuery($ratesql);
		$db->Query();

		$boxsql = "INSERT IGNORE INTO `#__redshop_shipping_boxes` (`shipping_box_id`, `shipping_box_name`, `shipping_box_length`, `shipping_box_width`, `shipping_box_height`, `shipping_box_priority`, `published`) VALUES
							(1, 'Box1', 1.00, 1.00, 1.00, 1, 1)";
		$db->setQuery($boxsql);
		$db->Query();

		$q = "SHOW COLUMNS FROM #__redshop_wrapper";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the wrapper_use_to_all column
			if (!array_key_exists('wrapper_use_to_all', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_wrapper ADD COLUMN `wrapper_use_to_all` TINYINT( 4 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the published column
			if (!array_key_exists('published', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_wrapper ADD COLUMN `published` TINYINT( 4 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the createdate column
			if (!array_key_exists('createdate', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_wrapper ADD COLUMN `createdate` TINYINT( 4 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the product_id column
			if (array_key_exists('product_id', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_wrapper CHANGE `product_id` `product_id` VARCHAR( 255 ) NOT NULL";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current columns for redshop product_category_xref
		$q = "SHOW COLUMNS FROM #__redshop_product_category_xref";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the voucher_left column
			if (!array_key_exists('ordering', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_category_xref ADD COLUMN ordering INT NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current columns for redshop quotation_item
		$q = "SHOW COLUMNS FROM #__redshop_quotation_item";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the is_giftcard column
			if (!array_key_exists('is_giftcard', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_quotation_item ADD is_giftcard TINYINT( 4 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the mycart_accessory column
			if (!array_key_exists('mycart_accessory', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_quotation_item ADD mycart_accessory TEXT NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the actualitem_price column
			if (!array_key_exists('actualitem_price', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_quotation_item ADD actualitem_price DECIMAL( 15, 4 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the product_wrapperid column
			if (!array_key_exists('product_wrapperid', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_quotation_item ADD product_wrapperid INT( 11 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the wrapper_price column
			if (!array_key_exists('wrapper_price', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_quotation_item ADD wrapper_price DECIMAL( 15, 2 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the product_excl_price column
			if (!array_key_exists('product_excl_price', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_quotation_item ADD product_excl_price DECIMAL( 15, 4 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the product_final_price column
			if (!array_key_exists('product_final_price', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_quotation_item ADD product_final_price DECIMAL( 15, 4 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Get the current columns for redshop quotation
		$q = "SHOW COLUMNS FROM #__redshop_quotation";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the order_id column
			if (!array_key_exists('order_id', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_quotation ADD order_id INT(11) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the quotation_encrkey column
			if (!array_key_exists('quotation_encrkey', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_quotation ADD quotation_encrkey varchar(255) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the quotation_discount column
			if (!array_key_exists('quotation_discount', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_quotation ADD quotation_discount DECIMAL( 15, 4 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the quotation_tax column
			if (!array_key_exists('quotation_tax', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_quotation ADD quotation_tax DECIMAL( 15, 2 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the user_email column
			if (!array_key_exists('user_email', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_quotation ADD user_email VARCHAR( 255 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the user_email column
			if (!array_key_exists('quotation_special_discount', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_quotation ADD quotation_special_discount DECIMAL( 15, 4 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Check installed payment plugins

		// 	update order_payment table
		$q = "SHOW COLUMNS FROM #__redshop_order_payment";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the order_payment_cardname
			if (!array_key_exists('order_payment_cardname', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_order_payment ADD `order_payment_cardname` BLOB NOT NULL AFTER `order_payment_code`";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the order_payment_ccv
			if (!array_key_exists('order_payment_ccv', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_order_payment ADD `order_payment_ccv` BLOB NOT NULL AFTER `order_payment_cardname`";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the order_payment_ccv
			if (!array_key_exists('payment_method_class', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_order_payment ADD `payment_method_class`  VARCHAR( 256 ) NULL AFTER `order_payment_name`";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the order_payment_ccv
			if (!array_key_exists('authorize_status', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_order_payment ADD `authorize_status`  VARCHAR( 255 ) NULL AFTER `payment_method_class`";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the order_transfee
			if (!array_key_exists('order_transfee', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_order_payment ADD `order_transfee`  DOUBLE( 10, 2 ) NULL ";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Add ordering field
		$q = "SHOW COLUMNS FROM #__redshop_discount_product";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the discount_amount column
			if (array_key_exists('discount_amount', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_discount_product CHANGE `discount_amount` `discount_amount` DECIMAL( 10, 2 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the category_ids column
			if (!array_key_exists('category_ids', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_discount_product ADD `category_ids` TEXT NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Add ordering field

		// Wishlist start
		$q = "SHOW COLUMNS FROM #__redshop_wishlist";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the discount_amount column
			if (array_key_exists('product_id', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_wishlist CHANGE `product_id` `wishlist_name` VARCHAR( 100 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}
		}

		// End

		// Mail start
		$q = "SHOW COLUMNS FROM #__redshop_mail";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the discount_amount column
			if (!array_key_exists('mail_bcc', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_mail ADD `mail_bcc` VARCHAR( 255 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}
		}

		// End
		// wishlist start
		$q = "SHOW COLUMNS FROM #__redshop_country";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the discount_amount column
			if (!array_key_exists('country_jtext', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_country ADD `country_jtext` VARCHAR( 255 ) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Giftcard start
		$q = "SHOW COLUMNS FROM #__redshop_giftcard";
		$db->setQuery($q);

		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the giftcard_value column
			if (!array_key_exists('giftcard_value', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_giftcard ADD `giftcard_value` decimal(10,3) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			if (!array_key_exists('customer_amount', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_giftcard ADD `customer_amount` INT(11) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			if (array_key_exists('customer_amount', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_giftcard CHANGE `customer_amount` `customer_amount` INT(11) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			if (!array_key_exists('accountgroup_id', $cols))
			{
				$q = "ALTER TABLE `#__redshop_giftcard` ADD `accountgroup_id` INT NOT NULL  ";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Mass discount start
		$q = "SHOW COLUMNS FROM #__redshop_mass_discount";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the discount_product column
			if (!array_key_exists('discount_product', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_mass_discount ADD `discount_product` LONGTEXT NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the discount_name column
			if (!array_key_exists('discount_name', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_mass_discount ADD `discount_name` LONGTEXT NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Product_download
		$q = "SHOW COLUMNS FROM #__redshop_product_download";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the product_serial_number column
			if (!array_key_exists('product_serial_number', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_product_download ADD `product_serial_number` varchar(255) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}
		}

		$q = "SHOW COLUMNS FROM #__redshop_template";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Field');

		if (is_array($cols))
		{
			// Check if we have the order_status column
			if (!array_key_exists('order_status', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_template ADD `order_status` varchar(255) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the payment_methods column
			if (!array_key_exists('payment_methods', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_template ADD `payment_methods` varchar(255) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the shipping_methods column
			if (!array_key_exists('shipping_methods', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_template ADD `shipping_methods` varchar(255) NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the checked_out column
			if (!array_key_exists('checked_out', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_template ADD COLUMN `checked_out` INT NOT NULL";
				$db->setQuery($q);
				$db->query();
			}

			// Check if we have the checked_out_time column
			if (!array_key_exists('checked_out_time', $cols))
			{
				$q = "ALTER IGNORE TABLE #__redshop_template ADD COLUMN `checked_out_time` DATETIME NOT NULL ";
				$db->setQuery($q);
				$db->query();
			}
		}

		// Demo content insert

		$q = "INSERT IGNORE INTO `#__redshop_mail`
			(`mail_id`, `mail_name`, `mail_subject`, `mail_section`, `mail_order_status`, `mail_body`, `published`, `mail_bcc`)
			VALUES
			(1, 'Ask Question', 'Ask Question About Product', 'ask_question_mail', '0', '<p>To Admin,</p>\r\n<p>Product  : {product_name}</p>\r\n<p>Please check this link : {product_link}</p>\r\n<p> </p>\r\n<p>{user_question}</p>\r\n<p>{answer}</p>\r\n<p> </p>', 1, ''),
			(10, 'Reset Password Mail', 'Reset Password', 'status_of_password_reset', '0', '<p>Hello, request has been made to reset your {username} account password. To reset your password, you will need to submit this token in order to verify that the request was legitimate.</p>\r\n<p>The token is {reset_token}</p>\r\n<p>Click on the URL below to enter the token and proceed with resetting your password.</p>\r\n<p><a href=\"{password_complete_url}\">Reset Password</a></p>\r\n<p> </p>\r\n<p>Thank you.</p>', 1, ''),
			(11, 'Send to friend', 'Send to friend', 'product', '0', '<p>Hi {friend_name} ,</p>\r\n<p>New Product  : {product_name}</p>\r\n<p>{product_desc} Please check this link : {product_url}</p>\r\n<p> </p>\r\n<p> </p>', 1, ''),
			(12, 'Tax exempt approval mail', 'Tax exempt approval mail subject', 'tax_exempt_approval_mail', '0', '<p>Hello,</p>\r\n<p>Tax exempt has been approved</p>', 1, ''),
			(13, 'Tax exempt disapproval mail', 'Tax exempt disapproval mail subject', 'tax_exempt_disapproval_mail', '0', '<p>Hello,</p>\r\n<p>Tax exempt  has been disapproved.</p>', 1, ''),
			(14, 'Tax exempt waiting approval mail', 'Tax exempt waiting approval mail subject', 'tax_exempt_waiting_approval_mail', '0', '<p>Tax exempt waiting approval mail contents...</p>\r\n<p>Thanks.</p>', 1, ''),
			(15, 'Registration mail', 'Registration mail', 'register', '0', '<table style=\"border: 1px solid #ccc; background: #fff; width: 600px;\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tbody>\r\n<tr>\r\n<td>\r\n<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 20px;\"></td>\r\n<td>\r\n<table style=\"width: 100%;\">\r\n<tbody>\r\n<tr>\r\n<td width=\"400px\"><a href=\"http://redcomponent.com\" target=\"_blank\"> <img src=\"http://redcomponent.com/images/redcomponent_logo.png\" style=\"width: 340px; height: auto; padding-top: 20px;\" /> </a></td>\r\n<td style=\"font-size: 12px; color: #878787; float: right;\"></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n<td style=\"width: 20px;\"></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<table style=\"width: 100%;\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tbody>\r\n<tr>\r\n<td style=\"height: 20px; background-color: #444544;\"></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tbody>\r\n<tr>\r\n<td colspan=\"3\" style=\"height: 20px;\"></td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 20px;\"></td>\r\n<td>\r\n<p style=\"font-size: 24px; font-family: verdana; color: #616161; text-align: justify;\">Thank you for your registration!</p>\r\n<p style=\"font-size: 14px; font-family: verdana; color: #616161; text-align: justify;\">You are now a registered user at redCOMPONENT.</p>\r\n<p style=\"font-size: 14px; font-family: verdana; color: #616161; text-align: justify; line-height: 20px;\">To go to your account where you can make all necessary adjustments, such as change your address book, view old orders, download your purchased products, or sign up for our newsletter please click <a href=\"http://www.redcomponent.com/account\" target=\"_blank\">here</a>.</p>\r\n<p style=\"font-size: 14px; font-family: verdana; color: #616161; text-align: justify;\">�</p>\r\n<p style=\"font-size: 14px; font-family: verdana; color: #616161; text-align: justify;\"><strong>User information:</strong></p>\r\n<p style=\"font-size: 14px; font-family: verdana; color: #616161; text-align: justify;\">Username: {username}</p>\r\n<p style=\"font-size: 14px; font-family: verdana; color: #616161; text-align: justify;\">Password: {password}</p>\r\n<p style=\"font-size: 14px; font-family: verdana; color: #616161; text-align: justify;\">�</p>\r\n<p style=\"font-size: 14px; font-family: verdana; color: #616161; text-align: justify;\">Regards,<br />redCOMPONENT</p>\r\n</td>\r\n<td style=\"width: 20px;\"></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<table style=\"margin-top: 30px; width: 100%;\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tbody>\r\n<tr>\r\n<td style=\"height: 45px; background-color: #444544; display: block !important;\"></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>', 1, ''),
			(16, 'Catlog First Reminder', 'Catlog First Reminder', 'catalog_first_reminder', '0', '<!-- 		@page { margin: 0.79in } 		P { margin-bottom: 0.08in } 	-->\r\n<p style=\"margin-left: 0.5in; margin-bottom: 0in; text-align: left;\"><strong><span style=\"font-family: Verdana,sans-serif;\"><span style=\"font-size: x-small;\">Dear {name}. <br /></span></span></strong></p>\r\n<p style=\"margin-left: 0.5in; margin-bottom: 0in; text-align: left;\"><span style=\"font-family: Verdana,sans-serif;\"><span style=\"font-size: x-small;\"><span> My name is xyz, in charge of customer support here at abc. We sent you our catalogue the other day, and I would just like to know if you had a chance to look at it...? In any case, I am ready by the phone / e-mail if you need any assistance whatsoever. </span></span></span></p>\r\n<p style=\"margin-left: 0.5in; margin-bottom: 0in; text-align: left;\"><strong><span style=\"font-family: Verdana,sans-serif;\"><span style=\"font-size: x-small;\"><span>Kind regards,</span></span></span></strong></p>\r\n<p style=\"margin-left: 0.5in; margin-bottom: 0in; text-align: left;\"><strong><span style=\"font-family: Verdana,sans-serif;\"><span style=\"font-size: x-small;\"><span>Name<br /></span></span></span></strong></p>\r\n<p style=\"margin-left: 0.5in; margin-bottom: 0in;\"> </p>', 1, ''),
			(17, 'Catlog Second Reminder', 'Catlog Second Reminder', 'catalog_second_reminder', '0', '<p style=\"background: #ffffff none repeat scroll 0% 0%; margin-left: 0.5in; margin-bottom: 0in; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; text-align: left;\"><strong><span style=\"font-family: Verdana,sans-serif;\"><span style=\"font-size: x-small;\"><span>Dear {name}, </span></span></span></strong></p>\r\n<p style=\"background: #ffffff none repeat scroll 0% 0%; margin-left: 0.5in; margin-bottom: 0in; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; text-align: left;\"><span style=\"font-family: Verdana,sans-serif;\"><span style=\"font-size: x-small;\"><span> I just wish to inform you that we are currently running a campaign for all the clients who received our catalogue earlier. This means that in the next 4 days, you get </span></span></span><span style=\"color: #ff0000;\"><span style=\"font-family: Verdana,sans-serif;\"><span style=\"font-size: x-small;\"><span>5% </span></span></span></span><span style=\"font-family: Verdana,sans-serif;\"><span style=\"font-size: x-small;\"><span>off everything you buy, and since our products are already competitively priced, it is a really good offer. You can use the code: XXX when you order to get the discount, but remember you have 4 days from now to decide!</span></span></span></p>\r\n<p style=\"background: #ffffff none repeat scroll 0% 0%; margin-left: 0.5in; margin-bottom: 0in; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; text-align: left;\"> </p>\r\n<p style=\"background: #ffffff none repeat scroll 0% 0%; margin-left: 0.5in; margin-bottom: 0in; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; text-align: left;\"><strong>Regards,</strong></p>\r\n<p style=\"background: #ffffff none repeat scroll 0% 0%; margin-left: 0.5in; margin-bottom: 0in; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; text-align: left;\"><strong>xyz. </strong></p>', 1, ''),
			(18, 'Catlog Sample First Reminder', 'Catlog Sample First Reminder', 'colour_sample_first_reminder', '0', '<!-- 		@page { margin: 0.79in } 		P { margin-bottom: 0.08in } 	-->\r\n<p style=\"margin-left: 0.5in; margin-bottom: 0in;\"><span style=\"font-family: Verdana,sans-serif;\"><span style=\"font-size: x-small;\"><span>Dear {name}. My name is xyz, in charge of customer support here at xyz. You have requested some colour samples, and I will send them to you as soon as possible. If you have any questions, please do not hesitate to contact me. </span></span></span><span style=\"font-family: Verdana,sans-serif;\"><span style=\"font-size: x-small;\">Kind regards, xyz</span></span></p>', 1, ''),
			(19, 'Catlog Sample Second Reminder', 'Catlog Sample Second Reminder', 'colour_sample_second_reminder', '0', '<!-- 		@page { margin: 0.79in } 		P { margin-bottom: 0.08in } 	-->\r\n<p style=\"margin-left: 0.5in; margin-bottom: 0in;\"><span style=\"font-family: Verdana,sans-serif;\"><span style=\"font-size: x-small;\"><span>Dear {name}. I sent you some sample colour material the other day, and I would just like to know if you had a chance to look at it...? In any case, I am ready by the phone / e-mail if you need any assistance whatsoever. Kind regards, xyz</span></span></span></p>', 1, ''),
			(20, 'Catlog Sample Third Reminder', 'Catlog Sample Third Reminder', 'colour_sample_third_reminder', '0', '<!-- 		@page { margin: 0.79in } 		P { margin-bottom: 0.08in } 	-->\r\n<p style=\"margin-left: 0.5in; margin-bottom: 0in;\"><span style=\"font-family: Verdana,sans-serif;\"><span style=\"font-size: x-small;\"><span>Dear {name}. I just wish to inform you that we are currently running a campaign for all the clients who received sample colour material from us earlier. This means that in the next 4 days, you get 5% off everything you buy, and since our products are already competitively priced, it is a really good offer. You can use the code: XXX when you order to get the discount, but remember you have 4 days from now to decide!</span></span></span></p>', 1, ''),
			(21, 'Order Mail', 'Order Mail for {order_id}', 'order', '0', '<table style=\"border: 1px solid #ccc; background: #fff; width: 700px;\" cellpadding=\"0\" cellspacing=\"0\">
<tbody>
<tr>
<td style=\"line-height: 25px;\">
<table style=\"width: 100%; background: #f7f7f7;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
<tbody>
<tr>
<td colspan=\"3\" style=\"height: 10px;\"></td>
</tr>
<tr>
<td style=\"width: 20px;\"></td>
<td style=\"line-height: 25px;\">
<table style=\"width: 100%;\">
<tbody>
<tr>
<td width=\"400px\"><a href=\"http://redcomponent.com\" target=\"_blank\"> <img src=\"images/redcomponent_logo.png\" style=\"width: 340px; height: auto; padding-top: 20px;\" /> </a></td>
<td style=\"font-size: 12px; color: #878787; float: right; text-align: right; font-family: verdana; vertical-align: top;\">{order_date}</td>
</tr>
</tbody>
</table>
</td>
<td style=\"width: 20px;\"></td>
</tr>
</tbody>
</table>
<table style=\"width: 100%;\" cellpadding=\"0\" cellspacing=\"0\">
<tbody>
<tr>
<td style=\"height: 20px; background-color: #444544;\"></td>
</tr>
</tbody>
</table>
<table style=\"width: 100%;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
<tbody>
<tr>
<td colspan=\"3\" style=\"height: 20px;\"></td>
</tr>
<tr>
<td style=\"width: 20px;\"></td>
<td style=\"line-height: 25px;\">
<p style=\"font-size: 24px; font-family: verdana; color: #666666; text-align: justify;\"><strong>Thank you for your order!</strong></p>
<p style=\"font-size: 14px; font-family: verdana; color: #666666; text-align: justify;\">Thank you for ordering from redCOMPONENT!<br />To download your purchased products please follow the link in the bottom of this mail.</p>
<p style=\"font-size: 14px; font-family: verdana; color: #666666; text-align: justify; margin-bottom: 0 !important;\">&nbsp;</p>
<p style=\"font-size: 14px; font-family: verdana; color: #666666; text-align: justify; margin-bottom: 5px !important;\"><strong>Products ordered:</strong></p>
<table style=\"width: 100%; line-height: 25px;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
<tbody><!--{product_loop_start}-->
<tr style=\"border-bottom: 1px solid #F1F1F1;\">
<td colspan=\"3\">
<table style=\"width: 100%;\" cellpadding=\"0\" cellspacing=\"0\">
<tbody>
<tr>
<td style=\"width: 32px;\"><span style=\"font-size: 14px; font-family: verdana; color: #666666; text-align: justify;\"> {product_quantity} x </span></td>
<td><span style=\"font-size: 14px; font-family: verdana; color: #666666; text-align: justify;\">{product_name}{without_vat}</span> <span style=\"font-size: 14px; font-family: verdana; color: #666666; text-align: justify;\">{product_accessory}</span></td>
</tr>
</tbody>
</table>
</td>
<td align=\"right\"><span style=\"font-size: 14px; font-family: verdana; color: #666666; text-align: right;\">{product_total_price}</span></td>
</tr>
<!--{product_loop_end}--> <!--{if discount}-->
<tr>
<td colspan=\"3\"><span style=\"font-size: 14px; font-family: verdana; color: #666666; text-align: justify;\">Discount</span></td>
<td align=\"right\"><span style=\"font-size: 14px; font-family: verdana; color: #666666; text-align: justify;\">{discount_excl_vat}</span></td>
</tr>
<!--{discount end if}-->
<tr>
<td colspan=\"3\" style=\"padding-top: 10px;\"><span style=\"font-size: 14px; font-family: verdana; color: #666666; text-align: justify;\">VAT</span></td>
<td align=\"right\"><span style=\"font-size: 14px; font-family: verdana; color: #666666; text-align: justify;\">{sub_total_vat}</span></td>
</tr>
<tr>
<td colspan=\"3\"><span style=\"font-size: 14px; font-family: verdana; color: #666666; text-align: justify;\"><strong>Total</strong></span></td>
<td align=\"right\"><span style=\"font-size: 14px; font-family: verdana; color: #666666; text-align: justify;\"><strong>{order_total}</strong></span></td>
</tr>
</tbody>
</table>
<p style=\"text-align: justify;\">&nbsp;</p>
<p style=\"font-size: 14px; font-family: verdana; color: #666666; text-align: justify; margin-bottom: 5px !important;\"><strong>Billing information: </strong></p>
<div style=\"font-size: 14px; font-family: verdana; color: #666666; text-align: justify;\">{billing_address}</div>
<p>&nbsp;</p>
<p style=\"font-size: 14px; font-family: verdana; color: #666666; text-align: justify; margin-bottom: 5px !important;\"><strong>Additional information:</strong></p>
<p style=\"font-size: 14px; font-family: verdana; color: #666666; text-align: justify; margin-bottom: 5px !important;\">{customer_note}</p>
<p>&nbsp;</p>
<p style=\"font-size: 14px; font-family: verdana; color: #666666; text-align: justify; margin-bottom: 5px !important;\"><strong>Order details:</strong></p>
<p style=\"font-size: 14px; font-family: verdana; color: #666666; text-align: justify;\">To see the details of your order and download your products please {order_detail_link}.</p>
<p>&nbsp;</p>
<p style=\"font-size: 14px; font-family: verdana; color: #666666; text-align: justify; margin-bottom: 5px !important;\"><strong>Please Note!</strong></p>
<p style=\"font-size: 14px; font-family: verdana; color: #666666; text-align: justify;\">Single sales items are available for immediate download, and until 7 days after purchase. Please ensure that you download your product within this timeframe.</p>
<p style=\"font-size: 14px; font-family: verdana; color: #666666; text-align: justify;\">When ordering one or more service products:</p>
<p style=\"font-size: 14px; font-family: verdana; color: #666666; text-align: justify;\">As this is a manual process, please allow 2-4 business days for a redCOMPONENT employee to get back to You.</p>
<p style=\"font-size: 14px; font-family: verdana; color: #666666; text-align: justify;\">Customizations Services is offered to Templates Customers only.</p>
<p style=\"font-size: 14px; font-family: verdana; color: #666666; text-align: justify;\">If you require any further assistance with customization of non-redCOMPONENT templates, or if You would prefer a quote on a uniquely designed website, template, component or plug-in - Please fill out our contact form to initiate dialogue.</p>
<p style=\"font-size: 14px; font-family: verdana; color: #666666; text-align: justify;\">&nbsp;</p>
<p style=\"font-size: 14px; font-family: verdana; color: #666666; text-align: justify;\">Regards,<br />redCOMPONENT</p>
</td>
<td style=\"width: 20px; padding-bottom: 30px;\"></td>
</tr>
<tr style=\"height: 10px;\">
<td colspan=\"3\"></td>
</tr>
</tbody>
</table>
<table style=\"width: 100%;\" cellpadding=\"0\" cellspacing=\"0\">
<tbody>
<tr>
<td style=\"height: 45px; background-color: #444544; display: block !important;\"></td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>', 1, ''),
			(22, 'Order Status Change Shipped', 'Order Status Change Shipped', 'order_status', 'S', '<table style=\"border: 1px solid #ccc;background: #fff; width:600px;\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td>\r\n\r\n<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100%;background: #f7f7f7;\">\r\n	<tbody>\r\n	<tr>\r\n		<td colspan=\"3\" style=\"height: 20px;\"></td>\r\n	</tr>\r\n	<tr>\r\n		<td style=\"width: 20px;\"></td>\r\n		<td>\r\n<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\r\n	<tr>\r\n		<td width=\"400px\">\r\n			<a href=\"http://redcomponent.com\" target=\"_blank\"> <img src=\"http://redcomponent.com/images/redcomponent_logo.png\" style=\"width: 340px; height: auto; padding-top: 20px;\" /> </a>\r\n		</td>\r\n		<td style=\"font-size: 12px; color: #878787; float: right;text-align: right;font-family: verdana;vertical-align:top;\">\r\n			{order_date}\r\n		</td>\r\n	</tr>\r\n</table>\r\n		</td>\r\n		<td style=\"width: 20px;\"></td>\r\n	</tr>\r\n	</tbody>\r\n</table>\r\n\r\n<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\r\n	<tr>\r\n		<td style=\"height: 20px; background-color: #444544;\"></td>\r\n	</tr>\r\n</table>\r\n\r\n<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100%\">\r\n<tbody>\r\n	<tr>\r\n		<td colspan=\"3\" style=\"height: 20px;\"></td>\r\n	</tr>\r\n	<tr>\r\n		<td style=\"width: 20px;\"></td>\r\n		<td>\r\n			<p style=\"font-size: 24px; font-family: verdana; color: #616161; text-align: justify;\">Order status</p>\r\n			<p style=\"font-size: 14px; font-family: verdana; color: #616161; text-align: justify;\">Your order status has changed.</p>\r\n			<p> </p>\r\n			<p> </p>\r\n			<p> </p>\r\n			<p style=\"font-size: 14px; font-family: verdana; color: #616161; text-align: justify;\">Regards,<br />redCOMPONENT</p>\r\n		</td>\r\n		<td style=\"width: 20px;\"></td>\r\n	</tr>\r\n<tr style=\"height: 160px\">\r\n    <td colspan=\"3\"></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n\r\n<table width=\"100%\" style=\"margin-top: 30px;\" cellpadding=\"0\" cellspacing=\"0\">\r\n	<tr>\r\n		<td style=\"height: 45px; background-color: #444544; display: block !important;\"></td>\r\n	</tr>\r\n</table>\r\n</td>\r\n</tr>\r\n</table>', 1, ''),
			(23, 'Order Status Change Refunded', 'Order Status Change Refunded', 'order_status', 'R', '<table style=\"border: 1px solid #ccc;background: #fff; width:600px;\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td>\r\n\r\n<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100%;background: #f7f7f7;\">\r\n	<tbody>\r\n	<tr>\r\n		<td colspan=\"3\" style=\"height: 20px;\"></td>\r\n	</tr>\r\n	<tr>\r\n		<td style=\"width: 20px;\"></td>\r\n		<td>\r\n<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\r\n	<tr>\r\n		<td width=\"400px\">\r\n			<a href=\"http://redcomponent.com\" target=\"_blank\"> <img src=\"http://redcomponent.com/images/redcomponent_logo.png\" style=\"width: 340px; height: auto; padding-top: 20px;\" /> </a>\r\n		</td>\r\n		<td style=\"font-size: 12px; color: #878787; float: right;text-align: right;font-family: verdana;vertical-align:top;\">\r\n			{order_date}\r\n		</td>\r\n	</tr>\r\n</table>\r\n		</td>\r\n		<td style=\"width: 20px;\"></td>\r\n	</tr>\r\n	</tbody>\r\n</table>\r\n\r\n<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\r\n	<tr>\r\n		<td style=\"height: 20px; background-color: #444544;\"></td>\r\n	</tr>\r\n</table>\r\n\r\n<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100%\">\r\n<tbody>\r\n	<tr>\r\n		<td colspan=\"3\" style=\"height: 20px;\"></td>\r\n	</tr>\r\n	<tr>\r\n		<td style=\"width: 20px;\"></td>\r\n		<td>\r\n			<p style=\"font-size: 24px; font-family: verdana; color: #616161; text-align: justify;\">Order status</p>\r\n			<p style=\"font-size: 14px; font-family: verdana; color: #616161; text-align: justify;\">Your order status has changed.</p>\r\n			<p> </p>\r\n			<p> </p>\r\n			<p> </p>\r\n			<p style=\"font-size: 14px; font-family: verdana; color: #616161; text-align: justify;\">Regards,<br />redCOMPONENT</p>\r\n		</td>\r\n		<td style=\"width: 20px;\"></td>\r\n	</tr>\r\n<tr style=\"height: 160px\">\r\n    <td colspan=\"3\"></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n\r\n<table width=\"100%\" style=\"margin-top: 30px;\" cellpadding=\"0\" cellspacing=\"0\">\r\n	<tr>\r\n		<td style=\"height: 45px; background-color: #444544; display: block !important;\"></td>\r\n	</tr>\r\n</table>\r\n</td>\r\n</tr>\r\n</table>', 1, ''),
			(24, 'Order Status Change Pending', 'Order Status Change Pending', 'order_status', 'P', '<table style=\"border: 1px solid #ccc;background: #fff; width:600px;\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td>\r\n\r\n<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100%;background: #f7f7f7;\">\r\n	<tbody>\r\n	<tr>\r\n		<td colspan=\"3\" style=\"height: 20px;\"></td>\r\n	</tr>\r\n	<tr>\r\n		<td style=\"width: 20px;\"></td>\r\n		<td>\r\n<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\r\n	<tr>\r\n		<td width=\"400px\">\r\n			<a href=\"http://redcomponent.com\" target=\"_blank\"> <img src=\"http://redcomponent.com/images/redcomponent_logo.png\" style=\"width: 340px; height: auto; padding-top: 20px;\" /> </a>\r\n		</td>\r\n		<td style=\"font-size: 12px; color: #878787; float: right;text-align: right;font-family: verdana;vertical-align:top;\">\r\n			{order_date}\r\n		</td>\r\n	</tr>\r\n</table>\r\n		</td>\r\n		<td style=\"width: 20px;\"></td>\r\n	</tr>\r\n	</tbody>\r\n</table>\r\n\r\n<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\r\n	<tr>\r\n		<td style=\"height: 20px; background-color: #444544;\"></td>\r\n	</tr>\r\n</table>\r\n\r\n<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100%\">\r\n<tbody>\r\n	<tr>\r\n		<td colspan=\"3\" style=\"height: 20px;\"></td>\r\n	</tr>\r\n	<tr>\r\n		<td style=\"width: 20px;\"></td>\r\n		<td>\r\n			<p style=\"font-size: 24px; font-family: verdana; color: #616161; text-align: justify;\">Order status</p>\r\n			<p style=\"font-size: 14px; font-family: verdana; color: #616161; text-align: justify;\">Your order status has changed.</p>\r\n			<p> </p>\r\n			<p> </p>\r\n			<p> </p>\r\n			<p style=\"font-size: 14px; font-family: verdana; color: #616161; text-align: justify;\">Regards,<br />redCOMPONENT</p>\r\n		</td>\r\n		<td style=\"width: 20px;\"></td>\r\n	</tr>\r\n<tr style=\"height: 160px\">\r\n    <td colspan=\"3\"></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n\r\n<table width=\"100%\" style=\"margin-top: 30px;\" cellpadding=\"0\" cellspacing=\"0\">\r\n	<tr>\r\n		<td style=\"height: 45px; background-color: #444544; display: block !important;\"></td>\r\n	</tr>\r\n</table>\r\n</td>\r\n</tr>\r\n</table>', 1, ''),
			(25, 'Order Status Change Confirmed', 'Order Status Change Confirmed', 'order_status', 'C', '<table style=\"border: 1px solid #ccc;background: #fff; width:600px;\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td>\r\n\r\n<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100%;background: #f7f7f7;\">\r\n	<tbody>\r\n	<tr>\r\n		<td colspan=\"3\" style=\"height: 20px;\"></td>\r\n	</tr>\r\n	<tr>\r\n		<td style=\"width: 20px;\"></td>\r\n		<td>\r\n<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\r\n	<tr>\r\n		<td width=\"400px\">\r\n			<a href=\"http://redcomponent.com\" target=\"_blank\"> <img src=\"http://redcomponent.com/images/redcomponent_logo.png\" style=\"width: 340px; height: auto; padding-top: 20px;\" /> </a>\r\n		</td>\r\n		<td style=\"font-size: 12px; color: #878787; float: right;text-align: right;font-family: verdana;vertical-align:top;\">\r\n			{order_date}\r\n		</td>\r\n	</tr>\r\n</table>\r\n		</td>\r\n		<td style=\"width: 20px;\"></td>\r\n	</tr>\r\n	</tbody>\r\n</table>\r\n\r\n<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\r\n	<tr>\r\n		<td style=\"height: 20px; background-color: #444544;\"></td>\r\n	</tr>\r\n</table>\r\n\r\n<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100%\">\r\n<tbody>\r\n	<tr>\r\n		<td colspan=\"3\" style=\"height: 20px;\"></td>\r\n	</tr>\r\n	<tr>\r\n		<td style=\"width: 20px;\"></td>\r\n		<td>\r\n			<p style=\"font-size: 24px; font-family: verdana; color: #616161; text-align: justify;\">Order status</p>\r\n			<p style=\"font-size: 14px; font-family: verdana; color: #616161; text-align: justify;\">Your order status has changed.</p>\r\n			<p> </p>\r\n			<p> </p>\r\n			<p> </p>\r\n			<p style=\"font-size: 14px; font-family: verdana; color: #616161; text-align: justify;\">Regards,<br />redCOMPONENT</p>\r\n		</td>\r\n		<td style=\"width: 20px;\"></td>\r\n	</tr>\r\n<tr style=\"height: 160px\">\r\n    <td colspan=\"3\"></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n\r\n<table width=\"100%\" style=\"margin-top: 30px;\" cellpadding=\"0\" cellspacing=\"0\">\r\n	<tr>\r\n		<td style=\"height: 45px; background-color: #444544; display: block !important;\"></td>\r\n	</tr>\r\n</table>\r\n</td>\r\n</tr>\r\n</table>', 1, ''),
			(26, 'Order Status Change Cancelled', 'Order Status Change Cancelled', 'order_status', 'X', '<table style=\"border: 1px solid #ccc;background: #fff; width:600px;\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tr>\r\n<td>\r\n\r\n<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100%;background: #f7f7f7;\">\r\n	<tbody>\r\n	<tr>\r\n		<td colspan=\"3\" style=\"height: 20px;\"></td>\r\n	</tr>\r\n	<tr>\r\n		<td style=\"width: 20px;\"></td>\r\n		<td>\r\n<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\r\n	<tr>\r\n		<td width=\"400px\">\r\n			<a href=\"http://redcomponent.com\" target=\"_blank\"> <img src=\"http://redcomponent.com/images/redcomponent_logo.png\" style=\"width: 340px; height: auto; padding-top: 20px;\" /> </a>\r\n		</td>\r\n		<td style=\"font-size: 12px; color: #878787; float: right;text-align: right;font-family: verdana;vertical-align:top;\">\r\n			{order_date}\r\n		</td>\r\n	</tr>\r\n</table>\r\n		</td>\r\n		<td style=\"width: 20px;\"></td>\r\n	</tr>\r\n	</tbody>\r\n</table>\r\n\r\n<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\r\n	<tr>\r\n		<td style=\"height: 20px; background-color: #444544;\"></td>\r\n	</tr>\r\n</table>\r\n\r\n<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100%\">\r\n<tbody>\r\n	<tr>\r\n		<td colspan=\"3\" style=\"height: 20px;\"></td>\r\n	</tr>\r\n	<tr>\r\n		<td style=\"width: 20px;\"></td>\r\n		<td>\r\n			<p style=\"font-size: 24px; font-family: verdana; color: #616161; text-align: justify;\">Order status</p>\r\n			<p style=\"font-size: 14px; font-family: verdana; color: #616161; text-align: justify;\">Your order status has changed.</p>\r\n			<p>�</p>\r\n			<p>�</p>\r\n			<p>�</p>\r\n			<p style=\"font-size: 14px; font-family: verdana; color: #616161; text-align: justify;\">Regards,<br />redCOMPONENT</p>\r\n		</td>\r\n		<td style=\"width: 20px;\"></td>\r\n	</tr>\r\n<tr style=\"height: 160px\">\r\n    <td colspan=\"3\"></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n\r\n<table width=\"100%\" style=\"margin-top: 30px;\" cellpadding=\"0\" cellspacing=\"0\">\r\n	<tr>\r\n		<td style=\"height: 45px; background-color: #444544; display: block !important;\"></td>\r\n	</tr>\r\n</table>\r\n</td>\r\n</tr>\r\n</table>', 1, ''),
			(27, 'catalog coupon reminder', 'catalog coupon reminder', 'catalog_coupon_reminder', '0', '<!-- 		@page { margin: 0.79in } 		P { margin-bottom: 0.08in } 	-->\r\n<p style=\"background: #ffffff none repeat scroll 0% 0%; margin-left: 0.5in; margin-bottom: 0in; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial\"><span style=\"font-family: Verdana,sans-serif;\"><span style=\"font-size: x-small;\"><span>Dear {name}. I just wish to inform you that we are currently running a campaign for all the clients who received our catalogue earlier. This means that in the next 4 days, you get </span></span></span><span style=\"color: #ff0000;\"><span style=\"font-family: Verdana,sans-serif;\"><span style=\"font-size: x-small;\"><span>{discount} </span></span></span></span><span style=\"font-family: Verdana,sans-serif;\"><span style=\"font-size: x-small;\"><span>off everything you buy, and since our products are already competitively priced, it is a really good offer. You can use the code: {coupon_code} when you order to get the discount, but remember you have 4 days from now to decide!</span></span></span></p>', 1, ''),
			(30, 'First Mail After Order Purchased', 'Mail After Order Purchased', 'first_mail_after_order_purchased', '0', '<p>Hi {name}, <br />You made an order with us 7 days ago and to show our appreciation of you as a customer we send you discount code to use the next time you visit our store</p>\r\n<p>{url}</p>\r\n<p>discount amount : {coupon_amount}</p>\r\n<p>discount coupon code : {coupon_code}</p>\r\n<p>valid upto : {coupon_duration}</p>\r\n<p>Thank you.</p>', 1, ''),
			(32, 'Second Mail After Order Purchased', 'Second Mail After Order Purchased', 'second_mail_after_order_purchased', '0', '<p>Hi {name}, <br />You made an order with us 10 days ago and to show our appreciation of you as a customer we send you discount code to use the next time you visit our store</p>\r\n<p>{url}</p>\r\n<p>discount amount : {coupon_amount}</p>\r\n<p>discount coupon code : {coupon_code}</p>\r\n<p>valid upto : {coupon_duration}</p>\r\n<p>Thank you.</p>', 1, ''),
			(33, 'Third Mail After Order Purchased', 'Third Mail After Order Purchased', 'third_mail_after_order_purchased', '0', '<p>Hi {name}, <br />You made an order with us 21 days ago and to show our appreciation of you as a customer we send you discount code to use the next time you visit our store</p>\r\n<p>{url}</p>\r\n<p>discount amount : {coupon_amount}</p>\r\n<p>discount coupon code : {coupon_code}</p>\r\n<p>valid upto : {coupon_duration}</p>\r\n<p>Thank you.</p>', 1, ''),
			(50, 'Economic Invoice', 'Invoice', 'economic_inoice', '0', '<table style=\"border: 1px solid #ccc; background: #fff; width: 600px;\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tbody>\r\n<tr>\r\n<td>\r\n<table style=\"width: 100%; background: #f7f7f7;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 20px;\"></td>\r\n<td style=\"line-height: 25px;\">\r\n<table style=\"width: 100%; padding-top: 10px;\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tbody>\r\n<tr>\r\n<td style=\"height: 94px;\" width=\"400px\"><a href=\"http://redcomponent.com\" target=\"_blank\"> <img src=\"http://redcomponent.com/images/redcomponent_logo.png\" style=\"width: 340px; height: auto;\" /> </a></td>\r\n<td style=\"font-size: 12px; color: #666666; float: right; text-align: right; font-family: verdana; vertical-align: top;\">{order_date}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n<td style=\"width: 20px;\"></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<table style=\"width: 100%;\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tbody>\r\n<tr>\r\n<td style=\"height: 20px; background-color: #444544;\"></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tbody>\r\n<tr>\r\n<td colspan=\"3\" style=\"height: 20px;\"></td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 20px;\"></td>\r\n<td style=\"padding-bottom: 30px;\">\r\n<p style=\"font-size: 14px; font-family: verdana; color: #666666; text-align: justify;\"><strong>Hi {name}</strong><br /><br />Attached is your invoice.<br /><br />Regards,<br />redCOMPONENT</p>\r\n</td>\r\n<td style=\"width: 20px;\"></td>\r\n</tr>\r\n<tr style=\"height: 160px;\">\r\n<td colspan=\"3\"></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<table style=\"width: 100%;\" cellpadding=\"0\" cellspacing=\"0\">\r\n<tbody>\r\n<tr>\r\n<td style=\"height: 45px; background-color: #444544; display: block !important;\"></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>', 1, ''),
			(34, 'Catalog Send Mail', 'Catalog Request', 'catalog', '0', '<p>Dear, <strong>{name}</strong></p>\r\n<p>We get your request for catalog. Here, you can found attached catalogs.</p>\r\n<p> </p>\r\n<p>Thank you.</p>', 1, ''),
			(54, 'My wishlist mail', 'My wishlist', 'mywishlist_mail', '0', 'hi,{name}<!--{product_loop_start}--><table style=\"width: 100%;\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\"><tbody><tr valign=\"top\"><td width=\"40%\"><div style=\"float: left; width: 195px; height: 230px; text-align: center;\">{product_thumb_image}<div>{product_name}</div><div>{product_price}</div></div></td></tr></tbody></table><!--{product_loop_end}-->Regards,{from_name}', 1, ''),
			(64, 'Order Special Discount Mail', 'Admin applied discount (special offer)', 'order_special_discount', '0', 'You got {special_discount} that is {special_discount_amount}.<table style=\"width: 100%;\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\"><tbody><tr><td><br /></td><td><table style=\"width: 100%;\" align=\"right\" border=\"0\" cellpadding=\"2\" cellspacing=\"0\"><tbody><tr><td align=\"right\">ABC Company -- abc.com</td></tr><tr><td align=\"right\">abccompany.com</td></tr><tr><td align=\"right\">Street Address</td></tr><tr><td align=\"right\">Address line 2</td></tr><tr><td align=\"right\">County</td></tr><tr><td align=\"right\">Country</td></tr><tr><td></td></tr><tr><td align=\"right\">Telephone Number : 11325-3251</td></tr><tr><td></td></tr><tr><td align=\"right\">E-mail : abccompany@abc.om</td></tr></tbody></table></td></tr><tr><td style=\"font-weight: bold\" colspan=\"2\">Some Title</td></tr><tr><td colspan=\"2\">Some Intro text...Lorem Ipsum is simply dummy 			text of the printing and typesetting industry. Lorem Ipsum has been 			the industrys standard dummy text ever since the 1500s, when an 			unknown printer took a galley of type and scrambled it to make a type 			specimen book. It has survived not only five centuries...</td></tr><tr><td colspan=\"2\"><table style=\"width: 100%;\" border=\"0\" cellpadding=\"2\" cellspacing=\"0\"><tbody><tr style=\"background-color: #cccccc\"><th align=\"left\">Order Information</th></tr><tr></tr><tr><td>Order id : {order_id}</td></tr><tr><td>Order Number : {order_number}</td></tr><tr><td>Order Date : {order_date}</td></tr><tr><td>Order Status : {order_status}</td></tr></tbody></table></td></tr><tr><td colspan=\"2\"><table style=\"width: 100%;\" border=\"0\" cellpadding=\"2\" cellspacing=\"0\"><tbody><tr style=\"background-color: #cccccc\"><th align=\"left\">Billing Address Information</th></tr><tr></tr><tr><td>{billing_address}</td></tr></tbody></table></td></tr><tr><td colspan=\"2\"><table style=\"width: 100%;\" border=\"0\" cellpadding=\"2\" cellspacing=\"0\"><tbody><tr style=\"background-color: #cccccc\"><th align=\"left\">Shipping Address Information</th></tr><tr></tr><tr><td>{shipping_address}</td></tr></tbody></table></td></tr><tr><td colspan=\"2\"><table style=\"width: 100%;\" border=\"0\" cellpadding=\"2\" cellspacing=\"0\"><tbody><tr style=\"background-color: #cccccc\"><th align=\"left\">Order Details</th></tr><tr></tr><tr><td><table style=\"width: 100%;\" border=\"0\" cellpadding=\"2\" cellspacing=\"2\"><tbody><tr><td>Product Name</td><td>Note</td><td>Price</td><td>Quantity</td><td align=\"right\">Total Price</td></tr><!--{product_loop_start}--><tr><td>{product_name}        {product_sku}</td><td>{product_wrapper}</td><td>{product_price}</td><td>{product_quantity}</td><td align=\"right\">{product_total_price}</td></tr><!--{product_loop_end}--></tbody></table></td></tr><tr><td></td></tr><tr><td><table style=\"width: 100%;\" border=\"0\" cellpadding=\"2\" cellspacing=\"2\"><tbody><tr align=\"left\"><td align=\"left\"><strong>Order Subtotal : </strong></td><td align=\"right\">{product_subtotal}</td></tr><tr align=\"left\"><td align=\"left\"><strong>TAX : </strong></td><td align=\"right\">{order_tax}</td></tr><tr align=\"left\"><td align=\"left\"><strong>Discount : </strong></td><td align=\"right\">{order_discount}</td></tr><tr align=\"left\"><td align=\"left\"><strong>{special_discount_lbl} </strong></td><td align=\"right\">{special_discount_amount}</td></tr><tr align=\"left\"><td align=\"left\"><strong>Shipping : </strong></td><td align=\"right\">{order_shipping}</td></tr><tr align=\"left\"><td colspan=\"2\" align=\"left\"><hr /></td></tr><tr align=\"left\"><td align=\"left\"><strong>Total :</strong></td><td align=\"right\">{order_total}</td></tr><tr align=\"left\"><td colspan=\"2\" align=\"left\"><hr /></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table>', 1, ''),
			(74, 'News letter ', 'News Letter confirmation', 'newsletter_confirmation', '0', '<p>hi {name},</p>\r\n<p>Confirm your News letter {link}.</p>', 1, ''),
			(94, 'Giftcard Mail', 'Giftcard Mail', 'giftcard_mail', '0', '<table><tr><td ><span>{giftcard_price_lbl}</span></td><td>{giftcard_price}</td></tr><tr><td ><span>{giftcard_reciver_name_lbl}</span></td><td>{giftcard_reciver_name}</td></tr><tr><td>{giftcard_reciver_email_lbl}</td><td>{giftcard_reciver_email}</td></tr><tr><td></td><td>{giftcard_desc}</td></tr><tr><td></td><td>{giftcard_price}</td></tr><tr><td>{giftcard_validity_from}{giftcard_validity_to}</td></tr><tr><td>{giftcard_image}</td></tr><tr><td>{giftcard_validity}</td></tr></table>', 1, ''),
			(84, 'NewsLetter cancellation ', 'NewsLetter cancellation ', 'newsletter_cancellation', '0', 'NewsLetter cancellationNewsLetter cancellation NewsLetter cancellation NewsLetter cancellationNewsLetter cancellation NewsLetter cancellation', 1, ''),
			(85, 'Invoice Mail', 'Invoice Mail', 'invoice_mail', '0', '<table style=\"border: 1px solid #ccc;background: #fff; width:600px;\">
<tr>
<td>
	<div style=\"padding: 10px 20px;\">
		<table width=\"100%\">
	<tr>
		<td width=\"400px\">
			<a href=\"http://redcomponent.com\" target=\"_blank\"> <img src=\"http://redcomponent.com/images/redcomponent_logo.png\" style=\"width: 340px; height: auto; padding-top: 20px;\" /> </a>
		</td>
		<td style=\"font-size: 12px; color: #878787; float: right;\">
			{order_date}
		</td>
	</tr>
</table>
	</div>
	<table width=\"100%\">
	<tr>
		<td style=\"height: 20px; background-color: #444544;\"></td>
	</tr>
</table>
	<div style=\"padding: 10px 20px;\">
		<p style=\"font-size: 24px; font-face: verdana; color: #616161; text-align: justify;\">Thank you for your order!</p>
		<p style=\"font-size: 14px; font-face: verdana; color: #616161; text-align: justify;\">Thank you for ordering from redCOMPONENT!<br />To download your purchased products please follow the link in the bottom of this mail.</p>
		<p style=\"font-size: 14px; font-face: verdana; color: #616161; text-align: justify;\"> </p>
		<p style=\"font-size: 14px; font-face: verdana; color: #616161; text-align: justify;\"><strong>Products ordered:</strong></p>
		<table style=\"width: 100%;\" border=\"0\">
		<tbody>
		<!--{product_loop_start}-->
		<tr style=\"border-bottom: 1px solid #F1F1F1;\">
		<td><span style=\"font-size: 14px; font-face: verdana; color: #616161; text-align: justify;\"><strong>{product_name}{without_vat}</strong></span></td>
		<td><span style=\"font-size: 14px; font-face: verdana; color: #616161; text-align: justify;\">{product_price}</span></td>
		<td><span style=\"font-size: 14px; font-face: verdana; color: #616161; text-align: justify;\">{product_quantity}</span></td>
		<td align=\"right\"><span style=\"font-size: 14px; font-face: verdana; color: #616161; text-align: right;\">{product_total_price}</span></td>
		</tr>
		<!--{product_loop_end}-->
		<tr>
		<td colspan=\"3\"><span style=\"font-size: 14px; font-face: verdana; color: #616161; text-align: justify;\"><strong>VAT</strong></span></td>
		<td align=\"right\"><span style=\"font-size: 14px; font-face: verdana; color: #616161; text-align: justify;\">{sub_total_vat}</span></td>
		</tr>
		<tr>
		<td colspan=\"3\"><span style=\"font-size: 14px; font-face: verdana; color: #616161; text-align: justify;\"><strong>Total</strong></span></td>
		<td align=\"right\"><span style=\"font-size: 14px; font-face: verdana; color: #616161; text-align: justify;\">{order_total}</span></td>
		</tr>
		</tbody>
		</table>
		<p style=\"text-align: justify;\"> </p>
		<p style=\"font-size: 14px; font-face: verdana; color: #616161; text-align: justify;\"><strong>Billing information: </strong></p>
		<div style=\"font-size: 14px; font-face: verdana; color: #616161; text-align: justify;\">{billing_address}</div>
		<p> </p>
		<p style=\"font-size: 14px; font-face: verdana; color: #616161; text-align: justify;\"><strong>Additional information:</strong></p>
		<p style=\"font-size: 14px; font-face: verdana; color: #616161; text-align: justify;\">{customer_note}</p>
		<p> </p>
		<p style=\"font-size: 14px; font-face: verdana; color: #616161; text-align: justify;\"><strong>Order details:</strong></p>
		<p style=\"font-size: 14px; font-face: verdana; color: #616161; text-align: justify;\">To see the details of your order and to download the products please click {order_detail_link}</p>
		<p style=\"font-size: 14px; font-face: verdana; color: #616161; text-align: justify;\"> </p>
		<p style=\"font-size: 14px; font-face: verdana; color: #616161; text-align: justify;\">Regards,<br />redCOMPONENT</p>
	</div>
<table width=\"100%\" style=\"margin-top: 30px;\">
	<tr>
		<td style=\"height: 45px; background-color: #444544; display: block !important;\"></td>
	</tr>
</table>
</td>
</tr>
</table>', 1, ''),
			(86, 'Product Subscription Mail', 'Mail for product Subscription ', 'subscription_renewal_mail', '0', '<h1>Product Subscription Renew</h1>\r\n<h3>Dear,</h3>\r\n<p><span>{firstname} {lastname}</span></p>\r\n<p>Your Subscription for <strong>{product_name}</strong> is going to expired on <span>{subsciption_enddate}</span></p>\r\n<h2>Your Subscription Detail is as below</h2>\r\n<table border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td>Subscribe Product :</td>\r\n<td>{product_name}</td>\r\n</tr>\r\n<tr>\r\n<td>Subscription Period :</td>\r\n<td>{subscription_period}</td>\r\n</tr>\r\n<tr>\r\n<td>Subscription Price : </td>\r\n<td>{subscription_price}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>Click here <em>{product_link}</em> and renew it</p>', 1, ''),
			(105, 'Quotation Mail', 'Quotation Mail for {quotation_id} - {quotation_status} - {quotation_total}', 'quotation_mail', '0', '<table border=\"0\" cellspacing=\"0\" cellpadding=\"5\" width=\"100%\">\r\n<tbody>\r\n<tr>\r\n<td>\r\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"2\" width=\"100%\">\r\n<tbody>\r\n<tr style=\"background-color: #cccccc\">\r\n<th align=\"left\">{quotation_information_lbl}</th>\r\n</tr>\r\n<tr>\r\n<td>{quotation_id_lbl} : {quotation_id}</td>\r\n</tr>\r\n<tr>\r\n<td>{quotation_number_lbl} : {quotation_number}</td>\r\n</tr>\r\n<tr>\r\n<td>{quotation_date_lbl} : {quotation_date}</td>\r\n</tr>\r\n<tr>\r\n<td>{quotation_status_lbl} : {quotation_status}</td>\r\n</tr>\r\n<tr>\r\n<td>{quotation_note_lbl} : {quotation_note}</td>\r\n</tr>\r\n<tr>\r\n<td>{quotation_detail_link}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td>\r\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"2\" width=\"100%\">\r\n<tbody>\r\n<tr style=\"background-color: #cccccc\">\r\n<th align=\"left\">{billing_address_information_lbl}</th>\r\n</tr>\r\n<tr>\r\n<td>{billing_address}</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td>\r\n<table border=\"0\" cellspacing=\"0\" cellpadding=\"2\" width=\"100%\">\r\n<tbody>\r\n<tr style=\"background-color: #cccccc\">\r\n<th align=\"left\">{quotation_detail_lbl}</th>\r\n</tr>\r\n<tr>\r\n<td>\r\n<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"100%\">\r\n<tbody>\r\n<tr>\r\n<td>{product_name_lbl}</td>\r\n<td>{note_lbl}</td>\r\n<td>{price_lbl}</td>\r\n<td>{quantity_lbl}</td>\r\n<td align=\"right\">{total_price_lbl}</td>\r\n</tr>\r\n<!--{product_loop_start}-->\r\n<tr>\r\n<td>{product_name}{product_s_desc}({product_number})<br />{product_userfields}<br />{product_attribute}<br />{product_accessory}</td>\r\n<td>{product_wrapper}<br />{product_thumb_image}</td>\r\n<td>{product_price}</td>\r\n<td>{product_quantity}</td>\r\n<td align=\"right\">{product_total_price}</td>\r\n</tr>\r\n<!--{product_loop_end}-->\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td></td>\r\n</tr>\r\n<tr>\r\n<td>\r\n<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"100%\">\r\n<tbody>\r\n<tr align=\"left\">\r\n<td align=\"left\"><strong>{quotation_subtotal_lbl} : </strong></td>\r\n<td align=\"right\">{quotation_subtotal}</td>\r\n</tr>\r\n<tr align=\"left\">\r\n<td colspan=\"2\" align=\"left\">\r\n<hr />\r\n</td>\r\n</tr>\r\n<tr align=\"left\">\r\n<td align=\"left\"><strong>{total_lbl} :</strong></td>\r\n<td align=\"right\">{quotation_total}</td>\r\n</tr>\r\n<tr align=\"left\">\r\n<td colspan=\"2\" align=\"left\">\r\n<hr />\r\n<br /> \r\n<hr />\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>', 1, ''),
			(150, 'Catalogue Order Mail', 'Catalogue Order Mail:', 'catalogue_order', '0', '<table style=\"width: 100%;\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\"><tbody><tr><td><br /></td><td><table style=\"width: 100%;\" align=\"right\" border=\"0\" cellpadding=\"2\" cellspacing=\"0\"><tbody><tr><td align=\"right\">ABC Company -- abc.com</td></tr><tr><td align=\"right\">abccompany.com</td></tr><tr><td align=\"right\">Street Address</td></tr><tr><td align=\"right\">Address line 2</td></tr><tr><td align=\"right\">County</td></tr><tr><td align=\"right\">Country</td></tr><tr><td></td></tr><tr><td align=\"right\">Telephone Number : 11325-3251</td></tr><tr><td></td></tr><tr><td align=\"right\">E-mail : abccompany@abc.om</td></tr></tbody></table></td></tr><tr><td style=\"font-weight: bold\" colspan=\"2\">Some Title</td></tr><tr><td colspan=\"2\">Some Intro text...Lorem Ipsum is simply dummy    text of the printing and typesetting industry. Lorem Ipsum has been    the industry\'s standard dummy text ever since the 1500s, when an    unknown printer took a galley of type and scrambled it to make a type    specimen book. It has survived not only five centuries...</td></tr><tr><td colspan=\"2\"><table style=\"width: 100%;\" border=\"0\" cellpadding=\"2\" cellspacing=\"0\"><tbody><tr style=\"background-color: #cccccc\"><th align=\"left\">{order_information_lbl}</th></tr><tr></tr><tr><td>{order_id_lbl} : {order_id}</td></tr><tr><td>{order_number_lbl} : {order_number}</td></tr><tr><td>{order_date_lbl} : {order_date}</td></tr><tr><td>{order_status_lbl} : {order_status}</td></tr></tbody></table></td></tr><tr><td colspan=\"2\"><table style=\"width: 100%;\" border=\"0\" cellpadding=\"2\" cellspacing=\"0\"><tbody><tr style=\"background-color: #cccccc\"><th align=\"left\">{billing_address_information_lbl}</th></tr><tr></tr><tr><td>{billing_address}</td></tr></tbody></table></td></tr><tr><td colspan=\"2\"><table style=\"width: 100%;\" border=\"0\" cellpadding=\"2\" cellspacing=\"0\"><tbody><tr style=\"background-color: #cccccc\"><th align=\"left\">{shipping_address_information_lbl}</th></tr><tr></tr><tr><td>{shipping_address}</td></tr></tbody></table></td></tr><tr><td colspan=\"2\"><table style=\"width: 100%;\" border=\"0\" cellpadding=\"2\" cellspacing=\"0\"><tbody><tr style=\"background-color: #cccccc\"><th align=\"left\">{order_detail_lbl}</th></tr><tr></tr><tr><td><table style=\"width: 100%;\" border=\"0\" cellpadding=\"2\" cellspacing=\"2\"><tbody><tr><td>{product_name_lbl}</td><td>{note_lbl}</td><td>{quantity_lbl}</td></tr><!--{product_loop_start}--><tr><td>{pro_name}<br /> {product_userfields}</td><td>{pro_note}</td><td>{pro_quantity}</td></tr><!--{product_loop_end}--></tbody></table></td></tr></tbody></table></td></tr><tr><td colspan=\"2\"><table style=\"width: 100%;\" border=\"0\" cellpadding=\"2\" cellspacing=\"0\"><tbody><tr style=\"background-color: #cccccc\"><th align=\"left\">Payment Status</th></tr><tr></tr><tr><td>{order_payment_status}{shipping_method_lbl}{shipping_method}</td></tr></tbody></table></td></tr><tr><td colspan=\"2\"><table style=\"width: 100%;\" border=\"0\" cellpadding=\"2\" cellspacing=\"0\"><tbody><tr style=\"background-color: #cccccc\"><th align=\"left\">Order url</th></tr><tr></tr><tr><td>{order_detail_link}</td></tr></tbody></table></td></tr></tbody></table>', 1, ''),
			(160, 'Quotation User Register Mail', 'Quotation User Register Mail:', 'quotation_user_register', '0', '<table><tr><td>Username</td><td> : </td><td>{username}</td></tr><tr><td>Password</td><td> : </td><td>{password}</td></tr><tr><td>Click here</td><td> : </td><td>{link}</td></tr></table>', 1, ''),
			(175, 'RequestTaxExemptMail', 'RequestTaxExemptMail:', 'request_tax_exempt_mail', '0', '<table style=\"width: 100%;\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\"><tbody><tr><td>Vat Number</td><td>{vat_number}</td></tr><tr><td>User Name</td><td>{username}</td></tr><tr><td>Company Name</td><td>{company_name}</td></tr><tr><td>Country</td><td>{country}</td></tr><tr><td>State</td><td>{state}</td></tr><tr><td>Phone</td><td>{phone}</td></tr><tr><td>Zipcode</td><td>{zipcode}</td></tr><tr><td>Address</td><td>{address}</td></tr><tr><td>City</td><td>{city}</td></tr></tbody></table>', 1, ''),
			(185, 'Downloadable Email', 'Link to download your newly purchased product(s)', 'downloadable_product_mail', '0', '<table style=\"border: 1px solid #ccc; background: #fff; width: 600px;\" cellpadding=\"0\" cellspacing=\"0\">
<tbody>
<tr>
<td style=\"line-height: 25px;\">
<table style=\"width: 100%; background: #f7f7f7;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
<tbody>
<tr>
<td colspan=\"3\" style=\"height: 10px;\"></td>
</tr>
<tr>
<td style=\"width: 20px;\"></td>
<td>
<table style=\"width: 100%;\" cellpadding=\"0\" cellspacing=\"0\">
<tbody>
<tr>
<td width=\"400px\"><a href=\"http://redcomponent.com\" target=\"_blank\"> <img src=\"http://redcomponent.com/images/redcomponent_logo.png\" style=\"width: 340px; height: auto; padding-top: 20px;\" /> </a></td>
<td style=\"font-size: 12px; color: #878787; float: right; font-family: verdana; vertical-align: top;\">{order_date}</td>
</tr>
</tbody>
</table>
</td>
<td style=\"width: 20px;\"></td>
</tr>
</tbody>
</table>
<table style=\"width: 100%;\" cellpadding=\"0\" cellspacing=\"0\">
<tbody>
<tr>
<td style=\"height: 20px; background-color: #444544;\"></td>
</tr>
</tbody>
</table>
<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
<tbody>
<tr>
<td colspan=\"3\" style=\"height: 20px;\"></td>
</tr>
<tr>
<td style=\"width: 20px;\"></td>
<td style=\"line-height: 25px;\">
<p style=\"font-size: 14px; font-family: verdana; color: #616161; text-align: justify;\"><strong>Dear {fullname}</strong></p>
<p style=\"font-size: 14px; font-family: verdana; color: #616161; text-align: justify;\">Thanks for your recent purchase at our store. Here are the link(s) where you can download file/product that you have purchased.</p>
<p style=\"font-size: 14px; font-family: verdana; color: #616161; text-align: justify;\">Order Date : {order_date}<br />Order # : {order_number}<br />Download Links :</p>
<p style=\"font-size: 14px; font-family: verdana; color: #616161; text-align: justify;\">{product_serial_loop_start} {product_name} - {token} <br /> {product_serial_loop_end}</p>
<p style=\"font-size: 14px; font-family: verdana; color: #616161; text-align: justify;\">Once again, thank you for shopping!</p>
</td>
<td style=\"width: 20px;\"></td>
</tr>
<tr style=\"height: 160px;\">
<td colspan=\"3\"></td>
</tr>
</tbody>
</table>
<table style=\"margin-top: 30px; width: 100%;\" cellpadding=\"0\" cellspacing=\"0\">
<tbody>
<tr>
<td style=\"height: 45px; background-color: #444544;\"></td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>', 1, ''),
			(186, 'Review', 'Review About Product', 'review_mail', '0', '<p>To Admin,</p><p>Username: {username}</p><p>Product : {product_name}</p><p>Please check this link : {product_link}</p><p>Title : {title}</p><p>Comment : {comment}</p>', 1, ''),
			(187, 'Notify Stock', 'Stock Update Notification for {product_name}', 'notify_stock_mail', '0', '<table border=\"0\" cellspacing=\"0\" cellpadding=\"5\" width=\"100%\"><tbody><tr><td><table border=\"0\" cellspacing=\"0\" cellpadding=\"2\" width=\"100%\"><tbody><tr style=\"background-color: #cccccc\"><th align=\"left\">{stocknotify_intro_text}</th></tr><tr><td>{product_detail}</td></tr></tbody></table></td></tr></tbody></table>', 1, '')";
		$db->setQuery($q);
		$db->query();

		// Start template demo content
		$redtemplate = new Redtemplate;
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
				    (372, 'quotation_cart_template', 'quotation_cart', '" . $redtemplate->getInstallSectionTemplate('quotation_cart') . "',1),
					(370, 'quotation_request_template', 'quotation_request', '" . $redtemplate->getInstallSectionTemplate('quotation_request_template') . "',1),
					(450, 'billing_template', 'billing_template', '" . $redtemplate->getInstallSectionTemplate('billing_template') . "',1),
					(451, 'shipping_template', 'shipping_template', '" . $redtemplate->getInstallSectionTemplate('shipping_template') . "',1),
					(452, 'shippment_invoice_template', 'shippment_invoice_template', '" . $redtemplate->getInstallSectionTemplate('shippment_invoice_template') . "',1),
					(460, 'private_billing_template', 'private_billing_template', '" . $redtemplate->getInstallSectionTemplate('private_billing_template') . "',1),
					(461, 'company_billing_template', 'company_billing_template', '" . $redtemplate->getInstallSectionTemplate('company_billing_template') . "',1),
	                (550, 'stock_note', 'stock_note', '" . $redtemplate->getInstallSectionTemplate('stock_note') . "',1)";
		$db->setQuery($q);
		$db->query();

		$shopper_query = "INSERT IGNORE INTO `#__redshop_shopper_group` ( `shopper_group_id` ,`shopper_group_name` ,`shopper_group_customer_type` ,`shopper_group_portal` ,`shopper_group_categories` ,`shopper_group_url` ,`shopper_group_logo` ,`shopper_group_introtext` ,`shopper_group_desc` ,`parent_id` ,`published`)
						VALUES (1 , 'Default Private', '1', '0', '', '', '', 'This is the default private shopper group.', 'This is the default private shopper group.', '0', '1')";
		$db->setQuery($shopper_query);
		$db->query();

		$shopper_query = "INSERT IGNORE INTO `#__redshop_shopper_group` ( `shopper_group_id` ,`shopper_group_name` ,`shopper_group_customer_type` ,`shopper_group_portal` ,`shopper_group_categories` ,`shopper_group_url` ,`shopper_group_logo` ,`shopper_group_introtext` ,`shopper_group_desc` ,`parent_id` ,`published`)
						VALUES (2 , 'Default Company', '0', '0', '', '', '', 'This is the default Company shopper group.', 'This is the default Company shopper group.', '0', '1')";
		$db->setQuery($shopper_query);
		$db->query();

		$shopper_query = "INSERT IGNORE INTO `#__redshop_shopper_group` ( `shopper_group_id` ,`shopper_group_name` ,`shopper_group_customer_type` ,`shopper_group_portal` ,`shopper_group_categories` ,`shopper_group_url` ,`shopper_group_logo` ,`shopper_group_introtext` ,`shopper_group_desc` ,`parent_id`, `published`)
						VALUES (3 , 'Default Tax Exempt', '0', '0', '', '', '', 'This is the Default Tax Exempt shopper group.', 'This is the Default Tax Exempt shopper group.', '0', '1')";
		$db->setQuery($shopper_query);
		$db->query();

		$vatgroup_query = "INSERT IGNORE INTO `#__redshop_tax_group` ( `tax_group_id` ,`tax_group_name` ,`published`)
						VALUES (1 , 'Default','1')";
		$db->setQuery($vatgroup_query);
		$db->query();

		$accgrp_query = "INSERT IGNORE INTO `#__redshop_economic_accountgroup` "
			. "( `accountgroup_id` ,`accountgroup_name` ,`economic_vat_account` "
			. ",`economic_nonvat_account`, `economic_discount_vat_account`, `economic_discount_nonvat_account` "
			. ", `economic_shipping_vat_account` ,`economic_shipping_nonvat_account` "
			. ",`economic_discount_product_number` ,`published`) "
			. "VALUES (1 , 'default account group', '4001', '4000', '4001', '4000', '4001', '4000', '191919', '1') ";
		$db->setQuery($accgrp_query);
		$db->query();

		$newsletter_query = "INSERT IGNORE INTO `#__redshop_newsletter` (`newsletter_id` , `name` , `subject` , `body` , `template_id` , `published`)
	                       VALUES ('1', 'News Letter Demo', 'News Letter Demo', 'User Name : {username} Email : {email}', '29', '1')";
		$db->setQuery($newsletter_query);
		$db->query();

		// TEMPLATE MOVE DB TO  FILE

		$db = JFactory::getDBO();
		$q  = "SELECT * FROM #__redshop_template";
		$db->setQuery($q);
		$list = $db->loadObjectList();

		for ($i = 0; $i < count($list); $i++)
		{
			$data = & $list[$i];

			$red_template        = new Redtemplate;
			$tname               = $data->template_name;
			$data->template_name = strtolower($data->template_name);
			$data->template_name = str_replace(" ", "_", $data->template_name);
			$tempate_file        = $red_template->getTemplatefilepath($data->template_section, $data->template_name, true);

			if (!is_file($tempate_file))
			{
				$fp = fopen($tempate_file, "w");
				fwrite($fp, $data->template_desc);
				fclose($fp);
			}

			if (is_file($tempate_file))
			{
				$template_desc = file_get_contents($tempate_file);

				if (!strstr($template_desc, '{product_subtotal}') && !strstr($template_desc, '{product_subtotal_excl_vat}'))
				{
					if (strstr($template_desc, '{subtotal}') || strstr($template_desc, '{order_subtotal}'))
					{
						$template_desc = str_replace("{subtotal}", "{product_subtotal}", $template_desc);
						$template_desc = str_replace("{order_subtotal}", "{product_subtotal}", $template_desc);
					}

					if (strstr($template_desc, '{subtotal_excl_vat}') || strstr($template_desc, '{order_subtotal_excl_vat}'))
					{
						$template_desc = str_replace("{subtotal_excl_vat}", "{product_subtotal_excl_vat}", $template_desc);
						$template_desc = str_replace("{order_subtotal_excl_vat}", "{product_subtotal_excl_vat}", $template_desc);
					}
				}

				if (!strstr($template_desc, '{shipping_excl_vat}'))
				{
					if (strstr($template_desc, '{shipping}'))
					{
						$template_desc = str_replace('{shipping}', '{shipping_excl_vat}', $template_desc);
					}

					if (strstr($template_desc, '{shipping_with_vat}'))
					{
						$template_desc = str_replace('{shipping_with_vat}', '{shipping}', $template_desc);
					}
				}

				$fp = fopen($tempate_file, "w");
				fwrite($fp, $template_desc);
				fclose($fp);

			}

			if ($data->template_id)
			{
				if ($data->template_name != $tname)
				{
					$uquery = "UPDATE `#__redshop_template` SET template_name ='" . $data->template_name . "' "
						. "WHERE template_id='" . $data->template_id . "'";
					$db->setQuery($uquery);
					$db->query();
				}
			}
		}

		$q = "select * from `#__redshop_mail` where mail_section = 'invoice_mail' or mail_section = 'order'";
		$db->setQuery($q);
		$list = $db->loadObjectList();

		for ($i = 0; $i < count($list); $i++)
		{
			$data      = & $list[$i];
			$mail_body = $data->mail_body;

			if (!strstr($mail_body, '{product_subtotal}') && !strstr($mail_body, '{product_subtotal_excl_vat}'))
			{
				if (strstr($mail_body, '{subtotal}') || strstr($mail_body, '{order_subtotal}'))
				{
					$mail_body = str_replace("{subtotal}", "{product_subtotal}", $mail_body);
					$mail_body = str_replace("{order_subtotal}", "{product_subtotal}", $mail_body);
				}

				if (strstr($mail_body, '{subtotal_excl_vat}') || strstr($mail_body, '{order_subtotal_excl_vat}'))
				{
					$mail_body = str_replace("{subtotal_excl_vat}", "{product_subtotal_excl_vat}", $mail_body);
					$mail_body = str_replace("{order_subtotal_excl_vat}", "{product_subtotal_excl_vat}", $mail_body);
				}

				// $mail_body = addslashes($mail_body);
				$uquery = "UPDATE `#__redshop_mail` SET mail_body ='$mail_body' "
					. "WHERE mail_section='" . $data->mail_section . "' AND mail_id='" . $data->mail_id . "'";
				$db->setQuery($uquery);
				$db->query();
			}

			if (!strstr($mail_body, '{shipping_excl_vat}'))
			{
				if (strstr($mail_body, '{shipping}') || strstr($mail_body, '{order_shipping}'))
				{
					$mail_body = str_replace("{shipping}", "{shipping_excl_vat}", $mail_body);
				}

				if (strstr($mail_body, '{order_shipping}'))
				{
					$mail_body = str_replace("{order_shipping}", "{shipping_excl_vat}", $mail_body);
				}

				if (strstr($mail_body, '{shipping_with_vat}'))
				{
					$mail_body = str_replace("{shipping_with_vat}", "{shipping}", $mail_body);
				}

				$mail_body = addslashes($mail_body);
				$uquery    = "UPDATE `#__redshop_mail` SET mail_body ='$mail_body' "
					. "WHERE mail_section='" . $data->mail_section . "' AND mail_id='" . $data->mail_id . "'";
				$db->setQuery($uquery);
				$db->query();

			}
		}

		// TEMPLATE MOVE DB TO  FILE END

		// For Blank component id in menu table-admin menu error solution

		$q_ext = "select * from `#__extensions` where name = 'redshop' and element = 'com_redshop' and type='component'";
		$db->setQuery($q_ext);
		$list_ext = $db->loadObjectList();
		$data     = & $list_ext[0];

		if (count($data) > 0)
		{
			$extension_id = $data->extension_id;

			if ($extension_id == "")
			{
				$extension_id = "1";
			}

			$uquery_ext = "UPDATE `#__menu` SET component_id ='.$extension_id.' "
				. " WHERE  menutype = 'main' and path = 'redshop' and type='component'";
			$db->setQuery($uquery_ext);
			$db->query();
		}

		$index_to = array("#__redshop_product" => "product_number", "#__redshop_orders" => "vm_order_number", "#__redshop_users_info" => "user_id");

		foreach ($index_to as $key => $val)
		{
			$db->setQuery('SHOW INDEXES FROM ' . $key . ' where Column_name="' . $val . '"');

			if ($redshop_users_info             = $db->query())
			{
				$redshop_users_info_index_count = $db->getNumRows($redshop_users_info);

				if ($redshop_users_info_index_count == 0)
				{
					$db->setQuery('ALTER TABLE ' . $key . ' ADD INDEX(' . $val . ')');
					$db->query();
				}
			}
		}

		?>
		<center>
			<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
				<tr>
					<td valign="top">
						<img src="<?php echo 'components/com_redshop/assets/images/261-x-88.png'; ?>" alt="redSHOP Logo"
						     align="left">
					</td>
					<td valign="top" width="100%">
						<strong>redSHOP</strong><br/>
						<font class="small">by <a href="http://www.redcomponent.com"
						                          target="_blank">redcomponent.com </a><br/></font>
						<font class="small">
							Released under the terms and conditions of the <a
								href="http://www.gnu.org/licenses/gpl-2.0.html" target="_blank">GNU General Public
								License</a>.
						</font>

						<p>Remember to check for updates on:
							<a href="http://redcomponent.com/" target="_new"><img
									src="http://images.redcomponent.com/redcomponent.jpg" alt=""></a>
						</p>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<form action="index.php" method="post" name="installDemoContent">
							<input type="button" name="save" id="installDemoContentsave" value="Configuration Wizard"
							       onclick="submitWizard('save');"/>
							<input type="button" name="content" value="install Demo Content"
							       onclick="submitWizard('content');"/>
							<input type="button" name="cancel" value="Cancel" onclick="submitWizard('cancel');"/>
							<input type="hidden" name="option" value="com_redshop">
							<input type="hidden" name="task" value="">
							<input type="hidden" name="wizard" value="1">
						</form>
						<script type="text/javascript">

							var ind = new Number(1);

							//window.onload = gotoconfigwizard();

							function gotoconfigwizard() {
								if (ind == 5) {
									submitWizard('save');
								} else {
									setTimeout("gotoconfigwizard()", 1000);
								}

								document.getElementById('installDemoContentsave').value = "Configuration Wizard " + ind++;

							}

							function submitWizard(task) {
								if (task == 'save') {
									document.installDemoContent.wizard.value = 1;
								}

								if (task == 'content') {
									document.installDemoContent.wizard.value = 0;
									document.installDemoContent.task.value = 'demoContentInsert';
								}

								if (task == 'cancel') {
									document.installDemoContent.wizard.value = 0;
								}

								document.installDemoContent.submit();
							}
						</script>
					</td>
				</tr>
			</table>
		</center>
		<?php
		// Install the sh404SEF router files
		JLoader::import('joomla.filesystem.file');
		JLoader::import('joomla.filesystem.folder');
		$sh404sefext   = JPATH_SITE . '/components/com_sh404sef/sef_ext';
		$sh404sefmeta  = JPATH_SITE . '/components/com_sh404sef/meta_ext';
		$sh404sefadmin = JPATH_SITE . '/administrator/components/com_sh404sef';
		$redadmin      = JPATH_SITE . '/administrator/components/com_redshop/extras';

		// Check if sh404SEF is installed
		if (JFolder::exists(JPATH_SITE . '/components/com_sh404sef'))
		{
			// Copy the plugin
			if (!JFile::copy($redadmin . '/sh404sef/sef_ext/com_redshop.php', $sh404sefext . '/com_redshop.php'))
			{
				echo JText::_('COM_REDSHOP_FAILED_TO_COPY_SH404SEF_EXTENSION_PLUGIN_FILE');
			}

			if (!JFile::copy($redadmin . '/sh404sef/meta_ext/com_redshop.php', $sh404sefmeta . '/com_redshop.php'))
			{
				echo JText::_('COM_REDSHOP_FAILED_TO_COPY_SH404SEF_META_PLUGIN_FILE');
			}

			if (!JFile::copy($redadmin . '/sh404sef/language/com_redshop.php', $sh404sefadmin . '/language/plugins/com_redshop.php'))
			{
				echo JText::_('COM_REDSHOP_FAILED_TO_COPY_SH404SEF_PLUGIN_LANGUAGE_FILE');
			}
		}
	}

	/**
	 * User synchronization
	 *
	 * @return  void
	 */
	private function userSynchronization()
	{
		require_once JPATH_SITE . "/administrator/components/com_redshop/helpers/redshop.cfg.php";
		require_once JPATH_SITE . '/components/com_redshop/helpers/user.php';

		JTable::addIncludePath(JPATH_SITE . '/administrator/components/com_redshop/tables');

		$userhelper = new rsUserhelper;
		$cnt        = $userhelper->userSynchronization();
	}

	/**
	 * Update/create configuration file
	 *
	 * @return  void
	 */
	private function redshopHandleCFGFile()
	{
		require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/configuration.php';

		// Include redshop.cfg.php file for cfg variables
		$cfgfile = JPATH_SITE . "/administrator/components/com_redshop/helpers/redshop.cfg.php";

		if (file_exists($cfgfile))
		{
			$configData = JFile::read($cfgfile);
			$configData = str_replace('<?php', '', $configData);
			$configData = str_replace('?>', '', $configData);
			$configData = "<?php" . $configData;

			JFile::write($cfgfile, $configData);

			require_once $cfgfile;
		}

		$Redconfiguration = new Redconfiguration;

		// Declaration
		$cfgarr = array();

		/*
		 * Check before update $cfgarr
		 * for variable is defined or not?
		 *
		 * Example:
		 * if (!defined("TESTING"))
		 * {
		 * 		$cfgarr["TESTING"] = 3.14;
		 * }
		 */
		if (!defined("UPDATE_MAIL_ENABLE"))
		{
			$cfgarr["UPDATE_MAIL_ENABLE"] = 1;
		}

		if (!defined("DISCOUNT_TYPE"))
		{
			$cfgarr["DISCOUNT_TYPE"] = 3;
		}

		if (!defined("ENABLE_BACKENDACCESS"))
		{
			$cfgarr["ENABLE_BACKENDACCESS"] = 0;
		}

		if (!defined("WANT_TO_SHOW_ATTRIBUTE_IMAGE_INCART"))
		{
			$cfgarr["WANT_TO_SHOW_ATTRIBUTE_IMAGE_INCART"] = 0;
		}

		if (!defined("ADDTOCART_BEHAVIOUR"))
		{
			$cfgarr["ADDTOCART_BEHAVIOUR"] = 1;
		}

		if (!defined("SHOPPER_GROUP_DEFAULT_UNREGISTERED") && defined("SHOPPER_GROUP_DEFAULT_PRIVATE"))
		{
			$cfgarr["SHOPPER_GROUP_DEFAULT_UNREGISTERED"] = SHOPPER_GROUP_DEFAULT_PRIVATE;
		}

		if (!defined("INDIVIDUAL_ADD_TO_CART_ENABLE"))
		{
			$cfgarr["INDIVIDUAL_ADD_TO_CART_ENABLE"] = 0;
		}

		if (!defined("PRODUCT_ADDIMG_IS_LIGHTBOX"))
		{
			$cfgarr["PRODUCT_ADDIMG_IS_LIGHTBOX"] = 1;
		}

		if (!defined("POSTDK_CUSTOMER_NO"))
		{
			$cfgarr["POSTDK_CUSTOMER_NO"] = 1;
		}

		if (!defined("POSTDK_INTEGRATION"))
		{
			$cfgarr["POSTDK_INTEGRATION"] = 0;
		}

		if (!defined("POSTDK_CUSTOMER_PASSWORD"))
		{
			$cfgarr["POSTDK_CUSTOMER_PASSWORD"] = '';
		}

		if (!defined("ENABLE_SEF_NUMBER_NAME"))
		{
			$cfgarr["ENABLE_SEF_NUMBER_NAME"] = '';
		}

		if (!defined("UNIT_DECIMAL"))
		{
			$cfgarr["UNIT_DECIMAL"] = '';
		}

		if (!defined("ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC"))
		{
			$cfgarr["ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC"] = 0;
		}

		if (!defined("CATEGORY_DESC_MAX_CHARS"))
		{
			$cfgarr["CATEGORY_DESC_MAX_CHARS"] = '';
		}

		if (!defined("CATEGORY_DESC_END_SUFFIX"))
		{
			$cfgarr["CATEGORY_DESC_END_SUFFIX"] = '';
		}

		if (!defined("DEFAULT_QUOTATION_MODE_PRE"))
		{
			$cfgarr["DEFAULT_QUOTATION_MODE_PRE"] = '0';
		}

		if (!defined("SHOW_PRICE_PRE"))
		{
			$cfgarr["SHOW_PRICE_PRE"] = '1';
		}

		if (!defined("QUICKLINK_ICON"))
		{
			$cfgarr["QUICKLINK_ICON"] = '';
		}

		if (!defined("DISPLAY_STOCKROOM_ATTRIBUTES"))
		{
			$cfgarr["DISPLAY_STOCKROOM_ATTRIBUTES"] = '';
		}

		if (!defined("DISPLAY_NEW_ORDERS"))
		{
			$cfgarr["DISPLAY_NEW_ORDERS"] = '0';
		}

		if (!defined("DISPLAY_NEW_CUSTOMERS"))
		{
			$cfgarr["DISPLAY_NEW_CUSTOMERS"] = '0';
		}

		if (!defined("DISPLAY_STATISTIC"))
		{
			$cfgarr["DISPLAY_STATISTIC"] = '0';
		}

		if (!defined("EXPAND_ALL"))
		{
			$cfgarr["EXPAND_ALL"] = '0';
		}

		if (!defined("NOOF_THUMB_FOR_SCROLLER"))
		{
			$cfgarr["NOOF_THUMB_FOR_SCROLLER"] = '3';
		}

		if (!defined("POSTDANMARK_ADDRESS"))
		{
			$cfgarr["POSTDANMARK_ADDRESS"] = 'address';
		}

		if (!defined("POSTDANMARK_POSTALCODE"))
		{
			$cfgarr["POSTDANMARK_POSTALCODE"] = '13256';
		}

		if (!defined("SEND_CATALOG_REMINDER_MAIL"))
		{
			$cfgarr["SEND_CATALOG_REMINDER_MAIL"] = '0';
		}

		if (!defined("AJAX_CART_DISPLAY_TIME"))
		{
			$cfgarr["AJAX_CART_DISPLAY_TIME"] = '3000';
		}

		if (!defined("PAYMENT_CALCULATION_ON"))
		{
			$cfgarr["PAYMENT_CALCULATION_ON"] = 'subtotal';
		}

		if (!defined("IMAGE_QUALITY_OUTPUT"))
		{
			$cfgarr["IMAGE_QUALITY_OUTPUT"] = '100';
		}

		if (!defined("DEFAULT_NEWSLETTER"))
		{
			$cfgarr["DEFAULT_NEWSLETTER"] = '1';
		}

		if (!defined("DETAIL_ERROR_MESSAGE_ON"))
		{
			$cfgarr["DETAIL_ERROR_MESSAGE_ON"] = '1';
		}

		if (!defined("MANUFACTURER_TITLE_MAX_CHARS"))
		{
			$cfgarr["MANUFACTURER_TITLE_MAX_CHARS"] = '';
		}

		if (!defined("MANUFACTURER_TITLE_END_SUFFIX"))
		{
			$cfgarr["MANUFACTURER_TITLE_END_SUFFIX"] = '';
		}

		if (!defined("WRITE_REVIEW_IS_LIGHTBOX"))
		{
			$cfgarr["WRITE_REVIEW_IS_LIGHTBOX"] = '0';
		}

		if (!defined("SPECIAL_DISCOUNT_MAIL_SEND"))
		{
			$cfgarr["SPECIAL_DISCOUNT_MAIL_SEND"] = '1';
		}

		if (!defined("WATERMARK_PRODUCT_ADDITIONAL_IMAGE"))
		{
			$cfgarr["WATERMARK_PRODUCT_ADDITIONAL_IMAGE"] = '0';
		}

		if (!defined("ACCESSORY_AS_PRODUCT_IN_CART_ENABLE"))
		{
			$cfgarr["ACCESSORY_AS_PRODUCT_IN_CART_ENABLE"] = '0';
		}

		if (!defined("ATTRIBUTE_SCROLLER_THUMB_WIDTH"))
		{
			$cfgarr["ATTRIBUTE_SCROLLER_THUMB_WIDTH"] = '50';
		}

		if (!defined("ATTRIBUTE_SCROLLER_THUMB_HEIGHT"))
		{
			$cfgarr["ATTRIBUTE_SCROLLER_THUMB_HEIGHT"] = '50';
		}

		if (!defined("NOOF_SUBATTRIB_THUMB_FOR_SCROLLER"))
		{
			$cfgarr["NOOF_SUBATTRIB_THUMB_FOR_SCROLLER"] = '3';
		}

		if (!defined("COMPARE_PRODUCT_THUMB_WIDTH"))
		{
			$cfgarr["COMPARE_PRODUCT_THUMB_WIDTH"] = '70';
		}

		if (!defined("COMPARE_PRODUCT_THUMB_HEIGHT"))
		{
			$cfgarr["COMPARE_PRODUCT_THUMB_HEIGHT"] = '70';
		}

		if (!defined("CATEGORY_TITLE_MAX_CHARS"))
		{
			$cfgarr["CATEGORY_TITLE_MAX_CHARS"] = '';
		}

		if (!defined("CATEGORY_TITLE_END_SUFFIX"))
		{
			$cfgarr["CATEGORY_TITLE_END_SUFFIX"] = '';
		}

		if (!defined("PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE"))
		{
			$cfgarr["PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE"] = '';
		}

		if (!defined("USE_ENCODING"))
		{
			$cfgarr["USE_ENCODING"] = '0';
		}

		if (!defined("CREATE_ACCOUNT_CHECKBOX"))
		{
			$cfgarr["CREATE_ACCOUNT_CHECKBOX"] = '0';
		}

		if (!defined("SHOW_QUOTATION_PRICE"))
		{
			$cfgarr["SHOW_QUOTATION_PRICE"] = '0';
		}

		if (!defined("CHILDPRODUCT_DROPDOWN"))
		{
			$cfgarr["CHILDPRODUCT_DROPDOWN"] = 'product_name';
		}

		if (!defined("ENABLE_ADDRESS_DETAIL_IN_SHIPPING"))
		{
			$cfgarr["ENABLE_ADDRESS_DETAIL_IN_SHIPPING"] = '0';
		}

		if (!defined("PURCHASE_PARENT_WITH_CHILD"))
		{
			$cfgarr["PURCHASE_PARENT_WITH_CHILD"] = '0';
		}

		if (!defined("CALCULATION_PRICE_DECIMAL"))
		{
			$cfgarr["CALCULATION_PRICE_DECIMAL"] = '4';
		}

		if (!defined("REQUESTQUOTE_IMAGE"))
		{
			$cfgarr["REQUESTQUOTE_IMAGE"] = 'requestquote.gif';
		}

		if (!defined("REQUESTQUOTE_BACKGROUND"))
		{
			$cfgarr["REQUESTQUOTE_BACKGROUND"] = 'requestquotebg.jpg';
		}

		if (!defined("SHOW_PRODUCT_DETAIL"))
		{
			$cfgarr["SHOW_PRODUCT_DETAIL"] = 1;
		}

		if (!defined("WEBPACK_ENABLE_EMAIL_TRACK"))
		{
			$cfgarr["WEBPACK_ENABLE_EMAIL_TRACK"] = 1;
		}

		if (!defined("WEBPACK_ENABLE_SMS"))
		{
			$cfgarr["WEBPACK_ENABLE_SMS"] = 1;
		}

		if (!defined("REQUIRED_VAT_NUMBER"))
		{
			$cfgarr["REQUIRED_VAT_NUMBER"] = 1;
		}

		if (!defined("ACCESSORY_PRODUCT_IN_LIGHTBOX"))
		{
			$cfgarr["ACCESSORY_PRODUCT_IN_LIGHTBOX"] = 0;
		}

		if (!defined("PRODUCT_PREVIEW_IMAGE_WIDTH"))
		{
			$cfgarr["PRODUCT_PREVIEW_IMAGE_WIDTH"] = 100;
		}

		if (!defined("PRODUCT_PREVIEW_IMAGE_HEIGHT"))
		{
			$cfgarr["PRODUCT_PREVIEW_IMAGE_HEIGHT"] = 100;
		}

		if (!defined("CATEGORY_PRODUCT_PREVIEW_IMAGE_WIDTH"))
		{
			$cfgarr["CATEGORY_PRODUCT_PREVIEW_IMAGE_WIDTH"] = 100;
		}

		if (!defined("CATEGORY_PRODUCT_PREVIEW_IMAGE_HEIGHT"))
		{
			$cfgarr["CATEGORY_PRODUCT_PREVIEW_IMAGE_HEIGHT"] = 100;
		}

		if (!defined("DISPLAY_OUT_OF_STOCK_ATTRIBUTE_DATA"))
		{
			$cfgarr["DISPLAY_OUT_OF_STOCK_ATTRIBUTE_DATA"] = 1;
		}

		if (!defined("SEND_MAIL_TO_CUSTOMER"))
		{
			$cfgarr["SEND_MAIL_TO_CUSTOMER"] = 1;
		}

		if (!defined("AJAX_DETAIL_BOX_WIDTH"))
		{
			$cfgarr["AJAX_DETAIL_BOX_WIDTH"] = 500;
		}

		if (!defined("AJAX_DETAIL_BOX_HEIGHT"))
		{
			$cfgarr["AJAX_DETAIL_BOX_HEIGHT"] = 600;
		}

		if (!defined("AJAX_BOX_WIDTH"))
		{
			$cfgarr["AJAX_BOX_WIDTH"] = 500;
		}

		if (!defined("AJAX_BOX_HEIGHT"))
		{
			$cfgarr["AJAX_BOX_HEIGHT"] = 150;
		}

		if (!defined("ORDER_MAIL_AFTER"))
		{
			$cfgarr["ORDER_MAIL_AFTER"] = 0;
		}

		$Redconfiguration->manageCFGFile($cfgarr);

		// End
	}

	/**
	 * Module installer
	 *
	 * @param   string  $module  Module name
	 * @param   string  $source  Source install folder
	 *
	 * @return  boolean
	 */
	private function redshopInstallModule($module, $source)
	{
		$path = $source . '/plugins/' . $module;

		$installer = new JInstaller;
		$result    = $installer->install($path);

		if ($result)
		{
			// Get a db instance
			$db    = JFactory::getDBO();
			$query = "UPDATE #__extensions SET position='icon', ordering=9, enabled=1 WHERE element=" . $db->Quote($module);
			$db    = JFactory::getDBO();
			$db->setQuery($query);
			$db->query();
		}
		else
		{
			$app = JFactory::getApplication();
			$app->enqueueMessage('Error installing redSHOP module: ' . $module);
		}

		return $result;
	}

	/**
	 * Get the common JInstaller instance used to install all the extensions
	 *
	 * @return JInstaller The JInstaller object
	 */
	public function getInstaller()
	{
		if (is_null($this->installer))
		{
			$this->installer = new JInstaller;
		}

		return $this->installer;
	}

	/**
	 * Install the package libraries
	 *
	 * @param   object  $parent  class calling this method
	 *
	 * @return  void
	 */
	private function installLibraries($parent)
	{
		// Required objects
		$installer = $this->getInstaller();
		$manifest  = $parent->get('manifest');
		$src       = $parent->getParent()->getPath('source');

		if ($nodes = $manifest->libraries->library)
		{
			foreach ($nodes as $node)
			{
				$extName = $node->attributes()->name;
				$extPath = $src . '/libraries/' . $extName;
				$result  = 0;

				if (is_dir($extPath))
				{
					$result = $installer->install($extPath);
				}

				$this->_storeStatus('libraries', array('name' => $extName, 'result' => $result));
			}
		}
	}

	/**
	 * Install the package modules
	 *
	 * @param   object  $parent  class calling this method
	 *
	 * @return  void
	 */
	private function installModules($parent)
	{
		// Required objects
		$installer = $this->getInstaller();
		$manifest  = $parent->get('manifest');
		$src       = $parent->getParent()->getPath('source');

		if ($nodes = $manifest->modules->module)
		{
			foreach ($nodes as $node)
			{
				$extName   = $node->attributes()->name;
				$extClient = $node->attributes()->client;
				$extPath   = $src . '/modules/' . $extClient . '/' . $extName;
				$result    = 0;

				if (is_dir($extPath))
				{
					$result = $installer->install($extPath);
				}

				$this->_storeStatus('modules', array('name' => $extName, 'client' => $extClient, 'result' => $result));
			}
		}
	}

	/**
	 * Install the package libraries
	 *
	 * @param   object  $parent  class calling this method
	 *
	 * @return  void
	 */
	private function installPlugins($parent)
	{
		// Required objects
		$installer = $this->getInstaller();
		$manifest  = $parent->get('manifest');
		$src       = $parent->getParent()->getPath('source');

		if ($nodes = $manifest->plugins->plugin)
		{
			foreach ($nodes as $node)
			{
				$extName  = $node->attributes()->name;
				$extGroup = $node->attributes()->group;
				$extPath  = $src . '/plugins/' . $extGroup . '/' . $extName;
				$result   = 0;

				if (is_dir($extPath))
				{
					$result = $installer->install($extPath);
				}

				// Store the result to show install summary later
				$this->_storeStatus('plugins', array('name' => $extName, 'group' => $extGroup, 'result' => $result));

				// Enable the installed plugin
				if ($result)
				{
					$db = JFactory::getDBO();
					$query = $db->getQuery(true);
					$query->update($db->quoteName("#__extensions"));
					$query->set("enabled=1");
					$query->where("type='plugin'");
					$query->where("element=" . $db->quote($extName));
					$query->where("folder=" . $db->quote($extGroup));
					$db->setQuery($query);
					$db->query();
				}
			}
		}
	}

	/**
	 * Uninstall the package libraries
	 *
	 * @param   object  $parent  class calling this method
	 *
	 * @return  void
	 */
	private function uninstallLibraries($parent)
	{
		// Required objects
		$installer = $this->getInstaller();
		$manifest  = $parent->get('manifest');
		$src       = $parent->getParent()->getPath('source');

		if ($nodes = $manifest->libraries->library)
		{
			foreach ($nodes as $node)
			{
				$extName = $node->attributes()->name;
				$extPath = $src . '/libraries/' . $extName;
				$result  = 0;

				$db = JFactory::getDBO();
				$query = $db->getQuery(true)
					->select('extension_id')
					->from($db->quoteName("#__extensions"))
					->where("type='library'")
					->where("element=" . $db->quote($extName));

				$db->setQuery($query);

				if ($extId = $db->loadResult())
				{
					$result = $installer->uninstall('library', $extId);
				}

				// Store the result to show install summary later
				$this->_storeStatus('libraries', array('name' => $extName, 'result' => $result));
			}
		}
	}

	/**
	 * Uninstall the package modules
	 *
	 * @param   object  $parent  class calling this method
	 *
	 * @return  void
	 */
	private function uninstallModules($parent)
	{
		// Required objects
		$installer = $this->getInstaller();
		$manifest  = $parent->get('manifest');
		$src       = $parent->getParent()->getPath('source');

		if ($nodes = $manifest->modules->module)
		{
			foreach ($nodes as $node)
			{
				$extName   = $node->attributes()->name;
				$extClient = $node->attributes()->client;
				$extPath   = $src . '/modules/' . $extClient . '/' . $extName;
				$result    = 0;

				$db = JFactory::getDBO();
				$query = $db->getQuery(true)
					->select('extension_id')
					->from($db->quoteName("#__extensions"))
					->where("type='module'")
					->where("element=" . $db->quote($extName));

				$db->setQuery($query);

				if ($extId = $db->loadResult())
				{
					$result = $installer->uninstall('module', $extId);
				}

				// Store the result to show install summary later
				$this->_storeStatus('modules', array('name' => $extName, 'client' => $extClient, 'result' => $result));
			}
		}
	}

	/**
	 * Uninstall the package plugins
	 *
	 * @param   object  $parent  class calling this method
	 *
	 * @return  void
	 */
	private function uninstallPlugins($parent)
	{
		// Required objects
		$installer = $this->getInstaller();
		$manifest  = $parent->get('manifest');
		$src       = $parent->getParent()->getPath('source');

		if ($nodes = $manifest->plugins->plugin)
		{
			foreach ($nodes as $node)
			{
				$extName  = $node->attributes()->name;
				$extGroup = $node->attributes()->group;
				$extPath  = $src . '/plugins/' . $extGroup . '/' . $extName;
				$result   = 0;

				$db = JFactory::getDBO();
				$query = $db->getQuery(true)
					->select('extension_id')
					->from($db->quoteName("#__extensions"))
					->where("type='plugin'")
					->where("element=" . $db->quote($extName))
					->where("folder=" . $db->quote($extGroup));

				$db->setQuery($query);

				if ($extId = $db->loadResult())
				{
					$result = $installer->uninstall('plugin', $extId);
				}

				// Store the result to show install summary later
				$this->_storeStatus('plugins', array('name' => $extName, 'group' => $extGroup, 'result' => $result));
			}
		}
	}

	/**
	 * Store the result of trying to install an extension
	 *
	 * @param   string  $type    Type of extension (libraries, modules, plugins)
	 * @param   array   $status  The status info
	 *
	 * @return void
	 */
	private function _storeStatus($type, $status)
	{
		// Initialise status object if needed
		if (is_null($this->status))
		{
			$this->status = new stdClass;
		}

		// Initialise current status type if needed
		if (!isset($this->status->{$type}))
		{
			$this->status->{$type} = array();
		}

		// Insert the status
		array_push($this->status->{$type}, $status);
	}
}
