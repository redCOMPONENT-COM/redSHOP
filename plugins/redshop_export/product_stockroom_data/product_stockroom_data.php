<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Plugin\Export;
use Redshop\Ajax\Response;

JLoader::import('redshop.library');

/**
 * Plugins redSHOP Export Product Stockroom Data
 *
 * @since  1.0
 */
class PlgRedshop_ExportProduct_Stockroom_Data extends Export\AbstractBase
{
	/**
	 * Event run when user load config for export this data.
	 *
	 * @return  string
	 *
	 * @since  1.0.0
	 *
	 * @TODO: Need to load XML File instead
	 */
	public function onAjaxProduct_Stockroom_Data_Config()
	{
		RedshopHelperAjax::validateAjaxRequest();

		$this->config();
	}

	/**
	 * Event run when user click on Start Export
	 *
	 * @return  number
	 *
	 * @since  1.0.0
	 */
	public function onAjaxProduct_Stockroom_Data_Start()
	{
		RedshopHelperAjax::validateAjaxRequest();

		$this->start();
	}

	/**
	 * Event run on export process
	 *
	 * @return  int
	 *
	 * @since  1.0.0
	 */
	public function onAjaxProduct_Stockroom_Data_Export()
	{
		RedshopHelperAjax::validateAjaxRequest();

		$this->export();
	}

	/**
	 * Event run on export process
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	public function onAjaxProduct_Stockroom_Data_Complete()
	{
		RedshopHelperAjax::validateAjaxRequest();

		return $this->convertFile();
	}

	/**
	 * Method for get query
	 *
	 * @return \JDatabaseQuery
	 *
	 * @since  1.0.0
	 */
	protected function getQuery()
	{
		$db = $this->db;

		return $db->getQuery(true)
			->select($db->qn('p.product_number'))
			->select($db->qn('p.product_name'))
			->select($db->qn('s.stockroom_name'))
			->select($db->qn('ref.quantity'))
			->select($db->qn('ref.preorder_stock'))
			->select($db->qn('ref.ordered_preorder'))
			->from($db->qn('#__redshop_product_stockroom_xref', 'ref'))
			->innerJoin($db->qn('#__redshop_product', 'p') . ' ON ' . $db->qn('p.product_id') . ' = ' . $db->qn('ref.product_id'))
			->innerJoin($db->qn('#__redshop_stockroom', 's') . ' ON ' . $db->qn('s.stockroom_id') . ' = ' . $db->qn('ref.stockroom_id'))
			->order($db->qn('p.product_name'));
	}

	/**
	 * Method for get headers data.
	 *
	 * @return array|bool
	 *
	 * @since  1.0.0
	 */
	protected function getHeader()
	{
		return array(
			'product_number', 'product_name', 'stockroom_name', 'quantity', 'preorder_stock', 'ordered_preorder'
		);
	}
}
