<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Table Field
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table
 * @since       __DEPLOY_VERSION__
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
	 * Checks that the object is valid and able to be stored.
	 *
	 * This method checks that the parent_id is non-zero and exists in the database.
	 * Note that the root node (parent_id = 0) cannot be manipulated with this class.
	 *
	 * @return  boolean  True if all checks pass.
	 */
	protected function doCheck()
	{
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
					->where($db->qn('name') . ' = ' .  $db->q($this->name))
					->where($db->qn('id') . ' != ' .  $this->id);

		$db->setQuery($query);
		$result = $db->loadResult();

		if ((boolean) $result)
		{
			$this->_error = JText::_('COM_REDSHOP_FIELDS_ALLREADY_EXIST');
			JError::raiseWarning('', $this->_error);

			return false;
		}

		if (!$this->id)
		{
			$query = $db->getQuery(true)
						->select('COUNT(*)+1')
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
	 */
	protected function doStore($updateNulls = false)
	{
		$db = JFactory::getDbo();

		if (!parent::doStore($updateNulls))
		{
			return false;
		}

		if ($this->type == 0 || $this->type == 1 || $this->type == 2)
		{
			$id[] = $this->id;
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
	 * @param   string/array  $pk  Array of ids or ids comma separated
	 *
	 * @return  boolean  Deleted successfuly?
	 */
	protected function doDelete($pk = null)
	{
		$db  = $this->getDbo();

		if (!parent::doDelete($pk))
		{
			return false;
		}

		if (is_array($pk))
		{
			$pk = implode(',', $pk);
		}

		// remove fields_data
		$query_field_data = $db->getQuery(true)
								->delete($db->qn('#__redshop_fields_data'))
								->where($db->qn('fieldid') . 'IN (' . $pk . ')');

		$db->setQuery($query_field_data);

		if (!$db->execute())
		{
			$this->setError($db->getErrorMsg());
		}

		return true;
	}

	/**
	 * Method to delete all values related to a field or array of fields
	 *
	 * @param   array  	$ids   	 An array of field ids.
	 * @param   string  $field   The field column to check for deleting.
	 *
	 * @return  boolean  True if successful, false if an error occurs.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function deleteFieldValues($ids, $field)
	{
		$db  = $this->getDbo();
		$ids = implode(',', $ids);

		$query = $db->getQuery(true)
				->delete($db->qn('#__redshop_fields_value'))
				->where("$field IN (" . $ids . ")");

		$db->setQuery($query);

		if (!$db->execute())
		{
			$this->setError($db->getErrorMsg());

			return false;
		}

		return true;
	}

	/**
	 * Method to save all values related to a field
	 *
	 * @param   int  $id   	 Id field.
	 *
	 * @return  boolean  True if successful, false if an error occurs.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function saveFieldValues($id)
	{
		$db  = $this->getDbo();
		$extra_field = extra_field::getInstance();
		$value_id = array();
		$extra_name = array();
		$extra_value = array();

		// Get input
		$app   = JFactory::getApplication();
		$post = $app->input->post;

		if (is_array($post->get('value_id')))
		{
			// $extra_value = JRequest::getVar('extra_value', '', 'post', 'string', JREQUEST_ALLOWRAW);
			$extra_value = $post->getString('extra_value', '');
			$value_id = $post->get('value_id', array(), 'array');

			if ($this->type == 11 || $this->type == 13)
			{
				$extra_name = JRequest::getVar('extra_name_file', '', 'files', 'array');
				$total = count($extra_name['name']);
			}
			else
			{
				$extra_name = $post->get('extra_name', '', 'raw');
				$total = count($extra_name);
			}
		}

		$filed_data_id = $extra_field->getFieldValue($id);

		if (count($filed_data_id) > 0)
		{
			$fid = array();

			foreach ($filed_data_id as $f)
			{
				$fid[] = $f->value_id;
			}

			$del_fid = array_diff($fid, $value_id);

			if (count($del_fid) > 0)
			{
				$this->deleteFieldValues($del_fid, 'value_id');
			}
		}

		for ($j = 0; $j < $total; $j++)
		{
			$set = "";

			if ($this->type == 11 || $this->type == 13)
			{
				if ($extra_value[$j] != "" && $extra_name['name'][$j] != "")
				{
					$filename = RedShopHelperImages::cleanFileName($extra_name['name'][$j]);

					$src = $extra_name['tmp_name'][$j];
					$dest = REDSHOP_FRONT_IMAGES_RELPATH . 'extrafield/' . $filename;

					JFile::upload($src, $dest);

					$set = " field_name='" . $filename . "', ";
				}
			}
			else
			{
				$filename = $extra_name[$j];
				$set = " field_name='" . $filename . "', ";
			}

			if ($value_id[$j] == "")
			{
				$query = $db->getQuery(true)
							->insert($db->qn('#__redshop_fields_value'))
							->columns($db->qn(array('field_id', 'field_name', 'field_value')))
							->values((int) $id . ', ' . $db->q($filename) . ', ' . $db->q($extra_value[$j]));
			}
			else
			{
				$query = $db->getQuery(true)
							->update($db->qn('#__redshop_fields_value'))
							->set($set . ' ' . $db->qn('field_value') . ' = ' . $db->q($extra_value[$j]))
							->where($db->qn('value_id') . ' = ' . $value_id[$j]) ;
			}

			if (!$db->setQuery($query)->execute())
			{
				$this->setError($db->getErrorMsg());

				return false;
			}
		}

		return true;
	}
}
