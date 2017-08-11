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
class JFormFieldRproducts extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $type = 'Rproducts';

	/**
	 * Method to get the field input markup for a generic list.
	 *
	 * @return  array  The field input markup.
	 */
	public function getInput()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('product_id'))
			->select($db->qn('product_name'))
			->select($db->qn('product_number'))
			->from($db->qn('#__redshop_product'))
			->order($db->qn('product_name'));

		if (isset($this->element['parent']) && $this->element['parent'] === '0')
		{
			$query->where($db->qn('product_parent_id') . ' != 0');
		}

		$items = $db->setQuery($query)->loadObjectList();
		$options = array();

		if (count($items) > 0)
		{
			foreach ($items as $item)
			{
				$option = JHTML::_('select.option', $item->product_id, $item->product_name . '(' . $item->product_number . ')');
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
