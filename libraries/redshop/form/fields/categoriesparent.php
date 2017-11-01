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
class JFormFieldCategoriesParent extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $type = 'CategoriesParent';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   1.6
	 */
	protected function getOptions()
	{
		// Initialise variables.
		$options = array();
		$name    = (string) $this->element['name'];

		// Let's get the id for the current item, either category or content item.
		$input     = JFactory::getApplication()->input;
		$extension = 'com_redshop';

		// For categories the old category is the category id 0 for new category.
		if ($this->element['parent'])
		{
			$oldCat = $input->get('id', 0);
		}
		else
			// For items the old category is the category they are in when opened or 0 if new.
		{
			$oldCat = $this->form->getValue($name);
		}

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('a.id AS value, a.name AS text, a.level, a.published')
			->from('#__redshop_category AS a')
			->join('LEFT', $db->quoteName('#__redshop_category') . ' AS b ON a.lft > b.lft AND a.rgt < b.rgt');

		if ($this->element['parent'])
		{
			// Prevent parenting to children of this item.
			if ($id = $this->form->getValue('id'))
			{
				$query->join('LEFT', $db->quoteName('#__redshop_category') . ' AS p ON p.id = ' . (int) $id)
					->where('NOT(a.lft >= p.lft AND a.rgt <= p.rgt)');

				$rowQuery = $db->getQuery(true);
				$rowQuery->select('a.id AS value, a.name AS text, a.level, a.parent_id')
					->from('#__redshop_category AS a')
					->where('a.id = ' . (int) $id);
				$db->setQuery($rowQuery);
				$row = $db->loadObject();
			}
		}

		$query->where('a.published IN (0,1)')
			->group('a.id, a.name, a.level, a.lft, a.rgt, a.parent_id')
			->order('a.lft ASC');

		// Get the options.
		$db->setQuery($query);

		try
		{
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}

		// Pad the option text with spaces using depth level as a multiplier.
		for ($i = 0, $n = count($options); $i < $n; $i++)
		{
			// Translate ROOT
			if ($this->element['parent'] == true)
			{
				if ($options[$i]->level == 0)
				{
					$options[$i]->text = JText::_('JGLOBAL_ROOT_PARENT');
				}
			}

			if ($options[$i]->published == 1)
			{
				$options[$i]->text = str_repeat('- ', $options[$i]->level) . $options[$i]->text;
			}
			else
			{
				$options[$i]->text = str_repeat('- ', $options[$i]->level) . '[' . $options[$i]->text . ']';
			}
		}

		// Get the current user object.
		$user = JFactory::getUser();

		// For new items we want a list of categories you are allowed to create in.
		if ($oldCat == 0)
		{
			foreach ($options as $i => $option)
			{
				/*
				 * To take save or create in a category you need to have create rights for that category unless the item is already in that category.
				 * Unset the option if the user isn't authorised for it. In this field assets are always categories.
				 */
				if ($user->authorise('core.create', $extension . '.category.' . $option->value) != true)
				{
					unset($options[$i]);
				}
			}
		}
		// If you have an existing category id things are more complex.
		else
		{
			foreach ($options as $i => $option)
			{
				/*
				 * If you are only allowed to edit in this category but not edit.state, you should not get any
				 * option to change the category parent for a category or the category for a content item,
				 * but you should be able to save in that category.
				 */
				if ($user->authorise('core.edit.state', $extension . '.category.' . $oldCat) != true)
				{
					if ($option->value != $oldCat)
					{
						echo 'y';
						unset($options[$i]);
					}
				}
				/*
				 * However, if you can edit.state you can also move this to another category for which you have
				 * create permission and you should also still be able to save in the current category.
				 */
				elseif (($user->authorise('core.create', $extension . '.category.' . $option->value) != true)
					&& $option->value != $oldCat
				)
				{
					echo 'x';
					unset($options[$i]);
				}
			}
		}

		if (isset($row) && !isset($options[0]) && $row->parent_id == '1')
		{
			$parent       = new stdClass;
			$parent->text = JText::_('JGLOBAL_ROOT_PARENT');
			array_unshift($options, $parent);
		}

		// Merge any additional options in the XML definition.
		return array_merge(parent::getOptions(), $options);
	}
}
