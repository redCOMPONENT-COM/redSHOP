<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Plugin\AbstractExportPlugin;

JLoader::import('redshop.library');

/**
 * Plugins redSHOP Export Attribute
 *
 * @since  1.0
 */
class PlgRedshop_ExportAttribute extends AbstractExportPlugin
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
	public function onAjaxAttribute_Config()
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
	public function onAjaxAttribute_Start()
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
	public function onAjaxAttribute_Export()
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
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	public function onAjaxAttribute_Complete()
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

		// Attributes query
		$attributeQuery = $db->getQuery(true)
			->select($db->qn('p.product_number'))
			->select($db->qn('a.attribute_name'))
			->select($db->qn('a.ordering', 'attribute_ordering'))
			->select($db->qn('a.allow_multiple_selection'))
			->select($db->qn('a.hide_attribute_price'))
			->select($db->qn('a.attribute_required'))
			->select($db->qn('a.display_type'))
			->select($db->quote('') . ' AS ' . $db->qn('property_name'))
			->select($db->quote('') . ' AS ' . $db->qn('property_stock'))
			->select($db->quote('') . ' AS ' . $db->qn('property_ordering'))
			->select($db->quote('') . ' AS ' . $db->qn('property_virtual_number'))
			->select($db->quote('') . ' AS ' . $db->qn('setdefault_selected'))
			->select($db->quote('') . ' AS ' . $db->qn('setdisplay_type'))
			->select($db->quote('') . ' AS ' . $db->qn('oprand'))
			->select($db->quote('') . ' AS ' . $db->qn('property_price'))
			->select($db->quote('') . ' AS ' . $db->qn('property_image'))
			->select($db->quote('') . ' AS ' . $db->qn('property_main_image'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_color_name'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_stock'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_color_ordering'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_setdefault_selected'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_color_title'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_virtual_number'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_color_oprand'))
			->select($db->quote('') . ' AS ' . $db->qn('required_sub_attribute'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_color_price'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_color_image'))
			->select($db->quote('0') . ' AS ' . $db->qn('delete'))
			->from($db->qn('#__redshop_product', 'p'))
			->innerJoin($db->qn('#__redshop_product_attribute', 'a') . ' ON ' . $db->qn('p.product_id') . ' = ' . $db->qn('a.product_id'));

		// Properties query
		$propertiesQuery = $db->getQuery(true)
			->select($db->qn('p.product_number'))
			->select($db->qn('a.attribute_name'))
			->select($db->quote('') . ' AS ' . $db->qn('attribute_ordering'))
			->select($db->quote('') . ' AS ' . $db->qn('allow_multiple_selection'))
			->select($db->quote('') . ' AS ' . $db->qn('hide_attribute_price'))
			->select($db->quote('') . ' AS ' . $db->qn('attribute_required'))
			->select($db->quote('') . ' AS ' . $db->qn('display_type'))
			->select($db->qn('ap.property_name'))
			->select(
				'(SELECT GROUP_CONCAT(CONCAT('
				. $db->qn('att_stock.stockroom_id') . ',' . $db->quote(':') . ',' . $db->qn('att_stock.quantity') . ')'
				. ' SEPARATOR ' . $db->quote('#') . ') FROM ' . $db->qn('#__redshop_product_attribute_stockroom_xref', 'att_stock')
				. ' WHERE ' . $db->qn('att_stock.section_id') . ' = ' . $db->qn('ap.property_id')
				. ' AND ' . $db->qn('att_stock.section') . ' = ' . $db->quote('property') . ') AS ' . $db->qn('property_stock')
			)
			->select($db->qn('ap.ordering', 'property_ordering'))
			->select($db->qn('ap.property_number', 'property_virtual_number'))
			->select($db->qn('ap.setdefault_selected'))
			->select($db->qn('ap.setdisplay_type'))
			->select($db->qn('ap.oprand'))
			->select($db->qn('ap.property_price'))
			->select($db->qn('ap.property_image'))
			->select($db->qn('ap.property_main_image'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_color_name'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_stock'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_color_ordering'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_setdefault_selected'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_color_title'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_virtual_number'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_color_oprand'))
			->select($db->quote('') . ' AS ' . $db->qn('required_sub_attribute'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_color_price'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_color_image'))
			->select($db->quote('0') . ' AS ' . $db->qn('delete'))
			->from($db->qn('#__redshop_product', 'p'))
			->innerJoin($db->qn('#__redshop_product_attribute', 'a') . ' ON ' . $db->qn('p.product_id') . ' = ' . $db->qn('a.product_id'))
			->innerJoin(
				$db->qn('#__redshop_product_attribute_property', 'ap') . ' ON ' . $db->qn('a.attribute_id') . ' = ' . $db->qn('ap.attribute_id')
			)
			->order($db->qn('product_number') . ',' . $db->qn('property_ordering'));

		// Sub-properties query
		$subPropertiesQuery = $db->getQuery(true)
			->select($db->qn('p.product_number'))
			->select($db->qn('a.attribute_name'))
			->select($db->quote('') . ' AS ' . $db->qn('attribute_ordering'))
			->select($db->quote('') . ' AS ' . $db->qn('allow_multiple_selection'))
			->select($db->quote('') . ' AS ' . $db->qn('hide_attribute_price'))
			->select($db->quote('') . ' AS ' . $db->qn('attribute_required'))
			->select($db->quote('') . ' AS ' . $db->qn('display_type'))
			->select($db->qn('ap.property_name'))
			->select($db->quote('') . ' AS ' . $db->qn('property_stock'))
			->select($db->quote('') . ' AS ' . $db->qn('property_ordering'))
			->select($db->quote('') . ' AS ' . $db->qn('property_virtual_number'))
			->select($db->quote('') . ' AS ' . $db->qn('setdefault_selected'))
			->select($db->quote('') . ' AS ' . $db->qn('setdisplay_type'))
			->select($db->quote('') . ' AS ' . $db->qn('oprand'))
			->select($db->quote('') . ' AS ' . $db->qn('property_price'))
			->select($db->quote('') . ' AS ' . $db->qn('property_image'))
			->select($db->quote('') . ' AS ' . $db->qn('property_main_image'))
			->select($db->qn('sp.subattribute_color_name'))
			->select(
				'(SELECT GROUP_CONCAT(CONCAT('
				. $db->qn('stocksp.stockroom_id') . ',' . $db->quote(':') . ',' . $db->qn('stocksp.quantity') . ')'
				. ' SEPARATOR ' . $db->quote('#') . ') FROM ' . $db->qn('#__redshop_product_attribute_stockroom_xref', 'stocksp')
				. ' WHERE ' . $db->qn('stocksp.section_id') . ' = ' . $db->qn('sp.subattribute_color_id')
				. ' AND ' . $db->qn('stocksp.section') . ' = ' . $db->quote('subproperty') . ') AS ' . $db->qn('subattribute_stock')
			)
			->select($db->qn('sp.ordering', 'subattribute_color_ordering'))
			->select($db->qn('sp.setdefault_selected', 'subattribute_setdefault_selected'))
			->select($db->qn('sp.subattribute_color_title'))
			->select($db->qn('sp.subattribute_color_number', 'subattribute_virtual_number'))
			->select($db->qn('sp.oprand', 'subattribute_color_oprand'))
			->select($db->qn('ap.setrequire_selected', 'required_sub_attribute'))
			->select($db->qn('sp.subattribute_color_price'))
			->select($db->qn('sp.subattribute_color_image'))
			->select($db->quote('0') . ' AS ' . $db->qn('delete'))
			->from($db->qn('#__redshop_product', 'p'))
			->innerJoin($db->qn('#__redshop_product_attribute', 'a') . ' ON ' . $db->qn('p.product_id') . ' = ' . $db->qn('a.product_id'))
			->innerJoin(
				$db->qn('#__redshop_product_attribute_property', 'ap') . ' ON ' . $db->qn('a.attribute_id') . ' = ' . $db->qn('ap.attribute_id')
			)
			->innerJoin(
				$db->qn('#__redshop_product_subattribute_color', 'sp') . ' ON ' . $db->qn('ap.property_id') . ' = ' . $db->qn('sp.subattribute_id')
			)
			->order($db->qn('product_number') . ',' . $db->qn('subattribute_color_ordering'));

		$attributeQuery->union($propertiesQuery)->union($subPropertiesQuery);

		return $attributeQuery;
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
			'product_number','attribute_name','attribute_ordering','allow_multiple_selection','hide_attribute_price','attribute_required',
			'display_type','property_name','property_stock','property_ordering','property_virtual_number','setdefault_selected','setdisplay_type',
			'oprand','property_price','property_image','property_main_image','subattribute_color_name','subattribute_stock',
			'subattribute_color_ordering','subattribute_setdefault_selected','subattribute_color_title','subattribute_virtual_number',
			'subattribute_color_oprand','required_sub_attribute','subattribute_color_price','subattribute_color_image','delete'
		);
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
			->from('(' . $query . ') AS ' . $db->qn('attribute_data'));

		return (int) $this->db->setQuery($newQuery)->loadResult();
	}

	/**
	 * Method for do some stuff for data return. (Like image path,...)
	 *
	 * @param   array  &$data  Array of data.
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	protected function processData(&$data)
	{
		if (empty($data))
		{
			return;
		}

		foreach ($data as $index => $item)
		{
			$item = (array) $item;

			// Property image
			if (!empty($item['property_image']))
			{
				$item['property_image'] = REDSHOP_FRONT_IMAGES_ABSPATH . 'product_attributes/' . $item['property_image'];
			}

			// Property main image
			if (!empty($item['property_main_image']))
			{
				$item['property_main_image'] = REDSHOP_FRONT_IMAGES_ABSPATH . 'property/' . $item['property_main_image'];
			}

			// Sub-attribute image
			if (!empty($item['subattribute_color_image']))
			{
				$item['subattribute_color_image'] = REDSHOP_FRONT_IMAGES_ABSPATH . 'subcolor/' . $item['subattribute_color_image'];
			}

			$data[$index] = $item;
		}
	}
}
