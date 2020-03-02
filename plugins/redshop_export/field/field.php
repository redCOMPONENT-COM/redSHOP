<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Plugin\AbstractExportPlugin;

JLoader::import('redshop.library');

/**
 * Plugins redSHOP Export Field
 *
 * @since  1.0
 */
class PlgRedshop_ExportField extends AbstractExportPlugin
{
	/**
	 * Event run when user load config for export this data.
	 *
	 * @return  string
	 *
	 * @since   1.0.0
	 * @throws  Exception
	 *
	 * @TODO: Need to load XML File instead
	 */
	public function onAjaxField_Config()
	{
		\Redshop\Helper\Ajax::validateAjaxRequest();

		\Redshop\Ajax\Response::getInstance()->respond();
	}

	/**
	 * Event run when user click on Start Export
	 *
	 * @return  integer
	 *
	 * @since   1.0.0
	 * @throws  Exception
	 */
	public function onAjaxField_Start()
	{
		\Redshop\Helper\Ajax::validateAjaxRequest();

		$this->writeData($this->getHeader(), 'w+');

		return (int) $this->getTotal();
	}

	/**
	 * Event run on export process
	 *
	 * @return  integer
	 *
	 * @since   1.0.0
	 * @throws  Exception
	 */
	public function onAjaxField_Export()
	{
		\Redshop\Helper\Ajax::validateAjaxRequest();

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
	 * @since   1.0.0
	 * @throws  Exception
	 */
	public function onAjaxField_Complete()
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
		$db       = $this->db;
		$query    = $this->getQuery();
		$newQuery = $db->getQuery(true)
			->select('COUNT(*)')
			->from('(' . $query . ') AS ' . $db->qn('field_union'));

		return (int) $this->db->setQuery($newQuery)->loadResult();
	}

	/**
	 * Method for do some stuff for data return. (Like image path,...)
	 *
	 * @param   array  $data  Array of data.
	 *
	 * @return  void
	 *
	 * @since   1.0.1
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

			foreach ($item as $column => $value)
			{
				if (!in_array($column, array('desc', 'data_txt', 'field_value')))
				{
					continue;
				}

				$item[$column] = str_replace(array("\n", "\r"), "", $value);
			}

			$data[$index] = $item;
		}
	}
}
