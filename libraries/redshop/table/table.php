<?php
/**
 * @package     RedSHOP
 * @subpackage  Base
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

use Redshop\Table\AbstractTable;

/**
 * redSHOP Base Table
 *
 * @package     Redshop
 * @subpackage  Base
 * @since       2.0.0.3
 */
class RedshopTable extends AbstractTable
{
	/**
	 * Prefix to add to log files
	 *
	 * @var  string
	 */
	protected $logPrefix = 'redshop';

	/**
	 * Get a table instance.
	 *
	 * @param   string  $name    The table name
	 * @param   mixed   $client  The client. null = auto, 1 = admin, 0 = frontend
	 * @param   array   $config  An optional array of configuration
	 * @param   string  $option  Component name, use for call table from another extension
	 *
	 * @return  RedshopTable     The table
	 *
	 * @throws  InvalidArgumentException
	 */
	public static function getAutoInstance($name, $client = null, array $config = array(), $option = 'auto')
	{
		if ($option === 'auto')
		{
			$option = JFactory::getApplication()->input->getString('option', '');

			// Add com_ to the element name if not exist
			$option = (strpos($option, 'com_') === 0 ? '' : 'com_') . $option;

			if ($option == 'com_installer')
			{
				$installer = JInstaller::getInstance();
				$option = $installer->manifestClass->getElement($installer);
			}
		}

		$componentName = ucfirst(strtolower(substr($option, 4)));
		$prefix = $componentName . 'Table';

		if (is_null($client))
		{
			$client = (int) JFactory::getApplication()->isAdmin();
		}

		// Admin
		if ($client === 1)
		{
			JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/' . $option . '/tables');
		}

		// Site
		elseif ($client === 0)
		{
			JTable::addIncludePath(JPATH_SITE . '/components/' . $option . '/tables');
		}

		else
		{
			throw new InvalidArgumentException(
				sprintf('Cannot instanciate the table %s in component %s. Invalid client %s.', $name, $option, $client)
			);
		}

		$table = self::getInstance($name, $prefix, $config);

		if (!$table instanceof JTable)
		{
			throw new InvalidArgumentException(
				sprintf('Cannot instanciate the table %s in component %s from client %s.', $name, $option, $client)
			);
		}

		return $table;
	}

	/**
	 * Get a backend table instance
	 *
	 * @param   string  $name    The table name
	 * @param   array   $config  An optional array of configuration
	 * @param   string  $option  Component name, use for call table from another extension
	 *
	 * @return  RedshopTable  The table
	 */
	public static function getAdminInstance($name, array $config = array(), $option = 'auto')
	{
		return self::getAutoInstance($name, 1, $config, $option);
	}

	/**
	 * Get a frontend table instance
	 *
	 * @param   string  $name    The table name
	 * @param   array   $config  An optional array of configuration
	 * @param   string  $option  Component name, use for call table from another extension
	 *
	 * @return  RedshopTable  The table
	 */
	public static function getFrontInstance($name, array $config = array(), $option = 'auto')
	{
		return self::getAutoInstance($name, 0, $config, $option);
	}

	/**
	 * Validate that the primary key has been set.
	 *
	 * @return  boolean  True if the primary key(s) have been set.
	 *
	 * @since   1.5.2
	 */
	public function hasPrimaryKey()
	{
		// For Joomla 3.2+ a native method has been provided
		if (method_exists(get_parent_class(), 'hasPrimaryKey'))
		{
			return parent::hasPrimaryKey();
		}

		// Otherwise, it checks if the only key field compatible for older Joomla versions is set or not
		if (isset($this->_tbl_key) && !empty($this->_tbl_key) && empty($this->{$this->_tbl_key}))
		{
			return false;
		}

		return true;
	}

	/**
	 * Delete one or more registers
	 *
	 * @param   string/array  $pk  Array of ids or ids comma separated
	 *
	 * @return  boolean  Deleted successfully?
	 */
	protected function doDelete($pk = null)
	{
		// Skip this check if this option has been set to true. In case use in CLI or API
		if ($this->getOption('skipCheckPermissionOnDelete') === true)
		{
			return parent::doDelete();
		}

		// Check permission of delete
		if (!JFactory::getUser()->authorise($this->getInstanceName() . '.delete', 'com_redshop.backend'))
		{
			$this->setError(JText::_('COM_REDSHOP_ACCESS_ERROR_NOT_HAVE_PERMISSION'));

			return false;
		}

		return parent::doDelete();
	}
}
