<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Plugin\Import;

JLoader::import('redshop.library');

/**
 * Plugin redSHOP Import Field
 *
 * @since  1.0
 */
class PlgRedshop_ImportField extends Import\AbstractBase
{
	/**
	 * @var string
	 *
	 * @since   2.0.3
	 */
	protected $primaryKey = 'id';

	/**
	 * @var string
	 *
	 * @since   2.0.3
	 */
	protected $nameKey = 'name_field';

	/**
	 * List of alias columns. For backward compatible. Example array('category_id' => 'id')
	 *
	 * @var    array
	 *
	 * @since  2.0.6
	 */
	protected $aliasColumns = array(
		'field_id'            => 'id',
		'field_title'         => 'title',
		'field_name'          => 'name',
		'field_type'          => 'type',
		'field_desc'          => 'desc',
		'field_class'         => 'class',
		'field_section'       => 'section',
		'field_maxlength'     => 'maxlength',
		'field_cols'          => 'cols',
		'field_rows'          => 'rows',
		'field_size'          => 'size',
		'field_show_in_front' => 'show_in_front',
	);

	/**
	 * Event run when user load config for export this data.
	 *
	 * @return  string
	 *
	 * @since   1.0.0
	 */
	public function onAjaxField_Config()
	{
		RedshopHelperAjax::validateAjaxRequest();

		return '';
	}

	/**
	 * Event run when run importing.
	 *
	 * @return  mixed
	 *
	 * @since   1.0.0
	 */
	public function onAjaxField_Import()
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
		return RedshopTable::getInstance('Field', 'RedshopTable');
	}

	/**
	 * Process import data.
	 *
	 * @param   JTable  $table  Header array
	 * @param   array   $data   Data array
	 *
	 * @return  boolean
	 *
	 * @since   1.0.0
	 */
	public function processImport($table, $data)
	{
		$db    = $this->db;
		$query = $db->getQuery(true);

		$productId = 0;

		if (!empty($data['section']) && $data['section'] == 1)
		{
			$query->clear()
				->select($db->quoteName('product_id'))
				->from($db->quoteName('#__redshop_product'))
				->where($db->quoteName('product_number') . ' = ' . $db->quote($data['data_number']));
			$productId = $db->setQuery($query)->loadResult();
		}

		// Get field id
		$query->clear()
			->select($db->qn('id'))
			->from($db->qn('#__redshop_fields'))
			->where($db->qn('section') . ' = ' . $db->quote($data['section']))
			->where($db->qn('name') . ' = ' . $db->q($data['name_field']));
		$fieldId = (int) $db->setQuery($query)->loadResult();

		// Import field.
		if (!empty($data['title']))
		{
			$fieldObject                = new stdClass;
			$fieldObject->title         = $data['title'];
			$fieldObject->name          = $data['name_field'];
			$fieldObject->type          = $data['type'];
			$fieldObject->desc          = $data['desc'];
			$fieldObject->class         = $data['class'];
			$fieldObject->section       = $data['section'];
			$fieldObject->maxlength     = $data['maxlength'];
			$fieldObject->cols          = $data['cols'];
			$fieldObject->rows          = $data['rows'];
			$fieldObject->size          = $data['size'];
			$fieldObject->show_in_front = $data['show_in_front'];
			$fieldObject->required      = $data['required'];
			$fieldObject->published     = $data['published'];

			if ($fieldId)
			{
				$fieldObject->id = $fieldId;
				$db->updateObject('#__redshop_fields', $fieldObject, 'id');
			}
			elseif ($db->insertObject('#__redshop_fields', $fieldObject, 'id'))
			{
				$fieldId = $fieldObject->id;
			}
			else
			{
				return false;
			}
		}

		// Import field data.
		if (!empty($data['data_txt']))
		{
			$object           = new stdClass;
			$object->fieldid  = $fieldId;
			$object->data_txt = $data['data_txt'];
			$object->itemid   = $productId;
			$object->section  = $data['section'];

			// Load data id
			$query->clear()
				->select('data_id')
				->from($db->qn('#__redshop_fields_data'))
				->where($db->qn('fieldid') . ' = ' . $db->quote($fieldId))
				->where($db->qn('itemid') . ' = ' . $db->quote($productId));
			$dataId = $db->setQuery($query)->loadResult();

			if (!$dataId)
			{
				$db->insertObject('#__redshop_fields_data', $object);
			}
			else
			{
				$object->data_id = $dataId;
				$db->updateObject('#__redshop_fields_data', $object, 'data_id');
			}
		}

		// Import field value
		if (!empty($data['field_name']))
		{
			$object              = new stdClass;
			$object->field_id    = $fieldId;
			$object->field_value = $data['field_value'];
			$object->field_name  = $data['field_name'];

			// Get Field value ID
			$query->clear()
				->select('value_id')
				->from($db->qn('#__redshop_fields_value'))
				->where($db->qn('field_id') . ' = ' . $db->quote($fieldId))
				->where('field_name = ' . $db->quote($data['field_name']));
			$fieldValueId = $db->setQuery($query)->loadResult();

			if (!$fieldValueId)
			{
				$db->insertObject('#__redshop_fields_value', $object);
			}
			else
			{
				$object->value_id = $fieldValueId;
				$db->updateObject('#__redshop_fields_value', $object, 'value_id');
			}
		}

		return true;
	}
}
