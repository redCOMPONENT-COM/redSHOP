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
 * Plugins redSHOP Export Field
 *
 * @since  1.0
 */
class PlgRedshop_ExportField extends Export\AbstractBase
{
	/**
	 * Event run when user load config for export this data.
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function onAjaxField_Config()
	{
		RedshopHelperAjax::validateAjaxRequest();

		$this->config();
	}

	/**
	 * Event run when user click on Start Export
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	public function onAjaxField_Start()
	{
		RedshopHelperAjax::validateAjaxRequest();

		$this->start();
	}

	/**
	 * Event run on export process
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function onAjaxField_Export()
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
	public function onAjaxField_Complete()
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

		// Fields query
		$fieldQuery = $db->getQuery(true)
			->select($db->qn('f.id'))
			->select($db->qn('f.title'))
			->select($db->qn('f.name', 'name_field'))
			->select($db->qn('f.type'))
			->select($db->qn('f.desc'))
			->select($db->qn('f.class'))
			->select($db->qn('f.section', 'field_section'))
			->select($db->qn('f.maxlength'))
			->select($db->qn('f.cols'))
			->select($db->qn('f.rows'))
			->select($db->qn('f.size'))
			->select($db->qn('f.show_in_front'))
			->select($db->qn('f.required'))
			->select($db->qn('f.published'))
			->select($db->quote('') . ' AS ' . $db->qn('data_id'))
			->select($db->quote('') . ' AS ' . $db->qn('data_txt'))
			->select($db->quote('') . ' AS ' . $db->qn('itemid'))
			->select($db->quote('') . ' AS ' . $db->qn('section'))
			->select($db->quote('') . ' AS ' . $db->qn('value_id'))
			->select($db->quote('') . ' AS ' . $db->qn('field_value'))
			->select($db->quote('') . ' AS ' . $db->qn('field_name'))
			->select($db->quote('') . ' AS ' . $db->qn('data_number'))
			->from($db->qn('#__redshop_fields', 'f'));

		// Fields query
		$fieldDataQuery = $db->getQuery(true)
			->select($db->qn('f.id'))
			->select($db->quote('') . ' AS ' . $db->qn('title'))
			->select($db->qn('f.name', 'name_field'))
			->select($db->quote('') . ' AS ' . $db->qn('type'))
			->select($db->quote('') . ' AS ' . $db->qn('desc'))
			->select($db->quote('') . ' AS ' . $db->qn('class'))
			->select($db->qn('f.section', 'field_section'))
			->select($db->quote('') . ' AS ' . $db->qn('maxlength'))
			->select($db->quote('') . ' AS ' . $db->qn('cols'))
			->select($db->quote('') . ' AS ' . $db->qn('rows'))
			->select($db->quote('') . ' AS ' . $db->qn('size'))
			->select($db->quote('') . ' AS ' . $db->qn('show_in_front'))
			->select($db->quote('') . ' AS ' . $db->qn('required'))
			->select($db->quote('') . ' AS ' . $db->qn('published'))
			->select($db->qn('d.data_id'))
			->select($db->qn('d.data_txt'))
			->select($db->qn('d.itemid'))
			->select($db->qn('d.section'))
			->select($db->quote('') . ' AS ' . $db->qn('value_id'))
			->select($db->quote('') . ' AS ' . $db->qn('field_value'))
			->select($db->quote('') . ' AS ' . $db->qn('field_name'))
			->select($db->qn('p.product_number', 'data_number'))
			->from($db->qn('#__redshop_fields_data', 'd'))
			->innerJoin($db->qn('#__redshop_fields', 'f') . ' ON ' . $db->qn('f.id') . ' = ' . $db->qn('d.fieldid'))
			->innerJoin($db->qn('#__redshop_product', 'p') . ' ON ' . $db->qn('d.itemid') . ' = ' . $db->qn('p.product_id'))
			->where($db->qn('d.section') . ' != ' . $db->quote(''))
			->order($db->qn('f.id'));

		// Fields query
		$fieldValueQuery = $db->getQuery(true)
			->select($db->qn('f.id'))
			->select($db->quote('') . ' AS ' . $db->qn('title'))
			->select($db->qn('f.name', 'name_field'))
			->select($db->quote('') . ' AS ' . $db->qn('type'))
			->select($db->quote('') . ' AS ' . $db->qn('desc'))
			->select($db->quote('') . ' AS ' . $db->qn('class'))
			->select($db->qn('f.section', 'field_section'))
			->select($db->quote('') . ' AS ' . $db->qn('maxlength'))
			->select($db->quote('') . ' AS ' . $db->qn('cols'))
			->select($db->quote('') . ' AS ' . $db->qn('rows'))
			->select($db->quote('') . ' AS ' . $db->qn('size'))
			->select($db->quote('') . ' AS ' . $db->qn('show_in_front'))
			->select($db->quote('') . ' AS ' . $db->qn('required'))
			->select($db->quote('') . ' AS ' . $db->qn('published'))
			->select($db->quote('') . ' AS ' . $db->qn('data_id'))
			->select($db->quote('') . ' AS ' . $db->qn('data_txt'))
			->select($db->quote('') . ' AS ' . $db->qn('itemid'))
			->select($db->quote('') . ' AS ' . $db->qn('section'))
			->select($db->qn('v.value_id'))
			->select($db->qn('v.field_value'))
			->select($db->qn('v.field_name'))
			->select($db->quote('') . ' AS ' . $db->qn('data_number'))
			->from($db->qn('#__redshop_fields_value', 'v'))
			->innerJoin($db->qn('#__redshop_fields', 'f') . ' ON ' . $db->qn('f.id') . ' = ' . $db->qn('v.field_id'))
			->order($db->qn('v.value_id'));

		$fieldQuery->union($fieldDataQuery)->union($fieldValueQuery);

		return $fieldQuery;
	}

	/**
	 * Method for get headers data.
	 *
	 * @return  mixed
	 *
	 * @since   1.0.0
	 */
	protected function getHeader()
	{
		return array(
			'id', 'title', 'name_field', 'type', 'desc', 'class', 'field_section','maxlength', 'cols',
			'rows', 'size', 'show_in_front', 'required', 'published', 'data_id', 'data_txt', 'itemid', 'section', 'value_id',
			'field_value', 'field_name', 'data_number'
		);
	}

	/**
	 * Method for get total count of data.
	 *
	 * @return  integer
	 *
	 * @since   1.0.0
	 */
	protected function getTotal()
	{
		$db = $this->db;
		$query = $this->getQuery();
		$newQuery = $db->getQuery(true)
			->select('COUNT(*)')
			->from('(' . $query . ') AS ' . $db->qn('field_union'));

		return (int) $this->db->setQuery($newQuery)->loadResult();
	}
}
