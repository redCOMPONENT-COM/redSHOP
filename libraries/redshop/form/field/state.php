<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Form.Field
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * Redshop States field.
 *
 * @since  1.0
 */
class RedshopFormFieldState extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $type = 'State';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getOptions()
	{
		$key = isset($this->element['idfield']) ? (string) $this->element['idfield'] : 'id';

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn($key, 'value'))
			->select($db->qn('state_name', 'text'))
			->select($db->qn('country_id'))
			->from($db->qn('#__redshop_state', 's'));

		if (!empty($this->form->getData()->get('tax_country')))
    		{
			$query->leftJoin($db->qn('#__redshop_country', 'c') . ' ON ' . $db->qn('s.country_id') . ' = ' . $db->qn('c.id'))
			      ->where($db->qn('c.country_3_code') . ' = ' . $db->q($this->form->getData()->get('tax_country')));
    		}

		$options = $db->setQuery($query)->loadObjectList();

		$fieldName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname);

		foreach ($options as $option)
		{
			$option->text     = JText::alt((string) $option->text, $fieldName);
			$option->value    = (string) $option->value;
			$option->disable  = false;
			$option->class    = '';
			$option->selected = false;
			$option->checked  = false;
			$option->country  = $option->country_id;
		}

		reset($options);

		$parentOptions = parent::getOptions();
		$options = array_merge($parentOptions, $options);

		return $options;
	}
}
