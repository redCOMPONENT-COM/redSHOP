<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
JLoader::import('redshop.library');
/**
 * Table Field
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table
 * @since       2.0.6
 */
class RedshopTableField extends RedshopTable
{
	/**
	 * The table name without the prefix. Ex: cursos_courses
	 *
	 * @var  string
	 */
	protected $_tableName = 'redshop_fields';

	/**
	 * @var integer
	 */
	public $id;

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var integer
	 */
	public $type;

	/**
	 * @var integer
	 */
	public $groupId;

	/**
	 * @var integer
	 */
	public $ordering;

	/**
	 * Checks that the object is valid and able to be stored.
	 *
	 * This method checks that the parent_id is non-zero and exists in the database.
	 * Note that the root node (parent_id = 0) cannot be manipulated with this class.
	 *
	 * @return  boolean  True if all checks pass.
	 */
	protected function doCheck()
	{
		if (empty($this->name))
		{
			return false;
		}

		if (empty($this->title))
		{
			return false;
		}

		if (!parent::doCheck())
		{
			return false;
		}

		$this->name = str_replace(" ", "_", $this->name);

		// Set 'rs' prefix to field name
		list($prefix) = explode("_", $this->name);

		if ($prefix != 'rs')
		{
			$this->name = "rs_" . $this->name;
		}

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('COUNT(*) AS cnt')
			->from($db->qn('#__redshop_fields'))
			->where($db->qn('name') . ' = ' . $db->quote($this->name))
			->where($db->qn('id') . ' != ' . (int) $this->id);

		$db->setQuery($query);
		$result = $db->loadResult();

		if ((boolean) $result)
		{
			$this->setError(JText::_('COM_REDSHOP_FIELDS_ALLREADY_EXIST'));

			return false;
		}

		if (!$this->id)
		{
			$query = $db->getQuery(true)
				->select('COUNT(*) + 1')
				->from($db->qn('#__redshop_fields'));

			$this->ordering = (int) $db->setQuery($query)->loadResult();
		}

		return true;
	}

	/**
	 * Do the database store.
	 *
	 * @param   boolean  $updateNulls  True to update null values as well.
	 *
	 * @return  boolean
	 * @throws  Exception
	 */
	protected function doStore($updateNulls = false)
	{
		if (!$this->groupId)
		{
			$this->groupId = null;
		}

		if (!parent::doStore($updateNulls))
		{
			return false;
		}

		if ($this->type == 0 || $this->type == RedshopHelperExtrafields::TYPE_TEXT || $this->type == RedshopHelperExtrafields::TYPE_TEXT_AREA)
		{
			$id[] = (int) $this->id;
			$this->deleteFieldValues($id, 'field_id');
		}
		else
		{
			$this->saveFieldValues($this->id);
		}

		return true;
	}

	/**
	 * Delete one or more registers
	 *
	 * @param   mixed  $pk  Array of ids or ids comma separated
	 *
	 * @return  boolean     Deleted successfully?
	 */
	protected function doDelete($pk = null)
	{
		$db = $this->getDbo();

		if (!parent::doDelete($pk))
		{
			return false;
		}

		if (is_array($pk))
		{
			$pk = implode(',', $pk);
		}

		// Remove fields_data
		$query = $db->getQuery(true)
			->delete($db->qn('#__redshop_fields_data'))
			->where($db->qn('fieldid') . ' IN (' . $pk . ')');

		$db->setQuery($query);

		if (!$db->execute())
		{
			$this->setError($db->getErrorMsg());
		}

		return true;
	}

	/**
	 * Method to delete all values related to a field or array of fields
	 *
	 * @param   array   $ids    An array of field ids.
	 * @param   string  $field  The field column to check for deleting.
	 *
	 * @return  boolean         True if successful, false if an error occurs.
	 *
	 * @since   2.0.6
	 */
	protected function deleteFieldValues($ids, $field)
	{
		$db  = $this->getDbo();
		$ids = implode(',', $ids);

		$query = $db->getQuery(true)
			->delete($db->qn('#__redshop_fields_value'))
			->where($db->qn($field) . ' IN (' . $ids . ')');

		if (!$db->setQuery($query)->execute())
		{
			$this->setError($db->getErrorMsg());

			return false;
		}

		return true;
	}

	/**
	 * Method to save all values related to a field
	 *
	 * @param   int  $id  Id of field.
	 *
	 * @return  boolean   True if successful, false if an error occurs.
	 * @throws  Exception
	 *
	 * @since   2.0.6
	 */
	protected function saveFieldValues($id)
	{
		$db          = JFactory::getDbo();
		$valueIds    = array();
		$extraNames  = array();
		$extraValues = array();

		// Get input
		$app   = JFactory::getApplication();
		$post  = $app->input->post;
		$total = 0;

		if (is_array($post->get('value_id')))
		{
			$extraValues = $post->getString('extra_value', '');
			$valueIds    = $post->get('value_id', array(), 'array');

			if ($this->type == RedshopHelperExtrafields::TYPE_IMAGE_SELECT || $this->type == RedshopHelperExtrafields::TYPE_IMAGE_WITH_LINK)
			{
				$extraNames = JFactory::getApplication()->input->files->get('extra_name_file', array(), 'array');
				$total      = count((array)$extraNames);
			}
			else
			{
				$extraNames = $post->get('extra_name', '', 'raw');
				$total      = count((array) $extraNames);
			}
		}

		// Do not reset values if we are ordering
		$task = $app->input->get('task');

		if ($task != 'fields.saveOrderAjax' && $task != 'saveOrderAjax')
		{
			$fieldDataIds = RedshopEntityField::getInstance($id)->getFieldValues();

			if (count($fieldDataIds) > 0)
			{
				$fid = array();

				foreach ($fieldDataIds as $fieldDataId)
				{
					$fid[] = $fieldDataId->value_id;
				}

				$delFieldIds = array_diff($fid, $valueIds);

				if (count($delFieldIds) > 0)
				{
					$this->deleteFieldValues($delFieldIds, 'value_id');
				}
			}
		}

		for ($j = 0; $j < $total; $j++)
		{
			$filename = $extraNames[$j];

			if ($this->type == RedshopHelperExtrafields::TYPE_IMAGE_SELECT || $this->type == RedshopHelperExtrafields::TYPE_IMAGE_WITH_LINK)
			{
				if ($extraValues[$j] != "" && $extraNames[$j]['name'] != "" && $extraNames[$j]['error'] == 0)
				{
					$filename = RedshopHelperMedia::cleanFileName($extraNames[$j]['name']);

					$source      = $extraNames[$j]['tmp_name'];
					$destination = REDSHOP_FRONT_IMAGES_RELPATH . 'extrafield/' . $filename;

					JFile::upload($source, $destination);
				}
			}

			if ($extraNames[$j]['error'] == 0 || !isset($extraNames[$j]['error']))
			{
				if (empty($valueIds[$j]))
				{
					$obj = new stdClass;
					$obj->field_id = (int)$id;
					$obj->field_name = $filename;
					$obj->field_value = $extraValues[$j];
					$db->insertObject('#__redshop_fields_value', $obj);
				}
				else
				{
					$obj = new stdClass;
					$obj->value_id = $valueIds[$j];
					$obj->field_value = $extraValues[$j];
					$obj->field_name = $filename;
					$db->updateObject('#__redshop_fields_value', $obj,  array('value_id'));
				}
			}
			else
			{
				$obj = new stdClass;
				$obj->value_id = $valueIds[$j];
				$obj->field_value = $extraValues[$j];
				$obj->field_name = $filename;
				$db->updateObject('#__redshop_fields_value', $obj,array('value_id'));
			}
		}

		return true;
	}
}
