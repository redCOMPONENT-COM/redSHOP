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
class JFormFieldCategoryList extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $type = 'Categorylist';

	/**
	 * Method to get the field input markup for a generic list.
	 *
	 * @return  string  The field input markup.
	 */
	public function getInput()
	{
		$db     = JFactory::getDbo();
		$user   = RFactory::getUser();
		$ignore = $this->getAttribute('ignoreCats', array());
		$allow  = $this->getAttribute('allow_cids', null);

		// Get the categories list
		$query = $db->getQuery(true)
			->select('c.*')
			->from($db->qn('#__redshop_category', 'c'))
			->where($db->qn('c.published') . ' = ' . $db->q(1))
			->where($db->qn('c.level') . ' > 0')
			->order($db->qn('c.lft'));

		if (!empty($ignore))
		{
			if (is_string($ignore))
			{
				$query->where($db->qn('c.id') . ' NOT IN (' . $ignore . ')');
			}
			elseif (is_array($ignore))
			{
				$query->where($db->qn('c.id') . ' NOT IN (' . implode(',', $ignore) . ')');
			}
		}

		if (!empty($allow))
		{
			if (is_string($allow))
			{
				$query->where($db->qn('c.id') . ' IN (' . $allow . ')');
			}
			elseif (is_array($allow))
			{
				$query->where($db->qn('c.id') . ' IN (' . implode(',', $allow) . ')');
			}
		}

		$db->setQuery($query);
		$categories = $db->loadObjectList();

		// Prepare options list
		$options    = array();
		$options    = array_merge(parent::getOptions(), $options);
		$permission = $this->getAttribute('permission');

		foreach ($categories as $category)
		{
			if (!empty($permission))
			{
				continue;
			}

			$optionText  = str_repeat(' -', $category->level - 1) . ' ' . $category->name;
			$optionValue = $category->id;
			$options[] = JHTML::_('select.option', $optionValue, $optionText);
		}

		$attr = '';

		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
		$attr .= $this->multiple ? ' multiple="multiple"' : '';
		$attr .= $this->placeholder ? ' placeholder="' . (string) $this->element['placeholder'] . '"' : '';

		if ($this->multiple && !is_array($this->value))
		{
			if ($value = ReditemHelperCustomfield::isJsonValue($this->value))
			{
				$this->value = $value;
			}
			else
			{
				$this->value = explode(",", $this->value);
			}
		}

		// Initialize JavaScript field attributes.
		$attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

		return JHTML::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);
	}
}
