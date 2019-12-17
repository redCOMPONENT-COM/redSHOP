<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Form.Field
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * Redshop Users Search field.
 *
 * @since  1.0
 */
class RedshopFormFieldUsers extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $type = 'Users';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  array  The field input markup.
	 */
	protected function getOptions()
	{
		$addressType = isset($this->element['address_type']) ? (string) $this->element['address_type'] : false;

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select($db->qn('u.id', 'value'))
			->select(
				'CONCAT(' . $db->qn('ru.firstname') . ','
				. $db->quote(' ') . ','
				. $db->qn('ru.lastname') . ','
				. $db->quote(' (') . ','
				. $db->qn('u.username') . ','
				. $db->quote(')') . ') AS ' . $db->qn('text')
			)
			->from($db->qn('#__users', 'u'))
			->leftJoin($db->qn('#__redshop_users_info', 'ru') . ' ON ' . $db->qn('u.id') . ' = ' . $db->qn('ru.user_id'));

		if ($addressType !== false)
		{
			$query->where($db->qn('ru.address_type') . ' = ' . $db->quote($addressType));
		}

		$options = $db->setQuery($query)->loadObjectList();

		return array_merge(parent::getOptions(), $options);
	}
}
