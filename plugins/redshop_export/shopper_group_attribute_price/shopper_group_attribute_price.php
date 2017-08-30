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
 * Plugins redSHOP Export Shopper Group Attribute Price
 *
 * @since  1.0
 */
class PlgRedshop_ExportShopper_Group_Attribute_Price extends Export\AbstractBase
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
	public function onAjaxShopper_Group_Attribute_Price_Config()
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
	public function onAjaxShopper_Group_Attribute_Price_Start()
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
	public function onAjaxShopper_Group_Attribute_Price_Export()
	{
		RedshopHelperAjax::validateAjaxRequest();

		$this->export();
	}

	/**
	 * Event run on export process
	 *
	 * @return  number
	 *
	 * @since  1.0.0
	 */
	public function onAjaxShopper_Group_Attribute_Price_Complete()
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

		$attributesQuery = $db->getQuery(true)
			->select(
				array(
					$db->qn('ap.section'),
					$db->qn('product.product_number'),
					$db->qn('product.product_name'),
					$db->qn('product.product_price'),
					$db->qn('p.property_number', 'attribute_number'),
					$db->qn('p.property_name', 'product_attribute'),
					$db->qn('ap.product_price', 'attribute_price'),
					$db->qn('ap.price_quantity_start'),
					$db->qn('ap.price_quantity_end'),
					$db->qn('ap.discount_price'),
					$db->qn('ap.discount_start_date'),
					$db->qn('ap.discount_end_date'),
					$db->qn('s.shopper_group_id'),
					$db->qn('s.shopper_group_name')
				)
			)
			->from($db->qn('#__redshop_product_attribute_price', 'ap'))
			->leftjoin(
				$db->qn('#__redshop_product_attribute_property', 'p')
				. ' ON ' . $db->qn('p.property_id') . '=' . $db->qn('ap.section_id')
			)
			->leftjoin(
				$db->qn('#__redshop_shopper_group', 's')
				. ' ON ' . $db->qn('s.shopper_group_id') . '=' . $db->qn('ap.shopper_group_id')
			)
			->leftjoin(
				$db->qn('#__redshop_product_attribute', 'pa')
				. ' ON ' . $db->qn('pa.attribute_id') . '=' . $db->qn('p.attribute_id')
			)
			->leftjoin(
				$db->qn('#__redshop_product', 'product')
				. ' ON ' . $db->qn('product.product_id') . '=' . $db->qn('pa.product_id')
			)
			->where($db->qn('ap.section') . ' = ' . $db->quote('property'));

		$propertiesQuery = $db->getQuery(true)
			->select(
				array(
					$db->qn('ap.section'),
					$db->qn('product.product_number'),
					$db->qn('product.product_name'),
					$db->qn('product.product_price'),
					$db->qn('sp.subattribute_color_number', 'attribute_number'),
					$db->qn('sp.subattribute_color_name', 'product_attribute'),
					$db->qn('ap.product_price', 'attribute_price'),
					$db->qn('ap.price_quantity_start'),
					$db->qn('ap.price_quantity_end'),
					$db->qn('ap.discount_price'),
					$db->qn('ap.discount_start_date'),
					$db->qn('ap.discount_end_date'),
					$db->qn('s.shopper_group_id'),
					$db->qn('s.shopper_group_name')
				)
			)
			->from($db->qn('#__redshop_product_attribute_price', 'ap'))
			->leftjoin(
				$db->qn('#__redshop_product_subattribute_color', 'sp')
				. ' ON ' . $db->qn('sp.subattribute_color_id') . '=' . $db->qn('ap.section_id')
			)
			->leftjoin(
				$db->qn('#__redshop_shopper_group', 's')
				. ' ON ' . $db->qn('s.shopper_group_id') . '=' . $db->qn('ap.shopper_group_id')
			)
			->leftjoin(
				$db->qn('#__redshop_product_attribute_property', 'p')
				. ' ON ' . $db->qn('sp.subattribute_id') . '=' . $db->qn('p.property_id')
			)
			->leftjoin(
				$db->qn('#__redshop_product_attribute', 'pa')
				. ' ON ' . $db->qn('pa.attribute_id') . '=' . $db->qn('p.attribute_id')
			)
			->leftjoin(
				$db->qn('#__redshop_product', 'product')
				. ' ON ' . $db->qn('product.product_id') . '=' . $db->qn('pa.product_id')
			)
			->where($db->qn('ap.section') . ' = ' . $db->quote('subproperty'));

		$attributesQuery->union($propertiesQuery);

		return $attributesQuery;
	}

	/**
	 * Method for get total count of data.
	 *
	 * @return int
	 *
	 * @since  1.0.0
	 */
	protected function getTotal()
	{
		$db = $this->db;
		$query = $this->getQuery();
		$newQuery = $db->getQuery(true)
			->select('COUNT(*)')
			->from('(' . $query . ') AS ' . $db->qn('attribute_price'));

		return (int) $this->db->setQuery($newQuery)->loadResult();
	}

	/**
	 * Method for get headers data.
	 *
	 * @return array|boolean
	 *
	 * @since  1.0.0
	 */
	protected function getHeader()
	{
		return array(
			'section', 'product_number', 'product_name', 'product_price', 'attribute_number', 'product_attribute', 'attribute_price',
			'price_quantity_start', 'price_quantity_end', 'discount_price', 'discount_start_date', 'discount_end_date', 'shopper_group_id',
			'shopper_group_name'
		);
	}
}
