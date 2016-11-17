<?php
/**
 * @package     Redshopb.Site
 * @subpackage  Fields
 *
 * @copyright   Copyright (C) 2012 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

/**
 * Supports an HTML grouped select list of menu item grouped by menu
 *
 * @since  1.6.21
 */
class JFormFieldFirstlevelmenuitem extends JFormFieldMenuitem
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	public $type = 'Firstlevelmenuitem';

	/**
	 * Method to attach a JForm object to the field.
	 *
	 * @param   SimpleXMLElement  $element  The SimpleXMLElement object representing the <field /> tag for the form field object.
	 * @param   mixed             $value    The form field value to validate.
	 * @param   string            $group    The field name group control value. This acts as as an array container for the field.
	 *                                      For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                      full field name would end up being "bar[foo]".
	 *
	 * @return  boolean  True on success.
	 *
	 * @see     JFormField::setup()
	 */
	public function setup(SimpleXMLElement $element, $value, $group = null)
	{
		$result = parent::setup($element, $value, $group);

		if (isset($this->element['menuTypeField']))
		{
			$menuTypeField = (string) $this->element['menuTypeField'];

			if (isset($this->form->getData()->get('params')->$menuTypeField))
			{
				$this->menuType = $this->form->getData()->get('params')->$menuTypeField;
			}
		}

		return $result;
	}

	/**
	 * Method to get the field option groups.
	 *
	 * @return  array  The field option objects as a nested array in groups.
	 */
	protected function getGroups()
	{
		$groups = array();

		$menuType = $this->menuType;

		// Get the menu items.
		$items = MenusHelper::getMenuLinks($menuType, 0, 0, $this->published, $this->language);

		// Build group for a specific menu type.
		if ($menuType)
		{
			// Initialize the group.
			$groups[$menuType] = array();

			// Build the options array.
			foreach ($items as $link)
			{
				if ($link->level == 1)
				{
					$groups[$menuType][] = JHtml::_('select.option', $link->value, $link->text, 'value', 'text', in_array($link->type, $this->disable));
				}
			}
		}

		// Build groups for all menu types.
		else
		{
			// Build the groups arrays.
			foreach ($items as $menu)
			{
				// Initialize the group.
				$groups[$menu->menutype] = array();

				// Build the options array.
				foreach ($menu->links as $link)
				{
					if ($link->level == 1)
					{
						$groups[$menu->menutype][] = JHtml::_(
							'select.option', $link->value, $link->text, 'value', 'text',
							in_array($link->type, $this->disable)
						);
					}
				}
			}
		}

		// Merge any additional groups in the XML definition.
		$groups = array_merge(parent::getGroups(), $groups);

		return $groups;
	}
}
