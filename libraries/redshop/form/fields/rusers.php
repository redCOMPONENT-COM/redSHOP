<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_categories
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

/**
 * Form Field class for the Joomla Framework.
 *
 * @since  1.6
 */
class JFormFieldRusers extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $type = 'Rusers';

	/**
	 * Method to get the field input markup for a generic list.
	 *
	 * @return  string  The field input markup.
	 */
	public function getInput()
	{
		$label = JText::_('COM_REDSHOP_USER');

		if ($this->value)
		{
			$user = JFactory::getUser($this->value);

			if ($user->name)
			{
				$label = $user->name;
			}
		}

		return JHTML::_('redshopselect.jFormSearch', $this->value, $this->name,
							array(
								'select2.ajaxOptions' => array('typeField' => ', user:1'),
								'select2.options' => array('placeholder' => $label)
							)
				);
	}
}
