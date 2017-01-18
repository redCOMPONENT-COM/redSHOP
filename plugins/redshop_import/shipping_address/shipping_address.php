<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Plugin\AbstractImportPlugin;

JLoader::import('redshop.library');

/**
 * Plugin redSHOP Import Shipping address
 *
 * @since  1.0
 */
class PlgRedshop_ImportShipping_address extends AbstractImportPlugin
{
	/**
	 * @var string
	 */
	protected $primaryKey = 'users_info_id';

	/**
	 * @var string
	 */
	protected $nameKey = 'user_email';

	/**
	 * Event run when user load config for export this data.
	 *
	 * @return  string
	 *
	 * @since  1.0.0
	 */
	public function onAjaxShipping_address_Config()
	{
		RedshopHelperAjax::validateAjaxRequest();

		return '';
	}

	/**
	 * Event run when run importing.
	 *
	 * @return  mixed
	 *
	 * @since  1.0.0
	 */
	public function onAjaxShipping_address_Import()
	{
		RedshopHelperAjax::validateAjaxRequest();

		$input           = JFactory::getApplication()->input;
		$this->encoding  = $input->getString('encoding', 'UTF-8');
		$this->separator = $input->getString('separator', ',');
		$this->folder    = $input->getCmd('folder', '');

		return json_encode($this->importing());
	}

	/**
	 * Method for get table object.
	 *
	 * @return  \JTable
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getTable()
	{
		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/tables');

		return JTable::getInstance('User_detail', 'Table');
	}

	/**
	 * Process import data.
	 *
	 * @param   \JTable  $table  Header array
	 * @param   array    $data   Data array
	 *
	 * @return  boolean
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function processImport($table, $data)
	{
		$isNew = false;
		$db    = $this->db;

		if (!empty($data['email']))
		{
			$data['user_email'] = $data['email'];
		}

		$data['address_type'] = 'ST';
		$data['shopper_group_id'] = 1;

		if (array_key_exists($this->primaryKey, $data) && $data[$this->primaryKey])
		{
			$isNew = $table->load($data[$this->primaryKey]);
		}

		if (!$table->bind($data))
		{
			return false;
		}

		if ((!$isNew && !$db->insertObject('#__redshop_users_info', $table, $this->primaryKey)) || !$table->store())
		{
			return false;
		}

		return true;
	}
}
