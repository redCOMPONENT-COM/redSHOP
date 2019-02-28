<?php
/**
 * @package     Redshop.Library
 * @subpackage  Entity
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

/**
 * Field Entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       2.0.6
 */
class RedshopEntityField extends RedshopEntity
{
	/**
	 * @var    array
	 *
	 * @since  2.0.6
	 */
	protected $fieldValues;

	/**
	 * Method for get field values
	 *
	 * @return  array
	 *
	 * @since   2.0.6
	 */
	public function getFieldValues()
	{
		if (null == $this->fieldValues)
		{
			$this->loadFieldValues();
		}

		return $this->fieldValues;
	}

	/**
	 * Method for get group of this field
	 *
	 * @return  null|RedshopEntityField_Group
	 *
	 * @since   2.1.0
	 */
	public function getGroup()
	{
		if (!$this->hasId() || $this->get('groupId') === null)
		{
			return null;
		}

		return RedshopEntityField_Group::getInstance((int) $this->get('groupId'));
	}

	/**
	 * Method for load field values
	 *
	 * @return  self
	 *
	 * @since   2.0.6
	 */
	protected function loadFieldValues()
	{
		if (!$this->hasId())
		{
			return $this;
		}

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_fields_value'))
			->where($db->qn('field_id') . ' = ' . (int) $this->getId())
			->order($db->qn('value_id') . ' ASC');

		$this->fieldValues = $db->setQuery($query)->loadObjectList();

		return $this;
	}
}
