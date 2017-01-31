<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Plugin\AbstractImportPlugin;

JLoader::import('redshop.library');

/**
 * Plugin redSHOP Import Field
 *
 * @since  1.0
 */
class PlgRedshop_ImportField extends AbstractImportPlugin
{
	/**
	 * @var string
	 */
	protected $primaryKey = 'field_id';

	/**
	 * @var string
	 */
	protected $nameKey = 'field_name_field';

	/**
	 * Event run when user load config for export this data.
	 *
	 * @return  string
	 *
	 * @since  1.0.0
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
	 * @since  1.0.0
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
		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/tables');

		return RedshopTable::getInstance('Field', 'RedshopTable');
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
			->select($db->qn('field_id'))
			->from($db->qn('#__redshop_fields'))
			->where($db->qn('field_section') . ' = ' . $db->quote($data['field_section']))
			->where($db->qn('field_name') . ' = ' . $db->q($data['field_name_field']));
		$fieldId = (int) $db->setQuery($query)->loadResult();

		// Import field.
		if (!empty($data['field_title']))
		{
			$fieldObject                      = new stdClass;
			$fieldObject->field_title         = $data['field_title'];
			$fieldObject->field_name          = $data['field_name_field'];
			$fieldObject->field_type          = $data['field_type'];
			$fieldObject->field_desc          = $data['field_desc'];
			$fieldObject->field_class         = $data['field_class'];
			$fieldObject->field_section       = $data['field_section'];
			$fieldObject->field_maxlength     = $data['field_maxlength'];
			$fieldObject->field_cols          = $data['field_cols'];
			$fieldObject->field_rows          = $data['field_rows'];
			$fieldObject->field_size          = $data['field_size'];
			$fieldObject->field_show_in_front = $data['field_show_in_front'];
			$fieldObject->required            = $data['required'];
			$fieldObject->published           = $data['published'];

			if ($fieldId)
			{
				$fieldObject->field_id = $fieldId;
				$db->updateObject('#__redshop_fields', $fieldObject, 'field_id');
			}
			elseif ($db->insertObject('#__redshop_fields', $fieldObject, 'field_id'))
			{
				$fieldId = $fieldObject->field_id;
			}
			else
			{
				return false;
			}
		}

		// Import field data.
		if (!empty($data['data_txt']))
		{
			$object = new stdClass;
			$object->fieldid = $fieldId;
			$object->data_txt = $data['data_txt'];
			$object->itemid = $productId;
			$object->section = $data['section'];

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
			$object = new stdClass;
			$object->field_id = $fieldId;
			$object->field_value = $data['field_value'];
			$object->field_name = $data['field_name'];

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
