<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_categories
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

// No direct access
defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * Class JFormFieldRedshopCategoryRemove
 *
 * @since  1.5
 */
class JFormFieldAesirItems extends JFormFieldList
{
	/**
	 * @access private
	 */
	protected $name = 'aesiritems';

	/**
	 * Method to get the field input markup for a generic list.
	 *
	 * @return  string  The field input markup.
	 */
	public function getInput()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('i.title'))
			->select($db->qn('i.id'))
			->select($db->qn('t.title', 'type'))
			->from($db->qn('#__reditem_items', 'i'))
			->leftjoin($db->qn('#__reditem_types', 't') .' ON ' . $db->qn('i.type_id') . ' = ' . $db->qn('t.id'))
			->order($db->qn('i.ordering'));

		$items = $db->setQuery($query)->loadObjectList();
		$options = array();

		if (!$this->element['multiple'])
		{
			$options[] = JHTML::_('select.option', '', JText::_('COM_REDSHOP_SELECT_TYPE'), 'value', 'text');
		}

		if (count($items) > 0)
		{
			foreach ($items as $item)
			{
				$option = JHTML::_('select.option', $item->id, $item->title . '(' . $item->type . ')');
				$options[] = $option;
			}
		}

		$options = array_merge(parent::getOptions(), $options);
		$attr = '';

		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
		$attr .= $this->element['multiple'] ? ' multiple' : '';

		// Initialize JavaScript field attributes.
		$attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

		return JHTML::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);
	}
}
