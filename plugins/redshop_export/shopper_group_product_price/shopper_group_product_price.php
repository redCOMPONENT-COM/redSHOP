<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Plugin\AbstractExportPlugin;

JLoader::import('redshop.library');

/**
 * Plugins redSHOP Export Shopper Group Product Price
 *
 * @since  1.0
 */
class PlgRedshop_ExportShopper_Group_Product_Price extends AbstractExportPlugin
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

		return '';
	}

	/**
	 * Event run when user click on Start Export
	 *
	 * @return  number
	 *
	 * @since  1.0.0
	 */
	public function onAjaxShopper_Group_Product_Price_Start()
	{
		RedshopHelperAjax::validateAjaxRequest();

		$this->writeData($this->getHeader(), 'w+');

		return (int) $this->getTotal();
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

		$input = JFactory::getApplication()->input;
		$limit = $input->getInt('limit', 0);
		$start = $input->getInt('start', 0);

		return $this->exporting($start, $limit);
	}

	/**
	 * Event run on export process
	 *
	 * @return  number
	 *
	 * @since  1.0.0
	 */
	public function onAjaxShopper_Group_Product_Price_Complete()
	{
		$this->downloadFile();

		JFactory::getApplication()->close();
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
