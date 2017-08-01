<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
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
class PlgRedshop_ImportShipping_Address extends AbstractImportPlugin
{
	/**
	 * @var string
	 */
	protected $primaryKey = 'users_info_id';

	/**
	 * @var string
	 */
	protected $nameKey = 'username';

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
	 * @since   1.0.0
	 */
	public function getTable()
	{
		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/tables');

		return JTable::getInstance('User_detail', 'Table');
	}

	/**
	 * Process mapping data.
	 *
	 * @param   array  $header  Header array
	 * @param   array  $data    Data array
	 *
	 * @return  array           Mapping data.
	 *
	 * @since   1.0.0
	 */
	public function processMapping($header, $data)
	{
		$data = parent::processMapping($header, $data);

		$data['user_email'] = empty($data['user_email']) ? $data['email'] : $data['user_email'];

		return $data;
	}

	/**
	 * Process import data.
	 *
	 * @param   \JTable  $table  Header array
	 * @param   array    $data   Data array
	 *
	 * @return  boolean
	 *
	 * @since   1.0.0
	 */
	public function processImport($table, $data)
	{
		if (empty($data['username']))
		{
			return false;
		}

		$db = $this->db;

		$query = $db->getQuery(true)
			->select($db->qn('id'))
			->from($db->qn('#__users'))
			->where($db->qn('username') . ' = ' . $db->quote($data['username']));

		$data['user_id'] = $db->setQuery($query)->loadResult();

		if (!$data['user_id'])
		{
			return false;
		}

		$data['address_type'] = 'ST';

		if (!$table->bind($data))
		{
			return false;
		}

		return $db->insertObject('#__redshop_users_info', $table, $this->primaryKey);
	}
}
