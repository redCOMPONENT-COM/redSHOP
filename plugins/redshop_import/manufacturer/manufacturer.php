<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Plugin\AbstractImportPlugin;

JLoader::import('redshop.library');

/**
 * Plugin redSHOP Import Manufacturer
 *
 * @since  1.0
 */
class PlgRedshop_ImportManufacturer extends AbstractImportPlugin
{
	/**
	 * @var string
	 */
	protected $primaryKey = 'id';

	/**
	 * @var string
	 */
	protected $nameKey = 'name';

	/**
	 * Event run when user load config for export this data.
	 *
	 * @return  string
	 *
	 * @since  1.0.0
	 */
	public function onAjaxManufacturer_Config()
	{
		\Redshop\Helper\Ajax::validateAjaxRequest();

		\Redshop\Ajax\Response::getInstance()->respond();
	}

	/**
	 * Event run when run importing.
	 *
	 * @return  mixed
	 *
	 * @since  1.0.0
	 */
	public function onAjaxManufacturer_Import()
	{
		\Redshop\Helper\Ajax::validateAjaxRequest();

		$input           = JFactory::getApplication()->input;
		$this->encoding  = $input->getString('encoding', 'UTF-8');
		$this->separator = $input->getString('separator', ',');
		$this->folder    = $input->getCmd('folder', '');

		return json_encode($this->importing());
	}

	/**
	 * Method for get table object.
	 *
	 * @return  \JTable|boolean
	 *
	 * @since   1.0.0
	 */
	public function getTable()
	{
		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/tables');

		return JTable::getInstance('Manufacturer', 'RedshopTable');
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
		$isNew = false;
		$db    = $this->db;

		if (array_key_exists($this->primaryKey, $data) && $data[$this->primaryKey])
		{
			$isNew = $table->load($data[$this->primaryKey]);
		}

		if (!$table->bind($data))
		{
			return false;
		}

		if ((!$isNew && !$db->insertObject('#__redshop_manufacturer', $table, $this->primaryKey)) || !$table->store())
		{
			return false;
		}

		if (empty($data['product_id']))
		{
			return true;
		}

		// Update product reference
		$productIds = explode('|', $data['product_id']);

		$query = $db->getQuery(true)
			->update($db->qn('#__redshop_product'))
			->set($db->qn('manufacturer_id') . ' = ' . $db->quote($table->{$this->primaryKey}))
			->where($db->qn('product_id') . ' IN(' . implode(',', $productIds) . ')');

		return $db->setQuery($query)->execute();
	}
}
