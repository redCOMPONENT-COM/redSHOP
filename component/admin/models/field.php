<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Redshop Field Model
 *
 * @package     Redshop.Backend
 * @subpackage  Models.Field
 * @since       2.0.6
 */
class RedshopModelField extends RedshopModelForm
{
	/**
	 * Method for get all exist field names except specific field ID.
	 *
	 * @param   integer  $fieldId  Field ID.
	 *
	 * @return  array              List of exist field name.
	 *
	 * @since   2.0.6
	 */
	public function getExistFieldNames($fieldId = 0)
	{
		$db    = $this->getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('f.name'))
			->from($db->qn('#__redshop_fields', 'f'));

		if ($fieldId)
		{
			$query->where($db->qn('id') . ' <> ' . $fieldId);
		}

		return $db->setQuery($query)->loadColumn();
	}

	/**
	 * Method for mass assign group into multiple fields
	 *
	 * @param   mixed  $fieldIds  Field Id
	 * @param   mixed  $groupId   Group Id
	 *
	 * @return  boolean
	 * @throws  Exception
	 *
	 * @since   2.1.0
	 */
	public function massAssignGroup($fieldIds, $groupId = null)
	{
		$fieldIds = !is_array($fieldIds) ? array($fieldIds) : $fieldIds;
		$fieldIds = \Joomla\Utilities\ArrayHelper::toInteger($fieldIds);

		if (empty($fieldIds))
		{
			return false;
		}

		// @TODO: Need change to use RedshopTableFields for update after fix error ordering field lost values.
		$db    = $this->getDbo();
		$query = $db->getQuery(true)
			->update($db->qn('#__redshop_fields'))
			->where($db->qn('id') . ' IN (' . implode(',', $fieldIds) . ')');

		$groupId = (int) $groupId;

		if ($groupId)
		{
			$query->set($db->qn('groupId') . ' = ' . (int) $groupId);
		}
		else
		{
			$query->set($db->qn('groupId') . ' = NULL');
		}

		return $db->setQuery($query)->execute();
	}
}
