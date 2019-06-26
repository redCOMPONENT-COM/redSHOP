<?php
/**
 * @package    RedSHOP.Installer
 *
 * @copyright  Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
	 * @var  object
	 */
	public $status = null;

	/**
	 * The common JInstaller instance used to install all the extensions
	 *
	 * @var  object
	 */
	public $installer = null;

	/**
	 * Install type
	 *
	 * @var   string
	 */
	protected $type = null;

	/**
	 * Method to install the component
	 *
	 * @param   object $parent Class calling this method
	 *
	 * @return  void
	 */
	public function install($parent)
	{
		// Install extensions
		$this->installLibraries($parent);
		$this->installModules($parent);
		$this->installPlugins($parent);
	}

	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @param   string $type   Type of method
	 * @param   object $parent Parent class call this method
	 *
	 * @return  void
	 *
	 * @since   2.0.0
	 *
	 * @throws  Exception
	 */
	public function postflight($type, $parent)
	{
		// Respond json for ajax request and redirect with standard request
		if(
			isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
			strcasecmp($_SERVER['HTTP_X_REQUESTED_WITH'], 'xmlhttprequest') == 0
		){

			$response = new JResponseJson(array('redirect' =>'index.php?option=com_redshop&view=install&install_type=' . $type));

			header('Content-type: application/json');
			echo $response;

			JFactory::getApplication()->close();
		}

		JFactory::getApplication()->redirect('index.php?option=com_redshop&view=install&install_type=' . $type);
	}

	/**
	 * method to uninstall the component
	 *
	 * @param   object $parent Class calling this method
	 *
	 * @return  void
	 */
	public function uninstall($parent)
	{
		// Uninstall extensions
		$this->uninstallPlugins($parent);
		$this->uninstallModules($parent);
		$this->uninstallLibraries($parent);
	}

	/**
	 * Method to update the component
	 *
	 * @param   object $parent Class calling this method
	 *
	 * @return  void
	 */
	public function update($parent)
	{
		$this->installLibraries($parent);
		$this->installModules($parent);
		$this->installPlugins($parent);
	}

	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @param   object $type   Type of change (install, update or discover_install)
	 * @param   object $parent Class calling this method
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 */
	public function preflight($type, $parent)
	{
		$this->type = $type;

		$this->implementProcedure();

		if ($type == 'update' || $type == 'discover_install')
		{
			if (!class_exists('RedshopHelperJoomla'))
			{
				require_once __DIR__ . '/libraries/redshop/helper/joomla.php';
			}

			// Store redSHOP old version.
			JFactory::getApplication()->setUserState('redshop.old_version', RedshopHelperJoomla::getManifestValue('version'));
		}
	}

	/**
	 * Get the common JInstaller instance used to install all the extensions
	 *
	 * @return JInstaller The JInstaller object
	 */
	public function getInstaller()
	{
		$this->installer = new JInstaller;

		return $this->installer;
	}

	/**
	 * Install the package libraries
	 *
	 * @param   object $parent Class calling this method
	 *
	 * @return  void
	 */
	protected function installLibraries($parent)
	{
		// Required objects
		$manifest = $parent->get('manifest');
		$src      = $parent->getParent()->getPath('source');

		if ($nodes = $manifest->libraries->library)
		{
			$installer = $this->getInstaller();

			foreach ($nodes as $node)
			{
				$extName = $node->attributes()->name;
				$extPath = $src . '/libraries/' . $extName;

				// Standard install
				if (is_dir($extPath))
				{
					$installer->install($extPath);
				}
				// Discover install
				elseif ($extId = $this->searchExtension($extName, 'library', '-1'))
				{
					$installer->discover_install($extId);
				}
			}
		}
	}

	/**
	 * Install the package modules
	 *
	 * @param   object $parent Class calling this method
	 *
	 * @return  void
	 */
	protected function installModules($parent)
	{
		// Required objects
		$manifest = $parent->get('manifest');
		$src      = $parent->getParent()->getPath('source');

		if ($nodes = $manifest->modules->module)
		{
			foreach ($nodes as $node)
			{
				$extName   = (string) $node->attributes()->name;
				$extClient = (string) $node->attributes()->client;
				$extPath   = $src . '/modules/' . $extClient . '/' . $extName;

				if (is_dir($extPath))
				{
					$this->getInstaller()->install($extPath);
				}
				// Discover install
				elseif ($extId = $this->searchExtension($extName, 'module', '-1'))
				{
					$this->getInstaller()->discover_install($extId);
				}
			}
		}
	}

	/**
	 * Install the package libraries
	 *
	 * @param   object $parent Class calling this method
	 *
	 * @return  void
	 */
	protected function installPlugins($parent)
	{
		// Required objects
		$manifest = $parent->get('manifest');
		$src      = $parent->getParent()->getPath('source');

		if ($nodes = $manifest->plugins->plugin)
		{
			$installer = $this->getInstaller();

			foreach ($nodes as $node)
			{
				$extName  = (string) $node->attributes()->name;
				$extGroup = (string) $node->attributes()->group;
				$extPath  = $src . '/plugins/' . $extGroup . '/' . $extName;
				$result   = 0;

				// Install or upgrade plugin
				if (is_dir($extPath))
				{
					$installer->setAdapter('plugin');
					$result = $installer->install($extPath);
				}
				// Discover install
				elseif ($extId = $this->searchExtension($extName, 'plugin', '-1', $extGroup))
				{
					$result = $installer->discover_install($extId);
				}

				// We'll not enable plugin for update case
				if ($this->type != 'update' && $result)
				{
					/*
					 * For another rest type cases
					 * Do not change plugin state if it's installed
					 * If plugin is installed successfully and it didn't exist before we enable it.
					 */
					$this->enablePlugin($extName, $extGroup);
				}

				// Force to enable redSHOP - System plugin by anyways
				$this->enablePlugin('redshop', 'system');

				// Force to enable redSHOP PDF - TcPDF plugin by anyways
				$this->enablePlugin('tcpdf', 'redshop_pdf');

				// Force to enable redSHOP Export - Attribute plugin by anyways
				$this->enablePlugin('attribute', 'redshop_export');

				// Force to enable redSHOP Export - Category plugin by anyways
				$this->enablePlugin('category', 'redshop_export');

				// Force to enable redSHOP Export - Field plugin by anyways
				$this->enablePlugin('field', 'redshop_export');

				// Force to enable redSHOP Export - Manufacturer plugin by anyways
				$this->enablePlugin('manufacturer', 'redshop_export');

				// Force to enable redSHOP Export - Newsletter Subscriber plugin by anyways
				$this->enablePlugin('newsletter_subscriber', 'redshop_export');

				// Force to enable redSHOP Export - Product plugin by anyways
				$this->enablePlugin('product', 'redshop_export');

				// Force to enable redSHOP Export - Product Stockroom Data plugin by anyways
				$this->enablePlugin('product_stockroom_data', 'redshop_export');

				// Force to enable redSHOP Export - Related Product plugin by anyways
				$this->enablePlugin('related_product', 'redshop_export');

				// Force to enable redSHOP Export - Shipping Address plugin by anyways
				$this->enablePlugin('shipping_address', 'redshop_export');

				// Force to enable redSHOP Export - Shopper Group Attribute Price plugin by anyways
				$this->enablePlugin('shopper_group_attribute_price', 'redshop_export');

				// Force to enable redSHOP Export - Shopper Group Product Price plugin by anyways
				$this->enablePlugin('shopper_group_product_price', 'redshop_export');

				// Force to enable redSHOP Export - User plugin by anyways
				$this->enablePlugin('user', 'redshop_export');

				// Force to enable redSHOP Import - Attribute plugin by anyways
				$this->enablePlugin('attribute', 'redshop_import');

				// Force to enable redSHOP Import - Category plugin by anyways
				$this->enablePlugin('category', 'redshop_import');

				// Force to enable redSHOP Import - Field plugin by anyways
				$this->enablePlugin('field', 'redshop_import');

				// Force to enable redSHOP Import - Manufacturer plugin by anyways
				$this->enablePlugin('manufacturer', 'redshop_import');

				// Force to enable redSHOP Import - Newsletter Subscriber plugin by anyways
				$this->enablePlugin('newsletter_subscriber', 'redshop_import');

				// Force to enable redSHOP Import - Product plugin by anyways
				$this->enablePlugin('product', 'redshop_import');

				// Force to enable redSHOP Import - Product Stockroom Data plugin by anyways
				$this->enablePlugin('product_stockroom_data', 'redshop_import');

				// Force to enable redSHOP Import - Related Product plugin by anyways
				$this->enablePlugin('related_product', 'redshop_import');

				// Force to enable redSHOP Import - Shipping Address plugin by anyways
				$this->enablePlugin('shipping_address', 'redshop_import');

				// Force to enable redSHOP Import - Shopper Group Attribute Price plugin by anyways
				$this->enablePlugin('shopper_group_attribute_price', 'redshop_import');

				// Force to enable redSHOP Import - Shopper Group Product Price plugin by anyways
				$this->enablePlugin('shopper_group_product_price', 'redshop_import');

				// Force to enable redSHOP Import - User plugin by anyways
				$this->enablePlugin('user', 'redshop_import');
			}
		}
	}

	/**
	 * Method for enable plugins
	 *
	 * @param   string $extName  Plugin name
	 * @param   string $extGroup Plugin group
	 * @param   int    $state    State of plugins
	 *
	 * @return mixed
	 */
	protected function enablePlugin($extName, $extGroup, $state = 1)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->update($db->qn("#__extensions"))
			->set("enabled = " . (int) $state)
			->where('type = ' . $db->quote('plugin'))
			->where('element = ' . $db->quote($extName))
			->where('folder = ' . $db->quote($extGroup));

		return $db->setQuery($query)->execute();
	}

	/**
	 * Uninstall the package libraries
	 *
	 * @param   object $parent Class calling this method
	 *
	 * @return  void
	 */
	protected function uninstallLibraries($parent)
	{
		// Required objects
		$manifest = $parent->get('manifest');

		if ($nodes = $manifest->libraries->library)
		{
			foreach ($nodes as $node)
			{
				$extName = (string) $node->attributes()->name;

				if ($extId = $this->searchExtension($extName, 'library'))
				{
					$this->getInstaller()->uninstall('library', $extId);
				}
			}
		}
	}

	/**
	 * Uninstall the package modules
	 *
	 * @param   object $parent Class calling this method
	 *
	 * @return  void
	 */
	protected function uninstallModules($parent)
	{
		// Required objects
		$manifest = $parent->get('manifest');

		if ($nodes = $manifest->modules->module)
		{
			foreach ($nodes as $node)
			{
				$extName   = (string) $node->attributes()->name;
				$extClient = (string) $node->attributes()->client;

				if ($extId = $this->searchExtension($extName, 'module'))
				{
					$this->getInstaller()->uninstall('module', $extId);
				}
			}
		}
	}

	/**
	 * Uninstall the package plugins
	 *
	 * @param   object $parent Class calling this method
	 *
	 * @return  void
	 */
	protected function uninstallPlugins($parent)
	{
		// Required objects
		$manifest = $parent->get('manifest');

		if ($nodes = $manifest->plugins->plugin)
		{
			$installer = $this->getInstaller();

			foreach ($nodes as $node)
			{
				$extName  = (string) $node->attributes()->name;
				$extGroup = (string) $node->attributes()->group;

				if ($extId = $this->searchExtension($extName, 'plugin', null, $extGroup))
				{
					$installer->uninstall('plugin', $extId);
				}
			}
		}
	}

	/**
	 * Search a extension in the database
	 *
	 * @param   string $element Extension technical name/alias
	 * @param   string $type    Type of extension (component, file, language, library, module, plugin)
	 * @param   string $state   State of the searched extension
	 * @param   string $folder  Folder name used mainly in plugins
	 *
	 * @return  integer           Extension identifier
	 */
	protected function searchExtension($element, $type, $state = null, $folder = null)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('extension_id')
			->from($db->quoteName("#__extensions"))
			->where("type = " . $db->quote($type))
			->where("element = " . $db->quote($element));

		if (!is_null($state))
		{
			$query->where("state = " . (int) $state);
		}

		if (!is_null($folder))
		{
			$query->where("folder = " . $db->quote($folder));
		}

		$db->setQuery($query);

		return $db->loadResult();
	}

	/**
	 * Method for implement procedure function for MySQL server only
	 *
	 * @return  void
	 *
	 * @since   2.1.0
	 */
	protected function implementProcedure()
	{
		$this->procedureRemoveColumn();
		$this->procedureUpdateColumn();
		$this->procedureIndexRemove();
		$this->procedureIndexAdd();
		$this->procedureUniqueIndexAdd();
		$this->procedureFulltextIndexAdd();
		$this->procedureConstraintRemove();
		$this->procedureConstraintUpdate();
		$this->procedurePrimaryRemove();
		$this->procedurePrimaryAdd();
	}

	/**
	 * Method for implement procedure "redSHOP_Column_Update"
	 *
	 * @return  void
	 *
	 * @since   2.1.0
	 */
	protected function procedureUpdateColumn()
	{
		$db = JFactory::getDbo();

		$query = "DROP PROCEDURE IF EXISTS " . $db->qn('redSHOP_Column_Update');

		$db->setQuery($query)->execute();

		$query = "CREATE PROCEDURE " . $db->qn("redSHOP_Column_Update") . "(
			IN " . $db->qn('tableName') . " VARCHAR(50),
			IN " . $db->qn('columnName') . " VARCHAR(50),
			IN " . $db->qn('newColumnName') . " VARCHAR(50),
			IN " . $db->qn('columnDetail') . " VARCHAR(255)
			)
			LANGUAGE SQL
			NOT DETERMINISTIC
			CONTAINS SQL
			COMMENT " . $db->quote('Procedure for use in redSHOP to update / add column to table avoid unexpected errors.') . "
			BEGIN
				SET tableName = REPLACE(tableName, " . $db->quote('#__') . ", " . $db->quote(JFactory::getConfig()->get('dbprefix')) . ") ;
				SET columnDetail = REPLACE(columnDetail, " . $db->quote('#__') . ", " . $db->quote(JFactory::getConfig()->get('dbprefix')) . ") ;
				set @ColOldExist = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE COLUMN_NAME=columnName AND TABLE_NAME=tableName AND table_schema = DATABASE());
				set @ColNewExist = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE COLUMN_NAME=newColumnName AND TABLE_NAME=tableName AND table_schema = DATABASE());
				IF (@ColOldExist = 0 AND @ColNewExist = 0) THEN
					/* Both column doesn't exist. Just add column */
					set @StatementToExecute = concat('ALTER TABLE `',DATABASE(),'`.`',tableName,'` ADD COLUMN `',newColumnName,'` ',columnDetail);
					prepare DynamicStatement from @StatementToExecute ;
					execute DynamicStatement ;
					deallocate prepare DynamicStatement ;
				ELSEIF (@ColOldExist = 1 AND @ColNewExist = 0) THEN
					/* Old column exist. New column not exist. Change column */
					set @StatementToExecute = concat('ALTER TABLE `',DATABASE(),'`.`',tableName,'` CHANGE `',columnName,'` ','`',newColumnName,'` ',columnDetail);
					prepare DynamicStatement from @StatementToExecute ;
					execute DynamicStatement ;
					deallocate prepare DynamicStatement ;
				ELSEIF (@ColOldExist = 0 AND @ColNewExist = 1) THEN
						/* Old column not exist. New column exist. Update column */
						set @StatementToExecute = concat('ALTER TABLE `',DATABASE(),'`.`',tableName,'` CHANGE `',newColumnName,'` ','`',newColumnName,'` ',columnDetail);
						prepare DynamicStatement from @StatementToExecute ;
						execute DynamicStatement ;
						deallocate prepare DynamicStatement ;
				ELSE
					/* Old column exist. New column exist. */
					IF (columnName <> newColumnName) THEN
						/* Old column is different with new column and both exist => The old column had to be remove */
						set @StatementToExecute = concat('ALTER TABLE `',DATABASE(),'`.`',tableName,'` DROP COLUMN `',columnName,'`');
						prepare DynamicStatement from @StatementToExecute ;
						execute DynamicStatement ;
						deallocate prepare DynamicStatement ;
					END IF ;
					
					/* Update structure of new column column */
					set @StatementToExecute = concat('ALTER TABLE `',DATABASE(),'`.`',tableName,'` CHANGE `',newColumnName,'` ','`',newColumnName,'` ',columnDetail);
					prepare DynamicStatement from @StatementToExecute ;
					execute DynamicStatement ;
					deallocate prepare DynamicStatement ;
				END IF;
			END";

		$db->setQuery($query)->execute();
	}

	/**
	 * Method for implement procedure "redSHOP_Column_Remove"
	 *
	 * @return  void
	 *
	 * @since   2.1.0
	 */
	protected function procedureRemoveColumn()
	{
		$db = JFactory::getDbo();

		$query = "DROP PROCEDURE IF EXISTS " . $db->qn('redSHOP_Column_Remove');

		$db->setQuery($query)->execute();

		$query = "CREATE PROCEDURE " . $db->qn("redSHOP_Column_Remove") . "(
			IN " . $db->qn('tableName') . " VARCHAR(50),
			IN " . $db->qn('columnName') . " VARCHAR(50)
			)
			LANGUAGE SQL
			NOT DETERMINISTIC
			CONTAINS SQL
			COMMENT " . $db->quote('Procedure for use in redSHOP to remove column to table avoid unexpected errors.') . "
			BEGIN
				SET tableName = REPLACE(tableName, " . $db->quote('#__') . ", " . $db->quote(JFactory::getConfig()->get('dbprefix')) . ") ;
				IF ((SELECT COUNT(*) FROM information_schema.COLUMNS WHERE COLUMN_NAME=columnName AND TABLE_NAME=tableName AND table_schema = DATABASE()) >= 1)
				THEN
					set @StatementToExecute = concat('ALTER TABLE `',DATABASE(),'`.`',tableName,'` DROP COLUMN `',columnName,'`');
					prepare DynamicStatement from @StatementToExecute ;
					execute DynamicStatement ;
					deallocate prepare DynamicStatement ;
				END IF ;
			END";

		$db->setQuery($query)->execute();
	}

	/**
	 * Method for implement procedure "redSHOP_Index_Remove"
	 *
	 * @return  void
	 *
	 * @since   2.1.0
	 */
	protected function procedureIndexRemove()
	{
		$db = JFactory::getDbo();

		$query = "DROP PROCEDURE IF EXISTS " . $db->qn('redSHOP_Index_Remove');

		$db->setQuery($query)->execute();

		$query = "CREATE PROCEDURE " . $db->qn("redSHOP_Index_Remove") . "(
			IN " . $db->qn('tableName') . " VARCHAR(50),
			IN " . $db->qn('indexName') . " VARCHAR(50)
			)
			LANGUAGE SQL
			NOT DETERMINISTIC
			CONTAINS SQL
			COMMENT " . $db->quote('Procedure for use in redSHOP to remove index from table avoid unexpected errors.') . "
			BEGIN
				SET tableName = REPLACE(tableName, " . $db->quote('#__') . ", " . $db->quote(JFactory::getConfig()->get('dbprefix')) . ") ;
				SET indexName = REPLACE(indexName, " . $db->quote('#__') . ", " . $db->quote(JFactory::getConfig()->get('dbprefix')) . ") ;
				IF ((SELECT COUNT(*) AS index_exists FROM information_schema.statistics WHERE TABLE_SCHEMA = DATABASE() and table_name = tableName AND index_name = indexName) >= 1)
				THEN
					set @StatementToExecute = concat('ALTER TABLE `',DATABASE(),'`.`',tableName,'` DROP INDEX `',indexName,'`');
					prepare DynamicStatement from @StatementToExecute ;
					execute DynamicStatement ;
					deallocate prepare DynamicStatement ;
				END IF ;
			END";

		$db->setQuery($query)->execute();
	}

	/**
	 * Method for implement procedure "redSHOP_Index_Add"
	 *
	 * @return  void
	 *
	 * @since   2.1.0
	 */
	protected function procedureIndexAdd()
	{
		$db = JFactory::getDbo();

		$query = "DROP PROCEDURE IF EXISTS " . $db->qn('redSHOP_Index_Add');

		$db->setQuery($query)->execute();

		$query = "CREATE PROCEDURE " . $db->qn("redSHOP_Index_Add") . "(
			IN " . $db->qn('tableName') . " VARCHAR(50),
			IN " . $db->qn('indexName') . " VARCHAR(50),
			IN " . $db->qn('indexData') . " VARCHAR(255)
			)
			LANGUAGE SQL
			NOT DETERMINISTIC
			CONTAINS SQL
			COMMENT " . $db->quote('Procedure for use in redSHOP to Add index to table avoid unexpected errors..') . "
			BEGIN
				SET tableName = REPLACE(tableName, " . $db->quote('#__') . ", " . $db->quote(JFactory::getConfig()->get('dbprefix')) . ") ;
				SET indexName = REPLACE(indexName, " . $db->quote('#__') . ", " . $db->quote(JFactory::getConfig()->get('dbprefix')) . ") ;
				SET indexData = REPLACE(indexData, " . $db->quote('#__') . ", " . $db->quote(JFactory::getConfig()->get('dbprefix')) . ") ;
				CALL redSHOP_Index_Remove(tableName, indexName) ;
				set @StatementToExecute = concat('ALTER TABLE `',DATABASE(),'`.`',tableName,'` ADD INDEX `',indexName,'` ',indexData);
				prepare DynamicStatement from @StatementToExecute ;
				execute DynamicStatement ;
				deallocate prepare DynamicStatement ;
			END";

		$db->setQuery($query)->execute();
	}

	/**
	 * Method for implement procedure "redSHOP_Index_Unique_Add"
	 *
	 * @return  void
	 *
	 * @since   2.1.0
	 */
	protected function procedureUniqueIndexAdd()
	{
		$db = JFactory::getDbo();

		$query = "DROP PROCEDURE IF EXISTS " . $db->qn('redSHOP_Index_Unique_Add');

		$db->setQuery($query)->execute();

		$query = "CREATE PROCEDURE " . $db->qn("redSHOP_Index_Unique_Add") . "(
			IN " . $db->qn('tableName') . " VARCHAR(50),
			IN " . $db->qn('indexName') . " VARCHAR(50),
			IN " . $db->qn('indexData') . " VARCHAR(255)
			)
			LANGUAGE SQL
			NOT DETERMINISTIC
			CONTAINS SQL
			COMMENT " . $db->quote('Procedure for use in redSHOP to Add Unique Index to table avoid unexpected errors..') . "
			BEGIN
				SET tableName = REPLACE(tableName, " . $db->quote('#__') . ", " . $db->quote(JFactory::getConfig()->get('dbprefix')) . ") ;
				SET indexName = REPLACE(indexName, " . $db->quote('#__') . ", " . $db->quote(JFactory::getConfig()->get('dbprefix')) . ") ;
				SET indexData = REPLACE(indexData, " . $db->quote('#__') . ", " . $db->quote(JFactory::getConfig()->get('dbprefix')) . ") ;
				CALL redSHOP_Index_Remove(tableName, indexName);
				set @StatementToExecute = concat('ALTER TABLE `',DATABASE(),'`.`',tableName,'` ADD UNIQUE INDEX `',indexName,'` ',indexData);
				prepare DynamicStatement from @StatementToExecute ;
				execute DynamicStatement ;
				deallocate prepare DynamicStatement ;
			END";

		$db->setQuery($query)->execute();
	}

	/**
	 * Method for implement procedure "redSHOP_Index_Fulltext_Add"
	 *
	 * @return  void
	 *
	 * @since   2.1.0
	 */
	protected function procedureFulltextIndexAdd()
	{
		$db = JFactory::getDbo();

		$query = "DROP PROCEDURE IF EXISTS " . $db->qn('redSHOP_Index_Fulltext_Add');

		$db->setQuery($query)->execute();

		$query = "CREATE PROCEDURE " . $db->qn("redSHOP_Index_Fulltext_Add") . "(
			IN " . $db->qn('tableName') . " VARCHAR(50),
			IN " . $db->qn('indexName') . " VARCHAR(50),
			IN " . $db->qn('indexData') . " VARCHAR(255)
			)
			LANGUAGE SQL
			NOT DETERMINISTIC
			CONTAINS SQL
			COMMENT " . $db->quote('Procedure for use in redSHOP to Add Unique Index to table avoid unexpected errors..') . "
			BEGIN
				SET tableName = REPLACE(tableName, " . $db->quote('#__') . ", " . $db->quote(JFactory::getConfig()->get('dbprefix')) . ") ;
				SET indexName = REPLACE(indexName, " . $db->quote('#__') . ", " . $db->quote(JFactory::getConfig()->get('dbprefix')) . ") ;
				SET indexData = REPLACE(indexData, " . $db->quote('#__') . ", " . $db->quote(JFactory::getConfig()->get('dbprefix')) . ") ;
				CALL redSHOP_Index_Remove(tableName, indexName);
				set @StatementToExecute = concat('ALTER TABLE `',DATABASE(),'`.`',tableName,'` ADD FULLTEXT INDEX `',indexName,'` ',indexData);
				prepare DynamicStatement from @StatementToExecute ;
				execute DynamicStatement ;
				deallocate prepare DynamicStatement ;
			END";

		$db->setQuery($query)->execute();
	}

	/**
	 * Method for implement procedure "redSHOP_Constraint_Remove"
	 *
	 * @return  void
	 *
	 * @since   2.1.0
	 */
	protected function procedureConstraintRemove()
	{
		$db = JFactory::getDbo();

		$query = "DROP PROCEDURE IF EXISTS " . $db->qn('redSHOP_Constraint_Remove');

		$db->setQuery($query)->execute();

		$query = "CREATE PROCEDURE " . $db->qn("redSHOP_Constraint_Remove") . "(
			IN " . $db->qn('tableName') . " VARCHAR(50),
			IN " . $db->qn('refName') . " VARCHAR(50)
			)
			LANGUAGE SQL
			NOT DETERMINISTIC
			CONTAINS SQL
			COMMENT " . $db->quote('Procedure for use in redSHOP to Add Constraint (Foreign Key) to table avoid unexpected errors..') . "
			BEGIN
				SET tableName = REPLACE(tableName, " . $db->quote('#__') . ", " . $db->quote(JFactory::getConfig()->get('dbprefix')) . ") ;
				SET refName = REPLACE(refName, " . $db->quote('#__') . ", " . $db->quote(JFactory::getConfig()->get('dbprefix')) . ") ;
				IF ((SELECT COUNT(*) AS constraint_exists FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() and TABLE_NAME = tableName AND CONSTRAINT_NAME = refName AND CONSTRAINT_TYPE = 'FOREIGN KEY') >= 1)
				THEN
					SET FOREIGN_KEY_CHECKS = 0;
					set @StatementToExecute = concat('ALTER TABLE `',DATABASE(),'`.`',tableName,'` DROP FOREIGN KEY `',refName,'`');
					prepare DynamicStatement from @StatementToExecute ;
					execute DynamicStatement ;
					deallocate prepare DynamicStatement ;
					SET FOREIGN_KEY_CHECKS = 1;
				END IF ;
			END";

		$db->setQuery($query)->execute();
	}

	/**
	 * Method for implement procedure "redSHOP_Constraint_Update"
	 *
	 * @return  void
	 *
	 * @since   2.1.0
	 */
	protected function procedureConstraintUpdate()
	{
		$db = JFactory::getDbo();

		$query = "DROP PROCEDURE IF EXISTS " . $db->qn('redSHOP_Constraint_Update');

		$db->setQuery($query)->execute();

		$query = "CREATE PROCEDURE " . $db->qn("redSHOP_Constraint_Update") . "(
			IN " . $db->qn('tableName') . " VARCHAR(50),
			IN " . $db->qn('constraintName') . " VARCHAR(50),
			IN " . $db->qn('columnName') . " VARCHAR(50),
			IN " . $db->qn('tableRef') . " VARCHAR(50),
			IN " . $db->qn('columnRef') . " VARCHAR(50),
			IN " . $db->qn('onUpdateAction') . " ENUM('RESTRICT','CASCADE','SET NULL','NO ACTION'),
			IN " . $db->qn('onDeleteAction') . " ENUM('RESTRICT','CASCADE','SET NULL','NO ACTION')
			)
			LANGUAGE SQL
			NOT DETERMINISTIC
			CONTAINS SQL
			COMMENT " . $db->quote('Procedure for use in redSHOP to Update/Create Constraint (Foreign Key) to table avoid unexpected errors..') . "
			BEGIN
				SET tableName = REPLACE(tableName, " . $db->quote('#__') . ", " . $db->quote(JFactory::getConfig()->get('dbprefix')) . ") ;
				SET constraintName = REPLACE(constraintName, " . $db->quote('#__') . ", " . $db->quote(JFactory::getConfig()->get('dbprefix')) . ") ;
				SET columnName = REPLACE(columnName, " . $db->quote('#__') . ", " . $db->quote(JFactory::getConfig()->get('dbprefix')) . ") ;
				SET tableRef = REPLACE(tableRef, " . $db->quote('#__') . ", " . $db->quote(JFactory::getConfig()->get('dbprefix')) . ") ;
				SET columnRef = REPLACE(columnRef, " . $db->quote('#__') . ", " . $db->quote(JFactory::getConfig()->get('dbprefix')) . ") ;
				CALL redSHOP_Constraint_Remove(tableName, constraintName);
				SET FOREIGN_KEY_CHECKS = 0;
				SET @StatementToExecute = concat('ALTER TABLE `',DATABASE(),'`.`',tableName,'` ADD CONSTRAINT `',constraintName,'` FOREIGN KEY (`',columnName,'`) REFERENCES `',tableRef,'` (`',columnRef,'`) ON UPDATE ',onUpdateAction,' ON DELETE ',onDeleteAction);
				prepare DynamicStatement from @StatementToExecute ;
				execute DynamicStatement ;
				deallocate prepare DynamicStatement ;
				SET FOREIGN_KEY_CHECKS = 1;
			END";

		$db->setQuery($query)->execute();
	}

	/**
	 * Method for implement procedure "redSHOP_Primary_Remove"
	 *
	 * @return  void
	 *
	 * @since   2.1.0
	 */
	protected function procedurePrimaryRemove()
	{
		$db = JFactory::getDbo();

		$query = "DROP PROCEDURE IF EXISTS " . $db->qn('redSHOP_Primary_Remove');

		$db->setQuery($query)->execute();

		$query = "CREATE PROCEDURE " . $db->qn("redSHOP_Primary_Remove") . "(
			IN " . $db->qn('tableName') . " VARCHAR(50)
			)
			LANGUAGE SQL
			NOT DETERMINISTIC
			CONTAINS SQL
			COMMENT " . $db->quote('Procedure for use in redSHOP to remove primary key from table avoid unexpected errors.') . "
			BEGIN
				SET tableName = REPLACE(tableName, " . $db->quote('#__') . ", " . $db->quote(JFactory::getConfig()->get('dbprefix')) . ") ;
				IF ((SELECT COUNT(*) AS index_exists FROM information_schema.table_constraints WHERE TABLE_SCHEMA = DATABASE() and table_name = tableName AND constraint_name = " . $db->quote('PRIMARY') . ") >= 1)
				THEN
					set @StatementToExecute = concat('ALTER TABLE `',DATABASE(),'`.`',tableName,'` DROP PRIMARY KEY');
					prepare DynamicStatement from @StatementToExecute ;
					execute DynamicStatement ;
					deallocate prepare DynamicStatement ;
				END IF ;
			END";

		$db->setQuery($query)->execute();
	}

	/**
	 * Method for implement procedure "redSHOP_Primary_Add"
	 *
	 * @return  void
	 *
	 * @since   2.1.0
	 */
	protected function procedurePrimaryAdd()
	{
		$db = JFactory::getDbo();

		$query = "DROP PROCEDURE IF EXISTS " . $db->qn('redSHOP_Primary_Add');

		$db->setQuery($query)->execute();

		$query = "CREATE PROCEDURE " . $db->qn("redSHOP_Primary_Add") . "(
			IN " . $db->qn('tableName') . " VARCHAR(50),
			IN " . $db->qn('keyData') . " VARCHAR(255)
			)
			LANGUAGE SQL
			NOT DETERMINISTIC
			CONTAINS SQL
			COMMENT " . $db->quote('Procedure for use in redSHOP to Add primary to table avoid unexpected errors..') . "
			BEGIN
				SET tableName = REPLACE(tableName, " . $db->quote('#__') . ", " . $db->quote(JFactory::getConfig()->get('dbprefix')) . ") ;
				SET keyData = REPLACE(keyData, " . $db->quote('#__') . ", " . $db->quote(JFactory::getConfig()->get('dbprefix')) . ") ;
				CALL redSHOP_Primary_Remove(tableName) ;
				set @StatementToExecute = concat('ALTER TABLE `',DATABASE(),'`.`',tableName,'` ADD PRIMARY KEY(',keyData,')');
				prepare DynamicStatement from @StatementToExecute ;
				execute DynamicStatement ;
				deallocate prepare DynamicStatement ;
			END";

		$db->setQuery($query)->execute();
	}
}
