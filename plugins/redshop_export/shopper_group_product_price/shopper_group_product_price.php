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

JLoader::import('redshop.library');

/**
 * Plugins redSHOP Export Shopper Group Product Price
 *
 * @since  1.0
 */
class PlgRedshop_ExportShopper_Group_Product_Price extends Export\AbstractBase
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
	public function onAjaxShopper_Group_Product_Price_Config()
	{
		RedshopHelperAjax::validateAjaxRequest();

		$this->config();
	}

	/**
	 * Event run when user click on Start Export
	 *
	 * @return  string
	 *
	 * @since  1.0.0
	 */
	public function onAjaxShopper_Group_Product_Price_Start()
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
	public function onAjaxShopper_Group_Product_Price_Export()
	{
		RedshopHelperAjax::validateAjaxRequest();

		$this->export();
	}

	/**
	 * Event run on export process
	 *
	 * @return  string
	 *
	 * @since  1.0.0
	 */
	public function onAjaxShopper_Group_Product_Price_Complete()
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

		$query = $db->getQuery(true)
			->select(
				array(
					$db->qn('p.product_number'),
					$db->qn('p.product_name'),
					$db->qn('pp.product_price'),
					$db->qn('pp.price_quantity_start'),
					$db->qn('pp.price_quantity_end'),
					$db->qn('pp.discount_price'),
					$db->qn('pp.discount_start_date'),
					$db->qn('pp.discount_end_date'),
					$db->qn('s.shopper_group_id'),
					$db->qn('s.shopper_group_name')
				)
			)
			->from($db->qn('#__redshop_product_price', 'pp'))
			->leftjoin(
				$db->qn('#__redshop_product', 'p') . ' ON ' . $db->qn('p.product_id') . '=' . $db->qn('pp.product_id')
			)
			->leftjoin(
				$db->qn('#__redshop_shopper_group', 's') . ' ON ' . $db->qn('s.shopper_group_id') . '=' . $db->qn('pp.shopper_group_id')
			)
			->where($db->qn('p.product_number') . ' != ' . $db->quote(''));

		return $query;
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
			'product_number','product_name','product_price','price_quantity_start','price_quantity_end','discount_price',
			'discount_start_date','discount_end_date','shopper_group_id','shopper_group_name'
		);
	}
}
