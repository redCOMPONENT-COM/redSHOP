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
 * Redshop Templates field.
 *
 * @since  2.1.0
 */
class RedshopFormFieldTemplate extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $type = 'Template';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  array  The field input markup.
	 */
	protected function getOptions()
	{
		$section = isset($this->element['section']) ? (string) $this->element['section'] : null;

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('id'))
			->select($db->qn('name'))
			->from($db->qn('#__redshop_template'))
			->where($db->qn('published') . ' = 1');

		if (null !== $section)
		{
			$query->where($db->qn('section') . ' = ' . $db->q($section));
		}

		$items   = $db->setQuery($query)->loadObjectList();
		$options = array();

		if (count($items) > 0)
		{
			foreach ($items as $item)
			{
				$option    = JHTML::_('select.option', $item->id, $item->name);
				$options[] = $option;
			}
		}

		return array_merge(parent::getOptions(), $options);
	}
}
