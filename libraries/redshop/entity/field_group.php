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
 * Field Group Entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       2.1.0
 */
class RedshopEntityField_Group extends RedshopEntity
{
	/**
	 * List of fields
	 *
	 * @var RedshopEntitiesCollection
	 */
	protected $fields;

	/**
	 * Method for get fields associate with this group
	 *
	 * @return  RedshopEntitiesCollection
	 *
	 * @since   2.1.0
	 */
	public function getFields()
	{
		if (null === $this->fields)
		{
			$this->loadFields();
		}

		return $this->fields;
	}

	/**
	 * Method for load fields associate with this field group
	 *
	 * @return  self
	 *
	 * @since   2.1.0
	 */
	protected function loadFields()
	{
		$this->fields = new RedshopEntitiesCollection;

		if (!$this->hasId())
		{
			return $this;
		}

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select($db->qn('id'))
			->from($db->qn('#__redshop_fields'))
			->where($db->qn('groupId') . ' = ' . $this->getId());

		$result = $db->setQuery($query)->loadColumn();

		if (empty($result))
		{
			return $this;
		}

		foreach ($result as $fieldId)
		{
			$this->fields->add(RedshopEntityField::getInstance($fieldId));
		}

		return $this;
	}
}
