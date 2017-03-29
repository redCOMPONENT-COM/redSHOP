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
 * Plugin redSHOP Import Product stockroom data
 *
 * @since  1.0
 */
class PlgRedshop_ImportProduct_Stockroom_Data extends AbstractImportPlugin
{
	/**
	 * @var string
	 */
	protected $nameKey = 'product_name';

	/**
	 * Event run when user load config for export this data.
	 *
	 * @return  string
	 *
	 * @since  1.0.0
	 */
	public function onAjaxProduct_stockroom_data_Config()
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
	public function onAjaxProduct_stockroom_data_Import()
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

		return JTable::getInstance('Stockroom_detail', 'Table');
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
		$db        = $this->db;
		$productId = $this->getProductIdByNumber($data['product_number']);

		if (!$productId)
		{
			return false;
		}

		$query = $db->getQuery(true)
			->select($db->qn('stockroom_id'))
			->from($db->qn('#__redshop_stockroom'))
			->where($db->qn('stockroom_name') . ' = ' . $db->quote($data['stockroom_name']));

		$stockroomId = $db->setQuery($query)->loadResult();

		if (!$stockroomId)
		{
			return false;
		}

		$productStockroom                   = new stdClass;
		$productStockroom->product_id       = $productId;
		$productStockroom->stockroom_id     = $stockroomId;
		$productStockroom->quantity         = $data['quantity'];
		$productStockroom->preorder_stock   = $data['preorder_stock'];
		$productStockroom->ordered_preorder = isset($data['ordered_preorder']) ? $data['ordered_preorder'] : 0;

		$query->clear()
			->select($db->qn('product_id'))
			->from($db->qn('#__redshop_product_stockroom_xref'))
			->where($db->qn('product_id') . ' = ' . $db->quote($productId))
			->where($db->qn('stockroom_id') . ' = ' . $db->quote($stockroomId));

		if ($db->setQuery($query)->loadResult())
		{
			$db->updateObject('#__redshop_product_stockroom_xref', $productStockroom, array('product_id', 'stockroom_id'));
		}
		else
		{
			$productStockroom->preorder_stock   = 0;
			$productStockroom->ordered_preorder = 0;

			$db->insertObject('#__redshop_product_stockroom_xref', $productStockroom);
		}

		return true;
	}

	/**
	 * Get product id by product number.
	 *
	 * @param   string  $productNumber  Product number
	 *
	 * @return  int
	 *
	 * @since  1.0.0
	 */
	public function getProductIdByNumber($productNumber)
	{
		$db    = $this->db;
		$query = $db->getQuery(true)
			->select($db->qn('product_id'))
			->from($db->qn('#__redshop_product'))
			->where($db->qn('product_number') . ' = ' . $db->q($productNumber));

		return $db->setQuery($query)->loadResult();
	}
}
